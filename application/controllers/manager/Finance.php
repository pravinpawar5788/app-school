<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends Manager_Controller{

/** 
* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** CONTANTS *************************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
    public $CURRENT_ACCOUNTING_PID;
    protected $DATA_RESET_PASSWORD;
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        if($this->LOGIN_USER->prm_finance<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }        
        $this->DATA_RESET_PASSWORD='aroosh-'.date('dmy');
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'finance/';
        //load all models for this controller
        $models = array('stf_role_m','class_m','class_section_m','class_feepackage_m','student_m','staff_m','std_acheivement_m','std_award_m','std_history_m','std_fee_history_m','std_fee_entry_m','std_fee_voucher_m','stf_history_m','stf_pay_history_m','stf_pay_voucher_m','stf_pay_entry_m','stf_allownce_m','stf_deduction_m','std_fee_concession_m','concession_type_m');
        $this->load->model($models);
        $this->CURRENT_ACCOUNTING_PID=$this->accounts_period_m->get_current_period_id($this->CAMPUSID);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
        $this->session->set_flashdata('error', 'Permission Denied!');
        redirect($this->LIB_CONT_ROOT.'', 'refresh'); 
	}


    // student instant fee management
    public function instantfee($tab=''){
        
        $this->data['main_content']='finance_instantfee';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_instantfee';
        $this->data['tab']=$tab;
        $this->BODY_INIT=' class="sidebar-xs" ';
        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_instantfee';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);  
    }

    // student fee management
    public function fee($tab=''){
        
        $this->data['main_content']='finance_fee';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_fee';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_fee';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);  
    }
    // staff pay management
    public function payment($tab=''){
        
        $this->data['main_content']='finance_pay';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_pay';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_pay';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);  
    }
    // expense management
    public function expense($tab=''){        
        $this->data['main_content']='finance_expenses';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_expenses';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_expense';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);  
    }
    // income management
    public function income($tab=''){        
        $this->data['main_content']='finance_income';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_income';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_income';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);  
    }
    // ledger management
    public function ledger($tab=''){        
        $this->data['main_content']='finance_ledger';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_ledger';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_ledger';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);  
    }
    ///////ACCOUNTS FUNCTION/////////////
    public function accounts($tab=''){
        $this->accounts_m->validate_default_tables($this->CAMPUSID,$this->system_setting_m->get_setting($this->system_setting_m->_ORG_ACCOUNTING_PERIOD));
        
        $this->data['main_content']='finance_accounts';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_accounts';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_accounts';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }

    ///////ACCOUNTS PROFILE FUNCTION/////////////
    public function accountpro($rid='',$tab=''){
        if(empty($rid) || $this->accounts_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid account');
            redirect($this->CONT_ROOT.'accounts', 'refresh'); 
        }
        $this->data['main_content']='finance_accounts_profile';    
        $this->data['menu']='finance';            
        $this->data['sub_menu']='finance_accounts';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='finance_accounts_profile';
        $this->data['account']=$this->accounts_m->get_by_primary($rid);
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }


    ///////SAVE ACCOUNTS BACKUP FUNCTION/////////////
    public function accountsbackup($password='',$table='accounts'){
        if(!$this->IS_DEV_LOGIN){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }

        if($password != $this->DATA_RESET_PASSWORD){
            $this->session->set_flashdata('error', 'Invalid Data Reset Password!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }

        switch ($table) {
            case 'accounts':{
                $data=$this->accounts_m->get_rows(array('campus_id'=>$this->CAMPUSID));
                array_csv_download( $data, $filename = "accounts.csv", $delimiter=";" );
            }
            break;
            case 'ledger':{
                $data=$this->accounts_ledger_m->get_rows(array('campus_id'=>$this->CAMPUSID));
                array_csv_download( $data, $filename = "journal.csv", $delimiter=";" );
            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Invalid Export Option!');
                redirect($this->LIB_CONT_ROOT.'', 'refresh'); 

            }
            break;
        }

    }


    ///////Profile function/////////////
    public function profile($rid='',$tab=''){
        if(empty($rid) || $this->staff_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid staff member');
            redirect($this->LIB_CONT_ROOT.'staff', 'refresh'); 
        }
        
        $this->data['main_content']='staff_profile';    
        $this->data['menu']='staff';            
        $this->data['sub_menu']='staff';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='staff_profile';
        $member=$this->staff_m->get_by_primary($rid);   
        $this->data['member']=$member;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
    ///////Print function for this module/////////////
    public function printing($action=''){
        set_time_limit(3600);        
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
            case 'feeslip':{

                $this->data['main_content']='finance_fee_slips';    
                $this->data['print_page_title']='Students Fee Slips'; 

            }
            break;
            case 'payslip':{

                $this->data['main_content']='finance_pay_slips';    
                $this->data['print_page_title']='Staff Pay Slips'; 

            }
            break;
            case 'report':{
                if(!isset($form['rpt'])){
                    $this->session->set_flashdata('error', 'Please choose a Report');
                    redirect($this->LIB_CONT_ROOT.'finance', 'refresh');
                }
                switch(strtolower($form['rpt'])){

                    case 'stdpaid' : {
                    $this->data['main_content']='report_finance_list_std_paid';   
                    $this->data['print_page_title']='Student Fee Payment Report';                        
                    }
                    break;
                    case 'stdunpaid' : {
                    $this->data['main_content']='report_finance_list_std_unpaid';   
                    $this->data['print_page_title']='Student Pending Fee Report';                        
                    }
                    break;
                    case 'feedefaulter' : {
                    $this->data['main_content']='report_finance_feedefaulter';   
                    $this->data['print_page_title']='Student Fee Defaulter Report';                        
                    }
                    break;
                    case 'stdconcession' : {
                    $this->data['main_content']='report_finance_std_concession';   
                    $this->data['print_page_title']='Student Concession Report ';                        
                    }
                    break;
                    case 'stfpaid' : {
                    $this->data['main_content']='report_finance_list_stf_paid';   
                    $this->data['print_page_title']='Staff Payroll';                        
                    }
                    break;
                    case 'stfunpaid' : {
                    $this->data['main_content']='report_finance_list_stf_unpaid';   
                    $this->data['print_page_title']='Staff Payroll Report ';                        
                    }
                    break;
                    case 'rembalance' : {
                    $this->data['main_content']='report_finance_stf_rembalance';   
                    $this->data['print_page_title']='Staff Payroll Report';                        
                    }
                    break;
                    case 'fstatements' : {
                    $this->data['main_content']='report_finance_trial_balance';   
                    $this->data['print_page_title']='Campus Financial Statements';                        
                    }
                    break;
                    case 'detfeecollection' : {
                    $this->data['main_content']='report_finance_feecollection';   
                    $this->data['print_page_title']='Detail Fee Collection Report';                        
                    }
                    break;
                    case 'dailyfeecollection' : {
                    $this->data['main_content']='report_finance_std_feereceived';   
                    $this->data['print_page_title']='Daily Fee Collection Report';                        
                    }
                    break;
                    case 'dailyadvancecollection' : {
                    $this->data['main_content']='report_finance_std_advancereceived';   
                    $this->data['print_page_title']='Daily Advance Fee Collection Report';                        
                    }
                    break;
                    case 'feecollection' : {
                    $this->data['main_content']='report_finance_feecollection_daily';   
                    $this->data['print_page_title']='Fee Collection Report';                        
                    }
                    break;
                    case 'advancecollection' : {
                    $this->data['main_content']='report_finance_advancecollection_daily';   
                    $this->data['print_page_title']='Advance Fee Collection Report';                        
                    }
                    break;
                    case 'expenses' : {
                    $this->data['main_content']='report_expenses';   
                    $this->data['print_page_title']='Campus Expenses Report';                        
                    }
                    break;
                    case 'income' : {
                    $this->data['main_content']='report_income';   
                    $this->data['print_page_title']='Campus Revenue Report';                        
                    }
                    break;
                    case 'ledger' : {
                    $this->data['main_content']='report_ledger';   
                    $this->data['print_page_title']='Journal Entry Record Report';                        
                    }
                    break;




                    // case 'stdpaid' : {
                    // $this->data['main_content']='report_finance_list_std_paid';   
                    // $this->data['print_page_title']='Student Fee';                        
                    // }
                    // break;
                    // case 'stdunpaid' : {
                    // $this->data['main_content']='report_finance_list_std_unpaid';   
                    // $this->data['print_page_title']='Student Fee ';                        
                    // }
                    // break;
                    // case 'feedefaulter' : {
                    // $this->data['main_content']='report_finance_feedefaulter';   
                    // $this->data['print_page_title']='Student Fee';                        
                    // }
                    // break;
                    // case 'stdconcession' : {
                    // $this->data['main_content']='report_finance_std_concession';   
                    // $this->data['print_page_title']='Student Concession Report ';                        
                    // }
                    // break;
                    // case 'stfpaid' : {
                    // $this->data['main_content']='report_finance_list_stf_paid';   
                    // $this->data['print_page_title']='Staff Payroll';                        
                    // }
                    // break;
                    // case 'stfunpaid' : {
                    // $this->data['main_content']='report_finance_list_stf_unpaid';   
                    // $this->data['print_page_title']='Staff Payroll ';                        
                    // }
                    // break;
                    // case 'rembalance' : {
                    // $this->data['main_content']='report_finance_stf_rembalance';   
                    // $this->data['print_page_title']='Staff Payroll';                        
                    // }
                    // break;
                    // case 'fstatements' : {
                    // $this->data['main_content']='report_finance_trial_balance';   
                    // $this->data['print_page_title']='Financial Statements';                        
                    // }
                    // break;
                    // case 'dailyfeecollection' : {
                    // $this->data['main_content']='report_finance_feecollection_daily';   
                    // $this->data['print_page_title']='Daily Fee Collection Report';                        
                    // }
                    // break;
                    // case 'feecollection' : {
                    // $this->data['main_content']='report_finance_feecollection_daily';   
                    // $this->data['print_page_title']='Fee Collection Report';                        
                    // }
                    // break;
                    // case 'expenses' : {
                    // $this->data['main_content']='report_expenses';   
                    // $this->data['print_page_title']='Campus Expenses Report';                        
                    // }
                    // break;
                    // case 'income' : {
                    // $this->data['main_content']='report_income';   
                    // $this->data['print_page_title']='Campus Revenue Report';                        
                    // }
                    // break;
                    // case 'ledger' : {
                    // $this->data['main_content']='report_ledger';   
                    // $this->data['print_page_title']='Journal Entry Record Report';                        
                    // }
                    // break;
                    default:{
                    $this->session->set_flashdata('error', 'Please choose a valid report');
                    redirect($this->LIB_CONT_ROOT.'finance', 'refresh');

                    }
                    break;
                }

            }
            break;
            case 'form':{
                if(!isset($form['frm'])){
                    $this->session->set_flashdata('error', 'Please choose a Report');
                    redirect($this->LIB_CONT_ROOT.'finance', 'refresh');
                }
                switch(strtolower($form['frm'])){
                    case 'feecollection' : {
                    $this->data['main_content']='form_finance_feecollection';   
                    $this->data['print_page_title']='Fee Collection Form';                        
                    }
                    break;
                    case 'blankfeeslip' : {
                    $this->data['main_content']='form_finance_blankfeeslip';   
                    $this->data['print_page_title']='Empty Fee Slip';                        
                    }
                    break;
                    default:{
                    $this->session->set_flashdata('error', 'Please choose a valid form');
                    redirect($this->LIB_CONT_ROOT.'finance', 'refresh');

                    }
                    break;
                }

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'finance', 'refresh');                       
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


    // load single row
    public function loadInstantFeeRecord(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $computer_number=$form['rid'] : $computer_number='';
        //////////////////////////////////////////////////////////////////////////
        $std_cmpnum_filter=array('computer_number'=>$computer_number,'campus_id'=>$this->CAMPUSID);
        $months_record=array();
        $listed_vouchers=array();
        if($this->student_m->get_rows($std_cmpnum_filter,'',true)>0){
            //valid computer number entered.
            $student=$this->student_m->get_by($std_cmpnum_filter,true);
            $student->class=$this->class_m->get_by_primary($student->class_id)->title;
            $student->clsection=$this->class_section_m->get_by_primary($student->section_id)->name;
            $this->RESPONSE['student']=$student;

            $this_month=$this->student_m->month_number+3;
            for($m=12;$m>=0;$m--){
                $vchr_filter=array('student_id'=>$student->mid,'campus_id'=>$this->CAMPUSID,'month_number'=>($this_month-$m));
                if($this->std_fee_voucher_m->get_rows($vchr_filter,'',true)>0){
                    $vouchers=$this->std_fee_voucher_m->get_rows($vchr_filter,array('orderby'=>'jd ASC'));
                    $i=0;
                    $vch_entry_filter=array('student_id'=>$student->mid,'campus_id'=>$this->CAMPUSID);
                    $hd_total=0;
                    $hd_balance=0;
                    $hd_previous=0;
                    $hd_monthfee=0;
                    $hd_transport=0;
                    $hd_library=0;
                    $hd_stationery=0;
                    $hd_admission=0;
                    $hd_readmission=0;
                    $hd_annualfund=0;
                    $hd_paperfund=0;
                    $hd_miscfund=0;
                    $hd_prospectus=0;
                    $hd_absentfine=0;
                    $hd_lffine=0;
                    $hd_miscfine=0;
                    $hd_other=0;
                    $hd_paid=0;
                    $hd_concession=0;
                    $pay_date='';
                    $vmonth='';

                    $prev_vchr_filter=array('student_id'=>$student->mid,'campus_id'=>$this->CAMPUSID,'month_number'=>($this_month-($m+1)));
                    if($this->std_fee_voucher_m->get_rows($prev_vchr_filter,'',true)>0){
                        $prev_vouchers=$this->std_fee_voucher_m->get_rows($prev_vchr_filter,array('orderby'=>'jd ASC'));
                        foreach($prev_vouchers as $row){
                            $hd_previous+=$row['balance'];
                            //handle college voucher proceeding feature
                            // if($this->IS_COLLEGE){
                                // $vch_entry_filter['voucher_id']=$row['voucher_id'];
                                // $vch_entry_filter['month_number']=$row['month_number'];
                                // $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_MINUS;
                                // $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_CF;
                                // $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                                // $hd_balance+=$amount;
                                // ///************
                                // $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_PLUS;
                                // $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_BF;
                                // $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                                // $hd_previous+=$amount;                          
                            // }
                        }
                    }
                    foreach($vouchers as $row){
                        if($row['status'] != $this->std_fee_voucher_m->STATUS_PAID){
                            $v_amount=$this->std_fee_entry_m->get_voucher_amount($row['voucher_id']);
                            $rec=array('mid'=>$row['mid'],'title'=>$row['title'],'balance'=>$row['balance'],'amount'=>$v_amount);
                            array_push($listed_vouchers, $rec);                            
                        }
                        $vmonth=$row['month'].'-'.$row['year'];
                        $hd_balance+=$row['balance'];
                        ///////////////////////////////////////////////////////
                        $vch_entry_filter['voucher_id']=$row['voucher_id'];
                        $vch_entry_filter['month_number']=$row['month_number'];
                        $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_PLUS;

                        //////////////////////////////////////////////////////////////////
                        //handle college voucher proceeding feature
                        // if($this->IS_COLLEGE){
                            $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_MINUS;
                            $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_CF;
                            $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                            $hd_balance+=$amount;
                            ///***********************    
                            $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_PLUS;
                            $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_BF;
                            $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                            $hd_previous+=$amount;                       
                        // }
                        /////////////////////////////////////////////////////////////////
                        //normal fee calculations
                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FEE;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_monthfee+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_TRANSPORT;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_transport+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_LIBRARY;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_library+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_STATIONERY;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_stationery+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_ADMISSION;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_admission+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_READMISSION;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_readmission+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_ANNUALFUND;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_annualfund+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_PAPERFUND;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_paperfund+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FUND;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_miscfund+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_PROSPECTUS;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_prospectus+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_ABSENT_FINE;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_absentfine+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_LATE_FEE_FINE;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_lffine+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FINE;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_miscfine+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_OTHER;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total+=$amount;
                        $hd_other+=$amount;
                        //////////////////////////////////////////////////////////////////
                        $vch_entry_filter['operation']=$this->std_fee_entry_m->OPT_MINUS;
                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_FEE;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_paid+=$amount;

                        $vch_entry_filter['type']=$this->std_fee_entry_m->TYPE_CONCESSION;
                        $amount=ceil($this->std_fee_entry_m->get_column_result('amount',$vch_entry_filter));
                        $hd_total-=$amount;
                        $hd_concession+=$amount;
                    }
                    $hd_total+=$hd_previous;
                    $record=array('month'=>$vmonth,'date'=>$pay_date,'hd_previous'=>$hd_previous,'hd_monthfee'=>$hd_monthfee,'hd_transport'=>$hd_transport,'hd_library'=>$hd_library,'hd_stationery'=>$hd_stationery,'hd_admission'=>$hd_admission,'hd_annualfund'=>$hd_annualfund,'hd_paperfund'=>$hd_paperfund,'hd_miscfund'=>$hd_miscfund,'hd_prospectus'=>$hd_prospectus,'hd_readmission'=>$hd_readmission,'hd_absentfine'=>$hd_absentfine,'hd_concession'=>$hd_concession,'hd_lffine'=>$hd_lffine,'hd_miscfine'=>$hd_miscfine,'hd_other'=>$hd_other,'hd_total'=>$hd_total,'hd_paid'=>$hd_paid,'hd_balance'=>$hd_balance);
                    array_push($months_record, $record);
                }
            }


            $this->RESPONSE['vouchers']=$months_record;
            $this->RESPONSE['listed_vouchers']=$listed_vouchers;
        }else{
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please enter valid computer number...';
            echo json_encode($this->RESPONSE);exit();
        }

        ///////////////////////////////////       
        echo json_encode($this->RESPONSE);
    }

    // create row
    public function addInstantFeeRecord(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }

        if($this->std_fee_voucher_m->get_rows(array('mid'=>$form['rid'],'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select valid voucher...';
            echo json_encode($this->RESPONSE);exit();
        }  
        $row=$this->std_fee_voucher_m->get_by_primary($form['rid']);        
        $monthstamp=array('month'=>$row->month,'year'=>$row->year);
        if($row->status==$this->std_fee_voucher_m->STATUS_PAID || $row->status==$this->std_fee_voucher_m->STATUS_CANCELED){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Paid or canceled voucher can not be updated...';
            echo json_encode($this->RESPONSE);exit();
        }  

        $entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'month'=>$row->month,'year'=>$row->year,'operation'=>$this->std_fee_entry_m->OPT_PLUS);

        //add transport fee
        if(isset($form['famt_transport']) && intval($form['famt_transport'])>0){
            $entry['remarks']='Van Fee';
            $entry['amount']=floatval($form['famt_transport']);
            $entry['type']=$this->std_fee_entry_m->TYPE_TRANSPORT;
            if(isset($form['fttl_transport']) && !empty($form['fttl_transport']) ){$entry['remarks']=$form['fttl_transport'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FEE_TRANSPORT,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }

        //add stationery fee
        if(isset($form['famt_stationery']) && intval($form['famt_stationery'])>0){
            $entry['remarks']='Stationery Funds';
            $entry['amount']=floatval($form['famt_stationery']);
            $entry['type']=$this->std_fee_entry_m->TYPE_STATIONERY;
            if(isset($form['fttl_stationery']) && !empty($form['fttl_stationery']) ){$entry['remarks']=$form['fttl_stationery'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add library fee
        if(isset($form['famt_library']) && intval($form['famt_library'])>0){
            $entry['remarks']='Library Fee';
            $entry['amount']=floatval($form['famt_library']);
            $entry['type']=$this->std_fee_entry_m->TYPE_LIBRARY;
            if(isset($form['fttl_library']) && !empty($form['fttl_library']) ){$entry['remarks']=$form['fttl_library'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }

        //add admission fee
        if(isset($form['famt_admission']) && intval($form['famt_admission'])>0){
            $entry['remarks']='Admission Fee';
            $entry['amount']=floatval($form['famt_admission']);
            $entry['type']=$this->std_fee_entry_m->TYPE_ADMISSION;
            if(isset($form['fttl_admission']) && !empty($form['fttl_admission']) ){$entry['remarks']=$form['fttl_admission'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }

        //add readmission fee
        if(isset($form['famt_readmission']) && intval($form['famt_readmission'])>0){
            $entry['remarks']='Re Admission Fee';
            $entry['amount']=floatval($form['famt_readmission']);
            $entry['type']=$this->std_fee_entry_m->TYPE_READMISSION;
            if(isset($form['fttl_readmission']) && !empty($form['fttl_readmission']) ){$entry['remarks']=$form['fttl_readmission'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add annualfund fee
        if(isset($form['famt_annualfund']) && intval($form['famt_annualfund'])>0){
            $entry['remarks']='Annual Funds';
            $entry['amount']=floatval($form['famt_annualfund']);
            $entry['type']=$this->std_fee_entry_m->TYPE_ANNUALFUND;
            if(isset($form['fttl_annualfund']) && !empty($form['fttl_annualfund']) ){$entry['remarks']=$form['fttl_annualfund'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FUNDS,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add paperfund fee
        if(isset($form['famt_paperfund']) && intval($form['famt_paperfund'])>0){
            $entry['remarks']='Paper Funds';
            $entry['amount']=floatval($form['famt_paperfund']);
            $entry['type']=$this->std_fee_entry_m->TYPE_PAPERFUND;
            if(isset($form['fttl_paperfund']) && !empty($form['fttl_paperfund']) ){$entry['remarks']=$form['fttl_paperfund'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FUNDS,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add miscfund fee
        if(isset($form['famt_miscfund']) && intval($form['famt_miscfund'])>0){
            $entry['remarks']='Misc. Funds';
            $entry['amount']=floatval($form['famt_miscfund']);
            $entry['type']=$this->std_fee_entry_m->TYPE_FUND;
            if(isset($form['fttl_miscfund']) && !empty($form['fttl_miscfund']) ){$entry['remarks']=$form['fttl_miscfund'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FUNDS,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add prospectus fee
        if(isset($form['famt_prospectus']) && intval($form['famt_prospectus'])>0){
            $entry['remarks']='Prospectus Fee';
            $entry['amount']=floatval($form['famt_prospectus']);
            $entry['type']=$this->std_fee_entry_m->TYPE_PROSPECTUS;
            if(isset($form['fttl_prospectus']) && !empty($form['fttl_prospectus']) ){$entry['remarks']=$form['fttl_prospectus'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add absentfine fee
        if(isset($form['famt_absentfine']) && intval($form['famt_absentfine'])>0){
            $entry['remarks']='Absent Fine';
            $entry['amount']=floatval($form['famt_absentfine']);
            $entry['type']=$this->std_fee_entry_m->TYPE_ABSENT_FINE;
            if(isset($form['fttl_absentfine']) && !empty($form['fttl_absentfine']) ){$entry['remarks']=$form['fttl_absentfine'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FINE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add latefeefine fee
        if(isset($form['famt_latefeefine']) && intval($form['famt_latefeefine'])>0){
            $entry['remarks']='Late Fee Fine';
            $entry['amount']=floatval($form['famt_latefeefine']);
            $entry['type']=$this->std_fee_entry_m->TYPE_LATE_FEE_FINE;
            if(isset($form['fttl_latefeefine']) && !empty($form['fttl_latefeefine']) ){$entry['remarks']=$form['fttl_latefeefine'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FINE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add miscfine fee
        if(isset($form['famt_miscfine']) && intval($form['famt_miscfine'])>0){
            $entry['remarks']='Misc. Fine';
            $entry['amount']=floatval($form['famt_miscfine']);
            $entry['type']=$this->std_fee_entry_m->TYPE_FINE;
            if(isset($form['fttl_miscfine']) && !empty($form['fttl_miscfine']) ){$entry['remarks']=$form['fttl_miscfine'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FINE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add other fee
        if(isset($form['famt_other']) && intval($form['famt_other'])>0){
            $entry['remarks']='Others';
            $entry['amount']=floatval($form['famt_other']);
            $entry['type']=$this->std_fee_entry_m->TYPE_OTHER;
            if(isset($form['fttl_other']) && !empty($form['fttl_other']) ){$entry['remarks']=$form['fttl_other'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
        //add concession fee
        if(isset($form['famt_concession']) && intval($form['famt_concession'])>0){
            $entry['remarks']='Others';
            $entry['amount']=floatval($form['famt_concession']);
            $entry['operation']=$this->std_fee_entry_m->OPT_MINUS;
            $entry['type']=$this->std_fee_entry_m->TYPE_CONCESSION;
            if(isset($form['fttl_concession']) && !empty($form['fttl_concession']) ){$entry['remarks']=$form['fttl_concession'];}
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry['remarks'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_CONCESSION,$entry['amount'],true,$entry['remarks'],$entry['remarks'],'','',$monthstamp);
            $this->std_fee_entry_m->add_row($entry);
        }
    
        $this->adjustStudentBalances($row->student_id);
        //send back the resposne  
        $this->RESPONSE['message']='Arrears added successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // create row
    public function addInstantPayRecord(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data 
        //method: cash, advance, 
        $required=array('rid','date','amount','method');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        $balance=0; //remaining fee of student
        $row=$this->std_fee_voucher_m->get_by_primary($form['rid']);
        $day=get_day_from_date($form['date']);
        $year=get_year_from_date($form['date']);
        $month=get_month_from_date($form['date'],'-',true);
        $monthNumber=$row->month_number;
        // $monthNumber=get_month_number($month,$year);
        //only month number can receive the fee of this voucher
        // if($monthNumber != $row->month_number){
        //     $this->RESPONSE['error']=TRUE;
        //     $this->RESPONSE['message']='Please choose a valid receiving date from voucher month...';
        //     echo json_encode($this->RESPONSE);exit();
        // }

        $monthstamp=array('day'=>$day,'month'=>$month,'year'=>$year);
        $row->amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_PLUS,$this->CAMPUSID);        
        $minus_amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_MINUS,$this->CAMPUSID);
        $member=$this->student_m->get_by_primary($row->student_id);
        if($row->status==$this->std_fee_voucher_m->STATUS_PAID || $row->status==$this->std_fee_voucher_m->STATUS_CANCELED){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Paid and canceled voucher can not be changed...';
            echo json_encode($this->RESPONSE);exit();
        } 
        if($form['amount'] > ($row->amount-$minus_amount) ){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You can not receive amount more then voucher remaining amount('.($row->amount-$minus_amount).')...';
            echo json_encode($this->RESPONSE);exit();
        }  
        //mark voucher as paid if there is nothing to receive
        if($this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID) ==0 ){
            $data=array('date_paid'=>$form['date'],'status'=>$this->std_fee_voucher_m->STATUS_PAID);
            $this->std_fee_voucher_m->save($data,$row->mid);
            $this->RESPONSE['message']='Fee Paid Successfully...';
            echo json_encode($this->RESPONSE);exit();
        }  
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
        
        //default is cash  
        if(intval($form['amount'])<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please enter valid amount...';
            echo json_encode($this->RESPONSE);exit();

        }  
        //create ledger entry for this operation
        //add_entry($narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='')
        $form['title']='Fee Received';
        $debited_account=$this->accounts_m->_CASH;
        if(strtolower($form['method'])=='bank'){$debited_account=$this->accounts_m->_BANK;}
        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$debited_account,$this->accounts_m->_FEE_RECEIVABLE,$form['amount'],true,"Fee of $member->name","Fee of $member->name",'','',$monthstamp);
        /////////////////////////////////////////
        $entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$form['amount'],'remarks'=>$form['title']."($debited_account)",'operation'=>$this->std_fee_entry_m->OPT_MINUS,'date'=>$form['date'],'ledger_id'=>$ledger_id);
        if($this->std_fee_entry_m->add_row($entry)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Fee cannot be received at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ////////////////////////////////////////
        $this->std_fee_voucher_m->update_column_value($row->mid,'amount_paid',$form['amount']);
        $data=array('date_paid'=>$form['date'],'status'=>$this->std_fee_voucher_m->STATUS_PAID);
        ////if balance remains then
        if($this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID) > 0){
            //update to partial paid
            $data['status']=$this->std_fee_voucher_m->STATUS_PARTIAL_PAID;
            //balance for sms hook
            $balance=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID);
        }
        $this->std_fee_voucher_m->save($data,$row->mid);
        //add to student fee history
        $this->std_fee_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'title'=>$form['title'],'amount'=>$form['amount'],'voucher_id'=>$row->mid,'month'=>$row->month,'year'=>$row->year,'month_number'=>$row->month_number));
        //ADD TO ORG INCOME
        $title="Fee Of $member->name ($member->student_id)";
        $income=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>$form['title'],'amount'=>$form['amount'],'month'=>$row->month,'year'=>$row->year,'ledger_id'=>$ledger_id,'type'=>$this->accounts_m->_STUDENT_FEE);
        $income['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STUDENT_FEE,'',true);
        $this->income_m->add_row($income);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." received student(".$member->name.") fee via $debited_account.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        //////////////////////////////////////////////////////////////
        $this->RESPONSE['message']='Fee received.';
        $this->adjustStudentBalances($row->student_id);
        //////////////////////////////////////////////////////////////
        //check if there is any sms hook registered
        $filter=array('campus_id'=>$this->CAMPUSID);
        $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_PAY_FEE;
        $hooks=$this->sms_hook_m->get_rows($filter);
        $std_class=$this->class_m->get_by_primary($member->class_id);
        if(count($hooks)>0){
            // $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
            // $row=$this->student_m->get_by_primary($attendance->student_id);
            foreach ($hooks as $hook){
                //send sms to student
                if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$member->name,
                            '{FEE_PAID}'=>$form['amount'],
                            '{BALANCE}'=>$balance,
                            '{CLASS}'=>$std_class->title,
                            '{SECTION}'=>$member->section,
                            '{MONTH}'=>month_string($row->month)
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$member->mobile,$sms);
                }
                //send sms to guardian
                if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$member->name,
                            '{GUARDIAN}'=>$member->guardian_name,
                            '{FEE_PAID}'=>$form['amount'],
                            '{BALANCE}'=>$balance,
                            '{CLASS}'=>$std_class->title,
                            '{SECTION}'=>$member->section,
                            '{MONTH}'=>month_string($row->month)
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$member->guardian_mobile,$sms);
                }
            }
        }
        //send back the resposne  
        echo json_encode($this->RESPONSE);exit();
    }

    ///////////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterFeeHistory(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        // isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        // isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        // isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        // isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        // isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->std_fee_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_fee_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $user=$this->student_m->get_by_primary($row['student_id']);
            $this->RESPONSE['rows'][$i]['student_name']=$user->name;
            $this->RESPONSE['rows'][$i]['image']=$user->image;
            $this->RESPONSE['rows'][$i]['class']=$classes[$user->class_id];
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterPendingFee(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        // isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        // isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        // isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        // isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        // isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        
        $filter['status <>']= $this->std_fee_voucher_m->STATUS_PAID;
        if(isset($form['filter']) && !empty($form['filter'])){
            if($form['filter']=='admission_no'){
                if(intval($form['search'])>0){
                    $user=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'admission_no'=>$form['search']),true);
                    if(!empty($user)){
                        $params['where_in']=array('student_id'=>array($user->mid));
                    }else{
                        $params['where_in']=array('student_id'=>array(-1));
                    }
                }else{
                    $params['where_in']=array('student_id'=>array(-1));
                }
            }
            if($form['filter']=='computer_number'){
                if(intval($form['search'])>0){
                    $user=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'computer_number'=>$form['search']),true);
                    if(!empty($user)){
                        $params['where_in']=array('student_id'=>array($user->mid));
                    }else{
                        $params['where_in']=array('student_id'=>array(-1));
                    }
                }else{
                    $params['where_in']=array('student_id'=>array(-1));
                }
            }
            if($form['filter']=='family_number'){
                if(intval($form['search'])>0){
                    $stds=array();
                    $users=$this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'family_number'=>$form['search']));
                    foreach($users as $usr){array_push($stds, $usr['mid']);}
                    $params['where_in']=array('student_id'=>$stds);
                }else{
                    $params['where_in']=array('student_id'=>array(-1));
                }
            }
        }else{
            //search students who have given computer or family number
            $search=intval($form['search']);
            $wherein=array();
            $cpstd=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'computer_number'=>$search),true);
            if(!empty($cpstd)){array_push($wherein, $cpstd->mid);}
            $fmstd=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'family_number'=>$search),true);
            if(!empty($fmstd)){array_push($wherein, $fmstd->mid);}
            if(count($wherein)<1){
                $params['where_in']=array('student_id'=>array(-1));
            }else{
                $params['where_in']=array('student_id'=>$wherein);
            }
        }
        ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='month_number DESC, type DESC, class_id ASC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->std_fee_voucher_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_fee_voucher_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $sections=$this->class_section_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $user=$this->student_m->get_by_primary($row['student_id']);
            $this->RESPONSE['rows'][$i]['image']=$user->image;
            $this->RESPONSE['rows'][$i]['advance_amount']=$user->advance_amount;
            $this->RESPONSE['rows'][$i]['father_name']=$user->father_name;
            $this->RESPONSE['rows'][$i]['computer_number']=$user->computer_number;
            $this->RESPONSE['rows'][$i]['roll_no']=$user->roll_no;
            if($user->class_id>0){$this->RESPONSE['rows'][$i]['class']=$classes[$user->class_id];}
            if($user->section_id>0){$this->RESPONSE['rows'][$i]['section']=$sections[$user->section_id];}
            $this->RESPONSE['rows'][$i]['amount']=$this->std_fee_entry_m->get_voucher_amount($row['voucher_id'],'',$this->CAMPUSID);
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterFee(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','student_name','voucher_id','roll_no','date','status','std_id');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        

        if(isset($form['filter']) && !empty($form['filter'])){
            if($form['filter']=='admission_no'){
                if(intval($form['search'])>0){
                    $user=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'admission_no'=>$form['search']),true);
                    if(!empty($user)){
                        $params['where_in']=array('student_id'=>array($user->mid));
                    }else{
                        $params['where_in']=array('student_id'=>array(-1));
                    }
                }else{
                    $params['where_in']=array('student_id'=>array(-1));
                }
            }
            if($form['filter']=='computer_number'){
                if(intval($form['search'])>0){
                    $user=$this->student_m->get_by(array('campus_id'=>$this->CAMPUSID,'computer_number'=>$form['search']),true);
                    if(!empty($user)){
                        $params['where_in']=array('student_id'=>array($user->mid));
                    }else{
                        $params['where_in']=array('student_id'=>array(-1));
                    }
                }else{
                    $params['where_in']=array('student_id'=>array(-1));
                }
            }
            if($form['filter']=='family_number'){
                if(intval($form['search'])>0){
                    $stds=array();
                    $users=$this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'family_number'=>$form['search']));
                    foreach($users as $usr){array_push($stds, $usr['mid']);}
                    $params['where_in']=array('student_id'=>$stds);
                }else{
                    $params['where_in']=array('student_id'=>array(-1));
                }
            }
        }else{
            //only search if user is not filtering specific data
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='month_number DESC, type DESC, class_id ASC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->std_fee_voucher_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_fee_voucher_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $sections=$this->class_section_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){            
            $this->updateFixedLateFeeFine($row['mid']);
            //////////////////////////////////////////////////////////////////////
            $user=$this->student_m->get_by_primary($row['student_id'],'mid,image,name,father_name,roll_no,computer_number,family_number,class_id,section_id,fee,advance_amount');
            $this->RESPONSE['rows'][$i]['image']=$user->image;
            $this->RESPONSE['rows'][$i]['advance_amount']=$user->advance_amount;
            $this->RESPONSE['rows'][$i]['father_name']=$user->father_name;
            $this->RESPONSE['rows'][$i]['computer_number']=$user->computer_number;
            $this->RESPONSE['rows'][$i]['roll_no']=$user->roll_no;
            if($user->class_id>0){$this->RESPONSE['rows'][$i]['class']=$classes[$user->class_id];}
            if($user->section_id>0){$this->RESPONSE['rows'][$i]['section']=$sections[$user->section_id];}
            $this->RESPONSE['rows'][$i]['amount']=$this->std_fee_entry_m->get_voucher_amount($row['voucher_id'],'',$this->CAMPUSID);
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addFeeEntry(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','amount','operation');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        strtolower($form['operation'])=='subtract' ? $opt=$this->std_fee_entry_m->OPT_MINUS : $opt=$this->std_fee_entry_m->OPT_PLUS;
        $row=$this->std_fee_voucher_m->get_by_primary($form['rid']);
        $monthstamp=array('month'=>$row->month,'year'=>$row->year);
        if($row->status==$this->std_fee_voucher_m->STATUS_PAID || $row->status==$this->std_fee_voucher_m->STATUS_CANCELED){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Paid and canceled voucher can not be updated...';
            echo json_encode($this->RESPONSE);exit();

        }  
        if(intval($form['amount'])<1 || ($form['amount']>$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID) )&& $opt==$this->std_fee_entry_m->OPT_MINUS){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please enter valid amount...';
            echo json_encode($this->RESPONSE);exit();

        }  
        $entry_type=$this->std_fee_entry_m->TYPE_FEE;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        if($opt==$this->std_fee_entry_m->OPT_MINUS){
            $entry_type=$this->std_fee_entry_m->TYPE_CONCESSION;
            $this->std_fee_voucher_m->update_column_value($row->mid,'amount',$form['amount'],'minus');
            //create ledger entry for this operation
            //add_entry($narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='')
            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$this->accounts_m->_CONCESSION,$this->accounts_m->_FEE_RECEIVABLE,$form['amount'],true,$form['title'],$form['title'],'','',$monthstamp);
        }else{
            if(isset($form['type']) && !empty($form['type'])){$entry_type=$form['type'];}
            $this->std_fee_voucher_m->update_column_value($row->mid,'amount',$form['amount']);
            //create ledger entry for this operation
            //add_entry($narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='')
            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$form['amount'],true,$form['title'],$form['title'],'','',$monthstamp);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'month'=>$row->month,'year'=>$row->year,'amount'=>$form['amount'],'remarks'=>$form['title'],'operation'=>$opt,'type'=>$entry_type,'ledger_id'=>$ledger_id);
        if($this->std_fee_entry_m->add_row($entry)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        if(intval($this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID)) == 0){
            //voucher pending amount is 0. mark as paid
            $data=array('date_paid'=>$this->std_fee_voucher_m->date,'status'=>$this->std_fee_voucher_m->STATUS_PAID);
            $this->std_fee_voucher_m->save($data,$row->mid);
        }        
        $this->adjustStudentBalances($row->student_id);
        //send back the resposne  
        $this->RESPONSE['message']='Record updated successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function getPayment(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data 
        //method: cash, advance, 
        $required=array('rid','title','amount','method');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        $balance=0; //remaining fee of student
        $row=$this->std_fee_voucher_m->get_by_primary($form['rid']);
        $monthstamp=array('day'=>10,'month'=>$row->month,'year'=>$row->year);
        $row->amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_PLUS,$this->CAMPUSID);        
        $minus_amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_MINUS,$this->CAMPUSID);
        $member=$this->student_m->get_by_primary($row->student_id);
        if($row->status==$this->std_fee_voucher_m->STATUS_PAID || $row->status==$this->std_fee_voucher_m->STATUS_CANCELED){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Paid and canceled voucher can not be changed...';
            echo json_encode($this->RESPONSE);exit();
        } 
        // $voucher_balance=0; 
        // if($row->balance>0){
        //     $voucher_balance=$row->amount-($form['amount']+$minus_amount);            
        // }
        if($form['amount'] > ($row->amount-$minus_amount) ){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You can not receive amount more then voucher remaining amount...';
            echo json_encode($this->RESPONSE);exit();
        }  
        //mark voucher as paid if there is nothing to receive
        if($this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID) ==0 ){
            $data=array('date_paid'=>$this->std_fee_voucher_m->date,'status'=>$this->std_fee_voucher_m->STATUS_PAID);
            $this->std_fee_voucher_m->save($data,$row->mid);
            $this->RESPONSE['message']='Voucher Paid Successfully...';
            echo json_encode($this->RESPONSE);exit();
        }  
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
        switch (strtolower($form['method'])) {
          case 'advance':{
            $fee_amount=$row->amount-$minus_amount;  
            $form['amount']=$fee_amount;    //fee amount for sms hook          
            if($member->advance_amount < $fee_amount){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']=$member->name.' do not have enough amount to pay fee or did not deposited any advance fee.';
                echo json_encode($this->RESPONSE);exit();
            }
            //create ledger entry for this operation
            //add_entry($narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='')
            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,"Fee received of $member->name from advance amount",$this->accounts_m->_FEE_ADVANCE,$this->accounts_m->_FEE_RECEIVABLE,$fee_amount,true,"received fee of $member->name from advance amount","received fee of $member->name from advance amount",'','',$monthstamp);
            ////////////////////////////////////////////
            $entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$fee_amount,'remarks'=>'Fee received from advance fee','operation'=>$this->std_fee_entry_m->OPT_MINUS,'ledger_id'=>$ledger_id);
            if($this->std_fee_entry_m->add_row($entry)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Fee cannot be received at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            }
            ////////////////////////////////////////////////////////////////////////////////////////
            $this->student_m->update_column_value($member->mid,'advance_amount',$fee_amount,'minus');
            $this->std_fee_voucher_m->update_column_value($row->mid,'amount_paid',$fee_amount);
            $data=array('date_paid'=>$this->std_fee_voucher_m->date,'status'=>$this->std_fee_voucher_m->STATUS_PAID);
            if(($row->amount-$minus_amount) > ($fee_amount+$row->amount_paid)){$data['status']=$this->std_fee_voucher_m->STATUS_PARTIAL_PAID;}
            $this->std_fee_voucher_m->save($data,$row->mid);
            //add to student fee history
            $this->std_fee_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'title'=>'Fee paid from advance deposited amount','amount'=>$fee_amount,'voucher_id'=>$row->mid,'month'=>$row->month,'year'=>$row->year,'month_number'=>$row->month_number));
            //ADD TO ORG INCOME
            $title="Fee Of $member->name ($member->student_id)";
            $income=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>'Fee from advance deposited amount','amount'=>$fee_amount,'month'=>$row->month,'year'=>$row->year,'ledger_id'=>$ledger_id,'type'=>$this->accounts_m->_STUDENT_FEE);
            $income['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STUDENT_FEE,'',true);
            $this->income_m->add_row($income);
            //Log the user Activity
            $msg=$this->LOGIN_USER->name." received student(".$member->name.") fee from advance deposited fee.";
            $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
            //////////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Fee received. You may print the updated voucher for record purpose.';
         

          }
          break;          
          default:{
            //default is cash  
            if(intval($form['amount'])<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please enter valid amount...';
                echo json_encode($this->RESPONSE);exit();

            }  
            //create ledger entry for this operation
            //add_entry($narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='')
            $debited_account=$this->accounts_m->_CASH;
            if(strtolower($form['method'])=='bank'){$debited_account=$this->accounts_m->_BANK;}
            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$debited_account,$this->accounts_m->_FEE_RECEIVABLE,$form['amount'],true,"Fee of $member->name","Fee of $member->name",'','',$monthstamp);
            /////////////////////////////////////////
            $entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$form['amount'],'remarks'=>$form['title']."($debited_account)",'operation'=>$this->std_fee_entry_m->OPT_MINUS,'ledger_id'=>$ledger_id);
            if($this->std_fee_entry_m->add_row($entry)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Fee cannot be received at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            }
            ////////////////////////////////////////
            $this->std_fee_voucher_m->update_column_value($row->mid,'amount_paid',$form['amount']);
            $data=array('date_paid'=>$this->std_fee_voucher_m->date,'status'=>$this->std_fee_voucher_m->STATUS_PAID);
            ////if balance remains then
            if($this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID) > 0){
                //update to partial paid
                $data['status']=$this->std_fee_voucher_m->STATUS_PARTIAL_PAID;
                //balance for sms hook
                $balance=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID);
            }
            // if(($row->amount-$minus_amount) > ($form['amount']+$row->amount_paid)){
            // if(($row->amount) > ($form['amount']+$row->amount_paid)){
            //     $data['status']=$this->std_fee_voucher_m->STATUS_PARTIAL_PAID;
            //     // $balance=($row->amount-$minus_amount) - ($form['amount']+$row->amount_paid);    //balance for sms hook
            //     $balance=($row->amount) - ($form['amount']+$row->amount_paid);    //balance for sms hook
            // }
            $this->std_fee_voucher_m->save($data,$row->mid);
            //add to student fee history
            $this->std_fee_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'title'=>$form['title'],'amount'=>$form['amount'],'voucher_id'=>$row->mid,'month'=>$row->month,'year'=>$row->year,'month_number'=>$row->month_number));
            //ADD TO ORG INCOME
            $title="Fee Of $member->name ($member->student_id)";
            $income=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>$form['title'],'amount'=>$form['amount'],'month'=>$row->month,'year'=>$row->year,'ledger_id'=>$ledger_id,'type'=>$this->accounts_m->_STUDENT_FEE);
            $income['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STUDENT_FEE,'',true);
            $this->income_m->add_row($income);
            //Log the user Activity
            $msg=$this->LOGIN_USER->name." received student(".$member->name.") fee via $debited_account.";
            $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
            //////////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Fee received. You may print the updated voucher for record purpose.';
          }
          break;
        } 
        $this->adjustStudentBalances($row->student_id);
        //////////////////////////////////////////////////////////////
        //check if there is any sms hook registered
        $filter=array('campus_id'=>$this->CAMPUSID);
        $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_PAY_FEE;
        $hooks=$this->sms_hook_m->get_rows($filter);
        $std_class=$this->class_m->get_by_primary($member->class_id);
        if(count($hooks)>0){
            // $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
            // $row=$this->student_m->get_by_primary($attendance->student_id);
            foreach ($hooks as $hook){
                //send sms to student
                if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$member->name,
                            '{FEE_PAID}'=>$form['amount'],
                            '{BALANCE}'=>$balance,
                            '{CLASS}'=>$std_class->title,
                            '{SECTION}'=>$member->section,
                            '{MONTH}'=>month_string($row->month)
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$member->mobile,$sms);
                }
                //send sms to guardian
                if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$member->name,
                            '{GUARDIAN}'=>$member->guardian_name,
                            '{FEE_PAID}'=>$form['amount'],
                            '{BALANCE}'=>$balance,
                            '{CLASS}'=>$std_class->title,
                            '{SECTION}'=>$member->section,
                            '{MONTH}'=>month_string($row->month)
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$member->guardian_mobile,$sms);
                }
            }
        }
        //send back the resposne  
        echo json_encode($this->RESPONSE);exit();
    }

    // update student balances
    private function adjustStudentBalances($student_id){

        $monthNumber=$this->std_fee_voucher_m->month_number+6;
        $last_two_years=$this->std_fee_voucher_m->year-2;
        // $ev_filter=array('student_id'=>$student_id,'status <>'=>$this->std_fee_voucher_m->STATUS_PAID,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber);
        $ev_filter=array('student_id'=>$student_id,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber,'year >'=>$last_two_years);
        $vouchers=$this->std_fee_voucher_m->get_rows($ev_filter,array('orderby'=>'month_number ASC'));
        $total_balance=0;
        foreach($vouchers as $vchr){
            $balance=$this->std_fee_entry_m->get_voucher_amount($vchr['voucher_id'],'',$this->CAMPUSID);
            $total_balance+=$balance;                           
            $this->std_fee_voucher_m->save(array('balance'=>$total_balance),$vchr['mid']);
            // if($total_balance>0){}
        }
    }
    // load single row
    public function loadFeeVoucher(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->std_fee_voucher_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid slip...';
            echo json_encode($this->RESPONSE);exit();
        }

        $this->updatePerdayLateFeeFine($rid);
        $this->updateFixedLateFeeFine($rid);
        $row=$this->std_fee_voucher_m->get_by_primary($rid);
        $row->amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_PLUS,$this->CAMPUSID);
        $row->remaining_amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID);
        $row->entries=$this->std_fee_entry_m->get_rows(array('voucher_id'=>$row->voucher_id,'campus_id'=>$this->CAMPUSID));
        // $minus_amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_MINUS,$this->ORGID,$this->CAMPUSID);
        $row->concession=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_MINUS,$this->CAMPUSID,$this->std_fee_entry_m->TYPE_CONCESSION);
        $row->amount_cf=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_MINUS,$this->CAMPUSID,$this->std_fee_entry_m->TYPE_CF);
        $row->amount_bf=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_PLUS,$this->CAMPUSID,$this->std_fee_entry_m->TYPE_BF);
        // $row->concession=round($minus_amount-$row->amount_paid, 2);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    // update row
    public function deleteFee(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        isset($form['type']) ? $type=$form['type'] : $type='';

        switch (strtolower($type)) {
            case 'entry':{
                //check for necessary data   
                if(empty($rid)|| $this->std_fee_entry_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Please choose a valid entry...';
                    echo json_encode($this->RESPONSE);exit();
                }
                $row=$this->std_fee_entry_m->get_by_primary($rid);
                $voucher=$this->std_fee_voucher_m->get_by(array('voucher_id'=>$row->voucher_id,'campus_id'=>$this->CAMPUSID),true);
                if($this->LOGIN_USER->prm_finance<5 && ($voucher->status==$this->std_fee_voucher_m->STATUS_PAID || $voucher->status==$this->std_fee_voucher_m->STATUS_PARTIAL_PAID) ){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Paid record cannot be removed...';
                    echo json_encode($this->RESPONSE);exit();

                }
                if($this->LOGIN_USER->prm_finance<5 && ($row->type==$this->std_fee_entry_m->TYPE_CF || $row->type==$this->std_fee_entry_m->TYPE_BF) ){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='C/F and B/F records cannot be removed...';
                    echo json_encode($this->RESPONSE);exit();

                }
                if($this->std_fee_entry_m->delete($rid)==false){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Entry can not be removed at this time. Please try again later...';
                    echo json_encode($this->RESPONSE);exit();
                }
                $this->accounts_ledger_m->delete($row->ledger_id);
                if($row->operation==$this->std_fee_entry_m->OPT_MINUS){
                    //update voucher status accordingly
                    $total_amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_PLUS,$this->CAMPUSID);        
                    // $minus_amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_MINUS,$this->ORGID,$this->CAMPUSID);
                    if($row->type==$this->std_fee_entry_m->TYPE_FEE){
                        $this->std_fee_voucher_m->update_column_value($voucher->mid,'amount_paid',$row->amount,'minus');
                    }
                    if($this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID) > 0){
                        //update to partial paid
                        $this->std_fee_voucher_m->save(array('status'=>$this->std_fee_voucher_m->STATUS_PARTIAL_PAID),$voucher->mid);
                    }
                    if($this->std_fee_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID)==$total_amount){
                        //update to unpaid
                        $this->std_fee_voucher_m->save(array('status'=>$this->std_fee_voucher_m->STATUS_UNPAID),$voucher->mid);
                    }
                }
                //Log the user Activity
                $msg=$this->LOGIN_USER->name." removed student fee entry(".$row->remarks.", Amount:".$row->amount.") of voucher(".$row->voucher_id.").";
                $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
                $this->adjustStudentBalances($row->student_id);
                //////////////////////////////////////////////////////////////
                $this->RESPONSE['message']='Removed Successfully.';
            }
            break;
            case 'slip':{
                //check for necessary data   
                if(empty($rid)|| $this->std_fee_voucher_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Please choose a valid voucher...';
                    echo json_encode($this->RESPONSE);exit();
                }
                $voucher=$this->std_fee_voucher_m->get_by_primary($rid);
                if(!$this->LOGIN_USER->prm_finance<5 && ($voucher->status==$this->std_fee_voucher_m->STATUS_PAID || $voucher->status==$this->std_fee_voucher_m->STATUS_PARTIAL_PAID) ){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Paid record cannot be removed...';
                    echo json_encode($this->RESPONSE);exit();

                }
                $entries=$this->std_fee_entry_m->get_rows(array('voucher_id'=>$voucher->voucher_id,'campus_id'=>$this->CAMPUSID));
                foreach($entries as $entry){
                    $this->std_fee_entry_m->delete($entry['mid']);
                    $this->accounts_ledger_m->delete($entry['ledger_id']);
                    }
                $this->std_fee_voucher_m->delete($voucher->mid);
                //Log the user Activity
                $msg=$this->LOGIN_USER->name." removed student fee voucher(".$voucher->title.", Amount:".$voucher->amount.").";
                $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
                $this->adjustStudentBalances($voucher->student_id);
                //////////////////////////////////////////////////////////////
                $this->RESPONSE['message']='Removed Successfully.';

            }
            break;
            
            default:{
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid operation...';
                echo json_encode($this->RESPONSE);exit();
            }
            break;
        }
        //send back the resposne  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function deleteFeeVouchers(){
        set_time_limit(9600);
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //////////////////////////////////////////////////////////////////////////
        
        isset($form['month']) && !empty($form['month']) ? $month= $form['month'] : $month=$this->std_fee_voucher_m->month;
        isset($form['year']) && !empty($form['year']) ? $year= $form['year'] : $year= $this->std_fee_voucher_m->year;
        if(empty($month) && empty($year)){
            $monthNumber=$this->std_fee_voucher_m->month_number;
        }else{
            $monthNumber=get_month_number($month,$year);
        }
        //////////////////////////////////////////////////////////////////////////

        $entries=$this->std_fee_entry_m->get_rows(array('month_number'=>$monthNumber,'campus_id'=>$this->CAMPUSID),array('select'=>'mid,ledger_id,month_number'));
        foreach($entries as $entry){
            $this->std_fee_entry_m->delete($entry['mid']);
            $this->accounts_ledger_m->delete($entry['ledger_id']);
        }
        $vouchers=$this->std_fee_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'month_number'=>$monthNumber),array('select'=>'mid,voucher_id,student_id'));
        foreach($vouchers as $voucher){            
            //if there is no entry for this voucher then remove this voucher
            if($this->std_fee_entry_m->get_rows(array('voucher_id'=>$voucher['voucher_id'],'campus_id'=>$this->CAMPUSID),'',true)<1){
                $this->std_fee_voucher_m->delete($voucher['mid']);                
                $this->std_fee_history_m->delete(NULL,array('voucher_id'=>$voucher['mid'],'campus_id'=>$this->CAMPUSID));
            }
            $this->adjustStudentBalances($voucher['student_id']);
        }
        // if(!$this->IS_DEV_LOGIN){            
            //Log the user Activity
            $msg=$this->LOGIN_USER->name." removed fee vouchers for the month of(".month_string($month).", $year)";
            $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        // }

        //send back the resposne  
        $this->RESPONSE['message']=' Removed Successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // create rows
    public function createFeeVouchers(){
        set_time_limit(900);
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $concession_types=$this->concession_type_m->get_values_array('mid','title',array());
        $total_created=0;
        $v_month=$this->student_m->month;
        $v_year=$this->student_m->year;
        $auto_fee_collectin=false;
        $update_existing_voucher=false;
        $apply_feepkgpolicy=false;
        $skipzero=false;
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        /////////////////////////////////////////////////////////////////////////////////////////
        //check for necessary required data   
        $required=array('due_date','target');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select valid target and due date...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        isset($form['month']) && !empty($form['month']) ? $v_month=$form['month'] : $v_month=$this->student_m->month;
        isset($form['year']) && !empty($form['year']) ? $v_year=$form['year'] : $v_year=$this->student_m->year;

        //update balances of existing vouchers  
        // $vchr_class_fltr='';
        // isset($form['class_id']) && intval($form['class_id'])>0 ? $vchr_class_fltr=intval($form['class_id']) : '';      
        // $vchrs_stds=$this->student_m->get_session_students($vchr_class_fltr);
        // foreach($vchrs_stds as $vchr_std){
        //     $monthNumber=get_month_number($v_month,$v_year);
        //     //skip if student already have voucher for this month
        //     $ev_filter=array('student_id'=>$vchr_std['mid'],'status <>'=>$this->std_fee_voucher_m->STATUS_PAID,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber);
        //     if($this->std_fee_voucher_m->get_rows($ev_filter,'',true)>0){
        //         $vchrs=$this->std_fee_voucher_m->get_rows($ev_filter,array('orderby'=>'month_number ASC'));
        //         $balance=0;
        //         foreach($vchrs as $vchr){
        //             $balnc=$this->std_fee_entry_m->get_voucher_amount($vchr['voucher_id'],'',$this->ORGID,$this->CAMPUSID);
        //             $balance+=$balnc;
        //         }                
        //         if($balance>0){
        //             $this->std_fee_voucher_m->save(array('balance'=>$balance),$vchr['mid']);
        //         }
        //     }
        // }
        /////////////////////////////////////////////////////////////
        if($this->IS_COLLEGE && $this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE]==$this->campus_setting_m->TYPE_FIXED){
            if(strtolower($form['target'])=='fixed'){
                //create one voucher for the session
                $monthstamp=array('month'=>$v_month,'year'=>$v_year);
                $monthNumber=get_month_number($v_month,$v_year);
                /////////////////////////////////////////FEE SLIP CREATION FOR MONTHLY STUDENTS/////////////////////
                $class_fltr='';
                isset($form['class_id']) && intval($form['class_id'])>0 ? $class_fltr=intval($form['class_id']) : '';
                isset($form['feepkgpolicy']) && intval($form['feepkgpolicy'])>0 ? $apply_feepkgpolicy=true : '';
                isset($form['skipzero']) && $form['skipzero']=='yes' ? $skipzero=true : $skipzero=false;
                isset($form['auto_collection']) && $form['auto_collection']=='receive' ? $auto_fee_collectin=true : '';
                isset($form['update_voucher']) && $form['update_voucher']=='existing' ? $update_existing_voucher=true : '';
                $activeSession=$this->session_m->getActiveSession();
                $filter['session_id']=$activeSession->mid;
                $filter['status']=$this->student_m->STATUS_ACTIVE;
                //get all students of active session.
                $students=$this->student_m->get_session_students($this->CAMPUSID,$class_fltr);
                foreach($students as $student){
                    //skip if student already have voucher for this month
                    if($this->std_fee_voucher_m->get_rows(array('student_id'=>$student['mid'],'session_id'=>$activeSession->mid,'type'=>$this->std_fee_voucher_m->TYPE_FEE,'campus_id'=>$this->CAMPUSID),'',true)<1 && $student['fee_type']==$this->student_m->FEE_TYPE_FIXED){
                        $blank_rows=array();
                        $voucher_total_amount=0;


                        $voucher_id=$this->std_fee_voucher_m->get_new_voucher_id();
                        $voucher=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'std_id'=>$student['student_id'],'student_name'=>$student['name'],'roll_no'=>$student['roll_no'],'class_id'=>$student['class_id'],'month_number'=>$monthNumber,'month'=>$v_month,'year'=>$v_year,'session_id'=>$activeSession->mid,'status'=>$this->std_fee_voucher_m->STATUS_UNPAID,'voucher_id'=>$voucher_id,'due_date'=>$form['due_date'],'title'=>'Term Fee - '.$activeSession->title);

                        $has_existing_voucher=false;
                        ////////////////////////////////////////////////////////////////////////////////////////////////
                                            
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher_id,'student_id'=>$student['mid'],'month_number'=>$monthNumber,'month'=>$v_month,'year'=>$v_year);

                        ////ADD STUDENT FEE TO VOUCHER
                        //PROCESS ONLY MONTHLY FEE (FIXED FEE VOUCHERS WILL BE CREATED BY ORGANIZAITON MANUALLY)
                        if($student['fee_type']==$this->student_m->FEE_TYPE_FIXED){

                            $title_student_fee=" Fee Package ";
                            $student_fee=$student['fee'];
                            ////////////////////////////////apply fee package policy//////////////////////////////////
                            if($this->IS_COLLEGE && $this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE]!=$this->student_m->FEE_TYPE_MONTHLY && $student['pkg_total_marks']>0 && $student['pkg_obt_marks']>0){
                                //create voucher$student_fee=$form['fee'];
                                // $pkg_percentage=floor(($student['pkg_obt_marks'] /$student['pkg_total_marks'])*100);
                                $pkg_percentage=intval($student['pkg_obt_marks']);

                                if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$pkg_percentage,'obt_max_percent <='=>$pkg_percentage,'class_id'=>$student['class_id']),'',true)>0){
                                    $package_policy=$this->class_feepackage_m->get_by(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$pkg_percentage,'obt_max_percent <='=>$pkg_percentage,'class_id'=>$student['class_id']),true);
                                    $student_fee=$package_policy->amount;        
                                    $title_student_fee.=' PKG@'.$package_policy->name;        
                                }elseif($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$pkg_percentage,'obt_min_percent <='=>$pkg_percentage,'class_id'=>$student['class_id']),'',true)>0){
                                    $package_policy=$this->class_feepackage_m->get_by(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$pkg_percentage,'obt_min_percent <='=>$pkg_percentage,'class_id'=>$student['class_id']),true);
                                    $student_fee=$package_policy->amount;        
                                    $title_student_fee.=' PKG@'.$package_policy->name;   
                                }
                            }

                            $voucher_total_amount+=$student_fee;
                            $entry['remarks']=$title_student_fee;
                            $entry['amount']=$student_fee;
                            $entry['type']=$this->std_fee_entry_m->TYPE_FEE;
                            //////////////////////////////////////////////////////////////////////////////
                            // if($student['fee']>0 || $skipzero){                        
                            if($voucher_total_amount<1 && $skipzero){continue;}
                            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' term fee',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$student['fee'],true,"Term Fee of ".$activeSession->title,"Term Fee of ".$activeSession->title,'','',$monthstamp );
                            $this->std_fee_entry_m->add_row($entry);
                        }
                        if($voucher_total_amount<1 && $skipzero){continue;}  
                        //ADD MONTH BLANK ENTRIES TO VOUCHER
                        $concessions=$this->std_fee_concession_m->get_rows(array('student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID));
                        if(count($concessions)>0){
                            foreach($concessions as $row){
                                $total_camount=0;                        
                                if($row['type']==$this->std_fee_concession_m->TYPE_FIXED){$total_camount=$row['amount'];}
                                if($row['type']==$this->std_fee_concession_m->TYPE_PERCENTAGE){$total_camount=(($student['fee']*$row['amount'])/100);}

                                $voucher_total_amount-=$total_camount;
                                $entry['remarks']=$concession_types[$row['type_id']];
                                $entry['concession_type']=$row['type_id'];
                                $entry['amount']=$total_camount;
                                $entry['type']=$this->std_fee_entry_m->TYPE_CONCESSION;
                                $entry['operation']=$this->std_fee_entry_m->OPT_MINUS;
                                $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' fee concessions',$this->accounts_m->_CONCESSION,$this->accounts_m->_FEE_RECEIVABLE,$total_camount,true,"Fee Concession","Fee Concession",'','',$monthstamp );
                                $this->std_fee_entry_m->add_row($entry);

                            }
                        }
                        $vid=$this->std_fee_voucher_m->add_row($voucher);                   
                        $total_created++;   //student have some amount in voucher.
                        //////////////////////////////////////////////////////////////////////
                        ////////////////CHECK IF AUTO FEE COLLECTION IS ENABLED///////////////
                        //////////////////////////////////////////////////////////////////////
                        if($auto_fee_collectin && $student['advance_amount']>=$voucher_total_amount && $voucher_total_amount>0){   
                            $fee_amount=$voucher_total_amount;  
                            $ldgr_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Auto Fee received from '.$student['name'].' advance deposited amount',$this->accounts_m->_FEE_ADVANCE,$this->accounts_m->_STUDENT_FEE,$fee_amount,true);
                            ////////////////////////////////////////////
                            $rx_entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'amount'=>$fee_amount,'remarks'=>'Auto Fee received from advance fee','operation'=>$this->std_fee_entry_m->OPT_MINUS,'ledger_id'=>$ldgr_id);
                            if($this->std_fee_entry_m->add_row($rx_entry)!=false){        
                                ////////////////////////////////////////////////////////////////////////////////////////
                                $this->student_m->update_column_value($student['mid'],'advance_amount',$fee_amount,'minus');
                                $this->std_fee_voucher_m->update_column_value($vid,'amount_paid',$fee_amount);
                                $data=array('date_paid'=>$this->std_fee_voucher_m->date,'status'=>$this->std_fee_voucher_m->STATUS_PAID);
                                $this->std_fee_voucher_m->save($data,$vid);
                                //add to student fee history
                                $this->std_fee_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'title'=>'Fee paid from advance deposited amount','amount'=>$fee_amount,'voucher_id'=>$vid,'month'=>$v_month,'year'=>$v_year,'month_number'=>$monthNumber));
                                //ADD TO ORG INCOME
                                $title="Fee Of ".$student['name']."(".$student['student_id'].")";
                                $income=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>'Fee from advance deposited amount','amount'=>$fee_amount,'month'=>$v_month,'year'=>$v_year,'ledger_id'=>$ldgr_id,'type'=>$this->accounts_m->_STUDENT_FEE);
                                $income['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STUDENT_FEE,'',true);
                                $this->income_m->add_row($income);
                                ///////////////////////////////////////////////////////////////////////////////////////
                                ////////////////////////////CHECK IF THERE IS ANY SMS HOOK/////////////////////////////
                                ///////////////////////////////////////////////////////////////////////////////////////
                                $filter=array('campus_id'=>$this->CAMPUSID);
                                $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_PAY_FEE;
                                $hooks=$this->sms_hook_m->get_rows($filter);
                                if(count($hooks)>0){
                                    // $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
                                    // $row=$this->student_m->get_by_primary($attendance->student_id);
                                    foreach ($hooks as $hook){
                                        //send sms to student
                                        if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                            $message=htmlspecialchars_decode($hook["template"]);
                                            //conversion keys
                                            $key_vars=array(
                                                    '{NAME}'=>$student['name'],
                                                    '{FEE_PAID}'=>$fee_amount,
                                                    '{BALANCE}'=>0
                                                );
                                            ////////////////////////////////////////
                                            $sms=strtr($message, $key_vars);
                                            $this->sms_history_m->send_sms($this->CAMPUSID,$student['mobile'],$sms);
                                        }
                                        //send sms to guardian
                                        if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                            $message=htmlspecialchars_decode($hook["template"]);
                                            //conversion keys
                                            $key_vars=array(
                                                    '{NAME}'=>$student['name'],
                                                    '{GUARDIAN}'=>$student['guardian_name'],
                                                    '{FEE_PAID}'=>$fee_amount,
                                                    '{BALANCE}'=>0
                                                );
                                            ////////////////////////////////////////
                                            $sms=strtr($message, $key_vars);
                                            $this->sms_history_m->send_sms($this->CAMPUSID,$student['guardian_mobile'],$sms);
                                        }
                                    }
                                }
                            }
                        }                    
                        //adjust student balances
                        $this->adjustStudentBalances($student['mid']);
                    }
                }
            }else{
                //proceed vouchers to next month                
                $monthstamp=array('month'=>$v_month,'year'=>$v_year);
                $monthNumber=get_month_number($v_month,$v_year);
                /////////////////////////////////////////FEE SLIP CREATION FOR MONTHLY STUDENTS/////////////////////
                $class_fltr='';
                isset($form['class_id']) && intval($form['class_id'])>0?$class_fltr=intval($form['class_id']) : '';
                isset($form['skipzero']) && $form['skipzero']=='yes' ?$skipzero=true:$skipzero=false;
                isset($form['auto_collection']) && $form['auto_collection']=='receive' ? $auto_fee_collectin=true : '';
                $activeSession=$this->session_m->getActiveSession();
                $filter['session_id']=$activeSession->mid;
                $filter['status']=$this->student_m->STATUS_ACTIVE;
                //get all students of active session.
                $students=$this->student_m->get_session_students($this->CAMPUSID,$class_fltr);
                foreach($students as $student){
                    //skip if student already have voucher for this month
                    if($this->std_fee_voucher_m->get_rows(array('student_id'=>$student['mid'],'month_number'=>$monthNumber,'type'=>$this->std_fee_voucher_m->TYPE_FEE,'campus_id'=>$this->CAMPUSID),'',true)<1 && $student['fee_type']==$this->student_m->FEE_TYPE_FIXED){
                        $blank_rows=array();
                        $voucher_total_amount=0;
                        $std_previous_balance=$this->std_fee_entry_m->get_student_balance($student['mid'],$this->CAMPUSID);
                        if($std_previous_balance<1){$std_previous_balance=0;}                        


                        $voucher_id=$this->std_fee_voucher_m->get_new_voucher_id();
                        $voucher=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'std_id'=>$student['student_id'],'student_name'=>$student['name'],'roll_no'=>$student['roll_no'],'class_id'=>$student['class_id'],'month_number'=>$monthNumber,'month'=>$v_month,'year'=>$v_year,'session_id'=>$activeSession->mid,'status'=>$this->std_fee_voucher_m->STATUS_UNPAID,'voucher_id'=>$voucher_id,'due_date'=>$form['due_date'],'title'=>'Voucher of - '.month_string($v_month).', '.$v_year);

                        $has_existing_voucher=false;
                        $existing_voucher_filter=array('student_id'=>$student['mid'],'status <>'=>$this->std_fee_voucher_m->STATUS_PAID,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber);
                        if($this->std_fee_voucher_m->get_rows($existing_voucher_filter,'',true)>0){
                            $has_existing_voucher=true;
                        }
                        //////////////////////////////////////////////////////////////////////////
                        //ADD MONTH BLANK ENTRIES TO VOUCHER
                        $blank_rows=$this->std_fee_entry_m->get_rows(array('voucher_id'=>'','student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID));
                        foreach($blank_rows as $row){
                            if(strlen($row['voucher_id'])<1){
                                $voucher_total_amount+=$row['amount'];
                                $updated_data=array('voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'month'=>$form['month'],'year'=>$form['year']);
                                switch ($row['type']){
                                    case 'fund':{
                                        $ldger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' class record',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FUNDS,$row['amount'],true,$row['remarks'],$row['remarks'] );
                                    }
                                    break;
                                    case 'fine':{
                                        $ldger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' class record',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FINE,$row['amount'],true,$row['remarks'],$row['remarks'] );
                                    }
                                    break;                        
                                    default:{
                                        //default is fee
                                        $ldger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' class record ',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$row['amount'],true,$row['remarks'],$row['remarks'] );
                                    }break;
                                }
                                $updated_data['ledger_id']=$ldger_id;
                                $this->std_fee_entry_m->save($updated_data,$row['mid']);
                            }
                        }
                        
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher_id,'student_id'=>$student['mid'],'month_number'=>$monthNumber,'month'=>$v_month,'year'=>$v_year);

                        //ADD STUDENT TRANSPORT FARE TO VOUCHER
                        if($student['transport_fee']>0){
                            $voucher_total_amount+=$student['transport_fee'];
                            $entry['remarks']='Transport Fare';
                            $entry['amount']=$student['transport_fee'];
                            $entry['type']=$this->std_fee_entry_m->TYPE_TRANSPORT;
                            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' transport fee',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FEE_TRANSPORT,$student['transport_fee'],true,$student['name']." transport charges of ".month_string($v_month)." ".$v_year,$student['name']." transport charges of ".month_string($v_month)." ".$v_year,'','',$monthstamp );
                            $this->std_fee_entry_m->add_row($entry);
                        }
                        //ADD PREVIOUS BALANCE TO VOUCHER                     
                        if($has_existing_voucher){                                                 
                            $existing_voucher_filter=array('student_id'=>$student['mid'],'status <>'=>$this->std_fee_voucher_m->STATUS_PAID,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber);
                            $prev_vouchers=$this->std_fee_voucher_m->get_rows($existing_voucher_filter);
                            if(count($prev_vouchers)>0){
                                foreach($prev_vouchers as $v){
                                    $v_balance=$this->std_fee_entry_m->get_voucher_amount($v['voucher_id'],'',$this->CAMPUSID);
                                    $pcf_entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$v['student_id'],'voucher_id'=>$v['voucher_id'],'month_number'=>$v['month_number'],'amount'=>$v_balance,'remarks'=>'Proceeded To Voucher('.$voucher_id.')','operation'=>$this->std_fee_entry_m->OPT_MINUS,'ledger_id'=>-1,'type'=>$this->std_fee_entry_m->TYPE_CF);
                                    if($this->std_fee_entry_m->add_row($pcf_entry)){
                                        $voucher_total_amount+=$v_balance;  
                                        $ncf_entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'amount'=>$v_balance,'remarks'=>'Remaining Fee','operation'=>$this->std_fee_entry_m->OPT_PLUS,'ledger_id'=>-1,'type'=>$this->std_fee_entry_m->TYPE_BF);
                                        $this->std_fee_entry_m->add_row($ncf_entry);
                                        $this->std_fee_voucher_m->save(array('status'=>$this->std_fee_voucher_m->STATUS_PAID,'date_paid'=>$this->std_fee_voucher_m->date),$v['mid']);
                                        //update voucher paid amount (if necessary)
                                    }
                                }
                            }
                        }                        
                        if($voucher_total_amount<1 && $skipzero){continue;}  
                        $vid=$this->std_fee_voucher_m->add_row($voucher);
                        $total_created++;
                        ///////////////////////////////////////////////////////
                        ///////////CHECK IF AUTO FEE COLLECTION IS ENABLED/////
                        ///////////////////////////////////////////////////////
                        if($auto_fee_collectin && $student['advance_amount']>=$voucher_total_amount && $voucher_total_amount>0){   
                            $fee_amount=$voucher_total_amount;             
                            //create ledger entry for this operation
                            //add_entry($narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='')
                            // $this->accounts_ledger_m->add_entry('Auto Fee received from advance amount',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FEE_RECEIVED,$fee_amount,true);
                            $ldgr_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Auto Fee received from '.$student['name'].' advance deposited amount',$this->accounts_m->_FEE_ADVANCE,$this->accounts_m->_STUDENT_FEE,$fee_amount,true);
                            ////////////////////////////////////////////
                            $rx_entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'amount'=>$fee_amount,'remarks'=>'Auto Fee received from advance fee','operation'=>$this->std_fee_entry_m->OPT_MINUS,'ledger_id'=>$ldgr_id);
                            if($this->std_fee_entry_m->add_row($rx_entry)!=false){        
                                ////////////////////////////////////////////////////////////////////////////////////////
                                $this->student_m->update_column_value($student['mid'],'advance_amount',$fee_amount,'minus');
                                $this->std_fee_voucher_m->update_column_value($vid,'amount_paid',$fee_amount);
                                $data=array('date_paid'=>$this->std_fee_voucher_m->date,'status'=>$this->std_fee_voucher_m->STATUS_PAID);
                                $this->std_fee_voucher_m->save($data,$vid);
                                //add to student fee history
                                $this->std_fee_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'title'=>'Fee paid from advance deposited amount','amount'=>$fee_amount,'voucher_id'=>$vid,'month'=>$form['month'],'year'=>$form['year'],'month_number'=>$monthNumber));
                                //ADD TO ORG INCOME
                                $title="Fee Of ".$student['name']."(".$student['student_id'].")";
                                $income=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>'Fee from advance deposited amount','amount'=>$fee_amount,'month'=>$form['month'],'year'=>$form['year'],'ledger_id'=>$ldgr_id,'type'=>$this->accounts_m->_STUDENT_FEE);
                                $income['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STUDENT_FEE,'',true);
                                $this->income_m->add_row($income);
                                ////////////////////////////////////////////////////////////////////
                                /////////////CHECK IF THERE IS ANY SMS HOOK/////////////////////////
                                ////////////////////////////////////////////////////////////////////
                                $filter=array('campus_id'=>$this->CAMPUSID);
                                $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_PAY_FEE;
                                $hooks=$this->sms_hook_m->get_rows($filter);
                                if(count($hooks)>0){
                                    // $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
                                    // $row=$this->student_m->get_by_primary($attendance->student_id);
                                    foreach ($hooks as $hook){
                                        //send sms to student
                                        if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                            $message=htmlspecialchars_decode($hook["template"]);
                                            //conversion keys
                                            $key_vars=array(
                                                    '{NAME}'=>$student['name'],
                                                    '{FEE_PAID}'=>$fee_amount,
                                                    '{BALANCE}'=>0
                                                );
                                            ////////////////////////////////////////
                                            $sms=strtr($message, $key_vars);
                                            $this->sms_history_m->send_sms($this->CAMPUSID,$student['mobile'],$sms);
                                        }
                                        //send sms to guardian
                                        if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                            $message=htmlspecialchars_decode($hook["template"]);
                                            //conversion keys
                                            $key_vars=array(
                                                    '{NAME}'=>$student['name'],
                                                    '{GUARDIAN}'=>$student['guardian_name'],
                                                    '{FEE_PAID}'=>$fee_amount,
                                                    '{BALANCE}'=>0
                                                );
                                            ////////////////////////////////////////
                                            $sms=strtr($message, $key_vars);
                                            $this->sms_history_m->send_sms($this->CAMPUSID,$student['guardian_mobile'],$sms);
                                        }
                                    }
                                }
                            }
                        }                
                        //adjust student balances
                        $this->adjustStudentBalances($student['mid']);
                    }
                }
            }
        }else{

            if(empty($form['month'])||empty($form['month'])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please select valid month and year.';
                echo json_encode($this->RESPONSE);exit();
            }    

            $due_date_jd=get_jd_from_date($form['due_date'],"-",true);
            $due_date_month_max_jd=get_jd_from_date('30-'.month_string($form['month']).'-'.$form['year'],"-",true);

            if($due_date_jd < ($this->std_fee_voucher_m->todayjd-90)){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please select valid due date from within 3 months range.';
                echo json_encode($this->RESPONSE);exit();

            }     
            $monthstamp=array('month'=>$v_month,'year'=>$v_year);
            $monthNumber=get_month_number($v_month,$v_year);
            if($monthNumber < $this->std_fee_voucher_m->month_number-1 || $monthNumber > $this->std_fee_voucher_m->month_number+5 ){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Only previous, current and next month vouchers can be process at this time...';
                echo json_encode($this->RESPONSE);exit();

            }  
            // if($this->std_fee_voucher_m->get_rows(array('month_number'=>$monthNumber,'type'=>$this->std_fee_voucher_m->TYPE_FEE,'campus_id'=>$this->CAMPUSID),'',true)>0){
            //     $this->RESPONSE['error']=TRUE;
            //     $this->RESPONSE['message']='Fee slips for this month has already been created. Please use search feature to list out slips...';
            //     echo json_encode($this->RESPONSE);exit();

            // }  


            //process students monthly vouchers
            if($due_date_jd >$due_date_month_max_jd){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please select the due date upto last date of selected month...';
                echo json_encode($this->RESPONSE);exit();
            }  
            /////////////////////////////////////////FEE SLIP CREATION FOR MONTHLY STUDENTS/////////////////////
            $class_fltr='';
            isset($form['class_id']) && intval($form['class_id'])>0 ? $class_fltr=intval($form['class_id']) : '';
            isset($form['skipzero']) && $form['skipzero']=='yes' ? $skipzero=true : $skipzero=false;
            isset($form['auto_collection']) && $form['auto_collection']=='receive' ? $auto_fee_collectin=true : '';
            isset($form['update_voucher']) && $form['update_voucher']=='existing' ? $update_existing_voucher=true : '';
            $activeSession=$this->session_m->getActiveSession();
            $filter['session_id']=$activeSession->mid;
            $filter['status']=$this->student_m->STATUS_ACTIVE;
            //get all students of active session.
            $students=$this->student_m->get_session_students($this->CAMPUSID,$class_fltr);
            foreach($students as $student){
                //skip if student already have voucher for this month
                if($this->std_fee_voucher_m->get_rows(array('student_id'=>$student['mid'],'month_number'=>$monthNumber,'type'=>$this->std_fee_voucher_m->TYPE_FEE,'campus_id'=>$this->CAMPUSID),'',true)<1 && $student['fee_type']==$this->student_m->FEE_TYPE_MONTHLY){
                    $blank_rows=array();
                    $voucher_total_amount=0;

                    $voucher_id=$this->std_fee_voucher_m->get_new_voucher_id();
                    $voucher=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'std_id'=>$student['student_id'],'student_name'=>$student['name'],'roll_no'=>$student['roll_no'],'class_id'=>$student['class_id'],'month_number'=>$monthNumber,'month'=>$v_month,'year'=>$v_year,'session_id'=>$activeSession->mid,'status'=>$this->std_fee_voucher_m->STATUS_UNPAID,'voucher_id'=>$voucher_id,'due_date'=>$form['due_date'],'title'=>'Monthly Fee - '.month_string($v_month).', '.$v_year);

                    $has_existing_voucher=false;
                    $existing_voucher_filter=array('student_id'=>$student['mid'],'status <>'=>$this->std_fee_voucher_m->STATUS_PAID,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber);
                    if($update_existing_voucher && $this->std_fee_voucher_m->get_rows($existing_voucher_filter,'',true)>0){
                        $has_existing_voucher=true;
                        // $voucher=$this->std_fee_voucher_m->get_by($existing_voucher_filter,true);
                        // $voucher_id=$voucher->voucher_id;
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////////////////
                    //ADD MONTH BLANK ENTRIES TO VOUCHER
                    $blank_rows=$this->std_fee_entry_m->get_rows(array('voucher_id'=>'','student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID));
                    foreach($blank_rows as $row){
                        if(strlen($row['voucher_id'])<1){
                            $voucher_total_amount+=$row['amount'];
                            $updated_data=array('voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'month'=>$form['month'],'year'=>$form['year']);
                            switch ($row['type']){
                                case 'fund':{
                                    $ldger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' class record',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FUNDS,$row['amount'],true,$row['remarks'],$row['remarks'] );
                                }
                                break;
                                case 'fine':{
                                    $ldger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' class record',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FINE,$row['amount'],true,$row['remarks'],$row['remarks'] );
                                }
                                break;                        
                                default:{
                                    //default is fee
                                    $ldger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' class record ',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$row['amount'],true,$row['remarks'],$row['remarks'] );
                                }break;
                            }
                            $updated_data['ledger_id']=$ldger_id;
                            $this->std_fee_entry_m->save($updated_data,$row['mid']);
                        }
                    }
                    
                    $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher_id,'student_id'=>$student['mid'],'month_number'=>$monthNumber,'month'=>$v_month,'year'=>$v_year);

                    //ADD STUDENT TRANSPORT FARE TO VOUCHER
                    if($student['transport_fee']>0){
                        $voucher_total_amount+=$student['transport_fee'];
                        $entry['remarks']='Transport Fare';
                        $entry['amount']=$student['transport_fee'];
                        $entry['type']=$this->std_fee_entry_m->TYPE_TRANSPORT;
                        $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' transport fee',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FEE_TRANSPORT,$student['transport_fee'],true,$student['name']." transport charges of ".month_string($v_month)." ".$v_year,$student['name']." transport charges of ".month_string($v_month)." ".$v_year,'','',$monthstamp );
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    ////ADD STUDENT FEE TO VOUCHER
                    //PROCESS ONLY MONTHLY FEE
                    if($student['fee_type']==$this->student_m->FEE_TYPE_MONTHLY){
                        $voucher_total_amount+=$student['fee'];
                        $entry['remarks']='Monthly Fee - '.month_string($v_month).','.$v_year;
                        $entry['amount']=$student['fee'];
                        $entry['type']=$this->std_fee_entry_m->TYPE_FEE;
                        // if($student['fee']>0 || $skipzero){                        
                        if($voucher_total_amount<1 && $skipzero){continue;}
                        $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' monthly fee',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$student['fee'],true,"Fee of ".month_string($v_month)." ".$v_year,"Fee of ".month_string($v_month)." ".$v_year,'','',$monthstamp );
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    if($voucher_total_amount<1 && $skipzero){continue;}                     
                    $total_created++;   //student have some amount in voucher.
                    //ADD MONTH BLANK ENTRIES TO VOUCHER
                    $concessions=$this->std_fee_concession_m->get_rows(array('student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID));
                    if(count($concessions)>0){
                        foreach($concessions as $row){
                            $total_camount=0;                        
                            if($row['type']==$this->std_fee_concession_m->TYPE_FIXED){$total_camount=$row['amount'];}
                            if($row['type']==$this->std_fee_concession_m->TYPE_PERCENTAGE){$total_camount=(($student['fee']*$row['amount'])/100);}

                            $voucher_total_amount-=$total_camount;
                            $entry['remarks']=$concession_types[$row['type_id']];
                            $entry['concession_type']=$row['type_id'];
                            $entry['amount']=$total_camount;
                            $entry['type']=$this->std_fee_entry_m->TYPE_CONCESSION;
                            $entry['operation']=$this->std_fee_entry_m->OPT_MINUS;
                            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$student['name'].' fee concessions',$this->accounts_m->_CONCESSION,$this->accounts_m->_FEE_RECEIVABLE,$total_camount,true,"Fee Concession","Fee Concession",'','',$monthstamp );
                            $this->std_fee_entry_m->add_row($entry);

                        }
                    }
                    // if($voucher_total_amount>0){
                        $vid=$this->std_fee_voucher_m->add_row($voucher);
                        //user opted to carry forward outstanding balance to new voucher.

                        //ADD PREVIOUS BALANCE TO VOUCHER                     
                        if($has_existing_voucher){                                                 
                            $existing_voucher_filter=array('student_id'=>$student['mid'],'status <>'=>$this->std_fee_voucher_m->STATUS_PAID,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber);
                            $prev_vouchers=$this->std_fee_voucher_m->get_rows($existing_voucher_filter);
                            if(count($prev_vouchers)>0){
                                foreach($prev_vouchers as $v){
                                    $v_balance=$this->std_fee_entry_m->get_voucher_amount($v['voucher_id'],'',$this->CAMPUSID);
                                    $pcf_entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$v['student_id'],'voucher_id'=>$v['voucher_id'],'month_number'=>$v['month_number'],'amount'=>$v_balance,'remarks'=>'Proceeded To Voucher('.$voucher_id.')','operation'=>$this->std_fee_entry_m->OPT_MINUS,'ledger_id'=>-1,'type'=>$this->std_fee_entry_m->TYPE_CF);
                                    if($this->std_fee_entry_m->add_row($pcf_entry)){
                                        $voucher_total_amount+=$v_balance;  
                                        $ncf_entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'amount'=>$v_balance,'remarks'=>'Previous Balance','operation'=>$this->std_fee_entry_m->OPT_PLUS,'ledger_id'=>-1,'type'=>$this->std_fee_entry_m->TYPE_BF);
                                        $this->std_fee_entry_m->add_row($ncf_entry);
                                        $this->std_fee_voucher_m->save(array('status'=>$this->std_fee_voucher_m->STATUS_PAID,'date_paid'=>$this->std_fee_voucher_m->date),$v['mid']);
                                        //update voucher paid amount (if necessary)
                                    }
                                }
                            }
                        }
                    // }
                    ///////////////////////////////////////////////////////
                    ///////////CHECK IF AUTO FEE COLLECTION IS ENABLED/////
                    ///////////////////////////////////////////////////////
                    if($auto_fee_collectin && $student['advance_amount']>=$voucher_total_amount && $voucher_total_amount>0){   
                        $fee_amount=$voucher_total_amount;             
                        //create ledger entry for this operation
                        //add_entry($narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='')
                        // $this->accounts_ledger_m->add_entry('Auto Fee received from advance amount',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FEE_RECEIVED,$fee_amount,true);
                        $ldgr_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Auto Fee received from '.$student['name'].' advance deposited amount',$this->accounts_m->_FEE_ADVANCE,$this->accounts_m->_STUDENT_FEE,$fee_amount,true);
                        ////////////////////////////////////////////
                        $rx_entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'amount'=>$fee_amount,'remarks'=>'Auto Fee received from advance fee','operation'=>$this->std_fee_entry_m->OPT_MINUS,'ledger_id'=>$ldgr_id);
                        if($this->std_fee_entry_m->add_row($rx_entry)!=false){        
                            ////////////////////////////////////////////////////////////////////////////////////////
                            $this->student_m->update_column_value($student['mid'],'advance_amount',$fee_amount,'minus');
                            $this->std_fee_voucher_m->update_column_value($vid,'amount_paid',$fee_amount);
                            $data=array('date_paid'=>$this->std_fee_voucher_m->date,'status'=>$this->std_fee_voucher_m->STATUS_PAID);
                            $this->std_fee_voucher_m->save($data,$vid);
                            //add to student fee history
                            $this->std_fee_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'title'=>'Fee paid from advance deposited amount','amount'=>$fee_amount,'voucher_id'=>$vid,'month'=>$form['month'],'year'=>$form['year'],'month_number'=>$monthNumber));
                            //ADD TO ORG INCOME
                            $title="Fee Of ".$student['name']."(".$student['student_id'].")";
                            $income=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>'Fee from advance deposited amount','amount'=>$fee_amount,'month'=>$form['month'],'year'=>$form['year'],'ledger_id'=>$ldgr_id,'type'=>$this->accounts_m->_STUDENT_FEE);
                            $income['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STUDENT_FEE,'',true);
                            $this->income_m->add_row($income);
                            ////////////////////////////////////////////////////////////////////
                            /////////////CHECK IF THERE IS ANY SMS HOOK/////////////////////////
                            ////////////////////////////////////////////////////////////////////
                            $filter=array('campus_id'=>$this->CAMPUSID);
                            $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_PAY_FEE;
                            $hooks=$this->sms_hook_m->get_rows($filter);
                            if(count($hooks)>0){
                                // $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
                                // $row=$this->student_m->get_by_primary($attendance->student_id);
                                foreach ($hooks as $hook){
                                    //send sms to student
                                    if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                        $message=htmlspecialchars_decode($hook["template"]);
                                        //conversion keys
                                        $key_vars=array(
                                                '{NAME}'=>$student['name'],
                                                '{FEE_PAID}'=>$fee_amount,
                                                '{BALANCE}'=>0
                                            );
                                        ////////////////////////////////////////
                                        $sms=strtr($message, $key_vars);
                                        $this->sms_history_m->send_sms($this->CAMPUSID,$student['mobile'],$sms);
                                    }
                                    //send sms to guardian
                                    if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                        $message=htmlspecialchars_decode($hook["template"]);
                                        //conversion keys
                                        $key_vars=array(
                                                '{NAME}'=>$student['name'],
                                                '{GUARDIAN}'=>$student['guardian_name'],
                                                '{FEE_PAID}'=>$fee_amount,
                                                '{BALANCE}'=>0
                                            );
                                        ////////////////////////////////////////
                                        $sms=strtr($message, $key_vars);
                                        $this->sms_history_m->send_sms($this->CAMPUSID,$student['guardian_mobile'],$sms);
                                    }
                                }
                            }
                        }
                    }
                    //adjust student balances
                    $this->adjustStudentBalances($student['mid']);
                }
            }
        }

        //Log the user Activity
        $filter=array('campus_id'=>$this->CAMPUSID);
        $filter['session_id']=$activeSession->mid;
        $total_session_students=$this->student_m->get_rows($filter,'',true);
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $active_session_students=$this->student_m->get_rows($filter,'',true);
        unset($filter['status']);
        $filter['status <>']=$this->student_m->STATUS_ACTIVE;
        $notactive_session_students=$this->student_m->get_rows($filter,'',true);
        $msg=$this->LOGIN_USER->name." created students fee slips for (".month_string($v_month).",".$v_year."). Students Log{Total Enrolled: $total_session_students, Active Students: $active_session_students, Not Active Students: $notactive_session_students, Create Mode: ";
        $skipzero==true ? $msg.=' skip free students': $msg.=' create for all';
        $msg.=", Auto Fee Collection: ";
        $auto_fee_collectin==true ? $msg.=' enabled ': $msg.=' disabled ';
        $msg.=", Voucher Mode: ";
        $update_existing_voucher==true ? $msg.=' transfer balance/arrears ': $msg.=' only create New Vouchers ';
        $msg.=", Vouchers Created For: $total_created students";
        $msg.=", Vouchers Target: ".$form['target'];
        $msg.="}";
        if($total_created>0){            
            $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']='Fee slips created for '.$total_created.' students successfully.';
        echo json_encode( $this->RESPONSE);
        
    }
    // update rows
    public function mergeFeeVouchers(){
        set_time_limit(900);
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //////////////////////////////////////////////////////////////////
        $students=$this->student_m->get_rows($filter);
        foreach($students as $student){
            if($this->std_fee_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'status <>'=>$this->std_fee_voucher_m->STATUS_PAID),'',true)>1){
                //student have multiple pending vouchers.
                $vouchers=$this->std_fee_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'student_id'=>$student['mid'],'status <>'=>$this->std_fee_voucher_m->STATUS_PAID),array('orderby'=>'status ASC, month_number DESC'));
                $master_voucher_id=$vouchers[0]['voucher_id'];

                foreach ($vouchers as $voucher) {
                    $entries=$this->std_fee_entry_m->get_rows(array('voucher_id'=>$voucher['voucher_id'],'student_id'=>$student['mid'],'campus_id'=>$this->CAMPUSID));
                    foreach($entries as $entry){
                        if($entry['voucher_id'] != $master_voucher_id){
                            $this->std_fee_entry_m->save(array('voucher_id'=>$master_voucher_id),$entry['mid']);
                        }
                    }
                    //check for voucher removal after conversion
                    if($voucher['voucher_id']!=$master_voucher_id){
                        if($this->std_fee_entry_m->get_rows(array('voucher_id'=>$voucher['voucher_id'],'campus_id'=>$this->CAMPUSID),'',true)<1 && $this->std_fee_entry_m->get_voucher_amount($voucher['voucher_id'],'',$this->CAMPUSID)==0){
                            //remove voucher
                            $this->std_fee_voucher_m->delete($voucher['mid']);
                        }
                    }
                }
                //adjust voucher balances
                $this->adjustStudentBalances($student['mid']);
            }
        }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']='Fee vouchers merged for students.';
        echo json_encode( $this->RESPONSE);
        
    }
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    // send sms to all filtered staff
    public function sendListSms(){
            $this->load->library('smspoint');
            // get input fields into array
            $filter=array('campus_id'=>$this->CAMPUSID);
            $params=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary fields   
            $required=array('target','message');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }    
            //check if sms api setting are enabled
            if(!$this->smspoint->is_sms_enable()){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='SMS sending option is not active. Please enable it in SMS API Settings menu...';
                echo json_encode($this->RESPONSE);exit();
            }
            ///////////////////////////////////////////////////////////////////////////////////////////
            isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
            isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
            isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : $filter['month']=$this->sms_hook_m->month;
            isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : $filter['year']=$this->sms_hook_m->year;
            ///////////////////////////////////////////
            $rows=$this->std_fee_voucher_m->get_rows($filter,$params);        
            //check there is enough sms credits in account
            if($this->smspoint->get_remaining_sms()<count($rows)){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Insufficient sms credits. Please recharge your account at vendor website...';
                echo json_encode($this->RESPONSE);exit();
            }
            $message=htmlspecialchars_decode($form['message']);
            foreach($rows as $row){
                $amount=$this->std_fee_entry_m->get_voucher_amount($row['voucher_id'],$this->std_fee_entry_m->OPT_PLUS);
                $member=$this->student_m->get_by_primary($row['student_id']);
                //conversion keys
                $key_vars=array(
                        '{NAME}'=>$member->name,
                        '{GUARDIAN}'=>$member->guardian_name,
                        '{AMOUNT}'=>$amount,
                        '{DUEDATE}'=>$row['due_date'],
                        '{VOUCHER}'=>$row['voucher_id']
                    );
                ////////////////////////////////////////
                if($form['target']==$this->sms_hook_m->TARGET_STUDENT){
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$member->mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
                }
                if($form['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$member->guardian_mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
                }
            }
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS notification sent to ".count($rows)." members...";
            echo json_encode( $this->RESPONSE);        
    }

    // send sms to single staff
    public function sendSingleSms(){
            $this->load->library('smspoint');
            // get input fields into array
            $filter=array('campus_id'=>$this->CAMPUSID);
            $params=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary fields   
            $required=array('rid','message','target');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }    
            //check if sms api setting are enabled
            if(!$this->smspoint->is_sms_enable()){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='SMS sending option is not active. Please enable it in SMS API Settings menu...';
                echo json_encode($this->RESPONSE);exit();
            }
            isset($form['rid']) && !empty($form['rid']) ? $filter['mid']= $form['rid'] : '';
            ///////////////////////////////////////////
            $row=$this->std_fee_voucher_m->get_by($filter,true); 
            $message=htmlspecialchars_decode($form['message']);
            $amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_PLUS);
            $member=$this->student_m->get_by_primary($row->student_id);
            //conversion keys
            $key_vars=array(
                        '{NAME}'=>$member->name,
                        '{GUARDIAN}'=>$member->guardian_name,
                        '{AMOUNT}'=>$amount,
                        '{DUEDATE}'=>$row->due_date,
                        '{VOUCHER}'=>$row->voucher_id
                );
            ////////////////////////////////////////
            if($form['target']==$this->sms_hook_m->TARGET_STUDENT){
                $sms=strtr($message, $key_vars);
                $this->sms_history_m->add_row(array('mobile'=>$member->mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
            }
            if($form['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                $sms=strtr($message, $key_vars);
                $this->sms_history_m->add_row(array('mobile'=>$member->guardian_mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
            }
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS notification sent...";
            echo json_encode( $this->RESPONSE);        
    }


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** STAFF FUNCTIONS*******************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

    // filter rows
    public function filterPayHistory(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
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
        $filter=array('campus_id'=>$this->CAMPUSID);
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
            $this->RESPONSE['rows'][$i]['amount']=$this->stf_pay_entry_m->get_voucher_amount($row['voucher_id'],'',$this->CAMPUSID);
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addPayEntry(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','amount','operation');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        strtolower($form['operation'])=='subtract' ? $opt=$this->stf_pay_entry_m->OPT_MINUS : $opt=$this->stf_pay_entry_m->OPT_PLUS;
        $row=$this->stf_pay_voucher_m->get_by_primary($form['rid']);
        if($row->status==$this->stf_pay_voucher_m->STATUS_PAID || $row->status==$this->stf_pay_voucher_m->STATUS_CANCELED){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Paid and canceled voucher can not be updated...';
            echo json_encode($this->RESPONSE);exit();

        }  
        if(intval($form['amount'])<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please enter valid amount...';
            echo json_encode($this->RESPONSE);exit();

        }  
        $entry_type=$this->stf_pay_voucher_m->TYPE_PAY;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        if($opt==$this->stf_pay_entry_m->OPT_MINUS){
            $entry_type=$this->stf_pay_entry_m->TYPE_FINE;
            $this->stf_pay_voucher_m->update_column_value($row->mid,'amount',$form['amount'],'minus');
            //create ledger entry for this operation
            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$this->accounts_m->_SALARY_PAYABLE,$this->accounts_m->_DEDUCTION,$form['amount'],true);
        }else{
            $this->stf_pay_voucher_m->update_column_value($row->mid,'amount',$form['amount']);
            //create ledger entry for this operation
            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$this->accounts_m->_STAFF_SALARY,$this->accounts_m->_SALARY_PAYABLE,$form['amount'],true);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $entry=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$row->staff_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$form['amount'],'remarks'=>$form['title'],'operation'=>$opt,'type'=>$entry_type,'ledger_id'=>$ledger_id);
        if($this->stf_pay_entry_m->add_row($entry)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //send back the resposne  
        $this->RESPONSE['message']='Recored updated successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function givePayment(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data 
        //method: cash, advance, 
        $required=array('rid','method');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        $row=$this->stf_pay_voucher_m->get_by_primary($form['rid']);
        $row->amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,$this->stf_pay_entry_m->OPT_PLUS,$this->CAMPUSID);        
        $minus_amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,$this->stf_pay_entry_m->OPT_MINUS,$this->CAMPUSID);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($row->status==$this->stf_pay_voucher_m->STATUS_PAID || $row->status==$this->stf_pay_voucher_m->STATUS_CANCELED){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Paid and canceled voucher can not be changed...';
            echo json_encode($this->RESPONSE);exit();
        }  

        $pay_amount=$row->amount-$minus_amount;  
        $debited_account=$this->accounts_m->_CASH;
        if(strtolower($form['method'])=='bank'){$debited_account=$this->accounts_m->_BANK;}
        //create ledger entry for this operation
        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Salary paid to '.$member->name,$this->accounts_m->_SALARY_PAYABLE,$debited_account,$pay_amount,true);
        ////////////////////////////////////////////
        $entry=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$row->staff_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$pay_amount,'remarks'=>"Payment paid to staff member ($debited_account)",'operation'=>$this->stf_pay_entry_m->OPT_MINUS,'ledger_id'=>$ledger_id);
        if($this->stf_pay_entry_m->add_row($entry)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Payment cannot be paid at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ////////////////////////////////////////////////////////////////////////////////////////
        $data=array('date_paid'=>$this->stf_pay_voucher_m->date,'status'=>$this->stf_pay_voucher_m->STATUS_PAID);
        $this->stf_pay_voucher_m->save($data,$row->mid);
        //add to staff fee history
        $this->stf_pay_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$row->staff_id,'title'=>'Salary paid','amount'=>$pay_amount,'voucher_id'=>$row->mid,'month'=>$row->month,'year'=>$row->year,'month_number'=>$row->month_number));
        //ADD TO ORG INCOME
        $title="Payment Of $member->name ($member->staff_id)";
        $expense=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>'Salary paid to staff','amount'=>$pay_amount,'month'=>$row->month,'year'=>$row->year,'ledger_id'=>$ledger_id);
        $expense['type']=$this->accounts_m->_STAFF_SALARY;
        $expense['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STAFF_SALARY,'',true);
        $this->expense_m->add_row($expense);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." paid payment to staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        //////////////////////////////////////////////////////////////
        //check if there is any sms hook
        $filter=array('campus_id'=>$this->CAMPUSID);
        $filter['hook']=$this->sms_hook_m->HOOK_STAFF_GET_PAID;
        $hooks=$this->sms_hook_m->get_rows($filter);
        if(count($hooks)>0){
            foreach ($hooks as $hook){
                //send sms to student
                if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$member->name,
                            '{AMOUNT}'=>$pay_amount
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$member->mobile,$sms);
                }
            }
        }
        //send back the resposne  
        $this->RESPONSE['message']='Payment paid. You may print the updated voucher for record purpose.';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function givePartialPayment(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data 
        //method: cash, advance, 
        $required=array('rid','title','amount','method');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        $row=$this->stf_pay_voucher_m->get_by_primary($form['rid']);
        $member=$this->staff_m->get_by_primary($row->staff_id);

        $row->amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,$this->stf_pay_entry_m->OPT_PLUS,$this->CAMPUSID);        
        $minus_amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,$this->stf_pay_entry_m->OPT_MINUS,$this->CAMPUSID);
        if($row->status==$this->stf_pay_voucher_m->STATUS_PAID || $row->status==$this->stf_pay_voucher_m->STATUS_CANCELED){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Paid and canceled voucher can not be changed...';
            echo json_encode($this->RESPONSE);exit();
        }  

        $payable_amount=$row->amount-$minus_amount;  
        $pay_amount=$form['amount'];
        if($pay_amount > $payable_amount){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Amount is more the payable amount...';
            echo json_encode($this->RESPONSE);exit();            
        }

        $debited_account=$this->accounts_m->_CASH;
        if(strtolower($form['method'])=='bank'){$debited_account=$this->accounts_m->_BANK;}
        //create ledger entry for this operation
        $entry_title='Salary paid to '.$member->name;
        if(!empty($form['title']) && strtolower($form['title'])!='salary paid'){
            $entry_title=$form['title'];
        }
        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$entry_title,$this->accounts_m->_SALARY_PAYABLE,$debited_account,$pay_amount,true);
        ////////////////////////////////////////////
        $entry=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$row->staff_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$pay_amount,'remarks'=>"Payment paid to staff member ($debited_account)",'operation'=>$this->stf_pay_entry_m->OPT_MINUS,'ledger_id'=>$ledger_id,'type'=>$this->stf_pay_entry_m->TYPE_PAY);
        if($this->stf_pay_entry_m->add_row($entry)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Payment cannot be paid at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ////////////////////////////////////////////////////////////////////////////////////////
        $data=array('date_paid'=>$this->stf_pay_voucher_m->date,'status'=>$this->stf_pay_voucher_m->STATUS_PARTIAL_PAID);
        if($payable_amount == $pay_amount){
            $data['status']=$this->stf_pay_voucher_m->STATUS_PAID;
        }
        $this->stf_pay_voucher_m->save($data,$row->mid);
        //add to staff fee history
        $this->stf_pay_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$row->staff_id,'title'=>'Salary paid','amount'=>$pay_amount,'voucher_id'=>$row->mid,'month'=>$row->month,'year'=>$row->year,'month_number'=>$row->month_number));
        //ADD TO ORG INCOME
        $title="Payment Of $member->name ($member->staff_id)";
        $expense=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>'Salary paid to staff','amount'=>$pay_amount,'month'=>$row->month,'year'=>$row->year,'ledger_id'=>$ledger_id);
        $expense['type']=$this->accounts_m->_STAFF_SALARY;
        $expense['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STAFF_SALARY,'',true);
        $this->expense_m->add_row($expense);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." paid payment to staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        //////////////////////////////////////////////////////////////
        //check if there is any sms hook
        $filter=array('campus_id'=>$this->CAMPUSID);
        $filter['hook']=$this->sms_hook_m->HOOK_STAFF_GET_PAID;
        $hooks=$this->sms_hook_m->get_rows($filter);
        if(count($hooks)>0){
            foreach ($hooks as $hook){
                //send sms to student
                if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$member->name,
                            '{AMOUNT}'=>$pay_amount
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$member->mobile,$sms);
                }
            }
        }
        //send back the resposne  
        $this->RESPONSE['message']='Payment paid. You may print the updated voucher for record purpose.';
        echo json_encode($this->RESPONSE);exit();
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
        if(empty($rid)|| $this->stf_pay_voucher_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid slip...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_pay_voucher_m->get_by_primary($rid);
        $row->amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,'',$this->CAMPUSID);
        $row->entries=$this->stf_pay_entry_m->get_rows(array('voucher_id'=>$row->voucher_id,'campus_id'=>$this->CAMPUSID));
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    // update row
    public function deletePay(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        isset($form['type']) ? $type=$form['type'] : $type='';
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }

        switch (strtolower($type)) {
            case 'entry':{
                //check for necessary data   
                if(empty($rid)|| $this->stf_pay_entry_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Please choose a valid entry...';
                    echo json_encode($this->RESPONSE);exit();
                }
                $row=$this->stf_pay_entry_m->get_by_primary($rid);
                $voucher=$this->stf_pay_voucher_m->get_by(array('voucher_id'=>$row->voucher_id,'campus_id'=>$this->CAMPUSID),true);
                if($this->LOGIN_USER->prm_finance<4 && $voucher->status==$this->stf_pay_voucher_m->STATUS_PAID ){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Paid record cannot be removed...';
                    echo json_encode($this->RESPONSE);exit();

                }
                if($this->stf_pay_entry_m->delete($rid)==false){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Entry can not be removed at this time. Please try again later...';
                    echo json_encode($this->RESPONSE);exit();
                }
                $this->accounts_ledger_m->delete($row->ledger_id);
                //Log the user Activity
                $msg=$this->LOGIN_USER->name." removed staff pay entry(".$row->remarks.", Amount:".$row->amount.") of voucher(".$row->voucher_id.").";
                $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
                //////////////////////////////////////////////////////////////
                $this->RESPONSE['message']='Removed Successfully.';
            }
            break;
            case 'slip':{
                //check for necessary data   
                if(empty($rid)|| $this->stf_pay_voucher_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Please choose a valid voucher...';
                    echo json_encode($this->RESPONSE);exit();
                }
                $voucher=$this->stf_pay_voucher_m->get_by_primary($rid);
                if(!$this->LOGIN_USER->prm_finance<5 && $voucher->status==$this->stf_pay_voucher_m->STATUS_PAID){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Paid record cannot be removed...';
                    echo json_encode($this->RESPONSE);exit();

                }
                $entries=$this->stf_pay_entry_m->get_rows(array('voucher_id'=>$voucher->voucher_id,'campus_id'=>$this->CAMPUSID));
                foreach($entries as $entry){
                    $this->stf_pay_entry_m->delete($entry['mid']);
                    $this->accounts_ledger_m->delete($entry['ledger_id']);
                    }
                $this->stf_pay_voucher_m->delete($voucher->mid);
                //Log the user Activity
                $msg=$this->LOGIN_USER->name." removed staff pay voucher(".$voucher->title.", Amount:".$voucher->amount.").";
                $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
                //////////////////////////////////////////////////////////////
                $this->RESPONSE['message']='Removed Successfully.';

            }
            break;
            
            default:{
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid operation...';
                echo json_encode($this->RESPONSE);exit();
            }
            break;
        }
        //send back the resposne  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function deletePayVouchers(){
        set_time_limit(900);
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //////////////////////////////////////////////////////////////////////////
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        
        $vouchers=$this->stf_pay_voucher_m->get_rows(array('campus_id'=>$this->CAMPUSID,'month_number'=>$this->stf_pay_voucher_m->month_number));
        foreach($vouchers as $voucher){            
            $entries=$this->stf_pay_entry_m->get_rows(array('voucher_id'=>$voucher['voucher_id'],'campus_id'=>$this->CAMPUSID));
            foreach($entries as $entry){
                $this->stf_pay_entry_m->delete($entry['mid']);
                $this->accounts_ledger_m->delete($entry['ledger_id']);
                }
            $this->stf_pay_history_m->delete(NULL,array('voucher_id'=>$voucher['mid'],'campus_id'=>$this->CAMPUSID));
            $this->stf_pay_voucher_m->delete($voucher['mid']);
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed pay vouchers(".date('M-Y')."";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Removed Successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // create rows
    public function createPayVouchers(){
        set_time_limit(900);
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $auto_pay=false;
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        //check for necessary required data   
        $required=array('month','year');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select valid month, year and due date...';
            echo json_encode($this->RESPONSE);exit();
            }
        }     

        $monthNumber=get_month_number($form['month'],$form['year']);
        if($monthNumber < $this->stf_pay_voucher_m->month_number-1 || $monthNumber > $this->stf_pay_voucher_m->month_number+1 ){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vouchers of previous or current month can be created. Try again...';
            echo json_encode($this->RESPONSE);exit();

        }  
        if($this->stf_pay_voucher_m->get_rows(array('month_number'=>$this->stf_pay_voucher_m->month_number,'type'=>$this->stf_pay_voucher_m->TYPE_PAY,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Payroll for this month has already been created. Please use search feature to list out slips...';
            echo json_encode($this->RESPONSE);exit();

        }  
        /////////////////////////////////////////
        isset($form['auto_pay']) && $form['auto_pay']=='pay' ? $auto_pay=true : '';
        $filter['status']=$this->staff_m->STATUS_ACTIVE;
        $staffs=$this->staff_m->get_rows($filter);
        foreach($staffs as $staff){
            $blank_rows=array();
            $voucher_total_amount=0;
            $voucher_id=$this->stf_pay_voucher_m->get_new_voucher_id();
            $voucher=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$staff['mid'],'stf_id'=>$staff['staff_id'],'staff_name'=>$staff['name'],'month_number'=>$monthNumber,'month'=>$form['month'],'year'=>$form['year'],'voucher_id'=>$voucher_id,'title'=>'Payment of '.month_string($form['month']).','.$form['year'],'status'=>$this->stf_pay_voucher_m->STATUS_UNPAID);
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher_id,'staff_id'=>$staff['mid'],'month_number'=>$monthNumber);
            ////ADD STAFF SALARY TO VOUCHER
            $voucher_total_amount+=$staff['salary'];
            $entry['remarks']='Basic Salary';
            $entry['amount']=$staff['salary'];
            $entry['type']=$this->stf_pay_entry_m->TYPE_PAY;
            $entry['operation']=$this->stf_pay_entry_m->OPT_PLUS;
            $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$staff['name'].' salary',$this->accounts_m->_STAFF_SALARY,$this->accounts_m->_SALARY_PAYABLE,$staff['salary'],true,'Salary of '.date('M Y'),'Salary of '.date('M Y') );
            $this->stf_pay_entry_m->add_row($entry);
            //ADD STAFF TRANSPORT FARE TO VOUCHER
            if($staff['transport_fee']>0){
                $voucher_total_amount-=$staff['transport_fee'];
                $entry['remarks']='Transport Fare';
                $entry['amount']=$staff['transport_fee'];
                $entry['type']=$this->stf_pay_entry_m->TYPE_FEE;
                $entry['operation']=$this->stf_pay_entry_m->OPT_MINUS;
                $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$staff['name'].' transport charges',$this->accounts_m->_SALARY_PAYABLE,$this->accounts_m->_FEE_TRANSPORT,$staff['transport_fee'],true,'Transport charges of '.date('M Y'),'Transport charges of '.date('M Y') );
                $this->stf_pay_entry_m->add_row($entry);
            }
            //ADD ALLOWANCE TO VOUCHER
            $allowances=$this->stf_allownce_m->get_rows(array('staff_id'=>$staff['mid'],'campus_id'=>$this->CAMPUSID));
            foreach($allowances as $row){
                if($row['amount']>0){               
                    $voucher_total_amount+=$row['amount'];
                    $entry['remarks']=$row['title'];
                    $entry['amount']=$row['amount'];
                    $entry['type']=$this->stf_pay_entry_m->TYPE_ALLOWANCE;
                    $entry['operation']=$this->stf_pay_entry_m->OPT_PLUS;
                    $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$staff['name'].' allowance ('.$row['title'].')',$this->accounts_m->_STAFF_ALLOWANCES,$this->accounts_m->_SALARY_PAYABLE,$row['amount'],true,'allowances of '.date('M Y'),'allowances of '.date('M Y') );
                    $this->stf_pay_entry_m->add_row($entry);
                }
            }
            //SUBTRACT PERMANANT DEDUCTIONS FROM VOUCHER
            $deductions=$this->stf_deduction_m->get_rows(array('staff_id'=>$staff['mid'],'campus_id'=>$this->CAMPUSID,'month'=>$this->stf_pay_voucher_m->month,'year'=>$this->stf_pay_voucher_m->year));
            foreach($deductions as $row){   
                if($row['amount']>0){             
                    $voucher_total_amount-=$row['amount'];
                    $entry['remarks']=$row['title'];
                    $entry['amount']=$row['amount'];
                    $entry['type']=$this->stf_pay_entry_m->TYPE_DEDUCTION;
                    $entry['operation']=$this->stf_pay_entry_m->OPT_MINUS;
                    $entry['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$staff['name'].' deduction ('.$row['title'].')',$this->accounts_m->_SALARY_PAYABLE,$this->accounts_m->_SALARY_ADVANCE,$row['amount'],true,'Installment of '.date('M Y'),'Installment of '.date('M Y') );
                    $this->stf_pay_entry_m->add_row($entry);
                }
            }
            if($voucher_total_amount>0){
                $voucher['amount']=$voucher_total_amount;
                $vid=$this->stf_pay_voucher_m->add_row($voucher);            
            }

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////CHECK IF AUTO FEE COLLECTION IS ENABLED/////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if($auto_pay && $voucher_total_amount>0){   
                $pay_amount=$voucher_total_amount;             
                //create ledger entry for this operation
                $ldgr_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Auto paid salary to'.$staff['name'],$this->accounts_m->_SALARY_PAYABLE,$this->accounts_m->_CASH,$pay_amount,true);
                ////////////////////////////////////////////
                $rx_entry=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$staff['mid'],'voucher_id'=>$voucher_id,'month_number'=>$monthNumber,'amount'=>$pay_amount,'remarks'=>'Auto Payment paid salary','operation'=>$this->stf_pay_entry_m->OPT_MINUS,'ledger_id'=>$ldgr_id);
                if($this->stf_pay_entry_m->add_row($rx_entry)!=false){        
                    ////////////////////////////////////////////////////////////////////////////////////////
                    $data=array('date_paid'=>$this->stf_pay_voucher_m->date,'status'=>$this->stf_pay_voucher_m->STATUS_PAID);
                    $this->stf_pay_voucher_m->save($data,$vid);
                    //add to staff pay history
                    $this->stf_pay_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$staff['mid'],'title'=>'Salary paid','amount'=>$pay_amount,'voucher_id'=>$vid,'month'=>$form['month'],'year'=>$form['year'],'month_number'=>$monthNumber));
                    //ADD TO ORG EXPENSE
                    $title="Pay Of ".$staff['name']."(".$staff['staff_id'].")";
                    $expense=array('campus_id'=>$this->CAMPUSID,'title'=>$title,'description'=>'Salary paid','amount'=>$pay_amount,'month'=>$form['month'],'year'=>$form['year'],'ledger_id'=>$ldgr_id,'type'=>$this->accounts_m->_STAFF_SALARY);
                    $expense['account_id']=$this->accounts_m->get_account_id($this->CAMPUSID,$this->accounts_m->_STAFF_SALARY,'',true);
                    $this->expense_m->add_row($expense);
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    //////////////////////////////////////////CHECK IF THERE IS ANY SMS HOOK////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $filter=array('campus_id'=>$this->CAMPUSID);
                    $filter['hook']=$this->sms_hook_m->HOOK_STAFF_GET_PAID;
                    $hooks=$this->sms_hook_m->get_rows($filter);
                    if(count($hooks)>0){
                        foreach ($hooks as $hook){
                            //send sms to student
                            if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$staff['name'],
                                        '{AMOUNT}'=>$pay_amount
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($this->CAMPUSID,$staff['mobile'],$sms);
                            }
                        }
                    }
                }
            }

        }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']='Salary slips created successfully.';
        echo json_encode( $this->RESPONSE);
        
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // send sms to all filtered staff
    public function sendPayListSms(){
            // get input fields into array
            $filter=array('campus_id'=>$this->CAMPUSID);
            $params=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary fields   
            $required=array('target','message');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }    
            //check if sms api setting are enabled
            if(!$this->smspoint->is_sms_enable()){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='SMS sending option is not active. Please enable it in SMS API Settings menu...';
                echo json_encode($this->RESPONSE);exit();
            }
            ///////////////////////////////////////////////////////////////////////////////////////////
            isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
            isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : $filter['month']=$this->sms_hook_m->month;
            isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : $filter['year']=$this->sms_hook_m->year;
            ///////////////////////////////////////////
            $rows=$this->stf_pay_voucher_m->get_rows($filter,$params);        
            //check there is enough sms credits in account
            if($this->smspoint->get_remaining_sms()<count($rows)){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Insufficient sms credits. Please recharge your account at vendor website...';
                echo json_encode($this->RESPONSE);exit();
            }
            $message=htmlspecialchars_decode($form['message']);
            foreach($rows as $row){
                $amount=$this->stf_pay_entry_m->get_voucher_amount($row['voucher_id'],$this->stf_pay_entry_m->OPT_PLUS);
                $member=$this->staff_m->get_by_primary($row['staff_id']);
                //conversion keys
                $key_vars=array(
                        '{NAME}'=>$member->name,
                        '{GUARDIAN}'=>$member->guardian_name,
                        '{AMOUNT}'=>$amount,
                        '{VOUCHER}'=>$row['voucher_id']
                    );
                ////////////////////////////////////////
                if($form['target']==$this->sms_hook_m->TARGET_STAFF){
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$member->mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
                }
                if($form['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$member->guardian_mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
                }
            }
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS notification sent to ".count($rows)." members...";
            echo json_encode( $this->RESPONSE);        
    }


    // send sms to single staff
    public function sendPaySingleSms(){
            // get input fields into array
            $filter=array('campus_id'=>$this->CAMPUSID);
            $params=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary fields   
            $required=array('rid','message','target');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }    
            //check if sms api setting are enabled
            if(!$this->smspoint->is_sms_enable()){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='SMS sending option is not active. Please enable it in SMS API Settings menu...';
                echo json_encode($this->RESPONSE);exit();
            }
            isset($form['rid']) && !empty($form['rid']) ? $filter['mid']= $form['rid'] : '';
            ///////////////////////////////////////////
            $row=$this->stf_pay_voucher_m->get_by($filter,true); 
            $message=htmlspecialchars_decode($form['message']);
            $amount=$this->stf_pay_entry_m->get_voucher_amount($row->voucher_id,$this->stf_pay_entry_m->OPT_PLUS);
            $member=$this->staff_m->get_by_primary($row->staff_id);
            //conversion keys
            $key_vars=array(
                        '{NAME}'=>$member->name,
                        '{GUARDIAN}'=>$member->guardian_name,
                        '{AMOUNT}'=>$amount,
                        '{VOUCHER}'=>$row->voucher_id
                );
            ////////////////////////////////////////
            if($form['target']==$this->sms_hook_m->TARGET_STAFF){
                $sms=strtr($message, $key_vars);
                $this->sms_history_m->add_row(array('mobile'=>$member->mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
            }
            if($form['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                $sms=strtr($message, $key_vars);
                $this->sms_history_m->add_row(array('mobile'=>$member->guardian_mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
            }
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS notification sent...";
            echo json_encode( $this->RESPONSE);        
    }


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** EXPENSE FUNCTIONS*****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

    // filter rows
    public function filterExpenses(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','description','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        isset($form['account']) && !empty($form['account']) ? $filter['account_id']= $form['account'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='jd DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->expense_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->expense_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID,'type'=>$this->accounts_m->TYPE_EXPENSE));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            if($row['account_id']>0 && array_key_exists($row['account_id'], $accounts)){
                $this->RESPONSE['rows'][$i]['account']=$accounts[$row['account_id']];
            }else{
                $this->RESPONSE['rows'][$i]['account']='';
            }
                
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addExpense(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('title','amount','date','account','credit_account');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        $account=$this->accounts_m->get_by_primary($form['account']);
        if($account->is_default>0){$is_default=true;}else{$is_default=false;}
        $credit_account=$form['credit_account'];
        if($this->accounts_m->get_rows(array('campus_id'=>$this->CAMPUSID,'title'=>$credit_account,'type'=>$this->accounts_m->TYPE_ASSETS,'is_current_asset >'=>0,'is_default >'=>0,'period_id'=>$this->CURRENT_ACCOUNTING_PID),'',true)<1){$is_default=false;}
        $form['account_id']=$account->mid;
        $form['type']=$account->title;
        $form['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$account->title,$credit_account,$form['amount'],$is_default,$form['description'],$form['description'],$form['date']);
        //save data in database   
        $form['campus_id']=$this->CAMPUSID;          
        if($this->expense_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created expense entry (".$form['title'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Data saved successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateExpense(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','date','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 


        $row=$this->expense_m->get_by_primary($form['rid']);
        //save data in database                
        if($this->expense_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        if($row->amount != floatval($form['amount'])){ 
            $this->accounts_ledger_m->save(array('debit_amount'=>$form['amount'],'credit_amount'=>$form['amount']),$row->ledger_id);
        }
        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function deleteExpense(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';

        //check for necessary data   
        if(empty($rid)|| $this->expense_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid entry...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->expense_m->get_by_primary($rid);
        if($this->expense_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($row->account_id>0){            
            $this->accounts_ledger_m->delete($row->ledger_id);
        }

        //send back the resposne  
        $this->RESPONSE['message']='Removed Successfully...';
        echo json_encode($this->RESPONSE);exit();
    }


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** INCOME FUNCTIONS*****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

    // filter rows
    public function filterIncome(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','description','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        isset($form['account']) && !empty($form['account']) ? $filter['account_id']= $form['account'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='jd DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->income_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->income_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID,'type'=>$this->accounts_m->TYPE_REVENUE));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            if($row['account_id']>0 && array_key_exists($row['account_id'], $accounts)){
                $this->RESPONSE['rows'][$i]['account']=$accounts[$row['account_id']];
            }else{
                $this->RESPONSE['rows'][$i]['account']='';
            }
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addIncome(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('title','amount','date','account','debit_account');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        $account=$this->accounts_m->get_by_primary($form['account']);
        $this->RESPONSE['account']=$this->accounts_m->get_account_id($this->CAMPUSID,$account->title);
        if($account->is_default>0){$is_default=true;}else{$is_default=false;}

        $debit_account=$form['debit_account'];
        if($this->accounts_m->get_rows(array('campus_id'=>$this->CAMPUSID,'title'=>$debit_account,'type'=>$this->accounts_m->TYPE_ASSETS,'is_current_asset >'=>0,'is_default >'=>0,'period_id'=>$this->CURRENT_ACCOUNTING_PID),'',true)<1){$is_default=false;}

        $form['account_id']=$account->mid;
        $form['type']=$account->title;
        $form['ledger_id']=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$debit_account,$account->title,$form['amount'],$is_default,$form['description'],$form['description'],$form['date']);
        //save data in database  
        $form['campus_id']=$this->CAMPUSID;             
        if($this->income_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created income entry (".$form['title'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Data saved successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateIncome(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','description','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 

        //save data in database                
        if($this->income_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        $row=$this->income_m->get_by_primary($form['rid']);
        if($row->amount != $form['amount']){ 
            $this->accounts_ledger_m->save(array('debit_amount'=>$form['amount'],'credit_amount'=>$form['amount']),$row->ledger_id);
        }
        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function deleteIncome(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';

        //check for necessary data   
        if(empty($rid)|| $this->income_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid entry...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->income_m->get_by_primary($rid);
        if($this->income_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($row->account_id>0){
            $this->accounts_ledger_m->delete($row->ledger_id);
        }
            

        //send back the resposne  
        $this->RESPONSE['message']='Removed Successfully...';
        echo json_encode($this->RESPONSE);exit();
    }


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** ACCOUNTS FUNCTIONS****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

    // filter rows
    public function filterAccounts(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','type');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $period_id=$this->accounts_period_m->get_current_period_id($this->CAMPUSID);
        isset($form['period']) && !empty($form['period']) ? $period_id= $form['period'] : '';
        // isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='type DESC, title ASC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $filter['period_id']=$period_id;
        $this->RESPONSE['rows']=$this->accounts_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->accounts_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['norm_balance']=$this->accounts_m->get_normalized_amount($row['type'],$row['balance']);
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addAccount(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('title','type');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        //check for necessary data   
        if($this->accounts_m->get_rows(array('title'=>$form['title'],'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account already exist...';
            echo json_encode($this->RESPONSE);exit();
        }
        //save data in database    
        $form['campus_id']=$this->CAMPUSID;         
        if($this->accounts_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created financial account (".$form['title'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Data saved successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateAccount(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 

        $row=$this->accounts_m->get_by_primary($form['rid']);
        //check for necessary data   
        if($this->accounts_m->get_rows(array('mid <>'=>$row->mid,'title'=>$form['title'],'type'=>$row->type,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account already exist...';
            echo json_encode($this->RESPONSE);exit();
        }
        //save data in database                
        if($this->accounts_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function deleteAccount(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';

        //check for necessary data   
        if(empty($rid)|| $this->accounts_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid account...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->accounts_m->get_by_primary($rid);//check for necessary data   
        if($row->is_default > 0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Default Account can not be removed...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->accounts_ledger_m->get_rows(array('campus_id'=>$this->CAMPUSID,'debit_account_id'=>$rid),'',true)>0 || $this->accounts_ledger_m->get_rows(array('campus_id'=>$this->CAMPUSID,'credit_account_id'=>$rid),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account has financial entries in journal. It can be removed only at start of next accounting period...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->accounts_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne  
        $this->RESPONSE['message']='Removed Successfully...';
        echo json_encode($this->RESPONSE);exit();
    }


    // filter account profile rows
    public function filterAccountProfile(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','debit_reference','credit_reference','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        isset($form['date']) && !empty($form['date']) ? $filter['date']= $form['date'] : '';
        if(isset($form['rid']) && !empty($form['rid']) ){
            if(isset($form['type']) && !empty($form['type'])){
                if($form['type']=='cr'){
                    $filter['credit_account_id']= $form['rid'];
                }elseif($form['type']=='dr'){
                    $filter['debit_account_id']= $form['rid'];
                }else{
                    $filter['debit_account_id']= $form['rid'];
                    $params['or_where']=array('credit_account_id'=>$form['rid']);
                }
            }else{
                $filter['debit_account_id']= $form['rid'];
                $params['or_where']=array('credit_account_id'=>$form['rid']);                
            }
        }else{
            exit();
        }
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='jd DESC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->accounts_ledger_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->accounts_ledger_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $account=$this->accounts_m->get_by_primary($form['rid']);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            if($form['rid']==$row['debit_account_id']){
                $this->RESPONSE['rows'][$i]['balance']=$this->accounts_m->get_normalized_amount($account->type,$row['debit_account_balance']);
                $this->RESPONSE['rows'][$i]['d_amount']=$row['debit_amount'];
                $this->RESPONSE['rows'][$i]['c_amount']='0.00';
            }elseif($form['rid']==$row['credit_account_id']){
                $this->RESPONSE['rows'][$i]['balance']=$this->accounts_m->get_normalized_amount($account->type,$row['credit_account_balance']);
                $this->RESPONSE['rows'][$i]['d_amount']='0.00';
                $this->RESPONSE['rows'][$i]['c_amount']=$row['credit_amount'];
            }
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // update row
    public function resetCampusAccounts(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['key']) ? $key=$form['key'] : $key='';

        //check for necessary data   
        if(!$this->LOGIN_USER->prm_finance<5){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Permission Denied!';
            echo json_encode($this->RESPONSE);exit();
        }

        if($key != $this->DATA_RESET_PASSWORD){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Invalid Data Reset Password!';
            echo json_encode($this->RESPONSE);exit();  

        }
        $filter=array('campus_id'=>$this->CAMPUSID);
        $this->accounts_m->delete(NULL,$filter,true);
        $this->accounts_ledger_m->delete(NULL,$filter,true);
        $this->accounts_period_m->validate_current_period();
        $this->accounts_m->validate_default_tables($this->CAMPUSID);

        //send back the resposne  
        $this->RESPONSE['message']='Accounts Reset To Default Successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ****************************************** LEDGER FUNCTIONS*****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

    // filter rows
    public function filterLedger(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','debit_reference','credit_reference','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $period_id=$this->accounts_period_m->get_current_period_id($this->CAMPUSID);
        isset($form['period']) && !empty($form['period']) ? $period_id= $form['period'] : '';
        isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        isset($form['debit_account_id']) && !empty($form['debit_account_id']) ? $filter['debit_account_id']= $form['debit_account_id'] : '';
        isset($form['credit_account_id']) && !empty($form['credit_account_id']) ? $filter['credit_account_id']= $form['credit_account_id']:'';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='jd DESC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $filter['period_id']=$period_id;
        $this->RESPONSE['rows']=$this->accounts_ledger_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->accounts_ledger_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $accounts=$this->accounts_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['debit_account']=array_key_exists($row['debit_account_id'], $accounts) ? $accounts[$row['debit_account_id']] : '';
            $this->RESPONSE['rows'][$i]['credit_account']=array_key_exists($row['credit_account_id'], $accounts) ? $accounts[$row['credit_account_id']] : '';
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addLedgerRecord(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('title','amount','date','account','credit_account');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        if($form['account']==$form['credit_account']){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Debit and credit account should be different...';
            echo json_encode($this->RESPONSE);exit();            
        }
        $account=$this->accounts_m->get_by_primary($form['account']);
        $credit_account=$this->accounts_m->get_by_primary($form['credit_account']);

        if($account->is_default>0 && $credit_account->is_default>0){$is_default=true;}else{$is_default=false;}
        //save data in database            
        if($this->accounts_ledger_m->add_entry($this->CAMPUSID,$form['title'],$account->title,$credit_account->title,$form['amount'],$is_default,$form['description'],$form['description'],$form['date'],1)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created journal entry (".$form['title']." amount:".$form['amount'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Data saved successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateLedgerRecord(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','description','date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 

        $row=$this->accounts_ledger_m->get_by_primary($form['rid']);
        if($row->is_manual<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='System entry cannot be updated...';
            echo json_encode($this->RESPONSE);exit();  

        }
        if($row->date != $form['date']){            
            $form['jd']=get_jd_from_date($form['date'],'-',true);
            $form['day']=get_day_from_date($form['date'],'-');
            $form['month']=get_month_from_date($form['date'],'-',true);
            $form['year']=get_year_from_date($form['date'],'-'); 
        }
        //save data in database                
        if($this->accounts_ledger_m->save($form,$row->mid)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function deleteLedgerRecord(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';

        //check for necessary data   
        if(empty($rid)|| $this->accounts_ledger_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid entry...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->accounts_ledger_m->get_by_primary($form['rid']);
        if($row->is_manual<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='System entry cannot be removed...';
            echo json_encode($this->RESPONSE);exit();  

        }
        if($this->accounts_ledger_m->delete($row->mid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Entry can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        //reverse ledger entry amount from account.        
        $this->accounts_m->update_column_value($row->debit_account_id,'balance',$row->debit_amount,'minus');
        $this->accounts_m->update_column_value($row->credit_account_id,'balance',$row->credit_amount);


        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed journal entry (title: $row->title, debit_amount:$row->debit_amount, credit_amount:$row->credit_amount, date:$row->date).";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Removed Successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    private function updateFixedLateFeeFine($rid){        
        $row=$this->std_fee_voucher_m->get_by_primary($rid);
        $due_jd=get_jd_from_date($row->due_date,'-',true);
        $late_fee_fine=0;
        if($this->std_fee_voucher_m->todayjd > $due_jd && $row->status==$this->std_fee_voucher_m->STATUS_UNPAID && $row->type=$this->std_fee_voucher_m->TYPE_FEE){
            //paying late fee. add fine if not yet added for this month for fixed fine
            if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]==$this->campus_setting_m->_LATE_FEE_FINE_FIXED){
                $late_fee_fine=$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE];                
                if($this->std_fee_entry_m->get_rows(array('voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'type'=>$this->std_fee_entry_m->TYPE_LATE_FEE_FINE,'campus_id'=>$this->CAMPUSID),'',true)<1){
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    if($late_fee_fine>0){
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Late Fee Fine',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FINE,$late_fee_fine,true);
                        $entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$late_fee_fine,'remarks'=>'Late Fee Fine','type'=>$this->std_fee_entry_m->TYPE_LATE_FEE_FINE,'ledger_id'=>$ledger_id);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                }
            }
        }       
    }

    private function updatePerdayLateFeeFine($rid){ 
        //per day late fee fine must be updated by operator by counting days
        return true;       
        $row=$this->std_fee_voucher_m->get_by_primary($rid);
        $due_jd=get_jd_from_date($row->due_date,'-',true);
        $late_fee_fine=0;
        if($this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE_TYPE]==$this->campus_setting_m->_LATE_FEE_FINE_PERDAY){
            // paying late fee. add fine if not yet added for this month for perday fine
            $days=$this->std_fee_voucher_m->todayjd-$due_jd;
            $late_fee_fine=$this->CAMPUSSETTINGS[$this->campus_setting_m->_LATE_FEE_FINE]*$days;  

            if($this->std_fee_entry_m->get_rows(array('voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'type'=>$this->std_fee_entry_m->TYPE_LATE_FEE_FINE,'campus_id'=>$this->CAMPUSID),'',true)<1){
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                if($late_fee_fine>0){
                    $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Late Fee Fine',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_FINE,$late_fee_fine,true);
                    $entry=array('campus_id'=>$this->CAMPUSID,'student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$late_fee_fine,'remarks'=>'Late Fee Fine','type'=>$this->std_fee_entry_m->TYPE_LATE_FEE_FINE,'ledger_id'=>$ledger_id);
                    $this->std_fee_entry_m->add_row($entry);
                }
            }
        }       
    }




/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	