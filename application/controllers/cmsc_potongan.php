<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 select_pot_taspen() rekening gaji manual. harap cek selalu
 */

class cmsc_potongan extends CI_Controller {

 
    function __construct(){   
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }     

    function trmpot(){
        $data['page_title']= 'P O T O N G A N';
        $this->template->set('title', 'PENERIMAAN POTONGAN');   
        $this->template->load('template','tukd/cms/trmpot_cmsbank',$data) ; 
    }

    function load_trans_trmpot(){
	   $kode    = $this->session->userdata('kdskpd');
	   $id      = $this->session->userdata('pcNama');
       
       
       $sql = "SELECT DISTINCT a.no_tgl,a.no_voucher,a.tgl_voucher,b.no_sp2d,b.kd_sub_kegiatan,b.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.jns_spp,a.total 
            FROM trhtransout_cmsbank a
            JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd and a.username=b.username
            WHERE a.kd_skpd = '$kode' and a.no_voucher not in (select no_voucher from trhtrmpot_cmsbank a where a.kd_skpd = '$kode' and a.username='$id') 
            and a.status_upload not in ('1') and a.jns_spp in ('1','3') and a.username='$id'
            order by a.tgl_voucher,a.no_voucher";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,  
                        'no_tgl' => $resulte['no_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                        'kd_rek5' => $resulte['kd_rek6'],
                        'nm_rek5' => $resulte['nm_rek6'],
                        'jns_spp' => $resulte['jns_spp'],
                        'total' => number_format($resulte['total'],2)                              
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
 }