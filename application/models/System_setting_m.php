<?php


class System_setting_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='System_setting_M.php';
	protected $_table_name = 'system_settings';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public $DB_COL_ARRAY=array();
    public $STATUS_VALID='valid';              
    public $STATUS_INVALID='invalid';
    public $LIC_LT='lifetime';
    public $LIC_DT='durable';
    public $LIC_TT='trial';
    public $TYPE_SCHOOL='school';
    public $TYPE_COLLEGE='college';

    public $_ORG_NAME='org_name';              
    public $_ORG_SHORT_NAME='org_short_name';            
    public $_ORG_TYPE='org_type'; //school | college            
    public $_ORG_ACCOUNTING_PERIOD='org_accounting_period'; //calendar | fiscal            
    public $_FEE_MODEL='fee_model'; //monthly | session            
    public $_ORG_ADDRESS='org_address';            
    public $_ORG_CONTACT_NUMBER='org_contact_number';            
    public $_ORG_LOGO='org_logo';            
    public $_CURRENCY_SYMBOL='currency_symbol';            
    public $_STFID_KEY='stfid_key';            
    public $_STDID_KEY='stdid_key';            
    public $_CUSTOM_AUTH_BG='custom_auth_bg';            
    public $_DISABLE_NOTE='disable_note';            
    public $_INSTALL_DATE='install_date';            
    public $_INSTALL_JD='install_jd';            
    public $_INSTALL_YEAR='install_year';            
    public $_INSTALL_VERSION='install_version';            
    public $_EXPIRE_DATE='expire_date';            
    public $_EXPIRE_JD='expire_jd';            
    public $_EXPIRE_NOTE='expire_note';            
    public $_LIC_KEY='licencekey';           
    public $_LIC_STATUS='lic_status';           
    public $_LIC_TYPE='lic_type';           
    public $_ENVATO_CODE='envatocode';           
    public $_SMS_VENDOR='sms_vendor';                    
    public $_SMS_TYPE='sms_type'; //brand|nonbrand            
    public $_SMS_LANG='sms_lang';             
    public $_SMS_MASK='sms_mask';             
    public $_SMS_SENDING='sms_sending';//0=disable | 1=enable             
    public $_SMS_API_USERNAME='sms_api_username';             
    public $_SMS_API_KEY='sms_api_key';             
    public $_MAINTENANCE_MODE='maintenance_mode';//0=disable | 1=enable             
    public $_MAINTENANCE_MESSAGE='maintenance_message';             
    public $_MAX_UPLOAD_SIZE='max_upload_size';   

    ///////////////////////CONTANTS FOR SETTINS VALUE///////////          
    public $_FEE_MODEL_MONTHLY='monthly';             
    public $_FEE_MODEL_SESSION='session';             



//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC FUNCTIONS /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function __construct (){
		parent::__construct();                
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// SETTER FUNCTIONS //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////

