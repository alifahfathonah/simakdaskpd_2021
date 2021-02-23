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
    
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0;
    var lcidx    = 0;
    var lcstatus = '';
    var pidx     = 0;
                    
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height   : 600,
            width    : 1000,
            modal    : true,
            autoOpen : false
        });
     });    

     
     $(function(){  
     $('#rek5').combogrid({  
       panelWidth : 500,  
       idField    : 'kd_rek6',  
       textField  : 'kd_rek6',  
       mode       : 'remote',
       url        : '<?php echo base_url(); ?>index.php/master/ambil_rekening5_ar',  
       columns    : [[  
           {field:'kd_rek6',title:'Kode Rekening',width:100},  
           {field:'nm_rek6',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_rek5 = rowData.kd_rek6;
            $("#nm_u").attr("value",rowData.nm_rek6.toUpperCase());
       }  
     });     
        
    $('#uraian').combogrid({  
       panelWidth : 500,  
       idField    : 'uraian',  
       textField  : 'uraian',  
       mode       : 'remote',
       url        : '<?php echo base_url(); ?>index.php/master/ambil_standar_harga',  
       columns    : [[  
           {field:'uraian',title:'Uraian',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            //$("#nm_13").attr("value",rowData.nm_rek13);
            //$("#kd_barang").attr("value",rowData.kd_barang);
            $("#uraian").attr("value",rowData.uraian);
            $("#merk").attr("value",rowData.merk);
            $("#satuan").attr("value",rowData.satuan);
            $("#harga").attr("value",number_format(rowData.harga,2,'.',','));
            //$("#harga").attr("value",rowData.harga);
            $("#keterangan").attr("value",rowData.keterangan);
       }  
     });
     
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_ar',
        idField       : 'id',            
        rownumbers    : "true", 
        fitColumns    : "true",
        singleSelect  : "true",
        autoRowHeight : "false",
        loadMsg       : "Tunggu Sebentar....!!",
        pagination    : "true",
        nowrap        : "true",                       
        columns       : [[
            {field:'kd_rek6',
            title:'Kode',
            width:23,
            align:"left"},
            {field:'nm_rek6',
            title:'Rekening',
            width:100,
            align:"left"},
            {field:'kd_barang',
            title:'Kode Barang',
            width:40,
            align:"left"},
            {field:'uraian',
            title:'Uraian',
            width:130,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_s  = rowData.kd_rek6;
          nm_s  = rowData.uraian;
          merk  = rowData.merk;
          satuan  = rowData.satuan;
          harga  = rowData.harga;
          keterangan  = rowData.keterangan;
          
          get(kd_s,nm_s,merk,satuan,harga,keterangan); 
          lcidx = rowIndex;  
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           nm_s  = rowData.uraian;
           judul = 'Edit Data Urusan'; 
           //alert(nm_s);
           edit_data(nm_s);   
           //load_detail(nm_s);
        }
        });

        
    $('#dg2').edatagrid({
        //var crekening = $("#uraian").combogrid("getValue") ;
        url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_detail_ar',
        //queryParams   : ({uraian:crekening}),
        idField       :'id',
        toolbar       :"#toolbar",              
        rownumbers    :"true", 
        fitColumns    :false,
        autoRowHeight :"false",
        singleSelect  :"true",
        nowrap        :"false",          
        columns       : [[{field:'id',        title:'id',           width:70, align:"left",hidden:"true"},
                          
                          {field:'kd_rek6',   title:'Rekening',     width:80, align:"left",hidden:"true"},
                          //{field:'kd_barang',    title:'Barang',       width:295,align:"left"},
                          {field:'uraian',    title:'Uraian',       width:295,align:"left"},
                          {field:'merk',      title:'Merk',         width:100,align:"left"},
                          {field:'satuan',    title:'Satuan',       width:100,align:"left"},
                          {field:'harga',     title:'Harga',        width:150,align:"right"},
                          {field:'keterangan',title:'Keterangan',   width:200,align:"left"},
                          {field:'hapus',     title:'Hapus',        width:70, align:"center",
                            formatter:function(value,rec){ 
                            return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                          }
                          }
                         ]]
        });
    });        

    
    function get(kd_s,nm_s,merk,satuan,harga,keterangan) {
        $("#rek5").combogrid("setValue",kd_s);
        //$("#nm_rek").attr("value",nm_rek6);
        $("#rek5_hide").attr("value",kd_s);
        $("#uraian").combogrid("setValue",nm_s);
        //console.log('test'+nm_s);
        //$("#uraian").attr("value",nm_s);  
        $("#satuan").attr("value",satuan);  
        $("#merk").attr("value",merk);  
        $("#harga").attr("value",number_format(harga,2,'.',','));  
        $("#keterangan").attr("value",keterangan);  
    }
       
    
    function kosong(){
        $("#rek5").combogrid("setValue",'');
        $("#nm_u").attr("value",'');
        $("#rek5_hide").attr("value",'');
        $("#uraian").combogrid("setValue",'');
        //$("#uraian").attr('value','') ;
        $("#merk").attr('value','') ;
        $("#satuan").attr('value','') ;
        $("#harga").attr('value',0) ;
        $("#keterangan").attr('value','') ;
        load_detail();
    }
    
    
    function kosong_detail(){
        $("#uraian").attr('value','') ;
        $("#merk").attr('value','') ;
        $("#satuan").attr('value','') ;
        $("#harga").attr('value',0) ;
        $("#ket").attr('value','') ;
    }
    

    function muncul(){
        var c_urus=kd_urus+'.';
        var c_skpd=kd_s;
        if(lcstatus=='tambah'){ 
            $("#kode").attr("value",c_urus);
        } else {
            $("#kode").attr("value",c_skpd);
        }     
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_ar',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    
    function simpan(){
       
        //var crek5      = $('#rek5').combogrid('getValue');
        //var cnmrek5    = document.getElementById('nm_u').value;
        //var crek5_hide = document.getElementById('rek5_hide').value;
            var ckdrek  = $("#rek5").combogrid('getValue') ;
            var ckdbrg  = document.getElementById('kd_barang').value ;
            var curaian   = $("#uraian").combogrid('getValue') ;
            var cmerk   = document.getElementById('merk').value ;
            var csatuan = document.getElementById('satuan').value ;
            var charga  = document.getElementById('harga').value ;
            var cket    = document.getElementById('keterangan').value ;
       
        if (ckdrek==''){
            alert('Kode Rekening Tidak Boleh Kosong');
            exit();
        } 

        if (ckdbrg==''){
            alert('Kode Barang Tidak Boleh Kosong');
            exit();
        } 

       

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_rek6,kd_barang,uraian,merk,satuan,harga,keterangan)";
            lcvalues = "('"+ckdrek+"','"+ckdbrg+"','"+curaian+"','"+cmerk+"','"+csatuan+"','"+charga+"','"+cket+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type      : "POST",
                    url       : '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data      : ({tabel:'ms_standar_harga',kolom:lcinsert,nilai:lcvalues,cid:'kd_rek6',lcid:ckdrek}),
                    dataType  : "json",
                    success   : function(data){
                        status = data;
                        //alert(status);
                        if(status=='1'){
                            detsimpan();
                            alert('Data Tersimpan..!!');
                            exit();
                        }else{
                            alert('Data Gagal Tersimpan');
                        }
                    }
                });
            });   
           
        } else {
            
            lcquery = "UPDATE ms_standar_harga SET kd_rek6='"+ckdrek+"', kd_barang='"+ckdbrg+"' where uraian='"+ckdrek+"'";

            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/master/update_master_ar',
                data     : ({st_query:lcquery,tabel:'ms_standar_harga',cid:'kd_rek6',lcid:ckdrekn}),
                dataType : "json",
                success  : function(data){
                           status = data;
                        
                        if ( status=='2' ){
        
                            detsimpan();
                            alert('Data Tersimpan...!!!');
                            lcstatus = 'edit';
                            load_detail();
                            exit();
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                    }
            });
            });
        }
        $('#dg').edatagrid('reload'); 
    } 
    
    
    function edit_data(uraian){
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/cari1/'+uraian,
                data: ({tabel:'ms_standar_harga',field:'uraian'}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status==0){
                            $('#hapus').linkbutton('enable');
                        }else{
                            $('#hapus').linkbutton('enable');
                        }
                    }
            });
            });

        lcstatus = 'edit';
        judul    = 'Edit Data Standar Harga';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        }    
    
    
    function tambah(){
        lcstatus = 'tambah';
        judul    = 'Input Data Standar Harga';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        $("#rek5").combogrid("enable");
        //document.getElementById("kode").disabled=false;
        //document.getElementById("kode").focus();
        } 
     
     
    function keluar(){
        $("#dialog-modal").dialog('close');
    }    

     
    function hapus(){
        
        var crek5 = $('#rek5').combogrid('getValue');
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
         $(document).ready(function(){
         $.post(urll,({tabel:'ms_standar_harga',cnid:crek5,cid:'kd_rek6'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                hapus_detail_all();
                alert('Data Berhasil Dihapus..!!');
                kosong();
                $("#dialog-modal").dialog('close');
                exit();
            }
         });
        });    
    } 
    
    
    function hapus_detail_all(){
        
        var crek5 = $('#rek5').combogrid('getValue');
        var urll  = '<?php echo base_url(); ?>index.php/master/hapus_detail_all';
         $(document).ready(function(){
         $.post(urll,({tabel:'ms_standar_harga',cnid:crek5,cid:'kd_rek6'}),function(data){
            status = data;
            if ( status == '0'){
                alert("Gagal Hapus Detail...!!!")
            }
         });
         });    
    } 
    
    
    function hapus_detail(){
        
        var a    = $("#rek5").combogrid("getValue") ;
        var rows = $('#dg2').edatagrid('getSelected');
        
        bkdrek   = rows.kd_rek5 ;
        buraian  = rows.uraian ;
        bmerk    = rows.merk ;
        bharga   = rows.harga ;
        burut    = rows.no_urut ;
        
        var idx  = $('#dg2').edatagrid('getRowIndex',rows);
        var tny  = confirm('Yakin Ingin Menghapus Data, '+buraian+'  Merk :  '+bmerk+'  Harga  '+bharga+' ?');
        
        if ( tny == true ) {
            
            $('#dg2').datagrid('deleteRow',idx);     
            $('#dg2').datagrid('unselectAll');
              
             var urll = '<?php  echo base_url(); ?>index.php/master/hapus_detail';
             $(document).ready(function(){
             $.post(urll,({ckdrek:bkdrek,curaian:buraian,cmerk:bmerk,charga:bharga,curut:burut}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    detsimpan();
                    load_detail();
                    exit();
                }
             });
             });    
        }     
    }
    
       
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split(',');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }

    
    function delCommas(nStr)
    {
        nStr += ' ';
        x2 = nStr.length;
        var x=nStr;
        var i=0;
        while (i<x2) {
            x = x.replace(',','');
            i++;
        }
        return x;
    }
    
    
    function enter(ckey,_cid){
        if (ckey==13)
            {                                  
                document.getElementById(_cid).focus();
                if(_cid=='uraian'){       
                   append_save();
                }
            }     
    }
    
    
    function append_save() {
            
            $('#dg2').datagrid('selectAll');
            var rows  = $('#dg2').datagrid('getSelections');
                jgrid = rows.length - 1 ;
                pidx  = jgrid + 1 ;
            
            var vkdrek  = $("#rek5").combogrid('getValue') ;
            var vkdbrg   = document.getElementById('kd_barang').value ;
            var vurai   = $("#uraian").combogrid('getValue') ;
            //alert(vurai);
            var vmerk   = document.getElementById('merk').value ;
            var vsatuan = document.getElementById('satuan').value ;
            var vharga  = document.getElementById('harga').value ;
            var vket    = document.getElementById('keterangan').value ;
            
            if ( vkdrek=='' ){
                alert('Pilih Rekening Terlebih Dahulu...!!!');
                document.getElementById('rek5').focus ;
                exit();
            }

            $('#dg2').edatagrid('appendRow',{kd_rek6:vkdrek,kd_barang:vkdbrg,uraian:vurai,merk:vmerk,satuan:vsatuan,harga:vharga,keterangan:vket,id:pidx,no_urut:pidx});
            $("#dg2").datagrid("unselectAll");
            kosong_detail();
            
       }
       
       
    function detsimpan() {
        
        var crek5_hide = document.getElementById('rek5_hide').value ;
        var crek5      = $("#rek5").combogrid("getValue") ;
        var csql       = '' ; 
        
        $('#dg2').datagrid('selectAll');
        var rows = $('#dg2').datagrid('getSelections');
        
        for(var i=0;i<rows.length;i++){            
            cidx    = rows[i].id;
            //curut   = rows[i].no_urut;
            ckdrek  = rows[i].kd_rek6;
            ckdbrg  = rows[i].kd_barang;
            curaian = rows[i].uraian;
            cmerk   = rows[i].merk;
            csatuan = rows[i].satuan;
            charga  = angka(rows[i].harga);
            cket    = rows[i].keterangan;
            
             if ( i > 0 ) {
                csql = csql+","+"('"+crek5+"','"+ckdbrg+"','"+curaian+"','"+cmerk+"','"+csatuan+"','"+charga+"','"+cket+"')";
             } else {
                csql = "values('"+crek5+"','"+ckdbrg+"','"+curaian+"','"+cmerk+"','"+csatuan+"','"+charga+"','"+cket+"')";                                            
             }
        }
        
        $(document).ready(function(){
        $.ajax({
              type     : "POST",   
              dataType : 'json',                 
              data     : ({tabel_detail:'ms_standar_harga',sql_detail:csql,proses:'detail',nomor:ckdbrg}),
              url      : '<?php echo base_url(); ?>/index.php/master/simpan_detail_standar_harga',
              success  : function(data){                        
                              status = data;   
                              if ( status=='0' ) {               
                                  alert('Data Detail Gagal Tersimpan');
                              } else if ( status=='1' ) {               
                                  alert('Data Detail Berhasil Tersimpan');
                              } 
                         }
            });
        });            

        $("#rek5_hide").attr("Value",crek5) ;
        $('#dg2').edatagrid('unselectAll');
    
    } 
    
    
    function load_detail() {
        
        var crekening = $("#uraian").combogrid("getValue") ;
        //alert(crekening);
        $('#dg2').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_detail',
        queryParams   : ({uraian:crekening}),
        idField       : 'id',
        toolbar       : "#toolbar",              
        rownumbers    : "true", 
        fitColumns    : "false",
        autoRowHeight : "false",
        singleSelect  : "true",
        nowrap        : "false",          
        columns       : [[{field:'id',        title:'id',           width:70, align:"left",hidden:"true"},
                          {field:'kd_rek6',   title:'Rekening',     width:80, align:"left",hidden:"true"},
                          //{field:'kd_barang',    title:'Barang',       width:295,align:"left"},
                          {field:'uraian',    title:'Uraian',       width:295,align:"left"},
                          {field:'merk',      title:'Merk',         width:100,align:"left"},
                          {field:'satuan',    title:'Satuan',       width:100,align:"left"},
                          {field:'harga',     title:'Harga',        width:150,align:"right"},
                          {field:'keterangan',title:'Keterangan',   width:200,align:"left"},
                          {field:'hapus',     title:'Hapus',        width:70, align:"center",
                          formatter:function(value,rec){ 
                          return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                          }
                          }
                         ]]
        });
    }
    
    
    function insert_row() {

        var rows     = $('#dg2').edatagrid('getSelected');
        var idx_ins  = $('#dg2').edatagrid('getRowIndex',rows);
        

        if ( idx_ins == -1){
            alert("Pilih Lokasi Insert Terlebih Dahulu...!!!") ;
            exit();
        }

        $('#dg2').datagrid('selectAll');
        var rows_grid = $('#dg2').datagrid('getSelections');
        for ( var i=idx_ins; i<rows_grid.length; i++ ) {            
              $('#dg2').edatagrid('updateRow',{index:i,row:{id:i+1,no_urut:i+1}});
        }
        $('#dg2').datagrid('unselectAll');
           
        var vkdrek  = $("#rek5").combogrid('getValue') ;
        var vurai   = document.getElementById('uraian').value ;
        var vmerk   = document.getElementById('merk').value ;
        var vsatuan = document.getElementById('satuan').value ;
        var vharga  = document.getElementById('harga').value ;
        var vket    = document.getElementById('keterangan').value ;
            
        if ( vkdrek=='' ){
             alert('Pilih Rekening Terlebih Dahulu...!!!');
             document.getElementById('rek5').focus ;
             exit();
        }

        $('#dg2').edatagrid('insertRow',{index:idx_ins,row:{kd_rek6:vkdrek,uraian:vurai,merk:vmerk,satuan:vsatuan,harga:vharga,keterangan:vket,id:idx_ins,no_urut:idx_ins}});
        $("#dg2").datagrid("unselectAll");
        kosong_detail();
            
    }

  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER STANDAR HARGA</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td>               
        -->
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA STANDAR HARGA" style="width:1100px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>  
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">

    <!--<p class="validateTips">Semua Inputan Harus Di Isi.</p>--> 
    
    <table align="center" style="width:100%;" border="0">
       <tr>
           <td width="10%">REKENING</td>
           <td width="1%">:</td>
           <td><input type="text" id="rek5" style="width:100px;"/>&nbsp;&nbsp;<input type="hidden" id="nm_u" style="width:100px;"/><input type="hidden" id="rek5_hide" style="width:100px;"/></td>  
      </tr> 
      <tr>
                <td width="30%">KODE BARANG</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_barang" style="width:360px;"/></td>  
            </tr> 
    </table>       
    
    
    <table align="center" style="width:100%;" border="0">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="color:#004080;font-size:17px;font:bolder;" align='left'><B>INPUT DETAIL RINCIAN</B></td>
        </tr> 
    </table>
    
    <fieldset style="width:927px;">
        <table align="center" style="width:100%;" border="0">
                 <tr>
                <td width="30%">Uraian</td>
                <td width="1%">:</td>
                <td><input type="text" id="uraian" style="width:100px;"/></td>  
            </tr> 
                <tr>
                <td width="30%">Merk</td>
                <td width="1%">:</td>
                <td><input type="text" id="merk" style="width:360px;"/></td>  
            </tr>  
                <tr>
                <td width="30%">Satuan</td>
                <td width="1%">:</td>
                <td><input type="text" id="satuan" style="width:360px;"/></td>  
            </tr>     
                
                <tr>
                    <td>Harga</td>
                    <td width="1%">:</td>
                    <td>&nbsp;<input type="text" id="harga" style="width:200px;text-align:right;" onkeypress="javascript:enter(event.keyCode,'harga');return(currencyFormat(this,',','.',event))"/></td>  
                </tr> 
                <tr>
                <td width="30%">Keterangan</td>
                <td width="1%">:</td>
                <td><input type="text" id="keterangan" style="width:360px;"/></td>  
            </tr>  
                
    
        </table>       
    </fieldset>
   
    <table style="width:950px;" border='0'>
            <tr>
                <td align="left">
                        <a class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:append_save();">Insert</a>
                </td>

                <td align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add"    plain="true" onclick="javascript:tambah();">Baru</a>
                    <a class="easyui-linkbutton" iconCls="icon-save"   plain="true" onclick="javascript:simpan();">Simpan</a>
                    <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo"   plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
             </tr>
             <table id="dg2" title="Listing Data" style="width:950px;height:280px;" >  
             </table>
    </table>
    
</div>
</body>
</html>