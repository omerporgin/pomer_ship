/**
 *
 * @param {*} rows
 * @param {*} methodName
 * @param {*} data
 */
let DataTableOnLoad = function (formatObj, item, data) {

    this.format = formatObj;
    var self = this;
//    var updateUrl=
    let jsonPackages = JSON.stringify(item.packages).replaceAll('\"', "'");

    var description = item.description
    if(description == null){
        description = ''
    }

    return [
        `<div
                data-packages="` + jsonPackages + `"
                data-description="` + description + `"
                data-is_labellable="` + item.is_labellable + `"
                data-is_labellable_reason="` + item.is_labellable_reason + `">
            ` + item.id + `
        </div>`,
        item.order_id,
        `<span class="order_satus" style="background:#` + item.real_status_color + `;">
            ` + item.real_status_name + `
        </span>`,
        '<img src="/img/entegrations/' + item.entegration_id + '.png" class="vendor-img-sm">',
        item.email,
        '<b>' + item.full_name + '</b>',
        item.tracking_no,
        item.shipment_name,
        item.country,
        item.packages.length,
        `<div class="upload_invoice text-center" data-id="` + item.id + `">
             <span href="#" class="btn btn-warning btn-circle btn-sm">
               <i class="far fa-file-alt"></i>
            </span>
        </div>`,
        self.format.updateButton(data, item.id,'vendorOrderModal'),
        self.format.deleteButton(item),
    ];
}

export {
    DataTableOnLoad
}
