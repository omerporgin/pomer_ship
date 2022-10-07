import $ from "jquery";

import {SelectCity} from "../../admin/components/selectCity";
/**
 *
 */
export class Register {

    constructor() {
        this.tabOnChange();
        this.events();
        this.showInvoiceAddress();

        new SelectCity()
    }

    /**
     *
     */
    events() {
        var self = this;
        $('input[name=is_same_address]').on('change', function () {
            self.showInvoiceAddress();
        })

        $('.masked-phone').on('keyup', function (e) {
            var value = $(this).val();
            value = value.replace(/\D/g, '')
            if (value[0] == 0) {
                value = value.substring(1);
            }
            if (value.length > 10) {
                value = value.substring(0, 10);
            }

            if (value.length == 10) {
                var x = value.match(/(\d{3})(\d{3})(\d{4})/);
                $(this).val('0 (' + x[1] + ') ' + x[2] + '-' + x[3]);
            }
        });

        $(function () {
            $('.masked-phone').trigger('keyup');
        })

    }

    /**
     *
     */
    showInvoiceAddress() {
        let checkboxStatus = $('input[name=is_same_address]:checked').val()
        if (checkboxStatus != '1') {
            $(".invoice_address").show();
        } else {
            $(".invoice_address").hide();
        }
    }

    /**
     *
     */
    tabOnChange() {
        var tabEl = document.querySelector('#register_tab')
        if (tabEl != null) {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                var current = event.target // newly activated tab
                event.relatedTarget // previous active tab,

                $("input[name=user_type]").val($(current).attr('data-id'));
            })
        }

        // Onload select the right tab
        let currentUserType = $("input[name=user_type]").val();
        $('#register_tab .nav-link[data-id='+currentUserType+']').trigger('click');
    }


}
