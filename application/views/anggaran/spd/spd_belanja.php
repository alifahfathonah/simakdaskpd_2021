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
    
    var kode  = '';
    var giat  = ''; 
    var jenis = '';
    var nomor = '';  
    var cid   = 0; 
    var ctk   = ''; 
    var s_tox='edit';   
    var status_spd  = 0;
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 600,
                width: 700,
                modal: true,
                autoOpen:false                
            });
      
             $( "#dialog-cetak" ).dialog({
                height: 380, 
                width: 500,
                modal: true,
                autoOpen:false                
            });
            get_tahun();
        });    
    
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
    
        function get_skpd(kd_s='',nm='') {
                $("#skpd").attr("value",kd_s);
                $("#nmskpd").attr("value",nm);
                kode = kd_s;
                $('#bendahara').combogrid({url:'<?php echo base_url(); ?>index.php/anggaran_spd/load_bendahara_p/'+kode,
                queryParams:({kode:kode})
                }); 

        }
    
     $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/anggaran_spd/load_spd_bl',
        queryParams:({jenis:'<?php echo $jenis ?>'}),
        idField:'id',
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        pagination:"true",
        nowrap:true,
      
        columns:[[
      {field:'ck',
        title:'',
        width:10,
      checkbox:'true'},
          {field:'no_spd',
        title:'Nomor SPD',
        width:10},
            {field:'tgl_spd',
        title:'Tanggal',
        width:5},
            {field:'nm_skpd',
        title:'Nama SKPD',
        width:12,
            align:"left"},
            {field:'nm_beban',
        title:'Jenis Beban',
        width:10,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_spd;
          tgl   = rowData.tgl_spd;
          kode  = rowData.kd_skpd;
          nama  = rowData.nm_skpd;
          ketentuan  = rowData.ketentuan;
          bulan1= rowData.bulan_awal;
          bulan2= rowData.bulan_akhir;
          jns   = rowData.jns_beban; 
          nip   = rowData.nip; 
          nama_bend  = rowData.nama_bend; 
          status_spd  = rowData.status; 
          tot   = angka(rowData.total);
          get(nomor,tgl,kode,nama,bulan1,bulan2,jns,tot,ketentuan,nip,status_spd);
          s_tox='edit';
                
        },
        onDblClickRow:function(rowIndex,rowData){         
          nomor = rowData.no_spd;
          tgl   = rowData.tgl_spd;
          kode  = rowData.kd_skpd;
          nama  = rowData.nm_skpd;
          ketentuan  = rowData.ketentuan;
          bulan1= rowData.bulan_awal;
          bulan2= rowData.bulan_akhir;
          jns   = rowData.jns_beban; 
          nip   = rowData.nip; 
          nama_bend  = rowData.nama_bend; 
          status_spd  = rowData.status; 
          tot   = angka(rowData.total);

          get(nomor,tgl,kode,nama,bulan1,bulan2,jns,tot,ketentuan,nip,status_spd);
          load_detail();
          tombol(status_spd);
          jumlah_detail();
          section2();
          $('#cek_edit').attr('value','edit');
                      
        }
    });
    
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            }
        });

        $(function(){
            $('#skpd').combogrid({  
                  panelWidth:700,  
                  idField:'kd_skpd',  
                  textField:'kd_skpd',  
                  mode:'remote',
                  url:'<?php echo base_url(); ?>index.php/anggaran_spd/skpduser_bp',  
                  columns:[[  
                      {field:'kd_skpd',title:'Kode SKPD',width:150},  
                      {field:'nm_skpd',title:'Nama SKPD',width:700}    
                  ]],
                  onSelect:function(rowIndex,rowData){
                      skpd = rowData.kd_skpd;
                      nmskpd = rowData.nm_skpd;                      
                      get_skpd(skpd,nmskpd);
                      cekbln_akhir(skpd);
                      cekangxx(skpd);
                  },
                  onChange:function(rowIndex,rowData){
                    kosong();
                            
                  }
            });
        });
            
     
    $('#bendahara').combogrid({  
           panelWidth:700,  
           idField:'id_ttd',  
           textField:'nip',  
           mode:'remote',
           columns:[[  
               {field:'nip',title:'NIP',width:150},  
               {field:'nama',title:'Nama',width:500}    
           ]],  
           onSelect:function(rowIndex,rowData){
                    $("#nama_bend").attr("value",rowData.nama);
            }   
     });
    
    
     
    $('#bendahara_ppkd').combogrid({  
           panelWidth:700,  
           idField:'nip',  
           textField:'nip',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/anggaran_spd/load_ttd_bud',
           columns:[[  
               {field:'nip',title:'NIP',width:150},  
               {field:'nama',title:'Nama',width:500}    
           ]],  onSelect:function(rowIndex,rowData){
                    $("#nama_ppkd").attr("value",rowData.nama);
                    $("#jabatan_ppkd").attr("value",rowData.jabatan);
                    $("#pangkat_ppkd").attr("value",rowData.pangkat);
                    }   
                });
        
        
    
        $('#giat').combogrid({  
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',                      
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Sub Kegiatan',width:140},  
               {field:'nm_kegiatan',title:'Nama Sub Kegiatan',width:400},
               {field:'lalu',title:'SPD Lalu',width:100,align:'right'},
               {field:'total',title:'Anggaran',width:100,align:'right'}               
           ]],  
           onSelect:function(rowIndex,rowData){
               idxGiat = rowIndex;               
               giat = rowData.kd_kegiatan;
               $("#nmgiat").attr("value",rowData.nm_kegiatan);
               $('#prog').attr("value",rowData.kd_program);
               $('#nmprog').attr("value",rowData.nm_program);
               $('#anggaran').attr("value",number_format(rowData.total,2,'.',','));                
               $("#lalu").attr("value",number_format(rowData.lalu,2,'.',','));
               document.getElementById('nilai').focus();                                                               
           }  
        });
        
        
        $('#dg1').edatagrid({  
      idField:'idx',
            toolbar:'#toolbar',
            rownumbers:"true",            
            singleSelect:"true",
            autoRowHeight:"false",
            nowrap:true,
            columns:[[                
              {field:'no_spd',
            title:'Nomor SPD',        
                hidden:"true"},                
                {field:'kd_kegiatan', 
            title:'Kode Sub Kegiatan',
            width:100},
                {field:'nm_kegiatan',
            title:'Nama Sub Kegiatan',
            width:280},
                {field:'nilai',
            title:'Nilai SPD',
            width:130,
                align:"right"},
                {field:'lalu',
            title:'Realisasi SPD',
            width:130,
                align:"right"},
                {field:'anggaran',
            title:'Anggaran',
            width:130,
                align:"right"},
              {field:'kd_program',
            title:'Kode Program',       
                hidden:"true",  
                width:0},
              {field:'nm_program',
            title:'Nama Program',       
                hidden:"true",
                width:0}                
            ]],
            onSelect:function(rowIndex,rowData){                    
                    idx = rowIndex;
                    nilx = rowData.nilai;
            },
            onDblClickRow:function(rowIndex,rowData){           
              kdkegiatan    = rowData.kd_kegiatan;            
              nmkegiatan    = rowData.nm_kegiatan;
              nlalu         = rowData.lalu;
              nilai1        = rowData.nilai;
              nilai_ag      = rowData.anggaran;
              edit_rekening(kdkegiatan,nmkegiatan,nilai1,nlalu,nilai_ag);  
            }     
        }); 
        
        
        $('#dg2').edatagrid({  
            toolbar:'#toolbar',
            rownumbers:"true",             
            singleSelect:"true",
            autoRowHeight:"false",
            nowrap:true,
            onSelect:function(rowIndex,rowData){                    
                    idx = rowIndex;
                    nilx = rowData.nilai;
            },                                                     
            columns:[[  
                {field:'hapus',
            title:'Hapus',
                width:35,
                align:"center",
                formatter:function(value,rec){                                                                       
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';                  
                    }                
                },          
                {field:'no_spd',
            title:'Nomor SPD',        
                hidden:"true"},                
                {field:'kd_kegiatan',
            title:'Kode Sub Kegiatan',
            width:100},
                {field:'nm_kegiatan',
            title:'Nama Sub Kegiatan',
            width:300},
                {field:'nilai',
            title:'Nilai SPD',
            width:130,
                align:"right"},
                {field:'lalu',
            title:'Telah di SPD kan',
            width:130,
                align:"right"},
                {field:'anggaran',
            title:'anggaran',
            width:130,
                align:"right"},
              {field:'kd_program',
            title:'Kode Program',       
                hidden:"true",  
                width:10},
              {field:'nm_program',
            title:'Nama Program',       
                hidden:"true",
                width:10}                       
            ]]
        });      
    });        
    
    
    
    

