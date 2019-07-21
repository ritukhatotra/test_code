											<?php
												if (!empty($this->session->userdata['member_id'])) {
													if($this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->row()->is_closed == 'yes'){ ?>
														<li class="dropdown dropdown--style-2 dropdown--animated float-left">
															<span class="badge badge-md badge-pill bg-danger" style="margin-top: 6px;">Account Closed</span>
														</li>
													<?php 
													}
												} 
											?>
											<?php
											if (!empty($this->session->userdata['member_id'])) {
												$noti_counter = 0;
												$msg_counter = 0;
												$notifications = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'notifications');
												$notification = json_decode($notifications, true);
												sort_array_of_array($notification, 'time', SORT_DESC);
											}
											
											if (!empty($this->session->userdata['member_id'])) {
											?>
												<li class="dropdown dropdown--style-2 dropdown--animated float-left">
													<a class="dropdown-icon dropdown-toggle has-notification noti_click" href="#" data-toggle="dropdown" aria-expanded="false">
													    <i class="ion-ios-bell-outline"></i> <span>Notification</span>
													</a>
													<?php include 'notification.php'; ?>
							                    </li>
												<li class="dropdown dropdown--style-2 dropdown--animated float-left">
								                    <a class="dropdown-icon dropdown-toggle has-notification" href="#" data-toggle="dropdown" aria-expanded="false">
									                    <i class="ion-ios-email-outline"></i> <span>Inbox</span>
								                    </a>
								                    <?php include 'messages.php'; ?>
							                    </li>
							                   <?php /*<li class="dropdown dropdown--style-2 dropdown--animated float-left">
											
											        <a class="dropdown-toggle has-badge c-base-1" href="<?=base_url()?>home/profile">
								                    	<?php
								                    		$profile_image = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'profile_image');
											                $images = json_decode($profile_image, true);
											                if (file_exists('uploads/profile_image/'.$images[0]['thumb'])) {
											                ?>
											                    <div class="top_nav_img" style="background-image: url(<?=base_url()?>uploads/profile_image/<?=$images[0]['thumb']?>)"></div>
											                <?php
											                }
											                else {
											                ?>
											                    <div class="top_nav_img" style="background-image: url(<?=base_url()?>uploads/profile_image/default_image.png"></div>
											                <?php
											                }
											            ?>
									                    <span class="dropdown-text strong-500 d-none d-lg-inline-block d-xl-inline-block" style="margin-top: 5px"><?=$this->session->userdata['member_name']?></span>
								                    </a>
							                    </li>*/?>
												<li class="dropdown dropdown--style-2 dropdown--animated float-left">
											
											        <a class="dropdown-toggle has-badge c-base-1" data-toggle="dropdown" href="#">
								                    	<?php
								                    		$profile_image = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'profile_image');
											                $images = json_decode($profile_image, true);
											                if (file_exists('uploads/profile_image/'.$images[0]['thumb'])) {
											                ?>
											                    <div class="top_nav_img" style="background-image: url(<?=base_url()?>uploads/profile_image/<?=$images[0]['thumb']?>)"></div>
											                <?php
											                }
											                else {
											                ?>
											                    <div class="top_nav_img" style="background-image: url(<?=base_url()?>uploads/profile_image/default_image.png"></div>
											                <?php
											                }
											            ?>
									                    <span class="dropdown-text strong-500 d-none d-lg-inline-block d-xl-inline-block" style="margin-top: 5px"><?=$this->session->userdata['member_name']?></span>
								                    </a>

													 <ul class="dropdown-menu">
     <li class="nav-item"> <a class="dropdown-item" href="<?=base_url()?>home/profile"><i class="ion-android-person"></i>My Profile</a></li>
  
    <li class="nav-item">
                        <a class="nav-link my_interests p_nav" href="<?=base_url()?>home/profile/my-interests">
                            <i class="fa fa-heart"></i> <?php echo translate('sent_interests')?>
                        </a>
                    </li>
