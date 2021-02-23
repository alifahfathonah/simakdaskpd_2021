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
        
          
     $('#kd_rek5').combogrid({  
       panelWidth:500,  
       idField:'kd_rek5',  
       textField:'kd_rek5',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening13',  
       columns:[[  
           {field:'kd_rek5',title:'Kode Rekening',width:100},  
           {field:'nm_rek5',title:'Nama Rekening',width:400},    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_rek5 = rowData.kd_rek5;
            $("#nm_rek5").attr("value",rowData.nm_rek5);
            ambil_nomor(kd_rek5);        
       }  
     });  

        
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/master/load_rekening6',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
          {field:'kd_rek6',
        title:'Kode Rek 6',
        width:10,
            align:"center"},           
            {field:'nm_rek6',
        title:'Nama Rek 6',
        width:10}//,
        ]],
        onSelect:function(rowIndex,rowData){
          kd_rek6          = rowData.kd_rek6;
          nm_rek6          = rowData.nm_rek6;
          kd_rek5          = rowData.kd_rek5;
          nm_rek5          = rowData.nm_rek5;
          get(kd_rek6,nm_rek6,kd_rek5,nm_rek5); 
          lcidx         = rowIndex;  
                                       
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
            url: '<?php echo base_url(); ?>/index.php/master/load_rekening6',
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
                    url: '<?php echo base_url(); ?>/index.php/master/ambilnomor',
                    data: ({rek5:rek5}),
                    dataType:"json",
                    success:function(data){
                      var rek6= rek5+data;
                      var cek = data.length;
                      if(cek==1){
                        var rek6 = rek5+'00'+data;
                      }else if(cek==2){
                        var rek6 = rek5+'0'+data;
                      }else{
                        var rek6 = rek5+data;
                      }
                      if(edit==''){
                        $("#kd_rek6").attr("value",rek6);
                      }

                    }
                });
            });   
    }
    
    function get(kd_rek6,nm_rek6,kd_rek5,nm_rek5) {    
        $("#kd_rek5").combogrid("setValue",kd_rek5);
        $("#kd_rek6").attr("value",kd_rek6);
        $("#nm_rek5").attr("value",nm_rek5);
        $("#nm_rek6").attr("value",nm_rek6);
    }
       
    function kosong(){
        $("#kd_rek5").combogrid("setValue",'');
        $("#kd_rek6").attr("value",'');
        $("#edit").attr("value",'');
        $("#nm_rek5").attr("value",'');
        $("#nm_rek6").attr("value",'');
    }
    
    

    
    function simpan_rek6(){
        var kd_rek5=$("#kd_rek5").combogrid("getValue");
        var kd_rek6=document.getElementById('kd_rek6').value;
        var nm_rek5=document.getElementById('nm_rek5').value;
        var nm_rek6=document.getElementById('nm_rek6').value;
        var edit=document.getElementById('edit').value;

                   $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_rek6',
                    data: ({rek5:kd_rek5,nm5:nm_rek5,rek6:kd_rek6,nm6:nm_rek6,edit:edit}),
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
        var kd_rek6   = document.getElementById('kd_rek6').value;
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_rek6';
        $(document).ready(function(){
         $.post(urll,({rek6:kd_rek6}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                $("#dialog-modal").dialog('close');
            }
         });
        });    
    } 
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER REKENING RINCIAN OBJEK</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td>               
        
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
                <td width="30%">Rekening 5</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek5" style="width:100px;" onclick="javascript:ambil_nomor()" /><input type="text" id="nm_rek5" style="width:310px;"/></td>  
            </tr>
          <tr>
                <td width="30%">Rekening 6</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek6" disabled style="width:100px;"/></td>  
            </tr>
          <tr>
                <td width="30%">Nama Rek 6</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_rek6" style="width:400px;"/></td>  
            </tr>           

                 
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_rek6();">Simpan</a>
            <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>