function cekangxx(skpd){
          //function get_total_angkas(bulan1,bulan2){
          var skp = skpd;
          var jn ='52';
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/anggaran_spd/jumlah_detail_angkas_spd_baru/',
            type: "POST",
            dataType:"json",   
                data: ({jn:jn,skp:skp}),                      
            success:function(data){
                        $("#total_angkas").attr("value",number_format(data.total));
                        }                                     
          });


    }
    
    
    function filter_giat(){
        var vgiat = '';
        $('#dg1').edatagrid('selectAll');
        var rows = $('#dg1').edatagrid('getSelections');                   
    for(var i=0;i<rows.length;i++){
      fgiat = "'"+rows[i].kd_kegiatan+"'";
            if (i>0){
                vgiat = vgiat +","+fgiat;
            }else{
                vgiat=fgiat;
            }
            
        }   
        var cno = document.getElementById('nomor').value;                                                          
        $('#dg1').edatagrid('unselectAll');   
        $('#giat').combogrid({  
             url:'<?php echo base_url(); ?>index.php/rka/load_trskpd',
             queryParams:({kode:kode,jenis:jenis,giat:vgiat,no:cno})
        });
    }
    


    
    function load_detail(){
        
    var kk = document.getElementById("nomor").value; 
    var jenis = document.getElementById('jenis').value;   
    var kode  = document.getElementById('skpd').value;
    var tgl1 = $('#tanggal').datebox('getValue');
    var bln1  = angka(document.getElementById('bulan1').value);
        
    $('#dg1').edatagrid({
      idField:'id',            
      rownumbers:"true", 
      fitColumns:"true",
      singleSelect:"true",
      autoRowHeight:"false",
      pagination:"true",
      pageList:[10,20,30,40,50,100,300],
      nowrap:true,  
      url: '<?php echo base_url(); ?>/index.php/anggaran_spd/load_dspd_ag_bl',
            queryParams:({ no:kk,jenis:jenis,skpd:kode,tgl:tgl1,cbln1:bln1 })
    });
    $('#dg1').edatagrid('reload');
    
    }
    

    
    function jumlah_detail()
        {
            var kk = document.getElementById("nomor").value;
            
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/rka/jumlah_detail_spd/',
            type: "POST",
            dataType:"json",   
                data: ({cno_spd:kk}),                      
            success:function(data){
                        $("#total").attr("value",number_format(data.total,2,'.',','));
                        $("#total1").attr("value",number_format(data.total,2,'.',','));
                        }                                     
          });
        } 
    
    
    function load_detail_kosong(){
        var no_kos = '' ;
      $('#dg1').edatagrid({
      url: '<?php echo base_url(); ?>/index.php/rka/load_dspd',
            queryParams:({ no:no_kos })
          /*   columns:[[                
              {field:'no_spd',
            title:'Nomor SPD',        
                hidden:"true"},                
                {field:'kd_kegiatan', 
            title:'Kode Sub Kegiatan',
            width:160},
                {field:'nm_kegiatan',
            title:'Nama Sub Kegiatan',
            width:280},
                {field:'nilai',
            title:'Nilai Rupiah',
            width:130,
                align:"right"},
                {field:'lalu',
            title:'Telah Di SPD kan',
            width:130,
                align:"right"},
                {field:'anggaran',
            title:'anggaran',
            width:130,
                align:"right"},
              {field:'kd_program',
            title:'Kode Program',       
                hidden:"true",  
                width:0},
              {field:'nm_program',
            title:'Nama Program',       
                hidden:"true",
                width:0}                
            ]] */
    });
      var jenis = document.getElementById('jenis').value;   
       set_grid() ;
        }
    

    function load_detail2(){        
       $('#dg1').edatagrid('selectAll');
       var rows = $('#dg1').edatagrid('getSelections');             
    for(var p=0;p<rows.length;p++){
       no = rows[p].no_spd;                                                                    
           giat = rows[p].kd_kegiatan;
           nmgiat = rows[p].nm_kegiatan;
           prog = rows[p].kd_program;
           nmprog = rows[p].nm_program;
           nil = rows[p].nilai;
           lal = rows[p].lalu;
           ang = rows[p].anggaran;                                                                                                                                                                                                                                                                         
           $('#dg2').edatagrid('appendRow',{no_spd:no,kd_kegiatan:giat,nm_kegiatan:nmgiat,nilai:nil,lalu:lal,anggaran:ang,kd_program:prog,nm_program:nmprog});            
        }
        $('#dg1').edatagrid('unselectAll');
    } 
    
    function set_grid(){
        $('#dg1').edatagrid({                                                                   
        columns:[[
        {field:'id',
         title:'id',
         width:10,
         hidden:"true"},               
        {field:'no_spd',
            title:'Nomor SPD',        
                hidden:"true"},                
                {field:'kd_kegiatan',
            title:'Kode Sub Kegiatan',
            width:100},
                {field:'nm_kegiatan',
            title:'Nama Sub Kegiatan',
            width:300},
                {field:'nilai',
            title:'Nilai SPD',
            width:130,
                align:"right"},
                {field:'lalu',
            title:'Realisasi SPD',
            width:130,
                align:"right"},
                {field:'anggaran',
            title:'anggaran',
            width:130,
                align:"right"},
              {field:'kd_program',
            title:'Kode Program',       
                hidden:"true",  
                width:0},
              {field:'nm_program',
            title:'Nama Program',       
                hidden:"true",
                width:0} ,
    
            ]],onLoadSuccess:function(data){                       
                 },       
                    onDblClickRow:function(rowIndex,rowData){           
                      kdkegiatan       = rowData.kd_kegiatan;            
                      nmkegiatan       = rowData.nm_kegiatan;
                      nlalu   = rowData.lalu;
                      nilai1  = rowData.nilai;
                      nilai_ag=rowData.anggaran;
                      edit_rekening(kdkegiatan,nmkegiatan,nilai1,nlalu,nilai_ag,'','');  
                    }   
        });
    }
  
  function set_grid_rek(){
        $('#dg1').datagrid({                                                                   
            columns:[[
            
      {field:'id',
       title:'id',
       width:10,
       hidden:"true",
      },
               {field:'no_spd',
            title:'Nomor SPD',        
                hidden:"true"},                
                {field:'kd_rekening',
            title:'Kode Rekening',
            width:160},
                {field:'nm_rekening',
            title:'Nama Rekening',
            width:280},
                {field:'nilai',
            title:'Nilai SPD',
            width:130,
                align:"right"},
        {field:'kd_kegiatan',
            title:'Kode Sub Kegiatan',
             hidden:"true",width:160},
                {field:'nm_kegiatan',
            title:'Nama Sub Kegiatan',
             hidden:"true",width:280},
                {field:'lalu',
            title:'Realisasi SPD',
            width:130,
                align:"right"},
                {field:'anggaran',
            title:'anggaran',
            width:130,
                align:"right"},
              {field:'kd_program',
            title:'Kode Program',       
                hidden:"true",  
                width:0},
              {field:'nm_program',
            title:'Nama Program',       
                hidden:"true",
                width:0} ,
    
            ]],
      onLoadSuccess:function(data){                       
      //get_total();
                 },       
                    onDblClickRow:function(rowIndex,rowData){
                      kdkegiatan       = rowData.kd_kegiatan;            
                      nmkegiatan       = rowData.nm_kegiatan;
                      kdrek       = rowData.kd_rekening;            
                      nmrek       = rowData.nm_rekening;
                      nlalu   = rowData.lalu;
                      nilai1  = rowData.nilai;
                      nilai_ag=rowData.anggaran;
                      edit_rekening(kdkegiatan,nmkegiatan,nilai1,nlalu,nilai_ag,kdrek,nmrek);  
                    }
        });
    
    }
    
  
  function getRowIndex(target){
    var tr = $(target).closest('tr.datagrid-row');
    return parseInt(tr.attr('datagrid-row-index'));
  }
  
  function editrow(target){
    $('#dg1').datagrid('beginEdit', getRowIndex(target));
  }
  
  function deleterow(target){
    $.messager.confirm('Confirm','Anda Yakin?',function(r){
      if (r){
        $('#dg1').datagrid('deleteRow', getRowIndex(target));
      }
    });
  }
  
  function saverow(target){
    var rows = $('#dg1').datagrid('getSelected');
        cnil1 = rows.nilai;        
    $('#dg1').datagrid('endEdit', getRowIndex(target));
    
    var rows = $('#dg1').datagrid('getSelected');
        cnil = rows.nilai;
      
     totala = angka(document.getElementById('total').value); 

           selisih_ag = (angka(rows.anggaran)-(angka(rows.nilai)+angka(rows.lalu))); 
           selisih = (angka(cnil1)-angka(cnil));
       cccc = (totala-selisih);
            $('#total').attr('value',number_format(cccc,"2",".",","));
      
      if (selisih_ag<0){
         rows.nilai=0;         
         alert('Nilai Melebihi Anggaran');
       }
       
  }

   function reject(target){
            $('#dg1').datagrid('rejectChanges');
            target= undefined;
        }
  
  
  function cancelrow(target){
    $('#dg1').datagrid('cancelEdit', getRowIndex(target));
  }
  
  
  
  
  
    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();    
             $('#dg').edatagrid('reload');                                           
         });         

     }
     
     
     function section2(){
         $(document).ready(function(){                
             $('#section2').click(); 
             document.getElementById("nomor").focus();                                              
         });
  
     }
       
     
    function get(nomor,tgl,kode,nama,bulan1,bulan2,jns,tot,ketentuan,nip,st_b){
        $("#nomor").attr("value",nomor);
     $("#nomor_hide").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#total").attr("value",tot);
        $("#skpd").combogrid("setValue",kode);
        $("#bendahara").combogrid("setValue",nip);
        $("#nmskpd").attr("value",nama);
        $("#ketentuan").attr("value",ketentuan);
        $("#bulan1").attr("value",bulan1);
        $("#bulan2").attr("value",bulan2);
        $("#jenis").attr("value",jns);
     if (st_b=='1'){
       document.getElementById("p1").innerHTML="<b style='font-size:17px;color: #0ad13f;'>SPD AKTIF</b>";
     } else {
          document.getElementById("p1").innerHTML="<b style='font-size:17px;color: red;'>SPD TIDAK AKTIF</b>";
     }

    }
    
    function kosong(){
        status_spd=0;
        cdate = '<?php echo date("Y-m-d"); ?>';
        s_tox='tambah';
        $("#cek_edit").attr("value",'tambah');        
        $("#nomor").attr("value",'');
        $("#nama_bend").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#tanggal").datebox("setValue",cdate);
        $("#bendahara").combogrid("setValue",'');

        $("#bulan1").attr("value",0);
        $("#bulan2").attr("value",0);

        $("#jenis").attr("value",'5');
        $("#ketentuan").attr("value",'');
        $("#bendahara").attr("value",'');
        $("#ketentuan").attr("value",'');
        $("#pengajuan").attr("value",'');
        var kode = '';
        var nomor = '';
        $('#giat').combogrid('setValue','');
        $('#nilai').attr('value','0');
        $('#total').attr('value','0');
        tombol(status_spd);
        load_detail_kosong() ;
        document.getElementById("nomor").focus();       
    }
  
  function get_spd(){
      $('#cek_edit').attr('value','tambah');
      var bln1  = document.getElementById('bulan1').value;
      var bln2  = document.getElementById('bulan2').value;
      var hasiltw ="";
      if(bln1==1){
        hasiltw = "I";
      }else if(bln1==4){
        hasiltw = "II";
      }else if(bln1==7){
        hasiltw = "III";
      }else if(bln1==10){
        hasiltw = "IV";
      }
      
      var jns_ang  = document.getElementById('jns_ang').value;
      if(jns_ang=='belanja'){
          var judul="BELANJA";
      }else{
          var judul="PEMBIAYAAN";
      }

      $.ajax({
            url:'<?php echo base_url(); ?>index.php/anggaran_spd/config_spd_nomor',
            type: "POST",
            dataType:"json",                         
            success:function(data){
              no_spd = data.nomor;
              var inisial = no_spd + "/SPD-"+hasiltw+"/"+judul+"/BKD/"+tahun_anggaran;
              $("#nomor").attr("value",inisial);
              $("#nomor_u").attr("value",no_spd);
            }                                     
      });
  }
  

    function aktif_spd(){
        var cno = document.getElementById('nomor').value;
        var cno_hide = document.getElementById('nomor_hide').value;
        var cskpd = document.getElementById('skpd').value;
        var status_spd_aktif=0;
        var status_cek=0;



    $(document).ready(function(){
      $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trhspp',field:'no_spd',no:cno}),
                    url: '<?php echo base_url(); ?>/index.php/anggaran_spd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
            if (status_spd=='1'){

              if(status_cek==1){
                alert("Nomor SPD Telah Dipakai di SPP tidak bisa di Non Aktifkan! ");
                return;
                //abort();
                
              };
            }  else {
               status_spd_aktif=1;
            }
            
            $.ajax({
              type: "POST",    
              dataType:'json',                            
              data: ({tabel:'trhspd',mode_tox:'edit',no:cno,status_spd:status_spd_aktif,kd_skpd:cskpd}),
              url: '<?php echo base_url(); ?>/index.php/anggaran_spd/update_sts_spd',
              success:function(data){
                status = data;
                  
                 if (status=='5'){
                  alert('Data Gagal Tersimpan...!!!');          
                } else{ 
                  if (status=='1'){
                    $('#dg').edatagrid('reload');
                     document.getElementById("id_aktif").innerHTML="NON Aktifkan SPD";
                     document.getElementById("p1").innerHTML="<b style='font-size:17px;color: #0ad13f;'>SPD AKTIF</b>";
                     status_spd=1;
                     tombol(status_spd);
                  //  section1();
                  }else{
                    
                      $('#dg').edatagrid('reload');
                       document.getElementById("id_aktif").innerHTML="Aktifkan SPD";
                       document.getElementById("p1").innerHTML="<b style='font-size:17px;color: red;'>SPD TIDAK AKTIF</b>";
                       status_spd=0;
                       tombol(status_spd);
                  //  section1();
                    
                  }
                } 


                }
            });

          }
     });
  });

  }

    
    function kosong2(){
        $('#giat').combogrid('setValue','');
        $('#nmgiat').attr('value','');
        $('#anggaran').attr('value','0');
        $('#lalu').attr('value','0');
        $('#nilai').attr('value','0');                
    }


    $(function(){
            $('#kskpd_2').combogrid({  
                panelWidth:830,  
                url: '<?php echo base_url(); ?>/index.php/anggaran_spd/load_skpd_bp',  
                    idField:'kd_skpd',                    
                    textField:'nm_skpd',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_skpd',title:'SKPD',width:150},  
                        {field:'nm_skpd',title:'NAMA',align:'left',width:600}
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kd_skpd = rowData.kd_skpd;
                    nm_skpd = rowData.nm_skpd;
                    cari_skpd(kd_skpd);                                   
                    }   
                });
           });
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
     var jns_ang  = document.getElementById('jns_ang').value;
    $(function(){ 
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/anggaran_spd/load_spd_bl',
        queryParams:({cari:kriteria,jenis:jns_ang})
        });        
     });
    }
    

    function validate1(){

        var jenis = document.getElementById('jenis').value; 
        var bln1  = document.getElementById('bulan1').value;
        var kode  = document.getElementById('skpd').value;
        var cno   = document.getElementById('nomor').value;
    $("#bulan2").attr("value",bln1);


            $(function(){
      $('#dg1').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/rka/load_dspd_all_keg/'+jenis+'/'+kode+'/'+bln1+'/'+bln1+'/'+cno,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
         showFooter:true,
         nowrap:true
      });
      });   
        (jenis=='5') ? set_grid_rek() : set_grid();

