<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class penerimaan extends CI_Controller
{

    function __construct()
    {
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
    }

    function tahun_ini(){
        $data['page_title']= 'INPUT PENERIMAAN';
        $this->template->set('title', 'INPUT PENERIMAAN');
        
        $pukesmas=$this->session->userdata('kdskpd');
        $cek=$this->db->query("SELECT count(kd_skpd) oke from ms_skpd where left(kd_skpd,17)=left('1.02.0.00.0.00.01.0000',17) and kd_skpd='$pukesmas'")->row()->oke;    
        if($cek==1){
            $this->template->load('template','tukd/pendapatan/penerimaan_pusk',$data) ; 
        }else{
            $this->template->load('template','tukd/pendapatan/penerimaan',$data) ; 
        }
               
    }

	function tahun_lalu(){
        $data['page_title']= 'INPUT PENERIMAAN';
        $this->template->set('title', 'INPUT PENERIMAAN');   
        $this->template->load('template','tukd/pendapatan/penerimaan_piutang_tl',$data) ; 
    }
    function config_tbp(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT max(urut) as nilai FROM tr_terima a WHERE a.kd_skpd = '$skpd'"; 
        $query1 = $this->db->query($sql);  						
       
        foreach($query1->result_array() as $resulte)
        { 
            $n = $resulte['nilai'];
            if($n==null){
                $n=0;
            }
            
            $result = array(                                
                        'nomor' => $n + 1
                        );
                        
        }
        echo json_encode($result); 	
    }

    function load_no_tetap() { //wahyu
        $kd_skpd  = $this->session->userdata('kdskpd');                  
        $where = "where kd_skpd='$kd_skpd'";        
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        if ($kriteria <> ''){                               
            $where="where kd_skpd='$kd_skpd' AND (upper(no_tetap) like upper('%$kriteria%') or tgl_tetap like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%')) and ";            
        }

		$where2 =' AND no_tetap not in(select no_tetap from tr_terima '.$where.' )';

        
        $sql = "SELECT *,
                (SELECT a.nm_rek5 FROM ms_rek5 a WHERE a.kd_rek5=tr_tetap.kd_rek5) as nm_rek,
                (SELECT a.uraian FROM map_rek_penerimaan a WHERE a.kd_rek6=tr_tetap.kd_rek6 and a.kd_skpd=tr_tetap.kd_skpd) as nm_rek6 FROM tr_tetap $where $where2
                order by no_tetap";
        
                
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_tetap' => $resulte['no_tetap'],
                        'tgl_tetap' => $resulte['tgl_tetap'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => $resulte['nilai'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'nm_rek5' => $resulte['nm_rek'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'nm_rek6' => $resulte['nm_rek6'],
                        'kd_rek_lo' => $resulte['kd_rek_lo'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'bidang' => '00'                                                                                                                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	} 

	function simpan_terima() {
            $tabel          = $this->input->post('tabel');
            $lckolom        = $this->input->post('kolom');
            $lcnilai        = $this->input->post('nilai');
            $cid            = $this->input->post('cid');
            $lcid           = $this->input->post('lcid');
			
			$sql        	= "insert into $tabel $lckolom values $lcnilai";
			$asg       	 = $this->db->query($sql);
			if ( $asg > 0 ) {
				echo '2';
			} else {
				echo '0';
				exit();
			}
		
	}

	function update_terima() {
            $tabel          = $this->input->post('tabel');
            $lckolom        = $this->input->post('kolom');
            $lcnilai        = $this->input->post('nilai');
            $cid            = $this->input->post('cid');
            $lcid           = $this->input->post('lcid');
			$nohide       = $this->input->post('no_hide');
			$skpd  = $this->session->userdata('kdskpd');
			
			
			$sql = "delete from tr_terima where kd_skpd='$skpd' and no_terima='$nohide'";
            $asg = $this->db->query($sql);
            if ($asg){
				$sql        = "insert into $tabel $lckolom values $lcnilai";
				$asg       	 = $this->db->query($sql);
				if ( $asg > 0 ) {
					echo '2';
				} else {
					echo '0';
					exit();
				}
			}
	}

   function hapus_terima(){
        //no:cnomor,skpd:cskpd
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql = "DELETE from tr_terima where no_terima='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);
        $sql1 = "DELETE from trhju_pkd where no_voucher='$nomor' and kd_skpd = '$skpd'";
        $asg1 = $this->db->query($sql1);
        $sql2 = "DELETE from trdju_pkd where no_voucher='$nomor'";
        $asg2 = $this->db->query($sql2);
        
        $sqlx1 = "DELETE from trhju where no_voucher='$nomor' and kd_skpd = '$skpd'";
        $asgx1 = $this->db->query($sqlx1);
        $sqlx2 = "DELETE from trdju where no_voucher='$nomor'";
        $asgx2 = $this->db->query($sqlx2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }

    function load_terima() {
		$skpd     = $this->session->userdata('kdskpd');        
        $cek = explode(".",$skpd);
        $ck = $cek[3];  
        
        if($ck=="00"){
            $par = "kd_skpd='$skpd'";
        }else{
            $par = "kd_skpd='$skpd'";
        }
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND no_terima LIKE '%$kriteria%' OR tgl_terima LIKE '%$kriteria%' OR keterangan LIKE '%$kriteria%' ";            
        }
       
        $sql = "SELECT count(*) as total from tr_terima WHERE $par $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
		
		
		//$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
		$sql = "
		SELECT top $rows no_terima,no_tetap,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket,nilai, kd_rek5,kd_rek_lo,kd_kegiatan,SUBSTRING(kd_kegiatan,14,2) as bidang,sts_tetap,bank,kd_rek6 from tr_terima WHERE $par AND (jenis <> '2' or jenis is null)
		$where AND no_terima NOT IN (SELECT TOP $offset no_terima FROM tr_terima WHERE $par $where ORDER BY tgl_terima,cast(urut as int)) ORDER BY tgl_terima,cast(urut as int) ";

		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           $par_terima = $resulte['no_terima'];
           $stt = $this->db->query("select count(no_terima) as row from trdkasin_pkd where kd_skpd='$skpd' and no_terima='$par_terima'")->row();
           $cek_stt = $stt->row;
           $stt2 = $this->db->query("select count(no_terima) as row from trdkasin_pkd_cms where kd_skpd='$skpd' and no_terima='$par_terima'")->row();
           $cek_stt_cms = $stt2->row;
           
            $row[] = array(  
                        'id' => $ii,        
                        'no_terima' => $resulte['no_terima'],
                        'no_tetap' => $resulte['no_tetap'],
                        'tgl_terima' => $resulte['tgl_terima'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['ket'],    
                        'nilai' => number_format($resulte['nilai']),
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'kd_rek' => $resulte['kd_rek_lo'],
						'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'bidang' => $resulte['bidang'],
						'tgl_tetap' => $resulte['tgl_tetap'],
                        'sts_tetap' =>$resulte['sts_tetap'],
                        'bank' =>$resulte['bank'],
                        'stt_sts' => $cek_stt,
                        'stt_cms' => $cek_stt_cms                                                                                           
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();	
    }

	function load_terima_tl() {
		$skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND no_terima LIKE '%$kriteria%' OR tgl_terima LIKE '%$kriteria%'";            
        }
       
        $sql = "SELECT count(*) as total from tr_terima WHERE kd_skpd = '$skpd' and jenis='2' $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
		
		
		//$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
		$sql = "
		SELECT top $rows no_terima,no_tetap,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket,nilai, kd_rek5,kd_rek_lo,kd_kegiatan, kd_sub_kegiatan,sts_tetap,bank,kd_rek6 from tr_terima WHERE kd_skpd='$skpd' AND jenis='2' 
		$where AND no_terima NOT IN (SELECT TOP $offset no_terima FROM tr_terima WHERE kd_skpd='$skpd' $where ORDER BY tgl_terima,no_terima ) ORDER BY tgl_terima,cast(urut as int) ";

		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
           $par_terima = $resulte['no_terima'];
           $stt = $this->db->query("select count(no_terima) as row from trdkasin_pkd where kd_skpd='$skpd' and no_terima='$par_terima'")->row();
           $cek_stt = $stt->row;
           $stt2 = $this->db->query("select count(no_terima) as row from trdkasin_pkd_cms where kd_skpd='$skpd' and no_terima='$par_terima'")->row();
           $cek_stt_cms = $stt2->row;
           
            $row[] = array(  
                        'id' => $ii,        
                        'no_terima' => $resulte['no_terima'],
                        'no_tetap' => $resulte['no_tetap'],
                        'tgl_terima' => $resulte['tgl_terima'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['ket'],    
                        'nilai' => number_format($resulte['nilai']),
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'kd_rek' => $resulte['kd_rek_lo'],
						'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
						'tgl_tetap' => $resulte['tgl_tetap'],
						'bank' => $resulte['bank'],
                        'sts_tetap' =>$resulte['sts_tetap'],
                        'sts_stt' =>$cek_stt,
                        'sts_cms' =>$cek_stt_cms                                                                                            
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();	
    }











}