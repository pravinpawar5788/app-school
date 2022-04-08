<?php


class Staff_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        protected $_model_name='Staff_M.php';
	protected $_table_name = 'staff';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_ACTIVE='active';         //if staff is active
        public $STATUS_UNACTIVE='disabled';     //if staff is disabled
        public $STATUS_TRANSFERRED='transferred';//if staff is transferred
        public $STATUS_EXPELLED='expelled';     //if staff is expelled
        public $STATUS_RETIRED='retired';       //if staff is retired
        public $STATUS_LEFT='left';             //if staff is left
        public $VISIBILITY_SHOW='show';         //if staff is visible
        public $VISIBILITY_HIDE='hide';         //if staff is hidden
        public $OPTION_YES='yes';               //if option is yes
        public $OPTION_NO='no';                 //if option is no
        public $KEY_SESS_STAFF='mozemsstf_logged';
        public $LOGIN_FLAG_FOR_SESSION='mozsmsstf_loggedin';




//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC FUNCTIONS /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	function __construct (){
		parent::__construct();
        $this->KEY_SESS_STAFF.=date('yd');
        $this->LOGIN_FLAG_FOR_SESSION.=date('yd');
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
    $db_row['date']=$this->date;
    $db_row['jd']=$this->todayjd;
    $db_row['image']='default.png';
    $db_row['status']=$this->STATUS_ACTIVE;
    $db_row['month']=$this->month;
    $db_row['year']=$this->year;
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['staff_id'])||empty($data['name'])){
        return false;
    }
    ///////////// data is valid to insert or update
    return true;
    
}	    
// DELETE ROW (PRIMARY KEY)
public function delete($id=NULL,$filter=array(),$force=FALSE){
    
    ///////////////////////////////////
    //////////////////////////////
    $this->stf_allownce_m->delete(NULL,$filter);
    $this->stf_advance_m->delete(NULL,$filter);
    $this->stf_acheivement_m->delete(NULL,$filter);
    $this->stf_attendance_m->delete(NULL,$filter);
    $this->stf_award_m->delete(NULL,$filter);
    $this->stf_deduction_m->delete(NULL,$filter);
    $this->stf_history_m->delete(NULL,$filter);
    $this->stf_pay_entry_m->delete(NULL,$filter);
    $this->stf_pay_history_m->delete(NULL,$filter);
    $this->stf_pay_voucher_m->delete(NULL,$filter);
    $this->stf_punishment_m->delete(NULL,$filter);
    //////////////////////////////
    return parent::delete($id);
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//generate new Staff_id
public function get_new_staff_id($start=''){
    if(empty($start)){$start=strtoupper(get_random_string(1));}
    $number=$start.date('ym').mt_rand(111,999).mt_rand(11,99);
    if($this->is_valid_field('staff_id', $number)){
    return $this->get_new_staff_id($start);
    }else{return $number;}
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// SESSION FUNCTIONS ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //check if password is valid
    public function is_valid_password($user_id,$password){
        $filter=array();
        $filter['staff_id']=$user_id;
        $filter['password']=$password;   
        if($this->get_rows($filter,'',true)>0){return true;}
        return false; 
    }  
    //user login method
    public function login ($filter=array()){
        $local_filter=array();
        $local_filter['staff_id']=$filter['staff_id'];
        $local_filter['password']=$filter['password'];
        if($this->get_rows($filter,'',true)>0){
            $user=$this->get_by($local_filter, TRUE);
            // Log in user
            $_pk=$this->_primary_key;
            $user_rid=$user->$_pk;
            $is_admin=FALSE;
            $is_staff=TRUE;
            //role is for campus distinction in tool library
            $data=array('login_id' => $user_rid,$this->LOGIN_FLAG_FOR_SESSION => TRUE,'role'=>'staff');
            $data[$this->KEY_SESS_STAFF]=$is_staff;             
            $this->session->set_userdata($data);     
            return $user_rid;                
        }
        // If we get to here then login did not succeed
        return FALSE;
    }
    
    
    //check if user role is loggedin
    public function is_loggedin($user_id,$roles=array()){
        
       //check that the specific role is logged in or not
        $filter=array();
        $filter['mid']=$user_id;
        $user=$this->get_by($filter, TRUE);
        if(!empty($user) && $this->session->userdata('role')=='staff'){
                return $this->loggedin();
        }
        // If we get to here then a valid user is not loggedin
        return FALSE;
    }
    ///check if user loggedin
    public function loggedin (){
    return (bool) $this->session->userdata($this->LOGIN_FLAG_FOR_SESSION);
    }
        
    //logout the user
    public function logout(){
        $this->session->sess_destroy();                
    }



//////////////////////////////////////////////// END OF CLASS /////////////////////
}