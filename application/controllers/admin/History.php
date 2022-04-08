<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History extends Admin_Controller{

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** CONTANTS *************************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	public $WEBSITE_HOME;
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'history/';
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
	public function index($tab=''){
		
		$this->data['main_content']='history';	
		$this->data['menu']='history';			
		$this->data['sub_menu']='history';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='history';
        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}

    public function system($rid='',$tab=''){
        
        $this->data['main_content']='history_system';    
        $this->data['menu']='history_system';            
        $this->data['sub_menu']='history_system';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='history_system';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }


    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** AJAX FUNCTIONS *******************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    // filter rows
    public function filter(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('message','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->user_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->user_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['password']='';
            $this->RESPONSE['rows'][$i]['user']=$this->user_m->get_by_primary($row['user_id'])->name;
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // filter rows
    public function filterOrgHistory(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('domain','host','message','date');
            $like=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            ///////////////////////////////////////////////////////////////////////////////////////////
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby'])&& !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']="jd DESC, mid DESC";
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->system_history_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->system_history_m->get_rows($filter,array('like'=>$like),true);
            ////////////////////////////////////////////////////////////////////////
            $i=0;
            foreach($this->RESPONSE['rows'] as $row){
                $this->RESPONSE['rows'][$i]['organization']=$this->organization_m->get_by_primary($row['org_id'])->name;
                $this->RESPONSE['rows'][$i]['campus']=$this->campus_m->get_by_primary($row['campus_id'])->name;
                $i++;
            }
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	