<li class="nav-item">
                        <a class="nav-link received_interests p_nav" href="<?=base_url()?>home/profile/received-interests">
                            <i class="fa fa-heart"></i> <?php echo translate('received_interests')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link short_list p_nav"  href="<?=base_url()?>home/profile/shortlist">
                            <i class="fa fa-list-ul"></i> <?php echo translate('shortlist')?>
                        </a>
                    </li>
                    <!---li class="nav-item">
                        <a class="nav-link followed_users p_nav" href="<?=base_url()?>home/profile/followed-users">
                            <i class="fa fa-star"></i> <?php //echo translate('followed_users')?>
                        </a>
                    </li-->
                    <li class="nav-item">
                        <a class="nav-link messaging p_nav" href="<?=base_url()?>home/profile/messaging-list">
                            <i class="fa fa-comments-o"></i> <?php echo translate('messaging')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ignored_list p_nav" href="<?=base_url()?>home/profile/ignored-list">
                            <i class="fa fa-ban"></i> <?php echo translate('ignored_list')?>
                        </a>
                    </li>
 <li class="nav-item">
                        <a class="nav-link ignored_list p_nav" href="<?=base_url()?>home/profile/gallery-list">
                            <i class="ion-images"></i> <?php echo translate('gallery')?>
                        </a>
                    </li>
 <li class="nav-item">
                        <a class="nav-link ignored_list p_nav" href="<?=base_url()?>home/profile/settings">
                            <i class="ion-settings"></i> <?php echo translate('settings')?>
                        </a>
                    </li>
					<li class="nav-item devider"><hr></li>
				
					<li class="nav-item text-center">

                       <?php
												if (!empty($this->session->userdata['member_id'])) {
												?>
							                    	<a href="<?=base_url()?>home/logout" class="nav-link"><i class="fa fa-power-off"></i> <?php echo translate('log_out')?></a>
												<?php	
												}?>
                    </li>
  </ul>
							                    </li>
											<?php	
											}
											else {
											?>
													
											<?php
											}
											?>
											
											<?php
												if (empty($this->session->userdata['member_id'])) {												
												?>	

                                               <li class="float-left pt-2"><a href="<?=base_url()?>registration"><i class="ion-android-person-add"></i> <?php echo translate('Register')?></a></li>						                    
							                    <li class="float-left pb-1">
												
		                                            <a href="<?=base_url()?>login" class="btn btn-login btn-base-1 btn-rouded"><i class="ion-android-person"></i> <?php echo translate('log_in')?></a>
		                                            <!---a href="<?=base_url()?>home/registration" class="btn btn-styled btn-xs btn-base-1 btn-shadow"><i class="fa fa-user"></i> <?php //echo translate('register')?></a-->
												
		                                        </li>
												<?php
												}
												?>

						                    <script>
						                    var isloggedin = '<?php echo $this->session->userdata['member_id'] ?>'
											    $(document).ready(function(){
											        if (isloggedin != "") {
											            var noti_count = "<?php if (!empty($noti_counter)){echo $noti_counter;}?>";
											            if (noti_count > 0) {
											                $('.noti_counter').show();
											                $('.noti_counter').html(noti_count);
											            }
											            var msg_count = "<?php if (!empty($msg_counter)){echo $msg_counter;}?>";
											            if (msg_count > 0) {
											                $('.msg_counter').show();
											                $('.msg_counter').html(msg_count);
											            }
											        }

											        $(".noti_click").click(function(){
											            var member_id = "<?=$this->session->userdata('member_id')?>";
											            if (member_id != "") {
											                $.ajax({
											                    type: "POST",
											                    url: "<?=base_url()?>home/refresh_notification/"+member_id,
											                    cache: false,
											                    success: function(response) {
											                        $('.noti_counter').hide();
											                        // $('#test').html(response);
											                    }
											                });
											            }
											        });
													
													$(".custom-nav ").hover(function(){
													$(".dropdown-menu").removeClass('show');
													});
													
											    });
											</script>