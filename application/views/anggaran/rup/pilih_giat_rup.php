 	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
     
    $(document).ready(function() 
	{ 
	get_skpd()
	}); 
    
	  var idx=0;
	  var tidx=0;
	  var oldRek=0;  
    var skpd='';
    var urusan='';
	  var bid='';
	  var kode_s='';
    var status_s='';
    var dippk='';
    var keg='';
    var x='';
    var jenis=''
    var lanjut='';
    var kode_k='';
    var kode_h='';
      
        $(function(){
            $('#giat').combogrid({  
            panelWidth:750,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            columns:[[  
                {field:'kd_program',title:'Kode Program',width:120},  
				        {field:'kd_kegiatan',title:'Kode Kegiatan',width:140},
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:600},
                {field:'jns_kegiatan',title:'Rek',width:50},
                {field:'lanjut',title:'Jenis Keg',width:50}
            ]]
            }); 
            });
            
        $(function(){
            $('#subgiat').combogrid({  
            panelWidth:750,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            columns:[[  
                {field:'kd_kegiatan',title:'Kode Kegiatan',width:200},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:600}                
            ]] 
            }); 
            });      
      

        $(function(){
            $('#subgiat_02').combogrid({  
            panelWidth:750,  
            idField:'kd_sub_kegiatan',  
            textField:'kd_sub_kegiatan',  
            mode:'remote',
            columns:[[  
                {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:200},  
                {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:600}                
            ]] 
            }); 
            });    

      function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/sirup/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nm_skpd").attr("value",data.nm_skpd);
        								skpd = data.kd_skpd;										
        							  }                                     
        	});  
        }
      
      function validate_skpd(){
		  $(function(){
            $('#skpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/sirup/rka/skpd',  			
			//url:'<?php echo base_url(); ?>index.php/rka/config_skpd',  						
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:580}    
            ]],
            onSelect:function(rowIndex,rowData){
                skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);               	
				
            }  
            }); 
            });
		}
		
			$(function(){
            $('#bid').combogrid({  
            panelWidth:700,  
            idField:'username',  
            textField:'username',  
            mode:'remote',          
			url:'<?php echo base_url(); ?>index.php/sirup/rka/user_cppkrup_giat',
            columns:[[  
                {field:'username',title:'User',width:100},  
                {field:'nama',title:'Nama',width:580}    
            ]],
            onSelect:function(rowIndex,rowData){
                skpd = rowData.kd_skpd;
				        bid = rowData.did;
                usn = rowData.username;
                dippk = rowData.dippk;
                $("#nm_bid").attr("value",rowData.nama);
                $("#did").attr("value",rowData.did);
				        $("#nm_did").attr("value",rowData.username);
				        $("#giat").combogrid("setValue",'');
                $("#nm_giat").attr("value",'');
                $("#subgiat").combogrid("setValue",'');
                $("#nm_subgiat").attr("value",'');    
                validate_combo();             
                validate_giat(skpd);	                                 			
            }  
            }); 
            });
		
        function validate_giat(){
           $(function(){
            $('#giat').combogrid({  
            panelWidth:750,  
            idField:'kd_program',  
            textField:'kd_program',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/sirup/rka/ld_mgiat_pa?kode='+skpd,  			
            columns:[[  
                {field:'kd_program',title:'Kode Program',width:120},  
                {field:'nm_program',title:'Nama Program',width:600},
            ]],
            onSelect:function(rowIndex,rowData){
                   	kode_p = rowData.kd_program;
                    nama = rowData.nm_program;
                    keg = kode_p; 
                    $("#nm_giat").attr("value",rowData.nm_program);    
                    $("#subgiat").combogrid("setValue",'');
                    $("#nm_subgiat").attr("value",'');
                    validate_subgiat(keg);	                               
                    } 
            }); 
            });
		}
        
        function validate_subgiat(keg){
          var xkeg = keg;
          var xnip = $("#bid").combogrid("getValue");
          
		  $(function(){
            $('#subgiat').combogrid({  
            panelWidth:750,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/sirup/rka/load_giatrup/'+xkeg,  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode Kegiatan',width:140},  
			          {field:'nm_kegiatan',title:'Nama Kegiatan',width:600}                
            ]],
            onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_kegiatan;
					          kode_s = rowData.kd_kegiatan;
                    nama = rowData.nm_kegiatan;                   
                    status_s = rowData.statuss;
                    $("#nm_subgiat").attr("value",rowData.nm_kegiatan);      
                    validate_subgiat_02(kode);	                                     
                    } 
            }); 
            });
		}

        function validate_subgiat_02(keg){
          var xkeg = keg;
          var xnip = $("#bid").combogrid("getValue");
          
		   $(function(){
            $('#subgiat_02').combogrid({  
            panelWidth:940,  
            idField:'kd_sub_kegiatan',  
            textField:'kd_sub_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/sirup/rka/load_subgiatrup/'+xkeg,  
            columns:[[  
                {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:120},  
			          {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:400},
                {field:'nm_skpd',title:'Lokasi',width:400}                 
            ]],
            onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_sub_kegiatan;
					          kode_s = rowData.kd_sub_kegiatan;
                    kode_k = rowData.kd_skpd;
                    nama = rowData.nm_sub_kegiatan;                   
                    status_s = rowData.statuss;
                    $("#nm_subgiat_02").attr("value",nama);                                         
                    } 
            }); 
            });
		}
        
        function tambahkeg(){            
            var nipp = $("#bid").combogrid("getValue");
			      var nmdid = document.getElementById('nm_did').value;
            var kegg = $("#subgiat_02").combogrid("getValue");
            var nmkg = document.getElementById('nm_subgiat').value;
            var did = document.getElementById('did').value;
           
            if(nmdid=="" || nipp=="" || kegg=="" || nmkg==""){
                alert("Harus Lengkap !!");
                exit();
            }
            //alert(dippk);
            simpan(nmdid,nipp,kegg,nmkg,did,status_s,dippk);            
        }
        												
        function append_jak(nipp,kegg,nmkg,did){
	   
       $("#dg").edatagrid("selectAll");
        var rows = $("#dg").edatagrid("getSelections");
        var jrow = rows.length;
        jidx     = jrow + 1 ;			
		  	$('#dg').edatagrid('appendRow',{kd_kegiatan:kode_s,id:jidx});            
        }
         
        function validate_combo(){
        var cskpd = document.getElementById('skpd').value;
        var nip = $("#bid").combogrid("getValue");
              
        $(function(){
			  $('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/sirup/rka/cgiatrup/'+nip,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",                 
                 columns:[[
	                {field:'kd_sub_kegiatan',
					         title:'Kode',
					         width:15,
					         align:'left'					 
					       },
                  {field:'nm_sub_kegiatan',
					        title:'Kegiatan',
					        width:85,
					        editor:{type:"text"}
					       },
                 {field:'nm_skpd',
                  title:'lokasi',
                  width:45,
                  editor:{type:"text"}
                 }
				        ]]
			});
		});
        }

    
	$(function(){
	   	  	$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/sirup/rka/select_subgiat',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",						 
				 onSelect:function(rowIndex, rowData){                       
			         oldRek=rowData.kd_sub_kegiatan;	 
               kode_h=rowData.kd_skpd;                           
						  },
              columns:[[
                  {field:'kd_sub_kegiatan',
                   title:'Kode',
                   width:15,
                   align:'left'          
                 },
                 {field:'nm_sub_kegiatan',
                  title:'Sub Kegiatan',
                  width:85,
                  editor:{type:"text"}
                 },
                 {field:'nm_skpd',
                  title:'lokasi',
                  width:45,
                  editor:{type:"text"}
                 }
                ]]
			
			});
  	
		  

		});


        function segarkan(nipp){
            $(function(){	   	   
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/sirup/rka/cgiatrup/'+nipp,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",				 
				 onSelect:function(rowIndex, rowData){                       
						  oldRek=rowData.kd_sub_kegiatan;	    
              kode_h=rowData.kd_skpd;                          
						  },
				columns:[[
                   {field:'kd_sub_kegiatan',
                   title:'Kode',
                   width:15,
                   align:'left'          
                 },
                 {field:'nm_sub_kegiatan',
                  title:'Sub Kegiatan',
                  width:85,
                  editor:{type:"text"}
                 },
                 {field:'nm_skpd',
                  title:'lokasi',
                  width:45,
                  editor:{type:"text"}
                 }
                ]]	
			
			});
  	
		  

		});

        }

		function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_kegiatan);
			}
			return ids.join(':');
		}


		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


    function simpan(nmdid,nipp,kegg,nmkg,did,status_s,dippk){
        var kodek = kode_k;
        alert(kodek)		
        var urll = '<?php echo base_url(); ?>index.php/sirup/rka/cek_subgiat_rup';
        if(status_s=='nonaktif'){

          $(document).ready(function(){
          $.post(urll,({cid:kegg,cuser:nmdid,cnip:nipp,copd:kodek}),function(data){
            status = data;
            alert(status);
           if (status!='0'){
                    
           alert(''+status+ ', Lanjutkan Pindah Kegiatan ?');
           $(function(){
           $('#dg').edatagrid({
             url: '<?php echo base_url(); ?>index.php/sirup/rka/psimpan_rup_nonaktif/'+nmdid+'/'+nipp+'/'+kegg+'/'+did+'/'+dippk+'/'+kodek,
           idField:'id',
           toolbar:"#toolbar",              
           rownumbers:"true", 
           fitColumns:"true",
           singleSelect:"true",                         
           });
           });    
            
            append_jak(nipp,kegg,nmkg);        
            alert("Berhasil Ditambahkan");
                
            }else{
                                  
           $(function(){
           $('#dg').edatagrid({
             url: '<?php echo base_url(); ?>index.php/sirup/rka/psimpan_rup/'+nmdid+'/'+nipp+'/'+kegg+'/'+did,
           idField:'id',
           toolbar:"#toolbar",              
           rownumbers:"true", 
           fitColumns:"true",
           singleSelect:"true",                         
           });
           });    
            
            append_jak(nipp,kegg,nmkg);        
            alert("Berhasil Ditambahkan");
            }
         });
        });

        }else{

          $(document).ready(function(){
         $.post(urll,({cid:kegg,cuser:nmdid,cnip:nipp,copd:kodek}),function(data){
            status = data;
            
            if (status!='0'){
                    
                alert(':'+status);
                
                kosong();
                
                $(function(){
                $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sirup/rka/cgiatrup/'+nipp,                  
                    });        
                });
                
                exit();               
                
            }else{
                                  
                $(function(){
                $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>index.php/sirup/rka/psimpan_rup/'+nmdid+'/'+nipp+'/'+kegg+'/'+did,
                idField:'id',
                toolbar:"#toolbar",              
                rownumbers:"true", 
                fitColumns:"true",
                singleSelect:"true",                         
                    });
                });    
            
            append_jak(nipp,kegg,nmkg);        
            alert("Berhasil Ditambahkan");
            }
         });
        });

        }        
        
        kosong();             
        segarkan(nipp);                                
		}
		
		
       function hapus(){            
				var cnm = document.getElementById('nm_bid').value;
        var copd = kode_h;
        
				var nipp = $("#bid").combogrid("getValue");
        if (cnm==""){
            alert("Tentukan Kegiatan pada PPKOM yang akan dihapus");
            exit();
        }

        if (copd==""){
            alert("Tentukan Lokasi pada PPKOM yang akan dihapus");
            exit();  
        }
                
				var rek=oldRek;                 
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus Sub kegiatan '+rek+' : pada '+cnm+ ' ?');
				if  (del==true){					    
                  
                    $(function(){      
         $.ajax({
            url:'<?php echo base_url(); ?>index.php/sirup/rka/hapus_kegrup/'+rek+'/'+copd,  
                data: ({kegiatan:rek,skpd:copd}),
            type: "POST",
            dataType:"json",                         
            success:function(data){
                if(data=='1'){
                   alert('Berhasil Dihapus');   

                }else{
                    alert('Gagal Dihapus');
                }                                 
             }  
            });
            });
                  
                  		
                }
				}
        kosong();
        segarkan(nipp);       
    
		}
        
        function kosong(){
            $("#bid").combogrid("setValue","");    
             $("#nm_bid").attr("value","");    
             $("#nm_giat").attr("value","");
             $("#giat").combogrid("setValue","");
             $("#nm_subgiat").attr("value","");    
             $("#subgiat").combogrid("setValue","");  
             skpd = ""; 
             bid = "";
             keg = ""; 
        }
    
	function cekppk_only(){
		
         var ckdskpd = document.getElementById('skpd').value;
         var ppkom = $("#bid").combogrid("getValue");  
         url="<?php echo site_url(); ?>index.php/sirup/sirup/cetak_listppk/"+ckdskpd+"/"+ppkom+'/Report-cek-Kegiatan-PPK'
        
        window.open(url,'_blank');
    }

   function cekppk(){
    
        var ckdskpd = document.getElementById('skpd').value;
        var ppkom = "-";
        url="<?php echo site_url(); ?>index.php/sirup/sirup/cetak_listppk/"+ckdskpd+"/"+ppkom+'/Report-cek-Kegiatan-PPK'
        
        window.open(url,'_blank');
    } 
	
	</script>
    
