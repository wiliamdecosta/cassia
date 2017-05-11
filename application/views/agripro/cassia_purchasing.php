<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url(); ?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Purchasing</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Purchasing</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
<div class="space-4"></div>
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

<?php $this->load->view('lov/lov_farmer.php'); ?>
<?php $this->load->view('lov/lov_plantation.php'); ?>
<script>function setFocusOnLoad() {}</script>
<script>

    function showLovFarmer(id, code) {
        modal_lov_farmer_show(id, code);
    }

    function clearLovFarmer() {
        $('#form_fm_id').val('');
        $('#form_fm_code').val('');
    }


    function showLovRawMaterial(id, code) {
        modal_lov_raw_material_show(id, code);
    }

    function clearLovRawMaterial() {
        $('#form_rm_id').val('');
        $('#form_rm_code').val('');
    }

    function showLovPlantation(id, code) {

        selRowId = $('#grid-table').jqGrid('getGridParam', 'selrow');
        // fm_id = $('#grid-table').jqGrid('getCell', selRowId, 'fm_id');
        if ($('#form_fm_id').val() == "") {
            swal({title: 'Attention', text: 'Please choose farmer', html: true, type: "info"});
            return;
        }
        modal_lov_plantation_show(id, code, $('#form_fm_id').val());
    }

    function clearLovPlantation() {
        $('#form_plt_id').val('');
        $('#form_plt_code').val('');
    }

    jQuery(function ($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID . "agripro.purchasing_controller/crud"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [
                {label: 'ID', name: 'purchasing_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                {
                    label: 'Transaction Code', name: 'trx_code', width: 250, align: "left", editable: true,hidden: false,
                    editoptions: {
                        size: 30,
                        maxlength: 32,
                        placeholder: 'Generate By Sistem'
                    },
                    editrules: {required: false}
                },
                {
                    label: 'Transaction Date', name: 'purchasing_date', width: 150, editable: true, align:"center",
                    edittype: "text",
                    editrules: {required: true},
                    editoptions: {
                        dataInit: function (element) {
                            $(element).datepicker({
                                autoclose: true,
                                format: 'dd-M-yyyy',
                                orientation: 'bottom',
                                todayHighlight: true
                            });
                        },
                        size: 25
                    }
                },
                {
                    label: 'Product', name: 'product_code', width: 150, align: "left", editable: false
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
                    label: 'Product',
                    name: 'product_id',
                    width: 150,
                    align: "left",
                    editable: true,
                    edittype: 'select',
                    hidden: true,
                    editrules: {edithidden: true, required: true},
                    editoptions: {
                        dataUrl: '<?php echo site_url('raw_material_controller/listRawMaterial');?>',
                        dataInit: function (elem) {
                            $(elem).width(150);  // set the width which you need
                        }
                    }
                },

                {
                    label: 'Total Weight (KGs)', name: 'purchasing_weight', width: 150, align: "right", editable: true,
                    editoptions: {
                        size: 10,
                        maxlength: 7
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
                    label: 'Total Price ', name: 'total_price', width: 170, align: "right", editable: false,
                    formatter: 'number'
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
                                format: 'dd-M-yyyy',
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
                },
                {
                    label: 'Created Date', name: 'created_date', width: 120, align: "center", editable: false,
                    editrules: {required: false}
                },
                {
                    label: 'Created By', name: 'created_by', width: 120, align: "center", editable: false,
                    editrules: {required: false}
                },{
                    label: 'Updated Date', name: 'updated_date', width: 120, align: "center", editable: false,
                    editrules: {required: false}
                },{
                    label: 'Updated By', name: 'updated_by', width: 120, align: "center", editable: false,
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
            //memanggil controller jqgrid yang ada di controller crud
            editurl: '<?php echo WS_JQGRID . "agripro.purchasing_controller/crud"; ?>',
            caption: "Raw Material Purchasing"

        });

        jQuery('#grid-table').jqGrid('navGrid', '#grid-pager',
            {   //navbar options
                edit: true,
                editicon: 'fa fa-pencil blue bigger-120',
                add: true,
                addicon: 'fa fa-plus-circle purple bigger-120',
                del: true,
                delicon: 'fa fa-trash-o red bigger-120',
                search: true,
                searchicon: 'fa fa-search orange bigger-120',
                refresh: true,
                afterRefresh: function () {
                    // some code here
                    jQuery("#detail_placeholder").hide();
                },

                refreshicon: 'fa fa-refresh green bigger-120',
                view: false,
                viewicon: 'fa fa-search-plus grey bigger-120'
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
                    $("#purchasing_id").focus();
                    setTimeout(function () {
                        clearLovFarmer();
                        clearLovPlantation();
                    }, 100);
                },
                afterShowForm: function (form) {
                    $('#purchasing_id').focus();
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

    function responsive_jqgrid(grid_selector, pager_selector) {
        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid('setGridWidth', $(".page-content").width());
        $(pager_selector).jqGrid('setGridWidth', parent_column.width());
    }

</script>