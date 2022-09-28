import Joi from "@hapi/joi";
import WHERE_TO_BUY from "../../../dictionary/where-to-buy";
import AlternativeProduct from "../index";

export const alternativeProductKeys = {
    id: Joi.number().required(),
    sku: Joi.string().required(),
    title: Joi.string().required(),
    name: Joi.string().optional().allow(null),
    image: Joi.string().optional().allow(null),
    where_to_buy: Joi.string().valid(...Object.values(WHERE_TO_BUY)).required(),
    // special_shop: Joi.boolean().optional().allow(null),
};

export const alternativeProductSchema = Joi.object().keys(alternativeProductKeys);

export default class ObjectToAlternativeProductFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !alternativeProductSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {AlternativeProduct}
     */
    static create(data) {
        Joi.assert(data, alternativeProductSchema);

        return new AlternativeProduct(
            data.id,
            data.sku,
            data.title,
            data.name,
            data.image,
            data.where_to_buy,
            // data.special_shop,
        );
    }
}
