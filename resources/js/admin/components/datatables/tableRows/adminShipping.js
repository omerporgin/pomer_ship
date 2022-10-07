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

    var priceUpdateUrl = data.update_shipping_price_url.replace('###', item.id)

    return [
        item.id,
        '<img src="'+item.img+'" class="img-fluid img-thumbnail">',
        item.account_number,
        '<b>' + item.name + '</b>',
        `<div class="text-center update-zones" title="" data-id="`+item.id+`"">
            <span href="#" class="btn btn-secondary btn-circle btn-sm">
               <i class="fas fa-map-marked-alt"></i>
            </span>
        </div>`,
        `<div class="text-center open-modal" title="" data-url="`+priceUpdateUrl+`">
            <span href="#" class="btn btn-warning btn-circle btn-sm">
               <i class="fas fa-redo-alt"></i>
            </span>
        </div>`,
        `<div class="text-center goto-price-url" title="" data-id="`+item.id+`">
            <span href="#" class="btn btn-info btn-circle btn-sm">
               <i class="fas fa-coins"></i>
            </span>
        </div>`,
        self.format.isActive(item.is_active),
        self.format.updateButton(data, item.id),
    ];
}

export {
    DataTableOnLoad
}
