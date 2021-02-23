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
    
    
    
     

        
    $(function(){ 

 



     $('#skpd').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/tukd/skpd_2',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_skpd',
            title:'Kode SKPD',
            width:20},
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:80}
        ]],
        onSelect:function(rowIndex,rowData){
          skpd = rowData.kd_skpd;                                                           
        },
        onDblClickRow:function(rowIndex,rowData){
            cetak();   
        }
    });





    }); 

        $(document).ready(function() {
                      $('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'id',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/pa',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });    

            $("#accordion").accordion();           
            $("#dialog-modal").dialog({            
        });
        });

    function cetakall()
        {
        var url ="<?php echo site_url(); ?>cetak_spp/cetak_register_spp";       
        window.open(url, '_blank');
        window.focus();
        }
    function cetak()
        {

        var ttd1 = $('#ttd1').combogrid('getValue');
        var url ="<?php echo site_url(); ?>cetak_spp/cetak_register_spp";       
        var jenis = document.getElementById('jenis').value;
        var bulan = document.getElementById('bulan').value;
        if (jenis=='1'){
            window.open(url+'/'+skpd+'/'+jenis+'/'+'1/'+ttd1, '_blank');
            window.focus();
        }else{
             window.open(url+'/'+skpd+'/'+jenis+'/'+bulan+'/'+ttd1, '_blank');
            window.focus();
        }
        }
    </script>

</head>
<body>



<div id="content">

<div id="accordion">

<h3><a href="#" id="section1" onclick="javascript:$('#sp2d').edatagrid('reload')">CETAKAN REGISTER SPP</a></h3>
    <div>
    <a>"Click Sekali Grid Hingga Berwarna Kuning, pilih jenis Lalu Cetak"<br/></a>
    
    <p align="right">       
         TTD :<input type="text" name="ttd1" id="ttd1"> &nbsp; &nbsp;&nbsp;
        <a>Jenis : <select name="jenis" id="jenis" style="width:200px;">
                 <option value="1">Keseluhan</option>
                 <option value="2">Perbulan</option>
                </select></a>
        <a>Bulan : <select name="bulan" id="bulan" style="width:200px;">
                 <option value="1">Januari</option>
                 <option value="2">Februari</option>
                 <option value="3">Maret</option>
                 <option value="4">April</option>
                 <option value="5">Mei</option>
                 <option value="6">Juni</option>
                 <option value="7">Juli</option>
                 <option value="8">Agustus</option>
                 <option value="9">September</option>
                 <option value="10">Oktober</option>
                 <option value="11">November</option>
                 <option value="12">Desember</option>
                </select></a>
       
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>               
        
        <table id="skpd" title="List SP2D" style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>


    

   
  
</div>

</div> 


    
</body>

</html>