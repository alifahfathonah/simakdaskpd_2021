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
	 var kode1 = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var kd_k = '';
	
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 350,
            width: 650,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){  

     $('#kd_prog').combogrid({  
       panelWidth:500,  
       idField:'kd_program',  
       textField:'kd_program',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_program',  
       columns:[[  
           {field:'kd_program',title:'Kode Program',width:100},  
           {field:'nm_program',title:'Nama Program',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           kd_prog = rowData.kd_program;
           $("#nm_prog").attr("value",rowData.nm_program.toUpperCase());
           muncul(kd_prog);  
           
                          
       }  
     });     
        
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_kegiatan_all',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_kegiatan',
    		title:'Kode Kegiatan',
    		width:15,
            align:"center"},
    	    {field:'kd_program',
    		title:'Kode program',
    		width:15,
            align:"center"},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',
    		width:50}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_giat = rowData.kd_kegiatan;
          kd_prog = rowData.kd_program;
          nm_giat = rowData.nm_kegiatan;
          nm_prog = rowData.nm_program;
          get(kd_giat,kd_prog,nm_giat); 
          lcidx = rowIndex;  
                                   
        },
        onDblClickRow:function(rowIndex,rowData){
            $("#dialog-modal").dialog('open');    
           lcidx = rowIndex;
           kd_k = rowData.kd_kegiatan;
           judul = 'Edit Data Kegiatan'; 
 
        }
        
        });
       
    });        

 
    
    function get(kd_giat,kd_prog,nm_giat,nm_prog) {
        $("#edit").attr("value",'edit');  
        $("#kd_giat").attr("value",kd_giat);
		$("#nm_prog").attr("value",nm_prog);
        $("#kd_prog").combogrid("setValue",kd_prog);
        $("#nm_giat").attr("value",nm_giat);
                       
    }
       
    function kosong(){
        $("#edit").attr("value",''); 
        $("#kd_giat").attr("value",'');
        $("#nm_prog").attr("value",'');
        $("#kd_prog").combogrid("setValue",'');
        $("#nm_giat").attr("value",'');
    }
    
    function muncul(kd_prog){
        var edit=document.getElementById('edit').value;

        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/nourut_giat',
                data: ({kdprog:kd_prog}),
                dataType:"json",
                success:function(data){

                    if(data.length==1){
                        var prog= kd_prog+'.2.0'+data;
                    }else{
                        var prog= kd_prog+'.'+data;
                    }
                    if(edit==''){
                        $("#kd_giat").attr("value",prog);
                    }
               }
            });
        });
        
                    
    }
    
    function cari(){
       $('#txtcari').on('keyup', function(){
          var kriteria = $(this).val();
          $('#preview_input').text(kriteria);
        $(function(){ 
         $('#dg').edatagrid({
            url: '<?php echo base_url(); ?>/index.php/master/load_kegiatan_all',
            queryParams:({cari:kriteria})
          });        
         });
       });
    }
    
    function simpan_skpd(){
        var edit=document.getElementById('edit').value;       
        var kd_prog=$("#kd_prog").combogrid("getValue");
        var kd_giat=document.getElementById('kd_giat').value;
        var nm_giat=document.getElementById('nm_giat').value;
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_giat',
                    data: ({kd_prog:kd_prog,kd_giat:kd_giat,nm_giat:nm_giat,edit:edit}),
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
    

        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Kegiatan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
		} 
		
     function keluar(){
        $("#dialog-modal").dialog('close');
        lcstatus = 'edit';
     }    
    
     function hapus(){
        var kd_giat   = document.getElementById('kd_giat').value;
        var cek= confirm("Apakah anda akan menghapus "+kd_giat+"?");
        if(cek==false){
            exit();
        }
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_giat';
        $(document).ready(function(){
         $.post(urll,({prog:kd_giat}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                alert('Data Berhasil Dihapus..!!');
                $('#dg').datagrid('deleteRow',lcidx);   
                $("#dialog-modal").dialog('close');
            }
         });
        });    
    } 
       
      
      function print_layar(){  
		url = '<?php echo base_url(); ?>index.php/master/cetak_kegiatan';
		window.open(url,'_blank');
        window.focus();	  
    }
    
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER KEGIATAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:print_layar()" disabled>Print</a>
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()" disabled>Tambah</a></td>               

        <td><input type="text" placeholder="Pencarian" value="" id="txtcari" onclick="javascript:cari();" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA KEGIATAN" style="width:900px;height:440px;" >  
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
<input type="text" id="edit" hidden style="width:130px;"/>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td width="30%">KODE PROGRAM</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_prog" readonly="true" style="width:130px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA PROGRAM</td>
                <td width="1%">:</td>
                <td><input type="text" readonly="true" id="nm_prog" style="width:350px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE KEGIATAN</td>
                <td width="1%">:</td>
                <td><input type="text" enable="true" disabled id="kd_giat" style="width:130px;"/><input type="text" enable="true" id="kode1" style="width:130px;" hidden /></td>  
            </tr>
                       
            <tr>
                <td width="30%">NAMA KEGIATAN</td>
                <td width="1%">:</td>
                <td><textarea name="nama" id="nm_giat" enable="true" cols="50" rows="3" ></textarea></td>  
            </tr>
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_skpd(); $('#dg').edatagrid('reload'); " disabled>Simpan</a>
		        <a id ="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();" disabled>Hapus</a>
                <a id="back" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>