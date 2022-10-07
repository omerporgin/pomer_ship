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
        item.country,
        item.state_name,
        '<b>' + item.name + '</b>',
        self.format.yesNo(item.is_accepted),
        self.format.updateButton(data, item.id),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
