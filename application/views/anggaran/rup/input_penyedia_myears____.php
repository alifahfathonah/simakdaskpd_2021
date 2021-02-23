<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
 
    <script type="text/javascript">
        
    var did      = '';
    var dskpd    = '';
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0 ;
    var lcidx    = 0 ;
    var lcstatus = '';
    var kpaket   = '';
    var tovol    = 0;
    var kode_tahun = 0;
    
    $(document).ready(function() {
		$("#paket2").hide();
		$("#sd2").hide();
		
        $("#accordion").accordion();            
        $( "#dialog-modal" ).dialog({
            height: 1190,
            width: 970,
            modal: true,
            autoOpen:false,
        });
        $( "#dialog-modal-lihat" ).dialog({
            height: 750,
            width: 990,
            modal: true,
            autoOpen:false,
        });
        $( "#dialog-modal-rek" ).dialog({
            height: 220,
            width: 630,
            modal: true,
            autoOpen:false,
        });
        get_skpd();
		get_tahun();
        
        });    

     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/sirup/sirup/loadPenyedia_myears',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        rowStyler: function(index,row){
        if (row.is_revisi == 1){
          return 'color:green;';
        }else{
            if (row.is_final == 1){
             return 'color:#6217FA;';
            }    
        }
        },
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[    	    
            {field:'id',
            title:'id',
            width:5,
            hidden:"true"},
            {field:'idrup',
            title:'IDRUP',
            width:3,
            align:"left"},
            {field:'nm_paket_gab',
            title:'Nama Paket',
            width:22,
            align:"left"},
            {field:'total',
    		title:'Pagu',
    		width:10,
            align:"right"},
            {field:'pilih_awal',
    		title:'Pemilihan',
    		width:6,
            align:"center"},            
            {field:'sumber_dana',
    		title:'S.Dana',
    		width:5,
            align:"center"},
            {field:'ket_revisi',
            title:'REVISI',
            width:3,
            align:"center"}
            /**{field:'tampil',title:'cek',width:2,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/info.png" onclick="javascript:x();" />';
                        }
                        }*/
            
        ]],
        onSelect:function(rowIndex,rowData){
		  idrup			= rowData.idrup;	
          dppk          = rowData.ppk;          
          dskpd         = rowData.skpd;
          did           = rowData.id; 
		  id   			= rowData.id;
          tahun         = rowData.tahun;   
          kldi          = rowData.kldi;
          nm_paket      = rowData.nm_paket;
          nm_paket_gab  = rowData.nm_paket_gab;
          kpaket        = rowData.nm_paket;          
		  kd_program    = rowData.kd_program;
		  kd_kegiatan   = rowData.kd_kegiatan;
		  nm_kegiatan   = rowData.nm_kegiatan;
		  volume        = rowData.volume;
		  uraian        = rowData.uraian;
		  spesifikasi   = rowData.spesifikasi;
		  uk            = rowData.uk;
          nuk           = rowData.nuk;		  
          tkdn          = rowData.tkdn;
		  pradipa       = rowData.pradipa;
		  total         = rowData.total;
		  mtd_pengadaan = rowData.mtd_pengadaan;
		  pilih_awal    = rowData.pilih_awal;
		  pilih_akhir   = rowData.pilih_akhir;
		  kerja_mulai   = rowData.kerja_mulai;
		  kerja_akhir   = rowData.kerja_akhir;
		  aktif         = rowData.aktif;
		  umumkan       = rowData.umumkan;
		  is_final      = rowData.is_final;	
          is_revisi     = rowData.is_revisi; 	  
          id_swakelola  = rowData.id_swakelola;
		  skpd  		= rowData.skpd;
          id_swakelola  = rowData.id_swakelola;
		  no_renja      = rowData.no_renja;
		  izin_tahun_jamak  = rowData.izin_tahun_jamak;		  
          tanggal_kebutuhan = rowData.tanggal_kebutuhan;
		  ket_final		= rowData.ket_final;
		  tanggal_kebutuhan_2 = rowData.tanggal_kebutuhan_2;
          username      = rowData.username;
		  lcstatus		='edit';
		  get(idrup,id,dppk,tahun,kldi,nm_paket,kd_program,kd_kegiatan,nm_kegiatan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,aktif,umumkan,id_swakelola,skpd,is_final,no_renja,izin_tahun_jamak,tanggal_kebutuhan,tanggal_kebutuhan_2,ket_final,username,nm_paket_gab,is_revisi);
		},
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data'; 
             
        }
        });
	  
		$('#dgsd').datagrid({
        idField:'idx',
        toolbar:"#tb",              
        autoRowHeight:"false",
        singleSelect:"true",
        nowrap:"false",                              
        columns:[[    	    
            {field:'idx',
    		title:'idx',
    		width:5,
            hidden:"true"},
            {field:'kd_sd',
    		title:'Sumber Dana',
    		width:95,
            align:"center"},
            {field:'tahun',
    		title:'T.A',
    		width:45,
            align:"center"}, 
            {field:'isi_paket',
    		title:'Nama Paket',
    		width:350,
            align:"left"},            
            {field:'kd_ad',
    		title:'Asal dana',
    		width:130,
            hidden:"true"},
            {field:'kd_ads',
    		title:'Asal Dana Satker',
    		width:130,
            hidden:"true"},
            {field:'max',
    		title:'Mak',
    		width:60,
            align:"right",
            hidden:"true"},
            {field:'kd_kegiatan',
    		title:'Kode Kegiatan',
    		width:100,
            hidden:"true"},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',
    		width:100,
            hidden:"true"},
            {field:'kd_rek5',
    		title:'Mak',
    		width:65},            
            {field:'nm_rek5',
    		title:'Uraian',
    		width:260,
            align:"left",
            hidden:"true"},
            {field:'vol',
    		title:'vol',
    		width:30,
            align:"right"},                       
            {field:'pagu',
    		title:'Pagu',
    		width:130,
            align:"right"},
            {field:'user',
    		title:'user',
    		width:200,
            hidden:"true"},
            {field:'jns_peng',
    		title:'Jenis Penggadaan',
    		width:170,
            align:"center",
            hidden:"true"},
            {field:'nmjns_peng',
    		title:'Jenis Penggadaan',
    		width:170,
            align:"center"}
           
        ]]
        });

        /*$('#dgsd').datagrid({
        idField:'idx',
        toolbar:"#tb",              
        autoRowHeight:"false",
        singleSelect:"true",
        nowrap:"false",                              
        columns:[[          
            {field:'idx',
            title:'idx',
            width:10},
            {field:'kd_sd',
            title:'Sumber Dana',
            width:95,
            align:"center"},
            {field:'tahun',
            title:'T.A',
            width:45,
            align:"center"}, 
            {field:'isi_paket',
            title:'Nama Paket',
            width:350,
            align:"left"},            
            {field:'kd_ad',
            title:'Asal dana',
            width:130},
            {field:'kd_ads',
            title:'Asal Dana Satker',
            width:130},
            {field:'max',
            title:'Mak',
            width:60,
            align:"right"},
            {field:'kd_kegiatan',
            title:'Kode Kegiatan',
            width:100},
            {field:'nm_kegiatan',
            title:'Nama Kegiatan',
            width:100},
            {field:'kd_rek5',
            title:'Mak',
            width:65},            
            {field:'nm_rek5',
            title:'Uraian',
            width:260,
            align:"left"},
            {field:'vol',
            title:'vol',
            width:30,
            align:"right"},                       
            {field:'pagu',
            title:'Pagu',
            width:130,
            align:"right"},
            {field:'user',
            title:'user',
            width:200},
            {field:'jns_peng',
            title:'Jenis Penggadaan',
            width:170,
            align:"center"},
            {field:'nmjns_peng',
            title:'Jenis Penggadaan',
            width:170,
            align:"center"}
           
        ]]
        });*/
        
        $('#dgsd_lihat').datagrid({
        idField:'idx',            
        rownumbers:"true",        
        singleSelect:"true",
        autoRowHeight:"true",  
        toolbar: '#tb',                               
        columns:[[    	    
            {field:'idx',
    		title:'idx',
    		width:5,
            hidden:"true"},
            {field:'kd_sd',
    		title:'Sumber Dana',
    		width:95,
            align:"center"},
            {field:'tahun',
    		title:'T.A',
    		width:45,
            align:"center"}, 
            {field:'isi_paket',
    		title:'Nama Paket',
    		width:350,
            align:"left"},            
            {field:'kd_ad',
    		title:'Asal dana',
    		width:130,
            hidden:"true"},
            {field:'kd_ads',
    		title:'Asal Dana Satker',
    		width:130,
            hidden:"true"},
            {field:'max',
    		title:'Mak',
    		width:60,
            align:"right",
            hidden:"true"},
            {field:'kd_kegiatan',
    		title:'Kode Kegiatan',
    		width:100,
            hidden:"true"},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',
    		width:100,
            hidden:"true"},
            {field:'kd_rek5',
    		title:'Mak',
    		width:65},            
            {field:'nm_rek5',
    		title:'Uraian',
    		width:260,
            align:"left",
            hidden:"true"},
            {field:'vol',
    		title:'vol',
    		width:30,
            align:"right"},                       
            {field:'pagu',
    		title:'Pagu',
    		width:130,
            align:"right"},
            {field:'user',
    		title:'user',
    		width:200,
            hidden:"true"},
            {field:'jns_peng',
    		title:'Jenis Penggadaan',
    		width:170,
            align:"center",
            hidden:"true"},
            {field:'nmjns_peng',
    		title:'Jenis Penggadaan',
    		width:170,
            align:"center"}                        
        ]]
        });
        
        
        $('#dgsd_lokasi').datagrid({
        idField:'id',            
        rownumbers:"true",        
        singleSelect:"true",
        autoRowHeight:"true", 
        nowrap:"false",                                 
        columns:[[    	    
            {field:'prov',
    		title:'Provinsi',
    		width:135,
            align:"left"},
            {field:'lokasi',
    		title:'Kode',
    		width:10,
            align:"left",
            hidden:"true"},             
            {field:'nm_lokasi',
    		title:'Kabupaten/Kota',
    		width:230,
            align:"left"}, 
            {field:'det_lokasi',
    		title:'Detail Lokasi',
    		width:500,
            align:"left"},
            {field:'user',
    		title:'user',
    		width:200,
            hidden:"true"}
            
        ]]
        });
        
        $('#dgsd_lihat_lokasi').datagrid({
        idField:'id',            
        rownumbers:"true",        
        singleSelect:"true",
        autoRowHeight:"true",  
        toolbar: '#tb',                               
        columns:[[    	    
            {field:'prov',
    		title:'Provinsi',
    		width:135,
            align:"left"},
            {field:'lokasi',
    		title:'Kode',
    		width:10,
            align:"left",
            hidden:"true"},             
            {field:'nm_lokasi',
    		title:'Kabupaten/Kota',
    		width:230,
            align:"left"}, 
            {field:'det_lokasi',
    		title:'Detail Lokasi',
    		width:500,
            align:"left"},
            {field:'user',
    		title:'user',
    		width:200,
            hidden:"true"}
            
        ]]
        });
        
        $('#pilih_awal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
           
        });
		
		$('#tgl_kebutuhan').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
           
        });
		
		$('#tgl_kebutuhan_2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
           
        });
		
		$('#pilih_akhir').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
           
        });
        
		$('#kerja_awal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
           
        });
		
		$('#kerja_akhir').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
           
        });
        
        $('#tgl_bukti').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka1 = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
        
		$('#nmppk').combogrid({  
           panelWidth:250,  
           idField:'nama',  
           textField:'nama',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/sirup/sirup/paket_ppk',  
           columns:[[  
               {field:'nama',title:'Nama',width:240}    
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#idppk").attr("value",rowData.did);
               $("#userppk").attr("value",rowData.user);
               }  
        });
		
        $('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               
           }  
        });
	
        $('#lokasi').combogrid({  
           panelWidth:250,  
           idField:'kd_lokasi',  
           textField:'nm_lokasi',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/sirup/sirup/lokasi',  
           columns:[[  
               {field:'nm_lokasi',title:'Lokasi',width:240}    
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#nmlokasi").attr("value",rowData.nm_lokasi);
			   $("#klpd").attr("value",rowData.klpd);
               }  
        });
		
		$('#nm_paket_detailx_sdana').combogrid({  
           panelWidth:110,  
           idField:'nm_sd',  
           textField:'nm_sd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/sirup/sirup/sumberdana_x',  
           columns:[[  
               {field:'nm_sd',title:'Sumber Dana',width:100}    
           ]],  
           onSelect:function(rowIndex,rowData){
               //$("#nmlokasi").attr("value",rowData.nm_lokasi);
			   //$("#klpd").attr("value",rowData.klpd);
               }  
        });
		
		$('#nm_paket_detailx_sdana_x').combogrid({  
           panelWidth:110,  
           idField:'nm_sd',  
           textField:'nm_sd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/sirup/sirup/sumberdana_x',  
           columns:[[  
               {field:'nm_sd',title:'Sumber Dana',width:100}    
           ]],  
           onSelect:function(rowIndex,rowData){
				document.getElementById("nsumber").value=rowData.nm_sd;
            }  
        });
        
        $('#jns_pengadaan').combogrid({  
           panelWidth:170,  
           idField:'kd_jp',  
           textField:'nm_jp',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/sirup/sirup/jns_pengadaan',  
           columns:[[  
               {field:'nm_jp',title:'Pengadaan',width:160}    
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#nmjns_pengadaan").attr("value",rowData.nm_jp);
               }  
        });        
		
		$('#homekd_kegiatan').combogrid({  
           panelWidth:680,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:500}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kd_giat = rowData.kd_kegiatan;
			   listdg_kegiatan(kd_giat);
           }  
        });
        
		$('#mtd_pengadaan').combobox({  
           panelWidth:400,  
		   multiple: false,
           idField:'kd_mp',  
           textField:'nm_mp',  
           mode:'remote',
		   url:'<?php echo base_url(); ?>index.php/sirup/sirup/mtd_pengadaan',  
           valueField:'kd_mp',
		   method:'get' 
        });
		
        $('#tm_mtd_pengadaan').combobox({  
           panelWidth:400,  
		   multiple: false,
           idField:'kd_mp',  
           textField:'nm_mp',  
           mode:'remote',
		   url:'<?php echo base_url(); ?>index.php/sirup/sirup/mtd_pengadaan',  
           valueField:'kd_mp',
		   method:'get',
		   required:true  
        });
        
		$('#kd_kegiatan').combogrid({  
           panelWidth:680,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:500}    
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_kegiatan").attr("value",rowData.nm_kegiatan);
               $("#kd_program").attr("value",rowData.kd_program);               
               kd_giat = rowData.kd_kegiatan;
               $("#nilai_totalkeg").attr("value",rowData.nilai); 
               $("#nilai_totalsirup").attr("value",rowData.nilai_sirup);


               if(kode_tahun==2020){
                   $('#nm_paket').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listRekening',queryParams:({kd_keg:kd_giat})});                
               }else{
                   $('#nm_paket').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listRekening_mYears',queryParams:({kd_keg:kd_giat})});  
               }

               //$('#koderek').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listRekening',queryParams:({kd_keg:kd_kegi})});
           }  
        });
        
        $('#koderek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:100},  
               {field:'nm_rek5',title:'Nama Rekening',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#namarek").attr("value",rowData.nm_rek5);
               $("#nsumber").attr("value",rowData.sumber);
           }  
           
        });
        
        $('#nm_paket').combogrid({  
           panelWidth:700,  
           idField:'nm_rek5',  
           textField:'nm_rek5',  
           mode:'remote',
           columns:[[  
               {field:'kd_rek5',title:'Kode',width:100},  
               {field:'nm_rek5',title:'Nama Paket',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#namarek").attr("value",rowData.nm_rek5);
               $("#nsumber").attr("value",rowData.sumber);
               $("#koderek").combogrid("setValue",rowData.kd_rek5);  
               $("#nilai_totalpaket").attr("value",rowData.nilai);
               $("#nilai_totalpaket_myears").attr("value",rowData.nilai_myears);               
               kd_rekk5 = rowData.kd_rek5;  

               if(kode_tahun==2020){
                $('#nm_paket_detail').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listRincianpaket',queryParams:({kd_keg:kd_giat,kd_rek:kd_rekk5,tahun:kode_tahun})});               
               }else{
                $('#nm_paket_detail').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listRincianpaket_mYears',queryParams:({kd_keg:kd_giat,kd_rek:kd_rekk5})});
               }
                          
           }
        });
        
        $('#nm_paket_detail').combogrid({  
           panelWidth:800,  
           idField:'nm_dpaket',  
           textField:'nm_dpaket',  
           mode:'remote',
           columns:[[  
               {field:'kd_dpaket',title:'Kode',width:50},  
               {field:'nm_dpaket',title:'Detail Paket',width:600},
               {field:'total_ubah',title:'Nilai',width:140}      
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#kd_paket_detail").attr("value",rowData.kd_dpaket);                          
               $("#jumvolume").attr("value",rowData.volume);
               $("#npagu").attr("value",rowData.total_ubah);                                           
           }
        });        
        
		$('#ntahun').combogrid({  
           panelWidth:110,  
           url:'<?php echo base_url(); ?>index.php/sirup/sirup/listTahun_Myears',
           idField:'tahun',  
           textField:'tahun',  
           mode:'remote',
           columns:[[  
               {field:'tahun',title:'Tahun',width:100}  
           ]],
           onSelect:function(rowIndex,rowData){
               kode_tahun = rowData.tahun;               
               if(kode_tahun==2020){
                getGiat();
               }else{
                getGiat_mYears();
               }
           }   
        });
		
        $('#bank').combogrid({  
           panelWidth:700,  
           idField:'kode',  
           textField:'kode',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/config_bank_simpanan',  
           columns:[[  
               {field:'kode',title:'Kode Bank',width:100},  
               {field:'nama',title:'Nama Bank',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kode;               
               $("#nmbank").attr("value",rowData.nama.toUpperCase());
           }  
        });

    });        
    
    function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
    }
    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
     
     function get_skpd(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
					$("#skpd").combogrid("setValue",data.kd_skpd);
					$("#nmskpd").attr("value",data.nm_skpd);
                    $("#tm_nmskpd").attr("value",data.nm_skpd);	
                    $("#klpd").attr("value","Pemerintah Daerah Kota Pontianak");
                    $("#det_lokasi").attr("value",data.nm_skpd);				
        		}                                     
     });
     }
     
     function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        }

	function getGiat(){
	 var skpd = $('#skpd').combogrid('getValue');
     $('#kd_kegiatan').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listKegiatan',queryParams:({skpd:skpd,tahun:kode_tahun})});  
     $('#homekd_kegiatan').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listKegiatan',queryParams:({skpd:skpd,tahun:kode_tahun})});
     } 

    function getGiat_mYears(){
     var skpd = $('#skpd').combogrid('getValue');
     $('#kd_kegiatan').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listKegiatan_mYears',queryParams:({skpd:skpd})});  
     $('#homekd_kegiatan').combogrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/listKegiatan_mYears',queryParams:({skpd:skpd})});
     }  
	 
	 function listdg_kegiatan(kdkeg){
		var kriteria = kdkeg; 
		$(function(){ 
		$('#dg').edatagrid({
			url: '<?php echo base_url(); ?>/index.php/sirup/sirup/loadPenyedia_kegiatan',
			queryParams:({cari:kriteria})
			});        
		});
	}
	
    function get(idrup,id,dppk,tahun,kldi,nm_paket,kd_program,kd_kegiatan,nm_kegiatan,volume,uraian,spesifikasi,tkdn,uk,nuk,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,aktif,umumkan,id_swakelola,skpd,is_final,no_renja,izin_tahun_jamak,tanggal_kebutuhan,tanggal_kebutuhan_2,ket_final,username,nm_paket_gab,is_revisi){
		var hasil_final = ket_final;
		var hasil_paket = nm_paket+' ('+nm_paket_gab+')';
		
    if(is_revisi==1){
            hasil_final = 'STATUS PAKET : DIREVISI SATUKESATU';
            $('#vhapus').linkbutton('disable');
            $('#vedit').linkbutton('disable');
    }else if(is_revisi==3){
            hasil_final = 'STATUS PAKET : DIREVISI PAKET DIBATALKAN';
            $('#vhapus').linkbutton('disable');
            $('#vedit').linkbutton('disable');
    }else{
            
        if(hasil_final=='SUDAH' && umumkan==0){
			hasil_final = 'STATUS PAKET : SUDAH DI FINALISASI OLEH PPKOM';
			$('#vhapus').linkbutton('disable');
			$('#vedit').linkbutton('disable');
		}else if(hasil_final=='BELUM' && umumkan==0){
			hasil_final = 'STATUS PAKET : BELUM DI FINALISASI';
			$('#vhapus').linkbutton('enable');
			$('#vedit').linkbutton('enable');
		}else if(umumkan==1){
			hasil_final = 'STATUS PAKET : SUDAH DI UMUMKAN OLEH PA';
			$('#vhapus').linkbutton('disable');
			$('#vedit').linkbutton('disable');
		}
	}

        $("#nm_username").attr("value",username);
		$("#tmket_final").attr("value",hasil_final);
		$("#tm_idrup").attr("value",idrup);
		$("#id").attr("value",id);
        $("#tm_ppk").attr("value",dppk);
		$("#nmppk").combogrid("setValue",dppk);
        $("#ntahun").combogrid("setValue",tahun);
        $("#tm_ntahun").attr("value",tahun);      
        $("#tm_nm_paket_kldi").attr("value",kldi);
        $("#nm_paket").combogrid("setValue",nm_paket); 		        
        $("#tm_nm_paket").attr("value",hasil_paket); 		                        		
        $("#kd_kegiatan").combogrid("setValue",kd_kegiatan); 
		$("#nm_kegiatan").attr("value",nm_kegiatan);
		$("#kd_program").attr("value",kd_program);
		$("#volume").attr("value",volume);
        var invol = volume.replace(".00","");
		$("#tm_volume").attr("value",invol);		
        $("#uraian").attr("value",uraian);		
        $("#tm_uraian").attr("value",uraian);
		$("#spesifikasi").attr("value",spesifikasi);
		$("#tm_spesifikasi").attr("value",spesifikasi);
		$("#total_pagu").attr("value",total);		
        $("#tm_total_pagu").attr("value",total);
		var str = mtd_pengadaan;
		var n = str.length;
		if(n==1){
			mtd_pengadaan = mtd_pengadaan+" ";
		}		
		
		$("#mtd_pengadaan").combobox("setValue",mtd_pengadaan);
		$("#tm_mtd_pengadaan").combobox("setValue",mtd_pengadaan);		
        $("#tgl_kebutuhan").datebox("setValue",tanggal_kebutuhan);
		$("#tm_tgl_kebutuhan").attr("value",tanggal_kebutuhan);
		$("#tgl_kebutuhan_2").datebox("setValue",tanggal_kebutuhan_2);
		$("#tm_tgl_kebutuhan_2").attr("value",tanggal_kebutuhan_2);
		$("#pilih_awal").datebox("setValue",pilih_awal);
		$("#tm_pilih_awal").attr("value",pilih_awal);
		$("#pilih_akhir").datebox("setValue",pilih_akhir);
		$("#tm_pilih_akhir").attr("value",pilih_akhir);
		$("#kerja_awal").datebox("setValue",kerja_mulai);
		$("#tm_kerja_awal").attr("value",kerja_mulai);
		$("#kerja_akhir").datebox("setValue",kerja_akhir);
		$("#tm_kerja_akhir").attr("value",kerja_akhir);
		$("#id_swakelola").attr("value",id_swakelola);
		$("#no_renja").attr("value",no_renja);
		$("#tm_norenja").attr("value",no_renja);		
        $("#no_jamak").attr("value",izin_tahun_jamak);
        $("#tm_noizin").attr("value",izin_tahun_jamak);
        
        if (is_final=='1'){            
				$("#final").attr("checked",true);
			} else {
				$("#final").attr("checked",false);                
			}
            
        if (tkdn=='1'){            
				$("#tkdn").attr("checked",true);
                $("#tm_tkdn").attr("value","Ya");
			} else {
				$("#tkdn").attr("checked",false);
                $("#tm_tkdn").attr("value","Tidak");
			}
        if (uk=='1'){            
				$("#uk").attr("checked",true);
                $("#tm_uk").attr("value","Ya");
			} else {
				$("#uk").attr("checked",false);
                $("#tm_uk").attr("value","Tidak");
			}  
        if (nuk=='1'){            
				$("#nuk").attr("checked",true);
                $("#tm_nuk").attr("value","Ya");
			} else {
				$("#nuk").attr("checked",false);
                $("#tm_nuk").attr("value","Tidak");
			}        
		if (pradipa=='1'){            
				$("#pradipa").attr("checked",true);
                $("#tm_pradipa").attr("value","Ya");
			} else {
				$("#pradipa").attr("checked",false);
                $("#tm_pradipa").attr("value","Tidak");
			}
		if (aktif=='1'){            
				$("#aktif").attr("checked",true);
			} else {
				$("#aktif").attr("checked",false);
			}
		if (umumkan=='1'){            
				$("#umumkan").attr("checked",true);
			} else {
				$("#umumkan").attr("checked",false);
			}
		
		//$('#dgsd').datagrid({url:'<?php echo base_url(); ?>index.php/sirup/sirup/detailPenyedia',queryParams:({skpd:skpd,id:id})}); 
		
    }
    
    function kosong(){
		get_nourut();
		lcstatus = 'tambah';
		$("#id").attr("value",'');
		$('#nmppk').combogrid('setValue','');
        $('#lokasi').combogrid('setValue','');		
        $("#ntahun").combogrid("setValue",'');
        $("#nm_paket").combogrid("setValue",''); 
		$("#kd_kegiatan").combogrid("setValue",''); 
		$("#nm_kegiatan").attr("value",'');
		$("#kd_program").attr("value",'');
		$("#nmlokasi").attr("value",'');		
        //$("#det_lokasi").attr("value",'');
		$("#jns_pengadaan").combogrid('setValue','');
		$("#volume").attr("value",'0');
		$("#uraian").attr("value",'');
		$("#spesifikasi").attr("value",'');
		$("#total_pagu").attr("value",'0');
        $("#total_pagu_myears").attr("value",'0');
        $("#no_renja").attr("value",'');
        $("#no_jamak").attr("value",'');
		$("#mtd_pengadaan").combobox("setValue",'');
		$("#tgl_kebutuhan").datebox("setValue",'');
		$("#tgl_kebutuhan_2").datebox("setValue",'');
		$("#pilih_awal").datebox("setValue",'');
		$("#pilih_akhir").datebox("setValue",'');
		$("#kerja_awal").datebox("setValue",'');
		$("#kerja_akhir").datebox("setValue",'');
        $("#id_swakelola").attr("value",'');
		$("#tkdn").attr("checked",false);
		$("#pradipa").attr("checked",false);
		$("#aktif").attr("checked",false);
		$("#umumkan").attr("checked",false);
		$("#paket_kecuali").attr("checked",false);
		
        
		$(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sirup/sirup/detailPenyedia',
                data: ({skpd:'',id:''}),
                dataType:"json",
                success:function(data){
                    $('#dgsd').datagrid('loadData',[]);
                    $('#dgsd').edatagrid('reload');
                    $.each(data,function(i,n){      
                    idx     = n['idx'];
                    tahun   = n['tahun'];
                    klpd    = n['klpd']; 
                    kd_paket= n['kd_paket'];
                    isi_paket= n['isi_paket'];                                       
                    kd_sd   = n['kd_sd'];
                    kd_ad  	= n['kd_ad'];
                    kd_ads 	= n['kd_ads'];
                    max  	= n['max'];
                    kd_keg  = n['kd_kegiatan'];
                    nm_keg  = n['nm_kegiatan'];
                    kd_rek  = n['kd_rek5'];
                    nm_rek  = n['nm_rek5'];       
                    vol     = n['vol'];             
                    pagu 	= n['pagu'];
                    user    = n['user'];
                    cjnspeng= n['jnsp'];
                    cnjnspeng= n['nmjnsp'];             					
                                                                                                        
                    $('#dgsd').edatagrid('appendRow',{idx:idx,klpd:klpd,tahun:tahun,kd_paket:kd_paket,isi_paket:isi_paket,kd_sd:kd_sd,kd_ad:kd_ad,kd_ads:kd_ads,max:max,kd_kegiatan:ckkeg,nm_kegiatan:cnkeg,kd_rek5:ckrek,nm_rek5:cnrek,vol:vol,pagu:pagu,user:user,jns_peng:cjnspeng,nmjns_peng:cnjnspeng});                                                                       
                    });                                                                           
                }
            });
           });
           
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sirup/sirup/detailPenyedia_lokasi',
                data: ({skpd:'',id:''}),
                dataType:"json",
                success:function(data){
                    $('#dgsd_lokasi').datagrid('loadData',[]);
                    $('#dgsd_lokasi').edatagrid('reload');
                    $.each(data,function(i,n){                                    
                    user        = n['user'];
                    prov        = n['prov'];
                    nm_lokasi   = n['nm_lokasi'];
                    det_lokasi  = n['det_lokasi'];                                        
                    lokasi      = n['lokasi'];                    
                                                                                                        
                    $('#dgsd_lihat_lokasi').edatagrid('appendRow',{user:user,prov:prov,lokasi:lokasi,nm_lokasi:nm_lokasi,det_lokasi:det_lokasi});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });
		
    }
    
   
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/sirup/sirup/loadPenyedia_myears',
        queryParams:({cari:kriteria})
        });        
     });
     }
    
    
    function simpan(){
		var skpd_ 		 	= $("#skpd").combogrid("getValue");         
        var nm_paket_ 	    = $("#nm_paket").combogrid("getValue");
        var ntahun_         = '2020';//$("#ntahun").combogrid("getValue");	
		var kd_kegiatan_ 	= $("#kd_kegiatan").combogrid("getValue"); 
		var nm_kegiatan_ 	= document.getElementById('nm_kegiatan').value;
		var kd_program_ 	= document.getElementById('kd_program').value;
		var lokasi_ 		= $('#lokasi').combogrid('getValues');
		var det_lokasi_ 	= document.getElementById('det_lokasi').value;
		var jns_pengadaan_ 	= $("#jns_pengadaan").combogrid("getValues"); 
		var volume_ 		= document.getElementById('volume').value;
		var uraian_ 		= document.getElementById('uraian').value;
		var spesifikasi_ 	= document.getElementById('spesifikasi').value;        
        var renja_ 		    = document.getElementById('no_renja').value;
		var jamak_ 	        = document.getElementById('no_jamak').value;        
		var total_pagu_ 	= angka(document.getElementById('total_pagu').value);
		var kecuali_ 		= document.getElementById('is_kecuali').checked;
		

        var nuraian = uraian_.length;
        var nm_paket_ = nm_paket_+' - Multiyears ';
        
        if(nuraian<10){
            alert('Silahkan Lengkapi Uraian Pekerjaan');
            exit();
        }

        if(jamak_<7){
            alert('Silahkan Lengkapi No. Izin Tahun Jamak / MOU');
            exit();
        }

        var nspesifikasi = spesifikasi_.length;
        
        if(nspesifikasi<10){
            alert('Silahkan Lengkapi spesifikasi Pekerjaan');
            exit();
        }

		if(kecuali_==true){
			mtd = 16;
		}else{
			mtd = $("#mtd_pengadaan").combobox("getValue");
		}
		
		var mtd_pengadaan_ 	= mtd; 
		var tgl_kebutuhan_ 	= $("#tgl_kebutuhan").datebox("getValue"); 
		var tgl_kebutuhan_2_ = $("#tgl_kebutuhan_2").datebox("getValue"); 
		var pilih_awal_ 	= $("#pilih_awal").datebox("getValue"); 
		var pilih_akhir_ 	= $("#pilih_akhir").datebox("getValue"); 
		var kerja_awal_ 	= $("#kerja_awal").datebox("getValue"); 
		var kerja_akhir_ 	= $("#kerja_akhir").datebox("getValue"); 
		var id_swakelola_ 	= document.getElementById('id_swakelola').value;
		var id_ 			= document.getElementById('id').value;        
        var idppk 			= document.getElementById('idppk').value;
        
        tkdn_ = 0; uk_ = 0; nuk_ = 0; pradipa_=0; aktif_ = 0; umumkan_= 0; final_ = 0;
          
        if (document.getElementById("final").checked == true){
			 final_ = "0";
		 }else{
			 final_ = "0";
		 }
        
		if (document.getElementById("tkdn").checked == true){
			 tkdn_ = "1";
		 }else{
			 tkdn_ = "0";
		 }
		 
         if (document.getElementById("uk").checked == true){
			 uk_ = "1";
		 }else{
			 uk_ = "0";
		 }
         
         if (document.getElementById("nuk").checked == true){
			 nuk_ = "1";
		 }else{
			 nuk_ = "0";
		 }
         
		if (document.getElementById("pradipa").checked == true){
			 pradipa_ = "1";
		 }else{
			 pradipa_ = "0";
		 }
		 
		if (document.getElementById("aktif").checked == true){
			 aktif_ = "1";
		 }else{
			 aktif_ = "0";
		 }
		 
		if (document.getElementById("umumkan").checked == true){
			 umumkan_ = "1";
		 }else{
			 umumkan_ = "0";
		 }
        
        if(nm_paket_==""){
            alert('Silahkan Pilih Kembali Belanja');
            exit();
        }
        
        if(idppk==""){
            alert('PPK Belum Sesuai, Silahkan Cek Master Kegiatan PPK');
            exit();
        }
        
        
        if(mtd_pengadaan_==""){
            alert('Metode Belum Sesuai, Silahkan Cek Metode Pengadaan');
            exit();   
        }

        if(tgl_kebutuhan_=="" || tgl_kebutuhan_2_=="" || pilih_awal_=="" || pilih_akhir_=="" || kerja_awal_=="" || kerja_akhir_==""){
            alert('Tanggal Paket Belum Sesuai, Silahkan Cek Tanggal Paket Pekerjaan');
            exit();   
        }

        if(lcstatus=='tambah'){
            var xurl = '<?php echo base_url(); ?>/index.php/sirup/sirup/simpanPenyedia'
        }else{
            var xurl = '<?php echo base_url(); ?>/index.php/sirup/sirup/editPenyedia';
        }
		 
        $('#dgsd').datagrid('selectAll');
				var rows = $('#dgsd').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
				    cidx     = rows[p].idx;
				    ctahun   = rows[p].tahun;
                    cklpd    = rows[p].klpd;
					ckdsd    = rows[p].kd_sd;
                    ckdpkt   = rows[p].kd_paket;
                    cisipkt  = rows[p].isi_paket;
					ckodekeg = rows[p].kd_kegiatan;
                    cnmkeg   = rows[p].nm_kegiatan;
                    ckoderek = rows[p].kd_rek5;
                    cnmrek   = rows[p].nm_rek5;					                    
                    ckdad    = rows[p].kd_ad;
					ckdads   = rows[p].kd_ads;
					cmax     = rows[p].max;
                    cvol     = rows[p].vol;
                    cpagu    = angka(rows[p].pagu);
                    cuser    = rows[p].user;
                    cjns_peng= rows[p].jns_peng;
                    cnjnspeng= rows[p].nmjns_peng;                    
					
					if (p>0) {
					csql = csql+","+"('"+id_+"','"+ctahun+"','"+cklpd+"','"+skpd_+"','"+ckdpkt+"','"+cisipkt+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cvol+"','"+cpagu+"','"+cuser+"','"+cjns_peng+"','"+cnjnspeng+"')";
					} else {
					csql = "values('"+id_+"','"+ctahun+"','"+cklpd+"','"+skpd_+"','"+ckdpkt+"','"+cisipkt+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cvol+"','"+cpagu+"','"+cuser+"','"+cjns_peng+"','"+cnjnspeng+"')";                                            
					}                                             
				}
                                
                $('#dgsd_lokasi').datagrid('selectAll');
				var rows = $('#dgsd_lokasi').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
				    cidx     = rows[p].idx;
				    cprov    = rows[p].prov;
                    clokk    = rows[p].lokasi;
					cnmlok   = rows[p].nm_lokasi;
                    cdetlok  = rows[p].det_lokasi;
                    cuser    = rows[p].user;
                    
					if (p>0) {
					csql_lok = csql_lok+","+"('"+id_+"','"+cprov+"','"+clokk+"','"+cnmlok+"','"+cdetlok+"','"+cuser+"','"+skpd_+"')";
					} else {
					csql_lok = "values('"+id_+"','"+cprov+"','"+clokk+"','"+cnmlok+"','"+cdetlok+"','"+cuser+"','"+skpd_+"')";                                            
					}                                             
				}
                
			$(document).ready(function(){
				$.ajax({
					type: "POST",       
					dataType : 'json',         
					data: ({cskpd:skpd_,ctahun:ntahun_,cpaket:nm_paket_,ckdgiat:kd_kegiatan_,ckdprog:kd_program_,clok:lokasi_,cdetlok:det_lokasi_, 	
							cjns:jns_pengadaan_,cvol:volume_,curai:uraian_,cspes:spesifikasi_,ctot:total_pagu_, 	
							cmtd:mtd_pengadaan_,ctglkeb:tgl_kebutuhan_,ctglkeb_2:tgl_kebutuhan_2_,cpilawl:pilih_awal_,cpilakhir:pilih_akhir_,ckerawalan:kerja_awal_,
							ckerakhir:kerja_akhir_,cidswa:id_swakelola_,ctkdn:tkdn_,cuk:uk_,cnuk:nuk_,cpra:pradipa_,caktif:aktif_,
							cumum:umumkan_,cnmgiat:nm_kegiatan_,cid:id_,cdet:csql,cfinall:final_,cidppk:idppk,cjamak:jamak_,crenja:renja_,cdet_lok:csql_lok}),  	
					url:xurl,
					success:function(data){
						pesan = data.pesan;
						if(pesan=='1'){
							alert("Data tersimpan");
                            keluar_app();
						}else if(pesan=='2'){
							alert("Detail Gagal Tersimpan");
						}else{
							alert("Header Gagal Tersimpan");
						}
						
					}
				});
			});
        
        
        /*
		if(lcstatus =='tambah') {
			$('#dgsd').datagrid('selectAll');
				var rows = $('#dgsd').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
				    cidx     = rows[p].idx;
				    ctahun   = rows[p].tahun;
                    cklpd    = rows[p].klpd;
					ckdsd    = rows[p].kd_sd;
                    ckdpkt   = rows[p].kd_paket;
                    cisipkt  = rows[p].isi_paket;
					ckodekeg = rows[p].kd_kegiatan;
                    cnmkeg   = rows[p].nm_kegiatan;
                    ckoderek = rows[p].kd_rek5;
                    cnmrek   = rows[p].nm_rek5;					                    
                    ckdad    = rows[p].kd_ad;
					ckdads   = rows[p].kd_ads;
					cmax     = rows[p].max;
                    cvol     = rows[p].vol;
                    cpagu    = angka(rows[p].pagu);
                    cuser    = rows[p].user;
                    cjns_peng= rows[p].jns_peng;
                    cnjnspeng= rows[p].nmjns_peng;                    
					
					if (p>0) {
					csql = csql+","+"('"+id_+"','"+ctahun+"','"+cklpd+"','"+skpd_+"','"+ckdpkt+"','"+cisipkt+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cvol+"','"+cpagu+"','"+cuser+"','"+cjns_peng+"','"+cnjnspeng+"')";
					} else {
					csql = "values('"+id_+"','"+ctahun+"','"+cklpd+"','"+skpd_+"','"+ckdpkt+"','"+cisipkt+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cvol+"','"+cpagu+"','"+cuser+"','"+cjns_peng+"','"+cnjnspeng+"')";                                            
					}                                             
				}
                
              $('#dgsd_lokasi').datagrid('selectAll');
				var rows = $('#dgsd_lokasi').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
				    cidx     = rows[p].idx;
				    cprov    = rows[p].prov;
                    clokk    = rows[p].lokasi;
					cnmlok   = rows[p].nm_lokasi;
                    cdetlok  = rows[p].det_lokasi;
                    cuser    = rows[p].user;
                    
					if (p>0) {
					csql_lok = csql_lok+","+"('"+id_+"','"+cprov+"','"+clokk+"','"+cnmlok+"','"+cdetlok+"','"+cuser+"','"+skpd_+"')";
					} else {
					csql_lok = "values('"+id_+"','"+cprov+"','"+clokk+"','"+cnmlok+"','"+cdetlok+"','"+cuser+"','"+skpd_+"')";                                            
					}                                             
				}                        
               
           	$(document).ready(function(){
       	        
				$.ajax({
					type: "POST",       
					dataType : 'json',         
					data: ({cskpd:skpd_,ctahun:ntahun_,cpaket:nm_paket_,ckdgiat:kd_kegiatan_,ckdprog:kd_program_,clok:lokasi_,cdetlok:det_lokasi_, 	
							cjns:jns_pengadaan_,cvol:volume_,curai:uraian_,cspes:spesifikasi_,ctot:total_pagu_, 	
							cmtd:mtd_pengadaan_,cpilawl:pilih_awal_,cpilakhir:pilih_akhir_,ckerawalan:kerja_awal_,
							ckerakhir:kerja_akhir_,cidswa:id_swakelola_,ctkdn:tkdn_,cuk:uk_,cnuk:nuk_,cpra:pradipa_,caktif:aktif_,
							cumum:umumkan_,cnmgiat:nm_kegiatan_,cid:id_,cdet:csql,cfinall:final_,cidppk:idppk,cjamak:jamak_,crenja:renja_,cdet_lok:csql_lok}),  	
					url:'<?php echo base_url(); ?>/index.php/sirup/sirup/savePenyedia',
					success:function(data){
						pesan = data.pesan;
						if(pesan=='1'){
							alert("Data tersimpan");
                            keluar_app();
						}else if(pesan=='1'){
							alert("Detail Gagal Tersimpan");
						}else{
							alert("Header Gagal Tersimpan");
						}
						
					}
				});
			});
        
        }else{
		  
			$('#dgsd').datagrid('selectAll');
				var rows = $('#dgsd').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
				    cidx     = rows[p].idx;
				    ctahun   = rows[p].tahun;
                    cklpd    = rows[p].klpd;
					ckdsd    = rows[p].kd_sd;
                    ckdpkt   = rows[p].kd_paket;
                    cisipkt  = rows[p].isi_paket;
					ckodekeg = rows[p].kd_kegiatan;
                    cnmkeg   = rows[p].nm_kegiatan;
                    ckoderek = rows[p].kd_rek5;
                    cnmrek   = rows[p].nm_rek5;					                    
                    ckdad    = rows[p].kd_ad;
					ckdads   = rows[p].kd_ads;
					cmax     = rows[p].max;
                    cvol     = rows[p].vol;
                    cpagu    = angka(rows[p].pagu);
                    cuser    = rows[p].user;
                    cjns_peng= rows[p].jns_peng;
                    cnjnspeng= rows[p].nmjns_peng;                    
					
					if (p>0) {
					csql = csql+","+"('"+id_+"','"+ctahun+"','"+cklpd+"','"+skpd_+"','"+ckdpkt+"','"+cisipkt+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cvol+"','"+cpagu+"','"+cuser+"','"+cjns_peng+"','"+cnjnspeng+"')";
					} else {
					csql = "values('"+id_+"','"+ctahun+"','"+cklpd+"','"+skpd_+"','"+ckdpkt+"','"+cisipkt+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cvol+"','"+cpagu+"','"+cuser+"','"+cjns_peng+"','"+cnjnspeng+"')";                                            
					}                                             
				}
                                
                $('#dgsd_lokasi').datagrid('selectAll');
				var rows = $('#dgsd_lokasi').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
				    cidx     = rows[p].idx;
				    cprov    = rows[p].prov;
                    clokk    = rows[p].lokasi;
					cnmlok   = rows[p].nm_lokasi;
                    cdetlok  = rows[p].det_lokasi;
                    cuser    = rows[p].user;
                    
					if (p>0) {
					csql_lok = csql_lok+","+"('"+id_+"','"+cprov+"','"+clokk+"','"+cnmlok+"','"+cdetlok+"','"+cuser+"','"+skpd_+"')";
					} else {
					csql_lok = "values('"+id_+"','"+cprov+"','"+clokk+"','"+cnmlok+"','"+cdetlok+"','"+cuser+"','"+skpd_+"')";                                            
					}                                             
				}
                  
			$(document).ready(function(){
				$.ajax({
					type: "POST",       
					dataType : 'json',         
					data: ({cskpd:skpd_,ctahun:ntahun_,cpaket:nm_paket_,ckdgiat:kd_kegiatan_,ckdprog:kd_program_,clok:lokasi_,cdetlok:det_lokasi_, 	
							cjns:jns_pengadaan_,cvol:volume_,curai:uraian_,cspes:spesifikasi_,ctot:total_pagu_, 	
							cmtd:mtd_pengadaan_,cpilawl:pilih_awal_,cpilakhir:pilih_akhir_,ckerawalan:kerja_awal_,
							ckerakhir:kerja_akhir_,cidswa:id_swakelola_,ctkdn:tkdn_,cuk:uk_,cnuk:nuk_,cpra:pradipa_,caktif:aktif_,
							cumum:umumkan_,cnmgiat:nm_kegiatan_,cid:id_,cdet:csql,cfinall:final_,cidppk:idppk,cjamak:jamak_,crenja:renja_,cdet_lok:csql_lok}),  	
					url:'<?php echo base_url(); ?>/index.php/sirup/sirup/editPenyedia',
					success:function(data){
						pesan = data.pesan;
						if(pesan=='1'){
							alert("Data tersimpan");
                            keluar_app();
						}else if(pesan=='1'){
							alert("Detail Gagal Tersimpan");
						}else{
							alert("Header Gagal Tersimpan");
						}
						
					}
				});
			});
		}		
	
    */
    }      

    function edit_data(){
        
        var rows = $('#dg').datagrid('getSelected');
        if(rows==null){
            alert('List Data Belum Dipilih');
            exit();
        }
        
        lcstatus = 'edit';
        judul = 'Penyedia';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("nomor").disabled=true;
        
        var skpd = dskpd;
        var id = did;
        var usern = document.getElementById('nm_username').value;
        
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sirup/sirup/detailPenyedia',
                data: ({skpd:skpd,id:id,user:usern}),
                dataType:"json",
                success:function(data){
                    $('#dgsd').datagrid('loadData',[]);
                    $('#dgsd').edatagrid('reload');
                    $.each(data,function(i,n){          
                    idx     = n['idx'];    
                    tahun   = n['tahun'];
                    klpd    = n['klpd'];     
                    kd_paket= n['kd_paket'];
                    isi_paket= n['isi_paket'];               
                    kd_sd   = n['kd_sd'];
                    kd_ad  	= n['kd_ad'];
                    kd_ads 	= n['kd_ads'];
                    max  	= n['max'];
                    kd_keg  = n['kd_kegiatan'];
                    nm_keg  = n['nm_kegiatan'];
                    kd_rek  = n['kd_rek5'];
                    nm_rek  = n['nm_rek5'];                    
                    vol     = n['vol']; 
                    pagu 	= n['pagu'];
                    user    = n['user'];
                    cjnspeng= n['jnsp'];
                    cnjnspeng= n['nmjnsp'];
                                                                                                        
                    $('#dgsd').edatagrid('appendRow',{idx:idx,klpd:klpd,tahun:tahun,kd_paket:kd_paket,isi_paket:isi_paket,kd_sd:kd_sd,kd_ad:kd_ad,kd_ads:kd_ads,max:max,kd_kegiatan:kd_keg,nm_kegiatan:nm_keg,kd_rek5:kd_rek,nm_rek5:nm_rek,vol:vol,pagu:pagu,user:user,jns_peng:cjnspeng,nmjns_peng:cnjnspeng});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });
           
           
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sirup/sirup/detailPenyedia_lokasi',
                data: ({skpd:skpd,id:id,user:usern}),
                dataType:"json",
                success:function(data){
                    $('#dgsd_lokasi').datagrid('loadData',[]);
                    $('#dgsd_lokasi').edatagrid('reload');
                    $.each(data,function(i,n){                                    
                    user        = n['user'];
                    prov        = n['prov'];
                    nm_lokasi   = n['nm_lokasi'];
                    det_lokasi  = n['det_lokasi'];                                        
                    lokasi   = n['lokasi'];                    
                                                                                                        
                    $('#dgsd_lokasi').edatagrid('appendRow',{user:user,prov:prov,lokasi:lokasi,nm_lokasi:nm_lokasi,det_lokasi:det_lokasi});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });
           
        }    
        
    function cetak_item(){
        
        var rows = $('#dg').datagrid('getSelected');
        if(rows==null){
            alert('List Data Belum Dipilih');
            exit();
        }
        
        judul = 'Paket Penyedia';
        $("#dialog-modal-lihat").dialog({ title: judul });
        $("#dialog-modal-lihat").dialog('open');
        
        var skpd = dskpd;
        var id = did;
        var usern = document.getElementById('nm_username').value;
        
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sirup/sirup/detailPenyedia',
                data: ({skpd:skpd,id:id,user:usern}),
                dataType:"json",
                success:function(data){
                    $('#dgsd_lihat').datagrid('loadData',[]);
                    $('#dgsd_lihat').edatagrid('reload');
                    $.each(data,function(i,n){    
                    idx     = n['idx'];    
                    tahun   = n['tahun'];
                    kd_paket= n['kd_paket'];
                    isi_paket= n['isi_paket'];
                    klpd    = n['klpd'];                    
                    kd_sd   = n['kd_sd'];
                    kd_ad  	= n['kd_ad'];
                    kd_ads 	= n['kd_ads'];
                    max  	= n['max'];
                    kd_keg  = n['kd_kegiatan'];
                    nm_keg  = n['nm_kegiatan'];
                    kd_rek  = n['kd_rek5'];
                    nm_rek  = n['nm_rek5'];                    
                    vol     = n['vol']; 
                    pagu 	= n['tm_pagu'];                    
                    user    = n['user'];
                    cjnspeng= n['jnsp'];
                    cnjnspeng= n['nmjnsp'];
                                                                                                                        
                    $('#dgsd_lihat').edatagrid('appendRow',{idx:idx,klpd:klpd,tahun:tahun,kd_paket:kd_paket,isi_paket:isi_paket,kd_sd:kd_sd,kd_ad:kd_ad,kd_ads:kd_ads,max:max,kd_kegiatan:kd_keg,nm_kegiatan:nm_keg,kd_rek5:kd_rek,nm_rek5:nm_rek,vol:vol,pagu:pagu,user:user,jns_peng:cjnspeng,nmjns_peng:cnjnspeng});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });
           
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sirup/sirup/detailPenyedia_lokasi',
                data: ({skpd:skpd,id:id,user:usern}),
                dataType:"json",
                success:function(data){
                    $('#dgsd_lihat_lokasi').datagrid('loadData',[]);
                    $('#dgsd_lihat_lokasi').edatagrid('reload');
                    $.each(data,function(i,n){                                    
                    user        = n['user'];
                    prov        = n['prov'];
                    nm_lokasi   = n['nm_lokasi'];
                    det_lokasi  = n['det_lokasi'];                                        
                    lokasi   = n['lokasi'];                    
                                                                                                        
                    $('#dgsd_lihat_lokasi').edatagrid('appendRow',{user:user,prov:prov,lokasi:lokasi,nm_lokasi:nm_lokasi,det_lokasi:det_lokasi});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });
           
        }
    
    function tambah(){
                
        lcstatus = 'tambah';
        judul = 'Penyedia';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        
        } 
     
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function keluar_lihat(){
        $("#dialog-modal-lihat").dialog('close');
     } 
     
     function keluar_app(){
        var url = '<?php echo site_url(); ?>/sirup/sirup/input_penyedia_myears';
            window.open(url, '_self');
            window.focus();  
     }
    
     function hapus(){
        
        var rows = $('#dg').datagrid('getSelected');
        if(rows==null){
            alert('List Data Belum Dipilih');
            exit();
        }
        
		var id     = document.getElementById('id').value;
        var kode   = $('#skpd').combogrid('getValue');
        var urll = '<?php echo base_url(); ?>index.php/sirup/sirup/hapusPenyedia';
        $(document).ready(function(){
         $.post(urll,({no:id,skpd:kode}),function(data){
            xstatus = data;
            if (xstatus=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                var url = '<?php echo site_url(); ?>/sirup/sirup/input_penyedia_myears';
                window.open(url, '_self');
                exit();
            }
         });
        });

        
        
    } 
    
       
    function addCommas(nStr)
    {
    	nStr += '';
    	x = nStr.split(',');
        x1 = x[0];
    	x2 = x.length > 1 ? ',' + x[1] : '';
    	var rgx = /(\d+)(\d{3})/;
    	while (rgx.test(x1)) {
    		x1 = x1.replace(rgx, '$1' + '.' + '$2');
    	}
    	return x1 + x2;
    }
    
     function delCommas(nStr)
    {
    	nStr += ' ';
    	x2 = nStr.length;
        var x=nStr;
        var i=0;
    	while (i<x2) {
    		x = x.replace(',','');
            i++;
    	}
    	return x;
    }
  
    function get_nourut(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/sirup/sirup/urutPenyedia',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			$("#id").attr("value",data.no_urut);
        		}                                     
        	});  
        }
	
        var editIndex = undefined;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#dgsd').datagrid('validateRow', editIndex)){
                $('#dgsd').datagrid('endEdit', editIndex);
                editIndex = undefined;
                return true;
            } else {
                return false;
            }
        }
        function onClickCell(index, field){
            if (editIndex != index){
                if (endEditing()){
                    $('#dgsd').datagrid('selectRow', index)
                            .datagrid('beginEdit', index);
                    var ed = $('#dgsd').datagrid('getEditor', {index:index,field:field});
                    if (ed){
                        ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                    }
                    editIndex = index;
                } else {
                    setTimeout(function(){
                        $('#dgsd').datagrid('selectRow', editIndex);
                    },0);
                }
            }
        }
        function onEndEdit(index, row){
            var ed = $(this).datagrid('getEditor', {
                index: index,
                field: 'kd_sd'
            });
            row.nm_sd = $(ed.target).combobox('getText');
        }
        function append(){
            if (endEditing()){
                $('#dgsd').datagrid('appendRow',{status:'P'});
                editIndex = $('#dgsd').datagrid('getRows').length-1;
                $('#dgsd').datagrid('selectRow', editIndex)
                        .datagrid('beginEdit', editIndex);
            }
        }
        
        function removeit(){
            var selectedrow = $("#dgsd").datagrid("getSelected");
            var rowIndex = $("#dgsd").datagrid("getRowIndex", selectedrow);
            if(rowIndex<0){
                alert('List Belum Dipilih'); 
            }else{
                $('#dgsd').datagrid('deleteRow',rowIndex); 
            }
            
            gpagu = 0; gvol = 0;
            $('#dgsd').datagrid('selectAll');
				var rows = $('#dgsd').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){				    
					gpagu    = gpagu+angka(rows[p].pagu);
                    gvol     = gvol+rows[p].vol;					                                             
				} 
              
             $("#total_pagu").attr("value",number_format(gpagu,2));
             $("#volume").attr("value",gvol); 
             $("#jumvolume").attr("value",gvol);            
             $('#nm_paket').combogrid('setValue',''); 
             $('#nm_paket_detail').combogrid('setValue',''); 
             $('#jns_pengadaan').combogrid('setValue','');

            $("#paket_kecuali").attr("checked",false);
            $('#sd2').hide();$('#paket2').hide();
            document.getElementById("nm_paket_detailx").value='';
            document.getElementById("nm_paket_detailx_vol").value='';
            document.getElementById("nm_paket_detailx_pagu").value='';
            document.getElementById("nsumber").value='';
            $('#nm_paket_detailx_sdana').combogrid('setValue','');  
            $('#nm_paket_detailx_sdana_x').combogrid('setValue','');
             
        }
        
        function removeit_lokasi(){
            var selectedrow = $("#dgsd_lokasi").datagrid("getSelected");
            var rowIndex = $("#dgsd_lokasi").datagrid("getRowIndex", selectedrow);
            if(rowIndex<0){
                alert('List Belum Dipilih'); 
            }else{
                $('#dgsd_lokasi').datagrid('deleteRow',rowIndex); 
            }             
        }
        
        function accept(){
            if (endEditing()){
                $('#dgsd').datagrid('acceptChanges');
            }
        }
        function reject(){
            $('#dgsd').datagrid('rejectChanges');
            editIndex = undefined;
        }
        function getChanges(){
            var rows = $('#dgsd').datagrid('getChanges');
            alert(rows.length+' Baris telah di Edit');
        }
        
        function buka_rek(){
            judul = 'Sumber Dana';
            $("#dialog-modal-rek").dialog({ title: judul });
            $("#dialog-modal-rek").dialog('open');
            
        }
        
        function tambah_rek(){
            kkrek = $('#nm_paket').combogrid('getValue');
            var cek = document.getElementById('paket_kecuali').checked;
			
            if(kkrek==''){
                alert('Belanja Tidak Boleh Kosong');
            }else{
				
			
            ctotal_paket = angka(document.getElementById('nilai_totalpaket').value);
            ctotal_paket_tm = document.getElementById('nilai_totalpaket').value;
            ctotal_paket_myears = angka(document.getElementById('nilai_totalpaket_myears').value);
            ckd_paket = document.getElementById("kd_paket_detail").value;
            cisi_paket = $('#nm_paket_detail').combogrid('getValue'); 
            cthun = $('#ntahun').combogrid('getValue');        
            cklpd = document.getElementById("klpd").value;     
            cvol = angka(document.getElementById("jumvolume").value);       
            cvol_total = angka(document.getElementById("volume").value);          
            cskpd = $('#skpd').combogrid('getValue');            
            ckkeg = $('#kd_kegiatan').combogrid('getValue');
            cnkeg = document.getElementById("nm_kegiatan").value;
            ckrek = $('#koderek').combogrid('getValue');
            cnrek = document.getElementById("namarek").value;
            cmak  = ckkeg+"."+ckrek;
            csumb = document.getElementById("nsumber").value;
            cpagu = document.getElementById("npagu").value;
            dpagu = angka(document.getElementById("npagu").value);
            hpagu = angka(document.getElementById('total_pagu').value); 
            hpagu_total = angka(document.getElementById('total_pagu_myears').value);
            hjns_peng = $('#jns_pengadaan').combogrid('getValue');
            hnmjns_peng = document.getElementById('nmjns_pengadaan').value;
            huser = document.getElementById('userppk').value;
            
            dtotalkeg = angka(document.getElementById("nilai_totalkeg").value);
            dtotalsrp = angka(document.getElementById("nilai_totalsirup").value);           
            gvol = 0;
            
			if(hjns_peng==""){
                alert('Jenis Pengadaan Belum Dipilih');
                exit();
            }	
			
			if(cek==true){
				kisi_x = document.getElementById("nm_paket_detailx").value;
                kvol_x = document.getElementById("nm_paket_detailx_vol").value;
                kdpagu_x = document.getElementById("nm_paket_detailx_pagu").value;
                kpagu_x = angka(document.getElementById("nm_paket_detailx_pagu").value);
                ksdana_x = $('#nm_paket_detailx_sdana').combogrid('getValue');
                
                if(kisi_x=="" || kvol_x=="" || kpagu_x=="" || ksdana_x==""){
                alert('Isian Paket Belum Lengkap !');
                exit();
                }   
                
            //isi
            kvol_x = parseInt(kvol_x, 10);

            $('#dgsd').datagrid('selectAll');
                var rows = $('#dgsd').datagrid('getSelections');           
                for(var p=0;p<rows.length;p++){
                    
                    cek_kd_paket    = rows[p].kd_paket;
                    cek_cisi_paket  = rows[p].isi_paket;
                    cek_kd_rek5     = rows[p].kd_rek5;
                    //gvol            = gvol+rows[p].vol;
                    if(cek_kd_paket==ckd_paket && cek_cisi_paket==cisi_paket && cek_kd_rek5==ckrek){
                        alert('Isi Paket Sudah Ditambahkan');
                        exit();
                    }               
                    
                    if(cek_kd_rek5!=ckrek){
                        alert('Isi Paket dan Belanja Tidak Sesuai');
                        exit();
                    }                                                
                }
            
            tovol = cvol_total + kvol_x;                
            hpagu = hpagu + kpagu_x;
            hpagu_total = hpagu_total + kpagu_x;
            ntotal_sirup = dtotalsrp + hpagu;
            jgrid = rows.length;

            //alert(hpagu);
            //alert(ctotal_paket_myears);
            //alert(kpagu_x);
            //alert(ctotal_paket);
            //alert(hpagu_total);
            if(kpagu_x>ctotal_paket){
                alert('Isi Paket Melebihi Aturan Inputan, Nilai Belanja = '+ctotal_paket_tm);
                exit();
            }

            if(hpagu>ctotal_paket_myears){
                alert('Isi Paket Melebihi Aturan Inputan, Nilai Total Belanja = '+ctotal_paket_myears);
                exit();
            }
           
            if(ntotal_sirup>dtotalkeg){
                alert('Isi Paket Melebihi Aturan Inputan Kegiatan');
                exit();
            }
            pidx = jgrid;
            pidx = pidx + 1;
            $("#total_pagu").attr("value",number_format(hpagu,2));
            $("#total_pagu_myears").attr("value",number_format(hpagu_total,2));
            $("#volume").attr("value",tovol);            
            
            $('#dgsd').edatagrid('appendRow',{idx:pidx,tahun:cthun,kd_paket:ckd_paket,isi_paket:kisi_x,klpd:cklpd,kd_sd:ksdana_x,kd_ad:ksdana_x,kd_ads:cskpd,max:cmak,kd_kegiatan:ckkeg,nm_kegiatan:cnkeg,kd_rek5:ckrek,nm_rek5:cnrek,vol:kvol_x,pagu:kdpagu_x,jns_peng:hjns_peng,nmjns_peng:hnmjns_peng,user:huser});
            
             accept();  

			}else{
			//normal
			
			sd_baru = $('#nm_paket_detailx_sdana_x').combogrid('getValue');
            
            if(csumb==''){
                alert('Silahkan Isi Sumber Dana');
                $('#sd2').show();
                exit();             
            }
            
            $('#dgsd').datagrid('selectAll');
                var rows = $('#dgsd').datagrid('getSelections');           
                for(var p=0;p<rows.length;p++){
                    
                    cek_kd_paket    = rows[p].kd_paket;
                    cek_cisi_paket  = rows[p].isi_paket;
                    cek_kd_rek5     = rows[p].kd_rek5;
                    //gvol            = gvol+rows[p].vol;
                    if(cek_kd_paket==ckd_paket && cek_cisi_paket==cisi_paket && cek_kd_rek5==ckrek){
                        alert('Isi Paket Sudah Ditambahkan');
                        exit();
                    }               
                    
                    if(cek_kd_rek5!=ckrek){
                        alert('Isi Paket dan Belanja Tidak Sesuai');
                        exit();
                    }                                                
                }
            
            tovol= cvol_total + cvol;                
            hpagu = hpagu + dpagu;
            ntotal_sirup = dtotalsrp + hpagu;
            jgrid = rows.length;
            if(hpagu>ctotal_paket){
                alert('Isi Paket Melebihi Aturan Inputan, Nilai Belanja = '+ctotal_paket_tm);
                exit();
            }
            if(ntotal_sirup>dtotalkeg){
                alert('Isi Paket Melebihi Aturan Inputan Kegiatan');
                exit();
            }
            pidx = jgrid;
            pidx = pidx + 1;
            $("#total_pagu").attr("value",number_format(hpagu,2));
            $("#volume").attr("value",tovol);            
            $('#dgsd').edatagrid('appendRow',{idx:pidx,tahun:cthun,kd_paket:ckd_paket,isi_paket:cisi_paket,klpd:cklpd,kd_sd:csumb,kd_ad:csumb,kd_ads:cskpd,max:cmak,kd_kegiatan:ckkeg,nm_kegiatan:cnkeg,kd_rek5:ckrek,nm_rek5:cnrek,vol:cvol,pagu:cpagu,jns_peng:hjns_peng,nmjns_peng:hnmjns_peng,user:huser});
            
            accept();
			


			/*if(sd_baru!=""){
				$('#sd2').hide();
				//$('#nm_paket_detailx_sdana_x').combogrid('setValue','');
			}*/
			
			//batas normal
			}
			
            $("#paket_kecuali").attr("checked",false);
            $('#sd2').hide();$('#paket2').hide();

            //$('#nm_paket').combogrid('setValue',''); 
            $('#nm_paket_detail').combogrid('setValue',''); 
            $('#jns_pengadaan').combogrid('setValue','');  
			document.getElementById("nm_paket_detailx").value='';
			document.getElementById("nm_paket_detailx_vol").value='';
			document.getElementById("nm_paket_detailx_pagu").value='';
			document.getElementById("nsumber").value='';
			$('#nm_paket_detailx_sdana').combogrid('setValue','');	
            $('#nm_paket_detailx_sdana_x').combogrid('setValue','');
            }
        }
        
        function tambah_lokasi(){
            klokasi = $('#lokasi').combogrid('getValue');
            mlokasi = document.getElementById("nmlokasi").value;             
            nlokasi = document.getElementById("det_lokasi").value;             
            huser = document.getElementById('userppk').value;
            
            if(klokasi==''){
                alert('Lokasi Paket Tidak Boleh Kosong');
            }else{
            
             $('#dgsd_lokasi').datagrid('selectAll');
				var rows = $('#dgsd_lokasi').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
				    
					cek_lokasi    = rows[p].nm_lokasi;
                    cek_detlokasi    = rows[p].det_lokasi;
                    
                    if(cek_lokasi==mlokasi && cek_detlokasi==nlokasi){
                        alert('Lokasi Paket Sudah Ditambahkan');
                        exit();
                    }				                                        	                                             
				}
                
            var prov = "Kalimantan barat";
            
            jgrid = rows.length;
            pidx = jgrid;
            pidx = pidx + 1;
            
            $('#dgsd_lokasi').edatagrid('appendRow',{id:pidx,prov:prov,lokasi:klokasi,nm_lokasi:mlokasi,det_lokasi:nlokasi,user:huser});
            accept();
            }
        }
        
        function tutup_rek(){
            $("#dialog-modal-rek").dialog('close');
        }
        
        function cetak(){
            var url = '<?php echo site_url(); ?>/sirup/sirup/cetak_listpenyedia';
            window.open(url, '_blank');
            window.focus();            
        }
		
		function cetak_rup(){
			norup = document.getElementById('tm_idrup').value;
            var url = '<?php echo site_url(); ?>/sirup/sirup/cetak_listpenyedia/'+norup+'/Paket Penyedia';
            window.open(url, '_blank');
            window.focus();            
        }
		
		function runEffect(){
			var cek = document.getElementById('is_kecuali').checked;
			if(cek==true){
				$("#kecuali").hide();
			}else{
				$("#kecuali").show();
			}
		}
		
		
		function runEffect_1(){
			var cek = document.getElementById('paket_kecuali').checked;
			if(cek==true){
				//$("#paket1").hide();
				$("#paket2").show();
			}else{
				//$("#paket1").show();
				$("#paket2").hide();
			}
		}

        function runTambahpaket(){
            alert("Tambah Paket ini Digunakan untuk Keperluan Paket Multiyears, dalam keadaan/kondisi tertentu dan mengikuti aturan yang berlaku.");
        }
		
        function runSubpaket(){
            alert("Kode ( 0 ) adalah Total Sub Rincian Objek, Digunakan apabila 1 paket bernilai ( = ) sama dengan Rekening Belanja.");
        }
		
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">PAKET PENYEDIA MULTIYEARS</a></b></u></h3>
    <div>
    <p align="right">          
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a class="easyui-linkbutton" id="vhapus" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" id="vedit" iconCls="icon-edit" plain="true" onclick="javascript:edit_data();">Edit</a>        
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cetak_item()">Lihat</a>                               
        <!--<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak()">Cetak </a>--> | &nbsp;                      
        <input type="text" value="" id="txtcari" placeholder="Tulis Nama Paket Atau IDRUP" style="width: 190px;"/>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a> | &nbsp;
		Kegiatan <input type="text" id="homekd_kegiatan" style="width: 170px;"/>
        <table id="dg" title="Listing Data Paket Penyedia" style="width:870px;height:450px;" >  
        </table>
    </p> 
	Note : Warna Biru (sudah di Finalisasi Draf oleh PPKOM)
    </div>
