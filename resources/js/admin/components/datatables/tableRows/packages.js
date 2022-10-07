/**
 *
 * @param formatObj
 * @param item
 * @returns {(*|string)[]}
 * @constructor
 */
let DataTableOnLoad = function (formatObj, item, data) {
    this.format = formatObj;
    var self = this;

    return [
        item.id,
        item.shipment_id,
        item.tracking_number,
        item.tracking_status,
        '<b>' + item.order_id + '</b> ',
        item.id,
        item.estimated_price,
        item.id,
        `<div class="text-center">
            <i class="fas fa-print text-primary print_package" data-id="` + item.id + `"></i>
        </div>`,
    ];
}

export {
    DataTableOnLoad
}
