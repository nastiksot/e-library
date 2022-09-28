import Joi from "@hapi/joi";
import GeneralSettings from "../index";

export const generalSettingsKeys = {
    social_facebook: Joi.string().optional().allow(null),
    social_youtube: Joi.string().optional().allow(null),
    social_instagram: Joi.string().optional().allow(null),
};

export const generalSettingsSchema = Joi.object().keys(generalSettingsKeys);

export default class ObjectToGeneralSettingsFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !generalSettingsSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Decision}
     */
    static create(data) {
        Joi.assert(data, generalSettingsSchema);

        return new GeneralSettings(
            data.social_facebook,
            data.social_youtube,
            data.social_instagram,
        );
    }
}
