<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Admin_Controller{

/** 
* ////////////////////////////////////////////////////////////////////////////////
* *************************** CONTANTS *******************************************
* ////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'profile/';
        //load all models for this controller
        $models = array();
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////
* ***************************** PUBLIC FUNCTIONS *********************************
* ////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		$this->data['main_content']='profile';	
		$this->data['menu']='profile';			
		$this->data['sub_menu']='profile';
        $this->data['tab']=$tab;

        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    //save profile pic
    public function save($module=''){
        switch (strtolower($module)) {
            case 'profile':{                
                $form=$this->input->safe_post(array("name","mobile","user_id"));
                if($this->IS_DEMO){
                    $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
                    redirect($this->LIB_CONT_ROOT.'profile', 'refresh');                    
                }
                if(empty($form['name']) || empty($form['user_id'])){
                    $this->session->set_flashdata('error', 'Please provide administrator id and name');
                    redirect($this->LIB_CONT_ROOT.'profile', 'refresh');                    
                }
                if($this->user_m->get_rows(array('user_id'=>$form['user_id'],'mid <>'=>$this->LOGIN_USER->mid),'',true)>0){
                    $this->session->set_flashdata('error', 'This Admin ID already assigned to other member');
                    redirect($this->LIB_CONT_ROOT.'profile', 'refresh');                    
                }
                $this->user_m->save($form,$this->LOGIN_USER->mid);
                $this->session->set_flashdata('success', 'Profile Updated Successfully.');
                redirect($this->LIB_CONT_ROOT.'profile', 'refresh'); 

            }
            break;
            case 'password':{                
                $form=$this->input->safe_post(array("password","npassword"));
                if($this->IS_DEMO){
                    $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
                    redirect($this->LIB_CONT_ROOT.'profile/index/password', 'refresh');                    
                }
                if(empty($form['password']) || empty($form['npassword'])){
                    $this->session->set_flashdata('error', 'Please provide current and new password');
                    redirect($this->LIB_CONT_ROOT.'profile/index/password', 'refresh');                    
                }
                if($this->user_m->hash($form['password'])!=$this->LOGIN_USER->password){
                    $this->session->set_flashdata('error', 'Wrong Current password');
                    redirect($this->LIB_CONT_ROOT.'profile/index/password', 'refresh');                    
                }
                $this->user_m->save(array('password'=>$this->user_m->hash($form['npassword'])),$this->LOGIN_USER->mid);
                $this->session->set_flashdata('success', 'Password Updated Successfully.');
                redirect($this->LIB_CONT_ROOT.'profile/index/password', 'refresh'); 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid operation');
                redirect($this->LIB_CONT_ROOT.'profile', 'refresh');
            }
            break;
        }         
    
    }
    //save profile pic
    public function upload_picture(){
        //upload artwork file
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'profile', 'refresh');                    
        }
        $file_name='pic_'.mt_rand(1001,9999);
        $path='./uploads/images/user';
        $data=$this->upload_img('file',$file_name,$path);
        if($data['file_uploaded']==FALSE){
            $this->session->set_flashdata('error', $data['file_error']);
            redirect($this->LIB_CONT_ROOT.'profile', 'refresh');
        }
        $nfile_name=$data['file_name'];
        $this->user_m->save(array('image'=>$nfile_name),$this->LOGIN_USER->mid);
        $this->session->set_flashdata('success', 'Image uploaded successfully.');
        redirect($this->LIB_CONT_ROOT.'profile', 'refresh');           
    
    }
    ////////////////////upload file///////////////////////////////
    private function upload_img($file_name='file',$new_name='',$path){  
        $size=isset($this->SETTINGS['max_upload_size']) ? $this->SETTINGS['max_upload_size'] : '800';    //0.8MB
        $allowed_types='jpg|jpeg|png|bmp';
        $upload_file_name=$file_name;    
        $min_width=$this->config->item('app_img_min_width');
        $min_height=$this->config->item('app_img_min_height');
        $upload_data=$this->upload_file($path,$size,$allowed_types,$upload_file_name,$new_name,$min_width,$min_height);
        return $upload_data;
    }  
/** 
* /////////////////////////////////////////////////////////////////////////////////////
* ************************** END OF CLASS *********************************************
* /////////////////////////////////////////////////////////////////////////////////////
*/

}
	