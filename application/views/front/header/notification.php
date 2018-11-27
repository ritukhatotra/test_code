<sup class="badge bg-base-1 noti_badge noti_counter" style="display: none;"> <!-- Counts Notification with JavaScript  --> </sup>



<ul class="dropdown-menu notify-drop">
            <div class="notify-drop-title">
            	<div class="row">
            		<div class="col-md-6 col-sm-6 col-xs-6"><?php echo translate('notifications')?></div>
            		<div class="col-md-6 col-sm-6 col-xs-6 text-right"><a href="" class="rIcon allRead" data-tooltip="tooltip" data-placement="bottom" title="tümü okundu."><i class="fa fa-dot-circle-o"></i></a></div>
            	</div>
            </div>
            <!-- end notify title -->
			
            <!-- notify content -->
            <div class="drop-content">			
		
			<?php 
        $listed_messaging_members = $this->Crud_model->get_listed_messaging_members($this->session->userdata('member_id'));
        sort_array_of_array($listed_messaging_members, 'message_thread_time', SORT_DESC);
        foreach ($listed_messaging_members as $messaging_member) {
        	if($this->db->get_where("member", array("member_id" => $messaging_member['member_id']))->row()->is_closed == 'no'){
	        	if ($this->db->get_where('member', array('member_id' => $messaging_member['member_id']))->row()->member_id) {
	        		$member = $this->session->userdata('member_id');
		        	if(!$this->Crud_model->is_message_thread_seen($messaging_member['message_thread_id'],$member)){
				        	$msg_counter++;
				    }
		        	$messaging_member_info = $this->db->get_where('member', array('member_id' => $messaging_member['member_id']))->row();
		        	?>
		
            	<li>
				
				<div class="row">
            		<div class="col-md-3 col-sm-3 col-xs-3">
					<div class="notify-img">

<?php
											$msg_profile_image = $this->Crud_model->get_type_name_by_id('member', $messaging_member_info->member_id, 'profile_image');
				                			$msg_images = json_decode($msg_profile_image, true);
							                if (file_exists('uploads/profile_image/'.$msg_images[0]['thumb'])) {
							                ?>
							                    <img src="<?=base_url()?>uploads/profile_image/<?=$msg_images[0]['thumb']?>" class="dropdown-image rounded-circle">
							                <?php
							                }
							                else {
							                ?>
							                    <img src="<?=base_url()?>uploads/profile_image/default_image.png" class="dropdown-image rounded-circle">
							                <?php
							                }
							            ?></div></div>
					
            		<div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
						<a href="<?=base_url()?>home/member_profile/<?= $messaging_member_info->member_id ?>" ><?= $this->Crud_model->get_type_name_by_id('member', $messaging_member_info->member_id, 'first_name'); ?></a>
						 has sent a	<a href="<?=base_url()?>home/profile/nav/messaging/<?=$messaging_member['message_thread_id']?>"> message </a>
						<!---a href="" class="rIcon"><i class="fa fa-dot-circle-o"></i></a-->
						
					 
						<p class="time"><i class="c-base-1 fa fa-clock-o"></i> <?=date('d M,y - h:i A', $messaging_member['message_thread_time'])?></p>
						
            		</div>
					</div>
            	</li>
				<?php
	        	}
	        }
	        }
               

        if (count($listed_messaging_members) <= 0) {
		?>
    		<div class="text-center">
    			<small class="sml_txt">
        			<?php echo translate('no_messages_to_show')?>
        		</small>
        	</div>
		<?php
		}
    ?>
            	
				
            </div>
			
		  <!-- notify content -->
		  
            <div class="notify-drop-footer text-center">
            	<a href="<?=base_url()?>home/profile/notifications-list">View All <i class="ion-ios-arrow-thin-right"></i> </a>
            </div>
          </ul>




