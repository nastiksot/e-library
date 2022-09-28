import Joi from "@hapi/joi";
import Locale from "../index";

export const localeKeys = {
    prefix:    Joi.string().required(),
    locale:    Joi.string().required(),
    title:     Joi.string().required(),
    enCountry: Joi.string().required(),
};

export const localeSchema = Joi.object().keys(localeKeys);

export default class ObjectToLocaleFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !sliderSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Decision}
     */
    static create(data) {
        Joi.assert(data, localeSchema);

        return new Locale(
            data.prefix,
            data.locale,
            data.title,
            data.enCountry,
        );
    }
}
