<div class="card-title pricing-page-title">
    <h3 class="heading heading-6 strong-500">
        <b><?php echo translate('messaging')?></b>
    </h3>
</div>
<!---TEmp css -->
<style> 
.sidebar-outer{display:none;}
.footer{display:none;}	
@media(max-width:767px)
{
.mobile-bottom-links{display:none;}	
#profile_load{background:none;box-shadow:none;}
}

</style>
<!---TEmp css -->
<div class="card-body">
    <div class="row">
        <?php if($this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->row()->is_closed == 'yes'){?>
            <div class="col-md-12">
                <p class="c-base-1 pt-4 text-center">"<?php echo translate('your_account_is_closed!_please_re-open_the_account_to_enable_messaging!')?>"
                </p>
                <div class="text-center pt-2 pb-4">
                    <a onclick="profile_load('reopen_account')" href="#" class="btn btn-styled btn-sm btn-base-1 z-depth-2-bottom"><?php echo translate('re-open_account')?></a>
                </div>
            </div>
        <?php }else{?>
		
		
		
		 <div class="col-md-4 mb-2 messaging-member-list-outer-desktop">
		    <div id="messaging_member_list">
                <?php include 'member_list.php'; ?>
            </div>
			
		   </div>
		
        <div class="col-md-8 col-sm-12 mb-2 message-box-outer">
		
		
		<div id="messaging_member_list" class="member-list-mobile">
                <?php include 'member_list_mobile.php'; ?>
            </div>
			
		
            <!-- DIRECT CHAT -->
            <div id="profile_message_box">
                <?php include 'message_box.php'; ?>
            </div>
            <!--/.direct-chat -->
        </div>
        <!-- /.col -->
		
		
       
    <?php } ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.top_bar_right').load('<?php echo base_url(); ?>home/top_bar_right');
        window.addEventListener("keydown", checkKeyPressed, false);
        function checkKeyPressed(e) {
            if (e.keyCode == "13") {
                $(":focus").each(function() {
                    event.preventDefault();
                    $(this).closest('form').find('.enterer').click();
                });
            }
        }

        $("#message_text").keyup(function(){
            if ($("#message_text").val().length != 0) {
                $("#msg_send_btn").removeAttr('disabled');
            }
            else {
                $("#msg_send_btn").attr('disabled', 'disabled');   
            }
        });
    });
</script>