<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends Admin_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'report/';
        //load all models for this controller
        $models = array();
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index(){
		
		$this->data['main_content']='report_progress';	
		$this->data['menu']='report';			
		$this->data['sub_menu']='report_progress';
		$this->data['tab']=$this->uri->segment(4);
        $this->FOOTER_INC[]='charts/highcharts.js';

        $form=$this->input->safe_post();
        isset($form['year'])&&!empty($form['year'])? $year=$form['year'] : $year=$this->user_m->year;
        $this->data['year']=$year;
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}
    //progress report
    public function progress(){
        
        $this->data['main_content']='report_progress';   
        $this->data['menu']='report';           
        $this->data['sub_menu']='report_progress';
        $this->data['tab']=$this->uri->segment(4);
        $this->FOOTER_INC[]='charts/highcharts.js';

        $form=$this->input->safe_post();
        isset($form['year'])&&!empty($form['year'])? $year=$form['year'] : $year=$this->user_m->year;
        $this->data['year']=$year;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
        
    

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** AJAX FUNCTIONS *******************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	