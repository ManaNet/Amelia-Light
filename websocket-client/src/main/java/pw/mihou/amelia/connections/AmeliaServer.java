package pw.mihou.amelia.connections;

import com.mongodb.client.model.Filters;
import io.javalin.Javalin;
import io.javalin.core.compression.CompressionStrategy;
import org.jsoup.Connection;
import org.jsoup.Jsoup;
import pw.mihou.amelia.Amelia;
import pw.mihou.amelia.activities.Feeds;
import pw.mihou.amelia.db.FeedManager;
import pw.mihou.amelia.db.MongoDB;
import pw.mihou.amelia.io.ReadRSS;
import pw.mihou.amelia.io.Scheduler;
import pw.mihou.amelia.models.FeedModel;
import pw.mihou.amelia.payloads.AmeliaPayload;
import pw.mihou.amelia.wrappers.ItemWrapper;

import java.io.IOException;
import java.util.Objects;
import java.util.concurrent.TimeUnit;

public class AmeliaServer {

    private static final int port = 9661;
    private static final String authorization = System.getenv("amelia_auth");

    private static void startFeeds() {
        Scheduler.schedule(Feeds::run, Amelia.determineNextTarget(), 10, TimeUnit.MINUTES);
        Amelia.log.debug("Module [RSS Feed] is delayed by {} minutes.", Amelia.determineNextTarget());
    }

    public static void sendPayload(AmeliaPayload payload) {
        try {
            Connection.Response response = Jsoup.connect(System.getenv("amelia_url")).data("token", authorization)
                    .data("webhook", payload.model.getWebhook())
                    .data("title", payload.wrapper.getTitle())
                    .data("author", payload.wrapper.getAuthor())
                    .data("link", payload.wrapper.getLink())
                    .method(Connection.Method.POST)
                    .execute();

            if (response.statusCode() != 204) {
                Amelia.log.error("An error occurred while trying to send payload for feed {} with webhook {}, received {} from server: {}",
                        payload.model.getFeedURL(), payload.model.getWebhook(), response.statusCode(), response.body() == null ? response.statusMessage() : response.body());
            }
        } catch (IOException exception) {
            exception.printStackTrace();
            Amelia.log.error(exception.getMessage());
        }
    }

    public static void execute() {
        Javalin app = Javalin.create(config -> {
            config.showJavalinBanner = false;
            config.autogenerateEtags = true;
            config.ignoreTrailingSlashes = true;
            config.defaultContentType = "application/json";
            config.compressionStrategy(CompressionStrategy.GZIP);
            config.wsLogger(wsHandler -> {
                wsHandler.onConnect(wsConnectContext -> Amelia.log.debug("Received connection from {}", wsConnectContext.session.getRemoteAddress().toString()));
                wsHandler.onClose(wsCloseContext -> Amelia.log.debug("Received closed connection from {} for {}", wsCloseContext.session.getRemoteAddress().toString(), wsCloseContext.reason()));
            });
        });

        app.events(event -> {
            event.serverStarting(() -> Amelia.log.debug("The server is now starting at port: {}", port));
            event.serverStarted(() -> Amelia.log.debug("The server has successfully booted up."));
            event.serverStartFailed(() -> Amelia.log.error("The server failed to start, possibly another instance at the same port is running."));
            event.serverStopping(() -> Amelia.log.debug("The server is now shutting off."));
            event.serverStopped(() -> Amelia.log.debug("The server has successfully closed."));
        }).start(port);

        app.post("/insert", ctx -> {
            if (ctx.formParam("token") != null && Objects.requireNonNull(ctx.formParam("token")).equals(authorization)) {
                if (ctx.formParam("discord") != null && ctx.formParam("scribblehub") != null) {
                    String unique = FeedManager.generateUnique();

                    ReadRSS.getLatest(fromSH(ctx.formParam("scribblehub"))).flatMap(ItemWrapper::getPubDate)
                            .ifPresentOrElse(date -> {
                                FeedModel model = new FeedModel(unique, fromSH(ctx.formParam("scribblehub")), ctx.formParam("discord"), date);
                                FeedManager.addModel(model);
                                ctx.status(200).result("Success!");
                            }, () -> ctx.status(400).result("ScribbleHub RSS Feed couldn't be reached!"));
                }
            }
        });

        app.post("/remove", ctx -> {
            if (ctx.formParam("token") != null && Objects.requireNonNull(ctx.formParam("token")).equals(authorization)) {
                if (ctx.formParam("discord") != null && ctx.formParam("scribblehub") != null) {

                    long count = MongoDB.collection("web", "amelia").deleteMany(Filters.and(
                            Filters.eq("webhook", ctx.formParam("discord")), Filters.eq("feedUrl",
                                    fromSH(ctx.formParam("scribblehub"))))).getDeletedCount();

                    ctx.status(200).result("Deleted " + count + " feeds!");
                }
            }
        });

        Runtime.getRuntime().addShutdownHook(new Thread(app::stop));
        Amelia.log.debug("All events and handlers are now ready.");

        startFeeds();
    }

    private static String fromSH(String s) {
        int id = Integer.parseInt(s.split("/")[4]);
        if (s.split("/")[3].equalsIgnoreCase("profile")) {
            return String.format("https://www.scribblehub.com/rssfeed.php?type=author&uid=%d", id);
        }

        return String.format("https://www.scribblehub.com/rssfeed.php?type=series&sid=%d", id);
    }

}
