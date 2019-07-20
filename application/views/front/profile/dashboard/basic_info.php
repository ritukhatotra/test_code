<?php 
    $basic_info = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'basic_info');
    $basic_info_data = json_decode($basic_info, true);
?>

<div class="mb-2 pl-3">
    <b><?=translate('Member ID').' - '?></b><b class="c-base-1"><?=$get_member[0]->member_profile_id?></b>
</div>

<div class="feature feature--boxed-border feature--bg-1 pt-3 pb-0 pl-3 pr-3 mb-3 border_top2x">
    <div id="info_basic_info">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('basic_information')?>
            </h3>
            <div class="pull-right">
                <button type="button" class="btn btn-base-1 btn-sm btn-icon-only btn-shadow mb-1" onclick="edit_section('basic_info')">
                <i class="ion-edit"></i>
                </button>
            </div>
        </div>
		
		<div class="clearfix"></div>
		
		<div class="row list-box-columns">
	    
		<div class="col-md-6">		
		      <div class="row">				  
			         <div class="col-6 p-0">
                                <b><?php echo translate('first_name')?></b>
                            </div>
                            <div class="col-6 p-0">
                                <?=$get_member[0]->first_name?>
                            </div>
													  
					</div>
					
					<div class="row">	
                          <div class="col-6 p-0">
                                <b><?php echo translate('last_name')?></b>
                            </div>
                            <div class="col-6 p-0">
                                <?=$get_member[0]->last_name?>
                            </div>				
												  
					</div>
					
					<div class="row">    
						
                            <div class="col-6 p-0">
                                <b><?php echo translate('age')?></b>
                            </div>
                            <div class="col-6 p-0">
                                <?php
                                    $calculated_age = (date('Y') - date('Y', $get_member[0]->date_of_birth));
                                    echo $calculated_age;
                                ?>
                            </div> 					   
					  </div>
					  
					  <div class="row">    
						  <div class="col-6 p-0">
                                <b><?php echo translate('date_of_birth')?></b>
                            </div>
                            <div class="col-6 p-0">
                                <?=date('d/m/Y', $get_member[0]->date_of_birth)?>
                            </div> 
                           				   
					  </div>
					  
					   <div class="row">  
					   
						 <div class="col-6 p-0">
                                <b><?php echo translate('on_behalf')?></b>
                            </div>
                            <div class="col-6 p-0">
                                 <?=$this->Crud_model->get_type_name_by_id('on_behalf', $basic_info_data[0]['on_behalf']);?>
                            </div>
                           				   
					  </div>
					  
					  
					  
					  
					

		     </div>
			 
			 
			  
		<div class="col-md-6">
		      <div class="row">	
                       <div class="col-6 p-0">
                                <b><?php echo translate('gender')?></b>
                            </div>
                            <div class="col-6 p-0">
                                <?=$this->Crud_model->get_type_name_by_id('gender', $get_member[0]->gender)?>
                            </div>							  
					</div>
					
					<div class="row">	
                         <div class="col-6 p-0">
                                <b><?php echo translate('email')?></b>
                            </div>
                            <div class="col-6 p-0">
                                <?=$get_member[0]->email?>
                            </div>
										  
					</div>
					
					<div class="row">	
                          <div class="col-6 p-0">
                                <b><?php echo translate('mobile')?></b>
                            </div>
                            <div class="col-6 p-0"><?=$get_member[0]->mobile?></div>	  
					</div>
					
					<div class="row">
                          <div class="col-6 p-0">
                                <b><?php echo translate('belongs_to')?></b>
                                </div>
                                <div class="col-6 p-0">
                                <?=$get_member[0]->belongs_to?>
                                </div>

					
                           
					</div>
					
					 <div class="row">  
					   
					       
                        <?php if($basic_info_data[0]['marital_status'] == 1) {?>
                               
                                <div class="col-6 p-0">
                                    <b><?php echo translate('marital_status')?></b>
                                </div>
                                <div class="col-6 p-0">
                                    <?=$this->Crud_model->get_type_name_by_id('marital_status', $basic_info_data[0]['marital_status'])?>
                                </div>
                            
                        <?php }else{?>
                            
                                <div class="col-6 p-0">
                                    <b><?php echo translate('marital_status')?></b>
                                </div>
                                <div class="col-6 p-0">
                                    <?=$this->Crud_model->get_type_name_by_id('marital_status', $basic_info_data[0]['marital_status'])?>
                                </div>                            
                                <div class="col-6 p-0">
                                    <b><?php echo translate('number_of_children')?></b>
                                </div>
                                <div class="col-6 p-0">
                                    <?=$basic_info_data[0]['number_of_children']?>
                                </div>
                        <?php } ?>
					   
						 
                           				   
					  </div>
					

		     </div>
			 
			 
			 
			 
