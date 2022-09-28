import Joi from "@hapi/joi";
import UserAccount from "../index";
import USER_ROLE from "../../../dictionary/user-role";

export const UserAccountKeys = {
    uid: Joi.string().required(),
    locale: Joi.string().required(),
    cart_add_products_url: Joi.string().required(),
    dealer_search_url: Joi.string().required(),
    id: Joi.number().optional().allow(null),
    email: Joi.string().optional().allow(null),
    roles: Joi.array().has(Object.values(USER_ROLE)).allow(null),
    first_name: Joi.string().optional().allow(null),
    last_name: Joi.string().optional().allow(null),
    accept_news: Joi.boolean().optional().allow(null),
    accept_process_personal_data: Joi.boolean().optional().allow(null),
    accept_privacy_policy: Joi.boolean().optional().allow(null),
    dealer_uid: Joi.string().optional().allow(null),
};

export const UserAccountSchema = Joi.object().keys(UserAccountKeys);

export default class ObjectToUserAccountFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !UserAccountSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {UserAccount}
     */
    static create(data) {
        Joi.assert(data, UserAccountSchema);

        return new UserAccount(
            data.uid,
            data.locale,
            data.id,
            data.email,
            data.roles,
            data.first_name,
            data.last_name,
            data.accept_news,
            data.accept_process_personal_data,
            data.accept_privacy_policy,
            data.dealer_uid,
            data.cart_add_products_url,
            data.dealer_search_url,
        );
    }
}
