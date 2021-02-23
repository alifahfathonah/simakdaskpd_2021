 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cmsc_trmpot extends CI_Controller {

    function __construct(){    
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }

    function trmpot(){
        $data['page_title']= 'P O T O N G A N';
        $this->template->set('title', 'PENERIMAAN POTONGAN');   
        $this->template->load('template','tukd/cms/trmpot_cmsbank',$data) ; 
    }

    function upload(){
        $data['page_title']= 'DAFTAR TRANSAKSI NON TUNAI';
        $this->template->set('title', 'DAFTAR TRANSAKSI NON TUNAI');   
        $this->template->load('template','tukd/cms/upload_cms',$data) ; 
    }
  	function load_pot_in(){
	
        $kd_skpd     = $this->session->userdata('kdskpd');
		$kd_user     = $this->session->userdata('pcNama');
		
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtrmpot_cmsbank where kd_skpd='$kd_skpd' and username='$kd_user' $where " ;

        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        
		$sql = "SELECT top $rows a.*,(select status_upload from trhtransout_cmsbank where no_voucher=a.no_voucher and kd_skpd='$kd_skpd' and username='$kd_user') as status_uploadx from trhtrmpot_cmsbank a where a.kd_skpd='$kd_skpd' AND a.username='$kd_user' AND a.no_bukti not in (SELECT top $offset no_bukti FROM trhtrmpot_cmsbank where kd_skpd='$kd_skpd' and username='$kd_user' 
		order by CAST(no_bukti AS INT)) $where order by CAST(a.no_bukti AS INT),a.kd_skpd";


		
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_voucher' => $resulte['no_voucher'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],        
                        'ket' => $resulte['ket'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'nilai' => $resulte['nilai'],
                        'kd_giat' => $resulte['kd_sub_kegiatan'],
                        'nm_giat' => $resulte['nm_sub_kegiatan'],
                        'kd_rek' => $resulte['kd_rek6'],
                        'nm_rek' => $resulte['nm_rek6'],
                        'rekanan' => $resulte['nmrekan'],
                        'dir' => $resulte['pimpinan'],
                        'alamat' => $resulte['alamat'],
                        'npwp' => $resulte['npwp'],
                        'jns_beban' => $resulte['jns_spp'],
                        'status' => $resulte['status'],     
                        'status_upload' => $resulte['status_uploadx']                                                                                      
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    } 
 
    function load_trans_trmpot(){
	   $kode    = $this->session->userdata('kdskpd');
	   $id      = $this->session->userdata('pcNama');
       
                 
       $sql = "SELECT DISTINCT a.no_tgl,a.no_voucher,a.tgl_voucher,b.no_sp2d,b.kd_sub_kegiatan,b.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.jns_spp,a.total 
            FROM trhtransout_cmsbank a
            JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd and a.username=b.username
            WHERE a.kd_skpd = '$kode' and a.no_voucher not in (select no_voucher from trhtrmpot_cmsbank a where a.kd_skpd = '$kode' and a.username='$id') 
            and a.status_upload not in ('1') and a.jns_spp in ('1','3') and a.username='$id'
            order by a.tgl_voucher,a.no_voucher";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,  
                        'no_tgl' => $resulte['no_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                        'kd_rek5' => $resulte['kd_rek6'],
                        'nm_rek5' => $resulte['nm_rek6'],
                        'jns_spp' => $resulte['jns_spp'],
                        'total' => number_format($resulte['total'],2)                              
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }

   function config_npwp(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT top 1 npwp,rekening FROM ms_skpd a WHERE left(a.kd_skpd,22) = left('$skpd',22)"; 
        $query1 = $this->db->query($sql);  		
		$result = array();
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(                                
                        'npwp' => $resulte['npwp'],
						'rekening' => $resulte['rekening']
                        );
                        
        }
        echo json_encode($result); 	
		
    }

    function cari_rekening_tujuan($jenis=''){				
	    $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $inskpd = substr($skpd,0,7);
        if($jenis==1){
            $jenis = "('1','2')";
        }else{
            $jenis = "('3')";
        }
       
        
        $sql = "SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,
        a.keterangan,a.kd_skpd,a.jenis FROM ms_rekening_bank a where a.jenis in $jenis and a.kd_skpd='$skpd' AND (UPPER(a.rekening) LIKE UPPER('%$lccr%') OR UPPER(a.nm_rekening) LIKE UPPER('%$lccr%'))
         order by a.nm_rekening";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rekening' => $resulte['rekening'],     
                        'nm_rekening' => $resulte['nm_rekening'],
                        'bank' => $resulte['bank'],     
                        'nmbank' => $resulte['nmbank'],     
                        'kd_skpd' => $resulte['kd_skpd'],
                        'jenis' => $resulte['jenis'],
                        'ket' => $resulte['keterangan']
                        );                        
        }
           
        echo json_encode($result);    	
	}

    function simpan_potongan(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomorvou = $this->input->post('novoucher');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $ket      = $this->input->post('ket');
        $total    = $this->input->post('total'); 
		$beban    = $this->input->post('beban');
        $npwp     = $this->input->post('npwp');      
        $kdrekbank= $this->input->post('kdbank');
        $nmrekbank= $this->input->post('nmbank');        
        $csql     = $this->input->post('sql');            
        $no_sp2d     = $this->input->post('no_sp2d');            
        $kd_giat     = $this->input->post('kd_giat');            
        $nm_giat     = $this->input->post('nm_giat');            
        $kd_rek     = $this->input->post('kd_rek');            
        $nm_rek     = $this->input->post('nm_rek');            
        $rekanan     = $this->input->post('rekanan');            
        $dir     = $this->input->post('dir');            
        $alamat     = $this->input->post('alamat');            
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
		$csqljur    = $this->input->post('sqljur');            
        $giatt      = "";
        $update     = date('Y-m-d H:i:s');      
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtrmpot_cmsbank') {
			$sql = "delete from trhtrmpot_cmsbank where kd_skpd='$skpd' and no_bukti='$nomor' and username='$usernm'";
			$asg = $this->db->query($sql);	            

            if ($asg){
                
				$sql = "insert into trhtrmpot_cmsbank(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6,nm_rek6,nmrekan, pimpinan,alamat,no_voucher,rekening_tujuan,nm_rekening_tujuan,status_upload) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$nomorvou','$kdrekbank','$nmrekbank','0')";
                $asg = $this->db->query($sql);
				
				$sql = "update trhtransout_cmsbank set status_trmpot = '1' where kd_skpd='$skpd' and no_voucher='$nomorvou' and username='$usernm'";
			    $asg = $this->db->query($sql);
            
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }elseif($tabel == 'trdtrmpot_cmsbank') {		 
            
            // Simpan Detail //                       
                $sql = "delete from trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$skpd' and username='$usernm'";
                $asg = $this->db->query($sql);
						
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtrmpot_cmsbank(no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans,ebilling,username)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }

    function load_trm_pot(){
		$skpd = $this->session->userdata('kdskpd');
		$bukti = $this->input->post('bukti');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdtrmpot_cmsbank where no_bukti='$bukti' AND kd_skpd='$skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['rektotal'],"2",",","."),
                        'rektotal1' => $resulte['rektotal']                       
                        );
                        $ii++;
        }
           
           //return $result;
		   echo json_encode($result);
           $query1->free_result();	
	}

   	function trdtrmpot_list() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('nomor');
		
        $sql = "SELECT * FROM trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd ='$kd_skpd' order by kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,   
                        'kd_rek_trans' => $resulte['kd_rek_trans'],  
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],  
                        'ebill' => $resulte['ebilling'],
                        //'nilai' => $resulte['nilai']
						'nilai' => number_format($resulte['nilai'],2,'.',',')
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	 //$query1->free_result();   
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
     $query1->free_result();	   
	} 

    function hapus_trmpot(){
        $nomor = $this->input->post('no');
        $nomorvo = $this->input->post('novoucher');
		$kd_skpd  = $this->session->userdata('kdskpd');
		$kd_user  = $this->session->userdata('pcNama');
        
        $sql = "update trhtransout_cmsbank set status_trmpot = '0' where no_voucher='$nomorvo' and kd_skpd='$kd_skpd' and username='$kd_user'";
        $asg = $this->db->query($sql);
        
        if($asg){
        $msg = array();
        $sql = "delete from trhtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$kd_skpd' and username='$kd_user'";
        $asg = $this->db->query($sql);

		$sql = "delete from trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$kd_skpd' and username='$kd_user'";
        $asg = $this->db->query($sql);
        
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        }
    }


