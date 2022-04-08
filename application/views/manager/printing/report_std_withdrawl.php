<?php
/// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['alumni']) ){$filter['class_id']=0;}else{$filter['class_id >']=0;}
if(isset($form['status']) && !empty($form['status'])){$filter['status']=$form['status'];}
if(isset($form['nstatus']) && !empty($form['nstatus'])){$filter['status <>']=$form['nstatus'];}
if(isset($form['session']) && !empty($form['session'])){$filter['session_id']=$form['session'];}
if(isset($form['class']) && !empty($form['class'])){$filter['class_id']=$form['class'];}
if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
if(isset($form['blood_group']) && !empty($form['blood_group'])){$filter['blood_group']=$form['blood_group'];}
if(isset($form['fee']) && !empty($form['fee'])){$filter['fee_type']=$form['fee'];}
$params=array();
if(isset($form['search']) && !empty($form['search'])){
	$like=array();
    $search=array('name','father_name','father_nic','guardian_name','student_id','nic','mobile','blood_group','gender','roll_no');
	foreach($search as $val){$like[$val]=$form['search'];} 
	$params['like']=$like;
}
$params['orderby']='admission_no ASC, class_id ASC, roll_no ASC';
$rows=$this->student_m->get_rows($filter,$params);
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
$sessions=$this->session_m->get_values_array('mid','title',array());
$activeSession=$this->session_m->getActiveSession();
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
	<div class="page <?php print $browser_print;?>  editable">
		<div class="row">
			<div class="col-md-4 col-sm-4 vertical-center">
				<center><span class="">
					<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->ORG->logo;?>" class="rounded" width="48" height="48" alt=""></span></center>						
			</div>
			<div class="col-md-8 col-sm-8 vertical-center">
				<span class="font-weight-bold" style="font-size:1.5em;">Students List (<?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?>)</span><br>						
			</div>
		</div>	
		<br>
		<div class="row d-block">				
		    <table class="table table-sm">
		        <thead>
		            <tr>
		                <th class="font-weight-bold text-center">#</th>
		                <?php if(isset($form['p_regd'])){?><th class="font-weight-bold text-center">Registration Date</th><?php }?>
		                <?php if(isset($form['p_admission'])){?><th class="font-weight-bold text-center">Admission Number</th><?php }?>
		                <th class="font-weight-bold">Student Name</th>
		                <?php if(isset($form['p_fname'])){?><th class="font-weight-bold text-center">Father Name</th><?php }?>
		                <?php if(isset($form['p_dob'])){?><th class="font-weight-bold text-center">Date Of Birth</th><?php }?>
		                <?php if(isset($form['p_cast'])){?><th class="font-weight-bold text-center">Cast</th><?php }?>
		                <?php if(isset($form['p_religion'])){?><th class="font-weight-bold text-center">Religion</th><?php }?>
		                <?php if(isset($form['p_foccupation'])){?><th class="font-weight-bold text-center">Profession</th><?php }?>
		                <?php if(isset($form['p_address'])){?><th class="font-weight-bold text-center">Address</th><?php }?>
		                <th class="font-weight-bold text-center">Class</th>
		                <?php if(isset($form['p_gname'])){?><th class="font-weight-bold text-center">Guardian Name</th><?php }?>
		                <?php if(isset($form['p_stdid'])){?><th class="font-weight-bold text-center">Student ID</th><?php }?>
		                <?php if(isset($form['p_roll'])){?><th class="font-weight-bold text-center">Roll Number</th><?php }?>
		                <?php if(isset($form['p_bg'])){?><th class="font-weight-bold text-center">Blood Group</th><?php }?>
		                <?php if(isset($form['p_session'])){?><th class="font-weight-bold text-center">Session</th><?php }?>
		                <?php if(isset($form['p_fee'])){?><th class="font-weight-bold text-center">Fee</th><?php }?>
		                <?php if(isset($form['p_mobile'])){?><th class="font-weight-bold text-center">Mobile</th><?php }?>
		                <?php if(isset($form['p_gmobile'])){?><th class="font-weight-bold text-center">Guardian Mobile</th><?php }?>
		                <?php if(isset($form['p_computer'])){?><th class="font-weight-bold text-center">Computer Number</th><?php }?>
		                <?php if(isset($form['p_family'])){?><th class="font-weight-bold text-center">Family Number</th><?php }?>
		                <?php if(isset($form['p_emg'])){?><th class="font-weight-bold text-center">Emergency Number</th><?php }?>
		                <?php if(isset($form['p_nic'])){?><th class="font-weight-bold text-center">National ID</th><?php }?>
		                <?php if(isset($form['p_fnic'])){?><th class="font-weight-bold text-center">Father National ID</th><?php }?>
		                <?php if(isset($form['p_gender'])){?><th class="font-weight-bold text-center">Gender</th><?php }?>
		                <?php if(isset($form['p_advance'])){?><th class="font-weight-bold text-center">Advance Amount</th><?php }?>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php
		        	$i=0;
		        	$total_fee_monthly=0;
		        	$total_fee_term=0;
		        	$total_advance=0;
		        	if(count($rows)<1){
		        		?>
		        		<tr>
			            	<td colspan="3"><span class="font-weight-semibold text-danger">No Student registered yet!</span></td>			                
			            </tr>
		        	<?php
		        	}else{
			        	foreach($rows as $row){
			        		$total_advance+=$row['advance_amount'];
			        		if($row['fee_type']==$this->student_m->FEE_TYPE_FIXED){$total_fee_term+=$row['fee'];}
			        		if($row['fee_type']==$this->student_m->FEE_TYPE_MONTHLY){$total_fee_monthly+=$row['fee'];}
			        	?>
			            <tr>
			            	<td class="text-center"><?php print ++$i;?></td>
		                	<?php if(isset($form['p_regd'])){?><td class="text-center"><?php print strtoupper($row['date']);?></td><?php }?>
		                	<?php if(isset($form['p_admission'])){?><td class="text-center"><?php print $row['admission_no'];?></td><?php }?>
			                <td>
			                	<?php if(isset($form['p_img'])){?>
			                		<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$row['image'];?>" class="rounded-circle mr-1" width="28" height="26" alt="">
			                	<?php }?>
			                	<strong><?php print ucwords(strtolower($row['name']));?></strong>
		                	</td>
		                	<?php if(isset($form['p_fname'])){?><td class="text-center"><?php print $row['father_name'];?></td><?php }?>
		                	<?php if(isset($form['p_dob'])){?><td class="text-center"><?php print $row['date_of_birth'];?></td><?php }?>
		                	<?php if(isset($form['p_cast'])){?><td class="text-center"><?php print $row['cast'];?></td><?php }?>
		                	<?php if(isset($form['p_religion'])){?><td class="text-center"><?php print $row['religion'];?></td><?php }?>
		                	<?php if(isset($form['p_foccupation'])){?><td class="text-center"><?php print $row['father_occupation'];?></td><?php }?>
		                	<?php if(isset($form['p_address'])){?><td class="text-center"><?php print $row['address'];?></td><?php }?>
			                <td class="text-center"><?php print $row['class_id']>0 ? $classes[$row['class_id']] : 'Alumni';?></td>
		                	<?php if(isset($form['p_gname'])){?><td class="text-center"><?php print $row['guardian_name'];?></td><?php }?>
		                	<?php if(isset($form['p_stdid'])){?><td class="text-center"><?php print $row['student_id'];?></td><?php }?>
		                	<?php if(isset($form['p_roll'])){?><td class="text-center"><?php print strtoupper($row['roll_no']);?></td><?php }?>
		                	<?php if(isset($form['p_bg'])){?><td class="text-center"><?php print strtoupper($row['blood_group']);?></td><?php }?>
		                	<?php if(isset($form['p_session'])){?><td class="text-center"><?php print $sessions[$row['session_id']];?></td><?php }?>
		                	<?php if(isset($form['p_fee'])){?><td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['fee'];?></td><?php }?>
		                	<?php if(isset($form['p_mobile'])){?><td class="text-center"><?php print $row['mobile'];?></td><?php }?>
		                	<?php if(isset($form['p_gmobile'])){?><td class="text-center"><?php print $row['guardian_mobile'];?></td><?php }?>
		                	<?php if(isset($form['p_computer'])){?><td class="text-center"><?php print $row['computer_number'];?></td><?php }?>
		                	<?php if(isset($form['p_family'])){?><td class="text-center"><?php print $row['family_number'];?></td><?php }?>
		                	<?php if(isset($form['p_emg'])){?><td class="text-center"><?php print $row['emergency_contact'];?></td><?php }?>
		                	<?php if(isset($form['p_nic'])){?><td class="text-center"><?php print $row['nic'];?></td><?php }?>
		                	<?php if(isset($form['p_fnic'])){?><td class="text-center"><?php print $row['father_nic'];?></td><?php }?>
		                	<?php if(isset($form['p_gender'])){?><td class="text-center"><?php print $row['gender'];?></td><?php }?>
		                	<?php if(isset($form['p_advance'])){?><td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['advance_amount'];?></td><?php }?>
			            </tr>
			        	<?php } 
		        	}?>
		        </tbody>
		    </table>
		    <div class="col-sm-12">
		    <br>		    	
		    <span class="font-weight-semibold m-1">Total Students:</span><span class="p-2 border-1"><?php print count($rows);?></span>
		    <?php if(isset($form['p_fee'])){?>
		    <span class="font-weight-semibold m-1">Total Monthly Fee :</span><span  class="p-2 border-1"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_fee_monthly;?></span>
		    <span class="font-weight-semibold m-1">Total Term Fee :</span><span  class="p-2 border-1"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_fee_term;?></span>
		    <?php }?>
		    <?php if(isset($form['p_advance'])){?>
		    	<span class="font-weight-semibold m-1">Advance Amount Received :</span><span  class="p-2 border-1"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_advance;?></span>
		    <?php }?>
		    </div>
		    <br>
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
