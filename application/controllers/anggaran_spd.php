<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class anggaran_spd extends CI_Controller {

public $ppkd = "4.02.01";
public $ppkd1 = "4.02.01.00";
 
    function __contruct()
    {     
        parent::__construct();
    }      
 
    function spd_belanja(){
        $data['jenis']="belanja";
        $data['page_title']= 'INPUT SPD BELANJA';
        $this->template->set('title', 'INPUT SPD BELANJA');   
        $this->template->load('template','anggaran/spd/spd_belanja',$data) ; 
    }

    function spd_pembiayaan(){
        $data['jenis']="pembiayaan";
        $data['page_title']= 'INPUT SPD PEMBIAYAAN';
        $this->template->set('title', 'INPUT SPD PEMBIAYAAN');   
        $this->template->load('template','anggaran/spd/spd_belanja',$data) ; 
    } 

    function spd_belanja_revisi(){
        $data['page_title']= 'INPUT SPD BELANJA REVISI';
        $this->template->set('title', 'INPUT SPD BELANJA REVISI');   
        $this->template->load('template','anggaran/spd/spd_belanja_revisi',$data) ; 
    }    

    function spd_pembiayaan_revisi(){
        $data['page_title']= 'INPUT SPD PEMBIAYAAN REVISI';
        $this->template->set('title', 'INPUT SPD PEMBIAYAAN REVISI');   
        $this->template->load('template','anggaran/spd/spd_pembiayaan_revisi',$data) ; 
    } 

    function skpduser_bp() {
        $lccr = $this->input->post('q');
        $result=$this->master_model->skpduser($lccr);
           
        echo json_encode($result);
    }

    function bln_spdakhir(){
        $kdskpd = $this->input->post('skpd');
        $jns = $this->input->post('jenis');
        $result=$this->anggaran_spd_model->bln_spdakhir($kdskpd,$jns);
        echo ($result);
    }
   
    function load_spd_bl($kriteria='') {
        $id      = $this->session->userdata('pcUser');
        $kd_skpd = $this->session->userdata('kdskpd');
        $beban = $this->input->post('jenis'); 
        $result  =$this->anggaran_spd_model->load_spd_bl($kriteria,$kd_skpd,$id,$beban);                  
        echo ($result);    
    }

    function load_ttd_bud(){
        echo $this->master_ttd->load_ttd_bud();                
    }
    
    function load_skpd_bp(){          
        echo $this->master_ttd->load_skpd_bp();

    }

    function jumlah_detail_angkas_spd_baru(){ /*cek selisih angkas*/
        $skp      = $this->input->post('skp');
        $jn       = $this->input->post('jn');
        echo $this->anggaran_spd_model->jumlah_detail_angkas_spd_baru($skp,$jn);
    }  

    function config_spd_nomor(){
        echo $this->anggaran_spd_model->config_spd_nomor();
    }

    function load_tot_dspd_bl($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$tgl1=''){
        echo $this->anggaran_spd_model->load_tot_dspd_bl($jenis,$skpd,$awal,$ahir,$nospd,$tgl1);
    }

    function load_bendahara_p(){
        $kdskpd = $this->input->post('kode');
        echo $this->master_ttd->load_bendahara_p($kdskpd);
    }
    
    function load_dspd_bl($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$cbln1=''){
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = $this->input->post('cari');
        echo $this->anggaran_spd_model->load_dspd_bl($jenis,$skpd,$awal,$ahir,$nospd,$cbln1,$tgl,$page,$rows,$offset,$kriteria); 
    }

    function cek_simpan_spd(){
        $nomor   = $this->input->post('no');
        $skpd    = $this->input->post('skpd');
        $awal    = $this->input->post('awal');   
        $akhir    = $this->input->post('akhir');
        echo $this->anggaran_spd_model->cek_simpan_spd($nomor,$skpd,$awal,$akhir);
    } 

       function simpan_spd(){
        $tabel  = $this->input->post('tabel');
        $idx    = $this->input->post('cidx');
        $nomor  = $this->input->post('no');
        $cno_u  = $this->input->post('cno_u');
        $nomor2  = $this->input->post('no2');
        $mode_tox= $this->input->post('mode_tox');
        $tgl    = $this->input->post('tgl');
        $skpd   = $this->input->post('skpd');
        $nmskpd = $this->input->post('nmskpd');
        $bend   = $this->input->post('bend');
        $bln1   = $this->input->post('bln1');
        $bln2   = $this->input->post('bln2');
        $ketentuan  = $this->input->post('ketentuan');
        $pengajuan  = $this->input->post('pengajuan');
        $jenis      = $this->input->post('jenis');
        $jenis_spp  = $this->input->post('jns_spp');
        $total      = $this->input->post('total');
        $csql       = $this->input->post('sql');        
        $usernm     = $this->session->userdata('pcNama');    
        $update     = date('Y-m-d H:i:s');    
        $msg = array();                
        // Simpan Header //
        if ($tabel == 'trhspd') {
            if ($mode_tox=='tambah'){

                $sql = "INSERT into  $tabel (no_spd,tgl_spd,kd_skpd,nm_skpd,jns_beban,bulan_awal,bulan_akhir,total,klain,kd_bkeluar,username,tglupdate,jns_spp,total_hasil,urut) 
                        values('$nomor','$tgl','$skpd', rtrim('$nmskpd'),'$jenis','$bln1','$bln2','$total', rtrim('$ketentuan'),'$bend','$usernm','$update','$jenis_spp','$total','$cno_u')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                } else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                }          

            } else if($mode_tox=='edit'){
                $sql = "UPDATE $tabel set 
                    no_spd='$nomor',tgl_spd='$tgl',kd_skpd='$skpd',nm_skpd=rtrim('$nmskpd'),
                    jns_beban='$jenis',bulan_awal='$bln1',bulan_akhir='$bln2',total='$total',total_hasil='$total',klain=rtrim('$ketentuan'),kd_bkeluar='$bend',username='$usernm',tglupdate='$update',jns_spp='$jenis_spp'
                    where no_spd='$nomor2' ";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                } else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                }          
                
            }
            
        } else if ($tabel == 'trdspd') {
            
            // Simpan Detail //                       
                $sql = "delete from  $tabel where no_spd='$nomor2'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg= array('pesan'=>'0');
                   echo json_encode($msg);
                   exit();
                } else {
                    $sql = "INSERT into  $tabel(no_spd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_program,nm_program,nilai,nilai_final,kd_subkegiatan,nm_subkegiatan)";                        
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }  else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }                                                             
        }        
    }

    function hapus_spd(){
        $nomor = $this->input->post('no');
        echo $this->anggaran_spd_model->hapus_spd($nomor);           
    } 

    function load_dspd_ag_bl() {            
        $no = $this->input->post('no');
        $jenis = $this->input->post('jenis');
        $skpd = $this->input->post('skpd');
        $dskpd = substr($skpd,0,22);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $rows='';
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = $this->input->post('cari');
        echo $this->anggaran_spd_model->load_dspd_ag_bl($no,$jenis,$skpd,$dskpd,$tgl,$cbln1,$page,$rows,$offset,$kriteria);
    }

    function load_spd_bl_angkas() {
        $kd_skpd = $this->session->userdata('kdskpd'); 
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $beban = $this->input->post('beban');  
        $id  = $this->session->userdata('pcUser');  
        echo $this->anggaran_spd_model->load_spd_bl_angkas($kd_skpd,$page, $rows,$offset,$kriteria,$id,$beban);    
    }

    function update_sts_spd(){
        $no_spd      = $this->input->post('no');
        $ckd_skpd     = $this->input->post('kd_skpd');
        $csts        = $this->input->post('status_spd');
        echo $this->anggaran_spd_model->update_sts_spd($no_spd, $ckd_skpd,$csts);            
        
    }

    function cek_simpan(){ /*untuk cek appakah ada spd di tabel trhspp*/
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field'); /*trhspp*/
        echo $this->anggaran_spd_model->cek_simpan($nomor,$tabel,$field);       
    }

  

     function load_spd_bl_skpd($skpd='') { 
        $kd_skpd = $skpd;//$this->session->userdata('kdskpd'); 
        //$kd_skpd = '1.20.08.10'; 
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $id  = $this->session->userdata('pcUser');  

        $where ="WHERE a.kd_skpd ='$kd_skpd' ";
        if ($kriteria <> ''){                               
            $where="where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) 
                    ) ";            
        }
        
        $sql = "SELECT count(*) as total from trhspd a $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
    
        
        $sql = "SELECT DISTINCT TOP $rows a.*,nama, 'BELANJA' AS nm_beban from trhspd a left join ms_ttd b 
        on a.kd_bkeluar=b.id_ttd  $where  AND no_spd not in (SELECT TOP $offset  no_spd from trhspd a left join ms_ttd b 
        on a.kd_bkeluar=b.id_ttd where a.kd_skpd ='$kd_skpd' order by a.no_spd,a.tgl_Spd,a.kd_skpd) order by no_spd,tgl_Spd,kd_skpd ";
        $query1 = $this->db->query($sql);       
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                        'tgl_spd' => $resulte['tgl_spd'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'ketentuan' => $resulte['klain'],
                        'nama_bend' => $resulte['nama'],
                        'nip' => $resulte['kd_bkeluar'],                        
                        'jns_beban' => $resulte['jns_beban'],
                        'nm_beban' => $resulte['nm_beban'],
                        'bulan_awal' => $resulte['bulan_awal'],
                        'bulan_akhir' => $resulte['bulan_akhir'],
                        'total' => $resulte['total'],                                                                      
                        'status' => $resulte['status']                                                                      
                        );
                        $ii++;
        }
        $result["rows"] = $row;           
        echo json_encode($result);
        $query1->free_result();        
    }

      function load_dspd() {            
        $no = $this->input->post('no');
        $sql = "SELECT a.*,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_kegiatan=a.kd_kegiatan  AND m.no_spd <> '$no') AS lalu,
                (SELECT SUM(total) FROM trskpd WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17) ) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                        'kd_subkegiatan' => $resulte['kd_subkegiatan'],
                        'nm_subkegiatan' => $resulte['kd_subkegiatan'],
                        'kd_kegiatan' => $resulte['kd_subkegiatan'],
                        'nm_kegiatan' => $resulte['kd_subkegiatan'],
                        'kd_rekening' => $resulte['kd_rek6'],
                        'nm_rekening' => $resulte['nm_rek6'],
                        'kd_program' => $resulte['kd_program'],
                        'nm_program' => $resulte['nm_program'],
                        'nilai' => $resulte['nilai'],
                        'lalu' => $resulte['lalu'],
                        'anggaran' => $resulte['anggaran']                                        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }

    function load_dspd_ag_blx() {            
        $no = $this->input->post('no');
        $jenis = $this->input->post('jenis');
        $skpd = $this->input->post('skpd');
        $dskpd = substr($skpd,0,17);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        //$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from trdspd WHERE no_spd='$no'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $n_status = $this->anggaran_spd_model->get_status($tgl,$skpd);
       
        $field=$n_status;
        $sql = "SELECT  a.*,kd_rek5,nm_rek5 ,(SELECT SUM(nilai_final) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_subkegiatan=a.kd_kegiatan  AND m.no_spd <> '$no' and month(m.tgl_spd)<'$cbln1') AS lalu,
                (select sum($field) from trdrka where kd_sub_kegiatan = a.kd_subkegiatan) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' AND left(b.kd_skpd,17)=left('$dskpd',17) 
                order by b.no_spd,a.kd_subkegiatan,a.kd_rek5 ";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                       'kd_subkegiatan' => $resulte['kd_subkegiatan'],
                        'nm_subkegiatan' => $resulte['nm_subkegiatan'],
                        //'kd_kegiatan' => $resulte['kd_kegiatan'],
                        //'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program'  => $resulte['kd_program'],
                        'nm_program'  => $resulte['nm_program'],
                        'kd_rekening' => $resulte['kd_rek5'],
                        'nm_rekening' => $resulte['nm_rek5'],
                        'nilai'       => number_format($resulte['nilai_final'],"2",".",","),
                        'lalu'        => number_format($resulte['lalu'],"2",".",","),
                        'anggaran'    => number_format($resulte['anggaran'],"2",".",",")                               
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }
    
}
