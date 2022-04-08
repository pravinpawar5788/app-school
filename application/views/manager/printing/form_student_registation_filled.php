<?php
/// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
// $filter=array('campus_id'=>$this->CAMPUSID);
// if(isset($form['alumni']) ){$filter['class_id']=0;}
// if(isset($form['status']) && !empty($form['status'])){$filter['status']=$form['status'];}
// if(isset($form['nstatus']) && !empty($form['nstatus'])){$filter['status <>']=$form['nstatus'];}
// if(isset($form['session']) && !empty($form['session'])){$filter['session_id']=$form['session'];}
// if(isset($form['class']) && !empty($form['class'])){$filter['class_id']=$form['class'];}
// if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
// if(isset($form['blood_group']) && !empty($form['blood_group'])){$filter['blood_group']=$form['blood_group'];}
// if(isset($form['fee']) && !empty($form['fee'])){$filter['fee >']=$form['fee'];}
// $params=array();
// if(isset($form['search']) && !empty($form['search'])){
// 	$like=array();
//     $search=array('name','father_name','father_nic','guardian_name','student_id','nic','mobile','blood_group','gender','roll_no');
// 	foreach($search as $val){$like[$val]=$form['search'];} 
// 	$params['like']=$like;
// }
// $params['orderby']='class_id ASC, roll_no ASC';
// $rows=$this->student_m->get_rows($filter,$params);
// $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
// $sessions=$this->session_m->get_values_array('mid','title',array());
// $activeSession=$this->session_m->getActiveSession();


//////////////////////////////////////////////////////////////////
$sibling=array();
$usr=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['usr']),true);
if(!empty($usr->father_nic)){
	$sibling=$this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'father_nic'=>$usr->father_nic),array('orderby'=>'class_id ASC'));

}
$academics=$this->std_qual_m->get_rows(array('student_id'=>$form['usr']),array('sortby'=>'session DESC'));
$class='';
if($usr->class_id>0){$class=$this->class_m->get_by_primary($usr->class_id);}
$session=$this->session_m->get_by_primary($usr->session_id);


