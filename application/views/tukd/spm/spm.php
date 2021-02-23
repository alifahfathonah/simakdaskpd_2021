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
     
    var nl           = 0;
    var tnl          = 0; 
    var idx          = 0;
    var tidx         = 0;
    var oldRek       = 0;
    var rek          = 0;
    var lcstatus     = 'tambah';
    var jumlah_pajak = 0;
    var pidx         = 0;
    var jns_bebann   = '';
    var kd_sub_skpd   = '';
    var rekkkk       = '';
    var tahun_anggaran = '<?php echo $this->session->userdata('pcThang'); ?>';
  
    $(function(){
        

        $('#dd').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
        return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        },
      onSelect: function(date){
        
            $("#kebutuhan_bulan").attr("Value",(date.getMonth()+1));
            }
        });
        


        $('#cspm').combogrid();
                
        $('#bank1').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/spmc/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:40},  
                           {field:'nama_bank',title:'Nama',width:140}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });
        
    $('#cc').combogrid({
          url:'<?php echo base_url(); ?>/index.php/sppc/load_jenis_beban',
          idField:'id',  
          textField:'text',
          mode:'remote',  
          fitColumns:true,  
          columns:[[  
                 {field:'text',title:'Jenis Beban',width:40}
             ]], 
        });

        $('#spm').edatagrid({
            url: '<?php echo base_url(); ?>/index.php/spmc/load_spm',
                idField       : 'id',            
                rownumbers    : "true",  
                fitColumns    : "true",
                singleSelect  : "true",
                autoRowHeight : "false",
                loadMsg       : "Tunggu Sebentar....!!",
                pagination    : "true",
                nowrap        : "true",
                rowStyler: function(index,row){
/*                    if (row.tot_spm >= 2){
                        return 'background-color:#ff471a;';
                    }else if (row.status == 1){
                      return 'background-color:#03d3ff;';
                    }else{
                        if (row.tot_spm >= 2){
                            return 'background-color:#ff471a;';
                        }else if (row.stt_valspm == 1 || row.stt_valspm == 2){
                            return 'background-color:#FFD700;';
                        }else if(row.stt_valspm ==3){
                            return 'background-color:#F08080;';
                        } 
                    }*/
                },                      
                columns:[[
          {title:'',
          width:5,
          checkbox:"true"},
                  {field:'no_spm',
                title:'Nomor SPM',
                width:70},
                    {field:'tgl_spm',
                title:'Tanggal',
                width:30},
                    {field:'kd_skpd',
                title:' SKPD',
                width:30,
                    align:"left", hidden:'true'},
                    {field:'keperluan',
                title:'Keterangan',
                width:100,
                    align:"left"},
                    {field:'status2',
                title:'Status',
                width:40,
                    align:"left"}
                ]],
                onSelect:function(rowIndex,rowData){
 
                },
                onDblClickRow:function(rowIndex,rowData,st){
                  urut     = rowData.urut;
                  no_spm   = rowData.no_spm;
                  no_spp   = rowData.no_spp;
                  skpd     = rowData.kd_skpd;
                  kd_sub_skpd     = rowData.kd_sub_skpd;         
                  tgs      = rowData.tgl_spm;
                  st       = rowData.status;
                  st_spm   = rowData.stt_valspm;
                  jns      = rowData.jns_spp;
                  jns_bbn  = rowData.jns_beban;
                  nospd    = rowData.no_spd;
                  tgspp    = rowData.tgl_spp;
                  cnpwp    = rowData.npwp;
                  nbl      = rowData.bulan;
                  ckep     = rowData.keperluan;
                  bank     = rowData.bank;
                  crekan   = rowData.nmrekan;
                  cnorek   = rowData.no_rek;
                  cnmskpd  = rowData.nm_skpd;
                  ctot_spm = rowData.tot_spm;

                  lcstatus = 'edit';  
                    section2();
                    detail();
                    tampil_potongan(); 
                    getspm(urut,no_spm,no_spp,tgs,st,jns,skpd,nospd,tgspp,cnpwp,nbl,ckep,bank,crekan,cnorek,cnmskpd,jns_bbn,st_spm,ctot_spm);  
                    detail();   
                }
            });
            
            
            
            $('#nospp').combogrid({  
                panelWidth : 500,  
                url        : '<?php echo base_url(); ?>spmc/nospp_2',  
                idField    : 'no_spp',                    
                textField  : 'no_spp',
                mode       : 'remote',  
                fitColumns : true,  
                columns:[[  
                        {field:'no_spp',title:'No',width:200,align:'left'},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:150} 
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_spp = rowData.no_spp;         
                        skpd   = rowData.kd_skpd;
                        kd_sub_skpd   = rowData.kd_sub_skpd;
                        sp     = rowData.no_spd;          
                        bl     = rowData.bulan;
                        tg     = rowData.tgl_spp;
                        jns    = rowData.jns_spp;
                        jns_bbn= rowData.jns_beban;
                        kep    = rowData.keperluan;
                        np     = rowData.npwp;
                        rekan  = rowData.nmrekan;
                        bk     = rowData.bank;
                        ning   = rowData.no_rek;
                        nm     = rowData.nm_skpd.trim();
                             
                        get(no_spp,skpd,sp,tg,bl,jns,kep,np,rekan,bk,ning,nm,jns_bbn);
                        get_spm(no_spp);
                        detail();
            
                    }  
                });
                
                
                $('#dg').edatagrid({
                    url           : '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                    autoRowHeight : "true",
                    idField       : 'id',
                    toolbar       : "#toolbar",              
                    rownumbers    : "true", 
                    fitColumns    : true,
                    singleSelect  : "true"
                    });
            
                
                $('#rekpajak').combogrid({  
                   panelWidth : 700,  
                   idField    : 'kd_rek5',  
                   textField  : 'kd_rek5',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/spmc/rek_pot',  
                   columns:[[  
                       {field:'kd_rek5',title:'Kode Rekening',width:100},  
                       {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek5);
                   }  
                   });
                   
                   
          $('#dgpajak').edatagrid({
               url            : '<?php echo base_url(); ?>/index.php/spmc/pot',
                     idField        : 'id',
                     toolbar        : "#toolbar",              
                     rownumbers     : "true", 
                     fitColumns     : true,
                     autoRowHeight  : "true",
                     singleSelect   : false,
                     columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_trans',title:'Rek. Trans',width:100,align:'left'},     
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},      
              {field:'nm_rek5',title:'Nama Rekening',width:317},
              {field:'nilai',title:'Nilai',width:100,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return "<button class='button-merah' onclick='javascript:hapus_detail();'>Hapus</button>";
                        }
                        }
              ]]  
              });

          
        $('#ttd1').combogrid({  
          panelWidth:600,  
          idField:'id_ttd',  
          textField:'nip',  
          mode:'remote',
          url:'<?php echo base_url(); ?>index.php/spmc/load_bendahara_p/<?php echo $this->session->userdata('kdskpd'); ?>',  
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
          url:'<?php echo base_url(); ?>index.php/spmc/load_ppk_pptk/<?php echo $this->session->userdata('kdskpd'); ?>',  
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
          url:'<?php echo base_url(); ?>index.php/spmc/load_tanda_tangan/<?php echo $this->session->userdata('kdskpd'); ?>',  
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
          url:'<?php echo base_url(); ?>index.php/spmc/load_ttd_bud/<?php echo $this->session->userdata('kdskpd'); ?>',  
          columns:[[  
            {field:'nip',title:'NIP',width:200},  
            {field:'nama',title:'Nama',width:400}    
          ]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd4").attr("value",rowData.nama);
                    }  
        }); 
        });

           
       
        
        function detail(){
        $(function(){
      $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      load_sum_spm();
                      },                                                 
                 columns:[[
                  {field:'ck',
           title:'ck',
           checkbox:true,
           hidden:true},                     
                     {field:'kdkegiatan',
           title:'Kegiatan',
           width:165,
           align:'left'
          },
          {field:'kdrek5',
           title:'Rekening',
           width:70,
           align:'left'
          },
          {field:'nmrek5',
           title:'Nama Rekening',
           width:350           
          },                    
                    {field:'nilai1',
           title:'Nilai',
           width:170,
                     align:'right'
                     }
        ]]  
      });
    });
        }
        
        $('#rekpajak').combogrid({  
                   panelWidth : 700,  
                   idField    : 'kd_rek5',  
                   textField  : 'kd_rek5',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/spmc/rek_pot',  
                   columns:[[  
                       {field:'kd_rek5',title:'Kode Rekening',width:100},  
                       {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek5);
                   }  
                   });

        
        function detail1(){
        $(function(){
            var no_spp='';
      $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams:({spp:no_spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 autoRowHeight:"true",
                 singleSelect:false,                                         
                 columns:[[
                  {field:'ck',
           title:'ck',
           checkbox:true,
           hidden:true},                     
                     {field:'kdkegiatan',
           title:'Kegiatan',
           width:165,
           align:'left'
          },
          {field:'kdrek5',
           title:'Rekening',
           width:70,
           align:'left'
          },
          {field:'nmrek5',
           title:'Nama Rekening',
           width:400           
          },                    
                    {field:'nilai1',
           title:'Nilai',
           width:100,
                     align:'right'
                     }
        ]]  
      });
    });
        }
              
    function pajak_kosong(){
      $('#dgpajak').edatagrid({
               url            : '<?php echo base_url(); ?>/index.php/spmc/pot_kosong',
                     idField        : 'id',
                     toolbar        : "#toolbar",              
                     rownumbers     : "true", 
                     fitColumns     : true,
                     autoRowHeight  : "true",
                     singleSelect   : false,
                     columns:[[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_trans',title:'Rek. Trans',width:100,align:'left'},     
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},      
              {field:'nm_rek5',title:'Nama Rekening',width:317},
              {field:'nilai',title:'Nilai',width:100,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return "<button class='button-merah' onclick='javascript:hapus_detail();'>Hapus</button>";
                        }
                        }
              ]]  
              });
    }
    
        function get(no_spp,kd_skpd,no_spd,tgl_spp,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,nm_skpd,jns_bbn){
            $("#nospp").attr("value",no_spp);
            $("#nospp1").attr("value",no_spp);
            $("#dn").attr("value",kd_skpd);
            $("#tgl_spp").attr("value",tgl_spp);
            $("#dd").datebox("setValue",tgl_spp);
            $("#sp").attr("value",no_spd);
            $("#kebutuhan_bulan").attr("Value",bulan);
            $("#ketentuan").attr("Value",keperluan);
            $("#jns_beban").attr("Value",jns_spp);
            $("#npwp").attr("Value",npwp);
            $("#rekanan").attr("Value",rekanan);
            $("#bank1").combogrid("setValue",bank);
            $("#rekening").attr("Value",rekening);
            $("#nmskpd").attr("Value",nm_skpd);
            $('#cc').combogrid({url:'<?php echo base_url(); ?>/index.php/sppc/load_jenis_beban/'+jns_spp});
            $("#cc").combogrid("setValue",jns_bbn);
            validate_rek_trans(no_spp);

        }
                  
        
        function getspm(urut,no_spm,no_spp,tgl_spm,status,jns_spp,kd_skpd,nospd,tgspp,npwp,bulan,keperluan,bank,rekanan,rekening,nm_skpd,jns_bbn,st_spm,ctot_spm){
            $('#cc').combogrid({url:'<?php echo base_url(); ?>/index.php/sppc/load_jenis_beban/'+jns_spp});
            $("#cc").combogrid("setValue",jns_bbn);
            $("#dd_spm").attr("value",urut);            
            $("#no_spm").attr("value",no_spm);
            $("#spm_pot").attr("value",no_spm);
            $("#no_spm_hide").attr("value",no_spm);
            $("#nospp").combogrid("setValue",no_spp);
            $("#nospp1").attr("value",no_spp); 
            $("#dd").datebox("setValue",tgl_spm);
            $("#jns_beban").attr("Value",jns_spp);
            $("#dn").attr("Value",kd_skpd);
            $("#sp").attr("value",nospd);   
            $("#tgl_spp").attr("value",tgspp);
            $("#npwp").attr("Value",npwp);
            $("#kebutuhan_bulan").attr("Value",bulan);
            $("#ketentuan").attr("Value",keperluan);
            $("#bank1").combogrid("setValue",bank);
            $("#rekanan").attr("Value",rekanan);
            $("#rekening").attr("Value",rekening);
            $("#nmskpd").attr("Value",nm_skpd);
            tampil_potongan();          
            load_sum_pot();
            tombol(status,st_spm,ctot_spm); 
            validate_rek_trans(no_spp);
            $('#ctk').show();

      
      
        }
    
        
        function kosong(){
            lcstatus = 'tambah';    
            cdate    = '<?php echo date("Y-m-d"); ?>';
            $("#dd_spm").attr("value",'');            
            $("#no_spm").attr("value",'');
            $("#no_spm_hide").attr("value",'');
            $("#spm_pot").attr("value",'');
            $("#dd").datebox("setValue",cdate);
            $("#nospp").combogrid("setValue",'');       
            $("#dn").attr("value",'');
            $("#sp").attr("value",'');        
            $("#tgl_spp").attr("value",'');
            $("#kebutuhan_bulan").attr("Value",'');
            $("#ketentuan").attr("Value",'');
            $("#jns_beban").attr("Value",'');
            $("#npwp").attr("Value",'');
            $("#rekanan").attr("Value",'');
             $("#bank1").combogrid("setValue",'');
            $("#rekening").attr("Value",'');
            $("#nmskpd").attr("Value",'');
            document.getElementById("p1").innerHTML="";
            detail1();
            $("#nospp").combogrid("clear");
            tombolnew();
      pajak_kosong();
            $("#totalrekpajak").attr("value",0);
            
        }
        

    $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $("#dialog-modal").dialog({
            height: 450,
            width: 750,
            modal: true,
            autoOpen:false
    });
   

    });
       
    
    function cetak(){
        var nom=document.getElementById("no_spm").value;
        $("#cspm").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
    

    
  function get_spm(no_spp){
    
      var xxsk = "";
      var yysk = "";
      
      var jenis_ls = document.getElementById('jns_beban').value;
      var skpdspm = document.getElementById('dn').value;
      
      var xxsk = skpdspm.substr(0,4);
      var yysk = skpdspm.substr(8,2);
      
      
      
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/spmc/config_spm',
            type: "POST",   
            dataType : 'json',                 
            data: ({no_spp:no_spp}),                      
            success:function(data){
              
          var inisial = data;
          $("#no_spm").attr("value",inisial);
          $("#spm_pot").attr("value",inisial);
          $("#dd_spm").attr("value",no_spm);
              }                                     
          });
        } 
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spm').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/spmc/load_spm',
         queryParams:({cari:kriteria})
        });        
     });
    }
        
    function data_no_spp(){
      $('#nospp').combogrid({url: '<?php echo base_url(); ?>spmc/nospp_2'});  
    }
    
    function simpan_spm(){
        var a1      = (document.getElementById('no_spm').value).split(" ").join("");
        var a1_hide = document.getElementById('no_spm_hide').value;
        var a1_dd   = document.getElementById('dd_spm').value;
        var b1      = $('#dd').datebox('getValue'); 
        var b       = document.getElementById('tgl_spp').value;      
        var c       = document.getElementById('jns_beban').value; 
        var d       = document.getElementById('kebutuhan_bulan').value;
        var e       = document.getElementById('ketentuan').value;
        var f       = document.getElementById('rekanan').value;
        var g       = $("#bank1").combogrid("getValue") ; 
        var h       = document.getElementById('npwp').value;
        var i       = document.getElementById('rekening').value;
        var j       = document.getElementById('nmskpd').value;
        var k       = document.getElementById('dn').value;
        var l       = document.getElementById('sp').value;
        var m       = document.getElementById('rekspm1').value; 
        var cc      = $('#cc').combogrid('getValue'); 
    var tahun_input = b1.substring(0, 4);
    
    //khusus januari
    if(c=='6'){
    if (tahun_input != tahun_anggaran){
      alert('Tahun tidak sama dengan tahun Anggaran');
      exit();
    }
    }
    
    if (a1==""){
    alert ("No SPM Tidak Boleh Kosong");
    exit();
    }
    if (l==""){
    alert ("No SPD Tidak Boleh Kosong");
    exit();
    }
    if (b>b1){
    alert("Tanggal SMP tidak boleh lebih kecil dari tanggal SPP");
    exit();
    }
    var lenket = e.length;
    if ( lenket>1000 ){
            alert('Keterangan Tidak boleh lebih dari 1000 karakter');
            exit();
        }
        if (lcstatus=='tambah') { 

            lcinsert = " ( no_spm,     tgl_spm,   no_spp,       kd_skpd,  nm_skpd,  tgl_spp,  bulan,   no_spd,  keperluan, username, last_update, status, jns_spp, jenis_beban,  bank,     nmrekan,  no_rek,   npwp,    nilai, kd_sub_skpd   ) ";
            lcvalues = " ( '"+a1+"',   '"+b1+"',  '"+no_spp+"', '"+k+"',  '"+j+"',  '"+b+"',  '"+d+"', '"+l+"', '"+e+"',   '<?php echo $this->session->userdata('pcNama') ?>',       '',          '0',    '"+c+"', '"+cc+"',  '"+g+"',  '"+f+"',  '"+i+"',  '"+h+"', '"+m+"','"+kd_sub_skpd+"') ";           
            
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/spmc/simpan_tukd_spm',
                    data     : ({tabel:'trhspm',kolom:lcinsert,nilai:lcvalues,cid:'no_spm',lcid:a1,tagih:no_spp}),
                    dataType : "json",
                    success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
                                  alert('Nomor SPM Sudah Terpakai...!!!,  Ganti Nomor SPM...!!!');
                                  exit();
                               } else {
                   //cek potongan
                     var ctot_det_pot=0;
                     $('#dgpajak').datagrid('selectAll');
                      var rows = $('#dgpajak').datagrid('getSelections');           
                      for(var x=0;x<rows.length;x++){
                      cnilai3     = angka(rows[x].nilai);
                      ctot_det_pot = ctot_det_pot + cnilai3;
                      }
                  //jika potongan tidak ada                     
                    if (ctot_det_pot==0){
                      $("#no_spm_hide").attr("value",a1);
                      lcstatus='edit';
                      alert('Data Tersimpan..!! Tak ada potongan!');
                      data_no_spp();  
                      } else{
                      //input potongan
                          $('#dgpajak').datagrid('selectAll');
                          var rows = $('#dgpajak').datagrid('getSelections');
                          for(var i=0;i<rows.length;i++){            
                            cidx      = rows[i].idx;
                            ckdrek5   = rows[i].kd_rek5;
                            ckd_trans = rows[i].kd_trans;
                            cnm_rek5  = rows[i].nm_rek5;
                            cnilai    = angka(rows[i].nilai);
                            no        = i + 1 ;    
                              if (i>0) {
                                csql = csql+","+"('"+a1+"','"+ckdrek5+"','"+cnm_rek5+"','"+cnilai+"','"+k+"',' ','"+ckd_trans+"', '"+kd_sub_skpd+"')";
                              } else {
                                csql = "values('"+a1+"','"+ckdrek5+"','"+cnm_rek5+"','"+cnilai+"','"+k+"',' ','"+ckd_trans+"', '"+kd_sub_skpd+"')";         
                                }                                             
                              } 
                              
                              $(document).ready(function(){
                                  $.ajax({
                                  type: "POST",   
                                  dataType : 'json',                 
                                  data: ({no:a1,sql:csql}),
                                  url: '<?php echo base_url(); ?>/index.php/spmc/dsimpan_potspm',
                                  success:function(data){                        
                                    status = data.pesan;   
                                     if (status=='1'){
                                      //$("#loading").dialog('close');
                                      $("#no_spm_hide").attr("value",a1);
                                      lcstatus='edit';
                                      alert('Data Tersimpan..!!');
                                      data_no_spp();
                                      section1();
                                      exit();
                                    } else{ 
                                      //$("#loading").dialog('close');
                                      lcstatus='tambah';
                                      alert('Detail Gagal Tersimpan...!!!');
                                    }                                             
                                  }
                                });
                              }); 
                      //akhir input potongan
                    }
                   
                   
                   
                    
                               }
                    }
                });
            });   
           
        } else {
            
            lcquery = " UPDATE trhspm SET  kd_sub_skpd='"+kd_sub_skpd+"',no_spm='"+a1+"',  tgl_spm='"+b1+"',  no_spp='"+no_spp+"', kd_skpd='"+k+"',  nm_skpd='"+j+"', tgl_spp='"+b+"',  bulan='"+d+"',   no_spd='"+l+"',  keperluan='"+e+"',  username='',  last_update='',  status='0',  jns_spp='"+c+"', jenis_beban='"+cc+"',  bank='"+g+"',  nmrekan='"+f+"',  no_rek='"+i+"',  npwp='"+h+"',  nilai='"+m+"' where no_spm='"+a1_hide+"' AND kd_skpd='"+k+"' "; 
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/spmc/update_spm',
                data     : ({st_query:lcquery,tabel:'trhspm',cid:'no_spm',lcid:a1,lcid_h:a1_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        if ( status=='1' ){
                            alert('Nomor SPM Sudah Terpakai...!!!,  Ganti Nomor SPM...!!!');
                            exit();
                        }
                        if ( status=='2' ){
              //cek potongan
                     var ctot_det_pot=0;
                     $('#dgpajak').datagrid('selectAll');
                      var rows = $('#dgpajak').datagrid('getSelections');           
                      for(var x=0;x<rows.length;x++){
                      cnilai3     = angka(rows[x].nilai);
                      ctot_det_pot = ctot_det_pot + cnilai3;
                      }
              //jika potongan tidak ada                     
                    if (ctot_det_pot==0){
                      $("#no_spm_hide").attr("value",a1);
                      lcstatus='edit';
                      alert('Data Tersimpan..!! Tak ada potongan!');
                      data_no_spp();  
                      } else {
                      $('#dgpajak').datagrid('selectAll');
                  var rows = $('#dgpajak').datagrid('getSelections');
                  for(var i=0;i<rows.length;i++){            
                    cidx      = rows[i].idx;
                    ckdrek5   = rows[i].kd_rek5;
                    ckd_trans = rows[i].kd_trans;
                    cnm_rek5  = rows[i].nm_rek5;
                    cnilai    = angka(rows[i].nilai);
                    no        = i + 1 ;    
                      if (i>0) {
                        var csql = csql+","+"('"+a1+"','"+ckdrek5+"','"+cnm_rek5+"','"+cnilai+"','"+k+"',' ','"+ckd_trans+"', '"+kd_sub_skpd+"')";
                      } else {
                        var csql = "values('"+a1+"','"+ckdrek5+"','"+cnm_rek5+"','"+cnilai+"','"+k+"',' ','"+ckd_trans+"', '"+kd_sub_skpd+"')";         
                        }                                             
                      }  
                      $(document).ready(function(){
                        //alert(csql);
                        //exit();
                        $.ajax({
                          type: "POST",   
                          dataType : 'json',                 
                          data: ({no:a1,sql:csql,no_hide:a1_hide}),
                          url: '<?php echo base_url(); ?>/index.php/spmc/update_dsimpan_potspm',
                          success:function(data){                        
                            status = data.pesan;   
                             if (status=='1'){
                              //$("#loading").dialog('close');
                              $("#no_spm_hide").attr("value",a1);
                              lcstatus='edit';
                              alert('Data Tersimpan..!!');
                              data_no_spp();
                              section1();
                              exit();
                            } else{ 
                              //$("#loading").dialog('close');
                              lcstatus='tambah';
                              alert('Detail Gagal Tersimpan...!!!');
                            }                                             
                          }
                        });
                        }); 
                      }
                }
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                    }
            });
            });
            }
            //$("#no_spm_hide").attr("Value",a1);
        }
        
    function edit_keterangan(){
    
    var a1      = (document.getElementById('no_spm').value).split(" ").join("");
        var a1_hide = document.getElementById('no_spm_hide').value;
        var b1      = $('#dd').datebox('getValue'); 
        var b       = document.getElementById('tgl_spp').value;      
        var c       = document.getElementById('jns_beban').value; 
        var d       = document.getElementById('kebutuhan_bulan').value;
        var e       = document.getElementById('ketentuan').value;
        var f       = document.getElementById('rekanan').value;
        var g       = $("#bank1").combogrid("getValue") ; 
        var h       = document.getElementById('npwp').value;
        var i       = document.getElementById('rekening').value;
        var j       = document.getElementById('nmskpd').value;
        var k       = document.getElementById('dn').value;
        var l       = document.getElementById('sp').value;
        var m       = document.getElementById('rekspm1').value; 
        var cc      = $('#cc').combogrid('getValue'); 
    var tahun_input = b1.substring(0, 4);
    if (tahun_input != tahun_anggaran){
      alert('Tahun tidak sama dengan tahun Anggaran');
      exit();
    }
    if (a1==""){
    alert ("No SPM Tidak Boleh Kosong");
    exit();
    }
    if (l==""){
    alert ("No SPD Tidak Boleh Kosong");
    exit();
    }
    if (b>b1){
    alert("Tanggal SPM tidak boleh lebih kecil dari tanggal SPP");
    exit();
    }
    var lenket = e.length;
    if ( lenket>1000 ){
            alert('Keterangan Tidak boleh lebih dari 1000 karakter');
            exit();
        }
  
        lcquery = " UPDATE trhspm SET keperluan='"+e+"', tgl_spm='"+b1+"' where no_spm='"+a1+"' AND no_spp='"+no_spp+"' AND kd_skpd='"+k+"'"; 
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/spmc/update_spm',
                data     : ({st_query:lcquery,tabel:'trhspm',cid:'no_spm',lcid:a1,lcid_h:a1_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        if ( status=='1' ){
                            alert('Nomor SPM Sudah Terpakai...!!!,  Ganti Nomor SPM...!!!');
                            exit();
                        }
                        if ( status=='2' ){
              //cek potongan
                     var ctot_det_pot=0;
                     $('#dgpajak').datagrid('selectAll');
                      var rows = $('#dgpajak').datagrid('getSelections');           
                      for(var x=0;x<rows.length;x++){
                      cnilai3     = angka(rows[x].nilai);
                      ctot_det_pot = ctot_det_pot + cnilai3;
                      }
              //jika potongan tidak ada                     
                    if (ctot_det_pot==0){
                      $("#no_spm_hide").attr("value",a1);
                      lcstatus='edit';
                      alert('Data Tersimpan..!!');
                      data_no_spp();  
                      } else {
                      $('#dgpajak').datagrid('selectAll');
                  var rows = $('#dgpajak').datagrid('getSelections');
                  for(var i=0;i<rows.length;i++){            
                    cidx      = rows[i].idx;
                    ckdrek5   = rows[i].kd_rek5;
                    ckd_trans = rows[i].kd_trans;
                    cnm_rek5  = rows[i].nm_rek5;
                    cnilai    = angka(rows[i].nilai);
                    no        = i + 1 ;    
                      if (i>0) {
                        var csql = csql+","+"('"+a1+"','"+ckdrek5+"','"+cnm_rek5+"','"+cnilai+"','"+k+"',' ','"+ckd_trans+"')";
                      } else {
                        var csql = "values('"+a1+"','"+ckdrek5+"','"+cnm_rek5+"','"+cnilai+"','"+k+"',' ','"+ckd_trans+"')";         
                        }                                             
                      }  
                      $(document).ready(function(){
                        //alert(csql);
                        //exit();
                        $.ajax({
                          type: "POST",   
                          dataType : 'json',                 
                          data: ({no:a1,sql:csql,no_hide:a1_hide}),
                          url: '<?php echo base_url(); ?>/index.php/spmc/update_dsimpan_potspm',
                          success:function(data){                        
                            status = data.pesan;   
                             if (status=='1'){
                              //$("#loading").dialog('close');
                              $("#no_spm_hide").attr("value",a1);
                              lcstatus='edit';
                              alert('Data Tersimpan..!!');
                              data_no_spp();
                            } else{ 
                              //$("#loading").dialog('close');
                              lcstatus='tambah';
                              alert('Detail Gagal Tersimpan...!!!');
                            }                                             
                          }
                        });
                        }); 
                      }
                }
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                    }
            });
            });
        }
    
          
    function hhapus(){        
            var spm = document.getElementById("no_spm_hide").value;
            var urll= '<?php echo base_url(); ?>/index.php/spmc/hapus_spm';                       
          if (spm !=''){
        var del=confirm('Anda yakin akan menghapus SPM '+spm+'  ?');
        if  (del==true){
          $(document).ready(function(){
                    $.post(urll,({no:spm,spp:no_spp}),function(data){
                    status = data;
                    });
                    });
        }
        } 
                alert('Berhasil Dihapus');
    }
        
    
    function load_sum_spm(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:no_spp}),
            url:"<?php echo base_url(); ?>index.php/spmc/load_sum_spm",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rekspm").attr("value",n['rekspm']);
                    $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }         
        
    function load_sum_pot(){                
    var spm = document.getElementById('no_spm').value;              
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({spm:spm}),
            url       : "<?php echo base_url(); ?>index.php/spmc/load_sum_pot",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
           
                    $("#totalrekpajak").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
     
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
     }
     
     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });
     }
     
     function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
     }
     
     
     function tombol(st,st_val){  
        if (ctot_spm>1){
            $('#save').hide();
            $('#ctkx').hide();
            $('#del').hide();
            $('#save-pot').hide();
            $('#del-pot').hide();
            $('#edit-ket').hide();
            $('#save-pottaspen').hide();
            document.getElementById("p1").innerHTML="NOMOR SPP INI DOUBLE !!";
         }else{
            
        if (st==1){
            $('#save').hide();
            $('#del').hide();
            $('#save-pot').hide();
            $('#del-pot').hide();
            $('#edit-ket').show();
            document.getElementById("p1").innerHTML="Sudah di Buat SP2D!!";
         }else{            
         if (st_val==1){
            $('#save').hide();
            $('#del').hide();
            $('#save-pot').hide();
            $('#del-pot').hide();
            $('#edit-ket').show();
            document.getElementById("p1").innerHTML="Berkas SPM Disetujui!!";
         }else if (st_val==2){
            $('#save').hide();
            $('#del').hide();
            $('#save-pot').hide();
            $('#del-pot').hide();
            $('#edit-ket').show();
            document.getElementById("p1").innerHTML="Berkas SPM Ditunda!!";
         }else if (st_val==3){
            $('#save').hide();
            $('#del').hide();
            $('#save-pot').hide();
            $('#del-pot').hide();
            $('#edit-ket').show();
            document.getElementById("p1").innerHTML="Berkas SPM Dibatalkan!!";
         }else{            
             $('#save').show();
             $('#del').show();
             $('#save-pot').show();
             $('#del-pot').show();
             $('#edit-ket').hide();
            document.getElementById("p1").innerHTML="";
         }
      }
    }
  }
    
    function tombolnew(){  
     $('#save').show();
     $('#del').show();
   $('#save-pot').show();
     $('#del-pot').show();
   $('#edit-ket').hide();
    }
     
    function openWindow( url ){
    var kode  = $("#cspm").combogrid("getValue") ;
    var no    = kode.split("/").join("123456789");
    var ttd   = $("#ttd1").combogrid("getValue") ;
    var ttd1  = ttd.split(" ").join("123456789");
    var ttd_2   = $("#ttd2").combogrid("getValue") ;
    var ttd2  = ttd_2.split(" ").join("123456789");
    var ttd_3   = $("#ttd3").combogrid("getValue") ;
    var ttd3  = ttd_3.split(" ").join("123456789");
    var ttd_4   ='';
    var ttd4  = ttd_4.split(" ").join("123456789");
    var tanpa   = document.getElementById('tanpa_tanggal').checked; 
    var baris   = document.getElementById("baris").value;
    if ( tanpa == false ){
           tanpa=0;
        }else{
           tanpa=1;
        }
    if(ttd==''){
      alert("Pilih Bendahara Pengeluaran Terlebih Dahulu!");
      exit();
    }
    if(ttd_2==''){
      alert("Pilih PPTK Terlebih Dahulu!");
      exit();
    }
    if(ttd_3==''){
      alert("Pilih Pengguna Anggaran Terlebih Dahulu!");
      exit();
    }

    window.open(url+'/'+no+'/'+skpd+'/'+jns+'/'+ttd1+'/'+ttd2+'/'+ttd3+'/'+ttd4+'/'+tanpa+'/'+baris, '_blank');
        window.focus();
        }
    
    
    
    function cek(){
        var lcno = document.getElementById('no_spm').value;
            if ( lcno !='' ) {
               section3();  
               $("#totalrekpajak").attr("value",0);  
               $("#nilairekpajak").attr("value",0);  
               tampil_potongan();          
               load_sum_pot();
               $("#rekpajak").combogrid("setValue",'');
               $("#nmrekpajak").attr("value",'');
               
            } else {
                alert('Nomor SPM Tidak Boleh kosong')
                document.getElementById('no_spm').focus();
                exit();
            }
    }    
    
    
    function append_save() {
      var no_spm_pot      = document.getElementById('no_spm').value;
            $('#dgpajak').datagrid('selectAll');
            var rows        = $('#dgpajak').datagrid('getSelections');
            jgrid     = rows.length ; 
            var kd_trans    = $("#rektrans").combogrid("getValue") ;
            var rek_pajak    = $("#rekpajak").combogrid("getValue") ;
            var nm_rek_pajak = document.getElementById("nmrekpajak").value ;
            var nilai_pajak  = document.getElementById("nilairekpajak").value ;
            var nil_pajak    = angka(nilai_pajak);
            var dinas        = document.getElementById('dn').value;
            var vnospm       = document.getElementById('no_spm').value;
            var cket         = '0' ;
            var jumlah_pajak = document.getElementById('totalrekpajak').value ;   
                jumlah_pajak = angka(jumlah_pajak);        
            if(no_spm_pot==''){
        alert("Isi No SPM Terlebih Dahulu...!!!");
                exit();
      }
      if(kd_trans==''){
        alert("Isi Rekening Transaksi Terlebih Dahulu...!!!");
                exit();
      }
            if ( rek_pajak == '' ){
                alert("Isi Rekening Pajak Terlebih Dahulu...!!!");
                exit();
                }
            
            if ( nilai_pajak == 0 ){
                alert("Isi Nilai Terlebih Dahulu...!!!");
                exit();
                }
            
            pidx = jgrid + 1 ;

            $('#dgpajak').edatagrid('appendRow',{kd_rek5:rek_pajak,kd_trans:kd_trans,nm_rek5:nm_rek_pajak,nilai:nilai_pajak,id:pidx});

            $("#rekpajak").combogrid("setValue",'');
            $("#nmrekpajak").attr("value",'');
            $("#nilairekpajak").attr("value",0);
            jumlah_pajak = jumlah_pajak + nil_pajak ;
            
            
      var data = $('#dgpajak').datagrid('getData');
            var rows = data.rows;
            var jumlah_pajak = 0;
            
            for (i=0; i < rows.length; i++) {
                jumlah_pajak+=angka(rows[i].nilai);
            }

            $("#totalrekpajak").attr('value',number_format(jumlah_pajak,2,'.',','));
            validate_rekening();
    }


    function validate_rekening() {
           $('#dgpajak').datagrid('selectAll');
           var rows = $('#dgpajak').datagrid('getSelections');
           frek  = '' ;
           rek5  = '' ;
           for ( var p=0; p < rows.length; p++ ) { 
           rek5 = rows[p].kd_rek5;                                       
           if ( p > 0 ){   
                  frek = frek+','+rek5;
              } else {
                  frek = rek5;
              }
           }
           
           $(function(){
           $('#rekpajak').combogrid({  
                   panelWidth  : 700,  
                   idField     : 'kd_rek5',  
                   textField   : 'kd_rek5',  
                   mode        : 'remote',
                   url         : '<?php echo base_url(); ?>index.php/spmc/rek_pot', 
                   queryParams :({kdrek:frek}), 
                   columns:[[  
                       {field:'kd_rek5',title:'Kode Rekening',width:100},  
                       {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nmrekpajak").attr("value",rowData.nm_rek5);
                   }  
                   });
                   });
          $('#dgpajak').datagrid('unselectAll');         
    }
    
    
        function hapus_detail(){
        
        var vnospm        = document.getElementById('no_spm').value;
        var dinas         = document.getElementById('dn').value;
        
        var rows          = $('#dgpajak').edatagrid('getSelected');
        var ctotalpotspm  = document.getElementById('totalrekpajak').value ;
        
        bkdrek            = rows.kd_rek5;
        bnilai            = rows.nilai;
        
        var idx = $('#dgpajak').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+bkdrek+'  Nilai :  '+bnilai+' ?');
        $('#dgpajak').datagrid('deleteRow',idx);
        $('#dgpajak').datagrid('unselectAll');
        alert('Data Telah Terhapus..!!');
        
        if ( tny == true ) {

            
             
           
             
             ctotalpotspm = angka(ctotalpotspm) - angka(bnilai) ;
             $("#totalrekpajak").attr("Value",number_format(ctotalpotspm,2,'.',','));
             validate_rekening();
             }     
        }
        
        
        
    function tampil_potongan () {
            var vnospm = document.getElementById('no_spm').value ;
            $(function(){
      $('#dgpajak').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spmc/pot',
                queryParams    : ({ spm:vnospm }),
                idField       : 'id',
                toolbar       : "#toolbar",              
                rownumbers    : "true", 
                fitColumns    : true,
                autoRowHeight : "false",
                singleSelect  : "true",
                nowrap        : "true",
            columns       :
                     [[
                        {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                        {field:'kd_trans',title:'Rek. Trans',width:100,align:'left'},     
                        {field:'kd_rek5',title:'Rekening',width:100,align:'left'},      
                        {field:'nm_rek5',title:'Nama Rekening',width:317},
                        {field:'nilai',title:'Nilai',width:100,align:"right"},
                        {field:'hapus',title:'Hapus',width:100,align:"center",
                        formatter:function(value,rec){ 
                        return "<button class='button-merah' onclick='javascript:hapus_detail();'>Hapus</button>";
                        }
                        }
              ]]  
                  });
        });
    }


        //copy
        function detail_taspen(){
            var jns_spp = document.getElementById('jns_beban').value; 
            var nospp = $("#nospp").combogrid("getValue");
            if(jns_spp!='4'){
                alert('Khusus Jenis Beban Gaji (TASPEN)');
                return;
            }
            
            if(nospp==""){
                alert('Pilih No SPP terlebih dahulu');
                return;
            }    
            
            
            $(function(){
          $('#dgpajak').edatagrid({
            url: '<?php echo base_url(); ?>/index.php/spmc/select_pot_taspen',
                    queryParams    : ({ spp:nospp }),
                    idField        : 'id',
                    toolbar        : "#toolbar",              
                    rownumbers     : "true", 
                    fitColumns     : true,
                    autoRowHeight  : "true",
                    singleSelect   : false,
                     onLoadSuccess : function(data){                      
                     },
                    onSelect:function(rowIndex,rowData){
                        kd          = rowIndex ;  
                        kd_trans    = rowData.kd_trans ;
                        kd_rek5     = rowData.kd_rek5 ;
                        nm_rek5     = rowData.nm_rek5 ;
                        nilai       = rowData.nilai ;                                                               
                    },
                     columns:[[
                            {field:'id',title:'id',width:100,align:'left',hidden:'true'}, 
                            {field:'kd_trans',title:'Rek. Trans',width:100,align:'left'},     
                            {field:'kd_rek5',title:'Rekening',width:100,align:'left'},      
                            {field:'nm_rek5',title:'Nama Rekening',width:317},
                            {field:'nilai',title:'Nilai',width:100,align:"right"},
                            {field:'hapus',title:'Hapus',width:100,align:"center",
                            formatter:function(value,rec){ 
                            return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                            }
                        }
            ]]  
          });
        });
            setTimeout(function(){total_pajak();}, 1000);
        }


 function total_pajak(){
        $('#dgpajak').edatagrid('reload');  
        var data = $('#dgpajak').datagrid('getData');
        var rows = data.rows;
        var jumlah_pajak = 0;
        for (i=0; i < rows.length; i++) {
            jumlah_pajak+=angka(rows[i].nilai);
        }    
        $("#totalrekpajak").attr('value',number_format(jumlah_pajak,2,'.',','));     
 }   


