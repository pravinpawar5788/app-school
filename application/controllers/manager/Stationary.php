<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stationary extends Manager_Controller{

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
        if($this->LOGIN_USER->prm_stationary<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'stationary/';
        //load all models for this controller
        $models = array('stationary_m','stationary_history_m','class_m','student_m','staff_m','std_acheivement_m','std_award_m','std_history_m','std_fee_history_m','std_fee_entry_m','std_fee_voucher_m','stf_history_m','stf_deduction_m');
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
        redirect($this->CONT_ROOT.'items', 'refresh');	
	}
    // stationary items
    public function items($tab=''){
        
        $this->data['main_content']='stationary';   
        $this->data['menu']='stationary';           
        $this->data['sub_menu']='stationary';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='stationary';
        /////////////////////////////////////////////////////////////
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
                $this->data['main_content']='stationary_item_list';    
                $this->data['print_page_title']='List of Stationary Items'; 

            }
            break;
            case 'history':{
                $this->data['main_content']='stationary_history';    
                $this->data['print_page_title']='Stationary History'; 

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
    public function filter(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('item','description','qty','type');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        isset($form['price']) && !empty($form['price']) ? $filter['item_price >']= $form['price'] : '';
        isset($form['stock']) && !empty($form['stock']) ? $filter['qty >']= $form['stock'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stationary_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stationary_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function add(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('item','item_price','type');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        $rid=$this->stationary_m->add_row($form);              
        if($rid==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Item cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new stationary item (".$form['item'].'@price '.$form['item_price'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Stationary item saved successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function update(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','item','item_price');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 


        //save data in database                
        if($this->stationary_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Itam cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated stationary item (".$form['item'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function load(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->student_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->student_m->get_by_primary($rid);
        $filter['student_id']=$row->mid;
        $row->total_awards=$this->std_award_m->get_rows($filter,'',true);
        $row->total_punishments=$this->std_punishment_m->get_rows($filter,'',true);
        $row->total_acheivements=$this->std_acheivement_m->get_rows($filter,'',true);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    // update row
    public function delete(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if(empty($rid)|| $this->stationary_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid item...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stationary_m->get_by_primary($rid);
        if($this->stationary_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Item can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed stationary item (".$row->item.") with stock quantity(".$row->qty.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Item Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    ////////////////////////////////////////////////////////////

    // update row
    public function updateStock(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','qty','amount','credit_account');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 


        //save data in database                
        if($this->stationary_m->update_column_value($form['rid'],'qty',$form['qty'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Stock cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        $row=$this->stationary_m->get_by_primary($form['rid']);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." added stock (".$form['qty']." units) of stationary item (".$row->item.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        //save purchase history
        $price=$form['amount']/$form['qty'];
        $history=array('type'=>$row->type);
        $history['description']='Purchased stationary item('.$row->item.') for amount('.$form['amount'].')';
        $history['qty']=$form['qty'];
        $history['item_price']=$price;
        $history['history_type']=$this->stationary_history_m->TYPE_PURCHASED;
        $history['campus_id']=$this->CAMPUSID;
        $this->stationary_history_m->add_row($history);
        //create ledger entry for this operation
        $ref="Purchased $row->item qty(".$form['qty'].")";
        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Purchased stationary items',$this->accounts_m->_INVENTORY,$form['credit_account'],$form['amount'],false,$ref,$ref);

        //send back the resposne
        $this->RESPONSE['message']=' Stock Successfully Updated.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function issueStudentItem(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','item','qty');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        $activeSession=$this->session_m->getActiveSession();
        $item=$this->stationary_m->get_by_primary($form['item']);
        $member=$this->student_m->get_by_primary($form['rid']);
        $total_amount=$item->item_price*$form['qty'];
        if($item->qty < $form['qty']){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='The selected item do not have enough stock to issue this quantity...';
            echo json_encode($this->RESPONSE);exit();            
        }

        //create ledger entry for this operation
        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Issued stationary item('.$item->item.')',$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_SALES,$total_amount,true);

        if(isset($form['voucher_id']) && !empty($form['voucher_id'])){
            //update existing voucher of member
            $voucher=$this->std_fee_voucher_m->get_by_primary($form['voucher_id']);
            $entry=array('voucher_id'=>$voucher->voucher_id,'student_id'=>$member->mid,'amount'=>$total_amount,'remarks'=>'Issued '.$item->item.' quantity('.$form['qty'].')','ledger_id'=>$ledger_id,'campus_id'=>$this->CAMPUSID);
            $eid=$this->std_fee_entry_m->add_row($entry);
            $this->std_fee_voucher_m->update_column_value($voucher->mid,'amount',$total_amount);
        }else{
            //create new voucher for member         
            $voucher_id=$this->std_fee_voucher_m->get_new_voucher_id();
            $voucher=array('voucher_id'=>$voucher_id,'student_id'=>$member->mid,'student_name'=>$member->name,'roll_no'=>$member->roll_no,'class_id'=>$member->class_id,'session_id'=>$activeSession->mid,'type'=>$this->std_fee_voucher_m->TYPE_OTHER,'title'=>'Stationary Items Payment','amount'=>$total_amount,'campus_id'=>$this->CAMPUSID);
            $vid=$this->std_fee_voucher_m->add_row($voucher);
            $entry=array('voucher_id'=>$voucher_id,'student_id'=>$member->mid,'amount'=>$total_amount,'remarks'=>'Issued '.$item->item.' quantity('.$form['qty'].')','ledger_id'=>$ledger_id,'campus_id'=>$this->CAMPUSID);
            $eid=$this->std_fee_entry_m->add_row($entry);

        }
        //update stock
        $this->stationary_m->update_column_value($item->mid,'qty',$form['qty'],'subtract');          
        //add to stationary history
        $history=array('type'=>$item->type,'item_price'=>$item->item_price,'qty'=>$form['qty']);
        $history['description']=$item->item.' issued to '.$member->name.'('.$member->student_id.')';
        $history['history_type']=$this->stationary_history_m->TYPE_SOLD;
        $history['campus_id']=$this->CAMPUSID;
        $this->stationary_history_m->add_row($history);        
        //add to student history
        $history=array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Purchased Stationary Item');
        $history['description']='Purchased '.$item->item.' quantity('.$form['qty'].')';
        $this->std_history_m->add_row($history);
            
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." issued stationary item (".$item->item.'@qty '.$form['qty'].") to student.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Stationary item issued successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function issueStaffItem(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','item','qty');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        $item=$this->stationary_m->get_by_primary($form['item']);
        $member=$this->staff_m->get_by_primary($form['rid']);
        $total_amount=$item->item_price*$form['qty'];
        if($item->qty < $form['qty']){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='The selected item do not have enough stock to issue this quantity...';
            echo json_encode($this->RESPONSE);exit();            
        }

        //create ledger entry for this operation
        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Issued stationary item('.$item->item.')',$this->accounts_m->_DEDUCTION,$this->accounts_m->_SALES,$total_amount,true);

        //get payments next month
        $month=intval(date('m'));$year=intval(date('Y'));$month++;   //get payment of item from next month vouchers
        if($month>12){$month=1;$year++;}        
        $entry=array('staff_id'=>$member->mid,'title'=>'Get '.$item->item.'  quantity('.$form['qty'].')','amount'=>$total_amount,'month'=>$month,'year'=>$year,'ledger_id'=>$ledger_id);
        $this->stf_deduction_m->add_row($entry);
        //update stock
        $this->stationary_m->update_column_value($item->mid,'qty',$form['qty'],'subtract');          
        //add to stationary history
        $history=array('type'=>$item->type,'item_price'=>$item->item_price,'qty'=>$form['qty']);
        $history['description']=$item->item.' issued to staff member '.$member->name.'('.$member->staff_id.')';
        $history['history_type']=$this->stationary_history_m->TYPE_SOLD;
        $history['campus_id']=$this->CAMPUSID;
        $this->stationary_history_m->add_row($history);        
        //add to student history
        $history=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Purchased Stationary Item');
        $history['description']='Purchased '.$item->item.' quantity('.$form['qty'].')';
        $this->stf_history_m->add_row($history);
            
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." issued stationary item (".$item->item.'@qty '.$form['qty'].") to staff.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));


        //send back the resposne  
        $this->RESPONSE['message']=' Stationary item issued successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////


    // send sms to all filtered staff
    public function sendListSms(){
            $this->load->library('smspoint');
            // get input fields into array
            $filter=array();
            $params=array();
            $filter['campus_id']=$this->CAMPUSID;
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary fields   
            $required=array('message');
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

            isset($form['gender']) && !empty($form['gender']) ? $filter['gender']= $form['gender'] : '';
            isset($form['blood_group']) && !empty($form['blood_group']) ? $filter['blood_group']= $form['blood_group'] : '';
            isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
            isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
            isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
            isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';

            ///////////////////////////////////////////
            $rows=$this->student_m->get_rows($filter,$params);        
            //check there is enough sms credits in account
            if($this->smspoint->get_remaining_sms()<count($rows)){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Insufficient sms credits. Please recharge your account at vendor website...';
                echo json_encode($this->RESPONSE);exit();
            }
            $message=htmlspecialchars_decode($form['message']);
            foreach($rows as $row){
                //conversion keys
                $key_vars=array(
                        '{NAME}'=>$row['name'],
                        '{GUARDIAN}'=>$row['guardian_name']
                    );
                ////////////////////////////////////////
                $sms=strtr($message, $key_vars);
                $this->sms_history_m->add_row(array('mobile'=>$row['mobile'],'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
            }
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS Notification sent to ".count($rows)." Students...";
            echo json_encode( $this->RESPONSE);        
    }
    // send sms to single staff
    public function sendSingleSms(){
            $this->load->library('smspoint');
            // get input fields into array
            $filter=array();
            $params=array();
            $filter['campus_id']=$this->CAMPUSID;
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary fields   
            $required=array('rid','message');
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
            $row=$this->student_m->get_by($filter,true); 
            $message=htmlspecialchars_decode($form['message']);
            //conversion keys
            $key_vars=array(
                    '{NAME}'=>$row->name,
                    '{GUARDIAN}'=>$row->guardian_name
                );
            ////////////////////////////////////////
            $sms=strtr($message, $key_vars);
            $this->sms_history_m->add_row(array('mobile'=>$row->mobile,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS Notification sent to $row->name...";
            echo json_encode( $this->RESPONSE);        
    }
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////MISC FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterStaff(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','guardian_name','mobile','staff_id','cnic');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT*3;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='name ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $filter['status']=$this->staff_m->STATUS_ACTIVE;
        $this->RESPONSE['rows']=$this->staff_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
    // filter rows
    public function filterStudents(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','father_name','father_nic','guardian_name','student_id','computer_number','mobile','roll_no');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT*3;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='class_id ASC, roll_no ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['password']='';
            $this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
    // filter rows
    public function filterStationary(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////
        $filter['qty >']= 0;
        $params['orderby']='item ';
        $this->RESPONSE['rows']=$this->stationary_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
    // filter rows
    public function filterStudentVouchers(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        ///////////////////////////////////////////
        $filter['status <>']=$this->std_fee_voucher_m->STATUS_PAID;
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $params['orderby']='jd ';
        $this->RESPONSE['rows']=$this->std_fee_voucher_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////HISTORY FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterHistory(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('description','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array();
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        isset($form['purchaseLog']) && $form['purchaseLog']=='true' ? $filter['history_type']= $this->stationary_history_m->TYPE_PURCHASED : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stationary_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stationary_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	