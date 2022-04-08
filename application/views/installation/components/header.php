
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php print ucwords($this->config->item('app_name'));?> | Installation</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/components.min.css" rel="stylesheet" type="text/css">
	<link href="<?php print $this->RES_ROOT;?>css/colors.min.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="<?php print $this->RES_ROOT;?>images/logo.png" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php print $this->RES_ROOT;?>images/logo.png" type="image/x-icon" />
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="<?php print $this->RES_ROOT;?>js/main/jquery.min.js"></script>
	<script src="<?php print $this->RES_ROOT;?>js/main/bootstrap.bundle.min.js"></script>
	<script src="<?php print $this->RES_ROOT;?>js/plugins/loaders/blockui.min.js"></script>
	<?php if(count($this->THEME_INC)>0){foreach ($this->THEME_INC as $inc){?>
	<link href="<?php print $this->RES_ROOT;?><?php print $inc;?>"/>
	<?php }} ?>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="<?php print $this->RES_ROOT;?>js/plugins/forms/styling/uniform.min.js"></script>

	<script src="<?php print $this->RES_ROOT;?>js/app.js"></script>
	<script src="<?php print $this->RES_ROOT;?>js/pages/login.js"></script>	
	<?php if(count($this->HEADER_INC)>0){foreach ($this->HEADER_INC as $inc){?>
	<link href="<?php print $this->RES_ROOT;?><?php print $inc;?>"/>
	<?php }} ?>
	<!-- /theme JS files -->

</head>

<body class="bg-slate-800">
