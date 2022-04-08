<?php 
$modules_enabled=FALSE;
$exam=$this->module_m->is_enabled($this->module_m->MODULE_EXAM);
$staff_portal=$this->module_m->is_enabled($this->module_m->MODULE_STAFF_PORTAL);
$parent_portal=$this->module_m->is_enabled($this->module_m->MODULE_PARENT_PORTAL);
//if any of module enabled. enable the modules function
if( $staff_portal || $parent_portal || $exam ){
$modules_enabled=TRUE;
}


?>


<div class="form-group text-center text-muted content-divider">
	<span class="px-2">or sign in to</span>
</div>

<div class="form-group text-center">

<a href="<?php print $this->APP_ROOT.'auth/login';?>" class="m-1 m-1 btn bg-success-400 btn-labeled btn-labeled-left rounded-round" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Campus Manager Login</span>" data-content="Click to login into campus manager account."><b><i class="icon-user"></i></b><strong>Manager Login</strong></a>

<?php 

if($modules_enabled){

if($parent_portal){?>
<a href="<?php print $this->APP_ROOT.'auth/login/parents';?>" class="m-1 m-1 btn bg-slate-400 btn-labeled btn-labeled-left rounded-round" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Parents Login</span>" data-content="Click to login into parent portal. Parents can track the progress and view activity detail of their children."><b><i class="icon-man-woman"></i></b><strong>Parent Login</strong></a>
<?php }?>

<?php if($staff_portal){?>
<a href="<?php print $this->APP_ROOT.'auth/login/staff';?>" class="m-1 m-1 m-1 btn bg-info-400 btn-labeled btn-labeled-left rounded-round" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Staff Login</span>" data-content="Click to login into staff portal. Staff members can manage their activities in staff portal."><b><i class="icon-users"></i></b><strong>Staff Login</strong></a>
<?php }?>


<?php if($exam){?>
<a href="<?php print $this->APP_ROOT.'auth/login/exam';?>" class="m-1 m-1 btn bg-pink-400 btn-labeled btn-labeled-left rounded-round" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Staff Login</span>" data-content="Click to login into exam system. Students can take online exam in this module."><b><i class="icon-terminal"></i></b><strong>Exam Login</strong></a>
<?php }?>




<?php }?>

<a href="<?php print $this->APP_ROOT.'auth/login/admin';?>" class="m-1 btn bg-danger-400 btn-labeled btn-labeled-left rounded-round" data-html="true" data-popup="popover" data-trigger="hover"  data-placement="top" title="<span class='text-success'>Administrator Login</span>" data-content="Click to login into administrator account."><b><i class="icon-laptop"></i></b> <strong>Admin Login</strong></a>

</div>