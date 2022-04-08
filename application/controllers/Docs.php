<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends Docs_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'';
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
	public function index($page='',$submenu=''){
		
        if(empty($page)){$page='intro';}
        $page=strtolower($page);
        if(!file_exists(FCPATH.'application/views/docs/'.$page.'.php')){$page='intro';}
        $this->data['main_content']=$page;
        $this->data['menu']=$page;
        $this->data['sub_menu']=$submenu;

        $this->THEME_INC[]='js/plugins/prism.min.js';
        $this->THEME_INC[]='js/plugins/sticky.min.js';
        $this->HEADER_INC[]='js/pages/components_scrollspy.js';


		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}
        
    


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	