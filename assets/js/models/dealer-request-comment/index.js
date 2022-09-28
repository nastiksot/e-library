export default class DealerRequestComment {
    constructor(
        id: Number,
        message: String,
        createdAt: String,
        updatedAt: String,
    ) {
        this._id = id;
        this._message = message;
        this._createdAt = createdAt;
        this._updatedAt = updatedAt;
    }

    get id(): Number {
        return this._id;
    }

    get message(): String {
        return this._message;
    }

    get createdAt(): String {
        return this._createdAt;
    }

    get updatedAt(): String {
        return this._updatedAt;
    }
}
