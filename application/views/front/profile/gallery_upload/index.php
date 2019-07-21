
<div class="card-title">
    <h3 class="heading heading-6 strong-500">
    <b><?php echo translate('upload_your_image')?></b></h3>
</div>
<div class="card-body">
  
    <form class="form-default" id="gallery_upload_form" method="post" action="<?=base_url()?>home/gallery_upload/add" role="form" enctype="multipart/form-data">
        <!--div class="form-group has-feedback">
            <label class="control-label"><?php echo translate('image_title')?></label>
            
            <input type="hidden" id="photo_upload_title" name="title" class="form-control" value="Gallery Image">
            <input type="text" id="photo_upload_title" name="title" class="form-control" required>
        </div-->
        <input type="hidden" id="photo_upload_title" name="title" class="form-control" value="Gallery Image">
        <div class="form-group has-feedback select_div" id="img_main">
            <!--label class="control-label"><?php //echo translate('upload_image')?></label-->
            <div class="col-sm-12 galery_image_div" style="margin:2px; padding:2px;" id="galery_image_div">
			     <label for="image_main" class="control-label select_a_photo">
                <a class="btn btn-styled  btn-base-2 btn-shadow ml-1" style="color: #FFF"><?php echo translate('select_a_photo')?></a>
            </label>
			
                <img class="img-responsive img-border blah z-depth-1-bottom" style="width: 100%;border: 1px solid #e6e6e6;" src="<?=base_url()?>uploads/happy_story_image/default_image.jpg" class="img-sm">
            </div>
             <div class="gallery_upload-demo-wrap" id="gallery_upload-demo-wrap" style="display:none;margin:2px; padding:2px; width: 100%;">
                 <div id="gallery_upload-demo"></div>
             </div>

            
<input type="hidden" id="gallery_profile_image_data" name="gallery_profile_image_data" />
            <input type="file" id="image_main" name="image" class="form-control imgInp" style="display: none" required>
        </div>
        <!-- <div class="form-group has-feedback col-10 ml-auto mr-auto select_div" id="img_main">
            <div class="col-12">
                <small class="text-danger" id="val_error">
                </small>
            </div>
        </div> -->
        <div class="form-group has-feedback text-center mt-5">
            <a href="#" class="btn btn-shadow" data-filter="*" onclick="profile_load('gallery')"><i class="ion-arrow-left-c"></i> <?php echo translate('go_back')?></a>
            <button type="submit" id="btn_gallery_upload" class="btn btn-shadow" data-filter="*" style="display: none;">
			<i class="ion-upload"></i><?php echo translate('upload')?></button>
               <!--a id="gallery_save_image_" class="btn btn-sm btn-base-1 btn-shadow" style="color: white"><?php echo translate('upload')?></a-->
            <a id="submit_gallery" class="btn btn-base-1 btn-shadow" onclick="return confirm_gallery_upload(<?=$this->session->userdata('member_id')?>)" style="color: white"><i class="ion-upload"></i> <?php echo translate('upload')?></a>
        </div>
    </form>  
</div>
<script>
    $('.swiper-container').swiper();
    // SCRIT FOR IMAGE UPLOAD
    function readURL_all(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.select_div').find('.blah').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#img_main").on('change', '.imgInp', function () {
        readURL_all(this);
        //galleryReadFile(this);
    });

 $(document).ready(function(){
     $('#gallery_save_image').on('click', function (ev) {
		     ev.preventDefault();
			$gallery_uploadCrop .croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (resp) {
				gallerypopupResult({
					src: resp
				});
			});
		});
		$('#cancel_save_image').on('click', function (ev) {
	            $("#galery_image_div").show();
          	    $(".gallery_upload-demo-wrap").hide();
		});
      $gallery_uploadCrop = $('#gallery_upload-demo').croppie({
        enableExif: true,
        viewport: {
         width: 320,
         height: 320,
         type: 'square'
       },
       boundary: {
         width: 322,
         height: 322
       }
    });
});
function galleryReadFile(input) {
//debugger;
 			if (input.files && input.files[0]) {
 			
	            var reader = new FileReader();
	            
	            reader.onload = function (e) {
					$('.gallery_upload-demo').addClass('ready');
	            	$gallery_uploadCrop .croppie('bind', {
	            		url: e.target.result
	            	}).then(function(){
	            		console.log('jQuery bind complete');
	            	});
	            	
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	            
	            $("#galery_image_div").hide();
          	    $("#gallery_upload-demo-wrap").show();	            
	        }
	        }
	        

	
	
	function gallerypopupResult(result) {
		
        // alert('asdas');
        	$("#gallery_profile_image_data").val(result.src);
               var id= "<?=$this->session->userdata('member_id')?>";
              confirm_gallery_upload(id); 
	}


</script>
<script>

    var isloggedin = "<?=$this->session->userdata('member_id')?>";
    var rem_photos = parseInt("<?=$this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'photo_gallery')?>");

    function confirm_gallery_upload(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_upload_images_in_gallery')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'>Close</button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            if (rem_photos <= 0) {
                $("#active_modal").modal("toggle");
                $("#modal_header").html("<?php echo translate('buy_premium_packages')?>");
                $("#modal_body").html("<p class='text-center'><b><?php echo translate('remaining_gallery_upload(s): ')?>"+rem_photos+" <?php echo translate('times')?></b><br><?php echo translate('please_buy_packages_from_the_premium_plans.')?></p>");
                $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'>Close</button> <a href='<?=base_url()?>home/plans' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('premium_plans')?></a>");
            }
            else {
                $("#active_modal").modal("toggle");
                $("#modal_header").html("<?php echo translate('confirm_gallery_upload')?>");
                $("#modal_body").html("<p class='text-center'><b><?php echo translate('remaining_gallery_upload(s): ')?>"+rem_photos+" <?php echo translate('times')?></b><br><span style='color:#DC0330;font-size:11px'>**N.B. <?php echo translate('uploading_an_image_will_cost_1_from_your_remaining_gallery_uploads')?>**</span></p>");
                $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'>Close</button> <a href='#' id='confirm_gallery_upload' class='btn btn-sm btn-base-1 btn-shadow' onclick='return do_gallery_upload("+id+")' style='width:25%'><?php echo translate('confirm')?></a>");
            }
        }    
        return false;
    }

    function do_gallery_upload(id) {
        // alert(id);
        if (isloggedin != "") {
            $("#confirm_gallery_upload").removeAttr("onclick");
            $("#confirm_gallery_upload").html("<i class='fa fa-refresh fa-spin'></i> <?php echo translate('processing');?>..");
            setTimeout(function() {
                $("#active_modal .close").click();
                $("#btn_gallery_upload").click();
            }, 500); // <-- time in milliseconds
        }    
        return false;
    }
</script>