<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class error extends CI_Controller {

 	function index(){
 		$testing= base_url('image/notfound.png');
 		echo "<div align='center'  style='hight:100%;'>
 				<img src='$testing' style='hight:100%; width: auto' alt='Girl in a jacket' ><br>ss</div>";
 	}

}
