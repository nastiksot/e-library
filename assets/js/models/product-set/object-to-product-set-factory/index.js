import Joi from "@hapi/joi";
import ObjectToProductSetProductFactory, {productSetProductSchema} from "../../product-set-product/object-to-product-set-product-factory";
import ProductSet from "../index";

export const productSetKeys = {
    id: Joi.number().required(),
    icon: Joi.string().required(),
    image: Joi.string().optional().allow(null),
    youtube_video_url: Joi.string().optional().allow(null),
    title: Joi.string().required(),
    description: Joi.string().optional().allow(null),
    products: Joi.array().items(productSetProductSchema),
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
    decision_id: Joi.number().allow(null),
    decision_product_id: Joi.number().allow(null),
};

export const productSetSchema = Joi.object().keys(productSetKeys);

export default class ObjectToProductSetFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !productSetSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Product}
     */
    static create(data) {
        Joi.assert(data, productSetSchema);

        return new ProductSet(
            data.id,
            data.icon,
            data.image,
            data.youtube_video_url,
            data.title,
            data.description,
            data.products.map((item) => ObjectToProductSetProductFactory.create(item)),
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
            data.decision_id,
            data.decision_product_id,
        );
    }
}
