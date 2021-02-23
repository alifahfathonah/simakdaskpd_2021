<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_kas extends CI_Controller {


    function __construct(){  
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }    
    }


	function simpanan_bank()
    {
			$print = $this->uri->segment(3);
			$thn_ang = $this->session->userdata('pcThang');
			$kd_skpd  = $this->session->userdata('kdskpd');
			$nm_skpd =$this->session->userdata('nm_skpd');
            $bulan= $_REQUEST['tgl1'];
            $lcperiode = $this->tukd_model->getBulan($bulan);
			$tgl_ttd= $_REQUEST['tgl_ttd'];
			$spasi= $_REQUEST['spasi'];
			$ttd1 = str_replace('123456789',' ',$_REQUEST['ttd1']);
			$ttd2 = str_replace('123456789',' ',$_REQUEST['ttd2']);
            $inskpd = substr($kd_skpd,18,4);
            if($inskpd=="0000"){
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";			    
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";			
            }else{
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";						    
            }
			$hasil = $this->db->query($csql);
			$trh2 = $hasil->row();          
			$lcNmBP = $trh2->nama;
			$lcNipBP = $trh2->nip;
			$lcJabBP = $trh2->jabatan;
			$lcPangkatBP = $trh2->pangkat;
			//PA
            $hasil = $this->db->query($csqls);
			$trh2 = $hasil->row();          
			$lcNmPA = $trh2->nama;
			$lcNipPA = $trh2->nip; 			
			$lcJabPA = $trh2->jabatan; 			
			$lcPangkatPA = $trh2->pangkat; 			
	
			$hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
			$trsk = $hasil->row();          
			$nm_skpd = $trsk->nm_skpd;
			
            $skpdbp  = substr($kd_skpd,0,17).".0000";
            
			$prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$skpdbp'");
			$prvn = $prv->row();          
			$prov = $prvn->provinsi;
			$daerah = $prvn->daerah;
			
                        
		$cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";
        }else{
            $init_skpd = "kode='$kd_skpd'";   
        }
        
		 $asql="SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union ALL
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK'
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            ) a
            where month(tgl)<'$bulan' and $init_skpd";
	
		$hasil=$this->db->query($asql);
		$bank=$hasil->row();
		$keluarbank=$bank->keluar;
		$terimabank=$bank->terima;
		$saldobank=$terimabank-$keluarbank;
			            
         $cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='6' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU PEMBANTU SIMPANAN BANK<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>SKPD</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>PERIODE</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
            </tr>
			</table>";
			
           $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
		<thead>		   
            <tr>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' >Tanggal.</td>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>No. BKU</td>
                <td bgcolor='#CCCCCC' align='center' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Uraian</td>
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Penerimaan</td> 
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Pengeluaran</td>  
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo</td>            
            </tr> 
		</thead>
            <tr>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' ></td>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>
                <td align='right' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo Lalu</td>
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td> 
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>  
                <td align='right' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>".number_format($saldobank,"2",",",".")."</td>            
            </tr>";
            

             $sql = "SELECT * FROM (
             select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
             select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan UNION ALL
	         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
			 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK' 
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            
						
                    ) a
             where month(a.tgl)='$bulan' and $init_skpd ORDER BY a.tgl,Cast(bku  as int), jns";
                                                           
                
                    $hasil = $this->db->query($sql);       
                    $saldo=$saldobank;
					$total_terima=0;
					$total_keluar=0;
                    foreach ($hasil->result() as $row)
                    {
                       $bku   =$row->bku ;
                       $tgl     =$row->tgl;
                       $uraian  =$row->ket; 
                       $nilai   =$row->jumlah;
                       $jns     =$row->jns; 
                                         
                       if ($jns==1){ 
                       $saldo=$saldo+$nilai;
					   $total_terima=$total_terima+$nilai;
                        $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>";                     
                                                        
                       }else{
                            $saldo=$saldo-$nilai;
							$total_keluar=$total_keluar+$nilai;
                            $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>"; 
                                   
                       }           
                    }
          $cRet .="<tr>
                                  <td bgcolor='#CCCCCC' colspan='3' valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>JUMLAH</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_terima+$saldobank,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_keluar,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_terima-$total_keluar+$saldobank,"2",",",".")."</td>
                                  </tr>"; 
         $cRet .="<tr>
                    <td align='left' colspan='6' style='font-size:12px;border:solid 1px white'>&nbsp;</td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>                
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>                    
                </tr>				
                <tr>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>Mengetahui:</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>$daerah, ".$this->support->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
               <tr>                
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'> NIP. $lcNipPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>NIP. $lcNipBP</td>
                </tr>";        
        $cRet .='</table>';
        $data['prev']= $cRet;    
        if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Simpanan Bank </title>");
			 echo $cRet;
			 }
		 else{
			$this->master_pdf->_mpdf('',$cRet,10,10,10,'0',0,'');
			}	
    }

    function simpanan_bank_cms()
    {
			$print = $this->uri->segment(3);
			$thn_ang = $this->session->userdata('pcThang');
			$kd_skpd  = $this->session->userdata('kdskpd');
			$nm_skpd =$this->session->userdata('nm_skpd');
            $bulan= $_REQUEST['tgl1'];
            $lcperiode = $this->tukd_model->getBulan($bulan);
			$tgl_ttd= $_REQUEST['tgl_ttd'];
			$spasi= $_REQUEST['spasi'];
			$ttd1 = str_replace('123456789',' ',$_REQUEST['ttd1']);
			$ttd2 = str_replace('123456789',' ',$_REQUEST['ttd2']);
            $inskpd = substr($kd_skpd,18,4);
            if($inskpd=="0000"){
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";			    
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";			
            }else{
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";						    
            }
            $hasil = $this->db->query($csql);
			$trh2 = $hasil->row();          
			$lcNmBP = $trh2->nama;
			$lcNipBP = $trh2->nip;
			$lcJabBP = $trh2->jabatan;
			$lcPangkatBP = $trh2->pangkat;
			
            $hasil = $this->db->query($csqls);
			$trh2 = $hasil->row();          
			$lcNmPA = $trh2->nama;
			$lcNipPA = $trh2->nip; 			
			$lcJabPA = $trh2->jabatan; 			
			$lcPangkatPA = $trh2->pangkat; 			
	
			$hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
			$trsk = $hasil->row();          
			$nm_skpd = $trsk->nm_skpd;
			$bpskpd = substr($kd_skpd,0,17).".0000";
			$prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$bpskpd'");
			$prvn = $prv->row();          
			$prov = $prvn->provinsi;
			$daerah = $prvn->daerah;
			
                        
		$cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";
            $init_skpd2 = "a.kd_skpd='$kd_skpd'";
        }else{
            /*if(substr($kd_skpd,8,2)=='00'){
                $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
                $init_skpd2 = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            }else{*/
                $init_skpd = "kode='$kd_skpd'";
                $init_skpd2 = "a.kd_skpd='$kd_skpd'";
            //}            
        }
        
        $as1ll = "
        SELECT SUM(case when jns=1 then jumlah else 0 end) AS terima,
               SUM(case when jns=2 then jumlah else 0 end) AS keluar
             FROM (
             select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
             select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            			             
	         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan UNION ALL
	         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
             
             SELECT z.tgl,z.bku,d.ket_tujuan+', An. '+e.nm_rekening_tujuan+', <br/>'+z.ket as ket,e.nilai as jumlah,z.jns,z.kode from ( 
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 	WHERE a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
             )z
             left join trhtransout_cmsbank d on d.kd_skpd=z.kode and d.no_bukti=z.bku	
             left join trdtransout_transfercms e on e.kd_skpd=d.kd_skpd and e.no_voucher=d.no_voucher
	         
             union all        
             
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 				 
             WHERE a.pay='BANK' and a.jns_spp in ('1','3') and a.no_bukti not in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))

             union all
							
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and a.jns_spp not in ('1','2','3') and panjar not in ('3') 
             
				UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
			 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK' 
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    ) a
             where $init_skpd and month(tgl)<'$bulan'
        ";
        
        $asql_salpotongan="SELECT
        SUM(case when z.jns=1 then z.jumlah else 0 end) AS terima,
        SUM(case when z.jns=2 then z.jumlah else 0 end) AS keluar 
        from
        (
        SELECT sum(a.total) AS jumlah,'1' jns FROM trhtransout a 
        join trhsp2d b on a.no_sp2d=b.no_sp2d WHERE $init_skpd2 and month(a.tgl_bukti)<'$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))        
        UNION ALL
        SELECT sum(c.nilai) AS jumlah,'2' jns FROM trhtransout a 
        left join trhtransout_cmsbank b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 	
        left join trdtransout_transfercms c on b.no_voucher=c.no_voucher and c.kd_skpd=b.kd_skpd 	
        WHERE $init_skpd2 and month(a.tgl_bukti)<'$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
        )z";        
        $hasil_pot=$this->db->query($asql_salpotongan);
		$pott=$hasil_pot->row();  
        $salterima_pot=$pott->terima;
        $salsetor_pot=$pott->keluar;      
        $salsisa_pott=$salterima_pot-$salsetor_pot;
        
        //                	
  		$hasil=$this->db->query($as1ll);
		$bank=$hasil->row();
		$keluarbank=$bank->keluar;
		$terimabank=$bank->terima;
		$saldobank=($terimabank-$keluarbank)-$salsisa_pott;        
        
        //$saldobank=0;
        			            
         $cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='6' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU PEMBANTU SIMPANAN BANK<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>SKPD</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>PERIODE</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
            </tr>
			</table>";
			
           $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
		<thead>		   
            <tr>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' >Tanggal.</td>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>No. BKU</td>
                <td bgcolor='#CCCCCC' align='center' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Uraian</td>
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Penerimaan</td> 
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Pengeluaran</td>  
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo</td>            
            </tr> 
		</thead>
            <tr>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' ></td>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>
                <td align='right' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo Lalu</td>
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td> 
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>  
                <td align='right' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>".number_format($saldobank,"2",",",".")."</td>            
            </tr>";
            
            
