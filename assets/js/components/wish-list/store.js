"use strict";

import SuccessResponse from "../../models/transport-response/success-response";
import WishList from "../../models/wish-list";
import ObjectToWishListFactory from "../../models/wish-list/object-to-wish-list-factory";
import HttpClient from "../../modules/HttpClient";
import _find from "lodash/find";
import _findIndex from 'lodash/findIndex';
import _forEach from "lodash/forEach";
import _uniq from "lodash/uniq";
import PRODUCT_SET_PRODUCT_TYPE from "../../dictionary/product-set-product-type";

let state = {
    busy: false,
    isDealerMode: document.querySelector('html').dataset.dealerUid.length > 0,
    /** @type WishList */
    wishList: {},

    briefRealWishListProductSets: {}, // real Wish List Product Sets data (excluding unsaved changes)

    isWishListPage: document.getElementById('wish-page') !== null,
};

let mutations = {
    setBusy(state: Object, busy: Boolean) {
        state.busy = busy;
    },

    setWishList(state: Object, wishList: WishList) {
        state.wishList = wishList;
    },

    generateBriefRealWishListProductSets(state: Object, wishList: WishList) {
        let result = {};

        for (let i in wishList.productSets) {
            let productSet = wishList.productSets[i];
            result[productSet.id] = [];

            for (let j in productSet.products) {
                if (productSet.products[j].productType === PRODUCT_SET_PRODUCT_TYPE.OPTIONAL &&
                    !productSet.products[j].currentQuantity
                ) {
                    // ignore optional products whose quantity < 1
                    continue;
                }

                result[productSet.id].push(productSet.products[j].product.id);
            }
        }

        state.briefRealWishListProductSets = result;
    },
};

/**
 * @param {Array} wishListProducts
 * @param {WishListProductSetProduct} product
 */
let getTcWishListProduct = function (wishListProducts, product) {
    let productId = product.product.id,
        cost = product.productCost,
        index = _findIndex(wishListProducts, {order_product_id: productId});

    if (index === -1) {
        wishListProducts.push({
            'order_product_id': productId,
            'order_product_name': product.product.title,
            'order_product_category': 'Home automation',
            'order_product_quantity': product.currentQuantity,
            'order_product_unit_sale_price_tf': cost,
            'order_product_unit_sale_price_ati': cost,
        });
    } else {
        wishListProducts[index].order_product_quantity += product.currentQuantity;
        wishListProducts[index].order_product_unit_sale_price_tf += cost;
        wishListProducts[index].order_product_unit_sale_price_ati += cost;
    }

    return wishListProducts;
};

let updateTcWishListData = function (wishList: WishList) {
    if (window.tc_vars === undefined || typeof(tC) === 'undefined') {
        return;
    }

    let productIds = [],
        productsCost = 0,
        wishListProducts = [];

    _forEach(wishList.productSets, (productSet: WishListProductSet) => {
        _forEach(productSet.displayedProducts, (product: WishListProductSetProduct) => {
            productIds.push(product.product.id);
            productsCost += product.productCost;
            wishListProducts = getTcWishListProduct(wishListProducts, product);
        });

        _forEach(productSet.displayedOptionalProducts, (product: WishListProductSetProduct) => {
            if (product.currentQuantity > 0) {
                productIds.push(product.product.id);
                productsCost += product.productCost;
                wishListProducts = getTcWishListProduct(wishListProducts, product);
            }
        });
    });

    window.tc_vars.basket_id = wishList.uid;
    window.tc_vars.order_total_amount = Number(productsCost).toFixed(2);
    window.tc_vars.order_products_reference_number = productIds.length;
    window.tc_vars.order_products_number = _uniq(productIds).length;
    window.tc_vars.order_products = wishListProducts;

    tC.container.reload();
};

