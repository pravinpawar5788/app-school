<?php
//////////////////////////////////////////////////////////
/////////////EDITING VERSION : 1 ////////////////////////
//////////////////////////////////////////////////////////
//root controller for visitors
class MY_Controller extends CI_Controller {

    //Global Vars
    public $APP_ROOT;
    public $RES_ROOT;
    public $UPLOADS_ROOT;
    public $ANGULAR_ROOT;
    public $BODY_INIT = '';
    public $FILE_VERSION = '?ver=1.0.1.1';
    public $SORT_ICON= ' ng-class="{\'icon-sort-amount-asc\':appConfig.sortAsc,\'icon-sort-amount-desc\':appConfig.sortDesc}" ';
    public $POPOVER_ICON = ' class="icon icon-question3" style="font-size: 0.6em;" data-html="true" data-popup="popover" data-trigger="hover" data-placement="top"  ';    
    public $POPOVER_CELL = ' style="font-size: 0.9em;" data-html="true" data-popup="popover" data-trigger="hover" data-placement="top"  ';    
    public $SETTINGS = array();
    public $data = array();
    public $PAGE_LIMIT=100;
    public $PRINT_PAGE_LAYOUT ='';
    public $PRINT_PAGE_SIZE ='A4';
    public $ANGULAR_INC = array();
    public $THEME_INC = array();
    public $HEADER_INC = array();
    public $FOOTER_INC = array();
    public $RESPONSE = array();
    public $IS_DEMO = false;
    public $IS_DEV_LOGIN = false;
    public $IS_DEV_MODE_ENABLE = false;
    public $IS_COLLEGE = false;
    public $SMS_HOST_NOTE = '';



    ///////////////////// CONTSTRUCTOR FOR INIT ADMIN APP //////////////////////////
    function __construct() {
        parent::__construct();

        //INIT HOST NAME
        //INIT APP Globals	
        $this->tools->set_base_url();
        $this->APP_ROOT = base_url();
        $this->RES_ROOT = $this->APP_ROOT . 'assets/portal/';
        $this->UPLOADS_ROOT = $this->APP_ROOT . 'uploads/';
        $this->ANGULAR_ROOT = $this->APP_ROOT . 'angular/';
        $this->THEME_RES_ROOT = $this->APP_ROOT.'assets/themes/default/';
        $this->data['main_content'] = 'index';
        //SITE TEMPLATED VARS
        $this->data['card_bg'] = '';  //bg-slate-700 panels bg color template
        $this->data['card_border'] = 'bg-slate-800';   //panel heading bg color template
        $this->data['card_heading_bg'] = 'bg-slate-800';   //panel heading bg color template
        ///////LOAD GENERAL MODELS FOR THIS LIBRARY
        $models = array('user_m','system_history_m','system_setting_m','login_session_m','module_m');
        $this->load->model($models);

    }

    //upload method for subclasses
    public function upload_file($path,$size,$allowed_types,$upload_file_name,$new_name='',$min_width=0,$min_height=0,$max_width=0,$max_height=0){
        $config['upload_path']=$path;   //$path='./assets/uploads/artwork';
        $config['allowed_types']=$allowed_types;    //$allowed_types='jpg|png|';
        $config['min_height']=$min_height;
        $config['min_width']=$min_width;
        $config['overwrite']=true;
        $config['max_size']=$size;  //in KiloBytes //$size='5120';
        $config['max_width']=$max_width;
        $config['max_height']=$max_height;
        if(!empty($new_name)){$config['file_name']=$new_name;}
        $this->load->library('upload',$config);
        if(!$this->upload->do_upload($upload_file_name)){
            $upload_data =$this->upload->data();
            $upload_data['file_uploaded']=FALSE;
            $error=$this->upload->display_errors();
            $upload_data['file_error']=$error;
            return $upload_data;                
        }else{
            $upload_data =$this->upload->data();
            $upload_data['file_uploaded']=TRUE;
            return $upload_data;
        }
    }
    

}

//////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////CLASS FOR Frontend ACCESS//////////////
//////////////////////////////////////////////////////////////////////////////////
class Frontend_Controller extends MY_Controller {

