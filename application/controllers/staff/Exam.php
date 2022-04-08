<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam extends Staff_Controller{

/** 
* /////////////////////////////////////////////////////////////////////////////////////
* ********************************* CONTANTS ******************************************
* /////////////////////////////////////////////////////////////////////////////////////
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
		// $this->data['main_content']='classes';	
		// $this->data['menu']='classes';			
		// $this->data['sub_menu']='classes';
  //       $this->data['tab']=$tab;
		//$this->data['barcode']=new Picqer\Barcode\BarcodeGeneratorHTML();
        //$this->data['barcode']=new Picqer\Barcode\BarcodeGeneratorPNG();

        // $this->ANGULAR_INC[]='classes';
        // $this->THEME_INC[]='js/plugins/extensions/jquery_ui/widgets.min.js';
        // $this->HEADER_INC[]='js/pages/task_manager_list.js';
        // $this->FOOTER_INC[]='js/pages/form_checkboxes_radios.js';
        // $generator = 
        // echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
        /////////////////////////////////////////////////////////////
		// $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
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
    public function results($tab=''){
        
        $this->data['main_content']='exam_results';    
        $this->data['menu']='exam';            
        $this->data['sub_menu']='exam_results';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='exam_results';
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
                        $this->data['print_page_title']='Student Progress Sheet';                        
                    }
                    break;
                    case 'clsprglist' : {
                        $this->data['main_content']='report_exam_class_prgslist';    
                        $this->data['print_page_title']='Student Progress List';                        
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
    * ///////////////////////////////////////////////////////////////////////////////
    * *********************** AJAX FUNCTIONS ****************************************
    * ///////////////////////////////////////////////////////////////////////////////
    */

    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    
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
        isset($form['section']) && !empty($form['section']) ? $filter['section']= $form['section'] : '';
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
        $filter['subject_id']=$subject->mid;
        $filter['session_id']=$session->mid;
        $filter['class_id']=$class->mid;
        $this->RESPONSE['rows']=$this->std_subject_final_result_m->get_rows($filter,$params);
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['student']=$this->student_m->get_by_primary($row['student_id'],'mid,name,image,father_name,roll_no');  


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
    }
    //////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterSubjects(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $or_where=array();
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

        // $subjects=$this->class_subject_faculty_m->get_rows(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$this->LOGIN_USER->mid));
        // if(count($subjects)<1){
        //     //user has not assigned any subject
        //     echo json_encode( $this->RESPONSE);exit();
        // }else{
        //     $filter['mid']=$subjects[0]['subject_id'];
        //     if(count($subjects)>1){foreach($subjects as $sub){$or_where['mid']=$sub['subject_id'];}}
        // }
        // $params['or_where']=$or_where;
        $this->RESPONSE['rows']=$this->class_subject_m->get_rows($filter,$params);
        // $this->RESPONSE['total_rows']=$this->class_subject_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $filter['session_id']=$this->session_m->getActiveSession()->mid;
        // $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){

            $subjects=$this->class_subject_faculty_m->get_rows(array('campus_id'=>$this->CAMPUSID,'staff_id'=>$this->LOGIN_USER->mid,'subject_id'=>$row['mid']),'',true);
            if($subjects<1){
                unset($this->RESPONSE['rows'][$i]);
            }
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

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
        $students=$this->student_m->get_rows($std_filter);
        foreach($students as $row){
            $term_result_filter=array('subject_id'=>$subject->mid,'class_id'=>$subject->class_id,'student_id'=>$row['mid'],'term_id'=>$term_id,'campus_id'=>$this->CAMPUSID);
            isset($form['section']) && !empty($form['section']) ? $term_result_filter['section_id']= $form['section'] :'';
            if($this->std_term_result_m->get_rows($term_result_filter,'',true)<1){
                $this->std_term_result_m->add_row(array('student_id'=>$row['mid'],'subject_id'=>$subject->mid,'class_id'=>$subject->class_id,'section_id'=>$row['section_id'],'session_id'=>$session->mid,'class_title'=>$class->title,'subject_title'=>$subject->name,'term_id'=>$term_id,'campus_id'=>$this->CAMPUSID));

            }
         
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $filter['subject_id']=$subject->mid;
        $filter['session_id']=$session->mid;
        $filter['class_id']=$class->mid;
        isset($form['section']) && !empty($form['section']) ? $filter['section_id']= $form['section'] : '';
        $filter['term_id']=$term_id;
        $this->RESPONSE['rows']=$this->std_term_result_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['student']=$this->student_m->get_by_primary($row['student_id'],'mid,name,image,father_name,roll_no');  


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

    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////


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

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	