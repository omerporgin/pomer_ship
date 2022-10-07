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
       '<img src="'+item.img+'" class="img-fluid">',
        '<b>' + item.headline + '</b>',
        item.language,
        self.format.isActive(item.active),
        self.format.updateButton(data, item.id, 'adminBlog'),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
