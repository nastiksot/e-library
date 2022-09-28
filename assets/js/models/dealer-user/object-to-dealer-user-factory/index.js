import Joi from "@hapi/joi";
import DealerUser from "../index";
import USER_ROLE from "../../../dictionary/user-role";

export const DealerUserKeys = {
    id: Joi.number().required(),
    email: Joi.string().optional().allow(null),
    first_name: Joi.string().optional().allow(null),
    last_name: Joi.string().optional().allow(null),
    role: Joi.string().valid(USER_ROLE.ROLE_DEALER_ADMIN, USER_ROLE.ROLE_DEALER_EMPLOYEE).required(),
    active: Joi.boolean(),
};

export const DealerUserSchema = Joi.object().keys(DealerUserKeys);

export default class ObjectToDealerUserFactory {
    /**
     * @param {Object} data
     *
     * @return {boolean}
     */
    static supports(data) {
        return !DealerUserSchema.validate(data).error;
    }

    /**
     * @param {Object} data
     * @return {DealerUser}
     */
    static create(data) {
        Joi.assert(data, DealerUserSchema);

        return new DealerUser(
            data.id,
            data.email,
            data.first_name,
            data.last_name,
            data.role,
            data.active,
        );
    }
}
