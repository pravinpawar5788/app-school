<?php if($this->IS_LYSAANEMS){ ?>
<span class="text-muted">This print is created by Mr/Mrs <?php print ucwords(strtolower($this->LOGIN_USER->name));?> using Lysaan EMS software on <?php print date('D d-F-Y h:i:s A');?>. Thank you for using Lysaan EMS.</span>
<?php }else{ ?>
<span class="text-muted">This print is created by Mr/Mrs <?php print ucwords(strtolower($this->LOGIN_USER->name));?> using Mozzine EMS software on <?php print date('D d-F-Y h:i:s A');?>. Thank you for using Mozzine EMS.</span>
<?php } ?>