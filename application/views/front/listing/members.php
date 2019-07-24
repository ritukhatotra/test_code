<?php 
if (empty($get_all_members)) {
    ?>
        <div class='text-center pt-5 pb-5'><i class='fa fa-exclamation-triangle fa-5x'></i><h5><?=translate('no_result_found!')?></h5></div>
    <?php
}else{
foreach ($get_all_members as $member): ?>
    <?php
//       $ok = $this->Crud_model->isCompleted($member->member_id);
//if($ok == true):
        $image = json_decode($member->profile_image, true);
    ?>
    <div class="block block--style-3 list z-depth-1-top row" id="block_<?=$member->member_id?>">
        <div class="profile-picture col-sm-4 p-0">
		    <?php if ($member->membership == 2): ?>
                <a class="badge-corner badge-corner-red">
                    <span style="-ms-transform: rotate(45deg);/* IE 9 */-webkit-transform: rotate(45deg);/* Chrome, Safari, Opera */transform: rotate(45deg);font-size: 10px;margin-left: -14px;">
                        <?=translate('premium')?>
                    </span>
                </a>
            <?php endif ?>
		     
		     <!-----   user Mobile  Buttons---->

                    <ul class="nav nav-inline right-user-buttons ">
                       
                       <?php include 'member_sidebar.php';  ?>
                    </ul>

           
			<!-----   user Mobile Buttons END ---->	
            <a onclick="return goto_profile(<?=$member->member_id?>)">
                    <?php
                    if (file_exists('uploads/profile_image/'.$image[0]['profile_image'])) {
                    ?>
                    <?php
                        $pic_privacy = $member->pic_privacy;
                        $pic_privacy_data = json_decode($pic_privacy, true);
                        $is_premium = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'membership');
                        if($pic_privacy_data[0]['profile_pic_show']=='only_me'){
                    ?>
                        <div class="listing-image">
						<img src="<?=base_url()?>uploads/profile_image/default.jpg"></div>
                        <?php }elseif ($pic_privacy_data[0]['profile_pic_show']=='premium' and $is_premium==2) {
                        ?>
                            <div class="listing-image"><img src="<?=base_url()?>uploads/profile_image/<?=$image[0]['profile_image']?>"></div>
                        <?php }elseif ($pic_privacy_data[0]['profile_pic_show']=='premium' and $is_premium==1) {
                        ?>
                            <div class="listing-image">
							 <img src="<?=base_url()?>uploads/profile_image/default.jpg">
							</div>
                        <?php }elseif ($pic_privacy_data[0]['profile_pic_show']=='all') {
                        ?>
                        <div class="listing-image">
						<img src="<?=base_url()?>uploads/profile_image/<?=$image[0]['profile_image']?>"></div>
                    <?php }else{ ?>
                        <div class="listing-image"><img src="<?=base_url()?>uploads/profile_image/default.jpg"></div>
                    <?php }
                    }
                    else {
                    ?>
                        <div class="listing-image"><img src="<?=base_url()?>uploads/profile_image/default.jpg"></div>
                    <?php
                    }
                    ?>
                </a>
        </div>
        <?php 
            $basic_info = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'basic_info');
            $basic_info_data = json_decode($basic_info, true);

            $education_and_career = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'education_and_career');
            $education_and_career_data = json_decode($education_and_career, true);

            $physical_attributes = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'physical_attributes');
            $physical_attributes_data = json_decode($physical_attributes, true);

            $spiritual_and_social_background = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'spiritual_and_social_background');
            $spiritual_and_social_background_data = json_decode($spiritual_and_social_background, true);

            $language = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'language');
            $language_data = json_decode($language, true);

            $present_address = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'present_address');
            $present_address_data = json_decode($present_address, true);
            $calculated_age = (date('Y') - date('Y', $member->date_of_birth));

            $mobile_number = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'mobile');            
            $belongs_to = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'belongs_to');            
        ?>
        <div class="block-title-wrapper col-sm-6">           
            <h3 class="heading heading-5 strong-600 <?php if($member->membership == 1){echo 'mt-4';} else {echo'mt-1';}?>">
                <a onclick="return goto_profile(<?=$member->member_id?>)" class="c-base-1"><?=$member->first_name." ".$member->last_name?></a>
            </h3>
