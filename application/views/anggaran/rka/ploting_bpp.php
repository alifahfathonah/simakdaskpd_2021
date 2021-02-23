  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
      
    $(document).ready(function()  
  { 

  });  
    
  var idx=0;
  var tidx=0;
  var oldRek=0;
    var skpd='';
    var urusan='';
        
    
    $(document).ready(function() {

            $("#dialog-modal").dialog({
                height: 500,
                width: 600,
                modal: true,
                autoOpen:false                
            });

            $("#dialog-modal").dialog("close");
      $('#dg').edatagrid();
      $("#giat").combogrid();


  $('#dg').edatagrid({});
    $('#kd_bank').combogrid({  
           panelWidth:700,  
           idField:'kode',  
           textField:'kode',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_bank',
           columns:[[  
               {field:'kode',title:'Kode',width:150},  
               {field:'nama',title:'Nama',width:500}    
           ]],  onSelect:function(rowIndex,rowData){
                    $("#nm_bank").attr("value",rowData.nama);
                    }   
           });




    $('#Xskpd').combogrid({  
            panelWidth:750,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/bpp_ploting/skpd',  
            columns:[[  
                {field:'kode',title:'Kode',width:50, align:'center', hidden:'true'},  
                {field:'nm_skpd',title:'BPP',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                 validate_combo(rowData.kd_skpd);
                 pilih_kegiatan(rowData.kd_skpd);       
            }  
            });   


    });    
  
      function pilih_kegiatan(kodeuser=''){

    $(document).ready(function() {
        $('#kd_sub_kegiatan').combogrid({  
            panelWidth:810,  
            idField:'kd_sub_kegiatan',  
            textField:'kd_urusan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/bpp_ploting/subkeluar/'+kodeuser,  
            columns:[[  
                {field:'kd_sub_kegiatan',title:'Kode Subkegiatan',width:110},  
                {field:'nm_sub_kegiatan',title:'Nama Subkegiatan',width:700}     
            ]],
            onSelect:function(rowIndex,rowData){
                if(rowData.kd_sub_kegiatan=='BPP ini'){
                  exit();
                }
               simpan(rowData.kd_sub_kegiatan);      
            } 
        }); 
        });  
      }


        function validate_combo(user){
            $(function(){
            $("#dg").datagrid("unselectAll");
      $('#dg').edatagrid({
        queryParams:({user:user}),
        url: '<?php echo base_url(); ?>/index.php/bpp_ploting/select_plot/',
                 idField:'id',
                 panelHeight:900,
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",

                 columns:[[
                    {field:'urut',
                     title:'Kode Subkegiatan',
                     width:20,
                     align:'center',
                     hidden:'true'
                    },
                    {field:'kd_sub_kegiatan',
                     title:'Kode Subkegiatan',
                     width:20,
                     align:'center'
                    }, 
                    {field:'nm_sub_kegiatan',
                     title:'Nama Subkegiatan',
                     width:150,
                     align:'left'
                    },
                    {field:'hapus',title:'Hapus',width:20,align:"center",
                        formatter:function(value,rec){
                        var lokasi=rec.urut;
                        return '<input value="HAPUS" style="background-color:red;" class="button" type="button" onclick="javascript:hapus('+lokasi+');" />';
                        }
                    }
        ]],
        onSelect:function(isi,index){
      
        }
      });
    });
        }  

        function simpan(subgiat){
          var skpd =$("#Xskpd").combogrid("getValue");
        $(function(){   
          $.ajax({
            type     : "POST",
            dataType : "json",
            data     : ({giat:subgiat,skpd:skpd,status:'tambah'}),
            url: '<?php echo base_url(); ?>/index.php/bpp_ploting/simpan', 
            success  : function(data){
                $("#kd_sub_kegiatan").combogrid("clear");
                    $('#kd_sub_kegiatan').combogrid({  
                        panelWidth:700,  
                        idField:'kd_sub_kegiatan',  
                        textField:'kd_sub_kegiatan',  
                        mode:'remote',
                        url:'<?php echo base_url(); ?>index.php/bpp_ploting/subkeluar',  
                    });  

                $("#dg").edatagrid("unselectAll");
                $('#dg').edatagrid('reload');
                alert("berhasil");
            }
            }); 
        }); 
        }

        function hapus(urut){
          var skpd =$("#Xskpd").combogrid("getValue");
        var del=confirm('Anda yakin akan menghapus sub keluaran dengan kode '+urut+' ?');
          if  (del==true){
            $(function(){   
              $.ajax({
                type     : "POST",
                dataType : "json",
                data     : ({skpd:skpd,urut:urut,status:'edit'}),
                url: '<?php echo base_url(); ?>/index.php/bpp_ploting/simpan', 
                success  : function(data){
                    $("#kd_sub_kegiatan").combogrid("clear");
                        $('#kd_sub_kegiatan').combogrid({  
                            panelWidth:700,  
                            idField:'kd_sub_kegiatan',  
                            textField:'kd_sub_kegiatan',  
                            mode:'remote',
                            url:'<?php echo base_url(); ?>index.php/bpp_ploting/subkeluar',  
                        });  

                    $("#dg").datagrid("unselectAll");
                    $('#dg').edatagrid('reload');
                    alert("berhasil");
                }
                }); 
            }); 
          }
        
    }

    function tambah_bpp(){
     
      var kdskpd="<?php echo $this->session->userdata('kdskpd') ?>";
      $(function(){   
              $.ajax({
                type     : "POST",
                dataType : "json",
                data     : ({skpd:kdskpd}),
                url: '<?php echo base_url(); ?>/index.php/bpp_ploting/username', 
                success  : function(data){
                    $.each(data, function(i,n){
                      $("#userbpp").attr("value",n['userbaru']);
                      $("#kodebpp").attr("value",n['kode']);
                         
                    });     
                     $("#dialog-modal").dialog("open");
                }
                }); 
            }); 
    }

  function simpan_user_bpp_baru(){
     var userbpp= document.getElementById('userbpp').value;
     var kodebpp = document.getElementById('kodebpp').value;
     var  nmbpp  = document.getElementById('nmbpp').value;
     var  obnmskpd = document.getElementById('obnmskpd').value;
     var  npwp = document.getElementById('npwp').value;
     var  reke = document.getElementById('reke').value;
     var  alamat = document.getElementById('alamat').value;
     var  kdpos = document.getElementById('kdpos').value;
     var  kd_bank =$("#kd_bank").combogrid("getValue");
     var kdskpd="<?php echo $this->session->userdata('kdskpd') ?>";

      $(function(){   
              $.ajax({
                type     : "POST",
                dataType : "json",
                data     : ({skpd:kdskpd,userbpp:userbpp,kodebpp:kodebpp,nmbpp:nmbpp,obnmskpd:obnmskpd,npwp:npwp,reke:reke,alamat:alamat,kdpos:kdpos,kd_bank:kd_bank}),
                url: '<?php echo base_url(); ?>/index.php/bpp_ploting/simpan_user_bpp_baru', 
                success  : function(data){
                      if(data==1){
                        alert("berhasil");
                        $("#dialog-modal").dialog("close");
                        $("#kodebpp").attr("value","");
                        $("#nmbpp").attr("value","");
                        $("#npwp").attr("value","");
                        $("#reke").attr("value","");
                        $("#alamat").attr("value","");
                        $("#kdpos").attr("value","");
                      }
                }
                }); 
      });     
  }
  
  </script>
    
</head>
<body>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<div id="content">   
<input type="text" hidden name="egiat" id="egiat">
<button class="button" onclick="javascript:tambah_bpp();" style="margin-bottom: 10px"> Tambah User BPP</button>
<fieldset style="border-radius: 10px; margin-bottom: 10px">
  <legend>PLOTING SUB KEGIATAN</legend>
<table width="100%" border="0" cellpadding="1" cellspacing="1" >
  <tr>
    <td width="20%" style="border-style: hidden;"><h3>BPP</h3></td>
    <td width="80%" style="border-style: hidden;">: <input id="Xskpd" name="Xskpd" style="width: 200px;border: 0;" /> Jika pilihan masih kosong atau belum ada dipilihan, silahkan klik <button class="button" onclick="javascript:tambah_bpp();" style="margin-bottom: 10px"> Tambah User BPP</button></td>
  </tr>
  <tr>
    <td width="20%" style="border-style: hidden;"><h3>SUB KEGIATAN</h3></td>
    <td width="80%" style="border-style: hidden;">: <input id="kd_sub_kegiatan" name="kd_sub_kegiatan" readonly="true" style="width:200px;border: 0;"/></td>
  </tr>
</table>

</fieldset>
   <table id="dg" title="Ploting BPP" style="width:1100px;height:500px"></table>          
       <div id="dialog-modal" title="Tambah User BPP">
          <div>
<table align="center" style="width:100%;" border="0" cellspacing="5" cellspacing="5">
           <tr>
                <td width="30%"><b>Username </td>
                <td width="1%">:</td>
                <td><input disabled type="text" id="userbpp" style="width:200px;"/ placeholder="01.00"> </td>  
            </tr>
           <tr>
                <td width="30%"><b>Password</td>
                <td width="1%">:</td>
                <td><input disabled type="text" style="width:200px;"/ placeholder="bpkad"></td>  
            </tr>
           <tr >
                <td width="30%">KODE BPP</td>
                <td width="1%">:</td>
                <td><input type="text" disabled id="kodebpp" style="width:200px;"/ placeholder="00.00"></td>  
            </tr>                       
            <tr>
                <td width="30%">NAMA BPP</td>
                <td width="1%">:</td>
                <td><textarea name="nama" id="nmbpp" cols="60" rows="1" ></textarea>
                </td>  
            </tr>
            <tr>
                <td width="30%">OB SKPD (CMS)</td>
                <td width="1%">:</td>
                <td><input type="text" id="obnmskpd" style="width:200px;" placeholder="Maksimal 15 Karakter" maxlength="15"/></td>  
            </tr>
            <tr>
                <td width="30%">NPWP</td>
                <td width="1%">:</td>
                <td><input type="text" id="npwp" style="width:200px;"/></td>  
            </tr>
      <tr>
                <td width="30%">BANK</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_bank" style="width:50px;"/><input type="text" id="nm_bank" style="width:310px;"/></td>  
            </tr>
            <tr>
                <td width="30%">REKENING PENGELUARAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="reke" style="width:200px;"/></td>  
            </tr>
      <tr>
                <td width="30%">ALAMAT</td>
                <td width="1%">:</td>
                <td><input type="text" id="alamat" style="width:300px;"/></td>  
            </tr>
      <tr>
                <td width="30%">KODE POS</td>
                <td width="1%">:</td>
                <td><input type="text" id="kdpos" style="width:200px;"/></td>  
            </tr>
    
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_user_bpp_baru();">Simpan</a>

                </td>                
            </tr>
           <tr>
            
                <td colspan="3">
                  1. Password di set secara default.<br>
                  2. Untuk menggantinya silahkan Login menggunakan <b>username</b> dan <b>password</b> diatas. <br> 
                  3. Kemudian masuk menu <b>UTILITY > GANTI PASSWORD</b><br>
                  4. Untuk merubah/mengedit data yang salah ke menu <b> Master SKPD</b>.<br>
                  5. Terima kasih.</td>  
            </tr>
        </table>   
          </div>
       </div>
 
</div>    

