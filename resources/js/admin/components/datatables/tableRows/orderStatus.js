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

    var fromList = '';
    $.each(item.from, function (index, fromItem) {
        fromList += '<span class="order_satus" style="background:#' + fromItem.color + ';">' + fromItem.name + '</span>';
    });

    var toList = '';
    $.each(item.to, function (index, toItem) {
        toList += '<span class="order_satus" style="background:#' + toItem.color + ';">' + toItem.name + '</span>';
    });
    var statusList = ['Vendor', 'ShipExporgin'];

    return [
        item.id,
        '<b>' + item.name + '</b> ',
        fromList,
        toList,
        `
            <div class="text-center">
                <span class="order_satus" style="background:#` + item.color + `;">
                    #` + item.color + `
                </span>
            </div>`,
        `
            <div class="text-center">
                ` + statusList[item.status_of] + `
            </div>`
    ];
}

export {
    DataTableOnLoad
}
