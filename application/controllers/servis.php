<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class servis extends CI_Controller {

public $ppkd = "4.02.02";
public $ppkd1 = "4.02.02.02";
public $keu1 = "4.02.02.01";
public $kdbkad="5-02.0-00.0-00.02.01";

public $ppkd_lama = "4.02.02";
public $ppkd1_lama = "4.02.02.02";
 
    function __contruct()
    {   
        parent::__construct();
    } 

    function standar()
    {
        $data['page_title']= 'STANDAR BIAYA HARGA';
        $this->template->set('title', 'STANDAR BIAYA HARGA');   
        $this->template->load('template','anggaran/standar',$data) ; 
    }

    function asb(){
    	$url="http://sisb.pontianakkota.go.id/api/standar_biaya_v2?tahun=2021&revisi=revisi-2021";
    	$ch=curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$konten=curl_exec($ch);
    	curl_close($ch);
    	$data=json_decode($konten,true);
    	$this->db->query("DELETE ms_standar_harga");
    	foreach($data as $isi){

    		$kd_rek6=$isi['kode_rek'];
    		$kd_barang=$isi['kode_rek_rinci'];
    		$uraian=$isi['uraian'];
    		$satuan=$isi['satuan'];
    		$harga=$isi['besaran'];
            $merk=$isi['parent'];
    		if($harga==''){
    			$harga=0;
    		}
    		$kunci=$isi['stat_kunci'];
    		$in="INSERT INTO ms_standar_harga 
    			(kd_rek6,kd_barang,uraian,satuan,harga,keterangan,kunci,merk)
    			values 
    			('$kd_rek6','$kd_barang','$uraian','$satuan','$harga','','$kunci','$merk')";
    		$this->db->query($in);
    	}

    	$url="http://asb.pontianakkota.go.id/api/asb2021";
    	$ch=curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$konten=curl_exec($ch);
    	curl_close($ch);
    	$data=json_decode($konten,true);
    	foreach($data as $isi){

    		$kd_rek6=$isi['kode_rek'];
    		$uraian=$isi['asb'];
    		$satuan=$isi['satuan'];
    		$harga=$isi['besaran'];
    		if($harga==''){
    			$harga=0;
    		}
    		$in="INSERT INTO ms_standar_harga 
    			(kd_rek6,kd_barang,uraian,satuan,harga,keterangan,kunci,merk)
    			values 
    			('$kd_rek6','x','$uraian','$satuan','$harga','','','')";
    		$this->db->query($in);
    	}

    }


}
