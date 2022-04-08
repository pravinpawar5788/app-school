<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'display_order ASC');
$section_filter=array('campus_id'=>$this->CAMPUSID);
if(isset($form['class'])&&!empty($form['class'])){$section_filter['class_id']=$form['class'];}
$sections=$this->class_section_m->get_values_array('mid','name',$section_filter,'name ASC');

$is_single_voucher=false;
$scale=intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_FONT_SACLE]);
if(isset($form['scale']) && !empty($form['scale']) ){$scale=intval($form['scale']);}
//////////////////////////////////////////////////////////////////
// $sections=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC') );
$sessions=$this->session_m->get_values_array('mid','title',array());
$params=array();
// $class_filter=array('campus_id'=>$this->CAMPUSID);
// if(isset($form['pid']) && !empty($form['pid'])){$class_filter['mid']=$form['pid'];}
// $params['orderby']='display_order ASC';
// $classes=$this->class_m->get_rows($class_filter,$params);
$default_month=$this->std_fee_voucher_m->month;
$default_year=$this->std_fee_voucher_m->year;
$default_month_number=$this->std_fee_voucher_m->month_number;
/////////////////////////////////////////////////////////////////

isset($form['class']) && !empty($form['class']) ? $class_id=$form['class'] : $class_id='';
isset($form['section']) && !empty($form['section']) ? $section_id=$form['section'] : $section_id='';
$sections_filter=array('campus_id'=>$this->CAMPUSID);
if(!empty($class_id)){$sections_filter['class_id']=$class_id;}
$sections_list=$this->class_section_m->get_rows($sections_filter,array('orderby'=>'name ASC') );
/////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
// $filter['status']=$this->std_fee_voucher_m->STATUS_UNPAID;
if(isset($form['vid']) && !empty($form['vid'])){
	$filter['mid']=$form['vid'];
	$is_single_voucher=true;
}else{
	// $filter['month_number']=$this->std_fee_voucher_m->month_number;
	$filter['session_id']=$this->session_m->getActiveSession()->mid;
	if(isset($form['type']) && !empty($form['type'])){$filter['status']=$form['type'];}else{$filter['status <>']=$this->std_fee_voucher_m->STATUS_PAID;}
	if(isset($form['class']) && !empty($form['class'])){$filter['class_id']=$form['class'];}
	if(isset($form['month']) && !empty($form['month'])){
		$filter['month_number']=$form['month'];
	}else{
		$filter['month_number']=$default_month_number;
	}
}
$params['orderby']='class_id ASC, roll_no ASC';
$params['select']='mid,student_id,voucher_id,title,due_date,status,date_paid,date,month_number,month,year';
$vouchers=$this->std_fee_voucher_m->get_rows($filter,$params);
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
	//process only if class is selected
	if($is_single_voucher || !empty($class_id) ){
	foreach($vouchers as $voucher){
		$vi++;
		$student=$this->student_m->get_by_primary($voucher['student_id'],'image,name,father_name,mobile,guardian_mobile,section,section_id,student_id,class_id,roll_no,session_id,computer_number,family_number');
		//skip if class section does not match
		if(!empty($section_id) && $student->section_id != $section_id){continue;}
		//when a single voucher is printed month number is set to voucher month number
		if(empty($filter['month_number'])){$filter['month_number']=$voucher['month_number'];}

		$rows=$this->std_fee_entry_m->get_rows(array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher['voucher_id']),array('select'=>'mid,remarks,amount,operation,date','orderby'=>'operation DESC, mid ASC'));
		$due_jd=get_jd_from_date($voucher['due_date'],'-',true);
		?>

	<?php 
	$template='';
	if(isset($form['template'])&&!empty($form['template'])){$template=$form['template'];}
		switch (strtolower($template)) {
			
			case 'bank':{
				///start of simple template
				$tbl_rows=10;	//how may rows to print for this table				
				if(isset($form['tblrows'])){$tbl_rows=intval($form['tblrows']);}
				$org_logo_size=32;
				$std_img_size=48;
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
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
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
							                	<?php 
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=18;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((40-($scale*1.3))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.3));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(72-($scale*2.5));?>">
													<?php print $student->name;?>
												</span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(74-($scale*2.7));?>"><?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(41-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.1));?>"><?php print $student->section_id>0 ? $sections[$student->section_id]: '';?></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.6));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.6));?>"><?php print $voucher['due_date'];?></span>
											</span>
											<?php if(isset($form['p_family']) || isset($form['p_voucher']) ){ ?>
											<span class="line mtb-1">
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(46-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
											</span>
											<?php } ?>
												
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID  && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

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
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
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
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
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
							                	<?php 
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=18;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((40-($scale*1.3))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.3));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(72-($scale*2.5));?>">
													<?php print $student->name;?>
												</span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(74-($scale*2.7));?>"><?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(41-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.1));?>"><?php print $student->section_id>0 ? $sections[$student->section_id]: '';?></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.6));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.6));?>"><?php print $voucher['due_date'];?></span>
											</span>
											<?php if(isset($form['p_family']) || isset($form['p_voucher']) ){ ?>
											<span class="line mtb-1">
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(46-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
											</span>
											<?php } ?>
												
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID  && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

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
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
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
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
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
							                	<?php 
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=18;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((40-($scale*1.3))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.3));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(72-($scale*2.5));?>">
													<?php print $student->name;?>
												</span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(74-($scale*2.7));?>"><?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(41-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.1));?>"><?php print $student->section_id>0 ? $sections[$student->section_id]: '';?></span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.6));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.6));?>"><?php print $voucher['due_date'];?></span>
											</span>
											<?php if(isset($form['p_family']) || isset($form['p_voucher']) ){ ?>
											<span class="line mtb-1">
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(46-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
											</span>
											<?php } ?>
												
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

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
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
				</page>
				<?php
				//end of simple template
			}break;	

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
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=18;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((40-($scale*1.1))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.1));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(72-($scale*1.8));?>">
													<?php print $student->name;?></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(74-($scale*1.8));?>">
													<?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(41-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.1));?>">
													<?php print $student->section_id>0 ? $sections[$student->section_id]: '';?>
												</span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.3));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.3));?>"><?php print $voucher['due_date'];?></span>
											</span>
											<?php if(isset($form['p_family']) || isset($form['p_voucher']) ){ ?>
											<span class="line mtb-1">
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(52-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
											</span>
											<?php } ?>
												
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
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

			case 'page':{
				///start of simple template
				$tbl_rows=3;	//how may rows to print for this table				
				if(isset($form['tblrows'])){$tbl_rows=intval($form['tblrows']);}
				$org_logo_size=32;
				$std_img_size=48;
				?>

				<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
				<div class="page force-page-break-after">
					<div class="row">	
						<div class="col-sm-12">
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
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
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
							                	<?php 
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=5;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((15-($scale*1.1))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(10-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.8));?>">
													<?php print $student->name;?></span>
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.8));?>">
													<?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(17-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(10-($scale*1.1));?>">
													<?php print $student->section_id>0 ? $sections[$student->section_id]: '';?>
												</span>
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.3));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.3));?>"><?php print $voucher['due_date'];?></span>
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID  && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
								</div>
							</div>
						</div>		
						<div class="col-sm-12">
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
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
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
							                	<?php 
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=5;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((15-($scale*1.1))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(10-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.8));?>">
													<?php print $student->name;?></span>
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.8));?>">
													<?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(17-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(10-($scale*1.1));?>">
													<?php print $student->section_id>0 ? $sections[$student->section_id]: '';?>
												</span>
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.3));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.3));?>"><?php print $voucher['due_date'];?></span>
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID  && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
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
												<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="<?php print $org_logo_size;?>" height="<?php print $org_logo_size;?>" alt=" ">
												
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
							                	<?php 
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=5;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((15-($scale*1.1))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(10-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.8));?>">
													<?php print $student->name;?></span>
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(35-($scale*1.8));?>">
													<?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(17-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(10-($scale*1.1));?>">
													<?php print $student->section_id>0 ? $sections[$student->section_id]: '';?>
												</span>
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.3));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(20-($scale*1.3));?>"><?php print $voucher['due_date'];?></span>
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID  && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
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
										<center><span class="font-weight-semibold">Student Copy</span></center>
									</h6>
								</div>
								<card class="body">	
									<div class="row">
										<div class="col-sm-12">
											<p><br>
											<span class="line mtb-1">
							                	<?php 
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=18;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((40-($scale*1.1))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.1));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(72-($scale*1.8));?>">
													<?php print $student->name;?></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(74-($scale*1.8));?>">
													<?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(41-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.1));?>">
													<?php print $student->section_id>0 ? $sections[$student->section_id]: '';?>
												</span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.3));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.3));?>"><?php print $voucher['due_date'];?></span>
											</span>
											<?php if(isset($form['p_family']) || isset($form['p_voucher']) ){ ?>
											<span class="line mtb-1">
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(52-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
											</span>
											<?php } ?>
												
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php 

										        $due_jd=get_jd_from_date($voucher['due_date'],'-',true);
												if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
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
							                	$img_margin=0;
							                	if(isset($form['p_img'])){
							                		$img_margin=18;
							                	?>
												<span>
													<img src="<?php print $this->UPLOADS_ROOT.'images/student/profile/'.$student->image;?>" class="rounded m-1" width="<?php print $std_img_size;?>" height="<?php print $std_img_size;?>" alt="">
												</span>
												<?php  }?>
												<strong>Comp No </strong><span class="vchr-filed-pl text-center line-fill w-<?php print ceil((40-($scale*1.1))-$img_margin);?>"> 
												<?php print $student->computer_number;?></span>
												Month <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(30-($scale*1.1));?>">
													<?php print month_string($voucher['month']);?>,<?php print $voucher['year'];?>
												</span>
											</span>
											<span class="line mtb-1">
												Student's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(72-($scale*1.8));?>">
													<?php print $student->name;?></span>
											</span>
											<span class="line mtb-1">
												Father's Name <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(74-($scale*1.8));?>">
													<?php print $student->father_name;?></span>
											</span>
											<span class="line mtb-1">
												Class <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(41-($scale*1.1));?>">
													<?php print $student->class_id>0 ? $classes[$student->class_id]: '';?>
													<?php print !empty($student->roll_no) ? ' Roll No.'.$student->roll_no : '';?></span>
												Section <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.1));?>">
													<?php print $student->section_id>0 ? $sections[$student->section_id]: '';?>
												</span>
											</span>
											<span class="line mtb-1">
												Issue Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(33-($scale*1.3));?>"><?php print $voucher['date'];?></span>
												Due Date <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(31-($scale*1.3));?>"><?php print $voucher['due_date'];?></span>
											</span>
											<?php if(isset($form['p_family']) || isset($form['p_voucher']) ){ ?>
											<span class="line mtb-1">
												<?php if(isset($form['p_family'])){ ?>
												Family No <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(15-($scale*1.1));?>"><?php print $student->family_number;?></span>
												<?php } ?>
												<?php if(isset($form['p_voucher'])){ ?>
												Voucher ID <span class="vchr-filed-pl text-center line-fill w-<?php print ceil(52-($scale*1.1));?>"><?php print $voucher['voucher_id'];?></span>
												<?php } ?>
											</span>
											<?php } ?>
												
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
									                <th class="font-weight-semibold text-center w-70">Net Payable </th>
									                <th class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </th>
									            </tr>
												<?php if($voucher['status']!=$this->std_fee_voucher_m->STATUS_UNPAID){?>
									            <!-- <tr>
									                <td class="font-weight-semibold text-center w-70">Remaining Balance</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id']);?>
									                </td>
									            </tr> -->
										        <?php } ?>

												<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID  && $this->std_fee_voucher_m->todayjd < $due_jd && $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]<1){?>
									            <tr>
									                <td class="font-weight-semibold text-center w-70">After Due Date</td>
									                <td class="font-weight-semibold text-center">
									                	<?php print ($this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'])+$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]);?>
									                </td>
									            </tr>
												<?php }?>

									        </tfoot>
									    </table>
										</div>
									</div>
									    
								</card>
								<div class="card-footer font-0-8em">
									<?php $this->load->view($LIB_VIEW_DIR.'printing/components/card_footer_note'); ?>
									
									<?php if($voucher['status']==$this->std_fee_voucher_m->STATUS_UNPAID){?>
										<?php if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]>0){
											if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]>0){?>
											<br><span class="text-sm">Late-fee fine is: <?php print $this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]; ?>/day</span>
											<?php }?>
										<?php }?>
									<?php }?>
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
	<?php } 

	}?>
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
			<form method="get" action="<?php print $this->CONT_ROOT.'printing/feeslip';?>">
				<?php if(isset($form['vid'])){?> 
				<input type="hidden" name="vid" value="<?php print isset($form['vid'])?$form['vid']:'';?>">
				<input type="hidden" name="tblrows" value="<?php print isset($form['tblrows'])?$form['tblrows']:'';?>">
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
							<input type="checkbox" name="p_family" 
								<?php print isset($form['p_family']) ? 'checked':'';?>>
								Show Family Number							
						</div>	
						<div class="col-md-3">
							<input type="checkbox" name="p_voucher" 
								<?php print isset($form['p_voucher']) ? 'checked':'';?>>
								Show Voucher ID							
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
						    <option value="page" <?php print isset($form['template'])&&$form['template']=='page' ?'selected':'';?>/>Single Page
						    <option value="bank" <?php print isset($form['template'])&&$form['template']=='bank' ?'selected':'';?>/>Bank Template
							</select>
						</div>	
						<div class="col-sm-3">
							<label class="text-muted">Type</label>          
							<select class="form-control select" name="type" data-fouc>
						    <option value="" />All Types
						    <option value="<?php print $this->std_fee_voucher_m->STATUS_UNPAID ?>" <?php print isset($form['type'])&&$form['type']==$this->std_fee_voucher_m->STATUS_UNPAID ?'selected':'';?>/>Unpaid Vouchers
						    <option value="<?php print $this->std_fee_voucher_m->STATUS_PAID ?>" <?php print isset($form['type'])&&$form['type']==$this->std_fee_voucher_m->STATUS_PAID ?'selected':'';?>/>Paid Vouchers
						    <option value="<?php print $this->std_fee_voucher_m->STATUS_PARTIAL_PAID ?>" <?php print isset($form['type'])&&$form['type']==$this->std_fee_voucher_m->STATUS_PARTIAL_PAID ?'selected':'';?>/>Partialy Paid Vouchers
							</select>
						</div>						
						<div class="col-sm-3">
							<label class="text-muted">Month</label>          
							<select class="form-control select" name="month" data-fouc>
							<option value="" />Current Month

							<?php 
								for($i=0;$i<8;$i++){
									$month=($this->std_fee_voucher_m->month-$i)+3;
									$year=$this->std_fee_voucher_m->year;
									if($month<1){$month=12;$year--;}
									if($month>12){$month-=12;$year++;}
									$month_number=get_month_number($month,$year);
									?>           
									<option value="<?php print $month_number;?>" <?php print isset($form['month'])&&$form['month']==$month_number?'selected':'';?>/><?php print month_string($month).'-'.$year;?>
									<?php 
								} 
							?>
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
						<?php if(isset($form['class'])&&!empty($form['class'])&&count($sections)){?>
						<div class="col-sm-3">
							<label class="text-muted">Class Section</label>          
							<select class="form-control select" name="section" data-fouc>
							<option value="" />All Sections
							<?php foreach ($sections as $key=>$val){?>            
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
						<div class="col-sm-3">
							<!-- <label class="text-muted">Font Scale</label> -->
							<select class="form-control select" name="tblrows" data-fouc>          
						    <option value="" />Default Rows
							<?php for($s=5;$s<20;$s++){ ?>
							<option value="<?php print $s+1; ?>" <?php print isset($form['tblrows'])&&$form['tblrows']==($s+1)?'selected':'';?>> Minimum <?php print $s+1; ?></option>
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
