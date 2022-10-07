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
    var userType = ['Kişisel', 'Kurumsal'];
    return [
        item.id,
        '<img src="'+item.avatar+'" class="img-thumbnail rounded-circle">',
        '<b>' + item.name + '</b>',
        '<b>' + item.surname + '</b>',
        item.email,
        item.permission_name,
        userType[item.user_type],

        self.format.updateButton(data, item.id, 'adminUserModal'),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
