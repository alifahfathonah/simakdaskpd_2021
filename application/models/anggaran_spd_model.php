<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */  
  
class anggaran_spd_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
 
    function get_status($tgl,$skpd){
        $n_status = '';
        $tanggal = $tgl;
        $sql = "SELECT case when '$tanggal'>=tgl_dpa_ubah then 'nilai_ubah' 
                    when '$tanggal'>=tgl_dpa_sempurna then 'nilai_sempurna' 
                    when '$tanggal'<=tgl_dpa 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd' ";
 
        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;                         
    }

    function get_status2($skpd){
        
        $sql = "SELECT case when statu='1' and status_sempurna='1' and status_ubah='1' then 'nilai_ubah' 
                    when statu='1' and status_sempurna='1' then 'nilai_sempurna' 
                    when statu='1' 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd'";
        
        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;                        
    }  

    function get_status3($skpd){
        
        $sql = "SELECT case when statu='1' and status_sempurna='1' and status_ubah='1' then 'Nilai Perubahan' 
                    when statu='1' and status_sempurna='1' then 'Nilai Pergeseran' 
                    when statu='1' 
                    then 'Nilai Murni' else 'Nilai Murni' end as anggaran from trhrka where kd_skpd ='$skpd'";
        
        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;   
                    
    }


    function bln_spdakhir($kdskpd,$jns){
        $query1 = $this->db->query("select top 1 bulan_akhir from trhspd where kd_skpd='$kdskpd' and jns_beban='$jns' order by tgl_spd desc ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                'id' => $ii,        
                'cbulan_akhir' => $resulte['bulan_akhir']                                              
            );
            $ii++;
        }
       
        return json_encode($result);
    }

    function load_spd_bl($kriteria,$kd_skpd,$id,$beban){
        
        if($beban=='belanja'){
            $beban="5";
            $judul="BELANJA";
        }else{
            $beban="6";
            $judul="PEMBIAYAAN";
        }
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        

        if ($kriteria <> ''){                               
            $where="WHERE ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper(left(a.jns_beban,1)='$beban' 
                    and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ) ";            
        }
        
        $sql = "SELECT count(*) as total from trhspd a where left(jns_beban,1)='$beban' " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();

        $sql = "SELECT DISTINCT a.*,nama, '$judul' AS nm_beban from trhspd a left join ms_ttd b 
        on a.kd_bkeluar=b.nip WHERE left(jns_beban,1)='$beban' order by no_spd,tgl_Spd,kd_skpd ";

        $query1 = $this->db->query($sql);       
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                        'tgl_spd' => $resulte['tgl_spd'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'ketentuan' => $resulte['klain'],
                        'nama_bend' => $resulte['nama'],
                        'nip' => $resulte['kd_bkeluar'],                        
                        'jns_beban' => $resulte['jns_beban'],
                        'nm_beban' => $resulte['nm_beban'],
                        'bulan_awal' => $resulte['bulan_awal'],
                        'bulan_akhir' => $resulte['bulan_akhir'],
                        'total' => $resulte['total'],                                                                      
                        'status' => $resulte['status']                                                                      
                        );
                        $ii++;
        }
        $result["rows"] = $row;           
        return json_encode($result);     
    }


    function jumlah_detail_angkas_spd_baru($skp,$jn){ /*cek selisih angkas*/
         $n_status = $this->get_status2($skp);
         $sql = "SELECT count(*) as total from (
                    select kd_kegiatan,sum(nilai) as nilai,sum(angkas) as angkas,
                    (sum(nilai))-(sum(angkas)) as total from(
                        select a.kd_sub_kegiatan kd_kegiatan,sum($n_status) as nilai,0 as angkas from trdrka a
                        inner join (select kd_kegiatan from trdskpd_ro GROUP BY kd_kegiatan) b 
                        on a.kd_sub_kegiatan=b.kd_kegiatan group by a.kd_sub_kegiatan 
                        union all
                        select a.kd_kegiatan,0 as nilai,sum(nilai) as angkas from trdskpd_ro a inner join 
                        (select kd_sub_kegiatan kd_kegiatan from trdrka GROUP BY kd_sub_kegiatan) b 
                        on a.kd_kegiatan=b.kd_kegiatan group by a.kd_kegiatan 
                    )z group by kd_kegiatan
                )x where x.total != 0 and substring(kd_kegiatan,6,10)='$skp'";
           
                
        $query1 = $this->db->query($sql);  
        $test   = $query1->num_rows();
        $ii     = 0;
        
        foreach($query1->result_array() as $resulte){ 
            $result = array(
                        'id' => $ii,        
                        'total' => $resulte['total']);
                        $ii++;
        }
        
        if ($test===0){
            $result = array(
                        'total' => ''
                        );
                        $ii++;
        }       
        return json_encode($result); 
    } 

   function config_spd_nomor(){    
        $sql = "SELECT  isnull(max(urut),0)+1 nilai from trhspd"; 
        $query1 = $this->db->query($sql);   
        
        foreach($query1->result_array() as $resulte){ 
            $result = array(                                
                        'nomor' => $resulte['nilai']
                        );
                        
        }
        return json_encode($result);  
    }

    function load_dspd_ag_bl($no,$jenis,$skpd,$dskpd,$tgl,$cbln1,$page,$rows,$offset,$kriteria) {            
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }


        
         $n_status = $this->get_status($tgl,$skpd);
        $field=$n_status;
        $sql = "SELECT  a.*,kd_rek5,nm_rek5 ,(SELECT SUM(nilai_final) 
                FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_kegiatan=a.kd_kegiatan  AND m.no_spd <> '$no' and month(m.tgl_spd)<'$cbln1') AS lalu,
                (select sum($field) from trdrka where kd_sub_kegiatan = a.kd_kegiatan) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd 
                where a.no_spd = '$no' AND left(b.kd_skpd,20)='$dskpd' 
                order by b.no_spd,a.kd_kegiatan,a.kd_rek5 "; 
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program'  => $resulte['kd_program'],
                        'nm_program'  => $resulte['nm_program'],
                        'kd_rekening' => $resulte['kd_rek5'],
                        'nm_rekening' => $resulte['nm_rek5'],
                        'nilai'       => number_format($resulte['nilai'],"2",".",","),
                        'lalu'        => number_format($resulte['lalu'],"2",".",","),
                        'anggaran'    => number_format($resulte['anggaran'],"2",".",",")                               
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        $query1->free_result();   
        return json_encode($result);
    }

    function load_spd_bl_angkas($kd_skpd,$page, $rows,$offset,$kriteria,$id,$beban) {
        if($beban=='belanja'){
            $beban=5;
            $judul="BELANJA";
        }else{
            $beban=6;
            $judul="PEMBIAYAAN";
        }
        $where ="WHERE left(jns_beban,1)='$beban' ";
        if ($kriteria <> ''){                               
            $where="where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper( left(a.jns_beban,1) )='$beban' ) ";            
        }
        
        $sql = "SELECT count(*) as total from trhspd a $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
         
        $sql = "SELECT TOP $rows  a.*,
        isnull((select nama from ms_ttd x where x.nip = a.kd_bkeluar and x.kd_skpd = a.kd_skpd),'') nama
        ,'$judul' AS nm_beban from trhspd a  $where  AND no_spd not in (SELECT TOP $offset  no_spd from trhspd a left join ms_ttd b 
        on a.kd_bkeluar=b.nip where left(a.jns_beban,1)='$beban' order by a.tgl_Spd,a.kd_skpd) and a.jns_beban='$beban' order by urut ";
        $query1 = $this->db->query($sql);       
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
             if($resulte['refisi']==""){
                $cekrefisi=0;
                $ketrefisi='Belum Revisi';
            }else{
                $cekrefisi=$resulte['refisi'];
                $ketrefisi='Revisi Ke '.$resulte['refisi'];
            }
            
            $opd = $resulte['kd_skpd'];
            $n_status = $this->get_status3($opd);

            $row[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                        'tgl_spd' => $resulte['tgl_spd'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'ketentuan' => $resulte['klain'],
                        'nama_bend' => $resulte['nama'],
                        'nip' => $resulte['kd_bkeluar'],                        
                        'jns_beban' => $resulte['jns_beban'],
                        'nm_beban' => $resulte['nm_beban'],
                        'bulan_awal' => $resulte['bulan_awal'],
                        'bulan_akhir' => $resulte['bulan_akhir'],
                        'total' => $resulte['total_hasil'],                                                                      
                        'status' => $resulte['status'],
                        'st' => $resulte['status'],
                        'refisi' => $cekrefisi,
                        'ket_refisi' => $ketrefisi,
                        'tgl_refisi' => $resulte['tgl_refisi1'],
                        'status_ang' => $n_status                                                                            
                        );
                        $ii++;
        }
        $result["rows"] = $row;           
        return json_encode($result);
      
    }

    function load_tot_dspd_bl($jenis,$skpd,$awal,$ahir,$nospd,$tgl1){
    
              
        $n_status = $this->get_status2($skpd);
        $spd = str_replace('123456789','/',$nospd);
        $sql = "SELECT SUM(nilai) as nilai FROM
                (SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                 kd_skpd
                FROM trskpd a WHERE left(a.kd_skpd,20)=left('$skpd',20) and a.jns_kegiatan='$jenis') a
                LEFT JOIN
                (SELECT kd_kegiatan, SUM($n_status) as nilai FROM trdskpd_ro b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
                AND left(kd_skpd,20)=left('$skpd',20)
                GROUP BY kd_kegiatan)b
                ON a.kd_sub_kegiatan=b.kd_kegiatan";
        $query1 = $this->db->query($sql);  
        $ii     = 0;

        foreach($query1->result_array() as $resulte){ 
            $result = array(
                        'id' => $ii,        
                        'nilai' => $resulte['nilai']
                        );
                        $ii++;
        }                
        return json_encode($result);
 
    }

    function load_dspd_bl($jenis,$skpd,$awal,$ahir,$nospd,$cbln1,$tgl,$page,$rows,$offset,$kriteria){

        $dskpd = substr($skpd,0,20);  
        if ($kriteria <> ''){                               
            $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
        if($jenis=='belanja'){
            $jns_giat="5";
        }else{
            $jns_giat="6";
        }

        $n_status = $this->get_status2($skpd);
        $spd = str_replace('123456789','/',$nospd);
         
        $sql = "SELECT a.kd_kegiatan, a.nm_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                a.total_ubah as anggaran, nilai,lalu FROM(
                   
                select a.kd_sub_kegiatan kd_kegiatan, b.nm_sub_kegiatan nm_kegiatan, b.kd_program, b.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                sum(a.$n_status) as total_ubah, left(a.kd_skpd,20) kd_skpd
                FROM trdrka a inner join trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                WHERE left(a.kd_skpd,20)='$dskpd' and left(a.kd_rek6,1) ='$jns_giat' 
                group by a.kd_sub_kegiatan, b.nm_sub_kegiatan, b.kd_program, b.nm_program,a.kd_skpd

                ) a LEFT JOIN (

                   SELECT kd_subkegiatan kd_kegiatan, LEFT(kd_skpd, 20) as kd_skpd, SUM ($n_status) AS nilai FROM trdskpd_ro
                   WHERE LEFT (kd_skpd, 20) = '$dskpd' and left(kd_rek6,1)='$jns_giat' AND bulan BETWEEN '$awal' AND '$ahir' 
                   GROUP BY kd_subkegiatan, LEFT (kd_skpd, 20)

                )b ON a.kd_kegiatan=b.kd_kegiatan AND left(a.kd_skpd,20)=left(b.kd_skpd,20) 
                LEFT JOIN (
                    SELECT kd_subkegiatan,SUM(a.nilai_final) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                    WHERE left(b.kd_skpd,20)='$dskpd' and a.no_spd != '$spd' and b.jns_beban='$jns_giat' and bulan_akhir <'$cbln1'
                    GROUP BY kd_subkegiatan
                ) c ON a.kd_kegiatan=c.kd_subkegiatan              
                ORDER BY a.kd_kegiatan 
                ";
            

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id'          => $ii,        
                        'no_spd'      => '',                        
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program'  => $resulte['kd_program'],
                        'nm_program'  => $resulte['nm_program'],
                        'kd_rekening' => $resulte['kd_rek5'],
                        'nm_rekening' => $resulte['nm_rek5'],
                        'nilai'       => number_format($resulte['nilai'],"2",".",","),
                        'lalu'        => number_format($resulte['lalu'],"2",".",","),
                        'anggaran'    => number_format($resulte['anggaran'],"2",".",",")
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        $query1->free_result();   
        return json_encode($result);
    }

    function cek_simpan_spd($nomor,$skpd,$awal,$akhir){
        $hasil=$this->db->query("SELECT sum(z.jumlah) as jumlah from(
        select count(*) as jumlah from trhspd where no_spd='$nomor' UNION
        select count(*) as jumlah from trhspd where kd_skpd='$skpd' and bulan_awal='$awal' and bulan_akhir='$akhir'
        and no_spd='$nomor')z");
        foreach ($hasil->result_array() as $row){
        $jumlah=$row['jumlah']; 
        }
        if($jumlah>0){
            $msg = array('pesan'=>'1');
            return json_encode($msg);
        } else{
            $msg = array('pesan'=>'0');
            return json_encode($msg);
        }
        
    } 

    function hapus_spd($nomor){
        $msg = array();         
        $sql = "delete from trdspd where no_spd='$nomor'";
        $asg = $this->db->query($sql);
        if ($asg){
            $sql = "delete from trhspd where no_spd='$nomor'";
            $asg = $this->db->query($sql);
            if (!($asg)){
               $msg = array('pesan'=>'0');
               return json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            return json_encode($msg);
            exit();
        }
          $msg = array('pesan'=>'1');
          return json_encode($msg);              
    } 

    function update_sts_spd($no_spd, $ckd_skpd,$csts){
        $sql = "update trhspd set status='$csts' where no_spd='$no_spd' and kd_skpd='$ckd_skpd' ";
        $asg = $this->db->query($sql);
        if ($asg > 0){      
            return $csts;
        } else {
            return '5';
        }
    }

    function cek_simpan($nomor,$tabel,$field){ /*untuk cek appakah ada spd di tabel trhspp*/
        $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' ");
        foreach ($hasil->result_array() as $row){
        $jumlah=$row['jumlah']; 
        }
        if($jumlah>0){
            $msg = array('pesan'=>'1');
            return json_encode($msg);
        } else{
            $msg = array('pesan'=>'0');
            return json_encode($msg);
        }
        
    }

}