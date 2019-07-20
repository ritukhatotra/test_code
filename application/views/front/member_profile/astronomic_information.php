<div id="info_astronomic_information">
    <div class="card-inner-title-wrapper pt-0">
        <h3 class="card-inner-title pull-left">
            <?php echo translate('astronomic_information')?>
        </h3>
    </div>
	
	<div class="clearfix"></div><hr>
	
	<div class="row list-box-columns">
	    
		<div class="col-md-6">
		      <div class="row">								  
					<div class="col-6 p-0"><b><?php echo translate('sun_sign')?>:</b></div>
					<div class="col-6 p-0"> <?=$astronomic_information_data[0]['sun_sign']?></div>								  
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
					<div class="col-6 p-0"><?=$astronomic_information_data[0]['city_of_birth']?></div>								  
					</div>

		     </div>
	
	    </div><!----- Main-Row End---> 
	

</div>