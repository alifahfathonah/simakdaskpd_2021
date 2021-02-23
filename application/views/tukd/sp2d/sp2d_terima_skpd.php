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
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script type="text/javascript"> 
    
    var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
    var status_cair = '';
    var kd_sub_skpd = '';
    
    $(function(){
        $("#loading1").hide();
   	     $('#dd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});
	
	$(function(){
   	     $('#tgl_kasda').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});

     $(function(){
   	     $('#dkas').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            }
        });
   	});
	
	
	$(function(){
	$('#bank1').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    //mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:40},  
                           {field:'nama_bank',title:'Nama',width:140}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //$("#kode").attr("value",rowData.kode);
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });	
	});
	
	
        $(function(){
            $('#csp2d').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_sp2d',  
                    idField:'no_sp2d',                    
                    textField:'no_sp2d',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_sp2d',title:'SP2D',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'no_spm',title:'SPM',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.no_sp2d;
                    dns = rowData.kd_skpd;
                    val_ttd(dns);
                    }   
                });
           });
           
		$(function(){   
		$('#cc').combobox({
					url:'<?php echo base_url(); ?>/index.php/tukd/load_jenis_beban',
					valueField:'id',
					textField:'text',
					onSelect:function(rowIndex,rowData){
					validate_tombol();
                    }
				});	
			 });		
				
        function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+dns,  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}
                        
                        
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;
                    
                    }   
                });
           });              
         }
    $(function(){ 
     $('#sp2d').edatagrid({
		url: '<?php echo base_url(); ?>index.php/sp2dc/load_terima_sp2d',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",   
        rowStyler: function(index,row){
        if (row.status == "Sudah diterima"){
          return 'background-color:#03d3ff;';
        }
        },                     
        columns:[[
    	    {field:'no_sp2d',
    		title:'Nomor SP2D',
    		width:90},
            {field:'no_spm',
    		title:'Nomor SPM',
    		width:90},
            {field:'tgl_sp2d',
    		title:'Tanggal',
    		width:35},
            {field:'kd_skpd',
    		title:' SKPD',
    		width:35,
            align:"left"},
            {field:'status',
    		title:'Status',
    		width:40,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){


        },
        onDblClickRow:function(rowIndex,rowData){
          no_sp2d = rowData.no_sp2d;
          kd_sub_skpd = rowData.kd_sub_skpd;
          no_spm = rowData.no_spm;
          tgs  = rowData.tgl_sp2d;
          st = rowData.status;
          nokas = rowData.no_terima;
          dkas  = rowData.dterima;
          nocek = rowData.nocek;
          status_terima = rowData.status_terima;
          status_cair = rowData.status_cair;
          dbud = rowData.dbud;
          if(status_cair=='1'){
            $("#btcair").hide();
          }else{
            $("#btcair").show();
          }
            getspm(no_sp2d,no_spm,tgs,st,nokas,dkas,nocek,status_terima,dbud); 
            st = rowData.status;
            section2(status_terima);   
        }
    });
    });         
   
   
    $(function(){
            $('#nospm').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/nospm1',  
                    idField:'no_spm',                    
                    textField:'no_spm',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spm',title:'No',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:80} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_spm = rowData.no_spm
                        tgspm = rowData.tgl_spm
                        no_spp = rowData.no_spp;         
                        dn  = rowData.kd_skpd;
                        sp  = rowData.no_spd;          
                        bl  = rowData.bulan;
                        tg  = rowData.tgl_spp;
                        jn  = rowData.jns_spp;
                        jns_bbn  = rowData.jns_beban;
                        kep  = rowData.keperluan;
                        np  = rowData.npwp;
                        rekan  = rowData.nmrekan;
                        bk  = rowData.bank;
                        ning  = rowData.no_rek;
                        nm  = rowData.nm_skpd;
                       get(no_spm,tgspm,no_spp,dn,sp,tg,bl,jn,kep,np,rekan,bk,ning,nm,jns_bbn);
					   
                       detail();
                       pot();                                                              
                    }  
                });
           });

             
        $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 singleSelect:"true"
                                  
			});
		}); 
        
        
        
        $(function(){
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/spmc/pot',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 singleSelect:"true",
                                  
			});
		}); 
              
        function detail(){
        $(function(){            
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_spm();
                      },                                  			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                     
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:165,
					 align:'left'
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:400					 
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
                      
				]]	
			
			});
  	

		});
        }        		
		
		function nospm_ck(no_spm){
			var spm = no_spm;
			var no =spm.split("/").join("zzzzz");			
			$(function(){
            $('#nospm').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>index.php/tukd/nospm1_ck/'+no,  
                    idField:'no_spm',                    
                    textField:'no_spm',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spm',title:'No',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:80} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_spm = rowData.no_spm
                        tgspm = rowData.tgl_spm
                        no_spp = rowData.no_spp;         
                        dn  = rowData.kd_skpd;
                        sp  = rowData.no_spd;          
                        bl  = rowData.bulan;
                        tg  = rowData.tgl_spp;
                        jn  = rowData.jns_spp;
                        jns_bbn  = rowData.jns_beban;
                        kep  = rowData.keperluan;
                        np  = rowData.npwp;
                        rekan  = rowData.nmrekan;
                        bk  = rowData.bank;
                        ning  = rowData.no_rek;
                        nm  = rowData.nm_skpd;
                       get(no_spm,tgspm,no_spp,dn,sp,tg,bl,jn,kep,np,rekan,bk,ning,nm,jns_bbn);
					   
                       detail();
                       pot();                                                              
                    }  
                });
           });
    
		}
		
        function detail1(){
        $(function(){ 
            var no_spp='';
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 autoRowHeight:"true",
                 singleSelect:false,                                                   			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                     
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:165,
					 align:'left'
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left'
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:400					 
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        
        
         function pot(){
        $(function(){
	   	    //alert(no_spm);                         
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/spmc/pot',
                queryParams:({spm:no_spm}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_pot();
                      },                              			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:550
					},                    
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
                      
				]]	
			
			});
  	

		});
        }
        
        function pot1(){
        $(function(){
	   	    var no_spm='';                         
			$('#pot').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/spmc/pot',
                queryParams:({spm:no_spm}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 autoRowHeight:"true",
                 singleSelect:false,                                              			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                    
					{field:'kd_rek5',
					 title:'Rekening',
					 width:100,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:550
					},                    
                    {field:'nilai',
					 title:'Nilai',
					 width:100,
                     align:'right'
                     }
                      
				]]	
			
			});
  	

		});
        }
              
        function get(no_spm,tgspm,no_spp,kd_skpd,no_spd,tgl_spp,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,nm_skpd){
			
			$("#no_spm").attr("value",no_spm);
			$("#tgl_spm").attr("value",tgspm);
			$("#nospp").attr("value",no_spp);
			$("#dn").attr("value",kd_skpd);
			$("#sp").attr("value",no_spd);        
			$("#tgl_spp").attr("value",tgl_spp);
			$("#kebutuhan_bulan").attr("Value",bulan);
			$("#ketentuan").attr("Value",keperluan);
			$("#jns_beban").attr("Value",jns_spp);
			$("#npwp").attr("Value",npwp);
			$("#rekanan").attr("Value",rekanan);
             $("#bank1").combogrid("setValue",bank);
			$("#rekening").attr("Value",rekening);
			$("#nmskpd").attr("Value",nm_skpd);
			validate_jenis_edit(jns_bbn);
        }
        
		
        function getspm(no_sp2d,no_spm,tgl_sp2d,status,nokas,dkas,nocek,status_cair,dbud){
			$("#no_sp2d").attr("value",no_sp2d);
			$("#dd").datebox("setValue",tgl_sp2d);
			$("#tgl_kasda").datebox("setValue",dbud);
			$("#nokas").attr("Value",nokas);
			$("#dkas").datebox("setValue",dbud);
			$("#nocek").attr("Value",nocek);
			$("#nospm").combogrid("setValue",no_spm);
			//nospm_ck(no_spm);
			tombol(status_cair);
        }
		
        function kosong(){
			$("#no_sp2d").attr("value",'');
			$("#dd").datebox("setValue",'');
			$("#nospm").combogrid("setValue",'');
			$("#nospp").attr("value",'');
			$("#dn").attr("value",'');
			$("#sp").attr("value",'');        
			$("#tgl_spp").attr("value",'');
			$("#tgl_spm").attr("value",'');
			$("#kebutuhan_bulan").attr("Value",'');
			$("#ketentuan").attr("Value",'');
			$("#jns_beban").attr("Value",'');
			$("#npwp").attr("Value",'');
			$("#rekanan").attr("Value",'');
			 $("#bank1").combogrid("setValue",'');
			$("#rekening").attr("Value",'');
			$("#nmskpd").attr("Value",'');   
			document.getElementById("p1").innerHTML="";
			detail1();
			pot1();
			$("#nospm").combogrid("clear");
			//tombolnew();      
        }


        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $("#dialog-modal").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
		get_tahun();
        });
       
     function cetak(){
        $("#dialog-modal").dialog('open');
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
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
     function cari(){
     var kriteria = document.getElementById("txtcari").value; 
     
        $(function(){ 
            $('#sp2d').edatagrid({
	       url: '<?php echo base_url(); ?>index.php/sp2dc/load_terima_sp2d',
         queryParams:({cari:kriteria})
        });        
     });
    }
	
	function validate_jenis_edit($jns_bbn){
        var beban   = document.getElementById('jns_beban').value;
		$('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/sp2dc/load_jenis_beban/'+beban,
		});

		$('#cc').combobox('setValue', jns_bbn);
	}	
        
        function simpan_spm(){        
        var a1 = document.getElementById('no_sp2d').value;
        var b1 = $('#dd').datebox('getValue');        
        var b2 =document.getElementById('tgl_spm').value;
        var b = document.getElementById('tgl_spp').value;      
        var c = document.getElementById('jns_beban').value; 
        var d = document.getElementById('kebutuhan_bulan').value;
        var e = document.getElementById('ketentuan').value;
        var f = document.getElementById('rekanan').value;
        var g  = $("#bank1").combogrid("getValue") ; 
        var h = document.getElementById('npwp').value;
        var i = document.getElementById('rekening').value;
        var j = document.getElementById('nmskpd').value;
        var k = document.getElementById('dn').value;
        var l = document.getElementById('sp').value;
                
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:k,cspd:l,no_sp2d:a1,tgl_sp2d:b1,no_spm:no_spm,tgl_spm:b2,no_spp:no_spp,tgl_spp:b,jns_spp:c,bulan:d,keperluan:e,nmskpd:j,rekanan:f,bank:g,npwp:h,rekening:i}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan_sp2d",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                    $('#sp2d').edatagrid('reload');
                }else{
                    alert('Data Gagal Tersimpan');
                }
            }
         }); 
        });
        }
    

        function simpan_cair(){ 

			var nokas = document.getElementById('nokas').value;
			var tglcair = $('#dkas').datebox('getValue');        
			var tglsp2d = $('#dd').datebox('getValue');        
			var nocek =document.getElementById('nocek').value;
			var total = document.getElementById('total').value;      
			var nosp2d = document.getElementById('no_sp2d').value; 
			var cskpd = document.getElementById('dn').value; 
			var cket = document.getElementById('ketentuan').value; 
			var cbeban = document.getElementById('jns_beban').value; 
            kd_sub_skpd=kd_sub_skpd;
			var tahun_input = tglcair.substring(0, 4);
    		if (tahun_input != tahun_anggaran){
    			alert('Tahun tidak sama dengan tahun Anggaran');
    			exit();
    		}

		if (tglcair==''){
		alert("Tanggal tidak boleh kosong");
		exit();
		} 

         
			$(function(){
			 $.ajax({
				type: 'POST',
				data: ({nkas:nokas,tcair:tglcair,ncek:nocek,tot:total,nsp2d:nosp2d,skpd:cskpd,ket:cket,beban:cbeban, kd_sub_skpd:kd_sub_skpd}),
				dataType:"json",
				url:"<?php echo base_url(); ?>index.php/sp2dc/simpan_terima_sp2d",
				success:function(data){
					if (data = 1){
						//alert('SP2D Telah Dicairkan');
					}
				}
			 });
			});
            alert('SP2D Telah Diterima');
			document.getElementById("p1").innerHTML="SP2D Sudah diterima!!";
			$('#nokas').attr('readonly',true);

        }

        function batal_cair(){       
			if(status_cair=='1'){
			alert("SP2D telah Dicairkan. Silakan batalkan pencairan terlebih dahulu");
			exit();
			}
			var nokas = document.getElementById('nokas').value;
			var tglcair = $('#dkas').datebox('getValue');        
			var nocek =document.getElementById('nocek').value;
			var total = document.getElementById('total').value;      
			var nosp2d = document.getElementById('no_sp2d').value; 

			$(function(){
            $("#loading1").show();       
			 $.ajax({
				type: 'POST',
				data: ({nkas:nokas,tcair:tglcair,ncek:nocek,tot:total,nsp2d:nosp2d}),
				dataType:"json",
				url:"<?php echo base_url(); ?>index.php/sp2dc/batal_terima",
				success:function(data){
					if (data = 1){
                        $("#loading1").hide(); 
						alert('Penerimaan Dibatalkan');
						$("#nokas").attr("Value",'');
						$("#dkas").datebox("setValue",'');
						$("#nocek").attr("Value",'');
						document.getElementById("p1").innerHTML=" ";
						get_nourut();
						$('#nokas').attr('readonly',false);
					}else{
                        $("#loading1").hide();
                        alert("Gagal");
                    }
				}
			 });
			});
        }  

				  
         function hhapus(){				
            var sp2d = document.getElementById("no_sp2d").value;
            //var spp = document.getElementById("no_spp").value; 
            alert(sp2d+no_spm);             
            var urll= '<?php echo base_url(); ?>/index.php/tukd/hapus_sp2d';             			    
         	if (sp2d !=''){
				var del=confirm('Anda yakin akan menghapus SP2D '+sp2d+'  ?');
				if  (del==true){
					$(document).ready(function(){
                    $.post(urll,({no:sp2d,spm:no_spm}),function(data){
                    status = data;
                        
                    });
                    });
				
				}
				} 
		}
        
        
        
        function load_sum_spm(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:no_spp}),
            url:"<?php echo base_url(); ?>index.php/spmc/load_sum_spm",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rekspm").attr("value",n['rekspm']);
                    $("#total").attr("value",n['rekspm']);
                });
            }
         });
        });
    }         
        
        function load_sum_pot(){                
		//var spm = document.getElementById('no_spm').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spm:no_spm}),
            url:"<?php echo base_url(); ?>index.php/spmc/load_sum_pot",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
     }
     function section2(st){

		if (st=='1'){
			document.getElementById("btcair").value="BATAL TERIMA";
		}else{
		  get_nourut();
			document.getElementById("btcair").value="TERIMA SP2D";		
		}

         $(document).ready(function(){    
             $('#section2').click();                                               
         });
     }
	 
	function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#nokas").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
        
     function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
     }
     
     function tombol(st){  
     if (st=='1'){
         $('#save').linkbutton('disable');
         $('#del').linkbutton('disable');
         $('#poto').linkbutton('disable');       
         document.getElementById("p1").innerHTML="SP2D Sudah diterima!!";
     } else {
         $('#save').linkbutton('enable');
         $('#del').linkbutton('enable');
         $('#poto').linkbutton('enable');     
         document.getElementById("p1").innerHTML="";
     }
    }
    
    function tombolnew(){  
    
     $('#save').linkbutton('enable');
     $('#del').linkbutton('enable');
     $('#poto').linkbutton('enable');     
    
    }
    
   

	function cair(){
		var cap=document.getElementById("btcair").value;
		var nokas=document.getElementById("nokas").value;
		
		if (nokas==''){
			alert('Nomor Kas Harus Diisi !!!');
			exit;
		}

		if (cap=='TERIMA SP2D'){
			simpan_cair();
			document.getElementById("btcair").value="BATAL TERIMA";
		}else{
			batal_cair();
			document.getElementById("btcair").value="TERIMA SP2D";		
		}
	}

	function linkcair(){
		var cap=document.getElementById("btcair").value;		
				
		if (cap=='TERIMA SP2D'){			
            alert('SP2D Harus Diterima Dulu');
            exit();
		}else{
			var url    = "<?php echo site_url(); ?>sp2dc/pencairan";
            window.open(url, '_self');
			window.focus();
		}
	}

     
    </script>
    <STYLE TYPE="text/css"> 
		 input.right{ 
         text-align:right; 
         } 