    //Global Vars
    public $LIB_CONT_ROOT = '';
    public $LIB_VIEW_DIR = '';
    public $LOGIN_ID = '';
    public $LOGIN_USER = NULL;
    public $LOGIN_FLAG = FALSE;
    public $IS_SUPER_ADMIN = FALSE;
    public $IS_ADMIN = FALSE;
    public $IS_USER = FALSE;
    public $IS_STAFF = FALSE;

    ///////////////////// CONTSTRUCTOR FOR INIT ADMIN APP //////////////////////////
    function __construct() {
        parent::__construct();
        // INIT CONSTANTS
        $this->LIB_CONT_ROOT = $this->APP_ROOT;
        $this->LIB_VIEW_DIR = 'public/default/'; 
        $this->THEME_RES_ROOT = $this->APP_ROOT.'assets/themes/default/';
        $this->data['main_content'] = 'index';
        ////////////////////////////////////////////////////////////////////////
        ////////////DEFINE GLOBAL VARIABLES---LOAD CONSTANTS IN DATA ARRAY//////

        $this->data['LIB_CONT_ROOT'] = $this->LIB_CONT_ROOT;  //HELPFUL IN VIEW FILES
        $this->data['LIB_VIEW_DIR'] = $this->LIB_VIEW_DIR; //HELPFUL IN VIEW DIR (MASTER.PHP)
        /////////////////////////////////////////////////////////////////////        
        $this->data['LOGIN_ID'] = $this->LOGIN_ID;
        $this->data['LOGIN_USER'] = $this->LOGIN_USER;
        $this->data['LOGIN_FLAG'] = $this->LOGIN_FLAG;
    }

}



//////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////CLASS FOR Docs ACCESS//////////////
//////////////////////////////////////////////////////////////////////////////////
class Docs_Controller extends MY_Controller {

    //Global Vars
    public $LIB_CONT_ROOT = '';
    public $LIB_VIEW_DIR = '';
    public $LOGIN_ID = '';
    public $LOGIN_USER = NULL;
    public $LOGIN_FLAG = FALSE;
    public $IS_ADMIN = FALSE;
    public $IS_PARENT = FALSE;
    public $IS_STAFF = FALSE;
    public $IS_MANAGER = FALSE;

    ///////////////////// CONTSTRUCTOR FOR INIT ADMIN APP //////////////////////////
    function __construct() {
        parent::__construct();
        // INIT CONSTANTS
        $this->LIB_CONT_ROOT = $this->APP_ROOT. 'docs/';
        $this->LIB_VIEW_DIR = 'docs/';
        $this->data['main_content'] = 'index';
        $this->load->database();
        $this->SETTINGS=$this->system_setting_m->get_settings_array();

        /////////LOAD GENERAL MODELS FOR THIS LIBRARY
        $models = array('system_setting_m');
        if (count($models) > 0) {
            $this->load->model($models);
        }

        ////////////////////////////////////////////////////////////////////////
        ////////////DEFINE GLOBAL VARIABLES---LOAD CONSTANTS IN DATA ARRAY//////

        $this->data['LIB_CONT_ROOT'] = $this->LIB_CONT_ROOT;  //HELPFUL IN VIEW FILES
        $this->data['LIB_VIEW_DIR'] = $this->LIB_VIEW_DIR; //HELPFUL IN VIEW DIR (MASTER.PHP)
        /////////////////////////////////////////////////////////////////////        
        $this->data['LOGIN_ID'] = $this->LOGIN_ID;
        $this->data['LOGIN_USER'] = $this->LOGIN_USER;
        $this->data['LOGIN_FLAG'] = $this->LOGIN_FLAG;
        /////////////////////////////////////////////////////////////////////
    }

}


/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////Class for Admin Controller///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

class Admin_Controller extends MY_Controller {

    public $LIB_CONT_ROOT = '';
    public $LIB_VIEW_DIR = '';
    public $IS_ADMIN = FALSE;
    public $IS_MANAGER = FALSE;
    public $LOGIN_USER = '';    //SET MANUALLY FOR TESTING PURPOSE
    public $LOGIN_ID = '';    //SET MANUALLY FOR TESTING PURPOSE 
    public $MODAL_OPTIONS = ' data-toggle="modal" data-backdrop="static" data-kayboard="false" ';

