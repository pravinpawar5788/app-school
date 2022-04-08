	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">



				<!-- Login card -->
				<div class="card mb-0 login-form2">
					<div class="card-body">
						<div class="text-center mb-3">
							<i class="icon-laptop icon-2x text-slate-800 border-slate-800 border-3 rounded-round p-3 mb-3 mt-1"></i>
							<h5 class="mb-0"><?php print ucwords($this->config->item('app_name'));?></h5>
						</div>


						<div class="form-group text-center text-muted content-divider">
							<span class="px-2">Enter license key to activate software</span>
						</div>
						<?php 
			            //include warning alerts
			            $this->load->view($this->LIB_VIEW_DIR.'includes/flash_inc');
			            ?>  

						<form action="<?php print $this->APP_ROOT.'auth/activatelicense';?>" method="post">
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control" name="licensekey" placeholder="Enter License Key">
								<div class="form-control-feedback"><i class="icon-barcode2 text-muted"></i></div>
							</div>			

							<div class="form-group">
								<button type="submit" class="btn btn-success btn-block">Activate License <i class="icon-circle-right2 ml-2"></i></button>
							</div>
						</form>

						<span class="form-text text-center text-muted">Feeling trouble activating the software? Please contact our <a href="mailto:<?php print $this->config->item('app_author_email') ?>">support team</a> to help you in this regard.</span>
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
