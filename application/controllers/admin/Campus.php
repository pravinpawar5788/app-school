<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campus extends Admin_Controller{

/** 
* ////////////////////////////////////////////////////////////////////////////////
* *************************** CONTANTS *******************************************
* ////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'campus/';
        //load all models for this controller
        $models = array('accounts_m','accounts_period_m','accounts_ledger_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////
* ***************************** PUBLIC FUNCTIONS *********************************
* ////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		$this->data['main_content']='campus';	
		$this->data['menu']='campus';			
		$this->data['sub_menu']='campus';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='campus';

        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}


    ///////PROFIEL FUNCTION/////////////
    public function profile($rid='',$tab=''){
        if(empty($rid)){
            $this->session->set_flashdata('error', 'Please choose a valid account');
            redirect($this->LIB_CONT_ROOT.'', 'refresh'); 
        }
        
        $this->data['main_content']='campus_profile';    
        $this->data['menu']='campus';            
        $this->data['sub_menu']='campus_profile';
        $this->data['tab']=$tab;

        ////////////////////////////////////////////
        $this->ANGULAR_INC[]='campus_profile';
        $user=$this->campus_m->get_by_primary($rid);   
        $this->data['record']=$user;
        $this->load->view($this->LIB_VIEW_DIR.'master', $this->data);   
    }
    ///////Print function for this module/////////////
    public function printing($action=''){
        
        $form=$this->input->safe_get();
        $this->data['form']=$form;
        if($this->session->flashdata('back_url')){
            $this->session->keep_flashdata('back_url');
        }else{
            $this->session->set_flashdata('back_url', $this->agent->referrer());
        }
        ///////////////////start processing
        switch (strtolower($action)) {
            case 'profile':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid student');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_profile';    
                $this->data['print_page_title']='Student profile'; 

            }
            break;
            case 'list':{
                $this->data['main_content']='student_list';    
                $this->data['print_page_title']='Students List'; 

            }
            break;
            case 'allowances':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'staff', 'refresh');
                }
                $this->data['main_content']='staff_allowances';    
                $this->data['print_page_title']='Staff Member Allowances'; 

            }
            break;
            case 'history':{
                if(empty($form['usr'])){
                    $this->session->set_flashdata('error', 'Please choose a valid staff member');
                    redirect($this->LIB_CONT_ROOT.'student', 'refresh');
                }
                $this->data['main_content']='student_history';    
                $this->data['print_page_title']='Student History'; 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'student', 'refresh');                       
            }break;
        }
        if($this->session->flashdata('back_url')){$this->data['back_url']=$this->session->flashdata('back_url');}    
        $this->load->view($this->LIB_VIEW_DIR.'printing/master', $this->data);   
    }


     //save profile pic
    public function upload_picture(){
        //upload artwork file
        $form=$this->input->safe_post(array("usr"));
        $user=$this->user_m->get_by_primary($form['usr']);
            if($this->IS_DEMO){
                $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
                redirect($this->CONT_ROOT.'profile/'.$user->mid, 'refresh');                
            }
            $file_name=$user->user_id.mt_rand(101,999);
            $path='./uploads/images/profile';
            $data=$this->upload_img('file',$file_name,$path);
            if($data['file_uploaded']==FALSE){
                $this->session->set_flashdata('error', $data['file_error']);
                redirect($this->CONT_ROOT.'profile/'.$user->mid, 'refresh');
            }
            $nfile_name=$data['file_name'];
            $saveform=array('image'=>$nfile_name);
            $this->user_m->save($saveform,$user->mid);
            $this->session->set_flashdata('success', 'Image uploaded successfully.');
            redirect($this->CONT_ROOT.'profile/'.$user->mid, 'refresh');           
    
    }
    ////////////////////upload file///////////////////////////////
    private function upload_img($file_name='file',$new_name='',$path){   
        $size=isset($this->SETTINGS['max_upload_size']) ? $this->SETTINGS['max_upload_size'] : '800';    //0.8MB
        $allowed_types='jpg|jpeg|png|bmp';
        $upload_file_name=$file_name;    
        $min_width=$this->config->item('app_img_min_width');
        $min_height=$this->config->item('app_img_min_height');
        $upload_data=$this->upload_file($path,$size,$allowed_types,$upload_file_name,$new_name,$min_width,$min_height);
        return $upload_data;
    }  

    public function login($rid=''){
        if(empty($rid) || $this->user_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->session->set_flashdata('error', 'Please choose a valid account');
            redirect($this->LIB_CONT_ROOT.'', 'refresh'); 
        }else{
            //start login process   
            $admin=$this->user_m->get_by_primary($rid);
            $this->user_m->save(array('campus_id'=>$admin->campus_id),$this->LOGIN_USER->mid);
            $url=$this->APP_ROOT.'manager';
            redirect($url.'', 'refresh'); 

        }
         
    } 
    /** 
    * /////////////////////////////////////////////////////////////////////
    * *********************** AJAX FUNCTIONS ******************************
    * /////////////////////////////////////////////////////////////////////
    */

    // filter rows
    public function filter(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('name','contact_number','address','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='name ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        // $params['select']='';
        // $params['distinct']=FALSE;
        $this->RESPONSE['rows']=$this->campus_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->campus_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function add(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        $this->RESPONSE['message']=' Registered Successfully.';
        //check for necessary required data   
        $required=array('name','contact_number','password','mobile','password','mname');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if($this->campus_m->get_rows(array('name'=>$form['name']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Campus already registered! Please choose another name...';
            echo json_encode($this->RESPONSE);exit();
        } 
        //check for necessary data   
        if($this->user_m->get_rows(array('email'=>$form['email']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Email already registered! Please choose another email...';
            echo json_encode($this->RESPONSE);exit();
        }
        $rid=$this->campus_m->add_row($form);
        if($rid===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later!';
            echo json_encode($this->RESPONSE);exit();            
        }
        //init campus settings and accounts.
        $this->campus_setting_m->reset_settings($rid);
        $this->accounts_m->validate_default_tables($rid);
        $this->user_m->add_row(array('campus_id'=>$rid,'name'=>$form['mname'],'email'=>$form['email'],'mobile'=>$form['mobile'],'password'=>$form['password'],'type'=>$this->user_m->TYPE_MANAGER));

        //add data to user history
        $history=array('user_id'=>$this->LOGIN_USER->mid);
        $history['message']="Registered new campus(".$form['name'].")";
        $this->system_history_m->add_row($history);

        //send back the resposne  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function update(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database                
        if($this->campus_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later!';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function load(){
        // get input fields into array
        $filter=array();
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->campus_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->campus_m->get_by_primary($rid);
        
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    // update row
    public function delete(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if(empty($rid)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record.';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->campus_m->get_by_primary($form['rid']); 

        if($this->campus_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later!';
            echo json_encode($this->RESPONSE);exit();
        }

        //add data to user history
        $history=array('user_id'=>$this->LOGIN_USER->mid);
        $history['message']="Removed campus($row->name, $row->contact_number, $row->address)";
        $this->system_history_m->add_row($history);

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    /////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////


    // edit row
    public function changeStatus(){ 
            // get input fields into array        
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('rid','status');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            //check for demo   
            if($this->IS_DEMO){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
                echo json_encode($this->RESPONSE);exit();
            }

            //check if a valid staff is being edited
            if(empty($form['rid'])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid record...';
                echo json_encode($this->RESPONSE);exit();
            }

            //save data in database
            if($this->user_m->save(array('status'=>$form['status']),$form['rid'])===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Process stopped. Please try again later!';
                echo json_encode($this->RESPONSE);exit();
            }

            //send back repsonse
            $this->RESPONSE['message']='Updated Successfully...';
            echo json_encode($this->RESPONSE);exit();
    }
    // edit row
    public function updateAdminPassword(){ 
            // get input fields into array        
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for demo   
            if($this->IS_DEMO){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']=$this->config->item('app_demo_edit_err');
                echo json_encode($this->RESPONSE);exit();
            }
            //check for necessary required data   
            $required=array('rid','password');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   

            //check for demo   
            if($this->IS_DEMO){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
                echo json_encode($this->RESPONSE);exit();
            }
            //check if a valid staff is being edited
            if(empty($form['rid'])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid record...';
                echo json_encode($this->RESPONSE);exit();
            }

            $form['password']=$this->user_m->hash($form['password']);  
            //save data in database
            if($this->user_m->save(array('password'=>$form['password']),$form['rid'])===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Process stopped. Please try again later!';
                echo json_encode($this->RESPONSE);exit();
            }

            //send back repsonse
            $this->RESPONSE['message']='Updated Successfully...';
            echo json_encode($this->RESPONSE);exit();
    }

    ///////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////CAMPUS FUNCTIONS//////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterAdmins(){
        // get input fields into array
        $filter=array();
        $params=array();
        $search=array('name','mobile','user_id','date');
        $like=array();
        $required=array('rid');
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ////////////////////////////////////////////////////////////////////////////////
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        $filter['campus_id']=$form['rid'];
        $filter['type']=$this->user_m->TYPE_MANAGER;

        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='name ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->user_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->user_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addAdmin(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        $this->RESPONSE['message']=' Registered Successfully.';
        //check for necessary required data   
        $required=array('name','mobile','password');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }   
        $form['campus_id']=$form['rid'];
        $form['type']=$this->user_m->TYPE_MANAGER;
        $rid=$this->user_m->add_row($form);
        if($rid===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later!';
            echo json_encode($this->RESPONSE);exit();            
        }
        //add data to user history
        $history=array('user_id'=>$this->LOGIN_USER->mid);
        $history['message']="Registered new campus admin(".$form['name'].")";
        $this->system_history_m->add_row($history);

        //send back the resposne  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateAdmin(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','name');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if($this->user_m->get_rows(array('user_id'=>$form['user_id'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Admin ID already assigned! Please choose another one...';
            echo json_encode($this->RESPONSE);exit();
        }
        //save data in database                
        if($this->user_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later!';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function loadAdmin(){
        // get input fields into array
        $filter=array();
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->user_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->user_m->get_by_primary($rid);
        
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    // update row
    public function deleteAdmin(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if(empty($rid)){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid record.';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->user_m->get_by_primary($form['rid']); 

        if($this->user_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later!';
            echo json_encode($this->RESPONSE);exit();
        }

        //add data to user history
        $history=array('user_id'=>$this->LOGIN_USER->mid);
        $history['message']="Removed campus admin($row->name, $row->mobile, $row->user_id)";
        $this->system_history_m->add_row($history);

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    /////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////


    // filter rows
    public function filterHistory(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('date');
            $like=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('rid');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            ///////////////////////////////////////////////////////////////////////////////////////////
            isset($form['rid']) && !empty($form['rid']) ? $filter['campus_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->system_history_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->system_history_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }


    
/** 
* /////////////////////////////////////////////////////////////////////////////////////
* ************************** END OF CLASS *********************************************
* /////////////////////////////////////////////////////////////////////////////////////
*/

}
	