import {AjaxForms} from '../forms/ajaxForms'

import {FormatDataTable} from "./formatDataTable";
import $ from "jquery";
import {Order} from "../../pages/order";

/**
 *
 * @param {*} className
 */
export class dataTable {

    constructor(className = null) {
        if (className == null) {
            this.className = ".dataTable";
        } else {
            this.className = className;
        }

        this.class = $(this.className);

        if (this.class.length != 0) {

            this.url = this.class.data('url');
            this.succcess = this.class.data('success');

            this.data = this.parseData();

            this.sort = this.class.data('sort');
            this.sortUrl = this.class.data('sort_url');
            this.childRowFormat = this.class.data('child_row_format');

            if (this.class.data('placeholder') != undefined) {
                this.searchPlaceholder = this.class.data('placeholder');
            } else {
                this.searchPlaceholder = "Search records";
            }

            this.paging = true;
            if (this.class.data('paging') != undefined) {
                if (this.class.data('paging') == "false") {
                    this.paging = false;
                }
            }

            var orderableTargets = this.class.data('orderable').toString().split(',');
            this.orderableTargets = $.map(orderableTargets, function (value) {
                return parseInt(value, 10);
            });

            this.create();
        }
    }

    parseData() {
        let data = this.class.data('data');
        if (typeof data != 'undefined') {
            data = atob(data);
            data = JSON.parse(data)
            return data
        }
    }

    /**
     * Deletes table if exist
     */
    destroyTable() {
        if ($.fn.DataTable.isDataTable(this.className)) {
            $(this.className).DataTable().destroy();
        }
    }

    /**
     * @param string $classname
     *      data-url = ajax url
     *      data-data = any string data
     *      data-orderable = 1,2,4 (nonorderable table columns)
     *      data-sort = false||true (Needs .sortable_table )
     *      data-on_succcess="successFunction"
     */
    create() {

        var self = this;

        self.destroyTable();

        self.reloadButton();

        var tableOptions = {
            language: {
                searchPlaceholder: this.searchPlaceholder,
            },
            paging: self.paging,
            processing: true,
            rowReorder: false,
            async: false,
            autoWidth: false,
            order: [
                [0, "desc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: self.orderableTargets
            }, {
                "defaultContent": "-",
                "targets": "_all",
            }],
            drawCallback: function (settings) {
                var response = settings.json;
            },
            //dom: 'lBfrti',
            footerCallback: function (row, data, start, end, display) {

            }
        };

        if (self.url != "") {
            tableOptions.serverSide = true;
            tableOptions.ajax = {
                type: "GET",
                url: self.url,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                data: function (data) {
                    data.data = self.class.attr('data-data'); // Must be fresh
                },
                error: function (request, status, error) {
                    console.log(status, error);
                }
            }
        }

        if (this.sort) {
            tableOptions.rowReorder = {
                update: false,
                selector: 'td:first-child',
            };
            tableOptions.paging = false;
            tableOptions.order = false;
            tableOptions.ordering = false;
            tableOptions.info = false;
            self.class.addClass("sortable_table");
        }
        this.tableOptions = tableOptions;

        this.table = self.class.DataTable(tableOptions);

        /**
         *
         */
        this.table.off('draw').on('draw', function () {
            new AjaxForms();
            window.tooltip();
        });

        /**
         *
         */
        this.table.off('page.dt').on('page.dt', function () {
            console.log('Page');
        })

        /**
         *
         */
        this.table.off('length.dt').on('length.dt', function (e, settings, len) {
            console.log('New page length: ' + len);
        });

        /**
         * Sort
         */
        this.table.off('row-reorder').on('row-reorder', function (e, diff, edit) {

            var val = $("tr td:first-child");
            var data = [];
            $.each(val, function (index, item) {
                data.push($(item).text());
            });

            $.ajax({
                type: "POST",
                url: self.sortUrl,
                dataType: 'json',
                cache: false,
                crossDomain: true,
                data: {
                    data: data
                }
            }).done(function (data) {

                return true;

            }).fail(function (xhr, textStatus, errorThrown) {

                return false;

            });
        });

        /**
         * Pre-process the data returned from the server
         */
        this.table.off('xhr.dt').on('xhr.dt', function (e, settings, json, xhr) {

            if (json.status == 200) {

                try {
                    console.log(self.succcess + "() loading");
                    var {DataTableOnLoad} = require("./tableRows/" + self.succcess);

                    $.each(json.data, function (index, item) {
                        json.data[index] = new DataTableOnLoad(new FormatDataTable, item, self.data);
                    });

                } catch (e) {
                    if (e instanceof Error && e.code === "MODULE_NOT_FOUND") {
                        alert("Can't load datatable onload module : " + self.succcess + ".js");
                    } else {
                        console.error(e);
                        throw e;
                    }
                }

            } else {
                alert("dataTable.js " + json.error);
                return false;
            }
        });

        this.setChildRow();

    }

