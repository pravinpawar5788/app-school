	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">



				<!-- Login card -->
				<div class="card mb-0 col-md-6">
					<div class="card-body">
						<div class="text-center mb-3">
							<i class="icon-laptop icon-2x text-slate-800 border-slate-800 border-3 rounded-round p-3 mb-3 mt-1"></i>
							<h5 class="mb-0"><?php print ucwords($this->config->item('app_name'));?></h5>
						</div>


						<div class="form-group text-center text-muted content-divider">
							<span class="px-2">Click on module you want to login</span>
						</div>
						<?php 
			            //include warning alerts
			            $this->load->view($this->LIB_VIEW_DIR.'includes/flash_inc');
			            ?>  

						<div class="d-md-flex">
							<?php 
				            //include tabs menu
				            $this->load->view($this->LIB_VIEW_DIR.'includes/options_inc');
				            ?>  

							<div class="tab-content">
								<?php 
					            //include tabs content
					            $this->load->view($this->LIB_VIEW_DIR.'includes/login_inc');
					            ?> 
							</div>
						</div>

						<span class="form-text text-center text-muted">Trouble signing in? Contact system administrator to resolve this issue.</span>
						<span class="form-text text-center text-muted">Forget Password ? <a href="<?php print $this->CONT_ROOT.'forget';?>"> Reset Password</a>.</span>
					</div>
				</div>
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
