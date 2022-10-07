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
        '<b>' + item.name + '</b>',
        self.format.updateButton(data, item.id, 'adminUserGroup'),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
