<style type="text/css">

@media print {

	@page{ 
	  @top-center {
	    margin: 10pt 0 30pt 0;
	    border-top: .25pt solid #666;
	    content: "<?php print $this->config->item('app_name') ?>";
	    font-size: 8pt;
	    color: #333;
	  }
	}

	table {
	margin-bottom: 1em;
	width: 100%;
	min-width: 750px;
	}
	.minus-margin{
		margin-top: -150px;
	}

}


/*//////////////////////////*/
/*body{
	padding: 1em;
	font-family:    sans-serif, Tahoma, "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "Jameel Noori Nastaleeq";
	font-size: 10pt;
}*/
/*table, figure{
	page-break-inside: avoid;	
}
table {
	margin-bottom: 1em;
	width: 100%;
	/*min-width: 100px;*
	text-align:justify;
}

table td {
	padding: 3px;
	/*text-align:center;*
}
thead { display: table-header-group; }
tfoot {display: table-footer-group;}*/
.heading{	
	text-align:center;
	font-size: 14pt;
	font-weight: bold;
}
.table1 {
	border: 1px solid red;
}

.table2,.table2 td {
	border: 1px solid silver;
	border-collapse: collapse;
}

.table2 td.dark {
	background-color: #E2E4F6;
}
.table2 th {
	background-color: #E2E4F6;
	/*text-align:center;*/
	height: 30px;
	font-size: 12pt;
	font-weight: bold;
}

.even {
	background-color: lightgrey;
}
.CSSTableGenerator {
	margin: 0px;
	padding: 0px;
	width: 100%;
	box-shadow: 10px 10px 5px #888888;
	border: 1px solid #000000;
	-moz-border-radius-bottomleft: 0px;
	-webkit-border-bottom-left-radius: 0px;
	border-bottom-left-radius: 0px;
	-moz-border-radius-bottomright: 0px;
	-webkit-border-bottom-right-radius: 0px;
	border-bottom-right-radius: 0px;
	-moz-border-radius-topright: 0px;
	-webkit-border-top-right-radius: 0px;
	border-top-right-radius: 0px;
	-moz-border-radius-topleft: 0px;
	-webkit-border-top-left-radius: 0px;
	border-top-left-radius: 0px;
}

.CSSTableGenerator table {
	border-collapse: collapse;
	border-spacing: 0;
	width: 100%;
	height: 100%;
	margin: 0px;
	padding: 0px;
}

.CSSTableGenerator tr:last-child td:last-child {
	-moz-border-radius-bottomright: 0px;
	-webkit-border-bottom-right-radius: 0px;
	border-bottom-right-radius: 0px;
}

.CSSTableGenerator table tr:first-child td:first-child {
	-moz-border-radius-topleft: 0px;
	-webkit-border-top-left-radius: 0px;
	border-top-left-radius: 0px;
}

.CSSTableGenerator table tr:first-child td:last-child {
	-moz-border-radius-topright: 0px;
	-webkit-border-top-right-radius: 0px;
	border-top-right-radius: 0px;
}

.CSSTableGenerator tr:last-child td:first-child {
	-moz-border-radius-bottomleft: 0px;
	-webkit-border-bottom-left-radius: 0px;
	border-bottom-left-radius: 0px;
}

.CSSTableGenerator tr:hover td {

}

.CSSTableGenerator tr:nth-child(odd) {
	background-color: #ffaa56;
}

.CSSTableGenerator tr:nth-child(even) {
	background-color: #ffffff;
}

.CSSTableGenerator td {
	vertical-align: middle;
	border: 1px solid #000000;
	border-width: 0px 1px 1px 0px;
	text-align: left;
	padding: 7px;
	font-size: 10px;
	font-family: Arial;
	font-weight: normal;
	color: #000000;
}

.CSSTableGenerator tr:last-child td {
	border-width: 0px 1px 0px 0px;
}

.CSSTableGenerator tr td:last-child {
	border-width: 0px 0px 1px 0px;
}

.CSSTableGenerator tr:last-child td:last-child {
	border-width: 0px 0px 0px 0px;
}

