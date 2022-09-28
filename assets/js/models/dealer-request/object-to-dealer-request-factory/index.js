import Joi from "@hapi/joi";
import DealerRequest from "../index";
import ObjectToCommentFactory, {DealerRequestCommentSchema} from "../../dealer-request-comment/object-to-dealer-request-comment-factory";
import DEALER_REQUEST_STATUS from "../../../dictionary/dealer-request-status";

export const DealerRequestKeys = {
    id: Joi.number().required(),
    contact_name: Joi.string().optional().allow(null),
    email: Joi.string().optional().allow(null),
    phone: Joi.string().optional().allow(null),
    address: Joi.string().optional().allow(null),
    message: Joi.string().optional().allow(null),
    status: Joi.string().valid(...Object.values(DEALER_REQUEST_STATUS)).required(),
    created_at: Joi.string().required(),
    updated_at: Joi.string().required(),
    comments: Joi.array().items(DealerRequestCommentSchema).optional().allow(null),
    dealer_uid: Joi.string().required(),
    dealer_slug: Joi.string().required(),
    wish_list_uid: Joi.string().optional().allow(null),
    user_id: Joi.number().optional().allow(null),
};

export const DealerRequestSchema = Joi.object().keys(DealerRequestKeys);

export default class ObjectToDealerRequestFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !DealerRequestSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {DealerRequest}
     */
    static create(data) {
        Joi.assert(data, DealerRequestSchema);

        return new DealerRequest(
            data.id,
            data.contact_name,
            data.email,
            data.phone,
            data.address,
            data.message,
            data.status,
            data.created_at,
            data.updated_at,
            data.comments.map(item => ObjectToCommentFactory.create(item)),
            data.dealer_uid,
            data.dealer_slug,
            data.wish_list_uid,
            data.user_id,
        );
    }
}