function inputnomor(){    
        var nomorspm = document.getElementById('no_spm').value;
        $("#spm_pot").attr("value",nomorspm);
     }



function validate_rek_trans(no_spp) {
        var nospp_pot = document.getElementById('nospp1').value;
    //alert(nospp_pot);
           $(function(){
         $('#rektrans').combogrid({  
          panelWidth:600,  
          idField:'kd_rek5',  
          textField:'kd_rek5',  
          mode:'remote',
                    url  : '<?php echo base_url(); ?>index.php/spmc/rek_pot_trans', 
                   queryParams :({nospp_pot:nospp_pot}), 

          columns:[[  
            {field:'kd_rek5',title:'NIP',width:200},  
            {field:'nm_rek5',title:'Nama',width:400}    
          ]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmrektrans").attr("value",rowData.nm_rek5);
                    rekkkk = rowData.kd_rek5;                    
                    }  
        });
         });
           
     
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
<div id="accordion">
<h3><a href="#" id="section1" onclick="javascript:$('#spm').edatagrid('reload')">List SPM</a></h3>
    <div>
   <p align="right">         
        <button class="button" onclick="javascript:kosong();section2();"><i class="fa fa-tambah"></i> Tambah</button>
        <button class="button-cerah" onclick="javascript:cari();"><i class="fa fa-cari"></i> Cari</button>              
        <input type="text" value="" class="input" style="display: inline;" id="txtcari"/>
        <table id="spm" title="List SPM" style="width:1024px;height:450px;" >  
        </table>
    </p> 
  <!--   <p>
        <table border="0" width="37%">
            <tr>
                <td colspan="2">Ket Warna:</td>
            </tr>
            <tr>
                <td>Biru</td>
                <td>: Sudah Dibuat SP2D</td>
            </tr>
            <tr>
                <td>Kuning</td>
                <td>: Berkas Sedang Diproses</td>
            </tr>
            <tr>
                <td>Putih</td>
                <td>: Berkas Dalam Antrian</td>
            </tr>
            <tr>
                <td>Merah</td>
                <td>: Berkas Dibatalkan</td>
            </tr>
            <tr>
                <td>Orange</td>
                <td>: Data SPP Double</td>
            </tr>
        </table>
    </p> -->
    </div>
    
<h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')" >Input SPM</a></h3>
   <div  style="height: 350px;">   
  
<p id="pcek" style="font-size: x-large;color: red;"></p>   
<p id="p1" style="font-size: x-large;color: red;"></p>


<table border='1' width="100%" style="font-size:11px; border-color: white; border-radius: 5px" cellspacing="10px" cellpadding="10px" >
 
<tr style="">
   <td width="8%" style="" >No SPM</td>
   <td width="42%" style="">&nbsp;<input disabled type="text"  class="input" name="no_spm" id="no_spm" style="width:300px;" onkeyup="this.value=this.value.toUpperCase(); javascript:inputnomor();"/><input type="hidden" name="no_spm_hide" id="no_spm_hide" onclick="javascript:select();"  style="width:300px;"/></td>
   <td width="8%" style="">Tgl SPM </td>
   <td width="42%" style="">&nbsp;&nbsp;<input id="dd" name="dd" class="input" type="text" style="width:300px;" /><input id="dd_spm" name="dd_spm" type="hidden" /></td>
 </tr>
 <tr style="" >   
   <td width="8%" style="">No SPP</td>
   <td width="42%" style="">&nbsp;<input id="nospp" name="nospp" style="width:310px;" />
     <input type="hidden" name="nospp1" id="nospp1" /></td>
   <td width="8%" style="">Tgl SPP </td>
   <td width="42%" style="">&nbsp;&nbsp;<input id="tgl_spp" name="tgl_spp" type="text" readonly="true" style="width:300px; border-style: none;" /></td>   
    </tr>
 <tr style="">
   <td width='8%' style="">SKPD</td>
   <td width="42%" style="" >     
      &nbsp;<input id="dn" name="dn" class="input" style="width:300px" readonly="true"/></td> 
   <td width='8%' style="">Bulan</td>
   <td width="42%" style="" ><select  class="select" name="kebutuhan_bulan" id="kebutuhan_bulan" style="width:300px;" >
     <option value="">...Pilih Kebutuhan Bulan... </option>
     <option value="1" >1 | Januari</option>
     <option value="2">2 | Februari</option>
     <option value="3">3 | Maret</option>
     <option value="4">4 | April</option>
     <option value="5">5 | Mei</option>
     <option value="6">6 | Juni</option>
     <option value="7">7 | Juli</option>
     <option value="8">8 | Agustus</option>
     <option value="9">9 | September</option>
     <option value="10">10 | Oktober</option>
     <option value="11">11 | November</option>
     <option value="12">12 | Desember</option>
   </select></td> 
 </tr>
 <tr style="">
   <td width='8%' style="">&nbsp;</td>
   <td width="42%" colspan="3" style=""><input name="nmskpd" id="nmskpd" style="border: 0; width: 400px"  readonly="true"></td>
 </tr>
 <tr style="">
   <td width='8%' style="">No SPD</td>
   <td width="42%" style="">&nbsp;<input id="sp" name="sp" class="input" style="width:300px" readonly="true"/></td>
   <td width='8%' style="">Rekanan</td>
   <td width="42%" style=""><textarea id="rekanan" class="textarea" name="rekanan" style="margin: 5px 0px 10px 5px; width: 300px; height: 79px" readonly="true" > </textarea></td>
 </tr>
 
 <tr style="">
   <td style="">Beban</td>
   <td style=""><select class="select" name="jns_beban" id="jns_beban" style="width:300px;" >
     <option value="">...Pilih Jenis Beban... </option>
     <option value="1">UP</option>
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS GAJI</option>
     <option value="6">LS Barang Jasa</option>
     <option value="7">GU NIHIL</option>
      <td width="8%" style="" >BANK</td>
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px">&nbsp;<input style="width:300px"  type="text" name="bank1" id="bank1" />
    &nbsp;<input type ="input" readonly="true" style="border:hidden;width:300px;" id="nama_bank" name="nama_bank" /></td>
 </tr>
 <tr style="">
   <td width='8%'  style="">Jenis</td>
   <td width='42%' style="">&nbsp;<input id="cc" name="dept" style="width: 310px;" value=" Pilih Jenis Beban" ></td>
   <td style=""> &nbsp;</td>
   <td style=""> &nbsp;</td>
 </tr>
 <tr style="">
   <td width='8%' style="">NPWP</td>
   <td width="40%" style="">&nbsp;<input type="text" class="input" name="npwp" id="npwp" value="" style="width:300px;"/></td>
   <td width='8%' style="">Rekening</td>
   <td width='31%' style="">&nbsp;<input type="text" class="input" name="rekening" id="rekening"  value="" style="width:300px;" /></td>
 </tr>       
 
            
            
             <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
               <td style="border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style: hidden;" >Ketentuan</td>
               <td colspan="3" style="border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style: hidden;"><textarea style="margin: 5px 0px 10px 5px; width: 300px; height: 79px;" class="textarea" name="ketentuan" id="ketentuan" ></textarea></td>
             </tr>
             
             <tr style="border-bottom:black; border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td colspan="4" align="right" style="border-bottom:black; border-spacing: 3px;padding:3px 3px 3px 3px;">

                <!-- <a id="edit-ket" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:edit_keterangan();">Edit</a> -->
                <button id="save" class="button-biru" onclick="javascript:simpan_spm();"><i class="fa fa-simpan"></i> Simpan</button>
                <button id="del" class="button-merah" onclick="javascript:hhapus();javascript:section1();"><i class="fa fa-hapus"></i> Hapus</button>
                <button class="button-cerah" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>
                <button class="button-cerah" onclick="javascript:cetak();"><i class="fa fa-print"></i> Cetak</button>            
            </tr>
            
    </table>
    <table id="dg" title=" Detail SPM" style="width:1024px;height:250%;">  
    </table>
     
        
        <table border='0' width="100%">
            
            <tr>
                <td width='400px'></td>
                <td width='220px'></td>
                <td width='240px'></td>
            </tr>
            
            <tr>
                <td></td>
                <td align='right'><B>Total</B></td>
                <td align="right"><input class="right" type="text" name="rekspm" id="rekspm"  style="width:200px" align="right" readonly="true" >
                    <input class="right" type="hidden" name="rekspm1" id="rekspm1"  style="width:100px" align="right" readonly="true" >
                </td>
            </tr>
        </table>
    </p>
  
  <!--dari sini -->
  
   <fieldset style="border-radius: 10px">
       <table border='0' width="100%" style="font-size:11px"> 
           <tr>
                <td>No. SPM</td>
                <td>:</td>
                <td><input type="text" id="spm_pot"  class="input" name="spm_pot" style="width:250px;"/></td>
           </tr>
       <tr>
                <td>Rekening Transaksi</td>
                <td>:</td>
                <td><input type="text" id="rektrans" class="input"   name="rektrans" style="width:250px;"/></td>
                <td><input type="text" id="nmrektrans" name="nmrektrans" style="width:400px;border:0px;"/></td>
           </tr>
           <tr>
                <td>Rekening Potongan</td>
                <td>:</td>
                <td><input type="text" id="rekpajak"   name="rekpajak" style="width:260px;"/></td>
                <td><input type="text" id="nmrekpajak" name="nmrekpajak" placeholder="Ketik di kolom jika tidak ada" style="width:400px;border:0px;"/></td>
           </tr>
           <tr>
                <td align="left">Nilai</td>
                <td>:</td>
                <td><input type="text" class="input" id="nilairekpajak" name="nilairekpajak" style="width:250px;text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td></td>
           </tr>      
           <tr>
             <td colspan="4" align="center" > 
                <button hidden id="save-pot" class="button" onclick="javascript:append_save();"><i class="fa fa-tambah"></i> Tambah Potongan</button>
                <button id="save-pottaspen" class="button" onclick="javascript:detail_taspen();"><i class="fa fa-tambah"></i> Potongan Khusus (Gaji Bulanan) Taspen</button>
             </td>
           </tr>
           
       </table>
       </fieldset>
       
      &nbsp;&nbsp; 
       
       <table id="dgpajak" title="List Potongan" style="width:1024px;height:300px;">  
       </table>   
       
       <table border='0' width="100%" style="font-size:11px;"> 
           <tr>
                <td width='50%'></td>
                <td width='20%' align="right">Total</td>
                <td width='30%' align="right"><input type="text" id="totalrekpajak" name="totalrekpajak" style="width:250px;text-align:right;"/></td>
           </tr>
       </table>
  
  
  <!--Sampai sini -->
    </fieldset>
    </div>

</div>
</div> 

<div id="dialog-modal" title="CETAK SPM" >
    <p class="validateTips">SILAHKAN PILIH SPM</p> 
    <fieldset>
    <table>

        <tr>
            <td width="110px">NO SPM:</td>
            <td><input id="cspm" name="cspm" style="width: 250px;" disabled />&nbsp; &nbsp; &nbsp; <input type="checkbox" id="tanpa_tanggal"> Tanpa Tanggal</td>
        </tr>
        <tr>
            <td width="110px">Bend. Pengeluaran:</td>
            <td><input id="ttd1" name="ttd1" style="width: 250px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd1" name="nmttd1" style="width: 250px;border:0" /></td>
        </tr>
        <tr>
            <td width="110px">PPTK/PPK:</td>
            <td><input id="ttd2" name="ttd2" style="width: 250px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd2" name="nmttd2" style="width: 250px;border:0" /></td>
        </tr>
        <tr>
            <td width="110px">PA:</td>
            <td><input id="ttd3" name="ttd3" style="width: 250px;" />  &nbsp; &nbsp; &nbsp;  <input id="nmttd3" name="nmttd3" style="width: 250px;border:0" /></td>
        </tr>
        <tr>
            <td width="110px"></td>
            <td>Ketik di kolom tanda tangan jika <u>nama tidak muncul</u> dalam list</td>
        </tr>
       
    </table>  
 
  <br/>
  <table style="border-collapse:collapse" align="center" border="1" width="98%" >
  <tr>

  <td colspan="8" align="center"><b>SPM</b></td>
  
  </tr>
  <tr>

  <td colspan="4" align="center" ><a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>cetak_spm/lampiran_spm/3');return false;">Lampiran SPM </a></td>    
    <td colspan="4" align="center" ><a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>cetak_spm/cetakspm/3');return false;">Cetak SPM </a></td>
   </tr>
  <tr>
  
    <td colspan="4" align="center" ><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>cetak_spm/lampiran_spm/0');return false;">Lampiran SPM  </a></td> 
    <td colspan="4" align="center" ><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>cetak_spm/cetakspm/0');return false;">Cetak SPM </a></td>
  
  </tr>
  <tr>
  <tr>

  <td colspan="8" align="center"><a HIDDEN class="easyui-linkbutton" iconCls="icon-download" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>/cetak_spm/cetak_spm/2');return false;">Save</a></td>
  </tr>

  <tr>
  <td colspan='5'> Baris SPM : &nbsp; <input type="number" id="baris" name="baris" style="width: 30px;border:0" value="15"/></td>
  </tr>
  </table>
  <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>
</body>
</html>