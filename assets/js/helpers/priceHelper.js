const priceFormat = (price: Number, locale: ?String, currency: ?String) => {

    // detect locale
    if (!locale && 'undefined' !== typeof (navigator.languages)) {
        locale = navigator.languages[0] ?? 'de-DE';
    }

    // set default currency
    if (!currency) {
        currency = 'EUR';
    }

    // detect price is float
    let isFloat = Number(price) === price && price % 1 !== 0;

    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
        maximumFractionDigits: isFloat ? 2 : 0, // when the price is float convert coins to 2 digits otherwise without coins
    }).format(price);
};

export {
    priceFormat
};
