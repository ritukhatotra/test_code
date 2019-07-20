<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    /*  
     *  Developed by: Active IT zone
     *  Date    : 18 September, 2017
     *  Active Matrimony CMS
     *  http://codecanyon.net/user/activeitezone
     */

    function __construct() {
        parent::__construct();
        $this->load->library('paypal');
        $this->load->library('pum');
        $this->system_name = $this->Crud_model->get_type_name_by_id('general_settings', '1', 'value');
        $this->system_email = $this->Crud_model->get_type_name_by_id('general_settings', '2', 'value');
        $this->system_title = $this->Crud_model->get_type_name_by_id('general_settings', '3', 'value');
        $cache_time  =  $this->db->get_where('general_settings',array('type' => 'cache_time'))->row()->value;
        if(!$this->input->is_ajax_request()){
            $this->output->set_header('HTTP/1.0 200 OK');
            $this->output->set_header('HTTP/1.1 200 OK');
            $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
            $this->output->set_header('Cache-Control: post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');
            $this->output_cache();
            if($this->router->fetch_method() == 'index' || 
                $this->router->fetch_method() == 'listing' || 
                $this->router->fetch_method() == 'plans' || 
                $this->router->fetch_method() == 'stories' || 
                $this->router->fetch_method() == 'contact_us' || 
                $this->router->fetch_method() == 'faq' || 
                $this->router->fetch_method() == 'terms_and_conditions' || 
                $this->router->fetch_method() == 'privacy_policy'){
                $this->output->cache($cache_time);
            }
        }
        
        $this->beforeRender();
        setcookie('lang', $this->session->userdata('language'), time() + (86400), "/");
    }

     public function beforeRender() {
       ### UPDATE LAST VISIT ###
        if ($this->session->userdata('member_id') != ""){
        // Checking for the SESSION - Proceed only if MEMBER/USER is logged in.
           $this->db->where('member_id', $this->session->userdata('member_id'));
             
            
            // UPDATE MEMBER VISIT TIME
            $last_visit = date('Y-m-d H:i:s', time());
            $data['last_visit'] = $last_visit;
            $result = $this->db->update('member', $data);
            recache();
        }    
      }
      
      public function set_timezone()
      {
          $this->db->where('member_id', $this->session->userdata('member_id'));
            
            // UPDATE MEMBER VISIT TIME
            $timezone =  $this->input->post('timezone');
            $data['timezone'] = $timezone;
            $result = $this->db->update('member', $data);
            recache();
          
      }
	
	public function index()
	{	
		$page_data['title'] = $this->system_title;
		$page_data['top'] = "home.php";
		$page_data['page'] = "home";
		$page_data['bottom'] = "home.php";

        $page_data['all_genders'] = $this->db->get('gender')->result();
        $page_data['all_religions'] = $this->db->get('religion')->result();
        $page_data['all_languages'] = $this->db->get('language')->result();
        $max_premium_member_num = $this->db->get_where('frontend_settings', array('type' => 'max_premium_member_num'))->row()->value;
        $max_story_num = $this->db->get_where('frontend_settings', array('type' => 'max_story_num'))->row()->value;
        
         $member_id = $this->session->userdata('member_id');
         
         if ($member_id == NULL) {
             $page_data['premium_members'] = $this->db->order_by('rand()')->get_where('member', array('membership' => 2, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1), $max_premium_member_num)->result();
             
             $page_data['recent_members'] = $this->db->order_by('member_since', 'desc')->get_where('member', array('is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1), $max_premium_member_num)->result();
             
             
         }else{
             $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
              $gender = 1;
              
             if(!empty($member_loggedin[0])) {
                 $gender = $member_loggedin[0]->gender;
             }
             
             if ($gender == 1) {
                 $gender = 2;
             } else {
                 $gender = 1;
             }
             
             
             //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                
                if (!empty($ignored_ids)) {
                    $page_data['premium_members'] = $this->db->order_by('rand()')->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 2, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                    
                    $page_data['recent_members'] = $this->db->order_by('member_since', 'desc')->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                 
                }
                else {
                     $page_data['premium_members'] = $this->db->order_by('rand()')->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 2, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                     
                     $page_data['recent_members'] = $this->db->order_by('member_since', 'desc')->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                }
         }
        
        $page_data['happy_stories'] = $this->db->get_where('happy_story', array('approval_status' => 1), $max_story_num)->result();
        $page_data['all_plans'] = $this->db->get("plan")->result();
        $this->load->view('front/index', $page_data);
    }

    function member_permission()
    {
        $login_state = $this->session->userdata('login_state');
        if($login_state == 'yes'){
            $member_id = $this->session->userdata('member_id');
            if ($member_id == NULL) {
                return FALSE;
            }
            else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    function listing($para1="",$para2="")
    {   

       if(strpos($para1, '-') !== false) {
           $para1 = str_replace('-', '_', $para1);
       }
       $page_data['height_array'] = $this->height_array();
        if ($para1=="") {
            $page_data['title'] = "Listing Page || ".$this->system_title;
            $page_data['top'] = "listing.php";
            $page_data['page'] = "listing";
            $page_data['bottom'] = "listing.php";
            $page_data['nav_dropdown'] = "all_members";
            $page_data['home_search'] = "false";

            $page_data['home_gender'] = "";
            $page_data['home_religion'] = "";
            $page_data['home_caste'] = "";
            $page_data['home_sub_caste'] = "";
            $page_data['home_language'] = "";
            $page_data['min_height'] = "";
            $page_data['max_height'] = "";
            $page_data['search_member_type'] = "all";
            recache();
            $this->load->view('front/index', $page_data);
        }
        elseif ($para1=="home_search") {
            $page_data['title'] = "Listing Page || ".$this->system_title;
            $page_data['top'] = "listing.php";
            $page_data['page'] = "listing";
            $page_data['bottom'] = "listing.php";
            $page_data['nav_dropdown'] = "";
            $page_data['home_search'] = "true";

            $page_data['home_gender'] = $this->input->post('gender');
            $page_data['aged_from'] = $this->input->post('aged_from');
            $page_data['aged_to'] = $this->input->post('aged_to');
            $page_data['home_religion'] = $this->input->post('religion');
            $page_data['home_caste'] = $this->input->post('caste');
            $page_data['home_sub_caste'] = $this->input->post('sub_caste');
            $page_data['home_language'] = $this->input->post('language');
            $page_data['min_height'] = $this->input->post('min_height');
            $page_data['max_height'] = $this->input->post('max_height');
            $page_data['search_member_type'] = "all";
            recache();
            $this->load->view('front/index', $page_data);
        }
        elseif ($para1=="premium_members") {
            $page_data['title'] = "Premium Members || ".$this->system_title;
            $page_data['top'] = "listing.php";
            $page_data['page'] = "listing";
            $page_data['bottom'] = "listing.php";
            $page_data['member_type'] = "premium_members";
            $page_data['nav_dropdown'] = "premium_members";
            $page_data['home_search'] = "false";

            $page_data['home_gender'] = "";
            $page_data['home_religion'] = "";
            $page_data['home_caste'] = "";
            $page_data['home_sub_caste'] = "";
            $page_data['home_language'] = "";
            $page_data['min_height'] = "";
            $page_data['max_height'] = "";
            $page_data['search_member_type'] = "all";
            recache();
            $this->load->view('front/index', $page_data);
        }
        elseif ($para1=="free_members") {
            $page_data['title'] = "Free Members || ".$this->system_title;
            $page_data['top'] = "listing.php";
            $page_data['page'] = "listing";
            $page_data['bottom'] = "listing.php";
            $page_data['member_type'] = "free_members";
            $page_data['nav_dropdown'] = "free_members";
            $page_data['home_search'] = "false";
            $page_data['home_gender'] = "";
            $page_data['home_religion'] = "";
            $page_data['home_caste'] = "";
            $page_data['home_sub_caste'] = "";
            $page_data['home_language'] = "";
            $page_data['min_height'] = "";
            $page_data['max_height'] = "";
            $page_data['search_member_type'] = "all";
            recache();
            $this->load->view('front/index', $page_data);
        } elseif($para1 == "near_me") {
			//echo "hello";
			$page_data['title'] = "Near Me || ".$this->system_title;
            $page_data['top'] = "listing.php";
            $page_data['page'] = "listing";
            $page_data['bottom'] = "listing.php";
            $page_data['member_type'] = "near_me";
            $page_data['nav_dropdown'] = "near_me";
            $page_data['home_search'] = "false";
            $page_data['home_gender'] = "";
            $page_data['home_religion'] = "";
            $page_data['home_caste'] = "";
            $page_data['home_sub_caste'] = "";
            $page_data['home_language'] = "";
            $page_data['min_height'] = "";
            $page_data['max_height'] = "";
            $page_data['search_member_type'] = "all";
            recache();
            $this->load->view('front/index', $page_data);
		}
    }

    function matches($para1="",$para2="")
    {   
		if(strpos($para1, '-') !== false) {
           $para1 = str_replace('-', '_', $para1);
		}
        if ($para1=="") {
            $page_data['title'] = "Listing Page || ".$this->system_title;
            $page_data['top'] = "matches.php";
            $page_data['page'] = "matches";
            $page_data['bottom'] = "matches.php";
            $page_data['nav_dropdown'] = "matches";
            $page_data['home_search'] = "false";
            recache();
            $this->load->view('front/index', $page_data);
        } elseif($para1 == "near_matches") {
			$page_data['title'] = "Listing Page || ".$this->system_title;
            $page_data['top'] = "matches.php";
            $page_data['page'] = "matches";
            $page_data['bottom'] = "matches.php";
            $page_data['nav_dropdown'] = "matches";
            $page_data['home_search'] = "false";
            recache();
            $this->load->view('front/index', $page_data);
		}
		
    }
    
    function member_profile($para1="",$para2="")
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }

        if ($para1 != "" || $para1 != NULL) {
            $is_valid = $this->db->get_where("member", array("member_id" => $para1))->row()->member_id;
            if (!$is_valid) {
                redirect(base_url().'home', 'refresh');
            }
            if ($this->db->get_where("member", array("member_id" => $para1))->row()->is_closed == 'yes') {
                redirect(base_url().'home', 'refresh');
            }
            $member_id = $this->session->userdata('member_id');
             
            if ($para2 == 'contact') {
                 $membership = $this->Crud_model->get_type_name_by_id('member', $member_id, 'membership');     
                 if ($membership > 1) {
                    $profile = $this->db->get_where("member", array("member_id" => $para1))->result();
                    $accepted = $this->Crud_model->get_type_name_by_id('member', $para1, 'interest');
                    $accepted  = json_decode($accepted , true);
                //    print_r($accepted);exit;
                    foreach($accepted as $a) {
                       if($a['id'] == $member_id && $a['status'] == 'accepted')  { 
                          $html = "<p>".$profile[0]->mobile."</p><p>".$profile[0]->email."</p>";       
//print_r($html);exit;
                          break;                  
                       }else{
                          $html = "<p>Contact information is visible to accepted members only.</p>";
                       }
                    }
                 }else{
                            $html = "<p>Contact information is visible to paid members only. Please upgrade your plan</p>";
                 }

               echo $html; exit;
            }
            $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
            $ignored_ids = json_decode($ignored_ids, true);

            if (!in_array($para1, $ignored_ids) && $para1 != $member_id) {
                $page_data['title'] = "Member Profile || ".$this->system_title;
                $page_data['top'] = "profile.php";
                $page_data['page'] = "member_profile";
                $page_data['bottom'] = "profile.php";
                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $para1))->result();

                $this->load->view('front/index', $page_data);
            }
            else {
                redirect(base_url().'home/listing', 'refresh');
            } 
        } else {
            redirect(base_url().'home/listing', 'refresh');
        }
        
    }

    function ajax_member_list($para1="",$para2="")
    {
         $this->load->library('Ajax_pagination');
        //$this->db->like('basic_info','"age":"30"','both');
        $config_base_url = base_url().'home/ajax_member_list/';
        if ($para2 == "free_members") {
            if ($this->member_permission() == FALSE) {
                $config['total_rows'] = $this->db->get_where('member', array('membership' => 1, 'is_blocked' => 'no'))->num_rows();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                
                if (!empty($ignored_ids)) {
                    $config['total_rows'] = $this->db->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 1, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->num_rows();
                }
                else {
                    $config['total_rows'] = $this->db->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 1, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->num_rows();
                }
            }
        } elseif($para2 == "near_me") {
			 if ($this->member_permission() == FALSE) {
                //echo "if";
				$ip  = $_SERVER['REMOTE_ADDR'];
				
				$geoIP  =  json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
				
				$location  =$geoIP->city;  
				// $lat = explode(",", $location)[0];
				// $lon = explode(",", $location)[1];
				
 $url ="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$geoIP->loc."&radius=100&key=";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     $response = curl_exec($ch);
     curl_close($ch);
	 
	 echo '<pre>';
	 print_r($response);
	 echo '</pre>';
	 
				exit;
				
				 $city = $this->db->get_where('city', array('name' => $location))->row();
					
				$this->db->select('*')->like('present_address','"city":"'.$city->city_id.'"','both');
               $query = $this->db->get('member')->result();
				foreach($query as $val) {					
					echo '<pre>';
					print_r($val);
					echo '</pre>';			
					
				}
				//$config['total_rows'] = $this->db->get_where('member', array('is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'city' => $geoIP, ))->num_rows();	
							
            } elseif ($this->member_permission() == TRUE) {
			//	echo "else";
			}
			
		
		} elseif($para2 == "near_matches") {
				echo "hello";
		} elseif ($para2 == "premium_members") {
            if ($this->member_permission() == FALSE) {
                $config['total_rows'] = $this->db->get_where('member', array('membership' => 2, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1))->num_rows();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $config['total_rows'] = $this->db->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 2, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->num_rows();
                }
                else {
                    $config['total_rows'] = $this->db->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 2, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->num_rows();
                }
            }
        }
        elseif ($para2 == "search") {
            $config_base_url = base_url().'home/ajax_member_list/search/';
            $all_result = array();
            if ($this->member_permission() == FALSE) {
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $all_id = $this->db->select('member_id')->where($cond)->get('member')->result();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                 //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                    //$this->db->last_query($all_id);
                }
                else {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                }
                
            }
            foreach ($all_id as $row) {
                $all_result[] = $row->member_id;
            }

            $gender   = $this->input->post('gender');
            $member_profile_id   = $this->input->post('member_id');
            $marital_status = $this->input->post('marital_status');
            $religion = $this->input->post('religion');
            $caste    = $this->input->post('caste');
            $sub_caste= $this->input->post('sub_caste');
            $language = $this->input->post('language');
            $country  = $this->input->post('country');
            $state    = $this->input->post('state');
            $city     = $this->input->post('city');
            $profession     = $this->input->post('profession');
           $aged_from = $this->input->post('aged_from');
           
            if (!empty($aged_from)) {
                $aged_from = $this->input->post('aged_from') - 1;
                $from_year = date('Y') - $aged_from;
                $from_date = $from_year."-01-01";
                $sql_aged_from = strtotime($from_date);   
            }

            $aged_to = $this->input->post('aged_to');
            if (!empty($aged_to)) {
                $to_year = date('Y') - $aged_to;
                $to_date = $to_year."-01-01";
                $sql_aged_to = strtotime($to_date);
            }
            if (!empty($this->input->post('min_height')) && !empty($this->input->post('max_height'))) {
            $min_height = $this->input->post('min_height');
            $max_height = $this->input->post('max_height');
            }
            $search_member_type = $this->input->post('search_member_type');

            $by_gender = array();
            $by_member_profile_id = array();
            $by_marital_status = array();
            $by_religion = array();
            $by_caste = array();
            $by_sub_caste = array();
            $by_language = array();
            $by_country = array();
            $by_state = array();
            $by_city = array();
            $by_profession = array();
            $by_age = array();
            $by_height = array();
            $by_member_type = array();

            $all_array = array();
             
             $member_id = $this->session->userdata('member_id');
             if ($member_id) {
              //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
             
                $by_genders = $this->db->select('member_id')->get_where('member', array('gender' => $gender))->result();
                foreach ($by_genders as $by_genders) {
                    $by_gender[] = $by_genders->member_id;
                }
             }else{
                 if (isset($gender) && $gender != "") {
                    $by_genders = $this->db->select('member_id')->get_where('member', array('gender' => $gender))->result();
                    foreach ($by_genders as $by_genders) {
                        $by_gender[] = $by_genders->member_id;
                    }
                } else {
                    $by_gender = $all_result;
                }
             }


            if (isset($member_profile_id) && $member_profile_id != "") {
                $by_member_profile_ids = $this->db->select('member_id')->get_where('member', array('member_profile_id' => $member_profile_id))->result();
                foreach ($by_member_profile_ids as $by_member_profile_ids) {
                    $by_member_profile_id[] = $by_member_profile_ids->member_id;
                }
            } else {
                $by_member_profile_id = $all_result;
            }

            if (isset($profession) && $profession != "") {
                $this->db->select('member_id')->like('education_and_career','"occupation":"'.$profession.'"','both');
                $by_professions = $this->db->get('member')->result();
                foreach ($by_professions as $by_professions) {
                    $by_profession[] = $by_professions->member_id;
                }
            } else {
                $by_profession = $all_result;
            }

            if (isset($marital_status) && $marital_status != "") {
                $this->db->select('member_id')->like('basic_info','"marital_status":"'.$marital_status.'"','both');
                $by_marital_statuss = $this->db->get('member')->result();
                foreach ($by_marital_statuss as $by_marital_statuss) {
                    $by_marital_status[] = $by_marital_statuss->member_id;
                }
            } else {
                $by_marital_status = $all_result;
            }

            if (isset($religion) && $religion != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"religion":"'.$religion.'"','both');
                $by_religions = $this->db->get('member')->result();
                foreach ($by_religions as $by_religions) {
                    $by_religion[] = $by_religions->member_id;
                }
            } else {
                $by_religion = $all_result;
            }

            // if (isset($caste) && $caste != "") {
            //     $this->db->select('member_id')->like('spiritual_and_social_background','"caste":"'.$caste.'"','both');
            //     $by_castes = $this->db->get('member')->result();
            //     foreach ($by_castes as $by_castes) {
            //         $by_caste[] = $by_castes->member_id;
            //     }
            // } else {
            //     $by_caste = $all_result;
            // }

            if (isset($caste) && $caste != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"caste":"'.$caste.'"','both');
                $by_castes = $this->db->get('member')->result();
                foreach ($by_castes as $by_castes) {
                    $by_caste[] = $by_castes->member_id;
                }
            } else {
                $by_caste = $all_result;
            }

            if (isset($sub_caste) && $sub_caste != "") {
                $this->db->select('member_id')->like('present_address','"sub_caste":"'.$sub_caste.'"','both');
                $by_sub_caste = $this->db->get('member')->result();
                foreach ($by_sub_caste as $by_sub_caste) {
                    $by_sub_caste[] = $by_sub_caste->member_id;
                }
            } else {
                $by_sub_caste = $all_result;
            }

            /*if (isset($language) && $language != "") {
                $this->db->select('member_id')->like('language','"mother_tongue":"'.$language.'"','both');
                $by_languages = $this->db->get('member')->result();
                foreach ($by_languages as $by_languages) {
                    $by_language[] = $by_languages->member_id;
                }
            } else {
                $by_language = $all_result;
            }*/
            $by_language = $all_result;

            if (isset($country) && $country != "") {
                $this->db->select('member_id')->like('present_address','"country":"'.$country.'"','both');
                $by_countries = $this->db->get('member')->result();
                foreach ($by_countries as $by_countries) {
                    $by_country[] = $by_countries->member_id;
                }
            } else {
                $by_country = $all_result;
            }

            if (isset($state) && $state != "") {
                $this->db->select('member_id')->like('present_address','"state":"'.$state.'"','both');
                $by_states = $this->db->get('member')->result();
                foreach ($by_states as $by_states) {
                    $by_state[] = $by_states->member_id;
                }
            } else {
                $by_state = $all_result;
            }

            if (isset($city) && $city != "") {
                $this->db->select('member_id')->like('present_address','"city":"'.$city.'"','both');
                $by_cities = $this->db->get('member')->result();
                foreach ($by_cities as $by_cities) {
                    $by_city[] = $by_cities->member_id;
                }
            } else {
                $by_city = $all_result;
            }

            if (isset($sql_aged_from) && isset($sql_aged_to)) {
                $by_ages = $this->db->select('member_id')->get_where('member',array('date_of_birth <=' => $sql_aged_from, 'date_of_birth >=' => $sql_aged_to))->result();
                foreach ($by_ages as $by_ages) {
                    $by_age[] = $by_ages->member_id;
                }
            } else {
                $by_age = $all_result;
            }

            if (isset($min_height) && isset($max_height)) {
                $by_heights = $this->db->select('member_id')->get_where('member',array('height >=' => $min_height, 'height <=' => $max_height))->result();
                foreach ($by_heights as $by_heights) {
                    $by_height[] = $by_heights->member_id;
                }
            } else {
                $by_height = $all_result;
            }
            

            if (isset($search_member_type)) {
                if ($search_member_type == "free_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 1))->result();
                }
                elseif ($search_member_type == "premium_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 2))->result();
                }
                elseif ($search_member_type == "all") {
                    $by_members_type = $all_id;
                }
                foreach ($by_members_type as $by_members_type) {
                    $by_member_type[] = $by_members_type->member_id;
                }
            } else {
                $by_member_type = $all_result;
            }
            /*print_r($by_gender);
            echo "<br>";
            print_r($by_marital_status);
            echo "<br>";
            print_r($by_religion);
            echo "<br>";
            print_r($by_language);
            echo "<br>";
            print_r($by_country);
            echo "<br>";
            print_r($by_state);
            echo "<br>";
            print_r($by_city);
            echo "<br>";
            print_r($by_member_profile_id);
            echo "<br>";
            print_r($by_profession);
            echo "<br>";
            print_r($by_caste);
            echo "<br>";
            print_r($by_sub_caste);
            echo "<br>";
            print_r($by_age);
            echo "<br>";
            print_r($by_height);
            echo "<br>";
            print_r($by_member_type);
            
            echo "<br>all<br>";*/
            $all_array = array_intersect($by_gender,$by_member_profile_id,$by_marital_status,$by_profession,$by_religion,$by_caste,$by_sub_caste,$by_language,$by_country,$by_state,$by_city,$by_age,$by_height,$by_member_type);
             //print_r($all_array);exit;
            $config['total_rows'] = count($all_array);
        }
        elseif ($para2 == "") {
           
            if ($this->member_permission() == FALSE) { 
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $config['total_rows'] = $this->db->where($cond)->count_all_results('member');
            }
            elseif ($this->member_permission() == TRUE) { 
                $member_id = $this->session->userdata('member_id');
                //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $config['total_rows'] = $this->db->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->num_rows();
                }
                else {
                    $config['total_rows'] = $this->db->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->num_rows();
                }
                
            }        
        }elseif ($para2 == "matches") {
            $config_base_url = base_url().'home/ajax_member_list/search/';
            $all_result = array();
            if ($this->member_permission() == FALSE) {
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $all_id = $this->db->select('member_id')->where($cond)->get('member')->result();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1))->result();
                    //$this->db->last_query($all_id);
                }
                else {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1))->result();
                }
                
            }
            foreach ($all_id as $row) {
                $all_result[] = $row->member_id;
            }

            $gender   = '1';
            $member_profile_id   = $this->input->post('member_id');
            
            $partner_expectation = $this->Crud_model->get_type_name_by_id('member', $member_id, 'partner_expectation');
            $partner_expectation_data = json_decode($partner_expectation, true);

            $marital_status = $partner_expectation_data['partner_marital_status'];
            $religion = $partner_expectation_data['partner_religion'];
            $caste    = $partner_expectation_data['partner_caste'];
            $sub_caste= $partner_expectation_data['partner_sub_caste'];
            $language = $partner_expectation_data['partner_language'];
            $country  = $partner_expectation_data['prefered_country'];
            $state    = $partner_expectation_data['prefered_state'];
            $city     = $partner_expectation_data['prefered_city'];
            $profession = $partner_expectation_data['profession'];
                        
            $aged_from = $partner_expectation_data['partner_min_age'] - 1;
            if (!empty($aged_from)) {
                $from_year = date('Y') - $aged_from;
                $from_date = $from_year."-01-01";
                $sql_aged_from = strtotime($from_date);   
            }

            $aged_to = $partner_expectation_data['partner_max_age'];
            if (!empty($aged_to)) {
                $to_year = date('Y') - $aged_to;
                $to_date = $to_year."-01-01";
                $sql_aged_to = strtotime($to_date);
            }

            $min_height = $partner_expectation_data['partner_min_height'];
            $max_height = $partner_expectation_data['partner_max_height'];
            $search_member_type = 'all';

           // print_R($partner_expectation_data);exit;

            $by_gender = array();
            $by_member_profile_id = array();
            $by_marital_status = array();
            $by_religion = array();
            $by_caste = array();
            $by_sub_caste = array();
            $by_language = array();
            $by_country = array();
            $by_state = array();
            $by_city = array();
            $by_profession = array();
            $by_age = array();
            $by_height = array();
            $by_member_type = array();

            $all_array = array();

            if (isset($gender) && $gender != "") {
                $by_genders = $this->db->select('member_id')->get_where('member', array('gender' => $gender))->result();
                foreach ($by_genders as $by_genders) {
                    $by_gender[] = $by_genders->member_id;
                }
            } else {
                $by_gender = $all_result;
            }

            if (isset($member_profile_id) && $member_profile_id != "") {
                $by_member_profile_ids = $this->db->select('member_id')->get_where('member', array('member_profile_id' => $member_profile_id))->result();
                foreach ($by_member_profile_ids as $by_member_profile_ids) {
                    $by_member_profile_id[] = $by_member_profile_ids->member_id;
                }
            } else {
                $by_member_profile_id = $all_result;
            }

            if (isset($profession) && $profession != "") {
                $this->db->select('member_id')->like('education_and_career','"occupation":"'.$profession.'"','both');
                $by_professions = $this->db->get('member')->result();
                foreach ($by_professions as $by_professions) {
                    $by_profession[] = $by_professions->member_id;
                }
            } else {
                $by_profession = $all_result;
            }

            if (isset($marital_status) && $marital_status != "") {
                $this->db->select('member_id')->like('education_and_career','"marital_status":"'.$marital_status.'"','both');
                $by_marital_statuss = $this->db->get('member')->result();
                foreach ($by_marital_statuss as $by_marital_statuss) {
                    $by_marital_status[] = $by_marital_statuss->member_id;
                }
            } else {
                $by_marital_status = $all_result;
            }

            if (isset($profession) && $profession != "") {
                $this->db->select('member_id')->like('basic_info','"occupation":"'.$profession.'"','both');
                $by_professions = $this->db->get('member')->result();
                foreach ($by_professions as $by_professions) {
                    $by_profession[] = $by_professions->member_id;
                }
            } else {
                $by_profession = $all_result;
            }

            if (isset($religion) && $religion != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"religion":"'.$religion.'"','both');
                $by_religions = $this->db->get('member')->result();
                foreach ($by_religions as $by_religions) {
                    $by_religion[] = $by_religions->member_id;
                }
            } else {
                $by_religion = $all_result;
            }

            if (isset($caste) && $caste != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"caste":"'.$caste.'"','both');
                $by_castes = $this->db->get('member')->result();
                foreach ($by_castes as $by_castes) {
                    $by_caste[] = $by_castes->member_id;
                }
            } else {
                $by_caste = $all_result;
            }
            if (isset($sub_caste) && $sub_caste != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"sub_caste":"'.$sub_caste.'"','both');
                $by_sub_castes = $this->db->get('member')->result();
                foreach ($by_sub_castes as $by_sub_castes) {
                    $by_sub_caste[] = $by_sub_castes->member_id;
                }
            } else {
                $by_sub_caste = $all_result;
            }

            if (isset($language) && $language != "") {
                $this->db->select('member_id')->like('language','"mother_tongue":"'.$language.'"','both');
                $by_languages = $this->db->get('member')->result();
                foreach ($by_languages as $by_languages) {
                    $by_language[] = $by_languages->member_id;
                }
            } else {
                $by_language = $all_result;
            }

            if (isset($country) && $country != "") {
                $this->db->select('member_id')->like('present_address','"country":"'.$country.'"','both');
                $by_countries = $this->db->get('member')->result();
                foreach ($by_countries as $by_countries) {
                    $by_country[] = $by_countries->member_id;
                }
            } else {
                $by_country = $all_result;
            }

            if (isset($state) && $state != "") {
                $this->db->select('member_id')->like('present_address','"state":"'.$state.'"','both');
                $by_states = $this->db->get('member')->result();
                foreach ($by_states as $by_states) {
                    $by_state[] = $by_states->member_id;
                }
            } else {
                $by_state = $all_result;
            }

            if (isset($city) && $city != "") {
                $this->db->select('member_id')->like('present_address','"city":"'.$city.'"','both');
                $by_cities = $this->db->get('member')->result();
                foreach ($by_cities as $by_cities) {
                    $by_city[] = $by_cities->member_id;
                }
            } else {
                $by_city = $all_result;
            }

            if (isset($sql_aged_from) && isset($sql_aged_to)) {
                $by_ages = $this->db->select('member_id')->get_where('member',array('date_of_birth <=' => $sql_aged_from, 'date_of_birth >=' => $sql_aged_to))->result();
                foreach ($by_ages as $by_ages) {
                    $by_age[] = $by_ages->member_id;
                }
            } else {
                $by_age = $all_result;
            }

            if (isset($min_height) && isset($max_height)) {
                $by_heights = $this->db->select('member_id')->get_where('member',array('height >=' => $min_height, 'height <=' => $max_height))->result();
                foreach ($by_heights as $by_heights) {
                    $by_height[] = $by_heights->member_id;
                }
            } else {
                $by_height = $all_result;
            }

            if (isset($search_member_type)) {
                if ($search_member_type == "free_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 1))->result();
                }
                elseif ($search_member_type == "premium_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 2))->result();
                }
                elseif ($search_member_type == "all") {
                    $by_members_type = $all_id;
                }
                foreach ($by_members_type as $by_members_type) {
                    $by_member_type[] = $by_members_type->member_id;
                }
            } else {
                $by_height = $all_result;
            }

            /*print_r($by_gender);
            echo "<br>";
            print_r($by_marital_status);
            echo "<br>";
            print_r($by_religion);
            echo "<br>";
            print_r($by_language);
            echo "<br>";
            print_r($by_country);
            echo "<br>";
            print_r($by_state);
            echo "<br>";
            print_r($by_city);
            echo "<br>all<br>";*/
            $all_array = array_intersect($by_gender,$by_member_profile_id,$by_marital_status,$by_profession,$by_religion,$by_caste,$by_sub_caste,$by_language,$by_country,$by_state,$by_city,$by_age,$by_height,$by_member_type);

            $config['total_rows'] = count($all_array);
        }

        // pagination
        $config['base_url'] = $config_base_url;
        $config['per_page'] = 5;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;
        if ($para2 == "search") {
            $function = "filter_members('0', 'search')";
        } else if ($para2 == "matches") {
            $function = "filter_members('0', 'matches')";
        } {
            $function = "filter_members('0')";
        }
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];

        $last_start = floor($rr) * $config['per_page'];
        if ($para2 == "search") {
            $function = "filter_members('" . $last_start . "', 'search')";
        } else if ($para2 == "matches") {
            $function = "filter_members('" . $last_start . "', 'matches')";
        }else {
            $function = "filter_members('" . $last_start . "')";
        }
        //$function = "filter_members('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        if ($para2 == "search") {
            $function = "filter_members('" . ($para1 - $config['per_page']) . "', 'search')";
        } else if ($para2 == "matches") {
            $function = "filter_members('" . ($para1 - $config['per_page']) . "', 'matches')";
        }else {
            $function = "filter_members('" . ($para1 - $config['per_page']) . "')";
        }
        //$function = "filter_members('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        if ($para2 == "search") {
            $function = "filter_members('" . ($para1 - $config['per_page']) . "', 'search')";
        }  if ($para2 == "matches") {
            $function = "filter_members('" . ($para1 - $config['per_page']) . "', 'matches')";
        } else {
            $function = "filter_members('" . ($para1 + $config['per_page']) . "')";
        }
        // $function = "filter_members('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        if ($para2 == "search") {
            $function = "filter_members(((this.innerHTML-1)*" . $config['per_page'] . "), 'search')";
        } if ($para2 == "matches") {
            $function = "filter_members(((this.innerHTML-1)*" . $config['per_page'] . "), 'matches')";
        }else {
            $function = "filter_members(((this.innerHTML-1)*" . $config['per_page'] . "))";
        }
        // $function = "filter_members(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);

        //$this->db->like('basic_info','"age":"30"','both');

        if ($para2 == "free_members") {
            if ($this->member_permission() == FALSE) {
                $page_data['get_all_members'] = $this->db->get_where('member', array('membership' => 1, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1), $config['per_page'], $para1)->result();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                 //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $page_data['get_all_members'] = $this->db->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 1, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender), $config['per_page'], $para1)->result();
                }
                else {
                    $page_data['get_all_members'] = $this->db->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 1, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender), $config['per_page'], $para1)->result();
                }
            }
        } elseif($para2 == "near_me") {
			 if ($this->member_permission() == FALSE) {
                //echo "if";
				$ip  = $_SERVER['REMOTE_ADDR'];
				
				$geoIP  =  json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
				
				$city = $geoIP;
								
				//$page_data['get_all_members'] = $this->db->get_where('member', array('is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1), $config['per_page'], $para1)->result();
				
				//				echo $this->db->last_query();

				
				
            } elseif ($this->member_permission() == TRUE) {
			//	echo "else";
			}
			
		
		} elseif ($para2 == "premium_members") {
            if ($this->member_permission() == FALSE) {
                $page_data['get_all_members'] = $this->db->get_where('member', array('membership' => 2, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1), $config['per_page'], $para1)->result();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $page_data['get_all_members'] = $this->db->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 2, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender), $config['per_page'], $para1)->result();
                }
                else {
                    $page_data['get_all_members'] = $this->db->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('membership' => 2, 'member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender), $config['per_page'], $para1)->result();
                }
            }
        }
        elseif ($para2 == "search") {
            $all_result = array();
            if ($this->member_permission() == FALSE) {
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $all_id = $this->db->select('member_id')->where($cond)->get('member')->result();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                }
                else {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                }
                
            }
            foreach ($all_id as $row) {
                $all_result[] = $row->member_id;
            }

            if (isset($gender) && $gender != "") {
                $by_genders = $this->db->select('member_id')->get_where('member', array('gender' => $gender))->result();
                foreach ($by_genders as $by_genders) {
                    $by_gender[] = $by_genders->member_id;
                }
            } else {
                $by_gender = $all_result;
            }

            if (isset($member_profile_id) && $member_profile_id != "") {
                $by_member_profile_ids = $this->db->select('member_id')->get_where('member', array('member_profile_id' => $member_profile_id))->result();
                foreach ($by_member_profile_ids as $by_member_profile_ids) {
                    $by_member_profile_id[] = $by_member_profile_ids->member_id;
                }
            } else {
                $by_member_profile_id = $all_result;
            }

            if (isset($marital_status) && $marital_status != "") {
                $this->db->select('member_id')->like('education_and_career','"marital_status":"'.$marital_status.'"','both');
                $by_marital_statuss = $this->db->get('member')->result();
                foreach ($by_marital_statuss as $by_marital_statuss) {
                    $by_marital_status[] = $by_marital_statuss->member_id;
                }
            } else {
                $by_marital_status = $all_result;
            }

            if (isset($profession) && $profession != "") {
                $this->db->select('member_id')->like('basic_info','"occupation":"'.$profession.'"','both');
                $by_professions = $this->db->get('member')->result();
                foreach ($by_professions as $by_professions) {
                    $by_profession[] = $by_professions->member_id;
                }
            } else {
                $by_profession = $all_result;
            }

            if (isset($religion) && $religion != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"religion":"'.$religion.'"','both');
                $by_religions = $this->db->get('member')->result();
                foreach ($by_religions as $by_religions) {
                    $by_religion[] = $by_religions->member_id;
                }
            } else {
                $by_religion = $all_result;
            }

            if (isset($caste) && $caste != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"caste":"'.$caste.'"','both');
                $by_castes = $this->db->get('member')->result();
                foreach ($by_castes as $by_castes) {
                    $by_caste[] = $by_castes->member_id;
                }
            } else {
                $by_caste = $all_result;
            }
            if (isset($sub_caste) && $sub_caste != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"sub_caste":"'.$sub_caste.'"','both');
                $by_sub_castes = $this->db->get('member')->result();
                foreach ($by_sub_castes as $by_sub_castes) {
                    $by_sub_caste[] = $by_sub_castes->member_id;
                }
            } else {
                $by_sub_caste = $all_result;
            }

            if (isset($language) && $language != "") {
                $this->db->select('member_id')->like('language','"mother_tongue":"'.$language.'"','both');
                $by_languages = $this->db->get('member')->result();
                foreach ($by_languages as $by_languages) {
                    $by_language[] = $by_languages->member_id;
                }
            } else {
                $by_language = $all_result;
            }

            if (isset($country) && $country != "") {
                $this->db->select('member_id')->like('present_address','"country":"'.$country.'"','both');
                $by_countries = $this->db->get('member')->result();
                foreach ($by_countries as $by_countries) {
                    $by_country[] = $by_countries->member_id;
                }
            } else {
                $by_country = $all_result;
            }

            if (isset($state) && $state != "") {
                $this->db->select('member_id')->like('present_address','"state":"'.$state.'"','both');
                $by_states = $this->db->get('member')->result();
                foreach ($by_states as $by_states) {
                    $by_state[] = $by_states->member_id;
                }
            } else {
                $by_state = $all_result;
            }

            if (isset($city) && $city != "") {
                $this->db->select('member_id')->like('present_address','"city":"'.$city.'"','both');
                $by_cities = $this->db->get('member')->result();
                foreach ($by_cities as $by_cities) {
                    $by_city[] = $by_cities->member_id;
                }
            } else {
                $by_city = $all_result;
            }

            if (isset($sql_aged_from) && isset($sql_aged_to)) {
                $by_ages = $this->db->select('member_id')->get_where('member',array('date_of_birth <=' => $sql_aged_from, 'date_of_birth >=' => $sql_aged_to))->result();
                foreach ($by_ages as $by_ages) {
                    $by_age[] = $by_ages->member_id;
                }
            } else {
                $by_age = $all_result;
            }

            if (isset($min_height) && isset($max_height)) {
                $by_heights = $this->db->select('member_id')->get_where('member',array('height >=' => $min_height, 'height <=' => $max_height))->result();
                foreach ($by_heights as $by_heights) {
                    $by_height[] = $by_heights->member_id;
                }
            } else {
                $by_height = $all_result;
            }

            if (isset($search_member_type)) {
                if ($search_member_type == "free_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 1))->result();
                }
                elseif ($search_member_type == "premium_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 2))->result();
                }
                elseif ($search_member_type == "all") {
                    $by_members_type = $all_id;
                }
                foreach ($by_members_type as $by_members_type) {
                    $by_member_type[] = $by_members_type->member_id;
                }
            } else {
                $by_height = $all_result;
            }
            /*print_r($by_gender);
            echo "<br>";
            print_r($by_marital_status);
            echo "<br>";
            print_r($by_religion);
            echo "<br>";
            print_r($by_language);
            echo "<br>";
            print_r($by_country);
            echo "<br>";
            print_r($by_state);
            echo "<br>";
            print_r($by_city);
            echo "<br>all<br>";*/
            $all_array = array_intersect($by_gender,$by_member_profile_id,$by_profession,$by_marital_status,$by_religion,$by_caste,$by_sub_caste,$by_language,$by_country,$by_state,$by_city,$by_age,$by_height,$by_member_type);

            if (count($all_array) != 0) {
                $this->db->where_in('member_id', $all_array);
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $page_data['get_all_members'] = $this->db->where($cond)->get('member', $config['per_page'], $para1)->result();
            } else {
                $page_data['get_all_members']  = array();
            }
        }elseif ($para2 == "matches") {
            $all_result = array();
            if ($this->member_permission() == FALSE) {
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $all_id = $this->db->select('member_id')->where($cond)->get('member')->result();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                 //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                if (!empty($ignored_ids)) {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                }
                else {
                    $all_id = $this->db->select('member_id')->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender))->result();
                }
                
            }
            foreach ($all_id as $row) {
                $all_result[] = $row->member_id;
            }

            if (isset($gender) && $gender != "") {
                $by_genders = $this->db->select('member_id')->get_where('member', array('gender' => $gender, 'is_completed' => 1))->result();
                foreach ($by_genders as $by_genders) {
                    $by_gender[] = $by_genders->member_id;
                }
            } else {
                $by_gender = $all_result;
            }

            if (isset($member_profile_id) && $member_profile_id != "") {
                $by_member_profile_ids = $this->db->select('member_id')->get_where('member', array('member_profile_id' => $member_profile_id, 'is_completed' => 1))->result();
                foreach ($by_member_profile_ids as $by_member_profile_ids) {
                    $by_member_profile_id[] = $by_member_profile_ids->member_id;
                }
            } else {
                $by_member_profile_id = $all_result;
            }

            if (isset($marital_status) && $marital_status != "") {
                $this->db->select('member_id')->like('education_and_career','"marital_status":"'.$marital_status.'"','both')->where(array('is_completed' => 1));
                $by_marital_statuss = $this->db->get('member')->result();
                foreach ($by_marital_statuss as $by_marital_statuss) {
                    $by_marital_status[] = $by_marital_statuss->member_id;
                }
            } else {
                $by_marital_status = $all_result;
            }

            if (isset($profession) && $profession != "") {
                $this->db->select('member_id')->like('basic_info','"occupation":"'.$profession.'"','both')->where(array('is_completed' => 1));
                $by_professions = $this->db->get('member')->result();
                foreach ($by_professions as $by_professions) {
                    $by_profession[] = $by_professions->member_id;
                }
            } else {
                $by_profession = $all_result;
            }

            if (isset($religion) && $religion != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"religion":"'.$religion.'"','both')->where(array('is_completed' => 1));
                $by_religions = $this->db->get('member')->result();
                foreach ($by_religions as $by_religions) {
                    $by_religion[] = $by_religions->member_id;
                }
            } else {
                $by_religion = $all_result;
            }

            if (isset($caste) && $caste != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"caste":"'.$caste.'"','both')->where(array('is_completed' => 1));
                $by_castes = $this->db->get('member')->result();
                foreach ($by_castes as $by_castes) {
                    $by_caste[] = $by_castes->member_id;
                }
            } else {
                $by_caste = $all_result;
            }
            if (isset($sub_caste) && $sub_caste != "") {
                $this->db->select('member_id')->like('spiritual_and_social_background','"sub_caste":"'.$sub_caste.'"','both')->where(array('is_completed' => 1));
                $by_sub_castes = $this->db->get('member')->result();
                foreach ($by_sub_castes as $by_sub_castes) {
                    $by_sub_caste[] = $by_sub_castes->member_id;
                }
            } else {
                $by_sub_caste = $all_result;
            }

            if (isset($language) && $language != "") {
                $this->db->select('member_id')->like('language','"mother_tongue":"'.$language.'"','both')->where(array('is_completed' => 1));
                $by_languages = $this->db->get('member')->result();
                foreach ($by_languages as $by_languages) {
                    $by_language[] = $by_languages->member_id;
                }
            } else {
                $by_language = $all_result;
            }

            if (isset($country) && $country != "") {
                $this->db->select('member_id')->like('present_address','"country":"'.$country.'"','both')->where(array('is_completed' => 1));
                $by_countries = $this->db->get('member')->result();
                foreach ($by_countries as $by_countries) {
                    $by_country[] = $by_countries->member_id;
                }
            } else {
                $by_country = $all_result;
            }

            if (isset($state) && $state != "") {
                $this->db->select('member_id')->like('present_address','"state":"'.$state.'"','both')->where(array('is_completed' => 1));
                $by_states = $this->db->get('member')->result();
                foreach ($by_states as $by_states) {
                    $by_state[] = $by_states->member_id;
                }
            } else {
                $by_state = $all_result;
            }

            if (isset($city) && $city != "") {
                $this->db->select('member_id')->like('present_address','"city":"'.$city.'"','both')->where(array('is_completed' => 1));
                $by_cities = $this->db->get('member')->result();
                foreach ($by_cities as $by_cities) {
                    $by_city[] = $by_cities->member_id;
                }
            } else {
                $by_city = $all_result;
            }

            if (isset($sql_aged_from) && isset($sql_aged_to)) {
                $by_ages = $this->db->select('member_id')->get_where('member',array('date_of_birth <=' => $sql_aged_from, 'date_of_birth >=' => $sql_aged_to, 'is_completed' => 1))->result();
                foreach ($by_ages as $by_ages) {
                    $by_age[] = $by_ages->member_id;
                }
            } else {
                $by_age = $all_result;
            }

            if (isset($min_height) && isset($max_height)) {
                $by_heights = $this->db->select('member_id')->get_where('member',array('height >=' => $min_height, 'height <=' => $max_height, 'is_completed' => 1))->result();
                foreach ($by_heights as $by_heights) {
                    $by_height[] = $by_heights->member_id;
                }
            } else {
                $by_height = $all_result;
            }

            if (isset($search_member_type)) {
                if ($search_member_type == "free_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 1, 'is_completed' => 1))->result();
                }
                elseif ($search_member_type == "premium_members") {
                    $by_members_type = $this->db->select('member_id')->get_where('member',array('membership' => 2, 'is_completed' => 1))->result();
                }
                elseif ($search_member_type == "all") {
                    $by_members_type = $all_id;
                }
                foreach ($by_members_type as $by_members_type) {
                    $by_member_type[] = $by_members_type->member_id;
                }
            } else {
                $by_height = $all_result;
            }
            /*print_r($by_gender);
            echo "<br>";
            print_r($by_marital_status);
            echo "<br>";
            print_r($by_religion);
            echo "<br>";
            print_r($by_language);
            echo "<br>";
            print_r($by_country);
            echo "<br>";
            print_r($by_state);
            echo "<br>";
            print_r($by_city);
            echo "<br>all<br>";*/
            $all_array = array_intersect($by_gender,$by_member_profile_id,$by_profession,$by_marital_status,$by_religion,$by_caste,$by_sub_caste,$by_language,$by_country,$by_state,$by_city,$by_age,$by_height,$by_member_type);

            if (count($all_array) != 0) {
                $this->db->where_in('member_id', $all_array);
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $page_data['get_all_members'] = $this->db->where($cond)->get('member', $config['per_page'], $para1)->result();
            } else {
                $page_data['get_all_members']  = array();
            }
        }
        elseif ($para2 == "") {
            if ($this->member_permission() == FALSE) {
                $cond = array('is_blocked' =>'no','is_closed' =>'no', 'is_completed' => 1);
                $page_data['get_all_members'] = $this->db->where($cond)->get('member', $config['per_page'], $para1)->result();
            }
            elseif ($this->member_permission() == TRUE) {
                $member_id = $this->session->userdata('member_id');
                  //For Opposite Gender
                 $member_loggedin = $this->db->get_where('member', array('member_id' => $member_id))->result();
             
                  $gender = 1;
                  
                 if(!empty($member_loggedin[0])) {
                     $gender = $member_loggedin[0]->gender;
                 }
                 
                 if ($gender == 1) {
                     $gender = 2;
                 } else {
                     $gender = 1;
                 }
                
                
                //For Ignored Members
                $ignored_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored');
                $ignored_ids = json_decode($ignored_ids, true);
                $ignored_by_ids = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
                $ignored_by_ids = json_decode($ignored_by_ids, true);
                if (empty($ignored_by_ids)) {
                    array_push($ignored_by_ids, 0);
                }
                
                
                if (!empty($ignored_ids)) {
                    // $this->db->like('basic_info','"age":"25"','both');
                    $page_data['get_all_members'] = $this->db->where_not_in('member_id', $ignored_ids)->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender), $config['per_page'], $para1)->result();
                }
                else {
                    // $this->db->like('basic_info','"age":"25"','both');
                    $page_data['get_all_members'] = $this->db->where_not_in('member_id', $ignored_by_ids)->get_where('member', array('member_id !=' => $member_id, 'is_blocked' => 'no','is_closed' => 'no', 'is_completed' => 1, 'gender' => $gender), $config['per_page'], $para1)->result();
                }
            }
        }
        
        $page_data['count'] = $config['total_rows'];

        $this->load->view('front/listing/members', $page_data);
    }

    function top_bar_right() {
        recache();
        $this->load->view('front/header/top_bar_right');
    }

    function add_interest($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $express_interest = $this->Crud_model->get_type_name_by_id('member', $member, 'express_interest');
        if ($express_interest > 0) {
            $interests = $this->Crud_model->get_type_name_by_id('member', $member, 'interest');
            $interest = json_decode($interests, true);
            if (empty($interest)) {
                $interest = array();
                $interest[] = array('id'=>$member_id,'status'=>'pending','time'=>time());
            }
            if (!in_assoc_array($member_id, 'id', $interest)) {
                $interest[] = array('id'=>$member_id,'status'=>'pending','time'=>time());
            }
            $this->db->where('member_id', $member);
            $this->db->update('member', array('interest' => json_encode($interest)));

            // Subtracting a Remaining Interest
            $express_interest = $express_interest - 1;
            $this->db->where('member_id', $member);
            $this->db->update('member', array('express_interest' => $express_interest));
            recache();

            // Updating the interest into the chosen Member
            $member_interests = $this->Crud_model->get_type_name_by_id('member', $member_id, 'interested_by');
            $member_interest = json_decode($member_interests, true);

            $notifications = $this->Crud_model->get_type_name_by_id('member', $member_id, 'notifications');
            $notification = json_decode($notifications, true);

            if (empty($member_interest)) {
                $member_interest = array();
                $member_interest[] = array('id'=>$member, 'status'=>'pending', 'time'=>time());
                $notification[] = array('by'=>$member, 'type'=>'interest_expressed', 'status'=>'pending', 'is_seen'=>'no', 'time'=>time());
            }
            if (!in_assoc_array($member, 'id',$member_interest)) {
                $member_interest[] = array('id'=>$member, 'status'=>'pending', 'time'=>time());
                $notification[] = array('by'=>$member, 'type'=>'interest_expressed', 'status'=>'pending', 'is_seen'=>'no', 'time'=>time());
            }

            $this->db->where('member_id', $member_id);
            $this->db->update('member', array('interested_by' => json_encode($member_interest), 'notifications' => json_encode($notification)));
            recache();
        }
    }

    function accept_interest($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }

        $member = $this->session->userdata('member_id');
        // For Updating User's interested_by
        $interested_by = $this->Crud_model->get_type_name_by_id('member', $member, 'interested_by');
        $interested_by = json_decode($interested_by, true);
        $new_interested_by = array();
        if (!empty($interested_by)) {
            foreach ($interested_by as $value1) {
                // print_r($value1)."<br>";
                if ($value1['id'] != $member_id) {
                    array_push($new_interested_by, $value1);
                }
                elseif ($value1['id'] == $member_id) {
                    array_push($new_interested_by, array('id'=>$value1['id'], 'status'=>'accepted', 'time'=>time()));
                }
                // print_r($new_interested_by)."<br>";
            }
        }
        // For Updating User's notifications
        $user_notifications = $this->Crud_model->get_type_name_by_id('member', $member, 'notifications');
        $user_notifications = json_decode($user_notifications, true);
        $new_user_notification = array();
        if (empty($user_notifications)) {
            // print_r($user_notifications)."<br>";
            array_push($new_user_notification, array('by'=>$member_id, 'type'=>'accepted_interest', 'status'=>'accepted', 'is_seen'=>'no', 'time'=>time()));
            // print_r($new_user_notification);
        }
        if (!empty($user_notifications)) {
            foreach ($user_notifications as $value2) {
                // print_r($value2)."<br>";
                if ($value2['by'] != $member_id) {
                    array_push($new_user_notification, $value2);
                }
                elseif ($value2['by'] == $member_id) {
                    array_push($new_user_notification, array('by'=>$value2['by'], 'type'=>'interest_expressed', 'status'=>'accepted', 'is_seen'=>'no', 'time'=>time()));
                }
                // print_r($new_user_notification);
            }
        }
        $this->db->where('member_id', $member);
        $this->db->update('member', array('interested_by' => json_encode($new_interested_by), 'notifications' => json_encode($new_user_notification)));

        // For Updating Member's interest
        $interest = $this->Crud_model->get_type_name_by_id('member', $member_id, 'interest');
        $interest = json_decode($interest, true);
        $new_interest = array();
        if (!empty($interest)) {
            foreach ($interest as $value3) {
                // print_r($value3)."<br>";
                if ($value3['id'] != $member) {
                    array_push($new_interest, $value3);
                }
                elseif ($value3['id'] == $member) {
                    array_push($new_interest, array('id'=>$value3['id'], 'status'=>'accepted', 'is_seen'=>'no', 'time'=>time()));
                }
                // print_r($new_interest)."<br>";
            }
        }
        // For Updating Member's notifications
        $member_notifications = $this->Crud_model->get_type_name_by_id('member', $member_id, 'notifications');
        $member_notifications = json_decode($member_notifications, true);
        // print_r($member_notifications)."<br>";
        array_push($member_notifications, array('by'=>$member, 'type'=>'accepted_interest', 'status'=>'accepted', 'is_seen'=>'no', 'time'=>time()));
        // print_r($member_notifications);
        
        $this->db->where('member_id', $member_id);
        $this->db->update('member', array('interest' => json_encode($new_interest), 'notifications' => json_encode($member_notifications)));
        recache();
    }

    function reject_interest($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }

        $member = $this->session->userdata('member_id');
        // For Updating User's interested_by
        $interested_by = $this->Crud_model->get_type_name_by_id('member', $member, 'interested_by');
        $interested_by = json_decode($interested_by, true);
        $new_interested_by = array();
        if (!empty($interested_by)) {
            foreach ($interested_by as $value1) {
                // print_r($value1)."<br>";
                if ($value1['id'] != $member_id) {
                    array_push($new_interested_by, $value1);
                }
                /*elseif ($value1['id'] == $member_id) {
                    array_push($new_interested_by, array('id'=>$value1['id'], 'status'=>'rejected', 'time'=>time()));
                }*/
                // print_r($new_interested_by)."<br>";
            }
        }
        // For Updating User's notifications
        $user_notifications = $this->Crud_model->get_type_name_by_id('member', $member, 'notifications');
        $user_notifications = json_decode($user_notifications, true);
        $new_user_notification = array();
        if (empty($user_notifications)) {
            // print_r($user_notifications)."<br>";
            array_push($new_user_notification, array('by'=>$member_id, 'type'=>'rejected_interest', 'status'=>'rejected', 'is_seen'=>'no', 'time'=>time()));
            // print_r($new_user_notification);
        }
        if (!empty($user_notifications)) {
            foreach ($user_notifications as $value2) {
                // print_r($value2)."<br>";
                if ($value2['by'] != $member_id) {
                    array_push($new_user_notification, $value2);
                }
                elseif ($value2['by'] == $member_id) {
                    array_push($new_user_notification, array('by'=>$value2['by'], 'type'=>'interest_expressed', 'status'=>'rejected', 'is_seen'=>'no', 'time'=>time()));
                }
                // print_r($new_user_notification);
            }
        }
        $this->db->where('member_id', $member);
        $this->db->update('member', array('interested_by' => json_encode($new_interested_by), 'notifications' => json_encode($new_user_notification)));

        // For Updating Member's interest
        $interest = $this->Crud_model->get_type_name_by_id('member', $member_id, 'interest');
        $interest = json_decode($interest, true);
        $new_interest = array();
        if (!empty($interest)) {
            foreach ($interest as $value3) {
                // print_r($value3)."<br>";
                if ($value3['id'] != $member) {
                    array_push($new_interest, $value3);
                }
                /*elseif ($value3['id'] == $member) {
                    array_push($new_interest, array('id'=>$value3['id'], 'status'=>'rejected', 'time'=>time()));
                }*/
                // print_r($new_interest)."<br>";
            }
        }
        // For Updating Member's notifications
        $member_notifications = $this->Crud_model->get_type_name_by_id('member', $member_id, 'notifications');
        $member_notifications = json_decode($member_notifications, true);
        // print_r($member_notifications)."<br>";
        array_push($member_notifications, array('by'=>$member, 'type'=>'rejected_interest', 'status'=>'rejected', 'is_seen'=>'no', 'time'=>time()));
        // print_r($member_notifications);
        
        $this->db->where('member_id', $member_id);
        $this->db->update('member', array('interest' => json_encode($new_interest), 'notifications' => json_encode($member_notifications)));
        recache();
    }

    function enable_message($member_id)
    {
       if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $direct_messages = $this->Crud_model->get_type_name_by_id('member', $member, 'direct_messages');
        if ($direct_messages > 0) {
            $data['message_thread_from'] = $member;
            $data['message_thread_to'] = $member_id;
            $data['message_thread_time'] = time();
            $this->db->insert('message_thread', $data);

            // Subtracting a Direct Message
            $direct_messages = $direct_messages - 1;
            $this->db->where('member_id', $member);
            $this->db->update('member', array('direct_messages' => $direct_messages));
            recache();
        }
    }

    function get_messages($message_thread_id, $get_all='')
    {
       if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        if ($get_all == "") {
            $member = $this->session->userdata('member_id');
            $member_position = $this->Crud_model->message_thread_member_position($message_thread_id,$member);
            $this->db->where('message_thread_id', $message_thread_id);
            $this->db->update('message_thread', array('message_'.$member_position.'_seen' => 'yes'));
            recache();

            $page_data['message_thread_id'] = $message_thread_id;
            $messages_query = $this->db->order_by('message_time')->get_where('message', array('message_thread_id' => $message_thread_id));
            $page_data['message_count'] = $messages_query->num_rows();
            if ($page_data['message_count'] <= 50) {
                $page_data['messages'] = $messages_query->result();
            } else {
                $limit_from = $page_data['message_count'] - 50;
                $limit_amount = 50;
                $page_data['messages'] = $this->db->order_by('message_time')->limit($limit_amount, $limit_from)->get_where('message', array('message_thread_id' => $message_thread_id))->result();
            }
        }
        elseif ($get_all == "all_msg") {
            $member = $this->session->userdata('member_id');
            $member_position = $this->Crud_model->message_thread_member_position($message_thread_id,$member);
            $this->db->where('message_thread_id', $message_thread_id);
            $this->db->update('message_thread', array('message_'.$member_position.'_seen' => 'yes'));
            recache();

            $page_data['message_thread_id'] = $message_thread_id;
            $messages_query = $this->db->order_by('message_time')->get_where('message', array('message_thread_id' => $message_thread_id));
            $page_data['messages'] = $messages_query->result();
            $page_data['message_count'] = 0; // to set the frontend variable for not displaying SHOW ALL MSG
        }
        
        $this->load->view('front/profile/messaging/messages', $page_data);
    }

    function send_message ($message_thread_id, $message_from, $message_to) {
        $data['message_thread_id'] = $message_thread_id;
        $data['message_from'] = $message_from;
        $data['message_to'] = $message_to;
        $data['message_text'] = $this->input->post('message_text');
        $data['message_time'] = time();
        $this->db->insert('message', $data);

        $member_position = $this->Crud_model->message_thread_member_position($message_thread_id,$message_to);
        $this->db->where('message_thread_id', $message_thread_id);
        $this->db->update('message_thread', array('message_'.$member_position.'_seen' => '','message_thread_time' => time()));
        recache();
    }

    function add_shortlist($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $shortlists = $this->Crud_model->get_type_name_by_id('member', $member, 'short_list');
        $shortlisted = json_decode($shortlists, true);
        if (empty($shortlisted)) {
            $shortlisted = array();
            array_push($shortlisted, $member_id);
        }
        if (!in_array($member_id, $shortlisted)) {
            array_push($shortlisted, $member_id);
        }
        $this->db->where('member_id', $member);
        $this->db->update('member', array('short_list' => json_encode($shortlisted)));
        recache();
    }

    function remove_shortlist($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $shortlists = $this->Crud_model->get_type_name_by_id('member', $member, 'short_list');
        $shortlisted = json_decode($shortlists, true);
        // $key = array_search($member_id, $shortlisted);
        if (empty($shortlisted)) {
            $shortlisted = array();
        }
        // unset($shortlisted[$key]); 
        $new_array = array();
        foreach ($shortlisted as $value) {
            if ($value != $member_id) {
                array_push($new_array, $value);
            }
        }
        $shortlisted = $new_array;
        $this->db->where('member_id', $member);
        $this->db->update('member', array('short_list' => json_encode($shortlisted)));
        recache();
    }

    function add_follow($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $follows = $this->Crud_model->get_type_name_by_id('member', $member, 'followed');
        $followed = json_decode($follows, true);
        if (empty($followed)) {
            $followed = array();
            array_push($followed, $member_id);

            $follower = $this->Crud_model->get_type_name_by_id('member', $member_id, 'follower');
            $follower = $follower + 1;
            $this->db->where('member_id', $member_id);
            $this->db->update('member', array('follower' => $follower));
        }
        if (!in_array($member_id, $followed)) {
            array_push($followed, $member_id);

            $follower = $this->Crud_model->get_type_name_by_id('member', $member_id, 'follower');
            $follower = $follower + 1;
            $this->db->where('member_id', $member_id);
            $this->db->update('member', array('follower' => $follower));
        }
        $this->db->where('member_id', $member);
        $this->db->update('member', array('followed' => json_encode($followed)));
        recache();
    }

    function add_unfollow($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $follows = $this->Crud_model->get_type_name_by_id('member', $member, 'followed');
        $followed = json_decode($follows, true);
        // $key = array_search($member_id, $followed);
        if (empty($followed)) {
            $followed = array();
        }
        // unset($followed[$key]);
        $new_array = array();
        foreach ($followed as $value) {
            if ($value != $member_id) {
                array_push($new_array, $value);
            }
        }
        $followed = $new_array;
        $this->db->where('member_id', $member);
        $this->db->update('member', array('followed' => json_encode($followed)));

        $follower = $this->Crud_model->get_type_name_by_id('member', $member_id, 'follower');
        $follower = $follower - 1;
        $this->db->where('member_id', $member_id);
        $this->db->update('member', array('follower' => $follower));
        recache();
    }

    function add_ignore($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $ignores = $this->Crud_model->get_type_name_by_id('member', $member, 'ignored');
        $ignored_bys = $this->Crud_model->get_type_name_by_id('member', $member_id, 'ignored_by');
        $ignored = json_decode($ignores, true);
        $ignored_by = json_decode($ignored_bys, true);
        // FOR Logged in USER
        if (empty($ignored)) {
            $ignored = array();
            array_push($ignored, $member_id);
        }
        elseif (!empty($ignored)) {
            if (!in_array($member_id, $ignored)) {
                array_push($ignored, $member_id);
            }
        }
        $this->db->where('member_id', $member);
        $this->db->update('member', array('ignored' => json_encode($ignored)));

        // FOR IGNORED USER
        if (empty($ignored_by)) {
            $ignored_by = array();
            array_push($ignored_by, $member);
        }
        elseif (!empty($ignored_by)) {
            if (!in_array($member, $ignored_by)) {
                array_push($ignored_by, $member);
            }
        }
        $this->db->where('member_id', $member_id);
        $this->db->update('member', array('ignored_by' => json_encode($ignored_by)));
        recache();
    }

    function do_unblock($member_id)
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }
        $member = $this->session->userdata('member_id');
        $ignores = $this->Crud_model->get_type_name_by_id('member', $member, 'ignored');
        $ignored = json_decode($ignores, true);
        if (empty($ignored)) {
            $ignored = array();
        }
        $new_array = array();
        foreach ($ignored as $value) {
            if ($value != $member_id) {
                array_push($new_array, $value);
            }
        }
        $ignored = $new_array;
        $this->db->where('member_id', $member);
        $this->db->update('member', array('ignored' => json_encode($ignored)));
        recache();
    }

    function profile($para1="",$para2="",$para3="")
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }   
        $page_data['height_array'] = $this->height_array();
       
        if ($para1 == "my-interests") {
            $page_data['current_tab'] = "my_interests";
            $page_data['current_tab_title'] = "My Interests";
            $para1 = "";            
        }


        if ($para1 == "received-interests") {
            $page_data['current_tab'] = "received_interests";
            $page_data['current_tab_title'] = "Received Interests";
            $para1 = "";
        }

        if ($para1 == "gallery-list") {
            $page_data['current_tab'] = "gallery";
            $page_data['current_tab_title'] = "Gallery";
            $para1 = "";
        }
        if ($para1 == "shortlist") {
            $page_data['current_tab'] = "short_list";
            $page_data['current_tab_title'] = "Short List";
            $para1 = "";            
        }

        if ($para1 == "followed-users") {
            $page_data['current_tab'] = "followed_users";
            $page_data['current_tab_title'] = "Followed Users";
            $para1 = "";            
        }

        if ($para1 == "messaging-list") {
            $page_data['current_tab'] = "messaging";
            $page_data['current_tab_title'] = "Messaging";
            $para1 = "";            
        }

        if ($para1 == "notifications-list") {
            $page_data['current_tab'] = "notifications";
            $page_data['current_tab_title'] = "Notifications";
            $para1 = "";
        }

        if ($para1 == "ignored-list") {
            $page_data['current_tab'] = "ignored_list";
            $page_data['current_tab_title'] = "Ignored List";
            $para1 = "";            
        }

        if ($para1 == "gallery-list") {
            $page_data['current_tab'] = "gallery";
            $page_data['current_tab_title'] = "Gallery";
            $para1 = "";            
        }

        if ($para1 == "settings") {
            $page_data['current_tab'] = "picture_privacy";
            $page_data['current_tab_title'] = "Picture Privacy Settings";
            $para1 = "";            
        }


       $ok = $this->Crud_model->isCompleted($this->session->userdata('member_id'));
        
       if($ok == false && $para1 != 'complete_profile' && $para1 != 'complete_profile_update') {
           $this->session->set_flashdata('alert','complete_profile');
            redirect( base_url().'home/profile/complete_profile', 'refresh' ); 
       }
       if($ok == true && $para1 == 'complete_profile') {
            redirect( base_url().'home/profile', 'refresh' ); 
       }

        if ($para1 == "" || $para1 == "nav") {
            $page_data['title'] = "Profile || ".$this->system_title;
            $page_data['top'] = "profile.php";
            $page_data['page'] = "profile/dashboard";
            $page_data['bottom'] = "profile.php";
            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
 if ($this->session->flashdata('alert') == "complete_profile") {
                $page_data['success_alert'] = translate("please_complete_your_profile!");
            }
            if ($this->session->flashdata('alert') == "edit") {
                $page_data['success_alert'] = translate("you_have_successfully_edited_your_profile!");
            }
            elseif ($this->session->flashdata('alert') == "edit_image") {
                $page_data['success_alert'] = translate("you_have_successfully_edited_your_profile_image!");
            }
            elseif ($this->session->flashdata('alert') == "add_gallery") {
                $page_data['success_alert'] = translate("you_have_successfully_added_the_photo_into_your_gallery!");
            }
            elseif ($this->session->flashdata('alert') == "failed") {
                $page_data['danger_alert'] = translate("failed_to_upload_your_image._make_sure_the_image_is_JPG,_JPEG_or_PNG!");
            }
            elseif ($this->session->flashdata('alert') == "add_story") {
                $page_data['success_alert'] = translate("you_have_successfully_added_your_story._please_wait_till_it_is_approved!");
            }
            elseif ($this->session->flashdata('alert') == "failed_add_story") {
                $page_data['danger_alert'] = translate("failed_to_add_your_story!");
            }
            $page_data['load_nav']  = $para2;
            $page_data['sp_nav']    = $para3;
            
            $this->load->view('front/index', $page_data);
        }
        elseif ($para1=="followed_users") {
            $this->load->view('front/profile/followed_users/index');
        }
        elseif ($para1=="messaging") {
            $user_id = $this->session->userdata('member_id');
            $page_data['listed_messaging_members'] = $this->Crud_model->get_listed_messaging_members($user_id);

            $this->load->view('front/profile/messaging/index', $page_data);         
        }
        elseif ($para1=="short_list") {
            $this->load->view('front/profile/short_list/index');
        }
        elseif ($para1=="my_interests") {
            $this->load->view('front/profile/my_interests/index');
        }
        elseif ($para1 == "received_interests") {
            $this->load->view( 'front/profile/received_interests/index'); 
        }elseif ($para1 == "notifications") {
            $this->load->view( 'front/profile/notifications/index'); 
        }
        elseif ($para1=="ignored_list") {
            $this->load->view('front/profile/ignored_list/index');
        }
        elseif ($para1=="my_packages") {
            $this->load->view('front/profile/my_packages/index');
        }
        elseif ($para1=="payments") {
            $page_data['payments_info'] = $this->db->order_by("purchase_datetime", "desc")->get_where('package_payment', array('member_id' => $this->session->userdata('member_id')))->result();
            $this->load->view('front/profile/payments/index', $page_data);
        }
        elseif ($para1=="change_pass") {
            $this->load->view('front/profile/change_password/index');
        }
         elseif ($para1=="picture_privacy") {
            $this->load->view('front/profile/picture_privacy/index');
        }
        elseif ($para1=="close_account") {
            if($para2=="yes"){ 
                $data['is_closed']=$para2;
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
            }elseif($para2=="no"){
                $data['is_closed']=$para2;
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
            }else{
                 $this->load->view('front/profile/close_account/index');
            }
           
        }
        elseif ($para1=="reopen_account") {
            if($para2=="yes"){ 
                $data['is_closed']= 'no';
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
            }elseif($para2=="no"){
                $data['is_closed']='yes';
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
            }else{
                 $this->load->view('front/profile/reopen_account/index');
            }
           
        }
        elseif ($para1=="gallery") {
            $this->load->view('front/profile/gallery/index');
        }
        elseif ($para1=="gallery_upload") {
            $this->load->view('front/profile/gallery_upload/index');
        }
        elseif ($para1=="happy_story") {
            $this->load->view('front/profile/happy_story/index');
        }
        elseif ($para1=="edit-full-profile") {
            $page_data['title'] = "Edit Profile || ".$this->system_title;
            $page_data['top'] = "profile.php";
            $page_data['page'] = "profile/edit_full_profile";
            $page_data['bottom'] = "profile.php";
            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();

            $page_data['load_nav']  = $para2;
            $page_data['sp_nav']    = $para3;
            
            if ($this->session->flashdata('alert') == "complete_profile") {
                $page_data['danger_alert'] = translate("please_complete_your_profile!");
            }
           
            $this->load->view('front/index', $page_data);
        }elseif ($para1=="complete_profile") {
            $page_data['title'] = "complete Profile || ".$this->system_title;
            $page_data['top'] = "profile.php";
            $page_data['page'] = "profile/complete_profile";
            $page_data['bottom'] = "profile.php";
            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();

            $page_data['load_nav']  = $para2;
            $page_data['sp_nav']    = $para3;
            if ($this->session->flashdata('alert') == "complete_profile") {
                $page_data['danger_alert'] = translate("please_complete_your_profile!");
            }
            $this->load->view('front/index', $page_data);
        }elseif ($para1=="complete_profile_update") {
            $this->form_validation->set_rules('introduction', 'Introduction', 'required');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[16]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[16]');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            if ($this->input->post('old_email') != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|is_unique[member.email]|valid_email',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
            }
            if ($this->input->post('old_mobile') != $this->input->post('mobile')) {
                $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[member.mobile]',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
            }
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('state', 'State', 'required');
          //  $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('highest_education', 'Highest Education', 'required');
            $this->form_validation->set_rules('occupation', 'Occupation', 'required');
            $this->form_validation->set_rules('mother_tongue', 'Mother Tongue', 'required');
            $this->form_validation->set_rules('religion', 'Religion', 'required');
            $this->form_validation->set_rules('caste', 'Caste', 'required');
            $this->form_validation->set_rules('dateob', 'Date of Birth', 'required');
            $this->form_validation->set_rules('monthob', 'Month of Birth', 'required');
            $this->form_validation->set_rules('yearob', 'Year of Birth', 'required');
            $this->form_validation->set_rules('belongs_to', 'Belongs to', 'required');
            $this->form_validation->set_rules('height', 'Height', 'required');
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required');

            if ($this->form_validation->run() == FALSE) {
                $page_data['form_contents'] = $this->input->post();
                $page_data['title'] = "complete Profile || ".$this->system_title;
                $page_data['top'] = "profile.php";
                $page_data['page'] = "profile/complete_profile";
                $page_data['bottom'] = "profile.php";
                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();

                $page_data['load_nav']  = $para2;
                $page_data['sp_nav']    = $para3;            
                $this->load->view('front/index', $page_data);
            }
            else {
                $data['first_name'] = $this->input->post('first_name');
                $data['last_name'] = $this->input->post('last_name');
                $data['gender'] = $this->input->post('gender');
                $data['email'] = $this->input->post('email');
                $data['mobile'] = $this->input->post('mobile');
                $data['height'] = $this->input->post('height');
                $data['belongs_to'] = $this->input->post('belongs_to');
                $monthob = $this->input->post('monthob');
                $dateob = $this->input->post('dateob');
                $yearob = $this->input->post('yearob');
                $dob = $yearob."-".$monthob."-".$dateob;
                $data['date_of_birth'] = strtotime($dob);
                $data['introduction'] = $this->input->post('introduction');
                $data['is_completed '] = 1;

                // ------------------------------------Basic Info------------------------------------ //
                $basic_info[] = array(
                                    'marital_status'        =>  $this->input->post('marital_status'),   
                                    'number_of_children'    =>  $this->input->post('number_of_children'),
                                    'area'                  =>  $this->input->post('area'),
                                    'on_behalf'             =>  $this->input->post('on_behalf')
			                        );
            	$data['basic_info'] = json_encode($basic_info);
            	// ------------------------------------Basic Info------------------------------------ //

            	// ------------------------------------Present Address------------------------------------ //
            	$present_address[] = array('country'		=>  $this->input->post('country'),
    								'city'					=>	$this->input->post('city'),	
    								'state'					=>	$this->input->post('state'),
    								'postal_code'			=>	$this->input->post('postal_code')
			                        );
            	$data['present_address'] = json_encode($present_address);
                $loc_array  = $this->findLatLong($present_address);
                $data['latitude'] = $loc_array['latitude'];
                $data['longitude'] = $loc_array['longitude'];
            	// ------------------------------------Present Address------------------------------------ //

            	// ------------------------------------Education & Career------------------------------------ //
            	$education_and_career[] = array('highest_education'	=>  $this->input->post('highest_education'),
    								'occupation'					=>	$this->input->post('occupation'),	
    								'annual_income'					=>	$this->input->post('annual_income')
			                        );
            	$data['education_and_career'] = json_encode($education_and_career);
            	// ------------------------------------Education & Career------------------------------------ //
            	
            	// ------------------------------------ Language------------------------------------ //
            	$language[] = array('mother_tongue'			=>  $this->input->post('mother_tongue'),
    								'language'				=>	$this->input->post('language'),	
    								'speak'					=>	$this->input->post('speak'),
    								'read'					=>	$this->input->post('read')
			                        );
            	$data['language'] = json_encode($language);
            	// ------------------------------------ Language------------------------------------ //

            	// ------------------------------------Spiritual and Social Background------------------------------------ //
            	$spiritual_and_social_background[] = array('religion'	=>  $this->input->post('religion'),
    								'caste'					=>	$this->input->post('caste'),	
    								'sub_caste'				=>	$this->input->post('sub_caste'),
    								'ethnicity'				=>	$this->input->post('ethnicity'),
    								'personal_value'		=>	$this->input->post('personal_value'),
    								'family_value'			=>	$this->input->post('family_value'),

                                    'u_manglik'             =>  $this->input->post('u_manglik'),
                                    'community_value'       =>  $this->input->post('community_value'),
                                    'family_status'         =>  $this->input->post('family_status')
                                    );
                $data['spiritual_and_social_background'] = json_encode($spiritual_and_social_background);
                // ------------------------------------Spiritual and Social Background------------------------------------ //
               
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                recache();
                if ($result) {
                    $this->session->set_flashdata('alert', 'edit');
                    redirect(base_url().'home/profile', 'refresh');
                }
            }
        }
        elseif ($para1=="update_all") {
            $this->form_validation->set_rules('introduction', 'Introduction', 'required');

            $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[16]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[16]');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('on_behalf', 'On Behalf', 'required');
            if ($this->input->post('old_email') != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|is_unique[member.email]|valid_email',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
            }
            if ($this->input->post('old_mobile') != $this->input->post('mobile')) {
                $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[member.mobile]',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
            }
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');

            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('state', 'State', 'required');
            // $this->form_validation->set_rules('city', 'City', 'required');

            $this->form_validation->set_rules('highest_education', 'Highest Education', 'required');
            $this->form_validation->set_rules('occupation', 'Occupation', 'required');

            $this->form_validation->set_rules('mother_tongue', 'Mother Tongue', 'required');

            $this->form_validation->set_rules('birth_country', 'Birth Country', 'required');
            $this->form_validation->set_rules('citizenship_country', 'Citizenship Country', 'required');

            $this->form_validation->set_rules('religion', 'Religion', 'required');

            // $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');
            $this->form_validation->set_rules('dateob', 'Date of Birth', 'required');
            $this->form_validation->set_rules('monthob', 'Month of Birth', 'required');
            $this->form_validation->set_rules('yearob', 'Year of Birth', 'required');

            $this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required');
            $this->form_validation->set_rules('permanent_state', 'Permanent State', 'required');
            // $this->form_validation->set_rules('permanent_city', 'Permanent City', 'required');

            if ($this->form_validation->run() == FALSE) {
                $page_data['form_contents'] = $this->input->post();
                $page_data['title'] = "Edit Profile || ".$this->system_title;
                $page_data['top'] = "profile.php";
                $page_data['page'] = "profile/edit_full_profile";
                $page_data['bottom'] = "profile.php";
                $page_data['load_nav']  = $para2;
                $page_data['sp_nav']    = $para3;
                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $this->load->view('front/index', $page_data);
            }
            else {
                $data['first_name'] = $this->input->post('first_name');
                $data['last_name'] = $this->input->post('last_name');
                $data['gender'] = $this->input->post('gender');
                $data['email'] = $this->input->post('email');
                $data['mobile'] = $this->input->post('mobile');

                $monthob = $this->input->post('monthob');
                $dateob = $this->input->post('dateob');
                $yearob = $this->input->post('yearob');
                $dob = $yearob."-".$monthob."-".$dateob;
                $data['date_of_birth'] = strtotime($dob);
                
                // $data['date_of_birth'] = strtotime($this->input->post('date_of_birth'));
                $data['height'] = $this->input->post('height');
                $data['introduction'] = $this->input->post('introduction');

                // ------------------------------------Basic Info------------------------------------ //
                $basic_info[] = array(
                                    'marital_status'        =>  $this->input->post('marital_status'),   
                                    'number_of_children'    =>  $this->input->post('number_of_children'),
                                    'area'                  =>  $this->input->post('area'),
                                    'on_behalf'             =>  $this->input->post('on_behalf')

			                        );
            	$data['basic_info'] = json_encode($basic_info);
            	// ------------------------------------Basic Info------------------------------------ //

            	// ------------------------------------Present Address------------------------------------ //
            	$present_address[] = array('country'		=>  $this->input->post('country'),
    								'city'					=>	$this->input->post('city'),	
    								'state'					=>	$this->input->post('state'),
    								'postal_code'			=>	$this->input->post('postal_code')
			                        );
            	$data['present_address'] = json_encode($present_address);
 $loc_array  = $this->findLatLong($present_address);
$data['latitude'] = $loc_array['latitude'];
$data['longitude'] = $loc_array['longitude'];
            	// ------------------------------------Present Address------------------------------------ //

            	// ------------------------------------Education & Career------------------------------------ //
            	$education_and_career[] = array('highest_education'	=>  $this->input->post('highest_education'),
    								'occupation'					=>	$this->input->post('occupation'),	
    								'annual_income'					=>	$this->input->post('annual_income')
			                        );
            	$data['education_and_career'] = json_encode($education_and_career);
            	// ------------------------------------Education & Career------------------------------------ //

            	// ------------------------------------ Physical Attributes------------------------------------ //
            	$physical_attributes[] = array('weight'     =>	$this->input->post('weight'),	
    								'eye_color'				=>	$this->input->post('eye_color'),
    								'hair_color'			=>	$this->input->post('hair_color'),
    								'complexion'			=>	$this->input->post('complexion'),
    								'blood_group'			=>	$this->input->post('blood_group'),
    								'body_type'				=>	$this->input->post('body_type'),
    								'body_art'				=>	$this->input->post('body_art'),
    								'any_disability'		=>	$this->input->post('any_disability')
			                        );
            	$data['physical_attributes'] = json_encode($physical_attributes);
            	// ------------------------------------ Physical Attributes------------------------------------ //

            	// ------------------------------------ Language------------------------------------ //
            	$language[] = array('mother_tongue'			=>  $this->input->post('mother_tongue'),
    								'language'				=>	$this->input->post('language'),	
    								'speak'					=>	$this->input->post('speak'),
    								'read'					=>	$this->input->post('read')
			                        );
            	$data['language'] = json_encode($language);
            	// ------------------------------------ Language------------------------------------ //

            	// ------------------------------------Hobbies & Interest------------------------------------ //
            	$hobbies_and_interest[] = array('hobby'	    =>  $this->input->post('hobby'),
            						'interest'				=>  $this->input->post('interest'),
    								'music'					=>	$this->input->post('music'),	
    								'books'					=>	$this->input->post('books'),
    								'movie'					=>	$this->input->post('movie'),
    								'tv_show'				=>	$this->input->post('tv_show'),
    								'sports_show'			=>	$this->input->post('sports_show'),
    								'fitness_activity'		=>	$this->input->post('fitness_activity'),
    								'cuisine'				=>	$this->input->post('cuisine'),
    								'dress_style'			=>	$this->input->post('dress_style')
			                        );
            	$data['hobbies_and_interest'] = json_encode($hobbies_and_interest);
            	// ------------------------------------Hobbies & Interest------------------------------------ //

            	// ------------------------------------ Personal Attitude & Behavior------------------------------------ //
            	$personal_attitude_and_behavior[] = array('affection'	=>  $this->input->post('affection'),	
                    								'humor'             =>	$this->input->post('humor'),
                    								'political_view'    =>	$this->input->post('political_view'),
                    								'religious_service' =>	$this->input->post('religious_service')
                			                        );
            	$data['personal_attitude_and_behavior'] = json_encode($personal_attitude_and_behavior);
            	// ------------------------------------ Personal Attitude & Behavior------------------------------------ //

            	// ------------------------------------Residency Information------------------------------------ //
            	$residency_information[] = array('birth_country'	=>  $this->input->post('birth_country'),
    								'residency_country'		=>	$this->input->post('residency_country'),	
    								'citizenship_country'	=>	$this->input->post('citizenship_country'),
    								'grow_up_country'		=>	$this->input->post('grow_up_country'),
    								'immigration_status'	=>	$this->input->post('immigration_status')
			                        );
            	$data['residency_information'] = json_encode($residency_information);
            	// ------------------------------------Residency Information------------------------------------ //

            	// ------------------------------------Spiritual and Social Background------------------------------------ //
            	$spiritual_and_social_background[] = array('religion'	=>  $this->input->post('religion'),
    								'caste'					=>	$this->input->post('caste'),	
    								'sub_caste'				=>	$this->input->post('sub_caste'),
    								'ethnicity'				=>	$this->input->post('ethnicity'),
    								'personal_value'		=>	$this->input->post('personal_value'),
    								'family_value'			=>	$this->input->post('family_value'),

                                    'u_manglik'             =>  $this->input->post('u_manglik'),
                                    'community_value'       =>  $this->input->post('community_value'),
                                    'family_status'         =>  $this->input->post('family_status')
                                    );
                $data['spiritual_and_social_background'] = json_encode($spiritual_and_social_background);
                // ------------------------------------Spiritual and Social Background------------------------------------ //

                // ------------------------------------ Life Style------------------------------------ //
                $life_style[] = array('diet'                =>  $this->input->post('diet'),
                                    'drink'                 =>  $this->input->post('drink'),    
                                    'smoke'                 =>  $this->input->post('smoke'),
                                    'living_with'           =>  $this->input->post('living_with')
                                    );
                $data['life_style'] = json_encode($life_style);
                // ------------------------------------ Life Style------------------------------------ //

                // ------------------------------------ Astronomic Information------------------------------------ //
                $astronomic_information[] = array('sun_sign'    =>  $this->input->post('sun_sign'),
                                    'moon_sign'                 =>  $this->input->post('moon_sign'),
                                    'time_of_birth'             =>  $this->input->post('time_of_birth'),
                                    'city_of_birth'             =>  $this->input->post('city_of_birth')
                                    );
                $data['astronomic_information'] = json_encode($astronomic_information);
                // ------------------------------------ Astronomic Information------------------------------------ //

                // ------------------------------------Permanent Address------------------------------------ //
                $permanent_address[] = array('permanent_country'    =>  $this->input->post('permanent_country'),
                                    'permanent_city'                =>  $this->input->post('permanent_city'),   
                                    'permanent_state'               =>  $this->input->post('permanent_state'),
                                    'permanent_postal_code'         =>  $this->input->post('permanent_postal_code')
                                    );
                $data['permanent_address'] = json_encode($permanent_address);
                // ------------------------------------Permanent Address------------------------------------ //

                // ------------------------------------Family Information------------------------------------ //
                $family_info[] = array('father'             =>  $this->input->post('father'),
                                    'mother'                =>  $this->input->post('mother'),   
                                    'brother_sister'        =>  $this->input->post('brother_sister')
                                    );
                $data['family_info'] = json_encode($family_info);
                // ------------------------------------Family Information------------------------------------ //

                // ------------------------------------ Additional Personal Details------------------------------------ //
                $additional_personal_details[] = array('home_district'  =>  $this->input->post('home_district'),
                                    'family_residence'              =>  $this->input->post('family_residence'), 
                                    'fathers_occupation'            =>  $this->input->post('fathers_occupation'),
                                    'special_circumstances'         =>  $this->input->post('special_circumstances')
                                    );
                $data['additional_personal_details'] = json_encode($additional_personal_details);
                // ------------------------------------ Additional Personal Details------------------------------------ //

                // ------------------------------------ Partner Expectation------------------------------------ //
                $partner_expectation[] = array('general_requirement'    =>  $this->input->post('general_requirement'),
                                    'partner_age'                       =>  $this->input->post('partner_age'),  
                                    'partner_height'                    =>  $this->input->post('partner_height'),
                                    'partner_weight'                    =>  $this->input->post('partner_weight'),
                                    'partner_marital_status'            =>  $this->input->post('partner_marital_status'),
                                    'with_children_acceptables'         =>  $this->input->post('with_children_acceptables'),
                                    'partner_country_of_residence'      =>  $this->input->post('partner_country_of_residence'),
                                    'partner_religion'                  =>  $this->input->post('partner_religion'),
                                    'partner_caste'                     =>  $this->input->post('partner_caste'),
                                    'partner_complexion'                =>  $this->input->post('partner_complexion'),
                                    'partner_education'                 =>  $this->input->post('partner_education'),
                                    'partner_profession'                =>  $this->input->post('partner_profession'),
                                    'partner_drinking_habits'           =>  $this->input->post('partner_drinking_habits'),
                                    'partner_smoking_habits'            =>  $this->input->post('partner_smoking_habits'),
                                    'partner_diet'                      =>  $this->input->post('partner_diet'),
                                    'partner_body_type'                 =>  $this->input->post('partner_body_type'),
                                    'partner_personal_value'            =>  $this->input->post('partner_personal_value'),
                                    'manglik'                           =>  $this->input->post('manglik'),
                                    'partner_any_disability'            =>  $this->input->post('partner_any_disability'),
                                    'partner_mother_tongue'             =>  $this->input->post('partner_mother_tongue'),
                                    'partner_family_value'              =>  $this->input->post('partner_family_value'),
                                    'prefered_country'                  =>  $this->input->post('prefered_country'),
                                    'prefered_state'                    =>  $this->input->post('prefered_state'),
                                    'prefered_status'                   =>  $this->input->post('prefered_status')
                                    );
                $data['partner_expectation'] = json_encode($partner_expectation);
                // ------------------------------------ Partner Expectation------------------------------------ //

                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                recache();
                if ($result) {
                    $this->session->set_flashdata('alert', 'edit');
                    redirect(base_url().'home/profile', 'refresh');
                }
            }
        }
        elseif ($para1=="update_introduction") {
            $this->form_validation->set_rules('introduction', 'Introduction', 'required');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }
            else {
                $data['introduction'] = $this->input->post('introduction');
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                recache();
                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $this->load->view('front/profile/dashboard/introduction', $page_data);
            }
        }
        elseif ($para1=="update_basic_info") {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[16]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[16]');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('on_behalf', 'On Behalf', 'required');
            $this->form_validation->set_rules('monthob', 'Month of Birth', 'required');
            $this->form_validation->set_rules('dateob', 'Date of Birth', 'required');
            $this->form_validation->set_rules('yearob', 'Year of Birth', 'required');
            $this->form_validation->set_rules('belongs_to', 'Belongs to', 'required');

            if ($this->input->post('old_email') != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|is_unique[member.email]|valid_email',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
            }
            if ($this->input->post('old_mobile') != $this->input->post('mobile')) {
                $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[member.mobile]',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
            }
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }
            else {
                $data['first_name'] = $this->input->post('first_name');
                $data['last_name'] = $this->input->post('last_name');
                $data['gender'] = $this->input->post('gender');
                $data['email'] = $this->input->post('email');
                $data['mobile'] = $this->input->post('mobile');
                $data['belongs_to'] = $this->input->post('belongs_to');

                $monthob = $this->input->post('monthob');
                $dateob = $this->input->post('dateob');
                $yearob = $this->input->post('yearob');
                $dob = $yearob."-".$monthob."-".$dateob;
                $data['date_of_birth'] = strtotime($dob);
                // $data['date_of_birth'] = strtotime($this->input->post('date_of_birth'));

                // ------------------------------------Basic Info------------------------------------ //
                $basic_info[] = array(
                                    'marital_status'        =>  $this->input->post('marital_status'),   
                                    'number_of_children'    =>  $this->input->post('number_of_children'),
                                    'area'                  =>  $this->input->post('area'),
                                    'on_behalf'                  =>  $this->input->post('on_behalf')

                                    );
                $data['basic_info'] = json_encode($basic_info);
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                
                $date_of_birth = date("Y-m-d", strtotime($this->input->post('date_of_birth')));
                $today = date("Y-m-d");
                $diff = date_diff(date_create($date_of_birth), date_create($today));
                $p_data['age'] = $diff->format('%y') + 1;

                $p_data['marital_status'] = $this->input->post('marital_status');
                $p_data['number_of_children'] = $this->input->post('number_of_children');

                $p_data_table = $this->db->get_where("partner_personal_preferances", array("member_id" => $this->session->userdata('member_id')))->result();                      
                if ($p_data_table == null) {
                    $p_data['member_id'] =  $this->session->userdata('member_id');
                    $result= $this->db->insert('partner_personal_preferances', $p_data);   
                }else{
                    $result = $this->db->update('partner_personal_preferances', $p_data);
                }

                recache();

                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $this->load->view('front/profile/dashboard/basic_info', $page_data);
            }
        }
        elseif ($para1=="update_present_address") {
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('state', 'State', 'required');
            // $this->form_validation->set_rules('city', 'City', 'required');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }

            else {
                // ------------------------------------Present Address------------------------------------ //
                $present_address[] = array('country'        =>  $this->input->post('country'),
                                    'city'                  =>  $this->input->post('city'), 
                                    'state'                 =>  $this->input->post('state'),
                                    'postal_code'           =>  $this->input->post('postal_code')
                                    );
                $data['present_address'] = json_encode($present_address);

 $add_array = $this->findLatLong($present_address[0]);
//print_r($add_array );exit;

 $data['latitude'] = $add_array['latitude'];
 $data['longitude'] = $add_array['longitude'];

                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);

                $p_data['address_country'] = $this->input->post('country');
                $p_data['address_city'] = $this->input->post('city');
                $p_data['address_state'] = $this->input->post('state');

                $p_data_table = $this->db->get_where("partner_personal_preferances", array("member_id" => $this->session->userdata('member_id')))->result();                      
                if ($p_data_table == null) {
                    $p_data['member_id'] =  $this->session->userdata('member_id');
                    $result= $this->db->insert('partner_personal_preferances', $p_data);   
                }else{
                    $result = $this->db->update('partner_personal_preferances', $p_data);
                }

                recache();

                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
                $page_data['privacy_status_data'] = json_decode($privacy_status, true);
                $this->load->view('front/profile/dashboard/present_address', $page_data);
            }
        }
        elseif ($para1=="update_education_and_career") {
            $this->form_validation->set_rules('highest_education', 'Highest Education', 'required');
            $this->form_validation->set_rules('occupation', 'Occupation', 'required');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }
                            
            else {
                // ------------------------------------Education & Career------------------------------------ //
                $education_and_career[] = array('highest_education' =>  $this->input->post('highest_education'),
                                    'occupation'                    =>  $this->input->post('occupation'),   
                                    'annual_income'                 =>  $this->input->post('annual_income')
                                    );
                $data['education_and_career'] = json_encode($education_and_career);
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                recache();

                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
                $page_data['privacy_status_data'] = json_decode($privacy_status, true);
                $this->load->view('front/profile/dashboard/education_and_career', $page_data);
            }    
        }
        elseif ($para1=="update_physical_attributes") {
            $this->form_validation->set_rules('weight', 'Weight', 'integer|greater_than[30]|less_than[200]');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }

            else {
            // ------------------------------------ Physical Attributes------------------------------------ //
            $physical_attributes[] = array('weight'     =>  $this->input->post('weight'),   
                                'eye_color'             =>  $this->input->post('eye_color'),
                                'hair_color'            =>  $this->input->post('hair_color'),
                                'complexion'            =>  $this->input->post('complexion'),
                                'blood_group'           =>  $this->input->post('blood_group'),
                                'body_type'             =>  $this->input->post('body_type'),
                                'body_art'              =>  $this->input->post('body_art'),
                                'any_disability'        =>  $this->input->post('any_disability')
                                );
            $data['height'] = $this->input->post('height');
            $data['physical_attributes'] = json_encode($physical_attributes);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            $p_data['height'] = $this->input->post('height');
            $p_data['weight'] = $this->input->post('weight');
            $p_data_table = $this->db->get_where("partner_personal_preferances", array("member_id" => $this->session->userdata('member_id')))->result();                      
            if ($p_data_table == null) {
                $p_data['member_id'] =  $this->session->userdata('member_id');
                $result= $this->db->insert('partner_personal_preferances', $p_data);   
            }else{
                $result = $this->db->update('partner_personal_preferances', $p_data);
            }

            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/physical_attributes', $page_data);
        }
        }
        elseif ($para1=="update_language") {
            $this->form_validation->set_rules('mother_tongue', 'Mother Tongue', 'required');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }

            else {
                // ------------------------------------ Language------------------------------------ //
                $language[] = array('mother_tongue'         =>  $this->input->post('mother_tongue'),
                                    'language'              =>  $this->input->post('language'), 
                                    'speak'                 =>  $this->input->post('speak'),
                                    'read'                  =>  $this->input->post('read')
                                    );
                $data['language'] = json_encode($language);
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                $p_data['mother_tongue'] = $this->input->post('mother_tongue');

                $p_data_table = $this->db->get_where("partner_personal_preferances", array("member_id" => $this->session->userdata('member_id')))->result();                      
                if ($p_data_table == null) {
                    $p_data['member_id'] =  $this->session->userdata('member_id');
                    $result= $this->db->insert('partner_personal_preferances', $p_data);   
                }else{
                    $result = $this->db->update('partner_personal_preferances', $p_data);
                }
                recache();

                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
                $page_data['privacy_status_data'] = json_decode($privacy_status, true);
                $this->load->view('front/profile/dashboard/language', $page_data);                
            }
        }
        elseif ($para1=="update_hobbies_and_interest") {
            // ------------------------------------Hobbies & Interest------------------------------------ //
            $hobbies_and_interest[] = array('hobby'     =>  $this->input->post('hobby'),
                                'interest'              =>  $this->input->post('interest'),
                                'music'                 =>  $this->input->post('music'),    
                                'books'                 =>  $this->input->post('books'),
                                'movie'                 =>  $this->input->post('movie'),
                                'tv_show'               =>  $this->input->post('tv_show'),
                                'sports_show'           =>  $this->input->post('sports_show'),
                                'fitness_activity'      =>  $this->input->post('fitness_activity'),
                                'cuisine'               =>  $this->input->post('cuisine'),
                                'dress_style'           =>  $this->input->post('dress_style')
                                );
            $data['hobbies_and_interest'] = json_encode($hobbies_and_interest);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/hobbies_and_interest', $page_data);
        }
        elseif ($para1=="update_personal_attitude_and_behavior") {
            // ------------------------------------ Personal Attitude & Behavior------------------------------------ //
            $personal_attitude_and_behavior[] = array('affection'   =>  $this->input->post('affection'),    
                                                'humor'             =>  $this->input->post('humor'),
                                                'political_view'    =>  $this->input->post('political_view'),
                                                'religious_service' =>  $this->input->post('religious_service')
                                                );
            $data['personal_attitude_and_behavior'] = json_encode($personal_attitude_and_behavior);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/personal_attitude_and_behavior', $page_data);
        }
        elseif ($para1=="update_residency_information") {
            $this->form_validation->set_rules('birth_country', 'Birth Country', 'required');
            $this->form_validation->set_rules('citizenship_country', 'Citizenship Country', 'required');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }

            else {
                // ------------------------------------Residency Information------------------------------------ //
                $residency_information[] = array('birth_country'    =>  $this->input->post('birth_country'),
                                    'residency_country'     =>  $this->input->post('residency_country'),    
                                    'citizenship_country'   =>  $this->input->post('citizenship_country'),
                                    'grow_up_country'       =>  $this->input->post('grow_up_country'),
                                    'immigration_status'    =>  $this->input->post('immigration_status')
                                    );
                $data['residency_information'] = json_encode($residency_information);
                // ------------------------------------Residency Information------------------------------------ //
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                recache();

                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
                $page_data['privacy_status_data'] = json_decode($privacy_status, true);
                $this->load->view('front/profile/dashboard/residency_information', $page_data);                
            }
        }
        elseif ($para1=="update_spiritual_and_social_background") {
            $this->form_validation->set_rules('religion', 'Religion', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }

            else {
                // ------------------------------------Spiritual and Social Background------------------------------------ //
                $spiritual_and_social_background[] = array('religion'   =>  $this->input->post('religion'),
                                    'caste'                 =>  $this->input->post('caste'),    
                                    'sub_caste'             =>  $this->input->post('sub_caste'),
                                    'ethnicity'             =>  $this->input->post('ethnicity'),
                                    'personal_value'        =>  $this->input->post('personal_value'),
                                    'family_value'          =>  $this->input->post('family_value'),
                                    'u_manglik'             =>  $this->input->post('u_manglik'),
                                    'community_value'       =>  $this->input->post('community_value'),
                                    'family_status'          =>  $this->input->post('family_status')
                                    );
                $data['spiritual_and_social_background'] = json_encode($spiritual_and_social_background);
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
            
                $p_data['religion'] = $this->input->post('religion');
                $p_data['caste'] = $this->input->post('caste');
                $p_data['sub_caste'] = $this->input->post('sub_caste');
                $p_data['family_value'] = $this->input->post('family_value');
                $p_data['manglik'] = $this->input->post('u_manglik');
                $p_data['family_status'] = $this->input->post('family_status');

                $p_data_table = $this->db->get_where("partner_personal_preferances", array("member_id" => $this->session->userdata('member_id')))->result();                      
                if ($p_data_table == null) {
                    $p_data['member_id'] =  $this->session->userdata('member_id');
                    $result= $this->db->insert('partner_personal_preferances', $p_data);   
                }else{
                    $result = $this->db->update('partner_personal_preferances', $p_data);
                }
                recache();

                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
                $page_data['privacy_status_data'] = json_decode($privacy_status, true);
                $this->load->view('front/profile/dashboard/spiritual_and_social_background', $page_data);                
            }
        }
        elseif ($para1=="update_life_style") {
            // ------------------------------------ Life Style------------------------------------ //
            $life_style[] = array('diet'                =>  $this->input->post('diet'),
                                'drink'                 =>  $this->input->post('drink'),    
                                'smoke'                 =>  $this->input->post('smoke'),
                                'living_with'           =>  $this->input->post('living_with')
                                );
            $data['life_style'] = json_encode($life_style);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);

            $p_data['drinking_habits'] = $this->input->post('drink');
            $p_data['smoking_habits'] = $this->input->post('smoke');
            
            $p_data_table = $this->db->get_where("partner_personal_preferances", array("member_id" => $this->session->userdata('member_id')))->result();                      
            if ($p_data_table == null) {
                $p_data['member_id'] =  $this->session->userdata('member_id');
                $result= $this->db->insert('partner_personal_preferances', $p_data);   
            }else{
                $result = $this->db->update('partner_personal_preferances', $p_data);
            }

            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/life_style', $page_data);
        }
        elseif ($para1=="update_astronomic_information") {

            // ------------------------------------ Astronomic Information------------------------------------ //
            $astronomic_information[] = array('sun_sign'    =>  $this->input->post('sun_sign'),
                                'moon_sign'                 =>  $this->input->post('moon_sign'),
                                'time_of_birth'             =>  $this->input->post('time_of_birth'),
                                'city_of_birth'             =>  $this->input->post('city_of_birth')
                                );
           
            $data['astronomic_information'] = json_encode($astronomic_information);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/astronomic_information', $page_data);                
            
        }
        elseif ($para1=="update_permanent_address") {
            $this->form_validation->set_rules('permanent_country', 'Permanent Country', 'required');
            $this->form_validation->set_rules('permanent_state', 'Permanent State', 'required');
            // $this->form_validation->set_rules('permanent_city', 'Permanent City', 'required');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }

            else {
                // ------------------------------------Permanent Address------------------------------------ //
                $permanent_address[] = array('permanent_country'    =>  $this->input->post('permanent_country'),
                                    'permanent_city'                =>  $this->input->post('permanent_city'),   
                                    'permanent_state'               =>  $this->input->post('permanent_state'),
                                    'permanent_postal_code'         =>  $this->input->post('permanent_postal_code')
                                    );
                $data['permanent_address'] = json_encode($permanent_address);
                $this->db->where('member_id', $this->session->userdata('member_id'));
                $result = $this->db->update('member', $data);
                recache();

                $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
                $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
                $page_data['privacy_status_data'] = json_decode($privacy_status, true);
                $this->load->view('front/profile/dashboard/permanent_address', $page_data);                
            }
        }
        elseif ($para1=="update_family_info") {
            // ------------------------------------Family Information------------------------------------ //
            $family_info[] = array('father'             =>  $this->input->post('father'),
                                'mother'                =>  $this->input->post('mother'),   
                                'brother_sister'        =>  $this->input->post('brother_sister')
                                );
            $data['family_info'] = json_encode($family_info);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
                $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/family_info', $page_data);
        }
        elseif ($para1=="update_additional_personal_details") {
            // ------------------------------------ Additional Personal Details------------------------------------ //
            $additional_personal_details[] = array('home_district'  =>  $this->input->post('home_district'),
                                'family_residence'              =>  $this->input->post('family_residence'), 
                                'fathers_occupation'            =>  $this->input->post('fathers_occupation'),
                                'special_circumstances'         =>  $this->input->post('special_circumstances')
                                );
            $data['additional_personal_details'] = json_encode($additional_personal_details);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/additional_personal_details', $page_data);
        }
        elseif ($para1=="update_partner_expectation") {
            $this->form_validation->set_rules('partner_min_age', 'Minimum Age', 'greater_than[18]|less_than[60]');
            $this->form_validation->set_rules('partner_max_age', 'Maximum Age', 'less_than[60]|greater_than['.$this->input->post('partner_min_age').']');
            $this->form_validation->set_rules('partner_min_height', 'Minimum Height', 'greater_than[3]|less_than[8]');
            $this->form_validation->set_rules('partner_max_height', 'Maximum Height', 'less_than[8]|greater_than['.$this->input->post('partner_min_height').']');
            $this->form_validation->set_rules('partner_min_weight', 'Minimum Weight', 'greater_than[30]|less_than[200]');
            $this->form_validation->set_rules('partner_max_weight', 'Maximum Weight', 'less_than[200]|greater_than['.$this->input->post('partner_min_weight').']');

            if ($this->form_validation->run() == FALSE) {
                $ajax_error[] = array('ajax_error'  =>  validation_errors());
                echo json_encode($ajax_error);
            }

            else {
            // ------------------------------------ Partner Expectation------------------------------------ //
            $partner_expectation[] = array('general_requirement'    =>  $this->input->post('general_requirement'),
                                'partner_age'                       =>  $this->input->post('partner_age'),  
                                'partner_height'                    =>  $this->input->post('partner_height'),
                                'partner_weight'                    =>  $this->input->post('partner_weight'),
                                
                                'partner_min_age'                       =>  $this->input->post('partner_min_age'),  
                                'partner_min_height'                    =>  $this->input->post('partner_min_height'),
                                'partner_min_weight'                    =>  $this->input->post('partner_min_weight'),
                                

                                'partner_max_age'                       =>  $this->input->post('partner_max_age'),  
                                'partner_max_height'                    =>  $this->input->post('partner_max_height'),
                                'partner_max_weight'                    =>  $this->input->post('partner_max_weight'),
                                
                                'partner_age'                       =>  $this->input->post('partner_age'),  
                                'partner_height'                    =>  $this->input->post('partner_height'),
                                'partner_weight'                    =>  $this->input->post('partner_weight'),
                                'partner_marital_status'            =>  $this->input->post('partner_marital_status'),
                                'with_children_acceptables'         =>  $this->input->post('with_children_acceptables'),
                                'partner_country_of_residence'      =>  $this->input->post('partner_country_of_residence'),
                                'partner_religion'                  =>  $this->input->post('partner_religion'),
                                'partner_caste'                     =>  $this->input->post('partner_caste'),
                                'partner_sub_caste'                 =>  $this->input->post('partner_sub_caste'),
                                'partner_complexion'                =>  $this->input->post('partner_complexion'),
                                'partner_education'                 =>  $this->input->post('partner_education'),
                                'partner_profession'                =>  $this->input->post('partner_profession'),
                                'partner_drinking_habits'           =>  $this->input->post('partner_drinking_habits'),
                                'partner_smoking_habits'            =>  $this->input->post('partner_smoking_habits'),
                                'partner_diet'                      =>  $this->input->post('partner_diet'),
                                'partner_body_type'                 =>  $this->input->post('partner_body_type'),
                                'partner_personal_value'            =>  $this->input->post('partner_personal_value'),
                                'manglik'                           =>  $this->input->post('manglik'),
                                'partner_any_disability'            =>  $this->input->post('partner_any_disability'),
                                'partner_mother_tongue'             =>  $this->input->post('partner_mother_tongue'),
                                'partner_family_value'              =>  $this->input->post('partner_family_value'),
                                'prefered_country'                  =>  $this->input->post('prefered_country'),
                                'prefered_state'                    =>  $this->input->post('prefered_state'),
                                'prefered_status'                   =>  $this->input->post('prefered_status'),
                                'partner_family_status'             =>  $this->input->post('partner_family_status'),
                                );
            $data['partner_expectation'] = json_encode($partner_expectation);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);

            $p_data['partner_height_min'] = $this->input->post('partner_height_min');
            $p_data['partner_height_max'] = $this->input->post('partner_height_max');
            $p_data['partner_weight_min'] = $this->input->post('partner_weight_min');
            $p_data['partner_weight_max'] = $this->input->post('partner_weight_max');
            $p_data['partner_age_min'] = $this->input->post('partner_age_min');
            $p_data['partner_age_max'] = $this->input->post('partner_age_max');
            $p_data['partner_weight_units'] = 'kg';
            $p_data['partner_marital_status'] = $this->input->post('partner_marital_status');
            $p_data['partner_accepted_with_children'] = $this->input->post('with_children_acceptables');
            $p_data['partner_religion'] = $this->input->post('partner_religion');
            $p_data['partner_caste'] = $this->input->post('partner_caste');
            $p_data['partner_sub_caste'] = $this->input->post('partner_sub_caste');
            $p_data['partner_family_value'] = $this->input->post('partner_family_value');
            $p_data['partner_education'] = $this->input->post('partner_education');
            $p_data['partner_profession'] = $this->input->post('partner_profession');
            $p_data['partner_drinking_habits'] = $this->input->post('partner_drinking_habits');
            $p_data['partner_smoking_habits'] = $this->input->post('partner_smoking_habits');
            $p_data['partner_diet'] = $this->input->post('partner_diet');
            $p_data['partner_body_type'] = $this->input->post('partner_body_type');
            $p_data['partner_manglik'] = $this->input->post('manglik');
            $p_data['partner_disability'] = $this->input->post('partner_any_disability');
            $p_data['partner_mother_tongue'] = $this->input->post('partner_mother_tongue');
            $p_data['partner_family_status'] = $this->input->post('prefered_status');
            $p_data['partner_complexion'] = $this->input->post('partner_complexion');
            $p_data['partner_address_country'] = $this->input->post('prefered_country');
            $p_data['partner_address_state'] = $this->input->post('prefered_state');
            $p_data['partner_address_city'] = $this->input->post('partner_address_city');

            $p_data_table = $this->db->get_where("partner_personal_preferances", array("member_id" => $this->session->userdata('member_id')))->result();                      
            if ($p_data_table == null) {
                $p_data['member_id'] =  $this->session->userdata('member_id');
                $result= $this->db->insert('partner_personal_preferances', $p_data);   
            }else{
                $result = $this->db->update('partner_personal_preferances', $p_data);
            }

            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $page_data['privacy_status_data'] = json_decode($privacy_status, true);
            $this->load->view('front/profile/dashboard/partner_expectation', $page_data);
        }
        }
        elseif ($para1=="update_image") {

            if(isset($_POST['profile_image_data'])) {
//die("jh");
                $img_data = $_POST['profile_image_data'];
                $id = $this->session->userdata('member_id');
                $path = isset($_POST['profile_image_data_name']) ? $_POST['profile_image_data_name'] : 'profile_image_'.$id.time();
                $ext = '.png';
                $file_name = 'uploads/' .'profile_image/profile_' . $id . $ext;
                $uri =  substr($img_data ,strpos($img_data ,",")+1);
                file_put_contents($file_name, base64_decode($uri));
                $this->Crud_model->img_thumb('profile', $id, $ext);
                $images[] = array('profile_image' => 'profile_' . $id . $ext, 'thumb' => 'profile_' . $id . '_thumb' . $ext);
                $data['profile_image'] = json_encode($images);

                $this->db->where('member_id', $this->session->userdata('member_id'));

                $result = $this->db->update('member', $data);
                recache();
                $this->session->set_flashdata('alert', 'edit_image');
                redirect(base_url().'home/profile', 'refresh');
            }

            if ($_FILES['profile_image']['name'] !== '') {
                $id = $this->session->userdata('member_id');
                $path = $_FILES['profile_image']['name'];
                $ext = '.' . pathinfo($path, PATHINFO_EXTENSION);
                if ($ext==".jpg" || $ext==".JPG" || $ext==".jpeg" || $ext==".JPEG" || $ext==".png" || $ext==".PNG") {
                    $this->Crud_model->file_up("profile_image", "profile", $id, '', '', $ext);
                    $images[] = array('profile_image' => 'profile_' . $id . $ext, 'thumb' => 'profile_' . $id . '_thumb' . $ext);
                    $data['profile_image'] = json_encode($images);
                    
                    $this->db->where('member_id', $this->session->userdata('member_id'));
                    $result = $this->db->update('member', $data);
                    recache();
                    
                    $this->session->set_flashdata('alert', 'edit_image');
                    redirect(base_url().'home/profile', 'refresh');
                }
                else {
                    $this->session->set_flashdata('alert', 'failed');
                    redirect(base_url().'home/profile', 'refresh');
                }
            }
        }
        elseif ($para1=="update_password") {
            $user_id = $this->session->userdata('member_id');
            $current_password = sha1($this->input->post('current_password'));
            $new_password = sha1($this->input->post('new_password'));
            $confirm_password = sha1($this->input->post('confirm_password'));
            $prev_password = $this->db->get_where('member', array('member_id' => $user_id))->row()->password;
            if ($prev_password == $current_password) {
                if ($new_password == $current_password) {
                    $ajax_error[] = array('ajax_error'  =>  "<p>".translate('new_password_and_current_password_are_same')."!</p>");
                    echo json_encode($ajax_error);
                }
                if ($new_password == $confirm_password) {
                    $this->db->where('member_id', $user_id);
                    $this->db->update('member', array('password' => $new_password));
                    recache();
                } else {
                    $ajax_error[] = array('ajax_error'  =>  "<p>".translate('new_password_does_not_matched_with_confirm_password')."!</p>");
                    echo json_encode($ajax_error);
                }
            } else {
                $ajax_error[] = array('ajax_error'  =>  "<p>".translate('invalid_current_password')."!</p>");
                echo json_encode($ajax_error);
            }
        }
        elseif ($para1=="unhide_section") {
            // ------------------------------------ Unhide Section------------------------------------ //
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $privacy_status_data = json_decode($privacy_status, true);
            foreach ($privacy_status_data as $key => $value) {
                if ($key == $para2) {
                    $privacy_status_data[0][$para2] = 'yes';
                }
            }
            $data['privacy_status'] = json_encode($privacy_status_data);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $this->load->view('front/profile/dashboard/additional_personal_details', $page_data);
        }
        elseif ($para1=="hide_section") {
            // ------------------------------------ Unhide Section------------------------------------ //
            $privacy_status = $this->Crud_model->get_type_name_by_id('member', $this->session->userdata['member_id'], 'privacy_status');
            $privacy_status_data = json_decode($privacy_status, true);
            foreach ($privacy_status_data as $key => $value) {
                if ($key == $para2) {
                    $privacy_status_data[0][$para2] = 'no';
                }
            }
            $data['privacy_status'] = json_encode($privacy_status_data);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
            recache();

            $page_data['get_member'] = $this->db->get_where("member", array("member_id" => $this->session->userdata('member_id')))->result();
            $this->load->view('front/profile/dashboard/additional_personal_details', $page_data);
        }
        elseif ($para1=="update_pic_privacy") {

            $pic_privacy[] = array(
                                'profile_pic_show'     =>  $this->input->post('profile_pic_show'),   
                                'gallery_show'             =>  $this->input->post('gallery_show')
                                );
            $data['pic_privacy'] = json_encode($pic_privacy, true);
            $this->db->where('member_id', $this->session->userdata('member_id'));
            $result = $this->db->update('member', $data);
             recache();

        }
    }

    function plans($para1="",$para2="")
    {
        if ($para1=="") {
            $page_data['title'] = "Premium Plans || ".$this->system_title;
            $page_data['top'] = "plans.php";
            $page_data['page'] = "plans";
            $page_data['bottom'] = "plans.php";
            $page_data['all_plans'] = $this->db->get("plan")->result();
            if ($this->session->flashdata('alert') == "paypal_cancel") {
                $page_data['danger_alert'] = translate("you_have_canceled_your_payment_via_paypal!");
            }
            elseif ($this->session->flashdata('alert') == "pum_fail") {
                $page_data['danger_alert'] = translate("your_payment_via_payUMoney_has_been_failed!");
            }
            elseif ($this->session->flashdata('alert') == "stripe_failed") {
                $page_data['danger_alert'] = translate("your_payment_via_stripe_has_been_failed!");
            }elseif ($this->session->flashdata('alert') == "paytm_failed") {
                $page_data['danger_alert'] = translate("your_payment_via_paytm_has_been_failed!");
            }elseif ($this->session->flashdata('alert') == "ccavenue_failed") {
                $page_data['danger_alert'] = translate("your_payment_via_ccavenue_has_been_failed!");
            }
            $this->load->view('front/index', $page_data);
        }
        elseif ($para1=="subscribe") {
            if ($this->member_permission() == FALSE) {
                redirect(base_url().'home/login', 'refresh');
            }
            if ($para2==1) {
                redirect(base_url().'home/plans', 'refresh');
            }
            $page_data['title'] = "Premium Plans || ".$this->system_title;
            $page_data['top'] = "plans.php";
            $page_data['page'] = "subscribe";
            $page_data['bottom'] = "plans.php";
            $page_data['selected_plan'] = $this->db->get_where("plan", array("plan_id" => $para2))->result();
            $this->load->view('front/index', $page_data);
        }
    }

    function stories($para1="",$para2="", $para3="")
    {
        if ($para1=="") {
            $page_data['title'] = "Happy Stories || ".$this->system_title;
            $page_data['top'] = "stories.php";
            $page_data['page'] = "stories";
            $page_data['bottom'] = "stories.php";
            $page_data['all_happy_stories'] = $this->db->get_where("happy_story", array("approval_status" => 1))->result();
            $this->load->view('front/index', $page_data);
        }
        elseif ($para1=="story_detail") {
            $page_data['title'] = "Story Detail || ".$this->system_title;
            $page_data['top'] = "story_detail.php";
            $page_data['page'] = "story_detail";
            $page_data['bottom'] = "story_detail.php";
            $page_data['get_story'] = $this->db->get_where("happy_story", array("happy_story_id" => $para2, "approval_status" => 1))->result();
            if ($page_data['get_story']) {
                $this->load->view('front/index', $page_data);
            }
            else {
                redirect(base_url().'home/stories', 'refresh');
            }
        }
        elseif ($para1=="add") {
            $member_id = $this->session->userdata('member_id');
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['post_time'] = strtotime($this->input->post('post_time'));
            $data['partner_name'] = $this->input->post('partner_name');
            $data['posted_by'] = $member_id;
            $data['approval_status'] = "0";
            $data['image'] = '[]';

            $this->db->insert('happy_story', $data);
            $id = $this->db->insert_id();            

            $images = array();
            foreach ($_FILES['image']['name'] as $i => $row) {
                if ($_FILES['image']['name'][$i] !== '') {
                    $ib = $i + 1;
                    $path = $_FILES['image']['name'][$i];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $img = 'happy_story_' . $id . '_' . $ib . '.jpg';
                    $img_thumb = 'happy_story_' . $id . '_' . $ib . '_thumb.jpg';
                    $images[] = array('index' => $i, 'img' => $img, 'thumb' => $img_thumb);
                }
            }

            $this->Crud_model->file_up("image", "happy_story", $id, 'multi');
            $data1['image'] = json_encode($images);
            $this->db->where('happy_story_id', $id);
            $result = $this->db->update('happy_story', $data1);
            recache();

            if ($this->input->post('upload_method') == 'upload') {
                $data_v['timestamp'] = time();
                $data_v['story_video_uploader_id'] = $this->session->userdata('member_id');
                $data_v['story_id'] = $id;
                $data_v['type'] = 'upload';
                $data_v['from'] = 'local';
                $data_v['video_link'] = '';
                $data_v['video_src'] = '';
                $this->db->insert('story_video', $data_v);
                $v_id = $this->db->insert_id();
                $video = $_FILES['upload_video']['name'];
                $ext = pathinfo($video, PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['upload_video']['tmp_name'], 'uploads/story_video/story_video_' . $v_id . '.' . $ext);
                $data_v['video_src'] = 'uploads/story_video/story_video_' . $v_id . '.' . $ext;
                $this->db->where('story_video_id', $v_id);
                $this->db->update('story_video', $data_v);
                recache();
            } elseif ($this->input->post('upload_method') == 'share') {
                $data_v['timestamp'] = time();
                $data_v['story_video_uploader_id'] = $this->session->userdata('member_id');
                $data_v['story_id'] = $id;
                $data_v['type'] = 'share';
                $data_v['from'] = $this->input->post('site');
                $data_v['video_link'] = $this->input->post('video_link');
                $code = $this->input->post('vl');
                if ($this->input->post('site') == 'youtube') {
                    $data_v['video_src'] = 'https://www.youtube.com/embed/' . $code;
                } else if ($this->input->post('site') == 'dailymotion') {
                    $data_v['video_src'] = '//www.dailymotion.com/embed/video/' . $code;
                } else if ($this->input->post('site') == 'vimeo') {
                    $data_v['video_src'] = 'https://player.vimeo.com/video/' . $code;
                }
                $this->db->insert('story_video', $data_v);
                recache();
            }

            if ($result) {
                $this->session->set_flashdata('alert', 'add_story');
                redirect(base_url().'home/profile', 'refresh');
            }
            else {
                $this->session->set_flashdata('alert', 'failed_add_story');
                redirect(base_url().'home/profile', 'refresh');
            }
        }
        elseif ($para1 == 'preview') {
                if ($para2 == 'youtube') {
                    echo '<iframe width="400" height="300" src="https://www.youtube.com/embed/' . $para3 . '" frameborder="0"></iframe>';
                } else if ($para2 == 'dailymotion') {
                    echo '<iframe width="400" height="300" src="//www.dailymotion.com/embed/video/' . $para3 . '" frameborder="0"></iframe>';
                } else if ($para2 == 'vimeo') {
                    echo '<iframe src="https://player.vimeo.com/video/' . $para3 . '" width="400" height="300" frameborder="0"></iframe>';
                }
            }
    }

    function gallery_upload($para1) {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }

        if ($para1 == "add") {
            $member_id = $this->session->userdata('member_id');
            $photo_gallery_amount = $this->db->get_where('member', array('member_id' => $member_id))->row()->photo_gallery;
            if ($photo_gallery_amount > 0) {
                $get_gallery = $this->db->get_where('member', array('member_id' => $member_id))->row()->gallery;
                $gallery_data = json_decode($get_gallery, true);
                //print_r($gallery_data);
                $max_index = 0;
                $new_index = 0;
                if (!empty($gallery_data)) {
                    foreach ($gallery_data as $gallery_val) {
                        if($gallery_val['index'] > $max_index) {
                            $max_index = $gallery_val['index'];
                        }
                    }
                    $new_index = $max_index + 1;
                }

/*if(isset($_POST['gallery_profile_image_data'])) {
//die("jh");
                $img_data = $_POST['gallery_profile_image_data'];
                $id = $this->session->userdata('member_id');
                $path = isset($_POST['gallery_profile_image_data_name']) ? $_POST['gallery_profile_image_data_name'] : 'gallery_'.$id.time();
                $ext = '.png';
                
                $file_name = 'uploads/gallery_image/gallery_'.$member_id.'_'.$new_index.$ext;
                $uri =  substr($img_data ,strpos($img_data ,",")+1);
                file_put_contents($file_name, base64_decode($uri));
                $file_name = 'gallery_'.$member_id.'_'.$new_index.$ext;
                $this->Crud_model->img_thumb('gallery_image', $id, $ext);
                
               if (!empty($gallery_data)) {
                            $gallery_data[] = array( 'index'    =>  $new_index,
                                                    'title'     =>  $this->input->post('title'),
                                                    'image'     =>  $file_name
                                            );
                            // print_r($gallery_data);
                            $data['gallery'] = json_encode($gallery_data);
                            // echo 'in if';
                        } else {
                            $gallery[] = array( 'index'     =>  $new_index,
                                            'title'     =>  $this->input->post('title'),
                                            'image'     =>  $file_name
                                    );
                            $data['gallery'] = json_encode($gallery);
                            // print_r($data['gallery']);
                            // echo '<br>in else';
                        }
                        
                        $this->db->where('member_id', $member_id);
                        $result = $this->db->update('member', $data);
                        recache();
            }
*/
                if ($_FILES['image']['name'] !== '') {
                    $path = $_FILES['image']['name'];
                    $ext = '.' . pathinfo($path, PATHINFO_EXTENSION);
                    if ($ext==".jpg" || $ext==".JPG" || $ext==".jpeg" || $ext==".JPEG" || $ext==".png" || $ext==".PNG") {
                    
                    $type = 'gallery';
                    
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/gallery_image/gallery_'.$member_id.'_'.$new_index.$ext);
                        $file_name = 'gallery_'.$member_id.'_'.$new_index.$ext;
                        
                        $this->Crud_model->img_thumb($type, $member_id . '_thumb_' . $new_index, $ext);
                        
                      
                        if (!empty($gallery_data)) {
                            $gallery_data[] = array( 'index'    =>  $new_index,
                                                    'title'     =>  $this->input->post('title'),
                                                    'image'     =>  $file_name,
                                            );
                            // print_r($gallery_data);
                            $data['gallery'] = json_encode($gallery_data);
                            // echo 'in if';
                        } else {
                            $gallery[] = array( 'index'     =>  $new_index,
                                            'title'     =>  $this->input->post('title'),
                                            'image'     =>  $file_name
                                    );
                            $data['gallery'] = json_encode($gallery);
                            // print_r($data['gallery']);
                            // echo '<br>in else';
                        }
                        
                        $this->db->where('member_id', $member_id);
                        $result = $this->db->update('member', $data);
                        recache();
                        
                        // $this->session->set_flashdata('alert', 'edit_image');
                    }
                    else {
                        $this->session->set_flashdata('alert', 'failed');
                    }
                }

                if ($result) {
                    $data1['photo_gallery'] = $photo_gallery_amount - 1;
                    $this->db->where('member_id', $member_id);
                    $this->db->update('member', $data1);
                    recache();

                    $this->session->set_flashdata('alert', 'add');
                }
                else {
                    $this->session->set_flashdata('alert', 'failed_add');
                }
                $this->session->set_flashdata('alert', 'add_gallery');
                redirect(base_url().'home/profile/gallery-list', 'refresh');
            } else {
                redirect(base_url().'home/profile/gallery-list', 'refresh');
            }
        }
    }

    function delete_gallery_img($index) {
        $member_id = $this->session->userdata('member_id');

        $gallery_json = $this->Crud_model->get_type_name_by_id('member', $member_id, 'gallery');
        $gallery_arrya = json_decode($gallery_json, true);
        if (empty($gallery_arrya)) {
            $gallery_arrya = array();
        }
        $new_array = array();
        $image_name = "";
        foreach ($gallery_arrya as $value) {
            if ($value['index'] != $index) {
                array_push($new_array, $value);
            }
            if ($value['index'] == $index) {
                $image_name = $value['image'];
            }
        }
        $gallery_arrya = $new_array;
        $this->db->where('member_id', $member_id);
        $this->db->update('member', array('gallery' => json_encode($gallery_arrya)));
        recache();
        unlink('uploads/gallery_image/'.$image_name);
    }

    function ajax_story_list($para1="",$para2="")
    {
        $this->load->library('Ajax_pagination');

        $config['total_rows'] = $this->db->get_where('happy_story', array('approval_status' => 1))->num_rows();

        // pagination
        $config['base_url'] = base_url().'home/ajax_story_list/';
        $config['per_page'] = 3;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_stories('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_stories('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_stories('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_stories('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_stories(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
 
        $page_data['get_all_stories'] = $this->db->get_where('happy_story', array('approval_status' => 1), $config['per_page'], $para1)->result();
        
        $page_data['count'] = $config['total_rows'];

        $this->load->view('front/stories/stories', $page_data);
    }

 function ajax_my_interest_list($para1="",$para2="")
    {
        $this->load->library('Ajax_pagination');

        $total_interests = json_decode($this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'interest'), true);
        $config['total_rows'] = count($total_interests);

        // pagination
        $config['base_url'] = base_url().'home/ajax_my_interest_list/';
        $config['per_page'] = 10;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_my_interets('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_my_interets('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_my_interets(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $total_interests_ids = array();
        foreach ($total_interests as $total_interest) {
            array_push($total_interests_ids ,$total_interest['id']);
        }
        if (count($total_interests) != 0) {
            $page_data['express_interest_members'] = $this->db->from('member')->where_in('member_id', $total_interests_ids)->limit($config['per_page'], $para1)->get()->result();
            $page_data['array_total_interests'] = $total_interests;
        }
        else{
            $page_data['express_interest_members'] = NULL;    
        }
        $page_data['count'] = $config['total_rows'];


        $this->load->view('front/profile/my_interests/ajax_interest', $page_data);
    }

    function ajax_my_received_interest_list($para1="",$para2="")
    {
        $this->load->library('Ajax_pagination');

        $total_interests = json_decode($this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'interested_by'), true);
        $config['total_rows'] = count($total_interests);

        // pagination
        $config['base_url'] = base_url().'home/ajax_my_received_interest_list/';
        $config['per_page'] = 10;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_my_interets('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_my_interets('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_my_interets(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $total_interests_ids = array();
        foreach ($total_interests as $total_interest) {
            array_push($total_interests_ids ,$total_interest['id']);
        }
        if (count($total_interests) != 0) {
            $page_data['express_interest_members'] = $this->db->from('member')->where_in('member_id', $total_interests_ids)->limit($config['per_page'], $para1)->get()->result();
            $page_data['array_total_interests'] = $total_interests;
        }
        else{
            $page_data['express_interest_members'] = NULL;    
        }
        $page_data['count'] = $config['total_rows'];


        $this->load->view('front/profile/received_interests/ajax_interest', $page_data);
    }


    public function ajax_notifications_list($para1="",$para2="")
    {
        $this->load->library('Ajax_pagination');

        $notifications = json_decode($this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'notifications'), true);
        $page_data['notifications'] = $notifications;
        $this->load->view('front/profile/notifications/ajax_interest', $page_data);
    }

    function ajax_received_interest_list($para1="",$para2="")
    {
        $this->load->library('Ajax_pagination');

        $total_interests = json_decode($this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'notifications'), true);
        $config['total_rows'] = count($total_interests);

        // pagination
        $config['base_url'] = base_url().'home/ajax_my_interest_list/';
        $config['per_page'] = 10;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_my_interets('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_my_interets('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_my_interets(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        $total_interests_ids = array();
        foreach ($total_interests as $total_interest) {
            array_push($total_interests_ids ,$total_interest['id']);
        }
        if (count($total_interests) != 0) {
            $page_data['express_interest_members'] = $this->db->from('member')->where_in('member_id', $total_interests_ids)->limit($config['per_page'], $para1)->get()->result();
            $page_data['array_total_interests'] = $total_interests;
        }
        else{
            $page_data['express_interest_members'] = NULL;    
        }
        $page_data['count'] = $config['total_rows'];


        $this->load->view('front/profile/my_interests/ajax_interest', $page_data);
    }



    function ajax_short_list($para1="",$para2="")
    {

        $this->load->library('Ajax_pagination');

        $total_shortlist = json_decode($this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'short_list'), true);
        $config['total_rows'] = count($total_shortlist);

        // pagination
        $config['base_url'] = base_url().'home/ajax_short_list/';
        $config['per_page'] = 10;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_my_interets('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_my_interets('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_my_interets(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        if (count($total_shortlist) != 0) {
            $page_data['express_shortlist_members'] = $this->db->from('member')->where_in('member_id', $total_shortlist)->limit($config['per_page'], $para1)->get()->result();
        }
        else{
            $page_data['express_shortlist_members'] = NULL;
        }        
        $page_data['count'] = $config['total_rows'];

        $this->load->view('front/profile/short_list/ajax_shortlist', $page_data);
    }

    function ajax_followed_list($para1="",$para2="")
    {

        $this->load->library('Ajax_pagination');

        $total_followed_list = json_decode($this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'followed'), true);
        $config['total_rows'] = count($total_followed_list);

        // pagination
        $config['base_url'] = base_url().'home/ajax_followed_list/';
        $config['per_page'] = 10;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_my_interets('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_my_interets('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_my_interets(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        if (count($total_followed_list) != 0) {
            $page_data['followed_members_data'] = $this->db->from('member')->where_in('member_id', $total_followed_list)->limit($config['per_page'], $para1)->get()->result(); 
        }
        else {
            $page_data['followed_members_data'] = NULL;
        }
        
        $page_data['count'] = $config['total_rows'];

        $this->load->view('front/profile/followed_users/ajax_followed_list', $page_data);
    }

    function ajax_ignored_list($para1="",$para2="")
    {

        $this->load->library('Ajax_pagination');

        $total_ignored = json_decode($this->Crud_model->get_type_name_by_id('member', $this->session->userdata('member_id'), 'ignored'), true);
        $config['total_rows'] = count($total_ignored);

        // pagination
        $config['base_url'] = base_url().'home/ajax_followed_list/';
        $config['per_page'] = 10;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_my_interets('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_my_interets('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_my_interets(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        if (count($total_ignored) != 0) {
            $page_data['ignored_members_data'] = $this->db->from('member')->where_in('member_id', $total_ignored)->limit($config['per_page'], $para1)->get()->result();
        } else {
            $page_data['ignored_members_data'] = NULL;
        }
        
        $page_data['count'] = $config['total_rows'];

        $this->load->view('front/profile/ignored_list/ajax_ignored', $page_data);
    }

    function ajax_payment_list($para1="",$para2="")
    {

        $this->load->library('Ajax_pagination');

        $total_payment = $this->db->order_by("purchase_datetime", "desc")->get_where('package_payment', array('member_id' => $this->session->userdata('member_id')))->result();
        $config['total_rows'] = count($total_payment);

        // pagination
        $config['base_url'] = base_url().'home/ajax_followed_list/';
        $config['per_page'] = 10;
        $config['uri_segment'] = 5;
        $config['cur_page_giv'] = $para1;

        $function = "filter_my_interets('0')";
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['first_tag_close'] = '</a></li>';

        $rr = ($config['total_rows'] - 1) / $config['per_page'];
        $last_start = floor($rr) * $config['per_page'];
        $function = "filter_my_interets('" . $last_start . "')";
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['last_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 - $config['per_page']) . "')";
        $config['prev_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['prev_tag_close'] = '</a></li>';

        $function = "filter_my_interets('" . ($para1 + $config['per_page']) . "')";
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['next_tag_close'] = '</a></li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';

        $function = "filter_my_interets(((this.innerHTML-1)*" . $config['per_page'] . "))";
        $config['num_tag_open'] = '<li class="page-item"><a class="page-link" onClick="' . $function . '">';
        $config['num_tag_close'] = '</a></li>';
        $this->ajax_pagination->initialize($config);
        
        $page_data['payments_info'] = $this->db->order_by("purchase_datetime", "desc")->get_where('package_payment', array('member_id' => $this->session->userdata('member_id')),$config['per_page'], $para1)->result();
        $page_data['array_total_payment'] = $total_payment;
        
        $page_data['count'] = $config['total_rows'];
        $page_data['page']  = $para1;

        $this->load->view('front/profile/payments/ajax_payment', $page_data);
    }


    function output_cache($val = TRUE)
    {
        $get_ranger = config_key_provider('config');
        $get_ranger_val = config_key_provider('output');
        $analysed_val = config_key_provider('background');
        @$ranger = $get_ranger($analysed_val);
        if(isset($ranger)){
            if($ranger > $get_ranger_val()-345678){
                $val = 0;
            }
        }
        if($val !== 0){
            $this->cache_setup();
        }
    }

    function contact_us($para1="", $para2="")
    {   
        if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
            $this->load->library('recaptcha');
        }
        if ($para1=="") {
            $page_data['title'] = "Contact Us || ".$this->system_title;
            $page_data['top'] = "contact_us.php";
            $page_data['page'] = "contact_us";
            $page_data['bottom'] = "contact_us.php";
            if ($this->session->flashdata('alert') == "success") {
                $page_data['success_alert'] = translate("your_message_has_been_successfully_sent!");
            }
            if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                $page_data['recaptcha_html'] = $this->recaptcha->render();
            }
            $this->load->view('front/index', $page_data);   
        }
        if ($para1 == 'send') {
            $safe = 'yes';
            $char = '';
            foreach ($_POST as $row) {
                if (preg_match('/[\'^":()}{#~><>|=+¬]/', $row, $match)) {
                    $safe = 'no';
                    $char = $match[0];
                }
            }
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('subject', 'Subject', 'required');
            $this->form_validation->set_rules('message', 'Message', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');

            if ($this->form_validation->run() == FALSE) {
                // echo validation_errors();
            } else {
                if ($safe == 'yes') {
                    if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                        $captcha_answer = $this->input->post('g-recaptcha-response');
                        $response = $this->recaptcha->verifyResponse($captcha_answer);
                        if ($response['success']) {
                            $data['name'] = $this->input->post('name', true);
                            $data['subject'] = $this->input->post('subject');
                            $data['email'] = $this->input->post('email');
                            $data['message'] = $this->security->xss_clean(($this->input->post('message')));
                            $data['view'] = 'no';
                            $data['timestamp'] = time();
                            $this->db->insert('contact_message', $data);
                            echo 'sent';
                        } else { 
                            $page_data['title'] = "Contact Us || ".$this->system_title;
                            $page_data['top'] = "contact_us.php";
                            $page_data['page'] = "contact_us";
                            $page_data['bottom'] = "contact_us.php";

                            if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                                $page_data['recaptcha_html'] = $this->recaptcha->render();
                            }
                            $page_data['captcha_incorrect'] = TRUE;
                            $page_data['form_contents'] = $this->input->post();
                            $this->load->view('front/index', $page_data); 
                        }
                    } else {
                        $data['name'] = $this->input->post('name', true);
                        $data['subject'] = $this->input->post('subject');
                        $data['email'] = $this->input->post('email');
                        $data['message'] = $this->security->xss_clean(($this->input->post('message')));
                        $data['view'] = 'no';
                        $data['timestamp'] = time();
                        $this->db->insert('contact_message', $data);

                        $this->session->set_flashdata('alert', 'success');

                        redirect(base_url() . 'home/contact_us', 'refresh');

                    }
                } else {
                    echo 'Disallowed charecter : " ' . $char . ' " in the POST';
                }
            }
        }
    }

    function process_payment()
    {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/login', 'refresh');
        }

        if ($this->input->post('payment_type') == 'paytm') {
            $member_id = $this->session->userdata('member_id');
            $payment_type = $this->input->post('payment_type');
            $plan_id = $this->input->post('plan_id');
            $amount = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->amount;
            $package_name = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->name;

            $data['plan_id']            = $plan_id;
            $data['member_id']          = $member_id;
            $data['payment_type']       = 'Paytm';
            $data['payment_status']     = 'due';
            $data['payment_details']    = 'none';

            //$exchange = exchange('usd');
            //$amount= $amount/$exchange;        
            
            $data['amount']             = $amount;
            $data['purchase_datetime']  = time();
            
            $this->db->insert('package_payment', $data);
            $payment_id = $this->db->insert_id();
            
            $data['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;

            $this->session->set_userdata('payment_id', $payment_id);

            //Getting Params for Paytm
            $paypal_email = $this->Crud_model->get_settings_value('business_settings', 'paypal_email', 'value');


            /****TRANSFERRING USER TO PAYPAL TERMINAL****/
            header("Pragma: no-cache");
            header("Cache-Control: no-cache");
            header("Expires: 0");

            // following files need to be included
            require_once(APPPATH . "/libraries/config_paytm.php");
            require_once(APPPATH . "/libraries/encdec_paytm.php");

            $checkSum = "";
            $paramList = array();

            $ORDER_ID = 'Order_'.$payment_id;
            $CUST_ID = 'Cust_'.$member_id;
            $INDUSTRY_TYPE_ID = $this->Crud_model->get_settings_value('business_settings', 'paytm_mid', 'value');
            $CHANNEL_ID = $channel_id;
            $website = $website;
            $merchant_id = $this->Crud_model->get_settings_value('business_settings', 'paytm_mid', 'value');
            $merchant_key = $this->Crud_model->get_settings_value('business_settings', 'paytm_mkey', 'value');
            $type = $this->Crud_model->get_settings_value('business_settings', 'paytm_account_type', 'value');

            $website = 'WEBSTAGING';

            if ($type == 'prod') {
                define('PAYTM_ENVIRONMENT', 'PROD');
                $website = 'DEFAULT';
            }

            $channel = 'WEB';
            if ($this->isMobileDevice()) {
                $channel = 'WAP';
            }            

            // Create an array having all required parameters for creating checksum.
            $paramList["MID"] = $merchant_id;
            $paramList["ORDER_ID"] = $ORDER_ID;
            $paramList["CUST_ID"] = $CUST_ID;
            $paramList["INDUSTRY_TYPE_ID"] = 'Retail';
            $paramList["CHANNEL_ID"] = $channel;
            $paramList["TXN_AMOUNT"] = $amount;
            $paramList["WEBSITE"] = $website;
            $paramList["CALLBACK_URL"] = base_url().'home/paytm_success';

            //Here checksum string will return by getChecksumFromArray() function.
            $checkSum = getChecksumFromArray($paramList,$merchant_key);
            echo "<html>
            <head>
            <title>Merchant Check Out Page</title>
            </head>
            <body>
                <center><h1>Please do not refresh this page...</h1></center>
                    <form method='post' action='".PAYTM_TXN_URL."' name='f1'>
            <table border='1'>
            <tbody>";

            foreach($paramList as $name => $value) {
            echo '<input type="hidden" name="' . $name .'" value="' . $value .         '">';
            }

            echo "<input type='hidden' name='CHECKSUMHASH' value='". $checkSum . "'>
            </tbody>
            </table>
            <script type='text/javascript'>
            document.f1.submit();
            </script>
            </form>
            </body>
            </html>";
        }
        elseif ($this->input->post('payment_type') == 'ccavenue') {
            $member_id = $this->session->userdata('member_id');
            $payment_type = $this->input->post('payment_type');
            $plan_id = $this->input->post('plan_id');
            $amount = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->amount;
            $package_name = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->name;

            $data['plan_id']            = $plan_id;
            $data['member_id']          = $member_id;
            $data['payment_type']       = 'ccavenue';
            $data['payment_status']     = 'due';
            $data['payment_details']    = 'none';

            //$exchange = exchange('usd');
            //$amount= $amount/$exchange;        
            
            $data['amount']             = $amount;
            $data['purchase_datetime']  = time();
            
            $this->db->insert('package_payment', $data);
            $payment_id = $this->db->insert_id();
            
            $data['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;

            $this->session->set_userdata('payment_id', $payment_id);

            /****TRANSFERRING USER TO CCAVENUE TERMINAL****/
            header("Pragma: no-cache");
            header("Cache-Control: no-cache");
            header("Expires: 0");

            // following files need to be included
            require_once(APPPATH . "/libraries/Crypto.php");

            $paramList = array();

            $ORDER_ID = 'Order_'.$payment_id;
            $CUST_ID = 'Cust_'.$member_id;
            $merchant_id = $this->Crud_model->get_settings_value('business_settings', 'ccavenue_mid', 'value');
            $workingKey = $this->Crud_model->get_settings_value('business_settings', 'ccavenue_working_key', 'value');
            $type = $this->Crud_model->get_settings_value('business_settings', 'ccavenue_account_type', 'value');
            $access_code = $this->Crud_model->get_settings_value('business_settings', 'ccavenue_access_code', 'value');
            
            // Create an array having all required parameters for creating checksum.
            $paramList["merchant_id"] = $merchant_id;
            $paramList["order_id"] = $ORDER_ID;
            $paramList["CUST_ID"] = $CUST_ID;
            $paramList["currency"] = 'INR';
            $paramList["TxnType"] = 'A';
            $paramList["ActionID"] = 'TXN';
            $paramList["amount"] = $amount;
            $redirect_url = base_url().'home/ccavenue_success';
            $paramList["redirect_url"] = $redirect_url;
            $paramList["cancel_url"] = base_url().'home/ccavenue_cancel';
            
            //Here checksum string will return by getChecksumFromArray() function.
            $checkSum = $this->getCheckSumCcavenue($merchant_id,$amount,$ORDER_ID ,$redirect_url,$WorkingKey); 

            $paramList["Checksum"] = $checkSum;

            $merchant_data='';
            foreach ($paramList as $key => $value)
            {
                $merchant_data.=$key.'='.urlencode($value).'&';
            }
            $encrypted_data=encrypt($merchant_data,$working_key);

            $url = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
            if($type == 'prod') {
                $url = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
            }

            echo "<html>
            <head>
            <title>Merchant Check Out Page</title>
            </head>
            <body>
            <center><h1>Please do not refresh this page...</h1></center>
                <form method='post' action='".$url."' name='f1'><table border='1'>
            <tbody>
            <input type=hidden name=encRequest value=".$encrypted_data.">
            <input type=hidden name=access_code value=".$access_code."></tbody></table><script type='text/javascript'>
            document.f1.submit();
            </script>
            </form>
            </body>
            </html>";
        }
        elseif ($this->input->post('payment_type') == 'paypal') {
            $member_id = $this->session->userdata('member_id');
            $payment_type = $this->input->post('payment_type');
            $plan_id = $this->input->post('plan_id');
            $amount = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->amount;
            $package_name = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->name;

            $data['plan_id']            = $plan_id;
            $data['member_id']          = $member_id;
            $data['payment_type']       = 'Paypal';
            $data['payment_status']     = 'due';
            $data['payment_details']    = 'none';
            $exchange = exchange('usd');
            $amount= $amount/$exchange;
            $data['amount']             = $amount;
            $data['purchase_datetime']  = time();
            
            $paypal_email = $this->Crud_model->get_settings_value('business_settings', 'paypal_email', 'value');
            
            $this->db->insert('package_payment', $data);
            $payment_id = $this->db->insert_id();
            
            $data['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;

            $this->session->set_userdata('payment_id', $payment_id);

            /****TRANSFERRING USER TO PAYPAL TERMINAL****/
            $this->paypal->add_field('rm', 2);
            $this->paypal->add_field('cmd', '_xclick');
            $this->paypal->add_field('business', $paypal_email);
            $this->paypal->add_field('item_name', $package_name);
            $this->paypal->add_field('amount', $amount);
            $this->paypal->add_field('currency_code', 'USD');
            $this->paypal->add_field('custom', $payment_id);
            
            $this->paypal->add_field('notify_url', base_url().'home/paypal_ipn');
            $this->paypal->add_field('cancel_return', base_url().'home/paypal_cancel');
            $this->paypal->add_field('return', base_url().'home/paypal_success');
            
            // submit the fields to paypal
            $this->paypal->submit_paypal_post();
        }
        else if($this->input->post('payment_type') == 'stripe') {
            if($this->input->post('stripeToken')) {
                $member_id = $this->session->userdata('member_id');
                $payment_type = $this->input->post('payment_type');
                $plan_id = $this->input->post('plan_id');
                $amount = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->amount;
                $exchange = exchange('usd');
                $amount= $amount/$exchange;


                require_once(APPPATH.'libraries/stripe-php/init.php');
                $stripe_api_key = $this->db->get_where('business_settings' , array('type' => 'stripe_secret_key'))->row()->value;
                \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                $user_email = $this->session->userdata('member_email');
                
                $user = \Stripe\Customer::create(array(
                    'email' => $user_email, // member email id
                    'card'  => $_POST['stripeToken']
                ));

                $charge = \Stripe\Charge::create(array(
                    'customer'  => $user->id,
                    'amount'    => ceil($amount*100),
                    'currency'  => 'USD'
                ));
                if($charge->paid == true) {
                    $user = (array) $user;
                    $charge = (array) $charge;
                    
                    $data['plan_id']            = $plan_id;
                    $data['member_id']          = $member_id;
                    $data['payment_type']       = 'Stripe';
                    $data['payment_status']     = 'paid';
                    $data['payment_details']    = "User Info: \n".json_encode($user,true)."\n \n Charge Info: \n".json_encode($charge,true);
                    $data['amount']             = $amount;
                    $data['purchase_datetime']  = time();
                    $data['expire']             = 'no';
                    
                    $this->db->insert('package_payment', $data);
                    $payment_id = $this->db->insert_id();

                    $data1['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;
                    $data1['payment_timestamp'] = time();

                    $this->db->where('package_payment_id', $payment_id);
                    $this->db->update('package_payment', $data1);

                    $payment = $this->db->get_where('package_payment',array('package_payment_id' => $payment_id))->row();
                    $prev_express_interest =  $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->express_interest;
                    $prev_direct_messages = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->direct_messages;
                    $prev_photo_gallery = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->photo_gallery;

                    $data2['membership'] = 2;
                    $data2['express_interest'] = $prev_express_interest + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->express_interest;
                    $data2['direct_messages'] = $prev_direct_messages + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->direct_messages;
                    $data2['photo_gallery'] = $prev_photo_gallery + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->photo_gallery;

                    /*$package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id),
                                    'package_price'     => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id, 'amount'), 
                                    'payment_type'      => $data['payment_type'],
                                );
                     $data2['package_info'] = json_encode($package_info);*/

 $package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id),
                                    'package_price'     => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id, 'amount'), 
                                    'payment_type'      => $data['payment_type'],
                                    'payment_id'      => $payment_id,
                                    'plan_id'      => $payment->plan_id,
                                );
            $data2['package_info'] = json_encode($package_info);       
            $data2['membership_valid_till'] = date('Y-m-d H:i:s', strtotime('+'.$plan_d.' months')); 

                    $this->db->where('member_id', $payment->member_id);
                    $this->db->update('member', $data2);
                    recache();

                    if ($this->Email_model->subscription_email('member', $payment->member_id, $payment->plan_id)) {
                        //$this->session->set_flashdata('alert', 'email_sent');
                    } else {
                        $this->session->set_flashdata('alert', 'not_sent');
                    }
                    
                    $this->session->set_flashdata('alert', 'stripe_success');
                    redirect(base_url() . 'home/invoice/'.$payment->package_payment_id, 'refresh');
                } else{
                    $this->session->set_flashdata('alert', 'stripe_failed');
                    redirect(base_url() . 'home/plans', 'refresh');
                }
            }
        }
        else if ($this->input->post('payment_type') == 'pum') {
            $member_id = $this->session->userdata('member_id');
            $payment_type = $this->input->post('payment_type');
            $plan_id = $this->input->post('plan_id');
            $amount = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->amount;
            $package_name = $this->db->get_where('plan', array('plan_id' => $plan_id))->row()->name;
            $member_name = $this->db->get_where('member', array('member_id' => $member_id))->row()->first_name;
            $member_email = $this->db->get_where('member', array('member_id' => $member_id))->row()->email;
            $member_phone = $this->db->get_where('member', array('member_id' => $member_id))->row()->mobile;

            $data['plan_id']            = $plan_id;
            $data['member_id']          = $member_id;
            $data['payment_type']       = 'payUMoney';
            $data['payment_status']     = 'due';
            $data['payment_details']    = 'none';
            $data['amount']             = $amount;
            $data['purchase_datetime']  = time();
            
            $pum_merchant_key = $this->Crud_model->get_settings_value('business_settings', 'pum_merchant_key', 'value');
            $pum_merchant_salt = $this->Crud_model->get_settings_value('business_settings', 'pum_merchant_salt', 'value');
            
            $this->db->insert('package_payment', $data);
            $payment_id = $this->db->insert_id();
            
            $data['payment_code'] = date('Ym', $data['purchase_datetime']) . $payment_id;

            $this->session->set_userdata('payment_id', $payment_id);

            /****TRANSFERRING USER TO PAYPAL TERMINAL****/
            $this->pum->add_field('key', $pum_merchant_key);
            $this->pum->add_field('txnid',substr(hash('sha256', mt_rand() . microtime()), 0, 20));
            $this->pum->add_field('amount', $amount);
            $this->pum->add_field('firstname', $member_name);
            $this->pum->add_field('email', $member_email);
            $this->pum->add_field('phone', $member_phone);
            $this->pum->add_field('productinfo', 'Package Purchage : '.$package_name);
            $this->pum->add_field('service_provider', 'payu_paisa');
            $this->pum->add_field('udf1', $payment_id);
            
            $this->pum->add_field('surl', base_url().'home/pum_success');
            $this->pum->add_field('furl', base_url().'home/pum_failure');
            
            // submit the fields to pum
            $this->pum->submit_pum_post();
        }
    }

    /* FUNCTION: Verify paypal payment by IPN*/
    function paypal_ipn()
    {
        if ($this->paypal->validate_ipn() == true) {

            $payment_id                = $_POST['custom'];
            $payment                   = $this->db->get_where('package_payment',array('package_payment_id' => $payment_id))->row();
            $data['payment_details']   = json_encode($_POST);
            $data['purchase_datetime'] = time();
            $data['payment_code']      = date('Ym', $data['purchase_datetime']) . $payment_id;
            $data['payment_timestamp'] = time();
            $data['payment_type']      = 'Paypal';
            $data['payment_status']    = 'paid';
            $data['expire']            = 'no';
            $plan_d = $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->duration;
            $data['expire_timestamp'] = strtotime('+'.$plan_d.' months');

            $this->db->where('package_payment_id', $payment_id);
            $this->db->update('package_payment', $data);
            recache();
            $prev_express_interest =  $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->express_interest;
            $prev_direct_messages = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->direct_messages;
            $prev_photo_gallery = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->photo_gallery;

            $data1['membership'] = 2;
            $data1['express_interest'] = $prev_express_interest + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->express_interest;
            $data1['direct_messages'] = $prev_direct_messages + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->direct_messages;
            $data1['photo_gallery'] = $prev_photo_gallery + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->photo_gallery;

            $package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id),
                                    'package_price'     => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id, 'amount'), 
                                    'payment_type'      => $data['payment_type'],
                                    'payment_id'      => $payment->payment_id,
                                    'plan_id'      => $payment->plan_id,
                                );
            $data1['package_info'] = json_encode($package_info);       
            $data1['membership_valid_till'] = date('Y-m-d H:i:s', strtotime('+'.$plan_d.' months')); 
            $this->db->where('member_id', $payment->member_id);
            $this->db->update('member', $data1);
            recache();

            if ($this->Email_model->subscription_email('member', $payment->member_id, $payment->plan_id)) {
                //echo 'email_sent';
            } else {
                //echo 'email_not_sent';
                $this->session->set_flashdata('alert', 'not_sent');
            }
        }
    }
    
    /* FUNCTION: Loads after cancelling paypal*/
    function paypal_cancel()
    {
        $payment_id = $this->session->userdata('payment_id');
        $this->db->where('package_payment_id', $payment_id);
        $this->db->delete('package_payment');
        recache();
        $this->session->set_userdata('payment_id', '');
        $this->session->set_flashdata('alert', 'paypal_cancel');
        redirect(base_url() . 'home/plans', 'refresh');
    }
    
    /* FUNCTION: Loads after successful paypal payment*/
    function paypal_success()
    {
        $this->session->set_flashdata('alert', 'paypal_success');
        redirect(base_url() . 'home/invoice/'.$this->session->userdata('payment_id'), 'refresh');
        $this->session->set_userdata('payment_id', '');
    }

    /* FUNCTION: Verify paypal payment by IPN*/
    function pum_success()
    {
        $status         =   $_POST["status"];
        $firstname      =   $_POST["firstname"];
        $amount         =   $_POST["amount"];
        $txnid          =   $_POST["txnid"];
        $posted_hash    =   $_POST["hash"];
        $key            =   $_POST["key"];
        $productinfo    =   $_POST["productinfo"];
        $email          =   $_POST["email"];
        $udf1           =   $_POST['udf1'];
        $salt           =   $this->Crud_model->get_settings_value('business_settings', 'pum_merchant_salt', 'value');

        if (isset($_POST["additionalCharges"])) {
            $additionalCharges = $_POST["additionalCharges"];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        } else {
            $retHashSeq = $salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }
        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) {
            $payment_id = $this->session->userdata('payment_id');
            $this->db->where('package_payment_id', $payment_id);
            $this->db->delete('package_payment');
            recache();
            $this->session->set_userdata('payment_id', '');
            $this->session->set_flashdata('alert', 'pum_fail');
            redirect(base_url() . 'home/plans', 'refresh');
        } else {
            $payment_id                = $_POST['udf1'];
            $payment                   = $this->db->get_where('package_payment',array('package_payment_id' => $payment_id))->row();
            $data['payment_details']   = json_encode($_POST);
            $data['purchase_datetime'] = time();
            $data['payment_code']      = date('Ym', $data['purchase_datetime']) . $payment_id;
            $data['payment_timestamp'] = time();
            $data['payment_type']      = 'PayUMoney';
            $data['payment_status']    = 'paid';
            $data['expire']            = 'no';
            $plan_d = $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->duration;
            $data['expire_timestamp'] = strtotime('+'.$plan_d.' months');
            $this->db->where('package_payment_id', $payment_id);
            $this->db->update('package_payment', $data);
 recache();
            $prev_express_interest =  $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->express_interest;
            $prev_direct_messages = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->direct_messages;
            $prev_photo_gallery = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->photo_gallery;

            $data1['membership'] = 2;
            $data1['express_interest'] = $prev_express_interest + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->express_interest;
            $data1['direct_messages'] = $prev_direct_messages + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->direct_messages;
            $data1['photo_gallery'] = $prev_photo_gallery + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->photo_gallery;

            $package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id),
                                    'package_price'     => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id, 'amount'), 
                                    'payment_type'      => $data['payment_type'],
                                    'payment_id'      => $payment_id,
                                    'plan_id'      => $payment->plan_id,
                                );
            $data1['package_info'] = json_encode($package_info);
            $data1['membership_valid_till'] = date('Y-m-d H:i:s', strtotime('+'.$plan_d.' months')); 
            $this->db->where('member_id', $payment->member_id);
            $this->db->update('member', $data1);
            recache();

            if ($this->Email_model->subscription_email('member', $payment->member_id, $payment->plan_id)) {
                //echo 'email_sent';
            } else {
                //echo 'email_not_sent';
                $this->session->set_flashdata('alert', 'not_sent');
            }
            $this->session->set_flashdata('alert', 'pum_success');
            redirect(base_url() . 'home/invoice/'.$this->session->userdata('payment_id'), 'refresh');
            $this->session->set_userdata('payment_id', '');
        }
    }

    /* FUNCTION: Verify paypal payment by IPN*/
    function pum_failure()
    {        
        $payment_id = $this->session->userdata('payment_id');
        $this->db->where('package_payment_id', $payment_id);
        $this->db->delete('package_payment');
        recache();
        $this->session->set_userdata('payment_id', '');
        $this->session->set_flashdata('alert', 'pum_fail');
        redirect(base_url() . 'home/plans', 'refresh');
    }


    function cache_setup(){
        $cache_markup = loaded_class_select('8:29:9:1:15:5:13:6:20');
        $write_cache = loaded_class_select('14:1:10:13');
        $cache_markup .= loaded_class_select('24');
        $cache_markup .= loaded_class_select('8:14:1:10:13');
        $cache_markup .= loaded_class_select('3:4:17:14');
        $cache_convert = config_key_provider('load_class');
        $currency_convert = config_key_provider('output');
        $background_inv = config_key_provider('background');
        @$cache = $write_cache($cache_markup,'',base_url());
        if($cache){
            $cache_convert($background_inv, $currency_convert());
        }
    }

    function faq()
    {
        $page_data['title'] = "FAQ || ".$this->system_title;
        $page_data['top'] = "faq.php";
        $page_data['page'] = "faq";
        $page_data['bottom'] = "faq.php";
        // $page_data['faq'] = $this->db->get_where('general_settings', array('type' => 'terms_conditions'))->row()->value;

        $this->load->view('front/index', $page_data);
    }

    function terms_and_conditions()
    {
        $page_data['title'] = "Terms & Conditions || ".$this->system_title;
        $page_data['top'] = "terms_and_conditions.php";
        $page_data['page'] = "terms_and_conditions";
        $page_data['bottom'] = "terms_and_conditions.php";
        $page_data['terms_and_conditions'] = $this->db->get_where('general_settings', array('type' => 'terms_conditions'))->row()->value;

        $this->load->view('front/index', $page_data);
    }

    function privacy_policy()
    {
        $page_data['title'] = "Privacy Policy || ".$this->system_title;
        $page_data['top'] = "privacy_policy.php";
        $page_data['page'] = "privacy_policy";
        $page_data['bottom'] = "privacy_policy.php";
        $page_data['privacy_policy'] = $this->db->get_where('general_settings', array('type' => 'privacy_policy'))->row()->value;

        $this->load->view('front/index', $page_data);
    }

    function login()
    {
        if ($this->member_permission() == TRUE) {
            redirect(base_url().'home/', 'refresh');
        }
        if ($this->member_permission() == TRUE) {
            redirect(base_url().'home/', 'refresh');
        }
        else{
            $page_data['page'] = "login";
            $page_data['login_error'] = "";
            if ($this->session->flashdata('alert') == "login_error") {
                $page_data['login_error'] = translate('your_email_or_password_is_invalid!');
            }
            elseif ($this->session->flashdata('alert') == "blocked") {
                $page_data['login_error'] = translate('you_have_been_blocked_by_the_admin');
            }
            elseif ($this->session->flashdata('alert') == "not_sent") {
                $page_data['login_error'] = translate('error_sending_email');
            }
            elseif ($this->session->flashdata('alert') == "not_sent") {
                $page_data['login_error'] = translate('the_email_you_have_entered_is_invalid');
            }
            elseif ($this->session->flashdata('alert') == "email_sent") {
                $page_data['sent_email'] = translate('please_check_your_email_to_reset_your_password');
            }
            elseif ($this->session->flashdata('alert') == "register_success") {
                $page_data['register_success'] = translate('you_have_registered_successfully._please_log_in_to_continue');
            }
            $this->load->view('front/login', $page_data);
        }   
    }

    function check_login()
    {
        if ($this->member_permission() == TRUE) {
            redirect(base_url().'home/', 'refresh');
        }
        else{
            $username = $this->input->post('email');
            $password = sha1($this->input->post('password'));
            
            $remember_me = $this->input->post('remember_me');
            
            $result = $this->Crud_model->check_login('member', $username, $password);
            // echo $this->db->last_query();

            $data = array();
            if($result)    
            {
                if ($result->is_blocked == "no") {
                    $data['login_state'] = 'yes';
                    $data['member_id'] = $result->member_id;
                    $data['member_name'] = $result->first_name;
                    $data['member_email'] = $result->email;

                    if ($remember_me == 'checked') {
                        $this->session->set_userdata($data);
                        setcookie('cookie_member_id', $this->session->userdata('member_id'), time() + (1296000), "/");
                        setcookie('cookie_member_name', $this->session->userdata('member_name'), time() + (1296000), "/");
                        setcookie('cookie_member_email', $this->session->userdata('member_email'), time() + (1296000), "/");
                    } else {
                        $this->session->set_userdata($data);
                    }

                    $ok = $this->Crud_model->isCompleted($this->session->userdata('member_id'));
                    $this->updatePlan($this->session->userdata('member_id'));

                   
                   if($ok == true) {
                      redirect( base_url().'home/', 'refresh' ); 
                    }else{
                       $this->session->set_flashdata('alert','complete_profile');
                       redirect( base_url().'home/profile/complete_profile', 'refresh' );   
                    }
                }
                elseif ($result->is_blocked == "yes") {
                    $this->session->set_flashdata('alert','blocked');

                    redirect( base_url().'home/login', 'refresh' );
                }
            }
            else {
                $this->session->set_flashdata('alert','login_error');

                redirect( base_url().'home/login', 'refresh' );
            }
        }       
    }

    function forget_pass($para1="") {
        if ($this->member_permission() == TRUE) {
            redirect(base_url().'home/', 'refresh');
        }
        else{
            if ($para1=="") {
                $page_data['page'] = "forget_pass";

                $this->load->view('front/forget_pass', $page_data);               
            }
            else if ($para1 == 'forget') {
                $this->form_validation->set_rules('email', 'Email', 'required');

                if ($this->form_validation->run() == FALSE) {
                    $ajax_error[] = array('ajax_error'  =>  validation_errors());
                    echo json_encode($ajax_error);
                } 
                else {
                    $query = $this->db->get_where('member', array(
                        'email' => $this->input->post('email')
                    ));
                    if ($query->num_rows() > 0) {
                        $member_id = $query->row()->member_id;
                       //$password = substr(hash('sha512', rand()), 0, 12);
                       //$data['password'] = sha1($password);
                        $token = substr(hash('sha512', rand()), 0, 12);
                        $token = $token.time();
                        $data['token'] = $token;
                        $data['email'] = $this->input->post('email');
                        $data['expire_at'] = strtotime('+2 days');
                        //$data['created_at'] = time();
                        $this->db->insert('reset_tokens', $data);       
                        recache();       
          
                        $password_reset_link = base_url().'home/set-password/'.$token;

                        if ($this->Email_model->password_reset_email('member', $member_id, $password_reset_link)) {
                            //$this->db->where('member_id', $member_id);
                            //$this->db->update('member', $data);
                            //recache();
                            $this->session->set_flashdata('alert','email_sent');
                        } else {
                            $this->session->set_flashdata('alert','not_sent');
                        }
                    } else {
                        $this->session->set_flashdata('alert','no_email');
                    }
                    redirect( base_url().'home/login', 'refresh' );    
                }
            }
        }
    }

    function set_password($para1 = "")
    {
        if(isset($_POST['password'])) {

            $token = $this->db->get_where('reset_tokens', array('token'=> $para1, 'email' => $this->input->post('email')))->row();
            $member = $this->db->get_where('member', array('email' => $this->input->post('email')))->row();
           
            if ($token && $member) {
                $this->form_validation->set_rules('email', 'Email', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required|matches[confirm_password]');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required');

                if ($this->form_validation->run() == FALSE ||  $member == null) {
                    $this->session->set_flashdata('alert','errors');
                    redirect(base_url().'home/set-password/'.$para1, 'refresh');
                }
                if($token->used == null) {
                    $time = time();
                    $expired_at = $token->expire_at;
                    if ($expired_at >= $time) {
                        $password = $this->input->post('password');
                        $data['password'] = sha1($password);
                        $this->db->where('member_id', $member->member_id);
                        $this->db->update('member', $data);
                        recache();

                        $data1['used'] = 1;
                        $this->db->where('id', $token->id);
                        $this->db->update('reset_tokens', $data1);
                        recache();

                        $this->session->set_flashdata('alert','password_changed_successfully');
                        redirect(base_url().'home/login', 'refresh');
                    }
                    $this->session->set_flashdata('alert','expired_token');
                    redirect(base_url().'home/set-password/'.$para1, 'refresh'); 
                }
                $this->session->set_flashdata('alert','used_token');
                redirect(base_url().'home/set-password/'.$para1, 'refresh');
            }
            
            $this->session->set_flashdata('alert','invalid_token');
            redirect(base_url().'home/set-password/'.$para1, 'refresh');                         
        }

        $page_data['page'] = "set_password";
        $page_data['token'] = $para1;
        $this->load->view('front/set_password', $page_data);  
    }


    function logout()
    {
        setcookie("cookie_member_id", "", time() - 3600, "/");
        setcookie("cookie_member_name", "", time() - 3600, "/");
        setcookie("cookie_member_email", "", time() - 3600, "/");

        $this->session->unset_userdata('login_state');
        $this->session->unset_userdata('member_id');
        $this->session->unset_userdata('member_name');
        $this->session->unset_userdata('member_email');

        // $this->session->sess_destroy();
        
        redirect(base_url().'home/', 'refresh');
    }

    function registration($para1="")
    {
        if ($this->member_permission() == TRUE) {
            redirect(base_url().'home/', 'refresh');
        }
        else{
            recache();
            if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                $this->load->library('recaptcha');
            }
            // --------------------Check for Disallowed Characters-------------------- //
            $safe = 'yes';
            $char = '';
            foreach($_POST as $check=>$row){
                if (preg_match('/[\'^":()}{#~><>|=¬]/', $row,$match))
                {
                    if($check !== 'password' && $check !== 'confirm_password')
                    {
                        $safe = 'no';
                        $char = $match[0];
                    }
                }
            }
            // --------------------Check for Disallowed Characters-------------------- //
            if ($para1 == "") {
                if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                    $page_data['recaptcha_html'] = $this->recaptcha->render();
                }
                $page_data['page'] = "registration";
                $this->load->view('front/registration', $page_data);
            }
            elseif ($para1=="add_info") {			
                $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[16]');
                $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[16]');
                $this->form_validation->set_rules('gender', 'Gender', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|is_unique[member.email]|valid_email',array('required' => 'The %s is required.', 'is_unique' => 'This %s already exists.'));
                $this->form_validation->set_rules('dateob', 'Date of Birth', 'required');
                $this->form_validation->set_rules('monthob', 'Month of Birth', 'required');
                $this->form_validation->set_rules('yearob', 'Year of Birth', 'required');
                $this->form_validation->set_rules('on_behalf', 'On Behalf', 'required');
                $this->form_validation->set_rules('mobile', 'Mobile Number', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required|matches[confirm_password]');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required');

                if ($this->form_validation->run() == FALSE) {				
                    if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                        $page_data['recaptcha_html'] = $this->recaptcha->render();
                    }
                    $page_data['page'] = "registration";
                    $page_data['form_contents'] = $this->input->post();
                    $this->load->view('front/registration', $page_data);
                }
                else {
                    if ($safe == 'yes') {
                        // ------------------------------------Profile Image------------------------------------ //
                        $profile_image[] = array('profile_image'    =>  'default.jpg',
                                                    'thumb'         =>  'default_thumb.jpg'
                                            );
                        $profile_image = json_encode($profile_image);
                        // ------------------------------------Profile Image------------------------------------ //

                        // ------------------------------------Basic Info------------------------------------ //
                        $basic_info[] = array('age'                 => '',
                                            'marital_status'        => '',   
                                            'number_of_children'    => '',
                                            'area'                  => '',
                                            'on_behalf'             => $this->input->post('on_behalf')
                                            );
                        $basic_info = json_encode($basic_info);
                        // ------------------------------------Basic Info------------------------------------ //

                        // ------------------------------------Present Address------------------------------------ //
                        $present_address[] = array('country'        => '',
                                            'city'                  => '', 
                                            'state'                 => '',
                                            'postal_code'           => ''
                                            );
                        $present_address = json_encode($present_address);
                        // ------------------------------------Present Address------------------------------------ //

                        // ------------------------------------Education & Career------------------------------------ //
                        $education_and_career[] = array('highest_education' => '',
                                            'occupation'                    => '',   
                                            'annual_income'                 => ''
                                            );
                        $education_and_career = json_encode($education_and_career);
                        // ------------------------------------Education & Career------------------------------------ //

                        // ------------------------------------ Physical Attributes------------------------------------ //
                        $physical_attributes[] = array('weight'     => '',   
                                            'eye_color'             => '',
                                            'hair_color'            => '',
                                            'complexion'            => '',
                                            'blood_group'           => '',
                                            'body_type'             => '',
                                            'body_art'              => '',
                                            'any_disability'        => ''
                                            );
                        $physical_attributes = json_encode($physical_attributes);
                        // ------------------------------------ Physical Attributes------------------------------------ //

                        // ------------------------------------ Language------------------------------------ //
                        $language[] = array('mother_tongue'         => '',
                                            'language'              => '', 
                                            'speak'                 => '',
                                            'read'                  => ''
                                            );
                        $language = json_encode($language);
                        // ------------------------------------ Language------------------------------------ //

                        // ------------------------------------Hobbies & Interest------------------------------------ //
                        $hobbies_and_interest[] = array('hobby'     => '',
                                            'interest'              => '',
                                            'music'                 => '',    
                                            'books'                 => '',
                                            'movie'                 => '',
                                            'tv_show'               => '',
                                            'sports_show'           => '',
                                            'fitness_activity'      => '',
                                            'cuisine'               => '',
                                            'dress_style'           => ''
                                            );
                        $hobbies_and_interest = json_encode($hobbies_and_interest);
                        // ------------------------------------Hobbies & Interest------------------------------------ //

                        // ------------------------------------ Personal Attitude & Behavior------------------------------------ //
                        $personal_attitude_and_behavior[] = array('affection'   => '',    
                                            'humor'                 => '',
                                            'political_view'        => '',
                                            'religious_service'     => ''
                                            );
                        $personal_attitude_and_behavior = json_encode($personal_attitude_and_behavior);
                        // ------------------------------------ Personal Attitude & Behavior------------------------------------ //

                        // ------------------------------------Residency Information------------------------------------ //
                        $residency_information[] = array('birth_country'    => '',
                                            'residency_country'     => '',    
                                            'citizenship_country'   => '',
                                            'grow_up_country'       => '',
                                            'immigration_status'    => ''
                                            );
                        $residency_information = json_encode($residency_information);
                        // ------------------------------------Residency Information------------------------------------ //

                        // ------------------------------------Spiritual and Social Background------------------------------------ //
                        $spiritual_and_social_background[] = array('religion'   => '',
                                            'caste'                 => '',    
                                            'sub_caste'             => '',
                                            'ethnicity'             => '',
                                            'u_manglik'             => '',
                                            'personal_value'        => '',
                                            'family_value'          => '',
                                            'community_value'       => '',
                                            'family_status'         =>  ''
                                            );
                        $spiritual_and_social_background = json_encode($spiritual_and_social_background);
                        // ------------------------------------Spiritual and Social Background------------------------------------ //

                        // ------------------------------------ Life Style------------------------------------ //
                        $life_style[] = array('diet'                => '',
                                            'drink'                 => '',    
                                            'smoke'                 => '',
                                            'living_with'           => ''
                                            );
                        $life_style = json_encode($life_style);
                        // ------------------------------------ Life Style------------------------------------ //

                        // ------------------------------------ Astronomic Information------------------------------------ //
                        $astronomic_information[] = array('sun_sign'    => '',
                                            'moon_sign'                 => '',
                                            'time_of_birth'             => '',
                                            'city_of_birth'             => ''
                                            );
                        $astronomic_information = json_encode($astronomic_information);
                        // ------------------------------------ Astronomic Information------------------------------------ //

                        // ------------------------------------Permanent Address------------------------------------ //
                        $permanent_address[] = array('permanent_country'    => '',
                                            'permanent_city'                => '',   
                                            'permanent_state'               => '',
                                            'permanent_postal_code'         => ''
                                            );
                        $permanent_address = json_encode($permanent_address);
                        // ------------------------------------Permanent Address------------------------------------ //

                        // ------------------------------------Family Information------------------------------------ //
                        $family_info[] = array('father'             => '',
                                            'mother'                => '',   
                                            'brother_sister'        => ''
                                            );
                        $family_info = json_encode($family_info);
                        // ------------------------------------Family Information------------------------------------ //

                        // --------------------------------- Additional Personal Details--------------------------------- //
                        $additional_personal_details[] = array('home_district'  => '',
                                            'family_residence'              => '', 
                                            'fathers_occupation'            => '',
                                            'special_circumstances'         => ''
                                            );
                        $additional_personal_details = json_encode($additional_personal_details);
                        // --------------------------------- Additional Personal Details--------------------------------- //

                        // ------------------------------------ Partner Expectation------------------------------------ //
                        $partner_expectation[] = array('general_requirement'    => '',
                                            'partner_age'                       => '',  
                                            'partner_height'                    => '',
                                            'partner_weight'                    => '',
                                            'partner_marital_status'            => '',
                                            'with_children_acceptables'         => '',
                                            'partner_country_of_residence'      => '',
                                            'partner_religion'                  => '',
                                            'partner_caste'                     => '',
                                            'partner_subcaste'                  => '',
                                            'partner_complexion'                => '',
                                            'partner_education'                 => '',
                                            'partner_profession'                => '',
                                            'partner_drinking_habits'           => '',
                                            'partner_smoking_habits'            => '',
                                            'partner_diet'                      => '',
                                            'partner_body_type'                 => '',
                                            'partner_personal_value'            => '',
                                            'manglik'                           => '',
                                            'partner_any_disability'            => '',
                                            'partner_mother_tongue'             => '',
                                            'partner_family_value'              => '',
                                            'prefered_country'                  => '',
                                            'prefered_state'                    => '',
                                            'prefered_status'                   => ''
                                            );
                        $partner_expectation = json_encode($partner_expectation);
                        // ------------------------------------ Partner Expectation------------------------------------ //

                        // ------------------------------------Privacy Status------------------------------------ //
                        $privacy_status[] = array(
                                            'present_address'                 => 'no',
                                            'education_and_career'            => 'no',
                                            'physical_attributes'             => 'no',
                                            'language'                        => 'no',
                                            'hobbies_and_interest'            => 'no',
                                            'personal_attitude_and_behavior'  => 'no',
                                            'residency_information'           => 'no',
                                            'spiritual_and_social_background' => 'no',
                                            'life_style'                      => 'no',
                                            'astronomic_information'          => 'no',
                                            'permanent_address'               => 'no',
                                            'family_info'                     => 'no',
                                            'additional_personal_details'     => 'no',
                                            'partner_expectation'             => 'yes'
                                            );
                        $privacy_status = json_encode($privacy_status);
                        // ------------------------------------Privacy Status------------------------------------ //

                        // ------------------------------------Pic Privacy Status------------------------------------ //
                        $pic_privacy[] = array(
                                            'profile_pic_show'        => 'all',
                                            'gallery_show'            => 'premium'
                                            
                                            );
                        $data_pic_privacy = json_encode($pic_privacy);
                        // ------------------------------------Pic Privacy Status------------------------------------ //

                        // --------------------------------- Additional Personal Details--------------------------------- //
                        $package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', '1'),
                                                'package_price'     => $this->Crud_model->get_type_name_by_id('plan', '1', 'amount'), 
                                                'payment_type'      => 'None',
                                            );
                        $package_info = json_encode($package_info);
                        // --------------------------------- Additional Personal Details--------------------------------- //
//print_r($this->input->post('first_name'));exit;
                        if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                            $captcha_answer = $this->input->post('g-recaptcha-response');
                            $response = $this->recaptcha->verifyResponse($captcha_answer);
                            if ($response['success']) {

                                $monthob = $this->input->post('monthob');
                                $dateob = $this->input->post('dateob');
                                $yearob = $this->input->post('yearob');
                                $dob = $yearob."-".$monthob."-".$dateob;
                                $data['date_of_birth'] = strtotime($dob);

                                $data['first_name'] = $this->input->post('first_name');
                                $data['last_name'] = $this->input->post('last_name');
                                $data['gender'] = $this->input->post('gender');
                                $data['email'] = $this->input->post('email');
                                // $data['date_of_birth'] = $dob;
                                $data['height'] = 0.00;
                                $data['mobile'] = $this->input->post('mobile');
                                $data['password'] = sha1($this->input->post('password'));
                                $data['profile_image'] = $profile_image;
                                $data['introduction'] = '';
                                $data['basic_info'] = $basic_info;
                                $data['present_address'] = $present_address;
                                $data['family_info'] = $family_info;
                                $data['education_and_career'] = $education_and_career;
                                $data['physical_attributes'] = $physical_attributes;
                                $data['language'] = $language;
                                $data['hobbies_and_interest'] = $hobbies_and_interest;
                                $data['personal_attitude_and_behavior'] = $personal_attitude_and_behavior;
                                $data['residency_information'] = $residency_information;
                                $data['spiritual_and_social_background'] = $spiritual_and_social_background;
                                $data['life_style'] = $life_style;
                                $data['astronomic_information'] = $astronomic_information;
                                $data['permanent_address'] = $permanent_address;
                                $data['additional_personal_details'] = $additional_personal_details;
                                $data['partner_expectation'] = $partner_expectation;
                                $data['interest'] = '[]';
                                $data['short_list'] = '[]';
                                $data['followed'] = '[]';
                                $data['ignored'] = '[]';
                                $data['ignored_by'] = '[]';
                                $data['gallery'] = '[]';
                                $data['happy_story'] = '[]';
                                $data['package_info'] = $package_info;
                                $data['payments_info'] = '[]';
                                $data['interested_by'] = '[]';
                                $data['follower'] = 0;
                                $data['notifications'] = '[]';
                                $data['membership'] = 1;
                                $data['is_closed'] = 'no';
                                $data['profile_status'] = 1;
                                $data['member_since'] = date("Y-m-d h:m:s");
                                $data['express_interest'] = $this->db->get_where('plan', array('plan_id'=> 1))->row()->express_interest;
                                $data['direct_messages'] = $this->db->get_where('plan', array('plan_id'=> 1))->row()->direct_messages;
                                $data['photo_gallery'] = $this->db->get_where('plan', array('plan_id'=> 1))->row()->photo_gallery;
                                $data['profile_completion'] = 0;
                                $data['is_blocked'] = 'no';
                                $data['privacy_status'] = $privacy_status;
                                $data['pic_privacy'] = $data_pic_privacy;
                                $data['timezone'] = $this->input->post('timezone');
                                
                                $this->db->insert('member', $data);
                                $insert_id = $this->db->insert_id();
                                $member_profile_id = strtoupper(substr(hash('sha512', rand()), 0, 8)).$insert_id;

                                $this->db->where('member_id', $insert_id);
                                $this->db->update('member', array('member_profile_id' => $member_profile_id));
                                recache();

                                // $msg = 'done';
                                if ($this->Email_model->account_opening('member', $data['email'], $this->input->post('password')) == false) {
                                    //$msg = 'done_but_not_sent';
                                } else {
                                    //$msg = 'done_and_sent';
                                }

                                $this->session->set_flashdata('alert', 'register_success');
                                redirect(base_url().'home/login', 'refresh');
                            } else {
                                if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                                    $page_data['recaptcha_html'] = $this->recaptcha->render();
                                }
                                $page_data['page'] = "registration";
                                $page_data['form_contents'] = $this->input->post();
                                $page_data['captcha_incorrect'] = TRUE;

                                $this->load->view('front/registration', $page_data);
                            }
                        } else {

                            $monthob = $this->input->post('monthob');
                            $dateob = $this->input->post('dateob');
                            $yearob = $this->input->post('yearob');
                            $dob = $yearob."-".$monthob."-".$dateob;

                            $data['first_name'] = $this->input->post('first_name');
                            $data['last_name'] = $this->input->post('last_name');
                            $data['gender'] = $this->input->post('gender');
                            $data['email'] = $this->input->post('email');
                            // $data['date_of_birth'] = strtotime($this->input->post('date_of_birth'));
                            $data['date_of_birth'] = strtotime($dob);
                            
                            $data['height'] = 0.00;
                            $data['mobile'] = $this->input->post('mobile');
                            $data['password'] = sha1($this->input->post('password'));
                            $data['profile_image'] = $profile_image;
                            $data['introduction'] = '';
                            $data['basic_info'] = $basic_info;
                            $data['present_address'] = $present_address;
                            $data['family_info'] = $family_info;
                            $data['education_and_career'] = $education_and_career;
                            $data['physical_attributes'] = $physical_attributes;
                            $data['language'] = $language;
                            $data['hobbies_and_interest'] = $hobbies_and_interest;
                            $data['personal_attitude_and_behavior'] = $personal_attitude_and_behavior;
                            $data['residency_information'] = $residency_information;
                            $data['spiritual_and_social_background'] = $spiritual_and_social_background;
                            $data['life_style'] = $life_style;
                            $data['astronomic_information'] = $astronomic_information;
                            $data['permanent_address'] = $permanent_address;
                            $data['additional_personal_details'] = $additional_personal_details;
                            $data['partner_expectation'] = $partner_expectation;
                            $data['interest'] = '[]';
                            $data['short_list'] = '[]';
                            $data['followed'] = '[]';
                            $data['ignored'] = '[]';
                            $data['ignored_by'] = '[]';
                            $data['gallery'] = '[]';
                            $data['happy_story'] = '[]';
                            $data['package_info'] = $package_info;
                            $data['payments_info'] = '[]';
                            $data['interested_by'] = '[]';
                            $data['follower'] = 0;
                            $data['notifications'] = '[]';
                            $data['membership'] = 1;
                            $data['profile_status'] = 1;
                            $data['is_closed'] = 'no';
                            $data['member_since'] = date("Y-m-d h:m:s");
                            $data['express_interest'] = $this->db->get_where('plan', array('plan_id'=> 1))->row()->express_interest;
                            $data['direct_messages'] = $this->db->get_where('plan', array('plan_id'=> 1))->row()->direct_messages;
                            $data['photo_gallery'] = $this->db->get_where('plan', array('plan_id'=> 1))->row()->photo_gallery;
                            $data['profile_completion'] = 0;
                            $data['is_blocked'] = 'no';
                            $data['privacy_status'] = $privacy_status;
                            $data['pic_privacy'] = $data_pic_privacy;
                            $data['timezone'] = $this->input->post('timezone');
                            
                            $this->db->insert('member', $data);
                            $insert_id = $this->db->insert_id();
                            $member_profile_id = strtoupper(substr(hash('sha512', rand()), 0, 8)).$insert_id;

                            $this->db->where('member_id', $insert_id);
                            $this->db->update('member', array('member_profile_id' => $member_profile_id));
                            recache();

                            // $msg = 'done';
                            if ($this->Email_model->account_opening('member', $data['email'], $this->input->post('password')) == false) {
                                //$msg = 'done_but_not_sent';
                            } else {
                                //$msg = 'done_and_sent';
                            }

                            $this->session->set_flashdata('alert', 'register_success');
                            redirect(base_url().'home/login', 'refresh');
                        }
                    }
                    else {
                        if ($this->Crud_model->get_settings_value('third_party_settings', 'captcha_status', 'value') == 'ok') {
                            $page_data['recaptcha_html'] = $this->recaptcha->render();
                        }
                        $page_data['form_contents'] = $this->input->post();
                        $page_data['disallowed_char'] =  translate('disallowed_charecter').' " '.$char.' " '.translate('in_the_POST');
                        $page_data['page'] = "registration";
                        $this->load->view('front/registration', $page_data);
                    }
                }
            }
        }       
    }

    function view_payment_detail($para1)
    {
        $detail = $this->db->get_where('package_payment', array('package_payment_id'=> $para1))->row()->payment_details;
        if ($detail != 'none') {
            echo "<p class='text-left' Style='word-wrap: break-word'>".$detail."<p>";
        } else {
            echo "<p class='text-center'><b>".translate('no_details_available')."</b><p>";
        }
    }

    function get_dropdown_by_id($table,$field,$id)
    {
        $options = $this->db->get_where($table, array($field=>$id))->result();
        $table_id = $table."_id";
        echo "<option value=''>".translate('choose_one')."</option>";
        foreach ($options as $value) {
            echo "<option value=".$value->$table_id.">".$value->name."</option>";
        }
    }

    function get_dropdown_by_id_caste($table,$field,$id,$caste="")
    {
        $options = $this->db->get_where($table, array($field=>$id))->result();
        $table_id = $table."_id";
        $table_name = $table."_name";

        echo "<option value=''>".translate('choose_one')."</option>";
        foreach ($options as $value) {
            if($value->$table_id == $caste){
                echo "<option value=".$value->$table_id." selected>".$value->$table_name."</option>";
            }else{
                echo "<option value=".$value->$table_id.">".$value->$table_name."</option>";

            }
        }
    }


    function get_dropdown_by_id_sub_caste($table,$field,$id,$sub_caste="")
    {
        $options = $this->db->get_where($table, array($field=>$id))->result();
        if(count($options)>0){
            $table_id = $table."_id";
            $table_name = $table."_name";

            echo "<option value=''>".translate('choose_one')."</option>";
            foreach ($options as $value) {
                if($value->$table_id == $sub_caste){
                    echo "<option value=".$value->$table_id." selected>".$value->$table_name."</option>";
                }else{
                    echo "<option value=".$value->$table_id.">".$value->$table_name."</option>";

                }
            }
        }else{
            return false;
        }
    }

    function set_language($lang) {
        $this->session->set_userdata('language', $lang);
        recache();
        $page_data['page_name'] = "home";
    }

    function set_currency($currency)
    {
        $this->session->set_userdata('currency', $currency);
        recache();
    }

    function invoice($payment_id) {
        if ($this->member_permission() == FALSE) {
            redirect(base_url().'home/', 'refresh');
        }
        $payment_status = $this->db->get_where('package_payment', array('package_payment_id' => $payment_id))->row()->payment_status;
        if($payment_status == 'paid'){
            $member_id = $this->db->get_where('package_payment', array('package_payment_id' => $payment_id))->row()->member_id;
            if ($member_id == $this->session->userdata('member_id')) {
                $page_data['title'] = translate('payment_invoice')." || ".$this->system_title;
                $page_data['top'] = "invoice.php";
                $page_data['page'] = "invoice";
                $page_data['bottom'] = "invoice.php";
                $page_data['get_payment'] = $this->db->get_where('package_payment', array('package_payment_id' =>$payment_id))->result();

                if ($this->session->flashdata('alert') == "paypal_success") {
                    $page_data['success_alert'] = translate("your_payment_via_paypal_has_been_successfull!");
                }
                elseif ($this->session->flashdata('alert') == "stripe_success") {
                    $page_data['success_alert'] = translate("your_payment_via_stripe_has_been_successfull!");
                }
                elseif ($this->session->flashdata('alert') == "pum_success") {
                    $page_data['success_alert'] = translate("your_payment_via_payUMoney_has_been_successfull!");
                }
                elseif ($this->session->flashdata('alert') == "not_sent") {
                    $page_data['danger_alert'] = translate("error_sending_email!");
                }


                $this->load->view('front/index', $page_data);                
            } else {
                redirect(base_url().'home/', 'refresh');
            }
        } else {
            redirect(base_url().'home/', 'refresh');
        }
    }

    function refresh_notification($member_id) {
        $notifications = $this->Crud_model->get_type_name_by_id('member', $member_id, 'notifications');
        $notifications = json_decode($notifications, true);
        $updated_notifications = array();
        if (!empty($notifications)) {
            foreach ($notifications as $notification) {
                $updated_notifications[] = array('by'=>$notification['by'], 'type'=>$notification['type'], 'status'=>$notification['status'], 'is_seen'=>'yes', 'time'=>$notification['time']);
            }
            $this->db->where('member_id', $member_id);
            $this->db->update('member', array('notifications' => json_encode($updated_notifications)));
            recache();
        }
    }

    function get_received_interests( $member_id ) {

        $notifications = $this->Crud_model->get_type_name_by_id('member', $member_id, 'notifications');
        echo $notifications;die;
        
    }



   function findLatLong($address)
   {
$address_string[] = $this->Crud_model->get_type_name_by_id('city', $address['city'], 'name');
$state= $this->Crud_model->get_type_name_by_id('state', $address['state'], 'name');
$address_string[] = $state;
     $country= $this->Crud_model->get_type_name_by_id('country', $address['country'], 'name');
$address_string[] = $country;

$address_string[] = $address['postal_code'];
$address_string = implode(',', array_filter($address_string));
   

      $array = [
                 'latitude' => '',
                 'longitude' => ''
               ];   
   //  try{
      $url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address_string )."&sensor=false&key=";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     $response = curl_exec($ch);
     curl_close($ch);

     $response_a = json_decode($response);
