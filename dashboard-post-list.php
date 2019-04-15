<?php
$img_rand=rand(1,6);							 
$next=htmlentities($ps_page_no)+1; 
if(isset($post_list) && count($post_list)<1 && $ps_page_no=='1'){  ?>
<?php if(isset($profile_user_name) && $profile_user_name!=""){ ?>
	<div class="col-12 col-sm-12 text-center bg-white py-5">
        <img src="<?=YH_SOURCE_PATH;?>img/yh_2_no_post.png" width="100" class="img-fluid mb-2">
        <p class="font-weight-normal mb-2"><b class="font-weight-bold"><?=htmlentities($profile_user_name);?></b> is working on something amazing!</p>
        <p class="mb-0">Please check after some time.</p>
    </div>
<?php }else if($SESS_NAME!=""){ ?>
 	<div class="col-12 col-sm-12 py-4 bg-white text-center">
        <img src="<?=YH_SOURCE_PATH;?>img/yh_2_no_post.png" width="100" class="img-fluid mb-4">
            <p class="font-weight-bolder mb-2">Create your first Post</p>
            <p class="mb-3 small_font">Share your journey and get noticed. It's easy, quick and fun to post.</p>
			<?php
				if(isset($is_memeber) && $is_memeber=='1'){ ?>
					<button type="button" class="btn btn_card_solid YB_profile_lets_go">Let's Go</button>  
				<?php
				}else if($is_memeber=='01'){ ?>
					<button type="button" class="btn btn_card_solid YB_profile_lets_go">Let's Go</button>  
				<?php 
				}
			?>		
    </div>
<?php }  }
 if(isset($post_list) && count($post_list)>0){
					  $rand_img=0;
					  $rand_like=0;
					  foreach($post_list as $post){
						$pm_post_id=htmlentities($post->pm_post_id);
						$um_profile_picture=htmlentities($post->um_profile_picture);
						$um_name=htmlentities($post->um_name);
						$um_code=htmlentities($post->um_code);
						$pm_created_on=htmlentities($post->pm_created_on);
						$pm_description=htmlentities($post->pm_description);
						$pm_total_like=htmlentities($post->pm_total_like);
						$pm_total_comment=htmlentities($post->pm_total_comment);
					  ?>
                <div class="posts mb-4" id="YB_post_<?=$pm_post_id;?>">
                    <div class="padding_head2"> 
				<?php
				
				if($post->pm_type=='1' || $post->pm_type=='2' || $post->pm_type=='3')
				{
					$share_exist=$this->dashboard_model->get_share_exist($post->pm_post_id);
					if($share_exist->num_rows() > 0){
					$shared_post_id=htmlentities($share_exist->row()->pus_pm_post_id);
					$shared_user_data=$this->dashboard_model->get_shared_user_data($shared_post_id);	
					if(count($shared_user_data) > 0){
						$sha=1;
						foreach($shared_user_data as $shared){
							if($sha=='1'){
								$last_title=htmlentities($shared->pus_share_title);
								$last_shared_by_pic=htmlentities($shared->shared_by_pic);
								$last_shared_by_name=htmlentities($shared->shared_by_name);
							}else{
								break;
							}								
						$sha++;		
						}								
					if($last_shared_by_pic!=''){ ?>
						<img src="<?=base_url('preview/36/'.md5(IMGSKEY.$last_shared_by_pic).'/'.base64_encode($last_shared_by_pic));?>" class="profile_logo  float-left mr-2" alt="profile">
					 <?php }else{
						 $names=explode(" ",$last_shared_by_name);
						 $f_letter="";
						 $i=1;
						 foreach ($names as $n){
							$f_letter.=ucfirst(substr($n, 0, 1));
							if($i==2){
								break;
							}
						$i++;			
						 }
					 ?>
					  <span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$f_letter;?></span>
					 <?php } ?>
                        <p class="mb-0 title_text d-inline ">
						<?php 
						$sh_display_count=1;
						$sh_n=1;
					$shared_name_arr=array();
					foreach($shared_user_data as $shared1){
							$shared_by_name=htmlentities($shared1->shared_by_name);
							$shared_by_code=htmlentities($shared1->shared_by_code);
							
							$sh_total_names=count($shared_user_data)-1; //included owner also
							if($shared1->pus_type=='1'){
								$post_own_name=htmlentities($shared1->shared_by_name);
								$post_created=htmlentities($shared1->shared_date);
								$post_owner_um_code=htmlentities($shared1->shared_by_code);
							}else{
								$shared_name_arr[]=$shared_by_name;	
							}
						?>
							<a href="<?=base_url();?>profile/<?=$shared_by_code;?>" class="db_profile" target="_blank">
								<?php
								if($sh_n==$sh_display_count){ ?>
									<?=$shared_by_name;?>
								<?php	
								}
								?>
							</a>
					<?php
					$sh_n++;
					} //loop End
					if($sh_total_names>$sh_display_count){
					  echo '<span class="text_grey font-weight-normal normal_font"> and </span>'; ?>
					<span data-toggle="tooltip" data-html="true" data-original-title="<?php 
					$shared_totel=count($shared_name_arr);
						$s=1; 
						foreach($shared_name_arr as $name){ 
							if($s!='1'){ 
								if($s <=7){
									echo $name . "<br>";
								}else{
									$shared_count_still=$shared_totel-7;
									echo "and ".$shared_count_still ."  more...";
									break;
								}	
							} 
						$s++;
						}
					?>" data-placement="top" class="content">
					<a href="#" data-toggle="modal" data-target=".YB_shared_members_model" class="YB_shared_members text-dark font-weight-normal" data-pid=<?=$shared_post_id;?>> 
					<?='  ' .$sh_total_names-$sh_display_count?> Others
					</a> 
					</span>
					<?php } ?>	
					<span class="text_grey font-weight-normal normal_font">shared a post</span>	
                        </p>
						<p class="mb-0 text_grey xs_font"> Posted by <a href="<?=base_url();?>profile/<?=$post_owner_um_code;?>" class="text-dark db_profile" target="_blank"> <?php echo " " . $post_own_name; ?>  </a> on <?php echo " " . strtolower(date("d M Y, g:i a",strtotime($post_created)));?></p>
						<?php
						if($last_title!=""){
							$share_title=$last_title;	  
							if(preg_match(REGEX_URL,$share_title,$url)){
								$share_title=preg_replace(REGEX_URL,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ",$share_title);
							} ?> 
							<p class="text_darkgrey post_desc mb-3 word_break">
							<?=nl2br($share_title);?>
							</p>
							<hr class="my-0">
							<?php 
						}	
					}	
				}else{
					
					 if($post->um_profile_picture!=''){ ?>
						<img src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture).'/'.base64_encode($um_profile_picture));?>" class="profile_logo float-left mr-2" alt="profile">
					 <?php }else{
						 $names=explode(" ",$um_name);
						 $f_letter="";
						 $i=1;
						 foreach ($names as $n){
							$f_letter.=ucfirst(substr($n, 0, 1));
							if($i==2){
								break;
							}
						$i++;			
						 }
					 ?>
					  <span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$f_letter;?></span>
					 <?php } ?>
                        <p class="mb-0 title_text d-inline">
						<a href="<?=base_url();?>profile/<?=$um_code;?>" class="db_profile" target="_blank"><?=$um_name;?></a>
                        </p>								
					<?php } ?>
                        <div class="dropdown drp_dwn_post float-right">
                           <button type="button" class="btn dropdown-toggle btn_dropdwn" data-toggle="dropdown">  
							   <i class="fas fa-chevron-down downarw"></i>
                           </button>
						<?php 
						/**Get Logged User If Favorite/Unfavorite The Post**/
						$favorite_data=$this->dashboard_model->get_feedback_details(5,$post->pm_post_id,$SESS_USER_ID);
						$pfe_type=htmlentities($favorite_data->row()->pfe_type);
						$pfe_active=htmlentities($favorite_data->row()->pfe_active);
						
						if($favorite_data->num_rows() > 0){
							$feedback_type=$pfe_type;
						    $feedback_status=$pfe_active;
						}else{
							$feedback_type='';
							$feedback_status='';
						}
						?>
                           <div class="dropdown-menu drp_menu dropdown-menu-left">
							<?php
							if($SESS_USER_ID==$post->pm_um_user_id){ ?>
								<a  href="javascript:void(0)" class="dropdown-item YB_post_favorite" data-pid="<?=$pm_post_id;?>"  
								<?php if($feedback_status==1) { ?> data-val='0'<?php }else if ($feedback_status==0) { ?> data-val='1' <?php } ?> > <?php if($feedback_status==1) { ?> Unfavorite<?php } else { ?>Favorite<?php } ?></a>
								<a href="javascript:void(0)" class="dropdown-item YB_post_edit" data-pid="<?=$pm_post_id;?>" data-toggle="modal" data-target=".YB_edit_post">Edit</a>
								<a href="javascript:void(0)" class="dropdown-item YB_post_delete" data-pid="<?=$pm_post_id;?>" data-toggle="modal" data-target=".YB_delete_post">Remove</a>
							<?php	
							}else{
							?>
                               <a class="dropdown-item YB_post_report" data-pid="<?=$pm_post_id;?>"  href="javascript:void(0)" data-toggle="modal" data-target=".YB_report_post">Report</a>
							  <a href="javascript:void(0)" class="dropdown-item YB_post_favorite" data-pid="<?=$pm_post_id;?>" <?php if($feedback_status==1) { ?> data-val='0'<?php }else if ($feedback_status==0) { ?> data-val='1' <?php }?>><?php if($feedback_status==1) { ?> Unfavorite<?php } else { ?>Favorite<?php } ?></a>
							 <?php
								if($SESS_TYPE_ID=='1'){ ?>
								<a href="javascript:void(0)" class="dropdown-item YB_post_delete" data-pid="<?=$pm_post_id;?>" data-toggle="modal" data-target=".YB_delete_post">Remove</a>
							<?php 								
								}
							}
							?>							 
                           </div>
                           <i class="fa fa-bookmark text_theme pt-2 lg_fot float-left YB_favorite_icon_<?=$pm_post_id;?>" style="<?php if($feedback_type=='5' && $feedback_status=='1'){echo 'display:block';}else{echo 'display:none';}?>"></i>
                        </div> 
						<?php 
					if($share_exist->num_rows() > 0){
						//share is there!
					}else{ ?>
							<p class="time_post text_grey mb-0"><?=strtolower(date("d M Y, g:i a",strtotime($pm_created_on)));?></p>
			<?php   }
				}		?>
					
					 <?php 
					if($post->pm_type=='4') //event_post
					{ 
						?>
						<div class="post_type"> <?php
						$event_post_list=$this->dashboard_model->get_event_post_list($post->pm_type_id);
							$um_name_e=htmlentities($event_post_list->row()->um_name);
							$um_code_e=htmlentities($event_post_list->row()->um_code);
							$em_created_on=htmlentities($event_post_list->row()->em_created_on);
							$em_code=htmlentities($event_post_list->row()->em_code);
							$em_logo=htmlentities($event_post_list->row()->em_logo);
							$em_title=htmlentities($event_post_list->row()->em_title);
							$em_start_date=htmlentities($event_post_list->row()->em_start_date);
							$em_start_time=htmlentities($event_post_list->row()->em_start_time);
							$em_end_time=htmlentities($event_post_list->row()->em_end_time);
							$re_name=htmlentities($event_post_list->row()->re_name);
							$ci_name=htmlentities($event_post_list->row()->ci_name);
							$em_detail=htmlentities($event_post_list->row()->em_detail);
							$em_address=htmlentities($event_post_list->row()->em_address);
							$em_contact_no=htmlentities($event_post_list->row()->em_contact_no);
						
						if(isset($event_post_list) && $event_post_list->num_rows() >0){ ?>
						
						<?php if($event_post_list->row()->um_profile_picture!=''){ ?>
						<img class="img-fluid profile_logo float-left mr-2" src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture).'/'.base64_encode($um_profile_picture));?>>" alt="profile">
					 <?php }else{
						 $names=explode(" ",$um_name_e);
						 $f_letter="";
						 $i=1;
						 foreach($names as $n){
							$f_letter.=ucfirst(substr($n, 0, 1));
							if($i==2){
								break;
							}
						$i++;			
						 }
					 ?>
					  <span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$f_letter;?></span>
					 <?php } ?>
							<p class="mb-0 title_text"><a href="<?=base_url()?>profile/<?=$um_code_e;?>" class="db_profile"><?=$um_name_e;?></a> posted a new Event</p>
							<p class="time_post text_grey mb-0">
							<?php 
								if($event_post_list->row()->em_created_on!='0000-00-00 00:00:00'){
								echo dh_time_ago($em_created_on,0,1);}
							?>
							</p>
							<div class="col-12 YH_5 p-0">
							  <div class="events_view bg-white my-4">
							 
								<a href="<?=base_url();?>event/<?=$em_code;?>" target="_blank" class="hover_state">
								<div class="row m-0">
								
								  <div class="col-12 col-sm-4 col-md-4 col-lg-4 p-0 event_img">
									<?php
									if($event_post_list->row()->em_logo!="")
									{ ?>
										<img src="<?=base_url('preview/24/'.md5(IMGSKEY.$em_logo).'/'.base64_encode($em_logo));?>" class="img_events_profile events_view" href="event_info.php">
									<?php	
									}
									?>
								  </div>
								  <div class="col-12 col-sm-8 col-md-8 col-lg-8 p-0 border_light">
									<div class="row m-0 border_bottom">
									  <div class="col-12 col-sm-7 col-md-7 col-lg-7 event_heading_pad">
										<p class="mb-0 event_heading font-weight-bold"><?=$em_title;?></p>
									  </div>
									</div>
									
									<div class="row m-0 mt-2 normal_font">
									  <div class="col-9 col-sm-9 col-md-9 col-lg-9">
										<p class="font-weight-bold mb-1 date_time font-weight-bold fixed_font">
										<?=date("d M Y",strtotime($em_start_date)); ?>
										(<?=date("g:ia", strtotime($em_start_time)); ?> - <?= date("g:ia", strtotime($em_end_time)); ?> )</p>
									  </div>
									<div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-0">
										<p class="text-secondary mb-1 event_content"><?=$re_name;?> - <?=$ci_name;?></p>
										<ul class="event_items mt-2 mb-0">
											<li><?=strip_tags($em_detail);?></li>
											<li><?=$em_address;?></li>
											<?php if($event_post_list->row()->em_contact_no!=''){?><li><?=str_replace(' ','',$em_contact_no);?></li><?php } ?>
										</ul>
									</div>
									  <div class="col-12 text-right">
										<h4 class="font-weight-bold mb-0 line_height text_theme event_s_date"><?=date('M',strtotime($em_start_date));?></h4>
										<h1 class="mb-0 font-weight-normal event_b_date"><?=date('d',strtotime($em_start_date));?></h1>
									  </div>
									</div>
								  </div>
								</div>
								</a>
							  </div>
							</div> 
						<?php 	  
						}
						?> </div> <?php
					}else if($post->pm_type=='5'){ //Job_post
						?> 
						<div class="post_type"> <?php
						$job_post_list=$this->dashboard_model->get_job_post_list($post->pm_type_id);
							$um_name=htmlentities($job_post_list->row()->um_name);	
							$um_profile_picture_j=htmlentities($job_post_list->row()->um_profile_picture);	
							$um_code=htmlentities($job_post_list->row()->um_code);	
							$jm_created_on=htmlentities($job_post_list->row()->jm_created_on);	
							$jm_code=htmlentities($job_post_list->row()->jm_code);	
							$jm_title=htmlentities($job_post_list->row()->jm_title);	
							$jt_name=htmlentities($job_post_list->row()->jt_name);	
							$jm_full_description=htmlentities($job_post_list->row()->jm_full_description);	
							$re_name_j=htmlentities($job_post_list->row()->re_name);	
						
						if(isset($job_post_list) && $job_post_list->num_rows() > 0){
						 ?> 
						 
					  <?php if($job_post_list->row()->um_profile_picture!=''){ ?>
						<img class="img-fluid profile_logo float-left mr-2" src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture_j).'/'.base64_encode($um_profile_picture_j));?>" alt="profile">
					 <?php }else{
						 $names=explode(" ",$um_name);
						 $f_letter="";
						 $i=1;
						 foreach($names as $n){
							$f_letter.=ucfirst(substr($n, 0, 1));
							if($i==2){
								break;
							}
						$i++;			
						 }
					 ?>
					  <span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$f_letter;?></span>
					 <?php } ?>
					 
					  <p class="mb-0 title_text"><a href="<?=base_url()?>profile/<?=$um_code;?>" class="db_profile"> <?=$um_name;?></a> posted a new job </p>
					  <p class="time_post text_grey mb-0"> 
						<?php 
							if($job_post_list->row()->jm_created_on!='0000-00-00 00:00:00'){
								echo dh_time_ago($jm_created_on,0,1);								
							}
						?>
					 </p>
						<div class="col-12 col-sm-12 p-0 my-3 YH_4 border">
						<a href="<?=base_url()?>job/<?=$jm_code;?>" target="_blank" class="text-dark">
						    <div class="list_jobs">
							    <div class="row m-0 border_bottom">
								  <div class="col-12 col-sm-12 col-md-9 col-lg-9 p-0 text-center text-md-left">
									  <h6 class="mb-0 text-truncate job_title padding_head"><?=$jm_title;?>
									  </h6>
								  </div>
								  <div class="col-12 col-sm-12 col-md-3 col-lg-3 pt-md-3 pt-sm-1 text-center">
									<h6 class="job_time text-right"><?=$jt_name;?></h6>
								  </div>
							    </div>
							    <div class="row m-0 padding_head pt-3">
							    	<div class="col-12 col-sm-8 col-md-8 col-lg-8 mb-0 job_info p-0">
									<p class="text_grey1">
										<?=$jm_full_description;?>
									</p>
								    </div>
								    <div class="col-12 col-sm-4 col-md-4 col-lg-4 pl-0 text-right">
									  <p class="font-weight-bold mb-2 job_place"><?=$re_name_j;?></p>
								    </div>
								    
							     </div>
						    </div>
						</a>
						</div>
					<?php } ?>
				</div>
						<?php 	 
					}else if($post->pm_type=='6'){ //Explore_post 
					?>
					<div class="post_type">
						<?php
					$explore_post_list=$this->dashboard_model->get_explore_post_list($post->pm_type_id);
						$um_name_ex=htmlentities($explore_post_list->row()->um_name);
						$um_profile_picture_ex=$explore_post_list->row()->um_profile_picture;
						$um_code_ex=htmlentities($explore_post_list->row()->um_code);
						$xm_code=htmlentities($explore_post_list->row()->xm_code);
						$xc_content=htmlentities($explore_post_list->row()->xc_content);
						$xm_title=htmlentities($explore_post_list->row()->xm_title);
						$xm_description=htmlentities($explore_post_list->row()->xm_description);
						$xm_logo=htmlentities($explore_post_list->row()->xm_logo);
						
					if(isset($explore_post_list) && $explore_post_list->num_rows() > 0)
					{
					?>
					 <?php if($explore_post_list->row()->um_profile_picture!=''){ ?>
						<img class="img-fluid profile_logo float-left mr-2" src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture_ex).'/'.base64_encode($um_profile_picture_ex));?>" alt="profile">
					 <?php }else{
						 $names=explode(" ",$um_name_ex);
						 $f_letter="";
						 $i=1;
						 foreach($names as $n){
							$f_letter.=ucfirst(substr($n, 0, 1));
							if($i==2){
								break;
							}
						$i++;			
						 } ?>
					  <span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$f_letter;?></span>
					 <?php } ?>
					 <p class="mb-0 title_text"><a href="<?=base_url()?>profile/<?=$um_code_ex;?>" class="db_profile"> <?=$um_name_ex;?></a> posted a new explore </p>
					 <div class="YH_6 px-0">
						<a href="<?=base_url()?>explore/<?=$xm_code;?>" target="_blank">
						    <div class="connection_profile my-2 border_box p-3">
							<div class="tab_image">
							<?php if($explore_post_list->row()->xm_logo!=""){ ?>
								<img src="<?=base_url('preview/29/'.md5(IMGSKEY.$xm_logo).'/'.base64_encode($xm_logo));?>" class="img-fluid res-img" />
							<?php }else if($explore_post_list->row()->xc_type=='1'){ ?>
								<img src="<?=base_url('preview/26/'.md5(IMGSKEY.$xc_content).'/'.base64_encode($xc_content));?>" alt="explore" class="img-fluid res-img">
							<?php } ?>
							</div>
							<div class="text-center">
							  <h5 class="mt-3 head-line">
								<p data-toggle="tooltip" data-placement="top" class="text-dark" title="" data-original-title="<?=$xm_title;?>"><?=$xm_title;?>
								</p>
							  </h5>
							  <small class="grade"></small> 
							  <p class="grid_description text-secondary"><?=$xm_description;?></p>
							</div>
						    </div>
						</a>
						</div>
						
				<?php   } ?>
			</div>
						<?php 						
					}else{ ?>
                        <p class="text_darkgrey post_desc YB_post_description mb-3 word_break">
						<?php
						if(preg_match(REGEX_URL,$pm_description,$url))
						{
							$pm_description=preg_replace(REGEX_URL,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ",$pm_description);
						} ?>
							<?=nl2br($pm_description);?>
						<?php
						?>
						</p>
						<?php
						if($post->pm_tags==1){
							$tag_list=$this->dashboard_model->get_tag_list($post->pm_post_id);
							if(count($tag_list) > 0){ ?> 
							<p class="mb-1">
							<?php
								foreach($tag_list as $tag){
									 ?>
									<a href="#" class="text_theme_2 d-none"><?="#". str_replace("#","",htmlentities($tag->tg_name));?></a>
									<span class="text_theme_2 d-inline-block mb-0 word_break"><?="#". str_replace("#","",htmlentities($tag->tg_name));?></span>
									<?php
								} ?>
							</p>
							<?php } 	
						}
						?>
						<!-- BEGIN : Gallery -->
						<?php
						$gallery_list=$this->dashboard_model->get_gallery_list($post->pm_post_id);
						$gallery_list_count=count($gallery_list); 
						$pg_layout=$gallery_list_count;
						if($gallery_list_count>6){$pg_layout=rand(3,6);}
						
						if($gallery_list_count>0)
						{
							$pg_visible=TRUE; $pg_no=1; $pg_more="";
							?>
							<ul class="flexbin flexbin-margin mb-3 gallery gal_<?=$pg_layout;?> p-0">
							
							
							
								<?php
								foreach($gallery_list as $gallery)
								{
									$pgal_type=htmlentities($gallery->pgal_type); $pgal_image=htmlentities($gallery->pgal_image);
									$pgal_video=htmlentities($gallery->pgal_video); $pgal_video_poster=htmlentities($gallery->pgal_video_poster);
									if($pg_no==$pg_layout && $gallery_list_count>$pg_layout){
										$pg_more="<div class='img_more'><h1 class='text-center'>+".($gallery_list_count-$pg_no)."</h1></div>";
									}
									if($pgal_type=='1'){ ?>
										<a class="" href="<?=base_url('preview/1/'.md5(IMGSKEY.$pgal_image).'/'.base64_encode($pgal_image));?>"><?=$pg_more;?><?php if($pg_visible){?><img src="<?=base_url('preview/1/'.md5(IMGSKEY.$pgal_image).'/'.base64_encode($pgal_image));?>" alt="PostImg"><?php } ?></a>	
									<?php	
									}else if($pgal_type=='2'){
										?><a class="" href="<?=base64_encode($pgal_image);?>"><?=$pg_more;?><?php if($pg_visible){?><img src="<?=base64_encode($pgal_image);?>" alt="PostImg"><?php } ?></a><?php
									}else if($pgal_type=='3'){ //direct video
										?><a class="mfp-iframe" href="<?=base_url('preview/1/'.md5(IMGSKEY.$pgal_video).'/'.base64_encode($pgal_video));?>"><?=$pg_more;?><?php if($pg_visible){?><img src="<?=base_url('preview/3/'.md5(IMGSKEY.$pgal_video_poster).'/'.base64_encode($pgal_video_poster));?>"/><i class="far fa-play-circle lg_font play_icn_post_b"></i> <?php }?></a><?php
									}else if($pgal_type=='4'){ //link video
										?><a class="mfp-iframe" href="<?=base64_encode($pgal_video);?>"><?=$pg_more;?><?php if($pg_visible){ ?><img src="<?=base64_encode($pgal_video_poster);?>"/><i class="far fa-play-circle lg_font play_icn_post_b"></i> <?php } ?></a><?php	
									}
									if($pg_no==$pg_layout){$pg_visible=FALSE;}
									$pgal_type=''; $pgal_image=''; $pgal_video=''; $pgal_video_poster=''; $pg_no++; $pg_more="";
								}
								?>
					
							</ul>
							<?php
						}
					} //PostEnd
					?> 
					
					<!-- END : Gallery -->	
					
					<div class="actions_count">
					   <input type="hidden" id="YB_like_count_db" value="<?=$pm_total_like;?>">
						   <?php
						   if($post->pm_total_like >=1)
						   {
						   ?>
							<p class="YB_like_count_hide">
								<a href="#" class="encourage_name_list YB_like_count YB_post_encouragers" data-toggle="modal" data-pid="<?=$pm_post_id;?>" data-target=".YB_show_encouragers_model"><?=$pm_total_like;?>
								<?php if($post->pm_total_like==1) { ?> People <?php } else if ($post->pm_total_like > 1) { ?> Peoples <?php } ?></a> encouraged this.
							</p>
						   <?php
						   }else{ /**like-to update with script**/ ?>
							  <p style="display:none" class="YB_like_count_hide" >
								<a href="#" class="encourage_name_list YB_like_count YB_post_encouragers" data-toggle="modal" data-pid="<?=$pm_post_id;?>" data-target=".YB_show_encouragers_model">
									<?=$pm_total_like;  if($post->pm_total_like==1) {?> people <?php } else if ($post->pm_total_like > 1) { ?> Peoples <?php }?>
								</a> encouraged this.
							 </p> 
							<?php   
						   }?>      
						   <input type="hidden" id="YB_comment_count_db" value="<?=$pm_total_comment;?>">
						   <?php 
						   if($post->pm_total_comment > 0){
						   ?>
							<a class="YB_comment_count_hide">
							   <p class="YB_comment_count"><?=$pm_total_comment; if($post->pm_total_comment==1) { ?> Comment <?php } else { ?> Comments <?php } ?>
							   </p>
							</a>
						   <?php
						   }else{/**comments-to update with script **/ ?>
						    <a style="display:none" class="YB_comment_count_hide">
							   <p class="YB_comment_count"><?=$pm_total_comment; if($post->pm_total_comment==1) { ?> Comment <?php } else { ?> Comments <?php } ?></p>
						    </a>			
							<?php		
						   }
						   ?>
                    </div>

					<?php /** Show Encourager's Name in Script**/ ?>
				<p class="encourage_name_list YB_name_list_script" style="display:none"></p>	
			
				<?php				
					/**Show The Encourager's Name**/
				if($rand_like % 5!= 0){
					$encouragers_name_list=$this->dashboard_model->get_post_encouragers($post->pm_post_id);
					$total_names=count($encouragers_name_list);
					$display_count=4;
					if($total_names > 0 ){ ?>
						<p class="encourage_name_list YB_name_list" id="sdsd">
						<?php $n=1;
						foreach($encouragers_name_list as $names){
							$um_name_en=htmlentities($names->um_name);

						?>
								<?php
								if($n<=$display_count){ ?>								
									<a href="<?=base_url();?>profile/<?=$names->um_code;?>" target="_blank" class="text_theme fixed_font"> 
									<?php
										if($n==$display_count || $total_names==1){ ?>
												<?=$um_name_en;?>
										<?php	
										}else if($total_names==2){
											if($n==2){ ?>
												<?=$um_name_en;?>
											<?php	
											}else{ ?>
												<?=$um_name_en .",";?>
											<?php
											}
										}else if($total_names==3){
											if($n==3){ ?>
												<?=$um_name_en;?>
											<?php	
											}else{ ?>
												<?=$um_name_en .",";?>
											<?php	
											}
										}else{ ?>
											<?=$um_name_en .",";?>
											<?php 
										}
									?>
									</a> 
									<?php 
								}else{
									break;
								}
								?>
							<?php
						$n++;
						}
						if($total_names>$display_count){ ?>
							and 
							<a href="#" class="encourage_name_list YB_like_count YB_post_encouragers text_theme fixed_font" data-toggle="modal" data-pid="<?=$pm_post_id;?>" data-target=".YB_show_encouragers_model">
							<?=$total_names-$display_count;?> other's</a> has encouraged this.<?php
						}else{ ?> has encouraged this.<?php
						} 
					} ?>
					</p>
				<?php }
					?>	
					<div class="action">
						<?php 
						/**Get Logged User If Liked/Disliked The Post**/
						$like_data =$this->dashboard_model->get_feedback_details(1,$post->pm_post_id,$SESS_USER_ID);
						if($like_data->num_rows() > 0){
							$feedback_type=$like_data->row()->pfe_type;
							$feedback_status=$like_data->row()->pfe_active;
						}else{
							$feedback_type='';
							$feedback_status='';
						} 
						?>
						
					   <div class="YB_like" style="<?php if($feedback_type==1 && $feedback_status==1) { echo "display:none"; }else{ echo "display:inline-block"; }?>">
						 <a href="javascript:void(0)" class="YB_post_like"  data-pid="<?=$pm_post_id;?>" data-val='1'><img src="<?=YH_SOURCE_PATH;?>img/yh_2_un_like.svg" class="img-fluid pr-1" alt="Encourage">Encourage </a>
					   </div>

					   <div class="YB_un_like" style="<?php if($feedback_type==1 && $feedback_status==1) { echo "display:inline-block"; } else { echo "display:none"; }?>">
						<a href="javascript:void(0)" class="YB_un_like_post" data-pid="<?=$pm_post_id;?>" data-val='0'><img src="<?=YH_SOURCE_PATH;?>img/yh_2_like.svg" class="img-fluid pr-1" alt="Encouraged">Encouraged</a>
					   </div>
					   
					   <a href="#" class="mx-3 YB_post_comment_add" data-pid="<?=$pm_post_id;?>" data-count="<?=$pm_total_comment;?>"> <img src="<?=YH_SOURCE_PATH;?>img/yh_2_comment.png" class="img-fluid px-1" alt="Comment">Comment
					   </a>
					   <?php 
					   if($SESS_USER_ID==$post->pm_um_user_id){
							$class='d-none';
					   }else{
						    $class='';
					   } //share option will be hide for post owner 
					   if($post->pm_gma_group_id=='0'){ //group post cant be shared
					   ?>
						<a href="#" class="mx-2 share_sec_post YB_post_share <?=$class;?>" data-pid="<?=$pm_post_id;?>" data-toggle="modal" data-target=".YB_post_share_modal">
						<i class="fas fa-share lg_font text_grey pr-1"></i>Share</a>
					   <?php } ?>
					</div>
					</div>
					 <?php $random_comment_show=mt_rand(0,1); ?>
                    <div class="comment_div padding_head2 YB_comment_part" style="<?php if($post->pm_total_comment > 0 || $random_comment_show==1){ echo "display:block"; }else{ echo "display:none"; } ?>">
						<?php  
						if($SESS_PROFILE_PICTURE!=''){ ?>
						   <img src="<?=base_url('preview/36/'.md5(IMGSKEY.$SESS_PROFILE_PICTURE).'/'.base64_encode($SESS_PROFILE_PICTURE));?>" class="profile_logo float-left mr-2" alt="profile">
						  
						<?php 
						}else{
							 $ses_name=explode(" ",$SESS_NAME);
							 $ses_f_letter="";
							 $i=1;
							 foreach ($ses_name as $s_n) {
								$ses_f_letter.=ucfirst(substr($s_n, 0, 1));	
								if($i==2){
								 break;
								}
							 $i++;	
							 }
						 ?>    
							<span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$ses_f_letter; ?></span>
						<?php
						}
						?>
						<div class="mb-0 form-group">
							<input type="text" class="form-control YB_post_comment_box" data-pid="<?=$pm_post_id;?>" placeholder="Add a comment">
						</div>
					<ul class="m-0 p-0 comment_ul mt-2">
						<li class="YB_append_comment mt-1" style="display:none"></li>
					<?php
					$comment_list=$this->dashboard_model->get_comment_list($post->pm_post_id,0);
					$totel_comments=count($comment_list); ?>
					<?php
					if($totel_comments > 0)
					{
						$comment=1;
						foreach($comment_list as $comments)
						{
							$um_profile_picture_com=htmlentities($comments->um_profile_picture);
							$um_name_com=htmlentities($comments->um_name);
							$um_code_com=htmlentities($comments->um_code);
							$pfe_created_on=htmlentities($comments->pfe_created_on);
							$pfe_feed_id=htmlentities($comments->pfe_feed_id);
							
							
					?>	
						<li class="comment_content_div pt-2 YB_inactive_comment_<?=htmlentities($comments->pfe_feed_id);?>" style="display:block">
						<?php
						if($comments->um_profile_picture!='')
						{ ?>
						 <img  class="img-fluid profile_logo float-left mr-2" src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture_com).'/'.base64_encode($um_profile_picture_com));?>" alt="profile"> 
						
						<?php	
						}
						else
						{
						$commented_user=$um_name_com;
						 $commented_user_name=explode(" ",$commented_user);
						 $user_f_letter="";
						 $i=1;
						 foreach ($commented_user_name as $s_n)
						 {
							$user_f_letter.=ucfirst(substr($s_n, 0, 1));	
							if($i==2)
							{
							 break;
							}
						 $i++;	
						 }
					 ?>    
						<span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$user_f_letter; ?></span>
					<?php
					}
					?>
                            <div class="comment_content">
							  <p class="mb-0 cmnt_title_text"><a href="<?=base_url();?>profile/<?=$um_code_com;?>" class="db_profile" target="_blank"><?=$um_name_com;?></a><span class="float-right fixed_font text_grey1"><?php
							  if($comments->pfe_created_on!='0000-00-00 00:00:00'){
								echo dh_time_ago($pfe_created_on,0,1);								
							   }
							  ?></span>
							  </p>
							  <p class="text_darkgrey cmnt_desc mb-0"><?php 
								$comment_reg=htmlentities($comments->pfe_message); 
								if(preg_match(REGEX_URL,$comment_reg,$url)){
									$comment_reg=preg_replace(REGEX_URL,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ",$comment_reg);
								} 
								echo nl2br($comment_reg);
								?>
							  <?php
							  if($SESS_USER_ID==$comments->pfe_um_user_id){
							  ?>
								<a href="#" class="YB_post_comment_delete post_comment_delete"  data-pid="<?=$pm_post_id;?>" data-fid="<?=$pfe_feed_id;?>" data-toggle="modal" data-target=".YB_delete_comment"><i class="far fa-trash-alt pl-3 cmt_delete"></i></a>
							  <?php
							  }  
							  ?>
							  </p>
                            </div>
						</li>
					<?php
					$comment++;	
						if($comment > 5){
							break;
						}
						}
						$last_fid=$pfe_feed_id; 
					}
					?>
					<li class="YB_append_comment_more mt-2" style="display:none"></li>
					<?php
					if($totel_comments > 5){
					$balence_comments=$totel_comments-5;
					?>
					<a href="javascript:void;" style="display:block" class="pt-2 col-12 text-center text_theme fixed_font YB_post_comments_more YB_comment_<?=$last_fid;?>" data-fid="<?=$last_fid;?>" data-pid="<?=$pm_post_id;?>">View <?=$balence_comments;?> more <?php if($balence_comments==1) { ?>comment <?php } else { ?> comments<?php } ?></a>
					<?php
					}
					?>
					  </ul> 
									</div>
								
                 </div>
				  <input type="hidden" class="YB_multi_comment_<?=$pm_post_id;?>" value=""> 
				  <?php 
				 $rand_like=$rand_like+3;
				 unset($shared_name_arr); //to clear tooltip data 
					
					  } //Loop End
				  ?>
					<div id="YB_post_list_<?=$next;?>"> 
						<input type="hidden" id="YB_post_next" value="<?=$next;?>"/>
					</div>
