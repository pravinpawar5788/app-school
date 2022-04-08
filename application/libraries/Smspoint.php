<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Smspoint{
		
	
	protected $CI;
	protected $_is_enabled;
    protected $_sms_sending;
    protected $_sms_vendor;
    protected $_sms_type;
    protected $_sms_lang;
    protected $_api_username;
    protected $_api_key;
    protected $_api_mask;
    protected $_vendor_akspk='akspk';
    protected $_vendor_regular='regular';

	
	
	//constructor of the class, optional params will loading the class
	function __construct(){
		// Assign the CodeIgniter super-object
        $this->_is_enabled=false;
		$this->CI =& get_instance();
		$this->initialize();
	}
	
	public function initialize($params=array()){
        $this->CI->load->model('system_setting_m');
        $this->_sms_sending=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_SMS_SENDING);
        $this->_sms_vendor=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_SMS_VENDOR);
        $this->_sms_type=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_SMS_TYPE);
        $this->_sms_lang=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_SMS_LANG);
        $this->_api_username=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_SMS_API_USERNAME);
        $this->_api_key=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_SMS_API_KEY);
        $this->_api_mask=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_SMS_MASK);

        if(!empty($this->_sms_vendor) && !empty($this->_sms_sending) && !empty($this->_api_username) && !empty($this->_api_key) && intval($this->_sms_sending)>0 ){
            $this->_is_enabled=true;
        }

		// if(array_key_exists('et_title', $config)){			
		// 	empty($config['et_title'])? $this->title=$this->getDomain(base_url()) : $this->title=$config['et_title'];
		// }		
	}


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function is_valid_api($vendor='',$param1, $param2){
        switch ($vendor) {
        //check if akspk api is valid
        case $this->_vendor_akspk:{
            $mobile=urlencode($param1);
            $apikey=urlencode($param2);
            $url='https://akspk.com/api/isvalidapi?mobile='.$mobile.'&apikey='.$apikey;
            $ch=curl_init();
            $timeout=15;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $result=curl_exec($ch);
            //print $result;
            curl_close($ch);
            if(strtolower($result)=='yes'){return true;}else{return false;}
        }break;
        //check if regularsms api is valid
        case $this->_vendor_regular:{
            $mobile=urlencode($param1);
            $apikey=urlencode($param2);
            $url='https://regularsms.pk/api/isvalidapi?mobile='.$mobile.'&apikey='.$apikey;
            $ch=curl_init();
            $timeout=15;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $result=curl_exec($ch);
            //print $result;
            curl_close($ch);
            if(strtolower($result)=='yes'){return true;}else{return false;}
        }break;
        
        default:
            return false;
        break;
        }
    }

    public function is_valid_mask($vendor='',$param1, $param2,$param3){
        switch (strtolower($vendor)) {
        //check if akspk api is valid
        case $this->_vendor_akspk:{
            $mobile=urlencode($param1);
            $apikey=urlencode($param2);
            $mask=urlencode($param3);
            $url='https://akspk.com/api/isvalidmask?mobile='.$mobile.'&apikey='.$apikey.'&mask='.$mask;
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $result=curl_exec($ch);
            //print $result;
            curl_close($ch);
            if(strtolower($result)=='yes'){return true;}else{return false;}
        }break;
        //check if regularsms api is valid
        case $this->_vendor_regular:{
            $mobile=urlencode($param1);
            $apikey=urlencode($param2);
            $mask=urlencode($param3);
            $url='https://regularsms.pk/api/isvalidmask?mobile='.$mobile.'&apikey='.$apikey.'&mask='.$mask;
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $result=curl_exec($ch);
            //print $result;
            curl_close($ch);
            if(strtolower($result)=='yes'){return true;}else{return false;}
        }break;
        
        default:
            return false;
        break;
        }            
    }

    public function get_remaining_sms(){
        if(!$this->_is_enabled){return 0;}
        switch ($this->_sms_vendor) {
        //check if akspk api is valid
        case $this->_vendor_akspk:{
            $mobile=urlencode($this->_api_username);
            $apikey=urlencode($this->_api_key);
            $url='https://akspk.com/api/getaccountsms?mobile='.$mobile.'&apikey='.$apikey;
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $result=curl_exec($ch);
            //print $result;
            curl_close($ch);
            return intval($result);
        }break;
        //check if regularsms api is valid
        case $this->_vendor_regular:{
            $mobile=urlencode($this->_api_username);
            $apikey=urlencode($this->_api_key);
            $url='https://regularsms.pk/api/getaccountsms?mobile='.$mobile.'&apikey='.$apikey;
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $result=curl_exec($ch);
            //print $result;
            curl_close($ch);
            return intval($result);
        }break;
        
        default:
            return 0;
        break;
        }
    }
    //return true if sms sending is enabled
    public function is_sms_enable(){
        return $this->_is_enabled;
    }

    //////////////////////////////////////////////// END OF CLASS /////////////////////
}