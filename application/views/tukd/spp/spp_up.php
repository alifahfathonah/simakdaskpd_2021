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
    
    var no_spp   = '';
    var kode     = '';
    var lcstatus = ''; 
    var th_agr   = '';
    var tahun_anggaran="<?php echo $this->session->userdata('pcThang'); ?>"

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 320,
            width: 700,
            modal: true,
            autoOpen:false
        });
        get_skpd();
   
        });
           
    $(function(){
         $('#dd').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            }, onSelect: function(date){
                    var tahunsekarang = date.getFullYear();
                    $("#tahunsekarang").attr("value",tahunsekarang);
                    get_spp();
                }
        });
    
              $('#bank1').combogrid({  
                panelWidth:700,  
                url: '<?php echo base_url(); ?>/index.php/tukd/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:120},  
                           {field:'nama_bank',title:'Nama',width:500}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });
        
            $('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>sppc/load_spp_up',  
                    idField:'no_spp',                    
                    textField:'no_spp',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spp',title:'SPP',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'tgl_spp',title:'Tanggal',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nomer = rowData.no_spp;
                    kode = rowData.kd_skpd;
                    jns = rowData.jns_spp;
                    }   
                });
                
      $('#ttd1').combogrid({  
          panelWidth:600,  
          idField:'id_ttd',  
          textField:'nip',  
          mode:'remote',
          url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BK',  
          columns:[[  
            {field:'nip',title:'NIP',width:200},  
            {field:'nama',title:'Nama',width:400}    
          ]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd1").attr("value",rowData.nama);
                    }  
        });          
        
        $('#ttd2').combogrid({  
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
                    $("#nmttd2").attr("value",rowData.nama);
                    }  
  
        });
        
        $('#ttd3').combogrid({  
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
                    $("#nmttd3").attr("value",rowData.nama);
                    }  
  
        });
        
        $('#ttd4').combogrid({  
          panelWidth:600,  
          idField:'id_ttd',  
          textField:'nip',  
          mode:'remote',
          url:'<?php echo base_url(); ?>index.php/sppc/load_ttd_bud/BUD',  
          columns:[[  
            {field:'nip',title:'NIP',width:200},  
            {field:'nama',title:'Nama',width:400}    
          ]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd4").attr("value",rowData.nama);
                    }  
  
        }); 
      
      
        $('#spp').edatagrid({
        url: '<?php echo base_url(); ?>sppc/load_spp_up',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",              
            rowStyler: function(index,row){
            if (row.status == "1"){
              return 'background-color:#03d3ff;';
              }
            },                                    
        columns:[[
          {field:'no_spp',
        title:'NO SPP',
        width:40},
            {field:'tgl_spp',
        title:'Tanggal',
        width:25},
            {field:'kd_skpd',
        title:'SKPD',
        width:25,
            align:"left"},
            {field:'keperluan',
        title:'Keterangan',
        width:140,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomer   = rowData.no_spp;         
          kode    = rowData.kd_skpd;
          spd     = rowData.no_spd;
          tg      = rowData.tgl_spp;
          jn      = rowData.jns_spp;
          kep     = rowData.keperluan;
          np      = rowData.npwp;          
          bk      = rowData.bank;
          ning    = rowData.no_rek;
          status  = rowData.status;          
          get(nomer,kode,spd,tg,jn,kep,np,bk,ning,status);
          detail1_up(); 
          lcstatus = 'edit';                                           
        },
        onDblClickRow:function(rowIndex,rowData){
            section1();
        }
        });            
         
     var jenis = 5;
         $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>sppc/spd1_ag',
          queryParams:({cjenis:52}),
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,
                    onLoadSuccess:function(data){
                      detail1_up();                                           
                    },                    
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:70},  
                        {field:'tgl_spd2',title:'Tanggal',align:'left',width:30},
                        {field:'nilai',title:'Nilai',align:'right',width:40}                            
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;                    
                                                                        
                    }    
                });
         
         $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
      });
            
        
         $('#rekup').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/sppc/spd1_up',                
                    idField    : 'kdrek5',  
                    textField  : 'kdrek5',
                    mode       : 'remote',  
                    fitColumns : true,
                                 
                    columns:[[  
                        {field:'kdrek5',title:'Kode Rekening',width:50},  
                        {field:'nmrek5',title:'Nama Rekening',align:'left',width:100}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                        $("#nmrekup").attr("value",rowData.nmrek5) ;                   
                    }    
                });
    
            
            
        });
           

  function get_spp(){

            var year =  document.getElementById('tahunsekarang').value;
      var jenis_ls = document.getElementById('jns_beban').value;
      var jns ="UP";
            if((year)!=(tahun_anggaran)){
            var th_agr = year;
        }else{
            var th_agr = tahun_anggaran;
        }
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/sppc/config_spp',
            type: "POST",
            dataType:"json",                         
            success:function(data){
              no_spp = data.nomor;

                  var inisial = no_spp + "/SPP/"+jns+"/<?php echo $this->session->userdata('kdskpd')?>/<?php echo $this->session->userdata('pcThang'); ?>";
                  $("#no_spp").attr("value",inisial);
                  $("#no_u").attr("value",no_spp);
              }                                     
          });
        }
             
   
    function get_skpd(){
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
            type: "POST",
            dataType:"json",                         
            success:function(data){
                        $("#dn").attr("value",data.kd_skpd);
                        $("#nmskpd").attr("value",data.nm_skpd);
                        kode = data.kd_skpd;
                        validate_spd(kode);              
                        }                                     
          });  
        }   
  
    
    function validate_spd(kode){
           $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
        
        
    
    function detail1_up(){
        
            var no_spp = document.getElementById('no_spp').value;
            
            $.ajax({
            url      : '<?php echo base_url(); ?>sppc/select_data1',
            type     : "POST",
                data     : ({spp:no_spp}),
            dataType : "json",                         
            success  : function(data){
                $.each(data, function(i,n){
                $("#rekup").combogrid("setValue",n['kdrek5']);
                $("#nmrekup").attr("Value",n['nmrek5']);
                $("#nilaiup").attr("Value",n['nilai1']);
                });
                }                                     
          });  
            
    }

    
       
        
        function detail(){
        $(function(){
          var no_spp = '';            
      $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,                           
                 columns:[[
                  {field:'ck',
           title:'ck',
           checkbox:true,
           hidden:true},
          {field:'kdrek5',
           title:'Rekening',
           width:100,
           align:'left'
          },
          {field:'nmrek5',
           title:'Nama Rekening',
           width:530
          },
                    {field:'nilai1',
           title:'Nilai',
           width:140,
                     align:'right',
           editor:{type:"numberbox"              
              } 
                     }
        ]]  
      });
    });
        }
        
        
        
        function get(no_spp,kd_skpd,no_spd,tgl_spp,jns_spp,keperluan,npwp,bank,rekening,status){
        $("#no_spp").attr("value",no_spp);
        $("#no_spp_hide").attr("value",no_spp);
        
        $("#dn").attr("Value",kd_skpd);
        $("#sp").combogrid("setValue",no_spd);
        $("#dd").datebox("setValue",tgl_spp);        
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);       
         $("#bank1").combogrid("setValue",bank);
        $("#rekening").attr("Value",rekening);
         tombol(status);           
        }
    
        function kosong(){
         cdate    = '<?php echo date("Y-m-d"); ?>';
                
            lcstatus = 'tambah';  
            $('#save').show();
            $('#del').hide();
            $('#sav').hide();
            $('#dele').hide();  
         
            $("#no_spp_hide").attr("value",'');
            
            $("#rekup").combogrid("setValue",'');
            $("#nmrekup").attr("Value",'');
            $("#nilaiup").attr("Value",0);
            
            $("#sp").combogrid("setValue",'');
            $("#dd").datebox("setValue",'');        
            $("#ketentuan").attr("Value",'');
            $("#jns_beban").attr("Value",'');
            $("#npwp").attr("Value",'');        
             $("#bank1").combogrid("setValue",'');
            $("#rekening").attr("Value",'');
            document.getElementById("p1").innerHTML="";
            document.getElementById("no_spp").focus();
            $("#sp").combogrid("clear");
            detail();
      get_spp();
        }
       
    
       
     function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#cspp").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 

     
     function setgrid(){
       $('#dg1').edatagrid({                       
                 columns:[[
                  {field:'ck',
           title:'ck',
           checkbox:true,
           hidden:true},
          {field:'kdrek5',
           title:'Rekening',
           width:100,
           align:'left'
          },
          {field:'nmrek5',
           title:'Nama Rekening',
           width:530
          },
                    {field:'nilai1',
           title:'Nilai',
           width:140,
                     align:'right',
           editor:{type:"numberbox"              
              } 
                     }
                      
        ]]
                });
     }  
      
     
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                            
         });
     }
     
     function section4(){
         $(document).ready(function(){    
             $('#section4').click();                                               
         });
     }
     
     
     function hsimpan(){        
        var cdate    = '<?php echo date("Y-m-d"); ?>';
        var a       = (document.getElementById('no_spp').value).split(" ").join("");
        var a_hide  = document.getElementById('no_spp_hide').value;
        var no_u  = document.getElementById('no_u').value;
        
        var b       = $('#dd').datebox('getValue');      
        var c       = 1;        
        var e       = document.getElementById('ketentuan').value;       
        var g       = $("#bank1").combogrid("getValue") ; 
        var h       = document.getElementById('npwp').value;
        var i       = document.getElementById('rekening').value;
        var j       = document.getElementById('nmskpd').value;         
        var k       = angka(document.getElementById('nilaiup').value);
  
    var tahun_input = b.substring(0, 4);

    if (tahun_input != tahun_anggaran){
      alert('Tahun tidak sama dengan tahun Anggaran');
      exit();
    } 
        
        if (lcstatus=='tambah') { 
            lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp,  bank,    nmrekan,  no_rek,   npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,   no_bukti, kd_kegiatan,  nm_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 )"; 
            lcvalues = "('"+a+"', '"+kode+"', '"+e+"',   ''   ,   '"+spd+"', '"+c+"',  '"+g+"', ''     ,  '"+i+"',  '"+h+"', '"+j+"',  '"+b+"', '0',    '<?php echo $this->session->userdata('pcNama'); ?>',           '',            '"+k+"', '',       '',           '',           '',          '',          '',        '',          '',         '',        '',        '',        '',        '',        '',      '',      ''      )";           
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/sppc/simpan_tukd_spp',
                    data     : ({tabel:'trhspp',kolom:lcinsert,nilai:lcvalues,cid:'no_spp',lcid:a}),
                    dataType : "json",
                    success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
                                  alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                                  exit();
                               } else {
                                  dsimpan_up();
                                  alert('Data Tersimpan..!!');
                                  lcstatus = 'edit';
                                  section4();
                                  exit();
                               }
                    }
                });
            });   
           
        } else {
            
            lcquery = " UPDATE trhspp SET kd_skpd='"+kode+"', keperluan='"+e+"', no_spd='"+spd+"', jns_spp='"+c+"', bank='"+g+"', no_rek='"+i+"', npwp='"+h+"', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', no_spp='"+a+"' where no_spp='"+a_hide+"' AND  kd_skpd='"+kode+"' "; 
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/sppc/update_tukd_spp',
                data     : ({st_query:lcquery,tabel:'trhspp',cid:'no_spp',lcid:a,lcid_h:a_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='1' ){
                            alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
                            dsimpan_up_edit() ;
                            alert('Data Tersimpan...!!!');
                            lcstatus = 'edit';
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
    }
    
    
    function dsimpan_up() {
        
        var a         =(document.getElementById('no_spp').value).split(" ").join("");
        var rek_up    = $("#rekup").combogrid("getValue") ;
        var nm_rek_up = document.getElementById('nmrekup').value ;
        var nilai_up  = angka(document.getElementById('nilaiup').value) ;

    
    
        $(function(){      
         $.ajax({
            type     : 'POST',
            data     : ({cno_spp:a,cskpd:kode,crek:rek_up,nrek:nm_rek_up,nilai:nilai_up,spd:spd}),
            dataType : "json",
            url      : "<?php echo base_url(); ?>index.php/sppc/dsimpan_up",
            success  : function(data){
            }            
         });
         });
        $("#no_spp_hide").attr("Value",a);
    } 
    
    function dsimpan_up_edit() {
        
        var a         = (document.getElementById('no_spp').value).split(" ").join("");
        var a_hide    = document.getElementById('no_spp_hide').value;
        var rek_up    = $("#rekup").combogrid("getValue") ;
        var nm_rek_up = document.getElementById('nmrekup').value ;
        var nilai_up  = angka(document.getElementById('nilaiup').value) ;
        //alert("'"+a+"','"+rek_up+"','"+nilai_up+"','"+kode+"'");
    
    
        $(function(){      
         $.ajax({
            type     : 'POST',
            data     : ({cno_spp:a,cskpd:kode,crek:rek_up,nrek:nm_rek_up,nilai:nilai_up,no_hide:a_hide}),
            dataType : "json",
            url      : "<?php echo base_url(); ?>index.php/sppc/dsimpan_up_edit",
            success  : function(data){
            }            
         });
         });
        $("#no_spp_hide").attr("Value",a);
    }  
    
    

   
    
    
    function hhapus(){        
            
            var spp = document.getElementById("no_spp").value;
            var nospp =spp.split("/").join("######");  
            var urll= '<?php echo base_url(); ?>/index.php/sppc/hapus_spp3';                      
          if (spp !=''){
        var del=confirm('Anda yakin akan menghapus SPP '+spp+'  ?');
        if  (del==true){
          $(document).ready(function(){
                    $.post(urll,({no:nospp}),function(data){
                    status = data;
                    if(status==1){
                        alert('Data Berhasil Di Hapus');
                    }else if(status==2){
                        alert('Data SPP No. '+ spp +'Sudah di SPM kan');
                        exit();
                    }else{
                        alert('Data Gagl di Hapus');
                    }
                                            
                    });
                    });       
        }
        } 
    }


    function tombol(st){ 
        if (st==1){
   
            $('#save').hide();
            $('#del').hide();
            $('#sav').hide();
            $('#dele').hide();    
            document.getElementById("p1").innerHTML="<button class='button-merah'>Sudah di Buat SPM!!</button>";
        } else {
           $('#save').show();
           $('#del').show();
           $('#sav').hide();
           $('#dele').hide();
          document.getElementById("p1").innerHTML="";
        }
    } 
    
        
    function openWindow( url )
        {
    var nomer   = $("#cspp").combobox('getValue');
        var jns = document.getElementById('jns_beban').value; 
        var no =nomer.split("/").join("123456789");
    var ttd1   = $("#ttd1").combogrid('getValue');
    var ttd2   = $("#ttd2").combogrid('getValue');
    var ttd4   = $("#ttd4").combogrid('getValue');
    var tanpa       = document.getElementById('tanpa_tanggal').checked; 
    if ( tanpa == false ){
           tanpa=0;
        }else{
           tanpa=1;
        }
    if ( ttd1 =='' ){
      alert("Bendahara Pengeluaran tidak boleh kosong!");
      exit();
    }
    if ( ttd2 =='' ){
      alert("PPTK tidak boleh kosong!");
      exit();
    }
    if ( ttd4 =='' ){
      alert("PPKD tidak boleh kosong!");
      exit();
    }
        var ttd_1 =ttd1.split(" ").join("123456789");
        var ttd_2 =ttd2.split(" ").join("123456789");
        var ttd_4 =ttd4.split(" ").join("123456789");


        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_1+'/'+ttd_2+'/'+ttd_4+'/'+tanpa, '_blank');
        window.focus();
        }
        
        function openWindow2( url )
        {
        var nomer   = $("#cspp").combogrid('getValue');
        var jns = document.getElementById('jns_beban').value; 
        var no =nomer.split("/").join("123456789");
        var ttd3   = $("#ttd3").combogrid('getValue');
    var tanpa       = document.getElementById('tanpa_tanggal').checked; 
    if ( tanpa == false ){
           tanpa=0;
        }else{
           tanpa=1;
        }
    if ( ttd3 =='' ){
      alert("Bendahara Pengeluaran tidak boleh kosong!");
      exit();
    }
    
        var ttd_3 =ttd3.split(" ").join("123456789");
        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanpa, '_blank');
        window.focus();
        }  
        
    </script>

