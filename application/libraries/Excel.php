<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php";

class Excel extends PHPExcel{//constructor of the library
    public function __construct(){
        parent::__construct();
    }
    
}
