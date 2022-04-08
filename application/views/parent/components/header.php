<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="<?php print $this->RES_ROOT;?>images/logo.png" type="image/x-icon" />
  <link rel="shortcut icon" href="<?php print $this->RES_ROOT;?>images/logo.png" type="image/x-icon" />
  <title><?php print ucwords($this->config->item('app_name'));?> | Parent Dashboard</title>

  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/icons/icomoon/styles.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/bootstrap.min.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/bootstrap_limitless.min.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/layout.min.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/components.min.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/colors.min.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/hotkeys.min.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css" />
  <link href="<?php print $this->RES_ROOT;?>css/custom.css<?php print $this->FILE_VERSION;?>" rel="stylesheet" type="text/css" />
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="<?php print $this->RES_ROOT;?>js/main/jquery.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/main/bootstrap.bundle.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/loaders/blockui.min.js<?php print $this->FILE_VERSION;?>"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <script src="<?php print $this->RES_ROOT;?>js/plugins/extensions/jquery_ui/widgets.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/visualization/d3/d3.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/sticky.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/uploaders/dropzone.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/tables/datatables/datatables.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/tables/datatables/extensions/natural_sort.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/forms/styling/switchery.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/notifications/sweet_alert.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/forms/selects/select2.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/plugins/forms/styling/uniform.min.js<?php print $this->FILE_VERSION;?>"></script>
  <?php if(count($this->THEME_INC)>0){foreach ($this->THEME_INC as $inc){?>
  <link href="<?php print $this->RES_ROOT;?><?php print $inc;?><?php print $this->FILE_VERSION;?>"/>
  <?php }} ?> 

  <script src="<?php print $this->RES_ROOT;?>js/app.js<?php print $this->FILE_VERSION;?>"></script>
  <?php if(count($this->HEADER_INC)>0){foreach ($this->HEADER_INC as $inc){?>
  <link href="<?php print $this->RES_ROOT;?><?php print $inc;?><?php print $this->FILE_VERSION;?>"/>
  <?php }} ?>
  <script src="<?php print $this->RES_ROOT;?>js/pages/widgets_content.js<?php print $this->FILE_VERSION;?>"></script>
  <script src="<?php print $this->RES_ROOT;?>js/custom.js<?php print $this->FILE_VERSION;?>"></script>
  <!-- /theme JS files -->

  <!-- angular js files -->  
  <?php  if(count($this->ANGULAR_INC)>0){?>
  <script type="text/javascript" src="<?php print $this->ANGULAR_ROOT;?>libs/pubnub.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script type="text/javascript" src="<?php print $this->ANGULAR_ROOT;?>angular.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script type="text/javascript" src="<?php print $this->ANGULAR_ROOT;?>libs/pubnub-angular.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script type="text/javascript" src="<?php print $this->ANGULAR_ROOT;?>hotkeys.min.js<?php print $this->FILE_VERSION;?>"></script>
  <script type="text/javascript" src="<?php print $this->ANGULAR_ROOT;?>parent/app_module.js<?php print $this->FILE_VERSION;?>"></script>
  <?php  foreach($this->ANGULAR_INC as $inc){?>
  <script type="text/javascript" src="<?php print $this->ANGULAR_ROOT;?>parent/<?php print $inc;?>.js<?php print $this->FILE_VERSION;?><?php print mt_rand(111111,999999);?>"></script>
  <?php }} ?>
  <!-- /angular js files -->

</head>