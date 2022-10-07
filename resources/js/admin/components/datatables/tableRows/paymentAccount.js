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
        item.payment_name,
        item.installment,
        '<span class="'+item.status_name+'">'+item.status_name+'</span>',
        '<b>'+item.total + '</b> TL',
        self.format.timeAgo(item.created_at),
    ];
}

export {
    DataTableOnLoad
}
