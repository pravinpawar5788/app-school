<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends Manager_Controller{

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
        if($this->LOGIN_USER->prm_std_info<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'student/';
        //load all models for this controller
        $models = array('class_m','class_section_m','student_m','staff_m','award_m','punishment_m','certificate_m','std_advance_m','std_attendance_m','std_acheivement_m','std_award_m','std_history_m','std_fee_history_m','std_fee_entry_m','std_fee_voucher_m','std_punishment_m','std_qual_m','accounts_ledger_m','std_subject_homework_m','class_subject_m','std_subject_test_result_m','std_result_m','std_group_m','concession_type_m','std_fee_concession_m','class_feepackage_m');
        
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
		
		$this->data['main_content']='student';	
		$this->data['menu']='student';			
		$this->data['sub_menu']='student';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='student';
        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    ///////Profile function/////////////
    public function profile($rid='',$tab=''){
        if(empty($rid) || $this->student_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid student');
            redirect($this->LIB_CONT_ROOT.'staff', 'refresh'); 
        }
        
        $this->data['main_content']='student_profile';    
        $this->data['menu']='student';            
        $this->data['sub_menu']='student';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='student_profile';
        $this->FOOTER_INC[]='js/plugins/ui/moment/moment.min.js';
        $this->FOOTER_INC[]='js/plugins/ui/fullcalendar/fullcalendar.min.js';

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
                // $this->data['main_content']='student_profile';    
                $this->data['main_content']='form_student_registation_filled';   
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
            case 'feehistory':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_feehistory';    
                $this->data['print_page_title']='Student Fee History'; 

            }
            break;
            case 'report':{
                if(empty($form['rpt'])){
                    $this->session->set_flashdata('error', 'Please choose a valid report');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                //check which report to be printed
                switch (strtolower($form['rpt'])) {
                    case 'attendance':{
                        $this->data['main_content']='report_attendance_daily';    
                        $this->data['print_page_title']='Daily Attendance Report'; 
                    }break;
                    case 'mrgconcession':{
                        $this->data['main_content']='report_std_marginal_concession';    
                        $this->data['print_page_title']='Student Fee Marginal Concession Report'; 
                    }break;
                    
                    default:{
                        $this->session->set_flashdata('error', 'Please choose a valid report');
                        redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                        
                    }break;
                }

            }
            break;
            case 'form':{
                if(empty($form['type'])){
                    $this->session->set_flashdata('error', 'Please choose a valid form');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                //check which report to be printed
                switch (strtolower($form['type'])) {
                    case 'stdreg':{
                        $this->data['main_content']='form_student_registation_blank';    
                        $this->data['print_page_title']='Student Admission Form'; 
                    }break;
                    
                    default:{
                        $this->session->set_flashdata('error', 'Please choose a valid form');
                        redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                        
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


     //save profile pic
    public function upload_picture(){
        //set_time_limit(90);
        //upload artwork file
        $size=isset($this->SETTINGS['max_upload_size']) ? $this->SETTINGS['max_upload_size'] : '800';    //0.8MB
        $min_width=$this->config->item('app_img_min_width');
        $min_height=$this->config->item('app_img_min_height');
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
                $this->session->set_flashdata('error', $data['file_error']."Max allowed file size is $size KB. Minimum dimension must be $min_width x $min_height Px.");
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
        // $size='250';    //0.25MB
        $size=isset($this->SETTINGS['max_upload_size']) ? $this->SETTINGS['max_upload_size'] : '800';    //0.8MB
        $allowed_types='jpg|jpeg|png|bmp';
        $upload_file_name=$file_name;    
        $min_width=$this->config->item('app_img_min_width');
        $min_height=$this->config->item('app_img_min_height');
        $upload_data=$this->upload_file($path,$size,$allowed_types,$upload_file_name,$new_name,$min_width,$min_height);
        return $upload_data;
    }  

    //upload file
    private function upload_sheet($file_name='file',$new_name=''){ 
       $path='./uploads/temp';
       $size='15220';
       $allowed_types='xls|xlsx';
       $upload_file_name=$file_name;        
       $upload_data=$this->upload_file($path,$size,$allowed_types,$upload_file_name,$new_name);
       return $upload_data;
    }   


    // save New file
    public function addfromfile(){

            set_time_limit(60*30);  //30 minutes
            $redir=$this->LIB_CONT_ROOT.'student';
            // get input fields into array        
            $form=$this->input->safe_post(array('session_id','class_id','section_id'));  
            //check if valid user & list           
            if (empty($form['session_id']) || empty($form['class_id']) ) {
                $this->session->set_flashdata('error','Please choose session and class');
                redirect($redir, 'refresh');
            }
            $class=$this->class_m->get_by_primary($form['class_id']);

            $new_file_name='bulkadd-'.date('ymdhis').mt_rand(11,99);
            $data=$this->upload_sheet('file',$new_file_name);

            if($data['file_uploaded']==FALSE){
                $this->session->set_flashdata('error', $data['file_error']);
                redirect($redir, 'refresh');
            }
            $file_name=$data['file_name']; 
            ///////EVERY THING IS FINE PROCESS FILE TO GET CONTACTS/////
            $students=array();
            $saved_students=0; 
            $total_students=0;
            $last_admission_number=$this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_ADMISSION_NUMBER];
            $last_computer_number=$this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_COMPUTER_NUMBER];
            $last_family_number=$this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_FAMILY_NUMBER];
            
            
            
            ///////////////////////////////////////////////////////////////////
            //excel file is uploaded
            $this->load->library('excel');
            $file='./uploads/temp/'.$file_name;
            //read file from path
            $obj=  PHPExcel_IOFactory::load($file);
            
            $lastColumn ='Q';
            $student=array('campus_id'=>$this->CAMPUSID);
            $student['session_id']=$form['session_id'];
            $student['admission_session_id']=$form['session_id'];
            $student['class_id']=$form['class_id'];
            $student['section_id']=$form['section_id'];
            $student['fee']=$class->fee;
            $student['fee_type']=$this->student_m->FEE_TYPE_MONTHLY;
            $student['status']=$this->student_m->STATUS_ACTIVE;
            $student['date']=$this->student_m->date;
            $student['month']=$this->student_m->month;
            $student['year']=$this->student_m->year;
            $student['jd']=$this->student_m->todayjd;
            $student['admission_class']=$class->title;
            $student['image']='default.png';

            $i=0;
            foreach($obj->getActiveSheet()->getRowIterator() as $rowIndex => $row) {
                //Convert the cell data from this row to an array of values
                //just as though you'd retrieved it using fgetcsv()
                $i++;
                if($i<=1){continue;}
                $this_row = $obj->getActiveSheet()->rangeToArray('A'.$rowIndex.':'.$lastColumn.$rowIndex);
                $total_students++;
                $adm_number=trim($this_row[0][0]);
                $s_name=trim($this_row[0][1]);
                $grd_name=trim($this_row[0][2]);
                $grd_mobile=trim($this_row[0][3]);
                $address=trim($this_row[0][4]);
                $gender=trim($this_row[0][5]);
                $f_name=trim($this_row[0][6]);
                $f_nic=trim($this_row[0][7]);
                $mother_name=trim($this_row[0][8]);
                $mother_nic=trim($this_row[0][9]);
                $blood_group=trim($this_row[0][10]);
                $std_mobile=trim($this_row[0][11]);
                $dob=trim($this_row[0][12]);
                $std_nic=trim($this_row[0][13]);
                $religion=trim($this_row[0][14]);
                $cast=trim($this_row[0][15]);
                $medical_history=trim($this_row[0][16]);

                if(!empty($adm_number) && !empty($s_name) && !empty($grd_name) && !empty($grd_mobile) && !empty($gender) && !empty($address)){

                    if($this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'admission_no'=>$adm_number),'',true)>0){continue;}
                    $last_computer_number++;$last_family_number++;
                    $student['student_id']=$this->student_m->get_new_student_id($this->SETTINGS[$this->system_setting_m->_STDID_KEY]);
                    $student['computer_number']=$last_computer_number;
                    $student['family_number']=$last_family_number;
                    $student['admission_no']=$adm_number;
                    $student['password']=mt_rand(111,999);
                    $student['name']=$s_name;
                    $student['guardian_name']=$grd_name;
                    $student['guardian_mobile']=$grd_mobile;
                    $student['date_of_birth']=$dob;
                    $student['nic']=$std_nic;
                    $student['gender']=strtolower($gender);
                    $student['blood_group']=$blood_group;
                    $student['medical_problem']=$medical_history;
                    $student['mobile']=$std_mobile;
                    $student['father_name']=$f_name;
                    $student['father_nic']=$f_nic;
                    $student['mother_name']=$mother_name;
                    $student['mother_nic']=$mother_nic;
                    $student['religion']=$religion;
                    $student['cast']=$cast;

                    /////////////////////////////////////////
                    $last_admission_number=$adm_number;
                    array_push($students,$student);
                    $saved_students++;
                }
            }


            if($saved_students>0){
                $this->student_m->save_batch($students);
                $this->campus_setting_m->save_settings_array(array('last_admission_no'=>$last_admission_number),$this->CAMPUSID);
                $this->campus_setting_m->save_settings_array(array('last_computer_number'=>$last_computer_number),$this->CAMPUSID);
                $this->campus_setting_m->save_settings_array(array('last_family_number'=>$last_family_number),$this->CAMPUSID);
            }
            
            $this->session->set_flashdata('success', $saved_students.' students saved from total '.$total_students.' students successfully.');
            redirect($redir, 'refresh'); 

            
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
        $search=array('name','father_name','father_nic','student_id','nic','mobile','blood_group','gender','roll_no');
        $like=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['gender']) && !empty($form['gender']) ? $filter['gender']= $form['gender'] : '';
        isset($form['blood_group']) && !empty($form['blood_group']) ? $filter['blood_group']= $form['blood_group'] : '';
        isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        if(isset($form['class_id']) && !empty($form['class_id']) ){
            if($form['class_id']=='alumni'){
                //only show alumni students
                $filter['class_id']= 0;
            }else{
                //show filtered class students
                $filter['class_id']= $form['class_id'];
            }
        }else{
            //only show students with active classes
            $filter['class_id >']= 0;
        } 
        isset($form['fee']) && !empty($form['fee']) ? $filter['fee_type']= $form['fee'] : '';
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
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='class_id ASC, roll_no ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->student_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['password']='';
            if($row['class_id']>0){$this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];}
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['last_admission_number']=intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_ADMISSION_NUMBER]);
        $this->RESPONSE['last_computer_number']=intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_COMPUTER_NUMBER]);
        $this->RESPONSE['last_family_number']=intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_FAMILY_NUMBER]);
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function add(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        $this->RESPONSE['message']=' Student admitted Successfully.';
        //check for necessary required data   
        $required=array('name','admission_number','class_id','session_id','guardian_name','guardian_mobile','computer_number','family_number');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   

        if($this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'admission_no'=>$form['admission_number']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This admission number is already alotted to a student. Please choose another admission number...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'computer_number'=>$form['computer_number']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This computer number is already alotted to an other student. Please choose other computer number...';
            echo json_encode($this->RESPONSE);exit();            
        }


        $form['student_id']=$this->student_m->get_new_student_id($this->SETTINGS[$this->system_setting_m->_STDID_KEY]);
        $form['password']=mt_rand(111,999); 
        $form['admission_session_id']=$form['session_id'];
        $form['admission_no']=$form['admission_number'];
        if(empty($form['computer_number'])){
            $form['computer_number']=intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_ADMISSION_NUMBER])+1;
        }
        if(empty($form['family_number'])){
            $form['family_number']=intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_LAST_FAMILY_NUMBER])+1;
        }
        $form['admission_class']=$this->class_m->get_by_primary($form['class_id'])->title;
        if(!empty($form['section_id'])){
            $section=$this->class_section_m->get_by_primary($form['section_id']);
            $form['section']=$section->name;            
        } 
        if($this->IS_COLLEGE){
            $form['fee_type']=$this->student_m->FEE_TYPE_FIXED;
            if(!empty($this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE])){
                $form['fee_type']=$this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE];
            }
        }


        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        $rid=$this->student_m->add_row($form);              
        if($rid==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //create student voucher
        isset($form['create_fee'])&& intval($form['create_fee'])>0 ? $create_fee=true : $create_fee=false;
        isset($form['create_voucher'])&& intval($form['create_voucher'])>0 ? $create_voucher=true : $create_voucher=false;
        isset($form['is_installments'])&& intval($form['is_installments'])>0 ? $is_installments=true : $is_installments=false;
        isset($form['force_inspkg'])&& intval($form['force_inspkg'])>0 ? $force_inspkg=true : $force_inspkg=false;
        $admission_fee=floatval($form['admission_fee']);
        $security_fee=floatval($form['security_fee']);
        $annual_fund=floatval($form['annual_fund']);
        $prospectus_fee=floatval($form['prospectus']);
        $title_admission_fee="Admission Fee";
        $title_security_fee="Security Fee";
        $title_annual_funds="Annual Funds";
        $title_prospectus="Prospectus";
        if(!$this->IS_COLLEGE && $create_voucher){
            if($admission_fee>0 || $security_fee>0 || $annual_fund>0 || $prospectus_fee>0){
                //create voucher
                $blank_voucher=$this->std_fee_voucher_m->create_blank_voucher($rid,'Admission Voucher',$this->std_fee_voucher_m->TYPE_ADMISSION);
                if($blank_voucher!==false){
                    $voucher=$this->std_fee_voucher_m->get_by_primary($blank_voucher['vid']);
                    if($admission_fee>0){
                        $ledger_title="Admission Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_admission_fee,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$admission_fee,true,$ledger_title,$ledger_title);                        
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$admission_fee,'remarks'=>$title_admission_fee,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    if($security_fee>0){
                        $ledger_title="Security Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_security_fee,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$security_fee,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$security_fee,'remarks'=>$title_security_fee,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    if($annual_fund>0){
                        $ledger_title="Annual Fund - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_annual_funds,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$annual_fund,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$annual_fund,'remarks'=>$title_annual_funds,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    if($prospectus_fee>0){
                        $ledger_title="Prospectus Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_prospectus,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$prospectus_fee,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$prospectus_fee,'remarks'=>$title_prospectus,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                }
            }
        }elseif($this->IS_COLLEGE){
            if($create_fee && $this->CAMPUSSETTINGS[$this->campus_setting_m->_STD_FEE_TYPE]!=$this->student_m->FEE_TYPE_MONTHLY){
                //create voucher$student_fee=$form['fee'];
                // $pkg_percentage=0;
                // if(intval($form['pkg_obt_marks'])>0 && intval($form['pkg_total_marks'])>0){
                //     $pkg_percentage=floor((intval($form['pkg_obt_marks'])/intval($form['pkg_total_marks']))*100);                    
                // }
                $pkg_percentage=intval($form['pkg_obt_marks']);
                $title_student_fee=" Fee Package ";
                $student_fee=$form['fee'];

                if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$pkg_percentage,'obt_max_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),'',true)>0){
                    $package_policy=$this->class_feepackage_m->get_by(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$pkg_percentage,'obt_max_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),true);
                    $student_fee=$package_policy->amount;        
                    $title_student_fee.=' PKG@'.$package_policy->name;        
                }elseif($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$pkg_percentage,'obt_min_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),'',true)>0){
                    $package_policy=$this->class_feepackage_m->get_by(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$pkg_percentage,'obt_min_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),true);
                    $student_fee=$package_policy->amount;        
                    $title_student_fee.=' PKG@'.$package_policy->name;   
                }
                isset($form['frequency'])&&intval($form['frequency'])>0 ? $frequency=$form['frequency'] : $frequency=1;
                if(isset($form['due_date']) && !empty($form['due_date']) ){$due_date=$form['due_date'];}else{$due_date=$this->student_m->date;}
                $day=get_day_from_date($due_date,'-');
                $month=get_month_from_date($due_date,'-',true);
                $year=get_year_from_date($due_date,'-');
                /////////////////////////////////////////
                $blank_voucher=$this->std_fee_voucher_m->create_blank_voucher($rid,$title_student_fee,$this->std_fee_voucher_m->TYPE_FEE,'',$due_date);
                if($blank_voucher!==false){
                    $voucher=$this->std_fee_voucher_m->get_by_primary($blank_voucher['vid']);                    
                    //save fee
                    if($student_fee>0 && $is_installments==false){
                        $ledger_title="Student Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$ledger_title,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$student_fee,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$student_fee,'remarks'=>'Fee Package','ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }elseif($student_fee>0 && $is_installments){

                        $total_installments=$form['installments'];
                        $first_installment=floatval($form['first_installment']);
                        $installment=floatval($form['installment']);
                        if($force_inspkg){
                            $installment=($student_fee-$first_installment)/$total_installments;
                        }

                        $ledger_title="Student Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$ledger_title,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$first_installment,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$first_installment,'remarks'=>'First Installment','ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                        for($i=0;$i<$total_installments;$i++){
                            $installment_year=$year;  
                            $installment_month=$month+($frequency*($i+1));
                            if($installment_month>12){$installment_month-=12;$installment_year++;}  
                            $due_date=$day.'-'.month_string($installment_month).'-'.$installment_year;
                            $title_installment="Installment No. ".($i+2).' - '.$form['name'];
                            $blank_voucher_2=$this->std_fee_voucher_m->create_blank_voucher($rid,$title_installment,$this->std_fee_voucher_m->TYPE_FEE,'',$due_date);
                            if($blank_voucher_2!==false){                                
                                $entry_title="Installment No. ".($i+2);
                                $ledger_title="Installment No. ".($i+2).' - '.$form['name'];
                                $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$ledger_title,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$installment,true,$ledger_title,$ledger_title);                     
                                $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$blank_voucher_2['voucher_id'],'student_id'=>$rid,'amount'=>$installment,'remarks'=>$entry_title,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                                $this->std_fee_entry_m->add_row($entry);
                            }
                        }

                    }
                    ///////////admission entries///////////
                    if($admission_fee>0){
                        $ledger_title="Admission Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_admission_fee,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$admission_fee,true,$ledger_title,$ledger_title);                        
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$admission_fee,'remarks'=>$title_admission_fee,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    if($security_fee>0){
                        $ledger_title="Security Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_security_fee,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$security_fee,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$security_fee,'remarks'=>$title_security_fee,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    if($annual_fund>0){
                        $ledger_title="Annual Fund - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_annual_funds,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$annual_fund,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$annual_fund,'remarks'=>$title_annual_funds,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                    if($prospectus_fee>0){
                        $ledger_title="Prospectus Fee - ".$form['name'];
                        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_prospectus,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$prospectus_fee,true,$ledger_title,$ledger_title);                     
                        $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$prospectus_fee,'remarks'=>$title_prospectus,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                        $this->std_fee_entry_m->add_row($entry);
                    }
                }

            }elseif($create_voucher){
                //only create admission voucher
                if($admission_fee>0 || $security_fee>0 || $annual_fund>0 || $prospectus_fee>0){
                    //create voucher
                    $blank_voucher=$this->std_fee_voucher_m->create_blank_voucher($rid,'Admission Voucher',$this->std_fee_voucher_m->TYPE_ADMISSION);
                    if($blank_voucher!==false){
                        $voucher=$this->std_fee_voucher_m->get_by_primary($blank_voucher['vid']);
                        if($admission_fee>0){
                            $ledger_title="Admission Fee - ".$form['name'];
                            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_admission_fee,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$admission_fee,true,$ledger_title,$ledger_title);                        
                            $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$admission_fee,'remarks'=>$title_admission_fee,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                            $this->std_fee_entry_m->add_row($entry);
                        }
                        if($security_fee>0){
                            $ledger_title="Security Fee - ".$form['name'];
                            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_security_fee,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$security_fee,true,$ledger_title,$ledger_title);                     
                            $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$security_fee,'remarks'=>$title_security_fee,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                            $this->std_fee_entry_m->add_row($entry);
                        }
                        if($annual_fund>0){
                            $ledger_title="Annual Fund - ".$form['name'];
                            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_annual_funds,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$annual_fund,true,$ledger_title,$ledger_title);                     
                            $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$annual_fund,'remarks'=>$title_annual_funds,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                            $this->std_fee_entry_m->add_row($entry);
                        }
                        if($prospectus_fee>0){
                            $ledger_title="Prospectus Fee - ".$form['name'];
                            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title_prospectus,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$prospectus_fee,true,$ledger_title,$ledger_title);                     
                            $entry=array('campus_id'=>$this->CAMPUSID,'voucher_id'=>$voucher->voucher_id,'student_id'=>$rid,'amount'=>$prospectus_fee,'remarks'=>$title_prospectus,'ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_PLUS);
                            $this->std_fee_entry_m->add_row($entry);
                        }
                    }
                }
            }

        }

        $this->campus_setting_m->save_setting($this->campus_setting_m->_LAST_ADMISSION_NUMBER,$form['admission_number'],$this->CAMPUSID);
        $this->campus_setting_m->save_setting($this->campus_setting_m->_LAST_COMPUTER_NUMBER,$form['computer_number'],$this->CAMPUSID);
        $this->campus_setting_m->save_setting($this->campus_setting_m->_LAST_FAMILY_NUMBER,$form['family_number'],$this->CAMPUSID);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new student account for (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //check if there is any sms hook        
        $filter=array('campus_id'=>$this->CAMPUSID);
        $filter['hook']=$this->sms_hook_m->HOOK_ADMISSION;
        $hooks=$this->sms_hook_m->get_rows($filter);
        if(count($hooks)>0){
            foreach ($hooks as $hook){
                //send sms to student
                if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$form['name']
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$form['mobile'],$sms);
                }
                //send sms to guardian
                if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                    $message=htmlspecialchars_decode($hook["template"]);
                    //conversion keys
                    $key_vars=array(
                            '{NAME}'=>$form['name'],
                            '{GUARDIAN}'=>$form['guardian_name']
                        );
                    ////////////////////////////////////////
                    $sms=strtr($message, $key_vars);
                    $this->sms_history_m->send_sms($this->CAMPUSID,$form['guardian_mobile'],$sms);
                }
            }
        }

        //send back the resposne  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function update(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','class_id','session_id','fee','guardian_name','guardian_mobile');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        if($this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'admission_no'=>$form['admission_no'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This admission number is already alotted to an other student. Please choose other admission number...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if($this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'computer_number'=>$form['computer_number'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This computer number is already alotted to an other student. Please choose other computer number...';
            echo json_encode($this->RESPONSE);exit();            
        }

        if(isset($form['password'])){
            unset($form['password']);
        }   

        //save data in database                
        if($this->student_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated student account (".$form['name'].").";
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
        if(empty($rid)|| $this->student_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }

        // $row=$this->staff_m->get_by_primary($rid);
        if($this->student_m->delete($rid)==false){
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

    public function filterClassSections(){
        // get input fields into array
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['rows']=$this->class_section_m->get_rows(array('campus_id'=>$this->CAMPUSID,'class_id'=>$form['class_id']),array('orderby'=>'name ASC') );
        // $this->RESPONSE['fee']=$this->class_m->get_by_primary($form['class_id'])->fee;
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

    public function filterClassFeePackage(){
        // get input fields into array
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $result=array();
        if(intval($form['pkg_total_marks'])>0 && intval($form['pkg_obt_marks'])>0){
            // $pkg_percentage=floor((intval($form['pkg_obt_marks'])/intval($form['pkg_total_marks']))*100);
            $pkg_percentage=intval($form['pkg_obt_marks']);
            if($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$pkg_percentage,'obt_max_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),'',true)>0){
                $package_policy=$this->class_feepackage_m->get_by(array('campus_id'=>$this->CAMPUSID,'obt_min_percent >='=>$pkg_percentage,'obt_max_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),true);
                $result['policy']=$package_policy;          
            }elseif($this->class_feepackage_m->get_rows(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$pkg_percentage,'obt_min_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),'',true)>0){
                $package_policy=$this->class_feepackage_m->get_by(array('campus_id'=>$this->CAMPUSID,'obt_max_percent >='=>$pkg_percentage,'obt_min_percent <='=>$pkg_percentage,'class_id'=>$form['class_id']),true);
                $result['policy']=$package_policy;  
            }else{
                $result['policy']=array();
            }
        }
        $this->RESPONSE['result']=$result;
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }



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
            if(empty($form['rid'])||$this->student_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid student...';
                echo json_encode($this->RESPONSE);exit();
            }

            //save data in database
            if($this->student_m->save(array('status'=>$form['status']),$form['rid'])===false){
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
            if(empty($form['rid'])||$this->student_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid student...';
                echo json_encode($this->RESPONSE);exit();
            }

            $form['password']=$this->user_m->hash($form['password']);  
            //save data in database
            if($this->student_m->save(array('password'=>$form['password']),$form['rid'])===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Password can not be updated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }

            //send back repsonse
            $this->RESPONSE['message']='Password Updated Successfully...';
            echo json_encode($this->RESPONSE);exit();
    }




    // create row
    public function createFeeVoucher(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','title','amount','due_date');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        $student=$this->student_m->get_by_primary($form['rid']);
        //create student voucher
        $name=$form['name'];
        $title=$form['title'];
        $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,$title,$this->accounts_m->_FEE_RECEIVABLE,$this->accounts_m->_STUDENT_FEE,$form['amount'],true,$title,$title);
        if($this->std_fee_entry_m->create_voucher($form['rid'],$form['amount'],$name,$title,'',$ledger_id,$form['due_date']) == FALSE){
            $this->RESPONSE['message']=' An error occured. Please try again later!';
            echo json_encode($this->RESPONSE);exit();
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created manual fee voucher for student (".$student->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));


        //send back the resposne  
        $this->RESPONSE['message']='Voucher created successfully.';
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

        isset($form['target']) && $form['target']=='grd' ? $target='guardian_mobile' : $target='mobile';
        isset($form['gender']) && !empty($form['gender']) ? $filter['gender']= $form['gender'] : '';
        isset($form['blood_group']) && !empty($form['blood_group']) ? $filter['blood_group']= $form['blood_group'] : '';
        isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        isset($form['session_id']) && !empty($form['session_id']) ? $filter['session_id']= $form['session_id'] : '';
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        isset($form['fee']) && !empty($form['fee']) ? $filter['fee >']= $form['fee'] : '';

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
            $sms=strtr($message, $key_vars);
            $this->sms_history_m->add_row(array('mobile'=>$row[$target],'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
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
        if(!$this->smspoint->is_sms_enable()){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='SMS sending option is not active. Please enable it in SMS API Settings menu...';
            echo json_encode($this->RESPONSE);exit();
        }
        isset($form['rid']) && !empty($form['rid']) ? $filter['mid']= $form['rid'] : '';
        isset($form['target']) && $form['target']=='grd' ? $target='guardian_mobile' : $target='mobile';
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
        $this->sms_history_m->add_row(array('mobile'=>$row->$target,'message'=>$sms,'priority'=>5,'campus_id'=>$this->CAMPUSID));
        ////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['message']="SMS Notification sent to $row->name...";
        echo json_encode( $this->RESPONSE);        
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
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->std_advance_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_advance_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////     
        $student=$this->student_m->get_by_primary($form['rid']);  
        $this->RESPONSE['total_advance']=$student->advance_amount;
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
        if($this->student_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->student_m->get_by_primary($form['rid']);
        $form['student_id']=$member->mid;

        //save data in database   
        $form['campus_id']=$this->CAMPUSID;             
        if($this->std_advance_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Advance amount cannot be received at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  
        //add to advance amount
        $advance_type='plus';
        isset($form['type']) && $form['type']=='minus' ? $advance_type='minus' : '';
        $this->student_m->update_advance($member->mid,$form['amount'],$advance_type);
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." created an advance entry(".$form['title'].", ".$this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$form['amount'].", $advance_type) for student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        if($advance_type=='plus'){
            $msg=$member->name." has deposited an advance amount(".$this->SETTINGS[$this->system_setting_m->_CURRENCY_SYMBOL].$form['amount'].") for upcoming fee payments.";
            $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Advance Deposit','description'=>$msg));

            //create ledger entry for this advance
            $ledger_id=$this->accounts_ledger_m->add_entry($this->CAMPUSID,'Advance amount received '.ucwords(strtolower($member->name)),$this->accounts_m->_CASH,$this->accounts_m->_FEE_ADVANCE,$form['amount'],true);
            $voucher=$this->std_fee_entry_m->create_voucher($member->mid,$form['amount'],$form['title'],'Advance Fee to be received',$this->std_fee_voucher_m->STATUS_PAID,-1);
            if($voucher != FALSE){
                $entry=array('voucher_id'=>$voucher['voucher_id'],'student_id'=>$member->mid,'amount'=>$form['amount'],'remarks'=>'Advance Fee Received','ledger_id'=>$ledger_id,'operation'=>$this->std_fee_entry_m->OPT_MINUS,'campus_id'=>$this->CAMPUSID);
                $this->std_fee_entry_m->add_row($entry);
                $this->RESPONSE['message']=' Data Saved Successfully and voucher created for the student.';
            }

            //check if there is any sms hook        
            $filter=array('campus_id'=>$this->CAMPUSID);
            $filter['hook']=$this->sms_hook_m->HOOK_ADVANCE_DEPOSIT;
            $hooks=$this->sms_hook_m->get_rows($filter);
            if(count($hooks)>0){
                foreach ($hooks as $hook){
                    //send sms to student
                    if($hook['target']==$this->sms_hook_m->TARGET_STUDENT){
                        $message=htmlspecialchars_decode($hook["template"]);
                        //conversion keys
                        $key_vars=array(
                                '{NAME}'=>$member->name,
                                '{GUARDIAN}'=>$member->guardian_name,
                                '{AMOUNT}'=>$form['amount']
                            );
                        ////////////////////////////////////////
                        $sms=strtr($message, $key_vars);
                        $this->sms_history_m->send_sms($member->mobile,$sms);
                    }
                    //send sms to guardian
                    if($hook['target']==$this->sms_hook_m->TARGET_GAURDIAN){
                        $message=htmlspecialchars_decode($hook["template"]);
                        //conversion keys
                        $key_vars=array(
                                '{NAME}'=>$member->name,
                                '{GUARDIAN}'=>$member->guardian_name,
                                '{AMOUNT}'=>$form['amount']
                            );
                        ////////////////////////////////////////
                        $sms=strtr($message, $key_vars);
                        $this->sms_history_m->send_sms($member->guardian_mobile,$sms);
                    }
                }
            }
        }
        



        $this->RESPONSE['message']=' Data Saved Successfully.';  
        //check if needs to create voucher record
        // if(isset($form['create_voucher'])&&intval($form['create_voucher'])>0){}
        //send back the resposne
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
        if(empty($rid)|| $this->std_advance_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid advance...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->std_advance_m->get_by_primary($rid);

        if($this->std_advance_m->date != $row->date){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Advance of previous dates can not be terminated.';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->student_m->get_by_primary($row->student_id);
        if($this->std_advance_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Advance can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //subtract from student advance amount
        $this->student_m->update_advance($member->mid,$row->amount,'minus');
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." canceled advance(".$row->title.", ".$row->amount.") of Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name."'s advance (".$row->title.") has been canceled by ".$this->LOGIN_USER->name;
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Advance Canceled','description'=>$msg));

        //create ledger entry for this advance
        $this->accounts_ledger_m->add_entry($this->CAMPUSID,'Advance amount of '.ucwords(strtolower($member->name)).' canceled.',$this->accounts_m->_FEE_ADVANCE,$this->accounts_m->_CASH,$row->amount,true);
        //send back the resposne
        $this->RESPONSE['message']='Advance Cancled Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}


////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////CONCESSION FUNCTIONS///////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterConcession(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('amount','date');
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
        $this->RESPONSE['rows']=$this->std_fee_concession_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_fee_concession_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $student=$this->student_m->get_by_primary($form['rid']);  
        $concessions=$this->concession_type_m->get_values_array('mid','title',array());
        $i=0;
        $total_amount=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['title']=$concessions[$row['type_id']];
            if($row['type']==$this->std_fee_concession_m->TYPE_FIXED){$total_amount+=$row['amount'];}
            if($row['type']==$this->std_fee_concession_m->TYPE_PERCENTAGE){$total_amount+=(($student->fee*$row['amount'])/100);}
            $i++;
        }

        $this->RESPONSE['total_concession']=$total_amount;
        // print_r($this->RESPONSE['rows']);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// create row
public function addConcession(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','type_id','type','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->student_m->get_rows(array('campus_id'=>$this->CAMPUSID,'mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->student_m->get_by_primary($form['rid']);
        $form['student_id']=$member->mid;

        //save data in database         
        $form['campus_id']=$this->CAMPUSID;         
        if($this->std_fee_concession_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Concession cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered concession(".$form['amount'].", ".$form['type'].") for student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." has granted concession by administration.";
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Advance Canceled','description'=>$msg));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
// update row
public function deleteConcession(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for necessary data   
        if(empty($rid)|| $this->std_fee_concession_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->std_fee_concession_m->get_by_primary($rid);

        $member=$this->student_m->get_by_primary($row->student_id);
        if($this->std_fee_concession_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Concession can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." canceled concession  of Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name."'s concession has been canceled by ".$this->LOGIN_USER->name;
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Advance Canceled','description'=>$msg));

        //send back the resposne
        $this->RESPONSE['message']='Concession Cancled Successfully.';  
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
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->std_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}
// filter rows
public function filterFeeHistory(){
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
        $this->RESPONSE['rows']=$this->std_fee_history_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_fee_history_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}

// filter rows
public function filterFeeVouchers(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('remarks','amount','date');
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
        isset($form['sortby']) &&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->std_fee_entry_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_fee_entry_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        // $roles=$this->stf_role_m->get_values_array('mid','title',array());
        // $i=0;
        // foreach($this->RESPONSE['rows'] as $row){
        //     $this->RESPONSE['rows'][$i]['password']='';
        //     $this->RESPONSE['rows'][$i]['role']=$roles[$row['role_id']];
        //     $i++;
        // }
        // print_r($this->RESPONSE['rows']);

        /////////////////total fee of the student//////////////////////////////
        $total_plus_fee=$this->std_fee_entry_m->get_column_result('amount',array('campus_id'=>$this->CAMPUSID,'operation'=>$this->std_fee_entry_m->OPT_PLUS,'student_id'=>$form['rid']));
        $total_minus_fee=$this->std_fee_entry_m->get_column_result('amount',array('campus_id'=>$this->CAMPUSID,'operation'=>$this->std_fee_entry_m->OPT_MINUS,'student_id'=>$form['rid']));
        $this->RESPONSE['total_fee']=$total_plus_fee;
        $this->RESPONSE['balance']=$total_plus_fee-$total_minus_fee;
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}

 
    ////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////ATTEDANCE FUNCTIONS////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

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
        echo json_encode($this->RESPONSE);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////HOMEWORK FUNCTIONS//////////////////////////////////////////////////
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            $student=$this->student_m->get_by_primary($form['rid']);
            $filter['session_id']=$student->session_id;
            $filter['class_id']=$student->class_id;
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->std_subject_homework_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_subject_homework_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            $i=0;
            foreach($this->RESPONSE['rows'] as $row){
                $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name;
                $i++;
            }
            ////////////////////////////////////////////////////////////////////////     
            $student=$this->student_m->get_by_primary($form['rid']);  
            $this->RESPONSE['total_advance']=$student->advance_amount;
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
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ?$params['orderby']=$form['sortby']:$params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->std_subject_test_result_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_subject_test_result_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){        
            $this->RESPONSE['rows'][$i]['subject']=$this->class_subject_m->get_by_primary($row['subject_id'])->name; 

            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////RESULTS FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterResults(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('session','class','status');
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
        $this->RESPONSE['rows']=$this->std_result_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_result_m->get_rows($filter,'',true);
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
        $this->RESPONSE['rows']=$this->std_qual_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_qual_m->get_rows($filter,'',true);
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
        $required=array('rid','session','class','obtained_marks','total_marks','status');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist
        if($this->student_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->student_m->get_by_primary($form['rid']);
        $form['student_id']=$member->mid;

        //save data in database 
        $form['campus_id']=$this->CAMPUSID;              
        if($this->std_qual_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Academic record cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered academic record(".$form['session'].") for Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." academic record(".$form['session'].") has been registered by ".$this->LOGIN_USER->name;
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Academic Record Registration','description'=>$msg));

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
        $required=array('rid','session','class','obtained_marks','total_marks','status');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check if staff id already exist other then this staff
        if($this->std_qual_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid academic record...';
            echo json_encode($this->RESPONSE);exit();
        }
               
        if($this->std_qual_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Academic record cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updateed student academic record(".$form['session'].").";
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
        if(empty($rid)|| $this->std_qual_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid academic record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->std_qual_m->get_by_primary($rid);
        $member=$this->student_m->get_by_primary($row->student_id);
        if($this->std_qual_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Academic record can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed academic record(".$row->session.") of Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." academic record(".$row->session.") has been removed by ".$this->LOGIN_USER->name;
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Academic Record Removed','description'=>$msg));
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
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->std_award_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_award_m->get_rows($filter,'',true);
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
        if($this->student_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }
        $award=$this->award_m->get_by_primary($form['award']);
        $member=$this->student_m->get_by_primary($form['rid']);
        $form['student_id']=$member->mid;
        $form['award_id']=$award->mid;
        $form['title']=$award->title;

        //save data in database  
        $form['campus_id']=$this->CAMPUSID;              
        if($this->std_award_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Award cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." assigned award(".$award->title.") to Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." earned an award(".$award->title.").";
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Award Earned','description'=>$msg));


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
        if($this->std_award_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid award...';
            echo json_encode($this->RESPONSE);exit();
        }
        
        $award=$this->award_m->get_by_primary($form['award']);
        $form['award_id']=$award->mid;
        $form['title']=$award->title;   
        if($this->std_award_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Award cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated student award(".$award->title.").";
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
        if(empty($rid)|| $this->std_award_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid award...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->std_award_m->get_by_primary($rid);
        $member=$this->student_m->get_by_primary($row->student_id);
        if($this->std_award_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Award can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed award(".$row->title.") of Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." award(".$row->title.") has been removed by ".$this->LOGIN_USER->name;
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Award Removal','description'=>$msg));
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
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->std_punishment_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_punishment_m->get_rows($filter,'',true);
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
        if($this->student_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }
        $punishment=$this->punishment_m->get_by_primary($form['notice']);
        $member=$this->student_m->get_by_primary($form['rid']);
        $form['student_id']=$member->mid;
        $form['doc_id']=$punishment->mid;
        $form['title']=$punishment->title;

        //save data in database    
        $form['campus_id']=$this->CAMPUSID;            
        if($this->std_punishment_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notice cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." issued notice(".$punishment->title.") to Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." received a notice(".$punishment->title.").";
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Notice','description'=>$msg));


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
        if($this->std_punishment_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid notice...';
            echo json_encode($this->RESPONSE);exit();
        }
        
        $punishment=$this->punishment_m->get_by_primary($form['notice']);
        $form['doc_id']=$punishment->mid;
        $form['title']=$punishment->title;   
        if($this->std_punishment_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notice cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated student notice(".$punishment->title.").";
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
        if(empty($rid)|| $this->std_punishment_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid notice...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->std_punishment_m->get_by_primary($rid);
        $member=$this->student_m->get_by_primary($row->student_id);
        if($this->std_punishment_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notice can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." get back notice(".$row->title.") of Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." notice(".$row->title.") has been terminated by ".$this->LOGIN_USER->name;
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Notice Termination','description'=>$msg));
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
        isset($form['rid']) && !empty($form['rid']) ? $filter['student_id']= $form['rid'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->std_acheivement_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->std_acheivement_m->get_rows($filter,'',true);
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
        if($this->student_m->get_rows(array('mid'=>$form["rid"]),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }
        $member=$this->student_m->get_by_primary($form['rid']);
        $form['student_id']=$member->mid;

        //save data in database   
        $form['campus_id']=$this->CAMPUSID;            
        if($this->std_acheivement_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Endorsement cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." saved an endorsement(".$form['title'].") for Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." has been endorsed with badge(".$form['title'].").";
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Endorsement','description'=>$msg));


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
        if($this->std_acheivement_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid endorsement...';
            echo json_encode($this->RESPONSE);exit();
        }
         
        if($this->std_acheivement_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Endorsement cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated student endorsement(".$form['title'].").";
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
        if(empty($rid)|| $this->std_acheivement_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid endorsement...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->std_acheivement_m->get_by_primary($rid);
        $member=$this->student_m->get_by_primary($row->student_id);
        if($this->std_acheivement_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Endorsement can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." terminated the endorsement(".$row->title.") of Student(".$member->name.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        $msg=$member->name." endorsement(".$row->title.") has been terminated by ".$this->LOGIN_USER->name;
        $this->std_history_m->add_row(array('campus_id'=>$this->CAMPUSID,'student_id'=>$member->mid,'title'=>'Endorsement Termination','description'=>$msg));
        //send back the resposne
        $this->RESPONSE['message']='Endorsement Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
}
//////////////////////////////////////////////////////////////////
/////////STAFF ATTENDANCE REPORT FUNCTION/////////////////
//////////////////////////////////////////////////////////////////
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

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	