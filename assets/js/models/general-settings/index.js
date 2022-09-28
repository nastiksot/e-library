export default class GeneralSettings {
    constructor(
        socialFacebook: ?String,
        socialYoutube: ?String,
        socialInstagram: ?String,
    ) {
        this._socialFacebook = socialFacebook;
        this._socialYoutube = socialYoutube;
        this._socialInstagram = socialInstagram;
    }

    get socialFacebook(): ?String {
        return this._socialFacebook;
    }

    get socialYoutube(): ?String {
        return this._socialYoutube;
    }

    get socialInstagram(): ?String {
        return this._socialInstagram;
    }
}
