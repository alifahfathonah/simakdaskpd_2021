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
    
    var kode     = '';
    var giat     = '';
    var jenis    = '';
    var nomor    = '';
    var cid      = 0;
    var lcstatus = '';
    var skpdrek  = '';
    var kd_sub_skpd='';
    var kd_sub_giat='';                      
     $(document).ready(function() {
      $("#loading").hide();
            $("#accordion").accordion();
            $('#rincidpo').combogrid();
            $('#kgiat').combogrid();
            $('#kontrak').combogrid();
            $('#rek').combogrid();   
            $('#sumber_dn').combogrid();         
            $( "#dialog-modal" ).dialog({
                height: 1000,
                width: 1050,
                modal: true,
                autoOpen:false                
            });              
            $("#tagih").hide();
            $("#save").show();
            $("#pesen").hide();
            get_skpd();
            get_tahun();
        });    
          
     $(function(){  /*start function ready*/
         $('#dg').edatagrid({
            url: '<?php echo base_url(); ?>/index.php/penagihanc/load_penagihan',
            idField:'id',            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",                       
            columns:[[
                {field:'no_bukti',
                title:'Nomor Bukti',
                width:50},
                {field:'tgl_bukti',
                title:'Tanggal',
                width:30},
                {field:'nm_skpd',
                title:'Nama SKPD',
                width:100,
                align:"left"},
                {field:'ket',
                title:'Keterangan',
                width:100,
                align:"left"}
            ]],
            onSelect:function(rowIndex,rowData){
                  nomor = rowData.no_bukti;
                  tgl   = rowData.tgl_bukti;
                  kode  = rowData.kd_skpd;
                  nama  = rowData.nm_skpd;
                  ket   = rowData.ket;
                  ket_bast   = rowData.ket_bast;   
                  kd_sub_skpd   = rowData.kd_sub_skpd;          
                  jns   = rowData.jns_beban; 
                  tot   = rowData.total;
                  notagih=rowData.no_tagih;
                  tgltagih=rowData.tgl_tagih;
                  ststagih=rowData.sts_tagih;
                  sts=rowData.status;
                  jenis=rowData.jenis;
                  kontrak=rowData.kontrak;
                  get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak,ket_bast);   
                  cekspp(nomor);
                  load_detail();  
                  load_tot_tagih();
            },
            onDblClickRow:function(rowIndex,rowData){
                  nomor = rowData.no_bukti;
                  tgl   = rowData.tgl_bukti;
                  kode  = rowData.kd_skpd;
                  nama  = rowData.nm_skpd;
                  ket   = rowData.ket;
                  ket_bast   = rowData.ket_bast;          
                  jns   = rowData.jns_beban; 
                  tot   = rowData.total;
                  notagih=rowData.no_tagih;
                  tgltagih=rowData.tgl_tagih;
                  ststagih=rowData.sts_tagih;
                   kd_sub_skpd   = rowData.kd_sub_skpd;     
                  sts=rowData.status;
                  jenis=rowData.jenis;
                  kontrak=rowData.kontrak;
                  get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak,ket_bast);   
                  cekspp(nomor);
                  load_detail();  
                  load_tot_tagih();         
                  section2(); 
                  lcstatus = 'edit';
            }
        });
    
    
        $('#dg1').edatagrid({  
                toolbar:'#toolbar',
                rownumbers:"true", 
                fitColumns:"true",
                singleSelect:"true",
                autoRowHeight:"false",
                loadMsg:"Tunggu Sebentar....!!",            
                nowrap:"true",
                onSelect:function(rowIndex,rowData){                    
                        idx = rowIndex;
                        nilx = rowData.nilai;
                },                                                     
                columns:[[
                {field:'no_bukti',
                  title:'No Bukti',           
                  hidden:"true"},
                {field:'no_sp2d',
                title:'No SP2D',            
                hidden:"true"},
                {field:'kd_kegiatan',
                title:'SubKegiatan',
                width:60},
                {field:'nm_kegiatan',
                title:'Nama Sub Kegiatan',          
                hidden:"true"},
                {field:'kd_rek5',
                title:'Kode Rekening',
                width:30},
                {field:'nm_rek5',
                title:'Nama Rekening',
                width:100,
                align:"left"},
                {field:'nilai',
                title:'Nilai',
                width:70,
                align:"right"},
                {field:'lalu',
                title:'Sudah Dibayarkan',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'sp2d',
                title:'SP2D Non UP',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'anggaran',
                title:'Anggaran',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'kd_rek',
                title:'Rekening',
                width:30},
                {field:'sumber',
                title:'Sumber',
                width:80,align:"center"}
                ]]
        });    
                
        $('#dg2').edatagrid({});
        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();    
                return y+'-'+m+'-'+d;
           }, onSelect: function(date){
                cek_status_ang();
            }
        });
        
        $('#tgltagih').datebox({  
                required:true,
                formatter :function(date){
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate();    
                    return y+'-'+m+'-'+d;
                }
        });
    }); /*end function ready*/     
  
    function load_total_spd(giat){
        var kode = document.getElementById('skpd').value;
        var ctgl = $('#tanggal').datebox('getValue');
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/penagihanc/load_total_spd",
            dataType:"json",
            data: ({giat:giat,kode:kode,tgl:ctgl}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#total_spd").attr("Value",n['total_spd']);              
                });
            }
         });
        });
    }
    

    
    function load_realisasi_rek(skpdrek,giat){
        var kode = document.getElementById('skpd').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/jumlah_ang_spp_tagih",
            dataType:"json",
            data: ({kegiatan:giat,kd_skpd:kode,kdrek5:skpdrek,no_spp:'XxXxX'}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#nilai_real").attr("value",n['nilai_real']);
                });
            }
         });
        });
    }
    
    //perbaikan
    function load_total_trans(giat){
        var no_simpan = document.getElementById('no_simpan').value;  
        var kode = document.getElementById('skpd').value;
        
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/sppc/load_total_trans_spd",
            dataType:"json",
            data: ({giat:giat,kode:kode,no_simpan:no_simpan}),
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#nilai_spd_lalu").attr("Value",n['total']);
                });
             $("#rek").combogrid('enable');                          
            }
         });
        });
    }
    
    function total_sisa_spd(){ 
        var tot_spd   = angka(document.getElementById('total_spd').value);  
        var tot_trans = angka(document.getElementById('nilai_spd_lalu').value);               
        totsisa = tot_spd-tot_trans;
       //$('#sisa_spd').attr('value',number_format(totsisa,2,'.',','));       
       //$("#total_spd").attr("Value",number_format(tot_spd,2,'.',','));
       //$("#nilai_spd_lalu").attr("Value",number_format(tot_trans,2,'.',','));
       $("#nilai_sisa_spd").attr("Value",number_format(totsisa,2,'.',','));
       
    }
    
    function get_skpd()
        {
        
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                        $("#skpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);
                                        skpd = data.kd_skpd;
                                        data_kegiatan();                 
                                      }                                     
            });  
        }       
    function get_tahun() {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                    tahun_anggaran = data;
                    }                                     
            });
             
        }

    function cek_status_ang(){
        var tgl_cek = $('#tanggal').datebox('getValue');      
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
    
    function data_kegiatan(){

           $('#kgiat').combogrid({  
           panelWidth:900,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/penagihanc/load_trskpd_giat',
           queryParams:({kd:skpd,jenis:'52'}),             
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Sub Kegiatan',width:140},  
               {field:'nm_kegiatan',title:'Nama Sub Kegiatan',width:500},
               {field:'nm_skpd',title:'',width:200},  
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               giat = rowData.kd_kegiatan;
               kd_sub_skpd=rowData.kd_sub_skpd;
               nm_giat = rowData.nm_kegiatan;
               $("#knmkegiatan").attr("value",rowData.nm_kegiatan);
               $("#giat").combogrid("setValue","");            
               data_rekening(giat,skpd);
               load_total_spd(giat);
               load_total_trans(giat);
               
             $("#rek").combogrid('enable');
           }
           
        });
    }       

    function data_rekening(giat,skpd){
      kd_sub_skpd=kd_sub_skpd;
        $('#rek').combogrid({  
           panelWidth:900,  
           idField:'kd_rek5',  
           textField:'kd_rek5',
           queryParams:({giat:giat,kd:skpd, kd_sub_skpd:kd_sub_skpd}),  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/penagihanc/load_rek_penagihan',
           columns:[[  
               {field:'kd_rek',title:'Kode Rekening Ang.',width:100,align:'center'},  
               {field:'kd_rek5',title:'Kode Rekening',width:100,align:'center'},  
               {field:'nm_rek5',title:'Nama Rekening',width:200},
               {field:'lalu',title:'Lalu',width:120,align:'right'},
               {field:'sp2d',title:'SP2D',width:120,align:'right'},
               {field:'anggaran_ubah',title:'Anggaran',width:120,align:'right'}
           ]],
           onSelect:function(rowIndex,rowData){
                var anggaran = rowData.anggaran;
                var anggaran_semp = rowData.anggaran_semp;
                var anggaran_ubah = rowData.anggaran_ubah;
                var lalu = rowData.lalu;
                sisa = anggaran-lalu;
                sisa_semp = anggaran_semp-lalu;
                sisa_ubah = anggaran_ubah-lalu;
                skpdrek = rowData.kd_rek;
                $("#rek1").attr("value",rowData.kd_rek);
                $("#nmrek").attr("value",rowData.nm_rek5);                               
                
                $("#rek_nilai_ang").attr("Value",number_format(anggaran,2,'.',','));
                $("#rek_nilai_ang_semp").attr("Value",number_format(anggaran_semp,2,'.',','));
                $("#rek_nilai_ang_ubah").attr("Value",number_format(anggaran_ubah,2,'.',','));
                
                $("#rek_nilai_spp").attr("Value",number_format(lalu,2,'.',','));                
                $("#rek_nilai_spp_semp").attr("Value",number_format(lalu,2,'.',','));               
                $("#rek_nilai_spp_ubah").attr("Value",number_format(lalu,2,'.',','));
                  
                $("#rek_nilai_sisa").attr("Value",number_format(sisa,2,'.',','));
                $("#rek_nilai_sisa_semp").attr("Value",number_format(sisa_semp,2,'.',','));
                $("#rek_nilai_sisa_ubah").attr("Value",number_format(sisa_ubah,2,'.',','));
                
                total_sisa_spd();
                data_sdana_n_rincian();
           }
        });  

    } 

    function data_sdana_n_rincian(){
       var kode = document.getElementById('skpd').value;
       var kode_keg = $('#kgiat').combogrid('getValue') ;
       var koderek = document.getElementById('rek1').value;

       $(function(){ 


        kd_sub_skpd=kd_sub_skpd;
       $('#sumber_dn').combogrid({  
           url:'<?php echo base_url(); ?>index.php/penagihanc/load_reksumber_dana',
           queryParams:({giat:kode_keg,kd:kode,rek:koderek, kd_sub_skpd:kd_sub_skpd}), 
           panelWidth:250,  
           idField:'sumber_dana',  
           textField:'sumber_dana',  
           mode:'remote',                        
           columns:[[  
               {field:'sumber_dana',title:'Sumber Dana',width:250}
           ]] ,
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
              document.getElementById('nilai').select();
           }
        }); });
    }               

    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        cgiat    = rows.kd_kegiatan;
        crek     = rows.kd_rek5;
        cnil     = rows.nilai;
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Sub Kegiatan : '+cgiat+' Rekening : '+crek+' Nilai : '+cnil);
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);
            total = angka(document.getElementById('total1').value) - angka(cnil);            
            $('#total1').attr('value',number_format(total,2,'.',','));    
            $('#total').attr('value',number_format(total,2,'.',','));
            kosong2();
            
        } 
    }
    
    function load_tot_tagih(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_tagih:nomor}),
            url:"<?php echo base_url(); ?>index.php/penagihanc/load_tot_tagih",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#total").attr("value",n['total']);
                });
            }
         });
        });
    }
    
    function load_detail(){        
        var kk    = document.getElementById("nomor").value;
        var ctgl  = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById("skpd").value;            
           
            $(document).ready(function(){
              $("#save").show();
              $("#pesen").hide();
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/penagihanc/load_dtagih',
                data     : ({no:kk}),
                dataType : "json",
                success  : function(data){                                          
                    $.each(data,function(i,n){                                    
                    no        = n['no_bukti'];
                    nosp2d    = n['no_sp2d'];                                                                    
                    kgiat      = n['kd_kegiatan'];
                    knmgiat    = n['nm_kegiatan'];
                    rek5      = n['kd_rek5'];
                    rek       = n['kd_rek'];
                    nmrek5    = n['nm_rek5'];
                    nil       = number_format(n['nilai'],2,'.',',');
                    clalu     = number_format(n['lalu'],2,'.',',');
                    csp2d     = number_format(n['sp2d'],2,'.',',');
                    canggaran = number_format(n['anggaran'],2,'.',',');    
                    cdana     = n['sumber'];                                                                                  
                    $('#dg1').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:clalu,sp2d:csp2d,anggaran:canggaran,kd_rek:rek,kd_kegiatan:kgiat,nm_kegiatan:knmgiat,sumber:cdana});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                
           set_grid();                                                  
    }
    
    
    
        function set_grid(){
        $('#dg1').edatagrid({                                                                   
            columns:[[
                {field:'no_bukti',
                title:'No Bukti',               
                hidden:"true"},
                {field:'no_sp2d',
                title:'No SP2D',                
                hidden:"true"},
                {field:'kd_kegiatan',
                title:'Kegiatan',
                width:320},
                {field:'nm_kegiatan',
                title:'Nama Sub Kegiatan',              
                hidden:"true"},
                {field:'kd_rek5',
                title:'Kode Rekening',
                width:100},
                {field:'nm_rek5',
                title:'Nama Rekening',
                width:200,
                align:"left"},
                {field:'nilai',
                title:'Nilai',
                width:150,
                align:"right"},
                {field:'lalu',
                title:'Sudah Dibayarkan',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'sp2d',
                title:'SP2D Non UP',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'anggaran',
                title:'Anggaran',
                align:"right",
                width:30,
                hidden:'true'},
                {field:'kd_rek',
                title:'Rekening',
                width:30,
                hidden:'true'},
                {field:'sumber',
                title:'Sumber',
                width:80,align:"center"}
            ]]
        });                 
    }
    
    
    
    function load_detail2(){        
       $('#dg1').datagrid('selectAll');
       var rows = $('#dg1').datagrid('getSelections');             
       if (rows.length==0){
            set_grid2();
            exit();
       }                     
        for(var p=0;p<rows.length;p++){
            no      = rows[p].no_bukti;
            nosp2d  = rows[p].no_sp2d;
            giat    = rows[p].kd_kegiatan;
            nmgiat  = rows[p].nm_kegiatan;
            rek5    = rows[p].kd_rek5;
            rek     = rows[p].kd_rek;
            nmrek5  = rows[p].nm_rek5;
            nil     = rows[p].nilai;
            lal     = rows[p].lalu;
            csp2d   = rows[p].sp2d;
            canggaran   = rows[p].anggaran;
            csumber   = rows[p].sumber;                                                                                                                              
            $('#dg2').edatagrid('appendRow',{no_bukti:no,no_sp2d:nosp2d,kd_rek5:rek5,nm_rek5:nmrek5,nilai:nil,lalu:lal,sp2d:csp2d,anggaran:canggaran,kd_rek:rek,kd_kegiatan:giat,nm_kegiatan:nmgiat,sumber:csumber});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    
    
    function set_grid2(){
        $('#dg2').edatagrid({ 
        height:200,     
         columns:[[
            {field:'hapus',
            title:'Hapus',
            width:50,
            align:"center",
            formatter:function(value,rec){                                                                       
                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';                  
                }                
            },
            {field:'no_bukti',
            title:'No Bukti',           
            hidden:"true",
            width:30},
            {field:'no_sp2d',
            title:'No SP2D',
            width:40,
            hidden:"true"},
            {field:'kd_kegiatan',
            title:'Sub Kegiatan',
            width:120},
            {field:'nm_kegiatan',
            title:'Nama Sub Kegiatan',          
            hidden:"true",
            width:30},
            {field:'kd_rek5',
            title:'REK LO',
            width:100,
            align:'center'},
            {field:'kd_rek',
            title:'REK 90',
            width:100,
            align:'center'},            
            {field:'nm_rek5',
            title:'Nama Rekening',
            align:"left",
            width:250},
            {field:'nilai',
            title:'Rupiah',
            align:"right",
            width:200},
            {field:'lalu',
            title:'Sudah Dibayarkan',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'sp2d',
            title:'SP2D Non UP',
            align:"right",
            width:30,
            hidden:"true"},
            {field:'anggaran',
            title:'Anggaran',
            align:"right",
            width:200},
            {field:'sumber',
            title:'Sumber',
            align:"center",
            width:150}
            ]]     
        });
    }
    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });         
         $('#dg').edatagrid('reload');
         set_grid();
    }
     

    function section_kontrak(){
      
      var kontrak_tagih = $('#kontrak').combogrid('getValue');
      var kontrak_tagih = kontrak_tagih.split("/").join("abcd");
      var kontrak_tagih = kontrak_tagih.split(" ").join("efgh");

      var url = "<?php echo base_url(); ?>/index.php/penagihanc/cetak_cek_penagihan_opd";
      if(kontrak_tagih=='')    
      {
        alert('Pilih Kontrak, Lalu Cek Kontrak');
        exit();
      }else{
        window.open(url+'/'+kontrak_tagih, '_blank');
        window.focus();
      }

    } 


    function section_kontrak_all(){
      
      var url = "<?php echo base_url(); ?>/index.php/penagihanc/cetak_cek_penagihan_opdall";
      window.open(url, '_blank');
      window.focus();      

    } 
     
    function section2(){
         $(document).ready(function(){                
             $('#section2').click(); 
             document.getElementById("nomor").focus();                                              
         });                 
         set_grid();
    }
       
    function cekspp(nomor){           
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_tagih:nomor}),
            url:"<?php echo base_url(); ?>index.php/penagihanc/cekspp",
            dataType:"json",
            success:function(total){ 
              if(total>0){
                  $('#save').hide();
                  $('#del').hide();
                  $('#pesen').show();
              }else{
                  $('#save').show();
                  $('#del').show();
                  $('#pesen').hide();
              }
            }

         });

        });

    }

    function get(nomor,tgl,kode,nama,ket,jns,tot,notagih,tgltagih,ststagih,sts,jenis,kontrak,ket_bast){

        $("#nomor").attr("value",nomor);
        $("#nomor_hide").attr("value",nomor);
        $("#no_simpan").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#keterangan").attr("value",ket); 
        $("#kete").attr("value",ket_bast);       
        $("#beban").attr("value",jns);
        $("#notagih").attr("value",notagih);        
        $("#tgltagih").datebox("setValue",tgltagih);    
        $("#status").attr("checked",false);
        $("#status_byr").attr("value",sts);
            $("#jns").attr("Value",jenis);
            $("#kontrak").combogrid("setValue",kontrak);
        if (ststagih==1){
            $("#save").hide();            
            $("#status").attr("checked",true);
            $("#tagih").show();
        } else {
            $("#save").show();  
            $("#status").attr("checked",false);
            $("#tagih").hide();
        }    
        
        //tombol(sts);
    }
    
   
        function tombol(st){  
            if (st=='1'){
                $('#save').hide();
                $('#del').hide();
             } else {
                $('#save').show();
                $('#del').show();
             }
            }
   
    function kosong(){
        cdate = '<?php echo date("Y-m-d"); ?>';        
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#keterangan").attr("value",'');
        $("#kontrak").combogrid("setValue",'');
        $("#total").attr("value",'0');         
        document.getElementById("nomor").focus();  
        lcstatus = 'tambah';
    }
    

    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/penagihanc/load_penagihan',
        queryParams:({cari:kriteria})
        });        
     });
    }    
        

    function append_save(){
        var no  = document.getElementById('nomor').value;             
        var kgiat    = $('#kgiat').combogrid('getValue');
        var knmgiat  = document.getElementById('knmkegiatan').value;
        var nosp2d  = '';
        var rek5  = document.getElementById('rek1').value;
        var rek     = $('#rek').combogrid('getValue');
        var nmrek   = document.getElementById('nmrek').value;
        var crek    = $('#rek').combogrid('grid');          
        var grek    = crek.datagrid('getSelected'); 
        var cdana   = $('#sumber_dn').combogrid('getValue');
        var canggaran = number_format(grek.anggaran,2,'.',',');
        var csp2d   = 0;
        var clalu   = 0;       
        var sisa    = angka(document.getElementById('rek_nilai_sisa').value);                
        var sisa_semp = angka(document.getElementById('rek_nilai_sisa_semp').value);                
        var sisa_ubah = angka(document.getElementById('rek_nilai_sisa_ubah').value);                         
        var sisa_spd     = angka(document.getElementById('nilai_sisa_spd').value);        
               
        var sisa_dana      = angka(document.getElementById('rek_nilai_sisa_dana').value);                
        var sisa_semp_dana = angka(document.getElementById('rek_nilai_sisa_semp_dana').value);                
        var sisa_ubah_dana = angka(document.getElementById('rek_nilai_sisa_ubah_dana').value);                                            
        //        
        var nil     = angka(document.getElementById('nilai').value);       
        var nil_rek     = document.getElementById('nilai').value;        
        var status_ang  = document.getElementById('status_ang').value ;
        var total = angka(document.getElementById('total1').value) + nil;


       
          if ( total > sisa_spd || nil > sisa_spd ){
                 alert('Nilai Melebihi Sisa Dana SPD !!') ;
                 exit();
            }

    
            if (status_ang==''){
                 alert('Pilih Tanggal Dahulu') ;
                 exit();
            }
            
            if ( nil == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            //sumber              
            if ( (status_ang=='Perubahan')&&((nil > sisa_ubah_dana) )){
                 alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&((nil > sisa_ubah_dana) ) ){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&((nil > sisa_semp_dana) )){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&((nil > sisa_ubah_dana) )){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&((nil > sisa_semp_dana))){
                 alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&((nil > sisa_dana))){
                 alert('Nilai Melebihi Sisa Sumber Dana Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            
            //rek    
            if ( (status_ang=='Perubahan')&&((nil > sisa_ubah) )){
                 alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&((nil > sisa_ubah) )){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyempurnaan')&&((nil > sisa_semp) )){
                 alert('Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&((nil > sisa_ubah) )){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&((nil > sisa_semp) )){
                 alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
                 exit();
            }
            if ( (status_ang=='Penyusunan')&&((nil > sisa) )){
                 alert('Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!') ;
                 exit();
            }            
            
            if (cdana==''){
                 alert('Pilih Sumber Dana Dahulu') ;
                 exit();
            }             
            
          
                
            if (nmrek==''){
                 alert('Pilih Rekening Dahulu') ;
                 exit();
            }   

                $('#dg1').edatagrid('appendRow',{no_bukti:no,              
                                                 no_sp2d:nosp2d,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5,
                                                 kd_kegiatan:kgiat,
                                                 nm_kegiatan:knmgiat,
                                                 sumber:cdana
                                                 });

                $('#dg2').edatagrid('appendRow',{no_bukti:no, 
                                                 no_sp2d:nosp2d,
                                                 kd_rek5:rek,
                                                 nm_rek5:nmrek,
                                                 nilai:nil_rek,
                                                 lalu:clalu,
                                                 sp2d:csp2d,
                                                 anggaran:canggaran,
                                                 kd_rek:rek5,
                                                 kd_kegiatan:kgiat,
                                                 nm_kegiatan:knmgiat,
                                                 sumber:cdana
                                                 });                                                 
                kosong2();
                $('#total1').attr('value',number_format(total,2,'.',','));
                $('#total').attr('value',number_format(total,2,'.',','));
    }     
    
    function tambah(){
        var nor = document.getElementById('nomor').value;
        var tot = document.getElementById('total').value;
        var kd  = document.getElementById('skpd').value;
        var kontrak  = $('#kontrak').combogrid("getValue");
        $('#dg2').edatagrid('reload');
        $('#total1').attr('value',tot);
        $('#kgiat').combogrid('setValue','');
        $('#rek').combogrid('setValue','');
        cek_status_ang();
        var tgl = $('#tanggal').datebox('getValue');
        if (kd != '' && tgl != '' && nor !='' &&kontrak !=''){            
            $("#dialog-modal").dialog('open'); 
            load_detail2();           
        } else {
            alert('Harap Isi Kode , Tanggal , Nomor Penagihan & Nomor Kontrak ') ;         
        }        
    }
    
    function kosong2(){        
        $('#giat').combogrid('setValue','');
        $('#sp2d').combogrid('setValue','');
        $('#rek').combogrid('setValue','');

        $('#nmrek').attr('value','');  
        $('#sumber_dn').combogrid('setValue','');
        $('#sisasp2d').attr('value','0');        
        
        $("#rek_nilai_ang").attr("Value",'0');
        $("#rek_nilai_ang_semp").attr("Value",'0');
        $("#rek_nilai_ang_ubah").attr("Value",'0');                
        $("#rek_nilai_spp").attr("Value",'0');                
        $("#rek_nilai_spp_semp").attr("Value",'0');             
        $("#rek_nilai_spp_ubah").attr("Value",'0');
        $("#nilai_rinci").attr("Value",'0');
        
        $("#rek_nilai_ang_dana").attr("Value",'0');
        $("#rek_nilai_ang_semp_dana").attr("Value",'0');
        $("#rek_nilai_ang_ubah_dana").attr("Value",'0');                
        $("#rek_nilai_spp_dana").attr("Value",'0');                
        $("#rek_nilai_spp_semp_dana").attr("Value",'0');                
        $("#rek_nilai_spp_ubah_dana").attr("Value",'0');
        
        
        $('#nilai_sisa_spd').attr('value','0');        
        $('#rek_nilai_sisa').attr('value','0');
        $('#rek_nilai_sisa_semp').attr('value','0');
        $('#rek_nilai_sisa_ubah').attr('value','0');
        $('#rek_nilai_sisa_dana').attr('value','0');
        $('#rek_nilai_sisa_semp_dana').attr('value','0');
        $('#rek_nilai_sisa_ubah_dana').attr('value','0');
        
        $('#nilai').attr('value','0');
        $('#rek1').attr('value','');
        $('#nmgiat').attr('value','');        
        $('#sisa_spd').attr('value','0');        
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg2').edatagrid('reload');
        kosong2();                        
    }   
     
    function hapus_giat(){
         tot3 = 0;
         var tot = angka(document.getElementById('total').value);
         tot3 = tot - nilx;
         $('#total').attr('value',number_format(tot3,2,'.',','));        
         $('#dg1').datagrid('deleteRow',idx);              
    }
    
    
    function hapus(){
        var cnomor = document.getElementById('nomor_hide').value;
        var urll = '<?php echo base_url(); ?>index.php/penagihanc/hapus_penagihan';
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor Penagihan : '+cnomor);        
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({no:cnomor}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil Terhapus');         
                        } else {
                            alert('Gagal Hapus');
                        }        
                 }
                 
                });           
        });
        }     
    }
    
    
    function simpan_transout(){
        var cno          = (document.getElementById('nomor').value).split(" ").join("");
        var cno_hide     = document.getElementById('nomor_hide').value;
        var cjenis_bayar = document.getElementById('status_byr').value;
        var ctgl         = $('#tanggal').datebox('getValue');
        var cskpd        = document.getElementById('skpd').value;
        var cnmskpd      = document.getElementById('nmskpd').value;
        var cket         = document.getElementById('keterangan').value;
        var cket2        = document.getElementById('kete').value;
        var jns          = document.getElementById('jns').value;
        var kontrak      = $('#kontrak').combogrid("getValue");
        
        var cjenis   = '6';
        var cstatus  = '';
        var csql     = '';
        kd_sub_skpd=kd_sub_skpd;
        var tahun_input = ctgl.substring(0, 4);
        if (tahun_input != tahun_anggaran){
            alert('Tahun tidak sama dengan tahun Anggaran');
            exit();
        }
        if (cstatus==false){
            cstatus=0;
        }else{
            cstatus=1;
        }
        
        var ctagih    = '';
        var ctgltagih = '2016-12-1';
        var ctotal    = angka(document.getElementById('total').value);        
        var jns_trs = '1';
        
        if ( cno=='' ){
            alert('Nomor Bukti Tidak Boleh Kosong');
            exit();
        } 
        if ( ctgl=='' ){
            alert('Tanggal Bukti Tidak Boleh Kosong');
            exit();
        }
        if ( cskpd=='' ){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        if ( cnmskpd=='' ){
            alert('Nama SKPD Tidak Boleh Kosong');
            exit();
        }
        if ( kontrak=='' ){
            alert('Kontrak Tidak Boleh Kosong');
            exit();
        }
        if ( cket=='' ){
            alert('Keterangan Tidak boleh kosong');
            exit();
        }
        var lenket = cket.length;
        if ( lenket>1000 ){
            alert('Keterangan Tidak boleh lebih dari 1000 karakter');
            exit();
        }
        
        if(lcstatus == 'tambah'){
        $(document).ready(function(){
               $("#loading").show();
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        alert("Nomor Telah Dipakai!");
                        $("#loading").hide();
                        document.getElementById("nomor").focus();
                        exit();
                        } 
                        if(status_cek==0){
                       
    //---------------------------
            lcinsert     = " ( no_bukti,  tgl_bukti,  ket,        username, tgl_update, kd_skpd,     nm_skpd,       total,        no_tagih,     sts_tagih,  status ,   tgl_tagih,       jns_spp, jenis, kontrak, jns_trs,ket_bast, kd_sub_skpd      ) " ; 
            lcvalues     = " ( '"+cno+"', '"+ctgl+"', '"+cket+"', '<?php echo $this->session->userdata('pcNama') ?>',       '',         '"+cskpd+"', '"+cnmskpd+"', '"+ctotal+"', '"+ctagih+"', '"+cstatus+"','"+cjenis_bayar+"', '"+ctgltagih+"', '"+cjenis+"', '"+jns+"', '"+kontrak+"', '"+jns_trs+"', '"+cket2+"', '"+kd_sub_skpd+"' ) " ;
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/penagihanc/simpan_penagihan_ar',
                    data     : ({tabel    : 'trhtagih',  kolom    :lcinsert,    nilai    : lcvalues,    cid    : 'no_bukti',   lcid    : cno,
                                 proses   : 'header', status_byr : cjenis_bayar }),

                    dataType : "json",
                    success  : function(data) {
                        status = data;
                        if ( status == '0') {
                          $("#loading").hide();
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1') {
                          $("#loading").hide();
                                  alert('Data Sudah Ada..!!');
                                  exit();
                               } else {
                                
                                    $('#dg1').datagrid('selectAll');
                                    var rows = $('#dg1').datagrid('getSelections');           
                                    for(var p=0;p<rows.length;p++){
                                        cnobukti   = rows[p].no_bukti;
                                        cnosp2d    = rows[p].no_sp2d;
                                        ckdgiat    = rows[p].kd_kegiatan;
                                        cnmgiat    = rows[p].nm_kegiatan;                                        
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                                        csumber    = rows[p].sumber;
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"','"+kd_sub_skpd+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"','"+kd_sub_skpd+"')";                                            
                                        }
                                    }
                                  
                                    $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,proses:'detail', status_byr : cjenis_bayar}),
                                         url      : '<?php echo base_url(); ?>/index.php/penagihanc/simpan_penagihan_ar',
                                         success  : function(data){                        
                                                    status = data;   
                                                    if ( status=='5' ) {
                                                    $("#loading").hide();               
                                                        alert('Data Detail Gagal Tersimpan');
                                                    } 
                                                    }
                                                    });
                                    });            
                                    $("#loading").hide();
                                    alert('Data Tersimpan..!!');
                                     $("#nomor_hide").attr("value",cno);
                                     $("#no_simpan").attr("value",cno);
                                    lcstatus = 'edit';
                                    exit();
                              
                               }
                    }
                });
            });
            //--------------            
            
        }
        }
        });
        });
    } else{
        $(document).ready(function(){
              $("#loading").show();
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhtagih',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && cno!=cno_hide){
                          $("#loading").hide();
                        alert("Nomor Telah Dipakai!");
                        exit();
                        }
                        if(status_cek==0 || cno==cno_hide){
       
         lcquery    = " UPDATE trhtagih  SET username='<?php echo $this->session->userdata('pcNama') ?>', kd_sub_skpd='"+kd_sub_skpd+"',no_bukti='"+cno+"',   tgl_bukti='"+ctgl+"',   ket='"+cket+"', tgl_update='', nm_skpd='"+cnmskpd+"', total='"+ctotal+"',   no_tagih='"+ctagih+"', sts_tagih='"+cstatus+"', status='"+cjenis_bayar+"', tgl_tagih='"+ctgltagih+"', jns_spp='"+cjenis+"', jenis='"+jns+"',ket_bast='"+cket2+"' , kontrak='"+kontrak+"' where no_bukti='"+cno_hide+"' AND kd_skpd='"+cskpd+"' "; 
           
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/penagihanc/update_penagihan_header_ar',
                data     : ({st_query:lcquery,tabel:'trhtagih',cid:'no_bukti',lcid:cno,lcid_h:cno_hide,status : cjenis_bayar}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='1' ){
                          $("#loading").hide();
                            alert('Nomor Bukti Sudah Terpakai...!!!,  Ganti Nomor Bukti...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
                              
                              var a         = document.getElementById('nomor').value; 
                              var a_hide    = document.getElementById('nomor_hide').value; 
                              
                              $('#dg1').datagrid('selectAll');
                              var rows = $('#dg1').datagrid('getSelections');           
                              for(var p=0;p<rows.length;p++){
                      
                                        cnobukti   = a ;
                                        cnosp2d    = rows[p].no_sp2d;
                                        ckdgiat    = rows[p].kd_kegiatan;
                                        cnmgiat    = rows[p].nm_kegiatan;                                     
                                        crek       = rows[p].kd_rek5;
                                        cnmrek     = rows[p].nm_rek5;
                                        cnilai     = angka(rows[p].nilai);
                                        crek5      = rows[p].kd_rek;
                                        csumber    = rows[p].sumber;
                                        
                                        if ( p > 0 ) {
                                           csql = csql+","+"('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"','"+kd_sub_skpd+"')";
                                        } else {
                                            csql = "values('"+cno+"','"+cnosp2d+"','"+ckdgiat+"','"+cnmgiat+"','"+crek+"','"+crek5+"','"+cnmrek+"','"+cnilai+"','"+cskpd+"','"+csumber+"','"+kd_sub_skpd+"')";                                            
                                        }
                              }
                                                                                  
                              $(document).ready(function(){
                                    $.ajax({
                                         type     : "POST",   
                                         dataType : 'json',                 
                                         data     : ({tabel_detail:'trdtagih',no_detail:cno,sql_detail:csql,
                                                      nomor:a_hide,lcid:a,lcid_h:a_hide}),
                                         url      : '<?php echo base_url(); ?>/index.php/penagihanc/update_penagihan_detail_ar',
                                         success  : function(data){                        
                                                    status = data;  
                                                    if(status=='1'){
                                                      $("#loading").hide();
                                                        $("#nomor_hide").attr("Value",cno) ;
                                                        $("#no_simpan").attr("Value",cno) ;
                                                        $('#dg1').edatagrid('unselectAll');
                                                        alert('Data Tersimpan');
                                                        lcstatus = 'edit';
                                                        $('#dg1').edatagrid('unselectAll');
                                                        } 
                                                        else {     
                                                        $("#loading").hide();          
                                                        alert('Data Detail Gagal Tersimpan');
                                                    } 
                                                    }
                                                    });
                                }); 
                            }
                        if ( status=='0' ){
                          $("#loading").hide();
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                        
                    }
            });
            });
      
            }
            }
        });
     });    
    }   $("#loading").hide();    
    }

    
    function sisa_bayar(){
        
        var sisa     = angka(document.getElementById('rek_nilai_sisa_ubah').value);             
        var nil      = angka(document.getElementById('nilai').value);        
        var sisasp2d = angka(document.getElementById('sisasp2d').value);
        var tot      = 0;
        tot          = sisa - nil;
        
        if (nil > sisasp2d) {    
                alert('Nilai Melebihi Sisa Sp2d');
                    exit();
        } else {
            if (tot < 0){
                    alert('Nilai Melebihi Sisa');
                    exit();                
            }
        }           
    }       
                         
                  
    function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
    };              
                             
    </script>

