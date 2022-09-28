import {ALERT_MESSAGE, ALERT_COLOR, ALERT_ICON} from "../../dictionary/decision-action";

export default class AlertMessage {

    constructor(
        type: ?String = ALERT_MESSAGE.INFO,
        message: ?String,
        color: ?String = ALERT_COLOR.INFO,
        icon: ?String = ALERT_ICON.INFO,
    ) {
        this._type = type;
        this._message = message;
        this._color = color;
        this._icon = icon;
    }

    get type(): ?String {
        return this._type;
    }

    get message(): ?String {
        return this._message;
    }

    get color(): ?String {
        return this._color;
    }

    get icon(): ?String {
        return this._icon;
    }
}
