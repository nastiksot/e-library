import Joi from "@hapi/joi";
import ObjectToWishListProductSetProductFactory, {wishListProductSetProductSchema} from "../../wish-list-product-set-product/object-to-wish-list-product-set-product-factory";
import WishListProductSet from "../index";

export const wishListProductSetSchema = Joi.object().keys({
    id: Joi.number().required(),
    original_id: Joi.number().required(),
    icon: Joi.string().required(),
    image: Joi.string().required(),
    youtube_video_url: Joi.string().optional().allow(null),
    title: Joi.string().required(),
    description: Joi.string().optional().allow(null),
    products: Joi.array().items(wishListProductSetProductSchema),
    recommended: Joi.boolean(),
    icon_title: Joi.string().allow(null),
    layer1_icon_x: Joi.number().allow(null),
    layer1_icon_y: Joi.number().allow(null),
    layer1_active: Joi.boolean(),
    layer2_icon_x: Joi.number().allow(null),
    layer2_icon_y: Joi.number().allow(null),
    layer2_active: Joi.boolean(),
    layer3_icon_x: Joi.number().allow(null),
    layer3_icon_y: Joi.number().allow(null),
    layer3_active: Joi.boolean(),
    deleted_product_id: Joi.number().allow(null),
    replaced_product_id: Joi.number().allow(null),
});

export default class ObjectToWishListProductSetFactory {
    static supports(data: Object): Boolean {
        return !wishListProductSetSchema.validate(data).error;
    }

    static create(data: Object): WishListProductSet {
        Joi.assert(data, wishListProductSetSchema);

        return new WishListProductSet(
            data.id,
            data.original_id,
            data.icon,
            data.image,
            data.youtube_video_url,
            data.title,
            data.description,
            data.products.map(item => ObjectToWishListProductSetProductFactory.create(item)),
            data.recommended,
            data.icon_title,
            data.layer1_icon_x,
            data.layer1_icon_y,
            data.layer1_active,
            data.layer2_icon_x,
            data.layer2_icon_y,
            data.layer2_active,
            data.layer3_icon_x,
            data.layer3_icon_y,
            data.layer3_active,
            data.deleted_product_id,
            data.replaced_product_id,
        );
    }
}
