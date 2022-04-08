<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$scale=intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_FONT_SACLE]);
if(isset($form['scale']) && !empty($form['scale']) ){$scale=intval($form['scale']);}
//////////////////////////////////////////////////////////////////
$sessions=$this->session_m->get_values_array('mid','title',array());
$params=array();
/////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);

if(isset($form['vid']) && !empty($form['vid'])){
	$filter['mid']=$form['vid'];
}else{
	$filter['month_number']=$this->std_fee_voucher_m->month_number;
	if(isset($form['type']) && !empty($form['type'])){$filter['status']=$form['type'];}
	if(isset($form['month']) && !empty($form['month'])){$filter['month_number']=$form['month'];}
}
$params['orderby']='staff_id ASC, mid ASC';
$vouchers=$this->stf_pay_voucher_m->get_rows($filter,$params);
?>
<!-- Main content -->
<div class="content-wrapper">

				
<!-- Content area -->
<div class="content" >

	
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
	<?php  
		$vi=0;
		foreach($vouchers as $voucher){
		$vi++;
		$staff=$this->staff_m->get_by_primary($voucher['staff_id']);
		
		//when a single voucher is printed month number is set to voucher month number
		if(empty($filter['month_number'])){$filter['month_number']=$voucher['month_number'];}
		$rows=$this->stf_pay_entry_m->get_rows(array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher['voucher_id'],'month_number'=>$filter['month_number']),array('select'=>'mid,remarks,amount,operation,date','orderby'=>'mid ASC'));

		?>

	<?php 
	$template='';
	if(isset($form['template'])&&!empty($form['template'])){$template=$form['template'];}
	switch (strtolower($template)) {
		
		case 'single':{
			///start of simple template
			$tbl_rows=3;	//how may rows to print for this table				
			if(isset($form['tblrows'])){$tbl_rows=intval($form['tblrows']);}
			$org_logo_size=32;
			$std_img_size=48;
			?>

			<?php if($vi<=1){//only at start page
				?>
			<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
			<div class="page">
				<div class="row avoid-page-break">	
			<?php } ?>
						
					<div class="col-sm-6">
						<div class="card card-solid editable">
							<div class="card-header bg-transparent header-elements-inline">
								<h6 class="card-title text-center horizontal-center">
									<center><span class="font-weight-bold font-1-1em">
									<?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span></center>
									<center><span class="ml-1 font-0-8em">
									<?php print ucwords($this->CAMPUS->address).' Ph:'.$this->CAMPUS->contact_number.'';?></span>
									</center>
								</h6>
							</div>
							<card class="body">	

								<div class="row">
									<div class="col-sm-12">
										<p><br>
										<span class="line mtb-1">
						                	<?php 
					                		$img_margin=1;
						                	?>
											<span>
												<img src="<?php print $this->UPLOADS_ROOT.'images/staff/profile/'.$staff->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
											</span>
											Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.1));?>">
												<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
											</span>
										</span>
										<span class="line mtb-1">
											Member / Staff Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(62-($scale*1.8));?>">
												<?php print ucwords(strtolower($staff->name)) ?></span>
										</span>
										<span class="line mtb-1">
											Father's / Husband Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(57-($scale*1.8));?>">
												<?php print ucwords(strtolower($staff->guardian_name)) ?></span>
										</span>
										<span class="line mtb-1">
											Staff ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.1));?>"><?php print $staff->staff_id ?></span>
											Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.1));?>">
												<?php print $voucher['voucher_id'];?>
											</span>
										</span>
											
										</p>
									</div>
								</div>	
								<div class="row">
									<div class="col-sm-12">	
								    <table class="table table-sm">
								        <thead>
								            <tr>
								                <th class="font-weight-semibold text-center w-70">Particulars</th>
								                <th class="font-weight-semibold text-center">Amount</th>
								            </tr>
								        </thead>
								        <tbody>
											<?php
								        	$rc=10;
								        	$i=0;
								        	if(count($rows)>0){
								        	foreach($rows as $row){
								        		$i++;
											?>
											<tr>
												<td class="vchr-td-height">
													<?php
													if (strpos($row['remarks'], 'Arrears From') !== false) {
														print 'Arrears';
													}else{ print ucwords(strtolower($row['remarks']));}
													?>
													<?php if($row['operation']==$this->std_fee_entry_m->OPT_MINUS){?>
													<span class="plr-2"><?php print $row['date'];?></span>
													<?php } ?>
												</td>
												<td class="vchr-td-height">
													<?php print $row['operation']==$this->std_fee_entry_m->OPT_MINUS ? '-':'';?>
													<?php print $row['amount'];?>
												</td>
											</tr>
								        	<?php } 
								        	if($tbl_rows-$i >0){
								        		for ($j=0; $j < ($tbl_rows-$i); $j++) { 
							        				?>
													<tr>
														<td class="vchr-td-height"></td>
														<td class="vchr-td-height"></td>
													</tr>
										        	<?php
							        			}
								        	}
							        		}else{
							        			for ($j=0; $j < $tbl_rows; $j++) { 
							        				?>
													<tr>
														<td class="vchr-td-height"></td>
														<td class="vchr-td-height"></td>
													</tr>
										        	<?php
							        			}
							        		}
							        		?>
								        </tbody>
								        <tfoot>
								            <tr>
								                <th class="font-weight-semibold text-center w-70">Total Payable</th>
								                <th class="font-weight-semibold text-center">
								                	<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->stf_pay_entry_m->get_voucher_amount($voucher['voucher_id'],$this->stf_pay_entry_m->OPT_PLUS);?>
								                </th>
								            </tr>
											<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
								            <tr>
								                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
								                <td class="font-weight-semibold text-center">
								                	<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->stf_pay_entry_m->get_voucher_amount($voucher['voucher_id']);?>
								                </td>
								            </tr>
									        <?php } ?>

								        </tfoot>
								    </table>
									</div>
								</div>
								    
							</card>
							<div class="card-footer font-0-8em">
								<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
								
							</div>
						</div>
					</div>	
			<?php if($vi%2==0){ 
				//start page
				?>

				</div>
			</div>
			</page>
			<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
			<div class="page">
				<div class="row avoid-page-break">

			<?php } ?>
			<?php if($vi >= count($vouchers)){ ?>
				</div>
			</div>
			</page>
			<?php } ?>
			<?php
			//end of simple template
		}break;	


		default:{
			///start of simple template
			$tbl_rows=3;	//how may rows to print for this table				
			if(isset($form['tblrows'])){$tbl_rows=intval($form['tblrows']);}
			$org_logo_size=32;
			$std_img_size=48;
			?>
			<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
			<div class="page">
				<div class="row avoid-page-break">	
					<div class="col-sm-6">
						<div class="card card-solid editable">
							<div class="card-header bg-transparent header-elements-inline">
								<h6 class="card-title text-center horizontal-center">
									<center><span class="font-weight-bold font-1-1em">
									<?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span></center>
									<center><span class="ml-1 font-0-8em">
									<?php print ucwords($this->CAMPUS->address).' Ph:'.$this->CAMPUS->contact_number.'';?></span>
									</center>
									<center><span class="font-weight-semibold">Staff Copy</span></center>
								</h6>
							</div>
							<card class="body">	

								<div class="row">
									<div class="col-sm-12">
										<p><br>
										<span class="line mtb-1">
						                	<?php 
					                		$img_margin=1;
						                	?>
											<span>
												<img src="<?php print $this->UPLOADS_ROOT.'images/staff/profile/'.$staff->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
											</span>
											Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.1));?>">
												<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
											</span>
										</span>
										<span class="line mtb-1">
											Member / Staff Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(62-($scale*1.8));?>">
												<?php print ucwords(strtolower($staff->name)) ?></span>
										</span>
										<span class="line mtb-1">
											Father's / Husband Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(57-($scale*1.8));?>">
												<?php print ucwords(strtolower($staff->guardian_name)) ?></span>
										</span>
										<span class="line mtb-1">
											Staff ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.1));?>"><?php print $staff->staff_id ?></span>
											Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.1));?>">
												<?php print $voucher['voucher_id'];?>
											</span>
										</span>
											
										</p>
									</div>
								</div>	
								<div class="row">
									<div class="col-sm-12">	
								    <table class="table table-sm">
								        <thead>
								            <tr>
								                <th class="font-weight-semibold text-center w-70">Particulars</th>
								                <th class="font-weight-semibold text-center">Amount</th>
								            </tr>
								        </thead>
								        <tbody>
											<?php
								        	$rc=10;
								        	$i=0;
								        	if(count($rows)>0){
								        	foreach($rows as $row){
								        		$i++;
											?>
											<tr>
												<td class="vchr-td-height">
													<?php
													if (strpos($row['remarks'], 'Arrears From') !== false) {
														print 'Arrears';
													}else{ print ucwords(strtolower($row['remarks']));}
													?>
													<?php if($row['operation']==$this->std_fee_entry_m->OPT_MINUS){?>
													<span class="plr-2"><?php print $row['date'];?></span>
													<?php } ?>
												</td>
												<td class="vchr-td-height">
													<?php print $row['operation']==$this->std_fee_entry_m->OPT_MINUS ? '-':'';?>
													<?php print $row['amount'];?>
												</td>
											</tr>
								        	<?php } 
								        	if($tbl_rows-$i >0){
								        		for ($j=0; $j < ($tbl_rows-$i); $j++) { 
							        				?>
													<tr>
														<td class="vchr-td-height"></td>
														<td class="vchr-td-height"></td>
													</tr>
										        	<?php
							        			}
								        	}
							        		}else{
							        			for ($j=0; $j < $tbl_rows; $j++) { 
							        				?>
													<tr>
														<td class="vchr-td-height"></td>
														<td class="vchr-td-height"></td>
													</tr>
										        	<?php
							        			}
							        		}
							        		?>
								        </tbody>
								        <tfoot>
								            <tr>
								                <th class="font-weight-semibold text-center w-70">Total Payable</th>
								                <th class="font-weight-semibold text-center">
								                	<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->stf_pay_entry_m->get_voucher_amount($voucher['voucher_id'],$this->stf_pay_entry_m->OPT_PLUS);?>
								                </th>
								            </tr>
											<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
								            <tr>
								                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
								                <td class="font-weight-semibold text-center">
								                	<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->stf_pay_entry_m->get_voucher_amount($voucher['voucher_id']);?>
								                </td>
								            </tr>
									        <?php } ?>

								        </tfoot>
								    </table>
									</div>
								</div>
								    
							</card>
							<div class="card-footer font-0-8em">
								<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
								
							</div>
						</div>
					</div>	
					<div class="col-sm-6">
						<div class="card card-solid editable">
							<div class="card-header bg-transparent header-elements-inline">
								<h6 class="card-title text-center horizontal-center">
									<center><span class="font-weight-bold font-1-1em">
									<?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span></center>
									<center><span class="ml-1 font-0-8em">
									<?php print ucwords($this->CAMPUS->address).' Ph:'.$this->CAMPUS->contact_number.'';?></span>
									</center>
									<center><span class="font-weight-semibold">Office Copy</span></center>
								</h6>
							</div>
							<card class="body">	

								<div class="row">
									<div class="col-sm-12">
										<p><br>
										<span class="line mtb-1">
						                	<?php 
					                		$img_margin=1;
						                	?>
											<span>
												<img src="<?php print $this->UPLOADS_ROOT.'images/staff/profile/'.$staff->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
											</span>
											Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.1));?>">
												<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
											</span>
										</span>
										<span class="line mtb-1">
											Member / Staff Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(62-($scale*1.8));?>">
												<?php print ucwords(strtolower($staff->name)) ?></span>
										</span>
										<span class="line mtb-1">
											Father's / Husband Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(57-($scale*1.8));?>">
												<?php print ucwords(strtolower($staff->guardian_name)) ?></span>
										</span>
										<span class="line mtb-1">
											Staff ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.1));?>"><?php print $staff->staff_id ?></span>
											Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.1));?>">
												<?php print $voucher['voucher_id'];?>
											</span>
										</span>
											
										</p>
									</div>
								</div>	
								<div class="row">
									<div class="col-sm-12">	
								    <table class="table table-sm">
								        <thead>
								            <tr>
								                <th class="font-weight-semibold text-center w-70">Particulars</th>
								                <th class="font-weight-semibold text-center">Amount</th>
								            </tr>
								        </thead>
								        <tbody>
											<?php
								        	$rc=10;
								        	$i=0;
								        	if(count($rows)>0){
								        	foreach($rows as $row){
								        		$i++;
											?>
											<tr>
												<td class="vchr-td-height">
													<?php
													if (strpos($row['remarks'], 'Arrears From') !== false) {
														print 'Arrears';
													}else{ print ucwords(strtolower($row['remarks']));}
													?>
													<?php if($row['operation']==$this->std_fee_entry_m->OPT_MINUS){?>
													<span class="plr-2"><?php print $row['date'];?></span>
													<?php } ?>
												</td>
												<td class="vchr-td-height">
													<?php print $row['operation']==$this->std_fee_entry_m->OPT_MINUS ? '-':'';?>
													<?php print $row['amount'];?>
												</td>
											</tr>
								        	<?php } 
								        	if($tbl_rows-$i >0){
								        		for ($j=0; $j < ($tbl_rows-$i); $j++) { 
							        				?>
													<tr>
														<td class="vchr-td-height"></td>
														<td class="vchr-td-height"></td>
													</tr>
										        	<?php
							        			}
								        	}
							        		}else{
							        			for ($j=0; $j < $tbl_rows; $j++) { 
							        				?>
													<tr>
														<td class="vchr-td-height"></td>
														<td class="vchr-td-height"></td>
													</tr>
										        	<?php
							        			}
							        		}
							        		?>
								        </tbody>
								        <tfoot>
								            <tr>
								                <th class="font-weight-semibold text-center w-70">Total Payable</th>
								                <th class="font-weight-semibold text-center">
								                	<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->stf_pay_entry_m->get_voucher_amount($voucher['voucher_id'],$this->stf_pay_entry_m->OPT_PLUS);?>
								                </th>
								            </tr>
											<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
								            <tr>
								                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
								                <td class="font-weight-semibold text-center">
								                	<?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->stf_pay_entry_m->get_voucher_amount($voucher['voucher_id']);?>
								                </td>
								            </tr>
									        <?php } ?>

								        </tfoot>
								    </table>
									</div>
								</div>
								    
							</card>
							<div class="card-footer font-0-8em">
								<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			</page>
			<?php
			//end of simple template
		}break;	
	}
	?>
	<?php } ?>
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
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/payslip';?>">
				<?php if(isset($form['vid'])){?> 
				<input type="hidden" name="vid" value="<?php print isset($form['vid'])?$form['vid']:'';?>">
				<?php } ?>
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<div class="col-md-3">
							<input type="checkbox" name="p_img" 
								<?php print isset($form['p_img']) ? 'checked':'';?>>
								Show Profile Image							
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
							<label class="text-muted">Template</label>          
							<select class="form-control select" name="template" data-fouc>
						    <option value="" />Default Template
						    <option value="single" <?php print isset($form['template'])&&$form['template']=='single' ?'selected':'';?>/>Single Copy
							</select>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Type</label>          
							<select class="form-control select" name="type" data-fouc>
						    <option value="" />All Types
						    <option value="<?php print $this->std_fee_voucher_m->STATUS_UNPAID ?>" <?php print isset($form['type'])&&$form['type']==$this->std_fee_voucher_m->STATUS_UNPAID ?'selected':'';?>/>Unpaid Vouchers
						    <option value="<?php print $this->std_fee_voucher_m->STATUS_PAID ?>" <?php print isset($form['type'])&&$form['type']==$this->std_fee_voucher_m->STATUS_PAID ?'selected':'';?>/>Paid Vouchers
							</select>
						</div>						
						<div class="col-sm-3">
							<label class="text-muted">Month</label>          
							<select class="form-control select" name="month" data-fouc>
							<option value="" />Current Month

							<?php 
								for($i=0;$i<6;$i++){
									$month=$this->std_fee_voucher_m->month-$i;
									$year=$this->std_fee_voucher_m->year;
									if($month<1){$month=12;$year--;}
									$month_number=get_month_number($month,$year);
									?>           
									<option value="<?php print $month_number;?>" <?php print isset($form['month'])&&$form['month']==$month_number?'selected':'';?>/><?php print month_string($month).'-'.$year;?>
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
