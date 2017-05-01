<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">

            <span class="col-xs-2" style="color: green"> SERIAL NUMBER  </span> : <?php echo $packing_inf->packing_batch_number;?> <br>
            <span class="col-xs-2" style="color: green"> PRODUCT NAME  </span> : <?php echo $packing_inf->product_code;?> <br>
            <span class="col-xs-2" style="color: green"> WEIGHT : </span>  : <?php echo $packing_inf->packing_kg;?> Kg<br>
            <span class="col-xs-2" style="color: green"> BATCH NUMBER  </span> : <?php echo $packing_inf->packing_serial;?> <br>
            <span class="col-xs-2" style="color: green"> PACKING DATE </span> : <?php echo $packing_inf->packing_tgl;?> <br>
            <span class="col-xs-2" style="color: green"> WAREHOUSE   </span> : <?php echo $packing_inf->wh_name;?> ( <?php echo $packing_inf->wh_location;?> )
            <br>

        </table>
    </div>
</div>
<hr>
<h4> Detail Packaging Source</h4>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    Farmer
                </th>
                <th>
                    Code
                </th>
                <th>
                    Lahan
                </th>
                <th>
                    Trx Number
                </th>
                <th>
                    Raw Material
                </th>
                <th>
                   Transaction Date
                </th>
                <th>
                    Farmer Address
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($details as $detail){;?>
            <tr>
                <td>
                    <?php echo $detail->fm_name;?>
                </td>
                <td>
                     <?php echo $detail->fm_code;?>
                </td>

                <td>
                    <?php echo $detail->plt_alamat;?>
                </td>
                <td>
                    <?php echo $detail->sm_no_trans;?>
                </td>
                <td>
                    <?php echo $detail->product_code;?>
                </td>
                <td>
                    <?php echo $detail->sm_tgl_masuk;?>
                </td>
                <td>
                    <?php echo $detail->fm_address;?>
                </td>
            </tr>
            <?php  } ;?>
            </tbody>
        </table>
    </div>
</div>