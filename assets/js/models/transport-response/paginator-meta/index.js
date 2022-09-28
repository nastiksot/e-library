'use strict';

export default class PaginatorMeta {
    /**
     * @param {Number} page
     * @param {Number} perPage
     * @param {Number} itemsCount
     */
    constructor(page, perPage, itemsCount) {
        this._page = page;
        this._perPage = perPage;
        this._itemsCount = itemsCount;
        this._pagesCount = Math.ceil(itemsCount / perPage);
    }

    get page() {
        return this._page;
    }

    get perPage() {
        return this._perPage;
    }

    get itemsCount() {
        return this._itemsCount;
    }

    get pagesCount() {
        return this._pagesCount;
    }

    get pageItemsFromCount() {
        return (this._page - 1) * this._perPage + 1;
    }

    get pageItemsToCount() {
        let amount = this._page * this._perPage;

        return amount > this._itemsCount ? this._itemsCount : amount;
    }

    get hasNext() {
        return this._perPage * this._page < this._itemsCount;
    }

    get nextPage() {
        return this._page + 1;
    }
}

