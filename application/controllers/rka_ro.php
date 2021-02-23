<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Rka_ro extends CI_Controller {

public $ppkd = "4.02.01";
public $ppkd1 = "4.02.01.00";
 
    function __construct(){  
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }    
    }   
      
    function angkas_ro(){
        $data['page_title']= 'INPUT RENCANA KEGIATAN ANGGARAN RO MURNI';
        $this->template->set('title', 'INPUT ANGKAS MURNI');   
        $this->template->load('template','anggaran/angkas/angkas_ro',$data) ; 
    } 

    function angkas_geser(){
        $data['page_title']= 'INPUT RENCANA KEGIATAN ANGGARAN RO PERGESERAN';
        $this->template->set('title', 'INPUT ANGKAS PERGESERAN');   
        $this->template->load('template','anggaran/angkas/angkas_geser',$data) ; 
    } 

    function angkas_ubah(){
        $data['page_title']= 'INPUT RENCANA KEGIATAN ANGGARAN RO PERUBAHAN';
        $this->template->set('title', 'INPUT ANGKAS PERUBAHAN ');   
        $this->template->load('template','anggaran/angkas/angkas_ubah',$data) ; 
    } 

    function skpduser() {
        $lccr = $this->input->post('q'); 
        $result= $this->master_model->skpduser($lccr);    
        echo json_encode($result);
    }

    function ambil_rek_angkas_ro($kegiatan='',$skpd='') {
        $result=$this->angkas_ro_model->ambil_rek_angkas_ro($kegiatan,$skpd);
        echo json_encode($result);
    }

    function load_giat(){ 
        $cskpd=$this->uri->segment(3);     
        $lccr = $this->input->post('q');  
        $result=$this->angkas_ro_model->load_giat($cskpd,$lccr);      
        echo json_encode($result);      
    }
   
    function total_triwulan($status='',$skpd=''){
        $kd_kegiatan=$this->input->post('kegiatan');
        $result=$this->angkas_ro_model->total_triwulan($status,$kd_kegiatan,$skpd);    
        echo json_encode($result);
    }

    function load_trdskpd($status='',$skpd=''){
        $kegiatan = $this->input->post('p');
        $rekening = $this->input->post('s');
        $result =$this->angkas_ro_model->load_trdskpd($kegiatan,$rekening,$status,$skpd);
        echo json_encode($result);
   }

   function simpan_trskpd_ro(){
        $id  = $this->session->userdata('pcUser');
        $cskpda=$this->input->post('cskpda');
        $cskpd=$this->input->post('cskpd');
        $cgiat=$this->input->post('cgiat');
        $crek5=$this->input->post('crek5');
        $bln1=$this->input->post('jan');      
        $bln2=$this->input->post('feb');       $bln3=$this->input->post('mar');
        $bln4=$this->input->post('apr');       $bln5=$this->input->post('mei');        $bln6=$this->input->post('jun');
        $bln7=$this->input->post('jul');       $bln8=$this->input->post('ags');        $bln9=$this->input->post('sep');
        $bln10=$this->input->post('okt');      $bln11=$this->input->post('nov');       $bln12=$this->input->post('des');
        $tr1=$this->input->post('tr1');        $tr2=$this->input->post('tr2');
        $tr3=$this->input->post('tr3');        $tr4=$this->input->post('tr4');               
        $status = $this->input->post('csts');
        $tabell = 'trdskpd_ro';
        $user_name  =  $this->session->userdata('pcNama');
        $result=$this->angkas_ro_model->simpan_trskpd_ro($cskpda,$status,$cskpd,$cskpd,$cgiat,$crek5, $bln1,$bln2,$bln3,$bln4,$bln5,$bln6,$bln7,$bln8,$bln9,$bln10,$bln11,$bln12, $tr1,$tr2,$tr3,$tr4,$status,$user_name);

        echo $result;
    } 

    function realisasi_angkas_ro($skpd=''){
        $skpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek5 = $this->input->post('rek5');
        $result = $this->angkas_ro_model->realisasi_angkas_ro($skpd,$kegiatan,$rek5);
        echo $result;
    }

    function  tanggal_format_indonesia($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = $this-> getBulan(substr($tgl,5,2));
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.' '.$bulan.' '.$tahun;

    }

    function cetak_angkas_ro($jns_anggaran=''){
        $data['jns_ang']   =$jns_anggaran;
        $data['page_title']= 'Cetak Angkas Murni RO';
        $this->template->set('title', 'Cetak Angkas Murni RO');   
        $this->template->load('template','anggaran/angkas/cetak_angkas_ro',$data) ; 
    }

    function cetak_angkas_giat($jns_anggaran=''){
        $data['jns_ang']   =$jns_anggaran;
        $data['page_title']= 'Cetak Angkas Murni Subkegiatan';
        $this->template->set('title', 'Cetak Angkas Murni Subkegiatan');   
        $this->template->load('template','anggaran/angkas/cetak_angkas_giat',$data) ; 
    }    	
}