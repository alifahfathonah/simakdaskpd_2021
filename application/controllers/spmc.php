<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 select_pot_taspen() rekening gaji manual. harap cek selalu
 */

class spmc extends CI_Controller {

 
    function __construct(){   
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }     
  
    function spm(){
        $data['page_title']= 'INPUT S P M';
        $this->template->set('title', 'INPUT S P M');   
        $this->template->load('template','tukd/spm/spm',$data) ; 
    }
 
    function nospp_2() {
		$kd_skpd = $this->session->userdata('kdskpd');
		$tanggal=date("d");
		$bulan=date("m");
		$bulan=$bulan-1;
		if($bulan<10){
			$bulan = str_replace("0","",$bulan);
			
		}
		if($tanggal<32){
		$sql = "SELECT kd_sub_skpd, no_spp,tgl_spp,kd_skpd,nm_skpd,jns_spp,keperluan,bulan,no_spd,bank,nmrekan,no_rek,jns_beban,npwp FROM trhspp WHERE no_spp NOT IN (SELECT no_spp FROM trhspm WHERE kd_skpd='$kd_skpd')and kd_skpd = '$kd_skpd' ";
		}
		else{

		$sql = "SELECT kd_sub_skpd, no_spp,tgl_spp,kd_skpd,nm_skpd,jns_spp,keperluan,bulan,no_spd,bank,nmrekan,no_rek,jns_beban,npwp 
		FROM trhspp WHERE no_spp NOT IN (SELECT no_spp FROM trhspm WHERE kd_skpd='$kd_skpd') AND jns_spp IN ('1','2','3') and kd_skpd = '$kd_skpd' 
		AND kd_skpd IN (select kd_skpd from trhspj_ppkd WHERE bulan='$bulan' AND cek='1' AND kd_skpd='$kd_skpd')
		UNION ALL
		SELECT kd_sub_skpd, no_spp,tgl_spp,kd_skpd,nm_skpd,jns_spp,keperluan,bulan,no_spd,bank,nmrekan,no_rek,jns_beban,npwp 
		FROM trhspp WHERE no_spp NOT IN (SELECT no_spp FROM trhspm WHERE kd_skpd='$kd_skpd') AND jns_spp IN ('4','5','6') and kd_skpd = '$kd_skpd' 
		";
		}
   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
		$result[] = array(
			'id' => $ii,        
			'no_spp' => $resulte['no_spp'],
			'tgl_spp' => $resulte['tgl_spp'],
			'kd_skpd' => $resulte['kd_skpd'],
			'nm_skpd' => $resulte['nm_skpd'],    
			'jns_spp' => $resulte['jns_spp'],
			'keperluan' => $resulte['keperluan'],
			'bulan' => $resulte['bulan'],
			'no_spd' => $resulte['no_spd'],
			'bank' => $resulte['bank'],
			'nmrekan' => $resulte['nmrekan'],
			'no_rek' => $resulte['no_rek'],
			'jns_beban' => $resulte['jns_beban'],
            'kd_sub_skpd' => $resulte['kd_sub_skpd'],
			'npwp' => $resulte['npwp']
			);
			$ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();	   
	}

	function config_spm(){
        $skpd     	= $this->session->userdata('kdskpd');
        $no_spp     = $this->input->post('no_spp');
        $spm 		= explode("SPP", $no_spp);
        $nomor      = $spm[0]."SPM".$spm[1];
        echo json_encode($nomor); 	
    }

     function rek_pot_trans() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $lccr   = $this->input->post('q') ;
        $spp=$this->input->post('nospp_pot');

