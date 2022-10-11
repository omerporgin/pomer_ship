/**
 * Order Modalı içinde product, paket ekleme çıkarma gibi işlemler
 */
export class VendorOrderModalAddItems {

    constructor() {

        var self = this;

        $(function () {
            self.afterTableAdded();
        });

        $(document).off('click', "#form_order .delete_row").on('click', "#form_order .delete_row", function () {
            $(this).parent().parent().remove();
            self.afterTableAdded();
        });

        $(document).off('click', "#form_order .add_product").on('click', "#form_order .add_product", function () {
            var body = $(this).parent().parent().parent().parent().find("tbody");
            var productDivContent = $("#package_div_content tbody").html();
            productDivContent = productDivContent.replace(/#COUNT#/g, self.productRowCount);
            body.append(productDivContent);

            self.afterTableAdded();

            self.productRowCount++;
        });

        $(document).off('click', ".add_package").on('click', ".add_package", function () {

            var packageDivContent = $("#template_div #package_div_content").html();
            var thead = $(".form_order_table thead").html();
            var tfoot = $("#template_div #template_package").html();

            packageDivContent = packageDivContent.replace(/<\/thead>/g, thead + '</thead>');
            packageDivContent = packageDivContent.replace(/<tfoot><\/tfoot>/g, '<tfoot>' + tfoot + '</tfoot>');

            // count
            packageDivContent = packageDivContent.replace(/#COUNT#/g, self.productRowCount);
            self.productRowCount++;

            $("#form_order #packages_container").append(packageDivContent);

            self.afterTableAdded();
        });
    }


    /**
     * A table or Package added
     */
    afterTableAdded() {
        this.createSortableRows();
        this.onChange();
    }

    /**
     *
     */
    createSortableRows() {
        var self = this;
        $('tbody.sortable_tbody').sortable({
            revert: true,
            connectWith: "tbody.sortable_tbody",
            stop: function (event, ui) {
                self.onChange();
            }
        });
    }

    /**
     * On anything in modal changes
     */
    onChange() {
        this.countAndSetProductsInPackage();
        this.setEmptyTableHeight();
    }


    /**
     * Counts products in package and sets in input
     */
    countAndSetProductsInPackage() {
        var packageListText = [];
        $("#packages_container .package").each(function () {
            packageListText.push($(this).find("tbody tr").length);
        })

        $("input[name=package_list]").val(packageListText);
    }


    /**
     * tbody will be fully closed if there isnt any tr in it.
     * So we add some height to work properly...
     */
    setEmptyTableHeight() {
        $("table").css('border-collapse', 'separate');
        var firstTrHeight = $(".ui-sortable-handle").height();

        $('tbody.sortable_tbody').each(function () {
            if ($(this).find("tr").length == 0) {
                $(this).css('height', firstTrHeight);
            } else {
                $(this).css('height', 'inherit');
            }
        });
    }

}
