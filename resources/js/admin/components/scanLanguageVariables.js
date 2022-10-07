/**
 *
 */
let ScanLanguageVariables = function () {
    this.defaulText = "";
    this.scanningText = "";
    this.afterScanText = "";

    this.init();
};

/**
 *
 */
ScanLanguageVariables.prototype.init = function () {

    var self = this;

    $('#scanLanguageVariablesBtn').on('click', function () {
        self.button = $(this);
        self.defaulText = $(this).text();
        self.scanningText = $(this).data("on_scan");
        self.afterScanText = $(this).data("after_scan");

        self.changeStyle(self.scanningText, "btn-danger");

        self.Scan();
    });
}

/**
 *
 * @param {string} text
 * @param {string} className
 */
ScanLanguageVariables.prototype.changeStyle = function (text, className) {
    this.button
        .removeClass("btn-danger")
        .removeClass("btn-primary")
        .removeClass("btn-success")
        .html(text)
        .addClass(className);
}

/**
 *
 */
ScanLanguageVariables.prototype.Scan = function () {

    var self = this;

    $.ajax({
        type: 'post',
        url: '/admin/language/scan',
        dataType: 'json',
        cache: false,
        crossDomain: true,
    }).done(function (data) {

        self.refreshDataTable();

        self.changeStyle(self.afterScanText, "btn-success");
        setTimeout(function () {
            self.changeStyle(self.defaulText, "btn-primary");
        }, 3000);

    }).fail(function (xhr, textStatus, errorThrown) {
        alert(errorThrown);
    });
}

/**
 * Ordering will refresh data table
 */
ScanLanguageVariables.prototype.refreshDataTable = function () {
    $("th:first-child").trigger('click').trigger('click');
}

export {
    ScanLanguageVariables
}