    /**
     *
     */
    hasChildRow() {
        if (typeof this.childRowFormat != 'undefined') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add event listener for opening and closing details
     */
    setChildRow() {

        var self = this;
        if (this.hasChildRow()) {
            this.table.on('click', 'tr:not(.shown) td:nth-child(1)', function () {

                var tr = $(this).closest('tr');
                var row = self.table.row(tr);
                var functionName = self.childRowFormat;
                // Open this row
                try {
                    if (functionName == 'orderPage') {
                        var adminOrderObject = new Order();
                        var content = adminOrderObject.createPackageRow(row.data());
                        if (typeof content != 'undefined') {
                            row.child(content[0]).show();
                            window.datePicker();
                            // adminOrderObject.loadShippingServicesInPackageRow(content[1]);
                        }
                    }
                } catch (e) {
                    console.log(e)
                    // We use try-catch block to hide errors
                }
                tr.addClass('shown');
            });

            this.table.on('click', 'tr.shown td:nth-child(1)', function () {
                try {
                    var tr = $(this).closest('tr').removeClass('shown');
                    var row = self.table.row(tr);
                    row.child().hide();
                } catch (e) {
                    // We use try-catch block to hide errors
                }
            });
        }
    }

    /**
     * @param array row
     */
    addRow(row) {
        var rowNode = this.table.row.add(row).draw().node();
        $(rowNode)
            .addClass('transitionRow');
        setTimeout(function () {
            $(rowNode)
                .addClass('addAnimatedRow');

            // Remove all animation classes after completed
            setTimeout(function () {
                $("tr")
                    .removeClass('transitionRow addAnimatedRow');
            }, 5500);
        }, 500);
    }

    /**
     * @param obj jquery selector
     */
    removeRow(item) {
        this.table
            .row(item.parents('tr'))
            .remove()
            .draw();
    }

    /**
     * Create reload button
     */
    reloadButton() {
        var self = this;
        this.class.append('<button type="button" class="reload-datatable d-none">reload</button>');

        $(".reload-datatable").on('click', function () {
            self.refresh();
        })
    }

    /**
     * Aşağıdaki kod hata veriyor.
     * this.table.ajax.reload( null, false );
     *
     */
    refresh() {
        var tableData = this.table.page.info();
        this.table.page(tableData.page).draw(false);
    }

    /**
     * Insert value to datatable-data attribute
     *
     * @param {string} key
     * @param {mixed} val
     */
    updateBase64Json(key, val) {
        try {
            // Get current data and convert to json
            var data = atob(this.class.data("data"));
            var jsonData = JSON.parse(data);

            // Add new data
            jsonData[key] = val;
            var jsonString = JSON.stringify(jsonData);

            // Set data again
            var base64Json = btoa(jsonString);
            this.class.attr("data-data", base64Json);
        } catch (err) {
            alert(err.message);
        }
    }

}
