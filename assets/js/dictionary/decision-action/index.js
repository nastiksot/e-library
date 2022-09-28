"use strict";

/**
 * @readonly
 * @enum {String}
 */
const DECISION_ACTION = Object.freeze({
    REPLACE_MAIN: 'replace_main',
    DELETE_MAIN: 'delete_main',
    KEEP_MAIN: 'keep_main',
});

/**
 * @readonly
 * @enum {String}
 */
const PRODUCT_SET_POSITION = Object.freeze({
    CENTER: 'center',
    LEFT: 'left',
    RIGHT: 'right',
});

/**
 * @readonly
 * @enum {String}
 */
const ALERT_MESSAGE = Object.freeze({
    INFO: 'info',
    SUCCESS: 'success',
    WARNING: 'warning',
    ERROR: 'error',
});

/**
 * @readonly
 * @enum {String}
 */
const ALERT_COLOR = Object.freeze({
    INFO: '#fcac22',
    SUCCESS: '#fcac22',
    WARNING: '#fcac22',
    ERROR: '#fcac22',
});

/**
 * @readonly
 * @enum {String}
 */
const ALERT_ICON = Object.freeze({
    INFO: null,
    SUCCESS: null,
    WARNING: null,
    ERROR: null,
});

export {
    DECISION_ACTION,
    PRODUCT_SET_POSITION,
    ALERT_MESSAGE, ALERT_COLOR, ALERT_ICON,
};
