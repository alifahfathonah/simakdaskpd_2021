<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_tukd extends CI_Controller {
  
    function __construct() 
    {   
        parent::__construct();
    } 

    function bku(){
        $skpd = $this->session->userdata('kdskpd');      
        $cek = substr($skpd,18,4);   
        $data['page_title']= 'BKU';
        $this->template->set('title', 'BKU');
        if($cek=="0000"){    
            $data['bpp']= 'bp';
            $this->template->load('template','tukd/transaksi/bku',$data) ;
        }else{
            $data['bpp']= 'bpp';
            $this->template->load('template','tukd/transaksi/bku_bpp',$data) ;                
        } 
    }

    function bku_global(){
        $data['page_title']= 'BKU REKAP';
        $this->template->set('title', 'BKU REKAP');   
        $this->template->load('template','tukd/transaksi/bku_global',$data) ; 
    }
    function cetak_bku_skpd(){
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);
 
		$this->db->query("recall_skpd '$lcskpd'");
		//$daerah=$this->tukd_model->get_nama($lcskpd,'daerah','sclient','kd_skpd');
         if ($pilih==1){
            $lctgl1 = $_REQUEST['tgl1'];
            $lctgl2 = $_REQUEST['tgl2'];   
            $lcperiode = $this->tukd_model->tanggal_format_indonesia($lctgl1)."  S.D. ".$this->tukd_model->tanggal_format_indonesia($lctgl2);
            $lcperiode1 = "Tanggal ".$this->tukd_model->tanggal_format_indonesia($lctgl1);
            $lcperiode2 = "Tanggal ".$this->tukd_model->tanggal_format_indonesia($lctgl2);			
			
         }else{
            $bulan = $_REQUEST['bulan'];
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
            if($bulan==1){
                $lcperiode1 = "Bulan Sebelumnya";    
            }else{
            $lcperiode1 = "Bulan ".$this->tukd_model->getBulan($bulan-1);
            }  
            $lcperiode2 = "Bulan ".$this->tukd_model->getBulan($bulan);;
         }
         
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         

         if ($pilih==1){
             $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             z.tgl_kas < '$lctgl1' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
         }else{
             $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'"
             
             ;
             }

             $tox_awal="SELECT isnull(sld_awal,0) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
             $hasil = $this->db->query($tox_awal);
             $tox = $hasil->row('jumlah');
             
         $hasil = $this->db->query($csql3);
         $trh4 = $hasil->row(); 
         
         $saldoawal = $trh4->sel;
         $saldoawal=$saldoawal+$tox;
			$lcskpdd = substr($lcskpd,0,17);
			$lcskpdd = $lcskpdd.".0000";
			$prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpdd'");
			$prvn = $prv->row();          
			$prov = $prvn->provinsi;         
			$daerah = $prvn->daerah;
			
            if ($pilih==1){
			$asql="SELECT
			SUM(case when jns=1 then jumlah else 0 end) AS terima,
			SUM(case when jns=2 then jumlah else 0 end) AS keluar
			from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
			
			SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
			SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan  WHERE status_drop!='1' union ALL
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') 
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
             ) a
			where tgl<='$lctgl2' and kode='$lcskpd'";	 	
			}else{
			$asql="SELECT
			SUM(case when jns=1 then jumlah else 0 end) AS terima,
			SUM(case when jns=2 then jumlah else 0 end) AS keluar
			from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
						
			SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
			SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') 
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
			where month(tgl)<='$bulan' and kode='$lcskpd'";	
			}
         
						
		$hasil=$this->db->query($asql);
		$bank=$hasil->row();
		$keluarbank=$bank->keluar;
		$terimabank=$bank->terima;
		$saldobank =$terimabank-$keluarbank;
        
        
        $xterima_lalu = 0; $xkeluar_lalu=0; $xhasil_lalu=0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
        foreach ($sk_lalu->result() as $rowxll)
        {        
        $xskpd = $rowxll->kd_skpd;
        
        if ($pilih==1){
            $sqlitull = "kas_tunai_tgl_lalu '$xskpd','$lctgl1'";   
        }else{
            $sqlitull = "kas_tunai_lalu '$xskpd','$bulan'";   
        }
        
        $sqlituull = $this->db->query($sqlitull); 
        $sqlituql=$sqlituull->row();	
            $xterima_lalu = $xterima_lalu+$sqlituql->terima; 
            $xkeluar_lalu = $xkeluar_lalu+$sqlituql->keluar;             
        }
        $xhasil_lalu = ($xterima_lalu-$xkeluar_lalu);
        
        $xterima = 0; $xkeluar=0; $xhasil_tunai=0;
        $sk = $this->db->query("SELECT kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
        foreach ($sk->result() as $rowx)
        {        
        $xskpd = $rowx->kd_skpd;
        
        if ($pilih==1){
            $sqlitu = "kas_tunai_tgl '$xskpd','$lctgl1','$lctgl2'";   
        }else{
            $sqlitu = "kas_tunai '$xskpd','$bulan'";
        }
        
        $sqlituu = $this->db->query($sqlitu); 
        $sqlituq=$sqlituu->row();	
            $xterima = $xterima+$sqlituq->terima; 
            $xkeluar = $xkeluar+$sqlituq->keluar;             
        }
        $xhasil_tunai = ($xterima-$xkeluar)+$xhasil_lalu;
                
        //
        
        //saldo pajak
        
        if($pilih==1){
            $asql_pjk="SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6, nm_rek5 nm_rek6 FROM ms_pot WHERE kd_rek5 IN ('2130101','2130201','2130301','2130401','2130501','4110707'))a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'									
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'					
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6"; 
        }else{
            $asql_pjk="SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6,nm_rek5 nm_rek6 FROM ms_pot WHERE kd_rek5 IN ('2130101','2130201','2130301','2130401','2130501','4110707'))a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'									
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'					
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6"; 
        }
          
        $hasil_pjk=$this->db->query($asql_pjk);
		$pjkk=$hasil_pjk->row();
		$sisa_pajakk=$pjkk->sisa;
            
			 		 
		 // SALDO SURAT BERHARGA (SP2D yang tanggal pencairannya beda dengan tanggal sp2dnya) 
		if ($pilih==1){
		$csql="SELECT sum(nilai) as total from trhsp2d where (tgl_terima BETWEEN '$lctgl1' and '$lctgl2')  and kd_skpd = '$lcskpd' and status_terima = '1' and (tgl_kas > '$lctgl2' or no_kas is null or no_kas='')";
		}else{
		$csql="SELECT sum(nilai) as total from trhsp2d where month(tgl_terima)='$bulan' and kd_skpd = '$lcskpd' and status_terima = '1' and (month(tgl_kas) > '$bulan' or no_kas is null or no_kas='')";
		}			 
					 $hasil_srt = $this->db->query($csql);
					 $saldoberharga = $hasil_srt->row('total');
		 
 $lcskpdd = substr($lcskpd,0,17);
 
 $nippa = str_replace('123456789',' ',$_REQUEST['ttd']);		     
 $csql="SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE id_ttd = '$nippa' ";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row(); 
 $nipbk = str_replace('123456789',' ',$_REQUEST['ttd2']);		     
 $csql="SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE id_ttd = '$nipbk'";                
         $hasil3 = $this->db->query($csql);
         $trh3 = $hasil3->row(); 
 $csql="SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";                
         $hasil4 = $this->db->query($csql);
         $trh4 = $hasil4->row();
$nipbpp = str_replace('123456789',' ',$_REQUEST['ttd3']);
if($nipbpp==''){
    $nipbpp=0;
}		     
 $csql="SELECT nip as nip_bk,nama as nm_bpp,jabatan,pangkat FROM ms_ttd WHERE id_ttd = '$nipbpp'";                
         $hasil5 = $this->db->query($csql);
         $trh5 = $hasil5->row(); 
		 
		$cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
			<tr>
                <td align='center' colspan='16' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU KAS UMUM PENGELUARAN</b></td>
            </tr>
            <tr>
                <td align='center' colspan='16' style='font-size:14px;border: solid 1px white;'><b>PERIODE ".strtoupper($lcperiode)."</b></td>
            </tr>
            <tr>
                <td align='left' colspan='12' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            <tr>
                <td align='left' colspan='12' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            <tr>
                <td align='left' colspan='12' style='font-size:12px;border: solid 1px white;'>SKPD</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>:&nbsp;$trh4->nm_skpd</td>
            </tr>
            <tr>
                <td align='left' colspan='12' style='font-size:12px;border: solid 1px white;'>Pengguna Anggaran / Kuasa Pengguna Anggaran</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>:&nbsp;$trh2->nm_pa</td>
            </tr>
            <tr>
                <td align='left' colspan='12' style='font-size:12px;border: solid 1px white;'>Bendahara Pengeluaran</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>:&nbsp;$trh3->nm_bk</td>
            </tr>";
			if($_REQUEST['ttd3']!=""){
			$cRet .="<tr>
                <td align='left' colspan='12' style='font-size:12px;border: solid 1px white;'>Bendahara Pengeluaran Pembantu</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>:&nbsp;$trh5->nm_bpp</td>
            </tr>";
			}
            $cRet .="<tr>
                <td align='left' colspan='12' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px black;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px black;'></td>
            </tr>
			</table>
			<table style='border-collapse:collapse; border-color: black;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1' >
            <thead> 
			<tr>
     <td align='center' bgcolor='#CCCCCC' width='3%' style='font-size:12px;font-weight:bold;'>No</td>
                <td align='center' bgcolor='#CCCCCC' width='10%' style='font-size:12px;font-weight:bold'>Tanggal</td>
                <td align='center' bgcolor='#CCCCCC' colspan=10 '20%' width='10%' style='font-size:12px;font-weight:bold'>Kode Rekening</td>
                <td align='center' bgcolor='#CCCCCC' width='22%' style='font-size:12px;font-weight:bold'>Uraian</td>
                <td align='center' bgcolor='#CCCCCC' width='13%' style='font-size:12px;font-weight:bold'>Penerimaan</td>
                <td align='center' bgcolor='#CCCCCC' width='13%' style='font-size:12px;font-weight:bold'>Pengeluaran</td>
                <td align='center' bgcolor='#CCCCCC' width='13%' style='font-size:12px;font-weight:bold'>Saldo</td>
            </tr>
            <tr>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>1</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>2</td>
                <td align='center' bgcolor='#CCCCCC' colspan='10' style='font-size:12px;border-top:solid 1px black'>3</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>4</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>5</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>6</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>7</td>
            </tr>
			</thead>";
           
           if ($pilih==1){
           $sql = "SELECT * FROM ( SELECT  z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z )okei
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
           }else{
      
			$sql = "SELECT * FROM ( SELECT  z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
			   CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
			   CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
			   case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z ) OKE
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
           
           }
           
                            
                    $hasil = $this->db->query($sql);
                    $lcno = 0;
                    $lcterima = 0;
                    $lckeluar = 0;
                    $lcterima_pajak = 0;
                    $lckeluar_pajak = 0;
                    $lhasil = $saldoawal;
                    $saldolalu=number_format($lhasil,"2",",",".");
                    $cRet .= "<tr><td valign='top' width='5%' align='center' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>";
                    $cRet .="<td valign='top' width='10%' align='center' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>
                                <td valign='top'  width='13%' colspan='9' align='center' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>
                                <td valign='top'  width='8%' colspan='1' align='center' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>
                                <td valign='top'  width='20%' align='left' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'>Saldo Lalu</td>";
                                $cRet .="<td valign='top'  width='13%' align='right' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>";
                                 $cRet .="<td valign='top'  width='13%' align='right' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>";
                                 $cRet .="<td valign='top'  width='13%' align='right' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'>$saldolalu</td></tr>";
                    foreach ($hasil->result() as $row)
                    {
                        $cRet .="<tr>";
                        $lhasil = $lhasil + $row->terima - $row->keluar;
                       if(!empty($row->tanggal)){
                         $a=$row->tanggal;
                         $jaka=$this->tukd_model->tanggal_ind($a);
                         $lcno = $lcno + 1;
                         $no_bku = $row->no_kas;
						 
                         $cRet .= "<td valign='top' align='center' style='font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray'>$no_bku</td>
                                    <td valign='top' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>$jaka</td>
									<td valign='top' colspan='9' align='center' style='font-size:10px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".($row->kegiatan)."</td>
									<td valign='top' colspan='1'align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".($row->rekening)."</td>                
                                <td valign='top' align='left' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>$row->uraian</td>
								";
								 if(empty($row->terima) or ($row->terima)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'></td>";
                                }else{
                                    $lcterima = $lcterima + $row->terima; 
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".number_format($row->terima,"2",",",".")."</td>";
                                }
								if(empty($row->keluar) or ($row->keluar)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'></td>";
                                }else{
                                    $lckeluar = $lckeluar + $row->keluar; 
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".number_format($row->keluar,"2",",",".")."</td>";
                                }
                                if(empty($row->terima) and empty($row->keluar) or ($row->terima)==0 and ($row->keluar)==0){
                                $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'></td>";
                                }else{
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".number_format($lhasil,"2",",",".")."</td>";
                                }
                       }else{
                        $cRet .= " <td valign='top' align='center' style='font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray'>&nbsp;</td>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>&nbsp;</td>
                                <td valign='top' colspan='9' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".($row->kegiatan)."</td>
								<td valign='top' colspan='1'align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".($row->rekening)."</td>                
                                <td valign='top' align='left' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>$row->uraian</td>
								";
								 if(empty($row->terima) or ($row->terima)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'></td>";
                                }else{
                                                                        
                                    if($row->jns_trans=='3'){
                                        $lcterima_pajak = $lcterima_pajak + $row->terima;
                                    }else{
                                        $lcterima = $lcterima + $row->terima; 
                                    }                                                                         
                                    
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".number_format($row->terima,"2",",",".")."</td>";
                                }
								if(empty($row->keluar) or ($row->keluar)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'></td>";
                                }else{
                                    
                                    if($row->jns_trans=='4'){
                                        $lckeluar_pajak = $lckeluar_pajak + $row->keluar;
                                    }else{
                                        $lckeluar = $lckeluar + $row->keluar;  
                                    }
                                    
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".number_format($row->keluar,"2",",",".")."</td>";
                                }
                                if(empty($row->terima) and empty($row->keluar) or ($row->terima)==0 and ($row->keluar)==0){
                                $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'></td>";
                                }else{
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".number_format($lhasil,"2",",",".")."</td>";
                                }
                       }
                            $cRet .="</tr>";    
                    }

        $cRet .="<tr>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' colspan='9' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                 </tr>";
			
                 if ($pilih==1){                 
                 $csql="SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas < '$lctgl1'  and a.kd_skpd = '$lcskpd'";
                 }else{
                    $csql="SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) < '$bulan' and year(a.tgl_kas) = $thn_ang and a.kd_skpd = '$lcskpd'";
                 }
                        
                 $hasil = $this->db->query($csql);
                 $trh1 = $hasil->row(); 
                
            
        $cRet .="<tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>Kas di Bendahara Pengeluaran bulan $lcperiode2 </td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'>".number_format(($trh1->jmterima+$lcterima+$lcterima_pajak+$tox),"2",",",".")."</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'>".number_format(($trh1->jmkeluar+$lckeluar+$lckeluar_pajak),"2",",",".")."</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'>".number_format(($trh1->jmterima+$lcterima+$lcterima_pajak-$trh1->jmkeluar-$lckeluar-$lckeluar_pajak+$tox),"2",",",".")."</td>
                 </tr>
		
					<tr>
                    <td colspan='2' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan ='14'valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($xhasil_tunai),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'></td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'></td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($saldobank),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($saldoberharga),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($sisa_pajakk),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='12' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>";
		/*		 
				 if($_REQUEST['ttd3']!=""){
				 
                    $cRet .="<td align='center' colspan='6' style='font-size:11px;border: solid 1px white;'>
					Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign='top' align='center' colspan='8' style='font-size:11px;border: solid 1px white;'>
					<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
					<br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";										
					 $cRet .="<td valign='top' align='center' colspan='4' style='font-size:11px;border: solid 1px white;'>
					".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh5->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
					<br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
				 }else{
					 $cRet .="<td align='center' colspan='12' style='font-size:11px;border: solid 1px white;'>
					Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign='top' align='center' colspan='4' style='font-size:11px;border: solid 1px white;'>
					".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
					<br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>"; 
				 }
					$cRet .="</tr>
		*/
        
                if($_REQUEST['ttd3']!=""){
				 
                    $cRet .="<td align='center' colspan='12' style='font-size:11px;border: solid 1px white;'>
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign='top' align='center' colspan='4' style='font-size:11px;border: solid 1px white;'>
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh5->jabatan<br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
				 }else{
					 $cRet .="<td align='center' colspan='12' style='font-size:11px;border: solid 1px white;'>
					Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign='top' align='center' colspan='4' style='font-size:11px;border: solid 1px white;'>
					".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
					<br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>"; 
				 }
					$cRet .="</tr>
        </table>";
        
        $print = $this->uri->segment(3);
		if($print==0){
 
         $data['prev']= $cRet;    
		 echo ("<title>Buku Kas Umum</title>");
		 echo $cRet;}
		 else{
			$this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'0',1,'',$atas,$bawah,$kiri,$kanan);
	    //$this->_mpdf('',$cRet,10,10,10,'0',1,'');
}		
     
     }


    function cetak_bku_global(){
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $skpx = substr($lcskpd,0,17);
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);
        
        /*$ckbpp = $this->db->query("select kd_skpd from ms_skpd where left(kd_skpd,17)=left('$lcskpd',17)");
        foreach($ckbpp->result_array() as $resulte)
        {        
        $skppd = $resulte['kd_skpd'];       
        
        $this->db->query("recall '$skppd'");             
        }*/
        $this->db->query("recall_global '$lcskpd'");  
        $this->db->query("WITH a as
(
SELECT 
no_kas,kd_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,terima,keluar,jns_trans,
ROW_NUMBER() OVER(PARTITION by 
no_kas,kd_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,terima,keluar,jns_trans
 ORDER BY no_kas) 
AS duplicateRecCount
FROM trdrekal  
)
--Now Delete Duplicate Records
DELETE FROM a
WHERE duplicateRecCount > 1
");    
        
        //$this->db->query("recall '$lcskpd'");
        //$daerah=$this->tukd_model->get_nama($lcskpd,'daerah','sclient','kd_skpd');
         if ($pilih==1){
            $lctgl1 = $_REQUEST['tgl1'];
            $lctgl2 = $_REQUEST['tgl2'];   
            $lcperiode = $this->tukd_model->tanggal_format_indonesia($lctgl1)."  S.D. ".$this->tukd_model->tanggal_format_indonesia($lctgl2);
            $lcperiode1 = "Tanggal ".$this->tukd_model->tanggal_format_indonesia($lctgl1);
            $lcperiode2 = "Tanggal ".$this->tukd_model->tanggal_format_indonesia($lctgl2);          
            
         }else{
            $bulan = $_REQUEST['bulan'];
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
            if($bulan==1){
                $lcperiode1 = "Bulan Sebelumnya";    
            }else{
            $lcperiode1 = "Bulan ".$this->tukd_model->getBulan($bulan-1);
            }  
            $lcperiode2 = "Bulan ".$this->tukd_model->getBulan($bulan);;
         }
         
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         

         if ($pilih==1){
             $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             z.tgl_kas < '$lctgl1' and year(z.tgl_kas) = $thn_ang AND LEFT(z.kd_skpd,17)=LEFT('$lcskpd',17)";
         }else{
             $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND LEFT(z.kd_skpd,17)=LEFT('$lcskpd',17)"
             
             ;
             }

             $tox_awal="SELECT isnull(sld_awal,0) AS jumlah FROM ms_skpd where LEFT(kd_skpd,17)=LEFT('$lcskpd',17)";
             $hasil = $this->db->query($tox_awal);
             $tox = $hasil->row('jumlah');
             
         $hasil = $this->db->query($csql3);
         $trh4 = $hasil->row(); 
         
         $saldoawal = $trh4->sel;
         $saldoawal=$saldoawal+$tox;
         
            $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpd'");
            $prvn = $prv->row();          
            $prov = $prvn->provinsi;         
            $daerah = $prvn->daerah;
            
            if ($pilih==1){
            $asql="SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan  WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK' 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
             ) a
            where tgl <= '$lctgl2' and left(kode,17)=left('$lcskpd',17)";     
            }else{
            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2')  and bank='BNK' 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where month(tgl)<='$bulan' and left(kode,17)=left('$lcskpd',17)"; 
            }

                        
        $hasil=$this->db->query($asql);
        $bank=$hasil->row();
        $keluarbank=$bank->keluar;
        $terimabank=$bank->terima;
        $saldobank=$terimabank-$keluarbank;
        
        
        //saldo tunai
        /*
        
        //bank
            if ($pilih==1){
            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan  WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
             ) a
            where tgl<='$lctgl2' and LEFT(kode,17)=LEFT('$lcskpd',17)";       
            }else{
            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
                        
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where month(tgl)<='$bulan' and LEFT(kode,17)=LEFT('$lcskpd',17)"; 
            }

                        
        $hasil=$this->db->query($asql);
        $bank=$hasil->row();
        $keluarbank=$bank->keluar;
        $terimabank=$bank->terima;
        $saldobank=$terimabank-$keluarbank;
        //tunai
        $esteh="SELECT 
                SUM(case when jns=1 then jumlah else 0 end ) AS terima,
                SUM(case when jns=2 then jumlah else 0 end) AS keluar
                FROM (
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALL
                select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar UNION ALL
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN ('4','2') and pot_khusus =0  
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd             
                UNION ALL
                SELECT  a.tgl_bukti AS tgl, a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
                                AND MONTH(a.tgl_bukti)<'$bulan' and a.kd_skpd='$lcskpd' 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)<'$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd')
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                SELECT  tgl_bukti AS tgl,   no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') and no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)<'$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd'
                
                UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' UNION ALL
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'
                ) a 
                where month(a.tgl)<'$bulan' and left(kode,17)=left('$lcskpd',17)";
              $hasils = $this->db->query($esteh);               
               $okok = $hasils->row();  
               $terima = $okok->terima;
               $keluar = $okok->keluar;                  
               $saldotunai_skpd=($terima+$tox)-$keluar;
        
        //bulan ini
        
        $sqlini="SELECT SUM(a.masuk) as terima,
                SUM(a.keluar) as keluar FROM (
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                        select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as masuk, 0 as keluar,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALl
                        select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, 0 as masuk,nilai as keluar,kd_skpd as kode from tr_panjar UNION ALL
                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
                                from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                                where jns_trans NOT IN ('4','2') and pot_khusus =0  
                                GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
                        UNION ALL
                        SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
                                AND MONTH(a.tgl_bukti)='$bulan' and a.kd_skpd='$lcskpd' 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
                                (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)='$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd')
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                        select tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 AS masuk, ISNULL(total,0)  AS keluar,kd_skpd AS kode 
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in 
                                (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
                                (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)='$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd'

                        UNION ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION  ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI')a
                        where month(a.tgl)='$bulan' and left(kode,17)=left('$lcskpd',17)";
                        $hasilini = $this->db->query($sqlini);  
                        $tunaiok = $hasilini->row(); 
                        $terimain = $tunaiok->terima;
                        $keluarin = $tunaiok->keluar;                    
                        $saldotunai_skpd_ini=($terimain+$saldotunai_skpd)-$keluarin;
                        */
        $xterima_lalu = 0; $xkeluar_lalu=0; $xhasil_lalu=0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where left(kd_skpd,17)=left('$lcskpd',17)");
        foreach ($sk_lalu->result() as $rowxll)
        {        
        $xskpd = $rowxll->kd_skpd;
        
        if ($pilih==1){
            $sqlitull = "kas_tunai_tgl_lalu '$xskpd','$lctgl1'";   
        }else{
            $sqlitull = "kas_tunai_lalu '$xskpd','$bulan'";   
        }
        
        $sqlituull = $this->db->query($sqlitull); 
        $sqlituql=$sqlituull->row();    
            $xterima_lalu = $xterima_lalu+$sqlituql->terima; 
            $xkeluar_lalu = $xkeluar_lalu+$sqlituql->keluar;             
        }
        $xhasil_lalu = ($xterima_lalu-$xkeluar_lalu);
        
        $xterima = 0; $xkeluar=0; $xhasil_tunai=0;
        $sk = $this->db->query("select kd_skpd from ms_skpd where left(kd_skpd,17)=left('$lcskpd',17)");
        foreach ($sk->result() as $rowx)
        {        
        $xskpd = $rowx->kd_skpd;
        
        if ($pilih==1){
            $sqlitu = "kas_tunai_tgl '$xskpd','$lctgl1','$lctgl2'";   
        }else{
            $sqlitu = "kas_tunai '$xskpd','$bulan'";
        }
        
        $sqlituu = $this->db->query($sqlitu); 
        $sqlituq=$sqlituu->row();   
            $xterima = $xterima+$sqlituq->terima; 
            $xkeluar = $xkeluar+$sqlituq->keluar;             
        }
        $xhasil_tunai = ($xterima-$xkeluar)+$xhasil_lalu;
                
        //
        
        //saldo pajak
        $map_pot="('2130101','2130201','2130301','2130401','2130501','4110707')";
        $map_pot="(SELECT map_pot from ms_pot)";
        if($pilih==1){
            $asql_pjk="SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(kd_rek5) as kd_rek6,nm_rek5 nm_rek6 FROM ms_pot WHERE map_pot IN $map_pot)a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6"; 
        }else{
            $asql_pjk="SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6,nm_rek5 nm_rek6 FROM ms_pot WHERE map_pot IN $map_pot )a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6"; 
        }
          
        $hasil_pjk=$this->db->query($asql_pjk);
        $pjkk=$hasil_pjk->row();
        $sisa_pajakk=$pjkk->sisa;
        
         // SALDO SURAT BERHARGA (SP2D yang tanggal pencairannya beda dengan tanggal sp2dnya) 
        if ($pilih==1){
        $csql="select sum(nilai) as total from trhsp2d where (tgl_terima BETWEEN '$lctgl1' and '$lctgl2')  and kd_skpd = '$lcskpd' and status_terima = '1' and (tgl_kas > '$lctgl2' or no_kas is null or no_kas='')";
        }else{
        $csql="select sum(nilai) as total from trhsp2d where month(tgl_terima)='$bulan' and kd_skpd = '$lcskpd' and status_terima = '1' and (month(tgl_kas) > '$bulan' or no_kas is null or no_kas='')";
        }            
                     $hasil_srt = $this->db->query($csql);
                     $saldoberharga = $hasil_srt->row('total');
        
        
 $nippa = str_replace('123456789',' ',$_REQUEST['ttd']);             
 $csql="SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE id_ttd = '$nippa' ";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row(); 
 $nipbk = str_replace('123456789',' ',$_REQUEST['ttd2']);            
 $csql="SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE id_ttd = '$nipbk' ";
                
         $hasil3 = $this->db->query($csql);
         $trh3 = $hasil3->row(); 
 $csql="SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";
                
         $hasil4 = $this->db->query($csql);
         $trh4 = $hasil4->row();
         
        $cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='17' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU KAS UMUM PENGELUARAN</b></td>
            </tr>
            <tr>
                <td align='center' colspan='17' style='font-size:14px;border: solid 1px white;'><b>PERIODE ".strtoupper($lcperiode)."</b></td>
            </tr>
            <tr>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='13' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            <tr>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='13' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            <tr>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>SKPD</td>
                <td align='left' colspan='13' style='font-size:12px;border: solid 1px white;'>:&nbsp;$trh4->nm_skpd</td>
            </tr>
            <tr>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>Pengguna Anggaran / Kuasa Pengguna Anggaran</td>
                <td align='left' colspan='13' style='font-size:12px;border: solid 1px white;'>:&nbsp;$trh2->nm_pa</td>
            </tr>
            <tr>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>Bendahara Pengeluaran</td>
                <td align='left' colspan='13' style='font-size:12px;border: solid 1px white;'>:&nbsp;$trh3->nm_bk</td>
            </tr>
            <tr>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px black;'>&nbsp;</td>
                <td align='left' colspan='13' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px black;'></td>
            </tr>
            </table>
            <table style='border-collapse:collapse; border-color: black;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1' > 
            <tr>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;font-weight:bold;'>No.</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;font-weight:bold'>Tanggal</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;font-weight:bold'>Bidang</td>
                <td align='center' bgcolor='#CCCCCC' colspan='10' style='font-size:12px;font-weight:bold'>Kode Rekening</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;font-weight:bold'>Uraian</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;font-weight:bold'>Penerimaan</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;font-weight:bold'>Pengeluaran</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;font-weight:bold'>Saldo</td>
            </tr>
            <tr>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>1</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>2</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>3</td>
                <td align='center' bgcolor='#CCCCCC' colspan='10' style='font-size:12px;border-top:solid 1px black'>4</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>5</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>6</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>7</td>
                <td align='center' bgcolor='#CCCCCC' style='font-size:12px;border-top:solid 1px black'>8</td>
            </tr>
            </thead>";
           
           if ($pilih==1){
           $sql = "SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and LEFT(kd_skpd,17)=LEFT('$lcskpd',17))
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and LEFT(b.kd_skpd,17)=LEFT('$lcskpd',17)))z
               ORDER BY tgl_kas,kd_skpd,cast (no_kas as int),jns_trans,st,rekening";
           }else{
      
            $sql = "SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and LEFT(kd_skpd,17)=LEFT('$lcskpd',17))
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and LEFT(b.kd_skpd,17)=LEFT('$lcskpd',17)))z
               ORDER BY tgl_kas,kd_skpd,cast (no_kas as int),jns_trans,st,rekening";
           
           }
           
                            
                    $hasil = $this->db->query($sql);
                    $lcno = 0;
                    $lcterima = 0;
                    $lcterima_pjk = 0;
                    $lckeluar = 0;
                    $lckeluar_pjk = 0;
                    $lhasil = $saldoawal;
                    $saldolalu=number_format($lhasil,"2",",",".");
                    $cRet .= "<tr>
                                <td valign='top' align='center' width='5%' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'>
                              </td>";
                    $cRet .="<td valign='top' align='center' width='9%' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>
                             <td valign='top' colspan='1' width='10%' align='center' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>
                             <td valign='top' colspan='9' width='17%' align='center' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>
                             <td valign='top' align='left' width='7%' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'>Saldo Lalu</td>";
                    $cRet .="<td valign='top' width='15%' align='right' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>";
                    $cRet .="<td valign='top' width='12%' align='right' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>";                                 
                    $cRet .="<td valign='top' width='12%' align='right' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>";
                    $cRet .="<td valign='top' width='12%' align='right' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'>$saldolalu</td>
                                 </tr>";
                    foreach ($hasil->result() as $row)
                    {
                        $cRet .="<tr>";
                        $lhasil = $lhasil + $row->terima - $row->keluar;
                       if(!empty($row->tanggal)){
                         $a=$row->tanggal;
                         $jaka=$this->tukd_model->tanggal_ind($a);
                         $lcno = $lcno + 1;
                         $no_bku = $row->no_kas;
                         $bidang = $row->kd_skpd;
                         $cRet .= "<td valign='top' align='center' style='font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray'>$no_bku</td>
                                    <td valign='top' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>$jaka</td>
                                    <td valign='top' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>$bidang</td>
                                    <td valign='top' colspan='9' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".($row->kegiatan)."</td>
                                    <td valign='top' colspan='1'align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".($row->rekening)."</td>                
                                <td valign='top' align='left' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>$row->uraian</td>
                                ";
                                 if(empty($row->terima) or ($row->terima)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'></td>";
                                }else{
                                    
                                    if($row->jns_trans=='3'){
                                        $lcterima_pjk = $lcterima_pjk + $row->terima;
                                    }else{
                                        $lcterima = $lcterima + $row->terima; 
                                    }                                    
                                    
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".number_format($row->terima,"2",",",".")."</td>";
                                }
                                                                
                                if(empty($row->keluar) or ($row->keluar)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'></td>";
                                }else{
                                    
                                    if($row->jns_trans=='4'){
                                        $lckeluar_pjk = $lckeluar_pjk + $row->keluar;
                                    }else{
                                        $lckeluar = $lckeluar + $row->keluar; 
                                    }
                                    
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".number_format($row->keluar,"2",",",".")."</td>";
                                }
                                if(empty($row->terima) and empty($row->keluar) or ($row->terima)==0 and ($row->keluar)==0){
                                $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'></td>";
                                }else{
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>".number_format($lhasil,"2",",",".")."</td>";
                                }
                       }else{
                        $cRet .= " <td valign='top' align='center' style='font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray'>&nbsp;</td>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>&nbsp;</td>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>&nbsp;</td>
                                <td valign='top' colspan='9' align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".($row->kegiatan)."</td>
                                <td valign='top' colspan='1'align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".($row->rekening)."</td>                
                                <td valign='top' align='left' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>$row->uraian</td>
                                ";
                                 if(empty($row->terima) or ($row->terima)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'></td>";
                                }else{                                   
                                    
                                    if($row->jns_trans=='3'){
                                        $lcterima_pjk = $lcterima_pjk + $row->terima;
                                    }else{
                                        $lcterima = $lcterima + $row->terima; 
                                    }
                                    
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".number_format($row->terima,"2",",",".")."</td>";
                                }
                                if(empty($row->keluar) or ($row->keluar)==0){
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'></td>";
                                }else{
                                    
                                    if($row->jns_trans=='4'){
                                        $lckeluar_pjk = $lckeluar_pjk + $row->keluar;
                                    }else{
                                        $lckeluar = $lckeluar + $row->keluar; 
                                    }
                                                                        
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".number_format($row->keluar,"2",",",".")."</td>";
                                }
                                if(empty($row->terima) and empty($row->keluar) or ($row->terima)==0 and ($row->keluar)==0){
                                $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'></td>";
                                }else{
                                    $cRet .="<td valign='top' align='right' style='font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray'>".number_format($lhasil,"2",",",".")."</td>";
                                }
                       }
                            $cRet .="</tr>";    
                    }

        $cRet .="<tr>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' colspan='9' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;border-top:solid 1px black;'>&nbsp;</td>
                 </tr>";
               
                 if ($pilih==1){                 
                 $csql="SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas < '$lctgl1'  and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
                 }else{
                    $csql="SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) < '$bulan' and year(a.tgl_kas) = $thn_ang and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
                 }
                        
                 $hasil = $this->db->query($csql);
                 $trh1 = $hasil->row(); 
                 
                if ($pilih==1){                 
                 $csql="SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas = '$lctgl1'  and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
                 }else{
                    $csql="SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) = '$bulan' and year(a.tgl_kas) = $thn_ang and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
                 }
                        
                 $hasil_ini = $this->db->query($csql);
                 $trh1_ini = $hasil_ini->row(); 
                 
        /*$trh1->jmterima+$lcterima-$trh1->jmkeluar-$lckeluar+$tox-$saldobank-$saldoberharga*/         
                 
        $cRet .="<tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>Kas di Bendahara Pengeluaran $lcperiode2 </td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'>".number_format(($trh1->jmterima+$lcterima+$lcterima_pjk),"2",",",".")."</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'>".number_format(($trh1->jmkeluar+$lckeluar+$lckeluar_pjk+$tox),"2",",",".")."</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'>".number_format(($trh1->jmterima+$lcterima+$lcterima_pjk-$trh1->jmkeluar-$lckeluar-$lckeluar_pjk+$tox),"2",",",".")."</td>
                 </tr>
        
                    <tr>
                    <td colspan='3' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan ='14'valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr> 
                 <tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($xhasil_tunai),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>               
                 <tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($saldobank),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($saldoberharga),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign='top' align='right' style='font-size:12px;border: solid 1px white;'><b>Rp  ".number_format(($sisa_pajakk),"2",",",".")."</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan='13' valign='top' align='left' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td valign='top' align='center' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                 </tr>
                 <tr>
                    <td align='center' colspan='13' style='font-size:12px;border: solid 1px white;'>
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign='top' align='center' colspan='4' style='font-size:12px;border: solid 1px white;'>
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>
            
        </table>";
        

        
        $print = $this->uri->segment(3);
        
 
         $data['prev']= $cRet;    
          switch($print) {
        case 0;
         echo ("<title>BKU</title>");
         echo $cRet;
        break;
        case 1;
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,''); 
        break;
        
         case 2;
             //$this->_mpdf('',$cRet,10,10,10,'1');
            $this->master_pdf->_mpdf_down2('BKU',$skpx,$cRet,10,10,10,'1');
        break;
        case 3;
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= BKU".$kd.".xls");
            $this->load->view('anggaran/rka/perkadaII', $data);         
        break;
        } 
     
     }


    function cetak_dth($lcskpd='',$nbulan='',$ctk=''){
        $nomor = str_replace('123456789',' ',$this->uri->segment(6));
        $nip2 = str_replace('123456789',' ',$this->uri->segment(7));
        $tanggal_ttd = $this->support->tanggal_format_indonesia($this->uri->segment(8));
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);
        $jns_bp = $this->uri->segment(13);
        $lcskpdd = substr($lcskpd,0,17).".0000";        
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }

            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$nip2'";
                         
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
                if($jns_bp=="bpp"){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$nomor'";
                }else{
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                }
                $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        
            $cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.$prov.' </TD>
                    </TR>
                    <tr></tr>
                    <TR>
                        <TD align="center" ><b>DAFTAR TRANSAKSI HARIAN BELANJA DAERAH (DTH) <br>
                                            BULAN '.strtoupper($this->support->getBulan($nbulan)).'</TD>
                    </TR>
                    </TABLE><br/>';

            $cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%">
                     <TR>
                        <TD align="left" width="20%" >SKPD</TD>
                        <TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
                     </TR>';
            if($jns_bp=="bpp"){         
            $cRet .='<TR>
                        <TD align="left">Bendahara Pengeluaran Pembantu</TD>
                        <TD align="left">: '.$nama1.'</TD>
            </TR>';}else{
            $cRet .='<TR>
                        <TD align="left">Bendahara Pengeluaran</TD>
                        <TD align="left">: '.$nama1.'</TD>
            </TR>'; 
            }
            $cRet .='        </TABLE>';

            $cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="2" cellpadding="2" align="center">
                     <thead>
                     <TR>
                        <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >No.</TD>
                        <TD colspan="2" width="90"  bgcolor="#CCCCCC" align="center" >SPM/SPD</TD>
                        <TD colspan="2" width="150"  bgcolor="#CCCCCC" align="center" >SP2D </TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Akun Belanja</TD>
                        <TD colspan="3" width="150" bgcolor="#CCCCCC" align="center" >Potongan Pajak</TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >NPWP</TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Nama Rekanan</TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Ket</TD>
                        <TD rowspan="2" width="50" bgcolor="#CCCCCC" align="center" >NTPN</TD>
                     </TR>
                     <TR>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" >No. SPM</TD>
                        <TD width="150"  bgcolor="#CCCCCC" align="center" >Nilai Belanja(Rp)</TD>                       
                        <TD width="150"  bgcolor="#CCCCCC" align="center" >No. SP2D </TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >Nilai Belanja (Rp)</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >Akun Potongan</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >Jenis</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >jumlah (Rp)</TD>
                     </TR>
                     </thead>
                     ';
            
                //$par_rek_pot = "('2110101','2110201','2110301','2110302','2110303','2110304','2110305','2110401','2110501','2110601','2110701','2110702','2110801','2110802')";
                //$par_rek_pot = "('2110901','2110101','2110201','2110301','2110501','2110601','2110701','2110801','2130101','2130201','2130301','2130401','2130501')";
             //   $par_rek_pot = "('2130101','2130201','2130301','2130401','2130501','4110707')";
                 $par_rek_pot = "('210105010001','210105020001','210105030001','210106010001')"; 
                $query = $this->db->query("SELECT 1 urut, c.no_spm,c.nilai,c.no_sp2d,c.nilai as nilai_belanja,'' no_bukti,'' kode_belanja,
                    '' as kd_rek5,'' as jenis_pajak,0 as nilai_pot,'' as npwp,
                    '' as nmrekan,z.banyak, ''ket,c.jns_spp, '' as no_nnt 
                    FROM trhstrpot a  
                    INNER JOIN trdstrpot b 
                    ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    LEFT JOIN trhsp2d c 
                    ON a.no_sp2d=c.no_sp2d
                    LEFT JOIN 
                    (SELECT b.kd_skpd, a.no_sp2d, SUM(a.nilai) as nil_trans FROM trdtransout a 
                    INNER JOIN trhtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
                    WHERE b.kd_skpd='$lcskpd'
                    GROUP BY b.kd_skpd, a.no_sp2d) x
                    ON a.no_sp2d=x.no_sp2d
                    LEFT JOIN 
                    (SELECT b.kd_skpd,b.no_sp2d, COUNT(b.no_sp2d) as banyak
                    FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
                    WHERE b.kd_skpd = '$lcskpd' AND month(b.tgl_bukti)='$nbulan' 
                    AND RTRIM(a.kd_rek6) IN $par_rek_pot
                    GROUP BY b.kd_skpd,b.no_sp2d)z 
                    ON a.no_sp2d=z.no_sp2d
                    WHERE a.kd_skpd = '$lcskpd' AND month(a.tgl_bukti)='$nbulan'
                    AND b.kd_rek6 IN $par_rek_pot
                    GROUP BY c.no_spm,c.nilai,c.no_sp2d,c.nilai,z.banyak,c.jns_spp
                    UNION ALL
                    SELECT 2 as urut, '' as no_spm,0 as nilai,b.no_sp2d as no_sp2d,0 as nilai_belanja,
                    a.no_bukti, kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6),'' as jenis_pajak,a.nilai as nilai_pot,npwp,
                    nmrekan,0 banyak, 'No Set: '+a.no_bukti as ket,'' jns_spp, a.ntpn as no_nnt 
                    FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
                    WHERE b.kd_skpd = '$lcskpd' AND month(b.tgl_bukti)='$nbulan'
                    AND RTRIM(a.kd_rek6) IN $par_rek_pot
                    ORDER BY no_sp2d,urut,no_spm,kode_belanja, kd_rek5");  
                $lcno=0;
                $tot_nilai=0;
                $tot_nilai_belanja=0;
                $tot_nilai_pot=0;
                foreach ($query->result() as $row) {
                    $no_spm = $row->no_spm; 
                    $nilai = $row->nilai;    
                    $nilai_belanja =$row->nilai_belanja;
                    $no_sp2d = $row->no_sp2d;
                    $jns_spp = $row->jns_spp;
                    if($jns_spp=='2'){
                    $nilai_belanja =$nilai; 
                    }
                    $kode_belanja=$row->kode_belanja;
                    $kd_rek5 = $row->kd_rek5;
                    $jenis_pajak = $row->jenis_pajak;
                    $nilai_pot = $row->nilai_pot;
                    $npwp = $row->npwp;
                    $nmrekan  = $row->nmrekan;
                    $ket  = $row->ket;
                    $no_nnt  = $row->no_nnt;
                    $banyak  = ($row->banyak)+1;
                    if (($row->urut)==1){
                           $lcno = $lcno + 1;
                       } 
                    
                    if($kd_rek5=='210106010001'){
                        $kd_rek5='210106010001';
                        $jenis_pajak='PPn';
                    }
                    if($kd_rek5=='210105010001'){
                        $kd_rek5='210105010001';
                        $jenis_pajak='PPh 21';
                    }
                    if($kd_rek5=='210105020001'){
                        $kd_rek5='210105020001';
                        $jenis_pajak='PPh 22';
                    }
                    if($kd_rek5=='210105010001'){
                        $kd_rek5='210105010001';
                        $jenis_pajak='PPh 23';
                    }
                    if($kd_rek5=='2130501'){
                        $kd_rek5='411128';
                        $jenis_pajak='PPh 4';
                    }
                    if($kd_rek5=='2130601'){
                        $kd_rek5='411128';
                        $jenis_pajak='PPh 4 Ayat (2)';
                    }
                    if (($row->urut)==1){
                            $cRet.='<TR>
                                <TD width="80" valign="top" align="center">'.$lcno.'</TD>
                                <TD width="90" valign="top" >'.$no_spm.'</TD>
                                <TD width="150" valign="top" align="right" >'.number_format($nilai,'2','.',',').'</TD>                              
                                <TD width="150" valign="top" >'.$no_sp2d.'</TD>
                                <TD width="150" valign="top" align="right" >'.number_format($nilai_belanja,'2','.',',').'</TD>
                                <TD width="150" align="right" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>         
                                <TD width="150" align="right" ></TD>                    
                             </TR>';    
                        } else{
                            $cRet.='<TR>
                                <TD width="150" align="right" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$kode_belanja.'</TD>
                                <TD width="150" valign="top" align="center"  style="border-top:hidden;">'.$kd_rek5.'</TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$jenis_pajak.'</TD>
                                <TD width="150" valign="top" align="right" style="border-top:hidden;" >'.number_format($nilai_pot,'2','.',',').'</TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$npwp.'</TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$nmrekan.'</TD>
                                <TD style="border-top:hidden;" width="150" valign="top" align="left" >'.$ket.'</TD>
                                <TD style="border-top:hidden;" width="50" valign="top" align="left" >'.$no_nnt.'</TD>
                             </TR>';                            
                        }
                $tot_nilai=$tot_nilai+$nilai;
                $tot_nilai_belanja=$tot_nilai_belanja+$nilai_belanja;
                $tot_nilai_pot=$tot_nilai_pot+$nilai_pot;
                }
            $cRet .='<TR>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" >Total</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" >'.$lcno.'</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="right" >'.number_format($tot_nilai,'2','.',',').'</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="right" >'.number_format($tot_nilai_belanja,'2','.',',').'</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150"  bgcolor="#CCCCCC" align="center" ></TD>                        
                        <TD width="150"  bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150" bgcolor="#CCCCCC" align="right" >'.number_format($tot_nilai_pot,'2','.',',').'</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="50" bgcolor="#CCCCCC" align="center" ></TD>
                     </TR>';
            

            $cRet .='</TABLE>';
            
                $cRet .='<TABLE style="font-size:14px;" width="100%" align="center">
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >Mengetahui,</TD>
                        <TD width="50%" align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$jabatan.'</TD>
                        <TD width="50%" align="center" >'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                     <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
                        <TD width="50%" align="center" ><b><u>'.$nama1.'</u></b><br>'.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$nip.'</TD>
                        <TD width="50%" align="center" >'.$nip1.'</TD>
                    </TR>
                    </TABLE><br/>';

            
            $data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
            echo ("<title>DTH</title>");
                echo $cRet;
                break;
            case 1;
                //$this->_mpdf('',$cRet,10,10,10,10,1,'');
                $this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
               //$this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
               break;
        }
    }
    
    function cetak_dth_global($lcskpd='',$nbulan='',$ctk=''){
        $nomor = str_replace('123456789',' ',$this->uri->segment(6));
        $nip2 = str_replace('123456789',' ',$this->uri->segment(7));
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(8));
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);
        $jns_bp = $this->uri->segment(13);
        $lcskpdd = substr($lcskpd,0,17).".0000";        
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        if($jns_bp=="bpp"){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$nip2' ";
        }else{
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$nip2'";
        }        
                         
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        
            $cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.$prov.' </TD>
                    </TR>
                    <tr></tr>
                    <TR>
                        <TD align="center" ><b>DAFTAR TRANSAKSI HARIAN BELANJA DAERAH (DTH) <br>
                                            BULAN '.strtoupper($this->support->getBulan($nbulan)).'</TD>
                    </TR>
                    </TABLE><br/>';

            $cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%">
                     <TR>
                        <TD align="left" width="20%" >SKPD</TD>
                        <TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
                     </TR>';
            if($jns_bp=="bpp"){         
            $cRet .='<TR>
                        <TD align="left">Bendahara Pengeluaran Pembantu</TD>
                        <TD align="left">: '.$nama.'</TD>
            </TR>';}else{
            $cRet .='<TR>
                        <TD align="left">Kepala SKPD</TD>
                        <TD align="left">: '.$nama.'</TD>
            </TR>'; 
            }
            $cRet .='        </TABLE>';

            $cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="2" cellpadding="2" align="center">
                     <thead>
                     <TR>
                        <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >No.</TD>
                        <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >SKPD</TD>
                        <TD colspan="2" width="90"  bgcolor="#CCCCCC" align="center" >SPM/SPD</TD>
                        <TD colspan="2" width="150"  bgcolor="#CCCCCC" align="center" >SP2D </TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Akun Belanja</TD>
                        <TD colspan="3" width="150" bgcolor="#CCCCCC" align="center" >Potongan Pajak</TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >NPWP</TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Nama Rekanan</TD>
                        <TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Ket</TD>
                        <TD rowspan="2" width="50" bgcolor="#CCCCCC" align="center" >NTPN</TD>
                     </TR>
                     <TR>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" >No. SPM</TD>
                        <TD width="150"  bgcolor="#CCCCCC" align="center" >Nilai Belanja(Rp)</TD>                       
                        <TD width="150"  bgcolor="#CCCCCC" align="center" >No. SP2D </TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >Nilai Belanja (Rp)</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >Akun Potongan</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >Jenis</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" >jumlah (Rp)</TD>
                     </TR>
                     </thead>
                     ';
            
                //$par_rek_pot = "('2110101','2110201','2110301','2110302','2110303','2110304','2110305','2110401','2110501','2110601','2110701','2110702','2110801','2110802')";
                //$par_rek_pot = "('2110901','2110101','2110201','2110301','2110501','2110601','2110701','2110801','2130101','2130201','2130301','2130401','2130501')";
               // $par_rek_pot = "('2130101','2130201','2130301','2130401','2130501','4110707')";
                                $par_rek_pot = "('210105010001','210105020001','210105030001','210106010001')";
                $query = $this->db->query("SELECT 1 urut, z.kd_skpd,c.no_spm,c.nilai,c.no_sp2d,c.nilai as nilai_belanja,'' no_bukti,'' kode_belanja,
                    '' as kd_rek6,'' as jenis_pajak,0 as nilai_pot,'' as npwp,
                    '' as nmrekan,z.banyak, ''ket,c.jns_spp, '' as no_nnt 
                    FROM trhstrpot a  
                    INNER JOIN trdstrpot b 
                    ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    LEFT JOIN trhsp2d c 
                    ON a.no_sp2d=c.no_sp2d
                    LEFT JOIN 
                    (SELECT b.kd_skpd, a.no_sp2d, SUM(a.nilai) as nil_trans FROM trdtransout a 
                    INNER JOIN trhtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
                    WHERE b.kd_skpd='$lcskpd'
                    GROUP BY b.kd_skpd, a.no_sp2d) x
                    ON a.no_sp2d=x.no_sp2d
                    LEFT JOIN 
                    (SELECT b.kd_skpd,b.no_sp2d, COUNT(b.no_sp2d) as banyak
                    FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
                    WHERE left(b.kd_skpd,17) = left('$lcskpd',17) AND month(b.tgl_bukti)='$nbulan' 
                    AND RTRIM(a.kd_rek6) IN $par_rek_pot
                    GROUP BY b.kd_skpd,b.no_sp2d) z 
                    ON a.no_sp2d=z.no_sp2d
                    WHERE left(a.kd_skpd,17) = left('$lcskpd',17) AND month(a.tgl_bukti)='$nbulan'
                    AND b.kd_rek6 IN $par_rek_pot
                    GROUP BY z.kd_skpd,c.no_spm,c.nilai,c.no_sp2d,c.nilai,z.banyak,c.jns_spp
                    UNION ALL
                    SELECT 2 as urut, a.kd_skpd, '' as no_spm,0 as nilai,b.no_sp2d as no_sp2d,0 as nilai_belanja,
                    a.no_bukti, kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6),'' as jenis_pajak,a.nilai as nilai_pot,npwp,
                    nmrekan,0 banyak, 'No Set: '+a.no_bukti as ket,'' jns_spp, a.ntpn 
                    FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
                    WHERE left(b.kd_skpd,17) = left('$lcskpd',17) AND month(b.tgl_bukti)='$nbulan'
                    AND RTRIM(a.kd_rek6) IN $par_rek_pot
                    ORDER BY no_sp2d,kd_skpd,urut,kode_belanja,kd_rek6");  
                $lcno=0;
                $tot_nilai=0;
                $tot_nilai_belanja=0;
                $tot_nilai_pot=0;
                foreach ($query->result() as $row) {
                    $no_spm = $row->no_spm; 
                    $nilai = $row->nilai;    
                    $nilai_belanja =$row->nilai_belanja;
                    $no_sp2d = $row->no_sp2d;
                    $jns_spp = $row->jns_spp;
                    if($jns_spp=='2'){
                    $nilai_belanja =$nilai; 
                    }
                    $kode_belanja=$row->kode_belanja;
                    $kd_rek5 = $row->kd_rek6;
                    $jenis_pajak = $row->jenis_pajak;
                    $nilai_pot = $row->nilai_pot;
                    $npwp = $row->npwp;
                    $nmrekan  = $row->nmrekan;
                    $ket  = $row->ket;
                    $kd_skpdd = $row->kd_skpd;
                    $no_nnt  = $row->no_nnt;
                    $banyak  = ($row->banyak)+1;
                    if (($row->urut)==1){
                           $lcno = $lcno + 1;
                       } 
                    
                    if($kd_rek5=='210106010001'){
                        $kd_rek5='210106010001';
                        $jenis_pajak='PPn';
                    }
                    if($kd_rek5=='210105010001'){
                        $kd_rek5='210105010001';
                        $jenis_pajak='PPh 21';
                    }
                    if($kd_rek5=='210105020001'){
                        $kd_rek5='210105020001';
                        $jenis_pajak='PPh 22';
                    }
                    if($kd_rek5=='210105010001'){
                        $kd_rek5='210105010001';
                        $jenis_pajak='PPh 23';
                    }
                    if($kd_rek5=='2130501'){
                        $kd_rek5='411128';
                        $jenis_pajak='PPh 4';
                    }
                    if($kd_rek5=='2130601'){
                        $kd_rek5='411128';
                        $jenis_pajak='PPh 4 Ayat (2)';
                    }
                    if (($row->urut)==1){
                            $cRet.='<TR>
                                <TD width="80" valign="top" align="center">'.$lcno.'</TD>
                                <TD width="80" valign="top" align="center">'.$kd_skpdd.'</TD>
                                <TD width="90" valign="top" >'.$no_spm.'</TD>
                                <TD width="150" valign="top" align="right" >'.number_format($nilai,'2','.',',').'</TD>                              
                                <TD width="150" valign="top" >'.$no_sp2d.'</TD>
                                <TD width="150" valign="top" align="right" >'.number_format($nilai_belanja,'2','.',',').'</TD>
                                <TD width="150" align="right" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>
                                <TD width="150" align="left" ></TD>         
                                <TD width="150" align="right" ></TD>                    
                             </TR>';    
                        } else{
                            $cRet.='<TR>
                                <TD width="150" align="right" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" align="right" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$kode_belanja.'</TD>
                                <TD width="150" valign="top" align="center"  style="border-top:hidden;">'.$kd_rek5.'</TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$jenis_pajak.'</TD>
                                <TD width="150" valign="top" align="right" style="border-top:hidden;" >'.number_format($nilai_pot,'2','.',',').'</TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$npwp.'</TD>
                                <TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$nmrekan.'</TD>
                                <TD style="border-top:hidden;" width="150" valign="top" align="left" >'.$ket.'</TD>
                                <TD style="border-top:hidden;" width="50" valign="top" align="left" >'.$no_nnt.'</TD>
                             </TR>';                            
                        }
                //$tot_nilai=$tot_nilai+$nilai;
                //$tot_nilai_belanja=$tot_nilai_belanja+$nilai_belanja;
                $tot_nilai_pot=$tot_nilai_pot+$nilai_pot;
                }
                
                $sql_sp2d = $this->db->query("SELECT sum(x.nilai) as nilai_belanja from (
                SELECT b.no_sp2d,(select sum(nilai) from trhsp2d where no_sp2d=b.no_sp2d) as nilai
                FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
                WHERE month(b.tgl_bukti)='$nbulan'
                AND RTRIM(a.kd_rek6) IN $par_rek_pot
                and left(a.kd_skpd,17)=left('$lcskpd',17)
                group by b.no_sp2d)x")->row();
                $nilai_tot_sp2d=$sql_sp2d->nilai_belanja;
                
            $cRet .='<TR>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" >Total</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>                        
                        <TD width="50" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="right" >'.number_format($nilai_tot_sp2d,'2','.',',').'</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="right" >'.number_format($nilai_tot_sp2d,'2','.',',').'</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150"  bgcolor="#CCCCCC" align="center" ></TD>                        
                        <TD width="150"  bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150" bgcolor="#CCCCCC" align="right" >'.number_format($tot_nilai_pot,'2','.',',').'</TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="50" bgcolor="#CCCCCC" align="center" ></TD>
                     </TR>';
            

            $cRet .='</TABLE>';
            if($jns_bp=="bpp"){ 
            $cRet .='<TABLE style="font-size:14px;" width="100%" align="center">
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >Mengetahui,</TD>
                        <TD width="50%" align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$jabatan1.'</TD>
                        <TD width="50%" align="center" >'.$jabatan.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                     <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b><u>'.$nama1.'</u></b><br>'.$pangkat1.'</TD>
                        <TD width="50%" align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$nip1.'</TD>
                        <TD width="50%" align="center" >'.$nip.'</TD>
                    </TR>
                    </TABLE><br/>';
            }else{
                $cRet .='<TABLE style="font-size:14px;" width="100%" align="center">
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >Mengetahui,</TD>
                        <TD width="50%" align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$jabatan.'</TD>
                        <TD width="50%" align="center" >'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                     <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
                        <TD width="50%" align="center" ><b><u>'.$nama1.'</u></b><br>'.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$nip.'</TD>
                        <TD width="50%" align="center" >'.$nip1.'</TD>
                    </TR>
                    </TABLE><br/>';

            }
            $data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
            echo ("<title>DTH</title>");
                echo $cRet;
                break;
            case 1;
                //$this->_mpdf('',$cRet,10,10,10,10,1,'');
                $this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
               //$this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
               break;
        }
    }

    function cetak_rincian_objek($dcetak='',$ttd1='',$skpd='',$rek5='',$dcetak2='',$giat='',$tgl_ctk='',$ttd2='',$ctk=''){
             $spasi = $this->uri->segment(12);
             $jns_bp = $this->uri->segment(13);
           $ttd1 = str_replace('123456789',' ',$ttd1);
            $ttd2 = str_replace('123456789',' ',$ttd2);
            $skpdd = substr($skpd,0,17).".0000";
            $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $nm_prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
                 
                     $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd2'";
                 
        
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }

                if($jns_bp=="bpp"){
                    $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd1'";    
                 }else{
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd1'";
                }
                $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
           if($giat<>''){
                $keg='1';
                $giat=$giat;
                $nm_giat=$this->tukd_model->get_nama($giat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');
                //$nm_prov=$this->tukd_model->get_sclient('provinsi','sclient');
                }else{
                $keg='0';
                $giat='';
                $nm_giat='KESELURUHAN';
            }
                        
            //echo $dcetak .'/'. $ttd.'/'.$skpd.'/'.$rek5.'/'.$dcetak2.'/'.$giat.'/'.$keg;
        

            $cRet ='<TABLE width="100%">
                     <TR>                        
                        <TD colspan="2" align="center" ><b>'.$nm_prov.'</TD>                    
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" ><b>BUKU PEMBANTU RINCIAN OBJEK </TD>                    
                     </TR>
                     </TABLE>
                     <TABLE style="font-size:12px" width="100%">
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp; </TD>                    
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp;</TD>                 
                     </TR>
                     <TR>                        
                        <TD align="left" width="15%" >SKPD </TD>
                        <TD align="left" width="85%" >: '.$skpd.' '.$this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd').'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Kegiatan</TD>
                        <TD align="left" width="85%" >: '.$giat.' '.$nm_giat.'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Rekening </TD>
                        <TD align="left" width="85%" >: '.$rek5.' '.$this->tukd_model->get_nama($rek5,'nm_rek6','ms_rek6','kd_rek6').'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Periode</TD>
                        <TD align="left" width="85%" >: '.$this->support->tanggal_format_indonesia($dcetak).' s/d '.$this->support->tanggal_format_indonesia($dcetak2).'</TD>
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp;</TD>                 
                     </TR>
                     </TABLE>';

            $cRet .='<TABLE style="border-collapse:collapse;font-size:12px" border="1" cellspacing="0" cellpadding="'.$spasi.'" width="100%" >
                    <THEAD>
                     <TR>
                        <TD width="40%" rowspan="2" colspan="2"  align="center" ><b>Nomor dan Tanggal BKU</b></TD>
                        <TD width="60%" colspan="4"  align="center" ><b>Pengeluaran (Rp)</b></TD>                   
                     </TR>
                     <TR>
                        <TD width="15%" align="center"><b>LS</b></TD>
                        <TD width="15%"  align="center"><b>UP/GU</b></TD>
                        <TD width="15%"  align="center"><b>TU</b></TD>
                        <TD width="15%"  align="center"><b>JUMLAH</b></TD>
                     </TR>
                     </THEAD>';    
                   
                    $query = $this->db->query("SELECT ISNULL(a.no_bukti,'') as no_bukti
                                                ,b.tgl_bukti
                                                ,ISNULL(a.no_sp2d,'') as no_sp2d
                                                ,SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS up
                                                ,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS gu
                                                ,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS ls
                                                FROM trdtransout a 
                                                LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                                                WHERE a.kd_sub_kegiatan='$giat' 
                                                and a.kd_rek6='$rek5' 
                                                AND b.kd_skpd='$skpd' 
                                                and b.tgl_bukti>='$dcetak' 
                                                and b.tgl_bukti<='$dcetak2' 
                                                GROUP BY a.no_bukti, b.tgl_bukti,a.no_sp2d
                                                ORDER BY cast(a.no_bukti as int), b.tgl_bukti
                                                ");
                $i=0;
                $jumls=0;
                $jumup=0;
                $jumgu=0;
                $jml=0;  
                foreach($query->result_array() as $res){                    
                                    $cetak[1] = empty($res['no_bukti']) || $res['no_bukti']== null ?'&nbsp;' :$res['no_bukti'];
                                    $cetak[2] = empty($res['no_sp2d']) || $res['no_sp2d']== null ?'&nbsp;' :$res['no_sp2d'];
                                    $cetak[3] = empty($res['ls']) || $res['ls'] == null ?'&nbsp;' :$res['ls'];
                                    $cetak[4] = empty($res['up']) || $res['up'] == null ?0 :$res['up'];
                                    $cetak[5] = empty($res['gu']) || $res['gu'] == null ?0 :$res['gu'];
                                    $cetak[6] = empty($res['tgl_bukti']) || $res['tgl_bukti'] == null ?0 :$res['tgl_bukti'];
                        $cRet .='<tr>
                                    <td style="border-bottom:hidden;border-right:hidden;" align="left" ><b>&nbsp;'.$cetak[1].'</b> </td>
                                    <td style="border-bottom:hidden;border-left:hidden;" align="right" >'.$this->tukd_model->tanggal_format_indonesia($cetak[6]).'&nbsp;</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[3],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[4],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[5],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[3]+$cetak[4]+$cetak[5],"2",",",".").'</td></tr>
                                 <tr>
                                    <td colspan="2" align="left" ><i>&nbsp;SP2D: '.$cetak[2].'</i> </td>
                                    
                                 </tr>';
                                     
                $jumls=$jumls+$cetak[3];
                $jumup=$jumup+$cetak[4];
                $jumgu=$jumgu+$cetak[5];
                $jml=$jml+$cetak[3]+$cetak[4]+$cetak[5];        
                        
                        
                }  
               
               
                $cRet .='<TR>               
                    <TD colspan="2" align="left" ><i><b>Jumlah</i></b></TD>
                    <TD align="right" ><b>'.number_format($jumls,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jumup,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jumgu,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jml,"2",",",".").'</b></TD>                  
                 </TR>';
                 
                $query = $this->db->query("SELECT SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS lalu_up
                                                ,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS lalu_gu
                                                ,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS lalu_ls
                                                FROM trdtransout a 
                                                LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                                                WHERE a.kd_sub_kegiatan='$giat' 
                                                and a.kd_rek6='$rek5' 
                                                AND b.kd_skpd='$skpd' 
                                                and b.tgl_bukti<'$dcetak' 
                                                ");
                foreach($query->result_array() as $res){                                        
                    $lalu_up=$res['lalu_up'];
                    $lalu_gu=$res['lalu_gu'];
                    $lalu_ls=$res['lalu_ls'];
                }
                $jml_lalu=$lalu_up+$lalu_gu+$lalu_ls;
                $tot=$jumup+$lalu_up;
                $tot1=$jumgu+$lalu_gu;
                $tot2=$jumls+$lalu_ls;
                $total=$tot+$tot1+$tot2;                                
                $cRet .='<TR>               
                    <TD colspan="2" align="left" ><i><b>Jumlah s/d periode lalu </i></b></TD>
                    <TD align="right" ><b>'.number_format($lalu_ls,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($lalu_up,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($lalu_gu,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jml_lalu,"2",",",".").'</b></TD>                 
                 </TR>';
                 $cRet .='<TR>              
                    <TD colspan="2" align="left" ><b><i>Jumlah s/d periode ini<i></b></TD>
                    <TD align="right" ><b>'.number_format($tot2,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($tot,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($tot1,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($total,"2",",",".").'</b></TD>                    
                 </TR>';
            $cRet .='</TABLE>';
            
            
                $cRet .='<TABLE style="font-size:12px" width="100%" border="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">Mengetahui,</TD>
                        <TD align="center" width="50%">'.$daerah.', '.$this->support->tanggal_format_indonesia($tgl_ctk).'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">'.$jabatan.'</TD>
                        <TD align="center" width="50%">'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><u><b>'.$nama.'</b></u><br>'.$pangkat.'</TD>
                        <TD align="center" width="50%"><u><b>'.$nama1.'</b></u><br>'.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">'.$nip.'</TD>
                        <TD align="center" width="50%">'.$nip1.'</TD>
                    </TR>
                    </TABLE><br/>';
            
            $data['prev']= 'RINCIAN OBJEK';
            switch ($ctk) {
                case 0;
                    echo ("<title> BP RINCIAN OBJEK</title>");
                    echo $cRet;
                    break;
                case 1;
                    $this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'P',0,'',15,15,15,15);
                    break;
            }
    } 


         function cetak_rincian_objek_kegiatan($dcetak='',$ttd1='',$skpd='',$dcetak2='',$giat='',$tgl_ctk='',$ttd2='',$ctk=''){
            //$this->load->library('mpdf');
            //$this->mpdf = new mPDF('utf-8', array(215,330),12); //folio

           $spasi = $this->uri->segment(11);
           $jns_bp = $this->uri->segment(12);
           $ttd1 = str_replace('123456789',' ',$ttd1);
            $ttd2 = str_replace('123456789',' ',$ttd2);
            $skpdd = substr($skpd,0,17).".0000";
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$skpdd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $nm_prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
                 
                    $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd2'"; 
                 
        
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        
        if($jns_bp=="bpp"){
                     $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd1'";
                 }else{
                    $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd1'";
                 }
                  $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        $cRet ='<TABLE style="font-size:16px" width="100%" border="0">
                    <TR>
                        <TD align="center" width="100%"><b>'.$nm_prov.'<BR>BUKU PEMBANTU RINCIAN OBJEK<BR><BR>
                        KEGIATAN '.strtoupper($this->tukd_model->get_nama($giat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan')).'
                        </b></TD>
                    </TR>
                </TABLE>';
        
        $sqlww= $this->db->query("SELECT b.kd_rek6 kd_rek5 FROM trhtransout a LEFT JOIN trdtransout b ON a.no_bukti=b.no_bukti
            AND a.kd_skpd = b.kd_skpd
            WHERE a.tgl_bukti<='$dcetak2' 
            AND a.kd_skpd='$skpd' 
            AND b.kd_sub_kegiatan='$giat' 
            GROUP BY b.kd_rek6 ORDER BY b.kd_rek6");
 foreach ($sqlww->result() as $row){
 $rek5=$row->kd_rek5;
            $nm_giat=$this->tukd_model->get_nama($giat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');       
                //$nm_giat=$this->tukd_model->get_nama($giat,'nm_subkegiatan','trskpd','kd_subkegiatan');
                //$nm_prov=$this->tukd_model->get_sclient('provinsi','sclient');
                
            
            //echo $rek5;

        $cRet .='       
        <pagebreak type="NEXT-ODD" resetpagenum="1" pagenumstyle="1" suppress="off" />
        <!--<TABLE width="100%">
                     <TR>                        
                        <TD colspan="2" align="center" ><b>'.$nm_prov.'</TD>                    
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" ><b>BUKU PEMBANTU RINCIAN OBJEK </TD>                    
                     </TR>
                     </TABLE>-->
                     <TABLE style="font-size:12px" width="100%">
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp; </TD>                    
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp;</TD>                 
                     </TR>
                     <TR>                        
                        <TD align="left" width="15%" >SKPD </TD>
                        <TD align="left" width="85%" >: '.$skpd.' '.$this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd').'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Kegiatan</TD>
                        <TD align="left" width="85%" >: '.$giat.' '.$nm_giat.'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Rekening </TD>
                        <TD align="left" width="85%" >: '.$rek5.' '.$this->tukd_model->get_nama($rek5,'nm_rek6','ms_rek6','kd_rek6').'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Periode</TD>
                        <TD align="left" width="85%" >: '.$this->support->tanggal_format_indonesia($dcetak).' s/d '.$this->support->tanggal_format_indonesia($dcetak2).'</TD>
                     </TR>                     
                     </TABLE>';
            $cRet .='<TABLE style="border-collapse:collapse;font-size:12px" border="1" cellspacing="0" cellpadding="'.$spasi.'" width="100%" >
                    <THEAD>
                     <TR>
                        <TD width="40%" rowspan="2" colspan="2"  align="center" ><b>Nomor dan Tanggal BKU</b></TD>
                        <TD width="60%" colspan="4"  align="center" ><b>Pengeluaran (Rp)</b></TD>                   
                     </TR>
                     <TR>
                        <TD width="15%" align="center"><b>LS</b></TD>
                        <TD width="15%"  align="center"><b>UP/GU</b></TD>
                        <TD width="15%"  align="center"><b>TU</b></TD>
                        <TD width="15%"  align="center"><b>JUMLAH</b></TD>
                     </TR>
                     </THEAD>';    
                   
                    $query = $this->db->query("SELECT ISNULL(a.no_bukti,'') as no_bukti
                                                ,b.tgl_bukti
                                                ,ISNULL(a.no_sp2d,'') as no_sp2d
                                                ,SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS up
                                                ,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS gu
                                                ,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS ls
                                                FROM trdtransout a 
                                                LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                                                WHERE a.kd_sub_kegiatan='$giat' 
                                                and a.kd_rek6='$rek5' 
                                                AND b.kd_skpd='$skpd' 
                                                and b.tgl_bukti>='$dcetak' 
                                                and b.tgl_bukti<='$dcetak2' 
                                                GROUP BY a.no_bukti, b.tgl_bukti,a.no_sp2d
                                                ORDER BY b.tgl_bukti,a.no_bukti
                                                ");
                $i=0;
                $jumls=0;
                $jumup=0;
                $jumgu=0;
                $jml=0;  
                foreach($query->result_array() as $res){                    
                                    $cetak[1] = empty($res['no_bukti']) || $res['no_bukti']== null ?'&nbsp;' :$res['no_bukti'];
                                    $cetak[2] = empty($res['no_sp2d']) || $res['no_sp2d']== null ?'&nbsp;' :$res['no_sp2d'];
                                    $cetak[3] = empty($res['ls']) || $res['ls'] == null ?'&nbsp;' :$res['ls'];
                                    $cetak[4] = empty($res['up']) || $res['up'] == null ?0 :$res['up'];
                                    $cetak[5] = empty($res['gu']) || $res['gu'] == null ?0 :$res['gu'];
                                    $cetak[6] = empty($res['tgl_bukti']) || $res['tgl_bukti'] == null ?0 :$res['tgl_bukti'];
                        $cRet .='<tr>
                                    <td style="border-bottom:hidden;border-right:hidden;" align="left" ><b>&nbsp;'.$cetak[1].'</b> </td>
                                    <td style="border-bottom:hidden;border-left:hidden;" align="right" >'.$this->support->tanggal_format_indonesia($cetak[6]).'&nbsp;</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[3],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[4],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[5],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[3]+$cetak[4]+$cetak[5],"2",",",".").'</td></tr>
                                 <tr>
                                    <td colspan="2" align="left" ><i>&nbsp;SP2D: '.$cetak[2].'</i> </td>
                                    
                                 </tr>';
                                     
                $jumls=$jumls+$cetak[3];
                $jumup=$jumup+$cetak[4];
                $jumgu=$jumgu+$cetak[5];
                $jml=$jml+$cetak[3]+$cetak[4]+$cetak[5];        
                        
                        
                }  
               
               
                $cRet .='<TR>               
                    <TD colspan="2" align="left" ><i><b>Jumlah</i></b></TD>
                    <TD align="right" ><b>'.number_format($jumls,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jumup,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jumgu,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jml,"2",",",".").'</b></TD>                  
                 </TR>';
                 
                $query = $this->db->query("SELECT SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS lalu_up
                                                ,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS lalu_gu
                                                ,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS lalu_ls
                                                FROM trdtransout a 
                                                LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                                                WHERE a.kd_sub_kegiatan='$giat' 
                                                and a.kd_rek6='$rek5' 
                                                AND b.kd_skpd='$skpd' 
                                                and b.tgl_bukti<'$dcetak' 
                                                ");
                foreach($query->result_array() as $res){                                        
                    $lalu_up=$res['lalu_up'];
                    $lalu_gu=$res['lalu_gu'];
                    $lalu_ls=$res['lalu_ls'];
                }
                $jml_lalu=$lalu_up+$lalu_gu+$lalu_ls;
                $tot=$jumup+$lalu_up;
                $tot1=$jumgu+$lalu_gu;
                $tot2=$jumls+$lalu_ls;
                $total=$tot+$tot1+$tot2;                                
                $cRet .='<TR>               
                    <TD colspan="2" align="left" ><i><b>Jumlah s/d periode lalu </i></b></TD>
                    <TD align="right" ><b>'.number_format($lalu_ls,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($lalu_up,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($lalu_gu,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jml_lalu,"2",",",".").'</b></TD>                 
                 </TR>';
                 $cRet .='<TR>              
                    <TD colspan="2" align="left" ><b><i>Jumlah s/d periode ini<i></b></TD>
                    <TD align="right" ><b>'.number_format($tot2,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($tot,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($tot1,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($total,"2",",",".").'</b></TD>                    
                 </TR>';
            $cRet .='</TABLE>';
            
            $cRet .='<TABLE style="font-size:12px" width="100%" border="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">Mengetahui,</TD>
                        <TD align="center" width="50%">'.$daerah.', '.$this->support->tanggal_format_indonesia($tgl_ctk).'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">'.$jabatan.'</TD>
                        <TD align="center" width="50%">'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><u><b>'.$nama.'</b></u><br>'.$pangkat.'</TD>
                        <TD align="center" width="50%"><u><b>'.$nama1.'</b></u><br>'.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">'.$nip.'</TD>
                        <TD align="center" width="50%">'.$nip1.'</TD>
                    </TR>
                    </TABLE></pagebreak>';
            
        //tambahkan bila tak ingin menggunakan mpdf
        //$this->mpdf->AddPage('P','',1,'1','off');
        //$this->mpdf->writeHTML($cRet);         
            
            }

            $data['prev']= 'RINCIAN OBJEK';
        //tambahkan bila tak ingin mpdf
        //$this->mpdf->Output();
            switch ($ctk) {
                case 0;
                    echo ("<title> BP RINCIAN OBJEK</title>");
                    echo $cRet;
                    break;
                case 1;
                    //$this->_mpdf('',$cRet,10,10,10,'0',0,'');
                    $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,'');
                    break;
            }
    }
    
    
