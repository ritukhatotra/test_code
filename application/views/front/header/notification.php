<sup class="badge bg-base-1 noti_badge noti_counter" style="display: none;"> <!-- Counts Notification with JavaScript  --> </sup>



<ul class="dropdown-menu notify-drop">
            <div class="notify-drop-title">
            	<div class="row">
            		<div class="col-md-6 col-sm-6 col-xs-6"><?php echo translate('notifications')?></div>
            		<div class="col-md-6 col-sm-6 col-xs-6 text-right"><a href="" class="rIcon allRead" data-tooltip="tooltip" data-placement="bottom" title=""><i class="fa fa-dot-circle-o"></i></a></div>
            	</div>
            </div>
            <!-- end notify title -->
			
            <!-- notify content -->
            <div class="drop-content">			
		
		<?php	foreach ($notification as $row) {
            if($row['type'] != "membership_expired"){ 
                if($this->db->get_where("member", array("member_id" => $row['by']))->row()->is_closed == 'no'){
                    if ($this->db->get_where('member', array('member_id' => $row['by']))->row()->member_id){
                        if ($row['is_seen'] == 'no') {
                        $noti_counter++;
                        }

                        if($row['type'] == 'interest_expressed') {
                            $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                            $noti_images = json_decode($noti_profile_image, true);
                        ?>
		
            	            <li>			
				                <div class="row">
            		                <div class="col-md-3 col-sm-3 col-xs-3">
					                    <div class="notify-img">
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
                                        </div>
                                    </div>					
            		                <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                        <a href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                            <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                        </a> 
                                        <?php echo translate('has_expressed_an_interest_on_you')?>
                                        <p class="time"><i class="c-base-1 fa fa-clock-o"></i>
                                        <?php
                                        $date = new DateTime( date('Y-m-d H:i:s', $row['time']), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone( $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') ? $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') : 'UTC' ));
echo $date->format('d M,y - h:i A') ;?>
                                        
                                        <?php /*=date('d M,y - h:i A', $row['time'])*/?></p>
                                   
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
                                        } ?>
					                </div>
                                     </div>
            	                </li>
				        <?php
	        	        } elseif ($row['type'] == 'accepted_interest') {
                        //$noti_counter++;
                            $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                            $noti_images = json_decode($noti_profile_image, true);
                        ?>
                        <li>
                            <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="notify-img">

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
							            ?></div></div>
					
            		    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                                <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                    <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                </a> 
                                <?php echo translate('accepted_your_interest')?>						
					 
						<p class="time"><i class="c-base-1 fa fa-clock-o"></i>
						<?php
                                        $date = new DateTime( date('Y-m-d H:i:s', $row['time']), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone( $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') ? $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') : 'UTC'));
echo $date->format('d M,y - h:i A') ;?>
                                        
                                        <?php /*=date('d M,y - h:i A', $row['time'])*/?>
                                        </p>
						
            		</div>
                    </li>
                <?php }elseif ($row['type'] == 'rejected_interest') {
                        //$noti_counter++;
                        $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
                            <li>
                                <div class="row">
                                                <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="notify-img">

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
                                                                    ?></div></div>
                                                
                                                <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                            <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                                                <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                                            </a> 
                                                            <?php echo translate('rejected_your_interest')?>						
                                                
                                                    <p class="time"><i class="c-base-1 fa fa-clock-o"></i> <?php
                                        $date = new DateTime( date('Y-m-d H:i:s', $row['time']), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone( $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') ? $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') : 'UTC'));
echo $date->format('d M,y - h:i A') ;?>
                                        
                                        <?php /*=date('d M,y - h:i A', $row['time'])*/?></p>
                                                    
                                                </div>
                            </li>
                    <?php } } 
                            }             
         }else{
             $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
             <li>
                                <div class="row">
                                                <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="notify-img">

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
                                                                    ?></div></div>
                                                
                                                <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                            
                                                            <?php echo translate('your_subscription_has_been_expired')?>
                                                            <a class="c-base-1" href="<?=base_url()?>home/plans">
                                                                <?php echo translate('please_upgrade_your_plan')?>
                                                            </a> 						
                                                
                                                    <p class="time"><i class="c-base-1 fa fa-clock-o"></i> <?php
                                        $date = new DateTime( date('Y-m-d H:i:s', $row['time']), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone( $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') ? $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone') : 'UTC'));
echo $date->format('d M,y - h:i A') ;?>
                                        
                                        <?php /*=date('d M,y - h:i A', $row['time'])*/?></p>
                                                    
                                                </div>
                            </li>
         <?php }
         } ?>
           
            	
<?php if (count($notification) <= 0) {
		?>
    		<div class="text-center">
    			<small class="sml_txt">
        			<?php echo translate('no_notification_to_show')?>
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


