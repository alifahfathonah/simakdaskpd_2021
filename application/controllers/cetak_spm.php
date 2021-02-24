<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 select_pot_taspen() rekening gaji manual. harap cek selalu
 */

class cetak_spm extends CI_Controller {

 
    function __construct(){   
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }    
 
    function reg_spm(){
        $data['page_title']= 'REGISTER S P M';
        $this->template->set('title', 'REGISTER S P M');   
        $this->template->load('template','tukd/register/spm',$data) ; 
    }
	function lampiran_spm(){
		$lntahunang = $this->session->userdata('pcThang');
       
       	$cetak = $this->uri->segment(3);
		$lcnospm = str_replace('123456789','/',$this->uri->segment(4));
        $kd = $this->uri->segment(5);
        $jns = $this->uri->segment(6);
        $tanpa = $this->uri->segment(11);
        $baris = $this->uri->segment(12);
        $nmskpd=$this->rka_model->get_nama($kd,'nm_skpd','trhspp','kd_skpd');		
		$BK = str_replace('123456789',' ',$this->uri->segment(7));
        $lcttd = str_replace('123456789',' ',$this->uri->segment(9));

		
        $a ='*'.$lcnospm.'*';
         $csql = "SELECT a.*,
				(SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan,
				(SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
				(SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhspm a WHERE a.no_spm = '$lcnospm' and a.kd_skpd='$kd'";
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
        $tgl        = $trh->tgl_spm;
        $n          = $trh->nilai;
		$pimpinan	= $trh->pimpinan;
        $nmrekan	=$trh->nmrekan;
		$jns_bbn	=$trh->jenis_beban;
		$jns		=$trh->jns_spp;
        $bank=$trh->bank;
		$banyak_kar = strlen($lcperlu);
        $tanggal    = $this->support->tanggal_format_indonesia($tgl);
		//$banyak = $banyak_kar > 400 ? 14 :23;

		$sqlttd1="SELECT top 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$lcttd'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
					$pangkat=$rowttd->pangkat;
                }
				
		 $cRet ="";
		 $cRet	.="<br><br><br><br><br><br><br>  
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:13px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr >
                        <td width='100%' align='center'><b>Daftar Lampiran SPM Nomor: $lcnospm <br/> $nmskpd </b></td>
                    </tr>
					<tr>
                        <td align='center'><b>Tanggal : $tanggal</b></td>
                    </tr>
					<tr>
                        <td align='center'>&nbsp;</td>
                    </tr>
				</table>";
         $cRet	.="  
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

			$sql = "SELECT * from (
					select kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai, sum(nilai) jum from trdspp a inner join 
					ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
					group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
					union all
					select kd_sub_kegiatan,left(kd_rek6,4)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai, 0 jum from trdspp a inner join 
					ms_rek3 b on left(kd_rek6,4)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
					group by left(kd_rek6,4),nm_rek3,kd_sub_kegiatan
					union all
					select kd_sub_kegiatan,left(kd_rek6,6)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai, 0 jum  from trdspp a inner join 
					ms_rek4 b on left(kd_rek6,6)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
					group by left(kd_rek6,6),nm_rek4,kd_sub_kegiatan
					union all
					select kd_sub_kegiatan,left(a.kd_rek6,8)kd_rek,b.nm_rek5 as nm_rek,sum(nilai)nilai, 0 jum from trdspp a inner join 
					ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
					group by left(a.kd_rek6,8),b.nm_rek5,kd_sub_kegiatan
					union all
					select kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai, 0 jum from trdspp a inner join 
					ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
					group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
					) tox order by kd_rek";

         
		
			$hasil = $this->db->query($sql);
			$lcno = 0;
			$lcno_baris = 0;
			$lntotal = 0;
			foreach ($hasil->result() as $row)
		   {	
				$lcno_baris = $lcno_baris + 1;										
				if (strlen($row->kd_rek)>=11){
				$lcno = $lcno + 1;
				$lcno_x = $lcno;
				}
				else { 
					$lcno_x ='';
				}
               $lntotal = $lntotal + $row->jum;                                           
			   $cRet .="<tr>
							<td align='center'>&nbsp;$lcno_x</td>
							<td >&nbsp; $row->kd_sub_kegiatan.".$this->support->dotrek($row->kd_rek)." </td>
							<td >&nbsp; $row->nm_rek</td>
							<td align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
						</tr>";    
			}
             $cRet .="<tr>
                        <td align='right' colspan='3'>&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align='right'><b>".number_format($lntotal,"2",",",".")." </b>&nbsp;</td>
                    </tr>
        </table>";
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
		
		if($cetak==3){
			$this->master_pdf->_mpdf('',$cRet,10,10,10,'P',1,'');        
		}else if($cetak==2){
			$this->master_pdf->_mpdf_down('',$cRet,10,10,10,1,0,'','Cetak SPM '.$lcnospm.'');			
		}else{
			echo "$cRet";
		}			
				
    }

 	function cetakspm(){
        $cetak = $this->uri->segment(3);
		$lcnospm = str_replace('123456789','/',$this->uri->segment(4));
        $kd = $this->uri->segment(5);
        $jns = $this->uri->segment(6);
        $tanpa = $this->uri->segment(11);
        $baris = $this->uri->segment(12);
        $nmskpd=$this->rka_model->get_nama($kd,'nm_skpd','trhspp','kd_skpd');		
		$BK = str_replace('123456789',' ',$this->uri->segment(7));
        $PA = str_replace('123456789',' ',$this->uri->segment(9));
		
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;                   
                }
        $sqlttd1="SELECT * FROM ms_ttd WHERE id_ttd = '$PA'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nama;
                    $jabatan  = $rowttd->jabatan;
                    $pangkat  = $rowttd->pangkat;
                }
		$sqlttd2="SELECT * FROM ms_ttd WHERE id_ttd = '$BK'";
                 $sqlttd2=$this->db->query($sqlttd2);
                 foreach ($sqlttd2->result() as $rowttd2)
                {
                    $nip2=$rowttd2->nip;                    
                    $nama2= $rowttd2->nama;
                    $jabatan2  = $rowttd2->jabatan;
                    $pangkat2  = $rowttd2->pangkat;
                }
        
        
     $csql = "SELECT a.*,
				(SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp) AS nmrekan,
				(SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp) AS pimpinan,
                (SELECT tgl_spd FROM trhspd WHERE no_spd=a.no_spd) AS tgl_spd,
                'BELANJA' AS jns_beban
                FROM trhspm a WHERE a.no_spm = '$lcnospm'  AND a.kd_skpd='$kd'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
        
