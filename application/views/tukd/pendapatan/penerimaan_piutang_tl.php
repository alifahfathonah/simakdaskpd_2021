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
    var bulan = '';
    var rekening='';
                    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height   : 450,
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
		url: '<?php echo base_url(); ?>/index.php/pendapatan/penerimaan/load_terima_tl',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true",                       
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
            align:"center"}
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
		  giat	    = rowData.kd_kegiatan;
		  tgl_tetap = rowData.tgl_tetap;
          bank = rowData.bank;
          lcidx     = rowIndex;
          lcrek_6   = rowData.kd_rek6;
          get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,lcnilai,sts,giat,tgl_tetap,bank,lcrek_6);   
        },
        onDblClickRow:function(rowIndex,rowData){
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

     function validate_rek(){
	  	$(function(){
        $('#rek').combogrid({  
           panelWidth : 700,  
           idField    : 'kd_rek5',  
           textField  : 'kd_rek5',  
           mode       : 'remote',
           url        : '<?php echo base_url(); ?>index.php/pendapatan/penetapan/ambil_rek_tetap/'+kode,             
           columns    : [[  
		       {field:'kd_rek5',title:'Kode Rek LRA',width:100},  
               {field:'kd_rek',title:'Kode Rek LO',width:100},
			   {field:'nm_rek',title:'Uraian Rinci',width:200},
			   {field:'nm_rek4',title:'Uraian Obyek',width:200},
                {field:'kd_kegiatan',title:'Kegiatan',width:200}
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
       var a2 = "TBPL";
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
       
     function get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,lcnilai,sts,giat,tgl_tetap,bank,lcrek_6){
	   var nox = parts = nomor.split("/");
        var nox1 = nox[0];var nox2 = nox[1];var nox3 = nox[2];var nox4 = nox[3];var nox5 = nox[4];var nox6 = nox[5];var nox7 = nox[6];
        var hasil = "/"+nox2+"/"+nox3+"/"+nox4+"/"+nox5+"/"+nox6+"/"+nox7;
        
        $("#nomor_urut").attr("value",nox1);        
         $("#notetap").combogrid("setValue",no_tetap);
        $("#nomor").attr("value",nomor);
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
    }
    
    
    function kosong(){        
        $("#nomor").attr("value",'');
        $("#nomor_hide").attr("value",'');
        $("#tanggal").datebox("setValue",'');
		$("#nilai").attr("value",'');        
        $("#rek").combogrid("setValue",'');
        $("#jenis_rinci").combogrid("setValue",'');    
        $("#nm_rinci").attr("value",'');      
        $("#rek1").attr("Value",'');
        $("#nmrek").attr("value",'');
		$("#giat").attr("Value",'');
        $("#ket").attr("value",'');
        $("#notetap").combogrid("setValue",'');        
        $("#tgltetap").attr("value",'');
        $("#status").attr("checked",false);      
        $("#tagih").hide();
        document.getElementById("nomor").focus();         
        lcstatus = 'tambah';       
        get_urut();
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/pendapatan/penerimaan/load_terima_tl',
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
        var kegi      = document.getElementById('giat').value;
        var lcket    = document.getElementById('ket').value;
        var lntotal  = angka(document.getElementById('nilai').value);
            lctotal  = number_format(lntotal,0,'.',',');
        var kegi2    = kegi.substr(0,21);    
            
        var cno = cnourut+cnotmbahan;
		var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			alert('Tahun tidak sama dengan tahun Anggaran');
			exit();
		}

        
        if (lntotal=='undefined' || lntotal==0){
            alert('Nilai Tidak Boleh Kosong');
            exit();
        }         
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
                    data: ({no:cno,tabel:'tr_terima',field:'no_terima'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
					
           
            lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_kegiatan,   kd_rek5,   kd_rek_lo,     nilai,         keterangan, jenis, urut, bank, kd_rek6, kd_sub_kegiatan  ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '', '', '', '"+cskpd+"', '"+kegi+"',  '"+rek+"', '"+rek+"', '"+lntotal+"', '"+lcket+"', '2', '"+cnourut+"', '"+jns_tbp+"', '"+lckdrek_6+"','"+kegi+"' ) ";
            
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
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide){
						alert("Nomor Bisa dipakai");
			//mulai	
            
           lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_kegiatan,   kd_rek5,   kd_rek_lo,     nilai,         keterangan, jenis, urut, bank, kd_rek6, kd_sub_kegiatan   ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '', '', '', '"+cskpd+"', '"+kegi+"',  '"+rek+"', '"+rek+"', '"+lntotal+"', '"+lcket+"', '2', '"+cnourut+"', '"+jns_tbp+"', '"+lckdrek_6+"','"+kegi+"' ) ";
            
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
        judul = 'Input Data Penerimaan Atas Piutang Tahun Lalu';
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
                    $('#dg').datagrid('deleteRow',lcidx);   
                    alert('Data Berhasil Dihapus..!!');
                    $("#dg").edatagrid("unselectAll") ;
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
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PENERIMAAN ATAS PIUTANG TAHUN LALU</a></b></u></h3>
    <div>
    <p align="right">         
        <button class="button button" onclick="javascript:tambah();kosong()"><i class="fa fa-tambah"></i> Tambah</button>
        <button class="button button-merah" onclick="javascript:hapus();"><i class="fa fa-hapus"></i> Hapus</button>        
        <input type="text" value="" class="input" style="display: inline; width: 200px" onkeyup="javascript:cari();" placeholder="Pencarian: Ketik dan enter" id="txtcari"/>
        <table id="dg" title="Listing data Penerimaan Atas Piutang Tahun Lalu" style="width:1024px;height:450px;" >  
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
                <td>No. Terima</td>
                <td></td>
                <td><input type="text" class="input" id="nomor_urut" style="width: 50px; display: inline;"/><input type="text" id="nomor" class="input" style="width: 400px; display: inline;"/><input type="hidden" id="nomor_hide" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tanggal </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 230px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" class="input" name="skpd" style="width: 220px;" /> </td>                            
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Rekening</td>
                <td></td>
                <td><input id="rek" name="rek" style="width: 230px;" /> <input id="rek1" style="border:0;width: 80px;" readonly="true"/>
                 <input type="text" id="nmrek" style="border:0;width: 400px;" readonly="true"/></td>                
            </tr> 
            <tr hidden>
                <td>Sub. Rek</td>
                <td></td>
                <td><input class="input" id="jenis_rinci" name="jenis_rinci" style="width: 168px; display: inline;" />
                 <input type="text" id="nm_rinci" style="border:0;width: 400px;" readonly="true"/></td>                
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td></td>
                <td><input type="text" class="input" id="giat" style="width: 220px;" readonly="true"/>
                 </td>                
            </tr> 
            <tr>
                <td>Jenis Penerimaan</td>
                <td></td>
                <td><select class="select" id="jns_tbp" name="jns_tbp" style="width: 230px;" onclick="javascript:runjns();">
                <!--<option value="TN">Tunai</option>-->
                <option value="BNK">Bank</option>
                </select>               
                 </td>                
            </tr>                 
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" class="input" id="nilai" style="width: 220px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" class="textarea" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
            <tr>
                <td colspan="3" align="center">
                  <button class="button button-biru" onclick="javascript:simpan_terima();"><i class="fa fa-simpan"></i> Simpan</button>
                  <button class="button button-abu" onclick="javascript:keluar();"><i class="fa fa-kiri"></i> Kembali</button>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>