/* global window */
'use strict';

let BookOrder = {

    startAtSelector: '#order_book_start_at',
    endAtSelector: '#order_book_end_at',

    init() {

        let $startAt = $(this.startAtSelector);
        let $endAt = $(this.endAtSelector);

        let minToday = new Date().toISOString().slice(0, 10);

        $startAt.attr('min', minToday);
        $endAt.attr('min', minToday);

        $startAt.on('change', () => {
            $endAt.attr('min', $startAt.val() ? $startAt.val() : minToday);
        });
    }
};

export default BookOrder;
