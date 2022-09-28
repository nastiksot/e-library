import WishListProductSetProduct from "../wish-list-product-set-product";
import _filter from "lodash/filter";
import _forEach from "lodash/forEach";
import _find from "lodash/find";
import _findIndex from "lodash/findIndex";

export default class WishListProductSet {
    constructor(
        id: Number,
        originalId: Number,
        icon: String,
        image: String,
        youtubeVideoUrl: ?String,
        title: String,
        description: ?String,
        products: Array<WishListProductSetProduct>,
        recommended: Boolean,
        iconTitle: ?String,
        layer1IconX: ?Number,
        layer1IconY: ?Number,
        layer1Active: Boolean,
        layer2IconX: ?Number,
        layer2IconY: ?Number,
        layer2Active: Boolean,
        layer3IconX: ?Number,
        layer3IconY: ?Number,
        layer3Active: Boolean,
        deletedProductId: ?Number,
        replacedProductId: ?Number,
    ) {
        this._id = id;
        this._originalId = originalId;
        this._icon = icon;
        this._image = image;
        this._youtubeVideoUrl = youtubeVideoUrl;
        this._title = title;
        this._products = products;
        this._recommended = recommended;
        this._iconTitle = iconTitle;
        this._layer1IconX = layer1IconX;
        this._layer1IconY = layer1IconY;
        this._layer1Active = layer1Active;
        this._layer2IconX = layer2IconX;
        this._layer2IconY = layer2IconY;
        this._layer2Active = layer2Active;
        this._layer3IconX = layer3IconX;
        this._layer3IconY = layer3IconY;
        this._layer3Active = layer3Active;
        this._description = description;
        this._deletedProductId = deletedProductId;
        this._replacedProductId = replacedProductId;
    }

    get totalPrice(): Number {
        let total = 0;
        _forEach(this.displayedProducts, (product: WishListProductSetProduct) => {
            if (!product.product.priceOnRequest) {
                total = total + (product.currentQuantity * product.product.price);
            }
        });

        return total;
    }

    get totalPriceEnd(): Number {
        let total = 0;
        _forEach(this.displayedProducts, (product: WishListProductSetProduct) => {
            if (!product.product.priceOnRequest) {
                let price = product.product.priceEnd ? product.product.priceEnd : product.product.price;
                total = total + (product.currentQuantity * price);
            }
        });

        return total;
    }

    get isRangePrice(): Boolean {
        let index = _findIndex(this.displayedProducts, (product: WishListProductSetProduct) => {
            return product.product.isRangePrice;
        });

        return index !== -1;
    }

    get id(): Number {
        return this._id;
    }

    get originalId(): Number {
        return this._originalId;
    }

    get icon(): String {
        return this._icon;
    }

    get image(): String {
        return this._image;
    }

    get youtubeVideoUrl(): ?String {
        return this._youtubeVideoUrl;
    }

    get title(): String {
        return this._title;
    }

    get description(): ?String {
        return this._description;
    }

    get products(): Array<WishListProductSetProduct> {
        return this._products;
    }

    get displayedProducts(): Array<WishListProductSetProduct> {
        return _filter(this._products, (product: WishListProductSetProduct) => {
            return product.isRegularProductType && !product.deleted && !product.replaced
        });
    }

    get displayedOptionalProducts(): Array<WishListProductSetProduct> {
        // collect products without a product that was deleted or replaced
        return _filter(this._products, (product: WishListProductSetProduct) => {
            return product.isOptionalProductType;
        });
    }

    get recommended(): Boolean {
        return this._recommended;
    }

    get iconTitle(): ?String {
        return this._iconTitle;
    }

    get layer1IconX(): ?Number {
        return this._layer1IconX;
    }

    get layer1IconY(): ?Number {
        return this._layer1IconY;
    }

    get layer1Active(): Boolean {
        return this._layer1Active;
    }

    get layer2IconX(): ?Number {
        return this._layer2IconX;
    }

    get layer2IconY(): ?Number {
        return this._layer2IconY;
    }

    get layer2Active(): Boolean {
        return this._layer2Active;
    }

    get layer3IconX(): ?Number {
        return this._layer3IconX;
    }

    get layer3IconY(): ?Number {
        return this._layer3IconY;
    }

    get layer3Active(): Boolean {
        return this._layer3Active;
    }

    get deletedProductId(): ?Number {
        return this._deletedProductId;
    }

    get replacedProductId(): ?Number {
        return this._replacedProductId;
    }
}
