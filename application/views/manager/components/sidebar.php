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
				<i class="icon-mobile font-size-sm"></i> &nbsp;<?php print ucwords($this->CAMPUS->name);?><br>
				<i class="icon-calendar font-size-sm"></i> &nbsp;<?php print ucwords($this->CAMPUS->contact_number);?><br>
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

<?php if($this->LOGIN_USER->prm_stf_info>0){?>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>staff" class="nav-link <?php print $menu=='staff'? $only_active : ''; ?>">
		<i class="icon-user-tie"></i><span>Staff</span>
	</a>
</li>
<?php }?>


<?php if($this->LOGIN_USER->prm_std_info>0){?>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>student" class="nav-link <?php print $menu=='student'? $only_active : ''; ?>">
		<i class="icon-reading"></i><span>Students</span>
	</a>
</li>
<?php }?>


<?php if($this->LOGIN_USER->prm_class>0){?>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>classes" class="nav-link <?php print $menu=='classes'? $only_active : ''; ?>">
		<i class="icon-collaboration"></i><span>Classes</span>
	</a>
</li>
<?php }?>


<?php if($this->LOGIN_USER->prm_stf_info>1 || $this->LOGIN_USER->prm_std_info>1){?>

<li class="nav-item nav-item-submenu <?php print $menu=='attendance'? $item_active : ''; ?>">
	<a href="#" class="nav-link"><i class="icon-user-check"></i> <span>Attendance</span></a>

	<ul class="nav nav-group-sub" data-submenu-title="Attendance">
		<?php if($this->LOGIN_USER->prm_stf_info>0){?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>attendance/staff" class="nav-link <?php print $sub_menu=='attendance_staff'? $only_active :''; ?>">
			<i class="icon-user-tie"></i> Staff Attendance</a>
		</li>
		<?php }?>
		<?php if($this->LOGIN_USER->prm_std_info>0){?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>attendance/student" class="nav-link <?php print $sub_menu=='attendance_student'? $only_active :''; ?>">
			<i class="icon-user-tie"></i> Student Attendance</a>
		</li>
		<?php }?>
	</ul>
</li>
<?php }?>


<?php if($this->LOGIN_USER->prm_finance>0){?>
<li class="nav-item nav-item-submenu <?php print $menu=='finance'? $item_active : ''; ?>">
	<a href="#" class="nav-link"><i class="icon-wallet"></i> <span>Finance</span></a>

	<ul class="nav nav-group-sub" data-submenu-title="Finance">
		<?php if($this->LOGIN_USER->prm_std_info>0){?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>finance/instantfee" class="nav-link <?php print $sub_menu=='finance_instantfee'? $only_active : ''; ?>">
			<i class="icon-alarm"></i> Fee Collection</a>
		</li>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>finance/fee" class="nav-link <?php print $sub_menu=='finance_fee'? $only_active : ''; ?>">
			<i class="icon-coin-dollar"></i> Fee Record</a>
		</li>	
		<?php }?>
		<?php if($this->LOGIN_USER->prm_stf_info>0){?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>finance/payment" class="nav-link <?php print $sub_menu=='finance_pay'? $only_active : ''; ?>">
			<i class="icon-user-tie"></i> Payroll</a>
		</li>
		<?php }?>
		<?php if($this->LOGIN_USER->prm_finance>2){?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>finance/expense" class="nav-link <?php print $sub_menu=='finance_expenses'? $only_active : ''; ?>">
			<i class="icon-credit-card"></i>  Expenses</a>
		</li>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>finance/income" class="nav-link <?php print $sub_menu=='finance_income'? $only_active : ''; ?>">
			<i class="icon-cash4"></i> Revenue </a>
		</li>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>finance/accounts" class="nav-link <?php print $sub_menu=='finance_accounts'? $only_active : ''; ?>">
			<i class="icon-notebook"></i> Accounts</a>
		</li>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>finance/ledger" class="nav-link <?php print $sub_menu=='finance_ledger'? $only_active : ''; ?>">
			<i class="icon-book"></i> Journal</a>
		</li>
		<?php }?>
	</ul>
</li>
<?php }?>


<li class="nav-item nav-item-submenu <?php print $menu=='exam'? $item_active : ''; ?>">
	<a href="#" class="nav-link"><i class="icon-clipboard"></i> <span>Exam</span></a>

	<ul class="nav nav-group-sub" data-submenu-title="Exam">
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>exam/progress" class="nav-link <?php print $sub_menu=='exam_progress'? $only_active : ''; ?>">
			<i class="icon-stats-bars2"></i> Monthly Tests</a>
		</li>	
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>exam/terms" class="nav-link <?php print $sub_menu=='exam_terms'? $only_active : ''; ?>">
			<i class="icon-pie-chart6"></i>Manage Terms</a>
		</li>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>exam/results" class="nav-link <?php print $sub_menu=='exam_results'? $only_active : ''; ?>">
			<i class="icon-graph"></i>Final Results</a>
		</li>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>exam/resultsms" class="nav-link <?php print $sub_menu=='exam_resultsms'? $only_active : ''; ?>">
			<i class="icon-envelope"></i>  Send Result SMS</a>
		</li>
		<?php if($this->IS_COLLEGE){?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>exam/pastresult" class="nav-link <?php print $sub_menu=='exam_pastresult'? $only_active : ''; ?>">
			<i class="icon-history"></i> Update Past Result</a>
		</li>	
		<?php } ?>
	</ul>
