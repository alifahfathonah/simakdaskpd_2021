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
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 500,
            width: 750,
            modal: true,
            autoOpen:false,
        });
        });    
     
  
        $(function(){
   	     $('#tgl_con').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
				return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            	//return y+'-'+m+'-'+d;
            }
        });
   	});
	
	
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/bud_validasi_spm/load_perbaikan_spm',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        nowrap:"true",                       
        columns:[[
    	    {field:'nm_skpd',
    		title:'SKPD',
    		width:50,
            align:"left"},
            {field:'no_spm',
            title:'no. SPM',
            width:40,
            align:"center"}

        ]],
        onSelect:function(rowIndex,rowData){
                    kd_skpdedit= rowData.kd_skpd;
                    nm_skpdedit= rowData.nm_skpd;
                    no_spmedit = rowData.no_spm;
                    no_sppedit = rowData.no_spp;
                    nmrekanedit = rowData.nmrekan;
                    pimpinanedit = rowData.pimpinan;
                    no_rekedit = rowData.no_rek;
                    npwpedit = rowData.npwp;
                    bankedit = rowData.bank;
                    no_tagihedit = rowData.no_bukti;
                    ketbastedit = rowData.ket_bast;
                    keperluanedit = rowData.keperluan;
                    getedit(kd_skpdedit,nm_skpdedit,no_spmedit,no_sppedit,pimpinanedit,no_rekedit,npwpedit,bankedit,keperluanedit,nmrekanedit,no_tagihedit,ketbastedit)
            
                                       
        },
        onDblClickRow:function(rowIndex,rowData){

           lcidx = rowIndex;
           judul = 'Edit Data ANGGARAN'; 
           edit_data();   
        }
        
        });
        
        
        $(function(){
            $('#sskpd').combogrid();
        });
            
         

            $('#bankedit').combogrid({  
                panelWidth:250,  
                url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:70},  
                           {field:'nama_bank',title:'Nama',width:180}
                       ]],  
                    onSelect:function(rowIndex,rowData){
//$("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });   
        });

       
 function validate_skpd(){
    
     var jns_sp = document.getElementById('jns_anggaran').value;
     
     
     $(function(){
            $('#sskpd').combogrid({  
            panelWidth:850,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/bud_validasi_spm/spm_skpd/'+jns_sp,  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:150},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                xskpd = rowData.kd_skpd;
                $("#nmskpd").attr("value",rowData.nm_skpd);
                
                validate_jenis();
                
            }
            });
            });
            
           
            
            
}
	function getedit(kd_skpdedit,nm_skpdedit,no_spmedit,no_sppedit,pimpinanedit,no_rekedit,npwpedit,bankedit,keperluanedit,nmrekanedit,no_tagihedit,ketbastedit){
            
            $("#kdskpdedit").attr("Value",kd_skpdedit);
            $("#nmskpdedit").attr("Value",nm_skpdedit);
            $("#spmedit").attr("value",no_spmedit);
            $("#no_tagihedit").attr("value",no_tagihedit);
            $("#sppedit").attr("value",no_sppedit);
            $("#norekedit").attr("value",no_rekedit);
            $("#pimpinanedit").attr("value",pimpinanedit);
            $("#npwpedit").attr("value",npwpedit);
            $("#rekanedit").attr("value",nmrekanedit);
            $("#keperluanedit").attr("value",keperluanedit);
            $("#ketbastedit").attr("value",ketbastedit);   
            $("#bankedit").combogrid("setValue",bankedit);          
        } 


    
	
       
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data SPM !';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("no").disabled=true;
        }    
        
    


     function validate_jenis(){
        var jns_ang = document.getElementById('jns_anggaran').value;
        var sskpd = xskpd;
        var krit = '';


        $(function(){ 
                $('#dg').edatagrid({
                  url: '<?php echo base_url(); ?>/index.php/bud_validasi_spm/load_perbaikan_spm/'+sskpd+'/'+jns_ang,
                  queryParams:({kriteria_init:krit})
                });        
               });
     }


      

        function perbaiki_data(){

        var spmedit = document.getElementById('spmedit').value;
        var no_tagihedit = document.getElementById('no_tagihedit').value;
        var sppedit = document.getElementById('sppedit').value;
        var bnkedt  = $("#bankedit").combogrid("getValue") ; 
        var rekanedit =document.getElementById('rekanedit').value;
        var pimpinanedit = document.getElementById('pimpinanedit').value;      
        var norekedit = document.getElementById('norekedit').value; 
        var npwpedit = document.getElementById('npwpedit').value;
        var kepedit = document.getElementById('keperluanedit').value;
        var ketbastedit = document.getElementById('ketbastedit').value;

        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({spmedit:spmedit,sppedit:sppedit,bnkedt:bnkedt,rekanedit:rekanedit,pimpinanedit:pimpinanedit,norekedit:norekedit,npwpedit:npwpedit,kepedit:kepedit,no_tagihedit:no_tagihedit,ketbastedit:ketbastedit}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/bud_validasi_spm/update_data_spm",
            success:function(asg3){
                if (asg3==2){                               
                    alert('Data Gagal Tersimpan');
                    document.getElementById('spmedit').value=asg3;                  
                }else{                  
                    document.getElementById('spmedit').value=asg3;
                    alert('Data Berhasil Tersimpan');
                   keluar2();
                }
            }
         });
        });
        
        validate_jenis();
 

        }
        

        function keluar2(){
        $("#dialog-modal").dialog('close');
    }     

       

  // Created by Tox
    
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">PERBAIKAN DATA SPP & SPM</a></b></u></h3>
    <div>
    <p align="left" >
        <a>&nbsp;&ensp;*) Pilih SKPD dan Jenis SPM terlebih Dahulu !</a>   
    </p>
    <p>
