<?php

require_once('connection.php');
require_once('functions.php');

$time=time()+100;        //run script for only 100 seconds
$settings=array();
//$ptime=time()-30;
$jd=juliantojd(date('m'), date('d'), date('Y'));


////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
//get sms api data
$query ="SELECT * FROM system_settings";
$result=$conn->query($query)->fetch_all(1);
foreach($result as $row){$settings[$row['name']]=$row['value'];}
$_sms_vendor=$settings['sms_vendor'];
$_sms_type=$settings['sms_type'];
$_sms_lang=$settings['sms_lang'];
$_sms_mask=$settings['sms_mask'];
$_sms_sending=intval($settings['sms_sending']);
$_sms_api_username=$settings['sms_api_username'];
$_sms_api_key=$settings['sms_api_key'];
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$query ="SELECT * FROM sms_history LEFT JOIN sms_sent ON sms_history.mid=sms_sent.smsid WHERE sms_sent.smsid IS NULL AND status!='sent' ORDER BY $ORDERBY DESC LIMIT $LIMIT";

$result=$conn->query($query);
$total_rows= $result->num_rows;

if($total_rows>0 && $_sms_sending>0){
    while($row = $result->fetch_assoc()){
        ////run the function only for preset time limit
        if(time()>$time){exit();}        
        ////////////////////////////////////////////////////////////////////////////////////////////////
        $row['message']=html_entity_decode($row['message']);
        $row['message']=htmlspecialchars_decode($row['message']);        
        switch(strtolower($_sms_vendor)){
            case 'akspk' : {
                if(strtolower($_sms_type)=='brand'){
                    //send brand sms
                    if(mark_sent($conn,$row['mid'],$jd)){
                        $response=send_akspk_brand_sms($_sms_api_username,$_sms_api_key, $row['mobile'], $row['message'], $_sms_mask,$_sms_lang);
                        if(strtolower($response)=='message sent'){
                            update_sms_status($conn,$row['mid'],'sent',$response);
                        }else{
                            update_sms_response($conn,$row['mid'],$response);
                        }
                    }
                }else{
                    //send regular sms
                    if(mark_sent($conn,$row['mid'],$jd)){
                        $response=send_akspk_regular_sms($_sms_api_username,$_sms_api_key, $row['mobile'], $row['message'],$_sms_lang);
                        if(strtolower($response)=='message sent'){
                            update_sms_status($conn,$row['mid'],'sent',$response);
                        }else{
                            update_sms_response($conn,$row['mid'],$response);
                        }
                        
                    }
                    
                }
            }break;
            case 'regularsms' : {
                if(strtolower($_sms_type)=='brand'){
                    //send brand sms
                    if(mark_sent($conn,$row['mid'],$jd)){
                        $response=send_regular_brand_sms($_sms_api_username,$_sms_api_key, $row['mobile'], $row['message'], $_sms_mask,$_sms_lang);
                        if(strtolower($response)=='message sent'){
                            update_sms_status($conn,$row['mid'],'sent',$response);
                        }else{
                            update_sms_response($conn,$row['mid'],$response);
                        }
                    }
                }else{
                    //send regular sms
                    if(mark_sent($conn,$row['mid'],$jd)){
                        $response=send_regular_nonbrand_sms($_sms_api_username,$_sms_api_key, $row['mobile'], $row['message'],$_sms_lang);
                        if(strtolower($response)=='message sent'){
                            update_sms_status($conn,$row['mid'],'sent',$response);
                        }else{
                            update_sms_response($conn,$row['mid'],$response);
                        }
                        
                    }
                    
                }
            }break;
            
            
            default:
                break;
        }
      
    }
}



//close databse connection
include 'footer.php';
