<?php 
    $astronomic_information = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'astronomic_information');
    $astronomic_information_data = json_decode($astronomic_information, true);
?>
<div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
    <div id="info_astronomic_information">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('astronomic_information')?>
            </h3>
            <div class="pull-right">
                <button type="button" id="unhide_astronomic_information" <?php if ($privacy_status_data[0]['astronomic_information'] == 'yes') {?> style="display: none" <?php }?> class="btn btn-base-1 btn-sm btn-icon-only btn-shadow mb-1" onclick="unhide_section('astronomic_information')">
                <i class="fa fa-unlock"></i> <?=translate('show')?>
                </button>
                <button type="button" id="hide_astronomic_information" <?php if ($privacy_status_data[0]['astronomic_information'] == 'no') {?> style="display: none" <?php }?> class="btn btn-dark btn-sm btn-icon-only btn-shadow mb-1" onclick="hide_section('astronomic_information')">
                <i class="fa fa-lock"></i> <?=translate('hide')?>
                </button>
                <button type="button" class="btn btn-base-1 btn-sm btn-icon-only btn-shadow mb-1" onclick="edit_section('astronomic_information')">
                <i class="ion-edit"></i>
                </button>  
            </div>
        </div>
		
		<div class="clearfix"></div><hr>
		
		<div class="row list-box-columns">
	    
		<div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('sun_sign')?>:</b></div>
					<div class="col-6 p-0"><?=$astronomic_information_data[0]['sun_sign']?></div>								  
					</div>
					
					<div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('moon_sign')?>:</b></div>
					<div class="col-6 p-0"><?=$astronomic_information_data[0]['moon_sign']?></div>								  
					</div>
					

		     </div>
	
	  
	  <div class="col-md-6">
		     <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('time_of_birth')?>:</b></div>
					<div class="col-6 p-0"><?=$astronomic_information_data[0]['time_of_birth']?></div>								  
					</div>
					
					 <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('city_of_birth')?>:</b></div>
					<div class="col-6 p-0">  <?=$astronomic_information_data[0]['city_of_birth']?></div>								  
					</div>

		     </div>
	
	    </div>
		
		
    </div>

    <div id="edit_astronomic_information" style="display: none;">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('astronomic_information')?>
            </h3>
            <div class="pull-right">
                <button type="button" class="btn btn-success btn-sm btn-icon-only btn-shadow" onclick="save_section('astronomic_information')"><i class="ion-checkmark"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-icon-only btn-shadow" onclick="load_section('astronomic_information')"><i class="ion-close"></i></button>
            </div>
        </div>
        
        <div class='clearfix'></div>
        <form id="form_astronomic_information" class="form-default" role="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="sun_sign" class="text-uppercase c-gray-light"><?php echo translate('sun_sign')?></label>
                        <input type="text" class="form-control no-resize" name="sun_sign" value="<?=$astronomic_information_data[0]['sun_sign']?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="moon_sign" class="text-uppercase c-gray-light"><?php echo translate('moon_sign')?></label>
                        <input type="text" class="form-control no-resize" name="moon_sign" value="<?=$astronomic_information_data[0]['moon_sign']?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="time_of_birth" class="text-uppercase c-gray-light"><?php echo translate('time_of_birth')?></label>
                        <input type="text" class="form-control no-resize" name="time_of_birth" value="<?=$astronomic_information_data[0]['time_of_birth']?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="city_of_birth" class="text-uppercase c-gray-light"><?php echo translate('city_of_birth')?></label>
                        <input type="text" class="form-control no-resize" name="city_of_birth" value="<?=$astronomic_information_data[0]['city_of_birth']?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>