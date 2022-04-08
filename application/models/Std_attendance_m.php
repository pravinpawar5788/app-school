<?php


class Std_attendance_M extends MY_Model
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    protected $_model_name='Std_attendance_M.php';
	protected $_table_name = 'std_attendance';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        public $DB_COL_ARRAY=array();
        public $LABEL_PRESENT='P';              //if PRESENT
        public $LABEL_ABSENT='A';               //if ABSENT WITHOUT LEAVE
        public $LABEL_LEAVE='L';                //if ON LEAVE
        public $LABEL_HOLIDAY='H';              //if HOLIDAY


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
return true;   
  
}
//ADD NEW TABLE ROW IN DATABASE 
public function add_row($vals){
    //GET ALL THE FIELDS IN ARRAY
    $db_row=  $this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['student_id'])||empty($data['year'])||empty($data['month'])){
        return false;        
    }
    
    
    
    ///////////// data is valid to insert or update
    return true;
    
}	    

//////////////////////////////////////////////////////////////////////////////////
/////////////////////GETTER FUNCTIONS ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

//GET ATTENDANCE DATA
public function get_attendance_report($campus_id='',$student_id='',$type='session',$session_id='',$strict_session=true){
    $data=array();
    $this->load->model(array('student_m','session_m'));
    if(empty($session_id)){
        $session=$this->session_m->getActiveSession();        
    }else{
        $session=$this->session_m->get_by_primary($session_id); 
    }
    $std_filter=array();
    if(!empty($campus_id)){$std_filter['campus_id']=$campus_id;}
    if(!empty($student_id)){$std_filter['mid']=$student_id;}
    if(empty($session_id) && $strict_session ){$session_id=$session->mid;}
    if(!empty($session_id)){$std_filter['session_id']=$session_id;}
    $students=$this->student_m->get_rows($std_filter);
    /////////////////////////////////////////////////////////
    $filter=array('session_id'=>$session->mid);
    if(!empty($campus_id)){$filter['campus_id']=$campus_id;}
    $this_month=$this->month;
    $this_year=$this->year;
    if(empty($year)){$target_year=$this->year;}else{$target_year=intval($year);}
    if(count($students)>0){        
        foreach ($students as $student){
            $filter['student_id']=$student['mid'];
            $monthly_report=array();
            $total_days=0;$total_present=0;$total_absent=0;$total_leave=0;$total_holidays=0;$total_others=0;
            switch (strtolower($type)) {
                case 'yearly':{
                    $month=$this_month;$year=$this_year;$month_limit=$this_month;
                    for($i=0;$i<$month_limit;$i++){
                        $month_days=0;$month_present=0;$month_absent=0;$month_leave=0;$month_holidays=0;$month_others=0;
                        $month_days=days_in_month($month,$year);
                        $total_days+=$month_days;
                        $filter['month']=$month;
                        $filter['year']=$year;
                        $student_row=$this->get_by($filter,true);
                        //loop for full month
                        for($j=0;$j<31;$j++){
                                $day=$j+1;
                                $col_num='d'.$day;
                                if(!empty($student_row->$col_num)){
                                    $key=$student_row->$col_num;
                                    if($key==$this->std_attendance_m->LABEL_PRESENT){$total_present++;$month_present++;}
                                    elseif($key==$this->std_attendance_m->LABEL_ABSENT){$total_absent++;$month_absent++;}
                                    elseif($key==$this->std_attendance_m->LABEL_LEAVE){$total_leave++;$month_leave++;}
                                    elseif($key==$this->std_attendance_m->LABEL_HOLIDAY){$total_holidays++;$month_holidays++;}
                                }
                        }
                        ////////////// decrement month////////
                        $month_others=$month_days-($month_holidays+$month_present+$month_absent+$month_leave);
                        $monthly_report[$month]=array('month'=>$month,'year'=>$year,'total_days'=>$month_days,'present'=>$month_present,'absent'=>$month_absent,'leave'=>$month_leave,'holidays'=>$month_holidays,'others'=>$month_others);
                        $month--;
                        if($month<1){$year--;$month=12;}
                    }
                    $total_others=$total_days-($total_holidays+$total_present+$total_absent+$total_leave);
                    //save to report
                    $data[$student['mid']]=array('total_report'=>array('total_days'=>$total_days,'present'=>$total_present,'absent'=>$total_absent,'leave'=>$total_leave,'holidays'=>$total_holidays,'others'=>$total_others),'monthly_report'=>$monthly_report);

                }break;
                
                default:{
                    $start_month=intval(get_month_from_date($session->start_date,'-',true));
                    $end_month=intval(get_month_from_date($session->end_date,'-',true));
                    if($end_month<1){$end_month=$this->month;}
                    $year=intval(get_year_from_date($session->start_date));
                    $month=$start_month;

                    for($i=$start_month;$i<=$end_month;$i++){
                        $month_days=0;$month_present=0;$month_absent=0;$month_leave=0;$month_holidays=0;$month_others=0;
                        $month_days=days_in_month($month,$year);
                        $total_days+=$month_days;
                        $filter['month']=$month;
                        $filter['year']=$year;
                        $student_row=$this->get_by($filter,true);
                        //loop for full month
                        for($j=0;$j<31;$j++){
                                $day=$j+1;
                                $col_num='d'.$day;
                                if(!empty($student_row->$col_num)){
                                    $key=$student_row->$col_num;
                                    if($key==$this->std_attendance_m->LABEL_PRESENT){$total_present++;$month_present++;}
                                    elseif($key==$this->std_attendance_m->LABEL_ABSENT){$total_absent++;$month_absent++;}
                                    elseif($key==$this->std_attendance_m->LABEL_LEAVE){$total_leave++;$month_leave++;}
                                    elseif($key==$this->std_attendance_m->LABEL_HOLIDAY){$total_holidays++;$month_holidays++;}
                                }
                        }
                        ////////////// decrement month////////
                        $month_others=$month_days-($month_holidays+$month_present+$month_absent+$month_leave);
                        $monthly_report[$month]=array('month'=>$month,'year'=>$year,'total_days'=>$month_days,'present'=>$month_present,'absent'=>$month_absent,'leave'=>$month_leave,'holidays'=>$month_holidays,'others'=>$month_others);
                        $month++;
                        if($month>12){$year++;$month=1;}
                    }
                    $total_others=$total_days-($total_holidays+$total_present+$total_absent+$total_leave);
                    //save to report
                    $data[$student['mid']]=array('total_report'=>array('total_days'=>$total_days,'present'=>$total_present,'absent'=>$total_absent,'leave'=>$total_leave,'holidays'=>$total_holidays,'others'=>$total_others),'monthly_report'=>$monthly_report);

                }break;
            }
        }
    }
    return $data;    
}       



//////////////////////////////////////////////// END OF CLASS /////////////////////
}