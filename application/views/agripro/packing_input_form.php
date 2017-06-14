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
            <span>Input Packing</span>
        </li>
    </ul>
</div>
<div class="space-4"></div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Input Packing</div>
            </div>

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form method="post" action="" class="form-horizontal" id="form-packing">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="packed_by">Packer</label>
                            <div class="col-md-3">
                                <input type="text" name="packed_by" id="packed_by" class="form-control required">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" for="packing_product_id">Product</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="hidden" name="product_id" id="packing_product_id" class="form-control">
                                    <input type="text" name="product_code" id="packing_product_code" readonly="" class="form-control required" placeholder="Choose Product">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success" type="button" onclick="showLovProduct('packing_product_id','packing_product_code')">
                                            <span class="fa fa-search icon-on-right bigger-110"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" for="packing_date">Packing Date</label>
                            <div class="col-md-2">
                                <input type="text" name="packing_date" id="packing_date" class="form-control required">
                            </div>
                        </div>


                        <hr>
                        <div class="row">
                            <div class="col-md-offset-1 col-md-10">
                                <h3>Source Selection(s)</h3>
                                <button type="button" id="add_selection_item" class="btn btn-info btn-sm"> <i class="fa fa-add"></i> Add Selection Item </button>
                                <div class="form-group">
                                    <table class="table table-striped" id="tbl-source-selections">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Selection Item</th>
                                                <th>Selection Weight(Kg)</th>
                                                <th>Input Weight(Kg)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <td>1</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="hidden" name="source_selection_id[]" id="source_selection_id1" class="form-control">
                                                        <input type="text" name="source_product_code[]" id="source_product_code1" readonly="" class="form-control" placeholder="Choose Source">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-success" type="button" onclick="showLovSortir('source_selection_id1','source_product_code1','source_product_weight1')">
                                                                <span class="fa fa-search icon-on-right bigger-110"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td><input type="text" name="source_product_weight[]" id="source_product_weight1"  class="form-control" readonly=""></td>
                                                <td><input type="text" name="input_weight[]" id="input_weight1" class="form-control required" placeholder="Input Weight Here"></td>
                                        </tbody>
                                    </table>

                                   <div class="col-md-offset-11">
                                        <button type="button" id="add_item_input" class="btn btn-success btn-sm"> <i class="fa fa-add"></i> Save Item(s) </button>
                                    </div>
                                </div>


                                <h3>Input Selection(s)</h3>
                                <table class="table table-bordered table-striped" id="tbl-input-packing">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Weight(Kg)</th>
                                            <th>Serial</th>
                                            <th>Batch</th>
                                            <th>SID</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
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

<?php $this->load->view('lov/lov_product_packing.php'); ?>
<?php $this->load->view('lov/lov_selection.php'); ?>

<script>
    var global_added_record_selection = 1;
    var _SERIAL_NUMBER = '';
    var _BATCH_NUMBER = '';
    var _TOTAL_BATCH = 0;

    $(function() {
        var url = '<?php echo WS_JQGRID."agripro.packing_controller/getSerialNumber"; ?>';
        $.ajax({
            type: "POST",
            url: url,
            dataType : 'json',
            success: function(response) {
                if(response.success != true){
                    swal('Warning',response.message,'warning');
                }else{
                    _SERIAL_NUMBER = response.serial_number;
                    _BATCH_NUMBER = response.batch_number;
                    _TOTAL_BATCH = response.total;
                }
            }
        });
    });

    function generateBatchNumber() {

        var str = "" + _TOTAL_BATCH;
        var pad = "0000";
        var ans = pad.substring(0, pad.length - str.length) + str;

        _BATCH_NUMBER = ans;
        _SERIAL_NUMBER = _SERIAL_NUMBER.substr(0, _SERIAL_NUMBER.length-4) + ans;
    }

    function showLovProduct(id, code) {
        modal_lov_product_show(id,code);
    }

    function showLovSortir(selection_id, product_code, selection_weight) {
        modal_lov_selection_show(selection_id,product_code, selection_weight);
    }

    function hasDuplicates(array) {
        var valuesSoFar = [];
        for (var i = 0; i < array.length; ++i) {
            var value = array[i];
            if (valuesSoFar.indexOf(value) !== -1) {
                return true;
            }
            valuesSoFar.push(value);
        }
        return false;
    }

    function add(a, b) {
        return parseInt(a) + parseInt(b);
    }
</script>

