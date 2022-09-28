import Filter from "../filter/index";

export default class FilterGroup {
    /**
     * @param {Number} id
     * @param {String} title
     * @param {Array<Filter>} filters
     */
    constructor(id: Number, title: String, filters: Array) {
        this._id = id;
        this._title = title;
        this._filters = filters;
    }

    get id(): Number {
        return this._id;
    }

    get title(): String {
        return this._title;
    }

    get filters(): Array {
        return this._filters;
    }
}
