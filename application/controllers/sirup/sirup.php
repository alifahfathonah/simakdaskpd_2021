<?php

/**
 * @author Boomer
 * @copyright 2018
 */



?><?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sirup extends CI_Controller {

	function __contruct(){	
		parent::__construct();
	}     
	
    function input_penyedia(){
        $data['page_title']= 'INPUT PENYEDIA';
        $this->template->set('title', 'INPUT PENYEDIA');   
        $this->template->load('template','anggaran/rup/input_penyedia',$data) ; 
    }    
    
    function input_penyedia_myears(){
        $data['page_title']= 'INPUT PENYEDIA MULTIYEARS';
        $this->template->set('title', 'INPUT PENYEDIA MULTIYEARS');   
        $this->template->load('template','anggaran/rup/input_penyedia_myears',$data) ; 
    }     

    function input_swakelola(){
        $data['page_title']= 'INPUT SWAKELOLA';
        $this->template->set('title', 'INPUT SWAKELOLA');   
        $this->template->load('template','anggaran/rup/input_swakelola',$data) ; 
    }

	function input_final_penyedia(){
        $data['page_title']= 'INPUT FINALISASI PENYEDIA';
        $this->template->set('title', 'INPUT FINALISASI PENYEDIA');   
        $this->template->load('template','anggaran/rup/input_final_penyedia',$data) ; 
    }

	function input_final_swakelola(){
        $data['page_title']= 'INPUT FINALISASI SWAKELOLA';
        $this->template->set('title', 'INPUT FINALISASI SWAKELOLA');   
        $this->template->load('template','anggaran/rup/input_final_swakelola',$data) ; 
    }	
    
	function input_umumkan_penyedia(){
        $data['page_title']= 'INPUT UMUMKAN PENYEDIA';
        $this->template->set('title', 'INPUT UMUMKAN PENYEDIA');   
        $this->template->load('template','anggaran/rup/input_umumkan_penyedia',$data) ; 
    }
	
	function input_umumkan_swakelola(){
        $data['page_title']= 'INPUT UMUMKAN SWAKELOLA';
        $this->template->set('title', 'INPUT UMUMKAN SWAKELOLA');   
        $this->template->load('template','anggaran/rup/input_umumkan_swakelola',$data) ; 
    }

    function input_revisi_penyedia(){
        $data['page_title']= 'INPUT REVISI PENYEDIA';
        $this->template->set('title', 'INPUT REVISI PENYEDIA');   
        $bidang = $this->session->userdata('bidang');
        if($bidang==6){
            $this->template->load('template','anggaran/rup/input_revisi_penyedia_ppkom',$data) ;
        }else{
            $this->template->load('template','anggaran/rup/input_revisi_penyedia_pa',$data) ;
        }         
    }

    function input_revisi_swakelola(){
        $data['page_title']= 'INPUT REVISI SWAKELOLA';
        $this->template->set('title', 'INPUT REVISI SWAKELOLA');   
        $bidang = $this->session->userdata('bidang');
        if($bidang==6){
            $this->template->load('template','anggaran/rup/input_revisi_swakelola_ppkom',$data) ;
        }else{
            $this->template->load('template','anggaran/rup/input_revisi_swakelola_pa',$data) ;
        }         
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
                        'tahun' => '2021'
                        );
                           
       echo json_encode($result);            
	}

    function listTahun_Myears(){                      
        $skpd  = $this->input->post('skpd');
        $result = array();

        for($i=2021;$i<=2023;$i++){

            $result[] = array(
                        'tahun' => $i
                        );
        }
                           
       echo json_encode($result);            
    }
	
    function listKegiatan(){                      
        $skpd  = $this->input->post('skpd');
        $ntahun= $this->input->post('tahun');
        $usern = $this->session->userdata('pcNama');
        
        $lccr  = $this->input->post('q');
		/*$sql   ="
                SELECT d.kd_kegiatan,d.nm_kegiatan,d.nilai,d.kd_program,d.nilai_sirup,d.nip,d.id,d.nama,d.username from(
                SELECT a.kd_kegiatan,a.nm_kegiatan,a.nilai,b.kd_program,
                (SELECT isnull(sum(b.pagu),0) from sirup_detail b left join sirup_header d on d.id=b.id and d.username=b.username where (d.is_deleted<>1 or d.is_deleted is null) and b.kd_kegiatan=a.kd_kegiatan and b.tahun='$ntahun') nilai_sirup,
                c.nip,c.id,c.nama,c.username                                  
                FROM 
				(select kd_kegiatan,nm_kegiatan,SUM(nilai_ubah) nilai FROM trdrka WHERE left(kd_skpd,7)=left('$skpd',7)
				GROUP BY kd_kegiatan,nm_kegiatan)a 
				LEFT JOIN trskpd b ON a.kd_kegiatan=b.kd_kegiatan 
                LEFT JOIN (
                select a.kd_kegiatan,b.id as nip,b.id,b.nama,a.username from ms_sub_kegiatan a left join ms_ttd b on a.username=b.username
                where b.kd_skpd='$skpd' and b.jns='rup' 
                )c ON c.kd_kegiatan=a.kd_kegiatan
                )d where d.username='$usern' and (upper(d.kd_kegiatan) like upper('%$lccr%') or upper(d.nm_kegiatan) like upper('%$lccr%'))
                order by d.kd_kegiatan";*/

        $sql  ="SELECT d.kd_sub_kegiatan,d.nm_sub_kegiatan,d.nilai,d.kd_program,d.nilai_sirup,d.nip,d.id,d.nama,d.username,d.kode_lokasi from(
            SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.nilai,b.kd_program,
            (SELECT isnull(sum(b.pagu),0) from sirup_detail b left join sirup_header d on d.id=b.id and d.username=b.username where (d.is_deleted<>1 or d.is_deleted is null) and b.kd_kegiatan=a.kd_sub_kegiatan and b.tahun='2021') nilai_sirup,
            c.nip,c.id,c.nama,c.username,c.kode_lokasi                                  
            FROM 
            (select kd_sub_kegiatan,nm_sub_kegiatan,SUM(nilai_ubah) nilai FROM trdrka WHERE left(kd_skpd,17)=left('$skpd',17)
            GROUP BY kd_sub_kegiatan,nm_sub_kegiatan)a 
            LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            LEFT JOIN (
            select a.kd_sub_kegiatan,b.id_ttd as nip,b.id_ttd as id,b.nama,a.username,a.kd_skpd as kode_lokasi from ms_sub_kegiatan_rup a left join ms_ttd b on a.username=b.username
            where left(b.kd_skpd,17)=left('$skpd',17) and b.jns='rup' 
            )c ON c.kd_sub_kegiatan=a.kd_sub_kegiatan
            )d where d.username='$usern' and (upper(d.kd_sub_kegiatan) like upper('%$lccr%') or upper(d.nm_sub_kegiatan) like upper('%$lccr%'))
            group by d.kd_sub_kegiatan,d.nm_sub_kegiatan,d.nilai,d.kd_program,d.nilai_sirup,d.nip,d.id,d.nama,d.username,d.kode_lokasi
            order by d.kd_sub_kegiatan";


		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_kegiatan' 	=> $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' 	=> $resulte['nm_sub_kegiatan'],  
                        'nilai' 		=> number_format($resulte['nilai'],"2",".",","),
                        'nilai_sirup' 	=> number_format($resulte['nilai_sirup'],"2",".",","),
                        'kd_program' 	=> $resulte['kd_program'],
                        'nip' 	=> $resulte['nip'],
                        'id' 	=> $resulte['id'],
                        'nama' 	=> $resulte['nama'],
                        'username' 	=> $resulte['username'],
                        'kode_lokasi'  => $resulte['kode_lokasi']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}

    function listKegiatan_mYears(){                      
        $skpd  = $this->input->post('skpd');
        $ntahun= $this->input->post('tahun');
        $usern = $this->session->userdata('pcNama');
        
        $lccr  = $this->input->post('q');
        
        $sql  ="SELECT d.kd_sub_kegiatan,d.nm_sub_kegiatan,d.nilai,d.kd_program,d.nilai_sirup,d.nip,d.id,d.nama,d.username,d.kode_lokasi from(
            SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.nilai,b.kd_program,
            (SELECT isnull(sum(b.pagu),0) from sirup_detail b left join sirup_header d on d.id=b.id and d.username=b.username where (d.is_deleted<>1 or d.is_deleted is null) and b.kd_kegiatan=a.kd_sub_kegiatan and b.tahun='2021') nilai_sirup,
            c.nip,c.id,c.nama,c.username,c.kode_lokasi                                  
            FROM 
            (
                select a.kd_sub_kegiatan,a.nm_sub_kegiatan,SUM(b.nilai_ubah+a.nilai_ubah) nilai 
                FROM trdrka_n1 a left join trdrka b on b.kd_sub_kegiatan=a.kd_sub_kegiatan
                WHERE left(a.kd_skpd,7)=left('$skpd',7)
                GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan
            )a 
            LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            LEFT JOIN (
            select a.kd_sub_kegiatan,b.id_ttd as nip,b.id_ttd as id,b.nama,a.username,a.kd_skpd as kode_lokasi from ms_sub_kegiatan_rup a left join ms_ttd b on a.username=b.username
            where left(b.kd_skpd,17)=left('$skpd',17) and b.jns='rup' 
            )c ON c.kd_sub_kegiatan=a.kd_sub_kegiatan
            )d where d.username='$usern' and (upper(d.kd_sub_kegiatan) like upper('%$lccr%') or upper(d.nm_sub_kegiatan) like upper('%$lccr%'))
            group by d.kd_sub_kegiatan,d.nm_sub_kegiatan,d.nilai,d.kd_program,d.nilai_sirup,d.nip,d.id,d.nama,d.username,d.kode_lokasi
            order by d.kd_sub_kegiatan";


        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],  
                        'nilai'         => number_format($resulte['nilai'],"2",".",","),
                        'nilai_sirup'   => number_format($resulte['nilai_sirup'],"2",".",","),
                        'kd_program'    => $resulte['kd_program'],
                        'nip'   => $resulte['nip'],
                        'id'    => $resulte['id'],
                        'nama'  => $resulte['nama'],
                        'username'  => $resulte['username'],
                        'kode_lokasi'  => $resulte['kode_lokasi']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }

    function listKegiatan_pa() {                      
        $skpd  = $this->input->post('skpd');
        //$kegg  = $this->input->post('kd_keg');
        $lccr  = $this->input->post('q');
        
        $sql = "SELECT a.kd_kegiatan,a.nm_kegiatan FROM                
                trdrka a where left(a.kd_skpd,7)=left('$skpd',7) and
                (upper(a.kd_kegiatan) like upper('%$lccr%') or upper(a.nm_kegiatan) like upper('%$lccr%'))
                group by a.kd_kegiatan,a.nm_kegiatan
                ";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],  
                        'nm_kegiatan'   => $resulte['nm_kegiatan']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }

    function listRekening() {                      
        $skpd  = $this->input->post('skpd');
		$kegg  = $this->input->post('kd_keg');
		$lccr  = $this->input->post('q');
        
        $sql = "SELECT a.kd_rek6 as kd_rek5,a.nm_rek6 as nm_rek5,a.sumber,sum(a.nilai_ubah) nilai FROM 				
				trdrka a where a.kd_sub_kegiatan='$kegg' and a.kd_skpd='$skpd' and
                (upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%'))
                group by a.kd_rek6,a.nm_rek6,a.sumber
                ";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_lokasi' => $skpd,  
                        'kd_rek5' 	=> $resulte['kd_rek5'],  
                        'nm_rek5' 	=> $resulte['nm_rek5'],  
                        'nilai' 	=> number_format($resulte['nilai'],"2",".",","),
                        'sumber' 	=> $resulte['sumber']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}

    function listRekening_master() {                      
        $skpd  = $this->input->post('skpd');
        $kegg  = $this->input->post('kd_keg');
        $lccr  = $this->input->post('q');
        
        $sql = "SELECT top 100 a.kd_rek6 as kd_rek5,a.nm_rek6 as nm_rek5,'APBD' as sumber,'300000000000' nilai FROM               
                ms_rek6 a where 
                (upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%'))
                ";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_rek5'   => $resulte['kd_rek5'],  
                        'nm_rek5'   => $resulte['nm_rek5'],  
                        'nilai'     => number_format($resulte['nilai'],"2",".",","),
                        'sumber'    => $resulte['sumber']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }

    function listRekening_mYears() {                      
        $skpd  = $this->input->post('skpd');
        $kegg  = $this->input->post('kd_keg');
        $lccr  = $this->input->post('q');
        
        $sql = "SELECT a.kd_rek6 as kd_rek5,a.nm_rek6 as nm_rek5,
                a.sumber,sum(a.nilai_ubah) nilai,
                sum(a.nilai_ubah+b.nilai_ubah) nilai_myears 
                FROM              
                trdrka_n1 a left join trdrka b on b.no_trdrka=a.no_trdrka
                where a.kd_sub_kegiatan='$kegg' and a.kd_skpd='$skpd' and
                (upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%'))
                group by a.kd_rek6,a.nm_rek6,a.sumber
                ";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_lokasi' => $skpd,  
                        'kd_rek5'   => $resulte['kd_rek5'],  
                        'nm_rek5'   => $resulte['nm_rek5'],  
                        'nilai'     => number_format($resulte['nilai'],"2",".",","),
                        'nilai_myears'  => number_format($resulte['nilai_myears'],"2",".",","),    
                        'sumber'    => $resulte['sumber']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }
    /*
    function listRekening_mYears() {                      
        $skpd  = $this->input->post('skpd');
        $kegg  = $this->input->post('kd_keg');
        $lccr  = $this->input->post('q');
        
        $sql = "
        		SELECT a.kd_rek5,a.nm_rek5,a.sumber,
                a.nilai_ubah as nilai,
                a.nilai_ubah+b.nilai_ubah nilai_myears
                FROM           
                trdrka_n1 a 
                left join trdrka b on b.no_trdrka=a.no_trdrka
                where a.kd_kegiatan='$kegg' and
                (upper(a.kd_rek5) like upper('%$lccr%') or upper(a.nm_rek5) like upper('%$lccr%'))               
                ";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_rek5'   => $resulte['kd_rek5'],  
                        'nm_rek5'   => $resulte['nm_rek5'],  
                        'nilai'     => number_format($resulte['nilai'],"2",".",","),
                        'nilai_myears'  => number_format($resulte['nilai_myears'],"2",".",","),
                        'sumber'    => $resulte['sumber']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }*/

    function listRekening_pa($kegg='') {                      
        //$skpd  = $this->input->post('skpd');
        //$kegg  = $this->input->post('kd_keg');
        $lccr  = $this->input->post('q');
        
        $sql = "SELECT a.kd_rek5,a.nm_rek5 FROM                
                trdrka a where a.kd_kegiatan='$kegg' and
                (upper(a.kd_rek5) like upper('%$lccr%') or upper(a.nm_rek5) like upper('%$lccr%'))
                group by a.kd_rek5,a.nm_rek5
                ";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_rek5'   => $resulte['kd_rek5'],  
                        'nm_rek5'   => $resulte['nm_rek5']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }
	
    function listRincianpaket() {                      
        $skpd  = $this->input->post('skpd');
		$kegg  = $this->input->post('kd_keg');
        $rekk  = $this->input->post('kd_rek');
        $ntahun= $this->input->post('tahun');
		$lccr  = $this->input->post('q');
        //$ntahun= $this->session->userdata('pcThang');
        //$kegg = "4.06.4.06.02.00.01.015";
        //$rekk = "5210105";



        /*
        $sql ="xSELECT
    m.* 
FROM
    (
    SELECT
        n.*,
        n.nilai_ukur-nilai_sirup as hasil,
        n.nilai_ukur2-nilai_sirup_detail as hasil_2
    FROM
        (
        SELECT
            v.*,
            ( SELECT isnull( SUM ( nilai_ubah ), 0 ) FROM trdrka WHERE kd_sub_kegiatan = '$kegg' ) nilai_ukur,
            ( SELECT isnull( SUM ( nilai_ubah ), 0 ) FROM trdrka WHERE kd_sub_kegiatan = '$kegg' and kd_rek6='$rekk') nilai_ukur2,
            ( SELECT isnull( SUM ( a.pagu ), 0 ) FROM sirup_detail a LEFT JOIN sirup_header b on b.id=a.id and b.username=a.username WHERE (is_deleted<>1 or is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.tahun='$ntahun') nilai_sirup,
            ( SELECT isnull( SUM ( a.pagu ), 0 ) FROM sirup_detail a LEFT JOIN sirup_header b on b.id=a.id and b.username=a.username WHERE (is_deleted<>1 or is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.kd_rek5 = '$rekk' and a.tahun='$ntahun') nilai_sirup_detail
        FROM
            (
            SELECT
                '0' kd_dpaket,
                ( SELECT nm_rek6 FROM trdrka WHERE kd_sub_kegiatan = '$kegg' AND kd_rek6 = '$rekk' ) nm_dpaket,
                SUM ( a.volume ) volume,
                '' lokasi,
                '' tu_capai,
                SUM ( a.total_ubah ) total_ubah 
            FROM
                trdpo a                
            WHERE
            substring(a.no_trdrka,24,15)= '$kegg' 
                AND a.kd_rek6= '$rekk' 
                AND a.kd_rek6 NOT IN ( SELECT b.kd_rek5 FROM sirup_detail b LEFT JOIN sirup_header a on a.id=b.id and a.username=b.username WHERE (a.is_deleted<>1 or a.is_deleted is null) AND b.kd_kegiatan = '$kegg' AND b.kd_rek5 = '$rekk' and b.tahun='$ntahun') 
            GROUP BY
                substring(a.no_trdrka,24,15),
                a.kd_rek6 
            UNION ALL
            SELECT
                a.id kd_dpaket,
                a.uraian nm_dpaket,
                a.volume volume,
                '' lokasi,
                '' tu_capai,
                a.total_ubah 
            FROM
                trdpo a                
            WHERE
                substring(a.no_trdrka,24,15)= '$kegg' 
                AND a.kd_rek6= '$rekk' 
                AND a.total<>0
            ) v 
        --WHERE
        --CAST ( v.kd_dpaket AS CHAR ) NOT IN ( SELECT CAST ( a.kd_paket AS CHAR ) FROM sirup_detail a LEFT JOIN sirup_header b ON b.id=a.id and b.username=a.username WHERE (b.is_deleted<>1 or b.is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.kd_rek5 = '$rekk' and a.tahun='$ntahun') 
        --and (upper(v.nm_dpaket) like upper('%$lccr%'))
        ) n 
    WHERE
        n.nilai_sirup < n.nilai_ukur and n.nilai_sirup_detail < n.nilai_ukur2
    ) m 
    WHERE m.hasil_2<>0 
    --m.hasil<>0 and m.total_ubah<>0 
    ORDER BY
    m.kd_dpaket
        ";

        */

        /*(v.nm_dpaket+CAST ( v.kd_dpaket AS CHAR ) NOT IN ( SELECT a.isi_paket + CAST ( a.kd_paket AS CHAR ) FROM sirup_detail a LEFT JOIN sirup_header b ON b.id=a.id and b.username=a.username WHERE (b.is_deleted<>1 or b.is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.kd_rek5 = '$rekk' and a.tahun='$ntahun') 
        )*/

       // echo "$sql";
        $cek_keg = "select count(kd_sub_kegiatan) as jumkeg from 
                    ms_sub_kegiatan_rup where kd_sub_kegiatan='$kegg'";
        $queryCekk = $this->db->query($cek_keg);
        $total_cekk = $queryCekk->row();      
        $cekPakett = $total_cekk->jumkeg;                     

        if($cekPakett==1)
        {    
        
        $cek_paket = "
                        SELECT
                            isnull(SUM(a.pagu), 0) as nilai_pagu
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND a.kd_paket = 0
                ";

        $queryCek = $this->db->query($cek_paket);
        $total_cek = $queryCek->row();      
        $cekPaket = $total_cek->nilai_pagu;

        if($cekPaket>0){

        $sql = "SELECT
    m.*
    FROM
    (
        SELECT
            n.*, n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,(
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND a.kd_paket = 0
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka
                                WHERE
                                    kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (a.volume) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah) total_ubah
                        FROM
                            trdpo a
                        WHERE
                            a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        
                        GROUP BY
                            SUBSTRING (a.no_trdrka, 24, 15),
                            a.kd_rek6
                        
                    ) v
            ) n
    ) m
ORDER BY
    m.kd_dpaket";
        

        }else{

        $sql = "
        SELECT
        m.*
        FROM
        (
        SELECT
            n.*,
            n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,
                    (
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND a.kd_paket=v.kd_dpaket
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka
                                WHERE
                                    kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (a.volume) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah) total_ubah
                        FROM
                            trdpo a
                        WHERE
                            a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        AND a.kd_rek6 NOT IN (
                            SELECT
                                b.kd_rek5
                            FROM
                                sirup_detail b
                            LEFT JOIN sirup_header a ON a.id = b.id
                            AND a.username = b.username
                            WHERE
                                (
                                    a.is_deleted <> 1
                                    OR a.is_deleted IS NULL
                                )
                            AND b.kd_kegiatan = '$kegg'
                            AND b.kd_rek5 = '$rekk'
                            AND b.tahun = '$ntahun'
                        )
                        GROUP BY
                            a.kd_sub_kegiatan,
                            a.kd_rek6
                        UNION ALL
                            SELECT
                                a.id kd_dpaket,
                                a.ket_bl_teks+' ('+a.uraian+')' nm_dpaket,
                                a.volume volume,
                                '' lokasi,
                                '' tu_capai,
                                a.total_ubah
                            FROM
                                trdpo a
                            WHERE
                                a.kd_sub_kegiatan = '$kegg'
                            AND a.kd_rek6 = '$rekk'
                            AND a.total <> 0
                    ) v
                ) n ) m 
                ORDER BY m.kd_dpaket";
            }
        }else{
            /*Banyak Kegiatan*/
            $cek_paket = "
                        SELECT
                            isnull(SUM(a.pagu), 0) as nilai_pagu
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND b.lokasi = '$skpd'
                        AND a.kd_paket = 0
                ";

        $queryCek = $this->db->query($cek_paket);
        $total_cek = $queryCek->row();      
        $cekPaket = $total_cek->nilai_pagu;

        if($cekPaket>0){

        $sql = "SELECT
    m.*
    FROM
    (
        SELECT
            n.*, n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,(
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND b.lokasi = '$skpd'
                        AND a.kd_paket = 0
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka
                                WHERE kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                AND kd_skpd = '$skpd'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (a.volume) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah) total_ubah
                        FROM
                            trdpo a
                        WHERE a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        AND a.kd_skpd = '$skpd'
                        GROUP BY
                            SUBSTRING (a.no_trdrka, 24, 15),
                            a.kd_rek6
                        
                    ) v
            ) n
    ) m
