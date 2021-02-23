<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class setor_cms extends CI_Controller
{

    function __construct(){
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
    }

    function tahun_ini(){
        $data['page_title']= 'INPUT S T S NON TUNAI';
        $this->template->set('title', 'INPUT S T S NON TUNAI');   
        $this->template->load('template','tukd/pendapatan/setor_cms',$data) ; 
    }

    function cari_rekening_pend()
	{		
		$lccr =  $this->session->userdata('kdskpd');
        $sql = "SELECT top 1 rekening_pend FROM ms_skpd where left(kd_skpd,17)=left('$lccr',17) order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rek_bend' => $resulte['rekening_pend']
                        );                        
        }
        echo json_encode($result);                                          
	}

    function cari_rekening_tujuan_kasda($jenis)
	{				
	    $skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,a.kd_skpd,a.jenis FROM ms_rekening_bank a where a.jenis='$jenis'";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rekening' => $resulte['rekening'],     
                        'nm_rekening' => $resulte['nm_rekening'],
                        'bank' => $resulte['bank'],     
                        'nmbank' => $resulte['nmbank'],     
                        'kd_skpd' => $resulte['kd_skpd'],
                        'jenis' => $resulte['jenis']
                        );                        
        }
           
        echo json_encode($result);    	
	}

    function cari_bank()
	{				
        $sql = "SELECT kode,nama FROM ms_bank";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'kode' => $resulte['kode'],     
                        'nama' => $resulte['nama']
                        );                        
        }
           
        echo json_encode($result);    	
	}   

   function update_sts_pendapatan_ag(){
        
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        $nohide      = $this->input->post('nohide');
        //$nomor_kas   = $this->input->post('lckas');
        //$tgl_kas     = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
		$pengirim      = $this->input->post('pengirim');
        $sumber      = $this->input->post('sts');  
        $sp2d        = $this->input->post('sp2d');  
        $jns_cp      = $this->input->post('jns_cp');  
		$no_terima   = $this->input->post('no_terima');  
        $nmskpd      = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
        $curut        = $this->input->post('surut');  
        $cbank      = $this->input->post('bankk');
        
        $rek_awal = trim($this->input->post('rek_awal'));            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $init_ket = $this->input->post('ketcms');
        $stt_val  = 0;
        $stt_up   = 0;  
        
		$last_update = date('d-m-y H:i:s');
      // $last_update = " ";
        $msg = array();        
            
            $sql = "delete from trhkasin_pkd_cms where kd_skpd='$skpd' and no_sts='$nohide'";
            $asg = $this->db->query($sql);
            
				
				 $sql = "insert into trhkasin_pkd_cms(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima,urut,bank,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,ket_tujuan) 
                        values('$nomor','$skpd','$tgl','$ket','$total','','$giat','$jnsrek','','$pengirim','0','','','$no_terima','$curut','$cbank','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$init_ket')";
				
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd_cms where no_sts='$nohide' AND kd_skpd='$skpd' ";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd_cms (kd_skpd,no_sts,kd_rek5,rupiah,kd_sub_kegiatan,no_terima,kd_rek6) values $lcnilaidet";
                        $asg = $this->db->query($sql); 
                        
                    }                
                }            
            
            echo '2';    
        
    }   

    function hapus_sts(){
        $nomor = $this->input->post('no');
	    $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "delete from trhkasin_pkd_cms where no_sts='$nomor' AND kd_skpd='$kd_skpd' ";
        $asg = $this->db->query($sql);
		$sql = "delete from trdkasin_pkd_cms where no_sts='$nomor'  AND kd_skpd='$kd_skpd'";
		$asg = $this->db->query($sql);
        echo '1';                
    }

    function simpan_sts_pendapatan(){
        
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        //$nomor_kas       = $this->input->post('lckas');
        //$tgl_kas       = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
		$pengirim    = $this->input->post('pengirim');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
        $sumber      = $this->input->post('sts');  
        $sp2d        = $this->input->post('sp2d');  
        $jns_cp        = $this->input->post('jns_cp');  
		$no_terima   = $this->input->post('no_terima');  
        $sgiat       = $this->input->post('sgiat');  
		$surut        = $this->input->post('surut');
        $sbank        = $this->input->post('bankk');
        
        $rek_awal = trim($this->input->post('rek_awal'));            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $init_ket = $this->input->post('ketcms');
        $stt_val  = 0;
        $stt_up   = 0;

        $nmskpd      = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
		$last_update = date('Y-m-d H:i:s');
      // $last_update = " ";
        $msg = array();
        if ($tabel == 'trhkasin_pkd_cms') {
            
            $sql = "delete from trhkasin_pkd_cms where kd_skpd='$skpd' and no_sts='$nomor'";
            $asg = $this->db->query($sql);
            
            
            if ($asg){
				if($jnsrek==5){
				 $sql = "insert into trhkasin_pkd_cms(no_kas,no_sts,kd_skpd,tgl_sts,tgl_kas,keterangan,total,kd_bank,kd_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,kd_sub_kegiatan) 
                        values('$nomor_kas','$nomor','$skpd','$tgl','$tgl_kas','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','1','$sp2d','$jns_cp','$giat')";
				} else{
				 $sql = "insert into trhkasin_pkd_cms(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima,urut,bank,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,ket_tujuan,kd_sub_kegiatan) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$pengirim','0','$sp2d','$jns_cp','$no_terima','$surut','$sbank','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$init_ket','$giat')";
				}
               
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd_cms where no_sts='$nomor' AND kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd_cms(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan,no_terima,kd_rek5) values $lcnilaidet";
                        $asg = $this->db->query($sql); 
                        
                    }                
                }            
            } 
            echo '2';
        }
         
    }
  

