<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fabsam{
		
	
	protected $CI;
	protected $_app_code;
    private $_host_url;
    private $_host_apiurl;
    private $_host_apikey;

	
	
	//constructor of the class, optional params will loading the class
	function __construct(){
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
        $this->_host_url="https://license.mozzine.com";
        $this->_host_apiurl=$this->_host_url."/api/v2/";
		$this->initialize();
	}
	
	public function initialize($params=array()){
        $this->_app_code=$this->CI->config->item('app_code');
        $this->_host_apikey=$this->CI->config->item('app_apikey');
	}


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //get product metadata
    public function get_metadata($app_code=''){
        empty($app_code) ? $code=$this->_app_code : $code=$app_code;
        $url=$this->_host_apiurl."getdata/products?apikey=".urlencode($this->_host_apikey)."&code=".urlencode($code)."&host=".urlencode($this->CI->APP_ROOT);
        $response=$this->explore_url($url);
        return $response;
    }

    //get license information
    public function get_licenseinfo($licensekey=''){
        if(empty($licensekey)){
            $this->CI->load->model('system_setting_m');
            $licensekey=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_LIC_KEY);
        }
        $url=$this->_host_apiurl."getdata/licenses?apikey=".urlencode($this->_host_apikey)."&licensekey=".urlencode($licensekey)."&host=".urlencode($this->CI->APP_ROOT);
        $response=$this->explore_url($url);
        return $response;
    }

    //issue envato license to the envatocode
    public function issue_envatolicense($code,$email,$name,$mobile='',$address=''){
        $url=$this->_host_apiurl."issue/envatolicense?apikey=".urlencode($this->_host_apikey)."&envatocode=".urlencode($code);
        $url.="&app_code=".urlencode($this->_app_code);
        $url.="&host=".urlencode($this->CI->APP_ROOT);
        $url.="&customer_email=".urlencode($email);
        $url.="&customer_name=".urlencode($name);
        $url.="&customer_mobile=".urlencode($mobile);
        $url.="&customer_address=".urlencode($address);
        $url.="&comments=".urlencode("License issued to customer against envatocode:".$code." \n Date:".date('D-M-Y h:i:s A'));
        $response=$this->explore_url($url);
        return $response;
    }

    //validate envato code
    public function is_valid_envatocode($code,$checklicense=true){
        $url=$this->_host_apiurl."validate/envatocode?apikey=".urlencode($this->_host_apikey)."&envatocode=".urlencode($code)."&app_code=".urlencode($this->_app_code)."&host=".urlencode($this->CI->APP_ROOT);
        if($checklicense){$url.="&checklic=".urlencode('yes');}
        $response=$this->explore_url($url);
        if($response->code==600){
            return true;
        }
        return $response->reason;
        // return false;
    }

    //validate license
    public function is_valid_license($licensekey='',$force_host_binding=true){
        return true;
        if(empty($licensekey)){
            $this->CI->load->model('system_setting_m');
            $licensekey=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_LIC_KEY);
        }
        $url=$this->_host_apiurl."validate/license?apikey=".urlencode($this->_host_apikey)."&licensekey=".urlencode($licensekey);
        if($force_host_binding){$url.="&host=".urlencode($this->CI->APP_ROOT);}
        $response=$this->explore_url($url);
        if($response->code==600){
            return true;
        }
        return false;
    }


    //check for new updates
    public function is_updates_availeable($current_version=''){
        if($this->is_connected()){
            if(empty($current_version)){
                $this->CI->load->model('system_setting_m');
                $current_version=$this->CI->system_setting_m->get_setting($this->CI->system_setting_m->_INSTALL_VERSION);
            }
            $metadata=$this->get_metadata();
            if($metadata->data[0]->version_code > intval($current_version)){
                return true;
            }
        }
        return false;
    }
    
    //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////
    //make call to server
    private function explore_url($url){

        $ch=curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,            
            // CURLOPT_HTTPHEADER => array(
            //     "Authorization: Bearer {$this->_personal_token}",
            //     "User-Agent: {$userAgent}"
            // )
        ));
        //response
        $response = curl_exec($ch);
        if (curl_errno($ch) > 0){
            $arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);  
            $response=file_get_contents($url,false,stream_context_create($arrContextOptions));
            if(empty($response)){
                if($stream=fopen($url, 'r')){
                    $response=stream_get_contents($stream);
                    fclose($stream);
                }else{
                    return null; 
                    // print curl_error($ch);                    
                }
            }
        }
        //result in object format
        $data = json_decode($response);
        curl_close($ch);
        return $data;
    }

    //////////////////////////////////////////////////////////////////////////////
    //download updates
    function download_updates($filename,$destination){
        if($this->is_connected()){
            $metadata=$this->get_metadata();
            $this->CI->load->library('download');
            $file_url=$this->_host_url.'/assets/downloads/'.$metadata->data[0]->code.'/'.$metadata->data[0]->code.'-'.$metadata->data[0]->version_code.'.zip';
            if($this->CI->download->file_curl($file_url,$destination.$filename)){
                return true;
            }
            return false;
        }
        return false;
    }

    //////////////////////////////////////////////////////////////////////////////
    //check if connected to server
    function is_connected(){
        // return true;
        $connected = @fsockopen($this->_host_url, 80);   //website, port  (try 80 or 443)
        if ($connected){
            fclose($connected);
            return true;
        }elseif(fopen("http://www.google.com:80/","r")){
           return true;
        }
        return false;

    }

    //validate jds
    public function validate_jds(){
        return true;
        // $config=$this->CI->system_setting_m->get_settings_array();
        // if($config[$this->CI->system_setting_m->_EXPIRE_JD]<$this->CI->system_setting_m->todayjd && $config[$this->CI->system_setting_m->_LIC_STATUS]==$this->CI->system_setting_m->STATUS_VALID && $config[$this->CI->system_setting_m->_LIC_TYPE] != $this->CI->system_setting_m->LIC_LT){
        //     $this->CI->system_setting_m->save_setting($this->CI->system_setting_m->_LIC_STATUS,$this->CI->system_setting_m->STATUS_INVALID);
        // }
    }
    //////////////////////////////////////////////// END OF CLASS /////////////////////
}