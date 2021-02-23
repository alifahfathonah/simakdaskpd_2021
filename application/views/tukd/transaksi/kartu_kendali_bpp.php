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
    var nip='';
    var kdskpd='';
    var kdrek5=''; 
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800            
            });
             get_skpd();               
        });   
    
    
    $(function(){  
            $('#ttd').combogrid({  
                panelWidth:600,  
                idField:'id_ttd',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PPTK',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama").attr("value",rowData.nama);
           } 
            }); 
            
             $('#bulan').combogrid({  
           panelWidth:150,
           panelHeight:340,  
           idField:'bln',  
           textField:'nm_bulan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/rka/bulan',  
           columns:[[ 
               {field:'nm_bulan',title:'Nama Bulan',width:140}    
           ]] 
       });
                
         });
    
        $(function(){  
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'id_ttd',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama2").attr("value",rowData.nama);
           } 
            });          
         });
         
         $(function(){  
            $('#ttd3').combogrid({  
                panelWidth:600,  
                idField:'id_ttd',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/KPA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama3").attr("value",rowData.nama);
           } 
            });          
         });
         
         $(function(){  
            $('#kdgiat').combogrid({  
                panelWidth:600,  
                idField:'kd_kegiatan',  
                textField:'kd_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_giat_trans',  
                columns:[[  
                    {field:'kd_kegiatan',title:'Kode',width:200},
                    {field:'nm_kegiatan',title:'Nama Kegiatan',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nmgiat").attr("value",rowData.nm_kegiatan);
           } 
            });          
         });
        $(function(){   
         $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        });
        $(function(){   
         $('#periode1').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        });
        $(function(){   
         $('#periode2').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        }); 
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    
    function get_skpd()
        {
        
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                        $("#sskpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);
                                       // $("#skpd").attr("value",rowData.kd_skpd);
                                        kdskpd = data.kd_skpd;
                                        
                                      }                                     
            });
             
        }
    
        
        function cetak(ctk)
        {
            var spasi  = document.getElementById('spasi').value; 
            var nip     = nip;
            var skpd   = kdskpd; 
            //alert(skpd);
            var  giat = $('#kdgiat').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            var cbulan = $('#bulan').combogrid('getValue');
            var  ttd = $('#ttd').combogrid('getValue');
            ttd = ttd.split(" ").join("123456789");
            var  ttd2 = "x";
            //ttd2 = ttd2.split(" ").join("123456789");
            var  ttd3 = $('#ttd3').combogrid('getValue');
            ttd3 = ttd3.split(" ").join("123456789");
            
            
            var url    = "<?php echo site_url(); ?>cetak_tukd/cetak_kartu_kendali_bpp";  
            
            if(giat==''){
            alert('Pilih Kegiatan dulu')
            exit()
            }
            if(cbulan==''){
            alert('Bulan Tidak Boleh Kosong')
            exit()
            }
            if(ttd==''){
            alert('Pilih Bendahara Pengeluaran dulu')
            exit()
            }
            window.open(url+'/'+skpd+'/'+giat+'/'+ctk+'/'+ttd+'/'+ttd2+'/'+ctglttd+'/'+spasi+'/'+cbulan+'/'+ttd3,'_blank')
            
            //window.open(url+'/'+skpd+'/'+giat+'/'+ctk+'/'+ttd+'/'+ttd2+'/'+ctglttd+'/'+spasi+'/'+cbulan+'/'-'/'+ttd3, '_blank');
            window.focus();
        }
        

    </script>

    <STYLE TYPE="text/css"> 
         input.right{ 
         text-align:right; 
         } 
    </STYLE> 

</head>
<body>

<div id="content">



<h3>KARTU KENDALI KEGIATAN BPP</h3>
    
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:100%;height:200px;" >  
        <tr >
            <td width="20%" height="40" ><B>SKPD</B></td>
            <td width="80%"><input readonly id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
        </tr>
       <tr>
                <td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Kode Kegiatan</td>
                            <td><input type="text" id="kdgiat" style="width: 200px;" /> &nbsp;&nbsp;
                            <input type="nmgiat" id="nmgiat" readonly="true" style="width: 300px;border:0" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
         <tr>
                <td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Tanggal TTD</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>

            <tr>
                <td colspan="4">
                <div id="div_tgl1">
                        <table style="width:100%;" border="0">
                             <td width="20%">BULAN</td>
                            <td width="1%">:</td>
                            <td width="79%"><input id="bulan" name="bulan" style="width: 100px;" />
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
        <tr>
        <tr>
        <td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">PPTK</td>
                            <td><input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
                            <input type="nama" id="nama" readonly="true" style="width: 200px;border:0" /> 
                            
                            </td> 
                        </table>
                </div>
        </td> 
        </tr>
        <tr>
        <td colspan="4">
                <div id="div_bend3">
                        <table style="width:100%;" border="0">
                            <td width="20%">Kuasa Pengguna Anggaran</td>
                            <td><input type="text" id="ttd3" style="width: 200px;" /> &nbsp;&nbsp;
                            <input type="nama3" id="nama3" readonly="true" style="width: 200px;border:0" /> 
                            
                            </td> 
                        </table>
                </div>
        </td> 
        </tr>
        <tr>
        <td colspan="4">
                <div id="div_bend2">
                        <table style="width:100%;" border="0">
                            <td width="20%">Spasi</td>
                            <td><input type="number" id="spasi" style="width: 100px;" value="1"/> 
                            
                            </td> 
                        </table>
                </div>
        </td> 
        </tr>
        <tr >
            <td colspan="2" align="center">
                <button class="button-abu" onclick="javascript:cetak(0);"><i class="fa fa-print"></i> Cetak Layar</button>
                <button class="button-abu" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> Cetak PDF</button>

            </td>
        </tr>
        
        </table>                      
    </p> 
    

</div>


    
</body>

</html>