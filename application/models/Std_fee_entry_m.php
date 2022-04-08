<?php


class Std_fee_entry_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Std_fee_entry_M.php';
	protected $_table_name = 'std_fee_entries';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $OPTION_YES='YES';               //if option is yes
        public $OPTION_NO='NO';                 //if option is no
        public $OPT_PLUS='plus';                //if entry operation is plus in voucher
        public $OPT_MINUS='minus';              //if entry operation is minus in voucher
        public $TYPE_CF='c/f';                  //carry forward        
        public $TYPE_BF='b/f';                  //bring forward        
        public $TYPE_FEE='fee';              
        public $TYPE_FINE='fine';              
        public $TYPE_LATE_FEE_FINE='late_fee_fine';              
        public $TYPE_ABSENT_FINE='absent_fine';              
        public $TYPE_CONCESSION='concession';              
        public $TYPE_TRANSPORT='transport';                    
        public $TYPE_ADMISSION='admission';                     
        public $TYPE_READMISSION='readmission';                     
        public $TYPE_ADVANCE='advance';                
        public $TYPE_FUND='fund';              
        public $TYPE_ANNUALFUND='annualfund';                
        public $TYPE_PAPERFUND='paperfund';                
        public $TYPE_PROSPECTUS='prospectus';                
        public $TYPE_STATIONERY='stationery';                
        public $TYPE_LIBRARY='library';                
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
    if(empty($db_row['type'])){$db_row['type']=$this->TYPE_FEE;}
    if(empty($db_row['operation'])){$db_row['operation']=$this->OPT_PLUS;}
    if(empty($db_row['month_number'])){$db_row['month_number']=$this->month_number;}

    if(empty($db_row['date'])){
        if(empty($db_row['day'])){$db_row['day']=$this->day;}
        if(empty($db_row['jd'])){$db_row['jd']=$this->todayjd;}
        if(empty($db_row['date'])){$db_row['date']=$this->date;}
        if(empty($db_row['month'])){$db_row['month']=$this->month;}
        if(empty($db_row['year'])){$db_row['year']=$this->year;}
    }else{
        $db_row['jd']=get_jd_from_date($db_row['date'],'-',true);
        $db_row['day']=get_day_from_date($db_row['date'],'-');
        $db_row['month']=get_month_from_date($db_row['date'],'-',true);
        $db_row['year']=get_year_from_date($db_row['date'],'-'); 

    }
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['ledger_id'])||empty($data['student_id'])||empty($data['amount'])){
        return false;        
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	  


//GENERATE NEW VOUCHER
public function create_voucher($student_id,$amount,$title,$remarks='',$status='',$ledger_id='',$due_date='',$type=''){
    $voucher_id=$this->std_fee_voucher_m->get_new_voucher_id();
    $result=array('voucher_id'=>$voucher_id);
    $student=$this->student_m->get_by_primary($student_id);
    $voucher=array('voucher_id'=>$voucher_id,'session_id'=>$student->session_id,'student_id'=>$student->mid,'class_id'=>$student->class_id);
    $voucher['campus_id']=$student->campus_id;
    $voucher['roll_no']=$student->roll_no;
    $voucher['student_name']=$student->name;
    $voucher['amount']=$amount;
    $voucher['title']=$title;
    if(empty($type)){
        $voucher['type']=$this->TYPE_OTHER;        
    }else{
        $voucher['type']=$type;
    }
    $voucher['status']=$status;
    $voucher['due_date']=$due_date;

    if($status==$this->std_fee_voucher_m->STATUS_PAID){
        $voucher['amount_paid']=$amount;
        $voucher['date_paid']=$this->date;    
    }
    $vid=$this->std_fee_voucher_m->add_row($voucher);
    $result['vid']=$vid;
    $result['voucher_id']=$voucher_id;
    if($vid){
        //create entry
        $entry=array('campus_id'=>$student->campus_id,'voucher_id'=>$voucher_id,'student_id'=>$student->mid,'amount'=>$amount,'remarks'=>$remarks,'ledger_id'=>$ledger_id,'operation'=>$this->OPT_PLUS,'type'=>$voucher['type']);
        $result['entry_rid']=$this->add_row($entry);
        return $result;
    }else{
        return false;
    }
}

//GET VOUCHER AMOUNT
public function get_voucher_amount($voucher_id,$opt='',$campus_id='',$type=''){
    $filter=array('voucher_id'=>$voucher_id);
    if(!empty($campus_id)){$filter['campus_id']=$campus_id;    }
    if(!empty($type)){$filter['type']=$type;}
    switch (strtolower($opt)) {
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


//GET STUDENT BALANCE
public function get_student_balance($std_id,$campus_id,$opt='',$type='',$filter=array()){
    $filter['student_id']=$std_id;
    $filter['campus_id']=$campus_id;
    if(!empty($type)){$filter['type']=$type;}
    switch (strtolower($opt)) {
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
public function get_fee_types($type=''){
    $types=array(
        $this->TYPE_FEE=>'Tution Fee',
        $this->TYPE_ANNUALFUND=>'Annual Funds',
        $this->TYPE_PAPERFUND=>'Paper Funds',
        $this->TYPE_FUND=>'Misc. Funds',
        $this->TYPE_ABSENT_FINE=>'Absent Fine',
        $this->TYPE_LATE_FEE_FINE=>'Late Fee Fine',
        $this->TYPE_FINE=>'Misc. Fine',
        $this->TYPE_TRANSPORT=>'Transport Fee',
        $this->TYPE_LIBRARY=>'Library Fee',
        $this->TYPE_PROSPECTUS=>'Prospectus Fee',
        $this->TYPE_ADMISSION=>'Admission Fee',
        $this->TYPE_READMISSION=>'Re.Admission Fee',
        $this->TYPE_OTHER=>'Others',
    );

    //////////////////////////////////////////////////////////
    if(empty($type)){
        return $types;
    }else{
        return $types[$type];
    }
}

//////////////////////////////////////////////// END OF CLASS /////////////////////
}