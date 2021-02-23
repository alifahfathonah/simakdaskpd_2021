<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */ 
 

class cetak_perda_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }


    function cetak_perda_murni($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji){

        $thn=$this->session->userdata('pcThang');
        $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
                a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
                 ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
        $sqlskpd=$this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns){
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
        } 
        if($doc=='PERWA_MURNI'){
            $lampiran="Rancangan Peraturan Walikota";
            $judul="RINGKASAN PENJABARAN APBD YANG DIKLASIFIKASI <br> MENURUT KELOMPOK, JENIS, OBJEK, RINCIAN OBJEK <br> PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perwa";
        }else{
            $lampiran="Rancangan Peraturan Daerah";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT KELOMPOK <br>DAN JENIS PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perda";
        }
        $cRet='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            
            $tgl_lam=$abc->tanggal;
        }

        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> LAMPIRAN I:</td>
                        <td width='40%' colspan='2' align='left' style='border-left:none'>&nbsp; $lampiran</td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> </td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Nomor </td>
                        <td width='30%' align='left' style='border-left:none'>: $nomor </td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'></td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Tanggal </td>
                        <td width='30%' align='left' style='border-left:none'>: $tgl_lam </td>
                    </tr>
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'><strong>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </strong></td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                     <thead>                       
                        <tr><td bgcolor='#CCCCCC' width='10%' align='center'><b>KODE</b></td>                            
                            <td bgcolor='#CCCCCC' width='70%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' width='20%' align='center'><b>JUMLAH (Rp)</b></td></tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='70%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>3</td>
                        </tr>
                ";

        if($detail=='detail'){
            $rincian="  UNION ALL 

                        SELECT a.kd_rek4 AS kd_rek,a.nm_rek4 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek4 a INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4)))
                        where left(b.kd_rek6,1)='4'  
                        GROUP BY a.kd_rek4, a.nm_rek4  
                        UNION ALL 

                        SELECT a.kd_rek5 AS kd_rek,a.nm_rek5 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek5 a INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5)))
                        where left(b.kd_rek6,1)='4' 
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 

                        SELECT a.kd_rek6 AS kd_rek,a.nm_rek6 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek6 a INNER JOIN trdrka b ON a.kd_rek6=LEFT(b.kd_rek6,(len(a.kd_rek6)))
                        where left(b.kd_rek6,1)='4' 
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}
        
        $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
                INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,1)='4' 
                 GROUP BY a.kd_rek1, a.nm_rek1 

                UNION ALL 

                SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b 
                ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek2,a.nm_rek2 

                UNION ALL 

                SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek ,
                SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
                where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek3, a.nm_rek3 
                $rincian
                ORDER BY kd_rek";
                 
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0){                                  
            foreach ($query->result() as $row){
                    $coba1=$this->support->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"2",",",".");
                   
                    $cRet.= " <tr>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>$coba1</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>$coba2</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba3</td>
                             </tr>";                     
            }
        }else{
                $cRet .= " <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>4</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>PENDAPATAN</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format(0,"2",",",".")."</td>
                          </tr>";
                    
                
        }                                 
                
        $sqltp="SELECT SUM(nilai) AS totp FROM trdrka WHERE LEFT(kd_rek6,1)='4' ";
        $sqlp=$this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp){

            $coba4=number_format($rowp->totp,"2",",",".");
            $cob1=$rowp->totp;
                   
            $cRet    .= "<tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'>Jumlah Pendapatan</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba4</td>
                        </tr>
                        <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>&nbsp;</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'></td>
                        </tr>";
        }

        if($gaji==1){
            $aktifkanGaji="and right(b.kd_sub_kegiatan,10) <> '01.2.02.01' ";
        }else{
            $aktifkanGaji="";
        }

        if($detail=='detail'){
            $rincian="  UNION ALL 
                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek4, a.nm_rek4 
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}     
                $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
                        INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek1, a.nm_rek1 
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->support->dotrek($row1->rek);
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"2",",",".");
                   
                     $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>$coba5</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>$coba6</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba7</td></tr>";
                }

                if($gaji==1){
                    $aktifkanGaji="and right(kd_sub_kegiatan,10) <> '01.2.02.01' ";
                }else{
                    $aktifkanGaji="";
                }     

                $sqltb="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='5' $aktifkanGaji";
                $sqlb=$this->db->query($sqltb);
                foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,"2",",",".");
                    $cob=$rowb->totb;
                    $cRet    .= " <tr>                                   
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='80%' align='right'>Jumlah Belanja</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba8</td></tr>";
                 }
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td></tr>";

                  
                  $surplus=$cob1-$cob; 
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Surplus/Defisit</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->rka_model->angka($surplus)."</td></tr>"; 

                    
                $sqltpm="SELECT isnull(SUM(nilai),0) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='6' ";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                   $coba12=number_format($rowtpm->totb,"2",",",".");
                    $cobtpm=$rowtpm->totb;
                    if($cobtpm>0){
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td></tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>6</td>                                     
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>Pembiayaan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba12</td>
                                    </tr>";
                        if($detail=='detail'){
                            $rincian="  UNION ALL 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            $coba10=$rowpm->nm_rek;
                            $coba11= number_format($rowpm->nilai,"2",",",".");
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba11</td></tr>";
                        } 


                        $sqltpm="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='61' ";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                           $coba12=number_format($rowtpm->totb,"2",",",".");
                                            $cobtpm=$rowtpm->totb;
                                            $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba12</td></tr>";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            $coba10=$rowpk->nm_rek;
                            $coba11= number_format($rowpk->nilai,"2",",",".");
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba11</td></tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='62'";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                   $cobatpk=number_format($rowtpk->totb,"2",",",".");
                    $cobtpk=$rowtpk->totb;
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$cobatpk</td></tr>";
                 }
    
                $pnetto=$cobtpm-$cobtpk;
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Pembiayaan Netto</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->rka_model->angka($pnetto)."</td></tr></table>";                                                      
                    

                    } /*end if pembiayaan 0*/

                } 
                  
                $cRet    .= "</table>";
        if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    

            $cRet.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>Pontianak, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                            </td>

                        </tr>
                    </table>";    
        }
       
        $data['prev']= $cRet;    
        $judul         = 'RKA SKPD';
        switch($cetak) { 
        case 1;
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'0');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            //$this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 3;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 0;
        echo ("<title>RKA SKPD</title>");
        echo($cRet);
        break;
        }
                
    } 

    function lampiran2_murni($tgl,$doc,$pdf){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="Rancangan Peraturan Walikota";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam="perwa";
        }else{
            $lampiran="Rancangan Peraturan Daerah";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam="perda";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            
            $tgl_lam=$abc->tanggal;
        }

        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> LAMPIRAN II:</td>
                        <td width='40%' colspan='2' align='left' style='border-left:none'>&nbsp; $lampiran</td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> </td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Nomor </td>
                        <td width='30%' align='left' style='border-left:none'>: $nomor </td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'></td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Tanggal </td>
                        <td width='30%' align='left' style='border-left:none'>: $tgl_lam </td>
                    </tr>
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'><strong>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </strong></td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='2' colspan='3' align='center' width='20%'><b>Kode</td>
                    <td rowspan='2' align='center' width='20%'><b>Urusan Pemerintah Daerah</td>
                    <td rowspan='2' align='center' width='10%'><b>Pendapatan</td>
                    <td colspan='5' align='center' width='40%'><b>Belanja</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center' ><b>Jumlah Belanja</td>
               </tr>
                </thead>
               <tr>
                    <td align='center' colspan='3'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>
                    <td align='center'><b>8</td>                    
               </tr>

               <tr>
                    <td align='center' colspan='3'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>                    
               </tr>

               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* from v_lampiran2_murni ORDER BY kd , urut";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
            $tot  =$ab->tot;

            if($skpd!=''){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
            }

            $tbl.="<tr>
                        <td align='center' width='5%'>$kode1</td>
                        <td align='center' width='5%'>$kode2</td>
                        <td align='center' width='10%'>$skpd</td>
                        <td align='left' width='20%'>$urai</td>
                        <td align='right' width='10%'>".number_format($pend,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b51,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b52,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b53,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b54,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($tot,2,',','.')."</td>                    
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='4'>Jumlah</td>
                        <td align='right'>".number_format($tot4,2,',','.')."</td>
                        <td align='right'>".number_format($tot51,2,',','.')."</td>
                        <td align='right'>".number_format($tot52,2,',','.')."</td>
                        <td align='right'>".number_format($tot53,2,',','.')."</td>
                        <td align='right'>".number_format($tot54,2,',','.')."</td>
                        <td align='right'>".number_format($tottot,2,',','.')."</td>                    
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>Pontianak, $tgl <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }
    }

    function lampiran3_murni($tgl,$doc,$pdf,$skpd,$urusan){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');
        if($doc=='PERWA_MURNI'){
            $lampiran="Rancangan Peraturan Walikota";
            $judul="RINGKASAN PENJABARAN APBD YANG DIKLASIFIKASI <br> MENURUT KELOMPOK, JENIS, OBJEK, RINCIAN OBJEK <br> PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perwa";
        }else{
            $lampiran="Rancangan Peraturan Daerah";
            $judul="RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN, <br>
                    SUB KEGIATAN, KELOMPOK, DAN JENIS PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perda";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";




        $nmskpd=$this->db->query("SELECT nm_skpd nama from ms_skpd where kd_skpd='$skpd'")->row()->nama;

        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $tgl_lam=$abc->tanggal;
        }

        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> LAMPIRAN III:</td>
                        <td width='40%' colspan='2' align='left' style='border-left:none'>&nbsp; $lampiran</td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> </td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Nomor </td>
                        <td width='30%' align='left' style='border-left:none'>: $nomor </td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'></td>
                        <td width='10%' align='left' style='border-bottom:none'>&nbsp; Tanggal </td>
                        <td width='30%' align='left' style='border-bottom:none'>: $tgl_lam </td>
                    </tr>
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'><strong>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </strong></td>
                    </tr>
                </table>";
            $kskpd=explode(".",$skpd);
            $urusan1=str_replace("-",".",$kskpd[0]);
            $urusan2=str_replace("-",".",$kskpd[1]);
            $urusan3=str_replace("-",".",$kskpd[2]);

            $data= array();
            $sql="SELECT * from ms_bidang_urusan where kd_bidang_urusan in ('$urusan1','$urusan3','$urusan2')";
            $kecap=$this->db->query($sql);
        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0' cellpadding='2'>
                    <tr>
                        <td align='left' width='15%'>URUSAN PEMERINTAH";
                        foreach($kecap->result() as $abc){
                             $tbl.="&nbsp;<br>";
                        }
                    $tbl .= "</td>

                        <td align='left' width='2%'>:";
                        foreach($kecap->result() as $abc){
                             $tbl.="&nbsp;<br>";
                        }
                    $tbl .= "</td>

                        <td align='left' width='15%'>";
                        foreach($kecap->result() as $abc){
                             $kdurus=$abc->kd_bidang_urusan;
                             $tbl.="$kdurus<br>";
                        }
                    $tbl .= "</td>

                        <td align='left' width='68%'>";
                        foreach($kecap->result() as $abc){
                             $nmurus=$abc->nm_bidang_urusan;
                             $tbl.="$nmurus<br>";
                        }
                    $tbl .= " </td>
                    </tr>
                    <tr>
                        <td align='left' width='10%'>ORGANISASI</td>
                        <td align='left' width='2%'>:</td>
                        <td align='left' width='15%'>$skpd</td>
                        <td align='left' width='68%'>$nmskpd</td>
                    </tr>
                </table>";
        $tbl.="<table style='border-collapse:collapse;font-size:12px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td colspan='7' align='center'><b>KODE REKENING</td>
                    <td align='center'><b>URAIAN</td>
                    <td align='center'><b>JUMLAH</td>
                    <td align='center' width='5%'><b>DASAR HUKUM</td>
               </tr>
               </thead>
               <tr>
                    <td colspan='7' align='center'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
               </tr>
               <tr>
                    <td colspan='10' bgcolor='#cccccc' align='center'><b>&nbsp;</td>
               </tr>


               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT * from v_lampiran3_murni where sk='$skpd' ORDER BY urut,rek";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1 =$ab->urusan;
            $kode2 =$ab->bid_urus;
            $kode3 =$ab->sk;
            $kode4 =$ab->program;
            $kode5 =$ab->giat;
            $kode6 =$ab->subgiat;
            $kode7 =$ab->rek;
            $nilai =$ab->nilai;
            $urai  =$ab->urai;
            if($kode4=='00' && $kode5!=''){
                $kode5="0.00";
            }
            if($kode4=='00' && $kode6!=''){
                $kode6="00";
            }
            $tbl.="<tr>
                        <td align='center' width='5%'>$kode1</td>
                        <td align='center' width='5%'>$kode2</td>
                        <td align='center' width='10%'>$kode3</td>
                        <td align='center' width='5%'>$kode4</td>
                        <td align='center' width='5%'>$kode5</td>
                        <td align='center' width='5%'>$kode6</td>
                        <td align='left' width='5%'>$kode7</td>
                        <td align='left' width='35%'>$urai</td>
                        <td align='right' width='20%'>".number_format($nilai,2,',','.')."</td>
                        <td align='center' width='5%'>&nbsp;</td>
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='8'>JUMLAH</td>
                        <td align='right'></td>
                        <td align='center'>&nbsp;</td>
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>Pontianak, $tgl <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                            </td>

                        </tr>
                    </table>"; 
        if($pdf==0){
                echo $tbl;   
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }

    }

    function lampiran4_murnid($tgl,$doc,$pdf){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="Rancangan Peraturan Walikota";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam="perwa";
        }else{
            $lampiran="Rancangan Peraturan Daerah";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam="perda";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            
            $tgl_lam=$abc->tanggal;
        }

        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> LAMPIRAN IV:</td>
                        <td width='40%' colspan='2' align='left' style='border-left:none'>&nbsp; $lampiran</td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> </td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Nomor </td>
                        <td width='30%' align='left' style='border-left:none'>: $nomor </td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'></td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Tanggal </td>
                        <td width='30%' align='left' style='border-left:none'>: $tgl_lam </td>
                    </tr>
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'><strong>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </strong></td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='2' colspan='6' align='center' width='20%'><b>Kode</td>
                    <td rowspan='2' align='center' width='20%'><b>Urusan Pemerintah Daerah</td>
                    <td colspan='5' align='center' width='40%'><b>Belanja</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center' ><b>Jumlah Belanja</td>
               </tr>
                </thead>
               <tr>
                    <td align='center' colspan='6'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>                                
               </tr>

               <tr>
                    <td align='center' colspan='6'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>                    
               </tr>

               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT * from (SELECT *,kd1+kd2+kd_skpd+kd3+kd4+kd5 uru from (SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2, SUBSTRING(kd,6,2) kd3, SUBSTRING(kd,9,4) kd4, SUBSTRING(kd,14,2) kd5,* from v_lampiran4_murnip) oke) oke2  where left(kd,1)='7' ORDER BY kd,kd_skpd";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode3=$ab->kd3;
            $kode4=$ab->kd4;
            $kode5=$ab->kd5;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
            $tot  =$ab->tot;
            $urut =$ab->urut;

            if($skpd!=''){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
            }

            if($urut=='6' || $urut=='8'){
                $kode1="";
                $kode2="";
                $kode3="";
                $kode ="";
                $skpd="";
                $kode4="";
                $kode5="";
                if($urut=='6'){
                    $urai="<i>Hasil </i>: $urai";
                }else if($urut=='8'){
                    $urai="<i>Keluaran </i>: $urai";
                }
            }
            if($urut=='1' || $urut=='2'){
                    $skpd="";
            }
            $tbl.="<tr>
                        <td align='center' width='5%'>$kode1</td>
                        <td align='center' width='5%'>$kode2</td>
                        <td align='center' width='10%'>$skpd</td>
                        <td align='center' width='5%'>$kode3</td>
                        <td align='center' width='5%'>$kode4</td>
                        <td align='center' width='5%'>$kode5</td>
                        <td align='left'   width='15%'>$urai</td>
                        <td align='right' width='10%'>".number_format($b51,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b52,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b53,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b54,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($tot,2,',','.')."</td>                    
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='7'>Jumlah</td>
                        <td align='right'>".number_format($tot51,2,',','.')."</td>
                        <td align='right'>".number_format($tot52,2,',','.')."</td>
                        <td align='right'>".number_format($tot53,2,',','.')."</td>
                        <td align='right'>".number_format($tot54,2,',','.')."</td>
                        <td align='right'>".number_format($tottot,2,',','.')."</td>                    
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>Pontianak, $tgl <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }
    }

}