let actions = {
    /**
     * @param {Function} commit
     * @param {String} uid
     * @param {Number|null} wishListId
     */
    loadWishList({commit}, {uid, wishListId = null}): Promise {
        let routeParams = {uid: uid};

        if (state.isDealerMode) {
            routeParams.is_dealer_mode = 1;
        }

        let request;

        if (wishListId) {
            routeParams.wishListId = wishListId;
            request = HttpClient
                .get("api.wish_list_get", routeParams);
        } else {
            request = HttpClient
                .get("api.wish_list_latest_get", routeParams);
        }

        return request
            .then((response: SuccessResponse) => {
                let wishList = ObjectToWishListFactory.create(response.data);

                if (state.isWishListPage) {
                    // add wish list data to TC Data Layer
                    updateTcWishListData(wishList);
                }

                commit("setWishList", wishList);
                commit('generateBriefRealWishListProductSets', wishList);
            }, error => {
            });
    },

    /**
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String} uid
     * @param {Number} wishListId
     * @param {Number} productSetId
     * @param {Array<Number>|undefined} productsQuantityIds
     * @param {Array<Number>|undefined} alternativeProductIds
     * @param {Number|undefined} deleteProductId
     * @param {Number|undefined} replaceProductId
     * @param {Array|undefined} replacementProductIds
     *
     * @returns {Promise<SuccessResponse>}
     */
    addProductSetToWishList({commit, dispatch}, {
        uid,
        wishListId,
        productSetId,
        alternativeProductIds,
        productsQuantityIds,
        deleteProductId,
        replaceProductId,
        replacementProductIds,
    }) {
        if (!wishListId) {
            return Promise.reject('No wishlist id specified');
        }

        // prepare state data to post
        let postData = {product_set_id: productSetId};

        if (state.isDealerMode) {
            postData.is_dealer_mode = 1;
        }

        if (productsQuantityIds) {
            postData.products_quantity_ids = productsQuantityIds;
        }

        if (alternativeProductIds) {
            postData.alternative_product_ids = alternativeProductIds;
        }

        if (deleteProductId) {
            postData.delete_product_id = deleteProductId;
        }

        if (replaceProductId) {
            postData.replace_product_id = replaceProductId;
        }

        if (replacementProductIds && replacementProductIds.length) {
            postData.replaced_product_ids = replacementProductIds;
        }

        return HttpClient
            .post("api.wish_list_product_set_post", {
                uid: uid,
                wishListId: wishListId
            }, postData)
            .then((response: SuccessResponse) => {
                dispatch("loadWishList", {uid: uid, wishListId: wishListId});
            }, error => {
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String} uid
     * @param {Number} wishListId
     * @param {Number|null} productSetId
     * @param {Number|null} wishListProductSetId
     *
     * @returns {Promise<SuccessResponse>}
     */
    removeProductSetFromWishList({state, commit, dispatch}, {
        uid,
        wishListId,
        productSetId = null,
        wishListProductSetId = null
    }) {
        if (wishListProductSetId === null) {
            wishListProductSetId = state.wishList.findProductSetByOriginalId(productSetId).id;
        }

        if (!wishListProductSetId) {
            return Promise.reject('No wishlist product set id specified');
        }

        return HttpClient
            .delete("api.wish_list_product_set_delete", {
                uid: uid,
                wishListId: wishListId,
                wishListProductSetId: wishListProductSetId
            })
            .then((response: SuccessResponse) => {
                dispatch("loadWishList", {uid: uid, wishListId: wishListId});
            }, error => {
            });
    },

    /**
     * @param {Object} state
     * @param {Function} commit
     * @param {Function} dispatch
     * @param {String} uid
     * @param {Number} wishListId
     * @param {Number} wishListProductSetId
     * @param {Number} wishListProductSetProductId
     * @param {Number} quantity
     * @returns {Promise<SuccessResponse>}
     */
    updateProductSetProductQuantity({state, commit, dispatch}, {
        uid,
        wishListId,
        wishListProductSetId,
        wishListProductSetProductId,
        quantity
    }): Promise {
        return HttpClient
            .patch('api.wish_list_product_set_product_quantity', {
                uid: uid,
                wishListId: wishListId,
                wishListProductSetId: wishListProductSetId,
                wishListProductSetProductId: wishListProductSetProductId
            }, {
                quantity: quantity
            })
            .then((response: SuccessResponse) => {
            }, error => {
            });
    },

};

let getters = {
    selectedProductSets(state: Object): Array<Number> {
        let result = [];

        _forEach(state.wishList.productSets, (item, key) => {
            result.push(item.originalId);
        });

        return result;
    },

    totalPrice(state: Object): Number {
        return state.wishList instanceof WishList ? state.wishList.totalPrice : 0;
    },

    totalPriceEnd(state: Object): Number {
        return state.wishList instanceof WishList ? state.wishList.totalPriceEnd : 0;
    },

    isRangePrice(state: Object): Boolean {
        return state.wishList instanceof WishList ? state.wishList.isRangePrice : false;
    },

    getProductSetByOriginalId(state) {
        return (productSetId) => {
            return _find(state.wishList.productSets, {originalId: productSetId});
        }
    },

};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
