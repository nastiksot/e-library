import Joi from "@hapi/joi";
import Partner from "../index";

export const partnerKeys = {
    id: Joi.number().required(),
    image: Joi.string().optional().allow(null),
    title: Joi.string().optional().allow(null),
};

export const partnerSchema = Joi.object().keys(partnerKeys);

export default class ObjectToPartnerFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !partnerSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {Partner}
     */
    static create(data) {
        Joi.assert(data, partnerSchema);

        return new Partner(
            data.id,
            data.image,
            data.title,
        );
    }
}
