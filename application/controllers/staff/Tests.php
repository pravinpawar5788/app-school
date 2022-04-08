<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tests extends Staff_Controller{

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
        // if($this->LOGIN_USER->prm_class<1){
        //     $this->session->set_flashdata('error', 'Permission Denied!');
        //     redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        // }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'tests/';
        //load all models for this controller
        $models = array('stf_role_m','class_m','class_section_m','class_subject_m','class_subject_lesson_m','class_subject_faculty_m','class_subject_progress_m','class_subject_qbank_m','student_m','staff_m','std_attendance_m','std_history_m','std_subject_homework_m','class_subject_test_m','std_subject_test_result_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		
		$this->data['main_content']='tests';	
		$this->data['menu']='tests';			
		$this->data['sub_menu']='tests';
        $this->data['tab']=$tab;
		//$this->data['barcode']=new Picqer\Barcode\BarcodeGeneratorHTML();
        //$this->data['barcode']=new Picqer\Barcode\BarcodeGeneratorPNG();

        $this->ANGULAR_INC[]='tests';
        // $this->THEME_INC[]='js/plugins/extensions/jquery_ui/widgets.min.js';
        // $this->HEADER_INC[]='js/pages/task_manager_list.js';
        // $this->FOOTER_INC[]='js/pages/form_checkboxes_radios.js';
        // $generator = 
        // echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
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

  
    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** AJAX FUNCTIONS *******************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    // filter rows
    public function filter2(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        // isset($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
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
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // filter rows
    public function filter(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $or_where=array();
        $search=array('title','date','description');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////
        $session=$this->session_m->getActiveSession();
        $filter['session_id']= $session->mid;
        $filter['month']= $this->class_subject_test_m->month;
        $filter['year']= $this->class_subject_test_m->year;
        $subjects=$this->class_subject_faculty_m->get_rows(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$this->LOGIN_USER->mid));
        if(count($subjects)<1){
            //user has not assigned any subject
            echo json_encode( $this->RESPONSE);exit();
        }else{
            $filter['subject_id']=$subjects[0]['subject_id'];
            if(count($subjects)>1){foreach($subjects as $sub){$or_where['subject_id']=$sub['subject_id'];}}
        }
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////

        $params['or_where']=$or_where;
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->class_subject_test_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_test_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name;
            $this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];
            // $this->RESPONSE['rows'][$i]['session']=$this->session_m->get_by_primary($row['session_id'])->title;
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // filter rows
    public function filterTestResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('test');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->class_subject_test_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $filter['test_id']=$rid;
        $this->RESPONSE['rows']=$this->std_subject_test_result_m->get_rows($filter,$params);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){    
            $student=$this->student_m->get_by_primary($row['student_id'],'mid,name,image,section_id,roll_no'); 
            if(isset($form['section']) && intval($form['section'])>0){
                if($student->section_id!=$form['section']){
                    unset($this->RESPONSE['rows'][$i]);
                    $i++;
                    continue;
                }
            }   
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            $this->RESPONSE['rows'][$i]['class']=$this->class_m->get_by_primary($row['class_id'])->title;  
            $this->RESPONSE['rows'][$i]['student']=$student;  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // update row
    public function updateTestResult(){
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
        $row=$this->std_subject_test_result_m->get_by_primary($form['rid']);
        $subject=$this->class_subject_m->get_by_primary($row->subject_id);
        //////////////////////////////////////////////////////////////////////
        $result=0;if($row->total_marks>0 && $form['obt_marks']>0){$result=round(($form['obt_marks']/$row->total_marks)*100);}
        if($result>=$subject->passing_percentage){
            $form['status']=$this->std_subject_test_result_m->STATUS_PASS;
        }else{
            $form['status']=$this->std_subject_test_result_m->STATUS_FAIL;
        }
        //save data in database                
        if($this->std_subject_test_result_m->save($form,$row->mid)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Result cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

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
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->class_subject_lesson_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_lesson_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     $filter['class_id']=$row['mid'];
        //     $this->RESPONSE['rows'][$i]['incharge']=$this->staff_m->get_by_primary($row['incharge_id'])->name;
        //     $this->RESPONSE['rows'][$i]['students']=$this->student_m->get_rows($filter,'',true);
        //     $i++;
        // }
        // print_r($this->RESPONSE['rows']);
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

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	