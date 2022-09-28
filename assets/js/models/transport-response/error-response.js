import DataErrorFactory from './data-error/data-error-factory';
import FormErrorFactory from './form-error/form-error-factory';

export default class ErrorResponse {
    /**
     * @param {AxiosResponse} response
     */
    constructor(response) {
        this._response = response;
        this._status = response.status;
        this._dataError = DataErrorFactory.supports(response.data) ? DataErrorFactory.create(response.data) : null;
        this._formError = FormErrorFactory.supports(response.data) ? FormErrorFactory.create(response.data) : null;
    }

    get response() {
        return this._response;
    }

    get status() {
        return this._status;
    }

    get generalError() {
        return this._dataError;
    }

    get formError() {
        return this._formError;
    }
}
