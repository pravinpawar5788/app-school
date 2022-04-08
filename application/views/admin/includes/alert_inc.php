<?php				
if($this->session->flashdata('success')){
?>	
<!-- alert -->
<div class="alert alert-success alert-styled-left alert-dismissible">
<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
<?php print $this->session->flashdata('success'); ?>
</div>
 <!-- /alert -->
<?php }		
if($this->session->flashdata('error')){
?>	
<!-- alert -->
<div class="alert alert-danger alert-styled-left alert-dismissible">
<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
<?php print $this->session->flashdata('error'); ?>
</div>
 <!-- /alert -->
<?php }	
if($this->session->flashdata('info')){
?>	
<!-- alert -->
<div class="alert alert-info alert-styled-left alert-dismissible">
<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
<?php print $this->session->flashdata('error'); ?>
</div>
 <!-- /alert -->
<?php }	
if($this->session->flashdata('warning')){
?>	
<!-- alert -->
<div class="alert alert-warning alert-styled-left alert-dismissible">
<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
<?php print $this->session->flashdata('error'); ?>
</div>
 <!-- /alert -->
<?php }	?>



<?php					
if(isset($show_view_alerts) && $show_view_alerts==true){
	if(isset($show_alert)){
		switch (strtolower($show_alert)) {
			case 'success':{?>
				<!-- alert -->
				<div class="alert alert-success alert-styled-left alert-dismissible">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<?php print $note_msg; ?>
				</div>
				 <!-- /alert -->
			<?php }
			break;
			case 'error':{?>
				<!-- alert -->
				<div class="alert alert-danger alert-styled-left alert-dismissible">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<?php print $note_msg; ?>
				</div>
				 <!-- /alert -->
			<?php }
			break;
			
			default:
				# code...
				break;
		}
		?>	

		<?php 
	}
}	
?>
						
						