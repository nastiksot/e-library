import Joi from "@hapi/joi";
import Slider from "../index";
import ObjectToSlideFactory, {slideSchema} from "../../slide/object-to-slide-factory";
import SLIDER_FILE_MIME_TYPE from "../../../dictionary/slider-file-mime-type";

export const sliderKeys = {
    id: Joi.number().required(),
    file_name: Joi.string().optional().allow(null),
    file_mime_type: Joi.string().valid(...Object.values(SLIDER_FILE_MIME_TYPE)).optional().allow(null),
    file_mobile_name: Joi.string().optional().allow(null),
    file_mobile_mime_type: Joi.string().valid(...Object.values(SLIDER_FILE_MIME_TYPE)).optional().allow(null),
    title: Joi.string().optional().allow(null),
    sub_title: Joi.string().optional().allow(null),
    slides: Joi.array().items(slideSchema).optional().allow(null),
};

export const sliderSchema = Joi.object().keys(sliderKeys);

export default class ObjectToSliderFactory {
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
        Joi.assert(data, sliderSchema);

        return new Slider(
            data.id,
            data.file_name,
            data.file_mime_type,
            data.file_mobile_name,
            data.file_mobile_mime_type,
            data.title,
            data.sub_title,
            data.slides.map(item => ObjectToSlideFactory.create(item)),
        );
    }
}
