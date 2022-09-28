import Slide from "../slide";
import SLIDER_FILE_MIME_TYPE from "../../dictionary/slider-file-mime-type";

export default class Slider {
    constructor(
        id: Number,
        fileName: ?String,
        fileMimeType: ?String,
        fileMobileName: ?String,
        fileMobileMimeType: ?String,
        title: ?String,
        subTitle: ?String,
        slides: Array<Slide>,
    ) {
        this._id = id;
        this._fileName = fileName;
        this._fileMimeType = fileMimeType;
        this._fileMobileName = fileMobileName;
        this._fileMobileMimeType = fileMobileMimeType;
        this._title = title;
        this._subTitle = subTitle;
        this._slides = slides;
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

    get title(): ?String {
        return this._title;
    }

    get subTitle(): ?String {
        return this._subTitle;
    }

    get slides(): Array<Slide> {
        return this._slides;
    }
}
