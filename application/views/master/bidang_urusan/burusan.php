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
        
     $('#kode_f').combogrid({  
       panelWidth:500,  
       idField:'kd_urusan',  
       textField:'kd_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/load_urusan',  
       columns:[[  
           {field:'kd_urusan',title:'Kode Urusan',width:100},  
           {field:'nm_urusan',title:'Nama Urusan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_f").attr("value",rowData.nm_urusan.toUpperCase());                
       }  
     });     
        
        
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_bidang_urusan',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_bidang_urusan',
            title:'Kode Bidang Urusan',
            width:15,
            align:"center"},
            {field:'kd_urusan',
            title:'Kode Urusan',
            width:15,
            align:"center"},
            {field:'nm_bidang_urusan',
            title:'Nama Bidang Urusan',
            width:50}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_u = rowData.kd_bidang_urusan;
          kd_f = rowData.kd_urusan;
          nm_u = rowData.nm_bidang_urusan;
          get(kd_u,kd_f,nm_u); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kd_u,kd_f,nm_u) {
        
        $("#kode").attr("value",kd_u);
        $("#kode_f").combogrid("setValue",kd_f);
        $("#nama").attr("value",nm_u);     
                       
    }
       
    function kosong(){
        $("#kode").attr("value",'');
        $("#kode_f").combogrid("setValue",'');
        $("#nama").attr("value",'');
        $('#kode_f').combogrid("enable"); 
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_bidang_urusan',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_bidang_urusan(){
       
        var ckode = document.getElementById('kode').value;
        var ckode_f= $('#kode_f').combogrid('getValue');
        var cnama = document.getElementById('nama').value;
                
        if (ckode==''){
            alert('Kode Bidaing Urusan Tidak Boleh Kosong');
            exit();
        } 
        if (ckode_f==''){
            alert('Kode Urusan Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama Bidaing Urusan Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_bidang_urusan,kd_urusan,nm_bidang_urusan)";
            lcvalues = "('"+ckode_f+'.'+ckode+"','"+ckode_f+"','"+cnama+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_bidang_urusan',kolom:lcinsert,nilai:lcvalues,cid:'kd_bidang_urusan',lcid:ckode}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else if(status=='1'){
                            alert('Data Sudah Ada..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
                });
            });   
           
        } else{
            
            lcquery = "UPDATE ms_bidang_urusan SET nm_bidang_urusan='"+cnama+"' where kd_bidang_urusan='"+ckode+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
            });
            });
            
            
        }
        
        
      
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Urusan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        $('#kode_f').combogrid("disable"); 
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Urusan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        $('#kode_f').combogrid("enable"); 
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var ckode = document.getElementById('kode').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_bidang_urusan',cnid:ckode,cid:'kd_bidang_urusan'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
                keluar();
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
<h3 align="center"><u><b><a>INPUTAN MASTER BIDANG URUSAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td>               
        
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA BIDANG URUSAN" style="width:900px;height:440px;" >  
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
                <td width="30%">KODE BIDANG URUSAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:100px;"/></td>  
            </tr>
                       
            <tr>
                <td width="30%">NAMA BIDANG URUSAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:360px;"/></td>  
            </tr>
            
            <tr>
                <td width="30%">KODE URUSAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode_f" style="width:50px;"/><input type="text" id="nm_f" style="width:310px;" readonly="true" /></td>  
            </tr> 
            
            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_bidang_urusan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>