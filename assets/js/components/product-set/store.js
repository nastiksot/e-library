'use strict';

import HttpClient from '../../modules/HttpClient';
import ProductSet from "../../models/product-set";
import ObjectToProductSetFactory from "../../models/product-set/object-to-product-set-factory";
import Product from "../../models/product";
import ObjectToProductSetProductFactory from "../../models/product-set-product/object-to-product-set-product-factory";
import ProductSetProduct from "../../models/product-set-product";
import MORE_DETAILS_PRODUCT_SET_STATE from "../../dictionary/more-details-product-set-state";
import _find from "lodash/find";

let state = {
    productSets: [],
    moreDetailsProductSetId: null,
    moreDetailsProductSetState: null,
    decisionProductSetIds: [],
};

let mutations = {
    setProductSets(state: Object, productSets: Array<ProductSet>) {
        state.productSets = productSets;
    },

    setMoreDetailsProductSetId(state: Object, id: ?Number) {
        if (state.moreDetailsProductSetId !== id) {
            // product set changed
            if (id) {
                // show description of active product set
                state.moreDetailsProductSetState = MORE_DETAILS_PRODUCT_SET_STATE.DESCRIPTION;
            } else {
                // reset
                state.moreDetailsProductSetState = MORE_DETAILS_PRODUCT_SET_STATE.NULL;
            }
        }

        state.moreDetailsProductSetId = id;
    },

    setMoreDetailsProductSetState(state: Object, value: ?String) {
        state.moreDetailsProductSetState = value;
    },

    addDecisionProductSetId(state: Object, productSetId: Number) {
        state.decisionProductSetIds.push(productSetId);
    },

    /**
     * @param {Object} state
     * @param {Number} productSetId
     * @param {Number} productId
     */
    setProductSetDeleteProductId(state: Object, {productSetId, productId}) {
        let productSet = _find(state.productSets, {id: productSetId});
        if (productSet) {
            // mark deleted product
            productSet.deleteProductId = productId;

            // remember decision by productSet
            if (productId) {
                state.decisionProductSetIds.push(productSetId);
            } else {
                // remove decision by productSet
                let indexOf = state.decisionProductSetIds.indexOf(productSetId);
                if (-1 !== indexOf) {
                    state.decisionProductSetIds.splice(indexOf, 1);
                }
            }
        }
    },

    /**
     * @param {Object} state
     * @param {Number} productSetId
     * @param {Number} productId
     * @param {Array<Product>} replacedProducts
     */
    setProductSetReplacedProducts(state: Object, {productSetId, productId, replacedProducts}) {

        let productSet = _find(state.productSets, {id: productSetId});
        if (productSet) {

            // mark replaced product
            productSet.replaceProductId = productId;

            // set replacedProduct
            let replacedProductSetProducts = replacedProducts.map((product => ObjectToProductSetProductFactory.createFromProduct(product)));
            productSet.replacedProductSetProducts.push(...replacedProductSetProducts);

            // remember decision by productSet
            if (productId) {
                state.decisionProductSetIds.push(productSetId);
            } else {
                // remove decision by productSet
                let indexOf = state.decisionProductSetIds.indexOf(productSetId);
                if (-1 !== indexOf) {
                    state.decisionProductSetIds.splice(indexOf, 1);
                }
            }
        }
    },

    /**
     * @param {Object} state
     * @param {Number} productSetId
     */
    resetProductSetReplacements(state: Object, {productSetId}) {
        let productSet = _find(state.productSets, {id: productSetId});
        if (productSet) {
            // clear all replacements by productSet
            productSet.clearReplacements();

            // remove decision by productSet
            let indexOf = state.decisionProductSetIds.indexOf(productSetId);
            if (-1 !== indexOf) {
                state.decisionProductSetIds.splice(indexOf, 1);
            }
        }
    },

};