<?php $online_status = $this->Crud_model->get_online_status($member->member_id);
?>
            <h4 class="heading heading-xs c-gray-light strong-400 <?php echo $online_status == 'Online'? 'online' : 'offline'; ?>"><i class="fa fa-circle"></i> <?php echo  $online_status; ?></h4>
			 <div class="clearfix"></div><hr>
			 
			 <div class="row list-box-columns">			 
			   <div class="col-sm-6 col-6"><?=$calculated_age?> yrs</div>
			   <div class="col-sm-6 col-6"><?=$member->height." ".translate('feet')?></div>
				
				<div class="col-sm-6 col-6">
				<?=$this->Crud_model->get_type_name_by_id('religion', $spiritual_and_social_background_data[0]['religion']);?>, 
				<?=$this->db->get_where('caste', array('caste_id'=>$spiritual_and_social_background_data[0]['caste']))->row()->caste_name?>
				</div>
				<div class="col-sm-6 col-6"><?=$this->Crud_model->get_type_name_by_id('marital_status', $basic_info_data[0]['marital_status'])?></div>
				
				 <div class="clearfix"></div>
				  <div class="col-sm-6 col-6">
				 <?=$this->Crud_model->get_type_name_by_id('language', $language_data[0]['mother_tongue']);?></div>
				 <div class="col-sm-6 col-6"><?=$education_and_career_data[0]['occupation']?> </div>
				 
				  <div class="clearfix"></div>
				  
				 <div class="col-sm-6">Living in <strong><?php if($present_address_data[0]['country']){echo $this->Crud_model->get_type_name_by_id('state', $present_address_data[0]['state']).', '.$this->Crud_model->get_type_name_by_id('country', $present_address_data[0]['country']);}?></strong></div>
				 
				 <div class="col-sm-6">From <strong><?= $belongs_to ?></strong> </div>
			   
			   
			   </div>
			 
            
        </div>
		
		<div class="col-sm-2 right-user-buttons-outer p-0">
            <ul class="nav flex-column right-user-buttons ">                       
                <?php include 'member_sidebar.php';  ?>
            </ul>
        </div>
			<!-----   Right  Buttons END ---->	
		
		
        <!---div class="block-footer b-xs-top">
            <div class="row align-items-center">
                <div class="col-sm-12 text-center">
                    <ul class="inline-links inline-links--style-3">
                        <li class="listing-hover">
                            <a onclick="return goto_profile(<?=$member->member_id?>)">
                                <i class="fa fa-id-card"></i><?php echo translate('full_profile')?>
                            </a>
                        </li>
                        <?php if($this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->row()->is_closed == 'yes'){ echo " "; }else{?>
                            <li class="listing-hover">
                                <?php
                                    $interests = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'interest');
                                    $interest = json_decode($interests, true);
                                    if (!empty($this->session->userdata('member_id'))) {
                                        if (in_assoc_array($member->member_id, 'id', $interest)) {
                                            $interest_onclick = 0;
                                            $interest_text = translate('interest_expressed');
                                            $interest_class = "c-base-1";
                                            $interest_style = "";
                                        }
                                        else {
                                            $interest_onclick = 1;
                                            $interest_text = translate('express_interest');
                                            $interest_class = "";
                                            $interest_style = "";
                                        }   
                                    }
                                    else {
                                        $interest_onclick = 1;
                                        $interest_text = translate('express_interest');
                                        $interest_class = "";
                                        $interest_style = "";
                                    }
                                ?>
                                <a id="interest_a_<?=$member->member_id?>" <?php if ($interest_onclick == 1){?>onclick="return confirm_interest(<?=$member->member_id?>)"<?php }?> style="<?=$interest_style?>">
                                    <span id="interest_<?=$member->member_id?>" class="<?=$interest_class?>">
                                       <i class="fa fa-heart"></i> <?=$interest_text?>
                                    </span>
                                </a>
                            </li>
                            <li class="listing-hover">
                                <?php
                                    $shortlists = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'short_list');
                                    $shortlist = json_decode($shortlists, true);
                                    if (!empty($this->session->userdata('member_id'))) {
                                        if (in_array($member->member_id, $shortlist)) {
                                            $shortlist_onclick = 0;
                                            $shortlist_text =  translate('shortlisted');
                                            $shortlist_class = "c-base-1";
                                            $shortlist_style = "";
                                        }
                                        else {
                                            $shortlist_onclick = 1;
                                            $shortlist_text =  translate('shortlist');
                                            $shortlist_class = "";
                                            $shortlist_style = "";
                                        }   
                                    }
                                    else {
                                        $shortlist_onclick = 1;
                                        $shortlist_text =  translate('shortlist');
                                        $shortlist_class = "";
                                        $shortlist_style = "";
                                    }
                                ?>
                                <a id="shortlist_a_<?=$member->member_id?>" 
                                    <?php 
                                        if ($shortlist_onclick == 1){?>onclick="return do_shortlist(<?=$member->member_id?>)"<?php }
                                        elseif ($shortlist_onclick == 0) {?>onclick="return remove_shortlist(<?=$member->member_id?>)"<?php }?> style="<?=$shortlist_style?>">
                                    <span id="shortlist_<?=$member->member_id?>" class="<?=$shortlist_class?>">
                                       <i class="fa fa-list-ul"></i> <?=$shortlist_text?>
                                    </span>
                                </a>
                            </li>
                            <li class="listing-hover">
                                <?php
                                    $followed = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'followed');
                                    $follow = json_decode($followed, true);
                                    if (!empty($this->session->userdata('member_id'))) {
                                        if (in_array($member->member_id, $follow)) {
                                            $followed_onclick = 0;
                                            $followed_text =  translate('unfollow');
                                            $followed_class = "c-base-1";
                                            $followed_style = "";
                                        }
                                        else {
                                            $followed_onclick = 1;
                                            $followed_text =  translate('follow');
                                            $followed_class = "";
                                            $followed_style = "";
                                        }   
                                    }
                                    else {
                                        $followed_onclick = 1;
                                        $followed_text =  translate('follow');
                                        $followed_class = "";
                                        $followed_style = "";
                                    }
                                ?>
                                <a id="followed_a_<?=$member->member_id?>"
                                    <?php 
                                        if ($followed_onclick == 1){?>onclick="return do_follow(<?=$member->member_id?>)"<?php }
                                        elseif ($followed_onclick == 0){?>onclick="return do_unfollow(<?=$member->member_id?>)"<?php }?> style="<?=$followed_style?>">
                                    <span id="followed_<?=$member->member_id?>" class="<?=$followed_class?>">
                                        <i class="fa fa-star"></i> <?=$followed_text?>
                                    </span>
                                </a>
                            </li>
                            <li class="listing-hover">
                                <a onclick="return confirm_ignore(<?=$member->member_id?>)">
                                    <i class="fa fa-ban"></i><?php echo translate('ignore')?>
                                </a>
                            </li>
							<li class="listing-hover">
                                <a onclick="return view_contact(<?=$member->member_id?>)">
                                    <i class="fa fa-phone"></i>View Contact
                                </a>
                            </li>
                        <?php } ?>
                    </ul>

                </div>
            </div>
        </div-->
    </div>
