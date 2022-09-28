'use strict';

import Joi from "@hapi/joi";
import FormError from "../index";

const formErrorSchema = Joi.object().unknown(true).keys({
    code: Joi.number().required(),
    errors: Joi.object().unknown(true).keys({
        global: Joi.string().allow(null),
        fields: Joi.object()
    }),
});

export default class FormErrorFactory {
    /**
     * @param {Object} responseData
     * @return {Boolean}
     */
    static supports(responseData) {
        return !formErrorSchema.validate(responseData).error;
    }

    /**
     * @param {Object} responseData
     * @return {FormError}
     */
    static create(responseData) {
        Joi.assert(responseData, formErrorSchema);

        return new FormError(responseData);
    }
}