</head>
<body>

<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1" >List Penagihan </a></h3>
    <div>
    <p align="right"> 
        <a class="button-cerah" onclick="javascript:section_kontrak_all();"><i class="fa fa-print"> CETAK KESELURUHAN</i></a>

                  <a ><button class="button" onclick="javascript:section2();kosong();load_detail();"> <i class="fa fa-tambah"> TAMBAH</i> </button> </a>
                  <a ><button class="button-cerah" onclick="javascript:cari();"> <i class="fa fa-cari"> CARI</i> </button> </a>      

        <input type="text" class="input" style="display: inline;" value="" id="txtcari"/>
        <table id="dg" title="List Pembayaran Transaksi" style="width:1030px;height:600px;" >  
        </table>                          
    </p> 
    </div>   

<h3><a href="#" id="section2">PENAGIHAN</a></h3>
   <div  style="height: 350px;">
   <p>       
   <div id="demo"></div>
        <table align="center" style="width:100%;">
            <tr>
        <td style="border-bottom: double 1px red;"><i>No. Tersimpan<i></td>
        <td style="border-bottom: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
                <td style="border-bottom: double 1px red;">&nbsp;&nbsp;</td>
                <td style="border-bottom: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr>
            <tr>
                <td>No.BAST/ Penagihan</td>
                <td>&nbsp;<input type="text" id="nomor" class="input" style="width: 100%;" onclick="javascript:select();"/> <input  id="nomor_hide" style="width: 20px;" onclick="javascript:select();" hidden /></td>
                <td>&nbsp;&nbsp;</td>
                <td>Tanggal </td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>     
            </tr>                        
            <tr>
                <td>S K P D</td>
                <td>&nbsp;<input id="skpd" class="input" name="skpd" readonly="true" style="width: 100%;border: 0;" /></td>
                <td></td>
                <td>Nama SKPD :</td> 
                <td><input type="text" class="input" id="nmskpd" style="border:0;width: 400px;border: 0;" readonly="true"/></td>                               
            </tr>
                   
            <tr>
                <td>Keterangan</td>
                <td colspan="4"><textarea id="keterangan" class="textarea" style="width: 760px; height: 40px;"></textarea></td>
           </tr> 
                       <tr>
                <td>Ket (BA)</td>
                <td colspan="4"><textarea id="kete" class="textarea" style="width: 760px; height: 40px;"></textarea></td>
           </tr> 
                <td>Status</td>
                 <td>
                     <select name="status_byr" class="input" id="status_byr" style="width: 100%">
                         <option value="1">SELESAI</option>
                         <option value="0">BELUM SELESAI</option>
                     </select>
                 </td> 
            </tr>
            <tr>
                 <td>Jenis</td>
                 <td>
                     <select name="jns" class="input" id="jns" style="width:100%">
                         <option value="">TANPA TERMIN / SEKALI PEMBAYARAN</option>
                         <option value="5">BAST 95% dan 5%</option>                        
                         <option value="4">UANG MUKA TERMIN</option>                         
                         <!--<option value="2">UANG MUKA LUNAS</option>-->
                         <option value="1">TERMIN</option>                         
                         <option value="3">HUTANG TAHUN LALU</option>
                         
                     </select>
                 </td>
            </tr>
            <tr>
                <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Kontrak</td>
                <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="kontrak" name="kontrak" style="width:360px"/> 
                <td colspan="3" align="center">
                <code id="pesen" class="button-merah"><b>SUDAH DIBUAT SPP</b></code>
                  <a class="button-cerah"  onclick="javascript:section_kontrak();"> <i class="fa fa-cari"> Cek Kontrak</i></a>
                    <a id="save"><button class="button-biru" onclick="javascript:simpan_transout();"> <i class="fa fa-cari"> SIMPAN</i> </button> </a>
                  <a id="del"><button class="button-merah" onclick="javascript:hapus();section1();"> <i class="fa fa-hapus"> HAPUS</i> </button> </a>                                        
                  <a class="button-cerah" onclick="javascript:section1();"> <i class="fa fa-kiri"> Kembali</i></a>                             
                </td>
            </tr>
        </table>          
        <table id="dg1" title="Rekening" style="width:1024px;height:350px;" >  
        </table>  
        <div id="toolbar" align="right">
            <button class="button" onclick="javascript:tambah();"><i class="fa fa-tambah"></i> Tambah Sub Kegiatan</button>
            <button class="button-merah" onclick="javascript:hapus_giat();"><i class="fa fa-hapus"></i> Hapus Sub Kegiatan</button>
                    
        </div>
        <table align="center" style="width:100%;">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ></td>
            <td align="right">Total : <input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/></td>
        </tr>
        </table>
                
   </p>
   </div>
   