/*              $sql = "SELECT * FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,no_sp2d AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhsp2d where jns_spp in('1','2','3','4') 
				union
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhtransout WHERE jns_spp='4' 
				UNION
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan ) a 
				where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,bku, jns";
 */
             $sql = "SELECT * FROM (
             select tgl_panjar as tgl,no_panjar as bku,'<b>Pembayaran Panjar</b>: '+keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
             select tgl_kas as tgl,no_kas as bku,'<b>Pembayaran Panjar</b>: '+keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            			             
	         SELECT tgl_kas AS tgl,no_kas AS bku,'<b>Setor Bank</b>: '+keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan UNION ALL
	         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,'<b>Pelimpahan Dana</b>: '+keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
             
             SELECT z.tgl,z.bku,'<b>CMS</b>: '+d.ket_tujuan+', An. '+e.nm_rekening_tujuan+', <br/>'+z.ket as ket,e.nilai as jumlah,z.jns,z.kode from ( 
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 	WHERE a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
             )z
             left join trhtransout_cmsbank d on d.kd_skpd=z.kode and d.no_bukti=z.bku	
             left join trdtransout_transfercms e on e.kd_skpd=d.kd_skpd and e.no_voucher=d.no_voucher
	         
             union all        
             
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,'<b>Pemindahbukuan</b>: '+a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 				 
             WHERE a.pay='BANK' and a.jns_spp in ('1','3') and a.no_bukti not in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))

             union all
							
             SELECT tgl_bukti AS tgl,no_bukti AS bku,'<b>Pemindahbukuan SP2D</b>: '+ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and a.jns_spp not in ('1','2','3') and panjar not in ('3')
             
				UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,'<b>Tarik Tunai</b>: '+keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
			 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK'  
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    ) a
             where $init_skpd and month(tgl)='$bulan' ORDER BY a.tgl,Cast(bku  as int), jns";
                                                                
                    $hasil = $this->db->query($sql);       
                    $saldo=$saldobank;
					$total_terima=0;
					$total_keluar=0;
                    foreach ($hasil->result() as $row)
                    {
                       $bku   =$row->bku ;
                       $tgl     =$row->tgl;
                       $uraian  =$row->ket; 
                       $nilai   =$row->jumlah;
                       $jns     =$row->jns; 
                                         
                       if ($jns==1){ 
                       $saldo=$saldo+$nilai;
					   $total_terima=$total_terima+$nilai;
                        $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>";                     
                                                        
                       }else{
                            $saldo=$saldo-$nilai;
							$total_keluar=$total_keluar+$nilai;
                            $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>"; 
                                   
                       }           
                    }
        
        $asql_potongan="SELECT
        SUM(case when z.jns=1 then z.jumlah else 0 end) AS terima,
        SUM(case when z.jns=2 then z.jumlah else 0 end) AS keluar 
        from
        (
        SELECT sum(a.total) AS jumlah,'1' jns FROM trhtransout a 
        join trhsp2d b on a.no_sp2d=b.no_sp2d WHERE $init_skpd2 and month(a.tgl_bukti)='$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))        
        UNION ALL
        SELECT sum(c.nilai) AS jumlah,'2' jns FROM trhtransout a 
        left join trhtransout_cmsbank b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 	
        left join trdtransout_transfercms c on b.no_voucher=c.no_voucher and c.kd_skpd=b.kd_skpd 	
        WHERE $init_skpd2 and month(a.tgl_bukti)='$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
        )z";        
        $hasil_pot=$this->db->query($asql_potongan);
		$pott=$hasil_pot->row();  
        $terima_pot=$pott->terima;
        $setor_pot=$pott->keluar;      
        $sisa_pott=$terima_pot-$setor_pot;
        
        /*if($sisa_pott<0){
        $sisa_pott = $sisa_pott*-1;
        }*/
            
        $cRet .="<tr>
                                  <td colspan='3' valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>JUMLAH POTONGAN TRANSAKSI</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($sisa_pott,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format(($total_terima-$total_keluar+$saldobank)-$sisa_pott,"2",",",".")."</td>
                                  </tr>";                                  
        
        $cRet .="<tr>
                                  <td bgcolor='#CCCCCC' colspan='3' valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>JUMLAH SIMPANAN BANK</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_terima+$saldobank,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_keluar+$sisa_pott,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format(($total_terima-$total_keluar+$saldobank)-$sisa_pott,"2",",",".")."</td>
                                  </tr>"; 
                
         $cRet .="<tr>
                    <td align='left' colspan='6' style='font-size:12px;border:solid 1px white'>&nbsp;</td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>                
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>                    
                </tr>				
                <tr>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>Mengetahui:</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>$daerah, ".$this->support->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
               <tr>                
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'> NIP. $lcNipPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>NIP. $lcNipBP</td>
                </tr>";        
        $cRet .='</table>';
        $data['prev']= $cRet;    
        if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Simpanan Bank </title>");
			 echo $cRet;
			 }
		 else{
			$this->master_pdf->_mpdf('',$cRet,10,10,10,'0',0,'');
			}	
    }

    function simpanan_bank_global()
    {
			$print = $this->uri->segment(3);
			$thn_ang = $this->session->userdata('pcThang');
			$kd_skpd  = $this->session->userdata('kdskpd');
			$nm_skpd =$this->session->userdata('nm_skpd');
            $bulan= $_REQUEST['tgl1'];
            $lcperiode = $this->tukd_model->getBulan($bulan);
			$tgl_ttd= $_REQUEST['tgl_ttd'];
			$spasi= $_REQUEST['spasi'];
			$ttd1 = str_replace('123456789',' ',$_REQUEST['ttd1']);
			$ttd2 = str_replace('123456789',' ',$_REQUEST['ttd2']);
            $inskpd = substr($kd_skpd,18,4);
            if($inskpd=="0000"){
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";			    
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";			
            }else{
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";						    
            }
			$hasil = $this->db->query($csql);
			$trh2 = $hasil->row();          
			$lcNmBP = $trh2->nama;
			$lcNipBP = $trh2->nip;
			$lcJabBP = $trh2->jabatan;
			$lcPangkatBP = $trh2->pangkat;
			//PA
            $hasil = $this->db->query($csqls);
			$trh2 = $hasil->row();          
			$lcNmPA = $trh2->nama;
			$lcNipPA = $trh2->nip; 			
			$lcJabPA = $trh2->jabatan; 			
			$lcPangkatPA = $trh2->pangkat; 			
	
			$hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
			$trsk = $hasil->row();          
			$nm_skpd = $trsk->nm_skpd;
			
            $skpdbp  = substr($kd_skpd,0,17).".0000";
            
			$prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$skpdbp'");
			$prvn = $prv->row();          
			$prov = $prvn->provinsi;
			$daerah = $prvn->daerah;
			
                        
		$cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
            }else{
                $init_skpd = "kode='$kd_skpd'";
            }            
        }
        
		 $asql="SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
			select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union ALL
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK'
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            ) a
            where month(tgl)<'$bulan' and $init_skpd";
	
		$hasil=$this->db->query($asql);
		$bank=$hasil->row();
		$keluarbank=$bank->keluar;
		$terimabank=$bank->terima;
		$saldobank=$terimabank-$keluarbank;
			            
         $cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='6' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU PEMBANTU SIMPANAN BANK GLOBAL<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>SKPD</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>PERIODE</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
            </tr>
			</table>";
			
           $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
		<thead>		   
            <tr>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' >Tanggal.</td>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>No. BKU</td>
                <td bgcolor='#CCCCCC' align='center' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Uraian</td>
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Penerimaan</td> 
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Pengeluaran</td>  
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo</td>            
            </tr> 
		</thead>
            <tr>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' ></td>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>
                <td align='right' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo Lalu</td>
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td> 
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>  
                <td align='right' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>".number_format($saldobank,"2",",",".")."</td>            
            </tr>";
            
            
