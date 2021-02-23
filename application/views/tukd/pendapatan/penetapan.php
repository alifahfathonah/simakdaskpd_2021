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
    var sgiat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0;
    var lcidx    = 0;
    var lcstatus = '';
    var curut    = '';
    var tahun_anggaran = '<?php echo $this->session->userdata('pcThang'); ?>';
    var bulan = '';
    var rekening='';
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 500,
            width: 900,
            modal: true,
            autoOpen:false,
        });
        get_skpd();
        get_urut();
        });    
    
     
     $(function(){ 
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/pendapatan/penetapan/load_tetap',
        idField       : 'id',            
        rownumbers    : "true", 
        fitColumns    : "true",
        singleSelect  : "true",
        autoRowHeight : "false",
        loadMsg       : "Tunggu Sebentar....!!",
        pagination    : "true",
        nowrap        : "true", 
        rowStyler: function(index,row){
        if (row.stt_terima == 1){
          return 'background-color:#03d3ff;';
        }
        },                      
        columns:[[
          {field:'no_tetap',
        title:'Nomor Tetap',
        width:50,
            align:"left"},
            {field:'tgl_tetap',
        title:'Tanggal',
        width:30},
            {field:'kd_skpd',
        title:'S K P D',
        width:30,
            align:"center"},
            {field:'kd_rek',
        title:'Rekening',
        width:50,
            align:"center"},
            {field:'nilai',
        title:'Nilai',
        width:50,
            align:"right"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor   = rowData.no_tetap;
          tgl     = rowData.tgl_tetap;
          kode    = rowData.kd_skpd;
          lcket   = rowData.keterangan;
          lcrek   = rowData.kd_rek5;
          nm_rek5   = rowData.nm_rek5;
          lcrek6   = rowData.kd_rek6;
          nm_rek6   = rowData.nm_rek6;
          rek     = rowData.kd_rek;
          lcnilai = rowData.nilai;
          ksub     = rowData.kd_kegiatan;
          //kbid = rowData.bidang;
          lcidx   = rowIndex;
          get(nomor,tgl,kode,lcket,lcrek,rek,lcnilai,nm_rek5,ksub,lcrek6,nm_rek6);   
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Penetapan'; 
           edit_data();   
           lcstatus = 'edit';
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
              return y+'-'+m+'-'+d;},
                onSelect: function(date){
              var m = date.getMonth()+1;
          bulan = m;
                    get_hasil();
        }            
        });
    
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
      

        
     function get_urut()
        {
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/pendapatan/penetapan/config_pnp',
            type: "POST",
            dataType:"json",                         
            success:function(data){
              curut = data.nomor;
                    $("#nomor_urut").attr("value",curut);
                    
              }                                     
          });
             
        }   
        
    function get_hasil(){
       var a1 = document.getElementById('nomor_urut').value;
       var a2 = "PTP";
       var a3 = kode;
       var a4 = rekening;
       var a5 = bulan;
       var a6 = tahun_anggaran;
       var hasil = "/"+a2+"/"+a3+"/"+a4+"/"+a5+"/"+a6;
       $("#nomor").attr("value",hasil);
    }    
        
     function validate_rek(){
      $(function(){
        $('#rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/pendapatan/penetapan/ambil_rek_tetap/'+kode,             
           columns:[[  
               {field:'kd_rek5',title:'Kode Rek LRA',width:100},  
               {field:'kd_rek',title:'Kode Rek LO',width:100, hidden: true},
               {field:'nm_rek',title:'Uraian Rinci',width:350},
               {field:'nm_rek4',title:'Uraian Obyek',width:350, hidden: true},
                {field:'kd_kegiatan',title:'Kegiatan',width:180}
               
              ]],
              
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek.toUpperCase());
               $("#rek1").attr("value",rowData.kd_rek5);
                $("#sgiat").attr("value",rowData.kd_kegiatan);
                $("#nmsgiat").attr("value",rowData.nm_kegiatan);
                rekening = rowData.kd_rek5;
                get_hasil();
                validate_combox(rowData.kd_rek5);
                //tambahan = "/PTP/"+kode+"/"+rekening+"/"+bulan+"/"+tahun_anggaran;
                //$("#nomor").attr("value",tambahan);
                
              }    
            });
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
                  
    function get(nomor,tgl,kode,lcket,lcrek,rek,lcnilai,nm_rek5,ksub,lcrek6,nm_rek6){
        var nox = parts = nomor.split("/");
        var nox1 = nox[0];var nox2 = nox[1];var nox3 = nox[2];var nox4 = nox[3];var nox5 = nox[4];var nox6 = nox[5];
        var hasil = "/"+nox2+"/"+nox3+"/"+nox4+"/"+nox5+"/"+nox6;
        $("#nomor_urut").attr("value",nox1);
        $("#nomor").attr("value",hasil);
        $("#nomor_hide").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#rek").combogrid("setValue",rek);
        $("#jenis_rinci").combogrid("setValue",lcrek6);
        $("#nm_rinci").attr("Value",nm_rek6);
        $("#rek1").attr("Value",lcrek);
        $("#sgiat").attr("Value",ksub);
        $("#nilai").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        $("#nmrek").attr("value",nm_rek5);
    }
    
    function kosong(){
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#jenis_rinci").combogrid("setValue",'');
        $("#nm_rinci").attr("Value",'');
        $("#rek").combogrid("setValue",'');
        $("#rek1").attr("Value",'');
        $("#nmrek").attr("value",'');
        $("#nilai").attr("value",'');        
        $("#ket").attr("value",'');         
        lcstatus = 'tambah';    
        get_urut();   
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/pendapatan/penetapan/load_tetap',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    
    function simpan_tetap(){
        
        var cnourut      = document.getElementById('nomor_urut').value;
        var cnotmbahan      = document.getElementById('nomor').value;
        var cno_hide = document.getElementById('nomor_hide').value;

        var ctgl     = $('#tanggal').datebox('getValue');
        var cskpd    = document.getElementById('skpd').value;
        var cnmskpd  = document.getElementById('nmskpd').value ;
        var lckdrek  = $('#rek').combogrid('getValue');
        var lckdrek_6  = $('#jenis_rinci').combogrid('getValue');
        var rek      = document.getElementById('rek1').value;
        var subkegi      = document.getElementById('sgiat').value;
        var lcket    = document.getElementById('ket').value;
        var lntotal  = angka(document.getElementById('nilai').value);
            lctotal  = number_format(lntotal,0,'.',',');
            
        var kegi = subkegi.substr(0,21);
        var depan = subkegi.substr(0,12);
        var belakang = subkegi.substr(16,8);
        
        var cno = cnourut+cnotmbahan;    
        var nox = parts = cno.split("/");
        var nox1 = nox[0];var nox2 = nox[1];var nox3 = nox[2];var nox4 = nox[3];var nox5 = nox[4];var nox6 = nox[5];
        
        if (nox5==''){
            alert('Tanggal STS Tidak Boleh Kosong');
            exit();
        }
            
        if (cno==''){
            alert('Nomor STS Tidak Boleh Kosong');
            exit();
        } 
    var tahun_input = ctgl.substring(0, 4);
    
    if (tahun_input != tahun_anggaran){
      alert('Tahun tidak sama dengan tahun Anggaran');
      exit();
    }
    
        if (ctgl==''){
            alert('Tanggal STS Tidak Boleh Kosong');
            exit();
        }
    
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        
    if ( lcstatus == 'tambah'){
        $(document).ready(function(){
            lcinsert        = " ( no_tetap,  tgl_tetap,  kd_skpd,     kd_sub_kegiatan,     kd_rek5,   kd_rek_lo,     nilai,        keterangan,    urut,        kd_rek6) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+cskpd+"', '"+subkegi+"', '"+lckdrek+"', '"+lckdrek+"', '"+lntotal+"', '"+lcket+"' , '"+cnourut+"','"+lckdrek+"') ";

            $(document).ready(function(){
              $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/pendapatan/penetapan/simpan_tetap_ag',
                data     : ({tabel       :'tr_tetap',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_tetap',   lcid       :cno}),
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
                          }
                }
              });
            });   
        });      
      } else { 

        $(document).ready(function(){
          lcinsert        = " ( no_tetap,  tgl_tetap,  kd_skpd, kd_sub_kegiatan,     kd_rek5,   kd_rek_lo,     nilai,         keterangan, urut, kd_rek6) ";
          lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+cskpd+"', '"+subkegi+"', '"+lckdrek+"', '"+lckdrek+"', '"+lntotal+"', '"+lcket+"' , '"+cnourut+"','"+lckdrek+"') ";
          $(document).ready(function(){
            $.ajax({
              type     : "POST",
              url      : '<?php echo base_url(); ?>/index.php/pendapatan/penetapan/update_tetap_ag',
              data     : ({tabel       :'tr_tetap',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_tetap',   lcid       :cno,no_hide:cno_hide}),
              dataType : "json",
              success  : function(data){
                   status=data ;
                   if ( status=='2' ){
                        alert('Data Tersimpan...!!!');
                        lcstatus = 'edit';
                        $("#nomor_hide").attr("Value",cno) ;
                        $("#dialog-modal").dialog('close');
                        $('#dg').edatagrid('reload');
                  }else{
                        alert('Gagal Simpan...!!!');
                        exit();                
                  }
              }
            });
          });

       });
      }
       
    }
    
    
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penetapan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul    = 'Input Data Penetapan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        } 
     
     
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     
     function hapus(){
        var urll = '<?php echo base_url(); ?>index.php/pendapatan/penetapan/hapus_tetap';
    var del=confirm('Anda yakin akan menghapus Nomor Penetapan '+nomor+'  ?');
    if  (del==true){
      $(document).ready(function(){
       $.post(urll,({no:nomor,skpd:kode}),function(data){
        status = data;
        if (status=='0'){
          alert('Gagal Hapus..!!');
          exit();
        } else {
          $('#dg').datagrid('deleteRow',lcidx);   
          alert('Data Berhasil Dihapus..!!');
          exit();
        }
       });
      });   
    }
    } 
    
  
   </script>

</head>
<body>

<div id="content">
<p><font color="red">Perhatian : Jika ada Penerimaan Atas Piutang Tahun Lalu, maka Langsung Input ke Penerimaan, Tanpa Input ke Penetapan</font></p> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PENETAPAN</a></b></u></h3>

    <div>    
    <p>   
               
        <button class="button" onclick="javascript:tambah();"><i class="fa fa-tambah"> Tambah</i></button>
        <button class="button button-merah" onclick="javascript:hapus();"><i class="fa fa-hapus"> Hapus</i></button>
        <input type="text" class="input" style="display: inline; width: 200px" value="" onkeyup="javascript:cari();" id="txtcari" placeholder="Pencarian: Ketik dan enter" />
        <table align="left" id="dg" title="Listing data penetapan" style="width:1024px;height:450px;" >  
        </table> 
    </p> 
    </div>       
</div>

</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td>No. TETAP</td>
                <td></td>
                <td><input class="input" disabled type="text" id="nomor_urut" style="width: 50px; display: inline;"/>
                  <input readonly type="text" id="nomor" class="input" style="width: 400px; display: inline;"/><input type="hidden" id="nomor_hide" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tanggal </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 230px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd"  style="width: 140px; border:0;" readonly="true" />  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>
            <!--<tr>
                <td>BIDANG</td>
                <td></td>
                <td><input id="bidang" name="bidang"  style="width: 170px; border:0;" readonly="true" />  <input type="text" id="nmbidang" style="border:0;width: 350px;" readonly="true"/></td>                            
            </tr>-->
            <tr>
                <td>Rekening</td>
                <td></td>
                <td><input id="rek" name="rek" style="width: 230px;" /> <input type="hidden" id="rek1" style="width: 140px;" readonly="true"/>
                 <input type="text" id="nmrek" style="border:0;width: 400px;" readonly="true"/></td>                
            </tr>
            <tr hidden>
                <td>Sub. Rek</td>
                <td></td>
                <td><input id="jenis_rinci" name="jenis_rinci" style="width: 230px;" />
                 <input type="text" id="nm_rinci" style="border:0;width: 400px;" readonly="true"/></td>                
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td></td>
                <td><input class="input" type="text" id="sgiat" style="width: 220px; display: inline;" readonly="true"/>
                <input type="text" id="nmsgiat" style="border:0;width: 400px;" readonly="true"/></td>      
                 </td>                
            </tr>            
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input class="input" type="text" id="nilai" style="width: 220px;  text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea  class="textarea" rows="2" cols="50" id="ket" style="width: 740px; height: 100px;"></textarea>
                </td> 
            </tr>
            <tr>
                <td colspan="3" align="center">
                  <button class="button-biru" onclick="javascript:simpan_tetap();"><i class="fa fa-save"></i> Simpan</button>
                  <button class="button-abu" onclick="javascript:keluar();"><i class="fa fa-kiri"></i> Kembali</button>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
    
</body>
</html>