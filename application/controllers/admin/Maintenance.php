<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends Admin_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'maintenance/';
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
		$this->data['main_content']='maintenance';	
		$this->data['menu']='maintenance';			
		$this->data['sub_menu']='maintenance';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='maintenance';

        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    //save profile pic
    public function save($module=''){

        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');                
        }
        switch (strtolower($module)) {
            case 'settings':{                
                $form=$this->input->safe_post();
                $this->system_setting_m->save_settings_array($form);
                $this->session->set_flashdata('success', 'Settings Updated Successfully.');
                redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh'); 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid operation');
                redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');
            }
            break;
        }         
    
    }
    //save profile pic
    public function upload_picture(){
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');                
        }
        //upload artwork file
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



    //create database backup    
    public function savebackup(){
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');                
        }
        set_time_limit(3600);
        ini_set('memory_limit','728M');
        $filename='dbbackup_'.date('dmy').'.gz';
        // Load the download helper and send the file to your desktop
        $this->load->helper('file');
        $this->load->helper('download');
        //create and download backup
        $this->load->dbutil();
        $backup = $this->dbutil->backup();
        force_download($filename, $backup);
        
        
        echo "Backup downloading successfull";
        
    }

    //create system backup    
    public function savesystembackup($savedb=0){
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');                
        }
        set_time_limit(3600);
        ini_set('memory_limit','728M');
        $filename='dbbackup_'.date('dmy').'.gz';
        $system_filename='systembackup_'.date('dmy').'.zip';
        // Load the download helper and send the file to your desktop
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->library('zip');

        $this->zip->read_dir('./');
        //also save databse data.
        if($savedb>0){
            //create and download backup
            $this->load->dbutil();
            $backup = $this->dbutil->backup();   
            $this->zip->add_data($filename,$backup);         
        }

        $this->zip->download($system_filename);
        
        
        echo "Backup downloading successfull";
        
    }
    //create system backup    
    public function resetSettings(){
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');                
        }
        $this->system_setting_m->reset_settings();
        $this->session->set_flashdata('success', 'Settings successfully reset to default settings.');
        redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');  
        
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////

    // create row
    public function doupdate($confirm=''){
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');                
        }

        if(empty($confirm)){
            print 'Please pass confirm paramter to one in last segment of uri';

        }else{

            ////start migration to current version
            $this->load->library('migration');
            if ($this->migration->current() === FALSE){
                print 'There is an error while updating the system...<hr>'; 
                print $this->migration->error_string().'';
            }else{
                print 'System updated successfully...';                
            }
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////

    // create row
    public function checkUpdates(){
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';

        $this->RESPONSE['updates_available']=false;
        $this->load->library('fabsam');
        if($this->fabsam->is_updates_availeable()==true){
            $this->RESPONSE['updates_available']=true;
            $this->RESPONSE['message']='New Updates Are Available. Click below to install...';
            echo json_encode($this->RESPONSE);exit();
        }
        ////////////////////////////
        $this->RESPONSE['message']='System is already upto date...';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function installUpdates(){
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'maintenance', 'refresh');                
        }
        set_time_limit(300);
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ////////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['updates_available']=true;
        $this->load->library('fabsam');

        $filename='file_'.date('dmyhis').'.zip';
        $download_path=ASSETSPATH.'updates'.DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR;
        $install_path=ASSETSPATH.'updates'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR;
        $dest_home=FCPATH.DIRECTORY_SEPARATOR;
        $download_updates=$this->fabsam->download_updates($filename,$download_path);

        if($download_updates){
            //updates downloaded. now install the updates.
            $app_metadata=$this->fabsam->get_metadata();
            $zip= new ZipArchive;
            $res= $zip->open($download_path.$filename);
            if($res === true){
                $zip->extractTo($install_path);
                $zip->close();
                //////////////////////////////////////////////////////////////////////////////////////
                $dir='angular';
                if(file_exists($install_path.$dir)){
                    xcopy($install_path.$dir,$dest_home.$dir);                    
                }
                $dir='application'.DIRECTORY_SEPARATOR.'contollers';
                if(file_exists($install_path.$dir)){
                    xcopy($install_path.$dir,$dest_home.$dir);                    
                }
                $dir='application'.DIRECTORY_SEPARATOR.'core';
                if(file_exists($install_path.$dir)){
                    xcopy($install_path.$dir,$dest_home.$dir);                    
                }
                $dir='application'.DIRECTORY_SEPARATOR.'helpers';
                if(file_exists($install_path.$dir)){
                    xcopy($install_path.$dir,$dest_home.$dir);                    
                }
                $dir='application'.DIRECTORY_SEPARATOR.'migrations';
                if(file_exists($install_path.$dir)){
                    xcopy($install_path.$dir,$dest_home.$dir);                    
                }
                $dir='application'.DIRECTORY_SEPARATOR.'models';
                if(file_exists($install_path.$dir)){
                    xcopy($install_path.$dir,$dest_home.$dir);                    
                }
                $dir='application'.DIRECTORY_SEPARATOR.'views';
                if(file_exists($install_path.$dir)){
                    xcopy($install_path.$dir,$dest_home.$dir);                    
                }
                //copy app and migration file

                $dir='application'.DIRECTORY_SEPARATOR.'config';
                $conf_file=DIRECTORY_SEPARATOR.'app.php';
                copy($install_path.$dir.$conf_file,$dest_home.$dir.$conf_file);
                //************************************************************************************
                $config_home=$dest_home.'application'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR;
                // $migrate_to='20190522163655'; //v1.0
                $migrate_to='20191009163655'; //v1.1
                $version=$app_metadata->data[0]->version;
                $version_code=$app_metadata->data[0]->version_code;
                $find='$config[\'app_version\'] = ';
                $replace='$config[\'app_version\'] = "'.$version.'";  //';
                update_file_text($config_home.'app.php',$find,$replace);


                $find='$config[\'app_version_code\'] = ';
                $replace='$config[\'app_version_code\'] = '.$version_code.';  //';
                update_file_text($config_home.'app.php',$find,$replace);


                $find='$config[\'migration_version\']';
                $replace='$config[\'migration_version\'] = '.$migrate_to.'; //';
                update_file_text($config_home.'migration.php',$find,$replace);


                $this->load->library('migration');
                $this->migration->current();

                $this->system_setting_m->save_setting($this->system_setting_m->_INSTALL_VERSION,$version_code);
                
                ///////////////////////////////////////////////////////////////////////////////////////
                xdelete($dest_home.'application'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'Install.php');
                unlink($download_path.$filename);
                xdelete($install_path);

            }
        }else{
            $this->RESPONSE['error']=true;
            $this->RESPONSE['message']='Network Error! Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }
        ////////////////////////////
        $this->RESPONSE['updates_available']=false;
        $this->RESPONSE['message']='System updated successfully...';
        echo json_encode($this->RESPONSE);exit();
    }

/** 
* /////////////////////////////////////////////////////////////////////////////////////
* ************************** END OF CLASS *********************************************
* /////////////////////////////////////////////////////////////////////////////////////
*/

}
	