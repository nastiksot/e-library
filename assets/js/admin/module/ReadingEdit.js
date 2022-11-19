/* global window */
'use strict';

let ReadingEdit = {

    startAtSelector: 'input[type="date"][id$=startAt][name$="[startAt]"]',
    endAtSelector: 'input[type="date"][id$=endAt][name$="[endAt]"]',
    prolongAtSelector: 'input[type="date"][id$=prolongAt][name$="[prolongAt]"]',

    init() {

        let $startAt = $(this.startAtSelector);
        let $endAt = $(this.endAtSelector);
        let $prolongAt = $(this.prolongAtSelector);

        let minToday = new Date().toISOString().slice(0, 10);
        let minStartAt = $startAt.data('stored') ? $startAt.data('stored') : minToday;
        let minEndAt = $endAt.data('stored') ? $endAt.data('stored') : minStartAt;
        let minProlongAt = $prolongAt.data('stored') ? $prolongAt.data('stored') : minEndAt;

        $startAt.attr('min', minStartAt);
        $endAt.attr('min', minEndAt);
        $prolongAt.attr('min', minProlongAt);

        $startAt.on('change', () => {
            $endAt.attr('min', $startAt.val() ? $startAt.val() : minStartAt);
            $prolongAt.attr('min', $endAt.val() ? $endAt.val() : ($startAt.val() ? $startAt.val() : minStartAt));
        });

        $endAt.on('change', () => {
            $endAt.attr('min', $startAt.val() ? $startAt.val() : minStartAt);
            $prolongAt.attr('min', $endAt.val() ? $endAt.val() : ($startAt.val() ? $startAt.val() : minStartAt));
        });

        $prolongAt.on('change', () => {
            $prolongAt.attr('min', $endAt.val() ? $endAt.val() : ($startAt.val() ? $startAt.val() : minStartAt));
        });
    }
};

export default ReadingEdit;
