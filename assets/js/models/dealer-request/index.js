import DealerRequestComment from "../dealer-request-comment";

export default class DealerRequest {
    constructor(
        id: Number,
        contactName: ?String,
        email: ?String,
        phone: ?String,
        address: ?String,
        message: ?String,
        status: String,
        createdAt: String,
        updatedAt: String,
        comments: Array<DealerRequestComment>,
        dealerUid: String,
        dealerSlug: String,
        wishListUid: ?String,
        userId: ?Number,
    ) {
        this._id = id;
        this._contactName = contactName;
        this._email = email;
        this._phone = phone;
        this._address = address;
        this._message = message;
        this._status = status;
        this._createdAt = createdAt;
        this._updatedAt = updatedAt;
        this._comments = comments;
        this._dealerUid = dealerUid;
        this._dealerSlug = dealerSlug;
        this._wishListUid = wishListUid;
        this._userId = userId;
    }

    get id(): Number {
        return this._id;
    }

    get contactName(): ?String {
        return this._contactName;
    }


    get email(): ?String {
        return this._email;
    }

    get phone(): ?String {
        return this._phone;
    }

    get address(): ?String {
        return this._address;
    }

    get message(): ?String {
        return this._message;
    }

    get status(): String {
        return this._status;
    }

    set status(status: String) {
        this._status = status;
    }

    get createdAt(): String {
        return this._createdAt;
    }

    get updatedAt(): String {
        return this._updatedAt;
    }

    get comments(): Array<DealerRequestComment> {
        return this._comments;
    }

    set comments(comments: Array<DealerRequestComment>) {
        this._comments = comments;
    }

    get dealerUid(): String {
        return this._dealerUid;
    }

    get dealerSlug(): String {
        return this._dealerSlug;
    }

    get wishListUid(): ?String {
        return this._wishListUid;
    }

    get userId(): ?Number {
        return this._userId;
    }
}
