"use strict";

/**
 * @readonly
 * @enum {String}
 */
const DEALER_REQUEST_STATUS = Object.freeze({
    NEW: "new",
    ANSWERED: "answered",
    MEETING_PLANNED: "meeting_planned",
    CLOSED: "closed",
});

export default DEALER_REQUEST_STATUS;
