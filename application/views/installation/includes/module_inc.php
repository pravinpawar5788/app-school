<?php 
$modules_enabled=FALSE;
$lms=$this->org_module_m->is_module_enabled($this->org_module_m->MODULE_LMS,$this->ORGID);
$exam=$this->org_module_m->is_module_enabled($this->org_module_m->MODULE_EXAM,$this->ORGID);
$staff_portal=$this->org_module_m->is_module_enabled($this->org_module_m->MODULE_STAFF_PORTAL,$this->ORGID);
$parent_portal=$this->org_module_m->is_module_enabled($this->org_module_m->MODULE_PARENT_PORTAL,$this->ORGID);
//if any of module enabled. enable the modules function
if($lms || $staff_portal || $parent_portal || $exam ){
$modules_enabled=TRUE;
}
if($modules_enabled){
?>


<div class="form-group text-center text-muted content-divider">
	<span class="px-2">or sign in to</span>
</div>

<div class="form-group text-center">

<a href="<?php print $this->APP_ROOT.'auth/login';?>" class="btn btn-outline bg-success border-success text-success btn-icon rounded-round border-2" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Administrator Login</span>" data-content="Click to login as administrator."><i class="icon-user-tie"></i></a>

<?php if($parent_portal){?>
<a href="<?php print $this->APP_ROOT.'auth/login/parents';?>" class="btn btn-outline bg-slate-600 border-slate-600 text-slate-600 btn-icon rounded-round border-2" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Parents Login</span>" data-content="CSMS provide parents the facility to track the academic activities of their children. Click to login into parent portal."><i class="icon-people"></i></a>
<?php }?>

<?php if($staff_portal){?>
<a href="<?php print $this->APP_ROOT.'auth/login/staff';?>" class="btn btn-outline bg-pink-300 border-pink-300 text-pink-300 btn-icon rounded-round border-2" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Staff Login</span>" data-content="CSMS provide staff's the facility to manage their tasks online. Staff may view their personal information. Click to login into staff portal."><i class="icon-user"></i></a>
<?php }?>


<?php if($lms){?>
<a href="<?php print $this->APP_ROOT.'auth/login/lms';?>" class="btn btn-outline bg-pink-300 border-pink-300 text-pink-300 btn-icon rounded-round border-2" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Staff Login</span>" data-content="CSMS provide staff's the facility to manage their tasks online. Staff may view their personal information. Click to login into staff portal."><i class="icon-user"></i></a>
<?php }?>

<?php if($exam){?>
<a href="<?php print $this->APP_ROOT.'auth/login/exam';?>" class="btn btn-outline bg-pink-300 border-pink-300 text-pink-300 btn-icon rounded-round border-2" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Staff Login</span>" data-content="CSMS provide staff's the facility to manage their tasks online. Staff may view their personal information. Click to login into staff portal."><i class="icon-user"></i></a>
<?php }?>
</div>

<?php }?>