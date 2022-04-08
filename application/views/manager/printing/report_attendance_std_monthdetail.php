<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
$params=array();
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'year DESC') );
$activeSession=$this->session_m->getActiveSession();

isset($form['month']) && !empty($form['month']) ? $month=$form['month'] : $month=$this->student_m->month;
isset($form['year']) && !empty($form['year']) ? $year=$form['year'] : $year=$this->student_m->year;

if(isset($form['class']) && !empty($form['class']) ){
	$filter['class_id']=$form['class'];
	isset($form['section']) && !empty($form['section']) ? $filter['section_id']=$form['section'] : '';
} 
$filter['status']=$this->student_m->STATUS_ACTIVE;
$filter['session_id']=$activeSession->mid;
$students=$this->student_m->get_rows($filter,$params);
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
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>	

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

			<!-- class block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head" style="height: 1.3em;">
					<span class="">Student Attendance Register
					<?php if(!empty($month)){ print '- '.month_string($month);} ?>
					<?php if(!empty($year)){ print ' , '.$year;} ?>
					</span>
				</div>
				<div class="col-sm-12 form-block-body">

					
					<div class="row">			
					    <table class="table table-sm">
					        <thead>
					            <tr>
					                <th class="" width="3%">#</th>
					                <th class="" width="5%">Class</th>
					                <th class="" width="10%">Student</th>
					                <?php
										$days_in_month=days_in_month($month,$activeSession->year);
							    		for ($i=0; $i < $days_in_month; $i++) {
							    			?>
							    			<th class="" width="2%"><?php print $i+1;?></th>
							    			<?php
							    		}
							    	?>
					                <th class="" width="5%">Present</th>
					                <th class="" width="5%">Absent</th>
					                <th class="" width="5%">Leave</th>
					            </tr>
					        </thead>
					        <tbody>


				                <?php 

								$index=0;
								foreach($students as $row){
								?>
						            <tr>
						            	<td class="text-center"><?php print ++$index;?></td>
						                <td class="text-center">
						                	<?php if($row['class_id']){print $classes[$row['class_id']];} ?>			                		
						                </td>
						                <td class="text-center"><?php print $row['name'].'-'.$row['roll_no'];?></td>
										<?php 
									    	$working_day=0;
									    	$present=0;
									    	$absent=0;
									    	$leave=0;
									    	$holiday=0;
									    	$attendance=$this->std_attendance_m->get_by(array('campus_id'=>$this->CAMPUSID,'session_id'=>$activeSession->mid,'student_id'=>$row['mid'],'month'=>$month,'year'=>$year),true);
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
								    	<?php
									    	if(!empty($attendance)){
									    		for ($i=0; $i < $days_in_month; $i++) {
								    				$day='d'.($i+1);
								    				if(empty($attendance->$day)){
								    					?><td class="text-center"> - </td><?php
								    				}else{ 
								    					?><td class="text-center"><?php print strtoupper($attendance->$day);?></td><?php
										    		}
									    		}
									    	}else{
									    		for ($i=0; $i < $days_in_month; $i++) {
									    		?> <td class="text-center"> - </td><?php
										    	}
									    	}
										?>								
						                <td class=""><?php print $present;?></td>
						                <td class=""><?php print $leave;?></td>
						                <td class=""><?php print $absent;?></td>
						            </tr>
						        <?php } ?>
					        </tbody>
					    </table>
					    <br>
					</div>
					<br>
				</div>	
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
	<?php print $this->config->item('app_print_code');?>3007 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">
	
						<!-- <div class="col-md-3">
							<input type="checkbox" name="p_analytics" 
								<?php print isset($form['p_analytics']) ? 'checked':'';?>>
								Show Analytics Footer							
						</div> -->						
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
								while($y>=$this->ORG->year){
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