    /**
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     * ***************************************** CONTSTRUCTOR FOR INIT ADMIN APP **************************************
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     */
    function __construct() {
        parent::__construct();
        /////////load all general models for application ///////////////////////
        $models = array('campus_m','campus_setting_m','note_m','student_m','staff_m');
        //load all models in above array
        $this->load->model($models);
        // INIT CONSTANTS
        $this->LIB_CONT_ROOT = $this->APP_ROOT . 'admin/';
        $this->LIB_VIEW_DIR = 'admin/';

        $this->load->database();
        $this->SETTINGS=$this->system_setting_m->get_settings_array();
        ///////////////////////////CHECK FOR DEVELOPMENT MODE/////////////////////////////////
        if(strtolower($this->SETTINGS[$this->system_setting_m->_MAINTENANCE_MODE])=='on'){
            //development/maintenance is in progress
            $this->IS_DEV_MODE_ENABLE= true;
        }
        ///////////////////////////////////////////////////////////
        if(strtolower($this->SETTINGS[$this->system_setting_m->_LIC_STATUS])!=$this->system_setting_m->STATUS_VALID){ 
            $this->session->set_flashdata('error', $this->config->item('app_invalidlic_err'));
            redirect($this->APP_ROOT.$this->config->item('app_lic_redir'), 'refresh'); 
        }

        //////////////////////////////////////////////////////////////////////////
        /*         * ***********************************************************************
         * SESSION VALIDATION
         * ACCESS TO VALID SESSION ONLY
         * *********************************************************************** */

        $login_id = '';
        $redir_url = $this->APP_ROOT . 'auth/logout';
        $exception_uris = array();
        if (in_array(uri_string(), $exception_uris) == FALSE) {
            if ($this->user_m->loggedin() == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
            //is valid user logged in
            $login_id = $this->session->userdata('login_id');
            $roles = array($this->user_m->TYPE_ADMIN); //valid roles for this lib
            if ($this->user_m->is_loggedin($login_id, $roles) == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
        }

        //session filter passed   
        $is_admin = $this->session->userdata($this->user_m->KEY_SESS_ADMIN);
        $this->LOGIN_ID = $this->session->userdata('login_id');
        $this->data['LOGIN_ID'] = $this->LOGIN_ID;
        $this->LOGIN_USER = $this->user_m->get_by_primary($this->LOGIN_ID);
        $this->CAMPUSID=$this->LOGIN_USER->campus_id;
        $this->CAMPUS = $this->campus_m->get_by_primary($this->CAMPUSID);
        $this->CAMPUSSETTINGS = $this->campus_setting_m->get_settings_array(array('campus_id'=>$this->CAMPUSID));
        isset($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION]) && intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION])>0 ? $this->RESPONSE['narration']=TRUE:$this->RESPONSE['narration']=FALSE;
        $this->data['LOGIN_USER'] = $this->LOGIN_USER;
        $this->data['LIB_CONT_ROOT'] = $this->LIB_CONT_ROOT;
        $this->data['LIB_VIEW_DIR'] = $this->LIB_VIEW_DIR;
        //validate that is login user acts as a manager
        if ($is_admin && $this->user_m->is_loggedin($this->LOGIN_ID, array('type'=>$this->user_m->TYPE_ADMIN)) == TRUE) {$this->IS_ADMIN = TRUE;}
        ////////////////////////////////////////////////////////////////////
        ///////////////////////CHECK FOR DEVELOPMENT MODE///////////////////
        if($this->IS_DEV_MODE_ENABLE){
            redirect($this->APP_ROOT.'', 'refresh');
            exit();
        }
    }

}


/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////Class for Manager Controller/////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

class Manager_Controller extends MY_Controller {

    public $LIB_CONT_ROOT = '';
    public $LIB_VIEW_DIR = '';
    public $IS_ADMIN = FALSE;
    public $IS_MANAGER = FALSE;
    public $LOGIN_USER = '';    //SET MANUALLY FOR TESTING PURPOSE
    public $LOGIN_ID = '';    //SET MANUALLY FOR TESTING PURPOSE 
    public $MODAL_OPTIONS = ' data-toggle="modal" data-backdrop="static" data-kayboard="false" ';

