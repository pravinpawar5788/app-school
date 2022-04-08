<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends Parent_Controller{

/** 
* /////////////////////////////////////////////////////////////////////////////////
* ****************************** CONTANTS *****************************************
* /////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	public $WEBSITE_HOME;
	
////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        
        //INIT CONSTANTS
		// $this->WEBSITE_HOME=realpath(dirname(__FILE__).'/../../../').'/website/';
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'student/';
        //load all models for this controller
        $models = array('staff_m','award_m','punishment_m','certificate_m','std_advance_m','std_attendance_m','std_acheivement_m','std_award_m','std_history_m','std_fee_history_m','std_fee_entry_m','std_fee_voucher_m','std_punishment_m','std_qual_m','std_subject_homework_m','class_subject_m','std_subject_test_result_m','std_result_m','std_subject_final_result_m','exam_term_m','std_term_result_m');
        $this->load->model($models);
        
    }
	
/** 
* /////////////////////////////////////////////////////////////////////////////
* ***************************** PUBLIC FUNCTIONS ******************************
* /////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){         
        $this->session->set_flashdata('error', 'Access Restricted!');
        redirect($this->LIB_CONT_ROOT.'', 'refresh'); 
		
		// $this->data['main_content']='student';	
		// $this->data['menu']='student';			
		// $this->data['sub_menu']='student';
        // $this->data['tab']=$tab;
		//$this->data['barcode']=new Picqer\Barcode\BarcodeGeneratorHTML();
        //$this->data['barcode']=new Picqer\Barcode\BarcodeGeneratorPNG();

        // $this->ANGULAR_INC[]='student';
        // $this->THEME_INC[]='js/plugins/extensions/jquery_ui/widgets.min.js';
        // $this->HEADER_INC[]='js/pages/task_manager_list.js';
        // $this->FOOTER_INC[]='js/pages/form_checkboxes_radios.js';
        // $generator = 
        // echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
        /////////////////////////////////////////////////////////////
		// $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    ///////Profile function/////////////
    public function profile($rid='',$tab=''){
        if(empty($rid) || $this->student_m->get_rows(array('mid'=>$rid,'father_nic'=>$this->LOGIN_USER->cnic,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid child');
            redirect($this->LIB_CONT_ROOT.'', 'refresh'); 
        }
        
        $this->data['main_content']='student_profile';    
        $this->data['menu']='student-'.$rid;            
        $this->data['sub_menu']='student';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='student_profile';
        $this->FOOTER_INC[]='js/plugins/ui/moment/moment.min.js';
        $this->FOOTER_INC[]='js/plugins/ui/fullcalendar/fullcalendar.min.js';
        // $this->FOOTER_INC[]='js/pages/fullcalendar_basic.js';
        $member=$this->student_m->get_by_primary($rid);   
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
                    $this->session->set_flashdata('error', 'Please choose a valid student');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_profile';    
                $this->data['print_page_title']='Student profile'; 

            }
            break;
            case 'list':{
                $this->data['main_content']='student_list';    
                $this->data['print_page_title']='Students List'; 

            }
            break;
            case 'allowances':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                $this->data['main_content']='staff_allowances';    
                $this->data['print_page_title']='Staff Member Allowances'; 

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


     //save profile pic
    public function upload_picture(){
        //set_time_limit(90);
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
    * /////////////////////////////////////////////////////////////////////////////
    * **************************** AJAX FUNCTIONS *********************************
    * /////////////////////////////////////////////////////////////////////////////
    */

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
    ///////////////////////////////////////////////////////////////////////////////
    /////////////////////////////FEE FUNCTIONS/////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterFee(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('title','student_name','voucher_id','roll_no','date','status','std_id');
        $like=array();
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
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        $student=$this->student_m->get_by_primary($form['rid']);
        $filter['session_id']=$student->session_id;
        $filter['class_id']=$student->class_id;

        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='month_number DESC, type DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->std_fee_voucher_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_fee_voucher_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $user=$this->student_m->get_by_primary($row['student_id']);
            $this->RESPONSE['rows'][$i]['image']=$user->image;
            $this->RESPONSE['rows'][$i]['advance_amount']=$user->advance_amount;
            $this->RESPONSE['rows'][$i]['amount']=$this->std_fee_entry_m->get_voucher_amount($row['voucher_id'],$this->std_fee_entry_m->OPT_PLUS,$this->CAMPUSID);
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // load single row
    public function loadFeeVoucher(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->std_fee_voucher_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid slip...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->std_fee_voucher_m->get_by_primary($rid);
        $due_jd=get_jd_from_date($row->due_date,'-',true);
        if($this->std_fee_voucher_m->todayjd > $due_jd && $row->status==$this->std_fee_voucher_m->STATUS_UNPAID && $row->type=$this->std_fee_voucher_m->TYPE_FEE){
            //paying late fee. add fine if not yet added for this month
            // if($this->std_fee_entry_m->get_rows(array('voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'type'=>$this->std_fee_entry_m->TYPE_LATE_FEE_FINE,'campus_id'=>$this->CAMPUSID),'',true)<1){
            //     $late_fee_fine=0;
            //     if($this->CAMPUSSETTINGS->late_fee_fine_type==$this->setting_campus_m->LATE_FEE_FINE_FIXED){$late_fee_fine=$this->CAMPUSSETTINGS->late_fee_fine;
            //     }
            //     if($this->CAMPUSSETTINGS->late_fee_fine_type==$this->setting_campus_m->LATE_FEE_FINE_PERDAY){
            //         $days=$this->std_fee_voucher_m->todayjd-$due_jd;
            //         $late_fee_fine=$this->CAMPUSSETTINGS->late_fee_fine*$days;
            //     }
            //     $ledger_id=$this->accounts_ledger_m->add_entry('Late Fee Fine',$this->accounts_m->_EDU_SERVICE,$this->accounts_m->_FEE_RECEIVABLE,$late_fee_fine,true);
            //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //     if($late_fee_fine>0){
            //         $entry=array('student_id'=>$row->student_id,'voucher_id'=>$row->voucher_id,'month_number'=>$row->month_number,'amount'=>$late_fee_fine,'remarks'=>'Late Fee Fine','type'=>$this->std_fee_entry_m->TYPE_LATE_FEE_FINE,'ledger_id'=>$ledger_id);
            //         $this->std_fee_entry_m->add_row($entry);
            //     }
            // }
        }
        $row->amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_PLUS,$this->CAMPUSID);
        $row->entries=$this->std_fee_entry_m->get_rows(array('voucher_id'=>$row->voucher_id,'campus_id'=>$this->CAMPUSID));
        $minus_amount=$this->std_fee_entry_m->get_voucher_amount($row->voucher_id,$this->std_fee_entry_m->OPT_MINUS,$this->CAMPUSID);
        $row->concession=round($minus_amount-$row->amount_paid, 2);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    ///////////////////////////////////////////////////////////////////////////////
    ///////////////////////////ATTEDANCE FUNCTIONS/////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    // load attendance record
    public function loadAttendance(){
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

        $filter['student_id']=$rid;
        $this_month=$this->student_m->month;
        $this_year=$this->student_m->year;
        $month_limit=6;
        $rows=array();
        $row_counter=0;
        for($i=0;$i<$month_limit;$i++){
            $filter['month']=$this_month;
            $filter['year']=$this_year;
            $row=$this->std_attendance_m->get_by($filter,true);
            //loop for full month
            for($j=0;$j<31;$j++){
                $day=$j+1;
                $col_num='d'.$day;
                if(!empty($row->$col_num)){
                    $key=$row->$col_num;
                    $title=$key;
                    $color="";
                    if($key==$this->std_attendance_m->LABEL_PRESENT){$title='Present';$color="#43A047";}
                    if($key==$this->std_attendance_m->LABEL_ABSENT){$title='Absent';$color="#E53935";}
                    if($key==$this->std_attendance_m->LABEL_LEAVE){$title='Leave';$color="#1E88E5";}
                    if($key==$this->std_attendance_m->LABEL_HOLIDAY){$title='Holiday';$color="#8A65E9";}
                    $month_val=$this_month;$day_val=$day;
                    if($this_month<10){$month_val='0'.intval($this_month);}if($day<10){$day_val='0'.intval($day);}
                    $single_row=array('title'=>$title,'start'=>$this_year.'-'.$month_val.'-'.$day_val,'color'=>$color);
                    $rows[$row_counter]=$single_row;
                    $row_counter++;
                }
            }
            //////////////decrement month////////
            $this_month--;
            if($this_month<1){$this_year--;$this_month=12;}
        }
        $this->RESPONSE['events']=$rows;
        $this->RESPONSE['default_date']=date('Y-m-d');
        ///////////////////////////////////////////////////
        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     $color_green="#43A047";$color_red="#E53935";$color_default="#1E88E5";
        //     $this->RESPONSE['rows'][$i]['color']=$color_default;
        //     if($row['title']=='Present'){$this->RESPONSE['rows'][$i]['color']=$color_green;}
        //     if($row['title']=='Absent'){$this->RESPONSE['rows'][$i]['color']=$color_red;}
        //     $i++;
        // }

        // $report=$this->std_attendance_m->get_attendance_report($rid,'yearly');
        // $events=array();
        // foreach ($report as $key=>$value) {
        //     # code...
        // }

        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     $color_green="#43A047";$color_red="#E53935";$color_default="#1E88E5";
        //     $this->RESPONSE['rows'][$i]['color']=$color_default;
        //     if($row['title']=='Present'){$this->RESPONSE['rows'][$i]['color']=$color_green;}
        //     if($row['title']=='Absent'){$this->RESPONSE['rows'][$i]['color']=$color_red;}
        //     $i++;
        // }
        // $row=$this->student_m->get_by_primary($rid);
        // $filter['student_id']=$row->mid;
        // $row->total_awards=$this->std_award_m->get_rows($filter,'',true);
        // $row->total_punishments=$this->std_punishment_m->get_rows($filter,'',true);
        // $row->total_acheivements=$this->std_acheivement_m->get_rows($filter,'',true);
        // //send back resposne
        // $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }
    ////////////////////////////////////////////////////////////////////////////
    ///////////////////////HOMEWORK FUNCTIONS///////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

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
            // isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            $student=$this->student_m->get_by_primary($form['rid']);
            $filter['session_id']=$student->session_id;
            $filter['class_id']=$student->class_id;
            if($student->section_id>0){$filter['section_id']=$student->section_id;}
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
            // $roles=$this->stf_role_m->get_values_array('mid','title',array());
            $i=0;
            foreach($this->RESPONSE['rows'] as $row){
                $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name;
                $this->RESPONSE['rows'][$i]['homework']=html_entity_decode(htmlspecialchars_decode($row['homework']));
                $i++;
            }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////     
            // $student=$this->student_m->get_by_primary($form['rid']);  
            // $this->RESPONSE['total_advance']=$student->advance_amount;
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////
    ///////////////////ADVANCE FUNCTIONS////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

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
            isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_advance_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_advance_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $roles=$this->stf_role_m->get_values_array('mid','title',array());
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     $this->RESPONSE['rows'][$i]['password']='';
            //     $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
            //     $i++;
            // }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////     
            $student=$this->student_m->get_by_primary($form['rid']);  
            $this->RESPONSE['total_advance']=$student->advance_amount;
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////
    /////////////////////HISTORY FUNCTIONS//////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

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
            isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_history_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_history_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $roles=$this->stf_role_m->get_values_array('mid','title',array());
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     $this->RESPONSE['rows'][$i]['password']='';
            //     $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
            //     $i++;
            // }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////
    ///////////////////////QUALIFICATION FUNCTIONS//////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterQual(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('session','class','roll_number','status','date');
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_qual_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_qual_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $roles=$this->stf_role_m->get_values_array('mid','title',array());
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     $this->RESPONSE['rows'][$i]['password']='';
            //     $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
            //     $i++;
            // }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////
    //////////////////////AWARDS FUNCTIONS//////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

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
            isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_award_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_award_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $roles=$this->stf_role_m->get_values_array('mid','title',array());
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     $this->RESPONSE['rows'][$i]['password']='';
            //     $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
            //     $i++;
            // }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////
    //////////////////////PUNISHMENT FUNCTIONS//////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

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
            isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_punishment_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_punishment_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $roles=$this->stf_role_m->get_values_array('mid','title',array());
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     $this->RESPONSE['rows'][$i]['password']='';
            //     $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
            //     $i++;
            // }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////
    ///////////////////////ACHIEVEMENT FUNCTIONS////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

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
            isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_acheivement_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_acheivement_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $roles=$this->stf_role_m->get_values_array('mid','title',array());
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     $this->RESPONSE['rows'][$i]['password']='';
            //     $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
            //     $i++;
            // }
            // print_r($this->RESPONSE['rows']);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }
    ///////////////////////////////////////////////////////////////////////////
    /////////////////STAFF ATTENDANCE REPORT FUNCTION//////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    // filter rows
    public function filterStudentAttendanceEvents(){
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



    //////////////////////////////////////////////////////////////////
    ///////////////STUDENT TEST PROGRESS DETAILS//////////////////////
    //////////////////////////////////////////////////////////////////
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
        ///////////////////////////////////////////////////////////////////////////////
        $student=$this->student_m->get_by_primary($form['rid']);
        $filter['session_id']=$student->session_id;
        $filter['student_id']=$student->mid;

        // isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
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


            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    //////////////////////////////////////////////////////////////////
    ///////////////STUDENT TERM RESULT ///////////////////////////////
    //////////////////////////////////////////////////////////////////
    // filter rows
    public function filterTermResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////
        $student=$this->student_m->get_by_primary($form['rid']);
        $filter['session_id']=$student->session_id;
        $filter['student_id']=$student->mid;

        // isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $session=$this->session_m->getActiveSession();
        $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->std_term_result_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_term_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){      
            $this->RESPONSE['rows'][$i]['term']=$this->exam_term_m->get_by_primary($row['term_id'])->name; 
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    //////////////////////////////////////////////////////////////////
    ///////////////STUDENT FINAL RESULT //////////////////////////////
    //////////////////////////////////////////////////////////////////
    // filter rows
    public function filterFinalResult(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////
        $student=$this->student_m->get_by_primary($form['rid']);
        $filter['session_id']=$student->session_id;
        $filter['student_id']=$student->mid;

        // isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        // isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        // isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $session=$this->session_m->getActiveSession();
        // $filter['session_id']=$session->mid;
        $this->RESPONSE['rows']=$this->std_subject_final_result_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_subject_final_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){      
            $this->RESPONSE['rows'][$i]['session']=$this->session_m->get_by_primary($row['session_id'])->title; 
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 
            $i++;
        }
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	