<?php


class User_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
protected $_model_name='User_M.php';
protected $_table_name = 'users';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $STATUS_ACTIVE='active';         //if user is active
        public $STATUS_UNACTIVE='inactive';     //if user is unactive
        public $TYPE_ADMIN='admin';             //if user type is admin
        public $TYPE_SUBADMIN='subadmin';       //if user type is sub admin
        public $TYPE_MANAGER='manager';         //if user type is manager
        public $KEY_SESS_ADMIN='domino';        //session key for admin
        public $KEY_SESS_SUBADMIN='sbdomino';   //session key for admin
        public $KEY_SESS_MANAGER='jelmino';     //session key for manager
        public $LOGIN_FLAG_FOR_SESSION='mozlogems_';

        ///PERMISSION MODEL FOR THE APPLICATION
        //permission <1 = permission denied
        //permission 1 = view section
        //permission 2 = view section + perform add/update operations
        //permission 3 = view section + perform add/update operations + perform delete operation
        //permission >=4 = all operations like super admin



//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC FUNCTIONS /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	function __construct (){
		parent::__construct();
        $this->KEY_SESS_ADMIN.=date('yd');
        $this->KEY_SESS_SUBADMIN.=date('yd');
        $this->KEY_SESS_MANAGER.=date('yd');
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
    if(empty($db_row['type'])){$db_row['type']=$this->TYPE_SUBADMIN;}
    if(empty($db_row['user_id'])){$db_row['user_id']=$this->get_new_admin_id();}
    $db_row['password']=$this->hash($db_row['password']);
    $db_row['status']=$this->STATUS_ACTIVE;
    $db_row['date']=$this->date;
    $db_row['image']='default.png';
    $db_row=$this->add_permissions($db_row);
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}   
	
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['user_id'])||empty($data['name'])||empty($data['password'])){
        return false;}
    ///////////// data is valid to insert or update
    return true;
    
}	    
//generate new Student_id
public function get_new_admin_id($start=''){
    if(empty($start)){$start=strtoupper(get_random_string(2));}
    $number=$start.date('ymdi').mt_rand(1,9).mt_rand(1,9);
    if($this->is_valid_field('user_id', $number)){
    return $this->get_new_admin_id($start);
    }else{return $number;}
}


////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// SESSION FUNCTIONS ///////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
    //check if password is valid
    public function is_valid_password($user_id,$password){
        $filter=array();
        $filter['user_id']=$user_id;
        $filter['password']=$password;
        if($this->get_rows($filter,'',true)>0){return true;}
        return false;
    }  
    //user login method
    public function login ($filter=array()){
        $local_filter=array();
        $local_filter['user_id']=$filter['user_id'];
        $local_filter['password']=$filter['password'];
        if($this->get_rows($filter,'',true)>0){
            $user=$this->get_by($local_filter, TRUE);
            // Log in user
            $_pk=$this->_primary_key;
            $user_rid=$user->$_pk;
            $is_admin=FALSE;
            $is_manager=FALSE;
            $is_subadmin=FALSE;
            if($user->type==$this->TYPE_ADMIN){$is_admin=TRUE;}
            if($user->type==$this->TYPE_MANAGER){$is_manager=TRUE;}
            if($user->type==$this->TYPE_SUBADMIN){$is_subadmin=TRUE;}
            //role is for campus distinction in tool library
            $data=array('login_id' => $user_rid,$this->LOGIN_FLAG_FOR_SESSION => TRUE,'role'=>'admin');
            $data[$this->KEY_SESS_ADMIN]=$is_admin;            
            $data[$this->KEY_SESS_MANAGER]=$is_manager;            
            $data[$this->KEY_SESS_SUBADMIN]=$is_subadmin;            
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
        if($this->get_rows($filter,'',true)>0){
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

///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////// GETTER FUNCTIONS ////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////


    //add permissions to user accounts.
    public function add_permissions($data){
        switch ($data['type']) {
            case $this->TYPE_ADMIN :{
                $data['prm_std_info']=9;
                $data['prm_stf_info']=9;
                $data['prm_finance']=9;
                $data['prm_class']=9;
                $data['prm_library']=9;
                $data['prm_stationary']=9;
                $data['prm_transport']=9;
                $data['prm_parents']=9;
            }
            break;
            case $this->TYPE_MANAGER :{
                $data['prm_std_info']=9;
                $data['prm_stf_info']=9;
                $data['prm_finance']=9;
                $data['prm_class']=9;
                $data['prm_library']=9;
                $data['prm_stationary']=9;
                $data['prm_transport']=9;
                $data['prm_parents']=9;
            }
            break;
            case $this->TYPE_SUBADMIN :{
                $data['prm_std_info']=0;
                $data['prm_stf_info']=0;
                $data['prm_finance']=0;
                $data['prm_class']=0;
                $data['prm_library']=0;
                $data['prm_stationary']=0;
                $data['prm_transport']=0;
                $data['prm_parents']=0;
            }
            break;
        }
        return $data;           
    }


//////////////////////////////////////////////// END OF CLASS /////////////////////
}