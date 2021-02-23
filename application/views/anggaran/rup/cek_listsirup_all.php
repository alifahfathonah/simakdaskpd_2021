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
    
    var kdstatus = '';
    var kd = '';
                        
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 420,
            width: 600,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){ 
        $('#kode').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/sirup/rka/skpduser',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kd = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
			   valiskpd(kd);
			   valibid(kd);	
           }  
       });
		
        
        $('#tgldpa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
		
		$('#tgldpasempurna').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
		
         $('#tgldppa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
		      jaka = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
	       }
        });
		
		$('#dg').edatagrid({
		//url: '<?php echo base_url(); ?>/index.php/rka/load_cek_anggaran',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",                       
        rowStyler: function(index,row){

      if (row.revisi == 'YA'){
          return 'color:green;';
        }else{    

        if (row.umumkan == 'SUDAH' && row.final == 'SUDAH'){
          return 'color:#6217FA;';
        }else if (row.umumkan == 'BELUM' && row.final == 'BELUM'){
          return 'color:red;';
        } 
      }  

        
        },
        columns:[[
            {field:'idrup',
    		title:'IDRUP',
    		width:1,
            align:"left"},
			{field:'id',
    		title:'NO',
    		width:0.5,
            align:"left"},
			{field:'nm_paket',
    		title:'Paket',
    		width:10,
            align:"left"},
            {field:'final',
            title:'Final',
            width:1,
            align:"center"},
            {field:'umumkan',
            title:'Umumkn',
            width:1,
            align:"center"},
            {field:'revisi',
            title:'Revisi',
            width:1,
            align:"center"},
			      {field:'paket',
            title:'Paket',
            width:1,
            align:"center"},
            /*{field:'detail',title:'Detail',width:1,align:"center",
                        formatter:function(value,rec){ 
                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit.bmp" onclick="javascript:lihat_detail();" />';
                        }
                        }   */         
        ]],
		
        onSelect:function(rowIndex,rowData){
          ckd_skpd = rowData.kd_skpd;
          csts_dpa = rowData.statu;
          csts_dpa_sempurna = rowData.status_sempurna;
          csts_dppa = rowData.status_ubah;
		  cno_dpa = rowData.no_dpa;
		  cno_dpa_sempurna = rowData.no_dpa_sempurna;
          ctgl_dpa_sempurna = rowData.tgl_dpa_sempurna;
          ctgl_dpa = rowData.tgl_dpa;
          cno_dppa = rowData.no_dpa_ubah;
		  ctgl_dppa = rowData.tgl_dpa_ubah;
          $("#xidrup").attr("value",rowData.idrup);
		  $("#xjnsrup").attr("value",rowData.jenis_paket);	
          lcidx = rowIndex;                           
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
                          
        }
        });		
		
    });        
    
	function valibid(skpd){
		$('#bid').combogrid({  
            panelWidth:700,  
            idField:'username',  
            textField:'username',  
            mode:'remote',          
			url:'<?php echo base_url(); ?>index.php/sirup/rka/user_cppkrup_all', 	
			queryParams:({skpd:skpd}),
            columns:[[  
                {field:'username',title:'User',width:100},  
                {field:'nama',title:'Nama',width:580}    
            ]],
            onSelect:function(rowIndex,rowData){
                skpd = rowData.kd_skpd;
				usn = rowData.username;
                $("#nmbid").attr("value",rowData.nama); 

				$(function(){ 
                $('#dg').edatagrid({
		          url: '<?php echo base_url(); ?>index.php/sirup/sirup/load_cek_list_sirup',
                    queryParams:({kriteria_skpd:kd,kriteria_user:usn})
                });        
               });
			   
            }  
            }); 
	}
	
	function valiskpd(skpd){
		var x= skpd;
		$('#homekd_kegiatan').combogrid({ 
		   url:'<?php echo base_url(); ?>index.php/sirup/sirup/listKegiatan_pa',	
           queryParams:({skpd:skpd}),
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
			   $("#homenm_kegiatan").attr("value",rowData.nm_kegiatan);
			   carilah(kd_giat);			   
           }  
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
  
    function cek($cetak,$jns){
         var ckdskpd = $('#kode').combogrid('getValue');
         
        url="<?php echo site_url(); ?>index.php/sirup/rka/preview_cetakan_cek_anggaran/"+ckdskpd+'/'+$cetak+'/'+status_ang+'/Report-cek-anggaran'
         
        openWindow( url,$jns );
    }
    
    function lihat_detail(){        
        var rows = $('#dg').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){				    
					cek_kd_kegiatan    = rows[p].kd_kegiatan;                     
				}
                
        if(status_ang==''){
			alert('Pilih List Kegiatan');
			exit();
		} 
		
		if(rows==''){
			alert('Pilih List Kegiatan');
			exit();
		}    
        
        url="<?php echo site_url(); ?>index.php/sirup/rka/preview_cetakan_cek_anggaran_sirup/"+cek_kd_kegiatan+'/'+status_ang+'/Report-cek-anggaran-RUP'
         
        window.open(url,'_blank');  
        
    }
    
 
 function openWindow( url,$jns ){
        
            lc = '';
      window.open(url+lc,'_blank');
      window.focus();
      
     }  
  
  function carilah(){
        var ckdskpd = $('#kode').combogrid('getValue');
        var x = $('#homekd_kegiatan').combogrid('getValue');
        var cbid = $('#bid').combogrid('getValue');
		
        if(ckdskpd=='' || cbid=='' || homekd_kegiatan=='' ){
            alert('Dipilih dulu SKPD, PPKOM dan Kegiatan');
            exit();
        }
        
        if(x==''){
            alert('Kolom pencarian tidak boleh kosong');
        }
        
        $(function(){ 
                $('#dg').edatagrid({
		          url: '<?php echo base_url(); ?>/index.php/sirup/sirup/load_cek_list_sirup_cari',
                    queryParams:({kriteria_x:x,kriteria_skpd:ckdskpd,kriteria_user:cbid})
                });        
               });
   }   
  
   function cetak_item(){
        
        var rows = $('#dg').datagrid('getSelected');
        if(rows==null){
            alert('List Data Belum Dipilih');
            exit();
        }
		
		    var idrup = document.getElementById('xidrup').value;
			var jnsrup = document.getElementById('xjnsrup').value;
			if(jnsrup==1){
			var url = '<?php echo site_url(); ?>index.php/sirup/sirup/cetak_listpenyedia/'+idrup+'/PAKET PENYEDIA';
            }else{
			var url = '<?php echo site_url(); ?>index.php/sirup/sirup/cetak_listswakelola/'+idrup+'/PAKET SWAKELOLA';
            }
            window.open(url, '_blank');
            window.focus();            
        
   }	
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>CEK LIST PAKET RUP PA</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
                <td width="10%"><b>SKPD</b></td>
                <td width="1%">:</td>
                <td colspan="2">&nbsp;&nbsp;<input  type="text" id="kode" style="width:100px;"/>&nbsp;&nbsp;<input type="text" id="nmskpd" style="border:0;width:550px;"/></td>                
        </tr>
        <tr>
                <td width="10%"><b>PPKOM</b></td>
                <td width="1%">:</td>
                <td colspan="2">&nbsp;&nbsp;<input  type="text" id="bid" style="width:100px;"/>&nbsp;&nbsp;<input type="text" id="nmbid" style="border:0;width:550px;"/></td>
        </tr> 			
        <tr>
                <td width="10%"><b>Pencarian</b></td>
                <td width="1%">:</td>
                <td colspan="2">&nbsp;&nbsp;<input placeholder="Kata Kunci Kegiatan" type="text" id="homekd_kegiatan" style="border:0;width:150px;"/> 
				&nbsp;<input type="text" id="homenm_kegiatan" style="border:0;width:520px;"/> &nbsp;<a class="easyui-linkbutton" iconCls="icon-print" plain="true" title="Cetak Paket" onclick="javascript:cetak_item();"><b>Cetak Paket</b></a>
				<input type="hidden" id="xidrup" style="border:0;width:100px;"/> <input type="hidden" id="xjnsrup" style="border:0;width:100px;"/> 
				</td>
        </tr> 
        <tr hidden="true">
           <td width="10%">Cetak Laporan</td>
           <td width="1%">:</td>
           <td> 
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'skpd');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'skpd');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'skpd');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>
           </td>    
        </tr>        
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA PAKET" style="width:900px;height:400px;" >  
        </table>
        <b>Note : Warna Biru (sudah di Umumkan oleh PA)</b>
        </td>
        </tr>

    </table>    
    
    </p> 
    </div>   
</div>

</body>

</html>