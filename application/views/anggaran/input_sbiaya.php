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
        
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0 ;
    var lcidx    = 0 ;
    var lcstatus = '';
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 850,
            width: 950,
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
		url: '<?php echo base_url(); ?>/index.php/sbiaya/sbiaya/loadSbiaya',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[    	    
            {field:'kd_harga5',
    		title:'Kode',
    		width:9,
            align:"center"},
            {field:'uraian',
    		title:'Uraian Standar Biaya Rincian',
    		width:20,
            align:"center"},
            {field:'kd_harga4',
    		title:'Kode Jenis',
    		width:9,
            align:"left"},
            {field:'uraian4',
    		title:'Uraian Standar Biaya Jenis',
    		width:20,
            align:"center"},
            
        ]],
        onSelect:function(rowIndex,rowData){
		  id   			= rowData.id;
          tahun         = rowData.tahun;    
          nm_paket      = rowData.nm_paket;
		  kd_program    = rowData.kd_program;
		  kd_kegiatan   = rowData.kd_kegiatan;
		  nm_kegiatan   = rowData.nm_kegiatan;
		  lokasi        = rowData.lokasi;
		  det_lokasi    = rowData.det_lokasi;
		  jns_pengadaan = rowData.jns_pengadaan;
		  volume        = rowData.volume;
		  uraian        = rowData.uraian;
		  spesifikasi   = rowData.spesifikasi;
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
		  id_swakelola  = rowData.id_swakelola;
		  skpd  		= rowData.skpd;
		  status		='edit';
		  get(id,tahun,nm_paket,kd_program,kd_kegiatan,nm_kegiatan,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,aktif,umumkan,id_swakelola,skpd);
		},
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Penetapan'; 
           edit_data();   
        }
        });
		
        /*
		$('#dgsd').datagrid({
			iconCls: 'icon-edit',
			//singleSelect: true,
			toolbar: '#tb',
			method: 'get',
			onClickCell: onClickCell,
			onEndEdit: onEndEdit,
			columns:[[
    	    {field:'kd_sd', title:'Sumber Dana',width:100, align:"left",
			 formatter:function(value,row){
                return row.kd_sd;
                        },
                         editor:{
                            type:'combobox',
                            options:{
                                valueField:'kd_sd',
                                textField:'nm_sd',
                                method:'get',
                                url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/sumber_dana',
                                required:true
                            }
                        }},
    	    {field:'kd_ad', title:'Asal dana',width:130, align:"left",editor:'text'},
            {field:'kd_ads', title:'Asal Dana Satker ',width:130,align:"left",editor:'text'},
            {field:'max', title:'Mak',width:220,align:"left",editor:'text'},
            {field:'pagu', title:'Pagu',width:170,align:"left",editor:{type:'numberbox',options:{precision:2}}},
        ]],
			
        });
        */
        
		$('#dgsd').datagrid({
        idField:'max',            
        rownumbers:"true",        
        singleSelect:"true",
        autoRowHeight:"true",  
        toolbar: '#tb',                               
        columns:[[    	    
            {field:'kd_sd',
    		title:'Sumber Dana',
    		width:100,
            align:"center"},
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
    		width:220,
            align:"left"},
            {field:'kd_kegiatan',
    		title:'Kode Kegiatan',
    		width:100,
            hidden:"true"},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',
    		width:100,
            hidden:"true"},
            {field:'kd_rek5',
    		title:'Kode',
    		width:100,
            hidden:"true"},            
            {field:'nm_rek5',
    		title:'Uraian',
    		width:260,
            align:"left"},
            {field:'pagu',
    		title:'Pagu',
    		width:150,
            align:"right"}
           
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
               getGiat();
           }  
        });
	
		$('#lokasi').combobox({  
           panelWidth:400,  
		   multiple: true,
           idField:'kd_lokasi',  
           textField:'nm_lokasi',  
           mode:'remote',
		   url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/lokasi',  
           valueField:'kd_lokasi',
		   method:'get',
		   required:true  
        });
		
		$('#jns_pengadaan').combobox({  
           panelWidth:400,  
		   multiple: true,
           idField:'kd_jp',  
           textField:'nm_jp',  
           mode:'remote',
		   url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/jns_pengadaan',  
           valueField:'kd_jp',
		   method:'get',
		   required:true  
        });
		
		$('#mtd_pengadaan').combobox({  
           panelWidth:400,  
		   multiple: false,
           idField:'kd_mp',  
           textField:'nm_mp',  
           mode:'remote',
		   url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/mtd_pengadaan',  
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
               $("#total_pagu").attr("value",rowData.nilai);
               kd_kegi = rowData.kd_kegiatan;
               $('#nm_paket').combogrid({url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/listRekening',queryParams:({kd_keg:kd_kegi})});
               //$('#koderek').combogrid({url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/listRekening',queryParams:({kd_keg:kd_kegi})});
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
               $("#npagu").attr("value",rowData.nilai); 
           }  
           
        });
        
        $('#nm_paket').combogrid({  
           panelWidth:700,  
           idField:'nm_rek5',  
           textField:'nm_rek5',  
           mode:'remote',
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:100},  
               {field:'nm_rek5',title:'Nama Rekening',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#namarek").attr("value",rowData.nm_rek5);
               $("#nsumber").attr("value",rowData.sumber);
               $("#npagu").attr("value",rowData.nilai);                
           }  
           
        });
        
		$('#ntahun').combogrid({  
           panelWidth:110,  
           url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/listTahun',
           idField:'tahun',  
           textField:'tahun',  
           mode:'remote',
           columns:[[  
               {field:'tahun',title:'Tahun',width:100}  
           ]] 
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
        //alert(skpd);
     $('#kd_kegiatan').combogrid({url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/listKegiatan',queryParams:({skpd:skpd})});  
   
     } 
	 
	
    function get(id,tahun,nm_paket,kd_program,kd_kegiatan,nm_kegiatan,lokasi,det_lokasi,jns_pengadaan,volume,uraian,spesifikasi,tkdn,pradipa,total,mtd_pengadaan,pilih_awal,pilih_akhir,kerja_mulai,kerja_akhir,aktif,umumkan,id_swakelola,skpd){
		$("#id").attr("value",id);
        $("#ntahun").combogrid("setValue",tahun);
        $("#nm_paket").attr("value",nm_paket);
		$("#kd_kegiatan").combogrid("setValue",kd_kegiatan); 
		$("#nm_kegiatan").attr("value",nm_kegiatan);
		$("#kd_program").attr("value",kd_program);
		$("#lokasi").combobox("setValues",lokasi);
		$("#det_lokasi").attr("value",det_lokasi);
		$("#jns_pengadaan").combobox("setValues",jns_pengadaan);
		$("#volume").attr("value",volume);
		$("#uraian").attr("value",uraian);
		$("#spesifikasi").attr("value",spesifikasi);
		$("#total_pagu").attr("value",total);
		$("#mtd_pengadaan").combobox("setValue",mtd_pengadaan);
		$("#pilih_awal").datebox("setValue",pilih_awal);
		$("#pilih_akhir").datebox("setValue",pilih_akhir);
		$("#kerja_awal").datebox("setValue",kerja_mulai);
		$("#kerja_akhir").datebox("setValue",kerja_akhir);
		$("#id_swakelola").attr("value",id_swakelola);
		if (tkdn=='1'){            
				$("#tkdn").attr("checked",true);
			} else {
				$("#tkdn").attr("checked",false);
			}
		if (pradipa=='1'){            
				$("#pradipa").attr("checked",true);
			} else {
				$("#pradipa").attr("checked",false);
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
		
		
		  $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sbiaya/sbiaya/detailPenyedia',
                data: ({skpd:skpd,id:id}),
                dataType:"json",
                success:function(data){
                    $('#dgsd').datagrid('loadData',[]);
                    $('#dgsd').edatagrid('reload');
                    $.each(data,function(i,n){                                    
                    kd_sd   = n['kd_sd'];
                    kd_ad  	= n['kd_ad'];
                    kd_ads 	= n['kd_ads'];
                    max  	= n['max'];
                    kd_keg  = n['kd_kegiatan'];
                    nm_keg  = n['nm_kegiatan'];
                    kd_rek  = n['kd_rek5'];
                    nm_rek  = n['nm_rek5'];                    
                    pagu 	= n['pagu'];
                                                                                                        
                    $('#dgsd').edatagrid('appendRow',{kd_sd:kd_sd,kd_ad:kd_ad,kd_ads:kd_ads,max:max,kd_kegiatan:kd_keg,nm_kegiatan:nm_keg,kd_rek5:kd_rek,nm_rek5:nm_rek,pagu:pagu});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });
		
		
		
		
		//$('#dgsd').datagrid({url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/detailPenyedia',queryParams:({skpd:skpd,id:id})}); 
		
		
    }
    
    function kosong(){
		get_nourut();
		status		='tambah';
		$("#id").attr("value",'');
        $("#ntahun").combogrid("setValue",'');
        $("#nm_paket").attr("value",'');
		$("#kd_kegiatan").combogrid("setValue",''); 
		$("#nm_kegiatan").attr("value",'');
		$("#kd_program").attr("value",'');
		$("#lokasi").combobox("setValues",'');
		$("#det_lokasi").attr("value",'');
		$("#jns_pengadaan").combobox("setValues",'');
		$("#volume").attr("value",'');
		$("#uraian").attr("value",'');
		$("#spesifikasi").attr("value",'');
		$("#total_pagu").attr("value",'');
		$("#mtd_pengadaan").combobox("setValue",'');
		$("#pilih_awal").datebox("setValue",'');
		$("#pilih_akhir").datebox("setValue",'');
		$("#kerja_awal").datebox("setValue",'');
		$("#kerja_akhir").datebox("setValue",'');
		$("#id_swakelola").attr("value",'');
		$("#tkdn").attr("checked",false);
		$("#pradipa").attr("checked",false);
		$("#aktif").attr("checked",false);
		$("#umumkan").attr("checked",false);
		$(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/sbiaya/sbiaya/detailPenyedia',
                data: ({skpd:'',id:''}),
                dataType:"json",
                success:function(data){
                    $('#dgsd').datagrid('loadData',[]);
                    $('#dgsd').edatagrid('reload');
                    $.each(data,function(i,n){                                    
                    kd_sd   = n['kd_sd'];
                    kd_ad  	= n['kd_ad'];
                    kd_ads 	= n['kd_ads'];
                    max  	= n['max'];
                    kd_keg  = n['kd_kegiatan'];
                    nm_keg  = n['nm_kegiatan'];
                    kd_rek  = n['kd_rek5'];
                    nm_rek  = n['nm_rek5'];                    
                    pagu 	= n['pagu'];
                                                                                                        
                    $('#dgsd').edatagrid('appendRow',{kd_sd:kd_sd,kd_ad:kd_ad,kd_ads:kd_ads,max:max,kd_kegiatan:ckkeg,nm_kegiatan:cnkeg,kd_rek5:ckrek,nm_rek5:cnrek,pagu:pagu});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });
		
    }
    
   
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/sbiaya/sbiaya/loadPenyedia',
        queryParams:({cari:kriteria})
        });        
     });
     }
    
    
    
    function simpan(){
		var skpd_ 		 	= $("#skpd").combogrid("getValue"); 
        var nm_paket_ 	    = document.getElementById('nm_paket').value;
        var ntahun_         = $("#ntahun").combogrid("getValue");	
		var kd_kegiatan_ 	= $("#kd_kegiatan").combogrid("getValue"); 
		var nm_kegiatan_ 	= document.getElementById('nm_kegiatan').value;
		var kd_program_ 	= document.getElementById('kd_program').value;
		var lokasi_ 		=  $('#lokasi').combobox('getValues');
		var det_lokasi_ 	= document.getElementById('det_lokasi').value;
		var jns_pengadaan_ 	= $("#jns_pengadaan").combobox("getValues"); 
		var volume_ 		= document.getElementById('volume').value;
		var uraian_ 		= document.getElementById('uraian').value;
		var spesifikasi_ 	= document.getElementById('spesifikasi').value;
		var total_pagu_ 	= angka(document.getElementById('total_pagu').value);
		var mtd_pengadaan_ 	= $("#mtd_pengadaan").combobox("getValue"); 
		var pilih_awal_ 	= $("#pilih_awal").datebox("getValue"); 
		var pilih_akhir_ 	= $("#pilih_akhir").datebox("getValue"); 
		var kerja_awal_ 	= $("#kerja_awal").datebox("getValue"); 
		var kerja_akhir_ 	= $("#kerja_akhir").datebox("getValue"); 
		var id_swakelola_ 	= document.getElementById('id_swakelola').value;
		var id_ 			= document.getElementById('id').value;
 
		if (document.getElementById("tkdn").checked == true){
			 tkdn_ = "1";
		 }else{
			 tkdn_ = "0";
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
		 
		if(status =='tambah') {
			$('#dgsd').datagrid('selectAll');
				var rows = $('#dgsd').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){
					ckdsd    = rows[p].kd_sd;
					ckodekeg = rows[p].kd_kegiatan;
                    cnmkeg   = rows[p].nm_kegiatan;
                    ckoderek = rows[p].kd_rek5;
                    cnmrek   = rows[p].nm_rek5;					                    
                    ckdad    = rows[p].kd_ad;
					ckdads   = rows[p].kd_ads;
					cmax     = rows[p].max;
					cpagu    = angka(rows[p].pagu);
					if (p>0) {
					csql = csql+","+"('"+id_+"','"+skpd_+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cpagu+"')";
					} else {
					csql = "values('"+id_+"','"+skpd_+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cpagu+"')";                                            
					}                                             
				}   
			alert(csql);
			$(document).ready(function(){
				$.ajax({
					type: "POST",       
					dataType : 'json',         
					data: ({cskpd:skpd_,ctahun:ntahun_,cpaket:nm_paket_,ckdgiat:kd_kegiatan_,ckdprog:kd_program_,clok:lokasi_,cdetlok:det_lokasi_, 	
							cjns:jns_pengadaan_,cvol:volume_,curai:uraian_,cspes:spesifikasi_,ctot:total_pagu_, 	
							cmtd:mtd_pengadaan_,cpilawl:pilih_awal_,cpilakhir:pilih_akhir_,ckerawalan:kerja_awal_,
							ckerakhir:kerja_akhir_,cidswa:id_swakelola_,ctkdn:tkdn_,cpra:pradipa_,caktif:aktif_,
							cumum:umumkan_,cnmgiat:nm_kegiatan_,cid:id_,cdet:csql
					}),  	
					url:'<?php echo base_url(); ?>/index.php/sbiaya/sbiaya/savePenyedia',
					success:function(data){
					   
						pesan = data.pesan;
						if(pesan=='1'){
							alert("Data tersimpan");
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
					ckdsd    = rows[p].kd_sd;
					ckdad    = rows[p].kd_ad;
					ckdads   = rows[p].kd_ads;
                    ckodekeg = rows[p].kd_kegiatan;
                    cnmkeg   = rows[p].nm_kegiatan;
                    ckoderek = rows[p].kd_rek5;
                    cnmrek   = rows[p].nm_rek5;
					cmax     = rows[p].max;
					cpagu    = angka(rows[p].pagu);
					if (p>0) {
					csql = csql+","+"('"+id_+"','"+skpd_+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cpagu+"')";
					} else {
					csql = "values('"+id_+"','"+skpd_+"','"+ckodekeg+"','"+cnmkeg+"','"+ckoderek+"','"+cnmrek+"','"+ckdsd+"','"+ckdad+"','"+ckdads+"','"+cmax+"','"+cpagu+"')";                                            
					}                                             
				}   
			
			$(document).ready(function(){
				$.ajax({
					type: "POST",       
					dataType : 'json',         
					data: ({cskpd:skpd_,ctahun:ntahun_,cpaket:nm_paket_,ckdgiat:kd_kegiatan_,ckdprog:kd_program_,clok:lokasi_,cdetlok:det_lokasi_, 	
							cjns:jns_pengadaan_,cvol:volume_,curai:uraian_,cspes:spesifikasi_,ctot:total_pagu_, 	
							cmtd:mtd_pengadaan_,cpilawl:pilih_awal_,cpilakhir:pilih_akhir_,ckerawalan:kerja_awal_,
							ckerakhir:kerja_akhir_,cidswa:id_swakelola_,ctkdn:tkdn_,cpra:pradipa_,caktif:aktif_,
							cumum:umumkan_,cnmgiat:nm_kegiatan_,cid:id_,cdet:csql
					}),  	
					url:'<?php echo base_url(); ?>/index.php/sbiaya/sbiaya/editPenyedia',
					success:function(data){
						pesan = data.pesan;
						if(pesan=='1'){
							alert("Data tersimpan");
						}else if(pesan=='1'){
							alert("Detail Gagal Tersimpan");
						}else{
							alert("Header Gagal Tersimpan");
						}
						
					}
				});
			});
		}		
	}      

    function edit_data(){
        lcstatus = 'edit';
        judul = 'Penyedia';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("nomor").disabled=true;
        }    
        
    
    function tambah(){
        lcstatus = 'tambah';
        judul = 'Penyedia';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        getGiat();
        } 
     
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        
		var id     = document.getElementById('id').value;
        var kode   = $('#skpd').combogrid('getValue');
        var urll = '<?php echo base_url(); ?>index.php/sbiaya/sbiaya/hapusPenyedia';
        $(document).ready(function(){
         $.post(urll,({no:id,skpd:kode}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
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
        		url:'<?php echo base_url(); ?>index.php/sbiaya/sbiaya/urutPenyedia',
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
            /*if (editIndex == undefined){return}
            $('#dgsd').datagrid('cancelEdit', editIndex)
                    .datagrid('deleteRow', editIndex);
            editIndex = undefined;*/
            
            var selectedrow = $("#dgsd").datagrid("getSelected");
            var rowIndex = $("#dgsd").datagrid("getRowIndex", selectedrow);
            if(rowIndex<0){
                alert('List Belum Dipilih'); 
            }else{
                $('#dgsd').datagrid('deleteRow',rowIndex); 
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
            
            if(kkrek==''){
                alert('Nama Paket Tidak Boleh Kosong');
            }else{
            
            cskpd = $('#skpd').combogrid('getValue');            
            ckkeg = $('#kd_kegiatan').combogrid('getValue');
            cnkeg = document.getElementById("nm_kegiatan").value;
            ckrek = $('#nm_paket').combogrid('getValue');
            cnrek = document.getElementById("namarek").value;
            cmak  = ckkeg+"."+ckrek;
            csumb = document.getElementById("nsumber").value;
            cpagu = document.getElementById("npagu").value;
            
            $('#dgsd').edatagrid('appendRow',{kd_sd:csumb,kd_ad:csumb,kd_ads:cskpd,max:cmak,kd_kegiatan:ckkeg,nm_kegiatan:cnkeg,kd_rek5:ckrek,nm_rek5:cnrek,pagu:cpagu});
            accept();
            }
        }
        
        function tutup_rek(){
            $("#dialog-modal-rek").dialog('close');
        }
        
        function cetak(){
            var url = '<?php echo site_url(); ?>/sbiaya/sbiaya/cetak_listpenyedia';
            window.open(url, '_blank');
            window.focus();            
        }
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">PAKET PENYEDIA</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak()">Cetak</a>                       
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="Listing Data Paket Penyedia" style="width:870px;height:450px;" >  
        </table>
 
    </p> 
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
				<input type="hidden" id="kd_program" style="border:0;width: 400px;" readonly="true" /></td>
            </tr>
            <tr>
                <td>Nama Paket</td>
                <td></td>
                <td><input type="text" id="nm_paket" style="width: 700px;" /> </td>                
            </tr>            
			<tr>
                <td>Lokasi Pekerjaan</td>
                <td></td>
                <td><input type="text" id="lokasi" name="lokasi" style="width: 170px;" /></td> 
            </tr>
            <tr>
                <td>Detail Lokasi</td>
                <td></td>
                <td><input type="text" id="det_lokasi" style="width: 700px;" /> </td>                
            </tr> 
            <tr>
                <td>Jenis Pengadaan</td>
                <td></td>
                <td><input type="text" id="jns_pengadaan" style="width:170px;" /> </td>                
            </tr> 
            <tr>
                <td>Volume</td>
                <td></td>
                <td><input type="text" id="volume" style="width: 170px;" /> </td>                
            </tr> 
            <tr>
                <td>Deskripsi</td>
                <td></td>
                <td><input type="text" id="uraian" style="width: 700px;" /> </td>                
            </tr> 
            <tr>
                <td>Spesifikasi</td>
                <td></td>
                <td>
                <textarea id="spesifikasi" rows="4" cols="92">                
                </textarea>
                
                <!--<input type="text" id="spesifikasi" style="width: 700px;" />--> 
                </td>                
            </tr> 
            <tr>
                <td>TKDN</td>
                <td></td>
                <td><input type="checkbox" id="tkdn"/> </td>                
            </tr> 
            <tr>
                <td>Usaha Kecil</td>
                <td></td>
                <td><input type="checkbox" id="uk"/> </td>                
            </tr>
            <tr>
                <td>PRA DIPA/DPA</td>
                <td></td>
                <td><input type="checkbox" id="pradipa"/> </td>                
            </tr> 
            <tr>
                <td>Total Pagu</td>
                <td></td>
                <td><input type="text" id="total_pagu" style="width: 170px; text-align: right"  /> </td>                
            </tr> 
			<tr>
                <td></td>
                <td></td>                
                <td><table id="dgsd" class="easyui-datagrid" title="List Rincian" style="width:770px;height:140px;">
				<div id="tb" style="height:auto">
					<!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="buka_rek()">Tambah Rekening</a>-->
					
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="tambah_rek()">Tambah Rekening</a>
					<!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Tambah</a>-->
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Hapus</a>
					<!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Simpan Rincian</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Undo</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">Cek</a>-->
				</div>
				</table>
			</td>                
            </tr> 
			    
            <tr>
                <td>Metode Pengadaan</td>
                <td></td>
                <td><input type="text" id="mtd_pengadaan" style="width: 140px;" /> </td>                
            </tr> 
            <tr>
                <td>Bulan Pemilihan</td>
                <td></td>
                <td><input type="text" id="pilih_awal" style="width: 140px;" /> s/d <input type="text" id="pilih_akhir" style="width: 140px;" / </td>                
            </tr> 
             <tr>
                <td>Bulan Pekerjaan</td>
                <td></td>
                <td><input type="text" id="kerja_awal" style="width: 140px;" /> s/d <input type="text" id="kerja_akhir" style="width: 140px;" / </td>                
            </tr> 
            <tr>
                <td>Aktif</td>
                <td></td>
                <td><input type="checkbox" id="aktif"  /> </td>                
            </tr> 
            <tr>
                <td>Umumkan</td>
                <td></td>
                <td><input type="checkbox" id="umumkan"  /> </td>                
            </tr> 
            <tr>
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