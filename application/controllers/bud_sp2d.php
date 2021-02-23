<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
REKENING MANUAL 1180101, 1110101
HARAP DIGANTI
simpan_cair()
*/

class bud_sp2d extends CI_Controller {

	function __construct(){	 
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
	} 

	function ls(){
        $data['kodesp2d']="LS"; /*6*/
        $data['kodespp']="6";
        $data['page_title']= 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D LS');   
        $this->template->load('template','tukd/sp2d/sp2d_ls',$data) ; 
    } 

    function ls_ppkd(){
        $data['kodesp2d']="LS"; /*5*/
        $data['kodespp']="5";
        $data['page_title']= 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D LS');   
        $this->template->load('template','tukd/sp2d/sp2d_ls',$data) ; 
    }  

    function gaji(){
        $data['kodesp2d']="LS-GJ"; /*4*/
        $data['kodespp']="4";
        $data['page_title']= 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D LS');   
        $this->template->load('template','tukd/sp2d/sp2d_ls',$data) ; 
    } 

    function tu(){
        $data['kodesp2d']="TU"; /*3*/
        $data['kodespp']="3";
        $data['page_title']= 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D TU');   
        $this->template->load('template','tukd/sp2d/sp2d_ls',$data) ; 
    } 

    function gu(){
        $data['kodesp2d']="GU"; /*2*/
        $data['kodespp']="2";
        $data['page_title']= 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D GU');   
        $this->template->load('template','tukd/sp2d/sp2d_ls',$data) ; 
    } 

    function up(){
        $data['kodesp2d']="UP"; /*1*/
        $data['kodespp']="1";
        $data['page_title']= 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D UP');   
        $this->template->load('template','tukd/sp2d/sp2d_ls',$data) ; 
    } 

    function pencairan(){
        $data['page_title']= 'PENCAIRAN S P 2 D';
        $this->template->set('title', 'PENCAIRAN S P 2 D');   
        $this->template->load('template','tukd/sp2d/sp2d_cair',$data) ; 
    }

    function penguji(){
        $data['page_title']= 'INPUT DAFTAR PENGUJI';
        $this->template->set('title', 'INPUT DAFTAR PENGUJI');   
        $this->template->load('template','tukd/sp2d/sp2d_penguji',$data) ; 
    }

	function no_urut_sp2d(){
    	$query1 = $this->db->query("select max(isnull(urut,0))+1 as nomor from trhsp2d");
	    $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
			$urut = $resulte['nomor'];
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $urut+1
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }

