<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_spp extends CI_Controller {

 
    function __construct()
    {
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }
    } 

   
    function reg_spp(){
        $data['page_title']= 'REGISTER S P P';
        $this->template->set('title', 'REGISTER S P P');   
        $this->template->load('template','tukd/register/spp',$data) ; 
    }

    function cetakspp1(){
		$spasi = $this->uri->segment(10); 
		$print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
                
        $nomor = str_replace('123456789','/',$this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');			
	   
		
        $kodepos = $this->rka_model->get_nama($kd,'kodepos','ms_skpd','kd_skpd');
			if($kodepos==''){
				$kodepos = "-------";
			} else {
				$kodepos = "$kodepos";
			}
		
		$kd_cek = substr($kd,18,4);	
		if($kd_cek!='0000'){
			$kodeBK = 'BPP';
		}else{
			$kodeBK = 'BK';
		}	
			
        $BK = str_replace('123456789',' ',$this->uri->segment(7));
        $PPTK = str_replace('123456789',' ',$this->uri->segment(8));
		$sqlttd1="SELECT top 1 nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$BK'";
		 $sqlttd=$this->db->query($sqlttd1);
		 foreach ($sqlttd->result() as $rowttd)
		{
			$nip=$rowttd->nip;                    
			$nama= $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		
		if($PPTK=='-'){
			$jdl2 = '';
			$nip2='';
            $mengehui="";                 
			$nama2= '';
			$jabatan2  = '';
			$pangkat2  = '';
		}else{
			 $sqlttd2="SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id_ttd='$PPTK'";

			$sqlttd2=$this->db->query($sqlttd2);
			foreach ($sqlttd2->result() as $rowttd2)
			{
                $mengehui = 'MENGETAHUI :';
				$jdl2 = 'MENGETAHUI :';
				$nip2 = "NIP. ".$rowttd2->nip."";                    
				$nama2= $rowttd2->nm2;
				$jabatan2  = $rowttd2->jab;
				$pangkat2  = $rowttd2->pangkat;
			}
		}
		
		$tgl_spp=$this->rka_model->get_nama($nomor,'tgl_spp','trhspp','no_spp');
        $no_spd=$this->rka_model->get_nama($nomor,'no_spd','trhspp','no_spp');
		$tglspd =$this->rka_model->get_nama($no_spd,'tgl_spd','trhspd','no_spd'); 
		
		$jnss_spp = $this->rka_model->get_nama($nomor,'jns_spp','trhspp','no_spp');
		$jnss_beban = $this->rka_model->get_nama($nomor,'jns_beban','trhspp','no_spp');
		
		$sqlgiat="SELECT kd_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_kegiatan";
		 $sqlgiat=$this->db->query($sqlgiat);
		 foreach ($sqlgiat->result() as $rowgiat){
			$giatspp=$rowgiat->kd_kegiatan;                    
		}
		$giatspp = empty($giatspp) || $giatspp == '' ? '' : $giatspp;
        
	
        $jenis=explode("/",$nomor);
        switch ($jenis[2]) {
            case 'UP':
                $lcbeban="UP";
                $judul  ="UANG PERSEDIAAN";
                break;
            case 'GU':
                $lcbeban="GU";
                $judul  ="GANTI UANG";
                break;
            case 'TU':
                $lcbeban="TU";
                $judul  ="TAMBAH UANG";
                break; 
            case 'LS-GJ':
                $lcbeban="LS-GJ";
                $judul  ="LANGSUNG GAJI DAN TUNJANGAN";
                break;   
            case 'LS':
                $lcbeban="LS";
                $judul  ="LANGSUNG";
                break;          
        }
	 

        if(($jnss_spp==4) && ($jnss_beban==9)){
            $jabatan9 = "Pihak Ketiga";
           // $nama9 = $rekan;

                                $filterbank="    no_rek AS no_rek,
                    npwp AS npwp,";
            $jabatan9 = "Pihak Ketiga";
        }else if(($jnss_spp==6) && ($jnss_beban==2)){
            
                                $filterbank="    no_rek AS no_rek,
                    npwp AS npwp,";
            $jabatan9 = "Pihak Ketiga";
           // $nama9 = $rekan;
        }else if(($jnss_spp==6) && ($jnss_beban==3)){
            $jabatan9 = "Pihak Ketiga";
            //$nama9 = $rekan;
            
                                $filterbank="    no_rek AS no_rek,
                    npwp AS npwp,";
            $jabatan9 = "Pihak Ketiga";
        }else{
            $jabatan9 = $jabatan;
           // $nama9 = $nama;

            $filterbank="                   (SELECT rekening FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS no_rek,
                    (SELECT npwp FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS npwp,";
        }

		$sqlrek="SELECT TOP 1 kd_rek6 FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd' ORDER BY kd_rek6";
                 $sqlrek=$this->db->query($sqlrek);
                 foreach ($sqlrek->result() as $rowrek){
                    $xrekspd     = $rowrek->kd_rek6;
                }
		$rekspd1=$this->support->left($xrekspd,5);

			
			 $sql1="SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank,
                    $filterbank
					a.no_spd,
					SUM(z.nilai) as nilai,
					(SELECT SUM(a.nilai) FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.kd_skpd='$kd'
					and b.tgl_spd <='$tglspd' ) AS spd,
					(SELECT ISNULL(SUM(b.nilai),0) FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
					INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
					AND a.jns_spp='4' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp') AS spp 
					FROM trhspp a INNER JOIN trdspp z on a.no_spp = z.no_spp and a.kd_skpd = z.kd_skpd
					INNER JOIN ms_bidang_urusan b 
					ON replace(left(a.kd_skpd,4),'-','.')=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'
					GROUP BY a.no_spp,
					a.tgl_spp, a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan,a.no_rek,a.npwp,
					a.jns_beban,b.kd_bidang_urusan,b.nm_bidang_urusan,a.bank,a.no_spd";
			
                $query = $this->db->query($sql1);
                foreach ($query->result() as $row){
                    $kd_urusan=$row->kd_bidang_urusan;
                    $nm_urusan=$row->nm_bidang_urusan;
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $spd=$row->no_spd;
                    $tgl=$row->tgl_spp;
                    $jns_bbn=$row->jns_beban;
                    $no_rek=$row->no_rek;
					$npwp = $row->npwp;
					$rekan = $row->nmrekan;
                    $nama9 = $row->nmrekan;
                    $tanggal = $this->support->tanggal_format_indonesia($tgl);
                    $bln = $this->support->getBulan($row->bulan);                    
                    $nilaispp=number_format($row->nilai,"2",",",".");
                    $nilai1=$row->nilai;
                    $nspd=$row->spd;
                    $spp=$row->spp;
                    $sis=$nspd-$spp;
					$ju=$spp+$nilai1;
                    $si=$nspd-$ju;
                    if ($si < 0){
                	$x1="(";
                	$si=$si*-1;
                	$y1=")";}
                    else {
                	$x1="";
	                $y1="";}
                    $sisa=number_format($si,"2",",",".");
                    $a=$this->tukd_model->terbilang($nilai1);
                    $b=$this->tukd_model->terbilang($sis);                    
                    //echo($a);
                }
				
				

				
        $kodebank = $this->rka_model->get_nama($nomor,'bank','trhspp','no_spp');
		$nama_bank = $this->db->query("SELECT isnull(nama,'') nama from ms_bank where kode='$kodebank'")->row()->nama;

        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $nogub_susun  = $rowsc->nogub_susun;
                    $nogub_perubahan  = $rowsc->nogub_perubahan;
                }
		$stsubah=$this->rka_model->get_nama($kd,'status_ubah','trhrka','kd_skpd');
		if($stsubah==1){
			$nogub=$nogub_perubahan;
		}else{
			$nogub=$nogub_susun;
		}
       
        $thn_ang	   = $this->session->userdata('pcThang');
		if($tanpa==1) {
			$tanggal ="_______________________$thn_ang";
			}

		$unit=$this->support->right($kd,2);
		if($unit=='01' || $kd=='4.02.02.00'){
		$peng='Pengguna Anggaran';
		} else{
		$peng='Kuasa Pengguna Anggaran';
		}
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
						<td rowspan='4' width='10%' align='left'></td>
                        <img src='".base_url()."image/simakdaskpd_2016.png' width='75' height='75'>
						<td align='center' width='90%' style='font-size:14px'><strong>PEMERINTAH KOTA PONTIANAK</strong></td>
					</tr>
                    <tr>
                    	<td align='center' style='font-size:13px'>$nm_skpd </td>
                    </tr>
					<tr>
						<td align='center' style='font-size:12px'>$alamat_skpd</td>
					</tr>
                    <tr>
                    	<td align='center'>".strtoupper($daerah)." </td>	
                    </tr>
					</table>
					<hr  width='100%'> 
					";
		
	   	
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
					<tr><td align='center' style='font-size:12px'><strong>SURAT PERMINTAAN PEMBAYARAN $judul</strong></td></tr>
                    <tr><td align='center' style='font-size:12px'><strong>(SPP - ".strtoupper($lcbeban).")</strong></td></tr>
                    <tr><td align='center' style='font-size:12px'><strong><u>SURAT PENGANTAR</u></strong></td></tr>
                    <tr><td align='center'><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='left'>Kepada Yth:</td></tr>
                    <tr><td align='left'>$peng</td></tr>
                    <tr><td align='left'>SKPD : $nm_skpd</td></tr>
                    <tr><td align='left'>Di <strong><u>".strtoupper($daerah)."</u></strong></td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='justify'>Dengan memperhatikan Peraturan Walikota Kota Pontianak $nogub, 
                    tentang Penjabaran APBD. Bersama ini kami mengajukan Surat Permintaan Pembayaran Langsung Barang dan Jasa sebagai berikut:</td></tr>
                    <tr><td align='center'></td></tr>
                  </table>";

        $cRet .= "<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='$spasi'>
                     
                        ";
                            
                                  
                     $cRet    .= " <tr>
                                        <td width='2%' align='center'>a.</td>                                     
                                        <td width='38%'>Urusan Pemerintahan</td>
                                        <td width='2%'>:</td>
                                        <td width='18%'>$kd_urusan - $nm_urusan</td>
                                        <td width='40%' align='right'></td>
                                     </tr>
                                     ";
                    $cRet    .= " <tr>
                                        <td width='2%' align='center'>b.</td>                                     
                                        <td width='38%'>SKPD</td>
                                        <td width='2%'>:</td>
                                        <td colspan='2' width='18%'>$kd_skpd - $nm_skpd</td>
                                     
                                     </tr>
                                     ";
                    $cRet    .= " <tr>
                                        <td width='2%' align='center'>c.</td>                                     
                                        <td width='38%'>Tahun Anggaran</td>
                                        <td width='2%'>:</td>
                                        <td width='18%'>$thn_ang</td>
                                        <td width='40%' align='right'></td>
                                     </tr>
                                     ";
                    	 $sql1="
							SELECT z.* from (
                            SELECT '1' as kode,a.no_spd, b.tgl_spd, SUM(a.nilai_final) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd 
							WHERE b.jns_beban = '5' and b.status='1' and left(b.kd_skpd,22)=left('$kd',22)							
							and b.tgl_spd <='$tgl_spp' 
                            GROUP BY a.no_spd, b.tgl_spd) z order by kode
							";
                    $query = $this->db->query($sql1);
                    $lcno = 0;
                    $lntotal = 0;
                    foreach ($query->result() as $row)
                    {
                        $lcno = $lcno + 1;
                        $lntotal = $lntotal + $row->nilai;
                        $totalspd = number_format($lntotal,"2",".",",");
                        
                        $no=$row->no_spd;
                        $tgl=$row->tgl_spd;
                        $tgl_spd = $this->support->tanggal_format_indonesia($tgl);
                        $nilai=number_format($row->nilai,"2",".",",");                    
                           $cRet    .= " <tr>
                                             <td width='2%' align='center'></td>                                     
                                             <td width='38%'></td>
                                             <td width='2%'></td>
                                             <td width='18%'>$no</td>
                                             <td width='40%' align='right'></td>
                                         </tr>
                                         ";
                    }
                    
					$sqlsppttu="SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
								INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE left(a.kd_skpd,22)=left('$kd',22) 								
                                AND a.jns_spp IN ('1','2','3','4','5','6') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
                    $sqlspptu=$this->db->query($sqlsppttu);
                    foreach ($sqlspptu->result() as $row)
                    {
                    $jns3     = $row->nilai;
                    $jns3_    = number_format($jns3,"2",".",",");                   
                    } 
                    $jmlblj = $jns3;
                    $totblj = number_format($jmlblj,"2",".",",");
                    $sisa   = $lntotal - $jmlblj;
                    
                    $sisaspp   =number_format($sisa,"2",".",",");				 
									 
                   $bilangan=$this->tukd_model->terbilang($sisa);
                   
                     $cRet    .= " <tr>
                                     <td width='2%' align='center'>e.</td>                                     
                                     <td width='38%'>Jumlah Sisa Dana SPD</td>
                                     <td width='2%'>:</td>
                                     <td width='18%'>Rp. $x1$sisaspp$y1 </td>
                                     <td width='40%' align='right'></td>
                                     </tr>
                                     ";
                    $cRet    .= " <tr>
                                        <td colspan ='2' width='40%' align='center'>(terbilang)</td>
                                        <td width='2%'>:</td>
                                        <td colspan ='2' width='58%'><i>( ".ucwords($bilangan).")</i></td>
                                  </tr>
                                 ";
                				 
                                    
                    $cRet    .= " <tr>
                                        <td width='2%' align='center'>f.</td>                                     
                                        <td width='38%'>Untuk Keperluan Bulan</td>
                                        <td width='2%'>:</td>
                                        <td width='18%'>$bln</td>
                                        <td width='40%' align='right'></td>
                                    </tr>
                                     ";
                    $cRet    .= " <tr>
                                        <td width='2%' align='center'>g.</td>                                     
                                        <td width='38%'>Jumlah Pembayaran yang Diminta</td>
                                        <td width='2%'>:</td>
                                        <td width='18%'>Rp. $nilaispp</td>
                                        <td width='40%' align='right'></td>
                                    </tr>
                                     ";                    
                    $cRet    .= " <tr>
                                        <td colspan ='2' width='40%' align='center'>(terbilang)</td>
                                        <td width='2%'>:</td>
                                        <td colspan ='2' width='58%'><i>( ".ucwords($a).")</i></td>
                                        </tr>
                                    ";                    
                                     
                     $cRet    .= " <tr>
                                        <td width='2%' align='center'>h.</td>                                     
                                        <td width='38%'>Nama ".ucwords($jabatan9)."</td>
                                        <td width='2%'>:</td>
                                        <td colspan = '2' width='58%'>$nama9</td>
                                    </tr>
                                     ";
                      $cRet    .= " <tr>
                                        <td width='2%' align='center'>i.</td>                                     
                                        <td width='38%'>Nama, Nomor Rekening Bank dan NPWP</td>
                                        <td width='2%'>:</td>
                                        <td colspan = '2' width='58%'>$nama_bank / $no_rek / $npwp</td>
                                    </tr>
                                     ";

        $cRet .=       " </table>";
        switch ($jns) {
            case 6:
                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$mengehui</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
            case 3:
                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$mengehui</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
            
            default:
                    $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$daerah, $tanggal</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$jabatan</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>                              
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'><b><u>$nama </u></b><br>
                         $pangkat <br>
                         NIP. $nip</td>
                     </tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>"; 
                break;
        }
     
                    
      
            $data['prev']= $cRet;
			if($print=='1'){
			$this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,''); 
			}else
			if($print=='0'){
			echo $cRet;
			}
			else
			if($print=='2'){
			$this->_mpdf_word('',$cRet,10,10,10,'0',1,''); 
			}
			if($print=='3'){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= pengantar_spp_ls".$kd.".xls");
            $this->load->view('anggaran/rka/perkadaII', $data);			
			}
	         
    }


    function cetakspp2(){
        $spasi = $this->uri->segment(10);
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $nomor = str_replace('123456789','/',$this->uri->segment(4));
        $tgl_spp=$this->rka_model->get_nama($nomor,'tgl_spp','trhspp','no_spp');
        $tanggal = $this->support->tanggal_format_indonesia($tgl_spp);
        $nm_skpd=$this->rka_model->get_nama($kd,'nm_skpd','ms_skpd','kd_skpd');
        $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');        
        $kodepos = $this->rka_model->get_nama($kd,'kodepos','ms_skpd','kd_skpd');
            if($kodepos==''){
                $kodepos = "-------";
            } else {
                $kodepos = "$kodepos";
            }
        $cekk = substr($kd,18,4);
        if($cekk!='0000'){
            $cekBP = 'BPP';
            $tipebendahara="Bendahara Pengeluaran Pembantu";
        }else{
            $cekBP = 'BK';
            $tipebendahara="Bendahara Pengeluaran";
        }    
            
        $mengehui="";    
        $BK = str_replace('123456789',' ',$this->uri->segment(7));
        $PPTK = str_replace('123456789',' ',$this->uri->segment(8));
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$BK'";
         $sqlttd=$this->db->query($sqlttd1);
         foreach ($sqlttd->result() as $rowttd)
        {
            $nip=$rowttd->nip;                    
            $nama= $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        
        if($PPTK=='-'){
            $nip2='';                    
            $nama2= '';
            $jabatan2  = '';
            $pangkat2  = '';
            $jdl2 = '';
        }else{
            $sqlttd2="SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id_ttd='$PPTK'";
            $sqlttd2=$this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2){
                $nip2=$rowttd2->nip;                    
                $nama2= $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
                $jdl2 = 'MENGETAHUI :';
            }
        }
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $nogub_susun  = $rowsc->nogub_susun;
                    $nogub_perubahan  = $rowsc->nogub_perubahan;
                }

        $jenis=explode("/",$nomor);
        switch ($jenis[2]) {
            case 'UP':
                $lcbeban="UP";
                $judul  ="UANG PERSEDIAAN";
                break;
            case 'GU':
                $lcbeban="GU";
                $judul  ="GANTI UANG";
                break;
            case 'TU':
                $lcbeban="TU";
                $judul  ="TAMBAH UANG";
                break; 
            case 'LS-GJ':
                $lcbeban="LS-GJ";
                $judul  ="LANGSUNG GAJI DAN TUNJANGAN";
                break;   
            case 'LS':
                $lcbeban="LS";
                $judul  ="LANGSUNG";
                break;          
        }
        $status_anggaran=$this->anggaran_spd_model->get_status2($kd);
        $sqlang="SELECT sum($status_anggaran) as nilai FROM trdrka where left(kd_rek6,1)='5' and LEFT(kd_skpd,17)=LEFT('$kd',17)";
        $nilai_ang=$this->db->query($sqlang)->row()->nilai;
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td rowspan='4' width='10%' align='left'></td>
                        <img src='".base_url()."image/simakdaskpd_2016.png' width='75' height='75'>
                        <td align='center' width='90%' style='font-size:14px'><strong>PEMERINTAH KOTA PONTIANAK</strong></td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:13px'>$nm_skpd </td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:12px'>$alamat_skpd</td>
                    </tr>
                    <tr>
                        <td align='center'>".strtoupper($daerah)." </td>    
                    </tr>
                    </table>
                    <hr  width='100%'> 
                    ";
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td align='center' style='font-size:14px'>SURAT PERMINTAAN PEMBAYARAN $judul</td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:14px'>(SPP - $lcbeban)</td>
                    </tr>
                    <tr>
                        <td align='center'><strong>Nomor :$nomor</strong></td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:14px'><strong><u>RINGKASAN</u></strong></td>
                    </tr>
                  </table>";

        $cRet .= "<br><table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='$spasi'>
                     
                        ";
                            
                                  
        $cRet    .= " <tr>
                            <td colspan='4'  align='center'>RINGKASAN DPA/DPPA/DPPAL-SKPD</td></tr> ";                                    
        $cRet    .= " <tr>
                            <td colspan='3' width='2%' align='left'>Jumlah dana DPA/DPPA/DPPAL-SKPD </td>
                            <td width='18%' align='right'>".number_format($nilai_ang,'2',',','.')."</td></tr>";                                                                          
        $cRet    .= " <tr>
                            <td colspan='4'  align='center'>RINGKASAN SPD</td>
                     </tr> ";
        $cRet    .= " <tr>
                            <td width='2%' align='center'>No. Urut</td>                                     
                            <td width='38%' align='center'>Nomor SPD</td>
                            <td width='32%' align='center'>Tanggal SPD</td>
                            <td width='18%' align='center'>Jumlah Dana</td>
                    </tr>";
     $sql1="SELECT z.* from (
            SELECT '1' as kode,a.no_spd, b.tgl_spd, SUM(a.nilai_final) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd 
            WHERE b.jns_beban = '5' and left(b.kd_skpd,17)=left('$kd',17) and b.status='1' and b.tgl_spd <='$tgl_spp' 
            GROUP BY a.no_spd, b.tgl_spd) z order by kode
                            ";
                    $query = $this->db->query($sql1);
                    $lcno = 0;
                    $lntotal = 0;
                    foreach ($query->result() as $row){
                        $lcno = $lcno + 1;
                        $lntotal = $lntotal + $row->nilai;
                        $totalspd = number_format($lntotal,"2",",",".");
                       
                        $no=$row->no_spd;
                        $tgl=$row->tgl_spd;
                        $tgl_spd = $this->support->tanggal_format_indonesia($tgl);
                        $nilai=number_format($row->nilai,"2",",",".");                    
                          $cRet    .= " <tr>
                                         <td width='7%' align='center'>$lcno</td>                                     
                                         <td width='25%'>$no</td>
                                         <td width='3%'>$tgl_spd</td>
                                         <td  width='3%' align='right'>$nilai</td>
                                         </tr>
                                         ";
                    }        
                    $blmspd=$nilai_ang-$lntotal;
                    $cRet    .= " <tr>
                                     <td colspan='3' width='2%' align='right'><i>JUMLAH</i> </td>
                                     <td width='18%' align='right'> $totalspd</td></tr>";


                    $cRet    .= " <tr>
                                    <td colspan='3' width='2%' align='right'><i>Sisa dana yang belum di SPD-kan</i> </td>
                                     <td width='18%' align='right'> ".number_format($blmspd,2,',','.')."</td></tr>";
                    $cRet    .= " <tr>
                                    <td colspan='3' width='2%' align='right'>&nbsp;</td>
                                     <td width='18%'></td>&nbsp;
                                 </tr>"; 

                   $sqlsppup="SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd'                               
                                AND a.jns_spp IN ('1','2') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";


                    $sqlspptls="SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd'                               
                                AND a.jns_spp IN ('6') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
 
                   
                    $sqlsppgj="SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd'                               
                                AND a.jns_spp IN ('4') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
    
     
                    $sqlspptu="SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd'                               
                                AND a.jns_spp IN ('3') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
                   

                   $jenisup=$this->db->query($sqlsppup)->row()->nilai;
                   $jenisls=$this->db->query($sqlspptls)->row()->nilai;
                   $jenisgj=$this->db->query($sqlsppgj)->row()->nilai;
                   $jenistu=$this->db->query($sqlspptu)->row()->nilai;
                    $cRet    .= " <tr><td colspan='4'  align='center'>RINGKASAN BELANJA</td></tr>                                      
                                  <tr> 
                                    <td colspan='3' width='2%' align='left'>Belanja UP/GU</td>
                                     <td width='18%' align='right'>".number_format($jenisup,2,'.',',')."</td></tr>
                                  <tr>
                                    <td colspan='3' width='2%' align='left'>Belanja TU</td>
                                    <td width='18%' align='right'>".number_format($jenistu,2,'.',',')."</td>
                                  </tr>
                                  <tr>
                                    <td colspan='3' width='2%' align='left'>Belanja LS Pembayaran Gaji dan Tunjangan</td>
                                    <td width='18%' align='right'>".number_format($jenisgj,2,'.',',')."</td>
                                  </tr>
                                  <tr>
                                    <td colspan='3' width='2%' align='left'>Belanja LS Pengadaan Barang dan Jasa</td>
                                    <td width='18%' align='right'>".number_format($jenisls,2,'.',',')."</td>
                                  </tr>
                                  <tr>
                                    <td colspan='3' width='2%' align='right'><i>JUMLAH</i></td>
                                    <td width='18%' align='right'>".number_format($jenisup+$jenistu+$jenisgj+$jenisls,2,'.',',')."</td>
                                  </tr>
                                  <tr>
                                    <td colspan='3' style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;' width='2%' align='left'><i>Sisa SPD yang telah, belum dibelanjakan</i></td>
                                    <td style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;' width='18%' align='right'>".number_format($lntotal-$jenisls,2,',','.')."</td>
                                  </tr>";

        $cRet .=       " </table>";       
        switch ($jns) {
            case 6:
                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>Mengetahui,</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
             case 3:
                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$mengehui</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
                      
            
            default:
                    $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$daerah, $tanggal</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$jabatan</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>                              
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'><b><u>$nama </u></b><br>
                         $pangkat <br>
                         NIP. $nip</td>
                     </tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>"; 
                break;
            }

            if($cetak=='1'){
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,''); 
            }else
            if($cetak=='0'){
            echo $cRet;
            }
            else
            if($cetak=='2'){
            $this->_mpdf_word('',$cRet,10,10,10,'0',1,''); 
            }

    }

    function cetakspp3(){
        $spasi   = $this->uri->segment(10);
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $nm_skpd=$this->rka_model->get_nama($kd,'nm_skpd','ms_skpd','kd_skpd');
        $nomor=str_replace('123456789','/',$this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');        
        $kodepos = $this->rka_model->get_nama($kd,'kodepos','ms_skpd','kd_skpd');
            if($kodepos==''){
                $kodepos = "-------";
            } else {
                $kodepos = "$kodepos";
            }
            
        $cekk = substr($kd,18,4);
        if($cekk!='0000'){
            $cekBP = 'BPP';
            $tipebendahara="Bendahara Pengeluaran Pembantu";
        }else{
            $cekBP = 'BK';
            $tipebendahara="Bendahara Pengeluaran";
        }  
            
        $BK = str_replace('123456789',' ',$this->uri->segment(7));
        $PPTK = str_replace('123456789',' ',$this->uri->segment(8));
        $PPKD = str_replace('123456789',' ',$this->uri->segment(9));
        $sqlttd1="SELECT top 1 nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$BK' ";
         $sqlttd=$this->db->query($sqlttd1);
         foreach ($sqlttd->result() as $rowttd)
        {
            $nip=$rowttd->nip;                    
            $nama= $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        
        $nip2='';                    
                $nama2= '';
                $jabatan2  = '';
                $pangkat2  = '';
                $jdl2 = '';
                
        if($PPTK=='-'){
                $nip2='';                    
                $nama2= '';
                $jabatan2  = '';
                $pangkat2  = '';
                $jdl2 = '';
        }else{
            $sqlttd2="SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id_ttd='$PPTK' ";
            $sqlttd2=$this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2){
                $nip2= $rowttd2->nip;                    
                $nama2= $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
                $jdl2 = 'MENGETAHUI :';
            }
        }
        $sqlttd3="SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id_ttd='$PPKD'";
         $sqlttd3=$this->db->query($sqlttd3);
         foreach ($sqlttd3->result() as $rowttd3)
        {
            $nip3   = $rowttd3->nip;                    
            $nama3  = $rowttd3->nm2;
            $jabatan3  = $rowttd3->jab;
            $pangkat3  = $rowttd3->pangkat;
        }
        
        

                $sqltgl="SELECT * FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
                 $sqltgl=$this->db->query($sqltgl);
                 foreach ($sqltgl->result() as $rowtg)
                {
                   $nmskpd = $rowtg->nm_skpd;
                   $tgl=$rowtg->tgl_spp;
                   $tanggal = $this->support->tanggal_format_indonesia($tgl);   
                   $bln = $this->support->getBulan($rowtg->bulan);
                }
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }

        $thn_ang       = $this->session->userdata('pcThang');
        if($tanpa==1) {
            $tanggal ="_______________________$thn_ang";
            } 

        $jenis=explode("/",$nomor);
        switch ($jenis[2]) {
            case 'UP':
                $lcbeban="UP";
                $judul  ="UANG PERSEDIAAN";
                break;
            case 'GU':
                $lcbeban="GU";
                $judul  ="GANTI UANG";
                break;
            case 'TU':
                $lcbeban="TU";
                $judul  ="TAMBAH UANG";
                break; 
            case 'LS-GJ':
                $lcbeban="LS-GJ";
                $judul  ="LANGSUNG GAJI DAN TUNJANGAN";
                break;   
            case 'LS':
                $lcbeban="LS";
                $judul  ="LANGSUNG";
                break;          
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td rowspan='4' width='10%' align='left'></td>
                        <img src='".base_url()."image/simakdaskpd_2016.png' width='75' height='75'>
                        <td align='center' width='90%' style='font-size:16px'><strong>PEMERINTAH KOTA PONTIANAK</strong></td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:13px'>$nm_skpd </td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:12px'>$alamat_skpd Kode Pos: $kodepos</td>
                    </tr>
                    <tr>
                        <td align='center'>".strtoupper($daerah)." </td>    
                    </tr>
                    </table>
                    <hr  width='100%'> 
                    ";
                    
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr><td align='center' style='font-size:14px'>SURAT PERMINTAAN PEMBAYARAN $judul</td></tr>
                    <tr><td align='center' style='font-size:14px'>(SPP - ".strtoupper($lcbeban).")</td></tr>
                    <tr><td align='center'><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align='center' style='font-size:14px'><strong><u>RINCIAN</u></strong></td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='left'>RENCANA PENGGUNA ANGGARAN</td></tr>
                    <tr><td align='left'>BULAN : ".strtoupper($bln)."</td></tr>

                  </table>";

        $cRet .= "<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='$spasi'>
                     <thead>                       
                        <tr><td bgcolor='#CCCCCC' width='5%' align='center'><b>No Urut</b></td>                            
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>Kode Rekening</b></td>
                            <td bgcolor='#CCCCCC' width='60%' align='center'><b>Uraian</b></td>
                            <td bgcolor='#CCCCCC' width='20%' align='center'><b>Jumlah</b></td>                                                    
                     </thead> 
                           
                        ";
                            
                 if($lcbeban=="UP"){
                    $sql1="SELECT 7 urut, c.kd_sub_kegiatan+'.'+c.kd_rek6 as kode, c.nm_rek6 as nama, c.nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    order by kode";
               }else{             
                 $sql1="SELECT 1 urut, LEFT(c.kd_sub_kegiatan,7) as kode, d.nm_program as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY LEFT(c.kd_sub_kegiatan,7), d.nm_program

                    UNION ALL
                    SELECT 2 urut, LEFT(c.kd_sub_kegiatan,12) as kode, d.nm_kegiatan as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY LEFT(c.kd_sub_kegiatan,12), d.nm_kegiatan

                    UNION ALL
                    SELECT 3 urut, c.kd_sub_kegiatan as kode, c.nm_sub_kegiatan as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,c.nm_sub_kegiatan

                    UNION ALL
                    SELECT 4 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,4) as kode, d.nm_rek3 as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd 
                    LEFT JOIN ms_rek3 d ON LEFT(c.kd_rek6,4)=d.kd_rek3 
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,4),d.nm_rek3

                    UNION ALL
                    SELECT 5 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,6) as kode, d.nm_rek4 as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    LEFT JOIN ms_rek4 d ON LEFT(c.kd_rek6,6)=d.kd_rek4
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,6),d.nm_rek4

                    UNION ALL
                    SELECT 6 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,8) as kode, d.nm_rek5 as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    LEFT JOIN ms_rek5 d ON LEFT(c.kd_rek6,8)=d.kd_rek5
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,8),d.nm_rek5

                    UNION ALL
                    SELECT 7 urut, c.kd_sub_kegiatan+'.'+c.kd_rek6 as kode, c.nm_rek6 as nama, c.nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    order by kode";
                }
                  $query = $this->db->query($sql1);
                $lcno = 0;
                $lntotal = 0;                                 
                foreach ($query->result() as $row){   
                    
                    $kode=$row->kode; 
                    $uraian=$row->nama;
                    $urut=$row->urut;
                    $nilai=$row->nilai;                    
                    if($urut==1){
                    $lcno=$lcno+1;
                     $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='5%' align='center'><b>$lcno</b></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='15%' align='left'><b>$kode</b></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='60%'><b>$uraian</b></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
                                     </tr>
                                     ";
                    } else if($urut==7){
                    $lntotal = $lntotal + $row->nilai;
                     $rek=substr($kode,16,12);
                     $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='5%' align='center'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='15%' align='left'>".$this->support->left($kode,15).".".$this->support->dotrek($rek)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='60%'>$uraian</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($nilai,"2",",",".")."</td>
                                     </tr>
                                     "; 
                    }else {
                    $rek=substr($kode,16,11);
                     $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='5%' align='center'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='15%' align='left'>".$this->support->left($kode,15)."".$this->support->dotrek($rek)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='60%'>$uraian</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($nilai,"2",",",".")."</td>
                                     </tr>
                                     ";
                    }
                }
                   
                   $totp=number_format($lntotal,"2",",",".");
                    $cRet    .=" <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;' width='5%' align='left'>&nbsp;</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;' width='15%'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;' width='60%' align='RIGHT'><b>JUMLAH</b></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;' width='20%' align='right'><b>$totp</b></td>                                     
                                     </tr>";
                  

        $cRet .=       " </table>";
        $cRet .= "<table style='border-collapse:collapse;font-family: Times New Roman; font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='10'>
                <tr><td>Terbilang : <i>".ucwords($this->tukd_model->terbilang($lntotal))."</i> </td></tr>
                </table>";
                
         if($PPTK=='-'){
                $nip2='';                    
                $nama2= '';
                $jabatan2  = '';
                $pangkat2  = '';
                $jdl2 = '';
        }
       switch ($jns) {
            case 6:


                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>Mengetahui,</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
            case 3:
                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>Mengetahui,</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
                       
            
            default:
                    $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$daerah, $tanggal</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$jabatan</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>                              
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'><b><u>$nama </u></b><br>
                         $pangkat <br>
                         NIP. $nip</td>
                     </tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>"; 
                break;
            }
            

                $data['prev']= $cRet;
                if($cetak=='1'){
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,''); 
                }
                if($cetak=='0'){
                echo $cRet;
                }
                if($cetak=='2'){
            $this->_mpdf_word('',$cRet,10,10,10,'0',1,''); 
                }
                if($cetak=='3'){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= rincian_spp_ls".$kd.".xls");
            $this->load->view('anggaran/rka/perkadaII', $data);         
            }
    }

    function cetakspp4(){

        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(8);
        $spasi = $this->uri->segment(9);
        $nm_skpd=$this->rka_model->get_nama($kd,'nm_skpd','ms_skpd','kd_skpd');  
        $nomor = str_replace('123456789','/',$this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');        
        $kodepos = $this->rka_model->get_nama($kd,'kodepos','ms_skpd','kd_skpd');
            if($kodepos==''){
                $kodepos = "-------";
            } else {
                $kodepos = "$kodepos";
            }
        $PA = str_replace('123456789',' ',$this->uri->segment(7));
       
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$PA'";
         $sqlttd=$this->db->query($sqlttd1);
         foreach ($sqlttd->result() as $rowttd)
        {
            $nip=$rowttd->nip;                    
            $nama= $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        




             $sql1="SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON replace(left(kd_skpd,4),'-','.')=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
                $query = $this->db->query($sql1);
                                                  
                foreach ($query->result() as $row)
                {
                    $kd_urusan=$row->kd_bidang_urusan;
                    $nm_urusan=$row->nm_bidang_urusan;
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $spd=$row->no_spd;
                    $tgl=$row->tgl_spp;
                    $jns_bbn=$row->jns_beban;
                    $nama_bank=$row->nama_bank;
                    $no_rek=$row->no_rek;
                    $npwp = $row->npwp;
                    $rekan = $row->nmrekan;
                    $tanggal1 = $this->support->tanggal_format_indonesia($tgl);
                    $bln = $this->support->getBulan($row->bulan);                    
                    $nilai=number_format($row->nilai,"2",",",".");
                    $nilai1=$row->nilai;
                    $a=$this->tukd_model->terbilang($nilai1);
                    //echo($a);
                }
                
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
       
        $thn_ang       = $this->session->userdata('pcThang');
        if($tanpa==1) {
            $tanggal ="_______________________$thn_ang";
            } else{
            $tanggal = $tanggal1;   
            }               

        $cRet='';
    
        $jenis=explode("/",$nomor);
        switch ($jenis[2]) {
            case 'UP':
                $lcbeban="UP";
                $judul  ="UANG PERSEDIAAN";
                break;
            case 'GU':
                $lcbeban="GU";
                $judul  ="GANTI UANG";
                break;
            case 'TU':
                $lcbeban="TU";
                $judul  ="TAMBAH UANG";
                break; 
            case 'LS-GJ':
                $lcbeban="LS-GJ";
                $judul  ="LANGSUNG GAJI DAN TUNJANGAN";
                break;   
            case 'LS':
                $lcbeban="LS";
                $judul  ="LANGSUNG";
                break;          
        }
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td rowspan='4' width='10%' align='left'></td>
                        <img src='".base_url()."image/simakdaskpd_2016.png' width='75' height='75'>
                        <td align='center' width='90%' style='font-size:16px'><strong>PEMERINTAH KOTA PONTIANAK</strong></td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:13px'>$nm_skpd </td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:12px'>$alamat_skpd Kode Pos: $kodepos</td>
                    </tr>
                    <tr>
                        <td align='center'>".strtoupper($daerah)." </td>    
                    </tr>
                    </table>
                    <hr  width='100%'> 
                    ";
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr><td align='center'><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - ".strtoupper($lcbeban)." </u></strong></td></tr>
                    <tr><td align='center'><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align='center'></td></tr>
                    <tr><td align='center'></td></tr>
                    <tr><td align='center'></td></tr>
                    <tr><td align='center'></td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='left'>Sehubungan dengan Surat Permintaan Pembayaran $judul (SPP - ".strtoupper($lcbeban).") Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai (".ucwords($a).")</td></tr>
                    <tr><td align='left'>&nbsp;</td></tr>
                    <tr><td align='left'>Untuk Keperluan SKPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align='left'>&nbsp;</td></tr>
                    <tr><td align='left'>Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align='left'>&nbsp;</td></tr>
                  </table>";

        $cRet .= "<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='$spasi'>
                     
                        ";
                            
                                  
                     $cRet    .= " <tr><td  width='5%' align='center'>1.</td>                                     
                                     <td  width='90%' align='justify'>
                                     Jumlah Pembayaran $judul ($lcbeban) tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-SKPD</td>
                                     </tr>
                                     ";
                    $cRet    .= " <tr><td  width='5%' align='center'>2.</td>                                     
                                     <td style='vertical-align:top;border-top: none;border-bottom: none;' width='90%' align='justify'>
                                     Jumlah Pembayaran $judul ($lcbeban) tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran $judul
                                     </tr>
                                     ";
        
        $cRet .=       " </table>";
         $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    
                    <tr><td align='justify'>Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP-$lcbeban SKPD kami.</td></tr>
                    <tr><td align='left'>&nbsp;</td></tr>
                  </table>";
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'></td>                    
                    <td align='center' width='25%'>$daerah, $tanggal</td></tr>
                    <tr><td align='center' width='25%'></td>                    
                    <td align='center' width='25%'>$jabatan</td></tr>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'> </td>                    
                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>";
            $data['prev']= $cRet;
            if($print=='1'){
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,''); 
            }
            if($print=='0'){
            echo $cRet;
            }
            if($print=='2'){
            $this->_mpdf_word('',$cRet,10,10,10,'0',1,''); 
            }
            if($print=='3'){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= pernyataan_spp_ls".$kd.".xls");
            $this->load->view('anggaran/rka/perkadaII', $data);         
            }
    }

     function cetakspp5(){
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $spasi = $this->uri->segment(10);
        
        $nm_skpd=$this->rka_model->get_nama($kd,'nm_skpd','ms_skpd','kd_skpd');
        $nomor = str_replace('123456789','/',$this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');
        $jns_bbn=$this->rka_model->get_nama($nomor,'jns_beban','trhspp','no_spp');
        $kodepos = $this->rka_model->get_nama($kd,'kodepos','ms_skpd','kd_skpd');
            if($kodepos==''){
                $kodepos = "-------";
            } else {
                $kodepos = "$kodepos";
            }
            
        $cekk = substr($kd,18,4);
        if($cekk!='0000'){
            $cekBP = 'BPP';
            $tipebendahara="Bendahara Pengeluaran Pembantu";
        }else{
            $cekBP = 'BK';
            $tipebendahara="Bendahara Pengeluaran";
        }       
         $BK = str_replace('123456789',' ',$this->uri->segment(7));
        $PPTK = str_replace('123456789',' ',$this->uri->segment(8));
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$BK'";
         $sqlttd=$this->db->query($sqlttd1);
         foreach ($sqlttd->result() as $rowttd)
        {
            $nip=$rowttd->nip;                    
            $nama= $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        
        $sqlttd2="SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id_ttd='$PPTK'";
         $sqlttd2=$this->db->query($sqlttd2);
         foreach ($sqlttd2->result() as $rowttd2)
        {
            $nip2=$rowttd2->nip;                    
            $nama2= $rowttd2->nm2;
            $jabatan2  = $rowttd2->jab;
            $pangkat2  = $rowttd2->pangkat;
        }   

            $lcbeban = "LS";                
                   
        
        $kdX = substr($kd,0,17)+'.0000';

        $jenis=explode("/",$nomor);
                switch ($jenis[2]) {
                    case 'UP':
                        $lcbeban="UP";
                        $judul  ="UANG PERSEDIAAN";
                        break;
                    case 'GU':
                        $lcbeban="GU";
                        $judul  ="GANTI UANG";
                        break;
                    case 'TU':
                        $lcbeban="TU";
                        $judul  ="TAMBAH UANG";
                        break; 
                    case 'LS-GJ':
                        $lcbeban="LS-GJ";
                        $judul  ="LANGSUNG GAJI DAN TUNJANGAN";
                        break;   
                    case 'LS':
                        $lcbeban="LS";
                        $judul  ="LANGSUNG";
                        break;          
                }
        $tgl_spp=$this->rka_model->get_nama($nomor,'tgl_spp','trhspp','no_spp');
        $tanggal = $this->support->tanggal_format_indonesia($tgl_spp);
        $no_spd=$this->rka_model->get_nama2($nomor,'no_spd','trhspp','no_spp','kd_skpd',$kd);
        $tglspd =$this->rka_model->get_nama2($no_spd,'tgl_spd','trhspd','no_spd','no_spd',$no_spd);
        $nmskpd=$this->rka_model->get_nama($kd,'nm_skpd','trhspp','kd_skpd');
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE left(kd_skpd,7)=left('$kd',7)";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $nogub_susun  = $rowsc->nogub_susun;
                    $nogub_perubahan  = $rowsc->nogub_perubahan;
                }
        
        
        
         $sql1="SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,left(a.kd_sub_kegiatan,7) as kd_program,a.nm_program,a.nm_sub_kegiatan,a.kd_sub_kegiatan,a.bulan,a.nmrekan, 
                a.no_rek as no_rek_rek, a.npwp as npwp_rek,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank, lanjut, kontrak, keperluan,pimpinan, alamat,
                ( SELECT nama FROM ms_bank WHERE  kode=a.bank ) AS nama_bank_rek, 
                ( SELECT rekening FROM ms_skpd WHERE  kd_skpd=a.kd_skpd ) AS no_rek, 
                ( SELECT npwp FROM ms_skpd WHERE  kd_skpd=a.kd_skpd ) AS npwp, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON replace(LEFT(a.kd_skpd, 4), '-', '.')=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
                 $query = $this->db->query($sql1);
                                                  
                foreach ($query->result() as $row)
                {
                    $kd_urusan=$row->kd_bidang_urusan;
                    $nm_urusan=$row->nm_bidang_urusan;
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $spd=$row->no_spd;
                    $tgl=$row->tgl_spp;
                    $kd_prog=$row->kd_program;
                    $nm_prog=$row->nm_program;
                    $kd_kegiatan= $row->kd_sub_kegiatan;
                    $nm_kegiatan=$row->nm_sub_kegiatan;
                    $nm_bank_rek=$row->nama_bank_rek;
                    $lanjut=$row->lanjut;
                    $kontrak=$row->kontrak;
                    $no_rek_rek=$row->no_rek_rek;
                    $npwp_rek = $row->npwp_rek;
                    $no_rek=$row->no_rek;
                    $npwp = $row->npwp;
                    $ket = ltrim($row->keperluan);
                    $rekan = $row->nmrekan;
                    $dir = $row->pimpinan;
                    $alamat = $row->alamat;
                    $tanggal = $this->support->tanggal_format_indonesia($tgl);
                    $bln = $this->support->getBulan($row->bulan);                    
                    $nilai=number_format($row->nilai,"2",",",".");
                    $nilai1=$row->nilai;
                    $a=$this->tukd_model->terbilang($nilai1);
                  
                }
         $kodebank = $this->rka_model->get_nama($nomor,'bank','trhspp','no_spp');
        $nama_bank = empty($kodebank) || $kodebank == '' ? '-' : $this->rka_model->get_nama($kodebank,'nama','ms_bank','kode');
 
        $stsubah=$this->rka_model->get_nama($kd,'status_ubah','trhrka','kd_skpd');
        $stssempurna =$this->rka_model->get_nama($kd,'status_sempurna','trhrka','kd_skpd');
        if (($stsubah==0) && ($stssempurna==0)){
            $field='nilai';
            $nodpa=$this->rka_model->get_nama($kd,'no_dpa','trhrka','kd_skpd');     
            $tgl_dpa=$this->rka_model->get_nama($kd,'tgl_dpa','trhrka','kd_skpd');
            $nogub=$nogub_susun;            
        }           
        else if (($stsubah==0) && ($stssempurna==1)){
            $nodpa=$this->rka_model->get_nama($kd,'no_dpa_sempurna','trhrka','kd_skpd');        
            $tgl_dpa=$this->rka_model->get_nama($kd,'tgl_dpa_sempurna','trhrka','kd_skpd');
            $field='nilai_sempurna';            
            $nogub=$nogub_susun;
            
        } else{
            $nodpa=$this->rka_model->get_nama($kd,'no_dpa_ubah','trhrka','kd_skpd');        
            $tgl_dpa=$this->rka_model->get_nama($kd,'tgl_dpa_ubah','trhrka','kd_skpd');
            $field='nilai_ubah';
            $nogub=$nogub_perubahan;

        }        
       
        if($nm_prog==''){
            $nm_prog=$this->rka_model->get_nama($kd_prog,'nm_program','ms_program','kd_program');
        }
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE left(kd_skpd,7)=left('$kd',7)";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        
        $thn_ang       = $this->session->userdata('pcThang');
        if($tanpa==1) {
            $tanggal ="_______________________$thn_ang";
            }          
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td rowspan='4' width='10%' align='left'></td>
                        <img src='".base_url()."image/simakdaskpd_2016.png' width='75' height='75'>
                        <td align='center' width='90%' style='font-size:16px'><strong>PEMERINTAH KOTA PONTIANAK</strong></td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:13px'>$nm_skpd </td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:12px'>$alamat_skpd Kode Pos: $kodepos</td>
                    </tr>
                    <tr>
                        <td align='center'>".strtoupper($daerah)." </td>    
                    </tr>
                    </table>
                    <hr  width='100%'> 
                    ";
                    
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr><td align='center' style='font-size:16px'>SURAT PERMINTAAN PEMBAYARAN ".strtoupper($lcbeban)."</td></tr>
                    <tr><td align='center' style='font-size:16px'>(SPP - ".strtoupper($lcbeban).")</td></tr>
                    <tr><td align='center'><strong>Nomor :$nomor</strong></td></tr>
                  </table>";

        $cRet .= "<br><table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='$spasi'> ";
                    $cRet    .= " <tr><td colspan='2' width='20%' align='left' >1. SKPD </td>
                                     <td colspan='2'  width='18%'>: $nm_skpd</td></tr>";                    
                    $cRet    .= " <tr><td colspan='2' align='left' >2. Alamat </td>
                                     <td colspan='2'  width='18%'>: $alamat_skpd</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >3. Nomor dan Tanggal DPA/DPPA/DPPAL-SKPD  </td>
                                     <td colspan='2'  width='18%'>: $nodpa / $tgl_dpa</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >4. Tahun Anggaran </td>
                                     <td colspan='2'  width='18%'>: $thn_ang </td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >5. Bulan </td>
                                     <td colspan='2'  width='18%'>: $bln $thn_ang</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >6. Urusan Pemerintahan </td>
                                     <td colspan='2'  width='18%'>: $kd_urusan $nm_urusan</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >7. Nama Program </td>
                                     <td colspan='2'  width='18%'>: $nm_prog</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >8. Nama Sub Kegiatan </td>
                                     <td colspan='2'  width='18%'>: $nm_kegiatan</td></tr>";
                    $cRet    .= " <tr><td colspan='4'   align='center'>&nbsp;</td></tr> ";                                    
        $cRet .=       " </table>";
        
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='1'>
                    <tr><td align='center'>Kepada Yth: <br>
                        Pengguna Anggaran/Kuasa Pengguna Anggaran <br>
                        <br>
                        SKPD $nm_skpd<br>
                        di $daerah<td></tr>
                    <tr><td align='justify'>Dengan memperhatikan Peraturan Walikota Kota Pontianak $nogub
                    tentang Perubahan Peraturan Walikota Kota Pontianak No. 84 Tahun 2015  tentang Penjabaran APBD Tahun Anggaran $thn_ang,
                    bersama ini kami mengajukan Surat Permintaan Pembayaran $judul sebagai berikut:
                    <br>
                    <br>
                    </td></tr>
                    <tr><td align='center'> &nbsp;<td></tr>
                  </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='5px'> ";
                    $cRet    .= " <tr><td colspan='2' width='20%' align='left' >a. Jumlah Pembayaran Yang Diminta </td>
                                     <td colspan='2'  width='18%'>: Rp. $nilai</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='center' >(terbilang) </td>
                                     <td colspan='2'  width='18%'>: <i style='font-size:10px'>(".ucwords($a).")</i></td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >b. Untuk Keperluan </td>
                                     <td colspan='2'  width='18%'><pre>:$ket</pre></td></tr>";
                    if(($jns==6) && ($jns_bbn==3)){              
                    $cRet    .= " <tr><td colspan='2' align='left' >c. Nama Pihak Ketiga</td>
                                     <td colspan='2'  width='18%'>: $rekan</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >d. Dasar Bendahara Pengeluaran </td>
                                     <td colspan='2'  width='18%'>: $no_spd </td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >e. Alamat </td>
                                     <td colspan='2'  width='18%'>: $alamat</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >f. Nama dan Nomor Rekening</td>
                                     <td colspan='2'  width='18%'>: $nm_bank_rek / $no_rek_rek</td></tr>";
                    $cRet    .= " <tr><td colspan='4'   align='center'>&nbsp;</td></tr> ";                                    
    
                    }else{
                    $cRet    .= " <tr><td colspan='2' align='left' >c. Nama Bendahara</td>
                                     <td colspan='2'  width='18%'>: $nama</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >d. Dasar Bendahara Pengeluaran </td>
                                     <td colspan='2'  width='18%'>: $no_spd </td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >e. Alamat </td>
                                     <td colspan='2'  width='18%'>: $alamat_skpd</td></tr>";
                    $cRet    .= " <tr><td colspan='2' align='left' >f. Nama dan Nomor Rekening</td>
                                     <td colspan='2'  width='18%'>: $nama_bank / $no_rek_rek</td></tr>";
                    $cRet    .= " <tr><td colspan='4' align='center'>&nbsp;</td></tr> ";                                    
    
                    }                
                        $cRet .=       " </table>";
  
 switch ($jns) {
            case 6:
                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>Mengetahui</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
            case 3:
                        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>Mengetahui</td>                    
                                    <td align='center' width='25%'>$daerah, $tanggal</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>$jabatan2</td>                    
                                    <td align='center' width='25%'>$jabatan</td>
                                </tr>
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td>
                                </tr>                              
                                <tr>
                                    <td align='center' width='25%'>&nbsp;</td>                    
                                    <td align='center' width='25%'>&nbsp;</td></tr>
                                <tr>
                                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                                 $pangkat2 <br>
                                  $nip2</td>                    
                                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                                 $pangkat <br>
                                 NIP. $nip</td></tr>                              
                                <tr><td align='center' width='25%'>&nbsp;</td>                    
                                <td align='center' width='25%'>&nbsp;</td></tr>
                              </table>"; 
                break;
            
            default:
                    $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$daerah, $tanggal</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'>$jabatan</td>
                    </tr>
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td>
                    </tr>                              
                    <tr>
                        <td align='center' width='25%'>&nbsp;</td>                    
                        <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr>
                        <td align='center' width='25%'></td>                    
                        <td align='center' width='25%'><b><u>$nama </u></b><br>
                         $pangkat <br>
                         NIP. $nip</td>
                     </tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>"; 
                break;
        }
        
                $data['prev']= $cRet;
                if($cetak=='1'){
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,''); 
                }   
                if($cetak=='0'){
                echo $cRet;
                }
                 if($cetak=='2'){
            $this->_mpdf_word('',$cRet,10,10,10,'0',1,''); 
                }if($cetak=='3'){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= permintaan_spp_ls".$kd.".xls");
            $this->load->view('anggaran/rka/perkadaII', $data);         
            }     
    }

    function cetakspp6(){
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(8);
        $spasi = $this->uri->segment(9); 
        $nmskpd=$this->rka_model->get_nama($kd,'nm_skpd','trhspp','kd_skpd');
        $nomor = str_replace('123456789','/',$this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');        
        $kodepos = $this->rka_model->get_nama($kd,'kodepos','ms_skpd','kd_skpd');
            if($kodepos==''){
                $kodepos = "-------";
            } else {
                $kodepos = "$kodepos";
            }
        $PA = str_replace('123456789',' ',$this->uri->segment(7));
       
        $sqlttd1="SELECT top 1 nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$PA'";
         $sqlttd=$this->db->query($sqlttd1);
         foreach ($sqlttd->result() as $rowttd)
        {
            $nip=$rowttd->nip;                    
            $nama= $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        
        
            $sql1="SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON replace(LEFT(a.kd_skpd, 4), '-', '.')=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
                $query = $this->db->query($sql1);
                                                  
                foreach ($query->result() as $row)
                {
                    $kd_urusan=$row->kd_bidang_urusan;
                    $nm_urusan=$row->nm_bidang_urusan;
                    $kd_skpd=$row->kd_skpd;
                    $nm_skpd=$row->nm_skpd;
                    $spd=$row->no_spd;
                    $tgl=$row->tgl_spp;
                    $jns_bbn=$row->jns_beban;
                    $nama_bank=$row->nama_bank;
                    $no_rek=$row->no_rek;
                    $npwp = $row->npwp;
                    $rekan = $row->nmrekan;
                    $tanggal1 = $this->support->tanggal_format_indonesia($tgl);
                    $bln = $this->support->getBulan($row->bulan);                    
                    $nilai=number_format($row->nilai,"2",",",".");
                    $nilai1=$row->nilai;
                    $a=$this->tukd_model->terbilang($nilai1);
                    //echo($a);
                }
                 
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
       
        $thn_ang       = $this->session->userdata('pcThang');
        if($tanpa==1) {
            $tanggal ="_______________________$thn_ang";
            } else{
            $tanggal = $tanggal1;   
            }               

        $cRet='';
        
        
        $lcbeban = "LS";        
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td rowspan='4' width='10%' align='left'></td>
                        <img src='".base_url()."image/simakdaskpd_2016.png' width='75' height='75'>
                        <td align='center' width='90%' style='font-size:16px'><strong>PEMERINTAH KOTA PONTIANAK</strong></td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:13px'>$nm_skpd </td>
                    </tr>
                    <tr>
                        <td align='center' style='font-size:12px'>$alamat_skpd Kode Pos: $kodepos</td>
                    </tr>
                    <tr>
                        <td align='center'>".strtoupper($daerah)." </td>    
                    </tr>
                    </table>
                    <hr  width='100%'> 
                    ";
                    
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr><td align='center'><strong><u>SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK </u></strong></td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='center'>&nbsp;</td></tr>
                    <tr><td align='left'>Yang Bertanda tangan di bawah ini:</td></tr>
                    <tr><td align='left'>&nbsp;</td></tr>
                  </table>";

        $cRet .= "<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>";
                     $cRet    .= " <tr><td  width='10' align='left'>Nama</td>                                     
                                     <td  width='90%' align='justify'>
                                    $nama                                     
                                    </tr>
                                     ";
                    $cRet    .= " <tr><td  width='10' align='left'>NIP</td>                                     
                                     <td  width='90%' align='justify'>
                                    $nip                                     
                                    </tr>
                                     ";
                    $cRet    .= " <tr><td  width='10' align='left'>Jabatan</td>                                     
                                     <td  width='90%' align='justify'>
                                    $jabatan                                     
                                    </tr>
                                     ";              
                    $cRet    .= " <tr><td  width='10' align='left'>&nbsp;</td>                                     
                                     <td  width='90%' align='justify'>
                                    &nbsp;                                     
                                    </tr>
                                     "; 
        $cRet .=       " </table>";
        $cRet .= "<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='$spasi'>";
                     $cRet    .= " <tr><td  width='5%' align='left'>1.</td>                                     
                                     <td  width='90%' align='justify'>
                                     Perhitungan yang terdapat pada Daftar Perhitungan Tambahan Penghasilan bagi PNS di Lingkungan Pemerintah KOTA PONTIANAK
                                    (".strtoupper($lcbeban).") bulan $bln $thn_ang bagi $nm_skpd telah dhitung dengan benar dan berdasarkan daftar hadir kerja Pegawai Negeri Sipil
                                    Daerah pada $nm_skpd                                    
                                    </td> </tr>
                                     ";
                    $cRet    .= " <tr><td  width='5%' align='left'>2.</td>                                     
                                     <td  width='90%' align='justify'>
                                     Apabila dikemudian hari terdapat kelebihan atas pembayaran ".strtoupper($lcbeban)." tersebut, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Daerah
                                     </tr>
                                     ";
        
        $cRet .=       " </table>";
         $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    
                    <tr><td align='justify'>Demikian  pernyataan ini kami buat dengan sebenar-benarnya.</td></tr>
                    <tr><td align='left'>&nbsp;</td></tr>
                  </table>";
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'></td>                    
                    <td align='center' width='25%'>$daerah, $tanggal</td></tr>
                    <tr><td align='center' width='25%'></td>                    
                    <td align='center' width='25%'>$jabatan</td></tr>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'> </td>                    
                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>";
            $data['prev']= $cRet;
            if($print=='1'){
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',1,''); 
            }
            if($print=='0'){
            echo $cRet;
            }
            if($print=='2'){
            $this->_mpdf_word('',$cRet,10,10,10,'0',1,''); 
            }
            if($print=='3'){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= kontrak_spp_ls".$kd.".xls");
            $this->load->view('anggaran/rka/perkadaII', $data);         
            }
      
    }

      function cetak_register_spp(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                } 

        $jns= '';
        $jns = $this->uri->segment(4);
        $bln= '';
        $bln = $this->uri->segment(5);
        $ttd1 = $this->uri->segment(6);
        $nmbln = $this->support->getBulan($bln);

        if ($jns <> '1'){                               
            $judbln="Bulan $nmbln";            
        }else{                               
            $judbln="";            
        }    
                
        $kd ='';
        $a ='';
        $nama ='';
        $kd = $this->uri->segment(3);
        if ($kd <> ''){
            $a ='SKPD :';
        $sqls="SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd'";
                 $sqls =$this->db->query($sqls);
                 foreach ($sqls->result() as $row)
                {
                    $nama     = $row->nm_skpd;
                    
                   
                }
        }
        $cRet = '';
$cRet = "<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>";
$cRet .="<thead>
        <tr>
            <td align='center' style='font-size:14px;border: solid 1px white;' colspan='15'><b> $kab</b></td>            
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;' colspan='15'><b>REGISTER SPP-UP/SPP-GU/SPP-TU/SPP-LS<br>$judbln</b></td>
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;' colspan='15'><b>$a $nama</b><br>&nbsp;</td>
        </tr>
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' rowspan='3'><b>No.<br>Urut</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='7%' rowspan='3'><b>Tanggal</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='40%' colspan='6'><b>Nomor SPP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='40%' rowspan='3'><b>Uraian</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='10%' colspan='6'><b>Jumlah SPP<br>(Rp)</b></td>
        </tr>  
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>UP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>GU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>TU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='18%' colspan='3'><b>LS</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>UP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>GU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>TU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='18%' colspan='3'><b>LS</b></td>
          </tr>
          <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Gaji</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Barang&<br>Jasa</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>PPKD</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Gaji</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Barang&<br>Jasa</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>PPKD</b></td>
          </tr>
          </thead>
          <tr>
            <td style='font-size:10px' align='center' width='3%'><b>1</b></td>
            <td style='font-size:10px' align='center' width='6%'><b>2</b></td>
            <td style='font-size:10px' align='center' width='36%' colspan='6'><b>3</b></td>
            <td style='font-size:10px' align='center' width='19%'><b>4</b></td>
            <td style='font-size:10px' align='center' width='36%' colspan='6'><b>5</b></td>
          </tr>";
        //$skpd = $this->uri->segment(3); 
        $where2= '';
        if ($jns <> '1'){                               
            $where2="and MONTH(tgl_spp)='$bln'";            
        }

        $kriteria = '';
        $kriteria = $this->uri->segment(3);
        $where ="";
        if ($kriteria <> ''){                               
            $where="where kd_skpd ='$kriteria' ";            
        }       
        
        
        
        $sql = "SELECT tgl_spp,no_spp,nilai,CASE WHEN sp2d_batal='1' then 'SP2D Dibatalkan' else keperluan END AS keperluan,jns_spp FROM trhspp $where $where2 order by tgl_spp";
                $hasil = $this->db->query($sql);
                $lcno = 0;
                foreach ($hasil->result() as $row)
                {
                   $lcno = $lcno + 1;
                    switch ($row->jns_spp) 
                    {
                        case '1': //UP
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spp)."</td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spp</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='left' width='19%' style='font-size:10px'>$row->keperluan</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->nilai)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '2': //GU
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spp)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spp</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='left' width='19%' style='font-size:10px'>$row->keperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->nilai)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '3': //TU
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spp)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spp</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='left' width='19%' style='font-size:10px'>$row->keperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->nilai)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '4': //LS gaji
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spp)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spp</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='left' width='19%' style='font-size:10px'>$row->keperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->nilai)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '5': //LS PPKD
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spp)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spp</td>
                                <td align='left' width='19%' style='font-size:10px'>$row->keperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->nilai)."</td>
                              </tr>  "; 
                            break;
                        case '6': //LS barang dan jasa
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spp)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spp</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='left' width='19%' style='font-size:10px'>$row->keperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->nilai)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '7': //LS GU nihil
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spp)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spp</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='left' width='19%' style='font-size:10px'>$row->keperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->nilai)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            
                    }
                   
                }
                $sqlttd1="SELECT top 1 nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$ttd1'";
         $sqlttd=$this->db->query($sqlttd1);
         foreach ($sqlttd->result() as $rowttd)
        {
            $nip=$rowttd->nip;                    
            $nama= $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        
                $sqlttd2="SELECT top 1 nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kode = 'BK' AND kd_skpd='$kd'";
         $sqlttd2=$this->db->query($sqlttd2);
         foreach ($sqlttd2->result() as $rowttd)
        {
            $nip2=$rowttd->nip;                    
            $nama2= $rowttd->nm;
            $jabatan2  = $rowttd->jab;
            $pangkat2  = $rowttd->pangkat;
        }
                
                $sql = "
SELECT sum(z.nil_up) as up,sum(z.nil_gu) as gu,sum(z.nil_tu) as tu,sum(z.nil_ls_gj) as gj,sum(z.nil_ppkd) as ppkd,sum(z.nil_ls_brg) as barang from (
select isnull(sum(nilai),0) as nil_up, 0 as nil_gu,0 as nil_tu,0 as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='1' and MONTH(tgl_spp)='$bln' and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,isnull(sum(nilai),0) as nil_gu,0 as nil_tu,0 as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp in ('2','7') and MONTH(tgl_spp)='$bln' and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu, isnull(sum(nilai),0) as nil_tu,0 as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='3' and MONTH(tgl_spp)='$bln' and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu,0 as nil_tu, isnull(sum(nilai),0) as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='4' and MONTH(tgl_spp)='$bln' and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu,0 as nil_tu,0 as nil_ls_gj,isnull(sum(nilai),0) as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='5' and MONTH(tgl_spp)='$bln' and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu,0 as nil_tu,0 as nil_ls_gj,0 as nil_ppkd,isnull(sum(nilai),0) as nil_ls_brg from trhspp where jns_spp ='6' and MONTH(tgl_spp)='$bln' and kd_skpd ='$kriteria')z";
                $hasil = $this->db->query($sql);
                $lcno = 0;
                foreach ($hasil->result() as $row)
                {
                  
                    
                            $cRet .=  "<tr>
                                <td colspan='9' align='right' width='3%' style='font-size:10px'>TOTAL</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->up)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->gu)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->tu)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->gj)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->barang)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($row->ppkd)."</td>
                              </tr>  "; 
                           
                  
                }
                
        $cRet .="</table>";                                
                
                   $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'>MENGETAHUI :</td>                    
                    <td align='center' width='25%'>$daerah, </td></tr>
                    <tr><td align='center' width='25%'>Pengguna Anggaran</td>                    
                    <td align='center' width='25%'>Bendahara Pengeluaran</td></tr>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td>                    
                    <td align='center' width='25%'><b><u>$nama2</u></b><br>
                    $pangkat2 <br>
                     NIP. $nip2</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>";
                  
        $data['prev']= $cRet;
        $this->tukd_model->_mpdf('',$cRet,'15','10',5,'10');   
        echo $cRet;
    }

 }