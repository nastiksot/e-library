import AlternativeProduct from "../alternative-product";
import WHERE_TO_BUY from "../../dictionary/where-to-buy";

export default class Product {
    constructor(
        id: Number,
        sku: String,
        title: String,
        name: ?String,
        description: ?String,
        tip: ?String,
        price: ?Number,
        priceEnd: ?Number,
        priceOnRequest: Boolean,
        // specialShop: Boolean,
        image: ?String,
        link: ?String,
        whereToBuy: String,
        alternativeProducts: Array<Product>,
        isBaseProduct: ?Boolean,
    ) {
        this._id = id;
        this._sku = sku;
        this._title = title;
        this._name = name;
        this._description = description;
        this._tip = tip;
        this._price = price;
        this._priceEnd = priceEnd;
        this._priceOnRequest = priceOnRequest;
        // this._specialShop = specialShop;
        this._image = image;
        this._link = link;
        this._whereToBuy = whereToBuy;
        this._alternativeProducts = alternativeProducts;
        this._isBaseProduct = isBaseProduct;
    }

    get id(): Number {
        return this._id;
    }

    get title(): String {
        return this._title;
    }

    get name(): ?String {
        return this._name;
    }

    get sku(): String {
        return this._sku;
    }

    get description(): ?String {
        return this._description;
    }

    get tip(): ?String {
        return this._tip;
    }

    get price(): ?Number {
        return this._price;
    }

    get priceEnd(): ?Number {
        return this._priceEnd;
    }

    get isRangePrice(): Boolean {
        return this._price > 0 && this._priceEnd > 0 &&
            this._price !== this._priceEnd;
    }

    get priceOnRequest(): Boolean {
        return this._priceOnRequest;
    }

    // get specialShop(): Boolean {
    //     return this._specialShop;
    // }

    get image(): ?String {
        return this._image;
    }

    get link(): ?String {
        return this._link;
    }

    get whereToBuy(): String {
        return this._whereToBuy;
    }

    get isOnlineOnlyProduct(): Boolean {
        return this._whereToBuy === WHERE_TO_BUY.ONLINE;
    }

    get isRetailOnlyProduct(): Boolean {
        return this._whereToBuy === WHERE_TO_BUY.RETAIL;
    }

    get alternativeProducts(): Array<Product> {
        return this._alternativeProducts;
    }

    set alternativeProducts(alternativeProducts: Array<Product>) {
        this._alternativeProducts = alternativeProducts;
    }

    get isBaseProduct(): ?Boolean {
        return this._isBaseProduct;
    }
}