</div>
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
			<tr>
                <td><input type="hidden" id="id" name="id" style="width: 140px;" readonly="true" />
            </tr>
            <tr>
                <td>PPKOM / USER</td>
                <td></td>
                <td><input id="idppk" name="idppk" style="width: 170px;" type="hidden" /><input id="userppk" name="userppk" style="width: 170px;" type="hidden" />
				<input type="text" id="nmppk" style="border:0;width: 300px;" readonly="true"/></td>                            
            </tr>            
            <tr>
                <td>SKPD</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 170px;" />
				<input type="text" id="nmskpd" style="border:0;width: 500px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Tahun</td>
                <td></td>
                <td>
                <input id="ntahun" name="ntahun" style="width: 170px;" />                
                </td>                            
            </tr>            
            <tr>
                <td>Kegiatan</td>
                <td></td>
                <td><input type="text" id="kd_kegiatan" style="width: 170px;"/>
				<input type="text" id="nm_kegiatan" style="border:0;width: 500px;" readonly="true"/>
				<input type="hidden" id="kd_program" style="border:0;width: 400px;" readonly="true" />
                <input type="hidden" id="nilai_totalkeg" style="width: 170px; "/>
                <input type="hidden" id="nilai_totalsirup" style="width: 170px;"/>
                
                </td>
            </tr>
            <tr>
                <td>Belanja</td>
                <td></td>
                <td><input type="text" id="nm_paket" style="width: 610px;" /> </td>                
            </tr>
			<tr>
                <td>Nama Paket</td>
                <td></td>
                <td><input type="hidden" id="kd_paket_detail" style="width: 170px;"/>       
					<input type="text" id="nm_paket_detail" style="width: 610px;" /> 
                    <a class="easyui-linkbutton" iconCls="icon-help" title="Info Input Paket" plain="true" onclick="javascript:runSubpaket();"></a><br/>
					<input type="checkbox" id="paket_kecuali" onclick="javascript:runEffect_1();"/> &nbsp; Tambah Paket Multiyears
                    <a class="easyui-linkbutton" iconCls="icon-help" title="Info Tambah Paket Multiyears" plain="true" onclick="javascript:runTambahpaket();"></a>
                </td>                
            </tr>
			<tr>
				<td colspan="3">
					<div id="paket2">
					<table width="100%"> 
						<tr>
							<td width="17%"></td>
							<td>&nbsp;</td>
							<td>
							<input type="hidden" id="kd_paket_detailx" style="width: 170px;"/> 
							Nama Paket	
							<input type="text" id="nm_paket_detailx" style="width: 550px;" /> <br/><br/>
							Vol&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" id="nm_paket_detailx_vol" placeholder="Hanya Angka" style="width: 100px;" /> 
							&nbsp;&nbsp;&nbsp;&nbsp;Pagu
							<input type="text" id="nm_paket_detailx_pagu" style="width: 200px; text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))"/> 
							&nbsp;&nbsp;&nbsp;&nbsp;Sumber Dana
							<input type="text" id="nm_paket_detailx_sdana" style="width: 100px;" />
							</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
			<tr>
                <td>Jenis Pengadaan</td>
                <td></td>
                <td><input type="text" id="jns_pengadaan" style="width:170px;" /><input type="hidden" id="nmjns_pengadaan" style="width:170px;" /> </td>                
            </tr> 
            <tr>
				<td colspan="3">
					<div id="sd2">
					<table width="100%"> 
						<tr>
							<td width="17%"></td>
							<td>&nbsp;</td>
							<td>Sumber Dana
							<input type="text" id="nm_paket_detailx_sdana_x" style="width: 80px;" /> <font color="red">*) Silahkan diisi dikarenakan pada RKA = Kosong</font>
							</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
			<tr>
                <td></td>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="tambah_rek()">Tambah</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Hapus</a>
				</td>                
            </tr> 
            <tr>
                <td colspan="3"><table id="dgsd" class="easyui-datagrid" title="Paket" style="width:900px;height:110px;">
				<div id="tb" style="height:auto">
					<!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="buka_rek()">Tambah Rekening</a>					
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Tambah</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Simpan Rincian</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Undo</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">Cek</a>-->
                    
                    <!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="tambah_rek()">Tambah Rekening</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Hapus</a>-->
					
				</div>
				</table>
			</td>                
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td align="right">Total : <input type="text" id="total_pagu" name="total_pagu" style="width: 170px; text-align: right" />&nbsp;   
                                          <input type="hidden" id="nilai_totalpaket" name="nilai_totalpaket"/>
                                          <input type="hidden" id="total_pagu_myears" name="total_pagu_myears" style="width: 170px; text-align: right"/>
                                          <input type="hidden" id="nilai_totalpaket_myears" name="nilai_totalpaket_myears"/>              
                </td> 
            </tr> 			           
			<tr>
                <td>Lokasi Pekerjaan</td>
                <td></td>
                <td><input type="text" id="lokasi" name="lokasi" style="width: 170px;" />
                    <input type="hidden" id="nmlokasi" name="nmlokasi" style="width: 170px;" />
                    <input type="hidden" id="klpd" name="klpd" style="width: 170px;" />
                </td> 
            </tr>
            <tr>
                <td>Detail Lokasi</td>
                <td></td>
                <td><input type="text" id="det_lokasi" style="width: 700px;" /> </td>                
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="tambah_lokasi()">Tambah</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit_lokasi()">Hapus</a>
				</td>                
            </tr> 
           
            <tr>
                <td colspan="3"><table id="dgsd_lokasi" class="easyui-datagrid" title="Lokasi Pekerjaan" style="width:900px;height:110px;">
				</table>
			</td>                
            </tr> 
            <tr>
                <td>Volume Pekerjaan</td>
                <td></td>
                <td><input type="text" id="volume" style="width: 170px;" align="right" readonly="true" />
                <input type="hidden" id="jumvolume" style="width: 170px;" align="right" /> </td>                
            </tr> 
            <tr>
                <td>Uraian Pekerjaan</td>
                <td></td>
                <td><input type="text" id="uraian" style="width: 700px;" /> </td>                
            </tr> 
            <tr>
                <td>Spesifikasi Pekerjaan</td>
                <td></td>
                <td>
                <textarea id="spesifikasi" rows="4" cols="92">                
                </textarea>                
                </td>                
            </tr> 
            <tr>
                <td></td>
                <td></td>
                <td><input type="checkbox" id="tkdn"/> &nbsp; Produk Dalam Negeri <!--<a title="X" class="easyui-linkbutton" data-options="iconCls:'icon-info'" ></a>--></td>                
            </tr> 
            <tr>
                <td></td>
                <td></td>
                <td><input type="checkbox" id="uk"/> &nbsp; Usaha Kecil</td>                
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><input type="checkbox" id="nuk"/> &nbsp; Bukan Usaha Kecil</td>                
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><input type="checkbox" id="pradipa"/> &nbsp; Pra DIPA/DPA 
                &nbsp;&nbsp; No KUAPPAS <input type="text" id="no_renja" style="width: 180px;" placeholder="Di isi jika pilih Pra DIPA/DPA" />
                &nbsp;&nbsp; No Izin Tahun Jamak <input type="text" id="no_jamak" style="width: 180px;" placeholder="Di isi jika Tahun Jamak/MOU" />
                </td>                
            </tr>
            <tr hidden="true">
                <td></td>
                <td></td>
                <td><input type="checkbox" id="final" /> &nbsp; (klik) Jika Paket Sudah Final</td>                
            </tr> 
			<tr >
                <td></td>
                <td></td>
                <td><input type="checkbox" id="is_kecuali" onclick="javascript:runEffect();"/> &nbsp; Pengadaan Dikecualikan</td>                
            </tr>
				
            <tr>
                <td colspan="3">
				<div id="kecuali">
				<table>
					<tr>
						<td>Rencana Metode Pemilihan</td>
						<td></td>
						<td><input type="text" id="mtd_pengadaan" style="width: 200px;" /></td>
					</tr>
				</table>
				</div>
				</td>           
            </tr>
				
			<tr>
                <td>Pemanfaatan Barang/Jasa</td>
                <td></td>
                <td><input type="text" id="tgl_kebutuhan" style="width: 140px;" /> s/d <input type="text" id="tgl_kebutuhan_2" style="width: 140px;" /></td>                
            </tr>
            <tr>
                <td>Bulan Pemilihan</td>
                <td></td>
                <td><input type="text" id="pilih_awal" style="width: 140px;" /> s/d <input type="text" id="pilih_akhir" style="width: 140px;"/> </td>                
            </tr> 
             <tr>
                <td>Bulan Pekerjaan</td>
                <td></td>
                <td><input type="text" id="kerja_awal" style="width: 140px;" /> s/d <input type="text" id="kerja_akhir" style="width: 140px;"> </td>                
            </tr> 
            <tr hidden="true">
                <td>Aktif</td>
                <td></td>
                <td><input type="checkbox" id="aktif"  /> </td>                
            </tr> 
            <tr hidden="true">
                <td>Umumkan</td>
                <td></td>
                <td><input type="checkbox" id="umumkan"  /> </td>                
            </tr> 
            <tr hidden="true"> 
                <td>ID Swakelola</td>
                <td></td>
                <td><input type="text" id="id_swakelola" style="width: 140px;" /> </td>                
            </tr> 
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();cari();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

