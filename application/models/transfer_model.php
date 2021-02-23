<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 
 */

class transfer_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	// Tampilkan semua master data kegiatan
    
    function transfer_sirup1(){
		
		$hasil='';
		$csql = "select a.kd_skpd,a.nm_skpd,b.kd_program,b.nm_program,a.kd_kegiatan,b.nm_kegiatan,isnull(b.lokasi,'') [lokasi],
                isnull(b.waktu_giat,'') [waktu_kegiatan],
				sum(nilai) [angg_penyusunan],sum(nilai_ubah) [angg_perubahan] 
				from  trdrka a join trskpd b on a.kd_kegiatan=b.kd_kegiatan 
				group by a.kd_skpd,a.nm_skpd,b.kd_program,b.nm_program,a.kd_kegiatan,b.nm_kegiatan,b.lokasi,b.waktu_giat
				order by a.kd_skpd,b.kd_program,a.kd_kegiatan,b.lokasi ";
		$hasil = $this->db->query($csql);  
		return $hasil;
	}

	function transfer_sirup2($jns_angg,$volume,$harga,$total){
		
		$kdskpd = '1.20.03.12';
		$hasil='';
		$csql = "select * from(
					select  a.kd_skpd,replace(b.kd_program,'.','') [kd_program],'' [nm_program],replace(a.kd_kegiatan,'.','') [kd_kegiatan],'' [nm_kegiatan],f.kd_rek3 [kd_rek5],f.nm_rek3 [nm_rek5],'' [lokasi],'' [waktu_kegiatan],
					sum($jns_angg) [angg],'' [uraian],0 [volume],0 [harga],0 [total]
					from  trdrka a join trskpd b on a.kd_kegiatan=b.kd_kegiatan 
					join ms_rek3 f on left(a.kd_rek5,3)=f.kd_rek3
					where a.kd_skpd='$kdskpd'
					group by a.kd_skpd,a.nm_skpd,b.kd_program,b.nm_program,a.kd_kegiatan,b.nm_kegiatan,b.lokasi,b.waktu_giat,f.kd_rek3,f.nm_rek3
					union all
					-------------rek4
					select  a.kd_skpd,replace(b.kd_program,'.','') [kd_program],'' [nm_program],replace(a.kd_kegiatan,'.','') [kd_kegiatan],'' [nm_kegiatan],f.kd_rek4 [kd_rek5],f.nm_rek4 [nm_rek5],'' [lokasi],
					'' [waktu_kegiatan],sum($jns_angg) [angg],'' [uraian],0 [volume],0 [harga],0 [total]
					from  trdrka a join trskpd b on a.kd_kegiatan=b.kd_kegiatan 
					join ms_rek4 f on left(a.kd_rek5,5)=f.kd_rek4
					where a.kd_skpd='$kdskpd'
					group by a.kd_skpd,a.nm_skpd,b.kd_program,b.nm_program,a.kd_kegiatan,b.nm_kegiatan,b.lokasi,b.waktu_giat,f.kd_rek4,f.nm_rek4
					union all
					--------rek5
					select  a.kd_skpd,replace(b.kd_program,'.','') [kd_program],b.nm_program,replace(a.kd_kegiatan,'.','') [kd_kegiatan],b.nm_kegiatan,a.kd_rek5,a.nm_rek5,isnull(b.lokasi,'') [lokasi],
					isnull(b.waktu_giat,'') [waktu_kegiatan],sum($jns_angg) [angg],'' [uraian],0 [volume],0 [harga],0 [total]
					from  trdrka a join trskpd b on a.kd_kegiatan=b.kd_kegiatan 
					where a.kd_skpd='$kdskpd'
					group by a.kd_skpd,a.nm_skpd,b.kd_program,b.nm_program,a.kd_kegiatan,b.nm_kegiatan,b.lokasi,b.waktu_giat,a.kd_rek5,a.nm_rek5
					union all
					--------rincian
					select '' [kd_skpd],'' [kd_program],'' [nm_program],replace(SUBSTRING(c.no_trdrka,12,21),'.','') [kd_kegiatan],'' [nm_kegiatan],RIGHT(c.no_trdrka,7)+CONVERT(varchar(10), no_po) [kd_rek5], 
					'' [nm_rek5],'' [lokasi],'' [waktu_kegiatan],0 [angg],uraian, $volume [volume], $harga [harga],$total [total]
					from trdpo c join trdrka d on c.no_trdrka=d.no_trdrka where d.kd_skpd='$kdskpd'
				) as e order by kd_kegiatan,kd_rek5";
		$hasil = $this->db->query($csql);  
		return $hasil;
	}

	
	function program($jns_angg,$tahun,$satker){
	   	
        if($tahun=="2017"){
            $dbvar ="simakdakota";
        }else{
            $dbvar ="simakda";
        }
        
        $dbvar_ = $dbvar.'_'.$tahun;
		
		$hasil='';
		$csql = "select b.kd_program [kd_program],left(a.kd_skpd,7) as kd_skpd,rtrim(b.nm_program) [nm_program],sum($jns_angg) [angg]
					from $dbvar_.dbo.trdrka a join $dbvar_.dbo.trskpd b on a.kd_kegiatan=b.kd_kegiatan 
                    where left(a.kd_skpd,7)='$satker'
					group by left(a.kd_skpd,7),b.kd_program,b.nm_program
					order by left(a.kd_skpd,7),b.kd_program";
		$db_debug = $this->db->db_debug;
        $this->db->db_debug=FALSE;                          		
        $hasil = $this->db->query($csql);  
		return $hasil;
	}

	function kegiatan($jns_angg,$tahun,$satker){
		
		
        if($tahun=="2017"){
            $dbvar ="simakdakota";
        }else{
            $dbvar ="simakda";
        }
        
        
        $dbvar_ = $dbvar.'_'.$tahun;
        
		$hasil='';
		$csql = "                
                select b.kd_program [kd_program],left(a.kd_skpd,7) as kd_skpd,rtrim(b.nm_program) [nm_program],
				a.kd_kegiatan [kd_kegiatan],
				rtrim(a.nm_kegiatan) [nm_kegiatan],sum($jns_angg) [angg]
				from $dbvar_.dbo.trdrka a join $dbvar_.dbo.trskpd b on a.kd_kegiatan=b.kd_kegiatan 
                where left(a.kd_skpd,7)='$satker'
				group by left(a.kd_skpd,7),b.kd_program,b.nm_program,a.kd_kegiatan,a.nm_kegiatan
				order by left(a.kd_skpd,7),b.kd_program,a.kd_kegiatan                  
                ";		  
        $db_debug = $this->db->db_debug;
        $this->db->db_debug=FALSE;                        
  		$hasil = $this->db->query($csql);        
        return $hasil;        
	}	
	
    function pagu($jns_angg,$tahun,$satker){
		
		
        if($tahun=="2017"){
            $dbvar ="simakdakota";
        }else{
            $dbvar ="simakda";
        }
        
        $dbvar_ = $dbvar.'_'.$tahun;
        
		$hasil='';
		$csql = "                
                select b.kd_program [kd_program],left(a.kd_skpd,7) as kd_skpd,
                (select nm_skpd from ms_skpd where kd_skpd=left(a.kd_skpd,7)+'.00') [nm_skpd], 
                rtrim(b.nm_program) [nm_program],a.kd_kegiatan [kd_kegiatan],
				rtrim(a.nm_kegiatan) [nm_kegiatan],
				a.kd_rek5 [kd_rek],a.nm_rek5 [uraian],
				(select nm_rek3 from ms_rek3 where kd_rek3=left(a.kd_rek5,3)) [jenis],								
				sum($jns_angg) [angg]
				from $dbvar_.dbo.trdrka a join $dbvar_.dbo.trskpd b on a.kd_kegiatan=b.kd_kegiatan 
                where left(a.kd_skpd,7)='$satker'
				group by left(a.kd_skpd,7),b.kd_program,b.nm_program,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5
				order by left(a.kd_skpd,7),b.kd_program,a.kd_kegiatan,a.kd_rek5                
                ";		  
        $db_debug = $this->db->db_debug;
        $this->db->db_debug=FALSE;                        
  		$hasil = $this->db->query($csql);        
        return $hasil;        
	}
    
    function realisasi($jn_bulan,$tahun,$satker){
		
		
        if($tahun=="2017"){
            $dbvar ="simakdakota";
        }else{
            $dbvar ="simakda";
        }
        
        $dbvar_ = $dbvar.'_'.$tahun;
        
		$hasil='';
		$csql = "                
                select left(kd_skpd,7) [kd_skpd],kd_kegiatan [kd_kegiatan],
                nm_kegiatan [nm_kegiatan],nm_rek [nm_rek],real_spj [real] from $dbvar_.dbo.Data_realisasi_keg4_sirup($jn_bulan,2)
                where left(kd_skpd,7)='$satker'                               
                order by left(kd_skpd,7),kd_kegiatan
                ";		  
        $db_debug = $this->db->db_debug;
        $this->db->db_debug=FALSE;                        
  		$hasil = $this->db->query($csql);        
        return $hasil;        
	}
    
	function anggaran_kas($jns_angg,$tahun){
		$kdskpd = '1.20.03.12';
		$database = 'simakda_'.$tahun;
		$hasil='';
		$csql = "select kd_skpd,replace(kd_kegiatan,'.','') [kd_kegiatan], 
				 sum(case when bulan='1' then nilai_sempurna else 0 end) [jan],
				 sum(case when bulan='2' then nilai_sempurna else 0 end) [feb], 
				 sum(case when bulan='3' then nilai_sempurna else 0 end) [mar], 
				 sum(case when bulan='4' then nilai_sempurna else 0 end) [apr], 
				 sum(case when bulan='5' then nilai_sempurna else 0 end) [mei], 
				 sum(case when bulan='6' then nilai_sempurna else 0 end) [jun], 
				 sum(case when bulan='7' then nilai_sempurna else 0 end) [jul], 
				 sum(case when bulan='8' then nilai_sempurna else 0 end) [agu], 
				 sum(case when bulan='9' then nilai_sempurna else 0 end) [sep], 
				 sum(case when bulan='10' then nilai_sempurna else 0 end) [okt], 
				 sum(case when bulan='11' then nilai_sempurna else 0 end) [nov], 
				 sum(case when bulan='12' then nilai_sempurna else 0 end) [des]
				 from $database.dbo.trdskpd where kd_skpd='$kdskpd'
				 group by kd_skpd,kd_kegiatan order by kd_kegiatan";
		$db_debug = $this->db->db_debug; 
		$this->db->db_debug = FALSE;
		$hasil = $this->db->query($csql);  
		return $hasil;
	}		
}