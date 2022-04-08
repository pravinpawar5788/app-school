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
$activeSession=$this->session_m->getActiveSession();

isset($form['month']) && !empty($form['month']) ? $month=$form['month'] : $month=$this->student_m->month;
isset($form['session']) && !empty($form['session']) ? $session_id=$form['session'] : $session_id=$activeSession->mid;
isset($form['class']) && !empty($form['class']) ? $class_id=$form['class'] : $class_id='';
isset($form['section']) && !empty($form['section']) ? $section_id=$form['section'] : $section_id='';

$sections_filter=array('campus_id'=>$this->CAMPUSID);
if(!empty($class_id)){$sections_filter['class_id']=$class_id;}
$sections=$this->class_section_m->get_rows($sections_filter,array('orderby'=>'name ASC') );


$session=$this->session_m->get_by_primary($session_id);
$params=array();

// $student=$this->student_m->get_by_primary($form['usr']);
$std_filter=array('campus_id'=>$this->CAMPUSID,'class_id'=>$class_id,'session_id'=>$activeSession->mid,'status'=>$this->student_m->STATUS_ACTIVE);
if(!empty($section_id) && $this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['section']))>0){$std_filter['section_id']=$section_id;}
$students=$this->student_m->get_rows($std_filter,array('orderby'=>'roll_no ASC, mid ASC'));
$class=$this->class_m->get_by_primary($class_id);
$months=$this->class_subject_test_m->get_rows(array('campus_id'=>$this->CAMPUSID,'session_id'=>$session_id),array('orderby'=>'month ASC','select'=>'month,year','distinct'=>true) );

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

	<?php foreach($students as $std){
		$student=$this->student_m->get_by_primary($std['mid']);
	?>

	<div class="page force-page-break-after">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="card card-solid editable">
					<card class="body p-5 plr-5">
						<div class="row mt-2 mb-4">
							<div class="col-md-2 col-sm-2">					
								<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="" width="150" height="170">
								
							</div>
							<div class="col-md-8 col-sm-8">
								<center>
									<span class="font-weight-bold" style="font-size: 2.2em"><?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
								</center>
								<center>
									<span class="font-weight-semibold" style="font-size: 1.9em"><?php print ucwords($this->CAMPUS->name);?>,<?php print ucwords($this->CAMPUS->address);?></span>
								</center>
								<center>
									<span class="font-weight-normal" style="font-size: 1.5em">Contact Number# <?php print ucwords($this->CAMPUS->contact_number);?></span>
								</center>
								<br>						
							</div>
							<div class="col-md-2 col-sm-2">
								<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="float-right" width="150" height="170">
							</div>
						</div>
						<br>
						<div class="row mt-3">
							<div class="col-sm-12">								
								<center>			
									<span class="font-weight-bold m-2" style="font-size: 1.8em">MONTHLY PROGRESS REPORT</span><br>
									<span class="font-weight-semibold m-2" style="font-size: 1.5em"><?php print month_string($month); ?> - <?php print strtoupper($session->title)?></span>
								</center>							
							</div>
						</div>
						<div class="row mt-3 plr-8">
							<div class="col-md-12 col-sm-12" >
								<p class="para">
									<span class="full-line">Certified that <span class="underline"><?php print empty($student->name)? ' --- ':strtoupper($student->name) ?></span></span>
									<span class="full-line">son/daughter of <span class="underline"><?php print empty($student->father_name)? ' --- ':strtoupper($student->father_name) ?></span></span>
									<span class="full-line">having student id <span class="underline"><?php print empty($student->student_id)? ' --- ':strtoupper($student->student_id) ?></span></span>
									<span class="full-line">whose date of birth is <span class="underline"><?php print empty($student->date_of_birth)? ' --- ':strtoupper($student->date_of_birth) ?></span></span>
									<span class="full-line">enrolled in class <span class="underline"><?php print empty($class->title)? ' --- ':strtoupper($class->title) ?></span></span>
									<span class="full-line">was appeared in following tests taken by institute during month of <span class="short-underline"><?php print month_string($month); ?></span>.
									<?php if(isset($form['p_pos'])){
										$position=0;
										$avg_performance='';
										$stds_performance=array();
										$stds=$this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'session_id'=>$session_id,'class_id'=>$student->class_id,'status'=>$this->student_m->STATUS_ACTIVE),array('select'=>'mid,name'));
										foreach($stds as $std){
											$total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'month'=>$month,'student_id'=>$std['mid']));
								            $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'month'=>$month,'student_id'=>$std['mid']));    
								            $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100,2);}
								            $stds_performance[$std['mid']]=$std_result;
										}								
							            asort($stds_performance,SORT_REGULAR);
							            $ei=0;
							            foreach($stds_performance as $key => $value){
							            	$ei++;
							            	// $position++;
							            	if($key==$student->mid){break;}
							            }
							            $position=count($stds_performance)-$ei+1;
							            // array_push($stds_performance, $std)
									 ?>
									Further certified that candidate has secured the <span class="short-underline"><?php print get_ordinal_symbol($position);?></span> position in the class.
									<?php } ?>
									Please see below the detailed progress report of the candidate.</p>
							</div>
						</div>
					    
					    <?php if(isset($form['p_attend'])){ ?>

					    <?php 
					    	$working_day=0;
					    	$present=0;
					    	$absent=0;
					    	$leave=0;
					    	$holiday=0;
					    	$attendance=$this->std_attendance_m->get_by(array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'student_id'=>$student->mid,'month'=>$month),true);
							$days_in_month=days_in_month($month,$session->year);
					    	if(!empty($attendance)){
					    		$working_day=$days_in_month;
					    		for ($i=0; $i < $days_in_month; $i++) {
					    			$day='d'.($i+1);
					    			if($attendance->$day==$this->std_attendance_m->LABEL_HOLIDAY){$working_day--;}
					    			switch ($attendance->$day) {
					    				case $this->std_attendance_m->LABEL_PRESENT: {$present++;}break;
					    				case $this->std_attendance_m->LABEL_LEAVE: {$leave++;}break;
					    				case $this->std_attendance_m->LABEL_HOLIDAY: {$holiday++;}break;			    				
					    				case $this->std_attendance_m->LABEL_ABSENT: {$absent++;}break;			    				
					    				// default: { $absent++;}break;
					    			}
					    		}
					    	}
					    ?>


						<div class="row mb-2 mt-2 plr-8">
							<div class="col-md-12 col-sm-12">
							<p><center><h3 class="font-weight-bold">ATTENDANCE REPORT</h3></center></p>
							<table class="table table-sm text-center table-solid">
						        <thead>
						            <tr>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Present</th>
						                <th class="font-weight-bold" style="border:1px solid black;min-width: 10%"><?php print $present; ?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Absent</th>
						                <th class="font-weight-bold" style="border:1px solid black;min-width: 10%;"><?php print $absent; ?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Leaves</th>
						                <th class="font-weight-bold" style="border:1px solid black;min-width: 10%;"><?php print $leave; ?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Holidays</th>
						                <th class="font-weight-bold" style="border:1px solid black;min-width: 10%;"><?php print $holiday; ?></th>
						            </tr>
						        </thead>
						    </table>
			    			<table  class="table table-sm text-center table-solid">
			    				<tr>
								<?php
						    		for ($i=0; $i < $days_in_month; $i++) {
						    			?>
						    			<th style="border:1px solid black;"><?php print $i+1;?></th>
						    			<?php
						    		}
						    	?>
						    	</tr><tr>
						    	<?php
							    	if(!empty($attendance)){
							    		for ($i=0; $i < $days_in_month; $i++) {
						    				$day='d'.($i+1);
						    				if(empty($attendance->$day)){
						    					?><td style="border:1px solid black;"> - </td><?php
						    				}else{ 
						    					?><td style="border:1px solid black;"><?php print strtoupper($attendance->$day);?></td><?php
								    		}
							    		}
							    	}else{
							    		for ($i=0; $i < $days_in_month; $i++) {
							    		?> <td style="border:1px solid black;"> - </td><?php
								    	}
							    	}
								?>
			    				</tr>
			    			</table>
							</div>
						</div>

						<?php }?>
						<div class="row mt-2 mb-2 plr-8">
							<div class="col-md-12 col-sm-12">
							<p><center>
								<h3 class="font-weight-bold">ACADEMIC REPORT</h3>
							</center></p>
							<table class="table table-sm text-center table-solid">
						        <thead>
						            <tr>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Sr. No.</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Date</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Test</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Total Marks</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Obt. Marks</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Grade</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Result</th>
						            </tr>
						        </thead>
						        <tbody>
						        	<?php

						        	$i=0;
						        	$final_status="<span class=''>Pass</span>";
						        	$grades_final_status="<span class=''>Passed</span>";
						        	$total_subject_marks=0;
						        	$total_obt_marks=0;
						        	$tests=$this->class_subject_test_m->get_rows(array('session_id'=>$session->mid,'month'=>$month,'class_id'=>$student->class_id,'campus_id'=>$this->CAMPUSID),array('orderby'=>'day ASC'));
						        	$sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$student->class_id,'student_id'=>$student->mid);
						        	foreach($tests as $test){
						        		$i++;
						        		$subject_marks=0;
						        		$obt_marks=0;
						        		$grade='';
						        		$status='';
						        		$sub_filter['test_id']=$test['mid'];
						        		$result=$this->std_subject_test_result_m->get_by($sub_filter,true);
						        		if(!empty($result)){
						        			$subject_marks=$result->total_marks;
						        			$obt_marks=$result->obt_marks;
						        			$grade=$this->std_result_m->get_grade($result->total_marks,$result->obt_marks);
						        			$status=$result->status;

											if(isset($form['p_undc'])){
						        				if(strtolower($result->status)=='undeclared'){continue;}
							        		}
						        			if(strtolower($result->status)=='fail'){
						        				$final_status="<span class=''>Fail</span>";
						        				$grades_final_status="<span class=''>Failed</span>";
						        			}
						        		}else{
						        			continue;
						        		}
						        		$subject=$this->class_subject_m->get_by_primary($test['subject_id']);
						        		$total_subject_marks+=$subject_marks;
						        		$total_obt_marks+=$obt_marks;
						        	?>
						            <tr>
						            	<td><?php print $i; ?></td>
						                <td><?php print  $test['date'];?></td>
						                <td><?php print  $test['title'].'('.ucwords($subject->name).')';?></td>
						                <td><?php print  $subject_marks;?></td>
						                <td><?php print  $obt_marks;?></td>
						                <td><?php print  $grade;?></td>
						                <td><?php print  ucwords($status);?></td>
						            </tr>
						        	<?php } ?>
						        </tbody>
						        <?php if($total_obt_marks>0 && $total_subject_marks>0){
						        	if(!isset($form['p_rgrd'])){
						        		//if not restricted grading the apply normal grading as per configurations from campus settings
						        		if(round(($total_obt_marks/$total_subject_marks)*100) < $this->CAMPUSSETTINGS->month_opass_percent){
						        				$final_status="<span class=''>Fail</span>";
						        				$grades_final_status="<span class=''>Failed</span>";
						        		}else{
						        				$final_status="<span class=''>Pass</span>";
						        				$grades_final_status="<span class=''>Passed</span>";

						        		}
						        	}
					        	?>
						        <tfoot>
						            <tr>
						                <th class="font-weight-bold" colspan="3" style="border:1px solid black;background-color: #B8B8AA;">Grand Total</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $total_subject_marks; ?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $total_obt_marks; ?></th>
					                	<th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $this->std_result_m->get_grade($total_subject_marks,$total_obt_marks);?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $final_status;?></th>
						            </tr>
						        </tfoot>
							    <?php } ?>
						    </table>
							<?php if($total_obt_marks>0 && $total_subject_marks>0){?>
						    <p><br>
						    	<?php 
						    		$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						    	?>
						    	<span class="font-weight-semibold"> (Marks in words)  </span>
						    	<span class="short-underline"> The candidate has <?php print strtoupper($grades_final_status);?> and obtained marks <strong><?php print strtoupper($f->format($total_obt_marks)) ?></strong></span>
						    </p>
						    <p>
						    	Grade awarded by institution <span class="short-underline"><strong><?php print strtoupper($this->std_result_m->get_grade($total_subject_marks,$total_obt_marks)); ?></strong></span>
						    </p>
							<?php } ?>

							</div>
						</div>

					    <?php if(isset($form['p_prg'])){ ?>

						<div class="row mb-2 mt-2 plr-8">
							<div class="col-md-12 col-sm-12">
							<p><center>
								<h3 class="font-weight-bold">PERSONAL DEVELOPMENT REPORT</h3>
							</center></p>
							<table class="table table-sm text-center table-solid">
						        <thead>
						            <tr>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;min-width: 30%;">Title</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;min-width: 30%;">Grade</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Parent's Signature</th>
						            </tr>
						        </thead>
						        <tbody>
						            <tr>
						            	<td class="font-weight-semibold" style="border:1px solid black;">Obedience</td>
						            	<td class="font-weight-semibold" style="border:1px solid black;"></td>
					                	<td rowspan="4" style="border:1px solid black;">______________</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold" style="border:1px solid black;">Self Discipline</td>
						            	<td class="font-weight-semibold" style="border:1px solid black;"></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold" style="border:1px solid black;">Hand Writing</td>
						            	<td class="font-weight-semibold" style="border:1px solid black;"></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold" style="border:1px solid black;">Home Work</td>
						            	<td class="font-weight-semibold" style="border:1px solid black;"></td>
						            </tr>
						        </tbody>
						    </table>
							</div>
						</div>

						<?php }?>

					    <?php if(isset($form['p_rem'])){ ?>

						<div class="row mb-2 mt-2 plr-8">
							<div class="col-md-12 col-sm-12">
								<p><h3 class="font-weight-bold">REMARKS:</h3></p>
								<p class="para">
									<hr><hr><hr>
								</p>
							</div>
						</div>

						<?php }?>

						<br><br><br><br><br>
					    <div class="row mb-2 mt-2 plr-8">
					    	<div class="col-md-6 col-sm-6">
					    		<span class=""> Dated: <span class="short-underline"><?php print date('d F Y')  ?></span></span>
					    	</div>
					    	<div class="col-md-6 col-sm-6">
					    		<span class="float-right font-weight-bold mr-2">Principal's Signature: __________________________</span>
					    	</div>
					    </div>						       

					</card>
				</div>
			</div>
		</div>
	</div>


	<?php }?>
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
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_attend" 
								<?php print isset($form['p_attend']) ? 'checked':'';?>>
								Show Attendance Report						
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_prg" 
								<?php print isset($form['p_prg']) ? 'checked':'';?>>
								Show Personal Report							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_rem" 
								<?php print isset($form['p_rem']) ? 'checked':'';?>>
								Show Remarks						
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_pos" 
								<?php print isset($form['p_pos']) ? 'checked':'';?>>
								Show Class Position						
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_rgrd" 
								<?php print isset($form['p_rgrd']) ? 'checked':'';?>>
								Show Restricted Grading							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_undc" 
								<?php print isset($form['p_undc']) ? 'checked':'';?>>
								Hide Undeclared Result							
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
						<div class="col-sm-3">
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
						</div>													
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
