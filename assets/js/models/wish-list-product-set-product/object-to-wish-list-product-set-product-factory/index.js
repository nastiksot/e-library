import Joi from "@hapi/joi";
import ObjectToProductFactory, {productSchema} from "../../product/object-to-product-factory";
import WishListProductSetProduct from "../index";
import PRODUCT_SET_PRODUCT_TYPE from "../../../dictionary/product-set-product-type";

export const wishListProductSetProductSchema = Joi.object().keys({
    id: Joi.number().required(),
    original_quantity: Joi.number().required(),
    current_quantity: Joi.number().required(),
    product: productSchema,
    product_type: Joi.string().valid(...Object.values(PRODUCT_SET_PRODUCT_TYPE)).required(),
    duplicate: Joi.boolean().optional().allow(null),
    deleted: Joi.boolean().optional().allow(null),
    replaced: Joi.boolean().optional().allow(null),
});

export default class ObjectToWishListProductSetProductFactory {
    static supports(data: Object): Boolean {
        return !wishListProductSetProductSchema.validate(data).error;
    }

    static create(data: Object): WishListProductSetProduct {
        Joi.assert(data, wishListProductSetProductSchema);

        return new WishListProductSetProduct(
            data.id,
            data.original_quantity,
            data.current_quantity,
            ObjectToProductFactory.create(data.product),
            data.product_type,
            data.duplicate,
            data.deleted,
            data.replaced,
        );
    }
}
