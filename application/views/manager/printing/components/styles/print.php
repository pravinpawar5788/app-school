<?php 
	if(isset($form['layout']) && !empty($form['layout']) && strtolower($form['layout'])=='landscape' ){
		$this->PRINT_PAGE_LAYOUT='landscape';
	}
	if(isset($form['page']) && !empty($form['page']) ){
		switch (strtolower($form['page'])) {
			case 'a2':{$this->PRINT_PAGE_SIZE='A2';}break;			
			case 'a3':{$this->PRINT_PAGE_SIZE='A3';}break;			
			case 'a5':{$this->PRINT_PAGE_SIZE='A5';}break;			
			case 'a6':{$this->PRINT_PAGE_SIZE='A6';}break;			
			case 'a7':{$this->PRINT_PAGE_SIZE='A7';}break;			
			case 'a8':{$this->PRINT_PAGE_SIZE='A8';}break;			
			default:{$this->PRINT_PAGE_SIZE='A4';}break;
		}
	}
?>
<style type="text/css">

.page-header, .page-header-space {
	min-height: 40pt;
	max-height: 50pt;
	padding: 2pt;
	margin: 2mm;
}

.page-footer, .page-footer-space {
  min-height: 25pt;
  padding: 1pt;
  margin: 2mm;

}
.page-footer-right{
	float: right;
	padding-right: 5mm;
}

.page-header {
	padding: 2pt;
	-webkit-print-color-adjust: exact;
	/*background: #3F7CAC; */
	/*background-color: #969fa5 !important;*/
	/*background-image: linear-gradient(#003049,#3F7CAC);*/
	/*color:#FFFFFF;*/
}
.page-header-image{vertical-align: middle;}
.page-form-name{font-size:1.5em;}
.page-campus-name{font-size:1.2em;}
.page-campus-contact{font-size:1.1em;}


@page {
  margin: 2mm;
}

@media print {

	body, page {
		margin: 0;
		box-shadow: 0;
		padding: 2mm;
		background: none;
		background-color: none;
	}

	@page {
		margin: 2mm;
		background: none;
		background-color: none;
		/*margin: 0.2cm;*/
		size: <?php print $this->PRINT_PAGE_SIZE;?> <?php print $this->PRINT_PAGE_LAYOUT;?>;
	    -webkit-print-color-adjust: exact;
	}

	.force-page-break-after{
		page-break-after: always;
		break-after: page;
	}
	.force-page-break-before{
		page-break-before: always;
		break-before: page;
	}
	.avoid-page-break{
		page-break-inside: avoid;
		break-inside: avoid;
	}

	.page {
	  -webkit-print-color-adjust: exact;
	  /*page-break-after: always;*/
	}

	.page-footer {
	  position: fixed;
	  bottom: 0;
	  width: 100%;
	  border-top: 1px solid black; 
	  /*background: yellow; */
	}

	.page-header {
	  position: fixed;
	  top: 0mm;
	  width: 100%;
	  /*border-bottom: 1px solid white;*/
	  /*background-image: linear-gradient(black,blue);*/
	  /*-webkit-print-color-adjust: exact;*/
	  /*background: yellow; */
	}

}


</style>