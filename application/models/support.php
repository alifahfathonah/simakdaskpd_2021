<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
1. tanggal_format_indonesia(a)
2. getBulan(a)
3. dotrek(a)
*/ 

class support extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function  tanggal_format_indonesia($tgl){
            
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;

    }
    function rp_minus($nilai){
        if($nilai<0){
            $nilai = $nilai * (-1);
            $nilai = '('.number_format($nilai,"2",",",".").')';    
        }else{
            $nilai = number_format($nilai,"2",",","."); 
        }
        
        return $nilai;
    } 
    function  getBulan($bln){
        switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "Maret";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
    }
    }
    function right($value, $count){
    return substr($value, ($count*-1));
    }

    function left($string, $count){
    return substr($string, 0, $count);
    }

    function  dotrek($rek){
                $nrek=strlen($rek);
                switch ($nrek) {
                case 1:
                $rek = $this->left($rek,1);                             
                 break;
                case 2:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1);                                
                 break;
                case 4:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2);                               
                 break;
                case 6:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2);                              
                break;
                case 8:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2);                             
                break;
                case 11:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2).'.'.substr($rek,8,12); ;                             
                break;
                case 12:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2).'.'.substr($rek,8,4); ;                             
                break;
                default:
                $rek = "";  
                }
                return $rek;
    }

    function auto_cek_status($skpd){
        $tgl_spp = $this->input->post('tgl_cek');
        $sql = "SELECT top 1 
                case 
                when statu=1 and status_sempurna=1 and status_ubah=1  then 'ubah'
                when statu=1 and status_sempurna=1 and status_ubah=0  then 'geser' 
                when statu=1 and status_sempurna=0 and status_ubah=0  then 'murni' 
                when statu=1 and status_sempurna=0 and status_ubah=1  then 'murni'
                else 'murni' end as anggaran from trhrka where left(kd_skpd,17) =left('$skpd',17)";
              //  echo "$sql";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result() as $resulte)
        { 
            $status_ang = $resulte->anggaran;
        }
        return $status_ang;
    }

    function sort($id='',$tbl=''){
        if($tbl==''){
            $tabel='';
        }else{
            $tabel="$tbl".".";
        }
        return $sort= substr($id,0,4)=='1.02' || substr($id,0,4)=='7.01' ? "{$tabel}kd_skpd='$id'" : "left({$tabel}kd_skpd,17)=left('$id',17)";  
    }
}