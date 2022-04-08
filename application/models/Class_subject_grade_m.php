<?php
class Class_subject_grade_M extends MY_Model{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
protected $_model_name='Class_subject_grade_M.php';
protected $_table_name = 'campus_result_grades';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public $DB_COL_ARRAY=array();
public $OPTION_YES='yes';               //if option is yes
public $OPTION_NO='no';                 //if option is no
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
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['min_marks'])||empty($data['grade'])){
        return false;
    }  
    ///////////// data is valid to insert or update
    return true;
    
}	



//////////////////////////////////////////////// END OF CLASS /////////////////////
}