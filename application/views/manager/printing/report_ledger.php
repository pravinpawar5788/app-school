<?php
/// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$accounting_periods=$this->accounts_period_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'mid DESC');
$accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));

//////////////////////////////////////////////////////////////////

// $accounting_periods=$this->accounts_period_m->get_rows(array('campus_id'=>$this->CAMPUSID));
$period_id=$this->accounts_period_m->get_current_period_id($this->CAMPUSID);
isset($form['period']) && !empty($form['period']) ? $period_id= $form['period'] : '';
$selected_period=$this->accounts_period_m->get_by_primary($period_id);
$filter=array('campus_id'=>$this->CAMPUSID);
$date=$this->expense_m->date;
$month='';
$year='';
$filter['period_id']=$period_id;
if(isset($form['date']) && !empty($form['date'])){$date=$form['date'];}
if(isset($form['month']) && !empty($form['month'])){$month=$form['month'];}
if(isset($form['year']) && !empty($form['year'])){$year=$form['year'];}
if(isset($form['debit']) && !empty($form['debit'])){$filter['debit_account_id']=$form['debit'];}
if(isset($form['credit']) && !empty($form['credit'])){$filter['credit_account_id']=$form['credit'];}
if(empty($month) && empty($year)){
	$filter['date']=$date;	
}else{
	$date='';
	if(!empty($month)){	$filter['month']=$month;}
	if(!empty($year)){$filter['year']=$year;}
}
$params=array();
$rows=$this->accounts_ledger_m->get_rows($filter,$params);

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
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="font-weight-bold text-center" width="5%">#</th>
			                <th class="font-weight-bold">Title</th>
			                <th class="font-weight-bold">Debit Amount</th>
			                <th class="font-weight-bold">Credit Amount</th>
			                <th class="font-weight-bold">Date</th>
			            </tr>
			        </thead>
			        <tbody>
			        	<?php
			        	$i=0;
			        	$total_debit_amount=0;
			        	$total_credit_amount=0;
			        	if(count($rows)<1){
			        		?>
			        		<tr>
				            	<td colspan="4"><span class="font-weight-semibold text-danger">No Record Found!</span></td>			                
				            </tr>
			        	<?php
			        	}else{
				        	foreach($rows as $row){
				        		$total_debit_amount+=$row['debit_amount'];
				        		$total_credit_amount+=$row['credit_amount'];
				        	?>
				            <tr>
				            	<td class="text-center"><?php print ++$i;?></td>
				                <td class="text-center"><?php print $row['title'];?></td>
			                	<td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['debit_amount'].' ('.$accounts[$row['debit_account_id']].')';?></td>
			                	<td class="text-center"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$row['credit_amount'].' ('.$accounts[$row['credit_account_id']].')';?></td>
			                	<td class="text-center"><?php print $row['date'];?></td>
				            </tr>
				        	<?php } 
			        	}?>
			        </tbody>
			    </table>
			    <br>
			</div>
			<?php if(isset($form['p_analytics'])){?>
			<br><br>
			<div class="row">
				<div class="col-sm-3 border-solid text-center">
					<span class="text-bold">Total Debit Amount:</span>
				</div>
				<div class="col-sm-3 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_debit_amount;?> </span>
				</div>
				<div class="col-sm-3 border-solid text-center">
					<span class="text-bold">Total Credit Amount:</span>
				</div>
				<div class="col-sm-3 grd-bg-orange border-white text-center">
					<span class="text-bold"> <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_credit_amount;?> </span>
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
	<?php print $this->config->item('app_print_code');?>5003 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
<div id="choose-date" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-ms">
		<div class="modal-content">
			<form method="get" action="<?php print $this->LIB_CONT_ROOT.'finance/printing/report/';?>">
				<input type="hidden" name="rpt" value="ledger">
			<div class="modal-header bg-success">
				<h6 class="modal-title">Choose date...</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12">
							<label class="text-muted">Choose Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" name="date" class="form-control form-control-sm datepicker" placeholder="dd-MMM-yyyy e.g 25-MAR-2019">
								<div class="form-control-feedback form-control-feedback-sm">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>						
					</div>
				</div>


			</div>
			<div class="modal-footer">
				<a class="btn btn-link" data-dismiss="modal">Close</a>
				<button type="submit" class="btn btn-success"><span class="font-weight-bold"> Apply Filter</span>
				</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- /create voucher modal -->

<!-- create voucher modal -->
<div id="view" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/report';?>">
				<input type="hidden" name="rpt" value="<?php print isset($form['rpt'])?$form['rpt']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_type" 
								<?php print isset($form['p_type']) ? 'checked':'';?>>
								Show Type							
						</div>
						<div class="col-md-3">
							<input type="checkbox" name="p_desc" 
								<?php print isset($form['p_desc']) ? 'checked':'';?>>
								Show Detail		
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
							<label class="text-muted">Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md datepicker" placeholder="Date" name="date">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>
						<!-- <div class="col-sm-3">
							<label class="text-muted">Gender</label>          
							<select class="form-control select" name="gender" data-fouc>
						    <option value="" />All Genders
						    <option value="male" <?php print isset($form['gender'])&&$form['gender']=='male'?'selected':'';?>/>Male
						    <option value="female" <?php print isset($form['gender'])&&$form['gender']=='female'?'selected':'';?>/>Female
							</select>
						</div>	 -->
					<div class="col-sm-3">
						<label class="text-muted">Accounting Period</label>          
						<select class="form-control select" name="period" data-fouc>
						<option value="" />Current Period
						<?php foreach ($accounting_periods as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['period'])&&$form['period']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
						</select>
					</div>	
					<div class="col-sm-3">
						<label class="text-muted">Debit Type</label>          
						<select class="form-control select-search" name="debit" data-fouc>
						<option value="" />All Types
						<?php foreach ($accounts as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['debit'])&&$form['debit']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
						</select>
					</div>	
					<div class="col-sm-3">
						<label class="text-muted">Credit Type</label>          
						<select class="form-control select-search" name="credit" data-fouc>
						<option value="" />All Types
						<?php foreach ($accounts as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['credit'])&&$form['credit']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
						</select>
					</div>		
					<div class="col-sm-3">
						<label class="text-muted">Year</label>          
						<select class="form-control select" name="year" data-fouc>
						<option value="" />Current Year
						<?php 
							$i=0;
							$y=$this->user_m->year;
							while($y>=$this->ORG->year){
								$i++;if($i>25){break;}
								?>
								<option value="<?php print $y;?>" <?php print isset($form['year'])&&$form['year']==$y?'selected':'';?>/><?php print $y;?>
								<?php
								$y--;
							}
						?>
						</select>
					</div>		
					<div class="col-sm-3">
						<label class="text-muted">Month</label>          
						<select class="form-control select" name="month" data-fouc>
						<option value="" />Current Month
						<?php 
							$mnth=$this->user_m->month;
							for($m=1;$m<=12;$m++){
								?>           
								<option value="<?php print $m;?>" <?php print isset($form['month'])&&$form['month']==$m?'selected':'';?>/><?php print month_string($m);?>
								<?php 
							} 
							?>
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

