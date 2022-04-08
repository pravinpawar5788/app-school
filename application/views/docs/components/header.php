<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" type="image/x-icon" />
  <link rel="shortcut icon" href="<?php print $this->UPLOADS_ROOT.'images/logo/'.$this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>" type="image/x-icon" />
  <title><?php print ucwords($this->config->item('app_name'));?> | Documentation</title>

  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/layout.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/components.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/colors.min.css" rel="stylesheet" type="text/css">
  <link href="<?php print $this->RES_ROOT;?>css/custom_docs.css" rel="stylesheet" type="text/css" />
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="<?php print $this->RES_ROOT;?>js/main/jquery.min.js"></script>
  <script src="<?php print $this->RES_ROOT;?>js/main/bootstrap.bundle.min.js"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
    <?php if(count($this->THEME_INC)>0){foreach ($this->THEME_INC as $inc){?>
    <link href="<?php print $this->RES_ROOT;?><?php print $inc;?>"/>
    <?php }} ?>

  <script src="<?php print $this->RES_ROOT;?>js/app.js"></script>  
  <?php if(count($this->HEADER_INC)>0){foreach ($this->HEADER_INC as $inc){?>
  <link href="<?php print $this->RES_ROOT;?><?php print $inc;?>"/>
  <?php }} ?>


</head>