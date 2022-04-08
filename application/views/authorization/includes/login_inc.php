<?php 
$active=' show active ';
$modules_enabled=FALSE;
$exam=$this->module_m->is_enabled($this->module_m->MODULE_EXAM);
$staff_portal=$this->module_m->is_enabled($this->module_m->MODULE_STAFF_PORTAL);
$parent_portal=$this->module_m->is_enabled($this->module_m->MODULE_PARENT_PORTAL);
//if any of module enabled. enable the modules function
if( $staff_portal || $parent_portal || $exam ){
$modules_enabled=TRUE;
}


?>




<div class="tab-pane fade <?php print $role=='manager' || empty($role) ? $active:'';?>" id="login-manager">
	<div class="ml-5 mt-2">
		<span class="text-muted">Login into system as a <code>campus manager </code>.</span>
		<hr>
		<form action="<?php print $this->APP_ROOT.'auth/signin';?>" method="post">
			<div class="form-group form-group-feedback form-group-feedback-left">
				<input type="text" class="form-control" name="user_id" placeholder="User ID">
				<div class="form-control-feedback"><i class="icon-user text-muted"></i></div>
			</div>

			<div class="form-group form-group-feedback form-group-feedback-left">
				<input type="password" class="form-control" name="password" placeholder="Password">
				<div class="form-control-feedback"><i class="icon-lock2 text-muted"></i></div>
			</div>
			<div class="form-group">
				<?php if($this->LOGIN_FLAG==TRUE && $this->IS_MANAGER){
					$db='manager';
				?>
				<a href="<?php print $this->APP_ROOT.$db;?>" class="btn btn-success btn-block">Go To Dashboard <i class="icon-circle-right2 ml-2"></i></a>
				<?php }else{?>
				<button type="submit" class="btn btn-success btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
				<?php }?>
			</div>
		</form>		
	</div>
</div>

<?php
if($modules_enabled){
	if($parent_portal){?>
	<div class="tab-pane fade <?php print $role=='parent' ? $active:'';?>" id="login-parent">
		<div class="ml-5 mt-2">
			<span class="text-muted">Login into <code>parent portal </code>.</span>
			<hr>
			<form action="<?php print $this->APP_ROOT.'auth/signin_parent';?>" method="post">
				<div class="form-group form-group-feedback form-group-feedback-left">
					<input type="text" class="form-control" name="user_id" placeholder="Parent ID">
					<div class="form-control-feedback"><i class="icon-user text-muted"></i></div>
				</div>

				<div class="form-group form-group-feedback form-group-feedback-left">
					<input type="password" class="form-control" name="password" placeholder="Password">
					<div class="form-control-feedback"><i class="icon-lock2 text-muted"></i></div>
				</div>
				<div class="form-group">
					<?php if($this->LOGIN_FLAG==TRUE){
						$db='parent';
					?>
					<a href="<?php print $this->APP_ROOT.$db;?>" class="btn btn-success btn-block">Go To Dashboard <i class="icon-circle-right2 ml-2"></i></a>
					<?php }else{?>
					<button type="submit" class="btn btn-success btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
					<?php }?>
				</div>
			</form>		
		</div>
	</div>
	<?php }
	if($staff_portal){?>
	<div class="tab-pane fade <?php print $role=='staff' ? $active:'';?>" id="login-staff">
		<div class="ml-5 mt-2">
			<span class="text-muted">Login into <code>staff portal </code>.</span>
			<hr>
			<form action="<?php print $this->APP_ROOT.'auth/signin_staff';?>" method="post">
				<div class="form-group form-group-feedback form-group-feedback-left">
					<input type="text" class="form-control" name="user_id" placeholder="Staff ID">
					<div class="form-control-feedback"><i class="icon-user text-muted"></i></div>
				</div>

				<div class="form-group form-group-feedback form-group-feedback-left">
					<input type="password" class="form-control" name="password" placeholder="Password">
					<div class="form-control-feedback"><i class="icon-lock2 text-muted"></i></div>
				</div>
				<div class="form-group">
					<?php if($this->LOGIN_FLAG==TRUE){
						$db='staff';
					?>
					<a href="<?php print $this->APP_ROOT.$db;?>" class="btn btn-success btn-block">Go To Dashboard <i class="icon-circle-right2 ml-2"></i></a>
					<?php }else{?>
					<button type="submit" class="btn btn-success btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
					<?php }?>
				</div>
			</form>		
		</div>
	</div>
	<?php }
}?>

<div class="tab-pane fade <?php print $role=='admin' ? $active:'';?>" id="login-admin">
	<div class="ml-5 mt-2">
		<span class="text-muted">Login into system as an <code>administrator </code>.</span>
		<hr>
		<form action="<?php print $this->APP_ROOT.'auth/signin_admin';?>" method="post">
			<div class="form-group form-group-feedback form-group-feedback-left">
				<input type="text" class="form-control" name="user_id" placeholder="Admin ID">
				<div class="form-control-feedback"><i class="icon-user text-muted"></i></div>
			</div>

			<div class="form-group form-group-feedback form-group-feedback-left">
				<input type="password" class="form-control" name="password" placeholder="Password">
				<div class="form-control-feedback"><i class="icon-lock2 text-muted"></i></div>
			</div>
			<div class="form-group">
				<?php if($this->LOGIN_FLAG==TRUE && $this->IS_ADMIN){
					$db='admin';
				?>
				<a href="<?php print $this->APP_ROOT.$db;?>" class="btn btn-success btn-block">Go To Dashboard <i class="icon-circle-right2 ml-2"></i></a>
				<?php }else{?>
				<button type="submit" class="btn btn-success btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
				<?php }?>
			</div>
		</form>		
	</div>
</div>