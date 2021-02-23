<?php  
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
class Service extends CI_Controller {
	
	
	function __contruct()
	{	
		parent::__construct();
	}
*/    
require APPPATH.'/libraries/REST_Controller.php';

class Service extends REST_Controller
{
	protected $builtInMethods;
	
	public function __construct()
	{
		parent::__construct();
		$this->__getMyMethods();		
	}
    
 
	function transfer_sirup() {
		$user=$this->uri->segment(3);
		$pass=$this->uri->segment(4);
		
        if($user=='reza' && $pass=='sirup'){
            $query1 = $this->transfer_model->transfer_sirup1();
      		$result = array();
		
            $ii = 0;        
            foreach($query1->result_array() as $resulte){ 
                         
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'kd_program' => $resulte['kd_program'],
                        'nm_program' => $resulte['nm_program'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'lokasi' => $resulte['lokasi'],
                        'waktu_kegiatan' => $resulte['waktu_kegiatan'],
                        'angg_penyusunan' => $resulte['angg_penyusunan'],
                        'angg_perubahan' => $resulte['angg_perubahan']
                        );
                        $ii++;
            }  
            echo json_encode($result);
            //echo $query1;
        }else{
            echo 'Access Denied';
        }    	   
	}

	
	function transfer_sirup2() {
		$user=$this->uri->segment(3);
		$pass=$this->uri->segment(4);
		$jpagu=$this->uri->segment(5);
		$jangg = '';
		
        if($user=='reza' && $pass=='sirup'){
           if($jpagu=='susun'){
				$jangg = 'nilai';
				$volume = 'volume1';
				$harga = 'harga1';
				$total = 'total';
			}else if ($jpagu=='ubah'){
				$jangg = 'nilai_ubah';
				$volume = 'volume_ubah1';
				$harga = 'harga_ubah1';
				$total = 'total_ubah';
			}else{
				echo 'Access Denied';
			}	
			
			if($jangg!=''){
				$query1 = $this->transfer_model->transfer_sirup2($jangg,$volume,$harga,$total);
				$result = array();
		
				$ii = 0;        
				foreach($query1->result_array() as $resulte){ 
                         
				$result[] = array(
                        'ID_SATKER' => $resulte['kd_skpd'],
                        'ID_PROGRAM' => $resulte['kd_program'],
                        'NAMA_PROGRAM' => $resulte['nm_program'],
                        'ID_KEGIATAN' => $resulte['kd_kegiatan'],
                        'NAMA_KEGIATAN' => $resulte['nm_kegiatan'],
						'ID_REKENING' => $resulte['kd_rek5'],
                        'NAMA_REKENING' => $resulte['nm_rek5'],
						'LOKASI_PEKERJAAN' => $resulte['lokasi'],
                        'WAKTU_KEGIATAN' => $resulte['waktu_kegiatan'],
                        'PAGU' => $resulte['angg'],
                        'URAIAN_RINCIAN' => $resulte['uraian'],
						'VOLUME' => $resulte['volume'],
						'HARGA' => $resulte['harga'],
						'PAGU_RINCIAN' => $resulte['total']
                        );
                        $ii++;
				}
			}	
            echo json_encode($result);
        }else{
            echo 'Access Denied';
        }    	   
	}


	
	function program_get() {
				    
        if(!isset($_GET['tahun'])){
          $tahun= "";
        }else{
          $tahun= $_GET['tahun'];  
        }
        
        if(!isset($_GET['key'])){
          $key= "";
        }else{
          $key= $_GET['key']; 
        }
        
        if(!isset($_GET['status'])){
          $jpagu= "";
        }else{
          $jpagu= $_GET['status']; 
        }
        
        if(!isset($_GET['satker'])){
          $satker= "";
        }else{
          $satker= $_GET['satker']; 
        }
        			        
        if($jpagu=='' || $tahun=='' || $satker==''){            
            echo 'Access Denied';                    
            exit();
        }
        
        if($key=='sirup'){
            if($jpagu=='susun'){
				$jangg = 'nilai';
			}else if ($jpagu=='ubah'){
				$jangg = 'nilai_ubah';
			}else{
				echo 'Access Denied';
			}	
			
			if($jangg!=''){
				$query1 = $this->transfer_model->program($jangg,$tahun,$satker);
                $result = array();
		
                if(!($query1)){
                    echo 'Data Not Available';                   
                    exit();
                 }                    
				
				$ii = 0;        
				foreach($query1->result_array() as $resulte){ 
                         
				$result[] = array(       
                        'ID_SATKER' => $resulte['kd_skpd'],                                                                
                        'ID_PROGRAM' => $resulte['kd_program'],
						'NAMA_PROGRAM' => $resulte['nm_program'],
                        'PAGU' => $resulte['angg'],
                        );
                        $ii++;
				}  
				echo json_encode($result);
                //$this->response($result, 200);            
            }
			
        }else{
            echo 'Access Denied';
        }    	   
	}
	