        $lcpemda = "$kab";
        $lcskpd = $nmskpd;
        $lcrekbank = $trh->no_rek;
        $lctahun = $this->session->userdata('pcThang');
        $lcnpwp = $trh->npwp;
        $lctglspd = $trh->tgl_spd;
        $lcnospd = $trh->no_spd;
        $lckeperluan = ltrim($trh->keperluan);
        $lcjnsbelanja = $trh->jns_beban;       

        $lcnospp = $trh->no_spp;
		$thn_ang	   = $this->session->userdata('pcThang');
		if($tanpa==1) {
			$ldtglspp ="_______________________$thn_ang";
		}else{
        $ldtglspp =$this->support->tanggal_format_indonesia($trh->tgl_spp);
		}
        $lctglspd =$this->support->tanggal_format_indonesia($trh->tgl_spd);
        $tglspm =$this->support->tanggal_format_indonesia($trh->tgl_spm);
        $jns_bbn=$trh->jenis_beban;
        $pimpinan=$trh->pimpinan;
        $nmrekan=$trh->nmrekan;
        $bank=$trh->bank;
		
		$sqlrek="SELECT bank,rekening, npwp FROM ms_skpd WHERE kd_skpd = '$kd' ";
                 $sqlrek=$this->db->query($sqlrek);
                 foreach ($sqlrek->result() as $rowrek)
                {
                    $bank_ben=$rowrek->bank;                    
                    $rekben=$rowrek->rekening;                    
                    $npwp_ben= $rowrek->npwp;
                    $npwp_ben2= $rowrek->npwp;
                }
		$bebanx=0;
		$sqlrek="SELECT jns_beban,no_rek,npwp FROM trhspp WHERE no_spp='$lcnospp' and kd_skpd = '$kd' ";
                 $sqlrek=$this->db->query($sqlrek);
                 foreach ($sqlrek->result() as $rowrek)
                {
                    $bebanx=$rowrek->jns_beban;
                    $rekbenspp=$rowrek->no_rek;
                    $npwpspp=$rowrek->npwp;                                       
                }
		
		$rek_ben = empty($rekben) || $rekben == 0 ? '' :$rekben;
		$rek_ben_spp = empty($rekbenspp) || $rekbenspp == 0 ? '' :$rekbenspp;
		$npwp_ben = empty($npwp_ben) || $npwp_ben == 0 ? '' :$npwp_ben;
		$thn_ang	   = $this->session->userdata('pcThang');
		if($tanpa==1) {
			$tglspm ="_______________________$thn_ang";
			}
		
		$nama_bank = empty($bank) || $bank == 0 ? 'Belum Pilih Bank' :$this->rka_model->get_nama($bank,'nama','ms_bank','kode');
		$nama_bank_ben = empty($bank_ben) || $bank_ben == 0 ? 'Belum Pilih Bank' :$this->rka_model->get_nama($bank_ben,'nama','ms_bank','kode');
		$jns_bebantagih='';
        $jns_spptagih='';
		
