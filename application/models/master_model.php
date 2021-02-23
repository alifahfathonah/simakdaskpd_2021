<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */ 

class Master_model extends CI_Model {

    function __construct()
    {
        parent::__construct(); 
    }


   function get_all_susun(){
        $hasil = '';
        $sql = "select * from(
                    select a.kd_skpd,a.nm_skpd,sum(nilai) [nilai] from ms_skpd a join trdrka b on a.kd_skpd=b.kd_skpd group by a.kd_skpd,a.nm_skpd
                )as c where nilai>0 order by kd_skpd";
        $hasil = $this->db->query($sql);        
        return $hasil;                         
    }

    function get_all_susun_cari($lccari){
        $hasil = '';
        $sql = "select * from(
                    select a.kd_skpd,a.nm_skpd,sum(nilai) [nilai] from ms_skpd a join trdrka b on a.kd_skpd=b.kd_skpd group by a.kd_skpd,a.nm_skpd
                )as c where nilai>0 and kd_skpd like '$lccari%' order by kd_skpd";
        $hasil = $this->db->query($sql);        
        return $hasil;                         
    }
    function get_count_susun(){
        $hasil = '';
        $sql = "select * from(
                    select a.kd_skpd,a.nm_skpd,sum(nilai) [nilai] from ms_skpd a join trdrka b on a.kd_skpd=b.kd_skpd group by a.kd_skpd,a.nm_skpd
                )as c where nilai>0 order by kd_skpd";
        $hasil = $this->db->query($sql)->num_rows();      
        return $hasil;                             
    }

    function get_count_teang_susun($lccari){
        $hasil = '';
        $sql = "select * from(
                    select a.kd_skpd,a.nm_skpd,sum(nilai) [nilai] from ms_skpd a join trdrka b on a.kd_skpd=b.kd_skpd group by a.kd_skpd,a.nm_skpd
                )as c where nilai>0 and kd_skpd='$lccari%' order by kd_skpd";
        $hasil = $this->db->query($sql)->num_rows();      
        return $hasil;                             
    }
	
	// Tampilkan semua master data fungsi
	//function getAll($limit, $offset)
    function getAll($tabel,$field1,$limit, $offset)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->order_by($field1, 'asc');
		$this->db->limit($limit,$offset);
		return $this->db->get();
	}
	
	function getAll2($tabel,$field1,$limit, $offset)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where('status', '1');
		$this->db->order_by($field1, 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}
	
	function getAll_bidang($tabel,$field,$data,$field_older,$limit, $offset)
	{
		$this->db->select('a.kd_skpd,a.kunci,
			a.kunci_murni, a.kunci_angkas_m, a.kunci_geser, 
			a.kunci_angkas_g, a.kunci_ubah, a.kunci_angkas_u,
			b.nm_skpd,c.status_rancang,c.status_ubah');
		$this->db->from($tabel.' as a');
		$this->db->join('ms_skpd as b', 'a.kd_skpd = b.kd_skpd');
		$this->db->join('trhrka as c', 'b.kd_skpd = c.kd_skpd');
		$this->db->where('a.'.$field,$data);
		//$this->db->where('a.kunci','0');
		$this->db->group_by('a.kd_skpd,b.nm_skpd,a.kunci,c.status_rancang,c.status_ubah,
			a.kunci_murni, 
			a.kunci_angkas_m, 
			a.kunci_geser, 
			a.kunci_angkas_g, 
			a.kunci_ubah, 
			a.kunci_angkas_u');
		$this->db->order_by('a.'.$field_older, 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}
	
	function getuser($tabel,$field1,$limit, $offset)
	{
		$this->db->query("SELECT id_user,nama,kd_skpd, case when jenis=1 then 'Simakda' else 'Siadinda' end as jns FROM [user] ORDER BY id_user asc");
		$this->db->from($tabel);
		$this->db->order_by($field1, 'asc');
		$this->db->limit($limit,$offset);
		return $this->db->get();
	}
	
    function getcari($tabel,$field,$field1,$limit, $offset,$lccari)
	{
		$this->db->select('*');
		$this->db->from($tabel);
        $this->db->or_like($field, $lccari);  
        $this->db->or_like($field1, $lccari);      
		$this->db->order_by($field, 'asc');
        $this->db->limit($limit,$offset);
		return $this->db->get();
	}

	function getcariskpd($tabel,$field,$data,$field_older,$limit, $offset)
	{
		$this->db->select('kd_skpd');
		$this->db->from($tabel);
		$this->db->where($field,$data);
		$this->db->where('kunci','0');
		$this->db->order_by($field_older, 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}
 
    function getAllc($tabel,$field1)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->order_by($field1, 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}
	
	// Total jumlah data
	function get_count($tabel)
	{
		return $this->db->get($tabel)->num_rows();
	}
    
	function get_count_cari2($tabel,$field,$data,$field_older)
	{
		$this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($field, $data);
        $this->db->order_by($field_older, 'asc');
        return $this->db->get()->num_rows();
	}
	
	function get_count_cari($tabel,$field1,$field2,$data)
	{
        $this->db->select('*');
		$this->db->from($tabel);
        $this->db->or_like($field1, $data);  
        $this->db->or_like($field2, $data);      
		$this->db->order_by($field1, 'asc');
		return $this->db->get()->num_rows();
		//return $this->db->get('ms_fungsi')->num_rows();
	}
    function get_count_teang($tabel,$field,$field1,$lccari)
	{
        $this->db->select('*');
		$this->db->from($tabel);
        $this->db->or_like($field, $lccari);  
        $this->db->or_like($field1, $lccari);      
		$this->db->order_by($field, 'asc');
		return $this->db->get()->num_rows();
		//return $this->db->get('ms_fungsi')->num_rows();
	}
	// Ambil by ID
	function get_by_id($tabel,$field1,$id)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where($field1, $id);
		return $this->db->get();
	}
    
	function get_by_id_top1($tabel,$field1,$id)
	{
		$this->db->select('top 1 *');
		$this->db->from($tabel);
		$this->db->where($field1, $id);
		return $this->db->get();
	}
        
	//cari
    function cari($tabel,$field1,$field2,$limit, $offset,$data)
	{
		$this->db->select('*');
		$this->db->from($tabel);
        $this->db->or_like($field2, $data);  
        $this->db->or_like($field1, $data);      
		$this->db->order_by($field1, 'asc');
		return $this->db->get();
	}

	// Simpan data
	function save($tabel,$data)
	{
		$this->db->insert($tabel, $data);
	}
	
	// Update data
	function update($tabel,$field1,$id, $data)
	{
		$this->db->where($field1, $id);
		$this->db->update($tabel, $data); 	
	}
	
	// Hapus data
	function delete($tabel,$field1,$id)
	{
		$this->db->where($field1, $id);
		$this->db->delete($tabel);
	}
    
    function getSome($tabel,$field1,$field2,$x)
        {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($field2, $x);
        $this->db->order_by($field1, 'asc');
        return $this->db->get();
        }
  
    function skpduser($lccr='') {

        $id    = $this->session->userdata('kdskpd');
        $type  = $this->session->userdata('type');
        if($type=='1'){
            $sql = "SELECT kd_skpd, nm_skpd, jns FROM ms_skpd and kd_skpd not in (select kd_skpd from kegiatan_bp) and (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) order by kd_skpd ";
        }else{
        	$sort= substr($id,0,4)=='1.02' || substr($id,0,4)=='7.01' ? "left(kd_skpd,17)=left('$id',17)" : "kd_skpd='$id'";
            $sql = "SELECT left(kd_gabungan,22) kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=left(kd_gabungan,22)) nm_skpd,'' jns FROM trskpd where left(kd_gabungan,17)=left('$id',17) group by left(kd_gabungan,22)  ";            
        }
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],
                        'jns' => ''
                        );
                        $ii++;
        }
           
        return $result;
    }

    function skpduser_induk($lccr='') {

        $id    = $this->session->userdata('kdskpd');
        $type  = $this->session->userdata('type');

        $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where right(kd_skpd,4)='0000' and left(kd_skpd,17)=left('$id',17) and (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) order by kd_skpd ";

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],
                        'jns' => $resulte['jns']
                        );
                        $ii++;
        }
           
        return $result;
    }




}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */