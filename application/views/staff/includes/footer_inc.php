<div class="navbar navbar-expand-lg navbar-light">
<div class="text-center d-lg-none w-100">
	<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
		<i class="icon-unfold mr-2"></i>
		Footer
	</button>
</div>

<div class="navbar-collapse collapse" id="navbar-footer">
	<span class="navbar-text">
		&copy; 2018-<?php print date('Y');?> <a href="#" target="_blank"><span class="text-success-400"><?php print $this->config->item('app_name');?></span></a>. 
		Powered by <a href="https://<?php print $this->config->item('app_author_site') ?>" target="_blank"><span class="text-success-400"><?php print $this->config->item('app_author'); ?></span></a>
	</span>



</div>
</div>