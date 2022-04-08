<?php
    
    //send akspk brand sms
    function send_akspk_brand_sms($mobile,$apikey, $to, $message, $masking,$lang=''){
        if($lang=='ur'){$lng='ur';}else{$lng='en';}
        $url='https://akspk.com/api/sendsms?apikey='.urlencode($apikey).'&mobile='.urlencode($mobile).'&to='.urlencode($to).'&message='.urlencode($message).'&mask='.urlencode($masking).'&lang='.urlencode($lng).'&output=text';
        $ch=curl_init();
        $timeout=3;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            $result=curl_exec($ch);
            curl_close($ch);
            return strtolower($result);
            // if(strtolower($result)=='message sent'){return TRUE;}
            // return FALSE;
	}
    //send akspk regular sms
    function send_akspk_regular_sms($mobile,$apikey, $to, $message,$lang=''){
        if($lang=='ur'){$lng='ur';}else{$lng='en';}
        $url='https://akspk.com/api/sendregular?apikey='.urlencode($apikey).'&mobile='.urlencode($mobile).'&to='.urlencode($to).'&message='.urlencode($message).'&lang='.urlencode($lng).'&output=text';
        $ch=curl_init();
        $timeout=3;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            $result=curl_exec($ch);
            curl_close($ch);
            return strtolower($result);
            // if(strtolower($result)=='message sent'){return TRUE;}
            // return FALSE;
	}
	  
    //send akspk brand sms
    function send_regular_brand_sms($mobile,$apikey, $to, $message, $masking,$lang=''){
        if($lang=='ur'){$lng='ur';}else{$lng='en';}
        $url='https://regularsms.pk/api/sendsms?apikey='.urlencode($apikey).'&mobile='.urlencode($mobile).'&to='.urlencode($to).'&message='.urlencode($message).'&mask='.urlencode($masking).'&lang='.urlencode($lng).'&output=text';
        $ch=curl_init();
        $timeout=3;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            $result=curl_exec($ch);
            curl_close($ch);
            return strtolower($result);
            // if(strtolower($result)=='message sent'){return TRUE;}
            // return FALSE;
    }
    //send akspk regular sms
    function send_regular_nonbrand_sms($mobile,$apikey, $to, $message,$lang=''){
        if($lang=='ur'){$lng='ur';}else{$lng='en';}
        $url='https://regularsms.pk/api/sendregular?apikey='.urlencode($apikey).'&mobile='.urlencode($mobile).'&to='.urlencode($to).'&message='.urlencode($message).'&lang='.urlencode($lng).'&output=text';
        $ch=curl_init();
        $timeout=3;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            $result=curl_exec($ch);
            curl_close($ch);
            return strtolower($result);
            // if(strtolower($result)=='message sent'){return TRUE;}
            // return FALSE;
    }
    
	
    ////////////////////////////////////// helper functions ////////////////////////////////////////////////////////////////////////////////////
    // GET ARRAY FROM STRING  
    function string_to_array($string, $opt=',') {return explode($opt, $string);}
    // GET STRING FROM ARRAY
    function array_to_string($array, $opt=',') {
        $string='';        
        foreach ($array as $var){
            if(is_array($var)){
                $string.=$this->array_to_string($var,$opt);
            }
            else{$string.=$var.$opt.' '; }         
            }        
        return $string;
        //return implode($opt, $array);
    }
    
    ///////////////////////////////////////////////////db functions////////////////////////////////////////////////////////////////////////////
    
    //update sms status
    function update_sms_status($conn,$rid,$status='sent',$res=''){
        $sql="UPDATE sms_history SET status='$status',response='$res' WHERE mid=".$rid;
        if($conn->query($sql)===TRUE){return true;}
        return false;
    }
    //update sms response
    function update_sms_response($conn,$rid,$res=''){
        $sql="UPDATE sms_history SET response='$res' WHERE mid=".$rid;
        if($conn->query($sql)===TRUE){return true;}
        return false;
    }
    
    //UPDATE ANY TABLE
    function mark_sent($conn,$rowid,$jd=''){
          $time=date('d-m-Y h:i:s');
          $sql="INSERT INTO sms_sent (smsid, time, jd) VALUES ($rowid, '$time',$jd)";
            if($conn->query($sql)===TRUE){return true;}
            return false;
    }
    //UPDATE ANY TABLE
    function mark_delivered($conn,$rowid,$jd=''){
          $time=date('d-m-Y h:i:s');
          $sql="INSERT INTO smt_delivered (delsmsid, deltime,deljd) VALUES ($rowid, '$time',$jd)";
            if($conn->query($sql)===TRUE){return true;}
            return false;
    }
    //DELETE SMS
    function delete_sms($conn,$rowid){
          $sql="DELETE FROM sms_history WHERE mid=".$rowid;
            if($conn->query($sql)===TRUE){return true;}
            return false;
    }