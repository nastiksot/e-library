export default class Dealer {
    constructor(
        slug: String,
        uid: String,
        title: ?String,
        image: ?String,
        countryName: ?String,
        regionName: ?String,
        cityName: ?String,
        addressLine1: ?String,
        addressLine2: ?String,
        postalCode: ?String,
    ) {
        this._slug = slug;
        this._uid = uid;
        this._title = title;
        this._image = image;
        this._countryName = countryName;
        this._regionName = regionName;
        this._cityName = cityName;
        this._addressLine1 = addressLine1;
        this._addressLine2 = addressLine2;
        this._postalCode = postalCode;
    }

    get slug(): String {
        return this._slug;
    }

    get uid(): String {
        return this._uid;
    }

    get title(): ?String {
        return this._title;
    }

    get image(): ?String {
        return this._image;
    }

    get countryName(): ?String {
        return this._countryName;
    }

    get regionName(): ?String {
        return this._regionName;
    }

    get cityName(): ?String {
        return this._cityName;
    }

    get addressLine1(): ?String {
        return this._addressLine1;
    }

    get addressLine2(): ?String {
        return this._addressLine2;
    }

    get postalCode(): ?String {
        return this._postalCode;
    }
}
