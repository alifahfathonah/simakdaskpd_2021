<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 
 */

class cetak_angkas_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
  
    function cetak_angkas_ro($tgl,$ttd1,$ttd2,$jenis,$skpd,$giat,$hit,$cret){

        $thn=$this->session->userdata('pcThang');

        $sql=$this->db->query("SELECT nm_skpd from ms_skpd WHERE kd_skpd='$skpd'")->row();
        $cetak="<table border='0', width='100%' style='font-size: 14px'>
                    <tr style='padding:10px'>
                        <td colspan='3' align='center'><b><BR> ANGGARAN KAS KEGIATAN MURNI<br> {$sql->nm_skpd} <br> TAHUN $thn <br></td>
                    </tr>
                </table>";
        $cetak.="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='2'>
                    <thead>
                    <tr>
                        <td width='8%' align='center' rowspan='2'  ><b>Kode</td>
                        <td width='12%'align='center' rowspan='2' ><b>Uraian</td>
                        <td width='8%' align='center' rowspan='2' ><b>Jumlah</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan I (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan II (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan III (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan IV (Rp).</td>                        
                    </tr> 
                    <tr>
                        <td width='6%' align='center'><b>Jan</td>
                        <td width='6%' align='center'><b>Feb</td>
                        <td width='6%' align='center'><b>Mar</td>
                        <td width='6%' align='center'><b>Apr</td>
                        <td width='6%' align='center'><b>Mei</td>
                        <td width='6%' align='center'><b>Jun</td>
                        <td width='6%' align='center'><b>Jul</td>
                        <td width='6%' align='center'><b>Ags</td>
                        <td width='6%' align='center'><b>Sep</td>
                        <td width='6%' align='center'><b>Okt</td>
                        <td width='6%' align='center'><b>Nov</td>
                        <td width='6%' align='center'><b>Des</td>
                    </tr>
                    </thead>";
      

        if(substr($skpd,18,4)=='0000'){
            $filterskpd="left(a.kd_gabungan,22)";
            $skpd="left('$skpd',22)";
        }else{
            $filterskpd="left(a.kd_gabungan,22)";
            $skpd="'$skpd'";
        }


        $sql="
            SELECT $filterskpd kd_skpd, kd_kegiatan giat, '' kd_rek6 , (SELECT nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_kegiatan) nm_giat,
            sum(case when bulan=1 then $jenis else 0 end) as jan,
            sum(case when bulan=2 then $jenis else 0 end) as feb,
            sum(case when bulan=3 then $jenis else 0 end) as mar,
            sum(case when bulan=4 then $jenis else 0 end) as apr,
            sum(case when bulan=5 then $jenis else 0 end) as mei,
            sum(case when bulan=6 then $jenis else 0 end) as jun,
            sum(case when bulan=7 then $jenis else 0 end) as jul,
            sum(case when bulan=8 then $jenis else 0 end) as ags,
            sum(case when bulan=9 then $jenis else 0 end) as sep,
            sum(case when bulan=10 then $jenis else 0 end) as okt,
            sum(case when bulan=11 then $jenis else 0 end) as nov,
            sum(case when bulan=12 then $jenis else 0 end) as des from trdskpd_ro a inner join 
            (select kd_sub_kegiatan oke, left(no_trdrka,22) kd_skpd from trdrka GROUP by kd_sub_kegiatan,left(no_trdrka,22)) b 
            on b.oke=a.kd_kegiatan and left(a.kd_gabungan,22)=b.kd_skpd WHERE 
                        $filterskpd=$skpd and a.kd_kegiatan='$giat'
            GROUP BY kd_kegiatan, $filterskpd 

            UNION ALL

            SELECT $filterskpd kd_skpd, kd_kegiatan giat, '.'+kd_rek6 kd_rek6 , (SELECT nm_rek6 from ms_rek6 where kd_rek6=a.kd_rek6) nm_giat,
            sum(case when bulan=1 then $jenis else 0 end) as jan,
            sum(case when bulan=2 then $jenis else 0 end) as feb,
            sum(case when bulan=3 then $jenis else 0 end) as mar,
            sum(case when bulan=4 then $jenis else 0 end) as apr,
            sum(case when bulan=5 then $jenis else 0 end) as mei,
            sum(case when bulan=6 then $jenis else 0 end) as jun,
            sum(case when bulan=7 then $jenis else 0 end) as jul,
            sum(case when bulan=8 then $jenis else 0 end) as ags,
            sum(case when bulan=9 then $jenis else 0 end) as sep,
            sum(case when bulan=10 then $jenis else 0 end) as okt,
            sum(case when bulan=11 then $jenis else 0 end) as nov,
            sum(case when bulan=12 then $jenis else 0 end) as des from trdskpd_ro a inner join 
            (select kd_sub_kegiatan oke, left(no_trdrka,22) kd_skpd from trdrka GROUP by kd_sub_kegiatan,left(no_trdrka,22)) b 
            on b.oke=a.kd_kegiatan and left(a.kd_gabungan,22)=b.kd_skpd WHERE 
                        $filterskpd=$skpd and a.kd_kegiatan='$giat'
            GROUP BY kd_kegiatan, $filterskpd, kd_rek6 ORDER by kd_kegiatan";


        $aa=0; $b=0; $c=0; $d=0; $e=0; $f=0; $g=0; $h=0; $i=0; $j=0; $k=0; $l=0; $tot=0;        
        $exe=$this->db->query($sql);
        foreach($exe->result() as $a){
            $giat   =$a->giat;
            $rek    =$a->kd_rek6;
            $nm_rek =$a->nm_giat;
            $jan    =$a->jan;
            $feb    =$a->feb;
            $mar    =$a->mar;
            $apr    =$a->apr;
            $mei    =$a->mei;
            $jun    =$a->jun;
            $jul    =$a->jul;
            $ags    =$a->ags;
            $sep    =$a->sep;
            $okt    =$a->okt;
            $nov    =$a->nov;
            $des    =$a->des;

            $jumlah1 =$jan+$feb+$mar+$apr+$mei+$jun+$jul+$ags+$sep+$okt+$nov+$des;
            if($rek==''){
                $aa=$aa+$jan; $g=$g+$jul;
                $b=$b+$feb; $h=$h+$ags;
                $c=$c+$mar; $i=$i+$sep;
                $d=$d+$apr; $j=$j+$okt;
                $e=$e+$mei; $k=$k+$nov;
                $f=$f+$jun; $l=$l+$des;
                $jumlah =$jan+$feb+$mar+$apr+$mei+$jun+$jul+$ags+$sep+$okt+$nov+$des;
                $tot    =$tot+$jumlah;                
            }



            $cetak.="
                    <tr>
                        <td>".$giat.$rek."</td>
                        <td>$nm_rek</td>
                        <td align='right'>".number_format($jumlah1,'2',',','.')."</td>
                        <td align='right'>".number_format($jan,'2',',','.')."</td>
                        <td align='right'>".number_format($feb,'2',',','.')."</td>
                        <td align='right'>".number_format($mar,'2',',','.')."</td>
                        <td align='right'>".number_format($apr,'2',',','.')."</td>
                        <td align='right'>".number_format($mei,'2',',','.')."</td>
                        <td align='right'>".number_format($jun,'2',',','.')."</td>
                        <td align='right'>".number_format($jul,'2',',','.')."</td>
                        <td align='right'>".number_format($ags,'2',',','.')."</td>
                        <td align='right'>".number_format($sep,'2',',','.')."</td>
                        <td align='right'>".number_format($okt,'2',',','.')."</td>
                        <td align='right'>".number_format($nov,'2',',','.')."</td>
                        <td align='right'>".number_format($des,'2',',','.')."</td>
                    </tr>";
        }
        $cetak.="   <tr>
                        <td colspan='2' align='center'><b>Total</td>
                        <td align='right'><b>".number_format($tot,'2',',','.')."</td>
                        <td align='right'><b>".number_format($aa,'2',',','.')."</td>
                        <td align='right'><b>".number_format($b,'2',',','.')."</td>
                        <td align='right'><b>".number_format($c,'2',',','.')."</td>
                        <td align='right'><b>".number_format($d,'2',',','.')."</td>
                        <td align='right'><b>".number_format($e,'2',',','.')."</td>
                        <td align='right'><b>".number_format($f,'2',',','.')."</td>
                        <td align='right'><b>".number_format($g,'2',',','.')."</td>
                        <td align='right'><b>".number_format($h,'2',',','.')."</td>
                        <td align='right'><b>".number_format($i,'2',',','.')."</td>
                        <td align='right'><b>".number_format($j,'2',',','.')."</td>
                        <td align='right'><b>".number_format($k,'2',',','.')."</td>
                        <td align='right'><b>".number_format($l,'2',',','.')."</td>
                    </tr>";
        $cetak.="   <tr>
                        <td colspan='2' align='center'><b>Total Triwulan</td>
                        <td align='right' ><b>".number_format($tot,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($aa+$b+$c,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($d+$e+$f,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($g+$h+$i,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($j+$k+$l,'2',',','.')."</td>
                    </tr>";
        $cetak.="</table>";

        if($hit!="hidden"){ /*if hidden*/
            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd1'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip    = $a->nip; 
                $nama   = $a->nama;
                $jabatan= $a->jabatan;
                $pangkat= $a->pangkat;
            }
            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd2'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip2    = $a->nip;
                $nama2   = $a->nama;
                $jabatan2= $a->jabatan;
                $pangkat2= $a->pangkat;
            }    

            $cetak.="<table width='100%' border='0' style='font-size: 12px'>
                        <tr>
                            <td width='50%' align='center'><br>
                                Mengetahui, <br>
                                $jabatan2
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <b><u>$nama2</u></b><br>
                                NIP. $nip2
                            </td>
                            <td width='50%' align='center'><br>
                                Pontianak, ".$this->support->tanggal_format_indonesia($tgl)." <br>
                                $jabatan
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <b><u>$nama</u></b><br>
                                NIP. $nip
                            </td>
                        </tr>

                    </table>";
        } /*end if hidden*/

        switch ($cret){
            case '1':
                echo ("<title>ANGKAS RO </title>");
                echo "$cetak";
                break;
            case '2':
                $this->master_pdf->_mpdf('',$cetak,10,10,10,'1');        
                break;
            case '3':
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= AngkasRO-$skpd.xls");
                echo "$cetak";
                break;            
        }

    }

    function cetak_angkas_giat($tgl='',$ttd1='',$ttd2='',$jenis='',$skpd='',$ctk='',$hid=''){


        $thn=$this->session->userdata('pcThang');
        $sql=$this->db->query("SELECT nm_skpd from ms_skpd WHERE kd_skpd='$skpd'")->row();
        $cetak="<table border='0', width='100%' style='font-size: 14px'>
                    <tr style='padding:10px'>
                        <td colspan='3' align='center'><b><BR> ANGGARAN KAS KEGIATAN MURNI<br> {$sql->nm_skpd} <br> TAHUN $thn <br></td>
                    </tr>
                </table>";

        $cetak.="<table style='border-collapse: collapse; font-size:12px;' width='100%', border='1', cellspacing='0' cellpadding='2'>
                    <thead>
                    <tr>
                        <td width='8%' align='center' rowspan='2' ><b>Kode</td>
                        <td width='12%'align='center' rowspan='2' ><b>Uraian</td>
                        <td width='8%' align='center' rowspan='2' ><b>Jumlah</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan I (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan II (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan III (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan IV (Rp).</td>                        
                    </tr> 
                    <tr>
                        <td width='6%' align='center'><b>Jan</td>
                        <td width='6%' align='center'><b>Feb</td>
                        <td width='6%' align='center'><b>Mar</td>
                        <td width='6%' align='center'><b>Apr</td>
                        <td width='6%' align='center'><b>Mei</td>
                        <td width='6%' align='center'><b>Jun</td>
                        <td width='6%' align='center'><b>Jul</td>
                        <td width='6%' align='center'><b>Ags</td>
                        <td width='6%' align='center'><b>Sep</td>
                        <td width='6%' align='center'><b>Okt</td>
                        <td width='6%' align='center'><b>Nov</td>
                        <td width='6%' align='center'><b>Des</td>
                    </tr>
                    </thead>";

        if(substr($skpd,18,4)=='0000'){
            $filterskpd="left(a.kd_gabungan,22)";
            $skpd="left('$skpd',22)";
        }else{
            $filterskpd="left(a.kd_gabungan,22)";
            $skpd="'$skpd'";
        }


        $sql="
            SELECT $filterskpd kd_skpd, kd_kegiatan giat, (SELECT nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_kegiatan) nm_giat,
            sum(case when bulan=1 then $jenis else 0 end) as jan,
            sum(case when bulan=2 then $jenis else 0 end) as feb,
            sum(case when bulan=3 then $jenis else 0 end) as mar,
            sum(case when bulan=4 then $jenis else 0 end) as apr,
            sum(case when bulan=5 then $jenis else 0 end) as mei,
            sum(case when bulan=6 then $jenis else 0 end) as jun,
            sum(case when bulan=7 then $jenis else 0 end) as jul,
            sum(case when bulan=8 then $jenis else 0 end) as ags,
            sum(case when bulan=9 then $jenis else 0 end) as sep,
            sum(case when bulan=10 then $jenis else 0 end) as okt,
            sum(case when bulan=11 then $jenis else 0 end) as nov,
            sum(case when bulan=12 then $jenis else 0 end) as des from trdskpd_ro a inner join 
            (select kd_sub_kegiatan oke, left(no_trdrka,22) kd_skpd from trdrka GROUP by kd_sub_kegiatan,left(no_trdrka,22)) b 
            on b.oke=a.kd_kegiatan and left(a.kd_gabungan,22)=b.kd_skpd WHERE left(kd_rek6,1)='5' and
                        $filterskpd=$skpd
            GROUP BY kd_kegiatan, $filterskpd ORDER by kd_kegiatan";

        $aa=0; $b=0; $c=0; $d=0; $e=0; $f=0; $g=0; $h=0; $i=0; $j=0; $k=0; $l=0; $tot=0;
        $exe=$this->db->query($sql);
        foreach($exe->result() as $a){
            $giat   =$a->giat;
            $nm_giat =$a->nm_giat;
            $jan    =$a->jan;
            $feb    =$a->feb;
            $mar    =$a->mar;
            $apr    =$a->apr;
            $mei    =$a->mei;
            $jun    =$a->jun;
            $jul    =$a->jul;
            $ags    =$a->ags;
            $sep    =$a->sep;
            $okt    =$a->okt;
            $nov    =$a->nov;
            $des    =$a->des;

            $aa=$aa+$jan; $g=$g+$jul;
            $b=$b+$feb; $h=$h+$ags;
            $c=$c+$mar; $i=$i+$sep;
            $d=$d+$apr; $j=$j+$okt;
            $e=$e+$mei; $k=$k+$nov;
            $f=$f+$jun; $l=$l+$des;

            $jumlah =$jan+$feb+$mar+$apr+$mei+$jun+$jul+$ags+$sep+$okt+$nov+$des;
            $tot    =$tot+$jumlah;
            $cetak.="
                    <tr>
                        <td>".$giat."</td>
                        <td width='12%'>$nm_giat</td>
                        <td align='right'>".number_format($jumlah,'2',',','.')."</td>
                        <td align='right'>".number_format($jan,'2',',','.')."</td>
                        <td align='right'>".number_format($feb,'2',',','.')."</td>
                        <td align='right'>".number_format($mar,'2',',','.')."</td>
                        <td align='right'>".number_format($apr,'2',',','.')."</td>
                        <td align='right'>".number_format($mei,'2',',','.')."</td>
                        <td align='right'>".number_format($jun,'2',',','.')."</td>
                        <td align='right'>".number_format($jul,'2',',','.')."</td>
                        <td align='right'>".number_format($ags,'2',',','.')."</td>
                        <td align='right'>".number_format($sep,'2',',','.')."</td>
                        <td align='right'>".number_format($okt,'2',',','.')."</td>
                        <td align='right'>".number_format($nov,'2',',','.')."</td>
                        <td align='right'>".number_format($des,'2',',','.')."</td>
                    </tr>";
        }

        $cetak.="   <tr>
                        <td colspan='2' align='center'><b>Total</td>
                        <td align='right'><b>".number_format($tot,'2',',','.')."</td>
                        <td align='right'><b>".number_format($aa,'2',',','.')."</td>
                        <td align='right'><b>".number_format($b,'2',',','.')."</td>
                        <td align='right'><b>".number_format($c,'2',',','.')."</td>
                        <td align='right'><b>".number_format($d,'2',',','.')."</td>
                        <td align='right'><b>".number_format($e,'2',',','.')."</td>
                        <td align='right'><b>".number_format($f,'2',',','.')."</td>
                        <td align='right'><b>".number_format($g,'2',',','.')."</td>
                        <td align='right'><b>".number_format($h,'2',',','.')."</td>
                        <td align='right'><b>".number_format($i,'2',',','.')."</td>
                        <td align='right'><b>".number_format($j,'2',',','.')."</td>
                        <td align='right'><b>".number_format($k,'2',',','.')."</td>
                        <td align='right'><b>".number_format($l,'2',',','.')."</td>
                    </tr>";
        $cetak.="   <tr>
                        <td colspan='2' align='center'><b>Total Triwulan</td>
                        <td align='right' ><b>".number_format($tot,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($aa+$b+$c,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($d+$e+$f,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($g+$h+$i,'2',',','.')."</td>
                        <td align='center' colspan='3' ><b>".number_format($j+$k+$l,'2',',','.')."</td>
                    </tr>";
        $cetak.="</table>";

        if($hid!="hidden"){
            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd1'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip    = $a->nip; 
                $nama   = $a->nama;
                $jabatan= $a->jabatan;
                $pangkat= $a->pangkat;
            }

            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd2'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip2    = $a->nip;
                $nama2   = $a->nama;
                $jabatan2= $a->jabatan;
                $pangkat2= $a->pangkat;
            } 

            $cetak.="<table width='100%' border='0' style='font-size:12px'>
                        <tr>
                            <td width='50%' align='center'><br>
                                Mengetahui, <br>
                                $jabatan2
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <b><u>$nama2</u></b><br>
                                NIP. $nip2
                            </td>
                            <td width='50%' align='center'><br>
                                Pontianak, ".$this->support->tanggal_format_indonesia($tgl)." <br>
                                $jabatan
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <b><u>$nama</u></b><br>
                                NIP. $nip
                            </td>
                        </tr>

                    </table>";
        }

        switch ($ctk){
            case '1':
                echo ("<title>ANGKAS RO </title>");
                echo "$cetak";
                break;
            case '2':
                $this->master_pdf->_mpdf('',$cetak,10,10,10,'1');        
                break;
            case '3':
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= AngkasRO-$skpd.xls");
                echo "$cetak";
                break;            
        }
    }

    function preview_cetakan_cek_anggaran($id,$cetak,$status_ang){
     
     if($status_ang=='nilai'){
        $status="PENYUSUNAN";
     }else if($status_ang=='nilai_sempurna'){
        $status="PERGESERAN";
     }else{
        $status="PERUBAHAN";
     }
        $nama=$this->db->query("SELECT nm_skpd from ms_skpd where kd_skpd='$id'")->row();
        $cRet='';

       $cRet.="<table style='font-size:12px;border-left:solid 0px black;border-top:solid 0px black;border-right:solid 0px black;' width='100%' border='0'>
                    <tr>
                        <td align='center' colspan='5'><b>LAPORAN PERBANDINGAN<br>NILAI ANGGARAN DAN NILAI ANGGARAN KAS $status<br>{$nama->nm_skpd}</b></td>
                        
                    </tr>
                 </table>";



        
        $cRet .= "<table style='border-collapse:collapse;vertical-align:top;font-size:12 px;' width='100%' align='center' border='1' cellspacing='0' cellpadding='1'>

                     <thead >                       
                        <tr>
                            <td bgcolor='#A9A9A9' width='15%' align='center '><b>Kode Kegiatan</b></td>
                            <td bgcolor='#A9A9A9' width='50%' align='center'><b>Nama Kegiatan</b></td>
                            <td bgcolor='#A9A9A9' width='15%' align='center'><b>Nilai Anggaran</b></td>
                            <td bgcolor='#A9A9A9' width='15%' align='center'><b>Nilai Anggaran Kas</b></td>
                            <td bgcolor='#A9A9A9' width='5%' align='center'><b>Hasil</b></td>
                         </tr>
                     </thead>
                     
                   
                        ";

               
              $sort=$this->support->sort($id);
               $sql1="
                    SELECT a.giat kd_kegiatan, a.nama nm_kegiatan, a.nilai_ang, isnull(b.nilai_kas,0) nilai_kas,
                    CASE WHEN isnull(b.nilai_kas,0) = a.nilai_ang THEN 'SAMA' ELSE 'SELISIH' END AS hasil
                                 from (
                select kd_sub_kegiatan giat, nm_sub_kegiatan nama, sum($status_ang) nilai_ang

                 from trdrka where left(no_trdrka,22)='$id' GROUP BY kd_sub_kegiatan,nm_sub_kegiatan)
                a left join (
                select kd_subkegiatan giat, sum($status_ang) nilai_kas from trdskpd_ro where left(kd_gabungan,22)='$id'GROUP BY kd_subkegiatan) b
                on a.giat=b.giat where isnull(b.nilai_kas,0) <> a.nilai_ang
                ORDER BY
                 hasil,a.giat

                ";
                
                $totnilai = 0; 
                $tnilai2 = 0;
                $tselisih = 0;
                $query = $this->db->query($sql1);
                                 
                foreach ($query->result() as $row)
                {
                    $giat=rtrim($row->kd_kegiatan);
                    $nm_giat=rtrim($row->nm_kegiatan);
                    $hasil=rtrim($row->hasil);
                    $nilai_ang=($row->nilai_ang);
                    $nilai_angx = number_format($nilai_ang,2,',','.');
                    $nilai_kas=($row->nilai_kas);
                    $nilai_kasx = number_format($nilai_kas,2,',','.');

                            if($hasil=='SAMA'){


                      $cRet    .= " <tr>                                
                                        <td align='center' style='vertical-align:middle; ' >$giat</td>
                                        <td align='left' style='vertical-align:middle; ' >$nm_giat</td>
                                        <td align='right' style='vertical-align:middle; ' >$nilai_ang</td>
                                        <td align='right' style='vertical-align:middle; ' >$nilai_kas</td>
                                        <td align='center' style='vertical-align:middle; ' >$hasil</td>
                                    </tr> 
                                   
                                    ";
    
                            }else{
                

                      $cRet    .= " <tr>                                
                                        <td bgcolor='#ff5d47' align='center' style='vertical-align:middle;' >$giat</td>
                                        <td bgcolor='#ff5d47' align='left' style='vertical-align:middle;' >$nm_giat</td>
                                        <td bgcolor='#ff5d47' align='right' style='vertical-align:middle;' >$nilai_ang</td>
                                        <td bgcolor='#ff5d47' align='right' style='vertical-align:middle;'>$nilai_kas</td>
                                        <td bgcolor='#ff5d47' align='center' style='vertical-align:middle;'>$hasil</td>
                                    </tr> 
                                   
                                    ";

                            }

                }

 
        $cRet .="</table>";
 
        $data['prev']= $cRet;    
        switch($cetak) {
        case 0;
               echo ("<title>Lap Perbandingan Anggaran</title>");
                echo($cRet);
        break;
        case 1;
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= cek_anggaran.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        
        }    
    }
}