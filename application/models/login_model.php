<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_model extends CI_Model {
 
public function login($user_id,$otori,$pcthang,$Cuser,$Display_name,$Skpd,$type,$bidang)
{

	 $time = date("j F Y, H:i:s");
	  $user =  array('pcUser'=>$user_id,
					   'pcOtoriName'=>$otori,
					   'pcThang'=>$pcthang,
					   'pcNama'=>$Cuser,
					   'pcLoginTime'=>$time,
					   'Display_name'=>$Display_name,
					   'kdskpd'=>$Skpd,
					   'type'=>$type,
					   'bidang'=>$bidang); 
			
	$CI =& get_instance();  
	$CI->session->set_userdata('logged', $user_id);
	$CI->session->set_userdata($user);
} 

public function semua($user_id='',$pcthang=''){
	$query = $this->db->query("SELECT * FROM [USER] WHERE USER_NAME='$user_id'");
	foreach($query->result() as $row):
		$user_id = $row->id_user;
		$Cuser = $row->user_name;
		$client['password'] = $row->password;
		$otori = $row->type;
		$Display_name = $row->nama;
		$Skpd = $row->kd_skpd;
		$client['jenis']= $row->jenis;
		$type= $row->type;
		$bidang= $row->bidang;
	endforeach;

	$time = date("j F Y, H:i:s");
	  $user =  array('pcUser'=>$user_id,
					   'pcOtoriName'=>$otori,
					   'pcThang'=>$pcthang,
					   'pcNama'=>$Cuser,
					   'pcLoginTime'=>$time,
					   'Display_name'=>$Display_name,
					   'kdskpd'=>$Skpd,
					   'type'=>$type,
					   'bidang'=>$bidang); 
			
	$CI =& get_instance();  
	$CI->session->set_userdata('logged', $user_id);
	$CI->session->set_userdata($user);
	return true;
}

public function logout()
{
$CI =& get_instance();
$CI->session->sess_destroy();
}
 
public function validate($username,$password,$pcThang)
{
	$query = $this->db->get_where('[user]', array('user_name' => $username));
	$cek = $query->num_rows();
	if($cek != 0)
	{
	foreach($query->result() as $row):
	$client['id_client'] = $row->id_user;
	$client['username'] = $row->user_name;
	$client['password'] = $row->password;
	$client['otori'] = $row->type;
	$client['display_name'] = $row->nama;
	$client['skpd'] = $row->kd_skpd;
	$client['jenis']= $row->jenis;
	$client['type']= $row->type;
	$client['bidang']= $row->bidang;
	endforeach;
	 
			$passmd5 = md5($password);
			if($passmd5 == $client['password'] && $client['jenis']==2 ){
				$this->login($client['id_client'],$client['otori'],$pcThang,$client['username'],$client['display_name'],$client['skpd'],$client['type'],$client['bidang']);	
				return true;
			}else{
				return false;
			}
	}else{
		return false;
	}
}


 
}