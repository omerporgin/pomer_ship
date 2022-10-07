let DownloadOrders = function (pagetable) {
    this.table = pagetable;
    this.events();

}

/**
 *
 */
DownloadOrders.prototype.events = function () {

    var self = this;

    $(".download-orders").click(function () {
        self.downloadOrderService($(this));
    })
}

/**
 *
 */
DownloadOrders.prototype.downloadOrderService = function (item) {
    var self = this;
    self.url = item.data("url");
    var firstText = item.text();
    var onAction = item.data("on_action");
    item.html(onAction);

    return $.ajax({
        type: 'POST',
        url: self.url,
        dataType: 'json',
        cache: false,
        crossDomain: true,
        data: {}
    }).done(function (data) {
        if (data.result) {
            $(".download-orders").text(data.count + ' Sonuç / ' + data.saved_items + ' Kayıt');
        } else {
            $(".download-orders").text('Hata. ' + data.err);
        }
        console.log(data);
        self.table.refresh();
        return true;

    }).fail(function (xhr, textStatus, errorThrown) {
        console.log(xhr);
        console.log(errorThrown);
        console.log(textStatus);
        var jsonErrorList = JSON.parse(xhr.responseJSON);
        console.log(jsonErrorList);
        return false;

    });
}

export {
    DownloadOrders
}
