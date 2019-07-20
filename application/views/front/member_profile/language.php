<div id="info_language">
    <div class="card-inner-title-wrapper pt-0">
        <h3 class="card-inner-title pull-left">
            <?php echo translate('language')?>
        </h3>
    </div>
	
	
	<div class="clearfix"></div><hr>
	
	<div class="row list-box-columns">
	    
		<div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('mother_tongue')?>:</b></div>
					<div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('language', $language_data[0]['mother_tongue']);?></div>								  
					</div>
					
					<div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('language')?>:</b></div>
					<div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('language', $language_data[0]['language']);?></div>								  
					</div>
					

		     </div>
	
	  
	  <div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('speak')?>:</b></div>
					<div class="col-6 p-0"> <?=$this->Crud_model->get_type_name_by_id('language', $language_data[0]['speak']);?></div>								  
					</div>
					
					 <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('read')?>:</b></div>
					<div class="col-6 p-0"> <?=$this->Crud_model->get_type_name_by_id('language', $language_data[0]['read']);?></div>								  
					</div>

		     </div>
	
	    </div><!----- Main-Row End--->   
</div>