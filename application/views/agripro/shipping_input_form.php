<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Tracking</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Input Trucking</span>
        </li>
    </ul>
</div>

<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Input Trucking</div>
            </div>

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form method="post" action="" class="form-horizontal" id="form-shipping" enctype="multipart/form-data">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="shipping_date">Trucking Date</label>
                            <div class="col-md-2">
                                <input type="text" name="shipping_date" id="shipping_date" class="form-control required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="shipping_driver_name">Driver Name:</label>
                            <div class="col-md-3">
                                <input type="text" name="shipping_driver_name" id="shipping_driver_name" class="form-control required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="shipping_police_no">Police No:</label>
                            <div class="col-md-3">
                                <input type="text" name="shipping_police_no" id="shipping_police_no" class="form-control required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="shipping_license">File License:</label>
                            <div class="col-md-3">
                                <input type="file" name="shipping_license" id="shipping_license" class="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="shipping_notes">Notes:</label>
                            <div class="col-md-6">
                                <textarea name="shipping_notes" id="shipping_notes" class="form-control"></textarea>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Trucking Details</h3>
                                <i class="green">Note : Please do check packing box(s) that you want to be put in truck </i>
                                <table id="grid-table"></table>
                                <div id="grid-pager"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" name="submit" class="btn btn-success"> <i class="fa fa-save"></i> Save Data </button>
                                <button type="button" name="back" id="btn-back" class="btn btn-danger"><i class="fa fa-arrow-left"></i>Back</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $("#shipping_date").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: 'bottom',
            todayHighlight: true
        });

        $("#btn-back").on('click', function(e) {
            loadContentWithParams('agripro.shipping',{});
        });

        $("#form-shipping").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var packingIDs = jQuery("#grid-table").jqGrid ('getGridParam', 'selarrrow');
            if(packingIDs.length == 0) {
                swal('Info','Please choose packing box(s) ','info');
                return false;
            }

            var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "packing_id").val(packingIDs.join(","));
            $('#form-shipping').append($(input));

            var formData = new FormData($(this)[0]);

            if($("#form-shipping").valid() == true){
                var url = '<?php echo WS_JQGRID."agripro.shipping_controller/createForm"; ?>';
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType : 'json',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if(response.success != true){
                            swal('Warning',response.message,'warning');
                        }else{
                            loadContentWithParams('agripro.shipping',{});
                        }

                    }
                });
            }
        });
    });
</script>

<script>
jQuery(function($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID."agripro.shipping_controller/readInputPacking"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [
                {label: 'ID', name: 'packing_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                {label: 'ID Product', name: 'product_id', width: 120, align: "left", editable: false, hidden:true},
                {label: 'Product Code', name: 'product_code', width: 120, align: "left", editable: false, hidden:true},
                {label: 'Product Name', name: 'product_name', width: 150, align: "left", editable: false},
                {label: 'Serial', name: 'packing_serial', width: 200, align: "left", editable: false},
                {label: 'Batch Number', name: 'packing_batch_number', width: 100, align: "left", editable: false},
                {label: 'Weight(Kg)', name: 'packing_weight', width: 80, align: "right", editable: false},
                {label: 'Packing Date', name: 'packing_date', width: 120, align: "center", editable: false},
                {label: 'Packed By', name: 'packed_by', width: 120, align: "left", editable: false, hidden:false},
                {label: 'Print Label',name: '',width: 120, align: "center",editable: false,
                    formatter:function(cellvalue, options, rowObject) {
                        var val = rowObject['packing_id'];
                        var url = "<?php echo base_url().'label/packing_label?id='?>"+val;
                        return '<a class="btn btn-danger btn-xs" href="#" onclick="PopupCenter(\''+url+'\',\'Label Packing\',500,500);"><i class="fa fa-print"></i>Label</a>';

                    }
                },
            ],
            height: '100%',
            autowidth: true,
            viewrecords: true,
            rowNum: 1000,
            rowList: [1000],
            multiselect:true,
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: true,
            multiboxonly: true,
            onSelectRow: function (rowid) {
                /*do something when selected*/
                var celValue = $('#grid-table').jqGrid('getCell', rowid, 'packing_id');
                var celCode = $('#grid-table').jqGrid('getCell', rowid, 'packing_batch_number');

                var grid_detail = jQuery("#grid-table-detail");
                if (rowid != null) {
                    grid_detail.jqGrid('setGridParam', {
                        url: '<?php echo WS_JQGRID."agripro.packing_detail_controller/crud"; ?>',
                        postData: {packing_id: rowid}
                    });
                    var strCaption = 'Contains :: ' + celCode;
                    grid_detail.jqGrid('setCaption', strCaption);
                    $("#grid-table-detail").trigger("reloadGrid");
                    $("#detail_placeholder").show();

                    responsive_jqgrid('#grid-table-detail', '#grid-pager-detail');
                }
            },
            sortorder:'',
            pager: '#grid-pager',
            jsonReader: {
                root: 'rows',
                id: 'id',
                repeatitems: false
            },
            loadComplete: function (response) {
                if(response.success == false) {
                    swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                }
            },
            caption: "Packing"
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
                view: false,
                viewicon: 'fa fa-search-plus grey bigger-120'
            },

            {
                // options for the Edit Dialog
                closeAfterEdit: true,
                closeOnEscape:true,
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
                    $("#pkg_serial_number").prop("readonly", true);
                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }
                    return [true,"",response.responseText];
                }
            },
            {
                //new record form
                closeAfterAdd: false,
                clearAfterAdd : true,
                closeOnEscape:true,
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

                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }

                    $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                    var tinfoel = $(".tinfo").show();
                    tinfoel.delay(3000).fadeOut();


                    return [true,"",response.responseText];
                }
            },
            {
                //delete record form
                serializeDelData: serializeJSON,
                recreateForm: true,
                width:400,
                caption:'Delete data packing',
                msg: "Once You delete selected record, it cannot be restored.<br>Are You sure to delete selected record?",
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                    style_delete_form(form);
                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                onClick: function (e) {
                    //alert(1);
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }
                    return [true,"",response.responseText];
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
        $(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );
    }
</script>