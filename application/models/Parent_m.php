<?php


class Parent_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
protected $_model_name='Parent_M.php';
protected $_table_name = 'parents';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_ACTIVE='active';             //if user is active
        public $STATUS_UNACTIVE='inactive';         //if user is unactive
        public $TYPE_PARENT='parent';               //if user type is parent
        public $KEY_SESS_PARENT='pjelmino';         //session key for parent
        public $LOGIN_FLAG_FOR_SESSION='mozlogems_';




//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC FUNCTIONS /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	function __construct (){
		parent::__construct();
        $this->KEY_SESS_PARENT.=date('yd');
        $this->LOGIN_FLAG_FOR_SESSION.=date('yd');
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
    $db_row=$this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    $db_row['date']=$this->date;
    $db_row['day']=$this->day;
    $db_row['month']=$this->month;
    $db_row['year']=$this->year;
    $db_row['password']=$this->hash($db_row['password']);
    $db_row['type']=$this->TYPE_PARENT;
    $db_row['image']='default.png';
    $db_row['status']=$this->STATUS_ACTIVE;
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}	    
	
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['name'])||empty($data['parent_id'])){
        return false;  
    }
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	    


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//generate new Student_id
public function get_new_parent_id($start=''){
    if(empty($start)){$start=strtoupper(get_random_string(2));}
    $number=$start.date('ym').mt_rand(111,999).mt_rand(11,99);
    if($this->is_valid_field('parent_id', $number)){
    return $this->get_new_parent_id($start);
    }else{return $number;}
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// SESSION FUNCTIONS ///////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //check if password is valid
    public function is_valid_password($user_id,$password){
        $filter=array();
        $filter['parent_id']=$user_id;
        $filter['password']=$password;  
        if($this->get_rows($filter,'',true)>0){return true;}
        return false;
    }  
    //user login method
    public function login ($filter=array()){
        $local_filter=array();
        $local_filter['parent_id']=$filter['parent_id'];
        $local_filter['password']=$filter['password'];
        if($this->get_rows($filter,'',true)>0){
            $user=$this->get_by($local_filter, TRUE);
            // Log in user
            $_pk=$this->_primary_key;
            $user_rid=$user->$_pk;
            $is_parent=FALSE;
            if($user->type==$this->TYPE_PARENT){$is_parent=TRUE;}
            //role is for campus distinction in tool library
            $data=array('login_id' => $user_rid,$this->LOGIN_FLAG_FOR_SESSION => TRUE,'role'=>'parent');
            $data[$this->KEY_SESS_PARENT]=$is_parent;             
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
        if($this->get_rows($filter,'',true)>0){
            $user=$this->get_by($filter, TRUE);
            //if role is valid role
            if(in_array($user->type, $roles)){
                return $this->loggedin();
            }
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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////






//////////////////////////////////////////////// END OF CLASS /////////////////////
}