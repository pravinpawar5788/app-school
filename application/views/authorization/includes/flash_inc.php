<?php if($this->session->flashdata('error') !== null){ ?>    
<div class="alert alert-danger alert-styled-left alert-bordered">
<button type="button" class="close" data-dismiss="alert"><span class="sr-only">Close</span></button>
<span class="text-semibold"></span><?php print $this->session->flashdata('error');?>
</div>
<?php }?>
<?php if($this->session->flashdata('success') !== null){ ?>    
<div class="alert alert-success alert-styled-left alert-bordered">
<button type="button" class="close" data-dismiss="alert"><span class="sr-only">Close</span></button>
<span class="text-semibold"></span><?php print $this->session->flashdata('success');?>
</div>
<?php }?>