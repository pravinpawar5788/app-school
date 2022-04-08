<?php
/// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
$filter=array('campus_id'=>$this->CAMPUSID);
// $classes=$this->class_m->get_rows($filter,array('orderby'=>'display_order ASC','select'=>'mid,title'));
//////////////////////////////////////////////////////////////////
$status_types=$this->student_m->get_status_types();
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$class_sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');
$sessions=$this->session_m->get_values_array('mid','title',array(),'mid DESC');
$activeSession=$this->session_m->getActiveSession();
//////////////////////////////////////////////////////////////////
if(isset($form['haveadvbal']) && !empty($form['haveadvbal'])){$filter['advance_amount >']=0;}
if(isset($form['freestd']) && !empty($form['freestd'])){$filter['fee <']=1;}
if(isset($form['fee']) && !empty($form['fee'])){$filter['fee_type']=$form['fee'];}
if(isset($form['status']) && !empty($form['status'])){$filter['status']=$form['status'];}
if(isset($form['nstatus']) && !empty($form['nstatus'])){$filter['status <>']=$form['nstatus'];}
if(isset($form['session']) && !empty($form['session'])){$filter['session_id']=$form['session'];}
if(isset($form['class']) && !empty($form['class'])){$filter['class_id']=$form['class'];}
if(isset($form['section']) && !empty($form['section'])){$filter['section_id']=$form['section'];}
if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
if(isset($form['admission']) && !empty($form['admission'])){$filter['admission_session_id']=$form['admission'];}
if(isset($form['alumni']) ){$filter['status']=$this->student_m->STATUS_ALUMNI;}else{$filter['class_id >']=0;}
if(isset($form['blood_group']) && !empty($form['blood_group'])){$filter['blood_group']=$form['blood_group'];}
$params=array();
if(isset($form['search']) && !empty($form['search'])){
	$like=array();
    $search=array('name','father_name','father_nic','guardian_name','student_id','nic','mobile','blood_group','gender','roll_no');
	foreach($search as $val){$like[$val]=$form['search'];} 
	$params['like']=$like;
}
$params['orderby']='class_id ASC, roll_no ASC';
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

	
	<!-- Top right menu -->
	<!-- <ul class="fab-menu fab-menu-absolute fab-menu-top-right" data-fab-toggle="click" id="fab-menu-affixed-demo-right">
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
	</ul> -->
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


			<div class="row">				
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="font-weight-semibold text-center th_max_5">#</th>
			                <?php if(isset($form['p_stdid'])){?>
			                	<th class="font-weight-semibold text-center th_max_7">Std.Id</th><?php }?>
			                <?php if(isset($form['p_admission'])){?>
			                	<th class="font-weight-semibold text-center th_max_5">Adm.No</th><?php }?>
			                <?php if(isset($form['p_computer'])){?>
			                	<th class="font-weight-semibold text-center th_max_7">Cmp.No</th><?php }?>
			                <?php if(isset($form['p_family'])){?>
			                	<th class="font-weight-semibold text-center th_max_5">Fam.No</th><?php }?>
			                <th class="font-weight-semibold <?php print isset($form['p_img'])? 'th_min_20':'th_min_17'; ?>">Student Name</th>
			                <?php if(isset($form['p_fname'])){?>
			                	<th class="font-weight-semibold text-center th_min_15">Father Name</th><?php }?>
			                <?php if(isset($form['p_class'])){?>
			                	<th class="font-weight-semibold text-center">Class</th><?php }?>
			                <?php if(isset($form['p_section'])){?>
			                	<th class="font-weight-semibold text-center">Sec.</th><?php }?>
			                <?php if(isset($form['p_gname'])){?>
			                	<th class="font-weight-semibold text-center th_min_17">Guardian Name</th><?php }?>
			                <?php if(isset($form['p_roll'])){?>
			                	<th class="font-weight-semibold text-center">Roll</th><?php }?>
			                <?php if(isset($form['p_bg'])){?>
			                	<th class="font-weight-semibold text-center">B.Grp</th><?php }?>
			                <?php if(isset($form['p_session'])){?>
			                	<th class="font-weight-semibold text-center">Session</th><?php }?>
			                <?php if(isset($form['p_fee'])){?>
			                	<th class="font-weight-semibold text-center">Fee</th><?php }?>
			                <?php if(isset($form['p_advance'])){?>
			                	<th class="font-weight-semibold text-center">Adv.Bal</th><?php }?>
			                <?php if(isset($form['p_transport'])){?>
			                	<th class="font-weight-semibold text-center">Tr.Fee</th><?php }?>
			                <?php if(isset($form['p_annualfund'])){?>
			                	<th class="font-weight-semibold text-center">AnnFun</th><?php }?>
			                <?php if(isset($form['p_mobile'])){?>
			                	<th class="font-weight-semibold text-center">Mobile</th><?php }?>
			                <?php if(isset($form['p_gmobile'])){?>
			                	<th class="font-weight-semibold text-center">G.Mobile</th><?php }?>
			                <?php if(isset($form['p_dob'])){?>
			                	<th class="font-weight-semibold text-center">DOB</th><?php }?>
			                <?php if(isset($form['p_emg'])){?>
			                	<th class="font-weight-semibold text-center">Emg.No</th><?php }?>
			                <?php if(isset($form['p_nic'])){?>
			                	<th class="font-weight-semibold text-center">NIC.No</th><?php }?>
			                <?php if(isset($form['p_fnic'])){?>
			                	<th class="font-weight-semibold text-center">F.NIC.NO</th><?php }?>
			                <?php if(isset($form['p_gender'])){?>
			                	<th class="font-weight-semibold text-center">Gndr</th><?php }?>
			                <?php if(isset($form['p_address'])){?>
			                	<th class="font-weight-semibold text-center">Address</th><?php }?>
			                <?php if(isset($form['p_regd'])){?>
			                	<th class="font-weight-semibold text-center">Reg.Date</th><?php }?>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
			        	$i=0;
			        	$total_annualfunds=0;
			        	$total_transport=0;
			        	$total_fee_monthly=0;
			        	$total_fee_term=0;
			        	$total_advance=0;
			        	if(count($rows)<1){
			        		?>
			        		<tr>
				            	<td colspan="3"><span class="font-weight-semibold text-danger">No Record Found!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($rows as $row){

				        		$total_advance+=$row['advance_amount'];
				        		$total_transport+=$row['transport_fee'];
				        		$total_annualfunds+=$row['annual_fund'];
				        		if($row['fee_type']==$this->student_m->FEE_TYPE_FIXED){
				        			$total_fee_term+=$row['fee'];}
				        		if($row['fee_type']==$this->student_m->FEE_TYPE_MONTHLY){
				        			$total_fee_monthly+=$row['fee'];}
					        	?>
					            <tr>
					            	<td class="text-center"><?php print ++$i;?></td>
				                	<?php if(isset($form['p_stdid'])){?>
				                		<td class="text-center"><?php print $row['mid'];?></td><?php }?>
				                	<?php if(isset($form['p_admission'])){?>
				                		<td class="text-center"><?php print $row['admission_no'];?></td><?php }?>
				                	<?php if(isset($form['p_computer'])){?>
				                		<td class="text-center"><?php print $row['computer_number'];?></td><?php }?>
				                	<?php if(isset($form['p_family'])){?>
				                		<td class="text-center"><?php print $row['family_number'];?></td><?php }?>
					                <td class="text-left">
					                	<?php if(isset($form['p_img'])){?>
					                		<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$row['image'];?>" class="mr-1" width="28" height="26" alt="">
					                	<?php }?>
					                	<?php print ucwords(strtolower($row['name']));?>
				                	</td>
				                	<?php if(isset($form['p_fname'])){?>
				                		<td class="text-left"><?php print $row['father_name'];?></td><?php }?>
				                	<?php if(isset($form['p_class'])){?>
					                <td class="text-center"><?php print $row['class_id']>0 ? $classes[$row['class_id']] : 'Alumni';?></td><?php }?>
					                <?php if(isset($form['p_section'])){?>
					                	<td class="text-center"><?php print $row['section_id']>0 && array_key_exists($row['section_id'], $class_sections) ? $class_sections[$row['section_id']] : '';?></td><?php }?>
				                	<?php if(isset($form['p_gname'])){?>
				                		<td class="text-left"><?php print $row['guardian_name'];?></td><?php }?>
				                	<?php if(isset($form['p_roll'])){?>
				                		<td class="text-center"><?php print strtoupper($row['roll_no']);?></td><?php }?>
				                	<?php if(isset($form['p_bg'])){?>
				                		<td class="text-center"><?php print strtoupper($row['blood_group']);?></td><?php }?>
				                	<?php if(isset($form['p_session'])){?>
				                		<td class="text-center"><?php print $sessions[$row['session_id']];?></td><?php }?>
				                	<?php if(isset($form['p_fee'])){?>
				                		<td class="text-center"><?php print ceil($row['fee']);?></td><?php }?>
				                	<?php if(isset($form['p_advance'])){?>
				                		<td class="text-center"><?php print ceil($row['advance_amount']);?></td><?php }?>
				                	<?php if(isset($form['p_transport'])){?>
				                		<td class="text-center"><?php print ceil($row['transport_fee']);?></td><?php }?>
				                	<?php if(isset($form['p_annualfund'])){?>
				                		<td class="text-center"><?php print ceil($row['annual_fund']);?></td><?php }?>
				                	<?php if(isset($form['p_mobile'])){?>
				                		<td class="text-center"><?php print $row['mobile'];?></td><?php }?>
				                	<?php if(isset($form['p_gmobile'])){?>
				                		<td class="text-center"><?php print $row['guardian_mobile'];?></td><?php }?>
				                	<?php if(isset($form['p_dob'])){?>
				                		<td class="text-center"><?php print $row['date_of_birth'];?></td><?php }?>
				                	<?php if(isset($form['p_emg'])){?>
				                		<td class="text-center"><?php print $row['emergency_contact'];?></td><?php }?>
				                	<?php if(isset($form['p_nic'])){?>
				                		<td class="text-center"><?php print $row['nic'];?></td><?php }?>
				                	<?php if(isset($form['p_fnic'])){?>
				                		<td class="text-center"><?php print $row['father_nic'];?></td><?php }?>
				                	<?php if(isset($form['p_gender'])){?>
				                		<td class="text-center"><?php print $row['gender'];?></td><?php }?>
				                	<?php if(isset($form['p_address'])){?>
				                		<td class="text-center"><?php print $row['address'];?></td><?php }?>
				                	<?php if(isset($form['p_regd'])){?>
				                		<td class="text-center"><?php print strtoupper($row['date']);?></td><?php }?>
					            </tr>
					        	<?php 
					        } 
			        	}?>
			        </tbody>
			    </table>
			    <br>
			</div>


			<br><br>
			<?php if(isset($form['p_analytics'])){?>
			<div class="row">
				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold">Total Students:</span>
				</div>
				<div class="col-sm-2 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $i;?> </span>
				</div>
				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold">Total Fee:</span>
				</div>
				<div class="col-sm-2 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].($total_fee_monthly+$total_fee_term);?> </span>
				</div>

				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold">Advance Balance:</span>
				</div>
				<div class="col-sm-2 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_advance;?> </span>
				</div>
				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold">Annual Funds:</span>
				</div>
				<div class="col-sm-2 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_annualfunds;?> </span>
				</div>
				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold">Van Fee:</span>
				</div>
				<div class="col-sm-2 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_transport;?> </span>
				</div>
			</div>
			<?php }?>


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


