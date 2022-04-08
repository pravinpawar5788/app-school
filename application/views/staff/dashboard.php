<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
$session=$this->session_m->getActiveSession();
$filter=array('staff_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID);
$total_allowance=$this->stf_allownce_m->get_column_result('amount',$filter);
$incharge_class=$this->class_m->get_by(array('incharge_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID),true);
///////////////////////////////////////////////////////////////////////////////
$history=$this->stf_history_m->get_rows(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$this->LOGIN_USER->mid),array('limit'=>5));
?>
<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<!-- <div class="header-elements d-none">
						<div class="d-flex justify-content-center">
							<a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
							<a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
							<a href="#" class="btn btn-link btn-float text-default"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
						</div>
					</div> -->
				</div>

				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<!-- <div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<a href="#" class="breadcrumb-elements-item">
								<i class="icon-comment-discussion mr-2"></i>
								Get in Touch
							</a>
							<a href="<?php print $this->APP_ROOT.'docs/dashboard';?>" target="_blank" class="breadcrumb-elements-item">
								<i class="icon-lifebuoy mr-2"></i>
								Docs
							</a>

							
						</div>
					</div> -->
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">
				<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>


				
				<!-- Dashboard content -->
				<div class="row">
					<div class="col-xl-8">

						<!-- Quick stats boxes -->
						<div class="row">
							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$this->LOGIN_USER->salary;?></h3>
											<!-- <span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span> -->
					                	</div>					                	
					                	<div>
											<strong>Basic Salary</strong>
											<div class="font-size-sm opacity-85">
												<?php if($total_allowance>0){ ?>
													Allowance: <?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_allowance;?>
												<?php } ?>
											</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->

							</div>
							<?php if($this->IS_TEACHER){?>
							<div class="col-lg-4">

								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print '';?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">Session <?php print $session->title;?></span>
					                	</div>					                	
					                	<div>
											<strong>Assigned Subjects</strong>
											<div class="font-size-sm opacity-85"><?php if(!empty($incharge_class)){?>Incharge: <?php print $incharge_class->title;?><?php } ?></div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->

							</div>

							<?php } ?>



						</div>
						<!-- /quick stats boxes -->
					</div>

					<div class="col-xl-4">

						<!-- Calendar widget -->
						<div class="card">
							<div class="form-control-datepicker border-0"></div>
						</div>
						<!-- /calendar widget -->
						<!-- My messages -->
						<div class="card">
							<div class="card-header header-elements-inline">
								<h6 class="card-title">Activity History</h6>
								<div class="header-elements">
									<span><i class="icon-calendar text-warning mr-2"></i> <?php print date('D  Md, Y h:i A');?></span>
								</div>
							</div>



							<!-- Area chart -->
							<div id="messages-stats"></div>
							<!-- /area chart -->


							<!-- Tabs -->
		                	<ul class="nav nav-tabs nav-tabs-solid nav-justified bg-indigo-400 border-x-0 border-bottom-0 border-top-indigo-300 mb-0">
								<li class="nav-item">
									<a href="#history" class="nav-link font-size-sm text-uppercase  active" data-toggle="tab">
										5 Most Recent Activities
									</a>
								</li>
							</ul>
							<!-- /tabs -->


							<!-- Tabs content -->
							<div class="tab-content card-body">
								<div class="tab-pane active fade show" id="history">
									<ul class="media-list">
										<?php foreach ($history as $row) {
											?>

										<li class="media">
											<div class="media-body">
												<div class="d-flex justify-content-between">
													<a href="#"><?php print $row['title'];?></a>
													<span class="font-size-sm text-muted"><?php print $row['date'] ?></span>
												</div>
												<?php print $row['description'];?>
											</div>
										</li>
										<?php } ?>


									</ul>
								</div>

							</div>
							<!-- /tabs content -->

						</div>
						<!-- /my messages -->

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