<tr>
    <td>Pilih Jenis Beban</td>
    <td>:</td>
    <select name="jns_anggaran" id="jns_anggaran" onchange="javascript:validate_skpd();" style="height: 27px; width:190px;">    
     <option value="0">...Pilih Jenis... </option>   
     <option value="1">UP</option>
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS Gaji</option>
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
     </select>
 </td>
 </tr>
 </p>
 <p>
<tr>
    <td>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;</td>
    <td>:&ensp;</td>
    <td><input id="sskpd" name="sskpd" style="width:190px;border: 0;" />
    <input id="nmskpd" name="nmskpd" readonly="true" style="width: 400px; border:0;  " /></td>
</tr>
</p>
<p>

</p>
        <table id="dg" title="LIST SPM" style="width:1024px;height:450px;" >  
        </table>
 
     
    </div>   

</div>

</div>


<div id="dialog-modal" title="">
    <fieldset style="border-radius: 5px ">
     <table align="center" style="width:100%;" border="0">
            <tr>
            <td width="110px">Kode SKPD</td>
            <td>:<input id="kdskpdedit" name="kdskpdedit" style="width: 130px;" readonly="true"/><input id="nmskpdedit" name="nmskpdedit" style="width: 400px; border-style: none"readonly="true" /></td>
        </tr>

        <tr>
            <td width="110px">NO SPM</td>
            <td>:<input id="spmedit" name="spmedit" style="width: 400px;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">NO Tagih</td>
            <td>:<input id="no_tagihedit" name="no_tagihedit" style="width: 400px;" readonly="true"/></td>
        </tr>
        <tr>
            <td width="110px">NO SPP</td>
            <td>:<input id="sppedit" name="sppedit" style="width: 400px;" readonly="true"/></td>
        </tr>

         <tr>
            <td width="110px">Rekanan</td>
            <td>:<input id="rekanedit" name="rekanedit" style="width: 400px;" /></td>
        </tr>
       <tr>
            <td width="110px">Pimpinan</td>
            <td>:<input id="pimpinanedit" name="pimpinanedit" style="width: 400px;" /></td>
        </tr>
        <tr>
            <td width="110px">Bank</td>
            <td>:<input id="bankedit" name="bankedit" style="width: 130px;" />
            no rek
            :<input id="norekedit" name="norekedit" style="width: 220px;"/></td>
        </tr>
        <tr>
            <td width="110px">NPWP</td>
            <td>:<input id="npwpedit" name="npwpedit" style="width: 400px;"/></td>
        </tr>
 
        <tr>
            <td width="110px">Keperluan</td>
            <td><textarea name="keperluanedit" id="keperluanedit" rows="3" style="width: 500px;"></textarea></td>
        </tr>
        <tr>
            <td width="110px">Ket.BAST</td>
            <td><textarea name="ketbastedit" id="ketbastedit" rows="5" style="width: 500px;"></textarea></td>
        </tr>
        </table>


        <table align="center">
        <tr>
        <td align="center">
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:perbaiki_data();">Update</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar2();">Keluar</a>         
        </td>
    </tr>
        </table>
  </fieldset>
</div>

  	
</body>

</html>