<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends Admin_Controller{

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
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'setting/';
        //load all models for this controller
        $models = array('session_m','award_m','punishment_m','stf_role_m','std_award_m','stf_award_m','std_punishment_m','stf_punishment_m','staff_m','student_m','std_group_m','concession_type_m','std_fee_concession_m');
        $this->load->model($models);
        $this->load->library('smspoint');        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////
* ***************************** PUBLIC FUNCTIONS *********************************
* ////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
		$this->data['main_content']='setting';	
		$this->data['menu']='setting';			
		$this->data['sub_menu']='setting';
        $this->data['tab']=$tab;
        $this->ANGULAR_INC[]='setting';

        /////////////////////////////////////////////////////////////
		$this->load->view($this->LIB_VIEW_DIR.'master', $this->data);	
	}

     //save profile pic
    public function upload_picture(){
        //upload artwork file
        if($this->IS_DEMO){
            $this->session->set_flashdata('error', $this->config->item('app_demo_edit_err'));
            redirect($this->LIB_CONT_ROOT.'setting', 'refresh');                    
        }
        $file_name='logo_'.mt_rand(1001,9999);
        $path='./uploads/images/logo';
        $data=$this->upload_img('file',$file_name,$path);
        if($data['file_uploaded']==FALSE){
            $this->session->set_flashdata('error', $data['file_error']);
            redirect($this->LIB_CONT_ROOT.'setting', 'refresh');
        }
        $nfile_name=$data['file_name'];
        $this->system_setting_m->save_setting($this->system_setting_m->_ORG_LOGO,$nfile_name);
        $this->session->set_flashdata('success', 'Image uploaded successfully.');
        redirect($this->LIB_CONT_ROOT.'setting', 'refresh');           
    
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
        $rid=$this->campus_m->add_row($form);
        if($rid===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Process stopped. Please try again later!';
            echo json_encode($this->RESPONSE);exit();            
        }
        $this->user_m->add_row(array('campus_id'=>$rid,'name'=>$form['mname'],'mobile'=>$form['mobile'],'password'=>$form['password'],'type'=>$this->user_m->TYPE_MANAGER));


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

    
    /////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////MODULE FUNCTIONS///////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterModules(){
            // get input fields into array
            $filter=array();
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
            $this->RESPONSE['rows']=$this->module_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->module_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            $i=0;
            foreach($this->RESPONSE['rows'] as $row){
                $this->RESPONSE['rows'][$i]['module_name']=$this->module_m->get_module_name($row['name']);
                $i++;
            }
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addModule(){
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

            if($this->module_m->get_rows(array('name'=>$form['name']),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Module already subscribed...';
                echo json_encode($this->RESPONSE);exit();
            } 
            $module=array('name'=>$form['name'] );
            //save data in database                
            if($this->module_m->add_row($module)===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Module cannot be subscribed at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 
            ///////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Module subscribed successfully. Controls are now visible in campus';
            echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateModule(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid');
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
        //save data in database                
        if($this->module_m->save(array('monthly_charges'=>floatval($form['monthly_charges'])),$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //send back the resposne
        $this->RESPONSE['message']='Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // remove row
    public function deleteModule(){
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
            if(empty($rid)|| $this->module_m->get_rows(array('mid'=>$rid),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid module...';
                echo json_encode($this->RESPONSE);exit();
            }

            $row=$this->module_m->get_by_primary($rid);
            if($this->module_m->delete($rid)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Module can not be removed at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }
            //send back the resposne
            $this->RESPONSE['message']='Module unsubscribed successfully.';  
            echo json_encode($this->RESPONSE);exit();
    }

    // edit row
    public function changeModuleStatus(){ 
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
            if(empty($form['rid'])||$this->module_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid module...';
                echo json_encode($this->RESPONSE);exit();
            }

            $row=$this->module_m->get_by_primary($form['rid']);
            //save data in database
            $data=array('status'=>$form['status']);
            if($this->module_m->save($data,$form['rid'])===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Status can not be updated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }

            //send back repsonse
            $this->RESPONSE['message']='Status Updated Successfully...';
            echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////AWARDS FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterAwards(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title','template');
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
            $this->RESPONSE['rows']=$this->award_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->award_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addAward(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('title','template');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   

            //save data in database                   
            if($this->award_m->add_row($form)===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Award cannot be registered at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 
            ///////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Award registered successfully.';
            echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateAward(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','template');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database                
        if($this->award_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Award cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 


        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // remove row
    public function deleteAward(){
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
            if(empty($rid)|| $this->award_m->get_rows(array('mid'=>$rid),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid award...';
                echo json_encode($this->RESPONSE);exit();
            }
            $row=$this->award_m->get_by_primary($rid);

            //check for necessary data   
            if($this->std_award_m->get_rows(array('award_id'=>$rid),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='This award is issued to one or more students. Please first remove awards from member profile...';
                echo json_encode($this->RESPONSE);exit();
            }  
            if($this->stf_award_m->get_rows(array('award_id'=>$rid),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='This award is issued to one or more staff. Please first remove awards from member profile...';
                echo json_encode($this->RESPONSE);exit();
            }
            if($this->award_m->delete($rid)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Award can not be terminated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }
            //send back the resposne
            $this->RESPONSE['message']='Award removed successfully.';  
            echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////PUNISHMENT FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterPunishment(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title','template');
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
            $this->RESPONSE['rows']=$this->punishment_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->punishment_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addPunishment(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('title','template');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   

            //save data in database              
            if($this->punishment_m->add_row($form)===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Notice cannot be registered at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 
            ///////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Notice registered successfully.';
            echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updatePunishment(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title','template');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        //save data in database                
        if($this->punishment_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Notice cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 


        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // remove row
    public function deletePunishment(){
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
            if(empty($rid)|| $this->punishment_m->get_rows(array('mid'=>$rid),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid notice...';
                echo json_encode($this->RESPONSE);exit();
            }
            $row=$this->punishment_m->get_by_primary($rid);
            //check for necessary data   
            if($this->std_punishment_m->get_rows(array('doc_id'=>$rid),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='This notice is issued to one or more students. Please first remove awards from member profile...';
                echo json_encode($this->RESPONSE);exit();
            }  
            if($this->stf_punishment_m->get_rows(array('doc_id'=>$rid),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='This notice is issued to one or more staff. Please first remove awards from member profile...';
                echo json_encode($this->RESPONSE);exit();
            }

            if($this->punishment_m->delete($rid)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Notice can not be terminated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }
            //send back the resposne
            $this->RESPONSE['message']='Notice removed successfully.';  
            echo json_encode($this->RESPONSE);exit();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////ROLE FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterRoles(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title');
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
            $this->RESPONSE['rows']=$this->stf_role_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->stf_role_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addRole(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('title');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            if($this->stf_role_m->get_rows(array('title'=>$form['title']),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Role already exist...';
                echo json_encode($this->RESPONSE);exit();
            } 


            //save data in database             
            if($this->stf_role_m->add_row($form)===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Role cannot be registered at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 
            ///////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Role registered successfully.';
            echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateRole(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->stf_role_m->get_by_primary($form['rid']);
        if(strtolower($row->title) == 'teacher'){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Role of teacher is by default and cannot be updated...';
            echo json_encode($this->RESPONSE);exit();
        }
        if($this->stf_role_m->get_rows(array('title'=>$form['title'],'mid <>'=>$form["rid"]),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Role already exist...';
            echo json_encode($this->RESPONSE);exit();
        } 
        //save data in database                
        if($this->stf_role_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Role cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 


        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // remove row
    public function deleteRole(){
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
            if(empty($rid)|| $this->stf_role_m->get_rows(array('mid'=>$rid),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid record...';
                echo json_encode($this->RESPONSE);exit();
            }
            $row=$this->stf_role_m->get_by_primary($rid);
            if(strtolower($row->title) == 'teacher'){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Role of teacher is by default and cannot be removed...';
                echo json_encode($this->RESPONSE);exit();
            }
            if($this->staff_m->get_rows(array('role_id'=>$rid),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='This role is assigned to one or more staff. Please first remove role from staff...';
                echo json_encode($this->RESPONSE);exit();
            }

            if($this->stf_role_m->delete($rid)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Role can not be terminated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }
            //send back the resposne
            $this->RESPONSE['message']='Role removed successfully.';  
            echo json_encode($this->RESPONSE);exit();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////SESSION FUNCTIONS/////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterSessions(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title');
            $like=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';  
            ///////////////////////////////////////////////////////////////////////////////////////////
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['orderby']='year DESC, title ASC';
            isset($form['search']) ? $params['like']=$like : '';
            $this->RESPONSE['rows']=$this->session_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->session_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addSession(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('title','year');
            foreach ($required as $key){
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
            if($this->session_m->get_rows(array('title'=>$form['title']),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Session already exist...';
                echo json_encode($this->RESPONSE);exit();
            } 

            if($this->session_m->get_rows(array('status'=>$this->session_m->STATUS_UPCOMING),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='An upcoming session already exist...';
                echo json_encode($this->RESPONSE);exit();
            }

            //save data in database              
            if($this->session_m->add_row($form)===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Session cannot be registered at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 
            ///////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Session registered successfully.';
            echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateSession(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->session_m->get_by_primary($form['rid']);
        if($this->session_m->get_rows(array('title'=>$form['title'],'mid <>'=>$form["rid"]),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Session already exist...';
            echo json_encode($this->RESPONSE);exit();
        } 
        //save data in database                
        if($this->session_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Session cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 


        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // remove row
    public function deleteSession(){
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
            if(empty($rid)|| $this->session_m->get_rows(array('mid'=>$rid),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid record...';
                echo json_encode($this->RESPONSE);exit();
            }
            // $this->load->model($this->EMS_MODELS);
            $row=$this->session_m->get_by_primary($rid);
            if($row->status != $this->session_m->STATUS_UPCOMING){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Only upcomming session can be removed...';
                echo json_encode($this->RESPONSE);exit();
            }

            if($this->session_m->delete($rid)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Session can not be terminated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }
            //send back the resposne
            $this->RESPONSE['message']='Session terminated successfully.';  
            echo json_encode($this->RESPONSE);exit();
    }

    // edit row
    public function changeSessionStatus(){ 
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
            if(empty($form['rid'])||$this->session_m->get_rows(array('mid'=>$form['rid']),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid session...';
                echo json_encode($this->RESPONSE);exit();
            }

            $row=$this->session_m->get_by_primary($form['rid']);
            if($row->status == $this->session_m->STATUS_PASSED){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Passed Session can not be updated...';
                echo json_encode($this->RESPONSE);exit();
            }
            if($form['status']==$this->session_m->STATUS_ACTIVE && $this->session_m->get_rows(array('status'=>$this->session_m->STATUS_ACTIVE),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='An active session is already available. Please first pass the active session...';
                echo json_encode($this->RESPONSE);exit();
            }

            //save data in database
            $data=array('status'=>$form['status']);
            if($form['status']==$this->session_m->STATUS_ACTIVE){$data['start_date']=$this->session_m->date;}
            if($form['status']==$this->session_m->STATUS_PASSED){$data['end_date']=$this->session_m->date;}
            if($this->session_m->save($data,$form['rid'])===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Status can not be updated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }

            //send back repsonse
            $this->RESPONSE['message']='Status Updated Successfully...';
            echo json_encode($this->RESPONSE);exit();
    }
	
	
	
	
    ////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////CONCESSION FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterOrgConcessions(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title');
            $like=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array();
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            ///////////////////////////////////////////////////////////////////////////////////////////
            //isset($form['rid']) && !empty($form['rid']) ? $filter['org_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->concession_type_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->concession_type_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $campuses=$this->campus_m->get_values_array('mid','name',array('user_id'=>$this->LOGIN_USER->mid,'org_id'=>$form['rid']));
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     if($row['campus_id']>0){$this->RESPONSE['rows'][$i]['campus']=$campuses[$row['campus_id']];}
            //     $i++;
            // }
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addOrgConcession(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('title');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            if($this->concession_type_m->get_rows(array('title'=>$form['title']),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Concession already exist...';
                echo json_encode($this->RESPONSE);exit();
            } 


            //save data in database               
            if($this->concession_type_m->add_row($form)===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Cannot be registered at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 
            //Log the user Activity
            // $msg=$this->LOGIN_USER->name." registered new Concession Type(".$form['title'].").";
            // $this->user_history_m->add_row(array('info'=>$msg,'user_id'=>$this->LOGIN_USER->mid));
            ///////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Concession registered successfully.';
            echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateOrgConcession(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->concession_type_m->get_by_primary($form['rid']);
        if($this->concession_type_m->get_rows(array('title'=>$form['title'],'mid <>'=>$form["rid"]),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Already exist...';
            echo json_encode($this->RESPONSE);exit();
        } 
        //save data in database                
        if($this->concession_type_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        // $msg=$this->LOGIN_USER->name." updated Concession Type(".$form['title'].").";
        // $this->user_history_m->add_row(array('info'=>$msg,'user_id'=>$this->LOGIN_USER->mid));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // remove row
    public function deleteOrgConcession(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            isset($form['rid']) ? $rid=$form['rid'] : $rid='';
            //check for necessary data   
            if(empty($rid)|| $this->concession_type_m->get_rows(array('mid'=>$rid),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid record...';
                echo json_encode($this->RESPONSE);exit();
            }
            // $this->load->model($this->EMS_MODELS);
            $row=$this->concession_type_m->get_by_primary($rid);
            if($this->std_fee_concession_m->get_rows(array('type_id'=>$rid),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='This concession is assigned to one or more students. Please first remove concession from students...';
                echo json_encode($this->RESPONSE);exit();
            }

            if($this->concession_type_m->delete($rid)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Can not be terminated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }
            //Log the user Activity
            // $msg=$this->LOGIN_USER->name." removed Concession Type(".$row->title.").";
            // $this->user_history_m->add_row(array('info'=>$msg,'user_id'=>$this->LOGIN_USER->mid));
            //send back the resposne
            $this->RESPONSE['message']='Concession removed successfully.';  
            echo json_encode($this->RESPONSE);exit();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////GROUP FUNCTIONS//////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    // filter rows
    public function filterOrgStdGroups(){
            // get input fields into array
            $filter=array();
            $params=array();
            $search=array('title');
            $like=array();
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_get();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array();
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            ///////////////////////////////////////////////////////////////////////////////////////////
            //isset($form['rid']) && !empty($form['rid']) ? $filter['org_id']= $form['rid'] : '';
            if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
            ///////////////////////////////////////////
            $params['limit']=$this->PAGE_LIMIT;
            isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
            isset($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
            isset($form['search']) ? $params['like']=$like : '';
            // $params['select']='';
            // $params['distinct']=FALSE;
            $this->RESPONSE['rows']=$this->std_group_m->get_rows($filter,$params);
            $this->RESPONSE['total_rows']=$this->std_group_m->get_rows($filter,'',true);
            ////////////////////////////////////////////////////////////////////////
            // $campuses=$this->campus_m->get_values_array('mid','name',array('user_id'=>$this->LOGIN_USER->mid,'org_id'=>$form['rid']));
            // $i=0;
            // foreach($this->RESPONSE['rows'] as $row){
            //     if($row['campus_id']>0){$this->RESPONSE['rows'][$i]['campus']=$campuses[$row['campus_id']];}
            //     $i++;
            // }
            ////////////////////////////////////////////////////////////////////////  
            echo json_encode( $this->RESPONSE);
            
    }
    // create row
    public function addOrgStdGroup(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            //check for necessary required data   
            $required=array('title');
            foreach ($required as $key) {
                if(!isset($form[$key]) || empty($form[$key])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please provide required information ...';
                echo json_encode($this->RESPONSE);exit();
                }
            }   
            if($this->std_group_m->get_rows(array('title'=>$form['title']),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Record already exist...';
                echo json_encode($this->RESPONSE);exit();
            } 


            //save data in database               
            if($this->std_group_m->add_row($form)===false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Cannot be registered at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            } 
            //Log the user Activity
            // $msg=$this->LOGIN_USER->name." registered new Concession Type(".$form['title'].").";
            // $this->user_history_m->add_row(array('info'=>$msg,'user_id'=>$this->LOGIN_USER->mid));
            ///////////////////////////////////////////////////////////
            $this->RESPONSE['message']='Registered successfully.';
            echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateOrgStdGroup(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','title');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        } 
        $row=$this->std_group_m->get_by_primary($form['rid']);
        if($this->std_group_m->get_rows(array('title'=>$form['title'],'mid <>'=>$form["rid"]),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Already exist...';
            echo json_encode($this->RESPONSE);exit();
        } 
        //save data in database                
        if($this->std_group_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Cannot be updated at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        // $msg=$this->LOGIN_USER->name." updated Concession Type(".$form['title'].").";
        // $this->user_history_m->add_row(array('info'=>$msg,'user_id'=>$this->LOGIN_USER->mid));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }
    // remove row
    public function deleteOrgStdGroup(){
            // get input fields into array       
            $this->RESPONSE['error']=FALSE;
            $form=$this->input->safe_post();
            ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
            isset($form['rid']) ? $rid=$form['rid'] : $rid='';
            //check for necessary data   
            if(empty($rid)|| $this->std_group_m->get_rows(array('mid'=>$rid),'',true)<1){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Please choose a valid record...';
                echo json_encode($this->RESPONSE);exit();
            }
            // $this->load->model($this->EMS_MODELS);
            $row=$this->std_group_m->get_by_primary($rid);
            if($this->student_m->get_rows(array('group_id'=>$rid),'',true)>0){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='This group is assigned to one or more students. Please first remove this from students...';
                echo json_encode($this->RESPONSE);exit();
            }

            if($this->std_group_m->delete($rid)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Can not be terminated at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();
            }
            //Log the user Activity
            // $msg=$this->LOGIN_USER->name." removed Concession Type(".$row->title.").";
            // $this->user_history_m->add_row(array('info'=>$msg,'user_id'=>$this->LOGIN_USER->mid));
            //send back the resposne
            $this->RESPONSE['message']='Record removed successfully.';  
            echo json_encode($this->RESPONSE);exit();
    }


    /////////////////////////////////////////////////////////////////////////////////////
    //////////////////SETTINGS FUNCTIONS//////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////

    // load single row
    public function loadSettings(){
        // get input fields into array
        $filter=array();
        $params=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';        
        //send back resposne
        $this->RESPONSE['output']=$this->SETTINGS;        
        echo json_encode($this->RESPONSE);
    }
    // update row
    public function updateGeneralSettings(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('currency_symbol');
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
                     
        $this->system_setting_m->save_settings_array($form);
        //send back the resposne
        $this->RESPONSE['message']='Settings Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    // update row
    public function updateSmsSending(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //////////////////////////////////////////////////////////////////////////
        
        //check for demo   
        if($this->IS_DEMO){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']=$this->config->item('app_demo_del_err');
            echo json_encode($this->RESPONSE);exit();
        }
        if(intval($form['sms_sending'])>0){

            if($this->smspoint->is_valid_api($this->SETTINGS[$this->system_setting_m->_SMS_VENDOR],$this->SETTINGS[$this->system_setting_m->_SMS_API_USERNAME],$this->SETTINGS[$this->system_setting_m->_SMS_API_KEY])==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Invalid credentials. Please update credential before enabling sms sending...';
                echo json_encode($this->RESPONSE);exit();                
            }
            if($this->SETTINGS[$this->system_setting_m->_SMS_TYPE]=='brand' && !$this->smspoint->is_valid_mask($this->SETTINGS[$this->system_setting_m->_SMS_VENDOR],$this->SETTINGS[$this->system_setting_m->_SMS_API_USERNAME],$this->SETTINGS[$this->system_setting_m->_SMS_API_KEY],$this->SETTINGS[$this->system_setting_m->_SMS_MASK])){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Invalid Masking or Sender ID. Please update credential befor enabling sms sending...';
                echo json_encode($this->RESPONSE);exit();                
            }

        }
        //save data in database
        $this->system_setting_m->save_settings_array($form);

        //send back the resposne
        $this->RESPONSE['message']='Settings Saved Successfully. Please refresh the page to continue...';  
        echo json_encode($this->RESPONSE);exit();
    }



/** 
* /////////////////////////////////////////////////////////////////////////////////////
* ************************** END OF CLASS *********************************************
* /////////////////////////////////////////////////////////////////////////////////////
*/

}
	