//      set_grid();
      $('#dg1').edatagrid('reload');
    }
    
    
    function validate2(){ /*setelah memilih bulan maka langsung ambil data angkas*/
        var bln1  = angka(document.getElementById('bulan1').value);
        var bln2  = angka(document.getElementById('bulan2').value);
        var kode  = $("#skpd").combogrid("getValue"); 
        var cno   = document.getElementById('nomor').value;
        var cnomor =cno.split("/").join("123456789");
        var tgl1 = $('#tanggal').datebox('getValue');
    
        var jns_ang  = document.getElementById('jns_ang').value;

        if ((bln1==1) && (bln2!=3)){
                alert('Bulan Akhir Salah !!');
                return; 
        }

        if ((bln1==4) && (bln2!=6)){
                alert('Bulan Akhir Salah !!');
                return; 
        }

        if ((bln1==7) && (bln2!=9)){
                alert('Bulan Akhir Salah !!');
               return; 
        }

        if ((bln1==10) && (bln2!=12)){
                alert('Bulan Akhir Salah !!');
                return; 
        }

        if (bln2 < bln1){
                alert('Bulan Akhir tidak bisa lebih kecil dari Bulan awal');
                $("#bulan2").attr("value",bln1);   
          bln2=bln1;
        }

        $(document).ready(function(){
            $('#dg1').edatagrid({
               url: '<?php echo base_url(); ?>/index.php/anggaran_spd/load_dspd_bl/'+jns_ang+'/'+kode+'/'+bln1+'/'+bln2+'/'+cnomor,
                       queryParams:({ tgl:tgl1,cbln1:bln1 }),
                       idField:'id',
                       toolbar:"#toolbar",              
                       rownumbers:"true", 
                       fitColumns:"true",
                       singleSelect:"true",
                       pagination:"true",
                       pageList:[10,20,30,40,50,100,300],
                       showFooter:true,
                       nowrap:true
              });
              $('#dg1').edatagrid('reload');
              

        });   
        get_spd();
        get_total(); 
      }
    

     function get_total() {
        var bln1  = document.getElementById('bulan1').value;
        var bln2  = document.getElementById('bulan2').value;
        var kode  = document.getElementById('skpd').value;
        var cno   = document.getElementById('nomor').value;
        var jenis = document.getElementById('jenis').value;
        var kode  = $("#skpd").combogrid("getValue");
        var tgl1  = $('#tanggal').datebox('getValue');
        var cnomor=cno.split("/").join("123456789");

      $.ajax({
         url: '<?php echo base_url(); ?>/index.php/anggaran_spd/load_tot_dspd_bl/'+jenis+'/'+kode+'/'+bln1+'/'+bln2+'/'+cnomor+'/'+tgl1,
            type: "POST",
            dataType:"json",   
            success:function(data){
                        $("#total").attr("value",number_format(data.nilai,2,'.',','));
                        $("#total1").attr("value",number_format(data.nilai,2,'.',','));
                        }                                     
          });
        $("#total").attr("value",number_format(total,2,'.',','));
        $("#total1").attr("value",number_format(total,2,'.',','));

    } 

    
    function cetak(){
        var nomor = document.getElementById('nomor').value;
        $("#dialog-cetak").dialog('open');
        $('#nomor1').attr('value',nomor);
    $('#chk_spd').attr('checked', false);
    $('#chk_tambah').attr('checked', false);
    $('#cetak').attr('checked', false);
    }                 
    
    function opt(val){        
        ctk = val;
    var tnp_no=0;   
        var tambah = 0; 
        var cell = document.getElementById('cell').value; 
    if ($('#chk_spd').is(":checked")){
      tnp_no=1;
    }
        
    if ($('#chk_tambah').is(":checked")){
      tambah='Tambahan';
    }
        
        if (ctk=='1'){
            urll ='<?php echo base_url(); ?>index.php/cetak_spd/cetak_lampiran_spd1/1/'+tnp_no+'/'+tambah+'/'+cell;
        } else if (ctk=='2'){
            urll ='<?php echo base_url(); ?>index.php/cetak_spd/cetak_lampiran_spd1/1/'+tnp_no+'/'+tambah+'/'+cell;
        } else if (ctk=='3'){
            urll ='<?php echo base_url(); ?>index.php/cetak_spd/cetak_otor_spd/1/'+tnp_no+'/'+tambah+'/'+cell;
        } else if (ctk=='4'){
            urll ='<?php echo base_url(); ?>index.php/cetak_spd/cetak_otor_spd/1/'+tnp_no+'/'+tambah+'/'+cell;
        } else {
            exit();
        }             
        $('#frm_ctk').attr('action',urll);                        
    }      
     
    function submit(val){
    mode_ctk=val; 
    //echo mode_ctk;   
    /* if (ctk==''){
            alert('Pilih Jenis Cetakan');
            exit();
        } */
    
     var xxx =$('input[name="cetak"]:checked', '#frm_ctk').val();
     
     opt(xxx);
        document.getElementById("frm_ctk").submit();    
    }
    
    function tambah(){
        var kd = document.getElementById('skpd').value;
        var tgl = $('#tanggal').datebox('getValue');
        var total = document.getElementById('total').value;
        $('#dg2').edatagrid('reload');
        if (kd != '' && tgl != ''){             
            filter_giat();
            kosong2();
            $("#dialog-modal").dialog('open');
            $('#total1').attr('value',total);
            load_detail2();
        } else {
            alert('Harap Isi Kode SKPD dan Tanggal SPD') ;         
        }
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $("#dialog-cetak").dialog('close');
       kosong2();
    }    
    
    function hapus_giat(){
    var rows = $('#dg1').edatagrid('getSelected');
    var idx = $('#dg1').edatagrid('getRowIndex',rows);
     nilx = angka(rows.nilai);
         tot3 = 0;
         var tot = angka(document.getElementById('total').value);
         tot3 = tot - nilx;
         $('#total').attr('value',number_format(tot3),2,'.',',');
      $('#total1').attr('value',number_format(tot3),2,'.',',');    
         $('#dg1').datagrid('deleteRow',idx);     
    }
    
    function hapus_detail(){
        var rows = $('#dg2').edatagrid('getSelected');
        cgiat = rows.kd_kegiatan;        
        cnil = rows.nilai;
        var idx = $('#dg2').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : '+cgiat+' ,Nilai : '+cnil);
        if (tny==true){
            $('#dg2').edatagrid('deleteRow',idx);
            $('#dg1').edatagrid('deleteRow',idx);
            total = angka(document.getElementById('total1').value) - angka(cnil);            
            $('#total1').attr('value',number_format(total,2,'.',','));    
            $('#total').attr('value',number_format(total,2,'.',','));
            kosong2();
        } 
        
    }
    
    function hapus(){
        var cnomor = document.getElementById('nomor').value;
        var urll = '<?php echo base_url(); ?>index.php/anggaran_spd/hapus_spd';
        var tny = confirm('Yakin Ingin Menghapus Data, Nomor SPD : '+cnomor);
        if (tny==true){
        $(document).ready(function(){
        $.ajax({url:urll,
                 dataType:'json',
                 type: "POST",    
                 data:({no:cnomor}),
                 success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil Terhapus...!!!');   
                            $('#dg').edatagrid('reload');      
                        } else {
                            alert('Gagal Hapus...!!!');
                        }        
                 }
                 
                });           
        });
        }   
    }

    function cek_spd(){
        var cno = document.getElementById('nomor').value;
        var cno_hide = document.getElementById('nomor_hide').value;
        if(cno != cno_hide ){
        $(document).ready(function(){
            $.ajax({
                type: "POST",   
                dataType : 'json',                 
                data: ({tabel:'trhspd',field:'no_spd',no:cno}),
                url: '<?php echo base_url(); ?>/index.php/anggaran_spd/cek_simpan',
                success:function(data){                        
                    status_cek = data.pesan;
            if(status_cek==1){
              alert("Nomor SPD Sudah Ada! Silakan ubah nomor spd sebelum disimpan");
                        
              document.getElementById("nomor").focus();
                        status = '0';
                        $("#id_status").attr("value",'0');
              exit();
                        
            }else{
                $("#id_status").attr("value",'1');  
            }
                }    
            });
            }); 
        }else{
            $("#id_status").attr("value",'1');  
        }
    }
 
    function simpan2(){
        var cek = document.getElementById('cek_edit').value;      
        var cno = document.getElementById('nomor').value;
        var cno_u = document.getElementById('nomor_u').value;
        var cno_hide = document.getElementById('nomor_hide').value;
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = document.getElementById('skpd').value;
        var cnmskpd = document.getElementById('nmskpd').value;
        var cbend =  $('#bendahara').combogrid('getValue');
        var cbln1 = document.getElementById('bulan1').value;
        var cbln2 = document.getElementById('bulan2').value;
        var cketentuan = document.getElementById('ketentuan').value;
        var cpengajuan = document.getElementById('pengajuan').value;
        var cjenis = document.getElementById('jenis').value;
        var ctotal = angka(document.getElementById('total').value);
        var beban = document.getElementById('jns_ang').value;
        if(beban=='belanja'){
          cjenis=5;
        }else{
          cjenis=6;
        }
        
        if (cek=='tambah'){
                $(document).ready(function(){
                    /*simpan header*/
                    $.ajax({
                        type: "POST",    
                        dataType:'json',                            
                        data: ({tabel:'trhspd',mode_tox:'tambah',no2:cno,no:cno,tgl:ctgl,skpd:cskpd,nmskpd:cnmskpd,bend:cbend,bln1:cbln1,bln2:cbln2,ketentuan:cketentuan,pengajuan:cpengajuan,jenis:cjenis,total:ctotal,cno_u:cno_u}),
                        url: '<?php echo base_url(); ?>/index.php/anggaran_spd/simpan_spd',
                        success:function(data){
                            status = data.pesan;                    
                        }
                    });
                    /*simpan detail*/
                    $('#dg1').datagrid('selectAll');
                    var rows = $('#dg1').datagrid('getSelections');       
                    for(var p=0;p<rows.length;p++){
                      cnospd   = cno;
                      ckdgiat  = rows[p].kd_kegiatan;
                      cnmgiat  = rows[p].nm_kegiatan;
                      ckdrek   = rows[p].kd_rekening;
                      cnmrek   = rows[p].nm_rekening;
                      ckdprog  = rows[p].kd_program;
                      cnmprog  = rows[p].nm_program;
                      cnilai   = angka(rows[p].nilai);                 
                      if (p>0) {
                          csql = csql+","+"('"+cnospd+"','"+ckdgiat+"','"+cnmgiat+"','"+ckdrek+"','"+cnmrek+"','"+ckdprog+"','"+cnmprog+"','"+cnilai+"','"+cnilai+"','"+ckdgiat+"','"+cnmgiat+"')";
                       } else {
                           csql = "values('"+cnospd+"','"+ckdgiat+"','"+cnmgiat+"','"+ckdrek+"','"+cnmrek+"','"+ckdprog+"','"+cnmprog+"','"+cnilai+"','"+cnilai+"','"+ckdgiat+"','"+cnmgiat+"')";                                            
                       }                                             
                    }
                        
                    $(document).ready(function(){
                        $.ajax({
                                 type: "POST",    
                                 dataType:'json',                    
                                 data: ({tabel:'trdspd',no:cno,no2:cno_hide,sql:csql}),
                                 url: '<?php echo base_url(); ?>/index.php/anggaran_spd/simpan_spd/simpan_detail',
                                 success:function(data){
                                     status = data.pesan;
                                      if (status=='1'){               
                                           alert('Data Berhasil Tersimpan...!!!');
                                           $("#nomor_hide").attr("value",cno);
                                            $('#dg').edatagrid('reload');
                                            section1();
                                       } else{ 
                                           alert('Data Gagal Tersimpan...!!!');
                                       }                                              
                                  }                                        
                              });
                    });   
                }); 

        } else if(cek=='edit'){
                

        }
                        
    }
    
    function simpan_spd(){
        
        var cno      = document.getElementById('nomor').value;
        var cno_hide = document.getElementById('nomor_hide').value;
        var ctgl     = $('#tanggal').datebox('getValue');
        var skpd     = document.getElementById('skpd').value;
        var cnmskpd  = document.getElementById('nmskpd').value;
        var cbend    =  $('#bendahara').combogrid('getValue');
        var bln1     = document.getElementById('bulan1').value;
        var bln2     = document.getElementById('bulan2').value;
        var cketentuan = document.getElementById('ketentuan').value;
        var cpengajuan = document.getElementById('pengajuan').value;
        var cjenis   = document.getElementById('jenis').value;
        var ctotal   = angka(document.getElementById('total').value);
        
           var cekangkas = document.getElementById('total_angkas').value;
    if(cekangkas!='0'){
          alert('Ada Selisih Anggaran Kas Silahkan Perbaiki Terlebih Dahulu !');
        
        }

        if (cbend==""){
            alert('Harap Isi Bendahara !!');   
            return;      
        }

        if (cno==''){
            alert('Nomor SPD Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal SPD Tidak Boleh Kosong');
            exit();
        }
        if (skpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        
        if (cbend==''){
            alert('Bendahara Tidak Boleh Kosong');
            exit();
        }

        if(cno != cno_hide ){
            $(document).ready(function(){
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({tabel:'trhspd',field:'no_spd',no:cno,awal:bln1,akhir:bln2,skpd:skpd}),
                    url: '<?php echo base_url(); ?>/index.php/anggaran_spd/cek_simpan_spd',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                            alert("Nomor Atau SPD Periode Ini Sudah Ada ! Silahkan Cek Kembali !");                             
                            document.getElementById("nomor").focus();
                            status = '0';
                            $("#id_status").attr("value",'0');
                            return;
                        }else{
                            $("#id_status").attr("value",'1'); 
                            simpan2(); 
                        }
                    }    
                });
            }); 
        }else{
            $("#id_status").attr("value",'1');  
            simpan2();
        }    
    }
    
    
    function spdlalu(cgiat){
        var dgiat = cgiat; 
        var dtgl = $('#tanggal').datebox('getValue');    
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/rka/spd_lalu',
                data: ({cgiat:dgiat,ctgl:dtgl}), 
                dataType:"json",              
                success:function(data){
                    $.each(data, function(i,n){
                        cspdLalu = number_format(n['lalu'],2,'.',',');
                        $("#lalu").attr("value",cspdLalu);
                   });
                }
            });
        });
                
    }
    
    function sisa_spd(){
        var ang = angka(document.getElementById('anggaran').value);
        var lalu = angka(document.getElementById('lalu').value);
        var nil = angka(document.getElementById('nilai').value)  ;
        
        sisa = ang - lalu;
        slalu = (sisa - nil);    
        if (slalu < 0){
                alert('Nilai Melebihi SPD Lalu');
                exit();                
        }
    }


    function tes(){
        urrl= '<?php echo base_url(); ?>/index.php/rka/sql_tes'
       $(document).ready(function(){
            $.post(urrl,({no:'1'}),function(data){
                status=data;
                if (status =='1'){
                    alert('ok');
                }else{
                    alert(status);
                }
            });
        });
    }
    
    
    function bend(c){                
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:c}),
            url:"<?php echo base_url(); ?>index.php/rka/load_bendahara_p",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#bendahara").attr("value",n['nama']);
                });
            }
         });
        });
    }
                         
    function edit_rekening(kdkegiatan,nmkegiatan,nilai1,nlalu,nilai_ag,kdrek,nmrek){

        $("#nm_rek_kegi").attr("Value",nmkegiatan);
    $("#rek_kegi").attr("disable");
    $("#rek_kegi").attr("Value",kdkegiatan);
    $("#real_keg").attr("Value",'999,999,999,999,999,999.00');
        $("#txtanggkas").attr("Value",'0.00');
    $("#rek_rek").attr("disable");
    $("#rek_rek").attr("Value",kdrek);
    $("#nm_rek_rek").attr("Value",nmrek);
    $("#rek_nilai").attr("Value",number_format((angka(nilai1)),2,'.',','));
    $("#rek_nilai_lalu").attr("Value",number_format((angka(nlalu)),2,'.',','));
        
    $("#rek_nilai_anggaran").attr("Value",number_format((angka(nilai_ag)),2,'.',','));
    
        var vnilai7=angka(nilai_ag)-angka(nlalu); 
    var sisa=number_format(vnilai7,2,'.',',');
        
    $("#rek_sisa").attr("Value",sisa);       
        get_realisasi_spd();
        get_anggkas_keg();
        
/*       var xx=angka(nilai1);
       xx7=number_format(xx,2,'.',',');      
           $("#rek_nilai").attr("Value",xx7);
 */      $("#dialog-edit_nilai").dialog('open'); 
                            
        }
  


  
  
     function tombol(st){  
     if (st=='1'){

     $('#del').linkbutton('disable');
     $('#poto').linkbutton('disable');       
    $("#bulan1").attr("disabled", true);    
        $("#bulan2").attr("disabled", true); 
    $("#nomor").attr("disabled", true);    
    $("#nomor_urut").attr("disabled", true);    
    document.getElementById("id_aktif").innerHTML="NON Aktifkan SPD";
    document.getElementById("p1").innerHTML="<b style='font-size:17px;color: #0ad13f;'>SPD AKTIF</b>";
     } else {

     $('#del').linkbutton('enable');
     $('#poto').linkbutton('enable');
    $("#bulan1").attr("disabled", false);    
        $("#bulan2").attr("disabled", false); 
    $("#nomor").attr("disabled", false);
    $("#nomor_urut").attr("disabled", false);     
    document.getElementById("id_aktif").innerHTML="Aktifkan SPD";
    document.getElementById("p1").innerHTML="<b style='font-size:17px;color: red;'>SPD TIDAK AKTIF</b>";
     }
    }
  

   function tampil_semua(){ 

   var cekangkas = document.getElementById('total_angkas').value;
    if(cekangkas!='0'){
          alert('Ada Selisih Anggaran Kas Silahkan Perbaiki Terlebih Dahulu !');
        
           // $('#tampil').linkbutton('disable');
        }else{ 
    $('#dg1').edatagrid({
      idField:'id',            
      rownumbers:"true", 
      fitColumns:"true",
      singleSelect:"true",
      autoRowHeight:"false",
      pagination:"true",
      pageList:[300],
      onLoadSuccess:function(data){
        cek_total_grid();   
      }    
    });
    alert("Tunggu Sebentar.... Sampai Tabel Dibawah Berwana Putih!!");
        if(document.getElementById("id_aktif").innerHTML=="Aktifkan SPD"){
                  $('#save').linkbutton('enable');            
        }
        //cek_spd();
     }
   }

   
  function cek_total_grid(){
    var data = $('#dg1').datagrid('getData');
    var rows = data.rows;
    var sum = 0;
 
    for (i=0; i < rows.length; i++) {
      sum+=angka(rows[i].nilai);
    }
    $("#total").attr("value",number_format(sum,2,'.',','));
  }  
    
    function get_realisasi_spd(){
    var a = document.getElementById('skpd').value;
        var b = document.getElementById('rek_kegi').value;
        var bln2  = angka(document.getElementById('bulan2').value);
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,cbln2:bln2}),
            url:"<?php echo base_url(); ?>index.php/rka/get_realisasi_keg_spd",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#real_keg").attr("value",n['nrealisasi']);
                });
            }
         });
        });
    }    


    function get_anggkas_keg(){
    var a = document.getElementById('skpd').value;
        var b = document.getElementById('rek_kegi').value;
        var bln1  = angka(document.getElementById('bulan1').value);
        var bln2  = angka(document.getElementById('bulan2').value);
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,cbln2:bln2,cbln1:bln1}),
            url:"<?php echo base_url(); ?>index.php/rka/get_anggkas_keg",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#txtanggkas").attr("value",n['vanggkas']);
                });
            }
         });
        });
    }  
    
    function cekbln_akhir(kd_s){
    var a = kd_s;
    var b = '52';
        //var bln1  = angka(document.getElementById('bulan1').value);
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,jenis:b}),
            url:"<?php echo base_url(); ?>index.php/rka/bln_spdakhir",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#txtblnakhir").attr("value",n['cbulan_akhir']);
                });
            }
         });
        });
    } 
   

   function cari_skpd(skpd){
     var kriteria = skpd;
     var jns_ang  = document.getElementById('jns_ang').value;
        $(function(){ 
           $('#dg').edatagrid({
           url: '<?php echo base_url(); ?>/index.php/anggaran_spd/load_spd_bl_angkas',
           queryParams:({cari:kriteria,beban:jns_ang})
        });        
     });
    } 


    </script>

