window._ = require('lodash');

try {
    window.Popper = require('popper.js');

    window.$ = window.jQuery = require('jquery');

    require('bootstrap');

    // rich text
    require('trumbowyg');
    global.moment = require('moment');
    require('tempusdominus-bootstrap-4');

    require('jquery-ui');
    require('jquery-ui/ui/widgets/sortable');
    require('jquery-ui/ui/disable-selection');

    require('datatables.net');
    require('datatables.net-bs4');
    require('datatables.net-buttons');
    require('datatables.net-buttons-bs4');
    require('datatables.net-rowreorder');
    require('datatables.net-rowreorder-bs4');

    require('select2');

    require('jquery-sortable-lists');

    require('jquery.easing');

    require('jquery-ui');

} catch (e) {
    console.log(e);
}

import {dataTable} from './admin/components/datatables/dataTable'

import {newModal} from './admin/components/newmodal/formModal'

import $ from 'jquery';

import {AdminLocalization} from './admin/pages/adminLocalization';

import {VendorPayment} from "./admin/pages/vendorPayment";

import {Order} from "./admin/pages/order";

import {VendorDashboard} from "./admin/pages/vendorDashboard";

import {SelectGtip} from "./admin/components/selectGtip";

import {bsAdmin} from "./admin/bsAdmin";

import {windowFunctions} from "./admin/windowFunctions";

import {WebSocket} from "./admin/components/webSocket";

import {componentGetPrice} from "./admin/components/componentGetPrice";

import {AdminShipping} from "./admin/pages/adminShipping";

import {VendorPackages} from "./admin/pages/vendorPackages";

windowFunctions();

/****************************************************************************************
 *  ON LOAD                                                                             *
 ****************************************************************************************/
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var userData = getUserData();

    var pagetable = new dataTable();

    /**
     * lc_swith format :
     *  <input type="hidden" name="is_default" value="0"/>
     *  <input type="checkbox" name="is_default" value="1" data-on_txt="Yes" data-off_txt="No" class="switch">
     */
    lc_switch('.switch');

    fixBootstrapTabs();

    dateTimePicker();

    new newModal();

    tooltip();

    new SelectGtip;

    new WebSocket(userData);

    bsAdmin();

    prettyJson();

    /**
     * PAGES
     */
    if ($('#admin_localization').length > 0) {
        new AdminLocalization();
    }

    if ($('#vendor_dashboard').length > 0) {
        new VendorDashboard();
    }

    if ($('#vendor_order').length > 0 || $('#admin_order').length > 0) {
        new Order(pagetable);
    }

    if ($('#vendor_payment').length > 0) {
        new VendorPayment;
    }

    if ($('#admin_shipping').length > 0) {
        new AdminShipping;
    }

    if ($('#vendor_packages').length > 0) {
        new VendorPackages;
    }

    openCurrentMenuItem()

    new componentGetPrice();

});
