<?php 
    $education_and_career = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'education_and_career');
    $education_and_career_data = json_decode($education_and_career, true);
?>
<div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
    <div id="info_education_and_career">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('education_and_career')?>
            </h3>
            <div class="pull-right">
                <button type="button" id="unhide_education_and_career" <?php if ($privacy_status_data[0]['education_and_career'] == 'yes') {?> style="display: none" <?php }?> class="btn btn-base-1 btn-sm btn-icon-only btn-shadow mb-1" onclick="unhide_section('education_and_career')">
                <i class="fa fa-unlock"></i> <?=translate('show')?>
                </button>
                <button type="button" id="hide_education_and_career" <?php if ($privacy_status_data[0]['education_and_career'] == 'no') {?> style="display: none" <?php }?> class="btn btn-dark btn-sm btn-icon-only btn-shadow mb-1" onclick="hide_section('education_and_career')">
                <i class="fa fa-lock"></i> <?=translate('hide')?>
                </button>
                <button type="button" class="btn btn-base-1 btn-sm btn-icon-only btn-shadow mb-1" onclick="edit_section('education_and_career')">
                <i class="ion-edit"></i>
                </button>               
            </div>
        </div>
		
		<div class="clearfix"></div><hr>
		
		<div class="row list-box-columns">
	    
		<div class="col-md-6">
		     <div class="row">	
                            <div class="col-6 p-0">
                                <b><?php echo translate('highest_education')?>:</b>
                            </div>
                            <div class="col-6 p-0">

                            <?=$this->Crud_model->get_type_name_by_id('education_level', $education_and_career_data[0]['highest_education'], 'education_level_name')?>

                                </div>
                            </div>
							
				<div class="row">
				    <div class="col-6 p-0">
                                <b><?php echo translate('occupation')?>:</b>
                            </div>
                            <div class="col-6 p-0">
                                <?=$education_and_career_data[0]['occupation']?>
                            </div>
				
				   </div>			
							
		 
		   </div>
		   
		<div class="col-md-6">
		     
			   <div class="row">	
                            <div class="col-6 p-0">
                                <b><?php echo translate('annual_income')?>:</b>
                            </div>
                            <div class="col-6 p-0">
                                <?=$education_and_career_data[0]['annual_income']?>
                            </div>
                           
                        </div>		
				   
		
		    </div>
		
		  </div>
		 
    </div>

    <div id="edit_education_and_career" style="display: none;">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('education_and_career')?>
            </h3>
            <div class="pull-right">
                <button type="button" class="btn btn-success btn-sm btn-icon-only btn-shadow" onclick="save_section('education_and_career')"><i class="ion-checkmark"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-icon-only btn-shadow" onclick="load_section('education_and_career')"><i class="ion-close"></i></button>
            </div>
        </div>
        
        <div class='clearfix'></div>
        <form id="form_education_and_career" class="form-default" role="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="highest_education" class="text-uppercase c-gray-light"><?php echo translate('highest_education')?></label>

 <?php 

echo $this->Crud_model->select_html('education_level', 'highest_education', 'education_level_name', 'edit', 'form-control form-control-sm selectpicker', $education_and_career_data[0]['highest_education'], '', '', '');

?>
<?php /*                        <input type="text" class="form-control no-resize" name="highest_education" value="<?=$education_and_career_data[0]['highest_education']?>">*/?>

                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="occupation" class="text-uppercase c-gray-light"><?php echo translate('occupation')?></label>
                        <input type="text" class="form-control no-resize" name="occupation" value="<?=$education_and_career_data[0]['occupation']?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="annual_income" class="text-uppercase c-gray-light"><?php echo translate('annual_income')?></label>
                        <input type="text" class="form-control no-resize" name="annual_income" value="<?=$education_and_career_data[0]['annual_income']?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>