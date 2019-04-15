<?php /** BEGIN Show The Encouragers Name in Script (Remove/Add Logged User  - Like/UnLIke) **/
				$total_names=count($encouragers_name_list);
				$display_count=4;
				$SESS_USER_ID =htmlentities($this->session->userdata('SESS_USER_ID'));
				if($total_names > 0 ){
						$n=1;
						foreach($encouragers_name_list as $names){
							$um_code=htmlentities($names->um_code);
							$post_id=htmlentities($names->pfe_pm_post_id);
							$um_name=htmlentities($names->um_name);
								if($n<=$display_count){ ?>								
								<a href="<?=base_url();?>profile/<?=$um_code;?>" target="_blank" class="text_theme fixed_font">
									<?php
										if($n==$display_count || $total_names==1){
											if($names->um_user_id==$SESS_USER_ID){ ?>
												<?="You";?>
											<?php	
											}else{ ?>
												<?=$um_name;?>
											<?php
											}
										}else if($total_names==2){
											if($n==2){
												if($names->um_user_id==$SESS_USER_ID){ ?>
													<?="You";?>
												<?php 
												}else{ ?>
													<?=$um_name;?>
											<?php
												}
											}else{
												if($names->um_user_id==$SESS_USER_ID){
													echo "You" . ",";
												}else{
													echo $um_name . ",";
												}
											}
										}else if($total_names==3){
											if($n==3){
													if($names->um_user_id==$SESS_USER_ID){ ?>
														<?="You";?>
													<?php	
													}else{ ?>
														<?=$um_name;?>
													<?php	
													}											
											}else{
												if($names->um_user_id==$SESS_USER_ID){
													echo "You" . ",";
												}else{
													echo $um_name. ",";
												}
											}
										}else{
											if($names->um_user_id==$SESS_USER_ID){
												echo "You" . ",";
											}else{
												echo $um_name . ",";
											}
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
						if($total_names>$display_count){
							$others=$total_names-$display_count;
							?>
								and
							<a href="#" class="encourage_name_list YB_post_encouragers text_theme fixed_font" data-toggle="modal" data-pid="<?=$post_id;?>" data-target=".YB_show_encouragers_model"><?=$others;?> other's</a> 
							has encouraged this.
							<?php
						}else{ ?>
						 encouraged this.
						<?php
						}
				}
				/** END Show The Encouragers Name in Script (Remove/Add Logged User  - Like/UnLIke) **/
					?>