<?php
class Accounts_ledger_M extends MY_Model{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
protected $_model_name='Accounts_ledger_M.php';
protected $_table_name = 'finance_accounts_ledger';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public $DB_COL_ARRAY=array();        

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
    if(empty($db_row['month']) && empty($db_row['year'])){
        if(empty($db_row['date'])){
            $db_row['date']=$this->date;
            $db_row['jd']=$this->todayjd;
            $db_row['day']=$this->day;
            $db_row['month']=$this->month;
            $db_row['year']=$this->year;
        }else{
            $db_row['jd']=get_jd_from_date($db_row['date'],'-',true);
            $db_row['day']=get_day_from_date($db_row['date'],'-');
            $db_row['month']=get_month_from_date($db_row['date'],'-',true);
            $db_row['year']=get_year_from_date($db_row['date'],'-'); 

        }
    }else{
        if($db_row['month']==$this->month && $db_row['year']==$this->year){
            if(empty($db_row['date'])){
                $db_row['date']=$this->date;
                $db_row['jd']=$this->todayjd;
                $db_row['day']=$this->day;
                $db_row['month']=$this->month;
                $db_row['year']=$this->year;
            }else{
                $db_row['jd']=get_jd_from_date($db_row['date'],'-',true);
                $db_row['day']=get_day_from_date($db_row['date'],'-');
                $db_row['month']=get_month_from_date($db_row['date'],'-',true);
                $db_row['year']=get_year_from_date($db_row['date'],'-'); 

            }
        }else{            
            if(empty($db_row['day'])){$db_row['day']=1;}
            $db_row['jd']=get_jd_from_date($db_row['day'].'-'.$db_row['month'].'-'.$db_row['year']);  
            if(empty($db_row['date'])){$db_row['date']=get_date_from_jd($db_row['jd']);}
        }
    }

    //$db_row['status']=$this->STATUS_ACTIVE;
    // if(empty($db_row['date'])){
    //     $db_row['date']=$this->date;
    //     $db_row['jd']=$this->todayjd;
    //     $db_row['day']=$this->day;
    //     $db_row['month']=$this->month;
    //     $db_row['year']=$this->year;
    // }else{
    //     $db_row['jd']=get_jd_from_date($db_row['date'],'-',true);
    //     $db_row['day']=get_day_from_date($db_row['date'],'-');
    //     $db_row['month']=get_month_from_date($db_row['date'],'-',true);
    //     $db_row['year']=get_year_from_date($db_row['date'],'-');        
    // }

    if(empty($db_row['period_id'])){$db_row['period_id']=$this->accounts_period_m->get_current_period_id($db_row['campus_id']);}
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['title'])||empty($data['debit_account_id'])||empty($data['debit_amount'])||empty($data['credit_account_id'])||empty($data['credit_amount'])||empty($data['period_id'])||$data['period_id']<1){
        return false;        
    } 
    ///////////// data is valid to insert or update
    return true;
    
}	  
    
