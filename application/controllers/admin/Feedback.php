<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends Admin_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'feedback/';
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
		$this->data['main_content']='feedback';	
		$this->data['menu']='feedback';			
		$this->data['sub_menu']='feedback';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='feedback';

        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


/** 
* ////////////////////////////////////////////////////////////////////////////////
* ***************************** AJAX FUNCTIONS *********************************
* ////////////////////////////////////////////////////////////////////////////////
*/  

    // create row
    public function sendMessage(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('subject','message');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   

        //////////////////////////send email//////////////////////////
        $app_url = rtrim($this->APP_ROOT, '/');
        $domain=str_replace('http://','', str_replace('https://','', str_replace('http://www','', str_replace('https:www//','', $app_url))));
        $msg = "<strong> Feedback From Application Customer</strong><br>";
        $msg.= "<strong>App Name</strong>:".$this->config->item('app_name')."<br>";
        $msg.= "<strong>App Code</strong>:".$this->config->item('app_code')."<br>";
        $msg.= "<strong>App Version</strong>:".$this->config->item('app_version')."<br>";
        $msg.= "<strong>Institue Name</strong>:".$this->SETTINGS[$this->system_setting_m->_ORG_NAME]."<br>";
        $msg.= "<strong>Institue Contact</strong>:".$this->SETTINGS[$this->system_setting_m->_ORG_CONTACT_NUMBER]."<br>";
        $msg.= "<strong>Institue Address</strong>:".$this->SETTINGS[$this->system_setting_m->_ORG_ADDRESS]."<br>";
        $msg.= "<strong>License key</strong>:".$this->SETTINGS[$this->system_setting_m->_LIC_KEY]."<br>";
        $msg.= "<strong>Installed On</strong>:".$this->SETTINGS[$this->system_setting_m->_INSTALL_DATE]."<br>";
        $msg.= "<strong>Login User</strong>:".$this->LOGIN_USER->name."<br>";
        $msg.= "<strong>Subject</strong>:".$form['subject']."<br>";
        $msg.= "<strong>message</strong>:<br>".$form['message']."<br><br>";
        $msg.= "Date :" . date('d-M-Y h:i:s A') . ", <br> Thanks.";
        $this->load->library('emailtemp');
        $this->load->library('email');
        $config = array('mailtype' => 'html');
        $params = array();
        $from = 'support@'.$domain;
        $block_title='Feedback '.ucwords($this->config->item('app_name'));
        $from_name = 'System Generated ';
        $title = 'Feedback';
        $blocks = array();
        $blocks[] = array('type' => 'block', 'block_title' => $block_title,
            'btn_url' => $app_url, 'block_text' => $msg, 'btn_text' => 'Password Reset');

        $data = array('title' => $title, 'from_footer_text' => $from_name);
        $data['blocks'] = $blocks;
        $page = $this->emailtemp->render_html($data);

        $this->email->send_email($title, $page, $this->config->item('app_author_email'), $from, $from_name, $params, $config);
        //send back the resposne  
        $this->RESPONSE['message']='Feedback Received! Thanks for your precious time.';
        echo json_encode($this->RESPONSE);exit();
    }
/** 
* /////////////////////////////////////////////////////////////////////////////////////
* ************************** END OF CLASS *********************************************
* /////////////////////////////////////////////////////////////////////////////////////
*/

}
	