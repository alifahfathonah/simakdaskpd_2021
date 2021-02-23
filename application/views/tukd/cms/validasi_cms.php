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
    
	<style>    
    #tagih {
        position: relative;
        width: 500px;
        height: 70px;
        padding: 0.4em;
    }  
    </style>
    <script type="text/javascript">
    
     var  zno_upload = '';   
    
     $(document).ready(function() {
            $("#accordion").accordion();
            $( "#dialog-modal" ).dialog("close");  
            $( "#dialog-modal-batal" ).dialog("close");             
            $( "#dialog-modal" ).dialog({
                height: 450,
                width: 1024,
                modal: true,
                autoOpen:false                
            }); 
            $( "#dialog-modal-batal" ).dialog({
                height: 450,
                width: 600,
                modal: true,
                autoOpen:false                
            });                
           load_sisa_bank(); 
           get_nourut();     
           get_nourutbku(); 
     
             
        });    
    $('#dg').edatagrid('unselectAll');   
    $(function(){                
      $('#dg').edatagrid({
		rowStyler:function(index,row){        
        if ((row.status_validasix==1)){
		   return 'background-color:#B0E0E6';
        }        
		},
		url: '<?php echo base_url(); ?>/index.php/cmsc_validasi/load_listbelum_validasi',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        columns:[[    	    
			{field:'no_voucher',
    		title:'No.',
    		width:8},
            {field:'tgl_voucher',
    		title:'TGL Trans.',
    		width:15},
            {field:'tgl_validasi',
    		title:'TGL Validasi',
    		width:15},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:13,
            align:"center",
			hidden:"TRUE"},
			{field:'username',
    		title:'User',
    		width:22,
            align:"center"},
            {field:'ket',
    		title:'Keterangan',
    		width:38,
            align:"left"},
			{field:'total',
    		title:'Nilai Pengeluaran',            
    		width:20,
            align:"right"},            
            {field:'no_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},            
			{field:'tgl_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_validasi',
    		title:'STT',
    		width:15,
            align:"center"},
            {field:'rekening_awal',
    		title:'Rek Bend',
    		width:10,
            align:"left",hidden:true},
            {field:'nm_rekening_tujuan',
    		title:'Nama Rek',
    		width:10,
            align:"left",hidden:true},
            {field:'rekening_tujuan',
    		title:'Rek Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'bank_tujuan',
    		title:'Bank Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'ket_tujuan',
    		title:'Ket. Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'status_pot',
    		title:'POT',
    		width:10,
            align:"left",hidden:true},
            {field:'bersih',
    		title:'Bersih',            
    		width:10,
            align:"right",hidden:true},
            {field:'jns_spp',
    		title:'JNS SPP',            
    		width:10,
            align:"left",hidden:true}
        ]],
        onSelect:function(rowIndex,rowData){                                                      
         $("#euser").attr("value", rowData.username);
         $("#evoucher").attr("value",rowData.no_voucher);
         $("#etglvoucher").attr("value",rowData.tgl_voucher);
         $("#eupload").attr("value",rowData.no_upload);
         $("#etglupload").attr("value",rowData.tgl_upload);
         $("#ekdskpd").attr("value",rowData.kd_skpd);
         $("#etotal").attr("value",rowData.total);
         $("#erektuju").attr("value",rowData.nm_rekening_tujuan);
         $("#ekettuju").attr("value",rowData.ket_tujuan);

         if(rowData.status_validasix=='1'){
            batal_open();
            $("#dialog-modal").dialog('open');
         }else{
            $("#dialog-modal-batal").dialog('open');
         }
         load_total_sub();
          
/*          if(rowData.status_validasix==1){
            alert('sudah di validasi');
            bersih_list();
            exit();
          } */         
          load_total_sub();
        },
        onDblClickRow:function(rowIndex,rowData){ 
         $("#euser").attr("value", rowData.username);
         $("#evoucher").attr("value",rowData.no_voucher);
         $("#etglvoucher").attr("value",rowData.tgl_voucher);
         $("#eupload").attr("value",rowData.no_upload);
         $("#etglupload").attr("value",rowData.tgl_upload);
         $("#ekdskpd").attr("value",rowData.kd_skpd);
         $("#etotal").attr("value",rowData.total);
         $("#erektuju").attr("value",rowData.nm_rekening_tujuan);
         $("#ekettuju").attr("value",rowData.ket_tujuan);

         if(rowData.status_validasix=='1'){
            batal_open();
            $("#dialog-modal").dialog('open');
         }else{
            $("#dialog-modal-batal").dialog('open');
         }
         load_total_sub();
        }
    }); });
    
    $(function(){
    $('#dg2').edatagrid({
		idField:'id',            
        toolbar:'#toolbar',
            rownumbers:"true", 
            fitColumns:"true",            
            autoRowHeight:"false",
            singleSelect:"true",
            nowrap:"true",
            loadMsg:"Tunggu Sebentar....!!",                               
        columns:[[    	    
			{field:'no_voucher',
    		title:'No.',
    		width:8,hidden:true},
            {field:'tgl_voucher',
    		title:'TGL Trans.',
    		width:15,hidden:true},
            {field:'no_bku',
    		title:'No. BKU',
    		width:10},
            {field:'tgl_validasi',
    		title:'TGL BKU',
    		width:13},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:13,
            align:"center"},
			{field:'username',
    		title:'USER',
    		width:13,
            align:"center"},
            {field:'ket',
    		title:'Keterangan',
    		width:60,
            align:"left"},
			{field:'total',
    		title:'Nilai Pengeluaran',            
    		width:20,
            align:"right"},            
            {field:'no_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},            
			{field:'tgl_upload',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},
			{field:'status_validasi',
    		title:'STT',
    		width:5,
            align:"center",hidden:true},            
            {field:'rekening_awal',
    		title:'Rek Bend',
    		width:10,
            align:"left",hidden:true},
            {field:'nm_rekening_tujuan',
    		title:'Nama Rek',
    		width:10,
            align:"left",hidden:true},
            {field:'rekening_tujuan',
    		title:'Rek Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'bank_tujuan',
    		title:'Bank Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'ket_tujuan',
    		title:'Ket. Tujuan',
    		width:10,
            align:"left",hidden:true},
            {field:'status_pot',
    		title:'POT',
    		width:10,
            align:"left",hidden:true}                     
            ]],
        onSelect:function(rowIndex,rowData){                                                                       
        },
        onDblClickRow:function(rowIndex,rowData){                                       
        }
    }); 
    
    $(function(){
    $('#tglvoucher').datebox({  
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
    $('#tglvalidasi').datebox({  
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
    $('#tglvalidasi_trans').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();    
            	return y+'-'+m+'-'+d;
            }
        });
    });
       
    });
        
    
function cari(){
    var kriteria = $('#tglvoucher').datebox('getValue');
    
    if(kriteria=='' || kriteria==null){
        alert('Tanggal Transaksi Belum dipilih !');
        exit();
    }
    
        $(function(){ 
        $('#dg').edatagrid({
		    url: '<?php echo base_url(); ?>/index.php/cmsc_validasi/load_list_validasi',
            queryParams:({cari:kriteria})
            });        
        });
    }		
    
function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd_cms/no_urut_validasicms',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_validasi").attr("value",data.no_urut);
        							  }                                     
        	});  
        }    

