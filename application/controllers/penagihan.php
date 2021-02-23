<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class penagihan extends CI_Controller
{

    function __construct(){  
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }    
    } 

    function penagihan_(){
        $data['page_title']= 'INPUT PENAGIHAN';
        $this->template->set('title', 'INPUT PENAGIHAN');   
        $this->template->load('template','tukd/transaksi/penagihan',$data) ; 
    }

	function kontrak() {                 
        $lccr = $this->input->post('q');
		$kd_skpd  = $this->session->userdata('kdskpd');        
        $sql = "SELECT TOP 10 kontrak FROM trhtagih WHERE LEN(kontrak)>1 AND kd_skpd = '$kd_skpd'   
					AND UPPER(kontrak) LIKE UPPER('%$lccr%')
					GROUP BY kontrak
				";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kontrak' => $resulte['kontrak'],  
                        );
                        $ii++;
        }
        echo json_encode($result);
       
    }

    function username($kdskpd){  
        $sql = "SELECT * from [user] WHERE kd_skpd='$kdskpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'username' => $resulte['user_name'],  
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

    function load_trskpd_giat() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        $cskpddd = substr($cskpd,0,7);
		$cek = substr($cskpd,8,2);
       
		
                     
        $lccr = $this->input->post('q');        
      
        $sql = "
        	SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan,sum(a.nilai) as total from trdrka a 	inner join trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd			
			where  a.kd_skpd='$cskpd' and (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%')) and
			 left(kd_rek6,1) = '5' and b.status_sub_kegiatan <> '0'
			group by  a.kd_sub_kegiatan,a.nm_sub_kegiatan
			order by  a.kd_sub_kegiatan
        ";
        		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan']      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}

    function load_rek_penagihan() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        //$sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
            
        if ($rek !=''){        
            $notIn = " and a.kd_rek6 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
        
         $cek = substr($giat,17,6);
        
        if (  $cek != '00.001' ){
            $in = " and left(a.kd_rek6,1) not in ('5') " ;
        } else {
            $in = " " ;
        } 
         
			 $sql = "SELECT a.kd_rek6,a.nm_rek6,e.map_lo,
					  (SELECT SUM(nilai) FROM 
						(SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout c
						LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
						AND c.kd_skpd = d.kd_skpd
						WHERE
						c.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND left(d.kd_skpd,7) = left(a.kd_skpd,7)
						AND c.kd_rek6 = a.kd_rek6
						AND d.jns_spp='1'
						UNION ALL
						SELECT SUM(x.nilai) as nilai FROM trdspp x
						INNER JOIN trhspp y 
						ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
						WHERE
						x.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND left(x.kd_skpd,20) = left(a.kd_skpd,20)
						AND x.kd_rek6 = a.kd_rek6
						AND y.jns_spp IN ('3','4','5','6')
						AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
						UNION ALL
						SELECT SUM(nilai) as nilai FROM trdtagih t 
						INNER JOIN trhtagih u 
						ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
						WHERE 
						t.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND u.kd_skpd = a.kd_skpd
						AND t.kd_rek = a.kd_rek6
						AND u.no_bukti 
						NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,20)=left('$kode',20) )
						)r) AS lalu,
                    0 AS sp2d,a.nilai AS anggaran, a.nilai_sempurna as nilai_sempurna, a.nilai_ubah as nilai_ubah
                      FROM trdrka a LEFT JOIN ms_rek6 e ON a.kd_rek6=e.kd_rek6  WHERE a.kd_sub_kegiatan= '$giat' AND left(a.kd_skpd,20) = left('$kode',20) 
					 $notIn ";
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['map_lo'],
                        'kd_rek' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran'],
                        'anggaran_semp' => $resulte['nilai_sempurna'],
                        'anggaran_ubah' => $resulte['nilai_ubah'],
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}
    
    function load_penagihan(){
		$skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or upper(nm_skpd) like 
                    upper('%$kriteria%') or upper(ket) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trhtagih WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        

        $sql = "SELECT TOP $rows * from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where AND no_bukti not in (SELECT TOP $offset no_bukti from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where order by no_bukti) order by no_bukti,kd_skpd ";
        $query1 = $this->db->query($sql);  
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'ket' => $resulte['ket'],
                        'ket_bast' => $resulte['ket_bast'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'status'    => $resulte['status'],						
                        'jenis'    => $resulte['jenis'],
						'kontrak'    => $resulte['kontrak']						
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }

    function load_reksumber_dana() {                      
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
                
           echo $sql ="SELECT * from (
            select sumber1_ubah as sumber_dana,isnull(nilai_sumber,0) as nilai,isnull(nsumber1_su,0) as nilai_sempurna,isnull(nsumber1_ubah,0) as nilai_ubah from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,20)=left('$kode',20) 
            union ALL
            select sumber1_ubah as sumber_dana,isnull(nilai_sumber2,0) as nilai,isnull(nsumber2_su,0) as nilai_sempurna,isnull(nsumber2_ubah,0) as nilai_ubah from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,20)=left('$kode',20) and nsumber2_ubah <> 0
            )z ";                

        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'sumber_dana' => $resulte['sumber_dana'],  
                        'nilaidana' => $resulte['nilai'],
                        'nilaidana_semp' => $resulte['nilai_sempurna'],
                        'nilaidana_ubah' => $resulte['nilai_ubah']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}

}