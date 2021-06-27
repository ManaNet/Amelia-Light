package pw.mihou.amelia.db;

import com.mongodb.client.MongoCollection;
import com.mongodb.client.model.Filters;
import com.mongodb.client.model.Updates;
import org.bson.Document;
import pw.mihou.amelia.io.Scheduler;
import pw.mihou.amelia.models.FeedModel;

import java.util.ArrayList;
import java.util.Collection;
import java.util.UUID;
import java.util.concurrent.CompletableFuture;

public class FeedManager {

    private static final MongoCollection<Document> db = MongoDB.collection("web", "amelia");

    public static CompletableFuture<Void> updateModel(String unique, String key, Object value) {
        return CompletableFuture.runAsync(() -> db.updateOne(Filters.eq("unique", unique),
                Updates.set(key, value)), Scheduler.getExecutorService());
    }

    public static String generateUnique() {
        String unique = UUID.randomUUID().toString().replaceAll("-", "");

        return db.find(Filters.eq("unique", unique)).first() != null ? generateUnique() : unique;
    }

    public static void addModel(FeedModel model) {
        db.insertOne(new Document().append("unique", model.getUnique()).append("feedUrl", model.getFeedURL()).append("webhook", model.getWebhook())
                .append("date", model.getDate()));
    }

    public static CompletableFuture<Collection<FeedModel>> request() {
        return CompletableFuture.supplyAsync(() -> {
            ArrayList<FeedModel> models = new ArrayList<>();
            db.find().forEach(doc -> models.add(new FeedModel(doc.getString("unique"), doc.getString("feedUrl"),
                    doc.getString("webhook"), doc.getDate("date"))));
            return models;
        });
    }

}