	function kegiatan_get() {
	    
        if(!isset($_GET['tahun'])){
          $tahun= "";
        }else{
          $tahun= $_GET['tahun'];  
        }
        
        if(!isset($_GET['key'])){
          $key= "";
        }else{
          $key= $_GET['key']; 
        }
        
        if(!isset($_GET['status'])){
          $jpagu= "";
        }else{
          $jpagu= $_GET['status']; 
        }
        
        if(!isset($_GET['satker'])){
          $satker= "";
        }else{
          $satker= $_GET['satker']; 
        }
        
        if($jpagu=='' || $tahun=='' || $satker==''){            
            echo 'Access Denied';                    
            exit();
        }
        
        if($key=='sirup'){
            if($jpagu=='susun'){
				$jangg = 'nilai';
			}else if ($jpagu=='ubah'){
				$jangg = 'nilai_ubah';
			}else{
				echo 'Access Denied';
			}	
            			
			if($jangg!=''){
				$query1 = $this->transfer_model->kegiatan($jangg,$tahun,$satker);
				$result = array();
		          
                if(!($query1)){
                    echo 'Data Not Available';                   
                    exit();
                 }
                   
				$ii = 0;        
				foreach($query1->result_array() as $resulte){ 
                         
				$result[] = array(       
                        'ID_SATKER' => $resulte['kd_skpd'], 			                        
                        'ID_PROGRAM' => $resulte['kd_program'],
						'ID_KEGIATAN' => $resulte['kd_kegiatan'],
						'NAMA_KEGIATAN' => $resulte['nm_kegiatan'],
                        'PAGU' => $resulte['angg'],
                        );
                        $ii++;
				}  
				echo json_encode($result);
            }
			//echo $query1;
        }else{
            echo 'Access Denied';
        }    	   
	}


    function pagu_get() {
	   
       if(!isset($_GET['tahun'])){
          $tahun= "";
        }else{
          $tahun= $_GET['tahun'];  
        }
        
        if(!isset($_GET['key'])){
          $key= "";
        }else{
          $key= $_GET['key']; 
        }
        
        if(!isset($_GET['status'])){
          $jpagu= "";
        }else{
          $jpagu= $_GET['status']; 
        }
        
        if(!isset($_GET['satker'])){
          $satker= "";
        }else{
          $satker= $_GET['satker']; 
        }
        
        if($jpagu=='' || $tahun=='' || $satker==''){            
            echo 'Access Denied';                    
            exit();
        }
        
        if($key=='sirup'){
            if($jpagu=='susun'){
				$jangg = 'nilai';
			}else if ($jpagu=='ubah'){
				$jangg = 'nilai_ubah';
			}else{
				echo 'Access Denied';
			}	
            			
			if($jangg!=''){
				$query1 = $this->transfer_model->pagu($jangg,$tahun,$satker);
				$result = array();
		          
                if(!($query1)){
                    echo 'Data Not Available';                    
                    exit();
                 }
                   
				$ii = 0;        
				foreach($query1->result_array() as $resulte){ 
                         
				$result[] = array(       
                        'ID_SATKER' => $resulte['kd_skpd'], 	
                        'NAMA_SATKER' => $resulte['nm_skpd'],		                        
                        'ID_PROGRAM' => $resulte['kd_program'],
                        'NAMA_PROGRAM' => $resulte['nm_program'],
						'ID_KEGIATAN' => $resulte['kd_kegiatan'],
						'NAMA_KEGIATAN' => $resulte['nm_kegiatan'],
                        'URAIAN' => $resulte['uraian'],
                        'JENIS' => $resulte['jenis'],
                        'PAGU' => $resulte['angg'],
                        'TA' => $tahun
                        );
                        $ii++;
				}  
				echo json_encode($result);
            }
			//echo $query1;
        }else{
            echo 'Access Denied';
        }    	   
	}

