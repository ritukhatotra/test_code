<!-- MAIN WRAPPER -->
<div class="body-wrap">
    <div id="st-container" class="st-container">
        <div class="st-pusher">
            <div class="st-content">
                <div class="st-content-inner">
                	<?php if ($page == 'login' || $page == 'registration') { } else { ?>
						<!-- Navbar -->
						<div class="top-navbar align-items-center" style="display:none;">
						    <div class="container">
						        <div class="row align-items-center py-1" style="padding-bottom: 0px !important">																		<!----===================    Language Commented Code   =======================--->									
						            <?php /* <div class="col-lg-4 col-md-5">
	                                    <nav class="top-navbar-menu" style="margin:0px !important;">
	                                        <ul class="top-menu" style="float: left !important;width: 40%;">
	                                            <li class="aux-languages dropdown">
		                                            <a class="pt-0 pb-0">
		                                            	<?php
						                                    if ($set_lang = $this->session->userdata('language')) {

						                                    } else {
						                                        $set_lang = $this->db->get_where('general_settings', array('type' => 'language'))->row()->value;
						                                    }
						                                    $lid = $this->db->get_where('site_language_list', array('db_field' => $set_lang))->row()->site_language_list_id;
						                                    $lnm = $this->db->get_where('site_language_list', array('db_field' => $set_lang))->row()->name;
						                                ?>
		                                            	<img src="<?=base_url()?>uploads/language_list_image/language_<?=$lid?>.jpg" style="width: 20px;margin-top: -2px">
		                                            	<span><?=$lnm?></span>
		                                            </a>
	                                                <ul id="auxLanguages" class="sub-menu">
	                                                	<?php
						                                    $langs = $this->db->get_where('site_language_list', array('status' => 'ok'))->result_array();
						                                    foreach ($langs as $row) {
						                                ?>
						                                    <li <?php if ($set_lang == $row['db_field']) { ?>class="active"<?php } ?> >
						                                        <a class="set_langs" data-href="<?php echo base_url(); ?>home/set_language/<?php echo $row['db_field']; ?>">
						                                            <img src="<?=base_url()?>uploads/language_list_image/language_<?=$row['site_language_list_id']?>.jpg" width="20px">
			                                                    	<span class="language"><?=$row['name']?></span>
						                                            <?php if ($set_lang == $row['db_field']) { ?>
						                                                <i class="fa fa-check"></i>
						                                            <?php } ?>
						                                        </a>
						                                    </li>
						                                <?php
						                                    }
						                                ?>
	                                                </ul>
	                                            </li>
	                                        </ul>
	                                        <ul class="top-menu" style="float: left !important;width: 60%;">
	                                            <li class="aux-languages dropdown">
		                                            <a class="pt-0 pb-0">
		                                            	<?php                                            
								                            if($currency_id = $this->session->userdata('currency')){} else {
								                                $currency_id = $this->db->get_where('business_settings', array('type' => 'currency'))->row()->value;
								                            }
								                            $symbol = $this->db->get_where('currency_settings',array('currency_settings_id'=>$currency_id))->row()->symbol;
								                            $c_name = $this->db->get_where('currency_settings',array('currency_settings_id'=>$currency_id))->row()->name;
								                        ?>
								                        <span><?=$c_name.' ('.$symbol.')'?></span>
		                                            </a>
	                                                <ul id="auxLanguages" class="sub-menu">
	                                                	<?php
								                            $currencies = $this->db->get_where('currency_settings',array('status'=>'ok'))->result_array();
								                            foreach ($currencies as $row)
								                            {
								                        ?>
								                            <li <?php if($currency_id == $row['currency_settings_id']){ ?>class="active"<?php } ?> >
								                                <a class="set_langs" data-href="<?php echo base_url(); ?>home/set_currency/<?php echo $row['currency_settings_id']; ?>">
								                                    <?php echo $row['name']; ?> (<?php echo $row['symbol']; ?>)
								                                    <?php if($currency_id == $row['currency_settings_id']){ ?>
								                                        <i class="fa fa-check"></i>
								                                    <?php } ?>
								                                </a>
								                            </li>
								                        <?php
								                            }
								                        ?>
	                                                </ul>
	                                            </li>
	                                        </ul>
                                        </nav>
									</div> */?>																		
									<!-- -===================    Language Commented Code End   ================- -->									
						            <div class="col-lg-8 col-md-7">
						               
						            </div>
						        </div>
						    </div>
						</div>
                	<?php } ?>
					
					
						<!----=============== mobile-nav for Mobile ==================-->	
						
						   <aside id="mobile-menu-outer" class="mobile-menu-outer">
						   <?php if (!empty($this->session->userdata['member_id'])) { ?>
						     <div class="mobile-user-info"> 
						   
						         <!-- Profile picture -->
					           <div class="profile-picture">
						          <?php
							$profile_image = $get_member[0]->profile_image;
							$images = json_decode($profile_image, true);
							if (file_exists('uploads/profile_image/'.$images[0]['thumb'])) {
								$pic_privacy = $get_member[0]->pic_privacy;
								$pic_privacy_data = json_decode($pic_privacy, true);
								$is_premium = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'membership');
								if($pic_privacy_data[0]['profile_pic_show']=='only_me'){
							?>
							
																
								<img src="<?=base_url()?>uploads/profile_image/default.jpg" alt="Picture">
								
								
							<?php }elseif ($pic_privacy_data[0]['profile_pic_show']=='premium' and $is_premium==2) {
							?>
							
							<img src="<?=base_url()?>uploads/profile_image/<?=$images[0]['thumb']?>" alt="Picture">
								
							<?php }elseif ($pic_privacy_data[0]['profile_pic_show']=='premium' and $is_premium==1) {
							?>
															
								<img src="<?=base_url()?>uploads/profile_image/default.jpg" alt="Picture">
								
							<?php }elseif ($pic_privacy_data[0]['profile_pic_show']=='all') {
							?>
							
								<img src="<?=base_url()?>uploads/profile_image/<?=$images[0]['thumb']?>" alt="Picture">
							
							
							<?php }else{
								?>								
									<img src="<?=base_url()?>uploads/profile_image/default.jpg" alt="Picture">
								
								<?php }
							} else {
							?>
								
								<img src="<?=base_url()?>uploads/profile_image/default_image.png" alt="Picture">
								
							<?php
							}
						?>
					              </div>
								  
								 <div class="user-details">
										<h2 class="heading heading-6 strong-600 profile-name">
										<?=$get_member[0]->first_name." ".$get_member[0]->last_name?>
										</h2>
										<p class=""><b><?=translate('ID').' - '?></b><?=$get_member[0]->member_profile_id?></p>
										<a class="link" href="<?=base_url()?>home/profile/edit_full_profile"><i class="ion-edit"></i> Edit Profile</a>
									
									</div>
									
						  </div>			
									
						 <div class="clearfix"></div>     
						 <?php }?>            
					
								  
								 <ul class="nav mobile-user-menu  tree-menu">
								  <?php if (!empty($this->session->userdata['member_id'])) { ?>
								    <li><a href="<?=base_url()?>home/profile/notifications-list"><i class="ion-ios-bell"></i> Notifications
									<span class="badge badge-primary badge-pill">14</span>
									</a> </li>
								    <li><a href="<?=base_url()?>home/profile/messaging-list"><i class="ion-chatbubble-working"></i> Messages
									<span class="badge badge-primary badge-pill">14</span>
									</a>									
									</li>									
									<li class="has-child">
									<a class="tree-toggler nav-header"><i class="ion-android-person"></i>  My Profile</a>
										<ul class="nav nav-list tree">
											<li ><a href="<?=base_url()?>home/profile/my-interests"><?php echo translate('sent_interests')?></a></li>
                    <li><a href="<?=base_url()?>home/profile/received-interests"><?php echo translate('received_interests')?></a></li>
                    <li class="<?php echo $_SERVER[REQUEST_URI] == '/home/profile/shortlist' ? 'active' : ''; ?>"> <a href="<?=base_url()?>home/profile/shortlist"><?php echo translate('shortlist')?></a> </li>
                    <li><a href="<?=base_url()?>home/profile/followed-users"><?php echo translate('followed_users')?></a></li>
                    <li><a href="<?=base_url()?>home/profile/messaging-list"> <?php echo translate('messaging')?></a></li>
                    <li><a  href="<?=base_url()?>home/profile/ignored-list"><?php echo translate('ignored_list')?> </a></li>
					
  </ul>
									</li>
					                    <li>
									<a class="tree-toggler nav-header"><i class="ion-android-person"></i><?php echo translate('matches')?></a> </a>
										<ul class="nav nav-list tree">									
					                    <li>
					                    <a href="<?=base_url()?>home/matches">
					                    <?php echo translate('my_matches')?></a>
					                    </li>
					                </ul>
									</li>
									<?php } ?>
									
									
									<li class="has-child">
									<a class="tree-toggler nav-header"><i class="ion-android-person"></i><?php echo translate('search_partner')?></a> </a>
										<ul class="nav nav-list tree">
											 
					                    <li>
					                    <a href="<?=base_url()?>home/listing">
					                    <?php echo translate('all_members')?></a>
					                    </li>
					                    <li>
					                    <a  href="<?=base_url()?>home/listing/premium_members">
					                    <?php echo translate('premium_members')?></a>
					                    </li>
					                    <li>
					                    <a href="<?=base_url()?>home/listing/free_members">
					                    <?php echo translate('free_members')?></a>
					                    </li>
					                    </ul>
					                    </li>
					                    
					                    <li>
					                <a href="<?=base_url()?>home/plans">
					                <i class="ion-check"></i>
					                <?php echo translate('pricing_plans')?></a>
					                </li>
					                <li>
					                <a href="<?=base_url()?>home/contact_us">
					                <i class="ion-check"></i>
					                <?php echo translate('contact_us')?></a>
					                </li>
					                
					                   
							
									
								</ul>
								   <div class="text-center mt-3">	
                                     <?php if (empty($this->session->userdata['member_id'])) { ?>
                                   								  
											 <a href="<?=base_url()?>home/login" class="btn btn-styled btn-base-1 btn-rouded"><i class="ion-android-person"></i> <?php echo translate('log_in')?></a>
											<?php }else{?>
											<a href="<?=base_url()?>home/logout" class="btn btn-styled btn-base-1 btn-rouded"><i class="fa fa-power-off"></i> <?php echo translate('log_out')?></a>
									  
					                <?php }?>
									  </div>
							  
							  </aside>
							
						<!----=============== mobile-nav  for Mobile END ==================-->	


					<nav class="navbar navbar-expand-lg navbar-light bg-default navbar--link-arrow navbar--uppercase">
					    <div class="container navbar-container">
					        <!-- Brand/Logo -->
					        <a class="navbar-brand" href="<?=base_url()?>home/">
					        	<?php
					        		$header_logo_info = $this->db->get_where('frontend_settings', array('type' => 'header_logo'))->row()->value;
                                    $header_logo = json_decode($header_logo_info, true);
                                    if (file_exists('uploads/header_logo/'.$header_logo[0]['image'])) {
                                    ?>
                                        <img src="<?=base_url()?>uploads/header_logo/<?=$header_logo[0]['image']?>" class="img-responsive" height="100%">
                                    <?php
                                    }
                                    else {
                                    ?>
                                        <img src="<?=base_url()?>uploads/header_logo/default_image.png" class="img-responsive" height="100%">
                                    <?php
                                    }
                                ?>
					        </a>
					        <div class="d-inline-block">
							
					            <!---========== Navbar toggler  ===========-->								
								
									<button type="button" class="navbar-toggle">
											 <label class="label">Menu</label>
										  <div class="bars">
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
										  </div>
									</button>
								
								
								
					        </div>
					        <div class="collapse navbar-collapse align-items-center justify-content-end" id="navbar_main">
							
									
					            <!-- Navbar links -->
					            <ul class="navbar-nav float-right " data-hover="dropdown">
					                <?php /*<li class="custom-nav">
					                <a class="nav-link <?php if($page == 'home'){?>nav_active<?php }?>" href="<?=base_url()?>home" aria-haspopup="true" aria-expanded="false">
					                <?php echo translate('home')?></a>
									</li>*/?>
									
					                <li class="custom-nav dropdown">
					                <a class="nav-link <?php if($page == 'listing' || $page == 'member_profile'){?>nav_active<?php }?>" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					                <?php echo translate('search_partner')?></a>
					                <ul class="dropdown-menu" style="border: 1px solid #f1f1f1 !important;">
					                    <li class="dropdown dropdown-submenu">
					                    <li>
					                    <a class="dropdown-item <?php if(!empty($nav_dropdown)){if($nav_dropdown == 'all_members'){?>nav_active_dropdown<?php }}?>" href="<?=base_url()?>home/listing">
					                    <?php echo translate('all_members')?></a>
					                    </li>
					                    <li>
					                    <a class="dropdown-item <?php if(!empty($nav_dropdown)){if($nav_dropdown == 'premium_members'){?>nav_active_dropdown<?php }}?>" href="<?=base_url()?>home/listing/premium_members">
					                    <?php echo translate('premium_members')?></a>
					                    </li>
					                    <li>
					                    <a class="dropdown-item <?php if(!empty($nav_dropdown)){if($nav_dropdown == 'free_members'){?>nav_active_dropdown<?php }}?>" href="<?=base_url()?>home/listing/free_members">
					                    <?php echo translate('free_members')?></a>
					                    </li>
					                </ul>
									</li>
									<?php if (!empty($this->session->userdata['member_id'])) { ?>
									<li class="custom-nav dropdown">
					                <a class="nav-link <?php if($page == 'matches'){?>nav_active<?php }?>" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					                <?php echo translate('matches')?></a>
					                <ul class="dropdown-menu" style="border: 1px solid #f1f1f1 !important;">
					                    <li class="dropdown dropdown-submenu">
					                    <li>
					                    <a class="dropdown-item <?php if(!empty($nav_dropdown)){if($nav_dropdown == 'matches'){?>nav_active_dropdown<?php }}?>" href="<?=base_url()?>home/matches">
					                    <?php echo translate('my_matches')?></a>
					                    </li>
					                <?php /* <li>
					                    <a class="dropdown-item <?php if(!empty($nav_dropdown)){if($nav_dropdown == 'premium_members'){?>nav_active_dropdown<?php }}?>" href="<?=base_url()?>home/listing/premium_members">
					                    <?php echo translate('premium_members')?></a>
					                    </li>
					                    <li>
					                    <a class="dropdown-item <?php if(!empty($nav_dropdown)){if($nav_dropdown == 'free_members'){?>nav_active_dropdown<?php }}?>" href="<?=base_url()?>home/listing/free_members">
					                    <?php echo translate('free_members')?></a>
					                    </li>*/?>
					                </ul>
									</li>
									<?php } ?>
					                <li class="custom-nav">
					                <a class="nav-link <?php if($page == 'plans' || $page == 'subscribe'){?>nav_active<?php }?>" href="<?=base_url()?>home/plans" aria-haspopup="true" aria-expanded="false">
					                <?php echo translate('pricing_plans')?></a>
					                </li>
					                <?php 
					                /*<li class="custom-nav">
					                <a class="nav-link <?php if($page == 'stories' || $page == 'story_detail'){?>nav_active<?php }?>" href="<?=base_url()?>home/stories" aria-haspopup="true" aria-expanded="false">
					                <?php echo translate('happy_stories')?></a>
					                </li>*/
					                ?>
					                <li class="custom-nav">
					                <a class="nav-link <?php if($page == 'contact_us'){?>nav_active<?php }?>" href="<?=base_url()?>home/contact_us" aria-haspopup="true" aria-expanded="false">
					                <?php echo translate('contact_us')?></a>
					                </li>
					            </ul>	

                        <!---nav class="top-navbar-menu"></nav-->
					            	<ul class="top_bar_right"></ul>									
					          					                
					            								
					        </div>
							
						
							
					    </div>
					</nav>
					
				
					<script type="text/javascript">
					    $(document).ready(function () {
					        $('.set_langs').on('click', function () {
					            var lang_url = $(this).data('href');
					            $.ajax({url: lang_url, success: function (result) {
					                    location.reload();
					                }});
					        });
							
							$('.navbar-toggle').click(function() {  
									$(this).toggleClass('closed');        
									$('#mobile-menu-outer').toggleClass('active');
									//$('.main_wrapper').toggleClass('active');
									//$('html').toggleClass('active');
								});
								
							 // SIDEBAR TREE NAV 
								$(document).ready(function () {
									$('a.tree-toggler').click(function () {
										$(this).parent().children('ul.tree').toggle(300);
									});
								});
								 // SIDEBAR TREE NAV  END	
								
							
							
					    });
					</script>
					<style>
						.navbar-brand {
						    display: inline-block;
						     padding-top: 0px; 
						     padding-bottom: 0px; 
						     margin-right: 0px; 
						    font-size: 1.25rem;
						    line-height: inherit;
						    white-space: nowrap;
						}
					</style>