<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Staff_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'settings/';
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
        $this->data['main_content']='settings';   
        $this->data['menu']='settings';           
        $this->data['sub_menu']='settings';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='settings';

        /////////////////////////////////////////////////////////////
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);    
    }




     //save profile pic
    public function upload_picture(){
        //upload artwork file
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->CONT_ROOT.'', 'refresh');           
        }
        $file_name=$this->LOGIN_USER->parent_id.date('dmyhi').mt_rand(101,999);
        $data=$this->upload_img('file',$file_name);
        if($data['file_uploaded']==FALSE){
            $this->session->set_flashdata('error', $data['file_error']);
            redirect($this->CONT_ROOT.'', 'refresh');
        }
        $nfile_name=$data['file_name'];
        $saveform=array('image'=>$nfile_name);
        $this->parent_m->save($saveform,$this->LOGIN_USER->mid);
        $this->session->set_flashdata('success', 'Profile picture uploaded successfully');
        redirect($this->CONT_ROOT.'', 'refresh');           
    
    }
    ////////////////////upload file///////////////////////////////
    private function upload_img($file_name='file',$new_name=''){   
        $path='./uploads/images/parent/profile';
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

    // edit row
    public function updatePassword(){ 
            // get input fields into array        
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for demo   
            if($this->IS_DEMO){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
                echo json_encode($this->RESPONSE);exit();
            }
            //check for necessary required data   
            $required=array('npassword','password');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   

            $password=$this->staff_m->hash($form['password']);  
            //check if a valid staff is being edited
            if($this->LOGIN_USER->password != $password){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Invalid current password. Please enter correct current password...';
                echo json_encode($this->RESPONSE);exit();
            }

            $npassword=$this->staff_m->hash($form['npassword']);  
            //save data in database
            if($this->staff_m->save(array('password'=>$npassword),$this->LOGIN_USER->mid)===false){
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
	