    function realisasi_get() {      
	   $jangg='';
       if(!isset($_GET['tahun'])){
          $tahun= "";
        }else{
          $tahun= $_GET['tahun'];  
        }
        
        if(!isset($_GET['key'])){
          $key= "";
        }else{
          $key= $_GET['key']; 
        }
        
        if(!isset($_GET['bulan'])){
          $bulan= "";
        }else{
          $bulan= $_GET['bulan']; 
        }
        
        if(!isset($_GET['satker'])){
          $satker= "";
        }else{
          $satker= $_GET['satker']; 
        }
       
       if($bulan=='' || $tahun=='' || $satker==''){            
            echo 'Access Denied';                    
            exit();
       }
       	
        
        if($key=='sirup'){
            if($bulan==1 || $bulan==2 || $bulan==3 || $bulan==4 || $bulan==5 || $bulan==6 || $bulan==7 || $bulan==8 || $bulan==9 || $bulan==10 || $bulan==11 || $bulan==12){
				$jangg = $bulan;
			}else{
				echo 'Data Not Available';
			}	
            			
			if($jangg!=''){
				$query1 = $this->transfer_model->realisasi($jangg,$tahun,$satker);
				$result = array();
		          
                if(!($query1)){
                    echo 'Data Not Available';                    
                    exit();
                 }
                   
				$ii = 0;        
				foreach($query1->result_array() as $resulte){ 
                         
				$result[] = array(       
                        'ID_SATKER' => $resulte['kd_skpd'], 	
                        'ID_KEGIATAN' => $resulte['kd_kegiatan'],
						'NAMA_KEGIATAN' => $resulte['nm_kegiatan'],
                        'URAIAN' => $resulte['nm_rek'],
                        'REALISASI' => $resulte['real'],
                        'TA' => $tahun,
                        'BULAN' => $jangg
                        );
                        $ii++;
				}  
				echo json_encode($result);
            }
			//echo $query1;
        }else{
            echo 'Access Denied';
        }    	   
	}

	function kegiatan2() {
		$user=$this->uri->segment(3);
		$pass=$this->uri->segment(4);
		$jpagu=$this->uri->segment(5);
		$jangg = '';
		
		
        if($user=='reza' && $pass=='sirup'){
            if($jpagu=='susun'){
				$jangg = 'nilai';
			}else if ($jpagu=='ubah'){
				$jangg = 'nilai_ubah';
			}else{
				echo 'Access Denied';
			}	
			
			if($jangg!=''){
				$query1 = $this->transfer_model->kegiatan($jangg);
				$result = array();
		
				$ii = 0;        
				foreach($query1->result_array() as $resulte){ 
                         
				$result[] = array(       
                        'ID_PROGRAM' => $resulte['kd_program'],
						'ID_KEGIATAN' => $resulte['kd_kegiatan'],
						'ID_SATKER' => $resulte['kd_skpd'], 			
                        
						'NAMA_KEGIATAN' => $resulte['nm_kegiatan'],
                        'PAGU' => $resulte['angg'],
                        );
                        $ii++;
				}  
				echo json_encode($result);
            }
			//echo $query1;
        }else{
            echo 'Access Denied';
        }    	   
	}

