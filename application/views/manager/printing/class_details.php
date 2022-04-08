<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$classes_array=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['pid'])&&!empty($form['pid'])){$section_filter['class_id']=$form['pid'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
//////////////////////////////////////////////////////////////////
$sections=array();
$sessions=$this->session_m->get_values_array('mid','title',array());
$params=array();
$class_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['pid']) && !empty($form['pid'])){
	if($form['pid']!='all'){
		$class_filter['mid']=$form['pid'];
		$sections=$this->class_section_m->get_rows(array('class_id'=>$form['pid'],'campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );		
	}
}
$params['orderby']='display_order ASC';
$classes=array();
if(isset($form['pid']) && !empty($form['pid'])){
	$classes=$this->class_m->get_rows($class_filter,$params);
}
/////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
$filter['status']=$this->student_m->STATUS_ACTIVE;
if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
if(isset($form['section']) && !empty($form['section'])){$filter['section_id']=$form['section'];}
$filter['session_id']=$this->session_m->getActiveSession()->mid;
$params['orderby']='class_id ASC, section ASC, roll_no ASC';
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
	<?php  if(count($classes)>0){
		foreach($classes as $class){
		$total_class_fee=0;
		?>

	<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>		
	<p class="text-center">Students List of class <strong><?php print ucwords(strtolower($class['title']));?></strong></p>
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
	      <div class="page force-page-break-after" style="line-height: 1;">

					
			<div class="row">			
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="font-weight-semibold text-center" width="5%">#</th>
			                <th class="font-weight-semibold">Name</th>
			                <th class="font-weight-semibold text-center">Father Name</th>
			                <?php if(count($sections)>0){ ?>
				                <th class="font-weight-semibold text-center">Section</th>
				            <?php } ?>
			                <th class="font-weight-semibold text-center">Roll Number</th>
                			<?php if(isset($form['p_gmobile'])){?><th class="font-weight-bold text-center">Guardian Mobile</th><?php }?>
                			<?php if(isset($form['p_fee'])){?><th class="font-weight-bold text-center">Fee</th><?php }?>
			                <?php if(isset($form['p_session'])){?>
			                	<th class="font-weight-semibold text-center">Session</th><?php }?>

			                <?php if(isset($form['p_stdid'])){?><th class="font-weight-bold text-center">Student ID</th><?php }?>
			                <?php if(isset($form['p_bg'])){?><th class="font-weight-bold text-center">Blood Group</th><?php }?>
			                <?php if(isset($form['p_session'])){?><th class="font-weight-bold text-center">Session</th><?php }?>
			                <?php if(isset($form['p_mobile'])){?><th class="font-weight-bold text-center">Mobile</th><?php }?>
			                <?php if(isset($form['p_dob'])){?><th class="font-weight-bold text-center">Date Of Birth</th><?php }?>
			                <?php if(isset($form['p_admission'])){?><th class="font-weight-bold text-center">Admission Number</th><?php }?>
			                <?php if(isset($form['p_computer'])){?><th class="font-weight-bold text-center">Computer Number</th><?php }?>
			                <?php if(isset($form['p_family'])){?><th class="font-weight-bold text-center">Family Number</th><?php }?>
			                <?php if(isset($form['p_emg'])){?><th class="font-weight-bold text-center">Emergency Number</th><?php }?>
			                <?php if(isset($form['p_nic'])){?><th class="font-weight-bold text-center">National ID</th><?php }?>
			                <?php if(isset($form['p_fnic'])){?><th class="font-weight-bold text-center">Father National ID</th><?php }?>
			                <?php if(isset($form['p_gender'])){?><th class="font-weight-bold text-center">Gender</th><?php }?>
			                <?php if(isset($form['p_address'])){?><th class="font-weight-bold text-center">Address</th><?php }?>
			                <?php if(isset($form['p_advance'])){?><th class="font-weight-bold text-center">Advance Amount</th><?php }?>
			                <?php if(isset($form['p_regd'])){?><th class="font-weight-bold text-center">Registration Date</th><?php }?>
                			<?php if(isset($form['p_feer'])){?><th class="font-weight-bold text-center">Fee Received</th><?php }?>
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
				            	<td colspan="4"><span class="font-weight-semibold text-danger">No student admitted in this class!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($rows as $row){
				        		$total_class_fee+=$row['fee'];
				        	?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td>
				                	<?php if(isset($form['p_img'])){?>
				                		<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$row['image'];?>" class="rounded m-1" width="28" height="26" alt="">
				                	<?php }?>
				                	<strong><?php print ucwords(strtolower($row['name']));?></strong>
			                	</td>
			                	<td class="text-center"><?php print ucwords(strtolower($row['father_name']));?></td>
				                <?php if(count($sections)>0){ ?>
					                <td class="text-center"><?php print $row['section'];?></td>
					            <?php } ?>
				                <td class="text-center"><?php print $row['roll_no'];?></td>
				                <?php if(isset($form['p_gmobile'])){?>
			                	<td class="text-center"><?php print $row['guardian_mobile'];?></td>
				                <?php }?>

				                <?php if(isset($form['p_fee'])){?>
			                	<td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['fee'];?></td>
				                <?php }?>
			                	<?php if(isset($form['p_session'])){?>
			                		<td class="text-center"><?php print $sessions[$row['session_id']];?></td><?php }?>

			                	<?php if(isset($form['p_fname'])){?><td class="text-center"><?php print $row['father_name'];?></td><?php }?>
			                	<?php if(isset($form['p_stdid'])){?><td class="text-center"><?php print $row['student_id'];?></td><?php }?>
			                	<?php if(isset($form['p_bg'])){?><td class="text-center"><?php print strtoupper($row['blood_group']);?></td><?php }?>
			                	<?php if(isset($form['p_session'])){?><td class="text-center"><?php print $sessions[$row['session_id']];?></td><?php }?>
			                	<?php if(isset($form['p_mobile'])){?><td class="text-center"><?php print $row['mobile'];?></td><?php }?>
			                	<?php if(isset($form['p_dob'])){?><td class="text-center"><?php print $row['date_of_birth'];?></td><?php }?>
			                	<?php if(isset($form['p_admission'])){?><td class="text-center"><?php print $row['admission_no'];?></td><?php }?>
			                	<?php if(isset($form['p_computer'])){?><td class="text-center"><?php print $row['computer_number'];?></td><?php }?>
			                	<?php if(isset($form['p_family'])){?><td class="text-center"><?php print $row['family_number'];?></td><?php }?>
			                	<?php if(isset($form['p_emg'])){?><td class="text-center"><?php print $row['emergency_contact'];?></td><?php }?>
			                	<?php if(isset($form['p_nic'])){?><td class="text-center"><?php print $row['nic'];?></td><?php }?>
			                	<?php if(isset($form['p_fnic'])){?><td class="text-center"><?php print $row['father_nic'];?></td><?php }?>
			                	<?php if(isset($form['p_gender'])){?><td class="text-center"><?php print $row['gender'];?></td><?php }?>
			                	<?php if(isset($form['p_address'])){?><td class="text-center"><?php print $row['address'];?></td><?php }?>
			                	<?php if(isset($form['p_advance'])){?><td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['advance_amount'];?></td><?php }?>
			                	<?php if(isset($form['p_regd'])){?><td class="text-center"><?php print strtoupper($row['date']);?></td><?php }?>

				                <?php if(isset($form['p_feer'])){?>
				            	<td class="text-center"></td>
				                <?php }?>
				            </tr>
				        	<?php } ?>

		                	<?php
			        	}?>
			        </tbody>
			    </table>
			    <br>
			</div>

			<?php if(isset($form['p_analytics'])){ ?>
			<br><br>
			<div class="row">
				<div class="col-sm-4 border-solid text-center">
					<span class="text-bold">Total Fee Collection :</span>
				</div>
				<div class="col-sm-4 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_class_fee;?> </span>
				</div>
			</div>
			<?php } ?>

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
	<?php print $this->config->item('app_print_code');?>2005 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
	</div>	

	</page>

	<br>
	<?php } 
	}
	?>
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
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/details';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">
						<div class="col-md-3">
							<input type="checkbox" name="p_stdid" 
								<?php print isset($form['p_stdid']) ? 'checked':'';?>>
								Show Student ID							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_img" 
								<?php print isset($form['p_img']) ? 'checked':'';?>>
								Show Profile Image							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_fee" 
								<?php print isset($form['p_fee']) ? 'checked':'';?>>
								Show Student Fee							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_transport" 
								<?php print isset($form['p_transport']) ? 'checked':'';?>>
								Show Van Fee							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_annualfund" 
								<?php print isset($form['p_annualfund']) ? 'checked':'';?>>
								Show Annual Funds							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_section" 
								<?php print isset($form['p_section']) ? 'checked':'';?>>
								Show Class Section							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_computer" 
								<?php print isset($form['p_computer']) ? 'checked':'';?>>
								Show Computer Number							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_family" 
								<?php print isset($form['p_family']) ? 'checked':'';?>>
								Show Family Number							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_admission" 
								<?php print isset($form['p_admission']) ? 'checked':'';?>>
								Show Admission Number							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_fee" 
								<?php print isset($form['p_fee']) ? 'checked':'';?>>
								Show Student Fee							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_mobile" 
								<?php print isset($form['p_mobile']) ? 'checked':'';?>>
								Show Student Mobile							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_gmobile" 
								<?php print isset($form['p_gmobile']) ? 'checked':'';?>>
								Show Guardian Mobile							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_dob" 
								<?php print isset($form['p_dob']) ? 'checked':'';?>>
								Show Date Of Birth							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_emg" 
								<?php print isset($form['p_emg']) ? 'checked':'';?>>
								Show Emergency Contact							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_nic" 
								<?php print isset($form['p_nic']) ? 'checked':'';?>>
								Show National ID							
						</div>			
						<div class="col-md-3">
							<input type="checkbox" name="p_fnic" 
								<?php print isset($form['p_fnic']) ? 'checked':'';?>>
								Show Father National ID							
						</div>			
						<div class="col-md-3">
							<input type="checkbox" name="p_fname" 
								<?php print isset($form['p_fname']) ? 'checked':'';?>>
								Show Father Name							
						</div>			
						<div class="col-md-3">
							<input type="checkbox" name="p_gname" 
								<?php print isset($form['p_gname']) ? 'checked':'';?>>
								Show Guardian Name							
						</div>			
						<div class="col-md-3">
							<input type="checkbox" name="p_gender" 
								<?php print isset($form['p_gender']) ? 'checked':'';?>>
								Show Gender							
						</div>			
						<div class="col-md-3">
							<input type="checkbox" name="p_address" 
								<?php print isset($form['p_address']) ? 'checked':'';?>>
								Show Address							
						</div>			
						<div class="col-md-3">
							<input type="checkbox" name="p_advance" 
								<?php print isset($form['p_advance']) ? 'checked':'';?>>
								Show Advance Balance							
						</div>			
						<div class="col-md-3">
							<input type="checkbox" name="p_regd" 
								<?php print isset($form['p_regd']) ? 'checked':'';?>>
								Show Registration Date							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_session" 
								<?php print isset($form['p_session']) ? 'checked':'';?>>
								Show Current Session							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_feer" 
								<?php print isset($form['p_feer']) ? 'checked':'';?>>
								Show Free Received Column							
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
						<div class="col-sm-3">
							<label class="text-muted">Gender</label>          
							<select class="form-control select" name="gender" data-fouc>
						    <option value="" />All Genders
						    <option value="male" <?php print isset($form['gender'])&&$form['gender']=='male'?'selected':'';?>/>Male
						    <option value="female" <?php print isset($form['gender'])&&$form['gender']=='female'?'selected':'';?>/>Female
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
