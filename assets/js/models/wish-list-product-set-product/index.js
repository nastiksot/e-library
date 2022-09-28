import Product from "../product";
import PRODUCT_SET_PRODUCT_TYPE from "../../dictionary/product-set-product-type";

export default class WishListProductSetProduct {
    constructor(
        id: Number,
        originalQuantity: Number,
        currentQuantity: Number,
        product: Product,
        productType: String,
        duplicate: Boolean,
        deleted: Boolean,
        replaced: Boolean,
    ) {
        this._id = id;
        this._originalQuantity = originalQuantity;
        this._currentQuantity = currentQuantity;
        this._product = product;
        this._productType = productType;
        this._duplicate = duplicate;
        this._deleted = deleted;
        this._replaced = replaced;
        this._isHiddenOnWishListPage = false; // a special field showing whether the current product should be displayed on the Wish List page or not
    }

    get id(): Number {
        return this._id;
    }

    get originalQuantity(): Number {
        return this._originalQuantity;
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

    get duplicate(): Boolean {
        return this._duplicate;
    }

    get deleted(): Boolean {
        return this._deleted;
    }

    get replaced(): Boolean {
        return this._replaced;
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

    set isHiddenOnWishListPage(val: Boolean) {
        return this._isHiddenOnWishListPage = val;
    }

    get isHiddenOnWishListPage(): Boolean {
        return this._isHiddenOnWishListPage;
    }

    get productCost(): Number {
        return this._product.priceOnRequest
            ? 0
            : this._currentQuantity * this._product.price;
    }

}


