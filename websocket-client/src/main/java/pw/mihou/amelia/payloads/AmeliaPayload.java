package pw.mihou.amelia.payloads;

import pw.mihou.amelia.models.FeedModel;
import pw.mihou.amelia.wrappers.ItemWrapper;

public class AmeliaPayload {

    public final ItemWrapper wrapper;
    public final FeedModel model;

    public AmeliaPayload(ItemWrapper wrapper, FeedModel model) {
        this.wrapper = wrapper;
        this.model = model;
    }

}
