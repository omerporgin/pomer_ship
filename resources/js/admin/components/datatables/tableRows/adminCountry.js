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
        item.iso3,
        item.iso2,
        item.capital,
        item.currency,
        item.region,
        item.subregion,
        self.format.yesNo(item.is_accepted),
        self.format.updateButton(data, item.id),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