</head>
<body>



<div id="content">
<div id="accordion">
<h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List SPP UP</a></h3>
    <div>
    <p align="right">         
        <button class="button" onclick="javascript:section1();kosong();"><i class="fa fa-tambah"></i> Tambah SPP</button>
        <button class="button-cerah" onclick="javascript:cari();"><i class="fa fa-cari"></i> Cari</button>                
        <input type="text" value="" class="input" style="display: inline;" id="txtcari"/>
        <table id="spp" title="List SPP UP" style="width:1024px;height:450px;" >  
        </table>
    </p> 
    </div>

<h3><a href="#" id="section1">Input SPP UP</a></h3>
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>

<fieldset style="width:1024px;height:650px;border-color:white;border-style:hidden;border-spacing:0;padding:0; border-radius: 20px">            
<fieldset>
<table border='0' style="font-size:11px" >

 <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">   
   <td width="8%" style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" >&nbsp;</td>
   <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
   <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
   <td style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>   
 </tr>
 
 <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">   
   <td width="8%" style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" >No SPP</td>
   <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><input type="text" class="input" readonly name="no_spp" id="no_spp" onkeyup="this.value=this.value.toUpperCase()" style="width:250px;" /><input type="hidden" name="no_u" id="no_u" style="width:200px;" /><input type="hidden" name="no_spp_hide" id="no_spp_hide" onclick="javascript:select();" style="width:200px;" /></td>
   <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">Tanggal</td>
   <td style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;<input id="dd" name="dd" type="text" class="input" style="width:250px" /><input type ="input" hidden readonly="true" style="border:hidden" id="tahunsekarang" name="tahunsekarang" /></td>   
 </tr>
 
 <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td width='8%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">SKPD</td>
   <td colspan='3' width="53%" style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" >     
      <input id="dn" name="dn" readonly="true" style="width:250px; border: 0; " /><textarea type="text" name="jns_beban"  id="jns_beban" hidden value="1"></textarea> <br><input name="nmskpd" id="nmskpd"  style="border: 0; width: 400px"  readonly="true"> </td> 
  </td>
 </tr>
 
 <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td width='8%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">No SPD</td>
   <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><input id="sp" name="sp" style="width:260px" /></td>
   
      <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >BANK</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input style="width:250px" type="text" name="bank1" id="bank1" />
    &nbsp;<input type ="input" readonly="true" style="border:hidden; width: 250px" id="nama_bank" name="nama_bank"/></td>
 </tr>
 
 <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
   <td style="border-bottom-style:hidden;border-right-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='8%'>NPWP</td>
   <td style="border-bottom-style:hidden;border-right-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='53%'><input type="text" class="input" name="npwp" id="npwp" value="" style="width:250px;" /></td>
   <td style="border-bottom-style:hidden;border-right-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='8%'>Rekening</td>
   <td style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='31%'>&nbsp;<input type="text" class="input" name="rekening" id="rekening"  value="" style="width:250px;" /></td>
 </tr>
 
 <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
   <td   style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">Keperluan</td>
   <td colspan="3" style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea name="ketentuan" class="textarea" id="ketentuan" style="margin: 5px 0px 10px 5px; width: 488px; height: 90px;" ></textarea></td>
 </tr>