let actions = {
    /**
     * @param {Function} commit
     * @param {Array<Number>} appliedFilters
     * @param {Boolean} isDealerMode
     */
    loadProductSets({commit}, {appliedFilters, isDealerMode}) {
        let queryParams = {};

        if (isDealerMode) {
            queryParams.is_dealer_mode = 1;
        }

        if (appliedFilters.length) {
            queryParams.filters = appliedFilters.join('-')
        }

        HttpClient
            .get("api.product_set_collection", queryParams)
            .then(response => {
                let productSets = response.dataCollection.items.map(item => ObjectToProductSetFactory.create(item));

                commit('setProductSets', productSets);
            }, error => {
            });
    },

    setMoreDetailsProductSetId({commit}, id: ?Number) {
        commit('setMoreDetailsProductSetId', id);
    },

    setMoreDetailsProductSetState({commit}, value: ?String) {
        commit('setMoreDetailsProductSetState', value);
    },

    addDecisionProductSetId({state, commit, dispatch}, productSetId: Number) {
        commit('addDecisionProductSetId', productSetId);
    },

    /**
     * @param {Object} state
     * @param {Object} commit
     * @param {Object} dispatch
     * @param {Number} productSetId
     * @param {Number} productId
     */
    setProductSetDeleteProductId({state, commit, dispatch}, {productSetId, productId}) {
        commit("setProductSetDeleteProductId", {
            productSetId: productSetId,
            productId: productId
        });
    },

    /**
     * @param {Object} state
     * @param {Object} commit
     * @param {Object} dispatch
     * @param {Number} productSetId
     * @param {Number} productId
     * @param {Array<Product>} replacedProducts
     */
    setProductSetReplacedProducts({state, commit, dispatch}, {productSetId, productId, replacedProducts}) {
        commit("setProductSetReplacedProducts", {
            productSetId: productSetId,
            productId: productId,
            replacedProducts: replacedProducts
        });
    },

    /**
     * @param {Object} state
     * @param {Object} commit
     * @param {Object} dispatch
     * @param {Number} productSetId
     */
    resetProductSetReplacements({state, commit, dispatch}, {productSetId}) {
        commit("resetProductSetReplacements", {
            productSetId: productSetId,
        });
    },
};

let getters = {
    getProductSetById(state) {
        return (productSetId) => {
            return _find(state.productSets, {id: productSetId});
        }
    },

    getProductSetProductByProductSetIdAndProductId(state: Object) {
        return (productSetId: Number, productId: Number) => {

            // resolve productSet from productSets
            let productSet = _find(state.productSets, {id: productSetId});

            // resolve product from productSet
            return productSet
                ? _find(productSet.products, (product: ProductSetProduct) => {
                    return product.product.id === productId;
                })
                : null;
        };
    },

    createProductSetProductsFromProducts(state: Object) {
        return (products: Array<Product>) => {
            return products.map((product => ObjectToProductSetProductFactory.createFromProduct(product)));
        };
    },

    getDeletedProductSetProduct(state) {
        return (productSetId: Number): ?ProductSetProduct => {
            let product = null;
            let productSet = _find(state.productSets, {id: productSetId});
            if (productSet && productSet.deleteProductId) {
                product = _find(productSet.products, (product: ProductSetProduct) => {
                    return productSet.deleteProductId === product.product.id;
                });
            }

            return product ? product : null;
        }
    },

    getReplacedProductSetProduct(state) {
        return (productSetId: Number): ?ProductSetProduct => {
            let product = null;
            let productSet = _find(state.productSets, {id: productSetId});
            if (productSet && productSet.replaceProductId) {
                product = _find(productSet.products, (product: ProductSetProduct) => {
                    return productSet.replaceProductId === product.product.id;
                });
            }

            return product ? product : null;
        }
    },

    getReplacedProductSetProducts(state) {
        return (productSetId: Number): Array<ProductSetProduct> => {
            let productSet = _find(state.productSets, {id: productSetId});
            if (productSet) {
                return productSet.replacedProductSetProducts;
            }

            return [];
        }
    },

    isProductSetUsesDecision(state: Object) {
        return (productSetId) => {
            return -1 !== state.decisionProductSetIds.indexOf(productSetId);
        }
    },

    isMoreDetailsProductSetStateDescription(state) {
        return (): Boolean => {
            return state.moreDetailsProductSetState === MORE_DETAILS_PRODUCT_SET_STATE.DESCRIPTION;
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
