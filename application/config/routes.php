<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
$route['mfungsi'] 		= "master/mfungsi";//OK
$route['murusan'] 		= "master/murusan";//OK
$route['burusan'] 		= "master/burusan";//OK
$route['mskpd'] 		= "master/mskpd";//OK
$route['mprogram'] 		= "master/mprogram";//OK
$route['mkegiatan'] 	= "master/mkegiatan";//OK
$route['mrek1'] 		= "master/mrek1";//OK
$route['mrek2'] 		= "master/mrek2";//OK
$route['mrek3'] 		= "master/mrek3";//OK
$route['mrek4'] 		= "master/mrek4";//OK
$route['mrek5'] 		= "master/mrek5";//OK
$route['mrek6'] 		= "master/mrek6";//OK
$route['mbank'] 		= "master/mbank";//OK
$route['mttd'] 			= "master/mttd";//OK
$route['tapd'] 			= "master/tapd";//OK
$route['sumber_dana'] 	= "master/sumberaktif";//OK


$route['user'] 				= "master/user";//OK
$route['tambah_user'] 		= "master/tambah_user";//OK
$route['edit_user/(:any)'] 	= "master/edit_user";//OK
$route['hapus_user/(:any)'] = "master/hapus_user";//OK

//master rup

$route['rup_mttdppk'] = "sirup/master/mttd_ppkrup";//OK
$route['rup_kgiat'] = "sirup/rka/tambah_giat_rup";//OK
$route['rup_ceklist'] = "sirup/rka/cek_list_sirup";//OK
$route['rup_cekang'] = "sirup/rka/cek_anggaran_sirup";//OK

//Transaksi rup
$route['rup_penyedia'] = "sirup/sirup/input_penyedia";//OK
$route['rup_penyedia_final'] = "sirup/sirup/input_final_penyedia";//OK
$route['rup_penyedia_umum'] = "sirup/sirup/input_umumkan_penyedia";//OK

$route['rup_swakelola'] = "sirup/sirup/input_swakelola";//OK




//ANGGARAN murni
$route['cetak-rka-rekap'] 								= "cetak_rka/cetak_rka_rekap/RKA";
$route['cetak-dpa-rekap'] 								= "cetak_rka/cetak_rka_rekap/DPA"; 				 				/*menu cetak rka satu*/
$route['preview_rka_skpd_penetapan/(:any)'] 			= "cetak_rka/preview_rka_skpd_penetapan/";  /*cetakan rka 1*/
$route['cetak-rka-pendapatan'] 							= "cetak_rka/cetak_rka_pendapatan/RKA";//OK
$route['cetak-dpa-pendapatan'] 							= "cetak_rka/cetak_rka_pendapatan/DPA";
$route['preview_pendapatan_penyusunan/(:any)'] 			= "cetak_rka/preview_pendapatan_penyusunan/";//OK
$route['cetak-rka-belanja'] 						    = "cetak_rka/rka22_penyusunan/RKA";//OK
$route['cetak-dpa-belanja'] 						    = "cetak_rka/rka22_penyusunan/DPA";//OK
$route['preview_belanja_penyusunan/(:any)'] 			= "cetak_rka/preview_belanja_penyusunan/";//OK
$route['cetak-rka-pembiayaan'] 			 				= "cetak_rka/cetak_rka_pembiayaan/RKA";
$route['cetak-dpa-pembiayaan'] 			 				= "cetak_rka/cetak_rka_pembiayaan/DPA";
$route['preview_rka_pembiayaan_penetapan/(:any)'] 		= "cetak_rka/preview_rka_pembiayaan_penetapan/";  
$route['cetak-rka-rinci']		 						= "cetak_rka/rka221_penyusunan";//OK

//PENYUSUNAN

$route['tambah_rka_penyusunan'] 				= "anggaran_murni/tambah_rka_penyusunan";//OK
$route['rka_skpd_penyusunan'] 					= "rka_rancang/rka0_penyusunan";//OK
$route['preview_rka0_penyusunan/(:any)'] 		= "rka_rancang/preview_rka0_penyusunan/";//OK


