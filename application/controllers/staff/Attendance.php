<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance extends Staff_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'attendance/';
        //load all models for this controller
        $models = array('std_attendance_m','sms_hook_m','sms_history_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
        $this->data['main_content']='attendance';   
        $this->data['menu']='attendance';           
        $this->data['sub_menu']='attendance';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='attendance';
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
            case 'list':{
                $this->data['main_content']='attendance_list';    
                $this->data['print_page_title']='Attendance Report'; 

            }
            break;
            case 'history':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_history';    
                $this->data['print_page_title']='Student History'; 

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
    public function filterStaff(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select class and date...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   

        $attendance_jd=get_jd_from_date($form['date'], '-', true);
        $today_jd=$this->class_m->todayjd;
        if($attendance_jd > $today_jd){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select a vlaid date (today or past date only)...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($attendance_jd < ($today_jd-180)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You can only mark the attendance of past 6 months...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $params['select']="mid,name,image";
        $params['orderby']="name ASC";
        $filter['status']=$this->staff_m->STATUS_ACTIVE;
        $this->RESPONSE['rows']=$this->staff_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->staff_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        $attendance_filter=array('campus_id'=>$this->CAMPUSID);
        $attendance_filter['month']=intval(get_month_from_date($form['date'], '-', true) );
        $attendance_filter['year']=intval(get_year_from_date($form['date'], '-') );
        $attendance_filter['session_id']=$this->session_m->getActiveSession()->mid;
        foreach($this->RESPONSE['rows'] as $row){
            $attendance_filter['staff_id']=$row['mid'];
            if($this->stf_attendance_m->get_rows($attendance_filter,'',true)<1){
                //new month started or init attendance
                $this->stf_attendance_m->add_row($attendance_filter);
            }
            $attendance=$this->stf_attendance_m->get_by($attendance_filter,true);
            $day='d'.get_day_from_date($form['date'],'-');
            $this->RESPONSE['rows'][$i]['attendance']=$attendance->$day;
            $this->RESPONSE['rows'][$i]['attendance_id']=$attendance->mid;
            $this->RESPONSE['rows'][$i]['attendance_day']=$day;
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterStudents(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('class_id','date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select class and date...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   

        $attendance_jd=get_jd_from_date($form['date'], '-', true);
        $today_jd=$this->class_m->todayjd;
        if($attendance_jd > $today_jd){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select a vlaid date (today or past date only)...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($attendance_jd < ($today_jd-180)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You can only mark the attendance of past 6 months...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $params['select']="mid,name,roll_no,image,section";
        $params['orderby']="roll_no ASC";
        $filter['class_id']=$form['class_id'];
        isset($form['section'])&& !empty($form['section']) ? $filter['section']=$form['section'] : '';
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->student_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        $attendance_filter=array('campus_id'=>$this->CAMPUSID);
        $attendance_filter['month']=intval(get_month_from_date($form['date'], '-', true) );
        $attendance_filter['year']=intval(get_year_from_date($form['date'], '-') );
        $attendance_filter['session_id']=$filter['session_id'];
        foreach($this->RESPONSE['rows'] as $row){
            $attendance_filter['student_id']=$row['mid'];
            if($this->std_attendance_m->get_rows($attendance_filter,'',true)<1){
                //new month started or init attendance
                $this->std_attendance_m->add_row($attendance_filter);
            }
            $attendance=$this->std_attendance_m->get_by($attendance_filter,true);
            $day='d'.get_day_from_date($form['date'],'-');
            $this->RESPONSE['rows'][$i]['attendance']=$attendance->$day;
            $this->RESPONSE['rows'][$i]['attendance_id']=$attendance->mid;
            $this->RESPONSE['rows'][$i]['attendance_day']=$day;
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    //////////////////////////////////////////////////////////////////////////////
    
    // update row
    public function markStudentAttendance(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        $this->RESPONSE['message']=' Student admitted Successfully.';
        //check for necessary required data   
        $required=array('rid','day','status');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        if($this->std_attendance_m->save(array($form['day']=>$form['status']),$form['rid'])==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Attendance can not be marked at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        //check if there is any sms hook  
        switch ($form['status']) {
                  case $this->std_attendance_m->LABEL_PRESENT:{
                    $filter=array('campus_id'=>$this->CAMPUSID);
                    $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_PRESENT;
                    $hooks=$this->sms_hook_m->get_rows($filter);
                    if(count($hooks)>0){
                        $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
                        $row=$this->student_m->get_by_primary($attendance->student_id);
                        foreach ($hooks as $hook){
                            //send sms to student
                            if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($row->mobile,$sms);
                            }
                            //send sms to guardian
                            if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name,
                                        '{GUARDIAN}'=>$row->guardian_name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($row->guardian_mobile,$sms);
                            }
                        }
                    }
                  }
                  break;
                  case $this->std_attendance_m->LABEL_ABSENT:{
                    $filter=array('campus_id'=>$this->CAMPUSID);
                    $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_ABSENT;
                    $hooks=$this->sms_hook_m->get_rows($filter);
                    if(count($hooks)>0){
                        $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
                        $row=$this->student_m->get_by_primary($attendance->student_id);
                        foreach ($hooks as $hook){
                            //send sms to student
                            if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($row->mobile,$sms);
                            }
                            //send sms to guardian
                            if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name,
                                        '{GUARDIAN}'=>$row->guardian_name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($row->guardian_mobile,$sms);
                            }
                        }
                    }
                  }
                  break;
                  case $this->std_attendance_m->LABEL_LEAVE:{
                    $filter=array('campus_id'=>$this->CAMPUSID);
                    $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_LEAVE;
                    $hooks=$this->sms_hook_m->get_rows($filter);
                    if(count($hooks)>0){
                        $attendance=$this->std_attendance_m->get_by_primary($form['rid']);
                        $row=$this->student_m->get_by_primary($attendance->student_id);
                        foreach ($hooks as $hook){
                            //send sms to student
                            if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($row->mobile,$sms);
                            }
                            //send sms to guardian
                            if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name,
                                        '{GUARDIAN}'=>$row->guardian_name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($row->guardian_mobile,$sms);
                            }
                        }
                    }
                  }
                  break;
                  
                  default:
                      # code...
                      break;
              }      
        

        //send back the resposne  
        $this->RESPONSE['message']='Attendance marked successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update rows
    public function markClassAttendance(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('class_id','date','status');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select attendance, class and date...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        if($this->class_m->get_rows(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['class_id']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid class...';
            echo json_encode($this->RESPONSE);exit();
        }
        $attendance_jd=get_jd_from_date($form['date'], '-', true);
        $today_jd=$this->class_m->todayjd;
        if($attendance_jd > $today_jd){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select a vlaid date (today or past date only)...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($attendance_jd < ($today_jd-180)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You can only mark the attendance of past 6 months...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $params['select']="mid,name,roll_no,image,guardian_name,guardian_mobile,mobile";
        $params['orderby']="roll_no ASC";
        $filter['class_id']=$form['class_id'];
        if(isset($form['section']) && !empty($form['section'])){$filter['section']=$form['section'];}
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $class_students=$this->student_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        $attendance_filter=array('campus_id'=>$this->CAMPUSID);
        $attendance_filter['month']=intval(get_month_from_date($form['date'], '-', true) );
        $attendance_filter['year']=intval(get_year_from_date($form['date'], '-') );
        $attendance_filter['session_id']=$filter['session_id'];
        foreach($class_students as $student){
            $attendance_filter['student_id']=$student['mid'];
            if($this->std_attendance_m->get_rows($attendance_filter,'',true)<1){
                //new month started or init attendance
                $this->std_attendance_m->add_row($attendance_filter);
            }
            $attendance=$this->std_attendance_m->get_by($attendance_filter,true);
            $day='d'.get_day_from_date($form['date'],'-');
            $this->std_attendance_m->save(array($day=>$form['status']),$attendance->mid);
                //check if there is any sms hook  
                switch ($form['status']) {
                      case $this->std_attendance_m->LABEL_PRESENT:{
                        $filter=array('campus_id'=>$this->CAMPUSID);
                        $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_PRESENT;
                        $hooks=$this->sms_hook_m->get_rows($filter);
                        if(count($hooks)>0){
                            foreach ($hooks as $hook){
                                //send sms to student
                                if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$student['name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($student['mobile'],$sms);
                                }
                                //send sms to guardian
                                if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$student['name'],
                                            '{GUARDIAN}'=>$student['guardian_name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($student['guardian_mobile'],$sms);
                                }
                            }
                        }
                      }
                      break;
                      case $this->std_attendance_m->LABEL_ABSENT:{
                        $filter=array('campus_id'=>$this->CAMPUSID);
                        $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_ABSENT;
                        $hooks=$this->sms_hook_m->get_rows($filter);
                        if(count($hooks)>0){
                            foreach ($hooks as $hook){
                                //send sms to student
                                if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$student['name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($student['mobile'],$sms);
                                }
                                //send sms to guardian
                                if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$student['name'],
                                            '{GUARDIAN}'=>$student['guardian_name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($student['guardian_mobile'],$sms);
                                }
                            }
                        }
                      }
                      break;
                      case $this->std_attendance_m->LABEL_LEAVE:{
                        $filter=array('campus_id'=>$this->CAMPUSID);
                        $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_LEAVE;
                        $hooks=$this->sms_hook_m->get_rows($filter);
                        if(count($hooks)>0){
                            foreach ($hooks as $hook){
                                //send sms to student
                                if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$student['name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($student['mobile'],$sms);
                                }
                                //send sms to guardian
                                if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$student['name'],
                                            '{GUARDIAN}'=>$student['guardian_name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($student['guardian_mobile'],$sms);
                                }
                            }
                        }
                      }
                      break;
                      
                      default:
                          # code...
                          break;
                }      
            //$i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']='Class attendance completed successfully...';
        echo json_encode( $this->RESPONSE);
        
    }
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	