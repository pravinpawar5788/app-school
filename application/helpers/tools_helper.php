<?php


	//security of input , already implemented in input library
	function secure_input($string){
        return htmlspecialchars(stripslashes(trim($string)));    
	}
	///////////////////////////////////////////////////
	//seo friendly url
	function seo_url($string,$dash_separated=true){
        //Lower case everything
        $string = strtolower(trim($string));
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        //$string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/\s-+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        //finally convert multiple dashes to single dash
        $string = preg_replace("/-+/", "-", $string);
        if($dash_separated==false){
            //remove dashes from the string
            $string = preg_replace("-", "", $string);        
        }
        return $string;
	}
	//create file from an array
	function save_array_file($file_name,$array,$save_path,$mode=''){
        file_put_contents($file_name,serialize($array));
        copy($file_name,$save_path.$file_name);unlink($file_name);
        clearstatcache();   
	}

	//create file from an array
	function get_file_array($file_name,$path,$mode=''){
        $file=$path.$file_name;
        return unserialize(file_get_contents($file));   
	}

	// Checks if a folder exist and return canonicalized absolute pathname (long version)
	function is_dir_exist($folder){ 
        // Get canonicalized absolute pathname
        $path = realpath($folder);
        // If it exist, check if it's a directory
        if($path !== false AND is_dir($path))
        {   // Return canonicalized absolute pathname
            return $path;
        }
        // Path/folder does not exist
        return false;
	}
    //check directory size
    function get_dir_size($directory,$file_ext='*',$format=''){
        //onle-liner solution. Result in bytes.
        //$size=array_sum(array_map('filesize', glob("{$dir}/*.*")));
        //Added bonus: can simply change the file mask to whatever, and count only certain files (eg by extension).
        $size = 0;
        $formatedsize=0;
        $files= glob($directory.'/*.'.$file_ext);
        foreach($files as $path){
            is_file($path) && $size += filesize($path);
            is_dir($path) && get_dir_size($path);
        }
        switch(strtolower($format)){
            case 'k' : $formatedsize=number_format($size/(1024),0); break;
            case 'm' : $formatedsize=number_format($size/(1024*1024),1); break;
            case 'g' : $formatedsize=number_format($size/(1024*1024*1024),3); break;
            default : $formatedsize=number_format($size,0); break;
        }
        return $formatedsize;
    }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Copy a file, or recursively copy a folder and its contents
	function xcopy($source, $dest, $permissions = 0755){
        // Check for symlinks
        if (is_link($source)) {        return symlink(readlink($source), $dest);    }
        // Simple copy for a file
        if (is_file($source)) {        return copy($source, $dest);    }
        // Make destination directory
        if (!is_dir($dest)) {        mkdir($dest, $permissions);    }
        // Loop through the folder
        $dir = dir($source);
        //$dir = opendir($source);    
        while (false !== ($entry = $dir->read())) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {   continue;        }
            // Deep copy directories
            xcopy("$source/$entry", "$dest/$entry", $permissions);
        }	
        // Clean up
        $dir->close();
        return true;
	}
    //remove directory & file and files in the directory recursively.
    function xdelete($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            foreach( $files as $file ){xdelete( $file ); }
            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }
    //remove directory & file and files in the session directory recursively.
    function xdeleteSession($target,$ignore) {
        $dirs = glob( $target . '*', GLOB_MARK ); //GLOB_MARK add slash directories returned
        foreach( $dirs as $dir ){
            if(is_dir($dir)){
                if (strpos($dir, $ignore) !== false){continue;}
                $files = glob( $dir . '*', GLOB_MARK ); //GLOB_MARK add slash directories returned
                foreach( $files as $file ){
                    unlink($file); 
                }
                rmdir($dir);
            }
        }
    }
	//Detect the server protocol
	function get_server_protocol(){
        $protocol = 'http://';    
        if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $protocol = 'https://';
        }    
        return $protocol;
	}

	//GET BASE64_ENCODE(D) STRING
	function encode64($string) {
        $string=  base64_encode($string);
        //convert = to whitespace
        $string = preg_replace("/=+/", "", $string);
        return $string;
	}
 
	//GET BASE64_DECODE(D) STRING
	function decode64($string) {
        $string= base64_decode($string, TRUE);
        return $string;
	}
	//get random hax color
	function get_random_hax_color($shade=''){
        switch ($shade) {
            case 'dark': {
                $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a');
                $len=count($rand)-1;
                $color = '#'.$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)];
                return $color;
            }
            break;
            case 'light': {
                $rand = array('8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
                $len=count($rand)-1;
                $color = '#'.$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)].$rand[rand(0,$len)];
                return $color;
            }
            break;
            
            default:{
                $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
                $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
                return $color;
            }
            break;
        }
	}
    //get month number
    function get_month_number($month=1,$year=2017){
        $total_months=intval($year-1)*12;
        $total_months+=intval($month);
        return $total_months;
    }
	// GET ARRAY FROM STRING  
	function string_to_array($string, $opt=','){return explode($opt, $string);}
	// GET STRING FROM ARRAY
	function array_to_string($array, $opt=','){$string='';        
        foreach ($array as $var){if(is_array($var)){$string.=array_to_string($var,$opt);}else{$string.=$var.$opt.' ';}}        
        return $string;
        //alternatively return implode($opt, $array);
	}

	//get month string by providing the the month number
	function month_string($key=1, $short=false){
    	$month=array();	$short_month=array();
    	$month[1]="January";$month[2]="Febraury";$month[3]="March";$month[4]="April";$month[5]="May";$month[6]="June";
    	$month[7]="July";$month[8]="August";$month[9]="September";$month[10]="October";$month[11]="November";$month[12]="December";
    	$short_month[1]="Jan";$short_month[2]="Feb";$short_month[3]="Mar";$short_month[4]="Apr";$short_month[5]="May";
    	$short_month[6]="June";$short_month[7]="Jul";$short_month[8]="Aug";$short_month[9]="Sep";$short_month[10]="Oct";
    	$short_month[11]="Nov";$short_month[12]="Dec";
        if($key>12){$key-=12;}
    	if($short){return $short_month[intval($key)];}else{return $month[intval($key)];}	
	}
	//GET FUTURE DATE BY ADDING DAYS TO CURRENT DATE
	function get_future_date($days){
		$date=date('d-M-Y');
		$future_date=date('d-M-Y',strtotime($date.'+'.$days.' days'));
		return $future_date;		
	}
	//GET FUTURE JD BY ADDING DAYS TO CURRENT JD
	function get_future_jd($days){
		$today=juliantojd(date('m'), date('d'), date('Y'));
		return $today+$days;	
	}
	//GET JD FROM DATE
	function get_jd_from_date($dd_MM_YYYY, $opt="-", $str_month=false){
            if(empty($dd_MM_YYYY)){return 0;}
            $date=$dd_MM_YYYY;
            $arr=explode($opt,$date);
            $day=intval($arr[0]);$month=intval($arr[1]);$year=intval($arr[2]);
            if($str_month){$month_str=$arr[1];$month=intval(date("n",strtotime($month_str)) );}
            return juliantojd($month,$day,$year);	
	}
	//GET FUTURE DATE FROM JD
	function get_date_from_jd($jd){
            $date=jdtojulian($jd);  //date=mm/dd/YYYY
            $arr=explode("/",$date);
            $month=date("M", mktime(null, null, null, $arr[0], 1)); //arr[0]=month
            return $arr[1].'-'.$month.'-'.$arr[2]; //   dd-MM-YYYY
	}
    //GET AGE FROM DATA
    function get_age($date,$result='all',$output='string'){
            $diff=date_diff(date_create($date), date_create('now'));
            switch (strtolower($result)) {
                case 'years':
                    if($output=='string'){return $diff->y.' years';}else{return $diff->y;}
                    break;
                case 'months':
                    if($output=='string'){return $diff->m.' months';}else{return $diff->m;}
                    break;
                case 'days':
                    if($output=='string'){return $diff->d.' days';}else{return $diff->d;}
                    break;
                
                default:{
                    return $diff->y.' years, '.$diff->m.' months, '.$diff->d.' days';
                }
                break;
            }
            $date=jdtojulian($jd);  //date=mm/dd/YYYY
            $arr=explode("/",$date);
            $month=date("M", mktime(null, null, null, $arr[0], 1)); //arr[0]=month
            return $arr[1].'-'.$month.'-'.$arr[2]; //   dd-MM-YYYY
    }
	//GET DAY OF MONTH FROM DATE
	function get_day_from_date($dd_MM_YYYY, $opt="-"){
            if(empty($dd_MM_YYYY)){return 0;}
            $date=$dd_MM_YYYY;$arr=explode($opt,$date);$day=intval($arr[0]);
            return $day;	
	}	
	//GET MONTH FROM DATE
	function get_month_from_date($dd_MM_YYYY, $opt="-", $str_month=false){
            if(empty($dd_MM_YYYY)){return 0;}
            $date=$dd_MM_YYYY;$arr=explode($opt,$date);$month=intval($arr[1]);
            if($str_month){$month_str=$arr[1];$month=intval(date("n",strtotime($month_str)) );}
            return $month;	
	}
	//GET YEAR FROM DATE
	function get_year_from_date($dd_MM_YYYY, $opt="-"){
            if(empty($dd_MM_YYYY)){return 0;}
            $date=$dd_MM_YYYY;$arr=explode($opt,$date);$year=intval($arr[2]);
            return $year;	
	}
	//calculate seconds of days
	function cal_seconds($days){
            $oneday=60*60*24;
            return $days*$oneday;
	}
	//get unix day count
	function get_unix_day_count($year="",$month="",$day=""){
            $y=intval(date('Y'));$m=intval(date('m'));$d=intval(date('d'));
            if(!empty($year) && $year>0 && !empty($month) && $month>0 && !empty($day) && $day>0){
                $y=intval($year);$m=intval($month);$d=intval($day);                
            }
            $unix_date=new DateTime("1970-01-01");
            $date=new DateTime($y."-".$m."-".$d);
            $difference=$unix_date->diff($date);
            return intval($difference->days);
	}	

    //get unix day count
    function get_unix_timestamp($date="",$time=""){
            if(empty($date)){$date=date('Y-m-d');}
            if(empty($time)){$time=date('H:i:s');}else{$time.=':00';}
            $d = new DateTime($date.' '.$time, new DateTimeZone(date_default_timezone_get()));
            return $d->getTimestamp();
    }   
	//GET DAYS IN A MONTH BY PROVIDING MONTH AND YEAR
    function days_in_month($m,$y){
        $month=trim($m);$year=trim($y);
        $days = ($month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year %400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31));
        return $days;
    }
    //RETURN BOOLEAN AFTER DATE CALCULATION
    function is_past_date($year,$month,$day,$hour,$minute=''){
            $cdate=$day.'-'.$month.'-'.$year;
            $jd=$this->get_jd_from_date($cdate);    
            $chour=intval(date('H'));
            $cminute=intval(date('i'));
            if(empty($minute)){
            if($jd<=$this->todayjd && $hour<=$chour){return TRUE;}else{return FALSE;}
            }else{
            if($jd<=$this->todayjd && $hour<=$chour && $minute<=$cminute){return TRUE;}else{return FALSE;}      
            }
                
    }
	// GET RANDOM STRING
    function get_random_string($len=8,$numeric=false,$special_chars=false){
		$string="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if($numeric){$string.="0123456789";}
		if($special_chars){$string.="#!@^-~";}
        return substr(str_shuffle($string), 0, $len);
    } 
    //RETURE CLEANED STRING
    function clean_string($string='',$chars=array(' ','-','_',',')){
        $string=trim($string);
        foreach ($chars as $key => $value) {
            $string=str_replace($value, '', $string);
        }
        return $string;
    }
    //RETURN BOOLEAN AFTER MOBILE VERIFICATION
    function is_valid_mobile_number($number=''){
        if(empty($number)){return false;}
        $number=clean_string($number);
        $length=strlen($number);
        switch ($length) {
            case 10 : {
                if(substr($number, 0, 1)=='3'){ return true;}
            }
            break;
            case 11 : {
                if(substr($number, 0, 2)=='03'){ return true;}
            }
            break;
            case 12 : {
                if(substr($number, 0, 3)=='923'){ return true;}
            }
            break;
            case 13 : {
                if(substr($number, 0, 4)=='0923' || substr($number, 0, 4)=='+923'){ return true;}
            }
            break;
            
            default:
                return false;
                break;
        }
        return false;
    }

    //RETURN STANDARD MOBILE FORM 3XXXXXXXXX
    function get_standard_mobile_number($number=''){
        $number=clean_string($number);
        $length=strlen($number);
        switch ($length) {
            case 11 : { 
                if(substr($number, 0, 2)=='03'){
                    //remove zero from start
                    $number=substr($number, 1, strlen($number));
                }
            }
            break;
            case 12 : {
                if(substr($number, 0, 3)=='923'){
                    //remove 92 from start
                    $number=substr($number, 2, strlen($number));
                }
            }
            break;
            case 13 : {
                if(substr($number, 0, 4)=='0923' || substr($number, 0, 4)=='+923'){
                    //remove 92 from start
                    $number=substr($number, 3, strlen($number));
                }
            }
            break;
            
            default:
                return $number;
                break;
        }
        return '0'.$number;
    }
    //GET VALID MOBILES ARRARY
    //get valid mobile numbers in an array
    function get_valid_mobiles_array($mobiles=array()){
        $valid_mobiles=array();
        foreach ($mobiles as $mobile){
            if(is_valid_mobile_number($mobile)){
                array_push($valid_mobiles, get_standard_mobile_number($mobile));}
            }
        return $valid_mobiles;
    }

    //RETURN THE SMS SIZE
    function get_sms_size($sms="",$unicode=false){        
        if(empty($sms)){return 0;}
        $len=1;
        if($unicode==true){
            $first=70;$rem=67;$chars=mb_strlen($sms,'UTF-8');
            if($chars>$first){$len=ceil($chars/$rem);}            
        }else{
            $first=160;$rem=153;$chars=strlen($sms); 
            if($chars>$first){$len=ceil($chars/$rem);}
        }        
        return $len;
    }
    //RETURN mobile network
    function get_mobile_network($number=''){
        $mobile=get_standard_mobile_number($number);
        $code=substr($mobile, 0,2);
        switch ($code) {
            case '30': return 'mobilink';
            break;
            case '31': return 'zong';
            break;
            case '32': return 'warid';
            break;
            case '33': return 'ufone';
            break;
            case '34': return 'telenor';
            break;
            
            default: return false;
            break;
        }
    }
    /**
     * Get Youtube video ID from URL
     *
     * @param string $url
     * @return mixed Youtube video ID or FALSE if not found
     */
    function getVideoIdFromUrl($url,$host='youtube') {
        switch (strtolower($host)) {
            case 'vimeo':{

            }
            break;
            //default is youtube
            default:{
                $parts = parse_url($url);
                if(isset($parts['query'])){
                    parse_str($parts['query'], $qs);
                    if(isset($qs['v'])){
                        return $qs['v'];
                    }else if(isset($qs['vi'])){
                        return $qs['vi'];
                    }
                }
                if(isset($parts['path'])){
                    $path = explode('/', trim($parts['path'], '/'));
                    return $path[count($path)-1];
                }
                return false;
            }
            break;
        }
        
    }
    //convert array to get url
    function arrayToUrl($data){
        $url="";
        foreach ($data as $key => $value) {
            $url.="&".urlencode($key)."=".urlencode($value);
        }
        return $url;
    }    
    //currency converter
    function get_converted_amount($amount,$conversion_rate=1,$string_output=false,$symbol='$'){
        $converted_amount=$amount*$conversion_rate;
        if($string_output){
            return $symbol.$converted_amount;
        }else{
            return $converted_amount;
        }
    }    
    //return counting position string
    function get_ordinal_symbol($number){
        $position='';
        if(strlen($number.'')>0){ 
            $last=substr($number.'', -1);
            switch (intval($last)) {
                case 0:{$position='';}break;
                case 1:{if(intval(substr($number.'', -2))==11){$position=$number.'th';}else{$position=$number.'st';} }break;
                case 2:{if(intval(substr($number.'', -2))==12){$position=$number.'th';}else{$position=$number.'nd';} }break;
                case 3:{if(intval(substr($number.'', -2))==13){$position=$number.'th';}else{$position=$number.'rd';} }break;            
                default:{$position=$number.'th';}break;
            }
        }
        return $position;
    }
    //check if internet is connect
    function is_internet_connected(){
        $ic_conn=false;
        $connected = @fsockopen("www.google.com", 80);
        if($connected){
            $is_conn=true;
            fclose($connected);
        }else{
            $connected = @fsockopen("www.google.com", 443);
            if($connected){
                $is_conn=true;
                fclose($connected);
            }
        } 
        return $is_conn;
    }
    //update file content
    function update_file_text($file,$find,$replace){
        file_put_contents($file,str_replace($find,$replace,file_get_contents($file)));
    }
    //extract domain name from url
    function extractDomain($url) { 
       $parseUrl = parse_url(trim($url)); 
       if(isset($parseUrl['host'])){
           $host = $parseUrl['host'];
       }else{
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
       }
       return trim(clean_string($host,array('www.','http://wwww.','https://www.')) ); 
} 