//Initialize some Componenets on new row addition (optional)
public function init_tasks($data){
//init other models
return true;   
  
}
//ADD NEW TABLE ROW IN DATABASE 
public function add_row($vals=array()){
    //GET ALL THE FIELDS IN ARRAY   
    $db_row=  $this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['name']) ){
        return false;}
    
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //GET VALUE FROM KEY
    public function get_setting($key){
        if($this->get_rows(array('name'=>$key),'',true)>0){
            return $this->get_by(array('name'=>$key),true)->value;
        }else{
            $this->save_setting($key);
            return '';
        }
    }       
    
    
    // GET ALL SETTINGS IN ARRAY WITH NAME AS INDEX AND VALUE AS ARRAY VALUE
    function get_settings_array(){
        return $this->get_values_array('name', 'value');        
    }
    
    //SET VALUE OF KEY
    public function save_setting($key='',$value=''){
        if($this->get_rows(array('name'=>$key),'',true)>0){
            //key already exist save value
            $this->save(array('value'=>$value),array('name'=>$key));
        }else{
            //key does not exist yet
            $this->add_row(array('name'=>strtolower($key),'value'=>$value));
        }
    }   
    //SAVE SETTINGS ARRAY
    public function save_settings_array($data=array() ){
        if (count($data)>0) {
            foreach ($data as $key => $value) {
                $this->save_setting($key,$value);
            }
        }
    }    


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// VALIDATION FUNCTIONS ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    //////////////////////////////////GETTER FUNCTIONS/////////////////////////////////
    public function get_default_settings(){
        $data=array();
        $data[$this->_ORG_NAME]=''; 
        $data[$this->_ORG_SHORT_NAME]=''; 
        $data[$this->_ORG_TYPE]=''; 
        $data[$this->_ORG_ADDRESS]=''; 
        $data[$this->_ORG_CONTACT_NUMBER]=''; 
        $data[$this->_ORG_ACCOUNTING_PERIOD]=''; 
        $data[$this->_ORG_LOGO]='default.png'; 
        $data[$this->_CURRENCY_SYMBOL]='$'; 
        $data[$this->_STFID_KEY]='STF'; 
        $data[$this->_STDID_KEY]='STD'; 
        $data[$this->_CUSTOM_AUTH_BG]=''; 
        $data[$this->_DISABLE_NOTE]=''; 
        $data[$this->_INSTALL_DATE]=''; 
        $data[$this->_INSTALL_JD]=''; 
        $data[$this->_INSTALL_YEAR]=''; 
        $data[$this->_INSTALL_VERSION]=''; 
        $data[$this->_EXPIRE_DATE]=''; 
        $data[$this->_EXPIRE_JD]=''; 
        $data[$this->_EXPIRE_NOTE]=''; 
        $data[$this->_LIC_KEY]=''; 
        $data[$this->_LIC_STATUS]=''; 
        $data[$this->_LIC_TYPE]=''; 
        $data[$this->_ENVATO_CODE]=''; 
        $data[$this->_SMS_VENDOR]=''; 
        $data[$this->_SMS_TYPE]=''; 
        $data[$this->_SMS_LANG]=''; 
        $data[$this->_SMS_MASK]=''; 
        $data[$this->_SMS_SENDING]=''; 
        $data[$this->_SMS_TYPE]=''; 
        $data[$this->_SMS_API_USERNAME]=''; 
        $data[$this->_SMS_API_KEY]=''; 
        $data[$this->_MAINTENANCE_MODE]='0'; 
        $data[$this->_MAINTENANCE_MESSAGE]=''; 
        $data[$this->_CUSTOM_AUTH_BG]=''; 
        $data[$this->_MAX_UPLOAD_SIZE]=''; 

                             
        return $data;
    }
    //process install settings
    public function process_install_settings(){
        $this->init_settings();
        $settings=array();
        $settings[$this->_INSTALL_DATE] = $this->date;
        $settings[$this->_INSTALL_JD] = $this->todayjd;
        $settings[$this->_INSTALL_YEAR] = $this->year;
        $settings[$this->_FEE_MODEL] = $this->_FEE_MODEL_MONTHLY;
        $settings[$this->_MAX_UPLOAD_SIZE] = 20;
        $this->save_settings_array($settings);
    }

    //reset settings to default
    public function init_settings(){
        $settings=$this->get_default_settings();

        foreach($settings as $key=>$value){
            if($this->get_rows(array('name'=>$key),'',true)<1){
                //create row
                $this->add_row(array('name'=>$key,'value'=>$value));
            }else{
                //update row
                $this->save(array('value'=>$value),array('name'=>$key));

            }

        }
    }


    //validate jds
    public function validate_jds(){
        $config=$this->CI->system_setting_m->get_settings_array();
        if($config[$this->CI->system_setting_m->_EXPIRE_JD]<$this->CI->system_setting_m->todayjd && $config[$this->CI->system_setting_m->_LIC_STATUS]==$this->CI->system_setting_m->STATUS_VALID && $config[$this->CI->system_setting_m->_LIC_TYPE] != $this->CI->system_setting_m->LIC_LT){
            $this->CI->system_setting_m->save_setting($this->CI->system_setting_m->_LIC_STATUS,$this->CI->system_setting_m->STATUS_INVALID);
        }
    }

//////////////////////////////////////////////// END OF CLASS /////////////////////
}