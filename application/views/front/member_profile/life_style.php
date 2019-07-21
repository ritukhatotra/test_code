<div id="info_life_style">
    <div class="card-inner-title-wrapper pt-0">
        <h3 class="card-inner-title pull-left">
            <?php echo translate('life_style');?>
        </h3>
    </div>
	
	
	
	<div class="clearfix"></div><hr>
	
	<div class="row list-box-columns">
	    
		<div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('diet');?>:</b></div>
					<div class="col-6 p-0"><?=$life_style_data[0]['diet']?></div>								  
					</div>
					
					<div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('drink');?>:</b></div>
					<div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('decision', $life_style_data[0]['drink'])?></div>								  
					</div>
					

		     </div>
	
	  
	  <div class="col-md-6">
		     <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('smoke');?>:</b></div>
					<div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('decision', $life_style_data[0]['smoke'])?></div>								  
					</div>
					
					 <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('living_with');?>:</b></div>
					<div class="col-6 p-0"><?=$life_style_data[0]['living_with']?></div>								  
					</div>

		     </div>
	
	    </div><!----- Main-Row End---> 
	

</div>