<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Upload extends CI_Upload{
	protected $CI;
	
	//constructor of the class, optional params will loading the class
	public function __construct($config = array())
        {
                parent::__construct($config);
				//assign the codeIgniter super-object
				$this->CI =& get_instance();
        }

	//////////////////////////////////////////////////
	//upload a file from post on server
    public function upload_file($path,$upload_file_name,$new_name='',$max_size=9999,$allowed_types='jpg|png',$min_width=0,$min_height=0,$max_width=0,$max_height=0,$overwrite=true){
		$config=array();
		$config['upload_path']=$path;   //$path='./assets/uploads/artwork';
        $config['allowed_types']=$allowed_types;    //$allowed_types='jpg|png|';
        $config['min_height']=$min_height;
        $config['min_width']=$min_width;
        $config['overwrite']=$overwrite;
        $config['max_size']=$max_size;  //in KiloBytes //$size='5120';
        $config['max_width']=$max_width;
        $config['max_height']=$max_height;
        if(!empty($new_name)){$config['file_name']=$new_name;}		
        $this->initialize($config);
        if(!$this->do_upload($upload_file_name) ){
            $upload_data =$this->data();
            $error=$this->display_errors();
            $upload_data['is_uploaded']=FALSE;
            $upload_data['upload_error']=$error;
            return $upload_data;                
        }else{
            $upload_data =$this->data();
            $upload_data['is_uploaded']=TRUE;
            return $upload_data;
        }
    }
	
	
}
