<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends Staff_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'accounts/';
        //load all models for this controller
        $models = array('std_fee_voucher_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
        
        $this->data['main_content']='accounts';    
        $this->data['menu']='accounts';            
        $this->data['sub_menu']='accounts';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='accounts';
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

                $this->data['main_content']='staff_profile';    
                $this->data['print_page_title']='Staff profile'; 

            }
            break;
            case 'list':{

                $this->data['main_content']='staff_list';    
                $this->data['print_page_title']='Staff List'; 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'staff', 'refresh');                       
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
    public function filterPayHistory(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$this->LOGIN_USER->mid);
        $params=array();
        $search=array('title','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_pay_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_pay_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $user=$this->staff_m->get_by_primary($row['staff_id']);
            $this->RESPONSE['rows'][$i]['staff_name']=$user->name;
            $this->RESPONSE['rows'][$i]['image']=$user->image;
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterPay(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$this->LOGIN_USER->mid);
        $params=array();
        $search=array('title','staff_name','voucher_id','date','status','stf_id');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='month_number DESC, type DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_pay_voucher_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_pay_voucher_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $user=$this->staff_m->get_by_primary($row['staff_id']);
            $this->RESPONSE['rows'][$i]['image']=$user->image;
            $this->RESPONSE['rows'][$i]['amount']=$this->stf_pay_entry_m->get_voucher_amount($row['voucher_id'],$this->stf_pay_entry_m->OPT_PLUS,$this->CAMPUSID);
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // load single row
    public function loadPayVoucher(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->stf_pay_voucher_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID,'staff_id'=>$this->LOGIN_USER->mid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid slip...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_pay_voucher_m->get_by_primary($rid);
        $row->plus_amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,$this->stf_pay_entry_m->OPT_PLUS,$this->CAMPUSID);
        $row->minus_amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,$this->stf_pay_entry_m->OPT_MINUS,$this->CAMPUSID);
        $row->entries=$this->stf_pay_entry_m->get_rows(array('voucher_id'=>$row->voucher_id,'campus_id'=>$this->CAMPUSID));
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	