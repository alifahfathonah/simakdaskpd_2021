 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * spm_model
 */ 

class spm_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function pot($kd_skpd='',$spm='') {
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
        return json_encode($result);
    }

    function rek_pot($lccr='') {
        $sql    = " SELECT top 20 kd_rek6,nm_rek6 FROM ms_rek6 where left(kd_rek6,1)='2' and left(nm_rek6,3)<>'Dst' and ( upper(kd_rek6) like upper('%$lccr%')
        OR upper(nm_rek6) like upper('%$lccr%') ) order by kd_rek6 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
         
            $result[] = array(
                'id' => $ii,        
                'kd_rek5' => $resulte['kd_rek6'],  
                'nm_rek5' => $resulte['nm_rek6'],  
                
            );
            $ii++;
        }
        
        return json_encode($result);      
    }  


}
