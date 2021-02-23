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
            height: 800,
            width: 600,
            modal: true,
            autoOpen:false
        });
        $('#kode2').combogrid();
        $('#kode3').combogrid();
        $('#kode4').combogrid();
        $('#kode5').combogrid();
        $('#kode6').combogrid();
        $('#hide2').hide();
        $('#hide3').hide();
        $('#hide4').hide();
        $('#hide1').hide();
        });    
     
     $(function(){
        $('#kode2').combogrid("setValue","");
        $('#kode3').combogrid("setValue","");
        $('#kode4').combogrid("setValue","");
        $('#kode5').combogrid("setValue","");
        $('#kode6').combogrid("setValue","");
        $('#kode').combogrid({  
           panelWidth:700,  
           idField:'id',  
           textField:'title',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/parent',  
           columns:[[  
               {field:'id',title:'Kode SKPD',width:100},  
               {field:'title',title:'Nama Menu',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               id = rowData.id;
               $('#hkode').attr("value",id);
               $('#hide1').show();              
               child1(id)
           }  
       });
    });

    function child1(id){
        $('#kode2').combogrid({  
           panelWidth:700,  
           idField:'id',  
           textField:'title',   
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/parent/'+id,  
           columns:[[  
               {field:'id',title:'Kode SKPD',width:100},  
               {field:'title',title:'Nama Menu',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               id = rowData.id;
               $('#hide2').show();   
               child2(id)
           }  
       });
    
    }
    function child2(id){
        $('#kode3').combogrid({  
           panelWidth:700,  
           idField:'id',  
           textField:'title',   
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/parent/'+id,  
           columns:[[  
               {field:'id',title:'Kode SKPD',width:100},  
               {field:'title',title:'Nama Menu',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               id = rowData.id;
               $('#hide3').show();
               $('#kode3').attr("value",id);                
               child3(id)
           }  
       });
    
    }

    function child3(id){
        $('#kode4').combogrid({  
           panelWidth:700,  
           idField:'id',  
           textField:'title',   
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/parent/'+id,  
           columns:[[  
               {field:'id',title:'Kode SKPD',width:100},  
               {field:'title',title:'Nama Menu',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               id = rowData.id;
               $('#hkode4').attr("value",id);
               $('#hide4').show();                
               child4(id)
           }  
       });
    
    }

    function child4(id){
        $('#kode5').combogrid({  
           panelWidth:700,  
           idField:'id',  
           textField:'title',   
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/parent/'+id,  
           columns:[[  
               {field:'id',title:'Kode SKPD',width:100},  
               {field:'title',title:'Nama Menu',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               id = rowData.id; 
               $('#hkode5').attr("value",id);               
               child5(id)
           }  
       });
    
    }

    function child5(id){
        $('#kode6').combogrid({  
           panelWidth:700,  
           idField:'id',  
           textField:'title',   
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/parent/'+id,  
           columns:[[  
               {field:'id',title:'Kode SKPD',width:100},  
               {field:'title',title:'Nama Menu',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               id = rowData.id;
               $('#hkode6').attr("value",id);                
           }  
       });
    
    }

    function simpan(){
        var kode5= document.getElementById('hkode5').value;
        var kode4= document.getElementById('hkode4').value;

        var kode3= document.getElementById('hkode3').value;
        var kode2= document.getElementById('hkode2').value;
        var kode = document.getElementById('hkode').value;
        var title = document.getElementById('title').value;
        var modul = document.getElementById('modul').value;
        var url = document.getElementById('url').value;
        $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/master/simpan_menu',
                        data: ({kode:kode,kode2:kode2,kode3:kode3,kode4:kode4,kode5:kode5,title:title,modul:modul,url:url}),
                        dataType:"json",
                        success:function(data){
                            status = data;
                            if(status=='1'){
                                alert('Tersimpan');
                                kosong();
                                exit();
                            }else{
                                alert('Gagal');
                                exit();
                            }
                        }
                    });
        }); 
    }
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>PENGESAHAN DPA & DPPA</a></b></u></h3>
    <div align="center"> 
    <table style="width:1024px;" border="0">
            <tr>
                <td width="30%">Parent</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:200px;"/>
                    <input type="text" hidden id="hkode" style="width:200px;"/></td>
            </tr>
            <tr id="hide1">
                <td width="30%">Child 1</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode2" style="width:200px;"/>
                    <input type="text" hidden id="hkode2" style="width:200px;"/></td>
            </tr>
            <tr id="hide2">
                <td width="30%">Child 2</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode3" style="width:200px;"/>
                    <input type="text" hidden id="hkode3" style="width:200px;"/></td>
            </tr>
            <tr id="hide3">
                <td width="30%">Child 3</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode4" style="width:200px;"/>
                    <input type="text" hidden id="hkode4" style="width:200px;"/></td>
            </tr>
            <tr id="hide4">
                <td width="30%">Child 4</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode5" style="width:200px;"/>
                    <input type="text" hidden id="hkode5" style="width:200px;"/></td>
            </tr>
    </table>    
    <table style="width:1024px;" border="0">
        <tr>
            <td>Title Menu</td>
            <td>:</td>
            <td><input type="text" name="title" id="title"></td>
        </tr>
        <tr>
            <td>Nama Modul</td>
            <td>:</td>
            <td><textarea id="modul"></textarea></td>
        </tr>
        <tr>
            <td>URL</td>
            <td></td>
            <td><input type="text" name="url" id="url"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><button class="button" onclick="javascript:simpan()"> Simpan</button></td>
        </tr>
    </table>
    </div>   


</div>

</body>

</html>