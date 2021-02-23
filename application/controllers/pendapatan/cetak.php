<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class cetak extends CI_Controller
{

    function __construct()
    {
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
    }



    function setor(){
        $lcnosts = str_replace('123456789','/',$this->uri->segment(4));
        $lcttd2 = str_replace('a',' ',$this->uri->segment(6));
        $lcttd1 = str_replace('a',' ',$this->uri->segment(5));
		$kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $cRet='';
        $lcpemda = $kab;
		$sqlttd1="SELECT distinct isnull(nama,'') as nm,isnull(nip,'') as nip,isnull(jabatan,'') as jab, isnull(pangkat,'') as pangkat FROM ms_ttd WHERE id_ttd ='$lcttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;  
                    $pangkat=$rowttd->pangkat;  
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                }
				
		$sqlttd2="SELECT distinct isnull(nama,'') as nm, isnull(nip,'') as nip, isnull(jabatan,'') as jab, isnull(pangkat,'') as pangkat FROM ms_ttd WHERE id_ttd='$lcttd2'";
                 $sqlttd2=$this->db->query($sqlttd2);
                 foreach ($sqlttd2->result() as $rowttd2)
                {
                    $nip2=$rowttd2->nip; 
                    $pangkat2=$rowttd2->pangkat;  
                    $nama2= $rowttd2->nm;
                    $jabatan2  = $rowttd2->jab;
                }
        $sql = "SELECT a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
                (SELECT nama FROM ms_bank WHERE kode = a.kd_bank) AS nm_bank
                FROM trhkasin_pkd a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();

        $rupiah = $this->tukd_model->terbilang($trh->total);
        $lcbank = $trh->nm_bank;
        $lcrek = $trh->rek_bank;
        $lcskpd = $trh->nm_skpd;
        $lctglsts = $trh->tgl_sts;
		$jns_bank = $trh->bank;
        
        if($jns_bank=="TN"){
            $jns_bank2="TUNAI";
        }else{
            $jns_bank2="BANK";
        }
		
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
                        <td width='10%'>No STS</td>
                        <td width='50%'>: $lcnosts</td>
                       <td width='10%'>JENIS</td>
                        <td width='40%'>: $jns_bank2 $lcbank</td>
                    </tr>
                    <tr>
                        <td width='10%'>SKPD</td>
                        <td width='50%'>: $lcskpd</td>
                        <td width='10%'>No Rekening</td>
                        <td width='40%'>: 1001002830</td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td>
                <table  style='border-collapse:collapse;' width='100%' align='left' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='30%'>Harap diterima uang sebesar <br>(dengan huruf)</td>
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
                <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Kode Rekening</b></td>
                <td width='48%' bgcolor='#CCCCCC' align='center'><b>Uraian Rincian Objek</b></td>
                <td width='50%' bgcolor='#CCCCCC' align='center'><b>Jumlah</b></td>
              </tr>";
           
           $sql = "SELECT a.*,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6 = a.kd_rek6) AS nm_rek5
                    FROM trdkasin_pkd a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        foreach ($hasil->result() as $row)
        {
           $lntotal = $lntotal + $row->rupiah;     
           $lcno = $lcno + 1;
           $cRet .=" <tr>
                        <td align='center'>$lcno</td>
                        <td width='15%' colspan='5'>".$row->kd_rek6."</td>
                        <td>$row->nm_rek5</td>
                        <td align='right'>".number_format($row->rupiah)."</td>
                      </tr>";     
            
        }
            $cRet .="
            <tr>
                <td colspan='7' align='right'>Jumlah</td>                
                <td align='right'>".number_format($lntotal)."</td>
                
            </tr>
            </table>
            </td>
          </tr>
		  
          <tr>
            <td height='30' align='center' style='font-size:12px'>Uang tersebut diterima pada tanggal ".$this->tukd_model->tanggal_format_indonesia($lctglsts)."</td>
          </tr>
          <tr>
            <td height='60' align='center'></td>
          </tr>
          <tr>
            <td height='56'>
                <table style='border-collapse:collapse;' width='700' align='center' border='0' cellspacing='0' cellpadding='0'>
                  <tr>
                    <td width='50%' align='center' style='font-size:14px'><b>Mengetahui<br>$jabatan</b></td>
                    <td width='50%' align='center' style='font-size:14px'><b>$jabatan2</b></td>
                  </tr>
                  <tr>
                  <td height='60' colspan ='2' ></td>
                  </tr>
                  <tr>
                    <td width='50%' align='center' style='font-size:14px'><b>$nama<br>NIP.$nip</b></td>
                    <td width='50%' align='center' style='font-size:14px'><b>$nama2<br>NIP.$nip2</b></td>
                  </tr>                  
                </table>
            </td>
          </tr>
        </table>";

        $data['prev']= $cRet;    
        $this->master_pdf->_mpdf('',$cRet,'10','10',5,'0');
        
    }


    function setor_cms(){
        //$b = $this->uri->segment(3);
        $lcnosts = str_replace('123456789','/',$this->uri->segment(4));
        $lcttd2 = str_replace('a',' ',$this->uri->segment(6));
        $lcttd1 = str_replace('a',' ',$this->uri->segment(5));
    $kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $cRet='';
        $lcpemda = $kab;
    $sqlttd1="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE id_ttd ='$lcttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;  
                    $pangkat=$rowttd->pangkat;  
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                }
        
    $sqlttd2="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE id_ttd='$lcttd2'";
                 $sqlttd2=$this->db->query($sqlttd2);
                 foreach ($sqlttd2->result() as $rowttd2)
                {
                    $nip2=$rowttd2->nip; 
                    $pangkat2=$rowttd2->pangkat;  
                    $nama2= $rowttd2->nm;
                    $jabatan2  = $rowttd2->jab;
                }
        $sql = "SELECT a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
                (SELECT nama FROM ms_bank WHERE kode = a.kd_bank) AS nm_bank
                FROM trhkasin_pkd_cms a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();

        $rupiah = $this->tukd_model->terbilang($trh->total);
        $lcbank = $trh->nm_bank;
        $lcrek = $trh->rek_bank;
        $lcskpd = $trh->nm_skpd;
        $lctglsts = $trh->tgl_sts;
    $jns_bank = $trh->bank;
        
        if($jns_bank=="TN"){
            $jns_bank2="TUNAI";
        }else{
            $jns_bank2="BANK";
        }
    
        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                     <thead>
                        <tr><td colspan='2' style='text-align:center;border: solid 1px white;'>$lcpemda</td></tr>
                        <tr><td colspan='2' style='text-align:center;border: solid 1px white;'>SURAT TANDA SETORAN</td></tr>
                        <tr><td colspan='2' style='text-align:center;border: solid 1px white;border-bottom:solid 1px black;'>(STS)</td></tr>
                     </thead></table><br>";       
              
        
     
        $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td>
                <table  style='border-collapse:collapse;' width='100%' align='left' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='10%'>No STS</td>
                        <td width='50%'>: $lcnosts</td>
                        <td width='15%'>JENIS</td>
                        <td width='25%'>: $jns_bank2 $lcbank</td>
                    </tr>
                    <tr>
                        <td width='10%'>SKPD</td>
                        <td width='50%'>: $lcskpd</td>
                        <td width='15%'>No Rekening</td>
                        <td width='25%'>: 1001002830</td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td>
                <table  style='border-collapse:collapse;' width='100%' align='left' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='30%'>Harap diterima uang sebesar <br>(dengan huruf)</td>
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
                <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Kode Rekening</b></td>
                <td width='48%' bgcolor='#CCCCCC' align='center'><b>Uraian Rincian Objek</b></td>
                <td width='50%' bgcolor='#CCCCCC' align='center'><b>Jumlah</b></td>
              </tr>";
           
            $sql = "SELECT a.*,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6 = a.kd_rek6) AS nm_rek5
                    FROM trdkasin_pkd_cms a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        foreach ($hasil->result() as $row)
        {
           $lntotal = $lntotal + $row->rupiah;     
           $lcno = $lcno + 1;
           $cRet .=" <tr>
                        <td align='center'>$lcno</td>
                        <td width='15%' colspan='5'>".$row->kd_rek5."</td>
                        <td>$row->nm_rek5</td>
                        <td align='right'>".number_format($row->rupiah,2)."</td>
                      </tr>";     
            
        }
            $cRet .="
            <tr>
                <td colspan='7' align='right'>Jumlah</td>                
                <td align='right'>".number_format($lntotal,2)."</td>
                
            </tr>
            </table>
            </td>
          </tr>
      
          <tr>
            <td height='30' align='center' style='font-size:12px'>Uang tersebut diterima pada tanggal ".$this->tukd_model->tanggal_format_indonesia($lctglsts)."</td>
          </tr>
          <tr>
            <td height='60' align='center'></td>
          </tr>
          <tr>
            <td height='56'>
                <table style='border-collapse:collapse;' width='700' align='center' border='0' cellspacing='0' cellpadding='0'>
                  <tr>
                    <td width='50%' align='center' style='font-size:14px'><b>Mengetahui<br>$jabatan</b></td>
                    <td width='50%' align='center' style='font-size:14px'><b>$jabatan2</b></td>
                  </tr>
                  <tr>
                  <td height='60' colspan ='2' ></td>
                  </tr>
                  <tr>
                    <td width='50%' align='center' style='font-size:14px'><b>$nama<br>NIP.$nip</b></td>
                    <td width='50%' align='center' style='font-size:14px'><b>$nama2<br>NIP.$nip2</b></td>
                  </tr>                  
                </table>
            </td>
          </tr>
        </table>";

        $data['prev']= $cRet;    

       $this->master_pdf->_mpdf('',$cRet,'10','10',5,'0');
        
    }
}