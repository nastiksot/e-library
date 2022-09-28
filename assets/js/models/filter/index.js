export default class Filter {
    /**
     * @param {Number} id
     * @param {String} title
     */
    constructor(id: Number, title: String) {
        this._id = id;
        this._title = title;
    }

    get id(): Number {
        return this._id;
    }

    get title(): String {
        return this._title;
    }
}
