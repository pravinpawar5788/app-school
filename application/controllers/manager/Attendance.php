<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance extends Manager_Controller{

/** 
* //////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
        $models = array('class_m','class_section_m','student_m','staff_m','stf_attendance_m','std_attendance_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		redirect($this->CONT_ROOT.'student', 'refresh');	
	}
    // mark student attendance
    public function student($tab=''){
        if($this->LOGIN_USER->prm_std_info<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        $this->data['main_content']='attendance_student';   
        $this->data['menu']='attendance';           
        $this->data['sub_menu']='attendance_student';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='attendance';
        /////////////////////////////////////////////////////////////
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);    
    }
    // mark staff attendance
    public function staff($tab=''){
        if($this->LOGIN_USER->prm_stf_info<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        $this->data['main_content']='attendance_staff';   
        $this->data['menu']='attendance';           
        $this->data['sub_menu']='attendance_staff';
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
                    $this->session->set_flashdata('error', 'Please choose a valid member');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_history';    
                $this->data['print_page_title']='Student History'; 

            }
            break;
            case 'report':{
                if(empty($form['rpt'])){
                    $this->session->set_flashdata('error', 'Please choose a valid report');
                    redirect($this->CONT_ROOT.'student', 'refresh');
                }
                //check which report to be printed
                switch (strtolower($form['rpt'])) {
                    case 'std_attendance':{
                        $this->data['main_content']='report_attendance_student_daily';    
                        $this->data['print_page_title']='Daily Student Attendance Report'; 
                    }break;
                    case 'std_atd_monsimple':{
                    $this->data['main_content']='report_attendance_std_monthsimple';   
                    $this->data['print_page_title']='Monthly Student Attendance Report'; 
                    }break;
                    case 'std_atd_mondetail':{
                    $this->data['main_content']='report_attendance_std_monthdetail';   
                    $this->data['print_page_title']='Monthly Student Attendance Report'; 
                    }break;
                    case 'stf_attendance':{
                        $this->data['main_content']='report_attendance_staff_daily';    
                        $this->data['print_page_title']='Daily Staff Attendance Report'; 
                    }break;
                    case 'stf_atd_monsimple':{
                    $this->data['main_content']='report_attendance_stf_monthsimple';   
                    $this->data['print_page_title']='Monthly Staff Attendance Report'; 
                    }break;
                    case 'stf_atd_mondetail':{
                    $this->data['main_content']='report_attendance_stf_monthdetail';   
                    $this->data['print_page_title']='Monthly Staff Attendance Report'; 
                    }break;
                    
                    default:{
                        $this->session->set_flashdata('error', 'Please choose a valid report');
                        redirect($this->CONT_ROOT.'student', 'refresh');
                        
                    }break;
                }

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
        $search=array('name');
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
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $params['select']="mid,name,image";
        $params['orderby']="name ASC";
        $filter['status']=$this->staff_m->STATUS_ACTIVE;
        isset($form['search']) ? $params['like']=$like : '';
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
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // filter rows
    public function filterStudents(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','roll_no');
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
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $params['select']="mid,name,section,roll_no,image";
        $params['orderby']="section ASC, roll_no ASC";
        $filter['class_id']=$form['class_id'];
        isset($form['section'])&& !empty($form['section']) ? $filter['section_id']=$form['section'] : '';
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        isset($form['search']) ? $params['like']=$like : '';
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
    public function markStaffAttendance(){
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
        if($this->stf_attendance_m->save(array($form['day']=>$form['status']),$form['rid'])==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Attendance can not be marked at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        //check if there is any sms hook  
        switch ($form['status']) {
                  case $this->stf_attendance_m->LABEL_PRESENT:{
                    $filter=array('campus_id'=>$this->CAMPUSID);
                    $filter['hook']=$this->sms_hook_m->HOOK_STAFF_PRESENT;
                    $hooks=$this->sms_hook_m->get_rows($filter);
                    if(count($hooks)>0){
                        $attendance=$this->stf_attendance_m->get_by_primary($form['rid']);
                        $row=$this->staff_m->get_by_primary($attendance->staff_id);
                        foreach ($hooks as $hook){
                            //send sms to student
                            if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->mobile,$sms);
                            }
                        }
                    }
                  }
                  break;
                  case $this->stf_attendance_m->LABEL_ABSENT:{
                    $filter=array('campus_id'=>$this->CAMPUSID);
                    $filter['hook']=$this->sms_hook_m->HOOK_STAFF_ABSENT;
                    $hooks=$this->sms_hook_m->get_rows($filter);
                    if(count($hooks)>0){
                        $attendance=$this->stf_attendance_m->get_by_primary($form['rid']);
                        $row=$this->staff_m->get_by_primary($attendance->staff_id);
                        foreach ($hooks as $hook){
                            //send sms to student
                            if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->mobile,$sms);
                            }
                        }
                    }
                  }
                  break;
                  case $this->stf_attendance_m->LABEL_LEAVE:{
                    $filter=array('campus_id'=>$this->CAMPUSID);
                    $filter['hook']=$this->sms_hook_m->HOOK_STUDENT_LEAVE;
                    $hooks=$this->sms_hook_m->get_rows($filter);
                    if(count($hooks)>0){
                        $attendance=$this->stf_attendance_m->get_by_primary($form['rid']);
                        $row=$this->staff_m->get_by_primary($attendance->staff_id);
                        foreach ($hooks as $hook){
                            //send sms to student
                            if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                                $message=htmlspecialchars_decode($hook["template"]);
                                //conversion keys
                                $key_vars=array(
                                        '{NAME}'=>$row->name
                                    );
                                ////////////////////////////////////////
                                $sms=strtr($message, $key_vars);
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->mobile,$sms);
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
    public function markAllStaffAttendance(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('date','status');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select attendance, class and date...';
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
        $params['select']="mid,name,image,mobile";
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $staff=$this->staff_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        $attendance_filter=array('campus_id'=>$this->CAMPUSID);
        $attendance_filter['month']=intval(get_month_from_date($form['date'], '-', true) );
        $attendance_filter['year']=intval(get_year_from_date($form['date'], '-') );
        $attendance_filter['session_id']=$this->session_m->getActiveSession()->mid;;
        foreach($staff as $member){
            $attendance_filter['staff_id']=$member['mid'];
            if($this->stf_attendance_m->get_rows($attendance_filter,'',true)<1){
                //new month started or init attendance
                $this->stf_attendance_m->add_row($attendance_filter);
            }
            $attendance=$this->stf_attendance_m->get_by($attendance_filter,true);
            $day='d'.get_day_from_date($form['date'],'-');
            $this->stf_attendance_m->save(array($day=>$form['status']),$attendance->mid);
                //check if there is any sms hook  
                switch ($form['status']) {
                      case $this->stf_attendance_m->LABEL_PRESENT:{
                        $filter=array('campus_id'=>$this->CAMPUSID);
                        $filter['hook']=$this->sms_hook_m->HOOK_STAFF_PRESENT;
                        $hooks=$this->sms_hook_m->get_rows($filter);
                        if(count($hooks)>0){
                            foreach ($hooks as $hook){
                                //send sms to student
                                if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$member['name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$member['mobile'],$sms);
                                }
                            }
                        }
                      }
                      break;
                      case $this->stf_attendance_m->LABEL_ABSENT:{
                        $filter=array('campus_id'=>$this->CAMPUSID);
                        $filter['hook']=$this->sms_hook_m->HOOK_STAFF_ABSENT;
                        $hooks=$this->sms_hook_m->get_rows($filter);
                        if(count($hooks)>0){
                            foreach ($hooks as $hook){
                                //send sms to student
                                if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$member['name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$member['mobile'],$sms);
                                }
                            }
                        }
                      }
                      break;
                      case $this->stf_attendance_m->LABEL_LEAVE:{
                        $filter=array('campus_id'=>$this->CAMPUSID);
                        $filter['hook']=$this->sms_hook_m->HOOK_STAFF_LEAVE;
                        $hooks=$this->sms_hook_m->get_rows($filter);
                        if(count($hooks)>0){
                            foreach ($hooks as $hook){
                                //send sms to student
                                if($hook['target']==$this->sms_hook_m->TARGET_STAFF){
                                    $message=htmlspecialchars_decode($hook["template"]);
                                    //conversion keys
                                    $key_vars=array(
                                            '{NAME}'=>$member['name']
                                        );
                                    ////////////////////////////////////////
                                    $sms=strtr($message, $key_vars);
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$member['mobile'],$sms);
                                }
                            }
                        }
                      }
                      break;
                      
                      default:
                          # code...
                          break;
                }      
        }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']='All Staff attendance completed successfully...';
        echo json_encode( $this->RESPONSE);
        
    }
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
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->mobile,$sms);
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
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->guardian_mobile,$sms);
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
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->mobile,$sms);
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
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->guardian_mobile,$sms);
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
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->mobile,$sms);
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
                                $this->sms_history_m->send_sms($this->CAMPUSID,$row->guardian_mobile,$sms);
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
        if(isset($form['section']) && !empty($form['section'])){$filter['section_id']=$form['section'];}
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
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$student['mobile'],$sms);
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
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$student['guardian_mobile'],$sms);
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
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$student['mobile'],$sms);
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
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$student['guardian_mobile'],$sms);
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
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$student['mobile'],$sms);
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
                                    $this->sms_history_m->send_sms($this->CAMPUSID,$student['guardian_mobile'],$sms);
                                }
                            }
                        }
                      }
                      break;
                      
                      default:
                          # code...
                          break;
                }      
        }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']='Class attendance completed successfully...';
        echo json_encode( $this->RESPONSE);
        
    }
    // update rows
    public function markHoliday(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please select attendance, class and date...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $attendance_jd=get_jd_from_date($form['date'], '-', true);
        $today_jd=$this->class_m->todayjd;
        if($attendance_jd > ($today_jd+30)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You can mark holiday within 1 comming month...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($attendance_jd < ($today_jd-180)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='You can only mark the holiday within past 6 months...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $params['select']="mid,name,roll_no";
        $params['orderby']="roll_no ASC";
        $filter['status']=$this->student_m->STATUS_ACTIVE;
        $filter['session_id']=$this->session_m->getActiveSession()->mid;
        $students=$this->student_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        $attendance_filter=array('campus_id'=>$this->CAMPUSID);
        $attendance_filter['month']=intval(get_month_from_date($form['date'], '-', true) );
        $attendance_filter['year']=intval(get_year_from_date($form['date'], '-') );
        $attendance_filter['session_id']=$filter['session_id'];
        foreach($students as $student){
            $attendance_filter['student_id']=$student['mid'];
            if($this->std_attendance_m->get_rows($attendance_filter,'',true)<1){
                //new month started or init attendance
                $this->std_attendance_m->add_row($attendance_filter);
            }
            $attendance=$this->std_attendance_m->get_by($attendance_filter,true);
            $day='d'.get_day_from_date($form['date'],'-');
            $this->std_attendance_m->save(array($day=>$this->std_attendance_m->LABEL_HOLIDAY),$attendance->mid);                
        }
        ///////////////////////////////////////////////////////////////////////////////////////// 
        $staff_filter=array('campus_id'=>$this->CAMPUSID);
        $staff_filter['status']=$this->staff_m->STATUS_ACTIVE;
        $staff=$this->staff_m->get_rows($staff_filter,array('select'=>'mid,name'));
        ////////////////////////////////////////////////////////////////////////
        $attendance_filter=array('campus_id'=>$this->CAMPUSID);
        $attendance_filter['month']=intval(get_month_from_date($form['date'], '-', true) );
        $attendance_filter['year']=intval(get_year_from_date($form['date'], '-') );
        $attendance_filter['session_id']=$filter['session_id'];
        foreach($staff as $member){
            $attendance_filter['staff_id']=$member['mid'];
            if($this->stf_attendance_m->get_rows($attendance_filter,'',true)<1){
                //new month started or init attendance
                $this->stf_attendance_m->add_row($attendance_filter);
            }
            $attendance=$this->stf_attendance_m->get_by($attendance_filter,true);
            $day='d'.get_day_from_date($form['date'],'-');
            $this->stf_attendance_m->save(array($day=>$this->stf_attendance_m->LABEL_HOLIDAY),$attendance->mid);                
        }
        /////////////////////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']=$form['date'].' is marked as holiday for all students and staff...';
        echo json_encode( $this->RESPONSE);
        
    }
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////


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
            if(!$this->sms_api_m->is_sms_enable()){
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
            if($this->sms_api_m->get_remaining_sms()<count($rows)){
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
                $this->sms_history_m->add_row(array('mobile'=>$row['mobile'],'message'=>$sms,'priority'=>5));
            }
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS Notification sent to ".count($rows)." Students...";
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
            if(!$this->sms_api_m->is_sms_enable()){
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
            $this->sms_history_m->add_row(array('mobile'=>$row->mobile,'message'=>$sms,'priority'=>5));
            ////////////////////////////////////////////////////////////////////////
            $this->RESPONSE['message']="SMS Notification sent to $row->name...";
            echo json_encode( $this->RESPONSE);        
    }
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	