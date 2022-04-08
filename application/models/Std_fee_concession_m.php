<?php


class Std_fee_concession_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Std_fee_concession_M.php';
	protected $_table_name = 'std_fee_concession';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $TYPE_FIXED='fixed';              
        public $TYPE_PERCENTAGE='percentage';    

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
    if(empty($db_row['type'])){$db_row['type']=$this->TYPE_PERCENTAGE;}
    $db_row['date']=$this->date;
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
	
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['student_id'])||empty($data['amount'])||empty($data['type_id'])){
        return false;        
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	    



//////////////////////////////////////////////// END OF CLASS /////////////////////
}