        $sql    = " SELECT top 10 kd_rek6,nm_rek6 FROM trdspp where no_spp = '$spp' AND kd_skpd ='$kd_skpd' and ( upper(kd_rek6) like upper('%$lccr%')
                    OR upper(nm_rek6) like upper('%$lccr%') )  ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();	   
	}

    function rek_pot() {
        $lccr   = $this->input->post('q') ;
        $sql    = " SELECT * from (SELECT '1' urut, map_pot kd_rek6, nm_rek5 nm_rek6 FROM ms_pot where map_pot<>'' 
            union all
            select '2' urut,kd_rek6, nm_rek6 from ms_rek6 where left(kd_rek6,1)=2 and kd_rek6 not in(select map_pot from ms_pot) ) okeii where 
          ( upper(kd_rek6) like upper('%$lccr%')
                    OR upper(nm_rek6) like upper('%$lccr%') ) order by urut, kd_rek6 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);  
	}   

	function pot_kosong() {
        $kd_skpd     = "2";
        $spm=$this->input->post('spm');
        $sql = "SELECT * FROM trspmpot where no_spm='$spm' AND kd_skpd='$kd_skpd' order by kd_rek6 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'kd_trans' => $resulte['kd_trans'],  
                        'nm_rek5' => $resulte['nm_rek6'],  
                        'pot' => $resulte['pot'],
                        'nilai' => $resulte['nilai']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     
	}

    function pot() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $spm=$this->input->post('spm');
        echo $this->spm_model->pot($kd_skpd,$spm);
    }

	function simpan_tukd_spm(){
        
        $tabel   = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcnotagih = $this->input->post('tagih');
		$skpd  = $this->session->userdata('kdskpd');

        $sql = "select $cid from $tabel where $cid='$lcid'  ";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            echo '1';
        }else{
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if($asg){
				if($tabel=='trhspm'){
					$sql1 = " UPDATE trhspp SET status='1' where no_spp='$lcnotagih' AND kd_skpd='$skpd'";
					$asg1 = $this->db->query($sql1);
				}
                echo '2';
            }else{
                echo '0';
            }
        }
        if($tabel=='trhspp'){
            $sql1 = " UPDATE trhtagih SET sts_tagih='1' where no_bukti='$lcnotagih' AND kd_skpd='$skpd'";
            $asg1 = $this->db->query($sql1);
        }
    }

    function dsimpan_potspm()	{
		$kdskpd  = $this->session->userdata('kdskpd');	
		$no_spm = $this->input->post('no');
		$csql     = $this->input->post('sql');            
		$sql = "DELETE from trspmpot where no_spm='$no_spm' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trspmpot (no_spm,kd_rek6,nm_rek6,nilai,kd_skpd,pot,kd_trans, kd_sub_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                       echo json_encode($msg);
						}else{
						$msg = array('pesan'=>'1');
						echo json_encode($msg);
					}
				}
	} 

    function load_spm() {
		$kd_skpd = $this->session->userdata('kdskpd');
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_spm) like upper('%$kriteria%') or tgl_spm like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
		$sql = "SELECT count(*) as tot  from trhspm WHERE kd_skpd = '$kd_skpd' " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT top $rows a.*,(select count(no_spp) from trhspm
				where no_spp =a.no_spp AND kd_skpd = a.kd_skpd
				group by no_spp) AS tot_spm,
				(select isnull(status,0) from config_valspm where no_spm=a.no_spm and
                kd_skpd=a.kd_skpd) as stt_valspm from trhspm a
				WHERE a.kd_skpd = '$kd_skpd' and a.no_spm
				not in (SELECT top $offset no_spm from trhspm WHERE kd_skpd = '$kd_skpd'
				order by tgl_spm,no_spm) $where order by a.tgl_spm,a.no_spm";
                
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                    if ($resulte['tot_spm'] >= 2){
                        $hasil="<button class='button-abu'style='font-size: 10px'>Data SPP Double</button>";
                    }else if ($resulte['status'] == 1){
                      $hasil="<button class='button-biru'style='font-size: 10px'>Sudah Dibuat SP2D</button>";
                    }else{
                        if ($resulte['tot_spm'] >= 2){
                            $hasil="<button class='button-abu'style='font-size: 10px'>Data SPP Double</button>";
                        }else if ($resulte['stt_valspm'] == 1 || $resulte['stt_valspm'] == 2){
                            $hasil="<button class='button-kuning'style='font-size: 10px'>Berkas Sedang Diproses</button>";
                        }else if($resulte['stt_valspm'] ==3){ 
                            $hasil="<button class='button-merah'style='font-size: 10px'>Berkas Dibatalkan</button>";
                        }else{
                            $hasil="<button class='button-cerah'style='font-size: 10px'>Belum dibuat SP2D</button>";
                        }
                    }
                
           $row[] = array(
                        'id' => $ii,
                        'urut' => $resulte['urut'],                        
                        'no_spm' => $resulte['no_spm'],
                        'tgl_spm' => $resulte['tgl_spm'],        
                        'no_spp' => $resulte['no_spp'],
                        'tgl_spp' => $resulte['tgl_spp'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],    
                        'jns_spp' => $resulte['jns_spp'],
                        'jns_beban' => $resulte['jenis_beban'],
                        'keperluan' => $resulte['keperluan'],
                        'bulan' => $resulte['bulan'],
                        'no_spd' => $resulte['no_spd'],
                        'kd_sub_skpd' => $resulte['kd_sub_skpd'],
                        'bank' => $resulte['bank'],
                        'nmrekan' => $resulte['nmrekan'],
                        'no_rek' => $resulte['no_rek'],
                        'npwp' => $resulte['npwp'],
                        'status' =>$resulte['status'],
                        'status2' =>$hasil,
                        'stt_valspm' =>$resulte['stt_valspm'],
                        'tot_spm' =>$resulte['tot_spm']                                                                                  
                        );
                        $ii++;
        }
           
         $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
     $query1->free_result();	   
	}
    
    function hapus_spm() {   
        $kd_skpd = $this->session->userdata('kdskpd');
        $nom = $this->input->post('no');
        $spp = $this->input->post('spp');        
        $query = $this->db->query("DELETE FROM trhspm WHERE no_spm='$nom' AND kd_skpd='$kd_skpd'");
        $query = $this->db->query("DELETE FROM trspmpot WHERE no_spm='$nom' AND kd_skpd='$kd_skpd'");
       echo  $query = $this->db->query("UPDATE trhspp SET status='0' WHERE no_spp='$spp' AND kd_skpd='$kd_skpd'");
    }  

    function update_spm(){
        $skpd    = $this->session->userdata('kdskpd');
        $tabel   = $this->input->post('tabel');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');
        
        if (  $lcid <> $lcid_h ) {
           $sql     = "select $cid from $tabel where $cid='$lcid' ";
           $res     = $this->db->query($sql);
           if ( $res->num_rows()>0 ) {
                echo '1';
                exit();
           } 
        }
                $sql1 = "UPDATE trhsp2d SET sp2d_edit='1' where no_spm='$lcid'";
                    $asg1 = $this->db->query($sql1);
                    
        $query   = $this->input->post('st_query'); 
        $asg     = $this->db->query($query);
        if ( $asg > 0 ){
           echo '2';
        } else {
           echo '0';
        }
    
    }

   function dsimpan_pot_delete_ar()
    {
           $skpd    = $this->input->post('cskpd');
           $spm     = $this->input->post('spm');
           $kd_rek5 = $this->input->post('kd_rek5');

           $sqlcek = "select count (no_spm) as result from trhsp2d where left(kd_skpd,22)=left('$skpd',22) and no_spm='$spm'";
           $asg2 = $this->db->query($sqlcek)->row();

           if ($asg2->result==1) { 
                 echo '2' ;
                 exit();

            }else{
           $sql = "delete from trspmpot where kd_skpd='$skpd' and no_spm='$spm' and kd_rek6='$kd_rek5'";
           $asg = $this->db->query($sql);
            if ($asg > 0) { 
                 echo '1' ;
                 exit();
            } else {
                 echo '0' ;
                 exit();
            }
        }
    }

     function update_dsimpan_potspm()   {
        $kdskpd  = $this->session->userdata('kdskpd');  
        $no_spm = $this->input->post('no');
        $no_spm_hide = $this->input->post('no_hide');
        $csql     = $this->input->post('sql');            
        $sql = "DELETE from trspmpot where no_spm='$no_spm_hide' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trspmpot (no_spm,kd_rek6,nm_rek6,nilai,kd_skpd,pot,kd_trans, kd_sub_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                       echo json_encode($msg);
                        }else{
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
    } 

    function select_pot_taspen() {
        $kdskpd     = $this->session->userdata('kdskpd');
        $spp=$this->input->post('spp');

        $sql = '';
        $i = 1;
        
        do {
            switch ($i) {
                case 1: $kdrek = '2110101'; $kdrek90 = '210108010001'; $nil = 'PIWP8'; break;
                case 2: $kdrek = '2110202'; $kdrek90 = '210102010001'; $nil = 'PIWP2'; break;
                case 3: $kdrek = '2110501'; $kdrek90 = '210107010001'; $nil = 'PTAPERUM'; break;
                case 4: $kdrek = '2130101'; $kdrek90 = '210105010001'; $nil = 'PPAJAK'; break;
                case 5: $kdrek = '2110201'; $kdrek90 = '210102010001'; $nil = 'PASKES'; break;
                case 6: $kdrek = '2111001'; $kdrek90 = '210103010001'; $nil = 'PJKK'; break;
                case 7: $kdrek = '2111101'; $kdrek90 = '210104010001'; $nil = 'PJKM'; break;
                                
            }
            $sql .= "
                     SELECT z.* from (
                     SELECT '$kdrek90' [kd_rek6],'510101010001' [kd_trans],(select top 1 nm_rek5 from ms_pot where kd_rek5='$kdrek') [nm_rek6],'' [pot],$nil [nilai] 
                     FROM ttaspen a join map_taspen b on a.KDSKPD_SIM=b.kd_skpd_sim 
                     WHERE a.NO_SPP='$spp' and b.kd_skpd='$kdskpd' )z where z.nilai<>0";

            if($i!=7){
                $sql .= " union all ";
            }                           
            $i++;
        } while ($i <= 7);
            
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'kd_trans' => $resulte['kd_trans'],  
                        'nm_rek5' => $resulte['nm_rek6'],  
                        'pot' => $resulte['pot'],
                        'nilai' => number_format($resulte['nilai'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
         //$query1->free_result();   
    }

    function load_bendahara_p($kdskpd){
        $lccr=$this->input->post('q');
        echo $this->master_ttd->load_bendahara_p($kdskpd,$lccr);
    }

    function load_tanda_tangan($kdskpd=''){
        $lccr=$this->input->post('q');
        echo $this->master_ttd->load_tanda_tangan($kdskpd,$lccr);
    }

    function load_ppk_pptk($kdskpd=''){
        $lccr=$this->input->post('q');
        echo $this->master_ttd->load_ppk_pptk($kdskpd,$lccr);
    }

    function load_ttd_bud($kdskpd=''){
        $lccr=$this->input->post('q');
        echo $this->master_ttd->load_ttd_bud($kdskpd,$lccr);
    }

    function load_sum_pot(){
        $skpd = $this->session->userdata('kdskpd');
        $spm    = $this->input->post('spm');
        $query1 = $this->db->query("SELECT sum(nilai) as rektotal from trspmpot where no_spm='$spm' AND kd_skpd='$skpd'");  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['rektotal'],2,'.',','),
                        'rektotal1' => $resulte['rektotal']                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    }

    function load_sum_spm(){
        $skpd = $this->session->userdata('kdskpd');
        $spp = $this->input->post('spp');
        $query1 = $this->db->query("SELECT sum(nilai) as rekspm from trdspp where no_spp='$spp' AND kd_skpd='$skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'rekspm1' => $resulte['rekspm']                       
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

    function config_bank2(){
        
        $lccr   = $this->input->post('q');
        $sql    = "SELECT top 10 kode, nama FROM ms_bank where upper(kode) like '%$lccr%' or upper(nama) like '%$lccr%' order by kode ";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_bank' => $resulte['kode'],
                        'nama_bank' => $resulte['nama']                                                                                        
                        );
                        $ii++;
        } 
        echo json_encode($result); 
    }
    
 }