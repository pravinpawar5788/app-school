<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
$org_sessions=$this->session_m->get_values_array('mid','title',array(),'mid DESC');
//////////////////////////////////////////////////////////////////
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'year DESC') );
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$activeSession=$this->session_m->getActiveSession();
// $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));

// isset($form['month']) && !empty($form['month']) ? $month=$form['month'] : $month=$this->student_m->month;
isset($form['session']) && !empty($form['session']) ? $session_id=$form['session'] : $session_id=$activeSession->mid;
isset($form['term']) && !empty($form['term']) ? $term_id=$form['term'] : $term_id='';
isset($form['class']) && !empty($form['class']) ? $class_id=$form['class'] : $class_id='';
isset($form['section']) && !empty($form['section']) ? $section_id=$form['section'] : $section_id='';
// isset($form['class_id']) && !empty($form['class_id']) ? $class_id=$form['class_id'] : $class_id='';
// isset($form['day']) && !empty($form['day']) ? $day=$form['day'] : $day=$this->student_m->day;

$sections_filter=array('campus_id'=>$this->CAMPUSID);
if(!empty($class_id)){$sections_filter['class_id']=$class_id;}
$sections=$this->class_section_m->get_rows($sections_filter,array('orderby'=>'name ASC') );

$session=$this->session_m->get_by_primary($session_id);
$term=$this->exam_term_m->get_by_primary($term_id);
$params=array();

// $student=$this->student_m->get_by_primary($form['usr']);
$class_section='';
$std_filter=array('campus_id'=>$this->CAMPUSID,'class_id'=>$class_id,'session_id'=>$activeSession->mid,'status'=>$this->student_m->STATUS_ACTIVE);
if(!empty($section_id) && $this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['section']))>0){
	$std_filter['section_id']=$section_id;
	$class_section=$this->class_section_m->get_by_primary($section_id);
}
$students=$this->student_m->get_rows($std_filter,array('orderby'=>'section_id ASC, roll_no ASC, mid ASC'));
$class=$this->class_m->get_by_primary($class_id);
$months=$this->class_subject_test_m->get_rows(array('campus_id'=>$this->CAMPUSID,'session_id'=>$session_id),array('orderby'=>'month ASC','select'=>'month,year','distinct'=>true) );
$subjects=$this->class_subject_m->get_rows(array('campus_id'=>$this->CAMPUSID,'class_id'=>$class_id));

