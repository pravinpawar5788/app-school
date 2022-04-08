
    </div>
    <!-- /page content -->
    
    <?php
    if(count($this->FOOTER_INC)>0){foreach ($this->FOOTER_INC as $inc){?>
    <script type="text/javascript" src="<?php print $this->RES_ROOT;?><?php print $inc;?>"></script>
    <?php }} ?>
    <script type="text/javascript">
    //check key down
    document.onkeydown = function(e) {
        
        //ctrl+p
        if (e.ctrlKey && e.keyCode === 80) {
            publish();
            // your code here
            return false;
        }
    };
    //print function
    function publish(){
            $("#printing-content").printMe({
                    "path" : ["<?php print $this->RES_ROOT;?>css/bootstrap.css","<?php print $this->RES_ROOT;?>css/print.css"],
                });
    }
    </script>
</body>
</html>
