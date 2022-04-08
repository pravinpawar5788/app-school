<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Manager_Controller{

/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** CONTANTS *************************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	public $CONT_ROOT; //REFERENCE TO THIS CONTROLLER
    public $DATA_RESET_PASSWORD;
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function __construct() {
        parent::__construct();
        
        //INIT CONSTANTS
        $this->CONT_ROOT=$this->LIB_CONT_ROOT.'home/';
        //load all models for this controller
        $this->DATA_RESET_PASSWORD=date('dmy');
        $models = array('class_m','class_section_m','std_fee_voucher_m','std_fee_entry_m','stf_pay_voucher_m','income_m','expense_m','accounts_m','std_attendance_m','income_m','expense_m');
        $this->load->model($models);
        
    }
	
/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** PUBLIC FUNCTIONS *****************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/	
	// default function for this controller (index)
	public function index(){
		
		$this->data['main_content']='dashboard';	
		$this->data['menu']='dashboard';			
		$this->data['sub_menu']='dashboard';
		$this->data['tab']=$this->uri->segment(4);
        $this->ANGULAR_INC[]='dashboard';

        $this->HEADER_INC[]='js/pages/dashboard.js';
        $this->FOOTER_INC[]='charts/highcharts.js';


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
            case 'list':{
                $this->data['main_content']='ems_print_chart';    
                $this->data['print_page_title']='Software Printing Chart'; 

            }
            break;
            case 'history':{
                $this->data['main_content']='ems_system_history';    
                $this->data['print_page_title']='System History'; 

            }
            break;
            
            default:{
                $this->session->set_flashdata('error', 'Please choose a valid printing option');
                redirect($this->LIB_CONT_ROOT.'home', 'refresh');                       
            }break;
        }
        if($this->session->flashdata('back_url')){$this->data['back_url']=$this->session->flashdata('back_url');}    
        $this->load->view($this->LIB_VIEW_DIR.'printing/master', $this->data);   
    }

    /////////////////////////////////////////////////////////////////////////////////////    
    /////////////////////////////////////////////////////////////////////////////////////    
    /////////////////////////////////////////////////////////////////////////////////////    
    public function maintenance(){
        if($this->user_m->is_loggedin($this->LOGIN_USER->mid, array($this->user_m->TYPE_ADMIN,$this->user_m->TYPE_MANAGER))==false){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        ?>
        <center><h1>System Maintenance</h1></center></hr>
        <table>
            <tr>
                <td>1.</td>
                <td>
                    <a href="<?php print $this->CONT_ROOT.'delcampusdata' ?>" target="_blank"> Remove Campus Data</a>
                </td>
            </tr>
            <tr>
                <td>3.</td>
                <td>
                    <a href="<?php print $this->CONT_ROOT.'resetNumbers?opt=computer' ?>" target="_blank"> Reset Computer Numbers for all students</a>
                </td>
            </tr>
            <tr>
                <td>4.</td>
                <td>
                    <a href="<?php print $this->CONT_ROOT.'resetNumbers?opt=family' ?>" target="_blank"> Reset Family Numbers for all students</a>
                </td>
            </tr>
            <tr>
                <td>5.</td>
                <td>
                    <a href="<?php print $this->CONT_ROOT.'resetNumbers?opt=admission' ?>" target="_blank"> Reset Admission Numbers for all students</a>
                </td>
            </tr>
            <tr>
                <td>6.</td>
                <td>
                    <a href="<?php print $this->CONT_ROOT.'resetBalances?cmp=self' ?>" target="_blank"> Reset Voucher Balances</a>
                </td>
            </tr>
            <tr>
                <td>6.</td>
                <td>
                    <a href="<?php print $this->CONT_ROOT.'resetRollNumbers' ?>" target="_blank"> Reset Roll Numbers</a>
                </td>
            </tr>
        </table>
        <?php
    }

    public function delcampusdata(){
        if($this->user_m->is_loggedin($this->LOGIN_USER->mid, array($this->user_m->TYPE_ADMIN,$this->user_m->TYPE_MANAGER))==false){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }

        $models=array('accounts_ledger_m','accounts_m','accounts_period_m','class_m','class_section_m','class_subject_faculty_m','class_subject_lesson_m','class_subject_m','class_subject_progress_m','class_subject_qbank_m','class_subject_test_m','class_timetable_m','exam_term_m','expense_m','income_m','lib_book_m','lib_book_issue_m','parent_m','parent_chat_m','parent_chat_detail_m','passenger_m','period_m','sms_history_m','sms_hook_m','staff_m','stationary_m','std_acheivement_m','std_advance_m','std_attendance_m','std_award_m','std_fee_concession_m','std_fee_entry_m','std_fee_history_m','std_fee_voucher_m','std_history_m','std_punishment_m','std_qual_m','std_result_m','std_subject_final_result_m','std_subject_test_result_m','std_term_result_m','stf_acheivement_m','stf_advance_m','stf_allownce_m','stf_attendance_m','stf_award_m','stf_deduction_m','stf_history_m','stf_pay_entry_m','stf_pay_history_m','stf_pay_voucher_m','stf_punishment_m','stf_qual_m','student_m','transport_passenger_m','transport_route_m','transport_route_vehicle_m');

        $this->load->model($models);
        $form=$this->input->safe_get();
        $filter=array('campus_id'=>$this->CAMPUSID);
        $del_filter=array('campus_id'=>$this->CAMPUSID);
        if(isset($form['mdl']) && !empty($form['mdl'])){
            if(isset($form['token']) && $form['token'] == $this->DATA_RESET_PASSWORD){
                $model=$form['mdl'];
                // if(isset($form['limit']) && !empty($form['limit'])){$del_filter['del_limit']=$form['limit'];}
                $this->$model->delete(NULL,$del_filter);                
                print '<br><center><code>'.$model.' data deleted for this campus!</code></center><br>';
            }else{
                print '<br><center><code>Invalid data reset token!</code></center><br>';
            }
        }
        ?>

        <center><h1>Remove Data from database for this <?php print $this->CAMPUS->name; ?></h1></center></hr>
        <center>
            <table border="1">
                <tr>
                    <th>S.No</th>
                    <th>Model / Table</th>
                    <th>Total Rows</th>
                    <th>Options</th>
                </tr>
                    <?php
                    $campuses=$this->campus_m->get_rows(array());
                    $i=0;
                    foreach($models as $mdl){
                        $total_rows=$this->$mdl->get_rows($filter,'',true);
                        ?>
                            <tr>
                                <td><?php print ++$i; ?></td>
                                <td><?php print $mdl; ?></td>
                                <td><?php print $total_rows; ?></td>
                                <td><?php print '<a href="'.$this->CONT_ROOT.'delcampusdata/?mdl='.$mdl.'&limit='.$total_rows.'" >Delete Data</a>'; ?></td>
                            </tr>
                    <?php
                    }
                    ?>
            </table>
        </center>

        <?php
    }

    public function resetNumbers(){
        if($this->user_m->is_loggedin($this->LOGIN_USER->mid, array($this->user_m->TYPE_ADMIN,$this->user_m->TYPE_MANAGER))==false){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }

        $form=$this->input->safe_get();        
        if(isset($form['token']) && $form['token'] == $this->DATA_RESET_PASSWORD){
            $i=0;
            $data=array();
            $filter=array('campus_id'=>$this->CAMPUSID);
            $students=$this->student_m->get_rows($filter,array('orderby'=>'class_id ASC'));
            if(isset($form['opt'])){
                switch (strtolower($form['opt'])) {
                    case 'computer':{                    
                        foreach($students as $std){
                            $i++;
                            $data['computer_number']=$i;
                            $this->student_m->save($data,$std['mid']);
                        }
                        $this->campus_setting_m->save_settings_array(array('last_computer_number'=>$i),$this->CAMPUSID);
                    }
                    break;
                    case 'family':{                    
                        foreach($students as $std){
                            $i++;
                            $data['family_number']=$i;
                            $this->student_m->save($data,$std['mid']);
                        }
                        $this->campus_setting_m->save_settings_array(array('last_family_number'=>$i),$this->CAMPUSID);
                    }
                    break;
                    case 'admission':{                    
                        foreach($students as $std){
                            $i++;
                            $data['admission_no']=$i;
                            $this->student_m->save($data,$std['mid']);
                        }
                        $this->campus_setting_m->save_settings_array(array('last_admission_no'=>$i),$this->CAMPUSID);
                    }
                    break;
                }
                print $form['opt'].' numbers updated for '.$i.' students'; exit();
            }else{
                print 'invalid option'; exit();
            }
        }else{
            print '<br><center><code>Invalid data reset token!</code></center><br>';
        }

    }

    //reset balances for all campus
    public function resetBalances(){
        set_time_limit(7200);
        if($this->user_m->is_loggedin($this->LOGIN_USER->mid, array($this->user_m->TYPE_ADMIN,$this->user_m->TYPE_MANAGER))==false){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }
        $form=$this->input->safe_get();
        if(isset($form['token']) && $form['token'] == $this->DATA_RESET_PASSWORD){

            $models=array('accounts_ledger_m','accounts_m','accounts_period_m','class_m','class_section_m','expense_m','income_m','sms_history_m','sms_hook_m','staff_m','std_advance_m','std_fee_concession_m','std_fee_entry_m','std_fee_history_m','std_fee_voucher_m','std_history_m','student_m');
            $this->load->model($models);

            if(isset($form['cmp']) && $form['cmp']=='all'){
                $campuses=$this->campus_m->get_rows(array());
                $i=0;
                foreach($campuses as $campus){   
                    $st=0;$i++;                 
                    // only update for this campus
                    $std_filter=array('campus_id'=>$campus['mid']); 
                    $students=$this->student_m->get_rows($std_filter,array('select'=>'mid,org_id,campus_id'));
                    foreach($students as $student){
                        $st++;
                        $this->adjustStudentBalances($student['mid'],$student['org_id'],$student['campus_id']);
                    }   
                    print '<br><center><code>account balances reset for '.$st.' students of campus '.$campus['name'].'!</code></center>'; 
                }

            }else{
                // only update for this campus
                $st=0;
                $std_filter=array('campus_id'=>$this->CAMPUSID);
                $students=$this->student_m->get_rows($std_filter,array('select'=>'mid,campus_id'));
                foreach($students as $student){
                    $st++;
                    $this->adjustStudentBalances($student['mid'],$student['campus_id']);
                }   
                print '<br><center><code>account balances reset for '.$st.' students!</code></center><br>';             
            }
        }else{
            print '<br><center><code>Invalid data reset token!</code></center><br>';
        }
    }
    // update student balances
    private function adjustStudentBalances($student_id,$campusid){
        $monthNumber=$this->std_fee_voucher_m->month_number+6;
        $last_two_years=$this->std_fee_voucher_m->year-2;
        // $ev_filter=array('student_id'=>$student_id,'status <>'=>$this->std_fee_voucher_m->STATUS_PAID,,'campus_id'=>$this->CAMPUSID,'month_number <'=>$monthNumber);
        $ev_filter=array('student_id'=>$student_id,'campus_id'=>$campusid,'month_number <'=>$monthNumber,'year >'=>$last_two_years);
        $vouchers=$this->std_fee_voucher_m->get_rows($ev_filter,array('orderby'=>'month_number ASC'));
        $total_balance=0;
        foreach($vouchers as $vchr){
            $balance=$this->std_fee_entry_m->get_voucher_amount($vchr['voucher_id'],'',$campusid);
            $total_balance+=$balance;                           
            $this->std_fee_voucher_m->save(array('balance'=>$total_balance),$vchr['mid']);
            // if($total_balance>0){}
        }
    }


    //reset balances for all campus
    public function resetRollNumbers(){
        set_time_limit(7200);
        if($this->user_m->is_loggedin($this->LOGIN_USER->mid, array($this->user_m->TYPE_ADMIN,$this->user_m->TYPE_MANAGER))==false){
            $this->session->set_flashdata('error', 'Permission Denied!');
            redirect($this->LIB_CONT_ROOT.'', 'refresh');            
        }

        $form=$this->input->safe_get();
        if(isset($form['token']) && $form['token'] == $this->DATA_RESET_PASSWORD){

            $models=array('class_m','class_section_m','student_m','session_m');
            $this->load->model($models);

            // only update for this campus
            $st=0;
            $filter=array('campus_id'=>$this->CAMPUSID);
            $classes=$this->class_m->get_rows($filter);
            $std_filter=array('campus_id'=>$this->CAMPUSID,'statis');
            foreach($classes as $cls){
                $filter['class_id']=$cls['mid'];
                $sections=$this->class_section_m->get_rows($filter);
                if(count($sections)>0){
                    foreach($sections as $sec){
                        $rollno=0;
                        $students=$this->student_m->get_session_students($cls['mid'],$sec['mid']);
                        foreach($students as $student){
                            $st++;$rollno++;
                            $this->student_m->save(array('roll_no'=>$rollno),$student['mid']);
                        } 
                    }
                }else{
                    $rollno=0;
                    $students=$this->student_m->get_session_students($cls['mid']);
                    foreach($students as $student){
                        $st++;$rollno++;
                        $this->student_m->save(array('roll_no'=>$rollno),$student['mid']);
                    } 
                }

            }
            print '<br><center><code>roll numbers updated for'.$st.' students!</code></center><br>'; 
        }else{
            print '<br><center><code>Invalid data reset token!</code></center><br>';
        }
    }
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    public function watermark_image(){
        $my_img = imagecreate( 200, 80 );
        // $background = imagecolorallocate( $my_img, 0, 0, 255 );
        imagecolorallocatealpha ( $my_img, 0, 0, 0 ,127);
        $text_colour = imagecolorallocatealpha ( $my_img, 0, 0, 0 ,10);
        // $text_colour = imagecolorallocatealpha ( $my_img, 255, 255, 0 ,127);
        // $line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
        imagestring( $my_img, 4, 30, 25, "".strtolower($this->ORG->name),$text_colour );
        // imagettftext ( $my_img, 5, 45 , 30, 25, "".$this->ORG->short_name,$text_colour );
        imagesetthickness ( $my_img, 9 );
        // imageline( $my_img, 30, 45, 165, 45, $line_colour );
        ob_start();
        imagepng( $my_img );
        $image_string = ob_get_flush();
        $imageb64 = base64_encode($image_string);
        // imagecolordeallocate( $line_color );
        imagecolordeallocate( $my_img,$text_colour );
        // imagecolordeallocate( $background );
        imagedestroy( $my_img );
        $url = "data:image/png;base64,".$imageb64;
        return $url;
    }




/** 
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
* ***************************************** END OF CLASS *********************************************************
* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

}
	