    /**
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     * ***************************************** CONTSTRUCTOR FOR INIT ADMIN APP **************************************
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     */
    function __construct() {
        parent::__construct();
        /////////load all general models for application ///////////////////////
        $models = array('campus_m','campus_setting_m','note_m','student_m','staff_m','session_m','sms_hook_m','sms_history_m','accounts_m','accounts_ledger_m','accounts_period_m','income_m','expense_m','class_m','class_section_m');
        //load all models in above array
        $this->load->model($models);
        // INIT CONSTANTS
        $this->LIB_CONT_ROOT = $this->APP_ROOT . 'manager/';
        $this->LIB_VIEW_DIR = 'manager/';

        $this->load->database();
        $this->load->library('smspoint');

        $this->SETTINGS=$this->system_setting_m->get_settings_array();
        ///////////////////////////CHECK FOR DEVELOPMENT MODE/////////////////////////////////
        if(strtolower($this->SETTINGS[$this->system_setting_m->_MAINTENANCE_MODE])=='on'){
            //development/maintenance is in progress
            $this->IS_DEV_MODE_ENABLE= true;
        }
        ///////////////////////////////////////////////////////////
        if(strtolower($this->SETTINGS[$this->system_setting_m->_LIC_STATUS])!=$this->system_setting_m->STATUS_VALID){ 
            $this->session->set_flashdata('error', $this->config->item('app_invalidlic_err'));
            redirect($this->APP_ROOT.$this->config->item('app_lic_redir'), 'refresh'); 
        }

        if($this->SETTINGS[$this->system_setting_m->_ORG_TYPE]==$this->system_setting_m->TYPE_COLLEGE){$this->IS_COLLEGE=true;}
        //////////////////////////////////////////////////////////////////////////
        /*         * ***********************************************************************
         * SESSION VALIDATION
         * ACCESS TO VALID SESSION ONLY
         * *********************************************************************** */

        $login_id = '';
        $redir_url = $this->APP_ROOT . 'auth/logout';
        $exception_uris = array();
        if (in_array(uri_string(), $exception_uris) == FALSE) {
            if ($this->user_m->loggedin() == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
            //is valid user logged in
            $login_id = $this->session->userdata('login_id');
            $roles = array($this->user_m->TYPE_ADMIN,$this->user_m->TYPE_MANAGER); //valid roles for this lib
            if ($this->user_m->is_loggedin($login_id, $roles) == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
        }

        //session filter passed   
        $is_admin = $this->session->userdata($this->user_m->KEY_SESS_ADMIN);
        $is_manager = $this->session->userdata($this->user_m->KEY_SESS_MANAGER);
        $this->LOGIN_ID = $this->session->userdata('login_id');
        $this->data['LOGIN_ID'] = $this->LOGIN_ID;
        $this->LOGIN_USER = $this->user_m->get_by_primary($this->LOGIN_ID);
        $this->CAMPUSID=$this->LOGIN_USER->campus_id;
        $this->CAMPUS = $this->campus_m->get_by_primary($this->CAMPUSID);
        $this->CAMPUSSETTINGS = $this->campus_setting_m->get_settings_array(array('campus_id'=>$this->CAMPUSID));
        isset($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION]) && intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION])>0 ? $this->RESPONSE['narration']=TRUE:$this->RESPONSE['narration']=FALSE;
        $this->data['LOGIN_USER'] = $this->LOGIN_USER;
        $this->data['LIB_CONT_ROOT'] = $this->LIB_CONT_ROOT;
        $this->data['LIB_VIEW_DIR'] = $this->LIB_VIEW_DIR;
        //validate that is login user acts as a manager
        if ($is_admin && $this->user_m->is_loggedin($this->LOGIN_ID, array('type'=>$this->user_m->TYPE_ADMIN)) == TRUE) {$this->IS_ADMIN = TRUE;}
        if ($is_manager && $this->user_m->is_loggedin($this->LOGIN_ID, array('type'=>$this->user_m->TYPE_MANAGER)) == TRUE) {$this->IS_MANAGER = TRUE;}
        ////////////////////////////////////////////////////////////////////
        ///////////////////////CHECK FOR DEVELOPMENT MODE///////////////////
        if($this->IS_DEV_MODE_ENABLE){
            redirect($this->APP_ROOT.'', 'refresh');
            exit();
        }
    }

}


