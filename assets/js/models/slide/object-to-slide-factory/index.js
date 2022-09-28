import Joi from "@hapi/joi";
import Slide from "../index";
import {PRODUCT_SET_POSITION} from "../../../dictionary/decision-action";
import SLIDER_FILE_MIME_TYPE from "../../../dictionary/slider-file-mime-type";

export const slideKeys = {
    id: Joi.number().required(),
    file_name: Joi.string().optional().allow(null),
    file_mime_type: Joi.string().valid(...Object.values(SLIDER_FILE_MIME_TYPE)).optional().allow(null),
    file_mobile_name: Joi.string().optional().allow(null),
    file_mobile_mime_type: Joi.string().valid(...Object.values(SLIDER_FILE_MIME_TYPE)).optional().allow(null),
    youtube_video_url: Joi.string().optional().allow(null),
    title: Joi.string().optional().allow(null),
    sub_title: Joi.string().optional().allow(null),
    product_set_id: Joi.number().optional().allow(null),
    product_set_position: Joi.string().valid(...Object.values(PRODUCT_SET_POSITION)).optional().allow(null),
};

export const slideSchema = Joi.object().keys(slideKeys);

export default class ObjectToSlideFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !slideSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Decision}
     */
    static create(data) {
        Joi.assert(data, slideSchema);

        return new Slide(
            data.id,
            data.file_name,
            data.file_mime_type,
            data.file_mobile_name,
            data.file_mobile_mime_type,
            data.product_set_id,
            data.product_set_position,
        );
    }
}
