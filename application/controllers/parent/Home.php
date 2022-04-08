<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Parent_Controller{

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** CONTANTS *************************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'home/';
        //load all models for this controller
        $models = array('note_m', 'student_m','staff_m','class_m','certificate_m','award_m','punishment_m','std_fee_voucher_m','stf_pay_voucher_m','income_m','expense_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index(){
		
		$this->data['main_content']='dashboard';	
		$this->data['menu']='dashboard';			
		$this->data['sub_menu']='dashboard';
		$this->data['tab']=$this->uri->segment(4);
        $this->ANGULAR_INC[]='dashboard';

        $this->THEME_INC[]='js/plugins/visualization/d3/d3.min.js';
        $this->THEME_INC[]='js/plugins/visualization/d3/d3_tooltip.js';
        $this->THEME_INC[]='js/plugins/forms/styling/switchery.min.js';
        $this->THEME_INC[]='js/plugins/forms/selects/bootstrap_multiselect.js';
        $this->THEME_INC[]='js/plugins/ui/moment/moment.min.js';
        $this->THEME_INC[]='js/plugins/pickers/daterangepicker.js';
        $this->HEADER_INC[]='js/pages/dashboard.js';


		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}
        
    


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	