?>
<!-- Main content -->
<div class="content-wrapper">
<!-- Content area -->
<div class="content" >

	
	<!-- Top right menu -->
	<ul class="fab-menu fab-menu-absolute fab-menu-top-right" <?php print $this->MODAL_OPTIONS;?> data-target="#view">
		<li>
			<a class="fab-menu-btn btn bg-danger-400 btn-float rounded-round btn-icon">
				<i class="fab-icon-open icon-printer"></i></a>
		</li>
	</ul>
	<!-- /top right menu -->

	
	<!-- ------------------------------printing------------------------------------------------------- -->
	<div  id="printing-content">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles'); ?>


	<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">

	<div class="page-header" style="text-align: center">
		<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>	
	</div>
	<table>
	<thead>
	  <tr>
	    <td>
	      <!--place holder for the fixed-position header-->
	      <div class="page-header-space"></div>
	    </td>
	  </tr>
	</thead>

	<tbody>
	  <tr>
	    <td>
	      <!-- <div class="page">PAGE</div> -->
	      <!--*** CONTENT STARTS HERE ***-->
	      <div class="page" style="line-height: 1;">


			<div class="row">
				<div class="col-sm-12 grd-bg-default border-white text-center">
					<span class="text-bold">Term Result Report of Class <?php print $class->title; ?> <?php print !empty($class_section) ? '('.$class_section->name.')' : ''; ?> - <?php print ucwords($term->name)?></span>
				</div>
			</div>
			<br>
			<div class="row">			
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="font-weight-semibold text-center" width="4%">#</th>
			                <th class="font-weight-semibold text-centers" >Student</th>
			                <th class="font-weight-semibold text-centers" >Father Name</th>
			                <th class="font-weight-semibold text-center" >Roll No.</th>
			                <?php 
			                foreach($subjects as $sub){?>
			                <th class="font-weight-semibold text-center">
			                	<?php print $sub['name'];?></th>
			                <?php } ?>

			                <?php if(isset($form['p_total'])){ ?>
			                <th class="font-weight-semibold text-center">Total</th>
			                <th class="font-weight-semibold text-center">%age</th>
				            <?php } ?>
			                <?php if(isset($form['p_pos'])){ ?>
			                <th class="font-weight-semibold text-center">Position</th>
				            <?php } ?>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php 
						$i=0;

						if(isset($form['p_pos'])){
							$stds_performance=array();
							$stds_positions=array();
							$std_prrm_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session_id,'class_id'=>$class_id,'status'=>$this->student_m->STATUS_ACTIVE);
							if(!empty($section_id) ){
								$std_prrm_filter['section_id']=$section_id;
							}
							$stds=$this->student_m->get_rows($std_prrm_filter,array('select'=>'mid,name,class_id'));
							foreach($stds as $std){
								$total_marks=$this->std_term_result_m->get_column_result('total_marks',array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$std['class_id'],'term_id'=>$term_id,'student_id'=>$std['mid']));
					            $obt_marks=$this->std_term_result_m->get_column_result('obt_marks',array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$std['class_id'],'term_id'=>$term_id,'student_id'=>$std['mid']));    
					            $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100,2);}
					            $stds_performance[$std['mid']]=$std_result;
							}
							// print_r($stds_performance);
				            arsort($stds_performance,SORT_NUMERIC);				            
					        //calculate positions
					        $last_position=0;
					        $last_marks=-2;
					        foreach($stds_performance as $p_sid=>$p_mrks){
					        	if($p_mrks !=$last_marks){$last_position++;}
				        		$stds_positions[$p_sid]=$last_position;
					        	$last_marks=$p_mrks;
					        }
							// print '<hr>';
					        // print_r($stds_positions);
							// print_r($stds_performance);
				   			// print '<hr>';
							// print_r(array_keys($stds_performance));
							// print_r($students);
						}
						foreach($students as $row){

			                $grand_total_obt=0;
			                $grand_total_marks=0;
			                $percentage=0;
							$position=0;

							if(isset($form['p_pos'])){
					            // $ei=0;
					            // foreach($stds_performance as $key => $value){
					            // 	if($key==$row['mid']){break;}
					            // 	$ei++;
					            // 	// $position++;
					            // }
					            // $position=count($stds_performance)-$ei;
					            $position=$stds_positions[$row['mid']];
					            // $position=(array_search(strval($row['mid']),array_keys($stds_performance)))+1;
				            }
						?>
				            <tr>
				            	<td class="text-center">
				            		<?php print ++$i;?></td>
				                <td><?php print ucwords(strtolower($row['name']));?></td>
				                <td><?php print ucwords(strtolower($row['father_name']));?></td>
				                <td><?php print $row['roll_no'];?></td>

				                <?php 

					        	$sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$row['class_id'],'term_id'=>$term_id,'student_id'=>$row['mid']);
				                foreach($subjects as $sub){

					        		$sub_filter['subject_id']=$sub['mid'];
					        		$result=$this->std_term_result_m->get_by($sub_filter,true);
				                	?>
				                	<td class="text-center">
				                		<?php 
				                		if(!empty($result)){
						        			$subject_marks=$result->total_marks;
						        			$obt_marks=$result->obt_marks;
						        			$grand_total_obt+=$obt_marks;
						        			$grand_total_marks+=$subject_marks;
						        			$grade=$this->std_result_m->get_grade($result->total_marks,$result->obt_marks);
						        			$status=$result->status;
						        			if(strtolower($result->status)=='fail'){
						        				$final_status="<span class=''>Fail</span>";
						        				$grades_final_status="<span class=''><strong> Failed </strong></span>";
						        			}
						        			if($obt_marks<=0 && $subject_marks<=0){
						        				print "pending";
						        			}else{
							        			print "$obt_marks/$subject_marks";						        				
						        			}
						        		}else{
						        			print '-';
						        		}
						        		?>
					                </td>
				                <?php }

				                if(isset($form['p_total'])){
				                	if($grand_total_obt>0){
					        			$percentage=round(($grand_total_obt/$grand_total_marks)*100,2);			                		
				                	}
				                	?> 
					                <td class="text-center">
						                	<?php print $grand_total_obt.'/'.$grand_total_marks;?>
					                </td>
					                <td class="text-center">
						                	<?php print $percentage;?>%
					                </td>

								<?php
								}								
				                ?>

				                <?php if(isset($form['p_pos'])){ ?>
					                <td class="text-center"><?php print get_ordinal_symbol($position);?></td>
					            <?php } ?>
				            </tr>
				        <?php 
				        } ?>
			        </tbody>
			    </table>
				<br>
			</div>


	      </div>

	      <!--*** CONTENT ENDS HERE ***-->
	    </td>
	  </tr>
	</tbody>

	<tfoot>
	  <tr>
	    <td>
	      <!--place holder for the fixed-position footer-->
	      <div class="page-footer-space"></div>
	    </td>
	  </tr>
	</tfoot>
	</table>	
	<div class="page-footer">
	<?php print $this->config->item('app_print_code');?>4003 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
	</div>	

	</page>


	</div>
	<!-- ------------------------------/printing-------------------------------------------------------- -->
