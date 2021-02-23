<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class rka_n1 extends CI_Controller {
	function __contruct()
	{	
		parent::__construct();
	}  

function tambah_rka()
    {
        $cRet              = '<h3 style="border-left: 6px solid #2196F3!important;background-color: #ddffff!important; padding: 5px; width: 30%;">Input RKA Murni</h3>' ;
        $data['prev']      = $cRet;
        $data['page_title']= 'Input RKA Murni';
        $this->template->set('title', 'Input RKA Murni');   
        $this->template->load('template','anggaran/rup/tambah_rka_penyusunan',$data) ; 
   }

function tambah_rkax() 
    {
        $jk   = $this->rka_model->combo_skpd();
        $ry   =  $this->rka_model->combo_giat();
        $cRet = '';
        
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
                   <tr >                       
                        <td>INPUT ANGGARAN RUP $jk</td>
                        <td>$ry</td>
                        </tr>
                  ";
         
        $cRet .="</table>";
        $data['prev']= $cRet;
        $data['page_title']= 'INPUT RENCANA KEGIATAN ANGGARAN';
        $this->template->set('title', 'INPUT RKA');   
         $sql = "select a.kd_rek5,b.nm_rek5,a.nilai,a.nilai as total from trdrka a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5";                   
        
        $query1 = $this->db->query($sql);  
        $results = array();
        $i = 1;
        foreach($query1->result_array() as $resulte)
        { 
            $results[] = array(
                       'id' => $i,
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],  
                        'nilai' => $resulte['nilai'] ,
                        'total' => $resulte['total']                            
                        );
                        $i++;
        }
        $this->template->load('template','anggaran/rka/tambah_rka_n1',$data) ; 
        $query1->free_result();
   }

    function select_rka($kegiatan='') {

        $sql = "SELECT a.kd_rek5,b.nm_rek5,a.nilai,a.nilai_sempurna,a.nilai_ubah,a.sumber,a.sumber2,a.sumber3,a.sumber4,a.nilai_sumber,a.nilai_sumber2
                ,a.nilai_sumber3,a.nilai_sumber4 from trdrka_n1 a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5 
                join trskpd_n1 c on a.kd_kegiatan=c.kd_kegiatan
                where a.kd_kegiatan='$kegiatan'
                order by a.kd_rek5";       
                      
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {
            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],  
                        'nilai' => number_format($resulte['nilai'],"2",".",","),
                        'nilai_sempurna' => number_format($resulte['nilai_sempurna'],"2",".",","),
                        'nilai_ubah' => number_format($resulte['nilai_ubah'],"2",".",","),                             
                        'sumber' => $resulte['sumber'],
                        'sumber2' => $resulte['sumber2'],
                        'sumber3' => $resulte['sumber3'],
                        'sumber4' => $resulte['sumber4'],                                
                        'nilai_sumber' => number_format($resulte['nilai_sumber'],"2",".",","), 
                        'nilai_sumber2' => number_format($resulte['nilai_sumber2'],"2",".",","), 
                        'nilai_sumber3' => number_format($resulte['nilai_sumber3'],"2",".",","),
                        'nilai_sumber4' => number_format($resulte['nilai_sumber4'],"2",".",",")                                

                        );
                        $ii++;
        }
           
           echo json_encode($result);
            $query1->free_result();
    }

    function select_rka_rancang($kegiatan='',$skpd='') {
        $result=$this->anggaran_murni_model_n1->select_rka_rancang($kegiatan,$skpd);    
        echo json_encode($result);
    } 

    function tsimpan_ar_rancang(){
        
        $kdskpd = $this->input->post('kd_skpd');
        $kdkegi = $this->input->post('kd_kegiatan');
        $kdrek  = $this->input->post('kd_rek5');
        $nilai  = $this->input->post('nilai');
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
        
        $data=$this->anggaran_murni_model_n1->tsimpan_ar_rancang($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4);            
          
        echo json_encode($data);
        
    }

    function simpan_rincian_dpo(){
        $id                 = $this->session->userdata('pcNama');
        $idskpd             = $this->session->userdata('kdskpd');
        $header             = $this->input->post('header');
        $kd_barang          = $this->input->post('kd_barang');
        $kode               = $this->input->post('kode');
        $kd_kegiatan        = $this->input->post('kd_kegiatan');      
        $kd_rek5            = $this->input->post('kd_rek');      
        $no_po              = $this->input->post('no_po');
        $cek              = $this->input->post('no_po');       
        $no_trdrka          = $this->input->post('no_trdrka');      
        $uraian             = $this->input->post('uraian');      
        $volume1            = $this->input->post('volume1');      
        $volume1_sempurna1  = $this->input->post('volume1_sempurna1');      
        $volume_ubah1       = $this->input->post('volume_ubah1');      
        $satuan1            = $this->input->post('satuan1');      
        $satuan_sempurna1   = $this->input->post('satuan_sempurna1');      
        $satuan_ubah1       = $this->input->post('satuan_ubah1');      
        $harga1             = $this->input->post('harga1');
        $harga_sempurna1    = $this->input->post('harga_sempurna1'); 
        $harga_ubah1        = $this->input->post('harga_ubah1');      
        $total              = $this->input->post('total');      
        $total_sempurna     = $this->input->post('total_sempurna');
        $total_ubah         = $this->input->post('total_ubah'); 
        $kode_unik          = $this->input->post('unik');
        $sdana1             = $this->input->post('sdana1');
        $sdana2             = $this->input->post('sdana2'); 
        $sdana3             = $this->input->post('sdana3');
        $nsumber2           = $this->input->post('nsumber2'); 
        $nsumber3           = $this->input->post('nsumber3');     
        $kd_lokasi          = $this->input->post('kd_lokasi');

        /*untuk menyimpan nilai no_po*/
        if($no_po==''){
            $unik=$this->db->query("SELECT isnull(max(cast(no_po as int)),0)+10 as nopo from trdpo_n1 where no_trdrka='$no_trdrka'")->row();            
            $no_po=$unik->nopo;
        }else{
            $no_po=$no_po-1;  /*nomer no_po ketika di sisipkan*/        
        }


        $unik=$this->db->query("SELECT isnull(max(cast(no_po as int)),0)+1 as oke from trdpo_n1")->row();
        $kd_sisb=$unik->oke; /*kode unik di trdpo*/

        $sql ="INSERT into trdpo_n1(header,kd_skpd,kd_sub_kegiatan,kode,kd_barang,kd_rek6,no_po,no_trdrka,uraian,volume,volume1,volume_sempurna1,volume_ubah1,satuan1,satuan_sempurna1,satuan_ubah1,harga1,harga_sempurna1,harga_ubah1,total,total_sempurna,total_ubah,kode_sbl,kd_lokasi)
                        values ('$header','$idskpd','$kd_kegiatan','$kode','$kd_barang','$kd_rek5','$no_po','$no_trdrka','$uraian','$volume1','$volume1','$volume1_sempurna1','$volume_ubah1','$satuan1','$satuan_sempurna1','$satuan_ubah1','$harga1','$harga_sempurna1','$harga_ubah1','$total','$total_sempurna','$total_ubah','$kd_sisb','$kd_lokasi')"; 
        $this->db->query($sql);

        if($cek!=''){ /*untuk penyisipan*/ 
            $update_nopo="UPDATE a SET a.no_po=b.unix
                        from trdpo_n1 a inner join 
                        (SELECT ROW_NUMBER() OVER(ORDER BY cast(no_po as int))*10 unix, no_po from trdpo_n1 where no_trdrka='$no_trdrka') b 
                        on a.no_po=b.no_po where no_trdrka='$no_trdrka'";
            $this->db->query($update_nopo);
        }


        $query1 = $this->db->query("UPDATE trdrka_n1 set
            sumber              = '$sdana1',
            sumber2             = '$sdana2',
            sumber3             = '$sdana3',
            sumber1_su          = '$sdana1',
            sumber2_su          = '$sdana2',
            sumber3_su          = '$sdana3',
            sumber1_ubah        = '$sdana1',
            sumber2_ubah        = '$sdana2',
            sumber3_ubah        = '$sdana3',
            nilai_sumber        = (select abs(sum(total)-$nsumber2-$nsumber3) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
            nilai_sumber2       = $nsumber2,
            nilai_sumber3       = $nsumber3,
            nsumber1_su         = (select abs(sum(total_sempurna)-$nsumber2-$nsumber3) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
            nsumber2_su         = $nsumber2,
            nsumber3_su         = $nsumber3,
            nsumber1_ubah       = (select abs(sum(total_ubah)-$nsumber2-$nsumber3) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
            nsumber2_ubah       = $nsumber2,
            nsumber3_ubah       = $nsumber3,
            nilai               = (select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
            nilai_sempurna      = (select sum(total_sempurna) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
            nilai_ubah          = (select sum(total_ubah) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
            username            ='$id',last_update=getdate() where no_trdrka='$no_trdrka' ");          

        /*$query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka_n1 where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka_n1 where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka_n1 where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)");  

        echo $query1 = $this->db->query("UPDATE trskpd set 
            total           = (select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            total_sempurna  = (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            total_ubah      = (select sum(nilai_ubah) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            username1       = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)"); 
        */
    }

    function thapus_rancang($skpd='',$kegiatan='',$rek='') {
        $data=$this->anggaran_murni_model_n1->thapus_rancang($skpd,$kegiatan,$rek);
        $this->select_rka_rancang($kegiatan);
    }

    function cek_transaksi(){
        $skpd     = $this->input->post('skpd');
        $kegiatan = $this->input->post('kegiatan');
        $rek      = $this->input->post('rek6');
        $data     =$this->anggaran_murni_model_n1->cek_transaksi($skpd,$kegiatan,$rek);
        echo $data;
    }

    function load_sum_rek_rinci_rancang(){
        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek = $this->input->post('rek');
        $result=$this->anggaran_murni_model_n1->load_sum_rek_rinci_rancang($kdskpd,$kegiatan,$rek);
        echo json_encode($result);   
    }

    function rka_rinci_rancang($skpd='',$kegiatan='',$rekening='',$idlokasi='') {
        $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;        
        $result = $this->anggaran_murni_model_n1->rka_rinci_rancang($skpd,$kegiatan,$rekening,$norka,$idlokasi);
        echo json_encode($result);
    }

    function tsimpan_rinci_jk_rancang(){
        $norka     = $this->input->post('no');
        $csql      = $this->input->post('sql');
        $cskpd     = $this->input->post('skpd');
        $kegiatan  = $this->input->post('giat'); 
        $rekening  = $this->input->post('rek');
        $id        = $this->session->userdata('pcNama');
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
        
        $result = $this->anggaran_murni_model_n1->tsimpan_rinci_jk_rancang($norka,$csql,$cskpd,$kegiatan,$rekening,$id,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4);               
        echo $result;
    }

    function load_det_keg_rancang(){
        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $result=$this->anggaran_murni_model_n1->load_det_keg_rancang($kdskpd,$kegiatan);
        echo json_encode($result);   
    }

    function simpan_det_keg_rancang(){
        
        $skpd=$this->input->post('skpd');
        $giat=$this->input->post('giat');
        $lokasi=$this->input->post('lokasi');      
        $keterangan=$this->input->post('keterangan');      
        $waktu_giat=$this->input->post('waktu_giat');      
        $waktu_giat2=$this->input->post('waktu_giat2');
        $sub_keluaran=$this->input->post('sub_keluaran');      
        $sas_prog  =$this->input->post('sas_prog'); 
        $cap_prog  =$this->input->post('cap_prog'); 
        $tu_capai  =$this->input->post('tu_capai'); 
        $tk_capai  =$this->input->post('tk_capai'); 
        $tu_capai_p=$this->input->post('tu_capai_p'); 
        $tk_capai_p=$this->input->post('tk_capai_p');
        $tu_mas =$this->input->post('tu_mas'); 
        $tk_mas =$this->input->post('tk_mas'); 
        $tu_mas_p =$this->input->post('tu_mas_p'); 
        $tk_mas_p =$this->input->post('tk_mas_p'); 
        $tu_kel =$this->input->post('tu_kel'); 
        $tk_kel =$this->input->post('tk_kel'); 
        $tu_kel_p =$this->input->post('tu_kel_p'); 
        $tk_kel_p =$this->input->post('tk_kel_p'); 
        $tu_has =$this->input->post('tu_has'); 
        $tk_has =$this->input->post('tk_has'); 
        $tu_has_p =$this->input->post('tu_has_p'); 
        $tk_has_p =$this->input->post('tk_has_p'); 
        $kel_sa =$this->input->post('kel_sa'); 
        $ttd=$this->input->post('ttd');      
        $ang_lalu=$this->input->post('lalu');    

        $data=$this->anggaran_murni_model_n1->simpan_det_keg_rancang($ttd, $ang_lalu, $skpd,$giat,$lokasi,$keterangan,$waktu_giat,$waktu_giat2,$sub_keluaran,$sas_prog,  
        $cap_prog,$tu_capai,$tk_capai,$tu_capai_p,$tk_capai_p,$tu_mas,$tk_mas,$tu_mas_p, 
        $tk_mas_p,$tu_kel,$tk_kel,$tu_kel_p,$tk_kel_p,$tu_has,$tk_has,$tu_has_p,$tk_has_p,$kel_sa,$ttd ,$ang_lalu,$ttd); 

        echo $data;
    }

    function update_rincian_dpo(){

        $id             = $this->session->userdata('pcNama');
        $lokasi         = $this->input->post('lokasi');
        $kdbarang_edit  = $this->input->post('kdbarang_edit');
        $header         = $this->input->post('header');
        $kode           = $this->input->post('kode');
        $kd_kegiatan    = $this->input->post('kd_kegiatan');      
        $kd_rek5        = $this->input->post('kd_rek5');      
        $no_po          = $this->input->post('no_po');      
        $no_trdrka      = $this->input->post('no_trdrka');      
        $uraian         = $this->input->post('uraian');        
        $volume         = $this->input->post('volume1');       
        $satuan         = $this->input->post('satuan1');      
        $harga          = $this->input->post('harga1');      
        $total          = $this->input->post('total'); 
        $unik           = $this->input->post('unik');
        $sdana1           = $this->input->post('sdana1');  

        $sql="UPDATE trdpo_n1 set
            kd_lokasi       ='$lokasi',
            kd_barang       ='$kdbarang_edit',
            header          ='$header',
            kode            ='$kode',
            uraian          ='$uraian',
            volume1         ='$volume',
            volume_sempurna1='$volume',
            volume_ubah1    ='$volume',
            satuan1         ='$satuan',
            satuan_sempurna1='$satuan',
            satuan_ubah1    ='$satuan',
            harga1          ='$harga',
            harga_sempurna1 ='$harga',
            harga_ubah1     ='$harga',
            total           ='$total',
            total_sempurna  ='$total',
            total_ubah      ='$total'
            where unik='$unik' and no_trdrka='$no_trdrka'";
        
         $this->db->query($sql);
        $query1 = $this->db->query("
            UPDATE trdrka set
            nilai          = (select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nilai_sempurna = (select sum(total_sempurna) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nilai_ubah     = (select sum(total_ubah) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nilai_sumber   = (select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nsumber1_su    = (select sum(total_sempurna) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nsumber1_ubah  = (select sum(total_ubah) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            username       = '$id',last_update=getdate() where no_trdrka='$no_trdrka' ");  

        /*$query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)");  
           
            $query1 = $this->db->query("UPDATE trskpd set 
            total         = (select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_sempurna= (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_ubah    = (select sum(nilai_ubah) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            username1     = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ");    
        */
    }

    function load_nilai_kua_rancang($cskpd=''){    
        $result=$this->anggaran_murni_model_n1->load_nilai_kua_rancang($cskpd);
        echo $result;
    }


    function save_lokasi(){
        $lokasi  = $this->input->post('lokasi');
        $skpd  = $this->input->post('skpd');
        $subkeluar  = $this->input->post('subkeluar');
        if($lokasi==''){
            echo 0;
            die();
        }
        $cek="SELECT count(*) dd from ms_lokasi where nm_lokasi='$lokasi' and subkeluar='$subkeluar' and kd_skpd='$skpd'";
        $exe=$this->db->query($cek)->row();
        if($exe->dd<1){
            $gas=$this->db->query("INSERT INTO ms_lokasi (nm_lokasi,kd_skpd,subkeluar) values ('$lokasi','$skpd','$subkeluar')");
            echo 1;
        }else{
            echo 2;
        }

    }

    function load_sum_rek_rancang(){

        $kdskpd = $this->input->post('skpd');
        $sub_kegiatan = $this->input->post('keg');
        $result=$this->anggaran_murni_model_n1->load_sum_rek_rancang($kdskpd,$sub_kegiatan);
        echo json_encode($result);   
    }

    function thapus_rinci_ar_all_rancang(){ 
        $norka = $this->input->post('vnorka');
        $rek   = $this->input->post('rek');
        $skpd  = $this->input->post('skpd');
        $giat  = $this->input->post('giat');
        $data  = $this->anggaran_murni_model_n1->thapus_rinci_ar_all_rancang($norka,$rek,$skpd,$giat);        
        echo $data;
    }

    function hapus_rincian_dpo(){
        $id         = $this->session->userdata('pcNama');        
        $kode_unik  = $this->input->post('kode_unik');
        $skpd       = $this->input->post('skpd');
        $kd_kegiatan= $this->input->post('giat');
        $norka      = $this->input->post('norka');

        $cek=$this->db->query("SELECT count(no_trdrka) cek from trdpo_n1 where id='$kode_unik' AND kode_sbl<>''")->row()->cek;
        if($cek>0){
            echo "7";
            die();
        }
        $sql="DELETE trdpo_n1 where id='$kode_unik'";
        $this->db->query($sql);
        $hapuskosong=$this->db->query("SELECT count(no_trdrka) oke from trdpo_n1 WHERE no_trdrka='$norka'")->row();
        
        $query1 = $this->db->query("
            UPDATE trdrka_n1 set
            nilai           = (select isnull(sum(total),0) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nilai_ubah      = (select isnull(sum(total_ubah),0) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nilai_sempurna  = (select isnull(sum(total_sempurna),0) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nilai_sumber    = (select isnull(sum(total),0) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka),
            nsumber1_ubah   = (select isnull(sum(total_ubah),0) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nsumber1_su     = (select isnull(sum(total_sempurna),0) as nl from trdpo_n1 where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            username        ='$id',last_update=getdate() where no_trdrka='$norka' ");  
        /*
        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)");  
    
        echo $query1 = $this->db->query("
            UPDATE trskpd set
            total           = (select isnull(sum(nilai),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)), 
            total_ubah      = (select isnull(sum(nilai_ubah),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)),
            total_sempurna  = (select isnull(sum(nilai_sempurna),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)),
            username1       = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22) ");    
        */
    }

	function thapus_ro($skpd='',$kegiatan='',$rek='') {
        
        $notrdrka=$skpd.'.'.$kegiatan.'.'.$rek;
        $query = $this->db->query(" DELETE from trdrka_n1 where kd_skpd='$skpd' and kd_kegiatan='$kegiatan' and kd_rek5='$rek' ");
        $query = $this->db->query(" DELETE from trdpo_n1 where no_trdrka='$notrdrka' ");
		$query = $this->db->query(" DELETE from trdskpd_ro_n1 where kd_kegiatan='$kegiatan' and kd_rek5='$rek'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='1'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='1'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='1')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='1'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='2'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='2'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='2')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='2'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='3'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='3'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='3')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='3'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='4'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='4'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='4')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='4'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='5'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='5'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='5')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='5'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='6'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='6'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='6')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='6'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='7'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='7'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='7')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='7'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='8'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='8'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='8')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='8'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='9'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='9'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='9')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='9'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='10'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='10'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='10')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='10'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='11'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='11'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='11')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='11'");
		$query = $this->db->query(" UPDATE trdskpd_n1 set nilai=( select sum(nilai) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='12'),
									nilai_sempurna=( select sum(nilai_sempurna) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='12'),
									nilai_ubah=( select sum(nilai_ubah) as jum from trdskpd_ro_n1
									where kd_kegiatan='$kegiatan' and bulan ='12')
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' and bulan ='12'");									
        $query = $this->db->query(" UPDATE trskpd_n1 set total=( select sum(nilai) as jum from trdrka_n1
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ),
									total_sempurna=( select sum(nilai_sempurna) as jum from trdrka_n1
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ),
									total_ubah=( select sum(nilai_ubah) as jum from trdrka_n1
									where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' )
									where kd_kegiatan1='$kegiatan' and kd_skpd='$skpd' ");   
        $this->select_rka($kegiatan);
    }

  function tsimpan_ar(){
        
        $id     =  $this->session->userdata('pcNama'); 
        $kdskpd = $this->input->post('kd_skpd');
        $kdkegi = $this->input->post('kd_kegiatan');
        $kdrek  = $this->input->post('kd_rek5');
        $nilai  = $this->input->post('nilai');
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
                
        $nmskpd = $this->rka_model->get_nama($kdskpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmkegi = $this->rka_model->get_nama($kdkegi,'nm_kegiatan','trskpd','kd_kegiatan');
        $nmrek  = $this->rka_model->get_nama($kdrek,'nm_rek5','ms_rek5','kd_rek5');
        
        $notrdrka  = $kdskpd.'.'.$kdkegi.'.'.$kdrek ;
        $query_del = $this->db->query("DELETE from trdrka_n1 where left(kd_skpd,7)=left('$kdskpd',7) and kd_kegiatan='$kdkegi' and kd_rek5='$kdrek' ");
        $query_ins = $this->db->query("INSERT into trdrka_n1 (no_trdrka,kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,nilai_sempurna,nilai_ubah,
                                        sumber,sumber2,sumber3,sumber4,nilai_sumber,nilai_sumber2,nilai_sumber3,nilai_sumber4,
                                       sumber1_su,sumber2_su,sumber3_su,sumber4_su,nsumber1_su,nsumber2_su,nsumber3_su,nsumber4_su,     
                                       sumber1_ubah,sumber2_ubah,sumber3_ubah,sumber4_ubah,nsumber1_ubah,nsumber2_ubah,nsumber3_ubah,nsumber4_ubah,nilai_akhir_sempurna,username,last_update
                                       ,sumber1_su2,sumber2_su2,sumber3_su2,sumber4_su2,nsumber1_su2,nsumber2_su2,nsumber3_su2,nsumber4_su2) 
                                        values('$notrdrka','$kdskpd','$nmskpd','$kdkegi','$nmkegi','$kdrek','$nmrek','$nilai','$nilai','$nilai','$sdana1','$sdana2','$sdana3'
                                        ,'$sdana4','$ndana1',$ndana2,$ndana3,$ndana4,'$sdana1','$sdana2','$sdana3','$sdana4',$ndana1,$ndana2,$ndana3,$ndana4,       
                                       '$sdana1','$sdana2','$sdana3','$sdana4',$ndana1,$ndana2,$ndana3,$ndana4,'$nilai','$id',getdate(),'$sdana1','$sdana2','$sdana3','$sdana4',$ndana1,$ndana2,$ndana3,$ndana4)");        

        
        if ( $query_ins > 0 and $query_del > 0 ) {
            echo "1" ;
        } else {
            echo "0" ;
        }
        
    }
    function simpan_det_keg(){
        
        $skpd=$this->input->post('skpd');
        $giat=$this->input->post('giat');
        $lokasi=$this->input->post('lokasi');      
        $sasaran=$this->input->post('sasaran');      
        $wkeg=$this->input->post('wkeg');      
        $cp_tu=$this->input->post('cp_tu');      
        $cp_ck=$this->input->post('cp_ck');      
        $m_tu=$this->input->post('m_tu');      
        $m_ck=$this->input->post('m_ck');      
        $k_tu=$this->input->post('k_tu');      
        $k_ck=$this->input->post('k_ck');      
        $h_tu=$this->input->post('h_tu');      
        $h_ck=$this->input->post('h_ck');      
        $ttd=$this->input->post('ttd');      
        $ang_lalu=$this->input->post('lalu');
        $ang_depan=$this->input->post('depan');

        
        $this->db->query(" UPDATE trskpd_n1 set tu_capai='$cp_tu', 
                                             tu_mas='$m_tu',            
                                             tu_kel='$k_tu',            
                                             tu_has='$h_tu',
                                             tk_capai='$cp_ck',
                                             tk_mas='$m_ck',
                                             tk_kel='$k_ck',
                                             tk_has='$h_ck',
                                             tu_capai_sempurna='$cp_tu', 
                                             tu_mas_sempurna='$m_tu',           
                                             tu_kel_sempurna='$k_tu',           
                                             tu_has_sempurna='$h_tu',
                                             tk_capai_sempurna='$cp_ck',
                                             tk_mas_sempurna='$m_ck',
                                             tk_kel_sempurna='$k_ck',
                                             tk_has_sempurna='$h_ck',
                                             tu_capai_ubah='$cp_tu', 
                                             tu_mas_ubah='$m_tu',           
                                             tu_kel_ubah='$k_tu',           
                                             tu_has_ubah='$h_tu',
                                             tk_capai_ubah='$cp_ck',
                                             tk_mas_ubah='$m_ck',
                                             tk_kel_ubah='$k_ck',
                                             tk_has_ubah='$h_ck',
                                             lokasi='$lokasi',
                                             sasaran_giat='$sasaran',
                                             waktu_giat='$wkeg',
                                             kd_pptk='$ttd',
                                             ang_depan='$ang_depan',
                                             ang_lalu='$ang_lalu'
        where kd_kegiatan='$giat'  "); 
    }

function rka_rinci($skpd='',$kegiatan='',$rekening='') {
        
        $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
        $sql    = "SELECT * from trdpo_n1 where no_trdrka='$norka' order by no_po";  

        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;

        foreach($query1->result_array() as $resulte)
        { 
			
	   $jm=0; 
       $sql = "SELECT sum(c.jm_sb) jm_sb,sum(c.jm_sh) jm_sh from(
        select count(*)jm_sb,0 jm_sh from mssisb where kode_rek_rinci not in 
        (select kode_rek_rinci from mssish) and left(kode_rek_rinci,7)='$rekening'
        UNION ALL
        select 0 jm_sb,count(*)jm_sh from mssish where kode_rek_rinci not in 
        (select kode_rek_rinci from mssisb) and left(kode_rek_rinci,7)='$rekening')c";       

        
        $query1 = $this->db->query($sql)->row();
        $sb = $query1->jm_sb;
        $sh = $query1->jm_sh;
        
        if($sh>0){
            $jm=1;
        }else{
            if($sb>0){
                $jm=2;
            }else{
                $jm=0;
            }
        }
			
            $result[] = array(
                        'id'      => $ii,   
                        'header'  => $resulte['header'],  
                        'kode'    => $resulte['kode'],  
                        'no_po'   => $resulte['no_po'],  
                        'uraian'  => $resulte['uraian'],  
                        'volume1' => $resulte['volume1'],  
                        'volume2' => $resulte['volume2'],  
                        'volume3' => $resulte['volume3'],  
                        'satuan1' => $resulte['satuan1'],  
                        'satuan2' => $resulte['satuan2'],  
                        'satuan3' => $resulte['satuan3'],
                        'volume'  => $resulte['tvolume'],  
                        'harga1'  => number_format($resulte['harga1'],"2",".",","),  
                        'hargap'  => number_format($resulte['harga1'],"2",".",","),                             
                        'harga2'  => number_format($resulte['harga2'],"2",".",","),                             
                        'harga3'  => number_format($resulte['harga3'],"2",".",","),
                        'totalp'  => number_format($resulte['total'],"2",".",",") ,                            
                        'total'   => number_format($resulte['total'],"2",".",","),
                        'volume_sempurna1' => $resulte['volume_sempurna1'],
                        'volume_sempurna2' => $resulte['volume_sempurna2'],
                        'tvolume_sempurna' => $resulte['tvolume_sempurna'],                            
                        'satuan_sempurna1' => $resulte['satuan_sempurna1'],
                        'satuan_sempurna2' => $resulte['satuan_sempurna2'],
                        'harga_sempurna1'  => number_format($resulte['harga_sempurna1'],"2",".",","),
                        'harga_sempurna2'  => number_format($resulte['harga_sempurna2'],"2",".",","),
                        'total_sempurna'  => number_format($resulte['total_sempurna'],"2",".",","),
                        'total_sempurna_2'  => number_format($resulte['total_sempurna_2'],"2",".",","),
                        'volume_ubah1' => $resulte['volume_ubah1'],
                        'tvolume_ubah' => $resulte['tvolume_ubah'],                            
                        'satuan_ubah1' => $resulte['satuan_ubah1'],
                        'harga_ubah1'  => number_format($resulte['harga_ubah1'],"2",".",","),
                        'total_ubah'  => number_format($resulte['total_ubah'],"2",".",","),
						'init_max' => $jm
                        );
                        $ii++;
        }
           
           echo json_encode($result);
    }

    function tsimpan_rinci_jk(){
        
        $norka     = $this->input->post('no');
        $csql      = $this->input->post('sql');
        $cskpd     = $this->input->post('cskpd');
        $kegiatan  = $this->input->post('giat'); 
        $id        =  $this->session->userdata('pcNama');                       
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
        $crek = $this->input->post('crek');
            
    
        $sql       = "delete from trdpo_n1 where no_trdrka='$norka'";
        $asg       = $this->db->query($sql);
            
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT into trdpo_n1 (kd_kegiatan,kd_rek5,no_po,header,kode,no_trdrka,uraian,volume1,satuan1,harga1,total,volume_ubah1,satuan_ubah1,harga_ubah1,
                            total_ubah,volume2,satuan2,volume_ubah2,satuan_ubah2,volume3,satuan3,volume_ubah3,satuan_ubah3,tvolume,tvolume_ubah,
                            volume_sempurna1,volume_sempurna2,volume_sempurna3,tvolume_sempurna,satuan_sempurna1,satuan_sempurna2,satuan_sempurna3,
                            harga_sempurna1,total_sempurna,harga_sempurna2,total_sempurna_2)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
      
        $query1 = $this->db->query(" UPDATE trdrka_n1 set nilai= (select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
                                     nilai_sempurna= (select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
                                     nilaisempurna2= (select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),
                                     nilai_ubah=(select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka), 
                                     nilai_akhir_sempurna=(select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka)
                                     ,username='$id',last_update=getdate(),
                                     sumber='$sdana1',sumber2='$sdana2',sumber3='$sdana3',sumber4='$sdana4',nilai_sumber='$ndana1',
                                     nilai_sumber2=$ndana2,nilai_sumber3=$ndana3,nilai_sumber4=$ndana4,     
                                     sumber1_su='$sdana1',sumber2_su='$sdana2',sumber3_su='$sdana3',sumber4_su='$sdana4',nsumber1_su=$ndana1,
                                     nsumber2_su=$ndana2,nsumber3_su=$ndana3,nsumber4_su=$ndana4,       
                                     sumber1_ubah='$sdana1',sumber2_ubah='$sdana2',sumber3_ubah='$sdana3',sumber4_ubah='$sdana4',nsumber1_ubah=$ndana1,
                                     nsumber2_ubah=$ndana2,nsumber3_ubah=$ndana3,nsumber4_ubah=$ndana4
                                     where no_trdrka='$norka' ");  

        $query1 = $this->db->query(" UPDATE trskpd_n1 set total= (select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ),
                                     total_sempurna= (select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ),
                                     total_sempurna_2= (select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ),
                                     total_sempurna_final= (select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                     total_ubah= (select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                     username='$id',last_update=getdate()
                                     where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd'");  

        $this->rka_rinci($cskpd,$kegiatan,$crek);
    
    }
 function tsimpan_rinci(){

        $skpd    = $this->input->post('skpd');
        $kegiatan    = $this->input->post('giat');
        $rekening    = $this->input->post('rek');
        $index    = $this->input->post('id');
        $uraian    = $this->input->post('uraian');
        $volume1    = $this->input->post('volum1');
        $satuan1    = $this->input->post('satuan1');
        $harga1    = $this->input->post('harga1');
        $volume2    = $this->input->post('volum2');
        $satuan2    = $this->input->post('satuan2');
        $volume3    = $this->input->post('volum3');
        $satuan3    = $this->input->post('satuan3');
        
        $satuan1 = str_replace("12345678987654321","",$satuan1);
        $satuan1 = str_replace("undefined","",$satuan1);

        $satuan2 = str_replace("12345678987654321","",$satuan2);
        $satuan2 = str_replace("undefined","",$satuan2);

        $satuan3 = str_replace("12345678987654321","",$satuan3);
        $satuan3 = str_replace("undefined","",$satuan3);

        $uraian = str_replace("%20"," ",$uraian);
        $uraian = str_replace("%60"," ",$uraian);

        $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
        $vol1=$volume1;
        $vol2=$volume2;
        $vol3=$volume3;
        if($volume1==0){$volume1=1;$vol1='';}
        if($volume2==0){$volume2=1;$vol2='';}
        if($volume3==0){$volume3=1;$vol3='';}
        
        $total   = $volume1*$volume2*$volume3*$harga1;

        $query1 = $this->db->query(" delete from trdpo_n1 where no_po='$index' and no_trdrka='$norka' ");  
        $query1 = $this->db->query(" INSERT into trdpo_n1(no_po,no_trdrka,uraian,volume1,satuan1,harga1,total,volume_ubah1,satuan_ubah1,harga_ubah1,total_ubah,volume2,satuan2,volume_ubah2,satuan_ubah2,volume3,satuan3,volume_ubah3,satuan_ubah3) 
                                     values('$index','$norka','$uraian','$vol1','$satuan1',$harga1,$total,'$vol1','$satuan1',$harga1,$total,'$vol2','$satuan2','$vol2','$satuan2','$vol3','$satuan3','$vol3','$satuan3') ");  
        $query1 = $this->db->query(" UPDATE trdrka_n1 set nilai= (select sum(total) as nl from trdpo_n1 where no_trdrka=trdrka_n1.no_trdrka),nilai_ubah=(select sum(total) as nl from trdpo where no_trdrka=trdrka_n1.no_trdrka) where no_trdrka='$norka' ");  
        $query1 = $this->db->query(" UPDATE trskpd set total= (select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ) where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ");   
        $this->rka_rinci($skpd,$kegiatan,$rekening);
    }
       
	 function thapus_rinci_ar_all_ro(){
        $norka = $this->input->post('vnorka');
        $kegi = $this->input->post('kegi');
        $kdskpd = $this->input->post('kdskpd');

        
        $query = $this->db->query("DELETE from trdpo_n1 where no_trdrka='$norka'");
        $query1 = $this->db->query(" UPDATE trdrka_n1 set nilai=0,nilai_sempurna=0,nilai_ubah=0,nilai_akhir_sempurna=0 
                                     ,nilai_sumber=0,nilai_sumber2=0,nilai_sumber3=0,nilai_sumber4=0, 
                                     nsumber1_su=0,nsumber2_su=0,nsumber3_su=0,nsumber4_su=0,nsumber1_ubah=0,nsumber2_ubah=0,nsumber3_ubah=0,nsumber4_ubah=0
                                     where no_trdrka='$norka' "); 
        $query1 = $this->db->query("UPDATE trskpd_n1 set total=(select isnull(sum(nilai),0) from trdrka_n1 where kd_kegiatan='$kegi' and kd_skpd='$kdskpd')
                                    ,total_sempurna=(select isnull(sum(nilai),0) from trdrka_n1 where kd_kegiatan='$kegi' and kd_skpd='$kdskpd'),
                                    total_ubah=(select isnull(sum(nilai),0) from trdrka_n1 where kd_kegiatan='$kegi' and kd_skpd='$kdskpd') 
                                    where kd_kegiatan='$kegi' and kd_skpd='$kdskpd'"); 

 
        if ( $query > 0 ){
            echo '1' ;
        } else {
            echo '0' ;
        }
    }
	
function load_sum_rek(){

        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $skp = substr($kdskpd,0,7);
        $query1 = $this->db->query(" select sum(nilai) as rektotal,sum(nilai_sempurna) as rektotal_sempurna,sum(nilai_ubah) as rektotal_ubah from 
                                     trdrka_n1 where left(kd_skpd,7)='$skp' and left(kd_rek5,3) not in ('514','515') and kd_kegiatan='$kegiatan'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['rektotal'],"2",".",","),  
                        'rektotal_sempurna' => number_format($resulte['rektotal_sempurna'],"2",".",","),
                        'rektotal_ubah' => number_format($resulte['rektotal_ubah'],"2",".",",")  
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   
    }
    function load_sum_rek_rinci(){

        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek = $this->input->post('rek');
        $norka=$kdskpd.'.'.$kegiatan.'.'.$rek;

        $query1 = $this->db->query(" select sum(total) as rektotal_rinci,sum(total_ubah) as rektotal_rinci_ubah from trdpo_n1 where no_trdrka='$norka' ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal_rinci' => number_format($resulte['rektotal_rinci'],"2",".",","),  
                        'rektotal_rinci_ubah' => number_format($resulte['rektotal_rinci_ubah'],"2",".",",")  
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   
    }
function load_sum_rek_rinci_rka(){

        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek = $this->input->post('rek');
        $norka=$kdskpd.'.'.$kegiatan.'.'.$rek;

        $query1 = $this->db->query("SELECT nilai, nilai_sempurna, nilai_ubah FROM trdrka_n1 where no_trdrka='$norka' ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal_rka' => number_format($resulte['nilai'],"2",".",","),
                        'rektotal_rka_sempurna' => number_format($resulte['nilai_sempurna'],"2",".",","),
                        'rektotal_rka_ubah' => number_format($resulte['nilai_ubah'],"2",".",","),
                        
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
           
         }

function load_nilai_kua($cskpd=''){
                
        $query1 = $this->db->query("SELECT a.nilai_kua, (SELECT SUM(nilai) FROM trdrka_n1 WHERE LEFT(kd_rek5,2) IN ('52') 
        AND left(kd_skpd,7) = left(a.kd_skpd,7)) as nilai_ang,(SELECT SUM(nilai_sempurna) FROM trdrka_n1 WHERE LEFT(kd_rek5,2) IN ('52') 
        AND left(kd_skpd,7) = left(a.kd_skpd,7)) [nilai_angg_sempurna],(SELECT SUM(nilai_ubah) FROM trdrka_n1 WHERE LEFT(kd_rek5,2) IN ('52') 
        AND left(kd_skpd,7) = left(a.kd_skpd,7)) [nilai_angg_ubah] FROM ms_skpd a where a.kd_skpd='$cskpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'nilai' => number_format($resulte['nilai_kua'],2,'.',','),                      
                        'kua_terpakai' => number_format($resulte['nilai_ang'],2,'.',','),
                        'kua_terpakai_sempurna' => number_format($resulte['nilai_angg_sempurna'],2,'.',','),                       
                        'kua_terpakai_ubah' => number_format($resulte['nilai_angg_ubah'],2,'.',',')  
                        );
                        $ii++;
        }
       
           echo json_encode($result);
           $query1->free_result();  
    }
     function cek_kas(){
        
        $skpd     = $this->input->post('skpd');
        $kegiatan = $this->input->post('kegiatan');
        
        $result   = array();
        $query    = $this->db->query("select * from trdskpd_n1 where left(kd_skpd,7)=left('$skpd',7) and kd_kegiatan='$kegiatan'");
        //$query    = $this->db->query("select * from trdskpd where kd_skpd='1.03.01.00' and kd_kegiatan='1.03.1.03.01.00.18.04' ");
        $ii       = 0;
        
        foreach ( $query->result_array() as $row ){
            
            $result[] = array(
                'id'    =>  '$ii',
                'bulan' =>  $row['bulan'],
                'nilai' =>  $row['nilai']
            );
            $ii++;
        }
        echo json_encode($result);
    }
   function pgiat($cskpd='') {
        $vvv="tambah_rka";
        //header("Refresh: 1; URL=$vvv");
        
       $where_baru = "";
         //$where_baru = "";

        $cskpdd = substr($cskpd,0,7);
        $lccr = $this->input->post('q');
        $sql  = " SELECT a.kd_skpd,a.kd_kegiatan,b.nm_kegiatan,a.jns_kegiatan,status_keg FROM trskpd_n1 a INNER JOIN m_giat b ON a.kd_kegiatan=b.kd_kegiatan
                 where left(a.kd_skpd,7) ='$cskpdd' and ( upper(a.kd_kegiatan) like upper('%$lccr%') or upper(a.nm_kegiatan) like upper('%$lccr%') ) $where_baru
                 order by a.kd_kegiatan";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd'  => $resulte['kd_skpd'],  
                        'kd_kegiatan'  => $resulte['kd_kegiatan'],  
                        'nm_kegiatan'  => $resulte['nm_kegiatan'],
                        'jns_kegiatan' => $resulte['jns_kegiatan'],
                        'status_keg'   => $resulte['status_keg']
                        );
                        $ii++;
        }
        echo json_encode($result);
           
    }   

   function tsimpan($skpd='',$kegiatan='',$rekbaru='',$reklama='',$nilai=0,$sdana='') {
       
        if (trim($reklama)==''){
            $reklama=$rekbaru;
        }

        $nmskpd=$this->rka_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmgiat=$this->rka_model->get_nama($kegiatan,'nm_kegiatan','trskpd_n1','kd_kegiatan');
        $nmrek5=$this->rka_model->get_nama($rekbaru,'nm_rek5','ms_rek5','kd_rek5');

        $notrdrka=$skpd.'.'.$kegiatan.'.'.$rekbaru;
        $query = $this->db->query(" delete from trdrka_n1 where kd_skpd='$skpd' and kd_kegiatan='$kegiatan' and kd_rek5='$reklama' ");
        $query = $this->db->query(" insert into trdrka_n1(no_trdrka,kd_skpd,kd_kegiatan,kd_rek5,nilai,nilai_ubah,sumber,nm_skpd,nm_rek5,nm_kegiatan) values('$notrdrka','$skpd','$kegiatan','$rekbaru',$nilai,$nilai,'$sdana','$nmskpd','$nmrek5','$nmgiat') ");   
        $query = $this->db->query(" update trskpd_n1 set total=( select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ),TK_MAS=( select sum(nilai) as jum from trdrka_n1 where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ),TU_MAS='Dana' where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ");    

        $this->select_rka($kegiatan);
    }
 function load_det_keg(){

        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');

        $query1 = $this->db->query(" select * from trskpd_n1 where kd_skpd='$kdskpd' and kd_kegiatan='$kegiatan'  ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'lokasi' => $resulte['lokasi'],  
                        'sasaran' => $resulte['sasaran_giat'],  
                        'wkeg' => $resulte['waktu_giat'],  
                        'ttd' => $resulte['kd_pptk'],
                        'cp_tu' => $resulte['tu_capai'],
                        'm_tu' => $resulte['tu_mas'],
                        'k_tu' => $resulte['tu_kel'],
                        'h_tu' => $resulte['tu_has'],
                        'cp_ck' => $resulte['tk_capai'],
                        'm_ck' => $resulte['tk_mas'],
                        'ang_lalu' => number_format(($resulte['ang_lalu']),"2",".",","),
                        'ang_depan' => number_format(($resulte['ang_depan']),"2",".",","),                    
                        'k_ck' => $resulte['tk_kel'],
                        'h_ck' => $resulte['tk_has']                        
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   

    }
   
}
    