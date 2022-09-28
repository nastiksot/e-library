import Joi from "@hapi/joi";
import DecisionAnswer from "../index";

export const decisionAnswerKeys = {
    id: Joi.number().required(),
    parent_id: Joi.number().optional().allow(null),
    answer: Joi.string().optional().allow(null),
};

export const decisionAnswerSchema = Joi.object().keys(decisionAnswerKeys);

export default class ObjectToDecisionAnswerFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !decisionAnswerSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {DecisionAnswer}
     */
    static create(data) {
        Joi.assert(data, decisionAnswerSchema);

        return new DecisionAnswer(
            data.id,
            'undefined' !== typeof(data.parent_id) ? data.parent_id : null,
            'undefined' !== typeof(data.answer) ? data.answer : null,
        );
    }
}
