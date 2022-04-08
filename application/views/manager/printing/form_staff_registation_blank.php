<?php
// print_r($form); 
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

			<!-- staff information block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Staff Information</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">
						<div class="col-sm-10">
							<div class="row">
								<div class="col-sm-6">
									<span class="form-line">Full Name:<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Mobile Number:<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Blood Group:<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Gender:<span class="form-line-fill line-70"> </span></span>
								</div>
								<div class="col-sm-6">
									<span class="form-line">Guardian Name:<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Guardian Mobile:<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Email Address:<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">NIC :<span class="form-line-fill line-70"> </span></span>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<span class="form-box p-20" style="width:35mm;height: 45mm">Passport Size Photo</span>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-sm-12">
							<span class="form-line">Postal Address:<span class="form-line-fill line-80"> </span></span>
							<span class="form-line">Qualification:<span class="form-line-fill line-80"> </span></span>
							<span class="form-line">Favourite Subjects:<span class="form-line-fill line-80"> </span></span>
							<span class="form-line">Medical Problem (if any):<span class="form-line-fill line-80"> </span></span>
							<span class="form-line">Experience:<span class="form-line-fill line-80"> </span></span>
							<span class="form-line"><span class="form-line-fill line-90 pt-3"> </span></span>
						</div>
					</div>
					<br><br>
				</div>	
			</div>

			<!-- academic record block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Academic Record</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">
						<table class="table table-sm m-1" border="1">
							<thead>
								<tr>
									<th>#</th>
									<th>Exam</th>
									<th width="40%">Board</th>
									<th>Year</th>
									<th>Total Marks</th>
									<th>Obt. Marks</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1.</td>
									<td>Matric</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>2.</td>
									<td>Intermediate</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>3.</td>
									<td>Graduation</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>4.</td>
									<td>Master's</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>

					<br>
				</div>	
			</div>


			<!-- acknowledgement block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Acknowledgement</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<p class="text-affirmation">I hereby certify that the information submitted through this form is true and accurate to the best of my knowledge, and I agree to be bound by the terms and conditions set forth herein and by any and all procedures of the institute applicable to the job, as it may be amended from time to time. I also confirm that I have attached the following documents with this form at the time of submission.</p>

					
					<div class="row">
						<div class="col-sm-12">
							<span style="font-size: 1.5em;"> &#9745;</span>
							<span class="form-check-title">CNIC</span>
						</div>
						<div class="col-sm-12">
							<span style="font-size: 1.5em;"> &#9745;</span>
							<span class="form-check-title">Document Photo Copies</span>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6">
							<span class="form-line">Candidate Signature :<span class="form-line-fill line-60 mr-1"> </span></span>
						</div>
					</div>

					<br>
				</div>	
			</div>



			<!-- office use block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">For Office Use Only</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">
						<div class="col-sm-4">
							<span class="form-line">Date:<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Staff ID:<span class="form-line-fill line-60"> </span></span>
						</div>
						<div class="col-sm-4">
							<span class="form-line">Basic Salary:<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Designation:<span class="form-line-fill line-60"> </span></span>
						</div>
						<div class="col-sm-4">
							<span class="form-line">Operator Name:<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Signature:<span class="form-line-fill line-60"> </span></span>
						</div>
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
	<?php print $this->config->item('app_print_code');?>1001 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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