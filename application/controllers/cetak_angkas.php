<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class cetak_angkas extends CI_Controller {

public $ppkd = "4.02.01";
public $ppkd1 = "4.02.01.00";
 
	function __construct()	{	  
		parent::__construct();
        
	}

	function load_ttd_unit($skpd){
		$lccr = $this->input->post('q');  
		$result =$this->master_ttd->load_ttd_unit($skpd);
		echo $result;
	}

	function load_ttd_bud(){
		$lccr = $this->input->post('q');  
		$result =$this->master_ttd->load_ttd_bud();
		echo $result;		
	}

	function cetak_angkas_ro($aa='',$tgl='',$ttd1='',$ttd2='',$jenis='',$skpd='',$giat='',$hit='',$cetak=''){
		echo $this->cetak_angkas_model->cetak_angkas_ro($tgl,$ttd1,$ttd2,$jenis,$skpd,$giat,$hit,$cetak);
	}

	function cetak_angkas_giat($aa='',$tgl='',$ttd1='',$ttd2='',$jenis='',$skpd='',$cetak='',$hit=''){
		echo $this->cetak_angkas_model->cetak_angkas_giat($tgl,$ttd1,$ttd2,$jenis,$skpd,$cetak,$hit);
	}	

    function preview_cetakan_cek_anggaran(){
        $id = $this->uri->segment(3);
        $cetak = $this->uri->segment(4);
        $status_ang = $this->uri->segment(5);     
        echo $this->cetak_angkas_model->preview_cetakan_cek_anggaran($id,$cetak,$status_ang);
    }
}