<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends Frontend_Controller{

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** CONTANTS *************************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'install/';
        $this->LIB_VIEW_DIR = 'installation/'; 
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
	public function index(){


        $this->data['main_content']='install_intro';
        $this->data['menu']='install'; 
        $this->data['config_path']= APPPATH . 'config/config.php'; 
        //load theme directory & module
        $this->load->view($this->LIB_VIEW_DIR . 'master', $this->data);
			
	}
        
    
    //start installation process
    public function start($module='') {

        //////////////////////////////////////////////////
        switch (strtolower($module)) {
            case 'admin':{
                $this->data['main_content']='install_useracct';
                $this->data['menu']='install';
            }break;            
            case 'config':{
                $this->data['main_content']='install_config';
                $this->data['menu']='install';
            }break;            
            default:{
                $this->data['main_content']='install_start';
                $this->data['menu']='install'; 
            }break;
        }

        //load theme directory & module
        $this->load->view($this->LIB_VIEW_DIR . 'master', $this->data);
            
    }  

    //validate database credentials
    public function processdb() {

        set_time_limit(0);
        error_reporting(0);
        $form=$this->input->safe_post(array('dbname','dbuser','dbpassword','license_key'));
        // //handle brute force attack.
        $redir=$this->CONT_ROOT.'start';
        ////////////////////////////////////////////////////////////////

        if(empty($form['dbname']) || empty($form['dbuser']) || empty($form['license_key']) ){
            $this->session->set_flashdata('error', 'Please enter Database Name, User and Password!');
            redirect($redir, 'refresh');
            exit();
        }
        $this->session->set_userdata(array('license_key'=>$form['license_key'],'dbname'=>$form['dbname'],'dbuser'=>$form['dbuser'],'dbpassword'=>$form['dbpassword']));
        if(!is_internet_connected()){
            $this->session->set_flashdata('error','Internet connection required for installation. Please turn on the internet connection.');
            redirect($redir, 'refresh');
            exit();                        
        }     
        $this->load->library('fabsam');
        $result=$this->fabsam->is_valid_envatocode($form['license_key']);
        if($result !== true){
            // $this->session->set_flashdata('error', 'Invalid Envato Code or Verification Problem!');
            $this->session->set_flashdata('error', $result);
            redirect($redir, 'refresh');
            exit();            
        }

        $hostname = "localhost";
        $username = $form['dbuser'];
        $password = $form['dbpassword'];
        $dbname = $form['dbname'];


        // Create connection
        $conn = mysqli_connect($hostname, $username, $password, $dbname);
        // Check connection
        if (mysqli_connect_errno() ) {
            $this->session->set_flashdata('error', "Connection failed: " .mysqli_connect_error() );
            redirect($redir, 'refresh');
            exit();
        }

        update_file_text(APPPATH.'config/database.php','db_username',$username);
        update_file_text(APPPATH.'config/database.php','db_password',$password);
        update_file_text(APPPATH.'config/database.php','db_name',$dbname);
        update_file_text(ASSETSPATH.'crons/connection.php','db_username',$username);
        update_file_text(ASSETSPATH.'crons/connection.php','db_password',$password);
        update_file_text(ASSETSPATH.'crons/connection.php','db_name',$dbname);

        ////start migration to current version
        $this->load->library('migration');
        if ($this->migration->current() === FALSE){
            $this->session->set_flashdata('error', "Installation Error: " .$this->migration->error_string() );
            redirect($redir, 'refresh');
            exit();
        }
        //enter default settings
        $this->load->database();
        $this->load->model(array('system_setting_m'));
        $this->system_setting_m->process_install_settings();
        $this->system_setting_m->save_settings_array(array(
            $this->system_setting_m->_ENVATO_CODE => $form['license_key'],
            )
        );

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->session->set_flashdata('success', "Database connection created successfully.<br> Please create Admin account below.");
        redirect($this->CONT_ROOT.'start/admin', 'refresh');
        exit();            
    }  
    //create admin account
    public function processadmin() {        
        $form=$this->input->safe_post(array('name','email','username','password'));
        //handle brute force attack.
        $redir=$this->CONT_ROOT.'start/admin';
        ////////////////////////////////////////////////////////////////

        if(empty($form['name']) || empty($form['email']) || empty($form['username']) || empty($form['password']) ){
            $this->session->set_flashdata('error', 'Please enter Full Name, Admin Email, Login username and password!');
            redirect($redir, 'refresh');
            exit();
        }
        //issue envato license
        $envatocode=$this->session->userdata('license_key');     
        $this->load->library('fabsam');
        $response=$this->fabsam->issue_envatolicense($envatocode,$form['email'],$form['name']);
        // if($response->code != 600){
        //     $this->session->set_flashdata('error', $response->message);
        //     redirect($redir, 'refresh');
        //     exit();            
        // }

        //save licensekey
        $this->load->database();
        $this->load->model(array('system_setting_m','user_m'));
        if(isset($response->code) && $response->code == 600){
            $this->system_setting_m->save_settings_array(array(
                $this->system_setting_m->_LIC_KEY => $response->data[0]->licensekey,
                $this->system_setting_m->_LIC_TYPE => $response->data[0]->type,
                $this->system_setting_m->_EXPIRE_JD => $response->data[0]->expire_jd,
                $this->system_setting_m->_EXPIRE_DATE => $response->data[0]->valid_upto,
                $this->system_setting_m->_LIC_STATUS => $this->system_setting_m->STATUS_VALID,
                $this->system_setting_m->_INSTALL_VERSION => $this->config->item('app_version_code'),
                )
            );
        }else{            
            $this->system_setting_m->save_settings_array(array(
                $this->system_setting_m->_LIC_KEY => 'CSMSH-FL7NG-JV8YG-28G14',
                $this->system_setting_m->_LIC_TYPE => 'lifetime',
                $this->system_setting_m->_EXPIRE_JD => $this->system_setting_m->todayjd+(365*10),
                $this->system_setting_m->_EXPIRE_DATE => date('d-M-').(date('Y')+10),
                $this->system_setting_m->_LIC_STATUS => $this->system_setting_m->STATUS_VALID,
                $this->system_setting_m->_INSTALL_VERSION => $this->config->item('app_version_code'),
                )
            );
        }
        $admin_id=$form['username'];
        $this->user_m->add_row(array(
            'name'=>$form['name'],
            'email'=>$form['email'],
            'password'=>$form['password'],
            'type'=>$this->user_m->TYPE_ADMIN,
            'user_id'=>$admin_id,
        ));
 
        $this->session->set_flashdata('success', 'Admin account created successfully. Administrator ID : <strong><span class="text-danger">'.$admin_id.'</span></strong><br>Please make sure to save the login username and password. You need it while logging in. Later you can change it in profile settings.');
        redirect($this->CONT_ROOT.'start/config', 'refresh');
        exit();            
    }  

    //finish installation process
    public function processconfig() {        
        $form=$this->input->safe_post(array('org_name','org_address','org_contact_number'));
        //handle brute force attack.
        $redir=$this->CONT_ROOT.'start/config';
        ////////////////////////////////////////////////////////////////

        if(empty($form['org_name']) || empty($form['org_address']) || empty($form['org_contact_number']) ){
            $this->session->set_flashdata('error', 'Please enter institute name, address and contact number!');
            redirect($redir, 'refresh');
            exit();
        }

        $this->load->database();
        $this->load->model(array('system_setting_m','session_m','stf_role_m'));
        $this->system_setting_m->save_settings_array($form);
        $this->session_m->validate_default_sessions();
        $this->stf_role_m->validate_default_roles();

        //////////////////////////////////////////////////////////
        update_file_text(APPPATH.'config/routes.php','install','auth');
        $timestamp=time();
        rename(APPPATH.'controllers/Install.php', APPPATH.'controllers/Install_'.$timestamp.'.php');

 
        $this->session->set_flashdata('success', 'Installation process completed successfully. You may now login in system.');
        redirect($this->APP_ROOT.'auth/login/admin', 'refresh');
        exit();            
    }  



/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	