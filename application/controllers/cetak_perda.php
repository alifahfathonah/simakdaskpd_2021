<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_perda extends CI_Controller {

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

    function laporan_perda_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN I";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda');   
        $this->template->load('template','anggaran/lap_apbd/laporan_perda',$data) ; 
    }

    function laporan_perwa_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN I";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/laporan_perda',$data) ; 
    }

    function laporan_perda2_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN II";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/lampiran2_murni',$data) ; 
    }

    function laporan_perwa2_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN II";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/lampiran2_murni',$data) ; 
    }

    function laporan_perda3_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN III";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda III');   
        $this->template->load('template','anggaran/lap_apbd/lampiran3_murni',$data) ; 
    }

    function laporan_perwa3_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN III";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa III');   
        $this->template->load('template','anggaran/lap_apbd/lampiran3_murni',$data) ; 
    }

    function laporan_perda4_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN IV";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda IV');   
        $this->template->load('template','anggaran/lap_apbd/lampiran4_murnid',$data) ; 
    }

    function laporan_perwa4_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN IV";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa IV');   
        $this->template->load('template','anggaran/lap_apbd/lampiran3_murni',$data) ; 
    }

    function cetak_perda_murni(){
        $tgl_ttd= $this->uri->segment(3);
        $ttd1   = $this->uri->segment(4);
        $ttd2   = $this->uri->segment(5);
        $id     = $this->uri->segment(6);
        $cetak  = $this->uri->segment(7);
        $detail = $this->uri->segment(8);
        $doc    = $this->uri->segment(9);
        $gaji   = $this->uri->segment(10);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_perda_model->cetak_perda_murni($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji);
     }


    function akses_gaji(){
        $sql="SELECT kd_skpd, nm_skpd,
        case when status_keg=0 then 
        '<label class=\"switch\"><input type=\"checkbox\" onclick=\"javascript:aktif(\"'+kd_skpd+'\");\"><span class=\"slider round\"></span></label>' else
        '<label class=\"switch\"><input type=\"checkbox\" onclick=\"javascript:aktif(\"'+kd_skpd+'\");\"><span class=\"slider round\"></span></label>' end as status from(
        select a.kd_skpd, a.nm_skpd, b.status_keg from ms_skpd a left join trskpd b on a.kd_skpd=b.kd_skpd WHERE right(kd_sub_kegiatan,10)='01.2.02.01')xx";
    
        $data=array();
        $exe=$this->db->query($sql);
         foreach($exe->result() as $oke){
            $kd_skpd=$oke->kd_skpd;
            $nm_skpd=$oke->nm_skpd;
            $status=$oke->status;
            $data[]=array(
                'kd_skpd'=>$kd_skpd,
                'nm_skpd'=>$nm_skpd,
                'status'=>$status
            );
         }

        echo json_encode($data);
    }

    function lampiran2_murni($tgl='',$doc='',$pdf=''){
        echo $this->cetak_perda_model->lampiran2_murni($tgl,$doc,$pdf);
    }

    function lampiran3_murni($tgl='',$doc='',$pdf='',$skpd='',$urusan=''){
        echo $this->cetak_perda_model->lampiran3_murni($tgl,$doc,$pdf,$skpd,$urusan);
    }

    function lampiran4_murnid($tgl='',$doc='',$pdf='',$skpd='',$urusan=''){
        echo $this->cetak_perda_model->lampiran4_murnid($tgl,$doc,$pdf,$skpd,$urusan);
    }
}