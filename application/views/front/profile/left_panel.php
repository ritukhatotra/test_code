<div class="sidebar sidebar-inverse sidebar--style-1 bg-base-1 z-depth-2-top">
    <?php if($this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->row()->is_closed == 'yes'){?>
        <a class="badge-corner badge-corner-red" style="right: 15px;border-top: 90px solid  #DC0330;border-left: 90px solid transparent;">
            <span style="-ms-transform: rotate(45deg);/* IE 9 */-webkit-transform: rotate(45deg);/* Chrome, Safari, Opera */transform: rotate(45deg);font-size: 14px;margin-left: -24px;margin-top: -16px;"><?=translate('closed')?></span>
        </a>
    <?php }?>
    <div class="sidebar-object mb-0">
        <!-- Profile picture -->
        <div class="profile-picture">
            <?php
                $profile_image = $get_member[0]->profile_image;
                $images = json_decode($profile_image, true);
                if (file_exists('uploads/profile_image/'.$images[0]['thumb'])) {
                ?>
                    <div>
                        <div class="profile_img" id="show_img" style="background-image: url()"><img src="<?=base_url()?>uploads/profile_image/<?=$images[0]['thumb'].'?t='.time()?>"></div>
                    </div>
                <?php
                }
                else {
                ?>
                        <div class="profile_img" id="show_img" style="background-image: url(<?=base_url()?>uploads/profile_image/default_image.png)"></div>
                    
                <?php
                }
            ?>
            <div class="upload-demo-wrap" style="display:none;">
                                <div id="upload-demo"></div>
                            </div>
            <div class="profile-connect mt-1 mb-0" id="save_button_section" style="display: none">
			<button type="button" class="btn btn-xs btn-warning" id="cancel_save_image" ><i class="ion-close-round"></i> <?php echo translate('cancel')?></button>
                <button type="button" class="btn btn-success btn-xs" id="save_image" ><i class="ion-checkmark-round"></i> <?php echo translate('save')?></button> 
				
            </div>
            <div class="profile-connect mt-1 mb-0" id="cancel_save_button_section" style="display: none">
                
            </div>
            
            <label class="btn-aux" for="profile_image" style="cursor: pointer;display:<?php echo !empty($current_tab) ? 'none !important' : '';?>">
                <i class="ion ion-edit"></i>
            </label>
            <form action="<?=base_url()?>home/profile/update_image" method="POST" id="profile_image_form" enctype="multipart/form-data">
            <input type="hidden" id="profile_image_data" name="profile_image_data" />
                <input type="file" style="display: none;" id="profile_image" name="profile_image" onChange="readFile(event)"/>
              <!--  <input type="file" id="upload" style="display: none;" value="Choose a file" accept="image/*" name="profile_image">-->
                
            </form>
            <style>
            .upload-demo .upload-demo-wrap,
.upload-demo .upload-result,
.upload-demo.ready .upload-msg {
    display: none;
}
.upload-demo.ready .upload-demo-wrap {
    display: block;
}
.upload-demo.ready .upload-result {
    display: inline-block;    
}
.upload-demo-wrap {
   /*  width: 400px;
    height: 450px;
    margin: 30px auto; */
}

.upload-msg {
    text-align: center;
    padding: 50px;
    font-size: 22px;
    color: #aaa;
    width: 260px;
    margin: 50px auto;
    border: 1px solid #aaa;
}

.profile-picture.profile-picture--style-2 .cr-image{
    border-radius: unset;
    margin-top:  unset;
    border:  unset;
}

            </style>
           

