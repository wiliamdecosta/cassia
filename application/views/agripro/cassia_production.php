<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url(); ?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Production</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Cassia Production</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
<div class="space-4"></div>

 <div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gears font-purple-soft"></i>
            <span class="caption-subject font-purple-soft bold uppercase">Cassia Production</span>
        </div>
    </div>
    <div class="portlet-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab"> Data </a>
            </li>
            <li>
                <a href="#tab_1_2" data-toggle="tab"> Production Form</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">
               
                <!-- MASTER GRID  -->
                <div class="row">
               <div class="space-4"></div>
                             
                    <div class="col-md-12">
                        <table id="grid-table"></table>
                        <div id="grid-pager"></div>
                    </div>
                </div>
                <!-- END MASTER GRID  -->
                
                <div class="space-4"></div>

                <!-- DETAIL GRID  -->
                <div class="row" id="detail_placeholder" style="display:none">
                    <div class="col-xs-12">
                      <div class="row">
                                    
                                  <div class="col-md-6 col-sm-6">
                                       <div role="form" class="form-horizontal" id="mappingform">
                                           <div class="form-group ">
                                                    <label class="control-label col-md-3 ">
                                                    </label>
                                                    <div class="col-md-8">
                                                         <button  class="btn btn-primary"  id="material" name="material" onclick="showLovMaterial()"> <i class="icon icon-grid"></i> Choose Material</button>
                                                   </div>
                                                </div>

                                             <div class="table-scrollable">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> Name </th>
                                                    <th> Qty </th>
                                                </tr>
                                            </thead>
                                            <tbody id="material_prod" >
                                               
                                            </tbody>
                                        </table>
                                    </div>

                                         <!--  </form>  -->
                                  </div>
                                  </div>

                                  <div class="col-md-6 col-sm-6">
                                  <div role="form" class="form-horizontal" id="mappingform">
                                          <div class="form-group ">
                                                    <label class="control-label col-md-3 ">
                                                    </label>
                                                    <div class="col-md-8">
                                                         <button  class="btn btn-primary"  id="showProduct" name="showProduct" > <i class="icon icon-layers"></i> Show Product</button>
                                                   </div>
                                                </div>
                                      <div class="table-scrollable">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> Product </th>
                                                    <th> Qty </th>
                                                </tr>
                                            </thead>
                                            <tbody id="rowProduct">

                                            </tbody>
                                        </table>
                                    </div>
                                    </div>

                                          
                                  </div>
                                  </div>  
                    </div>
                </div>
                <!-- END DETAIL GRID  -->

            </div>
            <div class="tab-pane fade" id="tab_1_2">

            <div class="row">
             
                <div class="col-md-12">
                <h4>Production Form</h4>
                    <hr>
                <div class="col-md-4">
                    <div role="form" class="form-horizontal" id="mappingform">
                        <div class="form-group">
                            <label class="control-label col-md-3">Code
                            </label>
                            <div class="col-md-8">
                                 <input type="text" class="form-control "  id="account_num_lov" name="" readonly placeholder="Generate By System" />
                           </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date
                            </label>
                            <div class="col-md-8">
                                 <input type="text" class="form-control "  id="account_num_lov" name="" value="<?php echo date('d-M-Y')?>" readonly />
                           </div>
                        </div>
                    </div>
                </div>

                 <div class="col-md-8">
                    <div style="margin: 20px 0 10px 30px">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                                <span class="label label-sm label-success"> Material Weight </span>
                                                <h3 id="matweight">0 Kg</h3>
                                                <input type="hidden" id="inmatweight" >
                                                <input type="hidden" id="totmatweight" >
                                                <input type="hidden" id="parent_id_temp" >
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                                <span class="label label-sm label-info"> Product Weight  </span>
                                                <h3 id="proweight">0 Kg</h3>
                                                <input type="hidden" id="inproweight" >
                                                <input type="hidden" id="totproweight" >
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                                <span class="label label-sm label-danger"> Unused Material </span>
                                                <h3 id="unusweight">0 Kg</h3>
                                                <input type="hidden" id="inunusweight" >
                                                <input type="hidden" id="totunusweight" >
                                            </div>
                                        </div>
                                    </div>


                 </div>
                 </div>
            </div>

            <div class="row" style="height: 500px">


                <div class="col-md-12">
                <h4>Material & Product</h4>
                <hr>
                    <div class="form-horizontal" role="form">
                        
                         <div class="row">
                                    
                                  <div class="col-md-6 col-sm-6">
                                       <div role="form" class="form-horizontal" id="mappingform">
                                           <div class="form-group ">
                                                    <label class="control-label col-md-3 ">
                                                    </label>
                                                    <div class="col-md-8">
                                                         <button  class="btn btn-primary"  id="material" name="material" onclick="showLovMaterial()"> <i class="icon icon-grid"></i> Choose Material</button>
                                                   </div>
                                                </div>

                                             <div class="table-scrollable">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> Name </th>
                                                    <th> Qty </th>
                                                </tr>
                                            </thead>
                                            <tbody id="material_prod" >
                                               
                                            </tbody>
                                        </table>
                                    </div>

                                         <!--  </form>  -->
                                  </div>
                                  </div>

                                  <div class="col-md-6 col-sm-6">
                                  <div role="form" class="form-horizontal" id="mappingform">
                                          <div class="form-group ">
                                                    <label class="control-label col-md-3 ">
                                                    </label>
                                                    <div class="col-md-8">
                                                         <button  class="btn btn-primary"  id="showProduct" name="showProduct" > <i class="icon icon-layers"></i> Show Product</button>
                                                   </div>
                                                </div>
                                      <div class="table-scrollable">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> Product </th>
                                                    <th> Qty </th>
                                                </tr>
                                            </thead>
                                            <tbody id="rowProduct">

                                            </tbody>
                                        </table>
                                    </div>

                                          
                                  </div>
                                  </div>
                                  </div>
                        </div>
                    </div>

            </div>
               <div class="row">
                <hr>
                     <div class="col-md-6 col-sm-6">

                     </div>

                      <div class="col-md-6 col-sm-6">

                            <div class="col-md-8">
                                 <button  class="btn btn-success" onclick="submitData()" id="submitform" name="submitform"> <i class="fa fa-arrow-right "></i>Submit</button>
                                 <button  class="btn btn-default"  onclick="resetData()"> <i class="fa fa-refresh"></i>Reset</button>
                           </div>
                      </div>

                </div>
            </div>

           
            
            <div class="tab-pane fade" id="tab_1_3">

            </div>
            <div class="tab-pane fade" id="tab_1_4">
            </div>
        </div>
        <div class="clearfix margin-bottom-20"> </div>
       
    </div>