$route['preview_rka0_penyusunan_org/(:any)'] 	= "rka_rancang/preview_rka0_penyusunan_org/";//OK

$route['preview_rka22_penyusunan/(:any)'] 		= "Rka_rancang/preview_rka22_penyusunan/";//OK

$route['preview_rka221_penyusunan/(:any)'] 		= "cetak_rka/preview_rka221_penyusunan/";//OK
$route['daftar_kegiatan_penyusunan/(:any)'] 	= "rka_rancang/daftar_kegiatan_penyusunan/";//OK
$route['pilih_giat_penyusunan'] 				= "rka_rancang/tambah_giat_penyusunan";//OK
$route['rka_pembiayaan_penyusunan'] 			= "rka_rancang/rka_pembiayaan_penyusunan";//OG
$route['preview_rka_pembiayaan_penyusunan/(:any)'] 		= "rka_rancang/preview_rka_pembiayaan_penyusunan/";//OG



//PENETAPAN

$route['tambah_rka_penetapan'] 			    			= "rka_penetapan/tambah_rka_penetapan";//OG

$route['preview_rka_skpd_penetapan_org/(:any)'] 		= "rka_penetapan/preview_rka_skpd_penetapan_org/";
$route['rka_belanja_skpd_penetapan'] 					= "rka_penetapan/rka_belanja_skpd_penetapan";
$route['preview_rka_belanja_skpd_penetapan/(:any)'] 	= "Rka_penetapan/preview_rka_belanja_skpd_penetapan/";
$route['rka_rincian_belanja_skpd_penetapan']			= "rka_penetapan/rka_rincian_belanja_skpd_penetapan";
$route['preview_rincian_belanja_skpd_penetapan/(:any)'] = "rka_penetapan/preview_rincian_belanja_skpd_penetapan/";
$route['daftar_kegiatan_penetapan/(:any)'] 				= "rka_penetapan/daftar_kegiatan_penetapan/";
$route['pilih_giat_penetapan'] 							= "rka_penetapan/tambah_giat_penetapan";//OK



//Penyempurnaan

$route['tambah_rka_penyempurnaan'] 			    = "rka_penyempurnaan/tambah_rka_penyempurnaan";//OG
$route['rka_skpd_penyempurnaan'] 				= "rka_penyempurnaan/rka0_penyusunan";
$route['preview_rka0_penyempurnaan/(:any)'] 	= "rka_penyempurnaan/preview_rka0_penyusunan/";
$route['rka_belanja_skpd_penyempurnaan'] 		= "rka_penyempurnaan/rka22_penyusunan";
$route['preview_rka22_penyempurnaan/(:any)'] 	= "Rka_penyempurnaan/preview_rka22_penyusunan/";
$route['rka_rincian_belanja_skpd_penyempurnaan']= "rka_penyempurnaan/rka221_penyusunan";
$route['preview_rka221_penyempurnaan/(:any)'] 	= "rka_penyempurnaan/preview_rka221_penyusunan/";
$route['daftar_kegiatan_penyempurnaan/(:any)'] 	= "rka_penyempurnaan/daftar_kegiatan_penyusunan/";



//MAPPING
$route['map_skpd'] 							= "mapping/map_skpd";//OK
$route['map_rekening'] 						= "mapping/map_rekening";//OK
$route['imapping'] 							= "mapping/imapping";//OK
$route['input_indikator'] 					= "mapping/input_indikator";//OK
$route['validasi_indikator'] 				= "mapping/validasi_indikator";//OK
$route['validasi_kegiatan'] 				= "mapping/validasi_kegiatan";//OK



//MASTER
$route['ganti_password'] 				= "master/ganti_pass";

$route['index'] = "index";
$route['login'] = "login";
$route['logout'] = "logout";
$route['404_override'] = 'error';



/* End of file routes.php */
/* Location: ./application/config/routes.php */