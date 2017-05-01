<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Administration</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Menu</span>
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
<br>
<div class="row">
    <div class="col-xs-12">
        <div id="detailsPlaceholder" style="display:none">
            <table id="jqGridDetails"></table>
            <div id="jqGridDetailsPager"></div>
        </div>
    </div>
</div>

<script>

    jQuery(function($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        // $(window).on("resize", function () {
        //     responsive_jqgrid(grid_selector, pager_selector);
        //     responsive_jqgrid('#jqGridDetails', '#jqGridDetailsPager');
        // });

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID."administration.menus_controller/crud"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [
               {label: 'ID', name: 'menu_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
               {
                    label: 'Menu Link',
                    name: 'menu_link',
                    width: 5,
                    editable: true,
                    hidden: true,
                    editrules: {edithidden: false},
                    editoptions: {defaultValue: '#'}
                },
                {
                    label: 'Nama Menu',
                    name: 'menu_name',
                    width: 200,
                    align: "left",
                    editable: true,
                    editrules: {required: true},
                    editoptions: {
                        size: 30,
                        maxlength:128
                    },
                },
                {
                    label: 'Menu Parent',
                    name: 'menu_parent',
                    width: 140,
                    align: "left",
                    editable: true,
                    hidden: true,
                    editrules: {required: true, edithidden: true},
                    editoptions: {defaultValue: '0', readonly: 'readonly'}
                },
                {
                    label: 'Icon',
                    name: 'menu_icon',
                    width: 145,
                    align: "left",
                    editable: true,
                    editoptions: {
                        size: 30,
                        maxlength:128
                    },
                },
                {
                    label: 'Deskripsi',
                    name: 'menu_desc',
                    width: 165,
                    align: "left",
                    editable: true
                },
                {
                    label: 'No. Urut',
                    name: 'listing_no',
                    width: 90,
                    editable: true,
                    editrules: {required: true},
                    editoptions: {
                        size: 10,
                        maxlength:2,
                        defaultValue: '0',
                        dataInit: function(element) {
                           $(element).keypress(function(e){
                                 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                    return false;
                                 }
                            });                        
                        }
                    },
                }
            ],
            height: '100%',
            autowidth: true,
            viewrecords: true,
            rowNum: 5,
            rowList: [5, 10, 20],
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: true,
            multiboxonly: true,
            onSelectRow: function (rowid) {
                /*do something when selected*/
                var celValue = $('#grid-table').jqGrid('getCell', rowid, 'menu_name');
                var grid_id = $("#jqGridDetails");
                if (rowid != null) {
                    grid_id.jqGrid('setGridParam', {
                        url: "<?php echo WS_JQGRID."administration.menus_controller/crud_detail"; ?>",
                        datatype: 'json',
                        postData: {parent_id: rowid},
                        userData: {row: rowid}
                    });
                    grid_id.jqGrid('setCaption', 'Menu Child :: ' + celValue);
                    $("#detailsPlaceholder").show();
                    $("#jqGridDetails").trigger("reloadGrid");
                }

                responsive_jqgrid('#jqGridDetails', '#jqGridDetailsPager');
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
            //memanggil controller jqgrid yang ada di controller crud
            editurl: '<?php echo WS_JQGRID."administration.menus_controller/crud"; ?>',
            caption: "Menu Parent"

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
                    jQuery("#detailsPlaceholder").hide();
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
                serializeEditData: serializeJSON,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    //form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                    style_edit_form(form);
                    //form.css({"width": 0.30*screen.width+"px"});

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
                    //form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                      //  .wrapInner('<div class="widget-header" />')
                    style_edit_form(form);
                    // form.css({"width": 0.30*screen.width+"px"});
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
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                    // if (form.data('styled')) return false;

                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                    style_delete_form(form);

                    // form.data('styled', true);
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
                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
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
                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                }
            }
        );

        //JqGrid Detail
       jQuery("#jqGridDetails").jqGrid({
            mtype: "POST",
            datatype: "json",
            colModel: [
                {label: 'Menu ID', name: 'menu_id', key: true, autowidth: true, editable: true, hidden: true},
                {label: 'Menu Parent', name: 'menu_parent', width: 5, sorttype: 'number', editable: true, hidden: true},
                {
                    label: 'Nama Menu',
                    name: 'menu_name',
                    editable: true,
                    editrules: {required: true},
                    editoptions: {
                        size: 30,
                        maxlength:128
                    },
                },
                {label: 'Controller', name: 'menu_link', editable: false, hidden: true},
                {
                    label: 'View',
                    name: 'file_name',
                    editable: true,
                    editrules: {required: true},
                    editoptions: {
                        size: 30,
                        maxlength:128
                    },
                },
                {
                    label: 'No. Urut',
                    name: 'listing_no',
                    width: 90,
                    editable: true,
                    editrules: {required: true},
                    editoptions: {
                        size: 10,
                        maxlength:2,
                        defaultValue: '0',
                        dataInit: function(element) {
                           $(element).keypress(function(e){
                                 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                    return false;
                                 }
                            });                        
                        }
                    }
                }
            ],
            // autowidth: true,
            width: 500,
            height: '100%',
            rowNum: 5,
            page: 1,
            shrinkToFit: true,
            rownumbers: true,
            rownumWidth: 35, // the width of the row numbers columns
            viewrecords: true,
            sortorder:'', // default sorting ID
            caption: 'Menu Child',
            pager: "#jqGridDetailsPager",
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
            editurl: '<?php echo WS_JQGRID."administration.menus_controller/crud_detail";?>'
        });

       //navButtons Grid Detail
       jQuery('#jqGridDetails').jqGrid('navGrid', '#jqGridDetailsPager',
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
                serializeEditData: serializeJSON,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                    style_edit_form(form);
                    // form.css({"width": 0.30*screen.width+"px"});

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
                editData: {
                    menu_parent: function() {
                        var selRowId =  $("#grid-table").jqGrid ('getGridParam', 'selrow');
                        var menu_parent = $("#grid-table").jqGrid('getCell', selRowId, 'menu_id');

                        return menu_parent;
                    }
                },
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
                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                        // .wrapInner('<div class="widget-header" />')
                    style_edit_form(form);
                    // form.css({"width": 0.30*screen.width+"px"});
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
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                    // if (form.data('styled')) return false;

                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                    style_delete_form(form);

                    // form.data('styled', true);
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
                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
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
                    // form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                }
            }
        );

    });

    function serializeJSON(postdata) {
        var items;
        if(postdata.oper != 'del') {
            items = JSON.stringify(postdata, function(key,value){
                if (typeof value === 'function') {
                    return value();
                } else {
                  return value;
                }
            });
        }else {
            items = postdata.id;
        }

        var jsondata = {items:items, oper:postdata.oper, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'};
        return jsondata;
    }


    function style_edit_form(form) {

        //update buttons classes
        var buttons = form.next().find('.EditButton .fm-button');
        buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
        buttons.eq(0).addClass('btn-primary');
        buttons.eq(1).addClass('btn-danger');


    }

    function style_delete_form(form) {
        var buttons = form.next().find('.EditButton .fm-button');
        buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
        buttons.eq(0).addClass('btn-danger');
        buttons.eq(1).addClass('btn-default');
    }

    function style_search_filters(form) {
        form.find('.delete-rule').val('X');
        form.find('.add-rule').addClass('btn btn-xs btn-primary');
        form.find('.add-group').addClass('btn btn-xs btn-success');
        form.find('.delete-group').addClass('btn btn-xs btn-danger');
    }

    function style_search_form(form) {
        var dialog = form.closest('.ui-jqdialog');
        var buttons = dialog.find('.EditTable')
        buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'fa fa-retweet');
        buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'fa fa-comment-o');
        buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-success').find('.ui-icon').attr('class', 'fa fa-search');
    }

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }

</script>