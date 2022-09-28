'use strict';

import Joi from "@hapi/joi";
import PaginatorMeta from "../index";

const paginatorFactorySchema = Joi.object().unknown(true).keys({
    current_page: Joi.number().required(),
    total_items: Joi.number().required(),
    items_per_page: Joi.number().required(),
});

export default class PaginatorMetaFactory {
    /**
     * @param {Object} data
     * @return {Boolean}
     */
    static supports(data) {
        return !paginatorFactorySchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {PaginatorMeta}
     */
    static create(data) {
        Joi.assert(data, paginatorFactorySchema);

        return new PaginatorMeta(
            data.current_page,
            data.items_per_page,
            data.total_items
        );
    }
}
