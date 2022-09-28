import DecisionAnswer from "../decision-answer";
import Product from "../product";

export default class Decision {
    constructor(
        id: Number,
        answer: ?String,
        question: ?String,
        parentId:?Number,
        final: ?Boolean,
        positive: ?Boolean,
        action: ?String,
        answers: Array<DecisionAnswer>,
        replacedProducts: Array<Product>,
    ) {
        this._id = id;
        this._answer = answer;
        this._question = question;
        this._parentId = parentId;
        this._final = final;
        this._positive = positive;
        this._action = action;
        this._answers = answers;
        this._replacedProducts = replacedProducts;
    }

    get id(): Number {
        return this._id;
    }

    get answer(): ?String {
        return this._answer;
    }

    get question(): ?String {
        return this._question;
    }

    get parentId(): ?Number {
        return this._parentId;
    }

    get final(): ?Boolean {
        return this._final;
    }

    get positive(): ?Boolean {
        return this._positive;
    }

    get action(): ?String {
        return this._action;
    }

    get answers(): Array<DecisionAnswer> {
        return this._answers;
    }

    get replacedProducts(): Array<Product> {
        return this._replacedProducts;
    }
}
