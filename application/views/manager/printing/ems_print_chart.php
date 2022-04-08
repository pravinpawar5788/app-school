<?php
/// print_r($form);
$this_url=current_url().'?';
$show_files=false;
if(isset($form['files']) ){$show_files=true;}
foreach ($form as $key => $value) {$this_url.='&'.urlencode($key).'='.urlencode($value);}
//////////////////////////////////////////////////////////////////
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
			                <th class="font-weight-semibold" width="15%">Form</th>
			                <th class="font-weight-semibold" width="30%">Name</th>
			                <th class="font-weight-semibold">Description</th>
			                <?php if($show_files){ ?>
			                <th class="font-weight-semibold">Printing File</th>
				            <?php } ?>
			            </tr>
			        </thead>
			        <tbody>

			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>0000</td>
			        		<td>EMS Printing Chart</td>
			        		<td>Show chart of all forms,certificates and reports availeable for printing </td>
			                <?php if($show_files){ ?>
			                	<td>ems_print_chart</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>0001</td>
			        		<td>Stationery Items List</td>
			        		<td>List of stationery item registered in inventory module</td>
			                <?php if($show_files){ ?>
			                	<td>stationary_item_list</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>0002</td>
			        		<td>Stationery History</td>
			        		<td>History of stationery item selling and purchase</td>
			                <?php if($show_files){ ?>
			                	<td>stationary_history</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>0003</td>
			        		<td>Library Book List</td>
			        		<td>List of books availeable in library</td>
			                <?php if($show_files){ ?>
			                	<td>library_book_list</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>0004</td>
			        		<td>Passenger List</td>
			        		<td>List of passengers registered in transport module</td>
			                <?php if($show_files){ ?>
			                	<td>passenger_list</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>1001</td>
			        		<td>Registered Staff List</td>
			        		<td>List of staff with dynamic data </td>
			                <?php if($show_files){ ?>
			                	<td>staff_list</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>1002</td>
			        		<td>Staff Allownces List</td>
			        		<td>List of allowances alotted to staff </td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>1003</td>
			        		<td>Staff Loan History</td>
			        		<td>Loan history of staff </td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2001</td>
			        		<td>Registered Students List</td>
			        		<td>List of students with dynamic data </td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2002</td>
			        		<td>Fee Marginal Concession Report</td>
			        		<td>Student fee marginal concession report  </td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2003</td>
			        		<td>Student Admission Form</td>
			        		<td>Student admission form</td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2004</td>
			        		<td>Student Fee History List</td>
			        		<td>List of fee history student</td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2005</td>
			        		<td>Class Students List</td>
			        		<td>List of class students</td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2006</td>
			        		<td>Class Strength Report</td>
			        		<td>Class strength report for current session</td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2007</td>
			        		<td>Class Monthly Attendance Report</td>
			        		<td>Class attendance report for a specific month</td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>2008</td>
			        		<td>Student History Report</td>
			        		<td>History of student</td>
			                <?php if($show_files){ ?>
			                	<td>student_history</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>3001</td>
			        		<td>Attendance Report List</td>
			        		<td>List of students/staff present,absent or on leave </td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>3002</td>
			        		<td>Staff Daily Attendance Report</td>
			        		<td>Daily attendance report of staff members</td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>3003</td>
			        		<td>Student Daily Attendance Report</td>
			        		<td>Daily attendance report of students of all classes</td>
			                <?php if($show_files){ ?>
			                	<td></td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>3004</td>
			        		<td>Staff Monthly Attendance Summary</td>
			        		<td>Monthly attendance report of all staff members</td>
			                <?php if($show_files){ ?>
			                	<td>report_attendance_stf_monthsimple</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>3005</td>
			        		<td>Staff Attendance Register</td>
			        		<td>Staff attendance register for all months</td>
			                <?php if($show_files){ ?>
			                	<td>report_attendance_stf_monthdetail</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>3006</td>
			        		<td>Student Monthly Attendance Summary</td>
			        		<td>Monthly attendance report of all students</td>
			                <?php if($show_files){ ?>
			                	<td>report_attendance_std_monthsimple</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>3007</td>
			        		<td>Student Attendance Register</td>
			        		<td>Student attendance register for all months</td>
			                <?php if($show_files){ ?>
			                	<td>report_attendance_std_monthdetail</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>4001</td>
			        		<td>Student Award List</td>
			        		<td>Exam result submission form</td>
			                <?php if($show_files){ ?>
			                	<td>form_exam_resultform_blank</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>4002</td>
			        		<td>Student Monthly Progress Report</td>
			        		<td>Class wise monthly progress report for all students</td>
			                <?php if($show_files){ ?>
			                	<td>report_exam_class_prglist</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>4003</td>
			        		<td>Student Term Progress Report</td>
			        		<td>Class wise term progress report for all students</td>
			                <?php if($show_files){ ?>
			                	<td>report_exam_class_term_prglist</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5001</td>
			        		<td>Revenue Log Report</td>
			        		<td>Log of revenue</td>
			                <?php if($show_files){ ?>
			                	<td>report_income</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5002</td>
			        		<td>Expenses Log Report</td>
			        		<td>Log of expenses</td>
			                <?php if($show_files){ ?>
			                	<td>report_expenses</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5003</td>
			        		<td>Journal Log</td>
			        		<td>Log of entries in journal</td>
			                <?php if($show_files){ ?>
			                	<td>report_ledger</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5004</td>
			        		<td>Daily Fee Collection Report</td>
			        		<td>Daily fee collection report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_std_feereceived</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5005</td>
			        		<td>Outstanding Fee Report</td>
			        		<td>Outstanding fee balances list of all students/class wise</td>
			                <?php if($show_files){ ?>
			                	<td>form_finance_feecollection</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5006</td>
			        		<td>Daily Advance Collection Report</td>
			        		<td>Daily advance fee collection report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_std_advancereceived</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5007</td>
			        		<td>Fee Collection Report</td>
			        		<td>Day wise fee collection report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_feecollection_daily</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5008</td>
			        		<td>Advance Collection Report</td>
			        		<td>Day wise advance fee collection report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_advancecollection_daily</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5009</td>
			        		<td>Fee Defaulters Report</td>
			        		<td>Fee defaulters report. both unpaid and partialy paid</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_feedefaulter</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5010</td>
			        		<td>Pending Fee Voucher Report</td>
			        		<td>Pending fee vouchers report including unpaid and partialy paid</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_list_std_unpaid</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5011</td>
			        		<td>Paid Fee Voucher Report</td>
			        		<td>Paid fee vouchers report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_list_std_paid</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5012</td>
			        		<td>Student Concession Report</td>
			        		<td>Student concession given report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_std_concession</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5013</td>
			        		<td>Staff Payment Report</td>
			        		<td>Staff payment vouchers report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_list_stf_paid</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5014</td>
			        		<td>Staff Balance Report</td>
			        		<td>Staff balance report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_stf_rembalance</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5015</td>
			        		<td>Trial Balance</td>
			        		<td>Trial balance report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_trial_balance</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5016</td>
			        		<td>Balance Sheet</td>
			        		<td>Balance sheet</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_trial_balance</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5017</td>
			        		<td>PL Statement</td>
			        		<td>Profit &amp; Loss Statement / income statement</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_trial_balance</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td><?php print $this->config->item('app_print_code');?>5018</td>
			        		<td>Detailed Fee Collection Report</td>
			        		<td>Detailed fee collection report</td>
			                <?php if($show_files){ ?>
			                	<td>report_finance_feecollection</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td>XXX-00000</td>
			        		<td>Student Fee Slips</td>
			        		<td>Student fee slips default, single, page, bank templates</td>
			                <?php if($show_files){ ?>
			                	<td>finance_fee_slips</td>
			                <?php } ?>
			        	</tr>
			        	<tr>
			        		<td>XXX-00000</td>
			        		<td>Staff Salary Slips</td>
			        		<td>Staff salar slips</td>
			                <?php if($show_files){ ?>
			                	<td>finance_pay_slips</td>
			                <?php } ?>
			        	</tr>

			        </tbody>
			    </table>			    
			</div>
		    <br><br><br>
		    <p><code><?php print $this->config->item('app_print_code');?>0*</code> <span class="text-muted">General Forms</span></p>
		    <p><code><?php print $this->config->item('app_print_code');?>1*</code> <span class="text-muted">Staff Forms</span></p>
		    <p><code><?php print $this->config->item('app_print_code');?>2*</code> <span class="text-muted">Student Forms</span></p>
		    <p><code><?php print $this->config->item('app_print_code');?>3*</code> <span class="text-muted">Attendance Forms</span></p>
		    <p><code><?php print $this->config->item('app_print_code');?>4*</code> <span class="text-muted">Exam Forms</span></p>
		    <p><code><?php print $this->config->item('app_print_code');?>5*</code> <span class="text-muted">Finance Forms</span></p>
		    <p><code>XXX-0*</code> <span class="text-muted">Miscellaneous</span></p>
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
	<?php print $this->config->item('app_print_code');?>0000 <span class="page-footer-right"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></span>
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
