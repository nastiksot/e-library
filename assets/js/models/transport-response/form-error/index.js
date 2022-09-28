'use strict';

import _get from 'lodash/get';

export default class FormError {
    constructor(responseData) {
        this._data = responseData;
    }

    /**
     * @return {String|null}
     */
    get globalError() {
        return _get(this._data, ['errors', 'fields', 'global'], null);
    }

    /**
     * @return {Array}
     */
    get fieldErrors() {
        return _get(this._data, ['errors', 'fields'], []);
    }

    /**
     * @param {String} fieldName
     * @return {array}
     */
    getFieldErrors(fieldName) {
        return _get(this._data, ['errors', 'fields', fieldName], []);
    }
}