		//menampilkan tagih
		$sqltagih="SELECT a.no_bukti, a.tgl_bukti, a.ket, a.ket_bast, a.status, a.sts_tagih, a.kontrak, a.jns_trs, c.no_spm, b.no_spp, b.tgl_spp, b.jns_spp, b.jns_beban from trhtagih a left join trhspp b on a.no_bukti=b.no_tagih and a.kd_skpd=b.kd_skpd
		inner join trhspm c on b.kd_skpd=c.kd_skpd and b.no_spp=c.no_spp where b.no_spp='$lcnospp' and a.kd_skpd='$kd'";
		$sqltagih1=$this->db->query($sqltagih);
		foreach ($sqltagih1->result() as $rowtagih)
                {
                    $no_buktitagih=$rowtagih->no_bukti;                                       
                    $tgl_buktitagih=$rowtagih->tgl_bukti;                                       
                    $kettagih=$rowtagih->ket;                                       
                    $ket_basttitagih=$rowtagih->ket_bast;                                       
                    $statustagih=$rowtagih->status;                                       
                    $sts_tagihtagih=$rowtagih->sts_tagih;                                       
                    $kontraktagih=$rowtagih->kontrak;                                       
                    $jns_trstagih=$rowtagih->jns_trs;                                       
                    $no_spmtagih=$rowtagih->no_spm;                                       
                    $no_spptagih=$rowtagih->no_spp;                                       
                    $tgl_spptagih=$rowtagih->tgl_spp;                                       
                    $jns_spptagih=$rowtagih->jns_spp;                                       
                    $jns_bebantagih=$rowtagih->jns_beban;                                       
                }
				
		if($jns_spptagih==6 and $jns_bebantagih==3){
			
			$tampiltagih = "No Tagih :".$no_buktitagih;
            $tampilkontrak="No Kontrak :".$kontraktagih;            
			$tampilket = "Keterangan :".$kettagih;
			$tampilketbast = "BAST :".$ket_basttitagih;
		}else{
			$tampiltagih = " ";
            $tampilkontrak = " ";
			$tampilket = " ";
			$tampilketbast = " ";
		}
	
        $cRet = "";
        $cRet .= "<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='1' cellspacing='-1' cellpadding='-1'>
                     <tr>
                         <td colspan='2' align='center' style='font-size:14px;border: solid 1px white;'>
                            <b>$lcpemda</b>
                         </td>
                     </tr>
                     <tr>
                         <td colspan='2' align='center' style='font-size:15px;border: solid 1px white;'>
                            <b>SURAT PERINTAH MEMBAYAR (SPM)</b>
                         </td>
                     </tr>
                     <tr>
                         <td colspan='2' align='center' style='font-size:14px;border: solid 1px white;'>
                            <b>TAHUN ANGGARAN $lctahun</b>
                         </td>
                     </tr>
                     <tr>
                         <td colspan='2' align='right' style='font-size:12px;border-right: none;border-top: none;border-left: none;'>
                            Nomor SPM : $lcnospm
                         </td>
                     </tr>
                     <tr>
                         <td colspan='2' align='center' style='font-size:14px'>&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan='1' align='center' style='font-size:14px'><b>BENDAHARA UMUM KOTA PONTIANAK</b><br><br>Supaya menerbitkan SP2D kepada:</td>
                        <td colspan='1' align='center' style='font-size:14px'><b>Potongan-potongan :<b/><br></td>
                         
                         
                     </tr>
                     <tr>
                         <td width='50%' style='font-size:12px' valign='top'>
						 <table style='border-collapse:collapse;' width='600' align='left' border='0' cellspacing='0' cellpadding='1'>
							<tr>
								<td valign='top' style='font-size:12px'>
									SKPD
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									<b>$lcskpd</b>
								</td>
							</tr>";
							if(($jns==6) ){
								if($bebanx==1){
									$cRet .="
							<tr>
								<td valign='top' style='font-size:12px'>
									Bendahara Pengeluaran
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
								$nama2
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									Nomor Rekening Bank 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$nama_bank - ".$lcrekbank."
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									NPWP 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$npwp_ben2 
								</td>
							</tr>";	
								}else{
									$cRet .="
							<tr>
								<td valign='top' style='font-size:12px'>
									Pihak Ketiga
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
								$pimpinan $nmrekan
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									Nomor Rekening Bank 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$nama_bank - $lcrekbank 
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									NPWP 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$lcnpwp
								</td>
							</tr>";
								}
							
							}else{
							   
                                if(($jns==4) && ($bebanx==9)){
								$cRet .="
							<tr>
								<td valign='top' style='font-size:12px'>
									Pihak Ketiga
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
								$pimpinan $nmrekan
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									Nomor Rekening Bank 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$nama_bank - $lcrekbank 
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									NPWP 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$lcnpwp
								</td>
							</tr>";
								}else{
							$cRet .="
							<tr>
								<td valign='top' style='font-size:12px'>
									Bendahara Pengeluaran
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
								$nama2
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									Nomor Rekening Bank 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$nama_bank_ben - $rek_ben_spp
								</td>
							</tr>
							<tr>
								<td valign='top' style='font-size:12px'>
									NPWP 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$lcnpwp
								</td>
							</tr>";	
								}	
							}
							$cRet .="
							<tr>
								<td valign='top' style='font-size:12px'>
									Dasar Pembayaran/No dan Tanggal SPD 
								</td>
								<td valign='top' style='font-size:12px'>
									:
								</td>
								<td valign='top' style='font-size:12px'>
									$lcnospd , $lctglspd
								</td>
							</tr>
							
