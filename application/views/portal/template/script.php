<!-- Vendor -->
<script src="<?php echo base_url();?>assets/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery.appear/jquery.appear.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-cookie/jquery-cookie.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/common/common.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery.validation/jquery.validation.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery.stellar/jquery.stellar.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery.gmap/jquery.gmap.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/isotope/jquery.isotope.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/vide/vide.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url();?>assets/swal/sweetalert.min.js"></script>


<!-- Theme Base, Components and Settings -->
<script src="<?php echo base_url();?>assets/js/theme.js"></script>

<!-- Theme Custom -->
<script src="<?php echo base_url();?>assets/js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="<?php echo base_url();?>assets/js/theme.init.js"></script>

<!-- <script src="vendor/style-switcher/style.switcher.js"></script> -->

<script type="text/javascript">
    $(document).ready(function () {

       // init();

        $('.setting_nav').click(function () {
            var nav = $(this).attr('id');
            if (!nav) {
                return false;
            } else {
                $(".setting_nav").removeClass('active');
                $(this).addClass('active');
                var title = $(this).text();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url();?>index.php/portal/" + nav,
                    data: {title: title},
                    success: function (data) {
                        $("#mid_content").html(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#mid_content").html(errorThrown);
                    },
                    timeout: 10000 // sets timeout to 10 seconds
                });
                return false;
            }

        })


    })

</script>
<script type="text/javascript">
    $(document).ajaxStart(function () {
        //Global Jquery UI Block
        $(document).ajaxStart($.blockUI({
            message: '<img src="<?php echo base_url();?>assets/image/loading.gif" /> Loading...',
            css: {
                border: 'none',
                padding: '5px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .6,
                color: '#fff'
            }

        })).ajaxStop($.unblockUI);
    });
    // Ajax setup csrf token.
    var csfrData = {};
    csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo
    $this->security->get_csrf_hash(); ?>';
    $.ajaxSetup({
        data: csfrData
    });
</script>
<!--<script>
    function init() {
        $.ajax({
            type: 'POST',
            url: "<?php /*echo base_url();*/?>index.php/home/beranda",
            data: {},
            success: function (data) {
                $(".portal_content").html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#portal_content").html(errorThrown);
            },
            timeout: 10000 // sets timeout to 10 seconds
        });
    }
</script>-->