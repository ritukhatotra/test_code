<?php 
if(!empty($this->session->userdata['member_id'])) {
	include_once APPPATH.'views/front/profile_nav.php';
} ?>
<section class="page-title page-title--style-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h2 class="heading heading-3 strong-400 mb-0"><?php echo translate('premium_plans')?></h2>
            </div>
        </div>
    </div>
</section>
<?php 
    $background_image = $this->db->get_where('frontend_settings', array('type' => 'premium_plans_image'))->row()->value;
    $background_image_data = json_decode($background_image, true);
 
?>
<!---section class="slice sct-color-1 pricing-plans pricing-plans--style-1 has-bg-cover bg-size-cover" style="background-image: url(<?=base_url()?>uploads/premium_plans_image/<?=$background_image_data[0]['image']?>); background-position: bottom bottom;"-->
  <section class="slice sct-color-2 pricing-plans pricing-plans--style-1 has-bg-cover bg-size-cover" >
    <div class="container">
        <span class="clearfix"></span>
        <div class="row">
            <?php if (!empty($danger_alert)): ?>
                <div class="col-12" id="danger_alert">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <?=$danger_alert?>
                    </div>
                </div>
            <?php endif ?>
            <?php foreach ($all_plans as $value): ?>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <?php /*if ($value->plan_id == 1) { $package_class = ""; } else { $package_class = "active"; }*/ ?>
                    <?php 
$package_class = "";
                    if(!empty($this->session->userdata('member_id'))) {  
                        $user_membership =  $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'membership');
                        
                        if($user_membership > 1) {
                    $membership =  $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'package_info');
                    $package = json_decode($membership, true);
                    
                    if(isset($package[0]['plan_id'])) {
                        $membership = $package[0]['plan_id'];
                    }
                   }else{
                       $membership = $user_membership;
                   }
                    if( $membership == $value->plan_id) {
                         $package_class = "active"; 
                         $valid_till = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'membership_valid_till');                      
                    }
                   }
                     ?>

                    <div class="feature feature--boxed-border feature--bg-2 active package_bg mt-4">
                        <div class="icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-dark">
                                <li style="list-style-type: none;">
                                <?php
                                    $image = $value->image;
                                    $images = json_decode($image, true);
                                    if (file_exists('uploads/plan_image/'.$images[0]['thumb'])) {
                                    ?>
                                        <img src="<?=base_url()?>uploads/plan_image/<?=$images[0]['thumb']?>" class="img-sm" height="100">
                                    <?php
                                    }
                                    else {
                                    ?>
                                        <img src="<?=base_url()?>uploads/plan_image/default_image.png" class="img-sm" height="100">
                                    <?php
                                    }
                                ?>
                                </li>
                            </div>
                            <div class="block-content">
                                <h3 class="heading heading-5 strong-500"><?=$value->name?></h3>
                                <h3 class="price-tag" style="font-size: 26px;">
                                    <?php $exchange =  $this->db->get_where('currency_settings', array('code' => 'INR'))->row()->exchange_rate_def;;
          
            $amount= $value->amount * $exchange;?>
                                    <?php /*<?= currency($value->amount); */?> ₹<?= $amount ?></h3>
                                <ul class="pl-0 pr-0 mt-0">
                                    <!-- <li class="package_items"><?php if($value->plan_id == 1){echo "Limited Profile Searching";}else{echo "Advanced Profile Searching";}?></li> -->
                                   <?php /* <li class="<?=$package_class?> package_items"><?=translate('express_interests:')?> <?=$value->express_interest?> <?=translate('times')?></li>
                                    <li class="<?=$package_class?> package_items"><?=translate('direct_messages:')?> <?=$value->direct_messages?> <?=translate('times')?></li>*/?>
                                    <li class="<?=$package_class?> package_items"><?=translate('photo_gallery:')?> <?=$value->photo_gallery?> <?=translate('images')?></li>
                                   <?php if($value->plan_id != 1) { ?>
                                     <li class="<?=$package_class?> package_items"><?=translate('direct_messages:')?> unlimited </li>
                                     <li class="<?=$package_class?> package_items"><?=translate('duration:')?> <?=$value->duration?> <?=translate('months')?></li>
                                     <?php }else{ ?>
                                       <li class="<?=$package_class?> package_items"><?=translate('no_direct_messages')?></li>
                                       <li class="<?=$package_class?> package_items"><?=translate('duration:')?> Unlimited </li>
                                     <?php } ?>
                                </ul>
                                <div class="py-2 text-center mb-2">
                                    <?php 
                                    if ($value->plan_id != 1) {
                                        $purchase_link = base_url()."home/plans/subscribe/".$value->plan_id;
                                    }
                                    else {
                                        $purchase_link = "#";
                                    }
                                    
                                    if($value->plan_id == 1) {?>
                                     <a href="#" class="btn btn-styled btn-sm"><i class="ion-checkmark-round"></i> <span><?php echo translate('default_package') ?></span>
                                    </a>
                                  <?php  }elseif($package_class == 'active') {
                                    ?>                                
                                    <a href="#" class="btn btn-styled btn-sm btn-base-1 btn-outline btn-circle">
                                        <span class="<?=$package_class?>"><?php echo translate('current_package')?></span>
                                    </a>
                                    <?php }elseif($user_membership < $value->plan_id){ ?>                                    
                                    <a href="<?=$purchase_link?>" class="btn btn-styled btn-sm btn-base-1 btn-outline btn-circle">
                                        <span class="<?=$package_class?>"><?php echo translate('get_this_package')?></span>
                                    </a>
                                    <?php }else{?>
                                     <a href="javascript:void(0)"  class="disabled btn btn-styled btn-sm btn-base-1 btn-outline btn-circle">
                                        <span class=""><?php echo translate('get_this_package')?></span>
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</section>
<script>
    setTimeout(function() {
        $('#danger_alert').fadeOut('fast');
    }, 5000); // <-- time in milliseconds
</script>