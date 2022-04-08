<?php


class Campus_setting_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Campus_setting_M.php';
	protected $_table_name = 'campus_settings';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public $DB_COL_ARRAY=array();
    public $_LATE_FEE_FINE='late_fee_fine';            
    public $_LATE_FEE_FINE_TYPE='late_fee_fine_type';            
    public $_LAST_ADMISSION_NUMBER='last_admission_number';            
    public $_LAST_COMPUTER_NUMBER='last_computer_number';            
    public $_LAST_FAMILY_NUMBER='last_family_number';            
    public $_MONTH_OPASS_PERCENT='month_opass_percent';            
    public $_FINAL_OPASS_PERCENT='final_opass_percent';            
    public $_BANK_NAME='bank_name';            
    public $_BANK_TITLE='bank_title';            
    public $_BANK_ACCOUNT='bank_account';            
    public $_NARRATION='narration';            
    public $_FONT_SACLE='font_scale';            
    public $_PRINT_CLASS_FEE='print_class_fee';            
    public $_PRM_PORTAL_STAFF_EDIT='prm_portal_staff_edit';
    public $_STD_FEE_TYPE='std_fee_type';
    public $_CAMPUS_LOGO='campus_logo';

    //////////////////////////////////////////////////
    public $_LATE_FEE_FINE_FIXED='fixed';            
    public $_LATE_FEE_FINE_PERDAY='daily';                                 
    public $TYPE_MONTHLY='monthly';                 
    public $TYPE_FIXED='fixed';             


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
    if(empty($data['campus_id']) || empty($data['name']) ){
        return false;}
    
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //GET VALUE FROM KEY
    public function get_setting($key='',$campus_id){
        if($this->get_rows(array('name'=>$key),'',true)>0){
            return $this->get_by(array('campus_id'=>$campus_id,'name'=>$key),true)->value;
        }
        return '';
    }       
    
    
    // GET ALL SETTINGS IN ARRAY WITH NAME AS INDEX AND VALUE AS ARRAY VALUE
    function get_settings_array($filter=array()){
        return $this->get_values_array('name', 'value',$filter);        
    }
    
    //SET VALUE OF KEY
    public function save_setting($key='',$value='',$campus_id){
        if($this->get_rows(array('name'=>$key,'campus_id'=>$campus_id),'',true)>0){
            //key already exist save value
            $this->save(array('value'=>$value),array('name'=>$key,'campus_id'=>$campus_id));
        }else{
            //key does not exist yet
            $this->add_row(array('name'=>strtolower($key),'value'=>$value,'campus_id'=>$campus_id));
        }
    }   
    //SAVE SETTINGS ARRAY
    public function save_settings_array($data=array() ,$campus_id){
        if (count($data)>0) {
            foreach ($data as $key => $value) {
                $this->save_setting($key,$value,$campus_id);
            }
        }
    }    


///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// VALIDATION FUNCTIONS ////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
  
    //////////////////////////////////GETTER FUNCTIONS/////////////////////////////////
    public function get_default_settings(){
        $data=array();
        $data[$this->_LATE_FEE_FINE]='0'; 
        $data[$this->_LATE_FEE_FINE_TYPE]=''; 
        $data[$this->_LAST_ADMISSION_NUMBER]='0'; 
        $data[$this->_LAST_COMPUTER_NUMBER]='0'; 
        $data[$this->_LAST_FAMILY_NUMBER]='0'; 
        $data[$this->_MONTH_OPASS_PERCENT]=''; 
        $data[$this->_FINAL_OPASS_PERCENT]=''; 
        $data[$this->_BANK_NAME]=''; 
        $data[$this->_BANK_TITLE]=''; 
        $data[$this->_BANK_ACCOUNT]=''; 
        $data[$this->_NARRATION]=''; 
        $data[$this->_FONT_SACLE]=''; 
        $data[$this->_PRINT_CLASS_FEE]='0'; 
        $data[$this->_PRM_PORTAL_STAFF_EDIT]='0';                              
        $data[$this->_STD_FEE_TYPE]='';                              
        $data[$this->_CAMPUS_LOGO]='';                              
        return $data;
    }
    //reset settings to default
    public function reset_settings($campus_id){
        $settings=$this->get_default_settings();

        foreach($settings as $key=>$value){
            if($this->get_rows(array('name'=>$key,'campus_id'=>$campus_id),'',true)<1){
                //create row
                $this->add_row(array('name'=>$key,'value'=>$value,'campus_id'=>$campus_id));
            }else{
                //update row
                $this->save(array('value'=>$value),array('name'=>$key,'campus_id'=>$campus_id));

            }

        }
    }



//////////////////////////////////////////////// END OF CLASS /////////////////////
}