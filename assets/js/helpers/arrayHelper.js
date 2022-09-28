import _transform from "lodash/transform";
import _isArray from "lodash/isArray";
import _isObject from "lodash/isObject";
import _camelCase from "lodash/camelCase";
import _snakeCase from "lodash/snakeCase";

const camelizeArrayKeys = obj => _transform(obj, (result, value, key, target) => {
    const camelKey = _isArray(target) ? key : _camelCase(key);
    result[camelKey] = _isObject(value) ? camelizeArrayKeys(value) : value;
});

const snakeArrayKeys = obj => _transform(obj, (result, value, key, target) => {
    const snakeKey = _isArray(target) ? key : _snakeCase(key);
    result[snakeKey] = _isObject(value) ? snakeArrayKeys(value) : value;
});

export {
    camelizeArrayKeys,
    snakeArrayKeys,
};
