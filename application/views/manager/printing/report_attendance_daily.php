<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
// $sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$sessions=$this->session_m->get_values_array('mid','title',array());
$params=array();
// $class_filter=array('campus_id'=>$this->CAMPUSID);
// if(isset($form['pid']) && !empty($form['pid'])){$class_filter['mid']=$form['pid'];}
// $params['orderby']='display_order ASC';
// $classes=$this->class_m->get_rows($class_filter,$params);
/////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
// $filter['status']=$this->std_fee_voucher_m->STATUS_UNPAID;
$filter['month_number']=$this->std_fee_voucher_m->month_number;
$filter['session_id']=$this->session_m->getActiveSession()->mid;
if(isset($form['type']) && !empty($form['type'])){$filter['status']=$form['type'];}
if(isset($form['class']) && !empty($form['class'])){$filter['class_id']=$form['class'];}
if(isset($form['month']) && !empty($form['month'])){$filter['month_number']=$form['month'];}
// if(isset($form['session']) && !empty($form['session'])){$filter['session_id']=$form['session'];}
$params['orderby']='class_id ASC, roll_no ASC';
$vouchers=$this->std_fee_voucher_m->get_rows($filter,$params);
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
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
		<div class="card <?php print $browser_print;?> editable" contenteditable="true">
			<div class="card-header bg-transparent header-elements-inline">
				<center><h6 class="card-title">Daily Student Attendance of <?php print  $this->ORG->name;?>(<strong><?php print ucwords(strtolower($this->CAMPUS->name));?></strong>) <?php print date('d-F-Y');?></h6></center>
			<span class="float-right mr-4"><img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->ORG->logo;?>" width="25" height="22"></span>
			</div>

			<div class="card-body editable" contenteditable="true">
				<div class="row">
					<div class="col-sm-9">
						<div class="mb-4">
							<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$usr->image;?>" class="mb-3 mt-2" alt="" style="width: 120px;">
								<ul class="list list-unstyled mb-0">
								<li>Guardian Name: <span class="font-weight-bold ml-2"><?php print ucwords($usr->guardian_name);?></span></li>
								<li>Guardian Mobile: <span class="font-weight-bold ml-2"><?php print ucwords($usr->guardian_mobile);?></span></li>
								<li>Father Name: <span class="font-weight-bold ml-2"><?php print ucwords($usr->father_name);?></span></li>
								<li>Mother Name: <span class="font-weight-bold ml-2"><?php print ucwords($usr->mother_name);?></span></li>
								<li>Emergency Contact: <span class="font-weight-bold ml-2"><?php print ucwords($usr->emergency_contact);?></span></li>
								<li>Address: <span class="font-weight-bold ml-2"><?php print ucwords($usr->address);?></span></li>
							</ul>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="mb-4">
							<div class="">
							<!-- <div class="text-sm-right"> -->
								<h4 class="text-primary mb-2 mt-md-2"><?php print ucwords(strtolower($usr->name));?></h4>
								<ul class="list list-unstyled mb-0">
									<li>Admission Number: <span class="font-weight-bold ml-2"><?php print ucwords($usr->admission_no);?></span></li>
									<li>Session: <span class="font-weight-bold ml-2"><?php print ucwords($session->title);?></span></li>
									<li>Class: <span class="font-weight-bold ml-2"><?php print ucwords($class->title);?></span></li>
									<li>Mobile: <span class="font-weight-bold ml-2"><?php print ucwords($usr->mobile);?></span></li>
									<li>Fee: <span class="font-weight-bold ml-2"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$usr->fee.' '.ucwords($usr->fee_type);?></span></li>
									<li>National ID: <span class="font-weight-bold ml-2"><?php print ucwords($usr->nic);?></span></li>
									<li>Gender: <span class="font-weight-bold ml-2"><?php print ucwords($usr->gender);?></span></li>
									<li>Blood Group: <span class="font-weight-bold ml-2"><?php print strtoupper($usr->blood_group);?></span></li>
									<li>Date Of Birth: <span class="font-weight-bold ml-2"><?php print ucwords($usr->date_of_birth);?></span></li>
									<li>Student Age: <span class="font-weight-bold ml-2"><?php print get_age($usr->date_of_birth);?></span></li>
									<li>Registered on: <span class="font-weight-bold ml-2"><?php print ucwords($usr->date);?></span></li>
								</ul>

							</div>

						</div>
					</div>
				</div>

				
			</div>

			<!-- <p class="text-muted">Academic record registered in the system</p> -->
			<!-- <br> -->
			<div class="table-responsive">
			    <table class="table table-lg">
			        <thead>
			            <tr>
			                <th class="font-weight-bold">#</th>
			                <th class="font-weight-bold">Session</th>
			                <th class="font-weight-bold">Class</th>
			                <th class="font-weight-bold">Roll Number</th>
			                <th class="font-weight-bold">Marks</th>
			                <th class="font-weight-bold">Status</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
			        	$i=0;
			        	if(count($academics)<1){
			        		?>
			        		<tr>
				            	<td colspan="4"><span class="font-weight-semibold text-danger">Academic record not found!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($academics as $row){
				        	?>
				            <tr>
				            	<td><?php print ++$i;?></td>
				                <td>
				                	<h6 class="mb-0"><?php print $row['session'];?></h6>
			                	</td>
				                <td><?php print $row['class'];?></td>
				                <td><?php print $row['roll_number'];?></td>
				                <td><?php print $row['obtained_marks'].'/'.$row['total_marks'];?></td>
				                <td><span class="font-weight-semibold"><?php print $row['status'];?></span></td>
				            </tr>
				        	<?php } 
			        	}?>
			        </tbody>
			    </table>
			</div>


			<div class="card-footer">
				<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
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
