<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class lpj extends CI_Controller {

public $ppkd = "4.02.01";
public $ppkd1 = "4.02.01.00";
 
    function __construct()  {     
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        } 
    }

    function up(){
        $data['page_title']= 'INPUT LPJ UP';
        $this->template->set('title', 'INPUT LPJ UP');   
        $this->template->load('template','tukd/lpj/tambah_lpj_up',$data) ; 
    }
    
    
    
    function tu(){
        $data['page_title']= 'INPUT LPJ TU';
        $this->template->set('title', 'INPUT LPJ TU');   
        $this->template->load('template','tukd/lpj/tambah_lpj_tu',$data) ; 
    }   

    function config_skpd(){
        $skpd=$this->session->userdata('kdskpd');
        $skp =$this->db->query("SELECT * from ms_skpd where kd_skpd='$skpd'");
        $result=array();
        $ii = 0;
        foreach($skp->result_array() as $resulte){ 
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

    function config_up(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT SUM(a.nilai) as nilai FROM trdspp a INNER JOIN trhspp b ON a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' AND b.jns_spp='1'"; 
        $query1 = $this->db->query($sql);  
        
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'nilai_up' => $resulte['nilai']
                        );
                        $ii++;
        }
        echo json_encode($result); 
    }

    function load_lpj() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> ''){                               
            $where=" and (upper(no_lpj) like upper('%$kriteria%') or tgl_lpj like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from trhlpj WHERE  kd_skpd = '$kd_skpd' AND jenis = '1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd = '$kd_skpd') as nm_skpd FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        
        foreach($query1->result_array() as $resulte){ 
            $row[] = array(
                        'id' => $ii,
                        'kd_skpd'    => $resulte['kd_skpd'],      
                        'nm_skpd'    => $resulte['nm_skpd'],                          
                        'ket'   => $resulte['keterangan'],
                        'no_lpj'   => $resulte['no_lpj'],
                        'tgl_lpj'      => $resulte['tgl_lpj'],
                        'status'      => $resulte['status'],
                        'tgl_awal'      => $resulte['tgl_awal'],
                        'tgl_akhir'      => $resulte['tgl_akhir']
                        );
                        $ii++;
        }
           
       $result["total"] = $total->tot;
       $result["rows"] = $row; 
       $query1->free_result();   
       echo json_encode($result);
    }

    function tambah_tanggal(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT DATEADD(DAY,1,MAX(tgl_akhir)) as tanggal_awal FROM trhlpj WHERE jenis='1' AND kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);  
        
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'tgl_awal' => $resulte['tanggal_awal']
                        
                        );
                        $ii++;
        }
        echo json_encode($result);  
    }

    function select_data1_lpj($lpj='') {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT a.tgl_bukti,a.kd_skpd,a.kd_skpd as kd_bp_skpd,a.nm_skpd,a.no_bukti,b.kd_sub_kegiatan,b.kd_rek6,b.nm_rek6,b.nilai,c.no_lpj,c.tgl_lpj FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti 
                AND a.kd_skpd=b.kd_skpd INNER JOIN trlpj c ON b.no_bukti=c.no_bukti AND b.kd_skpd=c.kd_skpd WHERE no_lpj='$lpj' AND a.kd_skpd='$kd_skpd' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek6";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'idx'        => $ii,
                        'no_bukti'   => $resulte['no_bukti'],                       
                        'kdkegiatan' => $resulte['kd_sub_kegiatan'],     
                        'kdrek5'     => $resulte['kd_rek6'],  
                        'nmrek5'     => $resulte['nm_rek6'],                         
                        'nilai1'     => number_format($resulte['nilai']),
                        'tgl_bukti'  => $resulte['tgl_bukti'],
                        'kd_bp_skpd' => $resulte['kd_bp_skpd']  
                        );
                        $ii++;
        }
           
           echo json_encode($result);
    }

    function load_data_transaksi_lpj($dtgl1='',$dtgl2='',$kdskpd='') {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
        $kdskpd = $this->input->post('kdskpd');
        $skpdd = substr($kdskpd,0,7);     
                   
        $sql    = "SELECT b.tgl_bukti,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.no_bukti,a.nilai,a.kd_skpd as kd_skpd1 FROM trdtransout a inner join trhtransout b on 
                   a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd WHERE (a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_bp_skpd) FROM trlpj) AND b.panjar not in ('3','5') AND b.tgl_bukti >= '$dtgl1' and b.tgl_bukti <= '$dtgl2' and b.jns_spp='1' and left(b.kd_skpd,17)=left('$kdskpd',17) 
                   ORDER BY  b.tgl_bukti,a.kd_sub_kegiatan, a.kd_rek6, cast(a.no_bukti as int)";           
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'idx' => $ii,
                        'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                        'nmkegiatan' => $resulte['nm_sub_kegiatan'],                                 
                        'kdrek5'     => $resulte['kd_rek6'],  
                        'nmrek5'     => $resulte['nm_rek6'],  
                        'nilai1'     => number_format($resulte['nilai']),
                        'kd_bp_skpd' => $resulte['kd_skpd1'],
                        'no_bukti'   => $resulte['no_bukti'],
                        'tgl_bukti'   => $resulte['tgl_bukti']
                        );
                        $ii++;
        }
           echo json_encode($result);
    }

    function load_sum_data_transaksi_lpj($dtgl1='',$dtgl2='') {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
        $kdskpd  = $this->session->userdata('kdskpd');  
        $skpdd = substr($kdskpd,0,7);

        $sql    = "SELECT SUM(a.nilai) as jumlah FROM trdtransout a inner join trhtransout b on 
                   a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE (a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_bp_skpd) FROM trlpj) AND b.tgl_bukti >= '$dtgl1' and b.tgl_bukti <= '$dtgl2' and b.jns_spp='1' and left(b.kd_skpd,17)=left('$kdskpd',17) 
                   "; 
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'idx' => $ii,
                        'jumlah' => $resulte['jumlah']
                        );
                        $ii++;
        }
           echo json_encode($result);
           $query1->free_result();
    }

      function simpan_lpj(){
      
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $csql     = $this->input->post('sql');            
        
        $sql = "delete from trlpj where no_lpj='$nlpj' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trlpj (no_lpj,no_bukti,tgl_lpj,kd_sub_kegiatan,keterangan,kd_rek6,nm_rek6,nilai,kd_skpd,kd_bp_skpd)"; 
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
    
    function cek_simpan(){
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');        
        if ($field2==''){
        $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else{
        $hasil=$this->db->query(" select count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor' ");      
        }
        foreach ($hasil->result_array() as $row){
        $jumlah=$row['jumlah']; 
        }
        if($jumlah>0){
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        } else{
        $msg = array('pesan'=>'0');
        echo json_encode($msg);
        }
        
    }

     function simpan_hlpj(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');
        
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1')";
        $query1 = $this->db->query($csql);
                        
                if($query1){
                    echo '2';
                }else{
                    echo '0';
                }
            }

    function update_hlpj_up(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');

        $csql = "delete from trhlpj where no_lpj= '$no_simpan'  and kd_skpd='$kdskpd'";
        $query1 = $this->db->query($csql);
        $csql = "delete from trlpj where no_lpj= '$no_simpan' and kd_skpd='$kdskpd' ";
        $query1 = $this->db->query($csql);
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1')";
        $query1 = $this->db->query($csql);
                        
                if($query1){
                    echo '2';
                }else{
                    echo '0';
                }
        }

    function simpan_lpj_update(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $csql     = $this->input->post('sql');            
        
        $sql = "delete from trlpj where no_lpj='$no_simpan' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trlpj (no_lpj,no_bukti,tgl_lpj,kd_sub_kegiatan,keterangan,kd_rek6,nm_rek6,nilai,kd_skpd,kd_bp_skpd)"; 
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

    function select_data1_lpj_ag($lpj='') {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT (select d.tgl_bukti from trhtransout d left join trdtransout c on c.no_bukti=d.no_bukti and c.kd_skpd=d.kd_skpd where c.no_bukti=a.no_bukti and c.kd_skpd=a.kd_bp_skpd and c.kd_sub_kegiatan=a.kd_sub_kegiatan and c.kd_rek6=a.kd_rek6) as tgl_bukti,
         a.kd_skpd, a.no_lpj,a.no_bukti,a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai,kd_bp_skpd FROM trlpj a INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                WHERE a.no_lpj='$lpj' AND a.kd_skpd='$kd_skpd' order by tgl_bukti";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'idx'        => $ii,
                        'no_bukti'   => $resulte['no_bukti'],                       
                        'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                        'kdrek5'     => $resulte['kd_rek6'],  
                        'nmrek5'     => $resulte['nm_rek6'],        
                        'kd_bp_skpd' => $resulte['kd_bp_skpd'],                                      
                        'nilai1'      => number_format($resulte['nilai']),
                        'tgl_bukti'   => $resulte['tgl_bukti']      
                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }

    function load_giat_lpj(){
        $kode     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('lpj');
        $query1 = $this->db->query("
        SELECT a.kd_sub_kegiatan, c.nm_sub_kegiatan
        from trlpj a 
        INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trskpd c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan
        WHERE a.no_lpj = '$nomor' AND a.kd_skpd='$kode'
        GROUP BY a.kd_sub_kegiatan,c.nm_sub_kegiatan
        ORDER BY a.kd_sub_kegiatan");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],                       
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan']                      
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
    }

    function load_ttd(){
        $kode     = $this->session->userdata('kdskpd');
        $cari = $this->input->post('q');
        echo $this->master_ttd->load_bendahara_p($kode,$cari);

    }
    function load_tanda_tangan(){
        $kode     = $this->session->userdata('kdskpd');
        $cari = $this->input->post('q');
        echo $this->master_ttd->load_tanda_tangan($kode,$cari);

    }
    function load_sp2d_lpj_tu() {

        $lcskpd  = $this->session->userdata('kdskpd');
            //$lcskpd = $this->uri->segment(4);

        $sql = "SELECT no_sp2d,tgl_sp2d FROM trhsp2d WHERE jns_spp = '3' and status='1' and left(kd_skpd,22) = left('$lcskpd',22) and no_sp2d NOT IN (SELECT ISNULL(no_sp2d,'') FROM trhlpj)" ;

            //echo $sql;    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 

            $result[] = array(
                'id' => $ii,        
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_cair' => $resulte['tgl_sp2d']
            );
            $ii++;
        }

        echo json_encode($result);

    }

    function load_data_transaksi_lpj_tu() {
        $kdskpd  = $this->session->userdata('kdskpd');
        $no_sp2d  = $this->input->post('no_sp2d');      
         $cek = substr($kdskpd,8,2);
        if($cek=="00"){
           $hasil = "left(b.kd_skpd,7)=left('$kdskpd',7)"; 
        }else{
           $hasil = "b.kd_skpd='$kdskpd'"; 
        }

        $sql    = "SELECT a.kd_sub_kegiatan,a.nm_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai, a.no_bukti,a.kd_skpd as kd_skpd1 FROM trdtransout a inner join trhtransout b on 
                   a.no_bukti=b.no_bukti and a.kd_skpd = b.kd_skpd WHERE a.no_sp2d = '$no_sp2d' and $hasil 
                   ORDER BY a.no_bukti, a.kd_sub_kegiatan, a.kd_rek6"; 
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'idx' => $ii,
                        'kd_bp_skpd'   => $resulte['kd_skpd1'],
                        'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                        'nmkegiatan' => $resulte['nm_kegiatan'],       
                        'kdrek5'     => $resulte['kd_rek6'],  
                        'nmrek5'     => $resulte['nm_rek6'],  
                        'nilai1'     => number_format($resulte['nilai']),
                        'no_bukti'   => $resulte['no_bukti']
                        );
                        $ii++;
        }
           echo json_encode($result);
           $query1->free_result();
    }

    function simpan_hlpj_tu(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_sp2d = $this->input->post('tgl_sp2d');
        $sp2d = $this->input->post('sp2d');
        $cket = $this->input->post('ket');
        
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,no_sp2d,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_sp2d','$sp2d','3')";
        $query1 = $this->db->query($csql);
                        
                if($query1){
                    echo '2';
                }else{
                    echo '0';
                }
    }

     function load_lpj_tu() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> ''){                               
            $where=" and (upper(no_lpj) like upper('%$kriteria%') or tgl_lpj like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from trhlpj WHERE  kd_skpd = '$kd_skpd' AND jenis = '3' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows kd_skpd,keterangan,no_lpj,tgl_lpj,ISNULL(status,0) as status, tgl_awal,no_sp2d,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd = '$kd_skpd') as nm_skpd FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '3' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '3' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $row = array();        
        $ii = 0;
        
        foreach($query1->result_array() as $resulte){ 
            $row[] = array(
                        'id'      => $ii,
                        'kd_skpd' => $resulte['kd_skpd'],      
                        'nm_skpd' => $resulte['nm_skpd'],                          
                        'ket'     => $resulte['keterangan'],
                        'no_lpj'  => $resulte['no_lpj'],
                        'tgl_lpj' => $resulte['tgl_lpj'],
                        'status'  => $resulte['status'],
                        'tgl_sp2d'=> $resulte['tgl_awal'],
                        'sp2d'    => $resulte['no_sp2d']
                        );
                        $ii++;
        }
           
       $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

    function update_hlpj_tu(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_sp2d = $this->input->post('tgl_sp2d');
        $sp2d = $this->input->post('sp2d');
        $cket = $this->input->post('ket');
        $csql = "delete from trhlpj where no_lpj= '$no_simpan' AND kd_skpd='$kdskpd' ";
        $query1 = $this->db->query($csql);
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,no_sp2d,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_sp2d','$sp2d','3')";
        $query1 = $this->db->query($csql);
                        
                if($query1){
                    echo '2';
                }else{
                    echo '0';
                }
            }   

    function hhapuslpj() {    
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');        
        $query = $this->db->query("DELETE from trlpj where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
        $query = $this->db->query("DELETE from trhlpj where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
    }

   function load_sum_lpj(){
        $xlpj = $this->input->post('lpj');
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT SUM(a.nilai)AS jml FROM trlpj a INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                  WHERE b.no_lpj='$xlpj' AND a.kd_skpd='$skpd' ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'cjumlah'  =>  $resulte['jml']                       
                        );
                        $ii++;
        }
        echo json_encode($result);
    }



}