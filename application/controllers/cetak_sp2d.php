<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_sp2d extends CI_Controller {

	function __construct(){	 
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
	} 

     function reg_sp2d()
    {
        $reg = $this->session->userdata('kdskpd');
        $cek_reg = substr($reg,18,4);        
        
        if($cek_reg=='0000'){

	        $data['page_title']= 'REGISTER S P 2 D';
	        $this->template->set('title', 'REGISTER S P 2 D');   
	        $this->template->load('template','tukd/register/sp2d_global',$data) ;     
        }else{
	        $data['page_title']= 'REGISTER S P 2 D';
	        $this->template->set('title', 'REGISTER S P 2 D');   
	        $this->template->load('template','tukd/register/sp2d',$data) ;     
        }        
        
        
    }
	function cetak_daftar_penguji($no_uji='',$ttd='',$dcetak='',$cetak='',$atas='',$bawah='',$kiri='',$kanan=''){
		$print = $cetak;
	
			$no_uji = str_replace('123456789','/',$this->uri->segment(3));
			$lcttd = str_replace('abcdefg',' ',$this->uri->segment(4));
		
		    $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
					$pangkat=$rowttd->pangkat;
                }
			$sqlcount="SELECT COUNT(a.no_sp2d) as jumlah FROM trduji a INNER JOIN trhuji b ON a.no_uji=b.no_uji WHERE a.no_uji='$no_uji'";
                 $sql123=$this->db->query($sqlcount);
                 foreach ($sql123->result() as $rowcount)
                {
                    $jumlah=$rowcount->jumlah;                    
                   
                }
			$PageCount='$page';	
			$cRet ='';
			$cRet .="<table style='border-collapse:collapse;font-weight:bold;font-family:Tahoma; font-size:12px' border='0' width='100%' align='center' cellspacing='0' cellpadding='0'>
            <tr >
                <td width='100%' align='center' colspan=4 style='font-size:18px'>DAFTAR PENGUJI / PENGANTAR<br>SURAT PERINTAH PENCAIRAN DANA</td></tr>
					 <TR >
						<TD align='left' width='10%'>Tanggal </TD>
						<TD align='left' width='70%'>: ".$this->support->tanggal_format_indonesia($dcetak)."</TD>
						<TD align='left' width='10%'></TD>
						<TD align='right'  width='20%'>Lembaran ke 1</TD>
					 </TR>					 
					 <TR>
						<TD align='left'> Nomor</TD>
						<TD align='left'  >: ".$no_uji."</TD>
						<TD align='left' > </TD>
						<TD align='right' >Terdiri dari ".$jumlah." lembar </TD>
					 </TR>
					 </TABLE>";

			$cRet .=" <table style='border-collapse:collapse;font-family:Tahoma; font-size:11px' width='100%' align='center' border='1' cellspacing='0' cellpadding='1'>               
				<thead>
			   <tr style='font-size:12px;font-weight:bold;'>
                    <td width='5%' align='center'><b>NO</b></td>
                    <td width='10%' align='center' ><b>TANGGAL DAN<br>NOMOR SP2D</b></td>
                     <td  width='28%' align='center'><b>ATAS NAMA<br>( YANG BERHAK )</b>
					 </td>
					 <td width='20%' align='center' ><b>SKPD</b>        
                    </td>
					<td width='7%' align='center' ><b>NOMOR<br/>REKENING</b>        
                    </td>
                    <td  width='10%' align='center'><b>JUMLAH KOTOR<br>(Rp)</b>
					 </td>					 
                    <td width='10%' align='center' ><b>JUMLAH<br>POTONGAN</b>
                    </td>
                    <td width='10%' align='center'><b>JUMLAH<br>BERSIH</b>
                    </td>
                    <td  width='10%' align='center'><b>TANGGAL<br>TRANSFER</b>
                    </td>
                   
                </tr>
				<tr style='font-size:11px;font-weight:bold;'>	
					<td align='center' >1
                    </td>
					<td align='center' >2
                    </td>
					<td align='center' >3
                    </td>
					<td align='center' >4
                    </td>
					<td align='center' >5
                    </td>
					<td align='center' >6
                    </td>
					<td align='center' >7
                    </td>
					<td align='center' >8
                    </td>
					<td align='center' >9
                    </td>
				</tr>
				</thead>
				";
			
			  $sql = "SELECT b.no_sp2d,c.tgl_sp2d,c.nmrekan,c.pimpinan,c.alamat,c.kd_skpd,c.nm_skpd
,c.jns_spp,c.jenis_beban,c.kotor,c.pot,c.no_rek FROM TRHUJI a inner join TRDUJI b on a.no_uji=b.no_uji 
LEFT join (
SELECT a.*,ISNULL(SUM(b.nilai),0)pot FROM (select no_sp2d,no_spm,tgl_sp2d,b.nmrekan,b.alamat,b.pimpinan,
a.kd_skpd,d.nm_skpd,a.jns_spp,a.jenis_beban, isnull(SUM(z.nilai),0)kotor,a.no_rek
from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
INNER JOIN trdspp z ON b.no_spp=z.no_spp AND b.kd_skpd=z.kd_skpd
INNER JOIN ms_skpd d on a.kd_skpd=d.kd_skpd
GROUP BY no_sp2d,no_spm,tgl_sp2d,b.nmrekan,b.alamat,b.pimpinan,
a.kd_skpd,d.nm_skpd,a.jns_spp,a.jenis_beban,a.no_rek)a 
LEFT JOIN 
trspmpot b ON a.no_spm=b.no_spm and a.kd_skpd=b.kd_skpd
GROUP BY no_sp2d,a.no_spm,tgl_sp2d,a.nmrekan,a.alamat,a.pimpinan,
a.kd_skpd,a.nm_skpd,a.jns_spp,a.jenis_beban,a.kotor,a.no_rek) c on b.no_sp2d=c.no_sp2d WHERE a.no_uji='$no_uji'";
			 $hasil = $this->db->query($sql);
                    $lcno = 0;
                     $total_kotor=0;
                     $total_pot=0;
					 
					 foreach ($hasil->result() as $row)
                    {
                       $lcno = $lcno + 1;
					   $no_sp2d=empty($row->no_sp2d) || $row->no_sp2d == '' ? ' ' :$row->no_sp2d;
					   //$tgl_sp2d=$row->tgl_sp2d;
					   $nmrekan=empty($row->nmrekan) || $row->nmrekan == '' ? ' ' :$row->nmrekan;
					   $pimpinan=empty($row->pimpinan) || $row->pimpinan == '' ? ' ' :$row->pimpinan;
					   $alamat=empty($row->alamat) || $row->alamat == '' ? ' ' :$row->alamat;
					   $kd_skpd=empty($row->kd_skpd) || $row->kd_skpd == '' ? ' ' :$row->kd_skpd;
					   $nm_skpd=empty($row->nm_skpd) || $row->nm_skpd == '' ? ' ' :$row->nm_skpd;
					   $jns=empty($row->jns_spp) || $row->jns_spp == '' ? ' ' :$row->jns_spp;
					   $jns_bbn=empty($row->jenis_beban) || $row->jenis_beban == '' ? ' ' :$row->jenis_beban;
					   $kotor=empty($row->kotor) || $row->kotor == '' ? 0 :$row->kotor;
					   $pot=empty($row->pot) || $row->pot == '' ? 0 :$row->pot;
					   $no_rek2=empty($row->no_rek) || $row->no_rek == '' ? 0 :$row->no_rek;
					   
					   $total_kotor=$kotor+$total_kotor;
					   $total_pot=$pot+$total_pot;
					   //$total_bersih=$total_kotor-$total_pot;
					   $tgl_sp2d=empty($row->tgl_sp2d) || $row->tgl_sp2d == '' ? ' ' :$this->tukd_model->tanggal_ind($row->tgl_sp2d);
			             $cekbp = substr($kd_skpd,18,4);
                         if($cekbp=='0000'){
                        $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$kd_skpd' AND kode='BK'";
                        }else{
                        $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$kd_skpd' AND kode='BPP'";   
                        }
							 $sqlnam=$this->db->query($sqlnam);
							 foreach ($sqlnam->result() as $rownam)
							{
								$nama_ben=$rownam->nama;                    
								$jabat_ben=$rownam->jabatan;                    
							}
						$nama_ben = empty($nama_ben) || $nama_ben == 'NULL' ? 'Belum Ada data Bendahara' :$nama_ben;
						$jabat_ben = empty($jabat_ben) || $jabat_ben == 0 ? ' ' :$jabat_ben;
						
						
		if(($jns==6) && ($jns_bbn==3) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan, $pimpinan <br>$alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd 
					</td>	
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		} else if (($jns==6) && ($jns_bbn==2) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan, $pimpinan <br>$alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>					
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		
		}else if (($jns==4) && ($jns_bbn==9) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan, $pimpinan <br>$alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>			
					<td valign='top' align='center' >$no_rek2 
					</td>	
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		
		} else if(($jns==5) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan <br> $alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		}  else{
		  $cRet .=" <tr >
                    <td valign='top' align='center' >$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left' >$nama_ben <br>$jabat_ben $nm_skpd
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
			
		}
				};
				
			 $cRet .=" <tr style='font-size:11px;font-weight:bold;'>
                    <td colspan='5' align='center' >TOTAL
                    </td>
                    <td  align='right' >".number_format($total_kotor,"2",",",".")."&nbsp; 
					</td>
					<td  align='right' >".number_format($total_pot,"2",",",".")."&nbsp; 
					</td>
					<td  align='right' >".number_format($total_kotor-$total_pot,"2",",",".")."&nbsp;
					</td>
					<td  align='center' >&nbsp; 
					</td>
                </tr>
				";
			$cRet .='</table>';
			
			$cRet .=" <table style='border-collapse:collapse;font-weight:bold;font-family:Tahoma; font-size:11px;' border='0' width='100%' align='center' cellspacing='0' cellpadding='0'>
			
			<tr >
				<td align='left' width='70%' style='height: 30px;' >&nbsp;&nbsp;Diterima oleh : ................................................</td>
				<td align='center' width='30%' >$jabatan</td>
				
				</tr>
			<tr>
				<td>&nbsp;&nbsp;.....................................................</td>
				<td align='center'>$pangkat</td>
				</tr>
			<tr>
				<td colspan='2' ><br>&nbsp;&nbsp;Petugas Bank / Pos</td>
				</tr>
			<tr >
				<td width='100%' colspan='2' style='height: 50px;' >&nbsp;</td>
				</tr>
			<tr>
				<td>&nbsp;</td>
				<td align='center'><u>$nama</u></td>
				</tr>
			<tr>
				<td>&nbsp;</td>
				<td align='center'>NIP. $nip</td>
				</tr>
			<tr>
				<td><align='left' style='width: 250px;'>__________________________________</td>
				<td align='center'></td>
				</tr>
				</table>";

			$data['prev']= 'Kartu Kendali';
			if ($print==1){

	    $this->master_pdf->_mpdf('',$cRet,10,10,10,1,'','','',5);

				

		} else{
		  $cRet = str_replace('$page', '', $cRet);
		  echo $cRet;
		}
	
	}

   	function antrian_sp2d_cair(){
		
		$n = date("Y-m-d");
		$tanggalsbl = $this->support->tanggal_format_indonesia($n);
		$thn_ang = $this->session->userdata('pcThang');
		$cRet="";
		$cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
			<tr>
                <td align='center' colspan='16' style='font-size:14px;border: solid 1px white;'><b>PEMERINTAH KOTA PONTIANAK<br>DAFTAR ANTRIAN SP2D TERBIT DAN STATUS ADVICE</b><br> <b>TAHUN ANGGARAN $thn_ang</b></td>
            </tr>
            
			</table>
			<table style='border-collapse:collapse; border-color: black;font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='1' >
            <thead> 
			<tr>
                <td align='center' bgcolor='#CCCCCC' width='5%' style='font-weight:bold;'>NOMOR</td>                 
                <td align='center' bgcolor='#CCCCCC' width='30%' style='font-weight:bold'>SKPD</td>
                <td align='center' bgcolor='#CCCCCC' width='15%' style='font-weight:bold'>SP2D</td>
				<td align='center' bgcolor='#CCCCCC' width='10%' style='font-weight:bold'>TANGGAL TERBIT</td>
                <td align='center' bgcolor='#CCCCCC' width='10%' style='font-weight:bold'>TANGGAL ADVICE</td>
                <td align='center' bgcolor='#CCCCCC' width='30%' style='font-weight:bold'>STATUS ANTRIAN</td>
            </tr>
            <tr>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>1</td>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>2</td>
				<td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>3</td>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>4</td>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>5</td>                
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>6</td>
            </tr>
			<tr>
				<td></td>
                <td>Tanggal : $tanggalsbl</td>
				<td></td>
                <td></td>                
                <td></td>
                <td></td>
			</tr>
            <tr>
				<td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
				<td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
			</tr>
			</thead>";
		
        
		$sql2 = "
                SELECT c.nm_skpd,c.no_sp2d,convert(varchar(20), c.tgl_sp2d,105) tgl_sp2d,c.tgl_uji,case when c.uji='1' then 'SP2D Telah Advice, Belum Cair' else 'SP2D Terbit, Belum Advice' end as hasil from(
                select a.nm_skpd,a.no_sp2d,a.tgl_sp2d,
                (select convert(varchar(20), tgl_uji,105) from TRDUJI where no_sp2d=a.no_sp2d) tgl_uji, 
                (select COUNT(no_sp2d) from TRDUJI where no_sp2d=a.no_sp2d) uji 
                from trhsp2d a where a.no_kas_bud is null and a.sp2d_batal is null
                )c order by c.uji,c.tgl_sp2d";
		$hasil2 = $this->db->query($sql2);
		$i=0;
		foreach ($hasil2->result() as $row){
			
            $i=$i+1;
            $init1 = $row->nm_skpd;
			$init2 = $row->no_sp2d;			
            $init3 = $row->tgl_sp2d;            
			$init4 = $row->tgl_uji;
			$init5 = $row->hasil;		
			
			$cRet .= '
			
			<tr>
				<TD style="border-top:hidden;" align="center">'.$i.'</TD>
                <TD style="border-top:hidden;" align="left">'.$init1.'</TD>				
                <TD style="border-top:hidden;" align="left">'.$init2.'</TD>
				<TD style="border-top:hidden;" align="center">'.$init3.'</TD>                
				<TD style="border-top:hidden;" align="center">'.$init4.'</TD>
				<TD style="border-top:hidden;" align="left">'.$init5.'</TD>				                
			</tr>';
		}	
			
			$cRet .='</table>';	
		
		
			 $data['prev']= $cRet;    
			 echo ("<title>DAFTAR ANTRIAN SP2D CAIR</title>");
			 echo $cRet;
			
	}

   function sp2d(){
		//$jns = $this->uri->segment(3);
		$lntahunang = $this->session->userdata('pcThang');
        $lcnosp2d = str_replace('123456789','/',$this->uri->segment(3));
       // $lcttd = str_replace('123456789','/',$this->uri->segment(5));
        $lcttd = str_replace('abc',' ',$this->uri->segment(5));
	    $banyak = $this->uri->segment(6);
		$jns_cetak = $this->uri->segment(7);
        $a ='*'.$lcnosp2d.'*';
        $csql = "SELECT a.*,
				(SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan,
				(SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
				(SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhsp2d a WHERE a.no_sp2d = '$lcnosp2d'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
		$lckd_skpd  = $trh->kd_skpd;
        $lcnospm    = $trh->no_spm;
        $ldtglspm   = $trh->tgl_spm;
        $lcnmskpd   = $trh->nm_skpd;
        $lckdskpd   = $trh->kd_skpd;
        $alamat     = $trh->alamat;
        $lcnpwp     = $trh->npwp;
        $rekbank    = $trh->no_rek;
        $lcperlu    = $trh->keperluan;
        $lcnospp    = $trh->no_spp;
        $tgl        = $trh->tgl_sp2d;
        $n          = $trh->nilai;
		$pimpinan	= $trh->pimpinan;
        $nmrekan	=$trh->nmrekan;
		$jns_bbn	=$trh->jenis_beban;
		$jns		=$trh->jns_spp;
        $bank=$trh->bank;
		$banyak_kar = strlen($lcperlu);
        $tanggal    = $this->support->tanggal_format_indonesia($tgl);
		//$banyak = $banyak_kar > 400 ? 14 :23;                     

        
		$sqlrek="SELECT bank,rekening, npwp FROM ms_skpd WHERE kd_skpd = '$lckd_skpd' ";
                 $sqlrek=$this->db->query($sqlrek);
                 foreach ($sqlrek->result() as $rowrek)
                {
                    $bank_ben=$rowrek->bank;                    
                    $rekben=$rowrek->rekening;                    
                    $npwp_ben= $rowrek->npwp;
                }
			$rek_ben = empty($rekben) || $rekben == 0 ? '' :$rekben;
			$npwp_ben = empty($npwp_ben) || $npwp_ben == 0 ? '' :$npwp_ben;
			$nama_bank = empty($bank) ? 'Belum Pilih Bank' :$this->rka_model->get_nama($bank,'nama','ms_bank','kode');
			//$nama_bank_ben = empty($bank_ben) ? 'Belum Pilih Bank' :$this->rka_model->get_nama($bank_ben,'nama','ms_bank','kode');			
			$sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
					$pangkat=$rowttd->pangkat;
                }
                
                $cekbp = substr($lckdskpd,8,2);
                if($cekbp=='00'){
          $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$lckdskpd' AND kode='BK' ";
        }else{
          $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$lckdskpd' AND kode='BPP' ";
        }
                 $sqlnam=$this->db->query($sqlnam);
                 foreach ($sqlnam->result() as $rownam)
                {
                    $nama_ben=$rownam->nama;                    
                    $jabat_ben=$rownam->jabatan;                    
                }
		$nama_ben = empty($nama_ben) ? 'Belum Ada data Bendahara' :$nama_ben;
		$jabat_ben = empty($jabat_ben) ? ' ' :$jabat_ben;
        
		if (($jns == '1') or ($jns == '2')  or ($jns == '4') or ($jns == '5') or ($jns == '7')){
		$kd_kegi = '';                    
		$nm_kegi = ''; 
		$kd_prog = ''; 
		$nm_prog = '';	
		}
		else {
		$sql12="SELECT kd_kegiatan FROM trdspp a INNER JOIN trhsp2d b ON a.no_spp = b.no_spp AND a.kd_skpd=b.kd_skpd 
				WHERE b.kd_skpd = '$lckdskpd' AND no_sp2d='$lcnosp2d' group by kd_kegiatan ";
                 $sqlrek12=$this->db->query($sql12);
                 foreach ($sqlrek12->result() as $rowrek)
                {
                    $kd_kegi=$rowrek->kd_kegiatan;                    
                }
		$nm_kegi = " - ".$this->rka_model->get_nama($kd_kegi,'nm_kegiatan','trskpd','kd_kegiatan') ; 
		$kd_prog = $this->support->left($kd_kegi,18); 
		$nm_prog = " - ".$this->rka_model->get_nama($kd_prog,'nm_program','trskpd','kd_program'); 
		}
		
		if($jns_cetak=='2'){
			$tinggi='150px';
			//$banyak=9;
			$banyak=10;
		} else 
		if($jns_cetak=='1'){
			$tinggi='80px';
			//$banyak=15;
			$banyak=16;
		}else{
			$tinggi='10px';
			$banyak=$banyak;
		}		
		
		$cRet = '';
		$cRet .= "
		<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px;'  width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>";
        $cRet .="
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:16px'> PEMERINTAH KOTA PONTIANAK
            </td> 
        </tr>
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:18px'> BADAN KEUANGAN DAERAH (BKD)
            </td> 
        </tr>		
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:11px'> Jalan Letnan Jendral Sutoyo. Telp / Fax (0561) 732509 / 741641 <br/> Kota Pontianak - 81147
            </td> 
        </tr>
		</table><br/>
		
		";
        $cRet .= "
		<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px;'  width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>";
        $cRet .="
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:12px'> PEMERINTAH KOTA PONTIANAK
            </td>
            <td align='center' width='50%'>
                <table style='border-collapse:collapse;font-size:12px; font-weight: bold;' width='100%' align='center' cellspacing='4' cellpadding='0'>
                    <tr>
                        <td align='right'>
                            <b>Nomor : $lcnosp2d</b>
                        </td>
                    </tr>
                    <tr>
                        <td align='center'>
                            SURAT PERINTAH PENCAIRAN DANA<br>(SP2D)
                        </td>
                    </tr>
                </table>
            </td>
        </tr>   
        <tr>
            <td style='border-left:solid 1px black;' >
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' valign='top' border='1' cellspacing='4' cellpadding='0'>
      					<tr>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' width='30%' align='left' valign='top'>&nbsp;Nomor SPM</td>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' width='2%' valign='top'>:</td>
						<td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' width='69%' valign='top'>$lcnospm</td>
                    </tr>
                    <tr>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>&nbsp;Tanggal</td>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top' >:</td>
						<td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>".$this->support->tanggal_format_indonesia($ldtglspm)."</td>
                    </tr>
                    <tr>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>&nbsp;SKPD</td>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>:</td>
						<td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top' height='35px'>$lckd_skpd $lcnmskpd</td>
                    </tr>
                </table>
            </td>
            <td>
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%'  valign='top' border='0' cellspacing='4' cellpadding='0'>
                    <tr>
                        <td valign='top'>&nbsp;Dari &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Bendahara Umum Daerah (BUD)</td>
                    </tr>
					 <tr>
                        <td valign='top' >&nbsp;Tahun Anggaran : &nbsp;$lntahunang</td>
                    </tr>
					<tr>
                        <td valign='top' >&nbsp;</td>
                    </tr>
					<tr>
                        <td valign='top' >&nbsp;</td>
                    </tr>					
                </table>
            </td>
        </tr>
			<tr>
		<td colspan='2'>
			<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='1' cellspacing='4' cellpadding='0'>
			<tr>
				<td style='border-bottom: hidden; border-right: hidden;' width='120px'>&nbsp;Bank/Pos</td>
                <td style='border-bottom: hidden; border-right: hidden;' width='10px' align='left'>:</td>
				<td style='border-bottom: hidden;' >PT. Bank Kalbar Cabang Utama Pontianak</td>
			</tr>
			<tr>
				<td style='border-bottom: hidden;' colspan='3' >&nbsp;Hendaklah mencairkan / memindahbukukan dari baki Rekening Nomor 100.100.283.0</td>
			</tr>
			<tr>
				<td style='border-bottom: hidden; border-right: hidden;' >&nbsp;Uang sebesar Rp</td>
                <td width='1' style='border-bottom: hidden; border-right: hidden;' width='10px' align='left'>:</td>
				<td style='border-bottom: hidden;' >".number_format($n,'2',',','.')."  , (".$this->tukd_model->terbilang($n).") </td>
			</tr>
			</table>
        </td>
		</tr>	
        <tr>
            <td colspan='2'>";
		if(($jns==6) && ($jns_bbn==3)){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$this->support->right($kd_prog,2)."$nm_prog
					<br>".$this->support->right($kd_kegi,3)."$nm_kegi
					</td>

				</tr>
                </table> ";
		}
		else
		if(($jns==6) && ($jns_bbn==2)){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$this->support->right($kd_prog,2)."$nm_prog
					<br>".$this->support->right($kd_kegi,3)."$nm_kegi
					</td>

				</tr>
                </table> ";
		}else
		if(($jns==4) && ($jns_bbn==9)){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$this->support->right($kd_prog,2)."$nm_prog
					<br>".$this->support->right($kd_kegi,3)."$nm_kegi
					</td>

				</tr>
                </table> ";
		}	
		else if($jns==5){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan ($lcnmskpd)</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$this->support->right($kd_prog,2)."$nm_prog
					<br>".$this->support->right($kd_kegi,3)."$nm_kegi
					</td>

				</tr>
                </table> ";
		}else{
			
			$cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada </td>
					<td valign='top' width='10px'>:&nbsp;</td>
                    <td valign='top' font-family: Arial; >$nama_ben - $jabat_ben ($lcnmskpd)</td>
					</tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                     <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Bank/Pos</td>
					<td valign='top' >:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$this->support->right($kd_prog,2)."$nm_prog
					<br>".$this->support->right($kd_kegi,3)."$nm_kegi
					</td>
				</tr>
                </table> ";
			
		}
         $cRet	.="  </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:11px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr >
                        <td width='5%' align='center'><b>NO</b></td>
                        <td width='28%' align='center'><b>KODE REKENING</b></td>
                        <td align='center'><b>URAIAN</b></td>
                        <td width='15%' align='center'><b>JUMLAH (Rp)</b></td>
                    </tr>
					<tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                    </tr>";
			$sql_total="SELECT sum(nilai)total FROM trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
                 $sql_x=$this->db->query($sql_total);
                 foreach ($sql_x->result() as $row_x)
                {
                    $lntotal=$row_x->total;                    
                }
				if(($jns==1) || ($jns==2) || ($jns==7)){
                                        $sql = "select SUM(nilai) nilai from trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
                                        $lntotal = 0;
                                        foreach ($hasil->result() as $row)
                                        {
                                           $lcno = $lcno + 1;
                                           $lntotal = $lntotal + $row->nilai;
										    $cRet .="<tr>
                                                        <td style='border-bottom: hidden;' align='center'>&nbsp;1</td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $lckd_skpd  </td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $lcnmskpd</td>
                                                        <td style='border-bottom: hidden;' align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                                    </tr>"; 
                                          
										}  
											if($lcno<=$banyak)
											   {
												 for ($i = $lcno; $i <= $banyak; $i++) 
												  {
													 $cRet .="<tr>
                                                        <td style='border-top: hidden;' align='center'>&nbsp;</td>
                                                        <td style='border-top: hidden;' ></td>
                                                        <td style='border-top: hidden;'></td>
                                                        <td style='border-top: hidden;' align='right'></td>
                                                    </tr>";    
												  }                                                   
											   } 
										   }
				else{
				
				
				$sql1 = "select COUNT(*) as jumlah from 
							(select kd_kegiatan,left(kd_rek5,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek2 b on left(kd_rek5,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' 
							group by left(kd_rek5,2),nm_rek2,kd_kegiatan
							union all
							select kd_kegiatan,left(kd_rek5,3)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek3 b on left(kd_rek5,3)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek5,3),nm_rek3,kd_kegiatan
							union all
							select kd_kegiatan,left(kd_rek5,5)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek4 b on left(kd_rek5,5)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek5,5),nm_rek4,kd_kegiatan
							union all
							select kd_kegiatan,left(a.kd_rek5,7)kd_rek,b.nm_rek5 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek5 b on left(a.kd_rek5,7)=b.kd_rek5 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(a.kd_rek5,7),b.nm_rek5,kd_kegiatan
							) tox";
						$hasil1 = $this->db->query($sql1);
						$row1 = $hasil1->row();
						$jumlahbaris = $row1->jumlah;  
						if($jumlahbaris<$banyak){
							$sql = "select * from (select kd_kegiatan,left(kd_rek5,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek2 b on left(kd_rek5,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek5,2),nm_rek2,kd_kegiatan
							union all
							select kd_kegiatan,left(kd_rek5,3)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek3 b on left(kd_rek5,3)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek5,3),nm_rek3,kd_kegiatan
							union all
							select kd_kegiatan,left(kd_rek5,5)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek4 b on left(kd_rek5,5)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek5,5),nm_rek4,kd_kegiatan
							union all
							select kd_kegiatan,left(a.kd_rek5,7)kd_rek,b.nm_rek5 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek5 b on left(a.kd_rek5,7)=b.kd_rek5 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(a.kd_rek5,7),b.nm_rek5,kd_kegiatan
							) tox order by kd_kegiatan, kd_rek";
						}else{
							$sql = "select * from (select '1' urut, kd_kegiatan,left(kd_rek5,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek2 b on left(kd_rek5,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek5,2),nm_rek2,kd_kegiatan
							union all
							select '2' urut, kd_kegiatan,left(kd_rek5,3)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek3 b on left(kd_rek5,3)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek5,3),nm_rek3,kd_kegiatan
							union all
							select '3' as urut, '' as kd_kegiatan, '' kd_rek, '(Rincian Terlampir)' as nm_rek, 0 as nilai
							) tox order by urut,kd_rek";	
						}
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
										$lcno_baris = 0;
                                       // $lntotal = 0;
                                        foreach ($hasil->result() as $row)
                                        {	
											$lcno_baris = $lcno_baris + 1;										
											if (strlen($row->kd_rek)>=7){
											$lcno = $lcno + 1;
											$lcno_x = $lcno;
											}
											else {
												$lcno_x ='';
											}
//                                           $lntotal = $lntotal + $row->nilai;                     
                                            $ceknama = $row->nm_rek;                      
                                            if($ceknama!='(Rincian Terlampir)'){                                                
                                           $cRet .="<tr>
                                                        <td style='border-bottom: hidden;' align='center'>&nbsp;$lcno_x</td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->kd_kegiatan.".$this->tukd_model->dotrek($row->kd_rek)." </td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->nm_rek</td>
                                                        <td style='border-bottom: hidden;' align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                                    </tr>";
                                            }else{
                                                
                                           $cRet .="<tr>
                                                        <td style='border-bottom: hidden;' align='center'>&nbsp;$lcno_x</td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->kd_kegiatan.".$this->tukd_model->dotrek($row->kd_rek)." </td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->nm_rek</td>
                                                        <td style='border-bottom: hidden;' align='right'></td>
                                                    </tr>";   
                                            }    
                                            
                                        }
                                        if($lcno_baris<=$banyak)
                                       {
                                         for ($i = $lcno_baris; $i <= $banyak; $i++) 
                                          {
                                            $cRet .="<tr>
                                                        <td style='border-top: hidden;' align='center'>&nbsp;</td>
                                                        <td style='border-top: hidden;' ></td>
                                                        <td style='border-top: hidden;'></td>
                                                        <td style='border-top: hidden;' align='right'></td>
                                                    </tr>";    
                                          }                                                   
                                       }
                                       
				}     
             $cRet .="<tr>
                        <td align='right' colspan='3'>&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align='right'><b>".number_format($lntotal,"2",",",".")."</b>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='4'>&nbsp;Potongan-potongan</td>
                    </tr>
                    <tr>
                        <td  align='center'><b>NO</b></td>
                        <td  align='center'><b>Uraian (No.Rekening)</b></td>
                        <td  align='center'><b>Jumlah(Rp)</b></td>
                        <td  align='center'><b>Keterangan</b></td>
                    </tr>";
                    
                    $sql = "select * from trspmpot where no_spm='$lcnospm' AND kd_rek5 IN('4140611','2110501','2110701','2110801','2110901','4140612')";
                            $hasil = $this->db->query($sql);
                            $lcno = 0;
                            $lntotalpot = 0;
                            foreach ($hasil->result() as $row){
                               $lcno = $lcno + 1;
                               $lntotalpot = $lntotalpot + $row->nilai;
                                $cRet .="<tr>
                                            <td align='center'>&nbsp;$lcno</td>
                                            <td>&nbsp; $row->nm_rek5</td>
                                            <td align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>";    
                            }
							if($lcno<=3)
                                       {
                                         for ($i = $lcno; $i < 3; $i++) 
                                          {
                                            $cRet .= "<tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";    
                                          }                                                   
                                       }
                                        
                    $cRet .="
                    <tr>
                        <td>&nbsp;</td>
                        <td align='right'><b>Jumlah</b>&nbsp;</td>
                        <td align='right'><b>".number_format($lntotalpot,"2",",",".")."</b>&nbsp;</td>
                        <td></td>
                    </tr>
                     <tr>
                        <td colspan='4'>&nbsp;Informasi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(tidak mengurangi jumlah pembayaran SP2D)</i></td>
                    </tr>
            
                    <tr>
                        <td align='center'><b>NO</b></td>
                        <td align='center'><b>Uraian (No.Rekening)</b></td>
                        <td align='center'><b>Jumlah(Rp)</b></td>
                        <td align='center'><b>Keterangan</b></td>
                    </tr>";
                     $sql = "select 1 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek5 IN('2130301')
							UNION ALL
							select 2 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek5 NOT IN('4140611','2130301','2110501','2110701','2110801','2110901','4140612')
							ORDER BY urut,kd_rek5";
                            $hasil = $this->db->query($sql);
                            $lcno = 0;
                            $lntotalpott = 0;
                            foreach ($hasil->result() as $row)
                            {
                               $lcno = $lcno + 1;
                               $lntotalpott = $lntotalpott + $row->nilai;
                               $kode_rek=$row->kd_rek5;
							   if($kode_rek=='2130101'){
								   $nama_rek='PPh 21';
							   } else if ($kode_rek=='2130201'){
								   $nama_rek='PPh 22';
							   } else if($kode_rek=='2130301'){
								   $nama_rek='PPN';
							   } else if($kode_rek=='2130401'){
								   $nama_rek='PPh 23';
							   } else if($kode_rek=='2130501'){
								   $nama_rek='PPh Pasal 4 ayat 2';
							   } else{
								    $nama_rek=$row->nm_rek5;
							   }
							   $cRet .="<tr>
                                            <td align='center'>&nbsp;$lcno</td>
                                            <td> &nbsp; $nama_rek</td>
                                            <td align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>";    
                            }
							if($lcno<=4)
                                       {
                                         for ($i = $lcno; $i < 4; $i++) 
                                          {
                                            $cRet .= "<tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";    
                                          }                                                   
                                       }
							
							
							
							$jum_bayar=strval($lntotal-$lntotalpot);
							$bil_bayar = strval($lntotal-($lntotalpot+$lntotalpott));
                    $cRet .="
                    <tr>
                        <td>&nbsp;</td>
                        <td align='right'><b>Jumlah</b>&nbsp;</td>
                        <td align='right'><b>".number_format($lntotalpott,"2",",",".")."</b>&nbsp;</td>
                        <td></td>
                    </tr>
                     
                </table>  
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='1' cellspacing='-1' cellpadding='-1'>
                   <tr>
                        <td colspan='4' valign='bottom' style='font-weight: bold;'>&nbsp;SP2D yang Dibayarkan</td>
                    </tr>
				   <tr>
				   
                        <td width='28%' align='left'>&nbsp;<b>Jumlah yang Diterima</b></td>
                        <td style='border-left: hidden;' width='4%' align='left'><b>&nbsp;RP</b></td>
                        <td style='border-left: hidden; font-size:12px;' width='50%' align='right'><b>&nbsp;".number_format($lntotal,"2",",",".")."</b></td>
						<td style='border-left: hidden;' width='20%' align='center'>&nbsp;</td>
						</tr>
                    <tr > 
                        <td align='left'>&nbsp;<b>Jumlah Potongan</b></td>
                        <td style='border-left: hidden;' align='left'><b>&nbsp;RP</b></td>
                        <td style='border-left: hidden; font-size:12px;' align='right' ><b>&nbsp;".number_format($lntotalpot+$lntotalpott,"2",",",".")."</b></td>
						<td style='border-left: hidden;' >&nbsp;</td>
                    </tr>
                    <tr style='font-weight: bold;'>
                        <td align='left'>&nbsp;<b>Jumlah yang Dibayarkan</b></td>
                        <td style='border-left: hidden;' align='left'><b>&nbsp;RP</b></td>
                        <td style='border-left: hidden;font-size:12px;' align='right'><b>&nbsp;".number_format($lntotal-($lntotalpot+$lntotalpott),"2",",",".")."</b></td>
						<td style='border-left: hidden;' >&nbsp;</td>
                    </tr>                    
                </table>  
            </td>
        </tr>
        
        <tr>
            <td colspan='2'>
                <table style='border-collapse:collapse;font-weight: bold;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			<tr>
                        <td colspan='2' align='left' >&nbsp;Uang Sejumlah :
                        (&nbsp;".$this->tukd_model->terbilang($bil_bayar)."&nbsp;)</td>
						
	
                    </tr>
				<tr>
                        <td width='65%' align='left' style='font-size:10px' valign='top'>
                        <br>&nbsp;Lembar 1 : Bank Yang Ditunjukan<br>
                        &nbsp;Lembar 2 : Pengguna Anggaran/Kuasa Pengguna Anggaran<br>
                        &nbsp;Lembar 3 : Arsip Bendahara Umum Daerah (BUD)<br>
                        &nbsp;Lembar 4 : Pihak Ketiga<br>
                               
                        </td>
                        <td width='35%' align='center'>
                        <br>
                        Pontianak, $tanggal<br>
                        $jabatan
						<br>Kepala Bidang Perbendaharaan
                        <br>$pangkat
                        <br>
                        <br>
                        <br>
                        <u></u><br>
                        $nama
						<br>
                        NIP. $nip                
                        </td>
                   </tr>
                </table>  
            </td>
        </tr>
        
        
        </table>";
        $data['prev']= $cRet;
        //echo "$cRet"  ;  
        $this->_mpdf_sp2d2('',$cRet,10,5,5,'0');
    }

  function cetak_register_sp2d_global(){
		$kd_skpd     = $this->session->userdata('kdskpd');
        $skpdd = substr($kd_skpd,0,17).".0000";
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
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
            <td align='center' style='font-size:14px;border: solid 1px white;border:none;' colspan='12'><b> $kab</b></td>            
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border:none;' colspan='12'><b>REGISTER SP2D $judbln</b></td>
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border:none;' colspan='12'><b>$a $nama</b><br>&nbsp;</td>
        </tr>
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' rowspan='3'><b>No.</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='3'><b>Tanggal SP2D</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='36%' colspan='3'><b>NOMOR SPP/SPM/SP2D</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='19%' rowspan='3'><b>Uraian</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='36%' colspan='6'><b>Jumlah SP2D<br>(Rp)</b></td>
        </tr>  
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>SPP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>SPM</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>SP2D</b></td>
            
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>UP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>GU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>TU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='18%' colspan='3'><b>LS</b></td>
          </tr>
          <tr>
            
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Gaji</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Barang&<br>Jasa</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>PPKD</b></td>
          </tr>
          </thead>
          <tr>
            <td style='font-size:10px' align='center' width='3%'><b>1</b></td>
            <td style='font-size:10px' align='center' width='6%'><b>2</b></td>
            <td style='font-size:10px' align='center' width='36%' colspan='3'><b>3</b></td>
            <td style='font-size:10px' align='center' width='19%'><b>4</b></td>
            <td style='font-size:10px' align='center' width='36%' colspan='6'><b>5</b></td>
          </tr>";
        //$skpd = $this->uri->segment(3); 
        
		//kapi

        $where2= '';
        if ($jns <> '1'){                               
            $where2="and MONTH(tgl_sp2d)='$bln'";            
        }

		$kriteria = '';
        $kriteria = $this->uri->segment(3);
        $where ="";
        

        if ($kriteria <> ''){                               
            $where="where left(a.kd_skpd,17) =left('$kriteria',17) ";            
        }       
              $sqltot="SELECT distinct a.kd_skpd,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp = 1 and left(kd_skpd,17) = left('$kd_skpd',17) $where2) AS up,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp in ('2','7') and left(kd_skpd,17) = left('$kd_skpd',17) $where2) AS gu,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp = 3 and left(kd_skpd,17) = left('$kd_skpd',17) $where2) AS tu,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp in (4,5) and left(kd_skpd,17) = left('$kd_skpd',17) $where2) AS gaji,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp = 6 and left(kd_skpd,17) = left('$kd_skpd',17) $where2) AS ls
        from trhsp2d a
        where left(a.kd_skpd,17) = left('$kd_skpd',17)";       
              
              $hasiltot = $this->db->query($sqltot);
                $lcno = 0;
                foreach ($hasiltot->result() as $row)
                {
                    $totup= $row->up;
                    $totgu=$row->gu;
                    $tottu= $row->tu;
                    $totgaji= $row->gaji;
                    $totls= $row->ls;
                }
                
        $sql = "SELECT a.no_spm,a.no_spp,a.jns_spp,isnull(b.tgl_sp2d,'') tgl_sp2d ,b.no_sp2d,CASE WHEN sp2d_batal='1' then 'SP2D Dibatalkan' else b.keperluan END AS keperluan,
                b.nilai from TRHSPM a LEFT JOIN TRHSP2D b ON a.no_spm=b.no_spm $where $where2 order by b.tgl_sp2d,b.no_sp2d";
                $hasil = $this->db->query($sql);
                $lcno = 0;
                foreach ($hasil->result() as $row)
                {
                    $beban= $row->jns_spp;
                    $tanggal=$row->tgl_sp2d;
                    $spp= $row->no_spp;
                    $spm= $row->no_spm;
                    $sp2d= $row->no_sp2d;
                    $kkeperluan= $row->keperluan;
                    $n= $row->nilai;
                    $lcno = $lcno + 1;
                    switch ($beban) 
                    {
                        case '1': //UP
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>
                               
                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>
                               
                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '3': //TU
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '4': //LS gaji
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '5': //LS PPKD
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                              </tr>  "; 
                            break;
                        case '6': //LS barang dan jasa
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                    case '7': //GU
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>
                               
                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                            
                    }
                   
                }
                   $cRet .=  "<tr>

                                <td align='right' width='6%' colspan='6' style='font-size:10px'>Jumlah</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totup)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totgu)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($tottu)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totgaji)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totls)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                            </tr>  ";                  
                  
        $cRet .="</table>";


        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where  id_ttd='$kd_skpd'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat=$rowttd->pangkat;
                }