</table>
        </fieldset>
 <br><br>
        
        <table border='1'>
        
            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
                 <td colspan='3' style="font-size:20px;font:bold;color:#004080;" >DETAIL SPP UP</td>
            </tr>
            
            <tr>
                 <td colspan='3' style="border-bottom-style:hidden;">&nbsp;</td>
            </tr>

        
            <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-bottom-style:hidden;">
                 <td width='10%' style="border-right-style:hidden;border-bottom-style:hidden;">Rekening</td>
                 <td width='15%' style="border-right-style:hidden;border-bottom-style:hidden;"><input type="text" name="rekup" id="rekup" value="" style="width:210px;" /></td>
                 <td width='75%' style="border-bottom-style:hidden;"><input type="text" name="nmrekup" id="nmrekup" value="" style="width:500px;border:0" readonly="true" /></td>
            </tr>
            
            <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
                 <td width='10'  style="border-bottom-style:hidden;border-right-style:hidden;">Nilai</td>
                 <td width='15%' style="border-bottom-style:hidden;border-bottom-color:black;border-right-style:hidden;"><input type="text" name="nilaiup" class="input" id="nilaiup"  value="" style="width:200px;text-align:right;"  onkeypress="return(currencyFormat(this,',','.',event))"/> </td>
                 <td width='75'  style="border-bottom-style:hidden;">&nbsp;</td>
            </tr>
            
            <tr>
                 <td colspan='3' style="border-bottom-color:black;">&nbsp;</td>
            </tr>
            
        </table>
        
        
        <table align="right">
            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
                <td align="right">
                  <div>
                        <button class="button-biru" id="save" onclick="javascript:$('#dg1').edatagrid('addRow');javascript:$('#dg1').edatagrid('reload');javascript:hsimpan();"><i class="fa fa-simpan"></i> Simpan</button>
                        <button class="button-merah" id="del" onclick="javascript:hhapus();"><i class="fa fa-hapus"></i> Hapus</button>
                        <button class="button-cerah" onclick="javascript:section4();"><i class="fa fa-kiri"></i> Kembali</button>
                        <button class="button-cerah" onclick="javascript:cetak();"><i class="fa fa-print"></i> Cetak</button>
                        
                  </div>
                </td>                
            </tr>
        </table>
      
      
   </p>
   </fieldset> 
   </div>

