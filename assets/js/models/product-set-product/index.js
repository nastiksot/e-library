import Product from "../product";
import PRODUCT_SET_PRODUCT_TYPE from "../../dictionary/product-set-product-type";

export default class ProductSetProduct {
    constructor(id: Number, quantity: Number, product: Product, productType: String) {
        this._id = id;
        this._quantity = quantity;
        this._currentQuantity = quantity;
        this._product = product;
        this._productType = productType;
    }

    get id(): Number {
        return this._id;
    }

    get quantity(): Number {
        return this._quantity;
    }

    get currentQuantity(): Number {
        return this._currentQuantity;
    }

    set currentQuantity(value: Number) {
        this._currentQuantity = value;
    }

    get product(): Product {
        return this._product;
    }

    set product(product: Product) {
        this._product = product;
    }

    get productType(): String {
        return this._productType;
    }

    get isRegularProductType(): Boolean {
        return this._productType === PRODUCT_SET_PRODUCT_TYPE.REGULAR;
    }

    get isOptionalProductType(): Boolean {
        return this._productType === PRODUCT_SET_PRODUCT_TYPE.OPTIONAL;
    }

    get productCost(): Number {
        return this._product.priceOnRequest
            ? 0
            : this._currentQuantity * this._product.price;
    }

}
