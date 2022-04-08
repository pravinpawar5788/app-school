<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools{
	protected $CI;
	
	//constructor of the class, optional params will loading the class
	public function __construct($config = array())
        {
                //assign the codeIgniter super-object
				$this->CI =& get_instance();
        }

	//////////////////////////////////////////////////
	//validate organization
	public function get_org_id(){
		$_pk=$this->CI->organization_m->_primary_key;
		$this->CI->load->model(array('organization_m'));
		$server=$this->CI->input->server(NULL);
		$min_length=4;
		$organization;
		$domain=strtolower(parse_url(current_url(),PHP_URL_HOST));
		if(strlen($domain)>$min_length){
			$organization=$this->CI->organization_m->get_by(array('domain'=>  $domain),true);
			if(!empty($organization->domain) && $this->CI->organization_m->is_valid_field('domain', $domain)==TRUE){
				//valid organization
				return $organization->$_pk;
			}
		}
		$domain=strtolower(parse_url(base_url(),PHP_URL_HOST));
		if(strlen($domain)>$min_length){
			$organization=$this->CI->organization_m->get_by(array('domain'=>  $domain),true);
			if(!empty($organization->domain) && $this->CI->organization_m->is_valid_field('domain', $domain)==TRUE){
				//valid organization
				return $organization->$_pk;
			}
		}
		$domain=strtolower($server['HTTP_HOST']);
		if(strlen($domain)>$min_length){
			$organization=$this->CI->organization_m->get_by(array('domain'=>  $domain),true);
			if(!empty($organization->domain) && $this->CI->organization_m->is_valid_field('domain', $domain)==TRUE){
				//valid organization
				return $organization->$_pk;
			}
		}
		$domain=strtolower($server['SERVER_NAME']);
		if(strlen($domain)>$min_length){
			$organization=$this->CI->organization_m->get_by(array('domain'=>  $domain),true);
			if(!empty($organization->domain) && $this->CI->organization_m->is_valid_field('domain', $domain)==TRUE){
				//valid organization
				return $organization->$_pk;
			}
		}
		return false;
    }	

    //validate store
	public function get_campus_id(){
		$this->CI->load->model(array('user_m'));
		$_pk=$this->CI->user_m->_primary_key;
		// $this->CI->load->library('session');
		$login_id=$this->CI->session->userdata('login_id');
		$row=$this->CI->user_m->get_by_primary($login_id);
		if(!empty($row)){
			return $row->campus_id;
		}
		return false;
    }
	//get current domain
	public function get_current_domain(){
		$server=$this->CI->input->server(NULL);
		$min_length=6;
		$domain=strtolower(parse_url(current_url(),PHP_URL_HOST));
		if(strlen($domain)>$min_length){
			return $domain;
		}
		$domain=strtolower(parse_url(base_url(),PHP_URL_HOST));
		if(strlen($domain)>$min_length){
			return $domain;
		}
		$domain=strtolower($server['HTTP_HOST']);
		if(strlen($domain)>$min_length){
			return $domain;
		}
		$domain=strtolower($server['SERVER_NAME']);
		if(strlen($domain)>$min_length){
			return $domain;
		}
		return $domain;
    }
	//set base url
	public function set_base_url(){
		// Set the base_url automatically if none was provided
		//if (empty($this->CI->config['base_url']))
		//{	
			$base_url = '';
			// The regular expression is only a basic validation for a valid "Host" header.
			// It's not exhaustive, only checks for valid characters.
			if (isset($_SERVER['HTTP_HOST']) && preg_match('/^((\[[0-9a-f:]+\])|(\d{1,3}(\.\d{1,3}){3})|[a-z0-9\-\.]+)(:\d+)?$/i', $_SERVER['HTTP_HOST']))
			{
				$base_url = (is_https() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
			}
			elseif(empty($base_url)&&isset($_SERVER['SERVER_NAME']) && preg_match('/^((\[[0-9a-f:]+\])|(\d{1,3}(\.\d{1,3}){3})|[a-z0-9\-\.]+)(:\d+)?$/i', $_SERVER['SERVER_NAME']))
			{
				$base_url = (is_https() ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'];				
			}else{
				$base_url = 'http://localhost/';
			}
			$base_url.=substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
			$this->CI->config->set_item('base_url', $base_url);
		//}
	}
	//Force a file to download
    public function downloadFile ($url, $path) {

        $newfname = $path;
        $file = fopen ($url, "rb");
        if ($file) {
            $newf = fopen ($newfname, "wb");

            if ($newf)
                while(!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
                }
        }

        if ($file) {
            fclose($file);
        }

        if ($newf) {
            fclose($newf);
        }
    }
    //decrypt rj256
    public function decryptRJ256($key,$string_to_decrypt){
        $iv = md5($key);
        $string_to_decrypt = base64_decode($string_to_decrypt);
        $rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_decrypt, MCRYPT_MODE_CBC, $iv);
        $rtn = rtrim($rtn, "\0\4");
        return($rtn);
    }
	//encrypt rj256
    public function encryptRJ256($key,$string_to_encrypt){
        $iv = md5($key);
        $rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string_to_encrypt, MCRYPT_MODE_CBC, $iv);
        $rtn = base64_encode($rtn);
        //convert = to whitespace
        $rtn = preg_replace("/=+/", "", $rtn);
        return($rtn);
    }
	//secure the string from xss
	public function validate_for_xss($string){
        //list of checks for xss
        $check[1] = chr(34);$check[2] = chr(39);$check[3] = chr(92);$check[4] = chr(96);
        $check[5] = "drop table";$check[6] = "update";$check[7] = "alter table";
        $check[8] = "drop database";$check[9] = "drop";$check[10] = "select";$check[11] = "delete";
        $check[12] = "insert";$check[13] = "alter";$check[14] = "destroy";$check[15] = "table";
        $check[16] = "database";$check[17] = "union";$check[18] = "TABLE_NAME";$check[19] = "1=1";
        $check[20] = 'or 1';$check[21] = 'exec';$check[22] = 'INFORMATION_SCHEMA';$check[23] = 'like';
        $check[24] = 'COLUMNS';$check[25] = 'into';$check[26] = 'VALUES';$check[27] = 'kill';
        $check[28] = 'union'; $check[29] = '$';
        //while loop handlers
        $y = 1;
        $x = sizeof($check);
        // replace the found value with empty string
        while($y <= $x){
            $target = strpos($string,$check[$y]);
            if($target !== false){
                $string = str_replace($check[$y], "", $string);
            }
            $y++;
        }
        // Return the validated string (safe from SQL Injection)
        return $string;
    }
	
	
}
