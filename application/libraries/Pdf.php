<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf{
	protected $CI;
	
	//constructor of the class, optional params will loading the class
	function __construct($params=array())
	{
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
		//$this->CI->load->helper('url');
	}

	
	//write below required functions
	public function generate($data=''){
		$dompdf = new Dompdf\Dompdf();
		$dompdf->loadHtml($data);

		// Render the HTML as PDF
		$dompdf->render();
		$dompdf->stream();
	}
}
