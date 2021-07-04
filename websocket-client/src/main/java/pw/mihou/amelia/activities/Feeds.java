package pw.mihou.amelia.activities;

import pw.mihou.amelia.Amelia;
import pw.mihou.amelia.connections.AmeliaServer;
import pw.mihou.amelia.db.FeedManager;
import pw.mihou.amelia.io.ReadRSS;
import pw.mihou.amelia.io.Scheduler;
import pw.mihou.amelia.payloads.AmeliaPayload;
import pw.mihou.amelia.wrappers.AmatsukiWrapper;

import java.util.concurrent.TimeUnit;
import java.util.concurrent.atomic.AtomicInteger;

public class Feeds {

    public static void run() {
        AtomicInteger bucket = new AtomicInteger(0);
        FeedManager.request().thenAccept(feedModels -> feedModels.forEach(feedModel ->
                Scheduler.schedule(() -> ReadRSS.getLatest(feedModel.getFeedURL()).ifPresentOrElse(item ->
                        item.getPubDate().ifPresent(date -> {
                            if (date.after(feedModel.getDate())) {
                                // Validate that the server indeed returns back an author name.
                                // or else we'll fetch it ourselves.
                                if(item.getAuthor().isBlank() || item.getAuthor().isEmpty()) {
                                    if(feedModel.getFeedURL().contains("sid=")) {
                                        item.setAuthor(AmatsukiWrapper.getStoryById(Integer.parseInt(feedModel
                                                .getFeedURL()
                                                .substring(feedModel.getFeedURL()
                                                .lastIndexOf("id=") + 3))).getCreator());
                                        AmeliaServer.sendPayload(new AmeliaPayload(item, feedModel.setPublishedDate(date)));
                                    }
                                }
                                Amelia.log.info("All nodes were notified for feed [{}].", feedModel.getUnique());
                            }
                        }), () -> Amelia.log.error("We couldn't find any results for {}.", feedModel.getFeedURL())), bucket.addAndGet(2), TimeUnit.SECONDS))).exceptionally(throwable -> {
            if (throwable != null) {
                throwable.printStackTrace();
            }
            return null;
        });
    }

}
