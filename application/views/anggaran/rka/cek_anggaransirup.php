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
	var usn = '';
                        
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
           url:'<?php echo base_url(); ?>index.php/rka/skpduser',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kd = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());                
           }  
       });
      
	  $(function(){
            $('#bid').combogrid({  
            panelWidth:700,  
            idField:'username',  
            textField:'username',  
            mode:'remote',          
			url:'<?php echo base_url(); ?>index.php/rka/user_cppkrup', 	
            columns:[[  
                {field:'username',title:'User',width:100},  
                {field:'nama',title:'Nama',width:580}    
            ]],
            onSelect:function(rowIndex,rowData){
                skpd = rowData.kd_skpd;
				usn = rowData.username;
                $("#nm_bid").attr("value",rowData.nama);                                              			
            }  
            }); 
            });
	  
        $('#fstatus').combogrid({  
           panelWidth:160,  
           idField:'idx',  
           textField:'dstatus',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/rka/load_status_cek_anggaran',  
           columns:[[  
               {field:'dstatus',title:'Status',width:150}                   
           ]],  
           onSelect:function(rowIndex,rowData){
               kdstatus = rowData.idx;        
               
               $(function(){ 
                $('#dg').edatagrid({
		            url: '<?php echo base_url(); ?>/index.php/rka/load_cek_anggaran_sirup_ppkom',
                    queryParams:({kriteria_init:kdstatus, kriteria_skpd:kd, kriteria_user:usn})
                });        
               });
                                                    
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
        if (row.status_hasil == 'SAMA'){
          return 'color:blue;';
        }else{
          return 'color:red;';  
        }
        },
        columns:[[
           {field:'kd_kegiatan',
    		   title:'Kegiatan',
    		   width:2,
            align:"left"},
    	     {field:'nm_kegiatan',
    		   title:'Nama Kegiatan',
    		    width:5,
            align:"left"},
            {field:'nilai_ang',
            title:'Nilai Anggaran',
            width:2,
            align:"right"},
            {field:'nilai_kas',
            title:'Nilai SIRUP',
            width:2,
            align:"right"},
            {field:'status_hasil',
            title:'Hasil',
            width:0.5,
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
          
          lcidx = rowIndex;                           
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
                          
        }
        });		
		
    });        
    
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
         var status_ang = $('#fstatus').combogrid('getValue');
         var cbid = $('#bid').combogrid('getValue');
		  
		 if(ckdskpd=='' || cbid=='' || status_ang==''){
			 alert('Filter Belum dipilih');
			 exit();
		 } 
		  
        url="<?php echo site_url(); ?>/rka/preview_cetakan_cek_anggaran/"+ckdskpd+'/'+$cetak+'/'+status_ang+'/Report-cek-anggaran'
         
        openWindow( url,$jns );
    }
    
    function lihat_detail(){        
        var status_ang = $('#fstatus').combogrid('getValue');
        var cek_username = $('#bid').combogrid('getValue');
		var rows = $('#dg').datagrid('getSelections');           
				for(var p=0;p<rows.length;p++){				    
					cek_kd_kegiatan    = rows[p].kd_kegiatan;           
				}
                
        if(cek_username==''){
			alert('Pilih List PPKOM');
			exit();
		} 
		
		if(status_ang==''){
			alert('Pilih List Kegiatan');
			exit();
		} 
		
		if(rows==''){
			alert('Pilih List Kegiatan');
			exit();
		}    
        
        url="<?php echo site_url(); ?>/rka/preview_cetakan_cek_anggaran_sirup/"+cek_kd_kegiatan+'/'+status_ang+'/'+cek_username+'/Report-cek-anggaran-RUP'
         
        window.open(url,'_blank');  
        
    }
    
 
 function openWindow( url,$jns ){
        
            lc = '';
      window.open(url+lc,'_blank');
      window.focus();
      
     }  
  
  function carilah(){
        var ckdskpd = $('#kode').combogrid('getValue');
        var cstatus = $('#fstatus').combogrid('getValue');
        var x = document.getElementById('keg').value;
        var cbid = $('#bid').combogrid('getValue');
		
        if(ckdskpd=='' || cstatus=='' || cbid==''){
            alert('Dipilih dulu SKPD, PPKOM dan Status Anggaran');
            exit();
        }
        
        if(x==''){
            alert('Kolom pencarian tidak boleh kosong');
        }
		
        $(function(){ 
                $('#dg').edatagrid({
		          url: '<?php echo base_url(); ?>/index.php/sirup/sirup/load_cek_anggaran_sirup',
                    queryParams:({kriteria_keg:x,kriteria_init: cstatus,kriteria_skpd:ckdskpd,kriteria_user:cbid})
                });        
               });
   }   
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>CEK NILAI ANGGARAN DAN SIRUP</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
                <td width="10%"><b>SKPD</b></td>
                <td width="1%">:</td>
                <td colspan="2">&nbsp;&nbsp;<input  type="text" id="kode" style="width:100px;"/>&nbsp;<input type="text" id="nmskpd" style="border:0;width:550px;"/></td>                
        </tr>
        <tr>
                <td width="10%"><b>PPKOM</b></td>
                <td width="1%">:</td>
                <td colspan="2">&nbsp;&nbsp;<input  type="text" id="bid" style="width:100px;"/>&nbsp;<input type="text" id="nm_bid" style="border:0;width:550px;"/></td>
        </tr> 			
        <tr>
                <td width="10%"><b>Status</b></td>
                <td width="1%">:</td>
                <td >&nbsp;&nbsp;<input type="text" id="fstatus" style="border:0;width:150px;"/></td>
                <td align="right" style="color: red;"><b>*) Ket Hasil: " SAMA " = Telah Sesuai || " TIDAK " = Belum Sesuai</b></td>
        </tr>
		<tr>
                <td width="10%"><b>Pencarian</b></td>
                <td width="1%">:</td>
                <td colspan="2">&nbsp;&nbsp;<input placeholder="Kata Kunci Pencarian Kegiatan" type="text" id="keg" style="border:0;width:350px;"/><a class="easyui-linkbutton" onclick="javascript:carilah();return false" />Cari</a> 
				&nbsp;&nbsp;|&nbsp;&nbsp; 
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" title="Pilih/Klik List Data Kegiatan" onclick="javascript:lihat_detail();">Lihat Detail Paket</a>
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
        <table id="dg" title="LISTING DATA KEGIATAN" style="width:900px;height:500px;" >  
        </table>
        </td>
        </tr>

    </table>    
    
    </p> 
    </div>   
</div>

</body>

</html>