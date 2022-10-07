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

    var dataJson = JSON.parse(item.data);
    var dataText = '';
    $.each(dataJson, function (index, item) {
        dataText += '<b class="text-danger">' + index + ' : </b> ' + item + '<br>';
    });

    return [
        item.id,
        item.notification,
        self.format.yesNo(item.is_read),
        dataText,
        self.format.timeAgo(item.created_at),
    ];
}

export {
    DataTableOnLoad
}
