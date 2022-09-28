import Joi from "@hapi/joi";
import {DECISION_ACTION} from "../../../dictionary/decision-action";
import Decision from "../index";
import ObjectToDecisionAnswerFactory, {decisionAnswerSchema} from "../../decision-answer/object-to-decision-answer-factory";
import ObjectToProductFactory, {productSchema} from "../../product/object-to-product-factory";

export const decisionKeys = {
    id: Joi.number().required(),
    answer: Joi.string().optional().allow(null),
    question: Joi.string().optional().allow(null),
    parent_id: Joi.number().optional().allow(null),
    final: Joi.boolean().optional().allow(null),
    positive: Joi.boolean().optional().allow(null),
    action: Joi.string().valid(...Object.values(DECISION_ACTION)).optional().allow(null),
    answers: Joi.array().items(decisionAnswerSchema).optional().allow(null),
    replaced_products: Joi.array().items(productSchema).optional().allow(null),
};

export const decisionSchema = Joi.object().keys(decisionKeys);

export default class ObjectToDecisionFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !decisionSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Decision}
     */
    static create(data) {
        Joi.assert(data, decisionSchema);

        return new Decision(
            data.id,
            data.answer,
            data.question,
            data.parent_id,
            data.final,
            data.positive,
            data.action,
            'undefined' !== typeof (data.answers) ? data.answers.map(item => ObjectToDecisionAnswerFactory.create(item)) : [],
            data.replaced_products.map(item => ObjectToProductFactory.create(item)),
        );
    }
}