<div id="dialog-modal-lihat" title="">
    <fieldset>
     <table align="center" style="width:100%;" border="0">
			<tr>
                <td></td>
                <td></td>
                <td><input type="text" id="tmket_final" style="width: 600px; border:none; color:red; font-weight:bold;" readonly="true"/> </td>                
            </tr>
			<tr>
                <td><b>ID RUP</b></td>
                <td>:</td>
                <td><input type="text" id="tm_idrup" style="width: 600px; border:none; font-weight: bold;" readonly="true"/>
                    <input type="hidden" id="nm_username" style="width: 300px; border:none;" readonly="true"/>
                 </td>                
            </tr>
			<tr>
                <td><b>PPKOM</b></td>
                <td>:</td>
                <td><input type="text" id="tm_ppk" style="width: 600px; border:none; font-weight: bold;" readonly="true"/> </td>                
            </tr> 
            <tr>
                <td><b>Nama Paket</b></td>
                <td>:</td>
                <td><input type="text" id="tm_nm_paket" style="width: 700px; border:none; font-weight: bold;" readonly="true"/> </td>                
            </tr>             
            <tr>
                <td>KLPD</td>
                <td>:</td>
                <td><input type="text" id="tm_nm_paket_kldi" style="width: 600px; border:none;" readonly="true"/> </td>                
            </tr>                         
            <tr>
                <td>Satuan Kerja</td>
                <td>:</td>
                <td><input type="text" id="tm_nmskpd" style="border:none; width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Tahun</td>
                <td>:</td>
                <td>
                <input id="tm_ntahun" style="width: 170px; border:none;" />                
                </td>                            
            </tr>
            <tr>
                <td colspan="3"><table id="dgsd_lihat_lokasi" class="easyui-datagrid" title="Lokasi Pekerjaan" style="width:930px;height:90px;">
				</table>
			</td>                
            </tr>            
            <tr>
                <td>Volume Pekerjaan</td>
                <td>:</td>
                <td><input type="text" id="tm_volume" style="width: 170px; border:none;" align="right" /> </td>                
            </tr> 
            <tr>
                <td>Uraian Pekerjaan</td>
                <td>:</td>
                <td><input type="text" id="tm_uraian" style="width: 600px; border:none;" /> </td>                
            </tr> 
            <tr>
                <td>Spesifikasi Pekerjaan</td>
                <td>:</td>
                <td>
                <textarea id="tm_spesifikasi" style="width: 600px; border:none;">                
                </textarea>                
                </td>                
            </tr> 
            <tr>
                <td colspan="3">
                Produk Dalam Negeri:&nbsp;<input type="text" id="tm_tkdn" style="width:100px; border:none;"/>&nbsp;
                Usaha Kecil &nbsp;&nbsp; :&nbsp;<input type="text" id="tm_uk" style="width:100px; border:none;"/>&nbsp;                
                </td>                
            </tr> 
            <tr>
                <td colspan="3">
                Bukan Usaha Kecil &nbsp;&nbsp; :&nbsp;<input type="text" id="tm_nuk" style="width:100px; border:none;"/>&nbsp;
                Pra DIPA/DPA :&nbsp;<input type="text" id="tm_pradipa" style="width:100px; border:none;"/>
                No. KUAPPAS :&nbsp;<input type="text" id="tm_norenja" style="width:100px; border:none;"/>
                Izin Tahun Jamak :&nbsp;<input type="text" id="tm_noizin" style="width:100px; border:none;"/>                
                </td>
            </tr>
            <tr>
                <td colspan="3"><table id="dgsd_lihat" class="easyui-datagrid" title="Paket" style="width:930px;height:90px;">				
				</table>
			</td>                
            </tr> 	
            <tr bgcolor="#ffb3b3" >
                <td></td>
                <td></td>
                <td align="right">Total Pagu : <input type="text" id="tm_total_pagu" style="width: 170px; text-align: right; border:none;"  /> &nbsp;</td>                
            </tr> 
			<tr>
                <td>Metode Pemilihan</td>
                <td>:</td>
                <td><input type="text" id="tm_mtd_pengadaan" style="width: 200px; border:none;" /> </td>                
            </tr> 
			<tr>
                <td>Waktu</td>
                <td>:</td>
                <td style="border:1px solid black;">
				<table width="100%">
					<tr >
					<td>
						<tr >
							<td width="25%">Pemanfaatan Barang/Jasa</td>
							<td>:</td>
							<td><input type="text" id="tm_tgl_kebutuhan" style="width: 100px; border:none;" /> s/d <input type="text" id="tm_tgl_kebutuhan_2" style="width: 100px; border:none;" /></td>                
						</tr> 
						<tr>
							<td>Pelaksanaan Kontrak</td>
							<td>:</td>
							<td><input type="text" id="tm_kerja_awal" style="width: 100px; border:none;" /> s/d <input type="text" id="tm_kerja_akhir" style="width: 100px; border:none;" /> </td>                
						</tr> 
						<tr >
							<td>Pemilihan Penyedia</td>
							<td>:</td>
							<td><input type="text" id="tm_pilih_awal" style="width: 100px; border:none;" /> s/d <input type="text" id="tm_pilih_akhir" style="width: 100px; border:none;" /> </td>                
						</tr>
					</td>
					</tr>
				</table>
				
				
				</td>                
            </tr> 
			<tr>
                <td colspan="3" align="center">
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_lihat();cari();">Tutup</a>
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_rup()">Cetak </a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>


<div id="dialog-modal-rek" title="">
    <!--<p class="validateTips">Semua Inputan Harus Di Isi.</p>--> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
        <tr>
            <td>Rekening</td>
            <td></td>
            <td><input type="text" id="koderek" name="koderek" style="width: 150px;" /> </td>                
        </tr>            
		<tr>
            <td>Uraian</td>
            <td></td>
            <td><input type="text" id="namarek" name="namarek" style="width: 450px;" readonly="true"/> </td>                
        </tr>
        <tr>
            <td>Sumber Dana</td>
            <td></td>
            <td><input type="text" id="nsumber" name="nsumber" style="width: 250px;" readonly="true"/> </td>                
        </tr>
        <tr>
            <td>Pagu</td>
            <td></td>
            <td><input type="text" id="npagu" name="npagu" style="width: 150px; align:right;" readonly="true"/> </td>                
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>                
        </tr>	
        <tr>
            <td></td>
            <td></td>
            <td>
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:tambah_rek();">Tambah</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:tutup_rek();">Kembali</a>
        </td>                
        </tr>
     </table>
     
    </fieldset>
</div>      	
</body>

</html>