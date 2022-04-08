<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['status']) && !empty($form['status'])){$filter['status']=$form['status'];}
if(isset($form['nstatus']) && !empty($form['nstatus'])){$filter['status <>']=$form['nstatus'];}
if(isset($form['role']) && !empty($form['role'])){$filter['role_id']=$form['role'];}
if(isset($form['gender']) && !empty($form['gender'])){$filter['gender']=$form['gender'];}
if(isset($form['blood_group']) && !empty($form['blood_group'])){$filter['blood_group']=$form['blood_group'];}
if(isset($form['salary']) && !empty($form['salary'])){$filter['salary >']=$form['salary'];}
$params=array();
if(isset($form['search']) && !empty($form['search'])){
	$like=array();
    $search=array('name','guardian_name','staff_id','mobile');
	foreach($search as $val){$like[$val]=$form['search'];} 
	$params['like']=$like;
}
$params['orderby']='name ASC';
$rows=$this->staff_m->get_rows($filter,$params);
$roles=$this->stf_role_m->get_rows(array('status'=>$this->stf_role_m->STATUS_ACTIVE),array('orderby'=>'title ASC') );

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
	      <div class="page" style="line-height: 1;">

			<div class="row">
				<div class="col-sm-12">				
				    <table class="table table-sm">
				        <thead>
				            <tr>
				                <th class="font-weight-semibold text-center" width="5%">#</th>
				                <?php if(isset($form['p_stfid'])){?>
				                	<th class="font-weight-semibold text-center">Staff ID</th>
				                <?php }?>
				                <th class="font-weight-semibold text-left">Name</th>
				                <?php if(isset($form['p_mobile'])){?>
					                <th class="font-weight-semibold">Contact Number</th>
					                <?php }?>
				                <?php if(isset($form['p_grd'])){?>
				                	<th class="font-weight-semibold text-center">Father / Husband</th><?php }?>
				                <?php if(isset($form['p_bg'])){?>
				                	<th class="font-weight-semibold text-center">Blood Group</th><?php }?>
				                <?php if(isset($form['p_nic'])){?>
				                	<th class="font-weight-semibold text-center">Natioabal ID</th><?php }?>
				                <?php if(isset($form['p_sal'])){?>
				                	<th class="font-weight-semibold text-center">Salary</th><?php }?>
				                <?php if(isset($form['p_email'])){?>
				                	<th class="font-weight-semibold text-center">Email</th><?php }?>
				            </tr>
				        </thead>
				        <tbody>
				        	<?php
				        	$i=0;
				        	$total_salary=0;
				        	if(count($rows)<1){
				        		?>
				        		<tr>
					            	<td colspan="4"><span class="font-weight-semibold text-danger">No Recrod Found!</span></td>			                
					            </tr>
				        	<?php
				        	}else{
					        	foreach($rows as $row){
					        		$total_salary+=floatval($row['salary']);
					        	?>
					            <tr>
					            	<td class="text-center"><?php print ++$i;?></td>
				                	<?php if(isset($form['p_stfid'])){?>
				                		<td class="text-center"><?php print $row['staff_id'];?></td><?php }?>
					                <td class="text-left">
				                	<?php if(isset($form['p_img'])){?>
				                		<img src="<?php print $this->UPLOADS_ROOT.'images/staff/profile/'.$row['image'];?>" class="rounded-circle mr-1" width="28" height="26" alt="">
				                	<?php }?>
				                	<strong><?php print ucwords(strtolower($row['name']));?></strong>
				                	</td>				                	
				                	<?php if(isset($form['p_mobile'])){?>
				                	<td class="text-center"><?php print $row['mobile'];?></td><?php }?>
				                	<?php if(isset($form['p_grd'])){?>
				                		<td class="text-center"><?php print $row['guardian_name'];?></td><?php }?>
				                	<?php if(isset($form['p_bg'])){?><td><?php print strtoupper($row['blood_group']);?></td><?php }?>
				                	<?php if(isset($form['p_nic'])){?>
				                		<td class="text-center"><?php print $row['cnic'];?></td><?php }?>

				                	<?php if(isset($form['p_sal'])){?>
			                		<td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['salary'];?></td>
			                		<?php }?>
				                	<?php if(isset($form['p_email'])){?>
				                		<td class="text-center"><?php print $row['email'];?></td><?php }?>
					            </tr>
					        	<?php } 
				        	}?>
				        </tbody>
				    </table>
				    <br>
				</div>
			</div>
			<?php if(isset($form['p_analytics'])){?>
			<div class="row">
				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold"> Total Members </span>
				</div>
				<div class="col-sm-2 grd-bg-orange text-center">
					<span class="text-bold"> <?php print $i;?> </span>
				</div>
				<div class="col-sm-2 border-solid text-center">
					<span class="text-bold"> Total Salary </span>
				</div>
				<div class="col-sm-2 grd-bg-orange text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_salary;?> </span>
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
	<?php print $this->config->item('app_print_code');?>1001 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
	</div>	

	</page>

	</div>
	<!-- ------------------------------/printing------------------------------------------------ -->
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
							<input type="checkbox" name="p_stfid" 
								<?php print isset($form['p_stfid']) ? 'checked':'';?>>
								Show Staff ID							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_img" 
								<?php print isset($form['p_img']) ? 'checked':'';?>>
								Show Profile Image							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_bg" 
								<?php print isset($form['p_bg']) ? 'checked':'';?>>
								Show Blood Group							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_mobile" 
								<?php print isset($form['p_mobile']) ? 'checked':'';?>>
								Show Mobile Number							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_email" 
								<?php print isset($form['p_email']) ? 'checked':'';?>>
								Show Email Address							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_grd" 
								<?php print isset($form['p_grd']) ? 'checked':'';?>>
								Show Father/Husband Name							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_nic" 
								<?php print isset($form['p_nic']) ? 'checked':'';?>>
								Show National ID							
						</div>		
						<div class="col-md-3">
							<input type="checkbox" name="p_sal" 
								<?php print isset($form['p_sal']) ? 'checked':'';?>>
								Show Salary							
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
							<input type="checkbox" name="status" value="active" 
								<?php print isset($form['status']) ? 'checked':'';?>>
								Only Show Active Members							
						</div>				
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
