<style type="text/css">
    @media (max-width: 991px) {
        .hidden_xs { display: none !important; }
    }
    @media (min-width: 992px) {
        .visible_xs { display: none !important; }
    }
</style>
<div class="hidden_xs">
    <nav class="navbar navbar-expand-lg  navbar--style-1 navbar-light bg-default navbar--shadow navbar--uppercase profile-nav">
        <div class="container navbar-container">
            <!-- Brand/Logo -->
            
            <div class="d-inline-block">
                <!-- Navbar toggler  -->
                <button class="navbar-toggler hamburger hamburger-js hamburger--spring" type="button" data-toggle="collapse" data-target="#navbar_main" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
                </button>
            </div>
            <div class="collapse navbar-collapse justify-content-between align-items-center" id="navbar_main">
                <ul class="navbar-nav " data-hover="dropdown" data-animations="zoomIn zoomIn zoomIn zoomIn">
                    <li class="nav-item">
                        <a href="<?=base_url()?>home/profile" class="nav-link p_nav active">
                            <i class="fa fa-user"></i> <?php echo translate('my_profile')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link my_interests p_nav" onclick="profile_load('my_interests')">
                            <i class="fa fa-heart"></i> <?php echo translate('sent_interests')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link received_interests p_nav" onclick="profile_load('received_interests')">
                            <i class="fa fa-heart"></i> <?php echo translate('received_interests')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link short_list p_nav" onclick="profile_load('short_list')">
                            <i class="fa fa-list-ul"></i> <?php echo translate('shortlist')?>
                        </a>
                    </li>
                    <!---li class="nav-item">
                        <a class="nav-link followed_users p_nav" onclick="profile_load('followed_users')">
                            <i class="fa fa-star"></i> <?php //echo translate('followed_users')?>
                        </a>
                    </li-->
                    <li class="nav-item">
                        <a class="nav-link messaging p_nav" onclick="profile_load('messaging')">
                            <i class="fa fa-comments-o"></i> <?php echo translate('messaging')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ignored_list p_nav" onclick="profile_load('ignored_list')">
                            <i class="fa fa-ban"></i> <?php echo translate('ignored_list')?>
                        </a>
                    </li>
<?php /* <li class="nav-item">
                     <a class="nav-link ignored_list p_nav"  onclick="profile_load('gallery')">
                    <b style="font-size: 12px"><?php echo translate('gallery')?></b>
                    </li>*/?>
                </ul>
            </div>
        </div>
    </nav>
</div>
<script>
    $(document).ready(function(){
        profile_load('<?= $load_nav; ?>','<?= $sp_nav; ?>');
    });
    function profile_load(page,sp){
        // alert('here');
        if (typeof message_interval !== 'undefined') {
            clearInterval(message_interval);
        }
        if(page !== ''){
        
            $.ajax({
                url: "<?=base_url()?>home/profile/"+page,
                success: function(response) {
                    $(".edit_profile_pic_btn").hide();
                    $("#profile_load").html(response);
                   
                    var url_in =  "my-interests";
                    if (page == "my_interests") {
                    	url_in =  "my-interests";
        	    }
        	    if (page == "received_interests") {
                    	url_in =  "received-interests";
        	    }
        	    if (page == "short_list") {
                    	url_in =  "shortlist";
        	    }
        	    if (page == "followed_users") {
                    	url_in =  "followed-users";
        	    }
        	    if (page == "messaging") {
                    	url_in =  "messaging-list";
        	    }
        	     if (page == "ignored_list") {
                    	url_in =  "ignored-list";
        	    }
                    if (page == "notifications") {
                    	url_in =  "notifications-list";
        	    }
                    if (page == "picture_privacy") {
                    	url_in =  "settings";
        	    }
                    if (page == "gallery") {
                    	url_in =  "gallery-list";
        	    }
                    if(page == '') {
                    if($(".sidebar-outer").hasClass("hidden-onmobile")){
                         $('.sidebar-outer').removeClass('hidden-onmobile');
                     }
                    }else{
                      if(!$(".sidebar-outer").hasClass("hidden-onmobile")){
                         $('.sidebar-outer').addClass('hidden-onmobile');
                     }
                      }
                        var url = window.location.href;
			url = url.split('/');
			var var_url = url;
			var_url = var_url.pop();
			console.log('var_url'+var_url);
			if(var_url == 'profile') {
			     url = url.join('/');
			     console.log('url'+url);
			     
				history.pushState(null, null, url+"/profile/"+url_in);
			}else{			
			  url.splice(-1,1)
		          url = url.join('/');
		          console.log('url'+url);
          		 history.pushState(null, null, url+"/profile/"+url_in);
       		 }
                    if(page == 'messaging'){
                        $('body').find('#thread_'+sp).click();
                    }
                    // window.scrollTo(0, 0);
                    if (sp != 'no') {
                        $(".btn-back-to-top").click();
                    }
                }
            });
            $('.p_nav').removeClass("active");
            $('.l_nav').removeClass("li_active");
            $('.m_nav').removeClass("m_nav_active");

            if (page!='gallery'||page!='happy_story'||page!='my_packages'||page!='payments' ||page=='change_pass'||page=='picture_privacy') {
                $('.'+page).addClass("active");
                $('.m_'+page).addClass("m_nav_active");
            } 
            if (page=='gallery'||page=='happy_story'||page=='my_packages'||page=='payments' ||page=='change_pass'||page=='picture_privacy') {
                $('.'+page).addClass("li_active");
            }
            
        }
    }
</script>