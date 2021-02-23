<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class penagihanc extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }  

    function penagihan(){
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
        $lccr = $this->input->post('q');
        $tipe=$this->session->userdata('type');

      
        $sql = "
        	SELECT a.kd_skpd, a.nm_skpd, a.kd_sub_kegiatan,a.nm_sub_kegiatan,sum(a.nilai) as total 
            from trdrka a 	inner join trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            and a.kd_skpd=b.kd_skpd			
			where  left(a.kd_skpd,17)=left('$cskpd',17) and
            (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%')) and
			 left(kd_rek6,1) = '5' and b.status_sub_kegiatan <> '0' 
			group by  a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_skpd, a.nm_skpd
			order by  a.kd_skpd, a.kd_sub_kegiatan
        ";
        		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'kd_sub_skpd' => $resulte['kd_skpd']        
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
        $kd_sub_skpd   = $this->input->post('kd_sub_skpd');
        $nomor  = $this->input->post('no');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
        $user   = $this->session->userdata('pcNama');
        $bidang = $this->session->userdata('bidang');



        if ($rek !=''){        
            $notIn = " and a.kd_rek6 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
        

			 $sql = "SELECT a.kd_skpd, a.kd_rek6,(select top 1 nm_rek6 from ms_rek6 where kd_rek6=a.kd_rek6) nm_rek6,e.map_lo,
					  (SELECT SUM(nilai) FROM 
						(SELECT SUM (c.nilai) as nilai FROM trdtransout c LEFT JOIN trhtransout d 
                        ON c.no_bukti = d.no_bukti
						AND c.kd_skpd = d.kd_skpd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND 
                        d.kd_skpd = a.kd_skpd
						AND c.kd_rek6 = a.kd_rek6 AND d.jns_spp='1'
						UNION ALL

						SELECT SUM(x.nilai) as nilai FROM trdspp x INNER JOIN trhspp y 
						ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd WHERE x.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND left(x.kd_skpd,22) = a.kd_skpd AND x.kd_rek6 = a.kd_rek6
						AND y.jns_spp IN ('3','4','5','6')
						AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
						UNION ALL

						SELECT SUM(nilai) as nilai FROM trdtagih t 
						INNER JOIN trhtagih u ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
						WHERE t.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND u.kd_skpd = left(a.no_trdrka,22) aND t.kd_rek = a.kd_rek6
						AND u.no_bukti NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,22)=left('$kd_sub_skpd',22) )
						)r) AS lalu,
                      0 AS sp2d,sum(a.nilai) AS anggaran, sum(a.nilai_sempurna) as nilai_sempurna, sum(a.nilai_ubah) as nilai_ubah
                      FROM trdrka a LEFT JOIN ms_rek6 e ON a.kd_rek6=e.kd_rek6  WHERE a.kd_sub_kegiatan= '$giat' AND a.kd_skpd = left('$kd_sub_skpd',22)  group by a.kd_rek6, no_trdrka,map_lo,a.kd_sub_kegiatan,a.kd_skpd

					  ";
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
        $user     = $this->session->userdata('pcNama');
        $bidang     = $this->session->userdata('bidang');
        if($bidang=='51'){
            $filter="and username='$user'";
        }else{
            $filter="";
        }
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

        $sql = "SELECT TOP $rows * from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where AND no_bukti not in (SELECT TOP $offset no_bukti from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where $filter order by no_bukti)  $filter order by no_bukti,kd_skpd ";
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
                        'kd_sub_skpd' => $resulte['kd_sub_skpd'],
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
        $kd_sub_skpd   = $this->input->post('kd_sub_skpd');   
        $kode   = $this->input->post('kd');
        $rek    = $this->input->post('rek'); 
        $nomor  = $this->input->post('nomor');
        $jenis  = $this->input->post('jenis');       
        $lccr   = $this->input->post('q');


            $sql ="SELECT sumber_dana, sum(nilai) nilai, sum(nilai_sempurna) nilai_sempurna, 
            sum(nilai_ubah) nilai_ubah, sum(lalu) lalu from (

            select sumber1_ubah as sumber_dana,isnull(nilai_sumber,0) as nilai,
            isnull(nsumber1_su,0) as nilai_sempurna,isnull(nsumber1_ubah,0) as nilai_ubah, 0 lalu 
            from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) 
            union ALL
            select sumber2_ubah as sumber_dana,isnull(nilai_sumber2,0) as nilai,isnull(nsumber2_su,0) as nilai_sempurna,isnull(nsumber2_ubah,0) as nilai_ubah, 0 lalu from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) and nsumber2_ubah <> 0
            union ALL
            select sumber3_ubah as sumber_dana,isnull(nilai_sumber3,0) as nilai,isnull(nsumber3_su,0) as nilai_sempurna,isnull(nsumber3_ubah,0) as nilai_ubah, 0 lalu from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) and nsumber2_ubah <> 0

            union all
            select sumber, 0 nilai, 0 nilai_sempurna, 0 nilai_ubah, sum(nilai) lalu from(
            SELECT sumber, kd_rek5, sum(nilai) nilai from(

                    SELECT
                    sumber, c.kd_rek6 kd_rek5, SUM (c.nilai) AS nilai
                    FROM
                        trdtransout c
                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                    AND c.kd_skpd = d.kd_skpd
                    WHERE
                        c.kd_sub_kegiatan = '$giat'
                    AND LEFT (d.kd_skpd, 22) = LEFT ('$kd_sub_skpd', 22)

                    AND d.jns_spp = '1'
                    GROUP BY c.kd_rek6, sumber

                    UNION all
                    SELECT
                            sumber, t.kd_rek6 kd_rek5,SUM (nilai) AS nilai
                            FROM
                                trdtagih t
                            INNER JOIN trhtagih u ON t.no_bukti = u.no_bukti
                            AND t.kd_skpd = u.kd_skpd
                            WHERE
                                t.kd_sub_kegiatan = '$giat'
                            AND u.kd_skpd = '$kd_sub_skpd'
                            AND u.no_bukti NOT IN (
                                SELECT
                                    no_tagih
                                FROM
                                    trhspp
                                WHERE
                                    kd_skpd = '$kd_sub_skpd'
                            ) GROUP BY t.kd_rek6, sumber
UNION all
SELECT
                        x.sumber, x.kd_rek6 kd_rek5,    SUM (x.nilai) AS nilai
                        FROM
                            trdspp x
                        INNER JOIN trhspp y ON x.no_spp = y.no_spp
                        AND x.kd_skpd = y.kd_skpd
                        WHERE
                            x.kd_sub_kegiatan = '$giat'
                        AND LEFT (x.kd_skpd, 22) = LEFT ('$kd_sub_skpd', 22)
                        AND y.jns_spp IN ('3', '4', '5', '6')
                        AND (
                            sp2d_batal IS NULL
                            OR sp2d_batal = ''
                            OR sp2d_batal = '0'
                        ) GROUP BY  x.sumber, x.kd_rek6

UNION all 
    SELECT
                c.sumber, c.kd_rek6 kd_rek5, SUM (c.nilai) AS nilai
                FROM
                    trdtransout_cmsbank c
                LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                AND c.kd_skpd = d.kd_skpd
                AND c.username = d.username
                WHERE
                    c.kd_sub_kegiatan = '$giat'
                AND LEFT (d.kd_skpd, 22) = LEFT ('$kd_sub_skpd', 22)
                AND c.no_voucher <> '$nomor'
                AND d.jns_spp = '$jenis'
                AND d.status_validasi = '0'

            GROUP BY c.sumber, c.kd_rek6

             ) dd WHERE kd_rek5='$rek' GROUP BY sumber, kd_rek5) v group by sumber, kd_rek5


            )z GROUP BY  sumber_dana";                

        
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
                        'nilaidana_ubah' => $resulte['nilai_ubah'],
                        'nilailalu' => $resulte['lalu']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }

	function rincian_dpo(){
		$notrdrka=$this->input->post('no_trdrka');
		$username=$this->session->userdata('pcNama');
		$bidang=$this->session->userdata('bidang');
		if($bidang=='51'){
			$kondi="and kd_lokasi in (select kd_lokasi from ms_lokasi WHERE username='$username')";
		}else{
			$kondi="";
		}


		$sql="SELECT kd_lokasi, unik, total, total_sempurna, total_ubah, uraian from trdpo where no_trdrka='$notrdrka' $kondi ";
		$exc=$this->db->query($sql);
		$data= array();
		$ii=0;
		foreach($exc->result() as $abc){
			$data[]=array(
				'ii'=>$ii,
				'total'=>$abc->total,
				'total_sempurna'=>$abc->total_sempurna,
				'total_ubah'=>$abc->total_ubah,
				'uraian'=>$abc->uraian,
                'kd_lokasi'=>$abc->kd_lokasi,
                'idx'=>$abc->unik
			);
			$ii++;
		}
		 echo json_encode($data);    
	}

  function load_total_trans(){
	   $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $no_bukti    = $this->input->post('no_simpan');
       $beban       = $this->input->post('beban');
       
       if($beban=="3"){
                	$sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
									(           
										select g.kd_kegiatan,sum(g.lalu) spp from(
                                SELECT b.kd_kegiatan,
                                (SELECT isnull(SUM(c.nilai),0) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd AND c.username=d.username
					            WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                                d.kd_skpd=a.kd_skpd 
					            AND c.kd_rek5=b.kd_rek5 AND c.no_voucher <> 'x' AND c.kd_kegiatan='$kegiatan') AS lalu,
                                b.nilai AS sp2d
                                FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
					            INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
					            INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                                WHERE b.kd_kegiatan='$kegiatan'
                                )g group by g.kd_kegiatan
                                
									) as d on a.kd_kegiatan=d.kd_kegiatan
									left join 
									(
										
                                        select z.kd_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
										from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
										where f.kd_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_kegiatan
                                        UNION ALL
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
										from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
										where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_kegiatan
                                        )z group by z.kd_kegiatan
                                        
									) g on a.kd_kegiatan=g.kd_kegiatan
									left join 
									(
										SELECT t.kd_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
										INNER JOIN trhtagih u 
										ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
										WHERE t.kd_kegiatan = '$kegiatan' 
										AND left(u.kd_skpd,17)=left('$kdskpd',17)
										AND u.no_bukti 
										NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,17)=left('$kdskpd',17) )
										GROUP BY t.kd_kegiatan
									) z ON a.kd_kegiatan=z.kd_kegiatan
									where a.kd_kegiatan='$kegiatan'"; 
       }else{

            	$sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
									(           
										select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
										where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
										and (sp2d_batal<>'1' or sp2d_batal is null ) 
										group by c.kd_sub_kegiatan
									) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
									left join 
									(
										
                                        select z.kd_sub_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
										from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
										where f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan
                                        UNION ALL
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
										from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
										where f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        )z group by z.kd_sub_kegiatan
                                        
									) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
									left join 
									(
										SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
										INNER JOIN trhtagih u 
										ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
										WHERE t.kd_sub_kegiatan = '$kegiatan' 
										AND left(u.kd_skpd,17)=left('$kdskpd',17)
										AND u.no_bukti 
										NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,17)=left('$kdskpd',17) )
										GROUP BY t.kd_sub_kegiatan
									) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
									where a.kd_sub_kegiatan='$kegiatan'";     
       }
    	

		$query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'total' => number_format($resulte['total'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    

    function simpan_penagihan_ar() {
		$skpd  = $this->session->userdata('kdskpd');
        $proses = $this->input->post('proses');
        $sta_byr      = $this->input->post('status_byr');
        if ( $proses == 'header' ) {
            
            $tabel        = $this->input->post('tabel');
            $lckolom      = $this->input->post('kolom');
            $lcnilai      = $this->input->post('nilai');
            $cid          = $this->input->post('cid');
            $sta_byr      = $this->input->post('status_byr');
            $lcid         = $this->input->post('lcid');
            
           
            $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd'";
            $res = $this->db->query($sql);
            if ( $res->num_rows() > 0 ) {
                echo '1';
                exit();
            } else {
                
                $sql    = "insert into $tabel $lckolom values $lcnilai";
                $asg    = $this->db->query($sql);
                if ( $asg ) {
                    echo '2';
                } else {
                    echo '0';
                    exit();
                    
                }
            }
        } 
        
        
        if ( $proses == 'detail' ) {
                $tabel_detail = $this->input->post('tabel_detail');
                $no_detail    = $this->input->post('no_detail');
                $sql_detail   = $this->input->post('sql_detail');
                $sta_byr      = $this->input->post('status_byr');
                $kd_kegiatan  ='';
                $nm_kegiatan  ='';
                $kdp          ='';
                $rek3         ='';
                $kdrek3       ='';
                $kd_aset      ='';
                $nm_aset      ='';
                $sql        = " insert into trdtagih(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,kd_rek,nm_rek6,nilai,kd_skpd,sumber, kd_sub_skpd) "; 
                $asg_detail = $this->db->query($sql.$sql_detail);
                        
                if ( $asg_detail ){
						echo '4';
					} else { 
					   echo '5';
					}
        }     
    }

    function load_dtagih(){        
        $nomor = $this->input->post('no'); 
        $kd_skpd = $this->session->userdata('kdskpd');   
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtagih c LEFT JOIN trhtagih d ON c.no_bukti=d.no_bukti WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek6=b.kd_rek AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_sub_kegiatan=b.kd_sub_kegiatan AND f.kd_rek6=b.kd_rek6) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = b.kd_sub_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek6=b.kd_rek) AS anggaran, b.kd_sub_skpd FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' and a.kd_skpd='$kd_skpd' ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_subkegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_subkegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek6'],
                        'kd_rek'        => $resulte['kd_rek'],
                        'nm_rek5'       => $resulte['nm_rek6'],
                        'nilai'         => $resulte['nilai'],
                        'lalu'          => $resulte['lalu'],
                        'sp2d'          => $resulte['sp2d'],   
                        'anggaran'      => $resulte['anggaran'],
                        'sumber'      => $resulte['sumber'],
                        'kd_sub_skpd'      => $resulte['kd_sub_skpd'],                                                                                                                                                           
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

    function load_tot_tagih(){
		$skpd = $this->session->userdata('kdskpd');
		$no = $this->input->post('no_tagih');
        $query1 = $this->db->query("select sum(nilai) as rektagih from trhtagih a INNER join trdtagih b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
								where a.no_bukti='$no' AND a.kd_skpd='$skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'total' => number_format($resulte['rektagih'],2,'.',',')
                        );
                        $ii++;
        }
       
		   echo json_encode($result);
            $query1->free_result();	
	}

  	function cekspp(){
        $no_tagih  = $this->input->post('no_tagih');
        $skpd  = $this->session->userdata('kdskpd');
        $querycek = $this->db->query("SELECT count(no_tagih) total from trhspp where no_tagih ='$no_tagih' and kd_skpd='$skpd'")->row();
        echo json_encode($querycek->total);
    }

    function update_penagihan_header_ar() {
		$skpd  = $this->session->userdata('kdskpd');
        $tabel   = $this->input->post('tabel');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');
        
        if (  $lcid <> $lcid_h ) {
           $sql     = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd'";
           $res     = $this->db->query($sql);
           if ( $res->num_rows()>0 ) {
                echo '1';
                exit();
           } 
        }
        
        $sqlcek = "select count(no_tagih) as total from trhspp where no_tagih ='$lcid'";
        $querycek = $this->db->query($sqlcek);
        $total = $querycek->row();
        $msg = array();
        if ($total->total=='0'){
            $query     = $this->input->post('st_query');
            $asg       = $this->db->query($query);

            
            if ( $asg > 0 ){
               echo '2';
            } else {
               echo '0';
            }
        } else {
           echo '0';
        }
}

    function update_penagihan_detail_ar()	{
		   $skpd  = $this->session->userdata('kdskpd');
           $nomor   = trim($this->input->post('nomor'));
           $lcid    = $this->input->post('lcid');
           $lcid_h  = $this->input->post('lcid_h');

        $sqlcek = "select count(no_tagih) as total from trhspp where no_tagih ='$nomor'";
        $querycek = $this->db->query($sqlcek);
        $total = $querycek->row();
        $msg = array();
        if ($total->total=='0'){
           
           $sql     = " delete from trdtagih where no_bukti='$nomor' AND kd_skpd='$skpd' ";
           $asg     = $this->db->query($sql);
           
           if ( $asg > 0 ) {  
            
                $tabel_detail = $this->input->post('tabel_detail');
                $no_detail    = $this->input->post('no_detail');
                $sql_detail   = $this->input->post('sql_detail');
           
				$sql 		  = " insert into trdtagih(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,kd_rek,nm_rek6,nilai,kd_skpd,sumber,kd_sub_skpd)";
                $asg_detail   = $this->db->query($sql.$sql_detail);
              
                echo '1'; 
                exit();
                 
           } 
       } else {
                    echo '0';
                    exit();
           }
	}

   function hapus_penagihan(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $sqlcek = "select count(no_tagih) as total from trhspp where no_tagih ='$nomor'";
        $querycek = $this->db->query($sqlcek);
        $total = $querycek->row();
        $msg = array();
        if ($total->total=='0'){
        $sql = "delete from trhtagih where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
		if ($asg){
			$sql = "delete from trdtagih where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);
			if ($asg){
              $msg = array('pesan'=>'1');
              echo json_encode($msg);
            } else{
				$msg = array('pesan'=>'0');
              echo json_encode($msg);
			}
		}
    }
		else {
		 $msg = array('pesan'=>'0');
            echo json_encode($msg);	
		}
       }


    function cetak_cek_penagihan_opd(){
        $skpd = $this->session->userdata('kdskpd');
        $kontrak = $this->uri->segment(3);
        $kontrak = str_replace('abcd','/',$kontrak);
        $kontrak = str_replace('efgh',' ',$kontrak);

        $sqlss="SELECT nm_skpd FROM ms_skpd where kd_skpd='$skpd' ";
                 $sqlsclient=$this->db->query($sqlss);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nama     = $rowsc->nm_skpd;
                   
                }

        $cRet = "";
        $cRet .="<table border='0' width='100%' >
                    <tr align='center'>
                        <td><b>".strtoupper('Laporan Penagihan Berdasarkan Kontrak')."</b></td>
                    </tr>
                 </table></br>   
        ";
        $cRet .="<table border='1' width='100%'>
                 
                 <tr>
                 <td style='border:none'>SKPD</td>
                 <td colspan='5' style='border:none'>: $nama</td>
                 </tr>
                 <tr>
                 <td style='border:none'>Kontrak</td>
                 <td colspan='5' style='border:none'>: $kontrak</td>
                 </tr>
                 <tr align='center'>
                 <td colspan='6' style='border:none'><br/></td>
                 </tr>

                 <tr align='center'>
                 <td width='15%'>No Bukti</td>
                 <td width='5%'>Tanggal</td>
                 <td width='5%'>Kode</td>
                 <td width='15%'>Rekening</td>
                 <td width='15%'>Jenis</td>
                 <td width='10%'>Nilai</td>
                 </tr>
                 
        ";

        $sql ="select
