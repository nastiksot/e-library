'use strict';

import Joi from '@hapi/joi';
import DataCollection from '../index';

const dataCollectionFactorySchema = Joi.object().unknown(true).keys({
    items: Joi.array().required(),
    meta: Joi.object().keys({
        paginator: Joi.object().required(),
        filters: Joi.object().allow(null),
    }),
});

export default class DataCollectionFactory {
    /**
     * @param {Object} data
     * @return {boolean}
     */
    static supports(data) {
        return !dataCollectionFactorySchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {DataCollection}
     */
    static create(data) {
        Joi.assert(data, dataCollectionFactorySchema);

        return new DataCollection(
            data.items,
            data.meta,
        );
    }
}