							<tr>
								<td valign='top' style='font-size:12px;border-bottom: solid 1px black;border-top: solid 1px black;'>
                                     Untuk keperluan 
								</td>
								<td valign='top' style='font-size:12px;border-bottom: solid 1px black;border-top: solid 1px black;'>
									:
								</td>
								<td valign='top' style='font-size:12px;border-bottom: solid 1px black;border-top: solid 1px black;'><pre style='font-family:Times New Roman'>$lckeperluan</pre>
								</td>
							</tr>
					 </table>
						 <table style='border-collapse:collapse;' width='600' align='left' border='0' cellspacing='0' cellpadding='0'>
                                 <tr>
                                    <td width='100%' style='font-size:12px'>
                                         Pembebanan pada kode rekening :
                                         <table style='border-collapse:collapse;' width='600' align='left' border='0' cellspacing='0' cellpadding='1'>
                                            ";
                                        if(($jns==1) || ($jns==2)){
                                        $sql = "SELECT SUM(nilai) nilai from trdspp where no_spp='$lcnospp' AND kd_skpd='$kd'";
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
                                        $lntotal = 0;
                                        foreach ($hasil->result() as $row)
                                        {
                                           $lcno = $lcno + 1;
                                           $lntotal = $lntotal + $row->nilai;
										   
                                           $cRet .="   
											  <tr>
                                                <td style='font-size:12px'>
                                                    $kd
                                                </td>
                                                <td style='font-size:12px'>
                                                    $lcskpd
                                                </td>
                                                <td style='font-size:12px'>
                                                    Rp
                                                </td>
                                                <td style='font-size:12px' align='right'>
                                                    ".number_format($row->nilai,"2",",",".")."
                                                </td>
                                            </tr>"; 
										}  
										  $cRet .="</table>";
											if($lcno<=$baris)
											   {
												 for ($i = $lcno; $i <= $baris; $i++) 
												  {
													$cRet .="<br>";     
												  }                                                   
											   } 
										   } else{
										
										$sql1 = "SELECT COUNT(*) as jumlah FROM
												(
												SELECT '1' as urut, SUBSTRING(a.kd_sub_kegiatan,1,7) as kode, b.nm_program as nama, sum(nilai) as nilai  FROM trdspp a  
												INNER JOIN trskpd b ON SUBSTRING(a.kd_sub_kegiatan,1,7)=b.kd_program and a.kd_skpd=b.kd_skpd
												where no_spp = '$lcnospp' and a.kd_skpd='$kd' GROUP BY  SUBSTRING(a.kd_sub_kegiatan,1,7), nm_program
												UNION ALL
												SELECT ' ' as urut, SUBSTRING(a.kd_sub_kegiatan,1,12) as kode, b.nm_kegiatan as nama, sum(nilai) as nilai  FROM trdspp a  
												INNER JOIN trskpd b ON SUBSTRING(a.kd_sub_kegiatan,1,12)=b.kd_kegiatan 
												where no_spp = '$lcnospp' and a.kd_skpd='$kd' GROUP BY  SUBSTRING(a.kd_sub_kegiatan,1,12), b.nm_kegiatan
												union all
												
												SELECT ' ' as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, nm_rek6 as nama, nilai from trdspp where no_spp = '$lcnospp' and kd_skpd = '$kd')z
												";
                                        $hasil1 = $this->db->query($sql1);
										$row1 = $hasil1->row();
										$jumlahbaris = $row1->jumlah;
										if($jumlahbaris<=10){
											$sql = "
												SELECT '1' as urut, SUBSTRING(a.kd_sub_kegiatan,1,7) as kode, b.nm_program as nama, sum(a.nilai) as nilai  FROM trdspp a  
												INNER JOIN trskpd b ON a.kd_sub_kegiatan =b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
												where no_spp = '$lcnospp' and a.kd_skpd='$kd' GROUP BY  SUBSTRING(a.kd_sub_kegiatan,1,7), nm_program
												UNION ALL

												SELECT ' ' as urut, SUBSTRING(a.kd_sub_kegiatan,1,12) as kode, b.nm_kegiatan as nama, sum(nilai) as nilai  FROM trdspp a  
												INNER JOIN ms_kegiatan b ON SUBSTRING(a.kd_sub_kegiatan,1,12)=b.kd_kegiatan 
												where no_spp = '$lcnospp' and a.kd_skpd='$kd' GROUP BY  SUBSTRING(a.kd_sub_kegiatan,1,12), b.nm_kegiatan
												union all

												SELECT ' ' as urut, kd_sub_kegiatan as kode, nm_sub_kegiatan as nama, sum(nilai) as nilai from trdspp 
												where no_spp = '$lcnospp' and kd_skpd = '$kd' GROUP BY kd_sub_kegiatan, nm_sub_kegiatan
												UNION ALL


												SELECT ' ' as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, nm_rek6 as nama, nilai from trdspp where no_spp = '$lcnospp' and kd_skpd = '$kd'
												ORDER BY kode";
										} else{
										$sql = "SELECT '1' as urut, SUBSTRING(a.kd_sub_kegiatan,1,7) as kode, b.nm_program as nama, sum(a.nilai) as nilai  FROM trdspp a  
												INNER JOIN trskpd b ON a.kd_sub_kegiatan =b.kd_sub_kegiatan  and a.kd_skpd=b.kd_skpd
												where no_spp = '$lcnospp' and a.kd_skpd='$kd' GROUP BY  SUBSTRING(a.kd_sub_kegiatan,1,7), nm_program
												UNION ALL

												


                                                SELECT '' as urut, SUBSTRING(a.kd_sub_kegiatan,1,12) as kode, b.nm_kegiatan as nama, sum(a.nilai) as nilai  FROM trdspp a  
                                                INNER JOIN trskpd b ON a.kd_sub_kegiatan =b.kd_sub_kegiatan  and a.kd_skpd=b.kd_skpd
                                                where no_spp = '$lcnospp' and a.kd_skpd='$kd' GROUP BY  SUBSTRING(a.kd_sub_kegiatan,1,12), b.nm_kegiatan
                                                UNION ALL
												
												SELECT '1' as urut, kd_sub_kegiatan as kode, nm_sub_kegiatan as nama, sum(nilai) as nilai from trdspp 
												where no_spp = '$lcnospp' and kd_skpd = '$kd' GROUP BY kd_sub_kegiatan, nm_sub_kegiatan
												UNION ALL
												SELECT '1' as urut, a.kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,4) as kode, b.nm_rek3 as nama, SUM(a.nilai) as nilai
												FROM trdspp a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3
												where no_spp = '$lcnospp' and kd_skpd = '$kd'
												GROUP BY kd_sub_kegiatan,  LEFT(a.kd_rek6,4), b.nm_rek3 
												UNION ALL
												SELECT '2' as urut, '' as kode, '- Rincian Terlampir ' as nama, 0 as nilai
												ORDER BY urut, kode";	
										}
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
                                        $lntotal = 0;
                                        foreach ($hasil->result() as $row)
                                        {
                                           $lcno = $lcno + 1;
                                           $lntotal = $lntotal + $row->nilai;
										$cRet .="     
                                           <tr>
                                                <td style='font-size:12px'>
                                                    $row->kode
                                                </td>
                                                <td style='font-size:12px'>
                                                    ".ucwords($row->nama)."
                                                </td>
                                                <td style='font-size:12px'>
                                                    Rp
                                                </td>
                                                <td style='font-size:12px' align='right'>
                                                    ".number_format($row->nilai,"2",",",".")."&nbsp;
                                                </td>
                                            </tr>"; 
											   
										   }
											$cRet .="</table>";