<!-- create voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/list';?>">
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
							<input type="checkbox" name="p_analytics" 
								<?php print isset($form['p_analytics']) ? 'checked':'';?>>
								Show Analytics Footer							
						</div>						
					</div>
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-filter3 mr-2"></i>Configure Data Filters</legend>
					<div class="row">
						<div class="col-md-3">
							<input type="checkbox" name="alumni" 
								<?php print isset($form['alumni']) ? 'checked':'';?>>
								Only Show Alumni Students							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="freestd" 
								<?php print isset($form['freestd']) ? 'checked':'';?>>
								Only Show Free Students							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="admission" value="<?php print $activeSession->mid;?>" 
								<?php print isset($form['admission']) ? 'checked':'';?>>
								Only Show New Admissions							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="haveadvbal" 
								<?php print isset($form['haveadvbal']) ? 'checked':'';?>>
								Only Show with advance balance							
						</div>					
					</div>
					<div class="row">
						<div class="col-sm-3">
							<label class="text-muted">Session</label>
							<select class="form-control select" name="session" data-fouc>           
						    <option value="" />All Sessions
							<?php foreach ($sessions as $key=>$val){?>            
							    <option value="<?php print $key;?>" <?php print isset($form['session'])&&$form['session']==$key?'selected':'';?>/><?php print $val;?>
						    <?php }?>
							</select>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Status</label>
							<select class="form-control select" name="status" data-fouc>          
						    <option value="" />All Status
							<?php foreach ($status_types as $key=>$val){?>            
							    <option value="<?php print $key;?>" <?php print isset($form['status'])&&$form['status']==$key?'selected':'';?>/><?php print $val;?>
						    <?php }?>
							</select>
						</div>		
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
