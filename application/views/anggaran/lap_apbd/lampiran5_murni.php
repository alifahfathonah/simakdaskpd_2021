

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
     
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
    var ctk = '1';
        
    
     $(function(){ 

            $("#accordion").accordion();
             $("#nm_skpd").attr("value",'');
             $('#ttd1').combogrid();
             $('#ttd2').combogrid();
      

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

    function cek2(cetak){
        var  ctglttd    = $('#tgl_ttd').datebox('getValue');
        var  tipe_doc   = document.getElementById('tipe_doc').value;

        var url="<?php echo site_url(); ?>cetak_perda/lampiran5_murni/"+ctglttd+'/'+tipe_doc+'/'+cetak+'/<?php echo $jenis ?>';
 
        if (ctglttd == ''){
            alert("Tanggal wajid diisi.");
        } else {
            window.open(url);
        }
    }
    
  
   </script>

<body>
<input type="text" name="tipe_doc" id="tipe_doc" value="<?php echo $jenis1 ?>" hidden> <!-- untuk cek rka atau dpa -->
<div id="content">
<fieldset style="border-radius: 20px; border: 3px solid green;">
    <legend><h3><b>CETAK <?php echo $jenis ?></b></h3></legend>
    <table align="center" style="width:100%;" border="0">
        <tr hidden> 
            <td width="20%">SKPD</td>
            <td width="1%">:</td>
            <td width="79%"><input id="skpd" name="skpd" style="width: 300px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
            </td>
        </tr>
        <tr> 
            <td width="20%">TANGGAL TTD</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="tgl_ttd" style="width: 300px;" />
            </td>
        </tr>        
        <tr hidden> 
            <td width="20%">TTD 1</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd1" style="width: 300px;" /> 
            </td>
        </tr>    
        <tr hidden> 
            <td width="20%">TTD 2</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd2" style="width: 300px;" /> 
            </td>
        </tr>   
        <tr> 
            <td width="20%">Cetak</td>
            <td width="1%">:</td>
            <td width="79%">
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(0,'skpd','0');return false" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(1,'skpd','0');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>

            </td>
        </tr>   
        </table>  
</fieldset> 
<embed src='<?php echo site_url(); ?>cetak_perda/lampiran5_murni/<?php echo $this->session->userdata('pcThang'); ?>-1-1/<?php echo $jenis1 ?>/0' width='100%' height='600px'></embed>
<label id="cetakan"></label>
</div>    
</body>
