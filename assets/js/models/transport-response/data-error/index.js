'use strict';

export default class DataError {
    constructor(responseData) {
        this._code = responseData.code;
        this._message = responseData.message;
    }

    /**
     * @return {Number}
     */
    get code() {
        return this._code;
    }

    /**
     * @return {String}
     */
    get message() {
        return this._message;
    }

    /**
     * @return {{code: Number, message: String}}
     */
    get codeMessage() {
        return {
            code: this._code,
            message: this._message,
        };
    }
}
