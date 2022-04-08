<?php
class Period_M extends MY_Model{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Period_M.php';
	protected $_table_name = 'class_timetable_periods';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();        
        public $OPTION_YES='yes';               //if option is yes
        public $OPTION_NO='no';                 //if option is no
        public $DAY_MONDAY='monday';            //if monday
        public $DAY_TUESDAY='tuesday';          //if tuesday
        public $DAY_WEDNESDAY='wednesday';      //if wednesday
        public $DAY_THURSDAY='thursday';        //if thursday
        public $DAY_FRIDAY='friday';            //if friday
        public $DAY_SATURDAY='saturday';        //if saturday
        public $TYPE_BREAK='break';     
        public $TYPE_PERIOD='period';     
        public $TYPE_PRAYER='prayer';     

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
	
    if(empty($data['campus_id'])||empty($data['name'])||empty($data['from_time'])||empty($data['to_time'])||empty($data['type'])){
        return false;        
    }
    ///////////// data is valid to insert or update
    return true;
    
}	


//////////////////////////////////////////////// END OF CLASS /////////////////////
}