<div class="dropdown-menu dropdown-menu-right dropdown-scale" style="max-height: 300px;overflow: auto;">
    <h6 class="dropdown-header"><?php echo translate('notifications')?></h6>
    <?php 
		foreach ($notification as $row) {
            if($this->db->get_where("member", array("member_id" => $row['by']))->row()->is_closed == 'no'){
                if ($this->db->get_where('member', array('member_id' => $row['by']))->row()->member_id){
                    if ($row['is_seen'] == 'no') {
                        $noti_counter++;
                    }
                    if($row['type'] == 'interest_expressed') {
                        $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
                        <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.07) !important; margin: 0 5%">
                            
                        </div>
                        <span class="dropdown-item" id="noti_item">
                            <small class="pull-right" style="margin-top: -2px;"><i class="c-base-1 fa fa-clock-o"></i> <?=date('d M,y - h:i A', $row['time'])?></small>
                            <small class="sml_txt">
                                <?php
                                    if (file_exists('uploads/profile_image/'.$noti_images[0]['thumb'])) {
                                    ?>
                                        <img src="<?=base_url()?>uploads/profile_image/<?=$noti_images[0]['thumb']?>" class="dropdown-image rounded-circle">
                                    <?php
                                    }
                                    else {
                                    ?>
                                        <img src="<?=base_url()?>uploads/profile_image/default_image.png" class="dropdown-image rounded-circle">
                                    <?php
                                    }
                                ?>
                                <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                    <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                </a> 
                                <?php echo translate('has_expressed_an_interest_on_you')?>
                            </small>
                            <?php 
                                if($row['status'] == 'pending') {
                                ?>
                                    <div class="text-center pt-1 text_<?=$row['by']?>">
                                        <button type="button" class="btn btn-sm btn-primary pt-0 pb-0" id="accept_<?=$row['by']?>" onclick="confirm_accept(<?=$row['by']?>)"><?php echo translate('accept')?></button>
                                        <button type="button" class="btn btn-sm btn-danger pt-0 pb-0" id="reject_<?=$row['by']?>" onclick="confirm_reject(<?=$row['by']?>)"><?php echo translate('reject')?></button>
                                    </div>
                                <?php
                                } else if($row['status'] == 'accepted') {
                                ?>
                                    <div class="text-center text-success text_<?=$row['by']?>">
                                        <small class="sml_txt">
                                            <i class="fa fa-check-circle"></i><?php echo translate('you_have_accepted_the_interest')?>
                                        </small>
                                    </div>
                                <?php
                                } else if($row['status'] == 'rejected') {
                                ?>
                                    <div class="text-center text-danger text_<?=$row['by']?>">
                                        <small class="sml_txt">
                                            <i class="fa fa-times-circle"></i><?php echo translate('you_have_rejected_the_interest')?>
                                        </small>
                                    </div>
                                <?php
                                }
                            ?>
                        </span>
                        <div style="border-top: 1px solid rgba(0, 0, 0, 0.07) !important; margin: 0 5%"></div>
                        <?php
                    }
                    elseif ($row['type'] == 'accepted_interest') {
                        //$noti_counter++;
                        $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
                        <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.07) !important; margin: 0 5%"></div>
                        <span class="dropdown-item" id="noti_item">
                            <small class="pull-right" style="margin-top: -2px;"><i class="c-base-1 fa fa-clock-o"></i> <?=date('d M,y - h:i A', $row['time'])?></small>
                            <small class="sml_txt">
                                <?php
                                    if (file_exists('uploads/profile_image/'.$noti_images[0]['thumb'])) {
                                    ?>
                                        <img src="<?=base_url()?>uploads/profile_image/<?=$noti_images[0]['thumb']?>" class="dropdown-image rounded-circle">
                                    <?php
                                    }
                                    else {
                                    ?>
                                        <img src="<?=base_url()?>uploads/profile_image/default_image.png" class="dropdown-image rounded-circle">
                                    <?php
                                    }
                                ?>
                                <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                    <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                </a> 
                                <span class="text-success"><i class="fa fa-check-circle"></i><?php echo translate('accepted_your_interest')?></span>
                            </small>
                        </span>
                        <?php
                    }
                    elseif ($row['type'] == 'rejected_interest') {
                        //$noti_counter++;
                        $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
                        <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.07) !important; margin: 0 5%"></div>
                        <span class="dropdown-item" id="noti_item">
                            <small class="pull-right" style="margin-top: -2px;"><i class="c-base-1 fa fa-clock-o"></i> <?=date('d M,y - h:i A', $row['time'])?></small>
                            <small class="sml_txt">
                                <?php
                                    if (file_exists('uploads/profile_image/'.$noti_images[0]['thumb'])) {
                                    ?>
                                        <img src="<?=base_url()?>uploads/profile_image/<?=$noti_images[0]['thumb']?>" class="dropdown-image rounded-circle">
                                    <?php
                                    }
                                    else {
                                    ?>
                                        <img src="<?=base_url()?>uploads/profile_image/default_image.png" class="dropdown-image rounded-circle">
                                    <?php
                                    }
                                ?>
                                <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                    <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                </a> 
                                <span class="text-danger"><i class="fa fa-times-circle"></i><?php echo translate('rejected_your_interest')?></span>
                            </small>
                        </span>
                        <?php
                    }
                }
            }
		}
		if (count($notification) <= 0) {
		?>
    		<div class="text-center">
    			<small class="sml_txt">
        			<?php echo translate('no_notification_to_show'); ?>
        		</small>
        	</div>
		<?php
		}
	?>

</div>