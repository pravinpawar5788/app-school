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
			<a href="#">
				<?php if(!empty($this->CAMPUSSETTINGS[$this->campus_setting_m->_CAMPUS_LOGO])){ ?>
				<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->CAMPUSSETTINGS[$this->campus_setting_m->_CAMPUS_LOGO];?>" width="42" height="42" class="rounded-circle" alt="" style="background: #fff;">
				<?php }else{  ?>
				<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" width="42" height="42" class="rounded-circle" alt="" style="background: #fff;">
				<?php } ?>
			</a>
		</div>

		<div class="media-body">
			<div class="media-title font-weight-semibold"><?php print ucwords($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?></div>
			<div class="font-size-xs opacity-50">
				<i class="icon-home font-size-sm"></i> &nbsp;<?php print ucwords($this->CAMPUS->name);?><br>
				<i class="icon-pin font-size-sm"></i> &nbsp;<?php print ucwords($this->CAMPUS->address);?><br>
			</div>
		</div>

		<div class="ml-3 align-self-center">
			<a href="<?php print $this->APP_ROOT;?>auth/logout/parents" class="text-white"><i class="icon-switch2"></i></a>
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

<?php 
foreach($this->CHILDREN as $std){		
		$id=$std['mid'];
?>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT."student/profile/$id";?>" class="nav-link <?php print $menu=="student-$id"? $only_active : ''; ?>">
		<i class="icon-reading"></i><span><?php print strtolower(ucwords($std['name'])); ?></span>
	</a>
</li>
<?php }?>


<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>feedback" class="nav-link <?php print $menu=='feedback'? $only_active : ''; ?>">
		<i class="icon-bubbles2"></i><span>Feedback</span>
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

