</div>
</div> 

<div id="dialog-modal" title="CETAK SPP">
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    <fieldset>
    <table>
        <tr>            
            <td width="110px">NO SPP:</td>
            <td><input id="cspp" name="cspp" style="width: 170px;" disabled />  &nbsp; &nbsp; &nbsp; <input type="checkbox" id="tanpa_tanggal"> Tanpa Tanggal</td>
        </tr>
       
    <tr>
            <td width="110px">Bend. Pengeluaran:</td>
            <td><input id="ttd1" name="ttd1" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd1" name="nmttd1" style="width: 170px;border:0" /></td>
        </tr>
    <tr>
            <td width="110px">PPTK:</td>
            <td><input id="ttd2" name="ttd2" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd2" name="nmttd2" style="width: 170px;border:0" /></td>
        </tr>
    <tr>
            <td width="110px">PA:</td>
            <td><input id="ttd3" name="ttd3" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd3" name="nmttd3" style="width: 170px;border:0" /></td>
        </tr>
    <tr>
            <td width="110px">PPKD:</td>
            <td><input id="ttd4" name="ttd4" style="width: 170px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd4" name="nmttd4" style="width: 170px;border:0" /></td>
        </tr>
    </table>  
    </fieldset>
    <div>
    </div>    
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp1/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Pengantar</a>
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp2/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Ringkasan</a>
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp3/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp4/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow2(this.href);return false;">Pernyataan</a>
  <br/>
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp1/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Pengantar</a>
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp2/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Ringkasan</a>
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp3/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
  <a href="<?php echo site_url(); ?>cetak_spp/cetakspp4/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow2(this.href);return false;">Pernyataan</a>
  <br/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  
  <!--<a href="<?php echo site_url(); ?>cetak_spp/cetakspp1/2 "class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:openWindow(this.href);return false;">Pengantar</a>
  --><a href="<?php echo site_url(); ?>cetak_spp/cetakspp2/2 "class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:openWindow(this.href);return false;">Ringkasan</a>
  &nbsp;&nbsp;&nbsp;<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>  
</div>
  
</body>

</html>