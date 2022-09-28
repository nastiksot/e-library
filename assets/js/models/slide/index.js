import SLIDER_FILE_MIME_TYPE from "../../dictionary/slider-file-mime-type";

export default class Slide {
    constructor(
        id: Number,
        fileName: ?String,
        fileMimeType: ?String,
        fileMobileName: ?String,
        fileMobileMimeType: ?String,
        productSetId: ?Number,
        productSetPosition: ?String,
    ) {
        this._id = id;
        this._fileName = fileName;
        this._fileMimeType = fileMimeType;
        this._fileMobileName = fileMobileName;
        this._fileMobileMimeType = fileMobileMimeType;
        this._productSetId = productSetId;
        this._productSetPosition = productSetPosition;
    }

    get id(): Number {
        return this._id;
    }

    get fileName(): ?String {
        return this._fileName;
    }

    get fileMimeType(): ?String {
        return this._fileMimeType;
    }

    get fileMobileName(): ?String {
        return this._fileMobileName;
    }

    get fileMobileMimeType(): ?String {
        return this._fileMobileMimeType;
    }

    get productSetId(): ?Number {
        return this._productSetId;
    }

    get productSetPosition(): ?String {
        return this._productSetPosition;
    }
}
