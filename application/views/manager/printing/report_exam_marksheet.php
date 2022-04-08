<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$org_sessions=$this->session_m->get_values_array('mid','title',array(),'mid DESC');
//////////////////////////////////////////////////////////////////
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'year DESC') );
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$activeSession=$this->session_m->getActiveSession();

$student=$this->student_m->get_by_primary($form['usr']);
$class_id=$student->class_id;
isset($form['session']) && !empty($form['session']) ? $session_id=$form['session'] : $session_id=$activeSession->mid;
isset($form['class_id']) && !empty($form['class_id']) ? $class_id=$form['class_id'] : '';

$session=$this->session_m->get_by_primary($session_id);
$params=array();
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
									<span class="font-weight-bold m-2" style="font-size: 1.8em">RESULT INTIMATION CARD</span><br>
									<span class="font-weight-semibold m-2" style="font-size: 1.5em">SESSION - <?php print strtoupper($session->title)?></span>
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
									<span class="full-line">has qualified for award of result intimation card for class <span class="short-underline"><?php print strtoupper($class->title) ?></span>  at the final examination held for session <span class="short-underline"><?php print strtoupper($session->title) ?></span>. Further certified that student appeared in examination as <span class="short-underline">REGULAR</span> candidate.

									<?php if(isset($form['p_pos'])){
										$position=0;
										$avg_performance='';
										$stds_performance=array();
										$stds=$this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'session_id'=>$session_id,'class_id'=>$class_id,'status'=>$this->student_m->STATUS_ACTIVE),array('select'=>'mid,name'));
										foreach($stds as $std){
											$total_marks=$this->std_subject_final_result_m->get_column_result('total_marks',array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'student_id'=>$std['mid']));
								            $obt_marks=$this->std_subject_final_result_m->get_column_result('obt_marks',array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'student_id'=>$std['mid']));    
								            $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}
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
									Candidate has secured the <span class="short-underline"><?php print get_ordinal_symbol($position);?></span> position in the class.
									<?php } ?>
									Please see below the detailed academic report of the candidate.</span></p>
							</div>
						</div>
					    
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
						        	$subjects=$this->class_subject_m->get_rows(array('class_id'=>$class_id,'campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC'));
						        	$sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$class_id,'student_id'=>$student->mid);
						        	foreach($subjects as $subject){
						        		$i++;
						        		$subject_marks=0;
						        		$obt_marks=0;
						        		$grade='';
						        		$status='';
						        		$sub_filter['subject_id']=$subject['mid'];
						        		$result=$this->std_subject_final_result_m->get_by($sub_filter,true);
						        		if(!empty($result)){
						        			$subject_marks=$result->total_marks;
						        			$obt_marks=$result->obt_marks;
						        			$grade=$this->std_result_m->get_grade($result->total_marks,$result->obt_marks);
						        			$status=$result->status;
						        			if(strtolower($result->status)=='fail'){
						        				$final_status="<span class=''>Fail</span>";
						        				$grades_final_status="<span class=''><strong> Failed </strong></span>";
						        			}
						        		}else{
						        			continue;
						        		}
						        		$total_subject_marks+=$subject_marks;
						        		$total_obt_marks+=$obt_marks;
						        		
						        	?>
						            <tr>
						            	<td><?php print $i; ?></td>
						            	<td><?php print  ucwords($subject['name']);?> (<?php print strtoupper($subject['code']);?>)</td>
						                <td><?php print  $subject_marks;?></td>
						                <td><?php print  $obt_marks;?></td>
						                <td><?php print  $grade;?></td>
						                <td><?php print  ucwords($status);?></td>
						                <td><?php print  round((($obt_marks/$subject_marks)*100),2);?>%</td>
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
					    <br><br><br>
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
						            	<td class="font-weight-semibold">Home Work</td>
						            	<td>Good</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">Uniform</td>
						            	<td>Good</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">Punctuality</td>
						            	<td>Good</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">Holidays Work</td>
						            	<td>Good</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">Communication Skills</td>
						            	<td>Good</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">Self Discipline</td>
						            	<td>Good</td>
						            </tr>
						            <tr>
						            	<td class="font-weight-semibold">Co-curricular Activities</td>
						            	<td>Good</td>
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