</STYLE> 

</head>
<body>


<div id="content">

<div id="accordion">

<h3><a href="#" id="section1" onclick="javascript:$('#sp2d').edatagrid('reload')">PENERIMAAN SP2D</a></h3>
    <table width="100%">
        <tr>
            <td align="right">
                <button class="button" onclick="javascript:cari();"><i class="fa fa-cari"></i> Cari</button>
                <input type="text" onkeyup="javascript:cari();" class="input" style="display: inline;" value="" id="txtcari"/>
            </td>
        </tr>
        <tr>
            <td><table id="sp2d" title="List SP2D" style="width:1024px;height:450px;" ></table></td>
        </tr>
    </table>

<h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')" >DATA SP2D</a></h3>
   <div  style="height: 400px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>

<button class="button-abu" ONCLICK="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>   	 
 <INPUT TYPE="button" class="button" name="btcair" id="btcair" VALUE="CAIRKAN" ONCLICK="cair()">			   
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <INPUT TYPE="button" name="btcair_skpd" id="btcair_skpd" VALUE="CAIRKAN SP2D" ONCLICK="linkcair()" class="button">


<table border='0' width="100%" style="font-size:11px" >
 
 <tr>
   <td >No Terima </td>
   <td><input type="text" name="nokas" class="input" id="nokas"  style="width:150px" ></td>
   <td>Tgl Terima </td>
   <td><input id="dkas" name="dkas" type="text" disabled style="width:155px" /></td>
   
   <td><input id="nocek" name="nocek" type="text"  style="width:155px" hidden /></td>
   </tr>
