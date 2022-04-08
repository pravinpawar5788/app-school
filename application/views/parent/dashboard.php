<?php
$bg=array('bg-violet','bg-success','bg-info','bg-warning','bg-primary','bg-blue','bg-teal','bg-green','bg-slate','bg-indigo');
$teals=array(2,3,4,6,7);
$total_bg=count($bg)-1;
$total_teals=count($teals)-1;
/////////////////////////////////////////////////////////////////////////////////////////////
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


				
				<!-- Dashboard content -->
				<div class="row">
					<div class="col-xl-8">
					<!-- Quick stats boxes -->					
					<div class="row">
						<?php 
							foreach($this->CHILDREN as $std){		
							$id=$std['mid'];
							$class=$this->class_m->get_by_primary($std['class_id'])->title;
							$session=$this->session_m->get_by_primary($std['session_id'])->title;
						 ?>
						<div class="col-sm-6">
							<div class="card card-body">
								<div class="media">
									<div class="mr-2 mt-3">
										<a href="<?php print $this->LIB_CONT_ROOT."student/profile/$id";?>">											
										<img src="<?php print $this->UPLOADS_ROOT;?>images/student/profile/<?php print $std['image'];?>" class="rounded-circle m-1" width="32" height="32" alt="">
										</a>
									</div>

									<div class="media-body">
										<h6 class="mb-0 font-weight-bold text-info"><?php print ucwords(strtolower($std['name'])) ?></h6>
										<span class="text-muted">Student ID:<strong class="m-3"><?php print $std['student_id'];?></strong></span><br>
										<span class="text-muted">Session:<strong class="m-3"><?php print $session;?></strong></span><br>
										<span class="text-muted">Class:<strong class="m-3"><?php print $class;?></strong></span><br>
										<span class="text-muted">Roll No:<strong class="m-3"><?php print $std['roll_no'];?></strong></span><br>
										<span class="text-muted">Fee:<strong class="m-3"><?php print $this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$std['fee'].' /'.$std['fee_type'];?></strong></span><br>
									</div>

								</div>
							</div>
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




