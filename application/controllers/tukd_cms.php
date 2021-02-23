<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Tukd_cms 
 *  
 * @package 
 * @author Boomer 
 * @copyright 2016
 * @version $Id$ 
 * @access public
 */ 
class Tukd_cms extends CI_Controller {

    function __construct() 
    {    
        parent::__construct();        
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }
    }
           
    function  tanggal_format_indonesia($tgl){
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;
        }
        
        function  ambil_bulan($tgl){
        $tanggal  = explode('-',$tgl); 
        return  $tanggal[1];
        }
            
        function  tanggal_indonesia($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = substr($tgl,5,2);
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.'-'.$bulan.'-'.$tahun;

        }
           
        function  getBulan($bln){
        switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "April";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
        case  0:
        return  "-";
        break;
        
    }
    } 
    
    function menuser(){
        $data['page_title']= 'MENU USER';
        $this->template->set('title', 'INPUT MENU USER');   
        $this->template->load('template','tukd/cms/menuser',$data) ; 
    }
    
    function config_mskpd(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where kd_skpd='4.02.02.03' order by kd_skpd,nm_skpd"; 
        $query1 = $this->db->query($sql);       
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(                                
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd']
                        );
                        
        }
        echo json_encode($result);  
        
    }
    
    function config_mskpd3(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where kd_skpd='4.08.02.08' order by kd_skpd,nm_skpd"; 
        $query1 = $this->db->query($sql);       
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(                                
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd']
                        );
                        
        }
        echo json_encode($result);  
        
    }
    
    function config_mskpd2(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd order by kd_skpd,nm_skpd"; 
        $query1 = $this->db->query($sql);       
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(                                
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd']
                        );
                        
        }
        echo json_encode($result);  
        
    }
    
    function proses_menu(){
        
        $skpd = $this->input->post('skpd');
        $skpd2 = $this->input->post('skpd2');
        
        $init = $this->db->query("select id_user from [user] where user_name='$skpd'")->row();
        $nmor_user_utm = $init->id_user;
        
        $init2 = $this->db->query("select id_user from [user] where user_name='$skpd2'")->row();
        $nmor_user_kdua = $init2->id_user;
        
        $init3 = $this->db->query("delete from otori where user_id='$nmor_user_kdua'");
        
        $init4 = $this->db->query("insert into otori 
                                   select '$nmor_user_kdua' as user_id,menu_id,akses from otori where user_id='$nmor_user_utm'");
        
        $init4 = $this->db->query("delete from ms_skpd where kd_skpd='$skpd2'");        
        
        $init4 = $this->db->query("insert into ms_skpd 
                                  select kd_skpd,kd_urusan,nm_skpd,kd_fungsi,bank,rekening,alamat,npwp,sld_awal,kodepos,nilai_kua from ms_skpd where kd_skpd='$skpd2'");
        
        /*$init4 = $this->db->query("select rekening,obskpd_inst from ms_skpd_daftarcms where kd_skpd='$skpd2'")->row();
        $user_rek = $init4->rekening; $user_ob = $init4->obskpd_inst;
        
        $init4 = $this->db->query("update ms_skpd set rekening='$user_rek', obskpd='$user_ob' where kd_skpd='$skpd2'");*/               
           
        if($init4){
            echo '1';    
        }else{
            echo '2';
        }
        
    }
    
    function proses_menu_adk(){
        
        $skpd = $this->input->post('skpd');
        $skpd2 = $this->input->post('skpd2');
        
        $init = $this->db->query("select id_user from [user] where user_name='$skpd'")->row();
        $nmor_user_utm = $init->id_user;
        
        $init2 = $this->db->query("select id_user from [user] where user_name='$skpd2'")->row();
        $nmor_user_kdua = $init2->id_user;
        
        $init3 = $this->db->query("delete from otori where user_id='$nmor_user_kdua'");
        
        $init4 = $this->db->query("insert into otori 
                                   select '$nmor_user_kdua' as user_id,menu_id,akses from otori where user_id='$nmor_user_utm'");
        
        $init4 = $this->db->query("delete from ms_skpd where kd_skpd='$skpd2'");        
        
        $init4 = $this->db->query("insert into ms_skpd 
                                  select kd_skpd,kd_urusan,nm_skpd,kd_fungsi,bank,rekening,alamat,npwp,sld_awal,kodepos,nilai_kua from ms_skpd where kd_skpd='$skpd2'");
        
        /*$init4 = $this->db->query("select rekening,obskpd_inst from ms_skpd_daftarcms where kd_skpd='$skpd2'")->row();
        $user_rek = $init4->rekening; $user_ob = $init4->obskpd_inst;
        
        $init4 = $this->db->query("update ms_skpd set rekening='$user_rek', obskpd='$user_ob' where kd_skpd='$skpd2'");                
        */
           
        if($init4){
            echo '1';    
        }else{
            echo '2';
        }
        
    }
    
    function proses_menubp(){
        
        $skpd = $this->input->post('skpd');
        
        $init = $this->db->query("select id_user from [user] where user_name='$skpd'")->row();
        $nmor_user_utm = $init->id_user;        
        
        $init4 = $this->db->query("delete from otori where user_id='$nmor_user_utm' and menu_id in ('283BE','283EA','283EB','283EC')");        
        
        $init4 = $this->db->query("insert into otori values ('$nmor_user_utm','283BE','1'),('$nmor_user_utm','283EA','1'),('$nmor_user_utm','283EB','1'),('$nmor_user_utm','283EC','1')");
        
        $init4 = $this->db->query("delete from ms_skpd where kd_skpd='$skpd'");
        
        $init4 = $this->db->query("insert into ms_skpd 
                                  select kd_skpd,kd_urusan,nm_skpd,kd_fungsi,bank,rekening,alamat,npwp,sld_awal,kodepos,nilai_kua from ms_skpd where kd_skpd='$skpd'");
        
        if($init4){
            echo '1';    
        }else{
            echo '2';
        }
        
    }
    
    
    function proses_menubp_panjar(){
        
        $skpd = $this->input->post('skpd');
        
        $init = $this->db->query("select id_user from [user] where user_name='$skpd'")->row();
        $nmor_user_utm = $init->id_user;        
        
        $init4 = $this->db->query("delete from otori where user_id='$nmor_user_utm' and menu_id in ('285G','286','286B','286C','287','287A','287B')");        
        
        $init4 = $this->db->query("insert into otori values ('$nmor_user_utm','285G','1'),('$nmor_user_utm','286','1'),('$nmor_user_utm','286B','1'),('$nmor_user_utm','286C','1'),('$nmor_user_utm','287','1'),('$nmor_user_utm','287A','1'),('$nmor_user_utm','287B','1')");
        
        if($init4){
            echo '1';    
        }else{
            echo '2';
        }
        
    }
    
    function proses_menubpp_cms(){
        
        $skpd = $this->input->post('skpd');
        
        $init = $this->db->query("select id_user from [user] where user_name='$skpd'")->row();
        $nmor_user_utm = $init->id_user;        
        
        $init4 = $this->db->query("delete from otori where user_id='$nmor_user_utm' and menu_id in ('285C','285CA','285CC','285CD','285CB','285D','285DA','285DB','285E','289B')");        
        
        $init4 = $this->db->query("insert into otori values ('$nmor_user_utm','285C','1'),('$nmor_user_utm','285CA','1'),('$nmor_user_utm','285CC','1'),('$nmor_user_utm','285CD','1'),('$nmor_user_utm','285CB','1'),('$nmor_user_utm','285D','1'),('$nmor_user_utm','285DA','1'),('$nmor_user_utm','285DB','1'),('$nmor_user_utm','285E','1'),('$nmor_user_utm','289B','1')");
        
        if($init4){
            echo '1';    
        }else{
            echo '2';
        }
        
    }
    
    function config_npwp(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT top 1 npwp,rekening FROM ms_skpd a WHERE left(a.kd_skpd,17) = left('$skpd',17)"; 
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
    
    function cari_rekening()
    {       
        $lccr =  $this->session->userdata('kdskpd');        
        $inskpd = substr($lccr,0,17);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$lccr'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            if($inskpd=='1.02.0.00.0.00.01'){
                $init_skpd = "kd_skpd='$lccr'";    
            }else{
                $init_skpd = "kd_skpd='$lccr'";  
            }
        }else{
            $init_skpd = "left(kd_skpd,17)=left('$lccr',17)";
        }
        
        $sql = "SELECT top 1 rekening FROM ms_skpd where $init_skpd order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rek_bend' => $resulte['rekening']
                        );                        
        }
        echo json_encode($result);                        
                        
    }
    
    function cari_rekening_pend()
    {       
        $lccr =  $this->session->userdata('kdskpd');
        $sql = "SELECT top 1 rekening_pend FROM ms_skpd where left(kd_skpd,17)=left('$lccr',17) order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rek_bend' => $resulte['rekening_pend']
                        );                        
        }
        echo json_encode($result);                        
                        
    }
    
    function cari_bank()
    {               
        $sql = "SELECT kode,nama FROM ms_bank";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'kode' => $resulte['kode'],     
                        'nama' => $resulte['nama']
                        );                        
        }
           
        echo json_encode($result);      
    }                                            
    
    function cari_rekening_tujuan($jenis='')
    {               
        $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $inskpd = substr($skpd,0,17);
        if($jenis==1){
            $jenis = "('1','2')";
        }else{
            $jenis = "('3')";
        }
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){            
            if($inskpd=='1.02.0.00.0.00.01'){
                $init_skpd = "a.kd_skpd='$skpd'";
            }else{
                $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
            }            
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }
        
        $sql = "SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,
        a.keterangan,a.kd_skpd,a.jenis FROM ms_rekening_bank a where a.jenis in $jenis and $init_skpd 
        AND (UPPER(a.rekening) LIKE UPPER('%$lccr%') OR UPPER(a.nm_rekening) LIKE UPPER('%$lccr%'))
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
    
    function cari_rekening_tujuan_kasda($jenis)
    {               
        $skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,a.kd_skpd,a.jenis FROM ms_rekening_bank a where a.jenis='$jenis'";
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
                        'jenis' => $resulte['jenis']
                        );                        
        }
           
        echo json_encode($result);      
    }
    
    function sts_cms()
    {
        $data['page_title']= 'INPUT S T S NON TUNAI';
        $this->template->set('title', 'INPUT S T S NON TUNAI');   
        $this->template->load('template','tukd/cms/sts_cmsbank',$data) ; 
    }
    
    function sts_cms_lalu()
    {
        $data['page_title']= 'INPUT S T S NON TUNAI';
        $this->template->set('title', 'INPUT S T S NON TUNAI');   
        $this->template->load('template','tukd/cms/sts_cmsbank_lalu',$data) ; 
    }
    
    function sts_upload_cms()
    {
        $data['page_title']= 'INPUT UPLOAD S T S';
        $this->template->set('title', 'INPUT UPLOAD S T S');   
        $this->template->load('template','tukd/cms/sts_upload',$data) ; 
    }
    
    function transout()
    {
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI'); 
        
        $kd_skpd  = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $hasil_skpd = $cek_skpd->hasil;
        $hslskpd = substr($kd_skpd,0,17);
        if($hasil_skpd==1){
            if($kd_skpd=='1.02.0.00.0.00.01.0000'){
                $this->template->load('template','tukd/cms/transout_tunai',$data); 
            }else{
                if($hslskpd=='1.02.0.00.0.00.01'){
                    $this->template->load('template','tukd/cms/transout_tunai_pusk',$data);
                }else{
                    $this->template->load('template','tukd/cms/transout_tunai',$data);    
                }                
            }            
        }else{
           $this->template->load('template','tukd/cms/transout_tunai',$data); 
        }           
    }
    
    
    function transout_bank() 
    {
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI');   
        $this->template->load('template','tukd/cms/transout_cmsbank',$data) ; 
    }
    
    function transout_pndhbank()
    {
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI');   
        $this->template->load('template','tukd/cms/transout_pindahbank',$data) ; 
    }
    
    function trmpot_pndhbank()
    {
        $data['page_title']= 'P O T O N G A N';
        $this->template->set('title', 'PENERIMAAN POTONGAN');   
        $this->template->load('template','tukd/cms/trmpot_pindahbank',$data) ;  
    }
    
    function list_transout()
    {
        $data['page_title']= 'DAFTAR TRANSAKSI NON TUNAI';
        $this->template->set('title', 'DAFTAR TRANSAKSI NON TUNAI');   
        $this->template->load('template','tukd/cms/list_upload',$data) ; 
    }
    
    function list_transoutval()
    {
        $data['page_title']= 'DAFTAR VALIDASI NON TUNAI';
        $this->template->set('title', 'DAFTAR VALIDASI NON TUNAI');   
        $this->template->load('template','tukd/cms/list_validasi',$data) ; 
    }    
    
    function trmpot()
    {
        $data['page_title']= 'P O T O N G A N';
        $this->template->set('title', 'PENERIMAAN POTONGAN');   
        $this->template->load('template','tukd/cms/trmpot_cmsbank',$data) ; 
    }
    
    function ambil_ststbp()
    {
        $data['page_title']= 'AMBIL DATA PENERIMAAN';
        $this->template->set('title', 'AMBIL DATA PENERIMAAN');   
        $this->template->load('template','tukd/cms/ambil_stspenerimaan',$data) ; 
    } 
    
    function cek_simpan_user(){
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kd_user  = $this->session->userdata('pcNama'); 
        
        if ($field2==''){
        $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' and username='$kd_user'");
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
    
    function load_transout_tunai(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }            
        }        
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
        $sql = "SELECT ISNULL(MAX(a.tgl_terima),'2018-01-01') as tgl_terima FROM trhspj_ppkd a WHERE a.cek='1' AND $init_skpd";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res)
        {
         $tgl_terima = $res['tgl_terima'];
        }
       
        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '0' AND a.pay='TUNAI' AND $init_skpd $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z 
    join trhlpj v on v.no_lpj = z.no_lpj
    where v.jenis=a.jns_spp and z.no_bukti = a.no_bukti and z.kd_bp_skpd = a.kd_skpd) ketlpj,
        (CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '0' AND a.pay='TUNAI' AND $init_skpd $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '0' AND a.pay='TUNAI' AND $init_skpd $where order by CAST (a.no_bukti as NUMERIC))  order by CAST (a.no_bukti as NUMERIC),kd_skpd ";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
        
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketlpj' => $resulte['ketlpj'],                                                                                            
                        'ketspj' => $resulte['ketspj'],                                                                                            
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_transout(){
        $kd_skpd     = $this->session->userdata('kdskpd');  
        $kd_id       = $this->session->userdata('pcNama');  
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }  
        }
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_voucher like '%$kriteria%' or upper(a.ket) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a where a.panjar = '0' AND a.username='$kd_id' AND $init_skpd $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,a.status_upload ketup,
        a.status_validasi ketval FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND a.username='$kd_id' AND $init_skpd $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND a.username='$kd_id' AND $init_skpd $where order by CAST (a.no_bukti as NUMERIC))  order by tgl_voucher,CAST (a.no_bukti as NUMERIC),kd_skpd ";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_tgl' => $resulte['no_tgl'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'no_sp2d' => $resulte['no_sp2d'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketup' => $resulte['ketup'],                                                                                            
                        'ketval' => $resulte['ketval'], 
                        'stpot' => $resulte['status_trmpot'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']                                                                                                                   
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_tgltransout(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }  
        }
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND a.tgl_voucher = '$kriteria'";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a where a.panjar = '0' AND $init_skpd $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,a.status_upload ketup,
        a.status_validasi ketval FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND $init_skpd $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND $init_skpd $where order by CAST (a.no_bukti as NUMERIC))  order by CAST (a.no_bukti as NUMERIC),kd_skpd ";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_tgl' => $resulte['no_tgl'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'no_sp2d' => $resulte['no_sp2d'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketup' => $resulte['ketup'],                                                                                            
                        'ketval' => $resulte['ketval'], 
                        'stpot' => $resulte['status_trmpot'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']                                                                                                                   
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }

        
    function pot() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $spm =$this->input->post('spm');
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
         //$query1->free_result();   
    }
    
    function load_trskpd_sub() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        
        $sskpd = substr($cskpd,0,17);
        
        $bid = $this->session->userdata('kdskpd');
        $cek = explode(".",$bid);
        if($cek=="0000"){
            $par = "left(b.kd_skpd,17)='$sskpd'";
        }else{
            $par = "b.kd_skpd='$bid'";
        }
        
        $jns_beban='';
        $cgiat = '';
        if ($jenis ==4){
            $jns_beban = "and left(a.jns_kegiatan,1)='5'";
        }
        else{
            $jns_beban = "and left(a.jns_kegiatan,1)='5'";
        }
        if ($giat !=''){                               
            $cgiat = " and a.kd_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        /*$sql = "SELECT a.kd_kegiatan,b.nm_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan
                WHERE a.kd_skpd='$cskpd' AND a.status_keg='1' $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_kegiatan) LIKE UPPER('%$lccr%'))";*/
                
                /*$sql = "SELECT a.kd_subkegiatan,a.nm_subkegiatan,a.kd_kegiatan,b.nm_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan
                WHERE $par $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_kegiatan) LIKE UPPER('%$lccr%'))";                                              
                */
       /* //sub
        $sql =" select d.kd_subkegiatan,d.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b              
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    inner join m_sub_giat d on d.kd_kegiatan = a.kd_kegiatan
    where $par $jns_beban $cgiat AND (UPPER(b.kd_subkegiatan) LIKE UPPER('%%') OR UPPER(b.nm_subkegiatan) LIKE UPPER('%%'))
    group by  d.kd_subkegiatan,d.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    order by  d.kd_subkegiatan,d.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    ";   */ 
        $sql ="SELECT a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b                 
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    where $par $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    order by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    ";
    //echo " $sql";
    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                  //      'kd_subkegiatan' => $resulte['kd_subkegiatan'],  
                    //    'nm_subkegiatan' => $resulte['nm_subkegiatan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program'],
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }
    
    function load_trskpd_sub_tunai() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        
        $sskpd = substr($cskpd,0,17);
        
        $bid = $this->session->userdata('kdskpd');
        $cek = explode(".",$bid);
        if($cek=="0000"){
            $par = "left(b.kd_skpd,17)='$sskpd'";
        }else{
            $par = "b.kd_skpd='$bid'";
        }
    

  
        $jns_beban='';
        $cgiat = '';
        if ($jenis ==4){
            $jns_beban = " and left(a.jns_kegiatan,1)='5'";
        }
        else{
            $jns_beban = " and left(a.jns_kegiatan,1)='5'";
        }
        if ($giat !=''){                               
            $cgiat = " and a.kd_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        /*$sql = "SELECT a.kd_kegiatan,b.nm_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan
                WHERE a.kd_skpd='$cskpd' AND a.status_keg='1' $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_kegiatan) LIKE UPPER('%$lccr%'))";*/
                
                /*$sql = "SELECT a.kd_subkegiatan,a.nm_subkegiatan,a.kd_kegiatan,b.nm_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan
                WHERE $par $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_kegiatan) LIKE UPPER('%$lccr%'))";                                              
                */
       /* //sub
        $sql =" select d.kd_subkegiatan,d.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b              
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    inner join m_sub_giat d on d.kd_kegiatan = a.kd_kegiatan
    where $par $jns_beban $cgiat AND (UPPER(b.kd_subkegiatan) LIKE UPPER('%%') OR UPPER(b.nm_subkegiatan) LIKE UPPER('%%'))
    group by  d.kd_subkegiatan,d.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    order by  d.kd_subkegiatan,d.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    ";   */ 



  /*      $sql ="SELECT a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b               
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    where $par $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('$lccr%%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    union all
    select a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b                
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    where $par2 $jns_beban AND a.kd_kegiatan='1.01.1.01.01.00.22.002' AND b.kd_rek5='5221104' AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    union all
    select a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b                
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    where $par2 $jns_beban AND left(a.kd_kegiatan,6)='01.005' AND $par AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    union all
    select a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b                
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    where $par2 $jns_beban AND a.kd_kegiatan='4.08.4.08.01.00.01.351' AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    order by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    
    ";*/
    







        $sql ="SELECT a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program,sum(b.nilai) as total from trdrka b                 
    inner join m_giat a on b.kd_kegiatan = a.kd_kegiatan
    inner join m_prog c on c.kd_program = a.kd_program
    where $par $jns_beban $cgiat  AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    order by  a.kd_kegiatan,a.nm_kegiatan,c.kd_program,c.nm_program
    ";











        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                  //      'kd_subkegiatan' => $resulte['kd_subkegiatan'],  
                    //    'nm_subkegiatan' => $resulte['nm_subkegiatan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program'],
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }
    
    function load_sp2d_transout(){
       //$beban='',$giat=''
       $beban   = $this->input->post('jenis');
       $giat    = $this->input->post('giat');
       $kode    = $this->input->post('kd');
       $bukti   = $this->input->post('bukti');
       $where = '';
       if ($beban=='1'){
        $sisa = "c.nilai + (SELECT SUM(ISNULL (v.nilai,0)) FROM trhspp z INNER JOIN trhspm s ON z.no_spp=s.no_spp AND z.kd_skpd=s.kd_skpd
                INNER JOIN trhsp2d v ON s.no_spm=v.no_spm AND s.kd_skpd=v.kd_skpd WHERE z.jns_spp IN ('1','2') AND z.kd_skpd=c.kd_skpd )
                -(SELECT SUM(ISNULL (nilai,0)) FROM trdtransout WHERE no_sp2d = c.no_sp2d and no_bukti <> '$bukti') AS sisa";
       }else{
        $sisa = "c.nilai -(SELECT SUM(ISNULL (nilai,0))FROM trdtransout WHERE no_sp2d = c.no_sp2d and no_bukti <> '$bukti') AS sisa";   
       }
       if (($beban != '' && $giat == '') || ($beban == '1')){
            $where = " and a.jns_spp IN ('1','2')"; 
       }
       if ($giat !='' && $beban != '1'){
            $where = " and a.jns_spp='$beban' and d.kd_kegiatan='$giat'";
       }
       
        $kriteria = $this->input->post('q');
            $sql = "SELECT DISTINCT a.no_sp2d,a.tgl_sp2d,sum(a.nilai) as nilai,
                    0 as sisa                   
                    FROM trhsp2d a 
                    INNER JOIN trdspp d ON a.no_spp=d.no_spp AND a.kd_skpd=d.kd_skpd
                    WHERE LEFT(a.kd_skpd,17) = LEFT('$kode',17) AND a.status = 1 $where 
                    GROUP BY a.no_sp2d,a.tgl_sp2d
                    ORDER BY a.tgl_sp2d DESC, a.no_sp2d";
       //and UPPER(no_sp2d) LIKE '%$kriteria%'  
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'no_sp2d' => $resulte['no_sp2d'],
                        'tgl_sp2d' => $resulte['tgl_sp2d'],
                        'nilai' => $resulte['nilai'],
                        'sisa' => $resulte['sisa']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function rek_pot() {
        $lccr   = $this->input->post('q') ;
        $sql    = " SELECT kd_rek5,nm_rek5 FROM ms_pot where ( upper(kd_rek5) like upper('%$lccr%')
                    OR upper(nm_rek5) like upper('%$lccr%') ) order by kd_rek5 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();       
    }
    
    function load_no_penagihan_trs() { 
        $cskpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        
        $sql = "SELECT a.kd_skpd,a.no_bukti, tgl_bukti, a.ket,a.kontrak,kd_subkegiatan,SUM(b.nilai) as total 
                FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti
                WHERE a.kd_skpd='$cskpd' and a.jns_trs='2' and (upper(a.kd_skpd) like upper('%$lccr%') or  
                upper(a.no_bukti) like upper('%$lccr%')) and a.no_bukti not in
                (SELECT isnull(no_tagih,'') no_tagih from trhspp WHERE kd_skpd = '$cskpd' GROUP BY no_tagih)
                GROUP BY a.kd_skpd, a.no_bukti,tgl_bukti,a.ket,a.kontrak,kd_subkegiatan order by a.no_bukti";
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
                        'subkegiatan' => $resulte['kd_subkegiatan'],
                        'kontrak' => $resulte['kontrak'],
                        'nila' => number_format($resulte['total'],2,'.',','),
                        'nil' => $resulte['total']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function auto_cek_status($skpd){
        $tgl_spp = $this->input->post('tgl_cek');
        $sql = "SELECT 
                case 
                when statu=1 and status_sempurna=1 and status_ubah=1 and status_ubah_penyempurna=1 then 'purna'
                when statu=1 and status_sempurna=1 and status_ubah=1 and status_ubah_penyempurna=0 then 'ubah'
                when statu=1 and status_sempurna=1 and status_ubah=0 and status_ubah_penyempurna=0 then 'geser' 
                when statu=1 and status_sempurna=0 and status_ubah=0 and status_ubah_penyempurna=0 then 'murni' 
                when statu=1 and status_sempurna=0 and status_ubah=1 and status_ubah_penyempurna=1 then 'murni'
                else 'murni' end as anggaran from trhrka where left(kd_skpd,17) =left('$skpd',17)";
              //  echo "$sql";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result() as $resulte)
        { 
            $status_ang = $resulte->anggaran;
        }
        return $status_ang;
    }
    
    function load_rek() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
    
        $cek=$this->auto_cek_status($kode);
        if($cek=='purna'){
            $stat="nilai_ubah_penyempurna";
        }else if($cek=='ubah'){
            $stat="nilai_ubah";
        }else{
            $stat="nilai_ubah";
        }
 /*       $stsubah     =$this->rka_model->get_nama($kode,'status_ubah','trhrka','kd_skpd');
        $stssempurna =$this->rka_model->get_nama($kode,'status_sempurna','trhrka','kd_skpd');
       */

        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
        
        
            $field='nilai_ubah';
        
        
        if ($jenis=='1'){
            $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd AND c.username=d.username
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5
                        AND c.no_voucher <> '$nomor'
                        AND d.jns_spp='$jenis' AND d.status_validasi='0'
                        UNION ALL
                        SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5 AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_kegiatan = a.kd_kegiatan
                        AND left(x.kd_skpd,17) = left(a.kd_skpd,17)
                        AND x.kd_rek5 = a.kd_rek5
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_kegiatan = a.kd_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek5
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, $stat AS nilai_ubah
                        FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn  ";
                    
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
                    WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
                    AND c.kd_rek5=b.kd_rek5 AND c.no_voucher <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran,
                    0 as nilai_sempurna,
                    0 as nilai_ubah
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_kegiatan='$giat' $notIn ";
        }        
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran'],
                        'anggaran_semp' => $resulte['nilai_sempurna'],
                        'anggaran_ubah' => $resulte['nilai_ubah']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }
    
   

    function load_rek_tunai() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
            
