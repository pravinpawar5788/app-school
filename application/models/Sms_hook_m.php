<?php


class Sms_hook_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Sms_hook_M.php';
	protected $_table_name = 'sms_hook';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $TARGET_STUDENT='student'; 
        public $TARGET_STAFF='staff'; 
        public $TARGET_GAURDIAN='guardian'; 
        //////////////////////////////////////////////////////////
        public $HOOK_ADMISSION='admission';
        public $HOOK_ADVANCE_DEPOSIT='advance_deposit';
        public $HOOK_STUDENT_PRESENT='att_std_present';
        public $HOOK_STUDENT_ABSENT='att_std_absent';
        public $HOOK_STUDENT_LEAVE='att_std_leave';
        public $HOOK_STAFF_PRESENT='att_stf_present';
        public $HOOK_STAFF_ABSENT='att_stf_absent';
        public $HOOK_STAFF_LEAVE='att_stf_leave';
        public $HOOK_STUDENT_PAY_FEE='att_std_pay_fee';
        public $HOOK_STAFF_GET_PAID='att_stf_get_paid';




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
    if(empty($data['campus_id'])||empty($data['hook'])){
        return false;}
    
    ///////////// data is valid to insert or update
    return true;
    
}	    

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function get_hooks($h_key=''){
    $hooks=array();
    //$hooks=array('event',$target=array(),$keys=array());

    $hooks[$this->HOOK_ADMISSION]=array(
        'event'=>'when newly registered in the sytem',
        'target'=>array($this->TARGET_STAFF,$this->TARGET_GAURDIAN,$this->TARGET_STUDENT),
        'keys'=>array(
            $this->TARGET_STAFF=>array('NAME'),
            $this->TARGET_STUDENT=>array('NAME'),
            $this->TARGET_GAURDIAN=>array('NAME','GUARDIAN')
        )
    );
    $hooks[$this->HOOK_ADVANCE_DEPOSIT]=array(
        'event'=>'when student deposit advance fee',
        'target'=>array($this->TARGET_GAURDIAN,$this->TARGET_STUDENT),
        'keys'=>array(
            $this->TARGET_STAFF=>array(),
            $this->TARGET_STUDENT=>array('NAME'),
            $this->TARGET_GAURDIAN=>array('NAME','GUARDIAN')
        )
    );
    $hooks[$this->HOOK_STUDENT_PRESENT]=array(
        'event'=>'when student marked as present',
        'target'=>array($this->TARGET_GAURDIAN,$this->TARGET_STUDENT),
        'keys'=>array(
            $this->TARGET_STAFF=>array(),
            $this->TARGET_STUDENT=>array('NAME'),
            $this->TARGET_GAURDIAN=>array('NAME','GUARDIAN')
        )
    );
    $hooks[$this->HOOK_STUDENT_ABSENT]=array(
        'event'=>'when student marked as absent',
        'target'=>array($this->TARGET_GAURDIAN,$this->TARGET_STUDENT),
        'keys'=>array(
            $this->TARGET_STAFF=>array(),
            $this->TARGET_STUDENT=>array('NAME'),
            $this->TARGET_GAURDIAN=>array('NAME','GUARDIAN')
        )
    );
    $hooks[$this->HOOK_STUDENT_LEAVE]=array(
        'event'=>'when student marked as on leave',
        'target'=>array($this->TARGET_GAURDIAN,$this->TARGET_STUDENT),
        'keys'=>array(
            $this->TARGET_STAFF=>array(),
            $this->TARGET_STUDENT=>array('NAME'),
            $this->TARGET_GAURDIAN=>array('NAME','GUARDIAN')
        )
    );
    $hooks[$this->HOOK_STUDENT_PAY_FEE]=array(
        'event'=>'when student pay fee voucher',
        'target'=>array($this->TARGET_GAURDIAN,$this->TARGET_STUDENT),
        'keys'=>array(
            $this->TARGET_STAFF=>array(),
            $this->TARGET_STUDENT=>array('NAME','FEE_PAID','BALANCE','CLASS','SECTION','MONTH'),
            $this->TARGET_GAURDIAN=>array('NAME','GUARDIAN','FEE_PAID','BALANCE','CLASS','SECTION','MONTH')
        )
    );
    $hooks[$this->HOOK_STAFF_PRESENT]=array(
        'event'=>'when staff marked as present',
        'target'=>array($this->TARGET_STAFF),
        'keys'=>array(
            $this->TARGET_STAFF=>array('NAME'),
            $this->TARGET_STUDENT=>array(),
            $this->TARGET_GAURDIAN=>array()
        )
    );
    $hooks[$this->HOOK_STAFF_ABSENT]=array(
        'event'=>'when staff marked as absent',
        'target'=>array($this->TARGET_STAFF),
        'keys'=>array(
            $this->TARGET_STAFF=>array('NAME'),
            $this->TARGET_STUDENT=>array(),
            $this->TARGET_GAURDIAN=>array()
        )
    );
    $hooks[$this->HOOK_STAFF_LEAVE]=array(
        'event'=>'when staff marked as on leave',
        'target'=>array($this->TARGET_STAFF),
        'keys'=>array(
            $this->TARGET_STAFF=>array('NAME'),
            $this->TARGET_STUDENT=>array(),
            $this->TARGET_GAURDIAN=>array()
        )
    );
    $hooks[$this->HOOK_STAFF_GET_PAID]=array(
        'event'=>'when staff receive salary',
        'target'=>array($this->TARGET_STAFF),
        'keys'=>array(
            $this->TARGET_STAFF=>array('NAME','AMOUNT'),
            $this->TARGET_STUDENT=>array(),
            $this->TARGET_GAURDIAN=>array()
        )
    );

    //////////////////////////////////////////////////////////
    if(empty($h_key)){
        return $hooks;
    }else{
        return $hooks[$h_key];
    }
}





//////////////////////////////////////////////// END OF CLASS /////////////////////
}