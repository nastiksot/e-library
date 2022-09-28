import Joi from "@hapi/joi";
import WHERE_TO_BUY from "../../../dictionary/where-to-buy";
//import ObjectToAlternativeProductFactory, {alternativeProductSchema} from "../../alternative-product/object-to-alternative-product-factory";
import Product from "../index";

export const productKeys = {
    id: Joi.number().required(),
    sku: Joi.string().required(),
    title: Joi.string().required(),
    name: Joi.string().optional().allow(null),
    description: Joi.string().optional().allow(null),
    tip: Joi.string().optional().allow(null),
    price: Joi.number().optional().allow(null),
    price_end: Joi.number().optional().allow(null),
    price_on_request: Joi.boolean().optional().allow(null),
    // special_shop: Joi.boolean().optional().allow(null),
    image: Joi.string().optional().allow(null),
    link: Joi.string().optional().allow(null),
    where_to_buy: Joi.string().valid(...Object.values(WHERE_TO_BUY)).required(),
    alternative_products: Joi.array().items(Joi.object().keys(productKeys)).optional().allow(null),
    base_product: Joi.boolean().optional().allow(null),
};

export const productSchema = Joi.object().keys(productKeys);

export default class ObjectToProductFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !productSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Product}
     */
    static create(data) {
        Joi.assert(data, productSchema);

        return new Product(
            data.id,
            data.sku,
            data.title,
            data.name,
            data.description,
            data.tip,
            data.price,
            data.price_end,
            data.price_on_request,
            // data.special_shop,
            data.image,
            data.link,
            data.where_to_buy,
            data.alternative_products.map(item => ObjectToProductFactory.create(item)),
            data.base_product,
        );
    }
}
