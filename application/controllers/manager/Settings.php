<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Manager_Controller{

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
        if($this->LOGIN_USER->type == $this->user_m->TYPE_MANAGER || $this->LOGIN_USER->type == $this->user_m->TYPE_ADMIN ){
        }else{
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            

        }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'settings/';
        //load all models for this controller
        $models = array('class_m','class_section_m','class_feepackage_m','class_subject_m','student_m','std_result_m','std_qual_m','staff_m','stf_attendance_m','std_attendance_m','std_subject_final_result_m','period_m','class_timetable_m','stf_role_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		redirect($this->CONT_ROOT.'profile', 'refresh');
	}
    // admin settings
    public function profile($tab=''){
        $this->data['main_content']='settings_profile';   
        $this->data['menu']='settings';           
        $this->data['sub_menu']='settings_profile';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='settings_profile';

        /////////////////////////////////////////////////////////////
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);    
    }

     //save profile pic
    public function upload_picture(){
        //upload artwork file
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->CONT_ROOT.'profile', 'refresh');                    
        }
        $form=$this->input->safe_post(array());
        $file_name=$this->LOGIN_USER->user_id.mt_rand(101,999);  
        $path='./uploads/images/user';
        $data=$this->upload_img('file',$file_name,$path);
        if($data['file_uploaded']==FALSE){
            $this->session->set_flashdata('error', $data['file_error']);
            redirect($this->CONT_ROOT.'profile', 'refresh');
        }
        $nfile_name=$data['file_name'];
        $saveform=array('image'=>$nfile_name);
        $this->user_m->save($saveform,$this->LOGIN_USER->mid);
        $this->session->set_flashdata('success', 'Profile picture upated successfully');
        redirect($this->CONT_ROOT.'profile', 'refresh');           
    
    }
     //save profile pic
    public function upload_logo(){
        //upload artwork file
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'setting', 'refresh');                    
        }
        $file_name='cmplogo_'.mt_rand(1001,9999);
        $path='./uploads/images/logo';
        $data=$this->upload_img('file',$file_name,$path);
        if($data['file_uploaded']==FALSE){
            $this->session->set_flashdata('error', $data['file_error']);
            redirect($this->LIB_CONT_ROOT.'settings/campus', 'refresh');
        }
        $nfile_name=$data['file_name'];
        $this->campus_setting_m->save_setting($this->campus_setting_m->_CAMPUS_LOGO,$nfile_name,$this->CAMPUSID);
        $this->session->set_flashdata('success', 'Image uploaded successfully.');
        redirect($this->LIB_CONT_ROOT.'settings/campus', 'refresh');           
    
    }
    ////////////////////upload file///////////////////////////////
    private function upload_img($file_name='file',$new_name='',$path){  
        $size=isset($this->SETTINGS['max_upload_size']) ? $this->SETTINGS['max_upload_size'] : '800';    //0.8MB
        $allowed_types='jpg|jpeg|png|bmp';
        $upload_file_name=$file_name;    
        $min_width=$this->config->item('app_img_min_width');
        $min_height=$this->config->item('app_img_min_height');
        $upload_data=$this->upload_file($path,$size,$allowed_types,$upload_file_name,$new_name,$min_width,$min_height);
        return $upload_data;
    }   
     //save profile
    public function update_password(){
        //upload artwork file
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->CONT_ROOT.'profile', 'refresh');                    
        }
        $form=$this->input->safe_post(array("password","npassword"));
        if(empty($form['password'])||empty($form['npassword'])){
            $this->session->set_flashdata('error',"Please provide current password and new password...");
            redirect($this->CONT_ROOT.'profile', 'refresh');
            exit;          
        }               
        if($this->LOGIN_USER->password != $this->user_m->hash($form['password'])){
            $this->session->set_flashdata('error',"Invalid current password. Please provide correct existing password...");
            redirect($this->CONT_ROOT.'profile', 'refresh');
            exit;          
        } 
        //save data in database                
        if($this->user_m->save(array('password'=>$this->user_m->hash($form['npassword']) ),$this->LOGIN_USER->mid)===false){
            $this->session->set_flashdata('error',"Password cannot be updated at this time. Please try again later...");
            redirect($this->CONT_ROOT.'profile', 'refresh');
            exit;          
        } 
        $this->session->set_flashdata('success', 'Password updated successfully. ');
        redirect($this->CONT_ROOT.'profile', 'refresh');           
    
    }
    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** SMS HOOKS FUNCTIONS **************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    //show sms hooks for this campus
    public function smshooks($tab=''){
        $this->data['main_content']='settings_smshooks';   
        $this->data['menu']='settings';           
        $this->data['sub_menu']='settings_smshooks';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='settings_smshooks';

        /////////////////////////////////////////////////////////////
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);    
    }
    // filter sms hooks
    public function filterSmsHooks(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->sms_hook_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->sms_hook_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // create row
    public function addSmsHook(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('hook','template','target');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        if($this->sms_hook_m->get_rows(array('campus_id'=>$this->CAMPUSID,'hook'=>$form['hook'],'target'=>$form['target']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You already subscribed to this sms notification.';
            echo json_encode($this->RESPONSE);exit();            
        }
        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        if($this->sms_hook_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notification cannot be subscribed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." subscribed sms notification (".$form['event'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Notification subscription successfull.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateSmsHook(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','template');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        $hook=$this->sms_hook_m->get_by_primary($form['rid']);
        if($this->sms_hook_m->get_rows(array('campus_id'=>$this->CAMPUSID,'hook'=>$hook->hook,'target'=>$hook->target,'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You already subscribed to the sms notification for selected target. Please edit that notification...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //save data in database                
        if($this->sms_hook_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notification cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']=' Notification updated successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // load single row
    public function loadSystemHook(){
        // get input fields into array
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($form['hook'])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide a hook...';
            echo json_encode($this->RESPONSE);exit();
        }
        $this->RESPONSE['output']=$this->sms_hook_m->get_hooks($form['hook']);       
        echo json_encode($this->RESPONSE);
    }
    // update row
    public function deleteSmsHook(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->sms_hook_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid notification...';
            echo json_encode($this->RESPONSE);exit();
        }

        if($this->sms_hook_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notification can not be unsubscribed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Notification Canceled Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** Campus Settings FUNCTIONS **************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    //show sms hooks for this campus
    public function campus($tab=''){
        $this->data['main_content']='settings_campus';   
        $this->data['menu']='settings';           
        $this->data['sub_menu']='settings_campus';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='settings_campus';

        /////////////////////////////////////////////////////////////
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);    
    }
    // filter sms hooks
    public function filterCampusSettings(){
        $this->RESPONSE['output']=$this->CAMPUSSETTINGS;
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // update row
    public function updateCampusSettings(){

        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //validate settings
        $settings=array('bank_name'=>$form['bank_name'],'bank_title'=>$form['bank_title'],'bank_account'=>$form['bank_account'],'font_scale'=>$form['font_scale'],'month_opass_percent'=>$form['month_opass_percent'],'final_opass_percent'=>$form['final_opass_percent'],'std_fee_type'=>$form['std_fee_type']);
        if($form['prm_portal_staff_edit']=='true'){$settings['prm_portal_staff_edit']=1;}else{$settings['prm_portal_staff_edit']=0;}
        if($form['narration']=='true'){$settings['narration']=1;}else{$settings['narration']=0;}
        if($form['get_late_fee_fine']=='true'){
            $settings['late_fee_fine_type']=$form['late_fee_fine_type'];
            $settings['late_fee_fine']=$form['late_fee_fine'];
        }else{
            $settings['late_fee_fine']=0;
        }
        //save data in database                
        if($this->campus_setting_m->save_settings_array($settings,$this->CAMPUSID)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Settings cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']=' Settings updated successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // filter sms hooks
    public function filterSections(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_section_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_section_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $faculty=$this->staff_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['password']='';
            $this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];
            $this->RESPONSE['rows'][$i]['incharge']=$faculty[$row['incharge_id']];
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // create row
    public function addSection(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('name','class_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        if($this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'name'=>$form['name'],'class_id'=>$form['class_id']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Section already exist. Please choose another name...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //save data in database    
        $form['campus_id']=$this->CAMPUSID;            
        if($this->class_section_m->add_row($form)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created new campus section (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Created successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateSection(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','class_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        if($this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'name'=>$form['name'],'class_id'=>$form['class_id'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Section already exist. Please choose another name...';
            echo json_encode($this->RESPONSE);exit();            
        }
        $row=$this->class_section_m->get_by_primary($form['rid']);
        //save data in database                
        if($this->class_section_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        //update students section
        $this->student_m->save(array('section'=>$form['name']),array('campus_id'=>$this->CAMPUSID,'section'=>$row->name));

        //send back the resposne
        $this->RESPONSE['message']='Updated successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function deleteSection(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->class_section_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        $session=$this->session_m->getActiveSession();
        $row=$this->class_section_m->get_by_primary($rid);
        if($this->student_m->get_rows(array('section'=>$row->name,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Section alloted to some students. Please first migrate them to another section...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->class_section_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Section Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** FEEPACKAGe FUNCTIONS *******************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */


    // filter rows
    public function filterFeePackages(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        // isset($form['gender']) && !empty($form['gender']) ? $filter['gender']= $form['gender'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->class_feepackage_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_feepackage_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // create row
    public function addFeePackage(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('name','class_id','amount','obt_min_percent','obt_max_percent');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'name'=>$form['name'],'class_id'=>$form['class_id']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Package with this name already exist. Please choose another name...';
            echo json_encode($this->RESPONSE);exit();            
        }  
        if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$form['obt_min_percent'],'obt_max_percent <='=>$form['obt_max_percent'],'class_id'=>$form['class_id']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Policy already exist for this percentage. Please choose another...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$form['obt_min_percent'],'obt_min_percent <='=>$form['obt_max_percent'],'class_id'=>$form['class_id']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Policy already exist for this percentage. Please choose another...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //save data in database     
        $form['campus_id']=$this->CAMPUSID;           
        if($this->class_feepackage_m->add_row($form)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created new fee package policy (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg));

        //send back the resposne  
        $this->RESPONSE['message']='Created successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateFeePackage(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','class_id','amount','obt_min_percent','obt_max_percent');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'name'=>$form['name'],'class_id'=>$form['class_id'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Package already exist. Please choose another name...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$form['obt_min_percent'],'obt_max_percent <='=>$form['obt_max_percent'],'class_id'=>$form['class_id'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Policy already exist for this percentage. Please choose another...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$form['obt_min_percent'],'obt_min_percent <='=>$form['obt_max_percent'],'class_id'=>$form['class_id'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Policy already exist for this percentage. Please choose another...';
            echo json_encode($this->RESPONSE);exit();            
        }
        
        $row=$this->class_feepackage_m->get_by_primary($form['rid']);
        //save data in database                
        if($this->class_feepackage_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        //update students section
        // $this->student_m->save(array('section'=>$form['name']),array('campus_id'=>$this->CAMPUSID,'section'=>$row->name));

        //send back the resposne
        $this->RESPONSE['message']='Updated successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function deleteFeePackage(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->class_feepackage_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->class_feepackage_m->get_by_primary($rid);
        // if($this->student_m->get_rows(array('section'=>$row->name,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID),'',true)>0){
        //     $this->RESPONSE['error']=TRUE;
        //     $this->RESPONSE['message']='Section alloted to some students. Please first migrate them to another section...';
        //     echo json_encode($this->RESPONSE);exit();
        // }
        // $row=$this->staff_m->get_by_primary($rid);
        if($this->class_feepackage_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }


    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** AJAX FUNCTIONS *******************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    // filter rows
    public function filterPeriods(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','from_time','to_time','type');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['search']) ? $params['like']=$like : '';
        $params['orderby']='sort_order ASC';
        $this->RESPONSE['rows']=$this->period_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->period_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // create row
    public function addPeriod(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('name','from_time','to_time','total_time','type');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        if($this->period_m->get_rows(array('campus_id'=>$this->CAMPUSID,'name'=>$form['name']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Period already exist. Please choose another name...';
            echo json_encode($this->RESPONSE);exit();            
        }
        $number=$this->period_m->get_rows(array('campus_id'=>$this->CAMPUSID),'',true);
        $form['number']=$number+1;
        $form['sort_order']=$number+1;
        //save data in database   
        $form['campus_id']=$this->CAMPUSID;             
        if($this->period_m->add_row($form)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created new study period (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Created successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updatePeriod(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','from_time','to_time','total_time','type','sort_order');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        //save data in database                
        if($this->period_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 
        //send back the resposne
        $this->RESPONSE['message']='Updated successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function deletePeriod(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->period_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->period_m->get_by_primary($rid);
        if($this->class_timetable_m->get_rows(array('period_id'=>$row->mid,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Period alloted to some students or staff. Please first migrate them to another periods...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->period_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Period Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** Session Settings FUNCTIONS **************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    //show sms hooks for this campus
    public function promotion($tab=''){
        $this->data['main_content']='settings_promotion';   
        $this->data['menu']='settings';           
        $this->data['sub_menu']='settings_promotion';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='settings_promotion';

        /////////////////////////////////////////////////////////////
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);    
    }
    // filter sms hooks
    public function filterStudents(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','roll_no','father_name');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['status']) && !empty($form['status']) ? $filter['promotion_status']= $form['status'] : '';
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->student_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['password']='';
            if($row['class_id']>0){$this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];}
            ///////////////////////////////////////////////////////////////////////////////////////////
            $sub_filter['student_id']=$row['mid'];
            $total_marks=$this->std_subject_final_result_m->get_column_result('total_marks',$sub_filter);
            $obt_marks=$this->std_subject_final_result_m->get_column_result('obt_marks',$sub_filter);    
            $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            $this->RESPONSE['rows'][$i]['performance']=$std_result;              
            $this->RESPONSE['rows'][$i]['total_subjects']=$this->class_subject_m->get_rows(array('class_id'=>$row['class_id'],'campus_id'=>$this->CAMPUSID),'',true);               
            $this->RESPONSE['rows'][$i]['passed_subjects']=$this->std_subject_final_result_m->get_rows(array('session_id'=>$session->mid,'student_id'=>$row['mid'],'status'=>$this->std_subject_final_result_m->STATUS_PASS,'class_id'=>$row['class_id'],'campus_id'=>$this->CAMPUSID),'',true);              
            ////////////////////////////////////////////////////////////////////////////////////////////
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
    // edit row
    public function changeStudentStatus(){ 
            //check for demo   
            if($this->IS_DEMO){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
                echo json_encode($this->RESPONSE);exit();
            }
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
            if(empty($form['rid'])||$this->student_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid student...';
                echo json_encode($this->RESPONSE);exit();
            }

            //save data in database
            if($this->student_m->save(array('promotion_status'=>$form['status']),$form['rid'])===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Status can not be updated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }

            //send back repsonse
            $this->RESPONSE['message']='Status Updated Successfully...';
            echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateFinalResult(){
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }

        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $students=$this->student_m->get_rows($filter,$params);

        foreach($students as $row){
            $status=$this->student_m->STATUS_PASS;
            //mark fail if failed in any subject            
            $total_subjects=$this->class_subject_m->get_rows(array('class_id'=>$row['class_id'],'campus_id'=>$this->CAMPUSID),'',true);               
            $passed_subjects=$this->std_subject_final_result_m->get_rows(array('session_id'=>$session->mid,'student_id'=>$row['mid'],'status'=>$this->std_subject_final_result_m->STATUS_PASS,'class_id'=>$row['class_id'],'campus_id'=>$this->CAMPUSID),'',true);
            if($total_subjects != $passed_subjects){
                $status=$this->student_m->STATUS_FAIL;
            }  

            $this->student_m->save(array('promotion_status'=>$status),$row['mid']);
        }
        //send back the resposne
        $this->RESPONSE['message']='Result updated successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // filter sms hooks
    public function promoteStudents(){
        
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        //check for necessary required data   
        $required=array('session_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    

        ////////////////////////////////////////////////////////////
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $params['orderby']='class_id ASC';
        $students=$this->student_m->get_rows($filter,$params);
        $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;$j=0;
        foreach($students as $row){
            $class=$this->class_m->get_by_primary($row['class_id']);
            $student_data=array('promotion_status'=>'');
            //change the student session only if he is going to promote to an active class
            if($row['promotion_status']==$this->student_m->STATUS_PASS && $class->promotion_id>0){
                $i++;
                $student_data['class_id']=$class->promotion_id;
                $student_data['session_id']=$form['session_id'];
                $this->student_m->save($student_data,$row['mid']);
                //////save result history in student results    
                $sub_filter['student_id']=$row['mid'];        
                $total_marks=$this->std_subject_final_result_m->get_column_result('total_marks',$sub_filter);
                $obt_marks=$this->std_subject_final_result_m->get_column_result('obt_marks',$sub_filter);   
                $this->std_result_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$row['mid'],'session_id'=>$session->mid,'class_id'=>$row['class_id'],'class'=>$class->title,'session'=>$session->title,'status'=>$row['promotion_status'],'total_marks'=>$total_marks,'obt_marks'=>$obt_marks));
                $this->std_qual_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$row['mid'],'class'=>$class->title,'session'=>$session->title,'status'=>$row['promotion_status'],'total_marks'=>$total_marks,'obtained_marks'=>$obt_marks,'roll_number'=>$row['roll_no']));
            }else{
                $j++;
            }
        }
        /////////////////////////////////////////////////////////////
        $this->RESPONSE['message']=$i.' students promoted to selected session. Still there are '.$j.' students left. If every things goes right then ask administrator to activate the next session.';
        echo json_encode( $this->RESPONSE);        
    }


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	