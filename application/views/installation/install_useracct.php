<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">

	<!-- Content area -->
	<div class="content d-flex justify-content-center align-items-center">

		<!-- Login card -->
		<form class="login-form2" action="<?php print $this->CONT_ROOT.'processadmin';?>" method="post">
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

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control" name="name" placeholder="Enter Admin Name">
								<div class="form-control-feedback"><i class="icon-user text-muted"></i></div>
							</div>
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="email" class="form-control" name="email" placeholder="Enter Admin Email Address">
								<div class="form-control-feedback"><i class="icon-envelope text-muted"></i></div>
							</div>


							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="text" class="form-control" name="username" placeholder="Enter Login Username">
								<div class="form-control-feedback"><i class="icon-vcard text-muted"></i></div>
							</div>
							
							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="password" class="form-control" name="password" placeholder="Enter Admin Password">
								<div class="form-control-feedback"><i class="icon-lock2 text-muted"></i></div>
							</div>
						</div>
						<!-- /circle empty -->

		            </div>



					<div class="form-group text-center">
						<button type="submit" class="btn btn-success font-weight-bold btn-process">
							<span class="btn-process-text"> Create Admin Account </span>
							<i class="icon-circle-right2 ml-2 icon-process"></i></button>
					</div>

       
					<span class="form-text text-center text-muted"><code>Note:- </code>This is main admin account. Later you can create more accounts.</span>
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

<script type="text/javascript">

$(document).ready(function() {
    "use strict"; 
    var button = $('.btn-process'),button_text = $('.btn-process-text'),icon = $('.icon-process');
    button.on("click", function() {
    	button_text.text('Please Wait ');
        if (icon.hasClass('icon-circle-right2')) {
            icon.removeClass('icon-circle-right2');
            icon.addClass('icon-spinner spinner');
        }
        icon.addClass('spinner');
    });
});

</script>