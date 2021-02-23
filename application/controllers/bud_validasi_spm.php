<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class bud_validasi_spm extends CI_Controller {

	function __construct(){	 
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
	}  

    function spm(){
        $data['page_title']= 'VALIDASI SPM';
        $this->template->set('title', 'VALIDASI SPM');   
        $this->template->load('template','tukd/sp2d/validasi_spm',$data) ; 
    } 

  	function perbaikan_spm(){
        $data['page_title']= 'MENU PERBAIKAN DATA SPM DAN SPP';
        $this->template->set('title', 'MENU PERBAIKAN DATA');   
        $this->template->load('template','tukd/sp2d/perbaikan_spm',$data) ; 
    }

	function spm_skpd() {
	    $idx = $this->session->userdata('pcUser');
		$jns = $this->uri->segment(3);
		
		if($jns=='6'){
			$jns="in ('6')";
		}else if($jns=='4'){
			$jns="in ('4')";
		}else if($jns=='3'){
			$jns="in ('3')";
		}else if($jns=='2'){
			$jns="in ('2')";
		}else if($jns=='1'){
			$jns="in ('1')";
		}else if($jns=='5'){
			$jns="in ('5')";
		}else if($jns=='7'){
			$jns="in ('7')";
		} 
		
        $sql = "SELECT b.kd_skpd,b.nm_skpd FROM trhspm b where status!=1 and jns_spp $jns  
        group by b.kd_skpd,b.nm_skpd        
        order by b.kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result(); 	  
	}	
    function load_validasi_spm($sskpd='',$jns_spm=''){
        $kriteria = '';
        $kriteria = $this->input->post('kriteria_init');


        $query1 = $this->db->query("
         SELECT b.no_tagih,c.ket,c.ket_bast,a.urut,a.nm_skpd,b.jns_spp,a.no_spp,a.no_spm,a.kd_skpd,
         a.nm_skpd,a.keperluan,a.bank,a.nmrekan,a.no_rek,a.npwp,b.pimpinan,a.status,
         (select tgl_terima from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as tgl_terima,
         (select tgl_setuju from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as tgl_setuju,
         (select ket from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as ket_val,
         (select status from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as status_validasi,
         (select gua from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm1,
         (select gub from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm2,
         (select guc from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm3,
         (select gud from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm4,
         (select gue from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm5,
         (select guf from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm6,
         (select gug from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm7,
         (select tua from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm8,
         (select tub from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm9,
         (select tuc from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm10,
         (select tud from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm11,
         (select tue from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm12,
         (select tuf from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm13,
         (select tug from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm14,
         (select lsa from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm15,
         (select lsb from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm16,
         (select lsc from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm17,
         (select lsd from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm18,
         (select lse from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm19,
         (select lsf from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm20,
         (select lsg from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm21,
         (select lsh from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm22,
         (select lsi from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm23,
         (select lsj from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm24,
         (select lsk from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm25,
         (select lsl from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm26,
         (select lsn from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm27,
         (select lsm from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm28,
         (select lso from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm29,
         (select lsp from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm30,
         (select lsq from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm31,
         (select lsr from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm32,
         (select lss from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm33,
         (select lsgja from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm34,
         (select lsgjb from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm35,
         (select lsgjc from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm36,
         (select uma from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm37,
         (select umb from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm38,
         (select umc from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm39,
         (select umd from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm40,
         (select ume from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm41,
         (select umf from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm42,
         (select umg from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm43,
         (select umh from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm44,
         (select umi from config_valspm where no_spm=a.no_spm and kd_skpd=a.kd_skpd) as spm45,
         (select count(no_spp) from trhspm
				where no_spp =a.no_spp AND kd_skpd = a.kd_skpd
				group by no_spp) AS tot_spm, a.nilai	            
         
         from trhspm a 
         inner join trhspp b on a.no_spp=b.no_spp left 
         join trhtagih c on b.no_tagih = c.no_bukti                 
         where left(a.kd_skpd,7)=left('$sskpd',7) and b.jns_spp IN ('$jns_spm') and a.status ='0' AND (upper(a.no_spm) like upper('%%')) order by urut");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {   
            if($resulte['status_validasi']=='1'){
                $ketstt = 'DISETUJUI';
            }else if($resulte['status_validasi']=='2'){
                $ketstt = 'DITUNDA';
            }else if($resulte['status_validasi']=='3'){
                $ketstt = 'DIBATALKAN';
            }else{
                $ketstt = 'BELUM/DAFTAR ANTRIAN';
            }
            
            
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],    
                        'no_spm' => $resulte['no_spm'],
                        'no_spp' => $resulte['no_spp'],
                        'keperluan' => $resulte['keperluan'],
                        'jns_spp' => $resulte['jns_spp'],
                        'nmrekan' => $resulte['nmrekan'],
                        'no_rek' => $resulte['no_rek'],
                        'npwp' => $resulte['npwp'],
                        'nilai' => number_format($resulte['nilai'],2,',','.'),
                        'terbilang' =>$this->tukd_model->terbilang($resulte['nilai']),
                        'bank' => $resulte['bank'],
                        'pimpinan' => $resulte['pimpinan'],
                        'no_tagih' => $resulte['no_tagih'],
                        'ket' => $resulte['ket'],
                        'ket_bast' => $resulte['ket_bast'],
                        'sttval' => $ketstt,
                        'tgl_terima' => $resulte['tgl_terima'],
                        'tgl_setuju' => $resulte['tgl_setuju'],
                        'ket_val' => $resulte['ket_val'], 
                        'stt_validasi' => $resulte['status_validasi'],
                        'spm1' => $resulte['spm1'], 
                        'spm2' => $resulte['spm2'],
                        'spm3' => $resulte['spm3'],
                        'spm4' => $resulte['spm4'],
                        'spm5' => $resulte['spm5'],
                        'spm6' => $resulte['spm6'],
                        'spm7' => $resulte['spm7'],
                        'spm8' => $resulte['spm8'],
                        'spm9' => $resulte['spm9'],
                        'spm10' => $resulte['spm10'],
                        'spm11' => $resulte['spm11'],
                        'spm12' => $resulte['spm12'],
                        'spm13' => $resulte['spm13'],
                        'spm14' => $resulte['spm14'],
                        'spm15' => $resulte['spm15'],
                        'spm16' => $resulte['spm16'],
                        'spm17' => $resulte['spm17'],
                        'spm18' => $resulte['spm18'],
                        'spm19' => $resulte['spm19'],
                        'spm20' => $resulte['spm20'],
                        'spm21' => $resulte['spm21'],
                        'spm22' => $resulte['spm22'],
                        'spm23' => $resulte['spm23'],
                        'spm24' => $resulte['spm24'],
                        'spm25' => $resulte['spm25'],
                        'spm26' => $resulte['spm26'],
                        'spm27' => $resulte['spm27'],
                        'spm28' => $resulte['spm28'],
                        'spm29' => $resulte['spm29'],
                        'spm30' => $resulte['spm30'],
                        'spm31' => $resulte['spm31'],
                        'spm32' => $resulte['spm32'],
                        'spm33' => $resulte['spm33'],
                        'spm34' => $resulte['spm34'],
                        'spm35' => $resulte['spm35'],
                        'spm36' => $resulte['spm36'],
                        'spm37' => $resulte['spm37'],
                        'spm38' => $resulte['spm38'],
                        'spm39' => $resulte['spm39'],
                        'spm40' => $resulte['spm40'],
                        'spm41' => $resulte['spm41'],
                        'spm42' => $resulte['spm42'],
                        'spm43' => $resulte['spm43'],
                        'spm44' => $resulte['spm44'],
                        'spm45' => $resulte['spm45'],
                        'tot_spm' => $resulte['tot_spm']                 
                        );
                        $ii++;
        }
           echo json_encode($result);   
    }
	
function simpan_validasi_spm(){
      $spmedit = $this->input->post('spmedit');
      $tglterima = $this->input->post('tglterima');
      $tglsetuju = $this->input->post('tglsetuju');
      $ketspm = $this->input->post('ketspm');
      $statusspm = $this->input->post('statusspm');
      $skppd = $this->input->post('kdskpd');
      $username = $this->session->userdata('pcNama');
      
      $spm1 = $this->input->post('dspm1');
      $spm2 = $this->input->post('dspm2');
      $spm3 = $this->input->post('dspm3');
      $spm4 = $this->input->post('dspm4');
      $spm5 = $this->input->post('dspm5');
      $spm6 = $this->input->post('dspm6');
      $spm7 = $this->input->post('dspm7');
      $spm8 = $this->input->post('dspm8');
      $spm9 = $this->input->post('dspm9');
      $spm10 = $this->input->post('dspm10');
      $spm11 = $this->input->post('dspm11');
      $spm12 = $this->input->post('dspm12');
      $spm13 = $this->input->post('dspm13');
      $spm14 = $this->input->post('dspm14');
      $spm15 = $this->input->post('dspm15');
      $spm16 = $this->input->post('dspm16');
      $spm17 = $this->input->post('dspm17');
      $spm18 = $this->input->post('dspm18');
      $spm19 = $this->input->post('dspm19');
      $spm20 = $this->input->post('dspm20');
      $spm21 = $this->input->post('dspm21');
      $spm22 = $this->input->post('dspm22');
      $spm23 = $this->input->post('dspm23');
      $spm24 = $this->input->post('dspm24');
      $spm25 = $this->input->post('dspm25');
      $spm26 = $this->input->post('dspm26');
      $spm27 = $this->input->post('dspm27');
      $spm28 = $this->input->post('dspm28');
      $spm29 = $this->input->post('dspm29');
      $spm30 = $this->input->post('dspm30');
      $spm31 = $this->input->post('dspm31');
      $spm32 = $this->input->post('dspm32');
      $spm33 = $this->input->post('dspm33');
      $spm34 = $this->input->post('dspm34');
      $spm35 = $this->input->post('dspm35');
      $spm36 = $this->input->post('dspm36');
      $spm37 = $this->input->post('dspm37');
      $spm38 = $this->input->post('dspm38');
      $spm39 = $this->input->post('dspm39');
      $spm40 = $this->input->post('dspm40');
      $spm41 = $this->input->post('dspm41');
      $spm42 = $this->input->post('dspm42');
      $spm43 = $this->input->post('dspm43');
      $spm44 = $this->input->post('dspm44');
      $spm45 = $this->input->post('dspm45');
            
            $cek = $this->db->query("select count(*) as total from config_valspm where no_spm='$spmedit' and kd_skpd='$skppd'")->row();
            $hsl_spm = $cek->total;
            
            if($hsl_spm>0){
                $sql1 = "UPDATE config_valspm set username='$username',tgl_terima='$tglterima',tgl_setuju='$tglsetuju',ket='$ketspm',status='$statusspm',gua='$spm1',gub='$spm2',guc='$spm3',gud='$spm4',gue='$spm5',guf='$spm6',gug='$spm7',tua='$spm8',tub='$spm9',tuc='$spm10',tud='$spm11',tue='$spm12',tuf='$spm13',tug='$spm14',lsa='$spm15',lsb='$spm16',lsc='$spm17',lsd='$spm18',lse='$spm19',lsf='$spm20',lsg='$spm21',lsh='$spm22',lsi='$spm23',lsj='$spm24',lsk='$spm25',lsl='$spm26',lsn='$spm27',lsm='$spm28',lso='$spm29',lsp='$spm30',lsq='$spm31',lsr='$spm32',lss='$spm33',lsgja='$spm34',lsgjb='$spm35',lsgjc='$spm36',uma='$spm37',umb='$spm38',umc='$spm39',umd='$spm40',ume='$spm41',umf='$spm42',umg='$spm43',umh='$spm44',umi='$spm45' where no_spm='$spmedit' and kd_skpd='$skppd'";
                $asg1 = $this->db->query($sql1);    
            }else{
                $sql1 = "INSERT into config_valspm (no_spm,kd_skpd,username,tgl_terima,tgl_setuju,ket,status,gua,gub,guc,gud,gue,guf,gug,tua,tub,tuc,tud,tue,tuf,tug,lsa,lsb,lsc,lsd,lse,lsf,lsg,lsh,lsi,lsj,lsk,lsl,lsn,lsm,lso,lsp,lsq,lsr,lss,lsgja,lsgjb,lsgjc,uma,umb,umc,umd,ume,umf,umg,umh,umi) values ('$spmedit','$skppd','$username','$tglterima','$tglsetuju','$ketspm','$statusspm','$spm1','$spm2','$spm3','$spm4','$spm5','$spm6','$spm7','$spm8','$spm9','$spm10','$spm11','$spm12','$spm13','$spm14','$spm15','$spm16','$spm17','$spm18','$spm19','$spm20','$spm21','$spm22','$spm23','$spm24','$spm25','$spm26','$spm27','$spm28','$spm29','$spm30','$spm31','$spm32','$spm33','$spm34','$spm35','$spm36','$spm37','$spm38','$spm39','$spm40','$spm41','$spm42','$spm43','$spm44','$spm45')";
                $asg1 = $this->db->query($sql1); 
            }
            
      if($asg1){
                echo '1';
            }else{
                echo '0';
            }       
    } 

    function select_data_untuk_cek() {
  	//$kd_skpd  = $this->session->userdata('kdskpd');
    $kd_skpd = $this->input->post('kd_skpd');
	$spp = $this->input->post('no_spp');
    $sql = "SELECT sum(hasil) hasil from(
select sisa,
case when anggaran-realisasi < 0 then 1 else 0 end as hasil from(
select kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,anggaran,realisasi,anggaran-realisasi as sisa,no_bukti,sumber from(
SELECT
	z.kd_sub_kegiatan kd_kegiatan,
	z.nm_sub_kegiatan nm_kegiatan,
	z.kd_rek6 kd_rek5,
	z.nm_rek6 nm_rek5,
	z.nilai,
( select nilai_ubah from (select w.kd_sub_kegiatan,w.kd_rek6,w.nilai_ubah from trdrka w where
z.kd_sub_kegiatan=w.kd_sub_kegiatan and z.kd_rek6 =w.kd_rek6
)w)
          as anggaran,
	( SELECT SUM(nilai) FROM 
					(select sum(a.nilai) nilai 
					from trdspp a 
					inner join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
					where a.kd_sub_kegiatan = z.kd_sub_kegiatan and a.kd_rek6 = z.kd_rek6 and left(a.kd_skpd,22)=left('$kd_skpd',22) and a.no_spp <> '' 
					AND b.jns_spp IN ('3','4','5','6')
					and (b.sp2d_batal !='1' or b.sp2d_batal IS NULL) 
					UNION ALL
					SELECT SUM(nilai) as nilai FROM trdtagih t 
					INNER JOIN trhtagih u 
					ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
					WHERE 
					t.kd_sub_kegiatan = z.kd_sub_kegiatan
					AND left(u.kd_skpd,22)=left('$kd_skpd',22)
					AND t.kd_rek = z.kd_rek6
					AND u.no_bukti 
					NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,7)=left('$kd_skpd',7))
					UNION ALL
					SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_sub_kegiatan= z.kd_sub_kegiatan and a.kd_rek6 = z.kd_rek6 and left(a.kd_skpd,22)=left('$kd_skpd',22) AND b.jns_spp IN ('1','2')
                    
                    UNION ALL                    
                    SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan= z.kd_sub_kegiatan and a.kd_rek6 = z.kd_rek6 and left(a.kd_skpd,22)=left('$kd_skpd',22) AND b.jns_spp IN ('4','6') and panjar in ('3')                    

                    UNION ALL
                    SELECT SUM(a.nilai) nilai FROM trdtransout_cmsbank a INNER JOIN trhtransout_cmsbank b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_sub_kegiatan= z.kd_sub_kegiatan and a.kd_rek6 = z.kd_rek6 and left(a.kd_skpd,22)=left('$kd_skpd',22) AND b.status_validasi = '0'
                    
					)b)
          as realisasi,
	no_bukti,
	sumber
FROM
	trdspp z
WHERE
	z.no_spp = '$spp'
AND kd_skpd = '$kd_skpd'
)z
)w
)w
";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result = array(
                        'idx'        => $ii,
                        'hasil' => $resulte['hasil']
              
                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }

    function preview_cetakan_val_spm(){

$cetak = $this->uri->segment(3);

        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $tanggal = $this->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
     
        
        $cRet='';
       $Xret1 = '';
       $Xret1.="";

       $Xret2 = '';
       $Xret3 = ''; 
       
       $Xret2.="<table style=\"border-collapse:collapse;font-size:14px;border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;\" width=\"100%\" border=\"0\">
                    ";
       $Xret3.= " 
                 </table>";       
        
        
        $font = 11;
        $font1 = $font - 1;
        
        $cRet .= "<table style=\"border-collapse:collapse;font-size:25px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td width=\"100%\" align=\"center\"><b>DAFTAR ANTRIAN SPM</b></td>
                        
                    </tr>
                    <tr>
                        <td width=\"100%\" align=\"center\"><b>YANG BELUM DIBUAT SP2D</b></td>
                    </tr>
                     <tr >
                        <td width=\"100%\" align=\"center\"><b>&nbsp;</b></td>
                    </tr>
                    </table>

        <table style=\"border-collapse:collapse;vertical-align:top;font-size:12 px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">

                     <thead >                       
                        <tr>
              <td bgcolor=\"#A9A9A9\" width=\"4%\" align=\"center \"><b>No</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"10%\" align=\"center \"><b>KODE SKPD</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"30%\" align=\"center\"><b>NAMA SKPD</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"12%\" align=\"center\"><b>NO SPP</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"12%\" align=\"center\"><b>NO SPM</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"10%\" align=\"center\"><b>TGL SPM</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"10%\" align=\"center\"><b>TGL TERIMA</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"10%\" align=\"center\"><b>TGL SETUJU</b></td>
                         </tr>
                     </thead>
                     
                   
                        ";
       

                $sql1="SELECT b.kd_skpd,b.nm_skpd,b.no_spp,b.no_spm,B.tgl_spm,isnull(a.tgl_terima,'') as tgl_terima,
                       isnull(a.tgl_setuju,'') as tgl_setuju
                       FROM
                       config_valspm a right join trhspm b on a.no_spm=b.no_spm left join trhsp2d c on b.no_spm=c.no_spm
                       where b.no_spm not in (select no_spm from trhsp2d) and b.jns_spp in ('1','2','3','4','5','6','7')
                       order by b.kd_skpd,b.urut
                        ";
  
$query = $this->db->query($sql1);
        $ii =0;
                                 
                foreach ($query->result() as $row)
                {
                    $ii++; 
                    $kd_skpd=rtrim($row->kd_skpd);
                    $nm_skpd=rtrim($row->nm_skpd);
                    $no_spp=rtrim($row->no_spp);
                    $no_spm=rtrim($row->no_spm);
                    $tgl_spmx=rtrim($row->tgl_spm);
                    $tgl_spm=$this->tanggal_format_indonesia($tgl_spmx);
                    $tgl_terimax=rtrim($row->tgl_terima);
                    if($tgl_terimax=='1900-01-01'){
                      $tgl_terima='-';
                    }else{
                      $tgl_terima=$this->tanggal_format_indonesia($tgl_terimax);;
                    }
                    $tgl_setujux=rtrim($row->tgl_setuju);
                    if($tgl_setujux=='1900-01-01'){
                      $tgl_setuju='-';
                    }else{
                      $tgl_setuju=$this->tanggal_format_indonesia($tgl_setujux);;
                    }

                      $cRet    .= " <tr>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$ii</td> 
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$kd_skpd</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >$nm_skpd</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$no_spp</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$no_spm</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$tgl_spm</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$tgl_terima</td> 
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$tgl_setuju</td> 
                                    </tr> 
                                   
                                    ";
    
                }

 
        $cRet .="</table>";
 
        $data['prev']= $cRet;    
        //$this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        switch($cetak) {
        case 0;
               echo ("<title>Lap Perbandingan Anggaran</title>");
                echo($cRet);
  
 //           $this->template->load('template','anggaran/rka/perkadaII',$data);
        break;
        case 1;
             $this->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= cek_anggaran.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        
        }    
    }

	function load_perbaikan_spm($sskpd='',$jns_ang=''){
        $kriteria = '';
        $kriteria = $this->input->post('kriteria_init');
        $jns_angx=$jns_ang;
        $query1 = $this->db->query("SELECT a.urut,a.nm_skpd,b.jns_spp,a.no_spp,a.no_spm,a.kd_skpd,a.nm_skpd,a.keperluan,
                                    a.bank,a.nmrekan,a.no_rek,a.npwp,b.pimpinan,a.status,c.no_bukti,c.ket_bast
                                    from trhspm a inner join trhspp b on a.no_spp=b.no_spp left join trhtagih c on b.no_tagih=c.no_bukti
                                    where a.kd_skpd='$sskpd' and b.jns_spp IN ('$jns_angx') and a.status ='0'
                                    AND (upper(a.no_spm) like upper('%%')) order by urut"); 

        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],    
                        'no_spm' => $resulte['no_spm'],
                        'no_spp' => $resulte['no_spp'],
                        'keperluan' => $resulte['keperluan'],
                        'nmrekan' => $resulte['nmrekan'],
                        'no_rek' => $resulte['no_rek'],
                        'npwp' => $resulte['npwp'],
                        'bank' => $resulte['bank'],
                        'no_bukti' => $resulte['no_bukti'],
                        'ket_bast' => $resulte['ket_bast'],
                        'pimpinan' => $resulte['pimpinan']                  
                        );
                        $ii++;
        }
		echo json_encode($result);   

    }		


	function update_data_spm(){
	      $sppedit = $this->input->post('sppedit');
	      $spmedit = $this->input->post('spmedit');
	      $no_tagihedit = $this->input->post('no_tagihedit');
	      $bankedit = $this->input->post('bnkedt');
	      $pimpinanedit = $this->input->post('pimpinanedit');
	      $nmrekanedit = $this->input->post('rekanedit');
	      $kepedit = $this->input->post('kepedit');
	      $norekedit =$this->input->post('norekedit');
	      $npwpedit =$this->input->post('npwpedit');
	      $ketbastedit = $this->input->post('ketbastedit');
	            
	            $sql = "UPDATE trhtagih set ket_bast ='$ketbastedit' where no_bukti ='$no_tagihedit'";
	            $asg = $this->db->query($sql);

	            $sql2 = "UPDATE trhspm set bank ='$bankedit', nmrekan= '$nmrekanedit',keperluan='$kepedit',no_rek ='$norekedit',npwp='$npwpedit' where no_spm ='$spmedit' and no_spp ='$sppedit'";
	            $asg2 = $this->db->query($sql2);
	            
	            $sql3 = "UPDATE trhspp set bank ='$bankedit', nmrekan= '$nmrekanedit',pimpinan= '$pimpinanedit',keperluan='$kepedit',no_rek ='$norekedit',npwp='$npwpedit' where no_spp ='$sppedit'";
	            $asg3 = $this->db->query($sql3);

	      if($asg3){
	                echo '1';
	            }else{
	                echo '0';
	            }   

	} 





}