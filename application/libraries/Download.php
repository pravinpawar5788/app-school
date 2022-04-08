<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download{
		
	
	protected $CI;

	
	//constructor of the class, optional params will loading the class
	function __construct(){
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
		$this->initialize();
	}
	
	public function initialize($params=array()){

		// if(array_key_exists('et_title', $config)){			
		// 	empty($config['et_title'])? $this->title=$this->getDomain(base_url()) : $this->title=$config['et_title'];
		// }		
	}


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //download file via curl.
    public function file_curl($fileUrl,$saveTo){
        //Open file handler.
        $fp = fopen($saveTo, 'w+');
        //If $fp is FALSE, something went wrong.
        if($fp === false){
            return false;
        }

        //Create a cURL handle.
        $ch = curl_init($fileUrl);
        //Pass our file handle to cURL.
        curl_setopt($ch, CURLOPT_FILE, $fp);
        //Timeout if the file doesn't download after 60 seconds.
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        //Execute the request.
        curl_exec($ch);
        //If there was an error, throw an Exception
        if(curl_errno($ch)){
            return false;
        }
        return true;
    }

    //download file via remote file.
    public function file_remote($fileUrl,$saveTo){
        set_time_limit(60);
        $in=    fopen($file_url, "rb");
        $out=   fopen($save_to, "wb");
 
        while ($chunk = fread($in,8192))
        {
            fwrite($out, $chunk, 8192);
        }
 
        fclose($in);
        fclose($out);
        return true;
    }

    //download file via remote file.
    public function file_getcontent($fileUrl,$saveTo){
        set_time_limit(60);
        $content = file_get_contents($file_url);
        file_put_contents($save_to, $content);
        return true;
    }

    //////////////////////////////////////////////// END OF CLASS /////////////////////
}