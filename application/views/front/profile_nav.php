<style type="text/css">
    @media (max-width: 991px) {
        .hidden_xs { display: none !important; }
    }
    @media (min-width: 992px) {
        .visible_xs { display: none !important; }
    }
</style>
<div class="hidden_xs">
    <nav class="navbar profile-nav">
        <div class="container navbar-container">
            
            
            <!---div class="d-inline-block">
               
                <button class="navbar-toggler hamburger hamburger-js hamburger--spring" type="button" data-toggle="collapse" data-target="#navbar_main" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
                </button>
            </div--->
            
                <ul id="navbar_main" class="nav navbar-nav  justify-content-center" data-hover="dropdown" data-animations="zoomIn zoomIn zoomIn zoomIn">
                    <li class="nav-item">
                        <a href="<?=base_url()?>home/profile" class="nav-link p_nav <?php echo $_SERVER['REQUEST_URI'] == base_url().'home/profile' ? 'active' : ''?>">
                            <i class="ion-android-person"></i> <?php echo translate('my_profile')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link my_interests p_nav" href="<?=base_url()?>home/profile/my-interests">
                            <i class="ion-ios-heart-outline"></i> <?php echo translate('sent_interests')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link received_interests p_nav" href="<?=base_url()?>home/profile/received-interests">
                            <i class="ion-ios-heart"></i> <?php echo translate('received_interests')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link short_list p_nav" href="<?=base_url()?>home/profile/shortlist">
                            <i class="fa fa-list-ul"></i> <?php echo translate('shortlist')?>
                        </a>
                    </li>
                    <!---li class="nav-item">
                        <a class="nav-link followed_users p_nav"  href="<?=base_url()?>home/profile/followed-users">
                            <i class="fa fa-star"></i> <?php //echo translate('followed_users')?>
                        </a>
                    </li-->
                    <li class="nav-item">
                        <a class="nav-link messaging p_nav" href="<?=base_url()?>home/profile/messaging-list">
                            <i class="fa fa-comments-o"></i> <?php echo translate('messaging')?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ignored_list p_nav" href="<?=base_url()?>home/profile/ignored-list">
                            <i class="fa fa-ban"></i> <?php echo translate('ignored_list')?>
                        </a>
                    </li>
<?php /*                    <li class="nav-item">
                     <a class="nav-link ignored_list p_nav"  href="<?=base_url()?>home/profile/gallery-list">
                    <b style="font-size: 12px"><?php echo translate('gallery')?></b>
                    </li>*/?>
                </a>
                </ul>
           
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
                    $("#profile_load").html(response);
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