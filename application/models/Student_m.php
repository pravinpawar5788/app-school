<?php


class Student_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Student_M.php';
	protected $_table_name = 'student';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_ACTIVE='active';         //if student is active
        public $STATUS_UNACTIVE='inactive';     //if student is unactive
        public $STATUS_PASSOUT='passout';       //if student is passedout
        public $STATUS_EXPELLED='expelled';     //if student is expelled
        public $STATUS_LEFT='left';             //if student left the school
        public $STATUS_TRANSFER='transfer';     //if student transfered the school
        public $STATUS_ALUMNI='alumni';         //if student completed the studies from the institute
        public $STATUS_PASS='pass';             //if student is ready to promotion to next class
        public $STATUS_FAIL='fail';             //if student is not ready to promotion to next class
        public $FEE_TYPE_MONTHLY='monthly';     //if fee collection is monthly
        public $FEE_TYPE_FIXED='fixed';         //if fee collection is fixed for one term
        public $VISIBILITY_SHOW='show';         //if student is visible
        public $VISIBILITY_HIDE='hide';         //if student is hidden
        public $OPTION_YES='yes';               //if option is yes
        public $OPTION_NO='no';                 //if option is no
        public $LOGIN_FLAG_FOR_SESSION='mozsmsstd_loggedin';




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
    $this->load->library('fabsam');
    $this->fabsam->validate_jds();
    return true;   
  
}
//ADD NEW TABLE ROW IN DATABASE 
public function add_row($vals){
    //GET ALL THE FIELDS IN ARRAY  
    $db_row=  $this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    if(empty($db_row['status'])){$db_row['status']=$this->STATUS_ACTIVE;}
    if(empty($db_row['fee_type'])){$db_row['fee_type']=$this->FEE_TYPE_MONTHLY;}
    if(empty($db_row['computer_number'])){
        $number=$this->get_new_computer_number();
        $db_row['computer_number']=$number;
        $db_row['family_number']=$number;
    }
    $db_row['month']=$this->month;
    $db_row['year']=$this->year;
    $db_row['date']=$this->date;
    $db_row['jd']=$this->todayjd;
    $db_row['image']='default.png';
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
	
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if( empty($data['campus_id'])|| empty($data['student_id'])||empty($data['name'])){
        return false;}
    
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	    
// DELETE ROW (PRIMARY KEY)
public function delete($id=NULL, $filter=array(),$force=FALSE){
    
    //////////////////////////////
    $this->std_acheivement_m->delete(NULL,$filter);
    $this->std_attendance_m->delete(NULL,$filter);
    $this->std_award_m->delete(NULL,$filter);
    $this->std_history_m->delete(NULL,$filter);
    $this->std_fee_entry_m->delete(NULL,$filter);
    $this->std_fee_history_m->delete(NULL,$filter);
    $this->std_fee_voucher_m->delete(NULL,$filter);
    $this->std_punishment_m->delete(NULL,$filter);
    //////////////////////////////
    parent::delete($id);
    ////////////////////////////////
    return TRUE;
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//generate new Student_id
public function get_new_student_id($start=''){
    if(empty($start)){$start=strtoupper(get_random_string(1));}
    $number=$start.date('ymd').mt_rand(111,999).mt_rand(11,99);
    if($this->is_valid_field('student_id', $number)){
    return $this->get_new_student_id($start);
    }else{return $number;}
}


//update advance
public function update_advance($rid,$amount,$type='plus'){
    $row=$this->get_by_primary($rid);
    switch (strtolower($type)) {
        case 'minus':{
            $new_amount=$row->advance_amount-$amount;
            $this->save(array('advance_amount'=>$new_amount),$row->mid);
        }
        break;
        
        default:{
            $new_amount=$row->advance_amount+$amount;
            $this->save(array('advance_amount'=>$new_amount),$row->mid);
        }
        break;
    }
}


public function get_status_types($type=''){
    $types=array(
        $this->STATUS_ACTIVE=>'Active',
        $this->STATUS_UNACTIVE=>'Disabled',
        $this->STATUS_PASSOUT=>'Graduated',
        $this->STATUS_EXPELLED=>'Expelled',
        $this->STATUS_LEFT=>'Leaved',
        $this->STATUS_TRANSFER=>'Migrated',
        $this->STATUS_ALUMNI=>'Alumni',
    );

    //////////////////////////////////////////////////////////
    if(empty($type)){
        return $types;
    }else{
        return $types[$type];
    }
}

//return student of active session only
public function get_session_students($campus_id, $class_id='',$section_id=''){
    $activeSession=$this->session_m->getActiveSession();
    $filter=array('campus_id'=>$campus_id);
    $filter['session_id']=$activeSession->mid;
    $filter['status']=$this->STATUS_ACTIVE;
    if(!empty($class_id)){$filter['class_id']=$class_id;}
    if(!empty($section_id)){$filter['section_id']=$section_id;}
    $students=$this->student_m->get_rows($filter);
    return $students;
}



//////////////////////////////////////////////// END OF CLASS /////////////////////
}