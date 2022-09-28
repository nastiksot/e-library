import Joi from "@hapi/joi";
import ObjectToProductFactory, {productSchema} from "../../product/object-to-product-factory";
import ProductSetProduct from "../index";
import PRODUCT_SET_PRODUCT_TYPE from "../../../dictionary/product-set-product-type";

export const productSetProductSchema = Joi.object().keys({
    id: Joi.number().required(),
    quantity: Joi.number().required(),
    product: productSchema,
    product_type: Joi.string().valid(...Object.values(PRODUCT_SET_PRODUCT_TYPE)).required(),
});

export default class ObjectToProductSetProductFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !productSetProductSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {ProductSetProduct}
     */
    static create(data) {
        Joi.assert(data, productSetProductSchema);

        return new ProductSetProduct(
            data.id,
            data.quantity,
            ObjectToProductFactory.create(data.product),
            data.product_type
        );
    }

    /**
     * @param {Product} product
     * @param {Number} productSetProductId
     * @param {Number} quantity
     * @return {ProductSetProduct}
     */
    static createFromProduct(product: Product, quantity: ?Number, productSetProductId: ?Number) {
        return new ProductSetProduct(
            productSetProductId ? productSetProductId : 0,
            quantity ? quantity : 1,
            product
        );
    }
}