function get_nourutbku()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd_cms/no_urut_validasibku',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#no_bku").attr("value",data.no_urut);
        							  }                                     
        	});  
        }    

    
function load_sisa_bank(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd_cms/load_sisa_bank_val",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_bank").attr("value",n['sisa']);                   
                });
            }
         });
        });
    }    
    
function bersih_list(){

    $('#dg').edatagrid('unselectAll');
    $("#total_trans").attr("value",number_format(0,2,'.',','));
    $('#dg').edatagrid('reload');
    
    load_sisa_bank(); 
    get_nourut();     
    get_nourutbku();
}    

function load_total_sub(){
    var hasil=0;
    var rows = $('#dg').datagrid('getSelections');     
		      for(var p=0;p<rows.length;p++){ 
		          
                  if(rows[p].jns_spp=='4' || rows[p].jns_spp=='6'){
                        hasil = hasil+angka(rows[p].bersih);
                    }else{
                        hasil = hasil+angka(rows[p].total);   
                    } 
                                                          
                   }
    $("#total_trans").attr("value",number_format(hasil,2,'.',','));                             
}
  
function proses_validasi_db(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    if(dd<10){
    dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    
    var gt_tglvalidasi = $('#tglvalidasi_trans').datebox('getValue');
    
    if(gt_tglvalidasi==''){
        alert('Tanggal validasi belum dipilih !');
        exit();
    }
    
    //var harini = yyyy+'-'+mm+'-'+dd;
    var harini = gt_tglvalidasi;
             
     var tot_transval = 0;     
     
     var n_bku = angka(document.getElementById('no_bku').value);
     var n_validasi = angka(document.getElementById('no_validasi').value);
     
    var x = $('#dg').datagrid('getSelected');     
    if(x==null){
        alert('List Data belum dipilih');
        exit();
    }
     
     if(n_bku==''){alert('Refresh App'); exit();}
     if(n_validasi==''){alert('Refresh App'); exit();}
     if(n_validasi=='NaN'){alert('Refresh App'); exit();}
     
     var sis_bank = angka(document.getElementById('sisa_bank').value);
               
     var rows = $('#dg').datagrid('getSelections');                                        
            for(var p=0;p<rows.length;p++){			 
                    
                    if(rows[p].jns_spp=='4' || rows[p].jns_spp=='6'){
                        tot_transval   = tot_transval+ angka(rows[p].bersih);
                    }else{
                        tot_transval   = tot_transval+ angka(rows[p].total);    
                    } 
                
            }              
      
                         
     if(tot_transval > sis_bank){
        alert('Total Transaksi melebihi Saldo Bank');
        exit();
     }                            
                         
     var r = confirm("Apakah data yang akan di-Validasi sudah benar ?");
     if (r == true) {
     
        var dskpd = '';                            
        var csql = '';
        var p=0; var nomorbku=0;
        var i=0;
        var j=1;
            $("#validasi").hide();    
            var rows = $('#dg').datagrid('getSelections'); 
            for(var p=0;p<rows.length;p++){			                                              
                    nomorbku = n_bku+i;                     
                    if(rows[p].status_pot==1){                        
                        i=i+2; 
                    }else{
                        i=i+1;
                    }                                                                                                                                          
                    cno_voucher   = rows[p].no_voucher;
                    ctgl_voucher  = rows[p].tgl_voucher;
                    cno_upload    = rows[p].no_upload;
                    cstt_upload   = rows[p].status_upload;                 
                    cskpd         = rows[p].kd_skpd;
                    cuser         = rows[p].username;
                    cket          = rows[p].ket;                    
                    ctotal        = angka(rows[p].total);                    
                    crekening_awal     = rows[p].rekening_awal;                   
                    cnm_rekening_tujuan  = rows[p].nm_rekening_tujuan;
                    crekening_tujuan   = rows[p].rekening_tujuan;
                    cbank_tujuan  = rows[p].bank_tujuan;
                    cket_tujuan   = rows[p].ket_tujuan;   
                    
                    //if(cskpd.substr(0,7)=='4.08.03'){
                    //    dskpd = cskpd.substr(0,7)+'.00';                        
                    //}else{
                        dskpd = cskpd;
                    //} 
                    
              
                if (p>0) {
                csql = csql+","+"('"+cno_voucher+"','"+ctgl_voucher+"','"+cno_upload+"','"+crekening_awal+"','"+cnm_rekening_tujuan+"','"+crekening_tujuan+"','"+cbank_tujuan+"','"+cket_tujuan+"','"+ctotal+"','"+cskpd+"','"+dskpd+"','"+cstt_upload+"','"+harini+"','1','"+n_validasi+"','"+nomorbku+"','"+cuser+"')";
                } else {
                csql = "values('"+cno_voucher+"','"+ctgl_voucher+"','"+cno_upload+"','"+crekening_awal+"','"+cnm_rekening_tujuan+"','"+crekening_tujuan+"','"+cbank_tujuan+"','"+cket_tujuan+"','"+ctotal+"','"+cskpd+"','"+dskpd+"','"+cstt_upload+"','"+harini+"','1','"+n_validasi+"','"+nomorbku+"','"+cuser+"')";                                            
                }                                                            
			}
            //alert(csql);            
            $(document).ready(function(){               
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trvalidasi_cmsbank',no:n_validasi,sql:csql,skpd:dskpd}),
                    url: '<?php echo base_url(); ?>/index.php/cmsc_validasi/simpan_validasicms',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){
                           $("#validasi").show();                
                            alert('Data Berhasil diproses...!!!');
                            $('#dg').edatagrid('reload');
                            $( "#dialog-modal-batal" ).dialog("close"); 		                 
                            bersih_list();                                                                  					
                        } else{ 
                            $("#validasi").show(); 
                            alert('Data Gagal diproses...!!!');
                        }                                             
                    }
                });
                });           
                                  
            } else {
                $("#validasi").show(); 
                alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
            }   
            $( "#dialog-modal" ).dialog("close");           
        }                 
        
        function batal_open(){
            $("#dialog-modal").dialog('open');
            
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                } 
                if(mm<10){
                    mm='0'+mm;
                } 
            var today = yyyy+'-'+mm+'-'+dd; 
            $('#tglvalidasi').datebox('setValue',today);                           
    
        $(function(){ 
        $('#dg2').edatagrid({
		    url: '<?php echo base_url(); ?>/index.php/cmsc_validasi/load_list_telahvalidasi',
            queryParams:({cari:today})
            });        
        });             
            
            
        }
        
        function batal_close(){
            var today = '';
            
            $(function(){ 

            $('#dg').edatagrid({
		    url: '<?php echo base_url(); ?>/index.php/cmsc_validasi/load_list_validasi',
            queryParams:({cari:today})
            });        
            });
            $('#dg').datagrid('unselectAll');
            $("#dialog-modal").dialog('close');
            $("#dialog-modal-batal").dialog('close');
               
        }
        
        function proses_batal(){
            
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                } 
                if(mm<10){
                    mm='0'+mm;
                } 
            var today = yyyy+'-'+mm+'-'+dd;
            var paramtoday = $('#tglvalidasi').datebox('getValue');  
            
            var x = $('#dg2').datagrid('getSelected');     
            if(x==null){
                alert('List Data belum dipilih');
                exit();
            }
            
            var r = confirm("Apakah data yang akan di-Batalkan sudah benar ?");
            if (r == true) {
                 
            if(today==paramtoday){
            
            var rows = $('#dg2').datagrid('getSelections'); 
            for(var p=0;p<rows.length;p++){			                                              
                    hno_voucher = rows[p].no_voucher;
                    htgl_valid  = rows[p].tgl_validasi;
                    hno_bukti   = rows[p].no_bku;
                    cskpd       = rows[p].kd_skpd;                     
            }
            
            $(document).ready(function(){ 
                $("#btl_validasi").hide();             
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trvalidasi_cmsbank',nobukti:hno_bukti,novoucher:hno_voucher,skpd:cskpd,tglvalid:htgl_valid}),
                    url: '<?php echo base_url(); ?>/index.php/cmsc_validasi/batal_validasicms',
                    success:function(data){                        
                        status = data.pesan;   
                         if (status=='1'){
                             $("#btl_validasi").show();
                            alert('Data Berhasil diproses...!!!');  
                            $( "#dialog-modal" ).dialog("close");  
                            $( "#dialog-modal-batal" ).dialog("close");                
	
                            load_sisa_bank(); 
                            get_nourut();     
                            get_nourutbku();                 
                            batal_close();                                                                  					
                        } else{
                            $("#btl_validasi").show();
                            alert('Data Gagal diproses...!!!');
                        }                                             
                    }
                });
                }); 
            
             
            
            }else{
                 $("#btl_validasi").show();
                alert('Tanggal harus hari ini...');
                exit();
            }
            }else{
                 $("#btl_validasi").show();
                alert('Silahkan cek Kembali...');
                exit();
            }
        }
    </script>

