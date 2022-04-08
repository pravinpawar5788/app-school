
    </div>
    <!-- /page content -->
    
<script type="text/javascript">
//print function
function publish(){$("#printing-content").printMe({});}

///page loaded. now call the functions
$(function(){
    "use strict";
    var editable = $(".editable").attr("contenteditable");
    $("#editing").on("click",function(){
        if(editable==true){
            editable=false;
            $(".editable").attr("contenteditable",false);
            swal({title:"Editing mode disabled.",type:'success',showConfirmButton:false,timer:2500});
        }else{
            editable=true;
            $(".editable").attr("contenteditable",true);
            swal({title:"You may now edit the content before printing.",type:'info',showConfirmButton:false,timer:3500});
        }
    });
    // handling dropdown menu for fabs
    $(this).on("keydown",function(e) {if(e.ctrlKey && e.keyCode === 80){publish();return false;};});

    $('.dropdown').on("mouseenter",function() {$(this).find('.content-area').stop(true, true).delay(200).fadeIn(500);});
    $('.dropdown').on("mouseleave",function() {$(this).find('.content-area').stop(true, true).delay(1200).fadeOut(500);});

    $('.dropdown-1').on("mouseenter",function() {$(this).find('.content-area-1').stop(true, true).delay(200).fadeIn(500);});
    $('.dropdown-1').on("mouseleave",function() {$(this).find('.content-area-1').stop(true, true).delay(1200).fadeOut(500);});

    $('.dropdown-2').on("mouseenter",function() {$(this).find('.content-area-2').stop(true, true).delay(200).fadeIn(500);});
    $('.dropdown-2').on("mouseleave",function() {$(this).find('.content-area-2').stop(true, true).delay(1200).fadeOut(500);});

    $('.dropdown-3').on("mouseenter",function() {$(this).find('.content-area-3').stop(true, true).delay(200).fadeIn(500);});
    $('.dropdown-3').on("mouseleave",function() {$(this).find('.content-area-3').stop(true, true).delay(1200).fadeOut(500);});

});

</script>
</body>
</html>