function perusahaan() {                 
        $lccr = $this->input->post('q');
		$kd_skpd  = $this->session->userdata('kdskpd');    
		$kd_skpdd = substr($kd_skpd,0,7);	
        $sql = "
                SELECT z.* FROM (
                SELECT nama as nmrekan, pimpinan, npwp, alamat FROM ms_perusahaan WHERE left(kd_skpd,7) = '$kd_skpdd'   
					AND UPPER(nama) LIKE UPPER('%$lccr%')
					GROUP BY nama, pimpinan, npwp, alamat
				UNION ALL		
				SELECT nmrekan, pimpinan, npwp, alamat FROM trhspp WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
					AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
					GROUP BY nmrekan, pimpinan, npwp, alamat
				UNION ALL
				SELECT nmrekan, pimpinan, npwp, alamat FROM trhtrmpot WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
					AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
					GROUP BY nmrekan, pimpinan, npwp, alamat
               )z GROUP BY z.nmrekan, z.pimpinan, z.npwp, z.alamat
                ORDER BY z.nmrekan, z.pimpinan, z.npwp, z.alamat     
                    ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'nmrekan' => $resulte['nmrekan'],  
                        'pimpinan' => $resulte['pimpinan'],      
                        'npwp' => $resulte['npwp'],
                        'alamat' => $resulte['alamat'],
                        );
                        $ii++;
        }
        echo json_encode($result);
   }


    function load_sisa_bank_upval(){
        $kd_skpd = $this->session->userdata('kdskpd');                
       
        
            $query1 = $this->db->query("SELECT sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
      		select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar where left(kd_skpd,22)=left('$kd_skpd',22)   UNION ALL
      		select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,22)=left('$kd_skpd',22) UNION ALL                     
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where left(kd_skpd,22)=left('$kd_skpd',22) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' and left(kd_skpd,22)=left('$kd_skpd',22) union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where left(kd_skpd,22)=left('$kd_skpd',22) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') and left(a.kd_skpd,22)=left('$kd_skpd',22) UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' and left(a.kd_skpd,22)=left('$kd_skpd',22)  UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'1' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_upload='0' and status_validasi='0' and left(a.kd_skpd,22)=left('$kd_skpd',22)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,22)=left('$kd_skpd',22) union ALL
          --  SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' and left(kd_skpd,22)=left('$kd_skpd',22)  union all
      select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,22)=left('$kd_skpd',22) 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where kode='$kd_skpd')b");
        //}
                          
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'sisa' => number_format(($resulte['terima'] - $resulte['keluar']),2,'.',',')                      
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
           $query1->free_result();  
    }

    function load_listbelum_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_voucher='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
		$user = $this->session->userdata('pcNama');      
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        where a.kd_skpd='$skpd' and status_upload='0' and a.username='$user' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("
        	SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan kd_kegiatan, b.nm_sub_kegiatan nm_kegiatan ,c.bersih,a.jns_spp FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join (
        select a.no_voucher,a.kd_skpd,a.username,sum(a.nilai) bersih from trdtransout_transfercms a where a.kd_skpd='$skpd'
        group by no_voucher,kd_skpd,a.username)c on c.no_voucher=a.no_voucher and c.kd_skpd=a.kd_skpd and c.username=a.username
        where a.kd_skpd='$skpd' and status_upload='0' and a.username='$user' $and         
        group by 
a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan,b.nm_sub_kegiatan,c.bersih,a.jns_spp        
        order by cast(a.no_voucher as int),a.kd_skpd");		
        
      
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'no_tgl' => $resulte['no_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'bersih' => number_format($resulte['bersih'],2),
                        'pot' => number_format($resulte['total']-$resulte['bersih'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'jns_spp' => $resulte['jns_spp']                               
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}

    function no_urut_uploadcms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $user = $this->session->userdata('pcNama');
  
    
	$query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
	select no_upload nomor, 'Urut Upload Pengeluaran cms' ket, kd_skpd, username from trdupload_cmsbank where kd_skpd = '$kd_skpd' 
    union all
    select no_upload nomor, 'Urut Upload Setor Dana Bank cms' ket, kd_skpd, username from trhupload_cmsbank_bidang where kd_skpd = '$kd_skpd'     
    union all
    select no_upload nomor, 'Urut Upload Panjar Bank cms' ket, kd_skpd, username from trhupload_cmsbank_panjar where kd_skpd = '$kd_skpd'     
    union all
    select no_upload nomor, 'Urut Upload Penerimaan cms' ket, kd_skpd, username from trhupload_sts_cmsbank where kd_skpd = '$kd_skpd'
    ) 
    z WHERE kd_skpd = '$kd_skpd' and username='$user'");
	    $ii = 0;
        $nomor = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            $nomor = $resulte['nomor'];
                        
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $nomor,
						'user_name' => $user
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }
    
    function no_urut_uploadcmsharian(){
    $kd_skpd = $this->session->userdata('kdskpd');  
  
    
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d");
    
  
    
    $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
		select a.no_upload_tgl nomor, a.tgl_upload tanggal,'Urut Upload Pengeluaran cms' ket, a.kd_skpd from trhupload_cmsbank a		
    where a.kd_skpd = '$kd_skpd'
		union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Setor Dropping Bank cms' ket, a.kd_skpd from trdupload_cmsbank_bidang a
		left join trhupload_cmsbank_bidang b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where a.kd_skpd = '$kd_skpd'
		union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Panjar Bank cms' ket, a.kd_skpd from trdupload_cmsbank_panjar a
		left join trhupload_cmsbank_panjar b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where a.kd_skpd = '$kd_skpd'
		union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Penerimaan cms' ket, a.kd_skpd from trdupload_sts_cmsbank a
		left join trhupload_sts_cmsbank b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where a.kd_skpd = '$kd_skpd'
    ) 
    z WHERE kd_skpd = '$kd_skpd' AND tanggal='$tanggal'");
    
    
	    $ii = 0;
        $nomor = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if(strlen($resulte['nomor'])==1){
                $nomor = "00".$resulte['nomor'];    
            }else if(strlen($resulte['nomor'])==2){
                $nomor = "0".$resulte['nomor'];    
            }else if(strlen($resulte['nomor'])==3){
                $nomor = $resulte['nomor'];    
            }
                        
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $nomor
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }


    function simpan_uploadcms(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $skpd     = $this->input->post('skpd');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');      
        $urut_tgl = $this->input->post('urut_tglupload');
		$usern    = $this->session->userdata('pcNama');
        
        date_default_timezone_set('Asia/Jakarta');
        $update     = date('Y-m-d');
        $msg        = array();

	if($tabel == 'trdupload_cmsbank'){
            // Simpan Detail //                       
                $sql = "delete from trhupload_cmsbank where no_upload='$nomor' AND kd_skpd='$skpd' AND username='$usern'";
                $asg = $this->db->query($sql);
                $sql = "delete from trdupload_cmsbank where no_upload='$nomor' AND kd_skpd='$skpd' AND username='$usern'";
                $asg = $this->db->query($sql);
                
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdupload_cmsbank(no_voucher,tgl_voucher,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,no_upload_tgl,username)"; 
                    $asg = $this->db->query($sql.$csql);                    
                    
                    $sql = "insert into trhupload_cmsbank(no_upload,tgl_upload,kd_skpd,total,no_upload_tgl,username) values ('$nomor','$update','$skpd','$total','$urut_tgl','$usern')";
                    $asg = $this->db->query($sql);
                    
                    $sql = "UPDATE
                            trhtransout_cmsbank
                            SET trhtransout_cmsbank.status_upload = Table_B.status_upload,
		                         trhtransout_cmsbank.tgl_upload = Table_B.tgl_upload
                        FROM trhtransout_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp,a.username from trhupload_cmsbank a left join 
                        trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username
                        where b.kd_bp='$skpd' and a.no_upload='$nomor' and a.username='$usern') AS Table_B ON trhtransout_cmsbank.no_voucher = Table_B.no_voucher AND trhtransout_cmsbank.kd_skpd = Table_B.kd_skpd AND trhtransout_cmsbank.username = Table_B.username
                        where left(trhtransout_cmsbank.kd_skpd,22)=left('$skpd',22)
                        ";
                    $asg = $this->db->query($sql);
                       
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    } 

    function load_list_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_voucher='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
		$user = $this->session->userdata('pcNama');
		        
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username 
        where a.kd_skpd='$skpd' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan,b.nm_sub_kegiatan,c.bersih FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join (
        select a.no_voucher,a.kd_skpd,a.username,sum(a.nilai) bersih from trdtransout_transfercms a where a.kd_skpd='$skpd' and a.username='$user'
        group by no_voucher,kd_skpd,username)c on c.no_voucher=a.no_voucher and c.kd_skpd=a.kd_skpd and c.username=a.username
        where a.kd_skpd='$skpd' and a.username='$user' $and    
        group by 
        a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan,b.nm_sub_kegiatan,c.bersih     
        order by cast(a.no_voucher as int),a.kd_skpd");		
        
    
        
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'no_tgl' => $resulte['no_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'bersih' => number_format($resulte['bersih'],2),
                        'pot' => number_format($resulte['total']-$resulte['bersih'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan']
                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}

    function load_list_telahupload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
		$kriteria = $this->input->post('pcNama');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
		$user = $this->session->userdata('pcNama');

        
        $sql = "SELECT c.no_upload,count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher and a.username=b.username
        where a.kd_skpd='$skpd' and a.status_upload='1' and a.status_validasi='0' and a.username='$user' $and group by c.no_upload" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.no_upload,c.no_upload_tgl,a.username FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher and a.username=b.username
        where a.kd_skpd='$skpd' and a.status_upload='1' and a.status_validasi='0' and a.username='$user' $and 
group by 
a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.no_upload,c.no_upload_tgl,a.username       
        order by cast(c.no_upload as int),cast(a.no_voucher as int),a.kd_skpd");		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'no_tgl' => $resulte['no_tgl'],
                        'no_upload' => $resulte['no_upload'],
                        'no_upload_tgl' => $resulte['no_upload_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan']
                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}

	function simpan_bataluploadcms(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomor_up = $this->input->post('noup');        
        $skpd     = $this->input->post('skpd');        
        $update   = date('Y-m-d');
        $msg      = array();
		$usern    = $this->session->userdata('pcNama');

	if($tabel == 'trdupload_cmsbank') {
            // Simpan Detail //               
                $sql_h = "select count(*) as jum from trdupload_cmsbank where no_upload='$nomor_up' AND kd_skpd='$skpd' AND username='$usern'";
                    $asg_h = $this->db->query($sql_h)->row();
                    $inith = $asg_h->jum; 
                    
                    if($inith>1){
                        $sql = "delete from trdupload_cmsbank where no_voucher='$nomor' and no_upload='$nomor_up' AND kd_skpd='$skpd' AND username='$usern'";
                        $asg = $this->db->query($sql);
                        
                        
                        $sql = "UPDATE
                            trhupload_cmsbank
                            SET trhupload_cmsbank.total = Table_B.total		                         
                        FROM trhupload_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp,a.username,sum(b.nilai) as total from trhupload_cmsbank a left join 
                        trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username
                        where b.kd_bp='$skpd' and a.no_upload='$nomor_up' and a.username='$usern'
                        group by a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp,a.username) AS Table_B ON trhupload_cmsbank.no_upload = Table_B.no_upload AND trhupload_cmsbank.kd_skpd = Table_B.kd_skpd  AND trhupload_cmsbank.username = Table_B.username
                        where left(trhupload_cmsbank.kd_skpd,22)=left('$skpd',22)
                        ";
                        $asg = $this->db->query($sql);                        
                        
                    }else{
                        $sql = "delete from trdupload_cmsbank where no_voucher='$nomor' and no_upload='$nomor_up' AND kd_skpd='$skpd' AND username='$usern'";
                        $asg = $this->db->query($sql);
                        
                        $sql = "delete from trhupload_cmsbank where no_upload='$nomor_up' AND kd_skpd='$skpd' AND username='$usern'";
                        $asg = $this->db->query($sql);                                   
                    }                        
                
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{                                
                    $sql = "update trhtransout_cmsbank set status_upload='0', tgl_upload='' where no_voucher='$nomor' AND kd_skpd='$skpd' AND username='$usern'";
                    $asg = $this->db->query($sql);                    
                                           
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
    function csv_cmsbank($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
		$user = $this->session->userdata('pcNama');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;        
        $init_skp = substr($skpd,0,7);
        
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
            
            if($init_skp=='1.02.01'){
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
                where a.kd_skpd='$skpd' and a.no_upload='$nomor' and a.username='$user' and d.bank='05'");
            }else{
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and left(d.kd_skpd,7)=left(b.kd_bp,7)
                where a.kd_skpd='$skpd' and a.no_upload='$nomor' and a.username='$user' and d.bank='05'");
            }
            
        }else{
            $init_skpd = "left(a.kd_skpd,7)=left('$skpd',7)";
            $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
            b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank a 
            left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
            left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
            left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
            where a.kd_skpd='$skpd' and a.no_upload='$nomor' and a.username='$user' and d.bank='05'");
        }         
        
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='OB';                 
        //and a.tgl_upload='$tgl'
        
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
           	$nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            $nmrektujuan = strtoupper($resulte['nm_rekening_tujuan']);
            $rekawall = $resulte['rekening_awal'];
            $rektujuann = $resulte['rekening_tujuan'];  
                      
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($rekawall)).";".rtrim($nmrektujuan).";".str_replace(" ","",rtrim($rektujuann)).";".$nilai.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }

function csv_cmsbank_lain($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
		$user = $this->session->userdata('pcNama');
        $init_skp = substr($skpd,0,7);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
            
            if($init_skp=='1.02.01'){
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl,e.bic FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
                left join ms_bank e on e.kode=d.bank
                where a.kd_skpd='$skpd' and a.no_upload='$nomor' and a.username='$user' and d.bank<>'05'");
            }else{
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl,e.bic FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and left(d.kd_skpd,7)=left(b.kd_bp,7)
                left join ms_bank e on e.kode=d.bank
                where a.kd_skpd='$skpd' and a.no_upload='$nomor' and a.username='$user' and d.bank<>'05'");                
            }    
            
        }else{
          
            
            $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl,e.bic FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
                left join ms_bank e on e.kode=d.bank
                where a.kd_skpd='$skpd' and a.no_upload='$nomor' and a.username='$user' and d.bank<>'05'");
        }         
        
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='SKN';                 
        //and a.tgl_upload='$tgl'        
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
           	$nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            $idr = "IDR";
            $rkawal  = $resulte['rekening_awal'];
            $rwtujuan = $resulte['rekening_tujuan']; 
            $nmrektujun = strtoupper($resulte['nm_rekening_tujuan']);
            //'=""&'.
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($rkawal)).";".rtrim($nmrektujun).";".rtrim($resulte['bic']).";".str_replace(" ","",rtrim($rwtujuan)).";".$nilai.";".$idr.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }
    function load_dtransout(){ 
		$kd_skpd = $this->session->userdata('kdskpd');
		$kd_user = $this->session->userdata('pcNama');
		
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
				FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.no_voucher=b.no_voucher 
				AND a.kd_skpd=b.kd_skpd and a.username=b.username
				WHERE a.no_voucher='$nomor' AND a.kd_skpd='$skpd' and a.username='$kd_user'
				ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_voucher'    => $resulte['no_voucher'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek6'],
                        'nm_rek5'       => $resulte['nm_rek6'],
                        'nilai'         => $resulte['nilai'],
                        'nilai_nformat' => number_format($resulte['nilai'],2),
                        'sumber'        => $resulte['sumber'],
                        'lalu'          => $resulte['lalu'],
                        'sp2d'          => $resulte['sp2d'],   
                        'anggaran'      => $resulte['anggaran']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtransout_trdmpot(){        
        $kd_skpd = $this->session->userdata('kdskpd');
		$kd_user = $this->session->userdata('pcNama');
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $sql = "select a.* from trdtrmpot_cmsbank a left join trhtrmpot_cmsbank b on b.no_bukti=a.no_bukti and a.kd_skpd=b.kd_skpd and a.username=b.username where b.no_voucher='$nomor' and b.kd_skpd='$skpd' and a.username='$kd_user'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'kd_rek5'       => $resulte['kd_rek6'],
                        'nm_rek5'       => $resulte['nm_rek6'],
                        'nilai'         => $resulte['nilai'],
                        'nilai_nformat' => number_format($resulte['nilai'])                                                                                                                                                           
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtransout_transfercms(){ 
		$kd_skpd = $this->session->userdata('kdskpd');
		$kd_user = $this->session->userdata('pcNama');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,a.username,(select sum(nilai) from trdtransout_transfercms where no_voucher=b.no_voucher and kd_skpd=b.kd_skpd and username=a.username) as total
				FROM trhtransout_cmsbank a INNER JOIN trdtransout_transfercms b ON a.no_voucher=b.no_voucher and a.username=b.username
				AND a.kd_skpd=b.kd_skpd and a.username=b.username
				WHERE b.no_voucher='$nomor' AND b.kd_skpd='$skpd' AND a.username='$kd_user'
                group by b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,a.username
				";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'                => $ii,        
                        'no_voucher'        => $resulte['no_voucher'],
                        'tgl_voucher'       => $resulte['tgl_voucher'],
                        'rekening_awal'     => $resulte['rekening_awal'],
                        'nm_rekening_tujuan'=> $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan'   => $resulte['rekening_tujuan'],
                        'bank_tujuan'       => $resulte['bank_tujuan'],
                        'nilai'             => number_format($resulte['nilai'],2),
                        'total'             => number_format($resulte['total'],2),
                        'kd_skpd'           => $resulte['kd_skpd']                                                                                                                                                                             
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    function load_total_upload($tgl=''){
	   $kode    = $this->session->userdata('kdskpd');

              
            $sql = "SELECT
						SUM (b.nilai) AS total_upload
					FROM
						trhtransout_cmsbank a
					JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd
					WHERE
						left(a.kd_skpd,22) = left('$kode',22)
					AND a.status_upload = '1' AND a.tgl_upload='$tgl'";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'xtotal_upload' => number_format($resulte['total_upload'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }

     function load_hdraf_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        		
        $skpd = $this->session->userdata('kdskpd');
		$user = $this->session->userdata('pcNama');
		$cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,22)=left('$skpd',22)";
        }  
		
        $sql = "SELECT count(*) as total from trhupload_cmsbank a
        where $init_skpd and username='$user' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.* FROM trhupload_cmsbank a               
        where $init_skpd and username='$user' $and         
        order by cast(a.no_upload as int),a.kd_skpd");		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        {                         
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_upload' => $resulte['no_upload'],
                        'no_upload_tgl' => $resulte['no_upload_tgl'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2)                                 
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	} 

    function load_draf_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.no_upload='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
		$cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,22)=left('$skpd',22)";
        } 
		
        $sql = "SELECT count(*) as total from trhupload_cmsbank a 
        left join trdupload_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where $init_skpd $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT b.kd_skpd,b.no_voucher,b.tgl_voucher,a.no_upload,a.tgl_upload,a.total,b.nilai,b.status_upload,
b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,b.bank_tujuan,b.ket_tujuan,c.bersih FROM trhupload_cmsbank a 
        left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload
        left join (
        select a.no_voucher,a.kd_skpd,sum(a.nilai) bersih from trdtransout_transfercms a where $init_skpd
        group by no_voucher,kd_skpd)c on c.no_voucher=b.no_voucher and c.kd_skpd=b.kd_skpd          
        where $init_skpd $and 
        group by 
        b.kd_skpd,b.no_voucher,b.tgl_voucher,a.no_upload,a.tgl_upload,a.total,b.nilai,b.status_upload,
b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,b.bank_tujuan,b.ket_tujuan,c.bersih
        order by cast(a.no_upload as int),b.kd_skpd");		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2),
                        'viewtotal' => number_format($resulte['nilai'],2),
                        'viewbersih' => number_format($resulte['bersih'],2),
                        'viewpot' => number_format($resulte['nilai']-$resulte['bersih'],2),
                        'nilai' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],                        
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']
                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}
}