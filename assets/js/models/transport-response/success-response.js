import {AxiosResponse} from "axios";
import DataCollectionFactory from './data-collection/data-collection-factory';

export default class SuccessResponse {
    constructor(response: AxiosResponse) {
        this._response = response;
        this._status = response.status;
        this._dataCollection = DataCollectionFactory.supports(response.data) ? DataCollectionFactory.create(response.data) : null;
    }

    get response() {
        return this._response;
    }

    get status() {
        return this._status;
    }

    get data() {
        return this._response.data;
    }

    get dataCollection() {
        return this._dataCollection;
    }
}