</div>




<?php $this->load->view('lov/lov_material_production.php'); ?>

<script>function setFocusOnLoad() {}</script>
<script>

    $(document).ready(function(){

        /*$('#modal_lov_stock_material').on('hidden.bs.modal', function () {
            getProduct();
        });*/

         $('#showProduct').on('click', function () {
            getProduct();
            return false;
        });


        $('.inputProduct').on('keyup', function(){

            calculateAll();

        });

    });
    
    function nvl(a, b){
        if(a == null || a == '' || a == undefined){
            return b;
        }else{
            return a;
        }
    }

    function getTotal(a){
        if(a == 'mat'){
            return nvl($('#totmatweight').val(),0);
        }
        if(a == 'prod'){
            return nvl($('#totproweight').val(),0);
        }
        if(a == 'unused'){
            return nvl($('#totunusweight').val(),0);
        }
    }

    function showLovMaterial() {
        modal_lov_stock_material_show('', '','','');
    }

    function recalculate(){

    }

    function setWeightInfo(id, val){

        inid = 'in'+id;
        totid = 'tot'+id;
        val = val*1;
        $('#'+inid).val(val);
        $('#'+totid).val(val);

        $('#'+id).html(val+' Kg');

    }

    function addMaterial(id, no, purchasing_id, product_name, qty){

        if(no%2 != 0){
            aktif = 'active';
        }
        
        elem =  '<tr class="'+aktif+'" id="'+id+'">'+
                    '<td class="nomor" width="10%">'+ no + '</td>'+
                    '<td  width="40%">'+ product_name + '</td>'+
                    '<td  width="40%" class="qtyMaterial" idmaterial="'+purchasing_id+'" valmat="'+qty+'"> '+ qty +' </td>'+
                '</tr>';
        $('#material_prod').append(elem);

    }

    function removeMaterial(id){

        $('#'+id).remove();

        
    }

    function addRemoveMaterial(dat, act){

        data = atob(dat);
        data = JSON.parse(data);

        no = '<a href="javascript:;" title="Remove" onclick="addRemoveMaterial(\''+dat+'\',\'remove\')" class="btn btn-circle btn-xs red">'+
             ' <span class="glyphicon glyphicon-trash"> </span>'+
             '</a>'
        ;
        
        id = 'trMaterial_'+data.purchasing_id;

        if(act == 'add'){

            addMaterial(id, no, data.purchasing_id, data.product_code, data.purchasing_weight);
            $('#addRowMaterial'+data.purchasing_id).css('display','none');
            $('#removeRowMaterial'+data.purchasing_id).css('display','block');
            
            parent_id = data.product_id+',';
            $('#parent_id_temp').val($('#parent_id_temp').val()+parent_id);
            totMat = getTotal('mat');
            tot = totMat + data.purchasing_weight;
            setWeightInfo('matweight',tot);

        }else{
            // swal confirm if yes it will reset all data 
            removeMaterial(id);
            $('#removeRowMaterial'+data.purchasing_id).css('display','none');
            $('#addRowMaterial'+data.purchasing_id).css('display','block');
            
            parent_id = data.product_id+',';
            pr_id = $('#parent_id_temp').val();
            pr_id = pr_id.replace(parent_id,'');

            $('#parent_id_temp').val(pr_id);

            totMat = getTotal('mat');
            tot = totMat - data.purchasing_weight;
            setWeightInfo('matweight',tot);

        }

    }

    function reorderNo(){

    }

    function getNumber(id){
        //elem = $('#'+id);
        return $('#'+id).find(".nomor").text();
    }

    function addProduct(data){

        for (var i = 0; i < data.length; i++) {
            
            no = i+1;
            
            aktif = '';

            if(no%2 > 0){
                aktif = 'active';
            }
            
            readonly = '';
            
            if(data[i].product_code == 'LOST'){
                readonly = 'readonly';
            }
            
                elem =  '<tr class="'+aktif+'" id="'+id+'">'+
                        '<td class="nomor" width="10%">'+ no + '</td>'+
                        '<td  width="40%">'+ data[i].product_code + '</td>'+    
                        '<td  width="40%"> '+
                        '<input class="form-control inputProduct" value="0" idproduct="'+data[i].product_id+'" onkeyup="calculateAll(this)" id="inputProduct'+data[i].product_code+'" type="number" '+ readonly +' class="form-control " > </td>'+
                    '</tr>';
            
            $('#rowProduct').append(elem);
        }

      
    }

    function getProduct(){

        parent_id = $('#parent_id_temp').val();
        parent_id = parent_id.slice(0,-1);

        if(parent_id != '' ){
            $.ajax({
                url: "<?php echo WS_JQGRID . 'agripro.cassia_production_controller/getProduct'; ?>",
                type: "POST",
                dataType: 'json',
                data: {parent_id:parent_id},
                success: function (data) {
                  $('#rowProduct').html('');
                  addProduct(data.item);
                },
                error: function (xhr, status, error) {
                    swal({title: "Error!", text: xhr.responseText, html: true, type: "error"});
                    return false;
                }
            });
        
        }else{
            $('#rowProduct').html('');
            setWeightInfo('matweight', 0);
            setWeightInfo('proweight', 0);
            setWeightInfo('unusweight', 0);
        }


    }


    function sumAllProd(){
        var sum = 0;
        $('.inputProduct').each(function(){
            //sum += parseFloat($(this).val()) ;
            sum += $(this).val() * 1;
        });
        return sum;
    }

    function sumAllMat(){
        var sum = 0;
        $('.qtyMaterial').each(function(){
            //sum += parseFloat($(this).val()) ;
            sum += $(this).text() * 1;
        });
        return sum;
    }

    function calculateAll(elem){

        totalProduct = sumAllProd();
        totalMat = sumAllMat();

        tot = totalMat - totalProduct;
        //setWeightInfo('matweight', tot);
        setWeightInfo('proweight', totalProduct);
        setWeightInfo('unusweight', tot);

    }

    function grabAllData(){
        var dat = {material:[], product:[]} ;
        
        $('.qtyMaterial').each(function(){
            id = $(this).attr('idmaterial');
            va = $(this).attr('valmat');
            datamat = {id:id,qty:va};
            dat.material.push(datamat);

        });

        $('.inputProduct').each(function(){
            id = $(this).attr('idproduct');
            va = $(this).val();
            dataprod = {id:id,qty:va};
            dat.product.push(dataprod);

        });
       /* a = JSON.stringify(dat);
        alert(a);*/
        return dat;
    }

    function submitData(){

        data = grabAllData();
        data = JSON.stringify(data);

         $.ajax({
            type: 'get',
            dataType: "json",
            url: '<?php echo WS_JQGRID."agripro.cassia_production_controller/submitData"; ?>'+'?param=123&<?php echo $this->security->get_csrf_token_name(); ?>='+'<?php echo $this->security->get_csrf_hash(); ?>',
            data:'data='+data,
            // timeout: 10000,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function(response) {
              if(response.success) {

                  swal({title: 'Info', text: response.message, html: true, type: "info"});
                  loadContentWithParams('agripro.cassia_production',{});

              }else{
                  swal({title: 'Attention', text: response.message, html: true, type: "warning"});
              }
            }

          });

    }

    function resetData(){
       loadContentWithParams('agripro.cassia_production',{}); 
    }
  
    jQuery(function ($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID . "agripro.cassia_production_controller/crud"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [
                {label: 'ID', name: 'production_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                {
                    label: 'Production Code', name: 'production_code', width: 170, align: "left", editable: true,
                    editoptions: {
                        size: 25,
                        maxlength: 32
                    },
                    editrules: {required: false}
                },
                {
                    label: 'Production Date', name: 'production_date', width: 120, align: "left", editable: false,
                    editrules: {required: false}
                },
                {
                    label: 'Created By', name: 'created_by', width: 120, align: "left", editable: false,
                    editrules: {required: false}
                },{
                    label: 'Updated Date', name: 'updated_date', width: 120, align: "left", editable: false,
                    editrules: {required: false}
                },{
                    label: 'Updated By', name: 'updated_by', width: 120, align: "left", editable: false,
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
            shrinkToFit: true,
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
            editurl: '<?php echo WS_JQGRID . "agripro.cassia_production_controller/crud"; ?>',
            caption: "Cassia Production Data"

        });

        jQuery('#grid-table').jqGrid('navGrid', '#grid-pager',
            {   //navbar options
                edit: false,
                editicon: 'fa fa-pencil blue bigger-120',
                add: false,
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
                width:400,
                caption:'Delete data',
                msg: "Once You delete selected record, it cannot be restored.<br>Are You sure to delete selected record?",
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
        $(grid_selector).jqGrid('setGridWidth', $(".row").width());
        $(pager_selector).jqGrid('setGridWidth', parent_column.width());
    }

</script>