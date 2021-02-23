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
        position : relative;
        width    : 500px;
        height   : 70px;
        padding  : 0.4em;
    }  
    
    </style>
    <script type="text/javascript">
    
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0;
    var lcidx    = 0;
    var lcstatus = '';
    var curut    = '';
    var tahun_anggaran = '<?php echo $this->session->userdata('pcThang'); ?>';
    var bulan    = '';
    var rekening = '';
    var stt_sts  = '';
    var stt_cms  = '';
                    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height   : 550,
            width    : 900,
            modal    : true,
            autoOpen : false,
        });
        $("#tagih").hide();
         get_skpd(); 
         get_urut();
        });    
    
     
     $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/pendapatan/penerimaan/load_terima',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true", 
        rowStyler: function(index,row){
        if (row.stt_sts == 1){
          return 'background-color:#03d3ff;';
        }else if (row.stt_cms == 1){
          return 'background-color:#03d3ff;';
        }
        },                       
        columns:[[
            {field:'no_terima',
            title:'Nomor Terima',
            width:50,
            align:"center"},
            {field:'tgl_terima',
            title:'Tanggal',
            width:30},
            {field:'kd_skpd',
            title:'S K P D',
            width:30,
            align:"center"},
            {field:'kd_rek5',
            title:'Rekening',
            width:50,
            align:"center"},
            {field:'nilai',
            title:'Nilai',
            width:50,
            align:"right"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor     = rowData.no_terima;
          no_tetap  = rowData.no_tetap;
          tgl       = rowData.tgl_terima;
          kode      = rowData.kd_skpd;
          lcket     = rowData.keterangan;
          lcrek     = rowData.kd_rek5;
          rek       = rowData.kd_rek;
          lcnilai   = rowData.nilai;
          sts       = rowData.sts_tetap;
          giat      = rowData.kd_kegiatan;
          tgl_tetap = rowData.tgl_tetap;
          bank      = rowData.bank;
          stt_sts   = rowData.stt_sts;
          stt_cms   = rowData.stt_cms;
          lcidx     = rowIndex;
          lcrek_6   = rowData.kd_rek6;
          get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,lcnilai,sts,giat,tgl_tetap,bank,stt_sts,stt_cms,lcrek_6);   
        },
        onDblClickRow:function(rowIndex,rowData){
          nomor     = rowData.no_terima;
          no_tetap  = rowData.no_tetap;
          tgl       = rowData.tgl_terima;
          kode      = rowData.kd_skpd;
          lcket     = rowData.keterangan;
          lcrek     = rowData.kd_rek5;
          rek       = rowData.kd_rek;
          lcnilai   = rowData.nilai;
          sts       = rowData.sts_tetap;
          giat      = rowData.kd_kegiatan;
          tgl_tetap = rowData.tgl_tetap;
          bank      = rowData.bank;
          stt_sts   = rowData.stt_sts;
          stt_cms   = rowData.stt_cms;
          lcidx     = rowIndex;
          lcrek_6   = rowData.kd_rek6;
          get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,lcnilai,sts,giat,tgl_tetap,bank,stt_sts,stt_cms,lcrek_6);
           lcstatus = 'edit';
           lcidx    = rowIndex;
           judul    = 'Edit Data Penerimaan'; 
           edit_data();   
        }
        });
        
        $(function(){
            $('#jenis_rinci').combogrid({
           panelWidth:700,  
           idField:'kd_rek6',  
           textField:'kd_rek6',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/load_rekening_rinci/'+kd_rek5x,             
           columns:[[  
               {field:'kd_rek6',title:'Kode Rekening',width:140},  
               {field:'nm_rek6',title:'Nama Rekening',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_rinci").attr("value",rowData.nm_rek6);                                                 
           }              
        });
        }); 
        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            },
                onSelect: function(date){
                var m = date.getMonth()+1;
                    bulan = m;
                    get_hasil();
                }            
        });
        
         
        /*$(function(){
            $('#bidang').combogrid({  
            panelWidth : 400,  
            idField    : 'kd_bidang',  
            textField  : 'kd_bidang',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/rka/cbidang',  
            columns    : [[  
                {field:'kd_bidang',title:'Kode Bidang',width:80},  
                {field:'nm_bidang',title:'Nama Bidang',width:300}    
            ]],
            onSelect:function(rowIndex,rowData){
                bidang = rowData.kd_bidang;
                bidskpd = rowData.kd_bidskpd;
                $("#nmbidang").attr("value",rowData.nm_bidang.toUpperCase());                                                
            },
            }); 
            });*/
    
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
                                        validate_rek();
                                        penetapan();
                                        
                                      }                                     
            });
        }
     
     function validate_combox(kd_rek5x){


        $('#jenis_rinci').combogrid({
           panelWidth:700,  
           idField:'kd_rek6',  
           textField:'kd_rek6',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/pendapatan/penetapan/load_rekening_rinci/'+kd_rek5x,             
           columns:[[  
               {field:'kd_rek6',title:'Kode Rekening',width:140},  
               {field:'nm_rek6',title:'Nama Rekening',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_rinci").attr("value",rowData.nm_rek6);
               }
              
        });
    }   

     function validate_rek(){
        $(function(){
        $('#rek').combogrid({  
           panelWidth : 700,  
           idField    : 'kd_rek5',  
           textField  : 'kd_rek5',  
           mode       : 'remote',
           url        : '<?php echo base_url(); ?>index.php/pendapatan/penetapan/ambil_rek_tetap/'+kode,             
           columns    : [[  
               {field:'kd_rek5',title:'Kode Rek LRA',width:90},  
               {field:'kd_rek',title:'Kode Rek LO',width:90},
               {field:'nm_rek',title:'Uraian Rinci',width:330},
               {field:'nm_rek4',title:'Uraian Obyek',width:200},
                {field:'kd_kegiatan',title:'Kegiatan',width:100}
              ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek.toUpperCase());
               $("#rek1").attr("value",rowData.kd_rek5);
               $("#giat").attr("value",rowData.kd_kegiatan);
               $("#nmgiat").attr("value","PENDAPATAN");
               rekening = rowData.kd_rek5;
               get_hasil();
               validate_combox(rowData.kd_rek5);
              }    
            });
            });
        } 
        

        
      function get_urut()
        {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/pendapatan/penerimaan/config_tbp',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                    curut = data.nomor;
                    $("#nomor_urut").attr("value",curut);
                    
                    }                                     
            });
             
        }   
        
    function get_hasil(){
       var a1 = document.getElementById('jns_tbp').value;
       var a2 = "TBP";
       var a3 = kode;
       var a4 = rekening;
       var a5 = bulan;
       var a6 = tahun_anggaran;
       var hasil = "/"+a1+"/"+a2+"/"+a3+"/"+a4+"/"+a5+"/"+a6;
       $("#nomor").attr("value",hasil);
    }    
    
    function runjns(){
        get_hasil();
    }
            
     function penetapan(){
         var kode = kode;
         $('#notetap').combogrid({  
           panelWidth  : 420,  
           idField     : 'no_tetap',  
           textField   : 'no_tetap',  
           mode        : 'remote',
           url         : '<?php echo base_url(); ?>index.php/pendapatan/penerimaan/load_no_tetap',
           queryParams : ({cari:kode}),             
           columns:[[  
               {field:'no_tetap',title:'No Penetapan',width:140},  
               {field:'tgl_tetap',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}]],  
           onSelect:function(rowIndex,rowData){
            var ststagih='1';
            $("#tgltetap").attr("value",rowData.tgl_tetap);
            $("#rek").combogrid("setValue",rowData.kd_rek_lo);
            $("#rek1").attr("Value",rowData.kd_rek5);
            $("#nmrek").attr("Value",rowData.nm_rek5);
            $("#jenis_rinci").combogrid("setValue",rowData.kd_rek6);
            $("#nm_rinci").attr("Value",rowData.nm_rek6);            
            $("#ket").attr("value",rowData.keterangan);
            $("#giat").attr("value",rowData.kd_kegiatan);
            $("#nmgiat").attr("value",'PENDAPATAN');
            $("#nilai").attr("value",number_format(rowData.nilai,2,'.',','));  
            $("#nil_tetap").attr("value",number_format(rowData.nilai,2,'.',',')); 
            rekening = rowData.kd_rek5; 
            }  
        });
     }       

     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
     }

     function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
       
     function get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,lcnilai,sts,giat,tgl_tetap,bank,stt_sts,stt_cms,lcrek_6){
        $("#notetap").combogrid("setValue",no_tetap);
        var nox = parts = nomor.split("/");
        var nox1 = nox[0];var nox2 = nox[1];var nox3 = nox[2];var nox4 = nox[3];var nox5 = nox[4];var nox6 = nox[5];var nox7 = nox[6];
        var hasil = "/"+nox2+"/"+nox3+"/"+nox4+"/"+nox5+"/"+nox6+"/"+nox7;
        
        $("#nomor_urut").attr("value",nox1);        
        $("#nomor").attr("value",hasil);
        $("#nomor_hide").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#rek").combogrid("setValue",rek);
        $("#jenis_rinci").combogrid("setValue",lcrek_6);
        $("#rek1").attr("Value",lcrek);
        $("#giat").attr("Value",giat);
        $("#nilai").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        $("#jns_tbp").attr("Value",bank);
        if (sts==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
            $("#nil_tetap").attr("value",lcnilai);
            $("#tgltetap").attr("value",tgl_tetap);
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
            $("#tgltetap").attr("value",'');
        }       
        tombolsave(stt_sts,stt_cms);
        
    }
    
    function tombolsave(stt_sts,stt_cms){  
    if ((stt_sts==1) || (stt_cms==1)){
    $('#save').hide();
     } else {
     $('#save').show();  
     }
    }
    
    
    function kosong(){
        get_urut();
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#nilai").attr("value",'');        
        $("#rek").combogrid("setValue",'');
        $("#jenis_rinci").combogrid("setValue",'');        
        $("#rek1").attr("Value",'');
        $("#nm_rinci").attr("Value",'');
        $("#nmrek").attr("value",'');
        $("#giat").attr("Value",'');
        $("#nmgiat").attr("Value",'');
        $("#ket").attr("value",'');
        $("#notetap").combogrid("setValue",'');        
        $("#tgltetap").attr("value",'');
        $("#nil_tetap").attr("value",0);
        $("#status").attr("checked",false);      
        $("#tagih").hide();
        document.getElementById("nomor").focus();         
        lcstatus = 'tambah';       
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/pendapatan/penerimaan/load_terima',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
      
    
    function simpan_terima() {
        
        var cnourut      = document.getElementById('nomor_urut').value;
        var cnotmbahan      = document.getElementById('nomor').value;
        var cno_hide = document.getElementById('nomor_hide').value;
        var jns_tbp = document.getElementById('jns_tbp').value;
        var ctgl     = $('#tanggal').datebox('getValue');
        var cskpd    = document.getElementById('skpd').value;
        var cnmskpd  = document.getElementById('nmskpd').value ;
        var lckdrek  = $('#rek').combogrid('getValue');
        var lckdrek_6  = $('#jenis_rinci').combogrid('getValue');
        var rek      = document.getElementById('rek1').value;
        var subkegi      = document.getElementById('giat').value;
        var lcket    = document.getElementById('ket').value;
        var lntotal  = angka(document.getElementById('nilai').value);
            lctotal  = number_format(lntotal,0,'.',',');
        var cstatus  = document.getElementById('status').checked;
        var tot_tetap  = angka(document.getElementById('nil_tetap').value);
        
        var kegi = subkegi.substr(0,21);
        var depan = subkegi.substr(0,12);
        var belakang = subkegi.substr(16,8);        
        var cno = cnourut+cnotmbahan; 
        var nox = parts = cno.split("/");
        var nox1 = nox[0];var nox2 = nox[1];var nox3 = nox[2];var nox4 = nox[3];var nox5 = nox[4];var nox6 = nox[5];var nox7 = nox[6];
        
        //var bidsub = depan+"."+bidang+"."+belakang; 
        
         if (nox5==''){
            alert('Tanggal Terima Tidak Boleh Kosong (klik tanggal)');
            exit();
        }
        
        var tahun_input = ctgl.substring(0, 4);
        if (tahun_input != tahun_anggaran){
            alert('Tahun tidak sama dengan tahun Anggaran');
            exit();
        }
        if (cstatus==false){
           cstatus=0;
        }else{
            cstatus=1;
            if(tot_tetap<lntotal){
                alert("Melebihi nilai penetapan");
                exit();
            }
        }
        
        if (lntotal=='undefined' || lntotal==0){
            alert('Nilai Tidak Boleh Kosong');
            exit();
        }
        
        var ctetap    = $('#notetap').combogrid('getValue');
        var ctgltetap = document.getElementById('tgltetap').value;
                
        if (cno==''){
            alert('Nomor  Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
            
            
        if ( lcstatus == 'tambah'){
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cnourut,tabel:'tr_terima',field:'urut'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        alert("Nomor Telah Dipakai! Refresh");
                        get_urut();
                        document.getElementById("nomor").focus();
                        exit();
                        } 
                        if(status_cek==0){
                        alert("Nomor Bisa dipakai");
                        //mulai
           
            lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_kegiatan,   kd_rek5,   kd_rek_lo,     nilai,         keterangan, jenis, urut, bank, kd_rek6, kd_sub_kegiatan  ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+ctetap+"', '"+ctgltetap+"', '"+cstatus+"', '"+cskpd+"', '"+subkegi+"',  '"+rek+"', '"+rek+"', '"+lntotal+"', '"+lcket+"', '1','"+cnourut+"', '"+jns_tbp+"', '"+rek+"','"+subkegi+"') ";
            
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/pendapatan/penerimaan/simpan_terima',
                    data     : ({tabel       :'tr_terima',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_terima',   lcid       :cno}),
                    dataType : "json",
                    success  : function(data) {
                        status = data;
                        if ( status == '0') {
                            alert('Gagal Simpan..!!');
                            exit();
                        }  else {
                                  
                                    alert('Data Tersimpan..!!');
                                    lcstatus = 'edit';
                                    $("#dialog-modal").dialog('close');
                                    $('#dg').edatagrid('reload');
                                    //exit();
                             }
                    }
                });
            }); 
            
            
           
       //akhir-mulai 
        }
        }
        });
        });     
            
       } else {
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_terima',field:'no_terima'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && cno!=cno_hide){
                        alert("Nomor Telah Dipakai! Refresh");
                        get_urut();
                        exit();
                        } 
                        if(status_cek==0 || cno==cno_hide){
                        alert("Nomor Bisa dipakai");
            //mulai 
            
           lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_kegiatan,   kd_rek5,   kd_rek_lo,     nilai,         keterangan, jenis, urut, bank, kd_rek6, kd_sub_kegiatan  ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+ctetap+"', '"+ctgltetap+"', '"+cstatus+"', '"+cskpd+"', '"+subkegi+"',  '"+rek+"', '"+rek+"', '"+lntotal+"', '"+lcket+"', '1', '"+cnourut+"', '"+jns_tbp+"', '"+lckdrek_6+"','"+subkegi+"' ) ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/pendapatan/penerimaan/update_terima',
                data     : ({tabel       :'tr_terima',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_terima',   lcid       :cno,no_hide:cno_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='2' ){
                                alert('Data Tersimpan...!!!');
                                lcstatus = 'edit';
                                $("#nomor_hide").attr("Value",cno) ;
                                $("#dialog-modal").dialog('close');
                                $('#dg').edatagrid('reload');
                               // exit();
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                    }
            });
            });
        //akhir
        }
            }
        });
        });
        }       
    }
    

    
    
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
    }    
        
    
    function tambah(){
        
        $("#notetap").combogrid("setValue",'');

        
        lcstatus = 'tambah';
        judul = 'Input Data Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        //kosong();
        $("#dialog-modal").dialog('open');
        
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        kosong();
     } 


     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        
        var stt_stsx  = stt_sts;
        var stt_cmsx  = stt_cms;
        
        if(stt_stsx=='1'){
            alert('Sudah Di Setorkan');
            exit();
        }
        
        if(stt_cmsx=='1'){
            alert('Sudah Di Setorkan');
            exit();
        }
        
        var rows  = $("#dg").edatagrid("getSelected") ;
        var nobkt = rows.no_terima ;
                
        var tanya = confirm('Apakah Data Nomor Terima '+nobkt+' Akan Di Hapus ???') ;
        
        if ( tanya == true ) {
        
            var urll  = '<?php echo base_url(); ?>index.php/pendapatan/penerimaan/hapus_terima';
            $(document).ready(function(){
             $.post(urll,({no:nomor,skpd:kode}),function(data){
                status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    $("#dg").edatagrid("reload") ;
                    exit();
                }
             });
            });    
        }
    } 
    
    function runEffect() {
        $('#notetap').combogrid({  
           panelWidth:420,  
           idField:'no_tetap',  
           textField:'no_tetap',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/pendapatan/penerimaan/load_no_tetap',
           queryParams:({kd:kode}),             
           columns:[[  
               {field:'no_tetap',title:'No Penetapan',width:140},  
               {field:'tgl_tetap',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}
           ]]  
        });
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notetap").combogrid("setValue",'');
        $("#tgltetap").attr("value",'');
        //$("#nilai").attr("value",'');
        $("#skpd").combogrid("setValue",'');
        $("#rek").combogrid("setValue",'');
        $("#nil_tetap").attr("value",'');
        
    };     
       
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PENERIMAAN</a></b></u></h3>
    <div>
    <p align="right">                      
        <button class="button" onclick="javascript:tambah();kosong();"><i class="fa fa-tambah"></i> Tambah</button>
        <button class="button button-merah" onclick="javascript:hapus();"><i class="fa fa-hapus"></i> Hapus</button>
        <input type="text" value="" class="input" onkeyup="javascript:cari();" id="txtcari" style="display: inline; width: 200px" placeholder="Pencarian: Ketik dan enter" />
        <table id="dg" title="Listing data Penerimaan" style="width:1024px;height:450px;" >  
        </table>
    </p> 
    <p>*) Warna Biru = Sudah di Setorkan (STS)</p>
    </div>   
