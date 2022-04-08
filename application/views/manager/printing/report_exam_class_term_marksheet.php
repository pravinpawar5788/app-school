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
// $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
isset($form['session']) && !empty($form['session']) ? $session_id=$form['session'] : $session_id=$activeSession->mid;
isset($form['class']) && !empty($form['class']) ? $class_id=$form['class'] : $class_id='';
isset($form['term']) && !empty($form['term']) ? $term_id=$form['term'] : $term_id='';
isset($form['usr']) && !empty($form['usr']) ? $user_id=$form['usr'] : $user_id='';

isset($form['section']) && !empty($form['section']) ? $section_id=$form['section'] : $section_id='';

$sections_filter=array('campus_id'=>$this->CAMPUSID);
if(!empty($class_id)){$sections_filter['class_id']=$class_id;}
$sections=$this->class_section_m->get_rows($sections_filter,array('orderby'=>'name ASC') );

// isset($form['class_id']) && !empty($form['class_id']) ? $class_id=$form['class_id'] : $class_id='';
// isset($form['day']) && !empty($form['day']) ? $day=$form['day'] : $day=$this->student_m->day;

$session=$this->session_m->get_by_primary($session_id);
$term=$this->exam_term_m->get_by_primary($term_id);
$params=array();

// $student=$this->student_m->get_by_primary($form['usr']);
$std_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$activeSession->mid);
if(!empty($user_id)&& $user_id!='cls'){
	$std_filter['mid']=$user_id;
}else{
	$std_filter['class_id']=$class_id;
	$std_filter['status']=$this->student_m->STATUS_ACTIVE;
	if(!empty($section_id) && $this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['section']))>0){$std_filter['section_id']=$section_id;}
}
$students=$this->student_m->get_rows($std_filter,array('orderby'=>'roll_no ASC, mid ASC'));
$class=$this->class_m->get_by_primary($class_id);

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

	<?php if(isset($form['p_pos'])){
		$position=0;
		$stds_performance=array();
		$stds_positions=array();


		if(!empty($user_id)&& $user_id!='cls'){
			$sstd=$this->student_m->get_by_primary($user_id);
			$class_id=$sstd->class_id;
		}

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

        arsort($stds_performance,SORT_NUMERIC);			            
        //calculate positions
        $last_position=0;
        $last_marks=-2;
        foreach($stds_performance as $p_sid=>$p_mrks){
        	if($p_mrks !=$last_marks){$last_position++;}
    		$stds_positions[$p_sid]=$last_position;
        	$last_marks=$p_mrks;
        }
        // asort($stds_performance,SORT_REGULAR);
        // print 'total Performance:'.count($stds_performance);
        // print_r($stds_performance);
	} ?>

	<?php foreach($students as $std){
		$student=$this->student_m->get_by_primary($std['mid']);

		if(!empty($user_id)&& $user_id!='cls'){
			$class_id=$student->class_id;
			$class=$this->class_m->get_by_primary($class_id);
		}
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
						<div class="row mt-2">
							<div class="col-sm-12">								
								<center>			
									<span class="font-weight-bold m-2" style="font-size: 1.8em">RESULT INTIMATION CARD</span><br>
									<span class="font-weight-semibold m-2" style="font-size: 1.5em"> <?php print strtoupper($term->name)?></span>
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
									<span class="full-line">has qualified for award of result intimation card for the term examination for duration <span class="short-underline"><?php print strtoupper($term->start_date.' - '.$term->end_date) ?></span>. Further certified that student appeared in examination as <span class="short-underline">REGULAR</span> candidate.

									<?php if(isset($form['p_pos'])){
										$position=0;
							            $position=$stds_positions[$student->mid];
							            // (array_search(strval($student->mid),array_keys($stds_positions)));
							            // $ei=0;
							            // foreach($stds_performance as $key => $value){
							            // 	if($key==$student->mid){break;}
							            // 	$ei++;
							            // 	// $position++;
							            // }
							            // // $position=count($stds_performance)-$ei;
							            // $position=array_search($student->mid,array_keys($stds_performance), true);

							            // array_push($stds_performance, $std)
									 ?>
									Candidate has secured the <span class="short-underline"><?php print get_ordinal_symbol($position);?></span> position in the <?php if(!empty($section_id)){print 'section';}else{print 'class';} ?>.
									<?php } ?>
									Please see below the detailed academic report of the candidate.</span></p>
							</div>
						</div>
					    

					    <?php if(isset($form['p_attend'])){ ?>

					    <?php 
					    	$working_day=0;
					    	$present=0;
					    	$absent=0;
					    	$leave=0;
					    	$holiday=0;
					    	$others=0;
					    	$start_day=get_day_from_date($term->start_date,'-');
					    	$start_year=get_year_from_date($term->start_date,'-');
					    	$start_month=get_month_from_date($term->start_date,'-',true);
					    	$end_day=get_day_from_date($term->end_date,'-');
					    	$end_year=get_year_from_date($term->end_date,'-');
					    	$end_month=get_month_from_date($term->end_date,'-',true);
					    	$start_month_number=(intval($start_year-1)*12)+$start_month;
					    	$end_month_number=(intval($end_year-1)*12)+$end_month;
					    	$month_difference=$end_month_number-$start_month_number;
				    		$month_counter=$start_month;
				    		$year_counter=$start_year;
					    	$current_month_number=(intval($year_counter-1)*12)+$month_counter;
				    		while ($start_month_number <= $end_month_number) {	
						    	$attendance=$this->std_attendance_m->get_by(array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'student_id'=>$student->mid,'month'=>$month_counter,'year'=>$year_counter),true);
								$days_in_month=days_in_month($month_counter,$year_counter);
						    	if(!empty($attendance)){
						    		$working_day=$days_in_month;
						    		for ($i=0; $i < $days_in_month; $i++) {
						    			$current_day=$i+1;
						    			$day='d'.($i+1);
						    			if($current_month_number==$start_month_number && $current_day < $start_day){ continue;}
						    			if($current_month_number==$end_month_number && $current_day >$end_day){ continue;}

						    			if($attendance->$day==$this->std_attendance_m->LABEL_HOLIDAY){$working_day--;}
						    			switch ($attendance->$day) {
						    				case $this->std_attendance_m->LABEL_PRESENT: {$present++;}break;
						    				case $this->std_attendance_m->LABEL_LEAVE: {$leave++;}break;
						    				case $this->std_attendance_m->LABEL_HOLIDAY: {$holiday++;}break;			    				
						    				case $this->std_attendance_m->LABEL_ABSENT: {$absent++;}break;			    				
						    				default: { $others++;}break;
						    			}
						    		}
						    	}else{
									$others+=$days_in_month;
						    	}
					    	$month_counter++;
					    	$start_month_number++;
					    	if($month_counter>12){$month_counter=1;$year_counter++;}
				    		}

					    	
					    ?>


						<div class="row mb-2 mt-2 plr-8">
							<div class="col-md-12 col-sm-12">
								<p><center><h3 class="font-weight-bold">ATTENDANCE REPORT</h3></center></p>
								<table class="table table-sm text-center table-solid">
							        <thead>
							            <tr>
							                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Present</th>
							                <th class="" style="border:1px solid black;min-width: 10%"><?php print $present; ?> days</th>
							                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Absent</th>
							                <th class="" style="border:1px solid black;min-width: 10%;"><?php print $absent; ?> days</th>
							                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Leaves</th>
							                <th class="" style="border:1px solid black;min-width: 10%;"><?php print $leave; ?> days</th>
							                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Holidays</th>
							                <th class="" style="border:1px solid black;min-width: 10%;"><?php print $holiday; ?> days</th>
							                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Other</th>
							                <th class="" style="border:1px solid black;min-width: 10%;"><?php print $others; ?> days</th>
							            </tr>
							        </thead>
							    </table>
							</div>
						</div>

						<?php }?>
						<div class="row mt-2 mb-2 plr-8">
							<div class="col-md-12 col-sm-12">
							<p><center>
								<h3 class="font-weight-bold">SUBJECT - WISE STATEMENT OF MARKS</h3>
							</center></p>
							<table class="table table-sm text-center table-solid">
						        <thead>
						            <tr>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Sr. No.</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Subject</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Total Marks</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Obt. Marks</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Grade</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Result</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;">Percent (%)</th>
						            </tr>
						        </thead>
						        <tbody>
						        	<?php
						        	$i=0;
						        	$final_status="<span class=''>Pass</span>";
						        	$grades_final_status="<span class=''><strong> Passed </strong></span>";
						        	$total_subject_marks=0;
						        	$total_obt_marks=0;
						        	$subjects=$this->class_subject_m->get_rows(array('class_id'=>$student->class_id,'campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC'));
						        	$sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$student->class_id,'term_id'=>$term_id,'student_id'=>$student->mid);
						        	foreach($subjects as $subject){
						        		$subject_marks=0;
						        		$obt_marks=0;
						        		$grade='';
						        		$status='';
						        		$sub_filter['subject_id']=$subject['mid'];
						        		$result=$this->std_term_result_m->get_by($sub_filter,true);
						        		if(!empty($result)){
						        			$subject_marks=$result->total_marks;
						        			$obt_marks=$result->obt_marks;
						        			$grade=$this->std_result_m->get_grade($result->total_marks,$result->obt_marks);
						        			$status=$result->status;
						        			if(strtolower($result->status)=='fail'){
						        				$final_status="<span class=''>Fail</span>";
						        				$grades_final_status="<span class=''><strong> Failed </strong></span>";
						        			}
							        		$total_subject_marks+=$subject_marks;
							        		$total_obt_marks+=$obt_marks;
						        		}else{
						        			continue;
						        		}
						        		$i++;
						        		
						        	?>
						            <tr>
						            	<td><?php print $i; ?></td>
						            	<td><?php print  ucwords($subject['name']);?></td>
						                <td><?php print  $subject_marks;?></td>
						                <td><?php print  $obt_marks;?></td>
						                <td><?php print  $grade;?></td>
						                <td><?php print  ucwords($status);?></td>
						                <td><?php print  $subject_marks>0 ? round((($obt_marks/$subject_marks)*100),2).'%' : '';?></td>
						            </tr>
						        	<?php } ?>
						        </tbody>
						        <?php if($total_obt_marks>0 && $total_subject_marks>0){
						        	if(!isset($form['p_rgrd'])){
						        		//if not restricted grading the apply normal grading as per configurations from campus settings
						        		if(round(($total_obt_marks/$total_subject_marks)*100) < $this->CAMPUSSETTINGS[$this->campus_setting_m->_FINAL_OPASS_PERCENT]){
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
						                <th class="font-weight-bold" colspan="2" style="border:1px solid black;background-color: #B8B8AA;">Grand Total</th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $total_subject_marks; ?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $total_obt_marks; ?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $this->std_result_m->get_grade($total_subject_marks,$total_obt_marks);?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print $final_status;?></th>
						                <th class="font-weight-bold" style="border:1px solid black;background-color: #B8B8AA;"><?php print  round((($total_obt_marks/$total_subject_marks)*100),2);?>%</th>
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
							<div class="col-md-6 col-sm-6">
							<table class="table table-sm text-center table-solid">
						        <thead>
						            <tr>
						                <th class="font-weight-bold" colspan="2" style="min-width: 30%;">Performance</th>
						            </tr>
						        </thead>
						        <tbody>
						            <tr>

						            	<td class="font-weight-semibold">
								            <?php print isset($form['prfm_ttl_1']) ? $form['prfm_ttl_1'] : 'Home Work';  ?>
							            </td>
						            	<td>						            		
								            <?php print isset($form['prfm_val_1']) ? $form['prfm_val_1'] : 'Good';  ?>
						            	</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">
								            <?php print isset($form['prfm_ttl_2']) ? $form['prfm_ttl_2'] : 'Uniform';  ?>
							            </td>
						            	<td>						            		
								            <?php print isset($form['prfm_val_2']) ? $form['prfm_val_2'] : 'Good';  ?>
						            	</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">
								            <?php print isset($form['prfm_ttl_3']) ? $form['prfm_ttl_3'] : 'Punctuality';  ?>
							            </td>
						            	<td>						            		
								            <?php print isset($form['prfm_val_3']) ? $form['prfm_val_3'] : 'Good';  ?>
						            	</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">
								            <?php print isset($form['prfm_ttl_4']) ? $form['prfm_ttl_4'] : 'Holidays Work';  ?>
							            </td>
						            	<td>						            		
								            <?php print isset($form['prfm_val_4']) ? $form['prfm_val_4'] : 'Good';  ?>
						            	</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">
								            <?php print isset($form['prfm_ttl_5']) ? $form['prfm_ttl_5'] : 'Communication Skills';  ?>
							            </td>
						            	<td>						            		
								            <?php print isset($form['prfm_val_5']) ? $form['prfm_val_5'] : 'Good';  ?>
						            	</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">
								            <?php print isset($form['prfm_ttl_6']) ? $form['prfm_ttl_6'] : 'Self Discipline';  ?>
							            </td>
						            	<td>						            		
								            <?php print isset($form['prfm_val_6']) ? $form['prfm_val_6'] : 'Good';  ?>
						            	</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">
								            <?php print isset($form['prfm_ttl_7']) ? $form['prfm_ttl_7'] : 'Co-curricular Activities';  ?>
							            </td>
						            	<td>						            		
								            <?php print isset($form['prfm_val_7']) ? $form['prfm_val_7'] : 'Good';  ?>
						            	</td>
						            </tr>
						        </tbody>
						    </table>
							</div>
							<div class="col-md-6 col-sm-6">
							<table class="table table-sm text-center table-solid">
						        <thead>
						            <tr>
						                <th class="font-weight-bold" colspan="3" style="min-width: 30%;">Grading Scheme</th>
						            </tr>
						        </thead>
						        <tbody>
						            <tr>
						            	<td class="font-weight-semibold">A+</td>
						            	<td>80% - 100%</td>
						            	<td><?php print $this->std_result_m->get_remarks('a+');?></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">A</td>
						            	<td>70% - 79.99%</td>
						            	<td><?php print $this->std_result_m->get_remarks('a');?></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">B</td>
						            	<td>60% - 69.99%</td>
						            	<td><?php print $this->std_result_m->get_remarks('b');?></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">C</td>
						            	<td>50% - 59.99%</td>
						            	<td><?php print $this->std_result_m->get_remarks('c');?></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">D</td>
						            	<td>40% - 49.99%</td>
						            	<td><?php print $this->std_result_m->get_remarks('d');?></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">E</td>
						            	<td>33% - 39.99%</td>
						            	<td><?php print $this->std_result_m->get_remarks('e');?></td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">F</td>
						            	<td>0% - 32.99%</td>
						            	<td><?php print $this->std_result_m->get_remarks('f');?></td>
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

						<br><br><br>
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
					<?php if(isset($form['p_prg'])){?>
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-printer mr-2"></i>Performance Scales</legend>
					<div class="row">
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_ttl_1" value="<?php print isset($form['prfm_ttl_1'])? $form['prfm_ttl_1']: 'Home Work'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Result</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_val_1" value="<?php print isset($form['prfm_val_1'])? $form['prfm_val_1']: 'Good'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-compose"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_ttl_2" value="<?php print isset($form['prfm_ttl_2'])? $form['prfm_ttl_2']: 'Uniform'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Result</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_val_2" value="<?php print isset($form['prfm_val_2'])? $form['prfm_val_2']: 'Good'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-compose"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_ttl_3" value="<?php print isset($form['prfm_ttl_3'])? $form['prfm_ttl_3']: 'Punctuality'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Result</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_val_3" value="<?php print isset($form['prfm_val_3'])? $form['prfm_val_3']: 'Good'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-compose"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_ttl_4" value="<?php print isset($form['prfm_ttl_4'])? $form['prfm_ttl_4']: 'Holidays Work'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Result</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_val_4" value="<?php print isset($form['prfm_val_4'])? $form['prfm_val_4']: 'Good'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-compose"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_ttl_5" value="<?php print isset($form['prfm_ttl_5'])? $form['prfm_ttl_5']: 'Communication Skills'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Result</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_val_5" value="<?php print isset($form['prfm_val_5'])? $form['prfm_val_5']: 'Good'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-compose"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_ttl_6" value="<?php print isset($form['prfm_ttl_6'])? $form['prfm_ttl_6']: 'Self Discipline'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Result</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_val_6" value="<?php print isset($form['prfm_val_6'])? $form['prfm_val_6']: 'Good'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-compose"></i>
								</div>
							</div>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Title</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_ttl_7" value="<?php print isset($form['prfm_ttl_7'])? $form['prfm_ttl_7']: 'Co-curricular Activities'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-vcard"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Result</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md" placeholder="Title" name="prfm_val_7" value="<?php print isset($form['prfm_val_7'])? $form['prfm_val_7']: 'Good'; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-compose"></i>
								</div>
							</div>
						</div>	

					</div>
					<?php } ?>

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
