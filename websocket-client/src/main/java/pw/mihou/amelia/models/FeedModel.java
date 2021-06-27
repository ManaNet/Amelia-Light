package pw.mihou.amelia.models;

import pw.mihou.amelia.db.FeedManager;

import java.util.Date;

public class FeedModel {

    private final String unique;
    private final String feedURL;
    private final String webhook;
    private Date date;

    public FeedModel(String unique, String feedURL, String webhook, Date date) {
        this.unique = unique;
        this.feedURL = feedURL;
        this.webhook = webhook;
        this.date = date;
    }

    public FeedModel setPublishedDate(Date date) {
        this.date = date;
        FeedManager.updateModel(unique, "date", date);
        return this;
    }

    public String getUnique() {
        return unique;
    }

    public String getFeedURL() {
        return feedURL;
    }

    public String getWebhook() {
        return webhook;
    }

    public Date getDate() {
        return date;
    }
}
