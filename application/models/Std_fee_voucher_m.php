<?php


class Std_fee_voucher_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Std_fee_voucher_M.php';
	protected $_table_name = 'std_fee_voucher';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_PAID='PAID';                     
        public $STATUS_PARTIAL_PAID='PARTIAL_PAID';     
        public $STATUS_UNPAID='UNPAID';                 
        public $STATUS_CANCELED='CANCELED';             
        public $OPTION_YES='YES';                       
        public $OPTION_NO='NO';                         
        public $TYPE_MONTHLY='MONTHLY';                 
        public $TYPE_FIXED='FIXED';                     
        public $TYPE_FEE='fee';                 
        public $TYPE_ADMISSION='admission';                         
        public $TYPE_ADVANCE='advance';                     
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
    if(empty($db_row['status'])){$db_row['status']=$this->STATUS_UNPAID;}
    if(empty($db_row['type'])){$db_row['type']=$this->TYPE_FEE;}
    if(empty($db_row['month_number'])){$db_row['month_number']=$this->month_number;}
    if(empty($db_row['month'])){$db_row['month']=$this->month;}
    if(empty($db_row['year'])){$db_row['year']=$this->year;}
    if(empty($db_row['due_date'])){$db_row['due_date']=$this->date;}
    $db_row['jd']=$this->todayjd;
    $db_row['date']=$this->date;
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['student_id']) ){
        return false;        
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	    

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////




//GENERATE NEW VOUCHER ID
public function get_new_voucher_id(){
    $start=strtoupper(get_random_string(3));
    $number=$start.date('ymdhis').mt_rand(101,999);
    if($this->is_valid_field('voucher_id', $number)){
    return $this->get_new_voucher_id();
    }else{return $number;}
}


//GENERATE NEW BLANK VOUCHER
public function create_blank_voucher($student_id,$title,$type='',$status='',$due_date=''){
    $voucher_id=$this->get_new_voucher_id();
    $result=array('voucher_id'=>$voucher_id);
    $student=$this->student_m->get_by_primary($student_id);
    $voucher=array('campus_id'=>$student->campus_id,'voucher_id'=>$voucher_id,'session_id'=>$student->session_id,'student_id'=>$student->mid,'class_id'=>$student->class_id);
    $voucher['roll_no']=$student->roll_no;
    $voucher['student_name']=$student->name;
    $voucher['title']=$title;
    if(empty($type)){
        $voucher['type']=$this->TYPE_OTHER;        
    }else{
        $voucher['type']=$type;
    }
    $voucher['status']=$status;
    $voucher['due_date']=$due_date;
    $vid=$this->add_row($voucher);
    if($vid){
        $result['vid']=$vid;
        return $result;
    }else{
        return false;
    }
}


//GET PENDING RECEIVEABLE AMOUNT
public function get_receiveable_amount($filter=array()){
    $total_amount=0;
    $filter['status <>']=$this->STATUS_PAID;
    $rows=$this->get_rows($filter);
    foreach($rows as $row){
        $amount=$this->std_fee_entry_m->get_voucher_amount($row['voucher_id'],'',$filter['campus_id']);
        $total_amount+=$amount;
    }
    return $total_amount;
}



//////////////////////////////////////////////// END OF CLASS /////////////////////
}