function load_belum_sts() {
		$kd_skpd     = $this->session->userdata('kdskpd');         
            $par = "a.kd_skpd='$kd_skpd'";
            $par2 = "kd_skpd='$kd_skpd'";        
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and a.status_upload='0'";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd_cms a where $par and a.jns_trans='4' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
		
		
		//$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
		$sql = "
		SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd_cms a where $par and a.jns_trans='4'  and a.status_upload='0'
		$where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd_cms where $par2 and jns_trans='4' and a.status_upload='0' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
		";
		
		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                      
           $bidang = "00";
          
           
           
            $row[] = array( 
						'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nmrek' => '',//$rek_rek,                      
                        'bidang' => $bidang,
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
						'sumber' => $resulte['sumber'],
						'no_terima' => $resulte['no_terima'],
                        'nm_skpd' => $resulte['nm_skpd'],
						'bank' => $resulte['bank'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_up' => $resulte['status_upload'],
                        'status_val' => $resulte['status_validasi']
                        );
                        $ii++;
				}
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();	
    	   
	}

    function load_dsts() {    
       // $kriteria = '0012.a/1.20.05';
        $kriteria = $this->input->post('no');
        //$kriteria = $this->uri->segment(3);
		$skpd = $this->session->userdata('kdskpd');
		
        $sql = "SELECT a.*, (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek 
        from trdkasin_pkd_cms a where a.no_sts = '$kriteria' and a.kd_skpd='$skpd' order by a.no_sts";
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'nm_rek' => $resulte['nm_rek'],
                        'rupiah' =>  number_format($resulte['rupiah'],2,'.',','),
                        'no_terima' => $resulte['no_terima']
						);
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
    
    function load_sts_tgl() {
		$kd_skpd     = $this->session->userdata('kdskpd');         
            $par = "a.kd_skpd='$kd_skpd'";
            $par2 = "kd_skpd='$kd_skpd'";        
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and a.tgl_sts ='$kriteria'";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd_cms a where $par and a.jns_trans='4' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
		
		
		//$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
		$sql = "
		SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd_cms a where $par and a.jns_trans='4' 
		$where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd_cms where $par2 and jns_trans='4' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
		";
		
		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                      
           $bidang = "00";
           
           
           
            $row[] = array( 
						'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nmrek' => '',//$rek_rek,                      
                        'bidang' => $bidang,
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
						'sumber' => $resulte['sumber'],
						'no_terima' => $resulte['no_terima'],
                        'nm_skpd' => $resulte['nm_skpd'],
						'bank' => $resulte['bank'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_up' => $resulte['status_upload'],
                        'status_val' => $resulte['status_validasi']
                        );
                        $ii++;
				}
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();	
    	   
	}
}