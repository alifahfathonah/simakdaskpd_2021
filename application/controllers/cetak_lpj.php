<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 select_pot_taspen() rekening gaji manual. harap cek selalu
 */

class cetak_lpj extends CI_Controller {

 
    function __construct(){   
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }     

function up(){
        
        $cskpd  = $this->uri->segment(4);
        $ttd1   = str_replace('a',' ',$this->uri->segment(3));
        $ttd2   = str_replace('a',' ',$this->uri->segment(6));
        $ctk =   $this->uri->segment(5);
        $nomor1   = str_replace('abcdefghij','/',$this->uri->segment(7));
        $nomor   = str_replace('123456789',' ',$nomor1);
        $jns =   $this->uri->segment(8);
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);
        $lctgl1 = $this->rka_model->get_nama2($nomor,'tgl_awal','trhlpj','no_lpj','kd_skpd',$cskpd);
        $lctgl2 = $this->rka_model->get_nama2($nomor,'tgl_akhir','trhlpj','no_lpj','kd_skpd',$cskpd);
        $lctglspp = $this->rka_model->get_nama2($nomor,'tgl_lpj','trhlpj','no_lpj','kd_skpd',$cskpd);
        $tanggalx = date('d - M - Y');

        
          
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$cskpd' and (kode='PA' or kode='KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip='Nip. '.$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1='Nip. '.$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        $cRet  =" <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>".strtoupper($jabatan1)."</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .=" <table border='0' style='font-size:12px' width='100%'>
                        <tr>
                            <td align='left' width='10%'>SKPD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                            <td align='center' width='1%'></td>
                            <td align='left' > ".$cskpd." ".$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd')." </td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>PERIODE :</td>
                            <td align='center' width='1%'></td>
                            <td align='left' >".$this->tukd_model->tanggal_format_indonesia($lctgl1).' s/d '.$this->tukd_model->tanggal_format_indonesia($lctgl2)."</td>
                        </tr>
                   </table>             
                ";      

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>KODE REKENING</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>URAIAN</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>4</b></td>
                    </tr>
                    </THEAD>
                ";      
            
                if($jns=='0'){
                $sql = "SELECT 1 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_sub_kegiatan,nm_sub_kegiatan,kd_skpd FROM trskpd GROUP BY kd_sub_kegiatan,nm_sub_kegiatan,kd_skpd)b 
                        ON a.kd_sub_kegiatan =b.kd_sub_kegiatan 
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        UNION ALL
                        SELECT 2 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan 
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        UNION ALL
                        SELECT 3 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, b.nm_rek2 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), b.nm_rek2
                        UNION ALL
                        SELECT 4 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,3) as kode, b.nm_rek3 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,3)=b.kd_rek3
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,3), b.nm_rek3
                        UNION ALL
                        SELECT 5 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,5) as kode, b.nm_rek4 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,5)=b.kd_rek4
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,5), b.nm_rek4
                        UNION ALL
                        SELECT 6 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, nm_rek6 as uraian, SUM(nilai) as nilai FROM trlpj
                        WHERE no_lpj='$nomor' AND kd_skpd='$cskpd'
                        GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
                        ORDER BY kode";     
                $query1 = $this->db->query($sql); 
                $total=0;
                $i=0;
                foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
                    
                    if ($urut==1){
                    $i=$i+1;    
                        $cRet .="<tr>
                                    <td valign='top' align='center' ><i><b>$i</b></i></td>
                                    <td valign='top' align='left' ><i><b>$kode</b></i></td>
                                    <td valign='top' align='left' ><i><b>$uraian</b></i></td>
                                    <td valign='top' align='right'><i><b>".number_format($nilai,"2",",",".")."</b></i></td>
                                </tr>";
                    } else if ($urut==2){
                            $cRet .="<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
                                </tr>";
                    }else if ($urut==6){
                            $total=$total+$nilai;
                            $cRet .="<tr>
                                    <td valign='top' align='center' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right'>".number_format($nilai,"2",",",".")."</td>
                                </tr>";
                    }
                    else{
                        $cRet .="<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >".number_format($nilai,"2",",",".")."</td>
                                </tr>"; 
                    }

                }
                } else{
                $sql = "SELECT 1 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_sub_kegiatan,nm_sub_kegiatan,kd_skpd FROM trskpd GROUP BY kd_sub_kegiatan,nm_sub_kegiatan,kd_skpd)b 
                        ON a.kd_sub_kegiatan =b.kd_sub_kegiatan  and a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        UNION ALL
                        SELECT 2 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan  and a.kd_skpd=b.kd_spd
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        ORDER BY kode";     
                $query1 = $this->db->query($sql); 
                $total=0;
                $i=0;
                foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
                    
                    if ($urut==1){
                    $i=$i+1;    
                        $cRet .="<tr>
                                    <td valign='top' align='center' ><b>$i</b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
                                </tr>";
                    } else{
                        $total=$total+$nilai;
                        $cRet .="<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >".number_format($nilai,"2",",",".")."</td>
                                </tr>"; 
                    }

                }   
                }


                $sqlp = " SELECT SUM(a.nilai) AS nilai FROM trdspp a LEFT JOIN trhsp2d b ON b.no_spp=a.no_spp  
                          WHERE b.kd_skpd='$cskpd' AND (b.jns_spp=1)";
                $queryp = $this->db->query($sqlp);      
                foreach($queryp->result_array() as $nlx){ 
                        $persediaan=$nlx["nilai"];
                }

                $cRet .="
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>".number_format($total,"2",",",".")."</b></td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Awal Periode</b></td>
                            <td align='right' ><b>".number_format($persediaan,"2",",",".")."</b></td>
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Ahir Periode</b></td>
                            <td align='right' ><b>".number_format($persediaan-$total,"2",",",".")."</b></td>
                        </tr>
                        </tr>
                        ";


                $cRet .="</table><p>";              
