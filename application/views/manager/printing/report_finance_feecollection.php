<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
$params=array();
$sessions=$this->session_m->get_rows(array(),array('orderby'=>'year DESC') );
$activeSession=$this->session_m->getActiveSession();
$filter['status']=$this->student_m->STATUS_ACTIVE;
$filter['session_id']=$activeSession->mid;

isset($form['month']) && !empty($form['month']) ? $month=$form['month'] : $month=$this->std_fee_voucher_m->month;
isset($form['year']) && !empty($form['year']) ? $year=$form['year'] : $year=$this->std_fee_voucher_m->year;
isset($form['status']) && !empty($form['status']) ? $filter['status']=$form['status'] : '';
isset($form['session']) && !empty($form['session']) ? $filter['session_id']=$form['session'] : '';

$monthNumber=$this->student_m->month_number;
if(!empty($month) && !empty($year)){
    $monthNumber=get_month_number($month,$year);
}
$params['select']='mid,name,computer_number,class_id,section_id,father_name,advance_amount,mobile';
$params['orderby']='class_id ASC, section_id ASC, computer_number ASC';
$students=$this->student_m->get_rows($filter,$params);
$classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID) );
$sections=$this->class_section_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID) );
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

	<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
	<?php $this->load->view($LIB_VIEW_DIR.'printing/components/styles/print_header'); ?>		
	<p class="text-center">
		Detailed Fee Collection Report
		<?php if(!empty($month)){ print '- '.month_string($month);} ?>
		<?php if(!empty($year)){ print ' , '.$year;} ?>							
	</p>
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
	      <div class="page" style="line-height: 1; font-size: 0.7em;">
			<div class="row">
			    <table class="table table-sm">
			        <thead>
			            <tr>
			                <th class="text-center th_max_3">#</th>
			                <th class="text-center">Comp.</th>
			                <th class="text-center th_max_7">Student</th>
			                <th class="text-center">Class</th>
			                <?php if(count($sections)>0){ ?>
			                <th class="text-center">Sect.</th>
				            <?php } ?>
			                <th class="text-center">Prev.</th>
			                <th class="text-center">Tut.Fee</th>
			                <th class="text-center">VanFee</th>
			                <th class="text-center">LibFee</th>
			                <th class="text-center">AdFee</th>
			                <th class="text-center">ReAdm</th>
			                <th class="text-center">AnnFun</th>
			                <th class="text-center">PaFun</th>
			                <th class="text-center">MiscFun</th>
			                <th class="text-center">Prosp</th>
			                <th class="text-center">AbFine</th>
			                <th class="text-center">LfFine</th>
			                <th class="text-center">MiscFine</th>
			                <th class="text-center">Others</th>
			                <th class="text-center">Conc.</th>
			                <th class="text-center">Total</th>
			                <th class="text-center">Paid</th>
			                <th class="text-center">Balance</th>
			                <th class="text-center">Adv</th>
			                <th class="text-center th_max_6">Mobile</th>
			            </tr>
			        </thead>
			        <tbody>
		        	<?php 
		        	$i=0;

                    $ghd_total=0;
                    $ghd_balance=0;
                    $ghd_previous=0;
                    $ghd_monthfee=0;
                    $ghd_transport=0;
                    $ghd_library=0;
                    $ghd_stationery=0;
                    $ghd_admission=0;
                    $ghd_readmission=0;
                    $ghd_annualfund=0;
                    $ghd_paperfund=0;
                    $ghd_miscfund=0;
                    $ghd_prospectus=0;
                    $ghd_absentfine=0;
                    $ghd_lffine=0;
                    $ghd_miscfine=0;
                    $ghd_other=0;
                    $ghd_paid=0;
                    $ghd_concession=0;
                    $ghd_advance=0;
		        	foreach($students as $student){
		        		$i++;
		        		$std_class='';
		        		if(array_key_exists($student['class_id'], $classes)){
			        		$std_class=$classes[$student['class_id']];		        			
		        		}
		        		$std_section='';
		        		if(array_key_exists($student['section_id'], $sections)){
		        			$std_section=$sections[$student['section_id']];
		        		}

	                    $hd_total=0;
	                    $hd_balance=0;
	                    $hd_previous=0;
	                    $hd_monthfee=0;
	                    $hd_transport=0;
	                    $hd_library=0;
	                    $hd_stationery=0;
	                    $hd_admission=0;
	                    $hd_readmission=0;
	                    $hd_annualfund=0;
	                    $hd_paperfund=0;
	                    $hd_miscfund=0;
	                    $hd_prospectus=0;
	                    $hd_absentfine=0;
	                    $hd_lffine=0;
	                    $hd_miscfine=0;
	                    $hd_other=0;
	                    $hd_paid=0;
	                    $hd_concession=0;
	                    $hd_advance=0;
	                    $hd_advance+=$student['advance_amount'];

		                $vchr_filter=array('student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID,'month_number'=>$monthNumber);
		                if($this->std_fee_voucher_m->get_rows($vchr_filter,'',true)>0){
		                    $vouchers=$this->std_fee_voucher_m->get_rows($vchr_filter,array('orderby'=>'jd ASC','select'=>'mid, voucher_id, balance, month_number, month, year'));

		                    $vch_entry_filter=array('student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID);
		                    $prev_vchr_filter=array('student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID,'month_number'=>($monthNumber-1));
		                    if($this->std_fee_voucher_m->get_rows($prev_vchr_filter,'',true)>0){
		                        $prev_vouchers=$this->std_fee_voucher_m->get_rows($prev_vchr_filter,array('orderby'=>'jd ASC','select'=>'mid,balance'));
		                        foreach($prev_vouchers as $row){
		                            $hd_previous+=$row['balance'];
		                        }
		                    }
		                    foreach($vouchers as $row){
		                        // if($row['status'] != $this->std_fee_voucher_m->STATUS_PAID){
		                        //     $v_amount=$this->std_fee_entry_m->get_voucher_amount($row['voucher_id']);
		                        //     $rec=array('mid'=>$row['mid'],'title'=>$row['title'],'balance'=>$row['balance'],'amount'=>$v_amount);
		                        //     array_push($listed_vouchers, $rec);                            
		                        // }
		                        $vmonth=$row['month'].'-'.$row['year'];
		                        $hd_balance+=floatval($row['balance']);
		                        ///////////////////////////////////////////////////////
		                        $vch_entry_filter['voucher_id']=$row['voucher_id'];
		                        $vch_entry_filter['month_number']=$row['month_number'];
		                        $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_PLUS;

		                        //////////////////////////////////////////////////////////////////
		                        //handle college voucher proceeding feature
		                        // if($this->IS_COLLEGE){
		                            $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_MINUS;
		                            $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_CF;
		                            $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                            $hd_balance+=$amount;
		                            ///***********************    
		                            $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_PLUS;
		                            $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_BF;
		                            $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                            $hd_previous+=$amount;                       
		                        // }
		                        /////////////////////////////////////////////////////////////////
		                        //normal fee calculations
		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FEE;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_monthfee+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_TRANSPORT;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_transport+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_LIBRARY;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_library+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_STATIONERY;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_stationery+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_ADMISSION;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_admission+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_READMISSION;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_readmission+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_ANNUALFUND;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_annualfund+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_PAPERFUND;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_paperfund+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FUND;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_miscfund+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_PROSPECTUS;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_prospectus+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_ABSENT_FINE;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_absentfine+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_LATE_FEE_FINE;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_lffine+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FINE;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_miscfine+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_OTHER;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total+=$amount;
		                        $hd_other+=$amount;
		                        //////////////////////////////////////////////////////////////////
		                        $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_MINUS;
		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FEE;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_paid+=$amount;

		                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_CONCESSION;
		                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
		                        $hd_total-=$amount;
		                        $hd_concession+=$amount;
		                    }
		                    $hd_total+=$hd_previous;
		                }
		                /////////////////////////////////////////////////////
	                    $ghd_total+=$hd_total;
	                    $ghd_balance+=$hd_balance;
	                    $ghd_previous+=$hd_previous;
	                    $ghd_monthfee+=$hd_monthfee;
	                    $ghd_transport+=$hd_transport;
	                    $ghd_library+=$hd_library;
	                    $ghd_stationery+=$hd_stationery;
	                    $ghd_admission+=$hd_admission;
	                    $ghd_readmission+=$hd_readmission;
	                    $ghd_annualfund+=$hd_annualfund;
	                    $ghd_paperfund+=$hd_paperfund;
	                    $ghd_miscfund+=$hd_miscfund;
	                    $ghd_prospectus+=$hd_prospectus;
	                    $ghd_absentfine+=$hd_absentfine;
	                    $ghd_lffine+=$hd_lffine;
	                    $ghd_miscfine+=$hd_miscfine;
	                    $ghd_other+=$hd_other;
	                    $ghd_paid+=$hd_paid;
	                    $ghd_concession+=$hd_concession;
	                    $ghd_advance+=$hd_advance;
		        		?>
		        		<tr>
		        			<td class="text-right"><?php print $i ?></td>
		        			<td><?php print $student['computer_number'];?></td>
		        			<td><?php print ucwords(strtolower($student['name'])); ?></td>
		        			<td><?php print $std_class; ?></td>		        			
			                <?php if(count($sections)>0){ ?>
		        			<td><?php print $std_section;?></td>
			        		<?php } ?>
		        			<td><?php print $hd_previous > 0 ? $hd_previous : '';?></td>
		        			<td><?php print $hd_monthfee > 0 ? $hd_monthfee : '';?></td>
		        			<td><?php print $hd_transport > 0 ? $hd_transport : '';?></td>
		        			<td><?php print $hd_library > 0 ? $hd_library : '';?></td>
		        			<td><?php print $hd_admission > 0 ? $hd_admission : '';?></td>
		        			<td><?php print $hd_readmission > 0 ? $hd_readmission : '';?></td>
		        			<td><?php print $hd_annualfund > 0 ? $hd_annualfund : '';?></td>
		        			<td><?php print $hd_paperfund > 0 ? $hd_paperfund : '';?></td>
		        			<td><?php print $hd_miscfund > 0 ? $hd_miscfund : '';?></td>
		        			<td><?php print $hd_prospectus > 0 ? $hd_prospectus : '';?></td>
		        			<td><?php print $hd_absentfine > 0 ? $hd_absentfine : '';?></td>
		        			<td><?php print $hd_lffine > 0 ? $hd_lffine : '';?></td>
		        			<td><?php print $hd_miscfine > 0 ? $hd_miscfine : '';?></td>
		        			<td><?php print $hd_other > 0 ? $hd_other : '';?></td>
		        			<td><?php print $hd_concession > 0 ? $hd_concession : '';?></td>
		        			<td><?php print $hd_total > 0 ? $hd_total : '';?></td>
		        			<td><?php print $hd_paid > 0 ? $hd_paid : '';?></td>
		        			<td><?php print $hd_balance > 0 ? $hd_balance : '';?></td>
		        			<td><?php print $hd_advance > 0 ? $hd_advance : '';?></td>
		        			<td><?php print $student['mobile'];?></td>
		        		</tr>

		        	<?php }?>
			        </tbody>

			        
			    </table>
			    <table class="table table-sm">
			    	<thead>
			            <tr>
			                <th class="text-center th_max_3"></th>
			                <th class="text-center"></th>
			                <th class="text-center th_max_7"></th>
			                <th class="text-center"></th>
			                <th class="text-center"></th>
			                <th class="text-center"><?php print $ghd_previous;?></th>
			                <th class="text-center"><?php print $ghd_monthfee;?></th>
			                <th class="text-center"><?php print $ghd_transport;?></th>
			                <th class="text-center"><?php print $ghd_library;?></th>
			                <th class="text-center"><?php print $ghd_admission;?></th>
			                <th class="text-center"><?php print $ghd_readmission;?></th>
			                <th class="text-center"><?php print $ghd_annualfund;?></th>
			                <th class="text-center"><?php print $ghd_paperfund;?></th>
			                <th class="text-center"><?php print $ghd_miscfund;?></th>
			                <th class="text-center"><?php print $ghd_prospectus;?></th>
			                <th class="text-center"><?php print $ghd_absentfine;?></th>
			                <th class="text-center"><?php print $ghd_lffine;?></th>
			                <th class="text-center"><?php print $ghd_miscfine;?></th>
			                <th class="text-center"><?php print $ghd_other;?></th>
			                <th class="text-center"><?php print $ghd_concession;?></th>
			                <th class="text-center"><?php print $ghd_total;?></th>
			                <th class="text-center"><?php print $ghd_paid;?></th>
			                <th class="text-center"><?php print $ghd_balance;?></th>
			                <th class="text-center"><?php print $ghd_advance;?></th>
			                <th class="text-center th_max_6"></th>
			            </tr>
			        </thead>
			    </table>
			    <br>
			</div>

			<?php if(isset($form['p_analytics'])){ ?>
			<br><br><br>
			<div class="row">
				<div class="col-sm-4" style="vertical-align: middle; text-align: center;">			
					<div class="row" style="font-size: 1.5em;vertical-align: middle; text-align: center;">
						<div class="col-sm-4 border-solid text-center">
							<span class="text-bold">Total Students:</span>
						</div>
						<div class="col-sm-4 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $i;?> </span>
						</div>
					</div>					
				</div>
				<div class="col-sm-7">				
					<div class="row" style="font-size: 1.5em;">
						<div class="col-sm-6 border-solid text-center">
							<span class="text-bold">Total Proceeded Amount:</span>
						</div>
						<div class="col-sm-6 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $ghd_previous;?> </span>
						</div>
						<div class="col-sm-6 border-solid text-center">
							<span class="text-bold">Total Tution Fee:</span>
						</div>
						<div class="col-sm-6 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $ghd_monthfee;?> </span>
						</div>
						<div class="col-sm-6 border-solid text-center">
							<span class="text-bold">Total Receivable Amount:</span>
						</div>
						<div class="col-sm-6 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $ghd_total;?> </span>
						</div>
						<div class="col-sm-6 border-solid text-center">
							<span class="text-bold">Total Received Amount:</span>
						</div>
						<div class="col-sm-6 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $ghd_paid;?> </span>
						</div>
						<div class="col-sm-6 border-solid text-center">
							<span class="text-bold">Total Balance:</span>
						</div>
						<div class="col-sm-6 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $ghd_balance;?> </span>
						</div>
						<div class="col-sm-6 border-solid text-center">
							<span class="text-bold">Total Advance:</span>
						</div>
						<div class="col-sm-6 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $ghd_advance;?> </span>
						</div>
						<div class="col-sm-6 border-solid text-center">
							<span class="text-bold">Total Receivable - Advance:</span>
						</div>
						<div class="col-sm-6 grd-bg-orange border-white text-center">
							<span class="text-bold"> <?php print $ghd_total-$ghd_advance;?> </span>
						</div>
					</div>
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
	<?php print $this->config->item('app_print_code');?>5018 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
<!-- /main content --->

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

