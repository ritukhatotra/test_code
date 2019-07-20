  <table class="table table-sm table-striped table-hover table-responsive" id="notification-table">
        <thead>
        <tr>
            <th>
                <?php echo translate('message') ?>
            </th>
            <th>
                <?php echo translate('actions') ?>
            </th>
        </tr>
        </thead>
        <tbody>
       <?php 
		foreach ($notifications as $row) {
		   if($this->db->get_where("member", array("member_id" => $row['by']))) {
            if($this->db->get_where("member", array("member_id" => $row['by']))->row()->is_closed == 'no'){
                if (!empty($this->db->get_where('member', array('member_id' => $row['by']))->row()->member_id)){
                    if ($row['is_seen'] == 'no') {
                        $noti_counter++;
                    }?>
                    <tr>
                     <?php if($row['type'] == 'interest_expressed') {
                        $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
                        <td>
<a href="<?=base_url()?>home/member_profile/<?=$row['by']?>">
                            <?php
                                if (file_exists('uploads/profile_image/'.$image[0]['thumb'])) {
                                ?>
                                <img src="<?=base_url()?>uploads/profile_image/<?=$noti_images[0]['thumb']?>" alt="" style="height: 50px">
                                <?php
                                }
                                else {
                                ?>
                                <img src="<?=base_url()?>uploads/profile_image/default_image.png" alt="" style="height: 50px">
                            <?php
                            }
                            ?></a>
                        
                            <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                    <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                </a> 
                                <?php echo translate('has_expressed_an_interest')?>
                        <p>
                            	<?php
                                        $date = new DateTime( date('Y-m-d H:i:s', $row['time']), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone( $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone')));
echo $date->format('d M,y - h:i A') ;?>
                                        </p>
                                        <?php /*=date('d M,y - h:i A', $row['time'])*/?>
                        </td>
                        <td>
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
                            </td>
                        <?php
                    }
                    elseif ($row['type'] == 'accepted_interest') {
                        //$noti_counter++;
                        $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
                        <td>
                            <a href="<?=base_url()?>home/member_profile/<?=$row['by']?>">
                            <?php
                                if (file_exists('uploads/profile_image/'.$image[0]['thumb'])) {
                                ?>
                                <img src="<?=base_url()?>uploads/profile_image/<?=$noti_images[0]['thumb']?>" alt="" style="height: 50px">
                                <?php
                                }
                                else {
                                ?>
                                <img src="<?=base_url()?>uploads/profile_image/default_image.png" alt="" style="height: 50px">
                            <?php
                            }
                            ?></a>
                       
                        <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                    <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                </a> 
                                <span class="text-success"><i class="fa fa-check-circle"></i><?php echo translate('accepted_your_interest')?></span>
                        <p>
                      	<?php
                                        $date = new DateTime( date('Y-m-d H:i:s', $row['time']), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone( $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone')));
echo $date->format('d M,y - h:i A') ;?></p>
                                        
                                        <?php /*=date('d M,y - h:i A', $row['time'])*/?>
                        </td>
                        <td></td>
                        <?php
                    }
                    elseif ($row['type'] == 'rejected_interest') {
                        //$noti_counter++;
                        $noti_profile_image = $this->Crud_model->get_type_name_by_id('member', $row['by'], 'profile_image');
                        $noti_images = json_decode($noti_profile_image, true);
                        ?>
                        <td>
                            <a href="<?=base_url()?>home/member_profile/<?=$row['by']?>">
                            <?php
                                if (file_exists('uploads/profile_image/'.$image[0]['thumb'])) {
                                ?>
                                <img src="<?=base_url()?>uploads/profile_image/<?=$noti_images[0]['thumb']?>" alt="" style="height: 50px">
                                <?php
                                }
                                else {
                                ?>
                                <img src="<?=base_url()?>uploads/profile_image/default_image.png" alt="" style="height: 50px">
                            <?php
                            }
                            ?></a>
                       
                        <a class="c-base-1" href="<?=base_url()?>home/member_profile/<?= $row['by']; ?>">
                                    <?= $this->Crud_model->get_type_name_by_id('member', $row['by'], 'first_name'); ?>
                                </a> 
                                <span><?php echo translate('rejected_your_interest')?></span>
                              <p>
                       	<?php
                                        $date = new DateTime( date('Y-m-d H:i:s', $row['time']), new DateTimeZone('UTC'));
                                        $date->setTimezone(new DateTimeZone( $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'timezone')));
echo $date->format('d M,y - h:i A') ;?>
                                        </p>
                                        <?php /*=date('d M,y - h:i A', $row['time'])*/?>
                        </td>
                        <td></td>
                        <?php
                    }?>
                </tr>
                <?php } }
            }
		    }
		    
		
		if (count($notifications) <= 0) {
		?>
    		<div class="text-center">
    			<small class="sml_txt">
        			<?php echo translate('no_notification_to_show'); ?>
        		</small>
        	</div>
		<?php
		}
	?>
        </tbody>
    </table>
    
    <script>
          $('#notification-table').dataTable({
            "paging": true,
            'searching':false,
            'lengthChange':false,
            'ordering':false,
            'info': false,
            "fnDrawCallback": function(oSettings) {
        if ($('#notification-table tr').length < 11) {
            $('.dataTables_paginate').hide();
        }
    }
        });
    </script>