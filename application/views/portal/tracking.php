<div class="row">
    <div class="col-md-12">
        <div class="post-content">
            <!--            <h2 style="text-align: center"><a href="#">Bergabung Bersama Kami Membangun Bangsa </a></h2>-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" class="form-control input-lg" id="input_txt" placeholder="Type Your Serial Number ..">
                  <span class="input-group-btn">
                    <a class="btn btn-default btn-lg" type="button" id="search">Go!</a>
                  </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->

        </div>
    </div>
</div>
<br>
<!--ATN-TES-SN-0001-->
<div class="row">
    <div class="col-md-12">
        <div id="show_tracking">

        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $("#search").click(function(){
        var input_txt =  $("#input_txt").val();
        if(input_txt){
            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('portal/submitTracking');?>",
                data: {input_txt: input_txt},
                success: function (data) {
                    $("#show_tracking").html(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#show_tracking").html(errorThrown);
                },
                timeout: 10000 // sets timeout to 10 seconds
            });
        }else{
            swal('','Input cant null','warning');
        }
    })
</script>