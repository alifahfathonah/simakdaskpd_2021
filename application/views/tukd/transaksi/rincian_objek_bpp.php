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
    var nip='';
	var kdskpd='';
	var kdrek5='';
    var kode='';
    var jns_bp = "bpp";
	
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800            
            });
             get_skpd();               
        });   
    
//	$(function(){
//	$('#sskpd').combogrid({  
//		panelWidth:630,  
//		idField:'kd_skpd',  
//		textField:'kd_skpd',  
//		mode:'remote',
//		url:'<?php echo base_url(); ?>index.php/akuntansi/skpd',  
//		columns:[[  
//			{field:'kd_skpd',title:'Kode SKPD',width:100},  
//			{field:'nm_skpd',title:'Nama SKPD',width:500}    
//		]],
//		onSelect:function(rowIndex,rowData){
//			kdskpd = rowData.kd_skpd;
//			$("#nmskpd").attr("value",rowData.nm_skpd);
//			$("#skpd").attr("value",rowData.kd_skpd);
//			validate_giat(kdskpd);
//            validate_ttd(kdskpd);
//            validate_rek(kode);
//		}  
//		});
//        
//       
//	});
    
    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        								kdskpd = data.kd_skpd;
                                        validate_giat(kdskpd);
                                        validate_ttd(kdskpd);
                                        validate_rek(kode); 
                                        
        							  }                                     
        	});
             
        } 
	
     function validate_giat(){
		  $(function(){
            $('#giat').combogrid({  
            panelWidth:700,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/tukd/ld_giat_rinci_objek_sub/'+kdskpd,  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode Kegiatan',width:150},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:660}    
            ]],
            onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_kegiatan;                    
                    $("#nm_giat").attr("value",rowData.nm_kegiatan);                    
                    validate_rek(kode);
                    } 
            }); 
            });
		}
     function validate_rek(){
	   $(function(){
	   $('#kdrek5').combogrid({  
		panelWidth:630,  
		idField:'kd_rek5',  
		textField:'kd_rek5',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/tukd/ld_rek_rinci_objek_sub/'+kode,  
		columns:[[  
			{field:'kd_rek5',title:'Kode Rekening',width:100},  
			{field:'nm_rek5',title:'Nama Rekening',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			rekening = rowData.kd_rek5;
			$("#kdrek5").attr("value",rowData.kd_rek5);
			$("#nmrek5").attr("value",rowData.nm_rek5);
		}  
		}); 
	});
    }
    
    $(function(){
   	     $('#dcetak').datebox({  
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
   	     $('#dcetak2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});
	//cdate = '<?php echo date("Y-m-d"); ?>';
 function validate_ttd(){
   $(function(){  
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'id_ttd',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/kpa',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd2").attr("value",rowData.nama);
           } 
            });

		$('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
			
         
            $('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'id_ttd',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd_bpp/BPP',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd1").attr("value",rowData.nama);
           } 
            });          
         });             
     }  


		function cetak(ctk)
        {
			var spasi  = document.getElementById('spasi').value; 
            var kgiat = kode.split(" ").join("");;      
			var skpd   = kdskpd.split(" ").join("");; 
			var rekening   = $("#kdrek5").combogrid('getValue');
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
		
			if(kode==''){
				alert('Kegiatan tidak boleh kosong!');
				exit();
			}
			if(rekening==''){
				alert('Rekening tidak boleh kosong!');
				exit();
			}
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(dcetak==''){
				alert('Tanggal tidak boleh kosong!');
				exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			
			var rek5   = rekening.split(" ").join("");
			var ttd_1 =ttd1.split(" ").join("123456789");
			var ttd_2 =ttd2.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>cetak_tukd/cetak_rincian_objek";  
			window.open(url+'/'+dcetak+'/'+ttd_1+'/'+kdskpd+'/'+rek5+'/'+dcetak2+'/'+kgiat+'/'+ctglttd+'/'+ttd_2+'/'+ctk+'/'+spasi+'/'+jns_bp, '_blank');
			window.focus();
        }
		
		
		function cetak2(ctk)
        {
			var spasi  = document.getElementById('spasi').value;
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');
            var kgiat = kode.split(" ").join("");;      
			var skpd   = kdskpd.split(" ").join("");; 
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(dcetak==''){
				alert('Tanggal tidak boleh kosong!');
				exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 =ttd1.split(" ").join("123456789");
			var ttd_2 =ttd2.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>cetak_tukd/cetak_rincian_objek_kegiatan";  
			window.open(url+'/'+dcetak+'/'+ttd_1+'/'+kdskpd+'/'+dcetak2+'/'+kgiat+'/'+ctglttd+'/'+ttd_2+'/'+ctk+'/'+spasi+'/'+jns_bp, '_blank');
			window.focus();
        }
		
		function cetak3(ctk)
        {
			var spasi  = document.getElementById('spasi').value; 
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');
			var skpd   = kdskpd.split(" ").join("");; 
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(dcetak==''){
				alert('Tanggal tidak boleh kosong!');
				exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 =ttd1.split(" ").join("123456789");
			var ttd_2 =ttd2.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>cetak_tukd/cetak_rincian_objek_all";  
			window.open(url+'/'+dcetak+'/'+ttd_1+'/'+kdskpd+'/'+dcetak2+'/'+ctglttd+'/'+ttd_2+'/'+ctk+'/'+spasi+'/'+jns_bp, '_blank');
			window.focus();
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


<h3>CETAK LAPORAN RINCIAN PEROBJEK</h3>

    
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" width="100%" style="height:200px;" >  
		<tr >
			<td width="20%"  ><B>SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
		</tr>
        <tr >
			<td width="20%"  ><B>KEGIATAN</B></td>
			<td width="80%"><input id="giat" name="giat" style="width: 175px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" style="width: 450px; border:0;" /></td>
		</tr>

		<tr >
			<td width="20%"  ><B>REKENING</B></td>
			<td width="80%"><input id="kdrek5" name="kdrek5" style="width: 175px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmrek5" name="nmrek5" style="width: 450px; border:0;" /></td>
		</tr>

		<tr >
			<td width="20%"  ><B>PERIODE</B></td>
			<td width="80%"><input id="dcetak" name="dcetak" type="text"  style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text"  style="width:155px" /></td>
		</tr>
		<tr >
			<td width="20%"  ><B>TANGGAL TTD</B></td>
			<td width="80%"><input id="tgl_ttd" name="tgl_ttd" style="width: 150px;" /></td>
		</tr>
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
							<td width="20%">Bendahara Pengeluaran Pembantu</td>
                            <td><input type="text" id="ttd1" style="width: 200px;" /> &nbsp;&nbsp;
							<input type="text" id="nm_ttd1" readonly="true" style="width: 200px;border:0" /> 
							
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
							<td width="20%">Pengguna Anggaran</td>
                            <td><input type="text" id="ttd2" style="width: 200px;" /> &nbsp;&nbsp;
							<input type="nm_ttd2" id="nm_ttd2" readonly="true" style="width: 200px;border:0" /> 
							
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
							<td width="20%">Spasi</td>
                            <td><input type="number" id="spasi" style="width: 100px;" value="1"/> 
							
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
		<tr>
			<td colspan="2" align="center">
			<input type="text" id="1" style="width:700px;border:0" placeholder="Cetak Per-rekening" readonly="true"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak Layar</a>&nbsp; &nbsp; &nbsp;
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak PDF</a>
			</td>			
		</tr>
		<tr>
			<td colspan="2" align="center">
			<input type="text" id="1" style="width:700px;border:0" placeholder="Cetak Langsung Perkegiatan, Tidak perlu pilih rekening namun Loading cetakan lebih lama" readonly="true"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(0);">Cetak Layar</a>&nbsp; &nbsp; &nbsp;
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(1);">Cetak PDF</a>
			</td>			
		</tr>
		<tr>
			<td colspan="2" align="center">
			<input type="text" id="1" style="width:700px;border:0" placeholder="Cetak Langsung semua Rekening, Loading cetakan lebih lama" readonly="true"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak3(0);">Cetak Layar</a>&nbsp; &nbsp; &nbsp;
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak3(1);">Cetak PDF</a>
			</td>			
		</tr>
		
		
		<tr >
			<td ></td>
			<td ></td>
		</tr>
        </table>                      
    </p> 
   
</div>


 	
</body>

</html>