<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 * select_data_taspen() rekening gaji manual. harap cek selalu
 * spd1_up() rekening uang persediaan manual. harap cek selalu
 */

class sppc extends CI_Controller
{
 
    function __construct() 
    { 
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }
    } 

    function sppup(){
        $data['page_title']= 'INPUT S P P U P';
        $this->template->set('title', 'INPUT S P P U P');   
        $this->template->load('template','tukd/spp/spp_up',$data) ; 
    }

    function spptu(){
        $data['page_title']= 'INPUT S P P';
        $this->template->set('title', 'INPUT SPP TU');   
        $this->template->load('template','tukd/spp/spp_tu',$data) ; 
    }

    function sppls(){
        $data['page_title']= 'INPUT S P P';
        $this->template->set('title', 'INPUT S P P');   
        $this->template->load('template','tukd/spp/spp_ls',$data) ; 
    }

    function sppgu(){
        $data['page_title']= 'INPUT S P P';
        $this->template->set('title', 'INPUT S P P');   
        $this->template->load('template','tukd/spp/spp_gu',$data) ; 
    }

    function select_data1($spp='') {
    $kd_skpd  = $this->session->userdata('kdskpd');
    $spp = $this->input->post('spp');
    $sql = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,sisa,no_bukti,sumber FROM trdspp WHERE no_spp='$spp' AND kd_skpd='$kd_skpd' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek6";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'idx'        => $ii,
                        'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                        'nmkegiatan' => $resulte['nm_sub_kegiatan'],       
                        'kdrek5'     => $resulte['kd_rek6'],  
                        'nmrek5'     => $resulte['nm_rek6'],  
                        'nilai1'     => number_format($resulte['nilai'],"2",".",","),
                        'nilai'      => number_format($resulte['nilai']),
                        'sisa'       => number_format($resulte['sisa']),                        
                        'sis'        => $resulte['sisa'],
                        'no_bukti'   => $resulte['no_bukti'],
                        'sumber'   => $resulte['sumber']
                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }

    function load_ttd_bud($ttd){
        $kd_skpd = $this->session->userdata('kdskpd'); 
        $kdskpd = substr($kd_skpd,0,22);
        $sql = "SELECT * FROM ms_ttd WHERE kode='$ttd'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $mas->free_result();
           
    }

    function spd1_up() {
        $result   = array();
        { 
            $result[] = array(
                        'id'       => '0',        
                        'kdrek5'   => '11103020000',
                        'nmrek5'   => 'Uang Persediaan'
                        );
        }
        echo json_encode($result);
    }   

    function select_data_tagih($no='') {

    $no_tagih = $this->input->post('no');
    $sql = "SELECT * FROM trdtagih WHERE no_bukti='$no_tagih' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'idx'        => $ii,
                        'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                        'nmkegiatan' => $resulte['nm_sub_kegiatan'],
                        'sumber'     => $resulte['sumber'],   
                        'kdrek5'     => $resulte['kd_rek'],  
                        'nmrek5'     => $resulte['nm_rek6'],  
                        'nilai1'     => number_format($resulte['nilai'],"2",".",","),
                        'nilai'      => number_format($resulte['nilai'])
                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }

  function kegiatan_spp() {
        $spd=$this->input->post('spd');
        $lccr = $this->input->post('q');
        $sql  = "SELECT DISTINCT a.kd_subkegiatan,b.nm_sub_kegiatan,a.kd_program,b.nm_program,a.nilai,b.kd_skpd as bidang FROM trdspd a INNER JOIN trskpd b ON 
                a.kd_subkegiatan=b.kd_sub_kegiatan where a.no_spd='$spd' and 
                (upper(a.kd_subkegiatan) like upper('%$lccr%') or upper(b.nm_sub_kegiatan) like upper('%$lccr%')) order by  a.kd_subkegiatan ";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                        
            $result[] = array(
                        'id' => $ii,  
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan'],  
                        'kd_program' => $resulte['kd_program'], 
                        'nm_program' => $resulte['nm_program'], 
                        'nilai_spd' => $resulte['nilai'],
                        'kdbidang' => $resulte['bidang'],
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    
    }

    function spd1_ag($jenis='',$tgl_spp='') {
        if ($jenis=='4') {
            $jenis = '5';
        }
        else {
            $jenis='5';
        }
        
        $skpd  = $this->session->userdata('kdskpd');
        if ($tgl_spp==''){
            $sql   = " SELECT no_spd, tgl_spd from trhspd where left(kd_skpd,17)=left('$skpd',17) and status='1'";
        }else{
            $sql   = " SELECT no_spd, tgl_spd from trhspd where left(kd_skpd,17)=left('$skpd',17) and tgl_spd<='$tgl_spp' and status='1' and left(jns_beban,1)=left('$jenis',1)";
        }
       
         $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $dk = $resulte['no_spd'];
            $sq = $this->db->query("select sum(nilai_final) as nilai from trdspd where no_spd='$dk'")->row();
            $sk = $sq->nilai; 
            
            $parx = $resulte['tgl_spd'];
            $cpar = explode("-",$parx);
            $tgl = $cpar[2]."-".$cpar[1]."-".$cpar[0];
            
            $result[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],  
                        'tgl_spd' => $resulte['tgl_spd'],
                        'tgl_spd2' => $tgl,
                        'nilai' => number_format($sk,2)  
                        );
                        $ii++;
        }
            
        echo json_encode($result);
     $query1->free_result();       
    }

 function load_jenis_beban($jenis='') {
    $result = array();  
    if ($jenis==3){
        $result = array(( 
                        array(
                        "id"   => 1 ,
                        "text" => " TU",
                        "selected"=>true
                        ) 
                    ) 
                );
         
     } else if($jenis==4){
        $result = array(
                       ( 
                        array(
                        "id"   => 1 ,
                        "text" => " LS Bendahara (Transfer)"
                        ) 
                       ),
                       ( 
                        array(
                        "id"   => 2 ,
                        "text" => " LS Bendahara (Non Tunai-CMS)"
                        ) 
                       ),
                       ( 
                        array( 
                      "id"   => 9 ,
                      "text" => " Gaji Pihak Ketiga"
                        ) 
                       )
                );          
     } else if($jenis==6){
        $result = array(
                    ( 
                        array( 
                      "id"   => 1 ,
                      "text" => " LS Bendahara (Non Tunai-CMS)"
                        ) 
                    ),
                    ( 
                        array( 
                      "id"   => 2 ,
                      "text" => " LS Pihak Ketiga (Tanpa Penagihan)"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 3 ,
                      "text" => " LS Pihak Ketiga (Dengan Penagihan)"
                        ) 
                    )
                    
                );          
                }
                 echo json_encode($result);
               
             }

    function load_spp() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $bidang  = $this->session->userdata('bidang');
        $user  = $this->session->userdata('pcNama');
        if($bidang=='51'){
            $bid="and username='$user'";
        }else{
            $bid="";
        }

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " AND jns_spp <> '1' AND jns_spp <> '2'  AND jns_spp <> '3' ";
        if ($kriteria <> ''){                               
            $where=" AND jns_spp <> '1' AND jns_spp <> '2'  AND jns_spp <> '3'  AND (upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as tot from trhspp WHERE kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
  
        $sql = "SELECT TOP $rows *, (select tgl_spd from trhspd WHERE no_spd=trhspp.no_spd) tgl_spd from trhspp WHERE kd_skpd = '$kd_skpd' $bid $where and no_spp not in (SELECT TOP $offset no_spp from trhspp WHERE kd_skpd = '$kd_skpd' $bid $where order by tgl_spp,no_spp) order by tgl_spp,cast(urut as int),no_spp";
    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,        
                        'urut' => $resulte['urut'],
                        'no_spp' => $resulte['no_spp'],
                        'tgl_spp' => $resulte['tgl_spp'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_sub_skpd' => $resulte['kd_sub_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],    
                        'jns_spp' => $resulte['jns_spp'],
                        'jns_beban' => $resulte['jns_beban'],
                        'keperluan' => $resulte['keperluan'],
                        'bulan' => $resulte['bulan'],
                        'no_spd' => $resulte['no_spd'],
                        'bank' => $resulte['bank'],
                        'nmrekan' => $resulte['nmrekan'],
                        'no_rek' => $resulte['no_rek'],
                        'npwp' => $resulte['npwp'],
                        'status' =>$resulte['status'],
                        'kd_kegiatan'=>$resulte['kd_sub_kegiatan'],
                        'nm_kegiatan'=>$resulte['nm_sub_kegiatan'],
                        'kd_subkegiatan'=>$resulte['kd_sub_kegiatan'],
                        'nm_subkegiatan'=>$resulte['nm_sub_kegiatan'],
                        'kd_program'=>$resulte['kd_program'],
                        'nm_program'=>$resulte['nm_program'],
                        'dir'=>$resulte['pimpinan'],
                        'no_tagih'=>$resulte['no_tagih'],
                        'tgl_tagih'=>$resulte['tgl_tagih'],
                        'alamat'=>$resulte['alamat'],
                        'lanjut'=>$resulte['lanjut'],
                        'kontrak'=>$resulte['kontrak'],
                        'tgl_mulai'=>$resulte['tgl_mulai'],
                        'tgl_spd'=>$resulte['tgl_spd'],
                        'tgl_akhir'=>$resulte['tgl_akhir'],
                        'sts_tagih'=>$resulte['sts_tagih'],
                        'tot_spp_'=>$resulte['nilai'],
                        'bidang' => $kd_skpd
                        
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
    }

    function config_npwp(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT npwp,rekening FROM ms_skpd a WHERE a.kd_skpd = '$skpd'"; 
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



    function config_spp(){
        $skpd   = $this->session->userdata('kdskpd');

        $cek=$this->db->query("SELECT count(no_spp) oke from trhspp where kd_skpd='$skpd'")->row()->oke;
        if($cek==0){
            $urutan1=1;
        }else{
             $urut=$this->db->query("SELECT top 1 no_spp from trhspp where kd_skpd='$skpd' ORDER BY urut desc")->row()->no_spp;
                    $urutan=explode("/",$urut);
                    $urutan1=$urutan[0]+1;
        }
       


   
            $result = array(                                
                        'nomor' => $urutan1
                        );
                        
        
        echo json_encode($result);  
    }


    function kegiatan_spd() {
        $skpd  = $this->session->userdata('kdskpd');
        $bidang  = $this->session->userdata('bidang');
        $spd=$this->input->post('spd');
        $lccr = $this->input->post('q');



        if($bidang=='55'){ 
            $filter="and b.kd_skpd='$skpd'"; /*JIKA BPP */
        }else {
            $filter="and left(b.kd_skpd,17)=left('$skpd',17)";
        }
    
        $sql = "SELECT  b.kd_skpd, b.nm_skpd, b.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program, a.nm_program,b.status_sub_kegiatan,
            '' as bidang FROM trdspd a inner join trskpd b
            on a.kd_subkegiatan =b.kd_sub_kegiatan 
            where a.no_spd='$spd' AND (b.status_sub_kegiatan !='0' or b.status_sub_kegiatan is null) $filter and 
            (upper(a.kd_subkegiatan) like upper('%$lccr%') or upper(b.nm_sub_kegiatan) like upper('%$lccr%')) order by  b.kd_skpd, a.kd_subkegiatan";
       
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $skpdd = $resulte['bidang'];
            if($skpdd==""){
                $skpdd = $skpd;
            }
            
            $result[] = array(
                        'id' => $ii,      
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan'],  
                        'kd_program' => $resulte['kd_program'], 
                        'nm_program' => $resulte['nm_program'],
                        'kd_skpd' => $skpd,
                        'nm_skpd' => $resulte['nm_skpd'],
                        'kdbidang' => $resulte['kd_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    
    }


    function load_rekening_sppls() {  
        
        $ckdkegi  = $this->input->post('kdkegiatan');
        $ckdrek   = $this->input->post('kdrek');
        $kd_sub_skpd   = $this->input->post('kd_sub_skpd');
        $kd_skpd  = $this->session->userdata('kdskpd');

        $cari   = $this->input->post('q');
        if ($ckdrek != '' ){
            $NotIn = " and kd_rek6 not in ($ckdrek) " ;
            $NotIn = "" ;
        } else {
            $NotIn = "" ;
        }
        
        

        $sql      = "SELECT kd_skpd, kd_rek6, nm_rek6 FROM trdrka where kd_sub_kegiatan= '$ckdkegi' 
        and left(kd_skpd,22)=left('$kd_sub_skpd',22) $NotIn  
                    and (kd_rek6 like '%$cari%' or nm_rek6 like '%$cari%')
                    order by kd_rek6 ";
        $query1   = $this->db->query($sql);  
        $result   = array();
        $ii       = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'      => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();        
    }


function jumlah_ang_spp() {  
        $ckdkegi  = $this->input->post('kegiatan');
        $ckdrek   = $this->input->post('kdrek5');
        $dckdskpd  = $this->input->post('kd_skpd');
        $cnospp   = $this->input->post('no_spp');
        $kd_sub_skpd  = $this->input->post('kd_sub_skpd');


        $query1   = $this->db->query(" SELECT SUM(nilai) as rektotal, SUM(nilai_sempurna) as rektotal_sempurna, SUM(nilai_ubah) as rektotal_ubah,
        ( SELECT SUM(nilai) FROM 
                    (select sum(a.nilai) nilai 
                    from trdspp a 
                    inner join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
                    where a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) and a.no_spp <> '$cnospp' 
                    AND b.jns_spp IN ('3','4','5','6')
                    and (b.sp2d_batal !='1' or b.sp2d_batal IS NULL) 
                    UNION ALL

                    SELECT SUM(nilai) as nilai FROM trdtagih t 
                    INNER JOIN trhtagih u 
                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                    WHERE 
                    t.kd_sub_kegiatan = '$ckdkegi'
                    AND left(u.kd_skpd,22)='$kd_sub_skpd'
                    AND t.kd_rek = '$ckdrek'
                    AND u.no_bukti 
                    NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,22)='$kd_sub_skpd')

                    UNION ALL
                    SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) AND b.jns_spp IN ('1','2')
                    
                    UNION ALL                    
                    SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) AND b.jns_spp IN ('4','6') and panjar in ('3')                    

                    UNION ALL
                    SELECT SUM(a.nilai) nilai FROM trdtransout_cmsbank a INNER JOIN trhtransout_cmsbank b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) AND b.status_validasi = '0'
                    
                    )b)
          as rektotal_spp_lalu
          FROM trdrka WHERE kd_rek6='$ckdrek' and kd_sub_kegiatan='$ckdkegi' and left(kd_skpd,22)=left('$kd_sub_skpd',22) ");   
        
        $result   = array();
        $ii       = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'             => $ii,        
                        'nilai'          => number_format($resulte['rektotal'],2,'.',','),
                        'nilai_sempurna' => number_format($resulte['rektotal_sempurna'],2,'.',','),
                        'nilai_ubah'     => number_format($resulte['rektotal_ubah'],2,'.',','),
                        'nilai_spp_lalu' => number_format($resulte['rektotal_spp_lalu'],2,'.',',')
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result(); 
    
    }