/*              $sql = "SELECT * FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,no_sp2d AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhsp2d where jns_spp in('1','2','3','4') 
				union
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhtransout WHERE jns_spp='4' 
				UNION
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan ) a 
				where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,bku, jns";
 */
             $sql = "SELECT * FROM (
             select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL			
             select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            
	         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan UNION ALL
	         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
			 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK' 
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    ) a
             where month(a.tgl)='$bulan' and $init_skpd ORDER BY a.tgl,Cast(bku  as int), jns";
                                                                
                    $hasil = $this->db->query($sql);       
                    $saldo=$saldobank;
					$total_terima=0;
					$total_keluar=0;
                    foreach ($hasil->result() as $row)
                    {
                       $bku   =$row->bku ;
                       $tgl     =$row->tgl;
                       $uraian  =$row->ket; 
                       $nilai   =$row->jumlah;
                       $jns     =$row->jns; 
                                         
                       if ($jns==1){ 
                       $saldo=$saldo+$nilai;
					   $total_terima=$total_terima+$nilai;
                        $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>";                     
                                                        
                       }else{
                            $saldo=$saldo-$nilai;
							$total_keluar=$total_keluar+$nilai;
                            $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>"; 
                                   
                       }           
                    }
          $cRet .="<tr>
                                  <td bgcolor='#CCCCCC' colspan='3' valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>JUMLAH</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_terima+$saldobank,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_keluar,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_terima-$total_keluar+$saldobank,"2",",",".")."</td>
                                  </tr>"; 
         $cRet .="<tr>
                    <td align='left' colspan='6' style='font-size:12px;border:solid 1px white'>&nbsp;</td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>                
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>                    
                </tr>				
                <tr>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>Mengetahui:</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>$daerah, ".$this->support->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
               <tr>                
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'> NIP. $lcNipPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>NIP. $lcNipBP</td>
                </tr>";        
        $cRet .='</table>';
        $data['prev']= $cRet;    
        if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Simpanan Bank </title>");
			 echo $cRet;
			 }
		 else{
			$this->master_pdf->_mpdf('',$cRet,10,10,10,'0',0,'');
			}	
    }


    function simpanan_bank_cms_global()
    {
			$print = $this->uri->segment(3);
			$thn_ang = $this->session->userdata('pcThang');
			$kd_skpd  = $this->session->userdata('kdskpd');
			$nm_skpd =$this->session->userdata('nm_skpd');
            $bulan= $_REQUEST['tgl1'];
            $lcperiode = $this->tukd_model->getBulan($bulan);
			$tgl_ttd= $_REQUEST['tgl_ttd'];
			$spasi= $_REQUEST['spasi'];
			$ttd1 = str_replace('123456789',' ',$_REQUEST['ttd1']);
			$ttd2 = str_replace('123456789',' ',$_REQUEST['ttd2']);
            $inskpd = substr($kd_skpd,18,4);
            if($inskpd=="0000"){
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";			    
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";			
            }else{
            $csql="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";
            $csqls="SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";						    
            }
            $hasil = $this->db->query($csql);
			$trh2 = $hasil->row();          
			$lcNmBP = $trh2->nama;
			$lcNipBP = $trh2->nip;
			$lcJabBP = $trh2->jabatan;
			$lcPangkatBP = $trh2->pangkat;
			
            $hasil = $this->db->query($csqls);
			$trh2 = $hasil->row();          
			$lcNmPA = $trh2->nama;
			$lcNipPA = $trh2->nip; 			
			$lcJabPA = $trh2->jabatan; 			
			$lcPangkatPA = $trh2->pangkat; 			
	
			$hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
			$trsk = $hasil->row();          
			$nm_skpd = $trsk->nm_skpd;
			$bpskpd = substr($kd_skpd,0,17).".0000";
			$prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$bpskpd'");
			$prvn = $prv->row();          
			$prov = $prvn->provinsi;
			$daerah = $prvn->daerah;
			
                        
		$cek_skpd = $this->db->query("SELECT count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";
            $init_skpd2 = "a.kd_skpd='$kd_skpd'";
        }else{
            
            if(substr($kd_skpd,8,4)=='0000'){
                $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
                $init_skpd2 = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            }else{
                $init_skpd = "kode='$kd_skpd'";
                $init_skpd2 = "a.kd_skpd='$kd_skpd'";
            }            
        }
        
        $as1ll = "
        SELECT SUM(case when jns=1 then jumlah else 0 end) AS terima,
               SUM(case when jns=2 then jumlah else 0 end) AS keluar
             FROM (
             select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode,'' username from tr_panjar UNION ALL
             select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode,'' username from tr_jpanjar where jns='2' UNION ALL
            			             
	         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode,'' username FROM tr_setorsimpanan UNION ALL
	         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode,'' username FROM trhINlain WHERE pay='BANK' UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode,'' username FROM tr_setorpelimpahan_bank union ALL
             
             SELECT z.tgl,z.bku,d.ket_tujuan+', An. '+e.nm_rekening_tujuan+', <br/>'+z.ket as ket,e.nilai as jumlah,z.jns,z.kode,z.username from ( 
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode,a.username FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 	WHERE a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
             )z
             left join trhtransout_cmsbank d on d.kd_skpd=z.kode and d.no_bukti=z.bku and z.username=d.username	
             left join trdtransout_transfercms e on e.kd_skpd=d.kd_skpd and e.no_voucher=d.no_voucher and z.username=e.username
	         
             union all        
             
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode,'' username FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 				 
             WHERE a.pay='BANK' and a.jns_spp in ('1') and a.no_bukti not in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))

             union all
							
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode,'' username FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and a.jns_spp not in ('1','2','3') and panjar not in ('3')
             
				UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode,'' username FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
			 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode,'' username FROM trhoutlain WHERE pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode,'' username 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK' 
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    ) a
             where $init_skpd and month(tgl)<'$bulan'
        ";
        
        $asql_salpotongan="SELECT
        SUM(case when z.jns=1 then z.jumlah else 0 end) AS terima,
        SUM(case when z.jns=2 then z.jumlah else 0 end) AS keluar 
        from
        (
        SELECT sum(a.total) AS jumlah,'1' jns FROM trhtransout a 
        join trhsp2d b on a.no_sp2d=b.no_sp2d WHERE $init_skpd2 and month(a.tgl_bukti)<'$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))        
        UNION ALL
        SELECT sum(c.nilai) AS jumlah,'2' jns FROM trhtransout a 
        left join trhtransout_cmsbank b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd and a.username=b.username 	
        left join trdtransout_transfercms c on b.no_voucher=c.no_voucher and c.kd_skpd=b.kd_skpd and a.username=c.username
        WHERE $init_skpd2 and month(a.tgl_bukti)<'$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
        )z";        
        $hasil_pot=$this->db->query($asql_salpotongan);
		$pott=$hasil_pot->row();  
        $salterima_pot=$pott->terima;
        $salsetor_pot=$pott->keluar;      
        $salsisa_pott=$salterima_pot-$salsetor_pot;
        
        //                	
  		$hasil=$this->db->query($as1ll);
		$bank=$hasil->row();
		$keluarbank=$bank->keluar;
		$terimabank=$bank->terima;
		$saldobank=($terimabank-$keluarbank)-$salsisa_pott;        
        
        //$saldobank=0;
        			            
         $cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='6' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU PEMBANTU SIMPANAN BANK GLOBAL<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>SKPD</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>PERIODE</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
            </tr>
			</table>";
			
           $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
		<thead>		   
            <tr>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' >Tanggal.</td>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>No. BKU</td>
                <td bgcolor='#CCCCCC' align='center' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Uraian</td>
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Penerimaan</td> 
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Pengeluaran</td>  
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo</td>            
            </tr> 
		</thead>
            <tr>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' ></td>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>
                <td align='right' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo Lalu</td>
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td> 
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>  
                <td align='right' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>".number_format($saldobank,"2",",",".")."</td>            
            </tr>";
            
            
