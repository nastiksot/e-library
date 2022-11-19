/* global window */
'use strict';

let FilterDateRange = {

    startAtSelector: 'input[type="date"][id^=filter][name^=filter][name$="[value][start]"]',

    handleFilters() {

        let $dateRanges = $(this.startAtSelector);

        $dateRanges.each(function () {
            let startAt = $(this).val();

            let startAtName = $(this).attr('name');
            let endAtName = startAtName.replace('[start]', '[end]');
            let $endAt = $('input[name="' + endAtName + '"]');

            if ($endAt.length > 0) {
                $endAt.attr('min', startAt);
            }
        });

    },

    init() {

        $(this.startAtSelector).on('change', () => {
            this.handleFilters();
        }).trigger('change');

    }
};

export default FilterDateRange;
