import Joi from "@hapi/joi";
import Dealer from "../index";

export const DealerKeys = {
    slug: Joi.string().required(),
    uid: Joi.string().required(),
    title: Joi.string().optional().allow(null),
    image: Joi.string().optional().allow(null),
    country_name: Joi.string().optional().allow(null),
    region_name: Joi.string().optional().allow(null),
    city_name: Joi.string().optional().allow(null),
    address_line_1: Joi.string().optional().allow(null),
    address_line_2: Joi.string().optional().allow(null),
    postal_code: Joi.string().optional().allow(null),
};

export const DealerSchema = Joi.object().keys(DealerKeys);

export default class ObjectToDealerFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !DealerSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Dealer}
     */
    static create(data) {
        Joi.assert(data, DealerSchema);

        return new Dealer(
            data.slug,
            data.uid,
            data.title,
            data.image,
            data.country_name,
            data.region_name,
            data.city_name,
            data.address_line_1,
            data.address_line_2,
            data.postal_code,
        );
    }
}
