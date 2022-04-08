<?php


class Stf_pay_entry_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Stf_pay_entry_M.php';
	protected $_table_name = 'stf_pay_entries';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $OPTION_YES='YES';               //if option is yes
        public $OPTION_NO='NO';                 //if option is no
        public $OPT_PLUS='plus';                //if entry operation is plus in voucher
        public $OPT_MINUS='minus';              //if entry operation is minus in voucher
        public $TYPE_FEE='fee';              
        public $TYPE_PAY='pay';              
        public $TYPE_FINE='fine';              
        public $TYPE_ALLOWANCE='allowance';              
        public $TYPE_DEDUCTION='deduction';              
        public $TYPE_OTHER='other';              
        public $TYPE_PAID='paid';              
        




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
    if(empty($db_row['type'])){$db_row['type']=$this->TYPE_PAY;}
    if(empty($db_row['operation'])){$db_row['operation']=$this->OPT_PLUS;}
    if(empty($db_row['month_number'])){$db_row['month_number']=$this->month_number;}
    $db_row['date']=$this->date;
    $db_row['jd']=$this->todayjd;
    $db_row['day']=$this->day;
    $db_row['month']=$this->month;
    $db_row['year']=$this->year;

    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['ledger_id'])||empty($data['staff_id'])||empty($data['amount'])||empty($data['voucher_id']) ){
        return false;        
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	   

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

//GENERATE NEW VOUCHER
public function create_voucher($staff_id,$amount,$title,$remarks='',$status=''){
    $voucher_id=$this->stf_pay_voucher_m->get_new_voucher_id();
    $result=array('voucher_id'=>$voucher_id);
    $staff=$this->staff_m->get_by_primary($staff_id);
    $voucher=array('voucher_id'=>$voucher_id,'staff_id'=>$staff->mid);
    $voucher['campus_id']=$staff->campus_id;
    $voucher['staff_name']=$staff->name;
    $voucher['stf_id']=$staff->staff_id;
    $voucher['amount']=$amount;
    $voucher['title']=$title;
    $voucher['type']=$this->stf_pay_voucher_m->TYPE_OTHER;
    $voucher['status']=$status;
    if($status==$this->stf_pay_voucher_m->STATUS_PAID){
        $voucher['date_paid']=$this->date;    
    }
    $vid=$this->stf_pay_voucher_m->add_row($voucher);
    $result['vid']=$vid;
    if($vid){
        //create entry
        $entry=array('voucher_id'=>$voucher_id,'staff_id'=>$staff->mid,'amount'=>$amount,'remarks'=>$remarks);
        $result['entry_rid']=$this->add_row($entry);
        return $result;
    }else{
        return false;
    }
}

//GET VOUCHER AMOUNT
public function get_voucher_amount($voucher_id,$type='',$campus_id=''){
    $filter=array('voucher_id'=>$voucher_id);
    if(!empty($campus_id)){$filter['campus_id']=$campus_id;}
    switch (strtolower($type)) {
        case $this->OPT_MINUS:{
            $filter['operation']=$this->OPT_MINUS;
            return $this->get_column_result('amount',$filter);
        }
        case $this->OPT_PLUS:{
            $filter['operation']=$this->OPT_PLUS;
            return $this->get_column_result('amount',$filter);
        }
        break;        
        default:{
            $filter['operation']=$this->OPT_MINUS;
            $minus_amount=$this->get_column_result('amount',$filter);
            $filter['operation']=$this->OPT_PLUS;
            $plus_amount=$this->get_column_result('amount',$filter);            
            return $plus_amount-$minus_amount;
        }
        break;
    }
}



///////////////////////////////////////////// END OF CLASS /////////////////////
}