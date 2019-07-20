<div id="info_education_and_career">
    <div class="card-inner-title-wrapper pt-0">
        <h3 class="card-inner-title pull-left">
            <?php echo translate('education_&_career')?>
        </h3>
    </div>
	
	<div class="clearfix"></div><hr>
	
	<div class="row list-box-columns">
	    
		<div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('highest_education')?>:</b></div>
					<div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('education_level', $education_and_career_data[0]['highest_education'], 'education_level_name')?></div>								  
					</div>
					
					<div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('occupation')?>:</b></div>
					<div class="col-6 p-0"><?=$education_and_career_data[0]['occupation']?></div>								  
					</div>
					

		     </div>
	
	  
	  <div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('annual_income')?>:</b></div>
					<div class="col-6 p-0"><?=$education_and_career_data[0]['annual_income']?></div>								  
					</div>

		     </div>
	
	    </div><!----- Main-Row End--->
</div>