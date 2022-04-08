<?php


class Std_subject_homework_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Std_subject_homework_M.php';
	protected $_table_name = 'std_subject_homework';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();            


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
public function add_row($vals){
    //GET ALL THE FIELDS IN ARRAY  
    $db_row=  $this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    $db_row['date']=$this->date;
    $db_row['jd']=$this->todayjd;
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['session_id'])||empty($data['class_id'])||empty($data['subject_id'])||empty($data['homework'])){
        return false;        
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	    


//////////////////////////////////////////////// END OF CLASS /////////////////////
}