/*              $sql = "SELECT * FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,no_sp2d AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhsp2d where jns_spp in('1','2','3','4') 
				union
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhtransout WHERE jns_spp='4' 
				UNION
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan ) a 
				where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,bku, jns";
 */
             $sql = "SELECT * FROM (
             select tgl_panjar as tgl,no_panjar as bku,'<b>Pembayaran Panjar</b>: '+keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode,'' username from tr_panjar UNION ALL
             select tgl_kas as tgl,no_kas as bku,'<b>Pembayaran Panjar</b>: '+keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode,'' username from tr_jpanjar where jns='2' UNION ALL
            			             
	         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode,'' username FROM tr_setorsimpanan UNION ALL
	         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode,'' username FROM trhINlain WHERE pay='BANK' UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode,'' username FROM tr_setorpelimpahan_bank union ALL
             
             SELECT z.tgl,z.bku,'<b>CMS</b>: '+d.ket_tujuan+', An. '+e.nm_rekening_tujuan+', <br/>'+z.ket as ket,e.nilai as jumlah,z.jns,z.kode,z.username from ( 
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode,a.username FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 	WHERE a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
             )z
             left join trhtransout_cmsbank d on d.kd_skpd=z.kode and d.no_bukti=z.bku and d.username=z.username
             left join trdtransout_transfercms e on e.kd_skpd=d.kd_skpd and e.no_voucher=d.no_voucher and e.username=z.username
	         
             union all        
             
             SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,'<b>Pemindahbukuan</b>: '+a.ket AS ket,a.total AS jumlah,'2' AS jns,a.kd_skpd AS kode,'' username FROM trhtransout a 
             join trhsp2d b on a.no_sp2d=b.no_sp2d 				 
             WHERE a.pay='BANK' and a.jns_spp in ('1') and a.no_bukti not in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))

             union all
							
             SELECT tgl_bukti AS tgl,no_bukti AS bku,'<b>Pemindahbukuan SP2D</b>: '+ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode,'' username FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and a.jns_spp not in ('1','2','3') and panjar not in ('3')
             
				UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode,'' username FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
			 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode,'' username FROM trhoutlain WHERE pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode,'' username 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK'  
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    ) a
             where $init_skpd and month(tgl)='$bulan' ORDER BY a.tgl,Cast(bku  as int), jns";
                                                                
                    $hasil = $this->db->query($sql);       
                    $saldo=$saldobank;
					$total_terima=0;
					$total_keluar=0;
                    foreach ($hasil->result() as $row)
                    {
                       $bku   =$row->bku ;
                       $tgl     =$row->tgl;
                       $uraian  =$row->ket; 
                       $nilai   =$row->jumlah;
                       $jns     =$row->jns; 
                                         
                       if ($jns==1){ 
                       $saldo=$saldo+$nilai;
					   $total_terima=$total_terima+$nilai;
                        $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>";                     
                                                        
                       }else{
                            $saldo=$saldo-$nilai;
							$total_keluar=$total_keluar+$nilai;
                            $cRet .="<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($nilai,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($saldo,"2",",",".")."</td>
                                  </tr>"; 
                                   
                       }           
                    }
        
        /*$asql_potongan="SELECT sum(a.nilai) as potongan
from trdstrpot a inner join trhstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
where $init_skpd2 and month(tgl_bukti)='$bulan' and b.jns_spp in ('1','2','3')
    */

