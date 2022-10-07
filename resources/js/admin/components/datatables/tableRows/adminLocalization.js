/**
 *
 * @param {*} rows
 * @param {*} methodName
 * @param {*} data
 */
let DataTableOnLoad = function (formatObj, item, data) {
    this.format = formatObj;
    var self = this;

    return [
        item.id,
        item.variable,
        item.value,
        self.format.updateButton(data, item.id),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
