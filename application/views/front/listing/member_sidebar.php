<?php if($this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->row()->is_closed == 'yes')
    { echo " "; } 
else { ?>
    <li class="listing-hover">
        <?php
        $interests = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'interest');
        $interest = json_decode($interests, true);
        $write_message = false;
        if (!empty($this->session->userdata('member_id'))) {

            if (in_assoc_array($member->member_id, 'id', $interest)) {
                $interest_onclick = 0;
                $status = "";
                foreach($interest as $in) {
                    if($in['id'] == $member->member_id) {
                        $status = $in['status'];
                    }
                }

                if ($status == 'accepted') {
                    $write_message = true;
                    $interest_text = translate('interest_accepted');
                    $interest_class = "interest_accepted";    
                }else{
                    $interest_text = translate('interest_expressed');
                    $interest_class = "interest_expressed";
                    $interest_style = "";
                }
            } else {
                $interests_by = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'interested_by');
                $interests_by = json_decode($interests_by, true);

                if (in_assoc_array($member->member_id, 'id', $interests_by)) {

                    $interest_onclick = 0;
                    $status = "";
                    foreach($interests_by as $in) {
                        if($in['id'] == $member->member_id) {
                            $status = $in['status'];
                        }
                    }
                    if ($status == 'accepted') {
                        $write_message = true;
                        $interest_text = translate('accepted');
                        $interest_class = "you_interest_accepted";    
                    }elseif ($status == 'rejected'){
                        $interest_text = translate('rejected');
                        $interest_class = "you_interest_rejected";
                        $interest_style = "";
                    }else{
                        $interest_text = translate('pending');
                        $interest_class = "you_interest_pending";
                        $interest_style = "";
                    }
                } else {
                    $interest_onclick = 1;
                    $interest_text = translate('express_interest');
                    $interest_class = "";
                    $interest_style = "";
                }   
            }
        } else {
            $interest_onclick = 1;
            $interest_text = translate('express_interest');
            $interest_class = "";
            $interest_style = "";
        }
        ?>
        <?php if($interest_text == translate('pending')) {?>
            <button type="button" class="btn btn-sm btn-primary pt-0 pb-0" id="mobile_listing_accept_<?=$member->member_id?>" onclick="confirm_accept(<?=$member->member_id?>)"><?php echo translate('accept')?></button>
            <button type="button" class="btn btn-sm btn-danger pt-0 pb-0" id="mobile_listing_reject_<?=$member->member_id?>" onclick="confirm_reject(<?=$member->member_id?>)"><?php echo translate('reject')?></button>
        <?php } elseif($interest_text == translate('accepted')) { ?>
            <i class="fa fa-check-circle"></i><?php echo translate('you_have_accepted_the_interest')?>
        <?php }elseif($interest_text == translate('rejected')) { ?>
            <i class="fa fa-check-circle"></i><?php echo translate('you_have_rejected_the_interest')?>
        <?php }else{ ?>
            <a id="interest_a_<?=$member->member_id?>" <?php if ($interest_onclick == 1){?>onclick="return confirm_interest(<?=$member->member_id?>)"<?php }?> style="<?=$interest_style?>" class="<?php echo $interest_class ?> interest_a_<?=$member->member_id?>">
            <span id="interest_<?=$member->member_id?>">
            <i class="ion-ios-heart"></i> 
            </span>
            <span><?=$interest_text?></span>
            </a>
        <?php } ?>
    </li>                            

    <li class="listing-hover">
        <?php
        $if_message = $this->db->get_where('message_thread', array('message_thread_from' => $member->member_id, 'message_thread_to' => $this->session->userdata('member_id')))->row();
        if (!$if_message) {
            $if_message = $this->db->get_where('message_thread', array('message_thread_from' => $this->session->userdata('member_id'), 'message_thread_to' => $member->member_id))->row();
        }

        if ($if_message) {
            $message_onclick = 0;
            $message_text = translate('write_message');
            $message_class = "";//"btn btn-styled btn-block btn-sm btn-white z-depth-2-bottom li_active";
        } else {
            $message_onclick = 1;
            $message_text = translate('write_message');
            $message_class = "";//"btn btn-styled btn-block btn-sm btn-white z-depth-2-bottom";
        }
        ?>

        <a <?php if ($message_onclick == 1 && $write_message == true){?>onclick="return confirm_message(<?=$member->member_id?>)"<?php }?> href="<?php if ($message_onclick == 0 && $write_message == true) { echo base_url().'home/profile/messaging-list'; }else{ echo 'javascript:void(0);'; } ?>" id="mobile_listing_message_a_<?=$member->member_id?>"
        class="<?php if ($message_onclick == 0 || $write_message == false) { echo 'disabled-messaging'; } ?>">

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
        <a onclick="return view_contact(<?=$member->member_id?>)">
            <i class="ion-android-call"></i><span>View Contact</span>
        </a>
    </li>
<?php } ?>