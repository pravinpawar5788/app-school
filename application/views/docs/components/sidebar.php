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
<!-- Support -->
<div class="card card-body">
	<a class="btn bg-success-400 btn-block"><i class="icon-lifebuoy mr-2"></i> <?php print ucwords($this->config->item('app_name'));?> Support Center</a>
</div>
<!-- /support -->
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
		
	</div>
</div>
</div>
<!-- /user menu -->


<!-- Main navigation -->
<div class="card card-sidebar-mobile">
<ul class="nav nav-sidebar" data-nav-type="accordion">

<!-- Main -->
<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>


<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>" class="nav-link <?php print $menu=='intro'? $only_active : ''; ?>"><span>Introduction</span>	</a>
</li>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>dashboard" class="nav-link <?php print $menu=='dashboard'? $only_active : ''; ?>"><span>Dashboard</span>	</a>
</li>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>printing" class="nav-link <?php print $menu=='printing'? $only_active : ''; ?>"><span>Printing</span>	</a>
</li>



<!-- /page kits -->

</ul>
</div>
<!-- /main navigation -->

</div>
<!-- /sidebar content -->

</div>
<!-- /main sidebar -->

















