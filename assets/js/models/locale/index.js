export default class Locale {
    constructor(
        prefix:    String,
        locale:    String,
        title:     String,
        enCountry: String,
    ) {
        this._prefix    = prefix;
        this._locale    = locale;
        this._title     = title;
        this._enCountry = enCountry;
    }

    get prefix(): String {
        return this._prefix;
    }

    get locale(): String {
        return this._locale;
    }

    get title(): String {
        return this._title;
    }

    get enCountry(): String {
        return this._enCountry;
    }
}
