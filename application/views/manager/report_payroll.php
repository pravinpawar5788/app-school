<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
///////////////////////////////////////////////////////////////////////////////////////////// 
$session=$this->session_m->getActiveSession();
$filter=array();
$filter['campus_id']=$this->CAMPUSID;

$total_paid=$this->stf_pay_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'status'=>$this->std_fee_voucher_m->STATUS_PAID),'',true);
$total_unpaid=$this->stf_pay_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'status'=>$this->std_fee_voucher_m->STATUS_UNPAID),'',true);
$total_partial_paid=$this->stf_pay_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'status'=>$this->std_fee_voucher_m->STATUS_PARTIAL_PAID),'',true);
////////////////////////////////////////////////////////////////////////
$total_payable=$this->stf_pay_voucher_m->get_payable_amount(array('campus_id'=>$this->CAMPUSID));
$today_paid=$this->stf_pay_entry_m->get_column_result('amount',array('campus_id'=>$this->CAMPUSID,'jd'=>$this->stf_pay_voucher_m->todayjd,'type'=>$this->stf_pay_entry_m->TYPE_PAY,'operation'=>$this->stf_pay_entry_m->OPT_MINUS));
$monthly_paid=$this->stf_pay_entry_m->get_column_result('amount',array('campus_id'=>$this->CAMPUSID,'month_number'=>$this->stf_pay_voucher_m->month_number,'type'=>$this->stf_pay_entry_m->TYPE_PAY,'operation'=>$this->stf_pay_entry_m->OPT_MINUS));

// $sessions=$this->session_m->get_rows(array(),array('orderby'=>'status ASC,mid DESC') );
$classes=$this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'title ASC') );

?>
<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Reports</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>

				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
				<div class="breadcrumb">
					<a class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a class="breadcrumb-item">Reports</a>
					<span class="breadcrumb-item active">Payroll Report</span>
				</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">
			<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>


			<!-- Search field -->
			<!-- <div class="card search-area" >
				<div class="card-header">
					<h5 class="mb-3"></h5>
					<div class="form-group">							
						<?php echo form_open_multipart($this->CONT_ROOT.'payroll');?> 							
						<div class="row">
							<div class="col-sm-4">
								<label class="text-muted">Select Year</label>
								<select class="form-control select" name="year" data-fouc>
									<option value="">Current Year</option>
									<?php 
									$year=$this->std_fee_voucher_m->year;
									while($year >= $this->SETTINGS[$this->system_setting_m->_INSTALL_YEAR]){?>            
									    <option value="<?php print $year;?>" /><?php print $year;?>
								    <?php $year--;}?>
								</select>
							</div>						
							<div class="col-sm-4">
								<button class="btn btn-success mt-4">
									<span class="font-weight-bold"> Load</span> <i class="icon-circle-right2 ml-2"></i>
								</button> 
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>

				</div>
			</div> -->
			<!-- /search field -->

			
			<!-- Quick stats boxes -->
			<div class="row">
				<div class="col-sm-3">
					<!-- Members online -->
					<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
					<div class="card <?php print $bg_color.$t_color;?>">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_payable;?></h3>
								<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print $this->stf_pay_entry_m->date;?></span>
		                	</div>					                	
		                	<div>
								<strong>Amount Payable</strong>
								<div class="font-size-sm opacity-85">Total payable amount</div>
							</div>
						</div>

						<div class="container-fluid">
							<div id="members-online"></div>
						</div>
					</div>
					<!-- /members online -->
				</div>
				<div class="col-sm-3">
					<!-- Members online -->
					<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
					<div class="card <?php print $bg_color.$t_color;?>">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$monthly_paid;?></h3>
								<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto"><?php print date('M-Y');?></span>
		                	</div>					                	
		                	<div>
								<strong>Monthly Paid</strong>
								<div class="font-size-sm opacity-85">Total amount paid in the month</div>
							</div>
						</div>

						<div class="container-fluid">
							<div id="members-online"></div>
						</div>
					</div>
					<!-- /members online -->
				</div>
				<div class="col-sm-3">
					<!-- Members online -->
					<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
					<div class="card <?php print $bg_color.$t_color;?>">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$today_paid;?></h3>
								<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto"><?php print date('d-M-Y');?></span>
		                	</div>					                	
		                	<div>
								<strong>Today Paid</strong>
								<div class="font-size-sm opacity-85">Total amount paid today</div>
							</div>
						</div>

						<div class="container-fluid">
							<div id="members-online"></div>
						</div>
					</div>
					<!-- /members online -->
				</div>
				<div class="col-sm-3">
					<!-- Members online -->
					<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
					<div class="card <?php print $bg_color.$t_color;?>">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0"><?php print $total_partial_paid;?></h3>
								<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print $this->stf_pay_entry_m->date;?></span>
		                	</div>					                	
		                	<div>
								<strong>Partialy Paid Vouchers</strong>
								<div class="font-size-sm opacity-85">some Pay has been paid</div>
							</div>
						</div>

						<div class="container-fluid">
							<div id="members-online"></div>
						</div>
					</div>
					<!-- /members online -->
				</div>
				<div class="col-sm-3">
					<!-- Members online -->
					<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
					<div class="card <?php print $bg_color.$t_color;?>">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0"><?php print $total_paid;?></h3>
								<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print $this->stf_pay_entry_m->date;?></span>
		                	</div>					                	
		                	<div>
								<strong>Paid Vouchers</strong>
								<div class="font-size-sm opacity-85">Total vouchers marked as paid</div>
							</div>
						</div>

						<div class="container-fluid">
							<div id="members-online"></div>
						</div>
					</div>
					<!-- /members online -->
				</div>
				<div class="col-sm-3">
					<!-- Members online -->
					<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
					<div class="card <?php print $bg_color.$t_color;?>">
						<div class="card-body">
							<div class="d-flex">
								<h3 class="font-weight-semibold mb-0"><?php print $total_unpaid;?></h3>
								<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print $this->stf_pay_entry_m->date;?></span>
		                	</div>					                	
		                	<div>
								<strong>Un Paid Vouchers</strong>
								<div class="font-size-sm opacity-85">Total vouchers not yet paid</div>
							</div>
						</div>

						<div class="container-fluid">
							<div id="members-online"></div>
						</div>
					</div>
					<!-- /members online -->
				</div>


			</div>
			<!-- /quick stats boxes -->

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<?php
			$this->load->view($LIB_VIEW_DIR.'includes/footer_inc');
			?>
			<!-- /footer -->

		</div>
		<!-- /main content -->




