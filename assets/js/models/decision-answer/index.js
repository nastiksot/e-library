export default class DecisionAnswer {
    constructor(
        id: Number,
        parentId: ?Number,
        answer: ?String,
    ) {
        this._id = id;
        this._parentId = parentId;
        this._answer = answer;
    }

    get id(): Number {
        return this._id;
    }

    get parentId(): ?Number {
        return this._parentId;
    }

    get answer(): ?String {
        return this._answer;
    }
}
