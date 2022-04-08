<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$usr=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['usr']),true);
$academics=$this->std_qual_m->get_rows(array('student_id'=>$form['usr']),array('sortby'=>'session DESC'));
$class='';
if($usr->class_id>0){$class=$this->class_m->get_by_primary($usr->class_id);}
$session=$this->session_m->get_by_primary($usr->session_id);

?>
<!-- Main content -->
<div class="content-wrapper">
<!-- Content area -->
<div class="content">

	<!-- Top right menu -->
	<ul class="fab-menu fab-menu-absolute fab-menu-top-right" data-fab-toggle="click" id="fab-menu-affixed-demo-right">
		<li>
			<a class="fab-menu-btn btn bg-info-400 btn-float rounded-round btn-icon"><i class="fab-icon-open icon-help"></i><i class="fab-icon-close icon-cross2"></i></a>

			<ul class="fab-menu-inner">
				<li>
					<div data-fab-label="Configure Parameters">
						<a href="#" <?php print $this->MODAL_OPTIONS;?> data-target="#view" class="btn btn-light rounded-round btn-icon btn-float">
							<i class="icon-compose"></i></a>
					</div>
				</li>

			</ul>
		</li>
	</ul>
	<!-- /top right menu -->

	<div id="printing-content">
		<!--------------------------------------- printing --------------------------------->
		<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles'); ?>
		<div class="page <?php print $browser_print;?>  editable">
			<br>
			<div class="row">
				<div class="col-md-4 col-sm-4 vertical-center">
					<center><span class="">
						<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->ORG->logo;?>" class="rounded" width="48" height="48" alt=""></span></center>						
				</div>
				<div class="col-md-8 col-sm-8 vertical-center">
					<span class="font-weight-bold" style="font-size:1.5em;">Profile Information Of <?php print ucwords(strtolower($usr->name));?></span><br>						
				</div>
			</div>	
			<br><br><br>
			<div class="row">

				<div class="col-sm-9">
					<div class="mb-4">
						<div class="">
						<!-- <div class="text-sm-right"> -->
							<h4 class="text-primary mb-2 mt-md-2"><?php print ucwords(strtolower($usr->name));?></h4>
							<ul class="list list-unstyled mb-0">
								<li>Admission Number: <span class="font-weight-bold ml-2"><?php print ucwords($usr->admission_no);?></span></li>
								<li>Computer Number: <span class="font-weight-bold ml-2"><?php print ucwords($usr->computer_number);?></span></li>
								<li>Family Number: <span class="font-weight-bold ml-2"><?php print ucwords($usr->family_number);?></span></li>
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
				<div class="col-sm-3">
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
			</div>

			<br>
			<div class="row d-block">				
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="font-weight-bold text-center">#</th>
			                <th class="font-weight-bold text-center">Session</th>
			                <th class="font-weight-bold text-center">Class</th>
			                <th class="font-weight-bold text-center">Roll Number</th>
			                <th class="font-weight-bold text-center">Marks</th>
			                <th class="font-weight-bold text-center">Status</th>

			            </tr>
			        </thead>
			        <tbody>

			        	<?php
			        	$i=0;
			        	if(count($academics)<1){
			        		?>
			        		<tr>
				            	<td colspan="6"><span class="font-weight-semibold text-danger">Academic record not found!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($academics as $row){
				        	?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-center">
				                	<h6 class="mb-0"><?php print $row['session'];?></h6>
			                	</td>
				                <td class="text-center"><?php print $row['class'];?></td>
				                <td class="text-center"><?php print $row['roll_number'];?></td>
				                <td class="text-center"><?php print $row['obtained_marks'].'/'.$row['total_marks'];?></td>
				                <td class="text-center"><span class="font-weight-semibold"><?php print $row['status'];?></span></td>
				            </tr>
				        	<?php } 
			        	}?>		        	
			        </tbody>
			    </table>
			    <br>
			</div>
		</div>






		<!-- /printing -->
	</div>
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
