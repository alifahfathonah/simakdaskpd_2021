<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class setor extends CI_Controller
{

    function __construct()
    {
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
    }

   	function tahun_ini(){
        $data['page_title']= 'INPUT S T S';
        $this->template->set('title', 'INPUT S T S');   
        $this->template->load('template','tukd/pendapatan/setor',$data) ; 
    }

	function tahun_lalu()
    {
        $data['page_title']= 'INPUT S T S TAHUN LALUS';
        $this->template->set('title', 'INPUT S T S TAHUN LALUS');   
        $this->template->load('template','tukd/pendapatan/setor_lalu',$data) ; 
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

	function load_sts_tl() {
		$kd_skpd     = $this->session->userdata('kdskpd');
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
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='2' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
		
		
		//$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
		$sql = "
		SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='2' 
		$where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd where kd_skpd='$kd_skpd' and jns_trans='2' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
		";
		
		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array( 
						'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
						'sumber' => $resulte['sumber'],
                        'urut' => $resulte['urut'],
						'no_terima' => $resulte['no_terima'],
                        'nm_skpd' => $resulte['nm_skpd']                                                                                           
                        );
                        $ii++;
				}
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();	
    	   
	}

	function load_pengirim() {        
        
        $skpd = $this->session->userdata('kdskpd');               
        $lccr = $this->input->post('q');
        
        $sql = "select * from ms_pengirim WHERE LEFT(kd_skpd,17)=LEFT('$skpd',17) 
		        AND (UPPER(kd_pengirim) LIKE UPPER('%$lccr%') OR UPPER(nm_pengirim) LIKE UPPER('%$lccr%')) 
				order by cast(kd_pengirim as int)";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_pengirim' => $resulte['kd_pengirim'],  
                        'nm_pengirim' => $resulte['nm_pengirim'],
                        'kd_skpd'     => $resulte['kd_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}

	function load_trskpd1_pend($lccr='') {
            $lccr='';        
            if(strlen($lccr)==1){
                $lcpj = 1;
            }else{
                $lcpj = 2;
            }
            $lcskpd  = $this->session->userdata('kdskpd');
            //$lcskpd = $this->uri->segment(4);
           $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trskpd a 
                    WHERE left(a.jns_kegiatan,1)='4' and a.kd_skpd = '$lcskpd'" ;    
            //echo $sql;    
            $query1 = $this->db->query($sql);  
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
               
            echo json_encode($result);
        	   
    	} 

	 function list_no_terima() {
		$kd_skpd = $this->session->userdata('kdskpd');
		$lccr = $this->input->post('q');
		
        $sql   = "SELECT * from tr_terima where kd_skpd='$kd_skpd' 
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

        function simpan_sts_pendapatan(){
        
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
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

        $nmskpd      = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
		$last_update = date('Y-m-d H:i:s');
      // $last_update = " ";
        $msg = array();
        if ($tabel == 'trhkasin_pkd') {
            
            $sql = "delete from trhkasin_pkd where kd_skpd='$skpd' and no_sts='$nomor'";
            $asg = $this->db->query($sql);
            
            
            if ($asg){
				if($jnsrek==5){
				 $sql = "INSERT into trhkasin_pkd(no_kas,no_sts,kd_skpd,tgl_sts,tgl_kas,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp) 
                        values('$nomor_kas','$nomor','$skpd','$tgl','$tgl_kas','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','1','$sp2d','$jns_cp')";
				} else{
				 $sql = "INSERT into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima,urut,bank) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$pengirim','0','$sp2d','$jns_cp','$no_terima','$surut','$sbank')";
				}
               
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd where no_sts='$nomor' AND kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek,rupiah,kd_sub_kegiatan,no_terima,kd_rek6) values $lcnilaidet";
                        $asg = $this->db->query($sql); 
                    }                
                }            
            } 
            echo '2';
        }
        
         
    }

	function update_sts_pendapatan(){
        
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        $nohide      = $this->input->post('nohide');
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
        
		$last_update = date('d-m-y H:i:s');
      // $last_update = " ";
        $msg = array();        
            
            $sql = "delete from trhkasin_pkd where kd_skpd='$skpd' and no_sts='$nohide'";
            $asg = $this->db->query($sql);
            
				
				 $sql = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima,urut,bank) 
                        values('$nomor','$skpd','$tgl','$ket','$total','','$giat','$jnsrek','','$pengirim','0','','','$no_terima','$curut','$cbank')";
				
                       

                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd where no_sts='$nohide' AND kd_skpd='$skpd' ";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek,rupiah,kd_sub_kegiatan,no_terima,kd_rek6) values $lcnilaidet";
                        $asg = $this->db->query($sql); 

                    }                
                }            
            
            echo '2';    
        
    }

   function ambil_rek() {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(4);
        $lcgiat = $this->uri->segment(5);
        $lcfilt = $this->uri->segment(6);
        $lc = '';
        if ($lcfilt!=''){
            $lcfilt = str_replace('A',"'",$lcfilt);
            $lcfilt = str_replace('B',",",$lcfilt);
            $lc = " and a.kd_rek6 not in ($lcfilt)";
        }
        
       
            $sql = "SELECT a.kd_rek6,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=a.kd_rek6) AS nm_rek FROM 
            trdrka a where a.kd_skpd = '$lckdskpd' and a.kd_sub_kegiatan = '$lcgiat' and 
            upper(a.kd_rek6) like upper('%$lccr%') $lc";
            
       
        
        //echo $sql;
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek' => $resulte['nm_rek']                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
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
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd a where $par and a.jns_trans='4' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
		
		
		//$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
		$sql = "
		SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd a where $par and a.jns_trans='4' 
		$where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd where $par2 and jns_trans='4' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, cast(a.urut as int)
		";
		
		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                      
           $bidang = "00";
           
           $par_sts = $resulte['no_sts'];
           $stt = $this->db->query("SELECT count(no_sts) as row from trhkasin_ppkd where no_sts='$par_sts'")->row();
           $cek_stt = $stt->row;
           
           
            $row[] = array( 
						'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'kd_subkegiatan' => $resulte['kd_subkegiatan'],
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
                        'status_kasda' => $cek_stt	
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
		
        $sql = "SELECT a.*, (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek 
        from trdkasin_pkd a where a.no_sts = '$kriteria' and a.kd_skpd='$skpd' order by a.no_sts";
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
                        'kd_rek5' => $resulte['kd_rek6'],
                        'nm_rek' => $resulte['nm_rek'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'rupiah' =>  number_format($resulte['rupiah'],2,'.',','),
                        'no_terima' => $resulte['no_terima']
						);
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

     function hapus_sts(){
        $nomor = $this->input->post('no');
	    $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "delete from trhkasin_pkd where no_sts='$nomor' AND kd_skpd='$kd_skpd' ";
        $asg = $this->db->query($sql);
		$sql = "delete from trdkasin_pkd where no_sts='$nomor'  AND kd_skpd='$kd_skpd'";
		$asg = $this->db->query($sql);
        echo '1';                
    }

	function load_ttd_pa_ppk(){
        $kdskpd = $this->session->userdata('kdskpd');
		$cari=$this->input->post('q');
		return $this->master_ttd->load_pa($kdskpd,$cari);           
	}

	function load_ttd_cek(){
        $kdskpd = $this->session->userdata('kdskpd');
		$cari=$this->input->post('q');
		return $this->master_ttd->load_pen($kdskpd,$cari);
	}
}