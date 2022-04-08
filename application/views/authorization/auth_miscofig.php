<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php print ucwords($this->config->item('app_name'));?> | 404 Error</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/components.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="<?php print $this->RES_ROOT;?>js/main/jquery.min.js"></script>
	<script src="<?php print $this->RES_ROOT;?>js/main/bootstrap.bundle.min.js"></script>
	<script src="<?php print $this->RES_ROOT;?>js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="<?php print $this->RES_ROOT;?>js/plugins/forms/styling/uniform.min.js"></script>

	<script src="<?php print $this->RES_ROOT;?>js/app.js"></script>
	<script src="<?php print $this->RES_ROOT;?>js/pages/login.js"></script>
	<!-- /theme JS files -->

</head>

<body class="bg-slate-800">

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Login card -->
        		<form class="login-form2">
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-warning icon-2x text-danger border-danger border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">404 Error</h5>
								<span class="d-block text-muted">The page you trying to access does not exist.</span>
							</div>

							<?php 
				            //include warning alerts
				            $this->load->view('authorization/includes/flash_inc');
				            ?>  
							<span class="form-text text-center text-danger">Please contact support team for more informatino.</span>
						</div>
					</div>
				</form>
				<!-- /login card -->

			</div>
			<!-- /content area -->
			<!-- Footer -->
			<div class="footer text-white text-center" style="margin-bottom: 20px;">
			All rights reserved - <a class="text-success" href="#"> <?php print ucwords($this->config->item('app_name'));?></a> | 
			Powered by <a class="text-success" href="https://<?php print ucwords($this->config->item('app_author_site'));?>" target="_blank"> <?php print ucwords($this->config->item('app_author'));?></a>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


</body>
</html>