											if($lcno<=$baris)
                                                if($baris>=10){
                                                    $baris=$baris-5;
                                                }
											   {
												 for ($i = $lcno; $i <=$baris; $i++) 
												  {
													$cRet .="<br>";     
												  }                                                   
											   }
                                        }
                                     $sql = "select SUM(nilai) nilai from trdspp where no_spp='$lcnospp' AND kd_skpd='$kd'";
                                        $hasil = $this->db->query($sql);
                                        foreach ($hasil->result() as $row)
                                        {
                                           $lnterbilang = $row->nilai;
										}
                                    $cRet .="</td>
                                <tr>
                                    <td width='100%' align='right' style='font-size:12px;border-hidden: top 1px black;border-top: solid 1px black;'>
                                    Jumlah SPP yang diminta 
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Rp ".number_format($lnterbilang,"2",",",".")." &nbsp;                              
                                    </td>
                                </tr>
								<tr>
                                    <td width='100%' align='left' style='font-size:13px;border-bottom: solid 1px black;border-top: hidden;'>
                                   
                                    <b><i>".ucwords($this->tukd_model->terbilang($lnterbilang))."</i></b><br>
                                    Nomor dan Tanggal SPP :$lcnospp dan $ldtglspp                                   
                                    </td>
                                </tr>
                                 <tr>
                                    <td width='100%' style='font-size:10px;border-bottom: none;border-top: solid 1px black;'>
                                      $tampiltagih<br>$tampilkontrak<br>$tampilket<br>$tampilketbast
                                    </td>
                                </tr>
                            </table>
                         </td>
                         <td width='50%' style='font-size:12px' valign='top'> 
                            <table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='600' align='left' border='1' cellspacing='0' cellpadding='1'>
                                <tr><thead>
                                    <td width='10%' style='font-size:12px' valign='center' align='center' valign='top'><b>No.</b></td>
                                    <td width='50%' style='font-size:12px' valign='center' align='center'><b>Uraian (No. Rekening)</b></td>
                                    <td width='20%' style='font-size:12px' valign='center' align='center' valign='top'><b>Jumlah</b></td>
                                    <td width='20%' style='font-size:12px' valign='center' align='center' valign='top'><b>Keterangan</b></td>
                                    </thead>
                                </tr>";
                                 $sql = "SELECT * from trspmpot where no_spm='$lcnospm' AND kd_rek6 IN('','','2110501','2110701','2110801','2110901','4140612') AND kd_skpd='$kd'";
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
                                        $lntotalpot = 0;
                                        foreach ($hasil->result() as $row)
                                        {
                                           $lcno = $lcno + 1;
                                           $lntotalpot = $lntotalpot + $row->nilai;
                                            $cRet .="<tr>
                                                        <td width='10%' style='font-size:12px' align='center' valign='top'>$lcno</td>
                                                        <td align='left' style='font-size:12px'>".ucwords($row->nm_rek6)."</td>
                                                        <td align='right'>".number_format($row->nilai,"2",",",".")."</td>
                                                        <td></td>
                                                    </tr>";    
                                        }
                                        
                                       if($lcno<=4) 
                                       {
                                         for ($i = $lcno; $i <= 4; $i++) 
                                          {
                                            $cRet .= "<tr>
                                                        <td width='10%' style='font-size:12px' align='center' valign='top'>&nbsp;</td>
                                                        <td align='left' style='font-size:12px'></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";    
                                          }                                                   
                                       }
                                $cRet .="
                                <tr>
                                    <td width='10%' style='font-size:12px' align='center' valign='top'></td>
                                    <td align='left' style='font-size:12px'><b>Jumlah Potongan</b></td>
                                    <td align='right'><b>".number_format($lntotalpot,"2",",",".")."</b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width='10%' style='font-size:12px' align='center' valign='top' colspan='4'>
                                    Informasi :<i>(tidak mengurangi jumlah pembayaran SPM)</i>
                                    </td>
                                </tr>
                                <tr>
                                    <td width='10%' style='font-size:12px' valign='center' align='center' valign='top'><b>No.</b></td>
                                    <td align='center' valign='center' style='font-size:12px'><b>Uraian</b></td>
                                    <td align='center' valign='center' style='font-size:12px'><b>Jumlah</b></td>
                                    <td align='center' valign='center' style='font-size:12px'><b>Keterangan</b></td>
                                </tr>";
                                 $sql = "
										 SELECT 2 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek6 NOT IN ('','','2130301','2110501','2110701','2110801','2110901','4140612') AND kd_skpd='$kd'
										 ORDER BY urut";
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
                                       $lntotalpot_not = 0;
                                      // $lntotalpot = $lntotalpot;
                                        foreach ($hasil->result() as $row)
                                        {
                                           $lntotalpot_not = $lntotalpot_not + $row->nilai; 
											$kode_rek=$row->kd_rek6;
                                           $lcno = $lcno + 1;
										   if($kode_rek=='2130101'){
											   $nama_rek='PPh 21';
										   } else if ($kode_rek=='2130201'){
											   $nama_rek='PPh 22';
										   } else if($kode_rek=='2130301'){
											   $nama_rek='PPN';
										   } else if($kode_rek=='2130401'){
											   $nama_rek='PPh 23';
										   } else if($kode_rek=='2130501'){
											   $nama_rek='PPh Pasal 4';
										   } else{
												$nama_rek=$row->nm_rek6;
										   }
                                            $cRet .="
                                                    <tr>
                                                        <td width='10%' style='font-size:12px' align='center' valign='top'>$lcno</td>
                                                        <td align='left' style='font-size:12px'>".$nama_rek."</td>
                                                        <td align='right'>".number_format($row->nilai,"2",",",".")."</td>
                                                        <td></td>
                                                    </tr>";    
                                        }
                                       if($lcno<=4)
                                       {
                                         for ($i = $lcno; $i <= 4; $i++) 
                                          {
                                            $cRet .= "<tr>
                                                        <td width='10%' style='font-size:12px' align='center' valign='top'>&nbsp;</td>
                                                        <td align='left' style='font-size:12px'></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";    
                                          }                                                   
                                       }
                                
                                
                                $cRet .="
								<tr>
                                    <td width='10%' style='font-size:12px' align='center' valign='top'></td>
                                    <td align='left' style='font-size:12px'><b>Jumlah Potongan</b></td>
                                    <td align='right'><b>".number_format($lntotalpot_not,"2",",",".")."</b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width='10%' style='font-size:12px' align='center' valign='top'></td>
                                    <td align='left' style='font-size:12px'><b>Total</b></td>
                                    <td align='right'><b>".number_format($lntotalpot+$lntotalpot_not,"2",",",".")."</b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan='2' width='10%' style='font-size:12px' align='center' valign='top'><b>Jumlah SPM</b></td>
                                    <td align='right'><b>".number_format($lnterbilang-$lntotalpot-$lntotalpot_not,"2",",",".")."</b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width='10%' style='font-size:12px;border-bottom: none;' align='left' valign='top' colspan='4'>
                                    Terbilang: <i>(".ucwords($this->tukd_model->terbilang($lnterbilang-$lntotalpot-$lntotalpot_not,"2",",",".")).")</i>
                                    </td>
                                </tr>
                                <tr>
                                    <td width='10%' style='font-size:12px;border-bottom: none;border-top: none;' align='Center' valign='top' colspan='4'>
                                    <br><br>
                                    $daerah, $tglspm <br>
                                    <pre>$jabatan</pre><br><br><br><br><br>
                                    <b><u>$nama</u></b><br>$pangkat<br>
                                    NIP. $nip
                                    <br>
                                    <br>                                    
                                    </td>
                                </tr>
                            </table>
                         </td>
                     </tr>
                     <tr>
                         <td colspan='2' style='font-size:12px' align='center'>
                         <i>SPM ini sah apabila telah ditandatangani dan distempel oleh Kepala SKPD</i>
                         </td>
                     </tr>
                  </table>";
                     

    $data['prev']= $cRet;    
	switch($cetak) {
		case 0;
		 echo ("<title>Cetak SPM</title>");
		 echo $cRet;
        break;
        case 1;
			$this->master_pdf->_mpdf_word('',$cRet,10,10,10,1,1,''); 
        break;
        case 2;        
			$this->master_pdf->_mpdf_down('',$cRet,10,10,10,1,0,'','Cetak SPM '.$lcnospm.'');
        break;
        case 3;        
			$this->master_pdf->_mpdf('',$cRet,10,10,10,1,1,'');
        break;
		case 4;        
			header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= spm".$kd.".xls");
            $this->load->view('anggaran/rka/perkadaII', $data);			
			break;
        } 
	}

  function cetak_register_spm(){
        $tglctkx = $this->uri->segment(4);
        
        $bln2 = $this->uri->segment(7);
        
        $tglctk = $this->tukd_model->tanggal_format_indonesia($tglctkx);
        $kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $jns= '';
        $jns = $this->uri->segment(5);
        $bln= '';
        $bln = $this->uri->segment(6);
        $nmbln = $this->support->getBulan($bln);
        if ($jns == '2'){                               
            $judbln="Bulan $nmbln";            
        }else if ($jns == '1'){                               
            $judbln="";            
        }else if ($jns == '3'){                               
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

        $sqls="SELECT top 1 nama,pangkat,jabatan,nip FROM ms_ttd where kode='PPK' and kd_skpd = '$kd'";
                 $sqls =$this->db->query($sqls);
                 foreach ($sqls->result() as $row)
                {
                    $nmppk     = $row->nama;
                    $jabppk     = $row->jabatan;
                    $pangppk     = $row->pangkat;
                    $nipppk     = $row->nip;  
                }

        if ($nipppk <> ''){
            $nipx = $nipppk;
            $pangx = $pangppk;
            $jabx = $jabppk;
            $namax = $nmppk;
        }else{
            $nipx = '';
            $pangx = '';
            $jabx = '';
            $namax = '';
        }

        $cRet = '';
$cRet = "<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>";
$cRet .="<thead>
        <tr>
            <td align='center' style='font-size:14px;border: solid 1px white;border-bottom:solid 1px white;' colspan='20'><b> ".strtoupper($kab)."</b></td>            
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border-bottom:solid 1px white;' colspan='17'><b>REGISTER SPM-UP/SPM-GU/SPM-TU/SPM-LS<br>".strtoupper($judbln)."</b></td>
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border-bottom:solid 1px black;' colspan='17'><b>$a $nama</b><br>&nbsp;</td>
        </tr>
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='5%' rowspan='3'><b>No.<br>Urut</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='6%' rowspan='3'><b>Tanggal</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='34%' colspan='6'><b>Nomor SPP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='19%' rowspan='3'><b>Uraian</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='36%' colspan='6'><b>Jumlah SPP<br>(Rp)</b></td>
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
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='4%'><b>PPKD</b></td>
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
        $kriteria = '';
        $kriteria = $this->uri->segment(3);
        $where2 ="";
        if ($jns == '2'){                               
            $where2x="and MONTH(a.tgl_spm)='$bln'";  
            $where2="and MONTH(tgl_spp)='$bln'";            
        }else if ($jns == '1'){                               
            $where2=""; 
            $where2x="";            
        }elseif ($jns == '3'){                               
            $where2="and (tgl_spp>='$bln' and tgl_spp<='$bln2')"; 
            $where2x="and (a.tgl_spm>='$bln' and a.tgl_spm<='$bln2')";          
        }else{
            $where2="";
            $where2x="";
        }

        $where ="";
        $where3="";
        if ($kriteria <> ''){                               
            $where="where a.kd_skpd ='$kriteria' ";
            $where3="where kd_skpd ='$kriteria' ";            
        }       
              
        $sql = "SELECT a.tgl_spm,a.no_spm,a.nilai,CASE WHEN sp2d_batal='1' then 'SP2D Dibatalkan' else a.keperluan END AS keperluan,a.jns_spp FROM trhspm a
        LEFT JOIN TRHSP2D b ON a.no_spm=b.no_spm and a.kd_skpd = b.kd_skpd $where $where2x order by a.tgl_spm";
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spm)."</td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spm</td>
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spm)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spm</td>
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spm)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spm</td>
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spm)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spm</td>
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spm)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spm</td>
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
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spm)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spm</td>
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
                       case '7': //GU nihil
                            $cRet .=  "<tr>
                                <td align='center' width='3%' style='font-size:10px'>$lcno</td>
                                <td align='center' width='6%' style='font-size:10px'>".$this->support->tanggal_format_indonesia($row->tgl_spm)."</td>
                                <td align='center' width='6%' style='font-size:10px'></td>
                                <td align='center' width='6%' style='font-size:10px'>$row->no_spm</td>
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
                    }
                   
                }
        $sql = "SELECT sum(z.nil_up) as up,sum(z.nil_gu) as gu,sum(z.nil_tu) as tu,sum(z.nil_ls_gj) as gj,sum(z.nil_ppkd) as ppkd,sum(z.nil_ls_brg) as barang from (