<script>
    function insertSourceSelectionRow() {
        var jumlah_baris = document.getElementById('tbl-source-selections').rows.length;

        var tr = document.getElementById('tbl-source-selections').insertRow(jumlah_baris);
        var tdNo = tr.insertCell(0);
        var tdSelectionLov = tr.insertCell(1);
        var tdSelectionWeight = tr.insertCell(2);
        var tdInputWeight = tr.insertCell(3);
        var tdAction = tr.insertCell(4);


        global_added_record_selection += 1;
        tdNo.innerHTML = jumlah_baris;
        tdSelectionLov.innerHTML = '<div class="input-group">' +
                                        '<input type="hidden" name="source_selection_id[]" id="source_selection_id'+global_added_record_selection+'" class="form-control">' +
                                        '<input type="text" name="source_product_code[]" id="source_product_code'+global_added_record_selection+'" readonly="" class="form-control" placeholder="Choose Source">' +
                                        '<span class="input-group-btn">' +
                                            '<button class="btn btn-success" type="button" onclick="showLovSortir(\'source_selection_id'+global_added_record_selection+'\',\'source_product_code'+global_added_record_selection+'\',\'source_product_weight'+global_added_record_selection+'\')">'+
                                                '<span class="fa fa-search icon-on-right bigger-110"></span>' +
                                            '</button>'+
                                        '</span>'+
                                    '</div>';
        tdSelectionWeight.innerHTML = '<input type="text" name="source_product_weight[]" id="source_product_weight'+global_added_record_selection+'"  class="form-control" readonly="">';
        tdInputWeight.innerHTML = '<input type="text" name="input_weight[]" id="input_weight'+global_added_record_selection+'" class="form-control required" value="0">';
        tdAction.innerHTML = '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="deleteDataRow(this);" title="Delete"><i class="fa fa-times" title="Delete"></i> </a>';
    }

    function insertInputSelectionRow(weight, sid) {
        var jumlah_baris = document.getElementById('tbl-input-packing').rows.length;

        var tr = document.getElementById('tbl-input-packing').insertRow(jumlah_baris);
        var tdNo = tr.insertCell(0);
        var tdInputWeight = tr.insertCell(1);
        var tdSerial = tr.insertCell(2);
        var tdBatch = tr.insertCell(3);
        var tdSID = tr.insertCell(4);
        var tdAction = tr.insertCell(5);

        tdNo.innerHTML = jumlah_baris;
        tdInputWeight.innerHTML = '<input type="text" name="input_weight_sum[]" class="form-control required" readonly="" value="'+weight+'">';
        tdSerial.innerHTML = '<input type="text" value="'+_SERIAL_NUMBER+'" name="input_serial[]" class="form-control required">';
        tdBatch.innerHTML = '<input type="text" value="'+_BATCH_NUMBER+'" name="input_batch_number[]" class="form-control required">';
        tdSID.innerHTML = '<input type="text" name="input_sid[]" class="form-control required" readonly=""  value="'+sid+'">';
        tdAction.innerHTML = '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="deleteDataRowSelectionRow(this);" title="Delete"><i class="fa fa-times" title="Delete"></i> </a>';

        _TOTAL_BATCH += 1;
        generateBatchNumber();
    }

    function deleteDataRow(sender) {
        $(sender).parent().parent().remove();
    }

    function deleteDataRowSelectionRow(sender) {
        $(sender).parent().parent().remove();
        _TOTAL_BATCH -= 1;
        generateBatchNumber();
    }
</script>

<script>
    $(function() {
        $("#packing_date").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: 'bottom',
            todayHighlight: true
        });
    });

    $('#add_selection_item').on('click', function(e){
        insertSourceSelectionRow();
    });

    $('#add_item_input').on('click', function(e){
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var error = false;

        $('input[name="source_product_weight[]"]').each(function(){
            if($(this).val() == "") {
                swal('Information','All source item weight zero value','info');
                error = true;
                return false;
            }
        });

        $('input[name="source_selection_id[]"]').each(function(){
            if($(this).val() == "") {
                swal('Information','All Selection Item must be filled','info');
                error = true;
                return false;
            }
        });

        var source_selection_id_arr = $('input[name="source_selection_id[]"]').map( function() {
            if( $(this).val() == "" ) return 0;
            return $(this).val();
        }).get();

        if(hasDuplicates(source_selection_id_arr)) {
            error = true;
            swal('Information','Duplicate Item not allowed','info');
        }

        if(error) return;

        var source_product_weight_arr = $('input[name="source_product_weight[]"]').map( function() {
            if( $(this).val() == "" ) return 0;
            return $(this).val();
        }).get();

        var input_weight_arr = $('input[name="input_weight[]"]').map( function() {
            if( $(this).val() == "" ) return 0;
            return $(this).val();
        }).get();

        var sid_arr = new Array();
        for(var i = 0; i < input_weight_arr.length; i++) {
            if(parseInt(input_weight_arr[i]) > parseInt(source_product_weight_arr[i])) {
                swal('Information','Input weight greater than source weight('+input_weight_arr[i]+' > '+source_product_weight_arr[i]+') ','info');
                return false;
            }
            if(input_weight_arr[i] > 0)
            sid_arr.push(source_selection_id_arr[i] +'|'+input_weight_arr[i]);
        }


        var index = 0;
        $('input[name="source_product_weight[]"]').map( function() {
            var weight = $(this).val();
            $(this).val( weight - parseInt(input_weight_arr[index]));
            index++;
        });

        $('input[name="input_weight[]"]').map( function() {
            $(this).val(0);
        });

        //get total input weight(KG)
        var sum_input_weight = input_weight_arr.reduce(add,0);
        var sid_join_str = sid_arr.join(';');

        insertInputSelectionRow(sum_input_weight, sid_join_str);
    });


    $("#form-packing").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            if($("#form-packing").valid() == true){
                var url = '<?php echo WS_JQGRID."agripro.packing_controller/createForm"; ?>';
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType : 'json',
                    data: $("#form-packing").serialize(),
                    success: function(response) {

                        if(response.success != true){
                            swal('Warning',response.message,'warning');
                        }else{
                            loadContentWithParams('agripro.packing',{});
                        }

                    }
                });
            }

        });
</script>