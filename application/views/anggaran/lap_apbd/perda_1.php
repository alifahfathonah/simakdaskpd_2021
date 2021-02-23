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
    var ctk = '';
  
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

	function cek($cetak){
		
        url="<?php echo site_url(); ?>cetak_perda/perda1_murni/"+$cetak;
        openWindow( url);
    }
        
    function openWindow( url){
        var  ctglttd = $('#tgl_ttd').datebox('getValue');
        if (ctglttd == '')

          
        { 
            alert("Tanggal Tidak Boleh Kosong"); 
            return;
        }
        if ($('input[name="chkrinci"]:checked').val()=='1'){
          var crinci = "detail";
         } else{
            var crinci = "ringkas";
         }
        lc = '/'+ctglttd+'/'+crinci+'';
        window.open(url+lc,'_blank');
        window.focus();
		  
     } 
	 

   </script>

	<div id="content">      

    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<br />Cetak Keseluruhan <br />
  		<td colspan="3">         
                        <table style="width:100%;" border="0">
                            <td width="20%">TANGGAL TTD</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                            </td> 
                        </table>
        </td> 
        <tr>
                <td><input type="checkbox" name="chkrinci" id="chkrinci" value="1"  /> Cetak Rincian
                </td>
                <td>&ensp;</td>
                <td>&nbsp</td>
            </tr>

        <tr>
            <td> 
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'all');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'all');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'all');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>
        </tr>

        
       </h1>

<h1>
		</div>