</head>
<body>

<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >VALIDASI - DAFTAR TRANSAKSI NON TUNAI</a></h3>
    <div>
    <p align="center">         
    <table width="100%">
        <tr>
            <td><label><b><i>No Bukti</i></b></label> : 
            <input name="no_bku" type="text" id="no_bku" style="width:50px; border: 0;" readonly="true"/>  &nbsp; <label><b><i>No Validasi</i></b></label> :         
            <input name="no_validasi" type="text" id="no_validasi" style="width:50px; border: 0;"/>
            </td>
            <td>
            </td>
        </tr>  
        <tr>
            <td><label><b>Tanggal Upload</b></label> : 
            <input name="tglvoucher" type="text" id="tglvoucher" style="width:100px; border: 0;"/>            
            &nbsp; 
            <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
            </td>
            <td align="right"><label><b>Aksi</b></label>:            
            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:bersih_list();">Bersihkan List</a> &nbsp;
                                          
            </td>
        </tr>        
        </table>
        <table id="dg" title="List Data Transaksi" style="width:1024px;height:390px;"  >  
        </table>
        <table width="100%" style="text-align: right;">
            <tr>
            <td><label><b>Total Transaksi</b></label> : 
            <input name="total_trans" type="text" id="total_trans" style="text-align:right; width:200px; border: 0;" readonly="true"/>            
            </td>
            </tr>
            <tr>
            <td><label><b>Sisa Saldo Bank</b></label> : 
            <input name="sisa_bank" type="text" id="sisa_bank" style="text-align:right; width:200px; border: 0;" readonly="true"/>            
            </td>
            </tr>        
        </table>
    </p> 
    </div>      