/*       $stsubah =$this->rka_model->get_nama($kode,'status_ubah','trhrka','kd_skpd');
        $stssempurna =$this->rka_model->get_nama($kode,'status_sempurna','trhrka','kd_skpd');
       */
        $cek=$this->auto_cek_status($kode);
        if($cek=='purna'){
            $stat="nilai_ubah_penyempurna";
        }else if($cek=='ubah'){
            $stat="nilai_ubah";
        }

        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
        
        
            $field='nilai_ubah';
        
        
        if ($jenis=='1'){
            
            if($giat=='1.01.1.01.01.00.22.002'){
                $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5
                        AND c.no_voucher <> '$nomor'
                        AND d.jns_spp='$jenis' AND d.status_validasi='0'
                        UNION ALL
                        SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5 AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_kegiatan = a.kd_kegiatan
                        AND left(x.kd_skpd,17) = left(a.kd_skpd,17)
                        AND x.kd_rek5 = a.kd_rek5
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_kegiatan = a.kd_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek5
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, $stat AS nilai_ubah
                        FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_rek5 in ('5221104') AND a.kd_skpd = '$kode' $notIn  ";
                
            }else if($giat=='4.08.4.08.01.00.01.351'){
                $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5
                        AND c.no_voucher <> '$nomor'
                        AND d.jns_spp='$jenis' AND d.status_validasi='0'
                        UNION ALL
                        SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5 AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_kegiatan = a.kd_kegiatan
                        AND left(x.kd_skpd,17) = left(a.kd_skpd,17)
                        AND x.kd_rek5 = a.kd_rek5
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_kegiatan = a.kd_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek5
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, $stat AS nilai_ubah
                        FROM trdrka a WHERE a.kd_kegiatan= '$giat'  AND a.kd_skpd = '$kode' $notIn  ";
                
            }else{
                $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5
                        AND c.no_voucher <> '$nomor'
                        AND d.jns_spp='$jenis' AND d.status_validasi='0'
                        UNION ALL
                        SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5 AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_kegiatan = a.kd_kegiatan
                        AND left(x.kd_skpd,17) = left(a.kd_skpd,17)
                        AND x.kd_rek5 = a.kd_rek5
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_kegiatan = a.kd_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek5
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, $stat AS nilai_ubah
                        FROM trdrka a WHERE a.kd_kegiatan= '$giat' 
                        --AND a.kd_rek5 in ('5221802','5221104','5221909','5220206','5220305','5220101','5220312','5220313','5220104','5220501','5222501','5220307','5220505','5220314','5220801','5220702','5221012','5220321','5220326','5220310','5220207','5220311','5221105') 
                        AND a.kd_skpd = '$kode' $notIn  ";
                
            }
                    
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
                    WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
                    AND c.kd_rek5=b.kd_rek5 AND c.no_voucher <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran,
                    0 as nilai_sempurna,
                    0 as nilai_ubah
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_kegiatan='$giat' $notIn ";
        }        
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran'],
                        'anggaran_semp' => $resulte['nilai_sempurna'],
                        'anggaran_ubah' => $resulte['nilai_ubah']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }
    
    function load_reksumber_dana() {                      
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $rek    = $this->input->post('rek'); 
        $nomor  = $this->input->post('nomor');
        $jenis  = $this->input->post('jenis');       
        $lccr   = $this->input->post('q');

        $cek=$this->auto_cek_status($kode);
        if($cek=='purna'){
            $stat="_penyempurna";
        }else{
            $stat="";
        }

        


            $sql ="SELECT sumber_dana, sum(nilai) nilai, sum(nilai_sempurna) nilai_sempurna, 
            sum(nilai_ubah) nilai_ubah, sum(lalu) lalu from (
            select sumber1_ubah$stat as sumber_dana,isnull(nilai_sumber,0) as nilai,isnull(nsumber1_su,0) as nilai_sempurna,isnull(nsumber1_ubah$stat,0) as nilai_ubah, 0 lalu from trdrka a where 
            a.kd_kegiatan='$giat' and a.kd_rek5='$rek' and left(a.kd_skpd,17)=left('$kode',17) 
            union ALL
            select sumber2_ubah$stat as sumber_dana,isnull(nilai_sumber2,0) as nilai,isnull(nsumber2_su,0) as nilai_sempurna,isnull(nsumber2_ubah$stat,0) as nilai_ubah, 0 lalu from trdrka a where 
            a.kd_kegiatan='$giat' and a.kd_rek5='$rek' and left(a.kd_skpd,17)=left('$kode',17) and nsumber2_ubah <> 0
            union all
            select sumber, 0 nilai, 0 nilai_sempurna, 0 nilai_ubah, sum(nilai) lalu from(
          
            SELECT
                    sumber, c.kd_rek5, SUM (c.nilai) AS nilai
                    FROM
                        trdtransout c
                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                    AND c.kd_skpd = d.kd_skpd
                    WHERE
                        c.kd_kegiatan = '$giat' and  c.kd_rek5='$rek'
                    AND LEFT (d.kd_skpd, 7) = LEFT ('$kode', 7)

                    AND d.jns_spp = '1'
GROUP BY c.kd_rek5, sumber
  union all
            SELECT sumber, kd_rek5, sum(nilai) nilai from(



SELECT
a.sumber,
a.kd_rek5,
                            SUM (a.nilai) nilai
                        FROM
                            trdtransout a
                        INNER JOIN trhtransout b ON a.no_bukti = b.no_bukti
                        AND a.kd_skpd = b.kd_skpd
                        WHERE
                            a.kd_kegiatan = '$giat'
                        AND a.kd_rek5 = '$rek'
                        AND LEFT (a.kd_skpd, 7) = left('$kode',7)
                        AND b.jns_spp IN ('4', '6')
                        AND panjar IN ('3')
                        GROUP BY a.kd_rek5, a.sumber

UNION all
SELECT
                            sumber, t.kd_rek5,SUM (nilai) AS nilai
                            FROM
                                trdtagih t
                            INNER JOIN trhtagih u ON t.no_bukti = u.no_bukti
                            AND t.kd_skpd = u.kd_skpd
                            WHERE
                                t.kd_kegiatan = '$giat'
                            AND u.kd_skpd = '$kode'
                            AND u.no_bukti NOT IN (
                                SELECT
                                    no_tagih
                                FROM
                                    trhspp
                                WHERE
                                    kd_skpd = '$kode'
                            ) GROUP BY t.kd_rek5, sumber
UNION all
SELECT
                        x.sumber, x.kd_rek5,    SUM (x.nilai) AS nilai
                        FROM
                            trdspp x
                        INNER JOIN trhspp y ON x.no_spp = y.no_spp
                        AND x.kd_skpd = y.kd_skpd
                        WHERE
                            x.kd_kegiatan = '$giat'
                        AND LEFT (x.kd_skpd, 7) = LEFT ('$kode', 7)
                        AND y.jns_spp IN ('3', '4', '5', '6')
                        AND (
                            sp2d_batal IS NULL
                            OR sp2d_batal = ''
                            OR sp2d_batal = '0'
                        ) GROUP BY  x.sumber, x.kd_rek5

UNION all 
    SELECT
                c.sumber, c.kd_rek5, SUM (c.nilai) AS nilai
                FROM
                    trdtransout_cmsbank c
                LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                AND c.kd_skpd = d.kd_skpd
                AND c.username = d.username
                WHERE
                    c.kd_kegiatan = '$giat'
                AND LEFT (d.kd_skpd, 7) = LEFT ('$kode', 7)
                AND c.no_voucher <> '$nomor'
                AND d.jns_spp = '$jenis'
                AND d.status_validasi = '0'

GROUP BY c.sumber, c.kd_rek5

 ) dd WHERE kd_rek5='$rek' GROUP BY sumber, kd_rek5) v group by sumber, kd_rek5


            )z /*where sumber_dana<>''*/ GROUP BY  sumber_dana";                

        
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
                        'nilailalu' => $resulte['lalu']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }



    function load_sisa_bank(){
        $kd_skpd = $this->session->userdata('kdskpd');                
        $skpdbp = substr($kd_skpd,18,4);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";  
            if($skpdbp=="0000"){
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";    
            }else{
            $init_skpd = "kode='$kd_skpd'";    
            }
            $init_skpd = "kode='$kd_skpd'";         
        }else{
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
            $init_skpd = "kode='$kd_skpd'";
        }
        
            $query1 = $this->db->query("SELECT sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
      select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar where left(kd_skpd,17)=left('$kd_skpd',17)   UNION ALL
      select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,17)=left('$kd_skpd',17) UNION ALL
                              
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') and left(a.kd_skpd,17)=left('$kd_skpd',17) UNION ALL
                        SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' and left(a.kd_skpd,17)=left('$kd_skpd',17)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17)  union all
      select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,17)=left('$kd_skpd',17) 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where $init_skpd)b");
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

    function load_sisa_bank_transcms(){
        $kd_skpd = $this->session->userdata('kdskpd');                
        $skpdbp = substr($kd_skpd,18,4);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";  
            if($skpdbp=="0000"){
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";    
            }else{
            $init_skpd = "kode='$kd_skpd'";    
            }          
        }else{
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
        }
        
            $query1 = $this->db->query("SELECT sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar where left(kd_skpd,17)=left('$kd_skpd',17)   UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd_sumber as kode from tr_setorpelimpahan_bank_cms where kd_skpd_sumber='$kd_skpd' and status_validasi=0  UNION ALL

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') and left(a.kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' and left(a.kd_skpd,17)=left('$kd_skpd',17)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17)  union all
            select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,17)=left('$kd_skpd',17) 
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
    
    function load_sisa_bank_upval(){
        $kd_skpd = $this->session->userdata('kdskpd');                
        $skpdbp = substr($kd_skpd,18,4);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";  
            if($skpdbp=="0000"){
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";    
            }else{
            $init_skpd = "kode='$kd_skpd'";    
            }           
        }else{
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
        }
        
            $query1 = $this->db->query("select sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
            SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar where left(kd_skpd,17)=left('$kd_skpd',17)   UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,17)=left('$kd_skpd',17) UNION ALL
                              
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') and left(a.kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' and left(a.kd_skpd,17)=left('$kd_skpd',17)  UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'1' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_upload='0' and status_validasi='0' and left(a.kd_skpd,17)=left('$kd_skpd',17)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17)  union all
      select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,17)=left('$kd_skpd',17) 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where $init_skpd)b");
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

    function load_sisa_bank_val(){
        $kd_skpd = $this->session->userdata('kdskpd');                
        $skpdbp = substr($kd_skpd,18,4);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";  
            if($skpdbp=="0000"){
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";    
            }else{
            $init_skpd = "kode='$kd_skpd'";    
            }          
        }else{
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
        }
        
            $query1 = $this->db->query("select sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
        SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
        select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar where left(kd_skpd,17)=left('$kd_skpd',17)   UNION ALL
        select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,17)=left('$kd_skpd',17) UNION ALL
                              
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') and left(a.kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' and left(a.kd_skpd,17)=left('$kd_skpd',17)  UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'1' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_upload='1' and status_validasi='0' and left(a.kd_skpd,17)=left('$kd_skpd',17)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17)  union all
      select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,17)=left('$kd_skpd',17) 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where $init_skpd)b");
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

    function load_sisa_bank_cms(){
        $kd_skpd = $this->session->userdata('kdskpd');    
        $skpdbp =  substr($kd_skpd,18,4);
        $inskpd = substr($kd_skpd,17);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kode='$kd_skpd'";
            if($skpdbp=="0000"){
               $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
            }else{
               $init_skpd = "kode='$kd_skpd'";    
            }            
        }else{
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
        }
        
                            
            $query1 = $this->db->query("SELECT sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
            select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE left(kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') AND bank='BNK' AND left(a.kd_skpd,17)=left('$kd_skpd',17)
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd UNION ALL   
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE left(kd_skpd,17)=left('$kd_skpd',17) and pay='BANK' union ALL                                  
            
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,(total-isnull(pot,0)) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') 
             and a.no_bukti+a.kd_skpd not in (SELECT min(z.no_kas)+z.kd_skpd as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and left(z.kd_skpd,17)=left('$kd_skpd',17) GROUP BY z.no_sp2d,z.kd_skpd HAVING COUNT(z.no_sp2d)>1) UNION ALL
             
             select a.tgl_bukti as tgl,a.no_bukti as bku, a.ket, a.total as jumlah,'2' AS jns, a.kd_skpd as kode from trhtransout a
             where a.no_sp2d in (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd=a.kd_skpd GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) and a.no_kas in
             (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd=a.kd_skpd GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1 )and a.jns_spp in (4,5,6) and left(a.kd_skpd,17)=left('$kd_skpd',17) UNION ALL
             
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE left(kd_skpd,17)=left('$kd_skpd',17) AND status_drop !='1' union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank_cms WHERE left(kd_skpd,17)=left('$kd_skpd',17) union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorpelimpahan_bank_cms WHERE kd_skpd='$kd_skpd' and kd_rek6='4'
            ) a where $init_skpd)b");
            
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

    function load_total_spd(){
       $kode    = $this->input->post('kode');
       $koded    = substr($kode,0,17);
       $giat    = $this->input->post('giat');
       
            $sql = "SELECT
                        SUM (a.nilai_final) AS total_spd
                    FROM
                        trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                        left(b.kd_skpd,17) = '$koded'
                    AND a.kd_kegiatan = '$giat'
                    AND b.status = '1'";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'total_spd' => number_format($resulte['total_spd'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_trans_trmpot(){
       $kode    = $this->session->userdata('kdskpd');
       $id      = $this->session->userdata('pcNama');
       
            /*$sql = "SELECT DISTINCT a.no_tgl,a.no_voucher,a.tgl_voucher,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,a.jns_spp,a.total 
            FROM trhtransout_cmsbank a
            JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd
            WHERE a.kd_skpd = '$kode' and a.status_upload not in ('1') and a.jns_spp in ('1','3')
            order by a.tgl_voucher,a.no_voucher";*/
       
       $sql = "SELECT DISTINCT a.no_tgl,a.no_voucher,a.tgl_voucher,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,a.jns_spp,a.total 
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
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'nm_rek5' => $resulte['nm_rek5'],
                        'jns_spp' => $resulte['jns_spp'],
                        'total' => number_format($resulte['total'],2)                              
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_total_upload($tgl=''){
       $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
                        SUM (b.nilai) AS total_upload
                    FROM
                        trhtransout_cmsbank a
                    JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd
                    WHERE
                        left(a.kd_skpd,17) = left('$kode',17)
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
    
    function load_total_trans(){
       $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $no_bukti    = $this->input->post('no_simpan');
       $beban       = $this->input->post('beban');
       
       if($beban=="3"){
                    $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                        select g.kd_kegiatan,sum(g.lalu) spp from(
                                SELECT b.kd_kegiatan,
                                (SELECT isnull(SUM(c.nilai),0) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd AND c.username=d.username
                                WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                                d.kd_skpd=a.kd_skpd 
                                AND c.kd_rek5=b.kd_rek5 AND c.no_voucher <> 'x' AND c.kd_kegiatan='$kegiatan') AS lalu,
                                b.nilai AS sp2d
                                FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                                INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                                INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                                WHERE b.kd_kegiatan='$kegiatan'
                                )g group by g.kd_kegiatan
                                
                                    ) as d on a.kd_kegiatan=d.kd_kegiatan
                                    left join 
                                    (
                                        
                                        select z.kd_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
                                        where f.kd_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_kegiatan
                                        UNION ALL
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_kegiatan
                                        )z group by z.kd_kegiatan
                                        
                                    ) g on a.kd_kegiatan=g.kd_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_kegiatan
                                    ) z ON a.kd_kegiatan=z.kd_kegiatan
                                    where a.kd_kegiatan='$kegiatan'"; 
       }else{
                $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                        select c.kd_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_kegiatan
                                    ) as d on a.kd_kegiatan=d.kd_kegiatan
                                    left join 
                                    (
                                        
                                        select z.kd_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
                                        where f.kd_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_kegiatan
                                        UNION ALL
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_kegiatan
                                        )z group by z.kd_kegiatan
                                        
                                    ) g on a.kd_kegiatan=g.kd_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_kegiatan
                                    ) z ON a.kd_kegiatan=z.kd_kegiatan
                                    where a.kd_kegiatan='$kegiatan'";     
       }
        
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
    
    function cekdulu(){
       $kd_skpd  = $this->session->userdata('kdskpd');
        $skpdbp = substr($kd_skpd,8,2);
        echo  $skpdbp;
    }
    
    function load_sisa_tunai(){
        $kd_skpd  = $this->session->userdata('kdskpd');
        $skpdbp = substr($kd_skpd,8,2);
        
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        
        if($cek_skpd1==1){
                $init_skpd = "a.kd_skpd='$kd_skpd'";
                $init_skpd2 = "kd_skpd='$kd_skpd'";
                $init_skpd3 = "kd_skpd_sumber='$kd_skpd'";
                $init_skpd4 = "kode='$kd_skpd'";  
                
            /*if($skpdbp=="00"){
                $init_skpd = "left(a.kd_skpd,7)=left('$kd_skpd',7)";
                $init_skpd2 = "left(kd_skpd,7)=left('$kd_skpd',7)";
                $init_skpd3 = "left(kd_skpd_sumber,7)=left('$kd_skpd',7)";
                $init_skpd4 = "left(kode,7)=left('$kd_skpd',7)";
            }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
                $init_skpd2 = "kd_skpd='$kd_skpd'";
                $init_skpd3 = "kd_skpd_sumber='$kd_skpd'";
                $init_skpd4 = "kode='$kd_skpd'";   
            } */                       
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            $init_skpd2 = "left(kd_skpd,17)=left('$kd_skpd',17)";
            $init_skpd3 = "left(kd_skpd_sumber,17)=left('$kd_skpd',17)";
            $init_skpd4 = "left(kode,17)=left('$kd_skpd',17)";
        }
                        
        $query1 = $this->db->query("
                SELECT 
                SUM(case when jns=1 then jumlah else 0 end ) AS terima,
                SUM(case when jns=2 then jumlah else 0 end) AS keluar
                FROM (
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE $init_skpd2 UNION ALL
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2')  AND $init_skpd and bank='TN'
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd             
                UNION ALL
                SELECT  a.tgl_bukti AS tgl, a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar <> 1
                                AND $init_skpd 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where $init_skpd2 GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                 and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and $init_skpd2 
                                
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and $init_skpd2)
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                SELECT  tgl_bukti AS tgl,   no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar <> 1 and no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where $init_skpd2 GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND   no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and $init_skpd2 
                            
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and $init_skpd2              
                UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' AND $init_skpd2 UNION ALL
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' AND $init_skpd2 UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI' AND $init_skpd2 UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan WHERE $init_skpd3
                ) a 
                where $init_skpd4");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'sisa' => number_format(($resulte['terima'] - $resulte['keluar']),2,'.',','),                      
                        'keluar' => number_format($resulte['keluar'],0),
                        'terima' => number_format($resulte['terima'],0)
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result(); 
    }
    
    function load_sisa_pot_ls(){
        $kd_skpd  = $this->session->userdata('kdskpd'); 
        $sp2d  = $this->input->post('sp2d');
        $query1 = $this->db->query("SELECT SUM(a.nilai) as total  FROM trspmpot a INNER JOIN trhsp2d b on b.no_spm = a.no_spm AND b.kd_skpd=a.kd_skpd
        where ((b.jns_spp = '4' AND b.jenis_beban != '1') or (b.jns_spp = '6' AND b.jenis_beban != '3'))
        and b.no_sp2d = '$sp2d' and LEFT(b.kd_skpd,17) = LEFT('$kd_skpd',17) ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'sisa' => number_format($resulte['total'],2,'.',',')                      
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
            $query1->free_result(); 
    }
    
    function cek_status_ang(){
        $tgl_spp = $this->input->post('tgl_cek');
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT 
                case 
                when statu=1 and status_sempurna=1 and status_ubah=1 and status_ubah_penyempurna=1 then 'Perubahan Penyempurnaan'
                when statu=1 and status_sempurna=1 and status_ubah=1 and status_ubah_penyempurna=0 then 'Perubahan' 
                when statu=1 and status_sempurna=1 and status_ubah=0 and status_ubah_penyempurna=0 then 'Penyempurnaan' 
                when statu=1 and status_sempurna=0 and status_ubah=0 and status_ubah_penyempurna=0 then 'Penyusunan'
                else 'Penyusunan' end as anggaran from trhrka where left(kd_skpd,17) =left('$skpd',17)";
              //  echo "$sql";
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
                ORDER BY b.kd_kegiatan,b.kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_voucher'    => $resulte['no_voucher'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
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
    
    function load_dtransout_tunai(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
                FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti 
                AND a.kd_skpd=b.kd_skpd 
                WHERE a.no_bukti='$nomor' AND a.kd_skpd='$skpd' AND a.pay='TUNAI'
                ORDER BY b.kd_kegiatan,b.kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'    => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
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
    
    function load_dtransout_trdmpot(){        
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_user = $this->session->userdata('pcNama');
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $sql = "select a.* from trdtrmpot_cmsbank a left join trhtrmpot_cmsbank b on b.no_bukti=a.no_bukti and a.kd_skpd=b.kd_skpd and a.username=b.username where b.no_voucher='$nomor' and b.kd_skpd='$skpd' a.username='$kd_user'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nilai'         => $resulte['nilai'],
                        'nilai_nformat' => number_format($resulte['nilai'])                                                                                                                                                           
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_dpot(){        
        $nomor = $this->input->post('no');
        $sql = "select * from trdtrmpot where no_bukti='$nomor' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nilai'         => $resulte['nilai']                                                                                                                                                         
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_dtagih(){        
        $nomor = $this->input->post('no'); 
        $kd_skpd = $this->session->userdata('kdskpd');   
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtagih c LEFT JOIN trhtagih d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = b.kd_subkegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek5=b.kd_rek AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_subkegiatan=b.kd_subkegiatan AND f.kd_rek5=b.kd_rek5) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_subkegiatan = b.kd_subkegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek) AS anggaran FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' and a.kd_skpd='$kd_skpd' ORDER BY b.kd_subkegiatan,b.kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_subkegiatan'   => $resulte['kd_subkegiatan'],
                        'nm_subkegiatan'   => $resulte['nm_subkegiatan'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'kd_rek'        => $resulte['kd_rek'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nilai'         => $resulte['nilai'],
                        'lalu'          => $resulte['lalu'],
                        'sp2d'          => $resulte['sp2d'],   
                        'anggaran'      => $resulte['anggaran']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }    
    
    function no_urut(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
    select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
    select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
    select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
    select no_panjar nomor, 'Pemberian Panjar' ket,kd_skpd from tr_panjar where  isnumeric(no_panjar)=1  union ALL
    select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
    select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
    select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
    select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
    select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
    select no_bukti+1 nomor, 'Ambil Simpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop !='1' union ALL
    select no_bukti nomor, 'Ambil Drop Dana' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop ='1' union ALL
    select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
    select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
    select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE KD_SKPD = '$kd_skpd'");
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
    
   function no_urut_validasibku(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "KD_SKPD = '$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(KD_SKPD,17) = left('$kd_skpd',17)";
            }else{
                $init_skpd = "KD_SKPD = '$kd_skpd'";
            }            
        }
    
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
    select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
    select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
    select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
    select no_panjar nomor, 'Pemberian Panjar' ket,kd_skpd from tr_panjar where  isnumeric(no_panjar)=1  union ALL
    select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
    select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
    select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
    select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
    select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
    select no_bukti+1 nomor, 'Ambil Simpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop !='1' union ALL
    select no_bukti nomor, 'Ambil Drop Dana' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop ='1' union ALL
    select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
    select no_kas nomor, 'Setor Simpanan CMS' ket,kd_skpd_sumber kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_bukti)=1 union all
    select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
    select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE $init_skpd");
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
    
   function no_urut_cms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $kd_user = $this->session->userdata('pcNama');
    $tgl = date('Y-m-d');
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_voucher nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' and username='$kd_user' union
    select no_bukti nomor, 'Potongan Pajak Transaksi Non Tunai' ket, kd_skpd from trhtrmpot_cmsbank where kd_skpd = '$kd_skpd' and username='$kd_user') z WHERE KD_SKPD = '$kd_skpd'");
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
    
    function no_urut_tglcms(){
    $kd_skpd = $this->session->userdata('kdskpd');     
    date_default_timezone_set("Asia/Bangkok");
    $tgl = date('Y-m-d');
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_tgl nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' and tgl_voucher='$tgl') z WHERE KD_SKPD = '$kd_skpd'");
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
    
    function no_urut_uploadcms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $user = $this->session->userdata('pcNama');
     $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = "1";//$cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "KD_SKPD = '$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(kd_skpd,17) = left('$kd_skpd',17)";
            }else{
                $init_skpd = "KD_SKPD = '$kd_skpd'";
            }            
        }
    
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_upload nomor, 'Urut Upload Pengeluaran cms' ket, kd_skpd, username from trdupload_cmsbank where $init_skpd 
    union all
    select no_upload nomor, 'Urut Upload Setor Dana Bank cms' ket, kd_skpd, username from trhupload_cmsbank_bidang where $init_skpd     
    union all
    select no_upload nomor, 'Urut Upload Panjar Bank cms' ket, kd_skpd, username from trhupload_cmsbank_panjar where $init_skpd     
    union all
    select no_upload nomor, 'Urut Upload Penerimaan cms' ket, kd_skpd, username from trhupload_sts_cmsbank where $init_skpd
    ) 
    z WHERE $init_skpd and username='$user'");
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
    
    $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd = '$kd_skpd'";
            $init_skpd2 = "kd_skpd = '$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(a.kd_skpd,17) = left('$kd_skpd',17)";
                $init_skpd2 = "left(kd_skpd,17) = left('$kd_skpd',17)";
            }else{
                $init_skpd = "a.KD_SKPD = '$kd_skpd'";
                $init_skpd2 = "KD_SKPD = '$kd_skpd'";
            }            
        }
    
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d");
    
    /*
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Pengeluaran cms' ket, a.kd_skpd from trdupload_cmsbank a
    left join trhupload_cmsbank b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where $init_skpd
    
    */
    
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
        select a.no_upload_tgl nomor, a.tgl_upload tanggal,'Urut Upload Pengeluaran cms' ket, a.kd_skpd from trhupload_cmsbank a        
    where $init_skpd
        union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Setor Dropping Bank cms' ket, a.kd_skpd from trdupload_cmsbank_bidang a
        left join trhupload_cmsbank_bidang b on b.kd_skpd=a.kd_skpd and b.no_upload=a.no_upload
    where $init_skpd
        union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Panjar Bank cms' ket, a.kd_skpd from trdupload_cmsbank_panjar a
        left join trhupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and b.no_upload=a.no_upload
    where $init_skpd
        union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Penerimaan cms' ket, a.kd_skpd from trdupload_sts_cmsbank a
        left join trhupload_sts_cmsbank b on b.kd_skpd=a.kd_skpd and b.no_upload=a.no_upload
    where $init_skpd
    ) 
    z WHERE $init_skpd2 AND tanggal='$tanggal'");
    
    
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
    
    function no_urut_uploadcms_sts(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_upload nomor, 'Urut Upload cms' ket, kd_skpd from trhupload_sts_cmsbank where kd_skpd = '$kd_skpd' 
    ) 
    z WHERE KD_SKPD = '$kd_skpd'");
        $ii = 0;
        $nomor = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            $nomor = $resulte['nomor'];
                        
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $nomor
                        );
                        $ii++;
        }
        
        echo json_encode($result);
        $query1->free_result();   
    }
    
    function no_urut_uploadcmsharian_sts(){
    $kd_skpd = $this->session->userdata('kdskpd');     
    $tanggal = date("Y-m-d");
    
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_upload_tgl nomor, tgl_sts tanggal,'Urut Upload cms' ket, kd_skpd from trdupload_sts_cmsbank where kd_skpd = '$kd_skpd' 
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

    function no_urut_validasicms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    
    $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kd_skpd = '$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(kd_skpd,17) = left('$kd_skpd',17)";
            }else{
                $init_skpd = "KD_SKPD = '$kd_skpd'";
            }            
        }
    
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_validasi nomor, 'Urut Validasi cms' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank where kd_skpd = '$kd_skpd' 
    union all
    select no_validasi nomor, 'Urut Validasi cms Perbidang' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank_bidang where kd_skpd = '$kd_skpd'
    union all
    select no_validasi nomor, 'Urut Validasi cms Panjar' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank_panjar where kd_skpd = '$kd_skpd'
    ) 
    z WHERE $init_skpd ");
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
 
         
        function rek_pot_ar() {
        
        $lccr     = $this->input->post('q');
        $ckdrek   = $this->input->post('kdrek');
        
        if (  $ckdrek != '' ){
            $NotIn = " where kd_rek5 not in ($ckdrek) and ( upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%') ) " ;
        } else {
            $NotIn = " where ( upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%') ) " ;
        }
        
        $sql      = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 $NotIn order by kd_rek5 ";
        $query1   = $this->db->query($sql);  
        $result   = array();
        $ii       = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'      => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();        
    
    }
    
    function load_no_penagihan() { 
        $cskpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        
        $sql = "SELECT a.kd_skpd,a.no_bukti, tgl_bukti, a.ket,a.kontrak,kd_kegiatan,SUM(b.nilai) as total 
                FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti
                WHERE a.kd_skpd='$cskpd' and a.jns_trs='1' and (upper(a.kd_skpd) like upper('%$lccr%') or  
                upper(a.no_bukti) like upper('%$lccr%')) and a.no_bukti not in
                (SELECT isnull(no_tagih,'') no_tagih from trhspp WHERE kd_skpd = '$cskpd' GROUP BY no_tagih)
                GROUP BY a.kd_skpd, a.no_bukti,tgl_bukti,a.ket,a.kontrak,kd_kegiatan order by a.no_bukti";
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
                        'kegiatan' => $resulte['kd_kegiatan'],
                        'kontrak' => $resulte['kontrak'],
                        'nila' => number_format($resulte['total'],2,'.',','),
                        'nil' => $resulte['total']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function hapus_transout(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $kd_id       = $this->session->userdata('pcNama');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd' AND username='$kd_id'";
        $asg = $this->db->query($sql);

        if ($asg){
            $sql = "delete from trhtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd' AND username='$kd_id'";
            $asg = $this->db->query($sql);
            
            $sql = "delete from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$kd_skpd' AND username='$kd_id'";
            $asg = $this->db->query($sql);
          
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }
    
    function hapus_transout_tunai(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        if ($asg){
            $sql = "delete from trhtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd' AND pay='TUNAI'";
            $asg = $this->db->query($sql);
           
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }

        function simpan_transout(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomor_tgl= $this->input->post('notgl');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $skpd = $this->session->userdata('kdskpd'); //$this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql'); 
        $csqlrek     = $this->input->post('sqlrek');           
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nosp2d   = $this->input->post('nosp2d2');  
        $xrek     = $this->input->post('xrek');     
        
        $rek_awal = trim($this->input->post('rek_awal'));            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $init_ket = $this->input->post('cinit_ket');
        $stt_val  = 0;
        $stt_up   = 0;
       
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout_cmsbank') {
            $sql = "delete from trhtransout_cmsbank where kd_skpd='$skpd' and no_voucher='$nomor' and username='$usernm'";
            $asg = $this->db->query($sql);
            
            if ($asg){
                $sql = "insert into trhtransout_cmsbank(no_voucher,tgl_voucher,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,no_tgl,ket_tujuan) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$nomor_tgl','$init_ket')";
                $asg = $this->db->query($sql);
                } else {
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
            
        }elseif($tabel == 'trdtransout_cmsbank') {
            // Simpan Detail //                                       
                
                $sql = "delete from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$skpd' and username='$usernm'";
                $asg = $this->db->query($sql);
                
                $sql = "delete from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$skpd' and username='$usernm'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout_cmsbank(no_voucher,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,sumber,username)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    $sql = "insert into trdtransout_transfercms(no_voucher,tgl_voucher,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai,username)"; 
                    $asg = $this->db->query($sql.$csqlrek);                                       
                       
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
    
    function simpan_transout_tunai(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomor_tgl= $this->input->post('notgl');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $skpd = $this->session->userdata('kdskpd'); //$this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql'); 
        $csqlrek     = $this->input->post('sqlrek');           
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nosp2d   = $this->input->post('nosp2d2');          
        
        $stt_val  = 0;
        $stt_up   = 0;
       
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor' and pay='TUNAI'";
            $asg = $this->db->query($sql);
            
            if ($asg){
                $sql = "insert into trhtransout (no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d')";
                $asg = $this->db->query($sql);
                } else {
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
            
        }elseif($tabel == 'trdtransout') {
            // Simpan Detail //                                       
                
                $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);                                
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout (no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,sumber)"; 
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
                        where left(trhtransout_cmsbank.kd_skpd,17)=left('$skpd',17)
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
                        where left(trhupload_cmsbank.kd_skpd,17)=left('$skpd',17)
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


    function simpan_validasicms(){
        $tabel    = $this->input->post('tabel');                
        $skpd     = $this->input->post('skpd');
        $csql     = $this->input->post('sql');      
        $nval     = $this->input->post('no');  
        
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

    if($tabel == 'trvalidasi_cmsbank') {
                            
                    $sql = "insert into trvalidasi_cmsbank(no_voucher,tgl_voucher,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,tgl_validasi,status_validasi,no_validasi,no_bukti,username)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $sql = "UPDATE
                            trhtransout_cmsbank
                            SET trhtransout_cmsbank.status_validasi = Table_B.status_validasi,
                                trhtransout_cmsbank.tgl_validasi = Table_B.tgl_validasi,
                                trhtransout_cmsbank.no_bukti = Table_B.no_bukti,
                                trhtransout_cmsbank.tgl_bukti = Table_B.tgl_validasi
                        FROM trhtransout_cmsbank     
                        INNER JOIN (select a.username,a.no_voucher,a.no_bukti,a.kd_skpd,a.kd_bp,a.tgl_validasi,a.status_validasi from trvalidasi_cmsbank a
                        where a.kd_skpd='$skpd' and no_validasi='$nval') AS Table_B ON trhtransout_cmsbank.no_voucher = Table_B.no_voucher AND trhtransout_cmsbank.kd_skpd = Table_B.kd_skpd AND trhtransout_cmsbank.username = Table_B.username
                        where left(trhtransout_cmsbank.kd_skpd,17)=left('$skpd',17)";
                        $asg = $this->db->query($sql);
                        
                        
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql = "INSERT INTO trhtransout (no_kas, tgl_kas, no_bukti, tgl_bukti, no_sp2d, ket, username, tgl_update, kd_skpd, nm_skpd, total, no_tagih, sts_tagih, tgl_tagih, jns_spp, pay, no_kas_pot, panjar, no_panjar)
                                    SELECT b.no_bukti as no_kas, b.tgl_validasi as tgl_kas, a.no_bukti, a.tgl_bukti, a.no_sp2d, a.ket, a.username as username, a.tgl_update, b.kd_skpd, a.nm_skpd, a.total, a.no_tagih, a.sts_tagih, a.tgl_tagih, a.jns_spp, a.pay, a.no_kas_pot, a.panjar, a.no_panjar
                                    FROM trhtransout_cmsbank a left join trvalidasi_cmsbank b on b.no_voucher=a.no_voucher and a.kd_skpd=b.kd_skpd and a.username=b.username
                                    WHERE b.no_validasi='$nval' and b.kd_bp='$skpd'";
                            $asg = $this->db->query($sql);
                            
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);                     
                                }  else {                                    
                                    $sql = "INSERT INTO trdtransout (no_bukti, no_sp2d, kd_kegiatan, nm_kegiatan, kd_rek5, nm_rek5, nilai, kd_skpd, sumber, username)
                                            SELECT c.no_bukti, a.no_sp2d, b.kd_kegiatan, b.nm_kegiatan, b.kd_rek5, b.nm_rek5, b.nilai, b.kd_skpd, b.sumber, a.username
                                            FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b on b.no_voucher=a.no_voucher and a.kd_skpd=b.kd_skpd and a.username=b.username
                                            LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd and c.username=a.username
                                            WHERE c.no_validasi='$nval' and c.kd_bp='$skpd'";
                                    $asg = $this->db->query($sql);                                    
                                    
                                    if (!($asg)){
                                        $msg = array('pesan'=>'0');
                                        echo json_encode($msg);                     
                                    }  else {                                                                        
                                        //Hpotongan
                                        $sql = "INSERT INTO trhtrmpot (no_bukti, tgl_bukti, ket, username, tgl_update, kd_skpd, nm_skpd, no_sp2d, nilai, npwp, jns_spp, status, kd_kegiatan, nm_kegiatan, kd_rek5, nm_rek5, nmrekan, pimpinan, alamat, ebilling, rekening_tujuan, nm_rekening_tujuan, no_kas)
                                        SELECT cast(c.no_bukti as int)+1 as no_bukti, c.tgl_validasi as tgl_bukti, d.ket, d.username, d.tgl_update, d.kd_skpd, d.nm_skpd, d.no_sp2d, d.nilai, d.npwp, d.jns_spp, d.status, d.kd_kegiatan, d.nm_kegiatan, d.kd_rek5, d.nm_rek5, d.nmrekan, d.pimpinan, d.alamat, d.ebilling, d.rekening_tujuan, d.nm_rekening_tujuan, c.no_bukti 
                                        FROM trhtrmpot_cmsbank d LEFT JOIN trhtransout_cmsbank a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd and a.username=d.username
                                        LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd and a.username=c.username
                                        WHERE c.no_validasi='$nval' and a.status_trmpot='1' and c.kd_bp='$skpd'";
                                            $asg = $this->db->query($sql);                                    
                                    
                                            if (!($asg)){
                                                $msg = array('pesan'=>'0');
                                                echo json_encode($msg);                     
                                            }  else {                                                                        
                                                
                                                    $sql = "INSERT INTO trdtrmpot (no_bukti, kd_rek5, nm_rek5, nilai, kd_skpd, kd_rek_trans, ebilling, username)
                                                    SELECT cast(c.no_bukti as int)+1 as no_bukti, b.kd_rek5, b.nm_rek5, b.nilai, b.kd_skpd, b.kd_rek_trans, b.ebilling, b.username
                                                    FROM trhtrmpot_cmsbank d inner join trdtrmpot_cmsbank b on b.no_bukti=d.no_bukti and b.kd_skpd=d.kd_skpd and b.username=d.username
                                                    LEFT JOIN trhtransout_cmsbank a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd and a.username=d.username
                                                    LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd and c.username=a.username
                                                    WHERE c.no_validasi='$nval' and a.status_trmpot='1' and c.kd_bp='$skpd'";
                                                    $asg = $this->db->query($sql);                                    
                                    
                                                if (!($asg)){
                                                    $msg = array('pesan'=>'0');
                                                    echo json_encode($msg);                     
                                                }  else {                                                                        
                                                    $msg = array('pesan'=>'1');
                                                    echo json_encode($msg);
                                                    /*
                                                    $sql = "INSERT INTO trdtransout_transfer(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)
                                                    SELECT a.no_bukti, a.tgl_bukti, d.rekening_awal, d.nm_rekening_tujuan, d.rekening_tujuan, d.bank_tujuan, left(d.kd_skpd,7)+'.00' kd_skpd, d.nilai
                                                    FROM trdtransout_transfercms d 
                                                    LEFT JOIN trhtransout_cmsbank a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd and a.username=d.username
                                                    LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                                    WHERE c.no_validasi='$nval' and c.kd_bp='$skpd'";
                                                    $asg = $this->db->query($sql);                                    
                                    
                                                    if (!($asg)){
                                                        $msg = array('pesan'=>'0');
                                                        echo json_encode($msg);                     
                                                    }  else {                                                                        
                                                        $msg = array('pesan'=>'1');
                                                        echo json_encode($msg);
                                                    }*/
                                                    
                                                }
                                                
                                            }
                                        
                                    }
                            }
                        }
                    }                    
                                                        
        }
    }    

    function batal_validasicms(){
        $tabel    = $this->input->post('tabel');  
        $skpd     = $this->input->post('skpd');
        $nbku     = $this->input->post('nobukti');   
        $nbku_i   = strval($nbku)+1;     
        $nval     = $this->input->post('novoucher'); 
        $tglbku   = $this->input->post('tglvalid');
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

    if($tabel == 'trvalidasi_cmsbank') {
                    
                    //hapus Htrans   
                    $sql ="delete from trhtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);   
                            
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       
                       $sql ="delete from trdtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
                       $asg = $this->db->query($sql);   
                    
                        $asg = $this->db->query($sql);
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql ="delete from trvalidasi_cmsbank where no_bukti='$nbku' and no_voucher='$nval' and kd_skpd='$skpd'";
                            $asg = $this->db->query($sql);
                            
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);                     
                                }  else {
                                    
                                    $sql ="update trhtransout_cmsbank set status_validasi='0', tgl_validasi='' where no_voucher='$nval' and kd_skpd='$skpd'";
                                    $asg = $this->db->query($sql);                                   
                                    
                                    if (!($asg)){
                                        $msg = array('pesan'=>'0');
                                        echo json_encode($msg);                     
                                    }  else {                                                                        
                                        //Hpotongan
                                        $sql = "select count(*) as jml from trhtransout_cmsbank where no_voucher='$nval' and kd_skpd='$skpd' and status_trmpot='1'";
                                            $asg = $this->db->query($sql)->row();                                    
                                                $initjml = $asg->jml;
                                                
                                                if($initjml=='1'){
                                                
                                                $sql = "delete trhtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                                                $asg = $this->db->query($sql);                                    
                                    
                                                if (!($asg)){
                                                    $msg = array('pesan'=>'0');
                                                    echo json_encode($msg);                     
                                                }  else {                  
                                                        
                                                    $sql = "delete trdtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                                                    $asg = $this->db->query($sql);                                    
                                    
                                                    if (!($asg)){
                                                        $msg = array('pesan'=>'0');
                                                        echo json_encode($msg);                     
                                                    }  else {                  
                                                        
                                                        $sql = "delete trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
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
                                                    
                                                }else{
                                                        $sql = "delete trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
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
                        }
                    }                    
                                                        
        }
    }    

    
    function simpan_transout_edit(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku   = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nosp2d   = $this->input->post('nosp2d2');     
        $update     = date('Y-m-d H:i:s');
        $rek_awal = $this->input->post('rek_awal');            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $stt_val  = 0;
        $stt_up   = 0;
        $msg        = array();
            
        // Simpan Header //
        if ($tabel == 'trhtransout_cmsbank') {
            $sql = "delete from trhtransout_cmsbank where kd_skpd='$skpd' and no_bukti='$no_bku'";
            $asg = $this->db->query($sql);
            

            if ($asg){
                
                $sql = "insert into trhtransout_cmsbank(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d')";
                $asg = $this->db->query($sql);

                             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }else if($tabel == 'trdtransout_cmsbank') {
           
            // Simpan Detail //                       
                $sql = "delete from trdtransout_cmsbank where no_bukti='$no_bku' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{                                
                    $sql = "insert into trdtransout_cmsbank(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,sumber)"; 
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
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        
        $sql = "SELECT top $rows a.*,(select status_upload from trhtransout_cmsbank where no_voucher=a.no_voucher and kd_skpd='$kd_skpd' and username='$kd_user') as status_uploadx from trhtrmpot_cmsbank a where a.kd_skpd='$kd_skpd' AND a.username='$kd_user' AND a.no_bukti not in (SELECT top $offset no_bukti FROM trhtrmpot_cmsbank where kd_skpd='$kd_skpd' and username='$kd_user' 
        order by CAST(no_bukti AS INT)) $where order by CAST(a.no_bukti AS INT),a.kd_skpd";

        
        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
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
                        'kd_giat' => $resulte['kd_kegiatan'],
                        'nm_giat' => $resulte['nm_kegiatan'],
                        'kd_rek' => $resulte['kd_rek5'],
                        'nm_rek' => $resulte['nm_rek5'],
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
    
    function trdtrmpot_list() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('nomor');
        
        $sql = "SELECT * FROM trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd ='$kd_skpd' order by kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,   
                        'kd_rek_trans' => $resulte['kd_rek_trans'],  
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],  
                        'ebill' => $resulte['ebilling'],
                        //'nilai' => $resulte['nilai']
                        'nilai' => number_format($resulte['nilai'],2,'.',',')
                        );
                        $ii++;
        }
           
        echo json_encode($result);
         //$query1->free_result();   
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
                
                $sql = "insert into trhtrmpot_cmsbank(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_kegiatan, nm_kegiatan, kd_rek5,nm_rek5,nmrekan, pimpinan,alamat,no_voucher,rekening_tujuan,nm_rekening_tujuan,status_upload) 
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
                    $sql = "insert into trdtrmpot_cmsbank(no_bukti,kd_rek5,nm_rek5,nilai,kd_skpd,kd_rek_trans,ebilling,username)"; 
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

    function load_trm_pot(){
        $skpd = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        //$id=str_replace('123456789','/',$spp);
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
    
    function out_lalu(){
         $giat = $this->input->post('giat');
         $sp2d = $this->input->post('sp2d');
         $rek  = $this->input->post('rek');
         $nomor  = $this->input->post('nomor');
         $tgl  = $this->input->post('tgl');
         $skpd  = $this->input->post('skpd');
         $jenis  = $this->input->post('jenis');
         $sql = "SELECT b.no_bukti,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_subkegiatan,b.nm_subkegiatan,b.kd_rek5,b.nm_rek5,
                (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = b.kd_subkegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_kegiatan=b.kd_kegiatan AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> b.no_bukti AND d.tgl_bukti<=a.tgl_bukti AND d.jns_spp = a.jns_spp) AS lalu,
                (SELECT SUM(g.nilai) FROM trdspp g INNER JOIN trhspm h ON g.no_spp=h.no_spp INNER JOIN trhsp2d i ON h.no_spm=i.no_spm WHERE i.no_sp2d=b.no_sp2d) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_kegiatan = b.kd_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek5) AS anggaran FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti
                WHERE a.kd_skpd = '$skpd' AND b.kd_subkegiatan = '$giat' AND b.kd_rek5 = '$rek' AND a.tgl_bukti <> '$tgl' AND 
                b.no_sp2d = '$sp2d' AND a.no_bukti = '$nomor'";                           
         $query1 = $this->db->query($sql);        
         $row = $query1->row();
         $result[] = array('lalu' =>$row->rp);
         //$result = $row->rp;
         echo json_encode($result);
         //echo $result;
         $query1->free_result();
    }
    
    function load_sum_pot(){
        $skpd = $this->session->userdata('kdskpd');
        $spm    = $this->input->post('spm');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trspmpot where no_spm='$spm' AND kd_skpd='$skpd'");  
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
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }        
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        where $init_skpd and status_upload='0' and a.username='$user' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih,a.jns_spp FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join (
        select a.no_voucher,a.kd_skpd,a.username,sum(a.nilai) bersih from trdtransout_transfercms a where $init_skpd
        group by no_voucher,kd_skpd,a.username)c on c.no_voucher=a.no_voucher and c.kd_skpd=a.kd_skpd and c.username=a.username
        where $init_skpd and status_upload='0' and a.username='$user' $and         
        group by 
a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih,a.jns_spp        
        order by cast(a.no_voucher as int),a.kd_skpd");     
        
        /*$query1 = $this->db->query("SELECT top $rows a.*,b.* FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where left(a.kd_skpd,7)=left('$skpd',7) and status_upload='0' $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) and status_upload='0' $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd");     
        */
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
    
    function load_listbelum_validasi(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a 
        where $init_skpd and a.status_upload='1' and a.status_validasi='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload,d.bersih,a.jns_spp FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd and c.username=a.username
        left join (
        select a.username,a.no_voucher,a.kd_skpd,sum(a.nilai) bersih from trdtransout_transfercms a where $init_skpd
        group by username,no_voucher,kd_skpd)d on d.no_voucher=a.no_voucher and d.kd_skpd=a.kd_skpd and d.username=a.username
        where $init_skpd and a.status_upload='1' and status_validasi='0' $and  
        group by 
        a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload,d.bersih,a.jns_spp       
        order by a.kd_skpd,cast(a.no_voucher as int)");
        
        /*
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        where left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' and status_validasi='0' $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' and status_validasi='0' $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd"); */      
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'username' => $resulte['username'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'bersih' => number_format($resulte['bersih'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_pot' => $resulte['status_trmpot'],
                        'jns_spp' => $resulte['jns_spp']                                                         
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
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
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }        
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username 
        where $init_skpd $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join (
        select a.no_voucher,a.kd_skpd,a.username,sum(a.nilai) bersih from trdtransout_transfercms a where $init_skpd and a.username='$user'
        group by no_voucher,kd_skpd,username)c on c.no_voucher=a.no_voucher and c.kd_skpd=a.kd_skpd and c.username=a.username
        where $init_skpd and a.username='$user' $and    
        group by 
        a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih     
        order by cast(a.no_voucher as int),a.kd_skpd");     
        
        /*$query1 = $this->db->query("SELECT top $rows a.*,b.* FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where left(a.kd_skpd,7)=left('$skpd',7) $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd"); */  
        
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
                        'nm_kegiatan' => $resulte['nm_kegiatan']
                                                       
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
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }
        
        $sql = "SELECT c.no_upload,count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher and a.username=b.username
        where $init_skpd and a.status_upload='1' and a.status_validasi='0' and a.username='$user' $and group by c.no_upload" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.no_upload,c.no_upload_tgl,a.username FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher and a.username=b.username
        where $init_skpd and a.status_upload='1' and a.status_validasi='0' and a.username='$user' $and 
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
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
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
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
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
    
    function csv_cmsbank($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $user = $this->session->userdata('pcNama');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;        
        $init_skp = substr($skpd,0,17);
        
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
            
            if($init_skp=='1.02.0.00.0.00.01'){
                $sqlquery = $this->db->query("SELECT DISTINCT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
                where $init_skpd and a.no_upload='$nomor' and a.username='$user' and d.bank='05'");
            }else{
                $sqlquery = $this->db->query("SELECT DISTINCT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and left(d.kd_skpd,17)=left(b.kd_bp,17)
                where $init_skpd and a.no_upload='$nomor' and a.username='$user' and d.bank='05'");
            }
            
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
            $sqlquery = $this->db->query("SELECT DISTINCT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
            b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank a 
            left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
            left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
            left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
            where $init_skpd and a.no_upload='$nomor' and a.username='$user' and d.bank='05'");
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
        $init_skp = substr($skpd,0,17);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
            
            if($init_skp=='1.02.0.00.0.00.01'){
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl,e.bic FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
                left join ms_bank e on e.kode=d.bank
                where $init_skpd and a.no_upload='$nomor' and a.username='$user' and d.bank<>'05'");
            }else{
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl,e.bic FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and left(d.kd_skpd,17)=left(b.kd_bp,17)
                left join ms_bank e on e.kode=d.bank
                where $init_skpd and a.no_upload='$nomor' and a.username='$user' and d.bank<>'05'");                
            }    
            
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
            
            $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl,e.bic FROM trhupload_cmsbank a 
                left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload and a.username=b.username 
                left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher and a.username=c.username
                left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
                left join ms_bank e on e.kode=d.bank
                where $init_skpd and a.no_upload='$nomor' and a.username='$user' and d.bank<>'05'");
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
    
 function load_list_validasi(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a 
        where $init_skpd and a.status_upload='1' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd and c.username=a.username
        where $init_skpd and a.status_upload='1' $and         
        group by 
        a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload
        order by cast(a.no_voucher as int),a.kd_skpd"); 
        
        
        /*
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        where left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd"); 
        */
            
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'username' => $resulte['username'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_pot' => $resulte['status_trmpot']                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }

    function load_list_telahvalidasi(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_validasi='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }
        
        $sql = "SELECT a.no_bukti,count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where $init_skpd and status_upload='1' $and group by a.no_bukti" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.kd_skpd,a.no_voucher,a.tgl_voucher,a.ket,a.total,a.status_upload,a.status_validasi,
        a.tgl_upload,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,
        a.ket_tujuan,a.status_trmpot,c.no_upload,d.no_bukti FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        left join trvalidasi_cmsbank d on d.no_voucher = c.no_voucher and d.kd_bp = c.kd_bp
        where $init_skpd and a.status_upload='1' and a.status_validasi='1' $and 
        group by 
        a.kd_skpd,a.no_voucher,a.tgl_voucher,a.ket,a.total,a.status_upload,a.status_validasi,
        a.tgl_upload,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,
        a.ket_tujuan,a.status_trmpot,c.no_upload,d.no_bukti
        order by cast(d.no_bukti as int),a.tgl_validasi,a.kd_skpd");        
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],  
                        'no_bku' => $resulte['no_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_pot' => $resulte['status_trmpot']                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }

    function cetak_listtransaksi(){
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_user = $this->session->userdata('pcNama');
        $thn     = $this->session->userdata('pcThang');
        $tgl     = $this->uri->segment(3);
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd=left('$kd_skpd',17)+'.0000'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>".$kab."<br>LIST TRANSAKSI</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE ".strtoupper($this->tanggal_format_indonesia($tgl))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"14\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</td>
            </tr>
            </table>";
            
            
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr> 
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold;\">SKPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"32%\" style=\"font-size:12px;font-weight:bold;\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"4%\" style=\"font-size:12px;font-weight:bold;\">ST</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;border-top:solid 1px black;\">7</td>
            </tr>
            </thead>";
                      
           $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
           $cek_skpd1 = $cek_skpd->hasil;
           if($cek_skpd1==1){
                $init_skpd = "a.kd_skpd='$kd_skpd'";
                $init_skpd2= "kode='$kd_skpd'";
           }else{
                $cek_skpd = substr($kd_skpd,18,4);           
                if($cek_skpd==0000){
                $init_skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
                $init_skpd2= "left(kode,17)=left('$kd_skpd',17)";
                }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
                $init_skpd2= "left(kode,17)=left('$kd_skpd',17)";
                }              
           }           
           
           $no=0;
           $tot_terima=0;
           $tot_keluar=0;
           $sql = "select z.* from (
            select '1' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,a.no_sp2d kegiatan,'' rekening, a.ket, 0 terima, 0 keluar, a.jns_spp, a.status_upload
            from trhtransout_cmsbank a where year(a.tgl_voucher) = '$thn' and a.username='$kd_user' and a.tgl_voucher='$tgl' and $init_skpd
            UNION
            select '2' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,b.kd_sub_kegiatan kegiatan,b.kd_rek6 rekening, b.nm_sub_kegiatan+', '+b.nm_rek6, 0 terima, b.nilai keluar, a.jns_spp, '' status_upload
            from trhtransout_cmsbank a 
            left join trdtransout_cmsbank b on b.no_voucher=a.no_voucher and b.kd_skpd=a.kd_skpd and a.username=b.username
            where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.username='$kd_user' and $init_skpd
            UNION
            select '3' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,'Rek. Tujuan : '+a.bank_tujuan kegiatan,'' rekening, RTRIM(a.rekening_tujuan)+' , AN : '+RTRIM(a.nm_rekening_tujuan), 0 terima, a.nilai keluar, '' jns_spp, '' status_upload
            from trdtransout_transfercms a where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.username='$kd_user' and $init_skpd           
            UNION
            select '4' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,b.kd_sub_kegiatan kegiatan,c.kd_rek6 rekening, 'Terima '+c.nm_rek6, c.nilai terima, 0 keluar, '' jns_spp, '' status_upload
            from trhtransout_cmsbank a 
            inner join trhtrmpot_cmsbank b on b.no_voucher=a.no_voucher and b.kd_skpd=a.kd_skpd and a.username=b.username
            inner join trdtrmpot_cmsbank c on b.no_bukti=c.no_bukti and b.kd_skpd=c.kd_skpd and c.username=b.username
            where year(a.tgl_voucher) = '$thn' and a.username='$kd_user' and a.tgl_voucher='$tgl' and $init_skpd
            )z order by z.kd_skpd,z.tgl_voucher,cast (z.no_voucher as int), z.urut";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;     
                        
            if($row->urut=='1'){                            
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:none;\">".$row->no_voucher."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kd_skpd."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kegiatan.".".$row->rekening."</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->ket."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->status_upload."</td>                                       
                 </tr>";
                 }else if($row->urut=='3'){                            
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kegiatan."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->ket."&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".number_format($row->keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\"></td>                                       
                 </tr>";
                 }else{
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".$row->kegiatan.".".$row->rekening."</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".$row->ket."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".number_format($row->terima,2)."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".number_format($row->keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>                                        
                 </tr>";
                 }
                 
                 if($row->urut!='3'){
                    $tot_terima=$tot_terima+$row->terima; 
                    $tot_keluar=$tot_keluar+$row->keluar;  
                 }                 
                                  
             }
             
            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK') a
            where tgl<='$tgl' and $init_skpd2";  
    
        $hasil=$this->db->query($asql);
        $bank=$hasil->row();
        $keluarbank=$bank->keluar;
        $terimabank=$bank->terima;
        $saldobank=$terimabank-$keluarbank;     
        
        $saldoakhirbank = (($saldobank+$tot_terima)-$tot_keluar);
            
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border-top:1px solid black;\">JUMLAH</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">".number_format($tot_terima,2)."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">".number_format($tot_keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"9\" style=\"font-size:11px;border:none;\"><br/></td>                                                   
                 </tr> 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Saldo Sampai Dengan Tanggal ".$this->tanggal_format_indonesia($tgl).", </td>                                                   
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($saldobank,2)."</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Terima</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($tot_terima,2)."</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Keluar</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($tot_keluar,2)."</td>                                                   
                 </tr>                                 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\"><hr/></td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Perkiraan Akhir Saldo, </td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($saldoakhirbank,2)."</td>                                                   
                 </tr>                                 
                                                  
            </table>";    
            
        $data['prev']= $cRet;    
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }
    
    function cetak_listsimpanan_bank(){
        $kd_skpd = $this->session->userdata('kdskpd');
        $thn     = $this->session->userdata('pcThang');
        $tgl     = $this->uri->segment(3);
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd=left('$kd_skpd',17)+'.0000'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>".$kab."<br>LIST PENYETORAN DANA SIMPANAN BANK</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE ".strtoupper($this->tanggal_format_indonesia($tgl))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"14\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</td>
            </tr>
            </table>";
            
            
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr> 
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold;\">SKPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Rekening Tujuan </td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"32%\" style=\"font-size:12px;font-weight:bold;\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"4%\" style=\"font-size:12px;font-weight:bold;\">ST</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;border-top:solid 1px black;\">7</td>
            </tr>
            </thead>";
           
           $no=0;
           $tot_terima=0;
           $tot_keluar=0;
           $sql = "select z.* from (
            select '1' urut,a.kd_skpd,a.tgl_bukti,a.no_bukti, a.rekening_tujuan+', an. '+a.nm_rekening_tujuan [tujuan], a.keterangan, a.nilai keluar, a.status_upload
            from tr_setorpelimpahan_bank_cms a 
            where year(a.tgl_bukti) = '$thn' and a.tgl_bukti='$tgl' and LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)             
            )z order by z.kd_skpd,z.tgl_bukti,z.no_bukti,z.urut";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;     
                        
            if($row->urut=='1'){                            
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->no_bukti."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kd_skpd."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->tujuan."</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->keterangan."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".number_format($row->keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->status_upload."</td>                                       
                 </tr>";
                 }
                 
                $tot_keluar=$tot_keluar+$row->keluar;              
                                  
             }
            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK') a
            where tgl<='$tgl' and left(kode,17)=left('$kd_skpd',17)";
    
        $hasil=$this->db->query($asql);
        $bank=$hasil->row();
        $keluarbank=$bank->keluar;
        $terimabank=$bank->terima;
        $saldobank=$terimabank-$keluarbank;     
        
        $saldoakhirbank = (($saldobank+$tot_terima)-$tot_keluar);
            
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border-top:1px solid black;\">JUMLAH</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">".number_format($tot_keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"9\" style=\"font-size:11px;border:none;\"><br/></td>                                                   
                 </tr>                                                  
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\"><hr/></td>                                                   
                 </tr>                                                        
            </table>";    
            
        $data['prev']= $cRet;    
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }
    
    //penerimaan
    
    function simpan_sts_pendapatan(){
        
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        //$nomor_kas       = $this->input->post('lckas');
        //$tgl_kas       = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
        $pengirim    = $this->input->post('pengirim');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
        $sumber      = $this->input->post('sts');  
        $sp2d        = $this->input->post('sp2d');  
        $jns_cp        = $this->input->post('jns_cp');  
        $no_terima   = $this->input->post('no_terima');  
        $sgiat       = $this->input->post('sgiat');  
        $surut        = $this->input->post('surut');
        $sbank        = $this->input->post('bankk');
        
        $rek_awal = trim($this->input->post('rek_awal'));            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $init_ket = $this->input->post('ketcms');
        $stt_val  = 0;
        $stt_up   = 0;

        $nmskpd      = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
        $last_update = date('Y-m-d H:i:s');
      // $last_update = " ";
        $msg = array();
        if ($tabel == 'trhkasin_pkd_cms') {
            
            $sql = "delete from trhkasin_pkd_cms where kd_skpd='$skpd' and no_sts='$nomor'";
            $asg = $this->db->query($sql);
            
            
            if ($asg){
                if($jnsrek==5){
                 $sql = "insert into trhkasin_pkd_cms(no_kas,no_sts,kd_skpd,tgl_sts,tgl_kas,keterangan,total,kd_bank,kd_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp) 
                        values('$nomor_kas','$nomor','$skpd','$tgl','$tgl_kas','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','1','$sp2d','$jns_cp')";
                } else{
                 $sql = "insert into trhkasin_pkd_cms(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima,urut,bank,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,ket_tujuan) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$pengirim','0','$sp2d','$jns_cp','$no_terima','$surut','$sbank','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$init_ket')";
                }
               
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd_cms where no_sts='$nomor' AND kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd_cms(kd_skpd,no_sts,kd_rek5,rupiah,kd_kegiatan,no_terima,kd_rek6) values $lcnilaidet";
                        $asg = $this->db->query($sql); 
                        
                    }                
                }            
            } 
            echo '2';
        }
         
    }
       
    function update_sts_pendapatan_ag(){
        
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        $nohide      = $this->input->post('nohide');
        //$nomor_kas   = $this->input->post('lckas');
        //$tgl_kas     = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
        $pengirim      = $this->input->post('pengirim');
        $sumber      = $this->input->post('sts');  
        $sp2d        = $this->input->post('sp2d');  
        $jns_cp      = $this->input->post('jns_cp');  
        $no_terima   = $this->input->post('no_terima');  
        $nmskpd      = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
        $curut        = $this->input->post('surut');  
        $cbank      = $this->input->post('bankk');
        
        $rek_awal = trim($this->input->post('rek_awal'));            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $init_ket = $this->input->post('ketcms');
        $stt_val  = 0;
        $stt_up   = 0;  
        
        $last_update = date('d-m-y H:i:s');
      // $last_update = " ";
        $msg = array();        
            
            $sql = "delete from trhkasin_pkd_cms where kd_skpd='$skpd' and no_sts='$nohide'";
            $asg = $this->db->query($sql);
            
                
                 $sql = "insert into trhkasin_pkd_cms(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima,urut,bank,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,ket_tujuan) 
                        values('$nomor','$skpd','$tgl','$ket','$total','','$giat','$jnsrek','','$pengirim','0','','','$no_terima','$curut','$cbank','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$init_ket')";
                
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd_cms where no_sts='$nohide' AND kd_skpd='$skpd' ";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd_cms (kd_skpd,no_sts,kd_rek5,rupiah,kd_kegiatan,no_terima,kd_rek6) values $lcnilaidet";
                        $asg = $this->db->query($sql); 
                        
                    }                
                }            
            
            echo '2';    
        
    }   
    
    function load_sts() {
        $kd_skpd     = $this->session->userdata('kdskpd');         
            $par = "a.kd_skpd='$kd_skpd'";
            $par2 = "kd_skpd='$kd_skpd'";        
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and (upper(a.no_sts) like upper('%$kriteria%') or a.tgl_sts like '%$kriteria%' or a.kd_skpd like'%$kriteria%' or
            upper(a.keterangan) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd_cms a where $par and a.jns_trans='4' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
        $sql = "
        SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd_cms a where $par and a.jns_trans='4' 
        $where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd_cms where $par2 and jns_trans='4' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
        ";
        
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                      
           $bidang = "00";
           
           $par_sts = $resulte['no_sts'];
           $par_sts_1 = explode("/",$par_sts);
           $par_rekk = $par_sts_1[4];
           $stt = $this->db->query("select nm_rek5 as row from ms_rek5 where kd_rek5='$par_rekk'")->row();
           $rek_rek = $stt->row;
           
           
            $row[] = array( 
                        'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nmrek' => $rek_rek,                      
                        'bidang' => $bidang,
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
                        'sumber' => $resulte['sumber'],
                        'no_terima' => $resulte['no_terima'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'bank' => $resulte['bank'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_up' => $resulte['status_upload'],
                        'status_val' => $resulte['status_validasi']
                        );
                        $ii++;
                }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
           
    }
    
    
    
    
    
    
    function load_sts_tgl() {
        $kd_skpd     = $this->session->userdata('kdskpd');         
            $par = "a.kd_skpd='$kd_skpd'";
            $par2 = "kd_skpd='$kd_skpd'";        
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and a.tgl_sts ='$kriteria'";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd_cms a where $par and a.jns_trans='4' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
        $sql = "
        SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd_cms a where $par and a.jns_trans='4' 
        $where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd_cms where $par2 and jns_trans='4' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
        ";
        
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                      
           $bidang = "00";
           
           /*$par_sts = $resulte['no_sts'];
           $par_sts_1 = explode("/",$par_sts);
           $par_rekk = $par_sts_1[4];
           $stt = $this->db->query("select nm_rek5 as row from ms_rek5 where kd_rek5='$par_rekk'")->row();
           $rek_rek = $stt->row;*/
           
           
            $row[] = array( 
                        'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nmrek' => '',//$rek_rek,                      
                        'bidang' => $bidang,
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
                        'sumber' => $resulte['sumber'],
                        'no_terima' => $resulte['no_terima'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'bank' => $resulte['bank'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_up' => $resulte['status_upload'],
                        'status_val' => $resulte['status_validasi']
                        );
                        $ii++;
                }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
           
    }
    
    function load_dsts() {    
       // $kriteria = '0012.a/1.20.05';
        $kriteria = $this->input->post('no');
        //$kriteria = $this->uri->segment(3);
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT a.*, (select nm_rek5 from ms_rek5 where kd_rek5 = a.kd_rek5) as nm_rek 
        from trdkasin_pkd_cms a where a.no_sts = '$kriteria' and a.kd_skpd='$skpd' order by a.no_sts";
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'nm_rek' => $resulte['nm_rek'],
                        'rupiah' =>  number_format($resulte['rupiah'],2,'.',','),
                        'no_terima' => $resulte['no_terima']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function list_no_terima() {
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        
        $sql   = "select * from tr_terima where kd_skpd='$kd_skpd' 
        AND no_terima NOT IN(select ISNULL(no_terima,'') no_terima from trdkasin_pkd_cms where kd_skpd='$kd_skpd')
        AND no_terima NOT IN(select ISNULL(no_terima,'') no_terima from trdkasin_pkd where kd_skpd='$kd_skpd')  
        AND no_terima LIKE '%$lccr%' order by tgl_terima,no_terima";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'no_terima' => $resulte['no_terima'],  
                        'tgl_terima' => $resulte['tgl_terima'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nilai' => number_format($resulte['nilai']),
                        'keterangan' => $resulte['keterangan']                      
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    
    }
    
    function config_sts(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT MAX(z.nilai) as nilai from(
SELECT isnull(max(urut),0) as nilai FROM trhkasin_pkd a WHERE a.kd_skpd = '$skpd'
UNION
SELECT isnull(max(urut),0) as nilai FROM trhkasin_pkd_cms a WHERE a.kd_skpd = '$skpd'
)z"; 
        $query1 = $this->db->query($sql);                       
       
        foreach($query1->result_array() as $resulte)
        { 
            $n = $resulte['nilai'];
            if($n==null){
                $n=0;
            }
            
            $result = array(                                
                        'nomor' => $n + 1
                        );
                        
        }
        echo json_encode($result);  
    }
    
    function hapus_sts(){
        $nomor = $this->input->post('no');
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "delete from trhkasin_pkd_cms where no_sts='$nomor' AND kd_skpd='$kd_skpd' ";
        $asg = $this->db->query($sql);
        $sql = "delete from trdkasin_pkd_cms where no_sts='$nomor'  AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        echo '1';                
    }
    
    function cetak_sts_cms(){       
        $tgl_sts = $this->uri->segment(3);       
        $kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $cRet='';
        $lcpemda = $kab;
        
        $sql = "SELECT top 1 a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
                (SELECT nama FROM ms_bank WHERE kode = a.kd_bank) AS nm_bank
                FROM trhkasin_pkd_cms a WHERE a.kd_skpd = '$kd_skpd' and a.tgl_sts='$tgl_sts'";
                
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();
        $lctujuan= $trh->nm_rekening_tujuan; 
        $lcbank = $trh->bank_tujuan;        
        $lcrek = $trh->rekening_tujuan;
        $lcskpd = $trh->nm_skpd;
        $lctglsts = $trh->tgl_sts;              
        
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: none;\">$lcpemda</td></tr>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: none;border-bottom:solid 1px black;\">
                        LIST SURAT TANDA SETORAN NON TUNAI (CMS)</td></tr>
                     </thead></table><br>";       
              
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td>
                <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td width=\"10%\">SKPD</td>
                        <td width=\"50%\">: $lcskpd</td>
                       <td width=\"15%\">REK. TUJUAN</td>
                        <td width=\"35%\">: $lcrek - $lcbank</td>
                    </tr>
                    <tr>
                        <td width=\"10%\">PERIODE</td>
                        <td width=\"50%\">: ".$this->tukd_model->tanggal_format_indonesia($lctglsts)."</td>
                       <td width=\"15%\"></td>
                        <td width=\"35%\"></td>
                    </tr>                    
                </table>      
            </td>
          </tr> 
          <tr>
          <td><br/></td>
          </tr>         
          <tr>
            <td valign=\"top\">
            <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
              <tr>
                <td width=\"4%\" height=\"28\" bgcolor=\"#CCCCCC\" align=\"center\"><b>No</b></td>
                <td width=\"27%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>STS</b></td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Kode Rekening</b></td>
                <td width=\"27%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Uraian Rincian Objek</b></td>
                <td width=\"13%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Jumlah</b></td>
                <td width=\"3%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>ST</b></td>
              </tr>";
           
           $sql = "SELECT z.* FROM(
SELECT '1' urut,a.no_sts sts,a.kd_skpd,a.no_sts,'' kd_rek5,
a.keterangan AS nm_rek5,a.total AS rupiah,a.status_upload
FROM trhkasin_pkd_cms a WHERE a.kd_skpd = '$kd_skpd' and a.tgl_sts='$tgl_sts'
UNION
SELECT '2' urut,a.no_sts sts,a.kd_skpd,'' no_sts,a.kd_rek5,
(SELECT nm_rek5 FROM ms_rek5 WHERE kd_rek5 = a.kd_rek5) AS nm_rek5,a.rupiah,'' status_upload
FROM trdkasin_pkd_cms a left join trhkasin_pkd_cms b on b.no_sts=a.no_sts 
and a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$kd_skpd' and b.tgl_sts='$tgl_sts'
)z order by z.sts,z.urut";
                
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        $total_hasil = 0;
        foreach ($hasil->result() as $row)
        {
           if($row->urut=='1'){
           $lntotal = $lntotal + $row->rupiah;     
           }
           
            
            if($row->urut=='1'){
                $lcno = $lcno + 1;
            $cRet .=" <tr>
                        <td align=\"center\">$lcno</td>
                        <td align=\"left\" >$row->no_sts</td>
                        <td align=\"center\" width=\"3%\"></td>
                        <td align=\"center\" width=\"3%\"></td>
                        <td align=\"center\" width=\"3%\"></td>
                        <td align=\"center\" width=\"3%\"></td>
                        <td align=\"center\" width=\"3%\"></td>
                        <td>$row->nm_rek5</td>
                        <td align=\"right\"><b>".number_format($row->rupiah,2)."</b></td>
                        <td align=\"center\">$row->status_upload</td>
                      </tr>";   
            }else{
            $cRet .=" <tr>
                        <td align=\"center\"></td>
                        <td align=\"left\">$row->no_sts</td>
                        <td align=\"center\" width=\"3%\">".substr($row->kd_rek5,0,1)."</td>
                        <td align=\"center\" width=\"3%\">".substr($row->kd_rek5,1,1)."</td>
                        <td align=\"center\" width=\"3%\">".substr($row->kd_rek5,2,1)."</td>
                        <td align=\"center\" width=\"3%\">".substr($row->kd_rek5,3,2)."</td>
                        <td align=\"center\" width=\"3%\">".substr($row->kd_rek5,5,2)."</td>
                        <td>$row->nm_rek5</td>
                        <td align=\"right\">".number_format($row->rupiah,2)."</td>
                        <td align=\"center\">$row->status_upload</td>
                      </tr>";    
            }                                              
        }   
        
            $sql_lalu_telah_validasi = $this->db->query("SELECT isnull(sum(a.rupiah),0) total_lalu
FROM trdkasin_pkd a left join trhkasin_pkd b on b.no_sts=a.no_sts 
and a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '1.03.01.00' and b.tgl_sts<'2018-1-2'")->row();
            $total_laluu = $sql_lalu_telah_validasi->total_lalu;
            $total_hasil = $total_laluu+$lntotal;
            $cRet .="
            <tr>
                <td bgcolor=\"#CCCCCC\" colspan=\"8\" align=\"right\"><b>Total</b></td>                
                <td bgcolor=\"#CCCCCC\" align=\"right\"><b>".number_format($lntotal,2)."</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\"></td>
            </tr>
            </table>
            </td>
          </tr>
          <tr>
            <td height=\"20\" align=\"center\"></td>
          </tr>           
          <tr>
          <td>
            <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td colspan=\"3\" width=\"20%\">Perkiraan Total Penyetoran,</td>                    
                       <td width=\"80%\"></td>                        
                    </tr>
                    <tr>
                        <td width=\"9%\">Lalu</td>
                        <td width=\"5%\">: Rp.</td>
                        <td width=\"15%\" align=\"right\"> <b>".number_format($total_laluu,2)."</b></td>
                       <td width=\"71%\" align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *) Yang telah di-validasi Kasda</td>                        
                    </tr> 
                    <tr>
                        <td width=\"9%\">Hari ini</td>
                        <td width=\"5%\">: Rp.</td>
                        <td width=\"15%\" align=\"right\" style=\"border-bottom:solid 1px black;\"> <b>".number_format($lntotal,2)."</b></td>
                       <td width=\"71%\"></td>                        
                    </tr> 
                    <tr>
                        <td width=\"9%\">Total</td>
                        <td width=\"5%\">: Rp.</td>
                        <td width=\"15%\" align=\"right\"> <b>".number_format($total_hasil,2)."</b></td>
                       <td width=\"71%\"></td>                        
                    </tr>                                     
                </table>          
          </td>
          </tr>         
        </table>";

        $data['prev']= $cRet;    
        //echo $cRet;
        $this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
        
    }
    
    function cetak_sts(){
        //$b = $this->uri->segment(3);
        $lcnosts = str_replace('123456789','/',$this->uri->segment(3));
        $lcttd2 = str_replace('a',' ',$this->uri->segment(5));
        $lcttd1 = str_replace('a',' ',$this->uri->segment(4));
        $kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $cRet='';
        $lcpemda = $kab;
        $sqlttd1="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE nip ='$lcttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;  
                    $pangkat=$rowttd->pangkat;  
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                }
                
        $sqlttd2="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kd_skpd= '$kd_skpd' AND kode='BP'";
                 $sqlttd2=$this->db->query($sqlttd2);
                 foreach ($sqlttd2->result() as $rowttd2)
                {
                    $nip2=$rowttd2->nip; 
                    $pangkat2=$rowttd2->pangkat;  
                    $nama2= $rowttd2->nm;
                    $jabatan2  = $rowttd2->jab;
                }
        $sql = "SELECT a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
                (SELECT nama FROM ms_bank WHERE kode = a.kd_bank) AS nm_bank
                FROM trhkasin_pkd a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();

        $rupiah = $this->tukd_model->terbilang($trh->total);
        $lcbank = $trh->nm_bank;
        $lcrek = $trh->rek_bank;
        $lcskpd = $trh->nm_skpd;
        $lctglsts = $trh->tgl_sts;
        $jns_bank = $trh->bank;
        
        if($jns_bank=="TN"){
            $jns_bank2="TUNAI";
        }else{
            $jns_bank2="BANK";
        }
        
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">$lcpemda</td></tr>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">SURAT TANDA SETORAN</td></tr>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">(STS)</td></tr>
                     </thead></table><br>";       
              
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td>
                <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td width=\"10%\">No STS</td>
                        <td width=\"50%\">: $lcnosts</td>
                       <td width=\"10%\">JENIS</td>
                        <td width=\"40%\">: $jns_bank2 $lcbank</td>
                    </tr>
                    <tr>
                        <td width=\"10%\">SKPD</td>
                        <td width=\"50%\">: $lcskpd</td>
                        <td width=\"10%\">No Rekening</td>
                        <td width=\"40%\">: 1001002830</td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td>
                <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td width=\"30%\">Harap diterima uang sebesar <br>(dengan huruf)</td>
                        <td width=\"70%\" valign=\"top\"><i>( $rupiah )</i></td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td valign=\"top\">Dengan rincian penerimaan sebagai berikut<br>
            <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
              <tr>
                <td width=\"4%\" height=\"28\" bgcolor=\"#CCCCCC\" align=\"center\"><b>No</b></td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Kode Rekening</b></td>
                <td width=\"48%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Uraian Rincian Objek</b></td>
                <td width=\"50%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Jumlah</b></td>
              </tr>";
           
           $sql = "SELECT a.*,(SELECT nm_rek5 FROM ms_rek5 WHERE kd_rek5 = a.kd_rek5) AS nm_rek5
                    FROM trdkasin_pkd a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        foreach ($hasil->result() as $row)
        {
           $lntotal = $lntotal + $row->rupiah;     
           $lcno = $lcno + 1;
           $cRet .=" <tr>
                        <td align=\"center\">$lcno</td>
                        <td width=\"3%\">".substr($row->kd_rek5,0,1)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,1,1)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,2,1)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,3,2)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,5,2)."</td>
                        <td>$row->nm_rek5</td>
                        <td align=\"right\">".number_format($row->rupiah)."</td>
                      </tr>";     
            
        }
            $cRet .="
            <tr>
                <td colspan=\"7\" align=\"right\">Jumlah</td>                
                <td align=\"right\">".number_format($lntotal)."</td>
                
            </tr>
            </table>
            </td>
          </tr>
          
          <tr>
            <td height=\"30\" align=\"center\" style=\"font-size:12px\">Uang tersebut diterima pada tanggal ".$this->tukd_model->tanggal_format_indonesia($lctglsts)."</td>
          </tr>
          <tr>
            <td height=\"60\" align=\"center\"></td>
          </tr>
          <tr>
            <td height=\"56\">
                <table style=\"border-collapse:collapse;\" width=\"700\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>Mengetahui<br>$jabatan</b></td>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>$jabatan2</b></td>
                  </tr>
                  <tr>
                  <td height=\"60\" colspan =\"2\" ></td>
                  </tr>
                  <tr>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>$nama<br>NIP.$nip</b></td>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>$nama2<br>NIP.$nip2</b></td>
                  </tr>                  
                </table>
            </td>
          </tr>
        </table>";

        $data['prev']= $cRet;    
        $this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
        
    }
    
    function cetak_stss_cms(){
        //$b = $this->uri->segment(3);
        $lcnosts = str_replace('123456789','/',$this->uri->segment(3));
        $lcttd2 = str_replace('a',' ',$this->uri->segment(5));
        $lcttd1 = str_replace('a',' ',$this->uri->segment(4));
        $kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $cRet='';
        $lcpemda = $kab;
        $sqlttd1="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE nip ='$lcttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;  
                    $pangkat=$rowttd->pangkat;  
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                }
                
        $sqlttd2="SELECT distinct nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kd_skpd= '$kd_skpd' AND kode='BP'";
                 $sqlttd2=$this->db->query($sqlttd2);
                 foreach ($sqlttd2->result() as $rowttd2)
                {
                    $nip2=$rowttd2->nip; 
                    $pangkat2=$rowttd2->pangkat;  
                    $nama2= $rowttd2->nm;
                    $jabatan2  = $rowttd2->jab;
                }
        $sql = "SELECT a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
                (SELECT nama FROM ms_bank WHERE kode = a.kd_bank) AS nm_bank
                FROM trhkasin_pkd_cms a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();

        $rupiah = $this->tukd_model->terbilang($trh->total);
        $lcbank = $trh->nm_bank;
        $lcrek = $trh->rek_bank;
        $lcskpd = $trh->nm_skpd;
        $lctglsts = $trh->tgl_sts;
        $jns_bank = $trh->bank;
        
        if($jns_bank=="TN"){
            $jns_bank2="TUNAI";
        }else{
            $jns_bank2="BANK";
        }
        
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">$lcpemda</td></tr>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">SURAT TANDA SETORAN</td></tr>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">(STS)</td></tr>
                     </thead></table><br>";       
              
        
     
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td>
                <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td width=\"10%\">No STS</td>
                        <td width=\"50%\">: $lcnosts</td>
                       <td width=\"10%\">JENIS</td>
                        <td width=\"40%\">: $jns_bank2 $lcbank</td>
                    </tr>
                    <tr>
                        <td width=\"10%\">SKPD</td>
                        <td width=\"50%\">: $lcskpd</td>
                        <td width=\"10%\">No Rekening</td>
                        <td width=\"40%\">: 1001002830</td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td>
                <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td width=\"30%\">Harap diterima uang sebesar <br>(dengan huruf)</td>
                        <td width=\"70%\" valign=\"top\"><i>( $rupiah )</i></td>
                    </tr>
                </table>      
            </td>
          </tr>
          <tr>
            <td valign=\"top\">Dengan rincian penerimaan sebagai berikut<br>
            <table  style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
              <tr>
                <td width=\"4%\" height=\"28\" bgcolor=\"#CCCCCC\" align=\"center\"><b>No</b></td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Kode Rekening</b></td>
                <td width=\"48%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Uraian Rincian Objek</b></td>
                <td width=\"50%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Jumlah</b></td>
              </tr>";
           
           $sql = "SELECT a.*,(SELECT nm_rek5 FROM ms_rek5 WHERE kd_rek5 = a.kd_rek5) AS nm_rek5
                    FROM trdkasin_pkd_cms a WHERE no_sts = '$lcnosts'";
                
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        foreach ($hasil->result() as $row)
        {
           $lntotal = $lntotal + $row->rupiah;     
           $lcno = $lcno + 1;
           $cRet .=" <tr>
                        <td align=\"center\">$lcno</td>
                        <td width=\"3%\">".substr($row->kd_rek5,0,1)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,1,1)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,2,1)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,3,2)."</td>
                        <td width=\"3%\">".substr($row->kd_rek5,5,2)."</td>
                        <td>$row->nm_rek5</td>
                        <td align=\"right\">".number_format($row->rupiah)."</td>
                      </tr>";     
            
        }
            $cRet .="
            <tr>
                <td colspan=\"7\" align=\"right\">Jumlah</td>                
                <td align=\"right\">".number_format($lntotal)."</td>
                
            </tr>
            </table>
            </td>
          </tr>
          
          <tr>
            <td height=\"30\" align=\"center\" style=\"font-size:12px\">Uang tersebut diterima pada tanggal ".$this->tukd_model->tanggal_format_indonesia($lctglsts)."</td>
          </tr>
          <tr>
            <td height=\"60\" align=\"center\"></td>
          </tr>
          <tr>
            <td height=\"56\">
                <table style=\"border-collapse:collapse;\" width=\"700\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>Mengetahui<br>$jabatan</b></td>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>$jabatan2</b></td>
                  </tr>
                  <tr>
                  <td height=\"60\" colspan =\"2\" ></td>
                  </tr>
                  <tr>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>$nama<br>NIP.$nip</b></td>
                    <td width=\"50%\" align=\"center\" style=\"font-size:14px\"><b>$nama2<br>NIP.$nip2</b></td>
                  </tr>                  
                </table>
            </td>
          </tr>
        </table>";

        $data['prev']= $cRet;    
        $this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
        
    }
    
    //sts upload
    
    function load_liststsbelum_upload(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_sts='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhkasin_pkd_cms a left join trdkasin_pkd_cms b on b.kd_skpd=a.kd_skpd and a.no_sts=b.no_sts 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.* FROM trhkasin_pkd_cms a 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='0' $and 
        order by cast(a.urut as int),a.kd_skpd");       
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }
    
    function load_liststs_upload(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_sts='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhkasin_pkd_cms a left join trdkasin_pkd_cms b on b.kd_skpd=a.kd_skpd and a.no_sts=b.no_sts 
        where left(a.kd_skpd,17)=left('$skpd',17) $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.* FROM trhkasin_pkd_cms a 
        where left(a.kd_skpd,17)=left('$skpd',17) $and 
        order by cast(a.urut as int),a.kd_skpd");       
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }
    
    function simpan_uploadcms_sts(){
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

    if($tabel == 'trdupload_sts_cmsbank') {
            // Simpan Detail //                       
                $sql = "delete from trhupload_sts_cmsbank where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                $sql = "delete from trdupload_sts_cmsbank where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdupload_sts_cmsbank(no_sts,tgl_sts,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,no_upload_tgl)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    $skpd = $this->session->userdata('kdskpd'); 
                    $sql = "insert into trhupload_sts_cmsbank(no_upload,tgl_upload,kd_skpd,total,no_upload_tgl,username) values ('$nomor','$update','$skpd','$total','$urut_tgl','$usern')";
                    $asg = $this->db->query($sql);
                    
                    $sql = "UPDATE
                            trhkasin_pkd_cms
                            SET trhkasin_pkd_cms.status_upload = Table_B.status_upload,
                                 trhkasin_pkd_cms.tgl_upload = Table_B.tgl_upload
                        FROM trhkasin_pkd_cms     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_sts,b.kd_bp from trhupload_sts_cmsbank a left join 
                        trdupload_sts_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_skpd='$skpd' and a.no_upload='$nomor') AS Table_B ON trhkasin_pkd_cms.no_sts = Table_B.no_sts AND trhkasin_pkd_cms.kd_skpd = Table_B.kd_skpd
                        where left(trhkasin_pkd_cms.kd_skpd,17)=left('$skpd',17)
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
    
    
    function csv_cmsbank_sts($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='OB';                 
        
        $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=a.kd_skpd) as nm_skpd,
        b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,b.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_sts_cmsbank a left join trdupload_sts_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.no_upload='$nomor'");
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
            $nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            $rrekawal = $resulte['rekening_awal'];
            $rrektujuan = $resulte['rekening_tujuan'];            
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($rrekawal)).";".rtrim($resulte['nm_rekening_tujuan']).";".str_replace(" ","",rtrim($rrektujuan)).";".$nilai.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }
    
    function load_hdraf_upload_sts(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhupload_sts_cmsbank a
        where left(a.kd_skpd,17)=left('$skpd',17) $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.* FROM trhupload_sts_cmsbank a               
        where left(a.kd_skpd,17)=left('$skpd',17) $and 
        and a.no_upload not in (SELECT top $offset a.no_upload FROM trhupload_sts_cmsbank a  
        WHERE left(a.kd_skpd,17)=left('$skpd',17) $and)
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
    
    function load_total_upload_sts($tgl=''){
       $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
                        SUM (b.rupiah) AS total_upload
                    FROM
                        trhkasin_pkd_cms a
                    JOIN trdkasin_pkd_cms b ON a.no_sts = b.no_sts and a.kd_skpd = b.kd_skpd
                    WHERE
                        left(a.kd_skpd,17) = left('$kode',17)
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
     
    function load_draf_upload_sts(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.no_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhupload_sts_cmsbank a left join trdupload_sts_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,b.* FROM trhupload_sts_cmsbank a left join trdupload_sts_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) $and 
        and a.no_upload not in (SELECT top $offset a.no_upload FROM trhupload_sts_cmsbank a  
        WHERE left(a.kd_skpd,17)=left('$skpd',17) $and order by cast(a.no_upload as int))
        order by cast(a.no_upload as int),a.kd_skpd");      
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2),
                        'viewtotal' => number_format($resulte['nilai'],2),
                        'nilai' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
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
    
    function simpan_ststrstbp(){
    
    $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
    $skpd = $this->session->userdata('kdskpd');
    $tgl = $_POST['xtgl'];
    $skpd_rep = str_replace(".","",$skpd);
    $tgl_rep = str_replace("-","",$tgl);
    $fileName = $tgl_rep.'_'.$skpd_rep.'_'.$_FILES['file']['name'];
         
        $config['upload_path'] = './download/terima/'; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['max_size'] = 10000;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        $upl = $this->upload->do_upload('file');
        if(!($upl)){
        $this->upload->display_errors();    
        }
             
        $media = $this->upload->data('file');
        $inputFileName = './download/terima/'.$media['file_name'];
         
        try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                
                //format nomor
                $formatcno = $_POST['xjns_nomor'];
                
                $cno = $_POST['xnomor_urut'];
                $cno_ttp = $_POST['ynomor_urut'];
                $cskpd = $_POST['xskpd'];
                $ctgl = $_POST['xtgl'];
                $cjns = $_POST['xjenis'];
                $cgiat = substr($cskpd,0,4).'.'.$cskpd.'.'.'00.001';                                
                $cjns_penerimaan = $_POST['xjns_penerimaan'];
                
                $tgl = explode("-",$ctgl);
                $bln = $tgl[1];
                $thn = $tgl[0];
                $init8='';
                
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
             
            for ($row = 1; $row <= $highestRow; $row++){
                
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
                
                if($formatcno=='1'){                
                $nmor = $cno+$row;
                $nmorttp = $cno_ttp+$row;
                $nomor_tbp = $nmor.'/'.$cjns.'/'.'TBP'.'/'.$cskpd.'/'.$bln.'/'.$thn;
                $nomor_ttp = $nmorttp.'/'.'PTP'.'/'.$cskpd.'/'.$bln.'/'.$thn;
                
                $init1 = $nomor_tbp;
                $init2 = $ctgl;//$rowData[0][1];
                $init3 = $nomor_ttp;
                $init4 = '1';
                $init5 = $cskpd;
                $init6 = $cgiat;
                $init7 = $rowData[0][0];
                $init8 = $this->tukd_model->get_nama($rowData[0][0],'map_lo','ms_rek5','kd_rek5');
                $init9 = $rowData[0][1];                
                $init10 = $rowData[0][2];
                
                }else{
                
                $init1 = "TBP/".$rowData[0][0];
                $init2 = $ctgl;//$rowData[0][1];
                $init3 = "PTP/".$rowData[0][0];
                $init4 = '1';
                $init5 = $cskpd;
                $init6 = $cgiat;
                $init7 = $rowData[0][1];
                $init8 = $this->tukd_model->get_nama($rowData[0][0],'map_lo','ms_rek5','kd_rek5');
                $init9 = $rowData[0][2];                
                $init10 = $rowData[0][3];
                $nmor = $rowData[0][0];
                
                }
                                                                
                
                
                if($init7==''){
                    echo "<script>alert('Data Berhasil Disimpan');</script>";
                   redirect('tukd_cms/ambil_ststbp'); 
                }else{
                   
                   if($cjns_penerimaan=='1'){
                    //tetap
                    $query = "INSERT INTO tr_tetap_api
                        (no_tetap,tgl_tetap,kd_skpd,kd_kegiatan,kd_rek5,kd_rek_lo,nilai,keterangan,urut,kd_subkegiatan) VALUES 
                        ('$init3','$init2','$init5','$init6','$init7','$init8','$init9','$init10','$nmor','1')"; 
                    $this->db->query($query); 
                    
                   }else if($cjns_penerimaan=='2'){
                    //terima dan tetap
                    $query = "INSERT INTO tr_tetap_api
                        (no_tetap,tgl_tetap,kd_skpd,kd_kegiatan,kd_rek5,kd_rek_lo,nilai,keterangan,urut,kd_subkegiatan) VALUES 
                        ('$init3','$init2','$init5','$init6','$init7','$init8','$init9','$init10','$nmor','1')"; 
                    $this->db->query($query); 
                    
                    $query = "INSERT INTO tr_terima_api
                        (no_terima,tgl_terima,no_tetap,tgl_tetap,sts_tetap,kd_skpd,kd_kegiatan,kd_rek5,kd_rek_lo,nilai,keterangan,jenis,urut,bank,kd_subkegiatan) VALUES 
                        ('$init1','$init2','$init3','$init2','1','$init5','$init6','$init7','$init8','$init9','$init10','1','$nmor','$cjns','1')"; 
                    $this->db->query($query); 
                   }else if($cjns_penerimaan=='3'){
                    //tanpa tetap tahun lalu
                    $query = "INSERT INTO tr_terima_api
                        (no_terima,tgl_terima,kd_skpd,kd_kegiatan,kd_rek5,kd_rek_lo,nilai,keterangan,jenis,urut,bank,kd_subkegiatan) VALUES 
                        ('$init1','$init2','$init5','$init6','$init7','$init8','$init9','$init10','2','$nmor','$cjns','1')"; 
                    $this->db->query($query); 
                   }if($cjns_penerimaan=='4'){
                    //tanpa tetap tahun ini
                    $query = "INSERT INTO tr_terima_api
                        (no_terima,tgl_terima,kd_skpd,kd_kegiatan,kd_rek5,kd_rek_lo,nilai,keterangan,jenis,urut,bank,kd_subkegiatan) VALUES 
                        ('$init1','$init2','$init5','$init6','$init7','$init8','$init9','$init10','1','$nmor','$cjns','1')"; 
                    $this->db->query($query); 
                   }                   
                    
                }
                     
            }
        
        echo "<script>alert('Data Berhasil Disimpan');</script>";                               
        redirect('tukd_cms/ambil_ststbp');        
        
    }
    
    function load_terima_api() {
        $skpd     = $this->session->userdata('kdskpd');        
        $cek = explode(".",$skpd);
        $ck = $cek[3];  
        
        if($ck=="00"){
            $par = "kd_skpd='$skpd'";
        }else{
            $par = "kd_skpd='$skpd'";
        }
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND tgl_terima = '$kriteria' AND kd_subkegiatan='1'";            
        }
       
        $sql = "SELECT count(*) as total from tr_terima_api WHERE $par $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        $sql = "
        SELECT top $rows no_terima,no_tetap,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket,nilai, kd_rek5,kd_rek_lo,kd_kegiatan,SUBSTRING(kd_kegiatan,14,2) as bidang,sts_tetap,bank from tr_terima_api WHERE $par AND (jenis <> '2' or jenis is null)
        $where AND no_terima NOT IN (SELECT TOP $offset no_terima FROM tr_terima_api WHERE $par $where ORDER BY tgl_terima,cast(urut as int)) ORDER BY tgl_terima,cast(urut as int) ";

        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           $par_terima = $resulte['no_terima'];
           $stt = $this->db->query("select count(no_terima) as row from trdkasin_pkd where SUBSTRING(kd_kegiatan,6,10)='$skpd' and no_terima='$par_terima'")->row();
           $cek_stt = $stt->row;
           
            $row[] = array(  
                        'id' => $ii,        
                        'no_terima' => $resulte['no_terima'],
                        'no_tetap' => $resulte['no_tetap'],
                        'tgl_terima' => $resulte['tgl_terima'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['ket'],    
                        'nilai' => number_format($resulte['nilai'],2),
                        'kd_rek5' => $resulte['kd_rek5'],
                        'kd_rek' => $resulte['kd_rek_lo'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'bidang' => $resulte['bidang'],
                        'tgl_tetap' => $resulte['tgl_tetap'],
                        'sts_tetap' =>$resulte['sts_tetap'],
                        'bank' =>$resulte['bank'],
                        'stt_sts' => $cek_stt                                                                                            
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
    }
    
    function load_tetap_api() {
        $skpd     = $this->session->userdata('kdskpd');                  
        $cek = explode(".",$skpd);
        $ck = $cek[3];  
        
        if($ck=="00"){
            $par = "a.kd_skpd='$skpd'";
        }else{
            $par = "a.kd_skpd='$skpd'";
        }
         
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND a.tgl_tetap = '$kriteria' AND a.kd_subkegiatan='1'";            
        }
       
        $sql = "SELECT count(*) as total from tr_tetap_api a WHERE $par $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
        $sql = "
        SELECT top $rows a.*, (SELECT b.nm_rek5 FROM ms_rek5 b WHERE a.kd_rek5=b.kd_rek5) as nm_rek5 FROM tr_tetap_api a WHERE $par
        $where AND a.no_tetap NOT IN (SELECT TOP $offset a.no_tetap FROM tr_tetap_api a WHERE $par $where 
        ORDER BY a.tgl_tetap,a.no_tetap ) ORDER BY tgl_tetap,cast(urut as int) ";

        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                     
           $bidd = "00";
           $par_tetap = $resulte['no_tetap'];
           
           $stt = $this->db->query("select count(no_tetap) as row from tr_terima_api where kd_skpd='$skpd' and no_tetap='$par_tetap'")->row();
           $cek_stt = $stt->row;                      
           
            $row[] = array(  
                        'id' => $ii,        
                        'no_tetap' => $resulte['no_tetap'],
                        'tgl_tetap' => $resulte['tgl_tetap'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai']),
                        'kd_rek5' => $resulte['kd_rek5'],
                        'nm_rek5' => $resulte['nm_rek5'],
                        'kd_rek' => $resulte['kd_rek_lo'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'bidang' => $bidd,
                        'stt_terima' => $cek_stt                                                                                              
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        
    }
    
    function load_total_dtetap_api($tgl=''){
       $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
                        SUM (b.nilai) AS total_tetap
                    FROM
                        tr_tetap_api b
                    WHERE
                        b.kd_skpd = '$kode' AND b.tgl_tetap='$tgl' AND b.kd_subkegiatan='1'";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'xtotal_tetap' => number_format($resulte['total_tetap'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
        
    function load_total_dterima_api($tgl=''){
       $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
                        SUM (b.nilai) AS total_terima
                    FROM
                        tr_terima_api b
                    WHERE
                        b.kd_skpd = '$kode' AND b.tgl_terima='$tgl' AND b.kd_subkegiatan='1'";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'xtotal_terima' => number_format($resulte['total_terima'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function hapus_terimatetap_api(){
        $kode   = $this->session->userdata('kdskpd');
        $tgl    = $this->input->post('tgl');
        $init    = $this->input->post('init');
        
        if($init=='1'){
            $sql = "delete from tr_tetap_api where tgl_tetap='$tgl' and kd_skpd = '$kode' and kd_subkegiatan='1'";
            $asg = $this->db->query($sql);                 
        }else if($init=='2'){
            $sql = "delete from tr_tetap_api where tgl_tetap='$tgl' and kd_skpd = '$kode' and kd_subkegiatan='1'";
            $asg = $this->db->query($sql);     
            
            $sql = "delete from tr_terima_api where tgl_terima='$tgl' and kd_skpd = '$kode' and kd_subkegiatan='1'";
            $asg = $this->db->query($sql);
        }else if($init=='3'){
            $sql = "delete from tr_terima_api where tgl_terima='$tgl' and kd_skpd = '$kode and kd_subkegiatan='1''";
            $asg = $this->db->query($sql);
        }else if($init=='4'){
            $sql = "delete from tr_terima_api where tgl_terima='$tgl' and kd_skpd = '$kode' and kd_subkegiatan='1'";
            $asg = $this->db->query($sql);
        }
        
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }
    
    //pemindahbukuan bank
    
    function load_transout_bnk(){
        $kd_skpd     = $this->session->userdata('kdskpd');        
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }            
        }   
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 30;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.ket) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout a where a.pay='BANK' and a.panjar = '0' AND $init_skpd $where 
                and a.no_bukti not in (select no_bukti from trhtransout_cmsbank a WHERE  a.panjar = '0' AND left(a.kd_skpd,17)=left('$kd_skpd',17) $where)" ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        //(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END )
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,(select count(*) from trhtrmpot where no_kas=a.no_bukti and kd_skpd=a.kd_skpd) AS kete,(SELECT COUNT(*) from trlpj z 
        join trhlpj v on v.no_lpj = z.no_lpj
        where v.jenis=a.jns_spp and z.no_bukti = a.no_bukti and z.kd_bp_skpd = a.kd_skpd) ketlpj,
        0 ketspj,(select rekening from ms_skpd where kd_skpd='$kd_skpd') as rekening_awal FROM trhtransout a  
        WHERE  a.panjar = '0' AND $init_skpd $where and a.pay='BANK' and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '0' AND $init_skpd $where order by CAST (a.no_bukti as NUMERIC)) and 
        a.no_bukti not in (select no_bukti from trhtransout_cmsbank a WHERE  a.panjar = '0' AND $init_skpd $where)
         order by CAST (a.no_bukti as NUMERIC),kd_skpd ";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketlpj' => $resulte['ketlpj'],                                                                                            
                        'ketspj' => $resulte['ketspj'], 
                        'rekening_awal' => $resulte['rekening_awal']                                                                                                                   
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_tgltransout_bnk(){
        $kd_skpd     = $this->session->userdata('kdskpd');        
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        }else{
            if(substr($kd_skpd,18,4)=='0000'){
                $init_skpd = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }            
        } 
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND a.tgl_bukti='$kriteria'";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout a where a.pay='BANK' and a.panjar = '0' AND $init_skpd $where 
                and a.no_bukti not in (select no_bukti from trhtransout_cmsbank a WHERE  a.panjar = '0' AND $init_skpd $where)" ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        //(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END )
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z 
        join trhlpj v on v.no_lpj = z.no_lpj
        where v.jenis=a.jns_spp and z.no_bukti = a.no_bukti and z.kd_bp_skpd = a.kd_skpd) ketlpj,
        0 ketspj FROM trhtransout a  
        WHERE  a.panjar = '0' AND $init_skpd $where and a.pay='BANK' and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '0' AND $init_skpd $where order by CAST (a.no_bukti as NUMERIC)) and 
        a.no_bukti not in (select no_bukti from trhtransout_cmsbank a WHERE  a.panjar = '0' AND $init_skpd $where)
         order by CAST (a.no_bukti as NUMERIC),kd_skpd ";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketlpj' => $resulte['ketlpj'],                                                                                            
                        'ketspj' => $resulte['ketspj'],                                                                                                                    
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_dtransout_bnk(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
                FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti 
                AND a.kd_skpd=b.kd_skpd 
                WHERE a.no_bukti='$nomor' AND a.kd_skpd='$skpd'
                ORDER BY b.kd_kegiatan,b.kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'    => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
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
    
    function load_dtransout_transfer_bnk(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_bukti,b.tgl_bukti,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,(select sum(nilai) from trdtransout_transfer where no_bukti=b.no_bukti and kd_skpd=b.kd_skpd) as total
                FROM trhtransout a INNER JOIN trdtransout_transfer b ON a.no_bukti=b.no_bukti
                AND a.kd_skpd=b.kd_skpd 
                WHERE b.no_bukti='$nomor' AND b.kd_skpd='$skpd'
                group by b.no_bukti,b.tgl_bukti,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai
                ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'                => $ii,        
                        'no_bukti'        => $resulte['no_bukti'],
                        'tgl_bukti'       => $resulte['tgl_bukti'],
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
    
    function load_total_trans_bnk(){
       $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $no_bukti    = $this->input->post('no_simpan');
 
        $sql = "select total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                        select c.kd_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_kegiatan
                                    ) as d on a.kd_kegiatan=d.kd_kegiatan
                                    left join 
                                    (
                                        select z.kd_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
                                        where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_kegiatan
                                        UNION ALL
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' and e.no_bukti<>'$no_bukti' group by f.kd_kegiatan
                                        )z group by z.kd_kegiatan
                                    ) g on a.kd_kegiatan=g.kd_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_kegiatan
                                    ) z ON a.kd_kegiatan=z.kd_kegiatan
                                    where a.kd_kegiatan='$kegiatan'";             
        
        
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
    
    function load_rek_bnk() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
            
/*       $stsubah =$this->rka_model->get_nama($kode,'status_ubah','trhrka','kd_skpd');
        $stssempurna =$this->rka_model->get_nama($kode,'status_sempurna','trhrka','kd_skpd');
       */

        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
        
        
            $field='nilai_ubah';
        
        
        if ($jenis=='1'){
            $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5
                        AND c.no_bukti <> '$nomor'
                        AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_kegiatan = a.kd_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek5 = a.kd_rek5 AND d.status_validasi='0' AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_kegiatan = a.kd_kegiatan
                        AND left(x.kd_skpd,17) = left(a.kd_skpd,17)
                        AND x.kd_rek5 = a.kd_rek5
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_kegiatan = a.kd_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek5
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, nilai_ubah AS nilai_ubah
                        FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn  ";
                    
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd 
                    WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
                    AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran,
                    0 as nilai_sempurna,
                    0 as nilai_ubah
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_kegiatan='$giat' $notIn ";
        }        
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran'],
                        'anggaran_semp' => $resulte['nilai_sempurna'],
                        'anggaran_ubah' => $resulte['nilai_ubah']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }
    
    function simpan_transout_bnk(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomor_tgl= $this->input->post('notgl');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $skpd = $this->session->userdata('kdskpd'); //$this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql'); 
        $csqlrek     = $this->input->post('sqlrek');           
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nosp2d   = $this->input->post('nosp2d2');  
        $xrek     = $this->input->post('xrek');     
        
        $rek_awal = trim($this->input->post('rek_awal'));            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $init_ket = $this->input->post('cinit_ket');
        $stt_val  = 0;
        $stt_up   = 0;
       
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);
            
            if ($asg){
                $sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d')";
                $asg = $this->db->query($sql);
                } else {
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
            
        }elseif($tabel == 'trdtransout') {
            // Simpan Detail //                                       
                
                $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                $sql = "delete from trdtransout_transfer where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,sumber)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    $sql = "insert into trdtransout_transfer(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)"; 
                    $asg = $this->db->query($sql.$csqlrek);                                       
                       
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
    
    function hapus_transout_bnk(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        if ($asg){
          
            $sql = "delete from trhtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);
            
            $sql = "delete from trdtransout_transfer where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);
          
            if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }
    
    function load_pot_in_bnk(){
    
        $kd_skpd     = $this->session->userdata('kdskpd');
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
       
        $sql = "SELECT count(*) as total from trhtrmpot where kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        
        $sql = "SELECT top $rows * from trhtrmpot where kd_skpd='$kd_skpd' AND no_bukti not in (SELECT top $offset no_bukti FROM trhtrmpot where kd_skpd='$kd_skpd' 
        order by CAST(no_bukti AS INT)) $where order by CAST(no_bukti AS INT),kd_skpd";

        
        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],        
                        'ket' => $resulte['ket'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'nilai' => $resulte['nilai'],
                        'kd_giat' => $resulte['kd_kegiatan'],
                        'nm_giat' => $resulte['nm_kegiatan'],
                        'kd_rek' => $resulte['kd_rek5'],
                        'nm_rek' => $resulte['nm_rek5'],
                        'rekanan' => $resulte['nmrekan'],
                        'dir' => $resulte['pimpinan'],
                        'alamat' => $resulte['alamat'],
                        'npwp' => $resulte['npwp'],
                        'jns_beban' => $resulte['jns_spp'],
                        'status' => $resulte['status'],
                        'ebilling' => $resulte['ebilling']                                                                                  
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_trans_trmpot_bnk(){
       $kode    = $this->session->userdata('kdskpd');
       $id      = $this->session->userdata('pcNama');
       
            $sql = "SELECT DISTINCT a.no_kas,a.no_bukti,a.tgl_bukti,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,a.jns_spp,a.total 
            FROM trhtransout a
            JOIN trdtransout b ON a.no_bukti = b.no_bukti and a.kd_skpd = b.kd_skpd
            WHERE a.kd_skpd = '$kode' and a.pay in ('BANK','TUNAI') and a.jns_spp in ('1','3') and a.username='$id'
            order by a.tgl_bukti,a.no_bukti";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,                          
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'nm_rek5' => $resulte['nm_rek5'],
                        'jns_spp' => $resulte['jns_spp'],
                        'total' => number_format($resulte['total'],2)                              
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function trdtrmpot_list_bnk() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('nomor');
        
        $sql = "SELECT * FROM trdtrmpot where no_bukti='$nomor' AND kd_skpd ='$kd_skpd' order by kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,   
                        'kd_rek_trans' => $resulte['kd_rek_trans'],  
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],  
                        'ebill' => $resulte['ebilling'],
                        //'pot' => $resulte['pot'],
                        //'nilai' => $resulte['nilai']
                        'nilai' => number_format($resulte['nilai'],2,'.',',')
                        );
                        $ii++;
        }
           
        echo json_encode($result);
         //$query1->free_result();   
    }
    
    function simpan_potongan_bnk(){
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
        //$ebill    = $this->input->post('ebill');        
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
        $csqljur     = $this->input->post('sqljur');            
        $giatt = "";
        $update     = date('Y-m-d H:i:s');      
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtrmpot') {
            $sql = "delete from trhtrmpot where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);              

            if ($asg){
                
                $sql = "insert into trhtrmpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_kegiatan, nm_kegiatan, kd_rek5,nm_rek5,nmrekan, pimpinan,alamat,rekening_tujuan,nm_rekening_tujuan,no_kas) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$kdrekbank','$nmrekbank','$nomorvou')";
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
            
        }elseif($tabel == 'trdtrmpot') {         
            
            // Simpan Detail //                       
                $sql = "delete from trdtrmpot where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                        
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtrmpot(no_bukti,kd_rek5,nm_rek5,nilai,kd_skpd,kd_rek_trans,ebilling)"; 
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
    
    function hapus_trmpot_bnk(){
        $nomor = $this->input->post('no');
        $nomorvo = $this->input->post('novoucher');
        $kd_skpd  = $this->session->userdata('kdskpd');
        
        $sql = "delete from trhtrmpot where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        
        if($asg){
        $msg = array(); 

        $sql = "delete from trdtrmpot where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        }
    }
    
    function load_trm_pot_bnk(){
        $skpd = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        //$id=str_replace('123456789','/',$spp);
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdtrmpot where no_bukti='$bukti' AND kd_skpd='$skpd'");  
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
    
    function load_jns_spp_drop(){
        
    $result = array(( 
                          array(
                          "id"   => 1 ,
                          "jns" => " UP/GU"
                          ) 
                        ),
                        ( 
                          array( 
                          "id"   => 3 ,
                          "jns" => " TU"
                          ) 
                        ),
                        ( 
                          array( 
                          "id"   => 4 ,
                          "jns" => " LS GAJI"
                          ) 
                        ),
                        ( 
                          array( 
                          "id"   => 6 ,
                          "jns" => " LS Barang Jasa"
                          ) 
                        )
                );         
     
    echo json_encode($result);
    }
    
    function load_jns_setor_drop(){
        
    $result = array(( 
                        array(
                          "id"   => 4 ,
                          "jns" => "Setor Ke Kas BP"
                          ) 
                        ),
                        ( 
                          array( 
                          "id"   => 5 ,
                          "jns" => "Setor Ke Kas Daerah"
                          ) 
                        )
                );         
     
    echo json_encode($result);
    }
    
    //setor dana bank
    function setor_simpanan_bidang()
    {
        $data['page_title']= 'INPUT SETOR NON TUNAI';
        $this->template->set('title', 'INPUT SETOR NON TUNAI');   
        $this->template->load('template','tukd/cms/bnk_setor_simpanan_bidang',$data) ; 
    }
    
    function upload_setor_simpanan_bidang()
    {
        $data['page_title']= 'UPLOAD SETOR NON TUNAI';
        $this->template->set('title', 'UPLOAD SETOR NON TUNAI');   
        $this->template->load('template','tukd/cms/bnk_upload_bidang',$data) ; 
    }
    
    function validasi_setor_simpanan_bidang()
    {
        $data['page_title']= 'VALIDASI SETOR NON TUNAI';
        $this->template->set('title', 'VALIDASI SETOR NON TUNAI');   
        $this->template->load('template','tukd/cms/bnk_validasi_bidang',$data) ; 
    }
    
    function ambil_bank_bidang()
    {
        $data['page_title']= 'AMBIL SETORAN BANK BIDANG';
        $this->template->set('title', 'AMBIL SETORAN BANK BIDANG');   
        $this->template->load('template','tukd/cms/bnk_ambil_simpanan_kebidang',$data) ; 
    }
    
    //setor sisa
    function setor_sisakas_bidang()
    {
        $data['page_title']= 'INPUT SETOR NON TUNAI';
        $this->template->set('title', 'INPUT SETOR NON TUNAI');   
        $this->template->load('template','tukd/cms/kas_setor_sisa_bidang',$data) ; 
    } 
    
    function load_setorbidang_bnk() {
        
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd,0,17);
        $dbidang = substr($bid,18,4);
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_kas) like upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from tr_setorpelimpahan_bank_cms where kd_skpd_sumber='$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows * from tr_setorpelimpahan_bank_cms where kd_skpd_sumber='$kd_skpd' $where and no_kas not in (
                SELECT TOP $offset no_kas from tr_setorpelimpahan_bank_cms WHERE kd_skpd_sumber='$kd_skpd' $where order by tgl_kas,cast(no_kas as int)) order by tgl_kas,cast(no_kas as int),kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        
           
            
        foreach($query1->result_array() as $resulte)
        {                         
            $row[] = array(
                        'id'          => $ii,        
                        'no_kas'      => $resulte['no_kas'],
                        'tgl_kas'     => $resulte['tgl_kas'],
                        'kd_skpd'     => $resulte['kd_skpd'],
                        'nilai'       => number_format($resulte['nilai']),
                        'nilai2'       => $resulte['nilai'],
                        'keterangan'  => $resulte['keterangan'],
                        'kd_skpd_sumber'    => $kd_skpd,
                        'jenis_spp'      => $resulte['jenis_spp'],
                        'ket_tujuan'      => $resulte['ket_tujuan'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],    
                        'status_validasi' => $resulte['status_validasi'],   
                        'status_upload' => $resulte['status_upload']                     
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
        }
        
    
    
    function simpan_ambil_simpanan_bidang_bnk(){
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd_sumber='$kd_skpd'";
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
    
    function update_ambilsimpanan_bnk(){
        $query  = $this->input->post('st_query');
        $asg    = $this->db->query($query);
         if(!$asg){
            echo "0";
        } else {
            echo "1";
        }
    }  
    
    function hapus_ambilsimpanan_bidang_bnk() {     
        $no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $tabel = $this->input->post('tabel');      
        $query = $this->db->query("delete from $tabel where no_kas='$no' and kd_skpd='$skpd' ");
       // $query->free_result();
    }
    
    function load_list_upload_perbidang(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_bukti='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where left(a.kd_skpd,17)=left('$skpd',17) $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,b.* FROM tr_setorpelimpahan_bank_cms a 
        where left(a.kd_skpd,17)=left('$skpd',17) $and 
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE left(a.kd_skpd,17)=left('$skpd',17) $and order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");       
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
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
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
    
    function load_listsetor_upload_cms(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_bukti='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.* FROM tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and 
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' $and order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");       
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']
                                                                              
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }
    
       function load_listsetor_belum_upload_cms(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_bukti='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and and a.status_upload='0'" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.* FROM tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and and a.status_upload='0'
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' $and and a.status_upload='0' order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");       
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']
                                                                              
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }
    
    
    function simpan_uploadcms_setorbidang(){
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

    if($tabel == 'trdupload_cmsbank_bidang') {
            // Simpan Detail //                       
                $sql = "delete from trhupload_cmsbank_bidang where no_upload='$nomor' AND kd_skpd='$skpd' and username='$usern'";
                $asg = $this->db->query($sql);
                $sql = "delete from trdupload_cmsbank_bidang where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdupload_cmsbank_bidang(no_bukti,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,no_upload_tgl)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    $skpd = $this->session->userdata('kdskpd'); 
                    $sql = "insert into trhupload_cmsbank_bidang(no_upload,tgl_upload,kd_skpd,total,no_upload_tgl,username) values ('$nomor','$update','$skpd','$total','$urut_tgl','$usern')";
                    $asg = $this->db->query($sql);
                    
                    $sql = "UPDATE
                            tr_setorpelimpahan_bank_cms
                            SET tr_setorpelimpahan_bank_cms.status_upload = Table_B.status_upload,
                                 tr_setorpelimpahan_bank_cms.tgl_upload = Table_B.tgl_upload
                        FROM tr_setorpelimpahan_bank_cms     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_bukti,b.kd_bp from trhupload_cmsbank_bidang a left join 
                        trdupload_cmsbank_bidang b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_bp='$skpd' and a.no_upload='$nomor') AS Table_B ON tr_setorpelimpahan_bank_cms.no_bukti = Table_B.no_bukti AND tr_setorpelimpahan_bank_cms.kd_skpd = Table_B.kd_skpd
                        where left(tr_setorpelimpahan_bank_cms.kd_skpd,17)=left('$skpd',17)
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
      
    function load_draf_upload_bidang(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.no_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        if(substr($skpd,8,2)=='00'){
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }else{
            $init_skpd = "a.kd_skpd='$skpd'";
        }
        
        $sql = "SELECT count(*) as total from trhupload_cmsbank_bidang a left join trdupload_cmsbank_bidang b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where $init_skpd $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,b.* FROM trhupload_cmsbank_bidang a left join trdupload_cmsbank_bidang b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        where $init_skpd $and 
        and a.no_upload not in (SELECT top $offset a.no_upload FROM trhupload_cmsbank_bidang a  
        WHERE $init_skpd $and order by cast(a.no_upload as int))
        order by cast(a.no_upload as int),a.kd_skpd");      
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2),
                        'viewtotal' => number_format($resulte['nilai'],2),
                        'nilai' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
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
    
    function load_hdraf_upload_bidang(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhupload_cmsbank_bidang a
        where a.kd_skpd='$skpd' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.* FROM trhupload_cmsbank_bidang a               
        where a.kd_skpd='$skpd' $and 
        and a.no_upload+a.no_upload_tgl in (SELECT a.no_upload+a.no_upload_tgl FROM trdupload_cmsbank_bidang a  
        WHERE a.kd_bp='$skpd')
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
    
    function load_total_upload_bidang($tgl=''){
       $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
                        SUM (a.nilai) AS total_upload
                    FROM
                        tr_setorpelimpahan_bank_cms a                   
                    WHERE
                        a.kd_skpd_sumber = '$kode'
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
    
    function csv_cmsbank_setorbidang($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $usern = $this->session->userdata('pcNama');
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='OB';                 
        
        $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=a.kd_skpd) as nm_skpd,
        b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,b.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank_bidang a left join trdupload_cmsbank_bidang b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.no_upload='$nomor' and a.username='$usern'");
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
            $nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            $rrekawal = $resulte['rekening_awal'];
            $rrektujuan = $resulte['rekening_tujuan'];
            
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($rrekawal)).";".rtrim($resulte['nm_rekening_tujuan']).";".str_replace(" ","",rtrim($rrektujuan)).";".$nilai.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }
    
    function load_list_validasi_perbidang(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' and status_upload='1' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM tr_setorpelimpahan_bank_cms a 
        left join trdupload_cmsbank_bidang c on a.no_bukti = c.no_bukti and a.kd_skpd = c.kd_skpd
        where a.kd_skpd_sumber='$skpd' and a.status_upload='1' $and 
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' and a.status_upload='1' $and order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");       
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
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
    
     function load_list_belum_validasi_perbidang(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' and status_upload='1' and status_validasi='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM tr_setorpelimpahan_bank_cms a 
        left join trdupload_cmsbank_bidang c on a.no_bukti = c.no_bukti and a.kd_skpd = c.kd_skpd
        where a.kd_skpd_sumber='$skpd' and a.status_upload='1' and status_validasi='0' $and 
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' and a.status_upload='1' and status_validasi='0' $and order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");       
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
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

    function load_list_telahvalidasi_perbidang(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_validasi='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where left(a.kd_skpd,17)=left('$skpd',17) and status_upload='1' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload,d.no_bukti FROM tr_setorpelimpahan_bank_cms a 
        left join trdupload_cmsbank_bidang c on a.no_bukti = c.no_bukti and a.kd_skpd = c.kd_skpd
        left join trvalidasi_cmsbank d on d.no_voucher = c.no_voucher and d.kd_bp = c.kd_bp
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='1' and a.status_validasi='1' $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='1' and a.status_validasi='1' $and order by cast(a.no_voucher as int))
        order by cast(d.no_bukti as int),a.tgl_validasi,a.kd_skpd");        
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],  
                        'no_bku' => $resulte['no_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_pot' => $resulte['status_trmpot']                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }
    
    function simpan_validasicms_bidang(){
        $tabel    = $this->input->post('tabel');                
        $skpd     = $this->input->post('skpd');
        $csql     = $this->input->post('sql');      
        $nval     = $this->input->post('no');  
        
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

    if($tabel == 'trvalidasi_cmsbank_bidang') {
                    
                    $sql = "delete from trvalidasi_cmsbank_bidang where kd_bp='$skpd_ss' and no_validasi='$nval'"; 
                    $asg = $this->db->query($sql);
                            
                    $sql = "insert into trvalidasi_cmsbank_bidang(no_bukti,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,tgl_validasi,status_validasi,no_validasi)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $sql = "UPDATE
                            tr_setorpelimpahan_bank_cms
                            SET tr_setorpelimpahan_bank_cms.status_validasi = Table_B.status_validasi,
                                tr_setorpelimpahan_bank_cms.tgl_validasi = Table_B.tgl_validasi                                
                        FROM tr_setorpelimpahan_bank_cms     
                        INNER JOIN (select a.no_bukti,a.kd_skpd,a.kd_bp,a.tgl_validasi,a.status_validasi from trvalidasi_cmsbank_bidang a
                        where a.kd_bp='$skpd_ss' and no_validasi='$nval') AS Table_B ON tr_setorpelimpahan_bank_cms.no_bukti = Table_B.no_bukti AND tr_setorpelimpahan_bank_cms.kd_skpd = Table_B.kd_skpd
                        where left(tr_setorpelimpahan_bank_cms.kd_skpd,17)=left('$skpd_ss',17)
                        ";
                        $asg = $this->db->query($sql);
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql = "INSERT INTO tr_setorpelimpahan_bank (no_kas, tgl_kas, no_bukti, tgl_bukti, kd_skpd, nilai, jenis_spp, keterangan, kd_skpd_sumber)
                                    SELECT a.no_kas, a.tgl_kas, a.no_bukti, a.tgl_bukti, a.kd_skpd, a.nilai, a.jenis_spp, a.keterangan, a.kd_skpd_sumber
                                    FROM tr_setorpelimpahan_bank_cms a left join trvalidasi_cmsbank_bidang b on b.no_bukti=a.no_bukti and a.kd_skpd_sumber=b.kd_bp
                                    WHERE b.no_validasi='$nval' and b.kd_bp='$skpd_ss'";
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
    }   
    
    function load_terima_bank_perbidang() {
        
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');        
                
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_kas) like upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from tr_setorsimpanan WHERE kd_skpd = '$kd_skpd' AND status_drop='1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows * from tr_setorsimpanan WHERE kd_skpd = '$kd_skpd' AND status_drop='1' $where and no_kas not in (
                SELECT TOP $offset no_kas from tr_setorsimpanan WHERE  kd_skpd = '$kd_skpd' AND status_drop='1' $where order by cast(no_kas as int)) order by cast(no_kas as int),kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte)
        {                         
            $row[] = array(
                        'id'          => $ii,        
                        'no_kas'      => $resulte['no_kas'],
                        'no_bukti'      => $resulte['no_bukti'],
                        'kd_link_drop'      => $resulte['kd_link_drop'],                                                
                        'tgl_kas'     => $resulte['tgl_kas'],
                        'tgl_bukti'     => $resulte['tgl_bukti'],
                        'kd_skpd'     => $resulte['kd_skpd'],
                        'nilai'       => number_format($resulte['nilai']),
                        'nilai2'       => $resulte['nilai'],
                        'keterangan'  => $resulte['keterangan'],
                        'jenis'  => $resulte['jenis']                                                    
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
        }
    
    function loadketdrop_bp_bnk() {    
       $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT no_bukti,tgl_bukti,nilai,keterangan,kd_skpd_sumber from tr_setorpelimpahan_bank where kd_skpd='$skpd' and 
        status_ambil is null";
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],                        
                        'kd_skpd_sumber' => $resulte['kd_skpd_sumber'],                                                
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'nilai' =>  number_format($resulte['nilai'],2,'.',','),
                        'keterangan' => $resulte['keterangan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_sisa_dana_kembali_bidang_bnk(){
        $kd_skpd = $this->session->userdata('kdskpd');                
        
            $query1 = $this->db->query("select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorpelimpahan_bank WHERE kd_skpd='$kd_skpd' union
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE kd_skpd='$kd_skpd' AND status_drop='1' ) a
                where  kode='$kd_skpd'");
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
    
    function cari_ambilsimpanan() {
    
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';
        
        $kd_skpd  = $this->session->userdata('kdskpd');
        
        if ($kriteria <> ''){                               
            $where="and ( upper(no_kas) like upper('%$kriteria%') ) ";            
        }
        
        $sql    = "SELECT * from tr_setorsimpanan where kd_skpd='$kd_skpd' and status_drop='1' $where order by no_kas";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'          => $ii,        
                        'no_kas'      => $resulte['no_kas'],
                        'tgl_kas'     => $this->tukd_model->rev_date($resulte['tgl_kas']),
                        'kd_skpd'     => $resulte['kd_skpd'],
                        'nilai'       => number_format($resulte['nilai']),
                        'bank'        => $resulte['bank'],                        
                        'keterangan'  => $resulte['keterangan']    
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
    
    function simpan_ambil_simpanan_bp_bnk(){
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $cno_asli = $this->input->post('cno_asli');                
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            echo '1';
        }else{
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if($asg){
                $sql = "update tr_setorpelimpahan_bank set status_ambil='1' where no_bukti='$cno_asli' and kd_skpd='$kd_skpd'";
                $asg = $this->db->query($sql);                
                echo '2';
            }else{
                echo '0';
            }
        }
    }
    
    function hapus_ambilsimpanan_bp_bnk() {     
        $no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');   
        $nobukti_asli = $this->input->post('nobukti_asli');     
        $query = $this->db->query("delete from tr_setorsimpanan where no_kas='$no' and kd_skpd='$skpd' ");
        $sql = "update tr_setorpelimpahan_bank set status_ambil=null where no_bukti='$nobukti_asli' and kd_skpd='$skpd'";
        $asg = $this->db->query($sql);                
                
       // $query->free_result();
    }
                                            
    function _mpdf_margin($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='',$atas='', $bawah='', $kiri='', $kanan='') {
                

        ini_set("memory_limit","-1M");
        ini_set("MAX_EXECUTION_TIME","-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        
        
        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        $sa=1;
        $tes=0;
        if ($hal==''){
        $hal1=1;
        } 
        if($hal!==''){
        $hal1=$hal;
        }
        if ($fonsize==''){
        $size=12;
        }else{
        $size=$fonsize;
        } 
        
        $this->mpdf = new mPDF('utf-8', array(215,330),$size); //folio
        $this->mpdf->AddPage($orientasi,'',$hal,'1','off',$kiri,$kanan,$atas,$bawah);
        if ($hal==''){
            $this->mpdf->SetFooter("");
        }
        else{
            $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output();
               
    }


 function load_belum_sts() {
        $kd_skpd     = $this->session->userdata('kdskpd');         
            $par = "a.kd_skpd='$kd_skpd'";
            $par2 = "kd_skpd='$kd_skpd'";        
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and a.status_upload='0'";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd_cms a where $par and a.jns_trans='4' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
        $sql = "
        SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd_cms a where $par and a.jns_trans='4'  and a.status_upload='0'
        $where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd_cms where $par2 and jns_trans='4' and a.status_upload='0' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
        ";
        
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                      
           $bidang = "00";
           
           /*$par_sts = $resulte['no_sts'];
           $par_sts_1 = explode("/",$par_sts);
           $par_rekk = $par_sts_1[4];
           $stt = $this->db->query("select nm_rek5 as row from ms_rek5 where kd_rek5='$par_rekk'")->row();
           $rek_rek = $stt->row;*/
           
           
            $row[] = array( 
                        'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nmrek' => '',//$rek_rek,                      
                        'bidang' => $bidang,
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
                        'sumber' => $resulte['sumber'],
                        'no_terima' => $resulte['no_terima'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'bank' => $resulte['bank'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_up' => $resulte['status_upload'],
                        'status_val' => $resulte['status_validasi']
                        );
                        $ii++;
                }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
           
    }
        
    function panjar()
    {
        $data['page_title']= 'PANJAR';
        $this->template->set('title', 'INPUT PANJAR');   
        $this->template->load('template','tukd/cms/panjar_cmsbank',$data) ; 
    }
   
   function panjar_tmb()
    {
        $data['page_title']= 'TAMBAH SISA PANJAR';
        $this->template->set('title', 'INPUT TAMBAH SISA PANJAR');   
        $this->template->load('template','tukd/cms/panjar_cmsbank_tmb',$data) ; 
    }
   
  function upload_panjar()
    {
        $data['page_title']= 'UPLOAD PANJAR';
        $this->template->set('title', 'INPUT UPLOAD PANJAR');   
        $this->template->load('template','tukd/cms/panjar_upload',$data) ; 
    }
    
  function validasi_panjar()
    {
        $data['page_title']= 'VALIDASI PANJAR';
        $this->template->set('title', 'INPUT VALIDASI PANJAR');   
        $this->template->load('template','tukd/cms/panjar_validasi',$data) ; 
    }  
        
  function jawabpanjar()
    {
        $data['page_title']= 'PERTANGUNGJAWABAN PANJAR';
        $this->template->set('title', 'INPUT PERTANGUNGJAWABAN PANJAR');   
        $this->template->load('template','tukdx/transaksi/jawabpanjar',$data) ; 
    }
  
   function tambahpanjar()
    {
        $data['page_title']= 'TAMBAH PANJAR';
        $this->template->set('title', 'INPUT TAMBAH PANJAR');   
        $this->template->load('template','tukdx/transaksi/tambahpanjar',$data) ; 
    }
    
    
    function load_panjar() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        
        if ($kriteria <> ''){                               
            $where="and (upper(no_panjar) like upper('%$kriteria%') or tgl_panjar like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%'))";            
        }
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank  where  jns='1' and kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where  jns='1' and kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' $where order by no_panjar)  order by no_panjar";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],        
                        'no_panjar' => $resulte['no_panjar'],
                        'tgl_panjar' => $resulte['tgl_panjar'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],
                        'rekening_awal' => $resulte['rekening_awal'],     
                        'nilai' => number_format($resulte['nilai']),
                        'pay' => $resulte['pay'],
                        'status' => $resulte['status'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan']
                        
                                                
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        }
        
        function load_panjar_tgl() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        
        if ($kriteria <> ''){                               
            $where="and tgl_kas='$kriteria'";            
        }
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank  where  jns='1' and kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where  jns='1' and kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' $where order by no_panjar)  order by no_panjar";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],        
                        'no_panjar' => $resulte['no_panjar'],
                        'tgl_panjar' => $resulte['tgl_panjar'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai']),
                        'pay' => $resulte['pay'],
                        'status' => $resulte['status'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan']
                        
                                                
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        }
        
        function load_panjar_tmb() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        
        if ($kriteria <> ''){                               
            $where="and (upper(no_panjar) like upper('%$kriteria%') or tgl_panjar like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%'))";            
        }
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank where jns='2' and kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where jns='2' and kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' $where order by no_panjar)  order by no_panjar";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],        
                        'no_panjar' => $resulte['no_panjar'],
                        'no_panjar_lalu' => $resulte['no_panjar_lalu'],
                        'tgl_panjar' => $resulte['tgl_panjar'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai']),
                        'pay' => $resulte['pay'],
                        'status' => $resulte['status'],
                        'kd_kegiatan' => $resulte['kd_kegiatan']
                        
                                                
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        }
        
        function load_panjar_tgl_tmb() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        
        if ($kriteria <> ''){                               
            $where="and tgl_kas='$kriteria'";            
        }
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank  where  jns='2' and kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where  jns='2' and kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' $where order by no_panjar)  order by no_panjar";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],        
                        'no_panjar' => $resulte['no_panjar'],
                        'tgl_panjar' => $resulte['tgl_panjar'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai']),
                        'pay' => $resulte['pay'],
                        'status' => $resulte['status'],
                        'kd_kegiatan' => $resulte['kd_kegiatan']
                        
                                                
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        }
        
        function hapus_panjar_cmsbank(){
        //no:cnomor,skpd:cskpd
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql = "delete from tr_panjar_cmsbank where no_panjar='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);
        
         $sql = "delete from tr_panjar_transfercms where no_bukti='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);
        
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }                       
    }
    
    function load_listpanjar_upload_cms(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_kas='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where left(a.kd_skpd,17)=left('$skpd',17) $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.* FROM tr_panjar_cmsbank a 
        where left(a.kd_skpd,17)=left('$skpd',17) $and 
        and a.no_kas not in (SELECT top $offset a.no_kas FROM tr_panjar_cmsbank a  
        WHERE left(a.kd_skpd,17)=left('$skpd',17) $and order by cast(a.no_kas as int))
        order by cast(a.no_kas as int),a.kd_skpd");     
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_bukti' => $resulte['no_kas'],
                        'tgl_bukti' => $resulte['tgl_kas'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']
                                                                              
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }
    
    function load_list_belumpanjar_upload_cms(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" ";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.* FROM tr_panjar_cmsbank a 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='0' $and         
        order by cast(a.no_kas as int),a.kd_skpd");     
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_bukti' => $resulte['no_kas'],
                        'tgl_bukti' => $resulte['tgl_kas'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']
                                                                              
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }
    
    function simpan_uploadcms_panjar(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $skpd     = $this->input->post('skpd');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');      
        $urut_tgl = $this->input->post('urut_tglupload');
        $username = $this->session->userdata('pcNama');
        
        date_default_timezone_set('Asia/Jakarta');
        $update     = date('Y-m-d');
        $msg        = array();

    if($tabel == 'trdupload_cmsbank_panjar') {
            // Simpan Detail //                       
                $sql = "delete from trhupload_cmsbank_panjar where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                $sql = "delete from trdupload_cmsbank_panjar where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT into trdupload_cmsbank_panjar (no_bukti,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,no_upload_tgl)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    $skpd = $this->session->userdata('kdskpd'); 
                    $sql = "INSERT into trhupload_cmsbank_panjar (no_upload,tgl_upload,kd_skpd,total,no_upload_tgl,username) values ('$nomor','$update','$skpd','$total','$urut_tgl','$username')";
                    $asg = $this->db->query($sql);
                    
                    $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
                    $cek_skpd1 = $cek_skpd->hasil;
                    if($cek_skpd1==1){
                    $sql = "UPDATE
                            tr_panjar_cmsbank
                            SET tr_panjar_cmsbank.status_upload = Table_B.status_upload,
                                 tr_panjar_cmsbank.tgl_upload = Table_B.tgl_upload
                        FROM tr_panjar_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_bukti,b.kd_bp from trhupload_cmsbank_panjar a left join 
                        trdupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_skpd='$skpd' and a.no_upload='$nomor') AS Table_B ON tr_panjar_cmsbank.no_kas = Table_B.no_bukti AND tr_panjar_cmsbank.kd_skpd = Table_B.kd_skpd
                        where left(tr_panjar_cmsbank.kd_skpd,17)=left('$skpd',17)
                        ";
                    }else{
                    $sql = "UPDATE
                            tr_panjar_cmsbank
                            SET tr_panjar_cmsbank.status_upload = Table_B.status_upload,
                                 tr_panjar_cmsbank.tgl_upload = Table_B.tgl_upload
                        FROM tr_panjar_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_bukti,b.kd_bp from trhupload_cmsbank_panjar a left join 
                        trdupload_cmsbank_panjar b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_bp='$skpd' and a.no_upload='$nomor') AS Table_B ON tr_panjar_cmsbank.no_kas = Table_B.no_bukti AND tr_panjar_cmsbank.kd_skpd = Table_B.kd_skpd
                        where left(tr_panjar_cmsbank.kd_skpd,17)=left('$skpd',17)
                        ";   
                    }    
                        
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
    
    function csv_cmsbank_panjar($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $usern = $this->session->userdata('pcNama');
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='OB';                 
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil; 
        $init_skp = substr($skpd,0,17);
        
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$skpd'";
            
            if($init_skp=='1.02.0.00.0.00.01'){
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=a.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank_panjar a 
                left join trdupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload
                left join tr_panjar_transfercms c on c.kd_skpd=b.kd_skpd and c.no_bukti=b.no_bukti 
                where $init_skpd and a.no_upload='$nomor' and a.username='$usern' order by c.no_bukti");
            }else{
                $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=a.kd_skpd) as nm_skpd,
                b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank_panjar a 
                left join trdupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload
                left join tr_panjar_transfercms c on c.kd_skpd=b.kd_skpd and c.no_bukti=b.no_bukti 
                where $init_skpd and a.no_upload='$nomor' and a.username='$usern' order by c.no_bukti");
            }
            
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
            $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=a.kd_skpd) as nm_skpd,
            b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank_panjar a 
            left join trdupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload
            left join tr_panjar_transfercms c on c.kd_skpd=b.kd_skpd and c.no_bukti=b.no_bukti 
            where $init_skpd and a.no_upload='$nomor' and a.username='$usern' order by c.no_bukti");
        }  
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
            $nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            $rrekawal = $resulte['rekening_awal'];
            $rrektujuan = $resulte['rekening_tujuan'];
            
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($rrekawal)).";".rtrim($resulte['nm_rekening_tujuan']).";".str_replace(" ","",rtrim($rrektujuan)).";".$nilai.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }
    
    function load_list_validasi_panjar(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where left(a.kd_skpd,17)=left('$skpd',17) and status_upload='1' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM tr_panjar_cmsbank a 
        left join trdupload_cmsbank_bidang c on a.no_kas = c.no_bukti and a.kd_skpd = c.kd_skpd
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='1' $and 
        and a.no_kas not in (SELECT top $offset a.no_kas FROM tr_panjar_cmsbank a  
        WHERE left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='1' $and order by cast(a.no_kas as int))
        order by cast(a.no_kas as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
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
    
    function load_listbelum_validasi_panjar(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='1' and a.status_validasi='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.*,c.no_upload FROM tr_panjar_cmsbank a 
        left join trdupload_cmsbank_bidang c on a.no_kas = c.no_bukti and a.kd_skpd = c.kd_skpd
        where left(a.kd_skpd,17)=left('$skpd',17) and a.status_upload='1' and a.status_validasi='0' $and         
        order by cast(a.no_kas as int),a.kd_skpd");     
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_kas'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_bukti' => $resulte['tgl_kas'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
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
    
    function simpan_validasicms_panjar(){
        $tabel    = $this->input->post('tabel');                
        $skpd     = $this->input->post('skpd');
        $csql     = $this->input->post('sql');      
        $nval     = $this->input->post('no');  
        
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

    if($tabel == 'trvalidasi_cmsbank_panjar') {
                    
                    $sql = "delete from trvalidasi_cmsbank_panjar where kd_bp='$skpd_ss' and no_validasi='$nval'"; 
                    $asg = $this->db->query($sql);
                            
                    $sql = "insert into trvalidasi_cmsbank_panjar(no_bukti,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,tgl_validasi,status_validasi,no_validasi)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $sql = "UPDATE
                            tr_panjar_cmsbank
                            SET tr_panjar_cmsbank.status_validasi = Table_B.status_validasi,
                                tr_panjar_cmsbank.tgl_validasi = Table_B.tgl_validasi                                
                        FROM tr_panjar_cmsbank     
                        INNER JOIN (select a.no_bukti,a.kd_skpd,a.kd_bp,a.tgl_validasi,a.status_validasi from trvalidasi_cmsbank_panjar a
                        where a.kd_skpd='$skpd_ss' and no_validasi='$nval') AS Table_B ON tr_panjar_cmsbank.no_kas = Table_B.no_bukti AND tr_panjar_cmsbank.kd_skpd = Table_B.kd_skpd
                        where left(tr_panjar_cmsbank.kd_skpd,17)=left('$skpd_ss',17)
                        ";
                        $asg = $this->db->query($sql);
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql = "INSERT INTO tr_panjar (no_kas,tgl_kas,no_panjar,tgl_panjar,kd_skpd,pengguna,nilai,keterangan,pay,rek_bank,kd_sub_kegiatan,status,jns,no_panjar_lalu)
                                    SELECT a.no_kas,a.tgl_kas,a.no_panjar,a.tgl_panjar,a.kd_skpd,a.pengguna,a.nilai,a.keterangan,a.pay,a.rek_bank,a.kd_sub_kegiatan,a.status,a.jns,a.no_panjar_lalu
                                    FROM tr_panjar_cmsbank a left join trvalidasi_cmsbank_panjar b on b.no_bukti=a.no_kas and a.kd_skpd=b.kd_skpd
                                    WHERE b.no_validasi='$nval' and b.kd_skpd='$skpd_ss'";
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
    }   
    
    function load_hdraf_upload_panjar(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhupload_cmsbank_panjar a
        where left(a.kd_skpd,17)=left('$skpd',17) $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.* FROM trhupload_cmsbank_panjar a               
        where left(a.kd_skpd,17)=left('$skpd',17) $and         
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
    
    function load_total_upload_panjar($tgl=''){
       $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
                        SUM (a.nilai) AS total_upload
                    FROM
                        tr_panjar_cmsbank a                 
                    WHERE
                        left(a.kd_skpd,17) = left('$kode',17)
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
    
    function load_draf_upload_panjar(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.no_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhupload_cmsbank_panjar a left join trdupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){                  
        $query1 = $this->db->query("SELECT a.*,b.* FROM trhupload_cmsbank_panjar a left join trdupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) $and         
        order by cast(a.no_upload as int),a.kd_skpd");  
        }else{
        $query1 = $this->db->query("SELECT a.*,b.* FROM trhupload_cmsbank_panjar a left join trdupload_cmsbank_panjar b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) $and         
        order by cast(a.no_upload as int),a.kd_skpd");    
        }
            
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2),
                        'viewtotal' => number_format($resulte['nilai'],2),
                        'nilai' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
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
    
    function simpan_master_panjar(){
        $kd_skpd  = $this->session->userdata('kdskpd'); 
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $sqlrek = $this->input->post('sqlrek');
        
        $sql = "SELECT $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            echo '1';
        }else{
            $sql = "INSERT into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            
            $sqlss = "INSERT into tr_panjar_transfercms(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)"; 
            $asg = $this->db->query($sqlss.$sqlrek); 
            
            if($asg){
                echo '2';
            }else{
                echo '0';
            }
        }
    }
    
    function update_master2(){
        $query = $this->input->post('st_query');
        $query2 = $this->input->post('sqlrek');
        $query3 = $this->input->post('lcid');
        $query4 = $this->input->post('xskpd');
        
        $sql = "delete from tr_panjar_transfercms where kd_skpd='$query4' and no_bukti='$query3'"; 
        $asg = $this->db->query($sql);
        
        $sql = "insert into tr_panjar_transfercms(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)"; 
        $asg = $this->db->query($sql.$sqlrek); 
        
        $asg = $this->db->query($query);
        if($asg){
            echo '1';
        }else{
            echo '0';
        }      
    }
    
    function load_dtrpanjar_transfercms(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_bukti,b.tgl_bukti,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,(select sum(nilai) from tr_panjar_transfercms where no_bukti=b.no_bukti and kd_skpd=b.kd_skpd and tgl_bukti=b.tgl_bukti) as total
                FROM tr_panjar_cmsbank a INNER JOIN tr_panjar_transfercms b ON a.no_kas=b.no_bukti
                AND a.kd_skpd=b.kd_skpd and a.tgl_kas=b.tgl_bukti
                WHERE b.no_bukti='$nomor' AND b.kd_skpd='$skpd'
                group by b.no_bukti,b.tgl_bukti,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai
                ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'                => $ii,        
                        'no_bukti'        => $resulte['no_bukti'],
                        'tgl_bukti'       => $resulte['tgl_bukti'],
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
    
    function cetak_list_panjarcms(){
        $kd_skpd = $this->session->userdata('kdskpd');
        $thn     = $this->session->userdata('pcThang');
        $tgl     = $this->uri->segment(3);
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd=left('$kd_skpd',17)+'.0000'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>".$kab."<br>LIST TRANSAKSI PANJAR</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE ".strtoupper($this->support->tanggal_format_indonesia($tgl))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"14\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</td>
            </tr>
            </table>";
            
            
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr> 
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold;\">SKPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"32%\" style=\"font-size:12px;font-weight:bold;\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"4%\" style=\"font-size:12px;font-weight:bold;\">ST</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;border-top:solid 1px black;\">7</td>
            </tr>
            </thead>";
                      
           $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
           $cek_skpd1 = $cek_skpd->hasil;
           if($cek_skpd1==1){
                $init_skpd = "a.kd_skpd='$kd_skpd'";
                $init_skpd2= "kode='$kd_skpd'";
           }else{
                $cek_skpd = substr($kd_skpd,18,4);           
                if($cek_skpd==0000){
                $init_skpd = "LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)";
                $init_skpd2= "left(kode,17)=left('$kd_skpd',17)";
                }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
                $init_skpd2= "left(kode,17)=left('$kd_skpd',17)";
                }              
           }           
           
           /*
           select '3' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,'Rek. Tujuan :' kegiatan,'' rekening, RTRIM(a.rekening_tujuan)+' , AN : '+RTRIM(a.nm_rekening_tujuan), 0 terima, a.nilai keluar, '' jns_spp, '' status_upload
            from trdtransout_transfercms a where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and $init_skpd                       
            
           */
           
           $no=0;
           $tot_terima=0;
           $tot_keluar=0;
           $sql = "SELECT z.* from (
            select '1' urut,a.kd_skpd,a.tgl_kas,a.no_kas,'' kegiatan,'' rekening, a.keterangan ket, 0 terima, 0 keluar, '' jns_spp, a.status_upload
            from tr_panjar_cmsbank a where year(a.tgl_kas) = '$thn' and a.tgl_kas='$tgl' and $init_skpd
            UNION
            select '2' urut,a.kd_skpd,a.tgl_kas,a.no_kas,a.kd_sub_kegiatan kegiatan,'' rekening, '' ket, 0 terima, a.nilai keluar, '' jns_spp, '' status_upload
            from tr_panjar_cmsbank a where year(a.tgl_kas) = '$thn' and a.tgl_kas='$tgl' and $init_skpd
            UNION
            select '3' urut,a.kd_skpd,a.tgl_bukti,a.no_bukti,'Rek. Tujuan : '+a.bank_tujuan kegiatan,'' rekening, RTRIM(a.rekening_tujuan)+' , AN : '+RTRIM(a.nm_rekening_tujuan), 0 terima, a.nilai keluar, '' jns_spp, '' status_upload
            from tr_panjar_transfercms a where year(a.tgl_bukti) = '$thn' and a.tgl_bukti='$tgl' and $init_skpd           
            
            )z order by z.kd_skpd,z.tgl_kas,cast (z.no_kas as int), z.urut";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;     
                        
            if($row->urut=='1'){                            
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:none;\">".$row->no_kas."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kd_skpd."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kegiatan.".".$row->rekening."</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->ket."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->status_upload."</td>                                       
                 </tr>";
                 }else if($row->urut=='3'){                            
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kegiatan."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->ket."&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".number_format($row->keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\"></td>                                       
                 </tr>";
                 }else{
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".$row->kegiatan.".".$row->rekening."</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".$row->ket."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".number_format($row->terima,2)."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".number_format($row->keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>                                        
                 </tr>";
                 }
                 
                 if($row->urut!='3'){
                    $tot_terima=$tot_terima+$row->terima; 
                    $tot_keluar=$tot_keluar+$row->keluar;  
                 }                 
                                  
             }
             
            $asql="SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK') a
            where tgl<='$tgl' and $init_skpd2";  
    
        $hasil=$this->db->query($asql);
        $bank=$hasil->row();
        $keluarbank=$bank->keluar;
        $terimabank=$bank->terima;
        $saldobank=$terimabank-$keluarbank;     
        
        $saldoakhirbank = (($saldobank+$tot_terima)-$tot_keluar);
            
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border-top:1px solid black;\">JUMLAH</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">".number_format($tot_terima,2)."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">".number_format($tot_keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"9\" style=\"font-size:11px;border:none;\"><br/></td>                                                   
                 </tr> 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Saldo Sampai Dengan Tanggal ".$this->tanggal_format_indonesia($tgl).", </td>                                                   
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($saldobank,2)."</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Terima</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($tot_terima,2)."</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Keluar</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($tot_keluar,2)."</td>                                                   
                 </tr>                                 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\"><hr/></td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Perkiraan Akhir Saldo, </td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($saldoakhirbank,2)."</td>                                                   
                 </tr>                                 
                                                  
            </table>";    
            
        $data['prev']= $cRet;    
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }  
    
    function load_total_trans_spd(){
       $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $no_bukti    = $this->input->post('no_simpan');
 
        $sql = "select total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                        select c.kd_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_kegiatan
                                    ) as d on a.kd_kegiatan=d.kd_kegiatan
                                    left join 
                                    (
                                        
                                        select z.kd_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
                                        where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_kegiatan
                                        UNION ALL
                                        select f.kd_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_kegiatan
                                        )z group by z.kd_kegiatan
                                        
                                    ) g on a.kd_kegiatan=g.kd_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_kegiatan
                                    ) z ON a.kd_kegiatan=z.kd_kegiatan
                                    where a.kd_kegiatan='$kegiatan'";      
        
        
        
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
    
}    
?>