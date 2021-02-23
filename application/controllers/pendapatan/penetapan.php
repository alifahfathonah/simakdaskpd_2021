<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class penetapan extends CI_Controller
{

    function __construct()
    {
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
    }

    function index(){
        $data['page_title']= 'INPUT PENETAPAN';
        $this->template->set('title', 'INPUT PENETAPAN');   
        $this->template->load('template','tukd/pendapatan/penetapan',$data) ; 
    }

	function config_pnp(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT max(urut) as nilai FROM tr_tetap a WHERE a.kd_skpd = '$skpd'"; 
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

     function ambil_rek_tetap() {
        $lccr = $this->input->post('q');
        $lckdskpd     = $this->session->userdata('kdskpd');

        
        $sql = "SELECT distinct a.kd_rek6 as kd_rek5,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, b.nm_rek6, 
        a.kd_sub_kegiatan, a.nm_sub_kegiatan FROM 
        trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6  
		where a.kd_skpd = '$lckdskpd' and left(a.kd_rek6,1)='4' and 
        upper(a.kd_rek6) like upper('%$lccr%') order by a.kd_rek6"; 
      
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_rek' => $resulte['kd_rek'],  
                        'nm_rek' => $resulte['nm_rek'],
						'nm_rek4' => '',
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan' => strtoupper($resulte['nm_sub_kegiatan'])                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

	function simpan_tetap_ag() {
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

     function update_tetap_ag() {
            $tabel          = $this->input->post('tabel');
            $lckolom        = $this->input->post('kolom');
            $lcnilai        = $this->input->post('nilai');
            $cid            = $this->input->post('cid');
            $lcid           = $this->input->post('lcid');
			$nohide       = $this->input->post('no_hide');
			$skpd  = $this->session->userdata('kdskpd');
			
			
			$sql = "delete from tr_tetap where kd_skpd='$skpd' and no_tetap='$nohide'";
            $asg = $this->db->query($sql);
            if ($asg){
				$sql        	= "insert into $tabel $lckolom values $lcnilai";
				$asg       	 = $this->db->query($sql);
				if ( $asg > 0 ) {
					echo '2';
				} else {
					echo '0';
					exit();
				}
			}
	}

     function load_tetap() {
        $skpd     = $this->session->userdata('kdskpd');                  
        $cek = explode(".",$skpd);
        $ck = $cek[3];  
        
        if($ck=="00"){
            $par = "a.kd_skpd='$skpd'";
        }else{
            $par = "a.kd_skpd='$skpd'";
        }
         
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND a.no_tetap LIKE '%$kriteria%' OR a.tgl_tetap LIKE '%$kriteria%' OR a.keterangan LIKE '%$kriteria%' ";            
        }
       
        $sql = "SELECT count(*) as total from tr_tetap a WHERE a.kd_skpd='$skpd' $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
		
		
		//$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
		$sql = "
		SELECT top $rows a.*, 
        (SELECT b.nm_rek6 FROM ms_rek6 b WHERE a.kd_rek6=b.kd_rek6) as nm_rek5,
        (SELECT b.uraian FROM map_rek_penerimaan b WHERE a.kd_rek6=b.kd_rek6 and b.kd_skpd=a.kd_skpd) as nm_rek6
         FROM tr_tetap a WHERE $par
		$where AND a.no_tetap NOT IN (SELECT TOP $offset a.no_tetap FROM tr_tetap a WHERE $par $where 
		ORDER BY a.tgl_tetap,a.no_tetap ) ORDER BY tgl_tetap,cast(urut as int) ";

		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                     
           $bidd = "00";
           $par_tetap = $resulte['no_tetap'];
           
           $stt = $this->db->query("SELECT count(no_tetap) as row from tr_terima where kd_skpd='$skpd' and no_tetap='$par_tetap'")->row();
           $cek_stt = $stt->row;                      
           
            $row[] = array(  
						'id' => $ii,        
                        'no_tetap' => $resulte['no_tetap'],
                        'tgl_tetap' => $resulte['tgl_tetap'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai']),
                        'kd_rek5' => $resulte['kd_rek5'],
                        'nm_rek5' => $resulte['nm_rek5'],
                        'kd_rek' => $resulte['kd_rek_lo'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'nm_rek6' => $resulte['nm_rek6'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'bidang' => $bidd,
                        'stt_terima' => $cek_stt                                                                                              
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();	
		
	}  

     function load_rekening_rinci($kd_Rek5x) {
        $skpd     = $this->session->userdata('kdskpd');
 
    
        $sql = "
        SELECT kd_kegiatan,kd_rek6,uraian [nm_rek6] from map_rek_penerimaan where kd_Rek5='$kd_Rek5x' and left(kd_skpd,17)=left('$skpd',17) order by kd_rek6                
        ";
    
    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,           
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'nm_rek6' => $resulte['nm_rek6']                                                                                          
                        );
                        $ii++;
        }
           
        echo json_encode($result);
         
  }  

     function hapus_tetap(){
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql = "delete from tr_tetap where no_tetap='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);
        $sql1 = "delete from trhju_pkd where no_voucher='$nomor' and kd_skpd = '$skpd'";
        $asg1 = $this->db->query($sql1);
        $sql2 = "delete from trdju_pkd where no_voucher='$nomor' and kd_unit = '$skpd'";
        $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }
}
