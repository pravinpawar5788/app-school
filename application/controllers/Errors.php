<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller{
	//Global Vars
    public $APP_ROOT;
	public $RES_ROOT;
	
	 function __construct (){
			parent::__construct();	
			//INIT HOST NAME
			$this->tools->set_base_url();
			$this->APP_ROOT=base_url();
			$this->RES_ROOT=$this->APP_ROOT.'assets/portal/';

	} 
	//default function 
	public function index()
	{
		$this->load->view('authorization/auth_miscofig');
	
	}
	
}
