export default class DealerUser {
    constructor(
        id: Number,
        email: ?String,
        firstName: ?String,
        lastName: ?String,
        role: String,
        active: Boolean,
    ) {
        this._id = id;
        this._email = email;
        this._firstName = firstName;
        this._lastName = lastName;
        this._role = role;this._active = active;
    }

    get id(): String {
        return this._id;
    }

    get email(): ?String {
        return this._email;
    }

    get firstName(): ?String {
        return this._firstName;
    }

    get lastName(): ?String {
        return this._lastName;
    }

    get role(): String {
        return this._role;
    }

    get active(): Boolean {
        return this._active;
    }
}
