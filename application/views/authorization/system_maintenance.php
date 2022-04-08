	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Login card -->
        		<form class="login-form" action="<?php print $this->APP_ROOT.'auth/signin';?>" method="post">
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-laptop icon-2x text-slate-800 border-slate-800 border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0"><?php print ucwords($this->config->item('app_name'));?></h5>
								<span class="d-block text-muted">System maintenance is in progress!</span>
							</div>

							<?php 
				            //include warning alerts
				            $this->load->view($this->LIB_VIEW_DIR.'includes/flash_inc');
				            ?>  

							<span class="form-text text-center text-danger">
								<?php print $this->SETTINGS[$this->system_setting_m->_MAINTENANCE_MESSAGE];?><br>
								Please contact administration for more information...</span> 

						</div>
					</div>
				</form>
				<!-- /login card -->

			</div>
			<!-- /content area -->
			<!-- Footer -->
			<?php 
		        //include warning alerts
		        $this->load->view($this->LIB_VIEW_DIR.'includes/footer_inc');
		     ?> 
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


