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
    
    var nl =0; 
    var tnl =0; 
    var idx=0; 
    var tidx=0;  
    var oldRek=0; 
    var rek=0;
    var tahun_anggaran="<?php echo $this->session->userdata('pcThang'); ?>";
    
    $(function(){
         $('#dd').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });

         $('#pot').edatagrid();

                     $("#dialog-modal-edit" ).dialog({
                height: 230,
                width: 600,
                modal: true,
                autoOpen:false                
            });
    });

    
    
     $(function(){
    $('#no_sp2d').combogrid({  
                   panelWidth : 450,  
                   idField    : 'no_sp2d',  
                   textField  : 'no_sp2d',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/setor/load_sp2d_trimpot',  
                   columns:[[  
                       {field:'no_sp2d',title:'No SP2D',width:250},  
                       {field:'tgl_sp2d',title:'Tanggal SP2D',width:200}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                        $("#nm_giat").attr("value",'');
                        $("#nm_rek").attr("value",'');
                        $("#kd_giat").combogrid("setValue",'');
                        $("#kd_rek").combogrid("setValue",'');
                       cskpd = document.getElementById('dn').value;
                       nosp2d = rowData.no_sp2d;
                       sp2d = nosp2d.split("/").join("123456789");
                       $("#beban").attr("value",rowData.jns_spp); 
                       $("#kd_giat").combogrid({url: '<?php echo base_url(); ?>/index.php/setor/load_kegiatan_pot/'+sp2d+'/'+cskpd}); 
                   }  
                   });  
                });
        $(function(){          
        $('#kd_giat').combogrid({  
                   panelWidth : 450,  
                   idField    : 'kd_giat',  
                   textField  : 'kd_giat',  
                   mode       : 'remote',
                   columns:[[  
                       {field:'kd_giat',title:'Kode Kegiatan',width:150},  
                       {field:'nm_giat',title:'Nama Kegiatan',width:300}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                       $("#nm_giat").attr("value",rowData.nm_giat); 
                       kd_giat_pot = rowData.kd_giat;
                       $("#kd_rek").combogrid({url: '<?php echo base_url(); ?>/index.php/setor/load_rek_pot/'+sp2d+'/'+kd_giat_pot});
                   }  
                   });  
        });
        $(function(){          
        $('#kd_rek').combogrid({  
                   panelWidth : 450,  
                   idField    : 'kd_rek',  
                   textField  : 'kd_rek',  
                   mode       : 'remote',
                   columns:[[  
                       {field:'kd_rek',title:'Kode Kegiatan',width:150},  
                       {field:'nm_rek',title:'Nama Kegiatan',width:300}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                        nosp2d    = $('#no_sp2d').combogrid('getValue');
                        sp2d = nosp2d.split("/").join("123456789");
                        $("#nm_rek").attr("value",rowData.nm_rek); 
                     
                   }  
                   });
            });
        $(function(){   
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
                    //$("#kode").attr("value",rowData.kode);
                    $("#dir").attr("value",rowData.pimpinan);
                    $("#npwp").attr("value",rowData.npwp);
                    $("#alamat").attr("value",rowData.alamat);
                    
                    }   
                });
    });
    
        $(function(){
            $('#trmpot1').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>index.php/setor/trmpot__',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'No',width:60},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:30} 
                          
                    ]],
                     onSelect:function(rowIndex,rowData){
                         no_terima = rowData.no_bukti;                                                                       
                        dns  = rowData.kd_skpd;                        
                        jns  = rowData.jns_spp;
                        nm  = rowData.nm_skpd;
                        npwp = rowData.npwp;
                        kd_giat = rowData.kd_giat;
                        nm_giat = rowData.nm_giat;
                        no_sp2d = rowData.no_sp2d;
                        kd_rek = rowData.kd_rek;
                        nm_rek = rowData.nm_rek;
                        $('#kd_rek2').attr('value',kd_rek);
                        $('#nm_rek2').attr('value',rowData.nm_rek);
                        alamat = rowData.alamat;
                        dir = rowData.dir;
                        rekanan = rowData.rekanan;
                        ket = rowData.ket;   
                        get(dns,nm,jns,npwp,ket,kd_giat,nm_giat,no_sp2d,kd_rek,nm_rek,alamat,dir,rekanan); 
                        pot();                                                              
                    },
                    onDblClickRow: function(rowIndex,rowData){
                         no_terima = rowData.no_bukti;                                                                       
                        dns  = rowData.kd_skpd;                        
                        jns  = rowData.jns_spp;
                        nm  = rowData.nm_skpd;
                        npwp = rowData.npwp;
                        kd_giat = rowData.kd_giat;
                        nm_giat = rowData.nm_giat;
                        no_sp2d = rowData.no_sp2d;
                        kd_rek = rowData.kd_rek;
                        $('#kd_rek2').attr('value',kd_rek);
                        $('#nm_rek2').attr('value',rowData.nm_rek);
                        nm_rek = rowData.nm_rek;
                        alamat = rowData.alamat;
                        dir = rowData.dir;
                        rekanan = rowData.rekanan;
                        ket = rowData.ket;   
                        get(dns,nm,jns,npwp,ket,kd_giat,nm_giat,no_sp2d,kd_rek,nm_rek,alamat,dir,rekanan); 
                        pot();                           
                    }  
                });
           });
    
        $(function(){
            $('#ctrmpot').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/setor/pilih_trmpot',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'Bukti',width:60},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:60},
                        {field:'no_sp2d',title:'SP2D',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.no_bukti;
                    dns = rowData.kd_skpd;
                    val_ttd(dns);
                    }   
                });
           });
           
        function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+dns,  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}
                        
                        
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;
                    
                    }   
                });
           });              
         }
    $(function(){ 
     $('#pot_out').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/setor/load_pot_out',
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
            width:40},
            {field:'tgl_bukti',
            title:'Tanggal Bukti',
            width:40},            
            {field:'ket',
            title:'Keterangan',
            width:140,
            align:"left"},
            {field:'tot',
            title:'jumlah', align:'center',
            width:20}
        ]],
        onSelect:function(rowIndex,rowData){
          bukti = rowData.no_bukti;
          trm = rowData.no_terima          
          tgl  = rowData.tgl_bukti;
          st   = rowData.status;
          ket = rowData.ket;   
          no_sp2d = rowData.no_sp2d; 
          jns_spp = rowData.jns_spp; 
          kd_kegiatan = rowData.kd_kegiatan; 
          nm_kegiatan = rowData.nm_kegiatan;
          kd_rek6 = rowData.kd_rek6;
          nm_rek6 = rowData.nm_rek6;
          nmrekan = rowData.nmrekan; 
          pimpinan = rowData.pimpinan; 
          alamat = rowData.alamat; 
          npwp = rowData.npwp; 
          no_nnt = rowData.no_nnt; 
          npwpd = rowData.npwpd; 
          no_kas_str = rowData.no_kas_str; 
          getpot(bukti,trm,tgl,st,ket,no_sp2d,jns_spp,kd_kegiatan,nm_kegiatan,nmrekan,pimpinan,alamat,npwp,no_nnt,npwpd,no_kas_str,kd_rek6,nm_rek6); 
       detpotong(bukti);
          load_sum_pot();
        },
        onDblClickRow:function(rowIndex,rowData){
        detpotong(bukti);
          load_sum_pot();
            section2();   
        }
    });
    }); 
        
              
    $(function(){
            $('#trmpot').combogrid({  
                panelWidth:610,  
                url: '<?php echo base_url(); ?>/index.php/setor/trmpot_',  
                    idField:'no_bukti',                    
                    textField:'no_bukti',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_bukti',title:'No. BKU',width:25},  
                        {field:'tgl_bukti',title:'Tanggal',align:'left',width:30},
                        {field:'ket',title:'Keterangan',width:130}, 
                        {field:'nmrekan',title:'Rekanan',width:70}  
                    ]],
                     onSelect:function(rowIndex,rowData){
                        no_terima = rowData.no_bukti;
                        $("#trmpot1").combogrid("setValue",no_terima);
                    }  
                });
           });
        
        
         function pot(){
         $(function(){                          
            $('#pot').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/setor/pot_in',
                queryParams:({bukti:no_terima}),
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true", 
                 onLoadSuccess:function(data){                 
                     // pot_pilih();                                 
                      },
                 onClickRow:function(rowIndex, rowData){
                                rk=rowData.kd_rek5;
                                nrek=rowData.nm_rek5;
                                nila=rowData.nilai;                                
                                //dsimpan(rk,nrek,nila);
                                //pot_pilih();
                             },                                                              
                 columns:[[
                    {field:'ck',
                     title:'ck',
                     checkbox:true,
                     hidden:true},
                     {field:'id',
                      title:'ID',
                      hidden:true           
                    },                    
                    {field:'kd_rek5',
                     title:'Rekening',
                     width:100,
                     align:'left'
                    },
                    {field:'nm_rek5',
                     title:'Nama Rekening',
                     width:350
                    },  
                    {field:'ntpn',
                     title:'NTPN',
                     width:200
                    },                                    
                    {field:'nilai',
                     title:'Nilai',
                     width:100,
                     align:'right'
                     }                      
                ]],
                 onDblClickRow:function(rowIndex, rowData){
                    $("#pot").datagrid("selectAll");
                    $("#editck").attr("Value",rowData.ck);
                    $("#editid").attr("Value",rowData.id);
                    $("#editrek").attr("Value",rowData.kd_rek5);
                    $("#editnm").attr("Value",rowData.nm_rek5);
                    $("#editnilai").attr("Value",rowData.nilai);

                    $("#dialog-modal-edit").dialog('open');
                }               
            });
        load_sum_pot1();

        });
        }
        
    function perbaiki(){
        var editck = document.getElementById('editck').value;
        var editid  = document.getElementById('editid').value;
        var editrek = document.getElementById('editrek').value;
        var editnm  = document.getElementById('editnm').value;
        var editntpn= document.getElementById('editntpn').value;
        var editnilai= document.getElementById('editnilai').value;

        var cek_ntpn = editntpn.length;

/*        if(cek_ntpn!=16){
          alert('Karakter NTPN tidak sesuai, silahkan perbaiki kode NTPN...');
          exit();
        }*/

        $('#pot').edatagrid('updateRow',{index:editid,row:{ kd_rek5:editrek, nm_rek5:editnm, ntpn:editntpn, nilai:editnilai}});
        $("#editntpn").attr("Value",'');
        $("#dialog-modal-edit").dialog('close');
        
    }       
        function load_sum_pot1(){                
        //var no_bukti = document.getElementById('no_bukti').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({bukti:no_terima}),
            url:"<?php echo base_url(); ?>index.php/setor/load_trm_pot",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                    $("#rektotal1").attr("value",n['rektotal1']);
                });
            }
         });
        });
    }
        
        
        
         function detpotong(bukti){
         $(function(){   


             $('#pot').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/setor/pot_setor',
                queryParams:({bukti:bukti}),
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                                                       
                 columns:[[
                    {field:'ck',
                     title:'ck',
                     checkbox:true,
                     hidden:true},
                     {field:'id',
                      title:'ID',
                      hidden:true           
                    },                    
                    {field:'kd_rek5',
                     title:'Rekening',
                     width:100,
                     align:'left'
                    },
                    {field:'nm_rek5',
                     title:'Nama Rekening',
                     width:350
                    },  
                    {field:'ntpn',
                     title:'NTPN',
                     width:200
                    },                                      
                    {field:'nilai',
                     title:'Nilai',
                     width:100,
                     align:'right'
                     }                      
                ]],
                onDblClickRow:function(rowIndex, rowData){
                    $("#pot").datagrid("selectAll");
                    $("#editck").attr("Value",rowData.ck);
                    $("#editid").attr("Value",rowData.id);


                    $("#editrek").attr("Value",rowData.kd_rek5);
                    $("#editntpn").attr("Value",rowData.ntpn);
                    $("#editnm").attr("Value",rowData.nm_rek5);
                    $("#editnilai").attr("Value",rowData.nilai);

                    $("#dialog-modal-edit").dialog('open');
                }
            });


        });
        }
        
        
        function get(dns,nm,jns,npwp,ket,kd_giat,nm_giat,no_sp2d,kd_rek,nm_rek,alamat,dir,rekanan){
        $("#dn").attr("value",dns);
        $("#npwp").attr("Value",npwp);
        $("#nmskpd").attr("Value",nm);
        $("#beban").attr("value",jns);
        $("#ketentuan").attr("Value",ket);
        $("#nm_giat").attr("Value",nm_giat);
        $("#nm_rek").attr("Value",nm_rek);
        $("#alamat").attr("Value",alamat);
        $("#dir").attr("Value",dir);
        $("#no_sp2d").combogrid("setValue",no_sp2d);
        $("#kd_giat").combogrid("setValue",kd_giat);
        $("#kd_rek").combogrid("setValue",kd_rek);
        $("#rekanan").combogrid("setValue",rekanan);

        }
                  
          
        function getpot(bukti,trm,tgl,st,ket,no_sp2d,beban,kd_kegiatan,nm_kegiatan,nmrekan,pimpinan,alamat,npwp,no_nnt,npwpd,no_kas_str,kd_rek6,nm_rek6){
                 
            //alert(no_bukti+no_sp2d+tgl_bukti+status+ket);
        $("#no_bukti").attr("value",bukti);
        $("#no_simpan").attr("value",bukti);
        $("#trmpot").combogrid("setValue",trm);
        $("#trmpot1").combogrid("setValue",trm);
        $("#no_sp2d").combogrid("setValue",no_sp2d);
        $("#kd_giat").combogrid("setValue",kd_kegiatan);
        $("#kd_rek").combogrid("setValue",kd_rek6);
        $("#rekanan").combogrid("setValue",nmrekan);
        $("#trmpot_lama").attr("value",trm);
        $("#dir").attr("value",pimpinan);
        $("#nm_rek").attr("value",nm_rek6);
        $("#npwp").attr("value",npwp);
        $("#nm_giat").attr("value",nm_giat);
        $("#beban").attr("value",beban);
        $("#dd").datebox("setValue",tgl);
        $("#ketentuan").attr("value",ket);
        $("#nonnt").attr("value",no_nnt);
        $("#npwpd").attr("value",npwpd);
        $("#no_kas_str").attr("value",no_kas_str);
        lcstatus = 'edit';
        tombol(st);                   
        }
        
        
        function kosong(){
        $("#no_bukti").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#trmpot_lama").attr("value",'');
        $("#dd").datebox("setValue",'');
        $("#trmpot").combogrid("setValue",'');        
        $("#dn").attr("value",'');        
        $("#beban").attr("Value",'');
        $("#npwp").attr("Value",'');        
        $("#nmskpd").attr("Value",'');
        $("#ketentuan").attr("value",'');  
        $("#nm_giat").attr("Value",'');
        $("#nm_rek").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#dir").attr("Value",'');
        $("#no_sp2d").combogrid("setValue",'');
        $("#kd_giat").combogrid("setValue",'');
        $("#kd_rek").combogrid("setValue",'');
        $("#rekanan").combogrid("setValue",'');
        $("#nonnt").attr("value",'');
        $("#npwpd").attr("value",'');
        $("#nokas_str").attr("value",'');
        lcstatus='tambah';
        get_nourut();
        
        document.getElementById("p1").innerHTML="";        
        //pot1();
        $("#trmpot").combogrid("clear");
        //tombolnew();      
        }
        
        function get_nourut()
        {
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/tunai/no_urut',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                    // $("#no_kas").attr("value",data.no_urut);
                                        $("#no_bukti").attr("value",data.no_urut);
                                      }                                     
            });  
        }

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $("#dialog-modal").dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
      
        });
       
     function cetak(){
        $("#dialog-modal").dialog('open');
    } 

    function keluar(){
        $("#dialog-modal").dialog('close');
    }   
     function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#pot_out').edatagrid({
           url: '<?php echo base_url(); ?>/index.php/setor/load_pot_out',
         queryParams:({cari:kriteria})
        });        
     });
    }
    
    
    function hsimpan(){    

        var no_bku = document.getElementById('no_simpan').value;
        var trmpot_lama = document.getElementById('trmpot_lama').value;
        var a = document.getElementById('no_bukti').value;
        var b = $('#dd').datebox('getValue');  
        var c = document.getElementById('beban').value;       
        var d = document.getElementById('ketentuan').value;       
        var e = document.getElementById('nmskpd').value;
        var f = document.getElementById('dn').value;
        var g = document.getElementById('npwp').value;
        var h = angka(document.getElementById('rektotal1').value);
        var i = document.getElementById('nm_giat').value;
        var j = document.getElementById('nm_rek').value;
        var k = document.getElementById('alamat').value;
        var l = document.getElementById('dir').value;
        var rek_kd = document.getElementById('kd_rek2').value;
        var rek_nm = document.getElementById('nm_rek2').value;
        var m = $("#no_sp2d").combogrid("getValue") ; 
        var n = $("#kd_giat").combogrid("getValue") ; 
        var o = $("#kd_rek").combogrid("getValue") ; 
        var p = $("#rekanan").combogrid("getValue") ; 
       
/*        if(p.length<5){
          alert("Rekanan harap diisi !");
          exit();
        }*/
 


        var no_terima = $("#trmpot").combogrid("getValue") ;
        var nnt = document.getElementById('nonnt').value;       
        var nokas_str = document.getElementById('nokas_str').value;       
        var npwpd = document.getElementById('npwpd').value;     


       var tahun_input = b.substring(0, 4);
        if (tahun_input != tahun_anggaran){
            alert('Tahun tidak sama dengan tahun Anggaran');
            exit();
        }
        if(c==''){
            alert('Pilih Beban terlebih dahulu!');
            exit();
        }
        if(lcstatus == 'tambah'){
        $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhstrpot',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tunai/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        alert("Nomor Telah Dipakai!");
                        document.getElementById("nomor").focus();
                        exit();
                        } 
                        if(status_cek==0){
                        alert("Nomor Bisa dipakai");
//-----
       $(function(){      
         $.ajax({
            type: 'POST',
            data: ({no_bukti:a,tgl_bukti:b,no_terima:no_terima,jns_spp:c,ket:d,kd_skpd:f,nm_skpd:e,npwp:g,nilai:h,nm_giat:i,nm_rek:j,alamat:k,dir:l,no_sp2d:m,kd_giat:n,kd_rek:o,rekanan:p,nnt:nnt,nokas_str:nokas_str,npwpd:npwpd,rek0:rek_kd,nmrek:rek_nm}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/setor/simpan_strpot",
            success:function(data){

                      




                if (data = 1){


$(function(){
      $("#pot").datagrid("selectAll");
                            var rows = $('#pot').edatagrid('getSelections');       
                        for(var p=0;p<rows.length;p++){
                            rek5 = rows[p].kd_rek5;
                            ntpnoke = rows[p].ntpn;
                            
                          
                            $.ajax({
                                type: 'POST',
                                data:({ntpn:ntpnoke,no_bukti:a,kd_rek5:rek5,kd_skpd:f}),
                                dataType: 'json',
                                url:"<?php echo base_url(); ?>index.php/setor/simpan_strpot_update_ntpn",
                                success:function(data){
                                    //alert(data);
                                }
                            });


                       }

});

                    alert('Data Berhasil Tersimpan');
                    $('#pot_out').edatagrid('reload')
                    
                    $("#no_simpan").attr("value",a);
                    $("#trmpot_lama").attr("value",no_terima);
                    $('#pot_out').edatagrid('reload');
                    lcstatus='edit'
                                        
                    $(function(){
                    $('#trmpot').combogrid({                    
                    url: '<?php echo base_url(); ?>/index.php/setor/trmpot_',  
                    idField:'no_bukti',                    
                    textField:'no_bukti'                                      
                    });
                    });                 
                    
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                        
                }
            }
         });
        });
    //------
    }
        }
        });
        });
        
        
            
        } else {
//alert(z);
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:'trhstrpot',field:'no_bukti'}),
                    url: '<?php echo base_url(); ?>/index.php/tunai/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && a!=no_bku){
                        alert("Nomor Telah Dipakai!");
                        exit();
                        } 
                        if(status_cek==0 || a==no_bku){
                        alert("Nomor Bisa dipakai");
    //------
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({no_bukti:a,tgl_bukti:b,no_terima:no_terima,jns_spp:c,ket:d,kd_skpd:f,nm_skpd:e,npwp:g,nilai:h,nm_giat:i,nm_rek:j,alamat:k,dir:l,no_sp2d:m,kd_giat:n,kd_rek:o,rekanan:p,no_bku:no_bku,trmpot_lama:trmpot_lama,nnt:nnt,nokas_str:nokas_str,npwpd:npwpd,rek0:rek_kd,nmrek:rek_nm}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/setor/simpan_strpot_edit",
            success:function(data){
                if (data = 1){

$(function(){
      $("#pot").datagrid("selectAll");
                            var rows = $('#pot').edatagrid('getSelections');       
                        for(var p=0;p<rows.length;p++){
                            rek5 = rows[p].kd_rek5;
                            ntpnoke = rows[p].ntpn;
                            
                          
                            $.ajax({
                                type: 'POST',
                                data:({ntpn:ntpnoke,no_bukti:a,kd_rek5:rek5,kd_skpd:f}),
                                dataType: 'json',
                                url:"<?php echo base_url(); ?>index.php/setor/simpan_strpot_update_ntpn",
                                success:function(data){
                                    //alert(data);
                                }
                            });


                       }

});



                    
                    alert('Data Berhasil Tersimpan');
                    $('#pot_out').edatagrid('reload')
                    $("#no_simpan").attr("value",a);
                    $("#trmpot_lama").attr("value",no_terima);
                    $('#pot_out').edatagrid('reload');
                    lcstatus = 'edit';
                    
                    $(function(){
                    $('#trmpot').combogrid({                    
                    url: '<?php echo base_url(); ?>/index.php/setor/trmpot_',  
                    idField:'no_bukti',                    
                    textField:'no_bukti'                                      
                    });
                    }); 
                    
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        }); 
    //----------
        }
            }
        });
     });
        
        }
        
        
        
        }
        
        
        


                  
         function hhapus(){             
            var nbukti = document.getElementById("no_simpan").value;            
            var no_terima = $("#trmpot").combogrid("getValue") ;

            var urll= '<?php echo base_url(); ?>/index.php/setor/hapus_strpot';                          
            if (nbukti !=''){
                var del=confirm('Anda yakin akan menghapus Setor Potongan NO  '+nbukti+'  ?');
                if  (del==true){
                    $(document).ready(function(){
                    $.post(urll,({no:nbukti,no_terima:no_terima}),function(data){
                      alert('Berhasil');
                      status = data;            
                    });
                    });
                
                }
                } 
                
            $(function(){
                    $('#trmpot').combogrid({                    
                    url: '<?php echo base_url(); ?>/index.php/setor/trmpot_',  
                    idField:'no_bukti',                    
                    textField:'no_bukti'                                      
                    });
                    });     
                
        }
        
        
        
     
        
        function load_sum_pot(){                
        var no_bukti = document.getElementById('no_bukti').value;              
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({bukti:no_bukti}),
            url:"<?php echo base_url(); ?>index.php/setor/load_str_pot",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                    $("#rektotal1").attr("value",n['rektotal1']);
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
     
     
     function tombol(st){  
     if (st=='1'){
     $('#save').hide();
     $('#del').hide();
     $('#poto').hide();       
     document.getElementById("p1").innerHTML="Sudah di CAIRKAN!!";
     } else {
     $('#save').show();
     $('#del').show();
     $('#poto').show();     
     document.getElementById("p1").innerHTML="";
     }
    }
    
    function tombolnew(){  
    
     $('#save').show();
     $('#del').show();   
    }
    
    function openWindow( url )
        {
      
        var no =kode.split("/").join("123456789");
       // alert(no);
        window.open(url+'/'+no+'/'+dns, '_blank');
        window.focus();
        }
    function cek(){
        var lcno = document.getElementById('no_bukti').value;
        var b = $('#dd').datebox('getValue');


        $("#pot").datagrid("selectAll");
        var rows = $('#pot').edatagrid('getSelections');       
            for(var p=0;p<rows.length;p++){
                ntpnoke = rows[p].ntpn;
            }

        var cek_ntpn = ntpnoke.length;
                            


        //alert(lcno);
            if(lcno !='' && b !=''){
               hsimpan();
               //detsimpan();               
            } else {
                alert('Nomor Kas atau Tanggal Tidak Boleh kosong')
                document.getElementById('no_bukti').focus();
                exit();
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

<h3><a href="#" id="section1" onclick="javascript:$('#pot_out').edatagrid('reload')">List Setor Potongan</a></h3>
    <div>
    <p align="right">
    <button class="button" onclick="javascript:section2();kosong();"><i class="fa fa-tambah"></i> Tambah</button>     
                          
        <input type="text" value="" class="input" placeholder="Pencarian" onkeyup="javascript:cari();" style="display: inline" id="txtcari"/>
        <table id="pot_out" title="List " style="width:1024px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>

<h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')" >Input Setor Potongan</a></h3>
   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p>

               
<table border='0' width="100%" style="font-size:11px" >
 <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/></td>
                <td colspan = '2' style="border-bottom: double 1px red;border-top: double 1px red;">&nbsp;&nbsp;<i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr> 
 <tr>
   <td >No Bukti </td>
   <td><input type="text" class="input" style="display: inline; width: 200px" name="no_bukti" id="no_bukti" onclick="javascript:select();" /></td>
   <td>Tanggal </td>
   <td><input id="dd" name="dd" style="width: 200px" type="text" /></td>
 </tr>
 <tr>
   <td >No Terima </td>
   <td><input type="text" style="width: 200px" name="trmpot" id="trmpot"  /> &nbsp; <input readonly="true" type="text" name="trmpot_lama" id="trmpot_lama" style="border:0" /></td>
   <td>Beban</td>
   <td><select class="select" style="display: inline; width: 200px" name="beban" id="beban" >
     <option value="">...Pilih Jenis Beban... </option>
     <option value="1">UP</option>
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS GAJI</option>
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
   </select></td>
 </tr>
 <tr>
                <td>No. SP2D</td>
                <td colspan = "3"><input readonly="true" border="0" type="text" id="no_sp2d"   name="no_sp2d" style="width:200px;"/></td>
           </tr>
           <tr>
                <td>Kd Kegiatan</td>
                <td colspan = "3"><input type="text" id="kd_giat" name="kd_giat" style="width:200px;"/>&nbsp;&nbsp;
                <input type="text" id="nm_giat" name="nm_giat" readonly="true" style="width:300px; border:0"/></td>
           </tr>
           <tr>
                <td>Rekening</td>
                <td colspan = "3"><input type="text" id="kd_rek" name="kd_rek" style="width:200px;"/>&nbsp;&nbsp;
                <input type="text" id="nm_rek" name="nm_rek" readonly="true" style="width:300px; border:0"/></td>
           </tr>
           
           
           <tr>
                <td>Rekanan </td>
                <td><input type="text" id="rekanan"   name="rekanan" style="width:200px;"/></td>
                <td>Pimpinan </td> 
                <td><input type="text" class="input" id="dir" name="dir" style="width:200px;"/></td>
           </tr>
           
           <tr>
            <td>ALamat Perusahaan</td>
                <td colspan='3'><textarea class="textarea" id="alamat" style="width:600px; height: 30px;" /></textarea></td>
           </tr>
           
            <tr>
                <td>SKPD</td>
                <td colspan = "3"><input class="input" type="text" id="dn" name="dn" style="width:200px; display: inline;"/>&nbsp;&nbsp;
                <input type="text" id="nmskpd" name="nmskpd" readonly="true" style="width:350px; border:0"/></td>
           </tr>
           
           <tr>
                <td>NPWP </td>
                <td colspan="3"><input class="input" type="text" id="npwp"   name="npwp" style="width:200px;"/></td>
                
           </tr>
            <tr>
                <td>No NTPN </td>
                <td colspan="3"><input class="input" type="text" id="nonnt"   name="nonnt" style="width:200px; display: inline;" disabled /> Silahkan Input di List Potongan</td>                
           </tr> 
           <tr>
                <td>NPWPD </td>
                <td colspan="3"><input class="input" type="text" id="npwpd"   name="npwpd" style="width:200px;"/></td>                
           </tr> 
           <tr>
                <td>NO KAS PENYETORAN </td>
                <td colspan="3"><input type="text" class="input" id="nokas_str"   name="nokas_str" style="width:200px;"/></td>                
           </tr> 
            <tr>
            <td>Katerangan</td>
                <td colspan='3'><textarea  class="textarea" id="ketentuan" style="width:600px; height: 30px;" /></textarea></td>
           </tr>      
            <tr>
                <td colspan="4" align="right">
                <button id="save" class="button-biru" onclick="javascript:cek();"><i class="fa fa-save"></i> Simpan</button>
                <button id="del" class="button-merah" onclick="javascript:hhapus();"><i class="fa fa-hapus"></i> Hapus</button>
                <button  class="button-abu" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>
                             
                </td> <!--<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:();">cetak</a>-->               
            </tr>
    </table>
    
        <table id="pot" title="List Potongan" style="width:1024px;height:250px;" >  
        </table><br/>
       
    <!-- <?php echo form_close(); ?> -->
    
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Nomor Terima: 
        <input class="right" id="trmpot1" name="trmpot1" type="hidden" readonly="true"  /></td>
        &nbsp;&nbsp;&nbsp;
        <B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" >
        <input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" >
   </p>
    </div>
    

   
  
</div>

</div> 

<div align="" id="dialog-modal-edit" title="Edit Rincian Rekening">
    <table align="">
        <tr>
            <td>Rekening</td> <input type="text" name="editck" id="editck" hidden="true"> <input type="text" name="editid" id="editid" hidden="true">
            <td><input type="text" class="input" name="editrek" id="editrek" readonly="true" style="width: 350px"></td>
        </tr>
        <tr>
            <td>Nama Rekening</td>
            <td><input type="text" class="input" name="editnm" id="editnm" readonly="true" style="width: 350px"></td>
        </tr>
        <tr>
            <td>NTPN</td>
            <td><input type="text" class="input" name="editntpn" id="editntpn" style="width: 350px"></td>
        </tr>
        <tr>
            <td>Nilai</td>
            <td><input type="text" class="input" name="editnilai" id="editnilai" readonly="true" style="width: 350px"></td>
        </tr>
        <tr>
            <td ></td>
            <td align="right"><br><button class="button" onclick="javascript:perbaiki();"><i class="fa fa-refresh"></i> Perbaiki</button></td>
        </tr>
    </table>
    
</div>
 <input type="text" name="kd_rek2" id="kd_rek2" hidden>
 <input type="text" name="nm_rek2" id="nm_rek2" hidden>     
</body>
</html>