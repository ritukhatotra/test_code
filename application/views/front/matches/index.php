<?php 
if(!empty($this->session->userdata['member_id'])) {
	include_once APPPATH.'views/front/profile_nav.php';
} ?>
<style>

    @media (max-width: 576px) {

        .listing-image {

            height: 330px !important;

        }

    }

    

</style>

<section class="page-title page-title--style-1">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-12 text-center">

                <h2 class="heading heading-3 strong-400 mb-0"><?php echo translate('best_matches')?></h2>

            </div>

        </div>

    </div>

</section>

<section class="slice sct-color-1">

    <div class="container">

        <div class="row">

            <div class="col-lg-12">



                <input type="hidden" id="member_type" value="<?php if(!empty($member_type)){echo $member_type;}?>">

   

                <div class="block-wrapper" id="result">

                    <!-- Loads List Data with Ajax Pagination -->

                </div>

                <div id="pagination" style="float: right;">

                    <!-- Loads Ajax Pagination Links -->

                </div>

            </div>
        </div>

    </div>

</section>

<script>

    $(document).ready(function(){
        filter_members('0','matches');
    });
    function filter_members(page, type){
           /* var member_type = "";

            if ($("#member_type").val() != "") {

                member_type = $("#member_type").val();

            }*/

            var url = '<?php echo base_url(); ?>home/ajax_member_list/'+page+'/'+type;

        

        var form = $('#filter_form');

        var place = $('#result');

        var formdata = false;

        if (window.FormData){

            formdata = new FormData(form[0]);

        }

        $(".btn-back-to-top").click();

        $.ajax({

            url: url, // form action url

            type: 'POST', // form submit method get/post

            dataType: 'html', // request type html/json/xml

            data: formdata ? formdata : form.serialize(), // serialize form data 

            cache       : false,

            contentType : false,

            processData : false,

            beforeSend: function() {

                place.html("");

                place.html("<div class='text-center pt-5 pb-5' id='payment_loader'><i class='fa fa-refresh fa-5x fa-spin'></i><p>Please Wait...</p></div>").fadeIn();

            },

            success: function(data) {

                var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

                if (width <= 768) {

                    $(".size-sm").css("display", "none");

                    $(".size-sm-btn").css("display", "block");

                }

                setTimeout(function(){

                    place.html(data); // fade in response data

                }, 20);

                setTimeout(function(){

                    place.fadeIn(); // fade in response data

                }, 30);

            },

            error: function(e) {

                console.log(e)

            }

        });

    }



    function adv_search(){

        $(".size-sm").css("display", "block");

        $(".size-sm-btn").css("display", "none");

    }

</script>

<style>

    /* xs */

    .size-sm {

        display: none;

    }

    .size-sm-btn {

        display: block;

    }

    /* sm */

    @media (min-width: 768px) {

        .size-sm {

            display: none;

        }

        .size-sm-btn {

            display: block;

        }

    }

    /* md */

    @media (min-width: 992px) {

        .size-sm {

            display: block;

        }

        .size-sm-btn {

            display: none;

        }

    }

    /* lg */

    @media (min-width: 1200px) {

        .size-sm {

            display: block;

        }

        .size-sm-btn {

            display: none;

        }

    }

</style>