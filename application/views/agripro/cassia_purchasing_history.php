<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url(); ?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Raw Material Purchasing</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Purchasing History</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
<div class="space-4"></div>
<div class="form-inline">
    <div class="input-group">
        <input type="text" class="form-control form-control-inline date-picker" id="inStart" placeholder="Start Date">
    </div>

    <div class="input-group">
        <input type="text" class="form-control form-control-inline date-picker" id="inEnd" placeholder="End Date">
    </div>

    <button type="button" class="btn btn-success" id="search">Search</button>
    <button type="button" class="btn btn-success" id="download_sm" style="float: right" onclick="toExcel();">Download</button>
</div>
&nbsp;
<div class="row">
    <div class="col-md-12">
        <table id="grid-table"></table>
        <div id="grid-pager"></div>
    </div>
</div>
<div class="space-4"></div>
<div class="row" id="detail_placeholder" style="display:none;">
    <div class="col-xs-12">
        <table id="grid-table-detail"></table>
        <div id="grid-pager-detail"></div>
    </div>
</div>

<script>
    $("#search").click(function () {
        var grid_pot = jQuery("#grid-table");
        var inStart = $("#inStart").val();
        var inEnd = $("#inEnd").val();

        var postdata = grid_pot.jqGrid('getGridParam', 'postData');
        $.extend(postdata, {inStart: inStart, inEnd: inEnd});
        grid_pot.trigger("reloadGrid", [{page: 1}]);
    });
