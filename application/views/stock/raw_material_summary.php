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
            <span>Raw Material Stock Summary</span>
        </li>
    </ul>
</div>

<div class="space-4"></div>
<h3>Raw Material Stock Summary</h3>

<div class="row">
    <div class="col-md-10">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Code</th>
                    <th>Total Stock (Kg)</th>
                </tr>
            </thead>
            <tbody id="tbody-summary">

            </tbody>
        </table>
    </div>
</div>

<script>

    $(function() {

        $.ajax({
            url: '<?php echo WS_JQGRID."agripro.stock_summary_controller/getSummary"; ?>',
            type: "POST",
            data: {sc_code : "'RAW_MATERIAL_STOCK'"},
            success: function (response) {
                $( "#tbody-summary" ).html( response );
            },
            error: function (xhr, status, error) {
                swal({title: "Error!", text: xhr.responseText, html: true, type: "error"});
            }
        });

    });

</script>