<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subjects extends Staff_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'subjects/';
        //load all models for this controller
        $models = array('stf_role_m','class_m','class_section_m','class_subject_m','class_subject_lesson_m','class_subject_faculty_m','class_subject_progress_m','class_subject_qbank_m','student_m','staff_m','std_attendance_m','std_history_m','std_subject_homework_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		
		$this->data['main_content']='subjects';	
		$this->data['menu']='subjects';			
		$this->data['sub_menu']='subjects';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='subjects';
        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    ///////subject information funcations/////////////
    public function info($rid='',$tab=''){
        if(empty($rid) || $this->class_subject_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid subject');
            redirect($this->LIB_CONT_ROOT.'subjects', 'refresh'); 
        }
        
        $this->data['main_content']='subjects_info';    
        $this->data['menu']='subjects';            
        $this->data['sub_menu']='subjects_info';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='subjects_info';
        $record=$this->class_subject_m->get_by_primary($rid);   
        $this->data['record']=$record;
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
            //upload artwork file
            if($this->IS_DEMO){
                $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
                redirect($this->CONT_ROOT.'profile/'.$student->mid, 'refresh');           
            }
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
        $path='./uploads/images/student/profile';
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
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='subject_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->class_subject_faculty_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_faculty_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id']);
            $this->RESPONSE['rows'][$i]['class']=$this->class_m->get_by_primary($this->RESPONSE['rows'][$i]['subject']->class_id)->title;
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////


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
            $msg=$this->LOGIN_USER->name."(".$this->LOGIN_USER->staff_id.")"." assigned homework of subject (".$row->name.") to students of class ".$class->title;
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));


        //send back the resposne  
        $this->RESPONSE['message']=' Homework assigned to students successfully.';
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
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
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
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
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
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
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
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	