a.no_bukti,a.tgl_bukti,b.kd_rek,b.nm_rek6,
case
when a.jenis='' then 'Tanpa Termin/Sekali Pembayaran'
when a.jenis='5' then 'BAST 95% dan 5%'
when a.jenis='1' then 'Termin'
when a.jenis='4' then 'Uang Muka Termin'
when a.jenis='3' then 'Utang Tahun Lalu'
end as jenis,
a.kontrak,b.nilai
from trhtagih a
inner join trdtagih b on b.no_bukti=a.no_bukti and b.kd_skpd=a.kd_skpd
where a.kd_skpd='$skpd' and kontrak='$kontrak'
order by a.tgl_bukti,a.jenis";
        $hasil = $this->db->query($sql);
                $lcno = 0;
                foreach ($hasil->result() as $row)
                {
                    $no_bukti= $row->no_bukti;
                     $tgl_bukti= $row->tgl_bukti;
                      $kd_rek= $row->kd_rek;
                       $nm_rek5= $row->nm_rek6;
                        $jenis= $row->jenis;
                         $kontrak= $row->kontrak;
                          $nilai= number_format($row->nilai,2);
        $cRet .="<tr>
                 <td width='15%' style='font-size:12px'>$no_bukti</td>
                 <td width='5%' style='font-size:12px'>$tgl_bukti</td>
                 <td width='5%' style='font-size:12px'>$kd_rek</td>
                 <td width='15%' style='font-size:12px'>$nm_rek5</td>
                 <td width='15%' style='font-size:12px'>$jenis</td>
                 <td width='10%' style='font-size:12px'align='right'>$nilai</td>
                 </tr>";
        }

        $cRet .="</table>";

        $data['prev']=$cRet;
        echo ("<title>CEK PENAGIHAN</title>");
        echo $cRet;
    }

    function cetak_cek_penagihan_opdall(){
        $skpd = $this->session->userdata('kdskpd');
        $kontrak = $this->uri->segment(3);
        $kontrak = str_replace('abcd','/',$kontrak);
        $kontrak = str_replace('efgh',' ',$kontrak);

        $sqlss="SELECT nm_skpd FROM ms_skpd where kd_skpd='$skpd' ";
                 $sqlsclient=$this->db->query($sqlss);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nama     = $rowsc->nm_skpd;
                   
                }

        $cRet = "";
        $cRet .="<table border='0' width='100%'>
                    <tr align='center'>
                        <td><b>".strtoupper('Laporan Keseluruhan Penagihan Berdasarkan Kontrak')."</td>
                    </tr>
                 </table></br>   
        ";

        $cRet .="<table border='1' width='100%'>
                 
                 <tr>
                 <td colspan='8' style='border:none'>SKPD : $nama</td>
                 </tr>
                 <tr align='center'>
                 <td colspan='8' style='border:none'><br/></td>
                 </tr>
                 <tr align='center'>
                 <td width='5%'>No</td>
                 <td width='15%'>Kontrak</td>
                 <td width='15%'>No Bukti</td>
                 <td width='5%'>Tanggal</td>
                 <td width='5%'>Kode</td>
                 <td width='15%'>Rekening</td>
                 <td width='15%'>Jenis</td>
                 <td width='10%'>Nilai</td>
                 </tr>                 
        ";

        $lcno = 1;
                
        $sql ="select 
        '1' kode,a.kontrak,
        (select count(kontrak) from trhtagih where kontrak=a.kontrak and kd_skpd=a.kd_skpd) byk_kontrak,
        '' no_bukti,'' tgl_bukti,'' kd_rek,'' nm_rek6,'' jenis, 0 nilai
        from trhtagih a where a.kd_skpd='$skpd' group by a.kontrak,a.kd_skpd
        union all 
        select 
        '2' kode,a.kontrak,0 byk_kontrak,
        a.no_bukti,a.tgl_bukti,b.kd_rek,b.nm_rek6,
        case 
        when a.jenis='' then 'Tanpa Termin/Sekali Pembayaran' 
        when a.jenis='5' then 'BAST 95% dan 5%'
        when a.jenis='1' then 'Termin'
        when a.jenis='4' then 'Uang Muka Termin'
        when a.jenis='3' then 'Utang Tahun Lalu'
        end as jenis,nilai 
        from trhtagih a
        inner join trdtagih b on b.no_bukti=a.no_bukti and b.kd_skpd=a.kd_skpd
        where a.kd_skpd='$skpd'
        order by a.kontrak,tgl_bukti";
        $hasil = $this->db->query($sql);
                
                foreach ($hasil->result() as $row)
                {
                   $kode= $row->kode; 
                    $no_bukti= $row->no_bukti;
                     $tgl_bukti= $row->tgl_bukti;
                      $kd_rek= $row->kd_rek;
                       $nm_rek5= $row->nm_rek6;
                        $jenis= $row->jenis;
                         $kontrak= $row->kontrak;
                          $byk= $row->byk_kontrak;
                          $nilai= number_format($row->nilai,2);

        if($kode==1){
            $cRet .="
                <tr>
                 <td bgcolor='#C9CFD1' width='5%' style='font-size:12px'>$lcno</td>
                 <td width='15%' style='font-size:12px'>$kontrak</td>
                 <td colspan='6' width='15%' style='font-size:12px'></td>
                <tr>  
                ";
                $lcno++;
        }else{
            $cRet .="
                    
            <tr>
             <td width='5%' style='font-size:12px'></td>
             <td width='15%' style='font-size:12px'></td>
             <td width='15%' style='font-size:12px'>$no_bukti</td>
             <td width='5%' style='font-size:12px'>$tgl_bukti</td>
             <td width='5%' style='font-size:12px'>$kd_rek</td>
             <td width='15%' style='font-size:12px'>$nm_rek5</td>
             <td width='15%' style='font-size:12px'>$jenis</td>
             <td width='10%' style='font-size:12px'align='right'>$nilai</td>
            </tr>";
        }            

                
        }
        
        $cRet .="</table>";

        $data['prev']=$cRet;
        echo ("<title>CEK PENAGIHAN</title>");
        echo $cRet;
    }

    function load_total_spd(){
       $kode    = $this->input->post('kode');
       $tgl    = $this->input->post('tgl');
       $giat    = $this->input->post('giat');
       
            $sql = "SELECT
                        SUM (a.nilai_final) AS total_spd
                    FROM
                        trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                        left(b.kd_skpd,17) = left('$kode',17)
                    AND a.kd_subkegiatan = '$giat' and b.tgl_spd <= '$tgl'
                    AND b.status = '1'";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'total_spd' => number_format($resulte['total_spd'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }



} /*end of file*/