</div>
<!-- /content area -->
<!-- Footer -->
<?php
$this->load->view($LIB_VIEW_DIR.'includes/footer_inc');
?>
<!-- /footer -->
</div>
<!-- /main content --->

<!-- create voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/report';?>">
				<input type="hidden" name="rpt" value="<?php print isset($form['rpt'])?$form['rpt']:'';?>">
				<input type="hidden" name="usr" value="<?php print isset($form['usr'])?$form['usr']:'';?>">
				<input type="hidden" name="term" value="<?php print isset($form['term'])?$form['term']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_total" 
								<?php print isset($form['p_total']) ? 'checked':'';?>>
								Show Details						
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_pos" 
								<?php print isset($form['p_pos']) ? 'checked':'';?>>
								Show Class Position						
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_analytics" 
								<?php print isset($form['p_analytics']) ? 'checked':'';?>>
								Show Analytics Footer							
						</div>						
					</div>
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Configure Data Filters</legend>
					<div class="row">
						<!-- <div class="col-md-3">
							<input type="checkbox" name="alumni" 
								<?php print isset($form['alumni']) ? 'checked':'';?>>
								Only Show Alumni Students							
						</div> -->					
					</div>
					<div class="row">
						<!-- <div class="col-sm-3">
							<label class="text-muted">Gender</label>          
							<select class="form-control select" name="gender" data-fouc>
						    <option value="" />All Genders
						    <option value="male" <?php print isset($form['gender'])&&$form['gender']=='male'?'selected':'';?>/>Male
						    <option value="female" <?php print isset($form['gender'])&&$form['gender']=='female'?'selected':'';?>/>Female
							</select>
						</div>	 -->	

						<div class="col-sm-3">
							<label class="text-muted">Session</label>          
							<select class="form-control select" name="session" data-fouc>
							<option value="" />Current Session
							<?php foreach ($org_sessions as $key=>$val){?>            
								<option value="<?php print $key;?>" <?php print isset($form['session'])&&$form['session']==$key?'selected':'';?>/><?php print $val;?>
							<?php }?>
							</select>
						</div>	
						<!-- <div class="col-sm-3">
							<label class="text-muted">Month</label>          
							<select class="form-control select" name="month" data-fouc>
							<option value="" />Current Month
							<?php 
								$mnth=$this->user_m->month;
								for($m=1;$m<=12;$m++){
									?>           
									<option value="<?php print $m;?>" <?php print isset($form['month'])&&$form['month']==$m?'selected':'';?>/><?php print month_string($m);?>
									<?php 
								} 
								?>
							</select>
						</div>	 -->												
						<div class="col-sm-3">
							<label class="text-muted">Class</label>          
							<select class="form-control select" name="class" data-fouc>
							<option value="" />All Classes
							<?php foreach ($classes as $key=>$val){?>            
								<option value="<?php print $key;?>" <?php print isset($form['class'])&&$form['class']==$key?'selected':'';?>/><?php print $val;?>
							<?php }?>
							</select>
						</div>		
						<?php if(isset($form['class'])&&!empty($form['class'])&&count($class_sections)){?>
						<div class="col-sm-3">
							<label class="text-muted">Class Section</label>          
							<select class="form-control select" name="section" data-fouc>
							<option value="" />All Sections
							<?php foreach ($class_sections as $key=>$val){?>            
								<option value="<?php print $key;?>" <?php print isset($form['section'])&&$form['section']==$key?'selected':'';?>/><?php print $val;?>
							<?php }?>
							</select>
						</div>	
						<?php } ?>		
					</div>
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-printer mr-2"></i>Adjust Page/Printer Parameters</legend>
					<div class="row">
						<div class="col-md-3">
							<input type="checkbox" name="layout" value="landscape" 
								<?php print isset($form['layout']) ? 'checked':'';?>>
								Landscape Mode							
						</div>	
						<div class="col-sm-3">
							<!-- <label class="text-muted">Font Scale</label> -->
							<select class="form-control select" name="scale" data-fouc>          
						    <option value="" />Default Scale
							<?php for($s=0;$s<5;$s++){ ?>
							<option value="<?php print $s+1; ?>" <?php print isset($form['scale'])&&$form['scale']==($s+1)?'selected':'';?>> Font Scale <?php print $s+1; ?></option>
							<?php } ?>
							</select>
						</div>			
					</div>

				</div>
				<div class="modal-footer">
					<a class="btn btn-link" data-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-success"><span class="font-weight-bold"> Process</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /create voucher modal -->