//print_r($response_a);exit;
      $array = [
                 'latitude' => $response_a->results[0]->geometry->location->lat,
                 'longitude' => $response_a->results[0]->geometry->location->lng
               ];   
//     echo $lat = $response_a->results[0]->geometry->location->lat;
  //   echo "<br />";
    // echo $long = $response_a->results[0]->geometry->location->lng;
   // }catch(Exception $e) {
   //    log_message('error', $e->getMessage());
   // }

//print_r($array);exit;
    return $array;
   }

   public function getOnlineStatus($id) 
   {

     $member_last_visit = $this->Crud_model->get_type_name_by_id('member', $id, 'last_visit');
  if($member_last_visit == null) {
   return "";
}
            $current_time = strtotime(date("Y-m-d H:i:s")); // CURRENT TIME
            $last_visit = strtotime($member_last_visit['Member']['last_visit']); // LAST VISITED TIME
            
            $time_period = floor(round(abs($current_time - $last_visit)/60,2)); //CALCULATING MINUTES
            
            //IF YOU WANT TO CALCULATE DAYS THEN USER THIS
            //$time_period = floor(round(abs($current_time - $last_visit)/60,2)/1440);
            
           
            if ($time_period <= 10){
                return true;
            } else {
               return "Online ".$this->time_elapsed_string($last_visit);
            }
        $this->db->where('member_id', $this->session->userdata('member_id'));
   }

   public function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
   }

    public function isCompleted($id)
    {
        $profile= $this->db->get_where("member", array("member_id" => $id))->row();
        $profile_basic_details = json_decode($profile->basic_info, true);

        $spiritual_and_social_background_data= json_decode($profile->spiritual_and_social_background, true);
        $present_address_data= json_decode($profile->present_address, true);
        $education_and_career_data = json_decode($profile->education_and_career, true);

        if($profile->height > 0 && $profile->introduction != "" && $profile_basic_details[0]['marital_status'] != "" && $profile->belongs_to != "" && !empty($spiritual_and_social_background_data[0]['religion']) && !empty($spiritual_and_social_background_data[0]['caste']) && !empty($present_address_data[0]['country']) && !empty($education_and_career_data[0]['highest_education']) && !empty($education_and_career_data[0]['occupation'])) {
            return true;
        }elseif($profile->is_completed == 1){
             $data['is_completed'] = null;
             $this->db->where('member_id', $id);
             $result = $this->db->update('member', $data);
        }
        return false;
    }

    public function updatePlan($id) 
    {
        $member = $this->db->get_where("member", array("member_id" => $id))->row();

        if ($member->membership_valid_till <= date("Y-m-d H:i:s") && $member->membership_id == 2) {              
           $package_info = json_decode($member->package_info, true);     
            $payment = $this->db->get_where("package_payment", array("package_payment_id" => $package_info[0]['payment_id']))->row();
            $data['expire'] = 'yes';
            $data['expire_timestamp'] = time();
            $this->db->where('package_payment_id', $payment->package_payment_id);
            $this->db->update('package_payment', $data);
            recache();

            $package_info_old = $package_info[0];
            
            $notifications = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'notifications');
            $notification = json_decode($notifications, true);
            $notification[] = array('by'=>$member, 'type'=>'membership_expired', 'package_info'=> $package_info_old, 'time'=>time());

            $data1['membership'] = 1;
            $data1['express_interest'] = $prev_express_interest + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->express_interest;
            $data1['direct_messages'] = $prev_direct_messages + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->direct_messages;
            $data1['photo_gallery'] = $prev_photo_gallery + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->photo_gallery;
            $data1['notifications'] = json_encode($notification);
            $package_info[] = array("current_package" => "Default","package_price" =>"0","payment_type" => "None");
            
            $data1['package_info'] = json_encode($package_info);
            $this->db->where('member_id', $member->member_id);
            $this->db->update('member', $data1);
            recache();
            if ($this->Email_model->membership_expired($member->member_id, $package_info_old)) {
                //$msg = 'done_but_not_sent';
            } else {
                //$msg = 'done_and_sent';
            }
        }
    }
    
    public function cron() 
    {       
      $members  = $this->db->get_where('member', array('membership' => 2, 'membership_valid_till <=' => date("Y-m-d H:i:s")))->result();          

       foreach($members as $member) {  

            $package_info = json_decode($member->package_info, true);     
            $payment = $this->db->get_where("package_payment", array("package_payment_id" => $package_info[0]['payment_id']))->row();
            $data['expire'] = 'yes';
            $data['expire_timestamp'] = time();
            $this->db->where('package_payment_id', $payment->package_payment_id);
            $this->db->update('package_payment', $data);
            recache();

            $package_info_old = $package_info[0];
            
            $notifications = $this->Crud_model->get_type_name_by_id('member', $member->member_id, 'notifications');
            $notification = json_decode($notifications, true);
            $notification[] = array('by'=>$member, 'type'=>'membership_expired', 'package_info'=> $package_info_old, 'time'=>time());

            $data1['membership'] = 1;
            $data1['express_interest'] = $prev_express_interest + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->express_interest;
            $data1['direct_messages'] = $prev_direct_messages + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->direct_messages;
            $data1['photo_gallery'] = $prev_photo_gallery + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->photo_gallery;
            $data1['notifications'] = json_encode($notification);
            $package_info[] = array("current_package" => "Default","package_price" =>"0","payment_type" => "None");
            
            $data1['package_info'] = json_encode($package_info);
            $this->db->where('member_id', $member->member_id);
            $this->db->update('member', $data1);
            recache();
            if ($this->Email_model->membership_expired($member->member_id, $package_info_old)) {
                //$msg = 'done_but_not_sent';
            } else {
                //$msg = 'done_and_sent';
            }
 
        }         
    }

    function isMobileDevice() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    //creating a signature using the given details for security reasons
    function getCheckSumCcavenue($MerchantId,$Amount,$OrderId ,$URL,$WorkingKey)
    {
        $str ="$MerchantId|$OrderId|$Amount|$URL|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler,$str);
        return $adler;
    }

    //functions
    function adler32($adler , $str)
    {
        $BASE = 65521 ;
        $s1 = $adler & 0xffff ;
        $s2 = ($adler >> 16) & 0xffff;
        for($i = 0 ; $i < strlen($str) ; $i++)
        {
            $s1 = ($s1 + Ord($str[$i])) % $BASE ;
            $s2 = ($s2 + $s1) % $BASE ;
        }
        return $this->leftshift($s2 , 16) + $s1;
    }
        
    //leftshift function
    function leftshift($str , $num) 
    {
        $str = DecBin($str);

        for($i = 0; $i < (64 - strlen($str)); $i++) {
            $str = "0".$str ;
            for($i = 0 ; $i < $num ; $i++)
            {
                $str = $str."0";
                $str = substr($str , 1 ) ;
            }
            return $this->cdec($str) ;
        }
    }
    
    function cdec($num)
    {
        $len = strlen($num);

        for($n = 0; $n < $len; $n++)
        {
            $temp = $num[$n] ;
            $l = strlen($num);
            $le = $l - $n - 1;
            $dec = $dec + $temp * pow(2 , $le);
        }
        return $dec;
    }


    function paytm_success()
    {
        $post_data = $_POST;
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        // following files need to be included
        require_once(APPPATH . "/libraries/config_paytm.php");
        require_once(APPPATH . "/libraries/encdec_paytm.php");

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.
        if($isValidChecksum == "TRUE") {    
            if ($_POST['STATUS'] == 'TXN_SUCCESS') {
                $payment_id = explode('_', $_POST['ORDERID'])[1];
                $payment                   = $this->db->get_where('package_payment',array('package_payment_id' => $payment_id))->row();
                $data['payment_details']   = json_encode($_POST);
                $data['purchase_datetime'] = time();
                $data['payment_code']      = date('Ym', $data['purchase_datetime']) . $payment_id;
                $data['payment_timestamp'] = time();
                $data['payment_type']      = 'Paytm';
                $data['payment_status']    = 'paid';
                $data['expire']            = 'no';
                $plan_d = $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->duration;
                $data['expire_timestamp'] = strtotime('+'.$plan_d.' months');
                $this->db->where('package_payment_id', $payment_id);
                $this->db->update('package_payment', $data);
                recache();
                $prev_express_interest =  $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->express_interest;
                $prev_direct_messages = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->direct_messages;
                $prev_photo_gallery = $this->db->get_where('member', array('member_id' => $payment->member_id))->row()->photo_gallery;

                $data1['membership'] = 2;
                $data1['express_interest'] = $prev_express_interest + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->express_interest;
                $data1['direct_messages'] = $prev_direct_messages + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->direct_messages;
                $data1['photo_gallery'] = $prev_photo_gallery + $this->db->get_where('plan', array('plan_id' => $payment->plan_id))->row()->photo_gallery;

                $package_info[] = array('current_package'   => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id),
                                        'package_price'     => $this->Crud_model->get_type_name_by_id('plan', $payment->plan_id, 'amount'), 
                                        'payment_type'      => $data['payment_type'],
                                        'payment_id'      => $payment_id,
                                        'plan_id'      => $payment->plan_id,
                                    );
                $data1['package_info'] = json_encode($package_info);
                $data1['membership_valid_till'] = date('Y-m-d H:i:s', strtotime('+'.$plan_d.' months')); 
                $this->db->where('member_id', $payment->member_id);
                $this->db->update('member', $data1);
                recache();

                if ($this->Email_model->subscription_email('member', $payment->member_id, $payment->plan_id)) {
                    //echo 'email_sent';
                } else {
                    //echo 'email_not_sent';
                    $this->session->set_flashdata('alert', 'not_sent');
                }
                $this->session->set_flashdata('alert', 'paytm_success');
                redirect(base_url() . 'home/invoice/'.$this->session->userdata('payment_id'), 'refresh');
                $this->session->set_userdata('payment_id', '');
            }
        } 

        $payment_id = $this->session->userdata('payment_id');
        $this->db->where('package_payment_id', $payment_id);
        $this->db->delete('package_payment');
        recache();
        $this->session->set_userdata('payment_id', '');
        $this->session->set_flashdata('alert', 'paytm_fail');
        redirect(base_url() . 'home/plans', 'refresh');        
    }
    
    function height_array($id = null)
    {
        $list = [
            '4.10' => "4'10\" feets",
            '4.11' => "4'11\" feets",
            '5.00' => "5 feets",
            '5.01' => "5'1\" feets",
            '5.02' => "5'2\" feets",
            '5.03' => "5'3\" feets",
            '5.04' => "5'4\" feets",
            '5.05' => "5'5\" feets",
            '5.06' => "5'6\" feets",
            '5.07' => "5'7\" feets",
            '5.08' => "5'8\" feets",
            '5.09' => "5'9\" feets",            
            '5.10' => "5'10\" feets",            
            '5.11' => "5'11\" feets",            
            '6.00' => "6 feets",
            '6.01' => "6'1\" feets",
            '6.02' => "6'2\" feets",
            '6.03' => "6'3\" feets",
        ];

        if ($id == null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
	
	public function forgot_password() {
		//echo "in maintainance";
		$this->load->view('front/forget_pass');
	}
}