$cRet .="<table style='border-collapse:collapse; font-family:Tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'></td>
                    <td width='50%' align='center'>Pontianak, $tanggal</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'></td>
                    <td width='50%' align='center'>$jabatan</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>$pangkat</td>
                    </tr>                              
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>  
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>                   
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>                   
                    </tr>                   
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'><b><u>$nama</u></b></td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>NIP. $nip</td>
                    </tr>
                    
                  </table>";

        $data['prev']= $cRet;    
	   //landscape	
     //   $this->_mpdf('',$cRet,2,10,10,'0',1,'');
      //potrait
       // $this->support->_mpdf('',$cRet,2,10,5,'10',5,'1'); 
	 //  $this->tukd_model->_mpdf('',$cRet,'2','10',5,'1');   
		echo $cRet;
    }

  function cetak_register_sp2d(){
		$kd_skpd     = $this->session->userdata('kdskpd');
        $skpdd = substr($kd_skpd,0,17).".0000";
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$skpdd'";
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
            <td align='center' style='font-size:14px;border: solid 1px white;border:none;' colspan='12'><b> $kab</b></td>            
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border:none;' colspan='12'><b>REGISTER SP2D $judbln</b></td>
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border:none;' colspan='12'><b>$a $nama</b><br>&nbsp;</td>
        </tr>
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' rowspan='3'><b>No.</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='3'><b>Tanggal SP2D</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='36%' colspan='3'><b>NOMOR SPP/SPM/SP2D</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='19%' rowspan='3'><b>Uraian</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='36%' colspan='6'><b>Jumlah SP2D<br>(Rp)</b></td>
        </tr>  
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>SPP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>SPM</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>SP2D</b></td>
            
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>UP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>GU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='2'><b>TU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='18%' colspan='3'><b>LS</b></td>
          </tr>
          <tr>
            
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Gaji</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>Barang&<br>Jasa</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%'><b>PPKD</b></td>
          </tr>
          </thead>
          <tr>
            <td style='font-size:10px' align='center' width='3%'><b>1</b></td>
            <td style='font-size:10px' align='center' width='6%'><b>2</b></td>
            <td style='font-size:10px' align='center' width='36%' colspan='3'><b>3</b></td>
            <td style='font-size:10px' align='center' width='19%'><b>4</b></td>
            <td style='font-size:10px' align='center' width='36%' colspan='6'><b>5</b></td>
          </tr>";
        //$skpd = $this->uri->segment(3); 
        
		//kapi

        $where2= '';
        if ($jns <> '1'){                               
            $where2="and MONTH(tgl_sp2d)='$bln'";            
        }

		$kriteria = '';
        $kriteria = $this->uri->segment(3);
        $where ="";
        

        if ($kriteria <> ''){                               
            $where="where left(a.kd_skpd,17) =left('$kriteria',17) ";            
        }       
              $sqltot="SELECT distinct a.kd_skpd,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp = 1 and left(kd_skpd,17) =left('$kd_skpd',17) $where2) AS up,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp in ('2','7') and left(kd_skpd,17) =left('$kd_skpd',17) $where2) AS gu,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp = 3 and left(kd_skpd,17) =left('$kd_skpd',17) $where2) AS tu,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp in (4,5) and left(kd_skpd,17) =left('$kd_skpd',17) $where2) AS gaji,
        (SELECT sum(nilai) FROM trhsp2d WHERE jns_spp = 6 and left(kd_skpd,17) =left('$kd_skpd',17) $where2) AS ls
        from trhsp2d a
        where left(a.kd_skpd,17) =left('$kd_skpd',17)";       
              
              $hasiltot = $this->db->query($sqltot);
                $lcno = 0;
                foreach ($hasiltot->result() as $row)
                {
                    $totup= $row->up;
                    $totgu=$row->gu;
                    $tottu= $row->tu;
                    $totgaji= $row->gaji;
                    $totls= $row->ls;
                }
                
        $sql = "SELECT a.no_spm,a.no_spp,a.jns_spp,isnull(b.tgl_sp2d,'') tgl_sp2d, b.no_sp2d,CASE WHEN sp2d_batal='1' then 'SP2D Dibatalkan' else b.keperluan END AS keperluan,
                b.nilai from TRHSPM a LEFT JOIN TRHSP2D b ON a.no_spm=b.no_spm $where $where2 order by b.tgl_sp2d,b.no_sp2d";
                $hasil = $this->db->query($sql);
                $lcno = 0;
                foreach ($hasil->result() as $row)
                {
                    $beban= $row->jns_spp;
                    $tanggal=$row->tgl_sp2d;
                    $spp= $row->no_spp;
                    $spm= $row->no_spm;
                    $sp2d= $row->no_sp2d;
                    $kkeperluan= $row->keperluan;
                    $n= $row->nilai;
                    $lcno = $lcno + 1;
                    switch ($beban) 
                    {
                        case '1': //UP
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>
                               
                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>
                               
                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '3': //TU
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '4': //LS gaji
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                        case '5': //LS PPKD
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                              </tr>  "; 
                            break;
                        case '6': //LS barang dan jasa
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>

                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                            case '7': //GU
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($tanggal)."</td>
                                <td align='left' width='6%' style='font-size:10px'>$spp</td>
                                <td align='left' width='6%' style='font-size:10px'>$spm</td>
                                <td align='left' width='6%' style='font-size:10px'>$sp2d</td>
                               
                                <td align='left' width='19%' style='font-size:10px'>$kkeperluan</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($n)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                              </tr>  "; 
                            break;
                            
                    }
                   
                }
                   $cRet .=  "<tr>

                                <td align='right' width='6%' colspan='6' style='font-size:10px'>Jumlah</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totup)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totgu)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($tottu)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totgaji)."</td>
                                <td align='right' width='6%' style='font-size:10px'>".number_format($totls)."</td>
                                <td align='right' width='6%' style='font-size:10px'></td>
                            </tr>  ";                  
                  
        $cRet .="</table>";
        $nippa = str_replace('123456789',' ',$_REQUEST['ttd']);

$csql="SELECT nip as nip,nama,jabatan,pangkat  FROM ms_ttd WHERE id_ttd = '$nippa'";
                     $hasil = $this->db->query($csql);
                     $trh2 = $hasil->row(); 
                     
                     $lcNmPA = $trh2->nama;
                     $lcNipPA = $trh2->nip;
                     $lcJabatanPA = $trh2->jabatan;
                     $lcPangkatPA = $trh2->pangkat;

$cRet .="<table style='border-collapse:collapse; font-family:Tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'></td>
                    <td width='50%' align='center'>Pontianak, $tanggal</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'></td>
                    <td width='50%' align='center'>$lcJabatanPA</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>$lcPangkatPA</td>
                    </tr>                              
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>  
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>                   
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>                   
                    </tr>                   
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'><b><u>$lcNmPA</u></b></td>
                    </tr>
                    <tr>
                    <td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>NIP. $lcNipPA</td>
                    </tr>
                    
                  </table>";
        
        $data['prev']= $cRet;    
        echo $cRet;
        //$this->_mpdf('',$cRet,2,10,5,'10',5,'1'); 

    }




}