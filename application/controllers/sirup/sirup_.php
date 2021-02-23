<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sirup extends CI_Controller {

	function __contruct(){	
		parent::__construct();
	}    
	
    function input_penyedia(){
        $data['page_title']= 'INPUT PENYEDIA';
        $this->template->set('title', 'INPUT PENYEDIA');   
        $this->template->load('template','anggaran/input_penyedia',$data) ; 
    }    
    
    function input_swakelola(){
        $data['page_title']= 'INPUT SWAKELOLA';
        $this->template->set('title', 'INPUT SWAKELOLA');   
        $this->template->load('template','anggaran/input_swakelola',$data) ; 
    } 
    
    function  ambil_bulan($tgl){
        $tanggal  = explode('-',$tgl); 
        return  $tanggal[1];
        }
        
    function  pilih_bulan($bln){
        switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "April";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
        case  0:
        return  "-";
        break;
        
    }
    }    
	
	function listTahun(){                      
        $skpd  = $this->input->post('skpd');
		$result = array();
            $result[] = array(
                        'tahun' 	=> '2019'
                        );
                           
       echo json_encode($result);            
	}
	
    function listKegiatan(){                      
        $skpd  = $this->input->post('skpd');
        $lccr  = $this->input->post('q');
		$sql   = "SELECT a.kd_kegiatan,a.nm_kegiatan,a.nilai,b.kd_program,
                 (SELECT isnull(sum(pagu),0) from sirup_detail where kd_kegiatan=a.kd_kegiatan) nilai_sirup
                 FROM 
				(select kd_kegiatan,nm_kegiatan,SUM(nilai) nilai FROM trdrka WHERE left(kd_skpd,7)=left('$skpd',7)
				GROUP BY kd_kegiatan,nm_kegiatan)a 
				LEFT JOIN trskpd b
				ON a.kd_kegiatan=b.kd_kegiatan 
                where (upper(a.kd_kegiatan) like upper('%$lccr%') or upper(a.nm_kegiatan) like upper('%$lccr%'))
                order by a.kd_kegiatan";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_kegiatan' 	=> $resulte['kd_kegiatan'],  
                        'nm_kegiatan' 	=> $resulte['nm_kegiatan'],  
                        'nilai' 		=> number_format($resulte['nilai'],"2",".",","),
                        'nilai_sirup' 	=> number_format($resulte['nilai_sirup'],"2",".",","),
                        'kd_program' 	=> $resulte['kd_program']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
    
    function listRekening() {                      
        $skpd  = $this->input->post('skpd');
		$kegg  = $this->input->post('kd_keg');
		$lccr  = $this->input->post('q');
        
        $sql = "SELECT a.kd_rek5,a.nm_rek5,a.sumber,a.nilai FROM 				
				trdrka a where a.kd_kegiatan='$kegg' and
                (upper(a.kd_rek5) like upper('%$lccr%') or upper(a.nm_rek5) like upper('%$lccr%'))
                ";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_rek5' 	=> $resulte['kd_rek5'],  
                        'nm_rek5' 	=> $resulte['nm_rek5'],  
                        'nilai' 		=> number_format($resulte['nilai'],"2",".",","),
                        'sumber' 	=> $resulte['sumber']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
	
    function listRincianpaket() {                      
        $skpd  = $this->input->post('skpd');
		$kegg  = $this->input->post('kd_keg');
        $rekk  = $this->input->post('kd_rek');
		$lccr  = $this->input->post('q');
        
        //$kegg = "4.06.4.06.02.00.01.015";
        //$rekk = "5210105";
        
        $sql = "SELECT n.* FROM(
                SELECT
	            v.*,(select isnull(sum(nilai_ubah),0) from trdrka where kd_kegiatan = '$kegg') nilai_ukur,
                    (select isnull(sum(pagu),0) from sirup_detail where kd_kegiatan = '$kegg' AND left(kd_rek5,3) = left('$rekk',3)) nilai_sirup
                FROM(
                SELECT	
				'0' kd_dpaket,(select nm_rek5 from trdrka where kd_kegiatan='$kegg' and kd_rek5='$rekk') nm_dpaket,sum(a.volume1) volume,b.lokasi,b.tu_capai,b.nm_skpd,
                (select klpd from sirup_lokasi where kd_lokasi='1') klpd,sum(a.total_ubah) total_ubah
                FROM trdpo a left join trskpd b on b.kd_kegiatan=a.kd_kegiatan                
				where a.kd_kegiatan='$kegg' and a.kd_rek5='$rekk' and a.kd_rek5 not in (select kd_rek5 from sirup_detail where kd_kegiatan='$kegg' and kd_rek5='$rekk')
				group by a.kd_kegiatan,a.kd_rek5,b.lokasi,b.tu_capai,b.nm_skpd
				union all                                       
                SELECT 
                a.no_po kd_dpaket,a.uraian nm_dpaket,a.volume1 volume,b.lokasi,b.tu_capai,b.nm_skpd,
                (select klpd from sirup_lokasi where kd_lokasi='1') klpd,a.total_ubah
                FROM trdpo a left join trskpd b on b.kd_kegiatan=a.kd_kegiatan
                where a.kd_kegiatan='$kegg' and a.kd_rek5='$rekk' 
                )v where v.nm_dpaket not in (select isi_paket from sirup_detail where kd_kegiatan='$kegg' and kd_rek5='$rekk') and                
                (upper(v.nm_dpaket) like upper('%$lccr%')) )n where n.nilai_sirup < n.nilai_ukur ORDER BY n.kd_dpaket
                ";
                //where n.nilai_sirup < n.nilai_ukur
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_dpaket' => $resulte['kd_dpaket'],  
                        'nm_dpaket' => $resulte['nm_dpaket'],  
                        'volume' 	=> str_replace(".00","",$resulte['volume']),
                        'total_ubah'=> number_format($resulte['total_ubah'],"2",".",","),
                        'total_ubah_t'=> $resulte['total_ubah'],
                        'lokasi' 	=> $resulte['lokasi'],
                        'klpd'  	=> $resulte['klpd'],
                        'tu_capai' 	=> $resulte['tu_capai'],                        
                        'nm_skpd' 	=> $resulte['nm_skpd']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}    
    
    function listRincianpaket_swakelola() {                      
        $skpd  = $this->input->post('skpd');
		$kegg  = $this->input->post('kd_keg');
        $rekk  = $this->input->post('kd_rek');
		$lccr  = $this->input->post('q');
        
        //$kegg = "4.06.4.06.02.00.01.015";
        //$rekk = "5210105";
        
        $sql = "
        SELECT x.* FROM(
        SELECT n.*,isnull(sum(n.n_ubah-n.nilai_sirup),0) total_ubah,isnull(sum(n.volume-n.vol_sirup),0) total_vol FROM(
        SELECT v.*,
        (select sumber from trdrka where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg' group by sumber) sumber_dana,
        (select isnull(sum(pagu),0) from sirup_detail where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg') nilai_sirup,
        (select isnull(sum(vol),0) from sirup_detail where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg') vol_sirup        
        FROM(
SELECT 
case 
when left(a.kd_rek5,3)='521' then '1'
when left(a.kd_rek5,3)='522' then '2'
when left(a.kd_rek5,3)='523' then '3' end as kd_dpaket,
case 
when left(a.kd_rek5,3)='521' then '521'
when left(a.kd_rek5,3)='522' then '522'
when left(a.kd_rek5,3)='523' then '523' end as kd_rek,
case 
when left(a.kd_rek5,3)='521' then 'Belanja Pegawai'
when left(a.kd_rek5,3)='522' then 'Belanja Barang Dan Jasa'
when left(a.kd_rek5,3)='523' then 'Belanja Modal' end as nm_dpaket,	
sum(a.volume1) volume,b.lokasi,b.tu_capai,b.nm_skpd,
(select klpd from sirup_lokasi where kd_lokasi='1') klpd,sum(a.total_ubah) n_ubah
FROM trdpo a left join trskpd b on b.kd_kegiatan=a.kd_kegiatan
where a.kd_kegiatan='$kegg'
group by left(a.kd_rek5,3),b.lokasi,b.tu_capai,b.nm_skpd
)v)n group by n.kd_dpaket,n.kd_rek,n.nm_dpaket,n.volume,n.lokasi,n.tu_capai,n.sumber_dana,n.vol_sirup,n.nm_skpd,n.klpd,n.n_ubah,n.nilai_sirup
)x where x.total_ubah<>0";
                //where n.nilai_sirup < n.nilai_ukur
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_dpaket' => $resulte['kd_dpaket'], 
                        'kd_rek' => $resulte['kd_rek'], 
                        'sumber' => $resulte['sumber_dana'],
                        'nm_dpaket' => $resulte['nm_dpaket'],  
                        'volume' 	=> str_replace(".00","",$resulte['total_vol']),
                        'total_ubah'=> number_format($resulte['total_ubah'],"2",".",","),
                        'total_ubah_t'=> $resulte['total_ubah'],
                        'lokasi' 	=> $resulte['lokasi'],
                        'klpd'  	=> $resulte['klpd'],
                        'tu_capai' 	=> $resulte['tu_capai'],                        
                        'nm_skpd' 	=> $resulte['nm_skpd']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
    
	function lokasi() {                      
		$sql = "SELECT kd_lokasi,nm_lokasi FROM sirup_lokasi";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_lokasi' 	=> $resulte['kd_lokasi'],  
                        'nm_lokasi' 	=> $resulte['nm_lokasi'] 
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
	
    function jns_pengadaan() {                      
		$sql = "SELECT kd_jp,nm_jp FROM sirup_jenis_pengadaan order by kd_jp";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_jp' 	=> $resulte['kd_jp'],  
                        'nm_jp' 	=> $resulte['nm_jp'] 
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
	
    function mtd_pengadaan() {                      
		$sql = "SELECT kd_mp,nm_mp FROM sirup_metode_pengadaan order by kd_mp";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_mp' 	=> $resulte['kd_mp'],  
                        'nm_mp' 	=> $resulte['nm_mp'] 
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
	
	function sumber_dana() {                      
		$sql = "SELECT kd_sd,nm_sd FROM sirup_sumber_dana";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_sd' 	=> $resulte['kd_sd'],  
                        'nm_sd' 	=> $resulte['nm_sd'] 
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
	
	function load_jenis_beban() {
		$result = array(( 
						array(
						"id"   => 1 ,
						"text" => " LS Bendahara (Transfer)"
						) 
					) ,
						( 
						array( 
					  "id"   => 2 ,
					  "text" => " Tambahan Penghasilan"
						) 
					),
						( 
						array( 
					  "id"   => 3 ,
					  "text" => " Uang Makan dan Minum Harian"
						) 
					),
						( 
						array( 
					  "id"   => 4 ,
					  "text" => " Insentif Pemungutan Pajak Daerah"
						) 
					),
						( 
						array( 
					  "id"   => 5 ,
					  "text" => " Insentif Pemungutan Retribusi Daerah"
						) 
					),
						( 
						array( 
					  "id"   => 6 ,
					  "text" => " LS Bendahara (Tunai)"
						) 
					),
						( 
						array( 
					  "id"   => 7 ,
					  "text" => " Tambahan/Kekurangan Gaji & Tunjangan"
						) 
					),
						( 
						array( 
					  "id"   => 8 ,
					  "text" => " Tunjangan Lainnya"
						) 
					),
						( 
						array( 
					  "id"   => 9 ,
					  "text" => " Gaji Pihak Ketiga"
						) 
					)
				);  
		 echo json_encode($result);
	 } 
	
function loadPenyedia() {
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$kd_skpd = $this->session->userdata('kdskpd');        
        $usernam = $this->session->userdata('pcNama');
        
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd,0,7);
        $dbidang = substr($bid,8,2);
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND ( upper(nm_paket) like upper('%$kriteria%')) ";            
        }
        
		$sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,(select a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select nama from ms_ttd where kd_skpd='$kd_skpd' and id=b.id_ppk) as ppk,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' and id=b.id) sumber_dana from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' $where and b.id not in (
				SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' $where order by cast(id as int)) order by cast(id as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
       		
        foreach($query1->result_array() as $resulte){ 
            $row[] = array(
                        'idx'          	=> $ii,        
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'tahun'   	    => $resulte['tahun'],
                        'nmskpd'    	=> $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                                                                
                        'kd_program'    => $resulte['kd_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'   	    => $resulte['ppk'],
                        'volume'   		=> $resulte['volume'],
                        'uraian'   		=> $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'   		=> $resulte['tkdn'],
                        'uk'   		    => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'   	=> $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'   		=> number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan'	=> $resulte['mtd_pengadaan'],
                        'pilih_awal'   	=> $resulte['pilih_awal'],
                        'pilih_akhir'  	=> $resulte['pilih_akhir'],
                        'kerja_mulai'	=> $resulte['kerja_mulai'],
                        'kerja_akhir'	=> $resulte['kerja_akhir'],
                        'aktif'			=> $resulte['aktif'],
                        'umumkan'   	=> $resulte['umumkan'],
                        'is_final'   	=> $resulte['is_final'],
                        'id_swakelola'	=> $resulte['id_swakelola'],
                        'no_renja'	    => $resulte['no_renja'],
                        'izin_tahun_jamak'	=> $resulte['izin_tahun_jamak']
                        
                        );
                        $ii++;
        }
		$result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
		}

    
    function loadSwakelola() {
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$kd_skpd = $this->session->userdata('kdskpd');
        $usernam = $this->session->userdata('pcNama');
        
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd,0,7);
        $dbidang = substr($bid,8,2);
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND ( upper(nm_paket) like upper('%$kriteria%')) ";            
        }

		$sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='2' and username='$usernam' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,(select a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nm_lokasi from sirup_lokasi where kd_lokasi like b.lokasi ) nm_lokasi,
                (select nama from ms_ttd where id=b.id_ppk) namappk,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and jenis_paket='2' and id=b.id) sumber_dana from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='2' $where and b.id not in (
				SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='2' $where order by cast(id as int)) order by cast(id as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
       		
        foreach($query1->result_array() as $resulte){ 
            $row[] = array(
                        'idx'          	=> $ii,        
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'tahun'   	    => $resulte['tahun'],
                        'nmskpd'    	=> $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                                                                
                        'kd_program'    => $resulte['kd_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'lokasi'       	=> json_decode($resulte['lokasi']),
                        'nm_lokasi'   	=> $resulte['nm_lokasi'],
                        'det_lokasi'   	=> $resulte['det_lokasi'],
                        'jns_pengadaan' => json_decode($resulte['jns_pengadaan']),
                        'volume'   		=> $resulte['volume'],
                        'uraian'   		=> $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'   		=> $resulte['tkdn'],
                        'uk'   		    => $resulte['uk'],
                        'pradipa'   	=> $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'   		=> number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan'	=> $resulte['mtd_pengadaan'],
                        'pilih_awal'   	=> $resulte['pilih_awal'],
                        'pilih_akhir'  	=> $resulte['pilih_akhir'],
                        'kerja_mulai'	=> $resulte['kerja_mulai'],
                        'kerja_akhir'	=> $resulte['kerja_akhir'],
                        'aktif'			=> $resulte['aktif'],
                        'user'          => $resulte['username'],
                        'namappk'       => $resulte['namappk'],
                        'idppk'       => $resulte['id_ppk'],                        
                        'umumkan'   	=> $resulte['umumkan'],
                        'id_swakelola'	=> $resulte['id_swakelola'],
                        'is_final'	    => $resulte['is_final'],
                        'tipe_swakelola'=> $resulte['tipe_swakelola'],
                        'nama_satker_lain'=> $resulte['nama_satker_lain']
                        );
                        $ii++;
        }
		$result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
		}
    
	function detailPenyedia() {
        $id 	= $this->input->post('id');
        $skpd 	= $this->input->post('skpd');
        $sql 	= "SELECT * FROM sirup_detail WHERE id='$id' AND kd_skpd='$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'idx'          	=> $ii,        
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'klpd'          => $resulte['klpd'],
                        'kd_paket'      => $resulte['kd_paket'],                        
                        'isi_paket'      => $resulte['isi_paket'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'   	=> $resulte['kd_rek5'],
                        'nm_rek5'   	=> $resulte['nm_rek5'],                        
                        'kd_sd'   		=> $resulte['kd_sd'],
                        'kd_ads'   		=> $resulte['kd_ads'],
                        'kd_ad'   		=> $resulte['kd_ad'],
                        'max'  			=> $resulte['mak'],
                        'pagu'   	    => $resulte['pagu'],
                        'vol'           => $resulte['vol'],
                        'tm_pagu'   	=> number_format($resulte['pagu'],2)
                        );
                        $ii++;
        }
		
        $query1->free_result();   
        echo json_encode($result);
		}
    
    function detailPenyedia_lokasi() {
        $id 	= $this->input->post('id');
        $skpd 	= $this->input->post('skpd');
        $sql 	= "SELECT a.id,a.kd_skpd,a.tahun,b.prov,b.nm_lokasi,a.det_lokasi FROM sirup_header a 
                   left join sirup_lokasi b on b.kd_lokasi = SUBSTRING(a.lokasi, 3, 1) where a.kd_skpd='$skpd' and a.id='$id'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'idx'          	=> $ii,        
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'prov'          => $resulte['prov'],
                        'nm_lokasi'      => $resulte['nm_lokasi'],                        
                        'det_lokasi'      => $resulte['det_lokasi']                        
                        );
                        $ii++;
        }
		
        $query1->free_result();   
        echo json_encode($result);
		}
	
	function urutPenyedia(){
		$usernam = $this->session->userdata('pcNama');
        $skpd = $this->session->userdata('kdskpd');
        	
		$sql_ 		= "select case when max(id+1) is null then 1 else max(id+1) end as nomor from sirup_header where  isnumeric(id)=1 and username='$usernam' and kd_skpd='$skpd'";		
		$data 		= $this->db->query($sql_);
		foreach($data->result_array() as $resulte){ 
            $result = array(
                        'no_urut' => $resulte['nomor']
                        );
        }
		
        echo json_encode($result);
    	$data->free_result();
	}
	
     function savePenyedia(){
        $cid    	= $this->input->post('cid');  
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');
        $cpaket    	= $this->input->post('cpaket');                                 
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');  
        $clok    	= json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd');  
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa    	= $this->input->post('cidswa');  
        $ctkdn    	= $this->input->post('ctkdn');
        $cuk    	= $this->input->post('cuk'); 
        $cnuk    	= $this->input->post('cnuk');  
        $cpra    	= $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum     	= $this->input->post('cumum');  
        $cdet     	= $this->input->post('cdet');  
		$now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username)
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','1','$usernm')";
		$asg = $this->db->query($sql);
		
		if ($asg){
			$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				$msg = array('pesan'=>'1');
				echo json_encode($msg);	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 }
	 
	 
	 
	function editPenyedia(){
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket    	= $this->input->post('cpaket');                            
        $clok    	= json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd');  
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa    	= $this->input->post('cidswa');  
        $ctkdn    	= $this->input->post('ctkdn');  
        $cuk    	= $this->input->post('cuk'); 
        $cnuk    	= $this->input->post('cnuk');         
        $cpra    	= $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum     	= $this->input->post('cumum');  
        $cdet     	= $this->input->post('cdet');  
		$now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		$sql1 = "DELETE FROM sirup_detail WHERE id='$cid' AND kd_skpd='$cskpd'";
		$sql2 = "DELETE FROM sirup_header WHERE id='$cid' AND kd_skpd='$cskpd' and jenis_paket='1'";
		$asg1 = $this->db->query($sql1);
		$asg2 = $this->db->query($sql2);
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username)
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','1','$usernm')";
		$asg = $this->db->query($sql);
		
		if ($asg){
			$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				$msg = array('pesan'=>'1');
				echo json_encode($msg);	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 }
	//
    
    function saveSwakelola(){
        $cid    	= $this->input->post('cid');  
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');
        $cpaket    	= $this->input->post('cpaket');                                 
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');  
        $clok    	= json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd');  
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa    	= $this->input->post('cidswa');  
        $ctkdn    	= $this->input->post('ctkdn');
        $cuk    	= $this->input->post('cuk'); 
        $cnuk    	= $this->input->post('cnuk');  
        $cpra    	= $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum     	= $this->input->post('cumum');  
        $cdet     	= $this->input->post('cdet');  
		$now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username)
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','2','$usernm')";
		$asg = $this->db->query($sql);
		
		if ($asg){
			$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				$msg = array('pesan'=>'1');
				echo json_encode($msg);	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 }
	 
	 
	 
	function editSwakelola(){
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket    	= $this->input->post('cpaket');                            
        $clok    	= json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd');  
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa    	= $this->input->post('cidswa');  
        $ctkdn    	= $this->input->post('ctkdn');  
        $cuk    	= $this->input->post('cuk');  
        $cnuk    	= $this->input->post('cnuk');          
        $cpra    	= $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum     	= $this->input->post('cumum');  
        $cdet     	= $this->input->post('cdet');  
		$now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		$sql1 = "DELETE FROM sirup_detail WHERE id='$cid' AND kd_skpd='$cskpd'";
		$sql2 = "DELETE FROM sirup_header WHERE id='$cid' AND kd_skpd='$cskpd' and jenis_paket='2'";
		$asg1 = $this->db->query($sql1);
		$asg2 = $this->db->query($sql2);
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username)
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','2','$usernm')";
		$asg = $this->db->query($sql);
		
		if ($asg){
			$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				$msg = array('pesan'=>'1');
				echo json_encode($msg);	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 }
	
    
    //
    /*
    function saveSwakelola(){
        $cid    	= $this->input->post('cid');  
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');
        $cpaket    	= $this->input->post('cpaket');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');  
        $clok    	= json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd');  
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa    	= $this->input->post('cidswa');  
        $ctkdn    	= $this->input->post('ctkdn');  
        $cpra    	= $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum     	= $this->input->post('cumum');  
        $cdet     	= $this->input->post('cdet');  
		$now 		= date('Y-m-d H:i:s');
		
		
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,'jenis_paket')
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','2')";
		$asg = $this->db->query($sql);
		
		if ($asg){
			$sql = "INSERT INTO sirup_detail(id,kd_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,pagu) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				$msg = array('pesan'=>'1');
				echo json_encode($msg);	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 }
	 
	 
	 
	function editSwakelola(){
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket    	= $this->input->post('cpaket');  
        $clok    	= json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd');  
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa    	= $this->input->post('cidswa');  
        $ctkdn    	= $this->input->post('ctkdn');  
        $cpra    	= $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum     	= $this->input->post('cumum');  
        $cdet     	= $this->input->post('cdet');  
		$now 		= date('Y-m-d H:i:s');
		
		$sql1 = "DELETE FROM sirup_detail WHERE id='$cid' AND kd_skpd='$cskpd'";
		$sql2 = "DELETE FROM sirup_header WHERE id='$cid' AND kd_skpd='$cskpd'";
		$asg1 = $this->db->query($sql1);
		$asg2 = $this->db->query($sql2);
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket)
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','2')";
		$asg = $this->db->query($sql);
		
		if ($asg){
			$sql = "INSERT INTO sirup_detail(id,kd_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,pagu) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				$msg = array('pesan'=>'1');
				echo json_encode($msg);	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 } 
	 */
	 
	 function hapusPenyedia() {    	
		$no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');        
        $query = $this->db->query("delete from sirup_detail where id='$no' and kd_skpd='$skpd' ");
        $query = $this->db->query("delete from sirup_header where id='$no' and kd_skpd='$skpd' ");
    }
    
    function cetak_listpenyedia(){
		$kd_skpd = $this->session->userdata('kdskpd');        
        //$this->tanggal_format_indonesia($tgl);       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>LIST PAKET PENYEDIA</b></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "SELECT b.*,(select a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd
                    from sirup_header b                   
                   where b.kd_skpd='$kd_skpd' and b.jenis_paket='1'";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;  
                        $cid       = $row->id;         
                        $ctahun    = $row->tahun;                        
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnm_paket = $row->nm_paket;
                        $cada1     = str_replace("[","",$row->jns_pengadaan);
                        $cada2     = str_replace("]","",$cada1);  
                        $cada3     = str_replace(" ","",$cada2); 
                        $cada4     = str_replace('"',"'",$cada3);                    
                        $sqlada="select nm_jp from sirup_jenis_pengadaan where kd_jp in ($cada4)";
	                    $ada3=$this->db->query($sqlada);$ada2=$ada3->row();
		                $jns_peng=$ada2->nm_jp;
                        $cvolume   = $row->volume;
                        $clokasi1  = str_replace("[","",$row->lokasi);
                        $clokasi2  = str_replace("]","",$clokasi1);  
                        $clokasi3  = str_replace(" ","",$clokasi2); 
                        $clokasi4  = str_replace('"',"'",$clokasi3);                    
                        $total     = number_format($row->total,2);                           
                        $sqllokasi="select nm_lokasi from sirup_lokasi where kd_lokasi in ($clokasi4)";
	                    $lokasi3=$this->db->query($sqllokasi);$lokasi2=$lokasi3->row();
		                $lokasi=$lokasi2->nm_lokasi;
                        $cdlokasi  = $row->det_lokasi;
                        $curaian   = $row->uraian;
		                $cspesif   = $row->spesifikasi;
                        $cmtdpeng  = $row->mtd_pengadaan;
                        $sqlmp="select nm_mp from sirup_metode_pengadaan where kd_mp in ('$cmtdpeng')";
	                    $mp3=$this->db->query($sqlmp);$mp2=$mp3->row();
		                $metodepeng=$mp2->nm_mp;
                        $cpawal    =$row->pilih_awal;
                        $cpawal2   =$this->ambil_bulan($cpawal);
                        $cpawal3   =$this->pilih_bulan($cpawal2);
                        
                        $cpakhir   =$row->pilih_akhir;
                        $cpakhir2   =$this->ambil_bulan($cpakhir);
                        $cpakhir3   =$this->pilih_bulan($cpakhir2);
                        
                        $ckawal    =$row->kerja_mulai;
                        $ckawal2   =$this->ambil_bulan($ckawal);
                        $ckawal3   =$this->pilih_bulan($ckawal2);
                        
                        $ckakhir   =$row->kerja_akhir;
                        $ckakhir2   =$this->ambil_bulan($ckakhir);
                        $ckakhir3   =$this->pilih_bulan($ckakhir2);
                                                  
            $cRet .="
                    <tr>
                    <td colspan=\"5\" valign=\"top\" width=\"95%\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black;\">&nbsp;&nbsp;<b>&#8711; &nbsp;".$cnm_paket."</b></td>
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tahun Anggaran</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Kegiatan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cnmkeg."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Jenis Pengadaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$jns_peng."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Volume</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cvolume."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Lokasi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$lokasi."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Detail Lokasi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cdlokasi."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Deskripsi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$curaian."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Spesifikasi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cspesif."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Rincian</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    ";   
           $sqld = "SELECT a.* from sirup_detail a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.kd_kegiatan='$ckdkeg'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
                    {
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;border-top:solid 1px black;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$rowd->kd_sd."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;border-top:none;\">Asal Dana Satker</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".strtoupper($this->tukd_model->get_nama($rowd->kd_ads,'nm_skpd','ms_skpd','kd_skpd'))."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Kode</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$rowd->mak."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Uraian</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$rowd->isi_paket."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;border-bottom:solid 1px black;\">Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($rowd->pagu,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                                        
                    ";              
                    }
                    
           $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pemilihan Penyedia</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$metodepeng."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Awal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cpawal3."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Akhir</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cpakhir3."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Waktu Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">&nbsp;</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Awal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ckawal3."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Akhir</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ckakhir3."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Total Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($row->total,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    
                    ";
                $tot_pagu=$tot_pagu+$row->total;
             }
                         
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                                                                         
            </table>";    
            
        $data['prev']= $cRet;    
        //echo $cRet;
        $this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }
    
        function cetak_listswakelola(){
		$kd_skpd = $this->session->userdata('kdskpd');        
        //$this->tanggal_format_indonesia($tgl);       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>LIST SWAKELOLA</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "SELECT b.*,(select a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                   a.* from sirup_header b inner join sirup_detail a on a.id=b.id and a.kd_skpd=b.kd_skpd and a.kd_kegiatan=b.kd_kegiatan                   
                   where b.kd_skpd='$kd_skpd' and b.jenis_paket='2'";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;  
                        $cid       = $row->id;         
                        $ctahun    = $row->tahun;                        
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnm_paket = $row->nm_paket;
                        $cada1     = str_replace("[","",$row->jns_pengadaan);
                        $cada2     = str_replace("]","",$cada1);  
                        $cada3     = str_replace(" ","",$cada2); 
                        $cada4     = str_replace('"',"'",$cada3);                    
                        $cvolume   = $row->volume;
                        $clokasi1  = str_replace("[","",$row->lokasi);
                        $clokasi2  = str_replace("]","",$clokasi1);  
                        $clokasi3  = str_replace(" ","",$clokasi2); 
                        $clokasi4  = str_replace('"',"'",$clokasi3);                    
                        $total     = number_format($row->total,2);                           
                        $sqllokasi="select nm_lokasi from sirup_lokasi where kd_lokasi in ($clokasi4)";
	                    $lokasi3=$this->db->query($sqllokasi);$lokasi2=$lokasi3->row();
		                $lokasi=$lokasi2->nm_lokasi;
                        $cdlokasi  = $row->det_lokasi;
                        $curaian   = $row->uraian;
		                $cspesif   = $row->spesifikasi;
                        $cmtdpeng  = $row->mtd_pengadaan;
                        $cpawal    =$row->pilih_awal;
                        $cpawal2   =$this->ambil_bulan($cpawal);
                        $cpawal3   =$this->pilih_bulan($cpawal2);
                        
                        $cpakhir   =$row->pilih_akhir;
                        $cpakhir2   =$this->ambil_bulan($cpakhir);
                        $cpakhir3   =$this->pilih_bulan($cpakhir2);
                        
                        $ckawal    =$row->kerja_mulai;
                        $ckawal2   =$this->ambil_bulan($ckawal);
                        $ckawal3   =$this->pilih_bulan($ckawal2);
                        
                        $ckakhir   =$row->kerja_akhir;
                        $ckakhir2   =$this->ambil_bulan($ckakhir);
                        $ckakhir3   =$this->pilih_bulan($ckakhir2);
                                                  
            $cRet .="
                    <tr>
                    <td colspan=\"5\" valign=\"top\" width=\"95%\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black;\">&nbsp;&nbsp;<b>&#8711; &nbsp;".$cnm_paket."</b></td>
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tahun Anggaran</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Kegiatan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cnmkeg."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Volume</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cvolume."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Lokasi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$lokasi."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Detail Lokasi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cdlokasi."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Rincian</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    ";   
                    
           $sqld = "SELECT a.* from sirup_detail a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.kd_kegiatan='$ckdkeg'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
                    {
           
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;border-top:solid 1px black;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$rowd->kd_sd."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;border-top:none;\">Asal Dana Satker</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".strtoupper($this->tukd_model->get_nama($rowd->kd_ads,'nm_skpd','ms_skpd','kd_skpd'))."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Kode</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$rowd->mak."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Uraian</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$rowd->nm_rek5."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;border-bottom:solid 1px black;\">Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($rowd->pagu,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                                        
                    ";              
                    }
                    
           $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">&nbsp;</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Waktu Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">&nbsp;</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Awal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ckawal3."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Akhir</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ckakhir3."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"8%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pagu Kegiatan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($row->total,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    
                    ";
                $tot_pagu=$tot_pagu+$row->total;
             }
                         
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                                                                         
            </table>";    
            
        $data['prev']= $cRet;    
        //echo $cRet;
        $this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }
    
	function cetak_listppk(){
        $kd_skpd = $this->uri->segment(4);
        
        if($kd_skpd==""){
		$kd_skpd = $this->session->userdata('kdskpd');}        
        //$this->tanggal_format_indonesia($tgl);       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>LIST KEGIATAN BERDASARKAN PPK</b></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">";
           
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px;\"><b>NO</b></td>                    
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;\"><b>KEGIATAN</b></td>
                    <td valign=\"top\" align=\"center\" width=\"40%\" style=\"font-size:11px;\"><b>URAIAN</b></td>
                    <td valign=\"top\" align=\"center\" width=\"35%\" style=\"font-size:11px;\"><b>NAMA PPK</b></td>
                    </tr>
                    
                    ";
            
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "select a.kd_kegiatan,a.nm_kegiatan,(select top 1 nama from ms_ttd where nip=a.nip and jns='rup') ppk from m_giat_rup a 
                   where a.kd_kegiatan like ('%$kd_skpd%') order by a.kd_kegiatan";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no+1;  
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnm_ppk   = $row->ppk;
                                                  
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px;\">$no</td>                    
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;\">$ckdkeg</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;\">$cnmkeg</td>
                    <td valign=\"top\" align=\"left\" width=\"35%\" style=\"font-size:11px;\">$cnm_ppk</td>
                    </tr>
                    
                    ";
                
             }
                         
            $cRet .="                                                                         
            </table>";    
            
        $data['prev']= $cRet;    
        //echo $cRet;
        $this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }
    
	
     function _mpdf_margin($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='',$atas='', $bawah='', $kiri='', $kanan='') {
                

        ini_set("memory_limit","-1M");
        ini_set("MAX_EXECUTION_TIME","-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        
        
        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        $sa=1;
        $tes=0;
        if ($hal==''){
        $hal1=1;
        } 
        if($hal!==''){
        $hal1=$hal;
        }
        if ($fonsize==''){
        $size=12;
        }else{
        $size=$fonsize;
        } 
        
        $this->mpdf = new mPDF('utf-8', array(215,330),$size); //folio
        $this->mpdf->AddPage($orientasi,'',$hal,'1','off',$kiri,$kanan,$atas,$bawah);
        if ($hal==''){
            $this->mpdf->SetFooter("");
        }
        else{
            $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output();
               
    }
	 
}