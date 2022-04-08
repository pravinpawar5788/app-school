<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
$notifications=$this->note_m->get_rows(array('campus_id'=>$this->CAMPUSID,'status'=>'active'));
$session=$this->session_m->getActiveSession();
//campus data filteration
$filter=array('campus_id'=>$this->CAMPUSID);

$total_students=$this->student_m->get_rows($filter,'',true);
$total_staff=$this->staff_m->get_rows($filter,'',true);
$total_classes=$this->class_m->get_rows($filter,'',true);
$total_sections=$this->class_section_m->get_rows($filter,'',true);
$total_std_fee_vouchers=$this->std_fee_voucher_m->get_rows($filter,'',true);
$total_stf_pay_vouchers=$this->stf_pay_voucher_m->get_rows($filter,'',true);
$total_fee_unpaid_vouchers=$this->std_fee_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'status'=>$this->std_fee_voucher_m->STATUS_UNPAID),'',true);
////////////////////////////////////////////////////////////////////////
$filter['month']=$this->user_m->month;
$filter['year']=$this->user_m->year;
$month_income=$this->income_m->get_column_result('amount',$filter);
$month_expense=$this->expense_m->get_column_result('amount',$filter);
$filter['day']=$this->user_m->day;
$today_income=$this->income_m->get_column_result('amount',$filter);
$today_expense=$this->expense_m->get_column_result('amount',$filter);
$today_fee=$this->std_fee_entry_m->get_column_result('amount',array('campus_id'=>$this->CAMPUSID,'jd'=>$this->std_fee_voucher_m->todayjd,'type'=>$this->std_fee_entry_m->TYPE_FEE,'operation'=>$this->std_fee_entry_m->OPT_MINUS));
$total_active_students=$this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'status'=>$this->student_m->STATUS_ACTIVE),'',true);
$total_active_staff=$this->staff_m->get_rows(array('campus_id'=>$this->CAMPUSID,'status'=>$this->staff_m->STATUS_ACTIVE),'',true);