</div>	
		
        
    </div>

    <div id="edit_basic_info" style="display: none;">
        <div class="card-inner-title-wrapper pt-0">
            <h3 class="card-inner-title pull-left">
                <?php echo translate('basic_information')?>
            </h3>
            <div class="pull-right">
                <button type="button" class="btn btn-success btn-sm btn-icon-only btn-shadow" onclick="save_section('basic_info')"><i class="ion-checkmark"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn-icon-only btn-shadow" onclick="load_section('basic_info')"><i class="ion-close"></i></button>
            </div>
        </div>
        
        <div class='clearfix'></div>
        <form id="form_basic_info" class="form-default" role="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="first_name" class="text-uppercase c-gray-light"><?php echo translate('first_name')?></label>
                        <input type="text" class="form-control no-resize" name="first_name" value="<?=$get_member[0]->first_name?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="last_name" class="text-uppercase c-gray-light"><?php echo translate('last_name')?></label>
                        <input type="text" class="form-control no-resize" name="last_name" value="<?=$get_member[0]->last_name?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="first_name" class="text-uppercase c-gray-light"><?php echo translate('gender')?></label>
                        <?php 
                            echo $this->Crud_model->select_html('gender', 'gender', 'name', 'edit', 'form-control form-control-sm selectpicker', $get_member[0]->gender, '', '', '');
                        ?>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="email" class="text-uppercase c-gray-light"><?php echo translate('email')?></label>
                        <input type="hidden" name="old_email" value="<?=$get_member[0]->email?>">
                        <input type="email" class="form-control no-resize" name="email" value="<?=$get_member[0]->email?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                 <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="date_of_birth" class="text-uppercase c-gray-light"><?php echo translate('date_of_birth')?> </label>
                        <?php
                                $month = [
                                    '1' => 'January',
                                    '2' => 'February',
                                    '3' => 'March',
                                    '4' => 'April',
                                    '5' => 'May',
                                    '6' => 'June',
                                    '7' => 'July',
                                    '8' => 'August',
                                    '9' => 'September',
                                    '10' => 'October',
                                    '11' => 'November',
                                    '12' => 'December'
                                ];
                                $current_year = date("Y");
                                
                                $old_date = date('d', $get_member[0]->date_of_birth);
                                $old_month = date('m', $get_member[0]->date_of_birth);
                                $old_year = date('Y', $get_member[0]->date_of_birth);
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select name="monthob" id="mobrth" class="form-control form-control-sm">
                                        <option value="">Month</option>
                                        <?php foreach ($month as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>" <?php if($key == $old_month) { echo "selected"; } ?>>
                                            <?php echo $value; ?>
                                        </option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="dateob" id="dobrth" class="form-control form-control-sm">
                                        <option value="">Date</option>
                                        </select>
                                        <input type="hidden" id="old_dob" value="<?php echo $old_date; ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <select name="yearob" id="yobrth" class="form-control form-control-sm">
                                        <option value="">Year</option>
                                        <?php for( $y = 1970; $y <= $current_year; $y++ ) { ?>
                                        <option value = "<?php echo $y; ?>" <?php if($y == $old_year) { echo "selected"; } ?>>
                                            <?php echo $y; ?>
                                        </option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        
                                            var mobr = $('#mobrth').val();
                                            var mon31 = ['1', '3', '5', '7','8', '10', '12'];
                                            var mon30 = ['4', '6', '9', '11'];
                                            if( $.inArray(mobr, mon31) != -1 ) {
                                                date_drop(31);
                                            }
                                            else if( $.inArray(mobr, mon30) != -1 ) {
                                                date_drop(30);
                                                
                                            }   else if( mobr == 2 ) {
                                                date_drop(28);
                                            }
                                    });
                                    function date_drop(nmbr_days) {
                                        var old_dob = $('#old_dob').val();
                                        var date_html = "<option>Date</option>";
                                                for( var i = 1; i <= nmbr_days; i++ ) {
                                                    if( i == old_dob ) { var selected = "selected"; }   else { var selected = ""; }
                                                    date_html += "<option value='"+i+"' "+selected+">"+i+"</option>";
                                                }
                                                $('#dobrth').html(date_html);
                                    }
                                </script>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="marital_status" class="text-uppercase c-gray-light"><?php echo translate('marital_status')?></label>
                        <?php 
                            echo $this->Crud_model->select_html('marital_status', 'marital_status', 'name', 'edit', 'form-control form-control-sm selectpicker basic_info_marital_status_select', $basic_info_data[0]['marital_status'], '', '', '');
                        ?>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6 basic_info_number_children_select" style="display:<?php echo $basic_info_data[0]['marital_status'] == 1 ? 'none' : ''; ?>">
                    <div class="form-group has-feedback">
                        <label for="number_of_children" class="text-uppercase c-gray-light"><?php echo translate('number_of_children')?></label>
                        <input type="number" class="form-control no-resize" name="number_of_children" value="<?=$basic_info_data[0]['number_of_children']?>" min="0">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <?php /*
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="area" class="text-uppercase c-gray-light"><?php echo translate('area')?></label>
                        <input type="text" class="form-control no-resize" name="area" value="<?=$basic_info_data[0]['area']?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>*/?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="on_behalf" class="text-uppercase c-gray-light"><?php echo translate('on_behalf')?></label>
                        <?php 
                            echo $this->Crud_model->select_html('on_behalf', 'on_behalf', 'name', 'edit', 'form-control form-control-sm selectpicker present_on_behalf_edit', $basic_info_data[0]['on_behalf'], '', '', '');
                        ?>
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="mobile" class="text-uppercase c-gray-light"><?php echo translate('mobile')?></label>
                        <input type="hidden" name="old_mobile" value="<?=$get_member[0]->mobile?>">
                        <input type="text" class="form-control no-resize" name="mobile" value="<?=$get_member[0]->mobile?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group has-feedback">
                        <label for="belongs_to" class="text-uppercase c-gray-light"><?php echo translate('belongs_to')?></label>
                        <input type="hidden" name="old_belongs_to" value="<?=$get_member[0]->belongs_to?>">
                        <input type="text" class="form-control no-resize" name="belongs_to" value="<?=$get_member[0]->belongs_to?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>