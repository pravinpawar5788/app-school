<?php
class Accounts_M extends MY_Model{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// INHERITED PROPERTIES //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
protected $_model_name='Accounts_M.php';
protected $_table_name = 'finance_accounts';
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// PUBLIC PROPERTIES /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
public $DB_COL_ARRAY=array();
public $_CASH='Cash';              
public $_BANK='Bank';              
public $_RENT='Rent';               
public $_FINE='Fine';                          
public $_SALES='Sales';              
public $_FUNDS='Funds';               
public $_CONCESSION='Concession';               
public $_FEE_ADVANCE='Advance Fee';               
public $_STUDENT_FEE='Student Fee';         
public $_FEE_TRANSPORT='Student Transport Charges';  

public $_FEE_RECEIVABLE='Fee Receivable';               
public $_STAFF_SALARY='Staff Salary';
public $_STAFF_ALLOWANCES='Staff Allowances';
public $_DEDUCTION='Salary Deductions';  
public $_SALARY_ADVANCE='Advance Salary';   

public $_SALARY_PAYABLE='Salary Payable';               
public $_PURCHASES='Purchases';               
public $_PURCHASES_PAYABLE='Purchases Payable';               
public $_STATIONERY='Stationary';  
public $_UTILITY='Utility';  
public $_INVENTORY='Inventory';  
public $_BUILDING='Building';  
public $_FURNITURE='Furniture';  
public $_CAPITAL='Capital';  

public $TYPE_ASSETS='Assets';       //Increase Dr               
public $TYPE_EXPENSE='Expense';     //Increase Dr      
public $TYPE_LIABILITY='Liability'; //Increase Cr          
public $TYPE_REVENUE='Revenue';     //Increase Cr      
public $TYPE_CAPITAL='Capital';     //Increase Cr               

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

return true;   
  
}
//ADD NEW TABLE ROW IN DATABASE 
public function add_row($vals){
    //GET ALL THE FIELDS IN ARRAY  
    $db_row=  $this->grab_row($vals);        
    //PERFROM DIFFERENCT CHECKS BEFORE DATA INSERTION (OPTIONAL)
    ////////////////////////////////////////////////////////////////////////
    $db_row['date']=$this->date;
    if(empty($db_row['period_id'])){$db_row['period_id']=$this->accounts_period_m->get_current_period_id($db_row['campus_id']);}
    
    //SAVE DATA INTO DATABASE
    return $this->save_db_row($db_row);
}
//VALIDATE DATA BEFORE SAVING NEW RECORD
public function is_valid_data($data){
    if(empty($data['campus_id'])||empty($data['title'])||empty($data['type'])||empty($data['period_id'])){
        return false;        
    } 
    ///////////// data is valid to insert or update
    return true;
    
}	   
//////////////////////////////////GETTER FUNCTIONS/////////////////////////////////


public function get_normalized_amount($account_type='', $amount=0){
    $balance='';
    if($account_type==$this->TYPE_ASSETS || $account_type==$this->TYPE_EXPENSE){
        if($amount<0){
            $balance=abs($amount).' Cr ';
        }elseif($amount>0){
            $balance=abs($amount).' Dr ';
        }else{
            $balance=abs($amount);
        }

    }
    if($account_type==$this->TYPE_LIABILITY || $account_type==$this->TYPE_REVENUE || $account_type==$this->TYPE_CAPITAL){
        if($amount<0){
            $balance=abs($amount).' Dr ';
        }elseif($amount>0){
            $balance=abs($amount).' Cr ';
        }else{
            $balance=abs($amount);
        }

    }
    return $balance;
}