<?php } ?>
    <!-- BEGIN : Edit Post -->
	<div class="modal fade YB_edit_post" data-backdrop="static">
			<div class="modal-dialog modal-dialog-centered modal-lg">
			  <div class="modal-content border-0">  
				<div class="modal-header padding_head">
					<h6 class="modal-title font-weight-bold">Post Description</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>			  
				<div class="modal-body p-0">
				  <div class="row m-0">
					<div class="col-12 padding_head">
					<?=form_open_multipart(base_url(),array('id'=>'edit_post_form'));?>
                      <div align="center"><textarea class="w-100 text_area_c YB_post_message"  rows="5"></textarea></div>
                        <div class="my-2 YB_edit_error"></div>
                    <?=form_close();?>
					</div>
					<div class="col-12 col-sm-12 d-flex justify-content-end mb-3">
					  <button type="button" class="btn_card_solid YB_post_edit_confirm"  data-pid="">Update</button>
					</div>
				  </div>
				</div>
			   </div>
			 </div>
         </div>
    <!-- END : Edit Post -->	
	<!-- BEGIN : Report Post -->
	<div class="modal fade YB_report_post YB_report_model" id="YB_report_model_id" data-backdrop="static">
			<div class="modal-dialog modal-dialog-centered modal-lg">
			  <div class="modal-content border-0"> 
				<div class="modal-header padding_head">
					<h6 class="modal-title font-weight-bold">Report Post</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>	
				<div class="modal-body p-0">
				  <div class="row m-0">
					<div class="col-12 padding_head">
					<?=form_open_multipart(base_url(),array('id'=>'report_post'));?>
                        <div align="center"><textarea class="w-100 text_area_c " placeholder="Write your text" rows="5" id="YB_report_message"></textarea></div>
						<div class="mt-2 YB_report_suc_error"></div>
                    <?=form_close();?>
					</div>
					<div class="col-12 col-sm-12 d-flex justify-content-end mb-3">
					  <button type="button" class="btn_card_solid YB_post_report_confirm" data-pid="">Submit</button>
					</div>
				  </div>
				</div>
			   </div>
			 </div>
         </div>
	 <!-- END : Report Post -->		
    <!-- BEGIN : Delete Post -->
	<div class="modal fade YB_delete_post" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content border-0">       
        <div class="modal-header padding_head">
         <h6 class="modal-title font-weight-bold">Delete post</h6>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-0">
          <div class="row m-0">
            <div class="col-12 padding_head">
              <p class="">Are you sure you would like to delete this post ?</p>
            </div>
            <div class="col-12 col-sm-12 d-flex justify-content-between my-3">
              <button type="button" class="btn_card_solid YB_post_delete_confirm" data-pid="">Yes</button>
              <button type="button" class="btn_card_solid_b" data-dismiss="modal">No</button>
            </div>
          </div>
        </div>
      </div>   
    </div>
    </div>
	<!-- END : Delete Post -->
	<!-- BEGIN : Hide Post -->
	<div class="modal fade YB_hide_post" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content border-0">       
        <div class="modal-header padding_head">
         <h6 class="modal-title font-weight-bold">Hide Post</h6>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-0">
          <div class="row m-0">
            <div class="col-12 padding_head">
              <p class="">If you hide this Post you will be no longer to find it anywhere. Are You Sure?</p>
            </div>
            <div class="col-12 col-sm-12 d-flex justify-content-between my-3">
              <button type="button" class="btn_card_solid YB_confirm_hide" data-pid="">Yes</button>
              <button type="button" class="btn_card_solid_b" data-dismiss="modal">No</button>
            </div>
          </div>
        </div>
      </div>   
    </div>
    </div>
	<!-- END : Hide Post -->
	<!-- BEGIN : Encouragers List On Modal -->			
	<div class="modal fade YB_show_encouragers_model" data-backdrop="static">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0">       
		  <div class="modal-header padding_head">
			<h6 class="modal-title mb-0 font-weight-bold">Encouragers
			</h6>
			<button type="button" class="close" id="YB_close" data-dismiss="modal">&times;
			</button>
		  </div>
		  <div class="modal-body bg_secondary p-0" >
			<div class="row m-1 my-3 likers_box  hide_scrl YB_show_names">
				<!-- Load Data From Script-->
			</div>
		  </div>
		</div>
	  </div> 
	</div>
	<!-- END : Encouragers List On Modal -->	
	 <!-- BEGIN : Comment Delete -->
	<div class="modal fade YB_delete_comment" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content border-0">       
        <div class="modal-header padding_head">
         <h6 class="modal-title font-weight-bold">Delete Comment</h6>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-0">
          <div class="row m-0">
            <div class="col-12 padding_head">
              <p class="">Are you sure you would like to delete this comment ?</p>
            </div>
            <div class="col-12 col-sm-12 d-flex justify-content-between my-3">
              <button type="button" class="btn_card_solid YB_comment_delete_confirm" data-pid="" data-fid="">Yes</button>
              <button type="button" class="btn_card_solid_b" data-dismiss="modal">No</button>
            </div>
          </div>
        </div>
      </div>   
    </div>
    </div>
	<!-- END : Comment -->
	<!-- BEGIN : Shared Members List On Modal -->			
	<div class="modal fade YB_shared_members_model" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border_box">       
      <div class="modal-header padding_head">
        <h6 class="modal-title mb-0 font-weight-bold">Shared Members
        </h6>
        <button type="button" class="close" id="YB_close" data-dismiss="modal">&times;
        </button>
      </div>
      <div class="modal-body bg_secondary p-0">
        <div class="row mx-0 mt-3 likers_box hide_scrl YB_shared_member">
          <!-- Load Data From Script-->	
        </div>
      </div>
    </div>
	</div> 
	</div>
	<!-- END : Shared Members List On Modal -->
	<!-- START : Post Share -->
	<div class="modal fade YB_post_share_modal post_share_modal" data-backdrop="static">
	  <div class="modal-dialog modal-dialog-centered modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header padding_head">
			<h6 class="modal-title mb-0">Share
			</h6>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  </div>
		  <div class="modal-body">
		   <textarea class="border-0 w-100 YB_post_share_title post_share_title YB_shared_content"  placeholder="Write something" rows="2"></textarea>
			<div class="YB_post_share_content YB_shared_content "> <!--Dynamic Content from Script -->
			</div>
			<div id="YB_post_share_loader"></div>
		  </div>
		</div>
	  </div>
	</div>
	<!-- END : Post Share -->