$asql_potongan="SELECT
        SUM(case when z.jns=1 then z.jumlah else 0 end) AS terima,
        SUM(case when z.jns=2 then z.jumlah else 0 end) AS keluar 
        from
        (
        SELECT sum(a.total) AS jumlah,'1' jns FROM trhtransout a 
        join trhsp2d b on a.no_sp2d=b.no_sp2d WHERE $init_skpd2 and month(a.tgl_bukti)='$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))        
        UNION ALL
        SELECT sum(c.nilai) AS jumlah,'2' jns FROM trhtransout a 
        left join trhtransout_cmsbank b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd    
        left join trdtransout_transfercms c on b.no_voucher=c.no_voucher and c.kd_skpd=b.kd_skpd    
        WHERE $init_skpd2 and month(a.tgl_bukti)='$bulan' and a.pay='BANK' and a.jns_spp in ('1','2','3') and a.no_bukti in (select no_bukti from trhtransout_cmsbank WHERE kd_skpd=a.kd_skpd and panjar not in ('3'))
        )z"; 
//";        

        $hasil_pot=$this->db->query($asql_potongan);
        $pott=$hasil_pot->row();  
        $terima_pot=$pott->terima;
        $setor_pot=$pott->keluar;      
        $sisa_pott=$terima_pot-$setor_pot;
            
        $cRet .="<tr>
                                  <td colspan='3' valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>JUMLAH POTONGAN TRANSAKSI</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($sisa_pott,"2",",",".")."</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format(($total_terima-$total_keluar+$saldobank)-$sisa_pott,"2",",",".")."</td>
                                  </tr>";                                  
        
        $cRet .="<tr>
                                  <td bgcolor='#CCCCCC' colspan='3' valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>JUMLAH SIMPANAN BANK</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_terima+$saldobank,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format($total_keluar+$sisa_pott,"2",",",".")."</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>".number_format(($total_terima-$total_keluar+$saldobank)-$sisa_pott,"2",",",".")."</td>
                                  </tr>"; 
                
         $cRet .="<tr>
                    <td align='left' colspan='6' style='font-size:12px;border:solid 1px white'>&nbsp;</td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>                
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>                    
                </tr>				
                <tr>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>Mengetahui:</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>$daerah, ".$this->support->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
               <tr>                
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'> NIP. $lcNipPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>NIP. $lcNipBP</td>
                </tr>";        
        $cRet .='</table>';
        $data['prev']= $cRet;    
        if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Simpanan Bank </title>");
			 echo $cRet;
			 }
		 else{
			$this->master_pdf->_mpdf('',$cRet,10,10,10,'0',0,'');
			}	
    } 


    function cetak_kas_tunai_global(){
            $print = $this->uri->segment(3);
            $thn_ang = $this->session->userdata('pcThang');
            $kd_skpd  = $this->session->userdata('kdskpd');
            $bulan= $_REQUEST['tgl1'];
            $spasi= $_REQUEST['spasi'];
            $jns_bpp= $_REQUEST['jns_bp'];
            $kd_skpddd = substr($kd_skpd,0,17);
            $kd_skpddd = $kd_skpddd . ".0000";
            $adinas=$this->db->query("select provinsi,daerah from sclient WHERE kd_skpd='$kd_skpddd'");
            $dinas=$adinas->row();
            $prov=$dinas->provinsi;
            $daerah=$dinas->daerah;
            $hasil = $this->db->query("SELECT nm_skpd from ms_skpd where kd_skpd = '$kd_skpd'");
            $trsk = $hasil->row();          
            $nm_skpd = $trsk->nm_skpd;
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
       
            $tgl_ttd= $_REQUEST['tgl_ttd'];
         
            $ttd1 = str_replace('123456789',' ',$_REQUEST['ttd1']);
            $ttd2 = str_replace('123456789',' ',$_REQUEST['ttd2']);         
            

                $csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE id_ttd='$ttd1'";    
            
            $hasil = $this->db->query($csql);
            $trh1 = $hasil->row();          
            $lcNmPA = $trh1->nama;
            $lcNipPA = $trh1->nip;          
            $lcJabPA = $trh1->jabatan;          
            $lcPangkatPA = $trh1->pangkat;          
            if($jns_bpp=="bpp"){
            $csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE  id_ttd='$ttd2'";
            }else{
            $csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";
            }
            $hasil = $this->db->query($csql);
            $trh2 = $hasil->row();          
            $lcNmBP = $trh2->nama;
            $lcNipBP = $trh2->nip;
            $lcJabBP = $trh2->jabatan;
            $lcPangkatBP = $trh2->pangkat;
       
        $esteh="SELECT 
                SUM(case when jns=1 then jumlah else 0 end ) AS terima,
                SUM(case when jns=2 then jumlah else 0 end) AS keluar
                FROM (
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN ('4','2') and pot_khusus in ('0','2') and bank='TN'
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd             
                UNION ALL
                SELECT  a.tgl_bukti AS tgl, a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
                                AND MONTH(a.tgl_bukti)<'$bulan' and left(a.kd_skpd,17)=left('$kd_skpd',17) 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where left(kd_skpd,17)=left('$kd_skpd',17) GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17) 
                                AND MONTH(tgl_bukti)<'$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17))
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                SELECT  tgl_bukti AS tgl,   no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') and no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where left(kd_skpd,17)=left('$kd_skpd',17) GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17) 
                                AND MONTH(tgl_bukti)<'$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17)
                
                UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' UNION ALL
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'
                ) a 
                where month(a.tgl)<'$bulan' and left(kode,17)=left('$kd_skpd',17)";
                
        $hasil = $this->db->query($esteh);
                
        $okok = $hasil->row();  
        $tox_awal="SELECT isnull(sld_awal,0) AS jumlah FROM ms_skpd where kd_skpd='$kd_skpd'";
                     $hasil = $this->db->query($tox_awal);                   
                     $tox = $hasil->row('jumlah');
                     $terima = $okok->terima;
                     $keluar = $okok->keluar;                    
                     $saldotunai=($terima+$tox)-$keluar;
         $cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='6' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU KAS TUNAI<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;'></td>
            </tr>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;'></td>
            </tr>            
            <tr>
                <td align='left' colspan='0' style='font-size:12px;'>SKPD</td>
                <td align='left' colspan='0' style='font-size:12px;'>: $kd_skpd &nbsp; $nm_skpd</td> 
            </tr>
            <tr>
                <td align='left' colspan='0' style='font-size:12px;'>PERIODE</td>
                <td align='left' colspan='4' style='font-size:12px;'>: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;'>&nbsp;</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;'>&nbsp;</td>
            </tr>
            </table>";

             $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
            <thead>
            <tr>
                <td bgcolor='#CCCCCC' align='center' width='5%' style='font-size:12px;' >Tanggal.</td>
                <td bgcolor='#CCCCCC' align='center' width='5%' style='font-size:12px;'>SKPD</td>
                <td bgcolor='#CCCCCC' align='center' width='5%' style='font-size:12px;'>No. BKU</td>
                <td bgcolor='#CCCCCC' align='center' width='30%' style='font-size:12px;'>Uraian</td>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;'>Penerimaan</td> 
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;'>Pengeluaran</td>  
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;'>Saldo</td>            
            </tr> 
            </thead>
            <tr>
            
                <td align='center' width='5%' style='font-size:12px;' ></td>
                <td align='center' width='5%' style='font-size:12px;'></td>
                <td align='center' width='5%' style='font-size:12px;'></td>
                <td align='right' width='35%' style='font-size:12px;'>Saldo Lalu</td>
                <td align='center' width='10%' style='font-size:12px;'></td> 
                <td align='center' width='10%' style='font-size:12px;'></td>  
                <td align='right' width='10%' style='font-size:12px;'>".number_format($saldotunai,"2",",",".")."</td>            
            </tr> ";
            
                /*AWAL SEBELUM UPDATE POTONGAN      
                $sql="SELECT * FROM (
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                        select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as masuk, 0 as keluar,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALl
                        select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, 0 as masuk,nilai as keluar,kd_skpd as kode from tr_panjar UNION ALL
                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
                                from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                                where jns_trans<>4 and pot_khusus =0  
                                GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
                        UNION ALL
                        SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk,SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar <> 1
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd 
                        UNION ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI')a
                        where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,CAST(left(bku,4) AS int)";
                */          
                $sql="SELECT * FROM (
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
                                from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                                where jns_trans NOT IN ('4','2') and pot_khusus in ('0','2') and bank='TN'  
                                GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
                        UNION ALL
                        SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
                                AND MONTH(a.tgl_bukti)='$bulan' and left(a.kd_skpd,17)=left('$kd_skpd',17) 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where left(kd_skpd,17)=left('$kd_skpd',17) GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
                                (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17) 
                                AND MONTH(tgl_bukti)='$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17))
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                        select tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 AS masuk, ISNULL(total,0)  AS keluar,kd_skpd AS kode 
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in 
                                (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where left(kd_skpd,17)=left('$kd_skpd',17) GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
                                (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17) 
                                AND MONTH(tgl_bukti)='$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and left(kd_skpd,17)=left('$kd_skpd',17)

                        UNION ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION  ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'
                        )a
                        where month(a.tgl)='$bulan' and left(kode,17)=left('$kd_skpd',17) ORDER BY a.tgl,a.kode,CAST(bku AS int)";
                        $hasil = $this->db->query($sql);       
                        $saldo=$saldotunai;
                        $total_terima=0;
                        $total_keluar=0;
                        foreach ($hasil->result() as $row){
                           $bku =$row->bku ;
                           $tgl =$row->tgl;
                           $skpd = $row->kode;
                           $uraian  =$row->ket; 
                           $terimatunai   =$row->masuk;
                           $keluartunai   =$row->keluar;
                           if ($keluartunai==1){ 
                           $saldo=$saldo+$terimatunai-$keluartunai;
                           $total_terima=$total_terima+$terimatunai;
                           $total_keluar=$total_keluar+$keluartunai;
                            $cRet .="<tr>
                            <td valign='top' align='center' style='font-size:12px;'>$tgl</td>
                            <td valign='top' align='center' style='font-size:12px;'>$skpd</td>
                            <td valign='top' align='center' style='font-size:12px;'>$bku</td>
                            <td valign='top' align='left' style='font-size:12px;'>$uraian</td>
                            <td valign='top' align='right' style='font-size:12px;'>".number_format($terimatunai,"2",",",".")."</td>
                            <td valign='top' align='right' style='font-size:12px;'>".number_format($keluartunai,"2",",",".")."</td>
                            <td valign='top' align='right' style='font-size:12px;'>".number_format($saldo,"2",",",".")."</td>
                                      </tr>";                     
                           }else{
                           $saldo=$saldo+$terimatunai-$keluartunai;
                           $total_keluar=$total_keluar+$keluartunai;
                           $total_terima=$total_terima+$terimatunai;
                           $cRet .="<tr>
                        <td valign='top' align='center' style='font-size:12px;'>$tgl</td>
                        <td valign='top' align='center' style='font-size:12px;'>$skpd</td>
                        <td valign='top' align='center' style='font-size:12px;'>$bku</td>
                        <td valign='top' align='left' style='font-size:12px;'>$uraian</td>
                        <td valign='top' align='right' style='font-size:12px;'>".number_format($terimatunai,"2",",",".")."</td>
                        <td valign='top' align='right' style='font-size:12px;'>".number_format($keluartunai,"2",",",".")."</td>
                        <td valign='top' align='right' style='font-size:12px;'>".number_format($saldo,"2",",",".")."</td>
                                                      </tr>"; 
                                       
                           }           
                        }
        $cRet .="<tr>
        <td bgcolor='#CCCCCC' colspan='4' valign='top' align='center' style='font-size:12px;'>JUMLAH</td>
        <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;'>".number_format($total_terima,"2",",",".")."</td>
        <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;'>".number_format($total_keluar,"2",",",".")."</td>
        <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;'>".number_format($total_terima-$total_keluar+$saldotunai,"2",",",".")."</td>
                                      </tr>";               
                 $cRet .="</table>";
        $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>
         <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>Mengetahui:</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$daerah, ".$this->support->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'> NIP. $lcNipPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>NIP. $lcNipBP</td>
      
                </tr>";        
                       
                
        $cRet .='</table>';
         if($print==0){
             $data['prev']= $cRet;    
             echo ("<title>Kas Tunai </title>");
             echo $cRet;
             }
         else{
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0',0,'');
            }
    }


    function cetak_kas_tunai(){
			$print = $this->uri->segment(3);
			$thn_ang = $this->session->userdata('pcThang');
			$kd_skpd  = $this->session->userdata('kdskpd');
			$bulan= $_REQUEST['tgl1'];
			$spasi= $_REQUEST['spasi'];
			$jns_bpp= $_REQUEST['jns_bp'];
			$kd_skpddd = substr($kd_skpd,0,17);
			$kd_skpddd = $kd_skpddd . ".0000";
			$adinas=$this->db->query("select provinsi,daerah from sclient WHERE kd_skpd='$kd_skpddd'");
			$dinas=$adinas->row();
			$prov=$dinas->provinsi;
			$daerah=$dinas->daerah;
			$hasil = $this->db->query("SELECT nm_skpd from ms_skpd where kd_skpd = '$kd_skpd'");
			$trsk = $hasil->row();          
			$nm_skpd = $trsk->nm_skpd;
			
            $lcperiode = $this->tukd_model->getBulan($bulan);
       
			$tgl_ttd= $_REQUEST['tgl_ttd'];
         
			$ttd1 = str_replace('123456789',' ',$_REQUEST['ttd1']);
			$ttd2 = str_replace('123456789',' ',$_REQUEST['ttd2']);			
			

				$csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE  id_ttd='$ttd1'";	
			
			$hasil = $this->db->query($csql);
			$trh1 = $hasil->row();          
			$lcNmPA = $trh1->nama;
			$lcNipPA = $trh1->nip; 			
			$lcJabPA = $trh1->jabatan; 			
			$lcPangkatPA = $trh1->pangkat; 			
			if($jns_bpp=="bpp"){
			$csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE  id_ttd='$ttd2'";
			}else{
            $csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE id_ttd='$ttd2'";
            }
            $hasil = $this->db->query($csql);
			$trh2 = $hasil->row();          
			$lcNmBP = $trh2->nama;
			$lcNipBP = $trh2->nip;
			$lcJabBP = $trh2->jabatan;
			$lcPangkatBP = $trh2->pangkat;
		/* SEBELUM 
		$esteh="SELECT 
				SUM(case when jns=1 then jumlah else 0 end ) AS terima,
				SUM(case when jns=2 then jumlah else 0 end) AS keluar
				FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
				select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALL
				select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar UNION ALL
				select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans<>4 and pot_khusus =0  
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd				
				UNION ALL
				SELECT	a.tgl_bukti AS tgl,	a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
					FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
					LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
					LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar <> 1
					GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
				UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' UNION ALL
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'
				) a 
				where month(a.tgl)<'$bulan' and kode='$kd_skpd'";
		*/
		$esteh="SELECT 
				SUM(case when jns=1 then jumlah else 0 end ) AS terima,
				SUM(case when jns=2 then jumlah else 0 end) AS keluar
				FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
				select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN ('4','2') and pot_khusus in ('0','2') and bank='TN'
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd				
				UNION ALL
				SELECT	a.tgl_bukti AS tgl,	a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
								FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
								LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
								LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c
								ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
								AND MONTH(a.tgl_bukti)<'$bulan' and a.kd_skpd='$kd_skpd' 
								AND a.no_bukti NOT IN(
								select no_bukti from trhtransout 
								where no_sp2d in 
								(SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
								(SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)<'$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
								GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
						UNION ALL
				SELECT	tgl_bukti AS tgl,	no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
								from trhtransout 
								WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') and no_sp2d in 
								(SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
								(SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)<'$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'
				
				UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' UNION ALL
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'
				) a 
				where month(a.tgl)<'$bulan' and kode='$kd_skpd'";
		$hasil = $this->db->query($esteh);
				
		$okok = $hasil->row();  
		$tox_awal="SELECT isnull(sld_awal,0) AS jumlah FROM ms_skpd where kd_skpd='$kd_skpd'";
					 $hasil = $this->db->query($tox_awal);					 
					 $tox = $hasil->row('jumlah');
					 $terima = $okok->terima;
					 $keluar = $okok->keluar;					 
					 $saldotunai=($terima+$tox)-$keluar;
         $cRet = '';
         $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='6' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU KAS TUNAI<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;'></td>
            </tr>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;'></td>
            </tr>            
            <tr>
                <td align='left' colspan='0' style='font-size:12px;'>SKPD</td>
                <td align='left' colspan='0' style='font-size:12px;'>: $kd_skpd &nbsp; $nm_skpd</td> 
            </tr>
            <tr>
                <td align='left' colspan='0' style='font-size:12px;'>PERIODE</td>
                <td align='left' colspan='4' style='font-size:12px;'>: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;'>&nbsp;</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;'>&nbsp;</td>
            </tr>
			</table>";

             $cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
            <thead>
			<tr>
                <td bgcolor='#CCCCCC' align='center' width='5%' style='font-size:12px;' >Tanggal.</td>
                <td bgcolor='#CCCCCC' align='center' width='5%' style='font-size:12px;'>No. BKU</td>
                <td bgcolor='#CCCCCC' align='center' width='35%' style='font-size:12px;'>Uraian</td>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;'>Penerimaan</td> 
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;'>Pengeluaran</td>  
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;'>Saldo</td>            
            </tr> 
			</thead>
			<tr>
			
                <td align='center' width='5%' style='font-size:12px;' ></td>
                <td align='center' width='5%' style='font-size:12px;'></td>
                <td align='right' width='35%' style='font-size:12px;'>Saldo Lalu</td>
                <td align='center' width='10%' style='font-size:12px;'></td> 
                <td align='center' width='10%' style='font-size:12px;'></td>  
				<td align='right' width='10%' style='font-size:12px;'>".number_format($saldotunai,"2",",",".")."</td>            
            </tr> ";
			
				/*AWAL SEBELUM UPDATE POTONGAN		
				$sql="SELECT * FROM (
						SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
						select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as masuk, 0 as keluar,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALl
						select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, 0 as masuk,nilai as keluar,kd_skpd as kode from tr_panjar UNION ALL
						select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
								from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
								where jns_trans<>4 and pot_khusus =0  
								GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd	
						UNION ALL
                        SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk,SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
								FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
								LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
								LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar <> 1
								GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd	
						UNION ALL
						SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
						SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
						SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI')a
						where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,CAST(left(bku,4) AS int)";
                */			
				$sql="SELECT * FROM (
						SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
						select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
								from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
								where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TN'   
								GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd	
						UNION ALL
						SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
								FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
								LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
								LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c
								ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
								AND MONTH(a.tgl_bukti)='$bulan' and a.kd_skpd='$kd_skpd' 
								AND a.no_bukti NOT IN(
								select no_bukti from trhtransout 
								where no_sp2d in 
								(SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
								(SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)='$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
								GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
						UNION ALL
						select tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 AS masuk, ISNULL(total,0)  AS keluar,kd_skpd AS kode 
								from trhtransout 
								WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in 
								(SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
								(SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)='$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'

						UNION ALL
						SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
						SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
						SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION  ALL
						SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI')a
						where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,CAST(left(bku,4) AS int)";
                        $hasil = $this->db->query($sql);       
                        $saldo=$saldotunai;
						$total_terima=0;
						$total_keluar=0;
                        foreach ($hasil->result() as $row){
                           $bku =$row->bku ;
                           $tgl =$row->tgl;
                           $uraian  =$row->ket; 
                           $terimatunai   =$row->masuk;
                           $keluartunai   =$row->keluar;
                           if ($keluartunai==1){ 
                           $saldo=$saldo+$terimatunai-$keluartunai;
						   $total_terima=$total_terima+$terimatunai;
						   $total_keluar=$total_keluar+$keluartunai;
                            $cRet .="<tr>
							<td valign='top' align='center' style='font-size:12px;'>$tgl</td>
							<td valign='top' align='center' style='font-size:12px;'>$bku</td>
							<td valign='top' align='left' style='font-size:12px;'>$uraian</td>
							<td valign='top' align='right' style='font-size:12px;'>".number_format($terimatunai,"2",",",".")."</td>
							<td valign='top' align='right' style='font-size:12px;'>".number_format($keluartunai,"2",",",".")."</td>
							<td valign='top' align='right' style='font-size:12px;'>".number_format($saldo,"2",",",".")."</td>
                                      </tr>";                     
                           }else{
                           $saldo=$saldo+$terimatunai-$keluartunai;
						   $total_keluar=$total_keluar+$keluartunai;
						   $total_terima=$total_terima+$terimatunai;
                           $cRet .="<tr>
						<td valign='top' align='center' style='font-size:12px;'>$tgl</td>
						<td valign='top' align='center' style='font-size:12px;'>$bku</td>
						<td valign='top' align='left' style='font-size:12px;'>$uraian</td>
						<td valign='top' align='right' style='font-size:12px;'>".number_format($terimatunai,"2",",",".")."</td>
						<td valign='top' align='right' style='font-size:12px;'>".number_format($keluartunai,"2",",",".")."</td>
						<td valign='top' align='right' style='font-size:12px;'>".number_format($saldo,"2",",",".")."</td>
													  </tr>"; 
                                       
                           }           
                        }
		$cRet .="<tr>
        <td bgcolor='#CCCCCC' colspan='3' valign='top' align='center' style='font-size:12px;'>JUMLAH</td>
        <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;'>".number_format($total_terima,"2",",",".")."</td>
        <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;'>".number_format($total_keluar,"2",",",".")."</td>
        <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;'>".number_format($total_terima-$total_keluar+$saldotunai,"2",",",".")."</td>
                                      </tr>"; 				
                 $cRet .="</table>";
		$cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>
		 <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>Mengetahui:</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$daerah, ".$this->support->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'> NIP. $lcNipPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>NIP. $lcNipBP</td>
      
                </tr>";        
		               
                
        $cRet .='</table>';
         if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Kas Tunai </title>");
			 echo $cRet;
			 }
		 else{
			$this->master_pdf->_mpdf('',$cRet,10,10,10,'0',0,'');
			}
    }




}