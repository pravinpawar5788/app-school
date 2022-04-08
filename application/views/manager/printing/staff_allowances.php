<?php
// print_r($form); 
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$form['usr']);
// if(isset($form['role']) && !empty($form['role'])){$filter['role_id']=$form['role'];}
$params=array();
$rows=$this->stf_allownce_m->get_rows($filter,$params);
$member=$this->staff_m->get_by(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['usr']),true);

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
				                <th class="font-weight-semibold text-center">Title</th>
				                <?php if(isset($form['p_desc'])){?>
				                	<th class="font-weight-semibold text-center">Description</th><?php }?>
				                <th class="font-weight-semibold text-center">Amount</th>
				                <th class="font-weight-semibold text-center">Start Date</th>
				            </tr>
				        </thead>
				        <tbody>

				        	<?php
				        	$i=0;
				        	$total_amount=0;
				        	if(count($rows)<1){
				        		?>
				        		<tr>
					            	<td colspan="4"><span class="font-weight-semibold text-danger"><?php print ucwords(strtolower($member->name)); ?> is not yet receiving any allowance!</span></td>			                
					            </tr>
				        	<?php
				        	}else{
					        	foreach($rows as $row){
					        		$total_amount+=$row['amount'];
					        	?>
					            <tr>
					            	<td class="text-center"><?php print ++$i;?></td>
					                <td class="text-center"><?php print $row['title'];?></td>
				                	<?php if(isset($form['p_desc'])){?>
				                		<td class="text-center"><?php print $row['description'];?></td><?php }?>
					                <td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['amount'];?></td>
					                <td class="text-center"><?php print $row['date'];?></td>
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
				<div class="col-sm-3 border-solid text-center">
					<span class="text-bold"> Total Allowances </span>
				</div>
				<div class="col-sm-3 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_amount;?> </span>
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
	<?php print $this->config->item('app_print_code');?>1002 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
	</div>	

	</page>

	</div>
	<!-- ------------------------------/printing-------------------------------------------------------- -->
</div>
<!-- /content area -->
<!-- Footer -->
<?php $this->load->view($LIB_VIEW_DIR.'includes/footer_inc');?>
<!-- /footer -->
</div>
<!-- /main content -->

<!-- create voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/allowances';?>">
				<input type="hidden" name="usr" value="<?php print isset($form['usr'])?$form['usr']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_desc" 
								<?php print isset($form['p_desc']) ? 'checked':'';?>>
								Show Description						
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
