
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php print ucwords($this->config->item('app_name'));?> |  Member Authentication</title>

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
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="<?php print $this->RES_ROOT;?>js/plugins/forms/styling/uniform.min.js"></script>

	<script src="<?php print $this->RES_ROOT;?>js/app.js"></script>
	<script src="<?php print $this->RES_ROOT;?>js/pages/login.js"></script>
	<!-- /theme JS files -->
	<style type="text/css">
		.hero-image{
			width: 100%;
			height: 100%;
			min-width: 100%;
			min-height: 100%;
		}
		.hero-image::before{
			background-image:url('<?php print $this->UPLOADS_ROOT;?>images/logo/<?php print $this->SETTINGS[$this->system_setting_m->_ORG_LOGO];?>');
			background-position: 50% 50%;
			background-repeat: repeat;
			/*background-size: cover;*/
			content: "";
			display: block;
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: -2;
			opacity: 0.4;
		}
		.hero-image::after {
			background-color: <?php print get_random_hax_color('dark');?>;
			content: "";
			display: block;
			position: absolute;
			top: 0px;
			left: 0px;
			width: 100%;
			height: 100%;
			z-index: -1;
			opacity: 0.6;
		}
	</style>

</head>

<?php if(intval($this->SETTINGS[$this->system_setting_m->_CUSTOM_AUTH_BG])>0){	?>
	<body class="hero-image">	
<?php }else{ ?>
	<body class="bg-slate-800">
<?php } ?>