</div>
</div>


<div id="dialog-modal" title="Input Sub Kegiatan">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table border="0">
        <tr>
            <td width="21%">Kode Sub Kegiatan</td>            
            <td width="21%"><input id="kgiat" name="kgiat" style="width: 200px;" /></td>
            <td width="18%" align="center">Nama Sub Kegiatan</td>            
            <td colspan="3" width="40%"><input type="text" id="knmkegiatan" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>         
        <tr>
            <td >Kode Rekening</td>            
            <td><input id="rek" name="rek" style="width: 200px;" /><br>
            <input id="rek1" class="input" name="rek1" style="width: 190px;" readonly="true"/></td>
            <td  align="center">Nama Rekening</td>            
            <td colspan="3"><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>        
        <tr>
            <td >Sumber Dana</td>           
            <td colspan="5"><input id="sumber_dn" name="sumber_dn" style="width: 200px;" /></td>            
        </tr>       
        
        <tr>
                <td bgcolor="#99FF99">TOTAL SPD</td>                
                <td bgcolor="#99FF99"><input type="text" id="total_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#99FF99" align="center">REALISASI</td>                
                <td bgcolor="#99FF99"><input type="text" id="nilai_spd_lalu" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#99FF99">SISA</td>                
                <td bgcolor="#99FF99"><input type="text" id="nilai_sisa_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
            </tr>            
            <tr>
                <td bgcolor="#87CEFA">ANGGARAN MURNI</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA" align="center">REALISASI</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>               
            </tr>
            
            <tr>
                <td bgcolor="#87CEFA">PENYEMPURNAAN</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA" align="center">REALISASI</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            <tr>
                <td bgcolor="#87CEFA">PERUBAHAN</td>   
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA" align="center">REALISASI</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>                
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            <tr>
                <td bgcolor="#FFA07A">SUMBER DANA MURNI</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A" align="center">REALISASI</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            
            <tr>
                <td bgcolor="#FFA07A">PENYEMPURNAAN</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A" align="center">REALISASI</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr>
            <tr>
                <td bgcolor="#FFA07A">PERUBAHAN</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A" align="center">REALISASI</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>                
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr>
        
         <tr>
            <td >Status</td>            
            <td><input type="text" id="status_ang" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>  
            <td colspan="4"></td>           
        </tr>
        <tr>
            <td >Nilai</td>            
            <td><input type="text" class="input" id="nilai" style="text-align: right; width: 196px;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:sisa_bayar();"/></td>            
            <td colspan="4"></td>  
        </tr>
    </table>  
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td>
                <a ><button class="button-biru" onclick="javascript:append_save();"><i class="fa fa-tambah"> Simpan</i></button></a>
                <a ><button class="button-abu" onclick="javascript:keluar();"><i class="fa fa-kiri"> Keluar</i></button></a>                               
            </td>
        </tr>
    </table>   
    </fieldset>
    <fieldset>
        <table align="right" >           
            <tr>
                <td>Total</td>
                <td>:</td>
                <td><input type="text" id="total1" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;"/></td>
            </tr>
        </table>
        <table id="dg2" title="Input Rekening" style="width:980px;height:150px;"  >  
        </table>  
     
    </fieldset>  
</div>
</body>
<div id="loading" class="loader1"> <div class="loader2"></div></div>
</html>
