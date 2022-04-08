<?php


class Module_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Module_M.php';
	protected $_table_name = 'modules';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_ACTIVE='active';
        public $STATUS_UNACTIVE='inactive';
        public $MODULE_PARENT_PORTAL='PARENT_PORTAL';   
        public $MODULE_STAFF_PORTAL='STAFF_PORTAL'; 
        public $MODULE_EXAM='EXAM';


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
	//$this->init_class(true); 
    $db_row=  $this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    if(empty($db_row['status'])){$db_row['status']=$this->STATUS_ACTIVE;}
    
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

//check if organization subscribed the module
public function is_subscribed($module=''){
    if($this->get_rows(array('name'=>$module),'',true)>0){return true;}
    return false;
}

//check if module is enabled for organization members
public function is_enabled($module=''){
    if($this->get_rows(array('name'=>$module,'status'=>$this->STATUS_ACTIVE),'',true)>0){return true;}
    return false;
}


public function get_modules_array(){
    $data=array();
    $data[$this->MODULE_PARENT_PORTAL]='Parents Portal';            
    $data[$this->MODULE_STAFF_PORTAL]='Staff Portal';            
    // $data[$this->MODULE_LMS]='Learning Management System';            
    // $data[$this->MODULE_HOSTEL]='Hostel Management';            
    // $data[$this->MODULE_EXAM]='Examination Management';            

    return $data;
}

public function get_module_name($module){
    switch($module){
        case $this->MODULE_PARENT_PORTAL:{
            return 'Parents Portal';
        }
        break;
        case $this->MODULE_STAFF_PORTAL:{
            return 'Staff Portal';
        }
        break;
        default: return '';
        break;
    }
}



public function get_module_description($module){
    $descirption='';
    switch($module){
        case $this->MODULE_PARENT_PORTAL:{
            $descirption="Parent module helps parents to view their children activities online.";
        }
        break;
        case $this->MODULE_STAFF_PORTAL:{
            $descirption="Staff module helps staff manage their task from their own account.";
        }
        break;
        default: 
        break;
    }
    return $descirption;
}



//////////////////////////////////////////////// END OF CLASS /////////////////////
}