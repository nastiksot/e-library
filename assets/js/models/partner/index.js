export default class Partner {
    constructor(
        id: Number,
        image: ?String,
        title: ?String,
    ) {
        this._id = id;
        this._image = image;
        this._title = title;
    }

    get id(): Number {
        return this._id;
    }

    get image(): ?String {
        return this._image;
    }

    get title(): ?String {
        return this._title;
    }
}
