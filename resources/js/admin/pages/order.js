import {DownloadOrders} from "../components/downloadOrders";
import {PrintLabel} from "../components/printLabel";
import {UploadInvoice} from "../components/uploadInvoice";
import $ from "jquery";

/**
 *
 * @param pagetable
 * @constructor
 */
export class Order {


    constructor(pagetable) {

        this.table = pagetable;
        this.ratios = [];

        // Run only in vendor page
        if ($('#vendor_order').length > 0) {
            new DownloadOrders(pagetable);
        }

        // Order page components
        new PrintLabel;

        new UploadInvoice;
        // end

        this.events();

        this.selectVendor = $('.select2_vendor');

        this.productRowCount = 0;
    }

    /**
     *
     */
    events() {

        var self = this;

        $(function () {

            $(".select_all").on('click', function () {
                $(".dataTable tr td:first-child").trigger("click");
                $(".form-check-input").prop('checked', true);
            });

            self.orderStatusEvent();

            /**
             * data table daki kson alanını değiştir
             * data table baştan oluştur.
             */
            $(".order_status_select2").change(function () {
                var val = $(this).val();

                self.table.updateBase64Json('order_statuses', val);

                self.table.refresh();
            });

            var select2Vendor = self.select2VendorEvent();
        })
    }

    /**
     *
     * @param state
     * @returns {*|jQuery|HTMLElement}
     */
    template(state) {
        var bg = $(state.element).css('background');
        if (!state.id) {
            return state.text;
        }

        var $state = $(
            '<div style="color:#fff;padding:3px;font-size:0.8em;background: ' + bg + '"> ' + state.text + '</div>'
        );
        return $state;
    }

    /**
     *
     */
    orderStatusEvent() {
        var self = this;
        var placeholder = $(".order_status_select2").data("placeholder");
        $(".order_status_select2").select2({
            placeholder: placeholder,
            allowClear: true,
            templateResult: self.template
        });
    }

    /**
     *
     */
    select2VendorEvent() {

        var self = this;

        let url = self.selectVendor.data("url");
        let placeholder = self.selectVendor.data("placeholder");

        self.selectVendor.select2({
            placeholder: placeholder,
            ajax: {
                url: url,
                delay: 250, // wait 250 milliseconds before triggering the request
                method: 'post',
                dataType: 'json',
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                data: function (params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data, params) {
                    return {
                        results: data.results
                    };
                }
            }
        });

        self.selectVendor.on('select2:select', function (e) {
            console.log(self.selectVendor.val());
        });

        /**
         * Search button
         */
        $("#admin_orders #filter_orders").off('click').on('click', function () {
            self.refresh();
        })
    }

    /**
     *
     */
    refresh() {

        var self = this;

        var data = JSON.parse($(".dataTable").attr("data-data"));
        data.vendors = self.selectVendor.val();

        var stringData = JSON.stringify(data);

        $(".dataTable").attr("data-data", stringData);
        this.table = new dataTable(null);
    }

    /**
     *  Formatting function for row details - modify as you need
     */
    createPackageRow(data) {

        var self = this;
        if (typeof data == 'undefined') {
            return;
        }
        var description = $(data[0]).attr('data-description');
        var packageData = $(data[0]).attr('data-packages');
        var isLabellable = ($(data[0]).attr('data-is_labellable') == 'true');
        var orderId = null;

        var isLabellableReason = $(data[0]).attr('data-is_labellable_reason');
        if (isLabellable) {
            var tableBodyContent = '';
            var tdContent = '';
            var packageDataJson = JSON.parse(packageData.replace(/'/g, '"'));
            if (packageData != []) {

                var topContent = $(".template #label-top-content").html();

                var table_header = $(".template #table_header table").html();

                var template = $("#table-order-child tbody").html();
                var template_footer = $("#table-order-child tfoot").html();

                $.each(packageDataJson, function (index, item) {

                    // Tüm paketler için shipment_id aynı
                    topContent = topContent.replace('__selected_' + item.shipment_id + '__', ' selected="selected" ');
                    topContent = topContent.replace(/__ORDER_ID__/g, item.order_id);
                    topContent = topContent.replace(/__DESCRIPTION__/g, description);

                    if (item.height == null) {
                        item.height = '';
                    }
                    if (item.width == null) {
                        item.width = '';
                    }
                    if (item.length == null) {
                        item.length = '';
                    }
                    if (item.weight == null) {
                        item.weight = '';
                    }

                    if (item.description == null) {
                        item.description = '';
                    }
                    var processedTemplate = template;
                    processedTemplate = processedTemplate.replace(/__HEIGHT__/g, item.height);
                    processedTemplate = processedTemplate.replace(/__PAKAGE_ID__/g, item.id);
                    processedTemplate = processedTemplate.replace(/__DESCTIPTION__/g, item.description);
                    processedTemplate = processedTemplate.replace(/__WIDTH__/g, item.width);
                    processedTemplate = processedTemplate.replace(/__LENGTH__/g, item.length);
                    processedTemplate = processedTemplate.replace(/__WEIGHT__/g, item.weight);

                    tableBodyContent += processedTemplate;
                });

                orderId = packageDataJson[0].order_id;

                tdContent += `<div class="text-right pb-2">` + topContent + `</div>
            <table class="table table-bordered table-sm order_child_table hide_on_success_` + orderId + `" id="package_table_` + orderId + `">
                ` + table_header + `
                <tbody>
                    ` + tableBodyContent + `
                </tbody>
                <tfoot class="label_msg_container">
                   ` + template_footer + `
                </tfoot>
            </table>`;
            }
        } else {
            tdContent = isLabellableReason;
        }

        return [tdContent, orderId];
    }

    /**
     * Bu metod row oluşturktan sonra çalışmalı.
     * Row datatable ile oluşuyor.
     */
    loadShippingServicesInPackageRow(orderId) {
        var selectBox = $('.select_service_' + orderId);
        console.log(selectBox.attr('data-processed'))
        if (selectBox.attr('data-processed') == '0') {
            selectBox.append($('<option/>', {
                value: '',
                text: selectBox.data('loading_text')
            }));
            var ajaxUrl = selectBox.data('url');

            $.ajax({
                type: 'post',
                url: ajaxUrl,
                dataType: 'json',
                cache: false,
                crossDomain: true,
                data: {
                    orderId: orderId
                },
            }).done(function (result) {

                return true;

            }).fail(function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
                return false;
            });

        }
        selectBox.attr('data-processed', 1);
    }

}
