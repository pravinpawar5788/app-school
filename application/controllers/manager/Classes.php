<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classes extends Manager_Controller{

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
        if($this->LOGIN_USER->prm_class<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'classes/';
        //load all models for this controller
        $models = array('stf_role_m','class_m','class_section_m','class_subject_m','class_subject_lesson_m','class_subject_faculty_m','class_subject_progress_m','class_subject_qbank_m','student_m','staff_m','std_attendance_m','std_history_m','std_fee_history_m','std_fee_entry_m','std_fee_voucher_m','class_timetable_m','period_m','std_subject_homework_m','exam_term_m','std_term_result_m','std_subject_final_result_m');
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
	    //organization must have a teacher role
	    if($this->stf_role_m->get_rows(array('title'=>'teacher'),'',true)<1){
	        $this->stf_role_m->add_row(array('title'=>'teacher'));
	    }
		
		$this->data['main_content']='classes';	
		$this->data['menu']='classes';			
		$this->data['sub_menu']='classes';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='classes';
        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    ///////timetable function/////////////
    public function timetable($tab=''){
        
        $this->data['main_content']='class_timetable';    
        $this->data['menu']='classes';            
        $this->data['sub_menu']='class_timetable';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='class_timetable'; 
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }


    ///////Profile function/////////////
    public function profile($rid='',$tab=''){
        if(empty($rid) || $this->class_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid class');
            redirect($this->LIB_CONT_ROOT.'classes', 'refresh'); 
        }
        
        $this->data['main_content']='class_profile';    
        $this->data['menu']='classes';            
        $this->data['sub_menu']='class_profile';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='class_profile';
        $class=$this->class_m->get_by_primary($rid);   
        $this->data['class']=$class;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }

    ///////Curriculum function/////////////
    public function curriculum($rid='',$tab=''){
        if(empty($rid) || $this->class_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid class');
            redirect($this->LIB_CONT_ROOT.'classes', 'refresh'); 
        }
        
        $this->data['main_content']='class_subjects';    
        $this->data['menu']='classes';            
        $this->data['sub_menu']='class_subjects';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='class_subjects';
        $class=$this->class_m->get_by_primary($rid);   
        $this->data['class']=$class;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
    ///////subject funcations/////////////
    public function subject($rid='',$tab=''){
        if(empty($rid) || $this->class_subject_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid subject');
            redirect($this->LIB_CONT_ROOT.'classes', 'refresh'); 
        }
        
        $this->data['main_content']='class_subject';    
        $this->data['menu']='classes';            
        $this->data['sub_menu']='class_subject';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='class_subject';
        $record=$this->class_subject_m->get_by_primary($rid);   
        $this->data['record']=$record;
        $class=$this->class_m->get_by_primary($record->class_id);   
        $this->data['class']=$class;
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
            case 'report':{
                $this->data['main_content']='class_report';    
                $this->data['print_page_title']='Classes Report'; 

            }
            break;
            case 'details':{
                $this->data['main_content']='class_details';    
                $this->data['print_page_title']='Class Students List'; 

            }
            break;
            case 'attendance':{
                $this->data['main_content']='class_attendance';    
                $this->data['print_page_title']='Class Attendance Report'; 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'classes', 'refresh');                       
            }break;
        }
        if($this->session->flashdata('back_url')){$this->data['back_url']=$this->session->flashdata('back_url');}    
        $this->load->view($this->LIB_VIEW_DIR.'printing/master', $this->data);   
    }


     //save profile pic
    public function upload_picture(){
        //upload artwork file
        $form=$this->input->safe_post(array("user"));
        $student=$this->student_m->get_by_primary($form['user']);
            $file_name=$student->student_id.mt_rand(101,999);
            $data=$this->upload_img('file',$file_name);
            if($data['file_uploaded']==FALSE){
                $this->session->set_flashdata('error', $data['file_error']);
                redirect($this->CONT_ROOT.'profile/'.$student->mid, 'refresh');
            }
            $nfile_name=$data['file_name'];
            $saveform=array('image'=>$nfile_name);
            $this->student_m->save($saveform,$student->mid);
            $this->session->set_flashdata('success', 'Profile picture uploaded successfully for '.$student->name);
            redirect($this->CONT_ROOT.'profile/'.$student->mid, 'refresh');           
    
    }
    ////////////////////upload file///////////////////////////////
    private function upload_img($file_name='file',$new_name=''){   
        $path='./assets/uploads/files/'.$this->ORG->organization_id.'/images/student/profile';
        $size='800';    //0.8MB
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
        $search=array('title','date');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='display_order ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $filter['class_id']=$row['mid'];
            $this->RESPONSE['rows'][$i]['incharge']=$this->staff_m->get_by_primary($row['incharge_id'])->name;
            $this->RESPONSE['rows'][$i]['students']=$this->student_m->get_rows($filter,'',true);
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
        $required=array('title','incharge_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        //check if class already exist
        if($this->class_m->get_rows(array('title'=>$form['title'],'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Class already exist. Please choose an other name...';
            echo json_encode($this->RESPONSE);exit();
        }
        $order_filter=array('campus_id'=>$this->CAMPUSID);
        $form['display_order']=intval($this->class_m->get_column_result('display_order',$order_filter,'max'))+1;

        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        $rid=$this->class_m->add_row($form);              
        if($rid==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Class cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new class (".$form['title'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Class registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function update(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','incharge_id','display_order');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 

        if(isset($form['password'])){
            unset($form['password']);
        }   

        //save data in database                
        if($this->class_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Class cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated class (".$form['title'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function load(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('name','roll_no');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->class_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid class...';
            echo json_encode($this->RESPONSE);exit();
        }

        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        $params['orderby']="section ASC, roll_no ASC";
        //////////////////////////////////////////////////////////////////////////////////////
        isset($form['section']) && !empty($form['section']) ? $filter['section_id']= $form['section'] : '';
        isset($form['search']) && !empty($form['search']) ? $params['like']=$like : '';
        $row=$this->class_m->get_by_primary($rid);
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $filter['class_id']=$row->mid;
        $students=$this->student_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        $sections=$this->class_section_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID));
        $i=0;        
        foreach($students as $std){
            if($std['section_id']>0){
                $students[$i]['section']=$sections[$std['section_id']];                
            }
            $i++;
        }
        $row->students=$students;
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
        if(empty($rid)|| $this->class_m->get_rows(array('mid'=>$rid,),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid class...';
            echo json_encode($this->RESPONSE);exit();
        }
        //check if class has students   
        $session_id=$this->session_m->getActiveSession()->mid;
        if($this->student_m->get_rows(array('class_id'=>$rid,'session_id'=>$session_id),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Class contains active students. First change the student class then try to delelte the class.';
            echo json_encode($this->RESPONSE);exit();
        }

        // $row=$this->staff_m->get_by_primary($rid);
        if($this->class_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Class can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Class Terminated Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function filterClassSubjects(){
        // get input fields into array
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['rows']=$this->class_subject_m->get_rows(array('campus_id'=>$this->CAMPUSID,'class_id'=>$form['class_id']),array('orderby'=>'display_order ASC') );
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    public function filterClassSections(){
        // get input fields into array
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['rows']=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'class_id'=>$form['class_id']),array('orderby'=>'name ASC') );
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    public function filterClassFee(){
        // get input fields into array
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['fee']=$this->class_m->get_by_primary($form['class_id'])->fee;
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////



    // create row
    public function createFeeVoucher(){
        // get input fields into array   
        $this->load->model(array('std_fee_entry_m'));    
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','type','title','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }

        $session_id=$this->session_m->getActiveSession()->mid;
        $students=$this->student_m->get_rows(array('class_id'=>$form['rid'],'session_id'=>$session_id,'campus_id'=>$this->CAMPUSID,'status'=>$this->student_m->STATUS_ACTIVE) );
        foreach($students as $student){
                $entry=array('student_id'=>$student['mid'],'amount'=>$form['amount'],'remarks'=>$form['title'],'type'=>$form['type'],'ledger_id'=>-1,'operation'=>$this->std_fee_entry_m->OPT_PLUS);

                $this->std_fee_entry_m->add_row($entry);
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." saved a class fee record for next month.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));


        //send back the resposne  
        $this->RESPONSE['message']='Record saved successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////


    // filter rows
    public function filterSubjects(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','code','description');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='name ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_subject_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addSubject(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('name','class_id','passing_percentage');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        //check if already exist
        if($this->class_subject_m->get_rows(array('name'=>$form['name'],'class_id'=>$form['class_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Subject already exist. Please choose an other name...';
            echo json_encode($this->RESPONSE);exit();
        }   
        $order_filter=array('class_id'=>$form['class_id'],'campus_id'=>$this->CAMPUSID);
        $form['display_order']=intval($this->class_subject_m->get_column_result('display_order',$order_filter,'max'))+1;

        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        if($this->class_subject_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Subject cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new class subject (".$form['name']." ".$form['code'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Subject registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateSubject(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','passing_percentage');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->class_subject_m->get_by_primary($form['rid']);
        //check if already exist
        if($this->class_subject_m->get_rows(array('mid <>'=>$form['rid'],'name'=>$form['name'],'class_id'=>$row->class_id,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Subject already exist. Please choose an other name...';
            echo json_encode($this->RESPONSE);exit();
        }   
        //save data in database                
        if($this->class_subject_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Subject cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated subject (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function loadSubject(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->class_subject_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid class...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->class_subject_m->get_by_primary($rid);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }
    // update row
    public function deleteSubject(){
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
        if(empty($rid)|| $this->class_subject_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid subject...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->class_subject_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Subject can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        $session=$this->session_m->getActiveSession();
        $this->class_subject_lesson_m->delete(null, array('subject_id'=>$rid,'campus_id'=>$this->CAMPUSID));
        $this->class_subject_qbank_m->delete(null, array('subject_id'=>$rid,'campus_id'=>$this->CAMPUSID));
        $this->class_subject_faculty_m->delete(null, array('subject_id'=>$rid,'campus_id'=>$this->CAMPUSID));
        $this->class_subject_progress_m->delete(null, array('subject_id'=>$rid,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
        //send back the resposne
        $this->RESPONSE['message']='Subject Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////


    // filter rows
    public function filterTimetable(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('day');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : exit;
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_timetable_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_timetable_m->get_rows($filter,'',true);
        $this->RESPONSE['periods']=$this->period_m->get_rows(array('campus_id'=>$this->CAMPUSID),array('orderby'=>'sort_order ASC'));
        $this->RESPONSE['days']=array('monday','tuesday','wednesday','thursday','friday','saturday');
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['periods'] as $p){
            $subjects=array();
            $teachers=array();
            $j=0;
            foreach($this->RESPONSE['days'] as $day){                
                $timetable=$this->class_timetable_m->get_by(array('campus_id'=>$this->CAMPUSID,'class_id'=>$form['class_id'],'period_id'=>$p['mid'],'day'=>$day),true);
                if(empty($timetable)){
                    $subjects[$j]['mid']='';
                    $subjects[$j]['name']='---';
                    $subjects[$j]['teacher']='***';
                }else{              
                    $subject=$this->class_subject_m->get_by_primary($timetable->subject_id);
                    $teacher=$this->staff_m->get_by_primary($timetable->teacher_id);

                    $subjects[$j]['mid']=$timetable->mid;
                    $subjects[$j]['name']=$subject->name;
                    $subjects[$j]['teacher']=$teacher->name;

                }
                $j++;
            }
            $this->RESPONSE['periods'][$i]['subjects']=$subjects;
            $i++;
        }
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
        $required=array('day','class_id','period_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        $period=$this->period_m->get_by_primary($form['period_id']);
        if($period->type==$this->period_m->TYPE_PERIOD){
            if(!isset($form['subject_id']) || empty($form['subject_id'])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please select subject...';
                echo json_encode($this->RESPONSE);exit();                
            }
            if(!isset($form['teacher_id']) || empty($form['teacher_id'])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please select teacher...';
                echo json_encode($this->RESPONSE);exit();                
            }
            // check if already have period
            if($this->class_timetable_m->get_rows(array('day'=>$form['day'],'period_id'=>$form['period_id'],'class_id'=>$form['class_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $time_table=$this->class_timetable_m->get_by(array('day'=>$form['day'],'period_id'=>$form['period_id'],'class_id'=>$form['class_id'],'campus_id'=>$this->CAMPUSID),true );
                $class=$this->class_m->get_by_primary($time_table->class_id);
                $subject=$this->class_subject_m->get_by_primary($time_table->subject_id);
                $staff=$this->staff_m->get_by_primary($time_table->teacher_id);
                $this->RESPONSE['message']='Oops! '.$class->title.' class is already reading '.$subject->name.' from '.$staff->name.' at this time.';
                echo json_encode($this->RESPONSE);exit();
            }  
            // check if teacher already have period
            if($this->class_timetable_m->get_rows(array('day'=>$form['day'],'period_id'=>$form['period_id'],'teacher_id'=>$form['teacher_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $time_table=$this->class_timetable_m->get_by(array('day'=>$form['day'],'period_id'=>$form['period_id'],'teacher_id'=>$form['teacher_id'],'campus_id'=>$this->CAMPUSID),true );
                $class=$this->class_m->get_by_primary($time_table->class_id);
                $subject=$this->class_subject_m->get_by_primary($time_table->subject_id);
                $staff=$this->staff_m->get_by_primary($time_table->teacher_id);
                $this->RESPONSE['message']='Oops! '.$staff->name.' is teaching '.$subject->name.' to class '.$class->title.' at this time.';
                echo json_encode($this->RESPONSE);exit();
            }   
        }

        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        if($this->class_timetable_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Period cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }


        //check if already exist
        if($this->class_subject_faculty_m->get_rows(array('staff_id'=>$form['teacher_id'],'subject_id'=>$form['subject_id'],'campus_id'=>$this->CAMPUSID),'',true)<1 && $period->type==$this->period_m->TYPE_PERIOD){
            $this->class_subject_faculty_m->add_row(array('staff_id'=>$form['teacher_id'],'subject_id'=>$form['subject_id'],'campus_id'=>$this->CAMPUSID));
        }

        //send back the resposne  
        $this->RESPONSE['message']='Assigned successfully.';
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
        if(empty($rid)|| $this->class_timetable_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->class_timetable_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////



    //send sms to all student of the class
    public function sendSms(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary fields   
        $required=array('message','rid');
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
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $filter['class_id']=$form['rid'];
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
            switch (strtolower($form['target'])) {
                case 'guardian':{
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$row['guardian_mobile'],'message'=>$sms,'priority'=>5));
                }
                break;   
                case 'student':{
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$row['mobile'],'message'=>$sms,'priority'=>5));
                }
                break;                   
                default:{
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$row['guardian_mobile'],'message'=>$sms,'priority'=>5));
                    $this->sms_history_m->add_row(array('mobile'=>$row['mobile'],'message'=>$sms,'priority'=>5));
                }
                break;
            }
        }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']="SMS Notification sent to ".count($rows)." Students...";
        echo json_encode( $this->RESPONSE);        
    }

    //send sms to single student of the class
    public function sendSingleSms(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary fields   
        $required=array('message','rid');
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
        $row=$this->student_m->get_by_primary($form['rid']);        
        //check there is enough sms credits in account
        if($this->smspoint->get_remaining_sms()<count($rows)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Insufficient sms credits. Please recharge your account at vendor website...';
            echo json_encode($this->RESPONSE);exit();
        }
        $message=htmlspecialchars_decode($form['message']);
            //conversion keys
            $key_vars=array(
                    '{NAME}'=>$row->name,
                    '{GUARDIAN}'=>$row->guardian_name
                );
            ////////////////////////////////////////
            switch (strtolower($form['target'])) {
                case 'guardian':{
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$row->guardian_mobile,'message'=>$sms,'priority'=>5));
                }
                break;   
                case 'student':{
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$row->mobile,'message'=>$sms,'priority'=>5));
                }
                break;                   
                default:{
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->add_row(array('mobile'=>$row->guardian_mobile,'message'=>$sms,'priority'=>5));
                    $this->sms_history_m->add_row(array('mobile'=>$row->mobile,'message'=>$sms,'priority'=>5));
                }
                break;
            }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']="SMS Notification sent to Student...";
        echo json_encode( $this->RESPONSE);        
    }

    // update row
    public function updateRollNo(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','roll_no');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 

        $filter=array('mid <>'=>$form['rid'],'roll_no'=>intval($form['roll_no']),'campus_id'=>$this->CAMPUSID);
        // //save data in database           
        $data=array('roll_no'=>intval($form['roll_no']));  
        if(!empty($form['section'])){
            $section=$this->class_section_m->get_by_primary($form['section']);
            $filter['section_id']=$section->mid;
            $data['section_id']=$section->mid;
            $data['section']=$section->name;
        }   

        if($this->student_m->get_rows($filter,'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Roll Number already assigned to other student. Please choose an other roll number...';
            echo json_encode($this->RESPONSE);exit();
        }  

        if($this->student_m->save($data,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Roll number cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']=' Roll Number and Section Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterLessons(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','description');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['subject_id']) ? $filter['subject_id']= $form['subject_id'] : '';
        isset($form['chapter']) && !empty($form['chapter']) ? $filter['chapter_number']= $form['chapter'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='chapter_number ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_subject_lesson_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_lesson_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }

    // create row
    public function addLesson(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('name','subject_id','chapter_number');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        //check if already exist
        if($this->class_subject_lesson_m->get_rows(array('name'=>$form['name'],'subject_id'=>$form['subject_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Lesson already exist. Please choose an other name...';
            echo json_encode($this->RESPONSE);exit();
        }   
        $order_filter=array('subject_id'=>$form['subject_id'],'campus_id'=>$this->CAMPUSID);
        $form['display_order']=intval($this->class_subject_lesson_m->get_column_result('display_order',$order_filter,'max'))+1;

        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        if($this->class_subject_lesson_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Lesson cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //send back the resposne  
        $this->RESPONSE['message']='Lesson registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateLesson(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','chapter_number');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->class_subject_lesson_m->get_by_primary($form['rid']);
        //check if already exist
        if($this->class_subject_lesson_m->get_rows(array('mid <>'=>$form['rid'],'name'=>$form['name'],'subject_id'=>$row->subject_id,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Lesson already exist. Please choose an other name...';
            echo json_encode($this->RESPONSE);exit();
        }  
        //save data in database                
        if($this->class_subject_lesson_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Lesson cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function deleteLesson(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->class_subject_lesson_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid lesson...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->class_subject_lesson_m->get_by_primary($rid);
        if($this->class_subject_lesson_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Lesson can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        $session=$this->session_m->getActiveSession();
        $this->class_subject_qbank_m->delete(null, array('lesson_id'=>$row->mid,'subject_id'=>$row->subject_id,'campus_id'=>$this->CAMPUSID));
        $this->class_subject_progress_m->delete(null, array('lesson_id'=>$row->mid,'subject_id'=>$row->subject_id,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
        //send back the resposne
        $this->RESPONSE['message']='Lesson Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterChapterLessons(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['subject_id']) ? $filter['subject_id']= $form['subject_id'] : '';
        isset($form['chapter']) && !empty($form['chapter']) ? $filter['chapter_number']= $form['chapter'] : '';
        ///////////////////////////////////////////
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='name ASC, mid DESC';
        $this->RESPONSE['rows']=$this->class_subject_lesson_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        if(isset($form['target']) && $form['target']=='progress'){
            $i=0;
            $session=$this->session_m->getActiveSession();
            foreach($this->RESPONSE['rows'] as $row){
                if($this->class_subject_progress_m->get_rows(array('session_id'=>$session->mid,'chapter'=>$form['chapter'],'lesson_id'=>$row['mid'],'subject_id'=>$form['subject_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
                    unset($this->RESPONSE['rows'][$i]);
                }
                $i++;
            }
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }

    // filter rows
    public function filterProgress(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('status','start_date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['subject_id']) ? $filter['subject_id']= $form['subject_id'] : '';
        isset($form['lesson_id']) ? $filter['lesson_id']= $form['lesson_id'] : '';
        isset($form['chapter']) && !empty($form['chapter']) ? $filter['chapter']= $form['chapter'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='chapter ASC, lesson_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_subject_progress_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_progress_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['lesson']=$this->class_subject_lesson_m->get_by_primary($row['lesson_id'])->name;
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }

    // create row
    public function addProgress(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('subject_id','lesson_id','chapter','start_date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        $session=$this->session_m->getActiveSession(); 
        //check if already exist
        if($this->class_subject_progress_m->get_rows(array('session_id'=>$session->mid,'chapter'=>$form['chapter'],'lesson_id'=>$form['lesson_id'],'subject_id'=>$form['subject_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Lesson already Planned. Please choose another lesson...';
            echo json_encode($this->RESPONSE);exit();
        }   
        //save data in database
        $form['status']=$this->class_subject_progress_m->STATUS_PENDING;
        $form['session_id']=$session->mid;
        $form['campus_id']=$this->CAMPUSID;
        if($this->class_subject_progress_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Planning cannot be made at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //send back the resposne  
        $this->RESPONSE['message']='Planning saved successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateProgress(){
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
        $row=$this->class_subject_progress_m->get_by_primary($form['rid']);
        if($form['status']==$this->class_subject_progress_m->STATUS_COMPLETED){
            $form['complete_date']=$this->class_subject_progress_m->date;
        }
        if($form['status']==$this->class_subject_progress_m->STATUS_PENDING){
            $form['complete_date']='';
        }
        //save data in database                
        if($this->class_subject_progress_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Planning cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function deleteProgress(){
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
        if(empty($rid)|| $this->class_subject_progress_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->class_subject_progress_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Planning can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Planning Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterFaculty(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('staff_id');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['subject_id']) ? $filter['subject_id']= $form['subject_id'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_subject_faculty_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_faculty_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $member=$this->staff_m->get_by_primary($row['staff_id']);
            $this->RESPONSE['rows'][$i]['name']=$member->name;
            $this->RESPONSE['rows'][$i]['subject']=$member->favourite_subject;
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
    // create row
    public function addFaculty(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('subject_id','staff_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if already exist
        if($this->class_subject_faculty_m->get_rows(array('staff_id'=>$form['staff_id'],'subject_id'=>$form['subject_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Teacher already assigned to this subject. Please choose another teacher...';
            echo json_encode($this->RESPONSE);exit();
        }   
        $form['campus_id']=$this->CAMPUSID;
        if($this->class_subject_faculty_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Tacher cannot be assigned at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //send back the resposne  
        $this->RESPONSE['message']='Assigned saved successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateFaculty(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','staff_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database                
        if($this->class_subject_faculty_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Assigning cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function deleteFaculty(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->class_subject_faculty_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->class_subject_faculty_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Teacher can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Teacher Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterQbank(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('question','detail','answer');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['subject_id']) ? $filter['subject_id']= $form['subject_id'] : '';
        isset($form['lesson_id']) ? $filter['lesson_id']= $form['lesson_id'] : '';
        isset($form['chapter']) && !empty($form['chapter']) ? $filter['chapter']= $form['chapter'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='chapter ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->class_subject_qbank_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_qbank_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['lesson']=$this->class_subject_lesson_m->get_by_primary($row['lesson_id'])->name;
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
    // create row
    public function addQbank(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('subject_id','lesson_id','chapter','question','marks','type');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $form['campus_id']=$this->CAMPUSID;
        if($this->class_subject_qbank_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Question cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //send back the resposne  
        $this->RESPONSE['message']='Question saved successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateQbank(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','question');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database                
        if($this->class_subject_qbank_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Question cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function deleteQbank(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->class_subject_qbank_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->class_subject_qbank_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterHomework(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('homework','date');
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['class_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            $student=$this->student_m->get_by_primary($form['rid']);
            $filter['session_id']=$this->session_m->getActiveSession()->mid;
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            // isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_subject_homework_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_subject_homework_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            $subjects=$this->class_subject_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID));
            $sections=$this->class_section_m->get_values_array('mid','name',array('campus_id'=>$this->CAMPUSID));
            $i=0;
            foreach($this->RESPONSE['rows'] as $row){
                $this->RESPONSE['rows'][$i]['subject']=$subjects[$row['subject_id']];
                $this->RESPONSE['rows'][$i]['homework']=html_entity_decode(htmlspecialchars_decode($row['homework']));
                if($row['section_id']>0){
                    $this->RESPONSE['rows'][$i]['section']=$sections[$row['section_id']];                    
                }else{
                    $this->RESPONSE['rows'][$i]['section']='';  
                }
                $i++;
            }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////     
            // $student=$this->student_m->get_by_primary($form['rid']);  
            // $this->RESPONSE['total_advance']=$student->advance_amount;
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addHomeWork(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','homework');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        //check if already exist
        if($this->class_subject_m->get_rows(array('mid'=>$form['rid'],'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid subject...';
            echo json_encode($this->RESPONSE);exit();
        }   

        $session=$this->session_m->getActiveSession();
        $row=$this->class_subject_m->get_by_primary($form['rid']);
        $class=$this->class_m->get_by_primary($row->class_id);
        $data=array('subject_id'=>$row->mid,'class_id'=>$row->class_id,'session_id'=>$session->mid,'jd'=>$this->class_subject_m->todayjd);


        $assigned_filter=array('campus_id'=>$this->CAMPUSID,'subject_id'=>$row->mid,'class_id'=>$row->class_id,'session_id'=>$session->mid,'jd'=>$this->class_subject_m->todayjd);
        isset($form['section']) && !empty($form['section']) ? $assigned_filter['section_id']=$form['section'] : '';
        if($this->std_subject_homework_m->get_rows($assigned_filter,'',true)>0){
            //update home work
            $this->std_subject_homework_m->save(array('homework'=>$form['homework']),$assigned_filter);
        }else{
            //add homework
            isset($form['section']) && !empty($form['section']) ? $data['section_id']=$form['section'] : '';
            $data['homework']=$form['homework'];
            $data['campus_id']=$this->CAMPUSID;
            $this->std_subject_homework_m->add_row($data);
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." assigned homework of subject (".$row->name." - ".$row->code.") to students of class ".$class->title;
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        // if(count($students)>0){}

        //send back the resposne  
        $this->RESPONSE['message']=' Homework assigned to students successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function deleteHomework(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->std_subject_homework_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->std_subject_homework_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }


    // create row
    public function eraseExamData(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','area');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }    
        $activeSession=$this->session_m->getActiveSession();
        $subject=$this->class_subject_m->get_by_primary($form['rid']);

        switch (strtolower($form['area'])) {
            case 'final':{

                $filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$activeSession->mid,'subject_id'=>$subject->mid,'class_id'=>$subject->class_id);
                $this->std_subject_final_result_m->delete(NULL,$filter,true);

            }break;
            case 'term':{
                //check if term is givent
                if(empty($form['term']) ){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Please choose a valid term...';
                    echo json_encode($this->RESPONSE);exit();
                } 

                $filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$activeSession->mid,'subject_id'=>$subject->mid,'class_id'=>$subject->class_id,'term_id'=>$form['term']);
                $this->std_term_result_m->delete(NULL,$filter,true);

            }break;
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed exam data of subject (".$subject->name." - ".$subject->code.") ";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        // $this->system_history_m->add_row(array('message'=>$msg));
        // if(count($students)>0){}

        //send back the resposne  
        $this->RESPONSE['message']=' Exam data removed successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	
