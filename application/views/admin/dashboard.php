<?php

$premium_modules=$this->module_m->get_modules_array();
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
$notifications=$this->note_m->get_rows(array('status'=>'active'));
$filter=array();

$total_campus=$this->campus_m->get_rows($filter,'',true);
$total_admins=$this->user_m->get_rows(array('type'=>$this->user_m->TYPE_MANAGER),'',true);
$total_students=$this->student_m->get_rows($filter,'',true);
$total_staff=$this->staff_m->get_rows($filter,'',true);
$total_awards=$this->award_m->get_rows($filter,'',true);
$total_punishments=$this->punishment_m->get_rows($filter,'',true);

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

				</div>

				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php print $this->LIB_CONT_ROOT;?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">
				<?php $this->load->view($this->LIB_VIEW_DIR.'includes/alert_inc');?>
				<?php if(count($notifications)>0){?>
					 <!-- Dashboard content -->
					<div class="row">
						<div class="col-md-12">
							<?php foreach ($notifications as $note){?>
							<!-- alert -->
							<div class="alert <?php print  'alert-'.$note['type'];?> alert-styled-left">
							    <strong>From <?php print ucwords($this->config->item('app_name'));?>! </strong><?php print htmlspecialchars_decode($note['detail']); ?>
							</div>
							 <!-- /alert -->
						    <?php }?>
						</div>
					</div>
				<?php }?>


				<!-- Dashboard content -->
				<div class="row">
					<div class="col-xl-12">
						<!-- Quick stats boxes -->
						<div class="row">
							<div class="col-lg-4">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_campus;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Total Campuses</strong>
											<div class="font-size-sm opacity-85">All registered campuses</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-lg-4">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_admins;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Total Administrators</strong>
											<div class="font-size-sm opacity-85">Total count of all campuses</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-lg-4">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_staff;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Total Staff</strong>
											<div class="font-size-sm opacity-85">Total count including left overs</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-lg-4">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_students;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Total Students</strong>
											<div class="font-size-sm opacity-85">Total count including alumni &amp; Left Overs</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-lg-4">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_awards;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Awards</strong>
											<div class="font-size-sm opacity-85">Registered awards for students &amp; staff</div>
										</div>
									</div>

									<div class="container-fluid">
										<div id="members-online"></div>
									</div>
								</div>
								<!-- /members online -->
							</div>
							<div class="col-lg-4">
								<!-- Members online -->
								<?php $n=mt_rand(1, $total_bg);$bg_color=$bg[$n];$t=mt_rand(1, $total_teals);$t_color='-'.$teals[$t].'00';?>
								<div class="card <?php print $bg_color.$t_color;?>">
									<div class="card-body">
										<div class="d-flex">
											<h3 class="font-weight-semibold mb-0"><?php print $total_punishments;?></h3>
											<span class="badge <?php print $bg_color.'-800';?> badge-pill align-self-center ml-auto">As of <?php print date('d-M-Y');?></span>
					                	</div>					                	
					                	<div>
											<strong>Notice's</strong>
											<div class="font-size-sm opacity-85">Registered Notice's for students &amp; staff</div>
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