<?php //endif ?>
<?php endforeach ?>
<div id="pseudo_pagination" style="display: none;">
    <?= $this->ajax_pagination->create_links();?>
</div>
                                <?php } ?>
<script type="text/javascript">
    $('#pagination').html($('#pseudo_pagination').html());
</script>

<script>
    var isloggedin = "<?=$this->session->userdata('member_id')?>";

    function goto_profile(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_view_full_profile_of_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?=translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            window.location.href = "<?=base_url()?>home/member-profile/"+id;
        }
    }

    var rem_interests = parseInt("<?=$this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'express_interest')?>");
    function confirm_interest(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_express_interest_on_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?=translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            if (rem_interests <= 0) {
                $("#active_modal").modal("toggle");
                $("#modal_header").html("<?php echo translate('buy_premium_packages')?>");
                $("#modal_body").html("<p class='text-center'><b>Remaining Express Interest(s): "+rem_interests+" times</b><br><?php echo translate('please_buy_packages_from_the_premium_plans.')?></p>");
                $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?=translate('close')?></button> <a href='<?=base_url()?>home/plans' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('premium_plans')?></a>");
            }
            else {
                $("#active_modal").modal("toggle");
                $("#modal_header").html("<?php echo translate('confirm_express_interest')?>");
                $("#modal_body").html("<p class='text-center'><b><?php echo translate('remaining_express_interest(s): ')?>"+rem_interests+" <?php echo translate('times')?></b><br><span style='color:#DC0330;font-size:11px'>**N.B. <?php echo translate('expressing_an_interest_will_cost_1_from_your_remaining_interests')?>**</span></p>");
                $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?=translate('close')?></button> <a href='#' id='confirm_interest' class='btn btn-sm btn-base-1 btn-shadow' onclick='return do_interest("+id+")' style='width:25%'><?php echo translate('confirm')?></a>");
            }
        }    
        return false;
    }

    function do_interest(id) {
        
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_express_interest_on_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            $("#confirm_interest").removeAttr("onclick");
            $("#confirm_interest").html("<i class='fa fa-refresh fa-spin'></i> <?php echo translate('processing')?>..");
            $("#interest_a_"+id).removeAttr("onclick");
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url()?>home/add-interest/"+id,
                    cache: false,
                    success: function(response) {
                        rem_interests = rem_interests - 1;
                        $("#active_modal .close").click();
                       // $("#interest_"+id).html("<i class='fa fa-heart'></i> <?php echo translate('interest_expressed')?>");
                        //$("#interest_"+id).attr("class","c-base-1");
                       // $("#interest_a_"+id).css("cssText", "");
                        
                        $(".interest_a_"+id).each(function(){
                            $(this).addClass('interest_expressed');
                        });
                        
                        $(".interest_a_"+id).each(function(){
                            $(this).html('<span id="interest_"'+id+'><i class="ion-ios-heart"></i></span><span>Interest Expressed</span>');
                        });
                        
                        //$("#interest_a_"+id).addClass('interest_expressed');
                       // $("#interest_a_"+id).html('<span id="interest_"'+id+'><i class="ion-ios-heart"></i></span>');
                        
                        
                        $("#success_alert").show();
                        $(".alert-success").html("<?php echo translate('you_have_expressed_an_interest_on_this_member!')?>");
                        $('#danger_alert').fadeOut('fast');
                        setTimeout(function() {
                            $('#success_alert').fadeOut('fast');
                        }, 5000); // <-- time in milliseconds
                    },
                    fail: function (error) {
                        alert(error);
                    }
                });
            }, 500); // <-- time in milliseconds
        }    
        return false;
    }

    function do_shortlist(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_shortlist_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            $("#shortlist_a_"+id).removeAttr("onclick");
            $("#shortlist_"+id).html("<i class='fa fa-refresh fa-spin'></i> <?php echo translate('shortlisting')?>..");
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url()?>home/add-shortlist/"+id,
                    cache: false,
                    success: function(response) {
                        $("#shortlist_"+id).html("<i class='fa fa-list-ul'></i> <?php echo translate('shortlisted')?>");
                        $("#shortlist_"+id).attr("class","c-base-1");
                        $("#shortlist_a_"+id).attr("onclick","return remove_shortlist("+id+")");
                        $("#shortlist_a_"+id).css("cssText", "");
                        $("#success_alert").show();
                        $(".alert-success").html("<?php echo translate('you_have_shortlisted_this_member!')?>");
                        $('#danger_alert').fadeOut('fast');
                        setTimeout(function() {
                            $('#success_alert').fadeOut('fast');
                        }, 5000); // <-- time in milliseconds
                    },
                    fail: function (error) {
                        alert(error);
                    }
                });
            }, 500); // <-- time in milliseconds
        }    
        return false;
    }

    function remove_shortlist(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_remove_this_member_from_shortlist')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?=translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            $("#shortlist_a_"+id).removeAttr("onclick");
            $("#shortlist_"+id).html("<i class='fa fa-refresh fa-spin'></i> <?php echo translate('removing')?>..");
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url()?>home/remove-shortlist/"+id,
                    cache: false,
                    success: function(response) {
                        $("#shortlist_"+id).html("<i class='fa fa-list-ul'></i> <?php echo translate('shortlist')?>");
                        $("#shortlist_"+id).attr("class","");
                        $("#shortlist_a_"+id).attr("onclick","return do_shortlist("+id+")");
                        $("#shortlist_a_"+id).css("cssText", "");
                        $("#danger_alert").show();
                        $(".alert-danger").html("<?php echo translate('you_have_removed_this_member_from_shortlist!')?>");
                        $('#success_alert').fadeOut('fast');
                        setTimeout(function() {
                            $('#danger_alert').fadeOut('fast');
                        }, 5000); // <-- time in milliseconds
                    },
                    fail: function (error) {
                        alert(error);
                    }
                });
            }, 500); // <-- time in milliseconds
        }    
        return false;
    }

    function do_follow(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_follow_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            $("#followed_a_"+id).removeAttr("onclick");
            $("#followed_"+id).html("<i class='fa fa-refresh fa-spin'></i> <?php echo translate('following')?>..");
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url()?>home/add-follow/"+id,
                    cache: false,
                    success: function(response) {
                        $("#followed_"+id).html("<i class='fa fa-star'></i> <?php echo translate('unfollow')?>");
                        $("#followed_"+id).attr("class","c-base-1");
                        $("#followed_a_"+id).attr("onclick","return do_unfollow("+id+")");
                        $("#followed_a_"+id).css("cssText", "");
                        $("#success_alert").show();
                        $(".alert-success").html("<?php echo translate('you_have_followed_this_member!')?>");
                        $('#danger_alert').fadeOut('fast');
                        setTimeout(function() {
                            $('#success_alert').fadeOut('fast');
                        }, 5000); // <-- time in milliseconds
                    },
                    fail: function (error) {
                        alert(error);
                    }
                });
            }, 500); // <-- time in milliseconds
        }    
        return false;
    }

    function do_unfollow(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_unfollow_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            $("#followed_a_"+id).removeAttr("onclick");
            $("#followed_"+id).html("<i class='fa fa-refresh fa-spin'></i> <?php echo translate('unfollowing')?>..");
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url()?>home/add-unfollow/"+id,
                    cache: false,
                    success: function(response) {
                        $("#followed_"+id).html("<i class='fa fa-star'></i> <?php echo translate('follow')?>");
                        $("#followed_"+id).attr("class","");
                        $("#followed_a_"+id).attr("onclick","return do_follow("+id+")");
                        $("#followed_a_"+id).css("cssText", "");
                        $("#danger_alert").show();
                        $(".alert-danger").html("<?php echo translate('you_have_unfollowed_this_member!')?>");
                        $('#success_alert').fadeOut('fast');
                        setTimeout(function() {
                            $('#danger_alert').fadeOut('fast');
                        }, 5000); // <-- time in milliseconds
                    },
                    fail: function (error) {
                        alert(error);
                    }
                });
            }, 500); // <-- time in milliseconds
        }    
        return false;
    }

    function view_contact(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_ignore_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
             $.ajax({
                    type: "POST",
                    url: "<?=base_url()?>home/member-profile/"+id+"/contact",
                    cache: false,
                    success: function(response) {
                       $("#active_modal").modal("toggle");
                       $("#modal_header").html("<?php echo translate('view_contact')?>");            
                       $("#modal_body").html(response);
                       $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button>");
                    },
                    fail: function (error) {
                        alert(error);
                    }
                });          
           
        }    
        return false;
    }

    function confirm_ignore(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_ignore_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('confirm_ignore')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('are_you_sure_that_you_want_to_ignore_this_member?')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='#' id='confirm_ignore' class='btn btn-sm btn-base-1 btn-shadow' onclick='return do_ignore("+id+")' style='width:25%'><?php echo translate('confirm')?></a>");
        }    
        return false;
    }

    function do_ignore(id) {
        // alert(id);
        if (isloggedin == "") {
            $("#active_modal").modal("toggle");
            $("#modal_header").html("<?php echo translate('please_log_in')?>");
            $("#modal_body").html("<p class='text-center'><?php echo translate('please_log_in_to_ignore_this_member')?></p>");
            $("#modal_buttons").html("<button type='button' class='btn btn-danger btn-sm btn-shadow' data-dismiss='modal' style='width:25%'><?php echo translate('close')?></button> <a href='<?=base_url()?>home/login' class='btn btn-sm btn-base-1 btn-shadow' style='width:25%'><?php echo translate('log_in')?></a>");
        }
        else {
            $("#confirm_ignore").removeAttr("onclick");
            $("#confirm_ignore").html("<i class='fa fa-refresh fa-spin'></i> <?php echo translate('processing')?>..");
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url()?>home/add-ignore/"+id,
                    cache: false,
                    success: function(response) {
                        $("#active_modal .close").click();
                        $("#block_"+id).hide();
                        $("#danger_alert").show();
                        $(".alert-danger").html("<?php echo translate('you_have_ignored_this_member!')?>");
                        $('#success_alert').fadeOut('fast');
                        setTimeout(function() {
                            $('#danger_alert').fadeOut('fast');
                        }, 5000); // <-- time in milliseconds
                    },
                    fail: function (error) {
                        alert(error);
                    }
                });
            }, 500); // <-- time in milliseconds
        }    
        return false;
    }
</script>