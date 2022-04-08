<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Office extends Manager_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'office/';
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
		
		$this->data['main_content']='office';	
		$this->data['menu']='office';			
		$this->data['sub_menu']='office';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='office';
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
        $search=array('name','user_id','mobile');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['status']) && !empty($form['status']) ? $filter['status']= $form['status'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='name ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $filter['type']=$this->user_m->TYPE_SUBADMIN;
        $this->RESPONSE['rows']=$this->user_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->user_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function add(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        $this->RESPONSE['message']='Account Created Successfully.';
        //check for necessary required data   
        $required=array('name','password');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        $form['user_id']=$this->user_m->get_new_admin_id();
        // $form['password']=$this->user_m->hash($form['password']);
        //save data in database
        $form['campus_id']=$this->CAMPUSID;
        if($this->user_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be created at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new office staff account for (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
        // //////////////////////////////////////////////////////////////////////////////
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
        $required=array('rid','name','user_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        if($this->user_m->get_rows(array('mid<>'=>$form['rid'],'user_id'=>$form['user_id']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Member ID already assigned to another member. Please choose another one...';
            echo json_encode($this->RESPONSE);exit();                   
        }
        if(isset($form['password'])){
            unset($form['password']);
        }   

        //save data in database                
        if($this->user_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated office staff account (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function load(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->user_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->user_m->get_by_primary($rid);
        $this->RESPONSE['output']=$row;
        //send back resposne      
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
        if(empty($rid)|| $this->user_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        if($this->user_m->delete($rid)==false){
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
        if(empty($form['rid'])||$this->user_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        //save data in database
        if($this->user_m->save(array('status'=>$form['status']),$form['rid'])===false){
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
        if(empty($form['rid'])||$this->user_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $form['password']=$this->user_m->hash($form['password']);  
        //save data in database
        if($this->user_m->save(array('password'=>$form['password']),$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Password can not be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back repsonse
        $this->RESPONSE['message']='Password Updated Successfully...';
        echo json_encode($this->RESPONSE);exit();
}

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	