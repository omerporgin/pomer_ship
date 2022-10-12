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
        '<b>' + item.name + '</b> <br><small>' + item.serviceName+'</small>',
        self.format.yesNo(item.is_default),
        item.min,
        item.max,
        item.price,
        item.discount,
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
