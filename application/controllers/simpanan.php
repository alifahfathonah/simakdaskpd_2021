<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/** 
 * Tukd
 * 
 * @package  
 * @author Boomer
 * @copyright 2016
 * @version $Id$ 
 * @access public 
 */
class simpanan extends CI_Controller {
  
	function __construct() 
	{	
		parent::__construct();
	}



function ambil_simpanan()
    {
        $data['page_title']= 'INPUT AMBIL SIMPANAN';
        $this->template->set('title', 'INPUT AMBIL SIMPANAN');   
        $this->template->load('template','tukd/simpanan/ambil_simpanan',$data) ; 
    }	

    function load_sisa_bank(){
        $kd_skpd = $this->session->userdata('kdskpd');                
        $skpdbp = substr($kd_skpd,8,2);
        $cek_skpd1=explode('.',$kd_skpd);
        if($cek_skpd1[7]=='0000'){
            $init_skpd = "kode='$kd_skpd'";             
        }else{
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
        }
        
            $query1 = $this->db->query("SELECT sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
      select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar where left(kd_skpd,7)=left('$kd_skpd',7)   UNION ALL
      select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,7)=left('$kd_skpd',7) UNION ALL
                              
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where left(kd_skpd,7)=left('$kd_skpd',7) union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where left(kd_skpd,7)=left('$kd_skpd',7) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') and left(a.kd_skpd,7)=left('$kd_skpd',7) UNION ALL
                        SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' and left(a.kd_skpd,7)=left('$kd_skpd',7)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,7)=left('$kd_skpd',7) union ALL
      select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,7)=left('$kd_skpd',7) 
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

    function simpan_ambil_simpanan(){
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
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

    function update_ambilsimpanan(){
        $query  = $this->input->post('st_query');
        $asg    = $this->db->query($query);
         if(!$asg){
            echo "0";
        } else {
            echo "1";
        }
    } 

    function load_ambilsimpanan() {
        
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

        $sql = "SELECT count(*) as tot from tr_ambilsimpanan WHERE  kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows * from tr_ambilsimpanan WHERE  kd_skpd = '$kd_skpd' $where and no_kas not in (
                SELECT TOP $offset no_kas from tr_ambilsimpanan WHERE  kd_skpd = '$kd_skpd' $where order by no_kas) order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
            
        foreach($query1->result_array() as $resulte)
        { 
            $bank = $resulte['bank'];
            
            $sql = $this->db->query("SELECT count(nama) as cekk from ms_bank where kode='$bank'")->row();
            $sqlcekk = $sql->cekk;
            
            if($sqlcekk==0){
                $nmbank = "";
            }else{
                $sql = $this->db->query("SELECT nama from ms_bank where kode='$bank'")->row();
                $nmbank = $sql->nama;   
            }            
            
            //$kaskas = $resulte['no_kas'];
            //$sql = $this->db->query("select jns_spp from trhsp2d where no_kas='$kaskas'")->row();
            //$jns_spp = $sql->jns_spp;  
            
            $row[] = array(
                        'id'          => $ii,        
                        'no_kas'      => $resulte['no_kas'],
                        'no_bukti'      => $resulte['no_bukti'],
                        //'tgl_kas'     => $this->tukd_model->rev_date($resulte['tgl_kas']),
                        'tgl_kas'     => $resulte['tgl_kas'],
                        'tgl_bukti'     => $resulte['tgl_bukti'],
                        'kd_skpd'     => $resulte['kd_skpd'],
                        'nilai'       => number_format($resulte['nilai']),
                        'nilai2'       => $resulte['nilai'],
                        'bank'        => $bank,
                        'nmbank'        => $nmbank,
                        'nm_rekening' => $resulte['nm_rekening'],
                        'keterangan'  => $resulte['keterangan'],
                        'status'    => $resulte['status_drop'], 
                        'jns_spp'   => '',
                        'kd_bid'    => $dbidang                           
                        );
                        $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
        }

    function hapus_ambilsimpanan() {        
        $no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');        
        echo $query = $this->db->query("delete from tr_ambilsimpanan where no_kas='$no' and kd_skpd='$skpd' ");
    }

    function no_urut(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
    select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
    select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
    select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
    select no_panjar nomor, 'Pemberian Panjar CMS' ket,kd_skpd from tr_panjar_cmsbank where  isnumeric(no_panjar)=1  union ALL
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
    select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='3' union ALL
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
}