function jumlah_ang_spp_tu() {  
        $ckdkegi  = $this->input->post('kegiatan');
        $ckdrek   = $this->input->post('kdrek5');
        $dckdskpd  = $this->input->post('kd_skpd');
        $cnospp   = $this->input->post('no_spp');
        $kd_sub_skpd  = $this->input->post('kd_sub_skpd');


        $query1   = $this->db->query(" SELECT SUM(nilai) as rektotal, SUM(nilai_sempurna) as rektotal_sempurna, SUM(nilai_ubah) as rektotal_ubah,
        ( SELECT SUM(nilai) FROM 
                    (select sum(a.nilai) nilai 
                    from trdspp a 
                    inner join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
                    where a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) and a.no_spp <> '$cnospp' 
                    AND b.jns_spp IN ('3','4','5','6')
                    and (b.sp2d_batal !='1' or b.sp2d_batal IS NULL) 
                    UNION ALL

                    SELECT SUM(nilai) as nilai FROM trdtagih t 
                    INNER JOIN trhtagih u 
                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                    WHERE 
                    t.kd_sub_kegiatan = '$ckdkegi'
                    AND left(u.kd_skpd,22)='$kd_sub_skpd'
                    AND t.kd_rek = '$ckdrek'
                    AND u.no_bukti 
                    NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,22)='$kd_sub_skpd')

                    UNION ALL
                    SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) AND b.jns_spp IN ('1','2')
                    
                    UNION ALL                    
                    SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) AND b.jns_spp IN ('4','6') and panjar in ('3')                    

                    UNION ALL
                    SELECT SUM(a.nilai) nilai FROM trdtransout_cmsbank a INNER JOIN trhtransout_cmsbank b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) AND b.status_validasi = '0'
                    
                    )b)
          as rektotal_spp_lalu
          FROM trdrka WHERE kd_rek6='$ckdrek' and kd_sub_kegiatan='$ckdkegi' and left(no_trdrka,22)=left('$kd_sub_skpd',22) ");   
        
        $result   = array();
        $ii       = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'             => $ii,        
                        'nilai'          => number_format($resulte['rektotal'],2,'.',','),
                        'nilai_sempurna' => number_format($resulte['rektotal_sempurna'],2,'.',','),
                        'nilai_ubah'     => number_format($resulte['rektotal_ubah'],2,'.',','),
                        'nilai_spp_lalu' => number_format($resulte['rektotal_spp_lalu'],2,'.',',')
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result(); 
    
    }




    function load_reksumber_dana() {                      
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $kd_sub_skpd   = $this->input->post('kd_sub_skpd');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');

            $sql ="SELECT * from (
            select kd_skpd, sumber1_ubah as sumber_dana,isnull(nilai_sumber,0) as nilai,isnull(nsumber1_su,0) as nilai_sempurna,isnull(nsumber1_ubah,0) as nilai_ubah from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) 
            union ALL
            select kd_skpd, sumber1_ubah as sumber_dana,isnull(nilai_sumber2,0) as nilai,isnull(nsumber2_su,0) as nilai_sempurna,isnull(nsumber2_ubah,0) as nilai_ubah from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,22)=left('$kd_sub_skpd',22) and nsumber2_ubah <> 0
            )z ";                

    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'sumber_dana' => $resulte['sumber_dana'],  
                        'nilaidana' => $resulte['nilai'],
                        'nilaidana_semp' => $resulte['nilai_sempurna'],
                        'nilaidana_ubah' => $resulte['nilai_ubah'],
                        'kd_sub_skpd' => $resulte['kd_skpd']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }

    function total_spd() {   
        
        $ckdkegi  = $this->input->post('kegiatan');
        $ckdskpd  = $this->input->post('kd_skpd');
        $tglspd   = $this->input->post('tglspd');
        $beban   = $this->input->post('beban');
        $kd_rek5   = $this->input->post('kdrek5');
        if($beban==4){
            $query1   = $this->db->query(" SELECT  a.kd_subkegiatan, SUM(a.nilai_final) as nilai FROM trdspd a 
                INNER JOIN trhspd b ON a.no_spd=b.no_spd 
            where left(kd_skpd,17) = left('$ckdskpd',17) and b.tgl_spd <= '$tglspd' and a.kd_subkegiatan = '$ckdkegi' and b.status='1' GROUP BY a.kd_subkegiatan
            ");
              
        } else{
            $query1   = $this->db->query(" SELECT  a.kd_subkegiatan, SUM(a.nilai_final) as nilai 
                FROM trdspd a INNER JOIN trhspd b ON a.no_spd=b.no_spd 
            where left(kd_skpd,17) = left('$ckdskpd',17) and  b.tgl_spd <= '$tglspd' and a.kd_subkegiatan = '$ckdkegi' and b.status='1' GROUP BY a.kd_subkegiatan
            ");    
        }
        

        $result   = array();
        $ii       = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'             => $ii,        
                        'nilai'          => number_format($resulte['nilai'],2,'.',','),
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result(); 
    
    }

    function load_total_trans_spd(){
       $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $no_bukti    = $this->input->post('no_simpan');
 
        $sql = "SELECT sum(isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0)) total from trskpd a left join
                                    (           
                                        select c.kd_skpd, c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_sub_kegiatan, c.kd_skpd
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_skpd = d.kd_skpd
                                    left join 
                                    (
                                        
                                        select z.kd_skpd, z.kd_sub_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_skpd, f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
                                        where f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan, f.kd_skpd
                                        UNION ALL
                                        select f.kd_skpd, f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_sub_kegiatan, f.kd_skpd
                                        )z group by z.kd_sub_kegiatan,z.kd_skpd
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan and a.kd_skpd = g.kd_skpd
                                    left join 
                                    (
                                        SELECT t.kd_skpd, t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,17)=left('$kdskpd',17) )
                                        GROUP BY t.kd_sub_kegiatan, t.kd_skpd
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan and a.kd_skpd = z.kd_skpd
                                    where a.kd_sub_kegiatan='$kegiatan' and left(a.kd_skpd,17)=left('$kdskpd',17)";      
        
        
        
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'total' => number_format($resulte['total'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }


function cek_simpan_spp(){
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');
          
        if ($field2==''){
        $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' ");
        } else{
        $hasil=$this->db->query(" SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE left(kd_skpd,22) = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE left(kd_skpd,22) = '$kd_skpd')a WHERE a.nomor = '$nomor' ");     
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

    function simpan_tukd(){
        
        $tabel   = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcnotagih = $this->input->post('tagih');
        $skpd  = $this->session->userdata('kdskpd');
        $kd_sub_skpd = $this->input->post('kd_sub_skpd');
        
        $jns_spp = $this->input->post('jns_spp');
        $jns_bbn = $this->input->post('jns_beban');        
        $tglsppt = $this->input->post('tglsppt');

        $rekanan = $this->input->post('rekanan');
        $alamat = $this->input->post('alamat');
        $bank    = $this->input->post('bank');
        $pimpinan    = $this->input->post('pimpinan');
        $npwp    = $this->input->post('npwp');
        $rekening    = $this->input->post('rekening');
        $kodee    = "$rekanan + $npwp";
        $cek=$this->db->query("SELECT count(kode) tot from ms_perusahaan where kode='$kodee' and left(kd_skpd,17)=left(kd_skpd,17)")->row()->tot;
        if($cek==0){
             $sqlx = "INSERT into ms_perusahaan (kode,nama, bank, alamat, pimpinan, npwp, kd_skpd, rekening ) values
                    ('$kodee','$rekanan','$bank','$alamat','$pimpinan','$npwp','$skpd', '$rekening')
             ";
             $this->db->query($sqlx);
        }


        
        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd' ";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            echo '1';
        }else{
            $sql = "INSERT into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if($asg){
                echo '2';
            }else{
                echo '0';
            }
        }
        if($tabel=='trhspp'){
            $sql1 = " UPDATE trhtagih SET sts_tagih='1' where no_bukti='$lcnotagih' AND kd_skpd='$skpd'";
            $asg1 = $this->db->query($sql1);
            
            if($tglsppt!=''){
            if($jns_spp=='4' and $jns_bbn=='1'){
                $sql1 = "   UPDATE a set no_spp='$lcid' from ttaspen a join map_taspen b on a.KDSKPD_SIM=b.kd_skpd_sim where 
                            a.TGL_SPP='$tglsppt' AND b.kd_skpd='$skpd'";
                $asg1 = $this->db->query($sql1);
            }
            }
            
        }
        if($tabel=='trhspm'){
            $sql1 = " UPDATE trhspp SET status='1' where no_spp='$lcnotagih' AND kd_skpd='$skpd'";
            $asg1 = $this->db->query($sql1);
        }
    }

    function load_taspen() { 
        $cskpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        
        $sql = "SELECT b.kd_skpd,replace(left(b.kd_skpd,4),'-','.')+'.01.2.02.01' kd_kegiatan,a.TGL_SPP,a.KETERANGAN,a.JNSGAJI,tot=a.GAPOK+a.TJKEL+a.TJSTRUK+a.TJFUNGSI+a.TJUMUM+a.TJBERAS+a.TJPAJAK
                +a.TBULAT+a.TJASKES+a.TJKKJKM from ttaspen a join map_taspen b on a.KDSKPD_SIM=b.kd_skpd_sim  
                where b.kd_skpd='$cskpd' and a.no_spp not in (select no_spp from trhspp where kd_skpd='$cskpd')";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'tgl_spp' => $resulte['TGL_SPP'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'ket' => $resulte['KETERANGAN'],
                        'kegiatan' => $resulte['kd_kegiatan'],
                        'nila' => number_format($resulte['tot'],2,'.',','),
                        'nil' => $resulte['tot']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    } 

    function select_data_taspen() {
        $tgl='';
        $tgl = $this->input->post('tgl');
        $kdskpd = $this->input->post('skpd');
        $kdskpds = $this->input->post('skpd');
        $sql = '';
        $i = 1;
        
        do {
            switch ($i) {
                case 1: $kdrek = '510101010001'; $nil = 'GAPOK'; break;
                case 2: $kdrek = '510101020001'; $nil = 'TJKEL'; break;
                case 3: $kdrek = '510101030001'; $nil = 'TJSTRUK'; break;
                case 4: $kdrek = '510101040001'; $nil = 'TJFUNGSI'; break;
                case 5: $kdrek = '510101050001'; $nil = 'TJUMUM'; break;
                case 6: $kdrek = '510101060001'; $nil = 'TJBERAS'; break;
                case 7: $kdrek = '510101070001'; $nil = 'TJPAJAK'; break;
                case 8: $kdrek = '510101080001'; $nil = 'TBULAT'; break;
                case 9: $kdrek = '510101090001'; $nil = 'TJASKES'; break;
                case 10: $kdrek = '510101100001'; $nil = 'PJKK'; break;
                case 11: $kdrek = '510101110001'; $nil = 'PJKM'; break;
            }
            $sql .= "
                     SELECT z.* from (   
                     SELECT replace(left(b.kd_skpd,4),'-','.')+'.01.2.02.01' kd_sub_kegiatan,(select top 1 nm_sub_kegiatan from trskpd WHERE kd_sub_kegiatan=replace(left(b.kd_skpd,4),'-','.')+'.01.2.02.01' ) nm_sub_kegiatan,$kdrek [kd_rek],(select nm_rek6 from ms_rek6 where kd_rek6='$kdrek') [nm_rek6],$nil [nilai] 
                     FROM ttaspen a join map_taspen b on a.KDSKPD_SIM=b.kd_skpd_sim 
                     WHERE a.TGL_SPP='$tgl' and b.kd_skpd='$kdskpd')z where z.nilai<>0";
                
            if($i!=11){
                $sql .= " union all ";
            }                           
            $i++;
        } while ($i <= 11);
         
               
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            {   

                $result[] = array(
                            'idx'        => $ii,
                            'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                            'nmkegiatan' => $resulte['nm_sub_kegiatan'],
                            'kdrek5'     => $resulte['kd_rek'],  
                            'nmrek5'     => $resulte['nm_rek6'],  
                            'nilai1'     => number_format($resulte['nilai'],"2",".",","),
                            'nilai'      => number_format($resulte['nilai']),
                            'sumber'     => 'DAU'
                            );
                            $ii++;
            }
               
               echo json_encode($result);
         $query1->free_result();
    }

        function load_ttd_cek($ttd){
        $kd_skpd = $this->session->userdata('kdskpd'); 
        $cek = substr($kd_skpd,18,4);
        //echo $cek;
        $init = "kd_skpd = '$kd_skpd'";
        if($cek!='0000'){
            if($ttd=='BK'){
                $ttd="BPP','BK";
            }   
            
            if($ttd=='PA'){
                $init = "left(kd_skpd,22) = left('$kd_skpd',22)";
            }
        }
        
        
        //$kdskpd = substr($kd_skpd,0,22);
        $sql = "SELECT * FROM ms_ttd WHERE $init and kode in ('$ttd')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $mas->free_result();
           
    }

 function load_ttd_pa_kpa($ttd){
        $kd_skpd = $this->session->userdata('kdskpd'); 
        $cek = substr($kd_skpd,8,2);
        //echo $cek;
        $init = "kd_skpd = '$kd_skpd'";

        //$kdskpd = substr($kd_skpd,0,22);
        $sql = "SELECT * FROM ms_ttd WHERE left(kd_skpd,22) = left('$kd_skpd',22) and kode in ('pa','kpa')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $mas->free_result();
           
    }
 
    function dsimpan_ag_edit_ls()   {
        $kdskpd  = $this->session->userdata('kdskpd');  
        $no_spp = $this->input->post('no');
        $no_hide = $this->input->post('no_hide');
        $csql     = $this->input->post('sql');            
        $sql = "DELETE from trdspp where no_spp='$no_hide' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,no_spd,kd_bidang,sumber, kd_sub_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                        $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp'"; 
                                $asg = $this->db->query($sql);
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);
                            }else{
                               $msg = array('pesan'=>'1');
                                echo json_encode($msg);
                            }
                    }
                }
    }  

    function dsimpan_ag_ls()    {
        $kdskpd  = $this->session->userdata('kdskpd');  
        $no_spp = $this->input->post('no');
        $csql     = $this->input->post('sql');            
        $sql = "DELETE from trdspp where no_spp='$no_spp' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,no_spd,kd_bidang,sumber, kd_sub_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                        $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp'"; 
                                $asg = $this->db->query($sql);
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);
                            }else{
                               $msg = array('pesan'=>'1');
                                echo json_encode($msg);
                            }
                    }
                }
    }

     function hapus_spp3($spp='',$skpd='')
    {       
        $spp = $this->input->post('no');
        $skpd = $this->session->userdata('kdskpd');
        $id=str_replace('######','/',$spp);
        
        $tagih=$this->db->query("select no_tagih from trhspp where no_spp='$id' and kd_skpd='$skpd'")->row()->no_tagih;
        $query = $this->db->query("UPDATE trhtagih set sts_tagih=0  where no_bukti='$tagih' and kd_skpd='$skpd'");
        $query = $this->db->query("DELETE from trhspp where no_spp='$id' and kd_skpd='$skpd'");
        $query = $this->db->query("DELETE from trdspp where no_spp='$id' and kd_skpd='$skpd'");
        if($query){
            echo '1';
        }else{
            echo '0';
        }
    
    }

    function load_no_penagihan() {
        $cskpd = $this->session->userdata('kdskpd');
        $user = $this->session->userdata('pcNama');
        $bidang = $this->session->userdata('bidang');
        $lccr = $this->input->post('q');
        
        if($bidang=='51'){
            $filter="and kd_lokasi in (select kd_lokasi from ms_lokasi WHERE username='$user')";
        }else{
            $filter="";
        }

        $sql = "SELECT a.kd_skpd,a.no_bukti, tgl_bukti, a.ket,a.kontrak,kd_sub_kegiatan,b.sumber,SUM(b.nilai) as total 
                FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti
                WHERE a.kd_skpd='$cskpd' and a.jns_trs='1' and (upper(a.kd_skpd) like upper('%$lccr%') or  
                upper(a.no_bukti) like upper('%$lccr%')) and a.no_bukti not in
                (SELECT isnull(no_tagih,'') no_tagih from trhspp WHERE kd_skpd = '$cskpd' GROUP BY no_tagih) 
                GROUP BY a.kd_skpd, a.no_bukti,tgl_bukti,a.ket,a.kontrak,kd_sub_kegiatan,b.sumber order by a.no_bukti";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_tagih' => $resulte['no_bukti'],
                        'tgl_tagih' => $resulte['tgl_bukti'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'ket' => $resulte['ket'],
                        'sumber' => $resulte['sumber'],
                        'kegiatan' => $resulte['kd_sub_kegiatan'],
                        'kontrak' => $resulte['kontrak'],
                        'nila' => number_format($resulte['total'],2,'.',','),
                        'nil' => $resulte['total']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    } 
   function load_sum_spp(){
        $xspp = $this->input->post('spp');
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdspp where no_spp = '$xspp' AND kd_skpd='$skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'rektotal'  =>  $resulte['rektotal'],
                        'rektotal1' => $resulte['rektotal']                         
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result(); 
    }

    function simpan_tukd_spp(){
        
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
            $sql = "INSERT into $tabel $lckolom values $lcnilai";
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



    function update_tukd_spp(){
        $skpd  = $this->session->userdata('kdskpd');
        $tabel   = $this->input->post('tabel');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');
        
        if (  $lcid <> $lcid_h ) {
            
           $sql     = "SELECT $cid from $tabel where $cid='$lcid'";
           $res     = $this->db->query($sql);
           if ( $res->num_rows()>0 ) {
                echo '1';
                exit();
                die();
           } 
        }
        
        $query   = $this->input->post('st_query');
        $asg     = $this->db->query($query);
        if ( $asg > 0 ){
           echo '2';
        } else {
           echo '0';
        }
    
    }


    function dsimpan_up(){
        $no_spp      = trim($this->input->post('cno_spp'));
        $kd_skpd     = $this->input->post('cskpd');
        $kd_rek5     = $this->input->post('crek');
        $nm_rek5     = $this->input->post('nrek');
        $nilai       = $this->input->post('nilai');
        $spd       = $this->input->post('spd');
           $sql = "delete from trdspp where no_spp='$no_spp' and kd_rek6='$kd_rek5' ";
           $asg = $this->db->query($sql);
            if ($asg > 0){      
                    $query ="insert into trdspp(no_spp,kd_skpd,kd_rek6,nm_rek6,nilai,no_spd) values('$no_spp','$kd_skpd','$kd_rek5','$nm_rek5','$nilai','$spd') ";                    
                    $asg = $this->db->query($query);
                                        
            } else {
            echo '0';
            exit();
        }
        echo '1';
    }

    function load_spp_up() {
        
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="and jns_spp='1'";
        if ($kriteria <> ''){                               
            $where="where (upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by no_spp,kd_skpd";
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
                        'npwp' => $resulte['npwp'],
                        'status' =>$resulte['status']                                                                                   
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();   
    }

    function dsimpan_up_edit(){
        $no_spp      = trim($this->input->post('cno_spp'));
        $no_hide      = trim($this->input->post('no_hide'));
        $kd_skpd     = $this->input->post('cskpd');
        $kd_rek5     = $this->input->post('crek');
        $nm_rek5     = $this->input->post('nrek');
        $nilai       = $this->input->post('nilai');
           $sql = "delete from trdspp where no_spp='$no_hide' and kd_rek6='$kd_rek5' AND kd_skpd='$kd_skpd'";
           $asg = $this->db->query($sql);
            if ($asg > 0){      
                    $query ="insert into trdspp(no_spp,kd_skpd,kd_rek6,nm_rek6,nilai) values('$no_spp','$kd_skpd','$kd_rek5','$nm_rek5','$nilai') ";                    
                    $asg = $this->db->query($query);
                                        
            } else {
            echo '0';
            exit();
        }
        echo '1';
    }

    function hapus_dspp(){
           $no_spp      = $this->input->post('cnospp');
           $kd_kegiatan = $this->input->post('ckdgiat');
           $kd_rek5     = $this->input->post('ckdrek');
           $vno_bukti   = $this->input->post('cnobukti');
                        
           $sql = "delete from trdspp where no_spp='$no_spp' and kd_kegiatan='$kd_kegiatan' and kd_rek5='$kd_rek5' and no_bukti='$vno_bukti' ";
           $asg = $this->db->query($sql);
           echo '1';        
    }

function perusahaan() {                 
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');    
        $kd_skpdd = substr($kd_skpd,0,17);   
        $sql = "SELECT z.* FROM (
                SELECT nama as nmrekan, pimpinan, npwp, alamat FROM ms_perusahaan WHERE left(kd_skpd,17) = '$kd_skpdd'   
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
        foreach($query1->result_array() as $resulte){            
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
        $query1->free_result();
    }


    function cek_status_ang(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT case 
                when statu=1 and status_sempurna=1 and status_ubah=1 then 'Perubahan' 
                when statu=1 and status_sempurna=1 and status_ubah=0 then 'Penyempurnaan'
                when statu=1 and status_sempurna=0 and status_ubah=0 then 'Penyusunan'
                when statu=1 and status_sempurna=0 and status_ubah=1 then 'Penyusunan' 
                when statu=0 and status_sempurna=1 and status_ubah=1 then 'Penyusunan'
                else 'Penyusunan' end as anggaran from trhrka where kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'status_ang' => $resulte['anggaran']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();   
    }

    function load_spp_tu() {
    
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = "and jns_spp='3' ";
        if ($kriteria <> ''){                               
            $where="where (upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by urut,no_spp,kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'urut'    => $resulte['urut'],                        
                        'no_spp'    => $resulte['no_spp'],
                        'tgl_spp'   => $resulte['tgl_spp'],
                        'kd_skpd'   => $resulte['kd_skpd'],
                        'kd_sub_skpd'   => $resulte['kd_sub_skpd'],
                        'nm_skpd'   => $resulte['nm_skpd'],    
                        'jns_spp'   => $resulte['jns_spp'],
                        'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'keperluan' => $resulte['keperluan'],
                        'bulan'     => $resulte['bulan'],
                        'no_spd'    => $resulte['no_spd'],
                        'no_spd2'   => $resulte['no_spd2'],
                        'no_spd3'   => $resulte['no_spd3'],
                        'no_spd4'   => $resulte['no_spd4'],
                        'bank'      => $resulte['bank'],
                        'nmrekan'   => $resulte['nmrekan'],
                        'no_rek'    => $resulte['no_rek'],
                        'npwp'      => $resulte['npwp'],
                        'status'    =>$resulte['status'],
                        'no_bukti'  =>$resulte['no_bukti'],
                        'no_bukti2' =>$resulte['no_bukti2'],
                        'no_bukti3' =>$resulte['no_bukti3'],
                        'no_bukti4' =>$resulte['no_bukti4'],
                        'no_bukti5' =>$resulte['no_bukti5'],
                        'status' =>$resulte['status'],
                        'no_lpj' =>$resulte['no_lpj']                                                                                   
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    
    }

    function load_trskpd_ar_2() { 
        $cskpd  =  '' ;
        $cskpd  =  $this->input->post('kdskpd');
        $sql    = "SELECT kd_sub_kegiatan, nm_sub_kegiatan FROM trskpd where left(kd_skpd,22) = '$cskpd' order by kd_sub_kegiatan ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();        
    }

    function spd1() {


        $skpd  = $this->session->userdata('kdskpd');
        $cjenis = $this->input->post('jenis');
        if($cjenis='5'){
        $sql   = "select no_spd, tgl_spd from trhspd where left(kd_skpd,22)=left('$skpd',22) and status='1' and jns_beban ='5'";
        }
         else{         
            $sql   = "select no_spd, tgl_spd from trhspd where left(kd_skpd,22)=left('$skpd',22) and status='1' and jns_beban ='5'";
        }   
        //echo "$sqls";             
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){
            
            $dk = $resulte['no_spd'];
            $sq = $this->db->query("select sum(nilai) as nilai from trdspd where no_spd='$dk'")->row();
            $sk = $sq->nilai; 
            
            $parx = $resulte['tgl_spd'];
            $cpar = explode("-",$parx);
            $tgl = $cpar[2]."-".$cpar[1]."-".$cpar[0];
            
            $result[] = array(                                                                         
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],  
                        'tgl_spd' => $resulte['tgl_spd'],
                        'tgl_spd2' => $tgl,
                        'nilai' => number_format($sk,2)  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();       
    }

    function kegi() {
        $spd=$this->input->post('spd');
        $lccr = $this->input->post('q');
        $skpd=$this->session->userdata('kdskpd');
        $sql  = "SELECT DISTINCT a.kd_kegiatan,b.nm_kegiatan,a.kd_program,b.nm_program,a.nilai,b.kd_skpd as bidang FROM trdspd a INNER JOIN trskpd b ON 
                a.kd_kegiatan=b.kd_kegiatan where a.no_spd='$spd'  and b.kd_skpd='$skpd' and
                (upper(a.kd_kegiatan) like upper('%$lccr%') or upper(b.nm_kegiatan) like upper('%$lccr%')) order by  a.kd_kegiatan ";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                       
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_kegiatan'],  
                        'kd_program' => $resulte['kd_program'], 
                        'nm_program' => $resulte['nm_program'], 
                        'nilai_spd' => $resulte['nilai'],
                        'kdbidang' => $resulte['bidang'],
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    
    }

   function kegiatan_spd_tu() {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $dkd_skpd = substr($kd_skpd,0,17);
        $spd      = $this->input->post('spd');
        $tgl_spp      = $this->input->post('tgl_spp');
        $lccr     = $this->input->post('q');
            
        //$sqlproteksi = $this->db->query("select init from ms_skpd_tu where kd_skpd='$kd_skpd'")->row();
        $sqlproteksiinit = 2 ;//$sqlproteksi->init;
        
        if($sqlproteksiinit=='1'){  
            
                      $sqlcekk = "SELECT DATEDIFF(day,'$tgl_spp',GETDATE()) as selisih from ms_skpd where kd_skpd='$kd_skpd'";
                      
                      $sqlcekkc = $this->db->query($sqlcekk);
                        foreach($sqlcekkc->result_array() as $resultecek){
                            $jumlah_hari=$resultecek['selisih'];       
                        }  
                        
                        if($jumlah_hari!=0){
                                $sql  = "SELECT '' kd_sub_kegiatan, 'LPJ TU Sebelumnya belum disahkan' nm_sub_kegiatan";
                        }else{

                                $sql7 = 
                                "SELECT sum(selisih) as selisih, COUNT(no_sp2d) as jumlah FROM (
                                SELECT no_sp2d , tgl_sp2d , DATEDIFF(day,tgl_sp2d,GETDATE()) as selisih
                                FROM trhsp2d WHERE jns_spp='3' AND kd_skpd = '$kd_skpd' AND no_sp2d 
                                NOT IN (select no_sp2d FROM trhlpj WHERE kd_skpd='$kd_skpd' AND jenis='3' AND status='1'))a
                                ";  
                                
                                $query7 = $this->db->query($sql7);
                                foreach($query7->result_array() as $resulte7){
                                    $jumlah=$resulte7['jumlah'];
                                    $selisih=$resulte7['selisih'];
                                }

                                if($selisih>0){

                                       if ($jumlah>0){
                                            $sql  = "SELECT '' kd_sub_kegiatan, 'LPJ TU Sebelumnya belum disahkan' nm_sub_kegiatan";                                        } else{

                                            $cek = substr($kd_skpd,18,4);
                                            if($cek=="0000"){
                                                $sql = "SELECT DISTINCT a.kd_subkegiatan,a.nm_subkegiatan FROM trdspd a 
                                                where a.no_spd='$spd' and substring(a.kd_sub_kegiatan,6,7) = '$dkd_skpd' order by  a.kd_subkegiatan";
                                            }else{
                                                 $sql = "SELECT DISTINCT a.kd_subkegiatan,a.nm_subkegiatan FROM trdspd a 
                                                 inner join trdrka c on c.kd_subkegiatan = a.kd_subkegiatan
                                                 where a.no_spd='$spd' and c.kd_skpd = '$kd_skpd' order by  a.kd_subkegiatan";
                                            }       
                                       }                       

                                   }else{
                                        $cek = substr($kd_skpd,18,4);
                                        if($cek=="0000"){


                                            $sql = "SELECT DISTINCT a.kd_subkegiatan,a.nm_subkegiatan FROM trdspd a 
                                            where a.no_spd='$spd' and substring(a.kd_subkegiatan,6,7) = '$dkd_skpd' order by  a.kd_subkegiatan";
                                        }else{
                                         $sql = "SELECT DISTINCT a.kd_subkegiatan,a.nm_sub_kegiatan FROM trdspd a 
                                         inner join trdrka c on c.kd_sub_kegiatan = a.kd_subkegiatan
                                         where a.no_spd='$spd' and c.kd_skpd = '$kd_skpd' order by  a.kd_subkegiatan";

                                        }       
                                   }
                            }
                
                }else{
        $bidang=$this->session->userdata('bidang');            
        if($bidang=='55'){ 
            $filter="and b.kd_skpd='$kd_skpd'"; /*JIKA BPP */
        }else {
            $filter="and left(b.kd_skpd,17)=left('$kd_skpd',17)";
        }
                             $cek = substr($kd_skpd,18,4);
                             if($cek=="0000"){                    
                                $sql = "
SELECT  b.kd_skpd, b.nm_skpd, b.kd_sub_kegiatan kd_subkegiatan,b.nm_sub_kegiatan nm_subkegiatan FROM trdspd a inner join trskpd b
            on a.kd_subkegiatan =b.kd_sub_kegiatan 
            where a.no_spd='$spd' AND (b.status_sub_kegiatan !='0' or b.status_sub_kegiatan is null) $filter and 
            (upper(a.kd_subkegiatan) like upper('%$lccr%') or upper(b.nm_sub_kegiatan) like upper('%$lccr%')) order by  b.kd_skpd, a.kd_subkegiatan



                               ";
                            }else{
                               $sql = "SELECT DISTINCT c.kd_skpd, c.nm_skpd,  a.kd_subkegiatan kd_sub_kegiatan,a.nm_subkegiatan nm_sub_kegiatan FROM trdspd a 
                               inner join trdrka c on c.kd_subkegiatan = a.kd_sub_kegiatan
                               where a.no_spd='$spd' and c.kd_skpd = '$kd_skpd' order by  a.kd_subkegiatan";

                           }

                }
                
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,    
                        'kd_kegiatan' => $resulte['kd_subkegiatan'],  
                        'nm_kegiatan' => $resulte['nm_subkegiatan'],
                        'kd_skpd' => $resulte['kd_skpd'], 
                        'nm_skpd' => $resulte['nm_skpd']                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    
    }


    function load_rek_ar() {  
        
        $ckdkegi  = $this->input->post('kdkegiatan');
        $ckdrek   = $this->input->post('kdrek');
        $kd_sub_skpd   = $this->input->post('kd_sub_skpd');
        $kdskpd= $this->session->userdata('kdskpd');
        
        if (  $ckdrek != '' ){
            $NotIn = " and kd_rek6 not in ($ckdrek) " ;
        } else {
            $NotIn = " " ;
        }
    

        $sql      = "SELECT kd_rek6, nm_rek6 FROM trdrka where kd_sub_kegiatan = '$ckdkegi' and kd_skpd=left('$kd_sub_skpd',22)  $NotIn  order by kd_rek6 ";
        $query1   = $this->db->query($sql);  
        $result   = array();
        $ii       = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'      => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();        
    }

    function simpan_tukd_tu(){
        $tabel   = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $skpd  = $this->session->userdata('kdskpd');

        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd' ";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            echo '1';
        }else{
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if($asg){
                echo '2';
            }else{
                echo '0';
            }
        }
        
    }


     function dsimpan_ag()  {
        $kdskpd  = $this->session->userdata('kdskpd');  
        $no_spp = $this->input->post('no');
        $csql     = $this->input->post('sql');            
        $sql = "DELETE from trdspp where no_spp='$no_spp' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,no_spd,sumber,nm_sub_kegiatan, kd_sub_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                    }  else {
                        $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp'"; 
                                $asg = $this->db->query($sql);
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);
                            }else{
                               $msg = array('pesan'=>'1');
                                echo json_encode($msg);
                            }
                    }
                }
    }


 function dsimpan_ag_edit() {
        $kdskpd  = $this->session->userdata('kdskpd');  
        $no_spp = $this->input->post('no');
        $no_hide = $this->input->post('no_hide');
        $csql     = $this->input->post('sql');            
        $sql = "DELETE from trdspp where no_spp='$no_hide' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,no_spd,sumber,nm_sub_kegiatan,kd_sub_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                        $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp'"; 
                                $asg = $this->db->query($sql);
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);
                            }else{
                               $msg = array('pesan'=>'1');
                                echo json_encode($msg);
                            }
                    }
                }
    }















}