ORDER BY
    m.kd_dpaket";
        

        }else{

        $sql = "
        SELECT
        m.*
        FROM
        (
        SELECT
            n.*,
            n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,
                    (
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND b.lokasi = '$skpd'
                        AND a.kd_paket=v.kd_dpaket
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka
                                WHERE kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                AND kd_skpd = '$skpd'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (a.volume) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah) total_ubah
                        FROM
                            trdpo a
                        WHERE a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        AND a.kd_skpd = '$skpd'
                        AND a.kd_rek6 NOT IN (
                            SELECT
                                b.kd_rek5
                            FROM
                                sirup_detail b
                            LEFT JOIN sirup_header a ON a.id = b.id
                            AND a.username = b.username
                            WHERE
                                (
                                    a.is_deleted <> 1
                                    OR a.is_deleted IS NULL
                                )
                            AND b.kd_kegiatan = '$kegg'
                            AND b.kd_rek5 = '$rekk'
                            AND a.lokasi = '$skpd'
                            AND b.tahun = '$ntahun'
                        )
                        GROUP BY
                            a.kd_sub_kegiatan,
                            a.kd_rek6
                        UNION ALL
                            SELECT
                                a.id kd_dpaket,
                                a.ket_bl_teks+' ('+a.uraian+')' nm_dpaket,
                                a.volume volume,
                                '' lokasi,
                                '' tu_capai,
                                a.total_ubah
                            FROM
                                trdpo a
                            WHERE a.kd_sub_kegiatan = '$kegg'
                            AND a.kd_rek6 = '$rekk'
                            AND a.kd_skpd = '$skpd'
                            AND a.total <> 0
                    ) v
                ) n ) m 
                ORDER BY m.kd_dpaket";
            }
        }    

		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_dpaket' => $resulte['kd_dpaket'],  
                        'nm_dpaket' => $resulte['nm_dpaket'],  
                        'volume' 	=> str_replace(".00","",$resulte['volume']),
                        'total_ubah'=> number_format($resulte['total_ubah'],"2",".",","),
                        'total_sisa'=> number_format($resulte['sisa'],"2",".",","),                        
                        'total_ubah_t'=> $resulte['total_ubah'],
                        'lokasi' 	=> $resulte['lokasi'],
                        'tu_capai' 	=> $resulte['tu_capai']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}

    function listRincianpaket_mYears() {                      
        $skpd  = $this->input->post('skpd');
        $kegg  = $this->input->post('kd_keg');
        $rekk  = $this->input->post('kd_rek');
        $ntahun= $this->input->post('tahun');
        $lccr  = $this->input->post('q');
        
        $cek_keg = "select count(kd_sub_kegiatan) as jumkeg from 
                    ms_sub_kegiatan_rup where kd_sub_kegiatan='$kegg'";
        $queryCekk = $this->db->query($cek_keg);
        $total_cekk = $queryCekk->row();      
        $cekPakett = $total_cekk->jumkeg;                     

        if($cekPakett==1)
        {    
        
        $cek_paket = "
                        SELECT
                            isnull(SUM(a.pagu), 0) as nilai_pagu
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND a.kd_paket = 0
                ";

        $queryCek = $this->db->query($cek_paket);
        $total_cek = $queryCek->row();      
        $cekPaket = $total_cek->nilai_pagu;

        if($cekPaket>0){

        $sql = "SELECT
    m.*
    FROM
    (
        SELECT
            n.*, n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,(
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND a.kd_paket = 0
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka_n1
                                WHERE
                                    kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (a.volume) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah+b.total_ubah) total_ubah
                        FROM
                            trdpo_n1 a left join trdpo b on a.no_trdrka=b.no_trdrka
                        WHERE
                            a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        
                        GROUP BY
                            SUBSTRING (a.no_trdrka, 24, 15),
                            a.kd_rek6
                        
                    ) v
            ) n
    ) m
ORDER BY
    m.kd_dpaket";
        

        }else{

        $sql = "
        SELECT
        m.*
        FROM
        (
        SELECT
            n.*,
            n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,
                    (
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND a.kd_paket=v.kd_dpaket
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka_n1
                                WHERE
                                    kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (a.volume) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah+b.total_ubah) total_ubah
                        FROM
                            trdpo_n1 a left join trdpo b on b.no_trdrka=a.no_trdrka
                        WHERE
                            a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        AND a.kd_rek6 NOT IN (
                            SELECT
                                b.kd_rek5
                            FROM
                                sirup_detail b
                            LEFT JOIN sirup_header a ON a.id = b.id
                            AND a.username = b.username
                            WHERE
                                (
                                    a.is_deleted <> 1
                                    OR a.is_deleted IS NULL
                                )
                            AND b.kd_kegiatan = '$kegg'
                            AND b.kd_rek5 = '$rekk'
                            AND b.tahun = '$ntahun'
                        )
                        GROUP BY
                            a.kd_sub_kegiatan,
                            a.kd_rek6
                        UNION ALL
                            SELECT
                                a.id kd_dpaket,
                                a.uraian nm_dpaket,
                                isnull(a.volume,1) volume,
                                '' lokasi,
                                '' tu_capai,
                                a.total_ubah
                            FROM
                                trdpo_n1 a
                            WHERE
                                a.kd_sub_kegiatan = '$kegg'
                            AND a.kd_rek6 = '$rekk'
                            AND a.total <> 0
                    ) v
                ) n ) m 
                ORDER BY m.kd_dpaket";
            }
        }else{
            /*Banyak Kegiatan*/
            $cek_paket = "
                        SELECT
                            isnull(SUM(a.pagu), 0) as nilai_pagu
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND b.lokasi = '$skpd'
                        AND a.kd_paket = 0
                ";

        $queryCek = $this->db->query($cek_paket);
        $total_cek = $queryCek->row();      
        $cekPaket = $total_cek->nilai_pagu;

        if($cekPaket>0){

        $sql = "SELECT
    m.*
    FROM
    (
        SELECT
            n.*, n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,(
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND b.lokasi = '$skpd'
                        AND a.kd_paket = 0
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka_n1
                                WHERE kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                AND kd_skpd = '$skpd'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (isnull(a.volume,1)) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah+b.total_ubah) total_ubah
                        FROM
                            trdpo_n1 a left join trdpo b on b.no_trdrka=a.no_trdrka
                        WHERE a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        AND a.kd_skpd = '$skpd'
                        GROUP BY
                            SUBSTRING (a.no_trdrka, 24, 15),
                            a.kd_rek6
                        
                    ) v
            ) n
    ) m
