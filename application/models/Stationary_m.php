<?php


class Stationary_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
protected $_model_name='Stationary_M.php';
protected $_table_name = 'stnry_items';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public $DB_COL_ARRAY=array();

public $TYPE_BOOK='book';      
public $TYPE_NOTEBOOK='notebook';      
public $TYPE_BALLPOINT='pallpoint';      
public $TYPE_PEN='pen';      
public $TYPE_INK='ink';      
public $TYPE_PENCIL='pencil';      
public $TYPE_SHARPENER='sharpner';      
public $TYPE_ERASER='eraser';      
public $TYPE_HIGHLIGHTER='highlighter';      
public $TYPE_RULER='ruler';      
public $TYPE_RUBBERBAND='rubberband';      
public $TYPE_SCHOOLBAG='schoolbag';      
public $TYPE_PAPER='paper';      
public $TYPE_STAPLER='stapler';      
public $TYPE_STAPLES='staples';      
public $TYPE_CLIPBOARD='clipboard';      
public $TYPE_CALCULATOR='calculator';      
public $TYPE_UNIFORM='uniform';      
public $TYPE_OTHER='other';      


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
    if(empty($data['campus_id'])||empty($data['item'])){
        return false;}
    
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	   

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function get_item_types(){
    $types=array();

    $types[$this->TYPE_BOOK]='Book';
    $types[$this->TYPE_NOTEBOOK]='Note Book';
    $types[$this->TYPE_BALLPOINT]='Ballpoint Pen';
    $types[$this->TYPE_PEN]='Pen';
    $types[$this->TYPE_INK]='Pen Ink';      
    $types[$this->TYPE_PENCIL]='Pencil';      
    $types[$this->TYPE_SHARPENER]='Sharpener';      
    $types[$this->TYPE_ERASER]='Eraser';      
    $types[$this->TYPE_HIGHLIGHTER]='Highlighter';      
    $types[$this->TYPE_RULER]='Ruler';      
    $types[$this->TYPE_RUBBERBAND]='Rubber Bank';      
    $types[$this->TYPE_SCHOOLBAG]='School Bag';      
    $types[$this->TYPE_PAPER]='Paper';      
    $types[$this->TYPE_STAPLER]='Stapler';      
    $types[$this->TYPE_STAPLES]='Stapler Staples';      
    $types[$this->TYPE_CLIPBOARD]='Clip Board';      
    $types[$this->TYPE_CALCULATOR]='Calculator';      
    $types[$this->TYPE_UNIFORM]='Uniform';      
    $types[$this->TYPE_OTHER]='Other';

    return $types;
}





//////////////////////////////////////////////// END OF CLASS /////////////////////
}