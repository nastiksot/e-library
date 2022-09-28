import WishListProductSet from "../wish-list-product-set";
import _find from "lodash/find";
import _forEach from "lodash/forEach";
import _findIndex from "lodash/findIndex";

export default class WishList {
    constructor(
        id: Number,
        uid: String,
        name: String,
        productSets: Array<WishListProductSet>,
        createdAt: String,
        updatedAt: String,
    ) {
        this._id = id;
        this._uid = uid;
        this._name = name;
        this._productSets = productSets;
        this._createdAt = createdAt;
        this._updatedAt = updatedAt;
    }

    get totalPrice(): Number {
        let total = 0;
        _forEach(this.productSets, (productSet: WishListProductSet) => {
            total = total + productSet.totalPrice;
        });

        return total;
    }

    get totalPriceEnd(): Number {
        let total = 0;
        _forEach(this.productSets, (productSet: WishListProductSet) => {
            total = total + productSet.totalPriceEnd;
        });

        return total;
    }

    get isRangePrice(): Boolean {
        let index = _findIndex(this.productSets, (productSet: WishListProductSet) => {
            return productSet.isRangePrice;
        });

        return index !== -1;
    }

    get id(): Number {
        return this._id;
    }

    get productSets(): Array<WishListProductSet> {
        return this._productSets;
    }

    get isEmptyProductSets(): Boolean {
        return this._productSets.length === 0;
    }

    findProductSetByOriginalId(id: Number): ?WishListProductSet {
        return _find(this._productSets, {originalId: id});
    }

    get uid(): String {
        return this._uid;
    }

    get name(): String {
        return this._name;
    }

    get createdAt(): String {
        return this._createdAt;
    }

    get updatedAt(): String {
        return this._updatedAt;
    }
}