function cetak_rincian_objek_all($dcetak='',$ttd1='',$skpd='',$dcetak2='',$tgl_ctk='',$ttd2='',$ctk=''){
            //$this->load->library('mpdf');
            //$this->mpdf = new mPDF('utf-8', array(215,330),12); //folio

            $spasi = $this->uri->segment(10);
            $jns_bp = $this->uri->segment(11);
           $ttd1 = str_replace('123456789',' ',$ttd1);
            $ttd2 = str_replace('123456789',' ',$ttd2);
            $skpdd= substr($skpd,0,17).".0000";
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$skpdd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $nm_prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }

                     $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd2'";
        
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
                if($jns_bp=="bpp"){
                    $sqlttd1="SELECT top 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd1'";    
                 }else{
                    $sqlttd1="SELECT top 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd1'";
                }

                $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        $cRet ='<TABLE style="font-size:16px" width="100%" border="0">
                    <TR>
                        <TD align="center" width="100%"><BR><BR><BR><b>CETAK BUKU RINCIAN OBJEK<BR>
                        '.strtoupper($this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd')).'<BR>
                        '.strtoupper($this->support->tanggal_format_indonesia($dcetak)).' s/d '.strtoupper($this->support->tanggal_format_indonesia($dcetak2)).'</TD>
                    </TR>
                </TABLE>';
        
        $sqlww= $this->db->query("SELECT b.kd_sub_kegiatan kd_kegiatan,b.kd_rek6 kd_rek5 FROM trhtransout a LEFT JOIN trdtransout b ON a.no_bukti=b.no_bukti
            AND a.kd_skpd = b.kd_skpd
            WHERE a.tgl_bukti<='$dcetak2' 
            AND a.kd_skpd='$skpd' 
            GROUP BY b.kd_sub_kegiatan,b.kd_rek6 ORDER BY b.kd_sub_kegiatan,b.kd_rek6");
        foreach ($sqlww->result() as $row){
            $giat=$row->kd_kegiatan;
            $rek5=$row->kd_rek5;
            $nm_giat=$this->tukd_model->get_nama($giat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');       
            //$nm_giat=$this->tukd_model->get_nama($giat,'nm_subkegiatan','trskpd','kd_subkegiatan');
            //$nm_prov=$this->tukd_model->get_sclient('provinsi','sclient');
            
            
            //echo $rek5;

        $cRet .='       
        <pagebreak type="NEXT-ODD" resetpagenum="1" pagenumstyle="1" suppress="off" />
        <TABLE width="100%">
                     <TR>                        
                        <TD colspan="2" align="center" ><b>'.$nm_prov.'</TD>                    
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" ><b>BUKU PEMBANTU RINCIAN OBJEK </TD>                    
                     </TR>
                     </TABLE>
                     <TABLE style="font-size:12px" width="100%">
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp; </TD>                    
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp;</TD>                 
                     </TR>
                     <TR>                        
                        <TD align="left" width="15%" >SKPD </TD>
                        <TD align="left" width="85%" >: '.$skpd.' '.$this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd').'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Kegiatan</TD>
                        <TD align="left" width="85%" >: '.$giat.' '.$nm_giat.'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Rekening </TD>
                        <TD align="left" width="85%" >: '.$rek5.' '.$this->tukd_model->get_nama($rek5,'nm_rek6','ms_rek6','kd_rek6').'</TD>
                     </TR>
                     <TR>
                        <TD align="left" width="15%" >Periode</TD>
                        <TD align="left" width="85%" >: '.$this->support->tanggal_format_indonesia($dcetak).' s/d '.$this->support->tanggal_format_indonesia($dcetak2).'</TD>
                     </TR>
                     <TR>                        
                        <TD colspan="2" align="center" >&nbsp;</TD>                 
                     </TR>
                     </TABLE>';
            $cRet .='<TABLE style="border-collapse:collapse;font-size:12px" border="1" cellspacing="0" cellpadding="'.$spasi.'" width="100%" >
                    <THEAD>
                     <TR>
                        <TD width="40%" rowspan="2" colspan="2"  align="center" ><b>Nomor dan Tanggal BKU</b></TD>
                        <TD width="60%" colspan="4"  align="center" ><b>Pengeluaran (Rp)</b></TD>                   
                     </TR>
                     <TR>
                        <TD width="15%" align="center"><b>LS</b></TD>
                        <TD width="15%"  align="center"><b>UP/GU</b></TD>
                        <TD width="15%"  align="center"><b>TU</b></TD>
                        <TD width="15%"  align="center"><b>JUMLAH</b></TD>
                     </TR>
                     </THEAD>';    
                   
                    $query = $this->db->query("SELECT ISNULL(a.no_bukti,'') as no_bukti
                                                ,b.tgl_bukti
                                                ,ISNULL(a.no_sp2d,'') as no_sp2d
                                                ,SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS up
                                                ,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS gu
                                                ,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS ls
                                                FROM trdtransout a 
                                                LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                                                WHERE a.kd_sub_kegiatan='$giat' 
                                                and a.kd_rek6='$rek5' 
                                                AND b.kd_skpd='$skpd' 
                                                and b.tgl_bukti>='$dcetak' 
                                                and b.tgl_bukti<='$dcetak2' 
                                                GROUP BY a.no_bukti, b.tgl_bukti,a.no_sp2d
                                                ORDER BY b.tgl_bukti,a.no_bukti
                                                ");
                $i=0;
                $jumls=0;
                $jumup=0;
                $jumgu=0;
                $jml=0;  
                foreach($query->result_array() as $res){                    
                                    $cetak[1] = empty($res['no_bukti']) || $res['no_bukti']== null ?'&nbsp;' :$res['no_bukti'];
                                    $cetak[2] = empty($res['no_sp2d']) || $res['no_sp2d']== null ?'&nbsp;' :$res['no_sp2d'];
                                    $cetak[3] = empty($res['ls']) || $res['ls'] == null ?'&nbsp;' :$res['ls'];
                                    $cetak[4] = empty($res['up']) || $res['up'] == null ?0 :$res['up'];
                                    $cetak[5] = empty($res['gu']) || $res['gu'] == null ?0 :$res['gu'];
                                    $cetak[6] = empty($res['tgl_bukti']) || $res['tgl_bukti'] == null ?0 :$res['tgl_bukti'];
                        $cRet .='<tr>
                                    <td style="border-bottom:hidden;border-right:hidden;" align="left" ><b>&nbsp;'.$cetak[1].'</b> </td>
                                    <td style="border-bottom:hidden;border-left:hidden;" align="right" >'.$this->support->tanggal_format_indonesia($cetak[6]).'&nbsp;</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[3],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[4],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[5],"2",",",".").'</td>
                                    <td rowspan="2" align="right" >'.number_format($cetak[3]+$cetak[4]+$cetak[5],"2",",",".").'</td></tr>
                                 <tr>
                                    <td colspan="2" align="left" ><i>&nbsp;SP2D: '.$cetak[2].'</i> </td>
                                    
                                 </tr>';
                                     
                $jumls=$jumls+$cetak[3];
                $jumup=$jumup+$cetak[4];
                $jumgu=$jumgu+$cetak[5];
                $jml=$jml+$cetak[3]+$cetak[4]+$cetak[5];        
                        
                        
                }  
               
               
                $cRet .='<TR>               
                    <TD colspan="2" align="left" ><i><b>Jumlah</i></b></TD>
                    <TD align="right" ><b>'.number_format($jumls,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jumup,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jumgu,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jml,"2",",",".").'</b></TD>                  
                 </TR>';
                 
                $query = $this->db->query("SELECT SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS lalu_up
                                                ,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS lalu_gu
                                                ,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS lalu_ls
                                                FROM trdtransout a 
                                                LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                                                WHERE a.kd_sub_kegiatan='$giat' 
                                                and a.kd_rek6='$rek5' 
                                                AND b.kd_skpd='$skpd' 
                                                and b.tgl_bukti<'$dcetak' 
                                                ");
                foreach($query->result_array() as $res){                                        
                    $lalu_up=$res['lalu_up'];
                    $lalu_gu=$res['lalu_gu'];
                    $lalu_ls=$res['lalu_ls'];
                }
                $jml_lalu=$lalu_up+$lalu_gu+$lalu_ls;
                $tot=$jumup+$lalu_up;
                $tot1=$jumgu+$lalu_gu;
                $tot2=$jumls+$lalu_ls;
                $total=$tot+$tot1+$tot2;                                
                $cRet .='<TR>               
                    <TD colspan="2" align="left" ><i><b>Jumlah s/d periode lalu </i></b></TD>
                    <TD align="right" ><b>'.number_format($lalu_ls,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($lalu_up,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($lalu_gu,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($jml_lalu,"2",",",".").'</b></TD>                 
                 </TR>';
                 $cRet .='<TR>              
                    <TD colspan="2" align="left" ><b><i>Jumlah s/d periode ini<i></b></TD>
                    <TD align="right" ><b>'.number_format($tot2,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($tot,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($tot1,"2",",",".").'</b></TD>
                    <TD align="right" ><b>'.number_format($total,"2",",",".").'</b></TD>                    
                 </TR>';
            $cRet .='</TABLE>';
                                        
            $cRet .='<TABLE style="font-size:12px" width="100%" border="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">Mengetahui,</TD>
                        <TD align="center" width="50%">'.$daerah.', '.$this->support->tanggal_format_indonesia($tgl_ctk).'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">'.$jabatan.'</TD>
                        <TD align="center" width="50%">'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%"><u><b>'.$nama.'</b></u><br>'.$pangkat.'</TD>
                        <TD align="center" width="50%"><u><b>'.$nama1.'</b></u><br>'.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">'.$nip.'</TD>
                        <TD align="center" width="50%">'.$nip1.'</TD>
                    </TR>
                    </TABLE></pagebreak>';
            
        //tambahkan bila tak ingin menggunakan mpdf
        //$this->mpdf->AddPage('P','',1,'1','off');
        //$this->mpdf->writeHTML($cRet);         
            
            }

            $data['prev']= 'RINCIAN OBJEK';
        //tambahkan bila tak ingin mpdf
        //$this->mpdf->Output();
            switch ($ctk) {
                case 0;
                    echo ("<title> BP RINCIAN OBJEK</title>");
                    echo $cRet;
                    break;
                case 1;
                    //$this->_mpdf('',$cRet,10,10,10,'0',0,'');
                    $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,'');
                    break;
            }
    }
    

    function cetak_kartu_kendali($lcskpd='',$giat='',$ctk='',$bulan=''){
        $spasi = $this->uri->segment(9);
        $bulan = $this->uri->segment(10);
        $bulanx= $this->support->getBulan($bulan);
        $nomor = str_replace('123456789',' ',$this->uri->segment(6));
        $nip2 = str_replace('123456789',' ',$this->uri->segment(7));
        $tanggal_ttd = $this->support->tanggal_format_indonesia($this->uri->segment(8));
        $nbulan=$this->support->getBulan($this->uri->segment(8));
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$nip2' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        
            $cRet ='<TABLE style="border-collapse:collapse;font-size:20px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.strtoupper($prov).' </TD>
                    </TR>
                    <TR>
                        <TD style="font-size:18px" align="center" ><b>KARTU KENDALI KEGIATAN </b></TD>
                    </TR>
                    <TR>
                        <TD style="font-size:18px" align="center" ><b>BULAN '.strtoupper($bulanx).'</b></TD>
                    </TR>

                    <TR>
                        <TD>&nbsp;</TD>
                    </TR>
                    </TABLE>';
            $cRet .='<TABLE style="border-collapse:collapse;font-size:12px" width="90%" border="0" cellspacing="2" cellpadding="2" align=center>
                    <TR>
                        <TD align="left" width="10%"><b>SKPD</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="88%"><b>'.$lcskpd.' - '.$skpd.'</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="10%"><b>Nama Subegiatan</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="88%"><b>'.$giat.' - '.$this->tukd_model->get_nama($giat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan').'</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="10%"><b>Nama PPTK</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="88%"><b>'.$nip1.' - '.$nama1.'</b> </TD>
                    </TR>
                    </TABLE> <p/>';     
            $cRet .="<table style='border-collapse:collapse; font-size:12px' width='90%' align='center' border='1' cellspacing='0' cellpadding='$spasi'>
            <thead>
            <tr>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>No Urut</b></td>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>KODE REKENING</b></td>
                <td colspan ='2' align='center' bgcolor='#CCCCCC'><b>PAGU ANGGARAN</b></td>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>URAIAN</b></td>
                <td colspan ='3' align='center' bgcolor='#CCCCCC'><b>REALISASI KEGIATAN</b></td>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>SISA PAGU</b></td>
            </tr>
            <tr>
                <td align='center' bgcolor='#CCCCCC'><b>MURNI</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>UBAH</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>LS</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>UP/GU</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>TU</b></td>
            </tr>
            </thead>
           ";
                $query = $this->db->query("cetak_kartu_kendali_global '$lcskpd',$bulan,'$giat','$bulan'");
                    $no=0;              
                   foreach ($query->result() as $row) {
                    $no=$no+1;
                    $kd_rek5 = $row->kd_rek6; 
                    $nilai = $row->nilai;                   
                    $nilai_ubah = $row->nilai_ubah;                   
                    $uraian = $row->uraian;                   
                    $real_ls = $row->real_ls;
                    $real_up =$row->real_up;
                    $real_tu=$row->real_tu;
                    $sisa = $row->sisa;
                    
                    $nilai1  = empty($nilai) || $nilai == 0 ? '' :number_format($nilai,"2",",",".");    
                    $nilai_ubah1  = empty($nilai_ubah) || $nilai_ubah == 0 ? '' :number_format($nilai_ubah,"2",",",".");    
                    $real_ls1  = empty($real_ls) || $real_ls == 0 ? number_format(0,"2",",",".") :number_format($real_ls,"2",",",".");
                    $real_up1  = empty($real_up) || $real_up == 0 ? number_format(0,"2",",",".") :number_format($real_up,"2",",",".");
                    $real_tu1  = empty($real_tu) || $real_tu == 0 ? number_format(0,"2",",",".") :number_format($real_tu,"2",",",".");
                    $sisa1  = empty($sisa) || $sisa == 0 ? number_format(0,"2",",",".") :number_format($sisa,"2",",",".");
            $cRet .="
                <tr>
                <td align='center' >$no</td>
                <td align='left' >$kd_rek5</td>
                <td align='right' >$nilai1</td>
                <td align='right' >$nilai_ubah1</td>
                <td align='left' >$uraian</td>
                <td align='right' >$real_ls1</td>
                <td align='right' >$real_up1</td>
                <td align='right' >$real_tu1</td>
                <td align='right' >$sisa1</td>
                </tr>
                ";
                    
            
            }
                        
            $cRet .="</table>";
            $cRet .='<TABLE width="100%" style="font-size:12px" border="0" cellspacing="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" >Mengetahui,</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" >'.$jabatan.';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><u><b>'.$nama.' </b><br></u> '.$pangkat.';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><u><b>'.$nama1.' </b><br></u> '.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" >'.$nip.';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >'.$nip1.'</TD>
                    </TR>
                    </TABLE><br/>';

            $data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
            echo ("<title>KARTU KENDALI</title>");
                echo $cRet;
                break;
            case 1;
        $this->master_pdf->_mpdf('',$cRet,10,10,10,'L',0,'');
               break;
        }
    }
    
    function cetak_kartu_kendali_bpp($lcskpd='',$giat='',$ctk='',$bulan='',$bulan2='',$ttd=''){
        $spasi = $this->uri->segment(9);
        $bulan = $this->uri->segment(10);
        $bulanx= $this->support->getBulan($bulan);
        $nomor = str_replace('123456789',' ',$this->uri->segment(6));
        $nip2 = str_replace('123456789',' ',$this->uri->segment(7));
        $tanggal_ttd = $this->support->tanggal_format_indonesia($this->uri->segment(8));
        //$perio1 = $this->tukd_model->tanggal_format_indonesia($per1);
        //$perio2 = $this->tukd_model->tanggal_format_indonesia($per2);
        $nbulan=$this->support->getBulan($this->uri->segment(8));
        $nipkpa = str_replace('123456789',' ',$this->uri->segment(11));
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }

        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
                
         $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nipkpa'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip3=$rowttd->nip;                    
                    $nama3= $rowttd->nm;
                    $jabatan3  = $rowttd->jab;
                    $pangkat3  = $rowttd->pangkat;
                }
        
            $cRet ='<TABLE style="border-collapse:collapse;font-size:20px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.strtoupper($prov).' </TD>
                    </TR>
                    <TR>
                        <TD style="font-size:18px" align="center" ><b>KARTU KENDALI KEGIATAN </b></TD>
                    </TR>
                    <TR>
                        <TD style="font-size:18px" align="center" ><b>BULAN '.strtoupper($bulanx).'</b></TD>
                    </TR>

                    <TR>
                        <TD>&nbsp;</TD>
                    </TR>
                    </TABLE>';
            $cRet .='<TABLE style="border-collapse:collapse;font-size:12px" width="90%" border="0" cellspacing="0" cellpadding="0" align=center>
                    <TR>
                        <TD align="left" width="10%"><b>SKPD</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="88%"><b>'.$lcskpd.' - '.$skpd.'</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="10%"><b>Nama Kegiatan</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="88%"><b>'.$giat.' - '.$this->tukd_model->get_nama($giat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan').'</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="10%"><b>Nama PPTK</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="88%"><b>'.$nip1.' - '.$nama1.'</b> </TD>
                    </TR>
                    </TABLE> <p/>';     
            $cRet .="<table style='border-collapse:collapse; font-size:12px' width='90%' align='center' border='1' cellspacing='0' cellpadding='$spasi'>
            <thead>
            <tr>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>No Urut</b></td>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>KODE REKENING</b></td>
                <td colspan ='2' align='center' bgcolor='#CCCCCC'><b>PAGU ANGGARAN</b></td>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>URAIAN</b></td>
                <td colspan ='3' align='center' bgcolor='#CCCCCC'><b>REALISASI KEGIATAN</b></td>
                <td rowspan ='2' align='center' bgcolor='#CCCCCC'><b>SISA PAGU</b></td>
            </tr>
            <tr>
                <td align='center' bgcolor='#CCCCCC'><b>MURNI</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>UBAH</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>LS</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>UP/GU</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>TU</b></td>
            </tr>
            </thead>
           ";
                $query = $this->db->query("cetak_kartu_kendali_global '$lcskpd',$bulan,'$giat', '$bulan' ");
                    $no=0;              
                   foreach ($query->result() as $row) {
                    $no=$no+1;
                    $kd_rek5 = $row->kd_rek6; 
                    $nilai = $row->nilai;                   
                    $nilai_ubah = $row->nilai_ubah;                   
                    $uraian = $row->uraian;                   
                    $real_ls = $row->real_ls;
                    $real_up =$row->real_up;
                    $real_tu=$row->real_tu;
                    $sisa = $row->sisa;
                    
                    $nilai1  = empty($nilai) || $nilai == 0 ? '' :number_format($nilai,"2",",",".");    
                    $nilai_ubah1  = empty($nilai_ubah) || $nilai_ubah == 0 ? '' :number_format($nilai_ubah,"2",",",".");    
                    $real_ls1  = empty($real_ls) || $real_ls == 0 ? number_format(0,"2",",",".") :number_format($real_ls,"2",",",".");
                    $real_up1  = empty($real_up) || $real_up == 0 ? number_format(0,"2",",",".") :number_format($real_up,"2",",",".");
                    $real_tu1  = empty($real_tu) || $real_tu == 0 ? number_format(0,"2",",",".") :number_format($real_tu,"2",",",".");
                    $sisa1  = empty($sisa) || $sisa == 0 ? number_format(0,"2",",",".") :number_format($sisa,"2",",",".");
            $cRet .="
                <tr>
                <td align='center' >$no</td>
                <td align='left' >$kd_rek5</td>
                <td align='right' >$nilai1</td>
                <td align='right' >$nilai_ubah1</td>
                <td align='left' >$uraian</td>
                <td align='right' >$real_ls1</td>
                <td align='right' >$real_up1</td>
                <td align='right' >$real_tu1</td>
                <td align='right' >$sisa1</td>
                </tr>
                ";
                    
            
            }
                        
            $cRet .="</table>";
            $cRet .='<TABLE width="100%" style="font-size:12px" border="0" cellspacing="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" >Mengetahui,</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" >'.$jabatan3.'</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><u><b>'.$nama3.' </b><br></u> '.$pangkat3.'</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><u><b>'.$nama1.' </b><br></u> '.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" >'.$nip3.'</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >'.$nip1.'</TD>
                    </TR>
                  
                    </TABLE><br/>';

            $data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
            echo ("<title>KARTU KENDALI</title>");
                echo $cRet;
                break;
            case 1;
        $this->master_pdf->_mpdf('',$cRet,10,10,10,'L',0,'');
               break;
        }
    }

    function cetak_real_fisik($lcskpd='',$nbulan='',$ctk=''){
        $spasi   = $this->uri->segment(9);
        $initang = $this->uri->segment(10);
        $nomor   = str_replace('123456789',' ',$this->uri->segment(6));
        $nip2   = str_replace('123456789',' ',$this->uri->segment(7));
        $tanggal_ttd = $this->support->tanggal_format_indonesia($this->uri->segment(8));
        $skpd  = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc = "SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1 ="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$nip2' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        
            $cRet ='<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.$prov.' </TD>
                    </TR>
                    <tr></tr>
                    <TR>
                        <TD align="center" ><b>LAPORAN REALISASI FISIK 
                        <br>'.strtoupper($this->support->getBulan($nbulan)).'  '.strtoupper($thn).'</TD>
                    </TR>
                    </TABLE><br/>';
                    
    $cRet .="<table style='border-collapse:collapse; font-size:12px' width='97%' align='center' border='1' cellspacing='0' cellpadding='$spasi'>
            <thead>
            <tr>
                <td align='center' bgcolor='#CCCCCC'><b>KODE REKENING</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>URAIAN</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>ANGARAN</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>REALISASI</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>SISA ANGGARAN</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>%</b></td>
            </tr>
            </thead>
           ";
                    $tot_nilai=0;
                    $tot_realisasi=0;
                $query = $this->db->query("realisasi_fisik '$lcskpd',$nbulan,$initang");        
                   foreach ($query->result() as $row) {
                    $rek = $row->rek; 
                    $urut = $row->urut;                   
                    $uraian = $row->uraian;                   
                    $nilai = $row->nilai;
                    $realisasi =$row->realisasi;
                    $sisa=$row->sisa;
                    $persen = $row->persen;
                    
                    $nilai1  = empty($nilai) || $nilai == 0 ? number_format(0,"2",",",".") :number_format($nilai,"2",",",".");  
                    $realisasi1  = empty($realisasi) || $realisasi == 0 ? number_format(0,"2",",",".") :number_format($realisasi,"2",",",".");
                    $sisa1  = empty($sisa) || $sisa == 0 ? number_format(0,"2",",",".") :number_format($sisa,"2",",",".");
                    $persen1  = empty($persen) || $persen == 0 ? number_format(0,"2",",",".") :number_format($persen,"2",",",".");
            
            if(strlen($rek)==18){
            $cRet .="
                <tr>
                <td align='left' ><b>$urut</b></td>
                <td align='left' ><b>$uraian</b></td>
                <td align='right' ><b>$nilai1</b></td>
                <td align='right' ><b>$realisasi1</b></td>
                <td align='right' ><b>$sisa1</b></td>
                <td align='right' ><b>$persen1</b></td>
                </tr>
                ";
            } else if(strlen($rek)==15){
            $cRet .="
                <tr>
                <td align='left' ><b>$urut</b></td>
                <td align='left' ><b>$uraian</b></td>
                <td align='right' ><b>$nilai1</b></td>
                <td align='right' ><b>$realisasi1</b></td>
                <td align='right' ><b>$sisa1</b></td>
                <td align='right' ><b>$persen1</b></td>
                </tr>
                ";
                $tot_nilai=$tot_nilai+$nilai;
                $tot_realisasi=$tot_realisasi+$realisasi;
            } else {
                $cRet .="
                <tr>
                <td align='left' >$urut</td>
                <td align='left' >$uraian</td>
                <td align='right' >$nilai1</td>
                <td align='right' >$realisasi1</td>
                <td align='right' >$sisa1</td>
                <td align='right' >$persen1</td>
                </tr>
                ";
            }       
            }

            if($tot_nilai==0){
                $hasl="0";
            }else{
                $hasl=($tot_realisasi/$tot_nilai)*100;
            }
            $cRet .="
                <tr>
                <td colspan='2' align='center' ><b>Total</b></td>
                <td align='right' ><b>".number_format($tot_nilai,"2",",",".")."</b></td>
                <td align='right' ><b>".number_format($tot_realisasi,"2",",",".")."</b></td>
                <td align='right' ><b>".number_format($tot_nilai-$tot_realisasi,"2",",",".")."</b></td>
                <td align='right' ><b>".number_format($hasl,"2",",",".")."</b></td>
                </tr>
                ";
                
            
            $cRet .="</table>";
            $cRet .='<TABLE width="100%" style="font-size:12px" border="0" cellspacing="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">Mengetahui</TD>
                        <TD align="center" width="30%">'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%">'.$jabatan.'</TD>
                        <TD align="center" width="30%">'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%"><b>&nbsp;</TD>
                        <TD align="center" width="30%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%"><u><b>'.$nama.' </b><br></u> '.$pangkat.'</TD>
                        <TD align="center" width="30%"><u><b>'.$nama1.' </b><br></u> '.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%">'.$nip.'</TD>
                        <TD align="center" width="30%">'.$nip1.'</TD>
                    </TR>
                    </TABLE><br/>';

            $data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
                echo ("<title>LAPORAN REALISASI FISIK</title>");
                echo $cRet;
                break;
            case 1;
                $this->master_pdf->_mpdf('',$cRet,10,10,10,'L',0,'');
               break;
        }
    }


    function cetak_real_fisik_global($lcskpd='',$nbulan='',$ctk=''){
        $spasi = $this->uri->segment(9);
        $initang = $this->uri->segment(10);
        $nomor = str_replace('123456789',' ',$this->uri->segment(6));
        $nip2 = str_replace('123456789',' ',$this->uri->segment(7));
        $tanggal_ttd = $this->support->tanggal_format_indonesia($this->uri->segment(8));
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$nip2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        
            $cRet ='<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.$prov.' </TD>
                    </TR>
                    <tr></tr>
                    <TR>
                        <TD align="center" ><b>LAPORAN REALISASI FISIK 
                        <br>  '.strtoupper($this->support->getBulan($nbulan)).'  '.strtoupper($thn).'</TD>
                    </TR>
                    </TABLE><br/>';
                    
            $cRet .="<table style='border-collapse:collapse; font-size:12px' width='97%' align='center' border='1' cellspacing='0' cellpadding='$spasi'>
            <thead>
            <tr>
                <td align='center' bgcolor='#CCCCCC'><b>KODE REKENING</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>URAIAN</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>ANGARAN</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>REALISASI</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>SISA ANGGARAN</b></td>
                <td align='center' bgcolor='#CCCCCC'><b>%</b></td>
            </tr>
            </thead>
           ";
                    $tot_nilai=0;
                    $tot_realisasi=0;
                $query = $this->db->query("realisasi_fisik_global '$lcskpd',$nbulan,$initang");     
                   foreach ($query->result() as $row) {
                    $rek = $row->rek; 
                    $urut = $row->urut;                   
                    $uraian = $row->uraian;                   
                    $nilai = $row->nilai;
                    $realisasi =$row->realisasi;
                    $sisa=$row->sisa;
                    $persen = $row->persen;
                    
                    $nilai1  = empty($nilai) || $nilai == 0 ? number_format(0,"2",",",".") :number_format($nilai,"2",",",".");  
                    $realisasi1  = empty($realisasi) || $realisasi == 0 ? number_format(0,"2",",",".") :number_format($realisasi,"2",",",".");
                    $sisa1  = empty($sisa) || $sisa == 0 ? number_format(0,"2",",",".") :number_format($sisa,"2",",",".");
                    $persen1  = empty($persen) || $persen == 0 ? number_format(0,"2",",",".") :number_format($persen,"2",",",".");
            
            if(strlen($rek)==18){
            $cRet .="
                <tr>
                <td align='left' ><b>$urut</b></td>
                <td align='left' ><b>$uraian</b></td>
                <td align='right' ><b>$nilai1</b></td>
                <td align='right' ><b>$realisasi1</b></td>
                <td align='right' ><b>$sisa1</b></td>
                <td align='right' ><b>$persen1</b></td>
                </tr>
                ";
            }           
            else if(strlen($rek)==15){
            $cRet .="
                <tr>
                <td align='left' ><b>$urut</b></td>
                <td align='left' ><b>$uraian</b></td>
                <td align='right' ><b>$nilai1</b></td>
                <td align='right' ><b>$realisasi1</b></td>
                <td align='right' ><b>$sisa1</b></td>
                <td align='right' ><b>$persen1</b></td>
                </tr>
                ";
                $tot_nilai=$tot_nilai+$nilai;
                $tot_realisasi=$tot_realisasi+$realisasi;
            } else {
                $cRet .="
                <tr>
                <td align='left' >$urut</td>
                <td align='left' >$uraian</td>
                <td align='right' >$nilai1</td>
                <td align='right' >$realisasi1</td>
                <td align='right' >$sisa1</td>
                <td align='right' >$persen1</td>
                </tr>
                ";
            }       
            }
            $cRet .="
                <tr>
                <td colspan='2' align='center' ><b>Total</b></td>
                <td align='right' ><b>".number_format($tot_nilai,"2",",",".")."</b></td>
                <td align='right' ><b>".number_format($tot_realisasi,"2",",",".")."</b></td>
                <td align='right' ><b>".number_format($tot_nilai-$tot_realisasi,"2",",",".")."</b></td>
                <td align='right' ><b>".number_format(($tot_realisasi/$tot_nilai)*100,"2",",",".")."</b></td>
                </tr>
                ";
                
            
            $cRet .="</table>";
            $cRet .='<TABLE width="100%" style="font-size:12px" border="0" cellspacing="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="50%">Mengetahui</TD>
                        <TD align="center" width="30%">'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%">'.$jabatan.'</TD>
                        <TD align="center" width="30%">'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%"><b>&nbsp;</TD>
                        <TD align="center" width="30%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%"><u><b>'.$nama.' </b><br></u> '.$pangkat.'</TD>
                        <TD align="center" width="30%"><u><b>'.$nama1.' </b><br></u> '.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD align="center" width="30%">'.$nip.'</TD>
                        <TD align="center" width="30%">'.$nip1.'</TD>
                    </TR>
                    </TABLE><br/>';

            $data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
            echo ("<title>LAPORAN REALISASI FISIK</title>");
                echo $cRet;
                break;
            case 1;
                $this->master_pdf->_mpdf('',$cRet,10,10,10,'L',0,'');
               break;
        }
    }

    function cetak_sts_bp(){
        $ctk = $this->uri->segment(3);
        $lcnosts    = str_replace('123456789','/',$this->uri->segment(4));
        $pa     = str_replace('123456789',' ',$this->uri->segment(5));
        $bp     = str_replace('123456789',' ',$this->uri->segment(6));
        $kd_skpd    = $this->session->userdata('kdskpd');
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $cRet='';
        $lcpemda = $kab;
        $sqlttd1="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE id_ttd='$pa'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;  
                    $pangkat=$rowttd->pangkat;  
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                }
                
        $sqlttd2="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE id_ttd='$bp'";
                 $sqlttd2=$this->db->query($sqlttd2);
                 foreach ($sqlttd2->result() as $rowttd2)
                {
                    $nip2=$rowttd2->nip; 
                    $pangkat2=$rowttd2->pangkat;  
                    $nama2= $rowttd2->nm;
                    $jabatan2  = $rowttd2->jab;
                }
        $sql = "SELECT a.kd_skpd,a.tgl_sts,keterangan,(SELECT DISTINCT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd) as nm_skpd,
                SUM(rupiah) as total FROM trhkasin_pkd a 
                INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
                WHERE a.no_sts='$lcnosts' AND a.kd_skpd='$kd_skpd'
                GROUP BY a.kd_skpd,a.tgl_sts,keterangan";
                
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();
        $total = $trh->total;
        $rupiah = $this->tukd_model->terbilang($trh->total);
        $lcskpd = $trh->nm_skpd;
        $lctglsts = $trh->tgl_sts;
        $keterangan = $trh->keterangan;
        
      
        
        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                     <thead>
                        <tr><td colspan='2' style='text-align:center;border: solid 1px white;'>".strtoupper($lcpemda)."</td></tr>
                        <tr><td colspan='2' style='text-align:center;border: solid 1px white;'>SURAT TANDA SETORAN</td></tr>
                        <tr><td colspan='2' style='text-align:center;border: solid 1px white;border-bottom:solid 1px black;'>(STS)</td></tr>
                     </thead></table><br>";       
              
        
     
        $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td>
                <table  style='border-collapse:collapse;' width='100%' align='left' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='20%'>No STS</td>
                        <td width='50%'>: $lcnosts</td>
                       <td width='20%'>BANK</td>
                        <td width='40%'>: Bank Kalbar</td>
                    </tr>
                    <tr>
                        <td width='20%'>SKPD</td>
                        <td width='50%'>: $lcskpd</td>
                        <td width='20%'>No Rekening</td>
                        <td width='40%'>: 1001002830</td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td>
                <table  style='border-collapse:collapse;' width='100%' align='left' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='30%'>Harap diterima uang sebesar</td>
                        <td width='70%' valign='top'>".number_format($total,2,'.',',')."</td>
                    </tr>
                    <tr>
                        <td width='30%'>(dengan huruf)</td>
                        <td width='70%' valign='top'><i>( $rupiah )</i></td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td valign='top'>Dengan rincian penerimaan sebagai berikut<br>
            <table  style='border-collapse:collapse;' width='100%' align='left' border='1' cellspacing='0' cellpadding='4'>
              <tr>
                <td width='4%' height='28' bgcolor='#CCCCCC' align='center'><b>No</b></td>
                <td width='15%' bgcolor='#CCCCCC' align='center'><b>Kode Rekening</b></td>
                <td width='48%' bgcolor='#CCCCCC' align='center'><b>Uraian Rincian Objek</b></td>
                <td width='50%' bgcolor='#CCCCCC' align='center'><b>Jumlah</b></td>
              </tr>
              
              <tr>
                <td style='border-bottom:none;' width='4%' height='28'  align='center'><b></b></td>
                <td style='border-bottom:none;' align='center'><b> </b></td>
                <td style='border-bottom:none;' width='48%' align='left'>$keterangan</td>
                <td style='border-bottom:none;' width='50%'  align='center'><b></b></td>
              </tr>";
              
           
           $sql = "SELECT kd_rek6,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=b.kd_rek6) nm_rek6,rupiah FROM trhkasin_pkd a 
                    INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
                    WHERE a.no_sts='$lcnosts' AND a.kd_skpd='$kd_skpd'
                    ORDER BY b.kd_rek6
                    ";
                
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        foreach ($hasil->result() as $row)
        {
           $lntotal = $lntotal + $row->rupiah;     
           $lcno = $lcno + 1;
           $cRet .=" <tr>
                        <td align='center' style='border-top:none;border-bottom:none;' >$lcno</td>
                        <td style='border-top:none;border-bottom:none;' >".$row->kd_rek6."</td>
                        <td style='border-top:none;border-bottom:none;' >$row->nm_rek6</td>
                        <td style='border-top:none;border-bottom:none;' align='right'>".number_format($row->rupiah,2,'.',',')."</td>
                      </tr>";     
            
        }
            $cRet .="
            <tr>
                <td colspan='3' align='right'>Jumlah</td>                
                <td align='right'>".number_format($lntotal)."</td>
                
            </tr>
            </table>
            </td>
          </tr>
          
          <tr>
            <td height='30' align='center' style='font-size:14px'>Uang tersebut diterima pada tanggal ".$this->tukd_model->tanggal_format_indonesia($lctglsts)."</td>
          </tr>
          <tr>
            <td height='60' align='center'></td>
          </tr>
          <tr>
            <td height='56'>
                <table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                  <tr>
                    <td width='50%' align='center' style='font-size:14px'><b>Mengetahui,<br>$jabatan</b></td>
                    <td width='50%' align='center' style='font-size:14px'><b><br>$jabatan2</b></td>
                  </tr>
                  <tr>
                  <td height='60' colspan ='2' ></td>
                  </tr>
                  <tr>
                    <td width='50%' align='center' style='font-size:14px'><b>$nama<br>$pangkat<br>NIP.$nip</b></td>
                    <td width='50%' align='center' style='font-size:14px'><b>$nama2<br>$pangkat2<br>NIP.$nip2</b></td>
                  </tr>                  
                </table>
            </td>
          </tr>
        </table>";
                $data['prev']= $cRet;    
                
        if($ctk=='0'){
         echo ("<title>STS</title>");
         echo $cRet;
        }
        else{
        $this->master_pdf->_mpdf('',$cRet,'10','10',5,'0');
        }
    }
    
    function cetak_reg_cp($lcskpd='',$ctk='',$ttd1='',$ttd2='',$tglttd='',$tgl1='',$tgl2=''){
        $nomor = str_replace('123456789',' ',$ttd1);
        $nip2 = str_replace('123456789',' ',$ttd2);
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tglttd);
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$nip2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        
            $cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.$prov.' </TD>
                    </TR>
                    <tr></tr>
                    <TR>
                        <TD align="center" ><b>REGISTER CP <br>
                                            </TD>
                    </TR>
                    </TABLE><br/>';

            $cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%">
                     <TR>
                        <TD align="left" width="20%" >SKPD</TD>
                        <TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
                     </TR>
                     <TR>
                        <TD align="left">Kepala SKPD</TD>
                        <TD align="left">: '.$nama.'</TD>
                     </TR>
                     </TABLE>';

            $cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="2" cellpadding="2" align="center">
                     <thead>
                     <TR>
                        <TD rowspan="4"  bgcolor="#CCCCCC" align="center" ><b>No.</b></TD>
                        <TD rowspan="4"  bgcolor="#CCCCCC" align="center" ><b>Tanggal CP</b></TD>
                        <TD rowspan="4"  bgcolor="#CCCCCC" align="center" ><b>No STS </b></TD>
                        <TD rowspan="4"  bgcolor="#CCCCCC" align="center" ><b>No SP2D</b></TD>
                        <TD rowspan="4"  bgcolor="#CCCCCC" align="center" ><b>Uraian</b></TD>
                        <TD colspan="5"  bgcolor="#CCCCCC" align="center" ><b>Jumlah CP</b></TD>
                     </TR>
                     <TR>
                        <TD rowspan="3" bgcolor="#CCCCCC" align="center" ><b>UP/GU/TU</b></TD>
                        <TD colspan="4" bgcolor="#CCCCCC" align="center" ><b>LS</b></TD>                        
                     </TR>
                     <TR>
                        <TD colspan="3" bgcolor="#CCCCCC" align="center" ><b>Gaji</b></TD>
                        <TD rowspan="2" bgcolor="#CCCCCC" align="center" ><b>Barang<br>Jasa</b></TD>                        
                     </TR>
                     <TR>
                        <TD bgcolor="#CCCCCC" align="center" ><b>Pot. Lain</b></TD>
                        <TD bgcolor="#CCCCCC" align="center" ><b>HKPG</b></TD>                      
                        <TD bgcolor="#CCCCCC" align="center" ><b>CP</b></TD>                        
                     </TR>
                     </thead>
                     ';
            
            
                $query = $this->db->query("SELECT a.tgl_sts,b.no_sts, a.no_sp2d, keterangan,
                        SUM(CASE WHEN jns_trans='1' AND jns_cp='3' THEN b.rupiah ELSE 0 END) AS up_gu,
                        SUM(CASE WHEN jns_trans='5' AND jns_cp='1' AND pot_khusus='2' THEN b.rupiah ELSE 0 END) AS pot_lain,
                        SUM(CASE WHEN jns_trans='5' AND jns_cp='1' AND pot_khusus='1' THEN b.rupiah ELSE 0 END) AS hkpg,
                        SUM(CASE WHEN jns_trans='1' AND jns_cp='1' THEN b.rupiah ELSE 0 END) AS cp,
                        SUM(CASE WHEN jns_trans='1' AND jns_cp='2' THEN b.rupiah ELSE 0 END) AS ls
                        FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts = b.no_sts AND a.kd_skpd=b.kd_skpd
                        WHERE a.kd_skpd='$lcskpd' AND a.tgl_sts BETWEEN '$tgl1' AND '$tgl2' AND jns_trans IN ('1','5')
                        GROUP BY a.tgl_sts, b.no_sts, a.no_sp2d, keterangan
                        ");  
                $lcno=0;
                $tot_up_gu=0;
                $tot_pot_lain=0;
                $tot_hkpg=0;
                $tot_cp=0;
                $tot_ls=0;
                foreach ($query->result() as $row) {
                    $tgl_sts = $row->tgl_sts; 
                    $no_sts = $row->no_sts;    
                    $no_sp2d = $row->no_sp2d;
                    $keterangan=$row->keterangan;
                    $up_gu = $row->up_gu;
                    $pot_lain = $row->pot_lain;
                    $hkpg = $row->hkpg;
                    $cp = $row->cp;
                    $ls  = $row->ls;
                    $lcno=$lcno+1;
                            $cRet.='<TR>
                                <TD valign="top" align="center"> '.$lcno.' </TD>
                                <TD valign="top" align="left">'.$this->support->tanggal_format_indonesia($tgl_sts).'</TD>
                                <TD valign="top" align="center">'.$no_sts.'</TD>
                                <TD valign="top" align="left">'.$no_sp2d.'</TD>
                                <TD valign="top" align="left">'.$keterangan.'</TD>
                                <TD valign="top" align="right">'.number_format($up_gu,'2','.',',').'</TD>
                                <TD valign="top" align="right">'.number_format($pot_lain,'2','.',',').'</TD>
                                <TD valign="top" align="right">'.number_format($hkpg,'2','.',',').'</TD>
                                <TD valign="top" align="right">'.number_format($cp,'2','.',',').'</TD>
                                <TD valign="top" align="right">'.number_format($ls,'2','.',',').'</TD>
                             </TR>';    
                        
                $tot_up_gu=$tot_up_gu+$up_gu;
                $tot_pot_lain=$tot_pot_lain+$pot_lain;
                $tot_hkpg=$tot_hkpg+$hkpg;
                $tot_cp=$tot_cp+$cp;
                $tot_ls=$tot_ls+$ls;
                }
                $cRet.='<TR>
                                <TD colspan="5" valign="top" align="center"><b>J U M L A H</b></TD>
                                <TD valign="top" align="right"><b>'.number_format($tot_up_gu,'2','.',',').'</b></TD>
                                <TD valign="top" align="right"><b>'.number_format($tot_pot_lain,'2','.',',').'</b></TD>
                                <TD valign="top" align="right"><b>'.number_format($tot_hkpg,'2','.',',').'</b></TD>
                                <TD valign="top" align="right"><b>'.number_format($tot_cp,'2','.',',').'</b></TD>
                                <TD valign="top" align="right"><b>'.number_format($tot_ls,'2','.',',').'</b></TD>
                             </TR>';
                
            $cRet .='</TABLE>';
            
            $cRet .='<TABLE style="font-size:14px;" width="100%" align="center">
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >Mengetahui,</TD>
                        <TD width="50%" align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$jabatan.'</TD>
                        <TD width="50%" align="center" >'.$jabatan1.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                     <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
                        <TD width="50%" align="center" ><b><u>'.$nama1.'</u></b><br>'.$pangkat1.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" >'.$nip.'</TD>
                        <TD width="50%" align="center" >'.$nip1.'</TD>
                    </TR>
                    
                    </TABLE><br/>';

            $data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
            echo ("<title>REG. CP</title>");
                echo $cRet;
                break;
            case 1;
                $this->master_pdf->_mpdf('',$cRet,10,10,10,10,1,'');
               break;
        }
    }
  
}