'use strict';

import Joi from "@hapi/joi";
import DataError from "../index";

const responseErrorSchema = Joi.object().unknown(true).keys({
    code: Joi.number().required(),
    message: Joi.string().required(),
});

export default class DataErrorFactory {
    /**
     * @param {Object} responseData
     * @return {boolean}
     */
    static supports(responseData) {
        return !responseErrorSchema.validate(responseData).error;
    }

    /**
     * @param {Object} responseData
     * @return {DataError}
     */
    static create(responseData) {
        Joi.assert(responseData, responseErrorSchema);

        return new DataError(responseData);
    }
}
