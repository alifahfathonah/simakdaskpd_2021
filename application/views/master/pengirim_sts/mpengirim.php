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
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    

     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 250,
            width: 600,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_mpengirim',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"false",
        nowrap:"true",                       
        columns:[[
    	    {field:'kd_skpd',
    		title:'Kode SKPD',
    		width:15,
            align:"center"},
            {field:'nm_pengirim',
    		title:'Nama Pengirim',
    		width:50}
        ]],
        onSelect:function(rowIndex,rowData){
          kd = rowData.kd_pengirim;
          nm = rowData.nm_pengirim;
          lcidx = rowIndex; 
          get(kd,nm);  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Fungsi'; 
           edit_data();   
        }
        
        });
       
    });        

 
    function get_skpd()
        {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                        kd_skpd = data.kd_skpd;
                                        $("#nmskpd").attr("value",data.nm_skpd.toUpperCase());
                                        $("#sskpd").attr("value",data.kd_skpd);
                                        
                                      }                                     
            });
        }
        
    function get_nomor_pengirim()
        {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/master/config_mpengirim',
                type: "POST",
                dataType:"json",                         
                success:function(data){                   
                                         $("#kode").attr("value",data.no_urut);                                                                              
                                      }                                     
            });
        }    

    function get(kd,nm) {
        
        $("#kode").attr("value",kd);
        $("#nama").attr("value",nm);     
                       
    }
       
    function kosong(){
        //$("#kode").attr("value",'');
        $("#nama").attr("value",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_program',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_program(){
       
        var ckode = document.getElementById('kode').value;
        var cnama = document.getElementById('nama').value;
        var cskpd = document.getElementById('sskpd').value;
                
        if (cnama==''){
            alert('Nama Pengirim Tidak Boleh Kosong');
            exit();
        }
        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_pengirim,nm_pengirim,kd_skpd)";
            lcvalues = "('"+ckode+"','"+cnama+"','"+cskpd+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_pengirim',kolom:lcinsert,nilai:lcvalues,cid:'kd_pengirim',lcid:ckode}),
                    dataType:"json"
                });
            });   
           
        } else{
            
            lcquery = "UPDATE ms_pengirim SET nm_pengirim='"+cnama+"' where kd_pengirim='"+ckode+"' and kd_skpd='"+cskpd+"'";            

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery,st_query1:lcquery1}),
                dataType:"json"
            });
            });
            
            
        }
        
        
        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Pengirim STS';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Pengirim STS';
        $("#dialog-modal").dialog({ title: judul });
        get_skpd();
        get_nomor_pengirim();
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        document.getElementById("nama").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var ckode = document.getElementById('kode').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_pengirim',cnid:ckode,cid:'kd_pengirim'}),function(data){
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
  
    
  
   </script>

</head>
<body>

<div id="content"> 

<h3 align="center"><u><b><a>INPUTAN MASTER PENGIRIM STS</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
            <!--<td width="10%"></td>--> 
            <td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah</a></td>
            <!--<td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
            <input type="text" value="" id="txtcari" style="width:300px;"/></td>-->
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA PENGIRIM STS" style="width:900px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
        
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
           <tr>
                <td width="30%">S K P D</td>
                <td width="1%">:</td>
                <td><input type="text" id="sskpd" readonly="true" style="width:100px;border: 0;"/><input type="text" id="nmskpd" readonly="true" style="width:250px;border: 0;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:150px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">NAMA PENGIRIM</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:360px;"/></td>  
            </tr>
            
            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_program();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>