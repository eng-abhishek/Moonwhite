<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  ======================================= 
 *  Author: Team Tech Arise 
 *  License: Protected 
 *  Email: info@techarise.com 
 * 
 *  ======================================= 
 */
require_once APPPATH . "/third_party/PHPExcel.php";
        include_once APPPATH . "/third_party/PHPExcel/Writer/Excel2007.php";
        include_once APPPATH . "/third_party/PHPExcel/IOFactory.php";
class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
?>