<div class="row text-center">
	<div class="col-sm-12">
		<span class="page-header-image plr-15">
			<img src="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" class="rounded-circle" width="48" height="48" alt=" ">
		</span>	
		<span class="font-weight-bold font-1-9em">
			<?php print strtoupper($this->SETTINGS[$this->system_setting_m->_ORG_NAME]);?>
		</span>			
		<br><span class="font-1-2em"><?php print !empty($print_page_title)? $print_page_title : '';?></span>
	</div>
</div>	