</head>
<body> 
<?php 
if($jenis=='belanja'){
  $beban=5;
}else{
  $beban=6;
}


 ?>
<input type="text" value="<?php echo $jenis ?>" name="jns_ang" id="jns_ang" hidden>
<input type="text" name="cek_edit" id="cek_edit" hidden>
<div id="content">    
<div id="accordion">
<h3><a href="#" id="section1">List SPD BELANJA</a></h3>
    <div>



                        
    </p> 
    <p align="left">
              <input type="text" name="kskpd_2" id="kskpd_2" style="width:700px;" />  
                      <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
                <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();section2();">Tambah</a>
      
    </p>
          <table id="dg" title="List SPD BELANJA" style="width:1024PX;height:450px;" >  
        </table>  
    </div>   

<h3><a href="#" id="section2"><b id="p1"></b></a></h3>
   <div  style="height: 350px;">
   <p>     
        <table align="center" border='0' style="width:100%;">
        
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                <td colspan="5" style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;</td>
            </tr>                        

           <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">S K P D</td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">&nbsp;<input id="skpd" name="skpd" style="width: 350px"  onchange="javascript:kosong();" /></td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Nama SKPD :</td> 
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;&nbsp;<input type="text" id="nmskpd" style="border:0;width: 350px;"  readonly="true"/></td>                                
            </tr>

      
      
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">No. S P D</td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">
                    &nbsp;<input type="text" id="nomor"  readonly="true" style="width: 343px;" />
                    <input  type="hidden" id="nomor_hide" style="width: 20px;" onclick="javascript:select();" readonly="true"/>
                <input  type="hidden" id="nomor_u" style="width: 20px;" readonly="true"/>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Tanggal SPD</td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;&nbsp;<input type="text" id="tanggal" style="width: 240px;" /></td>     
            </tr>                        
            
              <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Atas Beban</td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"><input type="text" name="jenis" id="jenis" value="<?php echo $beban ?>" hidden><b> <?php echo $jenis ?><b>
        </td>                
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Kebutuhan Bulan
                <input  type="hidden" id="txtblnakhir" style="width: 20px;" readonly="true"/>
                </td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;<?php echo $this->rka_model->combo_bulan('bulan1','onchange="javascript:get_spd();"'); ?> s/d <?php echo $this->rka_model->combo_bulan('bulan2','onchange="javascript:validate2();"'); ?>
                </td>
                
            </tr>                        
            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Ketentuan Lain</td>
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;" colspan="4"><textarea type="text" id="ketentuan" style="margin: 5px 0px 10px 5px; width: 338px; height: 69px;" ></textarea><input hidden=true type="text" id="pengajuan"/></td>
            </tr>   

            <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Bendahara</td>
                <td colspan ="4" style="border-bottom-style:hidden;padding:5px;border-spacing:5px 5px 5px 5px;">
                <input type="text"  name="nip" id="bendahara" style="width:350px" /> &nbsp;
                <input type ="input" readonly="true" style="border:hidden" id="nama_bend" name="nama_bend" style="width:350px; background-color: transparent;" />
                 <input type="hidden" readonly="true" style="border:hidden" id="id_status" name="id_status" value="1"/>
                </td>
                
            </tr>
            <tr style="padding:3px;border-spacing:5px 5px 5px 5px;">
                <td style="padding:3px;border-spacing:5px 5px 5px 5px;border-bottom-style:hidden;" colspan="5" align="right">
        <a class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="javascript:aktif_spd();" align="left"><b id="id_aktif"></b></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();get_spd();">Tambah</a>
                   <!--  <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:tampil_semua();">Tampil Semua</a> -->
                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_spd();">Simpan</a>
                    <a id="del"    class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
                    <a id="cetak"  class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>                                   
                </td>
            </tr>
            
            <tr style=";padding:3px;border-spacing:5px 5px 5px 5px;">
                <td colspan="5" style="padding:3px;border-spacing:5px 5px 5px 5px;border-bottom-color:black;">&nbsp;</td>
            </tr>                        

            
        </table>          
        
        <table id="dg1" title="Sub Kegiatan S P D" style="width:1040px;height:600px;" > 
        </table>  
        <div id="toolbar" align="right">
        </div>
        <table align="center" style="width:100%;">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ></td>
            <td align="right">Total : <input type="text" id="total" style="font-size: large;border:0;width: 200px;text-align: right;" readonly="true"/></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td ></td>
            <td align="right"><input type="text" id="total_angkas" hidden ="true"; style="font-size: large;border:0;width: 200px;text-align: right;" readonly="true"/></td>
        </tr>
        </table>
                
   </p>
   </div>
   
