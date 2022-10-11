import {VendorOrderModalAddItems} from './vendorOrderModalAddItems';
import {VendorOrderModalLocation} from "./vendorOrderModalLocation";
import {SelectPostCode} from "../../selectPostCode";
import $ from "jquery";

import {UploadInvoice} from '../../uploadInvoice';
import {SelectCity} from "../../selectCity";

/**
 *
 */
export class FormModalOnLoad {

    constructor() {

        new VendorOrderModalAddItems();

        prettyJson();

        this.events();

        this.modalFix();

        let location = new VendorOrderModalLocation('#modal_theme_primary');
        location.reset();


            $(".full_search").removeClass("d-none");
            $(".toggle_full_search").removeClass("d-none");


        new SelectPostCode();

        new UploadInvoice();

        new SelectCity();

    }

    /**
     *
     */
    events() {

        var self = this;

        $(".number_packages").click(function () {
            $(".main_button").trigger('click');
        });

        $(".create_documents").on('click', function () {
            let orderId = $(this).data('order_id');
            let ajaxUrl = $(this).data('url');
            self.createDocuments(orderId, ajaxUrl);
        });

        $(document).on('change', "select[name^=type]", function () {
            console.log(111);
            let currentTr = $(this).parent().parent();

            if ($(this).val() == 0) {
                currentTr.find(".d-none").removeClass("d-none");
            } else {
                currentTr.find(".show_by_type").addClass("d-none").val('');
            }
        })

        $('.js_choose_date').on('click',function () {
            let date = $(this).data('date')
            $(this).parent().parent().find('input[name=shipped_at]').val(date)
            $(this).parent().find('.bg-danger').removeClass('bg-danger text-light')
            $(this).addClass('bg-danger text-light').removeClass('bg-light')
        })
    }

    /**
     * Fix for :
     * When two bootstrap modals open one after one. Slider disabled when one closed.
     * We make every modal slidable again.
     */
    modalFix() {
        $(".modal").on('hide.bs.modal', function () {
            $('.modal').css({
                'overflow-y': 'auto'
            })
        })
    }

    /**
     *
     */
    createDocuments(orderId, ajaxUrl) {
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
            console.log(result);
            return true;

        }).fail(function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);
            return false;
        });
    }

}