///////////////////////////////////////////////////////////////////////////////////
/////////////////Class for Parent Controller///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

class Parent_Controller extends MY_Controller {

    public $LIB_CONT_ROOT = '';
    public $LIB_VIEW_DIR = '';
    public $LOGIN_USER = '';    //SET MANUALLY FOR TESTING PURPOSE
    public $LOGIN_ID = '';    //SET MANUALLY FOR TESTING PURPOSE  
    public $MODAL_OPTIONS = ' data-toggle="modal" data-backdrop="static" data-kayboard="false" ';
    public $CHILDREN = array();

    /**
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     * ***************************************** CONTSTRUCTOR FOR INIT ADMIN APP **************************************
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     */
    function __construct() {
        parent::__construct();
        /////////load all general models for application ///////////////////////
        $models = array('campus_m','session_m','campus_setting_m','parent_m','student_m','class_m');
        //load all models in above array
        $this->load->model($models);
        // INIT CONSTANTS
        $this->LIB_CONT_ROOT = $this->APP_ROOT . 'parent/';
        $this->LIB_VIEW_DIR = 'parent/';

        $this->load->database();
        $this->SETTINGS=$this->system_setting_m->get_settings_array();
        ///////////////////////////CHECK FOR DEVELOPMENT MODE/////////////////////////////////
        if(strtolower($this->SETTINGS[$this->system_setting_m->_MAINTENANCE_MODE])=='on'){
            //development/maintenance is in progress
            $this->IS_DEV_MODE_ENABLE= true;
        }



        //////////////////////////////////////////////////////////////////////////
        /*         * ***********************************************************************
         * SESSION VALIDATION
         * ACCESS TO VALID SESSION ONLY
         * *********************************************************************** */

        $login_id = '';
        $redir_url = $this->APP_ROOT . 'auth/logout';
        $exception_uris = array();
        if (in_array(uri_string(), $exception_uris) == FALSE) {
            if ($this->parent_m->loggedin() == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
            //is valid user logged in
            $login_id = $this->session->userdata('login_id');
            $roles = array($this->parent_m->TYPE_PARENT); //valid roles for this lib
            if ($this->parent_m->is_loggedin($login_id, $roles) == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
        }

        //session filter passed   
        $is_parent = $this->session->userdata($this->parent_m->KEY_SESS_PARENT);
        $this->LOGIN_ID = $this->session->userdata('login_id');
        $this->data['LOGIN_ID'] = $this->LOGIN_ID;
        $this->LOGIN_USER = $this->parent_m->get_by_primary($this->LOGIN_ID);
        $this->CAMPUSID=$this->LOGIN_USER->campus_id;
        $this->CAMPUS = $this->campus_m->get_by_primary($this->CAMPUSID);
        $this->CAMPUSSETTINGS = $this->campus_setting_m->get_settings_array(array('campus_id'=>$this->CAMPUSID));
        isset($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION]) && intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION])>0 ? $this->RESPONSE['narration']=TRUE:$this->RESPONSE['narration']=FALSE;
        $this->data['LOGIN_USER'] = $this->LOGIN_USER;
        $this->data['LIB_CONT_ROOT'] = $this->LIB_CONT_ROOT;
        $this->data['LIB_VIEW_DIR'] = $this->LIB_VIEW_DIR;
        $this->CHILDREN=$this->student_m->get_rows(array('father_nic'=>$this->LOGIN_USER->cnic,'campus_id'=>$this->CAMPUSID));
        // //validate that is login user acts as a manager
        ////////////////////////////////////////////////////////////////////
        ///////////////////////CHECK FOR DEVELOPMENT MODE///////////////////
        if($this->IS_DEV_MODE_ENABLE){
            redirect($this->APP_ROOT.'', 'refresh');
            exit();
        }
    }

}

/////////////////////////////////////////////////////////////////////////////
///////////////////Class for Staff Controller////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

class Staff_Controller extends MY_Controller {

