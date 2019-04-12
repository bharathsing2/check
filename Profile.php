<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends CI_Controller
{
    function __construct(){
        parent::__construct();
		$this->load->model('profile_model');
		$this->load->model('dashboard_model');
		$this->load->library(array('ion_auth'));
                $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter','ion_auth'),$this->config->item('error_end_delimiter','ion_auth'));
		$this->lang->load('auth');
                $this->config->load('ion_auth',TRUE);
		$this->load->library(array('email'));
		$this->lang->load('ion_auth');
                $this->load->helper(array('cookie','language','url'));
                $email_config=$this->config->item('email_config','ion_auth');
		if($this->config->item('use_ci_email','ion_auth') && isset($email_config) && is_array($email_config))
		{
		    $this->email->initialize($email_config);
		}	
    }
   
    //Function - 1 :  Get current user profile
    function index($usercode)
    {
	if(!$this->ion_auth->logged_in()){redirect('login','refresh');}
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $SESS_LAYOUT=$this->session->userdata('SESS_LAYOUT');
        if($SESS_TYPE_ID == 12){ redirect(); }
        //Get user profile through usercode
        if(!empty($usercode))
        {
            if(empty($this->profile_model->user_check($usercode))){redirect();}
            $USER_ID=$this->profile_model->get_user_id($usercode);
            $USER_TYPE=$this->profile_model->get_user_type($usercode);
        }
        else
        { //Get current user profile
            $USER_ID=$SESS_USER_ID;
            $USER_TYPE =$SESS_TYPE_ID; 
        }
        $data['user_profile_role']=$USER_TYPE;
        $data['user_profile_info']=$this->profile_model->get_profile_data($USER_ID,$USER_TYPE);
        $data['following_count']=$this->profile_model->following_count($USER_ID);
        $data['follower_count']=$this->profile_model->follower_count($USER_ID);
        $data['quick_links']=$this->profile_model->get_quick_links($USER_ID,$USER_TYPE);
        $data['page_no_follower']=1;
        $data['page_no_following']=1;
        $data['posts_count']=$this->profile_model->get_posts_count($USER_ID);
        $data['user_code']=$this->profile_model->get_usercode($USER_ID);
        $data['SESS_NAME']=$this->session->userdata('SESS_NAME');
        $data['profile_user_id']=$USER_ID;
        $data['SESS_USER_ID'] =$SESS_USER_ID; 
        $data['SESS_PROFILE_PICTURE']=$this->session->userdata('SESS_PROFILE_PICTURE'); 
        $data['followers_list']=array();
        $data['following_list']=array();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$USER_ID;
        if($SESS_USER_ID != $USER_ID){ $data['user_relation_status']=$this->profile_model->user_relation($USER_ID,$SESS_USER_ID); }
        $this->load->view('profile/profile-'.$SESS_LAYOUT,$data);
    }

    //Function - 5 : Edit profile
    function edit_profile()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_roles=array(8,9,10,11);
        $admin_roles=array(1,2,3,4,5,17,18,19,20,21,22,23);
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        if($SESS_USER_ID == $user_id)
        {
            $user_type=$this->session->userdata('SESS_TYPE_ID'); 
        }
        else
        {
            $user_type=$this->profile_model->get_user_type($this->input->post('user_code'));
        }
        if($user_type == 6)
        {
            $data['youth_info']=$this->profile_model->get_youth_profileinfo($user_id);
            $data['organisation_data']=$this->profile_model->get_organisation_category();
            //$data['location_ethnicity']=$this->profile_model->get_location_ethnicity();
            $data['location_iwi']=$this->profile_model->get_location_iwi();
            $data['region_list']=$this->profile_model->get_region_list();
            $data['city_list']=$this->profile_model->get_city_list(0);
            $data['intended_destination']=$this->profile_model->get_intended_destination();
            $data['licence_type']=$this->profile_model->get_licence_type();
            $this->load->view('profile/profile-a-info',$data);
            
        }
        elseif(in_array((int) $user_type,$user_roles,TRUE))
        {
            if($user_type == 8)
            {
                $data['business_category_list']=$this->profile_model->business_category();
                $data['business_info']=$this->profile_model->get_business_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Business';
            }
            else
            {
                $data['business_category_list']=$this->profile_model->organization_category();
                $data['business_info']=$this->profile_model->get_organization_info($user_id,$user_type);                
                $data['PROFILE_INFO_NAME']='Organisation';   
            }
            $data['business_contact_info']=$this->profile_model->get_business_contact_info($user_id,$user_type,0);
            $data['social_account_info']=$this->profile_model->get_social_account_info($user_id,$user_type);
            $data['quick_links']=$this->profile_model->get_quick_links($user_id,$user_type);
            $data['region_list']=$this->profile_model->get_region_list();
            $data['SESS_TYPE_ID']=$user_type;
            $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
            $data['EDIT_PROFILE_USER_ID']=$user_id;
            $this->load->view('profile/edit-profile-b',$data);
        }
        elseif($user_type == 7)
        {
            $data['teacher_info']=$this->profile_model->get_teacher_info($user_id);
            $data['school_name']=$this->profile_model->get_school_name();
            $data['city_list']=$this->profile_model->get_city_list(0);
            $data['suburb_list']=$this->profile_model->get_suburb_list(0,0);
            $data['experience']=$this->profile_model->get_user_experience($user_id,0);
            $data['education']=$this->profile_model->get_user_education($user_id,0);
            $data['volunteering']=$this->profile_model->get_user_volunteering($user_id,0);
            $data['skills']=$this->profile_model->get_skill_user($user_id);
            $data['technical_skills']=$this->profile_model->get_technical_skills($user_id,0);
            $data['interests']=$this->profile_model->get_interests($user_id,0);
            $data['achievement']=$this->profile_model->get_achievement_user($user_id,0);
            $data['language']=$this->profile_model->get_user_language($user_id);
            $data['business_category_list']=$this->profile_model->business_category();
            $data['job_type']=$this->profile_model->get_job_type();
            $data['region_list']=$this->profile_model->get_region_list();
            $data['organisation_category']=$this->profile_model->get_organisation_category();
            $data['qualification_category']=$this->profile_model->get_qualification_category();
            $data['volunteering_category']=$this->profile_model->get_volunteering_category();
            $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
            $data['EDIT_PROFILE_USER_ID']=$user_id;
            $this->load->view('profile/profile-b-teacher',$data);
        }
        elseif(in_array((int) $user_type,$admin_roles,TRUE)) 
        {
            $data['admin_info']=$this->profile_model->get_admin_info($user_id);
            $data['city_list']=$this->profile_model->get_city_list(0);
            $data['suburb_list']=$this->profile_model->get_suburb_list(0,0);
            $data['experience']=$this->profile_model->get_user_experience($user_id,0);
            $data['education']=$this->profile_model->get_user_education($user_id,0);
            $data['volunteering']=$this->profile_model->get_user_volunteering($user_id,0);
            $data['skills']=$this->profile_model->get_skill_user($user_id);
            $data['technical_skills']=$this->profile_model->get_technical_skills($user_id,0);
            $data['interests']=$this->profile_model->get_interests($user_id,0);
            $data['achievement']=$this->profile_model->get_achievement_user($user_id,0);
            $data['language']=$this->profile_model->get_user_language($user_id);
            $data['business_category_list']=$this->profile_model->business_category();
            $data['job_type']=$this->profile_model->get_job_type();
            $data['region_list']=$this->profile_model->get_region_list();
            $data['organisation_category']=$this->profile_model->get_organisation_category();
            $data['qualification_category']=$this->profile_model->get_qualification_category();
            $data['volunteering_category']=$this->profile_model->get_volunteering_category();
            $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
            $data['EDIT_PROFILE_USER_ID']=$user_id;
            $this->load->view('profile/profile-b-admin',$data);
        }
    }
    
    //Function - 6 : Business subcategory list
    function get_industry_subcategory()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $biz_subcategory=array();
        $biz_category_id=$this->input->post('biz_category_id');
        if($biz_category_id){$biz_subcategory=$this->profile_model->business_sub_category($biz_category_id);}
        echo json_encode($biz_subcategory);
    }
    
    //Function - 7 : get_business_contact
    function get_update_location()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $location_id=$this->input->post('location_id');
        $user_type=$this->session->userdata('SESS_TYPE_ID');
        $data['business_contact_detail']=$this->profile_model->get_business_contact_info($user_id,$user_type,$location_id);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['city_list']=$this->profile_model->get_city_list(0);
        $data['suburb_list']=$this->profile_model->get_suburb_list(0,0);
        if($user_type == 8){$data['PROFILE_INFO_NAME']='Business';}else{$data['PROFILE_INFO_NAME']='Organisation';}
        $this->load->view('profile/business-contact-details',$data);
    }
    
    //Function - 8 : get city list
    function get_city()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $city=array();
        $region_id=$this->input->post('region_id');
        if($region_id){$city=$this->profile_model->get_city_list($region_id);}
        echo json_encode($city);
    }
    
    //Function - 9 : get suburb list
    function get_suburb()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $suburb=array();
        $region_id=$this->input->post('region_id');
        $city_id=$this->input->post('city_id');
        if($region_id && $city_id){$suburb=$this->profile_model->get_suburb_list($region_id,$city_id);}
        echo json_encode($suburb);
    }
    
    //Function - 10 : update business info
    function update_basic_info()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_roles=array(7,8,9,10,11);
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $user_type=$SESS_TYPE_ID;
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $business_name=$this->input->post('business_name');
        $business_website=$this->input->post('business_website');
        $business_video=$this->input->post('business_video');
        $business_description=$this->input->post('business_description');
        $business_category_id=$this->input->post('business_category_id');
        $business_subcategory_id=$this->input->post('business_subcategory_id');
        if(in_array((int) $user_type,$user_roles,TRUE))
        {
            if($user_type == 8)
            {
                $business_data=array('biz_name'=>$business_name,'biz_about_video'=>$business_video,'biz_about_description'=>$business_description,'biz_ica_category_id'=>$business_category_id,'biz_isc_sub_category_id'=>$business_subcategory_id);
                $location_data=array('lou_website1'=>$business_website);
                $um_arr=array('um_name'=>$business_name);
                $biz_status=$this->profile_model->update_business_data($user_id,$business_data,$location_data,$um_arr);
                $data['business_category_list']=$this->profile_model->business_category();
                $data['business_info']=$this->profile_model->get_business_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Business';
                if($biz_status == TRUE){$sess_name_upt=array('SESS_NAME'=>$business_name);  $this->session->set_userdata($sess_name_upt);}else{}
            }
            else
            {
                $business_data=array('ogm_name'=>$business_name,'ogm_about_video'=>$business_video,'ogm_about_description'=>$business_description,'ogm_ogc_category_id'=>$business_category_id);
                $location_data=array('lou_website1'=>$business_website);
                $um_arr=array('um_name'=>$business_name);
                $biz_status=$this->profile_model->update_organization_data($user_id,$business_data,$location_data,$um_arr);
                $data['business_category_list']=$this->profile_model->organization_category();
                $data['business_info']=$this->profile_model->get_organization_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Organisation';  
                  if($biz_status == TRUE){$sess_name_upt=array('SESS_NAME'=>$business_name);  $this->session->set_userdata($sess_name_upt);}else{}
            }
            
          
        }
        $data['business_contact_info']=$this->profile_model->get_business_contact_info($user_id,$user_type,0);
        $data['social_account_info']=$this->profile_model->get_social_account_info($user_id,$user_type);
        $data['quick_links']=$this->profile_model->get_quick_links($user_id,$user_type);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $data['SESS_TYPE_ID']=$user_type;
        $this->load->view('profile/edit-profile-b',$data);
    }
    
    //Function - 11 : update business social links
    function update_social_links()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_roles=array(7,8,9,10,11);
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $user_type=$SESS_TYPE_ID;
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $link_my_fb=$this->input->post('link_my_fb');
        $fb=$this->input->post('fb');
        $instagram=$this->input->post('instagram');
        $twitter=$this->input->post('twitter');
        $google_plus=$this->input->post('google_plus');
        $youtube=$this->input->post('youtube');
        $behance=$this->input->post('behance');
        $blog=$this->input->post('blog');
        $social_link_data=array('slu_link_my_facebook'=>$link_my_fb,'slu_facebook'=>$fb,'slu_twitter'=>$twitter,'slu_instagram'=>$instagram,'slu_google_plus'=>$google_plus,'slu_behance'=>$behance,'slu_youtube'=>$youtube,'slu_blog'=>$blog);
        $data['social_status']=$this->profile_model->update_business_social_data($user_id,$social_link_data);
        if(in_array((int) $user_type,$user_roles,TRUE))
        {
            if($user_type == 8)
            {
                $data['business_category_list']=$this->profile_model->business_category();
                $data['business_info']=$this->profile_model->get_business_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Business';
            }
            else
            {
                $data['business_category_list']=$this->profile_model->organization_category();
                $data['business_info']=$this->profile_model->get_organization_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Organisation';
            }
        }
        $data['business_contact_info']=$this->profile_model->get_business_contact_info($user_id,$user_type,0);
        $data['social_account_info']=$this->profile_model->get_social_account_info($user_id,$user_type);
        $data['quick_links']=$this->profile_model->get_quick_links($user_id,$user_type);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $data['SESS_TYPE_ID']=$user_type;
        $this->load->view('profile/edit-profile-b',$data);   
    }
    
    //Function - 12 : add business quick links
    function add_quick_links()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_roles=array(7,8,9,10,11);
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $user_type=$SESS_TYPE_ID;
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $quick_link=$this->input->post('quick_link');
        $quick_link_title='';
        $pieces=parse_url($quick_link);
        $domain=isset($pieces['host']) ? $pieces['host'] : '';
        if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i',$domain,$regs)){$quick_link_title=$regs['domain'];}
        $created_on=date("Y-m-d h:i:s");
        $quick_link_data=array('qlu_title'=>$quick_link_title,'qlu_link'=>$quick_link,'qlu_display'=>1,'qlu_created_on'=>$created_on,'qlu_active'=>1);
        $data['link_status']=$this->profile_model->add_quick_link($user_id,$quick_link_data);
        if(in_array((int) $user_type,$user_roles,TRUE))
        {
            if($user_type == 8)
            {
                $data['business_category_list']=$this->profile_model->business_category();
                $data['business_info']=$this->profile_model->get_business_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Business';
            }
            else
            {
                $data['business_category_list']=$this->profile_model->organization_category();
                $data['business_info']=$this->profile_model->get_organization_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Organisation';
            }
        }
        $data['business_contact_info']=$this->profile_model->get_business_contact_info($user_id,$user_type,0);
        $data['social_account_info']=$this->profile_model->get_social_account_info($user_id,$user_type);
        $data['quick_links']=$this->profile_model->get_quick_links($user_id,$user_type);$data['region_list']=$this->profile_model->get_region_list();
        $data['region_list']=$this->profile_model->get_region_list();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $data['SESS_TYPE_ID']=$user_type;
        $this->load->view('profile/edit-profile-b',$data);
    }
    
    //Function - 12 : Remove quick link
    function delete_quick_links()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $quick_link_id=$this->input->post('quick_link_id');
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $data['link_del_status']=$this->profile_model->quick_link_remove($quick_link_id);
        $user_type=$this->session->userdata('SESS_TYPE_ID');
        if($user_type==8){
            $data['business_category_list']=$this->profile_model->business_category();
            $data['business_info']=$this->profile_model->get_business_info($user_id,$user_type);
            $data['PROFILE_INFO_NAME']='Business';
            
        }elseif(in_array((int) $user_type,array(9,10,11),TRUE)){
            $data['business_category_list']=$this->profile_model->organization_category();
            $data['business_info']=$this->profile_model->get_organization_info($user_id,$user_type);
            $data['PROFILE_INFO_NAME']='Organisation';
        }
        $data['business_contact_info']=$this->profile_model->get_business_contact_info($user_id,$user_type,0);
        $data['social_account_info']=$this->profile_model->get_social_account_info($user_id,$user_type);
        $data['quick_links']=$this->profile_model->get_quick_links($user_id,$user_type);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $data['SESS_TYPE_ID']=$user_type;
        $this->load->view('profile/edit-profile-b',$data);
    }
    
    //Function - 13 : update business contact info
    function update_location()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_roles=array(7,8,9,10,11);
        $bo_email=array();
        $um_email=array();
        $org_roles=array(9,10,11);
        $email1='';
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $user_type=$SESS_TYPE_ID;
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $location_id=$this->input->post('location_id');
        $location_title=$this->input->post('location_title');
        $address=$this->input->post('address');
        $region_name=$this->input->post('region_name');
        $city_name=$this->input->post('city_name');
        $suburb_name=$this->input->post('suburb_name');
        $postcode=$this->input->post('postcode');
        $phone1=$this->input->post('phone1');
        //$phone2=$this->input->post('phone2');
        if($this->input->post('email1')){
            $email1=$this->input->post('email1'); if($user_type==8){$bo_email=array('biz_registered_email_id'=>$email1);} elseif(in_array((int) $user_type,$org_roles,TRUE)){ $bo_email=array('ogm_registered_email_id'=>$email1); } 
            $um_email=array('um_email'=>$email1);
        }
        //$email2=$this->input->post('email2');
        $person_name=$this->input->post('person_name');
        $person_role=$this->input->post('person_role');
        $person_phone1=$this->input->post('person_phone1');
        $person_phone2=$this->input->post('person_phone2');
        $person_email=$this->input->post('person_email');
        $business_contact_data=array('lou_address'=>$location_title,'lou_postal_address'=>$address,'lou_re_region_id'=>$region_name,'lou_ci_city_id'=>$city_name,'lou_su_suburb_id'=>$suburb_name,'lou_postcode'=>$postcode,'lou_phone1'=>$phone1,'lou_email1'=>$email1,'lou_contact_person_name'=>$person_name,'lou_contact_person_role'=>$person_role,'lou_contact_person_phone1'=>$person_phone1,'lou_contact_person_phone2'=>$person_phone2,'lou_contact_person_email'=>$person_email);
        $data['biz_contact_status']=$this->profile_model->update_business_contact_data($user_id,$user_type,$location_id,$business_contact_data,$bo_email,$um_email);
        if(in_array((int) $user_type,$user_roles,TRUE))
        {
            if($user_type == 8)
            {
                $data['business_category_list']=$this->profile_model->business_category();
                $data['business_info']=$this->profile_model->get_business_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Business';
            }
            else
            {
                $data['business_category_list']=$this->profile_model->organization_category();
                $data['business_info']=$this->profile_model->get_organization_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Organisation';
            }
        }
        $data['business_contact_info']=$this->profile_model->get_business_contact_info($user_id,$user_type,0);
        $data['social_account_info']=$this->profile_model->get_social_account_info($user_id,$user_type);
        $data['quick_links']=$this->profile_model->get_quick_links($user_id,$user_type);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $data['SESS_TYPE_ID']=$user_type;
        $this->load->view('profile/edit-profile-b',$data);
    }
    
    //Function - 14 : add business contact info
    function add_location()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_roles=array(7,8,9,10,11);
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $user_type=$SESS_TYPE_ID;
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $location_title=$this->input->post('location_title');
        $address=$this->input->post('address');
        $region_name=$this->input->post('region_name');
        $city_name=$this->input->post('city_name');
        $suburb_name=$this->input->post('suburb_name');
        $postcode=$this->input->post('postcode');
        $phone1=$this->input->post('phone1');
        //$phone2=$this->input->post('phone2');
        //$email1=$this->input->post('email1');
        //$email2=$this->input->post('email2');
        $person_name=$this->input->post('person_name');
        $person_role=$this->input->post('person_role');
        $person_phone1=$this->input->post('person_phone1');
        $person_phone2=$this->input->post('person_phone2');
        $person_email=$this->input->post('person_email');
        $contact_data=array('lou_address'=>$location_title,'lou_postal_address'=>$address,'lou_re_region_id'=>$region_name,'lou_ci_city_id'=>$city_name,'lou_su_suburb_id'=>$suburb_name,'lou_postcode'=>$postcode,'lou_phone1'=>$phone1,'lou_contact_person_name'=>$person_name,'lou_contact_person_role'=>$person_role,'lou_contact_person_phone1'=>$person_phone1,'lou_contact_person_phone2'=>$person_phone2,'lou_contact_person_email'=>$person_email);
        $data['contact_status']=$this->profile_model->add_business_contact_data($user_id,$contact_data);
        if(in_array((int) $user_type,$user_roles,TRUE))
        {
            if($user_type == 8)
            {
                $data['business_category_list']=$this->profile_model->business_category();
                $data['business_info']=$this->profile_model->get_business_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Business';
            }
            else
            {
                $data['business_category_list']=$this->profile_model->organization_category();
                $data['business_info']=$this->profile_model->get_organization_info($user_id,$user_type);
                $data['PROFILE_INFO_NAME']='Organisation';
            }
        }
        $data['business_contact_info']=$this->profile_model->get_business_contact_info($user_id,$user_type,0);
        $data['social_account_info']=$this->profile_model->get_social_account_info($user_id,$user_type);
        $data['quick_links']=$this->profile_model->get_quick_links($user_id,$user_type);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $data['SESS_TYPE_ID']=$user_type;
        $this->load->view('profile/edit-profile-b',$data);
    }
    
    //Function - 15 : get profile
    function get_profile()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $user_code=$this->input->post('user_code');
        $user_id=$this->profile_model->get_user_id($user_code);
        $data['profile_info']=$this->profile_model->get_youth_profile_info($user_id);
        $data['job_wishlist']=$this->profile_model->get_job_wishlist($user_id);
        $data['testimonials']=$this->profile_model->get_user_testimonial($user_id);
        $data['experience']=$this->profile_model->get_user_experience($user_id,0);
        $data['education']=$this->profile_model->get_user_education($user_id,0);
        $data['volunteering']=$this->profile_model->get_user_volunteering($user_id,0);
        $data['skills']=$this->profile_model->get_skill_user($user_id);
        $data['achievement']=$this->profile_model->get_achievement_user($user_id,0);
        $data['user_cv']=$this->profile_model->get_user_cv($user_id);
        $data['technical_skills']=$this->profile_model->get_technical_skills($user_id,0);
        $data['interests']=$this->profile_model->get_interests($user_id,0);
        $data['language']=$this->profile_model->get_user_language($user_id);
        $data['business_category_list']=$this->profile_model->business_category();
        $data['job_type']=$this->profile_model->get_job_type();
        $data['region_list']=$this->profile_model->get_region_list();
        $data['organisation_category']=$this->profile_model->get_organisation_category();
        $data['qualification_category']=$this->profile_model->get_qualification_category();
        $data['volunteering_category']=$this->profile_model->get_volunteering_category();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        if($SESS_USER_ID!=$user_id){$SESS_TYPE_ID=$this->profile_model->get_user_type($user_code);}
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-youth',$data);
    }
    
    //Function - 16 : get youth experience
    function get_update_experience()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $employment_id=$this->input->post('employment_id');
        $data['youth_experience']=$this->profile_model->get_user_experience($user_id,$employment_id);
        $data['business_category_list']=$this->profile_model->business_category();
        $data['job_type']=$this->profile_model->get_job_type();
        $this->load->view('profile/profile-a-experience',$data);
    }
    
    //Function - 17 : update youth experience
    function update_experience()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $exp_id=$this->input->post('exp_id');
        $exp_title=$this->input->post('exp_title');
        $exp_designation=$this->input->post('exp_designation');
        $job_description=$this->input->post('job_description');
        $business_category_id=$this->input->post('business_category_id');
        $business_subcategory_id=$this->input->post('business_subcategory_id');
        $exp_job_type=$this->input->post('exp_job_type');
        $exp_start_date=$this->input->post('exp_start_date');
        $exp_end_date=$this->input->post('exp_end_date');
        $exp_status=$this->input->post('exp_status');
        $start_year=date('Y',strtotime('01-'.$exp_start_date)); 
        $start_month=date('m',strtotime('01-'.$exp_start_date));
        $exp_status=1;
        if(!empty($exp_end_date))
        {
            $exp_status=2;
            $end_year= date('Y',strtotime('01-'.$exp_end_date));
            $end_month=date('m',strtotime('01-'.$exp_end_date));   
        }
        else 
        {
            $end_exp_date=date('0000-00-00');
            $end_year=date('Y',strtotime($end_exp_date));
            $end_month=date('m',strtotime($end_exp_date));
        }   
        $yth_exp_data=array('emu_um_user_id'=>$user_id,'emu_employer_name'=>$exp_title,'emu_status'=>$exp_status,'emu_start_year'=>$start_year,'emu_start_month'=>$start_month,'emu_end_year'=>$end_year,'emu_end_month'=>$end_month,'emu_designation'=>$exp_designation,'emu_description'=>$job_description,'emu_ica_category_id'=>$business_category_id,'emu_isc_sub_category_id'=>$business_subcategory_id,'emu_jt_type_id'=>$exp_job_type);
        $data['exp_status']=$this->profile_model->update_youth_experience_data($exp_id,$yth_exp_data);
        $data['experience']=$this->profile_model->get_user_experience($user_id,0);
        $data['business_category_list']=$this->profile_model->business_category();
        $data['job_type']=$this->profile_model->get_job_type();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-experience-view',$data);
    }
    
    //Function - 18 : add youth experience url
    function add_experience()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $exp_title=$this->input->post('exp_title');
        $exp_designation=$this->input->post('exp_designation');
        $job_description=$this->input->post('job_description');
        $business_category_id=$this->input->post('business_category_id');
        $business_subcategory_id=$this->input->post('business_subcategory_id');
        $exp_job_type=$this->input->post('exp_job_type');
        $exp_start_date=$this->input->post('exp_start_date');
        $exp_end_date=$this->input->post('exp_end_date');
        $start_year=date('Y',strtotime('01-'.$exp_start_date)); 
        $start_month=date('m',strtotime('01-'.$exp_start_date));
        $exp_status=1;
        if(!empty($exp_end_date))
        {
            $exp_status=2;
            $end_year= date('Y',strtotime('01-'.$exp_end_date));
            $end_month=date('m',strtotime('01-'.$exp_end_date));   
        }
        else 
        {
            //$end_exp_date=date('0000-00-00');
            $end_year=0;
            $end_month=0;
        }
        $yth_exp_data=array('emu_employer_name'=>$exp_title,'emu_status'=>$exp_status,'emu_start_year'=>$start_year,'emu_start_month'=>$start_month,'emu_end_year'=>$end_year,'emu_end_month'=>$end_month,'emu_designation'=>$exp_designation,'emu_description'=>$job_description,'emu_ica_category_id'=>$business_category_id,'emu_isc_sub_category_id'=>$business_subcategory_id,'emu_jt_type_id'=>$exp_job_type,'emu_active'=>1);
        $data['exp_status']=$this->profile_model->add_youth_experience_data($user_id,$yth_exp_data);
        $data['experience']=$this->profile_model->get_user_experience($user_id,0);
        $data['business_category_list']=$this->profile_model->business_category();
        $data['job_type']=$this->profile_model->get_job_type();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-experience-view',$data);
    }
    
    //Function - 19 : delete youth experience
    function delete_experience()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $employment_id=$this->input->post('employment_id');
        $data['delete_status']=$this->profile_model->delete_youth_experience($user_id,$employment_id);
        $data['experience']=$this->profile_model->get_user_experience($user_id,0);
        $data['business_category_list']=$this->profile_model->business_category();
        $data['job_type']=$this->profile_model->get_job_type();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-experience-view',$data);
    }
    
    //Function - 20 : get youth education
    function get_update_education()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $education_id=$this->input->post('education_id');
        $data['youth_education']=$this->profile_model->get_user_education($user_id,$education_id);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['organisation_category']=$this->profile_model->get_organisation_category();
        $data['qualification_category']=$this->profile_model->get_qualification_category();
        $this->load->view('profile/profile-a-education',$data);
    }
    
    //Function - 21 : get qualification provider
    function get_qualification_provider()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $qualification_provider=array();
        $org_category_id=$this->input->post('org_category_id');
        if($org_category_id){$qualification_provider=$this->profile_model->get_qualification_provider($org_category_id);}
        echo json_encode($qualification_provider);
    }
    
    //Function - 22 : get qualification title
    function get_qualification()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $qualification_title=array();
        $qap_provider_id=$this->input->post('qap_provider_id');
        if($qap_provider_id>0)
        {
            $qualification_title=$this->profile_model->get_qualification_master($qap_provider_id);
            echo json_encode($qualification_title);
        }
    }
    
    //Function - 23 : get language master
    function get_language()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $data['languages']=$this->profile_model->get_language_master();
        $this->load->view('profile/profile-a-language',$data);
    }
    
    //Function - 24 : add language
    function add_language()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $edu_language=$this->input->post('edu_language');
        $data['langauge_add_status']=$this->profile_model->add_language($user_id,$edu_language);
        $data['language']=$this->profile_model->get_user_language($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-language-view',$data);
    }
    
    //Function - 25 : delete language
    function delete_language()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $language_id=$this->input->post('language_id');
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code')); 
        $data['lang_delete_status']=$this->profile_model->delete_language($language_id,$user_id);
        $data['language']=$this->profile_model->get_user_language($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-language-view',$data);
    }
    
    //Function - 26 : get wish list
    function get_wishlist()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $data['wishlist']=$this->profile_model->get_wishlist();
        $this->load->view('profile/profile-a-job-wishlist',$data);
    }
    
    //Function - 27 : add wishlist
    function add_wishlist()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $edu_jobwishlist= $this->input->post('edu_jobwishlist');
        $wishlist_status=$this->profile_model->wishlist_exists($user_id,$edu_jobwishlist);
        if($wishlist_status>=1)
        {
            $data['wish_list_status']=$wishlist_status;
        }
        else 
        {
            $data['add_wishlist_status']=$this->profile_model->add_wishlist($user_id,$edu_jobwishlist);
            $data['job_wishlist']=$this->profile_model->get_job_wishlist($user_id);
            $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
            $data['EDIT_PROFILE_USER_ID']=$user_id;
            $this->load->view('profile/profile-a-job-wishlist-view',$data);
        }
    }
    
    //Function - 28 : delete job wishlist
    function delete_wishlist()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $job_wishlist_id=$this->input->post('job_wishlist_id');
        $data['delete_wishlist_status']=$this->profile_model->delete_job_wishlist($user_id,$job_wishlist_id);
        $data['job_wishlist']=$this->profile_model->get_job_wishlist($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-a-job-wishlist-view',$data);  
    }
    
    //Function - 29 : update youth education
    function update_education()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $edu_qau_qualification_id=$this->input->post('edu_qau_qualification_id');
        $edu_region_name=$this->input->post('edu_region_name');
        $edu_org_category=$this->input->post('edu_org_category');
        $edu_qualification_provider=$this->input->post('edu_qualification_provider');
        $edu_title_of_qualification=$this->input->post('title_of_qualification');
        $edu_title=$this->input->post('edu_title');
        $edu_description=$this->input->post('edu_description');
        $edu_ncea_level=$this->input->post('edu_ncea_level');
        $qualification_category=$this->input->post('qualification_category');
        $edu_start_date=$this->input->post('edu_start_date');
        $edu_end_date=$this->input->post('edu_end_date');
        $edu_start_date=date('Y-m-d H:i:s',strtotime($edu_start_date));
        $edu_status=0;
        if(!empty($edu_end_date)){$edu_status=1; $edu_end_date=date('Y-m-d H:i:s',strtotime($edu_end_date));}
        $qualification_data=array('qau_re_region_id'=>$edu_region_name,'qau_ogc_category_id'=>$edu_org_category,'qau_status'=>$edu_status,'qau_qap_provider_id'=>$edu_qualification_provider,'qau_title'=>$edu_title,'qau_qam_qualification_id'=>$edu_title_of_qualification,'qau_description'=>$edu_description,'qau_ncea_level'=>$edu_ncea_level,'qau_qac_category_id'=>$qualification_category,'qau_start_date'=>$edu_start_date,'qau_end_date'=>$edu_end_date);
        $data['update_educat_status']=$this->profile_model->update_education_data($user_id,$edu_qau_qualification_id,$qualification_data);
        $data['education']=$this->profile_model->get_user_education($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-education-view',$data);
    }
    
    //Function - 30 : add youth education
    function add_education()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $edu_region_name=$this->input->post('edu_region_name');
        $edu_org_category=$this->input->post('edu_org_category');
        $edu_qualification_provider=$this->input->post('edu_qualification_provider');
        $edu_title_of_qualification=$this->input->post('title_of_qualification');
        $edu_title=$this->input->post('edu_title');
        $edu_description=$this->input->post('edu_description');
        $edu_ncea_level=$this->input->post('edu_ncea_level');
        $qualification_category=$this->input->post('qualification_category');
        $edu_start_date=$this->input->post('edu_start_date');
        $edu_end_date=$this->input->post('edu_end_date'); 
        $edu_start_date=date('Y-m-d H:i:s',strtotime($edu_start_date));
        $edu_status=0;
        if(!empty($edu_end_date)){$edu_status=1; $edu_end_date=date('Y-m-d H:i:s',strtotime($edu_end_date)); }
        $qualification_data=array('qau_um_user_id'=>$user_id,'qau_re_region_id'=>$edu_region_name,'qau_ogc_category_id'=>$edu_org_category,'qau_status'=>$edu_status,'qau_qap_provider_id'=>$edu_qualification_provider,'qau_title'=>$edu_title,'qau_qam_qualification_id'=>$edu_title_of_qualification,'qau_description'=>$edu_description,'qau_ncea_level'=>$edu_ncea_level,'qau_qac_category_id'=>$qualification_category,'qau_start_date'=>$edu_start_date,'qau_end_date'=>$edu_end_date,'qau_created_by'=>$user_id,'qau_active'=>1);
        $data['add_education_status']=$this->profile_model->add_education_data($user_id,$qualification_data);
        $data['education']=$this->profile_model->get_user_education($user_id,0);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['organisation_category']=$this->profile_model->get_organisation_category();
        $data['qualification_category']=$this->profile_model->get_qualification_category();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-education-view',$data);     
    }
    
    //Function - : education
    function education()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $data['region_list']=$this->profile_model->get_region_list();
        $data['organisation_category']=$this->profile_model->get_organisation_category();
        $data['qualification_category']=$this->profile_model->get_qualification_category();
        $this->load->view('profile/profile-a-education',$data);
    }
    
    //Function - 31 : delete qualification
    function delete_education(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $qualification_id=$this->input->post('qualification_id');
        $data['delete_edu_status']=$this->profile_model->delete_qualification($user_id,$qualification_id);
        $data['education']=$this->profile_model->get_user_education($user_id,0);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['organisation_category']=$this->profile_model->get_organisation_category();
        $data['qualification_category']=$this->profile_model->get_qualification_category();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-education-view',$data); 
    }
    
    //Function - 32 : get youth volunteering
    function get_update_volunteering()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $vou_qualification_id=$this->input->post('vou_qualification_id');
        $data['youth_volunteering']=$this->profile_model->get_user_volunteering($user_id,$vou_qualification_id);
        $data['volunteering_category']=$this->profile_model->get_volunteering_category();
        $this->load->view('profile/profile-a-volunteering',$data);
    }
    
    //Function - 33 : update youth volunteering
    function update_volunteering()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $volunteering_id=$this->input->post('volunteering_id');
        $volun_title=$this->input->post('volun_title');
        $volun_provider_name=$this->input->post('volun_provider_name');
        $volun_description=$this->input->post('volun_description');
        $volun_category_id=$this->input->post('volun_category_id');
        $vol_start_date=$this->input->post('vol_start_date');
        $vol_start_date=date('Y-m-d H:i:s',strtotime($vol_start_date));
        $vol_end_date=$this->input->post('vol_end_date');
        
        $volun_status=0;
        if(!empty($vol_end_date)){$volun_status=1; $vol_end_date=date('Y-m-d H:i:s',strtotime($vol_end_date)); }
        $volunteer_data=array('vou_title'=>$volun_title,'vou_provider_name'=>$volun_provider_name,'vou_status'=>$volun_status,'vou_description'=>$volun_description,'vou_voc_category_id'=>$volun_category_id,'vou_start_date'=>$vol_start_date,'vou_end_date'=>$vol_end_date);
        $data['volun_status']=$this->profile_model->update_volunteering_data($user_id,$volunteering_id,$volunteer_data);
        $data['volunteering']=$this->profile_model->get_user_volunteering($user_id,0);
        $data['volunteering_category']=$this->profile_model->get_volunteering_category();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-volunteering-view',$data);
    }
    
    //Function - 34 : add youth volunteering
    function add_volunteering()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $volun_title=$this->input->post('volun_title');
        $volun_provider_name=$this->input->post('volun_provider_name');
        $volun_description=$this->input->post('volun_description');
        $volun_category_id=$this->input->post('volun_category_id');
        $vol_start_date=$this->input->post('vol_start_date');
        $vol_start_date=date('Y-m-d H:i:s',strtotime($vol_start_date));
        $vol_end_date=$this->input->post('vol_end_date');
        $volun_status=0;
        if(!empty($vol_end_date)){$volun_status=1;  $vol_end_date=date('Y-m-d H:i:s',strtotime($vol_end_date)); }
        $volunteer_data=array('vou_um_user_id'=>$user_id,'vou_title'=>$volun_title,'vou_provider_name'=>$volun_provider_name,'vou_status'=>$volun_status,'vou_description'=>$volun_description,'vou_voc_category_id'=>$volun_category_id,'vou_start_date'=>$vol_start_date,'vou_end_date'=>$vol_end_date,'vou_created_by'=>$user_id);
        $data['volun_add_status']=$this->profile_model->add_volunteering_data($user_id,$volunteer_data);
        $data['volunteering']=$this->profile_model->get_user_volunteering($user_id,0);
        $data['volunteering_category']=$this->profile_model->get_volunteering_category();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-volunteering-view',$data);
    }
    
    //Function - 35 : delete volunteering
    function delete_volunteering()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $volunteer_id=$this->input->post('volunteer_id');
        $data['delete_volunteer_status']=$this->profile_model->delete_volunteering($user_id,$volunteer_id);
        $data['volunteering']=$this->profile_model->get_user_volunteering($user_id,0);
        $data['volunteering_category']=$this->profile_model->get_volunteering_category();
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-volunteering-view',$data);
    }
    
    //Function - 36 : user cv limit check
    function usercv_limit()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $cv_limit=$this->profile_model->get_usercv_limit($user_id);
        if($cv_limit<=10)
        {
            echo json_encode(array('cv_exist'=>TRUE));
        }
        else{
            echo json_encode(array('cv_exist'=>FALSE));
        }
    }
    
    //Function - 36 : add usercv
    function add_usercv()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $cv_limit=$this->profile_model->get_usercv_limit($user_id);
        if($cv_limit <= 10)
        {  
            //Upload file
            $config['upload_path']=YH_UPLOAD_PATH.'profile/cv/';
            $config['allowed_types']='pdf|doc|docx';
            $this->load->library('upload',$config);
            if($this->upload->do_upload("cv_file"))
            {
                $data=array('upload_data'=>$this->upload->data());
                $usercv_data=array('ucv_file_name'=>$data['upload_data']['file_name'],'ucv_um_user_id'=>$user_id,'ucv_type'=>$this->input->post('cv_type'),'ucv_title'=>$this->input->post('cv_title'),'ucv_created_by'=>$user_id,'ucv_active'=>1);   
                $data['add_usercv_status']=$this->profile_model->add_user_cv($usercv_data);
            }
        }
        else
        {
            $data['cv_limit_exceed']='upload limit exceed.';

        }
        $data['user_cv']=$this->profile_model->get_user_cv($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-usercv-view',$data);
    }
    
    //Function - 37 : delete usercv
    function delete_usercv()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $cv_id=$this->input->post('cv_id');
        $data['delete_usercv_status']=$this->profile_model->delete_user_cv($user_id,$cv_id);
        $data['user_cv']=$this->profile_model->get_user_cv($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-usercv-view',$data);
    }
    
    //Function - 38 : update profile info
    function update_profile_info()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $this->form_validation->set_rules('user_code','Usercode','trim|required');
        $this->form_validation->set_rules('fname','Firstname','trim|required');
        $this->form_validation->set_rules('lname','Lastname','trim|required');   
        $this->form_validation->set_rules('dob','DOB','trim|required');
        $this->form_validation->set_rules('gender','Gender','trim|required');
        $this->form_validation->set_rules('region_name','Region','trim|required');
        $this->form_validation->set_rules('city_name','City','trim|required');
        $this->form_validation->set_rules('conatact_email','Contact email','trim|required');
        $this->form_validation->set_rules('intended_destination','Intended destination','trim|required');
        $this->form_validation->set_rules('licence_transport','Licence transport','trim|required');
        if($this->form_validation->run() === FALSE)
	{
            $errors=validation_errors();
            echo json_encode(array('status'=>FALSE,'errors'=>$errors));
        }
        else
        {   $work_experience=0;
            $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
            $fname=$this->input->post('fname');
            $lname=$this->input->post('lname');
            $dob=$this->input->post('dob');
            $gender=$this->input->post('gender');
            $region_name=$this->input->post('region_name');
            $city_name=$this->input->post('city_name');
            $current_education=$this->input->post('current_education');
            $ethnicity=$this->input->post('ethnicity');
            $iwi=$this->input->post('iwi');
            $conatact_email=$this->input->post('conatact_email');
            $mobile=$this->input->post('mobile');
            $work_status=$this->input->post('work_status');
            $work_availability=$this->input->post('work_availability');
            $work_experience=$this->input->post('work_experience');
            (int)$work_experience*=12;
            $work_per_week=$this->input->post('work_per_week');
            $preferred_region=$this->input->post('preferred_region');
            $preferred_city=$this->input->post('preferred_city');
            $intended_destination=$this->input->post('intended_destination');
            $licence_transport=$this->input->post('licence_transport');
            $residency_status=$this->input->post('residency_status');
            $visa_type='';
            $visa_expire_month=0;
            $visa_expire_year=0;
            if($residency_status==2)
            {
                $visa_type=$this->input->post('visa_type');
                $visa_expire_month=$this->input->post('visa_expire_month');
                $visa_expire_year=$this->input->post('visa_expire_year');
            }
            $instagram=$this->input->post('instagram');
            $facebook=$this->input->post('facebook');
            $twitter=$this->input->post('twitter');
            $google_plus=$this->input->post('google_plus');
            $linkedin=$this->input->post('linkedin');
            $github=$this->input->post('github');
            $behance=$this->input->post('behance');
            $description=$this->input->post('description');
            $dob=date('Y-m-d',strtotime($dob));
            $info_data=array('yth_ogm_organisation_id'=>$current_education,'yth_first_name'=>$fname,'yth_last_name'=>$lname,'yth_gender'=>$gender,'yth_dob'=>$dob,'yth_full_description'=>$description,'yth_ethnicity'=>$ethnicity,'yth_iwi'=>$iwi,'yth_region'=>$region_name,'yth_city'=>$city_name,'yth_mobile_no'=>$mobile,'yth_contact_email'=>$conatact_email,'yth_work_status'=>$work_status,'yth_work_availability_timing'=>$work_availability,'yth_work_availability_hour'=>$work_per_week,'yth_work_experience'=>$work_experience,'yth_work_preferred_region'=>$preferred_region,'yth_work_preferred_district'=>$preferred_city,'yth_intended_destination'=>$intended_destination,'yth_lt_type_id'=>$licence_transport,'yth_residency_type'=>$residency_status,'yth_visa_type'=>$visa_type,'yth_visa_expire_month'=>$visa_expire_month,'yth_visa_expire_year'=>$visa_expire_year);
            $socialmedia_data=array('slu_facebook'=>$facebook,'slu_twitter'=>$twitter,'slu_instagram'=>$instagram,'slu_google_plus'=>$google_plus,'slu_behance'=>$behance,'slu_linkedin'=>$linkedin,'slu_github'=>$github);
            $um_arr=array('um_name'=>$fname.' '.$lname,'um_email'=>$conatact_email);
            $info_update_status=$this->profile_model->update_youth_info($user_id,$info_data);
            $info_update_slu=$this->profile_model->update_youth_social_links($user_id,$socialmedia_data);
            $info_upt_um=$this->profile_model->update_um($user_id,$um_arr);
            echo json_encode(array('status'=>TRUE,'info_update_status'=>$info_update_status,'info_update_slu'=>$info_update_slu,'um_upt_status'=>$info_upt_um));
            if($info_update_status == TRUE){$sess_name_upt=array('SESS_NAME'=>$fname.' '.$lname);  $this->session->set_userdata($sess_name_upt);}else{}
        }
    }
    
    //Function - 39 : get youth info
    function get_youth_info()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $data['profile_info']=$this->profile_model->get_youth_profile_info($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-a-info-view',$data); 
    }
    
    //Function - 40 : add qualification title
    function add_qualification_title()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $qualification_provider_id=$this->input->post('qualification_provider_id');
        $edu_title=$this->input->post('edu_title');
        $this->profile_model->add_qualification_title($qualification_provider_id,$edu_title);
        $qualification_title=$this->profile_model->get_qualification_master($qualification_provider_id);
        echo json_encode($qualification_title);
    }
    
    //Function - 41 : is wishlist exists check
    function is_wishlist_exists()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $wishlist_id=$this->input->post('edu_jobwishlist');
        $wishlist_status=$this->profile_model->wishlist_exists($user_id,$wishlist_id);
        if($wishlist_status>=1)
        {
            echo json_encode(array('wishlist_exist'=>TRUE));
        }
        else{
            echo json_encode(array('wishlist_exist'=>FALSE));
        }
    }
    
    //Function - 42 : get update teacher info
    function get_update_teacher_info()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $data['teacher_info']=$this->profile_model->get_teacher_info($user_id);
        $data['school_name']=$this->profile_model->get_school_name();
        $data['region_list']=$this->profile_model->get_region_list();
        $data['city_list']=$this->profile_model->get_city_list(0);
        $data['suburb_list']=$this->profile_model->get_suburb_list(0,0); 
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-b-teacher-info',$data);
    }
    
    //Function - 43 : update teacher info
    function update_teacher_info()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $school_name=$this->input->post('school_name');
        $fname=$this->input->post('fname');
        $lname=$this->input->post('lname');
        $about_me=$this->input->post('about_me');
        $region_name=$this->input->post('region_name');
        $city_name=$this->input->post('city_name');
        $suburb_name=$this->input->post('suburb_name');
        $contact_no=$this->input->post('contact_no');
        $contact_email=$this->input->post('contact_email');
        $alt_contact_no=$this->input->post('alt_contact_no');
        $alt_contact_email=$this->input->post('alt_contact_email');
        $tch_data=array('tch_ogm_organisation_id'=>$school_name,'tch_first_name'=>$fname,'tch_last_name'=>$lname,'tch_full_description'=>$about_me,'tch_region'=>$region_name,'tch_city'=>$city_name,'tch_suburb'=>$suburb_name,'tch_contact_no'=>$contact_no,'tch_contact_email'=>$contact_email,'tch_alt_contact_no'=>$alt_contact_no,'tch_alt_contact_email'=>$alt_contact_email);
        $um_data=array('um_email'=>$contact_email);
        $tch_update_status=$this->profile_model->update_teacher_data($user_id,$tch_data,$um_data);
        if($tch_update_status==TRUE){$sess_name_upt=array('SESS_NAME'=>$fname.' '.$lname);  $this->session->set_userdata($sess_name_upt);}else{}
        $data['teacher_info']=$this->profile_model->get_teacher_info($user_id);
        $data['school_name']=$this->profile_model->get_school_name();
        $data['region_list']=$this->profile_model->get_region_list();
        $data['city_list']=$this->profile_model->get_city_list(0);
        $data['suburb_list']=$this->profile_model->get_suburb_list(0,0); 
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-b-teacher-info-view',$data);
    }
    
    // Function - 44 : get update achievement
    function get_update_achievement()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $achievement_id=$this->input->post('achievement_id');
        $data['achievement_data']=$this->profile_model->get_achievement_user($user_id,$achievement_id);
        $this->load->view('profile/profile-a-achievements',$data);
    }
    
    //Function - 45 : update achievement
    function update_achievement()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $acu_achievement_id=$this->input->post('acu_achievement_id');
        $acu_title=$this->input->post('acu_title');
        $acu_occupation=$this->input->post('acu_occupation');
        $acu_provider_name=$this->input->post('acu_provider_name');
        $acu_description=$this->input->post('acu_description');
        $ach_date=$this->input->post('ach_date');
        $ach_year=0;
        $ach_month=0;
        if(!empty($ach_date))
        {
            $exp_status=2;
            $ach_year= date('Y',strtotime($ach_date));
            $ach_month=date('m',strtotime($ach_date));  
        }
        $ach_data=array('acu_title'=>$acu_title,'acu_occupation'=>$acu_occupation,'acu_description'=>$acu_description,'acu_provider_name'=>$acu_provider_name,'acu_issued_year'=>$ach_year,'acu_issued_month'=>$ach_month);
        $data['ach_update_status']=$this->profile_model->update_achievement_data($user_id,$acu_achievement_id,$ach_data);
        $data['achievement']=$this->profile_model->get_achievement_user($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-achievements-view',$data);
    }
    
    //Function - 46 : add achievement
    function add_achievement()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $acu_title=$this->input->post('acu_title');
        $acu_occupation=$this->input->post('acu_occupation');
        $acu_provider_name=$this->input->post('acu_provider_name');
        $acu_description=$this->input->post('acu_description');
        $ach_date=$this->input->post('ach_date');
        $ach_year=0;
        $ach_month=0;
        if(!empty($ach_date))
        {
            $exp_status=2;
            $ach_year= date('Y',strtotime($ach_date));
            $ach_month=date('m',strtotime($ach_date));  
        }
        $ach_data=array('acu_title'=>$acu_title,'acu_occupation'=>$acu_occupation,'acu_description'=>$acu_description,'acu_provider_name'=>$acu_provider_name,'acu_issued_year'=>$ach_year,'acu_issued_month'=>$ach_month);
        $data['ach_add_status']=$this->profile_model->add_achievement_data($user_id,$ach_data);
        $data['achievement']=$this->profile_model->get_achievement_user($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-achievements-view',$data);
    }
    
    //Function - 47 : delete achievement
    function delete_achievement()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $acu_achievement_id=$this->input->post('achievement_id');
        $data['ach_delete_status']=$this->profile_model->delete_achievement_data($user_id,$acu_achievement_id);
        $data['achievement']=$this->profile_model->get_achievement_user($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-achievements-view',$data);
    }
    
    //Function - 48 : add technical skills
    function add_technical_skill()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $skill_name=ucwords($this->input->post('skill_name'));
        $skill_level=ucwords($this->input->post('skill_level'));
        $tech_skill_data=array('sku_name'=>$skill_name,'sku_level'=>$skill_level);
        $data['tech_skill_add_status']=$this->profile_model->add_technical_skills_data($user_id,$tech_skill_data);
        $data['technical_skills']=$this->profile_model->get_technical_skills($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-technical-skills-view',$data);
    }
    
    //Function - 49 : get update technical skills
    function get_update_technical_skill()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $technical_skill_id=$this->input->post('technical_skill_id');
        $data['technical_skill']=$this->profile_model->get_technical_skills($user_id,$technical_skill_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-a-technical-skills',$data);
    }
    
    //Function - 50 : update technical skill
    function update_technical_skill()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $technical_skill_id=ucwords($this->input->post('technical_skill_id'));
        $skill_name=ucwords($this->input->post('skill_name'));
        $skill_level=ucwords($this->input->post('skill_level'));
        $tech_skill_data=array('sku_name'=>$skill_name,'sku_level'=>$skill_level);
        $data['tech_skill_update_status']=$this->profile_model->update_technical_skills_data($user_id,$technical_skill_id,$tech_skill_data);
        $data['technical_skills']=$this->profile_model->get_technical_skills($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-technical-skills-view',$data);
    }
    
    //Function - 51 : delete technical skill
    function delete_technical_skill()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $technical_skill_id=$this->input->post('technical_skill_id');
        $data['tch_skill_delete_status']=$this->profile_model->delete_technical_skill_data($user_id,$technical_skill_id);
        $data['technical_skills']=$this->profile_model->get_technical_skills($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-technical-skills-view',$data);
    }
    
    //Function - 52 : add interest
    function add_interest()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $interest=ucwords($this->input->post('interest'));
        $data['add_interest_status']=$this->profile_model->add_interest_data($user_id,$interest);
        $data['interests']=$this->profile_model->get_interests($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-interest-view',$data);
    }
    
    //Function - 53 : get update interest
    function get_update_interest()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $interest_id=$this->input->post('interest_id');
        $data['interest']=$this->profile_model->get_interests($user_id,$interest_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-a-interest',$data); 
    }
    
    //Function - 54 : update interest
    function update_interest()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $interest_id=$this->input->post('interest_id');
        $interest=ucwords($this->input->post('interest'));
        $data['update_interest_status']=$this->profile_model->update_interest_data($user_id,$interest_id,$interest);
        $data['interests']=$this->profile_model->get_interests($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-interest-view',$data);
    }
    
    //Function - 55 : delete interest
    function delete_interest()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $interest_id=$this->input->post('interest_id');
        $data['delete_interest_status']=$this->profile_model->delete_interest_data($user_id,$interest_id);
        $data['interests']=$this->profile_model->get_interests($user_id,0);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-interest-view',$data);
    }
    
    //Function - 56 : get update admin info
    function get_update_admin_info()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $data['upt_admin_info']=$this->profile_model->get_admin_info($user_id);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['city_list']=$this->profile_model->get_city_list(0);
        $data['suburb_list']=$this->profile_model->get_suburb_list(0,0); 
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-b-admin-info',$data);
    }
    
    //Function - 57 : update admin info
    function update_admin_info()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $fname=$this->input->post('fname');
        $lname=$this->input->post('lname');
        $about_me=$this->input->post('about_me');
        $region_name=$this->input->post('region_name');
        $city_name=$this->input->post('city_name');
        $suburb_name=$this->input->post('suburb_name');
        $contact_no=$this->input->post('contact_no');
        $contact_email=$this->input->post('contact_email');
        $alt_contact_no=$this->input->post('alt_contact_no');
        $alt_contact_email=$this->input->post('alt_contact_email');
        $admin_data=array('adm_first_name'=>$fname,'adm_last_name'=>$lname,'adm_full_description'=>$about_me,'adm_region'=>$region_name,'adm_city'=>$city_name,'adm_suburb'=>$suburb_name,'adm_contact_no'=>$contact_no,'adm_contact_email'=>$contact_email,'adm_alt_contact_no'=>$alt_contact_no,'adm_alt_contact_email'=>$alt_contact_email);
        $um_data=array('um_name'=>$fname.' '.$lname);
        $admin_update_status=$this->profile_model->update_admin_data($user_id,$admin_data,$um_data);
        if($admin_update_status==TRUE){$sess_name_upt=array('SESS_NAME'=>$fname.' '.$lname);  $this->session->set_userdata($sess_name_upt);}else{}
        $data['admin_info']=$this->profile_model->get_admin_info($user_id);
        $data['region_list']=$this->profile_model->get_region_list();
        $data['city_list']=$this->profile_model->get_city_list(0);
        $data['suburb_list']=$this->profile_model->get_suburb_list(0,0); 
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('profile/profile-b-admin-info-view',$data); 
    }
    
    //Function - 58 : send testimonial
    function send_testimonial(){
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $testimonial_provider=$this->input->post('testimonial_provider');
        $email=$this->input->post('email');
        $organisation_name=$this->input->post('organisation_name');
        $providers_title=$this->input->post('providers_title');
        $comments=$this->input->post('comments');
        $SESS_NAME=$this->session->userdata('SESS_NAME');
        $testimonial_data=array('teu_provider_name'=>$testimonial_provider,'teu_provider_email'=>$email,'teu_provider_company'=>$organisation_name,'teu_provider_title'=>$providers_title,'teu_message'=>$comments,'teu_status'=>0);
        $testimonial_code=$this->profile_model->send_testimonial_data($user_id,$testimonial_data);
        if(!empty($testimonial_code))
        {
            $data=array('testimonial_provider'=>$testimonial_provider,'requester_name'=>$SESS_NAME,'request_msg'=>$comments,'testimonial_code'=>$testimonial_code);
            $message=$this->load->view('email/testimonial.tpl.php',$data,TRUE);
	    $this->email->clear();
	    $this->email->from($this->config->item('admin_email','ion_auth'),$this->config->item('site_title','ion_auth'));
	    $this->email->to($email);
	    $this->email->subject('Testimonial Request from '.$SESS_NAME);
	    $this->email->set_mailtype("html");
	    $this->email->message($message);
	    if($this->email->send() === TRUE)
	    {
		 echo json_encode(array('send_testimonial'=>TRUE,'email'=>$email));
	    }
	    else
	    {
		 echo json_encode(array('send_testimonial'=>FALSE,'email'=>$email));
	    }	
        }  
    }
    
    //Function - 59 : testimonial
    function testimonial($teu_code)
    {
        $teu_status=$this->profile_model->check_testimonial($teu_code);
        if($teu_status != 1)
        {
            $data['testimonial_data']=$this->profile_model->get_testimonial_data($teu_code);
            $data['skills']=$this->profile_model->get_skills();
            $this->load->view('profile/testimonial',$data);
        }
        else
        {
            $data['teu_status']=$teu_status;
            $this->load->view('profile/testimonial',$data);
        }
    }
    
    //Function - 60 : testimonial by
    function testimonial_by($teu_code)
    {  
        $teu_status=$this->profile_model->check_testimonial($teu_code);
        if($teu_status != 1){
            $contact_no=$this->input->post('contact_no');
            $skills=$this->input->post('skills'); 
            $comma_separated_skills='0,';
            $comma_separated_skills .= implode(",",$skills).',0'; 
            $testimonial=$this->input->post('testimonial');
            $teu_array=array('teu_provider_phone'=>$contact_no,'teu_provider_message'=>$testimonial,'teu_skills'=>$comma_separated_skills,'teu_status'=>1);
            $data['testimonial_status']=$this->profile_model->testimonialed_by($teu_code,$teu_array);
            $this->load->view('profile/testimonial',$data);
        }
        else
        {
            $data['teu_status']=$teu_status;
            $this->load->view('profile/testimonial',$data);
        }
    }
    
    //Function - 61 : video view
    function video_view()
    {
        $this->load->view('profile/video-intro-record');
    }
    
    //Function - 62 : is language exist
    function is_language_exist()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $edu_language=$this->input->post('edu_language');
        $language_status=$this->profile_model->language_exist($user_id,$edu_language);
        if($language_status>=1)
        {
            echo json_encode(array('language_exist'=>TRUE));
        }
        else
        {
            echo json_encode(array('language_exist'=>FALSE));
        }
    }
    
    //Function - 63 :  accelerator cv
    function accelerator_cv()
    {
        if(!$this->ion_auth->logged_in()){redirect('login','refresh');}
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $user_id=$SESS_USER_ID;
        $data=array();
        $data['user_code']=$this->profile_model->get_usercode($SESS_USER_ID);
        $data['profile_info']=$this->profile_model->get_youth_profile_info($user_id);
        $data['job_wishlist']=$this->profile_model->get_job_wishlist($user_id);
        $data['about_user']=$this->profile_model->get_about_user($user_id);        
        $data['testimonials']=$this->profile_model->get_user_testimonial($user_id);
        $data['experience']=$this->profile_model->get_user_experience($user_id,0);
        $data['education']=$this->profile_model->get_user_education($user_id,0);
        $data['volunteering']=$this->profile_model->get_user_volunteering($user_id,0);
        $data['skills']=$this->profile_model->get_skill_user($user_id);
        $data['achievement']=$this->profile_model->get_achievement_user($user_id,0);
        $data['user_cv']=$this->profile_model->get_user_cv($user_id);
        $data['technical_skills']=$this->profile_model->get_technical_skills($user_id,0);
        $data['interests']=$this->profile_model->get_interests($user_id,0);
        $data['language']=$this->profile_model->get_user_language($user_id);
        $data['business_category_list']=$this->profile_model->business_category();
        $data['job_type']=$this->profile_model->get_job_type();
        $data['region_list']=$this->profile_model->get_region_list();
        $data['organisation_category']=$this->profile_model->get_organisation_category();
        $data['qualification_category']=$this->profile_model->get_qualification_category();
        $data['volunteering_category']=$this->profile_model->get_volunteering_category();
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('accelerator/profile/cv-info',$data);
    }
    
    //Function - 64 : update about me
    function update_about_me()
    {
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $about_me=$this->input->post('about_me');
        $data['update_about_me_status']=$this->profile_model->update_about_me($user_id,$about_me);
        $data['about_user']=$this->profile_model->get_about_user($user_id);
        $data['SESS_USER_ID']=$SESS_USER_ID;
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $this->load->view('accelerator/profile/about',$data);
    }
    //Function - 65 : delete testimonial
    function delete_testimonial(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $testimonial_id = $this->input->post('testimonial_id');
        $data['teu_delete_status']=$this->profile_model->delete_testimonial_data($user_id,$testimonial_id);
        $data['testimonials']=$this->profile_model->get_user_testimonial($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-testimonial-view',$data);
    }
    //Function - 66 : update skills
    function update_skills(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $data['skills']=$this->profile_model->get_skill_user($user_id);
        $data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');
        $data['EDIT_PROFILE_USER_ID']=$user_id;
        $SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
        $data['user_settings']=$this->profile_model->get_user_settings($user_id,$SESS_TYPE_ID);
        $this->load->view('profile/profile-a-skills-view',$data);
    }
    
    //Function - 67 : profile cover photo upload
    function profile_cover_photo_upload(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        if($_POST)
	{
            $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
            $ftype=$this->input->post('ftype');
	    $fcode=$this->input->post('fcode');
	    $fname=$this->input->post('fname');
	    $fsize=$this->input->post('fsize');
            $photo_type= $this->input->post('photo_type');
            if($ftype>3)
	    {
		$fhandle=$this->input->post('fhandle');
		$furl=$this->input->post('furl');
		$fs_filename=$fhandle.'.png';
		//insert data
                if($photo_type==1){
                    $fs_to=YH_UPLOAD_PATH.'profile/'.$fs_filename; 
                    $fs_source=file_get_contents($furl);
                    file_put_contents($fs_to,$fs_source);
                    //load plugin
                    $this->load->helper('path');
                    $this->load->library('image_lib');
                    //generate medium
                    $fs_to_medium=YH_UPLOAD_PATH. 'profile/medium';
                    $config_medium=array('source_image'=>$fs_to,'new_image'=>$fs_to_medium,'maintain_ratio'=>true,'width'=>250,'height'=>230);
                    $this->image_lib->initialize($config_medium);
                    $this->image_lib->resize();
                    $photo_arr=array('um_profile_picture'=>$fs_filename);
		    $photo_status=$this->profile_model->photo_update($user_id,$photo_arr);
                }
                else{
                    $fs_to=YH_UPLOAD_PATH.'profile/background/'.$fs_filename; 
                    $fs_source=file_get_contents($furl);
                    file_put_contents($fs_to,$fs_source);
                    //load plugin
                    $this->load->helper('path');
                    $this->load->library('image_lib');
		    $photo_arr=array('um_cover_picture'=>$fs_filename);
		    $photo_status=$this->profile_model->cover_photo_update($user_id,$photo_arr);
                }
                echo $photo_status;
	    }
        }
    }
    
    //Function - 68 : cv download
    function cv_download($cv){
        if (empty($cv)) {
            return FALSE;
        }
        $SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
        $get_file_name=$this->profile_model->get_cv_filename($cv,$SESS_USER_ID);
        $pathfilename=YH_MEDIA_PATH.'profile/cv/'. $get_file_name;
        if(FALSE != ($data = file_get_contents($pathfilename))) {
            force_download($get_file_name, $data);
            return TRUE;
        }
    }
    
    //Function - 69 : experience privacy
    function experience_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $exp_privacy=$this->input->post('exp_privacy');
        $exp_privacy_status=$this->profile_model->experience_privacy_upt($user_id,$exp_privacy);
        echo $exp_privacy_status;
    }
    //Function - 70 : education privacy
    function education_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $edu_privacy=$this->input->post('edu_privacy');
        $edu_privacy_status=$this->profile_model->education_privacy_upt($user_id,$edu_privacy);
        echo $edu_privacy_status;
    }
    //Function - 71 : volunteering privacy
    function volunteering_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $volun_privacy=$this->input->post('volun_privacy');
        $volun_privacy_status=$this->profile_model->volunteering_privacy_upt($user_id,$volun_privacy);
        echo $volun_privacy_status;
    }
    //Function - 72 : achievements privacy
    function achievements_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $achiev_privacy=$this->input->post('achiev_privacy');
        $achiev_privacy_status=$this->profile_model->achievements_privacy_upt($user_id,$achiev_privacy);
        echo $achiev_privacy_status;
    }
    //Function - 73 : cv privacy
    function cv_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $cv_privacy=$this->input->post('cv_privacy');
        $cv_privacy_status=$this->profile_model->cv_privacy_upt($user_id,$cv_privacy);
        echo $cv_privacy_status;
    }
    //Function - 74 : technical privacy
    function technical_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $ts_privacy=$this->input->post('ts_privacy');
        $ts_privacy_status=$this->profile_model->technical_privacy_upt($user_id,$ts_privacy);
        echo $ts_privacy_status;
    }
    //Function - 75 : interest privacy
    function interest_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $interest_privacy=$this->input->post('interest_privacy');
        $interest_privacy_status=$this->profile_model->interest_privacy_upt($user_id,$interest_privacy);
        echo $interest_privacy_status;
    }
    //Function - 76 : language privacy
    function language_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $language_privacy=$this->input->post('language_privacy');
        $language_privacy_status=$this->profile_model->language_privacy_upt($user_id,$language_privacy);
        echo $language_privacy_status;
    }
    //Function - 76 : language privacy
    function testimonial_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $teu_privacy=$this->input->post('teu_privacy');
        $teu_privacy_status=$this->profile_model->testimonial_privacy_upt($user_id,$teu_privacy);
        echo $teu_privacy_status;
    }
    //Function - 77 : skills privacy
    function skills_privacy(){
        if(!$this->input->is_ajax_request()){redirect('404_override');}
        if(!$this->ion_auth->logged_in()){return FALSE;}
        $user_id=$this->profile_model->get_user_id($this->input->post('user_code'));
        $skill_privacy=$this->input->post('skill_privacy');
        $skill_privacy_status=$this->profile_model->skills_privacy_upt($user_id,$skill_privacy);
        echo $skill_privacy_status;
    }
}
?>