    function data_sp2d() {
		$jns = $this->uri->segment(3); 
        
        if($jns=='6'){
			$jns_spp="in ('5','6')";
		}else if($jns=='4'){
			$jns_spp="in ('4')";
		}else if($jns=='3'){
			$jns_spp="in ('3')";
		}else if($jns=='2'){
			$jns_spp="in ('2')";
		}else if($jns=='1'){
			$jns_spp="in ('1')";
		} 
        $sql = "SELECT b.kd_skpd,b.nm_skpd FROM trhsp2d b where jns_spp $jns_spp
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

    function data_spm($spp='') {
		
		$spp=$this->input->post('spp');
        $sql = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,sisa FROM trdspp WHERE no_spp='$spp' ORDER BY kd_kegiatan,kd_rek6";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                         'idx' => $ii,
                        'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                        'nmkegiatan' => $resulte['nm_sub_kegiatan'],       
                        'kdrek5' => $resulte['kd_rek6'],  
                        'nmrek5' => $resulte['nm_rek6'],  
                        'nilai1' => $resulte['nilai'],
                        'nilai' => number_format($resulte['nilai'],"2",",","."),
                        'sisa' => number_format($resulte['sisa'],"2",",","."),                        
                        'sis' => $resulte['sisa'] 
                           
                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }

	function nospm_ls2($skkpd='',$kodespp=''){
		$id  = $this->session->userdata('pcUser');        
        $lccr = $this->input->post('q');
		$tanggal=date("d");
		$bulan=date("m");
		if($bulan<10){
			$bulan = str_replace("0","",$bulan);
			$bulan = $bulan-1;
		}
		
	    $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
	     a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
	     (case when jns_beban='5' then 'BELANJA' else 'BELANJA' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd inner join config_valspm c on a.no_spm=c.no_spm 
	    where a.status = '0' and c.status = '1' AND a.jns_spp IN ('$kodespp')  
	    AND a.kd_skpd='$skkpd' and (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%')) order by a.kd_skpd,a.no_spm";
	
		$query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' => $ii,
                        'no_spm' => $resulte['no_spm'],
                        'tgl_spm' => $resulte['tgl_spm'],        
                        'no_spp' => $resulte['no_spp'],
                        'tgl_spp' => $resulte['tgl_spp'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],    
                        'jns_spp' => $resulte['jns_spp'],
                        'keperluan' => $resulte['keperluan'],
                        'bulan' => $resulte['bulan'],
                        'no_spd' => $resulte['no_spd'],
						'jns_spd' => $resulte['jns_spd'],
                        'bank' => $resulte['bank'],
                        'nmrekan' => $resulte['nmrekan'],
                        'no_rek' => $resulte['no_rek'],
                        'npwp' => $resulte['npwp']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	  
	}

    function pot() {
        $spm=$this->input->post('spm');
        $sql = "SELECT * FROM trspmpot where no_spm='$spm' order by kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],  
                        'pot' => $resulte['pot'],
                        'nilai' => $resulte['nilai']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
	}

    function load_sum_pot(){

        $spm = $this->input->post('spm');
        $query1 = $this->db->query("SELECT sum(nilai) as rektotal from trspmpot where no_spm='$spm'");  
        $result = array();
        $ii = 0;
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
           $query1->free_result();  
    }

    function simpan_sp2d(){
            $no_spp = $this->input->post('no_spp');
            $no_spm = $this->input->post('no_spm');
            $kd_gaji = $this->input->post('kd_gaji');
            $kd_skpd = $this->input->post('cskpd');
            $keperluan = $this->input->post('keperluan');
            $jns_spp = $this->input->post('jns_spp');
            $jns_beban = $this->input->post('jenis');
            $tgl_spp = $this->input->post('tgl_spp');
            $tgl_spm = $this->input->post('tgl_spm');
            $tgl_sp2d = $this->input->post('tgl_sp2d');                        
            $bulan = $this->input->post('bulan');                        
            $spd = $this->input->post('cspd');
            $bank= $this->input->post('bank');
            $nmrekan= $this->input->post('rekanan');
            $no_rek= $this->input->post('rekening');
            $npwp= $this->input->post('npwp');
            $nm_skpd= $this->input->post('nmskpd');
            $nilai= $this->input->post('nilai');
            $dir = $this->input->post('dir');
            $usernm= $this->session->userdata('pcNama');
            $sp2d_blk=$this->input->post('sp2d_blk');
            //date_default_timezone_set('Asia/Bangkok');
            $last_update=  "";
            $no_sementara = $this->input->post('no_sp2d');
            $no_urut = $this->input->post('urut_sp2d');
            
            $lcstatus_input= $this->input->post('lcstatus_input');
            $no_sp2d_tag = $this->input->post('no_sp2d_tag');
            $nomor_urut='';
            
            
            $sqql = "SELECT sum(jum) as jum from(
                     select COUNT(urut) as jum from trhsp2d where urut='$no_urut'
                     union all
                     select COUNT(no_spm) as jum from trhsp2d where no_spm='$no_spm'
                    )x";
            $asg7 = $this->db->query($sqql)->row(); 
            
            if($asg7->jum>0){
                echo '3';
            }else{
            
            $real_no_sp2d='7777';
            $lc_save='2';

           if ($lcstatus_input=='tambah'){ 
           $query2 ="INSERT into trhsp2d(no_sp2d,tgl_sp2d,no_spm,tgl_spm,no_spp,kd_skpd,nm_skpd,tgl_spp,bulan,no_spd,keperluan,username,last_update,status,jns_spp,bank,nmrekan,no_rek,npwp,nilai,status_terima,jenis_beban,kd_gaji) 
                    values('$no_sementara','$tgl_sp2d','$no_spm','$tgl_spm','$no_spp','$kd_skpd','$nm_skpd','$tgl_spp','$bulan','$spd','$keperluan','$usernm','$last_update','0','$jns_spp','$bank','$nmrekan','$no_rek','$npwp','$nilai','0','$jns_beban','$kd_gaji') ";
            } else{
    
             $query2 = " UPDATE trhsp2d SET no_sp2d='$no_sp2d_tag',tgl_sp2d='$tgl_sp2d', no_spm='$no_spm', tgl_spm='$tgl_spm', bulan='$bulan', kd_skpd='$kd_skpd',
             nm_skpd='$nm_skpd', no_spp='$no_spp', tgl_spp='$tgl_spp', no_spd='$spd', username='$usernm', status='0', last_update='$last_update',
             keperluan='$keperluan', kd_gaji='$kd_gaji', jns_spp='$jns_spp', no_rek='$no_rek', bank='$bank',nmrekan='$nmrekan',npwp='$npwp',nilai='$nilai' where no_sp2d='$no_sp2d_tag' "; 

            }           
            $asg2 = $this->db->query($query2); 
          
          $query1="UPDATE trhspm set status='1' where no_spm='$no_spm'";
          $asg3 = $this->db->query($query1);
           if($asg3){
                $lc_save='1';
            }else{
                $lc_save='2';
            }
            
    
        
        if ($lc_save=='1')
        {  

            echo json_encode($no_sementara);
        }
        else
        {
            echo '2';
            }
        }       
         
    }

    function load_sp2d_ls($kodespp='') {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $id  = $this->session->userdata('pcUser');        
        $kriteria = $this->input->post('cari');
        $where =" ";
        if ($kriteria <> ''){                               
            $where=" (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as tot from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where  jns_spp IN ('$kodespp')" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT TOP $rows *
        from (select a.* , (case when c.jns_beban='5' then 'BELANJA' else 'BELANJA' end) jns_spd FROM trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where jns_spp IN ('$kodespp')  and no_sp2d not in 
        (SELECT TOP $offset no_sp2d from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where  jns_spp IN ('$kodespp')  order by cast(urut as int)) order by cast(urut as int)";
        $query1 = $this->db->query($sql);  
        $result = array(); 
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
           if ($resulte['status_terima']=='1'){
                $s='Sudah Diterima';
            }else{
                $s='Belum Diterima';            
            }

            if ($resulte['status_bud']=='1'){
                $s_bud='Sudah Cair';
            }else{
                $s_bud='Belum Cair';            
            }

            $row[] = array(
                        'id' => $ii,
                        'no_sp2d' => $resulte['no_sp2d'],
                        'tgl_sp2d' => $resulte['tgl_sp2d'],
                        'no_spm' => $resulte['no_spm'],
                        'tgl_spm' => $resulte['tgl_spm'],        
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
                        'npwp' => $resulte['npwp'],
                        'sp2d_batal' => $resulte['sp2d_batal'],
                        'jenis_beban' => $resulte['jenis_beban'],
                        'status' =>$s,                                                                           
                        'status_bud' =>$s_bud,
                        'nokasbud' =>$resulte['no_kas_bud'],                        
                        'dkasbud' =>$resulte['tgl_kas_bud'],                            
                        'jns_spd' =>$resulte['jns_spd']                         
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result); 
    }

    function batal_sp2d() {     
        $sp2d = $this->input->post('sp2d');
        $spp = $this->input->post('no');
        $ket = $this->input->post('ket');
        $usernm= $this->session->userdata('pcNama');        
        $query = $this->db->query("UPDATE trhspp set sp2d_batal='1',ket_batal='$ket',user_batal='$usernm' where no_spp='$spp'");
        $query = $this->db->query("UPDATE trhsp2d set sp2d_batal='1' where no_sp2d='$sp2d'");

    }

    function simpan_daftar_uji(){
        $tabel    = $this->input->post('tabel');        
        $no_uji = $this->input->post('no_uji');     
        $tgl_uji = $this->input->post('tgl_uji');
        $no_blk = $this->input->post('no_blk');
        $cwaktu = date("Y-m-d H:i:s");      
        $user =  $this->session->userdata('pcNama'); 
        $lcst=$this->input->post('lcst');
        $r_nomor='2';
        
        if ($tabel == 'trhuji') {   
        $sql = "Select isnull(Max((no_urut)),0) As maks From trhuji";
        $hasil = $this->db->query($sql);
        $nomor7 = $hasil->row();
        $nomor7_urut=$nomor7->maks+1;
        $r_nomor=strval($nomor7_urut).$no_blk;
            
        $csql = "INSERT INTO trhuji (no_uji,tgl_uji,username,tgl_update,no_urut) values ('$r_nomor','$tgl_uji','$user','$cwaktu','$nomor7_urut')";
        $query1 = $this->db->query($csql);                      
                if($query1){
                    echo json_encode($r_nomor);
                }else{
                    echo '0';
                }
        }
        else if ($tabel == 'trduji') {
            $nomor_baru = $this->input->post('nomor_baru');
            $csql     = $this->input->post('sql');            
            // Simpan Detail //                       
                $sql = "delete from TRDUJI where no_uji='$nomor_baru'";
                $asg = $this->db->query($sql);
                
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into TRDUJI(no_uji,tgl_uji,no_sp2d)"; 
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

    function load_d_uji() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
    
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> ''){                               
            $where="  and (upper(a.no_uji) like upper('%$kriteria%') or a.tgl_uji like '%$kriteria%') or a.no_uji  in (select no_uji from trduji where no_sp2d like upper('%$kriteria%') ) ";            
        }

        $sql = "SELECT count(*) as tot from trhuji" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();


        $sql = "SELECT top $rows * from trhuji  a where a.no_uji not in (SELECT TOP $offset  no_uji from  
                trhuji order by tgl_uji,no_uji ) $where order by a.tgl_uji, a.no_uji ";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){    
            $row[] = array(
                        'id' => $ii,
                        'no_uji'    => $resulte['no_uji'],
                        'tgl_uji'    => $resulte['tgl_uji']
                        );
                        $ii++;
        }                                  
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
        
    }

    function select_detail_uji($vno_uji='') {

        $luji = $this->input->post('vno_uji');
        $sql = "SELECT no_uji, tgl_uji, a.no_sp2d,b.tgl_sp2d,no_spm,tgl_spm,nilai 
                FROM TRDUJI a inner join trhsp2d b on a.no_sp2d=b.no_sp2d WHERE no_uji='$luji'";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'idx'        => $ii,                        
                        'no_sp2d' => $resulte['no_sp2d'],     
                        'tgl_sp2d'     => $resulte['tgl_sp2d'],  
                        'no_spm'     => $resulte['no_spm'],                          
                        'tgl_spm'     => $resulte['tgl_spm'],                        
                        'nilai1'      => number_format($resulte['nilai'])
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    }


    function hhapusuji() {      
        $nomor = $this->input->post('no');        
        $query = $this->db->query("delete from TRHUJI where no_uji='$nomor'");
        $query = $this->db->query("delete from TRDUJI where no_uji='$nomor'");
        $query->free_result();
    }

    function edit_daftar_uji(){
        $tabel    = $this->input->post('tabel');        
        $no_uji = $this->input->post('no_uji');     
        $no_uji_hide = $this->input->post('no_uji_hide');       
        $tgl_uji = $this->input->post('tgl_uji');
        $cwaktu = date("Y-m-d H:i:s");      
        $user =  $this->session->userdata('pcNama'); 
                
        if ($tabel == 'trhuji') {
        $csql = "update trhuji set tgl_uji='$tgl_uji',username='$user',tgl_update='$cwaktu' where no_uji='$no_uji_hide'";
        $query1 = $this->db->query($csql);  
                if($query1){
                    echo json_encode($no_uji);
                }else{
                    echo '0';
                }
        }
        else if ($tabel == 'trduji') {
            $csql     = $this->input->post('sql');            
            // Simpan Detail //                       
                $sql = "delete from TRDUJI where no_uji='$no_uji_hide'";
                $asg = $this->db->query($sql);
                
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into TRDUJI(no_uji,tgl_uji,no_sp2d)"; 
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

      function sp2d_list_uji() {
       $lccr = $this->input->post('q');
        $sql   = " SELECT no_sp2d, tgl_sp2d,no_spm,tgl_spm,nilai from trhsp2d where no_sp2d not in 
                    (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji) and upper(no_sp2d) like upper('%$lccr%') ";     
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'no_sp2d' => $resulte['no_sp2d'],  
                        'tgl_sp2d' => $resulte['tgl_sp2d'],
                        'no_spm' => $resulte['no_spm'],
                        'tgl_spm' => $resulte['tgl_spm'],
                        'nilai' => $resulte['nilai']                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();       
    }


  function load_sp2d_cair() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where =" kd_skpd <> ''";
        if ($kriteria <> ''){                               
            $where=" (upper(a.no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(a.no_sp2d) as tot from trhsp2d a inner join trduji b on b.no_sp2d = a.no_sp2d where $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
         $sql = "SELECT TOP $rows a.no_sp2d,tgl_sp2d, no_spm, tgl_spm,no_spp, tgl_spp, kd_skpd,nm_skpd,jns_spp, keperluan, bulan,
                no_spd, bank, nmrekan, no_rek, npwp, no_kas, no_kas_bud, tgl_kas, tgl_kas_bud,
                nocek, status_bud,jenis_beban,no_spd FROM trhsp2d a 
                inner join trduji b on b.no_sp2d = a.no_sp2d
                where $where and  a.no_sp2d not in 
        (SELECT TOP $offset a.no_sp2d from trhsp2d a inner join trduji b on b.no_sp2d = a.no_sp2d where $where order by a.urut,kd_skpd) order by a.urut,kd_skpd";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            if ($resulte['status_bud']=='1'){
                $s='Sudah Cair';
            }else{
                $s='Belum Cair';            
            }

            $row[] = array(
                        'id' => $ii,
                        'no_sp2d' => $resulte['no_sp2d'],
                        'tgl_sp2d' => $this->tukd_model->rev_date($resulte['tgl_sp2d']),
                        'no_spm' => $resulte['no_spm'],
                        'tgl_spm' => $resulte['tgl_spm'],        
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
                        'npwp' => $resulte['npwp'],
                        'nokas' => $resulte['no_kas'],
                        'nokasbud' => $resulte['no_kas_bud'],
                        'dkas' => $resulte['tgl_kas'],
                        'dkasbud' => $resulte['tgl_kas_bud'],
                        'nocek' => $resulte['nocek'],
                        'jenis_beban' => $resulte['jenis_beban'],
                        'no_spd' => $resulte['no_spd'],
                        'status' => $s                                                                                   
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
           
    } 

    function no_urut(){
        $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
        select no_kas_bud nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas_bud)=1 and status_bud=1
        ) z 
        ");
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result = array(
                            'id' => $ii,        
                            'no_urut' => $resulte['nomor']
                            );
                            $ii++;
            }
            
            echo json_encode($result);
            $query1->free_result();   
    }

    function load_sum_spm(){

        $spp = $this->input->post('spp');
        $query1 = $this->db->query("select sum(nilai) as rekspm from trdspp where no_spp='$spp'");  
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

    function simpan_cair(){
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $cket = $this->input->post('ket');
        $beban = $this->input->post('beban');
        $usernm= $this->session->userdata('pcNama');
        $cskpd= $this->session->userdata('kdskpd');
        $last_update=  "";

        $total = str_replace(",","",$total);
        
        $nmskpd=$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd');
        
        $sqql = "SELECT COUNT(*) as jum from trhsp2d where no_kas_bud ='$nokas'";
            $asg7 = $this->db->query($sqql)->row(); 
            
            if($asg7->jum>0){
                echo '3';
            }else{
            
        
        $sql = " UPDATE trhsp2d set status_bud='1', no_kas_bud='$nokas', tgl_kas_bud='$tglkas' where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);


        $sql3 = " INSERT into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,kd_unit,total_d,total_k,tabel) 
                  values('$nokas','$tglkas','$no_sp2d','$usernm','$last_update','$cskpd','$nmskpd','$cskpd','$total','$total','0')";
        $asg3 = $this->db->query($sql3);        

        $sql = " SELECT a.no_spp,a.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6,a.nilai,b.bulan,c.no_spm,d.no_sp2d,b.sts_tagih FROM trdspp a 
                 LEFT JOIN trhspp b ON a.no_spp=b.no_spp
                 LEFT JOIN trhspm c ON c.no_spp=b.no_spp
                 LEFT JOIN trhsp2d d ON d.no_spm=c.no_spm
                 WHERE d.no_sp2d='$no_sp2d' ";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        $jum=0;
        foreach($query1->result_array() as $resulte){

            $sp2d=$no_sp2d;
            $jns=$beban;
            $skpd=$resulte['kd_skpd'];
            $giat=$resulte['kd_sub_kegiatan'];
            $rek5=$resulte['kd_rek6'];
            
            
             $rek9=$this->tukd_model->get_nama($rek5,'map_lo','ms_rek6','kd_rek6');
             $nmrek9=$this->tukd_model->get_nama($rek9,'nm_rek6','ms_rek6','kd_rek6');
            
             $rek64=$this->tukd_model->get_nama($rek5,'kd_rek64','ms_rek6','kd_rek6');
             $nmrek64=$this->tukd_model->get_nama($rek64,'nm_rek64','ms_rek6','kd_rek64');

            $nilai=$resulte['nilai'];
            $tagih=$resulte['sts_tagih'];
            
            if ($beban=='1'){
                $rek3=$rek5;            
            }else{
                $rek3=substr($rek5,0,3);
            }

            $jum=$jum+$nilai;


        } 


        if ($tagih=='1'){
                 $this->db->query("INSERT trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1180101','RK SKPD','$jum','0','D','','1','1','$cskpd') ");     
                $this->db->query("insert trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1110101','KAS DI KAS DAERAH','0','$jum','K','','2','1','$cskpd') ");
            
            
        }else{
            if (($jns=='1') or ($jns=='2') or ($jns=='3')){
                $this->db->query("INSERT trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1180101','RK SKPD','$jum','0','D','','1','1','$cskpd') ");     
                $this->db->query("INSERT trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1110101','KAS DI KAS DAERAH','0','$jum','K','','2','1','$cskpd') ");
                                  
            
            }else{
                $this->db->query("INSERT trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1180101','RK SKPD','$jum','0','D','','1','1','$cskpd') ");     
                $this->db->query("INSERT trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1110101','KAS DI KAS DAERAH','0','$jum','K','','2','1','$cskpd') ");
                
                        
            }
        }
        
        echo '1';       
       }
    }

    function batal_cair(){
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $cskpd= $this->session->userdata('kdskpd');
        $sql = " update trhsp2d set status_bud='0',no_kas_bud='',tgl_kas_bud='',nocek='' where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);
        $sql = " DELETE FROM trhju_pkd WHERE no_voucher='$nokas' AND kd_skpd='$cskpd' ";
        $asg = $this->db->query($sql);
        $sql = " DELETE FROM trdju_pkd WHERE no_voucher='$nokas' AND kd_unit='$cskpd' ";
        $asg = $this->db->query($sql);
        if (($asg>0)and($asg1>0)){ 
            echo '1';
        }
    }
    
}