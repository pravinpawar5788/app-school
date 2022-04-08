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
$default_month=$this->std_fee_voucher_m->month;
$default_year=$this->std_fee_voucher_m->year;
$default_month_number=$this->std_fee_voucher_m->month_number;
/////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
$params['orderby']='class_id ASC, roll_no ASC';
$params['select']='mid,student_id,voucher_id,title,due_date,status,date_paid,date,month_number';
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
	//voucher etnries rows
	$rows=array(
		array('remarks'=>'Tution Fee','amount'=>''),
		array('remarks'=>'Transport Fee','amount'=>''),
		array('remarks'=>'Admission Fee','amount'=>''),
		array('remarks'=>'Prospectus','amount'=>''),
		array('remarks'=>'Annual Stationary Charges','amount'=>''),
		array('remarks'=>'Absent Fine','amount'=>''),
		array('remarks'=>'Gen. Charges','amount'=>''),
		array('remarks'=>'Late Fee Fine','amount'=>''),
	);	
	$template='';
	if(isset($form['template'])&&!empty($form['template'])){$template=$form['template'];}
		switch (strtolower($template)) {
			case 'bank':{
				///start of simple template
				$tbl_rows=12;	//how may rows to print for this table				
				if(isset($form['tblrows'])){$tbl_rows=intval($form['tblrows']);}
				$org_logo_size=32;
				?>
				<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
				<div class="page force-page-break-after">
					<div class="row" style="vertical-align: middle;">
						
						<div class="col-sm-4">
							<div class="card card-solid editable">
								<div class="card-header bg-transparent header-elements-inline">
									<h6 class="card-title text-center horizontal-center">
										<center><span class="font-weight-bold font-1-1em">
										<?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span></center>
										<center><span class="ml-1 font-0-8em">
										<?php print ucwords($this->CAMPUS->address).' Ph:'.$this->CAMPUS->contact_number.'';?></span>
										</center>
										<div class="row">
											<div class="col-sm-2">
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->ORG->logo;?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
											</div>
											<div class="col-sm-10">
												<center>													
													<span class="vertical-center font-0-8em">
														<strong><?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_BANK_NAME];?></strong>
														 No. <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_BANK_ACCOUNT];?>
													</span>
												</center>
											</div>
										</div>
										<center><span class="font-weight-semibold">Bank Copy</span></center>
									</h6>
								</div>
								<card class="body">	
									<div class="row">
										<div class="col-sm-12">
											<p><br>
											<span class="line mtb-1">
												Comp No <span class="line-fill w-<?php print ceil(40-($scale*1.3));?>"> </span>
												Month <span class="line-fill w-<?php print ceil(30-($scale*1.3));?>"></span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="line-fill w-<?php print ceil(72-($scale*2.5));?>"></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="line-fill w-<?php print ceil(74-($scale*2.7));?>"></span>
											</span>
											<span class="line mtb-1">
												Class <span class="line-fill w-<?php print ceil(41-($scale*1.1));?>"> </span>
												Section <span class="line-fill w-<?php print ceil(33-($scale*1.1));?>"></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="line-fill w-<?php print ceil(33-($scale*1.6));?>"> </span>
												Due Date <span class="line-fill w-<?php print ceil(31-($scale*1.6));?>"></span>
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
														<?php print ucwords(strtolower($row['remarks']));?>
													</td>
													<td class="vchr-td-height">
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
									                <th class="font-weight-semibold text-center w-70">Net Payable</th>
									                <th class="font-weight-semibold text-center"></th>
									            </tr>
									        </tfoot>
									    </table>
										</div>
									</div>


									<div class="row">
										<div class="col-sm-12">
										<br><br>
										<center><span class="font-weight-bold">(Signature &amp; Stamp)</span></center>
										</div>
									</div>
									    
								</card>
								<div class="card-footer" style="font-size: 0.9em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<br>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="card card-solid editable">
								<div class="card-header bg-transparent header-elements-inline">
									<h6 class="card-title text-center horizontal-center">
										<center><span class="font-weight-bold font-1-1em">
										<?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span></center>
										<center><span class="ml-1 font-0-8em">
										<?php print ucwords($this->CAMPUS->address).' Ph:'.$this->CAMPUS->contact_number.'';?></span>
										</center>
										<div class="row">
											<div class="col-sm-2">
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->ORG->logo;?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
											</div>
											<div class="col-sm-10">
												<center>													
													<span class="vertical-center font-0-8em">
														<strong><?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_BANK_NAME];?></strong>
														 No. <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_BANK_ACCOUNT];?>
													</span>
												</center>
											</div>
										</div>
										<center><span class="font-weight-semibold">Office Copy</span></center>
									</h6>
								</div>
								<card class="body">	
									<div class="row">
										<div class="col-sm-12">
											<p><br>
											<span class="line mtb-1">
												Comp No <span class="line-fill w-<?php print ceil(40-($scale*1.3));?>"> </span>
												Month <span class="line-fill w-<?php print ceil(30-($scale*1.3));?>"></span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="line-fill w-<?php print ceil(72-($scale*2.5));?>"></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="line-fill w-<?php print ceil(74-($scale*2.7));?>"></span>
											</span>
											<span class="line mtb-1">
												Class <span class="line-fill w-<?php print ceil(41-($scale*1.1));?>"> </span>
												Section <span class="line-fill w-<?php print ceil(33-($scale*1.1));?>"></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="line-fill w-<?php print ceil(33-($scale*1.6));?>"> </span>
												Due Date <span class="line-fill w-<?php print ceil(31-($scale*1.6));?>"></span>
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
														<?php print ucwords(strtolower($row['remarks']));?>
													</td>
													<td class="vchr-td-height">
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
									                <th class="font-weight-semibold text-center w-70">Net Payable</th>
									                <th class="font-weight-semibold text-center"></th>
									            </tr>
									        </tfoot>
									    </table>
										</div>
									</div>


									<div class="row">
										<div class="col-sm-12">
										<br><br>
										<center><span class="font-weight-bold">(Signature &amp; Stamp)</span></center>
										</div>
									</div>
									    
								</card>
								<div class="card-footer" style="font-size: 0.9em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<br>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="card card-solid editable">
								<div class="card-header bg-transparent header-elements-inline">
									<h6 class="card-title text-center horizontal-center">
										<center><span class="font-weight-bold font-1-1em">
										<?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span></center>
										<center><span class="ml-1 font-0-8em">
										<?php print ucwords($this->CAMPUS->address).' Ph:'.$this->CAMPUS->contact_number.'';?></span>
										</center>
										<div class="row">
											<div class="col-sm-2">
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->ORG->logo;?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
											</div>
											<div class="col-sm-10">
												<center>													
													<span class="vertical-center font-0-8em">
														<strong><?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_BANK_NAME];?></strong>
														 No. <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_BANK_ACCOUNT];?>
													</span>
												</center>
											</div>
										</div>
										<center><span class="font-weight-semibold">Student Copy</span></center>
									</h6>
								</div>
								<card class="body">	
									<div class="row">
										<div class="col-sm-12">
											<p><br>
											<span class="line mtb-1">
												Comp No <span class="line-fill w-<?php print ceil(40-($scale*1.3));?>"> </span>
												Month <span class="line-fill w-<?php print ceil(30-($scale*1.3));?>"></span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="line-fill w-<?php print ceil(72-($scale*2.5));?>"></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="line-fill w-<?php print ceil(74-($scale*2.7));?>"></span>
											</span>
											<span class="line mtb-1">
												Class <span class="line-fill w-<?php print ceil(41-($scale*1.1));?>"> </span>
												Section <span class="line-fill w-<?php print ceil(33-($scale*1.1));?>"></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="line-fill w-<?php print ceil(33-($scale*1.6));?>"> </span>
												Due Date <span class="line-fill w-<?php print ceil(31-($scale*1.6));?>"></span>
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
														<?php print ucwords(strtolower($row['remarks']));?>
													</td>
													<td class="vchr-td-height">
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
									                <th class="font-weight-semibold text-center w-70">Net Payable</th>
									                <th class="font-weight-semibold text-center"></th>
									            </tr>
									        </tfoot>
									    </table>
										</div>
									</div>


									<div class="row">
										<div class="col-sm-12">
										<br><br>
										<center><span class="font-weight-bold">(Signature &amp; Stamp)</span></center>
										</div>
									</div>
									    
								</card>
								<div class="card-footer" style="font-size: 0.9em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<br>
								</div>
							</div>
						</div>
					</div>
				</div>
				</page>
				<?php
				//end of simple template
			}break;	

			default:{
				///start of simple template
				$tbl_rows=3;	//how may rows to print for this table				
				if(isset($form['tblrows'])){$tbl_rows=intval($form['tblrows']);}
				$org_logo_size=32;
				$rows=array();
				?>
				<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
				<div class="page force-page-break-after">
					<div class="row" style="vertical-align: middle;">	
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
												Comp No <span class="line-fill w-<?php print ceil(40-($scale*1.1));?>"> </span>
												Month <span class="line-fill w-<?php print ceil(30-($scale*1.1));?>"></span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="line-fill w-<?php print ceil(72-($scale*1.8));?>"></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="line-fill w-<?php print ceil(74-($scale*1.8));?>"></span>
											</span>
											<span class="line mtb-1">
												Class <span class="line-fill w-<?php print ceil(41-($scale*1.1));?>"> </span>
												Section <span class="line-fill w-<?php print ceil(33-($scale*1.1));?>"></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="line-fill w-<?php print ceil(33-($scale*1.3));?>"> </span>
												Due Date <span class="line-fill w-<?php print ceil(31-($scale*1.3));?>"></span>
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
														<?php print ucwords(strtolower($row['remarks']));?>
													</td>
													<td class="vchr-td-height">
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
									                <th class="font-weight-semibold text-center w-70">Net Payable</th>
									                <th class="font-weight-semibold text-center"></th>
									            </tr>
									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer" style="font-size: 0.9em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<br>
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
										<center><span class="font-weight-semibold">Student Copy</span></center>
									</h6>
								</div>
								<card class="body">	
									<div class="row">
										<div class="col-sm-12">
											<p><br>
											<span class="line mtb-1">
												Comp No <span class="line-fill w-<?php print ceil(40-($scale*1.1));?>"> </span>
												Month <span class="line-fill w-<?php print ceil(30-($scale*1.1));?>"></span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="line-fill w-<?php print ceil(72-($scale*1.8));?>"></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="line-fill w-<?php print ceil(74-($scale*1.8));?>"></span>
											</span>
											<span class="line mtb-1">
												Class <span class="line-fill w-<?php print ceil(41-($scale*1.1));?>"> </span>
												Section <span class="line-fill w-<?php print ceil(33-($scale*1.1));?>"></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="line-fill w-<?php print ceil(33-($scale*1.3));?>"> </span>
												Due Date <span class="line-fill w-<?php print ceil(31-($scale*1.3));?>"></span>
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
														<?php print ucwords(strtolower($row['remarks']));?>
													</td>
													<td class="vchr-td-height">
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
									                <th class="font-weight-semibold text-center w-70">Net Payable</th>
									                <th class="font-weight-semibold text-center"></th>
									            </tr>
									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer" style="font-size: 0.9em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<br>
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
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/form';?>">
				<input type="hidden" name="frm" value="<?php print isset($form['frm'])?$form['frm']:'';?>">
				<div class="modal-header bg-success">
					<h6 class="modal-title">Configure Visible Parameters...</h6>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body p-3">
					<br>
					<legend class="font-weight-semibold text-uppercase font-size-sm"><i class="icon-eye mr-2"></i>Configure Visible Items</legend>
				
					<div class="row">

						<!-- <div class="col-md-3">
							<input type="checkbox" name="p_analytics" 
								<?php print isset($form['p_analytics']) ? 'checked':'';?>>
								Show Analytics Footer							
						</div> -->						
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
						    <option value="brank" <?php print isset($form['template'])&&$form['template']=='brank'?'selected':'';?>/>Bank Template
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