$history=$this->system_history_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('limit'=>10));
?>
<!-- Main content --->
		<div class="content-wrapper">
			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>

				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<div class="breadcrumb-elements-item dropdown p-0">
								<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
									<i class="icon-printer mr-2"></i>Print
								</a>
								<div class="dropdown-menu dropdown-menu-left">
									<a href="<?php print $this->CONT_ROOT.'printing/list/?';?>" class="dropdown-item"><i class="icon-clipboard"></i>Form &amp; Report Chart</a>
								</div>
							</div>
							<a href="<?php print $this->APP_ROOT.'docs/dashboard';?>" target="_blank" class="breadcrumb-elements-item">
								<i class="icon-lifebuoy mr-2"></i>
								Docs
							</a>
						</div>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">
				<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>
				
				<?php
				if(count($notifications)>0){?>
				 <!-- Dashboard content -->
				<div class="row">
				<div class="col-lg-12">
				<?php foreach ($notifications as $note){?>
				<!-- alert -->
				<div class="alert <?php print  'alert-'.$note['type'];?> alert-styled-left">
				    <strong>Notification! </strong><?php print htmlspecialchars_decode($note['detail']); ?>
				</div>
				 <!-- /alert -->
				    <?php }?>
				</div>
				</div>
				<?php }?>
				<!-- Dashboard content -->
				<div class="row">
					<div class="col-xl-8">
					<?php
					//show cards only if super admin is loggedin
					if($this->IS_MANAGER || $this->IS_ADMIN){
					?>

						<!-- Quick stats boxes -->
						<div class="row">
							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<a href="<?php print $this->LIB_CONT_ROOT.'student'?>">
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_active_students;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">Session <?php print $session->title;?></span>
					                	</div>					                	
					                	<div>
											<strong>Active Students</strong>
											<div class="font-size-sm opacity-85">Total Students: <?php print $total_students;?></div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								</a>
								<!-- /members online -->

							</div>

							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<a href="<?php print $this->LIB_CONT_ROOT.'staff'?>">
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_active_staff;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Active Staff</strong>
											<div class="font-size-sm opacity-85">Total Staff: <?php print $total_staff;?></div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								</a>
								<!-- /members online -->

							</div>


							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<a href="<?php print $this->LIB_CONT_ROOT.'classes'?>">
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_classes;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Total Classes</strong>
											<div class="font-size-sm opacity-85">Campus Sections: <?php print $total_sections;?></div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								</a>
								<!-- /members online -->

							</div>


							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<a href="<?php print $this->LIB_CONT_ROOT.'finance/income'?>">
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $today_income;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto"><?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Income</strong>
											<div class="font-size-sm opacity-85"><?php print date('F'); ?> Income <?php print $month_income; ?></div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								</a>
								<!-- /members online -->

							</div>


							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<a href="<?php print $this->LIB_CONT_ROOT.'finance/expense'?>">
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $today_expense;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto"><?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Expenses</strong>
											<div class="font-size-sm opacity-85"><?php print date('F'); ?> Expenses <?php print $month_expense; ?></div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								</a>
								<!-- /members online -->

							</div>


							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<a href="<?php print $this->LIB_CONT_ROOT.'finance/fee'?>">
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $today_fee;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto"><?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Fee Collected</strong>
											<div class="font-size-sm opacity-85">Unpaid Vouchers: <?php print $total_fee_unpaid_vouchers;?></div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								</a>
								<!-- /members online -->

							</div>


						</div>
						<!-- /quick stats boxes -->

						<!-- daily income - expense chart -->
						<div class="card">
							<div class="card-body">
								<div id="container-iechart" style="width: 100%; height: 450px;"></div>
								<?php
								$days_string="";
								$income_string='';
								$expense_string='';
								$filter=array('campus_id'=>$this->CAMPUSID,'year'=>$this->student_m->year,'month'=>$this->student_m->month);
								$days_in_month=days_in_month($this->student_m->month,$this->student_m->year);
								for ($i=1; $i <= $days_in_month; $i++) {
									$days_string.=$i;
									$filter['day']=$i;
									$income=$this->income_m->get_column_result('amount',$filter);
									$expense=$this->expense_m->get_column_result('amount',$filter);
									$income_string.=intval($income);
									$expense_string.=intval($expense);
									//add comma only valid next month
									if($i<$days_in_month){
										$days_string.=',';
										$income_string.=',';$expense_string.=',';	}
								}


								?>

								<script type="text/javascript">
									$(function () {
								    $('#container-iechart').highcharts({
								        chart: {
								            type: 'column'
								        },
								        title: {
								            text: 'Income/Expense Summary - <?php print month_string($this->student_m->month).', '.$this->student_m->year;?>'
								        },
								        xAxis: {
								            categories: [<?php print $days_string;?>]
								        },
								        yAxis: {
								            title: {
								                text: 'Amount'
								            }
								        },
								        series: [{
								            name: 'Income',
								            color: '<?php print get_random_hax_color();?>',
								            data: [<?php print $income_string;?>]
								        },{
								            name: 'Expenses',
								            color: '<?php print get_random_hax_color();?>',
								            data: [<?php print $expense_string;?>]
								        }],
								    });
									});
								</script>

							</div>
						</div>
						<!-- /daily income - expense chart -->
					<?php }?>
					</div>

					<div class="col-xl-4">


						<!-- Application status -->
						<div class="card">
							<?php
								$filter=array('campus_id'=>$this->CAMPUSID,'year'=>$this->student_m->year,'month'=>$this->student_m->month);
								$aday='d'.intval($this->student_m->day);
								$params=array('select'=>'mid,'.$aday);
								$present=0;$absent=0;$leave=0;$holiday=0;$other=0;
						    	$attendances=$this->std_attendance_m->get_rows($filter,$params);
						    	foreach($attendances as $row){
						    		switch ($row[$aday]) {
					    				case $this->std_attendance_m->LABEL_PRESENT: {
					    					$present++;}break;
					    				case $this->std_attendance_m->LABEL_LEAVE: {
					    					$leave++;}break;
					    				case $this->std_attendance_m->LABEL_HOLIDAY: {
					    					$holiday++;}break;			    				
					    				case $this->std_attendance_m->LABEL_ABSENT: {
					    					$absent++;}break;			    				
					    				default: { $other++;}break;
					    			}

						    	}


								?>
							<div class="card-header header-elements-inline">
								<h6 class="card-title">Daily Attendance Summary</h6>

							</div>

							<div class="card-body">
						        <ul class="list-unstyled mb-0">
						            <li class="mb-3">
						                <div class="d-flex align-items-center mb-1">Present Students <span class="text-muted ml-auto">
						                	<?php print $present;?></span></div>
										<div class="progress" style="height: 0.375rem;">
											<div class="progress-bar bg-success" style="width: <?php print $total_active_students>0 ? intval(($present/$total_active_students)*100) : 0;?>%">
											</div>
										</div>
						            </li>
						            <li class="mb-3">
						                <div class="d-flex align-items-center mb-1">Absent Students <span class="text-muted ml-auto">
						                	<?php print $absent;?></span></div>
										<div class="progress" style="height: 0.375rem;">
											<div class="progress-bar bg-danger" style="width: <?php print $total_active_students>0 ? intval(($absent/$total_active_students)*100) : 0;?>%">
											</div>
										</div>
						            </li>
						            <li class="mb-3">
						                <div class="d-flex align-items-center mb-1">Students On Leave <span class="text-muted ml-auto">
						                	<?php print $leave;?></span></div>
										<div class="progress" style="height: 0.375rem;">
											<div class="progress-bar bg-warning" style="width: <?php print $total_active_students>0 ? intval(($leave/$total_active_students)*100) : 0;?>%">
											</div>
										</div>
						            </li>
						            <li class="mb-3">
						                <div class="d-flex align-items-center mb-1">Student without attendance <span class="text-muted ml-auto">
						                	<?php print $total_active_students-($present+$absent+$leave+$holiday);?></span></div>
										<div class="progress" style="height: 0.375rem;">
											<div class="progress-bar bg-info" style="width: <?php print $total_active_students>0 ? intval((($total_active_students-($present+$absent+$leave+$holiday))/$total_active_students)*100) : 0;?>%">
											</div>
										</div>
						            </li>


						        </ul>
							</div>
						</div>
						<!-- /application status -->
						<!-- Thumbnail with feed -->
						<div class="card">
							<div class="card-body">
								<div class="list-feed">

									<?php foreach ($history as $row) { ?>

									<div class="list-feed-item border-warning-400">
										<div class="text-muted font-size-sm mb-1">
											<?php print $row['date'] ?></div>
										<span><?php print $row['message'];?></span>
									</div>
									<?php } ?>

								</div>
							</div>
						</div>
						<!-- /thumbnail with feed -->	

					</div>
				</div>
				<!-- /dashboard content -->

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<?php
			$this->load->view($LIB_VIEW_DIR.'includes/footer_inc');
			?>
			<!-- /footer -->

		</div>
		<!-- /main content -->




