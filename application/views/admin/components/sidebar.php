<?php $active='class="active"'; $only_active='active'; $item_active=' nav-item-expanded nav-item-open ';?>
<!-- //////////////////////////////////////////////////////////////////////////// --> 

<!-- Page content -->
<div class="page-content">

<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

<!-- Sidebar mobile toggler -->
<div class="sidebar-mobile-toggler text-center">
<a href="#" class="sidebar-mobile-main-toggle"><i class="icon-arrow-left8"></i></a>
Navigation
<a href="#" class="sidebar-mobile-expand"><i class="icon-screen-full"></i><i class="icon-screen-normal"></i></a>
</div>
<!-- /sidebar mobile toggler -->


<!-- Sidebar content -->
<div class="sidebar-content">

<!-- User menu -->
<div class="sidebar-user">
<div class="card-body">
	<div class="media">
		<div class="mr-3">
			<a>
				<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" width="42" height="42" class="rounded-circle" alt="" style="background: #fff;">
			</a>
		</div>

		<div class="media-body">
			<div class="media-title font-weight-semibold"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></div>
			<div class="font-size-xs opacity-50">
				<i class="icon-mobile font-size-sm"></i> &nbsp;<?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_CONTACT_NUMBER]);?><br>
				<i class="icon-calendar font-size-sm"></i> &nbsp;<?php print ucwords($this->SETTINGS[$this->system_setting_m->_INSTALL_DATE]);?><br>
			</div>
		</div>

		<div class="ml-3 align-self-center">
			<a href="<?php print $this->APP_ROOT;?>auth/logout" class="text-white"><i class="icon-switch2"></i></a>
		</div>
	</div>
</div>
</div>
<!-- /user menu -->


<!-- Main navigation -->
<div class="card card-sidebar-mobile">
<ul class="nav nav-sidebar" data-nav-type="accordion">

<!-- Main -->
<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main Menu</div> <i class="icon-menu" title="Main"></i></li>


<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>" class="nav-link <?php print $menu=='dashboard'? $only_active : ''; ?>">
		<i class="icon-home4"></i><span>Dashboard</span>
	</a>
</li>

<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>campus" class="nav-link <?php print $menu=='campus'? $only_active : ''; ?>">
		<i class="icon-office"></i><span>Campuses</span>
	</a>
</li>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>setting" class="nav-link <?php print $menu=='setting'? $only_active : ''; ?>">
		<i class="icon-earth"></i><span>Global Configurations</span>
	</a>
</li>

<!-- Main -->
<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Support</div> <i class="icon-stats-bars2" title="Main"></i></li>

<li class="nav-item nav-item-submenu <?php print $menu=='report'? $item_active : ''; ?>">
	<a href="#" class="nav-link"><i class="icon-stats-bars2"></i> <span>Quick Analysis</span></a>

	<ul class="nav nav-group-sub" data-submenu-title="Reports">
		
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>report" class="nav-link <?php print $sub_menu=='report_progress'? $only_active : ''; ?>">
			<i class="icon-eye"></i> Registration Report</a>
		</li>	

	</ul>
</li>

<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>feedback" class="nav-link <?php print $menu=='feedback'? $only_active : ''; ?>">
		<i class="icon-envelope"></i><span>Feedback</span>
	</a>
</li>


<!-- /page kits -->

</ul>
</div>
<!-- /main navigation -->

</div>
<!-- /sidebar content -->

</div>
<!-- /main sidebar -->

















