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
		<div class="page" style="line-heights: 1.7;">

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
									<span class="form-line">Student Name<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Student Mobile<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">NIC / Form-B<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Blood Group<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Gender<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Religion<span class="form-line-fill line-70"> </span></span>
								</div>
								<div class="col-sm-6">
									<span class="form-line">Guardian Name<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Guardian Mobile<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Session<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Admission Class<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Section<span class="form-line-fill line-70"> </span></span>
									<span class="form-line">Cast<span class="form-line-fill line-70"> </span></span>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<span class="form-box p-20" style="width:35mm;height: 45mm">Passport Size Photo</span>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-sm-12">
							<span class="form-line">Home Address<span class="form-line-fill line-80"> </span></span>
							<span class="form-line">Medical History<span class="form-line-fill line-80"> </span></span>
							<span class="form-line">Other Information<span class="form-line-fill line-80"> </span></span>
							<span class="form-line"><span class="form-line-fill line-90 pt-3"> </span></span>
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
							<span class="form-line">Father Name<span class="form-line-fill line-70"> </span></span>
							<span class="form-line">Father NIC<span class="form-line-fill line-70"> </span></span>
							<span class="form-line">Profession<span class="form-line-fill line-70"> </span></span>
						</div>
						<div class="col-sm-6">
							<span class="form-line">Mother Name<span class="form-line-fill line-70"> </span></span>
							<span class="form-line">Mother NIC<span class="form-line-fill line-70"> </span></span>
							<span class="form-line">Profession<span class="form-line-fill line-70"> </span></span>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-sm-12">
							<span class="form-line">Emergency Contact Number<span class="form-line-fill line-80"> </span></span>
							<span class="form-line">Other Details<span class="form-line-fill line-80"> </span></span>
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
						<table class="table table-sm m-1">
							<thead>
								<tr>
									<th>#</th>
									<th>School</th>
									<th>Class</th>
									<th>Year</th>
									<th>Total Marks</th>
									<th>Obt. Marks</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1.</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>2.</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>3.</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>4.</td>
									<td></td>
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

			<!-- sibling info block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Brothers Or Sisters Schooling Here</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">
						<table class="table table-sm m-1">
							<thead>
								<tr>
									<th>#</th>
									<th style="width: 40%">Name</th>
									<th>Class</th>
									<th>Roll Number</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1.</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>2.</td>
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

			<!-- office use block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">For Office Use Only</span>
				</div>
				<div class="col-sm-12 form-block-body">
					<div class="row">
						<div class="col-sm-4">
							<span class="form-line">Admission Number<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Computer Number<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Family Number<span class="form-line-fill line-60"> </span></span>
						</div>
						<div class="col-sm-4">
							<span class="form-line">Admission Fee<span class="form-line-fill line-60">  </span></span>
							<span class="form-line">Tution Fee<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Van Fee<span class="form-line-fill line-60"> </span></span>
						</div>
						<div class="col-sm-4">
							<span class="form-line">Student ID<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Admission Officer<span class="form-line-fill line-60"> </span></span>
							<span class="form-line">Admission Date<span class="form-line-fill line-60"> </span></span>
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
	<?php print $this->config->item('app_print_code');?>2001 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
	</div>	

	</page>

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
	    <div class="page" style="line-height: 1.7;">

			<!-- rules block -->
			<div class="row form-block">
				<div class="col-sm-12 form-block-head">
						<span class="">Rules & Regulations</span>
				</div>
				<div class="col-sm-12 form-block-body">
					
					<div class="row">
						<p>
							<span class="form-check-icon"> &#187;</span>
							<span class="form-check-title">Any change in the contact numbers or in the address should be notified to the institute immediately.</span>
						</p>
						<p>
							<span class="form-check-icon"> &#187;</span>
							<span class="form-check-title">Fee is payable in advance till 5th of every month. All charges are non-refundable except Security Charges.</span>
						</p>
						<p>
							<span class="form-check-icon"> &#187;</span>
							<span class="form-check-title">Fee of summber vacations is payable before the vacations/collection of results. Fee will be increased annually.</span>
						</p>
						<p>
							<span class="form-check-icon"> &#187;</span>
							<span class="form-check-title">If student absent two week without written leave/information he/she will be expl from institute.</span>
						</p>
						<p>
							<span class="form-check-icon"> &#187;</span>
							<span class="form-check-title">Institue will expel student immediately and with out any prior notice if he/she break discipline, or found in immoral activities.</span>
						</p>
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
					<p class="text-affirmation">I hereby certify that the information submitted through this form is true and accurate to the best of my knowledge, and I agree to be bound by the rules and regulations set forth herein and by any and all procedures of the institute applicable to the certification and process of the Certification Program, as it may be amended from time to time. I also confirm that I have attached the following documents with this form at the time of submission.</p>

					
					<div class="row">
						<div class="col-sm-12">
							<span style="font-size: 1.5em;"> &#9745;</span>
							<span class="form-check-title">Student NIC / Form-B</span>
						</div>
						<div class="col-sm-12">
							<span style="font-size: 1.5em;"> &#9745;</span>
							<span class="form-check-title">Father CNIC</span>
						</div>
						<div class="col-sm-12">
							<span style="font-size: 1.5em;"> &#9745;</span>
							<span class="form-check-title">Document Photo Copies</span>
						</div>
					</div>

					<br><br><br><br>
					<div class="row" style="text-align: center;">
						<div class="col-sm-4">
							<span class="border-top w-60"><strong>Father's Signature</strong></span>
							<span class="border-top w-40 ml-2">&nbsp;&nbsp; Thumb &nbsp;&nbsp;</span>
						</div>
						<div class="col-sm-4">
							<span class="border-top w-60"><strong>Mother/Guardian's Signature</strong></span>
							<span class="border-top w-40 ml-2">&nbsp;&nbsp; Thumb &nbsp;&nbsp;</span>
						</div>
						<div class="col-sm-4">
							<span class="border-top-dashed w-100">
							&nbsp;&nbsp; <strong>Principal's Signature</strong> &nbsp;&nbsp;</span>
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