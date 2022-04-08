<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History extends Staff_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'history/';
        //load all models for this controller
        $models = array();
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		
		$this->data['main_content']='history';	
		$this->data['menu']='history';			
		$this->data['sub_menu']='history';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='history';
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
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                $this->data['main_content']='staff_profile';    
                $this->data['print_page_title']='Staff profile'; 

            }
            break;
            case 'list':{
                $this->data['main_content']='staff_list';    
                $this->data['print_page_title']='Staff List'; 

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
            case 'deductions':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                $this->data['main_content']='staff_deductions';    
                $this->data['print_page_title']='Staff Member Loan History'; 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'staff', 'refresh');                       
            }break;
        }
        if($this->session->flashdata('back_url')){$this->data['back_url']=$this->session->flashdata('back_url');}    
        $this->load->view($this->LIB_VIEW_DIR.'printing/master', $this->data);   
    }
    
     //save profile pic
    public function upload_picture(){
        //set_time_limit(90);
        //upload artwork file
        $form=$this->input->safe_post(array());
        $staff=$this->staff_m->get_by_primary($this->LOGIN_USER->mid);
            $file_name=$staff->staff_id.mt_rand(101,999);
            $data=$this->upload_img('file',$file_name);
            if($data['file_uploaded']==FALSE){
                $this->session->set_flashdata('error', $data['file_error'].'Max allowed file size is 1550KB. Minimum dimension must be 250x250 Px.');
                redirect($this->LIB_CONT_ROOT.'history', 'refresh');
            }
            $nfile_name=$data['file_name'];
            $saveform=array('image'=>$nfile_name);
            $this->staff_m->save($saveform,$staff->mid);
            $this->session->set_flashdata('success', 'Profile picture uploaded successfully for '.$staff->name);
            redirect($this->LIB_CONT_ROOT.'history', 'refresh');           
    
    }
    ////////////////////upload file///////////////////////////////
    private function upload_img($file_name='file',$new_name=''){   
        $path='./uploads/images/staff/profile';
        $size='1550';    //1.4MB
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


    // load single row
    public function load(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        // ///////////////////////////////////////////////////////////////////////////////////////////

        $row=$this->staff_m->get_by_primary($this->LOGIN_USER->mid);
        $filter['staff_id']=$row->mid;
        $row->total_awards=$this->stf_award_m->get_rows($filter,'',true);
        $row->total_punishments=$this->stf_punishment_m->get_rows($filter,'',true);
        $row->total_acheivements=$this->stf_acheivement_m->get_rows($filter,'',true);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////ALLOWANCE FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterAllowances(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title','description','amount','date');
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_allownce_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_allownce_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////DEDUCTION FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterDeductions(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title','month','amount','year','date');
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_deduction_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_deduction_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_advance_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_advance_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_history_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_history_m->get_rows($filter,'',true);
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
            $search=array('qualification','year','roll_number','registration_no','program','institute','date');
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_qual_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_qual_m->get_rows($filter,'',true);
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
            $required=array('rid','qualification','year','institute');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            $form['staff_id']=$this->LOGIN_USER->mid;
            $form['campus_id']=$this->CAMPUSID;
            //save data in database                
            if($this->stf_qual_m->add_row($form)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Academic record cannot be saved at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            }  

            //Log the user Activity
            $msg=$this->LOGIN_USER->name." registered selef academic record(".$form['qualification'].").";
            $this->system_history_m->add_row(array('message'=>$msg));
            $msg="Academic record(".$form['qualification'].") has been registered by ".$this->LOGIN_USER->name;
            $this->stf_history_m->add_row(array('staff_id'=>$this->LOGIN_USER->mid,'title'=>'Academic Record Registration','description'=>$msg));


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
            $required=array('rid','qualification','year','institute');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            //check if staff id already exist other then this staff
            if($this->stf_qual_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid academic record...';
                echo json_encode($this->RESPONSE);exit();
            }
                   
            if($this->stf_qual_m->save($form,$form['rid'])===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Academic record cannot be updated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 

            //send back the resposne
            $this->RESPONSE['message']=' Data Saved Successfully.';  
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_award_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_award_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_punishment_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_punishment_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
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
            isset($form['rid']) && !empty($form['rid']) ? $filter['staff_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->stf_acheivement_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_acheivement_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            echo json_encode( $this->RESPONSE);
            
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////ALLOWANCE FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    /////////STAFF ATTENDANCE REPORT FUNCTION/////////////////
    //////////////////////////////////////////////////////////////////
    // filter rows
    public function filterStaffAttendanceEvents(){
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
	