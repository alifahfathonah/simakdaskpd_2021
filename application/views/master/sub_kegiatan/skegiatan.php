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
        
     $('#kd_giat').combogrid({  
       panelWidth:500,  
       idField:'kd_kegiatan',  
       textField:'kd_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_kegiatan',  
       columns:[[  
           {field:'kd_kegiatan',title:'Kode kegiatan',width:100},  
           {field:'nm_kegiatan',title:'Nama kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           kd_prog = rowData.kd_kegiatan;
           $("#nm_giat").attr("value",rowData.nm_kegiatan.toUpperCase());
		   muncul(kd_prog);  
           
                          
       }  
     });     
        
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_subkegiatan_all',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_sub_kegiatan',
    		title:'Kode Sub Kegiatan',
    		width:15,
            align:"center"},
    	    {field:'kd_kegiatan',
    		title:'Kode Kegiatan',
    		width:15,
            align:"center"},
            {field:'nm_sub_kegiatan',
    		title:'Nama Sub Kegiatan',
    		width:50},
            {field:'jns_sub_kegiatan',
    		title:'Jenis Sub Kegiatan',
    		width:15}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_subgiat = rowData.kd_sub_kegiatan;
          kd_giat = rowData.kd_kegiatan;
          nm_subgiat = rowData.nm_sub_kegiatan;
          jns = rowData.jns_sub_kegiatan;
          nm_giat = rowData.nm_kegiatan;
          $("#edit").attr("value",'edit');  
          get(kd_subgiat,kd_giat,nm_subgiat,jns,nm_giat); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           kd_k = rowData.kd_kegiatan;
           judul = 'Edit Data Kegiatan'; 
           $("#dialog-modal").dialog('open');
 
        }
        
        });
       
    });        

 
    
    function get(kd_subgiat,kd_giat,nm_subgiat,jns,nm_giat) {
        $("#kd_subgiat").attr("value",kd_subgiat);
		$("#nm_subgiat").attr("value",nm_subgiat);
        $("#kd_giat").combogrid("setValue",kd_giat);
        $("#nm_giat").attr("value",nm_giat);
        $("#jns_k").combobox("setValue",jns);
        $("#edit").attr("value",'edit');  
                       
    }
       
    function kosong(){
        $("#edit").attr("value",''); 
        $("#kd_subgiat").attr("value",'');
        $("#nm_subgiat").attr("value",'');
        $("#kd_giat").combogrid("setValue",'');
        $("#nm_giat").attr("value",'');
        $("#jns_k").combobox("setValue",'');
    }
    
    function muncul(kd_prog){
        var edit=document.getElementById('edit').value;   
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/nourut_kegiatan',
                data: ({kdprog:kd_prog}),
                dataType:"json",
                success:function(data){
                    if(data.length==1){
                        var prog= kd_prog+'.0'+data;
                    }else{
                        var prog= kd_prog+'.'+data;
                    }
                    if(edit==''){ 
                        $("#kd_subgiat").attr("value",prog);
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
            url: '<?php echo base_url(); ?>/index.php/master/load_subkegiatan_all',
            queryParams:({cari:kriteria})
          });        
         });
       });
    }    
       function simpan_skpd(){
        var cjns = $('#jns_k').combobox('getValue');
        if(cjns==''){
            alert('Jenis harap diisi'); exit();
        }
        var edit=document.getElementById('edit').value;       
        var kd_giat=$("#kd_giat").combogrid("getValue");
        var kd_subgiat=document.getElementById('kd_subgiat').value;
        var nm_subgiat=document.getElementById('nm_subgiat').value;

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_subgiat',
                    data: ({kd_subgiat:kd_subgiat,kd_giat:kd_giat,nm_subgiat:nm_subgiat,jns:cjns,edit:edit}),
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
        $("#kode_p").combogrid("enable");
		tombol(0);

        
		} 
		
     function keluar(){
        $("#dialog-modal").dialog('close');
        lcstatus = 'edit';
     }    
    
     function hapus(){
        var kd_subgiat   = document.getElementById('kd_subgiat').value;
        var cek= confirm("Apakah anda akan menghapus "+kd_subgiat+"?");
        if(cek==false){
            exit();
        }
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_subgiat';
        $(document).ready(function(){
         $.post(urll,({prog:kd_subgiat}),function(data){
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
<h3 align="center"><u><b><a>INPUTAN MASTER SUB KEGIATAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:print_layar()" disabled>Print</a>
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()" disabled>Tambah</a></td>               
        <td><input type="text" value="" id="txtcari" onclick="javascript:cari();" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA SUB KEGIATAN" style="width:900px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
        
 
    </p> 
    </div>   
</div>
<input type="text" id="edit" hidden style="width:130px;"/>
<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td width="30%">KODE KEGIATAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_giat" readonly="true" style="width:130px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA KEGIATAN</td>
                <td width="1%">:</td>
                <td><input type="text" readonly="true" id="nm_giat" style="width:350px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE SUB KEGIATAN</td>
                <td width="1%">:</td>
                <td><input readonly="true" type="text" enable="true" id="kd_subgiat" style="width:130px;"/><input type="text" enable="true" id="kode1" style="width:130px;" hidden /></td>  
            </tr>
                       
            <tr>
                <td width="30%">NAMA SUB KEGIATAN</td>
                <td width="1%">:</td>
                <td><textarea name="nama" id="nm_subgiat" enable="true" cols="50" rows="2" ></textarea></td>  
            </tr>
            <tr>
                <td width="30%">JENIS</td>
                <td width="1%">:</td>
                <td><input id="jns_k" style="width:250px;" class="easyui-combobox" data-options="
            		valueField: 'value',
            		textField: 'label',
            		data: [{
            			label: '',
            			value: ''
            		},{
            			label: 'PENDAPATAN',
            			value: '4'
            		},{
            			label: 'BELANJA',
            			value: '5'
            		},{
            			label: 'PENERIMAAN PEMBIAYAAN',
            			value: '61'
            		},{
            			label: 'PENGELUARAN PEMBIAYAAN',
            			value: '62'
            		}]"/>
                </td>  
                
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