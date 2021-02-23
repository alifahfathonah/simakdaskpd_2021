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
        width: 700px;
        height: 70px;
        padding: 0.4em; 
    }   
    </style>
    
    <script type="text/javascript"> 
   
    var nl       = 0;
    var tnl      = 0;
    var idx      = 0;
    var tidx     = 0;
    var oldRek   = 0;
    var rek      = 0;
    var kode     = '';
    var pidx     = 0;  
    var frek     = '';             
    var rek5     = '';
    var edit     = '';
    var lcstatus = 'tambah';
    var kd_sub_skpd='';    
    var no_spp   = '';
    var tahun_anggaran = '<?php echo $this->session->userdata('pcThang'); ?>';
    var yy       = '';
                    
    $(document).ready(function(){
        $("#tagihhid").hide();
        $("#loading").hide();
        $("#taspenhid").hide();
        $('#kg').combogrid();
        $("#sp").combogrid();
            $("#accordion").accordion({
            height: 600
            });
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal" ).dialog({
            height: 400,
            width: 700,
            modal: true,
            autoOpen:false            
        });
        
        $('#q_minus')._propAttr('checked',false);
        
        $( "#dialog-modal-rek" ).dialog({
            height: 450,
            width: 1100,
            modal: true,
            autoOpen:false
        });
            $("#tagih").hide();
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
                   var m = date.getMonth()+1;
                    $("#kebutuhan_bulan").attr('value',m);
                    var yy = date.getFullYear();
                    cek_status_ang();
                    var tahunsekarang = date.getFullYear();
                    $("#tahunsekarang").attr("value",tahunsekarang);
                    
                }
            });     
            
            $('#tgl_mulai').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
                }
            });
            
            $('#tgl_akhir').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
                }
            });
            
            $('#tgl_ttd').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
                }
            });
            
            

            $('#rekanan').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/sppc/perusahaan',  
                    idField:'nmrekan',  
                    textField:'nmrekan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'nmrekan',title:'Perusahaan',width:40} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    $("#dir").attr("value",rowData.pimpinan);
                    $("#npwp").attr("value",rowData.npwp);
                    $("#alamat").attr("value",rowData.alamat);
                    
                    }   
                });

             $('#tglspd').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();
                    return y+'-'+m+'-'+d;
                }
            });
                
                $('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/sppc/load_spp',  
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
                
                $('#cc').combobox({
                    url:'<?php echo base_url(); ?>/index.php/sppc/load_jenis_beban',
                    valueField:'id',
                    textField:'text',
                    onSelect:function(rowIndex,rowData){
                    validate_tombol();
                    }
                });
                                
                $('#ttd1').combogrid({  
                    panelWidth:600,  
                    idField:'id_ttd',  
                    textField:'nip',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/sppc/load_ttd_cek/BK',  
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
                    url:'<?php echo base_url(); ?>index.php/sppc/load_ttd_cek/PPTK',  
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
                    url:'<?php echo base_url(); ?>index.php/sppc/load_ttd_pa_kpa/PX',  
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
                
                $('#notagih').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/sppc/load_no_penagihan',  
                    idField:'no_tagih',  
                    textField:'no_tagih',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'no_tagih',title:'No Penagihan',width:140},  
                           {field:'tgl_tagih',title:'Tanggal',width:140},
                           {field:'kd_skpd',title:'SKPD',width:140}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    var ststagih='1';
                    no_tagih = rowData.no_tagih;
                    $("#tgltagih").attr("value",rowData.tgl_tagih);
                    $("#nil").attr("value",rowData.nila);
                    $("#ni").attr("value",rowData.nil);
                    $("#ketentuan").attr("Value",rowData.ket);
                    $("#kontrak").attr("Value",rowData.kontrak);
                    $("#kg").combogrid("setValue",rowData.kegiatan);
                
                    detail_tagih(no_tagih);
                    $("#rektotal_ls").attr('value',rowData.nila);
                    $("#rektotal1_ls").attr('value',rowData.nil);
                    get_skpd();
                    }   
                });
                
                //copy   
                $('#tglgj').combogrid();
                
            $('#bank1').combogrid({  
                panelWidth:700,  
                url: '<?php echo base_url(); ?>index.php/spmc/config_bank2',  
                    idField:'kd_bank',  
                    textField:'kd_bank',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_bank',title:'Kd Bank',width:150},  
                           {field:'nama_bank',title:'Nama',width:500}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    $("#nama_bank").attr("value",rowData.nama_bank);
                    }   
                });
                 
            $('#npwp_combo').combogrid({  
                panelWidth:180,  
                url: '<?php echo base_url(); ?>/index.php/sppc/config_npwp',  
                    idField:'npwp',  
                    textField:'npwp',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'npwp',title:'NPWP',width:150}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    $("#npwp").attr("value",rowData.npwp);
                    }   
                });
                
            $('#rekening_combo').combogrid({  
                panelWidth:180,  
                url: '<?php echo base_url(); ?>/index.php/sppc/config_npwp',  
                    idField:'rekening',  
                    textField:'rekening',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'rekening',title:'rekening',width:150}
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    $("#rekening").attr("value",rowData.rekening);
                    }   
                });             
                 
                 
                 
                    $('#spp').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/sppc/load_spp',
                    idField:'id',            
                    rownumbers:"true", 
                    fitColumns:"true",
                    singleSelect:"true",
                    autoRowHeight:"false",
                    loadMsg:"Tunggu Sebentar....!!",
                    pagination:"true",
                    nowrap:"true",                       
                    columns:[[
                        {field:'no_spp',
                        title:'Nomor SPP',
                        width:40},
                        {field:'tgl_spp',
                        title:'Tanggal',
                        width:25},
                        {field:'kd_skpd',
                        title:'Nama SKPD',
                        width:25,
                        align:"left"},
                        {field:'keperluan',
                        title:'Keterangan',
                        width:140,
                        align:"left"}
                    ]],
                    onSelect:function(rowIndex,rowData){
                 
                    },
                    onDblClickRow:function(rowIndex,rowData){
                      no_spp   = rowData.no_spp;      
                      urut     = rowData.urut;         
                      kode     = rowData.kd_skpd;
                      sp       = rowData.no_spd;          
                      bl       = rowData.bulan;
                      tg       = rowData.tgl_spp;
                      jn       = rowData.jns_spp;
                      jns_bbn  = rowData.jns_beban;
                      kep      = rowData.keperluan;
                      np       = rowData.npwp;
                      rekan    = rowData.nmrekan;
                      bk       = rowData.bank;
                      ning     = rowData.no_rek;
                      status   = rowData.status;
                      kegi     = rowData.kd_kegiatan;
                      nm       = rowData.nm_kegiatan;
                      kprog    = rowData.kd_program;
                      nprog    = rowData.nm_program;
                      dir      = rowData.dir;
                      notagih  = rowData.no_tagih;
                      tgltagih = rowData.tgl_tagih;
                      sttagih  = rowData.sts_tagih;         
                      alamat  = rowData.alamat;         
                      kontrak  = rowData.kontrak;         
                      lanjut  = rowData.lanjut;         
                      tgl_mulai  = rowData.tgl_mulai;
                      tgl_spd  = rowData.tgl_spd;         
                      tgl_akhir  = rowData.tgl_akhir;  
                      tot_spp  = rowData.tot_spp_;
                      bidangg = rowData.bidang;
                      kd_sub_skpd= rowData.kd_sub_skpd;
                      $("#tglspd").datebox("setValue",tgl_spd);
                      $("#no_spd").combogrid("setValue",sp);
                      $("#sp").combogrid("setValue",sp);
                      $('#nomer_spd').attr('value',sp);
                      get(urut,no_spp,kode,sp,tg,bl,jn,kep,np,rekan,bk,ning,status,kegi,nm,kprog,nprog,dir,notagih,tgltagih,sttagih,alamat,kontrak,lanjut,tgl_mulai,tgl_akhir,jns_bbn,tot_spp);
              
                   
                      edit = 'T' ;
                      lcstatus = 'edit';
                      detail_trans_3();   
                      validate_kegiatan();
                      load_sum_spp();
                      kd_sub_skpd= rowData.kd_sub_skpd;
                                      
                      section2();
                        lcstatus = 'edit';
                      $("#status_taspen").attr("checked",false); 
                      cek_taspen();
                       
                    }
                });
         
         var jenis = 51;

            
                
                $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
            });
            
            
                $('#dgsppls').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"false",
                 columns:[[
                    {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},             
                    {field:'kdkegiatan',title:'Kegiatan',width:160,align:'left'},
                    {field:'kdrek5',title:'Rekening',width:70,align:'left'},
                    {field:'nmrek5',title:'Nama Rekening',width:280},
                    {field:'nilai1',title:'Nilai',width:140,align:'right'},
                    {field:'sumber',title:'Sumber',width:100,align:'center'},
                    {field:'hapus',title:'Hapus',width:50,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]]  
           }); 
            
           
           $('#rek_skpd').combogrid();
           
           
           $('#rek_kegi').combogrid({  
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Sub Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Sub Kegiatan',width:700}    
           ]]  
           });
           
           
           $('#rek_reke').combogrid({  
           panelWidth:700,  
           idField   :'kd_rek5',  
           textField :'kd_rek5',  
           mode      :'remote',
           columns   :[[  
               {field:'kd_rek5',title:'Kode Rekening',width:150},  
               {field:'nm_rek5',title:'Nama Rekening',width:700}    
           ]]  
           });
           
           $('#sumber_dn').combogrid({  
           panelWidth:200,  
           idField:'sumber_dana',  
           textField:'sumber_dana',  
           mode:'remote',                        
           columns:[[  
               {field:'sumber_dana',title:'Sumber Dana',width:180}
           ]] 
        });

        });
        
        
        
        
        function get_skpd(){
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                  $("#dn").attr("value",data.kd_skpd);
                  $("#nmskpd").attr("value",data.nm_skpd);
                  $("#rek_skpd").combogrid("setValue",data.kd_skpd);
                  $("#rek_nmskpd").attr("value",data.nm_skpd.toUpperCase());
                  kode = data.kd_skpd;
            }  
            });
        }
        
        
        function get_spp(){
          
          
            var xxsk = "";
            var yysk = "";
            
            var skpx   = document.getElementById('dn').value;
            var xxsk = skpx.substr(0,4);
            var yysk = skpx.substr(8,2);
            
            
            var year =  document.getElementById('tahunsekarang').value;
            var jenis_ls = document.getElementById('jns_beban').value;
            var jns ="";
            
          if(jenis_ls==4){
                if(xxsk=='4.08' && yysk != '00'){
                jns = "LS-GJ/DAU-T";
                }else{
                    jns = "LS-GJ";
                }
            }else{
                if(xxsk=='4.08' && yysk != '00'){   
                jns = "LS/DAU-T";
                }else{
                    jns = "LS";
                }
            }

            $.ajax({
                url:'<?php echo base_url(); ?>index.php/sppc/config_spp',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                    no_spp = data.nomor;
                    var inisial = no_spp + "/SPP/"+jns+"/"+kode+"/"+tahun_anggaran;
                    $("#no_spp").attr('disabled',true);
                    $("#no_spp").attr("value",inisial);
                    $("#dd_spp").attr("value",no_spp);
                }                                     
            });
        }
        
        function cek_status_ang(){
        var tgl_cek = $('#dd').datebox('getValue');      
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/sppc/cek_status_ang',
                data: ({tgl_cek:tgl_cek}),
                type: "POST",
                dataType:"json",                         
                success:function(data){
                $("#status_ang").attr("value",data.status_ang);
            }  
            });
        }
        
        function data_notagih(){
          $('#notagih').combogrid({url: '<?php echo base_url(); ?>/index.php/sppc/load_no_penagihan'});  
        }
        
        function detail_tagih(no_tagih){
        $(function(){
            $('#dgsppls').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sppc/select_data_tagih',
                queryParams    : ({ no:no_tagih }),
                 idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : false,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkdkegiatan = rowData.kdkegiatan ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai1 ;
                    tsumber     = rowData.sumber ;                                           
                },
                 columns:[[
                     {field:'idx',
                     title:'idx',
                     width:100,
                     align:'left',
                     hidden:'true'
                     },               
                     {field:'kdkegiatan',
                     title:'Kode Sub Kegiatan',
                     width:160,
                     align:'left'
                     },
                    {field:'kdrek5',
                     title:'Rekening',
                     width:70,
                     align:'left'
                     },
                    {field:'nmrek5',
                     title:'Nama Rekening',
                     width:280
                     },
                    {field:'nilai1',
                     title:'Nilai',
                     width:140,
                     align:'right'
                     },
                     {field:'sumber',
                     title:'Sumber',
                     width:100,
                     align:'right'
                     },
                    {field:'hapus',title:'Hapus',width:50,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]]  
            });
        });
        }
        //copy
        function detail_taspen(tglsppt,skpd){
        skpd = kode;    
        $(function(){
            $('#dgsppls').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sppc/select_data_taspen',
                queryParams    : ({ tgl:tglsppt,skpd:skpd }),
                 idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : false,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkdkegiatan = rowData.kdkegiatan ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai1 ;
                    tsumber     = rowData.sumber ;                                                               
                },
                 columns:[[
                     {field:'idx',
                     title:'idx',
                     width:100,
                     align:'left',
                     hidden:'true'
                     },               
                     {field:'kdkegiatan',
                     title:'Kode Sub Kegiatan',
                     width:160,
                     align:'left'
                     },
                    {field:'kdrek5',
                     title:'Rekening',
                     width:70,
                     align:'left'
                     },
                    {field:'nmrek5',
                     title:'Nama Rekening',
                     width:280
                     },
                    {field:'nilai1',
                     title:'Nilai',
                     width:140,
                     align:'right'
                     },
                     {field:'sumber',
                     title:'Sumber',
                     width:100,
                     align:'center'
                     },
                    {field:'hapus',title:'Hapus',width:50,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]]  
            });
        });
        }
        //copy



        function validate_kegiatan(){

            var kode_s = document.getElementById('dn').value;
            $(function(){
              $('#rek_kegi').combogrid({  
              panelWidth:700,  
              idField   :'kd_kegiatan',  
              textField :'kd_kegiatan',  
              mode      :'remote',
              url       :'<?php echo base_url(); ?>index.php/sppc/kegiatan_spp',  
              queryParams:({kdskpd:kode_s}), 
              columns   :[[  
               {field:'kd_kegiatan',title:'Kode Sub Kegiatan',width:150},  
               {field:'nm_kegiatan',title:'Nama Sub Kegiatan',width:700}    
               ]],
               onSelect:function(rowIndex,rowData){ 
               $("#nm_rek_kegi").attr("value",rowData.nm_kegiatan); 
               $("#rek_reke").combogrid("setValue",''); 
        
               data_rekening(); 
               
               }              
           });
           });
        }    
        
 
        function data_rekening(kegi){
           $('#dgsppls').datagrid('selectAll');
           var rows = $('#dgsppls').datagrid('getSelections');     
           frek  = '' ;
           rek5  = '' ;
           for ( var p=0; p < rows.length; p++ ) { 
           rek5 = "'"+rows[p].kdrek5+"'";                                       
           if ( p > 0 ){ 
                  frek = rek5+","+frek;
              } else {
                  frek = rek5;
              }
           }    
                kd_sub_skpd=kd_sub_skpd;
                var beban   = document.getElementById('jns_beban').value;
                var kode_s   = document.getElementById('dn').value  ;
                var kode_keg = kegi;
                var nospp    = document.getElementById('no_spp').value ;
                
                $(function(){
                  $('#rek_reke').combogrid({  
                  panelWidth:700,  
                  idField   :'kd_rek5',  
                  textField :'kd_rek5',  
                  mode      :'remote',
                  url       :'<?php echo base_url(); ?>index.php/sppc/load_rekening_sppls',  
                  queryParams:({kdkegiatan:kegi,kdrek:frek,kd_sub_skpd:kd_sub_skpd}), 
                  columns:[[  
                   {field:'kd_rek5',title:'Kode Rekening',width:150},  
                   {field:'nm_rek5',title:'Nama Rekening',width:700}    
                   ]],
                   onSelect:function(rowIndex,rowData){      
                           var koderek = rowData.kd_rek5 ;
                           var nmarek = rowData.nm_rek5 ;
                           $("#nm_rek_reke").attr("value",nmarek);
                           $("#sumber_dn").combogrid('setValue','');
                           $("#rek_nilai_ang_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_spp_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_ang_semp_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_spp_semp_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_ang_ubah_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_spp_ubah_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_sisa_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_sisa_semp_dana").attr("Value",number_format(0,2,'.',','));
                           $("#rek_nilai_sisa_ubah_dana").attr("Value",number_format(0,2,'.',','));                       
                           validasi_sumber_dana();                           
                             
                           if((koderek=="5110108")||(koderek=="5110105")){
                                $("#q_minus").attr('disabled',false);          
                           }else{
                                $('#q_minus')._propAttr('checked',false);
                                $("#q_minus").attr('disabled',true);                                
                           }                           
                            
                           $.ajax({
                                type     : "POST",
                                dataType : "json",   
                                data     : ({kegiatan:kode_keg,kdrek5:koderek,kd_skpd:kode_s,no_spp:nospp,kd_sub_skpd:kd_sub_skpd}), 
                                url      : '<?php echo base_url(); ?>index.php/sppc/jumlah_ang_spp',
                                success  : function(data){
                                      $.each(data, function(i,n){
                                        $("#rek_nilai_ang").attr("Value",n['nilai']);
                                        $("#rek_nilai_spp").attr("Value",n['nilai_spp_lalu']);
                                        $("#rek_nilai_ang_semp").attr("Value",n['nilai_sempurna']);
                                        $("#rek_nilai_spp_semp").attr("Value",n['nilai_spp_lalu']);
                                        $("#rek_nilai_ang_ubah").attr("Value",n['nilai_ubah']);
                                        $("#rek_nilai_spp_ubah").attr("Value",n['nilai_spp_lalu']);
                                        
                                        var n_ang  = n['nilai'] ;
                                        var n_ang_semp  = n['nilai_sempurna'] ;
                                        var n_ang_ubah  = n['nilai_ubah'] ;
                                        var n_spp  = n['nilai_spp_lalu'] ;
                                        var n_sisa = angka(n_ang) - angka(n_spp) ;
                                        var n_sisa_semp = angka(n_ang_semp) - angka(n_spp) ;
                                        var n_sisa_ubah = angka(n_ang_ubah) - angka(n_spp) ;
                                        $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
                                        $("#rek_nilai_sisa_semp").attr("Value",number_format(n_sisa_semp,2,'.',','));
                                        $("#rek_nilai_sisa_ubah").attr("Value",number_format(n_sisa_ubah,2,'.',','));
                                        
                                        var tgl_spd   = $('#tglspd').datebox('getValue');      
                                 $.ajax({
                                            type     : "POST",
                                            dataType : "json",   
                                            data     : ({kegiatan:kode_keg,kd_skpd:kode_s,tglspd:tgl_spd,kdrek5:koderek,beban:beban}), 
                                            url      : '<?php echo base_url(); ?>index.php/sppc/total_spd',
                                            success  : function(data){
                                                  $.each(data, function(i,n){
                                                    $("#total_spd").attr("Value",n['nilai']);
                                                    var n_totalspd  = n['nilai'] ;
                                                  
                                                });
                                            }                                     
                                       });
                                       
                               
                                 $.ajax({
                                            type     : "POST",
                                            dataType : "json",   
                                            data: ({giat:kode_keg,kode:kode_s}), 
                                            url      : '<?php echo base_url(); ?>index.php/sppc/load_total_trans_spd',
                                            success  : function(data){
                                                  $.each(data, function(i,n){
                                                    $("#nilai_spd_lalu").attr("Value",n['total']);
                                                    var n_spdlalu  = n['total'] ;
                                                    var total_spd = document.getElementById('total_spd').value;
                                                    var n_sisaspd = angka(total_spd) - angka(n_spdlalu) ;
                                                    $("#nilai_sisa_spd").attr("Value",number_format(n_sisaspd,2,'.',','));
                                                });
                                            }                                     
                                       });
                                    });
                                }                                     
                           });
                   }                
               });
               });
               $('#dgsppls').datagrid('unselectAll');
        }
         
        
    function validasi_sumber_dana(){
        
        var kode_keg = $('#rek_kegi').combogrid('getValue') ;
        var koderek = $('#rek_reke').combogrid('getValue') ;
        kd_sub_skpd=kd_sub_skpd;
        $(function(){
        $('#sumber_dn').combogrid({            
           panelWidth:200,  
           idField:'sumber_dana',  
           textField:'sumber_dana',  
           mode:'remote',      
           url:'<?php echo base_url(); ?>index.php/sppc/load_reksumber_dana',
           queryParams:({giat:kode_keg,kd:kode,rek:koderek,kd_sub_skpd:kd_sub_skpd}),                   
           columns:[[  
               {field:'sumber_dana',title:'Sumber Dana',width:180}
           ]],  
           onSelect:function(rowIndex,rowData){
              var parsumber = rowData.sumber_dana;    
              var vnilaidana = rowData.nilaidana;
              var vnilaidana_semp = rowData.nilaidana_semp;
              var vnilaidana_ubah = rowData.nilaidana_ubah;                                                                               
              var lalu_ubahspp = angka(document.getElementById('rek_nilai_spp_ubah').value);                 
              
              $("#rek_nilai_ang_dana").attr("Value",number_format(vnilaidana,2,'.',','));
              $("#rek_nilai_spp_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));
              $("#rek_nilai_ang_semp_dana").attr("Value",number_format(vnilaidana_semp,2,'.',','));
              $("#rek_nilai_spp_semp_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));
              $("#rek_nilai_ang_ubah_dana").attr("Value",number_format(vnilaidana_ubah,2,'.',','));
              $("#rek_nilai_spp_ubah_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));  
              
              var sisa_nil_dana = vnilaidana-lalu_ubahspp;
              var sisa_nil_semp_dana = vnilaidana_semp-lalu_ubahspp;
              var sisa_nil_ubah_dana = vnilaidana_ubah-lalu_ubahspp;
                              
              $("#rek_nilai_sisa_dana").attr("Value",number_format(sisa_nil_dana,2,'.',','));
              $("#rek_nilai_sisa_semp_dana").attr("Value",number_format(sisa_nil_semp_dana,2,'.',','));
              $("#rek_nilai_sisa_ubah_dana").attr("Value",number_format(sisa_nil_ubah_dana,2,'.',','));                           
           }  
       }); });
    }    
       

        
    function data_spd(beban,tanggal_spp){

           $(function(){
                $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/sppc/spd1_ag/'+beban+'/'+tanggal_spp,             
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,                    
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:70},  
                        {field:'tgl_spd2',title:'Tanggal',align:'left',width:30},
                        {field:'nilai',title:'Nilai',align:'right',width:40}                                 
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;
                    tglspd = rowData.tgl_spd;
                    $("#nomer_spd").attr('value', spd);
                    $("#tglspd").datebox("setValue",tglspd);
                    data_kegiatan(spd);                                                        
                    }    
                });
           });


                
        }
    
    
    function data_kegiatan(spd){
           $(function(){

                $('#kg').combogrid({  
                panelWidth:800,  
                url: '<?php echo base_url(); ?>/index.php/sppc/kegiatan_spd',  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    queryParams:({spd:spd}),
                    mode:'remote',  
                    fitColumns:true,                       
                    columns:[[  
                        {field:'kd_kegiatan',title:'Kode Sub Kegiatan',width:100},  
                        {field:'nm_kegiatan',title:'Nama',align:'left',width:250},
                        {field:'nm_skpd',title:'',align:'left',width:250}                                                    
                    ]],
                    onSelect:function(rowIndex,rowData){
                        kegi   = rowData.kd_kegiatan;
                        nmkegi = rowData.nm_kegiatan;     
                        bidang = rowData.kdbidang;         
                        prog = rowData.kd_program;
                        nmprog = rowData.nm_program;
                        kd_sub_skpd=rowData.kdbidang;
                        nilai= rowData.nilai; 
                        $("#kp").attr("value",rowData.kd_program);
                        $("#nm_kp").attr("value",rowData.nm_program);
                        $("#bidangg").attr("value",bidang);
                        $("#nm_kg").attr("value",rowData.nm_kegiatan);

                        data_rekening(kegi);                  
                                                      
                    }    
                });
           });
        }


        function detail1(){
        $(function(){
            var spp = document.getElementById('no_spp').value;            
            $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams:({spp:spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onLoadSuccess:function(data){                      
                      //load_sum_spp();                        
                    },
                onSelect:function(rowIndex,rowData){
                kd = rowIndex;                                               
                },                                        
                 columns:[[
                    {field:'ck',
                     title:'ck',
                     checkbox:true,
                     hidden:true},                     
                     {field:'kdsubkegiatan',
                     title:'Kode Sub Kegiatan',
                     width:150,
                     align:'left'
                    },
                    {field:'kdrek5',
                     title:'Rekening',
                     width:70,
                     align:'left'
                    },
                    {field:'nmrek5',
                     title:'Nama Rekening',
                     width:300
                    },
                    {field:'sisa',
                     title:'Sisa',
                     width:100,
                     align:'right'                   
                     },
                    {field:'nilai1',
                     title:'Nilai',
                     width:100,
                     align:'right',                    
                     editor:{type:"numberbox"                        
                            }
                     }
                ]]  
            });
        });
        }


     function get(urut,no_spp,kd_skpd,no_spd,tgl_spp,bulan,jns_spp,keperluan,npwp,rekanan,bank,rekening,status,giat,nmgiat,prog,nmprog,pim,notagih,tgltagih,ststagih,alamat,kontrak,lanjut,tgl_mulai,tgl_akhir,jns_bbn,tot_spp){
        
        $("#dd_spp").attr("value",urut);
        $("#no_spp").attr("value",no_spp);
        $("#no_spp_hide").attr("value",no_spp);
        $("#no_simpan").attr("value",no_spp);
        $("#dd").datebox("setValue",tgl_spp);
        $("#tgl_mulai").datebox("setValue",tgl_mulai);
        $("#tgl_akhir").datebox("setValue",tgl_akhir);
        $("#kebutuhan_bulan").attr("Value",bulan);
        $("#ketentuan").attr("Value",keperluan);
        $("#jns_beban").attr("Value",jns_spp);
        $("#npwp").attr("Value",npwp);
        $("#npwp_combo").combogrid("setValue",npwp);
        $("#sp").combogrid("setValue",no_spd);
        $("#rekanan").combogrid("setValue",rekanan);
        $("#dir").attr("Value",pim);
        $("#alamat").attr("Value",alamat);
        $("#kontrak").attr("Value",kontrak);
        $("#lanjut").attr("Value",lanjut);
        $("#bank1").combogrid("setValue",bank);
        $("#rekening").attr("Value",rekening);
        $("#rekening_combo").combogrid("setValue",rekening);
        $("#kg").combogrid("setValue",giat);
        $("#nm_kg").attr("Value",nmgiat);
        $("#kp").attr("setValue",prog);
        $("#nm_kp").attr("Value",nmprog);
        $("#notagih").combogrid("setValue",notagih);        
        $("#tgltagih").attr("Value",tgltagih);
        validate_jenis_edit(jns_bbn);
        validate_tombol();
        
                    
        $("#status").attr("checked",false);                  
        if (ststagih==1){            
            $("#status").attr("checked",true);
            $("#tagihhid").show();
            $("#nil").attr('value',number_format(tot_spp,2,'.',','));
        } else {
            $("#status").attr("checked",false);
            $("#tagihhid").hide();
            $("#nil").attr("value",'');
        }
         
         tombol(status);           
        }

    function cek_taspen(){
        $("#taspenhid").hide();
        $("#tglgj").combogrid("clear");
        $("#nilgj").attr("value",'');
        $("#nigj").attr("value",'');
        if (document.getElementById("status_taspen").checked == true){
            $("#taspenhid").show();
        }else{
            $("#taspenhid").hide();             
        }
    }
    //copy
    
    function kosong(){
         validate_kegiatan();  
        $("#no_spp").attr("value",'');
        $("#no_spp_hide").attr("value",'');
        $("#dd_spp").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#nomer_spd").attr("value",'');
        $("#sp").combogrid("setValue",'');
        $("#dd").datebox("setValue",'');
        $("#tgl_mulai").datebox("setValue",'');
        $("#tgl_akhir").datebox("setValue",'');
        $("#tglspd").datebox("setValue",'');
        $("#kebutuhan_bulan").attr("Value",'');
        $("#ketentuan").attr("Value",'');
        $("#jns_beban").attr("Value",'');
        $("#npwp").attr("Value",'');
        $("#rekanan").combogrid("setValue",'');
        $("#dir").attr("Value",'');
        $("#bank1").combogrid("setValue",'');
        $("#rekening").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#kg").combogrid("setValue",'');
        $("#nm_kg").attr("Value",'');
        $("#nama_bank").attr("Value",'');
        $("#kontrak").attr("Value",'');
        $("#lanjut").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#kp").attr("setValue",'');
        $("#nm_kp").attr("Value",'');
        document.getElementById("p1").innerHTML="";        
        $("#sp").combogrid("clear");
        $("#kg").combogrid("clear");
        $("#cc").combobox("setValue",'');
        $("#notagih").combogrid("clear");
     
        tombolnew();
        detail_kosong(); 

        var pidx  = 0   ;     
        edit      = 'F' ;
        data_notagih();
        $("#rektotal_ls").attr("Value",0);
        $("#rektotal1_ls").attr("Value",0);
        
        lcstatus = 'tambah';
        $("#tgltagih").attr("value",'');
        //$("#nmskpd").attr("value",'');
        $("#nil").attr("value",'');
        $("#ni").attr("value",'');
        $("#status").attr("checked",false);                  
        $("#tagih").hide();
        $("#status_taspen").attr("checked",false);
        $("#nilgj").attr('value',number_format(0,2,'.',',')); 
        cek_taspen();              
        
        }


    
    function getRowIndex(target){  
            var tr = $(target).closest('tr.datagrid-row');  
            return parseInt(tr.attr('datagrid-row-index'));  
        }  

 
    function cetak(){
        var nom=document.getElementById("no_spp").value;
        $("#cspp").combogrid("setValue",nom);
        $("#dialog-modal").dialog('open');
    } 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 
    
    
    function keluar_rek(){
        $("#dialog-modal-rek").dialog('close');
        $("#dgsppls").datagrid("unselectAll");

        $("#rek_nilai").attr("Value",0);
        $("#rek_nilai_ang").attr("Value",0);
        $("#rek_nilai_spp").attr("Value",0);
        $("#rek_nilai_sisa").attr("Value",0);
        $("#rek_nilai_ang_semp").attr("Value",0);
        $("#rek_nilai_spp_semp").attr("Value",0);
        $("#rek_nilai_sisa_semp").attr("Value",0);
        $("#rek_nilai_ang_ubah").attr("Value",0);
        $("#rek_nilai_spp_ubah").attr("Value",0);
        $("#rek_nilai_sisa_ubah").attr("Value",0);
        
        $("#rek_nilai_ang_dana").attr("Value",0);
        $("#rek_nilai_spp_dana").attr("Value",0);
        $("#rek_nilai_sisa_dana").attr("Value",0);
        $("#rek_nilai_ang_semp_dana").attr("Value",0);
        $("#rek_nilai_spp_semp_dana").attr("Value",0);
        $("#rek_nilai_sisa_semp_dana").attr("Value",0);
        $("#rek_nilai_ang_ubah_dana").attr("Value",0);
        $("#rek_nilai_spp_ubah_dana").attr("Value",0);
        $("#rek_nilai_sisa_ubah_dana").attr("Value",0);
    }     
    
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spp').edatagrid({
           url: '<?php echo base_url(); ?>/index.php/sppc/load_spp',
         queryParams:({cari:kriteria})
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

     
    function hsimpan(){        
        var a       = (document.getElementById('no_spp').value).split(" ").join("");
        var a_hide  = document.getElementById('no_spp_hide').value;
        var a_dd    = document.getElementById('dd_spp').value;        
        var b       = $('#dd').datebox('getValue');      
        var c       = document.getElementById('jns_beban').value; 
        var d       = document.getElementById('kebutuhan_bulan').value;
        var e       = document.getElementById('ketentuan').value;
        var f       = $("#rekanan").combogrid("getValue") ; 
        var f1      = document.getElementById('dir').value;
        var g       = $("#bank1").combogrid("getValue") ; 
        var h       = $("#npwp_combo").combogrid("getValue") ; 
        var i       = $("#rekening_combo").combogrid("getValue");
        var j       = document.getElementById('nmskpd').value.trim();
        var k1      = document.getElementById('rektotal1_ls').value;
        var l       = document.getElementById('nm_kg').value;
        var m       = document.getElementById('kp').value;
        var n       = document.getElementById('nm_kp').value;
        var alamat       = document.getElementById('alamat').value;
        var kontrak      = document.getElementById('kontrak').value;
        var lanjut       = document.getElementById('lanjut').value;
        var tgl_mulai    = $('#tgl_mulai').datebox('getValue');      
        var tgl_akhir    = $('#tgl_akhir').datebox('getValue');      
        var o       = document.getElementById('status').checked; 
        var jenis   = $("#cc").combobox("getValue") ; 
        var z       = document.getElementById('nomer_spd').value;
        var spd       = document.getElementById('nomer_spd').value;
        var y       = $("#kg").combogrid("getValue") ; 
        var k       = angka(k1);       
        var p = $('#notagih').combogrid('getValue');
        var q = document.getElementById('tgltagih').value;
        var kd_bidang = document.getElementById('bidangg').value;
        var tglsppt = $('#tglgj').combogrid("getValue") ; 

        var xx = y.substr(0,21);
        if ( o == false ){
           o=0;
           var p ='';
           var q ='';
        }else{
            o=1;
        }
        if(o==1 && p==''){
            alert("Nomor Penagihan Tidak Boleh Kosong...!!!") ;
            exit();
        }
        if ( a == '' ){
            alert("Isi Nomor SPP Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( z == '' ){
            alert("Isi Nomor SPD Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( h == '' ){
            alert("Isi NPWP Terlebih Dahulu...!!!") ;
            exit();
        }
        
     
        
        if ( e == '' ){
            alert("Isi Keperluan Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( g == '' ){
            alert("Isi Bank Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( b == '' ){
            alert("Isi Tanggal Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( kode == '' ){
            alert("Isi SKPD Terlebih Dahulu...!!!") ;
            exit();
        }
        
        var tahun_input = b.substring(0, 4);
        //khusus januri
        if (tahun_input != tahun_anggaran){
            alert('Tahun tidak sama dengan tahun Anggaran');
            exit();
        }       
        
        if ( c == '' ){
            alert("Isi Beban Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( d == '' ){
            alert("Isi Kebutuhan Bulan Terlebih Dahulu...!!!") ;
            exit();
        }
        
        if ( y == '' ){
            alert("Isi Kode Sub Kegiatan Terlebih Dahulu...!!!") ;
            exit();
        }
    
        
        if(jenis.trim()=='Pilih Jenis Beban'){
            alert('Jenis Belum Dipilih');
            return;
        }
        if ( l == '' ){
            alert("Pilih Kegiatan Terlebih Dahulu...!!!") ;
            exit();
        }
        if ( c == 6 && jenis== 3 && kontrak=='' ){
            alert("Nomor Kontrak Harus Diisi...!!!") ;
            exit();
        }       
        if ( c == 6 && jenis== 2 && f=='' ){
            alert("Rekanan Harus Diisi...!!!") ;
            exit();
        }
        if ( c == 6 && jenis== 3 && f=='' ){
            alert("Rekanan Harus Diisi...!!!") ;
            exit();
        }       
        if ( c == 6 && jenis== 3 && f1=='' ){
            alert("Direktur/Nama Rekanan Harus Diisi...!!!") ;
            exit();
        }
        
        if ( c == 6 && jenis== 3 && p=='' ){
            alert("Nomor Penagihan Tidak Boleh Kosong...!!!") ;
            exit();
        }
        var lenket = e.length;
        if ( lenket>1000 ){
            alert('Keterangan Tidak boleh lebih dari 1000 karakter');
            exit();
        }
        
        
        //Cek Datagrid
        var ctot_det=0;
         $('#dgsppls').datagrid('selectAll');
            var rows = $('#dgsppls').datagrid('getSelections');           
            for(var x=0;x<rows.length;x++){
            cnilai3     = angka(rows[x].nilai1);
            ctot_det = ctot_det + cnilai3;
            } 
            
        if(nilgj==''){                
        if (k != ctot_det){
            alert('Nilai Rincian tidak sama dengan Total, Silakan Refresh kembali halaman ini!');
            exit();
        }
        }
        
        if (ctot_det==0){
            alert('Rincian Rekening Kosong');
            exit();
        }
        
        kd_sub_skpd=kd_sub_skpd;
        if(lcstatus == 'tambah'){
        $(document).ready(function(){
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhspp',field:'no_spp'}),
                    url: '<?php echo base_url(); ?>/index.php/sppc/cek_simpan_spp',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        alert("Nomor Telah Dipakai!");
                        document.getElementById("nomor").focus();
                        exit();
                        } 
                        if(status_cek==0){
                        
                        if ( kode == '' ){
                            alert("Isi SKPD Terlebih Dahulu...!!!") ;
                            return;
                        }

                        if ( j == '' ){
                            alert("Nama SKPD Kosong...!!!") ;
                            return;
                        }
                        
        //---------
        
            lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, jns_beban, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,     kd_sub_kegiatan,  nm_sub_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 , alamat, kontrak, lanjut, tgl_mulai, tgl_akhir, kd_sub_skpd)"; 
            lcvalues = "('"+a+"', '"+kode+"', '"+e+"',   '"+d+"', '"+spd+"', '"+c+"', '"+jenis+"', '"+g+"', '"+f+"',  '"+i+"', '"+h+"', '"+j+"',  '"+b+"', '0',    '<?php echo $this->session->userdata('pcNama'); ?>',           '',            '"+k+"',  '',           '"+y+"',   '"+l+"',      '"+m+"',     '"+n+"',     '"+f1+"',  '"+p+"',     '"+q+"',    '"+o+"',    '',       '',        '',        '',        '',      '',      '',      '"+alamat+"', '"+kontrak+"','"+lanjut+"','"+tgl_mulai+"','"+tgl_akhir+"','"+kd_sub_skpd+"')";
            
            
            $(document).ready(function(){

                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/sppc/simpan_tukd',
                    //copy
                    data     : ({tabel:'trhspp',kolom:lcinsert,nilai:lcvalues,cid:'no_spp',jns_spp:c,jns_beban:jenis,tglsppt:tglsppt,lcid:a,tagih:p, kd_sub_skpd:kd_sub_skpd,
                        rekanan:f,alamat:alamat,npwp:h, pimpinan:f1, bank:g, rekening:i}),
                    dataType : "json",
                    beforeSend:function(xhr){
                    $("#loading").show();
                        },
                    success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
                                  alert('Data Sudah Ada..!!');
                                  exit();
                               } else {
                                   $('#dgsppls').datagrid('selectAll');
                                    var rows = $('#dgsppls').datagrid('getSelections');
                                    
                                    for(var i=0;i<rows.length;i++){            
                                        cidx      = rows[i].idx;
                                        ckdgiat   = rows[i].kdkegiatan;
                                        ckdrek    = rows[i].kdrek5;
                                        cnmrek    = rows[i].nmrek5;
                                        cnilai    = angka(rows[i].nilai1);
                                        cgiat     = ckdgiat.substr(0,21);
                                        csumber    = rows[i].sumber;
                                        no        = i + 1 ;    
                                            if (i>0) {
                                                csql = csql+","+"('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdgiat+"','"+spd+"','<?php echo $this->session->userdata('pcNama'); ?>','"+csumber+"','"+kd_sub_skpd+"')";
                                            } else {
                                                csql = "values('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdgiat+"','"+spd+"','<?php echo $this->session->userdata('pcNama'); ?>','"+csumber+"','"+kd_sub_skpd+"')";                 
                                                }                                             
                                            }                         
                                            $(document).ready(function(){
                                               
                                                $.ajax({
                                                    type: "POST",   
                                                    dataType : 'json',                 
                                                    data: ({no:a,sql:csql, kd_sub_skpd:kd_sub_skpd}),
                                                    url: '<?php echo base_url(); ?>/index.php/sppc/dsimpan_ag_ls',
                                                    success:function(data){                        
                                                        status = data.pesan;   
                                                         if (status=='1'){
                                                            $("#loading").hide();
                                                            alert('Data Berhasil Tersimpan...!!!');
                                                            $("#no_spp_hide").attr("value",a);
                                                            lcstatus='edit';
                                                            section1();
                                                        } else{ 
                                                            $("#loading").hide();
                                                            lcstatus='tambah';
                                                            alert('Detail Gagal Tersimpan...!!!');
                                                        }                                             
                                                    }
                                                });
                                                });            
                                            }
                    }
                });
            });   
           
        //----------
        
        }
        }
        });
        });
        
        
            
        } else { /*untuk edit*/

            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhspp',field:'no_spp'}),
                    url: '<?php echo base_url(); ?>/index.php/sppc/cek_simpan_spp',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && a!=a_hide){
                        alert("Nomor Telah Dipakai!");
                        exit();
                        } 
                        if(status_cek==0 || a==a_hide){
                     
            
            
        //---------
        lcquery = " UPDATE trhspp SET kd_skpd='"+kode+"', kd_sub_skpd='"+kd_sub_skpd+"', keperluan='"+e+"', bulan='"+d+"', no_spd='"+z+"', jns_spp='"+c+"',jns_beban='"+jenis+"', bank='"+g+"', nmrekan='"+f+"', no_rek='"+i+"', npwp='"+h+"', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', kd_sub_kegiatan='"+kegi+"', nm_sub_kegiatan='"+l+"', kd_program='"+m+"', nm_program='"+n+"', pimpinan='"+f1+"', no_tagih='"+p+"', tgl_tagih='"+q+"', sts_tagih='"+o+"', no_spp='"+a+"',alamat ='"+alamat+"', kontrak='"+kontrak+"',lanjut='"+lanjut+"',tgl_mulai='"+tgl_mulai+"',tgl_akhir='"+tgl_akhir+"' where no_spp='"+a_hide+"' AND kd_skpd='"+kode+"' "; 

            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/sppc/update_tukd_spp',
                data     : ({st_query:lcquery,tabel:'trhspp',cid:'no_spp',lcid:a,lcid_h:a_hide}),
                dataType : "json",
                beforeSend:function(xhr){
                    $("#loading").show();
                        },
                success  : function(data){
                           status=data ;
                                                        
                        if ( status=='1' ){
                            //alert("aaaa");
                            alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
                                   $('#dgsppls').datagrid('selectAll');
                                    var rows = $('#dgsppls').datagrid('getSelections');
                                    
                                    for(var i=0;i<rows.length;i++){            
                                        cidx      = rows[i].idx;
                                        ckdgiat   = rows[i].kdkegiatan;
                                        ckdrek    = rows[i].kdrek5;
                                        cnmrek    = rows[i].nmrek5;
                                        cnilai    = angka(rows[i].nilai1);
                                        cgiat     = ckdgiat.substr(0.21);
                                        csumber    = rows[i].sumber; 
                                        
                                        no        = i + 1 ;    
                                            if (i>0) {
                                                csql = csql+","+"('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdgiat+"','"+spd+"','<?php echo $this->session->userdata('pcNama'); ?>','"+csumber+"','"+kd_sub_skpd+"')";
                                            } else {
                                                csql = "values('"+a+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdgiat+"','"+spd+"','<?php echo $this->session->userdata('pcNama'); ?>','"+csumber+"','"+kd_sub_skpd+"')";                 
                                                }                                             
                                            }                         
                                            $(document).ready(function(){
                                                //alert(csql);
                                                //exit();
                                                $.ajax({
                                                    type: "POST",   
                                                    dataType : 'json',                 
                                                    data: ({no:a,sql:csql,no_hide:a_hide}),
                                                    url: '<?php echo base_url(); ?>/index.php/sppc/dsimpan_ag_edit_ls',
                                                    success:function(data){                        
                                                        status = data.pesan;   
                                                         if (status=='1'){
                                                            $("#loading").hide();
                                                            alert('Data Berhasil Tersimpan...!!!');
                                                            $("#no_spp_hide").attr("value",a);
                                                            lcstatus='edit';
                                                            data_notagih();
                                                        } else{ 
                                                            $("#loading").hide();
                                                            lcstatus='tambah';
                                                            alert('Detail Gagal Tersimpan...!!!');
                                                        }                                             
                                                    }
                                                });
                                                });            
                                            }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                        
                    }
            });
            });
        
        //-----------
                }
            }
        });
     });
        
        }
        
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
        
        
    
    function kembali(){
        $('#kem').click();
    }                
    

    function load_sum_spp(){                
        var nospp = document.getElementById('no_spp').value; 
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({spp:nospp}),
            url:"<?php echo base_url(); ?>index.php/sppc/load_sum_spp",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal_ls").attr('value',number_format(n['rektotal'],2,'.',','));
                    $("#rektotal1_ls").attr('value',number_format(n['rektotal'],2,'.',','));
                });
            }
         });
        });
    }

    
    
    function tombol(st){ 
    if (st==1){
    $('#save').hide();
    $('#del').hide();
    document.getElementById("p1").innerHTML="Sudah di Buat SPM...!!!";
    } else {
     $('#save').show();
     $('#del').show();
    document.getElementById("p1").innerHTML="";
    }
    }
    
    
    function tombolnew(){  
     $('#save').show();
     $('#del').show();
     $('#det').show();     
     $('#sav').show();
     $('#dele').show();
    }
        

   
   function cetak_spp( url ){
        var spasi  = document.getElementById('spasi').value;
        var nomer   = $("#cspp").combogrid('getValue');
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

        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_1+'/'+ttd_2+'/'+ttd_4+'/'+spasi+'/'+tanpa, '_blank');
        window.focus();
        }
    

    function cetak_spp_2( url )
        {
        var spasi  = document.getElementById('spasi').value; 
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

       // window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanda, '_blank');
        window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanpa+'/'+spasi, '_blank');
        window.focus();
        }    
   
    
    function validate_jenis_edit(){
        var beban   = document.getElementById('jns_beban').value;
        var jenis   = $("#cc").combobox('getValue');
        $('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/sppc/load_jenis_beban/'+beban,
        });
        $('#sp').combogrid({url:'<?php echo base_url(); ?>/index.php/sppc/spd1_ag/'+beban,
        });
        if (beban=='6'){
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('enable');
            $("#tgl_akhir").datebox('enable');
            $("#rekanan").combogrid('enable');
            $("#dir").attr('disabled',false);
            $("#alamat").attr('disabled',false);
            $("#kontrak").attr('disabled',false);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
        } else {
            
            if ((beban=='4') && (jenis=='9')){
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('disable');
            $("#tgl_akhir").datebox('disable');
            $("#rekanan").combogrid('enable');
            $("#dir").attr('disabled',false);
            $("#alamat").attr('disabled',false);
            $("#kontrak").attr('disabled',true);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
            }else{
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('disable');
            $("#tgl_akhir").datebox('disable');
            $("#rekanan").combogrid('disable');
            $("#dir").attr('disabled',true);
            $("#alamat").attr('disabled',true);
            $("#kontrak").attr('disabled',true);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
            }
        
        }
        $('#cc').combobox('setValue', jns_bbn);
    }
    function validate_jenis(){
        var tanggal_spp = $('#dd').datebox('getValue');
        if(tanggal_spp == ''){
            alert("Isi Tanggal SPP Terlebih Dahulu!");
            $("#jns_beban").attr("Value",'');
            exit();
        }
        var beban   = document.getElementById('jns_beban').value;
        var jenis   = $("#cc").combobox('getValue');
        $('#cc').combobox({url:'<?php echo base_url(); ?>/index.php/sppc/load_jenis_beban/'+beban,
        });

        if (beban=='6'){
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('enable');
            $("#tgl_akhir").datebox('enable');
            $("#rekanan").combogrid('enable');
            $("#dir").attr('disabled',false);
            $("#alamat").attr('disabled',false);
            $("#kontrak").attr('disabled',false);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
        } else {
            if ((beban=='4') && (jenis=='9')){
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('disable');
            $("#tgl_akhir").datebox('disable');
            $("#rekanan").combogrid('enable');
            $("#dir").attr('disabled',false);
            $("#alamat").attr('disabled',false);
            $("#kontrak").attr('disabled',true);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
            }else{
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('disable');
            $("#tgl_akhir").datebox('disable');
            $("#rekanan").combogrid('disable');
            $("#dir").attr('disabled',true);
            $("#alamat").attr('disabled',true);
            $("#kontrak").attr('disabled',true);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
            }
        }
        data_spd(beban,tanggal_spp);
        get_spp();
    
    } 
    
     function validate_tombol(){
        var beban   = document.getElementById('jns_beban').value;
        var jenis   = $("#cc").combobox('getValue');
        if ((beban=='6') && (jenis=='3')){
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('enable');
            $("#tgl_akhir").datebox('enable');
            $("#rekanan").combogrid('enable');
            $("#dir").attr('disabled',false);
            $("#alamat").attr('disabled',false);
            $("#kontrak").attr('disabled',false);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
        } 
        else if ((beban=='6') && (jenis=='2')){
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('disable');
            $("#tgl_akhir").datebox('disable');
            $("#rekanan").combogrid('enable');
            $("#dir").attr('disabled',false);
            $("#alamat").attr('disabled',false);
            $("#kontrak").attr('disabled',true);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
        } else if ((beban=='4') && (jenis=='9')){
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('disable');
            $("#tgl_akhir").datebox('disable');
            $("#rekanan").combogrid('enable');
            $("#dir").attr('disabled',false);
            $("#alamat").attr('disabled',false);
            $("#kontrak").attr('disabled',true);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
        } 
        else {
            $("#npwp").attr('disabled',false);
            $("#tgl_mulai").datebox('disable');
            $("#tgl_akhir").datebox('disable');
            $("#rekanan").combogrid('disable');
            $("#dir").attr('disabled',true);
            $("#alamat").attr('disabled',true);
            $("#kontrak").attr('disabled',true);
            $("#bank1").combogrid('enable');
            $("#rekening").attr('disabled',false);
        }
    }
    function runEffect() {
        var selectedEffect = 'explode';            
        var options = {};                      
        var status=document.getElementById('status').checked;
        if(status==true){
            $("#tagihhid").show();
        }else{
            $("#tagihhid").hide();
            $("#notagih").combogrid("setValue",'');
            $("#tgltagih").attr("value",'');
            $("#nil").attr("value",'');
            $("#ni").attr("value",'');
        }

    };        
    
    //copy
    function runEffect_taspen() {
        var selectedEffect = 'explode';            
        var options = {};
        var status=document.getElementById('status_taspen').checked;                      
        if(status==true){
            $("#taspenhid").show();
        }else{
            $("#taspenhid").hide();
            $("#tglgj").attr("value",'');
            $("#nilgj").attr("value",'');
            $("#nigj").attr("value",'');            
        }       
        loadrek_taspen();
    };    
    
    function loadrek_taspen(){
       $('#tglgj').combogrid({  
                panelWidth:620,  
                url: '<?php echo base_url(); ?>/index.php/sppc/load_taspen',  
                    idField:'tgl_spp',  
                    textField:'tgl_spp',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'tgl_spp',title:'Tanggal',width:75,align:'center'},
                           {field:'kd_skpd',title:'SKPD',width:75,align:'center'},                           
                           {field:'nila',title:'Total Gaji',width:130,align:'right'},
                           {field:'ket',title:'KET',width:320,align:'left'}
                       ]],

                    onSelect:function(rowIndex,rowData){
                            var ststagih='1';
                            tgl_spp = rowData.tgl_spp;
                            var mtgl_spp = new Date(tgl_spp);
                            var xth = tgl_spp.substr(0,4);
                            
                            
                            $("#tahunsekarang").attr("value",xth);
                            $("#dd").datebox("setValue",tgl_spp);
                            $("#nilgj").attr("value",rowData.nila);
                            $("#nigj").attr("value",rowData.nil);
                            $("#ketentuan").attr("Value",rowData.ket);
                            $("#jns_beban").attr("Value",'4');
                            $("#kebutuhan_bulan").attr('value', mtgl_spp.getMonth()+1);
                            cek_status_ang();
                            $("#bank1").combogrid("setValue",'05');                    
                            $('#lanjut').attr("value",'2');
                       
                            validate_jenis();
                            $("#cc").combobox("setValue",'1');
                   
                            $("#rektotal_ls").attr('value',rowData.nila);
                            $("#rektotal1_ls").attr('value',rowData.nil);
                            get_skpd();
                            detail_taspen(tgl_spp,kode);
                    } 
            });


    }
    
    //copy
    
    function detail_trans_3(){
        $(function(){
            $('#dgsppls').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams    : ({ spp:no_spp }),
                 idField       : 'idx',
                 toolbar       : "#toolbar",              
                 rownumbers    : "true", 
                 fitColumns    : true,
                 autoRowHeight : "false",
                 singleSelect  : "true",
                 nowrap        : "true",
                 onLoadSuccess : function(data){                      
                 },
                onSelect:function(rowIndex,rowData){
                    
                    kd          = rowIndex ;  
                    idx         =  rowData.idx ;
                    tkdkegiatan = rowData.kdkegiatan ;
                    tkdrek5     = rowData.kdrek5 ;
                    tnmrek5     = rowData.nmrek5 ;
                    tnilai1     = rowData.nilai1 ;
                    tsumber     = rowData.sumber ;                                           
                },
                 columns:[[
                     {field:'idx',
                     title:'idx',
                     width:100,
                     align:'left',
                     hidden:'true'
                     },               
                     {field:'kdkegiatan',
                     title:'Kegiatan',
                     width:160,
                     align:'left'
                     },
                    {field:'kdrek5',
                     title:'Rekening',
                     width:70,
                     align:'left'
                     },
                    {field:'nmrek5',
                     title:'Nama Rekening',
                     width:280
                     },
                    {field:'nilai1',
                     title:'Nilai',
                     width:140,
                     align:'right'
                     },
                     {field:'sumber',
                     title:'Sumber',
                     width:100,
                     align:'center'
                     },
                    {field:'hapus',title:'Hapus',width:50,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]]  
            });
        });


        }

        
        function detail_kosong(){
            
        var no_spp = '' ; 
        $(function(){
            $('#dgsppls').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sppc/select_data1',
                queryParams:({ spp:no_spp }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:true,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 onLoadSuccess:function(data){   
                 },
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx ;                                           
                },
                 columns:[[
                     {field:'idx',
                     title:'idx',
                     width:100,
                     align:'left',
                     hidden:'true'
                     },               
                     {field:'kdkegiatan',
                     title:'Kode',
                     width:160,
                     align:'left'
                     },
                    {field:'kdrek5',
                     title:'Rekening',
                     width:70,
                     align:'left'
                     },
                    {field:'nmrek5',
                     title:'Nama Rekening',
                     width:280
                     },
                    {field:'nilai1',
                     title:'Nilai',
                     width:140,
                     align:'right'
                     },
                     {field:'sumber',
                     title:'Sumber',
                     width:100,
                     align:'center'
                     },
                    {field:'hapus',title:'Hapus',width:50,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
                ]]  
            });
        });
        }
        
        
        function tambah(){
           
           var cek_kegi  = $("#kg").combogrid('getValue');
           if (cek_kegi == '') {
                alert('Isi Kode Sub Kegiatan Terlebih Dahulu....!!!') ;
                exit() ;
           } 

           $("#dialog-modal-rek").dialog('open'); 
           $("#rek_skpd").combogrid("disable");
           $("#rek_kegi").combogrid("disable");
           $("#rek_kegi").combogrid("setValue",'');
           $("#nm_rek_kegi").attr("Value",'');
           $("#rek_reke").combogrid("setValue",'');
           $("#nm_rek_reke").attr("Value",'');
           $("#sumber_dn").combogrid("setValue",'');
           
           var kegi_tmb    = $("#kg").combogrid('getValue') ;
           var nm_kegi_tmb = document.getElementById('nm_kg').value ;
           
           $("#rek_kegi").combogrid("setValue",kegi_tmb);
           $("#nm_rek_kegi").attr("Value",nm_kegi_tmb);
          
           $("#total_spd").attr("Value",0);
           $("#nilai_spd_lalu").attr("Value",0);
           $("#nilai_sisa_spd").attr("Value",0);
           $("#rek_nilai").attr("Value",0);
           $("#rek_nilai_ang").attr("Value",0);
           $("#rek_nilai_spp").attr("Value",0);
           $("#rek_nilai_sisa").attr("Value",0);
           $("#rek_nilai_ang_semp").attr("Value",0);
           $("#rek_nilai_spp_semp").attr("Value",0);
           $("#rek_nilai_sisa_semp").attr("Value",0);
           $("#rek_nilai_ang_ubah").attr("Value",0);
           $("#rek_nilai_spp_ubah").attr("Value",0);
           $("#rek_nilai_sisa_ubah").attr("Value",0);
           
          $("#rek_nilai_ang_dana").attr("Value",0);
          $("#rek_nilai_spp_dana").attr("Value",0);
          $("#rek_nilai_sisa_dana").attr("Value",0);
          $("#rek_nilai_ang_semp_dana").attr("Value",0);
          $("#rek_nilai_spp_semp_dana").attr("Value",0);
          $("#rek_nilai_sisa_semp_dana").attr("Value",0);
          $("#rek_nilai_ang_ubah_dana").attr("Value",0);
          $("#rek_nilai_spp_ubah_dana").attr("Value",0);
          $("#rek_nilai_sisa_ubah_dana").attr("Value",0);
        }
        
       
       function append_save() {
        
            $('#dgsppls').datagrid('selectAll');
            var rows  = $('#dgsppls').datagrid('getSelections') ;
                jgrid = rows.length ;
        
            var jumtotal  = document.getElementById('rektotal_ls').value ;
                jumtotal  = angka(jumtotal);
            var vrek_skpd = $('#rek_skpd').combobox('getValue');
            var vrek_kegi = $('#rek_kegi').combobox('getValue');            
            var vrek_reke = $('#rek_reke').combobox('getValue');
            var vsumber_dn = $('#sumber_dn').combobox('getValue');
            var cnil      = document.getElementById('rek_nilai').value;
            var cnilai    = cnil;                                                    
            var cnil_sisa_spd   = angka(document.getElementById('nilai_sisa_spd').value) ;
            var cnil_sisa   = angka(document.getElementById('rek_nilai_sisa').value) ;
            var cnil_sisa_semp   = angka(document.getElementById('rek_nilai_sisa_semp').value) ;
            var cnil_sisa_ubah   = angka(document.getElementById('rek_nilai_sisa_ubah').value) ;
            var cnil_sisa_dana   = angka(document.getElementById('rek_nilai_sisa_dana').value) ;
            var cnil_sisa_semp_dana   = angka(document.getElementById('rek_nilai_sisa_semp_dana').value) ;
            var cnil_sisa_ubah_dana   = angka(document.getElementById('rek_nilai_sisa_ubah_dana').value) ;
            var cnil_input  = angka(document.getElementById('rek_nilai').value) ;
            var status_ang  = document.getElementById('status_ang').value ;
            var beban_gj   = document.getElementById('jns_beban').value;
            var tot_input =  angka(document.getElementById('rektotal1_ls').value);
                akumulasi = cnil_input+tot_input;
                            
            if ($('#q_minus').attr('checked')) {                                 
                cnilai_ = -1 * cnil_input;
                cnilai = number_format(cnilai_,2,'.',',');
                
                cnil_ = -1 * cnil_input;
                cnil = number_format(cnil_,2,'.',',');
            }
            
        

            if(vsumber_dn==''){
                alert('Pilih Sumber Dana Dahulu') ;
                 exit();
            }
            
            if ((status_ang=='') && (beban_gj != '4')){
                 alert('Pilih Tanggal Dahulu') ;
                 exit();
            }
            
              if ((akumulasi > cnil_sisa_spd) && (beban_gj != '4')){
             alert('Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
             exit();
            }
            
            if (cnil_input == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            if ((status_ang=='Perubahan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
                 if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_semp)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            //sumber dana
            if ((status_ang=='Perubahan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
                 if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_semp_dana)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp_dana)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_dana)&& (beban_gj != '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            //gaji
             if ((akumulasi > cnil_sisa_spd) && (beban_gj == '4')){
             alert('Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!') ;
             exit();
            }
            
            if ((status_ang=='Perubahan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_semp)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
                
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            
            //sumber dana
            if ((status_ang=='Perubahan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_semp_dana)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
                
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp_dana)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_dana)&& (beban_gj == '4')){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            var vnm_rek_reke = document.getElementById('nm_rek_reke').value;
            
            if ( edit == 'F' ){
                pidx = pidx + 1 ;
                }
                
            if ( edit == 'T' ){
                pidx = jgrid ;
                pidx = pidx + 1 ;
                }

       
            $('#dgsppls').datagrid('selectAll');
            var rows = $('#dgsppls').datagrid('getSelections');           
            for(var p=0;p<rows.length;p++){
                  idx        = p;
                  ckdgiat    = rows[p].kdkegiatan;                                    
                  crek       = rows[p].kdrek5;
                  nilai      = rows[p].nilai1;
                  
                  
                  if(ckdgiat==vrek_kegi && crek==vrek_reke){
                        $('#dgsppls').edatagrid('deleteRow',idx);
                        nlai=angka(nilai)+angka(cnil);
                        $('#dgsppls').edatagrid('appendRow',{kdkegiatan:vrek_kegi,kdrek5:vrek_reke,nmrek5:vnm_rek_reke,nilai1:number_format(nlai,2,'.',','),sumber:vsumber_dn,idx:pidx});
                        
                        jumtotal = jumtotal + angka(cnil) ;
                        $("#rektotal_ls").attr('value',number_format(jumtotal,2,'.',','));
                        $("#rektotal1_ls").attr('value',number_format(jumtotal,2,'.',','));
                        $("#dialog-modal-rek").dialog('close'); 
                        exit();
                  }
            }
            $('#dgsppls').edatagrid('appendRow',{kdkegiatan:vrek_kegi,kdrek5:vrek_reke,nmrek5:vnm_rek_reke,nilai1:cnilai,sumber:vsumber_dn,idx:pidx});
            $("#dialog-modal-rek").dialog('close'); 
            
            jumtotal = jumtotal + angka(cnil) ;
            $("#rektotal_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#rektotal1_ls").attr('value',number_format(jumtotal,2,'.',','));
            $("#dgsppls").datagrid("unselectAll");
            
       }
       
       
       function hapus_detail(){
        
        var a          = document.getElementById('no_spp').value;
        var rows       = $('#dgsppls').edatagrid('getSelected');
        var ctotalspp  = document.getElementById('rektotal_ls').value ;
        
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        bbukti      = '';
      
        ctotalspp   = angka(ctotalspp) - angka(bnilai) ;
        
        var idx = $('#dgsppls').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Rekening : '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#dgsppls').datagrid('deleteRow',idx);     
            $('#dgsppls').datagrid('unselectAll');
            $("#rektotal_ls").attr("Value",number_format(ctotalspp,2,'.',','));
            $("#rektotal1_ls").attr("Value",number_format(ctotalspp,2,'.',','));
              
             var urll = '<?php  echo base_url(); ?>index.php/sppc/hapus_dspp';
             $(document).ready(function(){
             $.post(urll,({cnospp:a,ckdgiat:bkdkegiatan,ckdrek:bkdrek,cnobukti:bbukti}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             });    
        }     
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
<h3><a href="#" id="section1" onclick="javascript:$('#spp').edatagrid('reload')">List SPP</a></h3>
    
    <div style="height:600px;">
    <p align="right">         
        <button class="button" onclick="javascript:section2();kosong();"><i class="fa fa-tambah"></i>Tambah</button>        
        <button class="button-cerah" onclick="javascript:cari();"><i class="fa fa-cari"></i>Cari</button>
        <input type="text"  value="" class="input" style="display: inline;" id="txtcari"/>
        <table id="spp" title="List SPP" style="width:1024px;height:650px;" >  
        </table>
    </p> 
    </div>

<h3><a href="#" id="section2">Input SPP</a></h3>
   
   <div  style="height:620px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>

   <fieldset style="width:1024px;height:950px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            
   <table border='0' style="font-size:12px; border-style: hidden;"   >
   <tr >
                <td  colspan="5"><b>P E N A G I H A N </b><input type="checkbox" id="status"  onclick="javascript:runEffect();"/>
                    <label id="tagihhid"><br>
                    No.Penagihan <br><input type="text" id="notagih" style="width: 250px;"/><br>
                    Tgl Penagihan <br><input disabled type="text" id="tgltagih" style="width: 140px;" class="input" />
                    Nilai <input disabled type="text" id="nil" style="width: 140px;" class="input" />
                    <input type="hidden" id="ni" style="width: 140px;" />
                    </label>
                </td>                
   </tr>
   <!--copy-->
   <tr >
                <td  colspan="5">
                    <b>G A J I &nbsp;(TASPEN)</b>
                    <input type="checkbox" id="status_taspen"  onclick="javascript:runEffect_taspen();cek_taspen();"/>
                    <label id="taspenhid">
                    
                    Tanggal <input type="text" id="tglgj" style="width: 140px;" />
                    Total <input type="text" id="nilgj" style="width: 140px; text-align: right; display: inline;" class="input" />
                    <input type="hidden" id="nigj" style="width: 140px;" />
                    </label>      
                </td>                
   </tr>
   <!--copy--> 
  <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 250px;" readonly="true";/></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
                <td style="border-bottom: double 1px red;border-top: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr> 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  >&nbsp;</td>
   <td width='42%' >&nbsp;</td>
   <td width='8%'  >&nbsp;</td>
   <td ></td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
 </tr>  

 <tr>   
   <td width="8%"  >No SPP</td>
   <td ><input disabled type="text" name="no_spp" id="no_spp" class="input" style="width:300px" onkeyup="this.value=this.value.toUpperCase()"/><input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px"/></td>
   <td >Tanggal</td>
   <td colspan="2" ><input style="width: 300px" id="dd" name="dd" type="text" /><input type="hidden" id="dd_spp" name="dd_spp" /></td>   
 </tr>

 <tr >
   <td width='8%' >SKPD</td>
   <td width="42%"  ><input id="dn" name="dn" class="input" disabled style="width:300px; border: 1;"/> <input type="hidden" id="bidangg" name="bidangg"/></td> 
   <td width='8%' >Bulan</td>
   <td width="31%" colspan="2" ><select style="width: 300px" class="select"  name="kebutuhan_bulan" id="kebutuhan_bulan" >
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
   </select>
    </td> 
 </tr>

 <tr >
   <td width='8%'  >&nbsp;</td>
   <td width='42%' ><input name="nmskpd" id="nmskpd" class="input"  style="width: 300px;"  readonly="true"></td>
   <td width='8%'  >Keperluan</td>
   <td width='31%' colspan="2" ><textarea name="ketentuan" class="textarea" id="ketentuan" style="width: 285px;" ></textarea></td>
 </tr>
 
 <tr >
   <td >Beban</td>
   <td ><select name="jns_beban" class="select" id="jns_beban" onchange="javascript:validate_jenis();" style="height: 27px; width:300px;">
     <option value="">...Pilih Beban... </option>     
     <option value="4">LS GAJI</option>
     <option value="6">LS Barang Jasa</option>
   </td>
   <td colspan ="3" >
 </tr>
 
 <tr >
   <td   >Jenis</td>
   <td  ><input id="cc" name="dept" style="width: 300px;" value=" Pilih Jenis Beban" ></td>
   <td>BANK<br>&nbsp;</td>
   <td colspan="2" ><input type="text" name="bank1" id="bank1" style="width: 300px;"/>
    <br><input type ="input" readonly="true" class="input" disabled id="nama_bank" name="nama_bank" style="width:286px" /><input type ="input" hidden readonly="true" id="tahunsekarang" name="tahunsekarang" style="width:250px" /></td>
 </tr>
 
 <tr>
   <td width='8%' >No SPD</td>
   <td ><input id="sp" name="sp" style="width:300px; " /><input style="width: 300px; margin: 100px" id="tglspd" name="tglspad" type="text" disabled /></td></td>
   <td width='8%' >Rekanan/Pimpinan. <br></td>
   <td colspan="2">
    <input id="rekanan" class="input" name="rekanan" style="width:300px"/><br>
    <input id="dir" class="input" name="dir" style="width:285px"/></td>
 </tr>
 
 <tr >
   <td >Sub Kegiatan</td>
   <td colspan="4" ><input id="kg" name="kg" style="width:300px" />
   <input type ="hidden" id="kp" name="kp" style="width:300px" />
    <input id="nm_kg" name="nm_kg" style="width:500px;border: 0;"/>
      <input type ="hidden" id="nm_kp" name="nm_kp" /></td>
 </tr>
 
 <tr >
   <td width='8%'  >NPWP</td>
   <td width='42%' ><input type="text" name="npwp_combo" id="npwp_combo" style="width:300px"/>
    <input hidden type="text"  name="npwp" id="npwp" value="" style="width:300px"/></td>
   <td width='8%'  >Rekening</td>
   <td width='31%' colspan="2"><input type="text" name="rekening_combo" id="rekening_combo"  style="width:300px"/>&nbsp;<input type="text" hidden name="rekening" id="rekening"  value="" style="width:286px"/></td>
 </tr>
 
 
 <tr >
   <td width='8%'>Alamat Perusahaan</td>
   <td  ><textarea name="alamat" class="textarea" id="alamat" style="width: 290px" ></textarea></td>
   <td width='8%' >Tanggal Mulai<br>Tanggal Akhir</td>
   <td colspan="2">
    <input id="tgl_mulai" name="tgl_mulai" style="width:300px"/>
    <br>
    <input id="tgl_akhir" name="tgl_akhir" style="width:300px"/>
 </tr>
 
 <tr >
   <td >Lanjut</td>
   <td > <select name="lanjut" id="lanjut" class="select" style="height: 27px; width: 300px;">
     <option value="">...Pilih ... </option>     
     <option value="1">IYA</option>
     <option value="2">TIDAK</option>
   </td>
   <td width="8%"  >Nomor Kontrak</td>
   <td colspan="2"><input style="width: 300px" class="input" type="text" name="kontrak" id="kontrak" />
   </td>
 
 </tr>

       <tr>
                <td colspan="5" align='center' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;" >
                <a id="save"> <button onclick="javascript:hsimpan();" class="button-biru"><i class="fa fa-simpan"></i> Simpan</button></a>
                <a id="del" ><button class="button-merah"  onclick="javascript:hhapus();javascript:section1();"><i class="fa fa-hapus"></i> Hapus</button></a>
                <a><button class="button-cerah" onclick="javascript:section1();"><i class="fa fa-kiri"></i>Kembali</button></a>
                <a><button class="button-cerah" onclick="javascript:cetak();"><i class="fa fa-cetak"></i>cetak</button></a></td>              
       </tr>
</table>

    
        <!------------------------------------------------------------------------------------------------------------------>
        
        <table id="dgsppls" title="Input Detail SPP" style="width:1024px;height:250%;" >  
        </table>
        
        <div id="toolbar" align="left">
            <a ><button class="button" onclick="javascript:tambah();"><i class="fa fa-tambah"></i> Tambah Rekening</button></a>
        </div>
  
        <table border='0' style="width:100%;height:5%;"> 
             <td width='39%'></td>
             <td width='15%'><input class="right" type="hidden" name="rektotal1_ls" id="rektotal1_ls"  style="width:140px" align="right" readonly="true" ></td>
             <td width='10%'><B>Total</B></td>
             <td width='31%'><input class="right" type="text" name="rektotal_ls" id="rektotal_ls"  style="background-color: #FFA07A; width:140px" align="right" readonly="true" ></td>
        </table>
        </fieldset>
        <!------------------------------------------------------------------------------------------------------------------>
   </div>

</div>
</div> 
            <div id="loading" class="loader1"> <div class="loader2"></div>
            </div>


<div id="dialog-modal-rek" title="Input Rekening">
    <p class="validateTips"></p>  
    <fieldset>
    <table align="center" style="width:100%;" border="0">
       
            <tr>
                <td width='17%'>SKPD</td>
                <td width='3%'>:</td>
                <td colspan="6" width='80%'><input id="rek_skpd" name="rek_skpd" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="rek_nmskpd" style="border:0;width: 350px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>KEGIATAN</td>
                <td>:</td>
                <td colspan="6"><input id="rek_kegi" name="rek_kegi" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_kegi" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>REKENING</td>
                <td>:</td>
                <td colspan="6"><input id="rek_reke" name="rek_reke" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_reke" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>           
            <tr>
                <td>SUMBER DANA</td>
                <td>:</td>
                <td colspan="6"><input id="sumber_dn" name="sumber_dn" style="width: 200px;" /></td>                            
            </tr>

            <tr>
                <td bgcolor="#99FF99">TOTAL SPD</td>
                <td bgcolor="#99FF99">:</td>
                <td bgcolor="#99FF99"><input type="text" id="total_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#99FF99">SPD TERPAKAI</td>
                <td bgcolor="#99FF99">:</td>
                <td bgcolor="#99FF99"><input type="text" id="nilai_spd_lalu" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#99FF99">SISA</td>
                <td bgcolor="#99FF99">:</td>
                <td bgcolor="#99FF99"><input type="text" id="nilai_sisa_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
            </tr>            
            <tr>
                <td bgcolor="#87CEFA">ANGGARAN MURNI</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA">SPP TERPAKAI</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>               
            </tr>
            
            <tr>
                <td bgcolor="#87CEFA">PENYEMPURNAAN</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA">SPP TERPAKAI</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            <tr>
                <td bgcolor="#87CEFA">PERUBAHAN</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA">SPP TERPAKAI</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            <tr>
                <td bgcolor="#FFA07A">SUMBER DANA MURNI</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A">SPP TERPAKAI</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            
            <tr>
                <td bgcolor="#FFA07A">PENYEMPURNAAN</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A">SPP TERPAKAI</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr>
            <tr>
                <td bgcolor="#FFA07A">PERUBAHAN</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A">SPP TERPAKAI</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr>
            <tr>
                <td>NILAI</td>
                <td>:</td>
                <td><input type="text" id="rek_nilai" class="input" style="width: 196px; display: inline; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/>
                    &nbsp;Minus <input hidden id="q_minus"  name="q_minus" type="checkbox" value="1"/>
                </td> 
            <tr>
                <td>STATUS</td>
                <td>:</td>
                <td><input type="text" id="status_ang" style="width: 196px; border:0; text-align: left;" readonly="true"/></td> 
            
            </tr>
            
            <tr>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td> 
            </tr>
            
            <tr>
                <td colspan="6" align="center">
                <button class="button-biru" onclick="javascript:append_save();"><i class="fa fa-simpan"></i>Simpan</button>
                <button class="button-cerah" onclick="javascript:keluar_rek();"><i class="fa fa-kiri"></i>Keluar</button>  
                </td>
            </tr>
            
    </table>  
    </fieldset>
    
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
            <td width="110px">Bendahara:</td>
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
        <tr>
            <td width="110px">SPASI:</td>
            <td><input type="number" id="spasi" style="width: 100px;" value="1"/></td>
        </tr>
    </table>  
    </fieldset>
    <div>
    </div>    
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp1/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Pengantar</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp2/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Ringkasan</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp3/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Rincian</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp4/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">Pernyataan</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp5/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Permintaan</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp6/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">SPTB/Kontrak</a>
    <br/>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp1/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Pengantar</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp2/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Ringkasan</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp3/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Rincian</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp4/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">Pernyataan</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp5/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp(this.href);return false;">Permintaan</a>
    <a href="<?php echo site_url(); ?>cetak_spp/cetakspp6/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">SPTB/Kontrak</a>
    <br/>
    &nbsp;&nbsp;&nbsp;<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>  
</div>
<input type="text" name="nomer_spd" hidden id="nomer_spd">
</body>
</html>