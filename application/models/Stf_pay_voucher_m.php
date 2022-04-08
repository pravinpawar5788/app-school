<?php


class Stf_pay_voucher_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        protected $_model_name='Stf_pay_voucher_M.php';
	protected $_table_name = 'stf_pay_voucher';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_PAID='PAID';             //if PAID                   
        public $STATUS_PARTIAL_PAID='PARTIAL_PAID'; 
        public $STATUS_UNPAID='UNPAID';         //if UNPAID
        public $STATUS_REFUND='REFUND';         //if REFUNDED
        public $STATUS_CANCELED='CANCELED';    //if CANCELLED
        public $OPTION_YES='YES';               //if option is yes
        public $OPTION_NO='NO';                 //if option is no                    
        public $TYPE_PAY='pay';                     
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
    if(empty($db_row['type'])){$db_row['type']=$this->TYPE_PAY;}
    if(empty($db_row['month_number'])){$db_row['month_number']=$this->month_number;}
    if(empty($db_row['month'])){$db_row['month']=$this->month;}
    if(empty($db_row['year'])){$db_row['year']=$this->year;}
    $db_row['date']=$this->date;
    $db_row['jd']=$this->todayjd;
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}   
	
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['staff_id'])||empty($data['amount'])||empty($data['voucher_id']) ){
        return false;        
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	 


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//GENERATE NEW VOUCHER ID
public function get_new_voucher_id(){
    $start=strtoupper(get_random_string(3));
    $number=$start.date('ymdis').mt_rand(101,999);
    if($this->is_valid_field('voucher_id', $number)){
    return $this->get_new_voucher_id();
    }else{return $number;}
}



//GET PENDING PAYABLE AMOUNT
public function get_payable_amount($filter=array()){
    $total_amount=0;
    $filter['status <>']=$this->STATUS_PAID;
    $rows=$this->get_rows($filter);
    foreach($rows as $row){
        $total_amount+=$this->stf_pay_entry_m->get_voucher_amount($row['voucher_id'],'',$filter['campus_id']);
    }
    return $total_amount;
}




//////////////////////////////////////////////// END OF CLASS /////////////////////
}