select isnull(sum(nilai),0) as nil_up, 0 as nil_gu,0 as nil_tu,0 as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='1' $where2 and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,isnull(sum(nilai),0) as nil_gu,0 as nil_tu,0 as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp in ('2','7') $where2 and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu, isnull(sum(nilai),0) as nil_tu,0 as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='3' $where2 and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu,0 as nil_tu, isnull(sum(nilai),0) as nil_ls_gj,0 as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='4' $where2 and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu,0 as nil_tu,0 as nil_ls_gj,isnull(sum(nilai),0) as nil_ppkd, 0 as nil_ls_brg from trhspp where jns_spp ='5' $where2 and kd_skpd ='$kriteria'
UNION
select 0 as nil_up,0 as nil_gu,0 as nil_tu,0 as nil_ls_gj,0 as nil_ppkd,isnull(sum(nilai),0) as nil_ls_brg from trhspp where jns_spp ='6' $where2 and kd_skpd ='$kriteria')z";
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

            $cRet .="
               <table style='border-collapse:collapse;' width='100%' colspan='15' align='center' border='0' cellspacing='-1' cellpadding='-1'>
                    <tr>
                          <td style='font-size:12px' width='60%' align='center'>&nbsp;</td>
                          <td style='font-size:12px' width='40%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%' align='center' >&nbsp;</td>
                          <td style='font-size:12px' width='40%' align='center' >$daerah, $tglctk</td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%' align='center' >&nbsp;</td>
                          <td style='font-size:12px' width='40%' align='center' >$jabx</td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%' align='center' >&nbsp;</td>
                          <td style='font-size:12px' width='40%' align='center' >$pangx</td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%' align='center'>&nbsp;</td>
                          <td style='font-size:12px' width='40%' align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%' align='center'>&nbsp;</td>
                          <td style='font-size:12px' width='40%'  align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%'  align='center'>&nbsp;</td>
                          <td style='font-size:12px' width='40%'  align='center'>&nbsp;</td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%'  align='center' >&nbsp;</td>
                          <td style='font-size:12px' width='40%'  align='center' ><u>$namax</u></td>
                    </tr>
                    <tr>
                          <td style='font-size:12px' width='60%'  align='center' >&nbsp;</td>
                          <td style='font-size:12px' width='40%'  align='center' >$nipx</td>
                    </tr>
                    
        
        </table>";
        
        $data['prev']= $cRet;    


        $judul = "RegisterSPM";
        echo $cRet;
    } 
}