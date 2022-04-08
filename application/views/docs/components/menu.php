<body data-spy="scroll" data-target=".sidebar-component-right">

<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-light">
		<div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
			<div class="navbar-brand navbar-brand-md">
				<a  href="<?php print $this->APP_ROOT.'docs';?>" class="d-inline-block">
				<span class="brand-name"><?php print ucwords($this->config->item('app_name'));?><span class="brand-version"><?php print $this->config->item('app_version');?></span></span>
				</a>
			</div>
	
			<div class="navbar-brand navbar-brand-xs">
				<a  href="<?php print $this->APP_ROOT.'docs';?>" class="d-inline-block">
				<span class="brand-name"><?php print ucwords($this->config->item('app_name'));?><span class="brand-version"><?php print $this->config->item('app_version');?></span></span>
				</a>
			</div>
		</div>

		<div class="d-flex flex-1 d-md-none">
			<div class="navbar-brand mr-auto">
				<a  href="<?php print $this->APP_ROOT.'docs';?>" class="d-inline-block">
				<span class="brand-name"><?php print ucwords($this->config->item('app_name'));?><span class="brand-version"><?php print $this->config->item('app_version');?></span></span>
				</a>
			</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-component-toggle" type="button">
				<i class="icon-unfold"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-hide d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>

				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-component-toggle d-none d-md-block">
						<i class="icon-transmission"></i>
					</a>
				</li>
			</ul>

			<ul class="navbar-nav ml-md-auto">
				<li class="nav-item dropdown">
					<a href="<?php print $this->LIB_CONT_ROOT.'changelog'?>" class="navbar-nav-link">
						<i class="icon-history mr-2"></i>
						Change log
						<span class="badge bg-warning-400 badge-pill position-static ml-md-2"><?php print $this->config->item('app_version');?></span>
					</a>					
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->
