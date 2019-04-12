<?php
if(!defined('BASEPATH')){exit('No direct script access allowed');}
class Event extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('event_model');
	}
	
	//Function-1 : Get event list
	function event_list()
	{
		if(!$this->ion_auth->logged_in())
		{
			redirect('login');
			/*$data['page_no']='1';
			$filter=array('page_no'=>'1','search_name'=>'','event_part_id'=>'1','SESS_REGION_ID'=>'','region_id'=>'0','city_id'=>'0','SESS_USER_ID'=>'0','SESS_TYPE_ID'=>0,'my_event_userid'=>0);
			$data['event_list']=$this->event_model->get_event_list($filter);
			$this->load->view('event/event-list',$data);*/
		}
		else
		{
			$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
			$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
			$login_group=array('','1','2','3','4','5','6','7','8','9','10','11','12');
			if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
			
			$SESS_REGION_ID=$this->event_model->get_user_region($SESS_TYPE_ID,$SESS_USER_ID);
			$data['SESS_USER_ID']=$SESS_USER_ID; 			
			$data['SESS_TYPE_ID']=$SESS_TYPE_ID;
			$data['SESS_REGION_ID']=$SESS_REGION_ID;
			$data['page_no']='1'; $data['profile_user_id']=0;  $data['user']='';
			$my_event_userid=$SESS_USER_ID;
			
			if($SESS_TYPE_ID==12){$event_part_id=3;}else{$event_part_id=1;}
			
			//Getting all list of event from database
			$filter=array('page_no'=>'1','search_name'=>'','event_part_id'=>$event_part_id,'SESS_REGION_ID'=>$SESS_REGION_ID,'region_id'=>'0','city_id'=>'0','SESS_USER_ID'=>'0','SESS_TYPE_ID'=>$SESS_TYPE_ID,'my_event_userid'=>$my_event_userid);
			$data['event_list']=$this->event_model->get_event_list($filter);
			
			$data['filter']=$filter;
			$SESS_LAYOUT=$this->session->userdata('SESS_LAYOUT');
			$this->load->view('event/event-list-'.$SESS_LAYOUT,$data);
		}
		
	}
	
	//Function -2: Get city list using region id for New event and Filter list page
	function event_city_list()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		if($_POST)
		{
			if(!empty($this->input->post('region_id'))){$region_id=$this->input->post('region_id');}else{$region_id=array();}
			$event_part_id=$this->input->post('tab_section');
			
			if($event_part_id==2){$year=2018;}else if($event_part_id==1){$year=2017;}else if($event_part_id==3){$year=2019;}
			
			$data['event_part_id']=$event_part_id;
			if($event_part_id>0){$data['citylist']=$this->event_model->get_city_list($SESS_USER_ID,$event_part_id,$region_id,$SESS_TYPE_ID,$year);}
			else{$data['citylist']=$this->event_model->get_region_city_list($region_id);}
			$this->load->view('event/city-list',$data);
		}
	}
	
	//Function -3: Get region list New event and Filter list page
	function event_region_list()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		if($_POST)
		{
			$event_part_id=$this->input->post('tab_section');
			if($event_part_id==2){$year=2018;}else if($event_part_id==1){$year=2017;}else if($event_part_id==3){$year=2019;}
			$data['region_list']=$this->event_model->get_region_list($SESS_USER_ID,$event_part_id,$SESS_TYPE_ID,$year);
			$this->load->view('event/region-list',$data);
		}
	}
	
	//Function -4: Event Load more event
	function event_list_more()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			$page_no=$this->input->post('page_no');
			if(!$this->ion_auth->logged_in())
			{
				redirect('login');
				/*$data['page_no']=$page_no;
				$filter=array('page_no'=>$page_no,'search_name'=>'','event_part_id'=>'1','SESS_REGION_ID'=>'','region_id'=>'0','city_id'=>'0','SESS_USER_ID'=>'0','SESS_TYPE_ID'=>0,'my_event_userid'=>0);
				$data['event_list']=$this->event_model->get_event_list($filter);
				$this->load->view('event/event-list-more',$data);*/
			}
			else
			{
				$SESS_USER_ID='0'; $data['profile_user_id']=0; $data['user']='';
				$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
				$data['SESS_TYPE_ID']=$SESS_TYPE_ID;
				$data['page_no']=$page_no;
				$data['SESS_USER_ID']=$this->session->userdata('SESS_USER_ID');	
				
				$SESS_REGION_ID=$this->event_model->get_user_region($SESS_TYPE_ID,$this->session->userdata('SESS_USER_ID'));
				//$SESS_REGION_ID=$this->session->userdata('SESS_REGION_ID');
				
				//Getting values from script
				if(!empty($this->input->post('user_code')))
				{
					$SESS_USER_ID=get_user_id($this->input->post('user_code'));
					$data['profile_user_id']=$SESS_USER_ID;
					$data['user']=$this->event_model->get_session_user_detail($SESS_USER_ID);
				}
				$search_name=$this->input->post('search_name');
				$event_part_id=$this->input->post('event_part_id');
				if(!empty($this->input->post('region_id'))){$region_id=$this->input->post('region_id');}else{$region_id=array();}
				if(!empty($this->input->post('city_id'))){$city_id=$this->input->post('city_id');}else{$city_id=array();}
				
				//if($event_part_id==2){$filter_region_id=0;}else{$filter_region_id=$region_id;}
				//Getting all list of event from database
				$filter=array('page_no'=>$page_no,'search_name'=>$search_name,'event_part_id'=>$event_part_id,'SESS_REGION_ID'=>$SESS_REGION_ID,'region_id'=>$region_id,'city_id'=>$city_id,'SESS_USER_ID'=>$SESS_USER_ID,'SESS_TYPE_ID'=>$SESS_TYPE_ID,'my_event_userid'=>$this->session->userdata('SESS_USER_ID'));
				$data['event_list']=$this->event_model->get_event_list($filter);
				
				$data['filter']=$filter;
				$SESS_LAYOUT=$this->session->userdata('SESS_LAYOUT');				
				//youth or business list more page
				$this->load->view('event/event-list-'.$SESS_LAYOUT.'-more',$data);
			}
		}
	}
	
	//Function -5 : Create New Event
	function event_add()
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','1','2','3','4','5','','7','8','9','10','11','12');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		//Session Values
		$data['SESS_NAME']=$this->session->userdata('SESS_NAME');
		$data['SESS_TYPE_ID']=$SESS_TYPE_ID;
		$error_message=''; $error=false;
		if($_POST)
		{
			//Getting values from add page
			$em_title=$this->input->post('em_title');
			$em_detail=$this->input->post('em_detail');
			$em_logo=$_FILES['em_logo']['name'];
			if(!empty($this->input->post('em_type'))){$em_type=$this->input->post('em_type');}else{$em_type='';}
			$em_start_date=$this->input->post('em_start_date');
			$em_end_date=$this->input->post('em_end_date');
			$em_start_time=date("H:i:s", strtotime($this->input->post('em_start_time')));
			$em_end_time=date("H:i:s", strtotime($this->input->post('em_end_time')));
			if(!empty($this->input->post('em_re_region_id'))){$em_re_region_id=$this->input->post('em_re_region_id');}else{$em_re_region_id='';}
			if(!empty($this->input->post('em_ci_city_id'))){$em_ci_city_id=$this->input->post('em_ci_city_id');}else{$em_ci_city_id='';}
			$em_address=$this->input->post('em_address');
			$em_latitude=$this->input->post('em_latitude');
			$em_longitude=$this->input->post('em_longitude');
			$em_contact_no=$this->input->post('em_contact_no');
			$em_contact_email=$this->input->post('em_contact_email');
			$em_contact_name=$this->input->post('em_contact_name');
			$event_startdate=date('Y-m-d',strtotime($em_start_date));
			$event_enddate=date('Y-m-d',strtotime($em_end_date));
			$ega_video=$this->input->post('ega_video');
			
			//Upload image into folder
			$file_name='';
			if(isset($_FILES['em_logo']['name']))
			{
                $config['upload_path']=YH_UPLOAD_PATH.'event/logo/';
                $config['allowed_types']='jpg|jpeg|png';
                $this->load->library('upload',$config);
                if($this->upload->do_upload('em_logo'))
                {
                    $data=$this->upload->data();
                    $file_name=$data['file_name'];
                }
			}
			
			$error=false;
			$error_message="ERROR :";
			if($em_title==''){$error_message=$error_message."\n*Please enter your title."; $error=true;}
			if($em_detail==''){$error_message=$error_message."\n*Please enter your detail."; $error=true;}
			if($em_logo==''){$error_message=$error_message."\n*Select your image.";$error=true;}
			if($em_type==''){$error_message=$error_message."\n*Select your type.";$error=true;}
			if($em_start_date==''){$error_message=$error_message."\n*Select your start date.";$error=true;}
			if($em_end_date==''){$error_message=$error_message."\n*Select your end date.";$error=true;}
			if($em_start_time==''){$error_message=$error_message."\n*Select your start time.";$error=true;}
			if($em_end_time==''){$error_message=$error_message."\n*Select your end time.";$error=true;}
			if($em_re_region_id==''){$error_message=$error_message."\n*Select your region.";$error=true;}
			if($em_ci_city_id==''){$error_message=$error_message."\n*Select your localboard.";$error=true;}
			if($em_address==''){$error_message=$error_message."\n*Please enter your address.";$error=true;}
			if($em_latitude==''){$error_message=$error_message."\n*Please enter your latitude.";$error=true;}
			if($em_longitude==''){$error_message=$error_message."\n*Please enter your longitude.";$error=true;}
			if($error==true){$this->session->set_userdata('event_error',$error_message);}
			else if($error==false)
			{
				//Insert data in to event table creating new event
				$insertevent=array('em_um_user_id'=>$SESS_USER_ID,'em_type'=>$em_type,'em_title'=>$em_title,'em_detail'=>$em_detail,'em_logo'=>$file_name,'em_start_date'=>$event_startdate,'em_end_date'=>$event_enddate,'em_start_time'=>$em_start_time,'em_end_time'=>$em_end_time,'em_organiser'=>$SESS_USER_ID,'em_re_region_id'=>$em_re_region_id,'em_ci_city_id'=>$em_ci_city_id,'em_address'=>$em_address,'em_latitude'=>$em_latitude,'em_longitude'=>$em_longitude,'em_contact_no'=>$em_contact_no,'em_contact_email'=>$em_contact_email,'em_contact_name'=>$em_contact_name,'em_status'=>'1','em_created_on'=>date('Y-m-d H:i:s'),'em_created_by'=>$SESS_USER_ID,'em_active'=>'1');
				$em_event_id=$this->event_model->event_insert($insertevent);
				
				if($em_event_id>'0')
				{
					//creating new event user in event_user table
					$insertusers=array('eus_em_event_id'=>$em_event_id,'eus_um_user_id'=>$SESS_USER_ID,'eus_status'=>2,'eus_created_by'=>$SESS_USER_ID,'eus_created_on'=>date('Y-m-d H:i:s'),'eus_active'=>1);
					$this->event_model->event_user_insert($insertusers);
				}
				
				if($em_event_id>'0')
				{
					//generate and updating event code
					$updatecode=array('em_code'=>rand(1000,9999).$em_event_id);
					$wherecode=array('em_event_id'=>$em_event_id);
					$this->event_model->event_code_update($updatecode,$wherecode);
				}
				
				/*if($em_event_id>'0')
				{
					//mutiple date update for event schedule
					$esc_date=$this->input->post('esc_date');
					$esc_start_time=$this->input->post('esc_start_time');
					$esc_end_time=$this->input->post('esc_end_time');
					
					for($i='0'; $i<count($esc_date); $i++)
					{
						if(isset($esc_start_time[$i]) && isset($esc_end_time[$i]))
						{
							//inserting schedule list
							$insertschedule = array('esc_em_event_id'=>$em_event_id,'esc_date'=>date('Y-m-d',strtotime($esc_date[$i])),'esc_start_time'=>date("H:i:s", strtotime($esc_start_time[$i])),'esc_end_time'=>date("H:i:s", strtotime($esc_end_time[$i])),'esc_created_by'=>$SESS_USER_ID,'esc_active'=>'1');
							$this->event_model->event_schedule_insert($insertschedule);
						}
					}
				}*/
				
				if($em_event_id>'0')
				{
					$event_noti=$this->event_model->get_event_code($em_event_id);
					//notification for new event
					$insertnotiupdate=array('nu_date'=>date('Y-m-d H:i:s'),'nu_creator'=>$SESS_USER_ID,'nu_section'=>5,'nu_section_id'=>$event_noti['em_code'],'nu_section_title'=>$em_title,'nu_display'=>1,'nu_created_by'=>$SESS_USER_ID,'nu_active'=>1);
					$this->event_model->insert_notification_update($insertnotiupdate);
				}
				redirect('event');
			}
		}		
		//Full region list for new event
		$data['region_list']=$this->event_model->get_region_full_list();
		$SESS_LAYOUT=$this->session->userdata('SESS_LAYOUT');
		if($SESS_TYPE_ID!='6'){$this->load->view('event/event-add-'.$SESS_LAYOUT,$data);}
		else{redirect('event');}
	}
	
	//Function -6: Edit event
	function event_edit($event_code)
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','1','2','3','4','5','','7','8','9','10','11','12');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		$error_message=''; $error=false; $em_event_id=substr($event_code,4);
		if($_POST)
		{
			//Get values from edit form
			$em_event_id=$this->input->post('em_event_id');
			$em_code=$this->input->post('em_code');
			$em_title=$this->input->post('em_title');
			$em_detail=$this->input->post('em_detail');
			$em_logo=$_FILES['em_logo']['name'];
			if(!empty($this->input->post('em_type'))){$em_type=$this->input->post('em_type');}else{$em_type='';}
			$em_start_date=$this->input->post('em_start_date');
			$em_end_date=$this->input->post('em_end_date');
			$em_start_time=date("H:i:s",strtotime($this->input->post('em_start_time')));
			$em_end_time=date("H:i:s",strtotime($this->input->post('em_end_time')));
			if(!empty($this->input->post('em_re_region_id'))){$em_re_region_id=$this->input->post('em_re_region_id');}else{$em_re_region_id='';}
			if(!empty($this->input->post('em_ci_city_id'))){$em_ci_city_id=$this->input->post('em_ci_city_id');}else{$em_ci_city_id='';}
			$em_address=$this->input->post('em_address');
			$em_latitude=$this->input->post('em_latitude');
			$em_longitude=$this->input->post('em_longitude');
			$em_contact_no=$this->input->post('em_contact_no');
			$em_contact_email=$this->input->post('em_contact_email');
			$em_contact_name=$this->input->post('em_contact_name');
			
			//Check validation
			$error=false;
			$error_message="ERROR :";
			if($em_title==''){$error_message=$error_message."<br> Please enter your title."; $error=true;}
			if($em_detail==''){$error_message=$error_message."<br> Please enter your detail."; $error=true;}
			if($em_type==''){$error_message=$error_message."<br> Select your type.";$error=true;}
			if($em_start_date==''){$error_message=$error_message."<br> Select your date.";$error=true;}
			if($em_end_date==''){$error_message=$error_message."<br> Select your date.";$error=true;}
			if($em_start_time==''){$error_message=$error_message."<br> Select your time.";$error=true;}
			if($em_end_time==''){$error_message=$error_message."<br> Select your time.";$error=true;}
			if($em_re_region_id==''){$error_message=$error_message."<br> Select your region.";$error=true;}
			if($em_ci_city_id==''){$error_message=$error_message."<br> Select your localboard.";$error=true;}
			if($em_address==''){$error_message=$error_message."<br> Please enter your address.";$error=true;}
			if($em_latitude==''){$error_message=$error_message."<br> Please enter your latitude.";$error=true;}
			if($em_longitude==''){$error_message=$error_message."<br> Please enter your longitude.";$error=true;}
			$event_startdate = date('Y-m-d', strtotime($em_start_date));
			$event_enddate = date('Y-m-d', strtotime($em_end_date));
			
			if($error==true){$this->session->set_userdata('event_error',$error_message);}
			else if($error==false)
			{
				//Updating values
				$updateevent=array('em_type'=>$em_type,'em_title'=>$em_title,'em_detail'=>$em_detail,'em_start_date'=>$event_startdate,'em_end_date'=>$event_enddate,'em_start_time'=>$em_start_time,'em_end_time'=>$em_end_time,'em_re_region_id'=>$em_re_region_id,'em_ci_city_id'=>$em_ci_city_id,'em_address'=>$em_address,'em_latitude'=>$em_latitude,'em_longitude'=>$em_longitude,'em_contact_no'=>$em_contact_no,'em_contact_email'=>$em_contact_email,'em_contact_name'=>$em_contact_name,'em_updated_by'=>$SESS_USER_ID);
				$whereid=array('em_event_id'=>$em_event_id);
				$this->event_model->event_update($updateevent,$whereid);
				
				//Images update
				if($em_logo!='')
				{
					if($_FILES["em_logo"]["name"] != '')
					{
						$output='';
						$config["upload_path"] = YH_UPLOAD_PATH.'event/logo/';
						$config["allowed_types"] = 'jpg|jpeg|png';
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if($this->upload->do_upload('em_logo'))
						{
							$data=$this->upload->data();
							$file_name=$data["file_name"];
						}
					}
					$imagedata=array('em_logo'=>$file_name);
					$whereid2=array('em_event_id'=>$em_event_id);
					$this->event_model->event_update($imagedata,$whereid2);
				}
				
				//Notification update
				if($em_event_id>'0')
				{
					if($event_startdate<=date('Y-m-d') && $event_enddate >=date('Y-m-d'))
					{
						$users=$this->event_model->event_registered_users($em_event_id);
						$shadow_users=$this->event_model->event_shadowtech_registered_users($em_event_id);
						$res=array_merge($users,$shadow_users);
						
						foreach($users as $row)
						{
							$um_user_id=$row['um_user_id']; $em_um_user_id=$row['em_um_user_id']; $to_user_name=$row['um_name']; $em_title=$row['em_title']; $em_code=$row['em_code'];
							
							//generate notification
							$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$em_um_user_id,'nm_to'=>$um_user_id,'nm_section'=>20,'nm_section_id'=>$em_code,'nm_section_title'=>$em_title,'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
							$this->event_model->insert_notification($insertnotiupdate);
						}
					}
				}
				
				//Schedule list updating
				/*if($em_event_id>'0')
				{
					$this->event_model->event_delete_schedule($em_event_id);
					
					$esc_date=$this->input->post('esc_date');								
					for($i=0; $i<count($esc_date); $i++)
					{
						$esc_start_time=$this->input->post('esc_start_time');
						$esc_end_time=$this->input->post('esc_end_time');
							
						//Insert new schedule
						$insertschedule=array('esc_em_event_id'=>$em_event_id,'esc_date'=>date('Y-m-d',strtotime($esc_date[$i])),'esc_start_time'=>date("H:i:s",strtotime($esc_start_time[$i])),'esc_end_time'=>date("H:i:s",strtotime($esc_end_time[$i])),'esc_created_by'=>$SESS_USER_ID,'esc_active'=>'1');
						$this->event_model->event_schedule_insert($insertschedule);
					}
					//if($em_event_id>'0'){$this->session->set_userdata('event_update_success','Event Updated Successfully');}else{$this->session->set_userdata('event_update_success','Please Try Again!');}
				}*/
				redirect('event/'.$em_code.'/'.url_title($em_title));
			}
		}
		//datas for edit page
		$data['detail']=$this->event_model->get_event_detail_view($event_code);
		$eve_id=$data['detail']['em_event_id'];
		$em_code=$data['detail']['em_code'];
		$resgion_id=$data['detail']['em_re_region_id'];
		$em_um_user_id=$data['detail']['em_um_user_id'];
		$data['city_list']=$this->event_model->get_region_city_list($resgion_id);
		$data['region_list']=$this->event_model->get_region_full_list();
		$data['schedules']=$this->event_model->get_event_schedules_list($eve_id);
		$data['SESS_TYPE_ID']=$this->session->userdata('SESS_TYPE_ID');
		if($event_code==$em_code && ($em_um_user_id==$SESS_USER_ID || $SESS_USER_ID==1)){$this->load->view('event/event-edit-b',$data);}else{redirect('event');}
	}
	
	//Function -7: View event
	function event_view($event_code,$event_title='')
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','1','2','3','4','5','6','7','8','9','10','11','12');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		//Datas for view page
		$data['SESS_USER_ID']=$SESS_USER_ID; $data['SESS_TYPE_ID']=$SESS_TYPE_ID;
		$data['SESS_NAME']=$this->session->userdata('SESS_NAME');
		$data['profile_image']=$this->session->userdata('SESS_PROFILE_PICTURE');
		$data['user']=$this->event_model->get_session_user_detail($SESS_USER_ID);
		$data['detail']=$this->event_model->get_event_detail_view($event_code);
		$event_id=$data['detail']['em_event_id'];
		$event_type=$data['detail']['em_type'];
		$em_code=$data['detail']['em_code'];
		$em_active=$data['detail']['em_active'];
		$data['images']=$this->event_model->get_event_gallery_list($event_id);
		$data['videos']=$this->event_model->get_event_videos_list($event_id);
		$data['feed_backs']=$this->event_model->get_event_feedbacks_list($event_id);
		$data['participants']=$this->event_model->get_event_participants_list($event_id,$event_type);
		$data['user_participant']=$this->event_model->get_event_user_partcipant($event_id,$SESS_USER_ID);
		$data['register_youths']=$this->event_model->get_shadowtech_registered_users(10,$event_id,1);
		$data['register_business']=$this->event_model->get_shadowtech_registered_users(10,$event_id,2);
		$data['register_teacher']=$this->event_model->get_shadowtech_registered_users(11,$event_id,3);
		$schedules=array(); $check_schedules=0;
		$checkschedules=$this->event_model->check_event_schedule($event_id);
		if ($checkschedules->num_rows() > 0) {
			$checks=$checkschedules->first_row('array');
			if ($checks['total_schedule_changes'] > 0 || $checks['total_days']!=$checks['total_schedule']) {
				$check_schedules=1;
				$schedules=$this->event_model->get_event_schedule_list($event_id);
			}
		}
		$data['schedules']=$schedules;
		$count_register=$this->event_model->check_user_register($SESS_USER_ID,$event_id);
		if(isset($count_register) && $count_register!=''){$data['register_count']=$count_register;}else{$data['register_count']=0;}
		$data['register']=$this->event_model->get_event_register_detail($event_id,$SESS_USER_ID,0);	
		if(isset($data['register'])){$data['reg_val']='1';}else{$data['reg_val']='0';}
		$data['recent_events']=$this->event_model->get_recent_event($event_id,$SESS_TYPE_ID);
		$data['schools']=$this->event_model->get_event_school_list();
		
		//Inserting and updating View count
		if($event_code==$em_code && $em_active>0 && (($SESS_TYPE_ID==12 && $event_type==2) || ($SESS_TYPE_ID!=12 && ($event_type==2 || $event_type==1))))
		{
			$date=date('Y-m-d');
			$viewer_detail=$this->event_model->get_event_viwer_list($event_id,$SESS_USER_ID);
			if($viewer_detail['evi_viewer_id']>'0' && date('Y-m-d',strtotime($viewer_detail['evi_date']))!=$date)
			{
				$updateviewer=array('evi_total_visit'=>$viewer_detail['evi_total_visit']+'1','evi_ip_address'=>$_SERVER['REMOTE_ADDR'],'evi_date'=>date('Y-m-d H:i:s'));
				$whereviewer=array('evi_viewer_id'=>$viewer_detail['evi_viewer_id']);
				$this->event_model->event_view_update($whereviewer,$updateviewer);
			}
			else if($viewer_detail['evi_viewer_id']<='0')
			{
				$insertviewer=array('evi_em_event_id'=>$event_id,'evi_um_user_id'=>$SESS_USER_ID,'evi_date'=>date('Y-m-d H:i:s'),'evi_ip_address'=>$_SERVER['REMOTE_ADDR'],'evi_total_visit'=>'1');
				$this->event_model->event_viewer_insert($insertviewer);
			}
			
			$SESS_LAYOUT=$this->session->userdata('SESS_LAYOUT');
						
			//Youth view page
			$this->load->view('event/event-view-'.$SESS_LAYOUT,$data);
		}
		else
		{
			redirect('event');
		}
	}
	
	//Function -8:Delete Event
	function event_delete()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			//Delete event
			$event_id=$this->input->post('event_id');
			$event_code=$this->input->post('event_code');
			if($event_id>0 && $event_code>0)
			{
				$eventupdate=array('em_active'=>'0');
				$whereevent=array('em_event_id'=>$event_id,'em_code'=>$event_code);
				$this->event_model->event_update($eventupdate,$whereevent);
			}
		}
	}
	
	//Function -9: Cancel event
	function event_cancel()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		if($_POST)
		{
			$event_id=$this->input->post('event_id');
			$event_code=$this->input->post('event_code');
			
			if($event_id>0 && $event_code>0)
			{
				$updateevent=array('em_status'=>'3');
				$whereevent=array('em_event_id'=>$event_id,'em_code'=>$event_code);
				$this->event_model->event_update($updateevent,$whereevent);
				
				$users=$this->event_model->event_registered_users($event_id);
				$shadow_users=$this->event_model->event_shadowtech_registered_users($event_id);
				$res=array_merge($users,$shadow_users);
				foreach($res as $row)
				{
					$um_name=$row['um_name']; $um_user_id=$row['um_user_id']; $em_um_user_id=$row['em_um_user_id']; $to_user_mail=$row['um_email']; $to_user_name=$row['um_name']; $em_title=$row['em_title']; $em_code=$row['em_code'];
					
					//generate notification
					$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$em_um_user_id,'nm_to'=>$um_user_id,'nm_section'=>16,'nm_section_id'=>$em_code,'nm_section_title'=>$em_title,'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
					$this->event_model->insert_notification($insertnotiupdate);					
					$data['shorttitle']='Event Cancellation';
					$data['user_name']=$um_name;
					$data['content']="We are sorry, unfortunately event <a target='blank' href='".base_url()."event/".$em_code."'>".$em_title."</a> has cancelled.";
					$data['url']="href='".base_url()."event/".$em_code."'";
					$mail_body=$this->load->view('event/event-mail',$data,TRUE);
					
					$from_email = "support@youthhub.co.nz";
					$to_email = $to_user_name;
					
					$this->email->clear();
					$this->email->from($from_email);
					$this->email->to($to_email );
					$this->email->subject("Youth Hub : Event has been Cancelled");
					$this->email->set_mailtype("html");
					$this->email->message($mail_body);
					$this->email->send();		
				}
			}
		}
	}
		
	//Function -10: Create New chat
	function event_chat()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$event_id=$this->input->post('event_id');
		$chat_message=$this->input->post('chat_msg');
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		if($event_id>'0' && $chat_message!='')
		{
			//inserting chat values
			$insertchat=array('efb_em_event_id'=>$event_id,'efb_um_user_id'=>$SESS_USER_ID,'efb_type'=>'2','efb_message'=>$chat_message,'efb_parent_id'=>'0','efb_created_by'=>$SESS_USER_ID,'efb_created_on'=>date('Y-m-d H:i:s'),'efb_active'=>'1');
			echo $efb_feed_id=$this->event_model->event_chat_insert($insertchat);
		}
	}
	
	//Function -11: Image upload in table
	function event_image_add()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$att_type=$this->input->post('att_type');
		$att_code=$this->input->post('att_code');
		$event_id=$this->input->post('event_id');
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		if(isset($att_code) && $att_code>0)
		{
			$galleries=$this->event_model->get_temp_gallery($att_code);
			if(isset($galleries) && count($galleries)>0)
			{
				foreach($galleries as $gallery)
				{
					$att_um_user_id=$gallery['att_um_user_id']; $att_type=$gallery['att_type']; $att_name=$gallery['att_name'];
					$att_poster=$gallery['att_poster']; $att_size=$gallery['att_size']; $att_created_on=$gallery['att_created_on'];
					$att_title=$gallery['att_title']; $att_description=$gallery['att_description'];
					
					if($att_type==1){ $image_name=$att_name;}else{$image_name='';}
					if($att_type==3){ $video_name=$att_name;}else{$video_name='';}
					
					$insert_gallery=array('ega_em_event_id'=>$event_id,'ega_um_user_id'=>$att_um_user_id,'ega_type'=>$att_type,'ega_image'=>$image_name,'ega_video'=>$video_name,'ega_video_poster'=>$att_poster,'ega_size'=>$att_size,'ega_title'=>$att_title,'ega_description'=>$att_description,'ega_created_by'=>$SESS_USER_ID,'ega_created_on'=>date('Y-m-d'),'ega_active'=>1);
					$ega_gallery_id=$this->event_model->event_gallery_insert($insert_gallery);
				}
			}
			
			$event_noti=$this->event_model->get_event_code($event_id);
			//generate notification update
			$insertnotiupdate=array('nu_date'=>date('Y-m-d H:i:s'),'nu_creator'=>$SESS_USER_ID,'nu_section'=>19,'nu_section_id'=>$event_noti['em_code'],'nu_section_title'=>$event_noti['em_title'],'nu_display'=>1,'nu_created_by'=>$SESS_USER_ID,'nu_active'=>1);
			$this->event_model->insert_notification_update($insertnotiupdate);
			
			$galery_delete=array('att_code'=>$att_code,'att_section'=>2);
			$this->event_model->event_delete_temp_gallery($galery_delete);
			
			if($att_type==1)
			{
				$data['images']=$this->event_model->get_event_gallery_list($event_id);
				$this->load->view('event/event-image-b',$data);
			}
			
			if($att_type==3)
			{
				$data['videos']=$this->event_model->get_event_videos_list($event_id);
				$this->load->view('event/event-video-b',$data);
			}
		}
	}
	
	//Function -12: Uploaded image delete from temp table
	function event_upload_image_delete()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$att_type=$this->input->post('att_type');
		$att_code=$this->input->post('att_code');
		$event_id=$this->input->post('event_id');
		if(isset($att_code) && $att_code>0)
		{
			$galery_delete=array('att_code'=>$att_code,'att_section'=>2);
			$this->event_model->event_delete_temp_gallery($galery_delete);
			
			if($att_type==1)
			{
				$data['images']=$this->event_model->get_event_gallery_list($event_id);
				$this->load->view('event/event-image-b',$data);
			}
			
			if($att_type==3)
			{
				$data['videos']=$this->event_model->get_event_videos_list($event_id);
				$this->load->view('event/event-video-b',$data);
			}
		}
	}		
	
	//Function -13:Feedback delete
	function event_delete_feedback()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$event_id=$this->input->post('event_id');
		$efb_feed_id=$this->input->post('feedback_id');
		if($event_id>'0' && $efb_feed_id>'0')
		{
			//delete feedbacks
			$updatedelete=array('efb_active'=>'0');
			$whereid=array('efb_feed_id'=>$efb_feed_id,'efb_em_event_id'=>$event_id);
			$efb_feed_id=$this->event_model->event_feedback_update($updatedelete,$whereid);
			echo '1';
		}
	}
	
	//Function -14:event count me in and count me out status update
	function event_count_status()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		if($_POST)
		{
			//getting values form script
			$event_id=$this->input->post('event_id');
			$status_val=$this->input->post('status_val');
			$eus_user_id=$this->input->post('euser_id');
			if($status_val==1)
			{
				$event_noti=$this->event_model->get_event_code($event_id);
				//generate notification
				$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$SESS_USER_ID,'nm_to'=>$event_noti['em_um_user_id'],'nm_section'=>17,'nm_section_id'=>$event_noti['em_code'],'nm_section_title'=>$event_noti['em_title'],'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
				$this->event_model->insert_notification($insertnotiupdate);
				
				if($eus_user_id=='0')
				{
					//insert new user
					$insertuser=array('eus_em_event_id'=>$event_id,'eus_um_user_id'=>$SESS_USER_ID,'eus_status'=>'1','eus_verified'=>'0','eus_created_by'=>$SESS_USER_ID,'eus_active'=>'1');
					echo $eus_user_id=$this->event_model->event_user_insert($insertuser);					
				}
				else
				{
					//updating existed user data
					$updateuser=array('eus_status'=>'1');
					$whereuser=array('eus_user_id'=>$eus_user_id,'eus_em_event_id'=>$event_id);
					$this->event_model->event_user_update($updateuser,$whereuser);
					echo $eus_user_id;
				}
			}
			else if($status_val=='2')
			{
				//updating existed user data
				$updateuser2=array('eus_status'=>'0');
				$whereuser2=array('eus_user_id'=>$eus_user_id,'eus_em_event_id'=>$event_id);
				$this->event_model->event_user_update($updateuser2,$whereuser2);
				echo $eus_user_id;
			}
		}
	}
	
	//Function -15: update register for count me in  and count me out status values
	function event_register_status()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_NAME=$this->session->userdata('SESS_NAME');
		if($_POST)
		{
			$status_val=$this->input->post('status_val');
			$event_id=$this->input->post('event_id');
			$event_user=$this->input->post('euser_id');
			$register_id=$this->input->post('register_id');
			if($status_val==1)
			{
				$updateregister=array('ere_active'=>'0');
				$whereregister=array('ere_register_id'=>$register_id,'ere_em_event_id'=>$event_id,'ere_um_user_id'=>$event_user);
				$this->event_model->event_user_register_update($updateregister,$whereregister);
				echo $register_id;
			}
			else if($status_val==2)
			{
				if($register_id<=0)
				{
					$count_register=$this->event_model->check_user_register($event_user);
					
					$insertregister=array('ere_em_event_id'=>$event_id,'ere_um_user_id'=>$event_user,'ere_ut_type_id'=>6,'ere_first_name'=>$SESS_NAME,'ere_school_id'=>$count_register['ere_school_id'],'ere_school_name'=>$count_register['ere_school_name'],'ere_teacher_name'=>$count_register['ere_teacher_name'],'ere_created_by'=>$SESS_USER_ID,'ere_active'=>'1');
					echo $register_id=$this->event_model->event_register_insert($insertregister);
				}
				else if($register_id>0)
				{
					$updateregister=array('ere_active'=>'1');
					$whereregister=array('ere_register_id'=>$register_id,'ere_em_event_id'=>$event_id,'ere_um_user_id'=>$event_user);
					$this->event_model->event_user_register_update($updateregister,$whereregister);
					echo $register_id;
				}
				
				$event_noti=$this->event_model->get_event_code($event_id);
				//generate notification
				$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$SESS_USER_ID,'nm_to'=>$event_noti['em_um_user_id'],'nm_section'=>18,'nm_section_id'=>$event_noti['em_code'],'nm_section_title'=>$event_noti['em_title'],'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
				$this->event_model->insert_notification($insertnotiupdate);
			}
		}
	}
	
	//Function -16: Register for business
	function event_shadowtech_business_register()
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_NAME=$this->session->userdata('SESS_NAME');
		
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','','','','','','','','8');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		$error_message1=''; $error=false;
		if($_POST)
		{
			//Getting register add form
			$ere_register_id=$this->input->post('ere_register_id');
			$ere_um_user_id=$this->input->post('ere_um_user_id');
			$ere_first_name=$this->input->post('ere_first_name');
			$ere_last_name=$this->input->post('ere_last_name');
			$ere_contact_email=$this->input->post('ere_contact_email');
			$ere_contact_phone=$this->input->post('ere_contact_phone');
			$ere_mentored_before=$this->input->post('ere_mentored_before');
			$ere_no_of_mentors=$this->input->post('ere_no_of_mentors');
			$ere_no_of_students=$this->input->post('ere_no_of_students');
			$ere_marketing_and_promotion=$this->input->post('ere_marketing_and_promotion');
			$ere_transport=$this->input->post('ere_transport');
			$ere_criminal=$this->input->post('ere_criminal');
			$ere_em_event_id=$this->input->post('ere_em_event_id');
			$em_title=$this->input->post('em_title');
			if(!empty($this->input->post('ere_mentored_detail')))
			{
				$ere_mentored_detail=$this->input->post('ere_mentored_detail');
				$mentored_detail='0,'.implode(',',$ere_mentored_detail).',0';
			}
			else
			{
				$mentored_detail='0,,0';
			}
			$em_code=$this->input->post('em_code');
			$um_is_shadowtech=$this->input->post('um_is_shadowtech');
			$um_email=$this->input->post('um_email');
			
			//Validation checking
			$error=false;
			$error_message="ERROR :";
			if($ere_no_of_mentors==''){$error_message=$error_message."<br> Please enter your mentors."; $error=true;}
			if($ere_first_name==''){$error_message=$error_message."<br> Please enter your first name.";$error=true;}
			if($ere_last_name==''){$error_message=$error_message."<br> Please enter your last name.";$error=true;}
			if($ere_contact_phone==''){$error_message=$error_message."<br> Please enter your phone.";$error=true;}
			if($ere_contact_email==''){$error_message=$error_message."<br> Please enter your email.";$error=true;}
			if($ere_marketing_and_promotion==''){$error_message=$error_message."<br> Please enter your marketing and promotion.";$error=true;}
			if($ere_mentored_before==''){$error_message=$error_message."<br> Please enter your mentored before.";$error=true;}
			//if($ere_mentored_detail==''){$error_message=$error_message."<br> Please enter your mentored detail.";$error=true;}
			
			if($error==false)
			{
				//Inserting new register value
				$insertregister=array('ere_em_event_id'=>$ere_em_event_id,'ere_um_user_id'=>$ere_um_user_id,'ere_ut_type_id'=>'8','ere_first_name'=>$ere_first_name,'ere_last_name'=>$ere_last_name,'ere_contact_email'=>$ere_contact_email,'ere_contact_phone'=>$ere_contact_phone,'ere_activity'=>'','ere_mentored_before'=>$ere_mentored_before,'ere_mentored_detail'=>$mentored_detail,'ere_no_of_mentors'=>$ere_no_of_mentors,'ere_no_of_students'=>$ere_no_of_students,'ere_marketing_and_promotion'=>$ere_marketing_and_promotion,'ere_transport'=>$ere_transport,'ere_criminal'=>$ere_criminal,'ere_created_on'=>date('Y-m-d H:i:s'),'ere_created_by'=>$SESS_USER_ID,'ere_active'=>'1');
				if($ere_register_id>'0')
				{
					//updating existed register value
					$where=array('ere_register_id'=>$ere_register_id);
					$this->event_model->event_register_update($insertregister,$where);
				}
				else
				{
					//Insert new register
					$this->event_model->event_register_insert($insertregister);
					
					$data['shorttitle']='Your organisation is registered for ShadowTech';
					$data['user_name']=$SESS_NAME;
					$data['content']="Thanks for registering your organisation for the ".$em_title."<br><br><strong>What's next?</strong><br>After registration closes, the team at NZTech will assign students to your organisation based on the number of mentors you have listed.  The event contact person listed will be emailed with details specific to the day including venue location and times.<br>Please note that this may be some time after you registered so please save the date in your mentors' calendars now.<br><br><strong>Need to change something?</strong><br>If you need to change any of your organisation's ShadowTech registration details such as number of mentors or regional contact person, refer to the FAQ page under the Mentors Section on the <a title='ShadowTech' href='https://shadowtechday.nz/' target='_blank' rel='noopener noreferrer' style='font-weight:bold;letter-spacing:normal;line-height:100%;text-align:center;text-decoration:none;color:#00F;'>ShadowTech website</a><br><br>Further information can be found on the <a title='ShadowTech' href='https://shadowtechday.nz/shadow-tech-business-mentors/' target='_blank' rel='noopener noreferrer' style='font-weight:bold;letter-spacing:normal;line-height:100%;text-align:center;text-decoration:none;color:#00F;'>ShadowTech website</a>.<br>";
					$data['url']="href='".base_url()."event/".$em_code."'";
					$mail_body=$this->load->view('event/event-mail',$data,TRUE);
					
					$from_email = "support@youthhub.co.nz";
					$to_email = $ere_contact_email;
					
					$this->email->clear();
					$this->email->from($from_email);//ur email
					$this->email->to($to_email );//ur email
					$this->email->subject("Youth Hub : Your organisation is registered for ShadowTech");
					$this->email->set_mailtype("html");
					$this->email->message($mail_body);
					$this->email->send();
					
					$insertuser=array('eus_em_event_id'=>$ere_em_event_id,'eus_um_user_id'=>$SESS_USER_ID,'eus_status'=>'1','eus_verified'=>'0','eus_created_by'=>$SESS_USER_ID,'eus_created_on'=>date('Y-m-d H:i:s'),'eus_active'=>'1');
					$this->event_model->event_user_insert($insertuser);
					
					//Updating user master shadowtech value
					$data=array('um_is_shadowtech'=>'1');
					$where=array('um_user_id'=>$SESS_USER_ID);
					$this->event_model->user_update($data,$where);
					
					$this->session->set_userdata('IS_SHADOWTECH','1');
					
					$event_noti=$this->event_model->get_event_code($ere_em_event_id);
					//generate and updating event code
					$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$SESS_USER_ID,'nm_to'=>$event_noti['em_um_user_id'],'nm_section'=>18,'nm_section_id'=>$event_noti['em_code'],'nm_section_title'=>$event_noti['em_title'],'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
					$this->event_model->insert_notification($insertnotiupdate);
				}
			}
			redirect('event/'.$em_code.'/'.url_title($em_title));
		}
	}
	
	//Function -17: register student
	function event_shadowtech_student_register()
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_NAME=$this->session->userdata('SESS_NAME');
		
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','','','','','','6');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		if($_POST)
		{
			$ere_em_event_id=$this->input->post('ere_em_event_id');
			$em_title=$this->input->post('em_title');
			$em_code=$this->input->post('em_code');
			$um_is_shadowtech=$this->input->post('um_is_shadowtech');
			$um_email=$this->input->post('um_email');
			if($ere_em_event_id>'0')
			{
				$ere_school_id=0+$this->input->post('ere_school_id');
				if($this->input->post('ere_school_name')!=''){$ere_school_name=$this->input->post('ere_school_name');}else{$ere_school_name='0';}
				$ere_teacher_name=$this->input->post('ere_teacher_name');
				
				if($ere_teacher_name!='' && ($ere_school_name!='' || $ere_school_id>'0'))
				{
					//Insert student registration
					$sdata=array('ere_em_event_id'=>$ere_em_event_id,'ere_um_user_id'=>$SESS_USER_ID,'ere_ut_type_id'=>'6','ere_first_name'=>$SESS_NAME,'ere_school_id'=>$ere_school_id,'ere_school_name'=>$ere_school_name,'ere_teacher_name'=>$ere_teacher_name,'ere_created_by'=>$SESS_USER_ID,'ere_active'=>'1');
					$this->event_model->event_register_insert($sdata);
					
					if($um_is_shadowtech<1 && $um_email!='')
					{
						$data['shorttitle']='ShadowTech account registration with YouthHub';
						$data['user_name']=$SESS_NAME;
						$data['content']="Thanks for registering for the ".$em_title."<br><br><strong>What's next?</strong><br>The team at NZTech will soon be in touch with your school with all the information about the day. You'll find out who your mentor is on the morning of your ShadowTech day, and you'll get to spend time with them during their typical work day – they'll even shout you lunch!<br><br>For more information about what to expect, check out the <a title='ShadowTech' href='https://shadowtechday.nz/shadow-tech-students/' target='_blank' rel='noopener noreferrer' style='font-weight:bold;letter-spacing:normal;line-height:100%;text-align:center;text-decoration:none;color:#00F;'>ShadowTech website</a>.<br>";
						$data['url']="href='".base_url()."event/".$em_code."'";
						$mail_body=$this->load->view('event/event-mail',$data,TRUE);
						
						$from_email = "support@youthhub.co.nz";
						$to_email = $um_email;
						
						$this->email->clear();
						$this->email->from($from_email);//ur email
						$this->email->to($to_email );//ur email
						$this->email->subject("Youth Hub : ShadowTech account registration with YouthHub");
						$this->email->set_mailtype("html");
						$this->email->message($mail_body);
						$this->email->send();
					}
							
					//Update users shadowtect status
					$data=array('um_is_shadowtech'=>'1');
					$where=array('um_user_id'=>$SESS_USER_ID);
					$this->event_model->user_update($data,$where);
					
					$this->session->set_userdata('IS_SHADOWTECH','1');
					
					//Insert users for register users
					$insertuser=array('eus_em_event_id'=>$ere_em_event_id,'eus_um_user_id'=>$SESS_USER_ID,'eus_status'=>'1','eus_verified'=>'0','eus_created_by'=>$SESS_USER_ID,'eus_created_on'=>date('Y-m-d H:i:s'),'eus_active'=>'1');
					$this->event_model->event_user_insert($insertuser);
					
					$event_noti=$this->event_model->get_event_code($ere_em_event_id);
					//generate and updating event code
					$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$SESS_USER_ID,'nm_to'=>$event_noti['em_um_user_id'],'nm_section'=>18,'nm_section_id'=>$event_noti['em_code'],'nm_section_title'=>$event_noti['em_title'],'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
					$this->event_model->insert_notification($insertnotiupdate);
				}
			}
			redirect('event/'.$em_code.'/'.url_title($em_title));	
		}
	}
	
	//Function -18:Shadowtect register for teacher list
	function event_shadowtech_student($event_id='')
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','','','','','','','7');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		$IS_SHADOWTECH=$this->session->userdata('IS_SHADOWTECH'); 
		$data['event']=$this->event_model->get_event_detail($event_id); $ere_school_id='';
		$data['SESS_USER_ID']=$SESS_USER_ID;
		
		if($SESS_TYPE_ID=='7')
		{
			if($IS_SHADOWTECH>'0')
			{
				//After login shadowtech registration
				$data['events']=$this->event_model->get_event_shadowtech_list();
				$data['teacher_info']=$this->event_model->get_event_teacher_deatail($SESS_USER_ID);
				$ere_school_id=$data['teacher_info']['tch_ogm_organisation_id'];
				$data['allstudents']=$this->event_model->get_event_allstudents_list($ere_school_id);
				$data['mystudents']=$this->event_model->get_event_mystudents_list($ere_school_id,$SESS_USER_ID);
				$this->load->view('event/event-shadowtech-home',$data);
			}
			else
			{
				$data['user']=$this->event_model->get_session_user_detail($SESS_USER_ID);
				//Before login shadowtech registration
				$data['schools']=$this->event_model->get_event_school_list();
				$this->load->view('event/event-shadowtech-login',$data);
				
				$event_noti=$this->event_model->get_event_code($event_id);
				//generate and updating event code
				$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$SESS_USER_ID,'nm_to'=>$event_noti['em_um_user_id'],'nm_section'=>18,'nm_section_id'=>$event_noti['em_code'],'nm_section_title'=>$event_noti['em_title'],'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
				$this->event_model->insert_notification($insertnotiupdate);
			}
		}
		else
		{
			redirect('event');
		}
	}
	
	//Function -19: sending mail and getting code for shadowtech event for teacher
	function event_shadowtech_teacher_request()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_NAME=$this->session->userdata('SESS_NAME');
		$user_info=$this->event_model->get_event_user_detail($SESS_USER_ID);
		$um_email=$user_info['um_email'];
		
		
		$data['shorttitle']='ShadowTech Access Code';
		$data['user_name']=$SESS_NAME;
		$data['content']="Thanks for registering for shadowtech, your access number is <b>856412</b>";
		$data['url']="";
		$mail_body=$this->load->view('event/event-mail',$data,TRUE);
		
		$from_email = "support@youthhub.co.nz";
		$to_email = $um_email;
		
		$this->email->clear();
		$this->email->from($from_email);//ur email
		$this->email->to($to_email );//ur email
		$this->email->subject("Youth Hub : ShadowTech Access Code");
		$this->email->set_mailtype("html");
		$this->email->message($mail_body);
		if($this->email->send()){echo '<span style="color:#4caf50"><b>Success!</b> Your verification code has been sucessfully sent to your registered email id end with xxx'.substr(strstr($to_email,'@',true),-3).strstr($to_email,'@').'</span>';}
		else{echo '<span style="color:#F00"><b>Sorry!</b> Something went wrong please try again.</span>';}
		
	}
	
	//Function-20: Teacher Verifying access code
	function event_shadowtech_teacher_verify()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$return='0';
		if($_POST)
		{
			$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
			$SESS_NAME=$this->session->userdata('SESS_NAME');
			$access_code=$this->input->post('access_code');
			$schoolid=$this->input->post('schoolid');
			
			$um_is_shadowtech=$this->input->post('um_is_shadowtech');
			$um_email=$this->input->post('um_email');
			
			if($access_code=='856412' && $schoolid>'0')
			{
				//updating teacher register
				$updateregister=array('tch_ogm_organisation_id'=>$schoolid);
				$whereregister=array('tch_um_user_id'=>$SESS_USER_ID);
				$this->event_model->event_teacher_update($updateregister,$whereregister);
				
				if($um_is_shadowtech<1)
				{
					$data['shorttitle']='ShadowTech account registration with YouthHub';
					$data['user_name']=$SESS_NAME;
					$data['content']="Thanks for creating an account with ShadowTech on the YouthHub Platform.<br>The next step is to see which students have already registered, and get others to sign-up to participate.<br><br><strong>How do students register?</strong><br>Your students can register to participate in ShadowTech Day (and create their own personal profile on the YouthHub platform at the same time) by going to: <a title='ShadowTech' href='https://shadowtech.youthhub.co.nz/' target='_blank' rel='noopener noreferrer' style='font-weight:bold;letter-spacing:normal;line-height:100%;text-align:center;text-decoration:none;color:#00F;'>https://shadowtech.youthhub.co.nz/</a><br><br><strong>How can I see or change who has registered?</strong><br>As a Teacher, you can see which students have registered, and register any additional students if needed. Simply <a title='YouthHub' href='".base_url('login/shadowtech')."' target='_blank' rel='noopener noreferrer' style='font-weight:bold;letter-spacing:normal;line-height:100%;text-align:center;text-decoration:none;color:#00F;'>login to YouthHub</a> here.<br>To add extra students, select 'Add Student' from your dashboard, select your local ShadowTech Event from the drop down list provided and then proceed to add students to the event.<br><br><strong>What to know more?</strong><br>For more information about ShadowTech visit the ShadowTech website: <a title='ShadowTech' href='https://shadowtech.nz/' target='_blank' rel='noopener noreferrer' style='font-weight:bold;letter-spacing:normal;line-height:100%;text-align:center;text-decoration:none;color:#00F;'>https://shadowtech.nz</a>.<br>";
					$data['url']="";
					$mail_body=$this->load->view('event/event-mail',$data,TRUE);
					
					$from_email = "support@youthhub.co.nz";
					$to_email = $um_email;
					
					$this->email->clear();
					$this->email->from($from_email);//ur email
					$this->email->to($to_email );//ur email
					$this->email->subject("Youth Hub : ShadowTech account registration with YouthHub");
					$this->email->set_mailtype("html");
					$this->email->message($mail_body);
					$this->email->send();
				}
				
				
				//Updating user shadowtech
				$updateuser=array('um_is_shadowtech'=>'1');
				$whereuser=array('um_user_id'=>$SESS_USER_ID);
				$this->event_model->user_update($updateuser,$whereuser);
				
				$this->session->set_userdata('IS_SHADOWTECH','1');				
				$return='1';
			}
		}
		echo $return;
	}
	
	//Function-21: teacher add student for event
	function event_shadowtech_student_add()
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','','','','','','','7');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		if($_POST)
		{
			//Teacher add student values
			$ere_school_id=$this->input->post('ere_school_id');
			$ere_teacher_name=$this->input->post('ere_teacher_name');
			$ere_em_event_id=$this->input->post('ere_em_event_id');
			
			for($i='1';$i<='20';$i++)
			{
				$ere_first_name=$this->input->post('ere_first_name'.$i); //$this->security->xss_clean($_POST['ere_first_name'.$i]);
				$ere_last_name=$this->input->post('ere_last_name'.$i); //$this->security->xss_clean($_POST['ere_last_name'.$i]);
				$ere_ethnicity=$this->input->post('ere_ethnicity'.$i); //$this->security->xss_clean($_POST['ere_ethnicity'.$i]);
				$ere_age=$this->input->post('ere_age'.$i); //$this->security->xss_clean($_POST['ere_age'.$i]);
				$ere_permission_slip=$this->input->post('ere_permission_slip'.$i); //$this->security->xss_clean($_POST['ere_permission_slip'.$i]);
				
				//Insert student
				if($ere_first_name!='' && $ere_last_name!='' && $ere_ethnicity!='' && $ere_age>'0' && $ere_permission_slip!='')
				{
					$studentdata=array('ere_em_event_id'=>$ere_em_event_id,'ere_ut_type_id'=>6,'ere_first_name'=>$ere_first_name,'ere_last_name'=>$ere_last_name,'ere_school_id'=>$ere_school_id,'ere_teacher_name'=>$ere_teacher_name,'ere_permission_slip'=>$ere_permission_slip,'ere_ethnicity'=>$ere_ethnicity,'ere_age'=>$ere_age,'ere_created_by'=>$SESS_USER_ID,'ere_active'=>'1');
					$this->event_model->event_register_insert($studentdata);
					$esm_student_id=$this->db->insert_id();
				}
				$ere_first_name=''; $ere_last_name=''; $ere_ethnicity=''; $ere_age=''; $ere_permission_slip='';
			}
			
			$event_noti=$this->event_model->get_event_code($ere_em_event_id);
			//generate and updating event code
			$insertnotiupdate=array('nm_date'=>date('Y-m-d H:i:s'),'nm_from'=>$SESS_USER_ID,'nm_to'=>$event_noti['em_um_user_id'],'nm_section'=>19,'nm_section_id'=>$event_noti['em_code'],'nm_section_title'=>$event_noti['em_title'],'nm_read'=>0,'nm_created_by'=>$SESS_USER_ID,'nm_active'=>1);
			$this->event_model->insert_notification($insertnotiupdate);
		}
		redirect(base_url()."shadowtech/student");
	}
	
	//Function-22: teacher edit student
	function event_shadowtech_student_view()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			//View page for student edit view
			$register_id=$this->input->post('register_id');
			$data['events']=$this->event_model->get_event_shadowtech_list();
			$data['student_detail']=$this->event_model->get_event_student_detail($register_id);
			$this->load->view('event/event-edit-student',$data);
		}
	}
	
	//Function-23: teacher Update student info
	function event_shadowtech_student_edit()
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','','','','','','','7');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		if($_POST)
		{
			$ere_register_id=$this->input->post('ere_register_id');
			$ere_em_event_id=$this->input->post('ere_em_event_id');
			$ere_first_name=$this->input->post('ere_first_name1');
			$ere_last_name=$this->input->post('ere_last_name1');
			$ere_ethnicity=$this->input->post('ere_ethnicity1');
			$ere_age=$this->input->post('ere_age1');
			$ere_permission_slip=$this->input->post('ere_permission_slip1');
			
			if($ere_em_event_id>'0' && $ere_first_name!='' && $ere_last_name!='' && $ere_ethnicity!='' && $ere_age!='')
			{
				$updatestudent=array('ere_em_event_id'=>$ere_em_event_id,'ere_first_name'=>$ere_first_name,'ere_last_name'=>$ere_last_name,'ere_permission_slip'=>$ere_permission_slip,'ere_ethnicity'=>$ere_ethnicity,'ere_age'=>$ere_age,'ere_updated_by'=>$SESS_USER_ID);
				$wheredate=array('ere_register_id'=>$ere_register_id);
				$this->event_model->event_register_update($updatestudent,$wheredate);
			}
			redirect('shadowtech/student');
		}
	}
	
	//Function -24: teacher Delete student
	function event_shadowtech_student_delete()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			$register_id=$this->input->post('register_id');
			if($register_id>'0')
			{
				$deleteupdatedata=array('ere_active'=>'0');
				$deletewheredate=array('ere_register_id'=>$register_id);
				$this->event_model->event_register_update($deleteupdatedata,$deletewheredate);
				echo '1';
			}
		}
	}
	
	//Function -25: get map
	function event_map()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			$data['event_address']=$this->input->post('event_address');
			$this->load->view('event/event-map',$data);
		}
	}
	
	//Function -26: get participant List
	function event_participant_list()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$data['SESS_TYPE_ID']=$this->session->userdata('SESS_TYPE_ID');
		if($_POST)
		{
			$event_id=$this->input->post('event_id');
			$event_type=$this->input->post('event_type');
			$data['participants']=$this->event_model->get_event_participants_list($event_id,$event_type);
			$data['event_type']=$event_type;
			$this->load->view('event/event-participant-list',$data);
		}
	}
	
	//Function -27: Shadowtech users list download
	function event_shadowtech_export($download_id,$event_id=0,$part_id=0)
	{
		if(!$this->ion_auth->logged_in()){redirect('login');} 
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','1','','','','','','','','','','','12');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		
		$data['event_detail']=array(); $data['shadowtech_youths']=array();
		$data['shadowtech_users']=$this->event_model->get_shadowtech_registered_users($download_id,$event_id,$part_id);
		if($download_id==1 || $download_id==6){$data['shadowtech_youths']=$this->event_model->get_shadowtech_registered_youths($download_id);}
		$data['shadowtech_users']=array_merge($data['shadowtech_users'], $data['shadowtech_youths']);
		if($event_id>0){$data['event_detail']=$this->event_model->get_event_detail($event_id);}
		$data['part_id']=$part_id;
		$this->load->view('event/shadow-download-'.$download_id,$data);
	}
	
	//Function -28: Participants verification
	function event_participant_verification()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			$ref_id=$this->input->post('ref_id');
			$event_id=$this->input->post('event_id');
			$user_id=$this->input->post('user_id');
			if($ref_id>'0')
			{
				$update_users=array('eus_verified'=>'1');
				$where_users=array('eus_em_event_id'=>$event_id,'eus_um_user_id'=>$user_id,'eus_status'=>1);
				$this->event_model->event_user_update($update_users,$where_users);
			}
			else if($ref_id<='0')
			{
				$update_users=array('eus_verified'=>'0');
				$where_users=array('eus_em_event_id'=>$event_id,'eus_um_user_id'=>$user_id,'eus_status'=>1);
				$this->event_model->event_user_update($update_users,$where_users);
			}
		}
	}
	
	//Function -29: Upload images temp gallery
	function event_upload_gallery_temp()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$fname=$this->input->post('fname');
		$att_type=$this->input->post('att_type');
		$att_code=$this->input->post('att_code');
		$file_size=$this->input->post('fsize');
		$SESS_USER_ID=$this->session->userdata('SESS_USER_ID');
		$data5=array('att_code'=>$att_code,'att_um_user_id'=>$SESS_USER_ID,'att_section'=>2,'att_type'=>$att_type,'att_name'=>$fname,'att_size'=>$file_size,'att_created_by'=>$SESS_USER_ID,'att_created_on'=>date('Y-m-d h:i:s'),'att_active'=>1);
		$add_data5=$this->event_model->event_add_temp_gallery($data5);
		if($add_data5>0 && $att_type==3)
		{
			$poster_image=$this->event_generate_poster($add_data5);			
		}
		
		echo '1';
	}
	
	// event Poster Image generate
	function event_generate_poster($temp_content_id)
	{
		//$CI=&get_instance();
		$this->db->select("att_name")->from('attachment_temp')->where(array('att_attachment_id'=>$temp_content_id,'att_type'=>3));
		$pt_name=$this->db->get()->row()->att_name;
		if($pt_name!='')
		{
			$hostname=$_SERVER['HTTP_HOST']; $domainname='e';
			$interval=5; $size='250x250'; $dhpath=YH_UPLOAD_PATH.'event/';
			//setup video
			$video_name=$pt_name;
			$video_path=$dhpath.$video_name;
			//setup poster
			$poster_img_name=$domainname.$temp_content_id.time().'.jpg';
			$poster_path=YH_UPLOAD_PATH.'event/poster/'.$poster_img_name;
			if(strpos($video_name,' ')==TRUE){	
				$video_name_new=str_replace(" ","",$video_name);
				$video_path_new=$dhpath.$video_name_new;	
				rename($video_path,$video_path_new);
				exec('ffmpeg -i '.escapeshellarg($video_path_new).' -y -f mjpeg -ss 00:00:05 -s 250x250 -vframes 1 -an '.escapeshellarg($poster_path).' -hide_banner -loglevel error 2>&1',$errors);
				rename($video_path_new,$video_path);
			}else{
				exec('ffmpeg -i '.escapeshellarg($video_path).' -y -f mjpeg -ss 00:00:05 -s 2500x250 -vframes 1 -an '.escapeshellarg($poster_path).' -hide_banner -loglevel error 2>&1',$errors);
			}
			$where_poster=array('att_attachment_id'=>$temp_content_id,'att_section'=>'2');
			$update_poster=array('att_poster'=>$poster_img_name);
			$post_update11=$this->event_model->event_poster_image_update($where_poster,$update_poster);
		}
	}
	
	//Function -30: delete images temp gallery
	function delete_gallery_temp()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		$file_name=$this->input->post('file_name');
		$att_code=$this->input->post('att_code');
		$att_type=$this->input->post('att_type');
		$galery_delete=array('att_name'=>$file_name,'att_code'=>$att_code,'att_section'=>2);
		$this->event_model->event_delete_temp_gallery($galery_delete);
	}
	
	//function -30: Media delete
	function event_media_delete()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			$gallery_id=$this->input->post('gallery_id');
			$galery_delete=array('ega_active'=>0);
			$galery_where=array('ega_gallery_id'=>$gallery_id);
			$this->event_model->event_media_delete($galery_delete,$galery_where);
			echo $gallery_id;
		}
	}
	
	//Function -31: Shadowtect business edit
	function event_shadowtect_business_edit()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			$user_id=$this->input->post('user_id');
			$register_id=$this->input->post('register_id');
			$event_id=$this->input->post('event_id');
			$data['user']=$this->event_model->get_session_user_detail($user_id);
			$data['detail']=$this->event_model->get_event_detail($event_id);
			$data['register']=$this->event_model->get_event_register_detail($event_id,0,$register_id);
			$this->load->view('event/event-shadowtech-business',$data);
		}
	}
	
	//Function -32: shadowtech youth edit
	function event_shadowtect_youth_view()
	{
		if(!$this->input->is_ajax_request()){redirect('404');}
		if(!$this->ion_auth->logged_in()){return false;}
		if($_POST)
		{
			$teacher_user_id=$this->input->post('user_id');
			$register_id=$this->input->post('register_id');
			$event_id=$this->input->post('event_id');
			$data['event_id']=$event_id;
			$data['register']=$this->event_model->get_event_register_detail($event_id,0,$register_id);
			$data['teacher_info']=$this->event_model->get_event_teacher_deatail($teacher_user_id);
			
			$this->load->view('event/event-shadowtech-youth',$data);
		}
	}
	
	//Function -33: shadowtech youth update
	function event_shadowtect_youth_update()
	{
		if(!$this->ion_auth->logged_in()){redirect('login');}
		$SESS_TYPE_ID=$this->session->userdata('SESS_TYPE_ID');
		$login_group=array('','1','','','','','','','','','','','12');
		if(!$login_group[$SESS_TYPE_ID]){redirect('404');}
		if($_POST)
		{
			$ere_em_event_id=$this->input->post('ere_em_event_id');
			$ere_register_id=$this->input->post('ere_register_id');
			$ere_first_name=$this->input->post('ere_first_name');
			$ere_last_name=$this->input->post('ere_last_name');
			$ere_ethnicity=$this->input->post('ere_ethnicity');
			$ere_age=$this->input->post('ere_age');
			$ere_permission_slip=$this->input->post('ere_permission_slip');
			
			$data['detail']=$this->event_model->get_event_detail($ere_em_event_id);
			
			if($ere_first_name!='' && $ere_last_name!='' && $ere_ethnicity!='' && $ere_age!='' && $ere_permission_slip!='')
			{
				$update=array(
				'ere_first_name'=>$ere_first_name,
				'ere_last_name'=>$ere_last_name,
				'ere_ethnicity'=>$ere_ethnicity,
				'ere_age'=>$ere_age,
				'ere_permission_slip'=>$ere_permission_slip
				);
				$where=array('ere_register_id'=>$ere_register_id);
				$this->event_model->update_youth_register($update,$where);
			}
			redirect('event/'.$data['detail']['em_code'].'/'.url_title($data['detail']['em_title']));
		}
	}
}
?>
