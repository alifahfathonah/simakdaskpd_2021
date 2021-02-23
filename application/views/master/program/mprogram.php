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
            height: 460,
            width: 800,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){  
        
          
     $('#kd_bidang_urusan').combogrid({  
       panelWidth:500,  
       idField:'kd_bidang_urusan',  
       textField:'kd_bidang_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/load_bidang_urusan',  
       columns:[[  
           {field:'kd_bidang_urusan',title:'Kode Rekening',width:100},  
           {field:'nm_bidang_urusan',title:'Nama Rekening',width:400},    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_bidang_urusan = rowData.kd_bidang_urusan;
            $("#nm_bidang_urusan").attr("value",rowData.nm_bidang_urusan);
            ambil_nomor(kd_bidang_urusan);        
       }  
     });  

        
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/master/load_program',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
          {field:'kd_program',
        title:'Kode Program',
        width:5,
            align:"center"},           
            {field:'nm_program',
        title:'Nama Program',
        width:30},
            {field:'kd_bidang_urusan',
        title:'Kode Bid. Urusan',
        width:5}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_program          = rowData.kd_program;
          nm_program          = rowData.nm_program;
          kd_bidang_urusan          = rowData.kd_bidang_urusan;
          nm_bidang_urusan          = rowData.nm_bidang_urusan;
          $("#edit").attr("value",'edit');  

          get(kd_program,nm_program,kd_bidang_urusan,nm_bidang_urusan); 
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           kd_13 = rowData.kd_rek13;
           judul = 'Edit Data Urusan'; 
           $("#edit").attr("value",'edit');        
           $("#dialog-modal").dialog('open');
        }
        
        });
       
    });

    function cari(){
       $('#txtcari').on('keyup', function(){
          var kriteria = $(this).val();
          $('#preview_input').text(kriteria);
        $(function(){ 
         $('#dg').edatagrid({
            url: '<?php echo base_url(); ?>/index.php/master/load_program',
            queryParams:({cari:kriteria})
          });        
         });
       });
    }
    
    function ambil_nomor(rek5){
        var edit=document.getElementById('edit').value;
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/ambilnomor_prog',
                    data: ({prog:rek5}),
                    dataType:"json",
                    success:function(data){
                      var rek6= rek5+data;
                      var cek = data.length;
                      if(cek==1){
                        var rek6 = rek5+'.0'+data;
                      }else {
                        var rek6 = rek5+'.'+data;
                      }
                      if(edit==''){
                        $("#kd_program").attr("value",rek6);
                      }

                    }
                });
            });   
    }
    
    function get(kd_program,nm_program,kd_bidang_urusan,oke) {   
        $("#nm_bidang_urusan").attr("value",oke);
        $("#kd_bidang_urusan").combogrid("setValue",kd_bidang_urusan);   
        $("#nm_program").attr("value",nm_program);
        $("#kd_program").attr("value",kd_program);
    }
       
    function kosong(){
        $("#edit").attr("value",'');
        $("#nm_bidang_urusan").attr("value",'');
        $("#kd_bidang_urusan").combogrid("setValue",'');
        $("#nm_program").attr("value",'');
        $("#kd_program").attr("value",'');
    }
    
    

    
    function simpan_rek6(){
        var urusan=$("#kd_bidang_urusan").combogrid("getValue");
        var kd_prog=document.getElementById('kd_program').value;
        var nm_prog=document.getElementById('nm_program').value;
        var edit=document.getElementById('edit').value;

                   $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_prog',
                    data: ({urusan:urusan,nm_prog:nm_prog.toUpperCase(),kd_prog:kd_prog,edit:edit}),
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
        
        
        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Rincian Objek';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');

        } 

    function keluar(){
        $("#dialog-modal").dialog('close');
        //lcstatus = 'edit';
     }    
     

     
     function hapus(){
        var kd_program   = document.getElementById('kd_program').value;
        var cek= confirm("Apakah anda akan menghapus "+kd_program+"?");
        if(cek==false){
            exit();
        }
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_prog';
        $(document).ready(function(){
         $.post(urll,({prog:kd_program}),function(data){
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
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER PROGRAM</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()" disabled>Tambah</a></td>               
        
        <td width="5%"></td>
        <td><input placeholder="Pencarian" type="text" id="txtcari" onclick="javascript:cari();" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA REKENING RINCIAN OBJEK" style="width:900px;height:440px;" >  
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
      <input type="text" id="edit" style="width:100px;" hidden />
     <table align="center" style="width:100%;" border="0">
          <tr>
                <td width="30%">Kode Bid Urusan</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_bidang_urusan" style="width:100px;" onclick="javascript:ambil_nomor()" /><input type="text" id="nm_bidang_urusan" style="width:310px;"/></td>  
            </tr>
          <tr>
                <td width="30%">Kode Program</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_program" disabled style="width:100px;"/></td>  
            </tr>
          <tr>
                <td width="30%">Nama Program</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_program" style="width:400px;"/></td>  
            </tr>           

                 
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_rek6();" disabled>Simpan</a>
            <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();" disabled>Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>