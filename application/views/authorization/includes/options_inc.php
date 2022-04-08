<?php 
$active=' active ';
$modules_enabled=FALSE;
$exam=$this->module_m->is_enabled($this->module_m->MODULE_EXAM);
$staff_portal=$this->module_m->is_enabled($this->module_m->MODULE_STAFF_PORTAL);
$parent_portal=$this->module_m->is_enabled($this->module_m->MODULE_PARENT_PORTAL);
//if any of module enabled. enable the modules function
if( $staff_portal || $parent_portal || $exam ){
$modules_enabled=TRUE;
}


?>


<ul class="nav nav-tabs nav-tabs-vertical flex-column mr-md-3 wmin-md-200 mb-md-0 border-bottom-0">
	<li class="nav-item">
		<a href="#login-manager" data-toggle="tab" class="nav-link <?php print $role=='manager' || empty($role) ? $active:'';?>" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="left" title="<span class='text-success'>Campus Manager Login</span>" data-content="Click to login into campus manager account."><b><i class="icon-user mr-2"></i></b><strong>Campus Manager Login</strong></a>
	</li>

	<?php
	if($modules_enabled){
		if($parent_portal){?>
		<li class="nav-item">
			<a href="#login-parent" data-toggle="tab" class="nav-link <?php print $role=='parent'? $active:'';?>" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="left" title="<span class='text-success'>Parents Login</span>" data-content="Click to login into parent portal. Parents can track the progress and view activity detail of their children."><b><i class="icon-man-woman mr-2"></i></b><strong>Parents Login</strong></a>
		</li>
		<?php }
		if($staff_portal){?>
		<li class="nav-item">
			<a href="#login-staff" data-toggle="tab" class="nav-link <?php print $role=='staff'? $active:'';?>" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="left" title="<span class='text-success'>Staff Login</span>" data-content="Click to login into staff portal. Staff members can manage their activities in staff portal."><b><i class="icon-users mr-2"></i></b><strong>Staff Login</strong></a>
		</li>
		<?php }?>
	<?php }?>

	<li class="nav-item">
		<a href="#login-admin" data-toggle="tab" class="nav-link <?php print $role=='admin'? $active:'';?>" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="left" title="<span class='text-success'>Administrator Login</span>" data-content="Click to login into administrator account."><b><i class="icon-laptop mr-2"></i></b><strong>Administrator Login</strong></a>
	</li>


</ul>
	
