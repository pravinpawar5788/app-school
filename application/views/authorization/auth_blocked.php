	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Login card -->
        		<form class="login-form" action="<?php print $this->APP_ROOT.'auth/licrenew';?>" method="post">
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-laptop icon-2x text-slate-800 border-slate-800 border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0"><?php print ucwords($this->config->item('app_name'));?></h5>
								<span class="d-block text-muted">Access Restricted!</span>
							</div>

							<?php 
				            //include warning alerts
				            $this->load->view($this->LIB_VIEW_DIR.'includes/flash_inc');
				            ?>  

				            <?php if(empty($this->SETTINGS[$this->system_setting_m->_EXPIRE_NOTE]) ){?>
								<span class="form-text text-center text-danger">Your access to login in the system has been restricted due license expiry.<br> Please contact <a href="">support team </a>to  renew your license.</span> 
				            <?php }else{?>
								<span class="form-text text-center text-danger"><?php print $this->SETTINGS[$this->system_setting_m->_EXPIRE_NOTE]; ?>.</span> 
				            <?php }?>

								<span class="form-text text-center">If you have purchased new license, please enter below to activate it.</span>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control" name="lic" placeholder="License Key">
								<div class="form-control-feedback"><i class="icon-key text-muted"></i></div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success btn-block">Activate License <i class="icon-circle-right2 ml-2"></i></button>
							</div>

                       


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