</script>
<script>

    function toExcel() {
        var url = '<?php echo WS_JQGRID . "agripro.stock_material_controller/exportExcel"; ?>';
        var c = confirm('Export to Excel ?');
        if(c == true){
            $.ajax({
                url: url,
                data: {},
                type: 'POST',
                success: function (response) {
                    var output = $.parseJSON(response);
                    if (output.redirect !== undefined && output.redirect) {
                        window.location.href = output.redirect_url;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    // alert(errorThrown);
                    $("#ajaxContent").html(errorThrown);
                }
            });

        }
    }

    $('.date-picker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        orientation: 'bottom',
        todayHighlight: true
    });

    jQuery(function ($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID . "agripro.cassia_purchasing_controller/crud"; ?>',
            datatype: "json",
            mtype: "POST",
            postData: {history: 1},
            colModel: [
                {label: 'ID', name: 'purchasing_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                {
                    label: 'Transaction Code', name: 'trx_code', width: 250, align: "left", editable: true,
                    editoptions: {
                        size: 30,
                        maxlength: 32,
                        placeholder: 'Generate By Sistem'
                    },
                    editrules: {required: false}
                },
                {
                    label: 'Transaction Date', name: 'purchasing_date', width: 150, editable: true,
                    align:"center",
                    edittype: "text",
                    editrules: {required: true},
                    editoptions: {
                        dataInit: function (element) {
                            $(element).datepicker({
                                autoclose: true,
                                format: 'yyyy-mm-dd',
                                orientation: 'bottom',
                                todayHighlight: true
                            });
                        },
                        size: 25
                    }
                },
                {label: 'Farmer Code', name: 'fm_code', width: 150, align: "left", editable: false},
                {label: 'Farmer Name', name: 'fm_name', width: 170, align: "left", editable: false},
                {
                    label: 'Farmer',
                    name: 'fm_id',
                    width: 150,
                    sortable: true,
                    editable: true,
                    hidden: true,
                    editrules: {edithidden: true, number: true, required: true},
                    edittype: 'custom',
                    editoptions: {
                        "custom_element": function (value, options) {
                            var elm = $('<span></span>');

                            // give the editor time to initialize
                            setTimeout(function () {
                                elm.append('<input id="form_fm_id" type="text"  style="display:none;" onchange="clearLovPlantation()">' +
                                    '<input size="30" id="form_fm_code" disabled type="text" class="FormElement jqgrid-required" placeholder="Farmer">' +
                                    '<button class="btn btn-success" type="button" onclick="showLovFarmer(\'form_fm_id\',\'form_fm_code\')">' +
                                    '   <span class="fa fa-search icon-on-right bigger-110"></span>' +
                                    '</button>');
                                $("#form_fm_id").val(value);
                                elm.parent().removeClass('jqgrid-required');
                            }, 100);

                            return elm;
                        },
                        "custom_value": function (element, oper, gridval) {

                            if (oper === 'get') {
                                return $("#form_fm_id").val();
                            } else if (oper === 'set') {
                                $("#form_fm_id").val(gridval);
                                var gridId = this.id;
                                // give the editor time to set display
                                setTimeout(function () {
                                    var selectedRowId = $("#" + gridId).jqGrid('getGridParam', 'selrow');
                                    if (selectedRowId != null) {
                                        var code_display = $("#" + gridId).jqGrid('getCell', selectedRowId, 'fm_code');
                                        $("#form_fm_code").val(code_display);
                                    }
                                }, 100);
                            }
                        }, size: 25
                    }
                },
                {label: 'Plantation Code', name: 'plt_code', width: 200, align: "left", editable: false, hidden: true},
                {
                    label: 'Plantation',
                    name: 'plt_id',
                    width: 200,
                    sortable: true,
                    editable: true,
                    hidden: true,
                    editrules: {edithidden: true, number: true, required: false},
                    edittype: 'custom',
                    editoptions: {
                        "custom_element": function (value, options) {
                            var elm = $('<span></span>');

                            // give the editor time to initialize
                            setTimeout(function () {
                                elm.append('<input id="form_plt_id" type="text"  style="display:none;">' +
                                    '<input size="30" id="form_plt_code" disabled type="text" class="FormElement" placeholder="Choose Plantation">' +
                                    '<button class="btn btn-success" type="button" onclick="showLovPlantation(\'form_plt_id\',\'form_plt_code\')">' +
                                    '   <span class="fa fa-search icon-on-right bigger-110"></span>' +
                                    '</button>');
                                $("#form_plt_id").val(value);
                                elm.parent().removeClass('jqgrid-required');
                            }, 100);

                            return elm;
                        },
                        "custom_value": function (element, oper, gridval) {

                            if (oper === 'get') {
                                return $("#form_plt_id").val();
                            } else if (oper === 'set') {
                                $("#form_plt_id").val(gridval);
                                var gridId = this.id;
                                // give the editor time to set display
                                setTimeout(function () {
                                    var selectedRowId = $("#" + gridId).jqGrid('getGridParam', 'selrow');
                                    if (selectedRowId != null) {
                                        var code_display = $("#" + gridId).jqGrid('getCell', selectedRowId, 'plt_code');
                                        $("#form_plt_code").val(code_display);
                                    }
                                }, 100);
                            }
                        }
                    }
                },

                {
                    label: 'Total Weight (KGs)', name: 'purchasing_weight_init', width: 150, align: "right", editable: true,
                    editoptions: {
                        size: 10,
                        maxlength: 4
                    },
                    editrules: {required: true},
                    formatter: 'number'
                },

                {
                    label: 'Price (RP) / Kgs ', name: 'rate', width: 170, align: "right", editable: true,
                    editoptions: {
                        size: 25
                    },
                    editrules: {required: true},
                    formatter: 'number'
                },
                {
                    label: 'Total Price ', name: 'total_price', width: 170, align: "right", editable: false,formatter: 'number'
                },
                {
                    label: 'Batch Total', name: 'batch_total', width: 120, align: "right", editable: true,
                    editoptions: {
                        size: 5,
                        maxlength: 3
                    },
                    editrules: {required: false}
                },
                {
                    label: 'Payment Type',
                    name: 'payment_type',
                    width: 150,
                    align: "left",
                    editable: true,
                    edittype: 'select',
                    hidden: false,
                    editrules: {edithidden: true, required: false},
                    editoptions: {
                        value: "Tunai:Tunai;DP:DP",
                        dataInit: function (elem) {
                            $(elem).width(150);  // set the width which you need
                        }
                    }
                },
                {
                    label: 'Harvest Date', name: 'harvest_date', width: 120, editable: true,
                    edittype: "text",
                    editrules: {required: false},
                    editoptions: {
                        // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                        // use it to place a third party control to customize the toolbar
                        dataInit: function (element) {
                            $(element).datepicker({
                                autoclose: true,
                                format: 'yyyy-mm-dd',
                                orientation: 'up',
                                todayHighlight: true
                            });
                        },
                        size: 25
                    }
                },
                {
                    label: 'PO Number', name: 'po_number', width: 170, align: "left", editable: true,
                    editoptions: {
                        size: 25,
                        maxlength: 32
                    },
                    editrules: {required: false}
                }
            ],
            height: '100%',
            width: '100%',
            autowidth: true,
            viewrecords: true,
            rowNum: 10,
            rowList: [10, 20, 50],
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: false,
            multiboxonly: true,
            onSelectRow: function (rowid) {

            },
            sortorder: '',
            pager: '#grid-pager',
            jsonReader: {
                root: 'rows',
                id: 'id',
                repeatitems: false
            },
            loadComplete: function (response) {
                if (response.success == false) {
                    swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                }
            },
            caption: "Raw Material Purchesing"

        });


        jQuery('#grid-table').jqGrid('navGrid', '#grid-pager',
            {   //navbar options
                edit: false,
                editicon: 'fa fa-pencil blue bigger-120',
                add: false,
                addicon: 'fa fa-plus-circle purple bigger-120',
                del: false,
                delicon: 'fa fa-trash-o red bigger-120',
                search: true,
                searchicon: 'fa fa-search orange bigger-120',
                refresh: true,
                afterRefresh: function () {
                    // some code here
                    jQuery("#detail_placeholder").hide();
                },

                refreshicon: 'fa fa-refresh green bigger-120',
                view: true,
                viewicon: 'fa fa-search-plus grey bigger-120',
                excel:true
            },

            {
                // options for the Edit Dialog
                closeAfterEdit: true,
                closeOnEscape: true,
                recreateForm: true,
                viewPagerButtons: false,
                serializeEditData: serializeJSON,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    style_edit_form(form);
                    $("#trx_code").prop("readonly", true);
                    clearLovFarmer();
                    clearLovPlantation();
                },
                afterShowForm: function (form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit: function (response, postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if (response.success == false) {
                        return [false, response.message, response.responseText];
                    }
                    return [true, "", response.responseText];
                }
            },
            {
                //new record form
                closeAfterAdd: true,
                clearAfterAdd: true,
                closeOnEscape: true,
                recreateForm: true,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                serializeEditData: serializeJSON,
                viewPagerButtons: false,
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    style_edit_form(form);
                    /*form.css({"height": 0.70 * screen.height + "px"});
                     form.css({"width": 0.60 * screen.width + "px"});*/

                    $("#trx_code").prop("readonly", true);
                    setTimeout(function () {
                        clearLovFarmer();
                        clearLovPlantation();
                    }, 100);
                },
                afterShowForm: function (form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit: function (response, postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if (response.success == false) {
                        return [false, response.message, response.responseText];
                    }

                    $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                    var tinfoel = $(".tinfo").show();
                    tinfoel.delay(3000).fadeOut();


                    return [true, "", response.responseText];
                }
            },
            {
                //delete record form
                serializeDelData: serializeJSON,
                recreateForm: true,
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                    style_delete_form(form);

                },
                afterShowForm: function (form) {
                    form.closest('.ui-jqdialog').center();
                },
                onClick: function (e) {
                    //alert(1);
                },
                afterSubmit: function (response, postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if (response.success == false) {
                        return [false, response.message, response.responseText];
                    }
                    return [true, "", response.responseText];
                }
            },
            {
                //search form
                closeAfterSearch: false,
                recreateForm: true,
                afterShowSearch: function (e) {
                    var form = $(e[0]);
                    style_search_form(form);
                    form.closest('.ui-jqdialog').center();
                },
                afterRedraw: function () {
                    style_search_filters($(this));
                }
            },
            {
                //view record form
                recreateForm: true,
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                }
            }
        );


    });
    jQuery("#grid-table").jqGrid('navButtonAdd','#grid-pager',{
        caption:"",
        onClickButton : function () {
            jQuery("#grid-table").excelExport();
        }
    });

    function responsive_jqgrid(grid_selector, pager_selector) {
        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid('setGridWidth', $(".page-content").width());
        $(pager_selector).jqGrid('setGridWidth', parent_column.width());
    }

</script>