    public $LIB_CONT_ROOT = '';
    public $LIB_VIEW_DIR = '';
    public $LOGIN_USER = '';    //SET MANUALLY FOR TESTING PURPOSE
    public $LOGIN_ID = '';    //SET MANUALLY FOR TESTING PURPOSE  
    public $MODAL_OPTIONS = ' data-toggle="modal" data-backdrop="static" data-kayboard="false" ';
    public $CHILDREN = array();
    public $IS_TEACHER = FALSE;

    /**
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     * ***************************************** CONTSTRUCTOR FOR INIT ADMIN APP **************************************
     * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     */
    function __construct() {
        parent::__construct();
        /////////load all general models for application ///////////////////////
        $models = array('campus_m','campus_setting_m','session_m','stf_role_m','staff_m','student_m','class_m','class_section_m','stf_qual_m','award_m','punishment_m','certificate_m','stf_attendance_m','stf_allownce_m','stf_acheivement_m','stf_advance_m','stf_award_m','stf_deduction_m','stf_history_m','stf_pay_history_m','stf_pay_entry_m','stf_pay_voucher_m','stf_punishment_m');
        //load all models in above array
        $this->load->model($models);
        // INIT CONSTANTS
        $this->LIB_CONT_ROOT = $this->APP_ROOT . 'staff/';
        $this->LIB_VIEW_DIR = 'staff/';

        $this->load->database();
        $this->SETTINGS=$this->system_setting_m->get_settings_array();
        ///////////////////////////CHECK FOR DEVELOPMENT MODE/////////////////////////////////
        if(strtolower($this->SETTINGS[$this->system_setting_m->_MAINTENANCE_MODE])=='on'){
            //development/maintenance is in progress
            $this->IS_DEV_MODE_ENABLE= true;
        }



        //////////////////////////////////////////////////////////////////////////
        /*         * ***********************************************************************
         * SESSION VALIDATION
         * ACCESS TO VALID SESSION ONLY
         * *********************************************************************** */

        $login_id = '';
        $redir_url = $this->APP_ROOT . 'auth/logout/staff';
        $exception_uris = array();
        if (in_array(uri_string(), $exception_uris) == FALSE) {
            if ($this->staff_m->loggedin() == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
            //is valid user logged in
            $login_id = $this->session->userdata('login_id');
            $roles = array('staff'); //valid roles for this lib
            if ($this->staff_m->is_loggedin($login_id, $roles) == FALSE) {
                $this->session->set_flashdata('error', 'Login Expired! Please Login Again');
                redirect($redir_url, 'refresh');
                exit(1);
            }
        }

        //session filter passed   
        $is_parent = $this->session->userdata($this->staff_m->KEY_SESS_STAFF);
        $this->LOGIN_ID = $this->session->userdata('login_id');
        $this->data['LOGIN_ID'] = $this->LOGIN_ID;
        $this->LOGIN_USER = $this->staff_m->get_by_primary($this->LOGIN_ID);
        $this->CAMPUSID=$this->LOGIN_USER->campus_id;
        $this->CAMPUS = $this->campus_m->get_by_primary($this->CAMPUSID);
        $this->CAMPUSSETTINGS = $this->campus_setting_m->get_settings_array(array('campus_id'=>$this->CAMPUSID));
        isset($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION]) && intval($this->CAMPUSSETTINGS[$this->campus_setting_m->_NARRATION])>0 ? $this->RESPONSE['narration']=TRUE:$this->RESPONSE['narration']=FALSE;
        $this->data['LOGIN_USER'] = $this->LOGIN_USER;
        $this->data['LIB_CONT_ROOT'] = $this->LIB_CONT_ROOT;
        $this->data['LIB_VIEW_DIR'] = $this->LIB_VIEW_DIR;
        //validate that is login user acts as a teacher
        $teacher=$this->stf_role_m->get_by(array('title'=>'teacher'));
        if ($this->LOGIN_USER->role_id==$teacher->mid){$this->IS_TEACHER = TRUE;}
        /////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////
        ///////////////////////CHECK FOR DEVELOPMENT MODE///////////////////
        if($this->IS_DEV_MODE_ENABLE){
            redirect($this->APP_ROOT.'', 'refresh');
            exit();
        }
    }

}
