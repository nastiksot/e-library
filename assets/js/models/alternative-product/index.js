export default class AlternativeProduct {
    constructor(
        id: Number,
        sku: String,
        title: String,
        name: ?String,
        image: ?String,
        whereToBuy: String,
        // specialShop: Boolean,
    ) {
        this._id = id;
        this._sku = sku;
        this._title = title;
        this._name = name;
        this._image = image;
        this._whereToBuy = whereToBuy;
        // this._specialShop = specialShop;
    }

    get id(): Number {
        return this._id;
    }

    get sku(): String {
        return this._sku;
    }

    get title(): String {
        return this._title;
    }

    get name(): ?String {
        return this._name;
    }

    get image(): ?String {
        return this._image;
    }

    get whereToBuy(): String {
        return this._whereToBuy;
    }

    // get specialShop(): Boolean {
    //     return this._specialShop;
    // }
}
