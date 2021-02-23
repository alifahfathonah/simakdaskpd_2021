<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
class bpp_ploting extends CI_Controller {

    function __construct() 
    {    
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }
    }
    
    function ploting_bpp(){
        $data['page_title']= 'PLOTING BENDAHARA PEMBANTU';
        $this->template->set('title', 'PLOTING BENDAHARA PEMBANTU');   
        $this->template->load('template','anggaran/rka/ploting_bpp',$data) ; 
    }

   function skpd() {
        $lccr = $this->input->post('q');
        $skpd=$this->session->userdata('kdskpd');
        $sql = "SELECT right(kd_skpd,2) kode, kd_skpd, nama FROM [user] where left(kd_skpd,17)=left('$skpd',17) and right(kd_skpd,4) <> '0000' and bidang='55' and user_name<>'masterbpp' and (upper(kd_skpd) like upper('%$lccr%') or upper(nama) like upper('%$lccr%')) order by id_user ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nama'],
                        'kode'    => $resulte['kode'],
                        'jns' => 1
                        );
                        $ii++;
        }
           
        echo json_encode($result);
            $query1->free_result();
    }

   function subkeluar($kodeuser='') {
        $lccr = $this->input->post('q');
        $skpd=$this->session->userdata('kdskpd');
        
        $cek=$this->db->query("SELECT count(*) cek from trskpd where left(kd_gabungan,22)='$kodeuser' and right(left(kd_gabungan,22),4)<>'0000' ")->row()->cek;
        if($cek>0){
            $sql="SELECT 'BPP ini' kd_sub_kegiatan, 'Sudah dipilih secara default' nm_sub_kegiatan";
        }else{
            $sql = "SELECT kd_sub_kegiatan, nm_sub_kegiatan from trskpd where left(kd_skpd,17)=left('$skpd',17) and 
            kd_sub_kegiatan not in (select kd_sub_kegiatan from kegiatan_bp where left(kd_skpd,17)=left('$skpd',17)) and right(left(kd_gabungan,22),4)='0000'
            and (upper(kd_sub_kegiatan) like upper('%$lccr%') or upper(nm_sub_kegiatan) like upper('%$lccr%')) order by kd_sub_kegiatan ";            
        }

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function select_plot(){
        $lccr = $this->input->post('q');
        $user = $this->input->post('user');
        $skpd =$this->session->userdata('kdskpd');

        $cek=$this->db->query("SELECT count(*) cek from trskpd where left(kd_gabungan,22)='$user' and right(left(kd_gabungan,22),4)<>'0000'")->row()->cek;
        if($cek>0){
             $sql = "SELECT '' urut,kd_skpd,kd_sub_kegiatan, nm_sub_kegiatan from trskpd where kd_skpd='$user' order by kd_sub_kegiatan";
        }else{
            $sql = "SELECT id urut,kd_skpd,kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=kegiatan_bp.kd_sub_kegiatan) nm_sub_kegiatan from kegiatan_bp where kd_subskpd='$user' order by kd_sub_kegiatan";
        }
            
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],          
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],  
                        'urut' => $resulte['urut']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function select_plot_all(){
        $lccr = $this->input->post('q');
        $skpd=$this->session->userdata('kdskpd');
        $sql = "SELECT username, nm_lokasi, subkeluar, kd_lokasi from ms_lokasi where kd_skpd='$skpd' and username <> '' order by kd_lokasi";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'username' => $resulte['username'],       
                        'lokasi' => $resulte['nm_lokasi'],  
                        'subkeluar' => $resulte['subkeluar'],
                        'kd_lokasi' => $resulte['kd_lokasi']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function simpan(){
        $status = $this->input->post('status');
        $giat = $this->input->post('giat');
        $subskpd = $this->input->post('skpd');
        $skpd = $this->session->userdata('kdskpd');
        $urut = $this->input->post('urut');
        if($status=='tambah'){
            $user = $this->input->post('user');
      
                $this->db->query("INSERT INTO kegiatan_bp (kd_skpd, kd_sub_kegiatan, kd_subskpd) 
                values ('$skpd','$giat','$subskpd')");

                $this->db->query("UPDATE a SET
                a.kd_skpd=b.kd_subskpd
                from trdrka a inner join (
                select * from kegiatan_bp where kd_skpd='$skpd') b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                where a.kd_skpd='$skpd'");

                $this->db->query("UPDATE a SET
                a.kd_skpd=b.kd_subskpd
                from trskpd a inner join (
                select * from kegiatan_bp where kd_skpd='$skpd') b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                where a.kd_skpd='$skpd'");

                $this->db->query("UPDATE a SET
                a.kd_skpd=b.kd_subskpd
                from trdpo a inner join (
                select * from kegiatan_bp where kd_skpd='$skpd') b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                where a.kd_skpd='$skpd'");

                $this->db->query("UPDATE a SET
                a.kd_skpd=b.kd_subskpd
                from trdskpd_ro a inner join (
                select * from kegiatan_bp where kd_skpd='$skpd') b on a.kd_skpd=b.kd_skpd and a.kd_kegiatan = b.kd_sub_kegiatan
                where a.kd_skpd='$skpd'");

                echo "1";

        }else{
                    $this->db->query(" UPDATE a SET
                    a.kd_skpd=b.kd_skpd
                    from trdrka a inner join (select * from kegiatan_bp where id='$urut') b on 
                    a.kd_skpd=b.kd_subskpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                    where a.kd_skpd='$subskpd'");

                    $this->db->query("UPDATE a SET
                    a.kd_skpd=b.kd_skpd
                    from trskpd a inner join (select * from kegiatan_bp where id='$urut') b on 
                    a.kd_skpd=b.kd_subskpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                    where a.kd_skpd='$subskpd'");

                    $this->db->query("UPDATE a SET
                    a.kd_skpd=b.kd_skpd
                    from trdpo a inner join (select * from kegiatan_bp where id='$urut') b on 
                    a.kd_skpd=b.kd_subskpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                    where a.kd_skpd='$subskpd'");

                    $this->db->query("UPDATE a SET
                    a.kd_skpd=b.kd_skpd
                    from trdskpd_ro a inner join (select * from kegiatan_bp where id='$urut') b on 
                    a.kd_skpd=b.kd_subskpd and a.kd_subkegiatan = b.kd_sub_kegiatan
                    where a.kd_skpd='$subskpd'");

              echo $this->db->query("DELETE from kegiatan_bp where id='$urut'");
        }
    
    }

    function username(){
        $kdskpd=$this->input->post('skpd');
        $totalspd=$this->db->query("SELECT isnull(count(kd_skpd),0)+1 total from [user] where left(kd_skpd,17)=left('$kdskpd',17) and right(kd_skpd,4)<>'0000' and bidang='55' and id_user BETWEEN 250 and 499")->row()->total;

        if($totalspd<10){
            $belakang="0$totalspd";
        }else{
            $belakang=$totalspd;
        }

        $userpendek=$this->db->query("SELECT top 1 left(kd_skpd,4)+'.'+left(right(kd_skpd,7),2) ini from ms_skpd where kd_skpd='$kdskpd'");
        foreach($userpendek->result() as $abc){
                $blog=$abc->ini;
        }
        $code=substr($kdskpd,0,17);
        $result[] = array(
                        'id'=> 0,
                        'kode' => "$code.00$belakang",       
                        'urut' => $belakang,
                        'userbaru'=> "$blog.$belakang"
                        );

         echo json_encode($result);

    }

    function simpan_user_bpp_baru(){
         $userbpp= $this->input->post('userbpp');
         $kodebpp = $this->input->post('kodebpp');
         $nmbpp  = $this->input->post('nmbpp');
         $obnmskpd = $this->input->post('obnmskpd');
         $npwp = $this->input->post('npwp');
         $reke = $this->input->post('reke');
         $alamat = $this->input->post('alamat');
         $kdpos = $this->input->post('kdpos');
         $kd_bank =$this->input->post('kd_bank');
         $kdskpd= $this->input->post('skpd');
         $password=md5('bpkad');

         $this->db->query("INSERT into ms_skpd  (kd_skpd, nm_skpd, bank, rekening, alamat, npwp, obskpd, kodepos, kd_urusan) values ('$kodebpp','$nmbpp','$kd_bank','$reke','$alamat','$npwp','$obnmskpd','$kdpos', left('$kodebpp',4))");
         $this->db->query("INSERT  into trhrka (kd_skpd, statu, status_sempurna, status_ubah, no_dpa, tgl_dpa, status_rancang) select top 1 '$kodebpp' kd_skpd, statu, status_sempurna, status_ubah, no_dpa, tgl_dpa, status_rancang from trhrka where kd_skpd='$kdskpd'");
         $this->db->query("INSERT  INTO  sclient (kd_skpd, provinsi, kab_kota, daerah,thn_ang) SELECT top 1 '$kodebpp' kd_skpd, provinsi, kab_kota, daerah,thn_ang from sclient where kd_skpd='$kdskpd' ");

         $id_user=$this->db->query("SELECT max(id_user)+1 id from [user] WHERE id_user BETWEEN 250 and 499 ")->row()->id;

         $this->db->query("INSERT INTO [USER] (id_user, user_name, password, type, nama, kd_skpd, jenis, bidang, kunci) values ('$id_user', '$userbpp', '$password', '2', '$nmbpp', '$kodebpp', '2', '55', '0') ");

         $this->db->query("DELETE from otori where user_id='$id_user'");
         echo $this->db->query("INSERT otori
                select $id_user id_user, menu_id, akses from otori where user_id='201'
                ");

    }
}