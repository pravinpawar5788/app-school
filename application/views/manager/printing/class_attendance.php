<?php
// print_r($form); 
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
//////////////////////////////////////////////////////////////////
$month=$this->class_m->month;
$year=$this->class_m->year;
if(isset($form['year']) && !empty($form['year'])){$year=$form['year'];}
if(isset($form['month']) && !empty($form['month'])){$month=$form['month'];}
$activeSession=$this->session_m->getActiveSession();
$sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$sessions=$this->session_m->get_values_array('mid','title',array());
$params=array();
$class_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['pid']) && !empty($form['pid'])){$class_filter['mid']=$form['pid'];}
$params['orderby']='display_order ASC';
$classes=$this->class_m->get_rows($class_filter,$params);
/////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
$filter['status']=$this->student_m->STATUS_ACTIVE;
if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
if(isset($form['section']) && !empty($form['section'])){$filter['section_id']=$form['section'];}
$filter['session_id']=$this->session_m->getActiveSession()->mid;
$params['orderby']='class_id ASC, section_id ASC, roll_no ASC';
$rows=$this->student_m->get_rows($filter,$params);
?>
<!-- Main content -->
<div class="content-wrapper">

				
<!-- Content area -->
<div class="content">

	
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
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>

	<?php  foreach($classes as $class){?>
	<div class="avoid-page-break">
	<p class="text-center" style="margin-top: 50px;">Attendance Report of class <strong><?php print ucwords(strtolower($class['title']));?></strong> for <?php print month_string($month).' '.$year;?></p>

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
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="font-weight-bold text-center">#</th>
			                <th class="font-weight-bold">Name</th>
			                <th class="font-weight-bold text-center">Father Name</th>
			                <?php if(isset($form['p_section'])){?>
			                	<th class="font-weight-bold text-center">Section</th><?php }?>
			                <th class="font-weight-bold text-center">Roll Number</th>
			                <th class="font-weight-bold text-center">Present</th>
			                <th class="font-weight-bold text-center">Absent</th>
			                <th class="font-weight-bold text-center">Leave</th>
			                <th class="font-weight-bold text-center">Holidays</th>
			                <th class="font-weight-bold text-center">Not Marked</th>

			            </tr>
			        </thead>
			        <tbody>
			        	<?php
			        	$i=0;
			        	$filter['class_id']=$class['mid'];
						$rows=$this->student_m->get_rows($filter,$params);
			        	if(count($rows)<1){
			        		?>
			        		<tr>
				            	<td colspan="4"><span class="font-weight-semibold text-danger">No student yet admitted in this class!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($rows as $row){
						    	$working_day=0;$present=0;$absent=0;$leave=0;$holiday=0;$other=0;
						    	$attendance=$this->std_attendance_m->get_by(array('campus_id'=>$this->CAMPUSID,'student_id'=>$row['mid'],'month'=>$month,'year'=>$year),true);
						    	if(!empty($attendance)){
						    			$days_in_month=days_in_month($attendance->month,$attendance->year);
						    			$working_day=$days_in_month;
						    		for ($i=0; $i < $days_in_month; $i++) {
						    			$day='d'.($i+1);
						    			if($attendance->$day==$this->std_attendance_m->LABEL_HOLIDAY){$working_day--;}
						    			switch ($attendance->$day) {
						    				case $this->std_attendance_m->LABEL_PRESENT: {$present++;}break;
						    				case $this->std_attendance_m->LABEL_LEAVE: {$leave++;}break;
						    				case $this->std_attendance_m->LABEL_HOLIDAY: {$holiday++;}break;			    				
						    				case $this->std_attendance_m->LABEL_ABSENT: {$absent++;}break;			    				
						    				default: { $other++;}break;
						    			}
						    		}
						    	}

				        	?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td>
				                	<?php if(isset($form['p_img'])){?>
				                		<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$row['image'];?>" class="rounded-circle m-1" width="28" height="26" alt="">
				                	<?php }?>
				                	<strong><?php print ucwords(strtolower($row['name']));?></strong>
			                	</td>
			                	<td class="text-center"><?php print ucwords(strtolower($row['father_name']));?></td>
				                <?php if(isset($form['p_section'])){?>
				                	<td class="text-center"><?php print $row['section'];?></td><?php }?>
				                <td class="text-center"><?php print $row['roll_no'];?></td>
			                	<td class="text-center"><?php print $present;?></td>
			                	<td class="text-center"><?php print $absent;?></td>
			                	<td class="text-center"><?php print $leave;?></td>
			                	<td class="text-center"><?php print $holiday;?></td>
			                	<td class="text-center"><?php print $other;?></td>
				            </tr>
				        	<?php } 
			        	}?>
			        </tbody>
			    </table>
			    <br>
			</div>


			<br><br>
			<?php if(isset($form['p_analytics'])){?>
			<div class="row">
				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold">Total Students:</span>
				</div>
				<div class="col-sm-2 grd-bg-orange border-white text-center">
					<span class="text-bold">  </span>
				</div>
			</div>
			<?php }?>


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
	</div>	
	<?php } ?>
	<div class="page-footer">
	<?php print $this->config->item('app_print_code');?>2007 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
<!-- /main content -->

<!-- create voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/attendance';?>">
				<input type="hidden" name="pid" value="<?php print isset($form['pid'])?$form['pid']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						
						<div class="col-md-3">
							<input type="checkbox" name="p_img" 
								<?php print isset($form['p_img']) ? 'checked':'';?>>
								Show Profile Image							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_section" 
								<?php print isset($form['p_section']) ? 'checked':'';?>>
								Show Section							
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
							<label class="text-muted">Year</label>          
							<select class="form-control select" name="year" data-fouc>
						    <option value="" />Current Year
						    <?php 
						    	$i=0;
								$y=$this->user_m->year;
								while($y>$this->ORG->year){
									$i++;if($i>25){break;}
									?>
									<option value="<?php print $y;?>" <?php print isset($form['year'])&&$form['year']==$y?'selected':'';?>/><?php print $y;?>
									<?php
									$y--;
								}
							?>
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
							<select class="form-control select" name="pid" data-fouc>
						    <option value="" />All Classes
							<?php foreach ($classes_array as $key=>$val){?>            
							    <option value="<?php print $key;?>" <?php print isset($form['pid'])&&$form['pid']==$key?'selected':'';?>/><?php print $val;?>
						    <?php }?>
							</select>
						</div>		
						<?php if(isset($form['pid'])&& !empty($form['pid'])&&count($class_sections)){?>
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
