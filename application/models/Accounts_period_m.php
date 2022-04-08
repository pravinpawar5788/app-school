<?php
class Accounts_period_M extends MY_Model{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
protected $_model_name='Accounts_period_M.php';
protected $_table_name = 'finance_accounting_periods';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public $DB_COL_ARRAY=array();   
public $_TYPE_CALENDAR='calendar';              
public $_TYPE_FISCAL='fiscal';             
public $STATUS_ACTIVE='active';             
public $STATUS_PASSED='passed';      

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
// //init other models

return true;   
  
}
//ADD NEW TABLE ROW IN DATABASE 
public function add_row($vals){
    //GET ALL THE FIELDS IN ARRAY  
	
    $db_row=  $this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    //$db_row['status']=$this->STATUS_ACTIVE;
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['title'])||empty($data['start_jd']) ){
        return false;        
    } 
    ///////////// data is valid to insert or update
    return true;
    
}	  
///////////////////////GETTER FUNCTIONS////////////////////////////////////////////

public function get_current_period_id($campus_id){
    $filter=array('campus_id'=>$campus_id,'status'=>$this->STATUS_ACTIVE);
    if($this->get_rows($filter,'',true)>0){
        return $this->get_by($filter,true)->mid;
    }else{
        return 0;
    }
}


public function validate_current_period($campus_id,$accounting_period='calendar'){
    $filter=array('campus_id'=>$campus_id);    
    $start_date='01-Jan-'.$this->year;
    $end_date='31-Dec-'.$this->year;
    if($accounting_period=='fiscal'){
        $start_date='01-Jul-'.$this->year;
        $end_date='30-Jun-'.($this->year+1);
    }
    $start_jd=get_jd_from_date($start_date,'-',true);
    $end_jd=get_jd_from_date($end_date,'-',true);
    //create period of does not exist
    if($this->get_rows($filter,'',true)<1){
        $period=array('campus_id'=>$campus_id,'title'=>$start_date.' - '.$end_date, 'start_date'=>$start_date, 'end_date'=>$end_date, 'start_jd'=>$start_jd, 'end_jd'=>$end_jd,'status'=>$this->STATUS_ACTIVE);
        $id=$this->add_row($period);
        //if its first time then create default accounts.
        if($this->accounts_m->get_rows(array('campus_id'=>$campus_id),'',true)<1){
            $this->accounts_m->validate_default_tables($campus_id);
        }
    }
    //if accounts exists but accounting period is over then again create new accounts with new period
    $filter['status']=$this->STATUS_ACTIVE;    
    $filter['end_jd <']=$this->todayjd;
    $current_period_id=0;
    if($this->get_rows($filter,'',true)>0){
        //update current period to passed
        $cur_period=$this->get_by($filter,true);
        $current_period_id=$cur_period->mid;
        $this->save(array('status'=>$this->STATUS_PASSED),$cur_period->mid);
        //create new accounting period for this campus and register accounts for this period
        $period=array('campus_id'=>$campus_id,'title'=>$start_date.' - '.$end_date, 'start_date'=>$start_date, 'end_date'=>$end_date, 'start_jd'=>$start_jd, 'end_jd'=>$end_jd,'status'=>$this->STATUS_ACTIVE);
        $id=$this->add_row($period);
        //register accounts for new period
        if($current_period_id>0){
            $accounts=$this->accounts_m->get_rows(array('campus_id'=>$campus_id,'period_id'=>$current_period_id));
            $entry=array('campus_id'=>$campus_id,'period_id'=>$id);
            foreach($accounts as $acct){
                $entry['title']=$acct['title'];
                $entry['type']=$acct['type'];
                $entry['is_default']=$acct['is_default'];
                $entry['is_default']=$acct['is_current_asset'];
                $this->accounts_m->add_row($entry);
            }
        }
    }
}


public function validate_campuses_periods($accounting_period='calendar'){
    $campuses=$this->campus_m->get_rows(array());
    foreach($campuses as $campus){
        $campus_id=$campus['mid'];        
        if($this->get_rows(array('campus_id'=>$campus_id),'',true)<1){
            //create row
            $start_date='01-Jan-'.$this->year;
            $end_date='31-Dec-'.$this->year;
            if($accounting_period=='fiscal'){
                $start_date='01-Jul-'.$this->year;
                $end_date='30-Jun-'.($this->year+1);
            }
            $start_jd=get_jd_from_date($start_date,'-',true);
            $end_jd=get_jd_from_date($end_date,'-',true);
            $record=array('campus_id'=>$campus_id,'title'=>$start_date.' - '.$end_date, 'start_date'=>$start_date, 'end_date'=>$end_date, 'start_jd'=>$start_jd, 'end_jd'=>$end_jd,'status'=>$this->STATUS_ACTIVE);
            $id=$this->add_row($record);
        }

    }
}

//////////////////////////////////////////////// END OF CLASS /////////////////////
}