</li>



<?php if($this->LOGIN_USER->prm_stationary>0){?>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>stationary" class="nav-link <?php print $menu=='stationary'? $only_active : ''; ?>">
		<i class="icon-book"></i><span>Stationary</span>
	</a>
</li>
<?php }?>


<?php if($this->LOGIN_USER->prm_library>0){?>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>library" class="nav-link <?php print $menu=='library'? $only_active : ''; ?>">
		<i class="icon-books"></i><span>Library</span>
	</a>
</li>
<?php }?>


<?php if($this->LOGIN_USER->prm_transport>0){?>
<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>transport" class="nav-link <?php print $menu=='transport'? $only_active : ''; ?>">
		<i class="icon-bus"></i><span>Transport</span>
	</a>
</li>
<?php }?>




<li class="nav-item nav-item-submenu <?php print $menu=='reports'? $item_active : ''; ?>">
	<a href="#" class="nav-link"><i class="icon-pie-chart5"></i> <span>Report &amp; History</span></a>

	<ul class="nav nav-group-sub" data-submenu-title="Reports">

		<?php if($this->LOGIN_USER->prm_finance>2){ ?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>reports/feecollection" class="nav-link <?php print $sub_menu=='report_feecollection'? $only_active : ''; ?>">
			<i class="icon-coin-dollar"></i> Fee Collection Analytics</a>
		</li> 
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>reports/payroll" class="nav-link <?php print $sub_menu=='report_payroll'? $only_active : ''; ?>">
			<i class="icon-wallet"></i> Payroll Analytics</a>
		</li> 
		<?php } ?>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>reports/attendance" class="nav-link <?php print $sub_menu=='report_attendance'? $only_active : ''; ?>">
			<i class="icon-user-check"></i> Attendance Reports</a>
		</li> 
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>reports/earning" class="nav-link <?php print $sub_menu=='report_earning'? $only_active : ''; ?>">
			<i class="icon-credit-card"></i>  Earning Report</a>
		</li> 
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>reports/sms" class="nav-link <?php print $sub_menu=='reports_sms'? $only_active : ''; ?>">
			<i class="icon-envelope"></i>  SMS History</a>
		</li>
	</ul>
</li>

<?php if($this->module_m->is_enabled($this->module_m->MODULE_PARENT_PORTAL)){?>
	<?php if($this->LOGIN_USER->prm_parents>0){?>

	<li class="nav-item nav-item-submenu <?php print $menu=='parents'? $item_active : ''; ?>">
		<a href="#" class="nav-link"><i class="icon-people"></i> <span>Parents</span></a>

		<ul class="nav nav-group-sub" data-submenu-title="Parents">
			<li class="nav-item">
				<a href="<?php print $this->LIB_CONT_ROOT;?>parents" class="nav-link <?php print $sub_menu=='parents'? $only_active :''; ?>">
				<i class="icon-user-tie"></i> Parent Accounts</a>
			</li>
			<li class="nav-item">
				<a href="<?php print $this->LIB_CONT_ROOT;?>parents/feedback" class="nav-link <?php print $sub_menu=='parents_feedback'? $only_active :''; ?>">
				<i class="icon-bubbles2"></i> Parents Feedback</a>
			</li>
		</ul>
	</li>
	<?php }?>
<?php } ?>


<?php if($this->LOGIN_USER->type==$this->user_m->TYPE_MANAGER || $this->LOGIN_USER->type==$this->user_m->TYPE_ADMIN){?>

<li class="nav-item">
	<a href="<?php print $this->LIB_CONT_ROOT;?>office" class="nav-link <?php print $menu=='office'? $only_active : ''; ?>">
		<i class="icon-user-tie"></i><span>Assistant Managers</span>
	</a>
</li>
<?php } ?>



<?php if($this->LOGIN_USER->type==$this->user_m->TYPE_MANAGER || $this->LOGIN_USER->type==$this->user_m->TYPE_ADMIN){?>
<li class="nav-item nav-item-submenu <?php print $menu=='settings'? $item_active : ''; ?>">
	<a href="#" class="nav-link"><i class="icon-cogs"></i> <span>System Settings</span></a>

	<ul class="nav nav-group-sub" data-submenu-title="System Settings">
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>settings/campus" class="nav-link <?php print $sub_menu=='settings_campus'? $only_active : ''; ?>">
			<i class="icon-gear"></i> Campus Settings</a>
		</li>	
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>settings/smshooks" class="nav-link <?php print $sub_menu=='settings_smshooks'? $only_active : ''; ?>">
			<i class="icon-alarm"></i>  SMS Notifications</a>
		</li>
		<li class="nav-item">
			<a href="<?php print $this->LIB_CONT_ROOT;?>settings/promotion" class="nav-link <?php print $sub_menu=='settings_promotion'? $only_active : ''; ?>">
			<i class="icon-stairs-up"></i>  Session Change</a>
		</li>
	</ul>
</li>
<?php }?>


<!-- /page kits -->

</ul>
</div>
<!-- /main navigation -->

</div>
<!-- /sidebar content -->

</div>
<!-- /main sidebar -->

















