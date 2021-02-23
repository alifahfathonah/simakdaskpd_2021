<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 
 */

class master_ttd extends CI_Model {

    function __construct()
    {
        parent::__construct(); 
    }
    
    function load_ttd_unit($skpd='',$lccr='') {          
        $sql = "SELECT * FROM ms_ttd WHERE left(kd_skpd,17)=left('$skpd',17) AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;        
        foreach($query1->result_array() as $resulte)
        { 
            
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],
                        'urut' => $resulte['id_ttd'],   
                        'nama' => $resulte['nama']      
                        );
                        $ii++;
        }                     
        return json_encode($result);      
    }

    function load_ttd_bud($cari=''){
        $sql = "SELECT * FROM ms_ttd WHERE kode='bud' and nama like '%$cari%'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte){     
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'urut' => $resulte['id_ttd'],                           
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        return json_encode($result);
           
    }


    function load_skpd_bp($lccr=''){    
        $sql = "SELECT kd_skpd,nm_skpd from ms_skpd where right(kd_skpd,4)='0000' and (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
           
        return json_encode($result);
    }

    function load_tanda_tangan($skpd,$lccr) {       
        
        if($skpd==''){
            $skpd=$this->session->userdata('kdskpd');
        }

        $sql = "SELECT * FROM ms_ttd WHERE (left(kd_skpd,22)= left('$skpd',22) AND kode in ('PA','KPA'))  AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;        
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan'],
                        'id_ttd' => $resulte['id_ttd']       
                        );
                        $ii++;
        }           
           
        return json_encode($result);
           
    }

    function load_bendahara_p($kdskpd,$cari=''){
    
        $query1 = $this->db->query("SELECT top 10 nip,nama,id_ttd from ms_ttd where left(kd_skpd,17)=left('$kdskpd',17) and nama like '%$cari%'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd']
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   
    }

    function load_tanda_tangan_bud($kdskpd,$cari=''){
    
        $query1 = $this->db->query("
                    SELECT * from (
        SELECT nip,nama,id_ttd, jabatan from ms_ttd where  left(kd_skpd,17)='4.01.0.00.0.00.01' and kode in ('KPA','PA')
        UNION ALL
        SELECT nip, nama, id_ttd, jabatan from ms_ttd where kode in ('BUD','PPKD','PA') 
        ) okei where nama like '%$cari%'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd'],
                        'jabatan' => $resulte['jabatan'],
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   
    }

    function load_ppk_pptk($kdskpd,$cari=''){
    
        $query1 = $this->db->query("select nip,nama,id_ttd from ms_ttd where kd_skpd='$kdskpd' AND kode='PPTK' and nama like '%$cari%'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd']
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   
    }


}