// DELETE ROW (PRIMARY KEY)
public function delete($id=NULL, $where=array(),$force=FALSE){ 
    ////////////////////////////////
    if($id != NULL){
        //never delete entry from ledger rather add a reversal entry to maintain the account balances
        $record=$this->get_by_primary($id);
        //parent::delete($id);
        // $debit_account=$this->accounts_m->get_by_primary($record->credit_account_id);
        // $credit_account=$this->accounts_m->get_by_primary($record->debit_account_id);
        // $this->add_entry($record->title.' - Reversal', $debit_account->title, $credit_account->title, $record->debit_amount, false, $record->debit_reference.' - reversal', $record->credit_reference.' - reversal');
        $this->reverse_entry($id);
        //////////////////////////////
        $filter=array('ledger_id'=>$id,'campus_id'=>$record->campus_id);
        $this->std_fee_entry_m->delete(NULL,$filter);
        $this->stf_pay_entry_m->delete(NULL,$filter);
        $this->expense_m->delete(NULL,$filter);
        $this->income_m->delete(NULL,$filter);
        //////////////////////////////
        return true;
    }else{
        if($force){
            parent::delete($id,$where,$force);
        }else{
            $rows=$this->get_rows($where);
            foreach($rows as $row){
                $id=$row['mid'];
                $record=$this->get_by_primary($id);
                // $debit_account=$this->accounts_m->get_by_primary($record->credit_account_id);
                // $credit_account=$this->accounts_m->get_by_primary($record->debit_account_id);
                // $this->add_entry($record->title.' - Reversal', $debit_account->title, $credit_account->title, $record->debit_amount, false, $record->debit_reference.' - reversal', $record->credit_reference.' - reversal');
                $this->reverse_entry($id);
                //////////////////////////////
                $filter=array('ledger_id'=>$id,'campus_id'=>$record->campus_id);
                $this->std_fee_entry_m->delete(NULL,$filter);
                $this->stf_pay_entry_m->delete(NULL,$filter);
                $this->expense_m->delete(NULL,$filter);
                $this->income_m->delete(NULL,$filter);
            }
        }
        return true;
    }
}
///////////////////////GETTER FUNCTIONS////////////////////////////////////////////
public function reverse_entry($id){
        $record=$this->get_by_primary($id);
        $debit_account=$this->accounts_m->get_by_primary($record->credit_account_id);
        $credit_account=$this->accounts_m->get_by_primary($record->debit_account_id);
        $this->add_entry($record->campus_id,$record->title.' - Reversal', $debit_account->title, $credit_account->title, $record->debit_amount, false, $record->debit_reference.' - reversal', $record->credit_reference.' - reversal');

}


public function add_entry($campus_id,$narration,$debit_account,$credit_account,$amount,$is_default=true,$debit_ref='',$credit_ref='',$date='',$is_manual=0,$monthstamp=array()){
     //$monthstamp=array('day'=>'','month'=>,'year'=>'','date'=>'');   
    $debit_acct_type='';
    $credit_acct_type='';
    if($is_default){
        $default_accounts=$this->accounts_m->get_default_accounts_array();
        $debit_acct_type=$default_accounts[$debit_account];
        $credit_acct_type=$default_accounts[$credit_account];
    }
    $data=array('title'=>$narration,'campus_id'=>$campus_id);
    $data['debit_account_id']=$this->accounts_m->get_account_id($campus_id,$debit_account,$debit_acct_type,$is_default);
    $data['credit_account_id']=$this->accounts_m->get_account_id($campus_id,$credit_account,$credit_acct_type,$is_default);
    $data['debit_amount']=$amount;
    $data['credit_amount']=$amount;
    $data['debit_reference']=$debit_ref;
    $data['credit_reference']=$credit_ref;
    $data['is_manual']=$is_manual;
    $data['date']=$date;
    /////////////////////////////////////////////////    
    $data['debit_account_balance']=$this->accounts_m->get_by_primary($data['debit_account_id'],'balance')->balance + $data['debit_amount'];
    $data['credit_account_balance']=$this->accounts_m->get_by_primary($data['credit_account_id'],'balance')->balance - $data['credit_amount'];
    /////////////////////////////////////////////////
    if(isset($monthstamp['day']) && !empty($monthstamp['day'])){$data['day']=$monthstamp['day'];}
    if(isset($monthstamp['month']) && !empty($monthstamp['month'])){$data['month']=$monthstamp['month'];}
    if(isset($monthstamp['year']) && !empty($monthstamp['year'])){$data['year']=$monthstamp['year'];}
    if(isset($monthstamp['date']) && !empty($monthstamp['date'])){$data['date']=$monthstamp['date'];}
    $result=$this->add_row($data);
    if($result){
        $this->accounts_m->update_column_value($data['debit_account_id'],'balance',$data['debit_amount'] );
        $this->accounts_m->update_column_value($data['credit_account_id'],'balance',$data['credit_amount'],'minus');
    }
    return $result;
}
//////////////////////////////////////////////// END OF CLASS /////////////////////
}