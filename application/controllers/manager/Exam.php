<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam extends Manager_Controller{

/** 
* /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'exam/';
        //load all models for this controller
        $models = array('student_m','class_m','std_attendance_m','class_section_m','class_subject_m','class_subject_lesson_m','class_subject_faculty_m','class_subject_progress_m','class_subject_test_m','student_m','staff_m','std_subject_test_result_m','std_history_m','std_result_m','std_subject_final_result_m','exam_term_m','std_term_result_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		if($this->session->flashdata('error')){
            $this->session->keep_flashdata('error');
        }
        if($this->session->flashdata('success')){
            $this->session->keep_flashdata('success');
        }
        redirect($this->CONT_ROOT.'results', 'refresh');	
	}

    ///////TEST PROGRES FUNCTIONS/////////////
    public function progress($tab=''){
        
        $this->data['main_content']='exam_progress';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_progress';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_progress';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
    public function prgdetail($rid,$tab=''){
        if(empty($rid) || $this->student_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid record');
            redirect($this->CONT_ROOT.'progress', 'refresh'); 
        }
        $this->data['main_content']='exam_progress_detail';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_progress';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_progress_detail';
        $record=$this->student_m->get_by_primary($rid);   
        $this->data['record']=$record;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }

    ///////TERMS FUNCTIONS/////////////
    public function terms($tab=''){
        
        $this->data['main_content']='exam_terms';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_terms';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_terms';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
    public function termresults($rid,$tab=''){
        if(empty($rid) || $this->exam_term_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid record');
            redirect($this->CONT_ROOT.'progress', 'refresh'); 
        }
        $this->data['main_content']='exam_term_results';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_terms';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_term_results';
        $record=$this->exam_term_m->get_by_primary($rid);   
        $this->data['record']=$record;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }



    ///////RESULT FUNCTIONS/////////////
    public function pastresult($tab=''){
        
        $this->data['main_content']='exam_pastresults';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_pastresults';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_pastresults';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }

    ///////RESULT FUNCTIONS/////////////
    public function results($tab=''){
        
        $this->data['main_content']='exam_results';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_results';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_results';
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
    public function resdetail($rid,$tab=''){
        if(empty($rid) || $this->student_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid record');
            redirect($this->CONT_ROOT.'progress', 'refresh'); 
        }
        $this->data['main_content']='exam_results_detail';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_results';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_results_detail';
        $record=$this->student_m->get_by_primary($rid);   
        $this->data['record']=$record;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
    public function resultsms($tab=''){
        
        $this->data['main_content']='exam_resultsms';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_resultsms';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_resultsms';
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
                    $this->session->set_flashdata('error', 'Please choose a valid record');
                    redirect($this->LIB_CONT_ROOT.'exam', 'refresh');
                }
                $this->data['main_content']='student_profile';    
                $this->data['print_page_title']='Student profile'; 

            }
            break;
            case 'report':{
                if(empty($form['usr']) || !isset($form['rpt'])){
                    $this->session->set_flashdata('error', 'Please choose a valid Student & Report');
                    redirect($this->LIB_CONT_ROOT.'exam', 'refresh');
                }
                switch(strtolower($form['rpt'])){
                    case 'marksheet' : {
                        $this->data['main_content']='report_exam_marksheet';    
                        $this->data['print_page_title']='Student Marks Sheet';                        
                    }
                    break;
                    case 'clsmarksheet' : {
                        $this->data['main_content']='report_exam_class_marksheet';    
                        $this->data['print_page_title']='Student Marks Sheet';                        
                    }
                    break;
                    case 'clstermmarksheet' : {
                        if(empty($form['term']) ){
                            $this->session->set_flashdata('error', 'Please choose a valid term');
                            redirect($this->LIB_CONT_ROOT.'exam/terms', 'refresh');
                        }
                        $this->data['main_content']='report_exam_class_term_marksheet';    
                        $this->data['print_page_title']='Student Term Marks Sheet';                        
                    }
                    break;
                    case 'clsprgsheet' : {
                        $this->data['main_content']='report_exam_class_prgsheet';    
                        $this->data['print_page_title']='Class Student Progress Sheet';                        
                    }
                    break;
                    case 'clsprglist' : {
                        $this->data['main_content']='report_exam_class_prgslist';    
                        $this->data['print_page_title']='Class Student Progress List';                        
                    }
                    break;
                    case 'clstermprglist' : {
                        $this->data['main_content']='report_exam_class_term_prgslist';    
                        $this->data['print_page_title']='Student Result List';                        
                    }
                    break;
                    case 'prgsheet' : {
                        $this->data['main_content']='report_exam_progresssheet';    
                        $this->data['print_page_title']='Student Monthly Progress Report';                        
                    }
                    break;
                    default:{
                    $this->session->set_flashdata('error', 'Please choose a valid report');
                    redirect($this->LIB_CONT_ROOT.'exam', 'refresh');

                    }
                    break;
                }

            }
            break;
            case 'form':{
                if(!isset($form['frm'])){
                    $this->session->set_flashdata('error', 'Please choose a valid Option');
                    redirect($this->LIB_CONT_ROOT.'exam', 'refresh');
                }
                switch(strtolower($form['frm'])){
                    case 'resultform' : {
                        $this->data['main_content']='form_exam_resultform_blank';    
                        $this->data['print_page_title']='Blank Result Form';                        
                    }
                    break;
                    default:{
                    $this->session->set_flashdata('error', 'Please choose a valid form');
                    redirect($this->LIB_CONT_ROOT.'exam', 'refresh');

                    }
                    break;
                }

            }
            break;
            case 'list':{
                $this->data['main_content']='class_lists';    
                $this->data['print_page_title']='Class Students List'; 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'exam', 'refresh');                       
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
    public function filterTestProgress(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','father_name','student_id','roll_no');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['section']) && !empty($form['section']) ? $filter['section_id']= $form['section'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='class_id ASC, section_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $params['select']='mid,image,name,father_name,class_id,student_id,section,roll_no';
        // $params['distinct']=FALSE;
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->student_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $sub_filter['student_id']=$row['mid'];
            $total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',$sub_filter);
            $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',$sub_filter);    
            $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            $this->RESPONSE['rows'][$i]['performance']=$std_result;  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterTestProgressDetail(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('test');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='subject_id ASC, class_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $session=$this->session_m->getActiveSession();
        // $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->std_subject_test_result_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_subject_test_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            // $sub_filter['student_id']=$row['mid'];
            // $total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',$sub_filter);
            // $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',$sub_filter);    
            // $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            $this->RESPONSE['rows'][$i]['class']=$this->class_m->get_by_primary($row['class_id'])->title;  


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

        ///////////////////////////////////////////////////////////////////////////////////////////
        // isset($form['test_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        // ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        $params['orderby']='mid ASC';
        // isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $session=$this->session_m->getActiveSession();
        $record=$this->class_subject_test_m->get_by_primary($rid);
        $this->RESPONSE['class_id']=$record->class_id;
        $filter['test_id']=$rid;
        $this->RESPONSE['rows']=$this->std_subject_test_result_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->std_subject_test_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $student=$this->student_m->get_by_primary($row['student_id'],'mid,name,image,section_id,roll_no'); 
            if(isset($form['section']) && !empty($form['section'])){if($student->section_id!=$form['section']){
                unset($this->RESPONSE['rows'][$i]);
                $i++;
                continue;
            }}
            // $sub_filter['student_id']=$row['mid'];
            // $total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',$sub_filter);
            // $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',$sub_filter);    
            // $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
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
    
    // filter rows
    public function filterFinalResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','father_name','student_id','roll_no');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['section']) && !empty($form['section']) ? $filter['section_id']= $form['section'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='class_id ASC, section ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $params['select']='mid,image,name,father_name,class_id,student_id,section,roll_no';
        // $params['distinct']=FALSE;
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->student_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $sub_filter['student_id']=$row['mid'];
            $total_marks=$this->std_subject_final_result_m->get_column_result('total_marks',$sub_filter);
            $obt_marks=$this->std_subject_final_result_m->get_column_result('obt_marks',$sub_filter);    
            $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            $this->RESPONSE['rows'][$i]['performance']=$std_result;  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterFinalResultDetail(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('subject_title','class_title');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='subject_id ASC, class_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $session=$this->session_m->getActiveSession();
        // $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->std_subject_final_result_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_subject_final_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            // $sub_filter['student_id']=$row['mid'];
            // $total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',$sub_filter);
            // $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',$sub_filter);    
            // $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            $this->RESPONSE['rows'][$i]['class']=$this->class_m->get_by_primary($row['class_id'])->title;  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterSubjectFinalResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->class_subject_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        $subject=$this->class_subject_m->get_by_primary($rid); 
        $class=$this->class_m->get_by_primary($subject->class_id);
        $session=$this->session_m->getActiveSession();
        //if result does not exist for this subject then create result entries
        $students=$this->student_m->get_rows(array('class_id'=>$subject->class_id,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
        foreach($students as $row){
            if($this->std_subject_final_result_m->get_rows(array('subject_id'=>$subject->mid,'class_id'=>$subject->class_id,'student_id'=>$row['mid'],'campus_id'=>$this->CAMPUSID),'',true)<1){
                $this->std_subject_final_result_m->add_row(array('student_id'=>$row['mid'],'subject_id'=>$subject->mid,'class_id'=>$subject->class_id,'section_id'=>$row['section_id'],'session_id'=>$session->mid,'class_title'=>$class->title,'subject_title'=>$subject->name,'campus_id'=>$this->CAMPUSID));

            }
         
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        // isset($form['test_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        // ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        // isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='subject_id ASC, class_id ASC, mid DESC';
        // isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $session=$this->session_m->getActiveSession();
        $filter['subject_id']=$subject->mid;
        $filter['session_id']=$session->mid;
        $filter['class_id']=$class->mid;
        $this->RESPONSE['rows']=$this->std_subject_final_result_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->std_subject_test_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            // $sub_filter['student_id']=$row['mid'];
            // $total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',$sub_filter);
            // $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',$sub_filter);    
            // $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            // $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            // $this->RESPONSE['rows'][$i]['class']=$this->class_m->get_by_primary($row['class_id'])->title;  
            $this->RESPONSE['rows'][$i]['student']=$this->student_m->get_by_primary($row['student_id'],'mid,name,image');  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // update row
    public function updateFinalTotalMarks(){
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
        //save data in database      
        $row=$this->class_subject_m->get_by_primary($form['rid']); 
        $session=$this->session_m->getActiveSession();
        $where=array('subject_id'=>$row->mid,'session_id'=>$session->mid,'class_id'=>$row->class_id);          
        if($this->std_subject_final_result_m->save($form,$where)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Result cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateFinalResult(){
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
        $row=$this->std_subject_final_result_m->get_by_primary($form['rid']);
        $subject=$this->class_subject_m->get_by_primary($row->subject_id);
        //////////////////////////////////////////////////////////////////////
        $result=0;if($row->total_marks>0 && $form['obt_marks']>0){$result=round(($form['obt_marks']/$row->total_marks)*100);}
        if($result>=$subject->passing_percentage){
            $form['status']=$this->std_subject_final_result_m->STATUS_PASS;
        }else{
            $form['status']=$this->std_subject_final_result_m->STATUS_FAIL;
        }
        //save data in database                
        if($this->std_subject_final_result_m->save($form,$row->mid)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Result cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    // filter rows
    public function filterPastResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','father_name','student_id','roll_no');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['section']) && !empty($form['section']) ? $filter['section_id']= $form['section'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='roll_no ASC, class_id ASC, section_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $params['select']='mid,image,name,father_name,class_id,student_id,section,roll_no, pkg_total_marks,pkg_obt_marks';
        // $params['distinct']=FALSE;
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->student_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     $sub_filter['student_id']=$row['mid'];
        //     $total_marks=$this->std_subject_final_result_m->get_column_result('total_marks',$sub_filter);
        //     $obt_marks=$this->std_subject_final_result_m->get_column_result('obt_marks',$sub_filter);    
        //     $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
        //     $this->RESPONSE['rows'][$i]['performance']=$std_result;  


        //     $i++;
        // }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // update row
    public function updatePastTotalMarks(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('class_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database      
        $session=$this->session_m->getActiveSession();
        $where=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$form['class_id']);
        $where['status']=$this->student_m->STATUS_ACTIVE;
        isset($form['section']) && !empty($form['section']) ? $where['section_id']=$form['section'] : '';          
        if($this->student_m->save(array('pkg_total_marks'=>$form['total_marks']),$where)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Result cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updatePastResult(){
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
        $row=$this->student_m->get_by_primary($form['rid']);
        //save data in database                
        if($this->student_m->save(array('pkg_obt_marks'=>$form['obt_marks']),$row->mid)===false){
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
    // filter rows
    public function filterSubjects(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        // $search=array('name','code','description');
        // $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        // isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='name ASC, mid DESC';
        // isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->class_subject_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->class_subject_m->get_rows($filter,'',true);
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

    // filter rows
    public function filterTests(){
        // get input fields into array
        $activeSession=$this->session_m->getActiveSession();
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','date','description');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['session_id'])&&!empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : $filter['session_id']=$activeSession->mid;
        isset($form['subject_id'])&&!empty($form['subject_id']) ? $filter['subject_id']= $form['subject_id'] : '';
        isset($form['class_id'])&&!empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['date']) && !empty($form['date']) ? $filter['date']= $form['date'] : '';
        isset($form['month']) && !empty($form['month']) ? $filter['month']= $form['month'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
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
            $this->RESPONSE['rows'][$i]['session']=$this->session_m->get_by_primary($row['session_id'])->title;
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addTest(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('subject_id','class_id','total_marks','title');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database
        $session=$this->session_m->getActiveSession();
        $form['session_id']=$session->mid;
        $form['campus_id']=$this->CAMPUSID;
        $test_id=$this->class_subject_test_m->add_row($form);
        if($test_id==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Test cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //create result entries
        $students=$this->student_m->get_rows(array('class_id'=>$form['class_id'],'session_id'=>$session->mid,'status'=>$this->student_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID),array('orderby'=>'roll_no ASC, student_id ASC, mid ASC'));
        foreach($students as $row){
            $this->std_subject_test_result_m->add_row(array('student_id'=>$row['mid'],'subject_id'=>$form['subject_id'],'total_marks'=>$form['total_marks'],'test_id'=>$test_id,'test'=>$form['title'],'class_id'=>$form['class_id'],'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created new test (".$form['title'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Test registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateTest(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','total_marks','title');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->class_subject_test_m->get_by_primary($form['rid']);
        //save data in database                
        if($this->class_subject_test_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Test cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated test (".$row->title.").";
        $this->system_history_m->add_row(array('message'=>$msg));

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function loadTest(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['org_id']=$this->ORGID;
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
    public function deleteTest(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->class_subject_test_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->class_subject_test_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        $session=$this->session_m->getActiveSession();
        $this->std_subject_test_result_m->delete(null, array('test_id'=>$rid,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function resetTestStudents(){
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

        $test=$this->class_subject_test_m->get_by_primary($form['rid']);
        //save data in database
        $session=$this->session_m->getActiveSession();
        $form['session_id']=$session->mid;
        // $test_id=$this->class_subject_test_m->add_row($form);
        // if($test_id==false){
        //     $this->RESPONSE['error']=TRUE;
        //     $this->RESPONSE['message']='Test cannot be registered at this time. Please try again later...';
        //     echo json_encode($this->RESPONSE);exit();            
        // }
        //create result entries
        $students=$this->student_m->get_rows(array('class_id'=>$test->class_id,'session_id'=>$session->mid,'status'=>$this->student_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID),array('orderby'=>'roll_no ASC, student_id ASC, mid ASC'));
        $total_reset=0;
        foreach($students as $row){
            if($this->std_subject_test_result_m->get_rows(array('student_id'=>$row['mid'],'subject_id'=>$test->subject_id,'test_id'=>$test->mid,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID),'',true)<1){
                $total_reset++;
                $this->std_subject_test_result_m->add_row(array('student_id'=>$row['mid'],'subject_id'=>$test->subject_id,'total_marks'=>$test->total_marks,'test_id'=>$test->mid,'test'=>$test->title,'class_id'=>$test->class_id,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
            }
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated sutdents of test (".$test->title.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=$total_reset.' students of test reset successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    //////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////
    // filter rows
    public function filterTestMonths(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        // $search=array('name','code','description');
        // $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        // isset($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        // isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='name ASC, mid DESC';
        // isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $this->RESPONSE['rows']=$this->class_subject_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->class_subject_m->get_rows($filter,'',true);
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->class_subject_test_m->get_rows($filter,array('orderby'=>'month ASC','select'=>'month,year','distinct'=>true) );
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            if($row['month']>0){
                $this->RESPONSE['rows'][$i]['month_name']=month_string($row['month']).', '.$row['year'];                
            }else{
                $this->RESPONSE['rows'][$i]['month']='';
                $this->RESPONSE['rows'][$i]['month_name']='';
            }
            $i++;
        }
        // print_r($this->RESPONSE['rows']);

        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterTestDays(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        // $search=array('name','code','description');
        // $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['month']) ? $filter['month']= $form['month'] : '';
        // isset($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        // isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='name ASC, mid DESC';
        // isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $this->RESPONSE['rows']=$this->class_subject_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->class_subject_m->get_rows($filter,'',true);
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->class_subject_test_m->get_rows($filter,array('orderby'=>'day ASC','select'=>'day','distinct'=>true) );
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     if($row['month']>0){
        //         $this->RESPONSE['rows'][$i]['month_name']=month_string($row['month']).', '.$row['year'];                
        //     }else{
        //         $this->RESPONSE['rows'][$i]['month']='';
        //         $this->RESPONSE['rows'][$i]['month_name']='';
        //     }
        //     $i++;
        // }
        // print_r($this->RESPONSE['rows']);

        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterMonthTests(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        // $search=array('name','code','description');
        // $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['month']) ? $filter['month']= $form['month'] : '';
        isset($form['day']) && !empty($form['day']) ? $filter['day']= $form['day'] : '';
        // isset($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        // isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='name ASC, mid DESC';
        // isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $this->RESPONSE['rows']=$this->class_subject_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->class_subject_m->get_rows($filter,'',true);
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->class_subject_test_m->get_rows($filter);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     if($row['month']>0){
        //         $this->RESPONSE['rows'][$i]['month_name']=month_string($row['month']).', '.$row['year'];                
        //     }else{
        //         $this->RESPONSE['rows'][$i]['month']='';
        //         $this->RESPONSE['rows'][$i]['month_name']='';
        //     }
        //     $i++;
        // }
        // print_r($this->RESPONSE['rows']);

        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }


    // send monthly progress report sms
    public function sendMonthlyReportSMS(){
        $this->load->library('smspoint');
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('class_id','month','rxr');
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
            $this->RESPONSE['message']='SMS sending option is not active. Please enable sms sending option before sending sms...';
            echo json_encode($this->RESPONSE);exit();
        }
        //////////////////////////////////////////////
        $session=$this->session_m->getActiveSession();
        $std_filter=array('session_id'=>$session->mid,'class_id'=>$form['class_id'],'status'=>$this->student_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID);
        if(isset($form['student_id']) && !empty($form['student_id'])){$std_filter['mid']=$form['student_id'];}
        $students=$this->student_m->get_rows($std_filter,array('orderby'=>'section_id ASC, roll_no ASC'));
        $total_sent=0;
        foreach($students as $student){
            if(isset($form['section_id']) && !empty($form['section_id'])){
                if($form['section_id'] != $student['section_id']){ continue;}
            }
            $mobile=$student['guardian_mobile'];
            if($form['rxr']=='students'){
                $mobile=$student['mobile'];
            }
            $std_name=$student['name'];
            $std_guardian=$student['guardian_name'];
            $std_guardian=$student['guardian_name'];
            $month_str=month_string($form['month']);
            $stamp="\n".$this->SETTINGS[$this->system_setting_m->_ORG_NAME];
            $std_result="";
            $message=htmlspecialchars_decode($form['message']);
            // $message="Dear ".$student['guardian_name'].",\nBelow is(".month_string($form['month']).") monthly test result of ".$student['name'].":\n";
            ////load tests of select session and class///////////////////////////////
            $test_filter=array('session_id'=>$session->mid,'month'=>$form['month'],'class_id'=>$student['class_id'],'campus_id'=>$this->CAMPUSID);
            //check if a single test is selected.
            if(isset($form['test_id']) && !empty($form['test_id'])){$test_filter['mid']=$form['test_id'];}
            $tests=$this->class_subject_test_m->get_rows($test_filter,array('orderby'=>'day ASC'));
            $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$student['class_id'],'student_id'=>$student['mid']);
            $send_sms=false;
            $total_tests=0;
            $std_marks_total=0;
            $std_marks_obt=0;
            foreach($tests as $test){
                $sub_filter['test_id']=$test['mid'];
                $result=$this->std_subject_test_result_m->get_by($sub_filter,true);
                if(!empty($result)){
                    $total_tests++;
                    $send_sms=true;
                    $subject=$this->class_subject_m->get_by_primary($test['subject_id']);
                    $std_result.="\n".$test['title']."(".$subject->name."): ".$result->obt_marks."/".$result->total_marks;
                    $std_marks_total+=$result->total_marks;
                    $std_marks_obt+=$result->obt_marks;
                }
            }
            //send total marks only if more then one test reports
            if($total_tests>1){$std_result.="\n Total Marks: $std_marks_obt / $std_marks_total";}
            //add message footer.
            // $message.="\nRegargds,\n".$this->ORG->name."(".$this->CAMPUS->name.")";            
            //conversion keys
            $key_vars=array(
                    '{STUDENT}'=>$std_name,
                    '{GUARDIAN}'=>$std_guardian,
                    '{MONTH}'=>$month_str,
                    '{RESULT}'=>$std_result,
                    '{STAMP}'=>$stamp
                );
            ////////////////////////////////////////
            $sms=strtr($message, $key_vars);
            if($send_sms){
                $total_sent++;
                $this->sms_history_m->send_sms($this->CAMPUSID,$mobile,$sms);         
            }
        }

        $this->RESPONSE['message']='report sms sent to '.$total_sent.' students...';
        echo json_encode($this->RESPONSE);exit();
    }

    // send monthly progress report sms
    public function sendTermResultSMS(){
        $this->load->library('smspoint');
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('class_id','term_id','rxr');
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
            $this->RESPONSE['message']='SMS sending option is not active. Please enable sms sending option before sending sms...';
            echo json_encode($this->RESPONSE);exit();
        }
        //////////////////////////////////////////////
        $session=$this->session_m->getActiveSession();
        $term=$this->exam_term_m->get_by_primary($form['term_id']);
        $students=$this->student_m->get_rows(array('session_id'=>$session->mid,'class_id'=>$form['class_id'],'status'=>$this->student_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID),array('orderby'=>'section_id ASC, roll_no ASC'));
        $total_sent=0;
        foreach($students as $student){
            if(isset($form['section_id']) && !empty($form['section_id'])){
                if($form['section_id'] != $student['section_id']){ continue;}
            }
            $total_sent++;
            $mobile=$student['guardian_mobile'];
            if($form['rxr']=='students'){
                $mobile=$student['mobile'];
            }
            $std_name=$student['name'];
            $std_guardian=$student['guardian_name'];
            $std_guardian=$student['guardian_name'];
            $term_name=$term->name;
            // $month_str=month_string($form['month']);
            $stamp="\n".$this->SETTINGS[$this->system_setting_m->_ORG_NAME];
            $std_result="";
            $message=htmlspecialchars_decode($form['message']);

            // $message="";
            // $message="Dear ".$student['guardian_name'].",\n Below is ".$student['name']." result for the  ".$term->name.":\n";
            // if($form['rxr']=='students'){
            //     $mobile=$student['mobile'];
            //     $message="Dear ".$student['name'].",\n Below is your result for the ".$term->name.":\n";
            // }
            ////load subjects of selected class///////////////////////////////
            $subs_filter=array('class_id'=>$student['class_id'],'campus_id'=>$this->CAMPUSID);
            if(isset($form['subject']) && !empty($form['subject'])){$subs_filter['mid']=$form['subject'];}
            $subjects=$this->class_subject_m->get_rows($subs_filter,array('orderby'=>'name ASC'));
            $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$student['class_id'],'term_id'=>$form['term_id'],'student_id'=>$student['mid']);
            $status='Pass';
            foreach($subjects as $subject){
                $sub_filter['subject_id']=$subject['mid'];
                if($this->std_term_result_m->get_rows($sub_filter,'',true)>0){
                    $result=$this->std_term_result_m->get_by($sub_filter,true);
                    if(!empty($result)){
                        $std_result.="\n ".$subject['name'].": ".$result->obt_marks."/".$result->total_marks;
                        if($result->status=='fail'){$status='Fail';}   
                    }
                }
            }

            //add message footer.
            $std_result.="\n Status: ".$status;          
            //conversion keys
            $key_vars=array(
                    '{STUDENT}'=>$std_name,
                    '{GUARDIAN}'=>$std_guardian,
                    '{TERM}'=>$term_name,
                    '{RESULT}'=>$std_result,
                    '{STAMP}'=>$stamp
                );
            ////////////////////////////////////////
            $sms=strtr($message, $key_vars);
            $this->sms_history_m->send_sms($this->CAMPUSID,$mobile,$sms);            
        }

        $this->RESPONSE['message']='Result sms sent to '.$total_sent.' students...';
        echo json_encode($this->RESPONSE);exit();
    }
    // send monthly progress report sms
    public function sendFinalResultSMS(){
        $this->load->library('smspoint');
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('class_id','rxr');
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
            $this->RESPONSE['message']='SMS sending option is not active. Please enable sms sending option before sending sms...';
            echo json_encode($this->RESPONSE);exit();
        }
        //////////////////////////////////////////////
        $session=$this->session_m->getActiveSession();
        $students=$this->student_m->get_rows(array('session_id'=>$session->mid,'class_id'=>$form['class_id'],'status'=>$this->student_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID),array('orderby'=>'section_id ASC, roll_no ASC'));
        $total_sent=0;
        foreach($students as $student){
            if(isset($form['section_id']) && !empty($form['section_id'])){
                if($form['section_id'] != $student['section_id']){ continue;}
            }
            $total_sent++;
            $mobile=$student['guardian_mobile'];
            $std_name=$student['name'];
            $std_guardian=$student['guardian_name'];
            $std_guardian=$student['guardian_name'];
            $session_name=$session->title;
            // $month_str=month_string($form['month']);
            $stamp="\n".$this->SETTINGS[$this->system_setting_m->_ORG_NAME];
            $std_result="";
            $message=htmlspecialchars_decode($form['message']);

            // $message="";
            // $message="Dear ".$student['guardian_name'].",\n Below is ".$student['name']." final result for the session ".$session->title.":\n";
            // if($form['rxr']=='students'){
            //     $mobile=$student['mobile'];
            //     $message="Dear ".$student['name'].",\n Below is your final result for the session ".$session->title.":\n";
            // }
            ////load subjects of selected class///////////////////////////////
            $subjects=$this->class_subject_m->get_rows(array('class_id'=>$student['class_id'],'campus_id'=>$this->CAMPUSID),array('orderby'=>'name ASC'));
            $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'class_id'=>$student['class_id'],'student_id'=>$student['mid']);
            $status='passed';
            foreach($subjects as $subject){
                $sub_filter['subject_id']=$subject['mid'];
                $result=$this->std_subject_final_result_m->get_by($sub_filter,true);
                if(!empty($result)){
                    $std_result.="\n ".$subject['name'].": ".$result->obt_marks."/".$result->total_marks;
                    if($result->status=='fail'){$status='failed';}
                }
            }

            //add message footer.
            $std_result.="\n Status: ".$status;
            // $message.="\nRegargds,\n".$this->ORG->name."(".$this->CAMPUS->name.")";         
            //conversion keys
            $key_vars=array(
                    '{STUDENT}'=>$std_name,
                    '{GUARDIAN}'=>$std_guardian,
                    '{SESSION}'=>$session_name,
                    '{RESULT}'=>$std_result,
                    '{STAMP}'=>$stamp
                );
            ////////////////////////////////////////
            $sms=strtr($message, $key_vars);
            $this->sms_history_m->send_sms($this->CAMPUSID,$mobile,$sms);            
        }

        $this->RESPONSE['message']='Result sms sent to '.$total_sent.' students...';
        echo json_encode($this->RESPONSE);exit();
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterTermResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','father_name','student_id','roll_no');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['term_id']) && !empty($form['term_id']) ? $term_id= $form['term_id'] : exit;
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['section']) && !empty($form['section']) ? $filter['section_id']= $form['section'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='class_id ASC, section_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $params['select']='mid,image,name,father_name,class_id,student_id,section,roll_no';
        // $params['distinct']=FALSE;
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->student_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid,'term_id'=>$term_id);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $sub_filter['student_id']=$row['mid'];
            $total_marks=$this->std_term_result_m->get_column_result('total_marks',$sub_filter);
            $obt_marks=$this->std_term_result_m->get_column_result('obt_marks',$sub_filter);    
            $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            $this->RESPONSE['rows'][$i]['performance']=$std_result;  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterTermResultDetail(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('subject_title','class_title');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['term_id']) && !empty($form['term_id']) ? $filter['term_id']= $form['term_id'] : exit;
        isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='subject_id ASC, class_id ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $session=$this->session_m->getActiveSession();
        // $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->std_term_result_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_term_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            // $sub_filter['student_id']=$row['mid'];
            // $total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',$sub_filter);
            // $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',$sub_filter);    
            // $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            $this->RESPONSE['rows'][$i]['class']=$this->class_m->get_by_primary($row['class_id'])->title;  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterSubjectTermResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['term_id']) && !empty($form['term_id']) ? $term_id= $form['term_id'] : exit;
        if(empty($rid)|| $this->class_subject_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        $subject=$this->class_subject_m->get_by_primary($rid); 
        $class=$this->class_m->get_by_primary($subject->class_id);
        $session=$this->session_m->getActiveSession();
        //if result does not exist for this subject then create result entries
        $std_filter=array('class_id'=>$subject->class_id,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID,'status'=>$this->student_m->STATUS_ACTIVE);
        isset($form['section']) && !empty($form['section']) ? $std_filter['section_id']= $form['section'] : '';
        $students=$this->student_m->get_rows($std_filter,array('orderby'=>'roll_no ASC, mid ASC'));
        foreach($students as $row){
            $term_result_filter=array('subject_id'=>$subject->mid,'class_id'=>$subject->class_id,'student_id'=>$row['mid'],'term_id'=>$term_id,'campus_id'=>$this->CAMPUSID);
            isset($form['section']) && !empty($form['section']) ? $term_result_filter['section_id']= $form['section'] :'';
            if($this->std_term_result_m->get_rows($term_result_filter,'',true)<1){
                $this->std_term_result_m->add_row(array('student_id'=>$row['mid'],'subject_id'=>$subject->mid,'class_id'=>$subject->class_id,'section_id'=>$row['section_id'],'session_id'=>$session->mid,'class_title'=>$class->title,'subject_title'=>$subject->name,'term_id'=>$term_id,'campus_id'=>$this->CAMPUSID));

            }
         
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        // isset($form['test_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        // if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        // ///////////////////////////////////////////
        // $params['limit']=$this->PAGE_LIMIT;
        // isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        // isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='subject_id ASC, class_id ASC, mid DESC';
        // isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        // $session=$this->session_m->getActiveSession();
        $filter['subject_id']=$subject->mid;
        $filter['session_id']=$session->mid;
        $filter['class_id']=$class->mid;
        isset($form['section']) && !empty($form['section']) ? $filter['section_id']= $form['section'] : '';
        $filter['term_id']=$term_id;
        $params['orderby']='mid ASC';
        $this->RESPONSE['rows']=$this->std_term_result_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->std_subject_test_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $sub_filter=array('campus_id'=>$this->CAMPUSID,'session_id'=>$session->mid);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            // $sub_filter['student_id']=$row['mid'];
            // $total_marks=$this->std_subject_test_result_m->get_column_result('total_marks',$sub_filter);
            // $obt_marks=$this->std_subject_test_result_m->get_column_result('obt_marks',$sub_filter);    
            // $std_result=0;if($total_marks>0 && $obt_marks>0){$std_result=round(($obt_marks/$total_marks)*100);}         
            // $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            // $this->RESPONSE['rows'][$i]['class']=$this->class_m->get_by_primary($row['class_id'])->title;  
            $this->RESPONSE['rows'][$i]['student']=$this->student_m->get_by_primary($row['student_id'],'mid,name,father_name,image,roll_no');  


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // update row
    public function updateTermTotalMarks(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','term_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database      
        $row=$this->class_subject_m->get_by_primary($form['rid']); 
        $session=$this->session_m->getActiveSession();
        $where=array('term_id'=>$form['term_id'],'subject_id'=>$row->mid,'session_id'=>$session->mid,'class_id'=>$row->class_id);          
        if($this->std_term_result_m->save($form,$where)===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Result cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function updateTermResult(){
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
        $row=$this->std_term_result_m->get_by_primary($form['rid']);
        $subject=$this->class_subject_m->get_by_primary($row->subject_id);
        //////////////////////////////////////////////////////////////////////
        $result=0;if($row->total_marks>0 && $form['obt_marks']>0){$result=round(($form['obt_marks']/$row->total_marks)*100);}
        if($result>=$subject->passing_percentage){
            $form['status']=$this->std_term_result_m->STATUS_PASS;
        }else{
            $form['status']=$this->std_term_result_m->STATUS_FAIL;
        }
        //save data in database                
        if($this->std_term_result_m->save($form,$row->mid)===false){
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


    // filter rows
    public function filterTerms(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','date','start_date','end_date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $activeSession=$this->session_m->getActiveSession();
        isset($form['session_id'])&&!empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : $filter['session_id']=$activeSession->mid;
        // isset($form['subject_id'])&&!empty($form['subject_id']) ? $filter['subject_id']= $form['subject_id'] : '';
        // isset($form['class_id'])&&!empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby'] :$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->exam_term_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->exam_term_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name;
        //     $this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];
        //     $this->RESPONSE['rows'][$i]['session']=$this->session_m->get_by_primary($row['session_id'])->title;
        //     $i++;
        // }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addTerm(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('name','start_date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database
        $session=$this->session_m->getActiveSession();
        $form['session_id']=$session->mid;
        $form['campus_id']=$this->CAMPUSID;
        $test_id=$this->exam_term_m->add_row($form);
        if($test_id==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Test cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //create result entries
        // $students=$this->student_m->get_rows(array('class_id'=>$form['class_id'],'session_id'=>$session->mid,'status'=>$this->student_m->STATUS_ACTIVE,'campus_id'=>$this->CAMPUSID));
        // foreach($students as $row){
        //     $this->std_subject_test_result_m->add_row(array('student_id'=>$row['mid'],'subject_id'=>$form['subject_id'],'total_marks'=>$form['total_marks'],'test_id'=>$test_id,'test'=>$form['title'],'class_id'=>$form['class_id'],'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
        // }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created new exam term (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Term registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateTerm(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','start_date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->exam_term_m->get_by_primary($form['rid']);
        //save data in database                
        if($this->exam_term_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Term cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated exam term (".$row->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function loadTerm(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['org_id']=$this->ORGID;
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->exam_term_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->exam_term_m->get_by_primary($rid);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }
    // update row
    public function deleteTerm(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->exam_term_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->exam_term_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Record can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        $session=$this->session_m->getActiveSession();
        $this->std_term_result_m->delete(null, array('term_id'=>$rid,'session_id'=>$session->mid,'campus_id'=>$this->CAMPUSID));
        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
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
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->class_subject_qbank_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->class_subject_qbank_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['lesson']=$this->class_subject_lesson_m->get_by_primary($row['lesson_id'])->name;
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
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
    


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	