</div>
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td colspan="5"><b>Dengan Penetapan</b><input type="checkbox" id="status"  onclick="javascript:runEffect();"/>
                    <div id="tagih">
                        <table>
                            <tr>
                                <td>No Penetapan&nbsp;</td>
                                <td><input type="text" id="notetap" style="width: 200px;" /></td>
                                <td>&nbsp;Tgl&nbsp;&nbsp;</td>
                                <td><input class="input" type="text" id="tgltetap" style="width: 100px; display: inline;" /></td> 
                                <td>&nbsp;Nilai&nbsp;&nbsp;</td>
                                <td><input class="input" type="text" id="nil_tetap" style="width: 100px; display: inline; text-align: right;" /></td>   
                            </tr>
                        </table> 
                    </div>                
                </td>                
            </tr>
            <tr>
                <td>No. Terima</td>
                <td></td>
                <td><input type="text" class="input" id="nomor_urut" style="width: 50px;  display: inline;"/><input type="text" class="input" id="nomor" style="width: 400px; display: inline;"/><input type="hidden" id="nomor_hide" style="width: 200px; "/></td>  
            </tr>            
            <tr>
                <td>Tanggal </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 240px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" class="input" name="skpd" style="width: 230px;" />                       
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <!--<tr>
                <td>BIDANG</td>
                <td></td>
                <td><input id="bidang" name="bidang"  style="width: 170px; border:0;" readonly="true" />  <input type="text" id="nmbidang" style="border:0;width: 350px;" readonly="true"/></td>                            
            </tr>-->
            <tr>
                <td>Rekening</td>
                <td></td>
                <td><input id="rek" name="rek" style="width: 240px;" /> <input id="rek1" style="border:0;width: 80px;" readonly="true"/>
                 <input type="text" id="nmrek" style="border:0;width: 400px;" readonly="true"/></td>                
            </tr> 
            <tr hidden>
                <td>Sub. Rek</td>
                <td></td>
                <td><input id="jenis_rinci" name="jenis_rinci" style="width: 240px;" />
                 <input type="text" id="nm_rinci" style="border:0;width: 400px;" readonly="true"/></td>                
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td></td>
                <td><input class="input" type="text" id="giat" style="width: 230px; display: inline;" readonly="true"/>
                <input type="text" id="nmgiat" style="border:0;width: 400px;" readonly="true"/>
                 </td>                
            </tr>    
            <tr>
                <td>Jenis Penerimaan</td>
                <td></td>
                <td><select class="select" style="width: 240px" id="jns_tbp" name="jns_tbp" onclick="javascript:runjns();">
                <!--<option value="TN">Tunai</option>-->
                <option value="BNK" selected>Bank</option>
                </select>               
                 </td>                
            </tr>         
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" class="input" style="width: 230px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea class="textarea" rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <button id="save" class="button button-biru" onclick="javascript:simpan_terima();"><i class="fa fa-simpan"></i> Simpan</button>
                    <button class="button button-abu" onclick="javascript:keluar();"><i class="fa fa-kiri"></i> Kembali</button>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>