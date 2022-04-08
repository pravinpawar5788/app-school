<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
////////////////////////////////////////////////////////////////////////////////////
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
///////////////////////////////////////////////////////////////////////////////////
$role='student';
if(isset($form['role']) && strtolower($form['role'])=='staff'){$role='staff';}
if(isset($form['date']) && !empty($form['date']) ){$date=$form['date'];}else{$date=date('d-M-Y');}
$filter=array('campus_id'=>$this->CAMPUSID);
$params=array();
$activeSession=$this->session_m->getActiveSession();
$pg_ttl='List of '.ucwords($role).'s ';
$pg_ttl_status=isset($form['status'])? ucwords(strtolower($form['status'])) : '';
if($role=='student'){
	$params['orderby']='class_id ASC, roll_no ASC';
	$filter['status']=$this->student_m->STATUS_ACTIVE;
	$filter['session_id']=$activeSession->mid;
	if(isset($form['class'])&& !empty($form['class'])){
		$pg_ttl.=' of class '.$classes[$form['class']];
		$filter['class_id']=$form['class'];
	}
	if(isset($form['section'])&& !empty($form['section'])){
		$pg_ttl.='('.$class_sections[$form['section']].') ';
		$filter['section_id']=$form['section'];
	}
	$rows=$this->student_m->get_rows($filter,$params);
	$sessions=$this->session_m->get_values_array('mid','title',array());
	$pg_ttl.='<strong>'.$pg_ttl_status.'</strong> on '.$date;
}else{
	$filter['status']=$this->student_m->STATUS_ACTIVE;
	$rows=$this->staff_m->get_rows($filter,$params);
	$pg_ttl.='<strong>'.$pg_ttl_status.'</strong> on '.$date;
}
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

	<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable avoid-page-break">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>
	<p class="text-center"><?php print $pg_ttl;?></p>	
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
			                <th class="font-weight-semibold text-center th_max_5">#</th>
			                <?php if(isset($form['p_sid'])){?>
			                	<th class="font-weight-semibold text-center"><?php print ucwords($role);?>.Id</th><?php }?>
			                <th class="font-weight-semibold text-center">Name</th>
			                <?php if($role=='student'){?>
			                <th class="font-weight-semibold text-center">Father Name</th>
			                <th class="font-weight-semibold text-center">Class</th>
			                <?php if(count($class_sections)>0){?>
			                <th class="font-weight-semibold text-center">Section</th>
				            <?php } ?>
			                <th class="font-weight-semibold text-center">Roll N0</th>
			                <?php }else{?>
			                <th class="font-weight-semibold text-center">F/H.Name</th>
			                <?php }?>
			                <?php if(isset($form['p_mobile'])){?>
			                	<th class="font-weight-semibold text-center">Mobile</th><?php }?>
			                <?php if(isset($form['p_gmobile'])){?>
			                	<th class="font-weight-semibold text-center">G.Mobile</th><?php }?>

			            </tr>
			        </thead>
			        <tbody>
			        	<?php
			        	$i=0;
				        $attendance_filter=array('campus_id'=>$this->CAMPUSID);
				        $attendance_filter['month']=intval(get_month_from_date($date, '-', true) );
				        $attendance_filter['year']=intval(get_year_from_date($date, '-') );
				        $attendance_filter['session_id']=$activeSession->mid;
			        	$day='d'.get_day_from_date($date,'-');
			        	foreach($rows as $row){
			        		if($role=='student'){
			        			$attendance_filter['student_id']=$row['mid'];
			        			if($this->std_attendance_m->get_rows($attendance_filter,'',true)<1){continue;}
			        			$attendance=$this->std_attendance_m->get_by($attendance_filter,true);
			        			if(isset($form['status'])&&$form['status']=='present'&&$attendance->$day!=$this->std_attendance_m->LABEL_PRESENT){continue;}
			        			if(isset($form['status'])&&$form['status']=='absent' && $attendance->$day!=$this->std_attendance_m->LABEL_ABSENT){continue;}
			        			if(isset($form['status'])&&$form['status']=='leave' && $attendance->$day!=$this->std_attendance_m->LABEL_LEAVE){continue;}
			        		}
			        		if($role=='staff'){
			        			$attendance_filter['staff_id']=$row['mid'];
			        			if($this->stf_attendance_m->get_rows($attendance_filter,'',true)<1){continue;}
			        			$attendance=$this->stf_attendance_m->get_by($attendance_filter,true);
			        			if(isset($form['status'])&&$form['status']=='present' && $attendance->$day!=$this->stf_attendance_m->LABEL_PRESENT){continue;}
			        			if(isset($form['status'])&&$form['status']=='absent' && $attendance->$day!=$this->stf_attendance_m->LABEL_ABSENT){continue;}
			        			if(isset($form['status'])&&$form['status']=='leave' && $attendance->$day!=$this->stf_attendance_m->LABEL_LEAVE){continue;}
			        		}
			        	?>
			            <tr>
			            	<td class="text-center"><?php print ++$i;?></td>

		                	<?php if($role=='student'){ ?>
		                	<?php if(isset($form['p_sid'])){?>
		                		<td class="text-center"><?php print $row['student_id'];?></td><?php }?>
		                	<?php }else{ ?>
		                	<?php if(isset($form['p_sid'])){?>
		                		<td class="text-center"><?php print $row['staff_id'];?></td><?php }?>
		                	<?php } ?>
			                <td class="text-left">
			                	<?php if(isset($form['p_img'])){?>

			                		<img src="<?php print $this->UPLOADS_ROOT.'images/'.$role.'/profile/'.$row['image'];?>" class="rounded m-1" width="28" height="26" alt="">
			                	<?php }?>
			                	<strong><?php print ucwords(strtolower($row['name']));?></strong>
		                	</td>
		                	<?php if($role=='student'){ ?>
		                	<td class="text-center"><?php print ucwords(strtolower($row['father_name']));?></td>
			                <td class="text-center"><?php print $row['class_id']>0 ? $classes[$row['class_id']] : '';?></td>
			                <?php if(count($class_sections)>0){?>
			                <td class="text-center"><?php print $row['section'];?></td>
				            <?php } ?>
			                <td class="text-center"><?php print $row['roll_no'];?></td>
		                	<?php }else{ ?>
		                	<td class="text-center"><?php print ucwords(strtolower($row['guardian_name']));?></td>
		                	<?php } ?>
		                	<?php if(isset($form['p_mobile'])){?>
		                		<td class="text-center"><?php print strtoupper($row['mobile']);?></td><?php }?>
		                	<?php if(isset($form['p_gmobile'])){?>
		                		<td class="text-center"><?php print $row['guardian_mobile'];?></td><?php }?>
			            </tr>
			        	<?php } ?>
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
	<?php print $this->config->item('app_print_code');?>3001 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/list';?>">
				<input type="hidden" name="status" value="<?php print isset($form['status'])?$form['status']:'';?>">
				<input type="hidden" name="role" value="<?php print isset($form['role'])?$form['role']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_sid" 
								<?php print isset($form['p_sid']) ? 'checked':'';?>>
								Show <?php print ucwords($role);?> ID							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_img" 
								<?php print isset($form['p_img']) ? 'checked':'';?>>
								Show Profile Image							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_computer" 
								<?php print isset($form['p_computer']) ? 'checked':'';?>>
								Show Computer Number							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_mobile" 
								<?php print isset($form['p_mobile']) ? 'checked':'';?>>
								Show <?php print ucwords($role);?> Mobile							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_gmobile" 
								<?php print isset($form['p_gmobile']) ? 'checked':'';?>>
								Show Guardian Mobile							
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
						<?php if($role=='student'){ ?>
							<div class="col-sm-3">
								<label class="text-muted">Class</label>          
								<select class="form-control select" name="class" data-fouc>
							    <option value="" />All Classes
								<?php foreach ($classes as $key=>$val){?>            
								    <option value="<?php print $key;?>" <?php print isset($form['class'])&&$form['class']==$key?'selected':'';?>/><?php print $val;?>
							    <?php }?>
								</select>
							</div>		
							<?php if(isset($form['class'])&& !empty($form['class'])&&count($class_sections)){?>
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