public function get_default_accounts_array(){
    $data=array();
    $data[$this->_CAPITAL]=$this->TYPE_CAPITAL;            
    $data[$this->_RENT]=$this->TYPE_EXPENSE;       //building or vehicles rent paid    
    $data[$this->_UTILITY]=$this->TYPE_EXPENSE;     //utility bills paid      
    $data[$this->_PURCHASES]=$this->TYPE_EXPENSE;     //anything purchased for office use       
    $data[$this->_STATIONERY]=$this->TYPE_EXPENSE;     //stationary purchased for office use       
    $data[$this->_CONCESSION]=$this->TYPE_EXPENSE;   //concession given to students        
    $data[$this->_STAFF_SALARY]=$this->TYPE_EXPENSE;            
    $data[$this->_STAFF_ALLOWANCES]=$this->TYPE_EXPENSE;          
    $data[$this->_FINE]=$this->TYPE_REVENUE;     //fine collected from students
    $data[$this->_SALES]=$this->TYPE_REVENUE;    //sale made by selling stationary items etc.
    $data[$this->_FUNDS]=$this->TYPE_REVENUE;     //funds collected from students/staff    
    $data[$this->_DEDUCTION]=$this->TYPE_REVENUE;   //any type of deduction from staff salary            
    $data[$this->_STUDENT_FEE]=$this->TYPE_REVENUE;  //student fee recreived/marked as received.      
    $data[$this->_FEE_TRANSPORT]=$this->TYPE_REVENUE;    //transport charges from students        
    $data[$this->_CASH]=$this->TYPE_ASSETS;            
    $data[$this->_BANK]=$this->TYPE_ASSETS;            
    $data[$this->_BUILDING]=$this->TYPE_ASSETS; 
    $data[$this->_FURNITURE]=$this->TYPE_ASSETS; 
    $data[$this->_INVENTORY]=$this->TYPE_ASSETS;        //inventory available in stationary module
    $data[$this->_FEE_RECEIVABLE]=$this->TYPE_ASSETS;   //fee receivable from students         
    $data[$this->_SALARY_ADVANCE]=$this->TYPE_ASSETS;   //salary paid in advance or loans to staff
    $data[$this->_FEE_ADVANCE]=$this->TYPE_LIABILITY;      //fee received in advance from students      
    $data[$this->_SALARY_PAYABLE]=$this->TYPE_LIABILITY;       //salary payable to staff     
    $data[$this->_PURCHASES_PAYABLE]=$this->TYPE_LIABILITY;       //purchases payable    

    return $data;
}
public function get_account_id($campus_id,$title,$type='',$is_default=false,$period_id=''){
    $filter=array('campus_id'=>$campus_id,'title'=>$title);
    if(!empty($type)){$filter['type']=$type;}
    if($is_default){$filter['is_default >']=0;}
    if(empty($period_id)){$filter['period_id']=$this->accounts_period_m->get_current_period_id($campus_id);}
    return $this->get_by($filter,true,'mid')->mid;
}

public function validate_default_tables($campus_id,$accounting_period=''){
    $this->accounts_period_m->validate_current_period($campus_id,$accounting_period);
    $period_id=$this->accounts_period_m->get_current_period_id($campus_id);

    $accts=$this->get_default_accounts_array();
    foreach ($accts as $acct => $type) {
        if($this->get_rows(array('campus_id'=>$campus_id,'period_id'=>$period_id,'title'=>$acct,'type'=>$type),'',true)<1){
            //create row
            $acct_data=array('campus_id'=>$campus_id,'period_id'=>$period_id,'title'=>$acct,'type'=>$type, 'is_default'=>1);
            if($type==$this->TYPE_ASSETS && $acct==$this->_CASH){$acct_data['is_current_asset']=1;}
            if($type==$this->TYPE_ASSETS && $acct==$this->_BANK){$acct_data['is_current_asset']=1;}
            $this->add_row($acct_data);
        }
    }
}

public function validate_campuses_default_tables($accounting_period=''){
    $accts=$this->get_default_accounts_array();
    $campuses=$this->campus_m->get_rows();
    foreach($campuses as $campus){
        $campus_id=$campus['mid'];
        $this->validate_default_tables($campus_id,$accounting_period);
    }

}

//////////////////////////////////////////////// END OF CLASS /////////////////////
}