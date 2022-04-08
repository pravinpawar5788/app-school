<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends Parent_Controller{

/** 
* ///////////////////////////////////////////////////////////
* ********************** CONTANTS ***************************
* ///////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	public $WEBSITE_HOME;
	
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'feedback/';
        //load all models for this controller
        $models = array('student_m','parent_m','parent_chat_m','parent_chat_detail_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////
* ********************* PUBLIC FUNCTIONS ***************
* ///////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		
		$this->data['main_content']='feedback';	
		$this->data['menu']='feedback';			
		$this->data['sub_menu']='feedback';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='feedback';
        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}

    ///////view function/////////////
    public function view($rid='',$tab=''){
        if(empty($rid) || $this->parent_chat_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){            
            $this->session->set_flashdata('error', 'Please choose a valid record');
            redirect($this->LIB_CONT_ROOT.'feedback', 'refresh'); 
        }
        
        $this->data['main_content']='feedback_view';    
        $this->data['menu']='feedback';            
        $this->data['sub_menu']='feedback';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='feedback_view';
        $record=$this->parent_chat_m->get_by_primary($rid); 
        $this->parent_chat_m->save(array('parent_status'=>1),$rid);  
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
    * /////////////////////////////////////////////////////////
    * **************** AJAX FUNCTIONS *************************
    * /////////////////////////////////////////////////////////
    */

    // filter rows
    public function filter(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID,'parent_id'=>$this->LOGIN_USER->mid);
        $params=array();
        $search=array('title','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['status']) && !empty($form['status']) ? $filter['parent_status']= $form['status'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->parent_chat_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->parent_chat_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function add(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('title','message');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        $message=array('parent_id'=>$this->LOGIN_USER->mid,'parent_status'=>1,'title'=>$form['title'],'campus_id'=>$this->CAMPUSID);
        $chat_id=$this->parent_chat_m->add_row($message);
        //save data in database
        if($chat_id==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        $detail=array('chat_id'=>$chat_id,'user_id'=>$this->LOGIN_USER->mid,'message'=>$form['message'],'campus_id'=>$this->CAMPUSID);
        $this->parent_chat_detail_m->add_row($detail);
        //send back the resposne  
        $this->RESPONSE['message']=' Sent Successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function reply(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','message');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        $chat=$this->parent_chat_m->get_by_primary($form['rid']);
        $detail=array('chat_id'=>$chat->mid,'user_id'=>$this->LOGIN_USER->mid,'message'=>$form['message'],'campus_id'=>$this->CAMPUSID);
        $this->parent_chat_detail_m->add_row($detail);
        $this->parent_chat_m->save(array('org_status'=>0),$chat->mid);
        //send back the resposne  
        $this->RESPONSE['message']='Sent Successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
    // update row
    public function update(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name','mobile','cnic','parent_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        if($this->parent_m->get_rows(array('mid<>'=>$form['rid'],'parent_id'=>$form['parent_id'],'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Parent ID already exist. Please choose another one...';
            echo json_encode($this->RESPONSE);exit();                   
        }
        if($this->student_m->get_rows(array('father_nic'=>$form['cnic'],'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='There is not student with this father national id. Please first register student account...';
            echo json_encode($this->RESPONSE);exit();                   
        }
        if(isset($form['password'])){
            unset($form['password']);
        }   

        //save data in database                
        if($this->parent_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated parent account (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
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
        if(empty($rid)|| $this->parent_chat_m->get_rows(array('mid'=>$rid,),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->parent_chat_m->get_by_primary($rid);
        $filter['chat_id']=$row->mid;        
        $chat_details=$this->parent_chat_detail_m->get_rows($filter,array('orderby'=>'mid ASC'));
        $message="<center><h3><span class='text-info'>".ucwords($row->title)."</span></h3></center><hr>";
        ///////////////////////////////////////////
        // $i=0;
        foreach($chat_details as $row){
            if($row['user_id']==$this->LOGIN_USER->mid){
            $message.='<div class="alert alert-success alert-styled-left">
                    By <strong>'.ucwords($this->LOGIN_USER->name).'</strong> - '.$row['date'].'<br>'.htmlspecialchars_decode($row['message']).'
                    </div>';
            }else{

            $message.='<div class="alert alert-info alert-styled-right text-right">
                    By <strong> '.ucwords($this->ORG->name).'</strong> - '.$row['date'].'<br>'.htmlspecialchars_decode($row['message']).'
                    </div>';

            }
        }
        //send back resposne      
        $this->RESPONSE['chat_details']=$message;
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
        if(empty($rid)|| $this->parent_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid parnet...';
            echo json_encode($this->RESPONSE);exit();
        }

        if($this->parent_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Account can not be terminated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //send back the resposne
        $this->RESPONSE['message']='Account Terminated Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }


/** 
* //////////////////////////////////////////////////////////
* *********************** END OF CLASS *********************
* //////////////////////////////////////////////////////////
*/

}
	