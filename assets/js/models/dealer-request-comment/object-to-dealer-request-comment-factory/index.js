import Joi from "@hapi/joi";
import DealerRequestComment from "../index";

export const DealerRequestCommentKeys = {
    id: Joi.number().required(),
    message: Joi.string().required(),
    created_at: Joi.string().required(),
    updated_at: Joi.string().required(),
};

export const DealerRequestCommentSchema = Joi.object().keys(DealerRequestCommentKeys);

export default class ObjectToDealerRequestCommentFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !DealerRequestCommentSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {DealerRequestComment}
     */
    static create(data) {
        Joi.assert(data, DealerRequestCommentSchema);

        return new DealerRequestComment(
            data.id,
            data.message,
            data.created_at,
            data.updated_at,
        );
    }
}
