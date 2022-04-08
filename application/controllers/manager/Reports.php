<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Manager_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'reports/';
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
        //redirect to items page
        redirect($this->CONT_ROOT.'sms', 'refresh');		
	}
    // show sms history
    public function sms($tab=''){
        
        $this->data['main_content']='reports_sms';   
        $this->data['menu']='reports';           
        $this->data['sub_menu']='reports_sms';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='reports_sms';
        /////////////////////////////////////////////////////////////
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);    
    }

    // show earning history
    public function earning($tab=''){
        
        $this->data['main_content']='report_earning';   
        $this->data['menu']='reports';           
        $this->data['sub_menu']='report_earning';
        $this->data['tab']=$tab;
        $this->FOOTER_INC[]='charts/highcharts.js';

        $form=$this->input->safe_post();
        isset($form['year'])&&!empty($form['year'])? $year=$form['year'] : $year=$this->user_m->year;
        $this->data['year']=$year;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }


    // show earning history
    public function attendance($tab=''){
        $this->load->model(array('std_attendance_m','stf_attendance_m'));
        
        $this->data['main_content']='report_attendance';   
        $this->data['menu']='reports';           
        $this->data['sub_menu']='report_attendance';
        $this->data['tab']=$tab;
        $this->FOOTER_INC[]='charts/highcharts.js';

        $form=$this->input->safe_post();
        isset($form['year'])&&!empty($form['year'])? $year=$form['year'] : $year=$this->user_m->year;
        isset($form['month'])&&!empty($form['month'])? $month=$form['month'] : $month=$this->user_m->month;
        $this->data['year']=$year;
        $this->data['month']=$month;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }

    // fee analytics
    public function feecollection($tab=''){
        $this->load->model(array('std_fee_voucher_m','std_fee_entry_m','class_m'));
        
        $this->data['main_content']='report_feecollection';   
        $this->data['menu']='reports';           
        $this->data['sub_menu']='report_feecollection';
        $this->data['tab']=$tab;
        $this->FOOTER_INC[]='charts/highcharts.js';

        $form=$this->input->safe_post();
        isset($form['year'])&&!empty($form['year'])? $year=$form['year'] : $year=$this->user_m->year;
        isset($form['month'])&&!empty($form['month'])? $month=$form['month'] : $month=$this->user_m->month;
        $this->data['year']=$year;
        $this->data['month']=$month;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }

    // payroll analytics
    public function payroll($tab=''){
        $this->load->model(array('std_fee_voucher_m','std_fee_entry_m','stf_pay_voucher_m','stf_pay_entry_m','class_m'));
        
        $this->data['main_content']='report_payroll';   
        $this->data['menu']='reports';           
        $this->data['sub_menu']='report_payroll';
        $this->data['tab']=$tab;
        $this->FOOTER_INC[]='charts/highcharts.js';

        $form=$this->input->safe_post();
        isset($form['year'])&&!empty($form['year'])? $year=$form['year'] : $year=$this->user_m->year;
        isset($form['month'])&&!empty($form['month'])? $month=$form['month'] : $month=$this->user_m->month;
        $this->data['year']=$year;
        $this->data['month']=$month;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }


    ///////Print function for this module/////////////
    public function printing($action=''){
        
        $form=$this->input->safe_get();
        $this->data['form']=$form;
        if($this->session->flashdata('back_url')){
            $this->session->keep_flashdata('back_url');
        }else{
            $this->session->set_flashdata('back_url', $this->agent->referrer());
        }
        ///////////////////start processing
        switch (strtolower($action)) {
            case 'profile':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid student');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_profile';    
                $this->data['print_page_title']='Student profile'; 

            }
            break;
            case 'list':{
                $this->data['main_content']='student_list';    
                $this->data['print_page_title']='Students List'; 

            }
            break;
            case 'allowances':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                $this->data['main_content']='staff_allowances';    
                $this->data['print_page_title']='Staff Member Allowances'; 

            }
            break;
            case 'history':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_history';    
                $this->data['print_page_title']='Student History'; 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'student', 'refresh');                       
            }break;
        }
        if($this->session->flashdata('back_url')){$this->data['back_url']=$this->session->flashdata('back_url');}    
        $this->load->view($this->LIB_VIEW_DIR.'printing/master', $this->data);   
    }


    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** AJAX FUNCTIONS *******************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    // filter rows
    public function filterSmsHistory(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('mobile','message','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['sortby']='jd DESC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->sms_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->sms_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	