<button class="upload-result"  style="display:none;">Result</button>
            
            
            
            <!-- <a href="#" class="btn-aux">
                <i class="ion ion-edit"></i>
            </a> -->
        </div>
		
		<div class="clearfix"></div><br>
		
		<div class="photo-gallery text-center">
		  <h5>Photo Gallery</h5>
         
          <?php 
                $get_gallery = $this->db->get_where("member", array("member_id" => $get_member[0]->member_id))->row()->gallery;
                $gallery_data = json_decode($get_gallery, true);
                ?>

                    
                          
                                <div class="light-gallery">
                                    <div class="row">
                                        <?php 
                                            foreach ($gallery_data as $value) {
                                                if (file_exists('uploads/gallery_image/'.$value['image'])) {
                                                ?> 
                                                    <div class="col-sm-4 col-4 p-2">
                                                        <a target="_blank" href="<?=base_url()?>uploads/gallery_image/<?=$value['image']?>" class="item">
                                                            <img src="<?=base_url()?>uploads/gallery_image/<?=$value['image']?>" class="img-fluid rounded">
                                                        </a>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="col-sm-4 col-4 p-2">
                                                        <a target="_blank" href="<?=base_url()?>uploads/gallery_image/default_image.png" class="item">
                                                            <img src="<?=base_url()?>uploads/gallery_image/default_image.png" class="img-fluid rounded">
                                                        </a>
                                                    </div>
                                                <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                    
<div class="clearfix"></div><br>					
                       
                    <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 gallery l_nav" onclick="profile_load('gallery')">
                    <b style="font-size: 12px"><i class="ion-android-add-circle"></i> <?php echo translate('add_images')?></b>
                </a>
					
			   <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 picture_privacy l_nav" onclick="profile_load('picture_privacy')">
                    <b style="font-size: 12px"><i class="ion-ios-gear"></i> <?php echo translate('settings')?></b>
                </a>		
					
		   </div>
		
        <!-- Profile details -->
		
        <!---div class="profile-details">
            <h2 class="heading heading-3 strong-500 profile-name"><?=$get_member[0]->first_name." ".$get_member[0]->last_name?></h2>
            <?php
                $education_and_career = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'education_and_career');
                $education_and_career_data = json_decode($education_and_career, true);
            ?>
            <h3 class="heading heading-6 strong-400 profile-occupation mt-3"><?=$education_and_career_data[0]['occupation']?></h3>
            <?php 
                $package_info = json_decode($get_member[0]->package_info, true);
            ?>
            <div class="profile-stats clearfix mt-2">
                <div class="stats-entry" style="width: 100%">
                    <span class="stats-count"><?=$get_member[0]->follower?></span>
                    <span class="stats-label text-uppercase"><?php echo translate('followers')?></span>
                </div>
            </div>
           
            <div class="profile-connect mt-5">
               <a href="#" class="btn btn-styled btn-block btn-circle btn-sm btn-base-5">Follow</a>
                <a href="#" class="btn btn-styled btn-block btn-circle btn-sm btn-base-2">Send message</a>
                <h2 class="heading heading-5 strong-400"><?php echo translate('package_informations')?></h2>
            </div>
            <div class="profile-stats clearfix mt-0">
                <div class="stats-entry">
                    <span class="stats-count"><?=$package_info[0]['current_package']?></span>
                    <span class="stats-label text-uppercase"><?php echo translate('current_package')?></span>
                </div>
                <div class="stats-entry">
                    <span class="stats-count"><?=currency($package_info[0]['package_price'])?></span>
                    <span class="stats-label text-uppercase"><?php echo translate('package_price')?></span>
                </div>
            </div>
            <div class="profile-stats clearfix mt-2">
                <div class="stats-entry">
                    <span class="stats-count"><?=$package_info[0]['payment_type']?></span>
                    <span class="stats-label text-uppercase"><?php echo translate('payment_gateway')?></span>
                </div>
                <div class="stats-entry">
                    <span class="stats-count"><?=$get_member[0]->express_interest?></span>
                    <span class="stats-label text-uppercase"><?php echo translate('remaining_interest')?></span>
                </div>
            </div>
            <div class="profile-stats clearfix mt-2">
                <div class="stats-entry">
                    <span class="stats-count"><?=$get_member[0]->direct_messages?></span>
                    <span class="stats-label text-uppercase"><?php echo translate('remaining_message')?></span>
                </div>
                <div class="stats-entry">
                    <span class="stats-count"><?=$get_member[0]->photo_gallery?></span>
                    <span class="stats-label text-uppercase"><?php echo translate('photo_gallery')?></span>
                </div>
            </div>
        </div--->
        <!-- Profile stats -->
		
        <!---div class="profile-useful-links clearfix">
            <div class="useful-links">
                <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 gallery l_nav" onclick="profile_load('gallery')">
                    <b style="font-size: 12px"><?php echo translate('gallery')?></b>
                </a>
                <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 happy_story l_nav" onclick="profile_load('happy_story')">
                    <b style="font-size: 12px"><?php echo translate('happy_story')?></b>
                </a>
                <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 my_packages l_nav" onclick="profile_load('my_packages')">
                    <b style="font-size: 12px"><?php echo translate('My_package')?></b>
                </a>
                <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 payments l_nav" onclick="profile_load('payments')">
                    <b style="font-size: 12px"><?php echo translate('payment_informations')?></b>
                </a>
                <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 picture_privacy l_nav" onclick="profile_load('picture_privacy')">
                    <b style="font-size: 12px"><?php echo translate('picture_privacy')?></b>
                </a>
               
                <a class="btn btn-styled btn-sm btn-white z-depth-2-bottom mb-3 change_pass l_nav" onclick="profile_load('change_pass')">
                    <b style="font-size: 12px"><?php echo translate('change_password')?></b>
                </a>
                
                <div class="text-center pt-3 pb-0">
                    <?php if($this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->row()->is_closed == 'yes'){?>
                        <a onclick="profile_load('reopen_account')">
                        <i class="fa fa-unlock"></i>
                        <?php echo translate('re-open_account?')?>
                    </a>
                    <?php }else{?>
                        <a onclick="profile_load('close_account')">
                            <i class="fa fa-lock"></i>
                            <?php echo translate('close_account')?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div --->
		
		
    </div>
</div>

<script>
    $("#profile_image__").change(function () {
        readURL(this);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#show_img").css({
                    "background-image" : "url("+ e.target.result +")"
                });
            }
            reader.readAsDataURL(input.files[0]);
            $("#save_button_section").show();
        }
    }
    $("#save_image__").click(function(e) {
        e.preventDefault();
        // alert('asdas');
        $("#profile_image_form").submit();
    })
    $(document).ready(function(){
    
$uploadCrop = $('#upload-demo').croppie({
    enableExif: true,
    viewport: {
        width:300,
        height:320,
        //type: 'square'
    },
    boundary: {
        width:345,
        height:365
    }
});

	//	
		$('#save_image').on('click', function (ev) {
		ev.preventDefault();
			$uploadCrop.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (resp) {
				popupResult({
					src: resp
				});
			});
		});
		$('#cancel_save_image').on('click', function (ev) {
		//location.reload();
			$("#save_button_section").hide();
	            $("#show_img").parent().show();
          	    $(".btn-aux").show();
          	    $(".upload-demo-wrap").hide();
          	    $("#cancel_save_button_section").hide();
		});
});

$(document).delegate('#profile_image', 'change', function () { readFile(this); });

function readFile(input) {
//debugger;
 			if (input.files && input.files[0]) {
 			
	            var reader = new FileReader();
	            
	            reader.onload = function (e) {
					$('.upload-demo').addClass('ready');
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	}).then(function(){
	            		console.log('jQuery bind complete');
	            	});
	            	
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	            
	            $("#save_button_section").show();
	            $("#show_img").parent().hide();
          	    $(".btn-aux").hide();
          	    $(".upload-demo-wrap").show();
          	    $("#cancel_save_button_section").show();
	            
	        }
	        }
	        

	
	
	function popupResult(result) {
		
        // alert('asdas');
        	$("#profile_image_data").val(result.src);
        	$("#profile_image_form").submit();
	}
</script>