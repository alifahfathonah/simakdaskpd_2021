<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 select_pot_taspen() rekening gaji manual. harap cek selalu
 harap cek simpan_cair() 2110801 dan batal_cair() --AND a.kd_rek5 NOT IN ('2110801','4140612')
 */

class sp2dc extends CI_Controller {
 
 
    function __construct(){   
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }
    }    
 
    function terima(){
        $data['page_title']= 'PENERIMAAN S P 2 D';
        $this->template->set('title', 'PENERIMAAN S P 2 D');   
        $this->template->load('template','tukd/sp2d/sp2d_terima_skpd',$data) ; 
    }

    function pencairan(){
        $data['page_title']= 'PENCAIRAN S P 2 D';
        $this->template->set('title', 'PENCAIRAN S P 2 D');   
        $this->template->load('template','tukd/sp2d/sp2d_cair_skpd',$data) ; 
    }

    function load_terima_sp2d() {
        
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND ( upper(urut) like upper('%$kriteria%') or upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from trhsp2d WHERE status_bud='1' and kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows * from trhsp2d WHERE status_bud='1' and kd_skpd = '$kd_skpd' $where  and no_sp2d not in (
                SELECT TOP $offset no_sp2d from trhsp2d WHERE status_bud='1' and kd_skpd = '$kd_skpd' $where  order by urut) order by urut,no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            if ($resulte['status_terima']=='1'){
                $s="<button class='button-biru' style='font-size:10px'>Sudah diterima</button>";
            }else{
                $s="<button class='button-kuning' style='font-size:10px'>Belum diterima</button>";           
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
                        'nokas' => $resulte['no_kas'],
                        'no_kas_bud' => $resulte['no_kas_bud'],
                        'no_terima' => $resulte['no_terima'],
                        'dterima' => $resulte['tgl_terima'],
                        'dkas' => $resulte['tgl_kas'],
                        'dbud' => $resulte['tgl_kas_bud'],
                        'nocek' => $resulte['nocek'],
                        'nilai' => $resulte['nilai'],
                        'status' => $s,
                        'status_terima'=>$resulte['status_terima'],
                        'kd_sub_skpd'=>$resulte['kd_sub_skpd'],
                        'status_cair' => $resulte['status']
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
           
    }

     function simpan_terima_sp2d(){

        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $cskpd = $this->input->post('skpd');
        $cket = $this->input->post('ket');
        $beban = $this->input->post('beban');
         $kd_sub_skpd = $this->input->post('kd_sub_skpd');
        $usernm= $this->session->userdata('pcNama');

        $sql = " UPDATE trhsp2d set status_terima='1',no_terima='$nokas',tgl_terima='$tglkas' where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);
        
        $buktitrm=$nokas+1; 
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
                INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
                WHERE b.no_sp2d = '$no_sp2d'";
        $jumlah = $this->db->query($sql7)->row()->jumlah;
        if ($jumlah>0){
                
                    $sql9 = "SELECT a.*,b.jns_spp FROM trspmpot a
                            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd
                            WHERE b.no_sp2d = '$no_sp2d' AND b.kd_skpd='$cskpd'";
                    $query9 = $this->db->query($sql9); 
                        foreach($query9->result_array() as $resulte9){
                                $kdrekening=$resulte9['kd_rek6'];
                                $nmrekening=$resulte9['nm_rek6'];
                                $nilai=$resulte9['nilai'];
                                $jenis_spp=$resulte9['jns_spp'];
                                $kd_trans=$resulte9['kd_trans'];
                                $kd_sub_skpd=$resulte9['kd_sub_skpd'];

                                $this->db->query("INSERT into trdtrmpot(no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans, kd_sub_skpd) 
                                                      values('$buktitrm','$kdrekening','$nmrekening','$nilai','$cskpd','$kd_trans','$kd_sub_skpd')");
                            }

                    $sql8 = "SELECT SUM(a.nilai) as nilai_pot,b.keperluan, b.kd_sub_skpd, b.npwp,b.jns_spp, b.nm_skpd, c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat 
                            FROM trspmpot a INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd 
                            inner join trhspp c on b.no_spp = c.no_spp AND a.kd_skpd=b.kd_skpd 
                            WHERE b.no_sp2d = '$no_sp2d' AND b.kd_skpd='$cskpd'
                            GROUP BY no_sp2d,b.keperluan, b.npwp,b.jns_spp,b.nm_skpd,c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat,b.kd_sub_skpd";
                    $query8 = $this->db->query($sql8); 
                    
                    foreach($query8->result_array() as $resulte8){
                            $keperluan=$resulte8['keperluan'];
                            $nilai=$resulte8['nilai_pot'];
                            $npwp=$resulte8['npwp'];
                            $kd_sub_skpd=$resulte8['kd_sub_skpd'];
                            $jenis=$resulte8['jns_spp'];
                            $nmskpd=$resulte8['nm_skpd'];
                            $kd_kegiatan=$resulte8['kd_sub_kegiatan'];
                            $nm_kegiatan=$resulte8['nm_sub_kegiatan'];
                            $nmrekan=$resulte8['nmrekan'];
                            $pimpinan=$resulte8['pimpinan'];
                            $alamat=$resulte8['alamat'];
                            
                            $this->db->query("INSERT into trhtrmpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,no_sp2d,nilai,npwp,jns_spp,status,kd_sub_kegiatan,nm_sub_kegiatan,nmrekan,pimpinan,alamat,kd_subkegiatan,nm_subkegiatan, kd_sub_skpd) 
                                                  values('$buktitrm','$tglkas','Terima pajak nomor SP2D $no_sp2d','$usernm','','$cskpd','$nmskpd','$no_sp2d','$nilai','$npwp','$jenis','1','$kd_kegiatan','$nm_kegiatan','$nmrekan','$pimpinan','$alamat','','','$kd_sub_skpd')");
                        }
            echo '1'; 
        }else{
            echo '0'; 
        }        
              
    }

    function batal_terima(){
        $skpd  = $this->session->userdata('kdskpd');
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $buktitrm = $nokas+1;
        $sql = " UPDATE trhsp2d set status_terima='0',no_terima='',tgl_terima='' where no_sp2d='$no_sp2d'  AND kd_skpd='$skpd'";
        $asg = $this->db->query($sql);
        $sql = " DELETE from trhtrmpot where no_bukti='$buktitrm' and kd_skpd='$skpd' ";
        $asg = $this->db->query($sql);
        $sql = " DELETE from trdtrmpot where no_bukti='$buktitrm' and kd_skpd='$skpd' ";
        $asg = $this->db->query($sql);
       
        if ($asg>0){ 
            echo '1';
        }
    }

    function load_sp2d_cair() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from trhsp2d WHERE status_terima = '1' and status_bud = '1' AND kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows * from trhsp2d WHERE status_terima = '1' AND status_bud = '1' and kd_skpd = '$kd_skpd' $where and no_sp2d not in (
                SELECT TOP $offset no_sp2d from trhsp2d WHERE status_terima = '1' AND status_bud = '1' and kd_skpd = '$kd_skpd' $where order by urut) order by urut,no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            if ($resulte['status']=='1'){
                $s='Sudah Cair';
            }else{
                $s='Belum Cair';            
            }
            
            $nmbid= "";
            $bidd="";
            
            $ckkontrak2="";            
            /*$sppp = $resulte['no_spp'];
            $ck = $this->db->query("SELECT count(kontrak) as ck FROM trhspp WHERE no_spp = '$sppp' and kd_skpd = '$kd_skpd'")->row();
            $ckkontrak = $ck->ck;
            
            if($ckkontrak>0){
                $ck = $this->db->query("SELECT kontrak as ck FROM trhspp WHERE no_spp = '$sppp' and kd_skpd = '$kd_skpd'")->row();
                $ckkontrak2 = $ck->ck;
            }else{
                $ckkontrak2 = "";
            }*/                     

            $row[] = array(
                        'id' => $ii,
                        'no_sp2d' => $resulte['no_sp2d'],
                        'tgl_sp2d' => $resulte['tgl_sp2d'],
                        'no_spm' => $resulte['no_spm'],
                        'tgl_spm' => $resulte['tgl_spm'],        
                        'no_spp' => $resulte['no_spp'],
                        'tgl_spp' => $resulte['tgl_spp'],
                        'tgl_terima' => $resulte['tgl_terima'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_sub_skpd' => $resulte['kd_sub_skpd'],
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
                        'nokas_bud' => $resulte['no_kas_bud'],
                        'dkas' => $resulte['tgl_terima'],
                        'kd_bidskpd' => $bidd,
                        'nm_bidang' => $nmbid,
                        'nilai' => $resulte['nilai'],
                        'nilai1' => number_format($resulte['nilai'],0),
                        'nocek'=>$ckkontrak2,
                        'status_drop' => $resulte['status_drop'],
                        'status' => $s                                                                                   
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);         
    } 


    function simpan_cair(){
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $cskpd = $this->input->post('skpd');
        $cket = $this->input->post('ket');
        $beban = $this->input->post('beban'); /*jenis spp*/
        $jns_bbn = $this->input->post('sjenis'); 
        $potongan = $this->input->post('tot_pot');
        $npwp = $this->input->post('npwp');
        $kd_sub_skpd = $this->input->post('kd_sub_skpd');
        $usernm= $this->session->userdata('pcNama');
        //$last_update=  date('y-m-d');
        $last_update=  "";

       // echo "$no_sp2d";
        $nospp=$this->tukd_model->get_nama($no_sp2d,'no_spp','trhsp2d','no_sp2d');

        $kontrak=rtrim($this->tukd_model->get_nama($nospp,'kontrak','trhspp','no_spp'));
       // echo "$kontrak";
        $total = str_replace(",","",$total);

        $nmskpd=$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd');
       //echo "$nmskpd";
        $sql = " UPDATE trhsp2d set status='1',no_kas='$nokas',tgl_kas='$tglkas',nocek='$nocek' where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);
        
    //Pengambilan Nomor Bukti Potongan bila tak ada potongan maka nomor Bukti tetap sama dengan nomor Kas   
        $buktistr = $nokas;
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
                INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
                WHERE b.no_sp2d = '$no_sp2d' --AND a.kd_rek5 NOT IN ('2110801','4140612')";
                    
        $query7 = $this->db->query($sql7);
        foreach($query7->result_array() as $resulte7){
            $jumlah=$resulte7['jumlah'];
            if ($jumlah>0){
                    $buktistr = $nokas+1;
                    $buktitrm=$this->tukd_model->get_nama($no_sp2d,'no_bukti','trhtrmpot','no_sp2d');
                    
                    $sql9 = "SELECT a.*,b.jns_spp FROM trspmpot a
                            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
                            WHERE b.no_sp2d = '$no_sp2d' --AND a.kd_rek5 NOT IN ('2110801','4140612')";
                    $query9 = $this->db->query($sql9);
                    
                        foreach($query9->result_array() as $resulte9){
                                $kdrekening=$resulte9['kd_rek6'];
                                $nmrekening=$resulte9['nm_rek6'];
                                $nilai=$resulte9['nilai'];
                                $jenis_spp=$resulte9['jns_spp'];
                                $kd_trans=$resulte9['kd_trans'];
                                $kd_sub_skpd=$resulte9['kd_sub_skpd'];
                                $this->db->query("INSERT into trdstrpot(no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans, kd_sub_skpd) 
                                                      values('$buktistr','$kdrekening','$nmrekening','$nilai','$cskpd','$kd_trans','$kd_sub_skpd')");
                            }

                    $sql8 = "SELECT SUM(a.nilai) as nilai_pot, b.kd_sub_skpd, b.keperluan, b.npwp,b.jns_spp, b.nm_skpd, c.kd_sub_kegiatan, c.kd_sub_kegiatan kd_subkegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat FROM trspmpot a INNER JOIN trhsp2d b ON a.no_spm = b.no_spm inner join trhspp c on b.no_spp = c.no_spp WHERE b.no_sp2d = '$no_sp2d' 
                        GROUP BY no_sp2d,b.keperluan, b.npwp,b.jns_spp,b.nm_skpd,c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat,b.kd_sub_skpd";
                    $query8 = $this->db->query($sql8); 
                        foreach($query8->result_array() as $resulte8){
                            $keperluan=$resulte8['keperluan'];
                            $nilai=$resulte8['nilai_pot'];
                            $npwp=$resulte8['npwp'];
                            $jenis=$resulte8['jns_spp'];
                            $nmskpd=$resulte8['nm_skpd'];
                            $kd_kegiatan=$resulte8['kd_sub_kegiatan'];
                            $kd_subkegiatan=$resulte8['kd_subkegiatan'];
                            $nm_kegiatan=$resulte8['nm_sub_kegiatan'];
                            $nmrekan=$resulte8['nmrekan'];
                            $kd_sub_skpd=$resulte8['kd_sub_skpd'];
                            $pimpinan=$resulte8['pimpinan'];
                            $alamat=$resulte8['alamat'];
                            $this->db->query("INSERT into trhstrpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,no_terima,nilai,npwp,jns_spp,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,nmrekan,pimpinan,alamat,kd_subkegiatan, kd_sub_skpd) 
                                                  values('$buktistr','$tglkas','Setor pajak nomor SP2D $no_sp2d','$usernm','$last_update','$cskpd','$nmskpd','$buktitrm','$nilai','$npwp','$jenis','$no_sp2d','$kd_kegiatan','$nm_kegiatan','$nmrekan','$pimpinan','$alamat','$kd_subkegiatan','$kd_sub_skpd')");
                        }
                    }
                }
        //Pengambilan Nomor STS bila tak ada HKPG maka nomor Bukti tetap sama dengan nomor Setor/nomor kas
        //HKPG dan Penghasilan tidak pernah diinput bersamaan

        $no_sts = $buktistr;
        $no_setor=$no_sts+1;
        $sql9 = "SELECT COUNT(*) as jumlah FROM trspmpot a
            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
            WHERE b.no_sp2d = '$no_sp2d' --AND a.kd_rek5 IN ('2110801','4140612') ";
                 
        /*$query9 = $this->db->query($sql9);
        foreach($query9->result_array() as $resulte9){
            $jumlah=$resulte9['jumlah'];
            if ($jumlah>0){
                    $no_sts = $buktistr+1;
                    $no_setor=$no_sts+2;
                    $sql10 = "SELECT a.*,c.kd_sub_kegiatan, c.kd_sub_kegiatan kd_subkegiatan FROM trspmpot a
                            LEFT JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
                            INNER JOIN trdspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
                            WHERE b.no_sp2d = '$no_sp2d' -- AND a.kd_rek5 IN ('2110801','4140612')
                            GROUP BY a.no_spm, a.kd_rek6,a.nm_rek6,a.nilai, a.kd_skpd, a.pot, a.kd_trans,c.kd_sub_kegiatan";
                    $query10 = $this->db->query($sql10);
                        foreach($query10->result_array() as $resulte10){
                                $kdrekening=$resulte10['kd_rek6'];
                                $nmrekening=$resulte10['nm_rek6'];
                                $nilai=$resulte10['nilai'];
                                $kd_kegiatan=$resulte10['kd_sub_kegiatan'];
                                $kd_trans=$resulte10['kd_trans'];
                                $this->db->query("INSERT into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan,kd_subkegiatan) 
                                                      values('$cskpd','$no_sts','$kd_trans','$nilai','$kd_kegiatan','$kd_subkegiatan')");
                            }
                            
                            if ($kdrekening=='2110801'){
                                    $this->db->query("INSERT into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,no_kas,tgl_kas,sumber,jns_cp,pot_khusus,no_sp2d) 
                                                  values('$no_sts','$cskpd','$tglkas','$nmrekening atas SP2D $no_sp2d','$nilai','$kd_kegiatan','5','$no_sts','$tglkas','0','1','1','$no_sp2d')");
                                } else {
                                    $this->db->query("INSERT into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,no_kas,tgl_kas,sumber,jns_cp,pot_khusus,no_sp2d) 
                                                  values('$no_sts','$cskpd','$tglkas','$nmrekening atas SP2D $no_sp2d','$nilai','$kd_kegiatan','5','$no_sts','$tglkas','0','1','2','$no_sp2d')");
                                }
                        }
                    }   
                   */ 
            
            if(($beban<4) or ($beban == 6 && $jns_bbn == 4) or ($beban == 6 && $jns_bbn == 1) or ($beban == 4 && $jns_bbn != 9)){

            $this->db->query(" INSERT into tr_setorsimpanan(no_kas,tgl_kas,no_bukti,tgl_bukti,kd_skpd,nilai,keterangan,jenis, kd_sub_skpd) 
                             values('$no_setor','$tglkas','$no_setor','$tglkas','$cskpd',$total-$potongan,'Pergeseran Uang BANK atas SP2D $no_sp2d','1','$kd_sub_skpd')");
            }
            
            $no_trans=$no_setor+1;
            
            if (($beban=='4') && ($jns_bbn=='1')){
            $sql2 = " INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay,panjar, kd_sub_skpd)
                      values('$no_trans','$tglkas','$no_trans','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','BANK','0', '$kd_sub_skpd') ";
            $asg2 = $this->db->query($sql2);
            }
             if (($beban=='4' && $jns_bbn =='9' )){
            $sql2 = " INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay,panjar, kd_sub_skpd)
                      values('$nokas','$tglkas','$nokas','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','GAJI','0', '$kd_sub_skpd') ";
            $asg2 = $this->db->query($sql2);
            }
            if ($beban=='5'){
            $sql2 = " INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay,panjar, kd_sub_skpd)
                      values('$nokas','$tglkas','$nokas','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','BANK','0', '$kd_sub_skpd') ";
            $asg2 = $this->db->query($sql2);
            }

            if (($beban=='6') && ($jns_bbn=='4')){
            $sql2 = " INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay,panjar, kd_sub_skpd)
                      values('$no_trans','$tglkas','$no_trans','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','BANK','0', '$kd_sub_skpd') ";
            $asg2 = $this->db->query($sql2);
            }

            if (($beban=='6' && $jns_bbn !='1' )){
            $sql2 = " INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay,panjar, kd_sub_skpd)
                      values('$nokas','$tglkas','$nokas','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','LS','0', '$kd_sub_skpd') ";
            $asg2 = $this->db->query($sql2);
            }
            
            
            if($beban ==4 || $beban == 6){
            $sql = " SELECT a.no_spp,a.kd_skpd, a.kd_sub_skpd,a.kd_sub_kegiatan,a.kd_sub_kegiatan kd_subkegiatan,a.kd_rek6,a.nilai,b.bulan,c.no_spm,d.no_sp2d,b.sts_tagih FROM trdspp a 
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
                $kd_sub_skpd=$resulte['kd_sub_skpd'];
                $giat=$resulte['kd_sub_kegiatan'];
                $subgiat=$resulte['kd_subkegiatan'];
                $rek5=$resulte['kd_rek6'];
                $nilai=$resulte['nilai'];
                $nmskpd=$this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');

                $nmsubgiat = empty($subgiat) || $subgiat == '' ? '' : $this->tukd_model->get_nama($subgiat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');
                
                $nmgiat = empty($giat) || $giat == '' ? '' : $this->tukd_model->get_nama($giat,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');
                //echo "$nmgiat";
                $nmrek5 = empty($rek5) || $rek5 == '' ? '' : $this->tukd_model->get_nama($rek5,'nm_rek6','ms_rek6','kd_rek6');
                //echo " $rek5 $nmrek5 <> ";
                if(($beban ==4 && $jns_bbn ==1)){
                $this->db->query("INSERT trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,kd_kegiatan,nm_kegiatan, kd_sub_skpd) 
                                  values('$no_trans','$giat','$nmgiat','$rek5','$nmrek5',$nilai,'$sp2d','$skpd','$subgiat','$nmsubgiat', '$kd_sub_skpd') ");
                }
                if(($beban == 4 && $jns_bbn ==9 )){
                $this->db->query("INSERT trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,kd_kegiatan,nm_kegiatan, kd_sub_skpd) 
                                  values('$nokas','$giat','$nmgiat','$rek5','$nmrek5',$nilai,'$sp2d','$skpd','$subgiat','$nmsubgiat', '$kd_sub_skpd') ");
                }
                if(($beban ==6 && $jns_bbn ==4)){
                $this->db->query("INSERT trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,kd_kegiatan,nm_kegiatan, kd_sub_skpd) 
                                  values('$no_trans','$giat','$nmgiat','$rek5','$nmrek5',$nilai,'$sp2d','$skpd','$subgiat','$nmsubgiat', '$kd_sub_skpd') ");
                }
                if(($beban == 6 && $jns_bbn !=1 )){
                $this->db->query("INSERT trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,kd_kegiatan,nm_kegiatan, kd_sub_skpd) 
                                  values('$nokas','$giat','$nmgiat','$rek5','$nmrek5',$nilai,'$sp2d','$skpd','$subgiat','$nmsubgiat', '$kd_sub_skpd') ");
                }
            }
            } 
            echo '1';       
    }


   function batal_cair($jns_bbn=''){
        $skpd     = $this->session->userdata('kdskpd');
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas'); 
        $tglkas = $this->input->post('tcair');
        $beban=$this->input->post('beban');
        $jns_bbn=$this->input->post('jenis');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $nospp=$this->tukd_model->get_nama($no_sp2d,'no_spp','trhsp2d','no_sp2d');
        $kontrak=$this->tukd_model->get_nama($nospp,'kontrak','trhspp','no_spp');
        
        $buktistr = $nokas;
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd
            WHERE b.no_sp2d = '$no_sp2d' AND b.kd_skpd='$skpd' ---AND a.kd_rek6 NOT IN ('2110801','4140612') ";
        $query7 = $this->db->query($sql7);
        foreach($query7->result_array() as $resulte7){
            $jumlah=$resulte7['jumlah'];
                if ($jumlah>0){
                $buktistr = $nokas+1;
            }
        }
    
    $no_sts = $buktistr;
    $no_setor=$no_sts+1;
/*        $sql8 = "SELECT COUNT(*) as jumlah FROM trspmpot a
            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd
            WHERE b.no_sp2d = '$no_sp2d' AND b.kd_skpd='$skpd' --AND a.kd_rek6 IN ('2110801','4140612') ";
        $query8 = $this->db->query($sql8);
        foreach($query8->result_array() as $resulte8){
            $jumlah=$resulte8['jumlah'];
                if ($jumlah>0){
                $no_sts = $buktistr+1;
                $no_setor=$no_sts+1;                    
                }
            }*/
        

        if(($beban<4) or ($beban == 6 && $jns_bbn == 1) or ($beban == 6 && $jns_bbn == 4) or ($beban == 4 && $jns_bbn != 9)){

        $sql1 = " DELETE from tr_setorsimpanan where no_kas='$no_setor' and kd_skpd = '$skpd' ";
        $asg1 = $this->db->query($sql1);
        } 

         $no_trans=$no_setor+1;
     

        $sql = " UPDATE trhsp2d set status='0',no_kas='',tgl_kas=tgl_terima where no_sp2d='$no_sp2d' and kd_skpd = '$skpd' ";
        $asg = $this->db->query($sql);


        
        if(($beban ==4 && $jns_bbn ==1)){
             $sql1 = " DELETE from trhtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' ";
             $asg1 = $this->db->query($sql1);
             $sql1 = " DELETE from trdtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' ";
             $asg1 = $this->db->query($sql1);
        }
        if(($beban ==6 && $jns_bbn ==4)){
             $sql1 = " DELETE from trhtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' ";
             $asg1 = $this->db->query($sql1);
             $sql1 = " DELETE from trdtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' ";
             $asg1 = $this->db->query($sql1);
        }
        if(($beban == 6 && $jns_bbn !=1)){
         $sql1 = " DELETE from trhtransout where no_bukti='$nokas' and kd_skpd = '$skpd' ";
         $asg1 = $this->db->query($sql1);
         $sql1 = " DELETE from trdtransout where no_bukti='$nokas' and kd_skpd = '$skpd' ";
         $asg1 = $this->db->query($sql1);
         }
         if(($beban == 4 && $jns_bbn ==9)){
         $sql1 = " DELETE from trhtransout where no_bukti='$nokas' and kd_skpd = '$skpd' ";
         $asg1 = $this->db->query($sql1);
         $sql1 = " DELETE from trdtransout where no_bukti='$nokas' and kd_skpd = '$skpd' ";
         $asg1 = $this->db->query($sql1);
         }
       
        $sql1 = " DELETE from trhstrpot where no_bukti='$buktistr' and kd_skpd = '$skpd' ";
        $asg1 = $this->db->query($sql1);
        
        $sql1 = " DELETE from trdstrpot where no_bukti='$buktistr' and kd_skpd = '$skpd' ";
        $asg1 = $this->db->query($sql1);
        
        $sql1 = " DELETE from trhkasin_pkd where no_sts='$no_sts' and kd_skpd = '$skpd' ";
        $asg1 = $this->db->query($sql1);
        $sql1 = " DELETE from trdkasin_pkd where no_sts='$no_sts' and kd_skpd = '$skpd' ";
        $asg1 = $this->db->query($sql1);
        
        
    echo '1';
    }

     function load_jenis_beban($jenis='') {
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
        $result = array(( 
                        array(
                        "id"   => 1 ,
                        "text" => " LS Bendahara (Transfer)"
                        ) 
                    ) ,
                        ( 
                        array( 
                      "id"   => 2 ,
                      "text" => " LS Bendahara (Non Tunai CMS)"
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
        $result = array(( 
                        array(
                        "id"   => 1 ,
                        "text" => " LS Bendahara (Non Tunai CMS)"
                        ) 
                    ) ,
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





}