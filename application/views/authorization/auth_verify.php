	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">



				<!-- Login card -->
				<div class="card mb-0 login-form">
					<div class="card-body">
						<div class="text-center mb-3">
							<i class="icon-laptop icon-2x text-slate-800 border-slate-800 border-3 rounded-round p-3 mb-3 mt-1"></i>
							<h5 class="mb-0"><?php print ucwords($this->config->item('app_name'));?></h5>
						</div>


						<div class="form-group text-center text-muted content-divider">
							<span class="px-2">Enter new password for your account</span>
						</div>
						<?php 
			            //include warning alerts
			            $this->load->view($this->LIB_VIEW_DIR.'includes/flash_inc');
			            ?>  

						<form action="<?php print $this->APP_ROOT.'auth/updatepassword';?>" method="post">
							<input type="hidden" name="code" value="<?= $code;?>">

				            <div class="form-group form-group-feedback form-group-feedback-left">
								<input type="email" class="form-control" name="email" placeholder="Email ID" value="<?= decode64($email);?>" readonly="readonly">
								<div class="form-control-feedback"><i class="icon-user text-muted"></i></div>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="password" class="form-control" name="password" placeholder="Enter New Password">
								<div class="form-control-feedback"><i class="icon-lock2 text-muted"></i></div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-success btn-block">Update Password<i class="icon-circle-right2 ml-2"></i></button>
							</div>
		
						</form>

						<span class="form-text text-center text-muted">Password reset is available only for Administrator &amp; campus/ branch managers. Staff &amp; Parents needs to get in touch with campus manager for password reset.</span>
						<span class="form-text text-center text-muted">Remember Password ? <a href="<?php print $this->CONT_ROOT.'login';?>"> Login Here</a>.</span>
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
