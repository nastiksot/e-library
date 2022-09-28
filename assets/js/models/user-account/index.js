import USER_ROLE from "../../dictionary/user-role";

export default class UserAccount {
    constructor(
        uid: String,
        locale: String,
        id: ?Number,
        email: ?String,
        roles: Array,
        firstName: ?String,
        lastName: ?String,
        acceptNews: ?Boolean,
        acceptProcessPersonalData: ?Boolean,
        acceptPrivacyPolicy: ?Boolean,
        dealerUid: ?String,
        cartAddProductsUrl: String,
        dealerSearchUrl: String,
    ) {
        this._uid = uid;
        this._locale = locale;
        this._id = id;
        this._email = email;
        this._roles = roles;
        this._firstName = firstName;
        this._lastName = lastName;
        this._acceptNews = acceptNews;
        this._acceptProcessPersonalData = acceptProcessPersonalData;
        this._acceptPrivacyPolicy = acceptPrivacyPolicy;
        this._dealerUid = dealerUid;
        this._cartAddProductsUrl = cartAddProductsUrl;
        this._dealerSearchUrl = dealerSearchUrl;
    }

    get isLoggedIn(): Boolean {
        return this._email !== null;
    }

    get uid(): String {
        return this._uid;
    }

    get locale(): String {
        return this._locale;
    }

    get id(): ?Number {
        return this._id;
    }

    get email(): ?String {
        return this._email;
    }

    get roles(): Array {
        return this._roles;
    }

    get firstName(): ?String {
        return this._firstName;
    }

    get lastName(): ?String {
        return this._lastName;
    }

    get acceptNews(): ?Boolean {
        return this._acceptNews;
    }

    get acceptProcessPersonalData(): ?Boolean {
        return this._acceptProcessPersonalData;
    }

    get acceptPrivacyPolicy(): ?Boolean {
        return this._acceptPrivacyPolicy;
    }

    get cartAddProductsUrl(): String {
        return this._cartAddProductsUrl;
    }

    get dealerUid(): ?String {
        return this._dealerUid;
    }

    get dealerSearchUrl(): String {
        return this._dealerSearchUrl;
    }

    isDealerRole(): Boolean {
        return this.isDealerAdminRole() || this.isDealerEmployeeRole();
    }

    isDealerAdminRole(): Boolean {
        return this.roles.includes(USER_ROLE.ROLE_DEALER_ADMIN);
    }

    isDealerEmployeeRole(): Boolean {
        return this.roles.includes(USER_ROLE.ROLE_DEALER_EMPLOYEE);
    }
}