.CSSTableGenerator tr:first-child td {
	background: -o-linear-gradient(bottom, #ff7f00 5%, #bf5f00 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ff7f00), color-stop(1, #bf5f00));
	background: -moz-linear-gradient(center top, #ff7f00 5%, #bf5f00 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ff7f00", endColorstr="#bf5f00");
	background: -o-linear-gradient(top, #ff7f00, bf5f00);
	background-color: #ff7f00;
	border: 0px solid #000000;
	text-align: center;
	border-width: 0px 0px 1px 1px;
	font-size: 14px;
	font-family: Arial;
	font-weight: bold;
	color: #ffffff;
}

.CSSTableGenerator tr:first-child:hover td {
	background: -o-linear-gradient(bottom, #ff7f00 5%, #bf5f00 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ff7f00), color-stop(1, #bf5f00));
	background: -moz-linear-gradient(center top, #ff7f00 5%, #bf5f00 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ff7f00", endColorstr="#bf5f00");
	background: -o-linear-gradient(top, #ff7f00, bf5f00);
	background-color: #ff7f00;
}

.CSSTableGenerator tr:first-child td:first-child {
	border-width: 0px 0px 1px 0px;
}

.CSSTableGenerator tr:first-child td:last-child {
	border-width: 0px 0px 1px 1px;
}

.profile-header{
	display: inline-flex;
}
.profile-info{
	margin-left: 25px;
}
.profile-info-row{
	height: 35px;
	padding: 3px;
	border: 1px solid silver;
}
.profile-info-key{
	padding-left: 10px;
	padding-right: 10px;
	background-color: #E2E4F6;
	text-transform: uppercase;
	font-weight: bold;
}
.profile-info-value{
	padding-left: 15px;
	padding-right: 15px;
	text-transform: uppercase;
	font-weight: bold;
}

/*//////////////////////////////////////////////////*/

.voucher-single{
	width: 100%;
	margin-top: 30px;
	font-family:  sans-serif, Tahoma, "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans";
	font-size: 10px;
	text-align: center;
	page-break-after: always;

}
.voucher-single .v-org-title{
	padding-left: 3px;
	padding-right: 3px;
	font-size: 16px;
	text-transform: uppercase;
	font-weight: bold;
	text-align: center;
	height: 40px;

}
.voucher-single .v-org-subtitle{
	font-size: 12px;
	text-transform: capitalize;
	text-align: center;
	height: 20px;

}
.voucher-single .v-info-key{
	padding-left: 3px;
	padding-right: 3px;
	background-color: #E2E4F6;
	text-transform: uppercase;
	/*font-weight: bold;*/
	text-align: center;
	border: 1px solid silver;
}
.voucher-single .v-info-value{
	padding-left: 5px;
	padding-right: 5px;
	text-align: center;
	border: 1px solid silver;
}
.voucher-single .v-entry-info-key{
	padding-left: 3px;
	padding-right: 3px;
	font-size: 10px;
	text-align: center;
	border: 1px solid silver;
}
.voucher-single .v-entry-info-value{
	padding-left: 5px;
	padding-right: 5px;
	font-size: 10px;
	text-align: center;
	border: 1px solid silver;
}
/*///////////////////////////////////////////*/
.voucher-multiple * {
	max-width: 100%;
	margin-top: 30px;
	font-family:  sans-serif, Tahoma, "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans";
	font-size: 10px;
	text-align: center;
	/*page-break-after: always;*/

}
.voucher-multiple .office-copy{
	float: right;
}/*
.voucher-multiple .student-copy{
	width: 100%;
}*/
.voucher-multiple .table-left{
	width: 10%;
	border-collapse: collapse;
}
.voucher-multiple .table-right{
	/*float: right;*/
	width: 10%;
	border-collapse: collapse;
}
.voucher-multiple .vm-org-title{
	padding-left: 3px;
	padding-right: 3px;
	font-size: 16px;
	text-transform: uppercase;
	font-weight: bold;
	text-align: center;

}
.voucher-multiple .vm-org-subtitle{
	font-size: 12px;
	text-transform: capitalize;
	text-align: center;

}
.voucher-multiple .vm-info-key{/*
	padding-left: 1px;
	padding-right: 1px;
	background-color: #E2E4F6;
	text-transform: uppercase;
	text-align: center;
	border: 1px solid silver;*/
}
.voucher-multiple .vm-info-value{/*
	padding-left: 2px;
	padding-right: 2px;
	text-align: center;
	border: 1px solid silver;*/
}
.voucher-multiple .vm-entry-info-key{/*
	font-size: 10px;
	text-align: center;
	border: 1px solid silver;*/
}
.voucher-multiple .vm-entry-info-value{/*
	font-size: 10px;
	text-align: center;
	border: 1px solid silver;*/
}
</style>