	function anggaran_kas() {
		$user=$this->uri->segment(3);
		$pass=$this->uri->segment(4);
		$jpagu=$this->uri->segment(5);
		$tahun=$this->uri->segment(6);
		$jangg = '';
		$result = '';
		
        if($user=='reza' && $pass=='sirup'){
            if($jpagu=='susun'){
				$jangg = 'nilai';
			}else if ($jpagu=='ubah'){
				$jangg = 'nilai_ubah';
			}else{
				echo 'Access Denied';
			}	
			
			if($jangg!=''){
				$query1 = $this->transfer_model->anggaran_kas($jangg,$tahun);
				if($query1){
					$result = array();
			
					$ii = 0;        
					foreach($query1->result_array() as $resulte){ 
							 
					$result[] = array(       
							'ID_SATKER' => $resulte['kd_skpd'],
							'ID_KEGIATAN' => $resulte['kd_kegiatan'],                                        
							'JAN' => $resulte['jan'],
							'FEB' => $resulte['feb'],
							'MAR' => $resulte['mar'],
							'APR' => $resulte['apr'],
							'MEI' => $resulte['mei'],
							'JUN' => $resulte['jun'],
							'JUL' => $resulte['jul'],
							'AGU' => $resulte['agu'],
							'SEP' => $resulte['sep'],
							'OKT' => $resulte['okt'],
							'NOV' => $resulte['nov'],
							'DES' => $resulte['des']
							);
							$ii++;
					} 
				}	
				else{
					$result = 'Data tidak ditemukan';		
				}
			}
			echo json_encode($result);
			
			//echo $query1;
        }else{
            echo 'Access Denied';
        }    	   
	}	
    
    private function __getMyMethods()
	{
		$reflection = new ReflectionClass($this);
		
		//get all methods
		$methods = $reflection->getMethods();
		$this->builtInMethods = array();
		
		//get properties for each method
		if(!empty($methods))
		{
			foreach ($methods as $method) {
				if(!empty($method->name))
				{
					$methodProp = new ReflectionMethod($this, $method->name);
					
					//saves all methods names found
					$this->builtInMethods['all'][] = $method->name;
					
					//saves all private methods names found
					if($methodProp->isPrivate()) 
					{
						$this->builtInMethods['private'][] = $method->name;
					}
					
					//saves all private methods names found					
					if($methodProp->isPublic()) 
					{
						$this->builtInMethods['public'][] = $method->name;
						
						// gets info about the method and saves them. These info will be used for the xmlrpc server configuration.
						// (only for public methods => avoids also all the public methods starting with '_')
						if(!preg_match('/^_/', $method->name, $matches))
						{
							//consider only the methods having "_" inside their name
							if(preg_match('/_/', $method->name, $matches))
							{	
								//don't consider the methods get_instance and validation_errors
								if($method->name != 'get_instance' AND $method->name != 'validation_errors')
								{
									// -method name: user_get becomes [GET] user
									$name_split = explode("_", $method->name);
									$this->builtInMethods['functions'][$method->name]['function'] = $name_split['0'].' [method: '.$name_split['1'].']';
									
									// -method DocString
									$this->builtInMethods['functions'][$method->name]['docstring'] =  $this->__extractDocString($methodProp->getDocComment());
								}
							}
						}
					}
				}
			}
		} else {
			return false;
		}
		return true;
	}
    
    private function __extractDocString($DocComment)
	{
		$split = preg_split("/\r\n|\n|\r/", $DocComment);
		$_tmp = array();
		foreach ($split as $id => $row)
		{
			//clean up: removes useless chars like new-lines, tabs and *
			$_tmp[] = trim($row, "* /\n\t\r");
		}			
		return trim(implode("\n",$_tmp));
	}

	public function API_get()
	{
		$this->response($this->builtInMethods, 200); // 200 being the HTTP response code
	}
	
}