<!-- BEGIN : Jquery File Upload Plugin -->
<!-- Generic page styles -->
<link rel="stylesheet" href="<?=base_url('asset/plugins/jquery-file-upload/');?>css/style.css">
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="<?=base_url('asset/plugins/jquery-file-upload/');?>css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?=base_url('asset/plugins/jquery-file-upload/');?>css/jquery.fileupload.css">
<link rel="stylesheet" href="<?=base_url('asset/plugins/jquery-file-upload/');?>css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="<?=base_url('asset/plugins/jquery-file-upload/');?>css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?=base_url('asset/plugins/jquery-file-upload/');?>css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- END : Jquery File Upload Plugin -->
<!-- START: Add Post  -->
<?php
if(isset($profile_user_id) && $profile_user_id > 0 && isset($SESS_USER_ID)){ //On profile post add
	$data['profile_id']=$profile_user_id; /*for add post redirect*/
	$data['SESS_TYPE_ID']=$this->session->userdata('SESS_TYPE_ID');
	if($profile_user_id==$SESS_USER_ID)
	{
		$this->load->view('dashboard/dashboard-post-add',$data);
	}
}else if(isset($group_id) && isset($SESS_USER_ID)){
	$data['group_id']=htmlentities($group_id); //for add post redirect
	$data['group_code']=htmlentities($group_code); //for add post redirect
	$data['SESS_TYPE_ID']=htmlentities($this->session->userdata('SESS_TYPE_ID'));
	if($group_id > 0 && $gme_status=='1'){$this->load->view('dashboard/dashboard-post-add',$data);} //group member only can add and member should be approved
}else{
	$data['profile_id']=''; /*for add post redirect*/
	$this->load->view('dashboard/dashboard-post-add',$data);
}
?>
<!-- END: Add Post  --> 
<input type="hidden" class="YB_is_member" value="<?=htmlentities($gme_status);?>">	
<?php if($this->uri->segment(1)=='post'){if($this->uri->segment(2)!=''){$post_code=$this->uri->segment(2);}else{$post_code=0;}}else{$post_code=0;}?>
<input type="hidden" id="YB_post_view_code" value="<?=htmlentities($post_code);?>">
<!-- START: Post List -->
<div id="YB_post_list_1">
<?php
if(isset($profile_user_id) && $profile_user_id > 0 && isset($SESS_USER_ID)){ //On profile post add
	$data['ps_page_no']=1;
	$data['SESS_USER_ID']=htmlentities($SESS_USER_ID);
	$data['SESS_PROFILE_PICTURE']=htmlentities($this->session->userdata('SESS_PROFILE_PICTURE'));
	$data['SESS_NAME']=htmlentities($this->session->userdata('SESS_NAME'));
	$data['SESS_TYPE_ID']=htmlentities($SESS_TYPE_ID);
	$data['post_list']=$this->dashboard_model->get_post_list_loggedin($data['ps_page_no'],$profile_user_id,$data['SESS_USER_ID'],0,0);
	if($profile_user_id==$SESS_USER_ID){
		$data['is_memeber']="01"; //for LetsGo button
	}

	if($profile_user_id==$SESS_USER_ID)
	{
		$data['profile_user_name']='';
	}else
	{
		$get_user_name=$this->dashboard_model->get_profile_user_data($profile_user_id);
		$data['profile_user_name']=$get_user_name->um_name;	 //for no post msg on profile
	}
	$this->load->view('dashboard/dashboard-post-list',$data);
}else if(isset($group_id) && isset($SESS_USER_ID)){
	$data['ps_page_no']=1;
	$data['SESS_USER_ID']=htmlentities($SESS_USER_ID);
	$data['SESS_PROFILE_PICTURE']=htmlentities($this->session->userdata('SESS_PROFILE_PICTURE'));
	$data['SESS_NAME']=htmlentities($this->session->userdata('SESS_NAME'));
	$data['SESS_TYPE_ID']=htmlentities($SESS_TYPE_ID);
	$data['post_list']=$this->dashboard_model->get_post_list_loggedin($data['ps_page_no'],0,$data['SESS_USER_ID'],$group_id,0);
	$this->load->view('dashboard/dashboard-post-list',$data);
}else{
	$this->load->view('dashboard/dashboard-post-list',$post_list);
}
?>
</div>
<!-- END: Post List -->
<!-- BEGIN : Jquery File Upload Plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?=base_url('asset/plugins/jquery-file-upload/');?>js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="<?=base_url('asset/js/dashboard/');?>upload.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!-- END : Jquery File Upload Plugin -->