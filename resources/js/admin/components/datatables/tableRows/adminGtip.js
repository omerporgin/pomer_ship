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
        '<b>' + item.gtip + '</b>',
        item.description,
        item.unit,
        self.format.updateButton(data, item.id),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