</table>

<table border='0' width="100%" style="font-size:11px; border-color: white;" cellspacing="5px" cellpadding="5px" >
 <tr>
   <td width="10%">Nilai</td>
   <td width="40%"><input class="right" type="text" name="total" id="total"  style="width:150px;border:0" align="right" readonly="true" ></td>
   <td width="10%">Tgl Terima </td>
   <td width="40%"><input id="tgl_terima" class="input" name="tgl_terima" type="text" readonly="true" style="width:330px" /></td>
 </tr>
 <tr>
   <td >No SP2D </td>
   <td><input type="text" name="no_sp2d" id="no_sp2d" readonly="true" style="width:330px" class='input' ></td>
   <td>Tgl SP2D </td>
   <td><input id="dd" name="dd" type="text" style="width:330px" class='input' /></td>
 </tr>
 <tr>
   <td >No SPM</td>
   <td><input type="text" name="nospm" id="nospm"  style="width:335px" disabled  ></td>
   <td>Tgl SPM </td>
   <td><input id="tgl_spm" name="tgl_spm" type="text" readonly="true" style="width:330px" class='input' /></td>
 </tr>
 <tr>   
   <td  >No SPP</td>
   <td><input id="nospp" name="nospp" readonly="true" style="width:330px" class='input' /></td>
   <td>Tgl SPP </td>
   <td><input id="tgl_spp" name="tgl_spp" type="text" readonly="true" style="width:330px" class='input' /></td>   
    </tr>
 <tr>
   <td >SKPD</td>
   <td >     
      <input id="dn" name="dn"  readonly="true" style="width:330px" class='input' /></td> 
   <td >Bulan</td>
   <td  ><select  name="kebutuhan_bulan" id="kebutuhan_bulan"  readonly="true" style="width:330px" class='select' disabled>
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1">1  | Januari</option>
     <option value="2">2  | Februari</option>
     <option value="3">3  | Maret</option>
     <option value="4">4  | April</option>
     <option value="5">5  | Mei</option>
     <option value="6">6  | Juni</option>
     <option value="7">7  | Juli</option>
     <option value="8">8  | Agustus</option>
     <option value="9">9  | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
 <tr>
   <td >&nbsp;</td>
   <td ><textarea name="nmskpd" id="nmskpd"style="width:318px"   readonly="true"></textarea></td>
   <td >Keperluan</td>
   <td ><textarea name="ketentuan" id="ketentuan" style="width:318px" readonly="true"></textarea></td>
 </tr>
 <tr>
   <td >No SPD</td>
   <td><input id="sp" name="sp" style="width:330px" class='input' readonly="true"/></td>
   <td >Rekanan</td>
   <td><textarea id="rekanan" name="rekanan" style="width:318px"  readonly="true" > </textarea></td>
 </tr>
 
 <tr>
   <td>Beban</td>
   <td><select name="jns_beban" id="jns_beban" readonly="true" style="width:330px" class='select' disabled >
     <option value="">...Pilih Jenis Beban... </option>
     <option value="1">UP</option>
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS GAJI</option>
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
   </select></td>
  <td width="8%" >BANK</td>
   <td>&nbsp;<input  name="bank1" id="bank1" />
    &nbsp;<input type ="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:150" /></td>
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
   <td width='8%'>Jenis</td>
   <td >&nbsp;<input id="cc" name="dept" style="width:330px" class='input' value=" Pilih Jenis Beban" ></td>
   <td width='8%'>&nbsp;</td>
   <td >&nbsp;</td>
 </tr> 
 
 <tr>
   <td width='8%'>NPWP</td>
   <td ><input type="text" name="npwp" id="npwp" value="" readonly="true" style="width:330px" class='input' /></td>
   <td width='8%'>Rekening</td>
   <td ><input type="text" name="rekening" id="rekening"  value="" readonly="true" style="width:330px" class='input' /></td>
 </tr>       
 
    </table>
     <table id="dg" title=" Detail SPM" style="width:1024px;height:200%;" ></table>
    <div align="right" style="padding: 10px 10px">
    <B>Total Belanja </B><input class="right" type="text" name="rekspm" id="rekspm"  style="width:140px; border:hidden; background-color: #dbdbdb;" align="right" readonly="true" >
        <input class="right" type="hidden" name="rekspm1" id="rekspm1"  style="width:100px" align="right" readonly="true" ><br />
    </div> 

      <table id="pot" title="List Potongan" style="width:1024px;height:150px;" >  </table>
    <div align="right" style="padding: 10px 10px">
        <B>Total Potongan </B>
        <input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px; border:hidden; background-color: #dbdbdb;" align="right" readonly="true" >
    </div>    

   </p>
    </div>
    

   
  
</div>

</div> 

 	
</body>

</html>
<div class="loader1" id="loading1"> <div class="loader2"></div></div>