</div>
</div>

<div id="dialog-modal" title="List Data Validasi">
    <fieldset>
        <p>Tanggal Validasi : <input name="tglvalidasi" type="text" id="tglvalidasi" style="width:100px; border: 0;"/> </p>
        <table id="dg2" title="Data Transaksi - Telah Validasi" style="width:1000px;height:280px;"  >  
        </table>
        <table width="100%" >
            <tr style="text-align: center;">
            <td>
            <button class="button-abu"  onclick="javascript:batal_close();"><i class="fa fa-kiri"></i> Kembali </button>
            <button id="btl_validasi" class="button" style="background: red" onclick="javascript:proses_batal();">Batal Validasi</button>
            </td>
            </tr>
        </table>           
    </fieldset>  
</div>
<div id="dialog-modal-batal" title="List Data Validasi" align="center" >
    <br>
    <table id="valid" width="100%" border="0" cellpadding="5px" cellspacing="5px" style="font-size: 12px; font-weight: bold">
        
        <tr>
            <td align="right" width="30%">Tanggal </td>
            <td>: <input name="tglvalidasi_trans" type="text" id="tglvalidasi_trans" style="width:160px; border: 0;"/></td>
        </tr>        
        <tr>
            <td align="right" width="30%">No Upload</td>
            <td>: <input type="text" name="eupload" id="eupload" disabled></td>
        </tr>
        <tr>
            <td align="right" width="30%">Tanggal Upload</td>
            <td>: <input type="text" name="etglupload" id="etglupload" disabled></td>
        </tr>
        <tr>
            <td align="right" width="30%">No Voucher</td>
            <td>: <input type="text" name="evoucher" id="evoucher" disabled></td>
        </tr>
        <tr>
            <td align="right" width="30%">Total</td>
            <td>: <input type="text" name="etotal" id="etotal" disabled></td>
        </tr>
        <tr hidden >
            <td align="right" width="30%">Rekening Tujuan</td>
            <td>: <input type="text" name="erektuju" id="erektuju" disabled></td>
        </tr>
        <tr>
            <td align="right" width="30%">Keterangan</td>
            <td>: <input type="text" name="ekettuju" id="ekettuju" disabled></td>
        </tr>
        <tr>
            <td align="center" colspan="2"> <button class="button-abu"  onclick="javascript:batal_close();"><i class="fa fa-kiri"></i> Kembali </button> <button id="validasi" class="button" onclick="javascript:proses_validasi_db();">Validasi</button></td>
        </tr>
    </table>
  
</div>

</body>

</html>