ORDER BY
    m.kd_dpaket";
        

        }else{

        $sql = "
        SELECT
        m.*
        FROM
        (
        SELECT
            n.*,
            n.total_ubah - nilai_sirup_detail AS sisa
        FROM
            (
                SELECT
                    v.*,
                    (
                        SELECT
                            isnull(SUM(a.pagu), 0)
                        FROM
                            sirup_detail a
                        LEFT JOIN sirup_header b ON b.id = a.id
                        AND b.username = a.username
                        WHERE
                            (
                                is_deleted <> 1
                                OR is_deleted IS NULL
                            )
                        AND a.kd_kegiatan = '$kegg'
                        AND a.kd_rek5 = '$rekk'
                        AND a.tahun = '$ntahun'
                        AND b.lokasi = '$skpd'
                        AND a.kd_paket=v.kd_dpaket
                    ) nilai_sirup_detail
                FROM
                    (
                        SELECT
                            '0' kd_dpaket,
                            (
                                SELECT
                                    nm_rek6
                                FROM
                                    trdrka_n1
                                WHERE kd_sub_kegiatan = '$kegg'
                                AND kd_rek6 = '$rekk'
                                AND kd_skpd = '$skpd'
                                group by nm_rek6
                            ) nm_dpaket,
                            SUM (a.volume) volume,
                            '' lokasi,
                            '' tu_capai,
                            SUM (a.total_ubah) total_ubah
                        FROM
                            trdpo_n1 a
                        WHERE a.kd_sub_kegiatan = '$kegg'
                        AND a.kd_rek6 = '$rekk'
                        AND a.kd_skpd = '$skpd'
                        AND a.kd_rek6 NOT IN (
                            SELECT
                                b.kd_rek5
                            FROM
                                sirup_detail b
                            LEFT JOIN sirup_header a ON a.id = b.id
                            AND a.username = b.username
                            WHERE
                                (
                                    a.is_deleted <> 1
                                    OR a.is_deleted IS NULL
                                )
                            AND b.kd_kegiatan = '$kegg'
                            AND b.kd_rek5 = '$rekk'
                            AND a.lokasi = '$skpd'
                            AND b.tahun = '$ntahun'
                        )
                        GROUP BY
                            a.kd_sub_kegiatan,
                            a.kd_rek6
                        UNION ALL
                            SELECT
                                a.id kd_dpaket,
                                a.uraian nm_dpaket,
                                isnull(a.volume,1) volume,
                                '' lokasi,
                                '' tu_capai,
                                a.total_ubah
                            FROM
                                trdpo_n1 a
                            WHERE a.kd_sub_kegiatan = '$kegg'
                            AND a.kd_rek6 = '$rekk'
                            AND a.kd_skpd = '$skpd'
                            AND a.total <> 0
                    ) v
                ) n ) m 
                ORDER BY m.kd_dpaket";
            }
        }    

        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_dpaket' => $resulte['kd_dpaket'],  
                        'nm_dpaket' => $resulte['nm_dpaket'],  
                        'volume'    => str_replace(".00","",$resulte['volume']),
                        'total_ubah'=> number_format($resulte['total_ubah'],"2",".",","),
                        'total_sisa'=> number_format($resulte['sisa'],"2",".",","),                        
                        'total_ubah_t'=> $resulte['total_ubah'],
                        'lokasi'    => $resulte['lokasi'],
                        'tu_capai'  => $resulte['tu_capai']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }

    /*
     function listRincianpaket_mYears() {                      
        $skpd  = $this->input->post('skpd');
        $kegg  = $this->input->post('kd_keg');
        $rekk  = $this->input->post('kd_rek');
        //$ntahun= $this->input->post('tahun');
        $lccr  = $this->input->post('q');
        $ntahun= $this->session->userdata('pcThang');
           

        $sql ="SELECT
    m.* 
FROM
    (
    SELECT
        n.*,
        n.nilai_ukur-nilai_sirup hasil,
        n.nilai_ukur2-nilai_sirup_detail hasil_2
    FROM
        (
        SELECT
            v.*,
            ( 
                SELECT sum(a.nilai_ubah) as nilai_ubah FROM (                
                SELECT isnull( SUM ( nilai_ubah ), 0 ) as nilai_ubah FROM trdrka WHERE kd_kegiatan = '$kegg'
                UNION ALL
                SELECT isnull( SUM ( nilai_ubah ), 0 ) as nilai_ubah FROM trdrka_n1 WHERE kd_kegiatan = '$kegg'
                )a
            ) nilai_ukur,
            ( 
                SELECT sum(a.nilai_ubah) as nilai_ubah FROM (
                SELECT isnull( SUM ( nilai_ubah ), 0 ) as nilai_ubah FROM trdrka WHERE kd_kegiatan = '$kegg' and kd_rek5='$rekk'
                UNION ALL
                SELECT isnull( SUM ( nilai_ubah ), 0 ) as nilai_ubah FROM trdrka_n1 WHERE kd_kegiatan = '$kegg' and kd_rek5='$rekk'
                )a
            ) nilai_ukur2,
            
            ( SELECT isnull( SUM ( a.pagu ), 0 ) FROM sirup_detail a LEFT JOIN sirup_header b on b.id=a.id and b.username=a.username WHERE (is_deleted<>1 or is_deleted is null) and a.kd_kegiatan = '$kegg' ) nilai_sirup,
            ( SELECT isnull( SUM ( a.pagu ), 0 ) FROM sirup_detail a LEFT JOIN sirup_header b on b.id=a.id and b.username=a.username WHERE (is_deleted<>1 or is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.kd_rek5 = '$rekk') nilai_sirup_detail
        FROM
            (
            SELECT
                '0' kd_dpaket,
                ( SELECT nm_rek5 FROM trdrka_n1 WHERE kd_kegiatan = '$kegg' AND kd_rek5 = '$rekk' ) nm_dpaket,
                SUM ( a.volume_ubah1 ) volume,
                b.lokasi,
                b.tu_capai,
                SUM ( a.total_ubah ) total_ubah 
            FROM
                trdpo_n1 a
                LEFT JOIN trskpd_n1 b ON b.kd_kegiatan= a.kd_kegiatan 
            WHERE
                a.kd_kegiatan= '$kegg' 
                AND a.kd_rek5= '$rekk' 
                AND a.kd_rek5 NOT IN ( SELECT b.kd_rek5 FROM sirup_detail b LEFT JOIN sirup_header a on a.id=b.id and a.username=b.username WHERE (a.is_deleted<>1 or a.is_deleted is null) AND b.kd_kegiatan = '$kegg' AND b.kd_rek5 = '$rekk' ) 
            GROUP BY
                a.kd_kegiatan,
                a.kd_rek5,
                b.lokasi,
                b.tu_capai UNION ALL
            SELECT
                a.no_po kd_dpaket,
                a.uraian nm_dpaket,
                a.volume_ubah1 volume,
                b.lokasi,
                b.tu_capai,
                a.total_ubah 
            FROM
                trdpo_n1 a
                LEFT JOIN trskpd_n1 b ON b.kd_kegiatan= a.kd_kegiatan 
            WHERE
                a.kd_kegiatan= '$kegg' 
                AND a.kd_rek5= '$rekk' 
                --AND a.total<>0
            ) v 
        WHERE
        v.nm_dpaket+CAST ( v.kd_dpaket AS CHAR ) NOT IN ( SELECT a.isi_paket + CAST ( a.kd_paket AS CHAR ) FROM sirup_detail a LEFT JOIN sirup_header b ON b.id=a.id and b.username=a.username WHERE (b.is_deleted<>1 or b.is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.kd_rek5 = '$rekk' ) 
        and (upper(v.nm_dpaket) like upper('%$lccr%'))
        ) n 
    WHERE
        n.nilai_sirup < n.nilai_ukur and n.nilai_sirup_detail < n.nilai_ukur2
    ) m 
    WHERE m.hasil_2<>0 
    --m.hasil<>0 and m.total_ubah<>0 
    ORDER BY
    m.kd_dpaket
   
        ";
      
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_dpaket' => $resulte['kd_dpaket'],  
                        'nm_dpaket' => $resulte['nm_dpaket'],  
                        'volume'    => str_replace(".00","",$resulte['volume']),
                        'total_ubah'=> number_format($resulte['total_ubah'],"2",".",","),
                        'total_ubah_t'=> $resulte['total_ubah'],
                        'lokasi'    => $resulte['lokasi'],
                        'tu_capai'  => $resulte['tu_capai']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }*/        

    function listRincianpaket_revisi() {                      
        $skpd  = $this->input->post('skpd');
        $kegg  = $this->input->post('kd_keg');
        $rekk  = $this->input->post('kd_rek');
        $idrup = $this->input->post('idrup_lama');
        $ntahun= $this->input->post('tahun');
        $lccr  = $this->input->post('q');

        //$kegg = "4.06.4.06.02.00.01.015";
        //$rekk = "5210105";
        
        $sql ="SELECT
    m.* 
FROM
    (
    SELECT
        n.*,
        n.nilai_ukur-nilai_sirup hasil,
        n.nilai_ukur2-nilai_sirup_detail hasil_2
    FROM
        (
        SELECT
            v.*,
            ( SELECT isnull( SUM ( nilai_ubah ), 0 ) FROM trdrka WHERE kd_kegiatan = '$kegg' ) nilai_ukur,
            ( SELECT isnull( SUM ( nilai_ubah ), 0 ) FROM trdrka WHERE kd_kegiatan = '$kegg' and kd_rek5='$rekk') nilai_ukur2,
            ( SELECT isnull( SUM ( a.pagu ), 0 ) FROM sirup_detail a LEFT JOIN sirup_header b on b.id=a.id and b.username=a.username WHERE (is_deleted<>1 or is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.tahun='$ntahun') nilai_sirup,
            ( SELECT isnull( SUM ( a.pagu ), 0 ) FROM sirup_detail a LEFT JOIN sirup_header b on b.id=a.id and b.username=a.username WHERE (is_deleted<>1 or is_deleted is null) and a.kd_kegiatan = '$kegg' AND a.kd_rek5 = '$rekk' AND a.tahun='$ntahun') nilai_sirup_detail
        FROM
            (
            SELECT
                '0' kd_dpaket,
                ( SELECT nm_rek5 FROM trdrka WHERE kd_kegiatan = '$kegg' AND kd_rek5 = '$rekk' ) nm_dpaket,
                SUM ( a.volume_ubah1 ) volume,
                b.lokasi,
                b.tu_capai,
                SUM ( a.total_ubah ) total_ubah 
            FROM
                trdpo a
                LEFT JOIN trskpd b ON b.kd_kegiatan= a.kd_kegiatan 
            WHERE
                a.kd_kegiatan= '$kegg' 
                AND a.kd_rek5= '$rekk' 
                AND a.kd_rek5 NOT IN ( SELECT b.kd_rek5 FROM sirup_detail b LEFT JOIN sirup_header a on a.id=b.id and a.username=b.username WHERE (a.is_deleted<>1 or a.is_deleted is null) AND b.kd_kegiatan = '$kegg' AND b.kd_rek5 = '$rekk' AND b.tahun='$ntahun') 
            GROUP BY
                a.kd_kegiatan,
                a.kd_rek5,
                b.lokasi,
                b.tu_capai UNION ALL
            SELECT
                a.no_po kd_dpaket,
                a.uraian nm_dpaket,
                a.volume_ubah1 volume,
                b.lokasi,
                b.tu_capai,
                a.total_ubah 
            FROM
                trdpo a
                LEFT JOIN trskpd b ON b.kd_kegiatan= a.kd_kegiatan 
            WHERE
                a.kd_kegiatan= '$kegg' 
                AND a.kd_rek5= '$rekk' 
                AND a.total<>0
            ) v 
        WHERE
        v.nm_dpaket+CAST ( v.kd_dpaket AS CHAR ) IN ( SELECT a.isi_paket + CAST ( a.kd_paket AS CHAR ) FROM sirup_detail a LEFT JOIN sirup_header b ON b.id=a.id and b.username=a.username WHERE b.idrup='$idrup' and a.kd_kegiatan = '$kegg' AND a.kd_rek5 = '$rekk' AND a.tahun='$ntahun') 
        and (upper(v.nm_dpaket) like upper('%$lccr%'))
        ) n 
    WHERE
        n.nilai_sirup < n.nilai_ukur and n.nilai_sirup_detail < n.nilai_ukur2
    ) m 
    WHERE m.hasil_2<>0 
    --m.hasil<>0 and m.total_ubah<>0 
    ORDER BY
    m.kd_dpaket
   
        ";
       // echo "$sql";

        /*$sql = "SELECT n.* FROM(
                SELECT
                v.*,(select isnull(sum(nilai_ubah),0) from trdrka where kd_kegiatan = '$kegg') nilai_ukur,
                    (select isnull(sum(pagu),0) from sirup_detail where kd_kegiatan = '$kegg' AND left(kd_rek5,3) = left('$rekk',3)) nilai_sirup
                FROM(
                SELECT  
                '0' kd_dpaket,(select nm_rek5 from trdrka where kd_kegiatan='$kegg' and kd_rek5='$rekk') nm_dpaket,sum(a.volume1) volume,b.lokasi,b.tu_capai,
                sum(a.total_ubah) total_ubah
                FROM trdpo a left join trskpd b on b.kd_kegiatan=a.kd_kegiatan                
                where a.kd_kegiatan='$kegg' and a.kd_rek5='$rekk' and a.kd_rek5 not in (select kd_rek5 from sirup_detail where kd_kegiatan='$kegg' and kd_rek5='$rekk')
                group by a.kd_kegiatan,a.kd_rek5,b.lokasi,b.tu_capai
                union all                                       
                SELECT 
                a.no_po kd_dpaket,a.uraian nm_dpaket,a.volume1 volume,b.lokasi,b.tu_capai,
                a.total_ubah
                FROM trdpo a left join trskpd b on b.kd_kegiatan=a.kd_kegiatan
                where a.kd_kegiatan='$kegg' and a.kd_rek5='$rekk' 
                )v 
                where v.nm_dpaket+cast(v.kd_dpaket as char) not in (select isi_paket+cast(kd_paket as char) from sirup_detail 
                where kd_kegiatan='$kegg' and kd_rek5='$rekk') and                
                (upper(v.nm_dpaket) like upper('%$lccr%')) )n where n.nilai_sirup < n.nilai_ukur ORDER BY n.kd_dpaket
                ";
        */        
                //where n.nilai_sirup < n.nilai_ukur
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_dpaket' => $resulte['kd_dpaket'],  
                        'nm_dpaket' => $resulte['nm_dpaket'],  
                        'volume'    => str_replace(".00","",$resulte['volume']),
                        'total_ubah'=> number_format($resulte['total_ubah'],"2",".",","),
                        'total_ubah_t'=> $resulte['total_ubah'],
                        'lokasi'    => $resulte['lokasi'],
                        'tu_capai'  => $resulte['tu_capai']
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
        
		/*SELECT x.* FROM(
        SELECT n.*,isnull(sum(n.n_ubah-n.nilai_sirup),0) total_ubah,isnull(sum(n.volume-n.vol_sirup),0) total_vol FROM(
        SELECT v.*,
        (select sumber from trdrka where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg' group by sumber) sumber_dana,
        (select isnull(sum(pagu),0) from sirup_detail where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg') nilai_sirup,
        (select isnull(sum(vol),0) from sirup_detail where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg') vol_sirup        
        FROM(
SELECT 
ROW_NUMBER() OVER (ORDER BY c.kd_rek4) as kd_dpaket,
c.kd_rek4 as kd_rek,
(select nm_rek3 from ms_rek3 where kd_rek3=left(c.kd_rek4,3))+', '+c.nm_rek4 as nm_dpaket,  
sum(a.volume1) volume,b.lokasi,b.tu_capai,b.nm_skpd,
(select klpd from sirup_lokasi where kd_lokasi='D199') klpd,sum(a.total_ubah) n_ubah
FROM trdpo a 
left join trskpd b on b.kd_kegiatan=a.kd_kegiatan
left join ms_rek4 c on left(a.kd_rek5,5)=c.kd_rek4
where a.kd_kegiatan='$kegg'
group by c.kd_rek4,c.nm_rek4,b.lokasi,b.tu_capai,b.nm_skpd

)v)n group by n.kd_dpaket,n.kd_rek,n.nm_dpaket,n.volume,n.lokasi,n.tu_capai,n.sumber_dana,n.vol_sirup,n.nm_skpd,n.klpd,n.n_ubah,n.nilai_sirup
)x where x.total_ubah<>0

*/
	
/*
      $sql = "
        SELECT x.* FROM(
        SELECT n.*,
isnull(sum(n.n_ubah-n.nilai_sirup),0) total_ubah,
isnull(sum(n.volume-n.vol_sirup),0) total_vol FROM(
        SELECT v.*,
        (select top 1 sumber from trdrka where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg' group by sumber) sumber_dana,
        (select isnull(sum(a.pagu),0) from sirup_detail a
         left join sirup_header b on b.kd_skpd=a.kd_skpd and b.username=a.username and b.id=a.id
         where left(a.kd_rek5,7)=left(v.kd_rek,7) and a.kd_kegiatan='$kegg' and b.is_deleted is null) nilai_sirup,
        (select isnull(sum(a.vol),0) from sirup_detail a
         left join sirup_header b on b.kd_skpd=a.kd_skpd and b.username=a.username and b.id=a.id
         where left(a.kd_rek5,7)=left(v.kd_rek,7) and a.kd_kegiatan='$kegg' and b.is_deleted is null) vol_sirup        
        FROM(

SELECT 
ROW_NUMBER() OVER (ORDER BY c.kd_rek5) as kd_dpaket,
c.kd_rek5 as kd_rek,
(select top 1 nm_rek3 from ms_rek3 where kd_rek3=left(c.kd_rek5,3))+', '+c.nm_rek5 as nm_dpaket,  
sum(a.volume_ubah1) volume,b.lokasi,b.tu_capai,b.nm_skpd,
(select top 1 klpd from sirup_lokasi where kd_lokasi='D199') klpd,sum(a.total_ubah) n_ubah
FROM trdpo a 
left join trskpd b on b.kd_kegiatan=a.kd_kegiatan
left join ms_rek5 c on left(a.kd_rek5,7)=c.kd_rek5
where a.kd_kegiatan='$kegg'
group by c.kd_rek5,c.nm_rek5,b.lokasi,b.tu_capai,b.nm_skpd

)v)n group by n.kd_dpaket,n.kd_rek,n.nm_dpaket,n.volume,n.lokasi,n.tu_capai,n.sumber_dana,n.vol_sirup,n.nm_skpd,n.klpd,n.n_ubah,n.nilai_sirup
)x where x.total_ubah<>0";
                //where n.nilai_sirup < n.nilai_ukur
*/

        $cek_keg = "select count(kd_sub_kegiatan) as jumkeg from 
                    ms_sub_kegiatan_rup where kd_sub_kegiatan='$kegg'";
        $queryCekk = $this->db->query($cek_keg);
        $total_cekk = $queryCekk->row();      
        $cekPakett = $total_cekk->jumkeg; 

        if($cekPakett>1){

        $sql ="SELECT x.* FROM(
        SELECT n.*,
        isnull(sum(n.n_ubah-n.nilai_sirup),0) total_ubah,
        isnull(sum(n.volume-n.vol_sirup),0) total_vol FROM(
        SELECT v.*,
        (select top 1 sumber from trdrka where left(kd_rek6,3)=left(v.kd_rek,3) and kd_sub_kegiatan='$kegg' and kd_skpd='$skpd' group by sumber) sumber_dana,
        (select isnull(sum(a.pagu),0) from sirup_detail a
         left join sirup_header b on b.kd_skpd=a.kd_skpd and b.username=a.username and b.id=a.id
         where a.kd_rek5=v.kd_rek and a.kd_kegiatan='$kegg' and b.lokasi='$skpd' and b.is_deleted is null) nilai_sirup,
        (select isnull(sum(a.vol),0) from sirup_detail a
         left join sirup_header b on b.kd_skpd=a.kd_skpd and b.username=a.username and b.id=a.id
         where a.kd_rek5=v.kd_rek and a.kd_kegiatan='$kegg' and b.lokasi='$skpd' and b.is_deleted is null) vol_sirup        
        FROM(
SELECT 
ROW_NUMBER() OVER (ORDER BY c.kd_rek6) as kd_dpaket,
c.kd_rek6 as kd_rek,
c.nm_rek6 as nm_dpaket,  
sum(a.volume) volume,case when b.lokasi = '' or b.lokasi is null then 'Kota Pontianak' else b.lokasi end as lokasi,
b.tu_capai,b.nm_skpd,
(select top 1 klpd from sirup_lokasi where kd_lokasi='D199') klpd,sum(a.total_ubah) n_ubah
FROM trdpo a 
left join trskpd b on b.kd_sub_kegiatan=a.kd_sub_kegiatan
left join ms_rek6 c on a.kd_rek6=c.kd_rek6
where a.kd_sub_kegiatan='$kegg' and b.kd_skpd='$skpd'
group by c.kd_rek6,c.nm_rek6,b.lokasi,b.tu_capai,b.nm_skpd


)v)n group by n.kd_dpaket,n.kd_rek,n.nm_dpaket,n.volume,n.lokasi,n.tu_capai,n.sumber_dana,n.vol_sirup,n.nm_skpd,n.klpd,n.n_ubah,n.nilai_sirup
)x where x.total_ubah<>0";

        }else{
            //satu
            $sql ="SELECT x.* FROM(
        SELECT n.*,
        isnull(sum(n.n_ubah-n.nilai_sirup),0) total_ubah,
        isnull(sum(n.volume-n.vol_sirup),0) total_vol FROM(
        SELECT v.*,
        (select top 1 sumber from trdrka where left(kd_rek6,3)=left(v.kd_rek,3) and kd_sub_kegiatan='$kegg' group by sumber) sumber_dana,
        (select isnull(sum(a.pagu),0) from sirup_detail a
         left join sirup_header b on b.kd_skpd=a.kd_skpd and b.username=a.username and b.id=a.id
         where a.kd_rek5=v.kd_rek and a.kd_kegiatan='$kegg' and b.is_deleted is null) nilai_sirup,
        (select isnull(sum(a.vol),0) from sirup_detail a
         left join sirup_header b on b.kd_skpd=a.kd_skpd and b.username=a.username and b.id=a.id
         where a.kd_rek5=v.kd_rek and a.kd_kegiatan='$kegg' and b.is_deleted is null) vol_sirup        
        FROM(
SELECT 
ROW_NUMBER() OVER (ORDER BY c.kd_rek6) as kd_dpaket,
c.kd_rek6 as kd_rek,
c.nm_rek6 as nm_dpaket,  
sum(a.volume) volume,case when b.lokasi = '' or b.lokasi is null then 'Kota Pontianak' else b.lokasi end as lokasi,
b.tu_capai,b.nm_skpd,
(select top 1 klpd from sirup_lokasi where kd_lokasi='D199') klpd,sum(a.total_ubah) n_ubah
FROM trdpo a 
left join trskpd b on b.kd_sub_kegiatan=a.kd_sub_kegiatan
left join ms_rek6 c on a.kd_rek6=c.kd_rek6
where a.kd_sub_kegiatan='$kegg'
group by c.kd_rek6,c.nm_rek6,b.lokasi,b.tu_capai,b.nm_skpd


)v)n group by n.kd_dpaket,n.kd_rek,n.nm_dpaket,n.volume,n.lokasi,n.tu_capai,n.sumber_dana,n.vol_sirup,n.nm_skpd,n.klpd,n.n_ubah,n.nilai_sirup
)x where x.total_ubah<>0";

        }

        $vol=0;
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){    

            if($resulte['total_vol']<1){
                $vol=1;
            }else{
                $vol=$resulte['total_vol'];
            }

            $result[] = array(
                        'kd_dpaket' => $resulte['kd_dpaket'],
                        'kd_rek' => $resulte['kd_rek'],
                        'sumber' => $resulte['sumber_dana'],
                        'nm_dpaket' => $resulte['nm_dpaket'],  
                        'volume' 	=> str_replace(".00","",$vol),
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

    function listRincianpaket_swakelola_tes() {                      
        $skpd  = "2.10.01.00";//$this->input->post('skpd');
        $kegg  = "2.10.2.10.01.00.16.014";//$this->input->post('kd_keg');
        $rekk  = "";//$this->input->post('kd_rek');
        $lccr  = "";//$this->input->post('q');
        
        //$kegg = "4.06.4.06.02.00.01.015";
        //$rekk = "5210105";
        
        /*SELECT x.* FROM(
        SELECT n.*,isnull(sum(n.n_ubah-n.nilai_sirup),0) total_ubah,isnull(sum(n.volume-n.vol_sirup),0) total_vol FROM(
        SELECT v.*,
        (select sumber from trdrka where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg' group by sumber) sumber_dana,
        (select isnull(sum(pagu),0) from sirup_detail where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg') nilai_sirup,
        (select isnull(sum(vol),0) from sirup_detail where left(kd_rek5,3)=left(v.kd_rek,3) and kd_kegiatan='$kegg') vol_sirup        
        FROM(
SELECT 
ROW_NUMBER() OVER (ORDER BY c.kd_rek4) as kd_dpaket,
c.kd_rek4 as kd_rek,
(select nm_rek3 from ms_rek3 where kd_rek3=left(c.kd_rek4,3))+', '+c.nm_rek4 as nm_dpaket,  
sum(a.volume1) volume,b.lokasi,b.tu_capai,b.nm_skpd,
(select klpd from sirup_lokasi where kd_lokasi='D199') klpd,sum(a.total_ubah) n_ubah
FROM trdpo a 
left join trskpd b on b.kd_kegiatan=a.kd_kegiatan
left join ms_rek4 c on left(a.kd_rek5,5)=c.kd_rek4
where a.kd_kegiatan='$kegg'
group by c.kd_rek4,c.nm_rek4,b.lokasi,b.tu_capai,b.nm_skpd

)v)n group by n.kd_dpaket,n.kd_rek,n.nm_dpaket,n.volume,n.lokasi,n.tu_capai,n.sumber_dana,n.vol_sirup,n.nm_skpd,n.klpd,n.n_ubah,n.nilai_sirup
)x where x.total_ubah<>0

*/
        
      $sql = "
        SELECT x.* FROM(
        SELECT n.*,
isnull(sum(n.n_ubah-n.nilai_sirup),0) total_ubah,
isnull(sum(n.volume-n.vol_sirup),0) total_vol FROM(
        SELECT v.*,
        (select top 1 sumber from trdrka where left(kd_rek5,5)=left(v.kd_rek,5) and kd_kegiatan='$kegg' group by sumber) sumber_dana,
        (select isnull(sum(pagu),0) from sirup_detail where left(kd_rek5,5)=left(v.kd_rek,5) and kd_kegiatan='$kegg') nilai_sirup,
        (select isnull(sum(vol),0) from sirup_detail where left(kd_rek5,5)=left(v.kd_rek,5) and kd_kegiatan='$kegg') vol_sirup        
        FROM(
SELECT 
ROW_NUMBER() OVER (ORDER BY c.kd_rek4) as kd_dpaket,
c.kd_rek4 as kd_rek,
(select nm_rek3 from ms_rek3 where kd_rek3=left(c.kd_rek4,3))+', '+c.nm_rek4 as nm_dpaket,  
sum(a.volume1) volume,b.lokasi,b.tu_capai,b.nm_skpd,
(select klpd from sirup_lokasi where kd_lokasi='D199') klpd,sum(a.total_ubah) n_ubah
FROM trdpo a 
left join trskpd b on b.kd_kegiatan=a.kd_kegiatan
left join ms_rek4 c on left(a.kd_rek5,5)=c.kd_rek4
where a.kd_kegiatan='$kegg'
group by c.kd_rek4,c.nm_rek4,b.lokasi,b.tu_capai,b.nm_skpd

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
                        'volume'    => str_replace(".00","",$resulte['total_vol']),
                        'total_ubah'=> number_format($resulte['total_ubah'],"2",".",","),
                        'total_ubah_t'=> $resulte['total_ubah'],
                        'lokasi'    => $resulte['lokasi'],
                        'klpd'      => $resulte['klpd'],
                        'tu_capai'  => $resulte['tu_capai'],                        
                        'nm_skpd'   => $resulte['nm_skpd']
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }
    
	function lokasi() {                      
		$sql = "SELECT kd_lokasi,nm_lokasi,klpd FROM sirup_lokasi";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_lokasi' => $resulte['kd_lokasi'],  
                        'nm_lokasi' => $resulte['nm_lokasi'],
						'klpd' 		=> $resulte['klpd'] 		
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
	
	function sumberdana_x() {                      
		$sql = "select kd_sd,nm_sd from sirup_sumber_dana 
				where kd_sd in ('1','2','8') group by kd_sd,nm_sd
				order by cast(kd_sd as int)";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'kd_sd' => $resulte['kd_sd'],  
                        'nm_sd' => $resulte['nm_sd']		
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}
	
	function paket_ppk() {                      
		$usernam = $this->session->userdata('pcNama');
        $skpd = $this->session->userdata('kdskpd');
        
		$sql_ 		= "select nama,id_ttd as id,username from ms_ttd where username='$usernam' and left(kd_skpd,18)=left('$skpd',18)";
		$query1 = $this->db->query($sql_);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'nama' 	=> $resulte['nama'],  
                        'user' 	=> $resulte['username'], 
                        'did' 	=> $resulte['id'] 
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
	}

    function idrup_lama() {                      
        $usernam = $this->session->userdata('pcNama');
        $kd_skpd = $this->session->userdata('kdskpd');
        
        $sql_       = "select a.*,
                       (select top 1 nm_program from ms_program where kd_program=left(a.kd_kegiatan,18)) as nm_program,
                       (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                       (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=a.id_ppk) as ppk,
                       (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=a.id) nm_paket_gab,
                       (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=a.id) sumber_dana,
                       case when a.is_revisi=1 then 'SATUKESATU' 
                            when a.is_revisi=2 then 'SATUKEBANYAK'
                            when a.is_revisi=3 then 'DIBATALKAN' end as ket_revisi,
                       'ALASAN REVISI : '+b.alasan_revisi alasan_revisi     
                       from sirup_header a
                       left join sirup_history_paket b on b.idrup=a.idrup 
                       where a.username='$usernam' and a.kd_skpd='$kd_skpd' and a.is_revisi in ('1','3')  ";
        $query1 = $this->db->query($sql_);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'ket_revisi'=> $resulte['ket_revisi'],
                        'alasan_revisi'=> $resulte['alasan_revisi'],

                        'idrup'         => $resulte['idrup'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'tkd_kegiatan'  => substr($resulte['kd_kegiatan'],-6),
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2'   => $resulte['tanggal_kebutuhan_akhir']   
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }


    function tipe_revisi() {                      
        $usernam = $this->session->userdata('pcNama');
        $skpd = $this->session->userdata('kdskpd');
        
        $sql_       = "select id,tipe from sirup_history_tipe order by cast(id as int)";
        $query1 = $this->db->query($sql_);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'id'  => $resulte['id'], 
                        'tipe'  => $resulte['tipe'] 
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }

    function tipe_revisi_swakelola() {                      
        $usernam = $this->session->userdata('pcNama');
        $skpd = $this->session->userdata('kdskpd');
        
        $sql_       = "select id,tipe from sirup_history_tipe where id='3'";
        $query1 = $this->db->query($sql_);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'id'  => $resulte['id'], 
                        'tipe'  => $resulte['tipe'] 
                        );
        }                   
       echo json_encode($result);    
       $query1->free_result(); 
    }

	
    function tipeswakelola() {                      
		$sql = "SELECT tipe_swakelola,ket_swakelola FROM sirup_swakelola";
		$query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'tipe_swakelola' 	=> $resulte['tipe_swakelola'],  
                        'ket_swakelola' 	=> $resulte['ket_swakelola'] 
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
	
	function skpd_all() {
		$kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where right(kd_skpd,2) = '00' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result(); 	  
	}
	
	function load_cek_anggaran_sirup(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria_skpd = '';
        $kriteria = $this->input->post('kriteria_init');
        $kriteria_keg = $this->input->post('kriteria_keg');
        $kriteria_skpd = $this->input->post('kriteria_skpd');
        $kriteria_user = $this->input->post('kriteria_user');
		
        $init ='';
        if ($kriteria <> ''){                               
            
            if($kriteria=="1"){
                $init= "nilai";
            }else if($kriteria=="2"){
                $init= "nilai_sempurna";
            }else{
                $init= "nilai_ubah";
            }                                        
        }
        
        $sql = "select count(x.kd_kegiatan) as tot  from(
                select kd_kegiatan,nm_kegiatan,sum($init) as nilai_ang,(
                select username from m_giat_rup where kd_kegiatan=trdrka.kd_kegiatan
                ) username from trdrka
                where left(kd_skpd,7)=left('$kriteria_skpd',7) and nm_kegiatan like ('%$kriteria_keg%')
                group by kd_kegiatan,nm_kegiatan )x
                left join (
                select kd_kegiatan,sum(pagu) as nilai_kas from sirup_detail 
                where left(kd_skpd,7)=left('$kriteria_skpd',7) and nm_kegiatan like ('%$kriteria_keg%')
                group by kd_kegiatan
                ) z on z.kd_kegiatan = x.kd_kegiatan
				where x.username='$kriteria_user'
                " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "select x.kd_kegiatan,x.nm_kegiatan,x.nilai_ang,z.nilai_kas,case when z.nilai_kas = x.nilai_ang then 'SAMA' ELSE 'TIDAK' END AS hasil from(
                select kd_kegiatan,nm_kegiatan,sum($init) as nilai_ang,(
                select username from m_giat_rup where kd_kegiatan=trdrka.kd_kegiatan
                ) username from trdrka
                where left(kd_skpd,7)=left('$kriteria_skpd',7) and nm_kegiatan like ('%$kriteria_keg%')
                group by kd_kegiatan,nm_kegiatan )x
                left join (
                select kd_kegiatan,sum(pagu) as nilai_kas from sirup_detail 
                where left(kd_skpd,7)=left('$kriteria_skpd',7) and nm_kegiatan like ('%$kriteria_keg%')               
                group by kd_kegiatan
                ) z on z.kd_kegiatan = x.kd_kegiatan 
				where x.username='$kriteria_user'
                order by x.kd_kegiatan,x.nm_kegiatan
                ";
                
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],        
                        'nilai_ang' => number_format($resulte['nilai_ang'],2),
                        'nilai_kas' => number_format($resulte['nilai_kas'],2),
                        'status_hasil' => $resulte['hasil']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
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
        //$kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';$and ='';$row = '';
        if ($kriteria <> ''){                               
            $where="WHERE ( upper(nm_paket_gab) like upper('%$kriteria%') or upper(IDRUP) like upper('%$kriteria%')) ";  
            $and="AND ( upper(nm_paket) like upper('%$kriteria%') or upper(IDRUP) like upper('%$kriteria%')) ";           
            $rows=1000;
        }


		$sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1'  and username='$usernam' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT c.* FROM(SELECT TOP $rows b.*,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' and b.id not in (
                SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' order by cast(id as int)))c $where order by cast(idrup as int)";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
       		
        foreach($query1->result_array() as $resulte){ 
			$ketfinal = $resulte['is_final'];
			if($ketfinal==1){
				$ketfinal='SUDAH';
			}else{
				$ketfinal='BELUM';
			}	
			
			$ketumumkan = $resulte['umumkan'];
			if($ketumumkan==1){
				$ketumumkan='SUDAH';
			}else{
				$ketumumkan='BELUM';
			}

            $ketrevisi = $resulte['is_revisi'];
            if($ketrevisi==1 || $ketrevisi==2 || $ketrevisi==3){
                $ketrevisi='YA';
            }else{
                $ketrevisi='-';
            }	
			
            $row[] = array(
                        'idx'          	=> $ii,        
						'idrup'   		=> $resulte['idrup'],
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'tahun'   	    => $resulte['tahun'],
                        'nmskpd'    	=> $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                
                        'kd_program'    => $resulte['kd_program'],
						'nm_program'    => $resulte['nm_program'],
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
                        'mtd_pengadaan'	=> str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'   	=> $resulte['pilih_awal'],
                        'pilih_akhir'  	=> $resulte['pilih_akhir'],
                        'kerja_mulai'	=> $resulte['kerja_mulai'],
                        'kerja_akhir'	=> $resulte['kerja_akhir'],
                        'aktif'			=> $resulte['aktif'],
                        'umumkan'   	=> $resulte['umumkan'],
                        'is_final'   	=> $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
						'ket_final'		=> $ketfinal,
						'ket_umumkan'	=> $ketumumkan,
                        'ket_revisi'    => $ketrevisi,
                        'id_swakelola'	=> $resulte['id_swakelola'],
                        'no_renja'	    => $resulte['no_renja'],
                        'izin_tahun_jamak'	=> $resulte['izin_tahun_jamak'],
						'tanggal_kebutuhan'	=> $resulte['tanggal_kebutuhan'],
						'tanggal_kebutuhan_2'	=> $resulte['tanggal_kebutuhan_akhir']
                        );
                        $ii++;
        }
		$result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
	}

    function loadPenyedia_myears() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');        
        $usernam = $this->session->userdata('pcNama');
        
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd,0,7);
        $dbidang = substr($bid,8,2);
        //$kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';$and ='';$row = '';
        if ($kriteria <> ''){                               
            $where="WHERE ( upper(nm_paket_gab) like upper('%$kriteria%') or upper(IDRUP) like upper('%$kriteria%')) ";  
            $and="AND ( upper(nm_paket) like upper('%$kriteria%') or upper(IDRUP) like upper('%$kriteria%')) ";           
            $rows=1000;
        }


        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1' and (izin_tahun_jamak<>'')  and username='$usernam' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT c.* FROM(SELECT TOP $rows b.*,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and (izin_tahun_jamak<>'') and username='$usernam' and b.id not in (
                SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' and (izin_tahun_jamak<>'') and username='$usernam' order by cast(id as int)))c $where order by cast(idrup as int)";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }

            $ketrevisi = $resulte['is_revisi'];
            if($ketrevisi==1 || $ketrevisi==2 || $ketrevisi==3){
                $ketrevisi='YA';
            }else{
                $ketrevisi='-';
            }   
            
            $row[] = array(
                        'idx'           => $ii,        
                        'idrup'         => $resulte['idrup'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'ket_revisi'    => $ketrevisi,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2'   => $resulte['tanggal_kebutuhan_akhir']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

    function loadPenyedia_revisi() {
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
        $where ='';$and ='';$row = '';
        if ($kriteria <> ''){                               
            $where="WHERE ( upper(nm_paket_gab) like upper('%$kriteria%') or upper(IDRUP) like upper('%$kriteria%')) ";  
            $and="AND ( upper(nm_paket) like upper('%$kriteria%') or upper(IDRUP) like upper('%$kriteria%')) ";           
            $rows=1000;
        }
        
        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1' and is_revisi='4' and username='$usernam' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT c.* FROM(SELECT TOP $rows b.*,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) sumber_dana,
                (select top 1 'ALASAN REVISI: '+alasan_revisi as alasan_revisi from sirup_history_paket where idrup=b.idrup_lama) alasan_revisi
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and is_revisi='4' and username='$usernam' and b.id not in (
                SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' and is_revisi='4' and username='$usernam' order by cast(id as int)))c $where order by cast(idrup as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }   
            
            $row[] = array(
                        'idx'           => $ii,        
                        'idrup'         => $resulte['idrup'],
                        'idrup_lama'    => $resulte['idrup_lama'],
                        'alasan_revisi' => $resulte['alasan_revisi'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'username'      => $resulte['username'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2'   => $resulte['tanggal_kebutuhan_akhir']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }


    function loadPenyedia_pa() {
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
        $where ='';$row = '';
        if ($kriteria <> ''){                               
            $where="WHERE ( upper(nm_paket_gab) like upper('%$kriteria%') or upper(idrup) like upper('%$kriteria%')) ";            
            $rows=1000;
        }
        
        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "
                SELECT c.* FROM(
                SELECT TOP $rows b.*,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk and username=b.username) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and cast(b.idrup as varchar)+b.username not in (
                SELECT TOP $offset cast(idrup as varchar)+username from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' order by cast(idrup as int)))c $where order by cast(idrup as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }

            $ketrevisi = $resulte['is_revisi'];
            if($ketrevisi==1 || $ketrevisi==2 || $ketrevisi==3){
                $ketrevisi='YA';
            }else{
                $ketrevisi='-';
            }  
            
            $row[] = array(
                        'idx'           => $ii,        
                        'idrup'         => $resulte['idrup'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',               
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'ket_revisi'    => $ketrevisi,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2'   => $resulte['tanggal_kebutuhan_akhir']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

    function loadPenyedia_pa_revisi() {
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
        $where ='';$row = '';
        if ($kriteria <> ''){                               
            $where="WHERE ( upper(nm_paket_gab) like upper('%$kriteria%') or upper(idrup) like upper('%$kriteria%')) ";            
            $rows=1000;
        }
        
        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1' and umumkan='1'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "
                SELECT c.* FROM(
                SELECT TOP $rows b.*,
                (select top 1 count(idrup_lama) id from sirup_header where idrup_lama=b.idrup) as revisi_init,
                (select top 1 alasan_revisi from sirup_history_paket where idrup=b.idrup) as revisi_alasan,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk and username=b.username) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and umumkan='1' and cast(b.idrup as varchar)+b.username not in (
                SELECT TOP $offset cast(idrup as varchar)+username from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' and umumkan='1' order by cast(idrup as int)))c $where order by cast(idrup as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }  

            $revisi = $resulte['is_revisi'];
            if($revisi==1 || $revisi==2 || $revisi==3){
                $revisi='YA';
            }else{
                $revisi='-';
            }  
            
            $row[] = array(
                        'idx'           => $ii,        
                        'idrup'         => $resulte['idrup'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',               
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => trim($resulte['is_revisi']),
                        'username'      => $resulte['username'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'ket_revisi'    => $revisi,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2' => $resulte['tanggal_kebutuhan_akhir'],
                        'revisi_alasan' => $resulte['revisi_alasan'],
                        'revisi_init' => $resulte['revisi_init']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

    function loadPenyedia_pa_kegiatan() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');        
        $usernam = $this->session->userdata('pcNama');
        
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd,0,7);
        $dbidang = substr($bid,8,2);
        $kriteria = '';
        $and = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="WHERE kd_kegiatan='$kriteria'";  
            $and="AND kd_kegiatan='$kriteria'";           
        }
        
        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1' $and";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "
                SELECT c.* FROM(
                SELECT b.*,
                (select top 1 count(idrup_lama) id from sirup_header where idrup_lama=b.idrup) as revisi_init,
                (select top 1 alasan_revisi from sirup_history_paket where idrup=b.idrup) as revisi_alasan,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk and username=b.username) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1')c $where order by cast(idrup as int)";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        $row="";
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }   

            $revisi = $resulte['is_revisi'];
            if($revisi==1 || $revisi==2 || $revisi==3){
                $revisi='YA';
            }else{
                $revisi='-';
            }
            
            $row[] = array(
                        'idx'           => $ii,        
                        'idrup'         => $resulte['idrup'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',               
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'ket_revisi'    => $revisi,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2'   => $resulte['tanggal_kebutuhan_akhir'],
                        'revisi_alasan' => $resulte['revisi_alasan'],
                        'revisi_init' => $resulte['revisi_init']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

    function loadPenyedia_pa_kegiatan_revisi() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');        
        $usernam = $this->session->userdata('pcNama');
        
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd,0,7);
        $dbidang = substr($bid,8,2);
        $kriteria = '';
        $and = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="WHERE kd_kegiatan='$kriteria'";  
            $and="AND kd_kegiatan='$kriteria'";           
        }
        
        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1'  and umumkan='1' $and";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "
                SELECT c.* FROM(
                SELECT b.*,
                (select top 1 count(idrup_lama) id from sirup_header where idrup_lama=b.idrup) as revisi_init,
                (select top 1 alasan_revisi from sirup_history_paket where idrup=b.idrup) as revisi_alasan,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk and username=b.username) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and id=b.id and username=b.username) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and umumkan='1')c $where order by cast(idrup as int)";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        $row="";
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }   

            $revisi = $resulte['is_revisi'];
            if($revisi==1 || $revisi==2 || $revisi==3){
                $revisi='YA';
            }else{
                $revisi='-';
            }
            
            $row[] = array(
                        'idx'           => $ii,        
                        'idrup'         => $resulte['idrup'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',               
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'ket_revisi'    => $revisi,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2'   => $resulte['tanggal_kebutuhan_akhir'],
                        'revisi_alasan' => $resulte['revisi_alasan'],
                        'revisi_init' => $resulte['revisi_init']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

	function loadPenyedia_kegiatan() {
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
		$row = '';
        if ($kriteria <> ''){                               
            $where="AND ( upper(kd_kegiatan) like upper('%$kriteria%')) ";            
        }
        
		$sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,
                (select top 1 count(idrup_lama) id from sirup_header where idrup_lama=b.idrup) as revisi_init,
                (select top 1 alasan_revisi from sirup_history_paket where idrup=b.idrup) as revisi_alasan,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
				(select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' $where and b.id not in (
				SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' and username='$usernam' $where order by cast(id as int)) order by cast(id as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
       		
        foreach($query1->result_array() as $resulte){ 
			$ketfinal = $resulte['is_final'];
			if($ketfinal==1){
				$ketfinal='SUDAH';
			}else{
				$ketfinal='BELUM';
			}	
			
			$ketumumkan = $resulte['umumkan'];
			if($ketumumkan==1){
				$ketumumkan='SUDAH';
			}else{
				$ketumumkan='BELUM';
			}	

            $revisi = $resulte['is_revisi'];
            if($revisi==1 || $revisi==2 || $revisi==3){
                $revisi='YA';
            }else{
                $revisi='-';
            }
			
            $row[] = array(
                        'idx'          	=> $ii,        
						'idrup'   		=> $resulte['idrup'],
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'tahun'   	    => $resulte['tahun'],
                        'nmskpd'    	=> $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                
                        'kd_program'    => $resulte['kd_program'],
						'nm_program'    => $resulte['nm_program'],
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
                        'mtd_pengadaan'	=> str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'   	=> $resulte['pilih_awal'],
                        'pilih_akhir'  	=> $resulte['pilih_akhir'],
                        'kerja_mulai'	=> $resulte['kerja_mulai'],
                        'kerja_akhir'	=> $resulte['kerja_akhir'],
                        'aktif'			=> $resulte['aktif'],
                        'umumkan'   	=> $resulte['umumkan'],
                        'is_final'   	=> $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
						'ket_final'		=> $ketfinal,
						'ket_umumkan'	=> $ketumumkan,
                        'ket_revisi'    => $revisi,
                        'id_swakelola'	=> $resulte['id_swakelola'],
                        'no_renja'	    => $resulte['no_renja'],
                        'izin_tahun_jamak'	=> $resulte['izin_tahun_jamak'],
						'tanggal_kebutuhan'	=> $resulte['tanggal_kebutuhan'],
						'tanggal_kebutuhan_2'	=> $resulte['tanggal_kebutuhan_akhir'],
                        'revisi_alasan' => $resulte['revisi_alasan'],
                        'revisi_init' => $resulte['revisi_init']
                        );
                        $ii++;
        }
		$result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
	}
    

    function loadPenyedia_kegiatan_revisi() {
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
        $row = '';
        if ($kriteria <> ''){                               
            $where="AND ( upper(kd_kegiatan) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='1' and is_revisi=4 and is_deleted is null and username='$usernam' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,
                (select top 1 count(idrup_lama) id from sirup_header where idrup_lama=b.idrup) as revisi_init,
                (select top 1 alasan_revisi from sirup_history_paket where idrup=b.idrup) as revisi_alasan,
                (select top 1 nm_program from ms_program where kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nama from ms_ttd where kd_skpd='$kd_skpd' and id_ttd=b.id_ppk) as ppk,
                (select top 1 isi_paket from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) nm_paket_gab,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and username='$usernam' and id=b.id) sumber_dana
                from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='1' and is_revisi=4 and is_deleted is null and username='$usernam' $where and b.id not in (
                SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='1' and is_revisi=4 and is_deleted is null and username='$usernam' $where order by cast(id as int)) order by cast(id as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }   

            $revisi = $resulte['is_revisi'];
            if($revisi==1 || $revisi==2 || $revisi==3){
                $revisi='YA';
            }else{
                $revisi='-';
            }
            
            $row[] = array(
                        'idx'           => $ii,        
                        'idrup'         => $resulte['idrup'],
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'nm_paket_gab'  => $resulte['nm_paket_gab'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'ppk'           => $resulte['ppk'],
                        'volume'        => $resulte['volume'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'nuk'           => $resulte['nuk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => str_replace(" ","",$resulte['mtd_pengadaan']),
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'umumkan'       => $resulte['umumkan'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => $resulte['is_revisi'],
                        'username'      => $resulte['username'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'ket_revisi'    => $revisi,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'no_renja'      => $resulte['no_renja'],
                        'izin_tahun_jamak'  => $resulte['izin_tahun_jamak'],
                        'tanggal_kebutuhan' => $resulte['tanggal_kebutuhan'],
                        'tanggal_kebutuhan_2'   => $resulte['tanggal_kebutuhan_akhir'],
                        'revisi_alasan' => $resulte['revisi_alasan'],
                        'revisi_init' => $resulte['revisi_init']
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
            $where="AND ( upper(nm_paket) like upper('%$kriteria%') or upper(IDRUP) like upper('%$kriteria%')) ";            
            $rows=1000;
        }

		$sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='2' and username='$usernam' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,
				(select top 1 a.nm_program from ms_program a where a.kd_program=b.kd_program) as nm_program,
				(select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nm_lokasi from sirup_lokasi where kd_lokasi like b.lokasi ) nm_lokasi,
                (select top 1 nama from ms_ttd where id_ttd=b.id_ppk) namappk,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and jenis_paket='2' and id=b.id) sumber_dana from sirup_header b 
				where b.kd_skpd='$kd_skpd' and jenis_paket='2' and username='$usernam' $where and cast(b.id as varchar)+b.username not in (
				SELECT TOP $offset cast(id as varchar)+username from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='2' and username='$usernam' $where order by cast(id as int)) order by cast(id as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
       		
        foreach($query1->result_array() as $resulte){ 
			$ketfinal = $resulte['is_final'];
			if($ketfinal==1){
				$ketfinal='SUDAH';
			}else{
				$ketfinal='BELUM';
			}	
			
			$ketumumkan = $resulte['umumkan'];
			if($ketumumkan==1){
				$ketumumkan='SUDAH';
			}else{
				$ketumumkan='BELUM';
			}
			
            $row[] = array(
                        'idx'          	=> $ii,     
						'idrup'   		=> $resulte['idrup'],		
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'tahun'   	    => $resulte['tahun'],
                        'nmskpd'    	=> $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                                                                
                        'kd_program'    => $resulte['kd_program'],
						'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'lokasi'       	=> json_decode($resulte['lokasi']),
                        'nm_lokasi'   	=> $resulte['nm_lokasi'],
                        'det_lokasi'   	=> $resulte['det_lokasi'],
                        'jns_pengadaan' => json_decode($resulte['jns_pengadaan']),
                        'volume'   		=> $resulte['volume'],
                        'username'      => $resulte['username'],
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
						'ket_final'		=> $ketfinal,
						'ket_umumkan'	=> $ketumumkan,
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

    function loadSwakelola_pa() {
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
		$row="";
        if ($kriteria <> ''){                               
            $where="AND ( upper(nm_paket) like upper('%$kriteria%') or upper(idrup) like upper('%$kriteria%')) ";            
            $rows=1000;
        }

        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='2' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,
                (select top 1 a.nm_program from ms_program a where a.kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nm_lokasi from sirup_lokasi where kd_lokasi like b.lokasi ) nm_lokasi,
                (select top 1 nama from ms_ttd where id_ttd=b.id_ppk) namappk,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and jenis_paket='2' and id=b.id and username=b.username) sumber_dana from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='2' $where and cast(b.idrup as varchar)+b.username not in (
                SELECT TOP $offset cast(idrup as varchar)+username from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='2' $where order by cast(idrup as int)) order by cast(idrup as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }
            
            $row[] = array(
                        'idx'           => $ii,     
                        'idrup'         => $resulte['idrup'],       
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                  
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'lokasi'        => json_decode($resulte['lokasi']),
                        'nm_lokasi'     => $resulte['nm_lokasi'],
                        'det_lokasi'    => $resulte['det_lokasi'],
                        'jns_pengadaan' => json_decode($resulte['jns_pengadaan']),
                        'volume'        => $resulte['volume'],
                        'username'      => $resulte['username'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => $resulte['mtd_pengadaan'],
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'user'          => $resulte['username'],
                        'namappk'       => $resulte['namappk'],
                        'idppk'       => $resulte['id_ppk'],                        
                        'umumkan'       => $resulte['umumkan'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'is_final'      => $resulte['is_final'],
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

    function loadSwakelola_pa_revisi() {
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
		$row="";
        if ($kriteria <> ''){                               
            $where="AND ( upper(nm_paket) like upper('%$kriteria%') or upper(idrup) like upper('%$kriteria%')) ";            
            $rows=1000;
        }

        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='2' and umumkan='1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,
                (select top 1 count(idrup_lama) id from sirup_header where idrup_lama=b.idrup) as revisi_init,
                (select top 1 alasan_revisi from sirup_history_paket where idrup=b.idrup) as revisi_alasan,
                (select top 1 a.nm_program from ms_program a where a.kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nm_lokasi from sirup_lokasi where kd_lokasi like b.lokasi ) nm_lokasi,
                (select top 1 nama from ms_ttd where id_ttd=b.id_ppk) namappk,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and jenis_paket='2' and id=b.id and username=b.username) sumber_dana from sirup_header b where b.kd_skpd='$kd_skpd' and umumkan='1' and jenis_paket='2' $where and cast(b.idrup as varchar)+b.username not in (
                SELECT TOP $offset cast(idrup as varchar)+username from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='2' and umumkan='1' $where order by cast(idrup as int)) order by cast(idrup as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }

            $revisi = $resulte['is_revisi'];
            if($revisi==1 || $revisi==2 || $revisi==3){
                $revisi='YA';
            }else{
                $revisi='-';
            }  
            
            $row[] = array(
                        'idx'           => $ii,     
                        'idrup'         => $resulte['idrup'],       
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                  
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'lokasi'        => json_decode($resulte['lokasi']),
                        'nm_lokasi'     => $resulte['nm_lokasi'],
                        'det_lokasi'    => $resulte['det_lokasi'],
                        'jns_pengadaan' => json_decode($resulte['jns_pengadaan']),
                        'volume'        => $resulte['volume'],
                        'username'      => $resulte['username'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => $resulte['mtd_pengadaan'],
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'user'          => $resulte['username'],
                        'namappk'       => $resulte['namappk'],
                        'idppk'       => $resulte['id_ppk'],                        
                        'umumkan'       => $resulte['umumkan'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'ket_revisi'    => $revisi,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'is_final'      => $resulte['is_final'],
                        'is_revisi'     => trim($resulte['is_revisi']),
                        'tipe_swakelola'=> $resulte['tipe_swakelola'],
                        'nama_satker_lain'=> $resulte['nama_satker_lain'],
                        'revisi_alasan' => $resulte['revisi_alasan'],
                        'revisi_init' => $resulte['revisi_init']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }
    
	function loadSwakelola_kegiatan() {
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
            $where="AND kd_kegiatan = '$kriteria'";            
        }
		$row="";
        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='2' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,
                (select top 1 a.nm_program from ms_program a where a.kd_program=left(b.kd_kegiatan,7)) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nm_lokasi from sirup_lokasi where kd_lokasi like b.lokasi ) nm_lokasi,
                (select top 1 nama from ms_ttd where id_ttd=b.id_ppk) namappk,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and jenis_paket='2' and id=b.id and username=b.username) sumber_dana from sirup_header b where b.kd_skpd='$kd_skpd' and jenis_paket='2' $where and b.id not in (
                SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='2' $where order by cast(id as int)) order by cast(id as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }
            
            $row[] = array(
                        'idx'           => $ii,     
                        'idrup'         => $resulte['idrup'],       
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                  
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'lokasi'        => json_decode($resulte['lokasi']),
                        'nm_lokasi'     => $resulte['nm_lokasi'],
                        'det_lokasi'    => $resulte['det_lokasi'],
                        'jns_pengadaan' => json_decode($resulte['jns_pengadaan']),
                        'volume'        => $resulte['volume'],
                        'username'      => $resulte['username'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => $resulte['mtd_pengadaan'],
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'user'          => $resulte['username'],
                        'namappk'       => $resulte['namappk'],
                        'idppk'       => $resulte['id_ppk'],                        
                        'umumkan'       => $resulte['umumkan'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'is_final'      => $resulte['is_final'],
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

    function loadSwakelola_kegiatan_tes() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $usernam = $this->session->userdata('pcNama');
        
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd,0,7);
        $dbidang = substr($bid,8,2);
        $kriteria = '';
        $kriteria = "2.10.2.10.01.00.16.014";//$this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND ( upper(kd_kegiatan) like upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from sirup_header where kd_skpd='$kd_skpd' and jenis_paket='2' and username='$usernam' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows b.*,
                (select top 1 a.nm_program from m_prog a where a.kd_program=b.kd_program) as nm_program,
                (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd,
                (select top 1 nm_lokasi from sirup_lokasi where kd_lokasi like b.lokasi ) nm_lokasi,
                (select top 1 nama from ms_ttd where id_ttd=b.id_ppk) namappk,
                (select top 1 kd_sd from sirup_detail where kd_skpd='$kd_skpd' and jenis_paket='2' and id=b.id) sumber_dana from sirup_header b 
                where b.kd_skpd='$kd_skpd' and jenis_paket='2' and username='$usernam' $where and b.id not in (
                SELECT TOP $offset id from sirup_header WHERE kd_skpd='$kd_skpd' and jenis_paket='2' and username='$usernam' $where order by cast(id as int)) order by cast(id as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte){ 
            $ketfinal = $resulte['is_final'];
            if($ketfinal==1){
                $ketfinal='SUDAH';
            }else{
                $ketfinal='BELUM';
            }   
            
            $ketumumkan = $resulte['umumkan'];
            if($ketumumkan==1){
                $ketumumkan='SUDAH';
            }else{
                $ketumumkan='BELUM';
            }
            
            $row[] = array(
                        'idx'           => $ii,     
                        'idrup'         => $resulte['idrup'],       
                        'id'            => $resulte['id'],
                        'skpd'          => $resulte['kd_skpd'],
                        'tahun'         => $resulte['tahun'],
                        'nmskpd'        => $resulte['nm_skpd'],
                        'nm_paket'      => $resulte['nm_paket'],
                        'kldi'          => 'Pemerintah Daerah Kota Pontianak',                                                                
                        'kd_program'    => $resulte['kd_program'],
                        'nm_program'    => $resulte['nm_program'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'lokasi'        => json_decode($resulte['lokasi']),
                        'nm_lokasi'     => $resulte['nm_lokasi'],
                        'det_lokasi'    => $resulte['det_lokasi'],
                        'jns_pengadaan' => json_decode($resulte['jns_pengadaan']),
                        'volume'        => $resulte['volume'],
                        'username'      => $resulte['username'],
                        'uraian'        => $resulte['uraian'],
                        'spesifikasi'   => $resulte['spesifikasi'],
                        'tkdn'          => $resulte['tkdn'],
                        'uk'            => $resulte['uk'],
                        'pradipa'       => $resulte['pradipa'],
                        'sumber_dana'   => $resulte['sumber_dana'],                        
                        'total'         => number_format($resulte['total'],"2",".",","),
                        'mtd_pengadaan' => $resulte['mtd_pengadaan'],
                        'pilih_awal'    => $resulte['pilih_awal'],
                        'pilih_akhir'   => $resulte['pilih_akhir'],
                        'kerja_mulai'   => $resulte['kerja_mulai'],
                        'kerja_akhir'   => $resulte['kerja_akhir'],
                        'aktif'         => $resulte['aktif'],
                        'user'          => $resulte['username'],
                        'namappk'       => $resulte['namappk'],
                        'idppk'       => $resulte['id_ppk'],                        
                        'umumkan'       => $resulte['umumkan'],
                        'ket_final'     => $ketfinal,
                        'ket_umumkan'   => $ketumumkan,
                        'id_swakelola'  => $resulte['id_swakelola'],
                        'is_final'      => $resulte['is_final'],
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
        $user   = $this->input->post('user');
        $sql 	= "SELECT * FROM sirup_detail WHERE id='$id' AND kd_skpd='$skpd' AND username='$user'";
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
                        'isi_paket'     => $resulte['isi_paket'],
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
                        'user'          => $resulte['username'],
                        'jnsp'           => $resulte['jns_pengadaan'],
                        'nmjnsp'           => $resulte['nmjns_pengadaan'],                        
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
        $user   = $this->input->post('user');
        $sql 	= "SELECT a.id,a.kd_skpd,a.prov,a.lokasi,a.nm_lokasi,a.det_lokasi,a.username FROM sirup_detail_lokasi a 
                   where a.kd_skpd='$skpd' AND a.id='$id' AND a.username='$user'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'idx'          	=> $ii,        
                        'id'   			=> $resulte['id'],
                        'skpd'    		=> $resulte['kd_skpd'],
                        'prov'          => $resulte['prov'],
                        'nm_lokasi'     => $resulte['nm_lokasi'],                        
                        'det_lokasi'    => $resulte['det_lokasi'],                        
                        'user'          => $resulte['username'],
                        'lokasi'        => $resulte['lokasi'],                        
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
	
    function userppk(){
	    $usernam = $this->session->userdata('pcNama');
        $skpd = $this->session->userdata('kdskpd');
        
		$sql_ 		= "select nama,id,username from ms_ttd where username='$usernam' and kd_skpd='$skpd'";
		$data 		= $this->db->query($sql_);
		foreach($data->result_array() as $resulte){ 
            $result = array(
                        'nama' => $resulte['nama'],
                        'did' => $resulte['id'],
                        'user' => $resulte['username']                        
                        );
        }
		
        echo json_encode($result);
    	$data->free_result();
	}
    
     function savePenyedia(){
        date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');  
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');
        $cpaket    	= $this->input->post('cpaket');                                 
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');  
        $clok    	= $this->input->post('clok');  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= $this->input->post('cjns');  
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
		$cdet_lok   = $this->input->post('cdet_lok');  		
        $now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
        $cfinal     = $this->input->post('cfinall');
        $idppk      = $this->input->post('cidppk');
        $cjamak     = $this->input->post('cjamak');
        $crenja     = $this->input->post('crenja');		
        
        $sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username,is_final,id_ppk,no_renja,izin_tahun_jamak)
		        VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','1','$usernm','$cfinal','$idppk','$crenja','$cjamak')"; 
		$asg = $this->db->query($sql);
		        
		if ($asg){
			$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu,username,jns_pengadaan,nmjns_pengadaan) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				
                 $sql = "INSERT INTO sirup_detail_lokasi(id,prov,lokasi,nm_lokasi,det_lokasi,username,kd_skpd) $cdet_lok";
			     $asg = $this->db->query($sql);
			     if($asg){
				    $msg = array('pesan'=>'1');
				    echo json_encode($msg);	
			     }else{
				    $msg = array('pesan'=>'2');
				    echo json_encode($msg);	
			     }
                	
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
		date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket    	= $this->input->post('cpaket');                            
        $clok    	= $this->input->post('clok');  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd'); 
		$ctglkeb	= $this->input->post('ctglkeb');
		$ctglkeb_2	= $this->input->post('ctglkeb_2');	
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
        $cdet_lok 	= $this->input->post('cdet_lok');         
        $cidppk     = $this->input->post('cidppk');
        $cfinal     = $this->input->post('cfinall');
        $cjamak     = $this->input->post('cjamak');
        $crenja     = $this->input->post('crenja');
         
		$now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		$sql1 = "DELETE FROM sirup_detail WHERE id='$cid' AND kd_skpd='$cskpd' and username='$usernm'";
		$sql2 = "DELETE FROM sirup_detail_lokasi WHERE id='$cid' AND kd_skpd='$cskpd' and username='$usernm'";
		$asg1 = $this->db->query($sql1);
		$asg2 = $this->db->query($sql2);
		
		$sql = "UPDATE sirup_header SET kd_program='$ckdprog',kd_kegiatan='$ckdgiat',nm_kegiatan='$cnmgiat',nm_paket='$cpaket',volume='$cvol',uraian='$curai',spesifikasi='$cspes',tkdn='$ctkdn',uk='$cuk',nuk='$cnuk',pradipa='$cpra',total='$ctot',mtd_pengadaan='$cmtd',pilih_awal='$cpilawl',tanggal_kebutuhan='$ctglkeb',tanggal_kebutuhan_akhir='$ctglkeb_2',pilih_akhir='$cpilakhir',kerja_mulai='$ckerawal',kerja_akhir='$ckerakhir',last_update='$now',aktif='$caktif',umumkan='$cumum',jenis_paket='1',username='$usernm',id_ppk='$cidppk',is_final='$cfinal',no_renja='$crenja',izin_tahun_jamak='$cjamak'
		          WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun'";
		$asg = $this->db->query($sql);
		
		if ($asg){
				$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu,username,jns_pengadaan,nmjns_pengadaan) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				
                 $sql = "INSERT INTO sirup_detail_lokasi(id,prov,lokasi,nm_lokasi,det_lokasi,username,kd_skpd) $cdet_lok";
			     $asg = $this->db->query($sql);
			     if($asg){
				    $msg = array('pesan'=>'1');
				    echo json_encode($msg);	
			     }else{
				    $msg = array('pesan'=>'2');
				    echo json_encode($msg);	
			     }
                	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 }

     function editPenyedia_srevisi(){
        date_default_timezone_set('Asia/Jakarta');
        $cid        = $this->input->post('cid');  
        $cidruplama = $this->input->post('cidruplama');        
        $cskpd      = $this->input->post('cskpd');  
        $ctahun     = $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket     = $this->input->post('cpaket');                            
        $clok       = json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns       = json_encode($this->input->post('cjns'));  
        $cvol       = $this->input->post('cvol');  
        $curai      = $this->input->post('curai');  
        $cspes      = $this->input->post('cspes');  
        $ctot       = $this->input->post('ctot');  
        $cmtd       = $this->input->post('cmtd'); 
        $ctglkeb    = $this->input->post('ctglkeb');
        $ctglkeb_2  = $this->input->post('ctglkeb_2');  
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa     = $this->input->post('cidswa');  
        $ctkdn      = $this->input->post('ctkdn');  
        $cuk        = $this->input->post('cuk'); 
        $cnuk       = $this->input->post('cnuk');         
        $cpra       = $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum      = $this->input->post('cumum');  
        $cdet       = $this->input->post('cdet'); 
        $cdet_lok   = $this->input->post('cdet_lok');         
        $cidppk     = $this->input->post('cidppk');
        $cfinal     = $this->input->post('cfinall');
        $cjamak     = $this->input->post('cjamak');
        $crenja     = $this->input->post('crenja');
         
        $now        = date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
        
        $sql1 = "DELETE FROM sirup_detail WHERE id='$cid' AND kd_skpd='$cskpd' and username='$usernm'";
        $sql2 = "DELETE FROM sirup_detail_lokasi WHERE id='$cid' AND kd_skpd='$cskpd' and username='$usernm'";
        $asg1 = $this->db->query($sql1);
        $asg2 = $this->db->query($sql2);
        
        $sql = "UPDATE sirup_header SET kd_program='$ckdprog',kd_kegiatan='$ckdgiat',nm_kegiatan='$cnmgiat',nm_paket='$cpaket',volume='$cvol',uraian='$curai',spesifikasi='$cspes',tkdn='$ctkdn',uk='$cuk',nuk='$cnuk',pradipa='$cpra',total='$ctot',mtd_pengadaan='$cmtd',pilih_awal='$cpilawl',tanggal_kebutuhan='$ctglkeb',tanggal_kebutuhan_akhir='$ctglkeb_2',pilih_akhir='$cpilakhir',kerja_mulai='$ckerawal',kerja_akhir='$ckerakhir',last_update='$now',aktif='$caktif',umumkan='$cumum',jenis_paket='1',username='$usernm',id_ppk='$cidppk',is_final='$cfinal',no_renja='$crenja',izin_tahun_jamak='$cjamak'
                  WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun'";
        $asg = $this->db->query($sql);
        
        if ($asg){
                $sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu,username,jns_pengadaan,nmjns_pengadaan) $cdet";
            $asg = $this->db->query($sql);
            if($asg){
                
                 $sql = "INSERT INTO sirup_detail_lokasi(id,prov,lokasi,nm_lokasi,det_lokasi,username,kd_skpd) $cdet_lok";
                 $asg = $this->db->query($sql);
                 if($asg){
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg); 
                 }else{
                    $msg = array('pesan'=>'2');
                    echo json_encode($msg); 
                 }
                    
            }else{
                $msg = array('pesan'=>'2');
                echo json_encode($msg); 
            }
        }else{
            $msg = array('pesan'=>'0');
            echo json_encode($msg); 
        }
     }
	 
	 function simpanPenyedia(){
		 date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket    	= $this->input->post('cpaket');                            
        $clok    	= $this->input->post('clok');  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= json_encode($this->input->post('cjns'));  
        $cvol    	= $this->input->post('cvol');  
        $curai    	= $this->input->post('curai');  
        $cspes    	= $this->input->post('cspes');  
        $ctot    	= $this->input->post('ctot');  
        $cmtd    	= $this->input->post('cmtd');  
		$ctglkeb	= $this->input->post('ctglkeb'); 
		$ctglkeb_2	= $this->input->post('ctglkeb_2'); 
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
        $cdet_lok 	= $this->input->post('cdet_lok');         
        $cidppk     = $this->input->post('cidppk');
        $cfinal     = $this->input->post('cfinall');
        $cjamak     = $this->input->post('cjamak');
        $crenja     = $this->input->post('crenja');
         
		$now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username,is_final,id_ppk,no_renja,izin_tahun_jamak,tanggal_kebutuhan,tanggal_kebutuhan_akhir)
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','1','$usernm','$cfinal','$cidppk','$crenja','$cjamak','$ctglkeb','$ctglkeb_2')";
		$asg = $this->db->query($sql);
		
		if ($asg){
				$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu,username,jns_pengadaan,nmjns_pengadaan) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				
                 $sql = "INSERT INTO sirup_detail_lokasi(id,prov,lokasi,nm_lokasi,det_lokasi,username,kd_skpd) $cdet_lok";
			     $asg = $this->db->query($sql);
			     if($asg){
				    $msg = array('pesan'=>'1');
				    echo json_encode($msg);	
			     }else{
				    $msg = array('pesan'=>'2');
				    echo json_encode($msg);	
			     }
                	
			}else{
				$msg = array('pesan'=>'2');
				echo json_encode($msg);	
			}
		}else{
			$msg = array('pesan'=>'0');
			echo json_encode($msg);	
		}
	 }

     function simpanPenyedia_revisi(){
         date_default_timezone_set('Asia/Jakarta');
        $cid        = $this->input->post('cid'); 
        $cidruplama = $this->input->post('cidruplama');         
        $cskpd      = $this->input->post('cskpd');  
        $ctahun     = $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket     = $this->input->post('cpaket');                            
        $clok       = json_encode($this->input->post('clok'));  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns       = json_encode($this->input->post('cjns'));  
        $cvol       = $this->input->post('cvol');  
        $curai      = $this->input->post('curai');  
        $cspes      = $this->input->post('cspes');  
        $ctot       = $this->input->post('ctot');  
        $cmtd       = $this->input->post('cmtd');  
        $ctglkeb    = $this->input->post('ctglkeb'); 
        $ctglkeb_2  = $this->input->post('ctglkeb_2'); 
        $cpilawl    = $this->input->post('cpilawl');  
        $cpilakhir  = $this->input->post('cpilakhir');  
        $ckerawal   = $this->input->post('ckerawalan');  
        $ckerakhir  = $this->input->post('ckerakhir');  
        $cidswa     = $this->input->post('cidswa');  
        $ctkdn      = $this->input->post('ctkdn');  
        $cuk        = $this->input->post('cuk'); 
        $cnuk       = $this->input->post('cnuk');         
        $cpra       = $this->input->post('cpra');  
        $caktif     = $this->input->post('caktif');  
        $cumum      = $this->input->post('cumum');  
        $cdet       = $this->input->post('cdet'); 
        $cdet_lok   = $this->input->post('cdet_lok');         
        $cidppk     = $this->input->post('cidppk');
        $cfinal     = $this->input->post('cfinall');
        $cjamak     = $this->input->post('cjamak');
        $crenja     = $this->input->post('crenja');
         
        $now        = date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
        
        $sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username,is_final,id_ppk,no_renja,izin_tahun_jamak,tanggal_kebutuhan,tanggal_kebutuhan_akhir,is_revisi,idrup_lama)
                            VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$clok','$cdetlok','$cjns','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','1','$usernm','$cfinal','$cidppk','$crenja','$cjamak','$ctglkeb','$ctglkeb_2','4','$cidruplama')";
        $asg = $this->db->query($sql);

        
        
        if ($asg){
                $sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu,username,jns_pengadaan,nmjns_pengadaan) $cdet";
            $asg = $this->db->query($sql);
            if($asg){
                 $sql_idrup = $this->db->query("SELECT idrup FROM sirup_header WHERE idrup_lama='$cidruplama'")->row();
                 $sql_id = $sql_idrup->idrup;
                 $sql = "UPDATE sirup_history_paket SET idrup_to='$sql_id' WHERE idrup='$cidruplama'";
                 $asg = $this->db->query($sql);   

                 $sql = "INSERT INTO sirup_detail_lokasi(id,prov,lokasi,nm_lokasi,det_lokasi,username,kd_skpd) $cdet_lok";
                 $asg = $this->db->query($sql);
                 if($asg){
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg); 
                 }else{
                    $msg = array('pesan'=>'2');
                    echo json_encode($msg); 
                 }
                    
            }else{
                $msg = array('pesan'=>'2');
                echo json_encode($msg); 
            }
        }else{
            $msg = array('pesan'=>'0');
            echo json_encode($msg); 
        }
     }

	function editPenyedia_validasi(){
		date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');    
		$cfinall	= $this->input->post('cfinall');
        $now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		//if($cfinall==1){
		//	$caktif = 1;
		//}else{
		//	$caktif = 0;
		//}
		
		$sql = "UPDATE sirup_header SET is_final='$cfinall',last_update='$now'
		        WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='1'";
		$asg = $this->db->query($sql);
		
		$sql = "insert into sirup_history_otoritas (id,tahun,kd_skpd,create_time,username,ket,status) values
		       ('$cid','$ctahun','$cskpd','$now','$usernm','Validasi Penyedia Oleh PPK','1')";
		$asg = $this->db->query($sql);
		
		
		if($asg){
			$msg = array('pesan'=>'1');
			echo json_encode($msg);	
		}else{
			$msg = array('pesan'=>'2');
			echo json_encode($msg);	
		}
	 }
	 
	 function editPenyedia_validasi_batal(){
		 date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');    
		$cfinall	= $this->input->post('cfinall');
        $now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		//if($cfinall==1){
		//	$caktif = 1;
		//}else{
		//	$caktif = 0;
		//}
		
		$sql = "Select umumkan from sirup_header
		        WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='1'";
		$cek = $this->db->query($sql)->row();
		if($cek->umumkan==1){
			$msg = array('pesan'=>'3');
			echo json_encode($msg);	
		}else{
		
		$sql = "UPDATE sirup_header SET is_final='$cfinall',last_update='$now'
		        WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='1'";
		$asg = $this->db->query($sql);
		
		$sql = "insert into sirup_history_otoritas (id,tahun,kd_skpd,create_time,username,ket,status) values
		       ('$cid','$ctahun','$cskpd','$now','$usernm','Batal Validasi Penyedia Oleh PPK','0')";
		$asg = $this->db->query($sql);
		
		
		if($asg){
			$msg = array('pesan'=>'1');
			echo json_encode($msg);	
		}else{
			$msg = array('pesan'=>'2');
			echo json_encode($msg);	
		}
		}
	 }
	 
	 function editPenyedia_umumkan(){
		date_default_timezone_set('Asia/Jakarta'); 
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');    
		$cfinall	= $this->input->post('cfinall');
        $now 		= date('Y-m-d H:i:s');
        $usernm     = $this->input->post('cuser');
        $usernm_pa  = $this->session->userdata('pcNama');
		
		if($cfinall==1){
			$caktif = 1;
		}else{
			$caktif = 0;
		}
		
		$sql = "UPDATE sirup_header SET aktif='$caktif',umumkan='$cfinall',last_update='$now'
		        WHERE idrup='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='1'";
		$asg = $this->db->query($sql);
		
		$sql = "insert into sirup_history_otoritas (id,tahun,kd_skpd,create_time,username,ket,status) values
		       ('$cid','$ctahun','$cskpd','$now','$usernm_pa','Umumkan Penyedia Oleh PA/KPA','2')";
		$asg = $this->db->query($sql);
		
		
		if($asg){
			$msg = array('pesan'=>'1');
			echo json_encode($msg);	
		}else{
			$msg = array('pesan'=>'2');
			echo json_encode($msg);	
		}
	 }
	 
	function editPenyedia_revisi(){
        date_default_timezone_set('Asia/Jakarta'); 
        $cid        = $this->input->post('cid');          
        $cskpd      = $this->input->post('cskpd');  
        $ctahun     = $this->input->post('ctahun');    
        $calasan    = $this->input->post('calasan');
        $ctipe      = $this->input->post('ctipe');
        $cjenis     = $this->input->post('cjenis');
        $now        = date('Y-m-d H:i:s');
        $usernm     = $this->input->post('cuser');
        $usernm_pa  = $this->session->userdata('pcNama');
        

        $sql = "UPDATE sirup_header SET is_revisi='$ctipe',is_deleted='1'
                WHERE idrup='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='1'";
        $asg = $this->db->query($sql);
        
        $sql = "insert into sirup_history_paket (idrup,idrup_to,jenis,tipe,alasan_revisi,create_time) values
               ('$cid','$cid','$cjenis','$ctipe','$calasan','$now')";
        $asg = $this->db->query($sql);
        
        
        if($asg){
            $msg = array('pesan'=>'1');
            echo json_encode($msg); 
        }else{
            $msg = array('pesan'=>'2');
            echo json_encode($msg); 
        }
     }

     function editSwakelola_revisi(){
        date_default_timezone_set('Asia/Jakarta'); 
        $cid        = $this->input->post('cid');          
        $cskpd      = $this->input->post('cskpd');  
        $ctahun     = $this->input->post('ctahun');    
        $calasan    = $this->input->post('calasan');
        $ctipe      = $this->input->post('ctipe');
        $cjenis     = $this->input->post('cjenis');
        $now        = date('Y-m-d H:i:s');
        $usernm     = $this->input->post('cuser');
        $usernm_pa  = $this->session->userdata('pcNama');
        

        $sql = "UPDATE sirup_header SET is_revisi='$ctipe',is_deleted='1'
                WHERE idrup='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='2'";
        $asg = $this->db->query($sql);
        
        $sql = "insert into sirup_history_paket (idrup,idrup_to,jenis,tipe,alasan_revisi,create_time) values
               ('$cid','$cid','$cjenis','$ctipe','$calasan','$now')";
        $asg = $this->db->query($sql);
        
        
        if($asg){
            $msg = array('pesan'=>'1');
            echo json_encode($msg); 
        }else{
            $msg = array('pesan'=>'2');
            echo json_encode($msg); 
        }
     }

     function editPenyedia_revisi_batal(){
        date_default_timezone_set('Asia/Jakarta'); 
        $cid        = $this->input->post('cid');          
        $cskpd      = $this->input->post('cskpd');  
        $ctahun     = $this->input->post('ctahun');    
        $calasan    = $this->input->post('calasan');
        $ctipe      = $this->input->post('ctipe');
        $cjenis     = $this->input->post('cjenis');
        $now        = date('Y-m-d H:i:s');
        $usernm     = $this->input->post('cuser');
        $usernm_pa  = $this->session->userdata('pcNama');
        

        $sql = "UPDATE sirup_header SET is_revisi=NULL,is_deleted=NULL
                WHERE idrup='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='1'";
        $asg = $this->db->query($sql);
        
        $sql = "delete from sirup_history_paket where idrup='$cid'";
        $asg = $this->db->query($sql);
        
        
        if($asg){
            $msg = array('pesan'=>'1');
            echo json_encode($msg); 
        }else{
            $msg = array('pesan'=>'2');
            echo json_encode($msg); 
        }
     }

     function editSwakelola_revisi_batal(){
        date_default_timezone_set('Asia/Jakarta'); 
        $cid        = $this->input->post('cid');          
        $cskpd      = $this->input->post('cskpd');  
        $ctahun     = $this->input->post('ctahun');    
        $calasan    = $this->input->post('calasan');
        $ctipe      = $this->input->post('ctipe');
        $cjenis     = $this->input->post('cjenis');
        $now        = date('Y-m-d H:i:s');
        $usernm     = $this->input->post('cuser');
        $usernm_pa  = $this->session->userdata('pcNama');
        

        $sql = "UPDATE sirup_header SET is_revisi=NULL,is_deleted=NULL
                WHERE idrup='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='2'";
        $asg = $this->db->query($sql);
        
        $sql = "delete from sirup_history_paket where idrup='$cid'";
        $asg = $this->db->query($sql);
        
        
        if($asg){
            $msg = array('pesan'=>'1');
            echo json_encode($msg); 
        }else{
            $msg = array('pesan'=>'2');
            echo json_encode($msg); 
        }
     }

	function editSwakelola_validasi(){
		date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');    
		$cfinall	= $this->input->post('cfinall');
        $now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		//if($cfinall==1){
		//	$caktif = 1;
		//}else{
		//	$caktif = 0;
		//}
		
		$sql = "UPDATE sirup_header SET is_final='$cfinall',last_update='$now'
		        WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='2'";
		$asg = $this->db->query($sql);
		
		$sql = "insert into sirup_history_otoritas (id,tahun,kd_skpd,create_time,username,ket,status) values
		       ('$cid','$ctahun','$cskpd','$now','$usernm','Validasi Swakelola Oleh PPK','1')";
		$asg = $this->db->query($sql);
		
		
		if($asg){
			$msg = array('pesan'=>'1');
			echo json_encode($msg);	
		}else{
			$msg = array('pesan'=>'2');
			echo json_encode($msg);	
		}
	 }	
	 
	function editSwakelola_validasi_batal(){
		date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');    
		$cfinall	= $this->input->post('cfinall');
        $now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		//if($cfinall==1){
		//	$caktif = 1;
		//}else{
		//	$caktif = 0;
		//}
		
		$sql = "UPDATE sirup_header SET is_final='$cfinall',last_update='$now'
		        WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='2'";
		$asg = $this->db->query($sql);
		
		$sql = "insert into sirup_history_otoritas (id,tahun,kd_skpd,create_time,username,ket,status) values
		       ('$cid','$ctahun','$cskpd','$now','$usernm','Batal Validasi Swakelola Oleh PPK','0')";
		$asg = $this->db->query($sql);
		
		
		if($asg){
			$msg = array('pesan'=>'1');
			echo json_encode($msg);	
		}else{
			$msg = array('pesan'=>'2');
			echo json_encode($msg);	
		}
	 }	 
    
	function editSwakelola_umumkan(){
		date_default_timezone_set('Asia/Jakarta'); 
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');    
		$cfinall	= $this->input->post('cfinall');
        $now 		= date('Y-m-d H:i:s');
        $usernm     = $this->input->post('cuser');
		$usernm_pa  = $this->session->userdata('pcNama');

		if($cfinall==1){
			$caktif = 1;
		}else{
			$caktif = 0;
		}
		
		$sql = "UPDATE sirup_header SET aktif='$caktif',umumkan='$cfinall',last_update='$now'
		        WHERE idrup='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun' and jenis_paket='2'";
		$asg = $this->db->query($sql);
		
		$sql = "insert into sirup_history_otoritas (id,tahun,kd_skpd,create_time,username,ket,status) values
		       ('$cid','$ctahun','$cskpd','$now','$usernm_pa','Umumkan Swakelola Oleh PA/KPA','2')";
		$asg = $this->db->query($sql);
		
		
		if($asg){
			$msg = array('pesan'=>'1');
			echo json_encode($msg);	
		}else{
			$msg = array('pesan'=>'2');
			echo json_encode($msg);	
		}
	 }
	
    function saveSwakelola(){
		date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');  
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');
        $cpaket    	= $this->input->post('cpaket');                                 
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');  
        $clok    	= $this->input->post('clok');  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= $this->input->post('cjns');  
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
        $cdet_lok 	= $this->input->post('cdet_lok');         
        $cidppk     = $this->input->post('cidppk');
        $cfinal     = $this->input->post('cfinall');
        $ctipeswa     = $this->input->post('tipeswa');
        $cklpdlainswa = $this->input->post('skpdswa');
        $csatkerlainswa = $cklpdlainswa;
        
		$now 		= date('Y-m-d H:i:s');
        $usernm     = $this->session->userdata('pcNama');
		
		$sql = "INSERT INTO sirup_header (id,kd_skpd,tahun,kd_program,kd_kegiatan,nm_kegiatan,nm_paket,det_lokasi,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,create_time,last_update,aktif,umumkan,id_swakelola,jenis_paket,username,is_final,id_ppk,tipe_swakelola,nama_klpd_lain,nama_satker_lain)
							VALUES('$cid','$cskpd','$ctahun','$ckdprog','$ckdgiat','$cnmgiat','$cpaket','$cdetlok','$cvol','$curai','$cspes','$ctkdn','$cuk','$cnuk','$cpra','$ctot','$cmtd','$cpilawl','$cpilakhir','$ckerawal','$ckerakhir','$now','$now','$caktif','$cumum','$cidswa','2','$usernm','$cfinal','$cidppk','$ctipeswa','$cklpdlainswa','$csatkerlainswa')";
		$asg = $this->db->query($sql);
		
		if ($asg){
				$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu,username) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				
                 $sql = "INSERT INTO sirup_detail_lokasi(id,prov,lokasi,nm_lokasi,det_lokasi,username,kd_skpd) $cdet_lok";
			     $asg = $this->db->query($sql);
			     if($asg){
				    $msg = array('pesan'=>'1');
				    echo json_encode($msg);	
			     }else{
				    $msg = array('pesan'=>'2');
				    echo json_encode($msg);	
			     }
                	
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
		date_default_timezone_set('Asia/Jakarta');
        $cid    	= $this->input->post('cid');          
        $cskpd    	= $this->input->post('cskpd');  
        $ctahun    	= $this->input->post('ctahun');          
        $ckdgiat    = $this->input->post('ckdgiat');  
        $cnmgiat    = $this->input->post('cnmgiat');  
        $ckdprog    = $this->input->post('ckdprog');
        $cpaket    	= $this->input->post('cpaket');                            
        $clok    	= $this->input->post('clok');  
        $cdetlok    = $this->input->post('cdetlok');  
        $cjns    	= $this->input->post('cjns');  
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
		$cdet_lok 	= $this->input->post('cdet_lok');         
        $cidppk     = $this->input->post('cidppk');
        $cfinal     = $this->input->post('cfinall');
        $ctipeswa     = $this->input->post('tipeswa');
        $cklpdlainswa = $this->input->post('skpdswa');
        $csatkerlainswa = $cklpdlainswa;
        
        
		$sql1 = "DELETE FROM sirup_detail WHERE id='$cid' AND kd_skpd='$cskpd' and username='$usernm'";
		$sql2 = "DELETE FROM sirup_detail_lokasi WHERE id='$cid' AND kd_skpd='$cskpd' and username='$usernm'";
		$asg1 = $this->db->query($sql1);
		$asg2 = $this->db->query($sql2);
		
		$sql = "UPDATE sirup_header SET kd_program='$ckdprog',kd_kegiatan='$ckdgiat',nm_kegiatan='$cnmgiat',nm_paket='$cpaket',volume='$cvol',uraian='$curai',spesifikasi='$cspes',tkdn='$ctkdn',uk='$cuk',nuk='$cnuk',pradipa='$cpra',total='$ctot',mtd_pengadaan='$cmtd',pilih_awal='$cpilawl',pilih_akhir='$cpilakhir',kerja_mulai='$ckerawal',kerja_akhir='$ckerakhir',last_update='$now',aktif='$caktif',umumkan='$cumum',jenis_paket='2',username='$usernm',id_ppk='$cidppk',is_final='$cfinal',tipe_swakelola='$ctipeswa',nama_klpd_lain='$cklpdlainswa',nama_satker_lain='$cklpdlainswa'
		          WHERE id='$cid' and kd_skpd='$cskpd' and username='$usernm' and tahun='$ctahun'";
		$asg = $this->db->query($sql);
		
		
		if ($asg){
				$sql = "INSERT INTO sirup_detail(id,tahun,klpd,kd_skpd,kd_paket,isi_paket,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_sd,kd_ad,kd_ads,mak,vol,pagu,username) $cdet";
			$asg = $this->db->query($sql);
			if($asg){
				
                 $sql = "INSERT INTO sirup_detail_lokasi(id,prov,lokasi,nm_lokasi,det_lokasi,username,kd_skpd) $cdet_lok";
			     $asg = $this->db->query($sql);
			     if($asg){
				    $msg = array('pesan'=>'1');
				    echo json_encode($msg);	
			     }else{
				    $msg = array('pesan'=>'2');
				    echo json_encode($msg);	
			     }
                	
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
	 
	 function load_cek_list_sirup(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria_skpd = $this->input->post('kriteria_skpd');
        $kriteria_user = $this->input->post('kriteria_user');

        $sql = "select count(id) as tot 
				from sirup_header where kd_skpd='$kriteria_skpd' and username='$kriteria_user'
                " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "select a.idrup,a.id,a.nm_kegiatan,a.nm_paket,
				(select top 1 isi_paket from sirup_detail where username=a.username and kd_skpd=a.kd_skpd and id=a.id) isi_paket,
				create_time,username,
				case when a.is_final=1 then 'SUDAH' else 'BELUM' end as final,
                case when a.is_revisi=1 or a.is_revisi=3 then 'YA' else '-' end as revisi,
				case when a.umumkan=1 then 'SUDAH' else 'BELUM' end as umumkan, 
				case when a.jenis_paket=1 then 'Penyedia' else 'Swakelola' end as paket,
				a.jenis_paket		
				from sirup_header a where a.kd_skpd='$kriteria_skpd' and a.username='$kriteria_user'
				order by cast(a.idrup as int)
                ";
				
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 	
			if($resulte['paket']=='Penyedia'){
				$nmpaket = $resulte['nm_paket']." (".$resulte['isi_paket'].")";
			}else{
				$nmpaket = $resulte['nm_paket'];
			}
			
            $row[] = array(
						'idrup' => $resulte['idrup'],
                        'id' => $resulte['id'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],        
                        'nm_paket' => $nmpaket,
						'create_time' => $resulte['create_time'],
						'username' => $resulte['username'],
						'final' => $resulte['final'],
                        'revisi' => $resulte['revisi'],
						'umumkan' => $resulte['umumkan'],
                        'paket' => $resulte['paket'],
						'jenis_paket' => $resulte['jenis_paket']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);     
    }
	
	function load_cek_list_sirup_cari(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria_skpd = $this->input->post('kriteria_skpd');
		$kriteria_x = $this->input->post('kriteria_x');
		$kriteria_user = $this->input->post('kriteria_user');
        
        $sql = "select count(id) as tot 
				from sirup_header where kd_skpd='$kriteria_skpd' and username='$kriteria_user'
                " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "select a.idrup,a.id,a.nm_kegiatan,a.nm_paket,
				(select top 1 isi_paket from sirup_detail where username=a.username and kd_skpd=a.kd_skpd and id=a.id) isi_paket,
				create_time,username,
				case when a.is_final=1 then 'SUDAH' else 'BELUM' end as final,
                case when a.is_revisi=1 or a.is_revisi=3 then 'YA' else '-' end as revisi,
				case when a.umumkan=1 then 'SUDAH' else 'BELUM' end as umumkan, 
				case when a.jenis_paket=1 then 'Penyedia' else 'Swakelola' end as paket 
				from sirup_header a where a.kd_skpd='$kriteria_skpd' and a.username='$kriteria_user' and a.kd_kegiatan like '%$kriteria_x%'
				order by cast(a.idrup as int)
                ";
                
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 	
			if($resulte['paket']=='Penyedia'){
				$nmpaket = $resulte['nm_paket']." (".$resulte['isi_paket'].")";
			}else{
				$nmpaket = $resulte['nm_paket'];
			}
			
            $row[] = array(
						'idrup' => $resulte['idrup'],
                        'id' => $resulte['id'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],        
                        'nm_paket' => $nmpaket,
						'create_time' => $resulte['create_time'],
						'username' => $resulte['username'],
                        'revisi' => $resulte['revisi'],
						'final' => $resulte['final'],
						'umumkan' => $resulte['umumkan'],
                        'paket' => $resulte['paket']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);     
    }
	 
	 function hapusPenyedia() {    	
		$no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');        
        $user  = $this->session->userdata('pcNama');        
        
        $query = $this->db->query("delete from sirup_detail where id='$no' and kd_skpd='$skpd' and username='$user'");
        $query = $this->db->query("delete from sirup_header where id='$no' and kd_skpd='$skpd' and username='$user'");
        $query = $this->db->query("delete from sirup_detail_lokasi where id='$no' and kd_skpd='$skpd' and username='$user'");
        
    }

   function cetak_listpenyedia_tes($no_rup=''){
        $kd_skpd = $this->session->userdata('kdskpd');        
        $pcUser = $this->session->userdata('pcNama');        
        
        //$this->tanggal_format_indonesia($tgl);       
        $xqueryppk = $this->db->query("select top 1 username from sirup_header where idrup='$no_rup'  and jenis_paket='1'")->row();
        $zuserppk = $xqueryppk->username;
         
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PAKET PENYEDIA</b></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Perangkat Daerah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : ".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Pejabat Pembuat Komitmen :  ".strtoupper($this->tukd_model->get_nama($zuserppk,'nama','ms_ttd','username'))."</b></td>
            </tr>
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "SELECT b.*,
                    (select top 1 a.nm_program from m_prog a where a.kd_program=left(kd_kegiatan,18)) as nm_program,
                    (select top 1 a.isi_paket from sirup_detail a where a.kd_skpd='$kd_skpd' and a.id=b.id and a.username=b.username) as nm_paket_gab
                    from sirup_header b                   
                   where b.kd_skpd='$kd_skpd' and b.jenis_paket='1' and idrup='$no_rup'";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;  
                        $cidrup    = $row->idrup;  
                        $cid       = $row->id;         
                        $ctahun    = $row->tahun; 
                        $cuser    = $row->username;                        
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnmprog    = $row->nm_program; 
                        $clast_up    = $row->last_update; 
                        $cnm_paket = $row->nm_paket;
                        $cnm_paket_gab = $row->nm_paket_gab;
                        $cvolume   = $row->volume;
                        $total     = number_format($row->total,2);                           
                        $curaian   = $row->uraian;
                        $cspesif   = $row->spesifikasi;
                        $cmtdpeng  = $row->mtd_pengadaan;
                        $sqlmp="select nm_mp from sirup_metode_pengadaan where kd_mp in ('$cmtdpeng')";
                        $mp3=$this->db->query($sqlmp);$mp2=$mp3->row();
                        $metodepeng=$mp2->nm_mp;

                        $crevisi   = $row->is_revisi;
                        if($crevisi==2){
                            $sqlhistory="select isnull(idrup,0) idrup,isnull(tipe,'-') tipe,isnull(create_time,'') create_time from sirup_history_paket where idrup_to = '$cidrup' ";                       
                            $his3=$this->db->query($sqlhistory);
                            $his2=$his3->row(); 
                            $ctipe_=$his2->idrup; $ctipes_=$his2->tipe; $ctgl_=$his2->create_time;
                        }else{
                            $ctipe_=""; $ctipes_=""; $ctgl_="";
                        }    
                        
                        $cpawal    =$row->pilih_awal;
                        $cpawal2   =$this->ambil_bulan($cpawal);
                        $cpawal3   =$this->pilih_bulan($cpawal2);
                        
                        $cpakhir   =$row->pilih_akhir;
                        $cpakhir2   =$this->ambil_bulan($cpakhir);
                        $cpakhir3   =$this->pilih_bulan($cpakhir2);
                        
                        $cpbutuh   =$row->tanggal_kebutuhan;
                        $cpbutuh2   =$this->ambil_bulan($cpbutuh);
                        $cpbutuh3   =$this->pilih_bulan($cpbutuh2);
                        
                        $cpbutuh_2   =$row->tanggal_kebutuhan_akhir;
                        $cpbutuh2_2   =$this->ambil_bulan($cpbutuh_2);
                        $cpbutuh3_2   =$this->pilih_bulan($cpbutuh2_2);
                        
                        $ckawal    =$row->kerja_mulai;
                        $ckawal2   =$this->ambil_bulan($ckawal);
                        $ckawal3   =$this->pilih_bulan($ckawal2);
                        
                        $ckakhir   =$row->kerja_akhir;
                        $ckakhir2   =$this->ambil_bulan($ckakhir);
                        $ckakhir3   =$this->pilih_bulan($ckakhir2);
                        $crenja   =$row->no_renja;
                        $cizin   =$row->izin_tahun_jamak;
                        $ctkdn   = $row->tkdn;
                        $cuk   = $row->uk;
                        $cpradipa   = $row->pradipa;
                        
                        $cumumkan  = $row->umumkan; 
                        $cfinal    = $row->is_final; 
                        
                        if($ctkdn==1){
                            $ctkdn="Ya";
                        }else{
                            $ctkdn="Tidak";
                        }
                        
                        if($cuk==1){
                            $cuk="Ya";
                        }else{
                            $cuk="Tidak";
                        }
                        
                        if($cpradipa==1){
                            $cpradipa="Ya";
                        }else{
                            $cpradipa="Tidak";
                        }
                        
                        if($cumumkan==1 && $cfinal==1){
                            $stts_paket = "Sudah diumumkan";
                        }else if($cumumkan==0 && $cfinal==1){
                            $stts_paket = "Sudah difinalisasi dan belum diumumkan";
                        }else if($cumumkan==0 && $cfinal==0){
                            $stts_paket = "Belum difinalisasi dan belum diumumkan";
                        }

                        if($crevisi==1){
                            if($ctipes_=="PEMBATALAN"){
                                $stts_paket = "Paket Dibatalkan";    
                            }else{
                                $stts_paket = "Paket Direvisi Satu Ke Satu";    
                            }
                            
                        }
                                                  
            $cRet .="
                    <tr>
                    <td colspan=\"5\" valign=\"top\" width=\"95%\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black;\">&nbsp;&nbsp;<b>&#8711; &nbsp;".$cnm_paket."</b></td>
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>ID RUP</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cidrup."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Program</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmprog."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Kegiatan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmkeg."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Nama Paket</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnm_paket." (".$cnm_paket_gab.")</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">KLPD</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pemerintah Kota Pontianak</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>       
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Satuan Kerja</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tahun Anggaran</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    </tr>                    
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Lokasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    ";

                    $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"95%\">
         
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-bottom:1px solid black;\">No.</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border-bottom:1px solid black;\">Provinsi</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border-bottom:1px solid black;\">Kabupaten/Kota</td>
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border-bottom:1px solid black;\">Detail Lokasi</td>
                    </tr>"; 
                    
           $sqld = "SELECT a.* from sirup_detail_lokasi a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.username='$cuser'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
            {
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">1</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->prov."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->nm_lokasi."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->det_lokasi."
                    </td>
                                       
                    ";
            }        
            $cRet .="</table>
                    </td>
                    </tr>                                                            
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Volume Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cvolume."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Uraian Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$curaian."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Spesifikasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cspesif."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Produk Dalam Negeri</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctkdn."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Usaha Kecil</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cuk."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pra DIPA / DPA</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cpradipa."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    ";   
           $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"80%\">"; 
            
           $sqld = "SELECT a.* from sirup_detail a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.kd_kegiatan='$ckdkeg' and a.username='$cuser'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
                    {
           $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Jenis Pengadaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->nmjns_pengadaan."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->kd_sd."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">KLPD</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">Pemerintah Kota Pontianak</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">MAK</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;borde:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->mak."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Isi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->isi_paket."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-bottom:1px solid #B6B5B5;\">".number_format($rowd->pagu,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>  
                                                          
                    ";              
                    }
           $tot_pagu=$tot_pagu+$row->total;        
           $cRet .="</table>
                    </td>
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Total Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($row->total,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Metode Pemilihan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$metodepeng."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Alasan Aturan PBJ</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Waktu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pemanfaatan Barang/Jasa</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$cpbutuh3." ".$ctahun." - ".$cpbutuh3_2." ".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pelaksanaan Kontrak</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$cpawal3." ".$ctahun." - ".$cpakhir3." ".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pemilihan Penyedia</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$ckawal3." ".$ctahun." - ".$ckakhir3." ".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
                        </table >
                    </td>   
                    </tr>
                     
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Revisi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Sebelum Revisi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">$ctipe_</td>                                        
                               
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Tanggal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">$ctgl_</td>                                        
                                
                    </tr> 
                        </table>
                    </td>   
                    </tr> 
                        
                                       
                    
                    ";
                
             }
                         
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tanggal Perbaharuan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$clast_up</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr>  
                <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Status Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$stts_paket</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr> 
                                                                         
            </table>";  
            
            /*
            
             $cRet .="
             <table>
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                                                                         
             </table>"; */
            
        $data['prev']= $cRet;    
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    } 

   function cetak_listpenyedia($no_rup=''){
		$kd_skpd = $this->session->userdata('kdskpd');        
        $pcUser = $this->session->userdata('pcNama');        
        
        //$this->tanggal_format_indonesia($tgl);       
		$xqueryppk = $this->db->query("select top 1 username from sirup_header where idrup='$no_rup'  and jenis_paket='1'")->row();
		$zuserppk = $xqueryppk->username;
         
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PAKET PENYEDIA</b></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Perangkat Daerah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : ".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Pejabat Pembuat Komitmen :  ".strtoupper($this->tukd_model->get_nama($zuserppk,'nama','ms_ttd','username'))."</b></td>
            </tr>
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "SELECT b.*,
                    (select top 1 a.nm_program from ms_program a where a.kd_program=left(kd_kegiatan,7)) as nm_program,
                    (select top 1 a.isi_paket from sirup_detail a where a.kd_skpd='$kd_skpd' and a.id=b.id and a.username=b.username) as nm_paket_gab
                    from sirup_header b                   
                   where b.kd_skpd='$kd_skpd' and b.jenis_paket='1' and idrup='$no_rup'";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;  
                        $cidrup    = $row->idrup;  
						$cid       = $row->id;         
                        $ctahun    = $row->tahun; 
                        $cuser    = $row->username;                        
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnmprog    = $row->nm_program; 
                        $clast_up    = $row->last_update; 
                        $cnm_paket = $row->nm_paket;
                        $cnm_paket_gab = $row->nm_paket_gab;
                        $cvolume   = $row->volume;
                        $total     = number_format($row->total,2);                           
                        $curaian   = $row->uraian;
		                $cspesif   = $row->spesifikasi;
                        $cmtdpeng  = $row->mtd_pengadaan;
                        $sqlmp="select nm_mp from sirup_metode_pengadaan where kd_mp in ('$cmtdpeng')";
	                    $mp3=$this->db->query($sqlmp);$mp2=$mp3->row();		                
                        $metodepeng=$mp2->nm_mp;

                        $crevisi   = $row->is_revisi;
                        if($crevisi==4){
                            $sqlhistory="select isnull(idrup,0) idrup,isnull(tipe,'-') tipe,isnull(create_time,'') create_time from sirup_history_paket where idrup_to = '$cidrup' ";                       
                            $his3=$this->db->query($sqlhistory);
                            $his2=$his3->row(); 
                            $ctipe_=$his2->idrup; $ctipes_=$his2->tipe; $ctgl_=$his2->create_time;
                        }else{
                            $ctipe_=""; $ctipes_=""; $ctgl_="";
                        } 

                        $cpawal    =$row->pilih_awal;
                        $cpawal2   =$this->ambil_bulan($cpawal);
                        $cpawal3   =$this->pilih_bulan($cpawal2);
                        $cpawal3_t  =substr($cpawal,0,4);
                        
                        $cpakhir   =$row->pilih_akhir;
                        $cpakhir2   =$this->ambil_bulan($cpakhir);
                        $cpakhir3   =$this->pilih_bulan($cpakhir2);
                        $cpakhir3_t  =substr($cpakhir,0,4);
                        
						$cpbutuh   =$row->tanggal_kebutuhan;
                        $cpbutuh2   =$this->ambil_bulan($cpbutuh);
                        $cpbutuh3   =$this->pilih_bulan($cpbutuh2);
                        $cpbutuh3_t  =substr($cpbutuh,0,4);
                        
						$cpbutuh_2   =$row->tanggal_kebutuhan_akhir;
                        $cpbutuh2_2   =$this->ambil_bulan($cpbutuh_2);
                        $cpbutuh3_2   =$this->pilih_bulan($cpbutuh2_2);
						$cpbutuh3_2_t  =substr($cpbutuh_2,0,4);

                        $ckawal    =$row->kerja_mulai;
                        $ckawal2   =$this->ambil_bulan($ckawal);
                        $ckawal3   =$this->pilih_bulan($ckawal2);
                        $ckawal3_t  =substr($ckawal,0,4);

                        $ckakhir   =$row->kerja_akhir;
                        $ckakhir2   =$this->ambil_bulan($ckakhir);
                        $ckakhir3   =$this->pilih_bulan($ckakhir2);
                        $ckakhir3_t  =substr($ckakhir,0,4);

                        $crenja   =$row->no_renja;
                        $cizin   =$row->izin_tahun_jamak;
						$ctkdn   = $row->tkdn;
						$cuk   = $row->uk;
						$cpradipa   = $row->pradipa;
						
                        $cumumkan  = $row->umumkan; 
                        $cfinal    = $row->is_final; 
                        
						if($ctkdn==1){
							$ctkdn="Ya";
						}else{
							$ctkdn="Tidak";
						}
						
						if($cuk==1){
							$cuk="Ya";
						}else{
							$cuk="Tidak";
						}
						
						if($cpradipa==1){
							$cpradipa="Ya";
						}else{
							$cpradipa="Tidak";
						}
						
                        if($cumumkan==1 && $cfinal==1){
                            $stts_paket = "Sudah diumumkan";
                        }else if($cumumkan==0 && $cfinal==1){
                            $stts_paket = "Sudah difinalisasi dan belum diumumkan";
                        }else if($cumumkan==0 && $cfinal==0){
                            $stts_paket = "Belum difinalisasi dan belum diumumkan";
                        }

                        
                            if($crevisi==1){
                                $stts_paket = "Paket Direvisi Satu Ke Satu";    
                            }else if($crevisi==2){
                                $stts_paket = "Paket Direvisi Satu Ke Banyak";    
                            }else if($crevisi==3){
                                $stts_paket = "Paket Dibatalkan";    
                            }
                                                  
            $cRet .="
                    <tr>
                    <td colspan=\"5\" valign=\"top\" width=\"95%\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black;\">&nbsp;&nbsp;<b>&#8711; &nbsp;".$cnm_paket."</b></td>
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>ID RUP</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cidrup."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Program</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmprog."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Kegiatan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmkeg."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Nama Paket</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnm_paket." (".$cnm_paket_gab.")</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">KLPD</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pemerintah Kota Pontianak</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>		
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Satuan Kerja</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tahun Anggaran</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					</tr>                    
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Lokasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    ";

                    $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"95%\">
         
                    <tr>
					<td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-bottom:1px solid black;\">No.</td>
					<td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border-bottom:1px solid black;\">Provinsi</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border-bottom:1px solid black;\">Kabupaten/Kota</td>
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border-bottom:1px solid black;\">Detail Lokasi</td>
                    </tr>"; 
					
           $sqld = "SELECT a.* from sirup_detail_lokasi a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.username='$cuser'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
            {
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">1</td>
					<td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border:none;\">
					".$rowd->prov."
					</td>
					
					<td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">
					".$rowd->nm_lokasi."
					</td>
                    
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border:none;\">
					".$rowd->det_lokasi."
					</td>
                                       
                    ";
            }        
            $cRet .="</table>
                    </td>
                    </tr>                                                            
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Volume Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cvolume."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Uraian Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$curaian."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Spesifikasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cspesif."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Produk Dalam Negeri</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctkdn."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Usaha Kecil</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cuk."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pra DIPA / DPA</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cpradipa."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    ";   
           $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"80%\">"; 
            
           $sqld = "SELECT a.* from sirup_detail a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.kd_kegiatan='$ckdkeg' and a.username='$cuser'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
                    {
           $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Jenis Pengadaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->nmjns_pengadaan."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->kd_sd."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">KLPD</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">Pemerintah Kota Pontianak</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">MAK</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;borde:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->mak."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Isi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->isi_paket."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-bottom:1px solid #B6B5B5;\">".number_format($rowd->pagu,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>  
                                                          
                    ";              
                    }
           $tot_pagu=$tot_pagu+$row->total;        
           $cRet .="</table>
                    </td>
                    </tr> 
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Total Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($row->total,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
					<tr>
					<td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Metode Pemilihan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$metodepeng."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					<tr>
					<td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Alasan Aturan PBJ</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
					<tr>
					<td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Waktu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>					
                    <tr>
					<td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
						<table width=\"100%\" >
						
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pemanfaatan Barang/Jasa</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$cpbutuh3." ".$cpbutuh3_t." - ".$cpbutuh3_2." ".$cpbutuh3_2_t."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pelaksanaan Kontrak</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$ckawal3." ".$ckawal3_t." - ".$ckakhir3." ".$ckakhir3_t."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pemilihan Penyedia</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$cpawal3." ".$cpawal3_t." - ".$cpakhir3." ".$cpakhir3_t."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
						</table >
					</td>	
                    </tr>
					 
					<tr>
					<td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Revisi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>					
                    <tr>
					<td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
						<table width=\"100%\" >
						
					<tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Sebelum Revisi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">$ctipe_</td>                                        
                               
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Tanggal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">$ctgl_</td>                                        
                                
                    </tr> 
                    	</table>
					</td>	
                    </tr> 
                    	
                                       
                    
                    ";
                
             }
                         
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tanggal Perbaharuan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$clast_up</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr>  
                <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Status Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$stts_paket</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr> 
                                                                         
            </table>";  
			
			/*
			
			 $cRet .="
             <table>
				<tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                                                                         
             </table>";	*/
            
        $data['prev']= $cRet;    
        //echo $cRet;
        $this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }



    function cetak_listpenyedia_rup($no_rup=''){
        //$kd_skpd = $this->session->userdata('kdskpd');        
        //$pcUser = $this->session->userdata('pcNama');        
        
        //$this->tanggal_format_indonesia($tgl);       
        $xqueryppk = $this->db->query("select top 1 username,kd_skpd from sirup_header where idrup='$no_rup'  and jenis_paket='1'")->row();
        $zuserppk = $xqueryppk->username;
        $zuserskpd = $xqueryppk->kd_skpd;
         
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PAKET PENYEDIA</b></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Perangkat Daerah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : ".strtoupper($this->tukd_model->get_nama($zuserskpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Pejabat Pembuat Komitmen :  ".strtoupper($this->tukd_model->get_nama($zuserppk,'nama','ms_ttd','username'))."</b></td>
            </tr>
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "SELECT b.*,
                    (select top 1 a.nm_program from m_prog a where a.kd_program=left(kd_kegiatan,18)) as nm_program,
                    (select top 1 a.isi_paket from sirup_detail a where a.kd_skpd='$zuserskpd' and a.id=b.id and a.username=b.username) as nm_paket_gab
                    from sirup_header b                   
                   where b.kd_skpd='$zuserskpd' and b.jenis_paket='1' and idrup='$no_rup'";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;  
                        $cidrup    = $row->idrup;  
                        $cid       = $row->id;         
                        $ctahun    = $row->tahun; 
                        $cuser    = $row->username;                        
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnmprog    = $row->nm_program; 
                        $clast_up    = $row->last_update; 
                        $cnm_paket = $row->nm_paket;
                        $cnm_paket_gab = $row->nm_paket_gab;
                        $cvolume   = $row->volume;
                        $total     = number_format($row->total,2);                           
                        $curaian   = $row->uraian;
                        $cspesif   = $row->spesifikasi;
                        $cmtdpeng  = $row->mtd_pengadaan;
                        $sqlmp="select nm_mp from sirup_metode_pengadaan where kd_mp in ('$cmtdpeng')";
                        $mp3=$this->db->query($sqlmp);$mp2=$mp3->row();                     
                        $metodepeng=$mp2->nm_mp;

                        $crevisi   = $row->is_revisi;
                        if($crevisi==4){
                            $sqlhistory="select isnull(idrup,0) idrup,isnull(tipe,'-') tipe,isnull(create_time,'') create_time from sirup_history_paket where idrup_to = '$cidrup' ";                       
                            $his3=$this->db->query($sqlhistory);
                            $his2=$his3->row(); 
                            $ctipe_=$his2->idrup; $ctipes_=$his2->tipe; $ctgl_=$his2->create_time;
                        }else{
                            $ctipe_=""; $ctipes_=""; $ctgl_="";
                        } 

                        $cpawal    =$row->pilih_awal;
                        $cpawal2   =$this->ambil_bulan($cpawal);
                        $cpawal3   =$this->pilih_bulan($cpawal2);
                        $cpawal3_t  =substr($cpawal,0,4);
                        
                        $cpakhir   =$row->pilih_akhir;
                        $cpakhir2   =$this->ambil_bulan($cpakhir);
                        $cpakhir3   =$this->pilih_bulan($cpakhir2);
                        $cpakhir3_t  =substr($cpakhir,0,4);
                        
                        $cpbutuh   =$row->tanggal_kebutuhan;
                        $cpbutuh2   =$this->ambil_bulan($cpbutuh);
                        $cpbutuh3   =$this->pilih_bulan($cpbutuh2);
                        $cpbutuh3_t  =substr($cpbutuh,0,4);
                        
                        $cpbutuh_2   =$row->tanggal_kebutuhan_akhir;
                        $cpbutuh2_2   =$this->ambil_bulan($cpbutuh_2);
                        $cpbutuh3_2   =$this->pilih_bulan($cpbutuh2_2);
                        $cpbutuh3_2_t  =substr($cpbutuh_2,0,4);

                        $ckawal    =$row->kerja_mulai;
                        $ckawal2   =$this->ambil_bulan($ckawal);
                        $ckawal3   =$this->pilih_bulan($ckawal2);
                        $ckawal3_t  =substr($ckawal,0,4);

                        $ckakhir   =$row->kerja_akhir;
                        $ckakhir2   =$this->ambil_bulan($ckakhir);
                        $ckakhir3   =$this->pilih_bulan($ckakhir2);
                        $ckakhir3_t  =substr($ckakhir,0,4);

                        $crenja   =$row->no_renja;
                        $cizin   =$row->izin_tahun_jamak;
                        $ctkdn   = $row->tkdn;
                        $cuk   = $row->uk;
                        $cpradipa   = $row->pradipa;
                        
                        $cumumkan  = $row->umumkan; 
                        $cfinal    = $row->is_final; 
                        
                        if($ctkdn==1){
                            $ctkdn="Ya";
                        }else{
                            $ctkdn="Tidak";
                        }
                        
                        if($cuk==1){
                            $cuk="Ya";
                        }else{
                            $cuk="Tidak";
                        }
                        
                        if($cpradipa==1){
                            $cpradipa="Ya";
                        }else{
                            $cpradipa="Tidak";
                        }
                        
                        if($cumumkan==1 && $cfinal==1){
                            $stts_paket = "Sudah diumumkan";
                        }else if($cumumkan==0 && $cfinal==1){
                            $stts_paket = "Sudah difinalisasi dan belum diumumkan";
                        }else if($cumumkan==0 && $cfinal==0){
                            $stts_paket = "Belum difinalisasi dan belum diumumkan";
                        }

                        
                            if($crevisi==1){
                                $stts_paket = "Paket Direvisi Satu Ke Satu";    
                            }else if($crevisi==2){
                                $stts_paket = "Paket Direvisi Satu Ke Banyak";    
                            }else if($crevisi==3){
                                $stts_paket = "Paket Dibatalkan";    
                            }
                                                  
            $cRet .="
                    <tr>
                    <td colspan=\"5\" valign=\"top\" width=\"95%\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black;\">&nbsp;&nbsp;<b>&#8711; &nbsp;".$cnm_paket."</b></td>
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>ID RUP</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cidrup."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Program</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmprog."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Kegiatan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmkeg."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Nama Paket</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"60%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnm_paket." (".$cnm_paket_gab.")</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">KLPD</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pemerintah Kota Pontianak</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>       
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Satuan Kerja</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tahun Anggaran</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    </tr>                    
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Lokasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    ";

                    $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"95%\">
         
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-bottom:1px solid black;\">No.</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border-bottom:1px solid black;\">Provinsi</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border-bottom:1px solid black;\">Kabupaten/Kota</td>
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border-bottom:1px solid black;\">Detail Lokasi</td>
                    </tr>"; 
                    
           $sqld = "SELECT a.* from sirup_detail_lokasi a where a.id='$cid' and a.kd_skpd='$zuserskpd' and a.username='$cuser'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
            {
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">1</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->prov."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->nm_lokasi."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->det_lokasi."
                    </td>
                                       
                    ";
            }        
            $cRet .="</table>
                    </td>
                    </tr>                                                            
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Volume Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cvolume."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Uraian Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$curaian."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Spesifikasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cspesif."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Produk Dalam Negeri</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctkdn."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Usaha Kecil</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cuk."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Pra DIPA / DPA</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cpradipa."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"10%\" style=\"font-size:11px;border-left:none;border-right:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    ";   
           $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"80%\">"; 
            
           $sqld = "SELECT a.* from sirup_detail a where a.id='$cid' and a.kd_skpd='$zuserskpd' and a.kd_kegiatan='$ckdkeg' and a.username='$cuser'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
                    {
           $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Jenis Pengadaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->nmjns_pengadaan."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->kd_sd."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">KLPD</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">Pemerintah Kota Pontianak</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">MAK</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;borde:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->mak."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Isi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border:none;\">".$rowd->isi_paket."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"9%\" style=\"font-size:11px;border:none;\">Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-bottom:1px solid #B6B5B5;\">".number_format($rowd->pagu,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>  
                                                          
                    ";              
                    }
           $tot_pagu=$tot_pagu+$row->total;        
           $cRet .="</table>
                    </td>
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Total Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($row->total,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Metode Pemilihan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$metodepeng."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Alasan Aturan PBJ</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Waktu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pemanfaatan Barang/Jasa</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$cpbutuh3." ".$cpbutuh3_t." - ".$cpbutuh3_2." ".$cpbutuh3_2_t."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pelaksanaan Kontrak</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$ckawal3." ".$ckawal3_t." - ".$ckakhir3." ".$ckakhir3_t."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pemilihan Penyedia</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$cpawal3." ".$cpawal3_t." - ".$cpakhir3." ".$cpakhir3_t."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
                        </table >
                    </td>   
                    </tr>
                     
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Revisi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Sebelum Revisi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">$ctipe_</td>                                        
                               
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Tanggal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">$ctgl_</td>                                        
                                
                    </tr> 
                        </table>
                    </td>   
                    </tr> 
                        
                                       
                    
                    ";
                
             }
                         
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tanggal Perbaharuan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$clast_up</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr>  
                <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"13%\" style=\"font-size:11px;border-left:none;border-right:none;\">Status Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$stts_paket</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr> 
                                                                         
            </table>";  
            
            /*
            
             $cRet .="
             <table>
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"3\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                                                                         
             </table>"; */
            
        $data['prev']= $cRet;    
        //echo $cRet;
        $this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }
    
    function cetak_listswakelola($idrup=''){
		$kd_skpd = $this->session->userdata('kdskpd');        
        $pcUser = $this->session->userdata('pcNama');        
        
        //$this->tanggal_format_indonesia($tgl);       
        $xqueryppk = $this->db->query("select top 1 username from sirup_header where idrup='$idrup' and jenis_paket='2'")->row();
		$zuserppk = $xqueryppk->username;
		
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PAKET SWAKELOLA</b></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
           <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Perangkat Daerah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : ".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Pejabat Pembuat Komitmen :  ".strtoupper($this->tukd_model->get_nama($zuserppk,'nama','ms_ttd','username'))."</b></td>
            </tr>
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "SELECT b.*,
                    (select top 1 a.nm_program from ms_program a where a.kd_program=left(b.kd_kegiatan,7)) as nm_program,
                    (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$kd_skpd') as nm_skpd
                    from sirup_header b                   
                   where b.kd_skpd='$kd_skpd' and b.jenis_paket='2' and idrup='$idrup'";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;  
						$cidrup    = $row->idrup;	
                        $cid       = $row->id;         
                        $ctahun    = $row->tahun;
                        $cnskpd    = $row->nm_skpd;                         
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmprog   = $row->nm_program;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnm_paket = $row->nm_paket;
                        $cvolume   = $row->volume;
                        $total     = number_format($row->total,2);                           
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
                        $crenja   =$row->no_renja;
                        $cizin   =$row->izin_tahun_jamak;
						$tipe_swakelola =$row->tipe_swakelola;
                        $cusername  = $row->username;
                        $clast_up = $row->last_update;    
                        $sqlsw="SELECT top 1 a.ket_swakelola from sirup_swakelola a where a.tipe_swakelola='$tipe_swakelola'";
                        $sw3=$this->db->query($sqlsw);
                        $sw2=$sw3->row();
                        $cket_swakelola=$sw2->ket_swakelola;

                        if($tipe_swakelola==1){
                           $cpswakelola=$cnskpd;  
                        }else{
                           $cpswakelola=$row->nama_satker_lain;
                        }

                        $cumumkan  = $row->umumkan; 
                        $cfinal    = $row->is_final; 

                        if($cumumkan==1 && $cfinal==1){
                            $stts_paket = "Sudah diumumkan";
                        }else if($cumumkan==0 && $cfinal==1){
                            $stts_paket = "Sudah difinalisasi dan belum diumumkan";
                        }else if($cumumkan==0 && $cfinal==0){
                            $stts_paket = "Belum difinalisasi dan belum diumumkan";
                        }    
                                                  
            $cRet .="
                    <tr>
                    <td colspan=\"5\" valign=\"top\" width=\"95%\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black;\">&nbsp;&nbsp;<b>&#8711; &nbsp;".$cnm_paket."</b></td>
                    </tr>
					
					
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>ID RUP</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cidrup."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Program Kegiatan</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmprog."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>   
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Nama Paket</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnm_paket."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                                        
                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tahun Anggaran</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Lokasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    ";

                    $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"80%\">
                       <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-bottom:1px solid black;\">No.</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border-bottom:1px solid black;\">Provinsi</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border-bottom:1px solid black;\">Kabupaten/Kota</td>
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border-bottom:1px solid black;\">Detail Lokasi</td>
                    </tr>";  
                    
                    
                    
           $sqld = "SELECT a.* from sirup_detail_lokasi a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.username='$cusername'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
            {
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">1</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->prov."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->nm_lokasi."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->det_lokasi."
                    </td>
                                       
                    ";
            }             
			
			$cRet .="</table>
                    </td>
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Volume Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cvolume."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Uraian Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$curaian."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tipe Swakelola</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cket_swakelola."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Penyelenggara Swakelola</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cpswakelola."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Rincian</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    ";   
           $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"80%\">"; 
            
           $sqld = "SELECT a.* from sirup_detail a where a.id='$cid' and a.kd_skpd='$kd_skpd' and a.kd_kegiatan='$ckdkeg' and a.username='$cusername'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
                    {
           $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".$rowd->kd_sd."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Asal Dana Satker</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".strtoupper($this->tukd_model->get_nama($rowd->kd_ads,'nm_skpd','ms_skpd','kd_skpd'))."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">MAK</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;borde:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".$rowd->mak."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Isi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".$rowd->isi_paket."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-bottom:1px solid #B6B5B5;\">".number_format($rowd->pagu,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>  
                                                          
                    ";              
                    }
                    
           $cRet .="</table>
                    </td>
                    </tr>
					<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Total Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($row->total,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Waktu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pelaksanaan Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$ckawal3." ".$ctahun." - ".$ckakhir3." ".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
                        </table >
                    </td>   
                    </tr>
                     
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Revisi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Sebelum Revisi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\"></td>                                        
                               
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Tanggal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\"></td>                                        
                                
                    </tr> 
                        </table>
                    </td>   
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
                 <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tanggal Perbaharuan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$clast_up</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr>  
                <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Status Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$stts_paket</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr>                                                        
            </table>";    
            
        $data['prev']= $cRet;    
        //echo $cRet;
        $this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }

    function cetak_listswakelola_rup($idrup=''){
        //$kd_skpd = $this->session->userdata('kdskpd');        
        //$pcUser = $this->session->userdata('pcNama');        
        
        //$this->tanggal_format_indonesia($tgl);       
        $xqueryppk = $this->db->query("select top 1 username,kd_skpd from sirup_header where idrup='$idrup' and jenis_paket='2'")->row();
        $zuserppk = $xqueryppk->username;
        $zuserskpd = $xqueryppk->kd_skpd;
        
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PAKET SWAKELOLA</b></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
           <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Perangkat Daerah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : ".strtoupper($this->tukd_model->get_nama($zuserskpd,'nm_skpd','ms_skpd','kd_skpd'))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"16\" style=\"font-size:12px;border: solid 1px white;\"><b>Pejabat Pembuat Komitmen :  ".strtoupper($this->tukd_model->get_nama($zuserppk,'nama','ms_ttd','username'))."</b></td>
            </tr>
            </table>";            
            
           $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;
           $sql = "SELECT b.*,
                    (select top 1 a.nm_program from ms_program a where a.kd_program=left(b.kd_kegiatan,7)) as nm_program,
                    (select top 1 a.nm_skpd from ms_skpd a where a.kd_skpd='$zuserskpd') as nm_skpd
                    from sirup_header b                   
                   where b.kd_skpd='$zuserskpd' and b.jenis_paket='2' and idrup='$idrup'";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;  
                        $cidrup    = $row->idrup;   
                        $cid       = $row->id;         
                        $ctahun    = $row->tahun;
                        $cnskpd    = $row->nm_skpd;                         
                        $ckdkeg    = $row->kd_kegiatan;
                        $cnmprog   = $row->nm_program;
                        $cnmkeg    = $row->nm_kegiatan; 
                        $cnm_paket = $row->nm_paket;
                        $cvolume   = $row->volume;
                        $total     = number_format($row->total,2);                           
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
                        $crenja   =$row->no_renja;
                        $cizin   =$row->izin_tahun_jamak;
                        $tipe_swakelola =$row->tipe_swakelola;
                        $cusername  = $row->username;
                        $clast_up = $row->last_update;    
                        $sqlsw="SELECT top 1 a.ket_swakelola from sirup_swakelola a where a.tipe_swakelola='$tipe_swakelola'";
                        $sw3=$this->db->query($sqlsw);
                        $sw2=$sw3->row();
                        $cket_swakelola=$sw2->ket_swakelola;

                        if($tipe_swakelola==1){
                           $cpswakelola=$cnskpd;  
                        }else{
                           $cpswakelola=$row->nama_satker_lain;
                        }

                        $cumumkan  = $row->umumkan; 
                        $cfinal    = $row->is_final; 

                        if($cumumkan==1 && $cfinal==1){
                            $stts_paket = "Sudah diumumkan";
                        }else if($cumumkan==0 && $cfinal==1){
                            $stts_paket = "Sudah difinalisasi dan belum diumumkan";
                        }else if($cumumkan==0 && $cfinal==0){
                            $stts_paket = "Belum difinalisasi dan belum diumumkan";
                        }    
                                                  
            $cRet .="
                    <tr>
                    <td colspan=\"5\" valign=\"top\" width=\"95%\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black;\">&nbsp;&nbsp;<b>&#8711; &nbsp;".$cnm_paket."</b></td>
                    </tr>
                    
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>ID RUP</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cidrup."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Program Kegiatan</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnmprog."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>   
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>Nama Paket</b></td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"><b>".$cnm_paket."</b></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                                        
                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tahun Anggaran</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Lokasi Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    ";

                    $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"80%\">
                       <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-bottom:1px solid black;\">No.</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border-bottom:1px solid black;\">Provinsi</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border-bottom:1px solid black;\">Kabupaten/Kota</td>
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border-bottom:1px solid black;\">Detail Lokasi</td>
                    </tr>";  
                    
                    
                    
           $sqld = "SELECT a.* from sirup_detail_lokasi a where a.id='$cid' and a.kd_skpd='$zuserskpd' and a.username='$zuserppk'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
            {
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">1</td>
                    <td valign=\"top\" align=\"center\" width=\"7%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->prov."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->nm_lokasi."
                    </td>
                    
                    <td valign=\"top\" align=\"center\" width=\"70%\" style=\"font-size:11px;border:none;\">
                    ".$rowd->det_lokasi."
                    </td>
                                       
                    ";
            }             
            
            $cRet .="</table>
                    </td>
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Volume Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cvolume."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Uraian Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$curaian."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tipe Swakelola</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cket_swakelola."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Penyelenggara Swakelola</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".$cpswakelola."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr> 
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Rincian</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    ";   
           $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"3\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">                                      
                    <td valign=\"top\" align=\"left\" colspan=\"2\" width=\"1%\" style=\"font-size:11px;border:none;border-right:solid 1px black;\">                  
                    <table border=\"0\" width=\"80%\">"; 
            
           $sqld = "SELECT a.* from sirup_detail a where a.id='$cid' and a.kd_skpd='$zuserskpd' and a.kd_kegiatan='$ckdkeg' and a.username='$zuserppk'";               
           $hasild = $this->db->query($sqld);    
           foreach ($hasild->result() as $rowd)
                    {
           $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Sumber Dana</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".$rowd->kd_sd."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Asal Dana Satker</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".strtoupper($this->tukd_model->get_nama($rowd->kd_ads,'nm_skpd','ms_skpd','kd_skpd'))."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">MAK</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;borde:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".$rowd->mak."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Isi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border:none;\">".$rowd->isi_paket."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" width=\"10%\" style=\"font-size:11px;border:none;\">Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-bottom:1px solid #B6B5B5;\">".number_format($rowd->pagu,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;border:none;\">&nbsp;</td>                    
                    </tr>  
                                                          
                    ";              
                    }
                    
           $cRet .="</table>
                    </td>
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Total Pagu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"40%\" style=\"font-size:11px;border-left:none;border-right:none;\">".number_format($row->total,2)."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                    
                    
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Waktu</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"11%\" >&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"20%\" style=\"font-size:11px;\">Pelaksanaan Pekerjaan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\">".$ckawal3." ".$ctahun." - ".$ckakhir3." ".$ctahun."</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" >&nbsp;</td>                    
                    </tr>
                        </table >
                    </td>   
                    </tr>
                     
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:solid 1px black;border-right:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Revisi Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\"></td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border-right:solid 1px black;\">&nbsp;</td>                    
                    </tr>                   
                    <tr>
                    <td colspan=\"5\" style=\"font-size:11px;border-left:solid 1px black;border-right:solid 1px black;\">
                        <table width=\"100%\" >
                        
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Sebelum Revisi</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\"></td>                                        
                               
                    </tr>
                    <tr>
                    <td valign=\"top\" align=\"right\" width=\"32%\" style=\"font-size:11px;\">Tanggal</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;\"></td>                                        
                                
                    </tr> 
                        </table>
                    </td>   
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
                 <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Tanggal Perbaharuan</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$clast_up</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr>  
                <tr>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" width=\"15%\" style=\"font-size:11px;border-left:none;border-right:none;\">Status Paket</td>
                    <td valign=\"top\" align=\"center\" width=\"1%\" style=\"font-size:11px;border-left:none;border-right:none;\">:</td>
                    <td valign=\"top\" align=\"left\" width=\"50%\" style=\"font-size:11px;border-left:none;border-right:none;\">$stts_paket</td>                                        
                    <td valign=\"top\" align=\"center\" width=\"20%\" style=\"font-size:11px;border-left:none;border:none;\">&nbsp;</td>                    
                </tr>                                                        
            </table>";    
            
        $data['prev']= $cRet;    
        //echo $cRet;
        $this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }

    function cetak_listppk(){
        $kd_skpd = $this->uri->segment(4);
        $kd_ppkom = $this->uri->segment(5);
        
        if($kd_skpd==""){
		$kd_skpd = $this->session->userdata('kdskpd');}        
        //$this->tanggal_format_indonesia($tgl);       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>LIST PROGRAM KEGIATAN PPKOM</b></td>
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
            
           if($kd_ppkom=="-"){
                 $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">";
           
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px;\"><b>NO</b></td>   
                    <td valign=\"top\" align=\"center\" width=\"30%\" style=\"font-size:11px;\"><b>LOKASI</b></td>                 
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;\"><b>KODE</b></td>                    
                    <td valign=\"top\" align=\"center\" width=\"30%\" style=\"font-size:11px;\"><b>SUB KEGIATAN</b></td>
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;\"><b>NAMA PPKOM</b></td>
                    <td valign=\"top\" align=\"center\" width=\"5%\" style=\"font-size:11px;\"><b>PAKET</b></td>
                    </tr>
                    
                    ";
            }else{
                $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">";
           
            $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px;\"><b>NO</b></td>                    
                    <td valign=\"top\" align=\"center\" width=\"30%\" style=\"font-size:11px;\"><b>LOKASI</b></td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;\"><b>KODE</b></td>
                    <td valign=\"top\" align=\"center\" width=\"30%\" style=\"font-size:11px;\"><b>SUB KEGIATAN</b></td>
                    <td valign=\"top\" align=\"center\" width=\"25%\" style=\"font-size:11px;\"><b>NAMA PPKOM</b></td>
                    </tr>
                    
                    "; 
            }    

          
            
           
           $no=0;
           $tot_pagu=0;
           $tot_reki=0;

           if($kd_ppkom=="-"){
                 $sql = "
            select 
            isnull((select top 1
            case when b.kunci=3 then 'nonaktif'
            else 
            case when
            (select count(username) from ms_ttd where kd_skpd='$kd_skpd' and username=b.user_name)=0 
            then 'nonaktif'
            else 'aktif' end 
            end as hasil
            from [user] b where kd_skpd='$kd_skpd' and b.user_name=a.username),'nonaktif') status,     
            a.kd_sub_kegiatan,a.nm_sub_kegiatan,
            (select top 1 nama from [user] where id_user=a.id and bidang='6' and kd_skpd='$kd_skpd') ppk,
            (select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_kegiatan,
            (select count(idrup) from sirup_header where username=a.username and kd_kegiatan=a.kd_sub_kegiatan) paket
            from ms_sub_kegiatan_rup a 
                   where left(a.kd_skpd,17)=left('$kd_skpd',17)  order by a.kd_sub_kegiatan";   
           }else{
                 $sql = "select 'aktif' status,a.kd_sub_kegiatan,a.nm_sub_kegiatan,
            (select top 1 nama from [user] where id_user=a.id and bidang='6' and kd_skpd='$kd_skpd') ppk,
            (select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_kegiatan,
            0 paket
            from ms_sub_kegiatan_rup a 
                   where a.username='$kd_ppkom' order by a.kd_sub_kegiatan";   
           }

                      
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no+1;  
                        $cstatus   = $row->status;
                        $ckdkeg    = $row->kd_sub_kegiatan;
                        $cnmpro    = $row->nm_kegiatan; 
                        $cnmkeg    = $row->nm_sub_kegiatan; 
                        $cnm_ppk   = $row->ppk;
                        $cpaket    = $row->paket;
            
            if($kd_ppkom=="-"){
            if($cstatus=='aktif'){
                $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px;\">$no</td>                    
                    <td valign=\"top\" align=\"justify\" width=\"20%\" style=\"font-size:11px; \">&nbsp;$cnmpro</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;\">$ckdkeg</td>
                    <td valign=\"top\" align=\"justify\" width=\"30%\" style=\"font-size:11px; \">&nbsp;$cnmkeg</td>
                    <td valign=\"top\" align=\"left\" width=\"25%\" style=\"font-size:11px;\">&nbsp;<b>$cnm_ppk</b></td>
                    <td valign=\"top\" align=\"justify\" width=\"5%\" style=\"font-size:11px; \">&nbsp;$cpaket</td>
                    </tr>
                    
                    ";
                
            }else{
                $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px; color:red;\">$no</td>                    
                    <td valign=\"top\" align=\"justify\" width=\"20%\" style=\"font-size:11px; color:red;\">&nbsp;$cnmpro</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px; color:red;\">$ckdkeg</td>
                    <td valign=\"top\" align=\"justify\" width=\"30%\" style=\"font-size:11px; color:red;\">&nbsp;$cnmkeg</td>
                    <td valign=\"top\" align=\"left\" width=\"25%\" style=\"font-size:11px; color:red;\">&nbsp;<b>$cnm_ppk</b></td>
                    <td valign=\"top\" align=\"justify\" width=\"5%\" style=\"font-size:11px; color:red;\">&nbsp;$cpaket</td>
                    </tr>
                    
                    ";
                
            }}
            else{
            if($cstatus=='aktif'){
                $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px;\">$no</td>                         
                    <td valign=\"top\" align=\"justify\" width=\"20%\" style=\"font-size:11px; \">&nbsp;$cnmpro</td>               
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px;\">$ckdkeg</td>
                    <td valign=\"top\" align=\"justify\" width=\"30%\" style=\"font-size:11px; \">&nbsp;$cnmkeg</td>
                    <td valign=\"top\" align=\"left\" width=\"25%\" style=\"font-size:11px;\">&nbsp;<b>$cnm_ppk</b></td>
                    </tr>
                    
                    ";
                
            }else{
                $cRet .="
                    <tr>
                    <td valign=\"top\" align=\"center\" width=\"4%\" style=\"font-size:11px; color:red;\">$no</td>                    
                    <td valign=\"top\" align=\"justify\" width=\"20%\" style=\"font-size:11px; color:red;\">&nbsp;$cnmpro</td>
                    <td valign=\"top\" align=\"center\" width=\"10%\" style=\"font-size:11px; color:red;\">$ckdkeg</td>
                    <td valign=\"top\" align=\"justify\" width=\"30%\" style=\"font-size:11px; color:red;\">&nbsp;$cnmkeg</td>
                    <td valign=\"top\" align=\"left\" width=\"25%\" style=\"font-size:11px; color:red;\">&nbsp;<b>$cnm_ppk</b></td>
                    </tr>
                    
                    ";                
            }        
            }                                     
            
            
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
            $this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output();
               
    }
	 
}