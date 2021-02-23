 	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
     
    $(document).ready(function() 
	{ 
	//get_skpd()
	}); 
    
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var skpd='';
    var urusan='';
        
    
    $(document).ready(function() {

    	$('#dg').edatagrid();
    	$("#giat").combogrid();
		$('#Xskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka_rancang/skpd',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
				var skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);
			    var urus = skpd.substring(0, 4);
			    var urusan= urus.replace("-", ".");
			    $("#urusan").combogrid("setValue",urusan);
			    kegiatan(skpd,urusan);
			    validate_combo(skpd);				
            }  
            });   

        $('#urusan').combogrid({  
            panelWidth:700,  
            idField:'kd_urusan',  
            textField:'kd_urusan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka_rancang/urusan',  
            columns:[[  
                {field:'kd_urusan',title:'Kode Urusan',width:100},  
                {field:'nm_urusan',title:'Nama Urusan',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                urusan = rowData.kd_urusan;
                $("#nm_urusan").attr("value",rowData.nm_urusan);
                var skpd =$("#Xskpd").combogrid("getValue");
             	kegiatan(skpd,urusan);
                
            } 
        });  
    });    
	
		function kegiatan(skpd,urusan=''){
			$('#giat').combogrid({  
	            panelWidth:700,  
	            idField:'kd_kegiatan',  
	            textField:'kd_kegiatan',  
	            mode:'remote',
	            url:'<?php echo base_url(); ?>/index.php/rka_rancang/ld_giat_rancang/'+skpd+'/'+urusan,  
	            columns:[[  
	                {field:'kd_kegiatan',title:'Kode Kegiatan',width:120},  
	                {field:'nm_kegiatan',title:'Nama Kegiatan',width:600},
	                {field:'jns_kegiatan',title:'Jenis Kegiatan',width:40},
	                {field:'lanjut',title:'Lanjut',width:40}
	            ]],
	            onSelect:function(rowIndex,rowData){
	                    kode = rowData.kd_kegiatan;
	                    nama = rowData.nm_kegiatan;
	                    jenis = rowData.jns_kegiatan;
	                    lanjut = rowData.lanjut;
	                    $("#nm_giat").attr("value",rowData.nm_kegiatan);
						$(function(){   
					        $.ajax({
					           type     : "POST",
					           dataType : "json",
					           data     : ({skpd:skpd,giat:kode,jenis:jenis,lanjut:lanjut,nama:nama}),
					           url      : '<?php echo base_url(); ?>/index.php/rka_rancang/psimpan_rancang/', 
					           success  : function(data){
					           					validate_combo(skpd);
					           					$('#dg').edatagrid('reload');
					           }
					        });
				   		});     
	            }

            }); 

		}        												
      
        function validate_combo(skpd){
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka_rancang/select_giat_rancang/'+skpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",

                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:30,
					 align:'left'
					},                    
					{field:'nm_kegiatan',
					 title:'Nama Kegiatan',
					 width:140
					},
                    {field:'jns_kegiatan',
					 title:'Jenis',
					 width:10,
                     align:'center'
                    },
					{field:'rinci',
					 title:'Hapus',
					 width:10,
                     align:'center',
                     formatter:function(rowIndex, rowData){
							rk=rowData.kd_kegiatan;
							return "<a onclick='hapus("+skpd+","+rk+")'>sssss</a>";
						}
                    }
				]],
				onSelect : function(rowIndex, rowData){
					rek=rowData.kd_kegiatan;
					hapus(skpd,rek);
				}
			});
		});
        }  

        function hapus(skpd,rek){
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus kegiatan '+rek+' ?');
				if  (del==true){

						$(function(){   
					        $.ajax({
					           type     : "POST",
					           dataType : "json",
					           url: '<?php echo base_url(); ?>/index.php/rka/ghapus_rancang/'+skpd+'/'+rek, 
					           success  : function(data){
					           	$("#dg").datagrid("unselectAll");
					           			$('#dg').edatagrid('reload');
					           			alert("berhasil");
					           }
					        });	
					        });	
			
				
				}
				}
		}
		function runEffect(){

			if($("#st_lintas").is(':checked')) {	
				$("#urusan").combogrid("enable");
				$("#Xskpd").combogrid("disable");
			} else {
					$("#urusan").combogrid("disable");
					$("#Xskpd").combogrid("enable");
				}   
    }
	</script>
    
</head>
<body>

<div id="content">   
 <!-- <?php echo $prev; ?>-->
  <h3>	<input type="checkbox" id="st_lintas"  onclick="javascript:runEffect();"/> LINTAS URUSAN</h3>
  <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="Xskpd" name="Xskpd" style="width: 100px;border: 0;" />&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:600px;border: 0;"/> </h3>
  <h3>BIDANG URUSAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input disabled="true" id="urusan" name="urusan" style="width: 100px;" />&nbsp;&nbsp;&nbsp;<input id="nm_urusan" name="nm_urusan" readonly="true" style="width:200px;border: 0;"/> </h3>
  
  <h3>SUB KEGIATAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="giat" name="giat" style="width: 150px;" />&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" readonly="true" style="width:650px;border: 0;"/> </h3>
  
   <center><table id="dg" title="Pilih Kegiatan Anggaran Penyusunan" style="width:1100px;height:300%" >  </center>
        

	</table>    	    
        <button type="button" onclick="javascript:hapus()">HAPUS</button>       	
	
	     
 
</div>  	

