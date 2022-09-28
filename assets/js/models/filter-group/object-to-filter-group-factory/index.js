import Joi from "@hapi/joi";
import FilterGroup from "../index";
import ObjectToFilterFactory from "../../filter/object-to-filter-factory";

export const filterGroupSchema = Joi.object().keys({
    id: Joi.number().required(),
    title: Joi.string().required(),
    filters: Joi.array().required(),
});

export default class ObjectToFilterGroupFactory {
    /**
     * @param {Object} data
     * @return {boolean}
     */
    static supports(data) {
        return !filterGroupSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {FilterGroup}
     */
    static create(data) {
        Joi.assert(data, filterGroupSchema);

        return new FilterGroup(
            data.id,
            data.title,
            data.filters.map(item => ObjectToFilterFactory.create(item)),
        );
    }
}
