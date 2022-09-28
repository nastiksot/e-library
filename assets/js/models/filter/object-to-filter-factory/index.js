import Joi from "@hapi/joi";
import Filter from "../index";

export const filterSchema = Joi.object().keys({
    id: Joi.number().required(),
    title: Joi.string().required(),
});

export default class ObjectToFilterFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !filterSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Filter}
     */
    static create(data) {
        Joi.assert(data, filterSchema);

        return new Filter(
            data.id,
            data.title,
        );
    }
}
