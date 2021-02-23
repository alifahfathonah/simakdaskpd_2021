 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cmsc_validasi extends CI_Controller {

    function __construct(){    
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }
 
    function validasi(){
        $data['page_title']= 'DAFTAR VALIDASI NON TUNAI';
        $this->template->set('title', 'DAFTAR VALIDASI NON TUNAI');   
        $this->template->load('template','tukd/cms/validasi_cms',$data) ; 
    }  
 
    function no_urut_validasicms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    
    $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "kd_skpd = '$kd_skpd'";
        }else{
            if(substr($kd_skpd,8,2)=='00'){
                $init_skpd = "left(kd_skpd,22) = left('$kd_skpd',22)";
            }else{
                $init_skpd = "KD_SKPD = '$kd_skpd'";
            }            
        }
    
    $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
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

   function no_urut_validasibku(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "KD_SKPD = '$kd_skpd'";
        }else{
            if(substr($kd_skpd,8,2)=='00'){
                $init_skpd = "left(KD_SKPD,22) = left('$kd_skpd',22)";
            }else{
                $init_skpd = "KD_SKPD = '$kd_skpd'";
            }            
        }
    
    $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
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
    --select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    --select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
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

    function load_sisa_bank_val(){
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
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'1' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_upload='1' and status_validasi='0' and left(a.kd_skpd,22)=left('$kd_skpd',22)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,22)=left('$kd_skpd',22) union ALL
      select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,22)=left('$kd_skpd',22) 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where left(kode,22)=left('$kd_skpd',22))b");
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

        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a 
        where left(a.kd_skpd,22)=left('$skpd',22) and a.status_upload='1' and a.status_validasi='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload,d.bersih,a.jns_spp FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd and c.username=a.username
        left join (
        select a.username,a.no_voucher,a.kd_skpd,sum(a.nilai) bersih from trdtransout_transfercms a where left(a.kd_skpd,22)=left('$skpd',22)
        group by username,no_voucher,kd_skpd)d on d.no_voucher=a.no_voucher and d.kd_skpd=a.kd_skpd and d.username=a.username
        where left(a.kd_skpd,22)=left('$skpd',22)/*and a.status_upload='1' and status_validasi='0'*/ $and  
        group by 
        a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload,d.bersih,a.jns_spp       
        order by a.kd_skpd,cast(a.no_voucher as int), status_validasi");
        
          
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
                $stt_val="<button class='button' style='font-size:10px; padding: 5px;' >Sudah Validasi</button>";
            }else{
                $stt_val="<button class='button-merah' style='font-size:10px; padding: 5px;' >Belum Validasi</button>";
            }            
               
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

    function simpan_validasicms(){
        $tabel    = $this->input->post('tabel');                
        $skpd     = $this->input->post('skpd');
        $csql     = $this->input->post('sql');      
        $nval     = $this->input->post('no');  
        
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

    if($tabel == 'trvalidasi_cmsbank') {
                            
                    $sql = "INSERT into trvalidasi_cmsbank(no_voucher,tgl_voucher,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,tgl_validasi,status_validasi,no_validasi,no_bukti,username)"; 
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
                        where left(trhtransout_cmsbank.kd_skpd,22)=left('$skpd',22)";
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
                                    $sql = "INSERT INTO trdtransout (no_bukti, no_sp2d, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai, kd_skpd, sumber, username)
                                            SELECT c.no_bukti, a.no_sp2d, b.kd_sub_kegiatan, b.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6, b.nilai, b.kd_skpd, b.sumber, a.username
                                            FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b on b.no_voucher=a.no_voucher and a.kd_skpd=b.kd_skpd and a.username=b.username
                                            LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd and c.username=a.username
                                            WHERE c.no_validasi='$nval' and c.kd_bp='$skpd'";
                                    $asg = $this->db->query($sql);                                    
                                    
                                    if (!($asg)){
                                        $msg = array('pesan'=>'0');
                                        echo json_encode($msg);                     
                                    }  else {                                                                        
                                        //Hpotongan
                                        $sql = "INSERT INTO trhtrmpot (no_bukti, tgl_bukti, ket, username, tgl_update, kd_skpd, nm_skpd, no_sp2d, nilai, npwp, jns_spp, status, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nmrekan, pimpinan, alamat, ebilling, rekening_tujuan, nm_rekening_tujuan, no_kas)
                                        SELECT cast(c.no_bukti as int)+1 as no_bukti, c.tgl_validasi as tgl_bukti, d.ket, d.username, d.tgl_update, d.kd_skpd, d.nm_skpd, d.no_sp2d, d.nilai, d.npwp, d.jns_spp, d.status, d.kd_sub_kegiatan, d.nm_sub_kegiatan, d.kd_rek6, d.nm_rek6, d.nmrekan, d.pimpinan, d.alamat, d.ebilling, d.rekening_tujuan, d.nm_rekening_tujuan, c.no_bukti 
                                        FROM trhtrmpot_cmsbank d LEFT JOIN trhtransout_cmsbank a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd and a.username=d.username
                                        LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd and a.username=c.username
                                        WHERE c.no_validasi='$nval' and a.status_trmpot='1' and c.kd_bp='$skpd'";
                                            $asg = $this->db->query($sql);                                    
                                    
                                            if (!($asg)){
                                                $msg = array('pesan'=>'0');
                                                echo json_encode($msg);                     
                                            }  else {                                                                        
                                                
                                                    $sql = "INSERT INTO trdtrmpot (no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, username)
                                                    SELECT cast(c.no_bukti as int)+1 as no_bukti, b.kd_rek6, b.nm_rek6, b.nilai, b.kd_skpd, b.kd_rek_trans, b.ebilling, b.username
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
                                                    
                                                }
                                                
                                            }
                                        
                                    }
                            }
                        }
                    }                    
                                                        
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
            $init_skpd = "left(a.kd_skpd,22)=left('$skpd',22)";
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
        where $init_skpd /*and a.status_upload='1'*/ $and         
        group by 
        a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload
        order by cast(a.no_voucher as int),a.kd_skpd"); 
        
        
 
            
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
                $stt_val="<button class='button' style='font-size:10px; padding: 5px;' >Sudah Validasi</button>";
            }else{
                $stt_val="<button class='button-merah' style='font-size:10px; padding: 5px;' >Belum Validasi</button>";
            }             
               
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
            $sql ="DELETE from trhtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
            $asg = $this->db->query($sql);   
                    
            if (!($asg)){
               $msg = array('pesan'=>'0');
                echo json_encode($msg);
                die();                     
            }                         
                       
            $sql ="DELETE from trdtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
            $asg = $this->db->query($sql);   
                    
            if (!($asg)){
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                die();                     
            }                       
                            
            $sql ="DELETE from trvalidasi_cmsbank where no_bukti='$nbku' and no_voucher='$nval' and kd_skpd='$skpd'";
            $asg = $this->db->query($sql);
                            
            if (!($asg)){
                $msg = array('pesan'=>'0');
                echo json_encode($msg); die();                    
            }  
                                    
            $sql ="UPDATE trhtransout_cmsbank set status_validasi='0', tgl_validasi='' where no_voucher='$nval' and kd_skpd='$skpd'";
            $asg = $this->db->query($sql);                                   
                                    
            if (!($asg)){
                $msg = array('pesan'=>'0');
                echo json_encode($msg); die();                    
            }                                                                         
            //Hpotongan
            $sql = "SELECT count(*) as jml from trhtransout_cmsbank where no_voucher='$nval' and kd_skpd='$skpd' and status_trmpot='1'";
            $asg = $this->db->query($sql)->row();                                    
            $initjml = $asg->jml;
                                                
            if($initjml=='1'){                               
                $sql = "DELETE trhtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                $asg = $this->db->query($sql);                                    
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);   die();                  
                }                    
                                                        
                $sql = "DELETE trdtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                $asg = $this->db->query($sql);                                    
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg); die();                    
                }                   
                                                        
                $sql = "DELETE trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
                $asg = $this->db->query($sql);                                    
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);                     
                }  else {                  
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }
                                                                                                  
                                                    
            }else{
                $sql = "DELETE trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
                $asg = $this->db->query($sql);                                    
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg); die();                     
                }  else {                  
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg); die();
                }
            }                                                                   
                                                        
        }
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
            $init_skpd = "left(a.kd_skpd,22)=left('$skpd',22)";
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






}