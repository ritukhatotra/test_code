<?php
    $home_slider_image = $this->db->get_where('frontend_settings', array('type' => 'home_slider_image'))->row()->value;
    $home_searching_heading = $this->db->get_where('frontend_settings', array('type' => 'home_searching_heading'))->row()->value;

    $slider_image = json_decode($home_slider_image, true);
    
    // for slider dynamic margin
    $found = 0;
    if ($this->db->get_where('frontend_settings', array('type' => 'spiritual_and_social_background'))->row()->value != "yes") { $found++; }
    if ($this->db->get_where('frontend_settings', array('type' => 'language'))->row()->value != "yes") { $found++; };
?>
     <?php foreach ($slider_image as $image): ?>
                       
                    <?php endforeach ?>
<div class="hero" style="background-size: cover; background-position: center; background-image: url(<?=base_url()?>uploads/home_page/slider_image/<?=$image['img']?>); background-position: bottom bottom;">
 	
	<div class="container">
	<div class="outer-search">
                <h1 class="text-white text-center mb-4">
                    <span style="text-shadow: 4px 3px 6px #000;"><?=$home_searching_heading?></span>
                </h1>
                <div class="feature feature--boxed-border feature--bg-1 z-depth-2-bottom px-3 py-4 animated animation-ended s-search" data-animation-in="zoomIn" data-animation-delay="400" style="background: #1b1e23b3;">
                    <form class="mt-4" data-toggle="validator" role="form" action="<?=base_url()?>home/listing/home_search" method="POST" style="margin-top: 0px !important;">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-6 col-6 ml-auto">
                                <div class="form-group has-feedback">
                                    <label class="text-uppercase text-white"><?php echo translate("i'm_looking_for_a")?></label>
                                    <?= $this->Crud_model->select_html('gender', 'gender', 'name', 'edit', 'form-control form-control-sm selectpicker', '', '', '', ''); ?>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-3 pr-0">
                                <div class="form-group has-feedback">
                                    <label for="" class="text-uppercase text-white"><?php echo translate('aged_from')?></label>
                                  <select class="form-control form-control-sm selectpicker" name="aged_from" id="aged_from" >
                            <?php for($i = 18; $i<=70; $i++) {?>
                              <option value="<?= $i ?>" <?php echo $i == 18 ? 'selected' : '' ?>><?= $i ?></option>
                            <?php } ?>
                                   </select>
                                  
                                    <div class="help-block with-errors">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-3">
                                <div class="form-group has-feedback">
                                    <label for="" class="text-uppercase text-white"><?php echo translate('to')?></label>
                                      <select class="form-control form-control-sm selectpicker" name="aged_to" id="aged_to" >
                            
                            <?php for($i = 18; $i<=70; $i++) {?>
                              <option value="<?= $i ?>" <?php echo $i == 28 ? 'selected' : '' ?>><?= $i ?></option>
                            <?php } ?>
                                 </select>
                                </div>
                                <div class="help-block with-errors">
                                </div>
                            </div>
                            <?php
                                if ($this->db->get_where('frontend_settings', array('type' => 'spiritual_and_social_background'))->row()->value == "yes") {
                            ?>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-6">
                                <div class="form-group has-feedback">
                                    <label for="" class="text-uppercase text-white"><?php echo translate('religion')?></label>
                                    <?= $this->Crud_model->select_html('religion', 'religion', 'name', 'edit', 'form-control form-control-sm selectpicker s_religion', '', '', '', ''); ?>
                                    <div class="help-block with-errors">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-6">
                                <div class="form-group has-feedback">
                                    <label for="" class="text-uppercase text-white"><?php echo translate('caste_/_sect')?></label>
                                    <select class="form-control form-control-sm selectpicker s_caste" name="caste">
                                        <option value=""><?php echo translate('choose_a_religion_first')?></option>
                                    </select>
                                    <div class="help-block with-errors">
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                                if ($this->db->get_where('frontend_settings', array('type' => 'language'))->row()->value == "yes") {
                            ?>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-6">
                                <div class="form-group has-feedback">
                                    <label for="" class="text-uppercase text-white"><?php echo translate('mother_tongue')?></label>
                                    <?= $this->Crud_model->select_html('language', 'language', 'name', 'edit', 'form-control form-control-sm selectpicker', '', '', '', ''); ?>
                                    <div class="help-block with-errors">
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-12 mr-auto">
                                <button type="submit" class="btn btn-styled btn-sm btn-block btn-base-1 btn-search"><?php echo translate('search')?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
	
	
	   
	
	
        <!---div class="swiper-js-container background-image-holder">
            <div class="swiper-container" data-swiper-autoplay="true" data-swiper-effect="coverflow" data-swiper-items="1" data-swiper-space-between="0">
                <div class="swiper-wrapper">
                   
                    <?php //foreach ($slider_image as $image): ?>
                        <div class="swiper-slide" data-swiper-autoplay="10000">
                            <div class="slice px-3 holder-item holder-item-light has-bg-cover bg-size-cover same-height" data-same-height="#div_properties_search" style="height: 650px;">
                            </div>
                        </div>
                    <?php //endforeach ?>
               
                
                </div>
            </div>
        </div-->
       
    </div>    
</div>