<?php 
if(!empty($this->session->userdata['member_id'])) {
	include_once APPPATH.'views/front/profile_nav.php';
} ?>
<section class="slice sct-color-2">
    <div class="profile">
        <div class="container">
            <div class="row cols-md-space cols-sm-space cols-xs-space">
                <!-- Alerts for Member actions -->
                <div class="col-lg-3 col-md-4" id="success_alert" style="display: none; position: fixed; top: 15px; right: 0; z-index: 9999">
                    <div class="alert alert-success fade show" role="alert">
                        <!-- Success Alert Content -->
                        <!-- You have <b>Successfully</b> Edited your Profile! -->
                    </div>
                </div>
                <div class="col-lg-3 col-md-4" id="danger_alert" style="display: none; position: fixed; top: 15px; right: 0; z-index: 9999">
                    <div class="alert alert-danger fade show" role="alert">
                        <!-- Success Alert Content -->
                        <!-- You have <b>Successfully</b> Edited your Profile! -->
                    </div>
                </div>
                <!-- Alerts for Member actions -->
                <?php 
                    // Leading Json data
                    $basic_info = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'basic_info');
                    $basic_info_data = json_decode($basic_info, true);

                    $present_address = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'present_address');
                    $present_address_data = json_decode($present_address, true);

                    $education_and_career = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'education_and_career');
                    $education_and_career_data = json_decode($education_and_career, true);

                    $physical_attributes = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'physical_attributes');
                    $physical_attributes_data = json_decode($physical_attributes, true);

                    $language = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'language');
                    $language_data = json_decode($language, true);

                    $hobbies_and_interest = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'hobbies_and_interest');
                    $hobbies_and_interest_data = json_decode($hobbies_and_interest, true);

                    $personal_attitude_and_behavior = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'personal_attitude_and_behavior');
                    $personal_attitude_and_behavior_data = json_decode($personal_attitude_and_behavior, true);

                    $residency_information = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'residency_information');
                    $residency_information_data = json_decode($residency_information, true);

                    $spiritual_and_social_background = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'spiritual_and_social_background');
                    $spiritual_and_social_background_data = json_decode($spiritual_and_social_background, true);

                    $life_style = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'life_style');
                    $life_style_data = json_decode($life_style, true);

                    $astronomic_information = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'astronomic_information');
                    $astronomic_information_data = json_decode($astronomic_information, true);

                    $permanent_address = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'permanent_address');
                    $permanent_address_data = json_decode($permanent_address, true);

                    $family_info = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'family_info');
                    $family_info_data = json_decode($family_info, true);

                    $additional_personal_details = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'additional_personal_details');
                    $additional_personal_details_data = json_decode($additional_personal_details, true);

                    $partner_expectation = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'partner_expectation');
                    $partner_expectation_data = json_decode($partner_expectation, true);

                    $privacy_status = $this->Crud_model->get_type_name_by_id('member', $get_member[0]->member_id, 'privacy_status');
                    $privacy_status_data = json_decode($privacy_status, true);
                ?>
                <div class="col-lg-4">
                    <?php include_once APPPATH.'views/front/member_profile/left_panel.php';?>
                </div>
				
				<!-----===============   Right Side Starts here ===============--->
				
                <div class="col-lg-8">
                	

					
					
					<div class="card no-border">
					
					<div class="row">				

					   <div class="col-sm-9">
					
						   <div class="card-title">
							  <h3 class="heading heading-5 strong-600 mt-2">
								 <a onclick="return goto_profile(9)" class="c-base-1"><?=$get_member[0]->first_name?> <?=$get_member[0]->last_name?></a>
							  </h3>
							  <div class="clearfix"></div>
							  <?php $online_status = $this->Crud_model->get_online_status($get_member[0]->member_id);
	?>
							  <h4 class="heading heading-sm c-gray-light strong-400 <?php echo $online_status == 'Online'? 'online' : 'offline'; ?>"><i class="fa fa-circle"></i> <?php echo  $online_status; ?></h4>
							  
						   </div>
					   <div class="clearfix"></div>
					   <div class="card-body">
						  <div class="row list-box-columns">
							 <div class="col-sm-6">
							 
								<div class="row">								  
									   <div class="col-6 p-0"><b><?php echo translate('age')?>, Height:</b></div>
									  <div class="col-6 p-0"><?=$calculated_age = (date('Y') - date('Y', $get_member[0]->date_of_birth));?> yrs, <?=$get_member[0]->height." ".translate('feet')?></div>								  
								   </div>							   
								   
								   <div class="row">								  
									   <div class="col-6 p-0"><b><?php echo translate('mother_tongue')?>:</b></div>
									  <div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('language', $language_data[0]['mother_tongue']);?></div>								  
								   </div>
								   
								   <div class="row">								  
									   <div class="col-6 p-0">
									   <b><?php echo translate('religion')?>:</b></div>
									  <div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('religion', $spiritual_and_social_background_data[0]['religion']);?>/<?php echo $this->db->get_where('caste',array('caste_id'=>$spiritual_and_social_background_data[0]['caste']))->row()->caste_name; ?></div>								  
								   </div>
								   
								    <div class="row">								  
									   <div class="col-6 p-0"><b><?php echo translate('education')?>:</b></div>
									  <div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('education_level', $education_and_career_data[0]['highest_education'], 'education_level_name')?></div>								  
								   </div>
								   
								   

							 </div>
							 
							 <div class="col-sm-6">							 
							      
									<div class="row">								  
									   <div class="col-6 p-0"><b><?php echo translate('marital_status')?>:</b></div>
									   <div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('marital_status', $basic_info_data[0]['marital_status'])?></div>
									 </div>
									 
								    <div class="row">								  
									   <div class="col-6 p-0"><b><?php echo translate('occupation')?>:</b></div>
									   <div class="col-6 p-0"><?=$education_and_career_data[0]['occupation']?></div>
									  </div>
									  
									 <div class="row">								  
									   <div class="col-6 p-0"><b>Living in:</b></div>
									   <div class="col-6 p-0"><?php if($present_address_data[0]['country']){echo $this->Crud_model->get_type_name_by_id('state', $present_address_data[0]['state']).', '.$this->Crud_model->get_type_name_by_id('country', $present_address_data[0]['country']);}?> </div>
									 </div>
									 
									 <div class="row">								  
									   <div class="col-6 p-0"><b><?php echo translate('From')?>:</b></div>
									   <div class="col-6 p-0"><?=$get_member[0]->belongs_to?></div>
									 </div>
									 

							 </div><!---- Col-md-6 End--->
						  </div>
					   </div>
					   
					   
					   </div>
					    
               		<!-----   Right  Buttons Start---->	
					
			  <div class="col-sm-3 right-user-buttons-outer">

                    <ul class="nav flex-column right-user-buttons ">
                       
                        <?php if($this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->row()->is_closed == 'yes'){ echo " "; }else{?>
                            <li class="listing-hover">
                                <?php
                                    $interests = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'interest');
                                    $interest = json_decode($interests, true);
                                    if (!empty($this->session->userdata('member_id'))) {
                                        
                                        if (in_assoc_array($get_member[0]->member_id, 'id', $interest)) {
                                            $interest_onclick = 0;
                                            $interest_text = translate('interest_expressed');
                                            $interest_class = "interest_expressed";
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
                                <a id="interest_a_<?=$get_member[0]->member_id?>" <?php if ($interest_onclick == 1){?>onclick="return confirm_interest(<?=$get_member[0]->member_id?>)"<?php }?> style="<?=$interest_style?>" class="<?php echo $interest_class ?>">
                                    <span id="interest_<?=$get_member[0]->member_id?>">
                                       <i class="ion-ios-heart"></i> 
                                    </span>
									<span><?=$interest_text?></span>
                                </a>
                            </li>                            
                            
<li class="listing-hover">

 <?php
                        $if_message = $this->db->get_where('message_thread', array('message_thread_from' => $get_member[0]->member_id, 'message_thread_to' => $this->session->userdata('member_id')))->row();
                        if (!$if_message) {
                            $if_message = $this->db->get_where('message_thread', array('message_thread_from' => $this->session->userdata('member_id'), 'message_thread_to' => $get_member[0]->member_id))->row();
                        }

                        if ($if_message) {
                            $message_onclick = 0;
                            $message_text = translate('write_message');
                            $message_class = "";//"btn btn-styled btn-block btn-sm btn-white z-depth-2-bottom li_active";
                        }
                        else {
                            $message_onclick = 1;
                            $message_text = translate('write_message');
                            $message_class = "";//"btn btn-styled btn-block btn-sm btn-white z-depth-2-bottom";
                        }
                    ?>
   
                 <a <?php if ($message_onclick == 1){?>onclick="return confirm_message(<?=$get_member[0]->member_id?>)"<?php }?> href="<?=base_url()?>home/profile/messaging-list" id="message_a_<?=$get_member[0]->member_id?>">

 <span>
                                         <i class="ion-chatbubbles"></i>
                                    </span>
									<span><?=$message_text?></span>
                    </a>
                                 <!--<a onclick="return confirm_ignore(<?=$member->member_id?>)">
                                     <i class="ion-chatbubbles"></i><?=$message_text?>
                                </a>-->
                            </li>
							<li class="listing-hover">
                                <a onclick="return view_contact(<?=$get_member[0]->member_id?>)">
                                    <i class="ion-android-call"></i><span>View Contact</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>

        </div>
			<!-----   Right  Buttons END ---->		   
					   
					   
					</div>
					</div>
                 <!-----   Card  END ---->	

                    	
                    <div class="widget mt-4">
                        <div class="card z-depth-2-top" id="profile_load">
                            <div class="card-title">
                                <h3 class="heading heading-6 strong-500 pull-left">
                                    <b>Profile Information</b>
                                </h3>
								
							  
							  <div class="created-by">
								 <?php echo translate('Member_ID')?> - <b class="c-base-1"><?=$get_member[0]->member_profile_id?></b>
								 <?php $on_behalf = $this->Crud_model->get_type_name_by_id('on_behalf', $basic_info_data[0]['on_behalf']); ?>
								 |   Created by: <strong><?= $on_behalf ? $on_behalf : 'Self'; ?></strong>

							  </div>
                            </div>
							
                            <div class="card-body" style="padding: 1.5rem 0.5rem;">
                                <!-- Contact information -->
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_introduction">
                                        <?php include_once 'introduction.php'; ?>
                                    </div>
                                </div>
                                <!---div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_basic_info">
                                        <?php //include_once 'basic_info.php'; ?>
                                    </div>
                                </div-->
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'present_address'))->row()->value == "yes") {
                                        if ($privacy_status_data[0]['present_address'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_present_address">
                                        <?php include_once 'present_address.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'education_and_career'))->row()->value == "yes") {
                                        if ($privacy_status_data[0]['education_and_career'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_education_and_career">
                                        <?php include_once 'education_and_career.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'physical_attributes'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['physical_attributes'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_physical_attributes">
                                        <?php include_once 'physical_attributes.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'language'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['language'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_language">
                                        <?php include_once 'language.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'hobbies_and_interest'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['hobbies_and_interest'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_hobbies_and_interest">
                                        <?php include_once 'hobbies_and_interest.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'personal_attitude_and_behavior'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['personal_attitude_and_behavior'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_personal_attitude_and_behavior">
                                        <?php include_once 'personal_attitude_and_behavior.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'residency_information'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['residency_information'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_residency_information">
                                        <?php include_once 'residency_information.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'spiritual_and_social_background'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['spiritual_and_social_background'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_spiritual_and_social_background">
                                        <?php include_once 'spiritual_and_social_background.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'life_style'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['life_style'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_life_style">
                                        <?php include_once 'life_style.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'astronomic_information'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['astronomic_information'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_astronomic_information">
                                        <?php include_once 'astronomic_information.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'permanent_address'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['permanent_address'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_permanent_address">
                                        <?php include_once 'permanent_address.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'family_info'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['family_info'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_family_info">
                                        <?php include_once 'family_info.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'additional_personal_details'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['additional_personal_details'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_additional_personal_details">
                                        <?php include_once 'additional_personal_details.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'partner_expectation'))->row()->value == "yes") {
                                    if ($privacy_status_data[0]['partner_expectation'] == 'yes') {
                                ?>
                                <div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
                                    <div id="section_partner_expectation">
                                        <?php include_once 'partner_expectation.php'; ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
            
				
            </div><!-----===============   Right Side END here ===============--->
			
			
        </div><!----==============   Container END here=========--->
    </div>
</section>