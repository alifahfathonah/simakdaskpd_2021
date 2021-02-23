<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Master extends CI_Controller {

    function __contruct()
    {   
        parent::__construct();
    } 
     
    function test() 
    {  
 
    $this->load->dbutil();
    $query = $this->db->query("SELECT * FROM ms_bank");
    $config = array (
                  'root'    => 'root',
                  'element' => 'element',
                  'newline' => "\n", 
                  'tab'    => "\t"
                );

    echo $this->dbutil->xml_from_result($query, $config); 

    }
    
    function kunci_kasda()
    {
        $data['page_title']= 'KUNCI DATA KASDA';
        $this->template->set('title', 'KUNCI DATA KASDA');   
        $this->template->load('template','master/kunci_kasda',$data) ; 
    }

    function user_set()
    {
        $data['page_title']= 'USERNAME DAN PASSWORD';
        $this->template->set('title', 'USERNAME DAN PASSWORD');   
        $this->template->load('template','master/user/ganti_password',$data) ; 
    }    
    function simpan_kunci_kasda(){
        $tabel      = $this->input->post('tabel');
        $ctgl_awal   = $this->input->post('ctgl_awal');
        $ctgl_akhir   = $this->input->post('ctgl_akhir');
        
        
        $sql = "update $tabel set kd_bank=1 where tgl_kas BETWEEN '$ctgl_awal' AND '$ctgl_akhir'";
        $asg = $this->db->query($sql);
       
    }

    function mpengirim()
    {
        $data['page_title']= 'Master Pengirim STS';
        $this->template->set('title', 'Master Pengirim STS');   
        $this->template->load('template','master/pengirim_sts/mpengirim',$data); 
    }

    function load_mpengirim(){
      $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="and (upper(nm_pengirim) like upper('%$kriteria%') or kd_pengirim like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_pengirim where kd_skpd = '$kd_skpd' $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT * from ms_pengirim where kd_skpd = '$kd_skpd' $where order by kd_skpd ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_pengirim' => $resulte['kd_pengirim'],        
                        'nm_pengirim' => $resulte['nm_pengirim'],
                        'kd_skpd' => $resulte['kd_skpd']                                                                                 
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    } 
    
    function buka_kunci_kasda(){
        $tabel      = $this->input->post('tabel');
        $ctgl_awal   = $this->input->post('ctgl_awal');
        $ctgl_akhir   = $this->input->post('ctgl_akhir');
        
        
        $sql = "update $tabel set kd_bank=0 where tgl_kas BETWEEN '$ctgl_awal' AND '$ctgl_akhir'";
        $asg = $this->db->query($sql);
       
    }
    
    function fungsi()
    {   
     $this->index('0','ms_fungsi','kd_fungsi','nm_fungsi','Fungsi','fungsi','');
    }
    
    function urusan()
    {   
     $this->index('0','ms_urusan','kd_urusan','nm_urusan','Urusan','urusan','');
    }
    
    function skpd()
    {   
     $this->index('0','ms_skpd','kd_skpd','nm_skpd','SKPD','skpd','');
    }
    
    function unit()
    {   
     $this->index('0','ms_unit','kd_unit','nm_unit','Unit Kerja','unit','');
    }
    
    function program()
    {   
     $this->index('0','m_prog','kd_program','nm_program','Program','program','');
    }
    
    function kegiatan()
    {   
     $this->index('0','m_giat','kd_kegiatan','nm_kegiatan','Kegiatan','kegiatan','');
    }
    
    function rek1()
    {   
     $this->index('0','ms_rek1','kd_rek1','nm_rek1','Rekening Akun','rek1','');
    }
    
    function rek2()
    {   
     $this->index('0','ms_rek2','kd_rek2','nm_rek2','Rekening Kelompok','rek2','');
    }
    
    function rek3()
    {   
     $this->index('0','ms_rek3','kd_rek3','nm_rek3','Rekening Jenis','rek3','');
    }
    
    function rek4()
    {   
     $this->index('0','ms_rek4','kd_rek4','nm_rek4','Rekening Objek','rek4','');
    }
    
    function rek5()
    {   
     $this->index('0','ms_rek5','kd_rek5','nm_rek5','Rekening Rincian Objek','rek5','');
    }
    
    function ttd()
    {   
     $this->index('0','ms_ttd','nip','nama','Penandatangan','ttd','');
    }    
    
    function bank()
    {   
     $this->index('0','ms_bank','kode','nama','Bank','bank','');
    }
    function user()
    {   
     $this->index('0','[user]','id_user','nama','User','user','');
    }
    function user_online()
    {   
     $this->index2('0','[user]','id_user','nama','User Online','user_online','');
    }
    
    function cari1($id=''){
        $tabel      = $this->input->post('tabel');
        $field      = $this->input->post('field');
        $result = $this->master_model->get_by_id_top1($tabel,$field,$id)->num_rows();
        echo json_encode($result);
    }
    
//kunci_renja 
    function kunci_renja()
    {   
        $this->index_bidang('0','[user]','bidang','4','kd_skpd','Proses Penyusunan ke Penetapan','kunci_renja','');
    }


    
    function tombol_kunci_renja(){
        $id = $this->uri->segment(3);
        //$usern = $this->uri->segment(4);
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('[user]','kd_skpd',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/user');
        else:
            /*$sql1 = "select kd_skpd from trdrka_rancang where sumber=''  and nilai <> 0  and kd_skpd='$id' group by kd_skpd";
            $q_cek = $this->db->query($sql1);
            $num_rows = $q_cek->num_rows();
            if($num_rows<1){*/
                $sql = "Update [user] SET kunci='1',status='0' WHERE kd_skpd = '$id' and bidang='4'";
                $query1 = $this->db->query($sql);  

                $sql = "Update [user] SET kunci='0',status='0' WHERE kd_skpd = '$id' and bidang='5'";
                $query1 = $this->db->query($sql);  
                
                $sql = "update trskpd set status_keg=1 where kd_skpd='$id'";
                $query = $this->db->query($sql);  

                $sql = "update trskpd_rancang set status_keg=1 where kd_skpd='$id'";
                $query = $this->db->query($sql);  
                
                $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' telah Dikunci !');
                redirect('master/kunci_renja');
            /*}else{
                $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' gagal Dikunci. Silakan Cek Pemberitahuan Welcome di SKPD tersebut!');
                redirect('master/kunci_renja');
            }*/
            
        endif;
    }
    
    function tombol_penetapan_renja()
    {
        $id = $this->uri->segment(3);
        $usern = $this->uri->segment(4);
        //$bidang = $this->session->userdata('Sbidang');  
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('[user]','kd_skpd',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/user');
        else:
            $sql = "delete a from otori a join [user] b on a.user_id=b.id_user
                    where b.bidang='4' and a.menu_id in ('151a','153f','153g','153h','153i','153j','153k') 
                    and kd_skpd='$id'";
            $query = $this->db->query($sql);  

            $sql = "delete a from otori a join [user] b on a.user_id=b.id_user
                    where b.bidang='4' and a.menu_id in ('152','154','155','156','157','158','159') 
                    and kd_skpd='$id'";
            $query = $this->db->query($sql);  

            $sql = "update [user] set kunci=3 where bidang='4' and kd_skpd='$id'";
            $query = $this->db->query($sql);  

            $sql2 = "Update trskpd_rancang SET status_keg='1' WHERE kd_skpd = '$id'";
            $query2 = $this->db->query($sql2);  
            
            $sql = "update trskpd set status_keg=0 where kd_skpd='$id'";
            $query = $this->db->query($sql);  

            $sql4 = "Update trhrka SET status_rancang='1',tgl_dpa_rancang=getdate() WHERE kd_skpd = '$id'";
            $query4 = $this->db->query($sql4);  
            
            
            $sql = "insert into otori
                    select id_user,'152','1' from [user] where bidang='4' and kd_skpd='$id' 
                    insert into otori
                    select id_user,'154','1' from [user] where bidang='4' and kd_skpd='$id'
                    insert into otori
                    select id_user,'155','1' from [user] where bidang='4' and kd_skpd='$id'
                    insert into otori
                    select id_user,'156','1' from [user] where bidang='4' and kd_skpd='$id'
                    insert into otori
                    select id_user,'157','1' from [user] where bidang='4' and kd_skpd='$id'
                    insert into otori
                    select id_user,'158','1' from [user] where bidang='4' and kd_skpd='$id'
                    insert into otori
                    select id_user,'159','1' from [user] where bidang='4' and kd_skpd='$id'";
            $query = $this->db->query($sql);  

            
            //$this->session->set_fla ('notify', 'User '.$usern.' telah Dikunci !');
            $this->session->set_flashdata('notify', 'User '.$usern.' Sudah Masuk Ke Penetapan !');
            redirect('master/kunci_renja');
            
        endif;
    }
    
//end kunci_renja   

//buka_kunci renja
    function buka_kunci_renja(){    
        $this->index_bidang('0','[user]','bidang','4','kd_skpd','Buka/ Kunci Renja','buka_kunci_renja','');
    }

    function tombol_kunci_renja_semua()
    {
                $sql = "Update [user] SET kunci='1',status='0' WHERE bidang='4'";
                $query1 = $this->db->query($sql); 

                $this->session->set_flashdata('notify', 'Semua Renja SKPD telah Dikunci !');
                redirect('master/buka_kunci_renja');
    }

    function kunci_akun(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql = "Update [user] SET kunci='$kunci' WHERE bidang='4' and left(kd_skpd,20)=left('$id',20)";
        $query1 = $this->db->query($sql); 

        $this->session->set_flashdata('notify', 'Akun '.$id.' Telah diKunci ');
        redirect('master/buka_kunci_renja');
    }

function akses_input_murni(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql="UPDATE [user] set kunci_murni='$kunci' where left(kd_skpd,20)=left('$id',20)";
        $exe=$this->db->query($sql);
        if($exe){
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Telah Dikunci !');
            redirect('master/buka_kunci_renja');            
        }else{
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Gagal Dikunci !');
            redirect('master/buka_kunci_renja');
        } 
}

function akses_input_angkas_murni(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql="UPDATE [user] set kunci_angkas_m='$kunci' where left(kd_skpd,20)=left('$id',20)";
        $exe=$this->db->query($sql);
        if($exe){
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Telah Dikunci !');
            redirect('master/buka_kunci_renja');            
        }else{
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Gagal Dikunci !');
            redirect('master/buka_kunci_renja');
        } 
}

function akses_input_geser(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql="UPDATE [user] set kunci_geser='$kunci' where left(kd_skpd,20)=left('$id',20)";
        $exe=$this->db->query($sql);
        if($exe){
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Telah Dikunci !');
            redirect('master/buka_kunci_renja');            
        }else{
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Gagal Dikunci !');
            redirect('master/buka_kunci_renja');
        } 
}

function akses_input_angkas_geser(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql="UPDATE [user] set kunci_angkas_g='$kunci' where left(kd_skpd,20)=left('$id',20)";
        $exe=$this->db->query($sql);
        if($exe){
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Telah Dikunci !');
            redirect('master/buka_kunci_renja');            
        }else{
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Gagal Dikunci !');
            redirect('master/buka_kunci_renja');
        } 
}
function akses_input_ubah(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql="UPDATE [user] set kunci_ubah='$kunci' where left(kd_skpd,20)=left('$id',20)";
        $exe=$this->db->query($sql);
        if($exe){
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Telah Dikunci !');
            redirect('master/buka_kunci_renja');            
        }else{
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Gagal Dikunci !');
            redirect('master/buka_kunci_renja');
        } 
}

function akses_input_angkas_ubah(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql="UPDATE [user] set kunci_angkas_u='$kunci' where left(kd_skpd,20)=left('$id',20)";
        $exe=$this->db->query($sql);
        if($exe){
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Telah Dikunci !');
            redirect('master/buka_kunci_renja');            
        }else{
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Gagal Dikunci !');
            redirect('master/buka_kunci_renja');
        } 
}

function kunci_keseluruhan(){
        $id = $this->uri->segment(3);
        $kunci = $this->uri->segment(4);
        $sql="UPDATE [user] set kunci_angkas_u='$kunci' where left(kd_skpd,20)=left('$id',20)";
        $exe=$this->db->query($sql);
        if($exe){
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Telah Dikunci !');
            redirect('master/buka_kunci_renja');            
        }else{
            $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' Gagal Dikunci !');
            redirect('master/buka_kunci_renja');
        } 
}







    function tombol_kunci_renja2(){
        $id = $this->uri->segment(3);
        //$usern = $this->uri->segment(4);
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('[user]','kd_skpd',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/user');
        else:
            /*$sql1 = "select kd_skpd from trdrka_rancang where sumber=''  and nilai <> 0  and kd_skpd='$id' group by kd_skpd";
            $q_cek = $this->db->query($sql1);
            $num_rows = $q_cek->num_rows();
            if($num_rows<1){*/
                $sql = "Update [user] SET kunci='1',status='0' WHERE kd_skpd = '$id' and bidang='4'";
                $query1 = $this->db->query($sql);  

                $sql = "Update [user] SET kunci='0',status='0' WHERE kd_skpd = '$id' and bidang='5'";
                $query1 = $this->db->query($sql);  
                
                $sql = "update trskpd set status_keg=1 where kd_skpd='$id'";
                $query = $this->db->query($sql);  

                $sql = "update trskpd_rancang set status_keg=1 where kd_skpd='$id'";
                $query = $this->db->query($sql);  
                
                $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' telah Dikunci !');
                redirect('master/buka_kunci_renja');
            /*}else{
                $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' gagal Dikunci. Silakan Cek Pemberitahuan Welcome di SKPD tersebut!');
                redirect('master/kunci_renja');
            }*/
            
        endif;
    }
    
    function tombol_buka_renja(){
        $id = $this->uri->segment(3);
        //$usern = $this->uri->segment(4);
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('[user]','kd_skpd',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/user');
        else:
            /*$sql1 = "select kd_skpd from trdrka_rancang where sumber=''  and nilai <> 0  and kd_skpd='$id' group by kd_skpd";
            $q_cek = $this->db->query($sql1);
            $num_rows = $q_cek->num_rows();
            if($num_rows<1){*/
                $sql = "Update [user] SET kunci='0',status='0' WHERE kd_skpd = '$id' and bidang='4'";
                $query1 = $this->db->query($sql);  

                $sql = "Update [user] SET kunci='0',status='0' WHERE kd_skpd = '$id' and bidang='5'";
                $query1 = $this->db->query($sql);   

                
                $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' telah Dibuka !');
                redirect('master/buka_kunci_renja');
            /*}else{
                $this->session->set_flashdata('notify', 'Renja SKPD '.$id.' gagal Dikunci. Silakan Cek Pemberitahuan Welcome di SKPD tersebut!');
                redirect('master/kunci_renja');
            }*/
            
        endif;
    }
    
//end buka_kunci renja

    function sclient()
    {
        $data['page_title']= 'MASTER SCLIENT';
        $this->template->set('title', 'INPUT MASTER SCLIENT');   
        $this->template->load('template','master/sclient',$data) ; 
    }
    
     function tapd()
    {
        $data['page_title']= 'MASTER TAPD';
        $this->template->set('title', 'INPUT TAPD');   
        $this->template->load('template','master/tapd',$data) ; 
    }
    
    function index($offset=0,$lctabel,$field,$field1,$judul,$list,$lccari)
    {
        $data['page_title'] = "Master Data $judul";
        if(empty($lccari)){
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        }else{
            $total_rows = $this->master_model->get_count_teang($lctabel,$field,$field1,$lccari);
            $lc = "";
        }
        if(empty($lccari)){
        $config['base_url']     = site_url("master/".$list);
        }else{
        $config['base_url']     = site_url("master/cari_".$list);    
        }
        $config['total_rows']   = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']  = 3;
        $config['num_links']    = 5;
        $config['full_tag_open'] = '<ul class="page-navi">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="current">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $limit                  = $config['per_page'];  
        $offset                 = $this->uri->segment(3);  
        $offset                 = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
          
        if(empty($offset))  
        {  
            $offset=0;  
        }
        //$data['isi']=$this->aktif_menu();
        //$data['isi']=$this->aktif_menu(); 
        //$data['isi']= $this->session->userdata('lcisi');         
        //$data['list']         = $this->master_model->getAll($lctabel,$field,$limit, $offset);
         if(empty($lccari)){     
        $data['list']       = $this->master_model->getAll($lctabel,$field,$limit, $offset);
        }else {
            $data['list']       = $this->master_model->getCari($lctabel,$field,$field1,$limit, $offset,$lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;
        
                $this->pagination->initialize($config);
        $a=$judul;
        $this->template->set('title', 'Master Data ');
        $this->template->load('template', "master/".$list."/list", $data);
    }
    
    function index2($offset=0,$lctabel,$field,$field1,$judul,$list,$lccari)
    {
        $data['page_title'] = "Master Data $judul";
        if(empty($lccari)){
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        }else{
            $total_rows = $this->master_model->get_count_teang($lctabel,$field,$field1,$lccari);
            $lc = "";
        }
        if(empty($lccari)){
        $config['base_url']     = site_url("master/".$list);
        }else{
        $config['base_url']     = site_url("master/cari_".$list);    
        }
        $config['total_rows']   = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']  = 3;
        $config['num_links']    = 5;
        $config['full_tag_open'] = '<ul class="page-navi">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="current">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $limit                  = $config['per_page'];  
        $offset                 = $this->uri->segment(3);  
        $offset                 = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
          
        if(empty($offset))  
        {  
            $offset=0;  
        }
        //$data['isi']=$this->aktif_menu();
        //$data['isi']=$this->aktif_menu(); 
        //$data['isi']= $this->session->userdata('lcisi');         
        //$data['list']         = $this->master_model->getAll($lctabel,$field,$limit, $offset);
         if(empty($lccari)){     
        $data['list']       = $this->master_model->getAll2($lctabel,$field,$limit, $offset);
        }else {
            $data['list']       = $this->master_model->getCari($lctabel,$field,$field1,$limit, $offset,$lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;
        
                $this->pagination->initialize($config);
        $a=$judul;
        $this->template->set('title', 'Master Data ');
        $this->template->load('template', "master/".$list."/list", $data);
    }
    
    function index_bidang($offset=0,$lctabel,$field,$data1,$field_older,$judul,$list,$lccari)
    {
        $data['page_title'] = "Master Data $judul";
        if(empty($lccari)){
            $total_rows = $this->master_model->get_count_cari2($lctabel,$field,$data1,$field_older);
            $lc = "/.$lccari";
        }else{
            $total_rows = $this->master_model->get_count_teang($lctabel,$field,$data1,$lccari);
            $lc = "";
        }
        if(empty($lccari)){
        $config['base_url']     = site_url("master/".$list);
        }else{
        $config['base_url']     = site_url("master/cari_".$list);    
        }
        $config['total_rows']   = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']  = 3;
        $config['num_links']    = 5;
        $config['full_tag_open'] = '<ul class="page-navi">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="current">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $limit                  = $config['per_page'];  
        $offset                 = $this->uri->segment(3);  
        $offset                 = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
          
        if(empty($offset))  
        {  
            $offset=0;  
        }
        //$data['isi']=$this->aktif_menu();
        //$data['isi']=$this->aktif_menu(); 
        //$data['isi']= $this->session->userdata('lcisi');         
        //$data['list']         = $this->master_model->getAll($lctabel,$field,$limit, $offset);
         if(empty($lccari)){     
          $data['list']         = $this->master_model->getAll_bidang($lctabel,$field,$data1,$field_older,$limit, $offset);
        }else {
            $data['list']       = $this->master_model->getCari($lctabel,$field,$field1,$limit, $offset,$lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;
        
                $this->pagination->initialize($config);
        $a=$judul;
        $this->template->set('title', 'Master Data ');
        $this->template->load('template', "master/".$list."/list", $data);
    }
    
    function logout_user()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('[user]','id_user',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/user');
        
        else:
        
            $sql = "Update [user] SET status='0' WHERE id_user = '$id'";
            $query1 = $this->db->query($sql);  
            $this->session->set_flashdata('notify', 'User telah Log Out !');
            redirect('master/user_online');
            
        endif;
    }

    
    function cetak_fungsi(){
            $data['page_title'] = "Master Data Fungsi";
            $data['list']       = $this->master_model->getAllc('ms_fungsi','kd_fungsi');
            //$this->template->load('template','master/fungsi/list_preview', $data);
            $this->load->view('master/fungsi/list_preview', $data);
    }
    
    
    function get_sclient(){
      
         $sql = "SELECT kd_skpd,thn_ang,provinsi,kab_kota,daerah,tgl_rka,tgl_dpa,tgl_ubah,tgl_dppa,rek_kasda,rek_kasin,rek_kasout,rk_skpd,rk_skpkd,spd_head1,spd_head2,spd_head3,spd_head4,
                ingat1,ingat2,ingat3,ingat4,ingat5 FROM sclient";
                
        $query1 = $this->db->query($sql);  

        
        //$test = "hai";
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'thn_ang' => $resulte['thn_ang'],
                        'provinsi' => $resulte['provinsi'],
                        'kab_kota' => $resulte['kab_kota'],
                        'daerah' => $resulte['daerah'],
                        'tgl_rka' => $resulte['tgl_rka'],
                        'tgl_dpa' => $resulte['tgl_dpa'],
                        'tgl_ubah' => $resulte['tgl_ubah'],
                        'tgl_dppa' => $resulte['tgl_dppa'],
                        'rek_kasda' => $resulte['rek_kasda'],
                        'rek_kasin' => $resulte['rek_kasin'],
                        'rek_kasout' => $resulte['rek_kasout'],
                        'rk_skpd' => $resulte['rk_skpd'],
                        'rk_skpkd' => $resulte['rk_skpkd'],
                        'head1' => $resulte['spd_head1'],
                        'head2' => $resulte['spd_head2'],
                        'head3' => $resulte['spd_head3'],
                        'head4' => $resulte['spd_head4'],
                        'ingat1' => $resulte['ingat1'],
                        'ingat2' => $resulte['ingat2'],
                        'ingat3' => $resulte['ingat3'],
                        'ingat4' => $resulte['ingat4'],
                        'ingat5' => $resulte['ingat5']
                        );
                        $ii++;
        }
        
        if ($test===0){
            $result = array(
                        'kd_skpd' => '',
                        'thn_ang' => '',
                        'provinsi' => '',
                        'kab_kota' => '',
                        'daerah' => '',
                        'tgl_rka' => '',
                        'tgl_dpa' => '',
                        'tgl_ubah' => '',
                        'tgl_dppa' => '',
                        'rek_kasda' => '',
                        'rek_kasin' => '',
                        'rek_kasout' => '',
                        'rk_skpd' => '',
                        'rk_skpkd' => '',
                        'spd_head1' => '',
                        'spd_head2' => '',
                        'spd_head3' => '',
                        'spd_head4' => '',
                        'ingat1' => '',
                        'ingat2' => '',
                        'ingat3' => '',
                        'ingat4' => '',
                        'ingat5' => ''
                        );
                        $ii++;
        }       
        
        
        echo json_encode($result);
        $query1->free_result();   
    }
    
    function simpan_sclient(){
        $tabel      = $this->input->post('tabel');
        $cskpd      = $this->input->post('cskpd');
        $cthn       = $this->input->post('cthn');
        $cprov      = $this->input->post('cprov');
        $ckab       = $this->input->post('ckab');
        $cibu       = $this->input->post('cibu');
        $ctgl_rka   = $this->input->post('ctgl_rka');
        $ctgl_dpa   = $this->input->post('ctgl_dpa');
        $ctgl_ubah  = $this->input->post('ctgl_ubah');
        $ctgl_dppa  = $this->input->post('ctgl_dppa');
        $crek_kasda = $this->input->post('crek_kasda');
        $crek_kasin = $this->input->post('crek_kasin');
        $crek_kasout = $this->input->post('crek_kasout');
        $crk_skpd   = $this->input->post('crk_skpd');
        $crk_skpkd  = $this->input->post('crk_skpkd');
        $chead1     = $this->input->post('chead1');
        $chead2     = $this->input->post('chead2');
        $chead3     = $this->input->post('chead3');
        $chead4     = $this->input->post('chead4');
        $cingat1    = $this->input->post('cingat1');
        $cingat2    = $this->input->post('cingat2');
        $cingat3    = $this->input->post('cingat3');
        $cingat4    = $this->input->post('cingat4');
        $cingat5    = $this->input->post('cingat5');
       
        
        
        $sql = "delete from sclient ";
        $asg = $this->db->query($sql);
        
        if($asg){
            $sql = "insert into sclient(kd_skpd,thn_ang,provinsi,kab_kota,daerah,tgl_rka,tgl_dpa,tgl_ubah,tgl_dppa,rek_kasda,rek_kasin,rek_kasout,rk_skpd,rk_skpkd,
                    spd_head1,spd_head2,spd_head3,spd_head4,ingat1,ingat2,ingat3,ingat4,ingat5) 
            values('$cskpd','$cthn','$cprov','$ckab','$cibu','$ctgl_rka','$ctgl_dpa','$ctgl_ubah','$ctgl_dppa','$crek_kasda','$crek_kasin','$crek_kasout','$crk_skpd','$crk_skpkd',
            '$chead1','$chead2','$chead3','$chead4','$cingat1','$cingat2','$cingat3','$cingat4','$cingat5')";
            $asg = $this->db->query($sql);
             
        }
       
    }
    
        function get_tapd(){
      
         $sql = "SELECT no,nip,nama,jabatan FROM tapd";
                
        $query1 = $this->db->query($sql);  

        
        //$test = "hai";
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }
        
        if ($test===0){
            $result = array(
                        'nip' => '',
                        'nama' => '',
                        'jabatan' => ''
                        );
                        $ii++;
        }       
        
        
        echo json_encode($result);
        $query1->free_result();   
    }
    
    // Tamba data
    function tambah_fungsi()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_fungsi',
                     'label'   => 'Kd Fungsi',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_fungsi',
                     'label'   => 'Nm Fungsi',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Fungsi &raquo; Tambah";
        }
        else
        {
                                
            $data = array(
                        'kd_fungsi' => $this->input->post('kd_fungsi'),
                        'nm_fungsi' => $this->input->post('nm_fungsi'),
                        );
                        
            $this->master_model->save('ms_fungsi',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/fungsi');

        }
        
        $this->template->set('title', 'Master Data Fungsi &raquo; Tambah Data');
        $this->template->load('template', 'master/fungsi/tambah', $data);
    }
    
    function cari_fungsi()
    {
        
        $lccr =  $this->input->post('pencarian');
        $this->index('0','ms_fungsi','kd_fungsi','nm_fungsi','Fungsi','fungsi',$lccr);

    }
    
    // Ubah data
    function edit_fungsi()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_fungsi','kd_fungsi',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/fungsi');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_fungsi',
                     'label'   => 'Kd Fungsi',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_fungsi',
                     'label'   => 'Nm Fungsi',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Fungsi &raquo; Ubah Data";
            $data['fungsi'] = $this->master_model->get_by_id('ms_fungsi','kd_fungsi',$id)->row();
        }
        else
        {
                                
            $data = array(
                        'kd_fungsi' => $this->input->post('kd_fungsi'),
                        'nm_fungsi' => $this->input->post('nm_fungsi'),
                        );
                        
            $this->master_model->update('ms_fungsi','kd_fungsi',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/fungsi');

        }
        
        $this->template->set('title', 'Master Data Fungsi &raquo; Ubah Data');
        $this->template->load('template', 'master/fungsi/edit', $data);
    }
    
    // hapus data
    function hapus_fungsi()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_fungsi','kd_fungsi',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/fungsi');
        
        else:
                        
            $this->master_model->delete('ms_fungsi','kd_fungsi',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/fungsi');
            
        endif;
    }
    
    function preview_fungsi(){
        $cRet='';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER FUNGSI</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE FUNGSI</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA FUNGSI</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td></tr>
                        ";
                 
                 //$query = $this->db->query('SELECT kd_fungsi,nm_fungsi FROM ms_fungsi');
                 $query = $this->master_model->getAllc('ms_fungsi','kd_fungsi');

                foreach ($query->result() as $row)
                {
                    $coba1=$row->kd_fungsi;
                    $coba2=$row->nm_fungsi;
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba2</td></tr>";
                }
              
        $cRet    .= "</table>";
        $data['prev']= $cRet;    
        $this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        
                
    }
    
        function cari_urusan()
    {
        
    $lccr =  $this->input->post('pencarian');
        $this->index('0','ms_urusan','kd_urusan','nm_urusan','Urusan','urusan',$lccr);
    }
    
    // Tamba data
    function tambah_urusan()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_urusan',
                     'label'   => 'Kd Urusan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_urusan',
                     'label'   => 'Nm Urusan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_fungsi',
                     'label'   => 'kd_fungsi',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data urusan &raquo; Tambah";
            $lc = "select kd_fungsi,nm_fungsi from ms_fungsi  order by kd_fungsi";
            $query = $this->db->query($lc);
            $data["kdfungsi"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_urusan' => $this->input->post('kd_urusan'),
                        'nm_urusan' => $this->input->post('nm_urusan'),
                        'kd_fungsi' => $this->input->post('kd_fungsi')
                        );
                        
            $this->master_model->save('ms_urusan',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/urusan');

        }
        
        $this->template->set('title', 'Master Data Urusan &raquo; Tambah Data');
        $this->template->load('template', 'master/urusan/tambah', $data);
    }
    
    // Ubah data
    function edit_urusan()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_urusan','kd_urusan',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/urusan');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_urusan',
                     'label'   => 'Kd Urusan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_urusan',
                     'label'   => 'Nm Urusan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_fungsi',
                     'label'   => 'kd_fungsi',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data urusan &raquo; Ubah Data";
            $data['urusan'] = $this->master_model->get_by_id('ms_urusan','kd_urusan',$id)->row();
            $lc = "select kd_fungsi,nm_fungsi from ms_fungsi  order by kd_fungsi";
            $query = $this->db->query($lc);
            $data["kdfungsi"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_urusan' => $this->input->post('kd_urusan'),
                        'nm_urusan' => $this->input->post('nm_urusan'),
                        'kd_fungsi' => $this->input->post('kd_fungsi')
                        );
                        
            $this->master_model->update('ms_urusan','kd_urusan',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data  berhasil diupdate !');
            
            redirect('master/urusan');

        }
        
        $this->template->set('title', 'Master Data urusan &raquo; Ubah Data');
        $this->template->load('template', 'master/urusan/edit', $data);
    }
    
    // hapus data
    function hapus_urusan()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_urusan','kd_urusan',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/urusan');
        
        else:
                        
            $this->master_model->delete('ms_urusan','kd_urusan',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/urusan');
            
        endif;
    }
    
    function preview_urusan(){
        $cRet='';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER URUSAN</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE URUSAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA URUSAN</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td></tr>
                        ";
                 
                $query = $this->master_model->getAllc('ms_urusan','kd_urusan');

                foreach ($query->result() as $row)
                {
                    $coba1=$row->kd_urusan;
                    $coba2=$row->nm_urusan;
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba2</td></tr>";
                }
              
        $cRet    .= "</table>";
        $data['prev']= $cRet;    
        $this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/urusan/list_preview',$data);
        
                
    }
    
    function cari_skpd()
    {
        
        $lccr =  $this->input->post('pencarian');
        $this->index('0','ms_skpd','kd_skpd','nm_skpd','SKPD','skpd',$lccr);
    }
    
    // Tamba data
    function tambah_skpd()
    {
        $this->load->model('master_model');
        $config = array(
               array(
                     'field'   => 'kd_urusan',
                     'label'   => 'Kode Urusan',
                     'rules'   => 'trim|required'
                  ),               
               array(
                     'field'   => 'kd_skpd',
                     'label'   => 'Kode Skpd',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_skpd',
                     'label'   => 'Nama Skpd',
                     'rules'   => 'trim|required'
                  ),     
               array(
                     'field'   => 'npwp',
                     'label'   => 'NPWP',
                     'rules'   => 'trim|required'
                  )
               );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening SKPD &raquo; Tambah";
            $lc = "select a.kd_urusan,a.nm_urusan,a.kd_fungsi,b.nm_fungsi from ms_urusan a inner join ms_fungsi b on a.kd_fungsi=b.kd_fungsi where a.tipe='S' order by a.kd_urusan";
            $query = $this->db->query($lc);
            $data["kdurus"]=$query->result();

            
        }
        else
        {
                                
            $data = array(
                        'kd_urusan' => $this->input->post('kd_urusan'),                        
                        'kd_skpd' => $this->input->post('kd_skpd'),
                        'nm_skpd' => $this->input->post('nm_skpd'),                        
                        'npwp' => $this->input->post('npwp')
                        );
                        
            $this->master_model->save('ms_skpd',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/skpd');

        }
        
        $this->template->set('title', 'Master Data Rekening skpd &raquo; Tambah Data');
        $this->template->load('template', 'master/skpd/tambah', $data);
    }
    
    // Ubah data
    function edit_skpd()
    {
       $this->load->model('master_model');
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_skpd','kd_skpd',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/skpd');
        
        endif;
        
        $config = array(
             array(
                     'field'   => 'kd_urusan',
                     'label'   => 'Kode Urusan',
                     'rules'   => 'trim|required'
                  ),
             array(
                     'field'   => 'kd_skpd',
                     'label'   => 'Kode Skpd',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_skpd',
                     'label'   => 'Nama Skpd',
                     'rules'   => 'trim|required'
                  ),     
              array(
                     'field'   => 'npwp',
                     'label'   => 'NPWP',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data SKPD &raquo; Ubah Data";
            $data['skpd'] = $this->master_model->get_by_id('ms_skpd','kd_skpd',$id)->row();
            $lc = "select kd_urusan,nm_urusan from ms_urusan where tipe='S' order by kd_urusan";
            $query = $this->db->query($lc);
            $data["kdurus"]=$query->result();

            

        }
        else
        {
                                
            $data = array(
                        'kd_urusan' => $this->input->post('kd_urusan'),                        
                        'kd_skpd' => $this->input->post('kd_skpd'),
                        'nm_skpd' => $this->input->post('nm_skpd'),                        
                        'npwp' => $this->input->post('npwp'),
                        );
                        
            $this->master_model->update('ms_skpd','kd_skpd',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/skpd');

        }
        
        $this->template->set('title', 'Master Data Rekening SKPD &raquo; Ubah Data');
        $this->template->load('template', 'master/skpd/edit', $data);
    }
    
    // hapus data
    function hapus_skpd()
    {
       $this->load->model('master_model');
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_skpd','kd_skpd',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/skpd');
        
        else:
                        
            $this->master_model->delete('ms_skpd','kd_skpd',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/skpd');
            
        endif;
    }
    
     function preview_skpd(){
        $cRet='';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"4\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER SKPD</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE SKPD</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE URUSAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA SKPD</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NPWP</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td></tr>
                        ";
                 
                 //$query = $this->db->query('SELECT kd_fungsi,nm_fungsi FROM ms_fungsi');
                 $query = $this->master_model->getAllc('ms_skpd','kd_skpd');

                foreach ($query->result() as $row)
                {
                    $coba1=$row->kd_skpd;
                    $coba2=$row->kd_urusan;
                    $coba3=$row->nm_skpd;
                    $coba4=$row->npwp;
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba3</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba4</td></tr>";
                }
              
        $cRet    .= "</table>";
        $data['prev']= $cRet;    
        $this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        
                
    }
    
    function tambah_unit()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_unit',
                     'label'   => 'kd_unit',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_unit',
                     'label'   => 'nm_unit',
                     'rules'   => 'trim|required'
                  ),               
               array(
                     'field'   => 'kd_skpd',
                     'label'   => 'Kode Skpd',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Unit Kerja &raquo; Tambah";
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_unit' => $this->input->post('kd_unit'),
                        'nm_unit' => $this->input->post('nm_unit'),                        
                        'kd_skpd'=>$this->input->post('kd_skpd')                                        
                        
                        );
                        
            $this->master_model->save('ms_unit',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/unit');

        }
        
        $this->template->set('title', 'Master Unit Kerja &raquo; Tambah Data');
        $this->template->load('template', 'master/unit/tambah', $data);
    }
    
    // Ubah data
    function edit_unit()
    {
        $id = $this->uri->segment(3);
        $id=str_replace('%20',' ',$id);
        //echo($id);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_unit','kd_unit',$id)->num_rows() <= 0 ) ) :
             echo($id); 
            redirect('master/unit');
        
        endif;
        
        $config = array(
                array(
                     'field'   => 'kd_unit',
                     'label'   => 'kd_unit',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_unit',
                     'label'   => 'nm_unit',
                     'rules'   => 'trim|required'
                  ),               
               array(
                     'field'   => 'kd_skpd',
                     'label'   => 'Kode Skpd',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Unit Kerja &raquo; Ubah Data";
            $data['unit'] = $this->master_model->get_by_id('ms_unit','kd_unit',$id)->row();
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"]=$query->result();
            
  
        }
        else
        {
                                
            $data = array(
                        'kd_unit' => $this->input->post('kd_unit'),
                        'nm_unit' => $this->input->post('nm_unit'),                        
                        'kd_skpd'=>$this->input->post('kd_skpd')  
                        );
                        
            $this->master_model->update('ms_unit','kd_unit',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data berhasil diupdate !');
            
            redirect('master/unit');

        }
        
        $this->template->set('title', 'Master Unit Kerja &raquo; Ubah Data');
        $this->template->load('template', 'master/unit/edit', $data);
    }
    
    // hapus data
    function hapus_unit()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_unit','kd_unit',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/unit');
        
        else:
                        
            $this->master_model->delete('ms_unit','kd_unit',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/unit');
            
        endif;
    }
    
    function cari_program()
    {
        
        $lccr =  $this->input->post('pencarian');
        $this->index('0','m_prog','kd_program','nm_program','Program','program',$lccr);
    }
    
    // Tamba data
    function tambah_program()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_program',
                     'label'   => 'Kd program',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_program',
                     'label'   => 'Nm program',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Program &raquo; Tambah";
        }
        else
        {
                                
            $data = array(
                        'kd_program' => $this->input->post('kd_program'),
                        'nm_program' => $this->input->post('nm_program'),
                        );
                        
            $this->master_model->save('m_prog',$data);
                        
            $this->session->set_flashdata('notify', 'Data  berhasil disimpan !');
            
            redirect('master/program');

        }
        
        $this->template->set('title', 'Master Data Program &raquo; Tambah Data');
        $this->template->load('template', 'master/program/tambah', $data);
    }
    
    // Ubah data
    function edit_program()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('m_prog','kd_program',$id)->num_rows() <= 0 ) ) :
        
            redirect('program');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_program',
                     'label'   => 'Kd program',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_program',
                     'label'   => 'Nm program',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Program &raquo; Ubah Data";
            $data['program'] = $this->master_model->get_by_id('m_prog','kd_program',$id)->row();
        }
        else
        {
                                
            $data = array(
                        'kd_program' => $this->input->post('kd_program'),
                        'nm_program' => $this->input->post('nm_program'),
                        );
                        
            $this->master_model->update('m_prog','kd_program',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data  berhasil diupdate !');
            
            redirect('master/program');

        }
        
        $this->template->set('title', 'Master Data Program &raquo; Ubah Data');
        $this->template->load('template', 'master/program/edit', $data);
    }
    
    // hapus data
    function hapus_program()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('m_prog','kd_program',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/program');
        
        else:
                        
            $this->master_model->delete('m_prog','kd_program',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/program');
            
        endif;
    }
    
    function preview_program(){
        $cRet='';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER PROGRAM</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE PROGRAM</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA PROGRAM</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td></tr>
                        ";
                 
                $query = $this->master_model->getAllc('m_prog','kd_program');

                foreach ($query->result() as $row)
                {
                    $coba1=$row->kd_program;
                    $coba2=$row->nm_program;
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba2</td></tr>";
                }
              
        $cRet    .= "</table>";
        $data['prev']= $cRet;    
        $this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        
                
    }
    
    function cari_kegiatan()
    {
        
        $lccr =  $this->input->post('pencarian');
        $this->index('0','m_giat','kd_kegiatan','nm_kegiatan','Kegiatan','kegiatan',$lccr);
    }
    
    // Tamba data
    function tambah_kegiatan()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_program',
                     'label'   => 'Kd Program',
                     'rules'   => 'trim|required'
                    ),
                
               array(
                     'field'   => 'kd_kegiatan',
                     'label'   => 'Kd Kegiatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_kegiatan',
                     'label'   => 'Nm Kegiatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'jns_kegiatan',
                     'label'   => 'Jns Kegiatan',
                     'rules'   => 'trim|required'
               )
                  
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Kegiatan &raquo; Tambah";
            $lc = "select kd_program,nm_program from m_prog order by kd_program";
            $query = $this->db->query($lc);
            $data["program"]=$query->result();
            //$data["jumrow"]=$this->db->get('m_prog')->num_rows();
        }
        else
        {
                                
            $data = array(
                        'kd_program' => $this->input->post('kd_program'),
                        'kd_kegiatan' => $this->input->post('kd_kegiatan'),
                        'nm_kegiatan' => $this->input->post('nm_kegiatan'),
                        'jns_kegiatan' => $this->input->post('jns_kegiatan'),
                        );
                        
            $this->master_model->save('m_giat',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/kegiatan');

        }
        
        $this->template->set('title', 'Master Data Kegiatan &raquo; Tambah Data');
        $this->template->load('template', 'master/kegiatan/tambah', $data);
    }
    
    // Ubah data
    function edit_kegiatan()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('m_giat','kd_kegiatan',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/kegiatan');
        
        endif;
        
        $config = array(
              array(
                     'field'   => 'kd_program',
                     'label'   => 'Kd Program',
                     'rules'   => 'trim|required'
                    ),
                
               array(
                     'field'   => 'kd_kegiatan',
                     'label'   => 'Kd Kegiatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_kegiatan',
                     'label'   => 'Nm Kegiatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'jns_kegiatan',
                     'label'   => 'Jns Kegiatan',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Kegiatan &raquo; Ubah Data";
            $data['kegiatan'] = $this->master_model->get_by_id('m_giat','kd_kegiatan',$id)->row();
            $lc = "select kd_program,nm_program from m_prog order by kd_program";
            $query = $this->db->query($lc);
            $data["program"]=$query->result();
            $data["jumrow"]=$this->db->get('m_prog')->num_rows();
  
            
            
        }
        else
        {
                                
            $data = array(
                        'kd_program' => $this->input->post('kd_program'),
                        'kd_kegiatan' => $this->input->post('kd_kegiatan'),
                        'nm_kegiatan' => $this->input->post('nm_kegiatan'),
                        'jns_kegiatan' => $this->input->post('jns_kegiatan'),
                        );
                        
            $this->master_model->update('m_giat','kd_kegiatan',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/kegiatan');

        }
        
        $this->template->set('title', 'Master Data Kegiatan &raquo; Ubah Data');
        $this->template->load('template', 'master/kegiatan/edit', $data);
    }
    
    // hapus data
    function hapus_kegiatan()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('m_giat','kd_kegiatan',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/kegiatan');
        
        else:
                        
            $this->master_model->delete('m_giat','kd_kegiatan',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/kegiatan');
            
        endif;
    }
    
    function preview_kegiatan(){
        $cRet='';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"4\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER KEGIATAN</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE KEGIATAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE PROGRAM</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA KEGIATAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>JENIS</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td></tr>
                        ";
                 
                 //$query = $this->db->query('SELECT kd_fungsi,nm_fungsi FROM ms_fungsi');
                 $query = $this->master_model->getAllc('m_giat','kd_kegiatan');

                foreach ($query->result() as $row)
                {
                    $coba1=$row->kd_kegiatan;
                    $coba2=$row->kd_program;
                    $coba3=$row->nm_kegiatan;
                    $coba4=$row->jns_kegiatan;
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba3</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba4</td></tr>";
                }
              
        $cRet    .= "</table>";
        $data['prev']= $cRet;    
        $this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        
                
    }
    
    function tambah_rek1()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_rek1',
                     'label'   => 'Kode Rekening 1',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_rek1',
                     'label'   => 'Nama Rekening 1',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening 1 &raquo; Tambah";
        }
        else
        {
                                
            $data = array(
                        'kd_rek1' => $this->input->post('kd_rek1'),
                        'nm_rek1' => $this->input->post('nm_rek1'),
                        );
                        
            $this->master_model->save('ms_rek1',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/rek1');

        }
        
        $this->template->set('title', 'Master Data rekening 1 &raquo; Tambah Data');
        $this->template->load('template', 'master/rek1/tambah', $data);
    }
    
    // Ubah data
    function edit_rek1()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek1','kd_rek1',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek1');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_rek1',
                     'label'   => 'Kode Rekening 1',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_rek1',
                     'label'   => 'Nama Rekening 1',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening akun &raquo; Ubah Data";
            $data['rek1'] = $this->master_model->get_by_id('ms_rek1','kd_rek1',$id)->row();
        }
        else
        {
                                
            $data = array(
                        'kd_rek1' => $this->input->post('kd_rek1'),
                        'nm_rek1' => $this->input->post('nm_rek1'),
                        );
                        
            $this->master_model->update('ms_rek1','kd_rek1',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/rek1');

        }
        
        $this->template->set('title', 'Master Data Rekening Akun  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek1/edit', $data);
    }
    
    // hapus data
    function hapus_rek1()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek1','kd_rek1',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek1');
        
        else:
                        
            $this->master_model->delete('ms_rek1','kd_rek1',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/rek1');
            
        endif;
    }
    
    function tambah_rek2()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_rek2',
                     'label'   => 'Kode Rekening 2',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek1',
                     'label'   => 'Kode Rekening 1',
                     'rules'   => 'trim|required'
                  ),

               array(
                     'field'   => 'nm_rek2',
                     'label'   => 'Nama Rekening 2',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Tambah";
            $lc = "select kd_rek1,nm_rek1 from ms_rek1 order by kd_rek1";
            $query = $this->db->query($lc);
            $data["kdrek"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek2' => $this->input->post('kd_rek2'),
                        'kd_rek1' => $this->input->post('kd_rek1'),
                        'nm_rek2' => $this->input->post('nm_rek2'),
                        );
                        
            $this->master_model->save('ms_rek2',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/rek2');

        }
        
        $this->template->set('title', 'Master Data Rekening Kelompok &raquo; Tambah Data');
        $this->template->load('template', 'master/rek2/tambah', $data);
    }
    
    // Ubah data
    function edit_rek2()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek2','kd_rek2',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek2');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_rek2',
                     'label'   => 'Kode Rekening kelompok',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek1',
                     'label'   => 'Kode Rekening Akun',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_rek2',
                     'label'   => 'Nama Rekening Kelompok',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Ubah Data";
            $data['rek2'] = $this->master_model->get_by_id('ms_rek2','kd_rek2',$id)->row();
            $lc = "select kd_rek1,nm_rek1 from ms_rek1 order by kd_rek1";
            $query = $this->db->query($lc);
            $data["kdrek"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek2' => $this->input->post('kd_rek2'),
                        'kd_rek1' => $this->input->post('kd_rek1'),
                        'nm_rek2' => $this->input->post('nm_rek2'),
                        );
                        
            $this->master_model->update('ms_rek2','kd_rek2',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/rek2');

        }
        
        $this->template->set('title', 'Master Data Rekening Kelompok  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek2/edit', $data);
    }
    
    // hapus data
    function hapus_rek2()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek2','kd_rek2',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek2');
        
        else:
                        
            $this->master_model->delete('ms_rek2','kd_rek2',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/rek2');
            
        endif;
    }
    
    function cari_rek3()
    {
    $lccr =  $this->input->post('pencarian');
    $this->index('0','ms_rek3','kd_rek3','nm_rek3','Rekening Jenis','rek3',$lccr);
    }
    
    function tambah_rek3()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_rek2',
                     'label'   => 'Kode Rekening kelompok',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek3',
                     'label'   => 'Kode Rekening Jenis',
                     'rules'   => 'trim|required'
                  ),
               
               array(
                     'field'   => 'nm_rek3',
                     'label'   => 'Nama Rekening Jenis',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Tambah";
            $lc = "select kd_rek2,nm_rek2 from ms_rek2 order by kd_rek2";
            $query = $this->db->query($lc);
            $data["kdrek2"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek2' => $this->input->post('kd_rek2'),
                        'kd_rek3' => $this->input->post('kd_rek3'),
                        'nm_rek3' => $this->input->post('nm_rek3'),
                        );
                        
            $this->master_model->save('ms_rek3',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/rek3');

        }
        
        $this->template->set('title', 'Master Data Rekening Jenis &raquo; Tambah Data');
        $this->template->load('template', 'master/rek3/tambah', $data);
    }
    
    // Ubah data
    function edit_rek3()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek3','kd_rek3',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek3');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_rek2',
                     'label'   => 'Kode Rekening kelompok',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek3',
                     'label'   => 'Kode Rekening jenis',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_rek3',
                     'label'   => 'Nama Rekening Jenis',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Jenis &raquo; Ubah Data";
            $data['rek3'] = $this->master_model->get_by_id('ms_rek3','kd_rek3',$id)->row();
            $lc = "select kd_rek2,nm_rek2 from ms_rek2 order by kd_rek2";
            $query = $this->db->query($lc);
            $data["kdrek2"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek2' => $this->input->post('kd_rek2'),
                        'kd_rek3' => $this->input->post('kd_rek3'),
                        'nm_rek3' => $this->input->post('nm_rek3'),
                        );
                        
            $this->master_model->update('ms_rek3','kd_rek3',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/rek3');

        }
        
        $this->template->set('title', 'Master Data Rekening Jenis  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek3/edit', $data);
    }
    
    // hapus data
    function hapus_rek3()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek3','kd_rek3',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek3');
        
        else:
                        
            $this->master_model->delete('ms_rek3','kd_rek3',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/rek3');
            
        endif;
    }
    
    function cari_rek4()
    {
    $lccr =  $this->input->post('pencarian');
    $this->index('0','ms_rek4','kd_rek4','nm_rek4','Rekening Objek','rek4',$lccr);
    }
    
    function tambah_rek4()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_rek3',
                     'label'   => 'Kode Rekening kelompok',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek4',
                     'label'   => 'Kode Rekening Objek',
                     'rules'   => 'trim|required'
                  ),
               
               array(
                     'field'   => 'nm_rek4',
                     'label'   => 'Nama Rekening objek',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Tambah";
            $lc = "select kd_rek3,nm_rek3 from ms_rek3 order by kd_rek3";
            $query = $this->db->query($lc);
            $data["kdrek"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek3' => $this->input->post('kd_rek3'),
                        'kd_rek4' => $this->input->post('kd_rek4'),
                        'nm_rek4' => $this->input->post('nm_rek4'),
                        );
                        
            $this->master_model->save('ms_rek4',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/rek4');

        }
        
        $this->template->set('title', 'Master Data Rekening Objek &raquo; Tambah Data');
        $this->template->load('template', 'master/rek4/tambah', $data);
    }
    
    // Ubah data
    function edit_rek4()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek4','kd_rek4',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek4');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_rek3',
                     'label'   => 'Kode Rekening kelompok',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek4',
                     'label'   => 'Kode Rekening Objek',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_rek4',
                     'label'   => 'Nama Rekening Objek',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Objek &raquo; Ubah Data";
            $data['rek4'] = $this->master_model->get_by_id('ms_rek4','kd_rek4',$id)->row();
            $lc = "select kd_rek3,nm_rek3 from ms_rek3 order by kd_rek3";
            $query = $this->db->query($lc);
            $data["kdrek"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek3' => $this->input->post('kd_rek3'),
                        'kd_rek4' => $this->input->post('kd_rek4'),
                        'nm_rek4' => $this->input->post('nm_rek4'),
                        );
                        
            $this->master_model->update('ms_rek4','kd_rek4',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/rek4');

        }
        
        $this->template->set('title', 'Master Data Rekening Objek  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek4/edit', $data);
    }
    
    // hapus data
    function hapus_rek4()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek4','kd_rek4',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek4');
        
        else:
                        
            $this->master_model->delete('ms_rek4','kd_rek4',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/rek4');
            
        endif;
    }
    
    function cari_rek5()
    {
    $lccr =  $this->input->post('pencarian');
    $this->index('0','ms_rek5','kd_rek5','nm_rek5','Rekening Rincian Objek','rek5',$lccr);
    }
    
    function tambah_rek5()
    {
        
        $config = array(
               array(
                     'field'   => 'kd_rek4',
                     'label'   => 'Kode Rekening Objek',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek5',
                     'label'   => 'Kode Rekening Rincian Objek',
                     'rules'   => 'trim|required'
                  ),
               
               array(
                     'field'   => 'nm_rek5',
                     'label'   => 'Nama Rekening Rincian Objek',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Rincian &raquo; Tambah";
            $lc = "select kd_rek4,nm_rek4 from ms_rek4 order by kd_rek4";
            $query = $this->db->query($lc);
            $data["kdrek"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek4' => $this->input->post('kd_rek4'),
                        'kd_rek5' => $this->input->post('kd_rek5'),
                        'nm_rek5' => $this->input->post('nm_rek5'),
                        );
                        
            $this->master_model->save('ms_rek5',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/rek5');

        }
        
        $this->template->set('title', 'Master Data Rekening Rincian Objek &raquo; Tambah Data');
        $this->template->load('template', 'master/rek5/tambah', $data);
    }
    
    // Ubah data
    function edit_rek5()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek5','kd_rek5',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek5');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kd_rek4',
                     'label'   => 'Kode Rekening Objek',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_rek5',
                     'label'   => 'Kode Rekening Rincian Objek',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nm_rek5',
                     'label'   => 'Nama Rekening Rincian Objek',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Rekening Rincian Objek &raquo; Ubah Data";
            $data['rek5'] = $this->master_model->get_by_id('ms_rek5','kd_rek5',$id)->row();
            $lc = "select kd_rek4,nm_rek4 from ms_rek4 order by kd_rek4";
            $query = $this->db->query($lc);
            $data["kdrek"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'kd_rek4' => $this->input->post('kd_rek4'),
                        'kd_rek5' => $this->input->post('kd_rek5'),
                        'nm_rek5' => $this->input->post('nm_rek5'),
                        );
                        
            $this->master_model->update('ms_rek5','kd_rek5',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/rek5');

        }
        
        $this->template->set('title', 'Master Data Rekening Rincian Objek  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek5/edit', $data);
    }
    
    // hapus data
    function hapus_rek5()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_rek5','kd_rek5',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/rek5');
        
        else:
                        
            $this->master_model->delete('ms_rek5','kd_rek5',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/rek5');
            
        endif;
    }
    
    function cari_ttd()
    {
    $lccr =  $this->input->post('pencarian');
    $this->index('0','ms_ttd','nip','nama','Penandatangan','ttd',$lccr);
    }
    
    function tambah_ttd()
    {
        
        $config = array(
               array(
                     'field'   => 'nip',
                     'label'   => 'NIP',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nama',
                     'label'   => 'Nama',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'jabatan',
                     'label'   => 'Jabatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'pangkat',
                     'label'   => 'pangkat',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_skpd',
                     'label'   => 'Kode Skpd',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kode',
                     'label'   => 'Kode',
                     'rules'   => 'trim|required'
                  )



            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Tanda tangan &raquo; Tambah";
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"]=$query->result();
        }
        else
        {
                                
            $data = array(
                        'nip' => $this->input->post('nip'),
                        'nama' => $this->input->post('nama'),
                        'jabatan'=>$this->input->post('jabatan'),
                        'pangkat'=>$this->input->post('pangkat'),
                        'kd_skpd'=>$this->input->post('kd_skpd'),
                        'kode'=>$this->input->post('kode')
                        
                        
                        );
                        
            $this->master_model->save('ms_ttd',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/ttd');

        }
        
        $this->template->set('title', 'Master Data Tanda Tangan &raquo; Tambah Data');
        $this->template->load('template', 'master/ttd/tambah', $data);
    }
    
    // Ubah data
    function edit_ttd()
    {
        $id = $this->uri->segment(3);
        $id=str_replace('%20',' ',$id);
        //echo($id);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_ttd','nip',$id)->num_rows() <= 0 ) ) :
             echo($id); 
            redirect('master/ttd');
        
        endif;
        
        $config = array(
                array(
                     'field'   => 'nip',
                     'label'   => 'NIP',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nama',
                     'label'   => 'Nama',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'jabatan',
                     'label'   => 'Jabatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'pangkat',
                     'label'   => 'pangkat',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_skpd',
                     'label'   => 'Kode Skpd',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kode',
                     'label'   => 'Kode',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Tanda Tangan &raquo; Ubah Data";
            $data['ttd'] = $this->master_model->get_by_id('ms_ttd','nip',$id)->row();
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"]=$query->result();
            
  
        }
        else
        {
                                
            $data = array(
                        'nip' => $this->input->post('nip'),
                        'nama' => $this->input->post('nama'),
                        'jabatan'=>$this->input->post('jabatan'),
                        'pangkat'=>$this->input->post('pangkat'),
                        'kd_skpd'=>$this->input->post('kd_skpd'),
                        'kode'=>$this->input->post('kode')
                        );
                        
            $this->master_model->update('ms_ttd','nip',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data berhasil diupdate !');
            
            redirect('master/ttd');

        }
        
        $this->template->set('title', 'Master Data Tanda tangan &raquo; Ubah Data');
        $this->template->load('template', 'master/ttd/edit', $data);
    }
    
    // hapus data
    function hapus_ttd()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_ttd','nip',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/ttd');
        
        else:
                        
            $this->master_model->delete('ms_ttd','nip',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/ttd');
            
        endif;
    }
    
    function cari_bank()
    {
    $lccr =  $this->input->post('pencarian');
    $this->index('0','ms_bank','kode','nama','Bank','bank',$lccr);
    }
    
    function tambah_bank()
    {
        
        $config = array(
               array(
                     'field'   => 'kode',
                     'label'   => 'Kode',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nama',
                     'label'   => 'Nama',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Bank &raquo; Tambah";
        }
        else
        {
                                
            $data = array(
                        'kode' => $this->input->post('kode'),
                        'nama' => $this->input->post('nama'),
                        );
                        
            $this->master_model->save('ms_bank',$data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
            
            redirect('master/bank');

        }
        
        $this->template->set('title', 'Master Data Bank &raquo; Tambah Data');
        $this->template->load('template', 'master/bank/tambah', $data);
    }
    
    // Ubah data
    function edit_bank()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_bank','kode',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/bank');
        
        endif;
        
        $config = array(
               array(
                     'field'   => 'kode',
                     'label'   => 'Kode',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nama',
                     'label'   => 'Nama',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data Bank &raquo; Ubah Data";
            $data['bank'] = $this->master_model->get_by_id('ms_bank','kode',$id)->row();
        }
        else
        {
                                
            $data = array(
                        'kode' => $this->input->post('kode'),
                        'nama' => $this->input->post('nama'),
                        );
                        
            $this->master_model->update('ms_bank','kode',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
            
            redirect('master/bank');

        }
        
        $this->template->set('title', 'Master Data Bank &raquo; Ubah Data');
        $this->template->load('template', 'master/bank/edit', $data);
    }
    
    // hapus data
    function hapus_bank()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('ms_bank','kode',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/bank');
        
        else:
                        
            $this->master_model->delete('ms_bank','kode',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/bank');
            
        endif;
    }
    
    
    ////my
    ////my
    function cari_user()
    {
    $lccr =  $this->input->post('pencarian');
    $this->index('0','[user]','user_name','nama','USER','user',$lccr);
    }

    function edit_user()
    {
        
        $id = $this->uri->segment(3);
        
        $data['list']       = $this->db->query("SELECT a.*,b.user_id FROM dyn_menu a LEFT JOIN (SELECT * FROM otori WHERE user_id = '$id') b ON a.id = b.menu_id order by a.id");
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('[user]','id_user',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/user');
        
        endif;
        
        //*
        $config = array(
               array(
                     'field'   => 'id_user',
                     'label'   => 'ID',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'user_name',
                     'label'   => 'User Uame',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'trim'
                  ),
               array(
                     'field'   => 'type',
                     'label'   => 'Type',
                     'rules'   => 'trim'
                  ),
               array(
                     'field'   => 'nama',
                     'label'   => 'Nama',
                     'rules'   => 'trim|required'
                  )
                  
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data User &raquo; Ubah Data";
            $data['user'] = $this->master_model->get_by_id('[user]','id_user',$id)->row();
        }
        else
        {
            if((md5($this->input->post('password')) == $this->input->post('password_before')) || ($this->input->post('password') == ""))
            {
            $data = array(
                        'user_name' => $this->input->post('user_name'),
                        'password' => $this->input->post('password_before'),
                        //'password' => md5($this->input->post('password')),
                        'kd_skpd' => $this->input->post('pcskpd'),
                        'nama' => $this->input->post('nama'),
                        'type' => $this->input->post('type')
                        );
            }
            else
            {
            $data = array(
                        'user_name' => $this->input->post('user_name'),
                        //'password' => $this->input->post('password'),
                        'kd_skpd' => $this->input->post('pcskpd'),
                        'password' => md5($this->input->post('password')),
                        'nama' => $this->input->post('nama'),
                        'type' => $this->input->post('type')
                        );
            }
            //id_user' => $this->input->post('id_user'),
            $this->master_model->delete("otori","user_id",$this->input->post('id_user'));
            //*
            $max=count($this->input->post('otori_id')) - 1;
            for ($i = 0; $i <= $max; $i++) 
            {
            $id_menu = $this->input->post('otori_id');
            
            $data_otori = array(
                        'user_id' => $this->input->post('id_user'),
                        'menu_id' => $id_menu[$i],
                        'akses' => "1"
                        );
            $this->master_model->save('otori',$data_otori);
            }
            //*/
                        
            $this->master_model->update('[user]','id_user',$id, $data);
                        
            $this->session->set_flashdata('notify', 'Data User berhasil diupdate !');
            
            redirect('master/user');

        }
        
        $this->template->set('title', 'Master Data User &raquo; Ubah Data');
        $this->template->load('template', 'master/user/edit', $data);
        //*/
    }
    function tambah_user()
    {
        $data['list']       = $this->db->query("SELECT * FROM dyn_menu ORDER BY page_id");
        
        $config = array(
               array(
                     'field'   => 'id_user',
                     'label'   => 'ID',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'user_name',
                     'label'   => 'User_name',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'type',
                     'label'   => 'Type',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'nama',
                     'label'   => 'Nama',
                     'rules'   => 'trim|required'
                  )
            );
            
        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE)
        {
            $data['page_title'] = "Master Data User &raquo; Tambah";
        }
        else
        {
                                
            $data = array(                      
                        'user_name' => $this->input->post('user_name'),
                        'password' => md5($this->input->post('password')),
                        'kd_skpd' => $this->input->post('pcskpd'),
                        'type' => $this->input->post('type'),
                        'nama' => $this->input->post('nama')
                        );
//                      'id_user' => $this->input->post('id_user'),
            $this->master_model->save('[user]',$data);
            
            //*
            $max=count($this->input->post('otori_id')) - 1;
            for ($i = 0; $i <= $max; $i++) 
            {
            $id_menu = $this->input->post('otori_id');
            
            $data_otori = array(
                        'user_id' => $this->input->post('id_user'),
                        'menu_id' => $id_menu[$i],
                        'akses' => "1"
                        );
            $this->master_model->save('otori',$data_otori);
            }
            //*/
            
            $this->session->set_flashdata('notify', 'Data User berhasil disimpan !');
            
            redirect('master/user');

        }
        
        $this->template->set('title', 'Master Data User &raquo; Tambah Data');
        $this->template->load('template', 'master/user/tambah', $data);
    }
    function hapus_user()
    {
        $id = $this->uri->segment(3);
        
        if ( ( $id == "" ) || ( $this->master_model->get_by_id('[user]','id_user',$id)->num_rows() <= 0 ) ) :
        
            redirect('master/user');
        
        else:
        
            $this->master_model->delete("otori","user_id",$id);
                
            $this->master_model->delete('[user]','id_user',$id);
                        
            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');
            
            redirect('master/user');
            
        endif;
    }
    
    function error()
    {
        $data['page_title']= 'Dalam Penyesuaian';
        $this->template->set('title', 'Dalam Penyesuaian');   
        $this->template->load('template','master/error',$data) ; 
    }
    
    function mfungsi()
    {
        $data['page_title']= 'Master FUNGSI';
        $this->template->set('title', 'Master Fungsi');   
        $this->template->load('template','master/fungsi/mfungsi',$data) ; 
    }
    
    function mhukum()
    {
        $data['page_title']= 'Master Dasar Hukum';
        $this->template->set('title', 'Master Dasar Hukum');   
        $this->template->load('template','master/fungsi/mhukum',$data) ; 
    }
    
    function murusan()
    {
        $data['page_title']= 'Master URUSAN';
        $this->template->set('title', 'Master Urusan');   
        $this->template->load('template','master/urusan/murusan',$data) ; 
    }
    function burusan()
    {
        $data['page_title']= 'Master URUSAN';
        $this->template->set('title', 'Master Urusan');   
        $this->template->load('template','master/bidang_urusan/burusan',$data) ; 
    }

    function skegiatan()
    {
        $data['page_title']= 'Master Sub Kegiatan';
        $this->template->set('title', 'Master Sub Urusan');   
        $this->template->load('template','master/sub_kegiatan/skegiatan',$data) ; 
    }
    
    function mskpd()
    {
        $data['page_title']= 'Master SKPD';
        $this->template->set('title', 'Master SKPD');   
        $this->template->load('template','master/skpd/mskpd',$data) ; 
    }
    
    function standar_harga()
    {
        $data['page_title']= 'Master Daftar Harga';
        $this->template->set('title', 'Master Daftar Harga');   
        $this->template->load('template','master/harga/standar_harga_ar_insert',$data) ; 
    }
    
    function munit()
    {
        $data['page_title']= 'Master UNIT';
        $this->template->set('title', 'Master UNIT');   
        $this->template->load('template','master/unit/munit',$data) ; 
    }
    
    function mprogram()
    {
        $data['page_title']= 'Master PROGRAM';
        $this->template->set('title', 'Master PROGRAM');   
        $this->template->load('template','master/program/mprogram',$data) ; 
    }
    
    function mkegiatan()
    {
        $data['page_title']= 'Master KEGIATAN';
        $this->template->set('title', 'Master KEGIATAN');   
        $this->template->load('template','master/kegiatan/mkegiatan',$data) ; 
    }
    function mrekening(){

        $data['page_title']= 'Master Rekening Bank';
        $this->template->set('title', 'Master Rekening Bank');   
        $this->template->load('template','master/rekening/mrekening',$data); 
    }
    function hapus_master_rek(){
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $skpd = $this->session->userdata('kdskpd');
        
        $csql = "delete from $ctabel where $cid = '$cnid' and kd_skpd='$skpd'";
                
        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }
function simpan_master_cek(){
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel WHERE $cid='$lcid' and kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                    exit();
        }else{
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if($asg){
                $msg = array('pesan'=>'2');
                    echo json_encode($msg);
                    exit();                
            }else{
               $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
            }
        }
    }

    function load_rek_bank() {
      $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="and (upper(a.nm_rekening) like upper('%$kriteria%') or a.rekening like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_rekening_bank a where a.kd_skpd = '$kd_skpd' $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT a.*,b.nama from ms_rekening_bank a left join ms_bank b on b.kode = a.bank where a.kd_skpd = '$kd_skpd' $where order by nm_rekening,rekening ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            if($resulte['jenis']=='1'){
                $jns_rek = "Rekening Pegawai";
            }else if($resulte['jenis']=='2'){
                $jns_rek = "Rekanan Pihak Ketiga";
            }else if($resulte['jenis']=='3'){
                $jns_rek = "Penampung Pajak";
            }
           
            $row[] = array(
                        'id' => $ii,
                        'rekening' => $resulte['rekening'],        
                        'nm_rekening' => $resulte['nm_rekening'],
                        'bank' => $resulte['bank'],
                        'nmbank' => $resulte['nama'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'jenis' => $resulte['jenis'],
                        'ket' => $resulte['keterangan'],
                        'nm_jenis' => $jns_rek                                                                                  
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }    
     function mrek1()
    {
        $data['page_title']= 'Master Rekening Akun';
        $this->template->set('title', 'Master Rekening Akun');   
        $this->template->load('template','master/rek1/mrek1',$data) ; 
    }
    
    function mrek2()
    {
        $data['page_title']= 'Master Rekening Kelompok';
        $this->template->set('title', 'Master Rekening Kelompok');   
        $this->template->load('template','master/rek2/mrek2',$data) ; 
    }
    
    function mrek3()
    {
        $data['page_title']= 'Master Rekening Jenis';
        $this->template->set('title', 'Master Rekening Jenis');   
        $this->template->load('template','master/rek3/mrek3',$data) ; 
    }
    
    function mrek4()
    {
        $data['page_title']= 'Master Rekening Objek';
        $this->template->set('title', 'Master Rekening Objek');   
        $this->template->load('template','master/rek4/mrek4',$data) ; 
    }
    
    function mrek5()
    {
        $data['page_title']= 'Master Rekening Rincian Objek';
        $this->template->set('title', 'Master Rekening Rincian Objek');   
        $this->template->load('template','master/rek5/mrek5',$data) ;
        //echo CI_VERSION; 
    }

    function mrek6()
    {
        $data['page_title']= 'Master Rekening Sub Rincian Objek';
        $this->template->set('title', 'Master Sub Rekening Rincian Objek');   
        $this->template->load('template','master/rek6/mrek6',$data) ;
        //echo CI_VERSION; 
    }
    
    function mbank()
    {
        $data['page_title']= 'Master BANK';
        $this->template->set('title', 'Master Bank');   
        $this->template->load('template','master/bank/mbank',$data) ;
        //echo CI_VERSION; 
    }
    
    function mttd()
    {
        $data['page_title']= 'Master Penandatangan';
        $this->template->set('title', 'Master Penandatangan');   
        $this->template->load('template','master/ttd/mttd',$data) ;
        //echo CI_VERSION; 
    }



    
    function rekening_objek()
    {
        $data['page_title']= 'Master Rekening Objek';
        $this->template->set('title', 'Master Rekening');   
        $this->template->load('template','master/rek5/rincian_objek',$data) ; 
    }
    
    function load_rek1() {
    
        $sql = " SELECT kd_rek2,nm_rek2 FROM ms_rek2 ORDER BY kd_rek2 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek2' => $resulte['kd_rek2'],  
                        'nm_rek2' => $resulte['nm_rek2']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
            $query1->free_result();
    } 
    
    function load_rek5($reke='') {
        $rek=$reke;
        $sql = " SELECT kd_rek4,kd_rek5,map_lra1,map_lo,nm_rek5 FROM ms_rek5  where substr(kd_rek5,1,2)='$rek' ORDER BY kd_rek5 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek4' => $resulte['kd_rek4'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'map_lra1' => $resulte['map_lra1'],
                        'map_lo' => $resulte['map_lo'],  
                        'nm_rek5' => $resulte['nm_rek5']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
            $query1->free_result();
    }
    
    function simpan_rek5() {
       
        $rek4 = $this->input->post('rek4');
        $rek5 = $this->input->post('rek5');
        $rek_lra = $this->input->post('rek_lra');
        $rek_lo = $this->input->post('rek_lo');
        $nama = $this->input->post('nama'); 
            
        $query = $this->db->query(" delete from ms_rek5 where kd_rek4='$rek4' and kd_rek5='$rek5'");
        $query = $this->db->query(" insert into ms_rek5(kd_rek4,kd_rek5,map_lra1,map_lo,nm_rek5) values('$rek4','$rek5','$rek_lra','$rek_lo','$nama') ");   
        

        $this->select_rka($kegiatan);
    } 
    
     function load_fungsi() {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_fungsi) like upper('%$kriteria%') or kd_fungsi like'%$kriteria%')";            
        }
        
        $sql = "SELECT * from ms_fungsi $where order by kd_fungsi";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_fungsi' => $resulte['kd_fungsi'],
                        'nm_fungsi' => $resulte['nm_fungsi']                                                                                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function ambil_fungsi() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_fungsi, nm_fungsi FROM ms_fungsi where upper(kd_fungsi) like upper('%$lccr%') or upper(nm_fungsi) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_fungsi' => $resulte['kd_fungsi'],  
                        'nm_fungsi' => $resulte['nm_fungsi']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_urusan() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_urusan) like upper('%$kriteria%') or kd_urusan like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_urusan $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT * from ms_urusan $where ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_urusan' => $resulte['kd_urusan'],        
                        'nm_urusan' => $resulte['nm_urusan']                                                                                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }


    function load_bidang_urusan() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="and (upper(nm_bidang_urusan) like upper('%$kriteria%') or kd_bidang_urusan like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_bidang_urusan $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT TOP $rows * from ms_bidang_urusan where  kd_bidang_urusan not in 
                    (SELECT TOP  $offset kd_bidang_urusan from ms_bidang_urusan) 
                    $where order by kd_bidang_urusan";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){           
            $row[] = array(
                        'id' => $ii,
                        'kd_bidang_urusan' => $resulte['kd_bidang_urusan'],
                        'kd_urusan' => $resulte['kd_urusan'],        
                        'nm_bidang_urusan' => $resulte['nm_bidang_urusan']                                                                                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function ambil_urusan() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_urusan, nm_urusan FROM ms_urusan where upper(kd_urusan) like upper('%$lccr%') or upper(nm_urusan) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_urusan' => $resulte['kd_urusan'],  
                        'nm_urusan' => $resulte['nm_urusan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }


    function ambil_burusan() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_bidang_urusan kd_bidang_urusan, nm_bidang_urusan FROM ms_bidang_urusan where upper(kd_bidang_urusan) like upper('%$lccr%') or upper(nm_bidang_urusan) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_urusan' => $resulte['kd_bidang_urusan'],  
                        'nm_urusan' => $resulte['nm_bidang_urusan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_skpd() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $tipe=$this->session->userdata('type');
        $sk=$this->session->userdata('kdskpd');
        $bidang=$this->session->userdata('bidang');
        if($tipe=='1'){
            $filter="";
        }else{
            if($bidang=='55'){
                $filter="and left(kd_skpd,22)=left('$sk',22)";
            }else{
                $filter="and left(kd_skpd,17)=left('$sk',17)";
            }
            
        }
        $where ='';
        if ($kriteria <> ''){                               
            $where="and (upper(nm_skpd) like upper('%$kriteria%') or kd_skpd like'%$kriteria%') ";            
        }
             
        $sql = "SELECT count(*) as tot from ms_skpd where kd_skpd not in ('sdsd') $where $filter" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT * from ms_skpd where kd_skpd not in ('sdsd') $where  $filter order by kd_skpd ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_skpd' => $resulte['kd_skpd'],        
                        'kd_urusan' => $resulte['kd_urusan'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'npwp' => $resulte['npwp'],
                        'rekening' => $resulte['rekening'],   
                        'obskpd'=>$resulte['obskpd'],
                        'alamat'=>$resulte['alamat'],
                        'kodepos'=>$resulte['kodepos'],
                        'rekening_pend'=>$resulte['rekening_pend'],
                        'bank'=>$resulte['bank'],                                                                              
                        'nilai_kua' => number_format($resulte['nilai_kua'],"2",".",",")                                                                                       
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function ambil_skpd() {
        $lccr = $this->input->post('q');
        $kd_skpd = $this->session->userdata('kdskpd');
        $type=$this->session->userdata('type');
        if($type==2){
            $where=" where kd_skpd='$kd_skpd'";
        }else{
            $where=" where upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')";
        }
        $sql = "SELECT kd_skpd, nm_skpd FROM ms_skpd $where";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_unit() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_unit) like upper('%$kriteria%') or kd_unit like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_unit $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT * from ms_unit $where order by kd_unit ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_unit' => $resulte['kd_unit'],        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_unit' => $resulte['nm_unit']                                                                                      
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function load_program() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        $where2 ='';
        if ($kriteria <> ''){                               
            $where=" where (upper(nm_program) like upper('%$kriteria%') or kd_program like'%$kriteria%') "; 
            $where2 =" where (upper(nm_program) like upper('%$kriteria%') or kd_program like'%$kriteria%') ";       
        }
             
        $sql = "SELECT count(*) as tot from ms_program $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT TOP $rows *, (select nm_bidang_urusan from ms_bidang_urusan WHERE kd_bidang_urusan=ms_program.kd_bidang_urusan) nmbid from ms_program  $where";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_program' => $resulte['kd_program'],        
                        'nm_program' => $resulte['nm_program'],
                        'kd_bidang_urusan' => $resulte['kd_bidang_urusan'],
                        'nm_bidang_urusan' => $resulte['nmbid'],                                                                                
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function loadm_program() {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        //$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
//      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
//      $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="where kd_skpd='$kd_skpd'";
        if ($kriteria <> ''){                               
            $where="where kd_skpd='$kd_skpd' and (upper(nm_program) like upper('%$kriteria%') or kd_program like'%$kriteria%')";            
        }
             
        //$sql = "SELECT count(*) as tot from m_prog $where" ;
//        $query1 = $this->db->query($sql);
//        $total = $query1->row();
//        
        
        
        //$sql = "SELECT * from m_prog $where order by kd_program limit $offset,$rows";
        $sql = "SELECT kd_program,nm_program from trskpd $where group by kd_program,nm_program order by kd_program ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_program' => $resulte['kd_program'],        
                        'nm_program' => $resulte['nm_program']                                                                                
                        );
                        $ii++;
        }
           
        //$result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function ambil_program() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_program, nm_program FROM ms_program where upper(kd_program) like upper('%$lccr%') or upper(nm_program) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }


    function ambil_kegiatan() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_kegiatan, nm_kegiatan FROM ms_kegiatan where upper(kd_kegiatan) like upper('%$lccr%') or nm_kegiatan like '%$lccr%'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_kegiatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_kegiatan() {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="where kd_skpd='$kd_skpd'";
        if ($kriteria <> ''){                               
            $where="where kd_skpd='$kd_skpd' and (upper(nm_kegiatan) like upper('%$kriteria%') or kd_kegiatan like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from trskpd $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
       // $sql = "SELECT * from trskpd $where order by kd_kegiatan ";
        $sql = " SELECT TOP $rows * from trskpd $where and kd_gabungan not in (SELECT TOP  $offset kd_gabungan from trskpd)  order by kd_kegiatan";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_kegiatan' => $resulte['kd_kegiatan'], 
                        'kd_program' => $resulte['kd_program'],       
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'jns_kegiatan' => $resulte['jns_kegiatan']                                                                                
                        );
                        $ii++;
        }
           
            $result["total"] = $total->tot;
            $result["rows"] = $row; 
            echo json_encode($result);
           
    }
    function load_kegiatan_all() {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="";
        $where2 ="";
        if ($kriteria <> ''){                               
            $where=" (upper(nm_kegiatan) like upper('%$kriteria%') or kd_kegiatan like'%$kriteria%') and ";            
            $where2=" where nm_kegiatan like '%$kriteria%' or kd_kegiatan like '%$kriteria%'";            
        }
             
        $sql = " SELECT TOP $rows *, (select nm_program from ms_program where kd_program=ms_kegiatan.kd_program) nm from ms_kegiatan $where2  order by kd_kegiatan";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_kegiatan' => $resulte['kd_kegiatan'], 
                        'kd_program' => $resulte['kd_program'],       
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'nm_program' => $resulte['nm']                                                                                 
                        );
                        $ii++;
        }
           
            $result["rows"] = $row; 
            echo json_encode($result);
           
    }


    function load_subkegiatan_all() {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="";
        $where2 ="";
        if ($kriteria <> ''){                               
            $where=" (upper(nm_sub_kegiatan) like upper('%$kriteria%') or kd_sub_kegiatan like'%$kriteria%') and ";            
            $where2=" where nm_sub_kegiatan like '%$kriteria%' or kd_sub_kegiatan like '%$kriteria%' ";            
        }
             
        $sql = "SELECT count(*) as tot from ms_sub_kegiatan $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = " SELECT TOP $rows *, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=ms_sub_kegiatan.kd_kegiatan) oke from ms_sub_kegiatan $where2 order by kd_sub_kegiatan";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'], 
                        'kd_kegiatan' => $resulte['kd_kegiatan'],       
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                        'jns_sub_kegiatan' => $resulte['jns_sub_kegiatan'],
                        'nm_kegiatan' => $resulte['oke']                                                                                
                        );
                        $ii++;
        }
           
            $result["total"] = $total->tot;
            $result["rows"] = $row; 
            echo json_encode($result);
           
    }
    
    function load_rekening1() {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_rek1) like upper('%$kriteria%') or kd_rek1 like'%$kriteria%')";            
        }
        
        $sql = "SELECT * from ms_rek1 $where order by kd_rek1";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek1' => $resulte['kd_rek1'],
                        'nm_rek1' => $resulte['nm_rek1']                                                                                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function ambil_rekening1() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek1, nm_rek1 FROM ms_rek1 where upper(kd_rek1) like upper('%$lccr%') or upper(nm_rek1) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek1' => $resulte['kd_rek1'],  
                        'nm_rek1' => $resulte['nm_rek1']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }


    function ambil_rekening_lo() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek6, nm_rek6 FROM ambil_lo where  upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }


    function ambil_rekening_piu() {
        $lccr = $this->input->post('q');
        $sql = " SELECT * FROM ambil_piutang WHERE  (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%') )";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_rekening2() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_rek2) like upper('%$kriteria%') or kd_rek2 like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_rek2 $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT  TOP $rows * FROM ms_rek2 where kd_rek2 not in 
                ( SELECT  TOP $offset kd_rek2 FROM ms_rek2  $where order by kd_rek2 ) $where order by kd_rek2 ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_rek2' => $resulte['kd_rek2'], 
                        'kd_rek1' => $resulte['kd_rek1'],       
                        'nm_rek2' => $resulte['nm_rek2']                                                                       
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function ambil_rekening2() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek2, nm_rek2 FROM ms_rek2 where upper(kd_rek2) like upper('%$lccr%') or upper(nm_rek2) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek2' => $resulte['kd_rek2'],  
                        'nm_rek2' => $resulte['nm_rek2']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_rekening3() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_rek3) like upper('%$kriteria%') or kd_rek3 like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_rek3 $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT  TOP $rows * FROM ms_rek3 where kd_rek3 not in 
                ( SELECT  TOP $offset kd_rek3 FROM ms_rek3  $where order by kd_rek3 ) $where order by kd_rek3 ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_rek3' => $resulte['kd_rek3'], 
                        'kd_rek2' => $resulte['kd_rek2'],       
                        'nm_rek3' => $resulte['nm_rek3']                                                                            
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function ambil_rekening3() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek3, nm_rek3 FROM ms_rek3 where upper(kd_rek3) like upper('%$lccr%') or upper(nm_rek3) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek3' => $resulte['kd_rek3'],  
                        'nm_rek3' => $resulte['nm_rek3']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function load_rekening4() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_rek4) like upper('%$kriteria%') or kd_rek4 like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_rek4 $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT  TOP $rows * FROM ms_rek4 where kd_rek4 not in 
                ( SELECT  TOP $offset kd_rek4 FROM ms_rek4  $where order by kd_rek4 ) $where order by kd_rek4";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_rek4' => $resulte['kd_rek4'], 
                        'kd_rek3' => $resulte['kd_rek3'],       
                        'nm_rek4' => $resulte['nm_rek4']                                                                              
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function ambil_rekening4() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek4, nm_rek4 FROM ms_rek4 where upper(kd_rek4) like upper('%$lccr%') or upper(nm_rek4) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek4' => $resulte['kd_rek4'],  
                        'nm_rek4' => $resulte['nm_rek4']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function ambil_rekening4_64() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek4_64, nm_rek4_64 FROM ms_rek4_64 where upper(kd_rek4_64) like upper('%$lccr%') or upper(nm_rek4_64) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek4' => $resulte['kd_rek4_64'],  
                        'nm_rek4' => $resulte['nm_rek4_64']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

     function load_rekening5() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_rek5) like upper('%$kriteria%') or kd_rek5 like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_rek5 $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT  TOP $rows * FROM ms_rek5 where kd_rek5 not in 
                ( SELECT  TOP $offset kd_rek5 FROM ms_rek5  $where order by kd_rek5 ) $where order by kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_rek5' => $resulte['kd_rek5'], 
                        'kd_rek4' => $resulte['kd_rek4'],       
                        'nm_rek5' => $resulte['nm_rek5']                                                                              
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }

    function ambil_rekening5() {
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_rek5, a.nm_rek5,b.kd_rek6,b.nm_rek6 FROM ms_rek5 a
inner join ms_rek6 b on a.kd_rek5=b.kd_rek5 where (upper(a.kd_rek5) like upper('%$lccr%') or upper(a.nm_rek5) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'nm_rek6' => $resulte['nm_rek6']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
        
            
        
        
    function load_daftar_harga() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(uraian) like upper('%$kriteria%') or kd_harga like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from ms_harga $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT * from ms_harga $where order by kd_rek5 ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_harga' => $resulte['kd_harga'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'uraian' => $resulte['uraian'],
                        'satuan' => $resulte['satuan'],  
                        'harga' => $resulte['harga'],
                        'harga1' => number_format($resulte['harga'])                                                                                  
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    
    function load_bank() {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nama) like upper('%$kriteria%') or kode like'%$kriteria%')";            
        }
        
        $sql = "SELECT * from ms_bank $where order by kode";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kode'],
                        'nama' => $resulte['nama']                                                                                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
     function load_ttd() {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="";
        $kd_skpd=$this->session->userdata('kdskpd');
        if($this->session->userdata('type')==2){
            $where1="kd_skpd='$kd_skpd' and";
            $where2="where kd_skpd='$kd_skpd'";
        }else{
            $where1="";
            $where2="";
        }

        if ($kriteria <> ''){                               
            $where="$where1 (upper(nama) like upper('%$kriteria%') or nip like'%$kriteria%' or kd_skpd like'%$kriteria%')  and ";
            $where3="where $where1 (upper(nama) like upper('%$kriteria%') or nip like'%$kriteria%' or kd_skpd like'%$kriteria%')   ";            
        }else{
             $where="$where1";
             $where3="";
        }
             
        $sql = "SELECT count(*) as tot from ms_ttd $where2 " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT top $rows * from ms_ttd where $where  nip not in (SELECT TOP $offset  nip from  
                ms_ttd $where3 order by id_ttd,nama ) order by id_ttd,nama  ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan'],
                        'pangkat' => $resulte['pangkat'],  
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kode' => $resulte['kode']                                                                                                    
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
 
    function cek_simpan_ttd(){
        $nomor    = $this->input->post('no');
        $jabat    = $this->input->post('jabat');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $kd_skpd  = $this->session->userdata('kdskpd');        
        $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor'");
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
    
     function hapus_master_ttd(){
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $kode = $this->input->post('kode');
        $kd_skpd  = $this->session->userdata('kdskpd'); 
        $cbidang1 = '2';       
        $csql = "delete from $ctabel where $cid = '$cnid' AND kode='$kode' AND kd_skpd='$kd_skpd' and bidang='$cbidang1' ";
                
        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }

    
    function load_tapd() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nama) like upper('%$kriteria%') or nip like'%$kriteria%')";            
        }
             
        $sql = "SELECT count(*) as tot from tapd $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT * from tapd $where order by nip ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan']                                                                               
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    } 

    function simpan_skpd(){
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');

        $res = $this->db->query("insert into ms_skpd $lckolom values $lcnilai");        
    }


    
       function simpan_master(){
                    
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nip=$this->input->post('nip');
        $nama=$this->input->post('nama');
        $jab=$this->input->post('jab');
        $pang=$this->input->post('pang');
        $dinas=$this->input->post('dinas');
        $kode=$this->input->post('kode');
        $bid=$this->input->post('bid');


            $sql="select max(id_ttd)+1 oke from ms_ttd";
            $asg = $this->db->query($sql)->row();
            $id_ttd=$asg->oke;

            $sql = "insert into ms_ttd (nip,nama,jabatan,pangkat,kd_skpd,kode,bidang) 
                values ('$nip','$nama','$jab','$pang','$dinas','$kode','$bid')";
            $asg = $this->db->query($sql);
            if($asg){
                echo '2';
            }else{
                echo '0';
            }
        
    }
 

    function nourut_kegiatan(){
        
        $kdprog  = $this->input->post('kdprog');
        $sql = "SELECT isnull(max(right(kd_sub_kegiatan,2)),0)+1 oke from ms_sub_kegiatan where kd_kegiatan='$kdprog'";
        $res = $this->db->query($sql)->row();
        echo json_encode($res->oke);
    }
    function nourut_giat(){
        
        $kdprog  = $this->input->post('kdprog');
        $sql = "SELECT isnull(max(right(kd_kegiatan,2)),0)+1 oke from ms_kegiatan where kd_program='$kdprog'";
        $res = $this->db->query($sql)->row();
        echo json_encode($res->oke);
    }   
    function simpan_tapd(){
        
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cnip = $this->input->post('lcid');
        
        
        $sql = "delete from $tabel where nip='$cnip'";
        $asg = $this->db->query($sql);
        if($asg){
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
        }
       
    }
    
   // function update_master(){