</head>
<body>
<div id="content">
   
 <!-- <?php echo $prev; ?>-->
  <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input id="skpd" name="skpd" readonly="true" style="width: 140px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:500px;border: 0;"/> </h3>
  <h3>PPKOM / USER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="bid" name="bid" style="width: 170px;" />&nbsp;&nbsp;&nbsp;<input id="nm_bid" name="nm_bid" readonly="true" style="width:300px;border: 0; font-weight: bold;"/><input id="nm_did" name="nm_did" type="hidden"/><input type="hidden" id="did" name="did"  /> </h3>
  <h3>P R O G R A M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="giat" name="giat" style="width: 170px;" />&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" readonly="true" style="width:600px;border: 0; font-weight: bold;"/> </h3>
  <h3>K E G I A T A N&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="subgiat" name="subgiat" style="width: 170px;" />&nbsp;&nbsp;&nbsp;<input id="nm_subgiat" name="nm_subgiat" readonly="true" style="width:600px;border: 0; font-weight: bold;"/> </h3>
  <h3>S U B-K E G I A T A N&nbsp;&nbsp;<input id="subgiat_02" name="subgiat_02" style="width: 170px;" />&nbsp;&nbsp;&nbsp;<input id="nm_subgiat_02" name="nm_subgiat_02" readonly="true" style="width:600px;border: 0; font-weight: bold;"/> </h3>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="easyui-linkbutton" iconCls="icon-ok" onclick="javascript:tambahkeg()">TAMBAH KEGIATAN</button> 
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <button class="easyui-linkbutton" iconCls="icon-print" onclick="javascript:cekppk_only()">CETAK KEGIATAN PER PPKOM</button>
  &nbsp;
  <button class="easyui-linkbutton" iconCls="icon-print" onclick="javascript:cekppk()">CETAK SEMUA KEGIATAN PPKOM</button>
  
   <table id="dg" title="List Sub Kegiatan" style="width:1000%;height:285%" ></table>   

        <!--<button class="easyui-linkbutton" iconCls="icon-add" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>        
		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">SIMPAN</button>
		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">BATAL TAMBAH</button>
		<button class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:hapus()">HAPUS</button>-->
    <table border="0" width="99%">
        <tr>
            <td align="right"><button class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:hapus()">HAPUS KEGIATAN</button></td>
            <td><button class="easyui-linkbutton" iconCls="icon-reload" onclick="javascript:segarkan()">REFRESH</button></td>
            <td align="right" width="600px"></td>
        </tr>
    </table>        
        
        
        
</div>  	

