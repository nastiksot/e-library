import Joi from "@hapi/joi";
import AlertMessage from "../index";
import {ALERT_MESSAGE, ALERT_COLOR, ALERT_ICON} from "../../../dictionary/decision-action";

export const AlertMessageKeys = {
    type: Joi.string().valid(...Object.values(ALERT_MESSAGE)).optional().allow(null),
    message: Joi.string().optional().allow(null),
    icon: Joi.string().optional().allow(null),
    color: Joi.string().optional().allow(null),
};

export const AlertMessageSchema = Joi.object().keys(AlertMessageKeys);

export default class ObjectToAlertMessageFactory {

    /**
     * @param {Object} data
     * @return {boolean}
     */
    static supports(data) {
        return !AlertMessageSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {AlertMessage}
     */
    static create(data) {
        Joi.assert(data, AlertMessageSchema);
        return new AlertMessage(
            data.type,
            data.icon,
            data.color,
            data.message,
        );
    }

    /**
     * @param {String} message
     * @return {AlertMessage}
     */
    static createInfo(message) {
        return new AlertMessage(ALERT_MESSAGE.INFO, message, ALERT_COLOR.INFO, ALERT_ICON.INFO);
    }

    /**
     * @param {String} message
     * @return {AlertMessage}
     */
    static createSuccess(message) {
        return new AlertMessage(ALERT_MESSAGE.SUCCESS, message, ALERT_COLOR.SUCCESS, ALERT_ICON.SUCCESS);
    }

    /**
     * @param {String} message
     * @return {AlertMessage}
     */
    static createWarning(message) {
        return new AlertMessage(ALERT_MESSAGE.WARNING, message, ALERT_COLOR.WARNING, ALERT_ICON.WARNING);
    }

    /**
     * @param {String} message
     * @return {AlertMessage}
     */
    static createError(message) {
        return new AlertMessage(ALERT_MESSAGE.ERROR, message, ALERT_COLOR.ERROR, ALERT_ICON.ERROR);
    }
}
