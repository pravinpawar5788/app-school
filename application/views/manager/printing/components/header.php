<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="<?php print $this->RES_ROOT;?>images/logo.png" type="image/x-icon" />
  <link rel="shortcut icon" href="<?php print $this->RES_ROOT;?>images/logo.png" type="image/x-icon" />
  <?php if(isset($print_page_title) && !empty($print_page_title)){?>
    <title><?php print $print_page_title;?></title>
  <?php }else{?>
    <title><?php print $this->config->item('app_name');?> - Printing</title>
  <?php }?>


  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/layout.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/components.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/colors.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/printing.css" rel="stylesheet" type="text/css" />
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="<?php print $this->RES_ROOT;?>js/main/jquery.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/main/bootstrap.bundle.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/loaders/blockui.min.js"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <script src="<?php print $this->RES_ROOT;?>js/plugins/editors/ckeditor/ckeditor.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/ui/prism.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/ui/fab.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/extensions/jquery_ui/widgets.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/visualization/d3/d3.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/sticky.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/uploaders/dropzone.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/forms/styling/switchery.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/notifications/sweet_alert.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/forms/selects/select2.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/forms/styling/uniform.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/app.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/pages/widgets_content.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/custom.js"></script>


  <!-- /theme JS files -->

</head>
<!-- <body class="navbar-top" ng-app="mozzApp"> -->
<body class="navbar-top">

  <script type="text/javascript">
  $.blockUI({ 
      message: '<span class="font-weight-semibold" ><i class="icon-spinner4 spinner mr-2"></i>&nbsp; Please Wait...</span>',
      overlayCSS: {
        backgroundColor: '#000',
        // backgroundColor: '#1b2024',
        opacity: 0.9,
      },
      css: {
        border: 0,
        color: '#fff',
        padding: 0,
        backgroundColor: 'transparent'
      }
    });  
  </script>

  <!-- Main navbar -->
  <div class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="navbar-brand">
      <a  href="<?php print $this->LIB_CONT_ROOT;?>" class="d-inline-block">
        <span class="brand-name"><?php print ucwords($this->config->item('app_name'));?><span class="brand-version"><?php print $this->config->item('app_version');?></span></span>
      </a>
    </div>
    <div class="d-md-none">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
        <i class="icon-paragraph-justify3"></i>
      </button>
    </div>


    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="<?php print isset($back_url) ? $back_url : $this->LIB_CONT_ROOT;?>" class="navbar-nav-link">
            <i class="icon-arrow-left52 mr-2"></i>
            Go Back
          </a>
        </li>
      </ul>


      <ul class="navbar-nav ml-auto">

        <li class="nav-item mouse-pointer" data-popup="popover" data-trigger="hover" data-html="true" data-placement="bottom" data-content="We strongly recommend <code> Chrome </code>&<code> Firefox </code>browser for printing purpose. You may not get intended output in other browsers.">
          <a class="navbar-nav-link" onclick="publish()">
            <i class="icon-printer mr-2"></i>
            <span class="">Print</span>
          </a>          
        </li>
        <li class="nav-item mouse-pointer" data-popup="popover" data-trigger="hover" data-html="true" data-placement="bottom" data-content="Some times you may need to edit text before printing. <code>Editing Mode</code> let you do this easily.">
          <a class="navbar-nav-link" id="editing">
            <i class="icon-compose mr-2"></i>
            <span >Toggle Editing Mode</span>
          </a>          
        </li>
        <li class="nav-item mouse-pointer">
          <a href="<?php print $this->APP_ROOT.'docs/printing';?>" class="navbar-nav-link" target="_blank">
            <i class="icon-lifebuoy mr-2"></i>
            <span >Docs</span>
          </a>          
        </li>

        
      </ul>


    </div>
  </div>
  <!-- /main navbar -->

  <!-- Page content -->
  <div class="page-content">
