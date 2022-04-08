<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Email extends CI_Email{
	protected $CI;
	
	//constructor of the class, optional params will loading the class
	public function __construct($config = array())
        {
                parent::__construct($config);
				//assign the codeIgniter super-object
				$this->CI =& get_instance();
        }

	//////////////////////////////////////////////////
	//send email
    public function send_email($subject,$message,$to,$from,$name='',$params=array(),$config=array()){
		//$config=array('mailtype'=>'html|text','wordwrap'=>true);
		/////////////////////////////////////
		array_key_exists('return_failed', $params)? $return_failed=$params['return_failed'] : $return_failed='';
		array_key_exists('reply_to', $params)? $reply_to=$params['reply_to'] : $reply_to='';
		array_key_exists('reply_to_name', $params)? $reply_to_name=$params['reply_to_name'] : $reply_to_name='';
		array_key_exists('cc', $params)? $cc=$params['cc'] : $cc='';
		array_key_exists('bcc', $params)? $bcc=$params['bcc'] : $bcc='';
		array_key_exists('alt_message', $params)? $alt_message=$params['alt_message'] : $alt_message='';
		array_key_exists('attachments', $params)? $attachments=$params['attachments'] : $attachments='';
		array_key_exists('debug', $params)? $debug=$params['debug'] : $debug=false;		
		//////////////////////////////		
		//$this->clear();	//reset the data
		$this->initialize($config);
		$this->to($to);
		empty($return_failed)? $this->from($from,$name) : $this->from($from,$name,$return_failed);		
		!empty($reply_to)? $this->reply_to($reply_to, $reply_to_name) : '';
		!empty($cc)? $this->cc($cc) : '';
		!empty($bcc)? $this->bcc($bcc) : '';
		!empty($subject)? $this->subject($subject) : $this->subject('') ;
		!empty($message)? $this->message($message) : $this->message('') ;
		!empty($alt_message)? $this->set_alt_message($alt_message) : $this->set_alt_message('') ;
		if(is_array($attachments)){
			foreach($attachments as $attachment){
			array_key_exists('filename', $attachment)? $filename=$attachment['filename'] : $filename='';
			array_key_exists('newname', $attachment)? $newname=$attachment['newname'] : $newname=NULL;
			array_key_exists('disposition', $attachment)? $disposition=$attachment['disposition'] : $disposition='';
			array_key_exists('mime', $attachment)? $mime=$attachment['mime'] : $mime='';
			$this->attach($filename,$disposition,$newname,$mime);
			}
		}
		$sent=$this->send();
		($debug==true)? $this->print_debugger() : '';
		if($sent){ return true;}else{return false;}
		
		/////////sample code
		//$email_params=array('return_failed'=>'asghar@mail.com','reply_to'=>'noreply@mail.com');
		//$sent=$this->email->send_email(subject,$message,to[comma sep string,array],from, from_name, email_params);
    }
	
	
}
