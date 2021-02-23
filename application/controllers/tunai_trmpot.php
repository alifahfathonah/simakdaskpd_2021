<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Tukd_cms 
 *  
 * @package 
 * @author Boomer 
 * @copyright 2016
 * @version $Id$ 
 * @access public
 */ 
class tunai_trmpot extends CI_Controller {

    function __construct() 
    {    
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }        
    } 

    function trmpot_pndhbank()
    {
        $data['page_title']= 'P O T O N G A N';
        $this->template->set('title', 'PENERIMAAN POTONGAN');   
        $this->template->load('template','tukd/tunai/trmpot_tunai',$data) ;  
    } 

    function load_trans_trmpot_bnk(){
       $kode    = $this->session->userdata('kdskpd');
       
            $sql = "SELECT DISTINCT a.no_kas,a.no_bukti,a.tgl_bukti,b.no_sp2d,b.kd_sub_kegiatan,b.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.jns_spp,a.total 
            FROM trhtransout a
            JOIN trdtransout b ON a.no_bukti = b.no_bukti and a.kd_skpd = b.kd_skpd
            WHERE a.kd_skpd = '$kode' and a.pay in ('BANK','TUNAI') and a.jns_spp in ('1','3') 
            order by a.tgl_bukti,a.no_bukti";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,                          
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
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

    function simpan_potongan_bnk(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomorvou = $this->input->post('novoucher');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $ket      = $this->input->post('ket');
        $total    = $this->input->post('total'); 
        $beban    = $this->input->post('beban');
        $npwp     = $this->input->post('npwp');      
        $kdrekbank= $this->input->post('kdbank');
        $nmrekbank= $this->input->post('nmbank');        
        $csql     = $this->input->post('sql');            
        $no_sp2d     = $this->input->post('no_sp2d');            
        $kd_giat     = $this->input->post('kd_giat');            
        $nm_giat     = $this->input->post('nm_giat');            
        $kd_rek     = $this->input->post('kd_rek');            
        $nm_rek     = $this->input->post('nm_rek');            
        $rekanan     = $this->input->post('rekanan');            
        $dir     = $this->input->post('dir');            
        $alamat     = $this->input->post('alamat');            
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $csqljur     = $this->input->post('sqljur');            
        $giatt = "";
        $update     = date('Y-m-d H:i:s');      
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtrmpot') {
            $sql = "delete from trhtrmpot where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);              

            if ($asg){
                
                $sql = "insert into trhtrmpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6,nm_rek6,nmrekan, pimpinan,alamat,rekening_tujuan,nm_rekening_tujuan,no_kas) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$kdrekbank','$nmrekbank','$nomorvou')";
                $asg = $this->db->query($sql);
            
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }else if($tabel == 'trdtrmpot') {         
            
            // Simpan Detail //                       
                $sql = "delete from trdtrmpot where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                        
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtrmpot(no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans,ebilling)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
    function load_pot_in_bnk(){
    
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtrmpot where kd_skpd='$kd_skpd' $where " ;

        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        
        $sql = "SELECT top $rows * from trhtrmpot where kd_skpd='$kd_skpd' AND no_bukti not in (SELECT top $offset no_bukti FROM trhtrmpot where kd_skpd='$kd_skpd' 
        order by CAST(no_bukti AS INT)) $where order by CAST(no_bukti AS INT),kd_skpd";

        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],        
                        'ket' => $resulte['ket'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'nilai' => $resulte['nilai'],
                        'kd_giat' => $resulte['kd_sub_kegiatan'],
                        'nm_giat' => $resulte['nm_sub_kegiatan'],
                        'kd_rek' => $resulte['kd_rek6'],
                        'nm_rek' => $resulte['nm_rek6'],
                        'rekanan' => $resulte['nmrekan'],
                        'dir' => $resulte['pimpinan'],
                        'alamat' => $resulte['alamat'],
                        'npwp' => $resulte['npwp'],
                        'jns_beban' => $resulte['jns_spp'],
                        'status' => $resulte['status'],
                        'ebilling' => $resulte['ebilling']                                                                                  
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }

    function load_trm_pot_bnk(){
        $skpd = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdtrmpot where no_bukti='$bukti' AND kd_skpd='$skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['rektotal'],"2",",","."),
                        'rektotal1' => $resulte['rektotal']                       
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
           $query1->free_result();  
    }


    function trdtrmpot_list_bnk() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('nomor');
        
        $sql = "SELECT * FROM trdtrmpot where no_bukti='$nomor' AND kd_skpd ='$kd_skpd' order by kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,   
                        'kd_rek_trans' => $resulte['kd_rek_trans'],  
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],  
                        'ebill' => $resulte['ebilling'],
                        //'pot' => $resulte['pot'],
                        //'nilai' => $resulte['nilai']
                        'nilai' => number_format($resulte['nilai'],2,'.',',')
                        );
                        $ii++;
        }
           
        echo json_encode($result);
         //$query1->free_result();   
    }

   function hapus_trmpot_bnk(){
        $nomor = $this->input->post('no');
        $nomorvo = $this->input->post('novoucher');
        $kd_skpd  = $this->session->userdata('kdskpd');
        
        $sql = "delete from trhtrmpot where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        
        if($asg){
        $msg = array(); 

        $sql = "delete from trdtrmpot where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        }
    }














 }