?>
<!-- Main content -->
<div class="content-wrapper">

				
<!-- Content area -->
<div class="content">


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
		<div class="page" style="line-height: 1.9;">

			<!-- student information block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Student Information</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">
						<div class="col-sm-10">
							<div class="row">
								<div class="col-sm-6">
									<?php if(empty($usr->admission_no)){ ?>
									<span class="form-line">Admission No:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Admission No:<span class="form-line-textfill line-70"> <?php print ucwords($usr->admission_no);?></span></span>
									<?php } ?>

									<?php if(empty($usr->student_id)){ ?>
									<span class="form-line">Student ID:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Student ID:<span class="form-line-textfill line-70"> <?php print ucwords($usr->student_id);?></span></span>
									<?php } ?>


									<?php if(empty($usr->name)){ ?>
									<span class="form-line">Student Name: <span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Student Name: <span class="form-line-textfill line-70"> <?php print ucwords(strtolower($usr->name));?></span></span>
									<?php } ?>


									<?php if(empty($usr->mobile)){ ?>
									<span class="form-line">Student Mobile:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Student Mobile:<span class="form-line-textfill line-70"> <?php print ucwords($usr->mobile);?></span></span>
									<?php } ?>


									<?php if(empty($usr->nic)){ ?>
									<span class="form-line">NIC / Form-B:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">NIC / Form-B:<span class="form-line-textfill line-70"> <?php print ucwords($usr->nic);?></span></span>
									<?php } ?>


									<?php if(empty($usr->blood_group)){ ?>
									<span class="form-line">Blood Group:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Blood Group:<span class="form-line-textfill line-70"> <?php print strtoupper($usr->blood_group);?></span></span>
									<?php } ?>


									<?php if(empty($usr->gender)){ ?>
									<span class="form-line">Gender:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Gender:<span class="form-line-textfill line-70"> <?php print ucwords($usr->gender);?></span></span>
									<?php } ?>


									<?php if(empty($usr->religion)){ ?>
									<span class="form-line">Religion:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Religion:<span class="form-line-textfill line-70"> <?php print ucwords($usr->religion);?></span></span>
									<?php } ?>

									<?php if(empty($usr->date_of_birth)){ ?>
									<span class="form-line">Date Of Birth:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Date Of Birth:<span class="form-line-textfill line-70"> <?php print ucwords($usr->date_of_birth);?></span></span>
									<?php } ?>
								</div>
								<div class="col-sm-6">
									<?php if(empty($usr->computer_number)){ ?>
									<span class="form-line">Computer No:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Computer No:<span class="form-line-textfill line-70"> <?php print ucwords($usr->computer_number);?></span></span>
									<?php } ?>


									<?php if(empty($usr->family_number)){ ?>
									<span class="form-line">Family No:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Family No:<span class="form-line-textfill line-70"> <?php print ucwords($usr->family_number);?></span></span>
									<?php } ?>


									<?php if(empty($usr->guardian_name)){ ?>
									<span class="form-line">Guardian Name:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Guardian Name:<span class="form-line-textfill line-70"> <?php print ucwords($usr->guardian_name);?></span></span>
									<?php } ?>


									<?php if(empty($usr->guardian_mobile)){ ?>
									<span class="form-line">Guardian Mobile:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Guardian Mobile:<span class="form-line-textfill line-70"> <?php print ucwords($usr->guardian_mobile);?> (for sms)</span></span>
									<?php } ?>


									<?php if(empty($session->title)){ ?>
									<span class="form-line">Active Session:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Active Session:<span class="form-line-textfill line-70"> <?php print ucwords($session->title);?></span></span>
									<?php } ?>


									<?php if(empty($class->title)){ ?>
									<span class="form-line">Active Class:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Active Class:<span class="form-line-textfill line-70"> <?php print ucwords($class->title);?></span></span>
									<?php } ?>


									<?php if(empty($usr->cast)){ ?>
									<span class="form-line">Cast:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Cast:<span class="form-line-textfill line-70"> <?php print ucwords($usr->cast);?></span></span>
									<?php } ?>

									<?php if(empty($usr->fee)){ ?>
									<span class="form-line">Fee:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Fee:<span class="form-line-textfill line-70"> <?php print ucwords($usr->fee.' '.$usr->fee_type);?></span></span>
									<?php } ?>

									<?php if(empty($usr->date)){ ?>
									<span class="form-line">Registration Date:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Registration Date:<span class="form-line-textfill line-70"> <?php print ucwords($usr->date);?></span></span>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<span class="form-box" style="width:35mm;height: 45mm"><img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$usr->image;?>" style="width:35mm;height: 45mm"></span>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-sm-12">
							<?php if(empty($usr->address)){ ?>
							<span class="form-line">Home Address:<span class="form-line-fill line-80"></span></span>
							<?php }else{ ?>
							<span class="form-line">Home Address:<span class="form-line-textfill line-80"> <?php print ucwords($usr->address);?></span></span>
							<?php } ?>


							<?php if(empty($usr->medical_problem)){ ?>
							<span class="form-line">Medical History:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Medical History:<span class="form-line-textfill line-80"> <?php print ucwords($usr->medical_problem);?></span></span>
							<?php } ?>


							<?php if(empty($usr->other_info)){ ?>
							<span class="form-line">Other Information:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Other Information:<span class="form-line-textfill line-80"> <?php print ucwords($usr->other_info);?></span></span>
							<?php } ?>
						</div>
					</div>
					<br>
				</div>	
			</div>

			<!-- parent information block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Parent Information</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">
						<div class="col-sm-6">
							<?php if(empty($usr->father_name)){ ?>
							<span class="form-line">Father Name:<span class="form-line-fill line-70"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Father Name:<span class="form-line-textfill line-70"> <?php print ucwords($usr->father_name);?></span></span>
							<?php } ?>


							<?php if(empty($usr->father_nic)){ ?>
							<span class="form-line">Father NIC:<span class="form-line-fill line-70"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Father NIC:<span class="form-line-textfill line-70"> <?php print ucwords($usr->father_nic);?></span></span>
							<?php } ?>


							<?php if(empty($usr->father_occupation)){ ?>
							<span class="form-line">Profession:<span class="form-line-fill line-70"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Profession:<span class="form-line-textfill line-70"> <?php print ucwords($usr->father_occupation);?></span></span>
							<?php } ?>
						</div>
						<div class="col-sm-6">
							<?php if(empty($usr->mother_name)){ ?>
							<span class="form-line">Mother Name:<span class="form-line-fill line-70"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Mother Name:<span class="form-line-textfill line-70"> <?php print ucwords($usr->mother_name);?></span></span>
							<?php } ?>

							<?php if(empty($usr->mother_nic)){ ?>
							<span class="form-line">Mother NIC:<span class="form-line-fill line-70"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Mother NIC:<span class="form-line-textfill line-70"> <?php print ucwords($usr->mother_nic);?></span></span>
							<?php } ?>

							<span class="form-line">Profession:<span class="form-line-fill line-70"> </span></span>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-sm-12">
							<?php if(empty($usr->mother_nic)){ ?>
							<span class="form-line">Emergency Contact Number:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Emergency Contact Number:<span class="form-textline-fill line-80"> <?php print ucwords($usr->emergency_contact);?> (if parent's unreachable)</span></span>
							<?php } ?>
						</div>
					</div>
					<br>
				</div>	
			</div>


			<!-- academic record block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Previous Academic Record</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">			
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
					</div>

					<br>
				</div>	
			</div>

			<!-- sibling info block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Brothers Or Sisters Schooling Here</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">			
					    <table class="table table-sm">
					        <thead>
					            <tr>
					                <th class="font-weight-bold text-center" width="5%">#</th>
					                <th class="font-weight-bold text-center">Name</th>
					                <th class="font-weight-bold text-center">Class</th>
					                <th class="font-weight-bold text-center">Roll Number</th>

					            </tr>
					        </thead>
					        <tbody>

					        	<?php
					        	$i=0;
					        	if(count($sibling)<1){
					        		?>
					        		<tr>
						            	<td colspan="4"><span class="font-weight-semibold text-danger">Record not found!</span></td>			                
						            </tr>
					        	<?php
					        	}else{
						        	foreach($sibling as $row){
						        	?>
						            <tr>
						            	<td class="text-center"><?php print ++$i;?></td>
						                <td class="text-center"><?php print $row['name'];?></td>
						                <td class="text-center"><?php 
						                	if($row['class_id']>0){$cls=$this->class_m->get_by_primary($row['class_id'])->title;}else{$cls='';} print $cls;?></td>
						                <td class="text-center"><?php print $row['roll_no'];?></td>
						            </tr>
						        	<?php } 
					        	}?>		        	
					        </tbody>
					    </table>
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
	<?php print $this->config->item('app_print_code');?>2001 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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