			<?php
			$next=htmlentities($page_no)+1;
			$nu_section='';
			if(isset($recent_update) && count($recent_update)>0)
			{
				$new_array=array('','New post','','','','New event','','','','New explore','New explore topic','','New job','','New event gallery','New group','','','','Event');
				
				
				$updated_array=array('','has been added','has encouraged a post','has commented to a post','','has been added','','','','has been added','has been added','','has been added','','','has been added','has joined','has joined youthhub','shared a post','gallery has been updated');
				
				
				$url_array=array('','post','post','post','','event','event','event','event','explore','explore','explore','job','job','event','group','group','','post','event');
								foreach($recent_update as $update){
									$nu_section=htmlentities($update->nu_section);
									$um_name=htmlentities($update->um_name);
									$nu_section_title=htmlentities($update->nu_section_title); 
									$um_profile_picture=htmlentities($update->um_profile_picture);
									$um_code=htmlentities($update->um_code);
									$nu_section_id=htmlentities($update->nu_section_id);
									$nu_date=htmlentities($update->nu_date);
									?>
									<div class="media pro_div">
									<?php if($update->um_profile_picture!=''){ ?>  
											<img src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture).'/'.base64_encode($um_profile_picture));?>" id="picidphoto" class="img-fluid profile_logo cursor" alt="profile">
											
									<?php		 
										}else{
										 $names=explode(" ",$um_name);
										 $f_letter="";
										 $i=1;
										 foreach ($names as $n){$f_letter.=ucfirst(substr($n, 0, 1));if($i==2){break;}$i++;}
									?>
									<span  class="profile_logo profile_img_char1 float-left" alt="profile"><?=$f_letter;?></span>
									<?php } ?>
									<div class="media-body ml-2 align-self-center">
											<?php
											/*if(!isset($new_array[$nu_section])){ //array empty*/
											if($new_array[$nu_section]==""){
											?>
												<p class="mb-0"> <a href="<?=base_url()?>profile/<?=$um_code;?>" target="_blank" class="user_recent_updates"> <?=$um_name;?></a>
												<span class="text_grey1 content"><?=$updated_array[$nu_section];?>
												<a href="<?=base_url()?><?=$url_array[$nu_section];?>/<?=$nu_section_id;?>" class="user_recent_updates" target="_blank"><?=$nu_section_title;?></a></span>
												</p>
											<?php	
											}else{ ?>	
												<p class="text_grey1 mb-0"><?=$new_array[$nu_section];?> <a href="<?=base_url()?><?=$url_array[$nu_section];?>/<?=$nu_section_id;?>" class="user_recent_updates" target="_blank"><?=$nu_section_title;?></a> <?=$updated_array[$nu_section];?></p>
											<?php	
											}
											?><span class="text_grey time_recent_ago"><?=dh_time_ago($nu_date,0,1);?></span>
									</div>
									</div>
								<?php } ?>
						 <div class="w-100" id="YB_recent_updates_<?=$next;?>">
							<input type="hidden" value="<?=$next;?>" id="YB_recent_updates_more">
						 </div>
	<?php 	}else{ ?>	
				<input type="hidden" value="" id="YB_recent_updates_more">
			<?php } ?>