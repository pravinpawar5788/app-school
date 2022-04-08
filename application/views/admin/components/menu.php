<body <?php print $this->BODY_INIT;?> ng-app="mozzApp">

	<script type="text/javascript">
	$.blockUI({ 
			message: '<span class="font-weight-semibold" ><i class="icon-spinner4 spinner mr-2"></i>&nbsp; Please Wait...</span>',
			overlayCSS: {
				backgroundColor: '#000',
				// backgroundColor: '#1b2024',
				opacity: 0.9,
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				backgroundColor: 'transparent'
			}
		});  
	</script>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand">
			<a  href="<?php print $this->LIB_CONT_ROOT;?>" class="d-inline-block">
				<span class="brand-name"><?php print ucwords($this->config->item('app_name'));?><span class="brand-version"><?php print $this->config->item('app_version');?></span></span>
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>

			</ul>

			<span class="navbar-text ml-md-3 mr-md-auto">
				<span class="" style="font-size: 15px;"><?php print date('D d-M-Y'); ?> <span current-time="format"></span></span>
			</span>

			<ul class="navbar-nav">
				


				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
						<span><?php print ucwords($this->LOGIN_USER->name);?></span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php print $this->LIB_CONT_ROOT;?>profile" class="dropdown-item"><i class="icon-vcard"></i>Profile Settings</a>
					    <a href="<?php print $this->LIB_CONT_ROOT;?>maintenance" class="dropdown-item"><i class="icon-laptop"></i> System Maintenance</a>
						<a href="<?php print $this->APP_ROOT;?>auth/logout/admin" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->
