<?php
// print_r($form);
$this_url=current_url().'?';
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////

$accounting_periods=$this->accounts_period_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID),'mid DESC');
$accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));

//////////////////////////////////////////////////////////////////
$filter=array('campus_id'=>$this->CAMPUSID);
$params=array();

$period_id=$this->accounts_period_m->get_current_period_id($this->CAMPUSID);
isset($form['period']) && !empty($form['period']) ? $period_id= $form['period'] : '';
$selected_period=$this->accounts_period_m->get_by_primary($period_id);
////////////////////////////////////////////////////////////////////
$filter['period_id']=$period_id;
isset($form['listzero']) ? $list_zero=true : $list_zero=false; //list zero entries

isset($form['stmt']) && strlen($form['stmt'])>2 ? $statement=$form['stmt'] : $statement='trial'; //default statement is trial balance

isset($form['fromdate']) && strlen($form['fromdate'])>2 ? $from_date=$form['fromdate'] : $from_date='1-Jan-'.date('Y'); //default from date is from 1 jan of current year
isset($form['todate']) && strlen($form['todate'])>2 ? $to_date=$form['todate'] : $to_date=$this->accounts_m->date;
$from_jd=get_jd_from_date($from_date,'-',true);
$to_jd=get_jd_from_date($to_date,'-',true);


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

	<?php switch (strtolower($statement)) {
		///START OF BALANCE SHEET STATEMENT
		case 'balance':{ ?>

			<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">

			<div class="page-header" style="text-align: center">
				<div class="row">
					<div class="col-sm-2">
						<span class="float-right page-header-image">
							<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="72" height="72" alt=""></span>		
					</div>
					<div class="col-sm-10 font-family-arial-black">
						<span class="font-weight-bold page-form-name"><?php print $print_page_title;?></span><br>
						<span class="font-weight-semibold page-campus-name">
							<?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?> - <?php print $this->CAMPUS->name;?></span><br>
						<span class="font-weight-semibold page-campus-contact">
							For Accounting Period: <?php print $selected_period->title;?></span>			
					</div>
				</div>		
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

					<!-- block -->
					<div class="row form-block">
						<div class="col-sm-12 form-block-head" style="padding: 7pt;">
						<span class="text-center">
							Balance Sheet <br><?php print $from_date;?> - <?php print $to_date;?>
						</span>
						</div>
						<div class="col-sm-12 form-block-body">
							
							<div class="row">
								<div class="col-sm-6">
									<div class="row d-block">				
									    <table class="table table-sm">
									    <tbody>
									    <?php $t_assets=0;$t_liability=0;$t_equity=0;$t_income=0; ?>
							        	 <!-- START ASSETS ACCOUNTS LISTING -->
							        	 <tr style="border-color: white;">
							        	 	<td width="60%"></td>
							        	 	<td width="20%"></td>
							        	 	<td width="20%"></td>
							        	 </tr>
							        	<tr>
							        		<td class="font-weight-semibold text-center grd-bg-orange" colspan="3">
							        			<strong>ASSETS</strong>
							        		</td>
							        	</tr>
						                <?php 
										$params['orderby']='title ASC';
										$filter['type']=$this->accounts_m->TYPE_ASSETS;
										$accounts=$this->accounts_m->get_rows($filter,$params);
										foreach($accounts as $row){					
											$cr_balance=0;
											$dr_balance=0;
											$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
											$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
											$net_balance=abs($rdr_balance)-abs($rcr_balance);
											if($net_balance==0 && $list_zero){continue;}
											$t_assets+=$net_balance;
											?>
								            <tr>
								                <td class="text-center" width="60%"><?php print $row['title'];?> Account</td>
								                <td class="text-center" width="20%"><?php print $net_balance;?></td>
								                <td class="text-center" width="20%"></td>
								            </tr>
								        <?php } ?>
							        	<tr>
							        		<td class="font-weight-bold text-center grd-bg-default">
							        			<strong>Total ASSETS</strong>
							        		</td>
							        		<td></td>
							        		<td><strong><?php print $t_assets; ?></strong></td>
							        	</tr>
								        <!-- END ASSETS ACCOUNT LISTING -->


									        </tbody>
									    </table>
									    <br>
									</div>						
								</div>
								<div class="col-sm-6">
									<div class="row d-block">				
									    <table class="table table-sm">
									        <tbody>
									        	<!-- START OF INCOME CALCULATION -->
							        	<?php 
							        		$t_revenue=0;
							        		$t_expenses=0;
						        		//---------------------
										$params['orderby']='title ASC';
										$filter['type']=$this->accounts_m->TYPE_REVENUE;
										$accounts=$this->accounts_m->get_rows($filter,$params);
										foreach($accounts as $row){	
											$net_balance=0;				
											$cr_balance=0;
											$dr_balance=0;
											$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
											$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
											$net_balance=abs($rdr_balance)-abs($rcr_balance);
											if($net_balance==0 && $list_zero){continue;}
											$t_revenue+=abs($net_balance);
										}
										//------------------------------------							
										$params['orderby']='title ASC';
										$filter['type']=$this->accounts_m->TYPE_EXPENSE;
										$accounts=$this->accounts_m->get_rows($filter,$params);
										foreach($accounts as $row){
											$net_balance=0;					
											$cr_balance=0;
											$dr_balance=0;
											$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
											$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
											$net_balance=abs($rdr_balance)-abs($rcr_balance);
											if($net_balance==0 && $list_zero){continue;}
											$t_expenses+=abs($net_balance);
										}									
										$t_income=$t_revenue-$t_expenses;
										 ?>
							        	<!-- END OF INCOME CALCULATION -->



							        	 <!-- START LIABILITIES ACCOUNTS LISTING -->
							        	 <tr style="border-color: white;">
							        	 	<td width="60%"></td>
							        	 	<td width="20%"></td>
							        	 	<td width="20%"></td>
							        	 </tr>
							        	<tr>
							        		<td class="font-weight-semibold text-center grd-bg-orange" colspan="3">
							        			<strong>LIABILILITIES</strong>
							        		</td>
							        	</tr>
						                <?php 
										$params['orderby']='title ASC';
										$filter['type']=$this->accounts_m->TYPE_LIABILITY;
										$accounts=$this->accounts_m->get_rows($filter,$params);
										foreach($accounts as $row){	
											$net_balance=0;				
											$cr_balance=0;
											$dr_balance=0;
											$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
											$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
											$net_balance=abs($rdr_balance)-abs($rcr_balance);
											if($net_balance==0 && $list_zero){continue;}
											$t_liability+=abs($net_balance);
											?>
								            <tr>
								                <td class="text-center" width="60%"><?php print $row['title'];?> Account</td>
								                <td class="text-center" width="20%"><?php print abs($net_balance);?></td>
								                <td class="text-center" width="20%"></td>
								            </tr>
								        <?php } ?>
							        	<tr>
							        		<td class="font-weight-bold text-center  grd-bg-default">
							        			<strong>Total Liabilities</strong>
							        		</td>
							        		<td></td>
							        		<td><strong><?php print $t_liability; ?></strong></td>
							        	</tr>
								        <!-- END LIABILITY ACCOUNT LISTING -->

							        	 <!-- START EQUITY ACCOUNTS LISTING -->
							        	<tr>
							        		<td class="font-weight-semibold text-center grd-bg-orange" colspan="3">
							        			<strong>OWNER'S EQUITY</strong>
							        		</td>
							        	</tr>
						                <?php 
										$params['orderby']='title ASC';
										$filter['type']=$this->accounts_m->TYPE_CAPITAL;
										$accounts=$this->accounts_m->get_rows($filter,$params);
										foreach($accounts as $row){	
											$net_balance=0;				
											$cr_balance=0;
											$dr_balance=0;
											$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
											$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
											$net_balance=abs($rdr_balance)-abs($rcr_balance);
											if($net_balance==0 && $list_zero){continue;}
											$t_equity+=$net_balance;
											?>
								            <tr>
								                <td class="text-center" width="60%"><?php print $row['title'];?> Account</td>
								                <td class="text-center" width="20%"><?php print $net_balance;?></td>
								                <td class="text-center" width="20%"></td>
								            </tr>
								        <?php } ?>

							            <tr>
							                <td class="text-center">Net Income</td>
							                <td class="text-center"><?php print $t_income;?></td>
							                <td></td>
							            </tr>
							        	<tr>
							        		<td class="font-weight-bold text-center grd-bg-default" width="60%">
							        			<strong>Total Owner Equitry+Income</strong>
							        		</td>
							        		<td width="20%"></td>
							        		<td width="20%"><strong><?php print $t_equity+$t_income; ?></strong></td>
							        	</tr>
								        <!-- END EQUITY ACCOUNT LISTING -->
							        	<tr>
							        		<td class="font-weight-bold text-center grd-bg-default" width="60%">
							        			<strong>Total Liabilities+Equity</strong>
							        		</td>
							        		<td width="20%"></td>
							        		<td width="20%"><strong><?php print $t_equity+$t_income+$t_liability; ?></strong></td>
							        	</tr>



									        </tbody>
									    </table>
									    <br>
									</div>						
								</div>
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
			<?php print $this->config->item('app_print_code');?>5016 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
			</div>	

			</page>

		<?php }
		break;
		///END OF BALANCE SHEET STATEMENT


		///START OF INCOME STATEMENT
		case 'income':{ ?>
			<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">
			
			<div class="page-header" style="text-align: center">
				<div class="row">
					<div class="col-sm-2">
						<span class="float-right page-header-image">
							<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="72" height="72" alt=""></span>		
					</div>
					<div class="col-sm-10 font-family-arial-black">
						<span class="font-weight-bold page-form-name"><?php print $print_page_title;?></span><br>
						<span class="font-weight-semibold page-campus-name">
							<?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?> - <?php print $this->CAMPUS->name;?></span><br>
						<span class="font-weight-semibold page-campus-contact">
							For Accounting Period: <?php print $selected_period->title;?></span>			
					</div>
				</div>		
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

					<!-- block -->
					<div class="row form-block">
						<div class="col-sm-12 form-block-head" style="padding: 7pt;">
						<span class="text-center">
							Income Statement <br><?php print $from_date;?> - <?php print $to_date;?>
						</span>
						</div>
						<div class="col-sm-12 form-block-body">
							
							<div class="row">
								<div class="col-sm-12">
							    <table class="table table-sm">
							        <!-- <thead>
							            <tr>
							                <th class="font-weight-bold text-center" style="width: 60%;"></th>
							                <th class="font-weight-bold text-center"></th>
							                <th class="font-weight-bold text-center"></th>
							            </tr>
							        </thead> -->
							        <tbody>
							        	<?php 
							        		$t_revenue=0;
							        		$t_expenses=0;
										 ?>
							        	 <!-- START REVENUE ACCOUNTS LISTING -->
							        	 <tr style="border-color: white;">
							        	 	<td width="60%"></td>
							        	 	<td width="20%"></td>
							        	 	<td width="20%"></td>
							        	 </tr>
							        	<tr>
							        		<td class="font-weight-semibold text-center grd-bg-default" colspan="3">
							        			<strong>REVENUE</strong>
							        		</td>
							        	</tr>
						                <?php 
										$params['orderby']='title ASC';
										$filter['type']=$this->accounts_m->TYPE_REVENUE;
										$accounts=$this->accounts_m->get_rows($filter,$params);
										foreach($accounts as $row){					
											$cr_balance=0;
											$dr_balance=0;
											$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
											$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
											$net_balance=abs($rdr_balance)-abs($rcr_balance);
											if($net_balance==0 && $list_zero){continue;}
											$t_revenue+=abs($net_balance);
											?>
								            <tr>
								                <td class="text-center"><?php print $row['title'];?> Account</td>
								                <td class="text-center"><?php print abs($net_balance);?></td>
								                <td class="text-center"></td>
								            </tr>
								        <?php } ?>
							        	<tr>
							        		<td class="font-weight-bold text-center grd-bg-orange">
							        			<strong>Total Revenue</strong>
							        		</td>
							        		<td></td>
							        		<td><strong><?php print $t_revenue; ?></strong></td>
							        	</tr>
								        <!-- END REVENUE ACCOUNT LISTING -->

							        	 <!-- START EXPENSE ACCOUNTS LISTING -->
							        	<tr>
							        		<td class="font-weight-semibold text-center grd-bg-default" colspan="3">
							        			<strong>EXPENSES</strong>
							        		</td>
							        	</tr>
						                <?php 
										$params['orderby']='title ASC';
										$filter['type']=$this->accounts_m->TYPE_EXPENSE;
										$accounts=$this->accounts_m->get_rows($filter,$params);
										foreach($accounts as $row){					
											$cr_balance=0;
											$dr_balance=0;
											$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
											$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
											$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
											$net_balance=abs($rdr_balance)-abs($rcr_balance);
											if($net_balance==0 && $list_zero){continue;}
											$t_expenses+=abs($net_balance);
											?>
								            <tr>
								                <td class="text-center"><?php print $row['title'];?> Account</td>
								                <td class="text-center"><?php print abs($net_balance);?></td>
								                <td class="text-center"></td>
								            </tr>
								        <?php } ?>
							        	<tr>
							        		<td class="font-weight-bold text-center grd-bg-orange">
							        			<strong>Total Expenses</strong>
							        		</td>
							        		<td></td>
							        		<td><strong><?php print $t_expenses; ?></strong></td>
							        	</tr>
							        	<tr>
							        		<td class="font-weight-bold text-center grd-bg-orange">
							        			<strong>Net Income</strong>
							        		</td>
							        		<td></td>
							        		<td><strong><?php print $t_revenue-$t_expenses; ?></strong></td>
							        	</tr>
								        <!-- END EXPENSE ACCOUNT LISTING -->


							        </tbody>
							    </table>			
								</div>
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
			<?php print $this->config->item('app_print_code');?>5017 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
			</div>	

			</page>

		<?php }
		break;
		///END OF INCOME STATEMENT


		///START OF TRIAL BALANCE STATEMENT - DEFAULT STATEMENT
		default:{?>
			
			<page size="<?php print $this->PRINT_PAGE_SIZE;?>" layout="<?php print $this->PRINT_PAGE_LAYOUT;?>" class="editable">

			<div class="page-header" style="text-align: center">
				<div class="row">
					<div class="col-sm-2">
						<span class="float-right page-header-image">
							<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded" width="72" height="72" alt=""></span>		
					</div>
					<div class="col-sm-10 font-family-arial-black">
						<span class="font-weight-bold page-form-name"><?php print $print_page_title;?></span><br>
						<span class="font-weight-semibold page-campus-name">
							<?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?> - <?php print $this->CAMPUS->name;?></span><br>
						<span class="font-weight-semibold page-campus-contact">
							For Accounting Period: <?php print $selected_period->title;?></span>			
					</div>
				</div>		
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

					<!-- block -->
					<div class="row form-block">
						<div class="col-sm-12 form-block-head" style="padding: 7pt;">
						<span class="text-center">
							Trial Balance <br><?php print $from_date;?> - <?php print $to_date;?>
						</span>
						</div>
						<div class="col-sm-12 form-block-body">
							
							<div class="row">
								<div class="col-sm-12">		
									<br><!-- 
									<center><p>List of all vouchers paid by students.</p></center> -->
				
								    <table class="table table-sm">
								        <thead>
								            <tr>
								                <th class="font-weight-bold text-center" style="width: 60%;">Account Name</th>
								                <th class="font-weight-bold text-center">Debit Balance (Dr)</th>
								                <th class="font-weight-bold text-center">Credit Balance (Cr)</th>
								            </tr>
								        </thead>
								        <tbody>
								        	<?php 
											$tcr_balance=0;
											$tdr_balance=0;
											$t_balance=0;

								        	 ?>

								        	 <!-- START ASSETS ACCOUNTS LISTING -->
								        	<tr>
								        		<td class="font-weight-semibold text-center grd-bg-default" colspan="3">
								        			<strong>Asset Accounts - Current Assets</strong>
								        		</td>
								        	</tr>
							                <?php 
											$params['orderby']='title ASC';
											$filter['type']=$this->accounts_m->TYPE_ASSETS;
											$accounts=$this->accounts_m->get_rows($filter,$params);
											foreach($accounts as $row){					
												$cr_balance=0;
												$dr_balance=0;
												$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
												$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
												$net_balance=abs($rdr_balance)-abs($rcr_balance);
												if($net_balance==0 && $list_zero){continue;}
												//positive net balance in assets is debit balance
												if($net_balance>0){
													$dr_balance=abs($net_balance);
													$tdr_balance+=abs($net_balance);
												}else{
													$cr_balance=abs($net_balance);
													$tcr_balance+=abs($net_balance);
												}
												?>
									            <tr>
									                <td class="text-center"><?php print $row['title']; ?> Account</td>
									                <td class="text-center"><?php print $dr_balance!=0?$dr_balance:'';?></td>
									                <td class="text-center"><?php print $cr_balance!=0?$cr_balance:'';?></td>
									            </tr>
									        <?php } ?>
									        <!-- END ASSETS ACCOUNT LISTING -->

								        	 <!-- START LIABILITY ACCOUNTS LISTING -->
								        	<tr>
								        		<td class="font-weight-semibold text-center grd-bg-default" colspan="3">
								        			<strong>Liability Accounts</strong>
								        		</td>
								        	</tr>
							                <?php 
											$params['orderby']='title ASC';
											$filter['type']=$this->accounts_m->TYPE_LIABILITY;
											$accounts=$this->accounts_m->get_rows($filter,$params);
											foreach($accounts as $row){					
												$cr_balance=0;
												$dr_balance=0;
												$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
												$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
												$net_balance=abs($rdr_balance)-abs($rcr_balance);
												if($net_balance==0 && $list_zero){continue;}
												//positive net balance in assets is debit balance
												if($net_balance>0){
													$dr_balance=abs($net_balance);
													$tdr_balance+=abs($net_balance);
												}else{
													$cr_balance=abs($net_balance);
													$tcr_balance+=abs($net_balance);
												}
												?>
									            <tr>
									                <td class="text-center"><?php print $row['title']; ?> Account</td>
									                <td class="text-center"><?php print $dr_balance!=0?$dr_balance:'';?></td>
									                <td class="text-center"><?php print $cr_balance!=0?$cr_balance:'';?></td>
									            </tr>
									        <?php } ?>
									        <!-- END LIABILITY ACCOUNT LISTING -->

								        	 <!-- START EQUITY ACCOUNTS LISTING -->
								        	<tr>
								        		<td class="font-weight-semibold text-center grd-bg-default" colspan="3">
								        			<strong>Equity Accounts</strong>
								        		</td>
								        	</tr>
							                <?php 
											$params['orderby']='title ASC';
											$filter['type']=$this->accounts_m->TYPE_CAPITAL;
											$accounts=$this->accounts_m->get_rows($filter,$params);
											foreach($accounts as $row){					
												$cr_balance=0;
												$dr_balance=0;
												$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
												$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
												$net_balance=abs($rdr_balance)-abs($rcr_balance);
												if($net_balance==0 && $list_zero){continue;}
												//positive net balance in assets is debit balance
												if($net_balance>0){
													$dr_balance=abs($net_balance);
													$tdr_balance+=abs($net_balance);
												}else{
													$cr_balance=abs($net_balance);
													$tcr_balance+=abs($net_balance);
												}
												?>
									            <tr>
									                <td class="text-center"><?php print $row['title']; ?> Account</td>
									                <td class="text-center"><?php print $dr_balance!=0?$dr_balance:'';?></td>
									                <td class="text-center"><?php print $cr_balance!=0?$cr_balance:'';?></td>
									            </tr>
									        <?php } ?>
									        <!-- END EQUITY ACCOUNT LISTING -->

								        	 <!-- START REVENUE ACCOUNTS LISTING -->
								        	<tr>
								        		<td class="font-weight-semibold text-center grd-bg-default" colspan="3">
								        			<strong>Revenue Accounts</strong>
								        		</td>
								        	</tr>
							                <?php 
											$params['orderby']='title ASC';
											$filter['type']=$this->accounts_m->TYPE_REVENUE;
											$accounts=$this->accounts_m->get_rows($filter,$params);
											foreach($accounts as $row){					
												$cr_balance=0;
												$dr_balance=0;
												$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
												$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
												$net_balance=abs($rdr_balance)-abs($rcr_balance);
												if($net_balance==0 && $list_zero){continue;}
												//positive net balance in assets is debit balance
												if($net_balance>0){
													$dr_balance=abs($net_balance);
													$tdr_balance+=abs($net_balance);
												}else{
													$cr_balance=abs($net_balance);
													$tcr_balance+=abs($net_balance);
												}
												?>
									            <tr>
									                <td class="text-center"><?php print $row['title']; ?> Account</td>
									                <td class="text-center"><?php print $dr_balance!=0?$dr_balance:'';?></td>
									                <td class="text-center"><?php print $cr_balance!=0?$cr_balance:'';?></td>
									            </tr>
									        <?php } ?>
									        <!-- END REVENUE ACCOUNT LISTING -->

								        	 <!-- START EXPENSE ACCOUNTS LISTING -->
								        	<tr>
								        		<td class="font-weight-semibold text-center grd-bg-default" colspan="3">
								        			<strong>Expense Accounts</strong>
								        		</td>
								        	</tr>
							                <?php 
											$params['orderby']='title ASC';
											$filter['type']=$this->accounts_m->TYPE_EXPENSE;
											$accounts=$this->accounts_m->get_rows($filter,$params);
											foreach($accounts as $row){					
												$cr_balance=0;
												$dr_balance=0;
												$dr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND debit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$cr_filter="campus_id=$this->CAMPUSID AND period_id=$period_id AND credit_account_id=".$row['mid']." AND jd>=$from_jd AND jd <= $to_jd";
												$rdr_balance=$this->accounts_ledger_m->get_column_result('debit_amount',$dr_filter);
												$rcr_balance=$this->accounts_ledger_m->get_column_result('credit_amount',$cr_filter);
												$net_balance=abs($rdr_balance)-abs($rcr_balance);
												if($net_balance==0 && $list_zero){continue;}
												//positive net balance in assets is debit balance
												if($net_balance>0){
													$dr_balance=abs($net_balance);
													$tdr_balance+=abs($net_balance);
												}else{
													$cr_balance=abs($net_balance);
													$tcr_balance+=abs($net_balance);
												}
												?>
									            <tr>
									                <td class="text-center"><?php print $row['title']; ?> Account</td>
									                <td class="text-center"><?php print $dr_balance!=0?$dr_balance:'';?></td>
									                <td class="text-center"><?php print $cr_balance!=0?$cr_balance:'';?></td>
									            </tr>
									        <?php } ?>
									        <!-- END EXPENSE ACCOUNT LISTING -->



								        </tbody>
								        <tfoot>
								            <tr>
								                <th class="font-weight-semibold text-center">Totals</th>
								                <th class="font-weight-semibold text-center"><?php print $tdr_balance;?></th>
								                <th class="font-weight-semibold text-center"><?php print $tcr_balance;?></th>
								            </tr>
								        </tfoot>
								    </table>
								    <br>
								</div>
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
			<?php print $this->config->item('app_print_code');?>5015 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
			</div>	

			</page>

		<?php }
		break;
		///END OF TRIAL BALANCE STATEMENT
	} ?>



	</div>
	<!-- ------------------------------/printing-------------------------------------------------------- -->
	<div>
		<p>A positive balance in Asset or Expense category accounts is a debit balance. A positive balance in a Liability, Equity , or Revenue category, is a Credit balance.</p>
	</div>
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
							<input type="checkbox" name="listzero" 
								<?php print isset($form['listzero']) ? 'checked':'';?>>
								Hide Zero Entries							
						</div>		
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
							<label class="text-muted">From Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md datepicker" placeholder="From Date" name="fromdate" value="<?php print isset($form['fromdate']) && strlen($form['fromdate'])>2 ? $from_date=$form['fromdate'] : ''; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted"> To Date</label>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control form-control-md datepicker" placeholder="To Date" name="todate" value="<?php print isset($form['todate']) && strlen($form['todate'])>2 ? $from_date=$form['todate'] : ''; ?>">
								<div class="form-control-feedback form-control-feedback-md">
									<i class="icon-calendar"></i>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<label class="text-muted">Statement</label>          
							<select class="form-control select" name="stmt" data-fouc>
						    <option value="" />All Genders
						    <option value="trialbalance" <?php print isset($form['stmt'])&&$form['stmt']=='trialbalance'?'selected':'';?>/>Trial Balance
						    <option value="balance" <?php print isset($form['stmt'])&&$form['stmt']=='balance'?'selected':'';?>/>Balance Sheet
						    <option value="income" <?php print isset($form['stmt'])&&$form['stmt']=='income'?'selected':'';?>/>Income Statement
							</select>
						</div>
					<div class="col-sm-3">
						<label class="text-muted">Accounting Period</label>          
						<select class="form-control select" name="period" data-fouc>
						<option value="" />Current Period
						<?php foreach ($accounting_periods as $key=>$val){?>            
							<option value="<?php print $key;?>" <?php print isset($form['period'])&&$form['period']==$key?'selected':'';?>/><?php print $val;?>
						<?php }?>
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