</div>
</div>

<div id="dialog-cetak" title="Cetak SPD" >
    <p class="validateTips">Cetak</p>     
    <fieldset >
    <form target="_blank" method="POST" id="frm_ctk" >
    <table >
        <tr>
            <td>Nomor SPD</td>
            <td>:</td>
            <td><input type="text" id="nomor1" style="border: 0; width: 200px" name="nomor1" readonly="true" /></td>
        </tr>
        <tr><td colspan="3"><input type="radio" name="cetak" value="2" onclick="opt(this.value)" />Lampiran SPD (Layar)</td></tr>
        <tr><td colspan="3"><input type="radio" name="cetak" value="4" onclick="opt(this.value)" />Otorisasi SPD (Layar)</td></tr>
   <tr>
                <td valign="top" style="horizontal-align: center;">Penanda Tangan</td>
                &nbsp;&nbsp;<td colspan="3" style="vertical-align: center;"><input type="text" name="nip_ppkd" id="bendahara_ppkd" style="width:200px;" /><br>
        &nbsp;&nbsp;<input type ="input" readonly="true" style="border:hidden" id="nama_ppkd" name="nama_ppkd" style="width:300px;text-indent: 50px;" />
        <input type="hidden" readonly="true" style="border:hidden" id="jabatan_ppkd" name="jabatan_ppkd"/>
        <input type="hidden" readonly="true" style="border:hidden" id="pangkat_ppkd" name="pangkat_ppkd"/>
        </td>                

        </tr>
      <tr>
            <td>Cetak Tanpa Nomor SPD</td>
            <td>:</td>
            <td><input type="checkbox" id="chk_spd" style="border: 0;" name="chk_spd" value="1"/></td>
        </tr>
      <tr>
            <td>Tambahan</td>
            <td>:</td>
            <td><input type="checkbox" id="chk_tambah" style="border: 0;" name="chk_tambah" value="1"/></td>
        </tr>
      <tr>
            <td>Ukuran Cetakkan</td>  
            <td>:</td> 
            <td>&nbsp;<input type="number" id="cell" name="cell" style="width: 50px; border:1" value="1" /> &nbsp;&nbsp;</td>
          </tr>  

    </table>
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="submit()" >Print</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>
            </td>
        </tr>
    </table>
    </form>
    </fieldset>
   
</div>



</body>

</html>
