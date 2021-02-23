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
    
        var jenis='';
    var bulan='';    

    get_skpd();
    
    
     $(function(){
             $('#dd').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
                }, onSelect: function(date){
                   var m = date.getMonth()+1;
                    $("#kebutuhan_bulan").attr('value',m);
                    var yy = date.getFullYear();
                    cek_status_ang();
                    var tahunsekarang = date.getFullYear();
                    $("#tahunsekarang").attr("value",tahunsekarang);
                    
                }
            }); 
            
            
            $('#dd').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
                }, onSelect: function(date){
                   var m = date.getMonth()+1;
                    $("#kebutuhan_bulan").attr('value',m);
                    var yy = date.getFullYear();
                    cek_status_ang();
                    var tahunsekarang = date.getFullYear();
                    $("#tahunsekarang").attr("value",tahunsekarang);
                    
                }
            });
            
            
            $('#tglawal').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
                }
                    
                });
                
                $('#tglakhir').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
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
                                        $("#skpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);
                                        kode = data.kd_skpd;
                                        validate_spd(kode);              
                                      }                                     
            });  
        }  

    function pilih(val){        
        ctk = val; 
        if (ctk=='1'){
            $("#div_seluruh").show();
            $("#div_tgl").hide();
        } else if (ctk=='2'){
            $("#div_seluruh").hide();
            $("#div_tgl").show();
            } else {
            exit();
        }                 
    } 
    
    function cetak()
        {
        var skpx = document.getElementById('skpd').value;
        
        
        var tgl_ctk = $('#dd').datebox('getValue');
        
        if(tgl_ctk==''){
            alert('PILIH TANGGAL TANDA TANGAN TERLEBIH DULU !');
            exit();
        }
        
        var pilihctk = ctk;
        
        var kebutuhan_bulan = document.getElementById('kebutuhan_bulan').value;
        var tgl_aw = $('#tglawal').datebox('getValue');
        var tgl_ak = $('#tglakhir').datebox('getValue');
        
        if(pilihctk=='1'){
            var pilawal = 'seluruh';
            var pilakhir = '-';
        }else if(pilihctk=='2'){
            var pilawal = kebutuhan_bulan;
            var pilakhir = '-';
        }else{
            var pilawal = tgl_aw;
            var pilakhir = tgl_ak;
        }
        
        
        var url ="<?php echo site_url(); ?>cetak_spm/cetak_register_spM";       
        window.open(url+'/'+skpx+'/'+tgl_ctk+'/'+pilihctk+'/'+pilawal+'/'+pilakhir, '_blank');
        window.focus();
        }
        

        
        
        
    
    </script>
    
</head>
<body>

<div id="content">

<div id="accordion">

<h3>CETAK REGISTER SPM</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="CETAK REGISTER SP2D" style="width:870px;height:300px;" >
            
        <tr >
            <td colspan="2">
         
                <table style="width:100%;" border="0">
                <tr>
                <td width="20%" height="40" ><B>SKPD</B></td>
                <td width="80%"><input id="skpd" name="skpd" style="width: 100px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
                </tr>
                </table>
          
            </td>
        </tr>
        <div id="div_seluruh">
        <tr >
            <td colspan = "2" width="20%" height="40" ><input type="radio" name="pilihan" value="1" onclick="pilih(this.value)"/><B>Cetak Keseluruhan</B></td>
        </tr>
        </div>
        <div id="div_tgl">
        <tr> 
            <td width="20%" height="40" ><input type="radio" name="pilihan" value="2" onclick="pilih(this.value)"/><B>Cetak per BULAN</B></td>
            <td><select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1">1  | Januari</option>
     <option value="2">2  | Februari</option>
     <option value="3">3  | Maret</option>
     <option value="4">4  | April</option>
     <option value="5">5  | Mei</option>
     <option value="6">6  | Juni</option>
     <option value="7">7  | Juli</option>
     <option value="8">8  | Agustus</option>
     <option value="9">9  | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
    </div>
    <tr>
            <td width="20%" height="40" ><input type="radio" name="pilihan" value="3" onclick="pilih(this.value)"/><B>Cetak per PERIODE</B></td>
            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="tglawal" name="tglawal" type="text" />S/D<input id="tglakhir" name="tglakhir" type="text" /></td>
    </tr>
        <!--<tr>
            <td width="20%" height="40" ><input type="radio" name="pilihan" value="3" onclick="pilih(this.value)"/><B>PERIODE</B></td>
            <td width="80%"><input id="dcetak" name="dcetak" type="text"  style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text"  style="width:155px" /></td>
        </tr>-->

        </tr>        
    <tr>
            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal Cetak</td>
            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="dd" name="dd" type="text" /><input type="hidden" id="dd_spp" name="dd_spp" /></td>
    </tr>

        <tr >
            <td width="20%" height="40" ><B>Register SP2D</B></td>
            <td width="80%"> <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak Layar</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak Pdf</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak excel</a>
            </td>
    
        <tr >
            <td >&nbsp;</td>
            <td >&nbsp;</td>
        </tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

    
</body>

</html>