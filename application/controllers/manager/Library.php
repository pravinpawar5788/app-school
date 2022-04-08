<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Library extends Manager_Controller{

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
        if($this->LOGIN_USER->prm_library<1){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'library/';
        //load all models for this controller
        $models = array('lib_book_m','lib_book_issue_m','class_m','student_m','staff_m','std_history_m','std_fee_history_m','std_fee_entry_m','std_fee_voucher_m','stf_history_m','stf_deduction_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index($tab=''){
        //redirect to items page
        redirect($this->CONT_ROOT.'items', 'refresh');
		
	}
    // stationary items
    public function items($tab=''){
        
        $this->data['main_content']='library';   
        $this->data['menu']='library';           
        $this->data['sub_menu']='library';
        $this->data['tab']=$tab;

        $this->ANGULAR_INC[]='library';
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
                $this->data['main_content']='library_book_list';    
                $this->data['print_page_title']='Libarary Books List'; 

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
        $search=array('name','sub_title','author','sub_author','publisher','isbn','accession_number','ddc_number','placement_number','year');
        $like=array();
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_get();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        ///////////////////////////////////////////////////////////////////////////////////////////
        isset($form['type']) && !empty($form['type']) ? $filter['catagory']= $form['type'] : '';
        isset($form['year']) && !empty($form['year']) ? $filter['year']= $form['year'] : '';
        isset($form['stock']) && !empty($form['stock']) ? $filter['stock >']= $form['stock'] : '';
        isset($form['filter']) && !empty($form['filter']) ? $filter[$form['filter']]= $form['search'] : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby'])&&!empty($form['sortby']) ? $params['orderby']=$form['sortby'] : $params['sortby']='accession_number ASC,ddc_number ASC';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->lib_book_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->lib_book_m->get_rows($filter,'',true);
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
        $required=array('name','catagory');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        if(isset($form['accession_number']) && !empty($form['accession_number']) && $this->lib_book_m->get_rows(array('campus_id'=>$this->CAMPUSID,'accession_number'=>$form['accession_number']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This accession number is already in use. Please provide another one...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if(isset($form['placement_number']) && !empty($form['placement_number']) && $this->lib_book_m->get_rows(array('campus_id'=>$this->CAMPUSID,'placement_number'=>$form['placement_number']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This placement number is assigned to another book. Please provide another one...';
            echo json_encode($this->RESPONSE);exit();            
        }
        $this->RESPONSE['message']=' Book saved in library system successfully.';
        $accessions=string_to_array($form['accession_number'],',');
        $form['campus_id']=$this->CAMPUSID;
        if(count($accessions)>1){
            $total=0;
            //there are mulitple access numbers with same book information
            foreach ($accessions as $number){
                if($this->lib_book_m->get_rows(array('campus_id'=>$this->CAMPUSID,'accession_number'=>$number),'',true)<1){
                    $total++;
                    $form['accession_number']=$number;
                    $this->lib_book_m->add_row($form);                    
                }
            }
            $this->RESPONSE['message']=$total.' Books saved in library system successfully.';
        }else{
            //there is only one book to save in library   
            if($this->lib_book_m->add_row($form)==false){
                $this->RESPONSE['error']=TRUE;
                $this->RESPONSE['message']='Book cannot be saved at this time. Please try again later...';
                echo json_encode($this->RESPONSE);exit();            
            }            
        }

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." saved new library book (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

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
        $required=array('rid','name','catagory');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }  
        if(isset($form['accession_number']) && $this->lib_book_m->get_rows(array('campus_id'=>$this->CAMPUSID,'accession_number'=>$form['accession_number'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This accession number is already in use. Please provide another one...';
            echo json_encode($this->RESPONSE);exit();            
        }
        if(isset($form['placement_number']) && $this->lib_book_m->get_rows(array('campus_id'=>$this->CAMPUSID,'placement_number'=>$form['placement_number'],'mid <>'=>$form['rid']),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This placement number is assigned to another book. Please provide another one...';
            echo json_encode($this->RESPONSE);exit();            
        }


        //save data in database                
        if($this->lib_book_m->save($form,$form['rid'])===false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Book cannot be saved at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        } 

        //Log the user Activity
        $msg=$this->LOGIN_USER->name." updated library book (".$form['name'].").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']=' Data Saved Successfully.';  
        echo json_encode($this->RESPONSE);exit();
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
        if(empty($rid)|| $this->lib_book_m->get_rows(array('mid'=>$rid,'campus_id'=>$this->CAMPUSID),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please choose a valid item...';
            echo json_encode($this->RESPONSE);exit();
        }

        $row=$this->lib_book_m->get_by_primary($rid);
        if($this->lib_book_m->delete($rid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Item can not be removed at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();
        }
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." removed library book (".$row->name.") with stock quantity(".$row->stock.").";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne
        $this->RESPONSE['message']='Item Removed Successfully.';  
        echo json_encode($this->RESPONSE);exit();
    }

    ////////////////////////////////////////////////////////////

    // create row
    public function issueStudentItem(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','item','days');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        if($this->lib_book_issue_m->get_rows(array('campus_id'=>$this->CAMPUSID,'user_id'=>$form['rid'],'book_id'=>$form['item'],'user_type'=>$this->lib_book_issue_m->USER_STUDENT),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This book is already issued to this member...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //by default issue for 15 days
        if(isset($form['days'])&&empty($form['days'])){$form['days']=15;} 
        $item=$this->lib_book_m->get_by_primary($form['item']);
        $user=$this->student_m->get_by_primary($form['rid']);
        $due_jd=$this->lib_book_m->todayjd+$form['days'];
        $data=array('campus_id'=>$this->CAMPUSID,'user_id'=>$user->mid,'book_id'=>$item->mid,'user'=>$user->name,'book'=>$item->name,'catagory'=>$item->catagory,'user_type'=>$this->lib_book_issue_m->USER_STUDENT,'due_jd'=>$due_jd,'due_date'=>get_future_date($form['days']));
        if($this->lib_book_issue_m->add_row($data)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Book cannot be issued at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }

        //add to student history
        $history=array('campus_id'=>$this->CAMPUSID,'student_id'=>$user->mid,'title'=>'Get Library Book('.$item->name.') for '.$form['days'].' days');
        $history['description']='Get Library Book '.$item->name.' for ('.$form['days'].') days';
        $this->std_history_m->add_row($history);
            
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." issued library book (".$item->name.") to student.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Book issued successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function issueStaffItem(){
        // get input fields into array       
        $this->RESPONSE['error']=FALSE;
        $form=$this->input->safe_post();
        ENVIRONMENT !== 'production' ? $this->RESPONSE['request']=$form: '';
        //check for necessary required data   
        $required=array('rid','item','days');
        foreach ($required as $key) {
            if(!isset($form[$key]) || empty($form[$key])){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Please provide required information ...';
            echo json_encode($this->RESPONSE);exit();
            }
        }
        if($this->lib_book_issue_m->get_rows(array('campus_id'=>$this->CAMPUSID,'user_id'=>$form['rid'],'book_id'=>$form['item'],'user_type'=>$this->lib_book_issue_m->USER_STAFF),'',true)>0){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This book is already issued to this member...';
            echo json_encode($this->RESPONSE);exit();            
        }
        //by default issue for 15 days
        if(isset($form['days'])&&empty($form['days'])){$form['days']=15;} 
        $item=$this->lib_book_m->get_by_primary($form['item']);
        $user=$this->staff_m->get_by_primary($form['rid']);
        $due_jd=$this->lib_book_m->todayjd+$form['days'];
        $data=array('campus_id'=>$this->CAMPUSID,'user_id'=>$user->mid,'book_id'=>$item->mid,'user'=>$user->name,'book'=>$item->name,'catagory'=>$item->catagory,'user_type'=>$this->lib_book_issue_m->USER_STAFF,'due_jd'=>$due_jd,'due_date'=>get_future_date($form['days']));
        if($this->lib_book_issue_m->add_row($data)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Book cannot be issued at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }

        //add to student history
        $history=array('campus_id'=>$this->CAMPUSID,'staff_id'=>$user->mid,'title'=>'Get Library Book('.$item->name.') for '.$form['days'].' days');
        $history['description']='Get Library Book'.$item->name.' for ('.$form['days'].') days';
        $this->stf_history_m->add_row($history);
            
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." issued library book (".$item->name.") to staff.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Book issued successfully.';
        echo json_encode($this->RESPONSE);exit();
    }

    // create row
    public function receiveBook(){
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
        if($this->lib_book_issue_m->get_rows(array('campus_id'=>$this->CAMPUSID,'mid'=>$form['rid']),'',true)<1){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='This book is not issued by the system. You may have issued this manually...';
            echo json_encode($this->RESPONSE);exit();            
        }
        $item=$this->lib_book_issue_m->get_by_primary($form['rid']);
        if($item->user_type==$this->lib_book_issue_m->USER_STAFF){
            $user=$this->staff_m->get_by_primary($item->user_id);
        }elseif($item->user_type==$this->lib_book_issue_m->USER_STUDENT){
            $user=$this->student_m->get_by_primary($item->user_id);
        }
        //if user is returning the book after due date then fine him/her
        if($item->due_jd < $this->lib_book_issue_m->todayjd){
            $late_days=$this->lib_book_issue_m->todayjd-$item->due_jd;
        }
        if($this->lib_book_issue_m->delete($item->mid)==false){
            $this->RESPONSE['error']=TRUE;
            $this->RESPONSE['message']='Book cannot be received at this time. Please try again later...';
            echo json_encode($this->RESPONSE);exit();            
        }

        //update stock
        $this->lib_book_m->update_column_value($item->mid,'stock',1); 
        //Log the user Activity
        $msg=$this->LOGIN_USER->name." received library book (".$item->book.") from member.";
        $this->system_history_m->add_row(array('message'=>$msg,'user_id'=>$this->LOGIN_USER->mid,'campus_id'=>$this->CAMPUSID));

        //send back the resposne  
        $this->RESPONSE['message']=' Book received successfully.';
        echo json_encode($this->RESPONSE);exit();
    }
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
/////////////////////////////HISTORY FUNCTIONS//////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

// filter rows
public function filterHistory(){
        // get input fields into array
        $filter=array('campus_id'=>$this->CAMPUSID);
        $params=array();
        $search=array('book','user','date');
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
        isset($form['type']) && !empty($form['type']) ? $filter['catagory']= $form['type'] : '';
        isset($form['late']) && $form['late']=='true' ? $filter['due_jd <']= $this->lib_book_m->todayjd : '';
        if(isset($form['search'])){foreach($search as $value){$like[$value]=$form['search'];} }
        ///////////////////////////////////////////
        $params['limit']=$this->PAGE_LIMIT;
        isset($form['page']) ? $params['offset']=intval($form['page'])*$this->PAGE_LIMIT : '';
        isset($form['sortby']) && !empty($form['sortby']) ? $params['orderby']=$form['sortby'] : '';
        isset($form['search']) ? $params['like']=$like : '';
        $this->RESPONSE['rows']=$this->lib_book_issue_m->get_rows($filter,$params);
        $this->RESPONSE['total_rows']=$this->lib_book_issue_m->get_rows($filter,'',true);
        ////////////////////////////////////////////////////////////////////////
        $i=0;
        foreach($this->RESPONSE['rows'] as $row){
            if($row['user_type']==$this->lib_book_issue_m->USER_STAFF){
                $user=$this->staff_m->get_by_primary($row['user_id']);
                $this->RESPONSE['rows'][$i]['user_image']=$user->image;               
                $this->RESPONSE['rows'][$i]['user_pid']=$user->staff_id;               
            }elseif($row['user_type']==$this->lib_book_issue_m->USER_STUDENT){
                $user=$this->student_m->get_by_primary($row['user_id']);
                $this->RESPONSE['rows'][$i]['user_image']=$user->image;                
                $this->RESPONSE['rows'][$i]['user_pid']=$user->student_id;               
            }
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////
        echo json_encode( $this->RESPONSE);
        
}

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	