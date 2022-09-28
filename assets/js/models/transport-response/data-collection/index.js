'use strict';

import PaginatorMetaFactory from '../paginator-meta/paginator-meta-factory';

export default class DataCollection {
    /**
     * @param {Array} items
     * @param {Object} meta
     */
    constructor(items, meta) {
        this._items = items;
        if (meta) {
            this._paginator = PaginatorMetaFactory.create(meta.paginator);
        }
    }

    get items() {
        return this._items;
    }

    /**
     * @return {PaginatorMeta}
     */
    get paginator() {
        return this._paginator;
    }
}
