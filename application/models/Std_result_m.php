<?php

class Std_result_M extends MY_Model{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Std_result_M.php';
	protected $_table_name = 'std_results';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_PASS='pass';   
        public $STATUS_FAIL='fail';   

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
    if(empty($data['campus_id'])||empty($data['student_id'])||empty($data['session_id']) ){
        return false;        
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	   

///////////////////////////////////////////
//////OTHER FUNCTIONS////////////////////////
public function get_grade($total_marks,$obt_marks){
    $grade='';
    if($total_marks<=0){return 'F';}
    $percentage=($obt_marks/$total_marks)*100;
    if($percentage<33){$grade='F';}
    if($percentage>=33){$grade= 'E';}
    if($percentage>=40){$grade= 'D';}
    if($percentage>=50){$grade= 'C';}
    if($percentage>=60){$grade= 'B';}
    if($percentage>=70){$grade= 'A';}
    if($percentage>=80){$grade= 'A+';}    
    return $grade;
}      
public function get_remarks($grade){
    switch (strtolower($grade)) {
        case 'f': return 'Fail';
        break;
        case 'e': return 'Satisfactory';
        break;
        case 'd': return 'Fair';
        break;
        case 'c': return 'Good';
        break;
        case 'b': return 'Very Good';
        break;
        case 'a': return 'Excellent';
        break;
        case 'a+': return 'Exceptional';
        break;
    } 
}      
//////////////////////////////////////////////// END OF CLASS /////////////////////
}