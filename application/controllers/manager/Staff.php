<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff extends Manager_Controller{

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
        if($this->LOGIN_USER->prm_stf_info<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'staff/';
        //load all models for this controller
        $models = array('stf_role_m','staff_m','stf_qual_m','award_m','punishment_m','certificate_m','stf_attendance_m','stf_allownce_m','stf_acheivement_m','stf_advance_m','stf_award_m','stf_deduction_m','stf_history_m','stf_pay_history_m','stf_pay_entry_m','stf_pay_voucher_m','stf_punishment_m');
        $this->load->model($models);
        $this->load->library('smspoint');
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		
		$this->data['main_content']='staff';	
		$this->data['menu']='staff';			
		$this->data['sub_menu']='staff';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='staff';
        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    ///////DEVELOPER LOGIN FUNCTION IN EMS SOFTWARE ON THIS USER BEHALF/////////////
    public function login($rid=''){
        if(!$this->IS_DEV_LOGIN){
            $this->session->set_flashdata('error', 'Permission denied.');
            redirect($this->LIB_CONT_ROOT.'', 'refresh'); 
        }
        if(empty($rid) || $this->staff_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->session->set_flashdata('error', 'Please choose a valid record');
            redirect($this->LIB_CONT_ROOT.'staff', 'refresh'); 
        }else{
            //start login process   
            $row=$this->staff_m->get_by_primary($rid);
            $this->staff_m->login(array('staff_id'=>$row->staff_id,'password'=>$row->password));  
            redirect($this->APP_ROOT.'staff', 'refresh'); 

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
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                // $this->data['main_content']='staff_profile';    
                $this->data['main_content']='form_staff_registation_filled';    
                $this->data['print_page_title']='Staff profile'; 

            }
            break;
            case 'list':{
                $this->data['main_content']='staff_list';    
                $this->data['print_page_title']='Registered Staff List'; 

            }
            break;
            case 'allowances':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                $this->data['main_content']='staff_allowances';    
                $this->data['print_page_title']='Staff Allowances'; 

            }
            break;
            case 'deductions':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                $this->data['main_content']='staff_deductions';    
                $this->data['print_page_title']='Staff Loan History'; 

            }
            break;
            case 'form':{
                if(empty($form['type'])){
                    $this->session->set_flashdata('error', 'Please choose a valid form');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                //check which report to be printed
                switch (strtolower($form['type'])) {
                    case 'stfreg':{
                        $this->data['main_content']='form_staff_registation_blank';    
                        $this->data['print_page_title']='Staff Registration Form'; 
                    }break;
                    
                    default:{
                        $this->session->set_flashdata('error', 'Please choose a valid form');
                        redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                        
                    }break;
                }

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


     //save profile pic
    public function upload_picture(){
        //set_time_limit(90);
        //upload artwork file
        $size=isset($this->SETTINGS['max_upload_size']) ? $this->SETTINGS['max_upload_size'] : '800';    //0.8MB
        $min_width=$this->config->item('app_img_min_width');
        $min_height=$this->config->item('app_img_min_height');
        $form=$this->input->safe_post(array("user"));
        $staff=$this->staff_m->get_by_primary($form['user']);
            //upload artwork file
            if($this->IS_DEMO){
                $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
                redirect($this->CONT_ROOT.'profile/'.$staff->mid, 'refresh');           
            }
            $file_name=$staff->staff_id.mt_rand(101,999);
            $data=$this->upload_img('file',$file_name);
            if($data['file_uploaded']==FALSE){
                $this->session->set_flashdata('error', $data['file_error']."Max allowed file size is $size KB. Minimum dimension must be $min_width x $min_height Px.");
                redirect($this->CONT_ROOT.'profile/'.$staff->mid, 'refresh');
            }
            $nfile_name=$data['file_name'];
            $saveform=array('image'=>$nfile_name);
            $this->staff_m->save($saveform,$staff->mid);
            $this->session->set_flashdata('success', 'Profile picture uploaded successfully for '.$staff->name);
            redirect($this->CONT_ROOT.'profile/'.$staff->mid, 'refresh');           
    
    }
    ////////////////////upload file///////////////////////////////
    private function upload_img($file_name='file',$new_name=''){   
        $path='./uploads/images/staff/profile';
        // $size='250';    //0.25MB
        $size=isset($this->SETTINGS['max_upload_size']) ? $this->SETTINGS['max_upload_size'] : '800';    //0.8MB
        $allowed_types='jpg|jpeg|png|bmp';
        $upload_file_name=$file_name;    
        $min_width=$this->config->item('app_img_min_width');
        $min_height=$this->config->item('app_img_min_height');
        $upload_data=$this->upload_file($path,$size,$allowed_types,$upload_file_name,$new_name,$min_width,$min_height);
        return $upload_data;
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
        $search=array('name','staff_id','cnic','mobile','blood_group','gender');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['gender']) && !empty($form['gender']) ? $filter['gender']= $form['gender'] : '';
        isset($form['blood_group']) && !empty($form['blood_group']) ? $filter['blood_group']= $form['blood_group'] : '';
        isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        isset($form['role_id']) && !empty($form['role_id']) ? $filter['role_id']= $form['role_id'] : '';
        isset($form['salary']) && !empty($form['salary']) ? $filter['salary >']= $form['salary'] : '';
        if(isset($form['filter']) && !empty($form['filter'])){
            $filter_key=$form['filter'];
            $filter[$filter_key]=$form['search'];
        }else{
            //only search if user is not filtering specific data
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->staff_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->staff_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $roles=$this->stf_role_m->get_values_array('mid','title');
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['password']='';
            $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
            $i++;
        }
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
        $required=array('name','mobile','role_id','cnic','salary','staff_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('staff_id'=>$form["staff_id"]),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This staff ID already exist. Please choose another...';
            echo json_encode($this->RESPONSE);exit();
        }

        if(isset($form['password'])){
            if(empty($form['password'])){$form['password']=mt_rand(111,999);}
            $form['password']=$this->user_m->hash($form['password']);  
        }   

        //save data in database
        $key=strtoupper($this->SETTINGS[$this->system_setting_m->_STFID_KEY]);
        $form['staff_id']=  $this->staff_m->get_new_staff_id($key);   
        $form['campus_id']= $this->CAMPUSID;             
        if($this->staff_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new staff account for (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //check if there is any sms hook        
        $filter=array('campus_id'=>$this->CAMPUSID);
        $filter['hook']=$this->sms_hook_m->HOOK_ADMISSION;
        $hooks=$this->sms_hook_m->get_rows($filter);
        if(count($hooks)>0){
            foreach ($hooks as $hook){
                //send sms to staff
                if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                    $message=htmlspecialchars_decode($hook["template"]);
                    $this->sms_history_m->send_sms($form['mobile'],$message);
                }
            }
        }

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function update(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','mobile','role_id','cnic','salary','staff_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->staff_m->get_rows(array('staff_id'=>$form["staff_id"],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This staff ID already exist. Please choose another...';
            echo json_encode($this->RESPONSE);exit();
        }

        if(isset($form['password'])){
            unset($form['password']);
        }   

        //save data in database               
        if($this->staff_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated staff account (".$form['name'].").";
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
    if(empty($rid)|| $this->staff_m->get_rows(array('mid'=>$rid),'',true)<1){
        $this->RESPONSE['error']=TRUE;
        $this->RESPONSE['message']='Please choose a valid staff memeber...';
        echo json_encode($this->RESPONSE);exit();
    }

    $row=$this->staff_m->get_by_primary($rid);
    $filter['staff_id']=$row->mid;
    $row->total_awards=$this->stf_award_m->get_rows($filter,'',true);
    $row->total_punishments=$this->stf_punishment_m->get_rows($filter,'',true);
    $row->total_acheivements=$this->stf_acheivement_m->get_rows($filter,'',true);
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
        if(empty($rid)|| $this->staff_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff memeber...';
            echo json_encode($this->RESPONSE);exit();
        }

        if($this->staff_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Account Terminated Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////


// edit row
public function changeStatus(){ 
        // get input fields into array        
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','status');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   

        //check if a valid staff is being edited
        if(empty($form['rid'])||$this->staff_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }

        //save data in database
        if($this->staff_m->save(array('status'=>$form['status']),$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Status can not be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back repsonse
        $this->RESPONSE['message']='Status Updated Successfully...';
        echo json_encode($this->RESPONSE);exit();
}
// edit row
public function updatePassword(){ 
        // get input fields into array        
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary required data   
        $required=array('rid','password');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   

        //check if a valid staff is being edited
        if(empty($form['rid'])||$this->staff_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }

        $form['password']=$this->user_m->hash($form['password']);  
        //save data in database
        if($this->staff_m->save(array('password'=>$form['password']),$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Password can not be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back repsonse
        $this->RESPONSE['message']='Password Updated Successfully...';
        echo json_encode($this->RESPONSE);exit();
}

// send sms to all filtered staff
public function sendListSms(){
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
        isset($form['role_id']) && !empty($form['role_id']) ? $filter['role_id']= $form['role_id'] : '';
        isset($form['salary']) && !empty($form['salary']) ? $filter['salary >']= $form['salary'] : '';
        ///////////////////////////////////////////
        $rows=$this->staff_m->get_rows($filter,$params);        
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
        $this->RESPONSE['message']="SMS Notification sent to ".count($rows)." Staff members...";
        echo json_encode( $this->RESPONSE);        
}

// send sms to single staff
public function sendSingleSms(){
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
        $row=$this->staff_m->get_by($filter,true); 
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
///////////////////////////ALLOWANCE FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterAllowances(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','description','amount','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_allownce_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_allownce_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addAllowance(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->staff_m->get_by_primary($form['rid']);
        $form['staff_id']=$member->mid;

        //save data in database 
        $form['campus_id']=$this->CAMPUSID;             
        if($this->stf_allownce_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Allowance cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered allowance(".$form['title'].", ".$form['amount'].") for Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." will receive allowance(".$form['title'].") from next month and onwards.";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Allowance Subscription','description'=>$msg));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function updateAllowance(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->stf_allownce_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid allowance...';
            echo json_encode($this->RESPONSE);exit();
        }
               
        if($this->stf_allownce_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Allowance cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." update staff allowance(".$form['title'].", ".$form['amount'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deleteAllowance(){
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
        if(empty($rid)|| $this->stf_allownce_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid allowance...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_allownce_m->get_by_primary($rid);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($this->stf_allownce_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Allowance can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." terminated allowance(".$row->title.", ".$row->amount.") for Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." has stopped receiving allowance(".$row->title.") from next month and onwards.";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Allowance Unsubscription','description'=>$msg));
        //send back the resposne
        $this->RESPONSE['message']='Allowance Terminated Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////DEDUCTION FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterDeductions(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','month','amount','year','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_deduction_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_deduction_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['installment']=month_string($row['month']).', '.$row['year'];
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addDeduction(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','duration','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }
        $total_amount=$form['amount'];
        $member=$this->staff_m->get_by_primary($form['rid']);
        $form['staff_id']=$member->mid;

        $form['amount']=round($total_amount/$form['duration'],2); 
        //check if staff salary is less then installment
        if($member->salary<$form['amount']){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This member has not enough salary to pay the installment of '.$this->ORGSETTINGS->currency_symbol.$form['amount'];
            echo json_encode($this->RESPONSE);exit();
        }
        $form['title']=substr($form['title'], 0,100).'...';
        $form['campus_id']=$this->CAMPUSID;
        $year=$this->stf_deduction_m->year;
        $month=$this->stf_deduction_m->month;
        for($i=0;$i<$form['duration'];$i++){
            $month++;
            if($month>12){$month=1;$year++;}
            $form['month']=$month;$form['year']=$year;
            //save data in database                
            $this->stf_deduction_m->add_row($form);
        }

        //create ledger entry for this advance
        $this->accounts_ledger_m->add_entry($this->CAMPUSID,'Loan issued to '.ucwords(strtolower($member->name)),$this->accounts_m->_SALARY_ADVANCE,$this->accounts_m->_CASH,$total_amount,true);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." issued loan(".$this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$total_amount.") to Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." has received the loan(".$total_amount.") for(".$form['title'].").";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Received Loan','description'=>$msg));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function updateDeduction(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->stf_deduction_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid installment...';
            echo json_encode($this->RESPONSE);exit();
        }
               
        if($this->stf_deduction_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Installment cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated staff installment(".$form['title'].", ".$form['amount'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deleteDeduction(){
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
        if(empty($rid)|| $this->stf_deduction_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid installment...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_deduction_m->get_by_primary($rid);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($this->stf_deduction_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Installment can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //create ledger entry for this advance
        $this->accounts_ledger_m->add_entry($this->CAMPUSID,'Installment('.$row->title.') of '.ucwords(strtolower($member->name)).' terminated.',$this->accounts_m->_CASH,$this->accounts_m->_SALARY_ADVANCE,$row->amount,true);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." terminated installment(".$row->title.", ".$row->amount.") of Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." loan installment(".$row->title.") has been terminated.";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Installment Terminated','description'=>$msg));
        //send back the resposne
        $this->RESPONSE['message']='Installment Terminated Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////ADVANCE FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterAdvance(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','amount','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->stf_advance_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_advance_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addAdvance(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->staff_m->get_by_primary($form['rid']);
        $form['staff_id']=$member->mid;

        //check if total advance amount is larget than staff salary.
        $current_advance=$this->stf_advance_m->get_column_result('amount',array('campus_id'=>$this->CAMPUSID,'staff_id'=>$form["rid"]));
        if( ($form['amount']+$current_advance)>$member->salary){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Total Advance amount is larger then member basic salary. Please use loan feature if you wanna get back amount in multiple installments...';
            echo json_encode($this->RESPONSE);exit();    

        }

        //save data in database   
        $form['campus_id']=$this->CAMPUSID;             
        if($this->stf_advance_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Advance cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." provided an advance (".$form['title'].", ".$this->ORGSETTINGS->currency_symbol.$form['amount'].") to Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." has received an advance amount(".$this->ORGSETTINGS->currency_symbol.$form['amount'].") from upcoming month salary.";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Advance Amount','description'=>$msg));

        //create ledger entry for this advance
        $this->accounts_ledger_m->add_entry($this->CAMPUSID,'Advance amount given to '.ucwords(strtolower($member->name)),$this->accounts_m->_SALARY_ADVANCE,$this->accounts_m->_CASH,$form['amount'],true);

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deleteAdvance(){
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
        if(empty($rid)|| $this->stf_advance_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid advance...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_advance_m->get_by_primary($rid);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($this->stf_advance_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Advance can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." terminated advance(".$row->title.", ".$row->amount.") of Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name."'s advance (".$row->title.") has been terminated by ".$this->LOGIN_USER->name;
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Advance Terminated','description'=>$msg));

        //create ledger entry for this advance
        $this->accounts_ledger_m->add_entry($this->CAMPUSID,'Advance amount of '.ucwords(strtolower($member->name)).' terminated.',$this->accounts_m->_CASH,$this->accounts_m->_SALARY_ADVANCE,$row->amount,true);
        //send back the resposne
        $this->RESPONSE['message']='Advance Terminated Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////HISTORY FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterHistory(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','description','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// filter rows
public function filterSalaryHistory(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','amount','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_pay_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_pay_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////QUALIFICATION FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterQual(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('qualification','year','roll_number','registration_no','program','institute','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_qual_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_qual_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addQual(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','qualification','year','institute');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->staff_m->get_by_primary($form['rid']);
        $form['staff_id']=$member->mid;

        //save data in database  
        $form['campus_id']=$this->CAMPUSID;            
        if($this->stf_qual_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Academic record cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered academic record(".$form['qualification'].") for Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." academic record(".$form['qualification'].") has been registered by ".$this->LOGIN_USER->name;
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Academic Record Registration','description'=>$msg));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function updateQual(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','qualification','year','institute');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->stf_qual_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid academic record...';
            echo json_encode($this->RESPONSE);exit();
        }
               
        if($this->stf_qual_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Academic record cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updateed staff academic record(".$form['qualification'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deleteQual(){
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
        if(empty($rid)|| $this->stf_qual_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid academic record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_qual_m->get_by_primary($rid);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($this->stf_qual_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Academic record can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed academic record(".$row->qualification.") of Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." academic record(".$row->qualification.") has been removed by ".$this->LOGIN_USER->name;
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Academic Record Removed','description'=>$msg));
        //send back the resposne
        $this->RESPONSE['message']='Record Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////AWARDS FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterAwards(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','remarks','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_award_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_award_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addAward(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','award','remarks');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }
        $award=$this->award_m->get_by_primary($form['award']);
        $member=$this->staff_m->get_by_primary($form['rid']);
        $form['staff_id']=$member->mid;
        $form['award_id']=$award->mid;
        $form['title']=$award->title;

        //save data in database
        $form['campus_id']=$this->CAMPUSID;               
        if($this->stf_award_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Award cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." assigned award(".$award->title.") to Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." earned an award(".$award->title.").";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Award Earned','description'=>$msg));


        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function updateAward(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','award','remarks');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->stf_award_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid award...';
            echo json_encode($this->RESPONSE);exit();
        }
        
        $award=$this->award_m->get_by_primary($form['award']);
        $form['award_id']=$award->mid;
        $form['title']=$award->title;   
        if($this->stf_award_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Award cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated staff award(".$award->title.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deleteAward(){
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
        if(empty($rid)|| $this->stf_award_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid award...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_award_m->get_by_primary($rid);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($this->stf_award_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Award can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed award(".$row->title.") of Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." award(".$row->title.") has been removed by ".$this->LOGIN_USER->name;
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Award Removal','description'=>$msg));
        //send back the resposne
        $this->RESPONSE['message']='Award Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////PUNISHMENT FUNCTIONS//////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterPunishments(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','remarks','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_punishment_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_punishment_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addPunishment(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','notice','remarks');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }
        $punishment=$this->punishment_m->get_by_primary($form['notice']);
        $member=$this->staff_m->get_by_primary($form['rid']);
        $form['staff_id']=$member->mid;
        $form['doc_id']=$punishment->mid;
        $form['title']=$punishment->title;

        //save data in database
        $form['campus_id']=$this->CAMPUSID;               
        if($this->stf_punishment_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notice cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." issued notice(".$punishment->title.") to Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." received a notice(".$punishment->title.").";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Notice','description'=>$msg));


        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function updatePunishment(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','notice','remarks');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->stf_punishment_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid notice...';
            echo json_encode($this->RESPONSE);exit();
        }
        
        $punishment=$this->punishment_m->get_by_primary($form['notice']);
        $form['doc_id']=$punishment->mid;
        $form['title']=$punishment->title;   
        if($this->stf_punishment_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notice cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated staff notice(".$punishment->title.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deletePunishment(){
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
        if(empty($rid)|| $this->stf_punishment_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid notice...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_punishment_m->get_by_primary($rid);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($this->stf_punishment_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notice can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." get back notice(".$row->title.") of Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." notice(".$row->title.") has been terminated by ".$this->LOGIN_USER->name;
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Notice Termination','description'=>$msg));
        //send back the resposne
        $this->RESPONSE['message']='Notice Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////ACHIEVEMENT FUNCTIONS//////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterAchievements(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('title','remarks','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->stf_acheivement_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->stf_acheivement_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addAchievement(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','remarks');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->staff_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid staff member...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->staff_m->get_by_primary($form['rid']);
        $form['staff_id']=$member->mid;

        //save data in database  
        $form['campus_id']=$this->CAMPUSID;           
        if($this->stf_acheivement_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Endorsement cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." saved an endorsement(".$form['title'].") for Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." has been endorsed with badge(".$form['title'].").";
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Endorsement','description'=>$msg));


        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function updateAchievement(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','remarks');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->stf_acheivement_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid endorsement...';
            echo json_encode($this->RESPONSE);exit();
        }
         
        if($this->stf_acheivement_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Endorsement cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated staff endorsement(".$form['title'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deleteAchievement(){
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
        if(empty($rid)|| $this->stf_acheivement_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid endorsement...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->stf_acheivement_m->get_by_primary($rid);
        $member=$this->staff_m->get_by_primary($row->staff_id);
        if($this->stf_acheivement_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Endorsement can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." terminated the endorsement(".$row->title.") of Staff(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." endorsement(".$row->title.") has been terminated by ".$this->LOGIN_USER->name;
        $this->stf_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$member->mid,'title'=>'Endorsement Termination','description'=>$msg));
        //send back the resposne
        $this->RESPONSE['message']='Endorsement Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
//////////////////////////////////////////////////////////////////
/////////STAFF ATTENDANCE REPORT FUNCTION/////////////////
//////////////////////////////////////////////////////////////////
// filter rows
public function filterStaffAttendanceEvents(){
        // get input fields into array
        $filter=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post(array("staff"));
        $staff=$this->staff_m->get_row_by_id($form['staff']);
        $filter['staff_id']=$staff->staff_id;
        $this_month=$this->staff_m->month;
        $this_year=$this->staff_m->year;
        $month_limit=3;
        $rows=array();
        $row_counter=0;
        for($i=0;$i<$month_limit;$i++){
            $filter['month']=$this_month;
            $filter['year']=$this_year;
            $staff_row=$this->stf_attendance_m->get_row_by_fields($filter);
            //loop for full month
            for($j=0;$j<31;$j++){
                $day=$j+1;
                $col_num='d'.$day;
                if(!empty($staff_row->$col_num)){
                    $key=$staff_row->$col_num;
                    $title=$key;
                    if($key=='P'){$title='Present';}
                    if($key=='A'){$title='Absent';}
                    $month_val=$this_month;$day_val=$day;
                    if($this_month<10){$month_val='0'.intval($this_month);}if($day<10){$day_val='0'.intval($day);}
                    $single_row=array('title'=>$title,'start'=>$this_year.'-'.$month_val.'-'.$day_val);
                    $rows[$row_counter]=$single_row;
                    $row_counter++;
                }
            }
            ////////////// decrement month////////
            $this_month--;
            if($this_month<1){$this_year--;$this_month=12;}
        }
        $this->RESPONSE['rows']=$rows;
        ///////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $color_green="#43A047";$color_red="#E53935";$color_default="#1E88E5";
            $this->RESPONSE['rows'][$i]['color']=$color_default;
            if($row['title']=='Present'){$this->RESPONSE['rows'][$i]['color']=$color_green;}
            if($row['title']=='Absent'){$this->RESPONSE['rows'][$i]['color']=$color_red;}
            $i++;
        }
              
        echo json_encode( $this->RESPONSE);
}

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	