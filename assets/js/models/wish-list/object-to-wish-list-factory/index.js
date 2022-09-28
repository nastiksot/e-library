import Joi from "@hapi/joi";
import ObjectToWishListProductSetFactory, {wishListProductSetSchema} from "../../wish-list-product-set/object-to-wish-list-product-set-factory";
import WishList from "../index";

export const wishListSchema = Joi.object().keys({
    id: Joi.number().required(),
    uid: Joi.string().required(),
    name: Joi.string().required(),
    product_sets: Joi.array().items(wishListProductSetSchema),
    created_at: Joi.string().required(),
    updated_at: Joi.string().required(),
});

export const userWishListSchema = Joi.object().keys({
    id: Joi.number().required(),
    uid: Joi.string().required(),
    name: Joi.string().required(),
    created_at: Joi.string().required(),
    updated_at: Joi.string().required(),
});

export default class ObjectToWishListFactory {
    static supports(data: Object): Boolean {
        return !wishListSchema.validate(data).error;
    }

    static create(data: Object): WishList {
        Joi.assert(data, wishListSchema);

        return new WishList(
            data.id,
            data.uid,
            data.name,
            data.product_sets.map(item => ObjectToWishListProductSetFactory.create(item)),
            data.created_at,
            data.updated_at
        );
    }

    static createUserWishList(data: Object): WishList {
        Joi.assert(data, userWishListSchema);

        return new WishList(
            data.id,
            data.uid,
            data.name,
            [],
            data.created_at,
            data.updated_at
        );
    }
}
