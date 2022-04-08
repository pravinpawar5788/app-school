<?php 
// font scales in pt e.g 0=>9pt, 1=>10pt
$scales=array(9,10,11,12,13,14,15,16);
$fixed_height=false;
isset($form['fh']) ? $fixed_height=true : '';

?>

<style type="text/css">

@media screen {
	body {
	  background: rgb(204,204,204); 
	}

	page {
	  background: white;
	  display: block;
	  margin: 0 auto;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A2"] {
	  width: 420mm;
	  <?php if($fixed_height){?>height: 594mm;<?php }?>
	  /*height: 594mm;*/
	}
	page[size="A2"][layout="landscape"] {
	  width: 594mm;
	  <?php if($fixed_height){?>height: 420mm;<?php }?>
	  /*height: 29.7cm;  */
	}
	page[size="A3"] {
	  width: 297mm;
	  <?php if($fixed_height){?>height: 420mm;<?php }?>
	  /*height: 420mm;*/
	}
	page[size="A3"][layout="landscape"] {
	  width: 420mm;
	  <?php if($fixed_height){?>height: 297mm;<?php }?>
	  /*height: 297mm;  */
	}
	page[size="A4"] {  
	  width: 210mm;
	  <?php if($fixed_height){?>height: 297mm;<?php }?>
	  /*height: 297mm; */
	}
	page[size="A4"][layout="landscape"] {
	  width: 297mm;
	  <?php if($fixed_height){?>height: 210mm;<?php }?>
	  /*height: 210mm;  */
	}
	page[size="A5"] {
	  width: 148mm;
	  <?php if($fixed_height){?>height: 210mm;<?php }?>
	  /*height: 210mm;*/
	}
	page[size="A5"][layout="landscape"] {
	  width: 210mm;
	  <?php if($fixed_height){?>height: 148mm;<?php }?>
	  /*height: 148mm;  */
	}
	page[size="A6"] {
	  width: 105mm;
	  <?php if($fixed_height){?>height: 148mm;<?php }?>
	  /*height: 148mm;*/
	}
	page[size="A6"][layout="landscape"] {
	  width: 148mm;
	  <?php if($fixed_height){?>height: 105mm;<?php }?>
	  /*height: 105mm;  */
	}
	page[size="A7"] {
	  width: 74mm;
	  <?php if($fixed_height){?>height: 105mm;<?php }?>
	  /*height: 105mm;*/
	}
	page[size="A7"][layout="landscape"] {
	  width: 105mm;
	  <?php if($fixed_height){?>height: 74mm;<?php }?>
	  /*height: 74mm;  */
	}
	page[size="A8"] {
	  width: 52mm;
	  <?php if($fixed_height){?>height: 74mm;<?php }?>
	  /*height: 74mm;*/
	}
	page[size="A8"][layout="landscape"] {
	  width: 74mm;
	  <?php if($fixed_height){?>height: 52mm;<?php }?>
	  /*height: 52mm;  */
	}


}
/*
page[size="A4"] {  
  width: 21cm;
  height: 29.7cm; 
}
page[size="A4"][layout="landscape"] {
  width: 29.7cm;
  height: 21cm;  
}
page[size="A3"] {
  width: 29.7cm;
  height: 42cm;
}
page[size="A3"][layout="landscape"] {
  width: 42cm;
  height: 29.7cm;  
}
page[size="A5"] {
  width: 14.8cm;
  height: 21cm;
}
page[size="A5"][layout="landscape"] {
  width: 21cm;
  height: 14.8cm;  
}
*/
/*
@media print {
  body, page {
    margin: 0;
    box-shadow: 0;
  }
}
*/


	.page{
		/*set font scale as per user selection	*/
		<?php if(isset($form['scale']) && !empty($form['scale']) ){
			$scale=intval($form['scale']);if($scale<0){$scale=0;}if($scale>count($scales)){$scale=count($scales);}
			?>font-size: <?php print $scales[$scale];?>pt;<?php
		}else{
			?>font-size: <?php print $scales[$this->CAMPUSSETTINGS['font_scale']];?>pt;<?php
		} ?>
		margin: 10pt;
		font-size: 0.95em;
	}

	.card-solid{
		padding: 1pt;
		margin-top: 1pt;
		border: 1px solid black;
	}
	.card-dotted{
		padding: 1pt;
		margin-top: 1pt;
		border: 1px dotted black;
	}
	.card-dashed{
		padding: 1pt;
		margin-top: 1pt;
		border: 1px dashed black;
	}

	/*table styling*/
	.table{
		table-layout: fixed;
		width: 100%;
		border-collapse: collapse;
		padding: 0.3rem 0.5rem;
		border:1px solid black;
	}
	.d-block{
		display: block;
	}
	.table thead th, .table tfoot th{
		font-weight: bold;
		border:1px solid black;
		background-color: #969fa5 !important;
		/*background-color: #F25F38 !important;*/
		/*color: #FFFFFF !important;*/
		text-align: center;
		white-space: nowrap;
		vertical-align: middle;

	}
	.table tbody td{
		border:1px solid black;
		vertical-align: middle;
		/*white-space: nowrap;*/
		/*text-align: center;*/
	}
	.table-sm th{padding: 0.35rem 0.4rem;	}
	.table-sm td{padding: 0.25rem 0.4rem;	}

	.th_max_2{width: 2%; max-width: 20px;}
	.th_max_3{width: 3%; max-width: 30px;}
	.th_max_4{width: 4%; max-width: 40px;}
	.th_max_5{width: 5%; max-width: 50px;}
	.th_max_6{width: 6%; max-width: 60px;}
	.th_max_7{width: 7%; max-width: 70px;}
	.th_max_8{width: 8%; max-width: 85px;}
	.th_max_9{width: 9%; max-width: 98px;}
	.th_max_10{width: 10%; max-width: 115px;}
	.th_max_15{width: 15%; max-width: 175px;}
	.th_max_17{width: 17%; max-width: 200px;}
	.th_max_20{width: 20%; max-width: 230px;}
	.th_max_25{width: 25%; max-width: 300px;}

	.th_min_2{width: 2%; min-width: 20px;}
	.th_min_3{width: 3%; min-width: 30px;}
	.th_min_4{width: 4%; min-width: 40px;}
	.th_min_5{width: 5%; min-width: 50px;}
	.th_min_6{width: 6%; min-width: 60px;}
	.th_min_7{width: 7%; min-width: 70px;}
	.th_min_8{width: 8%; min-width: 85px;}
	.th_min_9{width: 9%; min-width: 98px;}
	.th_min_10{width: 10%; min-width: 115px;}
	.th_min_15{width: 15%; min-width: 175px;}
	.th_min_17{width: 17%; min-width: 200px;}
	.th_min_20{width: 20%; min-width: 230px;}
	.th_min_25{width: 25%; min-width: 300px;}

	.font-0-1em{font-size: 0.1em;}
	.font-0-2em{font-size: 0.2em;}
	.font-0-3em{font-size: 0.3em;}
	.font-0-4em{font-size: 0.4em;}
	.font-0-5em{font-size: 0.5em;}
	.font-0-6em{font-size: 0.6em;}
	.font-0-7em{font-size: 0.7em;}
	.font-0-8em{font-size: 0.8em;}
	.font-0-9em{font-size: 0.9em;}
	.font-1-0em{font-size: 1.0em;}
	.font-1-1em{font-size: 1.1em;}
	.font-1-2em{font-size: 1.2em;}
	.font-1-3em{font-size: 1.3em;}
	.font-1-4em{font-size: 1.4em;}
	.font-1-5em{font-size: 1.5em;}
	.font-1-6em{font-size: 1.6em;}
	.font-1-7em{font-size: 1.7em;}
	.font-1-8em{font-size: 1.8em;}
	.font-1-9em{font-size: 1.9em;}
	.font-2-0em{font-size: 2.0em;}
	.font-2-1em{font-size: 2.1em;}
	.font-2-2em{font-size: 2.2em;}
	.font-2-3em{font-size: 2.3em;}
	.font-2-4em{font-size: 2.4em;}
	.font-2-5em{font-size: 2.5em;}

	.font-style-italic{font-style: italic;}
	.font-style-normal{font-style: normal;}
	.font-style-oblique{font-style: oblique;}
	.font-family-georgia{font-family: Georgia, serif;}
	.font-family-palatino{font-family:"Palatino Linotype","Book Antiqua",Palatino,serif;}
	.font-family-times-nr{font-family: "Times New Roman", Times, serif;}
	.font-family-arial{font-family: Arial, sans-serif;}
	.font-family-helvetica{font-family: Helvetica, sans-serif;}
	.font-family-arial-black{font-family: "Arial Black", Gadget, sans-serif;}
	.font-family-comic{font-family: "Comic Sans MS", cursive, sans-serif;}
	.font-family-impact{font-family: Impact, Charcoal, sans-serif;}
	.font-family-lucida{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;}
	.font-family-tahoma{font-family: Tahoma, Geneva, sans-serif;}
	.font-family-trebuchet{font-family: "Trebuchet MS", Helvetica, sans-serif;}
	.font-family-verdana{font-family: Verdana, Geneva, sans-serif;}
	.font-family-courier{font-family: "Courier New", Courier, monospace;}
	.font-family-lucida-console{font-family: "Lucida Console", Monaco, monospace;}

	.grd-bg-default{
		padding: 3pt;
		-webkit-print-color-adjust: exact;
		/*background: #3F7CAC; */
		/*background-image: linear-gradient(#003049,#3F7CAC);*/
		color:#000;
		background-color: #969fa5 !important;
	}
	.grd-bg-orange{
		padding: 3pt;
		-webkit-print-color-adjust: exact;
		/*background: #F25F38; */
		/*background-image: linear-gradient(#B14629,#F25F38);*/
		/*color:#FFFFFF;*/
		background-color: #969fa5 !important;
	}
	/*statements and underlines*/
	.para{
	 	line-height: 1.5em !important;
		font-size: 1.2em;
		font-family: cursive;
	}
	.full-line{
		display: inline-block;
	 	width: 100%;
	}
	.underline{
		position: absolute;
		right: 0pt;
		font-style: oblique;
		font-weight: bold;
	 	border-bottom: 1px solid black;
	 	padding-left: 15%;
	 	width: 80%;
	 	&::after{
	      content: "\A";
	  	}
	}
	.short-underline{
		display: inline-block;
	 	border-bottom: 1px solid black;
	 	min-width: 10%;
	 	padding-left: 4%;
	 	padding-right: 4%;
		font-style: oblique;
		font-weight: bold;
	}
	.line-1,.w-1{width: 1%;	}
	.line-2,.w-2{width: 2%;	}
	.line-3,.w-3{width: 3%;	}
	.line-4,.w-4{width: 4%;	}
	.line-5,.w-5{width: 5%;	}
	.line-6,.w-6{width: 6%;	}
	.line-7,.w-7{width: 7%;	}
	.line-8,.w-8{width: 8%;	}
	.line-9,.w-9{width: 9%;	}
	.line-10,.w-10{width: 10%;	}
	.line-11,.w-11{width: 11%;	}
	.line-12,.w-12{width: 12%;	}
	.line-13,.w-13{width: 13%;	}
	.line-14,.w-14{width: 14%;	}
	.line-15,.w-15{width: 15%;	}
	.line-16,.w-16{width: 16%;	}
	.line-17,.w-17{width: 17%;	}
	.line-18,.w-18{width: 18%;	}
	.line-19,.w-19{width: 19%;	}
	.line-20,.w-20{width: 20%;	}
	.line-21,.w-21{width: 21%;	}
	.line-22,.w-22{width: 22%;	}
	.line-23,.w-23{width: 23%;	}
	.line-24,.w-24{width: 24%;	}
	.line-25,.w-25{width: 25%;	}
	.line-26,.w-26{width: 26%;	}
	.line-27,.w-27{width: 27%;	}
	.line-28,.w-28{width: 28%;	}
	.line-29,.w-29{width: 29%;	}
	.line-30,.w-30{width: 30%;	}
	.line-31,.w-31{width: 31%;	}
	.line-32,.w-32{width: 32%;	}
	.line-33,.w-33{width: 33%;	}
	.line-34,.w-34{width: 34%;	}
	.line-35,.w-35{width: 35%;	}
	.line-36,.w-36{width: 36%;	}
	.line-37,.w-37{width: 37%;	}
	.line-38,.w-38{width: 38%;	}
	.line-39,.w-39{width: 39%;	}
	.line-40,.w-40{width: 40%;	}
	.line-41,.w-41{width: 41%;	}
	.line-42,.w-42{width: 42%;	}
	.line-43,.w-43{width: 43%;	}
	.line-44,.w-44{width: 44%;	}
	.line-45,.w-45{width: 45%;	}
	.line-46,.w-46{width: 46%;	}
	.line-47,.w-47{width: 47%;	}
	.line-48,.w-48{width: 48%;	}
	.line-49,.w-49{width: 49%;	}
	.line-50,.w-50{width: 50%;	}
	.line-51,.w-51{width: 51%;	}
	.line-52,.w-52{width: 52%;	}
	.line-53,.w-53{width: 53%;	}
	.line-54,.w-54{width: 54%;	}
	.line-55,.w-55{width: 55%;	}
	.line-56,.w-56{width: 56%;	}
	.line-57,.w-57{width: 57%;	}
	.line-58,.w-58{width: 58%;	}
	.line-59,.w-59{width: 59%;	}
	.line-60,.w-60{width: 60%;	}
	.line-61,.w-61{width: 61%;	}
	.line-62,.w-62{width: 62%;	}
	.line-63,.w-63{width: 63%;	}
	.line-64,.w-64{width: 64%;	}
	.line-65,.w-65{width: 65%;	}
	.line-66,.w-66{width: 66%;	}
	.line-67,.w-67{width: 67%;	}
	.line-68,.w-68{width: 68%;	}
	.line-69,.w-69{width: 69%;	}
	.line-70,.w-70{width: 70%;	}
	.line-71,.w-71{width: 71%;	}
	.line-72,.w-72{width: 72%;	}
	.line-73,.w-73{width: 73%;	}
	.line-74,.w-74{width: 74%;	}
	.line-75,.w-75{width: 75%;	}
	.line-76,.w-76{width: 76%;	}
	.line-77,.w-77{width: 77%;	}
	.line-78,.w-78{width: 78%;	}
	.line-79,.w-79{width: 79%;	}
	.line-80,.w-80{width: 80%;	}
	.line-81,.w-81{width: 81%;	}
	.line-82,.w-82{width: 82%;	}
	.line-83,.w-83{width: 83%;	}
	.line-84,.w-84{width: 84%;	}
	.line-85,.w-85{width: 85%;	}
	.line-86,.w-86{width: 86%;	}
	.line-87,.w-87{width: 87%;	}
	.line-88,.w-88{width: 88%;	}
	.line-89,.w-89{width: 89%;	}
	.line-90,.w-90{width: 90%;	}
	.line-91,.w-91{width: 91%;	}
	.line-92,.w-92{width: 92%;	}
	.line-93,.w-93{width: 93%;	}
	.line-94,.w-94{width: 94%;	}
	.line-95,.w-95{width: 95%;	}
	.line-96,.w-96{width: 96%;	}
	.line-97,.w-97{width: 97%;	}
	.line-98,.w-98{width: 98%;	}
	.line-99,.w-99{width: 99%;	}
	.line-100,.w-100{width: 100%;	}

	.border-solid{border:1px solid black; vertical-align: middle;}
	.border-solid-2{border:2px solid black; vertical-align: middle;	}
	.border-dashed{border:1px dashed black; vertical-align: middle; }
	.border-dashed-2{border:2px dashed black; vertical-align: middle; }
	.border-dotted{border:1px dotted black; vertical-align: middle; }
	.border-dotted-2{border:2px dotted black; vertical-align: middle; }

	.border-bottom{border-bottom: 1px solid black;	}
	.border-top{border-top: 1px solid black;	}
	.border-left{border-left: 1px solid black;	}
	.border-right{border-right: 1px solid black;	}
	.border-bottom-dashed{border-bottom: 1px dashed black;	}
	.border-top-dashed{border-top: 1px dashed black;	}
	.border-left-dashed{border-left: 1px dashed black;	}
	.border-right-dashed{border-right: 1px dashed black;	}
	.border-bottom-dotted{border-bottom: 1px dotted black;	}
	.border-top-dotted{border-top: 1px dotted black;	}
	.border-left-dotted{border-left: 1px dotted black;	}
	.border-right-dotted{border-right: 1px dotted black;	}

	.text-bold{font-weight: bold;}
	.box{border:1px solid black;}
	.box-head{
		min-height: 25pt;
		text-align: center;
		background-color: #B8B8AA !important;
		border-bottom: 1px dotted black; 
	}
	.box-head-title{
		font-weight: bold;
		font-size: 1.1em;
	 	&::after{
	      content: "\A";
	  	}
	}
	.box-y-100{min-height: 100pt;}
	.box-y-150{min-height: 150pt;}
	.box-y-200{min-height: 200pt;}
	.box-y-250{min-height: 250pt;}
	.box-y-300{min-height: 300pt;}
	.box-y-350{min-height: 350pt;}
	.box-y-400{min-height: 400pt;}
	.box-y-500{min-height: 500pt;}
	/*margin and padding*/
	.fp-1{padding: 1pt;}
	.fp-2{padding: 2pt;}
	.fp-3{padding: 3pt;}
	.fp-4{padding: 4pt;}
	.fp-5{padding: 5pt;}
	.p-10{padding: 10pt;}
	.p-20{padding: 20pt;}
	.p-30{padding: 30pt;}
	.p-40{padding: 40pt;}
	.p-50{padding: 50pt;}
	.p-60{padding: 60pt;}
	.p-70{padding: 70pt;}
	.p-80{padding: 80pt;}
	.p-90{padding: 90pt;}
	.p-100{padding: 100pt;}

	.plr-1{padding-left: 1pt;padding-right: 1pt;}
	.plr-2{padding-left: 2pt;padding-right: 2pt;}
	.plr-3{padding-left: 3pt;padding-right: 3pt;}
	.plr-4{padding-left: 4pt;padding-right: 4pt;}
	.plr-5{padding-left: 5pt;padding-right: 5pt;}
	.plr-6{padding-left: 6pt;padding-right: 6pt;}
	.plr-7{padding-left: 7pt;padding-right: 7pt;}
	.plr-8{padding-left: 8pt;padding-right: 8pt;}
	.plr-9{padding-left: 9pt;padding-right: 9pt;}
	.plr-10{padding-left: 10pt;padding-right: 10pt;}
	.plr-15{padding-left: 15pt;padding-right: 15pt;}
	.plr-20{padding-left: 20pt;padding-right: 20pt;}
	.ptb-1{padding-top: 1pt;padding-bottom: 1pt;}
	.ptb-2{padding-top: 2pt;padding-bottom: 2pt;}
	.ptb-3{padding-top: 3pt;padding-bottom: 3pt;}
	.ptb-4{padding-top: 4pt;padding-bottom: 4pt;}
	.ptb-5{padding-top: 5pt;padding-bottom: 5pt;}

	.rt-0{right: 0pt;}
	.rt-1{right: 1pt;}
	.rt-2{right: 2pt;}
	.rt-3{right: 3pt;}
	.rt-4{right: 4pt;}
	.rt-5{right: 5pt;}
	.rt-6{right: 6pt;}
	.rt-7{right: 7pt;}
	.rt-8{right: 8pt;}
	.rt-9{right: 9pt;}
	.rt-10{right: 10pt;}
	.rt-11{right: 11pt;}
	.rt-12{right: 12pt;}
	.rt-13{right: 13pt;}
	.rt-14{right: 14pt;}
	.rt-15{right: 15pt;}
	.rt-16{right: 16pt;}
	.rt-17{right: 17pt;}
	.rt-18{right: 18pt;}
	.rt-19{right: 19pt;}
	.rt-20{right: 20pt;}
	.rt-25{right: 25pt;}
	.rt-30{right: 30pt;}
	.rt-35{right: 35pt;}
	.rt-40{right: 40pt;}
	.rt-45{right: 45pt;}
	.rt-50{right: 50pt;}

	.m-10{margin: 10pt;}
	.m-20{margin: 20pt;}
	.m-30{margin: 30pt;}
	.m-40{margin: 40pt;}
	.m-50{margin: 50pt;}
	.m-60{margin: 60pt;}
	.m-70{margin: 70pt;}
	.m-80{margin: 80pt;}
	.m-90{margin: 90pt;}
	.m-100{margin: 100pt;}

	.fw-5{width: 5pt;}
	.fw-10{width: 10pt;}
	.fw-15{width: 15pt;}
	.fw-20{width: 20pt;}
	.fw-25{width: 25pt;}
	.fw-30{width: 30pt;}
	.fw-35{width: 35pt;}
	.fw-40{width: 40pt;}
	.fw-45{width: 45pt;}
	.fw-50{width: 50pt;}

	.fh-1{height: 1pt;}
	.fh-2{height: 2pt;}
	.fh-3{height: 3pt;}
	.fh-4{height: 4pt;}
	.fh-5{height: 5pt;}
	.fh-6{height: 6pt;}
	.fh-7{height: 7pt;}
	.fh-8{height: 8pt;}
	.fh-9{height: 9pt;}
	.fh-10{height: 10pt;}
	.fh-11{height: 11pt;}
	.fh-12{height: 12pt;}
	.fh-13{height: 13pt;}
	.fh-14{height: 14pt;}
	.fh-15{height: 15pt;}
	.fh-16{height: 16pt;}
	.fh-17{height: 17pt;}
	.fh-18{height: 18pt;}
	.fh-19{height: 19pt;}
	.fh-20{height: 20pt;}
	.fh-25{height: 25pt;}
	.fh-30{height: 30pt;}
	.fh-35{height: 35pt;}
	.fh-40{height: 40pt;}
	.fh-45{height: 45pt;}
	.fh-50{height: 50pt;}

	.mlr-1{margin-left: 1pt;margin-right: 1pt;}
	.mlr-2{margin-left: 2pt;margin-right: 2pt;}
	.mlr-3{margin-left: 3pt;margin-right: 3pt;}
	.mlr-4{margin-left: 4pt;margin-right: 4pt;}
	.mlr-5{margin-left: 5pt;margin-right: 5pt;}
	.mlr-6{margin-left: 6pt;margin-right: 6pt;}
	.mlr-7{margin-left: 7pt;margin-right: 7pt;}
	.mlr-8{margin-left: 8pt;margin-right: 8pt;}
	.mtb-1{margin-top: 1pt;margin-bottom: 1pt;}
	.mtb-2{margin-top: 2pt;margin-bottom: 2pt;}
	.mtb-3{margin-top: 3pt;margin-bottom: 3pt;}
	.mtb-4{margin-top: 4pt;margin-bottom: 4pt;}
	.mtb-5{margin-top: 5pt;margin-bottom: 5pt;}

	.vertical-center{vertical-align: middle;}
	.horizontal-center{margin: 0 auto;}
	.vertical-horizontal-center{vertical-align: middle;margin: 0 auto;}
	/*///////////////////////////////////////////////////////////////////////////*/
	.vchr-td-height{height: 16pt;}
	.voucher-td-bg{background-color: #969fa5 !important;}
	.vchr-filed-pl{padding-left: 5pt;}
	/*form printing*/
	.form-block{
		/*width: 100%;*/
		border-radius: 2pt;
		margin-top: 3pt;
		margin-bottom: 3pt;
		border: 1px solid black;
	}
	.form-block-dashed{border: 1px dashed black;}
	.form-block-dotted{border: 1px dotted black;}

	.form-block-head{
		text-transform: uppercase;
		font-weight: bold;
		text-align: center;
		font-size: large;
		/*color: #FFFFFF;*/
		-webkit-print-color-adjust: exact;
		/*background: #3F7CAC; */
		/*background-image: linear-gradient(#003049,#3F7CAC);*/
		background-color: #969fa5 !important;
	}
	.form-block-body{
		margin-top: 3pt;
		line-height: 1.6em;

	}
	.line{
		display: inline-block;
		width: 100%;
	}
	.line-fill{
		display: inline-block;
	 	border-bottom: 1px solid black;
	}	
	.line-abspos{
		position: absolute;
	}	


	.form-line{
		display: inline-block;
		/*line-height: 1.8em;*/
		font-weight: bold;
	 	width: 100%;
	 	margin-left: 3pt;
	 	margin-bottom: 3pt;
	 	margin-top: 3pt;
	}

	.form-line-fill{
		display: block;
		position: absolute;
		right: 0pt;
	 	padding-left: 5pt;
	 	margin-right: 2pt;
	 	min-width: 10%;
	 	border-bottom: 1px solid black;
	 	&::after{
	      content: "\A";
	  	}

	}
	.form-line-textfill{
		display: inline-block;
		position: absolute;
		right: 0pt;
	 	padding-left: 5pt;
	 	margin-right: 2pt;
	 	min-width: 10%;
	 	border-bottom: 1px solid black;
	 	text-align: center;
	 	/*&::after{
	      content: "\A";
	  	}*/
	  	
	}
	.form-line-fill-dashed{border-bottom: 1px dashed black;}
	.form-line-fill-dotted{border-bottom: 1px dotted black;}


	.form-box{
		margin-top: 2pt;
		display: block;
		border: 1px solid black;
		text-align: center;
		font-weight: bold;
	}
	.text-affirmation{
		line-height: 1.2;
		font-style: italic;
	}

	.form-check{
		display: inline-block;
		width: 5pt;
		height: 9pt;
	 	border: 1px solid black;
	}
	.form-check-title{
		padding-left: 1pt;
		font-weight: normal;
	}
	.form-check-icon{
		font-size: 1.5em;		
		padding: 3pt;
	}


</style>