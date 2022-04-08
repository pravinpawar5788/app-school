<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transport extends Manager_Controller{

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** CONTANTS *************************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
	public $WEBSITE_HOME;
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        if($this->LOGIN_USER->prm_transport<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'transport/';
        //load all models for this controller
        $models = array('transport_route_m','transport_route_vehicle_m','transport_vehicle_m','transport_passenger_m','class_m','class_section_m','student_m','staff_m','std_history_m','stf_history_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		
		$this->data['main_content']='transport';	
		$this->data['menu']='transport';			
		$this->data['sub_menu']='transport';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='transport';
        /////////////////////////////////////////////////////////////
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
                $this->data['main_content']='passenger_list';    
                $this->data['print_page_title']='Passengers List'; 

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


    /** 
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * ***************************************** AJAX FUNCTIONS *******************************************************
    * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    // filter rows
    public function filter(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('passenger_name','date');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['type']) && !empty($form['type']) ? $filter['type']= $form['type'] : '';
        isset($form['route_id']) && !empty($form['route_id']) ? $filter['route_id']= $form['route_id'] : '';
        isset($form['vehicle_id']) && !empty($form['vehicle_id']) ? $filter['vehicle_id']= $form['vehicle_id'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->transport_passenger_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->transport_passenger_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['vehicle']=$this->transport_vehicle_m->get_by_primary($row['vehicle_id'])->reg_no;
            $this->RESPONSE['rows'][$i]['route']=$this->transport_route_m->get_by_primary($row['route_id'])->name;
            if($row['type']==$this->transport_passenger_m->TYPE_STAFF){
                $user=$this->staff_m->get_by_primary($row['passenger_id']);
                $this->RESPONSE['rows'][$i]['fee']=$user->transport_fee;
                $this->RESPONSE['rows'][$i]['image']=$user->image;
            }elseif($row['type']==$this->transport_passenger_m->TYPE_STUDENT){
                $user=$this->student_m->get_by_primary($row['passenger_id']);
                $this->RESPONSE['rows'][$i]['fee']=$user->transport_fee;
                $this->RESPONSE['rows'][$i]['image']=$user->image;
            }
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function add(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('user_id','vehicle_id','route_id','user_type');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        switch (strtolower($form['user_type'])) {
            case 'staff':{
                //add staff
                if($this->transport_passenger_m->get_rows(array('campus_id'=>$this->CAMPUSID,'passenger_id'=>$form['user_id'],'type'=>$this->transport_passenger_m->TYPE_STAFF),'',true)>0){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Member already registered in the tranport system...';
                    echo json_encode($this->RESPONSE);exit();
                }
                //save data in database
                $user=$this->staff_m->get_by_primary($form['user_id']);
                $form['passenger_id']=$user->mid;              
                $form['passenger_name']=$user->name;              
                $form['type']=$this->transport_passenger_m->TYPE_STAFF;  
                $form['campus_id']=$this->CAMPUSID;            
                if($this->transport_passenger_m->add_row($form)==false){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Member cannot be registered at this time. Please try again later...';
                    echo json_encode($this->RESPONSE);exit();            
                }      
                //update transport fee          
                $this->staff_m->save(array('transport_fee'=>$form['fare']),$user->mid);       
                //add to user history
                $history=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$user->mid,'title'=>'Register to avail transport fascility');
                $history['description']='Register to avail transport fascility';
                $this->stf_history_m->add_row($history);
                //Log the user Activity
                $msg=$this->LOGIN_USER->name." registered the staff member(".$user->name.") in transport.";
                $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));
            }
            break;
            
            default:{
                //add student
                if($this->transport_passenger_m->get_rows(array('campus_id'=>$this->CAMPUSID,'passenger_id'=>$form['user_id'],'type'=>$this->transport_passenger_m->TYPE_STUDENT),'',true)>0){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Member already registered in the tranport system...';
                    echo json_encode($this->RESPONSE);exit();
                }
                //save data in database
                $user=$this->student_m->get_by_primary($form['user_id']);
                $form['passenger_id']=$user->mid;              
                $form['passenger_name']=$user->name;              
                $form['type']=$this->transport_passenger_m->TYPE_STUDENT;
                $form['campus_id']=$this->CAMPUSID;              
                if($this->transport_passenger_m->add_row($form)==false){
                    $this->RESPONSE['error']=TRUE;
                    $this->RESPONSE['message']='Member cannot be registered at this time. Please try again later...';
                    echo json_encode($this->RESPONSE);exit();            
                }      
                //update transport fee          
                $this->student_m->save(array('transport_fee'=>$form['fare']),$user->mid);       
                //add to user history
                $history=array('campus_id'=>$this->CAMPUSID,'student_id'=>$user->mid,'title'=>'Registered to avail transport fascility');
                $history['description']='Registered to avail transport fascility';
                $this->std_history_m->add_row($history);
                //Log the user Activity
                $msg=$this->LOGIN_USER->name." registered the student(".$user->name.") in transport.";
                $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

            }
            break;
        }
        //send back the resposne  
        $this->RESPONSE['message']=' Member registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function update(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','fare');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 

        $row=$this->transport_passenger_m->get_by_primary($form['rid']);
        switch ($row->type) {
            case $this->transport_passenger_m->TYPE_STAFF:{
                //update transport fee          
                $this->staff_m->save(array('transport_fee'=>$form['fare']),$row->passenger_id);
            }
            break;            
            default:{
                //update transport fee          
                $this->student_m->save(array('transport_fee'=>$form['fare']),$row->passenger_id);
            }
            break;
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
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->student_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid student...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->student_m->get_by_primary($rid);
        $filter['student_id']=$row->mid;
        $row->total_awards=$this->std_award_m->get_rows($filter,'',true);
        $row->total_punishments=$this->std_punishment_m->get_rows($filter,'',true);
        $row->total_acheivements=$this->std_acheivement_m->get_rows($filter,'',true);
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
        if(empty($rid)|| $this->transport_passenger_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid passenger...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->transport_passenger_m->get_by_primary($rid);
        if($this->transport_passenger_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Passenger can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        switch ($row->type) {
            case $this->transport_passenger_m->TYPE_STAFF:{
                //update transport fee          
                $this->staff_m->save(array('transport_fee'=>0),$row->passenger_id);
            }
            break;            
            default:{
                //update transport fee          
                $this->student_m->save(array('transport_fee'=>0),$row->passenger_id);
            }
            break;
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed passenger (".$row->passenger_name.") from transportation.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////MISC FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterStaff(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','guardian_name','mobile','staff_id','cnic');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT*3;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='name ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->staff_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
    // filter rows
    public function filterStudents(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name','father_name','father_nic','guardian_name','student_id','mobile','roll_no');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['class_id']) && !empty($form['class_id']) ? $filter['class_id']= $form['class_id'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT*3;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='class_id ASC, roll_no ASC, mid DESC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->student_m->get_rows($filter,$params);
        ////////////////////////////////////////////////////////////////////////
        $classes=$this->class_m->get_values_array('mid','title',array('campus_id'=>$this->CAMPUSID));
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['password']='';
            $this->RESPONSE['rows'][$i]['class']=$classes[$row['class_id']];
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);        
    }
////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////ROUTES FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterRoutes(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->transport_route_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->transport_route_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $filter['route_id']=$row['mid'];
            $this->RESPONSE['rows'][$i]['vehicles']=$this->transport_route_vehicle_m->get_rows($filter,'',true);
            $this->RESPONSE['rows'][$i]['passengers']=$this->transport_passenger_m->get_rows($filter,'',true);            
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addRoute(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('name');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        if($this->transport_route_m->get_rows(array('campus_id'=>$this->CAMPUSID,'name'=>$form['name']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Route already registered...';
            echo json_encode($this->RESPONSE);exit();
        }
        $form['campus_id']=$this->CAMPUSID;
        if($this->transport_route_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Route cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new route(".$form['name'].") in transport.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Route registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateRoute(){
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
        if($this->transport_route_m->save(array('name'=>$form['name']),$form['rid'])==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Route cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  
        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function loadRoute(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->transport_route_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid route...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->transport_route_m->get_by_primary($rid);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    // update row
    public function deleteRoute(){
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
        if(empty($rid)|| $this->transport_route_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid route...';
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if($this->transport_route_vehicle_m->get_rows(array('route_id'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This route contains active vehicles. Please first delete those vehicles...';
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if($this->transport_passenger_m->get_rows(array('route_id'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This route contains active passengers. Please first remove those passengers...';
            echo json_encode($this->RESPONSE);exit();
        }


        if($this->transport_route_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Route can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed route from transportation.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////VEHICLES FUNCTIONS/////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterVehicles(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('reg_no','driver');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['owner']) && !empty($form['owner']) ? $filter['owner']= $form['owner'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->transport_vehicle_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->transport_vehicle_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $filter['vehicle_id']=$row['mid'];
            $this->RESPONSE['rows'][$i]['routes']=$this->transport_route_vehicle_m->get_rows($filter,'',true);
            $this->RESPONSE['rows'][$i]['passengers']=$this->transport_passenger_m->get_rows($filter,'',true);            
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // create row
    public function addVehicle(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('reg_no','driver','capacity','owner');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        if($this->transport_vehicle_m->get_rows(array('campus_id'=>$this->CAMPUSID,'reg_no'=>$form['reg_no']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vehicle already registered...';
            echo json_encode($this->RESPONSE);exit();
        }
        $form['campus_id']=$this->CAMPUSID;
        if($this->transport_vehicle_m->add_row($form)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vehicle cannot be registered at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." registered new vehicle(".$form['reg_no'].") in transport.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Vehicle registered successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateVehicle(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','reg_no','driver','capacity','owner','contract','amount');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        if($this->transport_vehicle_m->save($form,$form['rid'])==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vehicle cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  
        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // load single row
    public function loadVehicle(){
        // get input fields into array
        $filter=array();
        $params=array();
        $filter['campus_id']=$this->CAMPUSID;
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        isset($form['rid']) ? $rid=$form['rid'] : $rid='';
        ///////////////////////////////////////////////////////////////////////////////////////////
        if(empty($rid)|| $this->transport_vehicle_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid vehicle...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->transport_vehicle_m->get_by_primary($rid);
        //send back resposne
        $this->RESPONSE['output']=$row;        
        echo json_encode($this->RESPONSE);
    }

    // update row
    public function deleteVehicle(){
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
        if(empty($rid)|| $this->transport_vehicle_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid vehicle...';
            echo json_encode($this->RESPONSE);exit();
        }
        //check for necessary data   
        if($this->transport_passenger_m->get_rows(array('vehicle_id'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This vehicle contains active passengers. Please first remove those passengers...';
            echo json_encode($this->RESPONSE);exit();
        }


        if($this->transport_vehicle_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vehicle can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed vehicle from transportation.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////ROUTE VEHICLES FUNCTIONS///////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
// filter rows
    public function filterAllRoutes(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('name');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        $this->RESPONSE['rows']=$this->transport_route_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->transport_route_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $filter['route_id']=$row['mid'];
            $this->RESPONSE['rows'][$i]['vehicles']=$this->transport_route_vehicle_m->get_rows($filter,'',true);
            $this->RESPONSE['rows'][$i]['passengers']=$this->transport_passenger_m->get_rows($filter,'',true);            
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // filter rows
    public function filterVehicleRoutes(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['vehicle_id']) && !empty($form['vehicle_id']) ? $filter['vehicle_id']= $form['vehicle_id'] : '';
        isset($form['route_id']) && !empty($form['route_id']) ? $filter['route_id']= $form['route_id'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->transport_route_vehicle_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->transport_route_vehicle_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $this->RESPONSE['rows'][$i]['route']=$this->transport_route_m->get_by_primary($row['route_id'])->name;
            $filter['route_id']=$row['route_id'];
            $this->RESPONSE['rows'][$i]['passengers']=$this->transport_passenger_m->get_rows($filter,'',true);            
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }

    // update row
    public function deleteVehicleRoute(){
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
        if(empty($rid)|| $this->transport_route_vehicle_m->get_rows(array('mid'=>$rid),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid vehicle...';
            echo json_encode($this->RESPONSE);exit();
        }
        $row=$this->transport_route_vehicle_m->get_by_primary($rid);
        //check for necessary data   
        if($this->transport_passenger_m->get_rows(array('vehicle_id'=>$row->vehicle_id,'route_id'=>$row->route_id,'campus_id'=>$this->CAMPUSID),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This vehicle contains active passengers. Please first remove those passengers...';
            echo json_encode($this->RESPONSE);exit();
        }


        if($this->transport_route_vehicle_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vehicle route can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed vehicle route from transportation.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

// filter rows
    public function filterRouteVehicles(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array();
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['route_id']) && !empty($form['route_id']) ? $filter['route_id']= $form['route_id'] : '';
        $this->RESPONSE['rows']=$this->transport_route_vehicle_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->transport_route_vehicle_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            $vehicle=$this->transport_vehicle_m->get_by_primary($row['vehicle_id']);
            $this->RESPONSE['rows'][$i]['reg_no']=$vehicle->reg_no;
            $this->RESPONSE['rows'][$i]['driver']=$vehicle->driver;
            $this->RESPONSE['rows'][$i]['vehicle_id']=$vehicle->mid;           
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
    }
    // create row
    public function addRouteVehicle(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','route_id');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        if($this->transport_route_vehicle_m->get_rows(array('campus_id'=>$this->CAMPUSID,'vehicle_id'=>$form['rid'],'route_id'=>$form['route_id']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vehicle already assigned to this route...';
            echo json_encode($this->RESPONSE);exit();
        }
        $data=array('campus_id'=>$this->CAMPUSID,'route_id'=>$form['route_id'],'vehicle_id'=>$form['rid']);
        if($this->transport_route_vehicle_m->add_row($data)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Vehicle cannot be assigned at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }  

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." assigned new route to a vehicle in transport.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']='Vehicle assigned to selected route successfully.';
        echo json_encode($this->RESPONSE);exit();
    }


/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	