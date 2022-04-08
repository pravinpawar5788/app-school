<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Input extends CI_Input{
	protected $CI;
	
	//constructor of the class, optional params will loading the class
	public function __construct($config = array())
        {
                parent::__construct($config);
				//assign the codeIgniter super-object
				//$this->CI =& get_instance();
        }

	//////////////////////////////////////////////////
	//get safe post array
	public function safe_post($fields=array() ){
	$data = array();
	if(empty($fields)){
		$params=$this->post(NULL,TRUE);
		if(count($params)>0){
			foreach ($params as $key =>$value) {
            $data[$key] = $this->get_request_input($key,'post');
			}
		}
	}else{
		foreach ($fields as $field) {
            $data[$field] = $this->get_request_input($field,'post');
		}
	}
	return $data;
    }
	
	//get safe get array
	public function safe_get($fields=array() ){
	$data = array();
	if(empty($fields)){
		$params=$this->get(NULL,TRUE);
		if(count($params)>0){
			foreach ($params as $key =>$value) {
            $data[$key] = $this->get_request_input($key,'get');
			}
		}
	}else{
		foreach ($fields as $field) {
            $data[$field] = $this->get_request_input($field,'get');
		}
	}
	return $data;
    }
	
	//get safe get_post array
	public function safe_get_post($fields=array() ){
	$data = array();
	if(empty($fields)){
		$params=$this->get_post(NULL,TRUE);
		if(count($params)>0){
			foreach ($params as $key =>$value) {
            $data[$key] = $this->get_request_input($key,'get_post');
			}
		}
	}else{
		foreach ($fields as $field) {
            $data[$field] = $this->get_request_input($field,'get_post');
		}
	}
	return $data;
    }
	
	//get safe post_get array
	public function safe_post_get($fields=array() ){
	$data = array();
	if(empty($fields)){
		$params=$this->post_get(NULL,TRUE);
		if(count($params)>0){
			foreach ($params as $key =>$value) {
            $data[$key] = $this->get_request_input($key,'post_get');
			}
		}
	}else{
		foreach ($fields as $field) {
            $data[$field] = $this->get_request_input($field,'post_get');
		}
	}
	return $data;
    }
	//get secure the string
	public function get_safe_string($string){
		//urldecode|htmlspecialchars|stripslashes|trim|strip_tags|strip
		return htmlspecialchars(stripslashes(strip_tags(trim(urldecode($string)))));
	}
	//get secure the input from input class
	private function get_request_input($key,$method='post'){
		//urldecode|htmlspecialchars|stripslashes|trim|strip_tags|strip
		$value=$this->$method($key);
		if(!is_array($value)){return htmlspecialchars(stripslashes(trim($value)));}
		return $value;
	}
	
}
