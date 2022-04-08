<?php 

$error = false;
if (phpversion() < "5.6") {
    $error = true;
    $requirement1 = "<span class='badge badge-flat border-warning text-warning'>Your PHP version is " . phpversion() . "</span>";
} else {
    $requirement1 = "<span class='badge badge-flat border-success text-success'>v." . phpversion() . "</span>";
}
if (!is_really_writable(FCPATH . 'uploads/temp')) {
    $error = true;
    $requirement2 = "<span class='badge badge-flat border-warning text-warning'>No (Make temp folder writable) - Permissions 755</span>";
} else {
    $requirement2 = "<span class='badge badge-flat border-success text-success'>Ok</span>";
}
if (!extension_loaded('mysqli')) {
    $error = true;
    $requirement3 = "<span class='badge badge-flat border-warning text-warning'>Not enabled</span>";
} else {
    $requirement3 = "<span class='badge badge-flat border-success text-success'>Enabled</span>";
}
if (!is_really_writable(APPPATH . 'config/database.php')) {
    $error = true;
    $requirement4 = "<span class='badge badge-flat border-warning text-warning'>No (Make application/config/database.php writable) - Permissions - 755</span>";
} else {
    $requirement4 = "<span class='badge badge-flat border-success text-success'>Ok</span>";
}

if (!extension_loaded('mbstring')) {
    $error = true;
    $requirement5 = "<span class='badge badge-flat border-warning text-warning'>Not enabled</span>";
} else {
    $requirement5 = "<span class='badge badge-flat border-success text-success'>Enabled</span>";
}
if (!extension_loaded('gd')) {
    $error = true;
    $requirement6 = "<span class='badge badge-flat border-warning text-warning'>Not enabled</span>";
} else {
    $requirement6 = "<span class='badge badge-flat border-success text-success'>Enabled</span>";
}
if (!is_really_writable($config_path)) {
    $error = true;
    $requirement7 = "<span class='badge badge-flat border-warning text-warning'>No (Make application/config/config.php writable) - Permissions 755</span>";
} else {
    $requirement7 = "<span class='badge badge-flat border-success text-success'>Ok</span>";
}

if (!extension_loaded('curl')) {
    $error = true;
    $requirement8 = "<span class='label label-warning'>Not enabled</span>";
} else {
    $requirement8 = "<span class='badge badge-flat border-success text-success'>Enabled</span>";
}
if (ini_get('allow_url_fopen') != "1") {
    $error = true;
    $requirement9 = "<span class='badge badge-flat border-warning text-warning'>Allow_url_fopen is not enabled!</span>";
} else {
    $requirement9 = "<span class='badge badge-flat border-success text-success'>Enabled</span>";
}
if (!extension_loaded('zip')) {
    $error = true;
    $requirement10 = "<span class='badge badge-flat border-warning text-warning'>Zip Extension is not enabled</span>";
} else {
    $requirement10 = "<span class='badge badge-flat border-success text-success'>Enabled</span>";
}
?>

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">

	<!-- Content area -->
	<div class="content d-flex justify-content-center align-items-center">

		<!-- Login card -->
		<form class="login-form2" action="<?php print $this->CONT_ROOT.'start';?>" method="post">
			<div class="card mb-0">
				<div class="card-body">
					<div class="text-center mb-3">
						<i class="icon-laptop icon-2x text-slate-800 border-slate-800 border-3 rounded-round p-3 mb-3 mt-1"></i>
						<h5 class="mb-0"><?php print ucwords($this->config->item('app_name'));?></h5>
						<span class="d-block text-muted">Version <?php print $this->config->item('app_version');?> developed by <a href="https://<?php print $this->config->item('app_author_site');?>" target="_blank"><?php print $this->config->item('app_author');?></a></span>				
						<span class="d-block text-muted"><?php print $this->config->item('app_description');?></span>						
					</div>

					<?php 
		            //include warning alerts
		            $this->load->view($this->LIB_VIEW_DIR.'includes/flash_inc');
		            ?>  


		            <div class="row">
		            	<!-- Circle empty -->
						<div class="card card-body border-top-warning">
							<div class="list-feed">
								<div class="list-feed-item">
									Welcome to <?php print ucwords($this->config->item('app_name'));?> installation module.
								</div>

								<div class="list-feed-item">
									Please make sure to fill the required data on every step during installation.
								</div>

								<div class="list-feed-item">
									Remember! <strong>Envato/License Code</strong> is necessary to complete the installation process.
								</div>
								<div class="list-feed-item">
									You can get the license code from <a href="#">Envato Market</a> after buying the product.
								</div>

							</div>
						</div>
						<!-- /circle empty -->

		            </div>

		            <div class="row">
						<div class="card card-body border-top-info">		            	
						<h3 class="text-center">Server Requirements</h3>

						<?php
						if ($error == true) {
						    echo '<div class="text-center alert alert-danger">Please fullfill the requirements to begin installation.</div>';
						}?>
						<table class="table table-hover">
						    <thead>
						        <tr>
						            <th><b>Requirements</b></th>
						            <th><b>Result</b></th>
						        </tr>
						    </thead>
						    <tbody>
						        <tr>
						            <td>PHP 5.6+ </td>
						            <td><?php echo $requirement1; ?></td>
						        </tr>
						        <tr>
						            <td>MySQLi PHP Extension</td>
						            <td><?php echo $requirement3; ?></td>
						        </tr>
						        <tr>
						            <td>GD PHP Extension</td>
						            <td><?php echo $requirement6; ?></td>
						        </tr>
						        <tr>
						            <td>CURL PHP Extension</td>
						            <td><?php echo $requirement8; ?></td>
						        </tr>
						        <tr>
						            <td>MBString PHP Extension</td>
						            <td><?php echo $requirement5; ?></td>
						        </tr>
						        <tr>
						            <td>Allow allow_url_fopen</td>
						            <td><?php echo $requirement9; ?></td>
						        </tr>
						        <tr>
						            <td>Zip Extension</td>
						            <td><?php echo $requirement10; ?></td>
						        </tr>
						        <tr>
						            <td>config.php Writable</td>
						            <td><?php echo $requirement7; ?></td>
						        </tr>
						        <tr>
						            <td>database.php Writable</td>
						            <td><?php echo $requirement4; ?></td>
						        </tr>
						        <tr>
						            <td>/temp folder Writable</td>
						            <td><?php echo $requirement2; ?></td>
						        </tr>
						    </tbody>
						</table>
						<hr />
						</div>
		            </div>		


					<?php if ($error == false) { ?>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success font-weight-bold">
							Start Installation Process <i class="icon-circle-right2 ml-2"></i></button>
					</div>
					<?php } ?>
        

					<span class="form-text text-center text-muted">Trouble in installation? Please <a href="https://<?php print $this->config->item('app_author_site');?>" target="_blank">contact us</a> for installation support.</span>
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
