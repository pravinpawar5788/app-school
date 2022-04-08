<?php
// print_r($form); 
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////

$usr=$this->staff_m->get_by(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['usr']),true);
$academics=$this->stf_qual_m->get_rows(array('staff_id'=>$form['usr']),array('sortby'=>'year DESC'));
$role=$this->stf_role_m->get_by_primary($usr->role_id);

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
	      <!--place  holder for the fixed-position header-->
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

									<?php if(empty($usr->staff_id)){ ?>
									<span class="form-line">Staff ID:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Staff ID:<span class="form-line-textfill line-70"> <?php print ucwords($usr->staff_id);?></span></span>
									<?php } ?>

									<?php if(empty($usr->name)){ ?>
									<span class="form-line">Full Name:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Full Name:<span class="form-line-textfill line-70"> <?php print ucwords($usr->name);?></span></span>
									<?php } ?>

									<?php if(empty($usr->mobile)){ ?>
									<span class="form-line">Mobile:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Mobile:<span class="form-line-textfill line-70"> <?php print ucwords($usr->mobile);?></span></span>
									<?php } ?>

									<?php if(empty($usr->blood_group)){ ?>
									<span class="form-line">Blood Group:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Blood Group:<span class="form-line-textfill line-70"> <?php print ucwords($usr->blood_group);?></span></span>
									<?php } ?>

								</div>
								<div class="col-sm-6">	

									<?php if(empty($usr->gender)){ ?>
									<span class="form-line">Gender:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Gender:<span class="form-line-textfill line-70"> <?php print ucwords($usr->gender);?></span></span>
									<?php } ?>				

									<?php if(empty($usr->email)){ ?>
									<span class="form-line">Email Address:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Email Address:<span class="form-line-textfill line-70"> <?php print ucwords($usr->email);?></span></span>
									<?php } ?>								

									<?php if(empty($usr->cnic)){ ?>
									<span class="form-line">CNIC:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">CNIC:<span class="form-line-textfill line-70"> <?php print ucwords($usr->cnic);?></span></span>
									<?php } ?>							

									<?php if(empty($usr->salary)){ ?>
									<span class="form-line">Salary:<span class="form-line-fill line-70"> </span></span>
									<?php }else{ ?>
									<span class="form-line">Salary:<span class="form-line-textfill line-70"> <?php print ucwords($usr->salary);?></span></span>
									<?php } ?>
									
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<span class="form-box" style="width:35mm;height: 45mm"><img src="<?php print $this->UPLOADS_ROOT.'images/staff/profile/'.$usr->image;?>" style="width:35mm;height: 45mm"></span>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-sm-12">
							<?php if(empty($usr->guardian_name)){ ?>
							<span class="form-line">Father/Husband Name:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Father/Husband Name:<span class="form-line-textfill line-80"> <?php print ucwords($usr->guardian_name);?></span></span>
							<?php } ?>

							<?php if(empty($usr->guardian_mobile)){ ?>
							<span class="form-line">Father/Husband Mobile:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Father/Husband Mobile:<span class="form-line-textfill line-80"> <?php print ucwords($usr->guardian_mobile);?></span></span>
							<?php } ?>	
							<?php if(empty($usr->home_address)){ ?>
							<span class="form-line">Home Address:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Home Address:<span class="form-line-textfill line-80"> <?php print ucwords($usr->home_address);?></span></span>
							<?php } ?>			
							<?php if(empty($usr->postal_address)){ ?>
							<span class="form-line">Postal Address:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Postal Address:<span class="form-line-textfill line-80"> <?php print ucwords($usr->postal_address);?></span></span>
							<?php } ?>


							<?php if(empty($usr->qualification)){ ?>
							<span class="form-line">Qualification:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Qualification:<span class="form-line-textfill line-80"> <?php print ucwords($usr->qualification);?></span></span>
							<?php } ?>

							<?php if(empty($usr->favourite_subject)){ ?>
							<span class="form-line">Favourite Subject:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Favourite Subject:<span class="form-line-textfill line-80"> <?php print ucwords($usr->favourite_subject);?></span></span>
							<?php } ?>
							
							<?php if(empty($usr->experience)){ ?>
							<span class="form-line">Experience:<span class="form-line-fill line-80"> </span></span>
							<?php }else{ ?>
							<span class="form-line">Experience:<span class="form-line-textfill line-80"> <?php print ucwords($usr->experience);?></span></span>
							<?php } ?>

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
					    <table class="table table-sm">
					        <thead>
					            <tr>
					                <th class="font-weight-semibold text-center" width="5%">#</th>
					                <th class="font-weight-semibold text-center">Degree</th>
					                <th class="font-weight-semibold text-center">Roll Number</th>
					                <th class="font-weight-semibold text-center">Registration Number</th>
					                <th class="font-weight-semibold text-center">Institute</th>
					                <th class="font-weight-semibold text-center">Passing Year</th>
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
						                	<h6 class="mb-0"><?php print $row['qualification'];?></h6>
					                	</td>
						                <td class="text-center"><?php print $row['roll_number'];?></td>
						                <td class="text-center"><?php print $row['registration_no'];?></td>
						                <td class="text-center"><?php print $row['institute'];?></td>
						                <td class="text-center"><?php print $row['year'];?></td>
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