//        $query = $this->input->post('st_query');
//        $asg = $this->db->query($query);
//
//    }
    function update_master(){
        echo $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        if($asg){
            echo '1';
        }else{
            echo '0';
        }
        //$asg1 = $this->db->query($query1);
        //$asg2 = $this->db->query($query2);

    }
     function update_master2(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        if($asg){
            echo '1';
        }else{
            echo '0';
        }
        
  

    }

    function hapus_master2(){
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $cid2 = $this->input->post('cid2');
        $cnid2 = $this->input->post('cnid2');
        
        $cbidang1 = '1';  
        $csql = "delete from $ctabel where $cid = '$cnid' ";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }
    
    function hapus_master(){
        $cnid = $this->input->post('cnid');
        $csql = "delete from ms_skpd where kd_skpd ='$cnid'";
        if ($this->db->query($csql)){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    } 

    function hapus_rek6(){
        $rek6 = $this->input->post('rek6');
        $csql = "delete from ms_rek6 where kd_rek6 ='$rek6'";
        if ($this->db->query($csql)){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    } 
    function hapus_prog(){
        $rek6 = $this->input->post('prog');
        $csql = "delete from ms_program where kd_program ='$rek6'";
        if ($this->db->query($csql)){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    function hapus_giat(){
        $rek6 = $this->input->post('prog');
        $csql = "delete from ms_kegiatan where kd_kegiatan ='$rek6'";
        if ($this->db->query($csql)){
            echo '1'; 
        } else{
            echo '0';
        }
    }
    function hapus_subgiat(){
        $rek6 = $this->input->post('prog');
        $csql = "delete from ms_sub_kegiatan where kd_sub_kegiatan ='$rek6'";
        if ($this->db->query($csql)){
            echo '1'; 
        } else{
            echo '0';
        }
    }
     function hapus_tapd(){
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        
        
        $csql = "delete from $ctabel where nip = '$cid'";
                
        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }
    
    function neraca_awal()
    {
        $data['page_title'] = 'NERACA AWAL';
        $this->template->set('title', 'NERACA AWAL');
        $this->template->load('template', 'akuntansi/neraca_awal', $data);
    }
    
    function load_neraca_awal() {
        
        
        $sql = "SELECT * from rg_neraca  order by seq";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kode'],
                        'seq' => $resulte['seq'],
                        'aset' => $resulte['aset'],  
                        'nilai_lalu' => number_format($resulte['nilai_lalu'])                                                                                         
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function lak_awal()
    {
        $data['page_title'] = 'LAK AWAL';
        $this->template->set('title', 'LAK AWAL');
        $this->template->load('template', 'akuntansi/lak_awal', $data);
    }
    
    function load_lak_awal() {
        
        
        $sql = "SELECT * from rg_lak  order by seq";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['nor'],
                        'seq' => $resulte['seq'],
                        'aset' => $resulte['uraian'],  
                        'nilai_lalu' => number_format($resulte['nilai_lalu'])                                                                                         
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function lpe_awal()
    {
        $data['page_title'] = 'LPE AWAL';
        $this->template->set('title', 'LPE AWAL');
        $this->template->load('template', 'akuntansi/lpe_awal', $data);
    }
    
    function load_lpe_awal() {
        
        
        $sql = "SELECT * from map_lpe  order by seq";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['nor'],
                        'seq' => $resulte['seq'],
                        'uraian' => $resulte['uraian'],  
                        'nilai_lalu' => number_format($resulte['thn_m1'])                                                                                         
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function lpsal_awal()
    {
        $data['page_title'] = 'LPSAL AWAL';
        $this->template->set('title', 'LPSAL AWAL');
        $this->template->load('template', 'akuntansi/lpsal_awal', $data);
    }
    
    function load_lpsal_awal() {
        
        
        $sql = "SELECT * from map_lpsal  order by seq";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['nor'],
                        'seq' => $resulte['seq'],
                        'uraian' => $resulte['uraian'],  
                        'nilai_lalu' => number_format($resulte['thn_m1'])                                                                                         
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
    
    function _mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font=12,$orientasi='') {
        
        ini_set("memory_limit","512M");
        $this->load->library('mpdf');
        
        /*
        $this->mpdf->progbar_altHTML = '<html><body>
                                        <div style="margin-top: 5em; text-align: center; font-family: Verdana; font-size: 12px;"><img style="vertical-align: middle" src="'.base_url().'images/loading.gif" /> Creating PDF file. Please wait...</div>';        
        $this->mpdf->StartProgressBarOutput();
        */
        
        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6; /* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        
        //$this->mpdf->SetHeader('SIMAKDA||');
        $jam = date("H:i:s");
        //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Simakda| Page {PAGENO} of {nb}');
        $this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');
        
        $this->mpdf->AddPage($orientasi);
        
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
         
        $this->mpdf->Output();
    }
    
     function ambil_rekening5_ar()
    {
         if ($this->input->post('q')==''){
            $lccr = 'xxxxxx';
        }else{
            $lccr = $this->input->post('q');    
        }

        $sql = "SELECT kd_rek6, nm_rek6 FROM ms_rek6 where left(kd_rek6,1)='5' and
                (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) 
                order by kd_rek6";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6']
                 );
            $ii++;
        }

        echo json_encode($result);
    }
    
      function load_daftar_harga_ar() {
       
        $result   = array();
        $row      = array();
        $page     = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows     = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset   = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';
        $where2    = '';
        
        if ($kriteria <> ''){                               
            $where="AND (upper(uraian) like upper('%$kriteria%') or kd_rek6 like'%$kriteria%')"; 
            $where2="where (upper(uraian) like upper('%$kriteria%') or kd_rek6 like'%$kriteria%')";            
        }
             
        $sql    = "SELECT count(*) as tot from ms_standar_harga " ;
        $query1 = $this->db->query($sql);
        $total  = $query1->row();
        
        $sql    = "SELECT TOP $rows *,(select top 1 nm_rek6 from ms_rek6 where kd_rek6=a.kd_rek6) nm_rek6 from ms_standar_harga a where a.id not in (SELECT TOP  $offset id from ms_standar_harga $where2 order by kd_rek6 asc) $where  order by a.kd_rek6 asc";//limit $offset,$rows";

        //SELECT TOP $rows * from sumber_dana where $where kd_sumber_dana1 not in (SELECT TOP  $offset kd_sumber_dana1 from sumber_dana $where2)  order by kd_sumber_dana1
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_rek6'    => $resulte['kd_rek6'],
                        'nm_rek6'    => $resulte['nm_rek6'],
                        'kd_barang'    => $resulte['kd_barang'],
                        'uraian'     => $resulte['uraian'],
                        'merk'       => $resulte['merk'],
                        'satuan'     => $resulte['satuan'],
                        //'harga'      => number_format($resulte['harga'],"2",".",","),
                        'harga'      => $resulte['harga'],
                        'keterangan' => $resulte['keterangan']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"]  = $row; 
        echo json_encode($result);
    }



     function load_daftar_harga_detail_ar($norek='') {
       
        $norek  = $this->input->post('rekening') ;

        $sql    = "SELECT * from trdharga where kd_rek5 = '$norek' order by no_urut ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'         => $ii,        
                        'no_urut'    => $resulte['no_urut'],
                        'kd_rek5'    => $resulte['kd_rek5'],
                        'uraian'     => $resulte['uraian'],
                        'merk'       => $resulte['merk'],
                        'satuan'     => $resulte['satuan'],
                        //'harga'      => number_format($resulte['harga'],"2",".",","),
                        'harga'      => $resulte['harga'],
                        'keterangan' => $resulte['keterangan']
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
    
    
    function update_master_ar(){
        
        $tabel   = $this->input->post('tabel');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');
        
        if (  $lcid <> $lcid_h ) {
           $sql     = "select $cid from $tabel where $cid='$lcid'";
           $res     = $this->db->query($sql);
           if ( $res->num_rows()>0 ) {
                echo '1';
                exit();
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
    
    function hapus_detail_all(){

        $ctabel = $this->input->post('tabel');
        $cid    = $this->input->post('cid');
        $cnid   = $this->input->post('cnid');
        
        $csql   = "delete from $ctabel where $cid = '$cnid'";
                
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else {
            echo '0';
        }
                       
    }
    
    function hapus_detail(){

        $urut  =  $this->input->post('curut') ;
        $rek   =  $this->input->post('ckdrek') ;
        
        $csql  = "delete from trdharga where no_urut='$urut' and kd_rek5='$rek' ";
        $asg   = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else {
            echo '0';
        }
                       
    }
    
    function simpan_detail_standar_harga() {
        
        $proses = $this->input->post('proses');
        if ( $proses == 'detail' ) {

                $tabel_detail = $this->input->post('tabel_detail');
                $sql_detail   = $this->input->post('sql_detail');
                $nomor        = $this->input->post('nomor');
                
                $sql          = " delete from trdharga where kd_rek5='$nomor' ";
                $asg          = $this->db->query($sql) ;
    
                $sql          = " insert into trdharga (no_urut,kd_rek5,uraian,merk,satuan,harga,keterangan)  "; 
                $asg_detail   = $this->db->query($sql.$sql_detail);
                
                if ( $asg_getail > 0 ){
                   echo '1';     
                } else {
                   echo '0';
                }
        }            
    }
    
    function load_daftar_harga_detail($norek='') {
       
        $norek  = $this->input->post('rekening') ;

        $sql    = "SELECT * from trdharga where kd_rek5 = '$norek' order by no_urut ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'         => $ii,        
                        'no_urut'    => $resulte['no_urut'],
                        'kd_rek5'    => $resulte['kd_rek5'],
                        'uraian'     => $resulte['uraian'],
                        'merk'       => $resulte['merk'],
                        'satuan'     => $resulte['satuan'],
                        //'harga'      => number_format($resulte['harga'],"2",".",","),
                        'harga'      => $resulte['harga'],
                        'keterangan' => $resulte['keterangan']
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
    
    
    function load_daftar_harga_detail_ck($norek='') {
       
        $norek  = $this->input->post('rekening') ;

        $sql    = "SELECT *, kd_rek5 as ck from trdharga where kd_rek5 = '$norek' order by no_urut ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'         => $ii,        
                        'no_urut'    => $resulte['no_urut'],
                        'kd_rek5'    => $resulte['kd_rek5'],
                        'uraian'     => $resulte['uraian'],
                        'merk'       => $resulte['merk'],
                        'satuan'     => $resulte['satuan'],
                        //'harga'      => number_format($resulte['harga'],"2",".",","),
                        'harga'      => $resulte['harga'],
                        'keterangan' => $resulte['keterangan'],
                        'ck'         => $resulte['ck']

                        );
                        $ii++;
        }
        echo json_encode($result);
    }

    
    
    
    
 //    function ambil_rekening5_all_ar_lama() {
        
 //        $lccr    = $this->input->post('q');
 //        $notin   = $this->input->post('reknotin');
 //        $jnskegi = $this->input->post('jns_kegi');
        
 //        if ( $notin <> ''){
 //            $where = " and kd_rek5 not in ($notin) ";
 //        } else {
 //            $where = " ";
 //        }
        
 //        if ( $jnskegi =='4' ) {
 //            $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,1)='4' )
 //                    and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";
 //        } elseif ( $jnskegi=='51' or $jnskegi=='52' ){
 //                if($jnskegi=='51'){
    //                  $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,2)='51')
 //                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";                 
    //          }else{
    //                  $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,2)='52')
 //                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";                                     
    //          }
 //            } else {
 //                $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,1)='6' or left(kd_rek5,1)='7' )
 //                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";
 //            }
        
 //        $query1 = $this->db->query($sql); 
 //        $result = array();
 //        $ii = 0;
 //        foreach($query1->result_array() as $resulte)
 //        { 
 //            $result[] = array(
 //                        'id' => $ii,        
 //                        'kd_rek5' => $resulte['kd_rek5'],  
 //                        'nm_rek5' => $resulte['nm_rek5']
 //                        );
 //                        $ii++;
 //        }
 //        echo json_encode($result);
    // }
    
    
    function load_dhukum(){
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_hukum) like upper('%$kriteria%') or kd_hukum like'%$kriteria%')";            
        }
        
        $sql    = "SELECT * from m_hukum $where order by kd_hukum";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_hukum' => $resulte['kd_hukum'],
                        'nm_hukum' => $resulte['nm_hukum']                                                                                        
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

     function sumberaktif()
    {
        $data['page_title']= 'Master Sumberdana Aktif';
        $this->template->set('title', 'Master Sumberdana Aktif');   
        $this->template->load('template','master/fungsi/mdanaaktif',$data) ; 
    }

    function load_dana() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        $where2 ='';
        if ($kriteria <> ''){                               
            $where=" where (upper(nm_sumber_dana1) like upper('%$kriteria%') or kd_sumber_dana1 like'%$kriteria%') "; 
            $where2 =" where (upper(nm_sumber_dana1) like upper('%$kriteria%') or kd_sumber_dana1 like'%$kriteria%') ";     
        }
             
        $sql = "SELECT count(*) as tot from sumber_dana $where2 " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT TOP $rows * from sumber_dana where $where kd_sumber_dana1 not in (SELECT TOP  $offset kd_sumber_dana1 from sumber_dana $where2)  order by kd_sumber_dana1";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_sdana' => $resulte['kd_sumber_dana1'],
                        'nm_sdana' => $resulte['nm_sumber_dana1']                                                                                
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }

//=================================================================================
    function load_danaaktif() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        $where2 ='';
        if ($kriteria <> ''){                               
            $where=" where (upper(nm_sumberdana) like upper('%$kriteria%') or kd_sumberdana like'%$kriteria%') "; 
            $where2 =" where (upper(nm_sumberdana) like upper('%$kriteria%') or kd_sumberdana like'%$kriteria%') ";     
        }
             
        $sql = "SELECT count(*) as tot from sumber_dana $where2" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        
        
        $sql = "SELECT TOP $rows * from  hsumber_dana a where $where a.kd_sumberdana not in (SELECT TOP  $offset a.kd_sumberdana from hsumber_dana  $where2)  order by a.kd_sumberdana ";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id'       => $ii,
                        'kd_sdana' => $resulte['kd_sumberdana'],
                        'nm_sdana' => $resulte['nm_sumberdana'],
                        'nilai'    => $resulte['nilai'],
                        'nilaisil' => $resulte['nilaisilpa'],
                        'nilait'   => number_format($resulte['nilai'],"2",".",","),
                        'nilaisilt'=> number_format($resulte['nilaisilpa'],"2",".",",")                                                                                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }

    function ambil_sdana() {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        // $lccr = '';
        // $kriteria = $this->input->post('cari');
        $lccr = $this->input->post('q');
        $where ='';
        $where2 ='';
        if ($lccr <> ''){                               
            $where=" where (upper(nm_sumber_dana1) like upper('%$lccr%') or kd_sumber_dana1 like'%$lccr%') "; 
            $where2 =" where (upper(nm_sumber_dana1) like upper('%$lccr%') or kd_sumber_dana1 like'%$lccr%') ";     
        }
             
        // $sql = "SELECT * from sumber_dana $where2 order by kd2" ;
        // $query1 = $this->db->query($sql);
        // $total = $query1->row();
        
        
        
        $sql = "SELECT * from sumber_dana $where2 order by CAST(kd_sumber_dana1 as VARCHAR)";//limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_sdana' => $resulte['kd_sumber_dana1'],
                        'nm_sdana' => $resulte['nm_sumber_dana1']                                                                                
                        );
                        $ii++;
        }
           
        // $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }


    function hapus_detail_sumber(){

        $sskpd  =  $this->input->post('skpdd') ;
        $rek   =  $this->input->post('ckdrek') ;
        
        $csql  = "delete from dsumber_dana where kd_sumberdana='$rek' and kd_skpd='$sskpd' ";
        $asg   = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else {
            echo '0';
        }
                       
    }


    function simpan_detail_sdana() {
        
        $proses = $this->input->post('proses');
        if ( $proses == 'detail' ) {

                $tabel_detail = $this->input->post('tabel_detail');
                $sql_detail   = $this->input->post('sql_detail');
                $nomor        = $this->input->post('nomor');
                
                $sql          = " delete from dsumber_dana where kd_sumberdana='$nomor' ";
                $asg          = $this->db->query($sql) ;
    
                $sql          = " insert into dsumber_dana (kd_sumberdana,kd_skpd)  "; 
                $asg_detail   = $this->db->query($sql.$sql_detail);
                
                if ( $asg_detail > 0 ){
                   echo '1';     
                } else {
                   echo '0';
                }
        }            
    }


    function load_danadetail() {
       
        $norek  = $this->input->post('rekening') ;

        $sql    = "SELECT a.kd_sumberdana,a.nm_sumberdana,b.kd_skpd,c.nm_skpd 
        from  hsumber_dana a join dsumber_dana b on a.kd_sumberdana=b.kd_sumberdana 
        left join ms_skpd c on b.kd_skpd=c.kd_skpd where a.kd_sumberdana='$norek'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'            => $ii,        
                        'kd_skpd'       => $resulte['kd_skpd'],
                        'nm_skpd'       => $resulte['nm_skpd'],
                        'kd_sumberdana' => $resulte['kd_sumberdana'],
                        'nm_sumberdana' => $resulte['nm_sumberdana']
                        );
                        $ii++;
        }
        echo json_encode($result);
    }




//=================================================================================

    function sumberdana()
    {
        $data['page_title']= 'Master FUNGSI';
        $this->template->set('title', 'Master Fungsi');   
        $this->template->load('template','master/fungsi/mdana',$data) ; 
    }


 //    function load_dana() {
 //        $result = array();
 //        $row = array();
 //         $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    //     $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    //     $offset = ($page-1)*$rows;
 //        $kriteria = '';
 //        $kriteria = $this->input->post('cari');
 //        $where ='';
    //  $where2 ='';
 //        if ($kriteria <> ''){                               
 //            $where=" where (upper(nm_sumber_dana1) like upper('%$kriteria%') or kd_sumber_dana1 like'%$kriteria%') "; 
    //      $where2 =" where (upper(nm_sumber_dana1) like upper('%$kriteria%') or kd_sumber_dana1 like'%$kriteria%') ";     
 //        }
             
 //        $sql = "SELECT count(*) as tot from sumber_dana $where2" ;
 //        $query1 = $this->db->query($sql);
 //        $total = $query1->row();
        
        
        
 //        $sql = "SELECT TOP $rows * from sumber_dana where $where kd_sumber_dana1 not in (SELECT TOP  $offset kd_sumber_dana1 from sumber_dana $where2)  order by kd_sumber_dana1";//limit $offset,$rows";
 //        $query1 = $this->db->query($sql);  
 //        $result = array();
 //        $ii = 0;
 //        foreach($query1->result_array() as $resulte)
 //        { 
           
 //            $row[] = array(
 //                        'id' => $ii,
 //                        'kd_sdana' => $resulte['kd_sumber_dana1'],
 //                        'nm_sdana' => $resulte['nm_sumber_dana1']                                                                                
 //                        );
 //                        $ii++;
 //        }
           
 //        $result["total"] = $total->tot;
 //        $result["rows"] = $row; 
 //        echo json_encode($result);
           
    // }
    
    function load_dana1() {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';
        if ($kriteria <> ''){                               
            $where="where (upper(nm_sdana) like upper('%$kriteria%') or kd_sdana like'%$kriteria%')";            
        }
        
        $sql    = "SELECT TOP 1 * from sumber_dana where (upper(nm_sumber_dana1) like upper('%$kriteria%') 
or kd_sumber_dana1 like'%$kriteria%') and kd_sumber_dana1 not in 
(SELECT TOP  10 kd_sumber_dana1 from sumber_dana where (upper(nm_sumber_dana1) like 
upper('%$kriteria%') or kd_sumber_dana1 like'%$kriteria%'))  order by kd_sumber_dana1";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sdana' => $resulte['kd_sumber_dana1'],
                        'nm_sdana' => $resulte['nm_sumber_dana1']                                                                                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

    function cetak_kegiatan() {
        
         $cRet ="<table style=\"border-collapse:collapse;font-family: arial; font-size:10px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td width=\"10%\" align=\"center\"><b>KODE PROGRAM</b></td>                            
                            <td width=\"10%\" align=\"center\"><b>KODE KEGIATAN</b></td>
                            <td width=\"80%\" align=\"center\"><b>NAMA KEGIATAN</b></td>
                        </tr>
                     </thead>
                                         
                   
                  "; 
          
            
        $sql    = "SELECT kd_program,kd_kegiatan,nm_kegiatan from m_giat order by kd_kegiatan";
   
         
         $query1 = $this->db->query($sql);
                 foreach ($query1->result() as $row)
        { 
        
                    $coba1=$row->kd_program;
                    $coba2=$row->kd_kegiatan;
                    $coba3=$row->nm_kegiatan;
                    
           $cRet    .= " <tr><td align=\"left\">$coba1</td>                                     
                                     <td align=\"left\">$coba2</td>
                                     <td  align=\"left\">$coba3</td>";
           
        }
           $cRet .="</table>";
           
        echo $cRet;
           
    }




    //////////////////////////////////////////////////////TAMBAHAN RKA
    function ambil_rekening5_all_ar() {
        
        $lccr    = $this->input->post('q');
        $notin   = $this->input->post('reknotin');
        $jnskegi = $this->input->post('jns_kegi');
        
        if ( $notin <> ''){
            $where = " and kd_rek5 not in ($notin) ";
        } else {
            $where = " ";
        }
        
        if ( $jnskegi =='4' ) {
            $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,1)='4' )
                    and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";
        } elseif ( $jnskegi=='51' or $jnskegi=='52' ){
                if($jnskegi=='51'){
                        $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,2)='51')
                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";                    
                }else{
                        $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,2)='52')
                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";                                        
                }
            } else {
                // $sql = "SELECT kd_rek, nm_rek FROM ambil_rekening where ( left(kd_rek,2)='52' ) and len(kd_rek)='11'
                //         and (upper(kd_rek) like upper('%$lccr%') or upper(nm_rek) like upper('%$lccr%')) $where order by kd_rek";

                $sql = "SELECT b.kd_rek5,b.nm_rek5,kd_rek, nm_rek FROM ambil_rekening a left join ms_rek5 b on a.rek=b.kd_rek5
where  left(kd_rek,1) in ('5','4')  and /*len(kd_rek)='11'  and*/ (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where
group by kd_rek, nm_rek,b.kd_rek5,b.nm_rek5
order by b.kd_rek5,b.nm_rek5,kd_rek, nm_rek";
            }
        
        $query1 = $this->db->query($sql); 
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'kd_rek6' => $resulte['kd_rek'],  
                        'nm_rek6' => $resulte['nm_rek']
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
    
    function ambilnomor(){
        $rek = $this->input->post('rek5');
        $exe =$this->db->query("SELECT isnull(max(right(kd_rek6,3)),0)+1 oke from ms_rek6 where kd_rek5='$rek'")->row();
        echo json_encode($exe->oke);
    }
    function ambilnomor_prog(){
        $rek = $this->input->post('prog');
        $exe =$this->db->query("SELECT isnull(max(right(kd_program,2)),0)+1 oke from ms_program where kd_bidang_urusan='$rek'")->row();
        echo json_encode($exe->oke);
    }
//=================================================================================

function ambil_rekening13() {
        $lccr = $this->input->post('q');
        $sql = "SELECT top 10 * from ms_rek5 where (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
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
           
    }


function ambil_rekening64() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek64, nm_rek64 FROM ms_rek5_13 where (upper(kd_rek64) like upper('%$lccr%') or upper(nm_rek64) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek64' => $resulte['kd_rek64'],  
                        'nm_rek64' => $resulte['nm_rek64']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }


function load_rekening6() {
        
        $result = array();
        $row    = array();
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        $where2 ='';
        if ($kriteria <> ''){                                    
            $where = " where ((upper(kd_rek6) like upper('%$kriteria%'))  or (upper(nm_rek6) like upper('%$kriteria%'))) ";            
        }
             

        

        $sql = "SELECT TOP $rows *, (select nm_rek5 from ms_rek5 where kd_rek5=ms_rek6.kd_rek5) nm_rek5 from ms_rek6 $where ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_rek13'      => $resulte['kd_rek13'],
                        'nm_rek13'      => $resulte['nm_rek13'],
                        'kd_rek64'      => $resulte['kd_rek64'],
                        'nm_rek64'      => $resulte['nm_rek64'],
                        'map_lo'        => $resulte['map_lo'],
                        'piutang_utang' => $resulte['piutang_utang'],
                        'kd_rek6'       => $resulte['kd_rek6'],
                        'nm_rek6'       => $resulte['nm_rek6'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nm_lo'         => $resulte['nm_rek5'],
                        'nm_piu'        => $resulte['nm_rek5']

                        );
                        $ii++;
        }
           
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
//=================================================================================

    function simpan_rek6(){
       $rek5 = $this->input->post('rek5');
       $rek6 = $this->input->post('rek6');
       $nm5 = $this->input->post('nm5');
       $nm6 = $this->input->post('nm6');
       $edit = $this->input->post('edit');

       if($edit=='edit'){
            echo $this->db->query("UPDATE ms_rek6 SET nm_rek6='$nm6' where kd_rek6='$rek6'");            
       }else{
            echo $this->db->query("INSERT INTO ms_rek6 (kd_rek6,nm_rek6,kd_rek5) values ('$rek6','$nm6','$rek5')");        
       }
    }

    function simpan_prog(){
       $nm_prog = $this->input->post('nm_prog');
       $kd_prog = $this->input->post('kd_prog');
       $urusan = $this->input->post('urusan');
       $edit = $this->input->post('edit');

       if($edit=='edit'){
            echo $this->db->query("UPDATE ms_program SET nm_program='$nm_prog' where kd_program='$kd_prog'");            
       }else{
            echo $this->db->query("INSERT INTO ms_program (kd_program,nm_program,kd_bidang_urusan) values ('$kd_prog','$nm_prog','$urusan')");        
       }
    }
    function simpan_giat(){
       $nm_giat = $this->input->post('nm_giat');
       $kd_giat = $this->input->post('kd_giat');
       $kd_prog = $this->input->post('kd_prog');
       $edit = $this->input->post('edit');

       if($edit=='edit'){
            echo $this->db->query("UPDATE ms_kegiatan SET nm_kegiatan='$nm_giat' where kd_kegiatan='$kd_giat'");            
       }else{
            echo $this->db->query("INSERT INTO ms_kegiatan (kd_kegiatan,nm_kegiatan,kd_program) values ('$kd_giat','$nm_giat','$kd_prog')");        
       }
    }
    function simpan_subgiat(){
       $nm_subgiat = $this->input->post('nm_subgiat');
       $kd_subgiat = $this->input->post('kd_subgiat');
       $kd_giat = $this->input->post('kd_giat');
       $jns = $this->input->post('jns');
       $edit = $this->input->post('edit');

       if($edit=='edit'){
            echo $this->db->query("UPDATE ms_sub_kegiatan SET nm_sub_kegiatan='$nm_subgiat', jns_sub_kegiatan='$jns' where kd_sub_kegiatan='$kd_subgiat'");            
       }else{
            echo $this->db->query("INSERT INTO ms_sub_kegiatan (kd_sub_kegiatan,nm_sub_kegiatan,kd_kegiatan,jns_sub_kegiatan) values ('$kd_subgiat','$nm_subgiat','$kd_giat','$jns')");        
       }
    }

    function ambil_skpd_dana()
    {
       $lccr = $this->input->post('q');
        $sql = "SELECT kd_skpd, nm_skpd FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }
//=================================================================================



function ambil_rekening6() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek6, nm_rek6 FROM ms_rek6 where (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) order by kd_rek6 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

     function ambil_standar_harga()
    {
         if ($this->input->post('q')==''){
            $lccr = 'xxxxxx';
        }else{
            $lccr = $this->input->post('q');    
        }

        $sql = "SELECT kd_barang,uraian,merk,satuan,harga,keterangan FROM ms_standar_harga
                where  (upper(kd_rek6) like upper('%$lccr%') or upper(uraian) like upper('%$lccr%')) 
                order by kd_rek6";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                        'id' => $ii,        
                        'kd_barang' => $resulte['kd_barang'],
                        'uraian' => $resulte['uraian'],
                        'merk' => $resulte['merk'],
                        'satuan' => $resulte['satuan'],
                        'harga' => $resulte['harga'],
                        'keterangan' => $resulte['keterangan']
                 );
            $ii++;
        }

        echo json_encode($result);
    }
//=================================================================================
//=================================================================================
    function ambil_data_user(){
        $list="select * from [user] WHERE (id_user BETWEEN 101 and 132) and bidang=4";
        $exe=$this->db->query($list);
        foreach($exe->result() as $a){
            $id_user=$a->id_user;
            $hapus=$this->db->query("delete from otori where user_id='$id_user'");
            $in="

                insert otori
                select $id_user id_user, menu_id, akses from otori where user_id='6'
                ";
            $out=$this->db->query($in);
        }
    }



    function cek_password(){
        $passlama = md5($this->input->post('passlama'));
        $user     = $this->input->post('user');
        $cek=$this->db->query("SELECT count(*) oke from [user] where user_name='$user' and password='$passlama'")->row();
        echo $cek->oke;

    }

    function cek_user(){
        $userbaru = $this->input->post('userbaru');
        $cek=$this->db->query("SELECT count(*) oke from [user] where user_name='$userbaru'")->row();
        echo $cek->oke;

    }

    function update_password(){

        $passbaru = md5($this->input->post('passbaru'));
        $user     = $this->input->post('user');
        $jenis     = $this->input->post('jenis');
        $userbaru     = $this->input->post('userbaru');
        if($jenis=='username'){
            echo $simpan=$this->db->query("UPDATE [user] set user_name='$userbaru' where user_name='$user'");
        }else{
            echo $simpan=$this->db->query("UPDATE [user] set password='$passbaru' where user_name='$user'");
        }
        
    }

   function nomor_lampiran_laporan(){
        $thn=$this->session->userdata('pcThang');
        $no_config=$this->input->post('no_config');
        $jns_ang=$this->input->post('jns_ang');
        $judul  =$this->input->post('judul');
        $no_lamp=$this->input->post('no_lamp');
        $tgl=$this->input->post('tgl');
        $jns_lamp=$this->input->post('jns_lamp');
        $isi=$this->input->post('isi');
        $daerah=$this->input->post('daerah');

        $in="INSERT INTO trkonfig_anggaran (jenis_anggaran,judul,nomor,tanggal,lampiran,isi,daerah,kd_pemda,thn_ang) 
        values ('$jns_ang','$judul','$no_lamp','$tgl','$jns_lamp','$isi','$daerah','0000','$thn')";
        
        echo $put=$this->db->query($in);    
    }

    function editnomor_lampiran_laporan(){
        $no_config=$this->input->post('no_config');
        $jns_ang=$this->input->post('jns_ang');
        $judul  =$this->input->post('judul');
        $no_lamp=$this->input->post('no_lamp');
        $tgl=$this->input->post('tgl');
        $jns_lamp=$this->input->post('jns_lamp');
        $isi=$this->input->post('isi');
        $daerah=$this->input->post('daerah');

        $in="UPDATE trkonfig_anggaran SET
        jenis_anggaran='$jns_ang',
        judul='$judul',
        nomor='$no_lamp',
        tanggal='$tgl',
        lampiran='$jns_lamp',
        isi='$isi',
        daerah='$daerah'
        where no_konfig='$no_config'";
        
        echo $put=$this->db->query($in);  

    }

   function hapus_lampiran(){
        $no_config=$this->input->post('nomer');
        echo $put=$this->db->query("DELETE trkonfig_anggaran where no_konfig='$no_config'"); 

   } 
    
}