//.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .=" <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Mengetahui <br> $jabatan    </td>
                        <td valign='top' align='center' width='50%'>$daerah, $tanggalx <br> $jabatan1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'><b><u>$nama1</u></b><br>$pangkat1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$nip</td>
                        <td align='center' width='50%'>$nip1</td>
                    </tr>
                  </table>
                ";      

        $data['prev']= $cRet; 

        switch ($ctk)
        {
            case 0;
               echo ("<title> LPJ UP</title>");
                echo $cRet;     
                break;
            case 1;
                $this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'0',1,'',$atas,$bawah,$kiri,$kanan);
               break;
        }
    }

    function up_rinci(){
        
        $cskpd  = $this->uri->segment(4);
        $ttd1   = str_replace('a',' ',$this->uri->segment(3));
        $ttd2   = str_replace('a',' ',$this->uri->segment(6));
        $ctk =   $this->uri->segment(5);
        $nomor   = str_replace('abcdefghij','/',$this->uri->segment(7));
        $nomor   = str_replace('123456789',' ',$nomor);
        $kegiatan =   $this->uri->segment(8);
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);

        $lctgl1 = $this->tukd_model->get_nama($nomor,'tgl_awal','trhlpj','no_lpj');
        $lctgl2 = $this->tukd_model->get_nama($nomor,'tgl_akhir','trhlpj','no_lpj');
        $lctglspp = $this->tukd_model->get_nama($nomor,'tgl_lpj','trhlpj','no_lpj');

          
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$cskpd' and (kode='PA' or kode='KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip='Nip. '.$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1='Nip. '.$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
        $cRet  =" <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>".strtoupper($jabatan1)."</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .=" <table border='0' style='font-size:12px' width='100%'>
                        <tr>
                            <td align='left' width='10%'>SKPD&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='1%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' > ".$cskpd." ".$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd')." </td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>PERIODE&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='1%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' >".$this->tukd_model->tanggal_format_indonesia($lctgl1).' s/d '.$this->tukd_model->tanggal_format_indonesia($lctgl2)."</td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>Kegiatan&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='1%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' >$kegiatan - nama</td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='1%'>&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' ></td>
                        </tr>
                   </table>             
                ";      

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>KODE REKENING</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='60%'><b>URAIAN</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='60%'><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>4</b></td>
                    </tr>
                    </THEAD>
                ";      
            
                
                $sql = "SELECT 1 as urut, a.kd_sub_kegiatan as kode, a.kd_sub_kegiatan as rek, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan 
                        and a.kd_skpd=b.kd_skpd
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, LEFT(a.kd_rek6,2) as rek,  nm_rek2 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), nm_rek2
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,3) as kode, LEFT(a.kd_rek6,3) as rek,  nm_rek3 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,3)=b.kd_rek3
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,3), nm_rek3
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,5) as kode, LEFT(a.kd_rek6,5) as rek,  nm_rek4 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,5)=b.kd_rek4
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,5), nm_rek4
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, kd_rek6 as rek,  nm_rek6 as uraian, SUM(nilai) as nilai FROM trlpj a
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
                        UNION ALL
                        SELECT 3 as urut, a.kd_sub_kegiatan+'.'+a.kd_rek6+'.1' as kode,'' as rek, 'No BKU: '+a.no_bukti as uraian, a.nilai as nilai
                        FROM trlpj a 
                        INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY a.kd_sub_kegiatan, a.kd_rek6, nm_rek6,a.nilai,a.no_bukti
                        ORDER BY kode";     
                $query1 = $this->db->query($sql); 
                $total=0;
                $i=0;
                foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $rek=$row->rek;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
                    
                    if ($urut==1){
                    $i=$i+1;    
                        $cRet .="<tr>
                                    <td valign='top' align='center' ><i><b>$i</b></i></td>
                                    <td valign='top' align='left' ><i><b>$kode</b></i></td>
                                    <td valign='top' align='left' ><i><b>$uraian</b></i></td>
                                    <td valign='top' align='right'><i><b>".number_format($nilai,"2",",",".")."</b></i></td>
                                </tr>";
                    } else if ($urut==2){
                            $cRet .="<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
                                </tr>";
                    }else{
                        $total=$total+$nilai;
                        $cRet .="<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$rek</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >".number_format($nilai,"2",",",".")."</td>
                                </tr>"; 
                    }

                }
                

                $cRet .="
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>".number_format($total,"2",",",".")."</b></td>
                        </tr>                   
                        
                        ";


                $cRet .="</table><p>";              
//.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .=" <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Mengetahui <br> $jabatan    </td>
                        <td valign='top' align='center' width='50%'>$daerah, ".$this->tukd_model->tanggal_format_indonesia($lctglspp)." <br> $jabatan1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'><b><u>$nama1</u></b><br>$pangkat1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$nip</td>
                        <td align='center' width='50%'>$nip1</td>
                    </tr>
                  </table>
                ";      

        $data['prev']= $cRet; 

        switch ($ctk)
        {
            case 0;
               echo ("<title> LPJ UP</title>");
                echo $cRet;     
                break;
            case 1;
                $this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'0',1,'',$atas,$bawah,$kiri,$kanan);
                //$this->_mpdf('',$cRet,10,10,10,'0',0,'');
               break;
        }
    }
 }