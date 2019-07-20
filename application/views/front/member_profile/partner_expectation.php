<div id="info_partner_expectation">
    <div class="card-inner-title-wrapper pt-0">
        <h3 class="card-inner-title pull-left">
            <?php echo translate('partner_expectation');?>
        </h3>
    </div>
	
	
	<div class="clearfix"></div><hr>
	
	<div class="row list-box-columns">
	    
		<div class="col-md-6">
		     <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('general_requirement');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$partner_expectation_data[0]['general_requirement']?>
							</div>
						
                        </div>
						
						
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('age');?>:</b>
							</div>
							<div class="col-6 p-0">
							<?=$partner_expectation_data[0]['partner_min_age']?>- <?=$partner_expectation_data[0]['partner_max_age']?>
							</div>
                        </div>
                    
                       <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('height');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$partner_expectation_data[0]['partner_min_height']?>-<?=$partner_expectation_data[0]['partner_max_height']?> Feet
							</div>
                        </div>
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('weight');?>:</b>
							</div>
							<div class="col-6 p-0">
							<?=$partner_expectation_data[0]['partner_min_weight']?>- <?=$partner_expectation_data[0]['partner_max_weight']?>KG
							</div>
                        </div>
                    
                    <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('marital_status');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('marital_status', $partner_expectation_data[0]['partner_marital_status'])?>
							</div>
						
                        </div>
						
						<div class="row">						
							<div class="col-6 p-0">
								<b><?php echo translate('with_children_acceptables');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('decision', $partner_expectation_data[0]['with_children_acceptables'])?>
							</div>
						
                        </div>
                    
                    <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('country_of_residence');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('country', $partner_expectation_data[0]['partner_country_of_residence'])?>
							</div>						
                        </div>
						
					   <div class="row">	
							<div class="col-6 p-0">
								<b><?php echo translate('religion');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('religion', $partner_expectation_data[0]['partner_religion'])?>
							</div>
                        </div>
                    
                    <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('caste_/_sect');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->db->get_where('caste', array('caste_id'=>$partner_expectation_data[0]['partner_caste']))->row()->caste_name;?>
							</div>						
                        </div>
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('sub_caste');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->db->get_where('sub_caste', array('sub_caste_id'=>$partner_expectation_data[0]['partner_sub_caste']))->row()->sub_caste_name;?>
							</div>
                        </div>
                    
                      <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('education');?>:</b>
							</div>
							<div class="col-6 p-0">
							 <?=$this->Crud_model->get_type_name_by_id('education_level', $partner_expectation_data[0]['partner_education'], 'education_level_name')?>
							</div>
                        </div>
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('profession');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$partner_expectation_data[0]['partner_profession']?>
							</div>
                        </div>
		      
					

		     </div>
	
	  
	  <div class="col-md-6">
		      
			  <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('drinking_habits');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('decision', $partner_expectation_data[0]['partner_drinking_habits'])?>
							</div>
                        </div>
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('smoking_habits');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('decision', $partner_expectation_data[0]['partner_smoking_habits'])?>
							</div>
                        </div>
                    
                    <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('diet');?>:</b>
							</div>
							<div class="col-6 p-0">
							    <?=$this->Crud_model->get_type_name_by_id('diet', $partner_expectation_data[0]['partner_diet'], 'diet_name')?>
							</div>
                        </div>
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('body_type');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('body_type', $partner_expectation_data[0]['partner_body_type'], 'body_type_name')?>
							</div>
                        </div>
                    
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('manglik');?>:</b>
							</div>
							<div class="col-6 p-0">
								 <?=$this->Crud_model->get_type_name_by_id('decision', $partner_expectation_data[0]['manglik'])?>
							</div>
                        </div>
                    
                      <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('any_disability');?>:</b>
							</div>
							<div class="col-6 p-0">
							  <?=$this->Crud_model->get_type_name_by_id('decision', $partner_expectation_data[0]['partner_any_disability'])?>
							</div>
                        </div>
						
						<div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('mother_tongue');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('language', $partner_expectation_data[0]['partner_mother_tongue'])?>
							</div>
                        </div>
                    
                    <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('family_value');?>:</b>
							</div>
							<div class="col-6 p-0">
							   <?=$this->Crud_model->get_type_name_by_id('family_value', $partner_expectation_data[0]['partner_family_value'], 'name')?>   
							</div>
                        </div>
						
					<div class="row">	
							<div class="col-6 p-0">
								<b><?php echo translate('prefered_country');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$this->Crud_model->get_type_name_by_id('country', $partner_expectation_data[0]['prefered_country'])?>
							</div>						
                        </div>
                    
                    <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('prefered_state');?>:</b>
							</div>
							<div class="col-6 p-0">
								<?=$partner_expectation_data[0]['prefered_state']?>
							</div>							
                        </div>
						
                    
                      <div class="row">
							<div class="col-6 p-0">
								<b><?php echo translate('complexion');?>:</b>
							</div>
							<div class="col-6 p-0">
								     <?=$this->Crud_model->get_type_name_by_id('complexion', $partner_expectation_data[0]['partner_complexion'], 'complexion_name')?>
							</div>
                        </div>

		     </div>
	
	    </div><!----- Main-Row End---> 

</div>