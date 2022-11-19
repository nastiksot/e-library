/* global window */
'use strict';
import BookOrder from './module/BookOrder';

let $ = window.$;

$(document).ready(() => {
    BookOrder.init();
});
