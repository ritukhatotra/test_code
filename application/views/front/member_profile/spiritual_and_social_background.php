<div id="info_spiritual_and_social_background">
    <div class="card-inner-title-wrapper pt-0">
        <h3 class="card-inner-title pull-left">
            <?php echo translate('spiritual_and_social_background')?>
        </h3>
    </div>
	
	<div class="clearfix"></div><hr>
	
	<div class="row list-box-columns">
	    
		<div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('religion')?>:</b></div>
					<div class="col-6 p-0"> <?=$this->Crud_model->get_type_name_by_id('religion', $spiritual_and_social_background_data[0]['religion']);?></div>								  
					</div>
					
					 <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('caste_/_sect')?>:</b></div>
					<div class="col-6 p-0"><?php echo $this->db->get_where('caste',array('caste_id'=>$spiritual_and_social_background_data[0]['caste']))->row()->caste_name; ?></div>								  
					</div>
					
					 <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('sub-Caste')?>:</b></div>
					<div class="col-6 p-0"><?php echo $this->db->get_where('sub_caste',array('sub_caste_id'=>$spiritual_and_social_background_data[0]['sub_caste']))->row()->sub_caste_name;?></div>								  
					</div>

					 <div class="row">								  
					   <div class="col-6 p-0"><b><?php echo translate('ethnicity')?>:</b></div>
					   <div class="col-6 p-0"><?=$spiritual_and_social_background_data[0]['ethnicity']?></div>								  
					</div>

					 <div class="row">								  
					   <div class="col-6 p-0"><b><?php echo translate('personal_value')?>:</b></div>
					    <div class="col-6 p-0"> <?=$spiritual_and_social_background_data[0]['personal_value']?></div>
						</div>
					

		     </div>
	
	  
	  <div class="col-md-6">
		       <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('family_value')?>:</b></div>
					<div class="col-6 p-0"><?=$this->Crud_model->get_type_name_by_id('family_value', $spiritual_and_social_background_data[0]['family_value']);?></div>								  
					</div>
					
					  <div class="row">								  
					   <div class="col-6 p-0"><b><?php echo translate('community_value')?>:</b></div>
					    <div class="col-6 p-0"><?=$spiritual_and_social_background_data[0]['community_value']?></div>								  
					   </div>
					
					 <div class="row">								  
					   <div class="col-6 p-0"><b><?php echo translate('family_status')?>:</b></div>
					    <div class="col-6 p-0"> <?=$this->Crud_model->get_type_name_by_id('family_status', $spiritual_and_social_background_data[0]['family_status']);?></div>								  
					</div>
					
					<div class="row">								  
					   <div class="col-6 p-0"><b><?php echo translate('manglik')?>:</b></div>
					    <div class="col-6 p-0"><?php $u_manglik=$spiritual_and_social_background_data[0]['u_manglik'];

                                    if($u_manglik == 1){
                                        echo "Yes";
                                    }elseif($u_manglik == 2){
                                        echo "No";
                                    }
                                    elseif($u_manglik == 3){
                                        echo "I don't know";
                                    }else{
                                        echo " ";
                                    }
                                ?></div>								  
					</div>

		     </div>
	
	    </div><!----- Main-Row End---> 
	
</div>